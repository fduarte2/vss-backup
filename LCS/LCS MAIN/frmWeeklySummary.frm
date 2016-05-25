VERSION 5.00
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Begin VB.Form frmWeeklySummary 
   Caption         =   "Weekly Summary Report"
   ClientHeight    =   4200
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   5880
   LinkTopic       =   "Form1"
   ScaleHeight     =   4200
   ScaleWidth      =   5880
   StartUpPosition =   3  'Windows Default
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
      Left            =   3090
      TabIndex        =   1
      ToolTipText     =   "Update Changes"
      Top             =   3240
      Width           =   2610
   End
   Begin VB.CommandButton cmdView 
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
      Left            =   210
      TabIndex        =   0
      ToolTipText     =   "Update Changes"
      Top             =   3240
      Width           =   2610
   End
   Begin SSCalendarWidgets_A.SSDateCombo ssdtcboDate 
      Height          =   375
      Left            =   1695
      TabIndex        =   2
      ToolTipText     =   "Select Date"
      Top             =   1920
      Width           =   2610
      _Version        =   65543
      _ExtentX        =   4604
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
   End
   Begin VB.Label Label2 
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
      Left            =   1695
      TabIndex        =   4
      Top             =   1440
      Width           =   1575
   End
   Begin VB.Line Line2 
      X1              =   90
      X2              =   5850
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
      Left            =   1050
      TabIndex        =   3
      Top             =   0
      Width           =   4695
   End
   Begin VB.Image Image1 
      BorderStyle     =   1  'Fixed Single
      Height          =   855
      Left            =   90
      Picture         =   "frmWeeklySummary.frx":0000
      Stretch         =   -1  'True
      Top             =   0
      Width           =   855
   End
End
Attribute VB_Name = "frmWeeklySummary"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
'Dim OraSession As Object
'Dim OraDatabase As Object
Dim myDate As Date

Private Sub cmdExit_Click()
  Unload Me
  frmHiring.Show
End Sub

Private Sub cmdView_Click()
  myDate = ssdtcboDate.Text
  Call main
End Sub

Private Sub Form_Load()
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  ssdtcboDate.Text = Format(findLastSunday(Date), "mm/dd/yyyy")
End Sub

Sub main()
  'CreateSession
  WeekSummary
  Shell "notepad.exe " + App.Path + "\WeekSumm.txt", 1
End Sub

'****************************************
'To Display Weekly Summary Report
'****************************************
Private Sub WeekSummary()
  Dim DblEmpRS As Object, myHrs As String, myEmpIDSQL As String, myEarn As String
  Dim totalHrs As Single, StrTotalHrs As String, EarnTy As String, WeeklyTotal As Single
  Dim MonHrs As Single, TueHrs As Single, WedHrs As Single, ThuHrs As Single
  Dim FriHrs As Single, SatHrs As Single, SunHrs As Single, GrandTotal As Single
  Dim strMonHrs As String, strTueHrs As String, strWedHrs As String, strThuHrs As String
  Dim strFriHrs As String, strSatHrs As String, strSunHrs As String
  Dim CurrDay As Date, WeekNo As Integer, myEmpID As String
  
  Dim sqlStmt As String
  Dim dayRS As Object
  Dim start_date As String
  Dim end_date As String
  
  'get first day(Monday) and last day (Sunday)of week from oracle
  sqlStmt = "SELECT TRUNC(TO_DATE('" & myDate & "', 'MM/DD/YYYY'), 'IW') START_DATE, TRUNC(TO_DATE('" & myDate & "', 'MM/DD/YYYY'), 'IW')+6 END_DATE FROM DUAL"
  Set dayRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
  If Not dayRS.EOF Then
      start_date = Format(dayRS.Fields("start_date").Value, "mm/dd/yyyy")
      end_date = Format(dayRS.Fields("end_date").Value, "mm/dd/yyyy")
  End If
  
  Open App.Path + "\WeekSumm.txt" For Output As #1
  'Print the Report Header Section
  Print #1, String(70, "_")
  Print #1, "                    DIAMOND STATE PORT CORPORATION"
  Print #1, String(14, " ") + "          WEEKLY SUMMARY REPORT     "
  Print #1, String(24, " ") + start_date + "--" + end_date
  Print #1, String(70, "_")
  Print #1, "                 SUMMARIZED WEEKLY HOURS FOR EMPLOYEES"
  
  'Print the Detail Header Section
  Print #1, ""
'  rteDblRpt.Text = rteDblRpt.Text + "     " + String(75, "_") + vbCrLf
  Print #1, String(70, "_")
  Print #1, "           MON   TUE   WED   THU   FRI   SAT   SUN   TOTAL   "
  Print #1, String(70, "_")
  CurrDay = Format(myDate, "w")
  
  
  'Select all the Employees hired for the day
  'myEmpIDSQL = "Select b.Employee_Name, a.Employee_ID, Sum(a.Duration) Sum , a.Earning_Type_ID, a.Hire_Date from Hourly_Detail a, Employee  b where a.Employee_ID = b.Employee_ID AND a.Earning_Type_ID IS NOT NULL AND TO_CHAR(TRUNC(HIRE_DATE),'IW') =  " + Str(WeekNo) + " group by a.Employee_ID, b.Employee_Name, a.Hire_Date, a.Earning_Type_ID Order by a.Employee_Id,a.Earning_Type_ID"
  
  'myEmpIDSQL = "Select Employee_Name, Employee_ID, Sum(sum) Sum , Earning_Type_ID, Hire_Date from(("
  'myEmpIDSQL = myEmpIDSQL & " Select b.Employee_Name, a.Employee_ID, Sum(a.Duration) Sum , a.Earning_Type_ID, a.Hire_Date from Hourly_Detail a, Employee  b where a.Earning_Type_ID not in ('REG', 'ST') AND a.Employee_Id = b.Employee_Id And a.Earning_Type_ID Is Not Null AND a.hire_date>=to_date('" & start_date & "','mm/dd/yyyy') AND a.hire_date<=to_date('" & end_date & "','mm/dd/yyyy') group by a.Employee_ID, b.Employee_Name, a.Hire_Date, a.Earning_Type_ID )union all("
  'myEmpIDSQL = myEmpIDSQL & " Select b.Employee_Name, a.Employee_ID, Sum(a.Reg_hrs) Sum , a.Earning_Type_ID, a.Hire_Date from Hourly_Detail a, Employee  b where a.Earning_Type_ID in ('REG', 'ST') AND a.Employee_Id = b.Employee_Id And a.Earning_Type_ID Is Not Null AND a.hire_date>=to_date('" & start_date & "','mm/dd/yyyy') AND a.hire_date<=to_date('" & end_date & "','mm/dd/yyyy') group by a.Employee_ID, b.Employee_Name, a.Hire_Date, a.Earning_Type_ID )union all("
  'myEmpIDSQL = myEmpIDSQL & " Select b.Employee_Name, a.Employee_ID, Sum(a.OT_hrs) Sum , 'OT', a.Hire_Date from Hourly_Detail a, Employee  b where a.Earning_Type_ID in ('REG', 'ST') AND a.Employee_Id = b.Employee_Id And a.Earning_Type_ID Is Not Null AND a.hire_date>=to_date('" & start_date & "','mm/dd/yyyy') AND a.hire_date<=to_date('" & end_date & "','mm/dd/yyyy')group by a.Employee_ID, b.Employee_Name, a.Hire_Date, a.Earning_Type_ID ))"
  'myEmpIDSQL = myEmpIDSQL & " group by Employee_ID, Employee_Name, Hire_Date, Earning_Type_ID Having Sum(sum)> 0 Order by Employee_Id,Earning_Type_ID "
  
  'myEmpIDSQL = "Select Employee_Name, Employee_ID, Sum(sum) Sum, Earning_Type_ID, Hire_Date from (("
  'myEmpIDSQL = myEmpIDSQL & " Select b.Employee_Name, a.Employee_ID, Sum(a.Duration) Sum, a.Earning_Type_ID, a.Hire_Date from Hourly_Detail a, Employee b where a.Earning_Type_ID='REG' AND a.Employee_ID = b.Employee_ID AND a.hire_date >=to_date('" & start_date & "','mm/dd/yyyy') AND a.hire_date<=to_date('" & end_date & "','mm/dd/yyyy') group by a.Employee_ID, b.Employee_Name, a.Hire_Date, a.Earning_Type_ID )union all("
  'myEmpIDSQL = myEmpIDSQL & " Select b.Employee_Name, a.Employee_ID, Sum(a.Duration) Sum , a.Earning_Type_ID, a.Hire_Date from Hourly_Detail a, Employee  b where a.Earning_Type_ID = 'OT' AND a.Employee_Id = b.Employee_Id AND a.hire_date>=to_date('" & start_date & "','mm/dd/yyyy') AND a.hire_date<=to_date('" & end_date & "','mm/dd/yyyy') group by a.Employee_ID, b.Employee_Name, a.Hire_Date, a.Earning_Type_ID )union all("
  'myEmpIDSQL = myEmpIDSQL & " Select b.Employee_Name, a.Employee_ID, Sum(a.Duration) Sum , a.Earning_Type_ID, a.Hire_Date from Hourly_Detail a, Employee  b where a.Earning_Type_ID = 'DT' AND a.Employee_Id = b.Employee_Id AND a.hire_date>=to_date('" & start_date & "','mm/dd/yyyy') AND a.hire_date<=to_date('" & end_date & "','mm/dd/yyyy')group by a.Employee_ID, b.Employee_Name, a.Hire_Date, a.Earning_Type_ID ))"
  'myEmpIDSQL = myEmpIDSQL & " group by Employee_ID, Employee_Name, Hire_Date, Earning_Type_ID Having Sum(sum)> 0 Order by Employee_Id,Earning_Type_ID "
  
  myEmpIDSQL = " Select b.Employee_Name, a.Employee_ID, Sum(a.Duration) Sum , a.Earning_Type_ID, a.Hire_Date from Hourly_Detail a, Employee  b where a.Earning_Type_ID IS NOT NULL AND a.Employee_Id = b.Employee_Id AND a.hire_date>=to_date('" & start_date & "','mm/dd/yyyy') AND a.hire_date<=to_date('" & end_date & "','mm/dd/yyyy')group by a.Employee_ID, b.Employee_Name, a.Hire_Date, a.Earning_Type_ID Order by Employee_Id,Earning_Type_ID "
  Set DblEmpRS = OraDatabase.DBCreateDynaset(myEmpIDSQL, 0&)
  
  If DblEmpRS.BOF And DblEmpRS.EOF Then
  'Do Nothing
  Else
    DblEmpRS.MoveFirst
    myEmpID = DblEmpRS.Fields("Employee_ID").Value
    myEarn = DblEmpRS.Fields("Earning_Type_ID").Value
    'Print Employee ID and Employee Name
    Print #1, myEmpID + "  " + DblEmpRS.Fields("Employee_Name").Value

    Do While Not DblEmpRS.EOF
'    If myEmpID = " E300864" Then
'      MsgBox myEmpID
'    End If

      If myEmpID = DblEmpRS.Fields("Employee_ID").Value Then
        'Check for Earning Type
        If myEarn = DblEmpRS.Fields("Earning_Type_ID").Value Then
          'Find the Total Hours Worked for each day for each Earning Type
          Select Case UCase(Format(DblEmpRS.Fields("Hire_Date").Value, "ddd"))
          Case "MON"
            If IsNull(DblEmpRS.Fields("Sum").Value) Or Trim(DblEmpRS.Fields("Sum").Value) = vbNullString Then
              MonHrs = 0
            Else
              MonHrs = DblEmpRS.Fields("SUM").Value
              totalHrs = totalHrs + DblEmpRS.Fields("SUM").Value
            End If
          Case "TUE"
            If IsNull(DblEmpRS.Fields("Sum").Value) Or Trim(DblEmpRS.Fields("Sum").Value) = vbNullString Then
              TueHrs = 0
            Else
              totalHrs = totalHrs + DblEmpRS.Fields("SUM").Value
              TueHrs = DblEmpRS.Fields("SUM").Value
            End If
          
          Case "WED"
            If IsNull(DblEmpRS.Fields("Sum").Value) Or Trim(DblEmpRS.Fields("Sum").Value) = vbNullString Then
              WedHrs = 0
            Else
              totalHrs = totalHrs + DblEmpRS.Fields("SUM").Value
              WedHrs = DblEmpRS.Fields("SUM").Value
            End If
          Case "THU"
            If IsNull(DblEmpRS.Fields("Sum").Value) Or Trim(DblEmpRS.Fields("Sum").Value) = vbNullString Then
              ThuHrs = 0
            Else
              totalHrs = totalHrs + DblEmpRS.Fields("SUM").Value
              ThuHrs = DblEmpRS.Fields("SUM").Value
            End If
          Case "FRI"
            If IsNull(DblEmpRS.Fields("Sum").Value) Or Trim(DblEmpRS.Fields("Sum").Value) = vbNullString Then
              FriHrs = 0
            Else
              totalHrs = totalHrs + DblEmpRS.Fields("SUM").Value
              FriHrs = DblEmpRS.Fields("SUM").Value
            End If
          Case "SAT"
            If IsNull(DblEmpRS.Fields("Sum").Value) Or Trim(DblEmpRS.Fields("Sum").Value) = vbNullString Then
              SatHrs = 0
            Else
              totalHrs = totalHrs + DblEmpRS.Fields("SUM").Value
              SatHrs = DblEmpRS.Fields("SUM").Value
            End If
          Case "SUN"
            If IsNull(DblEmpRS.Fields("Sum").Value) Or Trim(DblEmpRS.Fields("Sum").Value) = vbNullString Then
              SunHrs = 0
            Else
              totalHrs = totalHrs + DblEmpRS.Fields("SUM").Value
              SunHrs = DblEmpRS.Fields("SUM").Value
            End If
          End Select
          DblEmpRS.MoveNext
        Else
          'Stuff HrsWorked with Spaces
          If Len(Trim(Str(MonHrs))) < 4 Then strMonHrs = Str(MonHrs) + Space(4 - Len(Trim(Str(MonHrs)))) Else strMonHrs = Str(MonHrs)
          If Len(Trim(Str(TueHrs))) < 4 Then strTueHrs = Str(TueHrs) + Space(4 - Len(Trim(Str(TueHrs)))) Else strTueHrs = Str(TueHrs)
          If Len(Trim(Str(WedHrs))) < 4 Then strWedHrs = Str(WedHrs) + Space(4 - Len(Trim(Str(WedHrs)))) Else strWedHrs = Str(WedHrs)
          If Len(Trim(Str(ThuHrs))) < 4 Then strThuHrs = Str(ThuHrs) + Space(4 - Len(Trim(Str(ThuHrs)))) Else strThuHrs = Str(ThuHrs)
          If Len(Trim(Str(FriHrs))) < 4 Then strFriHrs = Str(FriHrs) + Space(4 - Len(Trim(Str(FriHrs)))) Else strFriHrs = Str(FriHrs)
          If Len(Trim(Str(SatHrs))) < 4 Then strSatHrs = Str(SatHrs) + Space(4 - Len(Trim(Str(SatHrs)))) Else strSatHrs = Str(SatHrs)
          If Len(Trim(Str(SunHrs))) < 4 Then strSunHrs = Str(SunHrs) + Space(4 - Len(Trim(Str(SunHrs)))) Else strSunHrs = Str(SunHrs)
          WeeklyTotal = WeeklyTotal + totalHrs
          
          'Stuff TotalHrs with Spaces
          StrTotalHrs = Trim(Str(totalHrs))
          If Len(StrTotalHrs) < 5 Then StrTotalHrs = StrTotalHrs + Space(5 - Len(Trim(StrTotalHrs)))
     
          'Print the Details Section
          If Len(Trim(myEarn)) < 3 Then EarnTy = myEarn + Space(3 - Len(Trim(myEarn))) Else EarnTy = myEarn
          Print #1, EarnTy; Tab(12); strMonHrs + " " + strTueHrs + " " + strWedHrs + " " + strThuHrs + " " + strFriHrs + " " + strSatHrs + " " + strSunHrs + " " + StrTotalHrs + vbCrLf
          If IsNull(DblEmpRS.Fields("Earning_Type_ID").Value) Or Trim(DblEmpRS.Fields("Earning_Type_ID").Value) = vbNullString Then
            'Do Nothing
          Else
            myEarn = DblEmpRS.Fields("Earning_Type_ID").Value 'Assign the Next Earning Type ID
          End If
          totalHrs = 0
          MonHrs = 0
          TueHrs = 0
          WedHrs = 0
          ThuHrs = 0
          FriHrs = 0
          SatHrs = 0
          SunHrs = 0
        End If
        
      Else
        'Only One Earning  Type for the Employee
        'Stuff HrsWorked with Spaces
        If Len(Trim(Str(MonHrs))) < 4 Then strMonHrs = Str(MonHrs) + Space(4 - Len(Trim(Str(MonHrs)))) Else strMonHrs = Str(MonHrs)
        If Len(Trim(Str(TueHrs))) < 4 Then strTueHrs = Str(TueHrs) + Space(4 - Len(Trim(Str(TueHrs)))) Else strTueHrs = Str(TueHrs)
        If Len(Trim(Str(WedHrs))) < 4 Then strWedHrs = Str(WedHrs) + Space(4 - Len(Trim(Str(WedHrs)))) Else strWedHrs = Str(WedHrs)
        If Len(Trim(Str(ThuHrs))) < 4 Then strThuHrs = Str(ThuHrs) + Space(4 - Len(Trim(Str(ThuHrs)))) Else strThuHrs = Str(ThuHrs)
        If Len(Trim(Str(FriHrs))) < 4 Then strFriHrs = Str(FriHrs) + Space(4 - Len(Trim(Str(FriHrs)))) Else strFriHrs = Str(FriHrs)
        If Len(Trim(Str(SatHrs))) < 4 Then strSatHrs = Str(SatHrs) + Space(4 - Len(Trim(Str(SatHrs)))) Else strSatHrs = Str(SatHrs)
        If Len(Trim(Str(SunHrs))) < 4 Then strSunHrs = Str(SunHrs) + Space(4 - Len(Trim(Str(SunHrs)))) Else strSatHrs = Str(SatHrs)
        
        'Stuff TotalHrs with Spaces
        StrTotalHrs = Trim(Str(totalHrs))
        If Len(StrTotalHrs) < 5 Then StrTotalHrs = StrTotalHrs + Space(5 - Len(Trim(StrTotalHrs)))
        WeeklyTotal = WeeklyTotal + totalHrs
        If Len(Trim(myEarn)) < 3 Then EarnTy = myEarn + Space(3 - Len(Trim(myEarn))) Else EarnTy = myEarn
        'Print the Details Section
        Print #1, EarnTy; Tab(12); strMonHrs + " " + strTueHrs + " " + strWedHrs + " " + strThuHrs + " " + strFriHrs + " " + strSatHrs + " " + strSunHrs + " " + StrTotalHrs
        
        myEmpID = DblEmpRS.Fields("Employee_ID").Value    'Assign the Next Employee ID
        myEarn = DblEmpRS.Fields("Earning_Type_ID").Value 'Assign the Next Earning Type ID
        
        'Print Weekly Total
        Print #1, "                                                    " + String(5, "_")
        Print #1, "                                                    " + Str(WeeklyTotal)
        GrandTotal = GrandTotal + WeeklyTotal
        'Print Employee ID and Employee Name
        'Print #1, vbCrLf + vbCrLf
        Print #1, myEmpID + "  " + DblEmpRS.Fields("Employee_Name").Value
        'rteDblRpt.Text = rteDblRpt.Text + "     " + String(75, "_") + vbCrLf
        'rteDblRpt.Text = rteDblRpt.Text + "     " + "EARN TYPE  MON   TUE   WED   THU   FRI   SAT   SUN   TOTAL   " + vbCrLf
        'rteDblRpt.Text = rteDblRpt.Text + "     " + String(75, "_") + vbCrLf
        
        MonHrs = 0
        TueHrs = 0
        WedHrs = 0
        ThuHrs = 0
        FriHrs = 0
        SatHrs = 0
        SunHrs = 0
        totalHrs = 0
        WeeklyTotal = 0
      End If
    Loop
    'Stuff HrsWorked with Spaces
    If Len(Trim(Str(MonHrs))) < 4 Then strMonHrs = Str(MonHrs) + Space(4 - Len(Trim(Str(MonHrs)))) Else strMonHrs = Str(MonHrs)
    If Len(Trim(Str(TueHrs))) < 4 Then strTueHrs = Str(TueHrs) + Space(4 - Len(Trim(Str(TueHrs)))) Else strTueHrs = Str(TueHrs)
    If Len(Trim(Str(WedHrs))) < 4 Then strWedHrs = Str(WedHrs) + Space(4 - Len(Trim(Str(WedHrs)))) Else strWedHrs = Str(WedHrs)
    If Len(Trim(Str(ThuHrs))) < 4 Then strThuHrs = Str(ThuHrs) + Space(4 - Len(Trim(Str(ThuHrs)))) Else strThuHrs = Str(ThuHrs)
    If Len(Trim(Str(FriHrs))) < 4 Then strFriHrs = Str(FriHrs) + Space(4 - Len(Trim(Str(FriHrs)))) Else strFriHrs = Str(FriHrs)
    If Len(Trim(Str(SatHrs))) < 4 Then strSatHrs = Str(SatHrs) + Space(4 - Len(Trim(Str(SatHrs)))) Else strSatHrs = Str(SatHrs)
    If Len(Trim(Str(SunHrs))) < 4 Then strSunHrs = Str(SunHrs) + Space(4 - Len(Trim(Str(SunHrs)))) Else strSunHrs = Str(SunHrs)
    WeeklyTotal = WeeklyTotal + totalHrs
    
    'Stuff TotalHrs with Spaces
    StrTotalHrs = Trim(Str(totalHrs))
    If Len(StrTotalHrs) < 5 Then StrTotalHrs = StrTotalHrs + Space(5 - Len(Trim(StrTotalHrs)))

    'Print the Details Section for the Last Record
    If Len(Trim(myEarn)) < 3 Then EarnTy = myEarn + Space(3 - Len(Trim(myEarn))) Else EarnTy = myEarn
    Print #1, EarnTy; Tab(12); strMonHrs + " " + strTueHrs + " " + strWedHrs + " " + strThuHrs + " " + strFriHrs + " " + strSatHrs + " " + strSunHrs + " " + StrTotalHrs

    'Print Weekly Total
    Print #1, "                                                    " + String(5, "_")
    Print #1, "                                                    " + Str(WeeklyTotal)
    GrandTotal = GrandTotal + WeeklyTotal

    Print #1, String(70, "_")
    'Print Grand Total
    Print #1, "                                                    " + Str(GrandTotal)
    Print #1, String(70, "_")
'    Print #1, vbCrLf
  End If
  
  DblEmpRS.Close
  Set DblEmpRS = Nothing
  Close #1
End Sub

Private Sub Form_Unload(Cancel As Integer)
    Call cmdExit_Click
End Sub
