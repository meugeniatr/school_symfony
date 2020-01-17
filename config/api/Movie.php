<?php
namespace Api;
use Api\Api;

class Movie extends Api
{
    public $director;
    public $cast = array();
    public function __construct($id)
    {
        $infos = $this->results("movie", $id);
        foreach($infos as $key=>$data){
            $this->{$key} = $data;
        }
        $team = $this->results("movie", $id, "credits");
        foreach($team->crew as $person){
            if($person->job == "Director")
                $this->director = $person;
        }
        $this->cast = $team->cast;
    }
}
