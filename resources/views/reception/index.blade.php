@extends('layouts.base')
@section('title', '予約表')
@section('styles')
    <link href="{{ asset('css/reception/schedule.css') }}" rel="stylesheet">
    <link href="{{ asset('css/reception/calendar.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-4">
        <div class="rowframe01">
            <p id="info">
                編集を行うには、画面右下のパスワード欄に入力のうえ<span style="color:#FFFFFF;"><span style="background-color:#3366ff;"> 編集 </span></span>ボタンを押してください。<br />
                各項目に表示の編集アイコン（鉛筆マーク）を押すことで入力フォームが表示されます。
            </p>
            <i class="fa fa-lg fa-pencil-square-o edit-btn" data-type="info"></i>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="grid">
                    <div class="grid-header">
                        <span class="grid-title">{{ $month->format('Y年n月') }}</span>
                        <a href="{{ $nextMonthUrl }}"><button type="button" class="btn btn-xs page-link text-dark d-inline-block btn-radius pull-right">翌月 <i class="fa fa-chevron-right"></i></button></a>
                        @if (!$isCurrentMonth)
                            <a href="{{ $currentMonthUrl }}"><button type="button" class="btn btn-xs page-link text-dark d-inline-block btn-radius pull-right mr-2">今月</button></a>
                        @endif
                        <a href="{{ $previousMonthUrl }}"><button type="button" class="btn btn-xs page-link text-dark d-inline-block btn-radius pull-right mr-2"><i class="fa fa-chevron-left"></i> 先月</button></a>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <p class="lead">保護施設「とりの駅」</p>
                <div class="responsive-area">
                    <table class="table table-bordered table-calendar">
                        <thead>
                        <tr>
                            <th class="bg-sun">日</th>
                            <th>月</th>
                            <th>火</th>
                            <th>水</th>
                            <th>木</th>
                            <th>金</th>
                            <th class="bg-sat">土</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($calendarWeeks as $week)
                            <tr>
                                @foreach ($week as $day)
                                    @php
                                        $date = $day['date'];
                                        $isSunday = $date->dayOfWeek === \Illuminate\Support\Carbon::SUNDAY;
                                        $isSaturday = $date->dayOfWeek === \Illuminate\Support\Carbon::SATURDAY;
                                        $cellClass = $isSunday ? 'bg-sun2' : ($isSaturday ? 'bg-sat2' : '');
                                    @endphp
                                    <td class="{{ $cellClass }}">
                                        <div class="{{ $day['isCurrentMonth'] ? '' : 'bg-inactive' }}">
                                            <div class="day {{ $day['isCurrentMonth'] ? '' : 'day-inactive' }}">{{ $date->day }}</div>
                                            <span id="schedule_{{ $date->format('Y-n-j') }}"></span>
                                            @if ($day['isCurrentMonth'])
                                                <i class="fa fa-lg fa-pencil-square-o edit-btn" data-type="schedule::{{ $date->format('Y-n-j') }}"></i>
                                            @endif
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mode-change">
        <form name="login" method="post" action="https://kotori-yado.com/scheduler/auth/unlock/?p=2026-08-21" class="form-login">
            <input type="password" class="form-control" name="pass" id="pass" placeholder="パスワード" value="pass123" style="width: 100px;">
            <button type="submit" class="btn btn-sm btn-primary">編集</button>
        </form>
    </div>
@endsection

@section('javascript')
@endsection
