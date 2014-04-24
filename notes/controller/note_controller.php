<?php
require_once("{$site["path"]}/notes/model/note.class.php");
require_once("{$site["path"]}/shared/controller/shared_controller.php");

$note = new Note();

if (isset($_GET["noteid"]) && trim($_GET["noteid"]) !== '' ){
	if (isset($_POST["noteid"]) && !array_key_exists('newRelation',$_POST)){
		saveNotePOST($_POST);
	}

	$note->findById($_GET["noteid"]);
	
}else{
	if (isset($_POST["noteid"])){
		saveNotePOST($_POST);
		$note->getNewest();
	}
}

$notes = $note->getAll("name"); #date_entered desc to order by created desc
$dropdown = '<form method="GET" class="form-inline well" id="noteviewform" >';
$dropdown .= SharedController::allObjDropDown($notes, $_GET["noteid"],'$("#noteviewform").submit();',array('name'));
$dropdown .= '&nbsp;<input type="submit" class="btn" value="View"/></form>';

function listNotes($notes, $start = 0, $stop = 10){

	global $site;
	if (is_null($start)){
		$start = 0;
		$stop = 10;
	}
	if (count($notes) > 0){
		$row = "";
		$page = "";
		$count = 0;
		
		foreach ($notes as $note){
			$format_date_modified = format_date($note->date_modified);
			$format_date_entered = format_date($note->date_entered);
			
			if($count >= $start && $count < $stop){
				$row .= "<tr>
					<td class=\"span4\"><a href=\"{$site["url"]}/notes/?noteid={$note->id}\">{$note->name}</a></td>
					<td class=\"span3\">{$format_date_entered}</td>
					<td class=\"span3\">{$format_date_modified}</td> 
				</tr>";
			}
			$count++;	
		}
	}
	$table = <<<END
	<br/>
		<table id="notesTbl" class="table table-striped table-condensed table-bordered ">
			<tr>
				<th>Note Name</th>
				<th>Date Created</th>
				<th>Date Modified</th>
			</tr>
			$row
		</table>
END;

	$return = '<div class="row"><div class="span10">' . $table . '</div></div>';
	$return .= SharedController::pager('notes', $notes, $start, $stop);
	return $return;
}


function viewNote($note){

	$format_date_modified = format_date($note->date_modified);
	$format_date_entered = format_date($note->date_entered);
	$editBtn = SharedController::edit_button('note', array('noteid'=>$note->id));
	$deleteBtn = SharedController::delete_button('note', array('noteid'=>$note->id));
	$note->description = nl2br($note->description);

	$table = <<<END
	
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: left;width: 50%" class="left">
				<span style="" class="bold right">ID:</span>
				<span style="" class="left">{$note->id}</span>
			</span>
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">{$note->name}&nbsp;&nbsp;
				<button class="btn btn-primary btn-mini" id="newRelationBtn"><i class="icon-white icon-plus-sign"></i>Relation</button> $editBtn $deleteBtn</span>
			</span>
			</div>
		</div>
		<br/>	

		<table id="noteTbl" class="table table-striped table-condensed table-bordered">
			<tr>
				<th colspan="6" class="collapseHeader" id="overview">Overview
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Name:</td>
				<td class="left tableData" colspan="5">{$note->name}</td> 
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Description:</td>
				<td class="left" colspan="5">{$note->description}</td>
			</tr>
		</table>
		
		<div class="row">
			<div class="span7">
				<table id="" class="table table-striped table-condensed table-bordered">
					<tr>
						<th colspan="4">Other Information</th>
					</tr>
					<tr>
						<td class="bold right tableTitle">Date Created:</td>
						<td class="left tableData">$format_date_entered</td>
						<td class="bold right tableTitle">Date Modified:</td>
						<td class="left tableData">$format_date_modified</td>
					</tr>
				</table>
			</div>
		</div>
END;
	$return = '<div class="row"><div class="span10">' . $table . '</div></div>';
	
	$html = SharedController::includeRelations($note);	
	return $return . $html;
}

function editNote($note, $get = null){
	global $site;
	$format_date_modified = format_date($note->date_modified);
	$format_date_entered = format_date($note->date_entered);
	$saveBtn = SharedController::save_button('note', array('noteid'=>$note->id));
	$cancelBtn = SharedController::cancel_button('note', array('noteid'=>$note->id));
	$hiddenId = SharedController::input_hidden('noteid', 'noteid', $note->id);

	if (array_key_exists('parentid', $get)){
		$hiddenParentId = SharedController::input_hidden('parentid', 'parentid', $get["parentid"]);
		$hiddenParentType = SharedController::input_hidden('parenttype', 'parenttype', $get["parenttype"]);
	}else{
		$hiddenParentId = '';
		$hiddenParentType = '';
	}

	$edit = array();

	$edit["name"] = SharedController::input_text('name', 'name', '', $note->name, '', array('class'=>'span2'));
	$edit["description"] = SharedController::input_textarea('description', 'description', '', $note->description, '', array('class'=>'span4'));

	$table = <<<END
	
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: left;width: 50%" class="left">
				<span style="" class="bold right">ID:</span>
				<span style="" class="left">{$note->id}</span>
			</span>
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">{$note->name}&nbsp;&nbsp;$saveBtn $cancelBtn</span>
			</span>
			</div>
		</div>
		<br/>	
		<br/>	
		<form id="editFrm" method="POST" class="form" action="{$site["url"]}/notes/?noteid={$note->id}">
			$hiddenId
		<table id="noteTbl" class="table table-striped table-condensed table-bordered">
			<tr>
				<th colspan="6" class="collapseHeader" id="overview">Overview
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Name:</td>
				<td class="left tableData" colspan="5">{$edit['name']}</td> 
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Description:</td>
				<td class="left" colspan="5">{$edit['description']}</td>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Resolution:</td>
				<td class="left" colspan="5">{$edit['resolution']}</td>
			</tr>
		</table>
		
		<div class="row">
			<div class="span7">
				<table id="" class="table table-striped table-condensed table-bordered">
					<tr>
						<th colspan="4">Other Information</th>
					</tr>
					<tr>
						<td class="bold right tableTitle">Date Created:</td>
						<td class="left tableData">$format_date_entered</td>
						<td class="bold right tableTitle">Date Modified:</td>
						<td class="left tableData">$format_date_modified</td>
					</tr>
				</table>
			</div>
		</div>
		$hiddenParentId $hiddenParentType
	</form>
END;
	$return = '<div class="row"><div class="span10">' . $table . '</div></div>';
	return $return;
}

function saveNotePOST($post){
	global $site;
	if (trim($post["noteid"]) !== ""){
		$editNote = new Note($post["noteid"]);
	}else{
		$editNote = new Note();
	}

	$editNote->name = $post["name"];
	$editNote->description = $post["description"];
	$editNote->deleted = 0;

	$editNote->save();

	if (array_key_exists('parentid', $post)){
		require_once($site['path'] . "/relations/controller/relation_controller.php");
		saveQuickRelation($post,$editNote);
	}

}
?>
