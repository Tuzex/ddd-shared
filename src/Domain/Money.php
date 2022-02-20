<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain;

use Tuzex\Ddd\Shared\Domain\Money\Currency;
use Tuzex\Ddd\Shared\Domain\Money\MismatchCurrencies;
use Tuzex\Ddd\Shared\Domain\Money\NominalValue;

final class Money
{
    private function __construct(
        public readonly NominalValue $nominalValue,
        public readonly Currency $currency,
    ) {}

    public static function of(float $value, Currency $currency): self
    {
        $main = round($value, $currency->precision());
        $sub = intval($main * $currency->fraction());

        return self::ofSub($sub, $currency);
    }

    public static function ofSub(int $value, Currency $currency): self
    {
        return new self(NominalValue::set($value, $currency), $currency);
    }

    public function comparable(self $that): bool
    {
        return $this->currency->equals($that->currency);
    }

    public function equals(self $that): bool
    {
        return $this->comparable($that) && $this->nominalValue->equals($that->nominalValue);
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

    public function positive(): bool
    {
        return 0 < $this->nominalValue->subValue;
    }

    public function negative(): bool
    {
        return ! $this->positive();
    }

    public function add(self $that): self
    {
        if (! $this->comparable($that)) {
            throw new MismatchCurrencies($this, $that);
        }

        return self::ofSub($this->sum($that->nominalValue), $this->currency);
    }

    public function subtract(self $that): self
    {
        if (! $this->comparable($that)) {
            throw new MismatchCurrencies($this, $that);
        }

        return self::ofSub($this->diff($that->nominalValue), $this->currency);
    }

    public function multiply(int | float $factor): self
    {
        return self::ofSub($this->product($factor), $this->currency);
    }

    public function divide(int | float $divisor): self
    {
        return self::ofSub($this->quotient($divisor), $this->currency);
    }

    public function absolute(): self
    {
        return self::ofSub(abs($this->nominalValue->subValue), $this->currency);
    }

    public function opposite(): self
    {
        return self::ofSub(-1 * $this->nominalValue->subValue, $this->currency);
    }

    private function compare(self $that): int
    {
        if (! $this->comparable($that)) {
            throw new MismatchCurrencies($this, $that);
        }

        return $this->nominalValue->compare($that->nominalValue);
    }

    private function sum(NominalValue $that): int
    {
        return $this->nominalValue->subValue + $that->subValue;
    }

    private function diff(NominalValue $that): int
    {
        return $this->nominalValue->subValue - $that->subValue;
    }

    private function product(int | float $factor): int
    {
        return intval(round($this->nominalValue->subValue * $factor, 0));
    }

    private function quotient(int | float $divisor): int
    {
        return intval(round($this->nominalValue->subValue / $divisor, 0));
    }
}
