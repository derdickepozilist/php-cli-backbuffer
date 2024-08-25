<?php

declare(strict_types=1);

namespace CLI;

// why is it always stackoverflow?
// @see https://stackoverflow.com/a/56714690
function clearTerminal () {
    echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
}
