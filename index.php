<?php

/**
 * @copyright (c) 2015
 * @author Chris Allen, Robert Strutts 
 */

$mem_baseline = memory_get_usage();

require 'config.php'; 
require '../cx/startup.php';

$app->load_controller();

cx_get_memory_stats(); // If debug on, shows memory stats