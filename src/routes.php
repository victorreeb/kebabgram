<?php

// Homepage
$app->get('/', 'HomepageController:index')->setname('home');

//Auth
$app->get('/auth/signup', 'AuthController:getSignUp')->setname('auth.signup');
$app->post('/auth/signup', 'AuthController:postSignUp');
<<<<<<< HEAD

$app->get('/auth/signin', 'AuthController:getSignIn')->setname('auth.signin');
$app->post('/auth/signin', 'AuthController:postSignIn');
=======
>>>>>>> master
