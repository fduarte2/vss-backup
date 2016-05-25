VERSION 5.00
Begin VB.Form frmSplash 
   Caption         =   "View Time Card"
   ClientHeight    =   7365
   ClientLeft      =   270
   ClientTop       =   1710
   ClientWidth     =   10815
   ClipControls    =   0   'False
   ControlBox      =   0   'False
   Icon            =   "frmSplash.frx":0000
   KeyPreview      =   -1  'True
   LinkTopic       =   "Form2"
   ScaleHeight     =   7365
   ScaleWidth      =   10815
   StartUpPosition =   2  'CenterScreen
   Begin VB.Frame Frame1 
      Height          =   7290
      Left            =   67
      TabIndex        =   1
      Top             =   0
      Width           =   10680
      Begin VB.TextBox txtEmpID 
         Appearance      =   0  'Flat
         BackColor       =   &H8000000F&
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
         Left            =   4200
         TabIndex        =   0
         Top             =   3480
         Width           =   2655
      End
      Begin VB.CommandButton cmdView 
         Default         =   -1  'True
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   600
         Left            =   120
         TabIndex        =   2
         ToolTipText     =   "Update Changes"
         Top             =   6600
         Width           =   10410
      End
      Begin VB.Image imgLogo 
         Height          =   1665
         Left            =   4380
         Picture         =   "frmSplash.frx":000C
         Stretch         =   -1  'True
         Top             =   4320
         Width           =   2100
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         Caption         =   "SWIPE YOUR CARD NOW"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   27.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   660
         Left            =   1987
         TabIndex        =   5
         Top             =   2640
         Width           =   6840
      End
      Begin VB.Label lblCompany 
         Caption         =   "Diamond State Port Corporation"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   3540
         TabIndex        =   3
         Top             =   6390
         Width           =   3855
      End
      Begin VB.Label lblCompanyProduct 
         AutoSize        =   -1  'True
         Caption         =   "VIEW TIMESHEET"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   48
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   1125
         Left            =   1267
         TabIndex        =   4
         Top             =   600
         Width           =   8280
      End
   End
End
Attribute VB_Name = "frmSplash"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False

Option Explicit

Private Sub cmdView_Click()
  If Trim(txtEmpID) = vbNullString Or IsNull(txtEmpID) Then
    MsgBox "Please Enter Employee ID to View Time Card", vbInformation, "Data Required"
    txtEmpID.SetFocus
    Exit Sub
  End If
  BCField(2) = UCase(Trim(txtEmpID))
  Me.Hide
  frmTimeSheet.Show
End Sub

Private Sub Form_KeyPress(KeyAscii As Integer)
    Unload Me
End Sub

Private Sub Form_Load()
'    lblVersion.Caption = "Version " & App.Major & "." & App.Minor & "." & App.Revision
'    lblProductName.Caption = App.Title
End Sub

Private Sub Frame1_Click()
    Unload Me
End Sub
