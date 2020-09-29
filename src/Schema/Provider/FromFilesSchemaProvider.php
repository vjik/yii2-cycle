<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Schema\Provider;

use InvalidArgumentException;
use Vjik\Yii2\Cycle\Exception\DuplicateRoleException;
use Vjik\Yii2\Cycle\Exception\SchemaFileNotFoundException;
use Vjik\Yii2\Cycle\Schema\SchemaProviderInterface;
use Yii;

/**
 * Be careful, using this class may be insecure.
 */
final class FromFilesSchemaProvider implements SchemaProviderInterface
{
    /** @var array Schema files */
    private $files = [];

    /** @var bool Throw exception if file not found */
    private $strict = false;

    public function withConfig(array $config): SchemaProviderInterface
    {
        $files = $config['files'] ?? [];
        if (!is_array($files)) {
            throw new InvalidArgumentException('The "files" parameter must be an array.');
        }
        if (count($files) === 0) {
            throw new InvalidArgumentException('Schema file list is not set.');
        }

        $strict = $config['strict'] ?? $this->strict;
        if (!is_bool($strict)) {
            throw new InvalidArgumentException('The "strict" parameter must be a boolean.');
        }

        $files = array_map(
            function ($file) {
                if (!is_string($file)) {
                    throw new InvalidArgumentException('The "files" parameter must contain string values.');
                }
                return Yii::getAlias($file);
            },
            $files
        );

        $new = clone $this;
        $new->files = $files;
        $new->strict = $strict;
        return $new;
    }

    /**
     * @return array|null
     * @throws DuplicateRoleException
     * @throws SchemaFileNotFoundException
     */
    public function read(): ?array
    {
        $schema = null;

        foreach ($this->files as $file) {
            if (is_file($file)) {
                $schema = $schema ?? [];
                foreach (require $file as $role => $definition) {
                    if (array_key_exists($role, $schema)) {
                        throw new DuplicateRoleException($role);
                    }
                    $schema[$role] = $definition;
                }
            } elseif ($this->strict) {
                throw new SchemaFileNotFoundException($file);
            }
        }

        return $schema;
    }

    public function write(array $schema): bool
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
