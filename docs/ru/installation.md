# Установка

Предпочтительнее установить этот пакет через [composer](http://getcomposer.org/download/):

```
composer require vjik/yii2-cycle
```

## Настройка

Для работы расширения необходимо сконфигурировать контейнер зависимостей Yii2 и добавить параметры в приложение Yii2.

Пример конфигурации контейнера:

```php
'container' => [
    'definitions' => ArrayHelper::merge(
        require __DIR__ . '/../../vendor/vjik/yii2-cycle/config/common.php',
        [],
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
        'vjik/yii2-cycle' => [
            'dbal' => [
                'default' => 'default',
                'databases' => [
                    'default' => ['connection' => 'mysql']
                ],
                'connections' => [
                    'mysql' => [
                        'driver' => \Spiral\Database\Driver\MySQL\MySQLDriver::class,
                        'connection' => 'mysql:host=localhost;dbname=sdcorp',
                        'username' => 'root',
                        'password' => 'root',
                    ]
                ]
            ],
            'schema-providers' => [
                \Vjik\Yii2\Cycle\Schema\Provider\SimpleCacheSchemaProvider::class => [
                    'key' => 'my-custom-cache-key'
                ],
                \Vjik\Yii2\Cycle\Schema\Provider\FromFileSchemaProvider::class => [
                    'file' => '@runtime/schema.php'
                ],
                \Vjik\Yii2\Cycle\Schema\Provider\FromConveyorSchemaProvider::class,
            ],
            'annotated-entity-paths' => [
                '@domain/news',
            ],
        ],
    ],
);

```

Документация Cycle:

- [Конфигурирование подключений](https://github.com/cycle/docs/blob/master/basic/connect.md)
- [О Reference и Proxy](https://github.com/cycle/docs/blob/master/advanced/promise.md)
