<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Quantity;

use DomainException;
use Tuzex\Ddd\Shared\Domain\Quantity;

final class MismatchMeasureUnits extends DomainException
{
    public function __construct(Quantity $origin, Quantity $another)
    {
        parent::__construct(
            vsprintf('Mathematical operations are allowed for only the same measure unit (%s != %s).', [
                $origin->measureUnit->symbol,
                $another->measureUnit->symbol,
            ])
        );
    }
}
