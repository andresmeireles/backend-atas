<?php

namespace App\Http\Resources;

use App\Models\MeetItem;
use GDebrauwer\Hateoas\Traits\HasLinks;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetItemResource extends JsonResource
{
    use HasLinks;

    /**
     * @param MeetItem $resource
     */
    public function __construct(public $resource)
    {
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->resource->id,
            "name" => $this->resource->name,
            "type" => $this->resource->type,
            "is_repeatable" => $this->resource->is_repeatable,
            "is_obligatory" => $this->resource->is_obligatory,
            'link' => $this->links(),
        ];
    }
}
