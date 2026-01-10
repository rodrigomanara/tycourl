<?php
declare(strict_types=1);

namespace Codediesel\RestApi;

use Codediesel\Library\QRCodeGenerator;
use Codediesel\Model\Factory\URL;
use Codediesel\Library\Api\RestApiAbstract;
use Codediesel\Library\Api\RestApiInterface;
use Codediesel\Exception\DataNotFoundException;
use Codediesel\Exception\ArgumentMissingException;
use Codediesel\Exception\DatabaseErrorException;
use Codediesel\Library\UrlValidator;

/**
 * Hash class for managing URL shortening and retrieval.
 *
 * This class provides methods to create, retrieve, update, delete, and fetch history of shortened URLs.
 * It extends the RestApiAbstract class and implements the RestApiInterface.
 */
class Hash extends RestApiAbstract implements RestApiInterface
{
    /**
     * Creates a new shortened URL entry in the database.
     * retrieve
     * @param array $data Contains the 'url' key with the original URL to be shortened.
     * @return array Returns the created URL data, excluding the 'date_update' field.
     * @throws \Exception Throws an exception if the 'url' argument is missing or a database error occurs.
     */
    public function create(array $data): array
    {

        //check if url is valid, do a php ping to the url 
        $url = $data['url'] ?? null;
        if (!UrlValidator::isReachable($url))
            throw new DataNotFoundException("URL is not reachable");


        $fetch = (new URL())->createUrl($data);
        if (isset($fetch['error']))
            throw new DatabaseErrorException($fetch['error']);

        unset($fetch['date_update']);
        return $fetch;
    }

    /**
     * Retrieves the original URL based on the shortened URL identifier.
     *
     * @param array $data Contains the 'url' key with the shortened URL.
     * @return array Returns the fetched URL data.
     * @throws \Exception Throws an exception if the 'url' argument is missing, data is not found, or a database error occurs.
     */
    public function retrieve(array $data): array
    {
        if (!isset($data['url']))
            throw new ArgumentMissingException("Missing Argument URL");

        $url = $data['url'] ?? null;
        if ($url === null)
            throw new ArgumentMissingException("Missing Argument URL");

        $urls = explode('/', $url);
        $url = end($urls);
        if ($url === null)
            throw new ArgumentMissingException("Missing Argument URL");


        $fetch = (new URL())->fetchUrl($url);
        if (isset($fetch['error']))
            throw new \Exception($fetch['error']);

        if (isset($fetch[0]) && $fetch[0] === false)
            throw new DataNotFoundException("Data not found");

        return $fetch;
    }

    /**
     * Retrieves all shortened URLs from the database.
     *
     * @param array $data Contains the 'url' key with the shortened URL.
     * @return array Returns the fetched URL data.
     * @throws \Exception Throws an exception if a database error occurs.
     */
    public function history(array $data): array
    {

        $limit = 10;
        $data['limit'] = $limit;

        $data['user_id'] = $this->route->getUserSessionID();
        if (empty($data['user_id'])) {
            throw new DataNotFoundException();
        }

        if ($data['offset'])
            $data['offset'] = (int)$data['offset'] * (int)$data['limit'];

        $fetch = (new URL())->fetchAll([
            'user_id' => $data['user_id'] ?? null,
        ],
            $data['limit'] ?? 10,
            $data['offset'] ?? 0,
            ['orderBy' => [
                'field' => 'date_update',
                'action' => 'desc'
            ]

            ]);

        if (isset($fetch['error']))
            throw new \Exception($fetch['error']);

        if (isset($fetch[0]) && $fetch[0] === false)
            throw new DataNotFoundException("Data not found");

        //append total of records
        $fetch['total'] = (new URL())->totalCount($data['user_id']);
        $fetch['limit'] = $limit;
        $fetch['offset'] = $data['offset'] ?? 1;

        return $fetch;
    }

    public function fetchUrlWithoutQRCode(array $data): array
    {
        $limit = 10;
        $data['limit'] = $limit;


        $data['user_id'] = $this->route->getUserSessionID();
        if (empty($data['user_id'])) {
            throw new DataNotFoundException();
        }

        if (!isset($data['user_id']))
            throw new \Exception("User ID not set");


        if ($data['offset'])
            $data['offset'] = (int)$data['offset'] * (int)$data['limit'];

        $fetch = (new URL())->fetchWithoutQRCode([
            'user_id' => $data['user_id'] ?? null,
            'limit' => $data['limit'],
            'offset' => $data['offset'] ?? 0,
        ]);

        if (isset($fetch['error']))
            throw new \Exception($fetch['error']);

        if (isset($fetch[0]) && $fetch[0] === false)
            throw new DataNotFoundException("Data not found");

        //append total of records
        $fetch['total'] = (new URL())->totalCount($data['user_id']);
        $fetch['limit'] = $limit;
        $fetch['offset'] = $data['offset'] ?? 1;

        return $fetch;
    }

    /**
     * @param array $data
     * @return array
     * @throws DataNotFoundException
     */
    public function fetchUrlWithQRCode(array $data): array
    {
        $limit = 10;
        $data['limit'] = $limit;

        $data['user_id'] = $this->route->getUserSessionID();
        if (empty($data['user_id'])) {
            throw new DataNotFoundException();
        }

        if (!isset($data['user_id']))
            throw new \Exception("User ID not set");


        if ($data['offset'])
            $data['offset'] = (int)$data['offset'] * (int)$data['limit'];

        $fetch = (new URL())->fetchOnlyWithQRCode([
            'user_id' => $data['user_id'],
            'limit' => $data['limit'],
            'offset' => $data['offset'] ?? 0,
        ]);

        if (isset($fetch['error']))
            throw new \Exception($fetch['error']);

        if (isset($fetch[0]) && $fetch[0] === false)
            throw new DataNotFoundException("Data not found");

        //append total of records
        $fetch['total'] = (new URL())->totalCount($data['user_id']);
        $fetch['limit'] = $limit;
        $fetch['offset'] = $data['offset'] ?? 1;

        return $fetch;
    }


    /**
     * Deletes a shortened URL entry from the database.
     *
     * @param array $data Contains the necessary data to identify the URL to be deleted.
     * @return array Returns an empty array (to be implemented).
     */
    public function delete(array $data): array
    {

        $data['user_id'] = $this->route->getUserSessionID();
        if (empty($data['user_id'])) {
            throw new DataNotFoundException();
        }
        if (!isset($data['page_id']))
            throw new ArgumentMissingException("Missing Argument ID");

        if (!isset($data['hash']))
            throw new ArgumentMissingException("Missing Argument Hash ID");

        $user_id = $data['user_id'] ?? null;
        $page_id = $data['page_id'] ?? null;
        $hash = $data['hash'] ?? null;

        $url = new URL();
        //ensure the user and id and hash are correct before delete
        $isCorrect = $url->fetchBeforeDelete([
            'user_id' => $user_id,
            'id' => $page_id,
            'hash' => $hash
        ]);

        if (empty($isCorrect))
            throw new DataNotFoundException("Data not found");

        $fetch = $url->delete($isCorrect['id']);
        if (isset($fetch['error']))
            throw new \Exception($fetch['error']);
        if (isset($fetch[0]) && $fetch[0] === false)
            throw new DataNotFoundException("Data not found");

        return [];
    }

    /**
     * Updates an existing shortened URL entry in the database.
     *
     * @param array $data Contains the necessary data to update the URL entry.
     * @return array Returns an empty array (to be implemented).
     */
    public function update(array $data): array
    {

        return [];

    }
}