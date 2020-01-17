<?php

namespace App\Controller;

use App\Repository\FavoriteMoviesRepository;
use App\Entity\FavoriteMovies;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteMovieController extends AbstractController
{
    protected $favorite;
    /**
     * @Route("/favorite/{movieId}/{userId}", name="favorite_movie", methods={"GET", "POST"})
     */
    public function addToFavorite($movieId, $userId)
    {
        $favoriteMovie = new FavoriteMovies();
        $favoriteMovie->setMovieId($movieId);
        $favoriteMovie->setUserId($userId);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($favoriteMovie);
        $entityManager->flush();

        return $this->render('favorite_movie/index.html.twig', [
            'controller_name' => 'FavoriteMovieController',
        ]);
    }

    /**
     * @Route("/admin/ranking", name="ranking", methods={"GET", "POST"})
     */
    public function showFavorite()
    {   
        // $favoriteRepo = new FavoriteMoviesRepository();
        return $this->favorite->showRanking();
    }

    public function __construct(FavoriteMoviesRepository $favorite)
    {
        $this->favorite = $favorite;
    }
}
