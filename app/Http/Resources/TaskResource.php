<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'priority'    => $this->priority,

            'assigned_to' => [
                'id'   => $this->assignedTo?->id,
                'name' => $this->assignedTo?->name,
            ],

            'project'  => [
                'id'         => $this->project?->id,
                'name'       => $this->project?->name,
                'created_by' => [
                    'id'   => $this->project?->creator?->id,
                    'name' => $this->project?->creator?->name,
                ],
            ],
        ];
    }
}