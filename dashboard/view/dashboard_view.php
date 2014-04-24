<?php
$view["name"] = "Dashboard";

require_once("{$site["path"]}/dashboard/controller/dashboard_controller.php");

?>

<div class="row">
	<div class="span5 "><h3>Contacts</h3><?php echo drawDashboardTable($contacts, array('first_name'=>'Name','last_name'=>''));?></div>
	<div class="span1">&nbsp;</div>
	<div class="span5 "><h3>Groups</h3><?php echo drawDashboardTable($groups, array('name'=>'Name'));?></div>
</div>
<div class="row">
	<div class="span5 "><h3>Events</h3><?php echo drawDashboardTable($events, array('name'=>'Name'));?></div>
	<div class="span1">&nbsp;</div>
	<div class="span5 "><h3>Notes</h3><?php echo drawDashboardTable($notes, array('name'=>'Name'));?></div>
</div>
<div class="row">
	<div class="span5 "><h3>Documents</h3><?php echo drawDashboardTable($documents, array('document_name'=>'Document Name'));?></div>
</div>
