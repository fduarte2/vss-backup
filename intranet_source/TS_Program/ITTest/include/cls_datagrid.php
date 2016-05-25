<?php
// error_reporting(E_ALL);
// $THIS_SCRIPT = isset($PHP_SELF) ? = $PHP_SELF : $SCRIPT_NAME;

define('PAGE_SIZE_NONE', 999999);
define('PAGE_NUMBER', "pg");
define('BUTTON_STYLE', "style='font-size:8pt;font-family:verdana,arial;border:1pt black solid' onmouseover='this.style.color=\"blue\"' onmouseout='this.style.color=\"black\"'");
define("RADIO", "Radio");
define("CHECKBOX", "Checkbox");
define("DROPDOWN", "Dropdown");
define("MULTISELECT", "Multiselect");
define("MASTER_ROW_INDEX_DEFAULT", -1);
define("MASTER_ROW_BGCOLOR", "#BCD3E9");

class C_DataGrid{
	// c_databae class object
	var $db;
	var $sql;
	var $sql_table;
	var $sql_key;

	// table properties
	var $border;
	var $table_bg;
	var $alt_bgcolor;
	var $alt_rownum;
	var $onmouseover_bgcolor;
	var $onmouseover_fgcolor;	// need work
	var $table_style;
	var $table_class;
	var $th_style;
	var $th_class;
	var $tr_style;
	var $tr_class;
	var $td_style;				// need work
	var $td_class;				// in the future
	var $grid_class;
	var $grid_body_style;
	var $cell_style;
	var $cell_prtstyle;

	// column properties
	var $col_styles;
	var $col_links;
	var $col_imgs;
	var $col_txts;
	var $col_hiddens;
	var $col_titles;
	var $col_objects;
	var $col_sums;
	var $col_ctrls;

	// data & additional properties
	var $allow_sort;
	var $sorted_by;
	var $sort_direction;
	var $page_start_num;
	var $page_current;
	var $page_size;
	var $ok_rowindex;
	var $ok_nl2br;
	var $is_actncol_allwd;
	var $ok_showcredit;
	var $allow_export;
	var $template_layout;
	var $inlineedit_enabled;
	var $ajax_enabled;
	var $inline_valueset;
	var $multidel_enabled;
	var $col_sum_enabled;
	var $toolbar_enabled;
	var $print_enabled;
	var $action_type;
	var $fields_readonly;
	var $xmlexp_enabled;
	var $masterdetail_sql;
	var $masterdetail_key;		// data field name as foreign key to the detail table

	// theme variables
	var $theme;
	
	// others
	var $util;
	var $actions;		// user defined row actions
	var $commands;		// user defined toolbar commands
	
	// private variables
	var $_num_rows;
	var $_num_fields;
	var $_file_path;
	var $_ver_num;
	


	function C_DataGrid($hostName="", $userName="", $password="", $databaseName="", $dbType="mysql", $data=array()){
		if($dbType != "array"){
			$this->db = new C_DataBase($hostName, $userName, $password, $databaseName, $dbType);
		}
		$_SESSION["DATASOURCE"] = $dbType;
		$this->sql				= "";
		$this->sql_table		= "";
		$this->sql_key			= "id";
		$this->border 			= 1;
		$this->table_style 		= "";
		$this->table_class 		= "table_css";
		$this->table_bg			= "";
		$this->allow_sort		= true;
		$this->sort_direction	= "ASC";
		$this->tr_style			= "";
		$this->tr_class			= "tr_css";
		$this->th_style			= "";
		$this->th_class			= "th_css1";
		$this->td_style			= "";
		$this->td_class			= "";
		$this->alt_bgcolor 		= "#ffffff, #e9eff2";
		$this->alt_rownum 		= 1;	// alternate every other row by default
		$this->onmouseover_bgcolor= "#FFFFAF";
		$this->onmouseover_fgcolor= "";
		$this->col_styles		= array();
		$this->col_links		= array();
		$this->col_imgs			= array();
		$this->col_txts			= array();
		$this->col_hiddens		= array();			// columns that are hidden
		$this->col_titles		= array();			// columns w/ customized title
		$this->col_objects		= array();			// columns objects of C_Column class
	    $this->col_sums         = array();          // columns that have total quantity
	    $this->col_ctrls		= array();			// columns admin HTML control objects
		$this->page_start_num	= 1;				// page starting-on number
		$this->page_current 	= isset($_GET[PAGE_NUMBER])? $_GET[PAGE_NUMBER] : 1; // current page
		$this->page_size		= PAGE_SIZE_NONE;	// numbers of rows per page
		$this->ok_rowindex		= false;			// is index column allowed
		$this->ok_nl2br			= true;				// replace cartridge return with <br>
		$this->is_actncol_allwd = false;			// is action coloumn allowed
		$this->ok_showcredit	= true;
		$this->allow_export		= false;			// link to grid_export.php for XML, excel export
		$this->template_layout	= "";				// string to the layout template
		$this->inlineedit_enabled	= false;
		$this->ajax_enabled		= false;
		$this->inline_valueset	= ""; 				// form hidden input for saving inline edit value 
		$this->multidel_enabled	= false;			// allow showing checkboxs for multiple record delete
	    $this->col_sum_enabled  = false;            // allow calculating v the sum of a column
	    $this->toolbar_enabled	= true;				// allow showing toolbar
	    $this->print_enabled	= true;				// allow showing print button
	    $this->xmlexp_enabled	= true;				// allow xml export
		$this->grid_class		= "grid_css";		// default grid (outermost) css class
		$this->grid_body_style	= "";				// grid body css style
		$this->cell_style		= "";				// default CSS media screen style of table cell (block element inside the td tag)
		$this->cell_prtstyle	= "";				// CSS @media print style for table block elmt
		$this->theme			= $this->get_theme();	// get default theme (look & feel) 
		$this->action_type		= "VED";			// Default admin action: View, Edit, Delete
		$this->fields_readonly	= "";				// Default fields disabled for editing is nothing
		$this->masterDetail_pos	= "south";
		$this->masterdetail_sql	= "";
		$this->masterdetail_key = "";
		$this->util				= new C_Utility();	// create utility object
		$this->actions			= array();
		$this->commands			= array();
		$this->_num_rows 		= 0;				// values are updated in set_sql()
		$this->_num_fields		= 0;				// values are updated in set_sql()
		$this->_file_path		= $this->_get_filepath(); // get relative URL path of this file
		$this->_ver_num			= "phpGrid Enterprise (v3.0)";		
	}																	



		// Desc: render grid toolbar along with the page navigation bar
		function render_toolbar(){

			$toolbar = "\n\n<div id='toolbar' class='toolbar_css'>\n";

			$toolbar .= $this->render_page_nav_bar() ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

			$toolbar .= "<span class='toolbar_align'>Total Records: <strong>". $this->_num_rows."</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>\n";
			
			// add additional user defined toolbar commands
			foreach ($this->commands as $command) {
				list($cname, $clink, $extra_attr, $cimage) = $command;
				if($cimage == ""){
					$toolbar .= "&nbsp;<a href=\"$clink\" $extra_attr>$cname</a>&nbsp;";
				}else{
					$toolbar .= "&nbsp;<a href=\"$clink\" $extra_attr><img src='$cimage' border='0' alt='$cname' title='$cname' /></a>&nbsp;";						
				}
			}
			
			if($this->get_allow_actions()){
				$this_db 		= $this->db;
				$query 			= $this->get_sql();
				$toolbar .= "<a href='#' onclick=\"pop_up('". $this->_file_path ."action.php?a=a&pk=$this->sql_key&pk_v=')\" /><img src='" . $this->_file_path . $this->theme ."/images/new.gif' alt='[ New ]' border='0' /></a>\n";
			}

			if($this->inlineedit_enabled){
				$toolbar .= "<a href='javascript:document.form_inlineedit.submit();'><img src='" . $this->_file_path . $this->theme ."/images/save.gif' alt='[ Save All ]' border='0' /></a>\n ";
			}
			
			if($this->allow_export){
				$alt_bgcolor	= urlencode($this->alt_bgcolor);
				$toolbar .= "<a href='#' onclick=\"location.href='". $this->_file_path ."phpgrid_export.php?pk=&pk_v=&alt_bgcolor=$alt_bgcolor'\"><img src='" . $this->_file_path. $this->theme ."/images/excel.gif' alt='[ Excel Export ]' border='0' /></a>\n ";
				$toolbar .= "<a href=\"#\" onclick=\"location.href='". $this->_file_path ."pdf_export.php'\" target=\"_new\"><img src='" . $this->_file_path. $this->theme ."/images/pdf.gif' alt='[ PDF Export ]' border='0' /></a>\n ";
				$toolbar .= "<a href=\"#\" onclick=\"location.href='". $this->_file_path ."xml_export.php'\" target=\"_new\"><img src='" . $this->_file_path. $this->theme ."/images/xml.gif' alt='[ XML Export ]' border='0' /></a>\n ";
			}

			if($this->print_enabled) {
				$toolbar .= "<a href='javascript:window.print();'><img src='" . $this->_file_path. $this->theme ."/images/print.gif' alt='[ Print ]' border='0' /></a>\n ";
			}	

			if($this->multidel_enabled){
				$orderby	= isset($_GET["orderby"]) ? $_GET["orderby"] : "";
				$direction 	= isset($_GET["direction"]) ? $_GET["direction"] : "";
				$pg 		= isset($_GET["pg"]) ? $_GET["pg"] : "";
				$toolbar .= " <a href=\"javascript:if(confirm('Are you sure to delete selected records?')){document.form_del.submit();}\"><img src='" . $this->_file_path. $this->theme ."/images/delete.gif' alt='[ Delete Selected ]' border='0' /></a>\n ";
			}
			
			if($this->ok_showcredit){
				$toolbar .= "<a href='http://www.phpgrid.com/grid/examples/' target='_new'><img src='" . $this->_file_path. $this->theme ."/images/help.gif' alt='[ Online Help ]' border='0' /></a>\n ";
				$toolbar .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span align='center'><a href='http://www.phpgrid.com' target='_new'><img src='".$this->_file_path."images/phpgrid_logo.gif' border='0' alt='Powered by phpGrid' /></a></span>\n ";
			}

			$toolbar .= "</div>\n\n";

			return $toolbar;
	}




	// Desc: return table open tag in html
	function render_formdel_open(){
		$formdel_open = ($this->multidel_enabled) ? "\n<form name='form_del' action='". $this->_file_path ."delselected_exe.php' method='post'>\n" : "";
		return $formdel_open;
	}








	// Desc: return table header in html
	function render_header(){
		$header 	= "";
		$this_db 	= $this->db;
		$query_str 	= $this->get_sql();
		$rs_header 	= $this_db->select_limit($query_str, 1, 1);

		// //////////////// Grid Scroll Problem: can't make the header to sync with the body. Temporally comment out. //////////////
		$header .= "<thead id='grid_head'>";

		$header .= "<tr style='". $this->get_tr_style(). "' class='". $this->get_tr_class(). "'>";
		$header .= ($this->ok_rowindex) ? "<th style='". $this->get_th_style() ."' class='". $this->get_th_class() ."' width='1%'><a>#</a></th>" : "";		// index column
        $header .= ($this->multidel_enabled) ? "<th style='". $this->get_th_style() ."' class='". $this->get_th_class() ."' width='1%'><a>&nbsp;</a></th>" : "";		// multidel column
		$header.= ($this->is_actncol_allwd)  ? "<th style='". $this->get_th_style() ."' class='". $this->get_th_class(). "' width='1%'><a>Actions</a></th>" : "";		// action column
		$header.= ($this->masterdetail_sql)  ? "<th style='". $this->get_th_style() ."' class='". $this->get_th_class(). "' width='1%'><a>&nbsp;</a></th>" : "";		// master detail column


		for($i = 0; $i < $this->_num_fields; $i++) {
			// get associated column name
			$col_name = $this_db->field_name($rs_header, $i);
			// don't render if this column is set to hide (By either column name or index)
			if(!isset($this->col_hiddens[$col_name])){
				// get currrent page number through GET
				$pg_cur = isset($_GET[PAGE_NUMBER]) ? PAGE_NUMBER."=".$_GET[PAGE_NUMBER] : "";
				// get sort direction {ASC|DESC}
				$sort_direction = $this->get_sort_direction($col_name);

				$header .= "<th style='". $this->get_th_style(). "' class='". $this->get_th_class() ."'><nobr>";
				$header .= ($this->get_allow_sort()) ? "<a href='". $_SERVER['PHP_SELF'] ."?orderby=". $col_name ."&direction=$sort_direction&$pg_cur'>":""; 
				// get column title by name or index
				if(isset($this->col_titles[$col_name]))
					$header .= $this->col_titles[$col_name];
				else
					$header .= $col_name;

				$header .=	($this->get_allow_sort()) ? $this->get_sort_direction_img($col_name, $sort_direction)."</a></nobr></th>" : "</nobr></th>";
			}
		}



        // render column header
		if(!is_null($this->col_objects)){
			for($i = 0; $i < sizeof($this->col_objects); $i++){
				$this->col_objects[$i]->th_style = $this->th_style;
				$this->col_objects[$i]->th_class = $this->th_class;
				$header.= $this->col_objects[$i]->render_header();
			}
		}


		// http://www.hypermedic.com/php/arrays3.htm
		$header .= "</tr>\n";

		$header .= "</thead>";

		return $header;
	}








	// Desc: return tablel body in html
	function render_body(){
		$this_db 	= $this->db;
		$sql_key 	= $this->get_sql_key();
		$query_str 	= $this->get_sql();
		// get record by page syntax: "SELECT * FROM ".$table." LIMIT ".$start.", ".$limit;
		$rs_body 	= $this_db->select_limit($query_str, $this->get_page_size(), intval($this->get_page_size() * ($this->page_current-1)));
		$body = "";
		$row_index = 0;
		$row_count = 0;

		$orderby		= isset($_GET["orderby"]) ? $_GET["orderby"] : "";
		$direction 		= isset($_GET["direction"]) ? $_GET["direction"] : "";
		$pg 			= isset($_GET["pg"]) ? $_GET["pg"] : "";
		$home_url 		= $_SERVER['PHP_SELF'];
		$master_row_index = isset($_GET["master_row_index"]) ? $_GET["master_row_index"] : MASTER_ROW_INDEX_DEFAULT;


		// //////////////// Grid Scroll Problem: can't make the header to sync with the body. Temporally comment out. //////////////
		$body .= "<tbody id='grid_body'>";// class='grid_body_scroll'>";

		while($cols = $this_db->fetch_array($rs_body)){
			
			
			// ////////////////////// TR //////////////////////////
			$body .= "<tr bgcolor='". $this->_get_row_bgcolor($row_index, $master_row_index) ."' style='". $this->get_tr_style(). "' class='". $this->get_tr_class(). "' ".
						  "onmouseover=\"". $this->get_onmouseover_color() ."\" onmouseout=\"this.style.background='". $this->_get_row_bgcolor($row_index, $master_row_index) ."'\">";
			// ////////////////////// TD //////////////////////////
			$body .= ($this->ok_rowindex) ? "<td valign='middle' align='center' class='index_css'>". intval($row_index+1) ."</td>" : "";	// index column

            // multidel checkbox
            $body .= ($this->multidel_enabled) ? "<td align='center'><input type='checkbox' name='___". $this->sql_key ."___". $cols[$this->sql_key] ."' value='". $cols[$this->sql_key] ."' /></td>" : "";

			// action column(http://aspgrid.com/manual_forms.html. a = action, q = query, k = key, k_v = key value);
			if($this->is_actncol_allwd){

				$body .= "<td class='action_col_css'><nobr>&nbsp;";

				if(strstr(strtoupper($this->action_type), "V")){
					$body .= "&nbsp;<a href=\"javascript:pop_up('". $this->_file_path. "action.php?a=v&pk=$this->sql_key&pk_v=$cols[$sql_key]')\"><img src='" . $this->_file_path. $this->theme ."/images/view_icon.gif' border='0' alt='View' /></a>&nbsp;";
				}
				if(strstr(strtoupper($this->action_type), "E")){
					$body .= "&nbsp;<a href=\"javascript:pop_up('". $this->_file_path. "action.php?a=e&pk=$this->sql_key&pk_v=$cols[$sql_key]')\"><img src='" . $this->_file_path. $this->theme ."/images/edit_icon.gif' border='0' alt='Edit' /></a>&nbsp;";
				}
				if(strstr(strtoupper($this->action_type), "D")){
					$body .= "&nbsp;<a href=\"javascript:pop_up('". $this->_file_path. "action.php?a=d&pk=$this->sql_key&pk_v=$cols[$sql_key]')\"><img src='" . $this->_file_path. $this->theme ."/images/delete_icon.gif' border='0' alt='Delete' /></a>&nbsp;";
				}
				
				// add additional user defined actions
				foreach ($this->actions as $action) {
					list($aname, $alink, $alink_id, $extra_attr, $aimage) = $action;
					$alink = ($alink_id != "" && isset($cols[$alink_id])) ? $alink. $cols[$alink_id] : $alink;
					if($aimage == ""){
						$body .= "&nbsp;<a href=\"$alink\" $extra_attr>$aname</a>&nbsp;";
					}else{
						$body .= "&nbsp;<a href=\"$alink\" $extra_attr><img src='$aimage' border='0' alt='$aname' title='$aname' /></a>&nbsp;";						
					}
				}

				$body .= "&nbsp;</nobr></td>";
			}
			
			// show master detail icon body
			if($this->masterdetail_sql != ""){
				$master_foreign_id = $cols[$this->masterdetail_key];
				$body .= "<td class='action_col_css'>&nbsp;";
				$body .= "<a href='". $this->_file_path ."master_foreignid_exe.php?home_url=$home_url&orderby=$orderby&direction=$direction&pg=$pg&master_row_index=$row_index&master_foreign_id=$master_foreign_id'><img src='" . $this->_file_path. $this->theme ."/images/masterdetail.gif' border='0' alt='View Detail' /></a>";
				$body .= "&nbsp;</td>";
			}

			for($i = 0; $i < $this->_num_fields; $i++) {
				// get associated column name
				$col_name = $this_db->field_name($rs_body, $i);
				// obtain the text used for hyperlink after "?foo=". the default is the text of current column in that record(row)
				$link_id = (isset($this->col_links[$col_name][1]) && $this->col_links[$col_name][1] != "") ? $this->col_links[$col_name][1] : $i;
								
				// force $link_id to return as column name, not index 			
				if(is_numeric($link_id)){ 
					$link_id = $this_db->field_name($rs_body, $i); 
				}
				
				// don't render if this column is set to hide (By either column name or index)
				if(!isset($this->col_hiddens[$col_name])){


					// get TD table for inline editing
					$body .= ($this->inlineedit_enabled) ? "<td>" : "<td style='".$this->get_col_style($i, $col_name)."'>";


					// ///////////// enable inline editing by set contentEditable to true //////////////
					// *** IMPORTANT ***: only display the RAW data in cell if inline edit is set to true
					// Note: css class cellscroll on and off are primarily used to set the height and width of the cell with scroll bar
					// 		 though other css properties can be set in it too.
					$arr_fieldsreadonly = (isset($_SESSION["fields_readonly"]))? explode(",", trim($_SESSION["fields_readonly"])) : array();
					if($this->inlineedit_enabled && in_array($col_name, $arr_fieldsreadonly) == false){			
						$id_raw   = "___". $col_name."___". $cols[$this->sql_key];
						$id_dvtxt = $id_raw ."___dvtxt";
						$id_dvta  = $id_raw ."___dvta";
						$id_ta	  = $id_raw ."___ta";
						$toggleEditOffFuncName = ($this->ajax_enabled) ? "toggleEditOffAjax('$id_dvtxt', '$id_dvta', '$id_ta');" : "toggleEditOff('$id_dvtxt', '$id_dvta', '$id_ta');";		
						$body .= "<div class=\"cell\">";
						$body .= "<div id=\"$id_dvtxt\" class=\"cellText\" onclick=\"window.status=' ';toggleEditOn('$id_dvtxt', '$id_dvta', '$id_ta');\"	height=100>$cols[$col_name]</div>";
						$body .= "<div id=\"$id_dvta\"  class=\"cellDivTextarea\"><textarea id=\"$id_ta\" name=\"$id_ta\" class=\"cellTextarea\" onblur=\"". $toggleEditOffFuncName ."\" onchange=\"\">$cols[$col_name]</textarea></div>";						
						$body .= "</div>";
					
					}else{
																	
						// defautl cell css style for not inline editing
						$body .= "<div class='cellScrollOff'>";
						$body .= $this->get_col_img($i,  $col_name, $cols[$col_name]);
				
						// replace cartridge return with html <br>. however don't show text when an image is set to display
						if($this->ok_nl2br){
							if(!isset($this->col_imgs[$col_name])){ $body .= nl2br($this->get_col_link($i, $col_name, $cols[$col_name], $cols[$link_id]));}
						}else{
							if(!isset($this->col_imgs[$col_name])){ $body .= $this->get_col_link($i, $col_name, $cols[$col_name], $cols[$link_id]);}
						}
						$body .= "</div>";
						
					}


					// generate the hidden fields for saving data later to database
					if($this->inlineedit_enabled){
						$this->inline_valueset .="\t<input type=\"hidden\" id=\"$id_raw\" name=\"$id_raw\" value=\"". $this->util->add_slashes($cols[$col_name]) ."\" />\n";
					}
					
					// sum the columns
					if($this->col_sum_enabled){
						$this->add_col_sum($col_name, $cols[$col_name]);
					}

					// end of TD tag for inline editing
				 	$body .= "</td>";



				}// if
			}// for

			// //////////////////// render each column body after the index column above ////////////////////////////
			if(!is_null($this->col_objects)){
				for($i = 0; $i < sizeof($this->col_objects); $i++){
					$col_name = $this->col_objects[$i]->col_name;
					$this->col_objects[$i]->set_col_value($cols[$col_name]);
					$body.= $this->col_objects[$i]->render_body();
				}
			}

			$body .= "</tr>\n";

			$row_index++;
			$row_count++;
		}// while

		$body .= "</tbody>";

		return $body;
	}








	// Desc: render grid footer
	function render_footer(){
		$this_db 	= $this->db;
		$sql_key 	= $this->get_sql_key();
		$query_str 	= $this->get_sql();
		// get record by page syntax: "SELECT * FROM ".$table." LIMIT ".$start.", ".$limit;
		$rs_footer = $this_db->select_limit($query_str, 1, 1);
		$footer = "";
		$row_index = 0;
		$row_count = 0;

		$footer .= "<tfoot id='grid_foot'>";// class='grid_foot_scroll'>";

		while($cols = $this_db->fetch_array($rs_footer)){
			// ////////////////////// TR //////////////////////////
			$footer .= "<tr bgcolor='' class='tfooter_css'>".
			// ////////////////////// TD //////////////////////////
			$footer .= ($this->ok_rowindex) ? "<td></td>" : "";	// index column

            // multidel checkbox
            $footer .= ($this->multidel_enabled) ? "<td></td>" : "";

			// action column(http://aspgrid.com/manual_forms.html. a = action, q = query, k = key, k_v = key value);
			$footer .= ($this->is_actncol_allwd) ? "<td></td>" : "";
			
			// master detail footer
			$footer .= ($this->masterdetail_sql != "") ? "<td></td>" : "";


			for($i = 0; $i < $this->_num_fields; $i++) {
				// get associated column name
				$col_name = $this_db->field_name($rs_footer, $i);
				// don't render if this column is set to hide (By either column name or index)
				if(!isset($this->col_hiddens[$col_name])){

					if(isset($this->col_sums[$col_name])){
						// display the total
						$sum = $this->col_sums[$col_name];
						$sum = (strstr($sum, '.') > 0) ? number_format($sum, 2) : number_format($sum);
						$footer .= isset($this->col_sums[$col_name]) ? "<td style='".$this->get_col_style($i, $col_name)."'><strong>".$sum."</strong></td>" : "<td></td>";
					}else{
						$footer .= "<td></td>";
					}


				}
			}

			// //////////////////// render each column body after the index column above ////////////////////////////
			if(!is_null($this->col_objects)){
				for($i = 0; $i < sizeof($this->col_objects); $i++){
					$footer .= "<td></td>";
				}
			}

			$footer .= "</tr>\n";

			$row_index++;
			$row_count++;
		}// while

		$footer .= "</tfoot>";

		return $footer;
	}








	// the table end tag and also end tag for multiple delete form
	function render_formdel_close(){
		$formdel_close = "";
		if($this->multidel_enabled){
			$orderby		= isset($_GET["orderby"]) ? $_GET["orderby"] : "";
			$direction 		= isset($_GET["direction"]) ? $_GET["direction"] : "";
			$pg 			= isset($_GET["pg"]) ? $_GET["pg"] : "";
			$formdel_close .= "<input name='home_url' value='". $_SERVER['PHP_SELF'] ."' type='hidden' />\n";
			$formdel_close .= "<input name='orderby' 	value='$orderby' type='hidden' />\n";
			$formdel_close .= "<input name='direction' value='$direction' type='hidden' />\n";
			$formdel_close .= "<input name='pg' 		value='$pg' type='hidden' />\n";
			$formdel_close .= "</form>\n"; 			// the begining <form> tag is created in render_header for multiple delete
		}
		return $formdel_close;
	}






	// Desc: rendering page navigation bar for database tables that have many rows
	function render_page_nav_bar(){
		$page_nav_bar = "";
		// get currrent ORDER BY through GET
		$orderby = isset($_GET["orderby"]) ? "orderby=".$_GET["orderby"] : "";

		// get sorting direction
		$sort_direction = isset($_GET["direction"]) ? "direction=".$_GET["direction"] : "";

		$page_nav_bar .= "<span class='navbtn_css'>";

		// display First & Previous page buttons
		if(intval($this->page_current-1) > 0){
			$page_nav_bar .= "<a href='". $_SERVER['PHP_SELF']. "?".PAGE_NUMBER."=1&$orderby&$sort_direction'><img src='" . $this->_file_path. $this->theme ."/images/firstpg.gif' alt='[ First Page ]' border='0' /></a> ";
			$page_nav_bar .= "<a href='". $_SERVER['PHP_SELF'] ."?".PAGE_NUMBER."=". intval($this->page_current-1) ."&$orderby&$sort_direction'><img src='" . $this->_file_path. $this->theme ."/images/prevpg.gif' alt='[ Prev Page ]' border='0' /></a> ";
		}else{
			$page_nav_bar .= "<img src='" . $this->_file_path. $this->theme ."/images/firstpgdim.gif' alt='[ First Page ]' border='0' /> ";
			$page_nav_bar .= "<img src='" . $this->_file_path. $this->theme ."/images/prevpgdim.gif' alt='[ Prev Page ]' border='0' /> ";
		}

		// display page numbers in TEXT (dropdown is preferred)
/*
		for($i=1,$j=1; $i <= $this->_num_rows; $i=$i+$this->page_size,$j++){
			if($j != $this->page_current)
				$page_nav_bar .= " <a href='". $_SERVER['PHP_SELF']. "?".PAGE_NUMBER."=$j&$orderby&$sort_direction' style='font-size:10pt'>$j</a> ";
			elseif($this->_num_rows > $this->page_size)
				$page_nav_bar .= "<b style='font-size:10pt'>$j</b>";
		}
*/
		// display page numbers in DROPDOWN
		$page_nav_bar .= "<span class='toolbar_align'>";
		$page_nav_bar .= "Page <select name='pg' onchange=\"window.location='". $_SERVER['PHP_SELF']. "?".PAGE_NUMBER."='+this.value+'&$orderby&$sort_direction'\";>";
		for($i=1,$j=1; $i <= $this->_num_rows; $i=$i+$this->page_size,$j++){
			if($j != $this->page_current)
				$page_nav_bar .= "<option value='$j'>$j</option>";
			elseif($this->_num_rows > $this->page_size)
				$page_nav_bar .= "<option value='$j' selected>$j</option>";
			else
				$page_nav_bar .= "<option value='1' selected>1</option>";		// default is 1 if # number of rows less then page size

		}
		$page_nav_bar .= "</select> of <strong>". ceil($this->_num_rows/$this->page_size) ."</strong> ";
		$page_nav_bar .= "</span>";
		
		// display Next & Last page buttons
		if(intval($this->page_current * $this->page_size) < $this->_num_rows){
			$page_nav_bar .= "<a href='". $_SERVER['PHP_SELF']. "?".PAGE_NUMBER."=". intval($this->page_current+1) ."&$orderby&$sort_direction'><img src='" . $this->_file_path. $this->theme ."/images/nextpg.gif' alt='[ Next Page ]' border='0' /></a> ";
			$page_nav_bar .= "<a href='". $_SERVER['PHP_SELF']. "?".PAGE_NUMBER."=". ceil(($this->_num_rows) / ($this->page_size)) ."&$orderby&$sort_direction'><img src='" . $this->_file_path. $this->theme ."/images/lastpg.gif' alt='[ Last Page ]' border='0' /></a> ";
		}else{
			$page_nav_bar .= "<img src='" . $this->_file_path. $this->theme ."/images/nextpgdim.gif' alt='[ Next Page ]' border='0' /> ";
			$page_nav_bar .= "<img src='" . $this->_file_path. $this->theme ."/images/lastpgdim.gif' alt='[ Last Page ]' border='0' /> ";
		}

		$page_nav_bar .= "</span>";

		return $page_nav_bar;

	}








	// Desc: return tablel body in html
	function render_template_layout(){
		$this_db 	= $this->db;
		$sql_key 	= $this->get_sql_key();
		$query_str 	= $this->get_sql();
		// get record by page syntax: "SELECT * FROM ".$table." LIMIT ".$start.", ".$limit;
		$rs_body 	= $this_db->select_limit($query_str, $this->get_page_size(), intval($this->get_page_size() * ($this->page_current-1)));
		$body 		= "";
		$row_index 	= 0;
		$row_count 	= 0;
		// get grand total records returned (NOT just this page)
//		$this->_num_rows 	= $this_db->num_rows($this_db->db_query($query_str));

		// $body = "<span style='font-size:7pt;font-family:verdana'>Sort By:</span> <select name='orderby' style='font-size:7pt'><option>xxxxxxxxxxxxx</option></select>";
		while($cols = $this_db->fetch_array($rs_body)){

			// template layout actions column(http://aspgrid.com/manual_forms.html. a = action, q = query, k = key, k_v = key value);
			$query_where =  urlencode(" WHERE ". $sql_key ." = '$cols[$sql_key]'");
			$query = $this->_get_reformatted_sql($this->get_sql(), $query_where);

			// Technical Note 1: \\\" becomes \" in browser.
			// Technical Note 2: eregi_replace is 10 times slower than str_replace but case insensitive.
			//					 search a better string replacer in the future
			$url_admin_view  = str_replace(" ", "%20", $this->_file_path. "action.php?a=v&pk=$this->sql_key&pk_v=$cols[$sql_key]");
			$url_admin_edit  = str_replace(" ", "%20", $this->_file_path. "action.php?a=e&pk=$this->sql_key&pk_v=$cols[$sql_key]");
			$url_admin_delete= str_replace(" ", "%20", $this->_file_path. "action.php?a=d&pk=$this->sql_key&pk_v=$cols[$sql_key]");
			$template_layout = eregi_replace("{Admin:View]",   "javascript:pop_up(\\\"$url_admin_view\\\")", $this->template_layout);
			$template_layout = eregi_replace("{Admin:Edit]", 	 "javascript:pop_up(\\\"$url_admin_edit\\\")", $template_layout);
			$template_layout = eregi_replace("{Admin:Delete]", "javascript:pop_up(\\\"$url_admin_delete\\\")", $template_layout);
			$template_layout = eregi_replace("<tr", "<tr bgcolor='".$this->_get_row_bgcolor($row_index)."'",  $template_layout);
			$template_layout = eregi_replace("<td", "<td onmouseover=". $this->get_onmouseover_color() ." onmouseout=this.style.background='". $this->_get_row_bgcolor($row_index) ."'",  $template_layout);
			eval ("\$template_layout = \"$template_layout\";");
			print($template_layout);

			$row_index++;
			$row_count++;
		}

		return $body;
	}




	// Desc: return master detail table header in html
	function render_masterdetail_header(){
		$header 	= "";
		$this_db 	= $this->db;
		$query_str 	= $this->masterdetail_sql;
		$rs_header 	= $this_db->select_limit($query_str, 1, 1);
		$num_fields = $this_db->num_fields($rs_header);

		// //////////////// Grid Scroll Problem: can't make the header to sync with the body. Temporally comment out. //////////////
		$header .= "<thead id='grid_head'>";

		$header .= "<tr bgcolor='". MASTER_ROW_BGCOLOR ."' style='". $this->get_tr_style(). "' class='". $this->get_tr_class(). "'>";
		$header .= ($this->ok_rowindex) ? "<th bgcolor='". MASTER_ROW_BGCOLOR ."' width='1%'><a>#</a></th>" : "";		// index column

		for($i = 0; $i < $num_fields; $i++) {
			// get associated column name
			$col_name = $this_db->field_name($rs_header, $i);
			$header .= "<th style='". $this->get_th_style(). "' class='". $this->get_th_class() ."'><nobr>";
			// get column title by name or index
			$header .= $col_name;

			$header .= "</nobr></th>";
		}


		// http://www.hypermedic.com/php/arrays3.htm
		$header .= "</tr>\n";
		$header .= "</thead>";

		return $header;
	}
	

	function render_masterdetail(){
		
		print("<span style='font-size:7pt;color:lightgrey;font-family:arial'>". $this->masterdetail_sql ."</span>");
		
		$this_db 	= $this->db;
		$query_str 	= $this->masterdetail_sql; // $this->get_sql();
		$rs_body 	= $this_db->db_query($query_str);
		$num_fields = $this_db->num_fields($rs_body);
		$body = "";
		$row_index = 0;
		$row_count = 0;

		$body .= "<tbody id='grid_body'>";

		while($cols = $this_db->fetch_array($rs_body)){
			
			// ////////////////////// TR //////////////////////////
			$body .= "<tr bgcolor='". $this->_get_row_bgcolor($row_index) ."' style='". $this->get_tr_style(). "' class='". $this->get_tr_class(). "' ".
						  "onmouseover=\"". $this->get_onmouseover_color() ."\" onmouseout=\"this.style.background='". $this->_get_row_bgcolor($row_index) ."'\">";
			// ////////////////////// TD //////////////////////////
			$body .= ($this->ok_rowindex) ? "<td valign='middle' align='center' class='index_css'>". intval($row_index+1) ."</td>" : "";	// index column

			for($i = 0; $i < $num_fields; $i++) {
				// get associated column name
				$col_name = $this_db->field_name($rs_body, $i);
				// obtain the text used for hyperlink after "?foo=". the default is the text of current column in that record(row)
				$link_id = (isset($this->col_links[$col_name][1]) && $this->col_links[$col_name][1] != "") ? $this->col_links[$col_name][1] : $i;
								
				// force $link_id to return as column name, not index 			
				if(is_numeric($link_id)){ 
					$link_id = $this_db->field_name($rs_body, $i); 
				}
				
					// get TD table for inline editing
				$body .= "<td>";
														
				// defautl cell css style for not inline editing
				$body .= "<div class='cellScrollOff'>";
				$body .= $this->get_col_img($i,  $col_name, $cols[$col_name]);
				// replace cartridge return with html <br>
				if($this->ok_nl2br){
					$body .= nl2br($this->get_col_link($i, $col_name, $cols[$col_name], $cols[$link_id]));
				}else{
					$body .= $this->get_col_link($i, $col_name, $cols[$col_name], $cols[$link_id]);
				}
				$body .= "</div>";

				// end of TD tag for inline editing
			 	$body .= "</td>";

			}// for

			$body .= "</tr>\n";

			$row_index++;
			$row_count++;
		}// while

		$body .= "</tbody>";

		return $body;
	}






	// generate xml formatted report for export
	function get_xml_export($root_name, $tblname, $is_just_curr_page = false){
		$this_db 	= $this->db;
		$sql_key 	= $this->get_sql_key();
		$query_str 	= $this->get_sql();
		// get record by page syntax: "SELECT * FROM ".$table." LIMIT ".$start.", ".$limit;
		if($is_just_curr_page){
			$rs	= $this_db->select_limit($query_str, $this->get_page_size(), intval($this->get_page_size() * ($this->page_current-1)));
		}else{
			$rs = $this_db->db_query($query_str);
		}
		$xmlstr = "";
		$row_index = 0;
		$row_count = 0;

		$xmlstr.= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$xmlstr.= "<$root_name>\n";

		while($cols = $this_db->fetch_array($rs)){

			$table_name = $tblname; // $this->sql_table; //$this_db->field_table_by_querystr($this->sql);
			$xmlstr .= "<$table_name>";

			for($i = 0; $i < $this->_num_fields; $i++) {

				// get associated column name
				$col_name = $this_db->field_name($rs, $i);
				$xmlstr .= "<$col_name>". $cols[$col_name] ."</$col_name>\n";

			}
			$xmlstr .= "</$table_name>\n";

			$row_index++;
			$row_count++;
		}// while

		$xmlstr .= "</$root_name>";

		return $xmlstr;
	}


	// Desc: finally it prints to the browser
	function display(){
		
		$master_row_index = isset($_GET["master_row_index"]) ? $_GET["master_row_index"] : MASTER_ROW_INDEX_DEFAULT;

		// javascript pop_up is rendered when get_allow_actions() is true
		print("<link rel='stylesheet' href='". $this->_file_path .$this->theme ."/css/datagrid.css' type='text/css' />\n");
		print("<script src='". $this->_file_path ."js/common.js' type='text/javascript'></script>\n");
		print("<script src='". $this->_file_path ."js/pg_ajax.js' type='text/javascript'></script>\n");

		// //////// Custruct QUERY STRING HERE //////////
		$query_str = $this->get_sql() . $this->get_sorted_by();
		$this->set_sql($query_str);


		if($this->multidel_enabled){
			print($this->render_formdel_open());
		}
		
		print("<table id='grid_top' class='". $this->grid_class ."'><tr><td>\n");

		
			if($this->toolbar_enabled){
				print("<nobr>". $this->render_toolbar() ."</nobr>\n");
			}

			print("<div id=\"grid_content\">\n");
			print("<table cellspacing='0' cellpadding='0' border='". $this->border ."' background='". $this->table_bg ."' style='". $this->table_style ."' class='". $this->table_class ."'>\n");
			// //////////// TEMPLATE LAYOUT ////////////
			if($this->template_layout == ""){
				print($this->render_header());
				print($this->render_body());
				print($this->render_footer());
			}else{
				print($this->render_template_layout());
			}
			// /////////////////////////////////////////
			print("</table>");
			print("</div>");

		print("</table>\n");
		
		print($this->render_formdel_close());

		// display the "hidden" form if the inlineedit_enabled is set to true
		if($this->inlineedit_enabled || $this->multidel_enabled){
			$orderby	= isset($_GET["orderby"]) ? $_GET["orderby"] : "";
			$direction 	= isset($_GET["direction"]) ? $_GET["direction"] : "";
			$pg 		= isset($_GET["pg"]) ? $_GET["pg"] : "";
			print("\n<form name='form_inlineedit' action='". $this->_file_path ."inlineedit_exe.php' method='post'>\n");
			print($this->inline_valueset);
			print("\t<input name='home_url' 	value='". $_SERVER['PHP_SELF'] ."' type='hidden' />\n");
			print("\t<input name='orderby'  	value='$orderby' type='hidden' />\n");
			print("\t<input name='direction' 	value='$direction' type='hidden' />\n");
			print("\t<input name='pg' 		value='$pg' type='hidden' />\n");
			print("</form>\n");
		}
		
		
		
		
		// display master detail table if it has been set
		if($master_row_index != MASTER_ROW_INDEX_DEFAULT){
			
			print("<table id='grid_top' class='". $this->grid_class ."'><tr><td>\n");
			print("<table cellspacing='0' cellpadding='0' border='". $this->border ."' background='". $this->table_bg ."' style='". $this->table_style ."' class='". $this->table_class ."'>\n");
			print($this->render_masterdetail_header());
			print($this->render_masterdetail());	
			print("</table>");
			print("</table>\n");
			
		}
	}

















// ****************************** SET AND GET ATRRIBUTES & PROPERTIES *******************************
// **************************************************************************************************

	// Desc: set sql string
	function set_sql($sqlstr){
		$this->sql = $sqlstr;
		$this_db 	= $this->db;
		$this->_num_rows   = $this_db->num_rows($this_db->db_query($sqlstr));
		$this->_num_fields = $this_db->num_fields($this_db->select_limit($sqlstr,1, 1));
		$_SESSION["sql"] = $sqlstr;
	}

	// Desc: get sql string
	function get_sql(){
		return $this->sql;
	}
	
	// Desc: set table name in sql string. Must call this function on client. 
	// The table name was automatically returned by calling field_table_by_querystr() 
	// in C_Database class. However, it isn't so reliable in some case due to the parse method used.
	function set_sql_table($sqltable){
		$this->sql_table = $sqltable;
		$_SESSION["sql_table"] = $sqltable;	
	}

	// Desc: set sql PK
	function set_sql_key($sqlkey){
		$this->sql_key = $sqlkey;	
		$_SESSION["sql_key"] = $sqlkey;
	}

	// Desc: get sql PK
	function get_sql_key(){
		return $this->sql_key;
	}

	// Desc: set table html border
	function set_border($tblborder){
		$this->border = $tblborder;
	}

	// Desc: get table html border
	function get_border(){
		return $this->border;
	}

	// Desc: set table background attribute
	// Note: style may overwrite the HTML attribute setting such as 'border', 'bgcolor'
	function set_table_bg($tblbg){
		$this->table_bg = $tblbg;
	}

	// Desc: get table background attribute
	function get_table_bg(){
		return $this->table_bg;
	}

	// Desc: set alternative row background color
	function set_alt_bgcolor($bgclr, $altrownum=1){
		$this->alt_bgcolor = $bgclr;
		$this->alt_rownum  = $altrownum;
	}

	// Desc: get alternative row background color
	function get_alt_bgcolor(){
		return $this->alt_bgcolor;
	}

	// Desc: set table css style
	// Note: style may overwrite the HTML attribute setting such as 'border', 'bgcolor'
	function set_table_style($tblstyle){
		$this->table_style = $tblstyle;
	}

	// Desc: get table css style
	function get_table_style(){
		return $this->table_style;
	}

	// Desc: set table css class
	// Note: class may overwrite the HTML attribute setting such as 'border', 'bgcolor'
	function set_table_class($tblclass){
		$this->table_class = $tblclass;
	}

	// Desc: get table css style
	function get_table_class(){
		return $this->table_class;
	}

	// Desc: set table css style
	function set_th_style($thstyle){
		$this->th_style = $thstyle;
	}

	// Desc: get table th css style
	function get_th_style(){
		return $this->th_style;
	}

	// Desc: set table css class
	function set_th_class($thclass){
		$this->th_class = $thclass;
	}

	// Desc: get table th css class
	function get_th_class(){
		return $this->th_class;
	}

	// Desc: set table row css style
	function set_tr_style($trstyle){
		$this->tr_style = $trstyle;
	}

	// Desc: get table row css style
	function get_tr_style(){
		return $this->tr_style;
	}

	// Desc: set table css class
	function set_tr_class($trclass){
		$this->tr_class = $trclass;
	}

	// Desc: get table th css class
	function get_tr_class(){
		return $this->tr_class;
	}

	// Desc: set if grid allows sort {true|false}
	function set_allow_sort($allowsort){
		$this->allow_sort = $allowsort;
	}

	// Desc: get allow_sort attribute {true|false}
	function get_allow_sort(){
		return $this->allow_sort;
	}

	// Desc: set order by SQL clause
	function set_sorted_by($sortedby, $direction="ASC"){
		$this->sorted_by = $sortedby;
		$this->sort_direction = $direction;
	}

	// Desc: get order by SQL clause including the ASC or DESC order.
	// Check value from GET first if it is a manual sort by user or else
	// get the value from sorted_by
	function get_sorted_by(){
		// get sorting direction
		$sortdirection = isset($_GET["direction"]) ? $_GET["direction"] : "";
		if($sortdirection == ""){
			$sortdirection = $this->sort_direction;
		}
		// then combine with the ORDER BYs
		$sortedby = isset($_GET["orderby"])		 ? " ORDER BY ".$_GET["orderby"]. " $sortdirection" : "";
		if($sortedby == ""){
			$sortedby = ($this->sorted_by != "") ? " ORDER BY ".$this->sorted_by. " $sortdirection" : "";
		}
		return $sortedby;
	}

	// Desc: get the order sorting direction
	// Default is ASC. the current sorting column will be sorted in the opposite way
	function get_sort_direction($orderby){
		// get currrent ORDER BY through GET
		$cur_orderby = isset($_GET["orderby"]) ? $_GET["orderby"] : "";
		// echo $orderby . " == " . $cur_orderby ."<br>";
		// get sort direction {ASC|DESC}
		if($cur_orderby != trim($orderby)){
			$sort_direction = "ASC";
		}else{
			// echo $this->sort_direction;
			$sort_direction = isset($_GET["direction"]) ? $_GET["direction"] : $this->sort_direction;
			$sort_direction = ($sort_direction == "ASC") ? "DESC" : "ASC";
		}
		return $sort_direction;
	}

	// Desc: set grid allow actions (view/edit/delete) column {true|false}
	function set_allow_actions($allowactions){
		$this->is_actncol_allwd = $allowactions;
	}

	// Desc: get grid allow actions attribute {true|false}
	function get_allow_actions(){
		return $this->is_actncol_allwd;
	}

	// Desc: set grid allow show row index column {true|false}
	function set_ok_rowindex($okrowindex){
		$this->ok_rowindex = $okrowindex;
	}

	// Desc: get grid allow show row index column {true|false}
	function get_ok_rowindex(){
		return $this->ok_rowindex;
	}

	// Desc: set grid to allow replace cartridge return in text to <br> {true|false}
	function set_ok_nl2br($oknl2br){
		$this->ok_nl2br = $oknl2br;
	}

	// Desc: get grid to allow replace cartridge return in text to <br> {true|false}
	function get_ok_nl2br(){
		return $this->ok_nl2br;
	}

	// Desc: set grid page size
	function set_page_size($pagesize){
		$this->page_size = $pagesize;
	}

	// Desc: get grid page size
	function get_page_size(){
		return $this->page_size;
	}

	// Desc: set show credit box at the bottom {true|false}
	function set_ok_showcredit($okshowcredit){
		$this->ok_showcredit = $okshowcredit;
	}

	// Desc: get show credit box {true|false}
	function get_ok_showcredit(){
		return $this->ok_showcredit;
	}

	// Desc: set allow XML, Excel export {true|false}
	function set_allow_export($allowexport){
		$this->allow_export = $allowexport;
	}

	// Desc: get allow XML, Excel export {true|false}
	function get_allow_export(){
		return $this->allow_export;
	}

	// Desc: set column title
	// Note: as a default program set database field name as the column name
	// 		 this function has no corresponding get function because it's a simple single dimension array
	function set_col_title($col_index, $new_title){
		$this->col_titles[$col_index] = $new_title;
	}

	// Desc: set column to be hidden
	// Note: this function has no corresponding get function because it's a simple single dimension array
	function set_col_hidden($col_index){
		$this->col_hiddens[$col_index] = $col_index;
		$_SESSION["col_hiddens"] = $this->col_hiddens;
	}

	// Desc: Overwrite displayed text from the database, or no text at all.
	// Default is none. It is used for image link internally
	// Example: view | edit, for image we don't need text
	//
	// Note: 
	// Default parameter should only be used internally. All FIRST four parameters 
	// must present to render the conditional dynamic output (CDO). When the sixth parameter or 
	// the fifth default parameter is set to true, the original value will display as well
	// 
	// <parameter> 
	// $col_index - data field index name
	// $op_val_pair - Oprand value pair. e.g. " > 10 "; " = 'abc'"; " != true " etc.
	// $true_val - value to return when the comparison is true
	// $false_val - value to return when the comparison is false
	// $show_old_val - wehn set to true, the original value will display as well
	function set_col_txt($col_index, $op_val_pair="", $true_val="", $false_val="", $show_old_val = false){

		$this->col_txts[$col_index] = array($op_val_pair, $true_val, $false_val, $show_old_val);	
	}

	// Desc: get column the overwritten txt set by users
	// This function is not currently used becuase col_txt[] is used internally.
	function get_col_txt($col_index){
		foreach($this->col_txts as $key => $value){
			if($col_index === $key)
				return $this->col_txts[$col_index];
		}
	}
	




	// Desc: set a column to display img, $path_step is the steps in the path, add "../" as needed
	function set_col_img($col_index, $path_step, $extra_attr){
		$this->col_imgs[$col_index] = array($path_step, $extra_attr);
	}

	// Desc:get column image setting. The displaying of image path is supressed 
	// in get_col_link() when text is rendered
	// *************************** TECHNICAL NOTE **************************
	// * how to access for
	// * $this->col_imgs[$col_index] = array($path_step, $extra_attr):
	// * (also see: http://us4.php.net/manual/en/language.types.array.php)
	// * $path_step 	= $this->col_imgs[$col_index][0];
	// * $extra_attr 	= $this->col_imgs[$col_index][1];
	// *********************************************************************
	function get_col_img($col_index, $col_name, $img_link){
		foreach($this->col_imgs as $key => $value){
			// get value by name or index
//			echo "col_index: $col_index; col_name: $col_name; key: $key => value: $value<br>";
			if($col_index === $key){
				return "<img src='". $this->col_imgs[$col_index][0].$img_link ."' style='". $this->col_imgs[$col_index][1] ."' />";
			}elseif($col_name === $key){
				return "<img src='". $this->col_imgs[$col_name][0].$img_link  ."' style='". $this->col_imgs[$col_name][1]  ."' />";
			}
		}
		return "";
	}

	// Desc: set style of a column
	// Example: $dg -> set_col_style(0, "font-size:8pt");
	function set_col_style($col_index, $style){
		$this->col_styles[$col_index] = $style;
	}

	// Desc: get the style for that column in html
	function get_col_style($col_index, $col_name){
		foreach($this->col_styles as $key => $value){
			if($col_index === $key || $col_name === $key)
				return $value;
		}
		return "";
	}

	// Desc: set column link and value in a two dimensional array;
	function set_col_link($col_index, $link, $link_id="", $extra_attr=""){
		$this->col_links[$col_index] = array($link, $link_id, $extra_attr);
	}

	// ******************** TECHNICAL NOTE ***********************
	// Desc: this function actually returns the value of the colum by default
	// if not it calls the col_txts() to overwrite the text or
	// contruct the hyper link for that column in html
	//
	// * how to access for
	// * $this->col_txts[$col_index] = array($op_val_pair, $true_val, $false_val, show_old_val):
	// * (also see: http://us4.php.net/manual/en/language.types.array.php)
	// * $op_val_pair 		= $this->col_txts[$col_index][0];
	// * $true_val 		= $this->col_txts[$col_index][1];
	// * $false_val 	= $this->col_txts[$col_index][2];
	// * $show_old_val	= $this->col_txts[$col_index][3];
	// *********************************************************************
	function get_col_link($col_index, $col_name, $text, $link_id){
		foreach($this->col_txts as $key => $value){
			
			if($col_index === $key){
				// render conditional dynamic ouput (CDO) only all parameters are not set as default via set_col_txt()
				if($this->col_txts[$col_index][0] != "" && $this->col_txts[$col_index][1] != "" && $this->col_txts[$col_index][2] != ""){
					if(eval("return ". $text . $this->col_txts[$col_index][0] .";")){
						return ($this->col_txts[$col_index][3]) ? $this->col_txts[$col_index][1] ."&nbsp;".$text : $this->col_txts[$col_index][1];
					}else{
						return ($this->col_txts[$col_index][3]) ? $this->col_txts[$col_index][2] ."&nbsp;".$text : $this->col_txts[$col_index][2];
					}
				}else{
					return $this->col_txts[$col_index];
				}   
			}elseif($col_name === $key){
				if($this->col_txts[$col_name][0] != "" && $this->col_txts[$col_name][1] != "" && $this->col_txts[$col_name][2] != ""){
					if(eval("return ". $text . $this->col_txts[$col_name][0] .";")){
						return ($this->col_txts[$col_name][3]) ? $this->col_txts[$col_name][1] ."&nbsp;".$text : $this->col_txts[$col_name][1];
					}else{
						return ($this->col_txts[$col_name][3]) ? $this->col_txts[$col_name][2] ."&nbsp;".$text : $this->col_txts[$col_name][2];
					}
				}else{
					return $this->col_txts[$col_name];
				}   
			}
			
		}
		foreach($this->col_links as $key => $value){
			if($col_index === $key)
				return "<a href='". $this->col_links[$col_index][0]. $this->col_links[$col_name][1] ."$link_id' ". $this->col_links[$col_name][2] .">". $text ."</a>";
			elseif($col_name === $key)
				return "<a href='". $this->col_links[$col_name][0].  $link_id ."' ". $this->col_links[$col_name][2] .">". $text ."</a>";
		}
		return $text;
	}

	// Desc: get background color of each row. values are obtained from array alt_bgcolor.
	// use fixed color when this row is selected as master/detail.	
	function _get_row_bgcolor($row_index, $master_row_index= MASTER_ROW_INDEX_DEFAULT){	
		if($master_row_index == $row_index){
			return MASTER_ROW_BGCOLOR;
		}else{
			$body_bg = split(",", $this->get_alt_bgcolor());
			$altrownum = $this->alt_rownum;
			$bg = $row_index / $altrownum % 2;
	
			return trim($body_bg[$bg]);
		}
	}

	// Desc: set the onmouseover background color and forgroud color for all the rows
	function set_onmouseover($bgcolor, $fgcolor=""){
		$this->onmouseover_bgcolor = $bgcolor;
		$this->onmouseover_fgcolor = $fgcolor;
	}

	// Desc: get the onmouseover color for all the rows
	function get_onmouseover_color(){
		$onmouseover = "";
		if($this->onmouseover_bgcolor != ""){
			$onmouseover = "this.style.background='". $this->onmouseover_bgcolor ."';";
		}
		if($this->onmouseover_fgcolor != ""){
			$onmouseover .= "this.style.color='". $this->onmouseover_fgcolor ."';";
		}
		if($onmouseover == "")
			return "''";
		else
			return $onmouseover;
	}

	// Desc: set page property, start num and page size.
	// ****** Note: this should eventually replace the set_page_size() ******
	function set_page_property($pg_sz, $pg_start_num=1){
		$this->page_start_num 	= $pg_start_num;
		$this->page_size 	= $pg_sz;
	}

	// Desc: set free output layout formatting
	function set_template_layout($template_layout){
		$template_layout = str_replace("}", "]", str_replace("{DataField:", "\$cols[", $template_layout));
		$this->template_layout = $template_layout;
	}

	// Desc: get free output layout formatting string
	function get_template_layout(){
		return $this->template_layout;
	}

	// Desc: set wether allow inline editing (true|false) - compatible with IE only using contentEditable attribute in block elmt
	// The second parameter enables AJAX support. Default value is false
	// Note: this will remove all innerHTML tags when rendered.
	function set_inlineedit_enabled($inlineeditenabled, $ajaxenabled = false){
		$this->inlineedit_enabled = $inlineeditenabled;
		$this->ajax_enabled = $ajaxenabled;
	}

	// Desc: get function for $inlineedit_enabled
//	function get_inlineedit_enabled(){
//		return $this->inlineedit_enabled;
//	}

	// Desc: set wether allow multiple record delete {true|false}
	function set_multidel_enabled($muldelenabled){
		$this->multidel_enabled = $muldelenabled;
//		if($okmultidelete){
//			$this->add_column($this->sql_key, "", "", "Form:checkbox");
//		}
	}

	// Desc: get ok_multidelete value {true|false}
//	function get_multidel_enabled(){
//		return $this->multidel_enabled;
//	}

	// Desc: set grid (outermost) CSS class
	function set_grid_class($gridclass){
		$this->grid_class = $gridclass;
	}

	// Desc: get grid (outermost) CSS class
	function get_grid_class(){
		return $this->grid_class;
	}

	// Desc: set grid scroll properties in css style
	function set_grid_body_style($gridbodystyle){
		$this->grid_body_style = $gridbodystyle;
	}

	// Desc: get scroll properties
	function get_grid_body_style(){
		return $this->grid_body_style;
	}

	// Desc: set CSS for div.cellscroll
	function set_cell_style($cellstyle){
		$this->cell_style = $cellstyle;
	}

	// Desc: get CSS div.cellscroll
	function get_cell_style(){
		return $this->cell_style;
	}

	// Desc: set CSS media print style for div.cellscroll
	function set_cell_prtstyle($cellprtstyle){
		$this->cell_prtstyle = $cellprtstyle;
	}

	// Desc: get CSS media print style div.cellscroll
	function get_cell_prtstyle(){
		return $this->cell_prtstyle;
	}

	// Desc: set grid theme
	function set_theme($themetype){
		$this->theme = "theme/". $themetype;
		$_SESSION["theme"] = $this->theme;
	}

	// Desc: get grid theme
	function get_theme(){
		if(!isset($_SESSION["theme"])){
			$_SESSION["theme"] = "theme/royal";
		}
		if($_SESSION["theme"] == ""){
			$_SESSION["theme"] = "theme/royal";
		}
		return $_SESSION["theme"];
	}

    	// Desc: set wether allow displaying column total{true|false}
    function set_col_sum_enabled($colsumenabled){
        $this->col_sum_enabled = $colsumenabled;
    }

    // Desc: get wether allow displaying column total{true|false}
    function get_col_sum_enabled(){
        return $this->col_sum_enabled;
    }

    // Desc: add column to col_sums array
    function set_col_sum($colname){
        $this->col_sums[$colname] = 0;
    }

    // Desc: perform total addition to the column
    function add_col_sum($colname, $value){
        if(isset($this->col_sums[$colname])){
            if(!is_numeric($value)){
                $value = 0;
            }
            $this->col_sums[$colname] += $value;
        }
    }

    // Desc: set wether allow displaying tool bar{true|false}
    function set_toolbar_enabled($toolbarenabled){
        $this->toolbar_enabled = $toolbarenabled;
    }

    // Desc: get wether allow displaying tool bar{true|false}
    function get_toolbar_enabled(){
        return $this->toolbar_enabled;
    }
    
    // Desc: set wether allow displaying print button{true|false}
    function set_print_enabled($printenabled){
        $this->print_enabled = $printenabled;
    }

    // Desc: get wether allow displaying print button{true|false}
    function get_print_enabled(){
        return $this->print_enabled;
    }    

	// Desc: set admin action type
	function set_action_type($act_type){
		$this->action_type = $act_type;
	}

	// Desc: disable data fields for editing in action.php
	// It is stored in the SESSION variable
	function set_fields_readonly($arr_dbfields){
		$_SESSION["fields_readonly"] = $arr_dbfields;
	}
	
	function set_masterdetail($sql_select, $key, $extra_filter=""){
		$this->masterdetail_key = $key;
		$master_foreign_id = isset($_SESSION["master_foreign_id"]) ? $_SESSION["master_foreign_id"] : 0;
		$this->masterdetail_sql = $sql_select ." WHERE ". $key ." = '$master_foreign_id' ". $extra_filter;		
	}
	
	function get_masterdetail(){
		$this->masterdetail_sql;
	}


	// Desc: save admin html control in $_SESSION after set in member array col_ctrols
	// Note1: The last parameter is optional it equals to the col_name if it is omitted
	// 		  The structure of the col_ctrls is "an array of array"
	// 		  Each control is an array that is a group of controls (e.g radio box group),
	//		  then stored as an associat array by the $col_name
	// Note2: $size=1, $delimiter="," are only used for checkbox and multiselect controls
	//		  Since there is no good way of overload a function in PHP, this seems to be
	//  	  the best option.
	function add_control($colname, $ctrl_type, $arr_kvpair, $delimiter=",", $size=1, $varname = ""){
		$varname = ($varname == "") ? $colname : $varname;
		$ctrl_group = array();
		$index = 0;

		// some parameter type validation
		if(!is_array($arr_kvpair)){ die("Error: add_control()- The 3rd paramter in function must be a key-value pair associative array.");}
		if($ctrl_type != "Radio" && $ctrl_type != "Checkbox" && $ctrl_type != "Dropdown" && $ctrl_type != "Multiselect"){ die("Error: add_control() - $ctrl_type is an undefined HTML control.");}

		foreach($arr_kvpair as $val => $text){
			switch($ctrl_type){
				// See function Note2
				case "Checkbox":
					$obj_ctrl = new $ctrl_type($varname, $val, $text, $delimiter);
					break;
				case "Multiselect":
					$obj_ctrl = new $ctrl_type($varname, $val, $text, $size, $delimiter);
					break;
				default:
					$obj_ctrl = new $ctrl_type($varname, $val, $text);
			}
			$ctrl_group[$index] = $obj_ctrl;
			$index++;
		}

		$this->col_ctrls[$colname] = $ctrl_group;
		$_SESSION["ctrls"] = serialize($this->col_ctrls);
	}

	// Desc: additonal actions to the action column
	//
	// parameters:
	// $name: name of the action displayed
	// $link: Url link
	// $link_id: data field name will be construct as part of the hyperlink
	// $extra_attr: additional attributes for <a>, such as target
	// $image: if image used the $name will be surpressed
	function add_action($name, $link, $link_id='', $extra_attr='', $image='') {
		array_push($this->actions, array($name, $link, $link_id, $extra_attr, $image) );
	}
	
	// Desc: additonal commands/items to the toolbar
	//
	// parameters:
	// $name: name of the command displayed
	// $link: Url link
	// $extra_attr: additional attributes for <a>, such as target
	// $image: if image used the $name will be surpressed
	function add_toolbar_command($name, $link, $extra_attr='', $image='') {
		array_push($this->commands, array($name, $link, $extra_attr, $image) );
	}






	// /////////////////////////////// UTITILITY FUNCTIONS /////////////////////////
	// /////////////////////////////////////////////////////////////////////////////

	// Desc: add column obj into the column objects array
	// Note: The parameters MUST match paramters passed in Column constructor.
	//		 the column type is DataField by default
	// 		 use the th css values from the cls_database class
    	//       the additonal columns are added to the END of rest of columns
	function add_column($col_name, $col_desc, $col_type = "DataField"){
		$col_obj = new C_Column($col_name, $col_desc, $col_type, $this->th_style, $this->th_class);
		$col_index = sizeof($this->col_objects);
		$this->col_objects[$col_index] = $col_obj;
	}

	// Desc: reformat the sql string because the order by clause should be at the last position in a query
	// Return: reformatted sql string with ORDER BY at the end
	function _get_reformatted_sql($query, $query_where){
		$separat = " ORDER BY";
		// get substring " ORDER BY";
		$query_orderby 	= urlencode(stristr($query, $separat));
		$query_where	= urlencode($query_where);
		if($query_orderby){
			// get substring before " ORDER BY" (credit: user contribute @ http://www.php.net/manual/en/function.strstr.php)
			$query_beforeorderby = substr($query, 0, strlen($query)-strlen (stristr ($query,$separat)));
			return $query_beforeorderby . $query_where . $query_orderby;
		}else{
			// no ORDER BY clause
			return $query . $query_where;
		}
	}

	// Desc: private function get the relative script path currently executed in, not the script referenced in the URL
	// usage of __FILE__ see(http://us2.php.net/manual/en/function.getcwd.php)
	// Note: This is not so reliable sometimes so the set_gridpath is introduced in 2.0
	function _get_filepath(){
		$str_referencepath 	= str_replace("/", "\\", strtolower(dirname($_SERVER['PHP_SELF']))) ."\\";
		$str_actualpath 	= strtolower(dirname(__FILE__));
		$arr_filepath = explode($str_referencepath, $str_actualpath);

		$filepath = isset($arr_filepath[1]) ? str_replace("\\", "/", $arr_filepath[1]) : "";
		if($filepath != "")
			$filepath .= "/";

		return $filepath;
	}

	// Desc: set _file_path of phpgrid because sometimes _get_filepath() doesn't always work
	function set_gridpath($filepath){
		$this->_file_path = $filepath;
		$_SESSION["file_path"] = $filepath;

	}

	// Desc: get $file_path value set by either function set_gridpath() or _get_filepath().
	// The later is private.
	function get_gridpath(){
		return $this->_file_path;
	}

	// Desc: get production version number
	// Return: $_ver_num
	function get_vernum(){
		return $this->_ver_num;
	}

	// Desc: get the order sorting direction image
	function get_sort_direction_img($orderby, $sortdirection){
		// get currrent ORDER BY through GET
		$cur_orderby = isset($_GET["orderby"]) ? $_GET["orderby"] : "";
		if($orderby == $cur_orderby){
			if($sortdirection == "ASC")
				return " <img src='". $this->_file_path .$this->theme ."/images/asc.gif'  border='0' /> ";
			else
				return " <img src='". $this->_file_path .$this->theme ."/images/desc.gif' border='0' /> ";
		}else{
			return "";
		}
	}

	// Desc: debug function. dump the grid objec to screen
	function debug(){
		print("<b>" . $this->get_vernum() ."</b>");
		// tree collapes/expand functions
		print("<link rel='stylesheet' href='". $this->_file_path . $this->theme ."/css/datagrid.css' type='text/css' />");
		// tree collapes/expand functions 
		print("<script language=\"JavaScript\">".
			  		"var openImg = new Image();openImg.src = \"". $this->_file_path ."images/open.gif\";".
					"var closedImg = new Image();closedImg.src = \"". $this->_file_path ."images/closed.gif\";".
					"function showBranch(branch){var objBranch = document.getElementById(branch).style;if(objBranch.display==\"block\")objBranch.display=\"none\";else objBranch.display=\"block\";}".
					"function swapFolder(img){objImg = document.getElementById(img);if(objImg.src.indexOf('". $this->_file_path ."images/closed.gif')>-1)objImg.src = openImg.src;else objImg.src = closedImg.src;}".
			  "</script>");
		print("<br /><div style='font-size:8pt;font-family:verdana;cursor:hand;cursor:pointer;' onclick=\"showBranch('branch1');swapFolder('folder1')\"><img src='". $this->_file_path ."images/open.gif' border='0' id='folder1' alt=''> phpGrid Object Dump:</div>");
		print("<pre id='branch1' style='border:1pt dotted black;padding:5pt;background:#E4EAF5;display:block'>");
		print_r($this);
		print("</pre>");
		print("<div style='font-size:8pt;font-family:verdana;cursor:hand;cursor:pointer' onclick=\"showBranch('branch2');swapFolder('folder2')\"><img src='". $this->_file_path ."images/open.gif' border='0' id='folder2' alt=''> phpGrid Session Dump:</div>");
		print("<pre id='branch2' style='border:1pt dotted black;padding:5pt;background:#E4EAF5;display:block'>");
		print("<br />SESSION ID: ". session_id() ."<br />");
		print_r($_SESSION);
		print("</pre>");

		
	}
}
?>