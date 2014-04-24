<?php
require_once("{$site["path"]}/groups/model/group.class.php");
require_once("{$site["path"]}/shared/controller/shared_controller.php");

$group = new Group();

if (isset($_GET["groupid"]) && trim($_GET["groupid"]) !== '' ){
	if (isset($_POST["groupid"]) && !array_key_exists('newRelation',$_POST)){
		saveGroupPOST($_POST);
	}

	$group->findById($_GET["groupid"]);
	
}else{
	if (isset($_POST["groupid"])){
		saveGroupPOST($_POST);
		$group->getNewest();
	}
}

$groups = $group->getAll("name");
$dropdown = '<form method="GET" class="form-inline well" id="groupviewform" >';
$dropdown .= SharedController::allObjDropDown($groups, $_GET["groupid"],'$("#groupviewform").submit();',array('name'));
$dropdown .= '&nbsp;<input type="submit" class="btn" value="View"/></form>';

function listGroups($groups, $start = 0, $stop = 10){

	global $site;
	if (is_null($start)){
		$start = 0;
		$stop = 10;
	}
	if (count($groups) > 0){
		$row = "";
		$page = "";
		$count = 0;
		
		foreach ($groups as $group){
			$format_date_modified = format_date($group->date_modified);
			$format_date_entered = format_date($group->date_entered);
			
			if($count >= $start && $count < $stop){
				$row .= "<tr>
					<td ><a href=\"{$site["url"]}/groups/?groupid={$group->id}\">{$group->name}</a></td>
					<td >{$format_date_entered}</td>
					<td >{$format_date_modified}</td> 
				</tr>";
			}
			$count++;	
		}
	}
	$table = <<<END
	<br/>
		<table id="groupsTbl" class="table table-striped table-condensed table-bordered ">
			<tr>
				<th>Group Name</th>
				<th>Date Created</th>
				<th>Date Modified</th>
			</tr>
			$row
		</table>
END;

	$return = '<div class="row"><div class="span10">' . $table . '</div></div>';
	$return .= SharedController::pager('groups', $groups, $start, $stop);
	return $return;
}


function viewGroup($group){

	$format_date_modified = format_date($group->date_modified);
	$format_date_entered = format_date($group->date_entered);
	$editBtn = SharedController::edit_button('group', array('groupid'=>$group->id));
	$deleteBtn = SharedController::delete_button('group', array('groupid'=>$group->id));
	$group->description = nl2br($group->description);

	$table = <<<END
	
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: left;width: 50%" class="left">
				<span style="" class="bold right">ID:</span>
				<span style="" class="left">{$group->id}</span>
			</span>
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">{$group->name}&nbsp;&nbsp;
				<button class="btn btn-primary btn-mini" id="newRelationBtn"><i class="icon-white icon-plus-sign"></i>Relation</button> $editBtn $deleteBtn</span>
			</span>
			</div>
		</div>
		<br/>	

		<table id="groupTbl" class="table table-striped table-condensed table-bordered">
			<tr>
				<th colspan="6" class="collapseHeader" id="overview">Overview
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Name:</td>
				<td class="left tableData" colspan="5">{$group->name}</td> 
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Description:</td>
				<td class="left" colspan="5">{$group->description}</td>
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
	$html = SharedController::includeRelations($group);	
	return $return . $html;
}

function editGroup($group, $get = null){
	global $site;
	$format_date_modified = format_date($group->date_modified);
	$format_date_entered = format_date($group->date_entered);
	$saveBtn = SharedController::save_button('group', array('groupid'=>$group->id));
	$cancelBtn = SharedController::cancel_button('group', array('groupid'=>$group->id));
	$hiddenId = SharedController::input_hidden('groupid', 'groupid', $group->id);

	if (array_key_exists('parentid', $get)){
		$hiddenParentId = SharedController::input_hidden('parentid', 'parentid', $get["parentid"]);
		$hiddenParentType = SharedController::input_hidden('parenttype', 'parenttype', $get["parenttype"]);
	}else{
		$hiddenParentId = '';
		$hiddenParentType = '';
	}

	$edit = array();

	$edit["name"] = SharedController::input_text('name', 'name', '', $group->name, '', array('class'=>'span2'));
	$edit["description"] = SharedController::input_textarea('description', 'description', '', $group->description, '', array('class'=>'span4'));

	$table = <<<END
	
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: left;width: 50%" class="left">
				<span style="" class="bold right">ID:</span>
				<span style="" class="left">{$group->id}</span>
			</span>
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">{$group->name}&nbsp;&nbsp;$saveBtn $cancelBtn</span>
			</span>
			</div>
		</div>
		<br/>	
		<br/>	
		<form id="editFrm" method="POST" class="form" action="{$site["url"]}/groups/?groupid={$group->id}">
			$hiddenId
		<table id="groupTbl" class="table table-striped table-condensed table-bordered">
			<tr>
				<th colspan="6" class="collapseHeader" id="overview">Overview
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Name:</td>
				<td class="left tableData" colspan="5">{$edit["name"]}</td> 
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Description:</td>
				<td class="left" colspan="5">{$edit["description"]}</td>
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

function saveGroupPOST($post){
	global $site;

	if (trim($post["groupid"]) !== ""){
		$editGroup = new Group($post["groupid"]);
	}else{
		$editGroup = new Group();
	}

	$editGroup->name = $post["name"];
	$editGroup->description = $post["description"];
	$editGroup->deleted = 0;

	$editGroup->save();

	if (array_key_exists('parentid', $post)){
		require_once($site['path'] . "/relations/controller/relation_controller.php");
		saveQuickRelation($post,$editGroup);
	}

}
?>
