<?php

use core\Router;

    Router::get('contact',function(){
        echo 'hello word';
    });

    Router::get('','home@index');
    Router::get('get-raw-csv-data','CsvProcessor@getData');
    Router::get('process-csv','CsvProcessor@saveContacts');
    Router::get('contacts','Api@getContacts');
    Router::get('timezones','Api@getTimeZones');

?>











