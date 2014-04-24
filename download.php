<?php
require_once("config.php");
require_once("{$site["path"]}/documents/model/document.class.php");

if (strlen($_GET["f"]) > 0){
	$document = new Document($_GET["f"]);
	$filename = (strlen($document->doc_url) > 0) ? $document->doc_url : $document->document_name;
	
	#header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename=' . $filename);
	header('Pragma: no-cache');
	readfile("{$site["path"]}/files/{$_GET["f"]}");
}
?>