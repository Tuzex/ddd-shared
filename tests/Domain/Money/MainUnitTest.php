<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Test\Domain\Money;

use PHPUnit\Framework\TestCase;
use Tuzex\Ddd\Shared\Domain\Money\MainUnit;
use Webmozart\Assert\InvalidArgumentException;

final class MainUnitTest extends TestCase
{
    /**
     * @dataProvider provideValidCurrencies
     */
    public function testsItReturnsValidParameters(string $code, string $symbol): void
    {
        $mainUnit = new MainUnit($code, $symbol);

        $this->assertSame($code, $mainUnit->code);
        $this->assertSame($symbol, $mainUnit->symbol);
    }

    public function provideValidCurrencies(): array
    {
        return [
            'Euro' => ['EUR', '€'],
            'US Dollar' => ['USD', '$'],
        ];
    }

    /**
     * @dataProvider provideInvalidCurrencies
     */
    public function testsItThrowsExceptionIfParameterIsInvalid(string $code, string $symbol): void
    {
        $this->expectException(InvalidArgumentException::class);

        new MainUnit($code, $symbol);
    }

    public function provideInvalidCurrencies(): array
    {
        return [
            'Euro' => ['EURO', '€'],
            'US Dollar' => ['USD', ''],
        ];
    }
}
