<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Animations\RaytracedBall;
use CLI\Animation\Ani3D\Direction;
use CLI\Animation\Ani3D\Vect;
use CLI\Animation\Engine;
use CLI\Animation\Object3D\Ball;
use CLI\Backbuffer;

$bb = new Backbuffer(x_cols: 160, y_rows: 80, prefill_char: ' ');

// raytraced ball effect initialization
$start_pos = new Vect(7.5, 7.5, pi());
$start_dir = new Direction(0.0, -pi()/2);
$effect = new RaytracedBall($start_pos, $start_dir, 2.0, 2.0, $bb->x_cols, $bb->y_rows);

$effect->add_ball(new Ball(new Vect(5.0, 0.0, 2.0), 1.0));
$effect->add_ball(new Ball(new Vect(10.0, 0.0, 2.0), 1.0));
$effect->add_ball(new Ball(new Vect(7.5, -10.5, 2.0), 2.5));

$engine = new Engine($bb, $effect);
$engine->runForever();
