<?php

namespace App\Http\Requests;

class ProfileRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => 'required|string|max:160',
            'avatar'    => 'mimes:jpeg,gif,png|max:1024'
        ];
    }


    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'full_name.string'      => 'El nombre debe ser una cadena de caracteres.',
            'full_name.max'         => 'El nombre no debe exceder :max caracteres.',
            'avatar.mimes'     => 'La imagen debe ser un archivo de tipo JPG, GIF o PNG.',
            'avatar.max'       => 'La imagen no debe ser mayor que :max kilobytes.'
        ];
    }
}