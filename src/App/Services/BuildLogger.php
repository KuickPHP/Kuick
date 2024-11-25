<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Services;

use DateTimeZone;
use Kuick\App\AppException;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 *
 */
class BuildLogger extends ServiceBuildAbstract
{
    public function __invoke(): void
    {
        $this->builder->addDefinitions([LoggerInterface::class => function (ContainerInterface $container): LoggerInterface {
            $logger = new Logger($container->get('kuick.app.name'));
            $logger->useMicrosecondTimestamps((bool) $container->get('kuick.app.monolog.useMicroseconds'));
            $logger->setTimezone(new DateTimeZone($container->get('kuick.app.timezone')));
            $handlers = $container->get('kuick.app.monolog.handlers');
            $defaultLevel = $container->get('kuick.app.monolog.level') ?? LogLevel::WARNING;
            !is_array($handlers) && throw new AppException('Logger handlers are invalid, should be an array');
            foreach ($handlers as $handler) {
                $type = $handler['type'] ?? throw new AppException('Logger handler type not defined');
                $level = $handler['level'] ?? $defaultLevel;
                //@TODO: handle more types
                switch ($type) {
                    case 'firePHP':
                        $logger->pushHandler((new FirePHPHandler($level)));
                        break;
                    case 'stream':
                        $logger->pushHandler((new StreamHandler($handler['path'] ??
                        throw new AppException('Logger handler type not defined'), $level)));
                        break;
                    default:
                        throw new AppException('Unknown Monolog handler: ' . $type);
                }
            }
            return $logger;
        }]);
    }
}
