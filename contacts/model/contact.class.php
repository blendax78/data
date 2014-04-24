<?php
require_once("{$site["path"]}/shared/model/database.class.php");

class Contact extends Database
{

	public $id;
	public $date_entered;
	public $date_modified;
	public $modified_user_id;
	public $created_by;	
	public $description;
	public $deleted;
	public $salutation;
	public $first_name;
	public $middle_name;
	public $last_name;
	public $nick_name;
	public $job;
	public $company;
	public $phone_home;
	public $phone_mobile;
	public $phone_work;
	public $phone_other;
	public $phone_fax;
	public $primary_address_street;
	public $primary_address_city;
	public $primary_address_state;
	public $primary_address_postalcode;
	public $primary_address_country;
	public $alt_address_street;
	public $alt_address_city;
	public $alt_address_state;
	public $alt_address_postalcode;
	public $alt_address_country;
	public $birthdate;
	public $email1;
	public $email2;
	
	public function __construct($id = null){
		$this->idname = "id";
		$this->search_fields = array('description','first_name','last_name','nick_name','company','email1','email2','primary_address_city');
		$this->cols = array(	"date_entered",
								"date_modified", 
								"modified_user_id",
								"created_by",
								"description",
								"deleted",
								"salutation",
								"first_name",
								"middle_name",
								"last_name",
								"nick_name",
								"job",
								"company",
								"phone_home",
								"phone_mobile",
								"phone_work",
								"phone_other",
								"phone_fax",
								"primary_address_street",
								"primary_address_city",
								"primary_address_state",
								"primary_address_postalcode",
								"primary_address_country",
								"alt_address_street",
								"alt_address_city",
								"alt_address_state",
								"alt_address_postalcode",
								"alt_address_country",
								"birthdate",
								"email1",
								"email2");
		$this->table = "contacts";
		parent::__construct($id);

	}
	
	public function save(){

		if (!isset($this->created_by) || $this->created_by = ""){
			$this->created_by = 'eric';
			$this->date_entered = date("Y-m-d H:i:s");
		}
		
		$this->date_modified = date("Y-m-d H:i:s");
		$this->modified_user_id = 'eric';
		
		$this->values = array($this->id, $this->date_entered, $this->date_modified,
		$this->modified_user_id, $this->created_by, $this->description, 
		$this->deleted,	$this->salutation, $this->first_name,
		$this->middle_name, $this->last_name, $this->nick_name,
		$this->job, $this->company, $this->phone_home,
		$this->phone_mobile, $this->phone_work, $this->phone_other,
		$this->phone_fax, $this->primary_address_street, $this->primary_address_city,
		$this->primary_address_state, $this->primary_address_postalcode, $this->primary_address_country, $this->alt_address_street,
		$this->alt_address_city, $this->alt_address_state, $this->alt_address_postalcode, $this->alt_address_country,
		$this->birthdate, $this->email1, $this->email2);
							
		parent::save();
	}

}

?>
