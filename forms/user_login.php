<?php 

/**
 * @copyright (c) 2012
 * @author Robert Strutts
 */

$size = (isset($this->defaults['size']) ? $this->defaults['size'] : '20');
$rows = (isset($this->defaults['rows']) ? $this->defaults['rows'] : '16');
$cols = (isset($this->defaults['cols']) ? $this->defaults['cols'] : '80');

$this->form('start_div', 'pwdc', array('div-id'=>'pwd-container', 'div-class'=>'container'));
$this->form('start_div', 'row', array('div-class'=>'row'));
$this->form('start_div', 'offset', array('div-class'=>'col-md-offset-5 col-md-3'));

$this->form('text', 'username', array('size'=>$size,
    'maxlength'=>'27', 'label'=>'Username',
    'class'=>'form-control input-sm chat-input', 'required'=>true,
//    'div-class'=>'small-field-box',
    'placeholder'=>$model['username']));

$this->form('password', 'password', array('size'=>$size,
    'maxlength'=>'27', 'label'=>'Password',
    'class'=>'form-control input-sm chat-input', 'required'=>true,
//    'div-class'=>'small-field-box',
    'value'=>$model['pwd'],
    'placeholder'=>''));

$this->form('start_div', 'pwd_strength', array('div-class'=>'pwstrength_viewport_progress'));
$this->form('end_div', 'pwd_strength');

$checked = ($model['pwd'] == "**********") ? true : false;
$this->form('checkboxes', 'login', array('checked'=>$checked, 'options'=>array('rememberme'=>'Remember Me')));

$this->form('button', 'do_login', array('id' => 'save', 
        'class'=>'btn btn-primary btn-md', 'value' => 'Login <i class=\'fa fa-sign-in\'></i>',
        'onclick'=>'login_submit();'));
    
$this->form('end_div', 'offset');
$this->form('end_div', 'row');
$this->form('end_div', 'pwdc');

$ready_code = jquery_load("if ($('#login-password').val() == '**********') { \r\n $('.pwstrength_viewport_progress').hide(); \r\n } \r\n");

$this->form('js_inline', 'do_submit', array('code'=>"\r\n
  
  {$ready_code}

  function login_submit() { \r\n
    var login = $('#login-username').val(); \r\n
    var pwd = $('#login-password').val(); \r\n
    if (login.length > 2 && pwd.length > 2) { \r\n
      $('#login').submit();
    } \r\n
} \r\n    
  "));
