<?php

namespace Codediesel\Library\Api\TokenFactory;


use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\ExpirationTimeChecker;


class ValidateDecodeToken
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        try {
            $builder = new Builder();

            $jwsVerifier = $builder->verifier();

            if (!$jwsVerifier->verifyWithKey($this->unserialize(), (new PrivateKeyFetcher())->signature(), 0)) {
                throw new \Exception('Invalid signature');
            }
            $clock = new Clock();
            $checkerManager = new ClaimCheckerManager([new ExpirationTimeChecker($clock)]);
            $checkerManager->check($this->getPayload());

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return \Jose\Component\Signature\JWS
     */
    private function unserialize(): \Jose\Component\Signature\JWS
    {

        $serializer = new CompactSerializer();
        return $serializer->unserialize($this->token);
    }

    public function getPayload() :array{
        return json_decode($this->unserialize()->getPayload() , true);
    }

}