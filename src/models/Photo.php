<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model{

  protected $table = 'photos'; // optionnal

  protected $fillable = [
    'id',
    'tag',
    'name',
    'place',
    'id_user',
    'extension',
    'description'
  ];


  public function setPassword($password){
    $this->update([
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
  }
}

?>
