<?php

declare(strict_types=1);

use Kellegous\Algs4\Vector;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Vector::class)]
class VectorTest extends TestCase
{
    /**
     * @return iterable<string, array{Vector, array<float>}>
     */
    public static function creationTests(): iterable
    {
        yield 'withDimension(0)' => [
            Vector::withDimension(0),
            [],
        ];

        yield 'withDimension(0, 1.0)' => [
            Vector::withDimension(0, 1.0),
            [],
        ];

        yield 'withDimension(1)' => [
            Vector::withDimension(1),
            [0.0],
        ];

        yield 'withDimension(1, 1.0)' => [
            Vector::withDimension(1, 1.0),
            [1.0],
        ];

        yield 'withDimension(5)' => [
            Vector::withDimension(5),
            [0.0, 0.0, 0.0, 0.0, 0.0],
        ];

        yield 'withDimension(5, 2.2)' => [
            Vector::withDimension(5, 2.2),
            [2.2, 2.2, 2.2, 2.2, 2.2],
        ];

        yield 'withData()' => [
            Vector::fromData(),
            []
        ];

        yield 'withData(1.0)' => [
            Vector::fromData(1.0),
            [1.0]
        ];

        yield 'withData(1.0, 2.0)' => [
            Vector::fromData(1.0, 2.0),
            [1.0, 2.0]
        ];
    }

    /**
     * @return iterable<string, array{Vector, Vector, Vector|Exception}>
     */
    public static function plusTests(): iterable
    {
        yield '[] + []' => [
            Vector::fromData(),
            Vector::fromData(),
            Vector::fromData(),
        ];

        yield '[1.0] + [2.0]' => [
            Vector::fromData(1.0),
            Vector::fromData(2.0),
            Vector::fromData(3.0),
        ];

        yield '[1.0 2.0] + [3.0 4.0]' => [
            Vector::fromData(1.0, 2.0),
            Vector::fromData(3.0, 4.0),
            Vector::fromData(4.0, 6.0),
        ];

        yield '[1.0] + [3.0 4.0]' => [
            Vector::fromData(1.0),
            Vector::fromData(3.0, 4.0),
            new Exception('dimensions do not agree')
        ];
    }

    /**
     * @return iterable<string, array{Vector, Vector, Vector|Exception}>
     */
    public static function minusTests(): iterable
    {
        yield '[] - []' => [
            Vector::fromData(),
            Vector::fromData(),
            Vector::fromData(),
        ];

        yield '[1.0] - [2.0]' => [
            Vector::fromData(1.0),
            Vector::fromData(2.0),
            Vector::fromData(-1.0),
        ];

        yield '[3.0 4.0] - [1.0 2.0]' => [
            Vector::fromData(3.0, 4.0),
            Vector::fromData(1.0, 2.0),
            Vector::fromData(2.0, 2.0),
        ];

        yield '[1.0] - [3.0 4.0]' => [
            Vector::fromData(1.0),
            Vector::fromData(3.0, 4.0),
            new Exception('dimensions do not agree')
        ];
    }

    /**
     * @param Vector $vector
     * @param float[] $expected
     * @return void
     */
    #[Test, DataProvider('creationTests')]
    public function creation(
        Vector $vector,
        array $expected
    ): void {
        self::assertEquals($expected, self::toArray($vector));
    }

    /**
     * @param Vector $vector
     * @return float[]
     */
    private static function toArray(Vector $vector): array
    {
        $data = [];
        for ($i = 0, $n = $vector->dimension(); $i < $n; $i++) {
            $data[] = $vector->cartesian($i);
        }
        return $data;
    }

    /**
     * @param Vector $a
     * @param Vector $b
     * @param Vector|Exception $expected
     * @return void
     */
    #[Test, DataProvider('plusTests')]
    public function plus(
        Vector $a,
        Vector $b,
        Vector|Exception $expected
    ): void {
        if ($expected instanceof Exception) {
            self::expectExceptionObject($expected);
            $a->plus($b);
        } else {
            self::assertEquals(
                self::toArray($expected),
                self::toArray($a->plus($b))
            );
        }
    }

    /**
     * @param Vector $a
     * @param Vector $b
     * @param Vector|Exception $expected
     * @return void
     */
    #[Test, DataProvider('minusTests')]
    public function minus(
        Vector $a,
        Vector $b,
        Vector|Exception $expected
    ): void {
        if ($expected instanceof Exception) {
            self::expectExceptionObject($expected);
            $a->minus($b);
        } else {
            self::assertEquals(
                self::toArray($expected),
                self::toArray($a->minus($b))
            );
        }
    }
}