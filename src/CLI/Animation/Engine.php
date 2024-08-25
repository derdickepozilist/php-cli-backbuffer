<?php

declare(strict_types=1);

namespace CLI\Animation;

use CLI\Animation\Effect;
use CLI\Backbuffer;

use function CLI\clearTerminal;

class Engine
{
    private int $frame = 0;

    public function __construct(
        private Backbuffer &$bb,
        private readonly Effect $effect
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
