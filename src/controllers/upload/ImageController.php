<?php

namespace Controllers\Upload;

use \Controllers\Controller;

class ImageController extends Controller{

  public function getAddImage ($request, $response){

    return $this->view->render($response, 'auth/uploads/image.html');

  }

  public function postAddImage($request, $response){
    echo "Post Image \n";
    $files = $request->getUploadedFiles();
    var_dump($files['file_image']);
  }

}

?>
