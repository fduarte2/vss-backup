<?php
include_once("cls_db.php");
include_once("cls_datagrid.php");
include_once("cls_arraygrid.php");
require("../fpdf/fpdf.php");
require("../adodb/adodb.inc.php");

class PDF_MC_Table extends FPDF
{
	var $widths;
	var $aligns;
	
	function SetWidths($w)
	{
	    //Set the array of column widths
	    $this->widths=$w;
	}
	
	function SetAligns($a)
	{
	    //Set the array of column alignments
	    $this->aligns=$a;
	}
	
	
	function Row($data, $is_header=false)
	{
	    //Calculate the height of the row
	    $nb=0;
	    $i=0;
	    $j=0;
	    
	//    for($i=0;$i<count($data);$i++)
	    foreach($data as $key => $value){
	        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$key]));
	        $i++;
	      }
	    $h=5*$nb;
	    //Issue a page break first if needed
	    $this->CheckPageBreak($h);
	    //Draw the cells of the row
	//    for($i=0;$i<count($data);$i++)
	    foreach($data as $key => $value)
	    {
	        $w=$this->widths[$j];
	        $a=isset($this->aligns[$j]) ? $this->aligns[$j] : 'L';
	        //Save the current position
	        $x=$this->GetX();
	        $y=$this->GetY();
	        //Draw the border
	        $this->Rect($x,$y,$w,$h);
	        //Print the text
	       	if($is_header){
		        $this->SetFont('Arial','B',10);
	    		$this->MultiCell($w,5,$key,0,'C');
	    	}else{
	    	 	$this->SetFont('Arial','',8);
	    		$this->MultiCell($w,5,$data[$key],0,$a);
	        }
	        //Put the position to the right of the cell
	        $this->SetXY($x+$w,$y);
	        $j++;
	    }
	    //Go to the next line
	    $this->Ln($h);
	}
	
	
	function CheckPageBreak($h)
	{
	    //If the height h would cause an overflow, add a new page immediately
	    if($this->GetY()+$h>$this->PageBreakTrigger)
	        $this->AddPage($this->CurOrientation);
	}
	
	function NbLines($w,$txt)
	{
	    //Computes the number of lines a MultiCell of width w will take
	    $cw=&$this->CurrentFont['cw'];
	    if($w==0)
	        $w=$this->w-$this->rMargin-$this->x;
	    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	    $s=str_replace("\r",'',$txt);
	    $nb=strlen($s);
	    if($nb>0 and $s[$nb-1]=="\n")
	        $nb--;
	    $sep=-1;
	    $i=0;
	    $j=0;
	    $l=0;
	    $nl=1;
	    while($i<$nb)
	    {
	        $c=$s[$i];
	        if($c=="\n")
	        {
	            $i++;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	            continue;
	        }
	        if($c==' ')
	            $sep=$i;
	        $l+=$cw[$c];
	        if($l>$wmax)
	        {
	            if($sep==-1)
	            {
	                if($i==$j)
	                    $i++;
	            }
	            else
	                $i=$sep+1;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	        }
	        else
	            $i++;
	    }
	    return $nl;
	}
	
	
	// Desc: fix the bug in mysql array which returns both index and associate array
	function clean_array($old_arr){
		$row_cleaned = array();
		$array_cleaned = array();
		$temp = array();

		if($_SESSION["dbType"] == "mysql" || $_SESSION["dbType"] == "ibase"){
	
			// save header information
			foreach($old_arr[0] as $key => $value){
				if(!is_numeric($key)){
					$temp[] = $key;	
				}
			}
			
			// save body
			for($i = 0; $i < sizeof($old_arr); $i++){
				for($j = 0; $j < sizeof($old_arr[0]); $j++){
					if(isset($old_arr[$i][$j])){
						$row_cleaned[] = $old_arr[$i][$j];
					} 
				}	
				$array_cleaned[] = $row_cleaned;	
				$row_cleaned = array();	
			}
			
			$array_cleaned[0] = $temp;	
			return $array_cleaned;
			
		// do nothing, return the old array 
		}else{
			return $old_arr;
		}
	}
}





// =============================== execution starts here ================================
if(!session_id()){ session_start();}

if($_SESSION["DATASOURCE"] == "array"){

	$uid = $_GET["_uid_"];
	// obtain datagrid object via session and reset some properties before export
	$dg = unserialize($_SESSION["obj_". $uid]);
	$grid_data = $dg -> grid_data;
//	$grid_data = $_SESSION[$uid];

	// Export needs to match the datagrid displayed
	$arr_header = array();
	foreach($grid_data[0] as $key => $value){
		if(!isset($dg->col_hiddens[$key])){						// don't show hidden ones
			if(isset($dg->col_titles[$key])){
				$arr_header[$dg->col_titles[$key]] = $value; 	// friendly name		
			}else{
				$arr_header[$key] = $value;
			}
		}
	}		
	$numofcols = sizeof($arr_header);
	$numofrows = sizeof($grid_data);
	
	$pdf=new PDF_MC_Table();
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);
	
	// determine column width
	$cell_wid = floor(195/$numofcols);
	$arr_wid = array();
	
	// save column width in arrary to get passed in SetWidths
	for($i=0; $i<$numofcols; $i++)
		$arr_wid[$i] = $cell_wid; 
	$pdf->SetWidths($arr_wid);
	
	
	
	
	
	// ----------- print customized header on top of everything else -------
	$exp_header = isset($_SESSION["exp_header"]) ? $_SESSION["exp_header"] : "";
	$pdf->Cell(0, 0, strip_tags($exp_header), 0, 2);
	$pdf->Ln(5);
	
	
	
	
	// ------------------------------ print header --------------------------
	$pdf->Row($arr_header, true);
	
	
	
	
	// -------------------------------- print rows --------------------------
	$arr_body = array();
	for($i=0; $i<$numofrows; $i++){
		foreach($grid_data[$i] as $key => $value){
			if(!isset($dg->col_hiddens[$key])){	
				$arr_body[$key] = $value;
			}
		}
		$pdf->Row($arr_body);
	}

	    
	// ---------- print customized footer on top of everything else ---------
	$pdf->Ln(5);
	$exp_footer = isset($_SESSION["exp_footer"]) ? $_SESSION["exp_footer"] : "";
	$pdf->Cell(0, 0, strip_tags($exp_footer), 0, 2);
	
	$pdf->Output();
	$pdf = NULL;
		
}else{


	$hostName 		= isset($_SESSION["hostName"]) ? $_SESSION["hostName"] : ""; 
	$userName 		= isset($_SESSION["userName"]) ? $_SESSION["userName"] : ""; 
	$password 		= isset($_SESSION["password"]) ? $_SESSION["password"] : ""; 
	$databaseName 	= isset($_SESSION["databaseName"]) ? $_SESSION["databaseName"] : "";  
	$dbType		 	= isset($_SESSION["dbType"]) ? $_SESSION["dbType"] : "";
	$query 			= isset($_SESSION["sql"]) ? $_SESSION["sql"] : ""; 
	$arr_grid = array();
	
	$dg = new C_DataGrid($hostName, $userName, $password, $databaseName, $dbType);
	$dg -> set_sql			($query);
	$dg -> set_allow_sort	(false);
	$dg -> set_alt_bgcolor	("white, lightcyan");
	$grid_data = $dg->db->db_query($query);
 	$query_result = $dg->db->db_query($query);
	 
	 // dump into two dimensional array
    while($row = $dg->db->fetch_array($query_result)){
		$arr_grid[] = $row;
    }  
    
   	$pdf=new PDF_MC_Table();

	// must clean array before passing it to pdf object to remove redundent columns in mySql
	// other type of db seems to be OK
    $arr_grid = $pdf->clean_array($arr_grid);   
	$numofcols = sizeof($arr_grid[0]);
	$numofrows = sizeof($arr_grid);
	
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);
	
	// determine column width
	$cell_wid = floor(195/$numofcols);
	$arr_wid = array();
	
	// save column width in arrary to get passed in SetWidths
	for($i=0;$i< $numofcols;$i++)
		$arr_wid[$i] = $cell_wid; 
	$pdf->SetWidths($arr_wid);
	
	// ------------------------------ print header --------------------------
	// don't print header for mysql (it's saved as the first row in clean_array function)
	if($_SESSION["dbType"] != "mysql" || $_SESSION["dbType"] != "ibase"){
		$pdf->Row($arr_grid[0], true);	
	}

	// -------------------------------- print rows --------------------------
	for($i=0;$i<$numofrows;$i++)
	    $pdf->Row($arr_grid[$i]);
	    
	$pdf->Output();
	$pdf = NULL;

}
?>