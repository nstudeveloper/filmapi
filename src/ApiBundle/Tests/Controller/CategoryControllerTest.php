<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\Controller\ApiTestCase;

class CategoryControllerTest extends ApiTestCase
{
    public function testPOST()
    {
        $category = 'Category' . rand(0, 999);
        $data = array(
            'name' => $category,
        );

        $response = $this->client->post('/category/new', [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
    }

    public function testUpdate()
    {
        $category = 'UpdatedCategory' . rand(0, 999);
        $data = array(
            'name' => $category,
        );
        $this->createCategory($data);

        $response = $this->client->put('/category/' . $category, [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        return $response;
    }

    public function testDelete()
    {
        $category = 'CategoryForDelete' . rand(0, 999);
        $data = array(
            'name' => $category,
        );
        $this->createCategory($data);

        $response = $this->client->delete('/category/' . $category);
        $this->assertEquals(204, $response->getStatusCode());
    }


    private function createCategory($category)
    {
        $response = $this->client->post('/category/new', [
            'body' => json_encode($category)
        ]);
    }
}