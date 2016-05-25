VERSION 5.00
Begin VB.Form frmSumRep 
   Caption         =   "Summary Report"
   ClientHeight    =   5565
   ClientLeft      =   3540
   ClientTop       =   2775
   ClientWidth     =   7725
   BeginProperty Font 
      Name            =   "MS Sans Serif"
      Size            =   9.75
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   ScaleHeight     =   5565
   ScaleWidth      =   7725
   Begin VB.CommandButton cmdTimeKeeper 
      Caption         =   "Total Hours Added By Time Keeper"
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
      Left            =   1560
      TabIndex        =   4
      Top             =   3360
      Width           =   4815
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "&Exit"
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
      Left            =   1560
      TabIndex        =   2
      Top             =   4320
      Width           =   4815
   End
   Begin VB.CommandButton cmdDailyRep 
      Caption         =   "Employee &Daily Hours Report"
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
      Left            =   1560
      TabIndex        =   1
      Top             =   2400
      Width           =   4815
   End
   Begin VB.CommandButton cmdWeeklyRep 
      Caption         =   "Employee &Workly Hours Report"
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
      Left            =   1560
      TabIndex        =   0
      Top             =   1440
      Width           =   4815
   End
   Begin VB.Label labTitle 
      Alignment       =   2  'Center
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   855
      Left            =   960
      TabIndex        =   3
      Top             =   0
      Width           =   6495
   End
   Begin VB.Image Image1 
      BorderStyle     =   1  'Fixed Single
      Height          =   855
      Left            =   0
      Picture         =   "frmSumRep.frx":0000
      Stretch         =   -1  'True
      Top             =   0
      Width           =   855
   End
   Begin VB.Line Line1 
      X1              =   0
      X2              =   7680
      Y1              =   840
      Y2              =   840
   End
End
Attribute VB_Name = "frmSumRep"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit          '5/2/2007 HD2759 Rudy:

Private Sub cmdDailyRep_Click()
    Unload frmSumRep
    frmSumRep.Hide
    Load frmDailyHoursReport
    frmDailyHoursReport.Show
End Sub

Private Sub cmdExit_Click()
    Unload frmSumRep
    frmSumRep.Hide
    Load frmHiring
    frmHiring.Show
End Sub

Private Sub cmdTimeKeeper_Click()
    Unload frmSumRep
    frmSumRep.Hide
    Load frmTimeKeeperSumRep
    frmTimeKeeperSumRep.Show
End Sub

Private Sub cmdWeeklyRep_Click()
    Unload frmSumRep
    frmSumRep.Hide
    Load frmWeeklyRep
    frmWeeklyRep.Show
End Sub

Private Sub Form_Load()
  labTitle.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
End Sub
