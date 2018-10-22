<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportImage extends Model
{
  	public $table = "report_image";
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
  	protected $fillable = [
    	'filename','mime','original_filename','report_id', 'created_by', 'updated_by'
	];


    protected $hidden = ['created_at', 'updated_at'];
}
