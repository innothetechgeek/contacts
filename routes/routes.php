<?php

use core\Router;

    Router::get('contact',function(){
        echo 'hello word';
    });

    Router::get('','home@index');
    Router::get('get-raw-csv-data','Contacts@getData');
    Router::get('process-csv','Contacts@saveContacts');
    Router::get('contacts','Api@getContacts');
    Router::get('timezones','Api@getTimeZones');

    Router::get('list','Contacts@list');

?>