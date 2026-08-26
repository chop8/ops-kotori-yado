@extends('layouts.base')
@section('title', $date . ' の予定')
@section('styles')
    <link href="{{ asset('css/reception/schedule.css') }}" rel="stylesheet">
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
        <form name="login" method="post" action="https://kotori-yado.com/scheduler/auth/unlock/?p=2026-08-21" class="form-login">
            <input type="password" class="form-control" name="pass" id="pass" placeholder="パスワード" value="pass123" style="width: 100px;">
            <button type="submit" class="btn btn-sm btn-primary">編集</button>
        </form>
    </div>
@endsection
