<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Schema\Provider;

use Vjik\Yii2\Cycle\Schema\SchemaProviderInterface;
use Yii;

/**
 * Be careful, using this class may be insecure.
 */
final class FromFileSchemaProvider implements SchemaProviderInterface
{
    private $file = '';

    public function withConfig(array $config): SchemaProviderInterface
    {
        $clone = clone $this;
        // required option
        $clone->file = Yii::getAlias($config['file']);
        return $clone;
    }

    public function read(): ?array
    {
        if (!is_file($this->file)) {
            return null;
        }
        return include $this->file;
    }

    public function write($schema): bool
    {
        return false;
    }

    public function clear(): bool
    {
        return false;
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function isReadable(): bool
    {
        return true;
    }
}
