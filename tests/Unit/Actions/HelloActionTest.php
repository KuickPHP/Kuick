<?php

namespace Tests\Kuick\Example\UI;

use Kuick\Example\UI\HelloAction;
use Kuick\Http\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\Example\UI\HelloAction
 */
class HelloActionTest extends TestCase
{
    public function testIfKuickSaysHello(): void
    {
        $request = new Request();
        $response = (new HelloAction())($request);
        $this->assertEquals('{"message":"Kuick says: hello my friend!","hint":"If you want a proper greeting use: http:\/\/:\/?name=Your-name"}', $response->getContent());
        $this->assertEquals('application/json', $response->headers->get('Content-type'));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIfKuickSaysHelloUsingName(): void
    {
        $request = new Request();
        $request->query->set('name', 'John');
        $response = (new HelloAction())($request);
        $this->assertEquals('{"message":"Kuick says: hello John!"}', $response->getContent());
        $this->assertEquals('application/json', $response->headers->get('Content-type'));
        $this->assertEquals(200, $response->getStatusCode());
    }
}
