VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Begin VB.Form frmHiring 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Hiring Options"
   ClientHeight    =   9105
   ClientLeft      =   3420
   ClientTop       =   720
   ClientWidth     =   8670
   Icon            =   "frmHiring.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   9105
   ScaleWidth      =   8670
   Visible         =   0   'False
   Begin VB.CommandButton cmdPrintLaborTicket 
      Caption         =   "PRINT LABOR TICKET"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   930
      TabIndex        =   20
      Top             =   4800
      Visible         =   0   'False
      Width           =   7005
   End
   Begin VB.CommandButton cmdSupvAdjust 
      Caption         =   "SUPERVISOR WEEKLY ADJUSTMENT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   930
      TabIndex        =   19
      Top             =   4800
      Visible         =   0   'False
      Width           =   7005
   End
   Begin VB.CommandButton cmdSupvWeeklyRep 
      Caption         =   "SUPERVISOR WEEKLY SUMMARY REPORT"
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
      Height          =   495
      Left            =   930
      TabIndex        =   18
      Top             =   4800
      Width           =   7005
   End
   Begin VB.CommandButton cmdWeeklySummary 
      Caption         =   "WEEKLY SUMMARY REPORT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   465
      Left            =   930
      TabIndex        =   17
      Top             =   3630
      Visible         =   0   'False
      Width           =   6990
   End
   Begin VB.CommandButton cmdTempID 
      Caption         =   "&TEMPORARY TO PERMANENT ID"
      Enabled         =   0   'False
      Height          =   615
      Left            =   90
      MouseIcon       =   "frmHiring.frx":0442
      MousePointer    =   99  'Custom
      TabIndex        =   16
      ToolTipText     =   "Modify Temporary Employee ID to Permanent"
      Top             =   6660
      Visible         =   0   'False
      Width           =   510
   End
   Begin VB.CommandButton cmdClosure 
      Caption         =   "DAY &CLOSURE"
      Enabled         =   0   'False
      Height          =   495
      Left            =   90
      MouseIcon       =   "frmHiring.frx":0884
      MousePointer    =   99  'Custom
      TabIndex        =   15
      ToolTipText     =   "Close a Day"
      Top             =   7410
      Visible         =   0   'False
      Width           =   495
   End
   Begin VB.CommandButton cmdGrpHrDetail 
      Caption         =   "&GROUP HOURLY DETAIL"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   465
      Left            =   930
      MouseIcon       =   "frmHiring.frx":0CC6
      MousePointer    =   99  'Custom
      TabIndex        =   14
      Top             =   3630
      Width           =   6990
   End
   Begin VB.CommandButton cmdLine 
      Caption         =   "&LINE RUNNERS"
      Enabled         =   0   'False
      Height          =   525
      Left            =   90
      MouseIcon       =   "frmHiring.frx":0FD0
      MousePointer    =   99  'Custom
      TabIndex        =   13
      ToolTipText     =   "Hourly Detail for Line Runners"
      Top             =   6030
      Visible         =   0   'False
      Width           =   540
   End
   Begin VB.CommandButton cmdUnlock 
      Caption         =   " LINE RUNNER / PAYROLL ADJUSTMENT  SCREEN"
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
      Height          =   465
      Left            =   120
      MouseIcon       =   "frmHiring.frx":12DA
      MousePointer    =   99  'Custom
      TabIndex        =   12
      ToolTipText     =   "Make Modifications after the Day is Closed"
      Top             =   5370
      Visible         =   0   'False
      Width           =   450
   End
   Begin VB.CommandButton cmdEmpMaint 
      Caption         =   "EMPLOYEE &MAINTENANCE"
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
      Height          =   465
      Left            =   930
      MouseIcon       =   "frmHiring.frx":15E4
      MousePointer    =   99  'Custom
      TabIndex        =   11
      ToolTipText     =   "Add / Edit Employees"
      Top             =   1800
      Width           =   6990
   End
   Begin VB.CommandButton cmdTimeSheet 
      Caption         =   "TIME &SHEET REPORT"
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
      Height          =   465
      Left            =   930
      MouseIcon       =   "frmHiring.frx":18EE
      MousePointer    =   99  'Custom
      TabIndex        =   10
      ToolTipText     =   "View Time Sheet Report"
      Top             =   4200
      Width           =   6990
   End
   Begin VB.CommandButton cmdException 
      Caption         =   "&EXCEPTION REPORTS"
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
      Height          =   465
      Left            =   930
      MouseIcon       =   "frmHiring.frx":1BF8
      MousePointer    =   99  'Custom
      TabIndex        =   9
      ToolTipText     =   "View Exception Reports"
      Top             =   5430
      Width           =   6990
   End
   Begin VB.CommandButton cmdUpdate 
      Caption         =   "&UPDATE MASTERS"
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
      Height          =   465
      Left            =   930
      MouseIcon       =   "frmHiring.frx":1F02
      MousePointer    =   99  'Custom
      TabIndex        =   8
      ToolTipText     =   "Update Master Files"
      Top             =   6570
      Width           =   6990
   End
   Begin VB.CommandButton cmdLogOff 
      Caption         =   "LOG &OFF"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   465
      Left            =   930
      MouseIcon       =   "frmHiring.frx":220C
      MousePointer    =   99  'Custom
      TabIndex        =   7
      ToolTipText     =   "User Log off"
      Top             =   8400
      Width           =   6990
   End
   Begin VB.CommandButton cmdDailyHire 
      Caption         =   "&DAILY HIRE"
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
      Height          =   465
      Left            =   930
      MouseIcon       =   "frmHiring.frx":2516
      MousePointer    =   99  'Custom
      TabIndex        =   6
      ToolTipText     =   "Daily Hire Details"
      Top             =   1200
      Width           =   6990
   End
   Begin VB.CommandButton cmdHourlyDetail 
      Caption         =   "&HOURLY DETAIL"
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
      Height          =   465
      Left            =   930
      MouseIcon       =   "frmHiring.frx":2820
      MousePointer    =   99  'Custom
      TabIndex        =   5
      ToolTipText     =   "Hourly Detail of Hired Employees"
      Top             =   2430
      Width           =   6990
   End
   Begin VB.CommandButton cmdSumRep 
      Caption         =   "&SUMMARY REPORT"
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
      Height          =   465
      Left            =   930
      TabIndex        =   4
      Top             =   6000
      Width           =   6990
   End
   Begin VB.CommandButton cmdChangePWD 
      Caption         =   "&CHANGE PASSWORD"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   465
      Left            =   930
      TabIndex        =   3
      Top             =   7170
      Width           =   6990
   End
   Begin VB.CommandButton cmdAdmin 
      Caption         =   "&ADMINISTRATION"
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
      Height          =   465
      Left            =   930
      TabIndex        =   2
      Top             =   7800
      Width           =   6990
   End
   Begin VB.CommandButton cmdSFHourlyDetail 
      Caption         =   "SIMPLIFIED HOURLY DETAIL"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   465
      Left            =   930
      TabIndex        =   1
      Top             =   3030
      Width           =   6990
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
      Picture         =   "frmHiring.frx":2B2A
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
      TabIndex        =   0
      Top             =   0
      Width           =   7815
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   8640
      Y1              =   1020
      Y2              =   1020
   End
End
Attribute VB_Name = "frmHiring"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdAdmin_Click()
    Load frmUserGroup
    frmUserGroup.Show
End Sub

Private Sub cmdChangePWD_Click()
    Load frmChangePWD
    frmChangePWD.Show
End Sub

'****************************************
'To Show Day Closure Form
'****************************************
            '2853 3/29/2007 Rudy:   Doesn't appear that this control is ever visible, so would never run - OLD code.
Private Sub cmdClosure_Click()
  'frmHiring.MousePointer = vbHourglassager
  Me.Hide

  
  'Show Status Form for the Group ID 6 and Day Closure form for Others
  Dim UserRS As Object
  Set UserRS = OraDatabase.DBCreateDynaset("Select Group_ID from lcs_user where user_id = '" + UserID + "'", 0&)
  If UserRS.BOF And UserRS.EOF Then
  'Do Nothing
  Else
    If UserRS.Fields("Group_ID").Value = "6" Or UserRS.Fields("Group_ID").Value = "1" Or UserRS.Fields("Group_ID").Value = "5" Then
      frmClosureLock.Show
    Else
      frmClosure.Show
    End If
  End If
  UserRS.Close
  Set UserRS = Nothing
  frmHiring.MousePointer = vbDefault
End Sub

'****************************************
'To Show Daily Hire Form
'****************************************
Private Sub cmdDailyHire_Click()
  frmHiring.MousePointer = vbHourglass
  Me.Hide
  frmDailyHire.Show
  frmHiring.MousePointer = vbDefault
End Sub

'****************************************
'To Show Employee Maintanence Form
'****************************************
Private Sub cmdEmpMaint_Click()
  frmHiring.MousePointer = vbHourglass
  Me.Hide
  frmEmpMaint.Show
  frmHiring.MousePointer = vbDefault
End Sub

Private Sub cmdGrpHrDetail_Click()
  'frmHiring.MousePointer = vbHourglass
  'Unload Me
  frmGrpHrDetail.Show
  If ShowMeNot = True Then
    Unload frmGrpHrDetail
    'frmHiring.Show
    ShowMeNot = False
  End If
  'frmHiring.MousePointer = vbDefault
End Sub

'****************************************
'To Show Hourly Detail Form
'****************************************
                'This control is only visible for only for 2 guys
Private Sub cmdHourlyDetail_Click()
  'frmHiring.MousePointer = vbHourglass
  'Unload Me
  'frmHourlyDetail.Show
  frmHourlyDet.Show
  If ShowMeNot = True Then
    'Unload frmHourlyDetail
    Unload frmHourlyDet
    'frmHiring.Show
    ShowMeNot = False
  End If
  'frmHiring.MousePointer = vbDefault
End Sub

'****************************************
'To Show Hourly Detail Screen for the Line Runner
'****************************************
            '2853 3/29/2007 Rudy:   This control is NEVER visible, enabled when S8, so it's not used
Private Sub cmdLine_Click()
frmHiring.MousePointer = vbHourglass
Me.Hide
LineRun = True
frmHourlyDetail.Show
frmHiring.MousePointer = vbDefault
End Sub

Private Sub cmdPrintLaborTicket_Click()
    Call MouseHourlyGlass
    Load frmSelectLaborTicket
    frmHiring.Hide
    frmSelectLaborTicket.Show
    Call MouseNormal
End Sub

Private Sub cmdSFHourlyDetail_Click()
    Call MouseHourlyGlass
    Load frmSFHourlyDetail
    frmHiring.Hide
    frmSFHourlyDetail.Show
    Call MouseNormal
End Sub

Private Sub cmdSumRep_Click()
    Load frmSumRep
    frmSumRep.Show

End Sub

Private Sub cmdSupvAdjust_Click()
    Call MouseHourlyGlass
    Load frmSupvAdjust
    frmHiring.Hide
    frmSupvAdjust.Show
    Call MouseNormal
End Sub

Private Sub cmdSupvWeeklyRep_Click()
    ReportTitle = "SupervisorWeeklyReport"
    Me.Hide
    frmSelectDate.Show
End Sub

'****************************************
'To Show TimeSheet Report
'****************************************
Private Sub cmdTimeSheet_Click()
  ReportTitle = "TimeSheet"
  Me.Hide
  frmSelectDate.Show
End Sub

'****************************************
'To Show Te mporary Employee ID Form
'****************************************
            '2853 3/29/2007 Rudy:   Doesn't appear that this control is ever visible, so would never run - OLD code.
Private Sub cmdTempID_Click()
  frmHiring.MousePointer = vbHourglass
  Me.Hide
  frmTempEmpID.Show
  frmHiring.MousePointer = vbDefault
End Sub

'****************************************
'To Show Authorize User for Correction Form
'****************************************
            '2853 3/29/2007 Rudy:   Doesn't appear that this control is ever visible, or enabled, so would never run - OLD code.
Private Sub cmdUnlock_Click()
  'frmHiring.MousePointer = vbHourglass
  'Me.Hide
  'frmCorrection.Show
  'frmHiring.MousePointer = vbDefault
    Call MouseHourlyGlass
    Load frmSFHourlyDetail
    frmHiring.Hide
    frmSFHourlyDetail.Show
    Call MouseNormal
End Sub

'****************************************
'To Show Master Maintenance Form
'****************************************
Private Sub cmdUpdate_Click()
 frmHiring.MousePointer = vbHourglass
 Me.Hide
 frmMaster.Show
 frmHiring.MousePointer = vbDefault
End Sub

'****************************************
'To Show Exception Report Form
'****************************************
Private Sub cmdException_Click()
  frmHiring.MousePointer = vbHourglass
  Me.Hide
  frmException.Show
  frmHiring.MousePointer = vbDefault
End Sub

'****************************************
'To Close the Current Form
'****************************************
Private Sub cmdLogOff_Click()
  Unload Me
  Load frmLogin
  frmLogin.Show
End Sub

Private Sub cmdWeeklySummary_Click()
    frmHiring.MousePointer = vbHourglass
    Me.Hide
    frmWeeklySummary.Show
    frmHiring.MousePointer = vbDefault
End Sub

Private Sub Form_Load()
  Dim sqlStmt As String
  Dim hiringRS As Object

  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
'   NOTE
'   These 3: cmdLine cmdTempID cmdClosure   there are click events for each  It's enabled via code but always invisible!
'   This 1: cmdUnlock                       there is a click event           It's always disabled and always invisible
'   These 2: cmdChangePWD cmdLogOff         there are click event            It's always enabled and visible, anybody can use
  
  'need to init, as property pages is true
'  cmdWeeklySummary.Enabled = False
'  cmdPrintLaborTicket.Enabled = False

  'Check for Authorization and Enable / Disable Buttons as per the Group ID
  Dim myGrpSQL As String, Group_AccessRS As Object
  'Check for Reports
  myGrpSQL = "Select * from Group_Access where Upper(Group_ID) = '" + UCase(Trim(GroupID)) + "' and Upper(Scr_Rpt_ID) like 'R%' and Upper(Scr_Rpt_ID) NOT IN ('R4') Order By Scr_Rpt_ID"
  Set Group_AccessRS = OraDatabase.DBCreateDynaset(myGrpSQL, 0&)
  If Group_AccessRS.BOF And Group_AccessRS.EOF Then
    'Not having access to Reports - Disable EXCEPTION Button
    cmdException.Enabled = False
  Else
    Group_AccessRS.MoveFirst
    Do While Not Group_AccessRS.EOF
        Select Case UCase(Trim(Group_AccessRS.Fields("Scr_Rpt_Id").Value))
            Case "R1"
                cmdTimeSheet.Enabled = True
            Case "R10"
                cmdSumRep.Enabled = True
            Case "R12"
                cmdWeeklySummary.Visible = True
                cmdWeeklySummary.Enabled = True
                cmdPrintLaborTicket.Visible = True
                cmdPrintLaborTicket.Enabled = True
            Case Else
                cmdException.Enabled = True
        End Select
        Group_AccessRS.MoveNext
    Loop
    
    
    'Group_AccessRS.MoveFirst
    'Group_AccessRS.FindFirst "Upper(Scr_Rpt_ID) = 'R1'"
    'If Group_AccessRS.NoMatch Then  'No Access to TimeSheet
      'Having Access to One or More Exception Reports - Enable EXCEPTION Button
      'cmd Exception.Enabled = True
    'Else
      'cmd TimeSheet.Enabled = True
      'If Group_AccessRS.RecordCount > 1 Then
        'Having Access to One or More Exception Reports - Enable EXCEPTION Button
        'cmd Exception.Enabled = True
      'End If
    'End If
  End If
  
  'need to init, as property pages is true
'  cmdHourlyDetail.Enabled = False
  
  'Check for Screens
  myGrpSQL = "Select * from Group_Access where Upper(Group_ID) = '" + UCase(Trim(GroupID)) + "' and Upper(Scr_Rpt_ID) like 'S%' Order By Scr_Rpt_ID"
  Set Group_AccessRS = OraDatabase.DBCreateDynaset(myGrpSQL, 0&)
  If Group_AccessRS.BOF And Group_AccessRS.EOF Then
    'Not having access to Screens - Disable All Screen Buttons
    cmdDailyHire.Enabled = False
    cmdHourlyDetail.Enabled = False
    cmdSFHourlyDetail.Enabled = False
    cmdGrpHrDetail.Enabled = False
    cmdTempID.Enabled = False
    cmdUpdate.Enabled = False
    
    'how about these???
    'only 1
'    cmdAdmin.Enabled = False
'    cmdEmpMaint.Enabled = False
'    cmdSupvWeeklyRep.Enabled = False
'    cmdSupvAdjust.Enabled = False
'    cmdSupvAdjust.Visible = False  'and invisible too?

    'multi
'    cmdGrpHrDetail 2   'properties it's starting enabled, code sometimes making it false
'    cmdHourlyDetail  3  problem
'    cmdSFHourlyDetail 4 problem


  Else
    'Having Access to One or More Screens - Enable appropriate Buttons
    Group_AccessRS.MoveFirst
    Do While Not Group_AccessRS.EOF
      Select Case UCase(Trim(Group_AccessRS.Fields("Scr_Rpt_Id").Value))
      Case "S1"
        cmdDailyHire.Enabled = True
      Case "S2"
        'cmd HourlyDetail.Enabled = True
        'cmd GrpHrDetail.Enabled = True
        
        '5/2/2007 HD2759 Rudy: would really like to just execute the contents of else, comment rest:
        If isGuardSupervisor(UserID) Then
            cmdHourlyDetail.Enabled = True      '1 form for only for 2 guy
            'cmd HourlyDetail.Visible = True
            cmdSFHourlyDetail.Enabled = False
            'cmd SFHourlyDetail.Visible = Fa lse
        Else
            cmdHourlyDetail.Enabled = False
            'cmd HourlyDetail.Visible = Fa lse
            cmdSFHourlyDetail.Enabled = True
        End If
        
        cmdGrpHrDetail.Enabled = False
        cmdSupvWeeklyRep.Enabled = True
        
        'check hiring restriction
        sqlStmt = "SELECT * FROM HIRING_ACCESS WHERE USER_ID = '" & UserID & "'"
        Set hiringRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        HireRole = "000"
        Dim mt As String
        Dim gd As String
        Dim ot As String
        
        mt = "0"
        gd = "0"
        ot = "0"
        
        Do While Not hiringRS.EOF
            Select Case Trim(hiringRS.Fields("hiring_group_id").Value)
            Case 1
                mt = "1"
            Case 2
                gd = "1"
            Case 3
                ot = "1"
            End Select
            hiringRS.MoveNext
        Loop
        HireRole = mt & gd & ot
      Case "S3"
        cmdUpdate.Enabled = True
      Case "S4"
        'but it's still not Visible !!
        cmdTempID.Enabled = True
      Case "S5"
        cmdEmpMaint.Enabled = True
      Case "S6"
        If timeKeeperPrivilege = True Then
            cmdSFHourlyDetail.Caption = "LINE RUNNER / PAYROLL ADJUSTMENT SCREEN"
            cmdSFHourlyDetail.Enabled = True
            cmdSupvAdjust.Visible = True
            cmdSupvAdjust.Enabled = True
            'cmd SFHourlyDetail.Visible = Fa lse
            'cmd Unlock.Visible = True
            'cmd Unlock.Enabled = True
        End If
      Case "S7"
        'but it's still not Visible !!    Note only one employee and he's inactive!!
        cmdClosure.Enabled = True
      Case "S8"
        'but it's still not Visible !!
        cmdLine.Enabled = True
        
        cmdAdmin.Enabled = True
      End Select
      Group_AccessRS.MoveNext
    Loop
  End If
  
  '         andy markow           Philip Immediato (IS INACTIVE!!)
'  If User ID = " E405928" Or User ID = " E407341" Then
'    cmdAdmin.Enabled = True
'  End If
  
  Group_AccessRS.Close
  Set Group_AccessRS = Nothing
  
  'William Stansbury have access to Hourly Detail - but he can see only GUARDS in Empl list
  'If UCase(Trim(UserID)) = " E405833" Then
  'If UCase(Trim(UserID)) = " E002047" Then
  '  cmd HourlyDetail.Enabled = True
  '  cmd HourlyDetail.Visible = True
  '  'cmd GrpHrDetail.Enabled = True
  '  'cmd HourlyDetail.Enabled = Fa lse
  '  cmd SFHourlyDetail.Enabled = Fa lse
  '  'cmd SFHourlyDetail.Visible = Fa lse
  '  cmd GrpHrDetail.Enabled = Fa lse
  'End If
End Sub

'****************************************
'To Close the Current Form and Show the Previous Form
'****************************************
Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  frmLogin.Show
  frmLogin.txtPassword = ""
End Sub
