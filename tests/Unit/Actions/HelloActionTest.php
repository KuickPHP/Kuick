<?php

namespace Tests\Kuick\Example\UI;

use Kuick\Example\UI\HelloAction;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\Example\UI\HelloAction
 */
class HelloActionTest extends TestCase
{
    public function testIfKuickSaysHello(): void
    {
        $request = new ServerRequest('GET', 'some-api-url');
        $response = (new HelloAction())($request);
        $this->assertEquals('{"message":"Kuick says: hello my friend!","hint":"If you want a proper greeting use: some-api-url?name=Your-name"}', $response->getBody()->getContents());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-type'));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIfKuickSaysHelloUsingName(): void
    {
        $request = new ServerRequest('GET', 'some-api-url?name=John');
        $response = (new HelloAction())($request);
        $this->assertEquals('{"message":"Kuick says: hello John!"}', $response->getBody()->getContents());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-type'));
        $this->assertEquals(200, $response->getStatusCode());
    }
}
