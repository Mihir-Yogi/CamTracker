<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cctv extends Model
{
    use HasFactory;

    protected $fillable = ['combo_id', 'purchase_date', 'installed_date', 'expiry_date', 'warranty', 'status'];

    // Define inverse of the one-to-many relationship with Combo
    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }
}
