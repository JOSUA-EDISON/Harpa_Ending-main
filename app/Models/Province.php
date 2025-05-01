<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'province_id',
        'name'
    ];

    /**
     * Get the cities for the province.
     */
    public function cities()
    {
        return $this->hasMany(City::class, 'province_id', 'province_id');
    }
}
