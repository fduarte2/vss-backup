<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" !-->
<?
	include("pow_session.php");
   $title = "Temperature Monitor WHSE/BOX Control Status";

   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
     	printf("Error logging on to the Oracle Server: ");
      	printf(ora_errorcode($conn));
     	printf("</body></html>");
       	exit;
   }
   $cursor = ora_open($conn);

	$user = $userdata['username'];

	$submit = $HTTP_POST_VARS['submit'];
	$whse = $HTTP_POST_VARS['whse'];
	$box = $HTTP_POST_VARS['box'];
	$control = $HTTP_POST_VARS['control'];

	if($submit == "submit"){
		alter_control($whse, $box, $control, $user, $cursor);
	}

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<!--<title>Temperature Monitor WHSE/BOX Control Status</title> !-->
<!--
<link rel="stylesheet" type="text/css" href="../../grid/gt_grid.css" />
<link rel="stylesheet" type="text/css" href="../../grid/skin/vista/skinstyle.css" />
<script type="text/javascript" src="../../grid/gt_msg_en.js"></script>
<script type="text/javascript" src="../../grid/gt_grid_all.js"></script>
<script type="text/javascript" src="../../grid/flashchart/fusioncharts/FusionCharts.js"></script>
<script type="text/javascript" src="../../grid/calendar/calendar.js"></script>
<script type="text/javascript" src="../../grid/calendar/calendar-setup.js"></script>
!-->  
<link rel="stylesheet" type="text/css" href="/functions/phpsigma/grid/gt_grid.css" />
<link rel="stylesheet" type="text/css" href="/functions/phpsigma/grid/skin/vista/skinstyle.css" />
<script type="text/javascript" src="/functions/phpsigma/grid/gt_msg_en.js"></script>
<script type="text/javascript" src="/functions/phpsigma/grid/gt_grid_all.js"></script>
<script type="text/javascript" src="/functions/phpsigma/grid/flashchart/fusioncharts/FusionCharts.js"></script>
<script type="text/javascript" src="/functions/phpsigma/grid/calendar/calendar.js"></script>
<script type="text/javascript" src="/functions/phpsigma/grid/calendar/calendar-setup.js"></script>

	
<script type="text/javascript" >

var grid_demo_id = "myGrid1";


var dsOption= {

	fields :[
		{name : 'WHSE'  },
		{name : 'BOX'  },
		{name : 'TEMPERATURE_DISPLAY'  },
		{name : 'DATE_CHANGED'  },
		{name : 'LAST_CHANGE_BY'  }
	],
	recordType : 'object'
}

var colsOption = [
     {id: 'WHSE' , header: "WAREHOUSE" , width :100},
//     {id: 'DATEENTERED' , header: "DATEENTERED" , width :80, editor:{type:'text'}},
//     {id: 'HATCH_DECK' , header: "HCH-DK" , width :60, editor : { type :"select" ,options : {'1A': '1A' ,'1B': '1B' ,'1C': '1C' ,'1D': '1D' ,'2A': '2A' ,'2B': '2B' } ,defaultText : '1A' } },
//     {id: 'ENTEREDBY' , header: "ENTEREDBY" , width :60, editor:{type:'text'}},
     {id: 'BOX' , header: "BOX" , width :100},
     {id: 'TEMPERATURE_DISPLAY' , header: "DSPC Control" , width :180},
     {id: 'DATE_CHANGED' , header: "Last Changed On" , width :120},
     {id: 'LAST_CHANGE_BY' , header: "Last Changed By" , width :180}    
];

var gridOption={
	id : grid_demo_id,
	loadURL : 'Controller.php',
	saveURL : 'Controller.php',
	width: "680",
	height: "400",
	container : 'gridbox', 
	replaceContainer : true,
	encoding : 'UTF-8', // Sigma.$encoding(), 
	dataset : dsOption ,
	columns : colsOption ,
	clickStartEdit : true ,
//	defaultRecord : {'CONTRACTID':"Unsaved", 
//		'DESCRIPTION':"", 
//		'END_DATE':""},
	pageSize:100,
	toolbarContent : 'reload | print',
	//toolbarContent : 'nav goto | pagesize | reload | print csv xls xml pdf filter chart | state',
	// toolbarContent : 'reload | add del save | print filter',
	skin : 'vista',
       resizable : true
	//showGridMenu : true,
	//groupable : true
};


var mygrid=new Sigma.Grid( gridOption );
Sigma.Util.onLoad(function(){mygrid.render()});


//////////////////////////////////////////////////////////





</script>
</head>
<body>

<div id="page-container">
   
  <div id="header">
  </div>

  <div id="content">
    
	  <h2>Temperature Monitor WHSE/BOX Control Status</h2>
      
    <div id="bigbox" style="margin:15px;display:!none;">
      <div id="gridbox" style="border:0px solid #cccccc;background-color:#f3f3f3;padding:5px;height:400px;width:100%;" ></div>
    </div>

  </div>

<table border=0 width="100%" cellspacing=1 cellpadding=1 bgcolor=#ffffff>
<form name="alter" action="index.php" method="post">
	<tr>
		<td width="20%"><font size="2" face="Verdana">WHSE:&nbsp;&nbsp;<select name="whse">
									<option value="">&nbsp;</option>
<?
	$sql = "SELECT DISTINCT WHSE FROM WAREHOUSE_LOCATION ORDER BY WHSE";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
									<option value="<? echo $row['WHSE']; ?>"><? echo $row['WHSE']; ?></option>
<?
	}
?>						</select></td>
		<td width="20%"><font size="2" face="Verdana">BOX:&nbsp;&nbsp;<select name="box">
									<option value="">&nbsp;</option>
<?
	$sql = "SELECT DISTINCT BOX FROM WAREHOUSE_LOCATION ORDER BY BOX";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
									<option value="<? echo $row['BOX']; ?>"><? echo $row['BOX']; ?></option>
<?
	}
?>						</select></td>
		<td><font size="2" face="Verdana">Set DSPC Control As:&nbsp;&nbsp;<select name="control">
									<option value="">&nbsp;</option>
									<option value="Y">Y</option>
									<option value="N">N</option>
						</select></td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" name="submit" value="submit"></td>
	</tr>
</form>
</table>
   
   <a href="http://dspc-s16/">Click Here to return to the Intranet.</a>
</body>
</html>

<?

function alter_control($whse, $box, $control, $user, $cursor){

	if($whse == "" || $box == "" || $control == ""){
		echo "<font color=\"#FF0000\">All 3 boxes must have a selection to make an update</font>";
		return;
	}

	$sql = "SELECT COUNT(*) THE_COUNT FROM WAREHOUSE_LOCATION WHERE WHSE = '".$whse."' AND BOX = '".$box."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	if($row['THE_COUNT'] == 0){
		echo "<font color=\"#FF0000\">Selected WHSE/BOX combo does not exist.</font>";
		return;
	}

	$sql = "UPDATE WAREHOUSE_LOCATION SET TEMPERATURE_DISPLAY = '".$control."', DATE_CHANGED = SYSDATE, LAST_CHANGE_BY = '".$user."' WHERE WHSE = '".$whse."' AND BOX = '".$box."'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);

	echo "<font color=\"#0000FF\">Update made.</font>";
	return;

}