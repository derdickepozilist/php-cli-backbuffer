<?php

declare(strict_types=1);

namespace CLI\Calculator;

use CLI\Backbuffer;

use function CLI\clearTerminal;

class Engine {
    private int $iteration = 0;

    public function __construct(
        private Backbuffer &$bb,
        private readonly IterableFunction $function
    ) {}

    public function outputNextIteration(): void
    {
        // execute frame content manipulator
        $this->function->renderNextIteration($this->iteration, $this->bb);

        $nextResult = $this->bb->writeout();

        // clear output
        clearTerminal();

        // return final frame
        print $nextResult;

    }

    public function runIterations(int $max): void
    {
        while ($this->iteration < $max) {
            $this->outputNextIteration();

            // make frames delay a little, so the terminal does not flicker
            usleep($this->function::ITERATIONS_FRAME_DELAY_MS* 1000);
        }
    }
}
