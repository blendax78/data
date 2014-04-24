<?php 
require_once("config.php");

$dbinfo = get_digitalocean_config("data");

$db = new mysqlDB($dbinfo["host"],$dbinfo["db"],
		$dbinfo["user"],$dbinfo["pass"]);

if (array_key_exists("delete", $_GET)){
	if ($_GET["delete"]){
		deleteObject($_GET["type"], $_GET["id"]);
	}
	
}

function deleteObject($type, $id){
	global $db;
	
	$sql = "update $type" . "s set deleted = 1 where id = '$id';";
	$db->query($sql);
	print $sql;
}

?>

