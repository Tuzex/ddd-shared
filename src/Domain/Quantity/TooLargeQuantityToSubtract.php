<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Quantity;

use DomainException;
use Tuzex\Ddd\Shared\Domain\Quantity;

final class TooLargeQuantityToSubtract extends DomainException
{
    public function __construct(Quantity $small, Quantity $large)
    {
        parent::__construct(
            sprintf('Subtracting a larger amount from a smaller one is not allowed (%s - %s).', $small->amount, $large->amount)
        );
    }
}
