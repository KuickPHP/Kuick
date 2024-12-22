<?php

namespace Tests\Kuick\Ops\UI;

use Kuick\Ops\UI\DocJsonController;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Ops\UI\DocJsonController
 */
class DocJsonControllerTest extends TestCase
{
    public function testIfDocJsonIsReturned(): void
    {
        $docPath = realpath(dirname(__DIR__) . '/../../..');
        $docController = (new DocJsonController($docPath));
        $response = $docController();
        assertEquals(200, $response->getStatusCode());
        assertEquals('application/json', $response->getHeaderLine('Content-Type'));
    }
}
