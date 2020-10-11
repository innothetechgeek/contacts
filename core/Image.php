<?php

namespace core;

class Image{


    public static function createContactCard($image_options = [],$text_options = []){

        if(empty($image_options)) $image_options = [
            "imageWidth" => 216,
            "imageHeight" => 100, 
            "red-color" => 0,
            "green-color"=> 153,
            "blue-color" => 0
        ];

        $text_color = empty($text_color) ?  "255, 255, 255" : $text_color;
        return self::createImage($image_options,$text_color, $text_options);
       
       // return  $image;

    }

    public static function createImage($image_options,$text_color = [], $text_options =[],$type = "jpeg"){

         // Create the size of image or blank image 
         $image = imagecreate($image_options['imageWidth'], $image_options['imageHeight']); 
        
         // Set the background color of image 
         $background_color = imagecolorallocate($image, $image_options['red-color'], $image_options['green-color'], $image_options['blue-color']); 
         
         if(!empty($text_options)){

            // Set the text color of image 
            $text_color = explode(",",$text_color);
            $text_color = imagecolorallocate($image, $text_color[0], $text_color[1], $text_color[2]); 
            
            foreach($text_options as $lines => $line_arr){
                
                foreach($line_arr as $line){
                    
                    $position = explode(",",$line['position']);
                    $text = $line['text']; 
    
                    // Function to create image which contains string.
                    imagestring($image, $position[0],$position[1], $position[2] , $text, $text_color);
                    
                }
            }            

         }        
                 
         ob_start();
         $function = "image$type";
         $function($image);
         $image_data = ob_get_contents();
         ob_end_clean();
         return $image_data;
         
    }

}