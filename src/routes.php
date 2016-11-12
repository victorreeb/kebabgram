<?php

// Homepage
$app->get('/', 'HomepageController:index')->setname('home');

//Auth
$app->get('/auth/signup', 'AuthController:getSignUp')->setname('auth.signup');
$app->post('/auth/signup', 'AuthController:postSignUp');

$app->get('/auth/signin', 'AuthController:getSignIn')->setname('auth.signin');
$app->post('/auth/signin', 'AuthController:postSignIn');

$app->get('/auth/signout', 'AuthController:getSignOut')->setname('auth.signout');

//Import
$app->get('/auth/images/add', 'ImageController:getAddImage')->setname('auth.images.add');
$app->post('/auth/images/add', 'ImageController:postAddImage');
$app->post('/auth/images/{id}/delete', 'ImageController:delete_images_user')->setname('auth.images.delete');


//Profil
$app->get('/profil', 'ProfilController:index')->setname('auth.profil');

//images
$app->get('/{name}/images', 'ImageController:index_images_user')->setname('images.user.index');
$app->get('/{name}/images/{id}', 'ImageController:show_images_user')->setname('images.user.show');
$app->post('/{name}/images/{id}', 'ImageController:edit_images_user')->setname('images.user.edit');


//password
$app->get('/auth/password/change', 'PasswordController:getChangePassword')->setname('auth.password.change');
$app->post('/auth/password/change', 'PasswordController:postChangePassword');
