<?php

namespace Tests\Feature;

use App\Bin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\File\UploadedFile as Upfile;

trait ToolsContractTests
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_browse_to_the_shared_tools_index_page()
    {
        $this->signIn();

        $this->get("/tools")
            ->assertOk()
            ->assertSessionHasNoErrors()
            ->assertViewIs('tools.index')
            ->assertSee(route('affinitas.index'));
    }

    /** @test */
    public function file_processor_stores_data_in_the_correct_table()
    {
        $this->signIn();
        $file = UploadedFile::createFromBase(
            (new UpFile(
                __DIR__ . '/Files/CE20191211171004801' . $this->affinitas . '.rep',
                'CE20191211171004801' . $this->affinitas . '.rep',
                'text/plain',
                20416,
                0,
                true
            ))
        );

        $this->post("/file-processor", [
            'files' => [$file],
        ]);

        $this->assertDatabaseHas('reps'. $this->pName, [
            'user_id' => '288781',
            'autorizacion' => '062932'
        ]);
        $this->assertDatabaseHas('reps'. $this->pName, [
            'user_id' => '234993',
            'detalle_mensaje' => 'Rechazada'
        ]);
        $platforms = Arr::except(config('platforms'), $this->pName);

        foreach ($platforms as $platform) {
            $this->assertDatabaseCount('reps'. $platform['name'], 0);
        }
    }

    /** @test */
    public function admins_can_browse_to_the_users_by_bank_index_page()
    {
        $this->signIn();

        $this->get("/users-bank")
            ->assertOk()
            ->assertViewIs('users-bank.index')
            ->assertSeeInOrder(['Plataforma', 'option', $this->pName, '/option']);
    }

    /** @test */
    public function users_by_bank_show_displays_the_amounts()
    {
        $this->withoutExceptionHandling();
        $bank1 = factory(Bin::class)->create();
        $bank2 = factory(Bin::class)->create();
        $bank3 = factory(Bin::class)->create();
        factory($this->card)->times(2)->create(['number' => $bank1->bin.str_repeat(rand(0,9), 10)]);
        factory($this->card)->times(3)->create(['number' => $bank2->bin.str_repeat(rand(0,9), 10)]);
        factory($this->card)->times(4)->create(['number' => $bank3->bin.str_repeat(rand(0,9), 10)]);
        $cards = $this->card::all();
        foreach($cards as $card) {
            factory($this->reps_model)->create([
                'tarjeta' => $card->number,
                'estatus' => 'Declinada',
                'detalle_mensaje' => 'Fondos insuficientes',
                'terminacion' => substr($card->number, -4, 4),
                'user_id' => $card->user_id,
            ]);
        }
        $approved = $this->reps_model::whereIn('id', ['0', '2', '5'])->get();
        $approved->map(function ($charge) {
            $charge->forceFill([
                'estatus' => 'Aprobada',
                'detalle_mensaje' => 'Aprobado',
            ])->save();
        });
        $this->signIn();

        $this->post("/users-bank", ['platform' => $this->pName])
        ->assertSeeInOrder([$this->pName, $bank1->bank, $this->reps_model, 'Aprobado', '1', $this->pName, $bank3->bank, $this->reps_model, 'Aprobado', '1']);
    }
}
