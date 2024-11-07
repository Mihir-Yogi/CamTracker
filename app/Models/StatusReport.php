<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusReport  extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $fillable = [
        'user_id', 'depot_id', 'location_id', 
        'nvr_id', 'dvr_id', 'hdd_id', 'cctv_id', 
        'nvr_status','dvr_status','hdd_status','nvr_reason','dvr_reason',
        'hd_reason','cctv_reason','remark_image','cctv_off_count', 'cctv_on_count',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function CctvStatus() {
        return $this->hasMany(CctvStatus::class);
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
    public function nvrs()
    {
        return $this->hasMany(Nvr::class); // Adjust the relationship type based on your requirement
    }
    public function dvrs()
{
    return $this->hasMany(Dvr::class);
}
public function cctvStatuses()
{
    return $this->hasMany(CctvStatus::class, 'report_id');
}
public function hdds()
{
    return $this->hasMany(Hdd::class);
}

public function cctvs()
{
    return $this->hasMany(Cctv::class);
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
