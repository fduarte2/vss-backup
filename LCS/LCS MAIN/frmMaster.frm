VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Begin VB.Form frmMaster 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Update Masters"
   ClientHeight    =   6990
   ClientLeft      =   45
   ClientTop       =   285
   ClientWidth     =   9915
   Icon            =   "frmMaster.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   6990
   ScaleWidth      =   9915
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdSubType 
      Caption         =   "SU&B TYPE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   825
      Left            =   5520
      MouseIcon       =   "frmMaster.frx":0442
      MousePointer    =   99  'Custom
      TabIndex        =   8
      ToolTipText     =   "Update Employee Sub Type"
      Top             =   4440
      Width           =   3660
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
      Height          =   825
      Left            =   5520
      MouseIcon       =   "frmMaster.frx":0884
      MousePointer    =   99  'Custom
      TabIndex        =   9
      ToolTipText     =   "Return Back"
      Top             =   5520
      Width           =   3660
   End
   Begin VB.CommandButton cmdService 
      Caption         =   "&SERVICE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   825
      Left            =   840
      MouseIcon       =   "frmMaster.frx":0CC6
      MousePointer    =   99  'Custom
      TabIndex        =   1
      ToolTipText     =   "Update Service"
      Top             =   2280
      Width           =   3660
   End
   Begin VB.CommandButton cmdEarning 
      Caption         =   "&EARNING TYPE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   825
      Left            =   840
      MouseIcon       =   "frmMaster.frx":1108
      MousePointer    =   99  'Custom
      TabIndex        =   4
      ToolTipText     =   "Update Earning Type"
      Top             =   5520
      Width           =   3660
   End
   Begin VB.CommandButton cmdEmpType 
      Caption         =   "E&MPLOYEE TYPE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   825
      Left            =   840
      MouseIcon       =   "frmMaster.frx":154A
      MousePointer    =   99  'Custom
      TabIndex        =   3
      ToolTipText     =   "Update Employee Type"
      Top             =   4440
      Width           =   3660
   End
   Begin VB.CommandButton cmdCustomer 
      Caption         =   "C&USTOMER"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   825
      Left            =   5520
      MouseIcon       =   "frmMaster.frx":198C
      MousePointer    =   99  'Custom
      TabIndex        =   7
      ToolTipText     =   "Update Customer"
      Top             =   3360
      Width           =   3660
   End
   Begin VB.CommandButton cmdEquip 
      Caption         =   "E&QUIPMENT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   825
      Left            =   5520
      MouseIcon       =   "frmMaster.frx":1DCE
      MousePointer    =   99  'Custom
      TabIndex        =   6
      ToolTipText     =   "Update Equipment Profile"
      Top             =   2280
      Width           =   3660
   End
   Begin VB.CommandButton cmdCommodity 
      Caption         =   "&COMMODITY"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   825
      Left            =   840
      MouseIcon       =   "frmMaster.frx":2210
      MousePointer    =   99  'Custom
      TabIndex        =   2
      ToolTipText     =   "Update Commodity"
      Top             =   3360
      Width           =   3660
   End
   Begin VB.CommandButton cmdLocation 
      Caption         =   "C&ATEGORY"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   825
      Left            =   840
      MouseIcon       =   "frmMaster.frx":2652
      MousePointer    =   99  'Custom
      TabIndex        =   0
      ToolTipText     =   "Update Location Category"
      Top             =   1200
      Width           =   3660
   End
   Begin VB.CommandButton cmdVessel 
      Caption         =   "&VESSEL"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   825
      Left            =   5520
      MouseIcon       =   "frmMaster.frx":2A94
      MousePointer    =   99  'Custom
      TabIndex        =   5
      ToolTipText     =   "Update Vessel"
      Top             =   1200
      Width           =   3660
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
      Picture         =   "frmMaster.frx":2ED6
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
      TabIndex        =   10
      Top             =   0
      Width           =   9015
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   9840
      Y1              =   960
      Y2              =   960
   End
End
Attribute VB_Name = "frmMaster"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit           '5/2/2007 HD2759 Rudy:

'****************************************
'To Show Commodity Details in Master Maintanence
'****************************************
Private Sub cmdCommodity_Click()
  Me.Hide
  MasterName = "Commodity"
  frmMasterMaint.Caption = "Commodity Master Maintanence"
  SetColWidth
  frmMasterMaint.Show
End Sub

'****************************************
'To Show Customer Details in Master Maintanence
'****************************************
Private Sub cmdCustomer_Click()
  Me.Hide
  MasterName = "Customer"
  frmMasterMaint.Caption = "Customer Master Maintanence"
  SetColWidth
  frmMasterMaint.Show
End Sub

'****************************************
'To Show Earning Type Details in Master Maintanence
'****************************************
Private Sub cmdEarning_Click()
  Me.Hide
  MasterName = "Earning_Type"
  frmMasterMaint.Caption = "Earning Type Master Maintanence"
  SetColWidth
  frmMasterMaint.Show
End Sub

'****************************************
'To Show Employee Type Details in Master Maintanence
'****************************************
Private Sub cmdEmpType_Click()
  Me.Hide
  MasterName = "Employee_Type"
  frmMasterMaint.Caption = "Employee Type Master Maintanence"
  SetColWidth
  frmMasterMaint.Show
End Sub

'****************************************
'To Show Equipment Details in Master Maintanence
'****************************************
Private Sub cmdEquip_Click()
  Me.Hide
  MasterName = "Equipment_Profile"
  frmMasterMaint.Caption = "Equipment Profile Master Maintanence"
  SetColWidth
  frmMasterMaint.Show
End Sub

'****************************************
'To Show Location Details in Master Maintanence
'****************************************
Private Sub cmdLocation_Click()
  Me.Hide
  MasterName = "Location_Category"
  frmMasterMaint.Caption = "Category Master Maintanence"
  SetColWidth
  frmMasterMaint.Show
End Sub

'****************************************
'To Show Service Details in Master Maintanence
'****************************************
Private Sub cmdService_Click()
  Me.Hide
  MasterName = "Service"
  frmMasterMaint.Caption = "Service Master Maintanence"
  SetColWidth
  frmMasterMaint.Show
End Sub

'****************************************
'To Show Employee Sub Type Details in Master Maintanence
'****************************************
Private Sub cmdSubType_Click()
  Me.Hide
  MasterName = "SubType"
  frmMasterMaint.Caption = "Employee Sub Type Master Maintanence"
  SetColWidth
  frmMasterMaint.Show
End Sub

'****************************************
'To Show Vessel Details in Master Maintanence
'****************************************
Private Sub cmdVessel_Click()
  Me.Hide
  MasterName = "Vessel"
  frmMasterMaint.Caption = "Vessel Master Maintanence"
  SetColWidth
  frmMasterMaint.Show
End Sub

Private Sub Form_Load()
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
End Sub

'****************************************
'To Close the Current Form and to Show the Previous Form
'****************************************
Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  frmHiring.Show
End Sub

'****************************************
'To Close the Current Form
'****************************************
Private Sub cmdExit_Click()
  Unload Me
End Sub

'****************************************
'To Set the Column Width for the Grid
'****************************************
Private Sub SetColWidth()
  frmMasterMaint.SSDBGrid1.Columns(0).Width = 2550.047
  If MasterName = "Service" Then
    'frmMasterMaint.SSDBGrid1.Columns(0).Width = 1400
    frmMasterMaint.SSDBGrid1.Columns(1).Width = 4350.047
    frmMasterMaint.SSDBGrid1.Columns(2).Width = 1200.189
    'frmMasterMaint.SSDBGrid1.Columns(3).Width =
  Else
    frmMasterMaint.SSDBGrid1.Columns(1).Width = 5550.236
  End If
End Sub
