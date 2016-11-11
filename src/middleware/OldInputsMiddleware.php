<?php

namespace Middleware;

class OldInputsMiddleware extends Middleware{

  public function __invoke($request, $response, $next){

    if(!empty($_SESSION['old_inputs'])){
      $this->container->view->getEnvironment()->addGlobal('old_inputs', $_SESSION['old_inputs']);
    }
    $_SESSION['old_inputs'] = $request->getParams();
    $response = $next($request, $response);

    return $response;
  }

}

?>
