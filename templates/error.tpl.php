<?php
define('PRODUCTION', 600);
define('MAINTENACE', 3600); // 1 hour = 3600 seconds
define('RETRY_AFTER', PRODUCTION);

if(! headers_sent()) {
  header('HTTP/1.1 503 Service Temporarily Unavailable');
  header('Status: 503 Service Temporarily Unavailable');
  header('Retry-After: ' . RETRY_AFTER);
}
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Chris Allen, Robert Strutts">
    <meta name="language" content="english">
    <meta name="robots" content="NOINDEX, NOFOLLOW">
    <meta name="copyright" content="2014-<?php echo date('Y'); ?>">
    <link rel="shortcut icon" href="favicon.ico">
    <title>Sorry, we had an error...</title>
      
    <style>
      body { padding: 20px; background: #C00; color: white; font-size: 40px; }
    </style>  
  </head>
  <body>
  
  <h1>Sorry, we had an error...</h1>  
  <p>We apologize for any inconvenience this may cause.<p>
  <?php echo $this->page; ?>
  </body>
</html>  