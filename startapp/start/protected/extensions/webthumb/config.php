<?php
// require autoloader for Bluga
require_once 'Bluga/Autoload.php';

// Your apikey goes here
$APIKEY = "f13780c9d3a3eb64f120f005231796a9";

// check for a config file in your home dir
$home = getenv('HOME');
  if (file_exists("$home/start/protected/extensions/webthumb/webthumb.php")) {
  include "$home/start/protected/extensions/webthumb/webthumb.php";
}
