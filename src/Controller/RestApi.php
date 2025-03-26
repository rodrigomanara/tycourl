<?php

namespace Codediesel\Controller;
 
use Codediesel\Library\DataWithHeaderFormatting as DataFormatting; 
use Codediesel\Library\Api\Middleware;
use Codediesel\Exception\AuthenticationException;

class RestApi
{

    private Route $route;

    private array $urls;

    /**
     * Constructor
     *
     * @param \Codediesel\Controller\Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
        $this->urls = include __ROOT__ . DIRECTORY_SEPARATOR . "Config/restApi.php";
    }
    
    /**
     * Initialize the RestApi
     *
     * This method initializes the RestApi by processing the incoming request
     * and routing it to the appropriate handler. It also handles any exceptions
     * that may occur during the request processing.
     *
     * @return void
     */
    public function init(): void
    {
        $request = $this->route->request();
        $dataFormatting = new DataFormatting();

        try {
            $this->pathRunner($request, $dataFormatting);
        } catch (\Throwable|\Exception $e) {
            $dataFormatting->error([
                'message' => $e->getMessage(),
                'path' => $e->getFile(),
                'line code' => $e->getLine(),
            ]);
        }
        $dataFormatting->notFound(["error" => "Not found"]);
    }

    /**
     * Route the request to the appropriate handler
     *
     * This method routes the incoming request to the appropriate handler
     * based on the configured URLs. If the path is found in the configuration,
     * it initializes the corresponding class and action. If not found, it returns
     * a "Not found" error.
     *
     * @param array $request
     * @param DataFormatting $dataFormatting
     * @return void
     */
    private function pathRunner(array $request, DataFormatting $dataFormatting): void
    {
        $path = $this->path($request);

        try{
            foreach ($this->urls as $key => $value) {
                if (strpos($key, '{') !== false) {
                    // Replace placeholder {id} with the actual ID from the request
                    $compare = str_replace('{id}', $request['id'] ?? '', $key);

                    $this->route->set('id' , $request['id'] ?? null);
                    if ($compare === $path) { 
                        $this->execute($value, $dataFormatting);
                        return;
                    }
                } elseif ($key === $path) {
                    $this->execute($value ,  $dataFormatting);
                    return;
                }
            }
        }catch(AuthenticationException $e){
            $dataFormatting->notAuthorized(["error" => $e->getMessage()]);
        }

        // If no matching path is found, return a "Not found" error
        $dataFormatting->notFound(["error" => "Not found"]);
    }

    /**
     * Execute the handler for the given path
     *
     * This method executes the handler for the given path. It initializes the
     * class and action based on the configuration options and executes the
     * handler. If the class or action is not found, it returns an "Invalid class
     * or action" error.
     *
     * @param array $options
     * @param DataFormatting $dataFormatting
     * @return void
     */
    private function execute(array $options , DataFormatting $dataFormatting): void
    { 
        $class = $options['class'];
        $action = $options['action'] ?? null;
        $middleware = $options['middleware'] ?? null;

        if ($class && class_exists($class)) {

            $instanciate = new $class();
            if($middleware){
                $middleware = new Middleware($action);
 
                if(!$middleware->isAuthorise()){
                    $dataFormatting->notAuthorized(["error" => "Not authorised"]);
                }
                $role = $options['role'] ?? 'user';
                if(!$middleware->isUserAllowed($instanciate , $role)){
                    $dataFormatting->notAuthorized(["error" => "Not authorised"]);
                }
            }
            $data = $instanciate->initialize($action ?? 'create', $this->route);
            $dataFormatting->success($data); // Return success response
   
        } else {
            $dataFormatting->error(["error" => "Invalid class or action"]);
        }
    }

    /**
     * Construct the path based on the request parameters
     *
     * This method constructs the path based on the request parameters. It
     * determines the path based on the 'module', 'action', and 'id' parameters
     * in the request.
     *
     * @param array $request
     * @return string
     */
    private function path(array $request): string
    {
        // Determine the path based on the request parameters
        if (isset($request['module'], $request['action'], $request['id'])) {
            return sprintf("/%s/%s/%s", $request['module'], $request['action'], $request['id']);
        } elseif (isset($request['module'], $request['action'])) {
            return sprintf("/%s/%s", $request['module'], $request['action']);
        } elseif (isset($request['api'])) {
            return sprintf("/%s", $request['api']);
        }

        // Default return value if no path can be constructed
        return '';
    }
}