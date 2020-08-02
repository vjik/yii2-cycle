<?php

namespace Vjik\Yii2\Cycle\Factory;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Spiral\Database\Config\DatabaseConfig;
use Spiral\Database\DatabaseManager;
use Yii;

final class DbalFactory
{

    /** @var array|DatabaseConfig */
    private $dbalConfig;

    /** @var null|string|LoggerInterface */
    private $logger = null;

    /** @var null|ContainerInterface */
    private $container = null;

    /**
     * @param array|DatabaseConfig $config
     */
    public function __construct($config)
    {
        if (is_array($config) && array_key_exists('query-logger', $config)) {
            $this->logger = $config['query-logger'];
            unset($config['query-logger']);
        }
        $this->dbalConfig = $config;
    }

    public function __invoke(ContainerInterface $container)
    {
        $this->container = $container;
        $conf = $this->prepareConfig($this->dbalConfig);
        $dbal = new DatabaseManager($conf);

        if ($this->logger !== null) {
            $logger = $this->prepareLogger($this->logger);
            $dbal->setLogger($logger);
            /** Remove when issue is resolved @link https://github.com/cycle/orm/issues/60 */
            foreach ($dbal->getDrivers() as $driver) {
                $driver->setLogger($logger);
            }
        }

        return $dbal;
    }

    /**
     * @param string|LoggerInterface $logger
     * @return LoggerInterface
     * @throws InvalidArgumentException
     */
    private function prepareLogger($logger): LoggerInterface
    {
        if ($logger instanceof LoggerInterface) {
            return $logger;
        }
        if (is_string($logger)) {
            return $this->container->get($logger);
        }
        throw new InvalidArgumentException('Invalid logger.');
    }

    /**
     * @param array|DatabaseConfig $config
     * @return DatabaseConfig
     */
    private function prepareConfig($config): DatabaseConfig
    {
        if ($config instanceof DatabaseConfig) {
            return $config;
        }
        if (isset($config['connections'])) {
            // prepare connections
            foreach ($config['connections'] as &$connection) {
                $connection = $this->prepareConnection($connection);
            }
        }

        return new DatabaseConfig($config);
    }

    private function prepareConnection(array $connection): array
    {
        // if connection option contain alias in path
        if (isset($connection['connection']) && preg_match('/^(?<proto>\w+:)?@/', $connection['connection'], $m)) {
            $proto = $m['proto'];
            $path = $this->getAlias(substr($connection['connection'], strlen($proto)));
            $connection['connection'] = $proto . $path;
        }
        return $connection;
    }

    private function getAlias(string $alias): string
    {
        return Yii::getAlias($alias);
    }
}
