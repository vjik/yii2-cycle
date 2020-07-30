<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Schema\Provider;

use Psr\SimpleCache\CacheInterface;
use Vjik\Yii2\Cycle\Schema\SchemaProviderInterface;

final class SimpleCacheSchemaProvider implements SchemaProviderInterface
{
    public const DEFAULT_KEY = 'Cycle-ORM-Schema';
    private $cache;
    private $key = self::DEFAULT_KEY;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function withConfig(array $config): SchemaProviderInterface
    {
        $clone = clone $this;
        $clone->key = $config['key'] ?? self::DEFAULT_KEY;
        return $clone;
    }

    public function read(): ?array
    {
        return $this->cache->get($this->key);
    }

    public function write($schema): bool
    {
        return $this->cache->set($this->key, $schema);
    }

    public function clear(): bool
    {
        return $this->cache->delete($this->key);
    }

    public function isWritable(): bool
    {
        return true;
    }

    public function isReadable(): bool
    {
        return true;
    }
}
