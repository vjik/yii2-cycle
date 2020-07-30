<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Schema\Provider;

use Vjik\Yii2\Cycle\Schema\SchemaProviderInterface;
use yii\base\BaseObject;
use yii\caching\CacheInterface;
use yii\di\Instance;

final class SimpleCacheSchemaProvider extends BaseObject implements SchemaProviderInterface
{
    public const DEFAULT_KEY = 'Cycle-ORM-Schema';

    /**
     * @var CacheInterface|array|string the cache used to improve RBAC performance. This can be one of the following:
     *
     * - an application component ID (e.g. `cache`)
     * - a configuration array
     * - a [[\yii\caching\Cache]] object
     */
    public $cache = 'cache';

    private $key = self::DEFAULT_KEY;

    public function init()
    {
        parent::init();
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, CacheInterface::class);
        }
    }

    public function withConfig(array $config): SchemaProviderInterface
    {
        $clone = clone $this;
        $clone->key = $config['key'] ?? self::DEFAULT_KEY;
        return $clone;
    }

    public function read(): ?array
    {
        $value = $this->cache->get($this->key);
        return is_array($value) ? $value : null;
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
