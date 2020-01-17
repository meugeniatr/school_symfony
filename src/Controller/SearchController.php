<?php
namespace App\Controller;

use App\Form\SearchType;
use \TMDB\Client;
use \TMDB\structures\Genre;
use Api;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"GET"})
     */
    public function searchForm()
    { 
        $form = $this->createForm(SearchType::class);
        return $this->render('search/form.html.twig', [
            "searchForm" => $form->createView()
        ]);
    }
    /**
     * @Route("/search/1/", name="searchResultsRedirection", methods={"POST"})
     */
    public function searchResultsRedirection()
    { 
        return $this->redirect($this->generateUrl("searchResults", array(
            'page' => "1",
            'title' => $_POST['search']['title'],
            'yearStart' => $_POST['search']["yearStart"]["year"],
            'yearEnd' => $_POST['search']["yearEnd"]["year"]
        )));
    }
    /**
     * @Route("/search/{page}/{title}/{yearStart}/{yearEnd}", name="searchResults", methods={"GET"})
     */
    public function searchResults($page, $title, $yearStart, $yearEnd)
    { 
        $resultats = array();
        $api = new Api\Api();
        if (!empty($yearStart) AND ($yearStart != "1895" OR $yearEnd != "2025")) // if specific date is set
        {
            for($year = $yearStart; $year <= $yearEnd; $year++){
                $resultats = array_merge($resultats, $api->search($title, $year)->results);
            }
            $totalPage = ceil(count($resultats)/20);
            $resultats = array_slice($resultats, ($page-1)*20, 20);
        }
        else
        {
            $resultats = $api->search($title, null, $page);
            $totalPage = $resultats->total_pages;
            $resultats = $resultats->results;
        }
        $form = $this->createForm(SearchType::class);
        return $this->render('search/searchResults.html.twig', [
             "searchForm" => $form->createView(),
             "films" => $resultats,
             "curPage" => $page,
             "totalPage" => $totalPage,
             "yearStart" => $yearStart,
             "yearEnd" => $yearEnd, 
             "title" => $title
         ]);
    }

        
    /**
     * @Route("/discover", name="discover", methods={"GET"})
     */
    public function discoverForm()
    { 
        $form = $this->createForm(SearchType::class);
        return $this->render('search/discover.html.twig', [
            "searchForm" => $form->createView()
        ]);
    }

    /**
     * @Route("/discover", name="discoverResults", methods={"POST"})
     */
    public function discoverResults()
    { 
        $api = new Api\Api();
        $params = array();
        $search = $_POST['search'];
        if(!empty($search['crew']))
            $person = $api->findPerson($search['crew']);
        if(!empty($person))
            $personId = $person->id;
        if(!empty($personId)){
            $params = array_merge($params, ['with_people' => $personId]);
        }
        $genres = implode(",", $search['genre']);
        if(!empty($genres)){
            $params = array_merge($params, ["with_genres" => $genres]);
        }
        $resultats = $api->results("discover", "movie", null, $params);
        $form = $this->createForm(SearchType::class);
        return $this->render('search/discoverResults.html.twig', [
            "searchForm" => $form->createView(),
            "films" => $resultats->results
        ]);
    }
}