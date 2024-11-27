<?php

declare(strict_types=1);

namespace CLI\Calculator\Functions;

use CLI\Backbuffer;
use CLI\Calculator\IterableFunction;
use IndexOutOfRangeException;

final class EulerNumber implements IterableFunction
{
    const int ITERATIONS_PER_CYCLE = 1;
    const int ITERATIONS_FRAME_DELAY_MS = 500;

    private float $innerSum = 0.0;

    /**
     * Calculates Eulers Number iteratively and partially.
     *
     * @param int $iteration
     * @param \CLI\Backbuffer $bb
     * @return void
     */
    public function renderNextIteration(int &$iteration, Backbuffer &$bb): void
    {
        $target = $iteration + self::ITERATIONS_PER_CYCLE;

        for ($i = 0 + $iteration; $i < $target; $i++) {
            $this->innerSum += 1 / self::faculty(number: $i);
        }

        $formattedSum = number_format(num: $this->innerSum, decimals: 50);
        $iteration = $target;

        $bb->setPos(x_pos: 0, y_pos: 0);
        $bb->writeLine('Eulers Number narrowed down by his infinite row');
        $bb->writeLine('Iteration: ' . $iteration);
        $bb->writeLine('Value: ' . $formattedSum);
    }

    /**
     * Calculates the faculty of a given number.
     * @param int $number a number to calculate the faculty of.
     * @throws \IndexOutOfRangeException if given a value below 0, which is uncalcuable.
     * @return float the faculty of the given number.
     */
    private static function faculty(int $number): float
    {
        if ($number < 0) throw new IndexOutOfRangeException(message: 'Faculty only works for positive numbers');

        $f = 1;
        while ($number > 1 && ($f *= $number--)) {}
        return $f;
    }
}
