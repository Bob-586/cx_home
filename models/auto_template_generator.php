<?php

/**
 * @copyright (c) 2014
 * @author Robert Strutts 
 */

namespace cx\model;
use cx\database\model as the_model;

class auto_form_generator extends the_model {

  private $folder_name;
  private $file_name;
  private $perms;

  public function __construct($folder, $file, $perms) {
    $this->folder_name = str_replace('..', '', preg_replace("/[^a-zA-Z0-9_]+/", "", $folder));
    $this->file_name = str_replace('..', '', preg_replace("/[^a-zA-Z0-9_]+/", "", $file));
    $this->perms = $perms;
  }

  public function generator() {
    $controller_dir = PROJECT_BASE_DIR. 'controllers' . DS . $this->folder_name;
    $controller_file = $controller_dir . $this->file_name;
    
    if (! is_dir($controller_dir)) {
      mkdir($controller_dir, '0775');
    }
    
    if (file_exists($controller_file)) {
      echo "Project already exists!";
      exit;
    }
    
    $fh = fopen($controller_file, 'w') or die("can't open file");
    fwrite($fh, "<?php \r\n\r\n");
    fwrite($fh, "/**
 * @copyright (c) 2014
 * @author Chris Allen, Robert Strutts
 */\r\n\r\n");
    fwrite($fh, "class cx_loader_app_home extends cx\app\app {
    public function __construct() {
      parent::__construct(); // Must load app constructor
  }\r\n");
    fwrite($fh, "\t public function index() {
    $this->breadcrumb = array(\"javascript:;\"=>\"Home\");
    $this->active_crumb = \"Index\";

    $this->set_title('Hello,');
    
    $this->load_view($this->folder_name . DS . $this->file_name);
  }
}
      ");
     fclose($fh);
    echo "Created file: {$controller_file}";
    
    
    
    echo '<br/>Done...';
  }

}