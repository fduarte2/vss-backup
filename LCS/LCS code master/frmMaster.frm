VERSION 5.00
Begin VB.Form frmMaster 
   Caption         =   "LCS"
   ClientHeight    =   4950
   ClientLeft      =   5715
   ClientTop       =   3795
   ClientWidth     =   3255
   LinkTopic       =   "Form1"
   ScaleHeight     =   4950
   ScaleWidth      =   3255
   StartUpPosition =   2  'CenterScreen
   Begin VB.CommandButton cmdservgrp 
      Caption         =   "Service &Group"
      Height          =   375
      Left            =   480
      TabIndex        =   5
      Top             =   3720
      Width           =   2295
   End
   Begin VB.CommandButton cmdAsset 
      Caption         =   "&Asset Code"
      Height          =   375
      Left            =   480
      TabIndex        =   1
      Top             =   1320
      Width           =   2295
   End
   Begin VB.CommandButton cmdExit 
      Cancel          =   -1  'True
      Caption         =   "E&xit"
      Height          =   375
      Left            =   480
      TabIndex        =   6
      Top             =   4320
      Width           =   2295
   End
   Begin VB.CommandButton cmdSrvcCode 
      Caption         =   "&Service Code"
      Height          =   375
      Left            =   480
      TabIndex        =   4
      Top             =   3120
      Width           =   2295
   End
   Begin VB.CommandButton cmdEquipCode 
      Caption         =   "&Equipment Code"
      Height          =   375
      Left            =   480
      TabIndex        =   3
      Top             =   2520
      Width           =   2295
   End
   Begin VB.CommandButton cmdCommCode 
      Caption         =   "&Commodity Code"
      Height          =   375
      Left            =   480
      TabIndex        =   2
      Top             =   1920
      Width           =   2295
   End
   Begin VB.Label lblTitle 
      Alignment       =   2  'Center
      Caption         =   "Code Modification Application"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   735
      Left            =   240
      TabIndex        =   0
      Top             =   240
      Width           =   2775
   End
End
Attribute VB_Name = "frmMaster"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdAsset_Click()

  frmAssets.Show

End Sub

Private Sub cmdCommCode_Click()

  frmCommodity.Show

End Sub
Private Sub cmdEquipCode_Click()

  frmEquipment.Show

End Sub
Private Sub cmdExit_Click()

  Unload Me
  End

End Sub

Private Sub cmdservgrp_Click()

  frmServGroup.Show

End Sub

Private Sub cmdSrvcCode_Click()

  frmSrvcCodes.Show

End Sub

'4/30/2007 HD 2759 Rudy:
Private Sub Form_Load()
  
  Init
  
End Sub
