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
    const int FRAME_DELAY_MS = 1;

    const float MOVE_ANGLE = 0.01;
    const float MOVE_POSITION = 0.03;

    const float RAYSTEP = 0.02;
    const float RAYSTEPS = 5000;

    /**
     * @var Ball[]
     */
    public array $balls = [];
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

    public function add_ball(Ball $b): void
    {
        $this->balls[] = $b;
    }

    public function make_pic(Backbuffer $bb): void
    {
		// rays through equidistant points on width*height rectangle with distance 1 from viewer

        $v1 = $this->dir->to_unit();

		// v2 points from middle of the rectangle to upper edge
        $v2 = new Vect(
            -tan($this->dir->altitude) * $v1->x,
            -tan($this->dir->altitude) * $v1->y,
            cos($this->dir->altitude)
        );

        $v2->scale($this->height / 2.0);

		// v3 points from middle of rectangle to left edge
        $v3 = new Vect(
            -$v1->y,
            $v1->x,
            0
        );

        $v3->normalize();
        $v3->scale($this->width / 2.0);

        $ballcount = count($this->balls);
        for ($row = 0; $row < $this->yres; ++$row) {
            for ($col = 0; $col < $this->xres; ++$col) {
                $up_offset = -(floatval($row) / (floatval($this->yres) -1.0) -0.5);
                $left_offset = floatval($col) / (floatval($this->xres) -1.0) -0.5;

                $move = clone $v1;
                $move->add($v2->scaled($up_offset));
                $move->add($v3->scaled($left_offset));
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
                    foreach ($this->balls as &$b) {
                        $d = $ray->dist($b->center) - $b->radius;
                        $dists_to_balls[$ball_index] = $d;
                        if ($d < 0.0) {
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
                        $i += $possible_steps -1; // -1 because of default increment
                        $ray->add($move->scaled($possible_steps));
                    } else {
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
                $this->dir->altitude += self::MOVE_ANGLE;
                break;
            case 'down':
                $this->dir->altitude -= self::MOVE_ANGLE;
                break;
            case 'left':
                $this->dir->azimuth -= self::MOVE_ANGLE;
                break;
            case 'right':
            default:
                $this->dir->azimuth += self::MOVE_ANGLE;
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
            case 'back':
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

    public function renderNextFrame(int $frame, Backbuffer &$bb): void
    {
        $this->make_pic($bb);

        foreach ($this->balls as &$b) {
            $b->bounce_frame();
        }

        //$this->move_view('right');
        /*
        if ($frame % 5 === 0) {
            $this->move_view('up');
            $this->move_position('back', '');
        }*/
        
        //$this->move_view('left');*/
        //$this->move_position('back', '');

        $str = '';
        $last_line = $bb->y_rows - 2;
        for ($i = 0; $i < $bb->x_cols - 1; $i++) $str .= ' ';
        $bb->setPos(0, $last_line);
        $bb->writeString($str);
        $bb->setPos(0, $last_line);
        $str = 'pos: ' . $this->pos->to_string() . ' dir:' . $this->dir->to_string();
        $bb->writeString($str);
    }
}
