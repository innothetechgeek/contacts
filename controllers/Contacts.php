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

        $contacts = $this->getContacts();

        foreach($contacts as $index => $record){
            
            $email_address_domain = explode("@",$record['email'])[1];

            if($this->emailAdressDomainIsValid($email_address_domain)){

               $this->createContact($record,$email_address_domain);              

            }
        }
        $this->postContactsToRemoteUrl();   
    }

    public function createContact($contact_arr,$emailAddressDomain){       

        $date = date_create($contact_arr['date']);
        $date = date_format($date,"Y-m-d ").$contact_arr['time'];

        $contact = new Contact();
        $contact->id = $contact_arr['id'];
        $contact->title =  $contact_arr['title'];
        $contact->first_name = $contact_arr['first_name'];
        $contact->last_name = $contact_arr['last_name'];
        $contact->email = $contact_arr['email']; 
        $contact->date =  $date;
        $contact->note = $contact_arr['note'];
        $contact->tz = $contact_arr['tz'];
        $contact->image =  $this->createContactCard($contact);
        $contact->email_ip = gethostbyname($emailAddressDomain);

        $contact->save();        

    }

    public function postContactsToRemoteUrl(){

        //array to hold contacts from the database
        $contacts = [];

        //retrive contacts from the database
        $contactsObj = contact::all(); 
        
        foreach($contactsObj as $contact){

            $contacts['contacts'] = [
                 
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
        
        //request headers
        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($contacts)
        ];      
        
        //sends post request to a remote url
        return Rest::post("https://postman-echo.com/post", $contacts,$headers);

    }

    public function createContactCard($contact){

        //options for the card image
        $image_options = [
            "imageWidth" => 216,
            "imageHeight" => 100, 
            "red-color" => 0,
            "green-color"=> 153,
            "blue-color" => 0
        ];

        //options for the text that appears on the contact card
       $text_options["lines"] = [
            
            "line1"=>[
                "text" => $contact->first_name." ".$contact->last_name,
                "position" => '5, 15, 25'
            ],

            "line2" => [
                "text" => $contact->email,
                "position" => '5, 15, 45'
            ],          
            
        ];

        $image = Image::createContactCard($image_options,false,$text_options);

        return 'data:image/jpg;base64,'.base64_encode($image);
       
    }

    public function validateEmailAddressDomain($domain){

        return checkdnsrr($domain,"MX");        

    }

    public function emailAdressDomainIsValid($emailAddress){

        return $this->validateEmailAddressDomain($emailAddress)  == true;

    }

    public function list(){
      
        $view = ROOT . DS . 'views' . DS . 'list_contacts.php';

        include( $view);
        

    }

}