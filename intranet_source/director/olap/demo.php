<?
   session_start();

   $username = $HTTP_COOKIE_VARS[directoruser];
   if ($username==""){
      header("Location: ../director_login.php");
      exit;
   }

   // Solid defines for the DB
   include("defines.php");
   include("connect.php");
   $user = "admin";

   // open a connection to the database server
   $connection = pg_connect ("host=$host dbname=$db user=$user");
   $temp = 0;

   if (!$connection){
      printf("Could not open connection to database server.   Please go back to <a href=\"director_login.php\"> Director Login Page</a> and try again later!<br />");
      exit;
   }

   $user_type = $HTTP_SESSION_VARS['user_sub_type'];

   if ($user_type ==""){
        $query ="select sub_type from ccd_user where username='".$username."'";
        $result = pg_query($connection, $query) or
             die("Error in query: $query. " .  pg_last_error($connection));
        $rows = pg_num_rows($result);
        if ($rows >0){
                $row = pg_fetch_row($result, 0);
                $user_type = $row[0];
                //setcookie("user_sub_type", "$user_type",time() + 28800);
		$_SESSION['user_sub_type'] = $user_type;

        }
    }
    $path = "/upload/cubes/";
    $cubeFile = "";
//    if ($user_type <>"") $cubeFile ="/upload/cubes/".$user_type.".cube";
    if($user_type =="ENG"){
	$cubeFile = $path."ENGR.cube";
    }else if ($user_type =="EXEC"){
	$cubeFile = $path."EXEC.cube";
    }else if ($user_type =="FINA"){
	$cubeFile = $path."FINC.cube";
    }else if ($user_type =="HR"){
	$cubeFile = $path."HRSC.cube";
    }else if ($user_type =="MRKT"){
	$cubeFile = $path."MARK.cube";
    }else if ($user_type =="OPS"){
	$cubeFile = $path."OPER.cube";
    }else if ($user_type =="TECH"){
 	$cubeFile = $path."TSFS.cube";
    }
?>

<HTML>
<HEAD>
<meta http-equiv="EXPIRES"content="0">
<TITLE>ContourCube ActiveX Demo</TITLE>
<!--
 Copyright c 2001-2003 Intersoft Lab

 You have right to use, modify, reproduce, and distribute ContourCube sample
 application files in any way you find useful.

 Intersoft Lab has no warranty, obligations, or liability for any sample application files.

 This example shows how to use the ContourCube component to
 develop full-featured web-based Business Intelligence applications.

 With ContourCube users can filter categories, products, countries, etc.;
 group values in different ways moving dimensions;
 sort rows, columns and chart series by dimension and/or fact values.

 This application is the example of a web-based analyse system written in JavaScript language.
-->
</HEAD>

<!-- This script verifies if your browser supports ActiveX objects -->
<script language="javascript">
  try {
   var cc = new ActiveXObject("CCubeX.ContourCubeX");
  }
  catch(Exception) {
   cc = 0;
  }        
  if (!cc) {
   window.stop();
   window.alert('Could not create ContourCubeX object. Apparently your internet browser does not support ActiveX controls.');
  }
</script>

<BODY>

<a name="top">
<!-- First, all HTML static controls are drawn -->
<table border="0" width="100%" cellpadding="5" cellspacing="0">
<!--
<tr>
  <td width="50%"><a href="http://www.contourcomponents.com"><img border="0" src="http://www.contourcomponents.com/images/ContourComponents.GIF" align="left" width="330" height="42"></a></td>
  <td nowrap width="50%" align="right">
    <font style="font-family: arial;font-size: 13px;font-weight: bold;font-style: italic;color: #666666">Turn your application into Business Intelligence</font></td>
</tr>
-->
<tr>
 <td colspan="2" bgcolor="#DEB55A" height="2"></td>
</tr>
<tr>
 <td nowrap bgcolor="#C0C0C0" colspan="2" width="100%">
 <!--Cube Controls -->
<!--
 <select id="selCube" onchange="CubeOpen()" title="Select microcube to open">
  <option selected value="http://dspc-s16/upload/cubes/template.cube">DSPC
  <option selected value="http://www.contourcomponents.com/Cubes/Sales.cube">Sales
  <option value="http://www.contourcomponents.com/Cubes/Budget.cube">Budget
  <option value="http://www.contourcomponents.com/Cubes/Marketing.cube">Marketing
 </select>
 -->
 <img src="images/buttons/24x24/color/Save.bmp" alt="Save Microcube As" onclick="CubeSave()">
 &nbsp;
 <img src="images/buttons/24x24/color/Swap.bmp" alt="Transpose Grid" onclick="TransposeCube()">
 <img src="images/buttons/24x24/color/Expand.bmp" alt="Expand All" onclick="ExpandCube()">
 <img src="images/buttons/24x24/color/Collapse.bmp" alt="Collapse All" onclick="CollapseCube()">
 <img src="images/buttons/24x24/color/ProcH.bmp" alt="Show As Percent By Rows" onclick="PercentByRow()">
 <img src="images/buttons/24x24/color/ProcV.bmp" alt="Show As Percent By Cols" onclick="PercentByCol()">
 <img src="images/buttons/24x24/color/NoProc.bmp" alt="Cancel Percent View" onclick="CancelPercent()">
 &nbsp;
<!--
 <select id="selMainAxis" onchange="ChangeCubeAxis()" title="Main axis of the cube">
  <option value="xda_vertical" selected>Main:Vertical
  <option value="xda_horizontal">Main:Horizontal
 </select>
-->
 &nbsp;
 <select id="selFactSort" onchange="SortCubeByFact()" title="Sort row or column by fact values">
  <option value="-1" selected>No Fact Sorting
  <option value="0">Sort Column
  <option value="1">Sort Row
 </select>
 &nbsp;
 <select id="selFactScale" onchange="CubeFactScale()" title="Change fact scale">
  <option value="1" selected>Scale:1x1
  <option value=".1">Scale:1x10
  <option value=".01">Scale:1x100
  <option value=".001">Scale:1x1000
 </select>
 &nbsp;
 <img src="images/buttons/24x24/color/ToHTML.bmp" alt="Export To HTML Document" onclick="CubeExport('HTML Files|*.htm,*.html')">
 <img src="images/buttons/24x24/color/ToExcel.bmp" alt="Export To Excel Document" onclick="CubeExport('MS Excel Files|*.xls')">
 <img src="images/buttons/24x24/color/ToWord.bmp" alt="Export To Word Document" onclick="CubeExport('MS Word Files|*.doc')">
 &nbsp;
 <img src="images/buttons/24x24/color/Print.bmp" alt="Print Current View" onclick="CubePrint()">
 </td>
</tr>
<tr>
 <td colspan="2" bgcolor="#DEB55A" height="2"></td>
</tr>
<tr>
 <td colspan="2" width="100%">
 <!-- LPK Package that ContourCube needs-->
 <OBJECT CLASSID="clsid:5220cb21-c88d-11cf-b347-00aa00a28331" VIEWASTEXT>
  <PARAM NAME="LPKPath" VALUE="cx_license.lpk">
 </OBJECT>

 <!-- ContourCube control -->
 <object classid="clsid:CCA2C672-33FD-11D5-8D72-005004532BDF" id="ContourCubeX1"  CODEBASE="/upload/ActiveX/ccubex.cab#version=1,3,0,11" width="100%" height="386" VIEWASTEXT>
  <param name="DataSourceType" value="2">
 </object>
 </td>
</tr>
<tr>
 <td colspan="2" bgcolor="#DEB55A" height="2">
 </td>
</tr>
<tr>
 <td nowrap colspan="2" bgcolor="#C0C0C0">
 <!-- Chart Controls -->
   <strong>Chart options:</strong>
   &nbsp;&nbsp;
   <select id="selChartType" onchange="SwitchType()" title="Chart type">
    <option value="0" selected>3D Bar
    <option value="1">2D Bar
    <option value="2">3D Line
    <option value="3">2D Line
    <option value="4">3D Area
    <option value="5">2D Area
    <option value="6">3D Step
    <option value="7">2D Step
    <option value="8">3D Combination
    <option value="9">2D Combination
    <option value="14">Pie
    <option value="16">2D XY
   </select>
   &nbsp;&nbsp;
   <input type="checkbox" id="chkChartVisible" onclick="SwitchChart()">Visible
   &nbsp;&nbsp;
   <input type="checkbox" id="chkLegend" onclick="SwitchLegend()">Show Legend
   &nbsp;&nbsp;
   Column to chart:&nbsp;
   <select id="selChartFact">
   </select>
   &nbsp;&nbsp;
   <input type="button" value="Redraw Chart" id="btnDrawChart" onclick="DrawChart()">
 </td>
</tr>
<tr>
 <td colspan="2" bgcolor="#DEB55A" height="2"></td>
</tr>
<tr>
 <td colspan="2">

<!-- MS Chart control-->
<!-- to show / hide the chart, a layer is made in the document -->
<div id="Chart">
<object classid="clsid:3A2B370C-BA0A-11D1-B137-0000F8753F5D"
id="MSChart1" width="100%" height="450">
</object>
</div>
 </td>
</tr>
<tr>
 <td colspan="2" align="center">
  <a href="#top"><strong>Go Top<strong></a>
 </td>
</tr>
</table>

<!-- Begin of JavaScript program -->
<script language="javascript">
<!--

  CubeOpen("<? echo $cubeFile ?>");
  chkChartVisible.status=false;
  chkLegend.status=true;
  SwitchChart();
  MSChart1.DataGrid.RowCount=0;
  MSChart1.DataGrid.ColumnCount=0;
  MSChart1.ChartType=selChartType.value;
  MSChart1.ShowLegend=chkLegend.status;
  MSChart1.AllowDynamicRotation=true;
  MSChart1.Plot.View3D.Set(45, 30);

  //ContourCubeX1.MainAxis=selMainAxis.value;
  CubeFactScale();

  function WaitCube() {
   if (!ContourCubeX1.Active) {
    selChartFact.disabled=true;
    btnDrawChart.disabled=true;
    setTimeout('WaitCube()', 1000);
   }
   else {
    selChartFact.length=0;
    for (i=0;i<ContourCubeX1.FactCount;i++) {
     var Opt = document.createElement('OPTION');
     Opt.value=i;
     Opt.text=ContourCubeX1.FactName(i);
     selChartFact.add(Opt, i);
    }
    selChartFact.selectedIndex=0;
    selChartFact.disabled=false;
    btnDrawChart.disabled=false;
    window.status='©2003 Port of Wilmington, DE, Diamond State Port Corporation. All Rights Reserved';
   }
  }

  function DrawChart() {
   var i, j, DimNum, LabelLvl, FactValue, CellType, IsNULL;
   if (!ContourCubeX1.FactVisible(ContourCubeX1.FactName(selChartFact.value))) {
    window.alert('The charting column is filtered out. Please select another column.');
    return;
   }
   var mView = ContourCubeX1.CreateView (1, 1);
   mView.Transposed=ContourCubeX1.Transposed;
   mView.ViewFlags = 10;
   MSChart1.DataGrid.SetSize(mView.DimCount(1), mView.DimCount(0), mView.ColumnCount, mView.RowCount);
   MSChart1.Title.Text=ContourCubeX1.FactName(selChartFact.value);
   chkChartVisible.status=false;
   SwitchChart(); 
   for (i=0;i<mView.DimCount(0);i++) {
    DimNum = mView.DimNo(mView.DimName(0, i));
    LabelLvl = mView.DimCount(0) - i;
    for (j=0;j<mView.RowCount;j++) {
     MSChart1.DataGrid.ColumnLabel(j+1, LabelLvl) = mView.GetDimValue(DimNum, j, CellType);
    }
   }
   for (i=0;i<mView.DimCount(1);i++) {
    DimNum = mView.DimNo(mView.DimName(1, i));
    LabelLvl = mView.DimCount(1) - i;
    for (j=0;j<mView.ColumnCount;j++) {
     MSChart1.DataGrid.RowLabel(j+1, LabelLvl) = mView.GetDimValue(DimNum, j, CellType);
    }
   }
   for (i=0;i<MSChart1.Plot.Axis(1).Labels.Count;i++) {
    MSChart1.Plot.Axis(1).Labels.Item(i+1).Format='###,###,###.##';
    MSChart1.Plot.Axis(2).Labels.Item(i+1).Format='###,###,###.##';
   }
   for (i=0;i<mView.ColumnCount;i++) {
    for (j=0;j<mView.RowCount;j++) {
     FactValue=mView.GetFactValue(selChartFact.value, i, j, CellType, IsNULL);
     if (!CellType) {
      MSChart1.DataGrid.SetData(i+1, j+1, FactValue, 0);
     }
    }
   }
   chkChartVisible.status=true;
   SwitchChart();
  }

  function SwitchChart() {
   document.getElementById('Chart').style.visibility=(chkChartVisible.status)?'visible':'hidden';
  }

  function SwitchLegend() {
   MSChart1.ShowLegend=chkLegend.status;
  }

  function SwitchType() {
   MSChart1.ChartType=selChartType.value;
  }

  function CubeOpen(cFile) {
   ContourCubeX1.LoadCube(cFile);
   WaitCube();
}

  function CubeSave() {
   var Dlg = new ActiveXObject('MSComDlg.CommonDialog');
   Dlg.CancelError=false;
   Dlg.MaxFileSize=1024;
   Dlg.Filter='Contour Microcube files (*.cube)|*.cube';
   Dlg.DialogTitle='Save Microcube As';
   Dlg.ShowSave();
   if (Dlg.FileTitle!='') {
    ContourCubeX1.SaveCube(Dlg.FileName);
   }
  }

  function TransposeCube() {
   ContourCubeX1.Transposed=!ContourCubeX1.Transposed;
  }

  function ExpandCube() {
   ContourCubeX1.HDrillDownLevel=-1;
   ContourCubeX1.VDrillDownLevel=-1;
  }

  function CollapseCube() {
   ContourCubeX1.HDrillDownLevel=1;
   ContourCubeX1.VDrillDownLevel=1;
  }

  function PercentByRow() {
   ContourCubeX1.ShowAsPercent=2;
  }

  function PercentByCol() {
   ContourCubeX1.ShowAsPercent=1;
  }

  function CancelPercent() {
   ContourCubeX1.ShowAsPercent=0;
  }

  function ChangeCubeAxis() {
   ContourCubeX1.MainAxis=selMainAxis.value;

  }

  function SortCubeByFact() {
   if (selFactSort.value>=0) {
    ContourCubeX1.SortByFact(selFactSort.value, -1, -1);
   }
   else {
   ContourCubeX1.CancelFactSorting(0);
   ContourCubeX1.CancelFactSorting(1);
   }
  }

  function CubeFactScale() {
   for (i=0;i<ContourCubeX1.FactCount;i++) {
    ContourCubeX1.FactScale(ContourCubeX1.FactName(i))=selFactScale.value;
   }
  }

  function CubeExport(type) {
   var Dlg = new ActiveXObject('MSComDlg.CommonDialog');
   Dlg.CancelError=false;
   Dlg.MaxFileSize=1024;
   Dlg.Filter=type;
   Dlg.DialogTitle='Export Cube To ...';
   Dlg.ShowSave();
   if (Dlg.FileTitle!='') {
    ContourCubeX1.ExportToFile(Dlg.FileName, '', 0);
   }
  }

  function CubePrint() {
   ContourCubeX1.PrintCube(true, false);
  }
-->
</script>
<!-- End of JavaScript program -->
<BODY>
</HTML>
