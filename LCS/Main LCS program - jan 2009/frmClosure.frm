VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Begin VB.Form frmClosure 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Day Closure "
   ClientHeight    =   5160
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   6150
   Icon            =   "frmClosure.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   5160
   ScaleWidth      =   6150
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdClose 
      Caption         =   "&CLOSE DAY"
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
      Style           =   1  'Graphical
      TabIndex        =   3
      ToolTipText     =   "Close the Day"
      Top             =   4200
      Width           =   1875
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
      Left            =   4125
      Style           =   1  'Graphical
      TabIndex        =   5
      ToolTipText     =   "Return Back"
      Top             =   4200
      Width           =   1515
   End
   Begin VB.CommandButton cmdStatus 
      Caption         =   "&STATUS"
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
      Left            =   2302
      Style           =   1  'Graphical
      TabIndex        =   4
      ToolTipText     =   "Show Day Closed Status"
      Top             =   4200
      Width           =   1515
   End
   Begin VB.TextBox txtSupervisorID 
      Enabled         =   0   'False
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
      Left            =   2685
      TabIndex        =   1
      ToolTipText     =   "Login Supervisor ID"
      Top             =   1320
      Width           =   2610
   End
   Begin SSCalendarWidgets_A.SSDateCombo ssdtcboDate 
      Height          =   375
      Left            =   1950
      TabIndex        =   6
      ToolTipText     =   "Select Date"
      Top             =   2633
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
      ShowCentury     =   -1  'True
   End
   Begin VB.Label Label1 
      Alignment       =   1  'Right Justify
      Caption         =   "Select Date to Close"
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
      Left            =   1590
      TabIndex        =   7
      Top             =   2153
      Width           =   2940
   End
   Begin VB.Label Label2 
      Alignment       =   1  'Right Justify
      Caption         =   "Supervisor ID"
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
      Left            =   570
      TabIndex        =   2
      Top             =   1320
      Width           =   1890
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   6120
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
      Left            =   840
      TabIndex        =   0
      Top             =   0
      Width           =   5175
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
      Picture         =   "frmClosure.frx":0442
   End
End
Attribute VB_Name = "frmClosure"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

'****************************************
'To Close a day
'****************************************
Private Sub cmdClose_Click()
  Dim mySQL As String, myrec As Integer, myChkRS As Object, myDayRS As Object
  mySQL = "Select * from DayClosure where Supervisor_ID = '" + UserID + "' and Hire_Date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy')"
  Set myChkRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If myChkRS.EOF And myChkRS.BOF Then
    'Record Not Exists - In sert the Record
    OraSession.BeginTrans
    mySQL = "Insert into DayClosure (Supervisor_ID, Hire_Date, Time_Closed) values ('" + UserID + "',to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy'),to_date('" + Format(Now, "mm/dd/yyyy hh:nn am/pm") + "','MM/DD/yyyy HH:MI AM'))"
    myrec = OraDatabase.ExecuteSQL(mySQL)
    If OraDatabase.LastServerErr = 0 Then
      MsgBox ssdtcboDate.Text + " has been Closed.", vbInformation, "Day Closure Successful"
      OraSession.CommitTrans
    Else
      OraSession.Rollback
    End If
  Else
    'Record Already Exists for the Date Selected - Update only the Time
    OraSession.BeginTrans
    mySQL = "Update DayClosure Set Time_Closed = to_date('" + Format(Now, "mm/dd/yyyy hh:nn am/pm") + "','MM/DD/yyyy HH:MI AM') where Supervisor_ID = '" + UserID + "' and Hire_date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy')"
    myrec = OraDatabase.ExecuteSQL(mySQL)
    If OraDatabase.LastServerErr = 0 Then
      OraSession.CommitTrans
      MsgBox ssdtcboDate.Text + " has been Closed.", vbInformation, "Day Closure Successful"
    Else
      OraSession.Rollback
    End If
    'Set the Flag to N if it is Y previously.
    mySQL = "Select Flag from DayClosure where Supervisor_ID = '" + UserID + "' and Hire_date = to_date('" + ssdtcboDate.Text + "','mm/dd/yyyy')"
    Set myDayRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
    If myDayRS.EOF And myDayRS.BOF Then
      'Do Nothing
    Else
      If myDayRS.Fields("Flag").Value = "Y" Then
        myDayRS.Edit
        myDayRS.Fields("Flag").Value = "N"
        myDayRS.Update    'Authorization Revoked as Day is Closed
      End If
    End If
    myDayRS.Close
    Set myDayRS = Nothing
  End If
  myChkRS.Close
  Set myChkRS = Nothing
End Sub

'****************************************
'To return back to previous screen
'****************************************
Private Sub cmdExit_Click()
  Unload Me
End Sub

'****************************************
'To view the status of Day Closure
'****************************************
Private Sub cmdStatus_Click()
  ClosedDate = ssdtcboDate.Text
  Me.Hide
  frmStatus.Show
End Sub

Private Sub Form_Load()
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"

  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
  'To populate default values for UserID
  txtSupervisorID = UserID
End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  frmHiring.Show
End Sub
