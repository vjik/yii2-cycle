<?php

namespace Vjik\Yii2\Cycle\Tests\Factory\Stub;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class FakeContainer implements ContainerInterface
{

    private $testCase;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function get($id)
    {
        return $this->testCase->getMockBuilder($id)->getMock();
    }

    public function has($id)
    {
        return true;
    }
}
