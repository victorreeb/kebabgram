<?php

namespace Controllers\Profil;

use Models\Photo;
use \Controllers\Controller;

class ProfilController extends Controller{

  public function index($request, $response){
    $photos = Photo::where('id_user', $_SESSION['user'])->get();
    if(!empty($photos)){
      $images = [];
      foreach ($photos as $photo) {
        array_push($images, "/kebabgram/public/uploads/" . $_SESSION['user'] . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension);
      }
      return $this->view->render($response, 'auth/profil/index.html', ['images' => $images]);
    }
    return $this->view->render($response, 'auth/profil/index.html');
  }

}
 ?>
