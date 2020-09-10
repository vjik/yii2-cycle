<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Tests\SchemaManager;

use Cycle\ORM\Schema;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Vjik\Yii2\Cycle\Schema\SchemaManager;
use Vjik\Yii2\Cycle\Tests\Factory\Stub\FakeContainer;

abstract class BaseSchemaManagerTest extends TestCase
{
    protected const SIMPLE_SCHEMA = [
        'user' => [
            Schema::ENTITY => \stdClass::class,
            Schema::MAPPER => \stdClass::class,
            Schema::DATABASE => 'default',
            Schema::TABLE => 'user',
            Schema::PRIMARY_KEY => 'id',
            Schema::COLUMNS => [
                'id' => 'id',
                'email' => 'email',
                'balance' => 'balance',
            ],
            Schema::TYPECAST => [
                'id' => 'int',
                'balance' => 'float',
            ],
            Schema::RELATIONS => [],
        ],
    ];
    protected const ANOTHER_SCHEMA = [
        'post' => [
            Schema::ENTITY => \stdClass::class,
            Schema::MAPPER => \stdClass::class,
        ],
    ];

    protected $container;

    protected function setUp(): void
    {
        $this->prepareContainer();
    }

    protected function prepareContainer(array $definitions = []): ContainerInterface
    {
        return $this->container = new FakeContainer($definitions);
    }

    protected function prepareSchemaManager(array $providers): SchemaManager
    {
        return new SchemaManager($this->container, $providers);
    }
}
