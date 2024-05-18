<?php

declare(strict_types=1);

require_once __DIR__ . '/cli_animation.php';
require_once __DIR__ . '/cli_animation_effect_flame.php';
require_once __DIR__ . '/cli_backbuffer.php';

$bb = new CLIBackbuffer(x_cols: 80, y_rows: 25, prefill_char: ' ');
$effect = new CLIAnimationEffectFlame($bb);
$engine = new CLIAnimationEngine($bb, $effect);
$engine->runForever();
