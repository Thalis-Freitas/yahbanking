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
        'avatar'
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
            $client->total_value = 0;
            $client->uninvested_value = 0;
            $client->invested_value = 0;
        });
    }
}
