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
        
        foreach($data as $index => $record){

            $date = date_create($record['date']);
            $date = date_format($date,"Y-m-d ").$record['time'];

            $contact = new Contact();
            $contact->id = $record['id'];
            $contact->first_name = $record['first_name'];
            $contact->last_name = $record['last_name'];
            $contact->email = $record['email']; 
            $contact->date =  $date;
            $contact->note = $record['note'];

            $contact->save();
            
        }   

    }

    public function validateEmailAddress(){

    }

    public function emailAdressIsValid($mailAddress){

        return $this->validateEmailAddress($emailAddress)  == true;

    }

    public function CreateContactCard(){

    }


}