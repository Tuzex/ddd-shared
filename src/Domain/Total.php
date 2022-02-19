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

    public static function zero(Currency $currency = new Euro): self
    {
        return new self(Money::of(0, $currency));
    }

    public function increase(Money $addend): Total
    {
        return new self($this->value->add($addend));
    }

    public function decrease(Money $subtrahend): Total
    {
        return new self($this->value->subtract($subtrahend));
    }
}
