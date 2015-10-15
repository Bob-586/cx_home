<?php
define('DS', DIRECTORY_SEPARATOR);
define('PROJECT_BASE_DIR', dirname(__FILE__) . DS);
define('DEFAULT_PROJECT', 'home'); // default app controller to use
define("CX_SHORT_URL", "true"); // Is apache rewrite mod on?

define("COPYRIGHT", "Robert Strutts");

// set session name for homepage site
define("CX_SES","HOME_SYS_");

define('CX_SITE_NAME', 'CX Site');
define("CX_PAGE_TITLE","The CX HomePage");
define('CX_SYSTEM_ADMIN_NAME', '');
define('CX_SYSTEM_ADMIN_EMAIL', '');
define('CX_TIMEOUT', 4); // # of seconds before warning of timeout to developers
// database settings
define("CX_DB_TYPE","mysql");
define("PDOERR", PDO::ERRMODE_EXCEPTION);
// define("CX_DB_SOCKET_LOCAL","/var/run/mysqld/mysqld.sock");
define("CX_DB_HOST_LOCAL","localhost");
define("CX_DB_USER_LOCAL","home");
define("CX_DB_PASS_LOCAL","MYPASSWORD_CHANGE-ME");
define("CX_DB_PORT_LOCAL","3306");
define("CX_DB_NAME_LOCAL","home");

define("CX_DB_HOST_REMOTE","GODADDY.com....");
define("CX_DB_USER_REMOTE","home");
define("CX_DB_PASS_REMOTE","SECRETPASSWORD");
define("CX_DB_PORT_REMOTE","3306");
define("CX_DB_NAME_REMOTE","home");

if (!defined("CX_KEYWORDS")) {
  define("CX_KEYWORDS","");
}
if (!defined("CX_DESCRIPTION")) {
  define("CX_DESCRIPTION","");
}

$live_ips = array('192.168.10.121'); // My PC's IP to bind to LIVE mode.
// dev or localhost is developer mode.

/*
 * Do NOT changes the lines of code below this line HERE: !!
 * ----------------------------------------------------------------------------
 * =========================Hands OFF!!========================================
 */

if (!defined("CX_ROBOTS")) {
  define('CX_ROBOTS', 'INDEX, FOLLOW, ARCHIVE');
}

// Used by login_functions, don't change once live!!
define("CX_PWD_SALT1","@*15dW4&");
define("CX_PWD_SALT2","2*0PQ#8");
define('COOKIE_SALT', '(9%3Rcz3^');

$tzg = ini_get('date.timezone');
if (empty($tzg)) {
  define("CX_PHP_TZ","UTC");
}

/*
The first setting, “session.cookie_lifetime”, sets the time period in seconds 
 * that the cookie should exist in the user’s browser. The second setting, 
 * “session.gc_maxlifetime”, sets the minimum number of seconds that the session
 * information should be stored on the server. Set at 86,400 seconds, a user 
 * should be able to browse your application for 24 hours before needing to 
 * re-authenticate their session.
*/

ini_set('session.cookie_lifetime', 86400);
ini_set('session.gc_maxlifetime', 86400);