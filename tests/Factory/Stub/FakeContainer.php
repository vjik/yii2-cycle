<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Tests\Factory\Stub;

use Closure;
use Psr\Container\ContainerInterface;
use Vjik\Yii2\Psr\ContainerProxy\NotFoundException;

class FakeContainer implements ContainerInterface
{

    private $definitions;
    private $factory;

    /**
     * @param array $definitions
     * @param null|Closure $factory Should be closure that works like ContainerInterface::get(string $id): mixed
     */
    public function __construct(array $definitions = [], Closure $factory = null)
    {
        $this->definitions = $definitions;
        $this->factory = $factory ??
            static function (string $id) {
                throw new NotFoundException($id);
            };
    }

    public function get($id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            $this->definitions[$id] = ($this->factory)($id); // @phan-suppress-current-line PhanTypeVoidAssignment
        }
        return $this->definitions[$id];
    }

    public function has($id)
    {
        if (array_key_exists($id, $this->definitions)) {
            return true;
        }
        try {
            $this->get($id);
            return true;
        } catch (\Throwable $e) { // @phan-suppress-current-line PhanUnusedVariableCaughtException
            return false;
        }
    }
}
