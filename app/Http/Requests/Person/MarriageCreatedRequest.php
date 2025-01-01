<?php

namespace App\Http\Requests\Person;

use App\Http\Requests\Request;

final class MarriageCreatedRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'gender_id' => 'required|integer',
        ];
    }

    public function getGenderId(): int
    {
        return $this->gender_id;
    }
}
