<?php

class C_Column{
	var $col_name;
	var $col_value;
	var $col_desc;
	var $order_by;
	var $col_type;
	var $th_style;
	var $th_class;
	
	// Constructor. Note: the column type is DataField by default
	// Recommended: if th css is not defined, use the th css values from the cls_database class 
    function C_Column($col_name, $col_desc, $col_type = "DataField", $th_style = "", $th_class = ""){
        $this->col_name 	= trim($col_name);
        $this->col_value	= "";			// Trick: this value is assigned from cls_database in render_body()
        $this->col_desc 	= trim($col_desc);
        $this->order_by 	= trim($col_name);
        $this->col_type		= trim($col_type);
        $this->th_style 	= $th_style;
        $this->th_class 	= $th_class;
    }
    
    function render_header(){
		if(stristr($this->col_type, "Form:")){
			switch($this->col_type){
				case "Form:checkbox":
//					$this->col_desc = "<input type='checkbox' name='chkbxSelectAll' value='selectAll' onclick=\"\" >";
					break;
				default:			
			}
		}
	   	$column_header = "<th style='". $this->th_style. "' class='". $this->th_class ."' width='1%'>";
	   	$column_header.= ($this->order_by != "") ? "<a href='". $_SERVER['PHP_SELF'] ."?orderby=". $this->order_by ."'>&nbsp;". $this->col_desc ."</a>" : "<a>&nbsp;".$this->col_desc."</a>";
	   	$column_header.= "</th>";
	   	return $column_header;
	}
 
	function render_body(){
		$column_body = "<td align='center'>";

		if(strtolower($this->col_type) == "datafield"){
			$column_body .= $this->col_value;
		}else{
			if(stristr($this->col_type, "Form:")){
				switch(strtolower($this->col_type)){
					case "form:checkbox":
						$column_body .= "<input type='checkbox' name='___".$this->col_name ."___". $this->col_value ."' value='". $this->col_value ."' />";
						break;
					case "form:radio":
						$column_body .= "<input type='radio' name='___". 	$this->col_name ."___". $this->col_value ."' value='". $this->col_value ."' />";
						break;
					case "form:text":
						$column_body .= "<input type='text' name='___". 	$this->col_name ."___". $this->col_value ."' value='". $this->col_value ."' />";
						break;
					case "form:button":
						$column_body .= "<input type='button' name='___". 	$this->col_name ."___". $this->col_value ."' value='". $this->col_value ."' />";
						break;
					default:	
					
				}// switch
			}// if "Form:"
            elseif(stristr($this->col_type, "Chart:")){
                switch(strtolower($this->col_type)){
                    case "chart:bar":
						$column_body .= "<div align='left'><nobr>".
                                            "<img src='". $this->_get_filepath() ."/images/bar_blue.gif' height='12px' width='". $this->get_barchart_value($this->col_value) ."px' />&nbsp;".   
                                         "</nobr></div>";
                        break;
                    default:
                }// switch
            }// elseif "Chart:"
		}// if

		$column_body.= "</td>";
		return $column_body;
	}

    // Desc: calculate bar chart "stretch" value
    // $stretching factor = (int)($val*$maxsize / $maxval)
    function get_barchart_value($val){
        $maxsize = 150;
        $maxval  = 30000;
        $stretch_value = intval($val * $maxsize / $maxval);
        return $stretch_value;
    }

	// Desc: write only. this value is set from cls_database in render_body()
	function set_col_value($colvalue){
		$this->col_value = $colvalue;
	}

	// Desc: get file path from the session first, if not try to calculate it.
	function _get_filepath(){
		if(isset($_SESSION["file_path"])){
			$filepath = $_SESSION["file_path"];
		}else{
			$str_referencepath 	= str_replace("/", "\\", strtolower(dirname($_SERVER['PHP_SELF']))) ."\\";
			$str_actualpath 	= strtolower(dirname(__FILE__));
			$arr_filepath = explode($str_referencepath, $str_actualpath);
			$filepath = str_replace("\\", "/", $arr_filepath[1]);
				
			if($filepath != "")
				$filepath .= "/";
		}
		return $filepath;
	}
}
?>