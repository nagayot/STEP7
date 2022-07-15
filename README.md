 お疲れ様です！
 STEP7の見本です( ͡° ͜ʖ ͡°)

 
 [こちら](https://youtu.be/cO9Kfh3lypg)の動画をベースとしています(旧STEP3の福さんの動画です)
 


 管理システム作成課題のスプレッドシートのコード規約。ちゃんと読んでいますか？  
 仕様に沿ってコーディングしましょう！(自戒:skull_and_crossbones:)  
 ただし、viewは動画で提供されているものを使っているので、BEM記法はご容赦:bowing_man:  


 その他、ここどうなってるの？等あればお気軽にどうぞ〜  


 - web.phpでルーティングを決めたら、  
 - ModelにDB周りの処理を、  
 - Contollerには、Modelで持って来た処理をViewへ渡すことを意識して、  
 - Viewでは、ちゃんとContollerからデータを受け取れているかのチェック！(変数など)  

 この流れを踏まえて、以下の①~⑦が主に触れる場所になります  

 --------------------------------

 1. /routes/web.php

 2. /app/Models(コマンドで追加)
 Modelsディレクトリ(フォルダ)は手動で追加  
 ```
 php artisan make:model Models/Product  
 php artisan make:model Models/Company
 ```

 3. /app/Http/Controllers(コマンドで追加)  
 ```
 php artisan make:controller ProductController
 ```

 4. /resources/views(手動でファイル追加)  

 5. /config/message.php(手動でファイル追加)  

 6. /public/js/alert.js(手動でファイル追加)  

 7. /app/Http/Requests(コマンドで追加)  
 ```
 php artisan make:request ProductRequest
 ```

  --------------------------------



 # 〜 STEP7でお世話になるであろう記事たち 〜

 ## ◎ Model関連
 
 - [Modelにメソッドを書く](https://laraweb.net/practice/4865/)

 - [仮引数と実引数　値渡しについて](https://www.sejuku.net/blog/23615)

 - [ファットコントローラー回避術](https://www.kamome-susume.com/laravel-fatcontroler/)


 ## ◎ Controller関連

 - [コンストラクタについて](https://laraweb.net/surrounding/1472/)

 - [try catchについて](https://qiita.com/Chelsea/items/59436cfda149a6ac6c91)

 - [$requestについて その1](https://prograshi.com/framework/laravel/request-method-injection/)
 
 - [$requestについて その2](https://nebikatsu.com/6784.html/)

 - [クエリビルダとEloquentの違いに関して](https://biz.addisteria.com/query_builder/)



 ## ◎ View関連
 
 - [Bladeファイル内のコメントアウトについて](https://buralog.jp/laravel-blade-template-file-comments/)

 - [@forelseディレクティブについて](https://qiita.com/Masahiro111/items/008a6db75e98ea17f398)

 - [BEM記法](https://qiita.com/takahirocook/items/01fd723b934e3b38cbbc)

 - [old関数について](https://www.kamome-susume.com/laravel-old/)


 ## ◎ルーティング関連

 - [Laravel6公式レファレンス ルーティング](https://readouble.com/laravel/6.x/ja/routing.html)

 - [Laravelルーティングまとめ！](https://codelikes.com/laravel-routing-summary/)


 ## ◎ その他

 ・[Laravel エラーメッセージ等の日本語化](https://into-the-program.com/laravel-message-translation-japanese/)

 ・[Laravel オブジェクト指向でよく見る単語たち](https://qiita.com/miriwo/items/974adcee45f699553cd4)

 ・[VS Codeショートカットキー(Mac版)](https://qiita.com/naru0504/items/99495c4482cd158ddca8)

 ・[VS Codeショートカットキー(Windows版)](https://qiita.com/TakahiRoyte/items/cdab6fca64da386a690b)
