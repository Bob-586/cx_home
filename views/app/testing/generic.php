<h1>Generic Pagination</h1>
<div class="wrapper"><div class="container"><div class="row">

<div class="panel panel-default top20">
<div class="panel-body">

<?php

/**
 * @copyright (c) 2015
 * @author Chris Allen, Robert Strutts
 */

if ($no_results === true) {
  
  $fade = true;
  echo \cx\app\main_functions::do_alert('No results!', 'info', $fade);
  
} else {
  
  echo $paginator_items . "<br><br>";
  
  foreach($rows as $row) {
    echo "ID#{$row['id']} : {$row['data']} <br>";
  }

  echo $paginator_links;
  echo $paginator_entries;
}

?>
    
</div></div></div>
</div>
</div>