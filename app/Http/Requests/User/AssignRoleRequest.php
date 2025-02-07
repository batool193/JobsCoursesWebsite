<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust this if you need custom authorization logic
    }

    public function rules()
    {
        return [
            'role' => 'required|string|in:admin,owner',
        ];
    }
}
