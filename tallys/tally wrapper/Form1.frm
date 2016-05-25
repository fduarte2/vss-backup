VERSION 5.00
Begin VB.Form Form1 
   Caption         =   "Form1"
   ClientHeight    =   6945
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   8775
   LinkTopic       =   "Form1"
   ScaleHeight     =   6945
   ScaleWidth      =   8775
   StartUpPosition =   1  'CenterOwner
   Begin VB.CommandButton Command3 
      Caption         =   "Command1"
      Height          =   1155
      Left            =   7320
      TabIndex        =   2
      Top             =   6960
      Width           =   4215
   End
   Begin VB.CommandButton Command2 
      Caption         =   "Command1"
      Height          =   1155
      Left            =   7320
      TabIndex        =   1
      Top             =   4800
      Width           =   4215
   End
   Begin VB.CommandButton Command1 
      Caption         =   "Command1"
      Height          =   1155
      Left            =   3000
      TabIndex        =   0
      Top             =   2280
      Width           =   4215
   End
End
Attribute VB_Name = "Form1"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private Sub Form_Load()
    Me.Height = Screen.Height
    Me.Width = Screen.Width
    
    
End Sub
