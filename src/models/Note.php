<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model{

  protected $table = 'notes'; // optionnal

  protected $fillable = [
    'id',
    'id_photo',
    'valeur',
    'id_user'
  ];

}

?>
