<?php 

/**
 * @copyright (c) 2012
 * @author Robert Strutts
 */

$size = (isset($this->defaults['size']) ? $this->defaults['size'] : '20');
$rows = (isset($this->defaults['rows']) ? $this->defaults['rows'] : '16');
$cols = (isset($this->defaults['cols']) ? $this->defaults['cols'] : '80');

$this->form('text', 'username', array('size'=>$size,
    'maxlength'=>'27', 'label'=>'Username',
    'class'=>'txt-field righty', 'required'=>true,
    'div-class'=>'small-field-box',
    'placeholder'=>$model['username']));

$this->form('password', 'password', array('size'=>$size,
    'maxlength'=>'27', 'label'=>'Password',
    'class'=>'txt-field righty', 'required'=>true,
    'div-class'=>'small-field-box',
    'value'=>$model['pwd'],
    'placeholder'=>''));

$this->form('checkboxes', 'login', array('options'=>array('rememberme'=>'Remember Me')));