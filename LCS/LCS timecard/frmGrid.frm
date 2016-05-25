VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmGrid 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Time Card"
   ClientHeight    =   8145
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   15240
   LinkTopic       =   "Form1"
   ScaleHeight     =   8145
   ScaleWidth      =   15240
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&PRINT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   2880
      TabIndex        =   9
      Top             =   7440
      Width           =   2295
   End
   Begin VB.TextBox txtEmpId 
      Appearance      =   0  'Flat
      BackColor       =   &H8000000F&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   1080
      Locked          =   -1  'True
      TabIndex        =   3
      Top             =   75
      Width           =   1935
   End
   Begin VB.TextBox txtFirstName 
      Appearance      =   0  'Flat
      BackColor       =   &H8000000F&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   1080
      Locked          =   -1  'True
      TabIndex        =   2
      Top             =   795
      Width           =   6255
   End
   Begin VB.TextBox txtTotalHrs 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H8000000F&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   10800
      Locked          =   -1  'True
      TabIndex        =   1
      Top             =   7560
      Width           =   2175
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "E&XIT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   120
      TabIndex        =   0
      Top             =   7440
      Width           =   2295
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   5415
      Left            =   0
      TabIndex        =   10
      Top             =   1560
      Width           =   15135
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   12
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   0
      ForeColorOdd    =   8388608
      RowHeight       =   423
      Columns.Count   =   12
      Columns(0).Width=   2540
      Columns(0).Caption=   "DATE"
      Columns(0).Name =   "DATE"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1640
      Columns(1).Caption=   "IN"
      Columns(1).Name =   "IN"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1588
      Columns(2).Caption=   "OUT"
      Columns(2).Name =   "OUT"
      Columns(2).Alignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1852
      Columns(3).Caption=   "PHYS HRS"
      Columns(3).Name =   "PHYS HRS"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   3307
      Columns(4).Caption=   "SUPERVISOR"
      Columns(4).Name =   "SUPERVISOR"
      Columns(4).Alignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   3200
      Columns(5).Caption=   "SRVC-COMM-EQUI-SP"
      Columns(5).Name =   "SRVC-COMM-EQUI-SP"
      Columns(5).Alignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1958
      Columns(6).Caption=   "START"
      Columns(6).Name =   "START"
      Columns(6).Alignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   2037
      Columns(7).Caption=   "END"
      Columns(7).Name =   "END"
      Columns(7).Alignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1244
      Columns(8).Caption=   "HRS"
      Columns(8).Name =   "HRS"
      Columns(8).Alignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1376
      Columns(9).Caption=   "TYPE"
      Columns(9).Name =   "TYPE"
      Columns(9).Alignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   1482
      Columns(10).Caption=   "TOTAL"
      Columns(10).Name=   "TOTAL"
      Columns(10).Alignment=   2
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   3307
      Columns(11).Caption=   "ENTRY TIME"
      Columns(11).Name=   "ENTRY TIME"
      Columns(11).Alignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      _ExtentX        =   26696
      _ExtentY        =   9551
      _StockProps     =   79
      Caption         =   "HOURS DETAILS"
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.Label Label4 
      Caption         =   "No."
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   120
      TabIndex        =   8
      Top             =   75
      Width           =   450
   End
   Begin VB.Label Label8 
      Caption         =   "NAME :"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   120
      TabIndex        =   7
      Top             =   795
      Width           =   810
   End
   Begin VB.Label Label11 
      Alignment       =   1  'Right Justify
      Caption         =   "Weekly Total :"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   9120
      TabIndex        =   6
      Top             =   7560
      Width           =   1530
   End
   Begin VB.Label Label5 
      Caption         =   "Pay Period Ending"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   10680
      TabIndex        =   5
      Top             =   0
      Width           =   2250
   End
   Begin VB.Label Label6 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   10680
      TabIndex        =   4
      Top             =   360
      Width           =   2250
   End
End
Attribute VB_Name = "frmGrid"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

'if set to true will not bring up frmView (used for command-line printing)
Public bNoReturn  As Boolean

Dim iYear As String



Private Sub cmdExit_Click()
  Unload Me
End Sub

Private Sub cmdPrint_Click()

  PrintTimesheet
  MsgBox "Time Card has been Printed Successfully", vbInformation, "Printing Successful"

End Sub

Public Sub PrintTimesheet()
  
'  SSDBGrid1.PrintData ssPrintAllRows, False, True
  Dim indxCtr As Integer, ColCtr As Integer, GridData As String, rowCntr As Integer
  Dim gsSqlStmt As String, CurrDay As Integer, WeekNo As String, DayTotalHrs As String
  Dim dsHOURLY_DETAIL As Object, ds_USER As Object, dsEMPLOYEE As Object
  Dim dsDAILY_HIRE_LIST As Object, ds_HOURLY_SUM As Object, ds_HOURLY_DAYTOTAL As Object
  Dim HireDt As String, TimeIn As String, TimeOut As String, User As String
  Dim StartTime As String, EndTime As String, Duration As String, Earn As String
  Dim Duration1 As String, Earn1 As String, TwoRec As Boolean
  Dim MnDiff As Integer, PhysMns As Integer, PhysHrs As Integer, TimeDiff As String, Time_Entry As String
  Dim SrvcComm As String
  Dim dsWEEKS As Object
  
  ' Read it from the database
  'get week number and start date and end date
  gsSqlStmt = "SELECT * FROM WEEKS WHERE START_DATE<TO_DATE('" & Format(CDate(BCField(1)), "MM/DD/YYYY") & "','MM/DD/YYYY') +1"
  gsSqlStmt = gsSqlStmt & " AND END_DATE>=TO_DATE('" & Format(CDate(BCField(1)), "MM/DD/YYYY") & "','MM/DD/YYYY')"
  Set dsWEEKS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
  If dsWEEKS.recordcount <= 0 Then
    'MsgBox "NO ANY RECORDS, TRY LATER.", vbInformation, "HOURLY DETAILS"
    MsgBox "Not any records, try again later.", vbInformation, "HOURLY DETAILS"    '2853pt2 4/11/2007 Rudy:
    Exit Sub
  End If
  
  'Open App.Path + "\TimeCard.txt" For Output As #1
  Printer.Font = "Courier New"
  Printer.Print " "
  Printer.Print " "
  Printer.Print " "
  Printer.Print String(5, " ") + "NO.    " + BCField(2) + "                                        " + String(15, " ") + "Pay Period Ending"
  Printer.Print String(5, " ") + "                              " + String(24, " ") + String(15, " ") + Label6.Caption
  Printer.Print String(5, " ") + "NAME    : " + txtFirstName
  Printer.Print String(5, " ") + "PRINTED : " + Format(Now, "mm/dd/yyyy hh:mm:ss AMPM")
  Printer.Print " "
  Printer.Print String(5, " ") + String(43, " ") + "TIME CARD"
  Printer.Print String(5, " ") + String(117, "-")
  Printer.Print String(5, " ") + "DATE         IN      OUT     SUPERVISOR  SRVC-COMM-EQUIP-SPC  START   END     HRS   TYPE DAILY TOTAL   ENTRY TIME"
  Printer.Print String(5, " ") + String(117, "-")
  
  gsSqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
  gsSqlStmt = gsSqlStmt & " AND HIRE_DATE >= TO_DATE('" & Format$(dsWEEKS.fields("START_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
  gsSqlStmt = gsSqlStmt & " AND HIRE_DATE < TO_DATE('" & Format$(dsWEEKS.fields("END_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1"
  gsSqlStmt = gsSqlStmt & " ORDER BY HIRE_DATE"
  Set dsDAILY_HIRE_LIST = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
  
  If dsDAILY_HIRE_LIST.EOF And dsDAILY_HIRE_LIST.bof Then
    'No data for the current week
  Else
    dsDAILY_HIRE_LIST.MoveFirst
    gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
    gsSqlStmt = gsSqlStmt & " AND HIRE_DATE >= TO_DATE('" & Format$(dsWEEKS.fields("START_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
    gsSqlStmt = gsSqlStmt & " AND HIRE_DATE < TO_DATE('" & Format$(dsWEEKS.fields("END_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1"
    Set ds_HOURLY_SUM = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
    If ds_HOURLY_SUM.EOF And ds_HOURLY_SUM.bof Then
      txtTotalHrs = ""
    ElseIf IsNull(ds_HOURLY_SUM.fields("Sum").Value) Or Trim(ds_HOURLY_SUM.fields("Sum").Value) = vbNullString Then
      txtTotalHrs = ""
    Else
      txtTotalHrs = ds_HOURLY_SUM.fields("Sum").Value
    End If
    
    Do While Not dsDAILY_HIRE_LIST.EOF
      'Fill Hours from Hourly Detail
      gsSqlStmt = "SELECT * FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
      gsSqlStmt = gsSqlStmt & " AND HIRE_DATE >=TO_DATE('" & Format(dsDAILY_HIRE_LIST.fields("HIRE_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
      gsSqlStmt = gsSqlStmt & " AND HIRE_DATE <TO_DATE('" & Format(dsDAILY_HIRE_LIST.fields("HIRE_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')+1"
      gsSqlStmt = gsSqlStmt & " AND DURATION IS NOT NULL"
      gsSqlStmt = gsSqlStmt & " ORDER BY ROW_NUMBER"
      Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      
      If dsHOURLY_DETAIL.EOF And dsHOURLY_DETAIL.bof Then
      'Do Nothing
      Else
        dsHOURLY_DETAIL.MoveFirst
        Do While Not dsHOURLY_DETAIL.EOF
          '2853 3/29/07 Rudy: reinit all 18 variables, just like it's the first time thru (Note a few may be reinitialized, but do all 4 thoroughness ) :
'          2853pt2 4/11/2007 Rudy:  A little too ambitious, need these four with data
'          DayTotalHrs = ""
'          HireDt = ""
'          TimeIn = ""
'          TimeOut = ""
          User = ""
          StartTime = ""
          EndTime = ""
          Duration = ""
          Earn = ""
          Duration1 = ""
          Earn1 = ""
          TwoRec = False
          MnDiff = 0
          PhysMns = 0
          PhysHrs = 0
          TimeDiff = ""
          Time_Entry = ""

          SrvcComm = ""
        
          If HireDt = dsDAILY_HIRE_LIST.fields("Hire_Date").Value Then
            HireDt = ""
          Else
            HireDt = dsDAILY_HIRE_LIST.fields("Hire_Date").Value
            TimeIn = ""
            TimeOut = ""
            DayTotalHrs = ""
          End If
          
          gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "' AND HIRE_DATE = to_date('" + Str(dsDAILY_HIRE_LIST.fields("Hire_Date").Value) + "','mm/dd/yy') and duration is not null "
          Set ds_HOURLY_DAYTOTAL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
          If ds_HOURLY_DAYTOTAL.EOF And ds_HOURLY_DAYTOTAL.bof Then
            DayTotalHrs = ""
          ElseIf IsNull(ds_HOURLY_DAYTOTAL.fields("Sum").Value) Or Trim(ds_HOURLY_DAYTOTAL.fields("Sum").Value) = vbNullString Then
            DayTotalHrs = ""
          ElseIf DayTotalHrs = ds_HOURLY_DAYTOTAL.fields("Sum").Value Then
            DayTotalHrs = ""
          Else
            DayTotalHrs = ds_HOURLY_DAYTOTAL.fields("Sum").Value
          End If
          
          If IsNull(dsDAILY_HIRE_LIST.fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_In").Value) = vbNullString Then
            TimeIn = "Null"
          ElseIf TimeIn = dsDAILY_HIRE_LIST.fields("Time_In").Value Then
            TimeIn = ""
          Else
            TimeIn = dsDAILY_HIRE_LIST.fields("Time_In").Value
          End If
          
          If TimeOut = "Null" Then
            TimeOut = ""
          ElseIf IsNull(dsDAILY_HIRE_LIST.fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_Out").Value) = vbNullString Then
            TimeOut = "Null"
          ElseIf TimeOut = dsDAILY_HIRE_LIST.fields("Time_Out").Value Then
            TimeOut = ""
          Else
            TimeOut = dsDAILY_HIRE_LIST.fields("Time_Out").Value
          End If
          
          If TimeOut <> "" And TimeIn <> "" And TimeOut <> "Null" Then
            MnDiff = DateDiff("n", Format(TimeIn, "hh:nnAM/PM"), Format(TimeOut, "hh:nnAM/PM"))
            PhysHrs = Fix(MnDiff / 60)
            PhysMns = MnDiff - PhysHrs * 60
            TimeDiff = Trim(Str(PhysHrs)) + ":" + Trim(Str(PhysMns))
          Else
            TimeDiff = ""
          End If
          
'          gsSqlStmt = "Select EMPLOYEE_NAME from  EMPLOYEE where EMPLOYEE_ID ='" + dsHOURLY_DETAIL.Fields("User_Id").Value + "'"
          gsSqlStmt = "Select USER_NAME from  LCS_USER where USER_ID ='" + dsHOURLY_DETAIL.fields("User_Id").Value + "'"
          
          Set ds_USER = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
          
          If (ds_USER.recordcount) > 0 Then
'            User = ds_USER.Fields("EMPLOYEE_NAME").Value
            User = ds_USER.fields("USER_NAME").Value
          Else
            'MsgBox "Supervisor Id is incorrect, Try it again.", vbInformation, "SUPERVISOR ID"
            MsgBox "Supervisor Id is incorrect, try again.", vbInformation, "SUPERVISOR ID"            '2853pt2 4/11/2007 Rudy:
            Exit Sub
          End If
          
'          gsSqlStmt = "Select User_Name from LCS_USER where User_ID = '" + dsHOURLY_DETAIL.Fields("User_ID").Value + "'"
'          Set ds_USER = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
'
'          If (ds_USER.recordcount) > 0 Then
'            User = ds_USER.Fields("User_Name").Value
'          Else
'            User = "UnKnown"
'          End If
          
          StartTime = GetValue(dsHOURLY_DETAIL.fields("Start_Time").Value, "")
          EndTime = GetValue(dsHOURLY_DETAIL.fields("End_Time").Value, "")
          
          ' Added 03/23/2000 by Bruce LeBrun to Display the time of entry
          Time_Entry = GetValue(dsHOURLY_DETAIL.fields("Time_Entry").Value, "")
          ' Added 04/10/2000 by Bruce LeBrun to Display the srvc-comm-asset-shift
          SrvcComm = Right("0000" & GetValue(dsHOURLY_DETAIL.fields("Service_Code").Value, "0000"), 4) & "-"
          SrvcComm = SrvcComm & Right("0000" & GetValue(dsHOURLY_DETAIL.fields("Commodity_Code").Value, "0000"), 4) & "-"
          SrvcComm = SrvcComm & Right("0000" & GetValue(dsHOURLY_DETAIL.fields("Equipment_ID").Value, "0000"), 4) & "-"
          SrvcComm = SrvcComm & Left(GetValue(dsHOURLY_DETAIL.fields("Special_Code").Value, "00") & "    ", 4)
          
          'Duration = dsHOURLY_DETAIL.fields("Duration").Value
          If IsNull(dsHOURLY_DETAIL.fields("Earning_Type_ID").Value) Or Trim(dsHOURLY_DETAIL.fields("Earning_Type_ID").Value) = vbNullString Then
            Duration = dsHOURLY_DETAIL.fields("Duration").Value            '2853 3/29/07 Rudy: per Inigo
            Earn = ""
            'Earn = "Null"
          ElseIf dsHOURLY_DETAIL.fields("Earning_Type_ID").Value <> "REG" Then
            Duration = dsHOURLY_DETAIL.fields("Duration").Value
            Earn = dsHOURLY_DETAIL.fields("Earning_Type_ID").Value
          ElseIf dsHOURLY_DETAIL.fields("Earning_Type_ID").Value = "REG" Then
            If dsHOURLY_DETAIL.fields("REG_Hrs").Value = vbNullString Or IsNull(dsHOURLY_DETAIL.fields("REG_Hrs").Value) Or IsNull(dsHOURLY_DETAIL.fields("OT_Hrs").Value) Or Trim(dsHOURLY_DETAIL.fields("OT_Hrs").Value) = vbNullString Then
              'Do Nothing
              ' Added 3/6/2000 by Bruce LeBrun to fix null REG_HRS/OT_HRS problem
              Duration = dsHOURLY_DETAIL.fields("duration").Value
              Earn = dsHOURLY_DETAIL.fields("Earning_Type_ID").Value
            ElseIf dsHOURLY_DETAIL.fields("REG_Hrs").Value <> 0 And dsHOURLY_DETAIL.fields("OT_Hrs").Value <> 0 Then
              'Add 2 records
              TwoRec = True
              Duration = dsHOURLY_DETAIL.fields("OT_Hrs").Value
              Earn = "OT"
              Duration1 = dsHOURLY_DETAIL.fields("REG_Hrs").Value
              Earn1 = "REG"
            Else
              If dsHOURLY_DETAIL.fields("REG_Hrs").Value = 0 Then
                Duration = dsHOURLY_DETAIL.fields("OT_Hrs").Value
                Earn = "OT"
              Else
                Duration = dsHOURLY_DETAIL.fields("REG_Hrs").Value
                Earn = "REG"
              End If
            End If
          End If
          If TwoRec = True Then
            Printer.Print Space(5) & Left(Format(HireDt, "mm/dd/yy") & Space(8), 8) & " " & Left(UCase(Format(HireDt, "ddd")) & "   ", 3) & " " & Left(Format(TimeIn, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Format(TimeOut, "hh:nnAM/PM") & Space(7), 7) & " " & Left(User & Space(11), 11) & " " & SrvcComm & "  " & Left(Format(StartTime, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Format(EndTime, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Duration & Space(4), 4) & "  " & Left(UCase(Earn) & Space(3), 3) & "  " & Left(DayTotalHrs & Space(4), 4) & Space(8) & Format(Time_Entry, "mm/dd hh:nnAM/PM")
            Printer.Print Space(83) & Left(Duration1 & Space(4), 4) & "  " & Left(UCase(Earn1) & Space(3), 3)
            TwoRec = False
            Earn1 = ""
            Duration1 = ""
          Else
            Printer.Print Space(5) & Left(Format(HireDt, "mm/dd/yy") & Space(8), 8) & " " & Left(UCase(Format(HireDt, "ddd")) & "   ", 3) & " " & Left(Format(TimeIn, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Format(TimeOut, "hh:nnAM/PM") & Space(7), 7) & " " & Left(User & Space(11), 11) & " " & SrvcComm & "  " & Left(Format(StartTime, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Format(EndTime, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Duration & Space(4), 4) & "  " & Left(UCase(Earn) & Space(3), 3) & "  " & Left(DayTotalHrs & Space(4), 4) & Space(8) & Format(Time_Entry, "mm/dd hh:nnAM/PM")
          End If
          
          'Assign Values to Check with Next Record
          HireDt = dsDAILY_HIRE_LIST.fields("Hire_Date").Value
          If IsNull(dsDAILY_HIRE_LIST.fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_In").Value) = vbNullString Then
            TimeIn = ""
          Else
            TimeIn = dsDAILY_HIRE_LIST.fields("Time_In").Value
          End If
          If IsNull(dsDAILY_HIRE_LIST.fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_Out").Value) = vbNullString Then
            TimeOut = "Null"
          Else
            TimeOut = dsDAILY_HIRE_LIST.fields("Time_Out").Value
          End If
          DayTotalHrs = GetValue(ds_HOURLY_DAYTOTAL.fields("Sum").Value, 0)
          dsHOURLY_DETAIL.MoveNext
        Loop
      End If
      dsDAILY_HIRE_LIST.MoveNext
    Loop
  End If

  
  Printer.Print String(5, " ") + String(117, "-")
  Printer.Print String(5, " ") + String(75, " ") + "Weekly Total :" + txtTotalHrs
  Printer.Print String(5, " ") + String(117, "-")
  
  ' 03/23/2000 - Adding box for supervisors to hand write changes
  Printer.Print " "
  Printer.Print " "
  Printer.Print " "
  Printer.Print " "
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|   Date   |   Start   |    End    |  Hrs   | Pay Type |     Supv     |   Srvc   |  Comm   |  Equip  |  Spec  |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.EndDoc

  Exit Sub
  
'  For indxCtr = 0 To SSDBGrid1.Rows - 1
'    SSDBGrid1.Row = indxCtr
'
'    If Len(Trim(SSDBGrid1.Columns(0).Value)) < 12 Then
'      GridData = GridData + SSDBGrid1.Columns(0).Value + Space(11 - Len(Trim(SSDBGrid1.Columns(0).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(0).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(1).Value)) < 7 Then
'      GridData = GridData + SSDBGrid1.Columns(1).Value + Space(7 - Len(Trim(SSDBGrid1.Columns(1).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(1).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(2).Value)) < 7 Then
'      GridData = GridData + SSDBGrid1.Columns(2).Value + Space(7 - Len(Trim(SSDBGrid1.Columns(2).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(2).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(3).Value)) < 7 Then
'      GridData = GridData + SSDBGrid1.Columns(3).Value + Space(7 - Len(Trim(SSDBGrid1.Columns(3).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(3).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(4).Value)) < 25 Then
'      GridData = GridData + SSDBGrid1.Columns(4).Value + Space(25 - Len(Trim(SSDBGrid1.Columns(4).Value))) + " "
'    ElseIf Len(Trim(SSDBGrid1.Columns(4).Value)) > 25 Then
'      GridData = GridData + Left(SSDBGrid1.Columns(4).Value, 25)
'    Else
'      GridData = GridData + SSDBGrid1.Columns(4).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(5).Value)) < 7 Then
'      GridData = GridData + SSDBGrid1.Columns(5).Value + Space(7 - Len(Trim(SSDBGrid1.Columns(5).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(5).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(6).Value)) < 7 Then
'      GridData = GridData + SSDBGrid1.Columns(6).Value + Space(7 - Len(Trim(SSDBGrid1.Columns(6).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(6).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(7).Value)) < 5 Then
'      GridData = GridData + SSDBGrid1.Columns(7).Value + Space(5 - Len(Trim(SSDBGrid1.Columns(7).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(7).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(8).Value)) < 4 Then
'      GridData = GridData + SSDBGrid1.Columns(8).Value + Space(4 - Len(Trim(SSDBGrid1.Columns(8).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(8).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(9).Value)) < 6 Then
'      GridData = GridData + SSDBGrid1.Columns(9).Value + Space(6 - Len(Trim(SSDBGrid1.Columns(9).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(9).Value + " "
'    End If
'
'    Printer.Print String(5, " ") + GridData
'    GridData = ""
'  Next
  
'  Printer.Print String(5, " ") + String(95, "-")
'  Printer.Print String(5, " ") + String(68, " ") + "Weekly Total :" + txtTotalHrs
'  Printer.Print String(5, " ") + String(95, "-")
'  Printer.EndDoc
'  MsgBox "Time Card has been Printed Successfully", vbInformation, "Printing Successful"
'  'Close #1
'  'Shell "notepad.exe " + App.Path + "\TimeCard.txt", 1
'  'SendKeys "%" + "F" + "P" 'To Print the Data
'  'SendKeys "%" + "F" + "X" 'To Close NotePad
  
End Sub
  
Private Sub Form_Load()
  bNoReturn = False
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  'iYear = Format(Now, "YYYY")
  iYear = Format(Now, "YYYY")
  SetGrid
  TimeRecording
End Sub

Private Sub SetGrid()
  grdData.RemoveAll
End Sub

Private Sub TimeRecording()
  Dim gsSqlStmt As String, CurrDay As Integer, WeekNo As String, DayTotalHrs As String
  Dim dsHOURLY_DETAIL As Object, ds_USER As Object, dsEMPLOYEE As Object
  Dim dsDAILY_HIRE_LIST As Object, ds_HOURLY_SUM As Object, ds_HOURLY_DAYTOTAL As Object
  Dim HireDt As String, TimeIn As String, TimeOut As String, User As String
  Dim StartTime As String, EndTime As String, Duration As String, Earn As String
  Dim Duration1 As String, Earn1 As String, TwoRec As Boolean
  Dim MnDiff As Integer, PhysMns As Integer, PhysHrs As Integer, TimeDiff As String, Time_Entry As String
  Dim dsWEEKS As Object
  Dim iWeekName As String
  Dim SrvcComm As String
  
  'Get Employee Name
  '2853pt2 4/11/2007 Rudy: old sql
  'gsSqlStmt = "SELECT * FROM EMPLOYEE WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
  
  '2853pt2 4/11/2007 Rudy: new sql ('need to validate that a regular user did not type in a supervisor's ID):
  Dim strwhere As String
  
  gsSqlStmt = "SELECT * FROM EMPLOYEE "

  strwhere = "WHERE "
  
  If BCField(2) <> uid Then
    If colTimeCardUser(uid) = "Y" Then
      strwhere = strwhere & "EMPLOYEE_TYPE_ID <> 'SUPV' AND "  'broken if supv looking at self, need outter if
    End If
  End If
  strwhere = strwhere & "EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"

  gsSqlStmt = gsSqlStmt & strwhere
  
  Set dsEMPLOYEE = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
  If Trim(dsEMPLOYEE.fields("Employee_Name").Value) = vbNullString Or IsNull(dsEMPLOYEE.fields("Employee_Name").Value) Then
    Exit Sub    'No Employee in Employee Table with this Employee ID
  End If
  txtFirstName = dsEMPLOYEE.fields("Employee_Name").Value
  txtEmpId = BCField(2)
  
  'get week number and start date and end date
  gsSqlStmt = "SELECT * FROM WEEKS WHERE START_DATE<TO_DATE('" & Format(CDate(BCField(1)), "MM/DD/YYYY") & "','MM/DD/YYYY') +1"
  gsSqlStmt = gsSqlStmt & " AND END_DATE>=TO_DATE('" & Format(CDate(BCField(1)), "MM/DD/YYYY") & "','MM/DD/YYYY')"
  Set dsWEEKS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
  If dsWEEKS.recordcount <= 0 Then
    'MsgBox "NO ANY RECORDS, TRY LATER.", vbInformation, "HOURLY DETAILS"
    MsgBox "Not any records, try again later.", vbInformation, "HOURLY DETAILS"
    Exit Sub
  End If
  
  gsSqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
  gsSqlStmt = gsSqlStmt & " AND HIRE_DATE >= TO_DATE('" & Format$(dsWEEKS.fields("START_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
  gsSqlStmt = gsSqlStmt & " AND HIRE_DATE < TO_DATE('" & Format$(dsWEEKS.fields("END_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1"
  gsSqlStmt = gsSqlStmt & " ORDER BY HIRE_DATE"
  Set dsDAILY_HIRE_LIST = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
  
  If dsDAILY_HIRE_LIST.EOF And dsDAILY_HIRE_LIST.bof Then
    'No data for the current week
  Else
    dsDAILY_HIRE_LIST.MoveFirst
    'GET TOTAL HOUR FOR TAHT WEEK
    
    gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
    gsSqlStmt = gsSqlStmt & " AND HIRE_DATE >= TO_DATE('" & Format$(dsWEEKS.fields("START_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
    gsSqlStmt = gsSqlStmt & " AND HIRE_DATE < TO_DATE('" & Format$(dsWEEKS.fields("END_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1"
    Set ds_HOURLY_SUM = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
    If ds_HOURLY_SUM.EOF And ds_HOURLY_SUM.bof Then
      txtTotalHrs = ""
    ElseIf IsNull(ds_HOURLY_SUM.fields("Sum").Value) Or Trim(ds_HOURLY_SUM.fields("Sum").Value) = vbNullString Then
      txtTotalHrs = ""
    Else
      txtTotalHrs = ds_HOURLY_SUM.fields("Sum").Value
    End If
    
    Label6.Caption = Format(dsWEEKS.fields("END_DATE").Value, "MM/DD/YYYY")
    
    Do While Not dsDAILY_HIRE_LIST.EOF
      'Fill Hours from Hourly Detail
      gsSqlStmt = "SELECT * FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
      gsSqlStmt = gsSqlStmt & " AND HIRE_DATE >=TO_DATE('" & Format(dsDAILY_HIRE_LIST.fields("HIRE_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
      gsSqlStmt = gsSqlStmt & " AND HIRE_DATE <TO_DATE('" & Format(dsDAILY_HIRE_LIST.fields("HIRE_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')+1"
      gsSqlStmt = gsSqlStmt & " AND DURATION IS NOT NULL"
      gsSqlStmt = gsSqlStmt & " ORDER BY ROW_NUMBER"
      Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      If dsHOURLY_DETAIL.EOF And dsHOURLY_DETAIL.bof Then
      'Do Nothing
      Else
        dsHOURLY_DETAIL.MoveFirst
        iWeekName = UCase(Format(dsDAILY_HIRE_LIST.fields("Hire_Date").Value, "ddd"))
        Do While Not dsHOURLY_DETAIL.EOF
          '2853 3/29/07 Rudy: reinit all 17 variables, just like it's the first time thru (Note a few may be reinitialized, but do all 4 thoroughness ) :
'          2853pt2 4/11/2007 Rudy:  A little too ambitious, need these four with data
'          DayTotalHrs = ""
'          HireDt = ""
'          TimeIn = ""
'          TimeOut = ""
          User = ""
          StartTime = ""
          EndTime = ""
          Duration = ""
          Earn = ""
          Duration1 = ""
          Earn1 = ""
          TwoRec = False
          MnDiff = 0
          PhysMns = 0
          PhysHrs = 0
          TimeDiff = ""
          Time_Entry = ""
        
          If HireDt = Format(dsDAILY_HIRE_LIST.fields("Hire_Date").Value, "mm/dd/yyyy") Then
            HireDt = ""
          Else
            HireDt = Format(dsDAILY_HIRE_LIST.fields("Hire_Date").Value, "mm/dd/yyyy")
            TimeIn = ""
            TimeOut = ""
            DayTotalHrs = ""
          End If
          'GET TOATL HOUR FOR THAT DAY
          gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
          gsSqlStmt = gsSqlStmt & " AND HIRE_DATE >= to_date('" & Trim$(Format(dsDAILY_HIRE_LIST.fields("Hire_Date").Value, "MM/DD/YYYY")) & "','mm/dd/yyyy')  "
          gsSqlStmt = gsSqlStmt & " AND HIRE_DATE < to_date('" & Trim$(Format(dsDAILY_HIRE_LIST.fields("Hire_Date").Value, "MM/DD/YYYY")) & "','mm/dd/yyyy') +1 "
          gsSqlStmt = gsSqlStmt & " AND duration is not null"
          
          Set ds_HOURLY_DAYTOTAL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
          If ds_HOURLY_DAYTOTAL.EOF And ds_HOURLY_DAYTOTAL.bof Then
            DayTotalHrs = ""
          ElseIf IsNull(ds_HOURLY_DAYTOTAL.fields("Sum").Value) Or Trim(ds_HOURLY_DAYTOTAL.fields("Sum").Value) = vbNullString Then
            DayTotalHrs = ""
          ElseIf DayTotalHrs = ds_HOURLY_DAYTOTAL.fields("Sum").Value Then
            DayTotalHrs = ""
          Else
            DayTotalHrs = ds_HOURLY_DAYTOTAL.fields("Sum").Value
          End If
          
          If IsNull(dsDAILY_HIRE_LIST.fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_In").Value) = vbNullString Then
            TimeIn = "Null"
          ElseIf TimeIn = Format(dsDAILY_HIRE_LIST.fields("Time_In").Value, "hh:mm ampm") Then
            TimeIn = ""
          Else
            TimeIn = Format(dsDAILY_HIRE_LIST.fields("Time_In").Value, "hh:mm ampm")
          End If
          
          If TimeOut = "Null" Then
            TimeOut = ""
          ElseIf IsNull(dsDAILY_HIRE_LIST.fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_Out").Value) = vbNullString Then
            TimeOut = "Null"
          ElseIf TimeOut = Format(dsDAILY_HIRE_LIST.fields("Time_Out").Value, "hh:mm ampm") Then
            TimeOut = ""
          Else
            TimeOut = Format(dsDAILY_HIRE_LIST.fields("Time_Out").Value, "hh:mm ampm")
          End If
          
          If TimeOut <> "" And TimeIn <> "" And TimeOut <> "Null" Then
            MnDiff = DateDiff("n", TimeIn, TimeOut)
            PhysHrs = Fix(MnDiff / 60)
            PhysMns = MnDiff - PhysHrs * 60
            TimeDiff = Trim(Str(PhysHrs)) + ":" + Trim(Str(PhysMns))
          Else
            TimeDiff = ""
          End If
          
          'gsSqlStmt = "Select User_Name from LCS_USER where User_ID = '" + dsHOURLY_DETAIL.Fields("User_Id").Value + "'"
          gsSqlStmt = "Select EMPLOYEE_NAME from  EMPLOYEE where EMPLOYEE_ID ='" + dsHOURLY_DETAIL.fields("User_Id").Value + "'"
          
          Set ds_USER = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
          
          If (ds_USER.recordcount) > 0 Then
            User = ds_USER.fields("EMPLOYEE_NAME").Value
          Else
            'MsgBox "Supervisor Id is incorrect, Try it again.", vbInformation, "SUPERVISOR ID"
            MsgBox "Supervisor Id is incorrect, try again.", vbInformation, "SUPERVISOR ID"            '2853pt2 4/11/2007 Rudy: grammar
            Exit Sub
'            User = " BAD SUPV !!!!"    '2853pt2 4/11/2007 Rudy: instead of exiting, maybe skip this field?  NO, if use commented sql it runs fine
                                        'incidentally that is the sql used in the print piece. Doanld Zimmerman on 4/05/07 illustrates this
          End If
          
          StartTime = Format(GetValue(dsHOURLY_DETAIL.fields("Start_Time").Value, ""), "hh:mm ampm")
          EndTime = Format(GetValue(dsHOURLY_DETAIL.fields("End_Time").Value, ""), "hh:mm ampm")
          
          ' Added 03/23/2000 by Bruce LeBrun to Display the time of entry
          Time_Entry = GetValue(dsHOURLY_DETAIL.fields("Time_Entry").Value, "")
          ' Added 04/10/2000 by Bruce LeBrun to Display the srvc-comm-asset-shift
          SrvcComm = Right("0000" & GetValue(dsHOURLY_DETAIL.fields("Service_Code").Value, "0000"), 4) & "-"
          SrvcComm = SrvcComm & Right("0000" & GetValue(dsHOURLY_DETAIL.fields("Commodity_Code").Value, "0000"), 4) & "-"
          SrvcComm = SrvcComm & Right("0000" & GetValue(dsHOURLY_DETAIL.fields("Equipment_ID").Value, "0000"), 4) & "-"
          SrvcComm = SrvcComm & Left(GetValue(dsHOURLY_DETAIL.fields("Special_Code").Value, "00") & "    ", 4)
          
          'Duration = dsHOURLY_DETAIL.fields("Duration").Value
          If IsNull(dsHOURLY_DETAIL.fields("Earning_Type_ID").Value) Or Trim(dsHOURLY_DETAIL.fields("Earning_Type_ID").Value) = vbNullString Then
            Duration = dsHOURLY_DETAIL.fields("Duration").Value            '2853 3/29/07 Rudy: per Inigo
            Earn = ""
            'Earn = "Null"
          ElseIf dsHOURLY_DETAIL.fields("Earning_Type_ID").Value <> "REG" Then
            Duration = dsHOURLY_DETAIL.fields("Duration").Value
            Earn = dsHOURLY_DETAIL.fields("Earning_Type_ID").Value
          ElseIf dsHOURLY_DETAIL.fields("Earning_Type_ID").Value = "REG" Then
            If dsHOURLY_DETAIL.fields("REG_Hrs").Value = vbNullString Or IsNull(dsHOURLY_DETAIL.fields("REG_Hrs").Value) Or IsNull(dsHOURLY_DETAIL.fields("OT_Hrs").Value) Or Trim(dsHOURLY_DETAIL.fields("OT_Hrs").Value) = vbNullString Then
              'Do Nothing
              ' Added 3/6/2000 by Bruce LeBrun to fix null REG_HRS/OT_HRS problem
              Duration = dsHOURLY_DETAIL.fields("duration").Value
              Earn = dsHOURLY_DETAIL.fields("Earning_Type_ID").Value
            ElseIf dsHOURLY_DETAIL.fields("REG_Hrs").Value <> 0 And dsHOURLY_DETAIL.fields("OT_Hrs").Value <> 0 Then
              'Add 2 records
              TwoRec = True
              Duration = dsHOURLY_DETAIL.fields("OT_Hrs").Value
              Earn = "OT"
              Duration1 = dsHOURLY_DETAIL.fields("REG_Hrs").Value
              Earn1 = "REG"
            Else
              If dsHOURLY_DETAIL.fields("REG_Hrs").Value = 0 And dsHOURLY_DETAIL.fields("OT_HRS").Value <> 0 Then
                Duration = dsHOURLY_DETAIL.fields("OT_Hrs").Value
                Earn = "OT"
              Else
                Duration = dsHOURLY_DETAIL.fields("REG_Hrs").Value
                Earn = "REG"
              End If
            End If
          End If
          
          If Trim$(HireDt) = "" Then
            iWeekName = ""
          End If
          
          'ADD INTO GRID
           If TwoRec = False Then
                  grdData.AddItem HireDt & " " & iWeekName + Chr(9) + _
                                TimeIn + Chr(9) + _
                                TimeOut + Chr(9) + _
                                TimeDiff + Chr(9) + _
                                User + Chr(9) + _
                                SrvcComm + Chr(9) + _
                                StartTime + Chr(9) + _
                                EndTime + Chr(9) + _
                                Duration + Chr(9) + _
                                Earn + Chr(9) + _
                                DayTotalHrs + Chr(9) + _
                                Format(Time_Entry, "mm/dd/yy hh:nnAM/PM")
                                
                  grdData.Refresh
                 
            Else
                  grdData.AddItem HireDt & " " & iWeekName + Chr(9) + _
                                TimeIn + Chr(9) + _
                                TimeOut + Chr(9) + _
                                TimeDiff + Chr(9) + _
                                User + Chr(9) + _
                                SrvcComm + Chr(9) + _
                                StartTime + Chr(9) + _
                                EndTime + Chr(9) + _
                                Duration + Chr(9) + _
                                Earn + Chr(9) + _
                                DayTotalHrs + Chr(9) + _
                                Format(Time_Entry, "mm/dd/yy hh:nnAM/PM")
                    grdData.Refresh
                    
                    grdData.AddItem "" + Chr(9) + _
                                "" + Chr(9) + _
                                "" + Chr(9) + _
                                "" + Chr(9) + _
                                "" + Chr(9) + _
                                "" + Chr(9) + _
                                "" + Chr(9) + _
                                "" + Chr(9) + _
                                Duration1 + Chr(9) + _
                                Earn1 + Chr(9) + _
                                "" + Chr(9) + _
                                ""
                    grdData.Refresh
                    
                    TwoRec = False
                    Earn1 = ""
                    Duration1 = ""
            End If
          
          'Assign Values to Check with Next Record
          HireDt = Format(dsDAILY_HIRE_LIST.fields("Hire_Date").Value, "mm/dd/yyyy")
          If IsNull(dsDAILY_HIRE_LIST.fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_In").Value) = vbNullString Then
            TimeIn = ""
          Else
            TimeIn = Format(dsDAILY_HIRE_LIST.fields("Time_In").Value, "hh:mm ampm")
          End If
          If IsNull(dsDAILY_HIRE_LIST.fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_Out").Value) = vbNullString Then
            TimeOut = "Null"
          Else
            TimeOut = Format(dsDAILY_HIRE_LIST.fields("Time_Out").Value, "hh:mm ampm")
          End If
          DayTotalHrs = GetValue(ds_HOURLY_DAYTOTAL.fields("Sum").Value, 0)
          
          
          dsHOURLY_DETAIL.MoveNext
        Loop
      End If
      dsDAILY_HIRE_LIST.MoveNext
'      SSDBGrid1.AddItem " " + "!" + " " + "!" + " " + "!" + " " + "!" + "TOTAL " + "!" + "HOURS : " + "!" + DayTotalHrs + "!" + " "
    Loop
  End If
End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  'frmView.txtEmpId = ""
  If bNoReturn = False Then
    frmView.Show
    frmView.txtEmpId.SetFocus
  End If

End Sub

