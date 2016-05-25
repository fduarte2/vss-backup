VERSION 5.00
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Begin VB.Form frmView 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "View Time Card"
   ClientHeight    =   6345
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   9000
   LinkTopic       =   "Form1"
   ScaleHeight     =   6345
   ScaleWidth      =   9000
   StartUpPosition =   3  'Windows Default
   Begin VB.ComboBox cboSortBy 
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
      ItemData        =   "frmView.frx":0000
      Left            =   4200
      List            =   "frmView.frx":000D
      TabIndex        =   13
      Top             =   1080
      Width           =   1215
   End
   Begin VB.CommandButton cmdPrtAllSUPV 
      Caption         =   "PRINT TIME CARDS OF ALL SUPERVISORS"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   855
      Left            =   5760
      TabIndex        =   12
      Top             =   3600
      Visible         =   0   'False
      Width           =   2655
   End
   Begin VB.Frame Frame1 
      Caption         =   "Sort By"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   615
      Left            =   960
      TabIndex        =   9
      Top             =   120
      Visible         =   0   'False
      Width           =   3615
      Begin VB.OptionButton optSortBy 
         Caption         =   "Type - &Name"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   300
         Index           =   0
         Left            =   120
         TabIndex        =   11
         ToolTipText     =   "Sort by Name"
         Top             =   240
         Width           =   1935
      End
      Begin VB.OptionButton optSortBy 
         Caption         =   "Emp&ID"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   300
         Index           =   1
         Left            =   2160
         TabIndex        =   10
         ToolTipText     =   "Sort by Employee ID"
         Top             =   240
         Value           =   -1  'True
         Width           =   1335
      End
   End
   Begin VB.ListBox lstEmpName 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   4380
      Left            =   120
      TabIndex        =   5
      ToolTipText     =   "Select an Employee to Hire"
      Top             =   1560
      Width           =   5415
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
      Left            =   5790
      TabIndex        =   4
      ToolTipText     =   "Update Changes"
      Top             =   5400
      Width           =   2610
   End
   Begin VB.CommandButton cmdView 
      Caption         =   "&VIEW TIME CARD"
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
      Left            =   5760
      TabIndex        =   2
      ToolTipText     =   "Update Changes"
      Top             =   4680
      Width           =   2610
   End
   Begin VB.TextBox txtEmpID 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   420
      Left            =   5790
      TabIndex        =   1
      Top             =   1920
      Width           =   2610
   End
   Begin SSCalendarWidgets_A.SSDateCombo ssdtcboDate 
      BeginProperty DataFormat 
         Type            =   1
         Format          =   "MM/dd/yyyy"
         HaveTrueFalseNull=   0
         FirstDayOfWeek  =   0
         FirstWeekOfYear =   0
         LCID            =   1033
         SubFormatType   =   3
      EndProperty
      Height          =   375
      Left            =   5790
      TabIndex        =   8
      ToolTipText     =   "Select Date"
      Top             =   3000
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
      Left            =   5760
      TabIndex        =   7
      Top             =   2520
      Width           =   1575
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "Employee Information Sorted By"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   120
      TabIndex        =   6
      Top             =   1080
      Width           =   4035
   End
   Begin VB.Label Label8 
      Caption         =   "Employee ID"
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
      Left            =   5790
      TabIndex        =   3
      Top             =   1560
      Width           =   1890
   End
   Begin VB.Image Image1 
      BorderStyle     =   1  'Fixed Single
      Height          =   840
      Left            =   0
      Picture         =   "frmView.frx":0021
      Stretch         =   -1  'True
      Top             =   0
      Width           =   870
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   8880
      Y1              =   960
      Y2              =   960
   End
   Begin VB.Label Label6 
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
      Left            =   960
      TabIndex        =   0
      Top             =   0
      Width           =   7815
   End
End
Attribute VB_Name = "frmView"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit                 '2853 3/29/07 Rudy:

Dim ByName As Integer
Dim ByID As Integer
Dim ByType As Integer
Private Sub iniSortByOptions()
    
    ByName = 0
    ByID = 1
    ByType = 2
    
End Sub

Private Sub cboSortBy_Click()
    Call RetrieveEmpInfo(Me.cboSortBy.ListIndex)
End Sub

Private Sub cmdExit_Click()
  Unload Me
End Sub

Private Sub cmdPrtAllSUPV_Click()
    
    Dim i As Integer
 
    '' Retrieve all operation supervisors' emp_id and emp_name
    Call RetrieveOpsSUPV

    '' print Time Card for each supervisor
    For i = 1 To colSUPVID.Count
        BCField(1) = Format(ssdtcboDate.Text, "MM/DD/YYYY")
        BCField(2) = colSUPVID.Item(i)
        SUPVName = colSUPVNAME.Item(colSUPVID.Item(i))
        Call PrintTimesheet4SUPV
    Next i

End Sub

Private Sub cmdView_Click()
  If Trim(txtEmpId) = vbNullString Or IsNull(txtEmpId) Then
    MsgBox "Please Enter Employee ID to View Time Card", vbInformation, "Data Required"
    txtEmpId.SetFocus
    Exit Sub
  End If
  
  BCField(2) = UCase(Trim(txtEmpId))
  BCField(1) = Format(ssdtcboDate.Text, "MM/DD/YYYY")
  Me.Hide
  'frmTimeSheet.Show
  frmGrid.Show
End Sub



Private Sub Form_Load()
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
  CreateSession
  ''PopulateEmpName ("EmpID")
  
  
  
  '2853 04/03/2007 Rudy: Time Card and Time Card Modified are now
  'the SAME codebase, just uncomment these two for supv exe OPMGR.exe
  '(NOTE the use of txtEmpID_DblClick is no longer needed):
'  cmdPrtAllSUPV.Enabled = False
'  cmdPrtAllSUPV.Visible = False
  
  '' Hide 'PRINT TIMECARDS OF ALL SUPERVISORS' button
  '' ONLY TIMECARDUSER is 'Y' can see it
  If colTimeCardUser(uid) = "A" Then
    Me.cmdPrtAllSUPV.Visible = True
  End If
  
  Call RetrieveEmpInfo(ByName)
  Me.cboSortBy.ListIndex = 0

End Sub

'****************************************
'To Create Session and Database Objects to connect to Oracle
'****************************************
Private Sub CreateSession()
  On Error GoTo ErrHandler
  
  ' Initialize variables for DB connection                                      '2853 3/29/07 Rudy:
'  Call iniConnection   '2853 3/29/07 Rudy: TEMP!
  
  
  Set OraSession = CreateObject("OracleInProcServer.XOraSession")

  'Create the OraDatabase Object
  Set OraDatabase = OraSession.OpenDatabase("LCS", "LABOR/LABOR", 0&)
  'Set OraDatabase = OraSession.OpenDatabase("ISD", "LABOR/LABOR", 0&)
  'Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)                      '2853 3/29/07 Rudy: TEMP for dev, orig 2 above
  
  If OraDatabase.LastServerErr = 0 Then
   ' Login to Oracle Successful!!
  Else
    MsgBox "Login to Oracle Failed!", vbInformation, "Oracle Connection Failure"
    Unload Me
  End If
  Exit Sub
ErrHandler:
  If Err.Number = 440 Then
    MsgBox "Incorrect Server Name/User ID/Password. Closing the Application", vbCritical, "Application Termination"
    Err.Clear
    Dim ErrFlag As Boolean                                                      '2853 3/29/07 Rudy: wasn't dim'd before
    ErrFlag = True
    Unload Me
  Else
    MsgBox Err.Description + " " + Str(Err.Number)
  End If
End Sub

Private Sub PopulateEmpName(SortStr As String)
  Dim EmpRS As Object
  If SortStr = "EmpID" Then
    Set EmpRS = OraDatabase.DBCreateDynaset("Select '(' || Employee_ID || ') ' || Employee_Name Employee from Employee Order by Employee_ID", 0&)
  Else
    Set EmpRS = OraDatabase.DBCreateDynaset("Select employee_type_id || '-' || Employee_Name || ' (' || Employee_ID || ')' Employee from Employee Order by employee_type_id,Employee_Name", 0&)
  End If
  If EmpRS.EOF And EmpRS.bof Then
    'Do Nothing
  Else
    EmpRS.MoveFirst
    Do While Not EmpRS.EOF
      lstEmpName.AddItem EmpRS.fields("Employee").Value
      EmpRS.MoveNext
    Loop
  End If
End Sub

Private Sub lstEmpName_Click()
  
'  Commented out by pwu on 5/03/2006
'  Dim EmpIDStart As Integer, EmpIDEnd As Integer, myEmpID As String
'  EmpIDStart = InStr(1, lstEmpName.List(lstEmpName.ListIndex), "(")
'  EmpIDEnd = InStr(1, lstEmpName.List(lstEmpName.ListIndex), ")")
'  myEmpID = Mid(lstEmpName.List(lstEmpName.ListIndex), EmpIDStart + 1, EmpIDEnd - EmpIDStart - 1)
'  txtEmpId = myEmpID
  
    '' added by pwu on 5/3/2006
    Dim i As Integer
  
    i = Me.lstEmpName.ListIndex
  
    Me.txtEmpId.Text = colUserID(i + 1)
  
End Sub

Private Sub optSortBy_Click(Index As Integer)
  If Index = 0 Then
    lstEmpName.Clear
    Call PopulateEmpName("Name")
  ElseIf Index = 1 Then
    lstEmpName.Clear
    Call PopulateEmpName("EmpID")
  End If
End Sub

          'NOT needed, a separate single user exe OPMGR.exe will have buttons turned on
'Private Sub txtEmpID_DblClick()
'  If txtEmpID = "E405833" Then
'    '2853 04/03/2007 Rudy: Time Card and Time Card Modified are now
'    'the SAME codebase, an easter egg:
'    cmdPrtAllSUPV.Enabled = True
'    cmdPrtAllSUPV.Visible = True
'  End If
'End Sub

Sub RetrieveEmpInfo(myOption As Integer)

''On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDatabase As Object
    Dim rs As Object
    Dim strSql As String
    
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        
        '' Prepare sql statement
        '2853pt2 4/11/2007 Rudy: old sql
'        If (myOption = 0) Then
'            strSql = "SELECT EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID" & _
'                        " FROM EMPLOYEE E" & _
'                        " WHERE E.EMPLOYEE_TYPE_ID <> 'SUPV'" & _
'                        " AND EMPLOYEE_ID <>" & "'" & uid & "'" & _
'                        " ORDER BY EMPLOYEE_NAME"
'
'        ElseIf (myOption = 1) Then
'            strSql = ""
'            strSql = "SELECT EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID" & _
'                        " FROM EMPLOYEE E" & _
'                        " WHERE E.EMPLOYEE_TYPE_ID <> 'SUPV'" & _
'                        " AND E.EMPLOYEE_ID <>" & "'" & uid & "'" & _
'                        " ORDER BY E.EMPLOYEE_ID"
'
'        ElseIf (myOption = 2) Then
'            strSql = "SELECT EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID" & _
'                        " FROM EMPLOYEE E" & _
'                        " WHERE E.EMPLOYEE_TYPE_ID <> 'SUPV'" & _
'                        " AND EMPLOYEE_ID <>" & "'" & uid & "'" & _
'                        " ORDER BY EMPLOYEE_TYPE_ID, EMPLOYEE_NAME"
'        End If
        
        '2853pt2 4/11/2007 Rudy: begin new sql
        Dim strwhere As String
        Dim strOrderBy As String
        
        strSql = "SELECT EMPLOYEE_ID, EMPLOYEE_NAME, EMPLOYEE_TYPE_ID" & " FROM EMPLOYEE E "
        
        strwhere = "WHERE "
        If colTimeCardUser(uid) = "Y" Then
          strwhere = strwhere & "E.EMPLOYEE_TYPE_ID <> 'SUPV' AND "
        End If
        
        strwhere = strwhere & " E.EMPLOYEE_ID <>" & "'" & uid & "'"
        
        strOrderBy = " ORDER BY "
        If (myOption = 0) Then
          strOrderBy = strOrderBy & "EMPLOYEE_NAME"
        ElseIf (myOption = 1) Then
          strOrderBy = strOrderBy & "E.EMPLOYEE_ID"
        ElseIf (myOption = 2) Then
          strOrderBy = strOrderBy & "EMPLOYEE_TYPE_ID, EMPLOYEE_NAME"
        End If
        
        strSql = strSql & strwhere & strOrderBy
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        
        If rs.recordcount = 0 Then
            'MsgBox "No user inforamtion found"
            MsgBox "No user information found"      '2853pt2 4/11/2007 Rudy:
            Set OraSession = Nothing
            Set OraDatabase = Nothing
            Set rs = Nothing
            End
        End If
        
        ''rs.Fields(0).Value=EMPLOYEE_ID
        ''rs.Fields(1).Value=EMPLOYEE_NAME
        ''rs.Fields(2).value=EMPLOYEE_TYPE_ID
        
        '' Clean the contents of three Collection objects
        Set colUserID = Nothing
        Set colUserName = Nothing
        Set colUserPWD = Nothing
        Me.lstEmpName.Clear
        
        '' Add the person being logged in at the top of list
        Me.lstEmpName.AddItem uid & "-" & uname
        colUserID.Add uid
        colUserName.Add uname, uid
        
        '' Load values into colUserID, colUserName, colUserPWD
        rs.MoveFirst
        
        Do While Not rs.EOF
            colUserID.Add rs.fields(0).Value
            colUserName.Add rs.fields(1).Value, rs.fields(0).Value
            ''colUserPWD.Add rs.Fields(2).Value, rs.Fields(0).Value
            If myOption = 0 Then
                Me.lstEmpName.AddItem rs.fields(0).Value & "-" & rs.fields(1).Value
            ElseIf myOption = 1 Then
                Me.lstEmpName.AddItem rs.fields(0).Value & "-" & rs.fields(1).Value
            ElseIf myOption = 2 Then
                Me.lstEmpName.AddItem rs.fields(2).Value & "-" & rs.fields(0).Value & "-" & rs.fields(1).Value
            End If
            rs.MoveNext
        Loop
    
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
    
    
    Else
        MsgBox "Error:" & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    Exit Sub
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        Me.Name & "." & "RetrieveEmpInfo"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End
    End If

End Sub
