<?php

/**
 * @copyright (c) 2015
 * @author Chris Allen, Robert Strutts
 */

namespace cx\app;

trait security {

  /**
   * 
   * @todo make me
   */
  public function api_is_logged_in() {
    return true;
  }

  public function auth_is_logged_in() {
    $id = $this->session->get_int(CX_LOGIN . 'id');
    return ($this->request->is_valid_id($id));
  }

  public function auth_login_check() {
    if ($this->auth_is_logged_in() === false) {
      main_fn::set_message('That page requires User rights, please sign-in.');
      $this->do_login_redirect();
    }
  }

  public function auth_admin_check() {
    if ($this->auth_is_admin() === false) {
      main_fn::set_message('That page requires Administrative rights, please sign-in.');
      $this->do_login_redirect();
    }
  }

  public function auth_is_api_user() {
    return main_fn::found($this->session->session_var(CX_LOGIN . 'rights'), 'api');
  }

  public function auth_is_admin() {
    return main_fn::found($this->session->session_var(CX_LOGIN . 'rights'), 'admin');
  }

  public function auth_is_staff() {
    return main_fn::found($this->session->session_var(CX_LOGIN . 'rights'), 'staff');
  }

  public function auth_is_customer() {
    return main_fn::found($this->session->session_var(CX_LOGIN . 'rights'), 'cus');
  }

  public function get_login_full_name() {
    if ($this->auth_is_logged_in() === false) {
      return false;
    }
    return $this->session->session_var(CX_LOGIN . 'fname') . $this->session->session_var(CX_LOGIN . 'lname');
  }

}