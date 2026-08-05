<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // Return the provided data array as-is (safe defaults handled by caller)
        return is_array($this->resource) ? $this->resource : [];
    }
}
