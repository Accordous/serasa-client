<?php

namespace Accordous\SerasaClient\Tests\Unit;

use Accordous\SerasaClient\Services\SerasaService;
use Accordous\SerasaClient\Tests\TestCase;

class ReportTest extends TestCase
{
    /**
     * @test
     */
    public function canGetB49CReport()
    {
        $service = new SerasaService();

        $result = $service->b49c(env('TEST_CPF'), 'F');

        $this->assertArrayHasKey('income', $result);
        $this->assertArrayHasKey('scoring', $result);
    }

    /**
     * @test
     */
    public function canGetIP20Report()
    {
        $this->markTestIncomplete();

        $service = new SerasaService();

        $result = $service->ip20(env('TEST_CNPJ'));
    }
}
