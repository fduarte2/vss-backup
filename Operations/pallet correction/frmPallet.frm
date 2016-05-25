VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmPallet 
   Caption         =   "PALLET INFORMATION"
   ClientHeight    =   6000
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   9045
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   ScaleHeight     =   6000
   ScaleWidth      =   9045
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdOrdDtls 
      Caption         =   "Order Details"
      Height          =   350
      Left            =   502
      Style           =   1  'Graphical
      TabIndex        =   29
      Top             =   5280
      Width           =   1200
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "Commit"
      Height          =   350
      Left            =   1870
      Style           =   1  'Graphical
      TabIndex        =   28
      Top             =   5280
      Width           =   1200
   End
   Begin VB.CommandButton cmdCancel 
      Caption         =   "Cancel"
      Height          =   350
      Left            =   3238
      Style           =   1  'Graphical
      TabIndex        =   27
      Top             =   5280
      Width           =   1200
   End
   Begin VB.CommandButton CmdExit 
      Caption         =   "Exit"
      Height          =   350
      Left            =   7342
      Style           =   1  'Graphical
      TabIndex        =   26
      Top             =   5280
      Width           =   1200
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "Delete"
      Height          =   350
      Left            =   4606
      Style           =   1  'Graphical
      TabIndex        =   25
      Top             =   5280
      Width           =   1200
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "Print"
      Height          =   350
      Left            =   5974
      TabIndex        =   24
      Top             =   5280
      Width           =   1200
   End
   Begin VB.Frame Frame1 
      Height          =   2895
      Left            =   195
      TabIndex        =   1
      Top             =   240
      Width           =   8655
      Begin VB.TextBox txtPltNum 
         Height          =   300
         Left            =   1650
         TabIndex        =   12
         Top             =   300
         Width           =   1215
      End
      Begin VB.TextBox txtVesselNo 
         Height          =   300
         Left            =   1650
         TabIndex        =   11
         Top             =   780
         Width           =   1215
      End
      Begin VB.TextBox txtQtyRecvd 
         Height          =   300
         Left            =   1650
         TabIndex        =   10
         Top             =   1740
         Width           =   1215
      End
      Begin VB.TextBox txtQtyInHouse 
         Enabled         =   0   'False
         Height          =   300
         Left            =   1650
         TabIndex        =   9
         Top             =   2340
         Width           =   1215
      End
      Begin VB.TextBox txtOwnerId 
         Height          =   300
         Left            =   4530
         TabIndex        =   8
         Top             =   1740
         Width           =   1095
      End
      Begin VB.TextBox txtDtRecd 
         Height          =   300
         Left            =   4530
         TabIndex        =   7
         Top             =   2340
         Width           =   1095
      End
      Begin VB.TextBox txtIntls 
         Height          =   300
         Left            =   7530
         TabIndex        =   6
         Top             =   2340
         Width           =   735
      End
      Begin VB.TextBox txtCommCode 
         Height          =   300
         Left            =   1650
         TabIndex        =   5
         Top             =   1260
         Width           =   1215
      End
      Begin VB.CommandButton cmdVessel 
         Height          =   315
         Left            =   2970
         Picture         =   "frmPallet.frx":0000
         Style           =   1  'Graphical
         TabIndex        =   4
         Top             =   780
         Width           =   375
      End
      Begin VB.CommandButton cmdCommCode 
         Height          =   315
         Left            =   2970
         Picture         =   "frmPallet.frx":0102
         Style           =   1  'Graphical
         TabIndex        =   3
         Top             =   1260
         Width           =   375
      End
      Begin VB.TextBox txtTmRecd 
         Enabled         =   0   'False
         Height          =   300
         Left            =   5730
         TabIndex        =   2
         Top             =   2340
         Width           =   975
      End
      Begin VB.Label lblVesselName 
         BackColor       =   &H80000009&
         BorderStyle     =   1  'Fixed Single
         Height          =   300
         Left            =   3450
         TabIndex        =   23
         Top             =   780
         Width           =   4815
      End
      Begin VB.Label lblOwnerName 
         BackColor       =   &H80000009&
         BorderStyle     =   1  'Fixed Single
         Height          =   300
         Left            =   5730
         TabIndex        =   22
         Top             =   1740
         Width           =   2535
      End
      Begin VB.Label lblCommName 
         BackColor       =   &H80000009&
         BorderStyle     =   1  'Fixed Single
         Height          =   300
         Left            =   3450
         TabIndex        =   21
         Top             =   1260
         Width           =   4815
      End
      Begin VB.Label lblCommCode 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         Caption         =   "Commodity Code"
         Height          =   195
         Left            =   240
         TabIndex        =   20
         Top             =   1320
         Width           =   1185
      End
      Begin VB.Label lblIntls 
         AutoSize        =   -1  'True
         Caption         =   "Initials"
         Height          =   195
         Left            =   6930
         TabIndex        =   19
         Top             =   2400
         Width           =   435
      End
      Begin VB.Label lblRecdDate 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         Caption         =   "Date Received"
         Height          =   195
         Left            =   3225
         TabIndex        =   18
         Top             =   2400
         Width           =   1080
      End
      Begin VB.Label lblOwnerId 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         Caption         =   "Owner Id"
         Height          =   195
         Left            =   3660
         TabIndex        =   17
         Top             =   1800
         Width           =   645
      End
      Begin VB.Label lblQtyInHouse 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         Caption         =   "Qty Inhouse"
         Height          =   195
         Left            =   570
         TabIndex        =   16
         Top             =   2400
         Width           =   855
      End
      Begin VB.Label lblOrgQtyRecvd 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         Caption         =   "Qty Received"
         Height          =   195
         Left            =   450
         TabIndex        =   15
         Top             =   1800
         Width           =   975
      End
      Begin VB.Label lblVesselNo 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         Caption         =   "Vessel No"
         Height          =   195
         Left            =   705
         TabIndex        =   14
         Top             =   840
         Width           =   720
      End
      Begin VB.Label lblPltNum 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         Caption         =   "Pallet Barcode"
         Height          =   195
         Left            =   390
         TabIndex        =   13
         Top             =   360
         Width           =   1035
      End
   End
   Begin SSDataWidgets_B.SSDBGrid ssdbgOrdDtls 
      Height          =   1575
      Left            =   495
      TabIndex        =   0
      Top             =   3360
      Width           =   8055
      ScrollBars      =   2
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   8
      AllowDelete     =   -1  'True
      AllowUpdate     =   0   'False
      MaxSelectedRows =   1
      RowHeight       =   423
      Columns.Count   =   8
      Columns(0).Width=   1799
      Columns(0).Caption=   "Date"
      Columns(0).Name =   "Date"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   7
      Columns(0).NumberFormat=   "MM/DD/YYYY"
      Columns(0).FieldLen=   10
      Columns(0).PromptInclude=   -1  'True
      Columns(1).Width=   1799
      Columns(1).Caption=   "Time"
      Columns(1).Name =   "Time"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   7
      Columns(1).FieldLen=   8
      Columns(1).Locked=   -1  'True
      Columns(2).Width=   2037
      Columns(2).Caption=   "Action"
      Columns(2).Name =   "Action"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   2
      Columns(2).FieldLen=   256
      Columns(2).Locked=   -1  'True
      Columns(3).Width=   2514
      Columns(3).Caption=   "Checker Name"
      Columns(3).Name =   "Checker Name"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(3).Locked=   -1  'True
      Columns(4).Width=   2011
      Columns(4).Caption=   "Order No"
      Columns(4).Name =   "Order No"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1693
      Columns(5).Caption=   "Customer Id"
      Columns(5).Name =   "Customer Id"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   3
      Columns(5).FieldLen=   6
      Columns(6).Width=   1376
      Columns(6).Caption=   "Quantity"
      Columns(6).Name =   "Quantity"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   5
      Columns(6).FieldLen=   256
      Columns(7).Width=   3201
      Columns(7).Caption=   "Service Code"
      Columns(7).Name =   "Service Code"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   2
      Columns(7).FieldLen=   256
      _ExtentX        =   14208
      _ExtentY        =   2778
      _StockProps     =   79
      Caption         =   "ORDER DETAILS"
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
End
Attribute VB_Name = "frmPallet"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
