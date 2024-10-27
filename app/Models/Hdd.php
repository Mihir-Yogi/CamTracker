<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hdd extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'serial_number',
        'capacity',
        'status',
        'purchase_date',
        'installation_date',
        'warranty_expiration',
        'depot_id',  // New field
        'location_id',  // New field
    ];

    // Define one-to-one relationship with Combo
    public function combo()
    {
        return $this->hasOne(Combo::class);
    }
}
