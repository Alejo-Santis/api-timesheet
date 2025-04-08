<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimesheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $minutes = $this->day_in->diffInMinutes($this->day_out);
        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;

        return [
            'id' => $this->id,
            'type' => $this->type,
            'start' => $this->day_in->toDateTimeString(),
            'end' => $this->day_out->toDateTimeString(),
            'duration' => "{$hours}h {$minutes}m",
        ];
    }
}
