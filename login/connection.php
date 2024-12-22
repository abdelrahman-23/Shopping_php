<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "nile.com";

if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))
{

	die("failed to connect!");
}

