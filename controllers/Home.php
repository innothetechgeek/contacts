<?php
namespace controllers;
use models\contact;
use core\database\fecade\DB;

class Home{

    function index(){

        $contacts = contact::all();

        var_dump($contacts);
        // $movies = DB::table('movie')
        //           ->select('mv_id','mv_title','mv_year_released')->get();

                  
    }
}