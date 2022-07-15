<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 初回のマイグレーションでimageカラム忘れちゃった！という時は、本ファイルのようなコマンドを打つことでリカバリー可能です
// php artisan make:migration add_image_to_products_table --table=products
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
