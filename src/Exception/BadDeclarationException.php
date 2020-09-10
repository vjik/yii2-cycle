<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Exception;

use Exception;

class BadDeclarationException extends Exception
{

    public function __construct(string $parameter, string $class, $argument)
    {
        $type = is_object($argument) ? 'Instance of ' . get_class($argument) : ucfirst(gettype($argument));
        parent::__construct(sprintf(
            '%s should be instance of %s or its declaration. %s was received instead.',
            $parameter,
            $class,
            $type
        ));
    }
}
