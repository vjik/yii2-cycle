# Установка

Предпочтительнее установить этот пакет через [composer](http://getcomposer.org/download/):

```
composer require vjik/yii2-cycle
```

## Настройка

Для работы расширения необходимо сконфигурировать контейнер зависимостей Yii2 и добавить параметры в приложение Yii2.

Пример конфигурации контейнера:

```php
use yii\helpers\ArrayHelper;
...
'container' => [
    'singletons' => ArrayHelper::merge(
        require __DIR__ . '/../../vendor/vjik/yii2-cycle/config/common.php',
        [
            ...    
        ],
    ),
],
```

... и добавления параметров:

```php
<?php

use yii\helpers\ArrayHelper;

return ArrayHelper::merge(
    require __DIR__ . '/../../vendor/vjik/yii2-cycle/config/params.php',
    [
        // Общий конфиг Cycle
        'vjik/yii2-cycle' => [
            // Конфиг Cycle DBAL
            'dbal' => [
                /**
                 * Логгер SQL запросов
                 * Вы можете использовать любой PSR-совместимый логгер
                 */
                'query-logger' => null,
                // БД по умолчанию (из списка 'databases')
                'default' => 'default',
                'databases' => [
                    'default' => ['connection' => 'mysql']
                ],
                'connections' => [
                    // Пример настроек подключения к MySQL:
                    'mysql' => [
                        'driver' => \Spiral\Database\Driver\MySQL\MySQLDriver::class,
                        // Синтаксис подключения описан в https://www.php.net/manual/ru/pdo.construct.php, смотрите DSN
                        'connection' => 'mysql:host=localhost;dbname=yii2demo',
                        'username' => 'root',
                        'password' => 'root',
                    ]
                ]
            ],

            /**
             * Конфиг для фабрики ORM {@see \Vjik\Yii2\Cycle\Factory\OrmFactory}
             * Указывается определение класса {@see \Cycle\ORM\PromiseFactoryInterface} или null.
             * Документация: @link https://github.com/cycle/docs/blob/master/advanced/promise.md
             */
            'orm-promise-factory' => null,

             /**
              * Список поставщиков схемы БД для {@see \Vjik\Yii2\Cycle\Schema\SchemaManager}
              * Поставщики схемы реализуют класс {@see SchemaProviderInterface}.
              * Конфигурируется перечислением имён классов поставщиков. Вы здесь можете конфигурировать также и поставщиков,
              * указывая имя класса поставщика в качестве ключа элемента, а конфиг в виде массива элемента:
              */
            'schema-providers' => [
                \Vjik\Yii2\Cycle\Schema\Provider\SimpleCacheSchemaProvider::class => [
                    'key' => 'my-custom-cache-key'
                ],
                \Vjik\Yii2\Cycle\Schema\Provider\FromFileSchemaProvider::class => [
                    'file' => '@runtime/schema.php'
                ],
                \Vjik\Yii2\Cycle\Schema\Provider\FromConveyorSchemaProvider::class,
            ],

            /**
             * Настройка для класса {@see \Vjik\Yii2\Cycle\Schema\Conveyor\AnnotatedSchemaConveyor}
             * Здесь указывается список папок с сущностями.
             * В путях поддерживаются псевдонимы Yii2.
             */
            'annotated-entity-paths' => [
                '@domain/Entity',
            ],
        ],
    ]
);

```

Документация Cycle:

- [Конфигурирование подключений](https://github.com/cycle/docs/blob/master/basic/connect.md)
- [О Reference и Proxy](https://github.com/cycle/docs/blob/master/advanced/promise.md)
