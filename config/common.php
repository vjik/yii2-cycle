<?php

declare(strict_types=1);

use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Spiral\Database\DatabaseManager;
use Vjik\Yii2\Cycle\Factory\DbalFactory;
use Vjik\Yii2\Cycle\Schema\Conveyor\AnnotatedSchemaConveyor;
use Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface;
use Vjik\Yii2\Cycle\Schema\SchemaManager;

return [

    // Cycle DBAL
    DatabaseManager::class => static function ($container, $p, $c) {
        return (new DbalFactory(Yii::$app->params['vjik/yii2-cycle']['dbal']))();
    },

    // Schema Manager
    SchemaManager::class => static function ($container, $p, $c) {
        return new SchemaManager(Yii::$app->params['vjik/yii2-cycle']['schema-providers']);
    },

    // Schema
    SchemaInterface::class => static function ($container, $p, $c) {
        $schema = $container->get(SchemaManager::class)->read();
        if ($schema === null) {
            throw new RuntimeException('Cycle Schema not read.');
        }
        return new Schema($schema);
    },

    // Annotated Schema Conveyor
    SchemaConveyorInterface::class => static function ($container, $p, $c) use (&$params) {
        $conveyor = new AnnotatedSchemaConveyor();
        $conveyor->addEntityPaths(Yii::$app->params['vjik/yii2-cycle']['annotated-entity-paths']);
        return $conveyor;
    },
];
