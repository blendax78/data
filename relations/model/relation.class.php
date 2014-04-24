<?php
require_once("{$site["path"]}/shared/model/database.class.php");

class Relation extends Database
{

	public $id;
	public $date_entered;
	public $date_modified;
	public $modified_user_id;
	public $created_by;
	public $deleted;
	public $parent_type;
	public $parent_id;
	public $child_type;
	public $child_id;
	
	public function __construct($id = null){
		$this->idname = "id";
		$this->cols = array("date_entered",
							"date_modified",
							"modified_user_id",
							"created_by",
							"deleted",	
							"parent_type",
							"parent_id",
							"child_type",
							"child_id");

		$this->table = "relations";
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
								$this->deleted,
								$this->parent_type,
								$this->parent_id,
								$this->child_type,
								$this->child_id);
		parent::save();
	}

}

?>
