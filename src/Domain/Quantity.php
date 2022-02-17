<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain;

use Tuzex\Ddd\Shared\Domain\Quantity\MeasureUnit;

abstract class Quantity
{
    public function __construct(
        private int|float $amount,
        private MeasureUnit $measureUnit
    ) {
    }

    public function amount(): int|float
    {
        $precision = $this->measureUnit->precision();
        if (0 === $precision) {
            return intval($this->amount);
        }

        return round(floatval($this->amount), $precision);
    }

    public function measureUnit(): MeasureUnit
    {
        return $this->measureUnit;
    }
}
