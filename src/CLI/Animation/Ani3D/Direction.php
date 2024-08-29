<?php

declare(strict_types=1);

namespace CLI\Animation\Ani3D;

class Direction
{
    public function __construct(
        public float $altitude = 0.0,
        public float $azimuth = 0.0,
    ) {}

    public function to_unit(): Vect
    {
        return new Vect(
            cos($this->altitude) * cos($this->azimuth),
            cos($this->altitude) * sin($this->azimuth),
            sin($this->altitude)
        );
    }

    public function to_string(): string
    {
        $v = $this->to_unit();
        return '{ alt=' . number_format($this->altitude, 3) . ' azi=' . number_format($this->azimuth, 3) . ' ' . $v->to_string() . ' }';
    }
}
