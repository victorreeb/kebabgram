<?php

namespace Controllers;

use Models\User;
use Models\Photo;

class HomepageController extends Controller{

  public function index($request, $response){
    $photos = Photo::orderBy('created_at', 'desc')->take(6)->get();
    if(!empty($photos)){
      $images = [];
      foreach ($photos as $photo) {
        $user = User::where('id', '=', $photo->id_user)->first();
        array_push($images, ['user' => $user, 'name' => $photo->name, 'id' => $photo->id, 'link' => "/kebabgram/public/uploads/" . $user->id . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension]);
      }
          return $this->view->render($response, 'homepage.html', ['images' => $images]);
    }
    return $this->view->render($response, 'homepage.html');
  }

}

?>
