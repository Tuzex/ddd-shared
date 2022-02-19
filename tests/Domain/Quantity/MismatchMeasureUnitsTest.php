<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Test\Domain\Money;

use PHPUnit\Framework\TestCase;
use Tuzex\Ddd\Shared\Domain\Quantity;
use Tuzex\Ddd\Shared\Domain\Quantity\MismatchMeasureUnits;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Meter;

final class MismatchMeasureUnitsTest extends TestCase
{
    public function testItReturnsSpecificMessage(): void
    {
        $quantity = new Quantity(1, new Meter());
        $exception = new MismatchMeasureUnits($quantity, $quantity);

        $this->assertSame('Mathematical operations are allowed for only the same measure unit (m != m).', $exception->getMessage());
    }
}
