<?php
namespace core\database\fecade;
use core\database\QueryBuilder;

class Fecade{

  public static function __callStatic($name,$args){
      return self::resolve_instance(static::getFecadeAccessor())->$name($args);
  }

  public static function getFecadeAccessor(){

  }

  private static function resolve_instance($fecade){

      return new $fecade();
  }

}
?>
