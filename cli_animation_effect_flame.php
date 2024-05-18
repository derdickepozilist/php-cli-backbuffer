<?php

declare(strict_types=1);

require_once __DIR__ . '/cli_animation_effect.php';

class CLIAnimationEffectFlame extends CLIAnimationEffect {
    const FRAME_DELAY_MS = 20;

    const CHARS = [
        " ",
        ".",
        ":",
        "*",
        "s",
        "S",
        "#",
        "$",
    ];

    private array $b = [];
    private int $b_count;
    private int $lastline_offset;

    public function __construct(CLIBackbuffer &$bb)
    {
        $this->lastline_offset = $bb->x_cols * ($bb->y_rows - 1);

        for ($y = 0; $y < $bb->y_rows; $y++) {
            for ($x = 0; $x < $bb->x_cols; $x++) {
                $i = $y * $bb->x_cols + $x;
                $this->b[$i] = 0;
            }
        }

        $this->b_count = count($this->b);

        $i++;
        $this->b[$i] = 0;
    }

    public function renderNextFrame(int $frame, CLIBackbuffer &$bb): void
    {
        for ($i = 0; $i < $bb->x_cols / 10; $i++) {
            $random_x = random_int($i, $bb->x_cols);
            $random_item = $random_x + $this->lastline_offset;
            $this->b[$random_item] = 70;
        }

        for ($y = 0; $y < $bb->y_rows; $y++) {
            for ($x = 0; $x < $bb->x_cols; $x++) {
                $i = $y * $bb->x_cols + $x;

                $cumulation = $this->b[$i]
                            + $this->b[$i + 1]
                            + ($bb->x_cols + $i > $this->b_count ? random_int(0, 1) : $this->b[$i + $bb->x_cols])
                            + ($bb->x_cols + 1 + $i > $this->b_count ? random_int(0, 1) : $this->b[$i + $bb->x_cols + 1]);

                $this->b[$i] = intval(floor($cumulation / 3.953));

                $char = $this->b[$i] > 7 ? 7 : $this->b[$i];
                $bb->placeChar(x_pos: $x, y_pos: $y, char: self::CHARS[$char]);
            }
        }

    }

}
