<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 
            'name' => $this->name, 
            'email' => $this->email, 
            'email_verified_at' => $this->email_verified_at, 
            // 'currency' => new CurrencyResource($this->whenLoaded('setting.currency')), 
            'currency' => $this->when(
                $this->relationLoaded('setting') && $this->setting && $this->setting->currency,
                function () {
                    return new CurrencyResource($this->setting->currency);
                }
            ),
            'created_at' => $this->created_at?->toISOString(), 
            'updated_at' => $this->updated_at?->toISOString(), 
        ];
    }
}
