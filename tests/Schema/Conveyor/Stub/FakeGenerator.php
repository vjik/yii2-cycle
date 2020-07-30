<?php

namespace Vjik\Yii2\Cycle\Tests\Schema\Conveyor\Stub;

use Cycle\Schema\GeneratorInterface;
use Cycle\Schema\Registry;

class FakeGenerator implements GeneratorInterface
{

    private $originClass;

    public function __construct(string $originClass)
    {
        $this->originClass = $originClass;
    }

    public function run(Registry $registry): Registry
    {
    }

    public function originClass(): string
    {
        return $this->originClass;
    }
}
