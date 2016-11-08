<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model{

  protected $table = 'photos'; // optionnal

  protected $fillable = [
    'tag',
    'name',
    'place',
    'extension',
    'id_user'
  ];

}

?>
