<?php

declare(strict_types=1);

namespace CLI\Animation\Object3D;

use CLI\Animation\Ani3D\Vect;

class Ball
{
    const float BOUNCE_FRAME_STEP = 0.3;
    public function __construct(
        public Vect $center = new Vect(),
        public float $radius = 1.0
    ) {
        $this->zmin = $this->center->z - $radius;
        $this->zmax = $this->center->z + $radius;
    }

    public float $updown = -1;
    public float $zmin;
    public float $zmax;

    public function reflect(Vect &$incoming, Vect &$move): Vect
    {
        $this->center->scale(-1);
        $incoming->add($this->center);
        $this->center->scale(-1);
        $incoming->normalize();

        $incoming->scale(-2 * $incoming->dot($move));
        $new_move = $move;

        $new_move->add($incoming);

        return $new_move;
    }

    public function bounce_frame(): void
    {
        $this->center->z += self::BOUNCE_FRAME_STEP * $this->updown;

        if ($this->center->z < $this->zmin) {
            $this->updown *= -1;
        } else if ($this->center->z > $this->zmax) {
            $this->updown *= -1;
        }
    }
}
