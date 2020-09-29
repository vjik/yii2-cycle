<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Exception;

use yii\base\Exception;

class DuplicateRoleException extends Exception
{
    public function __construct(string $role)
    {
        parent::__construct('The "' . $role . '" role already exists in the DB schema.');
    }

    public function getName(): string
    {
        return 'Duplicate role in the DB schema';
    }
}
