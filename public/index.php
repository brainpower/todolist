<?php

if( chdir("..") ){
	require_once("app/main.php");
} else {
	die("App is broken. Check app Folder for correct permissions and other corruption.");
}

