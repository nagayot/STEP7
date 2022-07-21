<?php

// namespaceは住所みたいなもの(ここではProductControllerの住所)
namespace App\Http\Controllers;

// Product.phpと連携
use App\Models\Product;

// Company.phpと連携
use App\Models\Company;

// Requestの受け取り、受け渡しに必要
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;

// いらないuse(なくてもちゃんと動くuse)は消すこと！

// デフォルトで備わっているControllerクラスを継承した、ProductControllerクラスです
class ProductController extends Controller
{
    /**
     * コンストラクタです。
     * 
     * コンストラクタ内に書いた処理が、他の関数(このController内でいうと、showLineupやshowDetail等)の処理を行う前に、実行されます。
     * 実は、ユーザー認証周りの機能をコマンドで作成した時に自動生成される、/app/Controllers/Authの中のControllerでも使われてました
     * READMEに参考記事のせてあります！
     * 
     */
    public function __construct() {
        // ログインしていることを前提としてくれます(app/Http/Middleware/Authenticate.phpを利用する宣言です)
        // 'auth'という名前はapp/Http/Kernel.phpの$routeMiddlewareで決めています
        $this->middleware('auth');
        
        // newすることで、モデルクラスのインスタンスが使用可能になります
        // 設計図(クラス)とそこから作ったもの(インスタンス)...
        // 理解できるまでは壁がありますが、乗り越えたらなんで詰まってたんだ？くらいの気持ちになれます
        
        $this->product = new Product();
        $this->company = new Company();
    }


    /**
     * DBへ渡すデータを生成するメソッドです
     * 
     * 登録と更新で同じデータを渡します。
     * 2回に分けて書くのではなく、渡すデータ部分のみを切り取り、このメソッドを呼び出します。
     *
     * @param ProductRequest $request
     * @return $result
     */
    public function dataToDataBase(ProductRequest $request) {

        // 箱  : $imageいう名前の変数(function同様に、中身が分かるものがよい)
        // 中身: $request内のimageをfileメソッドで取得
        // 画像関係の実装をする際は、シンボリックリンクを貼ることをお忘れなく!
        $image = $request->file('image');

        // もし画像が登録されていたら(空でなければ)
        if (!empty($image)) {
            // 箱  : $image_pathという名前の変数(function同様に、中身が分かるものがよい)
            // 中身: getPathname()で画像のパスを取得します。
            $image_path = $image->getPathname();

            // if文開始前で宣言した$imageの中身の続きで、
            // $request->file('image')->storeAs('', $image_path, 'public');
            // と同じ意味になります！
            // $image_pathで取得した名前で、publicディレクトリに画像を保存する処理です
            $image->storeAs('', $image_path, 'public');
        }

        // 箱  : $resultという名前の変数(function同様に、中身が分かるものがよい)
        // 中身: 空の配列
        // 結果取得用で空の配列を作っておき、欲しいデータを突込んでいきます。
        $result = [];
        $result['id'] = $request->input('id');
        $result['company_id'] = $request->input('company_id');
        $result['product_name'] = $request->input('product_name');
        $result['price'] = $request->input('price');
        $result['stock'] = $request->input('stock');
        $result['comment'] = $request->input('comment');
        $result['image'] = $image;

        // オブジェクトとして扱えるようにします
        // dataToDataBaseが呼ばれたら、$resultを渡します！
        return $result;
    }


    /**
     * 商品一覧画面を表示
     * 
     * functionの名前は自由です。が、機能が分かる名前にしましょう！
     * 今回はコード規約に沿ってロワーキャメルとします
     * 
     * このコメントの部分はPHPDoc(ぴーえいちぴーどっく)と言います。
     * 現場でも使うので、どういう機能なのかを書く習慣をつけましょう！
     * 「/**」と打ってEnterキー押すと、自動で作ってくれます。
     * 自分で見返すときはもちろん、いつか来る改修案件の時、すごく助かります。
     * 
     * Requestクラス(useしていますね！)で受け取ったデータを$requestとして使います
     * 
     * ↓の@paramには引数を、@returnには返り値を書きます
     * 
     * @param Request $request    リクエスト($request)は、ブラウザからユーザーが送る情報のことです(例:ログイン時のメールアドレス＆パスワード)
     * @return view
     */
    public function showLineup(Request $request) {
        // 箱  ： $keywordという名前の変数(function同様に、中身が分かるものがよい)
        // 中身： 検索窓(lineup.blade.phpのname属性がkeywordのinputタグ)に入力された文字を取得します
        $keyword = $request->input('keyword');

        // 箱  ： $selected_nameという名前の変数(function同様に、中身が分かるものがよい)
        // 中身： セレクトボックスで選択されたメーカー名に紐づくcompany_idを取得します
        $selected_name = $request->input('company_id');

        // try catchを入れることで、正常な処理の時はtryを。エラーがあった際のみcatchに書いた内容が実行されます
        try {
            // 箱  ： $productListという名前の変数(function同様に、中身が分かるものがよい)
            // 中身： Product.phpのproductListにアクセス
            $product_list = $this->product->productList();

            // 箱  ： $company_dataという名前の変数(function同様に、中身が分かるものがよい)
            // 中身： Company.phpのcompanyInfoにアクセス
            $company_data = $this->company->companyInfo();

            // || と && についてはググりましょう！
            // キーワード入力がある場合か、セレクトボックスでメーカー名が選択されている場合か、両方の値がある場合
            if ( (!empty($keyword)) || (!empty($selected_name)) || (!empty($keyword) && !empty($selected_name)) ) {
                // 箱  ： $productListという名前の変数(function同様に、中身が分かるものがよい)
                // 中身： Product.phpのsearchProductByParamsにアクセス
                $product_list = $this->product->searchProductByParams($keyword, $selected_name);
            }

        } catch (\Throwable $e) {
            // 何らかのエラーが起きた際は、こちらの処理を実行

            // エラーメッセージだけだと、ユーザーが困ってしまうので本来は、エラーページを返します
            // 今回はページ作るの面倒だったので、エラーメッセージを返します
            throw new \Exception($e->getMessage());
        }

        // 〜 余談 〜
        // compact()に渡すものが多いなと思ったら、まとめ方があります
        // view(今回だと'/resources/views/product/lineup.blade.php')でのデータの表示のさせ方(書き方)も変わってきます
        $data = [
            'product_list' => $product_list,
            'company_data' => $company_data,
            'keyword'      => $keyword,
            'request'      => $request,
        ];
        // $dataで渡すものをまとめなかった場合↓
        // return view('product.lineup', compact('product_list', 'company_data', 'keyword', 'request'));

        // '/resources/views/product/lineup.blade.php'に渡したい変数(showLineupで定義したもの)を、compact()関数を用いて渡す。
        // このとき変数に$は付けない
        return view('product.lineup', compact('data'));
    }

    /**
     * 商品情報詳細画面を表示
     *  
     * functionの名前は自由です。が、機能が分かる名前にしましょう！
     * 今回はコード規約に沿ってロワーキャメルとします
     * 
     * このコメントの部分はPHPDoc(ぴーえいちぴーどっく)と言います。
     * 現場でも使うので、どういう機能なのかを書く習慣をつけましょう！
     * 「/**」と打ってEnterキー押すと、自動で作ってくれます。
     * 自分で見返すときはもちろん、いつか来る改修案件の時、すごく助かります。
     * 
     * 引数に指定している$idは変数として定義されてないのになんで使えるの？と思ったそこのあなた。
     * これはルートパラメータと言います。分からなければ、ググりましょう！
     * 
     * ↓の@paramには引数、@returnには返り値を書きます。
     * 
     * @param $id
     * @return view
     */
    public function showDetail($id) {
        // try catchを入れることで、正常な処理の時はtryを。エラーがあった際のみcatchに書いた内容が実行されます
        try {
            // 箱  ： $product_listという名前の変数(function同様に、中身が分かるものがよい)
            // 中身： Product.phpのproductDetailにアクセス
            // 選択した商品のidを持つ情報のみ表示したいので、引数に$id(ルートパラメータ)を渡します
            $product = $this->product->productDetail($id);

            // もし、該当商品がない場合
            if (is_null($product)) {
                // configフォルダのmessageファイル内にある、message1を取得
                // '該当商品がありません'というエラーメッセージを表示
                \Session::flash('err_msg', config('message.message1'));

                // 一覧画面へリダイレクトさせる
                return redirect(route('product.lineup'));
            }

        } catch (\Throwable $e) {
            // 何らかのエラーが起きた際は、こちらの処理を実行

            // 現場では、自作のエラーページを返します
            // 今回はページ作るの面倒だったので、エラーメッセージを返します
            throw new \Exception($e->getMessage());
        }

        // '/resources/views/product/detail.blade.php'に渡したい変数(showDetailで定義したもの)を、compact()関数を用いて渡す。
        // このとき変数に$は付けない
        return view('product.detail', compact('product'));
    }

    /**
     * 商品情報登録画面を表示
     * 
     * functionの名前は自由です。が、機能が分かる名前にしましょう！
     * 今回はコード規約に沿ってロワーキャメルとします
     * 
     * このコメントの部分はPHPDoc(ぴーえいちぴーどっく)と言います。
     * 現場でも使うので、どういう機能なのかを書く習慣をつけましょう！
     * 「/**」と打ってEnterキー押すと、自動で作ってくれます。
     * 自分で見返すときはもちろん、いつか来る改修案件の時、すごく助かります。
     * 
     * ↓の@returnには返り値を書きます。
     * 
     * @return view
     */
    public function showCreate() {
        // try catchを入れることで、正常な処理の時はtryを。エラーがあった際のみcatchに書いた内容が実行されます
        try {
            // 箱  ： $selectItemsという名前の変数(function同様に、中身が分かるものがよい)
            // 中身： Company.phpのcompanyInfoにアクセス
            $selectItems = $this->company->companyInfo();

        } catch (\Throwable $e) {
            // 何らかのエラーが起きた際は、こちらの処理を実行

            // 現場では、自作のエラーページを返します
            // 今回はページ作るの面倒だったので、エラーメッセージを返します
            throw new \Exception($e->getMessage());
        }

        // '/resources/views/product/form.blade.php'に渡したい変数(showCreateで定義したもの)を、compact()関数を用いて渡す。
        // このとき変数に$は付けない
        return view('product.form', compact('selectItems'));
    }

    /**
     * 商品情報の登録
     * 
     * functionの名前は自由です。が、機能が分かる名前にしましょう！
     * 今回はコード規約に沿ってロワーキャメルとします
     * 
     * このコメントの部分はPHPDoc(ぴーえいちぴーどっく)と言います。
     * 現場でも使うので、どういう機能なのかを書く習慣をつけましょう！
     * 「/**」と打ってEnterキー押すと、自動で作ってくれます。
     * 自分で見返すときはもちろん、いつか来る改修案件の時、すごく助かります。
     * 
     * ProductRequestクラス(useしていますね！)で受け取ったデータを$requestとして使います
     * ↓の@paramには引数を書きます。
     *
     * @param ProductRequest $request
     */
    public function exeStore(ProductRequest $request) {

        // 箱  ：$insert_dataという名前の変数(function同様に、中身が分かるものがよい)
        // 中身：$thisでクラス(ProductControllerの)を指します。そのクラス内にある、dataToDataBaseメソッドを呼び出します。
        $insert_data = $this->dataToDataBase($request);

        // トランザクションの開始
        // DB内容を変更する際は、トランザクションを使いましょう！
        // なんらかのエラーが起きた時、一部のデータは成功で更新、一部のデータは失敗でそのままということを防いでくれます。
        \DB::beginTransaction();

        // try catchを入れることで、正常な処理の時はtryを。エラーがあった際のみcatchに書いた内容が実行されます
        try {
            // Product.phpのcreateProductにアクセス
            // 欲しいデータを揃えた$insert_dataを使いたいので、引数として渡します。
            $this->product->createProduct($insert_data);

            // DBへの変更内容を確定します
            \DB::commit();

        } catch (\Throwable $e) {
            // 何らかのエラーが起きた際は、こちらの処理を実行

            // DBへの変更内容を無かったことにします
            \DB::rollback();

            // 現場では、自作のエラーページを返します
            // 今回はページ作るの面倒だったので、エラーメッセージを返します
            throw new \Exception($e->getMessage());
        }

        // configフォルダのmessageファイル内にある、message2を取得
        // '商品を登録しました！'というメッセージを表示
        \Session::flash('err_msg', config('message.message2'));

        // '/resources/views/product/lineup.blade.php'にリダイレクトします。
        // route()の中身を変えることで、遷移先を指定できます。
        return redirect(route('product.lineup'));
    }


    /**
     * 商品情報編集画面を表示
     * 
     * functionの名前は自由です。が、機能が分かる名前にしましょう！
     * 今回はコード規約に沿ってロワーキャメルとします
     * 
     * このコメントの部分はPHPDoc(ぴーえいちぴーどっく)と言います。
     * 現場でも使うので、どういう機能なのかを書く習慣をつけましょう！
     * 「/**」と打ってEnterキー押すと、自動で作ってくれます。
     * 自分で見返すときはもちろん、いつか来る改修案件の時、すごく助かります。
     * 
     * 引数に指定している$idは変数として定義されてないのになんで使えるの？と思ったそこのあなた。
     * これはルートパラメータと言います。分からなければ、ググりましょう！
     * 
     * ↓の@paramには引数、@returnには返り値を書きます。
     * 
     * 
     * @param $id
     * @return view
     */
    public function showEdit($id) {
        // try catchを入れることで、正常な処理の時はtryを。エラーがあった際のみcatchに書いた内容が実行されます
        try {
            // 箱  ： $product_listという名前の変数(function同様に、中身が分かるものがよい)
            // 中身： Product.phpのproductDetailにアクセス
            // 選択した商品のidを持つ情報のみ表示したいので、引数に$id(ルートパラメータ)を渡します
            $product = $this->product->productDetail($id);
            
            // 箱  ： $company_dataという名前の変数(function同様に、中身が分かるものがよい)
            // 中身： Company.phpのcompanyInfoにアクセス
            $company_list = $this->company->companyInfo();

            // 該当idを持つ商品が見つからなかった場合
            if (is_null($product)) {
                // configフォルダのmessageファイル内にある、message1を取得
                // '該当する商品がありません'というエラーメッセージを表示
                \Session::flash('err_msg', config('message.message1'));

                // '/resources/views/product/lineup.blade.php'にリダイレクトします。
                // route()の中身を変えることで、遷移先を指定できます。
                return redirect(route('product.lineup'));
            }

        } catch (\Throwable $e) {
            // 何らかのエラーが起きた際は、こちらの処理を実行
            // 現場では、自作のエラーページを返したりします
            // 今回はページ作るの面倒だったので、エラーメッセージを返します
            throw new \Exception($e->getMessage());
        }

        // '/resources/views/product/edit.blade.php'に渡したい変数(showEditで定義したもの)を、compact()関数を用いて渡す。
        // このとき変数に$は付けない
        return view('product.edit', compact('product', 'company_list'));
    }

    /**
     * 商品情報の更新
     *
     * functionの名前は自由です。が、機能が分かる名前にしましょう！
     * 今回はコード規約に沿ってロワーキャメルとします
     * 
     * このコメントの部分はPHPDoc(ぴーえいちぴーどっく)と言います。
     * 現場でも使うので、どういう機能なのかを書く習慣をつけましょう！
     * 「/**」と打ってEnterキー押すと、自動で作ってくれます。
     * 自分で見返すときはもちろん、いつか来る改修案件の時、すごく助かります。
     * 
     * ProductRequestクラス(useしていますね！)で受け取ったデータを$requestとして使います
     * ↓の@paramには引数を書きます。
     * 
     * @param ProductRequest $request
     */
    public function exeUpdate(ProductRequest $request) {

        // 箱  ：$update_dataという名前の変数(function同様に、中身が分かるものがよい)
        // 中身：$thisでクラス(ProductControllerの)を指します。そのクラス内にある、dataToDataBaseメソッドを呼び出します。
        $update_data = $this->dataToDataBase($request);

        // トランザクションの開始
        // DB内容を変更する際は、トランザクションを使いましょう！
        // なんらかのエラーが起きた時、一部のデータは成功で更新、一部のデータは失敗でそのままということを防いでくれます。
        \DB::beginTransaction();

        // try catchを入れることで、正常な処理の時はtryを。エラーがあった際のみcatchに書いた内容が実行されます
        try {
            // Product.phpのupdateProductにアクセス
            // 欲しいデータを揃えた$update_dataを使いたいので、引数として渡します。
            $this->product->updateProduct($update_data);

            // DBへの変更内容を確定します
            \DB::commit();

        } catch (\Throwable $e) {
            // 何らかのエラーが起きた際は、こちらの処理を実行
            // DBへの変更内容を無かったことにします
            \DB::rollback();

            // 現場では、自作のエラーページを返します
            // 今回はページ作るの面倒だったので、エラーメッセージを返します
            throw new \Exception($e->getMessage());
        }
        // configフォルダのmessageファイル内にある、message3を取得
        // '商品情報を更新しました！'というメッセージを表示
        \Session::flash('err_msg', config('message.message3'));

        // '/resources/views/product/lineup.blade.php'にリダイレクトします。
        // route()の中身を変えることで、遷移先を指定できます。
        return redirect(route('product.lineup'));
    }

    /**
     * 商品情報を削除
     * 
     * functionの名前は自由です。が、機能が分かる名前にしましょう！
     * 今回はコード規約に沿ってロワーキャメルとします
     * 
     * このコメントの部分はPHPDoc(ぴーえいちぴーどっく)と言います。
     * 現場でも使うので、どういう機能なのかを書く習慣をつけましょう！
     * 「/**」と打ってEnterキー押すと、自動で作ってくれます。
     * 自分で見返すときはもちろん、いつか来る改修案件の時、すごく助かります。
     * 
     * 引数に指定している$idは変数として定義されてないのになんで使えるの？と思ったそこのあなた。
     * これはルートパラメータと言います。分からなければ、ググりましょう！
     * ↓の@paramには引数を書きます
     * 
     * @param $id
     */
    public function exeDelete($id) {
        // 削除対象の商品idがない場合
        if (empty($id)) {
            // configフォルダのmessageファイル内にある、message1を取得
            // '該当する商品がありません'というエラーメッセージを表示
            \Session::flash('err_msg', config('message.message1'));

            // '/resources/views/product/lineup.blade.php'にリダイレクトします。
            // route()の中身を変えることで、遷移先を指定できます。
            return redirect(route('product.lineup'));
        }

        // try catchを入れることで、正常な処理の時はtryを。エラーがあった際のみcatchに書いた内容が実行されます
        try {
            // Product.phpのdeleteProductにアクセス
            // 選択したidの商品を削除したいので、引数に$id(ルートパラメータ)を渡します。
            $this->product->deleteProduct($id);

        } catch (\Throwable $e) {
            // 何らかのエラーが起きた際は、こちらの処理を実行
            // 現場では、自作のエラーページを返したりします
            // 今回はページ作るの面倒だったので、エラーメッセージを返します
            throw new \Exception($e->getMessage());
        }

        // configフォルダのmessageファイル内にある、message4を取得
        // '商品を削除しました'というメッセージを表示
        \Session::flash('err_msg', config('message.message4'));

        // '/resources/views/product/lineup.blade.php'にリダイレクトします。
        // route()の中身を変えることで、遷移先を指定できます。
        return redirect(route('product.lineup'));
    }
}
