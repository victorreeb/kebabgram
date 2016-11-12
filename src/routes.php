<?php

use Middleware\AuthMiddleware;
use Middleware\GuestMiddleware;

//GuestMiddleware
$app->group('', function(){

  //Auth
  $this->get('/auth/signup', 'AuthController:getSignUp')->setname('auth.signup');
  $this->post('/auth/signup', 'AuthController:postSignUp');

  $this->get('/auth/signin', 'AuthController:getSignIn')->setname('auth.signin');
  $this->post('/auth/signin', 'AuthController:postSignIn');

})->add(new GuestMiddleware($container));

//AuthMiddleware
$app->group('', function(){

  //Auth
  $this->get('/auth/signout', 'AuthController:getSignOut')->setname('auth.signout');

  $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setname('auth.password.change');
  $this->post('/auth/password/change', 'PasswordController:postChangePassword');

  $this->get('/auth/images/add', 'ImageController:getAddImage')->setname('auth.images.add');
  $this->post('/auth/images/add', 'ImageController:postAddImage');
  $this->post('/auth/images/{id}/delete', 'ImageController:delete_images_user')->setname('auth.images.delete');
  $this->post('/{name}/images/{id}/noter', 'ImageController:noter')->setname('image.noter');

})->add(new AuthMiddleware($container));

// Homepage
$app->get('/', 'HomepageController:index')->setname('home');

//Profil
$app->get('/profil', 'ProfilController:index')->setname('auth.profil');

//images
$app->get('/images', 'ImageController:index')->setname('images');
$app->get('/images/bestof', 'ImageController:index_best')->setname('images.bestof');
$app->get('/{name}/images', 'ImageController:index_images_user')->setname('images.user.index');
$app->get('/{name}/images/{id}', 'ImageController:show_images_user')->setname('images.user.show');
$app->post('/{name}/images/{id}', 'ImageController:edit_images_user')->setname('images.user.edit');
