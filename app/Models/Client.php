<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    public function getInvestimentsNotLinked()
    {
        return Investiment::whereNotIn('id', function ($query) {
            $query->select('investiment_id')
                ->from('client_investiment')
                ->where('client_id', '=', $this->id);
        })->orderBy('abbreviation')->get();
    }

    public function deposit($uninvestedValue)
    {
        $this->update(['uninvested_value' => $this->uninvested_value + $uninvestedValue]);

        return true;
    }

    public function invest($investiment, $investedValue)
    {
        if ($investedValue > $this->uninvested_value) {
            return false;
        }

        $this->invested_value += $investedValue;
        $this->uninvested_value -= $investedValue;

        $this->investiments()->attach([
            $investiment->id => ['invested_value' => $investedValue],
        ]);

        $this->save();

        return true;
    }

    public function applyValueToInvestiment($investiment, $valueToApply)
    {
        if ($valueToApply > $this->uninvested_value) {
            return false;
        }

        $this->invested_value += $valueToApply;
        $this->uninvested_value -= $valueToApply;
        $this->save();

        $investiment->clients()->syncWithoutDetaching([
            $this->id => ['invested_value' => DB::raw('invested_value + '.$valueToApply)],
        ]);

        return true;
    }

    public function redeemValueFromInvestiment($investiment, $valueToRedeem)
    {
        $investedValue = $this->investiments->find($investiment->id)->pivot->invested_value;

        if ($valueToRedeem > $investedValue) {
            return false;
        }

        $updatedInvestedValue = $investedValue - $valueToRedeem;
        $investiment->clients()->updateExistingPivot($this->id, [
            'invested_value' => DB::raw('invested_value - '.$valueToRedeem),
        ]);

        $this->uninvested_value += $valueToRedeem;
        $this->invested_value -= $valueToRedeem;
        $this->save();

        if ($updatedInvestedValue == 0) {
            $investiment->clients()->detach($this->id);
        }

        return true;
    }
}
