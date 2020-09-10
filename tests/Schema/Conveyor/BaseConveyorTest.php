<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Tests\Schema\Conveyor;

use Cycle\Schema\GeneratorInterface;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use stdClass;
use Vjik\Yii2\Cycle\Exception\BadGeneratorDeclarationException;
use Vjik\Yii2\Cycle\Schema\SchemaConveyorInterface;
use Vjik\Yii2\Cycle\Tests\Factory\Stub\FakeContainer;
use Vjik\Yii2\Cycle\Tests\Schema\Conveyor\Stub\FakeGenerator;
use Vjik\Yii2\Psr\ContainerProxy\NotFoundException;

abstract class BaseConveyorTest extends TestCase
{

    public function badGeneratorProvider(): array
    {
        return [
            [stdClass::class, '#Instance of ' . stdClass::class . '[\s\w]+instead#'],
            [new DateTimeImmutable(), '#Instance of ' . DateTimeImmutable::class . ' [\s\w]+instead#'],
            [
                function () {
                    return new DateTime();
                },
                '#Instance of ' . DateTime::class . ' [\s\w]+instead#'
            ],
            [null, '#NULL [\s\w]+instead#'],
            [42, '#Integer [\s\w]+instead#'],
        ];
    }

    /**
     * @dataProvider badGeneratorProvider
     */
    public function testAddWrongGenerator($badGenerator, string $message): void
    {
        $conveyor = $this->createConveyor();
        $conveyor->addGenerator($conveyor::STAGE_USERLAND, $badGenerator);

        $this->expectException(BadGeneratorDeclarationException::class);
        $this->expectExceptionMessageMatches($message);

        $conveyor->getGenerators();
    }

    protected function getGeneratorClassList(SchemaConveyorInterface $conveyor): array
    {
        return array_map(
            function ($value) {
                return $value instanceof FakeGenerator ? $value->originClass() : get_class($value);
            },
            $conveyor->getGenerators()
        );
    }

    protected function prepareContainer(): FakeContainer
    {
        return new FakeContainer([
            stdClass::class => new stdClass(),
        ], function (string $id) {
            if (is_a($id, GeneratorInterface::class, true)) {
                return new FakeGenerator($id);
            }
            throw new NotFoundException($id);
        });
    }
}
