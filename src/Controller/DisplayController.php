<?php

namespace App\Controller;

use Api;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DisplayController extends AbstractController
{
    /**
     * @Route("/display/{id}", name="display", methods={"GET"})
     */
    public function display($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $movieLists = $user->getMovieLists()->toArray();
        foreach ($movieLists as $movieList) {
            if ($movieList->getIsFavorite()) {
                $favoriteList = $movieList;
            }
        }
        $film = new Api\Movie($id);

        return $this->render('display/display.html.twig',
        [
            'movieTitle' => $film->title,
            'releaseDate' => $film->release_date,
            'overview' => $film->overview,
            'director' => $film->director->name,
            'actors' => $film->cast,
            'moviePicture' => $film->poster_path,
            'genres' => $film->genres,
            'runtime' => $film->runtime,
            'id' => $id,
            'movieLists' => $movieLists,
            'favoriteList' => $favoriteList
        ]);
    }
   
}
