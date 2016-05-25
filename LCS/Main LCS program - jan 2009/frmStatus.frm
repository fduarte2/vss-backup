VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmStatus 
   Caption         =   "Day Closure Status"
   ClientHeight    =   5955
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   8325
   Icon            =   "frmStatus.frx":0000
   LinkTopic       =   "Form1"
   ScaleHeight     =   5955
   ScaleWidth      =   8325
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdLock 
      Caption         =   "&LOCK"
      Enabled         =   0   'False
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
      Style           =   1  'Graphical
      TabIndex        =   3
      ToolTipText     =   "Return Back"
      Top             =   5280
      Width           =   1515
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
      Left            =   6720
      Style           =   1  'Graphical
      TabIndex        =   2
      ToolTipText     =   "Return Back"
      Top             =   5280
      Width           =   1515
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
      Height          =   3855
      Left            =   120
      TabIndex        =   0
      Top             =   1080
      Width           =   8055
      _Version        =   196616
      DataMode        =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      Col.Count       =   0
      AllowUpdate     =   0   'False
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowColumnSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      SelectTypeCol   =   0
      SelectTypeRow   =   0
      ForeColorEven   =   4210752
      RowHeight       =   503
      Columns(0).Width=   3200
      _ExtentX        =   14208
      _ExtentY        =   6800
      _StockProps     =   79
      Caption         =   "Day Closure Status"
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.Label Label2 
      Caption         =   "Date Selected :"
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
      Left            =   120
      TabIndex        =   4
      Top             =   5160
      Width           =   4050
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
      Picture         =   "frmStatus.frx":0442
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
      Left            =   840
      TabIndex        =   1
      Top             =   0
      Width           =   7335
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   8280
      Y1              =   960
      Y2              =   960
   End
End
Attribute VB_Name = "frmStatus"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdExit_Click()
  Unload Me
End Sub

'****************************************
'To Lock a day so that no Supervisor is allowed to modify data
'****************************************
Private Sub cmdLock_Click()
  Dim LockConf As Integer, myChkRS As Object
  Dim myDurRS As Object, mySQL As String, myrec As Integer
  LockConf = MsgBox("Supervisors can't do any Data Entry for the Selected Day. " + Chr(13) + "Do you wish to Lock the Selected Day?", vbYesNo + vbQuestion, "Confirm Locking")
  If LockConf = vbYes Then
    'Locking in Progress - Update DailyHrs_TransTo_PayRoll
    'Get the Total Hrs from Hourly Detail Table
    mySQL = "Select hire_date,sum(duration) Sum from hourly_detail where to_char(hire_date,'mm/dd/yyyy') = '" + Format(ClosedDate, "mm/dd/yyyy") + "' group by hire_date"
    Set myDurRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
    
    'Check if already Locked
    mySQL = "Select * from DailyHrs_TransTo_PayRoll where Hire_Date = to_date('" + Str(ClosedDate) + "','mm/dd/yyyy')"
    Set myChkRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
    If myChkRS.EOF And myChkRS.BOF Then
      'No record - In sert the data in DailyHrs_TransTo_PayRoll Table
      OraSession.BeginTrans
      mySQL = "Insert into DailyHrs_TransTo_PayRoll Values (to_date('" + Format(Now, "mm/dd/yyyy hh:nn am/pm") + "','MM/DD/yyyy HH:MI AM'),to_date('" + Str(ClosedDate) + "','mm/dd/yyyy')," + Str(myDurRS.Fields("Sum").Value) + ")"
      myrec = OraDatabase.ExecuteSQL(mySQL)
      'Commit or Rollback the Transaction
      If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        MsgBox "Data Transferred to PayRoll Successfully", vbInformation, "Day Closure Succeesful"
      Else
        OraSession.Rollback
      End If
    Else
      'Record Exists - Update Time_Transferred
      OraSession.BeginTrans
      mySQL = "Update DailyHrs_TransTo_PayRoll Set Time_Transferred = to_date('" + Format(Now, "mm/dd/yyyy hh:nn am/pm") + "','MM/DD/yyyy HH:MI AM') Where Hire_Date = to_date('" + Str(ClosedDate) + "','mm/dd/yyyy')"
      myrec = OraDatabase.ExecuteSQL(mySQL)
      'Commit or Rollback the Transaction
      If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        MsgBox "Data Transferred to PayRoll Successfully", vbInformation, "Day Closure Succeesful"
      Else
        OraSession.Rollback
      End If
    End If
    
    'Update Exception in Hourly_Detail to C if it is Y
    OraSession.BeginTrans
    mySQL = "Update Hourly_Detail set Exception = 'C' where Exception = 'Y' and Hire_Date = to_date('" + Str(ClosedDate) + "','mm/dd/yyyy')"
    myrec = OraDatabase.ExecuteSQL(mySQL)
    'Commit or Rollback the Transaction
    If OraDatabase.LastServerErr = 0 Then
       OraSession.CommitTrans
    Else
      OraSession.Rollback
    End If
  Else
    'Do Nothing
  End If
End Sub

Private Sub Form_Load()
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"

  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  SetColFormat
  populategrid1
  Label2.Caption = "Date Selected : " + Str(ClosedDate)
  
  'Enable LOCK Button for the Group ID 6 or 1
  Dim UserRS As Object
  Set UserRS = OraDatabase.DBCreateDynaset("Select Group_ID from lcs_user where user_id = '" + UserID + "'", 0&)
  If UserRS.BOF And UserRS.EOF Then
    'Do Nothing
  Else
    If UserRS.Fields("Group_ID").Value = "6" Or UserRS.Fields("Group_ID").Value = "1" Then
      EnableLock
    End If
  End If
  UserRS.Close
  Set UserRS = Nothing
End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  'Show Previous Form based on the Group ID
  Dim UserRS As Object
  Set UserRS = OraDatabase.DBCreateDynaset("Select Group_ID from lcs_user where user_id = '" + UserID + "'", 0&)
  If UserRS.BOF And UserRS.EOF Then
  'Do Nothing
  Else
    If UserRS.Fields("Group_ID").Value = "6" Or UserRS.Fields("Group_ID").Value = "1" Then
      frmClosureLock.Show
    Else
      frmClosure.Show
    End If
  End If
  UserRS.Close
  Set UserRS = Nothing
End Sub

'****************************************
'To Set the Column format for the Grid
'****************************************
Private Sub SetColFormat()
  Dim indxCtr As Integer
  SSDBGrid1.Columns.RemoveAll
  'For indxCtr = 0 To 8
  For indxCtr = 0 To 2
    SSDBGrid1.Columns.add indxCtr
  Next
  SSDBGrid1.Columns(0).Caption = "Supr ID"
  SSDBGrid1.Columns(0).Width = 1505.165
  SSDBGrid1.Columns(1).Caption = "Supervisor Name"
  SSDBGrid1.Columns(1).Width = 4145.26
  SSDBGrid1.Columns(2).Caption = "Day Closed Status"
  SSDBGrid1.Columns(2).Width = 2069.858
'  SSDBGrid1.Columns(2).Caption = "Mon"
'  SSDBGrid1.Columns(2).Width = 659.9055
'  SSDBGrid1.Columns(3).Caption = "Tue"
'  SSDBGrid1.Columns(3).Width = 615.1182
'  SSDBGrid1.Columns(4).Caption = "Wed"
'  SSDBGrid1.Columns(4).Width = 720.0001
'  SSDBGrid1.Columns(5).Caption = "Thu"
'  SSDBGrid1.Columns(5).Width = 615.1182
'  SSDBGrid1.Columns(6).Caption = "Fri"
'  SSDBGrid1.Columns(6).Width = 585.0709
'  SSDBGrid1.Columns(7).Caption = "Sat"
'  SSDBGrid1.Columns(7).Width = 689.9528
'  SSDBGrid1.Columns(8).Caption = "Sun"
'  SSDBGrid1.Columns(8).Width = 689.9528
  For indxCtr = 0 To 2
    SSDBGrid1.Columns(indxCtr).Locked = True
  Next
End Sub

'****************************************
'To Populate the Grid with data
'****************************************
Private Sub populategrid1()
  Dim mySQL As String, DayFlag As String, SupName As String, SupRS As Object, myCloseRS As Object
  SSDBGrid1.RemoveAll
  Set SupRS = OraDatabase.DBCreateDynaset("Select * from lcs_user where Group_ID = '4' or Group_ID = '2' Order by User_ID", 0&)
  If SupRS.EOF And SupRS.BOF Then
    'Do Nothing
  Else
    'Check For Record in DayClosure
    SupRS.MoveFirst
    Do While Not SupRS.EOF
      
      mySQL = "Select * from DayClosure where Supervisor_ID = '" + SupRS.Fields("User_ID").Value + "' and Hire_Date = to_date('" + Format(Str(ClosedDate), "mm/dd/yyyy") + "','mm/dd/yyyy') and (Upper(Flag) ='N' or Flag is Null)"
      'mySQL = "Select * from DayClosure where Supervisor_ID = '" + SupRS.Fields("User_ID").Value + "' and Hire_Date = to_date('" + Format(Str(ClosedDate), "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set myCloseRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
      If myCloseRS.EOF And myCloseRS.BOF Then
        DayFlag = " "
      Else
        DayFlag = "Y"
        'DayFlag = myCloseRS.Fields("FLAG").Value
      End If
      SSDBGrid1.AddItem SupRS.Fields("User_ID").Value + "!" + SupRS.Fields("User_Name").Value + "!" + DayFlag
      myCloseRS.Close
      Set myCloseRS = Nothing
      SupRS.MoveNext
    Loop
  End If
  SupRS.Close
  Set SupRS = Nothing
End Sub

'****************************************
'To Enable the Lock Button for Time Clerk only if all supervisors have closed the day
'****************************************
Private Sub EnableLock()
  Dim myCntRS As Object, indx As Integer, DisableLock As Boolean
  'Get the Number of Supervisors from the Database
  Set myCntRS = OraDatabase.DBCreateDynaset("Select Count(*) SuprTotal from lcs_user where Group_Id IN ('4','2')", 0&)
  If myCntRS.EOF And myCntRS.BOF Then
    'Do Nothing
  Else      'The Status Grid Should Show these many rows.
    If SSDBGrid1.rows = myCntRS.Fields("SuprTotal").Value Then
      SSDBGrid1.MoveFirst
      For indx = 0 To SSDBGrid1.rows
        'Check for Y in all Rows
        If SSDBGrid1.Columns(2).Value = "Y" Then
          'Do Nothing - Move on to Next Row
        Else
          DisableLock = True
          Exit For
        End If
        SSDBGrid1.MoveNext
      Next
      SSDBGrid1.MoveFirst
    Else
      DisableLock = True
    End If
    If DisableLock = True Then cmdLock.Enabled = False Else cmdLock.Enabled = True
  End If
End Sub
