<?php

namespace Controllers;

use Models\User;
use Models\Photo;

class SearchController extends Controller{

  public function index($request, $response){

    return $this->view->render($response, 'search.html');
  }

  public function search($request, $response){

    $query = $request->getParam('query');
    $option_by_user = $request->getParam('by_user');
    $option_by_tag = $request->getParam('by_tag');
    $option_by_place = $request->getParam('by_place');
    $option_by_name = $request->getParam('by_name');

    if($query != '' or $query != null){

      if($option_by_user == null and $option_by_name == null and $option_by_place == null and $option_by_tag == null){
        $this->flash->addMessage('error', 'Veuillez cocher au moins une option de recherche.');
        return $response->withRedirect($this->router->pathFor('search'));
      }
      else{
        $results = [];

        if($option_by_user != null){
          $users = User::where('name', 'like', '%' . $query . '%')->get();
          $results['users'] = $users;
          foreach ($results['users'] as $user) {
            $nb_photos = Photo::where('id_user', '=', $user->id)->count();
            $user['nb_photos'] = $nb_photos;
          }
        }

        if($option_by_name != null){
          $photos_by_name = Photo::where('name', 'like', '%' . $query .'%')->get();
          $results['photos_by_name'] = $photos_by_name;
          foreach ($results['photos_by_name'] as $photo) {
            $user = User::find($photo->id_user);
            $photo['user'] = $user->name;
            $photo['link'] = "/kebabgram/public/uploads/" . $user->id . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension;
            unset($user);
          }
        }

        if($option_by_place != null){
          $photos_by_place = Photo::where('place', 'like', '%' . $query .'%')->get();
          $results['photos_by_place'] = $photos_by_place;
          foreach ($results['photos_by_place'] as $photo) {
            $user = User::find($photo->id_user);
            $photo['user'] = $user->name;
            $photo['link'] = "/kebabgram/public/uploads/" . $user->id . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension;
            unset($user);
          }
        }

        if($option_by_tag != null){
          $photos_by_tag = Photo::where('tag', 'like', '%' . $query .'%')->get();
          $results['photos_by_tag'] = $photos_by_tag;
          foreach ($results['photos_by_tag'] as $photo) {
            $user = User::find($photo->id_user);
            $photo['user'] = $user->name;
            $photo['link'] = "/kebabgram/public/uploads/" . $user->id . "/" . $photo->id . "_" . $photo->name . "." . $photo->extension;
            unset($user);
          }
        }
        return $this->view->render($response, 'results.html', ["results" => $results, "query" => $query, "by_user" => $option_by_user, "by_name" => $option_by_name, "by_place" => $option_by_place, "by_tag" => $option_by_tag]);
      }

    }
    $this->flash->addMessage('error', 'Veuillez saisir un mot-clé.');
    return $response->withRedirect($this->router->pathFor('search'));
    // search in all tables from Database

    //
  }

}

?>
