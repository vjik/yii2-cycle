<?php

declare(strict_types=1);

use Cycle\ORM\Factory;
use Cycle\ORM\FactoryInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Spiral\Database\DatabaseManager;
use Vjik\Yii2\Cycle\Factory\ContainerFactory;
use Vjik\Yii2\Cycle\Factory\CycleDynamicFactory;
use Vjik\Yii2\Cycle\Factory\DbalFactory;
use Vjik\Yii2\Cycle\Factory\OrmFactory;
use Vjik\Yii2\Cycle\Schema\Conveyor\AnnotatedSchemaConveyor;
use Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface;
use Vjik\Yii2\Cycle\Schema\SchemaManager;

return [

    // Cycle DBAL
    DatabaseManager::class => static function ($container) {
        return (new DbalFactory(Yii::$app->params['vjik/yii2-cycle']['dbal']))(ContainerFactory::make($container));
    },

    // Cycle ORM
    ORMInterface::class => static function ($container) {
        return (new OrmFactory(Yii::$app->params['vjik/yii2-cycle']['orm-promise-factory']))(ContainerFactory::make($container));
    },

    // Factory for Cycle ORM
    FactoryInterface::class => static function ($container) {
        return new Factory(
            $container->get(DatabaseManager::class),
            null,
            new CycleDynamicFactory($container),
            ContainerFactory::make($container)
        );
    },

    // Schema Manager
    SchemaManager::class => static function ($container) {
        return new SchemaManager(ContainerFactory::make($container), Yii::$app->params['vjik/yii2-cycle']['schema-providers']);
    },

    // Schema
    SchemaInterface::class => static function ($container) {
        $schema = $container->get(SchemaManager::class)->read();
        if ($schema === null) {
            throw new RuntimeException('Cycle Schema not read.');
        }
        return new Schema($schema);
    },

    // Annotated Schema Conveyor
    SchemaConveyorInterface::class => static function ($container) use (&$params) {
        $conveyor = new AnnotatedSchemaConveyor(ContainerFactory::make($container));
        $conveyor->addEntityPaths(Yii::$app->params['vjik/yii2-cycle']['annotated-entity-paths']);
        return $conveyor;
    },
];
