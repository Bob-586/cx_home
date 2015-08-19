<?php
namespace cx\model;
use cx\database\model as the_model;

class testing extends the_model {

  protected $table = 'test';
  protected $key = 'id';
  
  public function __construct($options = array()) {
    $options['table'] = $this->table;
    $options['key'] = $this->key;
    parent::__construct($options);
  }
  
  public function caching() {
    return false;
  }
  
  public function get_test($id) {
    $table = 'test';
    $options['fields'] = "*";
    $options['fetch'] = "row";
    
    $bind = array(":id" => $id);
    $options['where'] = "`id` = :id";
    
    $data = $this->database->select($table, $options, $bind); 
    return $data['data']; 
  }
 
}
