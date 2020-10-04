<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TdcVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_browse_to_the_index_page()
    {
        $this->get('/tdc_verification')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('tdc_verification.index');
    }

    /** @test */
    public function can_validate_the_given_tdc_numbers()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/tdc_verification/show', [
            'tdcs' => "4027664183471030\n4027664183471031\n4027664183471032"
        ])
        ->assertViewIs('tdc_verification.show');

        $this->assertCount(1, $response->viewData('valid'));
        $this->assertCount(2, $response->viewData('invalid'));
    }
}
