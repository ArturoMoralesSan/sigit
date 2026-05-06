<?php

namespace App\Http\Requests;

class BookingRequest extends FormRequest
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:160',
            'name' => 'required|string',
            'subject' => 'required|string',
            'status' => 'required|string', 
        ];
    }
}
