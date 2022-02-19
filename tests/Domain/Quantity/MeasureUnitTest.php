<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Test\Domain\Quantity;

use PHPUnit\Framework\TestCase;
use Tuzex\Ddd\Shared\Domain\Quantity\MeasureUnit;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Gram;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Kilogram;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Liter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Meter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Piece;

final class MeasureUnitTest extends TestCase
{
    /**
     * @dataProvider provideDataForValidation
     */
    public function testItContainsValidOptions(MeasureUnit $measureUnit, array $expected): void
    {
        $this->assertSame($expected['fractional'], $measureUnit->fractional());
        $this->assertSame($expected['symbol'], $measureUnit->symbol);
        $this->assertSame($expected['precision'], $measureUnit->precision);
    }

    public function provideDataForValidation(): iterable
    {
        $definitions = [
            Gram::class => [
                'fractional' => false,
                'symbol' => 'g',
                'precision' => 0,
            ],
            Kilogram::class => [
                'fractional' => true,
                'symbol' => 'kg',
                'precision' => 2,
            ],
            Liter::class => [
                'fractional' => true,
                'symbol' => 'l',
                'precision' => 2,
            ],
            Meter::class => [
                'fractional' => true,
                'symbol' => 'm',
                'precision' => 2,
            ],
            Piece::class => [
                'fractional' => false,
                'symbol' => 'pc',
                'precision' => 0,
            ],
        ];

        foreach ($definitions as $class => $data) {
            yield $class => [
                'measureUnit' => new $class(),
                'expected' => [
                    'fractional' => $data['fractional'],
                    'symbol' => $data['symbol'],
                    'precision' => $data['precision'],
                ],
            ];
        }
    }
}
