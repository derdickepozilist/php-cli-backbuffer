# php-cli-backbuffer

A simple backbuffer for preparing cli-output before actually outputting it all at once.

PHP Version 8.2

## Testing/playing fire

To test the cli backbuffer in general, an integration test just inputs a lorem ipsum and emojis into it and outputs everything while measuring the individual durations for every task.

Please note: The display of emojis in Terminal is inconsistend and WILL seemingly overflow the line, until you copy the text and measure it in a text-editor to see, that there is no overflow, just a display bug. Emojis are used to fit UTF-8 compatibility.

$ php ./cli_test_backbuffer_integration_performance.php

To just directly look at the flames, start the animation effect flame test (you may have to manually adjust the size of the backbuffer in line 10).

$ php ./cli_test_animation_effect_flame.php

## "Documentation"

The `CLIAnimationEngine` takes an `CLIAnimationEffect` and a `CLIBackbuffer` for repeatedly creating frames.

`CLIAnimationEffect` will then procedurally generate frames by consecutively calculating over the last round's values.

`CLIBackbuffer` gets filled by `CLIAnimationEffect` exactly once per frame outputting the full diplay page as a long string which gets outputted to the terminal.

### CLIBackbuffer

My heartpiece of the project.

`constructor` the constructor takes the size of the terminal to buffer with `x_cols` and `y_rows`.
Furthermore you can give a `prefill_char` to prefill the buffer with; otherwise it will be prefilled with {empty string}.

`CLIBackbuffer` has a map (assoc-array) of all 2-dimensional values in 1 dimension (by making the keys 2-dimensional).

`makeInternalIdx` is used to build these keys by inserting x and y coordinates

`getPos` gives an assoc-array with ['x'] and ['y'] coordinates of the virtual write-cursor as int.

`setPos` sets the cursor position for writing.

`writeChar` writes a char at the given cursor position (not automatically advancing forward).

`writeString` writes a string at the given cursor position advancing the cursor forward, advancing lines forward.

`breakline` moves the cursor to the beginning of the next line.

`writeLine` combines `writeString` with `breakline`.

`writeout` returns the backbuffers content as a string.

`getRemCharsRow` returns the count of available chars in the current row as seen from the current cursor position.

`placeChar` places a char at a specific location not touching the cursor position.


### CLIAnimationEngine

A simple motivator for the CliAnimationEffect to run continously.

`constructor` takes a backbuffer and an effect to orchestrate.

`outputNextFrame` does everything necessary to get the next frame going: 
Uses the effect to calculate a new frame, clear the terminal and output the buffer.

`runForever` just consecutively generates frames for your viewing pleasure.


### CLIAnimationEffect

CLIAnimationEffect is an abstract class to shim other different effects into the same behaviour of having a `renderNextFrame` function and a `FRAME_DELAY_MS` constant.

### CLIAnimationEffectFlame

This code is mostly ported and unobfuscated from javascript and modified to use my backbuffer instead of the JS-window-Element.
