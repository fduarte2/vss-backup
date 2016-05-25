VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "mscomct2.ocx"
Begin VB.Form frmDailyHoursReport 
   Caption         =   "Daily Hours Report"
   ClientHeight    =   9180
   ClientLeft      =   1080
   ClientTop       =   945
   ClientWidth     =   12855
   BeginProperty Font 
      Name            =   "Times New Roman"
      Size            =   9.75
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   ScaleHeight     =   9180
   ScaleWidth      =   12855
   Begin SSDataWidgets_B.SSDBGrid DBGResult 
      Height          =   6135
      Left            =   0
      TabIndex        =   6
      Top             =   2640
      Width           =   12855
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
      Col.Count       =   7
      AllowUpdate     =   0   'False
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   0
      BackColorOdd    =   14737632
      RowHeight       =   503
      Columns.Count   =   7
      Columns(0).Width=   6059
      Columns(0).Caption=   "Employee"
      Columns(0).Name =   "Employee"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   6138
      Columns(1).Caption=   "Supervisor"
      Columns(1).Name =   "Supervisor"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   2011
      Columns(2).Caption=   "Type"
      Columns(2).Name =   "Type"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1984
      Columns(3).Caption=   "Start"
      Columns(3).Name =   "Start"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1693
      Columns(4).Caption=   "End"
      Columns(4).Name =   "End"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   2064
      Columns(5).Caption=   "Duration(hr)"
      Columns(5).Name =   "Duration"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   2143
      Columns(6).Caption=   "Swipe out"
      Columns(6).Name =   "Swipe out"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      _ExtentX        =   22675
      _ExtentY        =   10821
      _StockProps     =   79
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.Frame Frame1 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   735
      Left            =   600
      TabIndex        =   0
      Top             =   1680
      Width           =   11655
      Begin VB.CommandButton cmdExit 
         Caption         =   "&Exit"
         Height          =   375
         Left            =   9360
         TabIndex        =   5
         Top             =   240
         Width           =   1575
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
         Height          =   375
         Left            =   6960
         TabIndex        =   4
         Top             =   240
         Width           =   1575
      End
      Begin VB.CommandButton cmdRetrive 
         Caption         =   "&Retrieve"
         Height          =   375
         Left            =   4560
         TabIndex        =   3
         Top             =   240
         Width           =   1575
      End
      Begin MSComCtl2.DTPicker DTPDate 
         Height          =   375
         Left            =   1320
         TabIndex        =   2
         Top             =   240
         Width           =   1335
         _ExtentX        =   2355
         _ExtentY        =   661
         _Version        =   393216
         Format          =   60620801
         CurrentDate     =   37263
      End
      Begin VB.Label Label1 
         Caption         =   "Date:"
         Height          =   255
         Left            =   720
         TabIndex        =   1
         Top             =   360
         Width           =   615
      End
   End
   Begin VB.Label Label2 
      Caption         =   "Daily Employee Hours Report"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   18
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   4080
      TabIndex        =   8
      Top             =   1200
      Width           =   4695
   End
   Begin VB.Label labTitle 
      Alignment       =   2  'Center
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   18
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   975
      Left            =   1320
      TabIndex        =   7
      Top             =   0
      Width           =   10695
   End
   Begin VB.Image Image1 
      BorderStyle     =   1  'Fixed Single
      Height          =   960
      Left            =   0
      Picture         =   "frmDailyHoursReport.frx":0000
      Stretch         =   -1  'True
      Top             =   0
      Width           =   975
   End
   Begin VB.Line Line1 
      X1              =   0
      X2              =   12720
      Y1              =   960
      Y2              =   960
   End
End
Attribute VB_Name = "frmDailyHoursReport"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim rsHours As Object
Dim sqlStmt As String

Private Sub cmdExit_Click()
    Unload frmDailyHoursReport
    frmDailyHoursReport.Hide
    frmSumRep.Show
End Sub

Private Sub cmdPrint_Click()
    Dim line As String
    Dim i As Integer
    Dim Total As Integer
    Dim totPages As Integer
    
    line = String(180, "-")
    Total = DBGResult.Rows
    If Total Mod 50 <> 0 Then
        totPages = Total \ 50 + 1
    Else
        totPages = Total \ 50
    End If
    If Total > 0 Then
        Printer.FontSize = 14
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(33); "LCS SUMMARY REPORT"
        Printer.Print Tab(38); "Daily hours report"
        Printer.Print ""
        Printer.Print ""
        Printer.FontSize = 9
        Printer.Print Tab(5); "Date: " & Format(DTPDate.Value, "MM/DD/YYYY")
        Printer.Print Tab(130); "Pag 1 of " & totPages
        Printer.Print Tab(5); line
        Printer.Print Tab(5); "Employee"; Tab(40); "Supervisor"; Tab(75); "Type"; Tab(85); "Start"; Tab(100); "End"; Tab(115); "Duration(hr)"; Tab(130); "Swipe Out"
        Printer.Print Tab(5); line
        
        DBGResult.MoveFirst
        For i = 1 To Total
            Printer.Print Tab(5); DBGResult.Columns(0).Value; Tab(40); DBGResult.Columns(1).Value; Tab(75); DBGResult.Columns(2).Value; Tab(85); Format(DBGResult.Columns(3).Value, "HH:MM:SS"); Tab(100); Format(DBGResult.Columns(4).Value, "HH:MM:SS"); Tab(115); Format(DBGResult.Columns(5).Value, "0.0"); Tab(130); Format(DBGResult.Columns(6).Value, "HH:MM:SS");
            DBGResult.MoveNext
        
            If i Mod 50 = 0 Then
                Printer.Print Tab(5); line
                Printer.NewPage
                Printer.FontSize = 14
                Printer.Print ""
                Printer.Print ""
                Printer.Print ""
                Printer.Print ""
                Printer.Print Tab(33); "LCS SUMMARY REPORT"
                Printer.Print Tab(38); "Daily hours report"
                Printer.Print ""
                Printer.Print ""
                Printer.FontSize = 9
                Printer.Print Tab(5); "Date: " & Format(DTPDate.Value, "MM/DD/YYYY")
                Printer.Print Tab(130); "Page " & i / 50 + 1 & " of " & totPages
                Printer.Print Tab(5); line
                Printer.Print Tab(5); "Employee"; Tab(40); "Supervisor"; Tab(75); "Type"; Tab(85); "Start"; Tab(100); "End"; Tab(115); "Duration(hr)"; Tab(130); "Swipe Out"
                Printer.Print Tab(5); line
             End If
        Next i
        Printer.Print Tab(5); line

        Printer.EndDoc
               
    End If
End Sub

Private Sub cmdRetrive_Click()
    Dim i As Integer
    sqlStmt = "SELECT s.EMPLOYEE_NAME as SUPV, e.EMPLOYEE_NAME, e.EMPLOYEE_TYPE_ID, h.START_TIME, h.END_TIME, h.DURATION, d.TIME_OUT " + _
              " FROM HOURLY_DETAIL h, EMPLOYEE s, EMPLOYEE e, DAILY_HIRE_LIST d " + _
              " WHERE h.HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') AND h.USER_ID=s.EMPLOYEE_ID AND " + _
              " h.EMPLOYEE_ID=e.EMPLOYEE_ID AND h.HIRE_DATE=d.HIRE_DATE AND h.EMPLOYEE_ID = d.EMPLOYEE_ID " + _
              " ORDER BY e.EMPLOYEE_NAME, h.START_TIME "
              
    Set rsHours = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    DBGResult.RemoveAll
    
    If rsHours.RecordCount > 0 Then
        DBGResult.MoveFirst
        For i = 1 To rsHours.RecordCount
            DoEvents
            DBGResult.AddItem Trim("" & rsHours.Fields("employee_name").Value) + Chr(9) + _
                            Trim("" & rsHours.Fields("supv").Value) + Chr(9) + _
                            Trim("" & rsHours.Fields("employee_type_id").Value) + Chr(9) + _
                            Format(Trim("" & rsHours.Fields("start_time").Value), "HH:MM:SS") + Chr(9) + _
                            Format(Trim("" & rsHours.Fields("end_time").Value), "HH:MM:SS") + Chr(9) + _
                            Format(Trim("" & rsHours.Fields("duration").Value), "0.0") + Chr(9) + _
                            Format(Trim("" & rsHours.Fields("time_out").Value), "HH:MM:SS")
            DBGResult.Refresh
            rsHours.MoveNext
        Next i
        
    End If
    
End Sub

Private Sub Form_Load()
 labTitle.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
 DTPDate.Value = Date
End Sub
