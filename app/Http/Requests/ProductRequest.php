<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requestクラスを新たに作る理由は、Controllerにバリデーションを書くこと(Fat Controllerになること)を避けるためです
 */
class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            // 自作のバリデーションを設定できます
            // DBと同様の設定が好ましいです(varchar(255)みたいなのがあるはず)
            'company_id' => 'required | max:20',
            'product_name' => 'required | max:100',
            'price' => 'required | max:11',
            'stock' => 'required | max:11',
            'comment' => 'max:255'
        ];
    }
}
