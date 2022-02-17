<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Quantity\Unit;

use Tuzex\Ddd\Shared\Domain\Quantity\MeasureUnit;

final class Meter extends MeasureUnit
{
    public static function set(): self
    {
        return new self('m', 2);
    }
}
