<?php

namespace controllers;
use models\contact;

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
        
      //  $contact = contact::find(1);
       
        $data = $this->getData();
        var_dump($data);
        foreach($data as $index => $value)
        $contact = new Contact();
        $contact->id = 11;
        $contact->first_name = "Nickie";
        $contact->last_name = "Bella";
        $contact->email = "nickabella@wwe.com";
       // $contact->save();        

    }

    public function validateEmailAddress(){

    }

    public function emailAdressIsValid($mailAddress){

        return $this->validateEmailAddress($emailAddress)  == true;

    }

    public function CreateContactCard(){

    }


}