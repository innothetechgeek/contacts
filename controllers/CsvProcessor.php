<?php

namespace controllers;
use models\contact;
use core\Image;

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

        foreach($data as $index => $record){
            
            $emailAddressDomain = explode("@",$record['email'])[1];

            if($this->emailAdressDomainIsValid($emailAddressDomain)){

                $date = date_create($record['date']);
                $date = date_format($date,"Y-m-d ").$record['time'];

                $contact = new Contact();
                $contact->id = $record['id'];
                $contact->title =  $record['title'];
                $contact->first_name = $record['first_name'];
                $contact->last_name = $record['last_name'];
                $contact->email = $record['email']; 
                $contact->date =  $date;
                $contact->note = $record['note'];
                $contact->image =  $this->createContactCrd($contact);
               // echo '<img src = "'.$contact->image.'"><br/>';
                $contact->email_ip = gethostbyname($emailAddressDomain);
                $contact->save();

            }
        }   
    }

    public function createContactCrd($contact){

        $image_options = [
            "imageWidth" => 216,
            "imageHeight" => 100, 
            "red-color" => 0,
            "green-color"=> 153,
            "blue-color" => 0
        ];

       $text_options["lines"] = [
            
            "line1"=>[
                "text" => $contact->first_name." ".$contact->last_name,
                "position" => '5, 15, 25'
            ],

            "line2" => [
                "text" => "tracy@yahoo.com",
                "position" => '5, 15, 45'
            ],          
            
        ];

        $image = Image::createContactCard($image_options,$text_options);
         echo '<img src = "data:image/jpg;base64,'.base64_encode($image).'">';
        return 'data:image/jpg;base64,'.base64_encode($image);
        //echo '<img src = "data:image/jpg;base64,'.base64_encode($image).'"';
    }

    public function validateEmailAddressDomain($domain){

        return checkdnsrr($domain,"MX");        

    }

    public function emailAdressDomainIsValid($emailAddress){

        return $this->validateEmailAddressDomain($emailAddress)  == true;

    }

    public function CreateContactCard(){

    }


}