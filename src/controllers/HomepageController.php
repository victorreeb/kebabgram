<?php

namespace Controllers;

use Models\User;

class HomepageController extends Controller{

  public function index($request, $response){
    return $this->view->render($response, 'homepage.html');
  }

}

?>
