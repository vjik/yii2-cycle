<?php

namespace Vjik\Yii2\Cycle\Tests\SchemaManager\Stub;

use Vjik\Yii2\Cycle\Schema\SchemaProviderInterface;

class SameOriginProvider implements SchemaProviderInterface
{
    public const OPTION_READABLE = 'readable';
    public const OPTION_WRITABLE = 'writable';
    public const OPTION_CLEARABLE = 'clearable';
    public const EXCEPTION_ON_WRITE = 'exception_on_write';
    public const EXCEPTION_ON_CLEAR = 'exception_on_clear';

    protected $schema;

    private $readable = true;
    private $writable = true;
    private $clearable = true;
    private $exceptionOnWrite = false;
    private $exceptionOnClear = false;
    public function __construct($schema)
    {
        $this->schema = $schema;
    }
    public function withConfig(array $config): SchemaProviderInterface
    {
        $new = clone $this;
        $new->schema = &$this->schema;
        if (array_key_exists(self::OPTION_READABLE, $config)) {
            $new->readable = $config[self::OPTION_READABLE];
        }
        if (array_key_exists(self::OPTION_WRITABLE, $config)) {
            $new->writable = $config[self::OPTION_WRITABLE];
        }
        if (array_key_exists(self::OPTION_CLEARABLE, $config)) {
            $new->clearable = $config[self::OPTION_CLEARABLE];
        }
        if (array_key_exists(self::EXCEPTION_ON_WRITE, $config)) {
            $new->exceptionOnWrite = $config[self::EXCEPTION_ON_WRITE];
        }
        if (array_key_exists(self::EXCEPTION_ON_CLEAR, $config)) {
            $new->exceptionOnClear = $config[self::EXCEPTION_ON_CLEAR];
        }
        return $new;
    }
    public function isWritable(): bool
    {
        return $this->writable;
    }
    public function isReadable(): bool
    {
        return $this->readable;
    }
    public function read(): ?array
    {
        if (!$this->readable) {
            throw new \RuntimeException('Schema can\'t be raed.');
        }
        return $this->schema;
    }
    public function write(array $schema): bool
    {
        if ($this->exceptionOnWrite) {
            throw new \RuntimeException('Schema can\'t be write.');
        }
        if (!$this->writable) {
            return false;
        }
        $this->schema = $schema;
        return true;
    }
    public function clear(): bool
    {
        if ($this->exceptionOnClear) {
            throw new \RuntimeException('Schema cannot be cleared.');
        }
        if (!$this->clearable) {
            return false;
        }
        $this->schema = null;
        return true;
    }
}
