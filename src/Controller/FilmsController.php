<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilmsController extends AbstractController
{
    /**
     * @Route("/", name="index_list", methods={"GET"})
     */
    public function index()
    {
        return $this->render('films/index.html.twig');
    }

    /**
     * @Route("film/{id}", name="film_page", methods={"GET"})
     */
    public function showFilm($id)
    {
        $db = \TMDB\Client::getInstance('1210f19ac87961b089308e4742b1a4b4'); 
        $movie = new \TMDB\structures\Movie($id);
        $cast = $movie->casts();
        return $this->render('films/page.html.twig',[
            'film' => $movie,
            'cast' => $cast
        ]);
    }
}