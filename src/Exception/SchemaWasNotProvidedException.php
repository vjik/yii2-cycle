<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Exception;

use RuntimeException;

class SchemaWasNotProvidedException extends RuntimeException
{

    public function __construct()
    {
        parent::__construct('Schema was not provided.');
    }
}
