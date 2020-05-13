<?php

namespace Tests\Unit\Http\Controllers;


use App\AliadoUser;
use App\ContracargosAliado;
use App\ContracargosAliadoBanorte;
use App\FileProcessor;
use App\Http\Controllers\AliadoChargebackController;
use App\Repsaliado;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AliadoChargebackControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_index_page()
    {
        $this->signIn();

        $this->get('/aliado/chargeback')
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('aliado.chargeback.index');
    }

    /** @test */
    public function index_method_show_agregated_users_today()
    {
        $this->signIn();
        $chargedbackBanorte = factory(ContracargosAliadoBanorte::class)->create();
        $chargedbackProsa = factory(ContracargosAliado::class)->create();

        $this->get('/aliado/chargeback')
            ->assertSeeInOrder([
                $chargedbackBanorte->email,
                $chargedbackBanorte->fecha_contracargo,
                $chargedbackBanorte->fecha_consumo,
                $chargedbackBanorte->tarjeta,
                $chargedbackBanorte->autorizacion,
            ])
            ->assertSeeInOrder([
                $chargedbackProsa->email,
                $chargedbackProsa->fecha_contracargo,
                $chargedbackProsa->fecha_consumo,
                $chargedbackProsa->tarjeta,
                $chargedbackProsa->autorizacion,
            ]);
    }

    /** @test */
    public function store_method_persist_data_on_contracargos_table()
    {
        $this->signIn();
        $charge1 = factory(Repsaliado::class)->create();
        $charge2 = factory(Repsaliado::class)->create();

        $this->post('/aliado/chargeback/store', [
            'autorizaciones' => "$charge1->autorizacion,$charge1->terminacion\r\n$charge2->autorizacion,$charge2->terminacion",
        ]);

        $this->assertDatabaseHas('contracargos_aliado', [
            'tarjeta' => $charge1->terminacion,
            'autorizacion' => $charge1->autorizacion,
        ]);
        $this->assertDatabaseHas('contracargos_aliado', [
            'tarjeta' => $charge2->terminacion,
            'autorizacion' => $charge2->autorizacion,
        ]);
    }

    /** @test */
    public function show_method_displays_emails_from_users_searched_today()
    {
        $this->signIn();
        $chargebackProsaToday = factory(ContracargosAliado::class)->create();
        $chargebackBanorteToday = factory(ContracargosAliadoBanorte::class)->create();
        $chargebackProsaPast = factory(ContracargosAliado::class)->create([
            'created_at' => '2020-01-15 11:57:34'
        ]);
        $chargebackBanortePast = factory(ContracargosAliadoBanorte::class)->create([
            'created_at' => '2020-01-15 11:57:34'
        ]);

        $this->get('/aliado/chargeback/show')
            ->assertViewIs('aliado.chargeback.last')
            ->assertSee($chargebackProsaToday->email)
            ->assertSee($chargebackBanorteToday->email)
            ->assertDontSee($chargebackProsaPast->email)
            ->assertDontSee($chargebackBanortePast->email);
    }

    /** @test */
    public function update_method_adds_the_apropriate_email_userId_and_repDate_to_ContracargosAliado()
    {
        $this->signIn();
        $foundableUser = factory(AliadoUser::class)->create();
        $foundableCharge = factory(Repsaliado::class)->create([
            'user_id' => $foundableUser->id,
        ]);
        factory(ContracargosAliado::class)->create([
            'autorizacion' => $foundableCharge->autorizacion,
            'tarjeta' => $foundableCharge->terminacion,
            'user_id' => null,
            'fecha_rep' => null,
            'email' => null,
        ]);
        $notFoundableCharge = factory(ContracargosAliado::class)->create([
            'user_id' => null,
            'fecha_rep' => null,
            'email' => null,
        ]);

        (new AliadoChargebackController(new FileProcessor()))->update();

        $this->assertDatabaseHas('contracargos_aliado', [
            'user_id' => $foundableCharge->user_id,
            'fecha_rep' => $foundableCharge->fecha,
            'email' => $foundableUser->email,
        ]);
        $this->assertDatabaseHas('contracargos_aliado', [
            'autorizacion' => $notFoundableCharge->autorizacion,
            'user_id' => null,
            'fecha_rep' => null,
            'email' => null,
        ]);
    }
}
