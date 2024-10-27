<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city'];

    // Define one-to-many relationship with Location
    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
