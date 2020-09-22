<?php

declare(strict_types=1);

namespace Ddd\Tests\Functional;

use Safe\Exceptions\JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

use function Safe\json_decode;
use function Safe\json_encode;

abstract class AbstractRestTestCase extends WebTestCase
{
    /** @var KernelBrowser */
    protected $client;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->client = static::createClient(
            [],
            ['HTTP_HOST' => $_ENV['HTTP_HOST']]
        );
    }

    /**
     * @throws JsonException
     */
    protected function jsonRequest(string $method, string $uri, array $data = []): Response
    {
        $this->client->request(
            $method,
            $uri,
            $method === Request::METHOD_GET ? $data : [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => '*/*'],
            $method !== Request::METHOD_GET ? json_encode($data) : null
        );

        $response = $this->client->getResponse();

        return $response;
    }

    /**
     * @return mixed
     *
     * @throws JsonException
     */
    protected function assertJsonResponse(Response $response, int $expectedStatusCode)
    {
        $this->assertEquals(
            $expectedStatusCode,
            $response->getStatusCode(),
            'Received JSON: ' . $response->getContent()
        );
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());

        return json_decode($response->getContent(), true);
    }

    /**
     * @throws JsonException
     */
    protected function login(
        string $username = UserFixtures::DEFAULT_USER_LOGIN,
        string $password = UserFixtures::DEFAULT_USER_PASSWORD
    ): void {
        $this->client->request(
            Request::METHOD_POST,
            '/api/internal/security/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => $username, 'password' => $password])
        );
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @param array $data
     * @return array
     */
    protected function camelCaseToSnakeCaseInKeys(array $data): array
    {
        $converter = new CamelCaseToSnakeCaseNameConverter();

        foreach ($data as $key => $value) {
            $newKey = $converter->normalize($key);

            if ($newKey !== $key) {
                unset($data[$key]);
                $key = $newKey;
                $data[$key] = $value;
            }

            if (is_array($value)) {
                $data[$key] = $this->camelCaseToSnakeCaseInKeys($value);
            }
        }

        return $data;
    }
}
