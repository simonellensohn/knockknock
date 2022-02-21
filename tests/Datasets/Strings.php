<?php

use Illuminate\Support\Str;

dataset('strings', function () {
    yield Str::random();
    yield Str::random();
    yield Str::random();
    yield Str::random();
    yield Str::random();
});
