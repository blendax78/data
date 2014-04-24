<?php

class Database
{
	public $id;
	public $idname;
	public $table;
	public $cols = array();
	public $values = array();
	private $db;
	private $dbinfo = array();
	public $relations = array();
	public $search_fields = array();
	
	public function __construct($id = null){
		array_push($this->search_fields,'date_entered');
		array_push($this->search_fields,'date_modified');
		
		$this->dbinfo = get_digitalocean_config("data");

		$this->db = new mysqlDB($this->dbinfo["host"],$this->dbinfo["db"],
				$this->dbinfo["user"],$this->dbinfo["pass"]);
		$this->db->logqueries = true;
		$this->db->logfile = "<home>/www/log/data.log";
		if ($id){
			$this->findById($id, $this->idname);
		}
	}
	
	public function search($value){
		#string searching only
		if (count($this->search_fields) > 0){
			$sql = "select * from {$this->table} where deleted = 0 and (";
			foreach ($this->search_fields as $sf){
				$sql .= " $sf like '%$value%' or ";
			}
			$sql .= " 1=0 );";  #this will prevent the need to remove the last 'or'

			return $this->db->fetch_array($sql);
		}else{
			#not searchable
			return array();
		}
	}
	
	public function delete(){
		if ($this->id){
			$sql = "update {$this->table} set deleted = 1 where {$this->idname} = {$this->id};";
			$db->query($sql);
			return true;
		}else{
			return false;
		}
	}
	
	public function save(){
		$cols = $this->cols;
		$values = $this->values;
		#$idname = array_shift($cols);
		$idname = $this->idname;
		$id = array_shift($values);

		if (trim($id) == ""){
			$id = $this->generateUUID();

			$this->id = $id;
			$sql = "insert into {$this->table}(id,";
			$sql .= implode(",",$cols);
			$sql .= ") values ('$id',";
			#$sql .= implode(",",$values);

			foreach($values as $value){
				if (gettype($value) == "string"){
					$value = "'" . addslashes($value) . "'";
				}elseif(strlen($value) == 0){
					$value = "NULL";
				}
				$sql .= "$value,";
			}
			$sql[strlen($sql) - 1] = ");";

		}else{
			$sql = "update {$this->table} set ";
			for ($i = 0; $i < count($cols); $i++){
				if (gettype($values[$i]) == "string"){
					$values[$i] = "'" . addslashes($values[$i]) . "'";
				}elseif(strlen($values[$i]) == 0){
					$values[$i] = "NULL";
				}
				$sql .= " {$cols[$i]} = {$values[$i]},"; 
			}

			$sql[strlen($sql) - 1] = " ";
			$sql .= "where $idname = '$id';";
		}
		$this->db->query($sql);
	}
	
	public function findById($id, $idname = null){
		if (!is_null($idname)){
			$this->idname = $idname;	
		}elseif(!is_null($this->idname)){
			$idname = $this->idname;
		}else{
			$idname = "id";	
		}

		$class = null;
		if (isset($idname)){
			$sql = "select * from {$this->table} where $idname = '$id';";
			$class = $this->db->fetch_array($sql);

			$class = $class[0];
			
			if (isset($class)){
				$this->$idname = $class[$idname];
				foreach ($this->cols as $col){
					$this->$col = $class[$col];	
				}
			}
		}
	}
	
	public function getAll($sort = null, $limitStart = 0, $limitStop = 1000){
	
		if ($sort){
			$orderby = "order by $sort";
		}else{
			$orderby = "";
		}
		
		$sql = "select * from {$this->table} where deleted != 1 $orderby limit $limitStart,$limitStop;";
		$classes = $this->db->fetch_array($sql);
		
		$return = array();
		$classType = get_class($this);
		if ($classes){
			foreach ($classes as $class){
				$obj = new $classType;
				$obj->findById($class["id"]);
				$return[] = $obj;
			}
		}
		return $return;
	}

	public function generateUUID(){
		#Create a new database UUID;
		$sql = "select uuid() as uuid;";
		$uuid = $this->db->fetch_array($sql);
		
		return $uuid[0]["uuid"];
	}
	
	public function getNewest(){
		$sql = "select id from {$this->table} where deleted != 1 order by date_entered desc limit 1;";

		$return = $this->db->fetch_array($sql);
		$id = $return[0]["id"];
		$this->findById($id);
	}
	
	public function fetch_array($sql){
		return $this->db->fetch_array($sql);
	}
	
	public function query($sql){
		return $this->db->query($sql);
	}
	
	public function getRelations(){
		$types = array('group','contact','document','event','note');
		$parent_type = substr($this->table,0,strlen($this->table) - 1);

		$return = array();
		foreach ($types as $type){
			$sql = "select t.*, r.id as relationid from relations r  " .
					"join {$type}s t on (r.child_id = t.id and r.child_type = '$type' " .
					"and r.parent_id = '{$this->id}' and r.parent_type = '$parent_type') " .
					"or (r.parent_id = t.id and r.parent_type = '$type' " .
					"and r.child_id = '{$this->id}' and r.child_type = '$parent_type') " .
					"where r.deleted != 1 and t.deleted != 1 " .
					"order by t.date_modified desc;";

			$return[$type] = $this->db->fetch_array($sql);
		}
		$this->relations = $return;		
	}
	
}

?>
