<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain;

use Tuzex\Ddd\Shared\Domain\Money\Currency;
use Tuzex\Ddd\Shared\Domain\Money\Currency\Euro;

final class Total
{
    public function __construct(
        private Money $value
    ) {
    }

    public static function zero(?Currency $currency = null): self
    {
        return new self(new Money(0, $currency ?? new Euro()));
    }

    public function increaseBy(Money $addend): self
    {
        return new self($this->value->add($addend));
    }

    public function reduceBy(Money $subtrahend): self
    {
        return new self($this->value->subtract($subtrahend));
    }
}
