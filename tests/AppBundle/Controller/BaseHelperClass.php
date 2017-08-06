<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseHelperClass extends WebTestCase {

    protected $client;

    public function __construct() {
        parent::__construct();
        $this->client = static::createClient();
    }

    protected function getResponse(string $method, string $route, string $request = NULL, array $headers = [], array $file = []) : array {
        $this->client->request($method, $route, [], $file, $headers, $request);
        $response = $this->client->getResponse();
        return [
            'code' => $response->getStatusCode(),
            'body' => $response->getContent()
        ];
    }

}
