<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Test\Domain\Money;

use PHPUnit\Framework\TestCase;
use Tuzex\Ddd\Shared\Domain\Money\Currency;
use Tuzex\Ddd\Shared\Domain\Money\Currency\Euro;
use Tuzex\Ddd\Shared\Domain\Money\Currency\UsDollar;
use Tuzex\Ddd\Shared\Domain\Money\NominalValue;

final class NominalValueTest extends TestCase
{
    /**
     * @dataProvider provideDataForCreation
     */
    public function testItReturnsValidUnits(int $sub, int $fraction, float $main): void
    {
        $nominalValue = new NominalValue($sub, $fraction);

        $this->assertSame($sub, $nominalValue->subValue);
        $this->assertSame($main, $nominalValue->mainValue);
    }

    public function provideDataForCreation(): array
    {
        return [
            'one' => [1234, 1, 1234.0],
            'ten' => [1234, 10, 123.4],
            'hundred' => [1234, 100, 12.34],
            'thousand' => [1234, 1000, 1.234],
        ];
    }

    /**
     * @dataProvider provideDataForEquality
     */
    public function testItEquals(int $origin, int $another, bool $result): void
    {
        $origin = new NominalValue($origin, 10);
        $another = new NominalValue($another, 10);

        $this->assertSame($result, $origin->equals($another));
    }

    public function provideDataForEquality(): array
    {
        return [
            'equal' => [1234, 1234, true],
            'unequal' => [1234, 1467, false],
        ];
    }

    /**
     * @dataProvider provideDataForComparison
     */
    public function testItCompares(int $origin, int $another, int $result): void
    {
        $origin = new NominalValue($origin, 10);
        $another = new NominalValue($another, 10);

        $this->assertSame($result, $origin->compare($another));
    }

    public function provideDataForComparison(): array
    {
        return [
            'less' => [1234, 1467, -1],
            'equal' => [1234, 1234, 0],
            'greater' => [1234, 800, 1],
        ];
    }

    /**
     * @dataProvider provideDataForFactory
     */
    public function testItCreatesFromCurrency(int $sub, Currency $currency, float $main): void
    {
        $nominalValue = NominalValue::set($sub, $currency);

        $this->assertSame($sub, $nominalValue->subValue);
        $this->assertSame($main, $nominalValue->mainValue);
    }

    public function provideDataForFactory(): array
    {
        return [
            'Euro' => [1234, new Euro(), 12.34],
            'UsDollar' => [1234, new UsDollar(), 12.34],
        ];
    }
}
