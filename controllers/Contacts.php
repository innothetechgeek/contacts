<?php

namespace controllers;
use models\contact;
use core\Image;
use core\rest\Rest;

class Contacts{

    public function getContacts(){
        
        $file = fopen(FILES_DIR."/MOCK_DATA.csv","r");

        $records = array();
        $header =  fgetcsv($file);

        while ($row = fgetcsv($file)) {

          $records[] = array_combine($header, $row);

        }
       
        return $records;

    }

    public function saveContacts(){

        ini_set('max_execution_time', 500);
        $data = $this->getContacts();

        foreach($data as $index => $record){
            
            $email_address_domain = explode("@",$record['email'])[1];

            if($this->emailAdressDomainIsValid($email_address_domain)){

               $this->createContact($record,$email_address_domain);              

            }
        }
        $this->postContactsToRemoteUrl();   
    }

    public function createContact($record,$emailAddressDomain){       

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
        $contact->tz = $record['tz'];
        $contact->image =  $this->createContactCard($contact);
        $contact->email_ip = gethostbyname($emailAddressDomain);

        $contact->save();        

    }

    public function postContactsToRemoteUrl(){

        $contacts = [];
        $contactsObj = contact::all();
        
        foreach($contactsObj as $contact){

            $contacts['users'] = [
                 
                "id" => $contact->id,
                "title" => $contact->title,
                "first_name"=>$contact->first_name,
                "last_name" => $contact->last_name ,
                "email" => $contact->email,
                "email" => $contact->email,
                "note" => $contact->note ,
                "image" => $contact->image,
                "email_ip" => $contact->email_ip,
                
            ];

        }

        $contacts = json_encode($contacts);
       
        
        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($contacts)
        ];      
        
        return Rest::post("https://postman-echo.com/post", $contacts,$headers);

    }

    public function createContactCard($contact){

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
        return 'data:image/jpg;base64,'.base64_encode($image);
        //echo '<img src = "data:image/jpg;base64,'.base64_encode($image).'"';
    }

    public function validateEmailAddressDomain($domain){

        return checkdnsrr($domain,"MX");        

    }

    public function emailAdressDomainIsValid($emailAddress){

        return $this->validateEmailAddressDomain($emailAddress)  == true;

    }

    public function list(){
      
        $path = ROOT . DS . 'views' . DS . 'list_contacts.php';

        include( $path);
        

    }

}