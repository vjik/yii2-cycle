# Консольные команды

## Добавление консольного контроллера

В конфигурации консольног приложения добавьте контроллер:

```php
return [
    'controllerMap' => [
        'cycle' => [
            'class' => \Vjik\Yii2\Cycle\Console\Controller\CycleController::class,
        ],
    ],
];
```

## Общие

- `cycle/schema [role]` - Получить информацию об используемой схеме
- `cycle/schema-php [file]` - Экспортировать используемую схему в PHP-файл
