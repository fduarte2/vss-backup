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
		{name : 'ROW_NUM'  },
		{name : 'CONTRACTID'  },
		{name : 'DATEENTERED'  },
		{name : 'ENTEREDBY'  },
		{name : 'CUSTOMERID'  },
		{name : 'COMMODITYCODE'  },
		{name : 'RATEPRIORITY'  },
		{name : 'FRSHIPPINGLINE'  },
		{name : 'TOSHIPPINGLINE'  },
		{name : 'ARRIVAL_NUMBER'  },
		{name : 'ARRIVALTYPE'  },
		{name : 'FREEDAYS'  },
		{name : 'WEEKENDS'  },
		{name : 'HOLIDAYS'  },
		{name : 'BILLDURATION'  },
		{name : 'BILLDURATIONUNIT'  },
		{name : 'RATESTARTDATE'  },
		{name : 'RATE'  },
		{name : 'SERVICECODE'  },
		{name : 'UNIT'  },
		{name : 'STACKING'  },
		{name : 'WAREHOUSE'  },
		{name : 'BOX'  },
		{name : 'BILLTOCUSTOMER'  },
		{name : 'XFRDAYCREDIT'  }
	],
	recordType : 'object'
}

var colsOption = [
     {id: 'ROW_NUM' , header: "ROW_NUM" , width :60},
     {id: 'CONTRACTID' , header: "CONTRACT" , width :60, editor:{type:'text'}},
//     {id: 'DATEENTERED' , header: "DATEENTERED" , width :80, editor:{type:'text'}},
//     {id: 'HATCH_DECK' , header: "HCH-DK" , width :60, editor : { type :"select" ,options : {'1A': '1A' ,'1B': '1B' ,'1C': '1C' ,'1D': '1D' ,'2A': '2A' ,'2B': '2B' } ,defaultText : '1A' } },
//     {id: 'ENTEREDBY' , header: "ENTEREDBY" , width :60, editor:{type:'text'}},
     {id: 'CUSTOMERID' , header: "CUST" , width :50 , editor:{type:'text'}},
     {id: 'COMMODITYCODE' , header: "COMM" , width :100, editor : { type :'text'}},
     {id: 'RATEPRIORITY' , header: "PRIORITY" , width :70, editor:{type:'text'}},
     {id: 'FRSHIPPINGLINE' , header: "FROM LINE" , width :70 , editor:{type:'text'}},
     {id: 'TOSHIPPINGLINE' , header: "TO LINE" , width :70, editor : {type:'text'}},
     {id: 'ARRIVAL_NUMBER' , header: "LR" , width :90, editor:{type:'text'}},
     {id: 'ARRIVALTYPE' , header: "ARRIVALTYPE" , width :70, editor:{type:'text'}},
     {id: 'FREEDAYS' , header: "FREEDAYS" , width :100, editor:{type:'text'}},
     {id: 'WEEKENDS' , header: "WEEKENDS" , width :70 , editor:{type:'text'}},
     {id: 'HOLIDAYS' , header: "HOLIDAYS" , width :70, editor:{type:'text'}},
     {id: 'BILLDURATION' , header: "BILLDURATION" , width :70, editor:{type:'text'}},
     {id: 'BILLDURATIONUNIT' , header: "BILLDURATIONUNIT" , width :70, editor:{type:'text'}},
     {id: 'RATESTARTDATE' , header: "RATESTARTDATE" , width :70, editor:{type:'text'}},
     {id: 'RATE' , header: "RATE" , width :70, editor:{type:'text'}},
     {id: 'SERVICECODE' , header: "SERVICECODE" , width :70, editor:{type:'text'}},
     {id: 'UNIT' , header: "UNIT" , width :70, editor:{type:'text'}},
     {id: 'STACKING' , header: "STACKING" , width :70, editor:{type:'text'}},
     {id: 'WAREHOUSE' , header: "WAREHOUSE" , width :70, editor:{type:'text'}},
     {id: 'BOX' , header: "BOX" , width :70, editor:{type:'text'}},
     {id: 'BILLTOCUSTOMER' , header: "BILLTOCUSTOMER" , width :70, editor:{type:'text'}},
     {id: 'XFRDAYCREDIT' , header: "XFRDAYCREDIT" , width :70, editor:{type:'text'}}    
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
	defaultRecord : {'ROW_NUM':"Unsaved", 
		'CONTRACTID':"0", 
		'CUSTOMERID':"", 
		'COMMODITYCODE':"", 
		'RATEPRIORITY':0, 
		'FRSHIPPINGLINE':"", 
		'TOSHIPPINGLINE':"", 
		'ARRIVAL_NUMBER':"", 
		'ARRIVALTYPE': "", 
		'FREEDAYS':0, 
		'WEEKENDS':"N", 
		'HOLIDAYS': "N", 
		'BILLDURATION':0, 
		'BILLDURATIONUNIT':"DAY", 
		'RATESTARTDATE':0, 
		'RATE':"0.00", 
		'SERVICECODE':0, 
		'UNIT':"", 
		'STACKING':"", 
		'WAREHOUSE':"", 
		'BOX':"", 
		'BILLTOCUSTOMER':"", 
		'XFRDAYCREDIT':"N"},
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
    
	  <h2>STORAGE RATE TABLE</h2>
      
    <div id="bigbox" style="margin:15px;display:!none;">
      <div id="gridbox" style="border:0px solid #cccccc;background-color:#f3f3f3;padding:5px;height:200px;width:700px;" ></div>
    </div>

  </div>
   <a href="http://dspc-s16/">Click Here to return to the Intranet.</a>
</body>
</html>