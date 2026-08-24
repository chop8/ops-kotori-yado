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
}
