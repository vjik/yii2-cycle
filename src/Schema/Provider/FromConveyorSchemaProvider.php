<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Schema\Provider;

use Closure;
use Cycle\Schema\Compiler;
use Cycle\Schema\GeneratorInterface;
use Cycle\Schema\Registry;
use Spiral\Database\DatabaseManager;
use Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface;
use Vjik\Yii2\Cycle\Schema\SchemaProviderInterface;

class FromConveyorSchemaProvider implements SchemaProviderInterface
{

    /**
     * @var SchemaConveyorInterface
     */
    private $conveyor;

    /**
     * @var DatabaseManager
     */
    private $dbal;

    /**
     * Additional generators when reading Schema
     * @var string[]|GeneratorInterface[]|Closure[]
     */
    private $generators = [];

    public function __construct(SchemaConveyorInterface $conveyor, DatabaseManager $dbal)
    {
        $this->conveyor = $conveyor;
        $this->dbal = $dbal;
    }

    public function withConfig(array $config): SchemaProviderInterface
    {
        $clone = clone $this;
        $clone->generators = $config['generators'] ?? [];
        return $clone;
    }

    public function read(): ?array
    {
        $generators = $this->getGenerators();
        return (new Compiler())->compile(new Registry($this->dbal), $generators);
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

    private function getGenerators(): array
    {
        $conveyor = clone $this->conveyor;
        // add generators to userland stage
        foreach ($this->generators as $generator) {
            $conveyor->addGenerator(SchemaConveyorInterface::STAGE_USERLAND, $generator);
        }
        return $conveyor->getGenerators();
    }
}
