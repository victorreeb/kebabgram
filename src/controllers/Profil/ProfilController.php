<?php

namespace Controllers\Profil;

use Models\User;
use \Controllers\Controller;
use Respect\Validation\Validator as v;

class ProfilController extends Controller{


  public function getPhoto($request, $response){
    return $this->view->render($response, 'profil/profil.html');



  }





}
 ?>
