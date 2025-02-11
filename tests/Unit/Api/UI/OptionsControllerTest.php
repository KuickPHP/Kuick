<?php

namespace Tests\Unit\Kuick\Framework\Api\UI;

use Kuick\Framework\Api\UI\OptionsController;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Framework\Api\UI\OptionsController
 */
class OptionsControllerTest extends TestCase
{
    public function testIfAllValuesAreReturned(): void
    {
        $response = (new OptionsController())();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
