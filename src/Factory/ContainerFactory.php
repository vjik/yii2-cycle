<?php

namespace Vjik\Yii2\Cycle\Factory;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Vjik\Yii2\Cycle\ContainerProxy;
use yii\di\Container;

class ContainerFactory
{

    /**
     * @param Container|ContainerInterface $container
     * @return ContainerInterface
     */
    public static function make($container): ContainerInterface
    {
        if ($container instanceof Container) {
            return new ContainerProxy($container);
        }
        if ($container instanceof ContainerInterface) {
            return $container;
        }
        throw new InvalidArgumentException();
    }
}
