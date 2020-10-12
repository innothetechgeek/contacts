<?php

namespace controllers;
use models\contact;
use core\database\fecade\DB;

class Api{

    public function getContacts(){
        
        $contacts = contact::all();
        
        echo json_encode($contacts);

    }

    public function getTimeZones($time_zone){
        
        $times_zones = DB::table('contacts')
                  ->select('tz','id')
                  ->selectRaw('CONCAT(title ," ", first_name," ", last_name, " ", email) as contact') 
                  ->selectRaw('count(*) as total_contacts')                 
                  ->where("tz", $time_zone)
                  ->groupBy('tz','contact')
                  ->get();

        echo json_encode($times_zones);
    }

}