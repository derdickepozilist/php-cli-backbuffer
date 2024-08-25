<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Animations\RaytracedBall;
use CLI\Animation\Ani3D\Direction;
use CLI\Animation\Ani3D\Vect;
use CLI\Animation\Engine;
use CLI\Animation\Object3D\Ball;
use CLI\Backbuffer;

$bb = new Backbuffer(x_cols: 80, y_rows: 25, prefill_char: ' ');

$start_pos = new Vect;
$start_pos->x = 0.0;
$start_pos->y = 0.0;
$start_pos->z = 1.0;

$start_dir = new Direction;
$start_dir->ang_v = -0.2;
$start_dir->ang_h = 0.0;

$effect = new RaytracedBall($start_pos, $start_dir, 2, 2, $bb->x_cols, $bb->y_rows);

$b_vect = new Vect;
$b_vect->x = 5;
$b_vect->y = 0;
$b_vect->z = 2;

$b = new Ball;
$b->center = &$b_vect;
$b->radius = 2;

$effect->add_ball($b);

$c_vect = new Vect;
$c_vect->x = 10;
$c_vect->y = 0;
$c_vect->z = 2;

$c = new Ball;
$c->center = &$c_vect;
$c->radius = 2;

$effect->add_ball($c);

$d_vect = new Vect;
$d_vect->x = 7.5;
$d_vect->y = 0;
$d_vect->z = 8;

$d = new Ball;
$d->center = &$d_vect;
$d->radius = 4;

$effect->add_ball($d);

$engine = new Engine($bb, $effect);
$engine->runForever();
