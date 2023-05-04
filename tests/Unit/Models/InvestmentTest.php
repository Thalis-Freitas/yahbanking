<?php

namespace Tests\Unit\Models;

use App\Models\Investment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class InvestmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_the_correct_fillable_properties()
    {
        $fillable = ['name', 'abbreviation', 'description'];
        $investment = new Investment();

        $this->assertEquals($fillable, $investment->getFillable());
    }

    public function test_it_returns_uppercase_abbreviation()
    {
        $investment = new Investment([
            'abbreviation' => 'its',
        ]);

        $abbreviationUpper = $investment->getAbbreviationUpper();

        $this->assertEquals('ITS', $abbreviationUpper);
    }

    public function test_it_returns_abbreviation_and_name()
    {
        $investment = new Investment([
            'abbreviation' => 'its',
            'name' => 'Hammes Group',
        ]);

        $abbreviationAndName = $investment->getAbbreviationAndName();

        $this->assertEquals('ITS | Hammes Group', $abbreviationAndName);
    }
}
