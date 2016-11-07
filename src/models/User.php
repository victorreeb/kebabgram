<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

  protected $table = 'users'; // optionnal

  protected $fillable = [
    'name',
    'email',
    'password',
  ];


  public function setPassword($password){
    $this->update([
      'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
  }
}

?>
