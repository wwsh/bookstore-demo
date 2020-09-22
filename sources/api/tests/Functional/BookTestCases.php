<?php

namespace Ddd\Tests\Functional;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookTestCases extends AbstractRestTestCase
{
    public function testCreatingUpdatingDeletingBooks(): void
    {
        $this->jsonRequest(
            Request::METHOD_POST,
            '/api/categories',
            [
                'name' => 'Fiction',
            ]
        );

        $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_CREATED
        );

        $this->jsonRequest(
            Request::METHOD_POST,
            '/api/categories',
            [
                'name' => 'Tale',
            ]
        );

        $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_CREATED
        );

        $this->jsonRequest(
            Request::METHOD_POST,
            '/api/books',
            [
                'name' => 'Crime & Punishment',
                'categories' => [
                    'Fiction', 'Tale'
                ]
            ]
        );

        $data = $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_CREATED
        );

        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);

        $id = $data['id'];

        $this->jsonRequest(
            Request::METHOD_GET,
            '/api/books/' . $id
        );

        $data = $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_OK
        );

        $this->assertEquals(['Fiction', 'Tale'], $data['categories']);

        $this->jsonRequest(
            Request::METHOD_PATCH,
            '/api/books/' . $id,
            [
                'name' => 'Year 1984',
                'categories' => [
                    'Fiction',
                ]
            ]
        );

        $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_ACCEPTED
        );

        $this->jsonRequest(
            Request::METHOD_GET,
            '/api/books/' . $id
        );

        $data = $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_OK
        );

        $this->assertIsArray($data);
        $this->assertEquals([
            'name' => 'Year 1984',
            'categories' => [
                'Fiction'
            ],
            'id' => $id,
        ], $data);

        $this->jsonRequest(
            Request::METHOD_DELETE,
            '/api/books/' . $id
        );


        $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_ACCEPTED
        );

        $this->jsonRequest(
            Request::METHOD_GET,
            '/api/books/' . $id
        );

        $this->assertJsonResponse(
            $this->client->getResponse(),
            Response::HTTP_NOT_FOUND
        );
    }
}
