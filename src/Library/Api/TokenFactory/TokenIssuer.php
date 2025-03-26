<?php

namespace Codediesel\Library\Api\TokenFactory;

use Jose\Component\Signature\Serializer\CompactSerializer;

class TokenIssuer
{
    private array $payload;


    /**
     * @return false|string
     */
    private function payLoad(): false|string
    {
        return json_encode(array_merge([
            'iat' => time(),
            'exp' => time() + 3600, // Token expires in 1 hour
        ], $this->payload));
    }

    /**
     * @param array $payload
     * @return $this
     */
    public function setPayloadOptions(array $payload){
        $this->payload = $payload;
        return $this;
    }

    /**
     * @param $time
     * @return $this
     */
    public function setTokenExpireTime($time = null){

        if(!$time)
            $time = 3600;

        $this->payload['exp'] = time() + $time;

        return $this;
    }

    /**
     * @return string
     */
    function generaToken(): string
    {
        $jws = (new Builder())->jwsBuilder()
            ->create()
            ->withPayload($this->payLoad())
            ->addSignature((new PrivateKeyFetcher())->signature() , ['alg' => 'RS256'])
            ->build();


        $serializer = new CompactSerializer();
        return $serializer->serialize($jws, 0);
    }
}