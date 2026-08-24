<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReceptionPageTest extends TestCase
{
    public function test_reception_page_is_displayed(): void
    {
        $response = $this->get('/reception/');

        $response->assertOk();
        $response->assertViewIs('reception.index');
        $response->assertSee('予約表');
        $response->assertSee('先月');
        $response->assertSee('翌月');
        $response->assertDontSee('今月</button>', false);
    }

    public function test_reception_page_can_navigate_between_months(): void
    {
        $currentMonth = now()->startOfMonth();
        $requestedMonth = $currentMonth->copy()->subMonth();
        $response = $this->get('/reception/?m=' . $requestedMonth->format('Y-m'));

        $response->assertOk();
        $response->assertSee($requestedMonth->format('Y年n月'));
        $response->assertSee('/reception?m=' . $requestedMonth->copy()->subMonth()->format('Y-m'), false);
        $response->assertSee('/reception', false);
        $response->assertSee('/reception?m=' . $requestedMonth->copy()->addMonth()->format('Y-m'), false);
        $response->assertSee('今月</button>', false);
    }

    public function test_invalid_month_falls_back_to_current_month(): void
    {
        $response = $this->get('/reception/?m=invalid');

        $response->assertOk();
        $response->assertSee(now()->format('Y年n月'));
    }
}
