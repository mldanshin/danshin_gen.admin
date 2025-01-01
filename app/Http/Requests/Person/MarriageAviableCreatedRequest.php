<?php

namespace App\Http\Requests\Person;

use App\Http\Requests\Request;
use App\Models\Person\MarriageCreatedRequest as Model;

final class MarriageAviableCreatedRequest extends Request
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
            'temp_id' => 'required|string',
            'person_id' => 'nullable|integer',
            'birth_date' => 'nullable|string',
            'role_id' => 'nullable|integer',
            'parents' => 'sometimes|array',
            'parents.*' => [
                'required_with:parents',
                'integer',
                'distinct',
                'different:person_id',
            ],
        ];
    }

    public function getModel(): Model
    {
        $date = null;

        try {
            $datetime = new \DateTime($this->birth_date);
            $date = $datetime->format('Y-m-d');
        } catch (\Exception) {
        }

        if (empty($this->model)) {
            $this->model = new Model(
                $this->person_id,
                $date,
                $this->role_id,
                empty($this->input('parents')) ? collect() : collect($this->input('parents'))
            );
        }

        return $this->model;
    }

    public function getTempId(): string
    {
        return $this->temp_id;
    }
}
