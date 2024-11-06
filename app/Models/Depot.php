<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Define one-to-many relationship with Location
    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function nvrs() {
        return $this->hasMany(Nvr::class);
    }

    public function dvr()
    {
        return $this->belongsTo(Dvr::class);
    }

    public function hdd()
    {
        return $this->belongsTo(Hdd::class);
    }

    public function cctvs()
    {
        return $this->hasMany(Cctv::class);
    }

    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }
}
