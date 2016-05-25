VERSION 5.00
Object = "{CCA2C66D-33FD-11D5-8D72-005004532BDF}#1.3#0"; "ccubex.ocx"
Begin VB.Form Form1 
   Caption         =   "Form1"
   ClientHeight    =   4170
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   8910
   Icon            =   "Form1.frx":0000
   ScaleHeight     =   4170
   ScaleWidth      =   8910
   Begin CCubeX.ContourCubeX ContourCubeX1 
      Height          =   2415
      Left            =   240
      TabIndex        =   0
      Top             =   240
      Width           =   3735
      BackColor       =   14215660
      Enabled         =   -1  'True
      MainAxis        =   1
      DataSourceType  =   0
      ConnectionString=   ""
      SQL             =   ""
      PreGrouping     =   -1  'True
      Active          =   0   'False
      HDrillDownLevel =   1
      VDrillDownLevel =   1
      Transposed      =   0   'False
      SuppressZeroRows=   0   'False
      SuppressZeroCols=   0   'False
      ViewFlags       =   0
      BorderStyle     =   1
      AllowInactiveDimArea=   -1  'True
      AllowFilter     =   -1  'True
      AllowExpand     =   -1  'True
      AllowPivot      =   -1  'True
      AllowTitle      =   -1  'True
      ShowAsPercent   =   0
      TotalsString    =   ""
      CubeTitle       =   ""
      TitleAlign      =   2
      TitleBkColor    =   14898176
      DimBkColor      =   14215660
      DimTitleBkColor =   14898176
      DimTitleInactiveBkColor=   10070188
      DimFilterBkColor=   14215660
      InactiveDimAreaBkColor=   14215660
      HeadingBkColor  =   14215660
      DataGridColor   =   14215660
      DataBkColor     =   16777215
      TotalBkColor    =   14679807
      GrandTotalBkColor=   14679807
      BeginProperty TitleFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Arial"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty DimFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty DimTitleFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Arial"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty DimFilterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty HeadingFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty DataFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty TotalFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty GrandTotalFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      AutoSize        =   0   'False
      Object.Visible         =   -1  'True
      MousePointer    =   0
      TitleForeColor  =   16777215
      DimForeColor    =   0
      DimTitleForeColor=   16777215
      DimFilterForeColor=   0
      HeadingForeColor=   0
      DataForeColor   =   0
      TotalForeColor  =   -2147483640
      GrandTotalForeColor=   -2147483640
      UnusedDataAreaColor=   -2147483643
      MainAxisDim     =   ""
      DimTitleDragBkColor=   32768
      FactsCaption    =   "Facts"
      ShowFactsBitmap =   -1  'True
      ADOCursorLocation=   2
      AutoRefreshView =   0   'False
      FPErrString     =   "FPErr"
      NULLValueString =   ""
      NonExistentValueString=   ""
      DefaultFactFormat=   "###,###,###,###,###,##0.00"
      AllowFactFilter =   -1  'True
      VERSION_NO      =   2
      FIELDS_SETTINGS =   $"Form1.frx":000C
   End
End
Attribute VB_Name = "Form1"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
' Copyright c 2001,2002 Intersoft Lab
Private load_with_layout As Boolean

'Private Const CONS = _
'  "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=C:\Program Files\Microsoft Visual Studio\VB98\nwind.mdb;Persist Security Info=False"

'Private Const CONS = "Provider=msdaora;Data Source=BNITEST;User Id=SAG_OWNER;Password=BNITEST238"
Private Const CONS = "Provider=msdaora;Data Source=BNI;User Id=SAG_OWNER;Password=SAG"
'Private Const CONS = "Provider=msdaora;Data Source=PROD_LIVE;User Id=APPS;Password=APPS"
'Private Const CONS = "DSN=data_warehouse;UID=admin;PWD="
'Private Const CONS = "Driver={PostgreSQL};SERVER=172.22.15.70;Database=data_warehouse;UID=admin;PWD="

Private Sub Form_Load()

 

Dim dept_name(7) As String
Dim dept_code(7) As String
Dim SQL   As String

Dim year As String
Dim month As String
Dim month_num As String
Dim day As String
Dim start_date As String

Dim today() As String

'today() = Split(CStr(Format(Now, "mm/dd/yyyy")), "/")
today() = Split(CStr(Format(Now, "dd-mmm-mm-yyyy")), "-")
 
month = today(1)
day = today(0)
month_num = today(2)
year = today(3)
 
'start_date = month & "/01/" & year
start_date = "01-" & month & "-" & year

'SQL = "select subst_vals(w.warehouse,'','Other',w.warehouse) as warehouse, subst_vals(b.box,'','N/A',b.warehouse||b.box) as box, subst_vals(l.box,'','N','Y') as leased,  substr(a.commodity_name, 0, 20) as commodity, substr(a.customer_name, 0, 20) as customer, substr(a.vessel_name, 0, 20) as vessel, substr(a.cargo_mark, 0, 20) as mark, a.* from " _
'& " ((((select run_date, location, location2, commodity_code, subst_vals(stacking, 'f', qty, round(qty / 2, 0)) as qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, subst_vals(stacking, 'f','N', 'Y') as inspection, lr_num , subst_vals(storage_end, '', null, storage_end) as storage_end, subst_vals(commodity_name, '', commodity_code, commodity_name) as commodity_name, subst_vals(vessel_name,'', lr_num, vessel_name) as vessel_name, subst_vals(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in from cargo_detail where cargo_system ='CCDS' and run_date >='" & start_date & "' and date_received < run_date)  union all " _
'& " (select run_date, location, location2, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, ''as inspection, lr_num , subst_vals(storage_end, '', null, storage_end) as storage_end, subst_vals(commodity_name, '', commodity_code, commodity_name) as commodity_name, subst_vals(vessel_name,'', lr_num, vessel_name) as vessel_name, subst_vals(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in from cargo_detail where cargo_system = 'BNI' and run_date >='" & start_date & "' and date_received < run_date)  union all " _
'& " (select run_date, location, location2, commodity_code, 1 as qty, 'PLT' as qty_unit,  cargo_id, cargo_customer,cargo_system,date_received, '' as inspection,lr_num , subst_vals(storage_end, '', null, storage_end) as storage_end, subst_vals(commodity_name, '', commodity_code, commodity_name) as commodity_name, subst_vals(vessel_name,'', lr_num, vessel_name) as vessel_name, subst_vals(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in from cargo_detail where cargo_system = 'RF' and qty >= 10 and run_date >= '" & start_date & "' and date_received <run_date )) a " _
'& " left outer join warehouse w on a.location like w.warehouse ||'%' or a.location like 'WING ' || w.warehouse ||'%') left outer join warehouse_box_detail b on a.location like b.warehouse||b.box||'%' or a.location like 'WING '||b.warehouse||b.box||'%') left outer join warehouse_lease l on b.warehouse = l.warehouse and b.box = l.box and a.run_date >= l.start_date and (l.end_date is null or a.run_date <=l.end_date)"

'SQL = "select subst_vals(w.warehouse,'','Other',w.warehouse) as warehouse, subst_vals(b.box,'','N/A',b.warehouse||b.box) as box, subst_vals(l.box,'','N','Y') as leased,  substr(a.commodity_name, 0, 20) as commodity, substr(a.customer_name, 0, 20) as customer, substr(a.vessel_name, 0, 20) as vessel, substr(a.cargo_mark, 0, 20) as mark, a.* from " _
'& " ((((select run_date, location, location2, commodity_code, subst_vals(stacking, 'f', qty, round(qty / 2, 0)) as qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, subst_vals(stacking, 'f','N', 'Y') as inspection, lr_num , subst_vals(storage_end, '', null, storage_end) as storage_end, subst_vals(commodity_name, '', commodity_code, commodity_name) as commodity_name, subst_vals(vessel_name,'', lr_num, vessel_name) as vessel_name, subst_vals(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in from cargo_detail where cargo_system ='CCDS' and run_date >='" & start_date & "' and date_received < run_date)  union all " _
'& " (select run_date, location, location2, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, ''as inspection, lr_num , subst_vals(storage_end, '', null, storage_end) as storage_end, subst_vals(commodity_name, '', commodity_code, commodity_name) as commodity_name, subst_vals(vessel_name,'', lr_num, vessel_name) as vessel_name, subst_vals(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in from cargo_detail where cargo_system = 'BNI' and run_date >='" & start_date & "' and date_received < run_date)  union all " _
'& " (select run_date, location, location2, commodity_code, 1 as qty, 'PLT' as qty_unit,  cargo_id, cargo_customer,cargo_system,date_received, '' as inspection,lr_num , subst_vals(storage_end, '', null, storage_end) as storage_end, subst_vals(commodity_name, '', commodity_code, commodity_name) as commodity_name, subst_vals(vessel_name,'', lr_num, vessel_name) as vessel_name, subst_vals(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in from cargo_detail where cargo_system = 'RF' and qty >= 10 and run_date >= '" & start_date & "' and date_received <run_date ) union all " _
'& " (select run_date, location, location2, commodity_code, 1 as qty, 'ROLL' as qty_unit,  cargo_id, cargo_customer,cargo_system,date_received, '' as inspection,lr_num , subst_vals(storage_end, '', null, storage_end) as storage_end, subst_vals(commodity_name, '', commodity_code, commodity_name) as commodity_name, subst_vals(vessel_name,'', lr_num, vessel_name) as vessel_name, subst_vals(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in from cargo_detail where cargo_system = 'PAPER' and run_date >= '" & start_date & "' and date_received <run_date )) a " _
'& " left outer join warehouse w on a.location like w.warehouse ||'%' or a.location like 'WING ' || w.warehouse ||'%') left outer join warehouse_box_detail b on a.location like b.warehouse||b.box||'%' or a.location like 'WING '||b.warehouse||b.box||'%') left outer join warehouse_lease l on b.warehouse = l.warehouse and b.box = l.box and a.run_date >= l.start_date and (l.end_date is null or a.run_date <=l.end_date)"
 
'SQL = "select decode(w.warehouse,'','Other',w.warehouse) as warehouse, decode(b.box,'','N/A',b.warehouse||b.box) as box, decode(l.box,'','N','Y') as leased,  substr(a.commodity_name, 0, 20) as commodity, substr(a.customer_name, 0, 20) as customer, substr(a.vessel_name, 0, 20) as vessel, substr(a.cargo_mark, 0, 20) as mark,  a.* from " _
'& " ((((select run_date, location, location2, commodity_code, decode(stacking, 'f', qty, round(qty / 2, 0)) as qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, decode(stacking, 'f','N', 'Y') as inspection, lr_num , decode(storage_end, '', null, storage_end) as storage_end, decode(commodity_name, '', commodity_code, commodity_name) as commodity_name, decode(vessel_name,'', lr_num, vessel_name) as vessel_name, decode(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in, 0.0 as weight from cargo_detail where cargo_system ='CCDS' and run_date >='" & start_date & "' and date_received < run_date)  union all " _
'& " (select run_date, location, location2, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, ''as inspection, lr_num , decode(storage_end, '', null, storage_end) as storage_end, decode(commodity_name, '', commodity_code, commodity_name) as commodity_name, decode(vessel_name,'', lr_num, vessel_name) as vessel_name, decode(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in, weight from cargo_detail where cargo_system = 'BNI' and run_date >='" & start_date & "' and date_received < run_date)  union all " _
'& " (select run_date, location, location2, commodity_code, 1 as qty, 'PLT' as qty_unit,  cargo_id, cargo_customer,cargo_system,date_received, '' as inspection,lr_num , decode(storage_end, '', null, storage_end) as storage_end, decode(commodity_name, '', commodity_code, commodity_name) as commodity_name, decode(vessel_name,'', lr_num, vessel_name) as vessel_name, decode(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in, 0.0 as weight from cargo_detail where cargo_system = 'RF' and qty >= 10 and run_date >= '" & start_date & "' and date_received <run_date ) union all " _
'& " (select run_date, location, location2, commodity_code, 1 as qty, 'ROLL' as qty_unit,  cargo_id, cargo_customer,cargo_system,date_received, '' as inspection,lr_num , decode(storage_end, '', null, storage_end) as storage_end, decode(commodity_name, '', commodity_code, commodity_name) as commodity_name, decode(vessel_name,'', lr_num, vessel_name) as vessel_name, decode(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in, weight from cargo_detail where cargo_system = 'PAPER' and run_date >= '" & start_date & "' and date_received <run_date )) a " _
'& " left outer join warehouse w on a.location like w.warehouse ||'%' or a.location like 'WING ' || w.warehouse ||'%') left outer join warehouse_box_detail b on a.location like b.warehouse||b.box||'%' or a.location like 'WING '||b.warehouse||b.box||'%') left outer join warehouse_lease l on b.warehouse = l.warehouse and b.box = l.box and a.run_date >= l.start_date and (l.end_date is null or a.run_date <=l.end_date)"
'
'SQL = SQL & " (select run_date, location, location2, commodity_code," ' subquery to CCDS system (defunct)
'SQL = SQL & " decode(stacking, 'f', qty, round(qty / 2, 0)) as qty," ' subquery to CCDS system (defunct)
'SQL = SQL & " qty_unit, cargo_id, cargo_customer, cargo_system, date_received," ' subquery to CCDS system (defunct)
'SQL = SQL & " decode(stacking, 'f','N', 'Y') as inspection, lr_num," ' subquery to CCDS system (defunct)
'SQL = SQL & " decode(storage_end, '', null, storage_end) as storage_end," ' subquery to CCDS system (defunct)
'SQL = SQL & " nvl(commodity_name, TO_CHAR(commodity_code)) as commodity_name," ' subquery to CCDS system (defunct)
'SQL = SQL & " decode(vessel_name,'', lr_num, vessel_name) as vessel_name," ' subquery to CCDS system (defunct)
'SQL = SQL & " nvl(customer_name, TO_CHAR(cargo_customer)) as customer_name," ' subquery to CCDS system (defunct)
'SQL = SQL & " cargo_mark, trucked_in, 0.0 as weight" ' subquery to CCDS system (defunct)
'SQL = SQL & " from cargo_detail where cargo_system ='CCDS' and run_date >='" & start_date & "'" ' subquery to CCDS system (defunct)
'SQL = SQL & " and date_received < run_date)" ' subquery to CCDS system (defunct)
'SQL = SQL & " union all"
'SQL = SQL & " union all"
'SQL = SQL & " (select run_date, location, location2, commodity_code, 1 as qty, 'ROLL' as qty_unit," ' subquery to HOLMEN system (defunct)
'SQL = SQL & " cargo_id, cargo_customer,cargo_system,date_received, '' as inspection,lr_num," ' subquery to HOLMEN system (defunct)
'SQL = SQL & " decode(storage_end, '', null, storage_end) as storage_end," ' subquery to HOLMEN system (defunct)
'SQL = SQL & " nvl(commodity_name, TO_CHAR(commodity_code)) as commodity_name," ' subquery to HOLMEN system (defunct)
'SQL = SQL & " decode(vessel_name,'', lr_num, vessel_name) as vessel_name," ' subquery to HOLMEN system (defunct)
'SQL = SQL & " nvl(customer_name, TO_CHAR(cargo_customer)) as customer_name," ' subquery to HOLMEN system (defunct)
'SQL = SQL & " cargo_mark, trucked_in, weight" ' subquery to HOLMEN system (defunct)
'SQL = SQL & " from cargo_detail where cargo_system = 'PAPER' and run_date >= '" & start_date & "'" ' subquery to HOLMEN system (defunct)
'SQL = SQL & " and date_received <run_date )" ' subquery to HOLMEN system (defunct)

SQL = "select decode(w.warehouse,'','Other',w.warehouse) as warehouse," ' cube output data
SQL = SQL & " decode(b.box,'','N/A',b.warehouse||b.box) as box," ' cube output data
SQL = SQL & " decode(l.box,'','N','Y') as leased," ' cube output data
SQL = SQL & " substr(a.commodity_name, 0, 20) as commodity," ' cube output data
SQL = SQL & " substr(a.customer_name, 0, 20) as customer," ' cube output data
SQL = SQL & " substr(a.vessel_name, 0, 20) as vessel," ' cube output data
SQL = SQL & " substr(a.cargo_mark, 0, 20) as mark," ' cube output data
SQL = SQL & " a.* from" ' cube output data
SQL = SQL & " ((("
SQL = SQL & " (select run_date, location, location2, commodity_code, qty, qty_unit," ' subquery to BNI system
SQL = SQL & " cargo_id, cargo_customer, cargo_system, date_received, '' as inspection, lr_num," ' subquery to BNI system
SQL = SQL & " decode(storage_end, '', null, storage_end) as storage_end," ' subquery to BNI system
SQL = SQL & " nvl(commodity_name, TO_CHAR(commodity_code)) as commodity_name," ' subquery to BNI system
SQL = SQL & " decode(vessel_name,'', lr_num, vessel_name) as vessel_name," ' subquery to BNI system
SQL = SQL & " nvl(customer_name, TO_CHAR(cargo_customer)) as customer_name," ' subquery to BNI system
SQL = SQL & " cargo_mark, trucked_in, weight " ' subquery to BNI system
SQL = SQL & " from cargo_detail where cargo_system = 'BNI' and run_date >='" & start_date & "'" ' subquery to BNI system
SQL = SQL & " and date_received < run_date)" ' subquery to BNI system
SQL = SQL & " union all"
SQL = SQL & " (select run_date, location, location2, commodity_code, 1 as qty, 'PLT' as qty_unit," ' subquery to RF system
SQL = SQL & " cargo_id, cargo_customer,cargo_system,date_received, '' as inspection,lr_num ," ' subquery to RF system
SQL = SQL & " decode(storage_end, '', null, storage_end) as storage_end," ' subquery to RF system
SQL = SQL & " nvl(commodity_name, TO_CHAR(commodity_code)) as commodity_name," ' subquery to RF system
SQL = SQL & " decode(vessel_name,'', lr_num, vessel_name) as vessel_name," ' subquery to RF system
SQL = SQL & " nvl(customer_name, TO_CHAR(cargo_customer)) as customer_name," ' subquery to RF system
SQL = SQL & " cargo_mark, trucked_in, 0.0 as weight" ' subquery to RF system
SQL = SQL & " from cargo_detail where cargo_system = 'RF'" 'and (qty >= 10 or (commodity_code = '1272' and qty > 0))" 'REMOVED HD8264
SQL = SQL & " and run_date >= '" & start_date & "'" ' subquery to RF system
SQL = SQL & " and date_received <run_date )" ' subquery to RF system
SQL = SQL & " ) a"
SQL = SQL & " left outer join cube_whse w on" ' join to Warehouse table.
SQL = SQL & " a.location like w.warehouse ||'%' or a.location like 'WING ' || w.warehouse ||'%')"
SQL = SQL & " left outer join warehouse_box_detail b on" ' join to Warehouse_box_detail table
SQL = SQL & " a.location like b.warehouse||b.box||'%' or a.location like 'WING '||b.warehouse||b.box||'%')"
SQL = SQL & " left outer join warehouse_lease l on b.warehouse = l.warehouse and b.box = l.box and"
SQL = SQL & " a.run_date >= l.start_date and (l.end_date is null or a.run_date <=l.end_date)"

 

'Create form and initialize ContourCube properties
With ContourCubeX1
 
    .Active = False
    .DataSourceType = xcdt_ADO
    .ConnectionString = CONS
    .SQL = SQL
    
    'Create Dimensions and Facts in ContourCube
    .CubeTitle = "DSPC Warehouse Inventory (" & year & "/" & month & ")"
    .AddDimension "warehouse", "WING", xda_vertical, 1
    .AddDimension "box", "BOX", xda_vertical, 2
'    .AddDimension "commodity_code", "COMM", xda_vertical, 3
'    .AddDimension "cargo_customer", "CUST", xda_vertical, 4
'    .AddDimension "lr_num", "VESSEL", xda_vertical, 5
'    .AddDimension "commodity_name", "COMM", xda_vertical, 3
'    .AddDimension "customer_name", "CUST", xda_vertical, 4
'    .AddDimension "vessel_name", "VESSEL", xda_vertical, 5
'    .AddDimension "cargo_mark", "MARK", xda_vertical, 6
    .AddDimension "commodity", "COMMODITY", xda_vertical, 3
    .AddDimension "customer", "CUSTOMER", xda_vertical, 4
    .AddDimension "vessel", "VESSEL", xda_vertical, 5
    .AddDimension "mark", "MARK", xda_vertical, 6
    .AddDimension "cargo_id", "ID", xda_vertical, 7
    .AddDimension "location", "LOCATION", xda_vertical, 8
    .AddDimension "qty_unit", "UNIT", xda_vertical, 9
    .AddDimension "date_received", "RECEIVED", xda_vertical, 10
    .AddDimension "storage_end", "STR_BILL", xda_vertical, 11
    .AddDimension "cargo_system", "SYSTEM", xda_vertical, 12
    .AddDimension "trucked_in", "TRUCKED IN CARGO", xda_vertical, 13
    .AddDimension "inspection", "INSPECTION CARGO", xda_vertical, 14
    .AddDimension "leased", "WHS LEASED", xda_vertical, 15


    .SetDimPos "cargo_system", xda_outside, -1
    .SetDimPos "trucked_in", xda_outside, -1
    .SetDimPos "inspection", xda_outside, -1
    .SetDimPos "leased", xda_outside, -1

      

    '.AddDimension "run_date", "Date", xda_horizontal, 1
    .AddDimension "run_date", "Date", xda_outside, -1

    .AddDimModifier "Y", xoft_Date, "run_date", xmt_y_split, "Year", xda_horizontal, 1
   ' .AddDimModifier "Q", xoft_Date, "run_date", xmt_q_split, "Quarter", xda_horizontal, 2

    '.AddDimension "run_date", "Date", xda_outside, -1
    .AddDimModifier "M", xoft_Date, "run_date", xmt_m_split, "Month", xda_horizontal, 3
    .AddDimModifier "D", xoft_Date, "run_date", xmt_d_split, "Day", xda_horizontal, 4
 
    .AddFact "qty", "qty", xfaa_SUM, "QTY"
    .AddFact "weight", "weight", xfaa_SUM, "KGS"
    .FactVisible("weight") = False
    .DimFlags("Y") = xfNoTotals
    .DimFlags("M") = xfNoTotals
    .DimFlags("D") = xfNoTotals
    .HDrillDownLevel = 3

    .Active = True

    .DimensionsFilter.BeginUpdate
    .DimensionsFilter.FilterAll "D", xfo_FilterAll
    .DimensionsFilter.FilterValue "D", day, False
    .DimensionsFilter.EndUpdate

    .DimensionsFilter.BeginUpdate
    .DimensionsFilter.FilterAll "M", xfo_FilterAll
    .DimensionsFilter.FilterValue "M", month_num, False
    .DimensionsFilter.EndUpdate

    .DimensionsFilter.BeginUpdate
    .DimensionsFilter.FilterAll "Y", xfo_FilterAll
    .DimensionsFilter.FilterValue "Y", year, False
    .DimensionsFilter.EndUpdate
    .VDrillDownLevel = 2

    'ContourCubeX1.SaveCube ("../cubes/inventory/" & year & "-" & month & ".cube")
    ContourCubeX1.SaveCube (year & "-" & month_num & ".cube")
    'ContourCubeX1.SaveCube ("data_warehouse.cube")
    

End With

Unload Me
End

 

End Sub

Private Sub Form_Resize()
  'ContourCubeX1.Move 0, 0, Me.ScaleWidth, Me.ScaleHeight
   With ContourCubeX1
   .Top = 700
   .Left = 0
   .Height = Form1.Height - 1100
   .Width = Form1.Width - 100
 End With
End Sub


