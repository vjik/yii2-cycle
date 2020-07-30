<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Factory;

use Cycle\ORM\FactoryInterface;
use Cycle\ORM\ORM;
use Cycle\ORM\PromiseFactoryInterface;
use Cycle\ORM\SchemaInterface;
use Yii;

final class OrmFactory
{
    /** @var null|PromiseFactoryInterface|string  */
    private $promiseFactory = null;

    /**
     * OrmFactory constructor.
     * @param null|PromiseFactoryInterface|string $promiseFactory
     */
    public function __construct($promiseFactory = null)
    {
        $this->promiseFactory = $promiseFactory;
    }

    public function __invoke()
    {
        $schema = Yii::$container->get(SchemaInterface::class);
        $factory = Yii::$container->get(FactoryInterface::class);

        $orm = new ORM($factory, $schema);

        // Promise factory
        if ($this->promiseFactory !== null) {
            if (!$this->promiseFactory instanceof PromiseFactoryInterface) {
                $this->promiseFactory = Yii::$container->get($this->promiseFactory);
            }
            $orm = $orm->withPromiseFactory($this->promiseFactory);
        }

        return $orm;
    }
}
