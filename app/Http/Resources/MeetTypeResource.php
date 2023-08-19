<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\MeetType;
use GDebrauwer\Hateoas\Traits\HasLinks;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetTypeResource extends JsonResource
{
    use HasLinks;

    /**
     * @param MeetType $resource
     */
    public function __construct(public $resource)
    {
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'links' => $this->links()
        ];
    }
}
