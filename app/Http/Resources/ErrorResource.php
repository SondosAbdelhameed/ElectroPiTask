<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ErrorResource extends JsonResource
{
    protected $code , $message, $errors;

    public function __construct(int $code, $message , $errors = null)
    {
        JsonResource::withoutWrapping();

        $this->code = $code;
        $this->message = $message == null ? "Issue reported, please try again later" : $message;
        $this->errors = $errors;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if($this->errors){
            Log::error($this->errors);
        }

        return  [
                'message' => $this->message
            ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->code);
    }
}
