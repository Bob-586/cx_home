<?php 

/**
 * @copyright (c) 2015
 * @author Chris Allen, Robert Strutts 
 */

// http://dev/cx/?route=/app/testing

/* The following are devlopment sites, will show error messages
 * URL : http://dev
 * URL : http://project.local
 * URL : http://localhost
 * URL : http://testing
 * URL : http://test
 * URL : http://local
 * URL : http://127.0.0.1
 */

/* The following are production testing web sites, will not show error, instead shows error page
 * URL: http://live
 * URL: http://project
 * URL: http://production
 * URL: http://prod
 * URL: http://live
 */

class cx_loader_app_testing extends cx\app\app {

  public function __construct() {
    $this->set_footer("&copy; Copyright 2014-" . date('Y') . ". The Bishop's");
    parent::__construct();
  }
  
  public function index() {
    $id = cx\app\static_request::init('get', 'id');
    if ($id->is_not_set()) {
      echo "Invalid id!";
      exit;
    }
   
    $this->load_model('app' . DS . 'testing');
    $db_options = array();
    $test = new cx\model\testing($db_options);
    
    if ($id->is_not_valid_id()) {
      // no existing data
      $model = array(); 
    } else {
      $test->load($id->to_int());
      $model = $test->get_members();      
    }      

    if (cx\app\static_request::init('request', 'save')->is_set()) {
      $test->auto_set_members();
      $success = $test->save();
 
      $id = $test->get_member('id');
      if ($success===true && $id > 0) {
        cx_redirect_url($this->get_url('/app/testing', 'index', 'id='.$id));
      }
    }    
    
    $this->set_title_and_header('Hello,');
    $this->registry->get('document')->set_keywords('testing');
    
    $frm = $this->load_class('cx\form\form', array('name' => 'product', 'defaults'=>array('readonly'=>false)));
    $frm->grab_form('test', $model);
    $frm->form('submit', 'save', array('id' => 'save', 
        'class'=>'btn btn-success', 'value' => 'save',
        'onclick'=>'return validatePage();'));
    $frm->end_form();

    $this->do_view($frm->get_html());
  }
  
public function all() {
    $this->load_model('app' . DS . 'testing');
    $db_options = array();
    $test = new cx\model\testing($db_options);
    
    $test->load();
    $allow_html = true;
    $rows = $test->get_members($allow_html);
    
    foreach($rows as $row) {
      echo "ID#{$row['id']} : {$row['data']} <br>";
    }
  }
  
  public function generic() {
    $this->load_model();
    $db_options = array('table'=>'test', 'key'=>'id');
    $test = new cx\database\model($db_options);
//    $options['where'] = " 1=2 ";
    $options['fields'] = "`test`.`id`, `test`.`data`";
    $options['paginator'] = 'true';
    $test->load("", $options);
    
    $allow_html = false;
    $page['rows'] = $test->get_members($allow_html);
    if ($test->get_paginator_object() === false) {
      $page['no_results'] = false;
      $page['paginator_items'] = "";    
      $page['paginator_links'] = "";
      $page['paginator_entries'] = "";
    } else {
      $page['no_results'] = ($test->get_paginator_object()->items_total > 0) ? false : true;
      $page['paginator_items'] = $test->get_paginator_object()->display_items_per_page();    
      $page['paginator_links'] = $test->get_paginator_links();
      $page['paginator_entries'] = $test->get_paginator_object()->get_entries();
    }   
    
    $this->load_view('app/testing/generic', $page);
  }
  
  public function ssp() {
    $this->datatables_code();
    
    $page['q'] = \cx\app\main_functions::get_globals(array('route','m'));
    $this->load_view('app/testing/ssp_test', $page);
  }
  
  public function ajax_ssp() {
    $this->load_model();
    $db_options = array('table'=>'`test`', 'key'=>'`id`');
    $test = new cx\database\model($db_options);
    
    $columns = array(
      array( 'db' => "{$db_options['table']}.`id`", 'dt' => 0 ),
      array( 'db' => "{$db_options['table']}.`data`", 
             'dt' => 1, 
             'textsize' => 30,
             'hyper' => $this->get_url('/app/testing', 'echome', "id="), 
             'id' => "{$db_options['table']}.`{$db_options['key']}`",
             'fn' => 'get_data'
      ),
    );	
  
    $options['where'] = " 1=1";
    $test->ssp_load($columns, $options);
  }
  
  public function echome() {
    echo $this->request->get_var('id');
  }
 
  public function hash() {
    echo \cx\app\main_functions::get_large_random_hash();
  }
  
  public function test_curl() {
    $curl = $this->load_class('cx\app\cx_curl');
    $curl->hostname = 'dev';
    $curl->port = 80;
    $curl->ssl = false;
    $curl->json_decode = true;
    cx_dump($curl->post('home/api/app/testing/ajax_name', array('name'=>'bob', 'return'=>'json', 'debug'=>'true')));
  }
  
  public function ajax() {
    $this->load_view('app/testing/ajax_name');
  }
  
  private function ajax_name_api(array $data) {
    if ($this->request->is_not_set($data['name'])) {
      \cx\app\cx_api::error(array('code'=>422, 'reason'=>'Name not set'));
    }

    if ($this->request->is_empty($data['name'])) {
      \cx\app\cx_api::error(array('code'=>422, 'reason'=>'Name is a required field'));
    }    
    
    \cx\app\cx_api::ok(array('code'=>200, 'name'=>$data['name']));
  }
  
  public function ajax_name() {    
    $this->auth(array('ajax'=>true, 'user'=>'is_logged_in'));
    $data['name'] = $this->request->post_var('name');
    
    if ($this->is_api()) {
      $this->ajax_name_api($data);
    } elseif (! $this->request->is_ajax()) {
      echo 'Error no ajax';
    } else {
      if ($this->request->is_empty($data['name'])) {
        echo "Name is a required field";
      } else {
        echo $data['name'];
      }
    }

  }
  
  public function get_pwd() {
    $this->load_model('users' . DS . 'users');
    $db_options = array();
    $users = new cx\model\users($db_options);
    echo $users->get_pwd_hash($this->request->get_var('pwd'));
  }  
  
  public function sleep() {
    sleep(5);
    $this->do_view('zzz');
  }
}