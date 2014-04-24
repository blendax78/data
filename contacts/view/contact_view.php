<?php
$view["name"] = "Contact";

require_once("{$site["path"]}/contacts/controller/contact_controller.php");
?>
<div class="row">
  <div class="span4"><?php print $dropdown;?></div>
  <div class="span4"><?php print $search;?></div>
</div>
<div class="row">
	<div class="span4"><button class="btn" id="newContactBtn"><i class="icon-plus-sign"></i>Add Contact</button></div>
</div>

<?php
if ($_GET["contactid"] || $contact->id){
	
	if ($_GET["edit"]){
		print editContact($contact, $_GET);
	}else{
		print viewContact($contact);
	}
}else{
	if ($_GET["new"]){
		print editContact($contact, $_GET);
	}else{
		print listContacts($contacts, $_GET["start"], $_GET["stop"]);
	}
}

?>
