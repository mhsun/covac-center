<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VaccineCenter extends Model
{
    /** @use HasFactory<\Database\Factories\VaccineCenterFactory> */
    use HasFactory;

    protected $fillable = ['name', 'location', 'daily_capacity'];

    protected $casts = [
        'daily_capacity' => 'integer',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(User::class, 'vaccine_center_id');
    }
}
