<?php
namespace core\database;
use core\Paginator;

class QueryBuilder{

    public $columns;
    public $from;
    public $model;

    public function __construct(){

        $this->connection = new Connection();
        $this->grammer = new Grammer();
    
    }

        
    public $groups;

    public $wheres = [];


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
     
    function paginate($per_page){

        $total = $this->getCountForPagination();
        $page =isset($_GET['page']) ? $_GET['page'] : 1;
        $results = $total ? $this->forPage($page, $per_page)->get() : [];
        
        return new Paginator($total,$per_page,'',$results);
    }

    public function getCountForPagination(){
        
        return count($this->get());
    }

    //calculate offset and limit
    public function forPage($page, $perPage = 15)
    {   
        return $this->setOffset(($page - 1) * $perPage)->setLimit($perPage);
    }

    //set limit for the query (how many records to return)
    public function setLimit($value)
    {

        $this->limit = $value;

        return $this;

    } 

    //sets offset for the page (how many records to skip)
    public function setOffset($value){
        
            $this->offset = $value;

            return $this;

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
        $this->connection->insert($insert_statement,$values);
  
    }

    /**
     *
     */
    public function find($value){

        return $this->where($this->model->primary_key,'=', $value)->get();

    }

    /**
   * Add a new "raw" select expression to the query.
    */
    public function selectRaw($column){

        $this->bindings['select'] = [];
        $columns = is_array($column) ? $column : func_get_args();

        foreach ($columns as $as => $column) {

            $this->columns[] = $column;

        }

        return $this;

    }

    public function groupBy(...$groups)
    {

        foreach ($groups as $group) {

            $this->groups = array_merge(
                (array) $this->groups,
                $groups
            );
        }

        return $this;
    }

        /**
     * adds where clause to a query
     */
    public function where($column, $operator = null, $value = null , $boolean = 'and'){

        $value  = func_num_args() === 2 ? $operator : $value;
        $operator  = func_num_args() === 2 ? '=' : $operator;

        //add value and operator to the where's array
        $this->wheres = compact("operator","value","column");

        return $this;

    }
}