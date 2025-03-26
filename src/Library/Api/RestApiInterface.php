<?php

namespace Codediesel\Library\Api;

interface RestApiInterface
{
    /**
     * @method POST
     * @param array $data
     * @return array
     */
    public function create(array $data): array;

    /**
     * @method GET
     * @param array $data
     * @return array
     */
    public function retrieve(array $data): array;

    /**
     * @method DELETE
     * @param string $data
     * @return array
     */
    public function delete(array $data): array;

    /**
     * @method PUT
     * @param array $data
     * @return array
     */
    public function update(array $data): array;

    /**
     * @return bool
     */
    public function isAuthorise(string $role): bool;
}