<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    public $table = "report";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'type_id', 'description', 'location', 'long', 'lat', 'created_by', 'updated_by'
  ];
}
