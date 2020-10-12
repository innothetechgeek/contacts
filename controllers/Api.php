<?php

namespace controllers;
use models\contact;
use core\database\fecade\DB;
use core\rest\Rest;


class Api{

    public function getContacts(){ 
        
        $per_page = 50;
        $contacts = DB::table('contacts')
                      ->paginate($per_page,'ajax');

        $data = [ 
             'results' =>  $contacts->results,
            'pagination_links' => $contacts->get_pagination_links()
        ];

        echo json_encode($data);

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