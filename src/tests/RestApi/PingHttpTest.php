<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class PingHttpTest extends TestCase
{
    private string $baseUrl = 'http://localhost:8000/rest/api';

    public function testPingEndpointReturns200AndSuccess()
    {
        $client = new Client(['base_uri' => $this->baseUrl, 'http_errors' => false]);

        $response = $client->request('GET', '/ping', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode(), 'Ping endpoint should return HTTP 200 when server is running');

        $body = (string) $response->getBody();
        $json = json_decode($body, true);

        // basic structure check used by the front-end: { response: { success: true, data: {...} } }
        $this->assertIsArray($json);
        $this->assertArrayHasKey('response', $json);
        $this->assertArrayHasKey('success', $json['response']);
        $this->assertTrue($json['response']['success']);
    }

    public function testPingEndpointMethodNotAllowedForPost()
    {
        $client = new Client(['base_uri' => $this->baseUrl, 'http_errors' => false]);

        $response = $client->request('POST', '/ping', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => []
        ]);

        // Expect 4xx - either 405 or 400 depending on implementation
        $this->assertGreaterThanOrEqual(400, $response->getStatusCode());
        $this->assertLessThan(500, $response->getStatusCode());
    }
}

