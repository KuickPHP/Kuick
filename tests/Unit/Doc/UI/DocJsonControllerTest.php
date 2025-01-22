<?php

namespace Tests\Kuick\Unit\Doc\UI;

use Kuick\Doc\UI\DocJsonController;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\Doc\UI\DocJsonController
 */
class DocJsonControllerTest extends TestCase
{
    public function testIfDocJsonIsReturned(): void
    {
        $docPath = realpath(dirname(__DIR__) . '/../../../');
        $docController = new DocJsonController($docPath);
        $response = $docController();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
    }
}
