<?php

namespace controllers;

class CsvProcessor{

    public function getData(){
        
        $file = fopen(FILES_DIR."/MOCK_DATA.csv","r");

        $records = array();
        $header =  fgetcsv($file);

        while ($row = fgetcsv($file)) {

          $records[] = array_combine($header, $row);

        }
       
        return $records;

    }

    public function saveData(){
        
        $data = $this->getData();
        var_dump( $data);
        

    }

    public function validateEmailAddress(){

    }

    public function emailAdressIsValid($mailAddress){

        return $this->validateEmailAddress($emailAddress)  == true;

    }

    public function CreateContactCard(){

    }


}