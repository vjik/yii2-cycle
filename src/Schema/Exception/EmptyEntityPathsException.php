<?php

namespace Vjik\Yii2\Cycle\Schema\Exception;

use yii\base\Exception;

class EmptyEntityPathsException extends Exception
{

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Bad declaration of Entity paths';
    }
}
