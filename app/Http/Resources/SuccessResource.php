<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{
    protected $code , $message;

    public function __construct(int $code, $message)
    {
        JsonResource::withoutWrapping();

        $this->code = $code;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message
        ];
    }

    public function withResponse($request,$response)
    {
        $response->setStatusCode($this->code);
    }
}
