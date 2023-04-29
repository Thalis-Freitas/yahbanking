<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Investiment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'description',
    ];

    public function getAbbreviationUpper()
    {
        return Str::upper($this->abbreviation);
    }

    public function getAbbreviationAndName()
    {
        return sprintf('%s | %s', Str::upper($this->abbreviation), $this->name);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class)->withPivot('invested_value');
    }

    public function deleteInvestiment($id)
    {
        foreach ($this->clients as $client) {
            $investedValue = $client->investiments->find($id)->pivot->invested_value;
            $client->uninvested_value += $investedValue;
            $client->invested_value -= $investedValue;
            $client->investiments()->updateExistingPivot($id, ['invested_value' => 0]);
            $client->save();
        }

        $this->delete();

        return true;
    }
}
