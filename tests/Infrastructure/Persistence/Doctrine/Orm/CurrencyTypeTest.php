<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Test\Infrastructure\Persistence\Doctrine\Orm;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tuzex\Ddd\Shared\Domain\Money\Currency;
use Tuzex\Ddd\Shared\Domain\Money\Currency\Euro;
use Tuzex\Ddd\Shared\Domain\Money\Currency\UsDollar;
use Tuzex\Ddd\Shared\Infrastructure\Persistence\Doctrine\Orm\CurrencyType;

final class CurrencyTypeTest extends TestCase
{
    private CurrencyType $type;
    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->type = new CurrencyType();
        $this->platform = $this->createMock(AbstractPlatform::class);

        parent::setUp();
    }

    public function testItReturnsValidTypeName(): void
    {
        $this->assertSame('currency', $this->type->getName());
    }

    /**
     * @dataProvider provideCurrencies
     */
    public function testItConvertsCurrencyToCurrencyCode(Currency $currency): void
    {
        $ConvertedCurrencyCode = $this->type->convertToDatabaseValue($currency, $this->platform);

        $this->assertSame($currency->code(), $ConvertedCurrencyCode);
    }

    /**
     * @dataProvider provideCurrencies
     */
    public function testItConvertsSupportedCurrencyCodeToCurrency(Currency $currency): void
    {
        $convertedCurrency = $this->type->convertToPHPValue($currency->code(), $this->platform);

        $this->assertSame($currency::class, $convertedCurrency::class);
    }

    public function provideCurrencies(): array
    {
        return [
            Euro::class => [new Euro()],
            UsDollar::class => [new UsDollar()],
        ];
    }

    public function testItThrowsExceptionIfCurrencyCodeIsNotSupported(): void
    {
        $unsupportedCurrencyCode = 'XXX';

        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue($unsupportedCurrencyCode, $this->platform);
    }

    public function testItThrowsExceptionIfDatabaseValueIsNotString(): void
    {
        $databaseValue = new stdClass();

        $this->expectException(InvalidArgumentException::class);

        $this->type->convertToPHPValue($databaseValue, $this->platform);
    }

    public function testItThrowsExceptionIfPhpValueIsNotCurrencyObject(): void
    {
        $phpValue = 'EUR';

        $this->expectException(InvalidArgumentException::class);

        $this->type->convertToDatabaseValue($phpValue, $this->platform);
    }
}
