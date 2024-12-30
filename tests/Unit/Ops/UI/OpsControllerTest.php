<?php

namespace Kuick\Tests\Ops\UI;

use DI\Container;
use Kuick\Ops\UI\OpsController;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\Ops\UI\OpsController
 */
class OpsControllerTest extends TestCase
{
    public function testIfAllValuesAreReturned(): void
    {
        $container = new Container([]);
        $ops = new OpsController($container);
        $response = $ops->__invoke(new ServerRequest('GET', '/'));
        assertEquals(200, $response->getStatusCode());
        $responseJsonKeys = array_keys(json_decode($response->getBody()->getContents(), true));
        self::assertEquals([
            'request',
            'environment',
            'di-config',
            'opcache-status',
            'apcu-status',
            'php-version',
            'php-config',
            'php-loaded-extensions'
        ], $responseJsonKeys);
    }
}
