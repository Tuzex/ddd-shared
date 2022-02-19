<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Quantity\Unit;

use Tuzex\Ddd\Shared\Domain\Quantity\MeasureUnit;

final class Piece extends MeasureUnit
{
    public function __construct()
    {
        parent::__construct('pc', 0);
    }
}
