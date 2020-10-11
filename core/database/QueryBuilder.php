<?php
namespace core\database;

class QueryBuilder{

    public $columns;
    public $from;
    public $model;

    public function __construct(){

        $this->connection = new Connection();
        $this->grammer = new Grammer();
    
    }

     /**
   * The components that make up a select clause.
   *
   * @var array
   */
    protected $selectComponents = [
        'columns',
        'from',
        'joins',
        'wheres',
        'groups',
        'havings',
        'orders',
        'limit',
        'offset',
        'lock',
    ];


    //columns to be selected, uses * as a default
    public function select($columns){
        
      $this->columns = [];
      $columns = is_array($columns) ? $columns : func_get_args();

      foreach ($columns as $as => $column) {

            $this->columns[] = $column;

      }

      return $this;
    }

    public function table($table){

        $table = $table[0];
    
        $newBuilder = new QueryBuilder();
        $newBuilder->from = $table;
    
        return $newBuilder;
    
    }

    //set model, set 'from' to the name of the model table being queried
    public function setModel(Model $model){

        $this->model = $model;
        $this->from = $model->getTable();
  
    }

    public function get(){

        if(is_null($this->columns)) $this->columns = ['*'];
        $select_statement = $this->grammer->compileSelect($this);
   
       
        $result = $this->connection->get($select_statement);     
   
        if(!empty($this->model)){
   
           return $this->return_results_objects($result);

         }else{
   
           return $this->return_result_as_array($result);
         }
   
      }

    /**
    * return query results as objects
    */
    public function return_results_objects($result){

        $objectsArr = [];
        foreach($result as $result){
            $obj = new $this->model();
            $obj->populate_object_data($result);
            $objectsArr[] = $obj;
        }

        return $objectsArr;

    }

    /**
     * return query results an array
     */
    public function return_result_as_array($result_obj){

        $results_ = [];
        foreach($result_obj as $results_arr => $result_arr){

            foreach($result_arr as $key => $val){
                $results_[$results_arr][$key] = $val;
            }

            }
        return $results_;

    }

    /**
     *  get all column names for a given table
     */
    public function getTableColumns(){

        $show_columns_statement = $this->grammer->compileShowColumns($this);
        return $this->connection->getTableColumns($show_columns_statement);

    }

    public function insert($values){

        $insert_statement = $this->grammer->compileInsert($this,$this->model->get_fields());
        $this->connection->insert($insert_statement);
  
    }
}