<?php

use core\Router;

Router::get('contact',function(){
    echo 'hello word';
});

Router::get('','home@index');
Router::get('get-raw-csv-data','CsvProcessor@getData');
Router::get('get-csv-data','CsvProcessor@saveData');

?>











