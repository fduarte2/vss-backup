VERSION 5.00
Begin VB.Form frmReports 
   Caption         =   "Reports"
   ClientHeight    =   4455
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   6630
   LinkTopic       =   "Form1"
   ScaleHeight     =   4455
   ScaleWidth      =   6630
   StartUpPosition =   3  'Windows Default
   Begin VB.Frame Frame1 
      Caption         =   "Click On Button"
      Height          =   3015
      Left            =   0
      TabIndex        =   1
      Top             =   960
      Width           =   6615
      Begin VB.CommandButton cmdManifest 
         Caption         =   "&Manifests"
         Height          =   375
         Left            =   2040
         TabIndex        =   5
         Top             =   2280
         Width           =   2535
      End
      Begin VB.CommandButton cmdWithdrals 
         Caption         =   "&Withdrawals"
         Height          =   375
         Left            =   2040
         TabIndex        =   4
         Top             =   1680
         Width           =   2535
      End
      Begin VB.CommandButton cmdBilling 
         Caption         =   "&Billing"
         Height          =   375
         Left            =   2040
         TabIndex        =   3
         Top             =   1080
         Width           =   2535
      End
      Begin VB.CommandButton cmdInventory 
         Caption         =   "&Inventory"
         Height          =   375
         Left            =   2040
         TabIndex        =   2
         Top             =   480
         Width           =   2535
      End
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   375
      Left            =   0
      TabIndex        =   6
      Top             =   4080
      Width           =   6615
   End
   Begin VB.Label Label1 
      Caption         =   "Reports of Inventory and Billing"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   18
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   840
      TabIndex        =   0
      Top             =   240
      Width           =   5415
   End
End
Attribute VB_Name = "frmReports"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private Sub cmdInventory_Click()
    frmInventory.Show
    
End Sub

Private Sub Form_Load()
    iByCustDate = False
    iByShipDate = False
    
'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    lblStatus.Caption = "Please wait..."
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    
      
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err.Description & " - " & Err.Number & " occurred while processing.", vbExclamation, "Inventory History"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0
End Sub
