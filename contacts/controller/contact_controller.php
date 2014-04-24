<?php
require_once("{$site["path"]}/contacts/model/contact.class.php");
require_once("{$site["path"]}/shared/controller/shared_controller.php");

$contact = new Contact();

if (isset($_GET["contactid"]) && trim($_GET["contactid"]) !== '' ){
	if (isset($_POST["contactid"]) && !array_key_exists('newRelation',$_POST)){
		saveContactPOST($_POST);
	}

	$contact->findById($_GET["contactid"]);
	
}else{
	if (isset($_POST["contactid"])){
		saveContactPOST($_POST);
		$contact->getNewest();
	}
}

$contacts = $contact->getAll("first_name,last_name");
$dropdown = '<form method="GET" class="form-inline well" id="contactviewform" >';
$dropdown .= SharedController::allObjDropDown($contacts, $_GET["contactid"],'$("#contactviewform").submit();',array('first_name','last_name'));
$dropdown .= '&nbsp;<input type="submit" class="btn" value="View"/></form>';

$search = SharedController::drawSearch($contact);

function listContacts($contacts, $start = 0, $stop = 10){
	global $site;
	if (is_null($start)){
		$start = 0;
		$stop = 10;
	}
	if (count($contacts) > 0){
		$row = "";
		$page = "";
		$count = 0;
		
		foreach ($contacts as $contact){
			$format_date_modified = format_date($contact->date_modified);
			$format_date_entered = format_date($contact->date_entered);
			
			if($count >= $start && $count < $stop){
				$row .= "<tr>
					<td ><a href=\"{$site["url"]}/contacts/?contactid={$contact->id}\">{$contact->first_name} {$contact->last_name}</a></td>
					<td >{$contact->email1}</td>
					<td >{$contact->phone_mobile}</td>
					<td >{$format_date_entered}</td>
					<td >{$format_date_modified}</td> 
				</tr>";
			}
			$count++;	
		}
	}
	$table = <<<END
	<br/>
		<table id="contactsTbl" class="table table-striped table-condensed table-bordered ">
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Cell Phone</th>
				<th>Date Created</th>
				<th>Date Modified</th>
			</tr>
			$row
		</table>
END;

	$return = '<div class="row"><div class="span10">' . $table . '</div></div>';
	$return .= SharedController::pager('contacts', $contacts, $start, $stop);
	return $return;
}


function viewContact($contact){

	$format_date_modified = format_date($contact->date_modified);
	$format_date_entered = format_date($contact->date_entered);
	$editBtn = SharedController::edit_button('contact', array('contactid'=>$contact->id));
	$deleteBtn = SharedController::delete_button('contact', array('contactid'=>$contact->id));
	$contact->description = nl2br($contact->description);

	$table = <<<END
	
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: left;width: 50%" class="left">
				<span style="" class="bold right">ID:</span>
				<span style="" class="left">{$contact->id}</span>
			</span>
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">{$contact->first_name} {$contact->last_name}&nbsp;&nbsp;
	 			<button class="btn btn-primary btn-mini" id="newRelationBtn"><i class="icon-white icon-plus-sign"></i>Relation</button> $editBtn $deleteBtn</span>
			</span>
			</div>
		</div>
		<br/>	

		<table id="contactTbl" class="table table-striped table-condensed table-bordered">
			<tr>
				<th colspan="6" class="collapseHeader" id="personalInformation">Personal Information
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th>
			</tr>
			<tr class="personalInformationCollapse">
				<td class="bold right tableTitle">Name:</td>
				<td class="left tableData">{$contact->salutation} {$contact->first_name} {$contact->last_name}</td> 
				<td class="bold right tableTitle">Middle Name:</td>
				<td class="left tableData">{$contact->middle_name}</td>
				<td class="bold right tableTitle">Nick Name:</td>
				<td class="left tableData">{$contact->nick_name}</td>
			</tr>
			<tr class="personalInformationCollapse">
				<td class="bold right tableTitle">Birth Date:</td>
				<td class="left tableData">{$contact->birth_date}</td>
				<td class="bold right tableTitle">Company:</td>
				<td class="left tableData">{$contact->company}</td>
				<td class="bold right tableTitle">Job:</td>
				<td class="left tableData">{$contact->job}</td>
			</tr>
			<tr class="personalInformationCollapse">
				<td class="bold right tableTitle">Description:</td>
				<td class="left" colspan="5">{$contact->description}</td>
			</tr>
		</table>
		<table id="" class="table table-striped table-condensed table-bordered">
			<tr class="collapseHeader" id="contactInformation">
				<th colspan="6">Contact Information
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th></th>
			</tr>
			<tr class="contactInformationCollapse">
				<td class="bold right tableTitle">Email 1:</td>
				<td class="left tableData">{$contact->email1}</td>
				<td class="bold right tableTitle">Email 2:</td>
				<td class="left tableData">{$contact->email2}</td>
				<td class="" colspan="2"></td>
			</tr>
			<tr class="contactInformationCollapse">
				<td class="bold right tableTitle">Home Phone:</td>
				<td class="left tableData">{$contact->phone_home}</td>
				<td class="bold right tableTitle">Cell Phone:</td>
				<td class="left tableData">{$contact->phone_mobile}</td>
				<td class="bold right tableTitle">Work Phone:</td>
				<td class="left tableData">{$contact->phone_work}</td>
			</tr>
			<tr class="contactInformationCollapse">
				<td class="bold right tableTitle">Fax:</td>
				<td class="left tableData">{$contact->phone_fax}</td>
				<td class="bold right tableTitle">Other Phone:</td>
				<td class="left tableData">{$contact->phone_other}</td>
				<td class="" colspan="2"></td>
			</tr>
		</table>
		<table id="" class="table table-striped table-condensed table-bordered">
			<tr class="collapseHeader" id="primaryAddress">
				<th colspan="6">Primary Address
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th></th>
			</tr>
			<tr class="primaryAddressCollapse">
				<td class="bold right tableTitle">Street:</td>
				<td class="left tableData">{$contact->primary_address_street}</td>
				<td class="bold right tableTitle">City:</td>
				<td class="left tableData">{$contact->primary_address_city}</td>
				<td class="bold right tableTitle">State:</td>
				<td class="left tableData">{$contact->primary_address_state}</td>
			</tr>
			<tr class="primaryAddressCollapse">
				<td class="bold right tableTitle">Zip:</td>
				<td class="left tableData">{$contact->primary_address_postalcode}</td>
				<td class="bold right tableTitle">Country:</td>
				<td class="left tableData">{$contact->primary_address_country}</td>
				<td class="" colspan="2"></td>
			</tr>
		</table>
		<table id="" class="table table-striped table-condensed table-bordered">
			<tr class="collapseHeader" id="alternateAddress">
				<th colspan="6">Alternate Address
				<span class="chevron" style="position:relative; float:right"><i class=" icon-chevron-down"></i></span></th></th>
			</tr>
			<tr class="alternateAddressCollapse">
				<td class="bold right tableTitle">Street:</td>
				<td class="left tableData">{$contact->alt_address_street}</td>
				<td class="bold right tableTitle">City:</td>
				<td class="left tableData">{$contact->alt_address_city}</td>
				<td class="bold right tableTitle">State:</td>
				<td class="left tableData">{$contact->alt_address_state}</td>
			</tr>
			<tr class="alternateAddressCollapse">
				<td class="bold right tableTitle">Zip:</td>
				<td class="left tableData">{$contact->alt_address_postalcode}</td>
				<td class="bold right tableTitle">Country:</td>
				<td class="left tableData">{$contact->alt_address_country}</td>
				<td class="" colspan="2"></td>
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
	
	$html = SharedController::includeRelations($contact);	
	return $return . $html;
}

function editContact($contact, $get = null){
	global $site;
	$format_date_modified = format_date($contact->date_modified);
	$format_date_entered = format_date($contact->date_entered);
	$saveBtn = SharedController::save_button('contact', array('contactid'=>$contact->id));
	$cancelBtn = SharedController::cancel_button('contact', array('contactid'=>$contact->id));
	$hiddenId = SharedController::input_hidden('contactid', 'contactid', $contact->id);

	if (array_key_exists('parentid', $get)){
		$hiddenParentId = SharedController::input_hidden('parentid', 'parentid', $get["parentid"]);
		$hiddenParentType = SharedController::input_hidden('parenttype', 'parenttype', $get["parenttype"]);
	}else{
		$hiddenParentId = '';
		$hiddenParentType = '';
	}

	$edit = array();
	
	$edit["first_name"] = SharedController::input_text('first_name', 'first_name', '', $contact->first_name, '', array('class'=>'span2'));
	$edit["last_name"] = SharedController::input_text('last_name', 'last_name', '', $contact->last_name, '', array('class'=>'span2'));
	$edit["middle_name"] = SharedController::input_text('middle_name', 'middle_name', '', $contact->middle_name, '', array('class'=>'span2'));
	$edit["nick_name"] = SharedController::input_text('nick_name', 'nick_name', '', $contact->nick_name, '', array('class'=>'span2'));
	$edit["salutation"] = SharedController::input_dropdown('salutation', 'salutation', array('Mr.'=>'Mr.','Mrs.'=>'Mrs.','Ms.'=>'Ms.','Miss'=>'Miss','Dr.'=>'Dr.'),$contact->salutation,'',array('class'=>'span2'));
	
	$edit["birth_date"] = SharedController::input_text('birth_date', 'birth_date', '', $contact->birth_date, '', array('class'=>'span2'));
	$edit["company"] = SharedController::input_text('company', 'company', '', $contact->company, '', array('class'=>'span2'));
	$edit["job"] = SharedController::input_text('job', 'job', '', $contact->job, '', array('class'=>'span2'));
	$edit["description"] = SharedController::input_textarea('description', 'description', '', $contact->description, '', array('class'=>'span4'));
	
	$edit["email1"] = SharedController::input_text('email1', 'email1', '', $contact->email1, '', array('class'=>'span2'));
	$edit["email2"] = SharedController::input_text('email2', 'email2', '', $contact->email2, '', array('class'=>'span2'));
	$edit["phone_mobile"] = SharedController::input_text('phone_mobile', 'phone_mobile', '', $contact->phone_mobile, '', array('class'=>'span2'));
	$edit["phone_home"] = SharedController::input_text('phone_home', 'phone_home', '', $contact->phone_home, '', array('class'=>'span2'));
	$edit["phone_work"] = SharedController::input_text('phone_work', 'phone_work', '', $contact->phone_work, '', array('class'=>'span2'));
	$edit["phone_fax"] = SharedController::input_text('phone_fax', 'phone_fax', '', $contact->phone_fax, '', array('class'=>'span2'));
	$edit["phone_other"] = SharedController::input_text('phone_other', 'phone_other', '', $contact->phone_other, '', array('class'=>'span2'));
	
	$edit["primary_address_street"] = SharedController::input_text('primary_address_street', 'primary_address_street', '', $contact->primary_address_street, '', array('class'=>'span2'));
	$edit["primary_address_city"] = SharedController::input_text('primary_address_city', 'primary_address_city', '', $contact->primary_address_city, '', array('class'=>'span2'));
	$edit["primary_address_state"] = SharedController::input_text('primary_address_state', 'primary_address_state', '', $contact->primary_address_state, '', array('class'=>'span2'));
	$edit["primary_address_postalcode"] = SharedController::input_text('primary_address_postalcode', 'primary_address_postalcode', '', $contact->primary_address_postalcode, '', array('class'=>'span2'));
	$edit["primary_address_country"] = SharedController::input_text('primary_address_country', 'primary_address_country', '', $contact->primary_address_country, '', array('class'=>'span2'));

	$edit["alt_address_street"] = SharedController::input_text('alt_address_street', 'alt_address_street', '', $contact->alt_address_street, '', array('class'=>'span2'));
	$edit["alt_address_city"] = SharedController::input_text('alt_address_city', 'alt_address_city', '', $contact->alt_address_city, '', array('class'=>'span2'));
	$edit["alt_address_state"] = SharedController::input_text('alt_address_state', 'alt_address_state', '', $contact->alt_address_state, '', array('class'=>'span2'));
	$edit["alt_address_postalcode"] = SharedController::input_text('alt_address_postalcode', 'alt_address_postalcode', '', $contact->alt_address_postalcode, '', array('class'=>'span2'));
	$edit["alt_address_country"] = SharedController::input_text('alt_address_country', 'alt_address_country', '', $contact->alt_address_country, '', array('class'=>'span2'));
	
	$table = <<<END
	
		<div class="row">
			<div class="span10">
			<span style="position: relative; float: left;width: 50%" class="left">
				<span style="" class="bold right">ID:</span>
				<span style="" class="left">{$contact->id}</span>
			</span>
			<span style="position: relative; float: right;width: 50%" class="right">
				<span style="" class="left bold">{$contact->first_name} {$contact->last_name}&nbsp;&nbsp;$saveBtn $cancelBtn</span>
			</span>
			</div>
		</div>
		<br/>	
		<form id="editFrm" method="POST" class="form" action="{$site["url"]}/contacts/?contactid={$contact->id}">
			$hiddenId
			<table id="contactTbl" class="table table-striped table-condensed table-bordered">
				<tr>
					<th colspan="6">Personal Information</th>
				</tr>
				<tr>
					<td class="bold right tableTitle">Name:</td>
					<td class="left tableData">
						<label for="salutation" class="control-label">Salutation</label> {$edit["salutation"]}
						<label for="first_name" class="control-label">First Name</label> {$edit["first_name"]}
						<label for="last_name" class="control-label">Last	 Name:</label> {$edit["last_name"]}
					</td> 
					<td class="bold right tableTitle">Middle Name:</td>
					<td class="left tableData">{$edit["middle_name"]}</td>
					<td class="bold right tableTitle">Nick Name:</td>
					<td class="left tableData">{$edit["nick_name"]}</td>
				</tr>
				<tr>
					<td class="bold right tableTitle">Birth Date:</td>
					<td class="left tableData">{$edit["birth_date"]}</td>
					<td class="bold right tableTitle">Company:</td>
					<td class="left tableData">{$edit["company"]}</td>
					<td class="bold right tableTitle">Job:</td>
					<td class="left tableData">{$edit["job"]}</td>
				</tr>
				<tr>
					<td class="bold right tableTitle">Description:</td>
					<td class="left" colspan="5">{$edit["description"]}</td>
				</tr>
			</table>
			<table id="" class="table table-striped table-condensed table-bordered">
				<tr>
					<th colspan="6">Contact Information</th>
				</tr>
				<tr>
					<td class="bold right tableTitle">Email 1:</td>
					<td class="left tableData">{$edit["email1"]}</td>
					<td class="bold right tableTitle">Email 2:</td>
					<td class="left tableData">{$edit["email2"]}</td>
					<td class="" colspan="2"></td>
				</tr>
				<tr>
					<td class="bold right tableTitle">Home Phone:</td>
					<td class="left tableData">{$edit["phone_home"]}</td>
					<td class="bold right tableTitle">Cell Phone:</td>
					<td class="left tableData">{$edit["phone_mobile"]}</td>
					<td class="bold right tableTitle">Work Phone:</td>
					<td class="left tableData">{$edit["phone_work"]}</td>
				</tr>
				<tr>
					<td class="bold right tableTitle">Fax:</td>
					<td class="left tableData">{$edit["phone_fax"]}</td>
					<td class="bold right tableTitle">Other Phone:</td>
					<td class="left tableData">{$edit["phone_other"]}</td>
					<td class="" colspan="2"></td>
				</tr>
			</table>
			<table id="" class="table table-striped table-condensed table-bordered">
				<tr>
					<th colspan="6">Primary Address</th>
				</tr>
				<tr>
					<td class="bold right tableTitle">Street:</td>
					<td class="left tableData">{$edit["primary_address_street"]}</td>
					<td class="bold right tableTitle">City:</td>
					<td class="left tableData">{$edit["primary_address_city"]}</td>
					<td class="bold right tableTitle">State:</td>
					<td class="left tableData">{$edit["primary_address_state"]}</td>
				</tr>
				<tr>
					<td class="bold right tableTitle">Zip:</td>
					<td class="left tableData">{$edit["primary_address_postalcode"]}</td>
					<td class="bold right tableTitle">Country:</td>
					<td class="left tableData">{$edit["primary_address_country"]}</td>
					<td class="" colspan="2"></td>
				</tr>
			</table>
			<table id="" class="table table-striped table-condensed table-bordered">
				<tr>
					<th colspan="6">Alternate Address</th>
				</tr>
				<tr>
					<td class="bold right tableTitle">Street:</td>
					<td class="left tableData">{$edit["alt_address_street"]}</td>
					<td class="bold right tableTitle">City:</td>
					<td class="left tableData">{$edit["alt_address_city"]}</td>
					<td class="bold right tableTitle">State:</td>
					<td class="left tableData">{$edit["alt_address_state"]}</td>
				</tr>
				<tr>
					<td class="bold right tableTitle">Zip:</td>
					<td class="left tableData">{$edit["alt_address_postalcode"]}</td>
					<td class="bold right tableTitle">Country:</td>
					<td class="left tableData">{$edit["alt_address_country"]}</td>
					<td class="" colspan="2"></td>
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

function saveContactPOST($post){
	global $site;
	if (trim($post["contactid"]) !== ""){
		$editContact = new Contact($post["contactid"]);
	}else{
		$editContact = new Contact();
	}
	
	$editContact->first_name = $post["first_name"];
	$editContact->last_name = $post["last_name"];
	$editContact->middle_name = $post["middle_name"];
	$editContact->nick_name = $post["nick_name"];
	$editContact->salutation = $post["salutation"];
	$editContact->birth_date = $post["birth_date"];
	$editContact->company = $post["company"];
	$editContact->job = $post["job"];
	$editContact->description = $post["description"];
	$editContact->email1 = $post["email1"];
	$editContact->email2 = $post["email2"];
	$editContact->phone_mobile = $post["phone_mobile"];
	$editContact->phone_home = $post["phone_home"];
	$editContact->phone_work = $post["phone_work"];
	$editContact->phone_fax = $post["phone_fax"];
	$editContact->phone_other = $post["phone_other"];
	$editContact->primary_address_street = $post["primary_address_street"];
	$editContact->primary_address_city = $post["primary_address_city"];
	$editContact->primary_address_state = $post["primary_address_state"];
	$editContact->primary_address_postalcode = $post["primary_address_postalcode"];
	$editContact->primary_address_country = $post["primary_address_country"];
	$editContact->alt_address_street = $post["alt_address_street"];
	$editContact->alt_address_city = $post["alt_address_city"];
	$editContact->alt_address_state = $post["alt_address_state"];
	$editContact->alt_address_postalcode = $post["alt_address_postalcode"];
	$editContact->alt_address_country = $post["alt_address_country"];
	$editContact->deleted = 0;
	
	$editContact->save();

	if (array_key_exists('parentid', $post)){
		require_once($site['path'] . "/relations/controller/relation_controller.php");
		saveQuickRelation($post,$editContact);
	}

}
?>
