<?php
namespace core\database;
use core\database\QueryBuilder;
class Grammer{
  /**
   * The components that make up a select clause.
   *
   * @var array
   */
  protected $selectComponents = [
      'aggregate',
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
   //select
    public function compileInsert(QueryBuilder $quer_builder,$values){


       $table = $quer_builder->from;
       $columns = array_filter($values);

        $columns = implode(array_keys($columns),",");

        $insert_values = "(".$this->constructInsertValues($values).")";

        return "insert into $table ($columns) values $insert_values";

    }

    public function compileShowColumns(QueryBuilder $quer_builder){
       $table = $quer_builder->from;
       return "SHOW COLUMNS FROM {$table}";
    }

    public function constructInsertValues($values){
        $values = array_filter($values);
        $insert_values = "";

        foreach ($values as $column => $value) {
            $insert_values .= "'$value',";
        }

        return rtrim($insert_values,',');

    }

    //compile selector
    public function compileSelect(QueryBuilder $query_builder){

      $columns = $this->compileColumns($query_builder);
      return "select ".$this->concatenateQueryComponents($this->compileQueryComponents($query_builder));

    }

    public function compileColumns($query_builder){
       if(is_null($query_builder->columns)) return '*';
       return implode($query_builder->columns,",");
    }

     /**
     * Compile the "limit" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $limit
     * @return string
     */
    protected function compileLimit(QueryBuilder $query, $limit)
    {
        return 'limit '.(int) $limit;
    }

    /**
     * Compile the "offset" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $offset
     * @return string
     */
    protected function compileOffset(QueryBuilder $query, $offset)
    {
        return 'offset '.(int) $offset;
    }

    public function compileQueryComponents(QueryBuilder $query_builder){

       foreach($this->selectComponents as $component){
         $sql = [];

         foreach ($this->selectComponents as $component) {
             if (isset($query_builder->$component)) {

                 $method = 'compile'.ucfirst($component);
                 $sql[$component] = $this->$method($query_builder, $query_builder->$component);
             }
         }
         return $sql;
       }
    }

    public function compileFrom($query_builder){
        return "from $query_builder->from";
    }

    public function compileWheres($query_builder){

      if (empty($query_builder->wheres)) {
          return '';
      }
      $wheres = $query_builder->wheres;
      return "where {$wheres['column']} {$wheres['operator']} '{$wheres['value']}'";
    }
    /**
     * Remove the leading boolean from a statement.
     *
     * @param  string  $value
     * @return string
     */
    protected function removeLeadingBoolean($value)
    {
        return preg_replace('/and |or /i', '', $value, 1);
    }

    public function concatenateQueryComponents($queryComponetnts){
        return implode(' ', array_filter($queryComponetnts));
    }

    public function compileJoins($query_builder,$join){
        $join_statement = "";
        foreach($join as $join_obj){

          foreach($join_obj->wheres as $wheres_arr){
            $join_statement .= " {$join_obj->type} join $join_obj->table on $wheres_arr[first] $wheres_arr[operator] $wheres_arr[second]";
          }

        }
        return $join_statement;
    }

    protected function compileGroups(Builder $query, $groups)
    {
        return 'group by '.implode($groups,',');
    }

}
?>
