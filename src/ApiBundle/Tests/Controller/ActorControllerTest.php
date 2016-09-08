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

        $response = $this->client->post('/actor', [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());
//        $this->assertTrue($response->hasHeader('Location'));
    }

    public function testUpdate()
    {
        $firstName = 'nameForUpdate' . rand(0, 999);
        $lastName = 'lastName' . rand(0, 999);
        $data = array(
            'firstName' => $firstName,
            'lastName' => $lastName
        );

        $updated = array(
            'firstName' => 'updated' . rand(0, 999),
            'lastName' => $lastName
        );

        $this->createActor($data);

        $response = $this->client->put('/actor/' . $firstName, [
            'body' => json_encode($updated)
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        return $response;
    }

    public function testDelete()
    {
        $firstName = 'actorForDelete' . rand(0, 999);
        $lastName = 'lastName' . rand(0, 999);
        $data = array(
            'firstName' => $firstName,
            'lastName' => $lastName
        );
        $this->createActor($data);

        $response = $this->client->delete('/actor/' . $firstName);
        $this->assertEquals(204, $response->getStatusCode());
    }

    private function createActor($actor)
    {
        $response = $this->client->post('/actor', [
            'body' => json_encode($actor)
        ]);
    }

}