<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReceptionController extends Controller
{
    public function index(Request $request): View
    {
        $timezone = config('app.timezone', 'Asia/Tokyo');
        $month = Carbon::now($timezone)->startOfMonth();
        $requestedMonth = $request->query('m');

        if (is_string($requestedMonth) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $requestedMonth)) {
            try {
                $candidate = Carbon::createFromFormat('!Y-m', $requestedMonth, $timezone);

                if ($candidate !== false && $candidate->format('Y-m') === $requestedMonth) {
                    $month = $candidate->startOfMonth();
                }
            } catch (\Throwable) {
                // 不正な月指定は現在月にフォールバックする。
            }
        }

        $currentMonth = Carbon::now($timezone)->startOfMonth();

        $calendarStart = $month->copy()->startOfWeek(Carbon::SUNDAY);
        $calendarEnd = $month->copy()
            ->endOfMonth()
            ->endOfWeek(Carbon::SATURDAY);
        $calendarWeeks = [];
        $week = [];

        for ($date = $calendarStart->copy(); $date <= $calendarEnd; $date->addDay()) {
            $week[] = [
                'date' => $date->copy(),
                'isCurrentMonth' => $date->month === $month->month,
            ];

            if (count($week) === 7) {
                $calendarWeeks[] = $week;
                $week = [];
            }
        }

        return view('reception.index', [
            'month' => $month,
            'isCurrentMonth' => $month->format('Y-m') === $currentMonth->format('Y-m'),
            'calendarWeeks' => $calendarWeeks,
            'previousMonthUrl' => route('reception.index', ['m' => $month->copy()->subMonth()->format('Y-m')]),
            'currentMonthUrl' => route('reception.index'),
            'nextMonthUrl' => route('reception.index', ['m' => $month->copy()->addMonth()->format('Y-m')]),
        ]);
    }

    public function schedule(string $date): View
    {
        $targetDate = Carbon::parse($date);
        $month = $targetDate->format('Y-m');

        $start = "09:00";// 開始時刻
        $end = "18:00";// 最終時刻
        $excluded = ["12:00", "12:30"];// 除去する時間帯

        $startTime = Carbon::createFromFormat('H:i', $start);
        $endTime = Carbon::createFromFormat('H:i', $end);

        $timeSlots = [];
        $currentTime = $startTime->copy();

        while ($currentTime <= $endTime) {
            $timeString = $currentTime->format('H:i');
            if (!in_array($timeString, $excluded)) {
                $timeSlots[] = $timeString;
            }
            $currentTime->addMinutes(30);
        }

        $resources = [
            'IN/OUT' => 'td-inout',
            '名前' => 'td-name',
            'ケージ数' => 'td-cage',
            '利用' => 'td-use',
            '区分' => 'td-kubun',
            'お迎え日時' => 'td-pickup',
            '詳細・金額' => 'td-memo',
        ];

        return view('reception.schedule', [
            'date' => $date,
            'month' => $month,
            'timeSlots' => $timeSlots,
            'resources' => $resources,
        ]);
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|string',
            'time_slot' => 'required|string',
            'in_out' => 'nullable|string',
            'name' => 'nullable|string',
            'cage_count' => 'nullable|numeric',
            'type' => 'nullable|string',
            'category' => 'nullable|string',
            'pickup_at' => 'nullable|string',
            'memo' => 'nullable|string',
        ]);

        $reception = \App\Models\Reception::where('date', $data['date'])
            ->where('time_slot', $data['time_slot'])
            ->first();

        $isEmpty = empty($data['in_out']) && empty($data['name']) && empty($data['cage_count']) &&
                   empty($data['type']) && empty($data['category']) && empty($data['pickup_at']) && empty($data['memo']);

        if ($isEmpty) {
            if ($reception) {
                $reception->delete();
            }
        } else {
            if ($reception) {
                $reception->update($data);
            } else {
                \App\Models\Reception::create($data);
            }
        }

        return response()->json(['success' => true]);
    }

    public function getData(Request $request)
    {
        $reception = \App\Models\Reception::where('date', $request->query('date'))
            ->where('time_slot', $request->query('time_slot'))
            ->first();

        return response()->json($reception);
    }
}
