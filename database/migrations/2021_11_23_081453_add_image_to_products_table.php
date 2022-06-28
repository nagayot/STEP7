<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 初回のマイグレーションでimageカラム忘れちゃった！という時は本ファイルのようなコマンドを打つことでリカバリー可能です
class AddImageToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // productsテーブルにimageカラムの追加
            // imageは入力必須ではないので、null許可をすること
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // 巻き戻すときはimageカラムのみドロップ
            $table->dropColumn('image');
        });
    }
}
