<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Vjik\Yii2\Cycle\Exception\DuplicateRoleException;

final class DuplicateRoleExceptionTest extends TestCase
{
    private const DEFAULT_ROLE = 'tag';

    private function prepareException(string $role = self::DEFAULT_ROLE): DuplicateRoleException
    {
        return new DuplicateRoleException($role);
    }

    public function testDefaultState(): void
    {
        $exception = $this->prepareException();

        $this->assertInstanceOf(\Throwable::class, $exception);
        $this->assertSame(
            'The "' . self::DEFAULT_ROLE . '" role already exists in the DB schema.',
            $exception->getMessage()
        );
        $this->assertSame(0, $exception->getCode());
    }
}
