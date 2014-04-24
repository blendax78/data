<?php
require_once("{$site["path"]}/events/model/event.class.php");
require_once("{$site["path"]}/shared/controller/shared_controller.php");

$event = new Event();

if (isset($_GET["eventid"]) && trim($_GET["eventid"]) !== '' ){
	if (isset($_POST["eventid"]) && !array_key_exists('newRelation',$_POST)){
		saveEventPOST($_POST);
	}

	$event->findById($_GET["eventid"]);
	
}else{
	if (isset($_POST["eventid"])){
		saveEventPOST($_POST);
		$event->getNewest();
	}
}

$events = $event->getAll("name"); #date_entered desc to order by created desc
$dropdown = '<form method="GET" class="form-inline well" id="eventviewform" >';
$dropdown .= SharedController::allObjDropDown($events, $_GET["eventid"],'$("#eventviewform").submit();',array('name'));
$dropdown .= '&nbsp;<input type="submit" class="btn" value="View"/></form>';

function listEvents($events, $start = 0, $stop = 10){
	global $site;
	if (is_null($start)){
		$start = 0;
		$stop = 10;
	}
	if (count($events) > 0){
		$row = "";
		$page = "";
		$count = 0;
		
		foreach ($events as $event){
			$format_date_modified = format_date($event->date_modified);
			$format_date_entered = format_date($event->date_entered);
			
			if($count >= $start && $count < $stop){
				$row .= "<tr>
					<td ><a href=\"{$site["url"]}/events/?eventid={$event->id}\">{$event->name}</a></td>
					<td>{$event->type}</td>
					<td>{$event->status}</td>
					<td >{$format_date_entered}</td>
					<td >{$format_date_modified}</td> 
				</tr>";
			}
			$count++;	
		}
	}
	$table = <<<END
	<br/>
		<table id="eventsTbl" class="table table-striped table-condensed table-bordered ">
			<tr>
				<th>Event Name</th>
				<th>Event Type</th>
				<th>Event Status</th>
				<th>Date Created</th>
				<th>Date Modified</th>
			</tr>
			$row
		</table>
END;

	$return = '<div class="row"><div class="span10">' . $table . '</div></div>';
	$return .= SharedController::pager('events', $events, $start, $stop);
	return $return;
}


function viewEvent($event){

	$format_date_modified = format_date($event->date_modified);
	$format_date_entered = format_date($event->date_entered);
	$editBtn = SharedController::edit_button('event', array('eventid'=>$event->id));
	$deleteBtn = SharedController::delete_button('event', array('eventid'=>$event->id));
	$event->description = nl2br($event->description);

	$table = <<<END
	
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: left;width: 50%" class="left">
				<span style="" class="bold right">ID:</span>
				<span style="" class="left">{$event->id}</span>
			</span>
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">{$event->name}&nbsp;&nbsp;
				<button class="btn btn-primary btn-mini" id="newRelationBtn"><i class="icon-white icon-plus-sign"></i>Relation</button> $editBtn $deleteBtn</span>
			</span>
			</div>
		</div>
		<br/>	

		<table id="eventTbl" class="table table-striped table-condensed table-bordered">
			<tr>
				<th colspan="6" class="collapseHeader" id="overview">Overview
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Name:</td>
				<td class="left tableData" colspan="5">{$event->name}</td> 
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Type:</td>
				<td class="left tableData">{$event->type}</td> 
				<td class="bold right tableTitle">Status:</td>
				<td class="left tableData">{$event->status}</td>
				<td colspan="2"></td>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Description:</td>
				<td class="left" colspan="5">{$event->description}</td>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Resolution:</td>
				<td class="left" colspan="5">{$event->resolution}</td>
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
	$html = SharedController::includeRelations($event);	
	return $return . $html;
}

function editEvent($event, $get = null){
	global $site;
	$format_date_modified = format_date($event->date_modified);
	$format_date_entered = format_date($event->date_entered);
	$saveBtn = SharedController::save_button('event', array('eventid'=>$event->id));
	$cancelBtn = SharedController::cancel_button('event', array('eventid'=>$event->id));
	$hiddenId = SharedController::input_hidden('eventid', 'eventid', $event->id);

	if (array_key_exists('parentid', $get)){
		$hiddenParentId = SharedController::input_hidden('parentid', 'parentid', $get["parentid"]);
		$hiddenParentType = SharedController::input_hidden('parenttype', 'parenttype', $get["parenttype"]);
	}else{
		$hiddenParentId = '';
		$hiddenParentType = '';
	}

	$edit = array();

	$edit["name"] = SharedController::input_text('name', 'name', '', $event->name, '', array('class'=>'span2'));
	$edit["description"] = SharedController::input_textarea('description', 'description', '', $event->description, '', array('class'=>'span4'));
	$edit["resolution"] = SharedController::input_textarea('resolution', 'resolution', '', $event->resolution, '', array('class'=>'span4'));
	$edit["type"] = eventTypeDropDown($event->type);
	$edit["status"] = eventStatusDropDown($event->status);
	
	
	$table = <<<END
	
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: left;width: 50%" class="left">
				<span style="" class="bold right">ID:</span>
				<span style="" class="left">{$event->id}</span>
			</span>
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">{$event->name}&nbsp;&nbsp;$saveBtn $cancelBtn</span>
			</span>
			</div>
		</div>
		<br/>	
		<br/>	
		<form id="editFrm" method="POST" class="form" action="{$site["url"]}/events/?eventid={$event->id}">
			$hiddenId
		<table id="eventTbl" class="table table-striped table-condensed table-bordered">
			<tr>
				<th colspan="6" class="collapseHeader" id="overview">Overview
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Name:</td>
				<td class="left tableData" colspan="5">{$edit['name']}</td> 
			</tr>
			<tr class="personalInformationCollapse">
				<td class="bold right tableTitle">Type:</td>
				<td class="left tableData">{$edit['type']}</td> 
				<td class="bold right tableTitle">Status:</td>
				<td class="left tableData">{$edit['status']}</td>
				<td colspan="2"></td>
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

function eventTypeDropDown($selected = null){
	$db = new Database();
	$types = array();

	$typesList = $db->fetch_array('select * from event_type order by orderby;');
	foreach ($typesList as $typeList){
		$types[] = (object) array('id'=>$typeList["type_name"],'type_name'=>$typeList["type_name"]);
	}
	
	$typeDropdown = SharedController::allObjDropDown($types, $selected, '', array('type_name'),array('name'=>'type','id'=>'type'));
	return $typeDropdown;
}

function eventStatusDropDown($selected = null){
	$db = new Database();
	$statuss = array();

	$statussList = $db->fetch_array('select * from event_status order by orderby;');
	foreach ($statussList as $statusList){
		$statuss[] = (object) array('id'=>$statusList['status_name'],'status_name'=>$statusList['status_name']);
	}
	
	$statusDropdown = SharedController::allObjDropDown($statuss, $selected, '', array('status_name'),array('name'=>'status','id'=>'status'));
	return $statusDropdown;
}

function saveEventPOST($post){
	global $site;
	if (trim($post["eventid"]) !== ""){
		$editEvent = new Event($post["eventid"]);
	}else{
		$editEvent = new Event();
	}

	$editEvent->name = $post["name"];
	$editEvent->description = $post["description"];
	$editEvent->resolution = $post["resolution"];
	$editEvent->type = $post["type"];
	$editEvent->status = $post["status"];
	$editEvent->deleted = 0;

	$editEvent->save();

	if (array_key_exists('parentid', $post)){
		require_once($site['path'] . "/relations/controller/relation_controller.php");
		saveQuickRelation($post,$editEvent);
	}

}
?>
