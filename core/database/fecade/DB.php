<?php
namespace core\database\fecade;
use  core\database\fecade\Fecade;
use core\database\QueryBuilder;

class DB extends Fecade{

  public static function getFecadeAccessor(){
      return 'core\database\QueryBuilder';
  }
}
