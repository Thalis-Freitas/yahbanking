<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Client;

class Investiment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'description'
    ];

    public function getAbbreviationUpper()
    {
        return Str::upper($this->abbreviation);
    }

    public function getAbbreviationAndName()
    {
        return sprintf('%s | %s', Str::upper($this->abbreviation), $this->name);
    }

    public function clients() {
        return $this->belongsToMany(Client::class)->withPivot('invested_value');
    }
}
