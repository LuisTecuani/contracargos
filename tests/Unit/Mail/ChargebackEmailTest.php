<?php

namespace Tests\Unit\Mail;

use App\ContracargosAliado;
use App\ContracargosAliadoBanorte;
use App\ContracargosCellers;
use App\ContracargosCellersBanorte;
use App\ContracargosMediakey;
use App\ContracargosMediakeyBanorte;
use App\Http\Controllers\AliadoChargebackController;
use App\Http\Controllers\EmailController;
use Tests\TestCase;
use App\Mail\ChargebackEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ChargebackEmailTest extends TestCase
{
    use RefreshDatabase;

    /** incomplete test */
    public function send_method_dispatches_mail_with_valid_data()
    {
        Mail::fake();
        $this->signIn();
        $this->withoutExceptionHandling();
        //aliado banorte chargeback
        $user1 = factory(ContracargosAliadoBanorte::class)->create();
        //aliado prosa chargeback
        $user2 = factory(ContracargosAliado::class)->create();
        //cellers banorte chargeback
        factory(ContracargosCellersBanorte::class)->create();
        //cellers prosa chargeback
        factory(ContracargosCellers::class)->create();
        //mediakey banorte chargeback
        factory(ContracargosMediakeyBanorte::class)->create();
        //mediakey prosa chargeback
        factory(ContracargosMediakey::class)->create();

        $this->get('/aliado/chargeback/show');

        // Assert number of users...
        Mail::assertSent(ChargebackEmail::class, function ($mail) {
            return count($mail->data->users) === 2;
        });
        // Assert a message was sent to Daniel...
        Mail::assertSent(ChargebackEmail::class, function ($mail) {
            return $mail->hasTo('danielcarrillo@thehiveteam.com') &&
                $mail->hasCc('...') &&
                $mail->hasBcc('...');
        });
    }
}
