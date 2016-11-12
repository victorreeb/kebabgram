<?php

namespace Controllers\Profil;

use Models\Photo;
use Models\User;
use \Controllers\Controller;

class ProfilController extends Controller{

  public function index($request, $response){
    $photos = Photo::where('id_user', $_SESSION['user'])->get();
    $user = User::find($_SESSION['user']);
    if(!empty($photos)){
      $images = [];
      foreach ($photos as $photo) {
        array_push($images, ['name' => $photo->name, 'tag' => $photo->tag, 'id' => $photo->id, 'link' => "/kebabgram/public/uploads/" . $_SESSION['user'] . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension]);
      }
      return $this->view->render($response, 'auth/profil/index.html', ['images' => $images, 'user' => $user->name]);
    }
    return $this->view->render($response, 'auth/profil/index.html');
  }

}
 ?>
