@extends('layouts.common')
@section('title', '商品編集')
@section('lineup')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h2>商品編集</h2>
        <form method="POST" enctype="multipart/form-data" action="{{ route('product.update') }}" onSubmit="return checkUpdate()">
        @csrf
            <input type="hidden" name="id" value="{{ $product->id }}">
            <div class="form-group">
                <label for="company_id">
                    メーカー名
                </label>
                <select name="company_id">
                    @foreach($company_list as $company_data)
                        {{-- 表示自体はcompany_name(メーカー名)だけど、実際に裏で持っているのはcompanyのid --}}
                        {{-- @ifの部分で、productsテーブルにあるデータと一致するメーカー名を初期選択にしています --}}
                        <option
                            id="company_id"
                            name="company_id"
                            value="{{ $company_data->id }}"
                            @if( $company_data->id == $product->company_id )
                                selected
                            @endif
                        >
                            {{ $company_data->company_name }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('company_id'))
                    <div class="text-danger">
                        {{ $errors->first('company_id') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="product_name">
                    商品名
                </label>
                <input
                    name="product_name"
                    class="form-control"
                    value="{{ $product->product_name }}"
                    type="text"
                >
                @if ($errors->has('product_name'))
                    <div class="text-danger">
                        {{ $errors->first('product_name') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="price">
                    価格
                </label>
                <input
                    name="price"
                    class="form-control"
                    value="{{ $product->price }}"
                    type="text"
                >
                @if ($errors->has('price'))
                    <div class="text-danger">
                        {{ $errors->first('price') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="stock">
                    在庫数
                </label>
                <input
                    name="stock"
                    class="form-control"
                    value="{{ $product->stock }}"
                    type="text"
                >
                @if ($errors->has('stock'))
                    <div class="text-danger">
                        {{ $errors->first('stock') }}
                    </div>
                @endif
            </div>
            
            <div class="form-group">
                <label for="comment">
                    コメント
                </label>
                <textarea
                    name="comment"
                    class="form-control"
                    rows="4"
                >{{ $product->comment }}</textarea>
                @if ($errors->has('comment'))
                    <div class="text-danger">
                        {{ $errors->first('comment') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="image">
                    商品画像
                </label>
                @if ($product->image === null)
                    <img class="w-25" src="/storage/noimage.png">
                @else
                    <img class="w-25" src="{{ asset( '/storage'.$product->image ) }}">
                @endif
                <input type="file" name="image" class="form-control-file mt-2">
            </div>

            <div class="mt-5 mb-5">
                <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                    戻る
                </button>
                <button type="submit" class="btn btn-primary">
                    更新する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection