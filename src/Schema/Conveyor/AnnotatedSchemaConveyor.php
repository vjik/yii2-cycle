<?php

namespace Vjik\Yii2\Cycle\Schema\Conveyor;

use Cycle\Annotated\Embeddings;
use Cycle\Annotated\Entities;
use Cycle\Annotated\MergeColumns;
use Cycle\Annotated\MergeIndexes;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;
use Vjik\Yii2\Cycle\Schema\Exception\EmptyEntityPathsException;
use Yii;

final class AnnotatedSchemaConveyor extends SchemaConveyor
{
    /** @var string[] */
    private $entityPaths = [];

    private $tableNaming = Entities::TABLE_NAMING_SINGULAR;

    private $isAddedAnnotated = false;

    public function setTableNaming(int $type): void
    {
        $this->tableNaming = $type;
    }

    public function getTableNaming(): int
    {
        return $this->tableNaming;
    }

    /**
     * @param string[] $paths
     */
    public function addEntityPaths(array $paths): void
    {
        $this->entityPaths = array_merge($this->entityPaths, $paths);
    }

    public function getGenerators(): array
    {
        $this->addAnnotatedGenerators();
        return parent::getGenerators();
    }

    /**
     * Add some generators in this conveyor into the INDEX stage
     * Added generators will search for entity classes and read their annotations
     */
    private function addAnnotatedGenerators(): void
    {
        if ($this->isAddedAnnotated) {
            return;
        }
        // autoload annotations
        AnnotationRegistry::registerLoader('class_exists');

        $this->isAddedAnnotated = true;
        $classLocator = $this->getEntityClassLocator();

        // register embeddable entities
        $this->conveyor[SchemaConveyor::STAGE_INDEX][] = new Embeddings($classLocator);
        // register annotated entities
        $this->conveyor[SchemaConveyor::STAGE_INDEX][] = new Entities($classLocator, null, $this->tableNaming);
        // add @Table(columns) declarations
        $this->conveyor[SchemaConveyor::STAGE_INDEX][] = MergeColumns::class;
        // add @Table(indexes) declarations
        $this->conveyor[SchemaConveyor::STAGE_RENDER][] = MergeIndexes::class;
    }

    private function getEntityClassLocator(): ClassLocator
    {
        $list = [];
        foreach ($this->entityPaths as $path) {
            $list[] = Yii::getAlias($path);
        }

        if (!count($list)) {
            throw new EmptyEntityPathsException();
        }

        $finder = (new Finder())
            ->files()
            ->in($list);

        return new ClassLocator($finder);
    }
}
