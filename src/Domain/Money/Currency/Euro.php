<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Money\Currency;

use Tuzex\Ddd\Shared\Domain\Money\Currency;
use Tuzex\Ddd\Shared\Domain\Money\FractionalUnit;
use Tuzex\Ddd\Shared\Domain\Money\MainUnit;

final class Euro extends Currency
{
    public function __construct()
    {
        parent::__construct(
            new MainUnit('EUR', '€'),
            new FractionalUnit('cent', 'c', 100),
        );
    }
}
