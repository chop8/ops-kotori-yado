@extends('layouts.base')
@section('title', $date . ' の予定')
@section('styles')
    <link href="{{ asset('css/reception/schedule.css') }}" rel="stylesheet">
    <link href="{{ asset('css/reception/calendar.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-4">
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
        <a href="{{ route('reception.index') }}" class="btn btn-secondary mt-3">カレンダーに戻る</a>
    </div>
@endsection
