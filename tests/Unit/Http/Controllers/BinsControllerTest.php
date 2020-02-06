<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BinsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/bins')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('bins.index');
    }

    /** @test */
    public function store_method_persist_data_on_bins_table()
    {
        $this->signIn();

        $this->post('/bins', [
            'data' => "American Express,American Express,mexico,370700\r\nBanregio,Visa,usa,436401",
        ]);

        $this->assertDatabaseHas('bins', [
            'bank' => 'American Express',
            'network' => 'American Express',
            'country' => 'mexico',
            'bin' => '370700',
        ]);
        $this->assertDatabaseHas('bins', [
            'bank' => 'Banregio',
            'network' => 'Visa',
            'country' => 'usa',
            'bin' => '436401',
        ]);
    }
}
