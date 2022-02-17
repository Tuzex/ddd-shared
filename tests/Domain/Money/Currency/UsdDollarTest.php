<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Test\Domain\Money\Currency;

use PHPUnit\Framework\TestCase;
use Tuzex\Ddd\Shared\Domain\Money\Currency\UsDollar;

final class UsdDollarTest extends TestCase
{
    public function testItContainsValidSubUnit(): void
    {
        $dollar = new UsDollar();

        $this->assertSame(100, $dollar->fraction());
        $this->assertSame(2, $dollar->precision());
    }

    public function testItReturnsValidCode(): void
    {
        $dollar = new UsDollar();

        $this->assertSame('USD', $dollar->code());
    }
}
