<?php

namespace Tests\Unit\Http\Controllers;

use App\AliadoUser;
use App\CellersUser;
use App\MediakeyUser;
use App\Repsaliado;
use App\Repscellers;
use App\Repsmediakey;
use App\RespuestasBanorteAliado;
use App\RespuestasBanorteCellers;
use App\RespuestasBanorteMediakey;
use App\UserTdcAliado;
use App\UserTdcCellers;
use App\UserTdcMediakey;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FindUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/find_user')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('find_user.index');
    }

    /** @test */
    public function show_method_can_find_data_from_an_aliado_user()
    {
        $this->signIn();
        $user = factory(AliadoUser::class)->create();
        $tdc = factory(UserTdcAliado::class)->create([
            'user_id' => $user->id
        ]);
        $chargeBanorte = factory(RespuestasBanorteAliado::class)->create([
            'user_id' => $tdc->user_id,
            'tarjeta' => $tdc->number,
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado'
        ]);
        factory(Repsaliado::class)->create([
            'user_id' => $tdc->user_id,
            'tarjeta' => $tdc->number,
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado'
        ]);

        $this->post('/find_user/show', [
            'user_id' => $user->id,
            'platform' => 'aliado'
        ])->assertViewIs('find_user.show')
            ->assertSeeTextInOrder([
                $user->id,
                $user->email,
                $user->name,
                $user->cancelled_at,
                $user->created_at,
                '2',
                $chargeBanorte->fecha,
            ]);
    }

    /** @test */
    public function show_method_can_find_data_from_a_mediakey_user()
    {
        $this->signIn();
        $user = factory(MediakeyUser::class)->create();
        $tdc = factory(UserTdcMediakey::class)->create([
            'user_id' => $user->id
        ]);
        $chargeBanorte = factory(RespuestasBanorteMediakey::class)->create([
            'user_id' => $tdc->user_id,
            'tarjeta' => $tdc->number,
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado'
        ]);
        factory(Repsmediakey::class)->create([
            'user_id' => $tdc->user_id,
            'tarjeta' => $tdc->number,
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado'
        ]);

        $this->post('/find_user/show', [
            'email' => $user->email,
            'platform' => 'mediakey'
        ])->assertViewIs('find_user.show')
            ->assertSeeTextInOrder([
                $user->id,
                $user->email,
                $user->name,
                $user->cancelled_at,
                $user->created_at,
                '2',
                $chargeBanorte->fecha,
            ]);
    }

    /** @test */
    public function show_method_can_find_data_from_a_cellers_user()
    {
        $this->signIn();
        $user = factory(CellersUser::class)->create();
        $tdc = factory(UserTdcCellers::class)->create([
            'user_id' => $user->id
        ]);
        $chargeBanorte = factory(RespuestasBanorteCellers::class)->create([
            'user_id' => $tdc->user_id,
            'tarjeta' => $tdc->number,
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado'
        ]);
        factory(Repscellers::class)->create([
            'user_id' => $tdc->user_id,
            'tarjeta' => $tdc->number,
            'estatus' => 'Aprobada',
            'detalle_mensaje' => 'Aprobado'
        ]);

        $this->post('/find_user/show', [
            'email' => $user->email,
            'platform' => 'cellers'
        ])->assertViewIs('find_user.show')
            ->assertSeeTextInOrder([
                $user->id,
                $user->email,
                $user->name,
                $user->cancelled_at,
                $user->created_at,
                '2',
                $chargeBanorte->fecha,
            ]);
    }
}
