VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Begin VB.Form frmSelectDate 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Selection Criteria"
   ClientHeight    =   4140
   ClientLeft      =   4965
   ClientTop       =   3570
   ClientWidth     =   6735
   Icon            =   "frmSelectDate.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   4140
   ScaleWidth      =   6735
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
      Left            =   3720
      TabIndex        =   1
      ToolTipText     =   "Return Back"
      Top             =   3480
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
      Left            =   810
      TabIndex        =   0
      ToolTipText     =   "Show Report for the Selected Date"
      Top             =   3480
      Width           =   2295
   End
   Begin SSCalendarWidgets_A.SSDateCombo ssdtcboDate 
      Height          =   375
      Left            =   3225
      TabIndex        =   3
      ToolTipText     =   "Select Date"
      Top             =   1920
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
   Begin Crystal.CrystalReport crw1 
      Left            =   90
      Top             =   3510
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      WindowState     =   2
      PrintFileLinesPerPage=   60
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   285
      Left            =   0
      TabIndex        =   5
      Top             =   4230
      Width           =   6675
   End
   Begin VB.Label Label1 
      Alignment       =   1  'Right Justify
      Caption         =   "Select Date"
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
      Left            =   1316
      TabIndex        =   4
      Top             =   1920
      Width           =   1575
   End
   Begin VB.Line Line2 
      X1              =   -240
      X2              =   6720
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
      TabIndex        =   2
      Top             =   0
      Width           =   5655
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
      Picture         =   "frmSelectDate.frx":0442
   End
End
Attribute VB_Name = "frmSelectDate"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iCount As Long
Dim DoNotPrint As Boolean
Dim iError As Boolean

'Dim holiday_date As String
  
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
    Dim start_date As String
    Dim end_date As String
  
    DoNotPrint = False
    
    If UCase(ReportTitle) = "HIREEXP" Then
        'Current Pay Period is InValid. Check for the Date selected as Pay Period
        Dim WeekNo As Integer, WeekToday As Integer, CurrDay As Integer, SelectDay As Integer
        WeekNo = Format(ssdtcboDate.Text, "ww")
        SelectDay = Format(ssdtcboDate.Text, "w")
        If SelectDay = 1 Then WeekNo = WeekNo - 1
        CurrDay = Format(Date, "w")
        If CurrDay = 1 Then
          WeekToday = Format(Date, "ww") - 1
        Else
          WeekToday = Format(Date, "ww")
        End If
        If WeekNo = WeekToday Or Format(ssdtcboDate.Text, "mm/dd/yyyy") > Format(Date, "mm/dd/yyyy") Then
          MsgBox "Exception Report can't be generated for Current or Upcoming Pay Period. " + vbCrLf + "Please try previous Pay Periods.", vbInformation, "Date Invalid"
          Exit Sub
        End If
        
        crw1.LogOnServer "PDSORA7", "LCS", "", "LABOR", "LABOR"   'ODBCDriver, ServerName, Database, UserName, Password
        'crw1.Connect = "DSN = LCS;UID = LABOR;PWD = LABOR"
        crw1.ReportFileName = App.Path + "\HireExp.rpt"
        crw1.DiscardSavedData = True    'To refresh the data
        'crw1.SelectionFormula = "{DAILY_HIRE_LIST.HIRE_DATE} =" + "date(" + Str(Year(ssdtcboDate.Text)) + "," + Str(Month(ssdtcboDate.Text)) + "," + Str(Day(ssdtcboDate.Text)) + ")"
        'crw1.SelectionFormula = "{DAILY_HIRE_LIST.EMPLOYEE_ID} NOT IN (SELECT {HOURLY_DETAIL.EMPLOYEE_ID} FROM HOURLY_DETAIL WHERE {HOURLY_DETAIL.HIRE_DATE} = " + "date(" + Str(Year(ssdtcboDate.Text)) + "," + Str(Month(ssdtcboDate.Text)) + "," + Str(Day(ssdtcboDate.Text)) + ")"
        crw1.SQLQuery = "Select Daily_Hire_List.Hire_Date, Daily_Hire_List.Time_IN, Daily_Hire_List.Time_Out, Daily_Hire_List.Employee_ID, Employee.Employee_Name, Lcs_User.User_Name from Daily_Hire_List , Employee, Lcs_User Where Daily_Hire_List.Employee_ID = Employee.Employee_ID and Daily_Hire_List.User_ID = Lcs_User.User_ID and Daily_Hire_List.Hire_Date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy') and Daily_Hire_List.Employee_ID NOT IN (Select Employee_ID from Hourly_Detail where Hire_Date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy'))"
        crw1.Formulas(0) = "DtHead = '" + ssdtcboDate.Text + "'"
        crw1.Action = 1
    ElseIf UCase(ReportTitle) = "TIMESHEET" Then
        'get first day(Monday) and last day (Sunday)of week from oracle
        Dim sqlStmt As String
        Dim dayRS As Object
        sqlStmt = "SELECT TRUNC(TO_DATE('" & ssdtcboDate.Text & "', 'MM/DD/YYYY'), 'IW') START_DATE, TRUNC(TO_DATE('" & ssdtcboDate.Text & "', 'MM/DD/YYYY'), 'IW')+6 END_DATE FROM DUAL"
        Set dayRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        If Not dayRS.EOF Then
            start_date = Format(dayRS.Fields("start_date").Value, "mm/dd/yyyy")
            end_date = Format(dayRS.Fields("end_date").Value, "mm/dd/yyyy")
        End If
        dayRS.Close
        Set dayRS = Nothing
        
        If DoNotPrint = False And Not iError Then
            'crw1.LogOnServer "PDSORA7", "LCS", "", "LABOR", "LABOR"   'ODBCDriver, ServerName, Database, UserName, Password
            crw1.Connect = "DSN = LCS;UID = LABOR;PWD = LABOR"
            crw1.ReportFileName = App.Path + "\TimeSheet4.rpt"
            crw1.DiscardSavedData = True
            crw1.SelectionFormula = "{HOURLY_DETAIL.HIRE_DATE} >=" + "date(" + Str(Year(start_date)) + "," + Str(Month(start_date)) + "," + Str(day(start_date)) + ") and {HOURLY_DETAIL.HIRE_DATE} <=" + "date(" + Str(Year(end_date)) + "," + Str(Month(end_date)) + "," + Str(day(end_date)) + ")"
            'crw1.Formulas(0) = "DtHead = '" + Str(print_date) + "'"
            crw1.Action = 1
        End If
    ElseIf UCase(ReportTitle) = "SUPERVISORWEEKLYREPORT" Then
        Dim sqlStmt1 As String
        Dim dayRS1 As Object
        sqlStmt1 = "SELECT TRUNC(TO_DATE('" & ssdtcboDate.Text & "', 'MM/DD/YYYY'), 'IW') START_DATE, TRUNC(TO_DATE('" & ssdtcboDate.Text & "', 'MM/DD/YYYY'), 'IW')+6 END_DATE FROM DUAL"
        Set dayRS1 = OraDatabase.DBCreateDynaset(sqlStmt1, 0&)
        If Not dayRS1.EOF Then
            start_date = Format(dayRS1.Fields("start_date").Value, "mm/dd/yyyy")
            end_date = Format(dayRS1.Fields("end_date").Value, "mm/dd/yyyy")
        End If
        dayRS1.Close
        Set dayRS1 = Nothing
        
        If DoNotPrint = False And Not iError Then
            'crw1.LogOnServer "PDSORA7", "LCS", "", "LABOR", "LABOR"   'ODBCDriver, ServerName, Database, UserName, Password
            crw1.Connect = "DSN = LCS;UID = LABOR;PWD = LABOR"
            crw1.ReportFileName = App.Path + "\SupvTimeSheet.rpt"
            crw1.DiscardSavedData = True
            crw1.SelectionFormula = "{HOURLY_DETAIL.HIRE_DATE} >=" + "date(" + Str(Year(start_date)) + "," + Str(Month(start_date)) + "," + Str(day(start_date)) + ") and {HOURLY_DETAIL.HIRE_DATE} <=" + "date(" + Str(Year(end_date)) + "," + Str(Month(end_date)) + "," + Str(day(end_date)) + ") and {HOURLY_DETAIL.EMPLOYEE_ID}='" + UserID + "'"
            crw1.Formulas(0) = "DtHead = '" + end_date + "'"
            crw1.Action = 1
        End If
    ElseIf UCase(ReportTitle) = "DAYCLOSE" Then
        crw1.LogOnServer "PDSORA7", "LCS", "", "LABOR", "LABOR"   'ODBCDriver, ServerName, Database, UserName, Password
        'crw1.Connect = "DSN = LCS;UID = LABOR;PWD = LABOR"
        crw1.ReportFileName = App.Path + "\DayClose.rpt"
        crw1.DiscardSavedData = True
        crw1.SelectionFormula = "{HOURLY_DETAIL.HIRE_DATE} =" + "date(" + Str(Year(ssdtcboDate.Text)) + "," + Str(Month(ssdtcboDate.Text)) + "," + Str(day(ssdtcboDate.Text)) + ")"
        crw1.Formulas(0) = "DtHead = '" + ssdtcboDate.Text + "'"
        crw1.Action = 1
    End If
End Sub

Private Sub Form_Load()
    Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
    'Center the Form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    'Default Date is Previous Week from Now
    ssdtcboDate.Text = Format(findLastSunday(Date), "mm/dd/yyyy")
    lblStatus.Caption = "OK"
End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
    If UCase(ReportTitle) = "HIREEXP" Or UCase(ReportTitle) = "DAYCLOSE" Then
      frmException.Show
      Unload Me
    ElseIf UCase(ReportTitle) = "TIMESHEET" Or UCase(ReportTitle) = "SUPERVISORWEEKLYREPORT" Then
      frmHiring.Show
      Unload Me
    End If
End Sub


