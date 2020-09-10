<?php

namespace Vjik\Yii2\Cycle\Tests\SchemaManager;

use Vjik\Yii2\Cycle\Exception\BadDeclarationException;
use Vjik\Yii2\Cycle\Tests\SchemaManager\Stub\ArraySchemaProvider;

class ProviderDefinitionTest extends BaseSchemaManagerTest
{
    public function testProviderFromString(): void
    {
        $this->prepareContainer(['provider' => new ArraySchemaProvider(static::SIMPLE_SCHEMA)]);

        $manager = $this->prepareSchemaManager(['provider']);

        $this->assertSame(static::SIMPLE_SCHEMA, $manager->read());
    }

    public function testProviderFromArray(): void
    {
        $this->prepareContainer(['provider' => new ArraySchemaProvider(static::SIMPLE_SCHEMA)]);
        $newSchema = self::ANOTHER_SCHEMA;

        $manager = $this->prepareSchemaManager(['provider' => $newSchema]);

        $this->assertSame($newSchema, $manager->read());
    }

    public function testIgnoreStringKeyIfDefinitionIsNotArray(): void
    {
        $this->prepareContainer(['provider' => new ArraySchemaProvider(static::SIMPLE_SCHEMA)]);
        $provider = new ArraySchemaProvider(static::ANOTHER_SCHEMA);

        $manager = $this->prepareSchemaManager(['provider' => $provider]);

        $this->assertSame(self::ANOTHER_SCHEMA, $manager->read());
    }

    public function testProviderAsObject(): void
    {
        $provider = new ArraySchemaProvider(static::SIMPLE_SCHEMA);

        $manager = $this->prepareSchemaManager([$provider]);

        $this->assertSame(self::SIMPLE_SCHEMA, $manager->read());
    }

    public function testProviderAsBadClassObject(): void
    {
        $provider = new \DateTimeImmutable();
        $manager = $this->prepareSchemaManager([$provider]);

        $this->expectException(BadDeclarationException::class);

        $manager->read();
    }

    public function testShortCircuitInstantiation(): void
    {
        $this->prepareContainer([
            'goodProvider' => new ArraySchemaProvider(self::SIMPLE_SCHEMA),
            'badProvider' => 'not an object',
        ]);

        $manager = $this->prepareSchemaManager(['goodProvider', 'badProvider', 'undefined provider']);

        $this->assertSame(static::SIMPLE_SCHEMA, $manager->read());
    }
}
