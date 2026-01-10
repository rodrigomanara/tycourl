<?php
declare(strict_types=1);

namespace Codediesel\RestApi;


use Codediesel\Library\Api\RestApiAbstract;
use Codediesel\Library\Api\RestApiInterface;
use Codediesel\Exception\DataNotFoundException;
use Codediesel\Exception\ArgumentMissingException;
use Codediesel\Model\Factory\Analitics as AnaliticsFactory;
/**
 * Analytics class for managing URL analytics data.
 * 
 * This class provides methods to retrieve analytics data for shortened URLs.
 * It extends the RestApiAbstract class and implements the RestApiInterface.
 */
class Analytics extends RestApiAbstract implements RestApiInterface
{
    /**
     * Retrieves analytics data for a specific shortened URL.
     * 
     * @param array $data Contains the 'url' key with the shortened URL.
     * @return array Returns the analytics data for the URL.
     * @throws \Exception Throws an exception if the 'url' argument is missing, data is not found, or a database error occurs.
     */
    public function retrieve(array $data): array
    {

        $data['user_id'] =  $this->route->getUserSessionID();
        if(empty($data['user_id'])) {
            throw new DataNotFoundException();
        }


        $fetch = (new AnaliticsFactory())->fetchAll([
            'user_id' => $data['user_id']
        ]);

        if (isset($fetch['error'])) {
            throw new \Exception($fetch['error']);
        }

        if (isset($fetch[0]) && $fetch[0] === false) {
            throw new DataNotFoundException("Data not found");
        }

        return $fetch;
    }


    /**
     * Retrieves analytics history for all URLs associated with a user.
     * 
     * @param array $data Contains the 'user_id' key to identify the user.
     * @return array Returns the analytics history data.
     * @throws \Exception Throws an exception if a database error occurs.
     */
    public function history(array $data): array
    {

        $data['user_id'] = $this->route->getUserSessionID();
        if (empty($data['user_id'])) {
            throw new DataNotFoundException();
        }

        $fetch = (new AnaliticsFactory())->fetchAll([
            'user_id' => $data['user_id'] ?? null,
        ],
            $data['limit'] ?? 10,
            $data['offset'] ?? 0
        );

        if (isset($fetch['error'])) {
            throw new \Exception($fetch['error']);
        }

        if (isset($fetch[0]) && $fetch[0] === false) {
            throw new DataNotFoundException("Data not found");
        }

        return $fetch;
    }

    /**
     * Deletes analytics data for a specific shortened URL.
     * 
     * @param array $data Contains the 'url' key with the shortened URL.
     * @return array Returns an empty array upon successful deletion.
     * @throws \Exception Throws an exception if the 'url' argument is missing, data is not found, or a database error occurs.
     */
    public function delete(array $data): array
    {

        $data['user_id'] = $this->route->getUserSessionID();
        if (empty($data['user_id'])) {
            throw new DataNotFoundException();
        }

        if (!isset($data['hash'])) {
            throw new ArgumentMissingException("Missing Argument URL");
        }

        $url = $data['hash'] ?? null;
        $hash = explode('/', $url);
        $url = end($hash);

        if ($url === null) {
            throw new ArgumentMissingException("Missing Argument Hash");
        }

        $fetch = (new AnaliticsFactory())->fetch($url);
        if (isset($fetch['error'])) {
            throw new \Exception($fetch['error']);
        }

        if (isset($fetch[0]) && $fetch[0] === false) {
            throw new DataNotFoundException("Data not found");
        }

        $delete = (new AnaliticsFactory())->delete($fetch['id']);
        if (isset($delete['error'])) {
            throw new \Exception($delete['error']);
        }

        return [];
    }

    /**
     * Updates analytics data for a specific shortened URL.
     * 
     * @param array $data Contains the necessary data to update the analytics entry.
     * @return array Returns an empty array (to be implemented).
     */
    public function update(array $data): array
    {
        return [];
    }

    /**
     * Creates a new analytics entry for a shortened URL.
     * 
     * @param array $data Contains the necessary data to create the analytics entry.
     * @return array Returns an empty array (to be implemented).
     */
    public function create(array $data): array
    {
        return [];
    }
}