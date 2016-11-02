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


//Profil
$app->get('/profil/profil', 'ProfilController:getPhoto')->setname('profil.profil');
// $app->post('/profil/profil', 'ProfilController:postPhoto');
