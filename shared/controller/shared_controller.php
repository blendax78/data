<?php

class SharedController{
	public static function allObjDropDown($objects, $selected = null, $onchange = "", $values = array(), $extras = array(), $withPadding = true){

		if (is_array($objects) && count($objects) > 0){
			
			$extraval = "";
			foreach($extras as $e=>$x){
				$extraval .= " $e=\"$x\" ";
			}
			
			$ids = (strtolower(get_class($objects[0])) !== 'stdclass') ? 'id="' .strtolower(get_class($objects[0])) . 'id" name="' . strtolower(get_class($objects[0])) . 'id"' : "";

			$dropdown .= '<select ' . $ids .  $onchange . "$extraval>";
			if ($withPadding){
				$dropdown .= "<option $sel value=\"0\"></option> ";
			}
			foreach($objects as $object){
				if ($selected == $object->id){
					$sel = 'selected="selected"';	
				}else{
					$sel = '';
				}
				
				if (count($values) > 0){
					$val = "";
					foreach ($values as $value){
						$val .= $object->$value . " ";
					}
					
					$dropdown .= "<option $sel value=\"{$object->id}\">$val</option>";	
				}else{
					$dropdown .= "<option $sel value=\"{$object->id}\">{$object->id}</option>";	
				}
			}
			$dropdown .= "</select>";
		}

		return $dropdown;
	}
	
	public static function input_dropdown($id, $name, $objects, $selected = null, $onchange = "", $extraoptions = array()){
		$opts = "";
		if (count($extraoptions) > 0){
			foreach ($extraoptions as $e=>$o){
				$opts .= ' ' . $e . '="' . $o . '"';
			}
		}	
		if (is_array($objects) && count($objects) > 0){

			$dropdown .= '<select id="' . $id . '" name="' . $name . '" ' . $onchange . $opts . '>';
			if ($withPadding){
				$dropdown .= "<option $sel value=\"0\"></option> ";
			}
			foreach($objects as $key=>$val){
				if ($selected == $key){
					$sel = 'selected="selected"';	
				}else{
					$sel = '';
				}
				
					$dropdown .= "<option $sel value=\"$key\">$val</option>";	
			}
			$dropdown .= "</select>";
		}

		return $dropdown;
	}
	
	public static function input_text($id, $name, $css, $value, $onchange, $extraoptions = array()){
		$css = htmlentities($css, ENT_QUOTES);
		$value = htmlentities($value, ENT_QUOTES);
		$opts = "";
		if (count($extraoptions) > 0){
			foreach ($extraoptions as $e=>$o){
				$opts .= ' ' . $e . '="' . $o . '"';
			}
		}	

		$input = "<input type=\"text\" id=\"$id\" name=\"$name\" style=\"$css\" value=\"$value\" onchange=\"$onchange\" $opts/>";
		return $input;
	}
	
	public static function input_textarea($id, $name, $css, $value, $onchange, $extraoptions = array()){
		$css = htmlentities($css, ENT_QUOTES);
		$value = htmlentities($value, ENT_QUOTES);
		$opts = "";
		if (count($extraoptions) > 0){
			foreach ($extraoptions as $e=>$o){
				$opts .= ' ' . $e . '="' . $o . '"';
			}
		}	

		$input = "<textarea id=\"$id\" name=\"$name\" style=\"$css\" onchange=\"$onchange\" $opts>$value</textarea>";
		return $input;
	}
	
	public static function input_hidden($id, $name, $value){
		$css = htmlentities($css, ENT_QUOTES);
		$value = htmlentities($value, ENT_QUOTES);

		$input = "<input type=\"hidden\" id=\"$id\" name=\"$name\" value=\"$value\" />";
		return $input;
	}
	
	public static function edit_button($type, $extraoptions = array()){
		$opts = "";
		if (count($extraoptions) > 0){
			foreach ($extraoptions as $e=>$o){
				$opts .= ' ' . $e . '="' . $o . '"';
			}
		}
		$editimg = '<button objectType="' . $type . '" ' . $opts . 'type="submit" id="editBtn" name="edit" class="btn btn-primary btn-mini"><i class="icon-edit icon-white"></i> Edit</button>';
		
		return $editimg;
	}
	
	public static function delete_button($type, $extraoptions = array()){
		$opts = "";
		if (count($extraoptions) > 0){
			foreach ($extraoptions as $e=>$o){
				$opts .= ' ' . $e . '="' . $o . '"';
			}
		}
		$delimg = '<button objectType="' . $type . '" ' . $opts . 'type="submit" id="deleteBtn" name="delete" class="btn btn-danger btn-mini"><i class="icon-trash icon-white"></i> Delete</button>';
		
		return $delimg;
	}
	
	public static function save_button($type, $extraoptions = array()){
		$opts = "";
		if (count($extraoptions) > 0){
			foreach ($extraoptions as $e=>$o){
				$opts .= ' ' . $e . '="' . $o . '"';
			}
		}
		$delimg = '<button objectType="' . $type . '" ' . $opts . 'type="submit" id="saveBtn" name="save" class="btn btn-mini"><i class="icon-ok-sign"></i> Save</button>';
		
		return $delimg;
	}

	public static function cancel_button($type, $extraoptions = array()){
		$opts = "";
		if (count($extraoptions) > 0){
			foreach ($extraoptions as $e=>$o){
				$opts .= ' ' . $e . '="' . $o . '"';
			}
		}
		$delimg = '<button objectType="' . $type . '" ' . $opts . 'type="submit" id="cancelBtn" name="cancel" class="btn btn-mini"><i class="icon-remove-sign"></i> Cancel</button>';
		
		return $delimg;
	}
	public static function image_tag($url,$options = array()){
		$img = '<img src="' . $url . '" ';
		foreach ($options as $key=>$val){
			$img .= $key . '="' . htmlentities($val,ENT_QUOTES) . '" ';
		}
		$img .= ' />';
	
		return $img;
	}
	
	public static function pager($type, $objects, $start, $stop){
		
		for ($i = 1; $i <= ceil(count($objects) /10); $i++ ){
			$start2 = 0 + (($i - 1) * 10);
			$stop2 = 0 + ($i * 10);
			$page .=  '<li><a href="/' . $type . '/?start=' . $start2 . '&stop=' . $stop2 . '" >' . $i . '</a></li>';
			
		}
		$pagination = '<div class="pagination">
					  <ul>' .
					    $page .
					  '</ul>
					</div>';
		$pagination = '<div class="row"><div class="span10 center">' . $pagination . '</div></div>';
		return $pagination;
	}
	
	public static function includeRelations($object){
		global $site;
		ob_start(); # start buffer
		include_once("{$site["path"]}/relations/index.php");;
		# we pass the output to a variable
		$html = ob_get_contents();
		ob_end_clean(); # end buffer
		
		return $html;
	}
	
	public static function drawAddRelation($object){
		global $site;
		ob_start();
		include_once($site["path"] . "/relations/view/newRelation.php");
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	public static function drawSearch($object){
		#$object->search('poop');
		$form = '<form method="POST" class="well form-inline">';
		$input = self::input_text('searchInput', 'searchInput', '', '', '', $extraoptions = array('placeholder'=>"Search for " . ucwords($object->table)));
		$button = '<input class="btn" type="button" value="Go" />';
		$form .= "$input $button</form>";
		return $form;
	}
	
}


?>