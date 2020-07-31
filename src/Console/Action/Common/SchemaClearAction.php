<?php

namespace Vjik\Yii2\Cycle\Console\Action\Common;

use Vjik\Yii2\Cycle\Schema\SchemaManager;
use yii\base\Action;
use yii\console\ExitCode;

class SchemaClearAction extends Action
{

    protected $schemaManager;

    public function __construct(
        $id,
        $controller,
        SchemaManager $schemaManager,
        $config = []
    ) {
        $this->schemaManager = $schemaManager;
        parent::__construct($id, $controller, $config);
    }

    public function run()
    {
        $this->schemaManager->clear();
        $this->controller->stdout('Schema cleared.' . PHP_EOL);
        return ExitCode::OK;
    }
}
