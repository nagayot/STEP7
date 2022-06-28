<?php

namespace App\Http\UseCase\lineup;

use App\Model\Board;

use App\Models\Company;
use App\Models\Product;
// Requestの受け取り、受け渡しに必要

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;

use Illuminate\Support\Collection;

class lineupCase {
  public function __invoke(Request $request) {
    // 箱  : $product_instanceという名前の変数(function同様に、中身が分かるものがよい)
    // 中身: Models/Product.phpのProductクラスのインスタンス
    // newするとクラスのインスタンスが使えます。
    $product_instance = new Product;

    // 箱  : $company_instanceという名前の変数(function同様に、中身が分かるものがよい)
    // 中身: Models/Company.phpのCompanyクラスのインスタンス
    // newするとクラスのインスタンスが使えます。
    $company_instance = new Company;

    // 箱  ： $product_listという名前の変数(function同様に、中身が分かるものがよい)
    // 中身： Product.phpのproduct_infoにアクセス
    $product_list = $product_instance->product_list();

    // 箱  ： $company_dataという名前の変数(function同様に、中身が分かるものがよい)
    // 中身： Company.phpのcompany_infoにアクセス
    $company_data = $company_instance->company_info();
  }
}