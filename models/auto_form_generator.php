<?php

/**
 * @copyright (c) 2014
 * @author Robert Strutts 
 */

namespace cx\model;
use cx\database\model as the_model;

class auto_form_generator extends the_model {

  protected $table;
  protected $key = 'id';

  public function __construct($table) {
    $this->table = $table;
    parent::__construct(array('table' => $this->table, 'key' => $this->key));
  }

  public function generator() {
    $myFile = CX_BASE_DIR . 'forms' . DS . str_replace('..', '', $this->table) . "_new.php";
    //if (file_exists($myFile)) die('File already exists. Please delete it.');
    $fh = fopen($myFile, 'w') or die("can't open file");
    fwrite($fh, "<?php \r\n\r\n");
    fwrite($fh, "/**
 * @copyright (c) 2014
 * @author Robert Strutts
 */\r\n\r\n");
    fwrite($fh, "\$size = (isset(\$this->defaults['size']) ? \$this->defaults['size'] : '40');\r\n");
    fwrite($fh, "\$rows = (isset(\$this->defaults['rows']) ? \$this->defaults['rows'] : '6');\r\n");
    fwrite($fh, "\$cols = (isset(\$this->defaults['cols']) ? \$this->defaults['cols'] : '80');\r\n");

    $fields = $this->database->get_members($this->table);
    foreach ($fields as $key => $value) {
      if ($value == 'id' || $value == 'company_id' || $value == 'user_id' || $value == 'deleted') {
        continue;
      }
      
      $sql = "SELECT `{$value}` FROM `{$this->table}` WHERE 1=1";
      $query = $this->database->prepare($sql);
      $query->execute();
      $meta = $query->getColumnMeta(0);

      $type = (isset($meta['native_type']) ? $meta['native_type'] : '');
      $len = $meta['len'];

      switch ($type) {
        case 'BLOB':
          $stringData = "\$this->form('textarea', '{$value}', array('label'=>'', 
    'cols'=>\$cols, 'rows'=>\$rows, 'div'=>''));\r\n\r\n";
          fwrite($fh, $stringData);
          break;
        case 'VAR_STRING':
          if ($len == 1) {
            $stringData = "// CHAR: {$value}\r\n";
          } else {
            $stringData = "\$this->form('text', '{$value}', array('label'=>'', 'size'=>\$size, 'maxlength'=>'{$len}'));\r\n\r\n";
          }
          fwrite($fh, $stringData);
          break;
        case 'STRING': //CHAR
          $stringData = "// CHAR: {$value}\r\n";
          fwrite($fh, $stringData);
          break;
        case 'NEWDECIMAL':
          $stringData = "// Decimal : {$value}\r\n";
          fwrite($fh, $stringData);
          break;
        case 'TIMESTAMP':
        case 'TIME':
        case 'DATE':
        case 'DATETIME':
          $stringData = "// Time field: {$value}\r\n";
          fwrite($fh, $stringData);
          break;
        case 'SHORT': //Small INT
        case 'INT24': //MED INT
        case 'LONGLONG': //BIG INT or SERIAL is an alias for BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE.
        case 'LONG': // Integers 
          $stringData = "// Misc: {$value}\r\n";
          fwrite($fh, $stringData);
          break;
        default: //TinyInt
          $stringData = "\$this->form('start_fieldset', '{$value}', array('label'=>'{$value}'));                    
\$this->form('radios', '{$value}', array('options'=>array('1'=>'Yes','2'=>'No')));
\$this->form('end_fieldset');\r\n\r\n";
          fwrite($fh, $stringData);
          break;
      }
    }

    fclose($fh);
    echo "Created file: {$myFile}";
    echo '<br/>Done...';
  }

}