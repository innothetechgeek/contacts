<?php
namespace controllers;
use models\contacts;
use core\database\fecade\DB;

class Home{

    function index(){

        $movies = contacts::all();

        $movie = new Contacts();
        $movie->mv_title = 'Hello JHB';
        $movie->mv_year_released = '2020-10-10';
        $movie->save();
        var_dump($movies);
        $movies = DB::table('movie')
                  ->select('mv_id','mv_title','mv_year_released')->get();

                  
    }
}