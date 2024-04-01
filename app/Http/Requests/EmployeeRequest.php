<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use App\Traits\MessageHelper;

class EmployeeRequest extends FormRequest
{
    use MessageHelper;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:1|max:100',
            'email' => 'required|email|max:80',
            'phone' => 'required|max:15',
            'departament_id' => 'required|numeric|exists:departaments,id'
            /* La regla exists significa que el valor del campo debe existir en una tabla de la base de datos
            . En este caso, el valor del campo platform_id debe existir en la columna platform_id de la tabla platforms. 
            Si el valor del campo platform_id no existe en la tabla platforms, la validación fallará. */
        ];
    }

    public function messages() : array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.min' => 'El nombre debe tener al menos una letra',
            'name.max' => 'Se excedio el numero de caracteres permitidos',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'Email no valido',
            'email.max' => 'Se excedio el numero de caracteres permitidos para el email',
            'phone.required' => 'El telefono es permitido',
            'phone.max' => 'El numero de telefono excede la cantidad permitida',
            'departament_id.required' => 'El id del departamento es obligatorio',
            'departament_id.numeric' => 'El id del departamento debe ser de tipo numerico',
            'departament_id.exists' => 'El id del departamento no existe'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException($this->jsend_fail($errors));
    }
}
