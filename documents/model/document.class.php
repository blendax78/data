<?php
require_once("{$site["path"]}/shared/model/database.class.php");

class Document extends Database
{

	public $id;
	public $date_entered;
	public $date_modified;
	public $modified_user_id;
	public $created_by;	
	public $description;
	public $deleted;
	public $assigned_user_id;
	public $document_name;
	public $doc_id;
	public $doc_type;
	public $doc_url;
	public $active_date;
	public $exp_date;
	public $category_id;
	public $subcategory_id;
	public $status_id;
	public $document_revision_id;
	public $related_doc_id;
	public $related_doc_rev_id;
	public $is_template;
	public $template_type;

	public function __construct($id = null){
		$this->idname = "id";
		$this->cols = array("date_entered",
							"date_modified",
							"modified_user_id",
							"created_by",	
							"description",
							"deleted",
							"assigned_user_id",
							"document_name",
							"doc_id",
							"doc_type",
							"doc_url",
							"active_date",
							"exp_date",
							"category_id",
							"subcategory_id",
							"status_id",
							"document_revision_id",
							"related_doc_id",
							"related_doc_rev_id",
							"is_template",
							"template_type");

		$this->table = "documents";
		parent::__construct($id);

	}
	
	public function save(){

		if (!isset($this->created_by) || $this->created_by = ""){
			$this->created_by = 'eric';
			$this->date_entered = date("Y-m-d H:i:s");
		}
		
		$this->date_modified = date("Y-m-d H:i:s");
		$this->modified_user_id = 'eric';
		
		$this->values = array(	$this->id,
								$this->date_entered,
								$this->date_modified,
								$this->modified_user_id,
								$this->created_by,	
								$this->description,
								$this->deleted,
								$this->assigned_user_id,
								$this->document_name,
								$this->doc_id,
								$this->doc_type,
								$this->doc_url,
								$this->active_date,
								$this->exp_date,
								$this->category_id,
								$this->subcategory_id,
								$this->status_id,
								$this->document_revision_id,
								$this->related_doc_id,
								$this->related_doc_rev_id,
								$this->is_template,
								$this->template_type);
							
		parent::save();
	}

}

?>
