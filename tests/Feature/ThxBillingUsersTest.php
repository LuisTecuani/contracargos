<?php

namespace Tests\Feature;

use Tests\TestCase;

class ThxBillingUsersTest extends TestCase
{
    use BillingUsersContractTests;

    protected function getPlatformData()
    {
        return config('platforms.thx');
    }
}
