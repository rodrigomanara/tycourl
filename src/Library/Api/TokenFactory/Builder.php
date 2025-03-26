<?php

namespace Codediesel\Library\Api\TokenFactory;

use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSBuilder;

class Builder
{

    /**
     * @return AlgorithmManager
     */
    public function algorithmManager(): AlgorithmManager
    {
        return new AlgorithmManager([new RS256()]);
    }

    public function jwsBuilder(): JWSBuilder
    {
        return new JWSBuilder($this->algorithmManager());
    }

    /**
     * @return JWSVerifier
     */
    public function verifier(): JWSVerifier
    {
        return new JWSVerifier($this->algorithmManager());
    }
}