<?php

function format_rights($rights) {
  $rights = (cx\app\main_functions::is_serialized($rights) === true) ? cx\app\main_functions::safe_unserialize($rights) : $rights;
  if (is_array($rights)) {
    $out = '';
    foreach($rights as $right) {
      $out .= $right . ", ";
    }
    return rtrim($out, ', ');
 }
 return $rights;
}

class cx_loader_app_users extends cx\app\app {

  public function __construct() {
    $copy = (defined('COPYRIGHT')) ? COPYRIGHT : '';
    $this->set_footer("&copy; Copyright 2014-" . date('Y') . ' ' . $copy);

    parent::__construct(); // Must load app constructor
  }

  public function index() {
    $this->auth(array('user'=>'login_check'));
    $this->datatables_code();
    $page['q'] = \cx\app\main_functions::get_globals(array('route','m'));
    $this->load_view('app/users/index', $page);
  }

  public function ajax_ssp_users_list() {
    $this->load_model();
    $db_options = array('table'=>'`users`', 'key'=>'`id`');
    $test = new cx\database\model($db_options);
    
    $columns = array(
      array( 'db' => "{$db_options['table']}.`id`", 'dt' => 0 ),
      array( 'db' => "{$db_options['table']}.`fname`", 
             'dt' => 1, 
             'textsize' => 30,
             'hyper' => $this->get_url('/app/users', 'edit_user', "id="), 
             'id' => "{$db_options['table']}.`{$db_options['key']}`",
      ),
      array( 'db' => "{$db_options['table']}.`lname`", 
             'dt' => 2, 
             'textsize' => 30,
             'hyper' => $this->get_url('/app/users', 'edit_user', "id="), 
             'id' => "{$db_options['table']}.`{$db_options['key']}`",
      ),
      array( 'db' => "{$db_options['table']}.`username`", 
             'dt' => 3, 
             'textsize' => 30,
             'hyper' => $this->get_url('/app/users', 'edit_user', "id="), 
             'id' => "{$db_options['table']}.`{$db_options['key']}`",
      ),               
      array( 'db' => "{$db_options['table']}.`rights`", 
             'dt' => 4,
             'fn_results' => 'format_rights',
             'hyper' => $this->get_url('/app/users', 'edit_user', "id="), 
             'id' => "{$db_options['table']}.`{$db_options['key']}`",
      ),

    );	
  
    $is_admin = $this->auth(array('user' => 'is_admin'));         
    $id = $this->session->get_int(CX_LOGIN . 'id');
    
    $options['where'] = ($is_admin === true) ? " 1=1" : " id={$id}";
    $test->ssp_load($columns, $options);
  }

  
  public function edit_user() {
    $id = cx\app\static_request::init('get', 'id');

    if ($id->is_not_set()) {
      echo "Invalid id!";
      exit;
    }

    if ($id->to_int() !== $this->session->get_int(CX_LOGIN . 'id')) {
      $this->auth(array('user'=>'admin_check'));
      $lock_rights_controls = false; // Admin
    } elseif ($this->auth(array('user'=>'is_admin')) === true) {
      $lock_rights_controls = false; // Admin can modify self, as they can create any user...
    } else {
      $lock_rights_controls = true; // User must not be able to grant self more rights!
    }

    $this->load_model();
    $db_options = array('table' => 'users', 'key' => 'id');
    $edit_user = new cx\database\model($db_options);

    if ($id->is_not_valid_id()) {
      // no existing data
      $model = array();
      $model['new'] = true;
    } else {
      $edit_user->load($id->to_int());
      $model = $edit_user->get_members();
      if ($model == array()) {
        echo "Invalid id!";
        exit;
      }
      $s_pwd = $model['password']; // Save Pwd
      unset($model['password']);
      $model['new'] = false;
    }

    $model['lock_rights_controls'] = $lock_rights_controls;
    $model['rights_statuses'] = array('admin' => 'Administrator', 'staff' => 'Staff', 'cus' => 'Customer', 'api' => 'API client');

    if (cx\app\static_request::init('request', 'save')->is_set()) {
      $edit_user->auto_set_members();

      $confirm = cx\app\static_request::init('request', 'confirm');
      $pwd = cx\app\static_request::init('request', 'password');

      if (cx\app\static_request::init('request', 'username')->is_empty() || cx\app\static_request::init('request', 'fname')->is_empty()  || cx\app\static_request::init('request', 'lname')->is_empty()) {
        cx\app\main_functions::set_message('First/Last name or username is missing.');
        $saveme = false;
      } elseif ($model['new'] === false && $confirm->is_empty() && $pwd->is_empty()) {
        $edit_user->set_member('password', $s_pwd);
        $saveme = true;
      } elseif ($confirm->is_not_empty() &&
        $pwd->to_string() === $confirm->to_string() &&
        strlen($pwd->to_string()) > 6) {
        $this->load_model('users' . DS . 'users');
        $db_options = array('api' => false);
        $users = new cx\model\users($db_options);
        $edit_user->set_member('password', $users->get_pwd_hash($pwd->to_string()));
        $saveme = true;
      } else {
        cx\app\main_functions::set_message('Password not strong/does not match.');
        $saveme = false;
      }

      if ($saveme === true) {
        $success = $edit_user->save();

        $id = $edit_user->get_member('id');
        if ($success === true && $id > 0) {
          cx_redirect_url($this->get_url('/app/users', 'edit_user', 'id=' . $id));
        }
      }
    }

    $frm = $this->load_class('cx\form\form', array('name' => 'edit_user', 'defaults' => array('readonly' => false)));
    $frm->grab_form('edit_user', $model);
    $frm->end_form();

    $this->add_js('./assets/pwd-meter.min.js');
    $this->add_css('./assets/login.css');

    $index = $this->get_url('app/users', 'index');
    $this->breadcrumb = array($index=>"List Users");
    $this->active_crumb = "Edit User";

    $this->do_view($frm->get_html());
  }

}
