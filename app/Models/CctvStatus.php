<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CctvStatus extends Model
{
    use HasFactory;

    // Define the table if it's not following Laravel's naming convention
    protected $table = 'cctv_status';

    // Specify which attributes can be mass-assigned
    protected $fillable = [
        'report_id',
        'depot_id',
        'location_id',
        'cctv_id',
        'status',
        'off_reason'
    ];

    /**
     * Define the relationship to the Report model
     * A CCTV status entry belongs to one specific report
     */
    public function report()
    {
        return $this->belongsTo(StatusReport::class, 'report_id');
    }

    /**
     * Define the relationship to a CCTV device model (if you have one)
     * You may have a separate CCTV model to store specific CCTV device data
     */
    public function cctv()
    {
        return $this->belongsTo(Cctv::class, 'cctv_id');
    }

    // Optionally, if needed, add other relationships such as depot or location
}
