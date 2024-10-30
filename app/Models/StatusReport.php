<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusReport  extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'depot_id', 'location_id', 
        'nvr_id', 'dvr_id', 'hdd_id', 'cctv_id', 
        'off_reason', 'status', 'comments'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function depot() {
        return $this->belongsTo(Depot::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }

    public function nvr() {
        return $this->belongsTo(Nvr::class);
    }

    public function dvr() {
        return $this->belongsTo(Dvr::class);
    }

    public function hdd() {
        return $this->belongsTo(Hdd::class);
    }

    public function cctv() {
        return $this->belongsTo(Cctv::class);
    }
}
