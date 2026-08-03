<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\JsonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class LoginRequest extends JsonRequest
{
 
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
