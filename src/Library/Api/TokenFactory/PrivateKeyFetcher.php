<?php

namespace Codediesel\Library\Api\TokenFactory;
 
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Core\JWK;

class PrivateKeyFetcher
{

    /**
     * @var string|false
     */
    private string $privateKey;

    /**
     * @throws PathExistsException
     */
    public function __construct()
    {
        try {
            $path = (sprintf("%s/%s", __KEYS__, 'private.pem'));
            if (is_file($path))
                // Load the private key
                $this->privateKey = file_get_contents(sprintf("%s/%s", __KEYS__, 'private.pem'));
        }catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return false|string
     */
    private function getPrivateKey(): false|string
    {
        return $this->privateKey;
    }

    /**
     * @return JWK
     */
    public function signature(): JWK
    {
        return JWKFactory::createFromKey($this->getPrivateKey());
    }
}