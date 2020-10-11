<?php
/**
 * Created by PhpStorm.
 * User: KhuselaM
 * Date: 2020-05-17
 * Time: 00:36
 */
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
