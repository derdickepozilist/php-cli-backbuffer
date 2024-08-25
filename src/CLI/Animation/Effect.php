<?php

declare(strict_types=1);

namespace CLI\Animation;

use CLI\Backbuffer;

interface Effect {
    const int FRAME_DELAY_MS = 30;

    public function renderNextFrame(int $frame, Backbuffer &$bb): void;
}
