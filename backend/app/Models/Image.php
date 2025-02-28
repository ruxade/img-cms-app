<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $fillable = ['user_id', 'caption', 'path', 'visibility'];

  // Define the relationship with the User model
  public function user()
  {
      return $this->belongsTo(User::class);
  }
}
