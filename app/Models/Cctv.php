<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cctv extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',          
        'serial_number',   
        'combo_id', 
        'purchase_date', 
        'installation_date',
        'warranty_expiration', 
        'status',
        'image_replace', 
        'megapixel',
        'depot_id',
        'location_id',
        'sublocation_id',
    ];
        public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

    public function replacedBy()
    {
        return $this->belongsTo(User::class, 'replaced_by');
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }
    public function sublocation()
    {
        return $this->belongsTo(Sublocation::class, 'sublocation_id'); // Ensure foreign key is specified if needed
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }   
}