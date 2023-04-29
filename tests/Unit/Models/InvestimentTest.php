<?php

namespace Tests\Unit\Models;

use App\Models\Investiment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class InvestimentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_the_correct_fillable_properties()
    {
        $fillable = ['name', 'abbreviation', 'description'];
        $investiment = new Investiment();

        $this->assertEquals($fillable, $investiment->getFillable());
    }

    public function test_it_returns_uppercase_abbreviation()
    {
        $investiment = new Investiment([
            'abbreviation' => 'its',
        ]);

        $abbreviationUpper = $investiment->getAbbreviationUpper();

        $this->assertEquals('ITS', $abbreviationUpper);
    }

    public function test_it_returns_abbreviation_and_name()
    {
        $investiment = new Investiment([
            'abbreviation' => 'its',
            'name' => 'Hammes Group'
        ]);

        $abbreviationAndName = $investiment->getAbbreviationAndName();

        $this->assertEquals('ITS | Hammes Group', $abbreviationAndName);
    }
}
