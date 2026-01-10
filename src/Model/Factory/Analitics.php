<?php


namespace Codediesel\Model\Factory;

use Codediesel\Library\DatabaseFactory;


class Analitics
{
    // Use the DatabaseFactory trait to provide database-related functionality.
    use DatabaseFactory;

    // Define the name of the database table used for storing URLs.
    const table_name = 'analitics';

    /**
     * Fetch Data from the database using its hash.
     */
    public function fetch(string $hash)
    {
        // Retrieve the URL record from the database table using the provided hash.
        return $this->getInstance()->retrieve(static::table_name, [
            'hash' => $hash
        ]);
    }

    /**
     * Check if the URL record was created today.
     *
     * @param string $hash The hash of the URL to check.
     * @return array The URL record if it was created today, otherwise an empty array.
     */
    public function isToday(string $hash)
    {
        // Retrieve the URL record from the database table using the provided hash.
        return $this->getInstance()->rawSql(
            "select * from analitics where hash = :hash and date(date_create) = date(:today)",
            [
                'hash' => $hash,
                'today' => (new \DateTime())->format('Y-m-d')
            ]
        );
    }

    /**
     * Fetch all URL records from the database with pagination.
     *
     * @param array $where The conditions to filter the records.
     * @param int $limit The maximum number of records to retrieve (default is 10).
     * @param int $offset The offset for pagination (default is 0).
     * @return array The list of URL records matching the criteria.
     */
    public function fetchAll(array $where, int $limit = 10, int $offset = 0)
    {


        //validation input elments
        if (!isset($where['user_id']))
            throw new \Exception("Missing Argument User ID");

        // Retrieve all URL records from the database table with pagination.
        return $this->getInstance()->rawSql(
            "select * from analitics
inner join urls
on urls.hash = analitics.hash
where urls.user_id = :user_id limit $offset , $limit  ;"
            , [
                'user_id' => $where['user_id'],
            ]
        );
    }

    /**
     * Create a new URL record in the database.
     *
     * @param string $url The original URL to be shortened.
     * @return array The newly created URL record, including the generated hash.
     */
    public function create(array $data): array
    {
        if (!isset($data['hash']))
            throw new \Exception("Missing Arguments");
        $save = [];
        $save['hash'] = $data['hash'];
        $save['date_create'] = (new \DateTime())->format('Y-m-d H:i:s');
        $save['date_update'] = (new \DateTime())->format('Y-m-d H:i:s');
        $save['redirects'] = 1;

        // Create a new URL record in the database table with the provided data.
        return $this->getInstance()->create(static::table_name, $save);
    }

    /**
     * Update an existing URL record in the database.
     *
     * @param string $hash The hash of the URL to update.
     * @param array $data The new data to update the URL record with.
     * @return array The updated URL record.
     */
    public function update(array $data): array
    {

        if (!isset($data['hash']))
            throw new \Exception("Missing Argument Hash");

        $this->getInstance()->rawSql('update analitics set redirects = :redirect
        , date_update = :date_update
        where hash = :hash and date(date_create) = :date_create', [
            'redirect' => (int)$data['redirects'] + 1,
            'date_update' => (new \DateTime())->format('Y-m-d H:i:s'),
            'hash' => $data['hash'],
            'date_create' => (new \DateTime())->format('Y-m-d'),
        ]);

        return $this->getInstance()->retrieve(static::table_name, [
            'hash' => $data['hash'],
        ]);
    }

    /**
     * Delete a URL record from the database.
     *
     * @param string $hash The hash of the URL to delete.
     * @return array An empty array upon successful deletion.
     * @throws \Exception
     */
    public function delete(string $hash): array
    {
        if (!isset($hash))
            throw new \Exception("Missing Argument Hash");
        $fetch = $this->fetchAll(['hash' => $hash], 1, 0);
        $current = current($fetch);
        if (empty($current))
            throw new \Exception("Hash not found");

        // Delete the URL record from the database table with the provided hash.
        return $this->getInstance()->delete(static::table_name, [
            'id' => $current['id']
        ]);
    }


    public function getTotals(array $data): array
    {
        if (!isset($data['user_id']))
            throw new \Exception("Missing Argument User ID");

        $sql = <<<SQL

SELECT 
    COUNT(urls.qr_code) AS total_qr_codes,
    COUNT(urls.id) AS total_links , 
	sum((select sum(redirects) as total  from analitics where hash = urls.hash)) as total_clicks

FROM
    urls
WHERE
    urls.user_id = :user_id
GROUP BY urls.user_id 
SQL;

        return $this->getInstance()->rawSql($sql, ['user_id' => $data['user_id']]);

    }


    public function getTodayTotals(array $data): array
    {
        if (!isset($data['user_id']))
            throw new \Exception("Missing Argument User ID");

        $sql = <<<SQL

SELECT 
    COUNT(urls.qr_code) AS total_qr_codes,
    COUNT(urls.id) AS total_links , 
	sum((select sum(redirects) as total  from analitics where hash = urls.hash)) as total_clicks

FROM
    urls
WHERE
    urls.user_id = :user_id
and date(date_create) = date(:today)
GROUP BY urls.user_id 
SQL;

        return $this->getInstance()->rawSql($sql,
            [
                'user_id' => $data['user_id'],
                'today' => (new \DateTime())->format('Y-m-d')
            ]
        );

    }


    public function fetchRecentLinks(array $data)
    {
        if (!isset($data['user_id']))
            throw new \Exception("Missing Argument User ID");

        $sql = <<<SQL
SELECT DISTINCT
    urls.hash,
    urls.id,
    urls.date_create,
    urls.url,
    (SELECT 
            SUM(analitics.redirects)
        FROM
            analitics
        WHERE
            analitics.hash = urls.hash) AS clicks,
    (SELECT 
            SUM(analitics.redirects)
        FROM
            analitics
        WHERE
            analitics.hash = urls.hash
                AND DATE(analitics.date_create) = DATE(NOW())) AS clicksToday
FROM
    urls
WHERE
    urls.user_id = :user_id
GROUP BY urls.id , urls.hash
ORDER BY urls.date_create DESC
limit 10
SQL;

        return $this->getInstance()->rawSql($sql,
            [
                'user_id' => $data['user_id'],
            ]
        );
    }
}