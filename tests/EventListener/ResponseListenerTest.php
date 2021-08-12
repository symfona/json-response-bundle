<?php declare(strict_types=1);

namespace Symfona\Bundle\JsonResponseBundle\Tests\EventListener;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResponseListenerTest extends WebTestCase
{
    public function testCreated(): void
    {
        $body = \json_encode(['foo' => 'baz']) ?: '';
        $response = $this->sendRequest(Request::METHOD_POST, $body);

        $this->assertSame($body, $response->getContent());
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testCreatedWithoutResponse(): void
    {
        $response = $this->sendRequest(Request::METHOD_POST);

        $this->assertSame('', $response->getContent());
        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGet(): void
    {
        $response = $this->sendRequest();

        $this->assertSame('[]', $response->getContent());
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testError(): void
    {
        $response = $this->sendRequest(Request::METHOD_PUT);

        $this->assertSame('{"message":"Something wrong"}', $response->getContent());
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    private function sendRequest(string $method = Request::METHOD_GET, mixed $body = null): Response
    {
        $client = self::createClient();

        $client->request($method, '/', [], [], [], $body);

        return $client->getResponse();
    }
}
