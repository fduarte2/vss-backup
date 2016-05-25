VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Begin VB.Form frmException 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Exception Reports"
   ClientHeight    =   8340
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   7785
   Icon            =   "frmException.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   8340
   ScaleWidth      =   7785
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdExceptionalHour 
      Caption         =   "EXCEPTIONAL HOURS REPORT"
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
      Height          =   615
      Left            =   240
      TabIndex        =   10
      Top             =   1860
      Width           =   7215
   End
   Begin VB.CommandButton cmdHire 
      Caption         =   "&HIRED BUT NOT PAID EXCEPTION REPORT"
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
      Height          =   615
      Left            =   240
      TabIndex        =   6
      Top             =   6810
      Width           =   7215
   End
   Begin VB.CommandButton cmdDayClose 
      Caption         =   "&DAY CLOSURE EXCEPTION REPORT"
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
      Height          =   615
      Left            =   240
      TabIndex        =   5
      Top             =   5970
      Width           =   7215
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
      Height          =   615
      Left            =   6360
      TabIndex        =   7
      Top             =   7620
      Width           =   1095
   End
   Begin VB.CommandButton cmdSwipeOut2 
      Caption         =   "SWIPE OUT EXCEPTION REPORT &2"
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
      Height          =   615
      Left            =   240
      TabIndex        =   4
      Top             =   5130
      Width           =   7215
   End
   Begin VB.CommandButton cmdCheckOut 
      Caption         =   "SWIPE OUT EXCEPTION REPORT &1"
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
      Height          =   615
      Left            =   240
      TabIndex        =   3
      Top             =   4290
      Width           =   7215
   End
   Begin VB.CommandButton cmdTempID 
      Caption         =   "&TEMPORARY EMPLOYEE ID REPORT"
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
      Height          =   615
      Left            =   240
      TabIndex        =   8
      Top             =   4290
      Visible         =   0   'False
      Width           =   7215
   End
   Begin VB.CommandButton cmdTimeOut 
      Caption         =   "TIME &OUT MISMATCH REPORT"
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
      Height          =   615
      Left            =   240
      TabIndex        =   2
      Top             =   3450
      Width           =   7215
   End
   Begin VB.CommandButton cmdTimeIn 
      Caption         =   "TIME &IN MISMATCH REPORT"
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
      Height          =   615
      Left            =   240
      TabIndex        =   1
      Top             =   2610
      Width           =   7215
   End
   Begin VB.CommandButton cmdOverlap 
      Caption         =   "OVERLA&PPED HOURS EXCEPTION REPORT"
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
      Height          =   615
      Left            =   240
      TabIndex        =   0
      Top             =   1080
      Width           =   7215
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   7560
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
      TabIndex        =   9
      Top             =   0
      Width           =   6615
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
      Picture         =   "frmException.frx":0442
   End
End
Attribute VB_Name = "frmException"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit            '5/2/2007 HD2759 Rudy:

'****************************************
'To Show Selection Criteria Form for Day Closure Exception Report
'****************************************
Private Sub cmdDayClose_Click()
  ReportTitle = "DayClose"
  Me.Hide
  frmSelectDate.Show
End Sub

Private Sub cmdExceptionalHour_Click()
    Me.Hide
    frmExceptionalHour.Show
End Sub

'****************************************
'To Show Select Date Form for Hired but NOT Paid Exception Report
'****************************************
Private Sub cmdHire_Click()
  ReportTitle = "HireExp"
  Me.Hide
  frmSelectDate.Show
End Sub

'****************************************
'To Show Selection Criteria Form for Double Entry Exception Report
'****************************************
Private Sub cmdOverlap_Click()
  ReportTitle = "DoubleEntry"
  Me.Hide
  frmSelect.Show
End Sub

'****************************************
'To Show Selection Criteria Form for Time In Exception Report
'****************************************
Private Sub cmdTimeIn_Click()
  ReportTitle = "TimeIn"
  Me.Hide
  frmSelect.Show
End Sub

'****************************************
'To Show Selection Criteria Form for Time Out Exception Report
'****************************************
Private Sub cmdTimeOut_Click()
  ReportTitle = "TimeOut"
  Me.Hide
  frmSelect.Show
End Sub

'****************************************
'To Show Te mporary Employee ID Exception Report
'****************************************
Private Sub cmdTempID_Click()
  DE_TempID.rsTempID.Open
  DR_TempID.Refresh
  DR_TempID.Show
  DE_TempID.rsTempID.Close
End Sub

'****************************************
'To Show Selection Criteria Form for Swipe Out Exception Report 1
'****************************************
Private Sub cmdCheckOut_Click()
  ReportTitle = "SwipeOutException1"
  Me.Hide
  frmSelect.Show
End Sub

'****************************************
'To Show Selection Criteria Form for Swipe Out Exception Report 2
'****************************************
Private Sub cmdSwipeOut2_Click()
  ReportTitle = "SwipeOutException2"
  Me.Hide
  frmSelect.Show
End Sub

'****************************************
'To Close the Current Form and Show the Previous One
'****************************************
Private Sub cmdExit_Click()
  Unload Me
End Sub

Private Sub Form_Load()
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
  'Check for Authorization and Enable / Disable Buttons as per the Group ID
  Dim myGrpSQL As String, Group_AccessRS As Object
  myGrpSQL = "Select * from Group_Access where Upper(Group_ID) = '" + UCase(Trim(GroupID)) + "' and Upper(Scr_Rpt_ID) like 'R%' Order By Scr_Rpt_ID"
  Set Group_AccessRS = OraDatabase.DBCreateDynaset(myGrpSQL, 0&)
  If Group_AccessRS.BOF And Group_AccessRS.EOF Then
    'Do Nothing - Already all buttons are disabled
  Else
    'Having Access to One or More Reports - Enable appropriate Buttons
    Group_AccessRS.MoveFirst
    Do While Not Group_AccessRS.EOF
      Select Case UCase(Trim(Group_AccessRS.Fields("Scr_Rpt_Id").Value))
      Case "R2"
        cmdTimeOut.Enabled = True
      Case "R3"
        cmdTimeIn.Enabled = True
      Case "R4"
        cmdTempID.Enabled = True
      Case "R5"
        cmdOverlap.Enabled = True
      Case "R6"
        cmdCheckOut.Enabled = True
      Case "R7"
        cmdSwipeOut2.Enabled = True
      Case "R8"
        cmdDayClose.Enabled = True
      Case "R9"
        cmdHire.Enabled = True
      Case "R11"
        cmdExceptionalHour.Enabled = True
      End Select
      Group_AccessRS.MoveNext
    Loop
  End If
  Group_AccessRS.Close
  Set Group_AccessRS = Nothing
End Sub

'****************************************
'To Close the Current Form and Show the Previous One
'****************************************
Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  frmHiring.Show
End Sub
