<?php

namespace Tests\Kuick\Unit\Example\UI;

use Kuick\Example\UI\PingController;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7Server\ServerRequestCreator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\Example\UI\PingController
 */
class PingControllerTest extends TestCase
{
    public function testIfKuickSaysHello(): void
    {
        $response = (new PingController())(new ServerRequest('GET', '/ping'));
        $this->assertEquals('{"message":"Kuick says: hello my friend!","hint":"If you want a proper greeting use: \/hello\/Name"}', $response->getBody()->getContents());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-type'));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIfKuickSaysHelloUsingName(): void
    {
        $response = (new PingController())(new ServerRequest('GET', '/?name=John'));
        $this->assertEquals('{"message":"Kuick says: hello John!"}', $response->getBody()->getContents());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-type'));
        $this->assertEquals(200, $response->getStatusCode());
    }
}
