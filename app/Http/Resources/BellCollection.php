<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BellCollection extends ResourceCollection
{
    public $collects = BellResource::class;
}
