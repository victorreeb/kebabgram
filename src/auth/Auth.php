<?php

namespace Auth;

use Models\User;

class Auth{

  public function attempt($email, $password){

    $user = User::where('email', $email)->first();

    if(!$user){
      return false;
    }

    if(password_verify($password, $user->password)){
      $_SESSION['user'] = $user->id;
      return true;
    }

    return false;
  }

  public function check(){

    return(isset($_SESSION['user']));
  }

  public function user(){
    if(empty($_SESSION['user'])){
      return false;
    }
    if(empty(User::find($_SESSION['user']))){
      return false;
    }
    return User::find($_SESSION['user']);
  }

  public function logout(){

    unset($_SESSION['user']);
    
  }

}

?>
