<?php

declare(strict_types=1);

require_once __DIR__ . '/cli_backbuffer.php';

abstract class CLIAnimationEffect {
    const FRAME_DELAY_MS = 30;

    abstract public function renderNextFrame(int $frame, CLIBackbuffer &$bb): void;
}
