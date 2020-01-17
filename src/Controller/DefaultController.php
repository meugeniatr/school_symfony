<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Api;


class DefaultController extends AbstractController
{
    /**
    * @Route("/", name="index")
    */
    public function index()
    {
        $api = new Api\Api();

        $params = ["sort_by" => "popularity.desc"];
        $resultats = $api->results("discover", "movie", null, $params);
        $mostPopular = array_slice($resultats->results, 0, 11);

        $params = ["primary_release_date.gte" => date("Y-m-d", strtotime("first day of previous month")), "primary_release_date.lte" => date("Y-m-d")];
        $resultats = $api->results("discover", "movie", null, $params);
        $inTheaters = array_slice($resultats->results, 0, 11);

        $params = ["primary_release_year" => "2019", "sort_by" => "vote_average.desc"];
        $resultats = $api->results("discover", "movie", null, $params);
        $bestOfYear = array_slice($resultats->results, 0, 11);

        return $this->render('index.html.twig', [
            "mostPopular" => $mostPopular,
            "inTheaters" => $inTheaters,
            "bestOfYear" => $bestOfYear
        ]);
    }
}