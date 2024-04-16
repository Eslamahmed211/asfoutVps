<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [

          "name" => "required|min:3|string",
          "email" => "required|email",
          "password" => "required|min:8|max:32|confirmed|string",
          "mobile" => "required|numeric|digits:11",
          "address" => "required|string",
          "city" => "required|string",
          "role" => "required|string",
          "active" => "required|string",
          "permissions" => "nullable",
        ];
    }

    public function messages(): array
    {
        return [
          "name.required" => "يرجي كتابة اسم المستخدم",
          "email.required" => "يرجي كتابة  البريدالالكتروني",
          "mobile.required" => "يرجي كتابة رقم المستخدم",
          "mobile.digits" => "يجب ان لا يقل رقم التليفون عن 11 رقم",
          "mobile.numeric" => "يجب ان يكون رقم التليفون رقما",
    
          "password.required" => "يرجي كتابة  كلمة المرور",
          "email.email" => "صيغة الايميل غير صحيحة",
          "password.min" => "يجب ان لا تقل كلمة المرور عن 8 ارقام",
          "password.confirmed" => "كلمة المرور غير متطابقة",
          "address.required" => "يرجي كتابة العنوان",
          "city.required" => "يرجي كتابة المحافظة",
          "role.required" => "يرجي اختيار نوع الحساب",

          "active.required" => "يرجي اختيار حالة الحساب",

        ];
    }
}
