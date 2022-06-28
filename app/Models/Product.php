<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// クエリビルダで書くのに必要なuse
// Laravelのベストプラクティス的にはEloquentORMがオススメとされています
// それでもクエリビルダを用いる理由は、以下の２つ
// ① EloquentORMより処理速度が速い
// ② SQLの勉強にもなる
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    // Product.php(Model)と紐づくDBのテーブルを選択します
    protected $table = 'products';

    // 可変項目
    // productsテーブル内で、値をいじりたいカラムを書き出します
    protected $fillable = [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'image'
    ];

    /**
     * リレーション組み
     * NOTE：製品は１つの会社のもの
     *
     * @return void
     */
    public function company() {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * 一覧表示用のデータ
     *
     * @return $products
     */
    public function productList() {
        //  箱  : $productsという名前の変数(function同様に、中身が分かるものがよい)
        // 中身 : クエリビルダでproductsテーブル内のデータを取得します
        $products = DB::table('products')

            // productsテーブルのcompany_idとcompaniesテーブルのidで、joinを使ってリレーションを組みます
            ->join('companies', 'products.company_id', '=', 'companies.id')

            // select文で、DBからとってきたい値のあるカラムを選択します
            ->select(
                'products.id',
                'products.image',
                'products.product_name',
                'products.price',
                'products.stock',
                'products.comment',
                // ↓を取ろうとすると、products.idをcomapnies.idが上書きしてしまうので、選択しないこと
                // 'compaies.id',
                'companies.company_name',
            )

            // orderByを使って、productsテーブルのidが新しい順に並び替えます
            ->orderBy('products.id', 'desc')

            // １ページ最大5件になるようにページネーション機能を使います
            // '/resources/views/product/lineup.blade.php'にもページネーションに関する記述をお忘れなく！
            ->paginate(5);

        // オブジェクトとして扱えるようにします
        return $products;
    }

    /**
     * 入力されたキーワードで商品を検索します
     *
     * ProductControllerのshowLineupメソッドで、
     * search_product_by_keywordの引数に$keywordを渡しました。
     * 
     * なんと、名前を変えても引数の位置(第一引数や第二引数のこと)が同じものが使えます！
     * ProductController側では$keywordだったものを、こちらでは$paramとして使っています。
     * 
     * 
     * @param  $param
     * @return $products
     */
    public function searchProductByKeyword($param) {

        //  箱  : $productsという名前の変数(function同様に、中身が分かるものがよい)
        // 中身 : クエリビルダでproductsテーブル内のデータを取得します
        $products = DB::table('products')

        // productsテーブルのcompany_idとcompaniesテーブルのidで、joinを使ってリレーションを組みます
        ->join('companies', 'products.company_id', '=', 'companies.id')

        // product_nameと検索欄に入力された文字が部分的に一致しているものを、productsテーブルから取得します
        ->where('products.product_name', 'LIKE', '%'.$param.'%')

        // orderByを使って、productsテーブルの'id'カラムの降順で並び替えます
        ->orderBy('products.id', 'desc')

        // １ページ最大5件になるようにページネーション機能を使います
        // '/resources/views/product/lineup.blade.php'にもページネーションに関する記述をお忘れなく！
        ->paginate(5);

        // オブジェクトとして扱えるようにします
        return $products;
    }

    /**
     * 選択されたメーカー名に紐づくcompaniesテーブルのidで商品を検索します
     *
     * ProductControllerのshowLineupメソッドで、
     * search_product_by_company_nameの引数に$selected_nameを渡しました。
     * 
     * なんと、名前を変えても引数の位置(第一引数や第二引数のこと)が同じものが使えます！
     * ProductController側では$selected_nameだったものを、こちらでは$paramとして使っています。
     * 
     * @param $param
     * @return $products
     */
    public function searchProductByCompanyName($param) {

        //  箱  : $productsという名前の変数(function同様に、中身が分かるものがよい)
        // 中身 : クエリビルダでcompaniesテーブル内のデータを取得します
        $products = DB::table('companies')

        // companiesテーブルのidとproductsテーブルのcompany_idで、joinを使ってリレーションを組みます
        ->join('products', 'companies.id', '=', 'products.company_id')

        // 選択されたメーカ名に紐づくcompany_idと一致しているものを、productsテーブルから取得します
        ->where('products.company_id', $param)

        // orderByを使って、productテーブルの'created_at'カラムの昇順で並び替えます
        ->orderBy('products.created_at', 'asc')

        // １ページ最大5件になるようにページネーション機能を使います
        // '/resources/views/product/lineup.blade.php'にもページネーションに関する記述をお忘れなく！
        ->paginate(5);

        // オブジェクトとして扱えるようにします
        return $products;
    }

    /**
     * 商品情報の詳細データ
     *
     * @param $id
     * @return $product
     */
    public function productDetail($id) {
        //  箱  : $productsという名前の変数(function同様に、中身が分かるものがよい)
        // 中身 : クエリビルダでproductsテーブル内のデータを取得します
        $product = DB::table('products')

        // productsテーブルのcompany_idとcompaniesテーブルのidでリレーションを組みます
        ->join('companies', 'products.company_id', '=', 'companies.id')

        // select文で、DBからとってきたい値のあるカラムを選択します
        ->select(
            'products.id',
            'products.image',
            'products.product_name',
            'products.price',
            'products.stock',
            'products.comment',
            'products.company_id',
            // ↓を取ろうとすると、products.idをcomapnies.idが上書きしてしまうので、選択しないこと
            // 'companies.id',
            'companies.company_name',
        )

        // productsテーブルのidと、$idの持つidの値が一致するものを探して、
        ->where('products.id', $id)

        // その中で最初の1件を取得します
        ->first();

        // オブジェクトとして扱えるようにします
        return $product;
    }


    /**
     * 商品を登録します
     * 
     * ProductControllerのexeStoreメソッドで、
     * create_productの引数に$insert_dataを渡しました。
     * 
     * なんと、名前を変えても引数の位置(第一引数や第二引数のこと)が同じものが使えます！
     * ProductController側では$insert_dataだったものを、こちらでは$paramとして使っています。
     * 
     * @param $param
     */
    public function createProduct($param) {
        // productsテーブルのカラム(=>の左)に、引数(=>の右)の値を挿入します
        DB::table('products')->insert([
            'company_id'   => $param['company_id'],
            'product_name' => $param['product_name'],
            'price'        => $param['price'],
            'stock'        => $param['stock'],
            'comment'      => $param['comment'],
            'image'        => $param['image']
        ]);
    }

    /**
     * 商品情報の更新します
     * 
     * ProductControllerのexeUpdateメソッドで、
     * update_productの引数に$update_dataを渡しました。
     * 
     * なんと、名前を変えても引数の位置(第一引数や第二引数のこと)が同じものが使えます！
     * ProductController側では$update_dataだったものを、こちらでは$paramとして使っています。
     * 
     * @param $param
     */
    public function updateProduct($param) {
        // productsテーブルの
        DB::table('products')

        // productsテーブルのidと、$idの持つidの値が一致するものを探して、
        ->where('id', $param['id'])

        // それぞれのカラムの内容(=>の左)を引数(=>の右)の値で更新します
        ->update([
            'company_id'   => $param['company_id'],
            'product_name' => $param['product_name'],
            'price'        => $param['price'],
            'stock'        => $param['stock'],
            'comment'      => $param['comment'],
            'image'        => $param['image']
        ]);
    }

    /**
     * 商品情報を削除します
     *
     * @param  $id
     */
    public function deleteProduct($id) {
        // productsテーブルのidと、$idの持つidの値が一致するものを削除します
        DB::table('products')->delete($id);
    }
}
