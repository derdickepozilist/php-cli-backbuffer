<?php

declare(strict_types=1);

namespace CLI\Animation\Ani3D;

class Vect
{
    public float $x;
    public float $y;
    public float $z;

    public function normalize(): void
    {
        $len = $this->length();
        $this->x /= $len;
        $this->y /= $len;
        $this->z /= $len;
    }

    public function length(): float
    {
        return sqrt(
            $this->x * $this->x +
                $this->y * $this->y +
                $this->z * $this->z
        );
    }

    public function add(Vect $v): void
    {
        $this->x += $v->x;
        $this->y += $v->y;
        $this->z += $v->z;
    }

    public function scale(float $s): void
    {
        $this->x *= $s;
        $this->y *= $s;
        $this->z *= $s;
    }

    public function scaled(float $s): Vect
    {
        $v = new Vect;
        $v->x = $this->x * $s;
        $v->y = $this->y * $s;
        $v->z = $this->z * $s;

        return $v;
    }

    public function dist(Vect $other): float
    {
        return sqrt(
            ($this->x - $other->x) * ($this->x - $other->x) +
                ($this->y - $other->y) * ($this->y - $other->y) +
                ($this->z - $other->z) * ($this->z - $other->z)
        );
    }

    public function dot(Vect &$other): float
    {
        return $this->x * $other->x +
            $this->y * $other->y +
            $this->z * $other->z;
    }

    public function to_direction(): Direction
    {
        $ang_v = atan($this->z / ($this->x * $this->x + $this->y * $this->y));
        $ang_h = atan2($this->y, $this->x);

        $dir = new Direction();
        $dir->ang_v = $ang_v;
        $dir->ang_h = $ang_h;
        return $dir;
    }
}
