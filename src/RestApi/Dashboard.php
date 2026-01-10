<?php
declare(strict_types=1);

namespace Codediesel\RestApi;


use Codediesel\Exception\DataNotFoundException;
use Codediesel\Library\Api\RestApiAbstract;
use Codediesel\Library\Api\RestApiInterface;
use Codediesel\Model\Factory\Analitics;

/**
 * Analytics class for managing URL analytics data.
 *
 * This class provides methods to retrieve analytics data for shortened URLs.
 * It extends the RestApiAbstract class and implements the RestApiInterface.
 */
class Dashboard extends RestApiAbstract implements RestApiInterface
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
        return [];
    }

    /**
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getTotals(array $data): array
    {
        $data['user_id'] = $this->route->getUserSessionID();
        if (empty($data['user_id'])) {
            throw new DataNotFoundException();
        }

        $analytics = new Analitics();
        //check how many are in total
        $results = $analytics->getTotals($data);
        $result = current($results);

        $todayResults = $analytics->getTodayTotals($data);
        $todayResult = current($todayResults);

        return [
            'total_links' => $result['total_links'] ?? 0,
            'total_clicks' => $result['total_clicks'] ?? 0,
            'total_qr_codes' => $result['total_qr_codes'] ?? 0,
            //up
            'upTotal_links' => static::calculate($result['total_links'] ?? 0, $todayResult['total_links'] ?? 0),
            'upTotal_clicks' => static::calculate($result['total_clicks'] ?? 0, $todayResult['total_clicks'] ?? 0),
            'upTotal_qr_codes' => static::calculate($result['total_qr_codes'] ?? 0, $todayResult['total_qr_codes'] ?? 0),

        ];
    }

    /**
     * @param $a
     * @param $b
     * @return float|int
     */
    private static function calculate($a, $b)
    {
        $a = is_numeric($a) ? $a : 0;
        $b = is_numeric($b) ? $b : 0;
        if (($a > 0) && ($b > 0)) {
            return (($a / $b) * 100) ?? 0;
        }
        return 0;
    }


    /**
     * @throws \Exception
     */
    public function getMostRecentsLinks(array $data): array
    {
        $data['user_id'] = $this->route->getUserSessionID();
        if (empty($data['user_id'])) {
            throw new DataNotFoundException();
        }

        $analytics = new Analitics();
        return $analytics->fetchRecentLinks($data);

        //


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
        return [];
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