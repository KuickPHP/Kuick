<?php

namespace Tests\Kuick\App;

use Kuick\App\AppGetEnvironment;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\AppGetEnvironment
 */
class AppGetEnvironmentTest extends TestCase
{
    public function testIfEnvironmentIsProperlyInherited(): void
    {
        putenv('TESTING=testing');
        $environmentVariables = (new AppGetEnvironment())(dirname(__DIR__) . '/../Mocks/MockProjectDir');
        assertEquals('testing', $environmentVariables['testing']);
        assertEquals('local value', $environmentVariables['example']);
    }
}
