<?php

namespace Tests\Kuick\Ops\UI;

use Kuick\Ops\UI\DocHtmlController;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Ops\UI\DocHtmlController
 */
class DocHtmlControllerTest extends TestCase
{
    public function testIfAllValuesAreReturned(): void
    {
        $doc = new DocHtmlController();
        $response = $doc();
        assertEquals(200, $response->getStatusCode());
        //checking body size
        assertEquals(1056, $response->getBody()->getSize());
    }
}
