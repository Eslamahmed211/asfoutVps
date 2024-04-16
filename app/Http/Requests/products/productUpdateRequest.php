<?php

namespace App\Http\Requests\products;

use Illuminate\Foundation\Http\FormRequest;

class productUpdateRequest extends FormRequest
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
          "name" => "required|string",
          "slug" => "nullable|string",

          "dis" => "required|string",


          "price" => "required|numeric",
          "stock" => "required|numeric|min:0",
          "sku" => "required",

          "categories" => "required",


          "ponus" => "nullable|numeric|min:0" ,
          "min_comissation" => "nullable|numeric|min:0" ,
          "max_comissation" => "nullable|numeric|min:0" ,
          "unavailable" => "required" ,
          "comissation" => "nullable|numeric|min:0" ,
          "systemComissation" => "required|numeric|min:0" ,
          "drive" => "nullable" ,
          "nickName" => "required" ,

        ];
    }


    function messages() {
      return [
        "name.required" => "اسم المنتج مطلوب",
        "dis.required" => "وصف المنتج مطلوب",
        "price.required" => "سعر المنتج مطلوب",
        "stock.numeric" => "الاستوك يجب ان يكون رقم",
        "sku.required" => "وحدة الإحتفاظ بالمخزون (SKU) مطلوب",
        "categories.required" => "يرجي اختيار تصنيفات للمنتج",

        "trader_id" => "يرجي اختيار تاجر" ,
        "nickName" => "يرجي كتابة اسم الشهرة" ,
        "price.min" => "يجب ان لا يقل سعر المنتج عن 0 " ,
        "systemComissation.required" => "يرجي اضافة عمولة السيستم"

      ];
    }
}
