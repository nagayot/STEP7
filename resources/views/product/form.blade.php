@extends('layouts.common')
@section('title', '商品登録')
@section('lineup')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h2>商品登録</h2>
        
        {{-- formタグで囲ってあげた部分を、ProductControllerのstoreメソッドに渡します --}}
        {{-- onSubmitは、データを送信した際の挙動を指定できるので、今回はpublic/js/alert.jsの中のcheckSubmitを呼んであげます --}}
        {{-- どこでalert.js読み込んでんねーーんと思うかもですが、このblade自体が'resources/layouts/common'を継承しています(一番上の@extendsで) --}}
        {{-- common.blade.phpで読み込んであげると、継承している他のbladeでも使用可能になります！ --}}
        
        <form method="POST" enctype="multipart/form-data" action="{{ route('product.store') }}" onSubmit="return checkSubmit()">
            {{-- method="POST"時は、@csrfをお忘れなく。セキュリティ対策です！ --}}
            @csrf
            <div class="form-group">

                <label for="company_id">
                    メーカー名
                </label>

                <select name="company_id">
                    <option selected="selected" value="">メーカーを選択してください</option>
                    
                    {{-- ProductControllerのshowCreateメソッドで、viewに$selectItems渡してましたね！ --}}
                    @foreach($selectItems as $selectItem)
                        {{-- 裏で持つデータはcompanyのidですが、実際に表示するのはcompany_name --}}
                        <option id="company_id" name="company_id" value="{{ $selectItem->id }}">{{ $selectItem->company_name }}</option>
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
                    value="{{ old('product_name') }}"
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
                    value="{{ old('price') }}"
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
                    value="{{ old('stock') }}"
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
                >{{ old('comment') }}</textarea>
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
                <input type="file" name="image" class="form-control-file">
            </div>

            <div class="mt-5 mb-5">
                {{-- ここの戻るは一覧画面へ遷移した方がよさげだったので、他の戻るボタンとonclickが違います --}}
                <button type="button" class="btn btn-outline-secondary" onclick="location.href='{{ route('product.lineup') }}'">
                    戻る
                </button>
                <button type="submit" class="btn btn-primary">
                    登録する
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
