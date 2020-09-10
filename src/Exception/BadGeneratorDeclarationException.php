<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Exception;

use Cycle\Schema\GeneratorInterface;

class BadGeneratorDeclarationException extends BadDeclarationException
{

    public function __construct($argument)
    {
        parent::__construct('Generator', GeneratorInterface::class, $argument);
    }
}
