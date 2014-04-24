<?php
require_once("{$site["path"]}/shared/controller/shared_controller.php");
require_once("{$site["path"]}/contacts/model/contact.class.php");
require_once("{$site["path"]}/documents/model/document.class.php");
require_once("{$site["path"]}/groups/model/group.class.php");
require_once("{$site["path"]}/events/model/event.class.php");
require_once("{$site["path"]}/notes/model/note.class.php");

$obj = new Contact();
$contacts = $obj->getAll('date_modified desc',0,5);
$obj = new Group();
$groups = $obj->getAll('date_modified desc',0,5);
$obj = new Document();
$documents = $obj->getAll('date_modified desc',0,5);
$obj = new Event();
$events = $obj->getAll('date_modified desc',0,5);
$obj = new Note();
$notes = $obj->getAll('date_modified desc',0,5);

function drawDashboardTable($objects, $display){
	global $site;
	#display = array([table element name(s)],[display name])
	$table = '<table class="span5 table table-bordered table-striped">';
	$header = "";
	foreach ($display as $k=>$v){
		$header .= $v;
	}
	$table .= "<tr><th class=\"span3\">$header</th><th class=\"span2\">Last Modified</th></tr>";

	foreach ($objects as $object){
		$field = "";
	        foreach ($display as $k=>$v){
        	        $field .= "{$object->$k} ";
	        }

		$type = strtolower(get_class($object));
		$object->date_modified = format_date($object->date_modified);
		$table .= "<tr><td ><a href=\"{$site["url"]}/{$type}s/?{$type}id={$object->id}\">$field</a></td><td >{$object->date_modified}</td></tr>";
	}
	
	$table .= '</table>';
	return $table;
}



?>
