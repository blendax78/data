<?php
ini_set ('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

	require_once("<home>/www/lib/digitalocean.lib.php");
	require_once("<home>/www/lib/classes/mysql.class.php");
	$site["path"] = "<removed>";
	$site["url"] = "<removed>";

	define("UPLOAD_PATH","<removed>");
	define("SAVED_FILES_PATH","<removed>");

$timestart = microtime(true);

require_once("{$site["path"]}/shared/controller/shared_controller.php");

#Global Functions
function format_date($date){
	if (strlen($date) > 0){
		return date("m/d/Y g:i:sa",strtotime($date));
	}else{
		return '';
	}
}

?>
