<?php

namespace Controllers\Upload;

use \Controllers\Controller;
use Models\User;
use Models\Photo;
use Models\Note;
use Respect\Validation\Validator as v;

class ImageController extends Controller{

  public function index($request, $response){
    $photos = Photo::all();
    if(!empty($photos)){
      $images = [];
      foreach ($photos as $photo) {
        $user = User::where('id', '=', $photo->id_user)->first();
        array_push($images, ['user' => $user, 'name' => $photo->name, 'id' => $photo->id, 'link' => "/kebabgram/public/uploads/" . $user->id . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension]);
      }
      return $this->view->render($response, 'images/index.html', ['images' => $images]);
    }
    else{
      return $this->view->render($response, 'images/index.html');
    }
  }

  public function getAddImage ($request, $response){

    return $this->view->render($response, 'auth/uploads/image.html');

  }

  public function postAddImage($request, $response){

    $user = User::find($_SESSION['user']);

    // overwrite = true
    $storage = new \Upload\Storage\FileSystem($_SERVER['DOCUMENT_ROOT'] . '/kebabgram/public/uploads/' . $user->id, true);
    $file = new \Upload\File('file_image', $storage);

    $mimetype = $file->getMimetype();
    $data = array(
        'name'       => $file->getNameWithExtension(),
        'extension'  => $file->getExtension(),
        'mime'       => $file->getMimetype(),
        'size'       => $file->getSize(),
        'md5'        => $file->getMd5(),
        'dimensions' => $file->getDimensions(),
        'tag' => $request->getParam('tag'),
        'name' => $request->getParam('name'),
        'place' => $request->getParam('place'),
        'description' => $request->getParam('description')
    );

    if($data['tag'][0] != '#'){
      $data['tag'] = '#' . $data['tag'];
    }


    // validation
    $validation = $this->validator->validate($request, [
      'name' => v::notEmpty()->alnum(),
      'tag' => v::notEmpty(),
      'place' => v::notEmpty(),
      'description' => v::notEmpty()
    ]);
    $validation_format = ImageController::validate_format($data['mime']);
    if($validation->failed() or !$validation_format){
      $this->flash->addMessage('error', 'Some errors have been detected.');
      return $response->withRedirect($this->router->pathFor('auth.images.add'));
    }

    try {
        $photo = Photo::create([
          'tag' => $data['tag'],
          'name' => $data['name'],
          'place' => $data['place'],
          'extension' => $data['extension'],
          'description' => $data['description'],
          'moyenne' => 0,
          'id_user' => $user->id
        ]);

        $file->setName($photo->id . '_' . $data['name']);
        $file->upload();
        $this->flash->addMessage('success', 'Your image is uploaded');
        return $response->withRedirect($this->router->pathFor('auth.profil'));
    } catch (\Exception $e) {
        $this->flash->addMessage('error', 'Un problème est survenu lors de l\'import de votre image, réessayez ultérieurement.');
        return $response->withRedirect($this->router->pathFor('auth.images.add'));
    }
  }

  public function validate_format($mimetype){
    if($mimetype === 'image/jpeg' or $mimetype === 'image/jpg' or $mimetype === 'image/png'){
      return true;
    }
    else{
      $_SESSION['errors']['file_image'] = "Le fichier est incorrect. Les formats acceptés sont les images .png et .jpg.";
      var_dump($_SESSION['errors']);
      return false;
    }
  }

  public function index_images_user($request, $response, $name){
    $user = User::where('name', '=', $name)->first();
    if(!empty($user)){
      $photos = Photo::where('id_user', $user->id)->get();
      if(!empty($photos)){
        $images = [];
        foreach ($photos as $photo) {
          array_push($images, ['name' => $photo->name, 'id' => $photo->id, 'link' => "/kebabgram/public/uploads/" . $user->id . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension]);
        }
        return $this->view->render($response, 'images/users/index.html', ['images' => $images, 'user' => $user]);
      }
      return $this->view->render($response, 'images/users/index.html');
    }
    else{
      $this->flash->addMessage('error', 'L\'utilisateur ' . $name['name'] . ' est introuvable.');
      return $response->withRedirect($this->router->pathFor('home'));
    }
  }

  public function show_images_user($request, $response, $id){
    if(!empty($id)){
      $photo = Photo::find($id)->first();
      if(!empty($photo)){
        $user = User::where('id', '=', $photo->id_user)->first();

        //check if user has already give a note
        $vote_boolean = true;
        $note = Note::where('id_user', '=', $_SESSION['user'])->where('id_photo', '=', $photo->id)->first();
        if(!empty($note)){
          $vote_boolean = false;
        }

        if(!empty($_SESSION['user'])){
          if($_SESSION['user'] == $user->id){
            return $this->view->render($response, 'images/users/edit.html', ['user' => $user->name, 'photo' => $photo, 'link' => "/kebabgram/public/uploads/" . $user->id . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension]);
          }
          else{
            return $this->view->render($response, 'images/users/show.html', ['vote' => $vote_boolean, 'user' => $user->name, 'photo' => $photo, 'link' => "/kebabgram/public/uploads/" . $user->id . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension]);
          }
        }
        else{
          return $this->view->render($response, 'images/users/show.html', ['vote' => $vote_boolean, 'user' => $user->name, 'photo' => $photo, 'link' => "/kebabgram/public/uploads/" . $user->id . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension]);
        }
      }
    }
    $this->flash->addMessage('error', 'Nous ne parvenons pas à charger l\'image.');
    return $response->withRedirect($this->router->pathFor('home'));
  }

  public function edit_images_user($request, $response, $id){
    if(!empty($id)){
      $photo = Photo::find($id)->first();
      if(!empty($photo)){
        $user = User::find($_SESSION['user'])->first();
        // validation
        $validation = $this->validator->validate($request, [
          'tag' => v::notEmpty(),
          'place' => v::notEmpty(),
          'description' => v::notEmpty()
        ]);
        if($validation->failed()){
          $this->flash->addMessage('error', 'Some errors have been detected.');
          return $response->withRedirect($this->router->pathFor('images.user.show', ['name' => $user->name, 'id' => $photo->id]));
        }

        // update data
        $tag = $request->getParam('tag');
        if($tag[0] != '#'){
          $tag = '#' . $tag;
        }
        $photo->tag = $tag;
        $photo->place = $request->getParam('place');
        $photo->description = $request->getParam('description');

        // if photo save
        if($photo->save()){
          $this->flash->addMessage('success', 'Les informations ont pu être mise à jour.');
        }
        else{
          $this->flash->addMessage('error', 'Les informations n\'ont pas pu être mise à jour.');
        }
        return $response->withRedirect($this->router->pathFor('images.user.show', ['name' => $user->name, 'id' => $photo->id]));

      }
    }
    $this->flash->addMessage('error', 'Nous ne parvenons pas à charger l\'image.');
    return $response->withRedirect($this->router->pathFor('home'));
  }

  public function delete_images_user($request, $response, $id){
    $photo = Photo::find($id)->first();
    $photo->delete();
    $this->flash->addMessage('success', 'L\'image a bien été supprimée.');
    return $response->withRedirect($this->router->pathFor('auth.profil'));

  }

  public function noter($request, $response, $id){
    $photo = Photo::find($id)->first();
    $user = User::where('id', '=', $photo->id_user)->first();

    // validation
    $validation = $this->validator->validate($request, [
      'note' => v::alnum()
    ]);
    if($validation->failed()){
      $this->flash->addMessage('error', 'Some errors have been detected.');
      return $response->withRedirect($this->router->pathFor('images.user.show', ['name' => $user->name, 'id' => $photo->id]));
    }
    $param_note = $request->getParam('note');

    if($param_note < 0){
      $param_note = 0;
    }
    elseif($param_note > 10){
      $param_note = 10;
    }

    Note::create([
      'id_photo' => $photo->id,
      'valeur' => $param_note,
      'id_user' => $_SESSION['user']
    ]);


    $notes = Note::where('id_photo', '=', $photo->id)->get();

    if(!empty($notes)){
      $moyenne = 0;
      $coef = 0;
      $deno = 0;
      foreach ($notes as $note) {
        $coef = $note->valeur + $coef;
        $deno = $deno +1;
      }
      if($deno == 0){
        $deno = 1;
      }
      $moyenne = $coef / $deno;
      $photo->moyenne = $moyenne;
    }
    else{
      $photo->moyenne = $param_note;
    }
    $photo->save();
    return $response->withRedirect($this->router->pathFor('images.user.show', ['name' => $user->name, 'id' => $photo->id]));
  }



}

?>
