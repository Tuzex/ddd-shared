<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Quantity;

abstract class MeasureUnit
{
    protected function __construct(
        private string $symbol,
        private int $precision,
    ) {
    }

    abstract public static function set(): self;

    public function equals(self $that): bool
    {
        return $this::class === $that::class && $that->symbol === $this->symbol;
    }

    public function symbol(): string
    {
        return $this->symbol;
    }

    public function precision(): int
    {
        return $this->precision;
    }
}
