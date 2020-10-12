<?php

namespace controllers;
use models\contact;
use core\database\fecade\DB;


class Api{

    public function getContacts(){
        
        $contacts = DB::table('contacts')
                      ->paginate(50);

        $data = [ 
            'results' => $contacts->results,
            'pagination_links' => $contacts->get_pagination_links()
        ];

        echo json_encode($data);

    }

    public function testPagination(){
      
        $path = ROOT . DS . 'views' . DS . $name .'.php';

    }

    public function getTimeZones($time_zone = ""){
        
        $times_zones = DB::table('contacts')
                  ->select('tz','id')
                  ->selectRaw('CONCAT(title ," ", first_name," ", last_name, " ", email) as contact')                
                  ->where("tz", $time_zone)
                  ->get();

        echo json_encode($times_zones);
    }

}