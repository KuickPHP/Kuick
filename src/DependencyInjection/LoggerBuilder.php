<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\DependencyInjection;

use DateTimeZone;
use DI\ContainerBuilder;
use Kuick\Framework\Config\ConfigException;
use Kuick\Framework\KernelInterface;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\ErrorHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Logger builder
 */
class LoggerBuilder
{
    public function __construct(private ContainerBuilder $builder)
    {
    }

    public function __invoke(): void
    {
        $this->builder->addDefinitions([LoggerInterface::class => function (ContainerInterface $container): LoggerInterface {
            $logger = new Logger($container->get(KernelInterface::DI_APP_NAME_KEY));
            $logger->useMicrosecondTimestamps((bool) $container->get('app.log.usemicroseconds'));
            $logger->setTimezone(new DateTimeZone($container->get('app.timezone')));
            $handlers = $container->get('app.log.handlers');
            $defaultLevel = $container->get('app.log.level') ?? LogLevel::WARNING;
            !is_array($handlers) && throw new ConfigException('Logger handlers are invalid, should be an array');
            foreach ($handlers as $handler) {
                $type = $handler['type'] ?? throw new ConfigException('Logger handler type not defined');
                $level = $handler['level'] ?? $defaultLevel;
                //@TODO: extract handler factory to the different class and add missing types
                switch ($type) {
                    case 'fingersCrossed':
                        //@TODO: add more nested handler options
                        $nestedHandler = new StreamHandler($handler['nestedPath'] ?? 'php://stdout', $handler['nestedLevel'] ?? 'debug');
                        $logger->pushHandler(new FingersCrossedHandler($nestedHandler, $level));
                        break;
                    case 'firePHP':
                        $logger->pushHandler((new FirePHPHandler($level)));
                        break;
                    case 'stream':
                        $logger->pushHandler((new StreamHandler($handler['path'] ?? 'php://stdout', $level)));
                        break;
                    default:
                        throw new ConfigException('Unknown Monolog handler: ' . $type);
                }
            }
            $logger->debug('Logger initialized');
            return $logger;
        }]);
    }
}
