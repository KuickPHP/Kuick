<?php

namespace Tests\Kuick\App;

use Kuick\App\DotEnvLoader;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * @covers \Kuick\App\DotEnvLoader
 */
class DotEnvLoaderTest extends TestCase
{
    public function testIfEnvironmentIsProperlyInherited(): void
    {
        putenv('TESTING=no-override');
        putenv('UNTOUCHED=untouched');
        //clear envs
        putenv('KUICK_APP_ENV');
        putenv('OVERRIDE_LOCAL');
        putenv('OVERRIDE_DEV');
        putenv('OVERRIDE_DEV_LOCAL');
        new DotEnvLoader(dirname(__DIR__) . '/../Mocks/MockProjectDir');
        assertEquals('no-override', getenv('TESTING'));
        assertEquals('local value', getenv('ONLY_LOCAL'));
        assertEquals('untouched', getenv('UNTOUCHED'));
        assertEquals('override.env.local', getenv('OVERRIDE_LOCAL'));
        assertEquals('override.env.dev', getenv('OVERRIDE_DEV'));
        assertEquals('override.env.dev.local', getenv('OVERRIDE_DEV_LOCAL'));
    }

    public function testIfProdEnvironmentIsProperlyInherited(): void
    {
        putenv('KUICK_APP_ENV=prod');
        //clear envs
        putenv('OVERRIDE_LOCAL');
        putenv('OVERRIDE_DEV');
        putenv('OVERRIDE_DEV_LOCAL');
        new DotEnvLoader(dirname(__DIR__) . '/../Mocks/MockProjectDir');
        assertEquals('no-override', getenv('TESTING'));
        assertEquals('local value', getenv('ONLY_LOCAL'));
        assertEquals('untouched', getenv('UNTOUCHED'));
        assertEquals('override.env.local', getenv('OVERRIDE_LOCAL'));
        assertEquals('.env.dev', getenv('OVERRIDE_DEV'));
        assertEquals('.env.dev.local', getenv('OVERRIDE_DEV_LOCAL'));
    }

    public function testIfMissingEnvDefaultsToProd(): void
    {
        //clear env
        putenv('KUICK_APP_ENV');
        new DotEnvLoader(dirname(__DIR__) . '/../Mocks');
        assertEquals('.env.dev.local', getenv('OVERRIDE_DEV_LOCAL'));
    }
}
