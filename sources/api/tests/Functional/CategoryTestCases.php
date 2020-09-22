<?php

namespace Ddd\Tests\Functional;

use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryTestCases extends AbstractRestTestCase
{
    public function testCreatingCategories(): void
    {
        $this->jsonRequest(
            Request::METHOD_POST,
            '/api/categories',
            [
                'name' => 'SciFi',
            ]
        );

        $data = $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_CREATED
        );

        $this->jsonRequest(
            Request::METHOD_GET,
            '/api/categories'
        );

        $data = $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_OK
        );

        $this->assertIsArray($data);

        $this->assertNotFalse(
            collect($data)
            ->search(fn (array $category) => $category['name'] === 'SciFi')
        );
    }

    public function testListingCategories(): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $this->jsonRequest(
                Request::METHOD_POST,
                '/api/categories',
                [
                    'name' => $faker->text(20),
                ]
            );

            $data = $this->assertJsonResponse(
                $this->client->getResponse(),
                Response::HTTP_CREATED
            );
        }

        $this->jsonRequest(
            Request::METHOD_GET,
            '/api/categories'
        );

        $data = $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_OK
        );

        $this->assertIsArray($data);
        $this->assertTrue(count($data) >= 10);
    }
}
