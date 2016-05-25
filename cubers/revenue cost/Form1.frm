VERSION 5.00
Object = "{CCA2C66D-33FD-11D5-8D72-005004532BDF}#1.3#0"; "CCubeX.ocx"
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

Private Const CONS = "Provider=msdaora;Data Source=BNI;User Id=SAG_OWNER;Password=SAG"
'Private Const CONS = "DSN=data_warehouse;UID=admin;PWD="
'Private Const CONS = "Driver={PostgreSQL};SERVER=172.22.15.70;Database=data_warehouse;UID=admin;PWD="

Private Sub Form_Load()

Dim dept_name(7) As String
Dim dept_code(7) As String
Dim SQL   As String
Dim weekOfYear As Integer
Dim i As Integer
Dim year As String
Dim month As String
Dim day As String
Dim start_date As String

Dim today() As String
today() = Split(CStr(Format(Now, "mm/dd/yyyy")), "/  ")

'month = today(0)
'day = today(1)
'year = today(2)
'
'start_date = month & "/01/" & year
'
'SQL = "select subst_vals(w.warehouse,'','Other',w.warehouse) as warehouse, subst_vals(b.box,'','N/A',b.warehouse||b.box) as box, subst_vals(l.box,'','N','Y') as leased, a.* from " _
'& " ((((select run_date, location, location2, commodity_code, subst_vals(stacking, 'f', qty, round(qty / 2, 0)) as qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, subst_vals(stacking, 'f','N', 'Y') as inspection, lr_num from cargo_detail where cargo_system ='CCDS' and run_date >='" & start_date & "' and date_received < run_date)  union all " _
'& " (select run_date, location, location2, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, ''as inspection, lr_num from cargo_detail where cargo_system = 'BNI' and run_date >='" & start_date & "' and date_received < run_date)  union all " _
'& " (select run_date, location, location2, commodity_code, 1 as qty, 'PLT' as qty_unit,  cargo_id, cargo_customer,cargo_system,date_received, '' as inspection,lr_num from cargo_detail where cargo_system = 'RF' and qty >= 10 and run_date >= '" & start_date & "' and date_received <run_date )) a " _
'& " left outer join warehouse w on a.location like w.warehouse ||'%' or a.location like 'WING ' || w.warehouse ||'%') left outer join warehouse_box_detail b on a.location like b.warehouse||b.box||'%' or a.location like 'WING '||b.warehouse||b.box||'%') left outer join warehouse_lease l on b.warehouse = l.warehouse and b.box = l.box and a.run_date >= l.start_date and (l.end_date is null or a.run_date <=l.end_date)"
'
'SQL = "select subst_vals(w.warehouse,'','Other',w.warehouse) as warehouse, subst_vals(b.box,'','N/A',b.warehouse||b.box) as box, subst_vals(l.box,'','N','Y') as leased,  substr(a.commodity_name, 0, 20) as commodity, substr(a.customer_name, 0, 20) as customer, substr(a.vessel_name, 0, 20) as vessel, substr(a.cargo_mark, 0, 20) as mark, a.* from " _
'& " ((((select run_date, location, location2, commodity_code, subst_vals(stacking, 'f', qty, round(qty / 2, 0)) as qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, subst_vals(stacking, 'f','N', 'Y') as inspection, lr_num , subst_vals(storage_end, '', null, storage_end) as storage_end, subst_vals(commodity_name, '', commodity_code, commodity_name) as commodity_name, subst_vals(vessel_name,'', lr_num, vessel_name) as vessel_name, subst_vals(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in from cargo_detail where cargo_system ='CCDS' and run_date >='" & start_date & "' and date_received < run_date)  union all " _
'& " (select run_date, location, location2, commodity_code, qty, qty_unit, cargo_id, cargo_customer, cargo_system, date_received, ''as inspection, lr_num , subst_vals(storage_end, '', null, storage_end) as storage_end, subst_vals(commodity_name, '', commodity_code, commodity_name) as commodity_name, subst_vals(vessel_name,'', lr_num, vessel_name) as vessel_name, subst_vals(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in from cargo_detail where cargo_system = 'BNI' and run_date >='" & start_date & "' and date_received < run_date)  union all " _
'& " (select run_date, location, location2, commodity_code, 1 as qty, 'PLT' as qty_unit,  cargo_id, cargo_customer,cargo_system,date_received, '' as inspection,lr_num , subst_vals(storage_end, '', null, storage_end) as storage_end, subst_vals(commodity_name, '', commodity_code, commodity_name) as commodity_name, subst_vals(vessel_name,'', lr_num, vessel_name) as vessel_name, subst_vals(customer_name, '', cargo_customer, customer_name) as customer_name, cargo_mark, trucked_in from cargo_detail where cargo_system = 'RF' and qty >= 10 and run_date >= '" & start_date & "' and date_received <run_date )) a " _
'& " left outer join warehouse w on a.location like w.warehouse ||'%' or a.location like 'WING ' || w.warehouse ||'%') left outer join warehouse_box_detail b on a.location like b.warehouse||b.box||'%' or a.location like 'WING '||b.warehouse||b.box||'%') left outer join warehouse_lease l on b.warehouse = l.warehouse and b.box = l.box and a.run_date >= l.start_date and (l.end_date is null or a.run_date <=l.end_date)"
'
SQL = " SELECT R.*, add_months(R.DATE_TIME, 6) as fYear FROM REVENUE_TONS_HOURS R"

SQL = " select * from " _
    & " (SELECT R.*, add_months(R.DATE_TIME, 6) as fYear, to_char(add_months(R.DATE_TIME, 6), 'IW')||' Week' as week  FROM REVENUE_TONS_HOURS R where to_char(add_months(R.DATE_TIME, 6), 'yy') = 04" _
    & " Union All " _
    & " SELECT R.*, add_months(R.DATE_TIME, 6) as fYear, to_char(add_months(R.DATE_TIME, 6)+7, 'IW')||' Week' as week  FROM REVENUE_TONS_HOURS R where to_char(add_months(R.DATE_TIME, 6), 'yy') = 05)"

SQL = " select * from " _
    & " (SELECT R.*, add_months(R.DATE_TIME, 6) as fYear, to_char(add_months(R.DATE_TIME, 6), 'IW')||' Week' as week  FROM VIEW_REVENUE_TONS_HOURS R where to_char(add_months(R.DATE_TIME, 6), 'yy') = 04" _
    & " Union All " _
    & " SELECT R.*, add_months(R.DATE_TIME, 6) as fYear, to_char(add_months(R.DATE_TIME, 6)+7, 'IW')||' Week' as week  FROM VIEW_REVENUE_TONS_HOURS R where to_char(add_months(R.DATE_TIME, 6), 'yy') >= 05)"
SQL = " SELECT R.*, add_months(R.DATE_TIME, 6) as fYear FROM VIEW_REVENUE_TONS_HOURS R"

'Create form and initialize ContourCube properties
With ContourCubeX1

    .Active = False
    .DataSourceType = xcdt_ADO
    .ConnectionString = CONS
    .SQL = SQL
    
    'Create Dimensions and Facts in ContourCube
    
    .CubeTitle = "DSPC REVENUE, TONNAGE AND LABOR HOURS"
    .AddDimension "itg", "PROGRAM", xda_vertical, 1
    .AddDimension "commodity_name", "COMMODITY", xda_vertical, 2
    .AddDimension "gl_code", "GL", xda_wertical, 3
    .AddDimension "customer", "CUSTOMER", xda_wertical, 4
    .AddDimension "lr_name", "VESSEL", xda_vertical, 5
    
    
    .AddDimension "weight_unit", "WEIGHT UNIT", xda_vertical, 4

'    .AddDimension "billing_type", "TYPE", xda_vertical, 3
'    .AddDimension "service_rate", "RATE", xda_vertical, 4
'    .AddDimension "description", "DESCRIPTION", xda_vertical, 5
'    .AddDimension "commodity", "COMMODITY", xda_vertical, 6
'    .AddDimension "service_name", "SERVICE", xda_vertical, 7
'    .AddDimension "vessel_name", "VESSEL", xda_vertical, 8


    
    
    
    .AddDimension "fYear", "Date", xda_outside, -1
    .AddDimModifier "Y", xoft_Date, "fYear", xmt_y_split, "FY", xda_horizontal, 1
    .AddDimModifier "Q", xoft_Date, "fYear", xmt_q_split, "Quarter", xda_horizontal, 2
            
    .AddDimension "date_time", "Date", xda_outside, -1
    .AddDimModifier "M", xoft_Date, "date_time", xmt_m_split, "Month", xda_horizontal, 3
  '  .AddDimModifier "W", xoft_Date, "week", xmt_w_split, "Week", xda_horizontal, 4
 '   .AddDimModifier "W", xoft_String, "week", xmt_none, "Week", xda_horizontal, 4
    .AddDimModifier "D", xoft_Date, "date_time", xmt_d_split, "Day", xda_horizontal, 4

    .AddFact "revenue", "revenue", xfaa_SUM, "REVENUE"
    .AddFact "weight", "weight", xfaa_SUM, "TONS"
    .AddFact "hours", "hours", xfaa_SUM, "HOURS"
    .AddFormula "R/T", "revenue/weight", "R/T"
    .AddFormula "H/T", "hours/weight", "H/T"
    
    .DimFlags("Y") = xfNoTotals
    .DimFlags("Q") = xfNoTotals
    .DimFlags("M") = xfNoTotals
    '.DimFlags("W") = xfNoTotals
    
    .FactVisible("R/T") = False
    .FactVisible("H/T") = False
    .Active = True
    
'    .DimensionsFilter.BeginUpdate
'    .DimensionsFilter.FilterAll "W", xfo_FilterAll
'
'    weekOfYear = DatePart("WW", Now) - 26
'    For i = 1 To weekOfYear
'        .DimensionsFilter.FilterValue "W", Format(i, "00") + " Week", False
'    Next
'    .DimensionsFilter.EndUpdate
     
    .VDrillDownLevel = 1
    .HDrillDownLevel = 1

   
    ContourCubeX1.SaveCube ("revenue_cost.cube")
    
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


