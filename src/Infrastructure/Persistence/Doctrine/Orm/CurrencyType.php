<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Infrastructure\Persistence\Doctrine\Orm;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Tuzex\Ddd\Shared\Domain\Money\Currency;
use Tuzex\Ddd\Shared\Domain\Money\Currency\Euro;
use Tuzex\Ddd\Shared\Domain\Money\Currency\UsDollar;

final class CurrencyType extends Type
{
    private array $supported = [
        'EUR' => Euro::class,
        'USD' => UsDollar::class,
    ];

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        $column['length'] = 3;
        $column['fixed'] = true;

        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (! $value instanceof Currency) {
            throw new InvalidArgumentException(
                sprintf('Currency must be subclass of "%s", "%s" given.', Currency::class, gettype($value))
            );
        }

        return $value->code();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Currency
    {
        if (! is_string($value)) {
            throw new InvalidArgumentException(sprintf('Currency code must be string, "%s" given.', gettype($value)));
        }

        $class = $this->supported[$value] ?? null;
        if (! is_subclass_of($class, Currency::class)) {
            throw ConversionException::conversionFailed($value, self::class);
        }

        return new $class();
    }

    public function getName(): string
    {
        return 'currency';
    }
}
