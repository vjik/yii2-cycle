<?php

namespace Vjik\Yii2\Cycle\Console\Action\Common;

use Cycle\ORM\SchemaInterface;
use RuntimeException;
use Vjik\Yii2\Cycle\Helper\SchemaToPHP;
use Yii;
use yii\base\Action;
use yii\console\ExitCode;
use yii\helpers\Console;

class SchemaPhpAction extends Action
{

    protected $schema;

    public function __construct(
        $id,
        $controller,
        SchemaInterface $schema,
        $config = []
    ) {
        $this->schema = $schema;
        parent::__construct($id, $controller, $config);
    }

    public function run(?string $file = null)
    {
        $content = (new SchemaToPHP($this->schema))->render();
        if ($file) {
            $file = Yii::getAlias($file);
            $this->controller->stdout('Destination: ');
            $this->controller->stdout($file, Console::FG_CYAN);
            $this->controller->stdout(PHP_EOL);

            // Dir exists
            $dir = dirname($file);
            if (!is_dir($dir)) {
                throw new RuntimeException("Directory {$dir} not found");
            }

            if (file_put_contents($file, $content) === false) {
                return ExitCode::UNSPECIFIED_ERROR;
            }
        } else {
            echo $content;
        }
        return ExitCode::OK;
    }
}
