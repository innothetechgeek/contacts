<?php
/**
 * Created by PhpStorm.
 * User: KhuselaM
 * Date: 2020-05-17
 * Time: 00:36
 */
 namespace models;
 use core\database\model;
class Contacts extends Model
{

  protected $table =  'movie';
    public function __construct()
    {
        $table = 'movie';
        parent::__construct($table);
    }
}
