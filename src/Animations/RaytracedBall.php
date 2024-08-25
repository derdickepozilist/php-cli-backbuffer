<?php

declare(strict_types=1);

namespace Animations;

use CLI\Animation\Ani3D\Direction;
use CLI\Animation\Ani3D\Vect;
use CLI\Animation\Effect;
use CLI\Animation\Object3D\Ball;
use CLI\Backbuffer;

use function CLI\Animation\Ani3D\ray_char;
use function CLI\Animation\Ani3D\ray_done;

class RaytracedBall implements Effect
{
    const int FRAME_DELAY_MS = 30;

    const float MOVE_ANGLE = 0.01;
    const float MOVE_POSITION = 0.03;

    const float RAYSTEP = 0.02;
    const float RAYSTEPS = 5000;

    /**
     * @var Ball[]
     */
    public array $balls;
    public Vect $pos;
    public Direction $dir;
    public float $width;
    public float $height;
    public int $xres;
    public int $yres;

    public function __construct(
        Vect &$start_pos,
        Direction &$start_dir,
        float $width,
        float $height,
        int $xres,
        int $yres
    )
    {
        $this->width = $width;
        $this->height = $height;
        $this->pos = $start_pos;
        $this->dir = $start_dir;
        $this->xres = $xres;
        $this->yres = $yres;
    }

    public function add_ball(Ball &$b): void
    {
        $this->balls[] = $b;
    }

    public function make_pic(Backbuffer $bb): void
    {
		// rays through equidistant points on width*height rectangle with distance 1 from viewer

        $v1 = $this->dir->to_unit();

		// v2 points from middle of the rectangle to upper edge
        $v2 = new Vect;
        $v2->x = -tan($this->dir->ang_v) * $v1->x;
        $v2->y = -tan($this->dir->ang_v) * $v1->y;
        $v2->z = cos($this->dir->ang_v);

        $v2->scale($this->height / 2);

		// v3 points from middle of rectangle to left edge
        $v3 = new Vect;
        $v3->x = -$v1->y;
        $v3->y = $v1->x;
        $v3->z = 0;

        $v3->normalize();
        $v3->scale($this->width/2);

        $ballcount = count($this->balls);
        for ($row = 0; $row < $this->yres; ++$row) {
            for ($col = 0; $col < $this->xres; ++$col) {
                $up_offset = - (floatval($row) / $this->yres -1) -0.5;
                $left_offset = - (floatval($col) / $this->xres -1) -0.5;

                $up_scale = $v2->scaled($up_offset);
                $left_scale = $v3->scaled($left_offset);
                $move = clone $v1;
                $move->add($up_scale);
                $move->add($left_scale);
                $move->normalize();
                $move->scale(self::RAYSTEP);

                $ray = clone $this->pos;
				// trace ray
                $dists_to_balls = [];
                for ($i = 0; $i < $ballcount; ++$i) {
                    $dists_to_balls[] = 0;
                }
                $times_reflected = 0;
                for ($i = 0; $i < self::RAYSTEPS; ++$i) {
                    if (ray_done($ray)) break;

                    $ball_index = 0;
                    foreach ($this->balls as $b) {
                        $d = $ray->dist($b->center) - $b->radius;
                        $dists_to_balls[$ball_index] = $d;
                        if ($d < 0) {
                            $move = $b->reflect($ray, $move);
                            $times_reflected++;
                        }
                        $ball_index++;
                    }

					// optimization: test if all distances are large enough to make
					// multiple steps at once
                    $min_dist = $ray->z;
                    foreach ($dists_to_balls as $f) {
                        if ($f < $min_dist) {
                            $min_dist = $f;
                        }
                    }

                    if ($min_dist > self::RAYSTEP) {
                        $possible_steps = $min_dist / self::RAYSTEP;
                        $i += $possible_steps -1;
                        $ray->add($move->scaled($possible_steps));
                    }
                    else {
                        $ray->add($move);
                    }
                }

                $bb->setPos($col, $row);
                $bb->writeChar(ray_char($ray, $times_reflected));
            }
        }
    }

    public function move_view(string $direction): void
    {
        switch ($direction) {
            case 'up':
                $this->dir->ang_v += self::MOVE_ANGLE;
                break;
            case 'down':
                $this->dir->ang_v -= self::MOVE_ANGLE;
                break;
            case 'left':
                $this->dir->ang_h -= self::MOVE_ANGLE;
                break;
            case 'right':
            default:
                $this->dir->ang_h += self::MOVE_ANGLE;
                break;
        }
    }

    public function move_position(string $direction_fb, string $direction_rl): void
    {
        $dir_vect = $this->dir->to_unit();
        $xmov = $dir_vect->x;
        $ymov = $dir_vect->y;
        $scale = 1 / sqrt($xmov*$xmov + $ymov*$ymov);
        $xmov *= $scale;
        $ymov *= $scale;
        $xmov *= self::MOVE_POSITION;
        $ymov *= self::MOVE_POSITION;

        switch ($direction_fb) {
            case 'forward':
                $this->pos->x += $xmov;
                $this->pos->y += $ymov;
                break;
            case 'backward':
                $this->pos->x -= $xmov;
                $this->pos->y -= $ymov;
                break;
            default:
        }
        switch($direction_rl) {
            case 'left':
                $this->pos->x += $ymov;
                $this->pos->y -= $xmov;
                break;
            case 'right':
                $this->pos->x -= $ymov;
                $this->pos->y += $xmov;
                break;
            default:
        }
    }

    public function check_reflections(Vect &$ray, Vect &$move): bool
    {
		// checks if ray has to be reflected on one of the objects, changes dir accordingly
        foreach ($this->balls as $ball) {
            if ($ray->dist($ball->center) < $ball->radius) {
                $move = $ball->reflect($ray, $move);
                return true;
            }
        }

        return false;
    }

    public function renderNextFrame(int $frame, Backbuffer &$bb): void
    {
        $this->make_pic($bb);
        $this->move_view('right');
    }
}
