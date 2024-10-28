<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cctv extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',           // New field for model
        'serial_number',   // New field for serial number
        'combo_id', 
        'purchase_date', 
        'installation_date', // Updated field name to match the form and controller
        'warranty_expiration', // Updated field name to match the form and controller
        'status',
        'image_replace', 
    ];
    // Define inverse of the one-to-many relationship with Combo
    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }
}
