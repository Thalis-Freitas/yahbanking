<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'avatar',
        'uninvested_value',
    ];

    public function getFullName(): string
    {
        return "{$this->name} {$this->last_name}";
    }

    public function getAvatarUrl()
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }
    }

    protected static function booted(): void
    {
        static::created(function (Client $client) {
            $client->uninvested_value = 0;
            $client->invested_value = 0;
            $client->investiments()->syncWithoutDetaching([]);
        });
    }

    public function getTotalValueAttribute()
    {
        $totalValue = $this->invested_value + $this->uninvested_value;

        return preg_replace('/[^0-9\.]/', '', number_format($totalValue, 2));
    }

    public function investiments()
    {
        return $this->belongsToMany(Investiment::class)->withPivot('invested_value');
    }
}
