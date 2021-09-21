<?php

namespace Tests\Feature;

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
            ->assertViewIs('tools.index');
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
}
