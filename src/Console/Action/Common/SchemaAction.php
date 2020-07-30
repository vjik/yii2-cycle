<?php

namespace Vjik\Yii2\Cycle\Console\Action\Common;

use Cycle\ORM\Relation;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Yii;
use yii\base\Action;
use yii\console\ExitCode;
use yii\helpers\Console;

class SchemaAction extends Action
{

    private const STR_RELATION = [
        Relation::HAS_ONE => 'has one',
        Relation::HAS_MANY => 'has many',
        Relation::BELONGS_TO => 'belongs to',
        Relation::REFERS_TO => 'refers to',
        Relation::MANY_TO_MANY => 'many to many',
        Relation::BELONGS_TO_MORPHED => 'belongs to morphed',
        Relation::MORPHED_HAS_ONE => 'morphed has one',
        Relation::MORPHED_HAS_MANY => 'morphed has many',
    ];

    private const STR_PREFETCH_MODE = [
        Relation::LOAD_PROMISE => 'promise',
        Relation::LOAD_EAGER => 'eager',
    ];

    public function run(?string $role = null)
    {
        $result = true;
        $schema = Yii::$container->get(SchemaInterface::class);
        $roles = $role !== null ? explode(',', $role) : $schema->getRoles();

        foreach ($roles as $roleName) {
            $result = $this->displaySchema($schema, $roleName) && $result;
        }

        return $result ? ExitCode::OK : ExitCode::UNSPECIFIED_ERROR;
    }

    /**
     * Write a role schema in the output
     *
     * @param SchemaInterface $schema Data schema
     * @param string $role Role to display
     * @return bool
     */
    private function displaySchema(SchemaInterface $schema, string $role): bool
    {
        if (!$schema->defines($role)) {
            $this->controller->stdout('Role', Console::FG_RED);
            $this->controller->stdout(' ');
            $this->controller->stdout('[' . $role . ']', Console::FG_PURPLE);
            $this->controller->stdout(' ');
            $this->controller->stdout('not defined!' . PHP_EOL, Console::FG_RED);
            return false;
        }

        $this->controller->stdout('[' . $role, Console::FG_PURPLE);
        $alias = $schema->resolveAlias($role);
        // alias
        if ($alias !== null && $alias !== $role) {
            $this->controller->stdout('=>');
            $this->controller->stdout($alias, Console::FG_PURPLE);
        }
        $this->controller->stdout(']', Console::FG_PURPLE);

        // database and table
        $database = $schema->define($role, Schema::DATABASE);
        $table = $schema->define($role, Schema::TABLE);
        if ($database !== null) {
            $this->controller->stdout(' :: ');
            $this->controller->stdout($database, Console::FG_GREEN);
            $this->controller->stdout('.');
            $this->controller->stdout($table, Console::FG_GREEN);
        }
        $this->controller->stdout(PHP_EOL);

        // Entity
        $entity = $schema->define($role, Schema::ENTITY);
        $this->controller->stdout('   Entity     : ');
        $entity === null ? $this->controller->stdout('no entity') : $this->controller->stdout($entity, Console::FG_BLUE);
        $this->controller->stdout(PHP_EOL);

        // Mapper
        $mapper = $schema->define($role, Schema::MAPPER);
        $this->controller->stdout('   Mapper     : ');
        $mapper === null ? $this->controller->stdout('no mapper') : $this->controller->stdout($mapper, Console::FG_BLUE);
        $this->controller->stdout(PHP_EOL);

        // Constrain
        $constrain = $schema->define($role, Schema::CONSTRAIN);
        $this->controller->stdout('   Constrain  : ');
        $constrain === null ? $this->controller->stdout('no constrain') : $this->controller->stdout($constrain, Console::FG_BLUE);
        $this->controller->stdout(PHP_EOL);

        // Repository
        $repository = $schema->define($role, Schema::REPOSITORY);
        $this->controller->stdout('   Repository : ');
        $repository === null ? $this->controller->stdout('no repository') : $this->controller->stdout($repository, Console::FG_BLUE);
        $this->controller->stdout(PHP_EOL);

        // PK
        $pk = $schema->define($role, Schema::PRIMARY_KEY);
        $this->controller->stdout('   Primary key: ');
        $pk === null ? $this->controller->stdout('no primary key') : $this->controller->stdout($pk, Console::FG_GREEN);
        $this->controller->stdout(PHP_EOL);

        // Fields
        $columns = $schema->define($role, Schema::COLUMNS);
        $this->controller->stdout('   Fields     :' . PHP_EOL);
        $this->controller->stdout('     (');
        $this->controller->stdout('property', Console::FG_CYAN);
        $this->controller->stdout(' -> ');
        $this->controller->stdout('db.field', Console::FG_GREEN);
        $this->controller->stdout(' -> ');
        $this->controller->stdout('typecast', Console::FG_BLUE);
        $this->controller->stdout(')' . PHP_EOL);
        $types = $schema->define($role, Schema::TYPECAST);
        foreach ($columns as $property => $field) {
            $typecast = $types[$property] ?? $types[$field] ?? null;
            $this->controller->stdout('     ');
            $this->controller->stdout($property, Console::FG_CYAN);
            $this->controller->stdout(' -> ');
            $this->controller->stdout($field, Console::FG_GREEN);
            if ($typecast !== null) {
                $this->controller->stdout(' -> ');
                $this->controller->stdout(implode('::', (array)$typecast), Console::FG_BLUE);
            }
            $this->controller->stdout(PHP_EOL);
        }

        // Relations
        $relations = $schema->define($role, Schema::RELATIONS);
        if (count($relations) > 0) {
            $this->controller->stdout('   Relations  :' . PHP_EOL);
            foreach ($relations as $field => $relation) {
                $type = self::STR_RELATION[$relation[Relation::TYPE] ?? ''] ?? '?';
                $target = $relation[Relation::TARGET] ?? '?';
                $loading = self::STR_PREFETCH_MODE[$relation[Relation::LOAD] ?? ''] ?? '?';
                $relSchema = $relation[Relation::SCHEMA];
                $innerKey = $relSchema[Relation::INNER_KEY] ?? '?';
                $outerKey = $relSchema[Relation::OUTER_KEY] ?? '?';
                $where = $relSchema[Relation::WHERE] ?? [];
                $cascade = $relSchema[Relation::CASCADE] ?? null;
                $cascadeStr = $cascade ? 'cascaded' : 'not cascaded';
                $nullable = $relSchema[Relation::NULLABLE] ?? null;
                $nullableStr = $nullable ? 'nullable' : ($nullable === false ? 'not null' : 'n/a');
                $morphKey = $relSchema[Relation::MORPH_KEY] ?? null;
                // Many-To-Many relation(s) options
                $mmInnerKey = $relSchema[Relation::THROUGH_INNER_KEY] ?? '?';
                $mmOuterKey = $relSchema[Relation::THROUGH_OUTER_KEY] ?? '?';
                $mmEntity = $relSchema[Relation::THROUGH_ENTITY] ?? null;
                $mmWhere = $relSchema[Relation::THROUGH_WHERE] ?? [];
                // print
                $this->controller->stdout('     ');
                $this->controller->stdout($role, Console::FG_PURPLE);
                $this->controller->stdout('->');
                $this->controller->stdout($field, Console::FG_CYAN);
                $this->controller->stdout(" {$type} ");
                $this->controller->stdout($target, Console::FG_PURPLE);
                $this->controller->stdout(" {$loading} load");
                if ($morphKey !== null) {
                    $this->controller->stdout('       Morphed key: ');
                    $this->controller->stdout($morphKey, Console::FG_GREEN);
                    $this->controller->stdout(PHP_EOL);
                }
                $this->controller->stdout(' ');
                $this->controller->stdout($cascadeStr, Console::FG_YELLOW);
                $this->controller->stdout(PHP_EOL);
                $this->controller->stdout("       {$nullableStr} ");
                $this->controller->stdout($table, Console::FG_GREEN);
                $this->controller->stdout('.');
                $this->controller->stdout($innerKey, Console::FG_GREEN);
                $this->controller->stdout(' <=');
                if ($mmEntity !== null) {
                    $this->controller->stdout(' ');
                    $this->controller->stdout($mmEntity, Console::FG_PURPLE);
                    $this->controller->stdout('.');
                    $this->controller->stdout($mmInnerKey, Console::FG_GREEN);
                    $this->controller->stdout('|');
                    $this->controller->stdout($mmEntity, Console::FG_PURPLE);
                    $this->controller->stdout('.');
                    $this->controller->stdout($mmOuterKey, Console::FG_GREEN);
                    $this->controller->stdout(' ');
                }
                $this->controller->stdout('=> ');
                $this->controller->stdout($target, Console::FG_PURPLE);
                $this->controller->stdout('.');
                $this->controller->stdout($outerKey, Console::FG_GREEN);
                $this->controller->stdout(PHP_EOL);
                if (count($where)) {
                    $this->controller->stdout("       Where:");
                    $this->controller->stdout(str_replace(["\r\n", "\n"], "\n       ", "\n" . print_r($where, 1)) . PHP_EOL);
                }
                if (count($mmWhere)) {
                    $this->controller->stdout("       Through where:");
                    $this->controller->stdout(str_replace(["\r\n", "\n"], "\n       ", "\n" . print_r($mmWhere, 1)) . PHP_EOL);
                }
            }
        } else {
            $this->controller->stdout('   No relations' . PHP_EOL);
        }
        return true;
    }
}
