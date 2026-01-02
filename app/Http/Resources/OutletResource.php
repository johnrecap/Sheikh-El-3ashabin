<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"               => $this->id,
            "delivery_zone_id" => $this->delivery_zone_id,
            "name"             => $this->name,
            "email"            => $this->email === null ? '' : $this->email,
            "phone"            => $this->phone === null ? '' : $this->phone,
            "country_code"     => $this->country_code === null ? '' : $this->country_code,
            "latitude"         => $this->latitude === null ? '' : $this->latitude,
            "longitude"        => $this->longitude === null ? '' : $this->longitude,
            "address"          => $this->address,
            "status"           => $this->status
        ];
    }
}