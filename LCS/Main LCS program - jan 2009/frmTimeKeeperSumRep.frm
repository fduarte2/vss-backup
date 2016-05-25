VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Begin VB.Form frmTimeKeeperSumRep 
   Caption         =   "Total hours added by Time Keeper"
   ClientHeight    =   9375
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   12030
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
   ScaleHeight     =   9375
   ScaleWidth      =   12030
   StartUpPosition =   3  'Windows Default
   Begin VB.TextBox txtTotal 
      Enabled         =   0   'False
      Height          =   330
      Left            =   10560
      TabIndex        =   12
      Top             =   8880
      Width           =   1095
   End
   Begin SSDataWidgets_B.SSDBGrid DBGDate 
      Height          =   5655
      Left            =   0
      TabIndex        =   10
      Top             =   3120
      Width           =   12015
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
      ForeColorEven   =   0
      BackColorOdd    =   14737632
      RowHeight       =   503
      Columns.Count   =   7
      Columns(0).Width=   2249
      Columns(0).Caption=   "Employee ID"
      Columns(0).Name =   "Employee ID"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   7726
      Columns(1).Caption=   "Employee Name"
      Columns(1).Name =   "Employee Name"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1773
      Columns(2).Caption=   "Type"
      Columns(2).Name =   "Type"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   2170
      Columns(3).Caption=   "Hire Date"
      Columns(3).Name =   "Hire Date"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2223
      Columns(4).Caption=   "Start Time"
      Columns(4).Name =   "Start Time"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   2011
      Columns(5).Caption=   "End Time"
      Columns(5).Name =   "End Time"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   2064
      Columns(6).Caption=   "Duration(hr)"
      Columns(6).Name =   "Duration"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      _ExtentX        =   21193
      _ExtentY        =   9975
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
      Height          =   1215
      Left            =   480
      TabIndex        =   2
      Top             =   1560
      Width           =   11055
      Begin VB.CommandButton cmdExit 
         Caption         =   "&Exit"
         Height          =   350
         Left            =   7920
         TabIndex        =   9
         Top             =   720
         Width           =   2295
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
         Height          =   350
         Left            =   4440
         TabIndex        =   8
         Top             =   720
         Width           =   2295
      End
      Begin VB.CommandButton cmdRetrieve 
         Caption         =   "&Retrieve"
         Height          =   350
         Left            =   840
         TabIndex        =   7
         Top             =   720
         Width           =   2295
      End
      Begin MSComCtl2.DTPicker DTPEnd 
         Height          =   375
         Left            =   4920
         TabIndex        =   6
         Top             =   240
         Width           =   1335
         _ExtentX        =   2355
         _ExtentY        =   661
         _Version        =   393216
         Format          =   68354049
         CurrentDate     =   37270
      End
      Begin MSComCtl2.DTPicker DTPStart 
         Height          =   375
         Left            =   1800
         TabIndex        =   4
         Top             =   240
         Width           =   1335
         _ExtentX        =   2355
         _ExtentY        =   661
         _Version        =   393216
         Format          =   68354049
         CurrentDate     =   37270
      End
      Begin VB.Label Label4 
         Caption         =   "End Date: "
         Height          =   255
         Left            =   3960
         TabIndex        =   5
         Top             =   360
         Width           =   855
      End
      Begin VB.Label Label3 
         Caption         =   "Start Date:"
         Height          =   255
         Left            =   840
         TabIndex        =   3
         Top             =   360
         Width           =   855
      End
   End
   Begin VB.Label Label5 
      Caption         =   "Total:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   9960
      TabIndex        =   11
      Top             =   8880
      Width           =   615
   End
   Begin VB.Label Label2 
      Caption         =   "Total hours added by Time Keeper"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   15.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   3720
      TabIndex        =   1
      Top             =   1080
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
      TabIndex        =   0
      Top             =   0
      Width           =   10095
   End
   Begin VB.Image Image1 
      BorderStyle     =   1  'Fixed Single
      Height          =   855
      Left            =   120
      Picture         =   "frmTimeKeeperSumRep.frx":0000
      Stretch         =   -1  'True
      Top             =   0
      Width           =   975
   End
   Begin VB.Line Line1 
      X1              =   0
      X2              =   13320
      Y1              =   960
      Y2              =   960
   End
End
Attribute VB_Name = "frmTimeKeeperSumRep"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim start_date As String
Dim end_date As String
Dim sqlStmt As String
Dim rs As Object
Dim totHours As Integer

Private Sub cmdExit_Click()
    Unload frmTimeKeeperSumRep
    frmTimeKeeperSumRep.Hide
    frmSumRep.Show
End Sub

Private Sub cmdPrint_Click()
    Dim line As String
    Dim i As Integer
    Dim Total As Integer
    Dim totPages As Integer
    
    line = String(160, "-")
    Total = DBGDate.rows
    If Total Mod 50 <> 0 Then
        totPages = Total \ 50 + 1
    Else
        totPages = Total \ 50
    End If
    
    If Total > 0 Then
        Printer.FontSize = 14
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(35); "LCS SUMMARY REPORT"
        Printer.Print Tab(30); "Detail Hours Added By Time Keeper"
        Printer.Print ""
        Printer.Print ""
        Printer.FontSize = 10
        Printer.Print Tab(5); "Date From: " & Format(DTPStart.Value, "MM/DD/YYYY"); Tab(30); "To: " & Format(DTPEnd.Value, "MM/DD/YYYY")
        Printer.Print Tab(115); "Pag 1 of " & totPages
        Printer.Print Tab(5); line
        Printer.Print Tab(5); "Employee ID"; Tab(20); "Employee Name"; Tab(55); "Type"; Tab(67); "Hire Date"; Tab(83); "Start Time"; Tab(100); "End Time"; Tab(115); "Duration(hr)"
        Printer.Print Tab(5); line
        
        DBGDate.MoveFirst
        For i = 1 To Total
            Printer.Print Tab(5); DBGDate.Columns(0).Value; Tab(20); DBGDate.Columns(1).Value; Tab(55); DBGDate.Columns(2).Value; Tab(67); DBGDate.Columns(3).Value; Tab(83); DBGDate.Columns(4).Value; Tab(100); DBGDate.Columns(5).Value; Tab(115); DBGDate.Columns(6).Value
            DBGDate.MoveNext
        
            If i Mod 50 = 0 Then
                Printer.Print Tab(5); line
                Printer.NewPage
                Printer.FontSize = 14
                Printer.Print ""
                Printer.Print ""
                Printer.Print Tab(35); "LCS SUMMARY REPORT"
                Printer.Print Tab(30); "Detail Hours Added By Time Keeper"
                Printer.Print ""
                Printer.Print ""
                Printer.FontSize = 10
                Printer.Print Tab(5); "Date From: " & Format(DTPStart.Value, "MM/DD/YYYY"); Tab(30); "To: " & Format(DTPEnd.Value, "MM/DD/YYYY")
                Printer.Print Tab(115); "Page " & i / 50 + 1 & " of " & totPages
                Printer.Print Tab(5); line
                Printer.Print Tab(5); "Employee ID"; Tab(20); "Employee Name"; Tab(55); "Type"; Tab(67); "Hire Date"; Tab(83); "Start Time"; Tab(100); "End Time"; Tab(115); "Duration(hr)"
                Printer.Print Tab(5); line
             End If
        Next i
        Printer.Print Tab(5); line
        Printer.Print ""
        Printer.Print Tab(105); "Total:"; Tab(114); totHours
        Printer.EndDoc
               
    End If
End Sub

Private Sub cmdRetrieve_Click()
    Dim i As Integer
    Dim userName As String
    
    userName = "Time Keeper"
    
    start_date = Format(DTPStart.Value, "mm/dd/yyyy")
    end_date = Format(DTPEnd.Value, "mm/dd/yyyy")
    
    sqlStmt = "SELECT d.EMPLOYEE_ID, e.EMPLOYEE_NAME, e.EMPLOYEE_TYPE_ID, d.HIRE_DATE, d.START_TIME, d.END_TIME, d.DURATION "
    sqlStmt = sqlStmt & " FROM HOURLY_DETAIL d, EMPLOYEE e, LCS_USER u WHERE d.HIRE_DATE BETWEEN TO_DATE('" & start_date & "', 'mm/dd/yyyy') AND "
    sqlStmt = sqlStmt & " TO_DATE('" & end_date & "', 'mm/dd/yyyy') AND u.USER_NAME='" & userName & "' AND u.USER_ID = d.USER_ID AND "
    sqlStmt = sqlStmt & " d.EMPLOYEE_ID = e.EMPLOYEE_ID ORDER BY d.EMPLOYEE_ID, d.HIRE_DATE, d.START_TIME "
        
    On Error GoTo DB_Error
        
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If rs.RecordCount > 0 Then
        DBGDate.RemoveAll
        DBGDate.MoveFirst
        totHours = 0
        For i = 1 To rs.RecordCount
            DoEvents
            DBGDate.AddItem Trim("" & rs.Fields("employee_id").Value) + Chr(9) + _
                            Trim("" & rs.Fields("employee_name").Value) + Chr(9) + _
                            Trim("" & rs.Fields("employee_type_id").Value) + Chr(9) + _
                            Format(rs.Fields("hire_date").Value, "MM/DD/YYYY") + Chr(9) + _
                            Format(rs.Fields("start_time").Value, "H:MM") + Chr(9) + _
                            Format(rs.Fields("end_time").Value, "H:MM") + Chr(9) + _
                            Trim("" & rs.Fields("duration").Value)
            If Not IsNull(rs.Fields("duration")) Or rs.Fields("duration").Value <> "" Then
                totHours = totHours + rs.Fields("duration").Value
            End If
            DBGDate.Refresh
            rs.MoveNext
        Next i
        txtTotal.Text = totHours
        
        
    End If
Exit Sub
DB_Error:
    If OraDatabase.LastServerErr > 0 Then
        MsgBox "Oracle error, please try again!"
    End If
End Sub

Private Sub Form_Load()
  labTitle.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
  DTPStart = Format(Date - 7, "mm/dd/yyyy")
  DTPEnd = Format(Date, "mm/dd/yyyy")
End Sub
