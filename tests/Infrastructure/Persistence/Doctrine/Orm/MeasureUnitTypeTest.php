<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Test\Infrastructure\Persistence\Doctrine\Orm;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tuzex\Ddd\Shared\Domain\Quantity\MeasureUnit;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Gram;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Kilogram;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Liter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Meter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Mililiter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Milimeter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Piece;
use Tuzex\Ddd\Shared\Infrastructure\Persistence\Doctrine\Orm\MeasureUnitType;

final class MeasureUnitTypeTest extends TestCase
{
    private MeasureUnitType $type;
    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->type = new MeasureUnitType();
        $this->platform = $this->createMock(AbstractPlatform::class);

        parent::setUp();
    }

    public function testItReturnsValidTypeName(): void
    {
        $this->assertSame('measure_unit', $this->type->getName());
    }

    /**
     * @dataProvider provideMeasureUnit
     */
    public function testItConvertsMeasureUnitToMeasureUnitSymbol(MeasureUnit $measureUnit): void
    {
        $ConvertedMeasureUnit = $this->type->convertToDatabaseValue($measureUnit, $this->platform);

        $this->assertSame($measureUnit->symbol, $ConvertedMeasureUnit);
    }

    /**
     * @dataProvider provideMeasureUnit
     */
    public function testItConvertsSupportedMeasureUnitToMeasureUnit(MeasureUnit $measureUnit): void
    {
        $convertedMeasureUnit = $this->type->convertToPHPValue($measureUnit->symbol, $this->platform);

        $this->assertSame($measureUnit::class, $convertedMeasureUnit::class);
    }

    public function provideMeasureUnit(): array
    {
        return [
            Gram::class => [new Gram()],
            Kilogram::class => [new Kilogram()],
            Liter::class => [new Liter()],
            Meter::class => [new Meter()],
            Mililiter::class => [new Mililiter()],
            Milimeter::class => [new Milimeter()],
            Piece::class => [new Piece()],
        ];
    }

    public function testItThrowsExceptionIfMeasureUnitIsNotSupported(): void
    {
        $unsupportedMeasureUnit = 'xx';

        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue($unsupportedMeasureUnit, $this->platform);
    }

    public function testItThrowsExceptionIfDatabaseValueIsNotString(): void
    {
        $databaseValue = new stdClass();

        $this->expectException(InvalidArgumentException::class);

        $this->type->convertToPHPValue($databaseValue, $this->platform);
    }

    public function testItThrowsExceptionIfPhpValueIsNotMeasureUnitObject(): void
    {
        $phpValue = 'l';

        $this->expectException(InvalidArgumentException::class);

        $this->type->convertToDatabaseValue($phpValue, $this->platform);
    }
}
