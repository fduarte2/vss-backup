<?php
class C_HtmlControl
{
	var $type;
	var $varname;
	var $text;
	var $val;
	var $is_choosen;
	var $is_disabled;
	var $attr_select;		// html attribute text for "checked", "selected" etc. depend on control type
	
	function C_HtmlControl($newtype, $newvarname, $newval, $newtext, $new_attrselect, $disable = false){
		$this->type 		= $newtype;
		$this->text 		= $newtext;
		$this->varname 		= $newvarname;
		$this->val			= $newval;
		$this->is_choosen	= false;
		$this->is_diabled	= $disable;
		$this->attr_select	= $new_attrselect;
	}	
	
	function render(){
		$html = "";
		switch($this->type){
			case "select":
				$html.= "<option value='". $this->val ."'";
				break;
			default:
				$html.=	"<input name ='". $this->varname 
							."[]' id   ='". $this->varname 
						  	."[]' type ='". $this->type 
						  	."' value='". $this->val 
						  	."'";	
				$html .= ($this->is_disabled) ? " onfocus='blur();' " : "";
		}
		$html .= ($this->is_choosen)  ? " ".$this->attr_select." " : "";
		$html .= " />". $this->text;
		
		print($html);
	}	
}




class Radio extends C_HtmlControl
{
	// Calls base class contructor first
	function Radio($newvarname, $newval, $newtext){
		parent::C_HtmlControl("radio", $newvarname, $newval, $newtext, "checked");	
	}
		
	function get_value(){
		return $this->val;
	}
	
	function set_value($newval){
		$this->val = $newval;	
	}
	
	function render(){
		parent::render();
	}
}




class Dropdown extends C_HtmlControl
{
	// Calls base class contructor first
	function Dropdown($newvarname, $newval, $newtext){
		parent::C_HtmlControl("select", $newvarname, $newval, $newtext, "selected");	
	}
		
	function get_value(){
		return $this->val;
	}
	
	function set_value($newval){
		$this->val = $newval;	
	}
	
	function render(){
		parent::render();
	}
}






class Checkbox extends C_HtmlControl
{
	var $delimiter;
	
	// Calls base class contructor first
	function Checkbox($newvarname, $newval, $newtext, $newdelim){
		$this->delimiter = $newdelim;
		parent::C_HtmlControl("checkbox", $newvarname, $newval, $newtext, "checked");	
	}
		
	function get_value(){
		return $this->val;
	}
	
	function set_value($newval){
		$this->val = $newval;	
	}
	
	function get_delimiter(){
		return $this->delimiter;
	}
	
	function set_delimiter($newdelim){
		$this->delimiter = $newdelim;	
	}
	
	function render(){
		parent::render();
	}
}




class Multiselect extends C_HtmlControl
{
	var $delimiter;
	var $size;
	
	// Calls base class contructor first
	function Multiselect($newvarname, $newval, $newtext, $newsize, $newdelim){
		$this->size = $newsize;
		$this->delimiter = $newdelim;
		parent::C_HtmlControl("select", $newvarname, $newval, $newtext, "selected");	
	}
		
	function get_value(){
		return $this->val;
	}
	
	function set_value($newval){
		$this->val = $newval;	
	}
	
	function get_delimiter(){
		return $this->delimiter;
	}
	
	function set_delimiter($newdelim){
		$this->delimiter = $newdelim;	
	}
	
	function render(){
		$html = "";
		$html.= "<option value='". $this->val ."'";
		$html .= ($this->is_choosen)  ? " ".$this->attr_select." " : "";
		$html .= " />". $this->text;
		
		print($html);
	}
}
?>