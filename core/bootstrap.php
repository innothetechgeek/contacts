<?php
/**
 * Created by PhpStorm.
 * User: KhuselaM
 * Date: 2019-03-28
 * Time: 22:10
 */
 use core\Router;
 
 //load configuration and helper functions
  function url($path){
    if(ENVIROMENT == 'Development'){
        echo "http://".$_SERVER['HTTP_HOST']."/". strtolower(SITE_NAME).'/'.$path;
    }else{
        echo "http://".$_SERVER['HTTP_HOST']."/".$path;
    }
  }
 require_once(ROOT . DS . 'config' . DS . 'config.php');
   //Autoload classes
     /*load classes automantically...
        the class name will be passed to this method as a parameter when the class is being instantiated
     */     
    spl_autoload_register(function($className){
        $className = str_replace("\\", '/' ,$className);
        require_once($className.'.php');
    });

    require_once(ROOT . DS . 'routes' . DS .'routes.php');
    
    $request_url  = isset($_SERVER['PATH_INFO']) ? explode('/',ltrim($_SERVER['PATH_INFO'],'/')) : [];
    //Route Requests
    Router::route($request_url);
