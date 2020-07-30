<?php

namespace Vjik\Yii2\Cycle;

use Psr\Container\ContainerInterface;
use Spiral\Core\Exception\Container\ContainerException;
use Spiral\Core\Exception\Container\NotFoundException;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class ContainerProxy implements ContainerInterface
{

    public function get($id)
    {
        try {
            Yii::$container->get($id);
        } catch (NotInstantiableException $e) {
            throw new NotFoundException($e->getMessage());
        } catch (InvalidConfigException $e) {
            throw new ContainerException($e->getMessage());
        }
    }

    public function has($id)
    {
        Yii::$container->has($id);
    }
}
