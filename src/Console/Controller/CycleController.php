<?php

namespace Vjik\Yii2\Cycle\Console\Controller;

use Vjik\Yii2\Cycle\Console\Action\Common\SchemaAction;
use Vjik\Yii2\Cycle\Console\Action\Common\SchemaClearAction;
use Vjik\Yii2\Cycle\Console\Action\Common\SchemaPhpAction;
use yii\console\Controller;

class CycleController extends Controller
{

    public function actions()
    {
        return [
            'schema' => SchemaAction::class,
            'schema-php' => SchemaPhpAction::class,
            'schema-clear' => SchemaClearAction::class,
        ];
    }
}
