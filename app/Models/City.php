<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'province_id',
        'city_id',
        'name',
        'type'
    ];

    /**
     * Get the province that owns the city.
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }
}
