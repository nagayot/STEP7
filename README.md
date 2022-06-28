 お疲れ様です！
 STEP7の見本です( ͡° ͜ʖ ͡°)

 
 こちらの動画をベースとしています(旧STEP3の福さんの動画です)
 https://youtu.be/cO9Kfh3lypg


 管理システム作成課題のスプレッドシートのコード規約。ちゃんと読んでいますか？  
 仕様に沿ってコーディングしましょう！(自戒)  
 今回。特にBladeは動画で提供されているものを使っているので、BEM記法はご容赦。  


 その他、ここどうなってるの？等あればお気軽にどうぞ〜  


 web.phpでルーティングを決めたら、  
 ModelにDB周りの処理を、  
 Contollerには、Modelで持って来た処理をViewへ渡すことを意識して、  
 Viewでは、ちゃんとContollerからデータを受け取れているかのチェック！(変数など)  

 この流れを踏まえて、以下の①~⑦が主に触れる場所になります  

 --------------------------------

 ①/routes/web.php

 ②/app/Models(コマンドで追加)  
 php artisan make:model Models/Product  
 php artisan make:model Models/Company

 ③/app/Http/Controllers(コマンドで追加)  
 php artisan make:controller ProductController

 ④/resources/views(コマンドではなく手動でファイル追加)  

 ⑤/config/message.php(コマンドではなく手動でファイル追加)  

 ⑥/public/js/alert.js(コマンドではなく手動でファイル追加)  

 ⑦/app/Http/Requests(コマンドで追加)  
 php artisan make:request ProductRequest

  --------------------------------



 〜 STEP7でお世話になるであろう記事たち 〜

 ◎ Model関連

 --------------------------------
 
 ・Model活用法
 https://zenn.dev/maotouying/articles/9627078958be65

 https://laraweb.net/practice/4865/


 --------------------------------



 ◎ Controller関連

 --------------------------------
 ・コンストラクタについて  
 https://laraweb.net/surrounding/1472/

 ・try catchについて  
 https://qiita.com/Chelsea/items/59436cfda149a6ac6c91

 ・$requestについて  
 https://prograshi.com/framework/laravel/request-method-injection/

 --------------------------------


 ◎ View関連

 --------------------------------

 ・Bladeファイル内のコメントアウトについて  
 https://buralog.jp/laravel-blade-template-file-comments/

 ・@forelseディレクティブについて  
 https://qiita.com/Masahiro111/items/008a6db75e98ea17f398

 ・BEM記法  
 https://qiita.com/takahirocook/items/01fd723b934e3b38cbbc

 --------------------------------


 ◎ ルーティング関連

 --------------------------------

 ・Laravel6公式レファレンス ルーティング  
 https://readouble.com/laravel/6.x/ja/routing.html

 ・Laravelルーティングまとめ！  
 https://codelikes.com/laravel-routing-summary/


 --------------------------------


 ◎ その他

 --------------------------------

 ・Laravel エラーメッセージ等の日本語化  
 https://into-the-program.com/laravel-message-translation-japanese/

 ・Laravel オブジェクト指向でよく見る単語たち  
 https://qiita.com/miriwo/items/974adcee45f699553cd4

 ・VS Codeショートカットキー(Mac版)  
 https://qiita.com/naru0504/items/99495c4482cd158ddca8

 ・VS Codeショートカットキー(Windows版)  
 https://qiita.com/TakahiRoyte/items/cdab6fca64da386a690b

 --------------------------------