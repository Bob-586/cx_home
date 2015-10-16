<?php

/**
 * @copyright (c) 2015
 * @author Chris Allen, Robert Strutts
 */

echo "<a href=\"" . $this->get_url("/app/". DEFAULT_PROJECT, "logout") . "\">Logout</a><br><br>";

echo "Welcome back, {$fname} {$lname}<br><br>";
echo "Rights = "; 

if (is_array($rights)) {
  $out = '';
  foreach($rights as $right) {
    $out .= $right . ", ";
  }
  echo rtrim($out, ', ');
} else {
  echo $rights;
}