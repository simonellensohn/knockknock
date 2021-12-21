<?php

namespace App\Http\Resources;

use App\Models\Bell;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Bell */
class BellResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'threshold' => $this->threshold,
            'rings_count' => $this->when($this->rings_count !== null, $this->rings_count),
        ];
    }
}
