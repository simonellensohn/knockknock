<?php

namespace App\Events;

use App\Models\Ring;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Ring $ring)
    {}
}
