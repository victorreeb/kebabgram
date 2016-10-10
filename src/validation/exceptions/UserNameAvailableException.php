<?php

namespace Validation\exceptions;

use Respect\Validation\Exceptions\ValidationException;

class UserNameAvailableException extends ValidationException{

  public static $defaultTemplates = [
    self::MODE_DEFAULT => [
      self::STANDARD => 'Name is already taken.',
    ]
  ];


}


?>
