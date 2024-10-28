<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;

    protected $fillable = ['location_id','depot_id', 'nvr_id', 'dvr_id', 'hdd_id', 'camera_capacity', 'current_cctv_count'];

    // Define inverse of the one-to-one relationship with Location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    
    protected $attributes = [
        'current_cctv_count' => 0,  // Set default to 0
    ];
    public function depot()
    {
        return $this->belongsTo(depot::class);
    }
    public function nvr()
    {
        return $this->belongsTo(Nvr::class);
    }

    public function dvr()
    {
        return $this->belongsTo(Dvr::class);
    }

    public function hdd()
    {
        return $this->belongsTo(Hdd::class);
    }

    // Define one-to-many relationship with Cctv
    public function cctvs()
    {
        return $this->hasMany(Cctv::class);
    }
}
