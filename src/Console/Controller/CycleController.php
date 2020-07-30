<?php

namespace Vjik\Yii2\Cycle\Console\Controller;

use Vjik\Yii2\Cycle\Console\Action\SchemaAction;
use Vjik\Yii2\Cycle\Console\Action\SchemaPhpAction;
use yii\console\Controller;

class CycleController extends Controller
{

    public function actions()
    {
        return [
            'schema' => SchemaAction::class,
            'schema-php' => SchemaPhpAction::class,
        ];
    }
}
