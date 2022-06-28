<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// クエリビルダで書くのに必要なuse
// Laravelのベストプラクティス的にはEloquentORMがオススメとされています
// それでもクエリビルダを用いる理由は、以下の２つ
// ① EloquentORMより処理速度が速い
// ② SQLの勉強にもなる
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    // DB連携
    protected $table = 'companies';

    // 可変項目
    protected $fillable = [
        'company_name',
        'street_address',
    ];

    /**
     * リレーション組み
     * NOTE：会社は複数の商品を持つ
     *
     * @return void
     */
    public function products() {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * companyデータ取得
     * 
     * @return  $company
     */
    public function companyInfo() {
        // companiesテーブルの中から、'id'カラムと'company_name'カラムをselect文で取得
        $company = DB::table('companies')
            ->select(
                'id',
                'company_name',
            )
            ->orderBy('id', 'asc')
            ->get();
        
        return $company;
    }
}
