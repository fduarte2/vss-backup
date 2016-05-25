VERSION 5.00
Begin VB.Form frmPalletList 
   Caption         =   "Pallet List"
   ClientHeight    =   8625
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   6855
   LinkTopic       =   "Form1"
   ScaleHeight     =   8625
   ScaleWidth      =   6855
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton btnCancel 
      Caption         =   "Cancel"
      Height          =   375
      Left            =   3480
      TabIndex        =   7
      Top             =   8160
      Width           =   2055
   End
   Begin VB.CommandButton btnContinue 
      Caption         =   "Continue"
      Height          =   375
      Left            =   1440
      TabIndex        =   6
      Top             =   8160
      Width           =   1815
   End
   Begin VB.TextBox txtBad 
      Height          =   7335
      Left            =   6840
      TabIndex        =   2
      Text            =   "Not Currently Visible"
      Top             =   600
      Visible         =   0   'False
      Width           =   4815
   End
   Begin VB.TextBox txtUpdate 
      Height          =   6975
      Left            =   120
      MultiLine       =   -1  'True
      ScrollBars      =   2  'Vertical
      TabIndex        =   1
      Top             =   600
      Width           =   3255
   End
   Begin VB.TextBox txtAdd 
      Height          =   6975
      Left            =   3480
      MultiLine       =   -1  'True
      ScrollBars      =   2  'Vertical
      TabIndex        =   0
      Top             =   600
      Width           =   3255
   End
   Begin VB.Label lblAdd 
      Alignment       =   1  'Right Justify
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   3600
      TabIndex        =   9
      Top             =   7680
      Width           =   3015
   End
   Begin VB.Label lblUpd 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   240
      TabIndex        =   8
      Top             =   7680
      Width           =   3015
   End
   Begin VB.Line Line1 
      X1              =   120
      X2              =   6720
      Y1              =   8040
      Y2              =   8040
   End
   Begin VB.Label Label3 
      Alignment       =   2  'Center
      Caption         =   "Cannot Be Modified ( - Reason)"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   7440
      TabIndex        =   5
      Top             =   240
      Visible         =   0   'False
      Width           =   3615
   End
   Begin VB.Label Label2 
      Alignment       =   2  'Center
      Caption         =   "To Be Updated"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   600
      TabIndex        =   4
      Top             =   240
      Width           =   2295
   End
   Begin VB.Label Label1 
      Alignment       =   2  'Center
      Caption         =   "To Be Added"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   4080
      TabIndex        =   3
      Top             =   240
      Width           =   2055
   End
End
Attribute VB_Name = "frmPalletList"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private Sub Form_load()
Me.Top = (Screen.Height - Me.Height) / 3
    Me.Left = (Screen.Width - Me.Width) / 2
End Sub
Private Sub btnCancel_Click()
    iResponse2 = "No"
    Unload Me
End Sub

Private Sub btnContinue_Click()
    iResponse2 = "Yes"
    Unload Me
End Sub
