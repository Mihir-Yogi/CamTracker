<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'depot_id'];

    // Define inverse of the one-to-many relationship with Depot
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    // Define one-to-one relationship with Combo
    public function combo()
    {
        return $this->hasOne(Combo::class);
    }
}
