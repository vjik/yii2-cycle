<?php

namespace Vjik\Yii2\Cycle\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use stdClass;
use Vjik\Yii2\Cycle\Factory\DbalFactory;
use Vjik\Yii2\Cycle\Tests\Factory\Stub\FakeContainer;
use Vjik\Yii2\Cycle\Tests\Factory\Stub\FakeDriver;

class DbalFactoryPrepareLoggerTest extends TestCase
{

    private $container;

    protected function setUp(): void
    {
        $this->container = new FakeContainer($this);
    }

    protected function prepareLogger($logger)
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

    public function testString(): void
    {
        $this->assertInstanceOf(NullLogger::class, $this->prepareLogger(NullLogger::class));
    }

    public function testLoggerInterface(): void
    {
        $this->assertInstanceOf(NullLogger::class, $this->prepareLogger(new NullLogger()));
    }

    public function testInvalid(): void
    {
        $this->expectExceptionMessage('Invalid logger.');
        $this->prepareLogger(new stdClass());
    }
}
