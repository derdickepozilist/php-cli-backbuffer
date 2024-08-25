<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Animations\Flame;
use CLI\Animation\Engine;
use CLI\Backbuffer;

$bb = new Backbuffer(x_cols: 80, y_rows: 25, prefill_char: ' ');
$effect = new Flame($bb);
$engine = new Engine($bb, $effect);
$engine->runForever();
