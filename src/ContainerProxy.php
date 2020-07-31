<?php

namespace Vjik\Yii2\Cycle;

use Psr\Container\ContainerInterface;
use Spiral\Core\Exception\Container\ContainerException;
use Spiral\Core\Exception\Container\NotFoundException;
use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\di\NotInstantiableException;

class ContainerProxy implements ContainerInterface
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException(
                sprintf('Identifier "%s" is not known to the container.', $id)
            );
        }

        try {
            return $this->container->get($id);
        } catch (NotInstantiableException $e) {
            throw new ContainerException($e->getMessage());
        } catch (InvalidConfigException $e) {
            throw new ContainerException($e->getMessage());
        }
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return $this->container->has($id)
            || $this->container->hasSingleton($id)
            || class_exists($id);
    }
}
