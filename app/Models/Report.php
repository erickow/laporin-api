<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    public $table = "report";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id', 'description', 'location', 'long', 'lat', 'created_by', 'updated_by'
  	];

  	public function type() 
  	{
  		return $this->belongsTo('App\Models\ReportType');
  	}

  	public function images() 
  	{
  		return $this->hasMany('App\Models\ReportImage');
  	}
}
