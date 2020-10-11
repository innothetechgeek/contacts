<?php

/**
 * Created by PhpStorm.
 * User: KhuselaM
 * Date: 2019-03-31
 * Time: 01:24
 */
namespace core;

class Router
{
    private static $valid_routes = [];

    //registers a new post route with the router
    public static function get($uri,$action){
      array_push(self::$valid_routes,Array(
         'uri'    =>  $uri,
         'action' =>  $action,
         'method' => 'GET'
      ));
    }

    //registers a new get route with the router
    public static function post($uri,$action){
      array_push(self::$valid_routes,Array(
         'uri'    =>   $uri,
         'action' =>  $action,
         'method' =>  'POST',
      ));
    }

    //route request to relevent class and method
    public static function route($uri){

      unset($uri[2]);
     /*loop through the list of valid routes, check if url matches a route in the list of valid routes
          if request url matches method and class, call relevent method and class */
      $route_found = false;
      foreach (self::$valid_routes as $route) {

        //if path in the list of valid routes matches current request path, call relevent class and method
        if(self::request_match_route($route,$uri)){

            $route_found = true;
            $class_method = explode('@',$route['action']);
            self::map_to_class_and_method($class_method,$uri);

            break;
        }

      }
    
      if(!$route_found) die('page not found');
    }

  //map request to relavant class and method
  private static function map_to_class_and_method($class_method,$uri){
        
        if(empty($uri)){
          $class =  'Home';
            $method =  "index";
        }else{
          $class = $class_method[0];
          $method =  $class_method[1];
        }

        $class = "\controllers\\".ucfirst($class);
        $controller_obj = new $class($class,$method);
        $queryParams = [];

        if(method_exists($class,$method)){
            call_user_func_array([$controller_obj,$method],$queryParams);
        }else{
            die('That method does not exist in the controller '.$class);
        }
  }

   private static function request_match_route($route,$url){
   
      return $route['uri'] == implode($url,'/');
   }

    public function dispatch_class_(){

    }

    public static function redirect($location){
        //dnd(ROOT."/".$location)
        if(ENVIROMENT == 'Development'){
          $location = "http://".$_SERVER['HTTP_HOST']."/". strtolower(SITE_NAME).'/'.$location;
        }else{
          $location =  "http://".$_SERVER['HTTP_HOST']."/".$location;
        }

        header('Location: '.$location);
    }

    public static function getMenu($menu){

        $menu_arr = [];
        $menu_file = file_get_contents(ROOT.DS.'app'. DS . $menu. '.json');
        $acl = json_decode($menu_file,true);
        foreach ($acl as $key => $val){
            if(is_array($val)){
                $sub = [];
                foreach ($val as $k=>$v){
                    if($k == 'seperator' && !empty($sub)){
                        $sub[$k] = '';
                        continue;
                    }else if($final_val = self::getLink($v)){
                        $sub[$k] = $final_val;
                    }
                }
                if(!empty($sub)){
                    $menu_arr[$key] = $sub;
                }
            }else{
                if($final_val = self::getLink($val )){
                    $menu_arr[$key] = $final_val;
                }
            }
        }
        return $menu_arr;
    }

    private static function getLink($val){
        if(preg_match('/https?:\/\//',$val) ==1){
            return $val;
        }else{
            $u_array = explode(DS,$val);
            $controller_name = ucwords($u_array[0]);
            $action_name = (isset($u_array[1])) ? $u_array[1] : '';
            if(self::hasAccess($controller_name,$action_name)){
                return $val;
            }
            return false;
        }
    }

    public static function hasAccess($controller,$method_name = "index"){

        return true;
        $controller = ucfirst($controller);
        $acl_file = file_get_contents(ROOT . DS . 'app' . DS . "acl.json");
        $acl = json_decode($acl_file,true);
        $current_user_acls = ["Guest"];
        $grantAccess = false;

        if(Session::exists(CURRENT_USER_SESSION_NAME)){
            $current_user_acls[] = "LoggedIn";
            foreach($current_user_acls()->acl() as $acl){
                $current_user_acls = $acl;
            }
        }

        foreach($current_user_acls as $level){
            //dnd($acl[$level]);
            if(array_key_exists($level,$acl) && array_key_exists($controller,$acl[$level])){
                if(in_array($method_name,$acl[$level][$controller]) || in_array("*",$acl[$level][$controller])){
                    $grantAccess = true;
                    break;
                }
            }
        }

        //check for denied
        foreach($current_user_acls as $level){
            $denied = $acl[$level]['denied'];
            if(!empty($denied) && array_key_exists($controller,$denied) && in_array($method_name,$denied[$controller])){
                $grantAccess = false;
                break;
            }
        }

        return $grantAccess;
    }

}
