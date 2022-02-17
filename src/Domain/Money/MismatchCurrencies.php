<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Money;

use DomainException;
use Tuzex\Ddd\Shared\Domain\Money;

final class MismatchCurrencies extends DomainException
{
    public function __construct(Money $origin, Money $another)
    {
        parent::__construct(
            vsprintf('Mathematical operations are allowed for only the same currency (%s => %s).', [
                $origin->currency->code(),
                $another->currency->code(),
            ])
        );
    }
}
