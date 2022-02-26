<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain;

use Tuzex\Ddd\Shared\Domain\Money\Currency;
use Tuzex\Ddd\Shared\Domain\Money\MismatchCurrencies;

final class Money
{
    public readonly Currency $currency;

    private float $amountInMainUnit;
    private int $amountInSubUnit;

    public function __construct(int|float $amount, Currency $currency)
    {
        $this->amountInSubUnit = intval($amount * $currency->fraction());
        $this->amountInMainUnit = floatval($this->amountInSubUnit / $currency->fraction());
        $this->currency = $currency;
    }

    public static function ofSub(int $amount, Currency $currency): self
    {
        return new self($amount / $currency->fraction(), $currency);
    }

    public function comparable(self $that): bool
    {
        return $this->currency->equals($that->currency);
    }

    public function equals(self $that): bool
    {
        return $this->comparable($that) && $this->amountInSubUnit() === $that->amountInSubUnit();
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
        return 0 < $this->amountInSubUnit();
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

        return self::ofSub($this->sum($that), $this->currency);
    }

    public function subtract(self $that): self
    {
        if (! $this->comparable($that)) {
            throw new MismatchCurrencies($this, $that);
        }

        return self::ofSub($this->diff($that), $this->currency);
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
        return self::ofSub(abs($this->amountInSubUnit()), $this->currency);
    }

    public function opposite(): self
    {
        return self::ofSub(-1 * $this->amountInSubUnit(), $this->currency);
    }

    public function amountInMainUnit(): float
    {
        return $this->amountInMainUnit;
    }

    public function amountInSubUnit(): int
    {
        if (! $this->amountInSubUnit) {
            $this->amountInSubUnit = intval($this->amountInMainUnit * $this->currency->fraction());
        }

        return $this->amountInSubUnit;
    }

    private function compare(self $that): int
    {
        if (! $this->comparable($that)) {
            throw new MismatchCurrencies($this, $that);
        }

        return $this->amountInSubUnit() <=> $that->amountInSubUnit();
    }

    private function sum(self $that): int
    {
        return $this->amountInSubUnit() + $that->amountInSubUnit();
    }

    private function diff(self $that): int
    {
        return $this->amountInSubUnit() - $that->amountInSubUnit();
    }

    private function product(int | float $factor): int
    {
        return intval(round($this->amountInSubUnit() * $factor, 0));
    }

    private function quotient(int | float $divisor): int
    {
        return intval(round($this->amountInSubUnit() / $divisor, 0));
    }
}
