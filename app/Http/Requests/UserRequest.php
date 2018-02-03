<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return  [
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'username' => 'required|unique:users|max:50',
                'email' => 'required|unique:users|max:50',
                'password' => 'required|max:50',
                'con_password' => 'required|same:password|max:50',
                'user_package_type' => 'required'
        ];
    }
}
