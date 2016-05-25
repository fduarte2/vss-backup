VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Begin VB.Form frmSelect 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Selection Criteria"
   ClientHeight    =   4875
   ClientLeft      =   3240
   ClientTop       =   3375
   ClientWidth     =   7935
   Icon            =   "frmSelect.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   4875
   ScaleWidth      =   7935
   Begin Crystal.CrystalReport Crw1 
      Left            =   360
      Top             =   4440
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      WindowState     =   2
      PrintFileLinesPerPage=   60
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "E&XIT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   4560
      TabIndex        =   8
      ToolTipText     =   "Return Back"
      Top             =   3840
      Width           =   2295
   End
   Begin VB.CommandButton cmdShowRpt 
      Caption         =   "&SHOW REPORT"
      Default         =   -1  'True
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   840
      TabIndex        =   7
      ToolTipText     =   "Show Report for the Selected Date"
      Top             =   3840
      Width           =   2295
   End
   Begin VB.Frame Frame1 
      Caption         =   "Selection Criteria"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   2415
      Left            =   360
      TabIndex        =   0
      Top             =   1080
      Width           =   6975
      Begin SSCalendarWidgets_A.SSDateCombo ssdtcboDate 
         Height          =   375
         Left            =   1320
         TabIndex        =   3
         ToolTipText     =   "Select Date"
         Top             =   600
         Width           =   2175
         _Version        =   65543
         _ExtentX        =   3836
         _ExtentY        =   661
         _StockProps     =   93
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ShowCentury     =   -1  'True
      End
      Begin VB.OptionButton optSelectCrit 
         Caption         =   "&From"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Index           =   1
         Left            =   240
         TabIndex        =   2
         Top             =   1440
         Width           =   1095
      End
      Begin VB.OptionButton optSelectCrit 
         Caption         =   "&Date"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Index           =   0
         Left            =   240
         TabIndex        =   1
         Top             =   600
         Width           =   975
      End
      Begin SSCalendarWidgets_A.SSDateCombo ssdtcboFrom 
         Height          =   375
         Left            =   1320
         TabIndex        =   4
         ToolTipText     =   "Select From Date and To Date"
         Top             =   1440
         Width           =   2175
         _Version        =   65543
         _ExtentX        =   3836
         _ExtentY        =   661
         _StockProps     =   93
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ShowCentury     =   -1  'True
      End
      Begin SSCalendarWidgets_A.SSDateCombo ssdtcboTo 
         Height          =   375
         Left            =   4200
         TabIndex        =   6
         ToolTipText     =   "Select From Date and To Date"
         Top             =   1440
         Width           =   2415
         _Version        =   65543
         _ExtentX        =   4260
         _ExtentY        =   661
         _StockProps     =   93
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ShowCentury     =   -1  'True
      End
      Begin VB.Label Label1 
         Caption         =   "To"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   3720
         TabIndex        =   5
         Top             =   1440
         Width           =   735
      End
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   7920
      Y1              =   960
      Y2              =   960
   End
   Begin VB.Label Label6 
      Alignment       =   2  'Center
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   855
      Left            =   960
      TabIndex        =   9
      Top             =   0
      Width           =   6855
   End
   Begin MSForms.Image Image1 
      Height          =   735
      Left            =   0
      Top             =   0
      Width           =   855
      BorderStyle     =   0
      SizeMode        =   1
      SpecialEffect   =   2
      Size            =   "1508;1296"
      Picture         =   "frmSelect.frx":0442
   End
End
Attribute VB_Name = "frmSelect"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit      '5/2/2007 HD2759 Rudy:

'****************************************
'To Close the Current Form and to Open the Previous Form
'****************************************
Private Sub cmdExit_Click()
  Unload Me
End Sub

'****************************************
'To Show the Selected Report taking into account the selected criteria for date
'****************************************
Private Sub cmdShowRpt_Click()
'  On Error GoTo ErrHandler
  Dim mySQL As String
  Dim myDE As New DE_LCS
  Dim myDR As New DR_TimeSheet, myDR1 As New DR_DayCloseExp
  
  Call MouseHourlyGlass
  
  'Build the WHERE Clause and Show the Report
  If optSelectCrit(0).Value = True Then
  'Only Particular Date
    If UCase(ReportTitle) = "TIMEOUT" Then
      mySQL = "Select to_char(a.end_time,'hh:mi am') " + """End Time""" + ", to_char(b.Time_Out,'hh:mi am') " + """Time Out""" + ", a.Employee_ID, a.Hire_Date, c.Employee_Name" + " from hourly_detail a, daily_hire_list b , Employee c"
      mySQL = mySQL + " Where a.hire_date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy')"
      mySQL = mySQL + " and b.hire_date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy')"
      mySQL = mySQL + " and a.employee_id = b.employee_id and a.employee_id = c.employee_id"
      mySQL = mySQL + " and to_char(a.End_Time,'hh24') >= (select max(to_char(End_Time,'hh24')) from hourly_detail"
      mySQL = mySQL + " where hire_date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy') and employee_id = a.employee_id) "
      mySQL = mySQL + " and (to_number(to_char(b.time_out,'mi') + to_char(b.time_out,'hh24')*60) not "
      mySQL = mySQL + " between to_number(to_char(a.End_Time,'mi') + to_char(a.End_Time,'hh24')*60) "
      mySQL = mySQL + " and to_number(to_char(a.End_Time,'mi') + 15 + to_char(a.End_Time,'hh24')*60)) "
      mySQL = mySQL + " and (to_number(to_char(a.End_Time,'mi') + to_char(a.End_Time,'hh24')*60) not "
      mySQL = mySQL + " between to_number(to_char(b.time_out,'mi') + to_char(b.time_out,'hh24')*60) "
      mySQL = mySQL + " and to_number(to_char(b.time_out,'mi') + 15 + to_char(b.time_out,'hh24')*60))"
      DE_TimeOutException.rsTimeOutException.Source = mySQL
      DE_TimeOutException.rsTimeOutException.Open
      DR_TimeOutException.Refresh
      DR_TimeOutException.Show
      DE_TimeOutException.rsTimeOutException.Close
    'ElseIf UCase(ReportTitle) = "TIMESHEET" Then
      'myDE.rsHourlyDetail_Grouping.Source = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name, b.* From Employee a, Hourly_Detail b, LCS_User c Where b.Employee_id = a.Employee_Id and upper(b.Employee_Id) Not Like 'T%' and b.User_ID = c.User_ID and b.Hire_Date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy') and Upper(b.EARNING_TYPE_ID) <> 'LU' Order by b.Employee_ID, Row_Number}  AS HourlyDetail COMPUTE HourlyDetail BY 'Employee_Type_ID','Employee_Sub_Type_Id','Employee_Id','Employee_Name'"
      'myDE.rsHourlyDetail_Grouping.Sort = "Employee_ID"
      'myDE.rsHourlyDetail_Grouping.Open
      'myDR.Refresh
      'myDR.Show
      'myDE.rsHourlyDetail_Grouping.Close

    'ElseIf UCase(ReportTitle) = "DAYCLOSE" Then
      'myDE.rsHourlyDetail_Grouping.Source = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name, b.* From Employee a, Hourly_Detail b, LCS_User c Where b.Employee_id = a.Employee_Id and b.exception IN ('Y','C') and upper(b.Employee_Id) Not Like 'T%' and b.User_ID = c.User_ID and b.Hire_Date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy') and Upper(b.EARNING_TYPE_ID) <> 'LU' Order by b.Employee_ID, Row_Number}  AS HourlyDetail COMPUTE HourlyDetail BY 'Employee_Type_ID','Employee_Sub_Type_Id','Employee_Id','Employee_Name'"
      'myDE.rsHourlyDetail_Grouping.Sort = "Employee_ID"
      'myDE.rsHourlyDetail_Grouping.Open
      'myDR1.Refresh
      'myDR1.Show
      'myDE.rsHourlyDetail_Grouping.Close
    ElseIf UCase(ReportTitle) = "SWIPEOUTEXCEPTION1" Then
      'Check for records in CheckOutException Table and put the data in Daily Hire if possible
      Call UpdateTimeOut(1)
      mySQL = "Select max(a.Time_Out) as Time_Out, a.employee_id, b.Employee_name, a.Hire_date from checkoutexception a, Employee b where a.Hire_Date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy') and a.Employee_ID = b.Employee_ID group by a.employee_id, b.Employee_Name, a.Hire_date"
      DE_SwipeOutException1.rsSwipeOutException1.Source = mySQL
      DE_SwipeOutException1.rsSwipeOutException1.Open
      DR_SwipeOutException1.Refresh
      DR_SwipeOutException1.Show
      DE_SwipeOutException1.rsSwipeOutException1.Close
    ElseIf UCase(ReportTitle) = "SWIPEOUTEXCEPTION2" Then
      'Check for records in CheckOutException Table and put the data in Daily Hire if possible
      Call UpdateTimeOut(1)
      mySQL = "Select a.Hire_Date, a.Employee_ID, b.Employee_Name, a.Time_In from daily_hire_list a, Employee b where a.Hire_Date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy') and a.Time_Out is Null and a.Employee_ID = b.Employee_ID"
      DE_SwipeOutException2.rsSwipeOutException2.Source = mySQL
      DE_SwipeOutException2.rsSwipeOutException2.Open
      DR_SwipeOutException2.Refresh
      DR_SwipeOutException2.Show
      DE_SwipeOutException2.rsSwipeOutException2.Close
    ElseIf UCase(ReportTitle) = "TIMEIN" Then
      mySQL = "Select to_char(a.start_time,'hh:mi am') " + """Start Time""" + ", to_char(b.Time_In,'hh:mi am') " + """Time In""" + ", a.Employee_ID, a.Hire_Date, c.Employee_Name" + " from hourly_detail a, daily_hire_list b , Employee c"
      mySQL = mySQL + " Where a.hire_date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy')"
      mySQL = mySQL + " and b.hire_date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy')"
      mySQL = mySQL + " and a.employee_id = b.employee_id and a.employee_id = c.employee_id"
      mySQL = mySQL + " and a.Start_Time <= (select min(Start_Time) from hourly_detail"
      mySQL = mySQL + " where hire_date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy') and employee_id = a.employee_id) "
      mySQL = mySQL + " and (to_number(to_char(b.time_In,'mi') + to_char(b.time_In,'hh24')*60) not "
      mySQL = mySQL + " between to_number(to_char(a.start_time,'mi') + to_char(a.start_time,'hh24')*60) "
      mySQL = mySQL + " and to_number(to_char(a.start_time,'mi') + 15 + to_char(a.start_time,'hh24')*60)) "
      mySQL = mySQL + " and (to_number(to_char(a.start_time,'mi') + to_char(a.start_time,'hh24')*60) not "
      mySQL = mySQL + " between to_number(to_char(b.time_In,'mi') + to_char(b.time_In,'hh24')*60) "
      mySQL = mySQL + " and to_number(to_char(b.time_In,'mi') + 15 + to_char(b.time_In,'hh24')*60)) "
      DE_TimeInException.rsTimeInException.Source = mySQL
      DE_TimeInException.rsTimeInException.Open
      DR_TimeInException.Refresh
      DR_TimeInException.Show
      DE_TimeInException.rsTimeInException.Close
    ElseIf UCase(ReportTitle) = "DOUBLEENTRY" Then
      Me.Hide
      DoubleEntryReportDate
    End If
  ElseIf optSelectCrit(1).Value = True Then
    If UCase(ReportTitle) = "TIMEOUT" Then
      mySQL = "Select to_char(a.end_time,'hh:mi am') " + """End Time""" + ", to_char(b.Time_Out,'hh:mi am') " + """Time Out""" + ", a.Employee_ID, a.Hire_Date, c.Employee_Name" + " from hourly_detail a, daily_hire_list b , Employee c"
      mySQL = mySQL + " Where a.hire_date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and a.hire_date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy')"
      mySQL = mySQL + " and b.hire_date = a.hire_date"
      mySQL = mySQL + " and a.employee_id = b.employee_id and a.employee_id = c.employee_id"
      mySQL = mySQL + " and to_char(a.End_Time,'hh24:mi') >= (select max(to_char(End_Time,'hh24:mi')) from hourly_detail"
      mySQL = mySQL + " where hire_date = a.hire_date and employee_id = a.employee_id) "
      mySQL = mySQL + " and (to_number(to_char(b.time_out,'mi') + to_char(b.time_out,'hh24')*60) not "
      mySQL = mySQL + " between to_number(to_char(a.End_Time,'mi') + to_char(a.End_Time,'hh24')*60) "
      mySQL = mySQL + " and to_number(to_char(a.End_Time,'mi') + 15 + to_char(a.End_Time,'hh24')*60)) "
      mySQL = mySQL + " and (to_number(to_char(a.End_Time,'mi') + to_char(a.End_Time,'hh24')*60) not "
      mySQL = mySQL + " between to_number(to_char(b.time_out,'mi') + to_char(b.time_out,'hh24')*60) "
      mySQL = mySQL + " and to_number(to_char(b.time_out,'mi') + 15 + to_char(b.time_out,'hh24')*60))"
      DE_TimeOutException.rsTimeOutException.Source = mySQL
      DE_TimeOutException.rsTimeOutException.Open
      DR_TimeOutException.Refresh
      DR_TimeOutException.Show
      DE_TimeOutException.rsTimeOutException.Close
    'ElseIf UCase(ReportTitle) = "TIMESHEET" Then
    '  myDE.rsHourlyDetail_Grouping.Source = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name, b.* From Employee a, Hourly_Detail b, LCS_User c Where b.Employee_id = a.Employee_Id and upper(b.Employee_Id) Not Like 'T%' and b.User_ID = c.User_ID and b.Hire_Date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and b.Hire_Date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy') and Upper(b.EARNING_TYPE_ID) <> 'LU' Order by b.Employee_ID, Row_Number}  AS HourlyDetail COMPUTE HourlyDetail BY 'Employee_Type_ID','Employee_Sub_Type_Id','Employee_Id','Employee_Name'"
    '  myDE.rsHourlyDetail_Grouping.Sort = "Employee_ID"
    '  myDE.rsHourlyDetail_Grouping.Open
    '  myDR.Refresh
    '  myDR.Show
    '  myDE.rsHourlyDetail_Grouping.Close
    '  Crw1.LogOnServer "PDSORA7", "LCS", "", "LABOR", "LABOR"   'ODBCDriver, ServerName, Database, UserName, Password
    '  Crw1.Report FileName = App.Path + "\TimeSheet.rpt"
    '  Crw1.DiscardSavedData = True
    '  Crw1.SelectionFormula = "{HOURLY_DETAIL.HIRE_DATE} >=" + "date(" + Str(Year(ssdtcboFrom.Text)) + "," + Str(Month(ssdtcboFrom.Text)) + "," + Str(Day(ssdtcboFrom.Text)) + ") AND {HOURLY_DETAIL.HIRE_DATE} <=" + "date(" + Str(Year(ssdtcboTo.Text)) + "," + Str(Month(ssdtcboTo.Text)) + "," + Str(Day(ssdtcboTo.Text)) + ")"
      'Crw1.Formulas(0) = "DtHead = '" + ssdtcboTo.Text + "'"
   '   Crw1.Action = 1

    'ElseIf UCase(ReportTitle) = "DAYCLOSE" Then
      'myDE.rsHourlyDetail_Grouping.Source = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name, b.* From Employee a, Hourly_Detail b, LCS_User c Where b.Employee_id = a.Employee_Id and b.exception IN ('Y','C')  and upper(b.Employee_Id) Not Like 'T%' and b.User_ID = c.User_ID and b.Hire_Date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and b.Hire_Date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy') and Upper(b.EARNING_TYPE_ID) <> 'LU' Order by b.Employee_ID, Row_Number}  AS HourlyDetail COMPUTE HourlyDetail BY 'Employee_Type_ID','Employee_Sub_Type_Id','Employee_Id','Employee_Name'"
      'myDE.rsHourlyDetail_Grouping.Sort = "Employee_ID"
      'myDE.rsHourlyDetail_Grouping.Open
      'myDR1.Refresh
      'myDR1.Show
      'myDE.rsHourlyDetail_Grouping.Close
    ElseIf UCase(ReportTitle) = "CHECKOUTEXCEPTION" Then
      mySQL = "Select * from CheckOutException where Hire_Date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and Hire_Date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy')"
      DE_SwipeOutException1.rsSwipeOutException1.Source = mySQL
      DE_SwipeOutException1.rsSwipeOutException1.Open
      DR_SwipeOutException1.Refresh
      DR_SwipeOutException1.Show
      DE_SwipeOutException1.rsSwipeOutException1.Close
    ElseIf UCase(ReportTitle) = "SWIPEOUTEXCEPTION1" Then
      'Check for records in CheckOutException Table and put the data in Daily Hire if possible
      Call UpdateTimeOut(2)
      mySQL = "Select max(a.Time_Out) as Time_Out, a.employee_id, b.Employee_Name, a.Hire_date from checkoutexception a, Employee b where a.Hire_Date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and a.Hire_date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy') and a.Employee_ID = b.Employee_ID group by a.Hire_date, a.employee_id, b.Employee_Name"
      DE_SwipeOutException1.rsSwipeOutException1.Source = mySQL
      DE_SwipeOutException1.rsSwipeOutException1.Open
      DR_SwipeOutException1.Refresh
      DR_SwipeOutException1.Show
      DE_SwipeOutException1.rsSwipeOutException1.Close
    ElseIf UCase(ReportTitle) = "SWIPEOUTEXCEPTION2" Then
      'Check for records in CheckOutException Table and put the data in Daily Hire if possible
      Call UpdateTimeOut(2)
      mySQL = "Select a.Hire_Date, a.Employee_ID, b.Employee_Name, a.Time_In from daily_hire_list a, Employee b where a.Hire_Date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and a.Hire_Date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy') and a.Time_Out is Null and a.Employee_ID = b.Employee_ID"
      DE_SwipeOutException2.rsSwipeOutException2.Source = mySQL
      DE_SwipeOutException2.rsSwipeOutException2.Open
      DR_SwipeOutException2.Refresh
      DR_SwipeOutException2.Show
      DE_SwipeOutException2.rsSwipeOutException2.Close
    ElseIf UCase(ReportTitle) = "TIMEIN" Then
      mySQL = "Select to_char(a.start_time,'hh:mi am') " + """Start Time""" + ", to_char(b.Time_In,'hh:mi am') " + """Time In""" + ", a.Employee_ID, a.Hire_Date, c.Employee_Name" + " from hourly_detail a, daily_hire_list b , Employee c"
      mySQL = mySQL + " Where a.hire_date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and a.hire_date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy')"
      mySQL = mySQL + " and b.hire_date = a.hire_date"
      mySQL = mySQL + " and a.employee_id = b.employee_id and a.employee_id = c.employee_id"
      mySQL = mySQL + " and a.Row_Number <= (select min(Row_Number) from hourly_detail"
      mySQL = mySQL + " where hire_date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and hire_date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy')) and to_char(a.start_time,'hh:mi am') <> to_char(b.Time_In,'hh:mi am')"
      DE_TimeInException.rsTimeInException.Source = mySQL
      DE_TimeInException.rsTimeInException.Open
      DR_TimeInException.Refresh
      DR_TimeInException.Show
      DE_TimeInException.rsTimeInException.Close
    ElseIf UCase(ReportTitle) = "DOUBLEENTRY" Then
      Me.Hide
      DoubleEntryReportFromTo
    End If
  End If
  
  Call MouseNormal
  Exit Sub
ErrHandler:
  If Err.Number = 3705 Then
    MsgBox "Failure in Opening the Report. Please try later.", vbInformation, "General Failure"
    Err.Clear
    If UCase(ReportTitle) = "TIMEOUT" Then
      DE_TimeOutException.rsTimeOutException.Close
    ElseIf UCase(ReportTitle) = "TIMEIN" Then
      DE_TimeInException.rsTimeInException.Close
    ElseIf UCase(ReportTitle) = "SWIPEOUTEXCEPTION1" Then
      DE_SwipeOutException1.rsSwipeOutException1.Close
    ElseIf UCase(ReportTitle) = "SWIPEOUTEXCEPTION2" Then
      DE_SwipeOutException2.rsSwipeOutException2.Close
    ElseIf UCase(ReportTitle) = "CHECKOUTEXCEPTION" Then
      DE_SwipeOutException1.rsSwipeOutException1.Close
      'DE_CheckOutException.rsCheckOutException.Close        '5/2/2007 HD2759 Rudy:  not in scope (cut copy paste error?)
    End If
  End If
End Sub

Private Sub Form_Load()
    Dim lsunday As Date
    lsunday = findLastSunday(Date)
    
    Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
    'Center the Form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    ssdtcboFrom.Text = Format(lsunday - 6, "mm/dd/yy")
    ssdtcboTo.Text = Format(lsunday, "mm/dd/yy")
End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  If UCase(ReportTitle) = "TIMESHEET" Then
    frmHiring.Show
    Unload Me
  Else
    frmException.Show
    Unload Me
  End If
End Sub

'****************************************
'To Enable / Disable Controls based on Selection Criteria
'****************************************
Private Sub optSelectCrit_Click(Index As Integer)
  If Index = 0 Then
    ssdtcboDate.Enabled = True
    ssdtcboFrom.Enabled = False
    ssdtcboTo.Enabled = False
  ElseIf Index = 1 Then
    ssdtcboFrom.Enabled = True
    ssdtcboTo.Enabled = True
    ssdtcboDate.Enabled = False
  End If
End Sub

'****************************************
'To Display Double Entry Report for Particular Date
'****************************************
Private Sub DoubleEntryReportDate()
  Dim myStart As Date, myEnd As Date, DblRS As Object, DblEmpRS As Object
  Dim Total As Integer, i As Integer, arrPrintFlag() As Boolean
  Dim myEmpIDSQL As String, myHireSQL As String, AlreadyPrinted As Boolean
  Dim SuprName2 As String, mySupr As String
  Dim j As Integer
 
  'Print the Report Header Section
  frmDblEntryRpt.rteDblRpt.Text = vbCrLf + String(81, "_") + vbCrLf
  frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "DIAMOND STATE PORT CORPORATION" + vbCrLf
  frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + String(14, " ") + "DOUBLE ENTRY EXCEPTION REPORT   " + Str(Date) + vbCrLf
  frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + String(80, "_") + vbCrLf
  frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "EMPLOYEES WHOSE START AND END TIME OVERLAP" + vbCrLf + vbCrLf
    
  'Select all the Employees hired for the day
  myEmpIDSQL = "Select distinct a.Employee_ID from Hourly_Detail a, Daily_Hire_List b where a.Employee_ID = b.Employee_ID and a.Hire_date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy') and b.Hire_date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy')"
  Set DblEmpRS = OraDatabase.DBCreateDynaset(myEmpIDSQL, 0&)
  If DblEmpRS.BOF And DblEmpRS.EOF Then
  'Do Nothing
  Else
    DblEmpRS.MoveFirst
    'For each Employee, check whether Double Entry is made
    Do While Not DblEmpRS.EOF
      AlreadyPrinted = False    'Print the Group Header for Each Employee
      'Get the details from Hourly_detail for the employee
      myHireSQL = "Select a.Hire_Date, a.Start_Time, a.End_Time, a.Row_Number, a.User_ID, a.Employee_ID, b.Employee_Name from Hourly_Detail a, Employee b where a.Hire_Date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy') and a.Employee_ID = '" + DblEmpRS.Fields("Employee_ID").Value + "' and a.Employee_ID = b.Employee_ID Order by a.Row_Number"
      Set DblRS = OraDatabase.DBCreateDynaset(myHireSQL, 0&)
      If DblRS.BOF And DblRS.EOF Then
        'Do Nothing
      Else
        Dim myEmpID As String
        myEmpID = DblRS.Fields("Employee_ID").Value
        'Do not update Row number ...RW 1/17/2002
        'Update Row Number
        Call UpdateRowNo(myEmpID, Format(DblRS.Fields("hire_date").Value, "MM/DD/YYYY"))
        DblRS.Refresh     'Refresh recordset so that the Row Number gets updated
        'Get the Start and End Time and Check for Double Entry
        DblRS.MoveLast
        Total = DblRS.RecordCount
        ReDim arrPrintFlag(Total) As Boolean
        For i = 1 To Total
          DblRS.MoveTo i
          If IsNull(DblRS.Fields("Start_Time").Value) Then
             ' End time is null we will end up skipping this record
          Else
             myStart = DblRS.Fields("Start_Time").Value
          End If
          If IsNull(DblRS.Fields("End_Time").Value) Then
             ' End time is null we will end up skipping this record
          Else
             myEnd = DblRS.Fields("End_Time").Value
          End If
          mySupr = DblRS.Fields("User_ID").Value
          DblRS.MoveFirst
          j = 1
          Do While Not DblRS.EOF
            If i = j Or IsNull(DblRS.Fields("End_Time").Value) Or IsNull(DblRS.Fields("Start_Time").Value) Then
              'Proceed with the remaining records
              DblRS.MoveNext
              If DblRS.EOF Then Exit Do
              ElseIf myStart >= DblRS.Fields("Start_Time").Value And myStart < DblRS.Fields("End_Time").Value And arrPrintFlag(j) = False Then
              'Get the User Name to be printed
              Dim SupRS As Object, SuprName As String
              Set SupRS = OraDatabase.DBCreateDynaset("Select * from LCS_User where User_ID = '" + DblRS.Fields("User_ID").Value + "'", 0&)
              If SupRS.BOF And SupRS.EOF Then
                SuprName = Space(16)
              Else
                SuprName = SupRS.Fields("User_Name").Value
                'If Overlapping between two Supervisors
                If UCase(Trim(mySupr)) <> UCase(Trim(SuprName)) Then
                  Set SupRS = OraDatabase.DBCreateDynaset("Select * from LCS_USER where User_ID = '" + mySupr + "'", 0&)
                  If SupRS.BOF And SupRS.EOF Then
                    SuprName2 = Space(16)
                  Else
                    SuprName2 = SupRS.Fields("User_Name").Value
                  End If
                  SupRS.Close
                  Set SupRS = Nothing
                End If
              End If
              If AlreadyPrinted = False Then
                'Print the Group Header Section
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + vbCrLf + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + DblRS.Fields("Employee_Name").Value + "     " + DblRS.Fields("Employee_ID").Value + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + String(56, "_") + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + "DATE          START               END                   SUPR " + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + String(56, "_") + vbCrLf
                AlreadyPrinted = True
              End If
              'Print the Details Section
              frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + ssdtcboDate.Text + "      " + Format(Str(myStart), "hh:nn am/pm") + "           " + Format(Str(myEnd), "hh:nn am/pm") + "           " + SuprName2 + vbCrLf
              frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + ssdtcboDate.Text + "      " + Format(Str(DblRS.Fields("Start_Time").Value), "hh:nn am/pm") + "           " + Format(Str(DblRS.Fields("End_Time").Value), "hh:nn am/pm") + "           " + SuprName + vbCrLf
              frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + String(56, "_") + vbCrLf
              arrPrintFlag(i) = True
              DblRS.MoveNext
              If DblRS.EOF Then Exit Do
            Else
              DblRS.MoveNext
              If DblRS.EOF Then Exit Do
            End If
            j = j + 1
          Loop
        Next
      End If
      DblRS.Close
      Set DblRS = Nothing
      DblEmpRS.MoveNext
    Loop
  End If
  'Set Font for Header Section
  frmDblEntryRpt.rteDblRpt.SelStart = 83
  frmDblEntryRpt.rteDblRpt.SelLength = 32
  frmDblEntryRpt.rteDblRpt.SelAlignment = 2
  frmDblEntryRpt.rteDblRpt.SelFontSize = 16

  frmDblEntryRpt.rteDblRpt.SelStart = 117
  frmDblEntryRpt.rteDblRpt.SelLength = 56
  frmDblEntryRpt.rteDblRpt.SelAlignment = 2
  frmDblEntryRpt.rteDblRpt.SelFontSize = 12

  frmDblEntryRpt.rteDblRpt.SelStart = 253
  frmDblEntryRpt.rteDblRpt.SelLength = 50
  frmDblEntryRpt.rteDblRpt.SelAlignment = 2
  frmDblEntryRpt.rteDblRpt.SelFontSize = 11
  
  frmDblEntryRpt.rteDblRpt.SelStart = 300
  frmDblEntryRpt.rteDblRpt.SelLength = Len(frmDblEntryRpt.rteDblRpt.Text)
  frmDblEntryRpt.rteDblRpt.SelFontSize = 10
  
  frmDblEntryRpt.rteDblRpt.SelStart = 0
  frmDblEntryRpt.rteDblRpt.SelLength = 0
  
  Me.Hide
  frmDblEntryRpt.Show
  
  DblEmpRS.Close
  Set DblEmpRS = Nothing

  'DblRS.Close
  'Set DblRS = Nothing
End Sub

'****************************************
'To Display Double Entry Report between two specified Dates
'****************************************
Private Sub DoubleEntryReportFromTo()
Dim myStart As Date, myEnd As Date, DblRS As Object, DblEmpRS As Object
Dim Total As Integer, i As Integer, arrPrintFlag() As Boolean
Dim myEmpIDSQL As String, myHireSQL As String, AlreadyPrinted As Boolean
Dim j As Integer

'5/2/2007 HD2759 Rudy:
Dim mySupr As String
Dim SuprName2 As String

  'Print the Report Header Section
  frmDblEntryRpt.rteDblRpt.Text = vbCrLf + String(81, "_") + vbCrLf
  frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "DIAMOND STATE PORT CORPORATION" + vbCrLf
  frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + String(14, " ") + "DOUBLE ENTRY EXCEPTION REPORT   " + Str(Date) + vbCrLf
  frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + String(80, "_") + vbCrLf
  frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "EMPLOYEES WHOSE START AND END TIME OVERLAP" + vbCrLf + vbCrLf
  
  'Select all the Employees hired for the day
  myEmpIDSQL = "Select distinct a.Employee_ID, a.Hire_Date from Hourly_Detail a, Daily_Hire_List b where a.Employee_ID = b.Employee_ID and a.Hire_date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and a.Hire_date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy') and b.Hire_date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and b.Hire_date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy')"
  Set DblEmpRS = OraDatabase.DBCreateDynaset(myEmpIDSQL, 0&)
  If DblEmpRS.BOF And DblEmpRS.EOF Then
  'Do Nothing
  Else
    DblEmpRS.MoveFirst
    'For each Employee, check whether Double Entry is made
    Do While Not DblEmpRS.EOF
      AlreadyPrinted = False    'Print the Group Header for Each Employee
      'Get the details from Hourly_detail for the employee
      myHireSQL = "Select a.Start_Time, a.End_Time, a.Row_Number, a.User_ID, a.Hire_Date, a.Employee_ID, b.Employee_Name from Hourly_Detail a, Employee b where a.Hire_Date = to_date('" & Format(DblEmpRS.Fields("Hire_Date").Value, "mm/dd/yyyy") & "','mm/dd/yyyy') and a.Employee_ID = '" + DblEmpRS.Fields("Employee_ID").Value + "' and a.Employee_ID = b.Employee_ID Order by a.Row_Number"
      ' Changed by Bruce LeBrun 04/13/2000 - change SQL statement to ignore incomplete records.
      'myHireSQL = "Select a.Start_Time, a.End_Time, a.Row_Number, a.User_ID, a.Hire_Date, a.Employee_ID, b.Employee_Name from Hourly_Detail a, Employee b where a.Hire_Date = to_date('" & Format(DblEmpRS.Fields("Hire_Date").Value, "mm/dd/yyyy") & "','mm/dd/yyyy') and a.Employee_ID = '" + DblEmpRS.Fields("Employee_ID").Value + "' and a.Employee_ID = b.Employee_ID and not (a.end_time is null) Order by a.Row_Number"
      Set DblRS = OraDatabase.DBCreateDynaset(myHireSQL, 0&)
      If DblRS.BOF And DblRS.EOF Then
        'Do Nothing
      Else
        'Update Row No
        Dim myEmpID As String
        myEmpID = DblRS.Fields("Employee_ID").Value
        
        Call UpdateRowNo(myEmpID, Format(DblRS.Fields("hire_date").Value, "MM/DD/YYYY"))   'don't update row_number RW 1/17/2002
        DblRS.Refresh   'Refresh recordset so that the Row Number gets updated
        
        'Get the Start and End Time and Check for Double Entry
        DblRS.MoveLast
        Total = DblRS.RecordCount
        ReDim arrPrintFlag(Total) As Boolean
        For i = 1 To Total
          DblRS.MoveTo i
          If IsNull(DblRS.Fields("Start_Time").Value) Then
             ' End time is null we will end up skipping this record
          Else
             myStart = DblRS.Fields("Start_Time").Value
          End If
          If IsNull(DblRS.Fields("End_Time").Value) Then
             ' End time is null we will end up skipping this record
          Else
             myEnd = DblRS.Fields("End_Time").Value
          End If
          mySupr = DblRS.Fields("User_ID").Value
          DblRS.MoveFirst
          j = 1
          Do While Not DblRS.EOF
            If i = j Or IsNull(DblRS.Fields("End_Time").Value) Or IsNull(DblRS.Fields("Start_Time").Value) Then
              'Proceed with the remaining records
              DblRS.MoveNext
              If DblRS.EOF Then Exit Do
            ElseIf myStart >= DblRS.Fields("Start_Time").Value And myStart < DblRS.Fields("End_Time").Value And arrPrintFlag(j) = False Then
             'Get the User Name to be printed
              Dim SupRS As Object, SuprName As String
              Set SupRS = OraDatabase.DBCreateDynaset("Select * from LCS_User where User_ID = '" + DblRS.Fields("User_ID").Value + "'", 0&)
              If SupRS.BOF And SupRS.EOF Then
                SuprName = Space(16)
              Else
                SuprName = SupRS.Fields("User_Name").Value
                'If Overlapping between two Supervisors
                If UCase(Trim(mySupr)) <> UCase(Trim(SuprName)) Then
                  Set SupRS = OraDatabase.DBCreateDynaset("Select * from LCS_USER where User_ID = '" + mySupr + "'", 0&)
                  If SupRS.BOF And SupRS.EOF Then
                    SuprName2 = Space(16)
                  Else
                    SuprName2 = SupRS.Fields("User_Name").Value
                  End If
                End If
                SupRS.Close
                Set SupRS = Nothing
              End If
              If AlreadyPrinted = False Then
                'Print the Group Header Section
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + vbCrLf + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + DblRS.Fields("Employee_Name").Value + "     " + DblRS.Fields("Employee_ID").Value + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + String(56, "_") + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + "DATE          START               END                   SUPR " + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + String(56, "_") + vbCrLf
                AlreadyPrinted = True
              End If
              'Print the Details Section
              frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + Str(DblRS.Fields("Hire_Date").Value) + "      " + Format(Str(myStart), "hh:nn am/pm") + "           " + Format(Str(myEnd), "hh:nn am/pm") + "           " + SuprName2 + vbCrLf
              frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + Str(DblRS.Fields("Hire_Date").Value) + "      " + Format(Str(DblRS.Fields("Start_Time").Value), "hh:nn am/pm") + "           " + Format(Str(DblRS.Fields("End_Time").Value), "hh:nn am/pm") + "           " + SuprName + vbCrLf
              frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + String(56, "_") + vbCrLf
              arrPrintFlag(i) = True
              DblRS.MoveNext
              If DblRS.EOF Then Exit Do
            Else
              DblRS.MoveNext
              If DblRS.EOF Then Exit Do
            End If
            j = j + 1
          Loop
        Next
      End If
      DblEmpRS.MoveNext
      
      DblRS.Close
      Set DblRS = Nothing
    Loop
  End If
  'Set Font for Header Section
  frmDblEntryRpt.rteDblRpt.SelStart = 83
  frmDblEntryRpt.rteDblRpt.SelLength = 32
  frmDblEntryRpt.rteDblRpt.SelAlignment = 2
  frmDblEntryRpt.rteDblRpt.SelFontSize = 16

  frmDblEntryRpt.rteDblRpt.SelStart = 117
  frmDblEntryRpt.rteDblRpt.SelLength = 56
  frmDblEntryRpt.rteDblRpt.SelAlignment = 2
  frmDblEntryRpt.rteDblRpt.SelFontSize = 12
  
  frmDblEntryRpt.rteDblRpt.SelStart = 253
  frmDblEntryRpt.rteDblRpt.SelLength = 50
  frmDblEntryRpt.rteDblRpt.SelAlignment = 2
  frmDblEntryRpt.rteDblRpt.SelFontSize = 11
  
  frmDblEntryRpt.rteDblRpt.SelStart = 300
  frmDblEntryRpt.rteDblRpt.SelLength = Len(frmDblEntryRpt.rteDblRpt.Text)
  frmDblEntryRpt.rteDblRpt.SelFontSize = 10
  
  frmDblEntryRpt.rteDblRpt.SelStart = 0
  frmDblEntryRpt.rteDblRpt.SelLength = 0
  
  Me.Hide
  frmDblEntryRpt.Show
  
  DblEmpRS.Close
  Set DblEmpRS = Nothing
  'DblRS.Close
  'Set DblRS = Nothing
End Sub

'****************************************
'To Update the Row Number in Order
'****************************************
Private Sub UpdateRowNo(myEmpID As String, hire_date As String)
  Dim myRecNo As Integer, myRowRS As Object
  Set myRowRS = OraDatabase.DBCreateDynaset("Select Row_Number from Hourly_Detail where Upper(Employee_ID) = '" + UCase(Trim(myEmpID)) + "' and Hire_Date = to_date('" + hire_date + "','mm/dd/yyyy')", 0&)
  myRowRS.MoveFirst
  myRecNo = 1
  Do While Not myRowRS.EOF
    If myRowRS.Fields("Row_Number").Value <> myRecNo Then
      myRowRS.Edit
      myRowRS.Fields("Row_Number").Value = myRecNo
      myRowRS.Update
    End If
    myRowRS.MoveNext
    myRecNo = myRecNo + 1
  Loop
  myRowRS.Close
  Set myRowRS = Nothing
End Sub


Private Sub UpdateTimeOut(DateOption As Integer)
  Dim mySQL As String, UpdateRS As Object, DHRS As Object, myrec As Integer
  If DateOption = 1 Then  'Only Particular Date is Selected
    'Get the data from CheckOutException Table for this date
    mySQL = "Select * from CheckOutException where Hire_Date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy')"
    Set UpdateRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
    If UpdateRS.EOF And UpdateRS.BOF Then
    'Do Nothing
    Else
      UpdateRS.MoveFirst
      Do While Not UpdateRS.EOF
        'Get the Employee ID; Check in Daily Hire Table
        mySQL = "Select * from Daily_Hire_List where Upper(Employee_ID) = '" + UCase(UpdateRS.Fields("Employee_ID").Value) + "' and Hire_Date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy')"
        Set DHRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
        If DHRS.EOF And DHRS.BOF Then
          'Do Nothing - no updating Required
        Else
          'Only One Record for a Day in Daily Hire Table for that Employee
          DHRS.MoveFirst
          'Update the TIME OUT Column from CHECKOUTEXCEPTION to DAILY_HIRE_LIST Table
          OraSession.BeginTrans
          DHRS.Edit
          DHRS.Fields("Time_Out").Value = UpdateRS.Fields("Time_Out").Value
          DHRS.Update
          If OraDatabase.LastServerErr = 0 Then
            OraSession.CommitTrans
            'Delete from CHECKOUTEXCEPTION Table AFTER Updating
            OraSession.BeginTrans
            mySQL = "Delete from CheckOutException where Hire_Date = to_date('" + Format(Str(UpdateRS.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy') and Upper(Employee_ID) = '" + UCase(UpdateRS.Fields("Employee_ID").Value) + "'"
            myrec = OraDatabase.ExecuteSQL(mySQL)
            If OraDatabase.LastServerErr = 0 Then
              OraSession.CommitTrans
            Else
              OraSession.Rollback
            End If
          Else
            OraSession.Rollback
          End If
        End If
        UpdateRS.MoveNext
      Loop
    End If
  ElseIf DateOption = 2 Then  'Both From and To Dates are Selected
    'Get all the data from CheckOutException Table
    mySQL = "Select * from CheckOutException where Hire_Date >= to_date('" + ssdtcboFrom.Text + "','mm/dd/yyyy') and Hire_Date <= to_date('" + ssdtcboTo.Text + "','mm/dd/yyyy')"
    Set UpdateRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
    If UpdateRS.EOF And UpdateRS.BOF Then
    'Do Nothing
    Else
      UpdateRS.MoveFirst
      Do While Not UpdateRS.EOF
        'Get the Employee ID; Check in Daily Hire Table
        mySQL = "Select * from Daily_Hire_List where Upper(Employee_ID) = '" + UCase(UpdateRS.Fields("Employee_ID").Value) + "' and Hire_Date = to_date('" + Format(Str(UpdateRS.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        Set DHRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
        If DHRS.EOF And DHRS.BOF Then
          'Do Nothing - no updating Required
        Else
          'Only One Record for a Day in Daily Hire Table for that Employee
          DHRS.MoveFirst
          'Update the TIME OUT Column from CHECKOUTEXCEPTION to DAILY_HIRE_LIST Table
          OraSession.BeginTrans
          DHRS.Edit
          DHRS.Fields("Time_Out").Value = UpdateRS.Fields("Time_Out").Value
          DHRS.Update
          If OraDatabase.LastServerErr = 0 Then
            OraSession.CommitTrans
            'Delete from CHECKOUTEXCEPTION Table AFTER Updating
            OraSession.BeginTrans
            mySQL = "Delete from CheckOutException where Hire_Date = to_date('" + Format(Str(UpdateRS.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy') and Upper(Employee_ID) = '" + UCase(UpdateRS.Fields("Employee_ID").Value) + "'"
            myrec = OraDatabase.ExecuteSQL(mySQL)
            If OraDatabase.LastServerErr = 0 Then
              OraSession.CommitTrans
            Else
              OraSession.Rollback
            End If
          Else
            OraSession.Rollback
          End If
        End If
        UpdateRS.MoveNext
      Loop
    End If
  End If
End Sub

