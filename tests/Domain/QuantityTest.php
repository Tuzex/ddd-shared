<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Shared\Test\Domain;

use PHPUnit\Framework\TestCase;
use Tuzex\Ddd\Shared\Domain\Quantity;
use Tuzex\Ddd\Shared\Domain\Quantity\MismatchMeasureUnits;
use Tuzex\Ddd\Shared\Domain\Quantity\TooLargeQuantityToSubtract;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Liter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Meter;
use Tuzex\Ddd\Shared\Domain\Quantity\Unit\Piece;

final class QuantityTest extends TestCase
{
    /**
     * @dataProvider provideDataForRounding
     */
    public function testItRoundsBasedOnMeasureUnit(Quantity $quantity, int|float $expectedAmount): void
    {
        $this->assertSame($expectedAmount, $quantity->amount);
    }

    public function provideDataForRounding(): iterable
    {
        $circumstances = [
            'piece' => [1, new Piece(), 1],
            'meter' => [1.20, new Meter(), 1.20],
        ];

        foreach ($circumstances as $type => $data) {
            yield $type => [
                'quantity' => new Quantity($data[0], $data[1]),
                'expectedAmount' => $data[2],
            ];
        }
    }

    /**
     * @dataProvider provideDataForEquality
     */
    public function testItEquals(Quantity $origin, Quantity $another, bool $result): void
    {
        $this->assertSame($result, $origin->equals($another));
    }

    public function provideDataForEquality(): iterable
    {
        $circumstances = [
            'identical-quantities' => [1.00, new Meter(), 1.00, new Meter(), true],
            'different-amounts' => [1.00, new Meter(), 2.00, new Meter(), false],
            'different-measure-units' => [1.00, new Meter(), 1.00, new Liter(), false],
            'different-quantities' => [1.00, new Meter(), 2.00, new Liter(), false],
        ];

        return $this->generateDataForEquality($circumstances);
    }

    /**
     * @dataProvider provideDataForComparison
     */
    public function testItIsComparable(Quantity $origin, Quantity $another, bool $result): void
    {
        $this->assertSame($result, $origin->comparable($another));
    }

    public function provideDataForComparison(): iterable
    {
        $value = 1.00;
        $circumstances = [
            'identical' => [$value, new Meter(), $value, new Meter(), true],
            'mismatched' => [$value, new Meter(), $value, new Liter(), false],
        ];

        return $this->generateDataForEquality($circumstances);
    }

    /**
     * @dataProvider provideDataForComparisonLessThan
     */
    public function testItIsLessThan(Quantity $origin, Quantity $another, bool $result): void
    {
        $this->assertSame($result, $origin->lessThan($another));
    }

    public function provideDataForComparisonLessThan(): iterable
    {
        $results = [
            'less-than' => true,
            'equal' => false,
            'greater-than' => false,
        ];

        return $this->generateDataForComparison($results);
    }

    /**
     * @dataProvider provideDataForComparisonLessThanOrEqualTo
     */
    public function testItIsLessThanOrEqualTo(Quantity $origin, Quantity $another, bool $result): void
    {
        $this->assertSame($result, $origin->lessThanOrEqualTo($another));
    }

    public function provideDataForComparisonLessThanOrEqualTo(): iterable
    {
        $results = [
            'less-than' => true,
            'equal' => true,
            'greater-than' => false,
        ];

        return $this->generateDataForComparison($results);
    }

    /**
     * @dataProvider provideDataForComparisonGreaterThan
     */
    public function testItIsGreaterThan(Quantity $origin, Quantity $another, bool $result): void
    {
        $this->assertSame($result, $origin->greaterThan($another));
    }

    public function provideDataForComparisonGreaterThan(): iterable
    {
        $results = [
            'less-than' => false,
            'equal' => false,
            'greater-than' => true,
        ];

        return $this->generateDataForComparison($results);
    }

    /**
     * @dataProvider provideDataForComparisonGreaterThanOrEqualTo
     */
    public function testItIsGreaterThanOrEqualTo(Quantity $origin, Quantity $another, bool $result): void
    {
        $this->assertSame($result, $origin->greaterThanOrEqualTo($another));
    }

    public function provideDataForComparisonGreaterThanOrEqualTo(): iterable
    {
        $results = [
            'less-than' => false,
            'equal' => true,
            'greater-than' => true,
        ];

        return $this->generateDataForComparison($results);
    }

    /**
     * @dataProvider provideDataWithDifferentMeasureUnits
     */
    public function testItThrowsExceptionWhenCompareDifferentCurrencies(Quantity $minuend, Quantity $subtrahend): void
    {
        $this->expectException(MismatchMeasureUnits::class);
        $minuend->lessThan($subtrahend);
    }

    /**
     * @dataProvider provideDataWithDifferentMeasureUnits
     */
    public function testItThrowsExceptionWhenAddingDifferentCurrencies(Quantity $term, Quantity $addend): void
    {
        $this->expectException(MismatchMeasureUnits::class);
        $term->increase($addend);
    }

    /**
     * @dataProvider provideDataWithDifferentMeasureUnits
     */
    public function testItThrowsExceptionWhenSubtractDifferentCurrencies(Quantity $minuend, Quantity $subtrahend): void
    {
        $this->expectException(MismatchMeasureUnits::class);
        $minuend->decrease($subtrahend);
    }

    public function provideDataWithDifferentMeasureUnits(): array
    {
        $value = 1.25;

        return [
            'different-measure-units' => [
                'first' => new Quantity($value, new Meter()),
                'second' => new Quantity($value, new Liter()),
            ],
        ];
    }

    public function testItIncreasesQuantityByAnother(): void
    {
        $term = new Quantity(1, new Piece());
        $addend = new Quantity(1, new Piece());

        $sum = $term->increase($addend);

        $this->assertSame(2, $sum->amount);
    }

    public function testItDecreasesQuantityByAnother(): void
    {
        $minuend = new Quantity(2, new Piece());
        $subtrahend = new Quantity(1, new Piece());

        $diff = $minuend->decrease($subtrahend);

        $this->assertSame(1, $diff->amount);
    }

    public function testItThrowsExceptionWhenSubtrahendIsLargerThanMinuend(): void
    {
        $minuend = new Quantity(1, new Piece());
        $subtrahend = new Quantity(2, new Piece());

        $this->expectException(TooLargeQuantityToSubtract::class);
        $minuend->decrease($subtrahend);
    }

    private function generateDataForComparison(array $results): iterable
    {
        $measureUnit = new Meter();
        $circumstances = [
            'less-than' => [1.00, $measureUnit, 2.00, $measureUnit, $results['less-than']],
            'equal' => [1.00, $measureUnit, 1.00, $measureUnit, $results['equal']],
            'greater-than' => [2.00, $measureUnit, 1.00, $measureUnit, $results['greater-than']],
        ];

        return $this->generateDataForEquality($circumstances);
    }

    private function generateDataForEquality(array $circumstances): iterable
    {
        foreach ($circumstances as $type => $data) {
            yield $type => [
                'origin' => new Quantity($data[0], $data[1]),
                'another' => new Quantity($data[2], $data[3]),
                'result' => $data[4],
            ];
        }
    }
}
