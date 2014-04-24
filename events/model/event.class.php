<?php
require_once("{$site["path"]}/shared/model/database.class.php");

class Event extends Database
{

	public $id;
	public $date_entered;
	public $date_modified;
	public $modified_user_id;
	public $created_by;	
	public $description;
	public $deleted;
	public $assigned_user_id;
	public $name;
	public $case_number;
	public $type;
	public $status;
	public $priority;
	public $resolution;
	public $work_log;
	public $account_id;
	
	public function __construct($id = null){
		$this->idname = "id";
		$this->cols = array("date_entered",
							"date_modified",
							"modified_user_id",
							"created_by",	
							"description",
							"deleted",
							"assigned_user_id",
							"name",
							"case_number",
							"type",
							"status",
							"priority",
							"resolution",
							"work_log",
							"account_id");

		$this->table = "events";
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
								$this->name,
								$this->case_number,
								$this->type,
								$this->status,
								$this->priority,
								$this->resolution,
								$this->work_log,
								$this->account_id);
							
		parent::save();
	}

}

?>
