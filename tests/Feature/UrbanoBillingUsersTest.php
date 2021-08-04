<?php

namespace Tests\Feature;

use Tests\TestCase;

class UrbanoBillingUsersTest extends TestCase
{
    use BillingUsersContractTests;

    protected function getPlatformData()
    {
        return config('platforms.urbano');
    }
}
