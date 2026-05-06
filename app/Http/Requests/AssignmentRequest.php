<?php

namespace App\Http\Requests;

class AssignmentRequest extends FormRequest
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'user'        => 'required',
            
            #'study.*._estudio' => 'required|string|max:255',
        ];

        for($i = 1; $i <= $this->product_count; $i++) {
            $rules['product' . $i . '_producto'] = 'required';
            $rules['product' . $i . '_quantity'] = 'required';
        }
        
        return $rules;
    }
}
