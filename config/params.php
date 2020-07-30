<?php

return [
    'vjik/yii2-cycle' => [

        // DBAL config
        'dbal' => [
            // SQL query logger. Definition of Psr\Log\LoggerInterface
            'query-logger' => null,
            // Default database
            'default' => null,
            'aliases' => [],
            'databases' => [],
            'connections' => [],
        ],

        /**
         * Config for {@see \Vjik\Yii2\Cycle\Factory\OrmFactory}
         * Null, classname or {@see PromiseFactoryInterface} object.
         * @link https://github.com/cycle/docs/blob/master/advanced/promise.md
         */
        'orm-promise-factory' => null,

        /**
         * SchemaProvider list for {@see \Vjik\Yii2\Cycle\Schema\SchemaManager}
         * Array of classname and {@see SchemaProviderInterface} object.
         * You can configure providers if you pass classname as key and parameters as array:
         * [
         *     SimpleCacheSchemaProvider::class => [
         *         'key' => 'my-custom-cache-key'
         *     ],
         *     FromFileSchemaProvider::class => [
         *         'file' => '@runtime/cycle-schema.php'
         *     ],
         *     FromConveyorSchemaProvider::class => [
         *         'generators' => [
         *              Generator\SyncTables::class, // sync table changes to database
         *          ]
         *     ],
         * ]
         */
        'schema-providers' => [],

        /**
         * Config for {@see \Vjik\Yii2\Cycle\Schema\Conveyor\AnnotatedSchemaConveyor}
         * Annotated entity directories list.
         */
        'annotated-entity-paths' => [],
    ],
];
