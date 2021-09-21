<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

            $this->pName = strtolower($this->getPlatformData()['name']);
            $this->card = $this->getPlatformData()['card_model'];
            $this->affinitas = substr($this->getPlatformData()['affinitas'], -4);
            $this->respuestasBanorte = $this->getPlatformData()['respuestas_banorte_model'];
    }

    protected function signIn($user = null)
    {
        $user = $user ?: create('App\User');

        $this->actingAs($user);

        return $this;
    }

    public function getPlatformData()
    {
        return config($this->platform ?? null);
    }
}
