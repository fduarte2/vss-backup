VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Begin VB.Form frmLogin 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "User Login"
   ClientHeight    =   8130
   ClientLeft      =   150
   ClientTop       =   840
   ClientWidth     =   11400
   Icon            =   "frmLogin.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   8130
   ScaleWidth      =   11400
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdActiveCancel 
      Caption         =   "&CANCEL"
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
      Left            =   7920
      TabIndex        =   18
      ToolTipText     =   "Cancel Changes"
      Top             =   6000
      Visible         =   0   'False
      Width           =   2625
   End
   Begin VB.CommandButton cmdMakeActive 
      Caption         =   "&MAKE ACTIVE"
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
      Left            =   7920
      TabIndex        =   17
      ToolTipText     =   "Make User Active"
      Top             =   5400
      Visible         =   0   'False
      Width           =   2625
   End
   Begin VB.CommandButton cmdShowDeleted 
      Caption         =   "&SHOW DELETED"
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
      Left            =   7920
      TabIndex        =   16
      ToolTipText     =   "Show Inactive Users"
      Top             =   5400
      Width           =   2625
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&DELETE"
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
      Left            =   3960
      TabIndex        =   15
      ToolTipText     =   "Make User Inactive"
      Top             =   7320
      Width           =   1695
   End
   Begin VB.CommandButton cmdCancel 
      Caption         =   "&CANCEL"
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
      Left            =   2040
      TabIndex        =   14
      ToolTipText     =   "Cancel Changes"
      Top             =   7320
      Visible         =   0   'False
      Width           =   1695
   End
   Begin VB.CommandButton cmdUpdate 
      Caption         =   "&UPDATE"
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
      Left            =   120
      TabIndex        =   13
      ToolTipText     =   "Update Changes"
      Top             =   7320
      Visible         =   0   'False
      Width           =   1695
   End
   Begin VB.TextBox txtPassword 
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
      IMEMode         =   3  'DISABLE
      Left            =   7320
      PasswordChar    =   "*"
      TabIndex        =   4
      Top             =   3600
      Width           =   3855
   End
   Begin VB.CommandButton cmdAdd 
      Caption         =   "&ADD"
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
      Left            =   120
      TabIndex        =   6
      ToolTipText     =   "Add User Data"
      Top             =   7320
      Width           =   1695
   End
   Begin VB.CommandButton cmdEdit 
      Caption         =   "&EDIT"
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
      Left            =   2040
      TabIndex        =   7
      ToolTipText     =   "Edit User Data"
      Top             =   7320
      Width           =   1695
   End
   Begin VB.ListBox lstSupervisor 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   5760
      Left            =   120
      TabIndex        =   1
      ToolTipText     =   "Select an User to Login"
      Top             =   1440
      Width           =   5535
   End
   Begin VB.CommandButton cmdLogin 
      Caption         =   "LOGIN"
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
      Left            =   7920
      TabIndex        =   5
      ToolTipText     =   "Login to the Application"
      Top             =   4680
      Width           =   1215
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
      Left            =   9600
      TabIndex        =   8
      ToolTipText     =   "Close the Application"
      Top             =   4680
      Width           =   975
   End
   Begin VB.TextBox txtSupervisorID 
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
      Left            =   7320
      TabIndex        =   2
      Top             =   2160
      Width           =   3855
   End
   Begin VB.TextBox txtSupFirstName 
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
      Left            =   7320
      TabIndex        =   3
      Top             =   2880
      Width           =   3855
   End
   Begin MSForms.Image Image2 
      Height          =   855
      Left            =   0
      Top             =   0
      Width           =   855
      BorderStyle     =   0
      SizeMode        =   1
      SpecialEffect   =   2
      Size            =   "1508;1508"
      Picture         =   "frmLogin.frx":0442
   End
   Begin VB.Label Label4 
      Caption         =   "U&ser Name"
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
      TabIndex        =   0
      Top             =   1035
      Width           =   2490
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   11400
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
      TabIndex        =   12
      Top             =   0
      Width           =   10335
   End
   Begin VB.Label Label1 
      Alignment       =   1  'Right Justify
      Caption         =   "Password"
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
      Left            =   5280
      TabIndex        =   11
      Top             =   3600
      Width           =   1905
   End
   Begin VB.Label Label2 
      Alignment       =   1  'Right Justify
      Caption         =   "User ID"
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
      Left            =   5280
      TabIndex        =   10
      Top             =   2160
      Width           =   1905
   End
   Begin VB.Label Label8 
      Alignment       =   1  'Right Justify
      Caption         =   "Name"
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
      Left            =   5520
      TabIndex        =   9
      Top             =   2880
      Width           =   1665
   End
   Begin VB.Menu mnuVersion 
      Caption         =   "&Version"
      NegotiatePosition=   3  'Right
   End
End
Attribute VB_Name = "frmLogin"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim LRS As Object, ErrFlag As Boolean, DelCntRS As Object, mySupSQL As String
Dim blnAddMode As Boolean, blneditmode As Boolean, NoRec As Boolean
Dim arrPassword() As String, arrSupervisorID() As String, arrSupFirstName() As String, arrExpireDate() As Date
'****************************************
'To Create Session and Database Objects to connect to Oracle
'****************************************
Private Sub CreateSession()
  On Error GoTo ErrHandler
  
  Init      '5/2/2007 HD2759 Rudy:
  
  Set OraSession = CreateObject("OracleInProcServer.XOraSession")
  'Set OraDatabase = OraSession.Open Database("LCS", "LABOR/LABOR", 0&)
  'Set OraDatabase = OraSession.OpenDatabase("BNI.DEV", "LABOR/LABOR_DEV", 0&)  '2853 3/29/2007 Rudy: for testing, orig above
  Set OraDatabase = OraSession.OpenDatabase(DBLCS, LoginLCS, 0&)  '5/2/2007 HD2759 Rudy:  one init, orig above TESTED /

  Set OraSessionBNI = CreateObject("OracleInProcServer.XOraSession")
  'Set OraDatabaseBNI = OraSessionBNI.Open Database("BNI", "sag_owner/sag", 0&)
  'Set OraDatabaseBNI = OraSessionBNI.OpenDatabase("BNI.DEV", "SAG_OWNER/SAG_DEV", 0&)  '2853 3/29/2007 Rudy: for testing, orig above
  Set OraDatabaseBNI = OraSessionBNI.OpenDatabase(DB, Login, 0&)  '5/2/2007 HD2759 Rudy:  one init, orig above TESTED /
      
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
    ErrFlag = True
    Unload Me
  Else
    MsgBox Err.Description + " " + Str(Err.Number)
  End If
End Sub

'****************************************
'To Return back to original Active List
'****************************************
Private Sub cmdActiveCancel_Click()
  lstSupervisor.Clear
  Call PopulateSupervisorName("A")
  cmdActiveCancel.Visible = False
  cmdShowDeleted.Visible = True
  cmdMakeActive.Visible = False
  Dim mySupSQL As String, DelCntRS As Object
  mySupSQL = "Select * from LCS_USER Where Status = 'I'"
  Set DelCntRS = OraDatabase.DBCreateDynaset(mySupSQL, 0&)
  If DelCntRS.BOF And DelCntRS.EOF Then   'No Deleted Records
    cmdShowDeleted.Enabled = False
  Else
    cmdShowDeleted.Enabled = True
  End If
  txtSupervisorID = ""
  txtPassword = ""
  txtSupFirstName = ""
  DelCntRS.Close
  Set DelCntRS = Nothing
End Sub

'****************************************
'To Add an User
'****************************************
Private Sub cmdAdd_Click()
  If LRS.EOF And LRS.BOF Then
    txtSupervisorID.Enabled = True
    txtSupFirstName.Enabled = True
    txtPassword.Enabled = True
    txtSupervisorID.SetFocus
  Else
    ClearFields                 'Clear the Text Boxes
    If txtSupervisorID.Enabled = False Then txtSupervisorID.Enabled = True
    txtSupervisorID.SetFocus
  End If
  Call ShowUpdate(True)       'Activate and Deactivate certain controls
  blnAddMode = True
  
  'Lock the table in Exclusive mode before updating
  'Dim myLockSQL As String, i As Integer
  'For i = 1 To 5
  '  OraDatabase.LastServerErrReset
  '  myLockSQL = "LOCK TABLE SUPERVISOR IN EXCLUSIVE MODE NOWAIT"
  '  OraDatabase.ExecuteSQL myLockSQL
  '  If OraDatabase.LastServerErr = 0 Then Exit For
  'Next
  'If OraDatabase.LastServerErr <> 0 Then
  '  OraDatabase.LastServerErr
  '  MsgBox "Table could not be locked. Please Try Again", vbInformation, "Locking Failure"
  '  Exit Sub
  'End If
    
  'Begin the Transaction
  OraSession.BeginTrans
  
  LRS.AddNew
End Sub

'****************************************
'To Enable / Disable and Visible / Invisible Certain Controls
'****************************************
Private Sub ShowUpdate(blnShow As Boolean)
  cmdAdd.Visible = Not blnShow
  cmdEdit.Visible = Not blnShow
  cmdUpdate.Visible = blnShow
  cmdCancel.Visible = blnShow
  cmdDelete.Enabled = Not blnShow
  cmdLogin.Enabled = Not blnShow
  cmdShowDeleted.Enabled = Not blnShow
  txtSupFirstName.Enabled = blnShow
  lstSupervisor.Enabled = Not blnShow 'To disable lstSupervisor
End Sub

'****************************************
'To Make Text Boxes Blank
'****************************************
Private Sub ClearFields()
  txtSupervisorID = ""
  txtPassword = ""
  txtSupFirstName = ""
End Sub

'****************************************
'To cancel the Changes
'****************************************
Private Sub cmdCancel_Click()
  On Error Resume Next
  If LRS.BOF And LRS.EOF Then
    Call ShowUpdate(False)
    cmdDelete.Enabled = False
    cmdShowDeleted.Enabled = True
    cmdMakeActive.Enabled = True
    cmdLogin.Enabled = False
    txtSupervisorID.Enabled = False
    txtPassword.Enabled = False
  Else
    Call ShowUpdate(False)
    'Revert back the changes
    txtSupervisorID = arrSupervisorID(lstSupervisor.ListIndex)
    txtSupFirstName = arrSupFirstName(lstSupervisor.ListIndex)
    txtSupervisorID.Enabled = True
    txtPassword.Enabled = True
  End If
  LRS.CancelUpdate
  
  OraSession.Rollback     'Rollback the Transaction
  
  blnAddMode = False
  blneditmode = False
End Sub

'****************************************
'To make an User Inactive
'****************************************
Private Sub cmdDelete_Click()
'Status of User changes to Inactive (I) when deleted. Normally status is Active (A)
  Dim DelConfirm As String
  
  If lstSupervisor.SelCount >= 1 Then
    If UCase(Trim(txtPassword)) = UCase(Trim(LRS.Fields("User_Password").Value)) Then
      DelConfirm = MsgBox("Are you sure to delete this User data ?", vbYesNo + vbQuestion, "Confirm Delete")
      If DelConfirm = vbYes Then
        'LRS.Delete     'Instead of deleting, just make this User inactive
        
        'Lock the table in Exclusive mode before updating
        'Dim myLockSQL As String, i As Integer
        'For i = 1 To 5
        '  OraDatabase.LastServerErrReset
        '  myLockSQL = "LOCK TABLE SUPERVISOR IN EXCLUSIVE MODE NOWAIT"
        '  OraDatabase.ExecuteSQL myLockSQL
        '  If OraDatabase.LastServerErr = 0 Then Exit For
        'Next
        'If OraDatabase.LastServerErr <> 0 Then
        '  OraDatabase.LastServerErr
        '  MsgBox "Table could not be locked. Please Try Again", vbInformation, "Locking Failure"
        '  Exit Sub
        'End If
       
        OraSession.BeginTrans     'Begin the Transaction
        
        LRS.Edit
        LRS.Fields("Status").Value = "I"
        LRS.Update
        cmdShowDeleted.Enabled = True
        'Commit or Rollback the Transaction
        If OraDatabase.LastServerErr = 0 Then
          OraSession.CommitTrans
        Else
          OraSession.Rollback
        End If
        
        lstSupervisor.Clear
        Call PopulateSupervisorName("A")
        If LRS.BOF And LRS.EOF Then
          cmdShowDeleted.Enabled = True
          cmdMakeActive.Enabled = True
          txtPassword.Enabled = True
        Else
          lstSupervisor.Selected(0) = True
        End If
      Else
        'Do Nothing - no deletion - no status change
      End If
    'To delete a User data, the password should be known
    ElseIf txtPassword = "" Then
      MsgBox "Please Enter your Password to Delete this User Data", vbInformation + vbOKOnly, "Password Required"
      txtPassword.SetFocus
    Else
      MsgBox "You are not authorised to delete this User data", vbInformation + vbOKOnly, "Incorrect Password"
      txtPassword = ""
    End If
  Else
    MsgBox "Please Select User Name from the List", vbInformation, "Select User"
    lstSupervisor.SetFocus
  End If
End Sub

'****************************************
'To Edit User Details
'****************************************
Private Sub cmdEdit_Click()
  If lstSupervisor.SelCount < 1 Then
    MsgBox "Please Select User Name from the List", vbInformation, "Select User"
    lstSupervisor.SetFocus
    Exit Sub
  End If
  Call ShowUpdate(True)
  txtSupervisorID.Enabled = False     'ID should not be edited
  txtPassword.Enabled = False         'Password should not be edited
  txtSupFirstName.SetFocus
  blneditmode = True
End Sub

'****************************************
'To Close the Application
'****************************************
Private Sub cmdExit_Click()
  Unload Me
  End
End Sub

'****************************************
'To Login the System after verifying the password
'****************************************
Private Sub cmdLogin_Click()
  Dim blnData As Boolean
  Dim curDate As Date
  Dim expDate As Date
  Dim days As Integer
  Dim iResponse As Integer
  
  curDate = Format(Date, "MM/DD/YYYY")
  expDate = Format(LRS.Fields("expire_date").Value, "MM/DD/YYYY")
  days = expDate - curDate
  
  'frmLogin.MousePointer = vbHourglass
  Call MouseHourlyGlass
  blnData = CheckforNull
  If blnData = True Then
    Call MouseNormal
    lstSupervisor.SetFocus
  Else
  'Check for Password and allow only if valid
    If Trim(UCase(txtPassword)) = Trim(UCase(LRS.Fields("User_Password").Value)) Then
      UserID = txtSupervisorID
      GroupID = LRS.Fields("Group_ID").Value
      If days <= 0 Then
        'MsgBox "Sorry, your password expired. Please contact to the administrator.", vbInformation + vbOKOnly, "Password Expired"
        iResponse = MsgBox("Your password has expired. Please change your password before you log in.", vbOKOnly, "Password Expired")
        If iResponse = vbOK Then
            Call MouseNormal
            Unload frmLogin
            Load frmChangePWD
            frmChangePWD.Show
        Else
             MsgBox "Sorry, your password expired. You must change your password before loggin into the system. Please try again.", vbInformation + vbOKOnly, "Password Expired"
        End If
      ElseIf days <= 7 Then
        iResponse = MsgBox("Your password will expire in " & days & " day(s). Please change your password.", vbYesNo, "Password Expired")
        If iResponse = vbYes Then
            Call MouseNormal
            Unload frmLogin
            Load frmChangePWD
            frmChangePWD.Show
        Else
            Call MouseNormal
            Unload frmLogin
            Load frmHiring
            frmHiring.Show
        End If
               
      Else
        Call MouseNormal
         Unload frmLogin
         Load frmHiring
         Load frmUnPaidList
         frmUnPaidList.Show
         frmHiring.Show
      End If
    ElseIf txtPassword = "" Then
        Call MouseNormal
      MsgBox "Please Enter your Password to Login", vbInformation + vbOKOnly, "Password Required"
      txtPassword.SetFocus
    Else
        Call MouseNormal
      MsgBox "Sorry! You have entered a wrong Password", vbInformation + vbOKOnly, "Invalid Password"
      txtPassword = ""
    End If
  End If
  'frmLogin.MousePointer = vbDefault
End Sub

'****************************************
'To Check for Null data in text boxes
'****************************************
Private Function CheckforNull()
  If Trim(txtSupervisorID) = "" Or Trim(txtSupFirstName) = "" Or Trim(txtPassword) = "" Then
    MsgBox "Please make sure that User ID, User Name and Password are entered", vbOKOnly, "Insufficient Data"
    CheckforNull = True
  End If
End Function

'****************************************
'To Check for Null data in First Name text boxes for Update
'****************************************
Private Function CheckforName()
  If Trim(txtSupFirstName) = "" Then
    MsgBox "Please make sure that Name is entered", vbOKOnly, "Insufficient Data"
    CheckforName = True
  End If
End Function

'****************************************
'To Make the Selected User Active - After Deletion
'****************************************
Private Sub cmdMakeActive_Click()
On Error GoTo ErrHandler
  If lstSupervisor.SelCount < 1 Then
    MsgBox "Please Select an User to Make Active", vbInformation, "User Data Required"
    Exit Sub
  End If
  'Lock the table in Exclusive mode before updating
  'Dim myLockSQL As String, i As Integer
  'For i = 1 To 5
  '  OraDatabase.LastServerErrReset
  '  myLockSQL = "LOCK TABLE SUPERVISOR IN EXCLUSIVE MODE NOWAIT"
  '  OraDatabase.ExecuteSQL myLockSQL
  '  If OraDatabase.LastServerErr = 0 Then Exit For
  'Next
  'If OraDatabase.LastServerErr <> 0 Then
  '  OraDatabase.LastServerErr
  '  MsgBox "Table could not be locked. Please Try Again", vbInformation, "Locking Failure"
  '  Exit Sub
  'End If
  If Trim(txtPassword) = vbNullString Then
    MsgBox "Please Enter the Password to Make the User Active", vbInformation, "Password Required"
    Exit Sub
  End If
  OraSession.BeginTrans       'Begin the Transaction
  
  DelCntRS.Edit
  DelCntRS.Fields("Status").Value = "A"
  DelCntRS.Update
  
  'Commit or Rollback the Transaction
  If OraDatabase.LastServerErr = 0 Then
    OraSession.CommitTrans
  Else
    OraSession.Rollback
  End If
  
  lstSupervisor.Clear
  Call PopulateSupervisorName("A")
  lstSupervisor.Selected(0) = True
  cmdMakeActive.Visible = False
  cmdActiveCancel.Visible = False
  cmdAdd.Enabled = True
  cmdEdit.Enabled = True
  cmdDelete.Enabled = True
  cmdLogin.Enabled = True
  
  Exit Sub
ErrHandler:
  'MsgBox "Please Try Again Later", vbInformation, "Up dation Failure"
  MsgBox "Please Try Again Later", vbInformation, "Update Failed"
End Sub

'****************************************
'To Show the Deleted Users - Inactive Status
'****************************************
Private Sub cmdShowDeleted_Click()
  lstSupervisor.Clear
  Call PopulateSupervisorName("I")    'Show Only Inactive Records in List Box
  txtSupervisorID = ""
  txtPassword = ""
  txtSupFirstName = ""
End Sub

'****************************************
'To Update the Changes to DB
'****************************************
Private Sub cmdUpdate_Click()
  Dim blnData As Boolean
  frmLogin.MousePointer = vbHourglass
  If blneditmode = True Then  'Edit Mode
    blnData = CheckforName  'Don't check for Null in Password
    If blnData = True Then
      txtSupFirstName.SetFocus
    Else
      'Lock the table in Exclusive mode before updating
      'Dim myLockSQL As String, i As Integer
      'For i = 1 To 5
      '  OraDatabase.LastServerErrReset
      '  myLockSQL = "LOCK TABLE SUPERVISOR IN EXCLUSIVE MODE NOWAIT"
      '  OraDatabase.ExecuteSQL myLockSQL
      '  If OraDatabase.LastServerErr = 0 Then Exit For
      'Next
      'If OraDatabase.LastServerErr <> 0 Then
      '  OraDatabase.LastServerErr
      '  MsgBox "Table could not be locked. Please Try Again", vbInformation, "Locking Failure"
      '  Exit Sub
      'End If
      
      OraSession.BeginTrans   'Begin the Transaction
      
      LRS.Edit
      LRS.Fields("User_Name").Value = txtSupFirstName
      'LRS.Fields("Supervisor_LName") = txtSupLastName
      LRS.Update
      
      'Commit or Rollback the Transaction
      If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
      Else
        OraSession.Rollback
      End If
      
      blneditmode = False
    End If
  Else                        'Add Mode
    blnData = CheckforNull    'Check for Null in all Fields
    If blnData = True Then
      txtSupervisorID.SetFocus
      Exit Sub
    Else
      'Check for existing ID from Deleted List - Status - I
        Dim mySupSQL As String, DelCntRS As Object
        mySupSQL = "Select * from LCS_USER Where Status = 'I'"
        Set DelCntRS = OraDatabase.DBCreateDynaset(mySupSQL, 0&)
        If DelCntRS.BOF And DelCntRS.EOF Then   'No Deleted Records also
          'Do Nothing
        Else
          'Check for existing ID with Status I
          Do While Not DelCntRS.EOF
            If UCase(DelCntRS.Fields("User_ID").Value) = UCase(Trim(txtSupervisorID)) Then
              'User ID already Exists - Give Message
              'Msg Box "User ID Already Exists", vbInformation, "Up dation Failure"
              MsgBox "User ID Already Exists", vbInformation, "Update Failed"
              txtSupervisorID = ""
              txtSupFirstName = ""
              txtPassword = ""
              txtSupervisorID.SetFocus
              frmLogin.MousePointer = vbDefault
              Exit Sub
            End If
            DelCntRS.MoveNext
          Loop
        End If
      LRS.Fields("User_ID").Value = txtSupervisorID
      LRS.Fields("User_Name").Value = txtSupFirstName
      'LRS.Fields("Supervisor_LName").Value = txtSupLastName
      LRS.Fields("User_Password").Value = txtPassword
      LRS.Fields("Status").Value = "A"
      LRS.Update
      'Commit or Rollback the Transaction
      If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
      Else
        OraSession.Rollback
      End If
      blnAddMode = False
    End If
  End If
  lstSupervisor.Clear
  Call PopulateSupervisorName("A")
  Call ShowUpdate(False)
  cmdEdit.Enabled = True
  txtSupervisorID.Enabled = True
  txtPassword.Enabled = True
  txtPassword = ""
  txtSupervisorID = ""
  txtSupFirstName = ""
  frmLogin.MousePointer = vbDefault
  'DelCntRS.Close
  'Set DelCntRS = Nothing
End Sub

Private Sub Form_Load()
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
  
  CreateSession         'Create a Session to Connect to Oracle
  
  If ErrFlag = True Then
    'Do Nothing
  Else
    Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
    Call PopulateSupervisorName("A")    'To populate the List Box with User Names who are Active
    mySupSQL = "Select * from LCS_USER Where Status = 'I'"
    Set DelCntRS = OraDatabase.DBCreateDynaset(mySupSQL, 0&)
    If DelCntRS.BOF And DelCntRS.EOF Then   'No Deleted Records
      cmdShowDeleted.Enabled = False
    End If
  End If
End Sub

'****************************************
'To populate the list box with User Name
'****************************************
Private Sub PopulateSupervisorName(myStatus As String)
  Dim arrCnt As Integer, mySupSQL As String
  If UCase(Trim(myStatus)) = "I" Then
    'Check for Deleted Record
    DelCntRS.Refresh
    If DelCntRS.BOF And DelCntRS.EOF Then   'No Deleted Records
      MsgBox "No Deleted Users Found in the Database", vbInformation, "User Data Unavailable"
      cmdShowDeleted.Enabled = False
      cmdMakeActive.Enabled = False
      cmdCancel.Visible = False
    Else
      cmdShowDeleted.Enabled = False
      cmdMakeActive.Enabled = True
      cmdActiveCancel.Visible = True
      cmdMakeActive.Visible = True
      cmdAdd.Enabled = False
      cmdEdit.Enabled = False
      cmdDelete.Enabled = False
      cmdLogin.Enabled = False
      DelCntRS.MoveLast
      TotalSupRec = DelCntRS.RecordCount
      DelCntRS.MoveFirst
      ReDim arrSupervisorID(TotalSupRec) As String
      ReDim arrSupFirstName(TotalSupRec) As String
      ReDim arrPassword(TotalSupRec) As String
      ReDim arrExpireDate(TotalSupRec) As Date
     
      Do While Not DelCntRS.EOF
        arrSupFirstName(arrCnt) = DelCntRS.Fields("User_Name").Value
        arrSupervisorID(arrCnt) = DelCntRS.Fields("User_ID").Value
        If IsNull(DelCntRS.Fields("User_Password").Value) Then
          arrPassword(arrCnt) = ""
        Else
          arrPassword(arrCnt) = DelCntRS.Fields("User_Password").Value
        End If
        lstSupervisor.AddItem "(" + DelCntRS.Fields("User_ID").Value + ") " + DelCntRS.Fields("User_Name").Value
        DelCntRS.MoveNext
      Loop
    End If
  Else
    mySupSQL = "Select * from LCS_USER where status = 'A' Order by User_Name"
    Set LRS = OraDatabase.DBCreateDynaset(mySupSQL, 0&)
    If LRS.BOF And LRS.EOF Then
      MsgBox "No User Data Found in the Database", vbInformation, "User Data Unavailable"
      'Check for Deleted Record
      mySupSQL = "Select * from LCS_USER Where Status = 'I'"
      Set DelCntRS = OraDatabase.DBCreateDynaset(mySupSQL, 0&)
      If DelCntRS.BOF And DelCntRS.EOF Then   'No Deleted Records also
        cmdShowDeleted.Enabled = False
        cmdMakeActive.Enabled = False
      Else
        cmdShowDeleted.Enabled = True
        cmdMakeActive.Enabled = True
      End If
      NoRec = True
      Call EnableControls(False)
      cmdAdd.Enabled = True
      Exit Sub
    Else
      NoRec = False
      LRS.MoveLast
      TotalSupRec = LRS.RecordCount
      LRS.MoveFirst
      ReDim arrSupervisorID(TotalSupRec) As String
      ReDim arrSupFirstName(TotalSupRec) As String
      'ReDim arrSupLastName(TotalSupRec) As String
      ReDim arrPassword(TotalSupRec) As String
      ReDim arrExpireDate(TotalSupRec) As Date
      Call EnableControls(True)
      Do While Not LRS.EOF
        arrSupFirstName(arrCnt) = LRS.Fields("User_Name").Value
        'arrSupLastName(arrCnt) = LRS.Fields("Supervisor_LName").Value
        'As the User ID field can have characters, ITEMDATA can't be used.
        arrSupervisorID(arrCnt) = LRS.Fields("User_ID").Value
        If IsNull(LRS.Fields("User_Password").Value) Then
          arrPassword(arrCnt) = ""
        Else
          arrPassword(arrCnt) = LRS.Fields("User_Password").Value
        End If
        arrExpireDate(arrCnt) = LRS.Fields("Expire_Date").Value
        lstSupervisor.AddItem "(" + LRS.Fields("User_ID").Value + ") " + LRS.Fields("User_Name").Value
        arrCnt = arrCnt + 1
        LRS.MoveNext
      Loop
    End If
  End If
End Sub

'****************************************
'To Close the Recordset
'****************************************
Private Sub Form_Terminate()
  Set LRS = Nothing
End Sub

'****************************************
'To Move the Record Pointer and to display the Details
'****************************************
Private Sub lstSupervisor_Click()
  'Move to the Current Record Pointer
  LRS.MoveFirst
  Dim myRow As Long
  myRow = CLng(lstSupervisor.ListIndex)
  LRS.MoveRel (myRow)
  If DelCntRS.EOF And DelCntRS.BOF Then
    'Do Nothing
  Else
    DelCntRS.MoveFirst
    DelCntRS.MoveRel (myRow)
  End If
  txtSupervisorID = arrSupervisorID(lstSupervisor.ListIndex)
  txtSupFirstName = arrSupFirstName(lstSupervisor.ListIndex)
  txtPassword = ""
  If txtPassword.Enabled = False Then txtPassword.Enabled = True
  txtPassword.SetFocus
End Sub

'****************************************
'To Show the About Screen with the Version Number
'****************************************
Private Sub mnuVersion_Click()
  frmAbout.Show
End Sub

'****************************************
'To Validate entry
'****************************************
Private Sub txtSupervisorID_LostFocus()
  Dim indxCtr As Integer, found As Boolean
  If Trim(txtSupervisorID) <> "" Then
  If blnAddMode = True Then   'ADD Mode
    'Check for Duplicate Record
    For indxCtr = 0 To TotalSupRec - 1
      If UCase(Trim(txtSupervisorID)) = UCase(Trim(arrSupervisorID(indxCtr))) Then
        found = True
        Exit For
      End If
    Next
    'Check for existence of User ID
    If found = True Then
      MsgBox "User ID Already exists", vbOKOnly + vbInformation, "Duplicate Data"
      txtSupervisorID = ""
      txtSupervisorID.SetFocus
    End If
  Else
    'Check for ID - allow only existing IDs
    For indxCtr = 0 To TotalSupRec
      If UCase(Trim(txtSupervisorID)) = UCase(Trim(arrSupervisorID(indxCtr))) Then
        found = True
        txtSupFirstName = arrSupFirstName(indxCtr)
'        txtSupLastName = arrSupLastName(indxCtr)
        txtPassword = ""
        lstSupervisor.Selected(indxCtr) = True
        Exit For
      End If
    Next
    If found = False Then
      MsgBox "Select User Name from the List (OR)" + Chr(13) + "Click on ADD to add new User ID ", vbOKOnly + vbInformation, "User ID Not Found"
      If lstSupervisor.SelCount < 1 Then
        txtSupervisorID = arrSupervisorID(0)
        lstSupervisor.Selected(0) = True
      Else
        txtSupervisorID = arrSupervisorID(lstSupervisor.ListIndex)
      End If
      lstSupervisor.SetFocus
    End If
  End If
  End If
End Sub

'****************************************
'To Enable / Disable Controls
'****************************************
Private Sub EnableControls(blnEnableFlag As Boolean)
  cmdAdd.Enabled = blnEnableFlag
  cmdDelete.Enabled = blnEnableFlag
  cmdEdit.Enabled = blnEnableFlag
  cmdLogin.Enabled = blnEnableFlag
  txtSupervisorID.Enabled = blnEnableFlag
  txtPassword.Enabled = blnEnableFlag
End Sub
