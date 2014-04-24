<?php
require_once("{$site["path"]}/shared/model/database.class.php");

class Group extends Database
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
	public $account_type;
	public $industry;
	public $annual_revenue;
	public $phone_fax;
	public $billing_address_street;
	public $billing_address_city;
	public $billing_address_state;
	public $billing_address_postalcode;
	public $billing_address_country;
	public $rating;
	public $phone_office;
	public $phone_alternate;
	public $website;
	public $ownership;
	public $employees;
	public $ticker_symbol;
	public $shipping_address_street;
	public $shipping_address_city;
	public $shipping_address_state;
	public $shipping_address_postalcode;
	public $shipping_address_country;
	public $parent_id;
	public $sic_code;
	public $campaign_id;
	
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
							"account_type",
							"industry",
							"annual_revenue",
							"phone_fax",
							"billing_address_street",
							"billing_address_city",
							"billing_address_state",
							"billing_address_postalcode",
							"billing_address_country",
							"rating",
							"phone_office",
							"phone_alternate",
							"website",
							"ownership",
							"employees",
							"ticker_symbol",
							"shipping_address_street",
							"shipping_address_city",
							"shipping_address_state",
							"shipping_address_postalcode",
							"shipping_address_country",
							"parent_id",
							"sic_code",
							"campaign_id");

		$this->table = "groups";
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
								$this->account_type,
								$this->industry,
								$this->annual_revenue,
								$this->phone_fax,
								$this->billing_address_street,
								$this->billing_address_city,
								$this->billing_address_state,
								$this->billing_address_postalcode,
								$this->billing_address_country,
								$this->rating,
								$this->phone_office,
								$this->phone_alternate,
								$this->website,
								$this->ownership,
								$this->employees,
								$this->ticker_symbol,
								$this->shipping_address_street,
								$this->shipping_address_city,
								$this->shipping_address_state,
								$this->shipping_address_postalcode,
								$this->shipping_address_country,
								$this->parent_id,
								$this->sic_code,
								$this->campaign_id);
							
		parent::save();
	}

}

?>
