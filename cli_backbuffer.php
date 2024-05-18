<?php

declare(strict_types=1);

final class IndexOutOfRangeError extends Exception {}

class CLIBackbuffer
{
    private array $char_map = [];

    private int $x_pos = 0;
    private int $y_pos = 0;

    public function __construct(
        public readonly int $x_cols = 80,
        public readonly int $y_rows = 25,
        private string $prefill_char = ''
    ) {
        for ($y = 0; $y < $y_rows; $y++) {
            for ($x = 0; $x < $x_cols; $x++) {
                $this->char_map[self::makeInternalIdx(x: $x, y: $y)] = $prefill_char;
            }
        }
    }

    private static function makeInternalIdx(int $x, int $y): string
    {
        return $y . ',' . $x;
    }

    private function getInternalIdx(): string
    {
        return self::makeInternalIdx(x: $this->x_pos, y: $this->y_pos);
    }

    public function getPos(): array
    {
        return ['x' => $this->x_pos, 'y' => $this->y_pos];
    }

    public function setPos(int $x_pos, int $y_pos): void
    {
        if ($x_pos >= 0 && $x_pos < $this->x_cols) {
            $this->x_pos = $x_pos;
        } else {
            throw new IndexOutOfRangeError('x-pos is out of range: ' . $x_pos);
        }

        if ($y_pos >= 0 && $y_pos < $this->y_rows) {
            $this->y_pos = $y_pos;
        } else {
            throw new IndexOutOfRangeError('y-pos is out of range: ' . $y_pos);
        }
    }

    public function placeChar(int $x_pos, int $y_pos, string $char): void
    {
        if (mb_strlen($char) > 1) throw new InvalidArgumentException('Char is only allowed for placeChar');
        $this->char_map[self::makeInternalIdx(x: $x_pos, y: $y_pos)] = $char;
    }

    public function writeChar(string $char = ''): void
    {
        if (in_array(mb_strlen($char), [0, 1], true)) {
            $this->char_map[$this->getInternalIdx()] = $char;
        } else {
            throw new InvalidArgumentException('char must have a length of 0 or 1');
        }
    }

    public function getRemCharsRow(): int
    {
        return $this->x_cols - ($this->x_pos + 1);
    }

    public function writeString(string $string = ''): void
    {
        if (mb_strlen($string) > 0) {
                $str_idx = 0;

                while (true) {
                    $rem_chars_row = $this->getRemCharsRow();
                    $rem_chars_str = mb_strlen($string) - $str_idx;
                    if ($rem_chars_str === 0) break;

                    if ($rem_chars_row === 0) {
                        if ($this->y_pos + 1 === $this->y_rows) break;

                        $this->breakline();
    
                        $rem_chars_row = $this->getRemCharsRow();
                    }

                    $shortest_collision = min($rem_chars_row, $rem_chars_str);

                    if ($this->x_pos === $this->x_cols - 1 && $this->y_pos < $this->y_rows - 1) {
                        $this->breakline();
                    }

                    for ($i = 0; $i < $shortest_collision; $i++) {
                        $this->char_map[$this->getInternalIdx()] = mb_substr($string, $str_idx++, 1);
                        $this->setPos($this->x_pos + 1, $this->y_pos);
                    }

                    if ($shortest_collision === $rem_chars_row) {
                        if ($this->y_pos + 1 === $this->y_rows) break;
                        $this->breakline();
                    }
                }
        } else {
            throw new InvalidArgumentException("Empty string provided");
        }
    }

    public function breakline(): void
    {
        $this->setPos(0, $this->y_pos + 1);
    }

    public function writeLine(string $string = ''): void
    {
        $this->writeString($string);
        $this->breakline();
    }

    public function writeout(): string
    {
        $out_string = '';

        for ($y = 0; $y < $this->y_rows - 1; $y++) {
            for ($x = 0; $x < $this->x_cols - 1; $x++) {
                $out_string .= $this->char_map[self::makeInternalIdx(x: $x, y: $y)];
            }

            $out_string .=  PHP_EOL;
        }

        return $out_string;
    }
}
