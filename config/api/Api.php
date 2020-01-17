<?php
namespace Api;
require_once("Movie.php");

class Api{
    protected $key = "1210f19ac87961b089308e4742b1a4b4";
    protected $baseUrl = "https://api.themoviedb.org/3/";
    public function results($type, $id, $method = null, $params = []) {
        $url = $this->baseUrl . $type."/".$id;
        ($method != null) ? $url .= "/".$method : $url;
        $url .= "?api_key=".$this->key."&language=en-US";

        foreach($params as $param => $value){
            $url.= "&".$param."=".$value;
        }

        $raw = file_get_contents($url);
        return json_decode($raw);
    }

    public function search($query, $year = null, $page = "1"){
        $params = ["query" => $query];
        if($year != null) $params = array_merge($params, ["primary_release_year" => $year]);
        $params = array_merge($params, ["page" => $page]);
        return $this->results("search", "movie", null, $params);
    }

    public function listGenres(){
        return $this->results("genre", "movie", "list");
    }


    public function findPerson($query){
        $person = $this->results("search", "person", null, ["query" => $query])->results;
        if(!empty($person)) return $person[0];
        return null;
    } 
}
