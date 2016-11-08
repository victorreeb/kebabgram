<?php

namespace Controllers\Upload;

use \Controllers\Controller;
use Models\User;
use Models\Photo;

class ImageController extends Controller{

  public function getAddImage ($request, $response){

    return $this->view->render($response, 'auth/uploads/image.html');

  }

  public function postAddImage($request, $response){

    $user = User::find($_SESSION['user']);

    // overwrite = true
    $storage = new \Upload\Storage\FileSystem($_SERVER['DOCUMENT_ROOT'] . '/kebabgram/public/uploads/' . $user->id, true);
    $file = new \Upload\File('file_image', $storage);

    $data = array(
        'name'       => $file->getNameWithExtension(),
        'extension'  => $file->getExtension(),
        'mime'       => $file->getMimetype(),
        'size'       => $file->getSize(),
        'md5'        => $file->getMd5(),
        'dimensions' => $file->getDimensions(),
        'tag' => $request->getParam('tag'),
        'name' => $request->getParam('name'),
        'place' => $request->getParam('place')
    );


    $file->addValidations(array(
        new \Upload\Validation\Mimetype(array('image/png', 'image/jpeg')),
        new \Upload\Validation\Size('5M')
    ));

    $photo = Photo::create([
      'tag' => $data['tag'],
      'name' => $data['name'],
      'place' => $data['place'],
      'extension' => $data['extension'],
      'id_user' => $user->id
    ]);

    $file->setName($photo->id . '_' . $data['name']);
    try {
        $file->upload();
        $this->flash->addMessage('success', 'Your image is uploaded');
        return $response->withRedirect($this->router->pathFor('auth.profil'));
    } catch (\Exception $e) {
        $this->flash->addMessage('error', 'Some errors have been detected.');
        return $response->withRedirect($this->router->pathFor('auth.images.add'));
    }
  }

}

?>
