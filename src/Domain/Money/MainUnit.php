<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Money;

use Webmozart\Assert\Assert;

final class MainUnit
{
    public function __construct(
        public readonly string $code,
        public readonly string $symbol,
    ) {
        Assert::regex($this->code, '/^[A-Z]{3}$/');
        Assert::notEmpty($this->symbol);
    }

    public function equals(self $that): bool
    {
        return $that->code === $this->code
            && $that->symbol === $this->symbol;
    }
}
