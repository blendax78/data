<?php
require_once("{$site["path"]}/documents/model/document.class.php");
require_once("{$site["path"]}/shared/controller/shared_controller.php");

$document = new Document();

if (isset($_GET["documentid"]) && trim($_GET["documentid"]) !== '' ){
	if (isset($_POST["documentid"]) && !array_key_exists('newRelation',$_POST)){
		saveDocumentPOST($_POST);
	}

	$document->findById($_GET["documentid"]);
	
}else{
	if (isset($_POST["documentid"])){
		saveDocumentPOST($_POST);
		$document->getNewest();
	}
}

$documents = $document->getAll("document_name");
$dropdown = '<form method="GET" class="form-inline well" id="groupviewform" >';
$dropdown .= SharedController::allObjDropDown($documents, $_GET["documentid"],'$("#documentviewform").submit();',array('document_name'));
$dropdown .= '&nbsp;<input type="submit" class="btn" value="View"/></form>';

function listDocuments($documents, $start = 0, $stop = 10){
	global $site;
	if (is_null($start)){
		$start = 0;
		$stop = 10;
	}
	if (count($documents) > 0){
		$row = "";
		$page = "";
		$count = 0;
		
		foreach ($documents as $document){
			$format_date_modified = format_date($document->date_modified);
			$format_date_entered = format_date($document->date_entered);
			
			if($count >= $start && $count < $stop){
				$row .= "<tr>
					<td ><a href=\"{$site["url"]}/documents/?documentid={$document->id}\">{$document->document_name}</a></td>
					<td >{$document->template_type}</td>
					<td >{$document->category_id}</td>
					<td >{$document->status_id}</td>
					<td >{$format_date_entered}</td>
					<td >{$format_date_modified}</td> 
				</tr>";
			}
			$count++;	
		}
	}
	$table = <<<END
	<br/>
		<table id="documentsTbl" class="table table-striped table-condensed table-bordered ">
			<tr>
				<th>Document Name</th>
				<th>Type</th>
				<th>Category</th>
				<th>Status</th>
				<th>Date Created</th>
				<th>Date Modified</th>
			</tr>
			$row
		</table>
END;

	$return = '<div class="row"><div class="span10">' . $table . '</div></div>';
	$return .= SharedController::pager('documents', $documents, $start, $stop);
	return $return;
}

function viewDocument($document){

	$format_date_modified = format_date($document->date_modified);
	$format_date_entered = format_date($document->date_entered);
	$editBtn = SharedController::edit_button('document', array('documentid'=>$document->id));
	$deleteBtn = SharedController::delete_button('document', array('documentid'=>$document->id));
	$document->description = nl2br($document->description);

	$table = <<<END
	
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: left;width: 50%" class="left">
				<span style="" class="bold right">ID:</span>
				<span style="" class="left">{$document->id}</span>
			</span>
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">{$document->document_name}&nbsp;&nbsp;
				<button class="btn btn-primary btn-mini" id="newRelationBtn"><i class="icon-white icon-plus-sign"></i>Relation</button> $editBtn $deleteBtn</span>
			</span>
			</div>
		</div>
		<div class="row">
			<div class="span4"><a title="Download" href="{$site["url"]}/download.php?f={$document->id}" target="_blank">Download File</a></div>
		</div>
		<br/>	

		<table id="groupTbl" class="table table-striped table-condensed table-bordered">
			<tr>
				<th colspan="6" class="collapseHeader" id="overview">Overview
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Name:</td>
				<td class="left tableData" colspan="5">{$document->document_name}</td> 
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Type:</td>
				<td class="left tableData">{$document->template_type}</td>
				<td class="bold right tableTitle">Category:</td>
				<td class="left tableData">{$document->category_id}</td>
				<td class="bold right tableTitle">Status:</td>
				<td class="left tableData">{$document->status_id}</td>
			</tr>
			<tr class="overviewCollapse">
				<td class="bold right tableTitle">Description:</td>
				<td class="left" colspan="5">{$document->description}</td>
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
	$html = SharedController::includeRelations($document);	
	return $return . $html;
}

function editDocument($document){
	global $site;
	$format_date_modified = format_date($document->date_modified);
	$format_date_entered = format_date($document->date_entered);
	$saveBtn = SharedController::save_button('document', array('documentid'=>$document->id));
	$cancelBtn = SharedController::cancel_button('document', array('documentid'=>$document->id));
	$hiddenId = SharedController::input_hidden('documentid', 'documentid', $document->id);
	$edit = array();

	$edit["document_name"] = SharedController::input_text('document_name', 'document_name', '', $document->document_name, '', array('class'=>'span4'));
	$edit["description"] = SharedController::input_textarea('description', 'description', '', $document->description, '', array('class'=>'span5',"rows"=>"4"));
	
	#$edit["category_id"] = $document->category_id;
	
	$edit["category_id"] = categoryDropDown($document->category_id);
	$edit["template_type"] = templateTypeDropDown($document->template_type);
	$edit["status_id"] = statusDropDown($document->status_id);

	$table = <<<END
	
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: left;width: 50%" class="left">
				<span style="" class="bold right">ID:</span>
				<span style="" class="left">{$document->id}</span>
			</span>
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">{$document->name}&nbsp;&nbsp;$saveBtn $cancelBtn</span>
			</span>
			</div>
		</div>
		<br/>	
		<form id="editFrm" method="POST" class="form" action="{$site["url"]}/documents/?documentid={$document->id}">
			$hiddenId
			<table id="documentTbl" class="table table-striped table-condensed table-bordered">
				<tr>
					<th colspan="6" class="collapseHeader" id="overview">Overview
					<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th>
				</tr>
				<tr class="overviewCollapse">
					<td class="bold right tableTitle">Name:</td>
					<td class="left tableData" colspan="5">{$edit["document_name"]}</td> 
				</tr>
				<tr>
					<td class="bold right tableTitle">Type:</td>
					<td class="left tableData">{$edit["template_type"]}</td>
					<td class="bold right tableTitle">Category:</td>
					<td class="left tableData">{$edit["category_id"]}</td>
					<td class="bold right tableTitle">Status:</td>
					<td class="left tableData">{$edit["status_id"]}</td>
				</tr>
				<tr class="overviewCollapse">
					<td class="bold right tableTitle">Description:</td>
					<td class="left" colspan="5">{$edit["description"]}</td>
				</tr>
			</table>
		</form>
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
	return $return;
}

function saveDocumentPOST($post){
	global $site;
	if (trim($post["documentid"]) !== ""){
		$editDocument = new Document($post["documentid"]);
		$editDocument->document_name = $post["document_name"];
		$editDocument->description = $post["description"];
		$editDocument->deleted = 0;
		$editDocument->template_type = $post["template_type"];
		$editDocument->category_id = $post["category_id"];
		$editDocument->status_id = $post["status_id"];
		$editDocument->save();
	}else{
		$editDocument = new Document();
		$editDocument->document_name = $post["document_name"];
		$editDocument->description = $post["description"];
		$editDocument->deleted = 0;
		$editDocument->template_type = $post["template_type"];
		$editDocument->category_id = $post["category_id"];
		$editDocument->status_id = $post["status_id"];
		$editDocument->doc_url = $post["file_name"];
		$editDocument->save();

		moveFile($post["file_name"], $editDocument->id);
	}

	if (array_key_exists("reltype", $post)) {
		require_once("{$site["path"]}/relations/controller/relation_controller.php");
		$r = array();
		$r["relationid"] = "";
		$r["parenttype"] = "document";
		$r["parentid"] = $editDocument->id;
		$r["{$post["reltype"]}id"] = $post["relid"];
		saveRelationPOST($r);
	}
}

function drawNewDocumentModal($reltype = null, $relid = null){
	global $site;
	$upload_path = UPLOAD_PATH;
	$typeDropDown = templateTypeDropDown();
	$catDropDown = categoryDropDown();
	$statusDropDown = statusDropDown();
	$filesDropDown = drawFilesDropDown();
	$description = SharedController::input_textarea('description', 'description', '', '', '', array('class'=>'span5',"rows"=>"4",'placeholder'=>'Enter a description'));
	$name = SharedController::input_text('document_name', 'document_name', '', '', '', array('class'=>'span4','placeholder'=>'Enter a name'));
	$hidden = SharedController::input_hidden('documentid', 'documentid', ''); 
	$hiddenreltype = (!is_null($reltype)) ? SharedController::input_hidden('reltype', 'reltype', $reltype) : ""; 
	$hiddenrelid = (!is_null($relid)) ? SharedController::input_hidden('relid', 'relid', $relid) : ""; 
	$new = <<<END
	
	<div id="newDocumentModal" class="modal hide fade in" style="display: none; ">  
		<div class="modal-header">  
			<a class="close" data-dismiss="modal">×</a>  
			<h3>New Document</h3>  
		</div>  
		<div class="modal-body">    
			<form name="newDocumentFrm" id="newDocumentFrm" class="form-vertical" method="POST" action="{$site["url"]}/documents/">
				<label>Document Name</label>
  				$name
  				<label>Description</label>
  				$description
  				<label>Document Type</label>
  				$typeDropDown
  				<label>Document Category</label>
  				$catDropDown
  				<label>Document Status</label>
  				$statusDropDown
  				<label>File Name</label>
  				$filesDropDown
  				$hidden
				$hiddenreltype
				$hiddenrelid
			</form>
			<div class="alert alert-info">Files must be uploaded to $upload_path/</div>
		</div>
		<div class="modal-footer">  
			<a href="#" class="btn btn-success">Save Document</a>  
			<a href="#" class="btn" data-dismiss="modal">Close</a>  
		</div>  
	</div>
END;

	return $new;
}

function categoryDropDown($selected = null){
	$db = new Database();
	$cats = array();

	$catsList = $db->fetch_array('select * from document_categories order by orderby;');
	foreach ($catsList as $catList){
		$cats[] = (object) array('id'=>$catList['cat_name'], 'cat_name'=>$catList['cat_name']);
	}
	
	$catDropdown = SharedController::allObjDropDown($cats, $selected, '', array('cat_name'),array('name'=>'category_id','id'=>'category_id'));
	return $catDropdown;
}

function templateTypeDropDown($selected = null){
	$db = new Database();
	$types = array();

	$typesList = $db->fetch_array('select * from document_type order by orderby;');
	foreach ($typesList as $typeList){
		$types[] = (object) array('id'=>$typeList["type_name"],'type_name'=>$typeList["type_name"]);
	}
	
	$typeDropdown = SharedController::allObjDropDown($types, $selected, '', array('type_name'),array('name'=>'template_type','id'=>'template_type'));
	return $typeDropdown;
}

function statusDropDown($selected = null){
	$db = new Database();
	$statuss = array();

	$statussList = $db->fetch_array('select * from document_status order by orderby;');
	foreach ($statussList as $statusList){
		$statuss[] = (object) array('id'=>$statusList["status_name"],'status_name'=>$statusList["status_name"]);
	}
	
	$statusDropdown = SharedController::allObjDropDown($statuss, $selected, '', array('status_name'),array('name'=>'status_id','id'=>'status_id'));
	return $statusDropdown;
}

function drawFilesDropDown(){
	$dir = opendir(UPLOAD_PATH);
	$fileArray = array();
    while (false !== ($entry = readdir($dir))) {
    	if (!in_array($entry,array(".",".."))){
			$fileArray[] = (object) array("id"=>$entry);
    	}
    }

    $dropdown = SharedController::allObjDropDown($fileArray,"","",array("id"),array('name'=>'file_name','id'=>'file_name'));
	return $dropdown;
}

function moveFile($filename, $id){
	if (copy(UPLOAD_PATH . "/" . $filename, SAVED_FILES_PATH . "/" . $id)) {
	  unlink(UPLOAD_PATH . "/" . $filename);
	}
}
?>
