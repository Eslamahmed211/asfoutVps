<?php

namespace App\Http\Requests\users\cart;

use Illuminate\Foundation\Http\FormRequest;

class CheckOutRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
   */
  public function rules(): array
  {
    return [
      "clientName" => "required|string|min:3",
      "clientPhone" => "required|string|digits:11",
      "clientPhone2" => "nullable|string|digits:11",
      "city" => "required|integer",
      "address" => "required|string",
      "notes" => "nullable|string",
      "notesBosta" => "nullable|string",
      "status" => "nullable|string",
      "get" => "nullable|integer|min:0",
      "take" => "nullable|integer|min:0",
    ];
  }

  public function messages(): array
  {
    return [
      "clientName.required" => "يرجي كتابة اسم العميل",
      "clientName.min" => "يجب ان لا يقل اسم العميل عن 3 حروف",
      "clientPhone.digits" => "يجب ان  يكون رقم العميل الاول 11 رقم",
      "clientPhone2.digits" => "يجب ان  يكون رقم العميل الثاني 11 رقم",
      "city.required" => "يرجي اختيار المحافظة",
      "page.required" => "يرجي كتابة اسم البيدج",
      "address.required" => "يرجي كتابة عنوان العميل ",
    ];
  }
}
