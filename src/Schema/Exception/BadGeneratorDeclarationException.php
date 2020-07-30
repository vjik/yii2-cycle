<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Schema\Exception;

use yii\base\Exception;

class BadGeneratorDeclarationException extends Exception
{

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Bad declaration of schema generator';
    }
}
