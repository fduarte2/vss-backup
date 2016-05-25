<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" -->
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title>Chilean Emails</title>

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
		{name : 'LINE_NO'  },
		{name : 'CUSTOMER_ID'  },
		{name : 'EMAIL_ADDR'  }
	],
	recordType : 'object'
}

var colsOption = [
     {id: 'LINE_NO' , header: "Line #" , width :100},
     {id: 'CUSTOMER_ID' , title: "*REQUIRED*", header: "CUSTOMER #" , width :60, editor:{type:'text'}},
//     {id: 'DATEENTERED' , header: "DATEENTERED" , width :80, editor:{type:'text'}},
//     {id: 'HATCH_DECK' , header: "HCH-DK" , width :60, editor : { type :"select" ,options : {'1A': '1A' ,'1B': '1B' ,'1C': '1C' ,'1D': '1D' ,'2A': '2A' ,'2B': '2B' } ,defaultText : '1A' } },
//     {id: 'ENTEREDBY' , header: "ENTEREDBY" , width :60, editor:{type:'text'}},
     {id: 'EMAIL_ADDR' , title: "*REQUIRED*", header: "Email Address" , width :370, editor:{type:'text'}}    
];

var gridOption={
	id : grid_demo_id,
	loadURL : 'Controller.php',
	saveURL : 'Controller.php',
	width: "1200",
	height: "400",
	container : 'gridbox', 
	replaceContainer : true,
	encoding : 'UTF-8', // Sigma.$encoding(), 
	dataset : dsOption ,
	columns : colsOption ,
	clickStartEdit : true ,
	defaultRecord : {'LINE_NO':"Unsaved", 
		'CUSTOMER_ID':"", 
		'EMAIL_ADDR':""},
	pageSize:100,
	//toolbarContent : 'reload | add del save | print',
	//toolbarContent : 'nav goto | pagesize | reload | print csv xls xml pdf filter chart | state',
	toolbarContent : 'reload | add del save | print filter',
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
    
	  <h2>CHILEAN EMAIL LIST BY CUSTOMER</h2>
      
    <div id="bigbox" style="margin:15px;display:!none;">
      <div id="gridbox" style="border:0px solid #cccccc;background-color:#f3f3f3;padding:5px;height:400px;width:100%;" ></div>
    </div>

  </div>
   <a href="http://dspc-s16/">Click Here to return to the Intranet.</a>
</body>
</html>