<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Tests\Factory\Stub;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Vjik\Yii2\Psr\ContainerProxy\NotFoundException;

class FakeContainer implements ContainerInterface
{

    private $testCase;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function get($id)
    {
        if ($id === 'invalid') {
            throw new NotFoundException();
        }
        return $this->testCase->getMockBuilder($id)->getMock();
    }

    public function has($id)
    {
        return true;
    }
}
