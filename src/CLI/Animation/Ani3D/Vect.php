<?php

declare(strict_types=1);

namespace CLI\Animation\Ani3D;

class Vect
{
    public function __construct(
        public float $x = 0.0,
        public float $y = 0.0,
        public float $z = 0.0
        )
    {
    }

    public function length(): float
    {
        $x = &$this->x;
        $y = &$this->y;
        $z = &$this->z;

        return sqrt($x * $x + $y * $y + $z * $z);
    }

    public function dist(Vect &$other): float
    {
        $x = &$this->x;
        $y = &$this->y;
        $z = &$this->z;

        return sqrt(
            ($x - $other->x) * ($x - $other->x) +
                ($y - $other->y) * ($y - $other->y) +
                ($z - $other->z) * ($z - $other->z)
        );
    }

    public function dot(Vect &$other): float
    {
        $x = &$this->x;
        $y = &$this->y;
        $z = &$this->z;
        return $x * $other->x +
            $y * $other->y +
            $z * $other->z;
    }

    public function normalize(): void
    {
        $len = $this->length();
        $this->x /= $len;
        $this->y /= $len;
        $this->z /= $len;
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
        return new Vect($this->x * $s, $this->y * $s, $this->z * $s);
    }

    public function to_direction(): Direction
    {
        $x = &$this->x;
        $y = &$this->y;
        $z = &$this->z;

        return new Direction(
            atan($z / ($x * $x + $y * $y)),
            atan2($y, $x)
        );
    }

    public function to_string(): string
    {
        return '[ x=' . number_format($this->x, 3) . ' y=' . number_format($this->y, 3) . ' z=' . number_format($this->z, 3) . ' ]';
    }
}
