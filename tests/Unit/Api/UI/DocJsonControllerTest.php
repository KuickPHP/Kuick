<?php

namespace Tests\Unit\Kuick\Framework\Api\UI;

use Kuick\Framework\Api\UI\DocJsonController;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Framework\Api\UI\DocJsonController
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
