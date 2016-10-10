<?php

namespace Controllers\Auth;

use Models\User;
use \Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller{

  public function getSignUp($request, $response){
    $this->view->render($response, 'auth/sign_up.html');
  }

  public function postSignUp($request, $response){

    $validation = $this->validator->validate($request, [
      'name' => v::notEmpty()->length(2, 20),
      'email' => v::notEmpty()->noWhitespace()->email(),
      'password' => v::notEmpty()->noWhitespace(),
    ]);

    if($validation->failed()){
      return $response->withRedirect($this->router->pathFor('auth.signup'));
    }

    $user = User::create([
      'name' => $request->getParam('name'),
      'email' => $request->getParam('email'),
      'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
    ]);

    return $response->withRedirect($this->router->pathFor('home'));
  }

}

?>
