<?php

namespace core\database;
class Connection{

  private static $connection;
  public function __construct(){

      try{
        if(self::$connection == null) {
          self::$connection = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASSWORD);
          self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

      }catch(PDOException $e){

          die($e->getMessage());

      }

  }

    public function statement($query, $bindings = [])
    {
             var_dump($query);
            $statement = self::$connection->prepare($query);

            foreach ($bindings as $key=>$val) {
             
              if(!empty($val)) $statement->bindValue($key,$val);              
                
            }
            extract($bindings);
            
            return $statement->execute();                    
            
    }

    public function bindValues($statement, $bindings)
    {
        foreach ($bindings as $key => $value) {
            $statement->bindParam(":$key", $value);
        }
    }

    public function insert($sql,$bindings){
        
        return $this->statement($sql, $bindings);
       
    }

    public function get($sql){

        $stmt = self::$connection->prepare($sql);
        $stmt->execute();
        return  $stmt->fetchAll(\PDO::FETCH_OBJ);

    }

    function lastInsertId(){
        return self::$connection->lastInsertId();
    }

    public function getTableColumns($sql){

        $columns = [];
        foreach(self::$connection->query($sql)->fetchAll() as $key => $value){
            $columns[] = $value['Field'];
        }
        return $columns;

    }

}
?>
