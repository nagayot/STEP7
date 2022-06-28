@extends('layouts.common')
@section('title', '商品詳細')
@section('lineup')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <span>◎商品情報ID：{{ $product->id }}</span>
            <br>
            <span>◎商品画像　： 
                @if ($product->image === null)
                    {{-- noimage.pngという適当な画像をstorage内に置きました --}}
                    {{-- 画像はrequired(入力必須)ではないので、投稿されないパターン対策です --}}
                    <img class="w-25" src="/storage/noimage.png">
                @else
                    {{-- 画像が登録されている場合はこちら --}}
                    <img class="w-25" src="{{ asset( '/storage'.$product->image ) }}">
                @endif
            </span>
            <br>
            <span>◎商品名　　：{{ $product->product_name }}</span>
            <br>
            <span>◎メーカー　：{{ $product->company_name }}</span>
            <br>
            <span>◎価格　　　：{{ $product->price }}円</span>
            <br>
            <span>◎在庫数　　：{{ $product->stock }}</span>
            <br>
            <span>◎コメント　：{{ $product->comment }}</span>
        </div>
    </div>
    <div class="mt-5">
        <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
            戻る
        </button>
        <button type="button" class="btn btn-primary" onclick="location.href='/product/edit/{{ $product->id }}'">
            編集
        </button>
    </div>
</div>
@endsection