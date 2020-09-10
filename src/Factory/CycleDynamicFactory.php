<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Factory;

use Spiral\Core\FactoryInterface;
use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\di\NotInstantiableException;

final class CycleDynamicFactory implements FactoryInterface
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $alias
     * @param array $parameters
     * @return mixed|object|null
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function make(string $alias, array $parameters = [])
    {
        return $this->container->get($alias, $parameters);
    }
}
