<?php

/**
 * @copyright (c) 2015
 * @author Chris Allen, Robert Strutts 
 */

$mem_baseline = memory_get_usage();

require 'config.php'; 
require '../cx/startup.php';

if (!empty($_SERVER['REQUEST_URI'])) {
  $app->load_controller();
} else {
  echo "Todo add Ngnix access";
}

cx_get_memory_stats(); // If debug on, shows memory stats