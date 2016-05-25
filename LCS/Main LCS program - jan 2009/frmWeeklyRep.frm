VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "mscomct2.ocx"
Begin VB.Form frmWeeklyRep 
   Caption         =   "Weekly Report"
   ClientHeight    =   9315
   ClientLeft      =   2835
   ClientTop       =   1425
   ClientWidth     =   10725
   LinkTopic       =   "Form1"
   ScaleHeight     =   9315
   ScaleWidth      =   10725
   Begin VB.TextBox txtHours 
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   8520
      TabIndex        =   10
      Top             =   8640
      Width           =   1215
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGRep 
      Height          =   5775
      Left            =   600
      TabIndex        =   8
      Top             =   2760
      Width           =   9375
      ScrollBars      =   2
      _Version        =   196616
      DataMode        =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Col.Count       =   4
      ForeColorEven   =   0
      BackColorOdd    =   14737632
      RowHeight       =   423
      Columns.Count   =   4
      Columns(0).Width=   2434
      Columns(0).Caption=   "Employee ID"
      Columns(0).Name =   "Employee ID"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   6959
      Columns(1).Caption=   "Employee Name"
      Columns(1).Name =   "Employee Name"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   4842
      Columns(2).Caption=   "Employee Type"
      Columns(2).Name =   "Employee Type"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1720
      Columns(3).Caption=   "Hours"
      Columns(3).Name =   "Hours"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      _ExtentX        =   16536
      _ExtentY        =   10186
      _StockProps     =   79
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
   Begin VB.Frame Frame1 
      Height          =   1215
      Left            =   600
      TabIndex        =   0
      Top             =   1440
      Width           =   9375
      Begin VB.CommandButton cmdExit 
         Caption         =   "&Exit"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   350
         Left            =   6360
         TabIndex        =   7
         Top             =   720
         Width           =   1695
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   350
         Left            =   3720
         TabIndex        =   6
         Top             =   720
         Width           =   1695
      End
      Begin VB.CommandButton cmdRetrive 
         Caption         =   "&Retrieve"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   350
         Left            =   960
         TabIndex        =   5
         Top             =   720
         Width           =   1815
      End
      Begin MSComCtl2.DTPicker DTPEnd 
         Height          =   375
         Left            =   6000
         TabIndex        =   4
         Top             =   240
         Width           =   1335
         _ExtentX        =   2355
         _ExtentY        =   661
         _Version        =   393216
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Format          =   61538305
         CurrentDate     =   37235
      End
      Begin MSComCtl2.DTPicker DTPStart 
         Height          =   375
         Left            =   1920
         TabIndex        =   2
         Top             =   240
         Width           =   1335
         _ExtentX        =   2355
         _ExtentY        =   661
         _Version        =   393216
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Format          =   61538305
         CurrentDate     =   37235
      End
      Begin VB.Label labEndDate 
         Caption         =   "End Date:"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   5040
         TabIndex        =   3
         Top             =   360
         Width           =   855
      End
      Begin VB.Label labStartDate 
         Caption         =   "Start Date:"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   960
         TabIndex        =   1
         Top             =   360
         Width           =   1095
      End
   End
   Begin VB.Label Label1 
      Caption         =   "Employee Total Hours Report"
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
      Left            =   3120
      TabIndex        =   12
      Top             =   1080
      Width           =   3855
   End
   Begin VB.Label labTitle 
      Alignment       =   2  'Center
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   18
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   855
      Left            =   1320
      TabIndex        =   11
      Top             =   0
      Width           =   7695
   End
   Begin VB.Image Image1 
      BorderStyle     =   1  'Fixed Single
      Height          =   950
      Left            =   0
      Picture         =   "frmWeeklyRep.frx":0000
      Stretch         =   -1  'True
      Top             =   0
      Width           =   950
   End
   Begin VB.Line Line1 
      X1              =   0
      X2              =   10680
      Y1              =   960
      Y2              =   960
   End
   Begin VB.Label labTimerKeeper 
      Caption         =   "Total hours added by Time Keeper: "
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   4440
      TabIndex        =   9
      Top             =   8760
      Width           =   3855
   End
End
Attribute VB_Name = "frmWeeklyRep"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim startDate As Date
Dim endDate As Date
Dim tHours As Integer
Dim weeklyRS As Object
Dim sqlStmt As String

Private Sub cmdExit_Click()
    Unload frmWeeklyRep
    frmWeeklyRep.Hide
    frmSumRep.Show
End Sub

Private Sub cmdPrint_Click()
    Dim line As String
    Dim i As Integer
    Dim Total As Integer
    Dim totPages As Integer
    
    line = String(160, "-")
    Total = SSDBGRep.Rows
    If Total Mod 50 <> 0 Then
        totPages = Total \ 50 + 1
    Else
        totPages = Total \ 50
    End If
    
    If Total > 0 Then
        Printer.FontSize = 14
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(28); "LCS SUMMARY REPORT"
        Printer.Print Tab(30); "Employee hours report"
        Printer.Print ""
        Printer.Print ""
        Printer.FontSize = 10
        Printer.Print Tab(5); "Date From: " & Format(DTPStart.Value, "MM/DD/YYYY"); Tab(30); "To: " & Format(DTPEnd.Value, "MM/DD/YYYY")
        Printer.Print Tab(115); "Pag 1 of " & totPages
        Printer.Print Tab(5); line
        Printer.Print Tab(5); "Employee ID"; Tab(30); "Employee Name"; Tab(70); "Employee Type"; Tab(110); "Hours"
        Printer.Print Tab(5); line
        
        SSDBGRep.MoveFirst
        For i = 1 To Total
            Printer.Print Tab(5); SSDBGRep.Columns(0).Value; Tab(30); SSDBGRep.Columns(1).Value; Tab(70); SSDBGRep.Columns(2).Value; Tab(110); SSDBGRep.Columns(3).Value
            SSDBGRep.MoveNext
        
            If i Mod 50 = 0 Then
                Printer.Print Tab(5); line
                Printer.NewPage
                Printer.FontSize = 14
                Printer.Print ""
                Printer.Print ""
                Printer.Print Tab(28); "LCS SUMMARY REPORT"
                Printer.Print Tab(30); "Employee hours report"
                Printer.Print ""
                Printer.Print ""
                Printer.FontSize = 10
                Printer.Print Tab(5); "Date From: " & Format(DTPStart.Value, "MM/DD/YYYY"); Tab(30); "To: " & Format(DTPEnd.Value, "MM/DD/YYYY")
                Printer.Print Tab(112); "Page " & i / 50 + 1 & " of " & totPages
                Printer.Print Tab(5); line
                Printer.Print Tab(5); "Employee ID"; Tab(30); "Employee Name"; Tab(70); "Employee Type"; Tab(110); "Hours"
                Printer.Print Tab(5); line
             End If
        Next i
        Printer.Print Tab(5); line
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(5); "Total Hours added by Time Keeper is "; Tab(45); tHours
        Printer.EndDoc
               
    End If
End Sub

Private Sub cmdRetrive_Click()
    Dim i As Integer
    
    On Error GoTo OraError
    
    startDate = Format(DTPStart.Value, "MM/DD/YYYY")
    endDate = Format(DTPEnd.Value, "MM/dd/yyyy")
    
    If startDate > endDate Then
        MsgBox "Invalid Start Date. Please enter again.", vbExclamation, "Retrive"
        Exit Sub
    End If
    
    
    'get employee list and total hours(supv, regr, guard, casb, casc)
    'get supervisor list and total hours
    sqlStmt = "SELECT e.EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID, SUM(DURATION) FROM HOURLY_DETAIL h, EMPLOYEE e WHERE EMPLOYEE_TYPE_ID = 'SUPV' AND HIRE_DATE BETWEEN TO_DATE('" & startDate & "', 'MM/DD/YYYY') AND TO_DATE('" & endDate & "', 'MM/DD/YYYY') AND e.EMPLOYEE_ID = h.EMPLOYEE_ID GROUP BY e.EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID"
    Set weeklyRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    SSDBGRep.RemoveAll
    
    If weeklyRS.RecordCount > 0 Then
        SSDBGRep.MoveFirst
        For i = 1 To weeklyRS.RecordCount
            DoEvents
            SSDBGRep.AddItem Trim("" & weeklyRS.Fields("employee_id").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("employee_name").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("employee_type_id").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("sum(duration)").Value)
            SSDBGRep.Refresh
            weeklyRS.MoveNext
        Next i
        
    End If
    
    'get regular employee list ant total hours
    sqlStmt = "SELECT e.EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID, SUB_TYPE_DESCRIPTION, SUM(DURATION) FROM HOURLY_DETAIL h, EMPLOYEE e, EMPLOYEE_SUB_TYPE s WHERE EMPLOYEE_TYPE_ID = 'REGR' AND e.EMPLOYEE_SUB_TYPE_ID = s.EMPLOYEE_SUB_TYPE_ID AND HIRE_DATE BETWEEN TO_DATE('" & startDate & "', 'MM/DD/YYYY') AND TO_DATE('" & endDate & "', 'MM/DD/YYYY') AND e.EMPLOYEE_ID = h.EMPLOYEE_ID GROUP BY e.EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID, SUB_TYPE_DESCRIPTION ORDER BY SUB_TYPE_DESCRIPTION"
    Set weeklyRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    
    If weeklyRS.RecordCount > 0 Then
        For i = 1 To weeklyRS.RecordCount
            DoEvents
            SSDBGRep.AddItem Trim("" & weeklyRS.Fields("employee_id").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("employee_name").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("employee_type_id").Value) & "--" & Trim(weeklyRS.Fields("sub_type_description").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("sum(duration)").Value)
            SSDBGRep.Refresh
            weeklyRS.MoveNext
        Next i
        
    End If
    
    'get guard list and total hours
    sqlStmt = "SELECT e.EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID, SUM(DURATION) FROM HOURLY_DETAIL h, EMPLOYEE e WHERE EMPLOYEE_TYPE_ID = 'GUARD' AND HIRE_DATE BETWEEN TO_DATE('" & startDate & "', 'MM/DD/YYYY') AND TO_DATE('" & endDate & "', 'MM/DD/YYYY') AND e.EMPLOYEE_ID = h.EMPLOYEE_ID GROUP BY e.EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID"
    Set weeklyRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If weeklyRS.RecordCount > 0 Then
        For i = 1 To weeklyRS.RecordCount
            DoEvents
            SSDBGRep.AddItem Trim("" & weeklyRS.Fields("employee_id").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("employee_name").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("employee_type_id").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("sum(duration)").Value)
            SSDBGRep.Refresh
            weeklyRS.MoveNext
        Next i
        
    End If
    
    
    
    'get casual list and total hours
    sqlStmt = "SELECT e.EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID, SUM(DURATION) FROM HOURLY_DETAIL h, EMPLOYEE e WHERE EMPLOYEE_TYPE_ID LIKE 'CAS%' AND HIRE_DATE BETWEEN TO_DATE('" & startDate & "', 'MM/DD/YYYY') AND TO_DATE('" & endDate & "', 'MM/DD/YYYY') AND e.EMPLOYEE_ID = h.EMPLOYEE_ID GROUP BY e.EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID ORDER BY EMPLOYEE_TYPE_ID"
    Set weeklyRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If weeklyRS.RecordCount > 0 Then
        For i = 1 To weeklyRS.RecordCount
            DoEvents
            SSDBGRep.AddItem Trim("" & weeklyRS.Fields("employee_id").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("employee_name").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("employee_type_id").Value) + Chr(9) + _
                            Trim("" & weeklyRS.Fields("sum(duration)").Value)
            SSDBGRep.Refresh
            weeklyRS.MoveNext
        Next i
        
    End If
    
    'get total hours added by time keeper
    sqlStmt = "SELECT SUM(DURATION) FROM HOURLY_DETAIL h, LCS_USER u WHERE h.USER_ID=u.USER_ID AND u.USER_NAME = 'Time Keeper' AND HIRE_DATE BETWEEN TO_DATE('" & startDate & "', 'MM/DD/YYYY') AND TO_DATE('" & endDate & "', 'MM/DD/YYYY')"
    Set weeklyRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If weeklyRS.RecordCount > 0 Then
        If IsNull(weeklyRS.Fields("sum(duration)")) Then
            tHours = 0
        Else
            tHours = weeklyRS.Fields("sum(duration)").Value
        End If
    Else
        tHours = 0
    End If
    
        txtHours.Text = tHours
    
OraError:
    If OraDatabase.LastServerErr > 0 Then
        MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
        OraDatabase.LastServerErrReset
    End If
    Exit Sub

End Sub

Private Sub Form_Load()
  labTitle.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
  DTPStart = Format(Date - 7, "mm/dd/yyyy")
  DTPEnd = Format(Date, "mm/dd/yyyy")
End Sub

