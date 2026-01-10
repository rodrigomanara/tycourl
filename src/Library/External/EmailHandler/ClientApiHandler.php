<?php

namespace Codediesel\Library\External\EmailHandler;

use Codediesel\Library\External\ApiHandler;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ClientApiHandler extends ApiHandler
{
    private $apiUrl;
    private $apiKey;

    /** 
     * @param string $url
     * @param string $key
     */
    public function setUrl(string $url)
    {
        $this->apiUrl = $url;
    }
    /** 
     * @param string $key
     */
    public function setApiKey(string $key)
    {
        $this->apiKey = $key;
    }
    /** 
     * @return string
     */
    public function getUrl(): string
    {
        return $this->apiUrl;
    }
    /** 
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param array $data
     * @return mixed|string[]
     * @throws \Exception
     */
    public function sendEmail(array $data = []): mixed
    {

        if (empty($data)) {
            throw new \Exception('No data provided');
        }

        try {
            $response = $this->client(
                [
                    'base_uri' => $_ENV['EMAIL_API_URL'],
                    'auth' => [
                        $_ENV['EMAIL_API_KEY'],
                        $_ENV['EMAIL_API_SECRET']
                    ], // Basic Auth

                ]
            )
                ->post($this->getUrl(), [
                    'json' => $data
                ], [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ]
                ]);
        } catch (\Exception |\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to send email');
        }
        $responseBody = json_decode($response->getBody(), true);
        if (isset($responseBody['error'])) {
            throw new \Exception($responseBody['error']);
        }

        if (isset($responseBody['Messages'])) {
           return $responseBody['Messages'];
        }
         
        return [
            'status' => 'error',
            'message' => 'Unknown error occurred'
        ];
    }
}
