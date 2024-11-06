<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sublocation extends Model
{
    use HasFactory;

    protected $table = 'sublocations';

    protected $fillable = ['name'];

}
