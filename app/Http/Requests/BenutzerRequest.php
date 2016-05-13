<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BenutzerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
            switch( $this->method() ){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
               {
               
               return [
                    //'name' => 'required|unique:mandants'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    //'name' => 'required'
                ];
            }
            default:break;
        }
    }
}
