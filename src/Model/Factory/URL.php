<?php

namespace Codediesel\Model\Factory;

use Codediesel\Library\DatabaseFactory;
use Codediesel\Library\HashURL;
use Codediesel\Library\QRCodeGenerator;

class URL
{
    // Use the DatabaseFactory trait to provide database-related functionality.
    use DatabaseFactory;

    // Define the name of the database table used for storing URLs.
    const string table_name = 'urls';

    /**
     * Fetch a URL record from the database using its hash.
     *
     * @param string $hash The hash of the URL to retrieve.
     * @return array|null The URL record if found, or null if not found.
     */
    public function fetchUrl(string $hash)
    {
        // Retrieve the URL record from the database table using the provided hash.
        return $this->getInstance()->retrieve(static::table_name, [
            'hash' => $hash
        ]);
    }


    /**
     * @param array $data
     * @return mixed
     */
    public function fetchBeforeDelete(array $data){
        // Retrieve the URL record from the database table using the provided hash.
        return $this->getInstance()->retrieve(static::table_name, $data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function fetchWithoutQRCode(array $data): mixed
    {

        $limit = $data['limit'] ?? 10;
        $offset = $data['offset'] ?? 0;

        $table = static::table_name;
        $sql = <<<SQL
select * from {$table} where user_id = :user_id 
                              and (qr_code is null or qr_code = '') 
                    order by date_create desc 
                                         limit {$offset},{$limit};
SQL;
        return $this->getInstance()->rawSql($sql, [
            'user_id' => $data['user_id'],
        ]);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function fetchOnlyWithQRCode(array $data): mixed
    {


        $limit = $data['limit'] ?? 10;
        $offset = $data['offset'] ?? 0;

        $table = static::table_name;
        $sql = <<<SQL
                      select * from {$table} where user_id = :user_id 
                              and (qr_code is not null 
                       or qr_code != '') order by date_create desc limit {$offset},{$limit};
SQL;
        return $this->getInstance()->rawSql($sql, [
            'user_id' => $data['user_id'],
        ]);
    }

    /**
     * @param array $where
     * @param $limit
     * @param $offset
     * @param array $options
     * @return mixed
     */
    public function fetchAll(array $where, $limit = 10, $offset = 0 , array $options = []): mixed
    {
        // Retrieve all URL records from the database table with pagination.
        return $this->getInstance()->retrieveRecords(
            static::table_name
            , $where
            , $limit
            , $offset
            , $options
        );
    }

    /**
     * @param string $user_id
     * @return int
     */
    public function totalCount(int $user_id): int
    {
        $records =  $this->getInstance()->rawSql('SELECT COUNT(*) as total FROM `'
            . static::table_name . '` where user_id = :user_id', [
                'user_id' => $user_id
        ]);
        return current($records)['total'] ?? 0;
    }

    /**
     * Create a new URL record in the database.
     *
     * @param string $url The original URL to be shortened.
     * @return array The newly created URL record, including the generated hash.
     */
    public function createUrl(array $data): array
    {

        if (!isset($data['url']) or ($data['url'] === null))
            throw new \Exception("Missing Argument URL");
        if (!isset($data['user_id']) or $data['user_id'] === null)
            throw new \Exception("Missing Argument User ID");


        $arguments = [
            'url' => $data['url'],
            'user_id' => $data['user_id'],
        ];

        $existingUrl = $this->fetchAll($arguments);

        if (!empty($existingUrl)) {
            // If the URL already exists, return the existing record.
            throw new \Exception("Url already exists");
        }

        $args = [
            'url' => $data['url'],
            'user_id' => $data['user_id'],
            'hash' => HashURL::generateHash(
                sprintf("%s_%s", $data['url'], $data['user_id'])
                , 10)

        ];

        //check if QR code was requested as well
        if(isset($data['qr_code'])) {
            $url = sprintf("%s/%s", $data['qr_code'], $args['hash']);
            $qr_code = (new QRCodeGenerator($url))->render();
            $args['qr_code'] = sprintf("data:image/png;base64,%s",$qr_code);
        }
        // Generate a hash for the URL and insert it into the database table.
        return $this->getInstance()->create(static::table_name, $args);
    }

    /**
     * Delete a URL record from the database using its ID.
     *
     * @param string $record_id The ID of the URL record to delete.
     * @return array The result of the deletion operation.
     */
    public function delete(mixed $record_id): mixed
    {
        // Delete the URL record from the database using the provided hash.
        return $this->getInstance()->delete(static::table_name, [
            'id' => $record_id
        ]);
    }

    public function findDomain(string $domain): array
    {
        return [];
    }

}