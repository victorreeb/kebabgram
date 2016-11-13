<?php

namespace Controllers;

class VideoController extends Controller{

  public function index($request, $response){
    return $this->view->render($response, 'how_to_make.html');
  }

}

?>
