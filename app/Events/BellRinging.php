<?php

namespace App\Events;

use App\Models\Bell;
use App\Models\Ring;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BellRinging
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Bell $bell,
        public Ring $ring
    ) {
    }
}
