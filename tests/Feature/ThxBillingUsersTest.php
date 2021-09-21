<?php

namespace Tests\Feature;

use Tests\TestCase;

class ThxBillingUsersTest extends TestCase
{
    use BillingUsersContractTests;
    use ToolsContractTests;

    public $platform = 'platforms.thx';
}
