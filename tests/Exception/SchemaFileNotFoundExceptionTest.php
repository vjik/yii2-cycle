<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Vjik\Yii2\Cycle\Exception\SchemaFileNotFoundException;

final class SchemaFileNotFoundExceptionTest extends TestCase
{
    private const DEFAULT_FILENAME = './vendor/bin/notfound';

    private function prepareException(string $filename = self::DEFAULT_FILENAME): SchemaFileNotFoundException
    {
        return new SchemaFileNotFoundException($filename);
    }

    public function testDefaultState(): void
    {
        $exception = $this->prepareException();

        $this->assertInstanceOf(\Throwable::class, $exception);
        $this->assertSame('Schema file "' . self::DEFAULT_FILENAME . '" not found.', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
    }
}
