<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Money;

abstract class Currency
{
    public function __construct(
        public readonly MainUnit $mainUnit,
        public readonly SubUnit $subUnit,
    ) {}

    public function equals(self $that): bool
    {
        return $this->mainUnit->equals($that->mainUnit) && $this->subUnit->equals($that->subUnit);
    }

    public function code(): string
    {
        return $this->mainUnit->code;
    }

    public function fraction(): int
    {
        return $this->subUnit->fraction;
    }

    public function precision(): int
    {
        return $this->subUnit->precision();
    }
}
