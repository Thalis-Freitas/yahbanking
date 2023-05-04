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
            $client->investments()->syncWithoutDetaching([]);
        });
    }

    public function getTotalValueAttribute()
    {
        $totalValue = $this->invested_value + $this->uninvested_value;

        return preg_replace('/[^0-9\.]/', '', number_format($totalValue, 2));
    }

    public function investments()
    {
        return $this->belongsToMany(Investment::class)->withPivot('invested_value');
    }

    public function getInvestmentsNotLinked()
    {
        return Investment::whereNotIn('id', function ($query) {
            $query->select('investment_id')
                ->from('client_investment')
                ->where('client_id', '=', $this->id);
        })->orderBy('abbreviation')->get();
    }

    public function deposit($uninvestedValue)
    {
        $this->update(['uninvested_value' => $this->uninvested_value + $uninvestedValue]);

        return true;
    }

    public function invest($investment, $investedValue)
    {
        if ($investedValue > $this->uninvested_value) {
            return false;
        }

        $this->invested_value += $investedValue;
        $this->uninvested_value -= $investedValue;

        $this->investments()->attach([
            $investment->id => ['invested_value' => $investedValue],
        ]);

        $this->save();

        return true;
    }

    public function applyValueToInvestment($investment, $valueToApply)
    {
        if ($valueToApply > $this->uninvested_value) {
            return false;
        }

        $this->invested_value += $valueToApply;
        $this->uninvested_value -= $valueToApply;
        $this->save();

        $investment->clients()->syncWithoutDetaching([
            $this->id => ['invested_value' => DB::raw('invested_value + '.$valueToApply)],
        ]);

        return true;
    }

    public function redeemValueFromInvestment($investment, $valueToRedeem)
    {
        $investedValue = $this->investments->find($investment->id)->pivot->invested_value;

        if ($valueToRedeem > $investedValue) {
            return false;
        }

        $updatedInvestedValue = $investedValue - $valueToRedeem;
        $investment->clients()->updateExistingPivot($this->id, [
            'invested_value' => DB::raw('invested_value - '.$valueToRedeem),
        ]);

        $this->uninvested_value += $valueToRedeem;
        $this->invested_value -= $valueToRedeem;
        $this->save();

        if ($updatedInvestedValue == 0) {
            $investment->clients()->detach($this->id);
        }

        return true;
    }
}
