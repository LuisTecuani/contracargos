<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MediakeyTest extends TestCase
{
    /** @test */
    public function a_user_can_register_data_from_rep_files()
    {
        $atributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $atributes);

        $this->assertDatabaseHas('projects', $atributes);
    }
}
