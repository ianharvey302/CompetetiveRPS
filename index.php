<?php
// URL: https://cs4640.cs.virginia.edu/gxz6ja/CompetitiveRPS/ -->
error_reporting(E_ALL);
ini_set("display_errors", 1);
$PATHSTRING = "./"; //Use with local development
// $PATHSTRING = "/students/gxz6ja/students/gxz6ja/rps_secret/"; //use for server 
spl_autoload_register(function ($classname) {
    global $PATHSTRING;
    include($PATHSTRING . "$classname.php");
});
        

$rps = new RPSController($_GET);

$rps->run();
