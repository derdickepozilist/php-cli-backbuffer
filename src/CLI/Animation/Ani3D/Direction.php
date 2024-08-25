<?php

declare(strict_types=1);

namespace CLI\Animation\Ani3D;

class Direction
{
    public float $ang_v;
    public float $ang_h;

    public function to_unit(): Vect
    {
        $v = new Vect();
        $v->x = cos($this->ang_v) * cos($this->ang_h);
        $v->y = cos($this->ang_v) * sin($this->ang_h);
        $v->z = sin($this->ang_v);
        return $v;
    }
}
