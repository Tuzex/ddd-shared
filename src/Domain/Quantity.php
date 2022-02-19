<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain;

use Tuzex\Ddd\Shared\Domain\Quantity\MeasureUnit;
use Tuzex\Ddd\Shared\Domain\Quantity\MismatchMeasureUnits;
use Tuzex\Ddd\Shared\Domain\Quantity\TooLargeQuantityToSubtract;
use Webmozart\Assert\Assert;

final class Quantity
{
    public readonly int|float $amount;

    public function __construct(
        int|float $amount,
        public readonly MeasureUnit $measureUnit
    ) {
        Assert::greaterThan($amount, 0, 'Quantity must by greater than zero, "%s" given');

        $this->amount = $this->measureUnit->fractional() ? round(floatval($amount), $this->measureUnit->precision) : intval($amount);
    }

    public function equals(self $that): bool
    {
        return $this->amount === $that->amount && $this->measureUnit->equals($that->measureUnit);
    }

    public function comparable(self $that): bool
    {
        return $this->measureUnit->equals($that->measureUnit);
    }

    public function greaterThan(self $that): bool
    {
        return 0 < $this->compare($that);
    }

    public function greaterThanOrEqualTo(self $that): bool
    {
        return 0 <= $this->compare($that);
    }

    public function lessThan(self $that): bool
    {
        return 0 > $this->compare($that);
    }

    public function lessThanOrEqualTo(self $that): bool
    {
        return 0 >= $this->compare($that);
    }

    public function increase(self $that): self
    {
        if (! $this->comparable($that)) {
            throw new MismatchMeasureUnits($this, $that);
        }

        return new self($this->amount + $that->amount, $this->measureUnit);
    }

    public function decrease(self $that): self
    {
        if ($this->lessThan($that)) {
            throw new TooLargeQuantityToSubtract($this, $that);
        }

        return new self($this->amount - $that->amount, $this->measureUnit);
    }

    private function compare(self $that): int
    {
        if (! $this->comparable($that)) {
            throw new MismatchMeasureUnits($this, $that);
        }

        return $this->amount <=> $that->amount;
    }
}
