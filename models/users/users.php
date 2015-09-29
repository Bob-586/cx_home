<?php
namespace cx\model;
use cx\database\model as the_model;

class users extends the_model {

  protected $table = 'users';
  protected $key = 'id';
  private $api = true; // using a api call?
  
  public function __construct($options) {
    $this->api = (isset($options['api'])) ? $options['api'] : true;
    parent::__construct(array('table' => $this->table, 'key' => $this->key));
  }
 
  public function is_user($username, $password) {
    if ($this->api === false) {
      $c_ary = $this->get_cookie();
      if ($c_ary !== false) {
        if ($this->check_cookie($c_ary) === true) {
          return true;
        }
      }
    }

    if (! empty($username)) {
      
      $this->set_primary_key('username');
      $this->load($username);
      
      if ($this->get_member($this->key) > 0 && $this->check_pwd($password, $this->get_member('password'))) {
        $this->login_success($this->get_members());
        return true;
      }
    }
    return false;
  }

  public function get_username_from_cookie() {
    $c_ary = $this->get_cookie();
    if ($c_ary === false) {
      return '';
    }
    $c_aid = $c_ary['x']; //cookie "array" for id

    if ($c_aid > 0) {
      $this->load($c_aid);
      return $this->get_member('username');
    }
    return '';
  }

  public function get_pwd_hash($pwd) {
    if (function_exists('password_hash')) {
      return password_hash($pwd, PASSWORD_DEFAULT); // will add random SALT by it self
    } else {
      return hash('SHA256', CX_PWD_SALT1 . $pwd . CX_PWD_SALT2, false);
    }
  }
  
  public function check_pwd($pwd, $hash) {
    if (function_exists('password_hash')) {
      return password_verify($pwd, $hash);
    } else {
      $check = hash('SHA256', CX_PWD_SALT1 . $pwd . CX_PWD_SALT2, false);
      return ($check == $hash) ? true : false;
    }
  }

  private function get_cookie() {
    // Check to see if they have a cookie for access
    return $this->request->cookie_var('id');
  }

  private function check_cookie($c_ary) {
    $c_aid = $c_ary['x']; //cookie "array" for id
    $c_apwd = $c_ary['a']; //cookie "array" for password
    $c_ausr = $c_ary['b']; //cookie "array" for user

    if ($c_aid > 0) {
      $this->load($c_aid);

      $username = $this->request->request_var('username');
      // If another user is trying to login, let them...by ignoring the cookie data.
      if (empty($username) || $username == $this->get_member('username')) {
        // Make sure cookie data is same as database, if so log them in.
        
        if ($this->get_member($this->key) > 0 && md5(COOKIE_SALT . $this->get_member('password')) == $c_apwd && md5(COOKIE_SALT . $this->get_member('username')) == $c_ausr) {
          $this->login_success($this->get_members());
          return true;
        }
      }
    }
    return false;
  }

  private function set_cookie($c_id, $c_pwd, $username) {
    if ($this->request->request_var('login') == 'rememberme') {
      $expire = time() + 60 * 60 * 24 * 14; // Expires in: 2 weeks or 14 days.
      $c_usr = md5(COOKIE_SALT . $username);
      $c_pwd = md5(COOKIE_SALT . $c_pwd);
      $c_a = array('x' => $c_id, 'a' => $c_pwd, 'b' => $c_usr);
      $this->request->set_cookie_var('id', $c_a, 14, "days");
    }
  }

  private function login_success($a) {
    $this->set_cookie($a['id'], $a['password'], $a['username']);
    // store all login data in session
    foreach($a as $key=>$value) {
      if ($key == 'password') {
        continue; // do not store passwords!!
      }
      $this->session->set_session_var(CX_LOGIN . $key, $value);
    }
  }
  
  public function add_user($name, $username, $password, $user_type) {
    // Check if they are already in the system...
    $this->set_primary_key('username');
    $this->load($username);
    if ($this->get_member($this->key) > 0) {
      return false; // Already in the system!
    }
    
    $this->set_primary_key($this->key);
    $this->empty_data();
    
    $name = explode($name, " ");

    $this->set_member('fname', $name[0]);
    $this->set_member('lname', $name[1]);
    $this->set_member('username', $username);
    $this->set_member('password', $this->get_pwd_hash($password));
    $this->set_member('rights', $user_type);
    
    $success = $this->save();
    if ($success) {
      $this->login_success($this->get_members());
      return true;
    }
    return false;
  }
 
}