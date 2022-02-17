<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Money;

final class NominalValue
{
    public readonly float $mainValue;
    public readonly int $subValue;

    public function __construct(int $subValue, int $fraction)
    {
        $this->mainValue = floatval($subValue / $fraction);
        $this->subValue = $subValue;
    }

    public static function set(int $value, Currency $currency): self
    {
        return new self($value, $currency->fraction());
    }

    public function equals(self $that): bool
    {
        return $that->mainValue === $this->mainValue && $that->subValue === $this->subValue;
    }

    public function compare(self $that): int
    {
        return $this->subValue <=> $that->subValue;
    }
}
