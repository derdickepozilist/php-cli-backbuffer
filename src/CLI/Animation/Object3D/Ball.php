<?php

declare(strict_types=1);

namespace CLI\Animation\Object3D;

use CLI\Animation\Ani3D\Vect;

class Ball
{
    public Vect $center;
    public float $radius;

    public function reflect(Vect &$incoming, Vect &$move): Vect
    {
        $this->center->scale(-1);
        $incoming->add($this->center);
        $this->center->scale(-1);
        $incoming->normalize();

        $incoming->scale(-2 * $incoming->dot($move));
        $new_move = clone $move;

        $new_move->add($incoming);

        return $new_move;
    }
}
