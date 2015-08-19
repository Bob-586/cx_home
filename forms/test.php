<?php 

/**
 * @copyright (c) 2012
 * @author Robert Strutts
 */

$size = (isset($this->defaults['size']) ? $this->defaults['size'] : '20');
$rows = (isset($this->defaults['rows']) ? $this->defaults['rows'] : '16');
$cols = (isset($this->defaults['cols']) ? $this->defaults['cols'] : '80');

$this->form('text', 'data', array('size'=>$size,
    'maxlength'=>'27', 'label'=>'test',
    'class'=>'txt-field righty', 'required'=>true,
    'div-class'=>'small-field-box',
    'placeholder'=>'Enter something!'));