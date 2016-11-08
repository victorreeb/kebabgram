<?php

namespace Controllers\Auth;

use Models\User;
use \Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller{

  public function getSignUp($request, $response){

    return $this->view->render($response, 'auth/sign_up.html');
  }

  public function postSignUp($request, $response){

    $validation = $this->validator->validate($request, [
      'name' => v::notEmpty()->length(2, 20)->UserNameAvailable(),
      'email' => v::notEmpty()->noWhitespace()->email()->EmailAvailable(),
      'password' => v::notEmpty()->noWhitespace(),
    ]);

    if($validation->failed()){
      $this->flash->addMessage('error', 'Some errors have been detected.');
      return $response->withRedirect($this->router->pathFor('auth.signup'));
    }

    $user = User::create([
      'name' => ucfirst($request->getParam('name')),
      'email' => $request->getParam('email'),
      'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
    ]);

    // creation du directory upload du user : droit lecture & écriture pour le propriétaire && lecture pour les autres
    mkdir($_SERVER['DOCUMENT_ROOT'] . 'kebabgram/public/uploads/' . $user->id, 0644);

    $this->flash->addMessage('success', 'You have been signed up !');
    $this->flash->addMessage('info', 'You have been automatically logged in.');
    $this->auth->attempt($user->email, $request->getParam('password'));
    return $response->withRedirect($this->router->pathFor('home'));
  }

  public function getSignIn($request, $response){

    return $this->view->render($response, 'auth/sign_in.html');
  }

  public function postSignIn($request, $response){

    $auth = $this->auth->attempt(
      $request->getParam('email'),
      $request->getParam('password')
    );

    if(!$auth){
      $this->flash->addMessage('error', 'Could not sign you in with those details.');
      return $response->withRedirect($this->router->pathFor('auth.signin'));
    }

    $this->flash->addMessage('info', 'You have been signed in !');
    return $response->withRedirect($this->router->pathFor('home'));

  }

  public function getSignOut($request, $response){

    $this->auth->logout();

    $this->flash->addMessage('info', 'You have been logout !');
    return $response->withRedirect($this->router->pathFor('home'));
  }

}

?>
