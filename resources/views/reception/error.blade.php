@extends('layouts.base')
@section('title', 'エラー')
@section('styles')
    <link href="{{ asset('css/reception/reception.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section id="error" class="page mt-5">
        <div class="container">
            <div class="content cover text-center">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Error</h1>
                        <h3>パスワードが一致しませんでした</h3>
                        <br />
                        <p>
                        パスワードを確認のうえ再度送信を行ってください。<br />
                        <a href="{{ url('/') }}">トップページに戻る</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
