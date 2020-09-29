<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Exception;

use yii\base\Exception;

class SchemaFileNotFoundException extends Exception
{
    public function __construct(string $file)
    {
        parent::__construct('Schema file "' . $file . '" not found.');
    }

    public function getName(): string
    {
        return 'Schema file not found';
    }
}
