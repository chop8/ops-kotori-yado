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
        $response->assertSee('受付');
    }
}
