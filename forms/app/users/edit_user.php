<?php 

/**
 * @copyright (c) 2012
 * @author Robert Strutts
 */

$size = (isset($this->defaults['size']) ? $this->defaults['size'] : '20');
$rows = (isset($this->defaults['rows']) ? $this->defaults['rows'] : '16');
$cols = (isset($this->defaults['cols']) ? $this->defaults['cols'] : '80');

$this->form('hidden_field', 'save', array('value'=>'saveme'));

$this->form('start_div', 'pwdc', array('div-id'=>'pwd-container', 'div-class'=>'container'));

$this->form('text', 'fname', array('size'=>$size,
    'maxlength'=>'47', 'label'=>'Frist Name',
    'class'=>'form-control input-sm chat-input', 'required'=>true,
    'placeholder'=>'Enter the First Name'));

$this->form('text', 'lname', array('size'=>$size,
    'maxlength'=>'47', 'label'=>'Last Name',
    'class'=>'form-control input-sm chat-input', 'required'=>true,
    'placeholder'=>'Enter the Last Name'));

$this->form('text', 'username', array('size'=>$size,
    'maxlength'=>'80', 'label'=>'Username',
    'class'=>'form-control input-sm chat-input', 'required'=>true,
//    'div-class'=>'small-field-box',
    'placeholder'=>'Enter New Username'));

$holder = ($model['new'] === true) ? "Enter a Strong Password." : 'Leave blank for current password.';

$this->form('password', 'password', array('size'=>$size,
    'maxlength'=>'27', 'label'=>'Password',
    'class'=>'form-control input-sm chat-input', 'required'=>false,
    'placeholder'=>$holder));

$this->form('start_div', 'pwd_strength', array('div-class'=>'pwstrength_viewport_progress'));
$this->form('end_div', 'pwd_strength');

$this->form('password', 'confirm', array('size'=>$size,
    'maxlength'=>'27', 'label'=>'Confirm Password',
    'class'=>'form-control input-sm chat-input', 'required'=>false,
    'placeholder'=>$holder));

$this->form('checkboxes', 'rights', array('options'=>$model['rights_statuses'], 'read_only'=>$model['lock_rights_controls']));

$this->form('button', 'do_login', array('id' => 'save', 
        'class'=>'btn btn-primary btn-md', 'value' => 'Save <i class=\'fa fa-floppy-o\'></i>',
        'onclick'=>'save_user();'));
    
$this->form('end_div', 'pwdc');

$condition = ($model['new'] === true) ? 'pwd.length > 6' : '(pwd.length == 0 || pwd.length >6)';

$this->form('js_inline', 'do_submit', array('code'=>"
  function save_user() { 
    var fname = $('#edit_user-fname').val();
    var lname = $('#edit_user-lname').val();
    var login = $('#edit_user-username').val(); 
    var pwd = $('#edit_user-password').val(); 
    var confirm = $('#edit_user-confirm').val(); 
    if ({$condition} && pwd == confirm) { 
      if (login.length > 2) { 
        if (fname.length > 1 && lname.length > 1) {
          $('#edit_user').submit(); 
        } else {
          alert('Enter a first/last name!');
        }
      } else { 
        alert('Enter a valid username!'); 
      } 
    } else { 
      alert('Passwords must be complex and match!');
    } 
}     
  "));
