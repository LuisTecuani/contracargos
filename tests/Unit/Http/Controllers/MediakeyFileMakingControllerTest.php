<?php

namespace Tests\Unit\Http\Controllers;

use App\MediakeyBillingUsers;
use App\MediakeyUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediakeyFileMakingControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/mediakey/file_making')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('mediakey.file_making.index');
    }

    /** incomplete test test  */
    public function it_can_build_a_valid_ftp_file()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $user1 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '18-10',
        ]);
        $user2 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '17-01',
        ]);
        $user3 = factory(MediakeyBillingUsers::class)->create([
            'exp_date' => '27-01',
        ]);
        factory(MediakeyUser::class)->create([
            'id' => $user1->user_id,
        ]);
        factory(MediakeyUser::class)->create([
            'id' => $user2->user_id,
        ]);
        factory(MediakeyUser::class)->create([
            'id' => $user3->user_id,
        ]);

        $this->get('/mediakey/banorte/ftpProsa');

        $this->assertFileExists("SCAENT0897D" . now()->format('ymd') . "ER01.ftp",);

    }
}
