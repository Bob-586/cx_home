<?php
class cx_loader_app_home extends cx\app\app {

  public function __construct() {
    $this->set_footer("&copy; Copyright 2014-" . date('Y') . ". The Bishop's.");
    parent::__construct();
  }
  
  public function index() {
    $this->breadcrumb = array("javascript:;"=>"Main");
    $this->active_crumb = "Index";

    $this->set_title_and_header('Hello,');
    
    $this->do_view('Welcome...this is the main controller: /app/home<br><br>'
      . '<a href="' . $this->get_url("/app/home", "login") . '">Login</a><br><br>');
  }
  
  public function main() {
    $this->set_title_and_header('Main Page');
    $this->breadcrumb = 'Main Page';
    
    $id = $this->session->get_int(CX_LOGIN . 'id');

    if ($id === false || $id == 0) {
      cx_redirect_url($this->get_url('/app/home', 'login'));
    }

    $page['fname'] = $this->session->session_var(CX_LOGIN . 'fname');
    $page['lname'] = $this->session->session_var(CX_LOGIN . 'lname');
    $page['rights'] = $this->session->session_var(CX_LOGIN . 'rights');
    $this->load_view('app' . DS . 'main', $page);  
  }
  
  public function make_form() {
    $this->login_check();
    
    if ($this->request->get_var('table') !== false) {
      $this->load_model('auto_form_generator');
      $form_gen = new cx\model\auto_form_generator($this->request->get_var('table'));
      $form_gen->generator();
    } else {
      echo 'Sorry, please enter MySQL [table] name varible!';
    }
  }  

  public function login() {
    $this->load_model('users' . DS . 'users');
    $db_options = array('api'=>false);
    $users = new cx\model\users($db_options);

    $cc_name = $users->get_username_from_cookie();
    $username = ($this->request->request_var('username') !== false) ? $this->request->request_var('username') : $cc_name;
    $password = $this->request->request_var('password');

    if ($username !== false && $password !== false) {
      $success = $users->is_user($username, $password);

      if ($success == true) {
         cx_redirect_url($this->get_url('/app/home', 'main'));
      } else {
        cx_set_message('Invalid Username or Password!');
      }
    }

    $this->breadcrumb = array("index.php"=>"Main Page");
    $this->active_crumb = "Log in";
    $this->set_title_and_header('Please login');

    $model['pwd'] = (!empty($cc_name)) ? "**********" : '';
    $model['username'] = $username;
    $frm = $this->load_class('cx\form\form', array('name' => 'login', 'defaults'=>array('readonly'=>false)));
    $frm->grab_form('user_login', $model);
    $frm->form('submit', 'save', array('id' => 'save', 
        'class'=>'btn btn-success', 'value' => 'Login',
        'onclick'=>'return validatePage();'));
    $frm->end_form();

    $this->do_view($frm->get_html());
  }

  public function logout() {
    // Unset all of the session variables.
    $_SESSION = array();

    // Ensure remember me is deleted.
    setcookie(CX_SES . 'id', '', time() - 42000);

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();
    header('Location: '. $this->get_url('/app/home', 'index'));  
  } 
  
  public function error() {
    $this->do_view('', 'error');    
  }
  
}
