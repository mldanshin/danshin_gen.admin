<?php

namespace App\Http\Requests\Person;

use App\Http\Requests\Request;
use App\Models\Person\ParentCreatedRequest as Model;

final class ParentCreatedRequest extends Request
{
    private Model $model;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'person_id' => 'nullable|integer',
            'birth_date' => 'nullable|string',
            'role_id' => 'nullable|integer',
            'marriages' => 'sometimes|array',
            'marriages.*' => [
                'required_with:marriages',
                'integer',
                'distinct',
                'different:person_id',
            ],
        ];
    }

    public function getModel(): Model
    {
        if (empty($this->model)) {
            $this->model = new Model(
                $this->person_id,
                $this->birth_date,
                $this->role_id,
                empty($this->input('marriages')) ? collect() : collect($this->input('marriages'))
            );
        }

        return $this->model;
    }
}
