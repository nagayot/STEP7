<?php

// DBとのやりとりをする処理はModelに書いていきます
// ContorollerでDBとのやり取りをしたい時は、DBとやり取りしているModelのメソッドを呼び出す形にしましょう！

// ↓Product.phpの住所↓
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// クエリビルダで書くのに必要なuse
// Laravelのベストプラクティス的にはEloquentORMがオススメとされています
// それでもクエリビルダを用いる理由は、以下の２つ
// ① EloquentORMより処理速度が速い
// ② SQLの勉強にもなる
// 2つの違いについての記事を、READMEにのっけています！
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
     * join文でproductsテーブルとcompaniesテーブルを合体して、
     * select文で欲しいカラムを絞り込むクエリ部分
     * Product.php内では、$this->joinAndSelect()で呼び出すことができます。
     * 
     * 呼び出している部分すべてでこの記述をしていると、例えばproductsテーブルのimageカラム無くしますとなった場合
     * 書いた分だけ修正する必要がありますが、joinAndSelectに書き出してそれを呼び出す今の形にすれば
     * このメソッド内のsql文だけ変更すればいいだけなので、超楽です！
     * 
     * ここの説明意味わからん場合、理解できるまで説明するので、連絡ください！
     *
     * @return $sql
     */
    public function joinAndSelect() {
        // productsテーブルに対して
        $sql = DB::table('products')
            // companiesテーブルをくっつけます。
            ->join('companies', 'products.company_id', '=', 'companies.id')
            // select文を使って、2つのテーブルから欲しいカラムを選択します
            ->select(
                'products.id',
                'products.image',
                'products.product_name',
                'products.price',
                'products.stock',
                'products.comment',
                'companies.company_name',
            );

        // オブジェクトとして使えるようにします
        return $sql;
    }


    /**
     * 一覧表示用のデータ
     *
     * @return $products
     */
    public function productList() {
        //  箱  : $productsという名前の変数(function同様に、中身が分かるものがよい)
        // 中身 : クエリビルダでproductsテーブル内のデータを取得します
        // $thisでモデル(Product.php)のクラスを指定して、その中にあるjoinAndSelectメソッドを呼び出します
        $products = $this->joinAndSelect()

            // orderByを使って、productsテーブルのselect文で選んだデータを、idの降順で並び替えます
            ->orderBy('products.id', 'desc')

            // １ページ最大5件になるようにページネーション機能を使います
            // '/resources/views/product/lineup.blade.php'にもページネーションに関する記述をお忘れなく！
            ->paginate(5);

        // オブジェクトとして扱えるようにします
        // つまりどういうことかと言いますと、DB::table('products')~paginate(5);までを
        // 最初に作った$productsという箱で持ち運びできるようにしようってわけですわ
        return $products;
    }

    /**
     * 検索機能
     * 
     * Product.php内で一番情報盛りだくさんになってしまった・・・
     * このメソッドの中身ちゃんと説明するので、わからない場合は遠慮なくDMください。。
     * 
     * NOTE:if文を３つに分けるのと、else ifにするの、どっちが可読性高いんだろう？
     *
     * @param [type] $keyword
     * @param [type] $company_name
     * @return $result
     */
    public function searchProductByParams($keyword, $company_name) {
        // 変数$queryの中身は、Product.phpのjoinAndSelectメソッドでreturnされている$sql
        // つまり、join文とselect文
        $query = $this->joinAndSelect();

        // もし$keywordが空っぽでなければ = キーワードを入力して検索ボタン押したら
        if (!empty($keyword)) {
            // select文の続き
            // where文で絞り込みます(入力されたキーワード($keyword)の文字列を含む、product_name)
            $query->where('products.product_name', 'LIKE', '%'.$keyword.'%');
        }
        // もし$company_nameが空っぽでなければ = メーカー名を選んで検索ボタンを押したら
        if (!empty($company_name)) {
            // select文の続き
            // where文で絞り込みます(選択されたメーカー名($company_name)の、company_id)
            $query->where('products.company_id', $company_name);
        }
        // もし$keywordが空っぽでないかつ、$company_nameも空っぽでない場合
        // つまり、キーワード入力とメーカー名選択の両方を行って検索ボタンを押したら
        if (!empty($keyword) && !empty($company_name)) {
            // select文の続き
            // where文で何を絞り込んでいるかは...もう分かるな？
            $query->where('products.product_name', 'LIKE', '%'.$keyword.'%')
                ->where('products.company_id', $company_name);
        }

        // $resultという変数(箱)に、$query(where文の続き)を入れてあげます
        // orderByでproductsテーブルのidカラム降順にして
        $result = $query->orderBy('products.id', 'desc')
            // １ページ最大5件表示になるように、ページネーションします
            ->paginate(5);

        // オブジェクトとして扱えるようにします
        // searchProductByParamsが呼び出すと、$resultがもらえるよ
        return $result;
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
        // select文でとってくるものが違うので、$this->joinAndSelect()は使わない方向で！
        $product = DB::table('products')

            // productsテーブルのcompany_idとcompaniesテーブルのidでリレーションを組みます
            ->join('companies', 'products.company_id', '=', 'companies.id')

            // select文で、DBからとってきたい値のあるカラムを選択します
            // 'テーブル名.カラム名'
            ->select(
                'products.id',
                'products.image',
                'products.product_name',
                'products.price',
                'products.stock',
                'products.comment',
                'products.company_id',
                'companies.company_name',
            )

            // productsテーブルのidと、$idの持つidの値が一致するものを探して、
            ->where('products.id', $id)

            // その中で最初の1件を取得します
            ->first();

        // オブジェクトとして扱えるようにします
        // つまりどういうことかと言いますと、DB::table('products')~first();までを
        // 最初に作った$productという箱で持ち運びできるようにしようってわけですわ
        return $product;
    }


    /**
     * 商品を登録します
     * 
     * ProductControllerのexeStoreメソッドで、
     * create_productの引数に$insert_dataを渡しました。
     * 
     * なんと、名前を変えても引数の位置(第一引数や第二引数のこと)が同じものが使えます！
     * ProductController側で関数を呼び出す際は$insert_data(実引数)だったものを、定義側では$param(仮引数)として使っています。
     * 
     * これを値渡しと言います(READMEに記事のっけておきます！)
     * 
     * @param $param
     */
    public function createProduct($param) {
        // $paramには$insert_dataのデータが入っている(引数で渡した)ので、
        // productsテーブルのカラム(=>の左)に、引数(=>の右)の値を挿入(insert)していきます
        
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
     * ProductController側で関数を呼び出す際は$update_data(実引数)だったものを、定義側では$param(仮引数)として使っています。
     * 
     * これを値渡しと言います(READMEに記事のっけておきます！)
     * 
     * @param $param
     */
    public function updateProduct($param) {
        // productsテーブルの
        DB::table('products')

            // productsテーブルのidと、$paramの持つidの値が一致するものを探して、
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
