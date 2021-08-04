<?php

namespace Tests\Feature;

use Tests\TestCase;

class CellersBillingUsersTest extends TestCase
{
    use BillingUsersContractTests;

    protected function getPlatformData()
    {
        return config('platforms.cellers');
    }
}
