<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use stdClass;
use Vjik\Yii2\Cycle\Factory\DbalFactory;
use Vjik\Yii2\Cycle\Tests\Factory\Stub\FakeContainer;
use Vjik\Yii2\Cycle\Tests\Factory\Stub\FakeDriver;
use yii\base\BaseObject;

class DbalFactoryPrepareLoggerTest extends TestCase
{

    private $container;

    protected function setUp(): void
    {
        $this->container = new FakeContainer([
            NullLogger::class => new NullLogger(),
            BaseObject::class => new BaseObject(),
        ]);
    }

    /**
     * @param string|LoggerInterface $logger Classname or object
     * @return null|LoggerInterface
     */
    protected function prepareLoggerFromDbalFactory($logger): ?LoggerInterface
    {
        $factory = (new DbalFactory([
            'query-logger' => $logger,
            'default' => 'default',
            'aliases' => [],
            'databases' => [
                'default' => ['connection' => 'fake']
            ],
            'connections' => [
                'fake' => [
                    'driver' => FakeDriver::class,
                    'connection' => 'fake',
                    'username' => '',
                    'password' => '',
                ]
            ],
        ]))($this->container);
        return $factory->driver('fake')->getLogger();
    }

    public function testLoggerDefinitionAsStringDefinition(): void
    {
        $this->assertInstanceOf(NullLogger::class, $this->prepareLoggerFromDbalFactory(NullLogger::class));
    }

    public function testLoggerDefinitionAsObject(): void
    {
        $this->assertInstanceOf(NullLogger::class, $this->prepareLoggerFromDbalFactory(new NullLogger()));
    }

    public function testLoggerDefinitionAsInvalidDefinition(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);
        $this->prepareLoggerFromDbalFactory('invalid');
    }

    public function testLoggerDefinitionAsInvalidClassName(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Logger definition should be subclass of Psr\Log\LoggerInterface');
        $this->prepareLoggerFromDbalFactory(BaseObject::class);
    }

    public function testLoggerDefinitionAsInvalidObject(): void
    {
        $this->expectExceptionMessage('Logger definition should be subclass of Psr\Log\LoggerInterface.');
        $this->prepareLoggerFromDbalFactory(new stdClass());
    }
}
