<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\Controller\ApiTestCase;

class ActorControllerTest extends ApiTestCase
{
    public function testPOST()
    {
        $firstName = 'firstName' . rand(0, 999);
        $lastName = 'lastName' . rand(0, 999);
        $data = array(
            'firstName' => $firstName,
            'lastName' => $lastName
        );

        $response = $this->client->post('/actor/new', [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());
//        $this->assertTrue($response->hasHeader('Location'));
    }

}