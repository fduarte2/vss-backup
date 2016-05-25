<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" -->
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title>WAREHOUSE LAYOUT</title>

<link rel="stylesheet" type="text/css" href="../../grid/gt_grid.css" />
<link rel="stylesheet" type="text/css" href="../../grid/skin/vista/skinstyle.css" />
<script type="text/javascript" src="../../grid/gt_msg_en.js"></script>
<script type="text/javascript" src="../../grid/gt_grid_all.js"></script>
<script type="text/javascript" src="../../grid/flashchart/fusioncharts/FusionCharts.js"></script>
<script type="text/javascript" src="../../grid/calendar/calendar.js"></script>
<script type="text/javascript" src="../../grid/calendar/calendar-setup.js"></script>
    
    
<script type="text/javascript" >

var grid_demo_id = "myGrid1";


var dsOption= {

	fields :[
		{name : 'ENTRY_NUM'  },
		{name : 'ARRIVAL_NUM'  },
		{name : 'FUME_STATUS'  },
		{name : 'HATCH_DECK'  },
		{name : 'RECEIVER_ID'  },
		{name : 'COMMODITY_CODE'  },
		{name : 'VARIETY'  },
		{name : 'CARGO_LABEL'  },
		{name : 'CARGO_SIZE'  },
		{name : 'SPECIAL_HANDLING'  },
		{name : 'QTY_EXPECTED'  },
		{name : 'FUME_SECTION'  },
		{name : 'FUME_ROWS'  },
		{name : 'TRANSFER_TO_WHSE'  },
		{name : 'TRANSFER_TO_BOX'  },
		{name : 'TRANSFER_TO_ROWS'  }
	],
	recordType : 'object'
}

var colsOption = [
     {id: 'ENTRY_NUM' , header: "ENTRY_NUM" , width :60},
     {id: 'ARRIVAL_NUM' , header: "ARRIVAL" , width :60, editor:{type:'text'}},
     {id: 'FUME_STATUS' , header: "FUMECODE" , width :80, editor:{type:'text'}},
     {id: 'HATCH_DECK' , header: "HCH-DK" , width :60, editor : { type :"select" ,options : {'1A': '1A' ,'1B': '1B' ,'1C': '1C' ,'1D': '1D' ,'2A': '2A' ,'2B': '2B' } ,defaultText : '1A' } },
     {id: 'RECEIVER_ID' , header: "RCVR" , width :60, editor:{type:'text'}},
     {id: 'COMMODITY_CODE' , header: "COMM" , width :50 , editor:{type:'text'}},
     {id: 'VARIETY' , header: "VARIETY" , width :100, editor : { type :'text'}},
     {id: 'CARGO_LABEL' , header: "LABEL" , width :70, editor:{type:'text'}},
     {id: 'CARGO_SIZE' , header: "SIZE" , width :70 , editor:{type:'text'}},
     {id: 'SPECIAL_HANDLING' , header: "SPL HNDL" , width :70, editor : {type:'text'}},
     {id: 'QTY_EXPECTED' , header: "EXPECTED" , width :90, editor:{type:'text'}},
     {id: 'FUME_SECTION' , header: "F-SECTION" , width :70, editor:{type:'text'}},
     {id: 'FUME_ROWS' , header: "F-ROWS" , width :100, editor:{type:'text'}},
     {id: 'TRANSFER_TO_WHSE' , header: "XFR WHSE" , width :70 , editor:{type:'text'}},
     {id: 'TRANSFER_TO_BOX' , header: "XFR BOX" , width :70, editor:{type:'text'}},
     {id: 'TRANSFER_TO_ROWS' , header: "XFR ROWS" , width :70, editor:{type:'text'}}    
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
	defaultRecord : {'ENTRY_NUM':0, 'ARRIVAL_NUM':"4321", 'FUME_STATUS':"PRE-FUMED", 'HATCH_DECK':"2A", 'RECEIVER_ID':1, 'COMMODITY_CODE':1, 'VARIETY':"NONE", 'CARGO_LABEL':"NONE", 'CARGO_SIZE': "NONE", 'SPECIAL_HANDLING': "NONE", 'QTY_EXPECTED':0, 'FUME_SECTION': "1", 'FUME_ROWS':"1", 'TRANSFER_TO_WHSE':"C", 'TRANSFER_TO_BOX':"5", 'TRANSFER_TO_ROWS':"100-200"},
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
    
	  <h2>WAREHOUSE LAYOUT</h2>
      
    <div id="bigbox" style="margin:15px;display:!none;">
      <div id="gridbox" style="border:0px solid #cccccc;background-color:#f3f3f3;padding:5px;height:200px;width:700px;" ></div>
    </div>

  </div>
   <a href="http://dspc-s16/">Click Here to return to the Intranet.</a>
</body>
</html>