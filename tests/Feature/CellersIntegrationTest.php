<?php

namespace Tests\Feature;

use Tests\TestCase;

class CellersIntegrationTest extends TestCase
{
    use BillingUsersContractTests;
    use ResponsesContractTests;
    use ToolsContractTests;

    public $platform = 'platforms.cellers';

}
