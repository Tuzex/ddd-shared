<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Money;

abstract class Currency
{
    public function __construct(
        public readonly MainUnit $mainUnit,
        public readonly FractionalUnit $fractionalUnit,
    ) {}

    public function equals(self $that): bool
    {
        return $this->mainUnit->equals($that->mainUnit) && $this->fractionalUnit->equals($that->fractionalUnit);
    }

    public function code(): string
    {
        return $this->mainUnit->code;
    }

    public function fraction(): int
    {
        return $this->fractionalUnit->fraction;
    }

    public function precision(): int
    {
        return $this->fractionalUnit->precision();
    }
}
