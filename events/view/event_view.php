<?php
$view["name"] = "Event";

require_once("{$site["path"]}/events/controller/event_controller.php");
?>
<div class="row">
  <div class="span4"><?php print $dropdown;?></div>
</div>
<div class="row">
	<div class="span4"><button class="btn" id="newEventBtn"><i class="icon-plus-sign"></i>Add Event</button></div>
</div>

<?php
if ($_GET["eventid"] || $event->id){
	
	if ($_GET["edit"]){
		print editEvent($event, $_GET);
	}else{
		print viewEvent($event);
	}
}else{
	if ($_GET["new"]){
		print editEvent($event, $_GET);
	}else{
		print listEvents($events, $_GET["start"], $_GET["stop"]);
	}
}

?>
