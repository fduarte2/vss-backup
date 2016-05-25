VERSION 5.00
Begin VB.Form frmView 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "View Time Card"
   ClientHeight    =   7530
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   10350
   Icon            =   "frmView.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   7530
   ScaleWidth      =   10350
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdView 
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
      Height          =   600
      Left            =   -120
      TabIndex        =   1
      ToolTipText     =   "Update Changes"
      Top             =   7560
      Width           =   10800
   End
   Begin VB.TextBox txtEmpID 
      Appearance      =   0  'Flat
      BackColor       =   &H8000000F&
      BorderStyle     =   0  'None
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
      Left            =   3939
      TabIndex        =   0
      Top             =   3840
      Width           =   2655
   End
   Begin VB.Image imgLogo 
      Height          =   2025
      Left            =   3765
      Picture         =   "frmView.frx":0442
      Stretch         =   -1  'True
      Top             =   4440
      Width           =   2820
   End
   Begin VB.Label lblCompanyProduct 
      AutoSize        =   -1  'True
      Caption         =   "TIMECARD"
      BeginProperty Font 
         Name            =   "Garamond"
         Size            =   48
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   1080
      Left            =   2160
      TabIndex        =   4
      Top             =   600
      Width           =   5295
   End
   Begin VB.Label lblCompany 
      Caption         =   "Diamond State Port Corporation"
      BeginProperty Font 
         Name            =   "Garamond"
         Size            =   14.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3353
      TabIndex        =   3
      Top             =   6840
      Width           =   4095
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "SWIPE YOUR CARD NOW"
      BeginProperty Font 
         Name            =   "Garamond"
         Size            =   27.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   630
      Left            =   1853
      TabIndex        =   2
      Top             =   2520
      Width           =   6705
   End
End
Attribute VB_Name = "frmView"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private Sub cmdExit_Click()
  Unload Me
End Sub

Private Sub cmdView_Click()
  If Trim(txtEmpId) = vbNullString Or IsNull(txtEmpId) Then
    MsgBox "Please Enter Employee ID to View Time Card", vbInformation, "Data Required"
    txtEmpId.SetFocus
    Exit Sub
  End If
  EmpID = UCase(Trim(txtEmpId))
  Me.Hide
  frmTimeSheet.Show
  printTimeSheet
End Sub

Private Sub printTimeSheet()
  
  Dim dLastWeek As Date
  
  DoEvents
  dLastWeek = Now - 7
  If gbPrintTimesheet Then
    If Weekday(Now) = vbMonday Then
      Shell "TimeCard.exe -e" & Trim$(txtEmpId.Text) & " -d" & Format$(dLastWeek, "MMDDYYYY"), vbHide
      DoEvents
    Else
      Shell "TimeCard.exe -e" & Trim$(txtEmpId.Text) & " -d" & Format$(Now, "MMDDYYYY"), vbHide
      DoEvents
    End If
  End If
  
End Sub

Private Sub Form_Load()
  gbPrintTimesheet = (Trim$(Command$) = "-print")
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  CreateSession
End Sub

'****************************************
'To Create Session and Database Objects to connect to Oracle
'****************************************
Private Sub CreateSession()
  On Error GoTo ErrHandler
  Set OraSession = CreateObject("OracleInProcServer.XOraSession")
  Set OraDatabase = OraSession.OpenDatabase("LCS", "LABOR/LABOR", 0&)
  ''Set OraDatabase = OraSession.OpenDatabase("ISD", "LABOR/LABOR", 0&)

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

