<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Quantity\Unit;

use Tuzex\Ddd\Shared\Domain\Quantity\MeasureUnit;

final class Liter extends MeasureUnit
{
    public function __construct()
    {
        parent::__construct('l', 2);
    }
}
