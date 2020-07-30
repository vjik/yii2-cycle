<?php

namespace Vjik\Yii2\Cycle\Console\Action\Common;

use Vjik\Yii2\Cycle\Schema\SchemaManager;
use Yii;
use yii\base\Action;
use yii\console\ExitCode;

class SchemaClearAction extends Action
{

    public function run()
    {
        Yii::$container->get(SchemaManager::class)->clear();
        $this->controller->stdout('Schema cleared.' . PHP_EOL);
        return ExitCode::OK;
    }
}
