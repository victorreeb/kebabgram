<?php

namespace Controllers;

use Models\User;
use Models\Photo;

class HomepageController extends Controller{

  public function index($request, $response){

    $photos = Photo::all();
    return $this->view->render($response, 'homepage.html');
  }

}

?>
