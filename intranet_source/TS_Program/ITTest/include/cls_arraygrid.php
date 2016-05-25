<?php
include_once("cls_util.php");

if(!session_id()){ session_start();}
class C_ArrayGrid extends C_DataGrid
{
	var $exp_header;
	var $exp_footer;
	var $grid_data = array();
	var $_ver_num;
	var $uid;
	var $util;
	
	// #### Parent constructor MUST be called before anything else!!! #####
	function C_ArrayGrid($data){
		parent::C_DataGrid("", "", "", "", "array", $data);
		$this->_Load_array($data);
		$this->grid_data 	= isset ($_SESSION["grid_data"]) ? $_SESSION["grid_data"] : $data;
		$this->exp_header	= isset ($_SESSION["exp_header"]) ? $_SESSION["exp_header"] : "";
		$this->exp_footer	= isset ($_SESSION["exp_footer"]) ? $_SESSION["exp_footer"] : "";
		$this->_num_fields 	= isset ($this->grid_data[0]) ? sizeof($this->grid_data[0]) : 0;
		$this->_num_rows 	= sizeof($this->grid_data);
		$this->_ver_num		= "phpGrid Enterprise (v2.4)";			// Set product version number
		$this->uid		= "grid_data";	// Unique ID for multiple datagrid in a single session
		$util				= new C_Utility();
	}	
	
	
	// Desc: render grid toolbar along with the page navigation bar
	function render_toolbar(){
		$this->inlineedit_enabled = false;
		
		$toolbar = "\n\n<div id='toolbar' class='toolbar_css'>\n";
		
		$toolbar .= $this->render_page_nav_bar() ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		$toolbar .= "Total Records: <strong>". $this->_num_rows."</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
		
		if($this->get_allow_actions()){
			$this_db 		= $this->db;
			$query 			= $this->get_sql();	
			$toolbar .= "<a href='#' onclick=\"pop_up('". $this->_file_path ."action_array.php?_uid_=". $this->uid ."&a=a&pk=&pk_v=', 400, 400)\" /><img src='" . $this->_file_path ."images/theme/". $this->theme ."/new.gif' alt='[ New ]' border='0' /></a>\n";
		}
		
		if($this->inlineedit_enabled){
			$toolbar .= "<a href='javascript:document.form_inlineedit.submit();'><img src='" . $this->_file_path. "images/theme/". $this->theme ."/save.gif' alt='[ Save All ]' border='0' /></a>\n ";
		}
		
		if($this->allow_export){
			$alt_bgcolor	= urlencode($this->alt_bgcolor);	
			$toolbar .= "<a href='#' onclick=\"location.href='". $this->_file_path ."phpgrid_export.php?_uid_=". $this->uid ."&pk=&pk_v=&alt_bgcolor=$alt_bgcolor'\"><img src='" . $this->_file_path. "images/theme/". $this->theme ."/excel.gif' alt='[ Excel Export ]' border='0' /></a>\n ";	
			$toolbar .= "<a href='". $this->_file_path ."pdf_export.php?_uid_=". $this->uid ."' target='_new'><img src='" . $this->_file_path. "images/theme/". $this->theme ."/pdf.gif' alt='[ PDF Export ]' border='0' /></a>\n ";		
		}
		
		$toolbar .= "<!--a href=\"javascript:alert('Email This Page is temporarily not available in Beta.');\"><img src='" . $this->_file_path. "images/theme/". $this->theme ."/emailpage.gif' alt='[ Email This Page ]' border='0' /></a-->\n ";		
		$toolbar .= "<!--a href=\"javascript:alert('Interactive Search is temporarily not available in Beta.');\"><img src='" . $this->_file_path. "images/theme/". $this->theme ."/search.gif' alt='[ Search ]' border='0' /></a-->\n ";		
		$toolbar .= "<a href='javascript:window.print();'><img src='" . $this->_file_path. "images/theme/". $this->theme ."/print.gif' alt='[ Print ]' border='0' /></a>\n ";
	
		
		if($this->multidel_enabled){
			$orderby	= isset($_GET["orderby"]) ? $_GET["orderby"] : "";			
			$direction 	= isset($_GET["direction"]) ? $_GET["direction"] : "";
			$pg 		= isset($_GET["pg"]) ? $_GET["pg"] : "";
			$toolbar .= " <a href=\"javascript:if(confirm('Are you sure to delete selected records?')){document.form_del.submit();}\"><img src='" . $this->_file_path. "images/theme/". $this->theme ."/delete.gif' alt='[ Delete Selected ]' border='0' /></a>\n ";
		}
		
		if($this->ok_showcredit){
			$toolbar .= "<a href='http://www.phpgrid.com/grid/examples/' target='_new'><img src='" . $this->_file_path. "images/theme/". $this->theme ."/help.gif' alt='[ Online Help ]' border='0' /></a>\n ";
			$toolbar .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span align='center'><a href='http://www.phpgrid.com' target='_new'><img src='".$this->_file_path."images/phpgrid_logo.gif' border='0' alt='Powered by phpGrid' /></a></span>\n ";	
		}
		
		$toolbar .= "</div>\n\n";
		
		return $toolbar;
	}
	
	
	
	// Desc: return table header in html
	function render_header(){
		$header 	= "";
//		$this_db 	= $this->db;
//		$query_str 	= $this->get_sql();
//		$rs_header 	= $this_db->db_query($query_str ." LIMIT 1, 1");
		$rs_header 	= $this->grid_data[0];
	
		// //////////////// Grid Scroll Problem: can't make the header to sync with the body. Temporally comment out. //////////////
		$header .= "<thead id='grid_head'>";

		$header .= "<tr style='". $this->get_tr_style(). "' class='". $this->get_tr_class(). "'>";
		$header .= ($this->ok_rowindex) ? "<th style='". $this->get_th_style() ."' class='". $this->get_th_class() ."' width='1%'><a>#</a></th>" : "";		// index column
        $header .= ($this->multidel_enabled) ? "<th style='". $this->get_th_style() ."' class='". $this->get_th_class() ."' width='1%'><a>&nbsp;</a></th>" : "";		// multidel column
		$header .= ($this->is_actncol_allwd)  ? "<th style='". $this->get_th_style() ."' class='". $this->get_th_class(). "' width='1%'><a>Actions</a></th>" : "";		// action column


		for($i = 0; $i < $this->_num_fields; $i++) {
			
			// get associated column name
//			$col_name = $this_db->field_name($rs_header, $i);
			$col_name = key($rs_header);
			
			// don't render if this column is set to hide (By either column name or index)
			if(!isset($this->col_hiddens[$col_name]) && !isset($this->col_hiddens[$i])){	
				// get currrent page number through GET
				$pg_cur = isset($_GET[PAGE_NUMBER]) ? PAGE_NUMBER."=".$_GET[PAGE_NUMBER] : "";
				// get sort direction {ASC|DESC}
				$sort_direction = $this->get_sort_direction($col_name);

				$header .= "<th style='". $this->get_th_style(). "' class='". $this->get_th_class() ."'><nobr>";
				$header .= ($this->get_allow_sort()) ? "<a href='". $_SERVER['PHP_SELF'] ."?orderby=". $col_name ."&direction=$sort_direction&$pg_cur'>":""; // style='". $this->get_th_style() ."' class='". $this->get_th_class(). "'>" : "";
				// get column title by name or index
				if(isset($this->col_titles[$col_name]))
					$header .= $this->col_titles[$col_name]; 		
				elseif(isset($this->col_titles[$i]))
					$header .= $this->col_titles[$i];
				else
					$header .= $col_name;
					
				$header .=	($this->get_allow_sort()) ? $this->get_sort_direction_img($col_name, $sort_direction)."</a></nobr></th>" : "</nobr></th>";
			}
			next($rs_header);
		}



        // render column header - empty() returns FALSE if var has a non-empty and non-zero value
		if(!empty($this->col_objects)){
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
	
	




	
	// NOTE: ALL $cols[$i] MUST BE CONVERTED TO $cols[$col_name];
	// Desc: return tablel body in html
	function render_body(){
		$this->inlineedit_enabled = false;
//		$this_db 	= $this->db;
		$sql_key 	= $this->get_sql_key();
//		$query_str 	= $this->get_sql();
		// get record by page syntax: "SELECT * FROM ".$table." LIMIT ".$start.", ".$limit;
//		$rs_body = $this_db->db_query($query_str ." LIMIT ". intval($this->get_page_size() * ($this->page_current-1)) .", ". $this->get_page_size());
		$rs_body = $this->SortArray($this->grid_data, $this->get_sorted_by(), $this->get_sort_direction()); 
		$rs_body = array_slice($rs_body, intval($this->get_page_size() * ($this->page_current-1)), $this->get_page_size());


		$body 	   = "";
		$row_index = 0;
		$row_count = 0;
		$cols 	   = array();
		
		// //////////////// Grid Scroll Problem: can't make the header to sync with the body. Temporally comment out. //////////////
		$body .= "<tbody id='grid_body'>";// class='grid_body_scroll'>";

//		while($cols = $this_db->fetch_array($rs_body)){
		for($j=0; $j<sizeof($rs_body); $j++){			
			$cols = $rs_body[$j];
			// calculate current array index
			$array_index = intval($this->get_page_size() * ($this->page_current-1))+$j;
			
			// ////////////////////// TR //////////////////////////
			$body .= "<tr bgcolor='". $this->_get_row_bgcolor($row_index) ."' style='". $this->get_tr_style(). "' class='". $this->get_tr_class(). "' ".
						  "onmouseover=\"". $this->get_onmouseover_color() ."\" onmouseout=\"this.style.background='". $this->_get_row_bgcolor($row_index) ."'\">";
			// ////////////////////// TD //////////////////////////
			$body .= ($this->ok_rowindex) ? "<td valign='middle' align='center' style='color:gray;font-size:7pt'>". intval($row_index+1) ."</td>" : "";	// index column

            // multidel checkbox
//	       $body .= ($this->multidel_enabled) ? "<td align='center'><input type='checkbox' name='___". $this->sql_key ."___". $cols[$this->sql_key] ."' value='". $cols[$this->sql_key] ."' /></td>" : "";
	       $body .= ($this->multidel_enabled) ? "<td align='center'><input type='checkbox' name='___index___". $array_index ."' value='". $array_index ."' /></td>" : "";

			// action column(http://aspgrid.com/manual_forms.html. a = action, q = query, k = key, k_v = key value);
			if($this->is_actncol_allwd){
			
				$body .= "<td style='font-size:8pt;font-family:verdana'><nobr>&nbsp;";
				
				if(strstr(strtoupper($this->action_type), "V")){
//					$body .= "&nbsp;<a href=\"javascript:pop_up('". $this->_file_path. "action_array.php?a=v&pk=$this->sql_key&pk_v=$cols[$sql_key]', 400, 400)\"><img src='" . $this->_file_path. "images/view_icon.gif' border='0' alt='View' /></a>&nbsp;";
					$body .= "&nbsp;<a href=\"javascript:pop_up('". $this->_file_path. "action_array.php?_uid_=". $this->uid ."&a=v&pk=". $array_index . "&pk_v=". $array_index ."', 400, 400)\"><img src='" . $this->_file_path. "images/view_icon.gif' border='0' alt='View' /></a>&nbsp;";

				}
				if(strstr(strtoupper($this->action_type), "E")){			
//					$body .= "&nbsp;<a href=\"javascript:pop_up('". $this->_file_path. "action_array.php?a=e&pk=$this->sql_key&pk_v=$cols[$sql_key]', 400, 400)\"><img src='" . $this->_file_path. "images/edit_icon.gif' border='0' alt='Edit' /></a>&nbsp;";
					$body .= "&nbsp;<a href=\"javascript:pop_up('". $this->_file_path. "action_array.php?_uid_=". $this->uid ."&a=e&pk=". $array_index . "&pk_v=". $array_index ."', 400, 400)\"><img src='" . $this->_file_path. "images/edit_icon.gif' border='0' alt='Update' /></a>&nbsp;";
				}
				if(strstr(strtoupper($this->action_type), "D")){			
//					$body .= "&nbsp;<a href=\"javascript:pop_up('". $this->_file_path. "action_array.php?a=d&pk=$this->sql_key&pk_v=$cols[$sql_key]', 400, 400)\"><img src='" . $this->_file_path. "images/delete_icon.gif' border='0' alt='Edit' /></a>&nbsp;";
					$body .= "&nbsp;<a href=\"javascript:pop_up('". $this->_file_path. "action_array.php?_uid_=". $this->uid ."&a=d&pk=". $array_index . "&pk_v=". $array_index ."', 400, 400)\"><img src='" . $this->_file_path. "images/delete_icon.gif' border='0' alt='Delete' /></a>&nbsp;";
				}
				
				$body .= "&nbsp;</nobr></td>";
			}

			$arr_temp = $rs_body[0];
			for($i = 0; $i < $this->_num_fields; $i++) {
				
				// get associated column name
//				$col_name = $this_db->field_name($rs_body, $i);
				$col_name = key($arr_temp);
				
				// obtain the text used for hyperlink after "?foo=". the default is the text of current column in that record(row)
//				$link_id = (isset($this->col_links[$col_name][1]) && $this->col_links[$col_name][1] != "") ? $this->col_links[$col_name][1] : $i;
				$link_id = (isset($this->col_links[$col_name][1]) && $this->col_links[$col_name][1] != "") ? $this->col_links[$col_name][1] : $col_name;
				// don't render if this column is set to hide (By either column name or index)
				if(!isset($this->col_hiddens[$col_name]) && !isset($this->col_hiddens[$i])){



					// get TD table for inline editing
					$body .= ($this->inlineedit_enabled) ? "<td>" : "<td style='".$this->get_col_style($i, $col_name)."'>";


					// ///////////// enable inline editing by set contentEditable to true //////////////
					// *** IMPORTANT ***: only display the RAW data in cell if inline edit is set to true
					// Note: css class cellscroll on and off are primarily used to set the height and width of the cell with scroll bar
					// 		 though other css properties can be set in it too.
					if($this->inlineedit_enabled){
//						$id_text = "___". $col_name."___". $cols[$this->sql_key];
						$id_text = "___". $col_name."___". $array_index;
//						$body .= "<div class='cellScrollOff' onfocus=\"this.className='cellScrollOn';\" contentEditable=true onblur=\"this.className='cellScrollOff';document.form_inlineedit('___". $col_name."___". $cols[$this->sql_key] ."').value=this.innerHTML;\">";// alert(document.form_inlineedit('___". $col_name."___". $cols[$this->sql_key] ."').value);\">";
						$body .= "<div class='cellScrollOff' onfocus=\"this.className='cellScrollOn';\" contentEditable=true onblur=\"this.className='cellScrollOff';document.form_inlineedit('___". $col_name."___". $array_index ."').value=this.innerHTML;\">";// alert(document.form_inlineedit('___". $col_name."___". $cols[$this->sql_key] ."').value);\">";
//						$body .= $cols[$i];
						$body .= $cols[$col_name];
						$body .= "</div>";
					}else{
						// defautl cell css style for not inline editing
						$body .= "<div class='cellScrollOff'>";
//						$body .= $this->get_col_img($i,  $col_name, $cols[$i]);
						$body .= $this->get_col_img($i,  $col_name, $cols[$col_name]);
						// replace cartridge return with html <br>
						

						if($this->ok_nl2br){
//							$body .= nl2br($this->get_col_link($i, $col_name, $cols[$i], $cols[$link_id]));
							$body .= nl2br($this->get_col_link($i, $col_name, $cols[$col_name], $cols[$link_id]));
						}else{
//							$body .= $this->get_col_link($i, $col_name, $cols[$i], $cols[$link_id]);
							$body .= nl2br($this->get_col_link($i, $col_name, $cols[$col_name], $cols[$link_id]));
						}

//						$body .= $cols[$col_name];
						$body .= "</div>";
					}
					
					// generate the hidden fields for saving data later to database
					if($this->inlineedit_enabled){
//						$this->inline_valueset .="\t<input type=\"hidden\" id=\"$id_text\" name=\"$id_text\" value=\"". $this->util->add_slashes($cols[$i]) ."\" />\n";
						$this->inline_valueset .="\t<input type=\"hidden\" id=\"$id_text\" name=\"$id_text\" value=\"". $this->$util->add_slashes($cols[$col_name]) ."\" />\n";
					}

					// sum the columns
					if($this->col_sum_enabled){
//						$this->add_col_sum($col_name, $cols[$i]);
						$this->add_col_sum($col_name, $cols[$col_name]);
					}

					// end of TD tag for inline editing
				 	$body .= "</td>";



				}// if
				next($arr_temp);
			}// for
			
			// //////////////////// render each column body after the index column above ////////////////////////////
			// Note: empty() returns FALSE if var has a non-empty and non-zero value
			if(!empty($this->col_objects)){
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
//		$this_db 	= $this->db;
//		$sql_key 	= $this->get_sql_key();
//		$query_str 	= $this->get_sql();
		// get record by page syntax: "SELECT * FROM ".$table." LIMIT ".$start.", ".$limit;
//		$rs_body = $this_db->db_query($query_str ." LIMIT 1, 1");
		$rs_footer = $this->grid_data[0];
		$footer = "";
		$row_index = 0;
		$row_count = 0;

		$footer .= "<tfoot id='grid_foot'>";// class='grid_foot_scroll'>";

//		while($cols = $this_db->fetch_array($rs_body)){
		for($i=0; $i < $this->_num_fields; $i++){
			// ////////////////////// TR //////////////////////////
			$footer .= "<tr bgcolor='' style='padding:3px;border:2px black solid;'>".
			// ////////////////////// TD //////////////////////////
			$footer .= ($this->ok_rowindex) ? "<td></td>" : "";	// index column

            // multidel checkbox
            $footer .= ($this->multidel_enabled) ? "<td></td>" : "";

			// action column(http://aspgrid.com/manual_forms.html. a = action, q = query, k = key, k_v = key value);
			$footer .= ($this->is_actncol_allwd) ? "<td></td>" : "";


			for($i = 0; $i < $this->_num_fields; $i++) {
//				$col_name = $this_db->field_name($rs_body, $i);
				$col_name = key($rs_footer);
				// don't render if this column is set to hide (By either column name or index)
				if(!isset($this->col_hiddens[$col_name]) && !isset($this->col_hiddens[$i])){
				
					if(isset($this->col_sums[$col_name])){
						// display the total
						$sum = $this->col_sums[$col_name];
						$sum = (strstr($sum, '.') > 0) ? number_format($sum, 2) : number_format($sum);
						$footer .= isset($this->col_sums[$col_name]) ? "<td style='".$this->get_col_style($i, $col_name)."'><strong>".$sum."</strong></td>" : "<td></td>";				
					}else{
						$footer .= "<td></td>";
					}
					
					
				}
				next($rs_footer);
				
			}// for
			
			
		
			// //////////////////// render each column body after the index column above ////////////////////////////
			// Note: empty() returns FALSE if var has a non-empty and non-zero value
			if(!empty($this->col_objects)){
				for($i = 0; $i < sizeof($this->col_objects); $i++){
					$footer .= "<td></td>";
				}
			}
			
			$footer .= "</tr>\n";
	
			$row_index++;
			$row_count++;
		}// for

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
			$formdel_close .= "<input name='home_url' value='". $_SERVER['PHP_SELF'] ."' type='hidden' />";
			$formdel_close .= "<input name='orderby' 	value='$orderby' type='hidden' />";
			$formdel_close .= "<input name='direction' value='$direction' type='hidden' />";
			$formdel_close .= "<input name='pg' 		value='$pg' type='hidden' />";
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

		$page_nav_bar .= "<span style='font-size:8pt;font-family:verdana,arial'>";
		
		// display First & Previous page buttons
		if(intval($this->page_current-1) > 0){
			$page_nav_bar .= "<a href='". $_SERVER['PHP_SELF']. "?".PAGE_NUMBER."=1&$orderby&$sort_direction'><img src='" . $this->_file_path. "images/theme/". $this->theme ."/firstpg.gif' alt='[ First Page ]' border='0' /></a> ";
			$page_nav_bar .= "<a href='". $_SERVER['PHP_SELF'] ."?".PAGE_NUMBER."=". intval($this->page_current-1) ."&$orderby&$sort_direction'><img src='" . $this->_file_path. "images/theme/". $this->theme ."/prevpg.gif' alt='[ Prev Page ]' border='0' /></a> ";
		}else{
			$page_nav_bar .= "<img src='" . $this->_file_path. "images/theme/". $this->theme ."/firstpgdim.gif' alt='[ First Page ]' border='0' /> ";
			$page_nav_bar .= "<img src='" . $this->_file_path. "images/theme/". $this->theme ."/prevpgdim.gif' alt='[ Prev Page ]' border='0' /> ";
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
		$page_nav_bar .= " Page <select name='pg' style='color:navy' onchange=\"window.location='". $_SERVER['PHP_SELF']. "?".PAGE_NUMBER."='+this.value+'&$orderby&$sort_direction'\";>";
		for($i=1,$j=1; $i <= $this->_num_rows; $i=$i+$this->page_size,$j++){
			if($j != $this->page_current)
				$page_nav_bar .= "<option value='$j'>$j</option>";
			elseif($this->_num_rows > $this->page_size)
				$page_nav_bar .= "<option value='$j' selected>$j</option>";
			else
				$page_nav_bar .= "<option value='1' selected>1</option>";		// default is 1 if # number of rows less then page size
				
		}
		$page_nav_bar .= "</select> of <strong>". ceil($this->_num_rows/$this->page_size) ."</strong> ";
		
		// display Next & Last page buttons
		if(intval($this->page_current * $this->page_size) < $this->_num_rows){
			$page_nav_bar .= "<a href='". $_SERVER['PHP_SELF']. "?".PAGE_NUMBER."=". intval($this->page_current+1) ."&$orderby&$sort_direction'><img src='" . $this->_file_path. "images/theme/". $this->theme ."/nextpg.gif' alt='[ Next Page ]' border='0' /></a> ";
			$page_nav_bar .= "<a href='". $_SERVER['PHP_SELF']. "?".PAGE_NUMBER."=". ceil(($this->_num_rows) / ($this->page_size)) ."&$orderby&$sort_direction'><img src='" . $this->_file_path. "images/theme/". $this->theme ."/lastpg.gif' alt='[ Last Page ]' border='0' /></a> ";		
		}else{
			$page_nav_bar .= "<img src='" . $this->_file_path. "images/theme/". $this->theme ."/nextpgdim.gif' alt='[ Next Page ]' border='0' /> ";			
			$page_nav_bar .= "<img src='" . $this->_file_path. "images/theme/". $this->theme ."/lastpgdim.gif' alt='[ Last Page ]' border='0' /> ";	
		}
		
		$page_nav_bar .= "</span>";
		
		return $page_nav_bar;
	
	}
	
	
	
	
	
	// Desc: finally it prints to the browser
	function display(){
		$this->inlineedit_enabled = false;
		
//		if($this->sql == ""){ die("SQL string is not defined!");}
//		if($this->sql_key == ""){ die("SQL primary key is not defined!");}
		
		// javascript pop_up is rendered when get_allow_actions() is true
		print("<link rel='stylesheet' href='". $this->_file_path ."css/datagrid.css' type='text/css' />");
		if($this->get_allow_actions()){
			print("<script language='JavaScript' type='text/javascript'>
				   function pop_up(URL, width, height)
				   {
						window.self.name = 'main';
						var remote = window.open(URL, 'popup', 'width=' + width + ',height=' + height + ',screenX=100, screenY=100, left=100, top=100, toolbar=0,location=0,directories=0,resizable=1,status=0,menubar=1,scrollbars=1');
				   }
				   </script>\n");
		}
		print("<style type='text/css'>
				    @media print {
						div.grid_body_scroll{}
					}
					@media screen {
						div.grid_body_scroll{". $this->grid_body_style ."}
					}
					@media print {
						div.cellScrollOn {height:70px; overflow: auto; border:2px inset;background-color:#ffffff;padding: 2px;background-image:url(".$this->_file_path. "images/formshadow.gif);}
						div.cellScrollOff{". $this->cell_prtstyle ."}
   					}
   					@media screen {
						div.cellScrollOn {height:70px; overflow: auto; border:2px inset;background-color:#ffffff;padding: 2px;background-image:url(". $this->_file_path ."images/formshadow.gif);}
						div.cellScrollOff{". $this->cell_style ."}
					}
			  </style>\n");
		
		// //////// Custruct QUERY STRING HERE //////////
//		$query_str = $this->get_sql() . $this->get_sorted_by();
//		$this->set_sql($query_str);
		
		print($this->exp_header);
		print("<table id='grid_top' class='". $this->grid_class ."'><tr><td>\n");
			
			print($this->render_formdel_open());	
			
			if($this->toolbar_enabled){
				print("<nobr>". $this->render_toolbar() ."</nobr>");
			}			
				
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

			print($this->render_formdel_close());
			
		print("</table>\n");
		print($this->exp_footer);
				
		// display the "hidden" form if the inlineedit_enabled is set to true
		if($this->inlineedit_enabled){
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
		
		// store obj into the session
		$_SESSION["obj_" . $this->uid] = serialize($this);;
		
	}
	
	
	// sort mult-domensional array by key name
	// Reference: http://us3.php.net/usort
	// ********** Note: $array returned always update the Session (called in render_body) **********
	function SortArray() {
       $arguments = func_get_args();
       $array = $arguments[0];
       $code = '';
       for ($c = 1; $c < count($arguments); $c += 2) {
           if (in_array($arguments[$c + 1], array("ASC", "DESC"))) {
               $code .= 'if ($a["'.$arguments[$c].'"] != $b["'.$arguments[$c].'"]) {';
               if ($arguments[$c + 1] == "ASC") {
                   $code .= 'return ($a["'.$arguments[$c].'"] < $b["'.$arguments[$c].'"] ? -1 : 1); }';
               }
               else {
                   $code .= 'return ($a["'.$arguments[$c].'"] < $b["'.$arguments[$c].'"] ? 1 : -1); }';
               }
           }
       }
       $code .= 'return 0;';
       $compare = create_function('$a,$b', $code);
       usort($array, $compare);
       
       $_SESSION[$this->uid] = $array;
       
       return $array;
   } 
	

	// Override
	function get_sorted_by(){
		// get sorting direction
		$sortedby = isset($_GET["orderby"]) ? $_GET["orderby"] : "";
		if($sortedby == ""){
			$sortedby = $this->sorted_by;
		}		
		// then combine with the ORDER BYs
//		$sortedby = isset($_GET["orderby"])		 ? " ORDER BY ".$_GET["orderby"]. " $sortdirection" : "";
//		if($sortedby == ""){
//			$sortedby = ($this->sorted_by != "") ? " ORDER BY ".$this->sorted_by. " $sortdirection" : ""; 
//		}
		return $sortedby;
	}	
	

	// Override
	function get_sort_direction(){		
		// get currrent ORDER BY through GET
//		$cur_orderby = isset($_GET["orderby"]) ? $_GET["orderby"] : "";
		// echo $orderby . " == " . $cur_orderby ."<br>";
		// get sort direction {ASC|DESC}
//		if($cur_orderby != trim($orderby)){
//			$sort_direction = "ASC";
//		}else{
			// echo $this->sort_direction;	
			$sort_direction = isset($_GET["direction"]) ? $_GET["direction"] : $this->sort_direction;
			$sort_direction = ($sort_direction == "ASC") ? "DESC" : "ASC";
//		}
		return $sort_direction;
	}

	//Desc: programmatically add new row to array
	function add_row($newrow){
		$this->grid_data[] = $newrow;
		$_SESSION[$this->uid] =  $this->grid_data;
	}

	// Desc: set customized header message in Export. General text above everything
	function set_exp_header($exp_header){
		$this->exp_header = $exp_header;
		$_SESSION["exp_header"] = $exp_header;
	}
	
	// Desc: set customized footer message in Export. rendered below everything else.
	function set_exp_footer($exp_footer){
		$this->exp_footer = $exp_footer;
		$_SESSION["exp_footer"] = $exp_footer;
	}










	// get datagrid data
	// Reference: http://us4.php.net/manual/en/function.array-diff.php
	// It does array_diff on multidimensional arrays
	function _my_serialize(&$arr,$pos){
	  $arr = serialize($arr);
	}
	
	function _my_unserialize(&$arr,$pos){
	  $arr = unserialize($arr);
	}
	
	
	// Desc: load datagrid data. Load from session if it's the same as the current array
	function _load_array($new_array){
		if(!isset($_SESSION[$this->uid])){
			// when page first initialized
			$this->grid_data = $new_array;
			
		}else{	
			 //make a copy
			$curr_array_s = $_SESSION[$this->uid];
			$new_array_s = $new_array;
			array_walk($curr_array_s, array($this, '_my_serialize'));
			array_walk($new_array_s, array($this, '_my_serialize'));
			$diff = array_diff($curr_array_s,$new_array_s);
			array_walk($diff, array($this, '_my_unserialize'));
		
			// print_r($diff);
			// if the array is the same, which returns empty array, then load from session
			if(count($diff) == 0)
				$this->grid_data = $_SESSION[$this->uid];
			else{
				$this->grid_data = $new_array;
			}
		}
	}
	
	// Desc: set unique ID for this grid. Used for multiple datagrid in a session
	function set_grid_uid($new_uid){
		$this->uid = $new_uid;	
		$_SESSION["curr_grid_uid"] = $new_uid;
	}
	
	
	
}
?>