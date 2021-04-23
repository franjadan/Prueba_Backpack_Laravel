<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5|max:150',
            'description' => 'required|min:5|max:255'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => "El título es obligatorio",
            'title.min' => "El título debe tener mínimo 5 caracteres",
            'title.max' => 'El título no puede tener más de 150 caracteres',
            'description.required' => "La descripción es obligatorio",
            'description.min' => "La descripción debe tener mínimo 5 caracteres",
            'description.max' => 'La descripción no puede tener más de 150 caracteres',
        ];
    }
}
