VERSION 5.00
Begin VB.Form frmDashBoard 
   Appearance      =   0  'Flat
   BackColor       =   &H80000005&
   Caption         =   "Set Free Time Status"
   ClientHeight    =   1095
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   4680
   LinkTopic       =   "Form1"
   ScaleHeight     =   1095
   ScaleWidth      =   4680
   StartUpPosition =   2  'CenterScreen
   Begin VB.Label lblDisplay 
      Appearance      =   0  'Flat
      BackColor       =   &H00000000&
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H0000FF00&
      Height          =   735
      Left            =   240
      TabIndex        =   0
      Top             =   120
      Width           =   4095
   End
End
Attribute VB_Name = "frmDashBoard"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private Sub Form_Load()
    Me.lblDisplay.Caption = "Please Wait"
    
End Sub
