<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Forecast extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the city that owns the forecasts.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
