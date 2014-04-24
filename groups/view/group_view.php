<?php
$view["name"] = "Group";

require_once("{$site["path"]}/groups/controller/group_controller.php");
?>
<div class="row">
  <div class="span4"><?php print $dropdown;?></div>
</div>
<div class="row">
	<div class="span4"><button class="btn" id="newGroupBtn"><i class="icon-plus-sign"></i>Add Group</button></div>
</div>

<?php
if ($_GET["groupid"] || $group->id){
	
	if ($_GET["edit"]){
		print editGroup($group, $_GET);
	}else{
		print viewGroup($group);
	}
}else{
	if ($_GET["new"]){
		print editGroup($group, $_GET);
	}else{
		print listGroups($groups, $_GET["start"], $_GET["stop"]);
	}
}

?>
