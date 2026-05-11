<?php

namespace App\Http\Requests;


class EquipmentRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'num_serie'   => 'required|string|max:160',
            'control_tag' => 'required|string',
            'area'        => 'required|string',
            'product'     => 'required|string',
            'category'    => 'required|string',
            'brand'       => 'required|string',
            'model'       => 'required|string',
            'pu'          => 'required|string',
            'status'      => 'required|string',
            'quantity'    => 'required|string',
        ];
    }
}
