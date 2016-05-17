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
        return true;
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
                    'username' => 'required|unique:users',
                    'password' => 'required|min:6',
                    'password_repeat' => 'same:password',
                    'vorname' => 'required',
                    'nachname' => 'required',
                    'email' => 'required|email|unique:users',
                    'picture' => 'image',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'username' => 'required|unique:users',
                    'password' => 'required|min:6',
                    'password_repeat' => 'same:password',
                    'vorname' => 'required',
                    'nachname' => 'required',
                    'email' => 'required|email|unique:users',
                    'picture' => 'image',
                ];
            }
            default:break;
        }
    }
}
