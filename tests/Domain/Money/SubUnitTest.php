<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Test\Domain\Money;

use PHPUnit\Framework\TestCase;
use Tuzex\Ddd\Shared\Domain\Money\SubUnit;
use Webmozart\Assert\InvalidArgumentException;

final class SubUnitTest extends TestCase
{
    /**
     * @dataProvider provideValidCurrencies
     */
    public function testsItReturnsValidParameters(string $code, string $symbol, int $fraction): void
    {
        $subUnit = new SubUnit($code, $symbol, $fraction);

        $this->assertSame($code, $subUnit->code);
        $this->assertSame($symbol, $subUnit->symbol);
        $this->assertSame($fraction, $subUnit->fraction);
    }

    public function provideValidCurrencies(): array
    {
        return [
            'Euro' => ['cent', 'c', 100],
            'Japanese yen' => ['sen', '錢', 1],
        ];
    }

    /**
     * @dataProvider provideInvalidCurrencies
     */
    public function testsItThrowsExceptionIfParameterIsInvalid(string $code, string $symbol, int $fraction): void
    {
        $this->expectException(InvalidArgumentException::class);

        new SubUnit($code, $symbol, $fraction);
    }

    public function provideInvalidCurrencies(): array
    {
        return [
            'Euro' => ['', 'c', 100],
            'Japanese yen' => ['sen', '', 1],
            'US Dollar yen' => ['cent', 'c', 4],
        ];
    }
}
