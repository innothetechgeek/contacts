<?php
 namespace models;

 use core\database\model;

class Contact extends Model
{

  protected $table =  'contacts';
  public $primary_key = 'id';
    public function __construct()
    {
        $table = 'contact';
        parent::__construct($table);
    }
}