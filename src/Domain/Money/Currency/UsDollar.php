<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Domain\Money\Currency;

use Tuzex\Ddd\Shared\Domain\Money\Currency;
use Tuzex\Ddd\Shared\Domain\Money\MainUnit;
use Tuzex\Ddd\Shared\Domain\Money\SubUnit;

final class UsDollar extends Currency
{
    public function __construct()
    {
        parent::__construct(
            new MainUnit('USD', '$'),
            new SubUnit('cent', 'c', 100),
        );
    }
}
