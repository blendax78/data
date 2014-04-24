<?php
$view["name"] = "Note";

require_once("{$site["path"]}/notes/controller/note_controller.php");
?>
<div class="row">
  <div class="span4"><?php print $dropdown;?></div>
</div>
<div class="row">
	<div class="span4"><button class="btn" id="newNoteBtn"><i class="icon-plus-sign"></i>Add Note</button></div>
</div>

<?php
if ($_GET["noteid"] || $note->id){
	
	if ($_GET["edit"]){
		print editNote($note, $_GET);
	}else{
		print viewNote($note);
	}
}else{
	if ($_GET["new"]){
		print editNote($note, $_GET);
	}else{
		print listNotes($notes, $_GET["start"], $_GET["stop"]);
	}
}

?>
