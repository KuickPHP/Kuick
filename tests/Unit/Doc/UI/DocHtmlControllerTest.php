<?php

namespace Tests\Kuick\Unit\Doc\UI;

use Kuick\Doc\UI\DocHtmlController;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kuick\Doc\UI\DocHtmlController
 */
class DocHtmlControllerTest extends TestCase
{
    public function testIfAllValuesAreReturned(): void
    {
        $doc = new DocHtmlController();
        $response = $doc();
        $this->assertEquals(200, $response->getStatusCode());
        //checking body size
        $this->assertEquals(1056, $response->getBody()->getSize());
    }
}
