VERSION 5.00
Object = "{20C62CAE-15DA-101B-B9A8-444553540000}#1.1#0"; "MSMAPI32.OCX"
Begin VB.Form frmSendMail 
   AutoRedraw      =   -1  'True
   Caption         =   "SEND MAIL"
   ClientHeight    =   3765
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   7785
   BeginProperty Font 
      Name            =   "Times New Roman"
      Size            =   9
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   ScaleHeight     =   3765
   ScaleWidth      =   7785
   StartUpPosition =   3  'Windows Default
   Begin MSMAPI.MAPIMessages MAPIMessages1 
      Left            =   480
      Top             =   3120
      _ExtentX        =   1005
      _ExtentY        =   1005
      _Version        =   393216
      AddressEditFieldCount=   1
      AddressModifiable=   0   'False
      AddressResolveUI=   0   'False
      FetchSorted     =   0   'False
      FetchUnreadOnly =   0   'False
   End
   Begin MSMAPI.MAPISession MAPISession1 
      Left            =   480
      Top             =   2160
      _ExtentX        =   1005
      _ExtentY        =   1005
      _Version        =   393216
      DownloadMail    =   -1  'True
      LogonUI         =   -1  'True
      NewSession      =   0   'False
   End
   Begin VB.TextBox txtMsg 
      Appearance      =   0  'Flat
      Height          =   975
      Left            =   2160
      MultiLine       =   -1  'True
      ScrollBars      =   2  'Vertical
      TabIndex        =   2
      Top             =   1680
      Width           =   4935
   End
   Begin VB.TextBox txtSubject 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2160
      TabIndex        =   1
      Top             =   1080
      Width           =   4935
   End
   Begin VB.TextBox txtEMail 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2160
      TabIndex        =   0
      Top             =   360
      Width           =   4935
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "EXIT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   5085
      TabIndex        =   5
      Top             =   3120
      Width           =   1095
   End
   Begin VB.CommandButton cmdClear 
      Caption         =   "CLEAR"
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
      Left            =   3345
      TabIndex        =   4
      Top             =   3120
      Width           =   1095
   End
   Begin VB.CommandButton cmdSend 
      Caption         =   "SEND"
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
      Left            =   1605
      TabIndex        =   3
      Top             =   3120
      Width           =   1095
   End
   Begin VB.Label Label3 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "ATTACHED MSG  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   240
      TabIndex        =   8
      Top             =   1680
      Width           =   1635
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "SUBJECT  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   885
      TabIndex        =   7
      Top             =   1133
      Width           =   990
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "E MAIL  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   1095
      TabIndex        =   6
      Top             =   413
      Width           =   780
   End
End
Attribute VB_Name = "frmSendMail"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim sEmailAddress As String

Const SESSION_SIGNON = 1
Const MESSAGE_COMPOSE = 6
Const ATTACHTYPE_DATA = 0
Const RECIPTYPE_TO = 1
Const RECIPTYPE_CC = 2
Const MESSAGE_RESOLVENAME = 13
Const MESSAGE_SEND = 3
Const SESSION_SIGNOFF = 2

Private Sub cmdExit_Click()
    Unload Me
End Sub

Private Sub cmdSend_Click()
               
        On Error GoTo MailErr
        
        If txtEMail = "" Then Exit Sub
        
        sEmailAddress = Trim(txtEMail)
        
        MAPISession1.SignOn
        MAPIMessages1.SessionID = MAPISession1.SessionID
        MAPIMessages1.Action = MESSAGE_COMPOSE
        MAPIMessages1.MsgIndex = -1
        MAPIMessages1.MsgSubject = Trim(txtSubject)
        MAPIMessages1.MsgNoteText = Trim(txtMsg)
        MAPIMessages1.RecipIndex = 0
        MAPIMessages1.RecipType = RECIPTYPE_TO
        MAPIMessages1.RecipDisplayName = sEmailAddress
        MAPIMessages1.AttachmentIndex = MAPIMessages1.AttachmentCount
        MAPIMessages1.AttachmentPathName = "D:\Inventory\output.csv"
        MAPIMessages1.Action = MESSAGE_RESOLVENAME      'Send the message:
        MAPIMessages1.Action = MESSAGE_SEND    'Close MAPI mail session:
        MAPISession1.Action = SESSION_SIGNOFF
        
        Exit Sub
        
MailErr:
    
    MsgBox err.Description, vbInformation, "ERROR WHILE SENDING EMAIL"
    err.Clear
    Exit Sub
              
End Sub


Private Sub Form_Load()
    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 3
    Me.Left = (Screen.Width - Me.Width) / 2
   
    
Err_FormLoad:

  
    On Error GoTo 0
    
End Sub
Private Sub txtEMail_Validate(Cancel As Boolean)
    
    If txtEMail = "" Then Exit Sub
    
    If InStr(1, txtEMail, "@") = 0 Then
        MsgBox "Invalid Email Address.", vbInformation, "EMAIL"
        txtEMail = ""
        Cancel = True
        Exit Sub
    End If
            
End Sub
