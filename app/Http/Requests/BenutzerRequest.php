<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;

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
            // Get user ID from url segment
            $userId = $this->segment(2);
        
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
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'picture' => 'image',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'username' => 'required|unique:users,username,' . $userId,
                    'password' => 'min:6',
                    'password_repeat' => 'same:password',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $userId,
                    'picture' => 'image',
                ];
            }
            default:break;
        }
    }
}