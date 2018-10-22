<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportType extends Model
{

    public $table = "report_type";
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
