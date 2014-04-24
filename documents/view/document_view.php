<?php
$view["name"] = "Document";

require_once("{$site["path"]}/documents/controller/document_controller.php");
?>
<div class="row">
  <div class="span4"><?php print $dropdown;?></div>
</div>
<div class="row">
	<div class="span4"><button class="btn" id="newDocumentBtn"><i class="icon-plus-sign"></i>Add Document</button></div>
</div>

<?php
if ($_GET["documentid"] || $document->id){
	
	if ($_GET["edit"]){
		print editDocument($document);
	}else{
		print viewDocument($document);
	}
}else{
	if ($_GET["new"]){
		print saveDocumentPOST($_POST);
	}else{
		print listDocuments($documents, $_GET["start"], $_GET["stop"]);
	}
}
	
print drawNewDocumentModal();

?>

