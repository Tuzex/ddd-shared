<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Test\Domain\Money;

use PHPUnit\Framework\TestCase;
use Tuzex\Ddd\Shared\Domain\Quantity;
use Tuzex\Ddd\Shared\Domain\Quantity\TooLargeQuantityToSubtract;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Meter;

final class TooLargeQuantityToSubtractTest extends TestCase
{
    public function testItReturnsSpecificMessage(): void
    {
        $quantity = new Quantity(1, new Meter());
        $exception = new TooLargeQuantityToSubtract($quantity, $quantity);

        $this->assertSame('Subtracting a larger amount from a smaller one is not allowed (1 - 1).', $exception->getMessage());
    }
}
