<?php
require_once("{$site["path"]}/relations/model/relation.class.php");
require_once("{$site["path"]}/shared/controller/shared_controller.php");
require_once("{$site["path"]}/documents/controller/document_controller.php");

$relation = new Relation();

if (isset($_GET["relationid"]) && trim($_GET["relationid"]) !== '' ){
	if (isset($_POST["relationid"])){
		saveRelationPOST($_POST);
	}

	$relation->findById($_GET["relationid"]);
	
}else{
	if (isset($_POST["relationid"])){
		saveRelationPOST($_POST);
		$relation->getNewest();
	}
}

#$relations = $relation->getAll("parent_type, child_type"); #date_entered desc to order by created desc
global $view;

function listRelations($object){
	global $site;
	$object->getRelations();
	if (count($object->relations) > 0){
		$return = '<div class="span10"><h3>Relations</h3></div>';

		$objectJSON = json_encode(array('parentid'=>$object->id,'parenttype'=>strtolower(get_class($object))));
		$hiddenObject = SharedController::input_hidden('hiddenParent','hiddenParent',$objectJSON);

		foreach ($object->relations as $rname=>$relations){
			$name = ucwords($rname);

			$idname = strtolower($name);
			$rows = "";
			if (is_array($relations) && count($relations) > 0){
				foreach($relations as $relation){
					$format_date_modified = format_date($relation["date_modified"]);
					$format_date_entered = format_date($relation["date_entered"]);

					switch ($rname){
						case "contact":
							$relation["name"] = "{$relation["first_name"]} {$relation["last_name"]}";
							break;
						case "document":
							$relation["name"] = "{$relation["document_name"]}";
							break;
					}
					
					$namelink = '<a href="' . $site["url"] . "/{$rname}s/?$idname" . "id={$relation["id"]}" .'">' . $relation["name"] . '</a>';
					$delete = '<a style="margin-left:15px;" href="#" class="btn btn-mini deleteRelationBtn" id="' . $relation["relationid"] . '"><i class=" icon-trash"></i></a>';
					$rows .= "<tr class=\"overview{$name}Collapse\"><td>$namelink</td><td>$format_date_entered</td><td>$format_date_modified</td><td class=\"center\">$delete</td></tr>";
				}
			}
				$table = <<<END
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">$deleteBtn</span>
			</span>
			</div>
		</div>
		<br/>	
		<div class="" style="">
			<button style="position: relative; float:right;" class="btn btn-mini newRelation{$name}Btn"><i class="icon-plus-sign"></i>Add $name</button>
		</div>
		<table id="relationTbl" class="table table-striped table-condensed table-bordered">
			<tr>
				<th colspan="4" class="collapseHeader" id="overview$name">$name 
				<span  class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th>
			</tr>
			<tr class="overview{$name}Collapse">
				<th class="span5">Name</th><th class="span2">Date Created</th><th class="span2">Date Modified</th><th class="span1">Delete</th>
			</tr>
			$rows
		</table>
		$hiddenObject
END;
				$return .= '<div class="row"><div class="span10">' . $table . '</div></div>';
			
		}
	}

	return $return;
}


function viewRelation($relation){
	return '';
}

function editRelation($relation){
	return '';
}

function newRelation($object){
	$dropdown = newRelationDropdown();
}

function newRelationDropdown(){
	global $site;

	include_once($site["path"] . "/contacts/model/contact.class.php");
	include_once($site["path"] . "/documents/model/document.class.php");
	include_once($site["path"] . "/events/model/event.class.php");
	include_once($site["path"] . "/notes/model/note.class.php");
	include_once($site["path"] . "/groups/model/group.class.php");
	
	$object = new Contact();
	$objectArray["Contacts"] = $object->getAll('first_name');
	$object = new Document();
	$objectArray["Documents"] = $object->getAll('document_name');
	$object = new Event();
	$objectArray["Events"] = $object->getAll('name');
	$object = new Note();
	$objectArray["Notes"] = $object->getAll('name');
	$object = new Group();
	$objectArray["Groups"] = $object->getAll('name');
	
	foreach($objectArray as $type=>$objects){
		print "<b>$type</b><br/>";
		foreach($objects as $obj){
			print $obj->id . "<br/>";
		}
	}
	
	return $return;	
}

function saveQuickRelation($parent,$object){
	$newRelation = new Relation();
	$newRelation->parent_id = $parent['parentid'];
	$newRelation->parent_type = $parent['parenttype'];
	$child = strtolower(get_class($object));
	$newRelation->child_type = $child;
	$newRelation->child_id = $object->id;
	$newRelation->deleted = 0;
	$newRelation->save();
}

function saveRelationPOST($post){
	if (trim($post["relationid"]) !== ""){
		$editRelation = new Relation($post["relationid"]);
	}else{
		$editRelation = new Relation();
	}
	
	$editRelation->parent_type = $post["parenttype"];
	$editRelation->parent_id = $post["parentid"];
	
	if (strlen($post["contactid"]) > 2){
		$editRelation->child_type = 'contact';
		$editRelation->child_id = $post["contactid"];
	}elseif(strlen($post["groupid"]) > 2){
		$editRelation->child_type = 'group';
		$editRelation->child_id = $post["groupid"];
	}elseif(strlen($post["documentid"]) > 2){
		$editRelation->child_type = 'document';
		$editRelation->child_id = $post["documentid"];
	}elseif(strlen($post["noteid"]) > 2){
		$editRelation->child_type = 'note';
		$editRelation->child_id = $post["noteid"];
	}elseif(strlen($post["eventid"]) > 2){
		$editRelation->child_type = 'event';
		$editRelation->child_id = $post["eventid"];
	}
	$editRelation->deleted = 0;
	$editRelation->save();
}

function drawNewRelationModal($object){
	global $site;
	$name = strtolower(get_class($object)) . "s";

	include_once($site["path"] . "/contacts/model/contact.class.php");
	include_once($site["path"] . "/documents/model/document.class.php");
	include_once($site["path"] . "/events/model/event.class.php");
	include_once($site["path"] . "/notes/model/note.class.php");
	include_once($site["path"] . "/groups/model/group.class.php");
	
	$tempObject = new Contact();
	$contacts = $tempObject->getAll('first_name');
	$contactsDD = SharedController::allObjDropDown($contacts, '','',array('first_name','last_name'),array('class'=>'relationDD contactsDD	','style'=>'display:none;'));
	$tempObject = new Document();
	$documents = $tempObject->getAll('document_name');
	$documentsDD = SharedController::allObjDropDown($documents, '','',array('document_name'),array('class'=>'relationDD documentsDD','style'=>'display:none;'));
	$tempObject = new Event();
	$events = $tempObject->getAll('name');
	$eventsDD = SharedController::allObjDropDown($events, '','',array('name'),array('class'=>'relationDD eventsDD','style'=>'display:none;'));
	$tempObject = new Note();
	$notes = $tempObject->getAll('name');
	$notesDD = SharedController::allObjDropDown($notes, '','',array('name'),array('class'=>'relationDD notesDD','style'=>'display:none;'));
	$tempObject = new Group();
	$groups = $tempObject->getAll('name');
	$groupsDD = SharedController::allObjDropDown($groups, '','',array('name'),array('class'=>'relationDD groupsDD','style'=>'display:none;'));
	$hidden = SharedController::input_hidden('parentid', 'parentid', $object->id) .
	$hidden .= SharedController::input_hidden('parenttype', 'parenttype', strtolower(get_class($object))) .
				SharedController::input_hidden('newRelation', 'newRelation', '');;
	$idname = substr($name,0,strlen($name) -1 ) . 'id'; 
	$new = <<<END
	
	<div id="newRelationModal" class="modal hide fade in" style="display: none; ">  
		<div class="modal-header">  
			<a class="close" data-dismiss="modal">×</a>  
			<h3>New Relation</h3>  
		</div>  
		<div class="modal-body">    
			<form name="newRelationFrm" id="newRelationFrm" class="form-vertical" method="POST" action="{$site["url"]}/$name/?$idname={$object->id}">
				<label>Relate To:</label>
	  				<select id="selectRelationType">
	  					<option value=""></option>
	  					<option value="groups">Group</option>
	  					<option value="contacts">Contact</option>
	  					<option value="documents">Document</option>
	  					<option value="events">Event</option>
	  					<option value="notes">Note</option>
	  				</select>
  				<label>Object:</label>
  				$contactsDD
  				$documentsDD
  				$eventsDD
  				$notesDD
  				$groupsDD
  				$hidden
			</form>
		</div>
		<div class="modal-footer">  
			<a href="#" id="saveRelationBtn" class="btn btn-success">Save Relation</a>  
			<a href="#" class="btn" data-dismiss="modal">Close</a>  
		</div>  
	</div>
END;

	return $new;
}

?>
