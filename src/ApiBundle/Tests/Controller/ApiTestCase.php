<?php

namespace ApiBundle\Tests\Controller;

use GuzzleHttp\Client;

class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    private static $staticClient;

    public static function setUpBeforeClass()
    {
        self::$staticClient = new Client([
            'base_url' => 'http://site.com',
            'defaults' => [
                'exceptions' => false
            ]
        ]);
    }

    protected $client;

    protected function setUp()
    {
        $this->client = self::$staticClient;
    }
}