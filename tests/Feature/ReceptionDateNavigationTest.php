<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReceptionDateNavigationTest extends TestCase
{
    /**
     * カレンダーの日付クリックで該当日の予定ページに遷移できることを確認
     */
    public function test_can_navigate_to_date_page(): void
    {
        $date = '2026-08-26';
        $response = $this->get(route('reception.show', ['date' => $date]));

        $response->assertStatus(200);
        $response->assertViewIs('reception.data');
        $response->assertSee($date . ' の予定');
    }
}
