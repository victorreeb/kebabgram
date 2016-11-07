<?php

namespace Controllers\Upload;

use \Controllers\Controller;
use Models\User;

class ImageController extends Controller{

  public function getAddImage ($request, $response){

    return $this->view->render($response, 'auth/uploads/image.html');

  }

  public function postAddImage($request, $response){

    $user = User::find($_SESSION['user']);

    // overwrite = true
    $storage = new \Upload\Storage\FileSystem($_SERVER['DOCUMENT_ROOT'] . 'kebabgram/public/uploads', true);
    $file = new \Upload\File('file_image', $storage);

    $file->setName(strtolower($user->name) . '_' . 'test');

    $file->addValidations(array(
        new \Upload\Validation\Mimetype(array('image/png', 'image/jpeg')),
        new \Upload\Validation\Size('5M')
    ));

    $data = array(
        'name'       => $file->getNameWithExtension(),
        'extension'  => $file->getExtension(),
        'mime'       => $file->getMimetype(),
        'size'       => $file->getSize(),
        'md5'        => $file->getMd5(),
        'dimensions' => $file->getDimensions()
    );

    try {
        $file->upload();
        $this->flash->addMessage('success', 'Your image is uploaded');
        return $response->withRedirect($this->router->pathFor('home'));
    } catch (\Exception $e) {
        $this->flash->addMessage('error', 'Some errors have been detected.');
        return $response->withRedirect($this->router->pathFor('auth.images.add'));
    }
  }

}

?>
