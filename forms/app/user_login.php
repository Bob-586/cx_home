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

$checked = ($model['pwd'] == "**********") ? true : false;
$this->form('checkboxes', 'login', array('checked'=>$checked, 'options'=>array('rememberme'=>'Remember Me')));

$this->form('button', 'do_login', array('id' => 'save', 
        'class'=>'btn btn-primary btn-md', 'value' => 'Login <i class=\'fa fa-sign-in\'></i>',
        'onclick'=>'login_submit();'));
    
$this->form('end_div', 'offset');
$this->form('end_div', 'row');
$this->form('end_div', 'pwdc');

$this->form('js_inline', 'do_submit', array('code'=>"
  function login_submit() { 
    var login = $('#login-username').val();
    var pwd = $('#login-password').val(); 
    if (login.length < 3) {
      $('#login-password').css('background-color', 'white');
      $('#login-username').css('background-color', 'red');
    } else if (pwd.length < 3) {
      $('#login-username').css('background-color', 'white');
      $('#login-password').css('background-color', 'red');
    } else {
      $('#login-username').css('background-color', 'white');
      $('#login-password').css('background-color', 'white');
      $('#login').submit();
    }
  }

$(':input').keypress(function (e) {
  if (e.which == 13) {
    $('#login').submit(); // Pressing Enter key on input field allows submit
  }
});
  "));
