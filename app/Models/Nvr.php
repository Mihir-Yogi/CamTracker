<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Nvr extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'serial_number',
        'status',
        'purchase_date',
        'installation_date',
        'warranty_expiration',
        'depot_id',  // New field
        'location_id',  // New field
        'image_replace', 
    ];


    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    // Define one-to-one relationship with Combo
    public function combo()
    {
        return $this->hasOne(Combo::class);
    }
}
