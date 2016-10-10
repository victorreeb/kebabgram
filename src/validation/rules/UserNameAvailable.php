<?php

namespace Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Models\User;

class UserNameAvailable extends AbstractRule{

  public function validate($input){

    return User::where('name', $input)->count() === 0;
  }

}

?>
