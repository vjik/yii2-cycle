<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Tests\Factory;

use Cycle\ORM\FactoryInterface;
use Cycle\ORM\ORM;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\PromiseFactoryInterface;
use Cycle\ORM\SchemaInterface;
use PHPUnit\Framework\TestCase;
use stdClass;
use Vjik\Yii2\Cycle\Exception\BadDeclarationException;
use Vjik\Yii2\Cycle\Factory\OrmFactory;
use Vjik\Yii2\Cycle\Tests\Factory\Stub\FakeContainer;

class OrmFactoryTest extends TestCase
{

    private $container;

    protected function setUp(): void
    {
        $this->container = new FakeContainer([
            SchemaInterface::class => $this->createMock(SchemaInterface::class),
            FactoryInterface::class => $this->createMock(FactoryInterface::class),
            PromiseFactoryInterface::class => $this->createMock(PromiseFactoryInterface::class),
            stdClass::class => new stdClass(),
        ]);
    }

    /**
     * @param null|PromiseFactoryInterface|string $promiseFactory
     * @return ORM
     * @throws \Throwable
     */
    protected function runOrmFactory($promiseFactory = null): ORM
    {
        return (new OrmFactory($promiseFactory))($this->container);
    }

    public function testFactoryCreatesOrmInterface(): void
    {
        $orm = $this->runOrmFactory();
        $this->assertInstanceOf(ORMInterface::class, $orm);
    }

    public function testFactoryWithNullSchema(): void
    {
        $this->container = new FakeContainer([
            FactoryInterface::class => $this->createMock(FactoryInterface::class),
            SchemaInterface::class => null,
        ]);

        $orm = $this->runOrmFactory();
        $this->assertInstanceOf(ORMInterface::class, $orm);
    }

    // Promise Factory

    public function testPromiseFactoryAsStringDefinition(): void
    {
        $orm = $this->runOrmFactory(PromiseFactoryInterface::class);
        $this->assertInstanceOf(ORMInterface::class, $orm);
    }

    public function testPromiseFactoryAsObject(): void
    {
        $promiseFactory = $this->container->get(PromiseFactoryInterface::class);

        $orm = $this->runOrmFactory($promiseFactory);
        $this->assertInstanceOf(ORMInterface::class, $orm);
    }

    public function testPromiseFactoryAsInvalidObject(): void
    {
        $this->expectException(BadDeclarationException::class);
        $this->runOrmFactory(new stdClass());
    }

    public function testPromiseFactoryAsInvalidDefinition(): void
    {
        $this->expectException(BadDeclarationException::class);
        $this->runOrmFactory(stdClass::class);
    }
}
