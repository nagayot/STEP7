<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->bigIncrements('id');
                // 外部キーにする
                $table->unsignedBigInteger('company_id');
                $table->string('product_name', 100);
                $table->integer('price');
                $table->integer('stock');
                // 入力必須でないものはnull許可をすること
                $table->text('comment')->nullable();
                $table->timestamps();
                // 外部キー制約
                $table->foreign('company_id')->refereneces('id')->on('companies')->onDelete('cascade');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
