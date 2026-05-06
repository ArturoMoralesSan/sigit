<?php

namespace App\Http\Requests;

class VoucherRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'req'        => 'required|string',
            'teacher'    => 'required|string',
            'group'      => 'required|string',
            'subject'    => 'required|string',
            'laboratory' => 'required|string',
            'return_date'=> 'required',
            'status'     => 'required|string'
            #'study.*._estudio' => 'required|string|max:255',
        ];

        for($i = 1; $i <= $this->product_count; $i++) {
            $rules['product' . $i . '_producto'] = 'required';
            $rules['product' . $i . '_quantity'] = 'required';
        }
        
        return $rules;
    }
}
