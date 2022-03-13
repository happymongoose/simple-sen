<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateStudentRequest extends FormRequest
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
          'first_name' => [
            'required',
            'min:1',
            'max:255',
          ],
          'last_name' => [
            'required',
            'min:1',
            'max:255',
          ],
          'year-group' => 'required:integer',
          'teaching-group' => 'required:integer'
        ];
    }
}
