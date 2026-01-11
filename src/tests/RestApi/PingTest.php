<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class PingTest extends TestCase
{
    public function testRetrieveReturnsPingTrue()
    {
        // construct a Route stub that provides an empty request/session
        $routeStub = $this->createStub(\Codediesel\Controller\Route::class);
        $routeStub->method('getAll')->willReturn([]);

        $ping = new \Codediesel\RestApi\Ping($routeStub);
        $result = $ping->retrieve([]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('ping', $result);
        $this->assertTrue($result['ping']);
    }
}

