<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
  public $table = "user_image";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'filename','mime','original_filename','user_id', 'created_by', 'updated_by'
];
}
