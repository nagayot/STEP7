@extends('layouts.common')
@section('title', '商品一覧')
@section('lineup')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>商品一覧</h2>
            <div class="form-group mt-3">
                <form  method="GET" action="{{ route('product.lineup') }}" id="searchForm" class="form-inline my-2 my-lg-0">
                    {{-- ↓キーワド検索部分↓ --}}
                    <div class="search-form">
                        <input
                            type="text"
                            class="form-control mr-sm-2"
                            name="keyword"
                            placeholder="キーワードを入力"
                            value="{{ $data['keyword'] }}"
                        >
                    </div>
                    {{-- ↑キーワド検索部分↑ --}}

                    {{-- ↓セレクトボックス検索部分↓ --}}
                    <div class="search-dropDown">
                        <select name="company_id">
                            <option selected="selected" value="">メーカーを選択してください</option>
                            @foreach($data['company_data'] as $company_info)
                                <option
                                    id="company_id"
                                    name="company_id"
                                    value="{{ $company_info->id }}"
                                    {{-- TODO:挙動おかしいけど、メーカー名選択後にキーワード入力して検索できるように --}}
                                    @if ( !empty($data['request']->company_id) || (old('company_id') == $company_info->id))
                                        selected
                                    @endif
                                >{{ $company_info->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- ↑セレクトボックス検索部分↑ --}}

                    {{-- ↓検索ボタン部分↓ --}}
                    <div class="search-btn ml-2">
                        <button class="btn btn-secondary" type="submit">商品を探す</button>
                    </div>
                    {{-- ↑検索ボタン部分↑ --}}
                </form>
            </div>

            {{-- ↓なんらかのメッセージを表示する部分↓ --}}
            @if (session('err_msg'))
                <p class="text-danger">{{ session('err_msg') }}</p>
            @endif
            {{-- ↑なんらかのメッセージを表示する部分↑ --}}

            {{-- ↓一覧データ表示部分↓ --}}
            <table class="table table-striped">
                <tr>
                    <th>商品ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th></th>
                    <th></th>
                </tr>
                {{-- 登録されている商品があるかの分岐 初期状態も含む --}}
                @forelse($data['product_list'] as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            {{-- imageは入力必須でないので、画像投稿しなかった場合のことも考えてあげる --}}
                            @if ($product->image === null)
                                {{-- noimage.pngという名前の適当な画像を用意して、storageディレクトリ内に置いておく --}}
                                <img class="w-25 h-25" src="/storage/noimage.png">
                            @else
                                {{-- 画像投稿があった場合は、投降した画像を表示する --}}
                                <img class="w-25 h-25" src="{{ asset( '/storage'.$product->image ) }}">
                            @endif
                        </td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->price }}円</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->company_name }}</td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-info"
                                onclick="location.href='/product/{{ $product->id }}'"
                            >詳細</button>
                        </td>
                        <form method="POST" action="{{ route('product.delete', $product->id) }}" onSubmit="return checkDelete()">
                            @csrf
                            <td>
                                <button type="submit" class="btn btn-danger" onclick="">削除</button>
                            </td>
                        </form>
                    </tr>
                @empty
                    {{-- '登録されている商品がまだありません。'というメッセージを表示します --}}
                    <p class="text-danger">{{ config('message.message5') }}</p>
                @endforelse
            </table>
            {{-- ↑一覧データ表示部分↑ --}}

            {{-- ↓ページネーション機能↓ --}}
            {{-- この書き方をすると、ページを変えても検索条件が保持されたままになります --}}
            <div class = "paginate mt-5 mb-5 d-flex justify-content-center">
                {{ $data['product_list']->appends(request()->input())->links() }}
            </div>
            {{-- ↑ページネーション機能↑ --}}

        </div>
    </div>
</div>
@endsection
