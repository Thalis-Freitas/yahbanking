<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Investiment;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'avatar',
        'uninvested_value'
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
        });
    }

    public function getTotalValueAttribute()
    {
        $totalValue = $this->invested_value + $this->uninvested_value;
        return number_format($totalValue, 2);
    }
}
