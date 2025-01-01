<?php

namespace App\Http\Requests;

final class FrontLogRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'message' => ['required', 'string'],
        ];
    }

    public function getMessage(): string
    {
        return $this->input('message');
    }
}
