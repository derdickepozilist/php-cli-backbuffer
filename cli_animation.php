<?php

declare(strict_types=1);

require_once __DIR__ . '/cli_animation_effect.php';
require_once __DIR__ . '/cli_backbuffer.php';
require_once __DIR__ . '/cli_helper.fn.php';

class CLIAnimationEngine
{
    private int $frame = 0;

    public function __construct(
        private CLIBackbuffer &$bb,
        private readonly CLIAnimationEffect $effect
    ) {}

    public function outputNextFrame(): void
    {
        // execute frame content manipulator
        $this->effect->renderNextFrame($this->frame, $this->bb);

        // advance frame-counter
        $this->frame++;

        $nextFrame = $this->bb->writeout();
        // clear output
        clearTerminal();

        // return final frame
        print $nextFrame;

    }

    public function runForever(): void
    {
        while (true) {
            $this->outputNextFrame();

            // make frames delay a little, so the terminal does not flicker
            usleep($this->effect::FRAME_DELAY_MS *1000);
        }
    }
}
