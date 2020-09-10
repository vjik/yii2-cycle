<?php

namespace PHPSTORM_META {

    expectedArguments(
        \Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface::addGenerator(),
        0,
        argumentsSet('\Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface::STAGES'),
    );

    registerArgumentsSet(
        '\Cycle\Annotated\Entities::TABLE_NAMINGS',
        \Cycle\Annotated\Entities::TABLE_NAMING_PLURAL,
        \Cycle\Annotated\Entities::TABLE_NAMING_SINGULAR,
        \Cycle\Annotated\Entities::TABLE_NAMING_NONE,
    );
    registerArgumentsSet(
        '\Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface::STAGES',
        \Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface::STAGE_INDEX,
        \Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface::STAGE_RENDER,
        \Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface::STAGE_USERLAND,
        \Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface::STAGE_POSTPROCESS,
    );
}
