<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInformation extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $this->customValidation($validator);
        });
    }

    protected function customValidation($validator)
    {
        $count = count($this->all());

        if ($count !== 4) {
            $validator->errors()->add('count', 'El número de atributos no es correcto');
        }

        $keys = ['name_db', 'area', 'date', 'hour'];

        foreach ($this->all() as $key => $value) {
            if (!in_array($key, $keys)) {
                $validator->errors()->add($key, 'El atributo ' . $key . ' es incorrecto');
            }
        }

        if ($validator->errors()->any()) {
            throw new HttpResponseException(response()->json([
                'status' => 422,
                'response' => $validator->errors()->first(),
            ], 422));
        }
    }
}
