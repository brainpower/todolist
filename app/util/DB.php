<?php

require_once("config.inc.php");
require_once("3rdparty/php/notorm/NotORM.php");

try {
	$DBH = new PDO("mysql:host=localhost;dbname=" . DB_NAME, DB_USER, DB_PASS);
} catch(PDOException $e) {
	die("Database connection failed!\n\nSee server log for more information.");
}
$notorm_cache = new NotORM_Cache_Database($DBH);
$notorm_struct = new NotORM_Structure_Convention(
	$primary = 'id',
	$foreign = '%s_id',
	$table = '%s',
	$prefix = ''
);
$DB = new NotORM($DBH, $notorm_struct, $notorm_cache);
