<?php

declare(strict_types=1);

use CLI\Backbuffer;
use CLI\Calculator\Engine;
use CLI\Calculator\Functions\EulerNumber;

require_once __DIR__ . '/vendor/autoload.php';

$bb = new Backbuffer(x_cols: 80, y_rows: 5, prefill_char: ' ');
$function = new EulerNumber;
$engine = new Engine(bb: $bb, function: $function);
$engine->runIterations(25);
