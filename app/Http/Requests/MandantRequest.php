<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Mandant;



class MandantRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //$mandant = Mandant::where('name',$this->name )->first();
        
        switch($this->method())
    {
        case 'GET':
        case 'DELETE':
        {
            return [];
        }
        case 'POST':
           {
           
           return [
                'name' => 'required|unique:mandants',
                'mandant_number'  => 'required',
                'mandant_id_hauptstelle' => 'required_if:hauptstelle,1',
            ];
        }
        case 'PUT':
        case 'PATCH':
        {
            return [
                'name' => 'required',
                'mandant_number'  => 'required',
                'mandant_id_hauptstelle' => 'required_if:hauptstelle,1',
                'mandant_id' => 'integer',
                'email' => 'email',
            ];
        }
        default:break;
    }
    }
}
