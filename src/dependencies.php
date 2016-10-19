<?php

use Respect\Validation\Validator as v;

// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};


// db bootEloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container['db'] = function($c) {
  return $capsule;
};

//validator
$container['validator'] = function($c){
  return new Validation\Validator;
};

//controllers
$container['HomepageController'] = function($c) {
  return new \Controllers\HomepageController($c);
};
$container['AuthController'] = function($c) {
  return new \Controllers\Auth\AuthController($c);
};
// $container['ProfilController'] = function($c){
//   return new \Controllers\Profil\ProfilController($c);
// }

// csrf
$container['csrf'] = function($container){
  return new \Slim\Csrf\Guard;
};

//middleware
$app->add(new Middleware\ValidationErrorsMiddleware($container));
$app->add(new Middleware\OldInputsMiddleware($container));
$app->add(new Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

//custom rules validation
v::with('Validation\\rules');

//authentification
$container['auth'] = function($c){
  return new Auth\Auth;
};

//Profil


//flash messages
$container['flash'] = function(){
  return new \Slim\Flash\Messages;
};

//view
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $view = new \Slim\Views\Twig($settings['template_path']);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c->router,
        $c->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('auth', [
      'check' => $c->auth->check(),
      'user' => $c->auth->user(),
    ]);

    $view->getEnvironment()->addGlobal('flash', $c->flash);

    return $view;
};
