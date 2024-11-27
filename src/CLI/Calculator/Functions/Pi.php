<?php

declare(strict_types=1);

namespace CLI\Calculator\Functions;

use CLI\Backbuffer;
use CLI\Calculator\IterableFunction;

final class Pi implements IterableFunction
{
    const int ITERATIONS_PER_CYCLE = 500;
    const int ITERATIONS_FRAME_DELAY_MS = 0;

    private float $quarter_pi = 0.0;

    public function renderNextIteration(int &$iteration, Backbuffer &$bb): void
    {
        $target = $iteration + self::ITERATIONS_PER_CYCLE;
        $sign = 1;
        for ($i = 0 + $iteration; $i < $target; $i++) {
            $this->quarter_pi += $sign / (2 * $i + 1);
            $sign = -$sign;
        }

        $formattedSum = number_format(num: $this->quarter_pi * 4, decimals: 50);
        $iteration = $target;
        
        $bb->setPos(x_pos: 0, y_pos: 0);
        $bb->writeLine('Pi calculated with Leibniz Formula');
        $bb->writeLine('Iteration: ' . $iteration);
        $bb->writeLine('Value: ' . $formattedSum);
    }
}
