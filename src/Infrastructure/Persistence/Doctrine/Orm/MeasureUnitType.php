<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Infrastructure\Persistence\Doctrine\Orm;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Tuzex\Ddd\Shared\Domain\Quantity\MeasureUnit;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Gram;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Kilogram;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Liter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Meter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Mililiter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Milimeter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Piece;

final class MeasureUnitType extends Type
{
    private array $supported = [
        'pc' => Piece::class,
        'g' => Gram::class,
        'kg' => Kilogram::class,
        'l' => Liter::class,
        'ml' => Mililiter::class,
        'm' => Meter::class,
        'mm' => Milimeter::class,
    ];

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        $column['length'] = 3;

        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (! $value instanceof MeasureUnit) {
            throw new InvalidArgumentException(
                sprintf('Measure unit must be subclass of "%s", "%s" given.', MeasureUnit::class, gettype($value))
            );
        }

        return $value->symbol;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): MeasureUnit
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException(sprintf('Measure unit symbol must be string, "%s" given.', gettype($value)));
        }

        $class = $this->supported[$value] ?? null;
        if (! is_subclass_of($class, MeasureUnit::class)) {
            throw ConversionException::conversionFailed($value, self::class);
        }

        return new $class();
    }

    public function getName(): string
    {
        return 'measure_unit';
    }
}
