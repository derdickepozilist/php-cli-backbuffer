<?php

declare(strict_types=1);

namespace CLI\Animation\Ani3D;

// ray ends when it hits the floor at z = 0
function ray_done(Vect &$ray): bool
{
    return $ray->z <= 0;
}

// determines character to be printed for finished ray
function ray_char(Vect &$ray, int $refl): string
{
    static $chars = [
        '.',
        '-',
        ',',
    ];

    if (ray_done($ray) && abs(intval(floor($ray->x)) - intval(floor($ray->y))) % 2 === 0) {
        return '#';
    } else if ($refl > 0) {
        if ($refl < 4) return $chars[$refl-1];
        else return '+';
    } else return '';
}
