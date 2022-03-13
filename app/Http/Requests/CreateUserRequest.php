<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'email' => [
            'required',
            'min:1',
            'max:255',
          ],
          'name' => [
            'required',
            'min:1',
            'max:255',
          ],
          'role' => 'required:integer',
          'password' => [
            'required',
            'min:1',
            'max:255',
          ],
          'password-confirm' => [
            'required',
            'min:1',
            'max:255',
          ],
        ];
    }
}
