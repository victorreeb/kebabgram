<?php

namespace Controllers\Upload;

use \Controllers\Controller;
use Models\User;
use Models\Photo;
use Respect\Validation\Validator as v;

class ImageController extends Controller{

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


    // validation
    $validation = $this->validator->validate($request, [
      'name' => v::notEmpty(),
      'tag' => v::notEmpty()->noWhitespace(),
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
}

?>
