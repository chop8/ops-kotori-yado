@extends('layouts.base')
@section('title', $date . ' の予定')
@section('styles')
    <link href="{{ asset('css/reception/reception.css') }}" rel="stylesheet">
    <link href="{{ asset('css/reception/calendar.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('reception.index') }}">予約表</a></li>
                <li class="breadcrumb-item"><a href="{{ route('reception.index', ['m' => $month]) }}">{{ \Illuminate\Support\Carbon::parse($date)->format('Y年n月') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Carbon::parse($date)->format('d日（D）') }}</li>
            </ol>
        </nav>

        <h4>{{ $date }} の予定</h4>
        <div class="responsive-area">
            <table class="table table-bordered table-schedule">
                <thead>
                    <tr>
                        <th class="td-time">時間</th>
                        @foreach ($resources as $name => $class)
                            <th class="{{ $class }}">{{ $name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($timeSlots as $time)
                        <tr>
                            <th>{{ $time }}</th>
                            @foreach ($resources as $class)
                                <td class="{{ $class }}"><div></div></td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('reception.index', ['m' => $month]) }}" class="btn btn-secondary mt-3">カレンダーに戻る</a>
    </div>

    <div class="mode-change">
        @if(Cookie::get('is_login'))
            <form name="login" method="post" action="{{ url('/auth/lock') }}?p={{ $date }}" class="form-login">
                @csrf
                <input type="hidden" name="return_url" value="{{ url()->current() }}">
                <button type="submit" class="btn btn-sm btn-primary">ログアウト</button>
            </form>
        @else
            <form name="login" method="post" action="{{ url('/auth/unlock') }}?p={{ $date }}" class="form-login">
                @csrf
                <input type="hidden" name="return_url" value="{{ url()->current() }}">
                <input type="password" class="form-control" name="pass" id="pass" placeholder="パスワード" style="width: 100px;">
                <button type="submit" class="btn btn-sm btn-primary">編集</button>
            </form>
        @endif
    </div>
@endsection
