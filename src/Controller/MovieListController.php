<?php

namespace App\Controller;

use Api;

use App\Entity\MovieList;
use App\Form\MovieListType;
use App\Repository\MovieListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 
 * AbstractControler
 * Method:
 *   - getUser() // To get the login user
 *   - render() // To render twig template
 */

/**
 * @Route("/list")
 */
class MovieListController extends AbstractController
{
    /**
     * @Route("/", name="movie_list_index", methods={"GET"})
     */
    public function index(MovieListRepository $movieListRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('movie_list/index.html.twig', [
            'movie_lists' => $movieListRepository->findBy(
                ['userId' => $this->getUser()->getId()]
            ),
        ]);
    }

    /**
     * @Route("/new", name="movie_list_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $movieList = new MovieList();
        $form = $this->createForm(MovieListType::class, $movieList);
        $form->handleRequest($request);

        //creating a list
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $movieList->setIsFavorite(false);
            $user->addMovieList($movieList);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movieList);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('movie_list_index');
        }

        return $this->render('movie_list/new.html.twig', [
            'movie_list' => $movieList,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/favorites", name="movie_list_favorite", methods={"GET"})
     */
    public function favoritesMovies(MovieListRepository $movieListRepository)
    {
        $favoriteLists = $movieListRepository->findBy(
            ['isFavorite' => true]
        );
        $movies = [];
        foreach ($favoriteLists as $favoriteList) {
            $tempMovies = $favoriteList->getMovies();
            foreach ($tempMovies as $movie) {
                if (array_key_exists($movie, $movies)) {
                    $movies[$movie]["count"] += 1;
                }
                else {
                    $movies[$movie] = [
                        "movie" => new Api\Movie($movie),
                        "count" => 1
                    ];
                }
            }
        }
        return $this->render('movie_list/favorites.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/{id}", name="movie_list_show", methods={"GET"})
     */
    public function show(MovieList $movieList): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($movieList->getUserId() != $this->getUser()) {
            throw $this->createNotFoundException('The list does not exist');
        }

        $movies = [];
        foreach ($movieList->getMovies() as $movieId) {
            $movies[] = new Api\Movie($movieId);
        }

        return $this->render('movie_list/show.html.twig', [
            'movie_list' => $movieList,
            'movies' => $movies
        ]);
    }

    /**
     * @Route("/{id}/edit", name="movie_list_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MovieList $movieList): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($movieList->getUserId() != $this->getUser()) {
            throw $this->createNotFoundException('The list does not exist');
        }

        $form = $this->createForm(MovieListType::class, $movieList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movie_list_index');
        }

        return $this->render('movie_list/edit.html.twig', [
            'movie_list' => $movieList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_list_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MovieList $movieList): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($movieList->getUserId() != $this->getUser()) {
            throw $this->createNotFoundException('The list does not exist');
        }

        if ($this->isCsrfTokenValid('delete'.$movieList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($movieList);
            $user = $this->getUser();
            $user->removeMovieList($movieList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('movie_list_index');
    }

    /**
     * @Route("/{id}/movie", name="movie_list_add", methods={"POST"})
     */
    public function addMovie(Request $request, MovieList $movieList): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$movieList->getUserId() === $this->getUser())
        {
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            $response->setContent(json_encode([
                'error' => "Forbidden",
            ]));
            return $response;
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $data = json_decode($request->getContent());

        // Verify Data
        if (!$data || !ctype_digit($data->movieId))
        {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->setContent(json_encode([
                'error' => "Bad parameters",
            ]));
            return $response;
        }        

        // Verify if movie already present in list
        if ($movieList->isMovieInList($data->movieId) == true)
        {
            $response->setStatusCode(Response::HTTP_CONFLICT);
            $response->setContent(json_encode([
                'error' => "This movie already exists",
            ]));
            return $response;
        }
        $movieList->addMovie($data->movieId);
        $entityManager = $this->getDoctrine()->getManager();
        $this->getDoctrine()->getManager()->flush();
        $response->setContent(json_encode([
            'data' => "Movie added successfully!",
        ]));
        return $response;
    }

    /**
     * @Route("/{id}/movie", name="movie_list_remove", methods={"DELETE"})
     */
    public function removeMovie(Request $request, MovieList $movieList)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$movieList->getUserId() === $this->getUser())
        {
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            $response->setContent(json_encode([
                'error' => "Forbidden",
            ]));
            return $response;
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $data = json_decode($request->getContent());

        // Verify Data
        if (!$data || !ctype_digit($data->movieId))
        {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->setContent(json_encode([
                'error' => "Bad parameters",
            ]));
            return $response;
        } 
        
        // Verify if movie already present in list
        if ($movieList->isMovieInList($data->movieId) == false)
        {
            $response->setStatusCode(Response::HTTP_CONFLICT);
            $response->setContent(json_encode([
                'error' => "This movie is not in the list",
            ]));
            return $response;
        }

        $movieList->removeMovie($data->movieId);
        $entityManager = $this->getDoctrine()->getManager();
        $this->getDoctrine()->getManager()->flush();
        $response->setContent(json_encode([
            'data' => "Movie removed successfully!",
        ]));
        return $response;
    }
}
