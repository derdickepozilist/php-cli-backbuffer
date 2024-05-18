<?php

declare(strict_types=1);

require_once __DIR__ . '/cli_backbuffer.php';
require_once __DIR__ . '/cli_helper.fn.php';

$whole_start = microtime(true);

$start = microtime(true);
$bb = new CLIBackbuffer(y_rows: 17, prefill_char: '');
$dur_instance = microtime(true) - $start;

$start = microtime(true);
$bb->setPos(0, 0);
$bb->writeString('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.');
$dur_100_words = microtime(true) - $start;

$start = microtime(true);
$bb->breakline();
$bb->breakline();
$bb->writeString('😀 😃 😄 😁 😆 😅 😂 🤣 ☺️ 😊 😇 🙂 🙃 😉 😌 😍 😘 😗 😙 😚 😋 😜 😝 😛 🤑 🤗 🤓 😎 🤡 🤠 😏 😒 😞 😔 😟 😕 🙁 ☹️ 😣 😖 😫 😩 😤 😠 😡 😶 😐 😑 😯 😦 😧 😮 😲 😵 😳 😱 😨 😰 😢 😥 🤤 😭 😓 😪 😴 🙄 🤔 🤥 😬 🤐 🤢 🤮 🤧 😷 🤒 🤕 🤨 🤩 🤯 🧐 🤫 🤪 🥺 🤭');
$dur_emoji = microtime(true) - $start;

$bb->breakline();
$bb->breakline();
$bb->writeString('instance dur: ' . number_format($dur_instance, 6, '.', '') . 's');
$bb->breakline();
$bb->writeString('100words dur: ' . number_format($dur_100_words, 6, '.', '') . 's');
$bb->breakline();
$bb->writeString('emojis   dur: ' . number_format($dur_emoji, 6, '.', '') . 's');

$start = microtime(true);
$writeout = $bb->writeout();
$dur_writeout = microtime(true) - $start;
$dur_whole_process = microtime(true) - $whole_start;

clearTerminal();
print($writeout);
print 'writeout dur: ' . number_format($dur_writeout, 6, '.', '') . 's' . PHP_EOL;
print 'cumul.   dur: ' . number_format($dur_whole_process, 6, '.', '') . 's' . PHP_EOL;
