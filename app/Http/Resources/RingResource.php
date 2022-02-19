<?php

namespace App\Http\Resources;

use App\Models\Ring;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Ring */
class RingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'volume' => $this->volume,
            'events' => $this->events,
            'created_at' => [
                'diff' => $this->created_at?->diffForHumans(),
                'date' => $this->created_at?->toDateTimeString(),
            ],
            'bell' => BellResource::make($this->whenLoaded('bell')),
        ];
    }
}
