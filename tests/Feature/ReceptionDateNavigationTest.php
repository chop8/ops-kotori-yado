<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReceptionDateNavigationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * カレンダーの日付クリックで該当日の予定ページに遷移できることを確認
     */
    public function test_can_navigate_to_date_page(): void
    {
        $date = '2026-08-26';
        $response = $this->get(route('reception.show', ['date' => $date]));

        $response->assertStatus(200);
        $response->assertViewIs('reception.schedule');
        $response->assertSee($date . ' の予定');
    }

    public function test_can_save_and_get_daily_note(): void
    {
        $date = '2026-10-15';
        $noteText = 'テスト注意書き：本日は臨時休業日です。';

        $response = $this->postJson(route('reception.saveNote'), [
            'date' => $date,
            'note' => $noteText,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $getResponse = $this->get(route('reception.getNote', ['date' => $date]));
        $getResponse->assertStatus(200)
                    ->assertJson(['date' => $date, 'note' => $noteText]);

        $pageResponse = $this->get(route('reception.show', ['date' => $date]));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee($noteText);
    }
}
