VERSION 5.00
Begin VB.Form frmDetlsOption 
   AutoRedraw      =   -1  'True
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "ORDER NUMBER / PALLET NUMBER"
   ClientHeight    =   3435
   ClientLeft      =   5340
   ClientTop       =   1950
   ClientWidth     =   4680
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   ScaleHeight     =   3435
   ScaleWidth      =   4680
   Begin VB.CommandButton cmdTransfers 
      Caption         =   "&Transfers"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   1298
      TabIndex        =   4
      Top             =   1620
      Width           =   2085
   End
   Begin VB.CommandButton cmdExit 
      Cancel          =   -1  'True
      Caption         =   "&Exit"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   1673
      TabIndex        =   3
      Top             =   2250
      Width           =   1335
   End
   Begin VB.CommandButton cmdOrderdtls 
      Caption         =   "&Order Detail"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   1298
      TabIndex        =   1
      Top             =   960
      Width           =   2085
   End
   Begin VB.CommandButton cmdPltDtls 
      Caption         =   "&Pallet Detail"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   1298
      TabIndex        =   0
      Top             =   360
      Width           =   2085
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Click on any one button to see the Pallet / Order details"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   405
      Left            =   150
      TabIndex        =   2
      Top             =   2850
      Width           =   4380
   End
End
Attribute VB_Name = "frmDetlsOption"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private Sub cmdExit_Click()
    Unload frmOrderNum
    Unload frmPltCorrection
    Unload Me
End Sub

Private Sub cmdOrderdtls_Click()
    Unload Me
    frmOrderNum.Show
    'Me.Show
    gsOrderNum = ""
    giCustId = 0
    gsLotNum = ""
End Sub

Private Sub cmdPltDtls_Click()
    Unload Me
    frmPltCorrection.Show
    'Me.Show
    gsOrderNum = ""
    giCustId = 0
    gsLotNum = ""
    
End Sub

Private Sub cmdTransfers_Click()
   Unload Me
     frmTransfer.Show
     gsLotNum = ""
End Sub

'Private Sub cmdTransfer_Click()
'
'End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
End Sub
