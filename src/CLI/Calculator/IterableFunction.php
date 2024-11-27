<?php

declare(strict_types=1);

namespace CLI\Calculator;

use CLI\Backbuffer;

interface IterableFunction
{
    const int ITERATIONS_FRAME_DELAY_MS = 100;

    public function renderNextIteration(int &$iteration, Backbuffer &$bb): void;
}
