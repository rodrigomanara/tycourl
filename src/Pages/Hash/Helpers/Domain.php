<?php

namespace Codediesel\Pages\Hash\Helpers;

use Codediesel\Library\DatabaseFactory;
use Codediesel\Model\Factory\URL;

class Domain
{
    use DatabaseFactory;

    private string $domain;

    public function __construct(string $domain, $user)
    {
        if ($this->isValid($domain))
            throw new \InvalidArgumentException("Invalid Domain Name");

        //only check if domain is duplication if user is not logged in
        if (!$user->isLogged())
            if ($this->isDuplicate($domain))
                throw new \DomainException("Domain is already in!, Sorry create an account");

    }

    /**
     * @param string $domain
     * @return bool
     */
    private function isValid(string $domain): bool
    {

        if (!preg_match("/http|https/", $domain))
            return false;

        return true;

    }

    /**
     * @param string $domain
     * @return bool
     */

    private function isDuplicate(string $domain)
    {
        $domain = (new URL())->findDomain($domain);
        if ($domain)
            return true;

        return false;

    }


}