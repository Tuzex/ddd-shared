<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Quantity;

abstract class MeasureUnit
{
    public function __construct(
        public readonly string $symbol,
        public readonly int $precision,
    ) {
    }

    public function equals(self $that): bool
    {
        return $this::class === $that::class && $that->symbol === $this->symbol;
    }

    public function fractional(): bool
    {
        return 0 < $this->precision;
    }
}
