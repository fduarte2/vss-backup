VERSION 5.00
Begin VB.Form frmChistory 
   BackColor       =   &H00FFFFC0&
   Caption         =   "Cargo Manifest Changes Form"
   ClientHeight    =   9720
   ClientLeft      =   2100
   ClientTop       =   615
   ClientWidth     =   9345
   LinkTopic       =   "Form1"
   ScaleHeight     =   9720
   ScaleWidth      =   9345
   Begin VB.TextBox txtChangeES 
      BackColor       =   &H00C0C0C0&
      Height          =   315
      Left            =   1680
      Locked          =   -1  'True
      MaxLength       =   12
      TabIndex        =   93
      Tag             =   "22"
      Top             =   6120
      Width           =   1425
   End
   Begin VB.TextBox txtChangeESName 
      BackColor       =   &H00C0C0C0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   3240
      Locked          =   -1  'True
      MaxLength       =   12
      TabIndex        =   92
      Tag             =   "22"
      Top             =   6120
      Width           =   3825
   End
   Begin VB.TextBox txtSupplierName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   3360
      MaxLength       =   12
      TabIndex        =   91
      Tag             =   "22"
      Top             =   2880
      Width           =   3825
   End
   Begin VB.TextBox txtSupplierCode 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   1680
      MaxLength       =   12
      TabIndex        =   90
      Tag             =   "22"
      Top             =   2880
      Width           =   1665
   End
   Begin VB.CommandButton cmdOrig 
      Caption         =   "&Original"
      Height          =   315
      Left            =   7560
      TabIndex        =   88
      Top             =   1050
      Width           =   1425
   End
   Begin VB.CommandButton cmdHistory 
      Caption         =   "&History"
      Height          =   315
      Left            =   7560
      TabIndex        =   87
      Top             =   1500
      Width           =   1425
   End
   Begin VB.TextBox txtChangeDateReceived 
      Height          =   315
      Left            =   6600
      TabIndex        =   37
      Top             =   7920
      Width           =   1335
   End
   Begin VB.CheckBox chkChangeReceiveCargo 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Inventory"
      Height          =   255
      Left            =   8160
      TabIndex        =   39
      Top             =   8040
      Width           =   975
   End
   Begin VB.TextBox txtChangeComm 
      Height          =   315
      Left            =   3480
      TabIndex        =   32
      Top             =   7080
      Width           =   1215
   End
   Begin VB.TextBox txtQtyInHouse 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   4440
      MaxLength       =   10
      TabIndex        =   22
      Top             =   4800
      Width           =   1665
   End
   Begin VB.TextBox txtChangeMark 
      Height          =   315
      Left            =   1440
      TabIndex        =   34
      Top             =   7440
      Width           =   5655
   End
   Begin VB.TextBox txtChangeBol 
      Height          =   315
      Left            =   5400
      TabIndex        =   33
      Top             =   7080
      Width           =   1695
   End
   Begin VB.TextBox txtChangeOwner 
      Height          =   315
      Left            =   1440
      TabIndex        =   31
      Top             =   7080
      Width           =   1215
   End
   Begin VB.CheckBox chkReceiveCargo 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Inventory"
      Enabled         =   0   'False
      Height          =   255
      Left            =   7320
      TabIndex        =   23
      Top             =   4440
      Width           =   975
   End
   Begin VB.ListBox lstbol 
      Height          =   645
      Left            =   1320
      TabIndex        =   80
      Top             =   1320
      Visible         =   0   'False
      Width           =   2715
   End
   Begin VB.ListBox lstmark 
      Height          =   645
      Left            =   1320
      TabIndex        =   79
      Top             =   1800
      Visible         =   0   'False
      Width           =   3945
   End
   Begin VB.Frame frachangetype 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Type"
      Height          =   1005
      Left            =   7320
      TabIndex        =   38
      Top             =   6840
      Width           =   1395
      Begin VB.OptionButton optchangetype 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Both"
         Height          =   195
         Index           =   2
         Left            =   180
         TabIndex        =   78
         Top             =   720
         Width           =   1155
      End
      Begin VB.OptionButton optchangetype 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Export"
         Height          =   195
         Index           =   1
         Left            =   180
         TabIndex        =   77
         Top             =   480
         Width           =   1155
      End
      Begin VB.OptionButton optchangetype 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Import"
         Height          =   195
         Index           =   0
         Left            =   180
         TabIndex        =   76
         Top             =   240
         Width           =   1035
      End
   End
   Begin VB.TextBox txtchangeinitials 
      Height          =   315
      Left            =   1320
      TabIndex        =   41
      Top             =   8880
      Width           =   1665
   End
   Begin VB.TextBox txtchangerem 
      Height          =   315
      Left            =   1320
      MultiLine       =   -1  'True
      TabIndex        =   40
      Top             =   8400
      Width           =   7815
   End
   Begin VB.ComboBox cbochangecargolocation 
      Height          =   315
      ItemData        =   "frmChistory.frx":0000
      Left            =   3960
      List            =   "frmChistory.frx":0002
      TabIndex        =   36
      Top             =   7920
      Width           =   1665
   End
   Begin VB.ComboBox cbochangemanstatus 
      Height          =   315
      ItemData        =   "frmChistory.frx":0004
      Left            =   1440
      List            =   "frmChistory.frx":0011
      TabIndex        =   35
      Top             =   7920
      Width           =   1665
   End
   Begin VB.ComboBox cbochangeweightunit 
      Height          =   315
      ItemData        =   "frmChistory.frx":002E
      Left            =   7260
      List            =   "frmChistory.frx":0030
      TabIndex        =   30
      Top             =   6480
      Width           =   1665
   End
   Begin VB.ComboBox cbochangeunit2 
      Height          =   315
      ItemData        =   "frmChistory.frx":0032
      Left            =   4440
      List            =   "frmChistory.frx":0034
      TabIndex        =   29
      Top             =   6480
      Width           =   1665
   End
   Begin VB.ComboBox cbochangeunit1 
      Height          =   315
      ItemData        =   "frmChistory.frx":0036
      Left            =   1440
      List            =   "frmChistory.frx":0038
      TabIndex        =   28
      Top             =   6480
      Width           =   1665
   End
   Begin VB.TextBox txtchangeweight 
      Alignment       =   1  'Right Justify
      Height          =   315
      Left            =   7260
      MultiLine       =   -1  'True
      TabIndex        =   27
      Top             =   5580
      Width           =   1665
   End
   Begin VB.TextBox txtchangeqty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Left            =   1440
      MultiLine       =   -1  'True
      TabIndex        =   25
      Top             =   5580
      Width           =   1665
   End
   Begin VB.TextBox txtchangeqty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Left            =   4440
      TabIndex        =   26
      Text            =   "0"
      Top             =   5580
      Width           =   1665
   End
   Begin VB.TextBox txtcargolocation 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   4440
      TabIndex        =   20
      Top             =   4440
      Width           =   1665
   End
   Begin VB.TextBox txtweightunit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   4440
      TabIndex        =   18
      Top             =   4080
      Width           =   1665
   End
   Begin VB.TextBox txtunit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   4440
      TabIndex        =   15
      Top             =   3660
      Width           =   1665
   End
   Begin VB.TextBox txtunit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   4440
      TabIndex        =   13
      Top             =   3300
      Width           =   1665
   End
   Begin VB.TextBox txtmanifeststatus 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   1440
      TabIndex        =   19
      Top             =   4440
      Width           =   1665
   End
   Begin VB.CommandButton cmdClearScreen 
      Caption         =   "C&lear Screen"
      Height          =   315
      Left            =   7320
      TabIndex        =   43
      Top             =   8880
      Width           =   1425
   End
   Begin VB.CommandButton cmdBOL 
      Height          =   315
      Left            =   3900
      Picture         =   "frmChistory.frx":003A
      Style           =   1  'Graphical
      TabIndex        =   8
      TabStop         =   0   'False
      Top             =   1320
      Width           =   345
   End
   Begin VB.CommandButton cmdMark 
      Height          =   315
      Left            =   5400
      Picture         =   "frmChistory.frx":0344
      Style           =   1  'Graphical
      TabIndex        =   10
      TabStop         =   0   'False
      Top             =   1740
      Width           =   345
   End
   Begin VB.Frame fraType 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Type"
      Height          =   1005
      Left            =   7320
      TabIndex        =   24
      Top             =   3180
      Width           =   1215
      Begin VB.OptionButton optImpex 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Import"
         Enabled         =   0   'False
         Height          =   255
         Index           =   0
         Left            =   180
         TabIndex        =   59
         Top             =   240
         Width           =   825
      End
      Begin VB.OptionButton optImpex 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Export"
         Enabled         =   0   'False
         Height          =   255
         Index           =   1
         Left            =   180
         TabIndex        =   60
         Top             =   480
         Width           =   825
      End
      Begin VB.OptionButton optImpex 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Both"
         Enabled         =   0   'False
         Height          =   255
         Index           =   2
         Left            =   180
         TabIndex        =   61
         Top             =   720
         Width           =   825
      End
   End
   Begin VB.TextBox txtBOL 
      Height          =   315
      Left            =   1320
      MaxLength       =   20
      TabIndex        =   7
      Top             =   1350
      Width           =   2505
   End
   Begin VB.CommandButton cmdRecipientList 
      Height          =   315
      Left            =   2370
      Picture         =   "frmChistory.frx":064E
      Style           =   1  'Graphical
      TabIndex        =   4
      TabStop         =   0   'False
      Top             =   540
      Width           =   345
   End
   Begin VB.CommandButton cmdVesselList 
      Height          =   315
      Left            =   2370
      Picture         =   "frmChistory.frx":0958
      Style           =   1  'Graphical
      TabIndex        =   2
      TabStop         =   0   'False
      Top             =   120
      Width           =   345
   End
   Begin VB.TextBox txtRecipientId 
      Height          =   315
      Left            =   1320
      MaxLength       =   6
      TabIndex        =   3
      Top             =   540
      Width           =   975
   End
   Begin VB.TextBox txtCustomerName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   2760
      MaxLength       =   40
      TabIndex        =   45
      Top             =   540
      Width           =   3435
   End
   Begin VB.TextBox txtVesselName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   2760
      MaxLength       =   40
      TabIndex        =   16
      Top             =   120
      Width           =   3435
   End
   Begin VB.TextBox txtLRNum 
      Height          =   315
      Left            =   1320
      MaxLength       =   7
      TabIndex        =   1
      Top             =   120
      Width           =   975
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
      Height          =   315
      Left            =   5280
      TabIndex        =   42
      Top             =   8880
      Width           =   1425
   End
   Begin VB.CommandButton cmdRetrieve 
      Caption         =   "&Retrieve"
      Height          =   315
      Left            =   7560
      TabIndex        =   11
      Top             =   600
      Width           =   1425
   End
   Begin VB.TextBox txtDateReceived 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   1440
      MaxLength       =   10
      TabIndex        =   21
      Top             =   4800
      Width           =   1665
   End
   Begin VB.TextBox txtWeight 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   1440
      MaxLength       =   12
      TabIndex        =   17
      Top             =   4080
      Width           =   1665
   End
   Begin VB.TextBox txtQty2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   1440
      MaxLength       =   12
      TabIndex        =   14
      Tag             =   "26"
      Top             =   3720
      Width           =   1665
   End
   Begin VB.TextBox txtQty1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   1440
      MaxLength       =   12
      TabIndex        =   12
      Tag             =   "22"
      Top             =   3300
      Width           =   1665
   End
   Begin VB.CommandButton cmdCommodityList 
      Height          =   315
      Left            =   2370
      Picture         =   "frmChistory.frx":0C62
      Style           =   1  'Graphical
      TabIndex        =   6
      TabStop         =   0   'False
      Top             =   960
      Width           =   345
   End
   Begin VB.TextBox txtCommodityCode 
      Height          =   315
      Left            =   1320
      MaxLength       =   12
      TabIndex        =   5
      Top             =   960
      Width           =   975
   End
   Begin VB.TextBox txtCommodityName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   2760
      MaxLength       =   40
      TabIndex        =   47
      Top             =   960
      Width           =   3435
   End
   Begin VB.TextBox txtMark 
      Height          =   315
      Left            =   1320
      MaxLength       =   60
      TabIndex        =   9
      Top             =   1740
      Width           =   3975
   End
   Begin VB.Label Label21 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Export/Supplier"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   480
      TabIndex        =   94
      Top             =   6240
      Width           =   1125
   End
   Begin VB.Label Label20 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Exporter/Supplier"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   240
      TabIndex        =   89
      Top             =   3000
      Width           =   1365
   End
   Begin VB.Label Label19 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Date Rcvd"
      ForeColor       =   &H00000000&
      Height          =   165
      Left            =   5640
      TabIndex        =   86
      Top             =   8040
      Width           =   885
   End
   Begin VB.Label Label18 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Comm"
      Height          =   255
      Left            =   2760
      TabIndex        =   85
      Top             =   7200
      Width           =   615
   End
   Begin VB.Label Label17 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Qty In House"
      ForeColor       =   &H00000000&
      Height          =   195
      Left            =   3240
      TabIndex        =   84
      Top             =   4920
      Width           =   1125
   End
   Begin VB.Label Label16 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Mark"
      Height          =   255
      Left            =   600
      TabIndex        =   83
      Top             =   7560
      Width           =   735
   End
   Begin VB.Label Label15 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "BOL"
      Height          =   255
      Left            =   4680
      TabIndex        =   82
      Top             =   7200
      Width           =   495
   End
   Begin VB.Label Label14 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Owner"
      Height          =   255
      Left            =   720
      TabIndex        =   81
      Top             =   7200
      Width           =   615
   End
   Begin VB.Label Label13 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Initials"
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
      Left            =   120
      TabIndex        =   75
      Top             =   8880
      Width           =   975
   End
   Begin VB.Label Label12 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Remarks"
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
      Left            =   120
      TabIndex        =   74
      Top             =   8400
      Width           =   1035
   End
   Begin VB.Label Label11 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Location"
      Height          =   255
      Left            =   3240
      TabIndex        =   73
      Top             =   8040
      Width           =   735
   End
   Begin VB.Label Label10 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Manifest Status"
      Height          =   255
      Left            =   240
      TabIndex        =   72
      Top             =   8040
      Width           =   1095
   End
   Begin VB.Label Label9 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Weight Unit"
      Height          =   255
      Left            =   6240
      TabIndex        =   71
      Top             =   6600
      Width           =   915
   End
   Begin VB.Label Label8 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Unit2"
      Height          =   255
      Left            =   3720
      TabIndex        =   70
      Top             =   6600
      Width           =   555
   End
   Begin VB.Label Label7 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Unit1"
      Height          =   255
      Left            =   720
      TabIndex        =   69
      Top             =   6600
      Width           =   615
   End
   Begin VB.Label Label6 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Change "
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   -1  'True
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   240
      TabIndex        =   68
      Top             =   6000
      Width           =   795
   End
   Begin VB.Label Label5 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Weight"
      Height          =   255
      Left            =   6120
      TabIndex        =   67
      Top             =   5610
      Width           =   975
   End
   Begin VB.Label Label4 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Qty2"
      Height          =   255
      Left            =   3240
      TabIndex        =   66
      Top             =   5610
      Width           =   1095
   End
   Begin VB.Label Label2 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Qty1"
      Height          =   255
      Left            =   240
      TabIndex        =   65
      Top             =   5610
      Width           =   975
   End
   Begin VB.Line Line1 
      X1              =   180
      X2              =   9240
      Y1              =   5940
      Y2              =   5940
   End
   Begin VB.Label Label3 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Add/Delete Amount"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   -1  'True
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   240
      TabIndex        =   64
      Top             =   5280
      Width           =   1815
   End
   Begin VB.Shape Shape2 
      BorderColor     =   &H80000001&
      BorderWidth     =   2
      Height          =   3195
      Left            =   120
      Top             =   5160
      Width           =   9135
   End
   Begin VB.Label Label1 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Current Values"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   -1  'True
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   240
      TabIndex        =   63
      Top             =   2640
      Width           =   1635
   End
   Begin VB.Label lblBol 
      BackColor       =   &H00FFFFC0&
      Caption         =   "BOL*"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   180
      TabIndex        =   48
      Top             =   1410
      Width           =   885
   End
   Begin VB.Shape Shape1 
      BorderColor     =   &H80000002&
      BorderWidth     =   2
      Height          =   2685
      Left            =   120
      Top             =   2520
      Width           =   9135
   End
   Begin VB.Label lblWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Weight"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   240
      TabIndex        =   54
      Tag             =   "29"
      Top             =   4170
      Width           =   1125
   End
   Begin VB.Label lblQty1 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Quantity 1*"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   240
      TabIndex        =   50
      Top             =   3360
      Width           =   1125
   End
   Begin VB.Label lblOwner 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Owner"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   150
      TabIndex        =   44
      Top             =   600
      Width           =   975
   End
   Begin VB.Label lblVessel 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Vessel"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   150
      TabIndex        =   0
      Top             =   180
      Width           =   975
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   315
      Left            =   0
      TabIndex        =   62
      Top             =   9360
      Width           =   9345
   End
   Begin VB.Label lblDateReceived 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Date Received"
      ForeColor       =   &H00000000&
      Height          =   165
      Left            =   240
      TabIndex        =   58
      Top             =   4920
      Width           =   1125
   End
   Begin VB.Label lblCargoLocation 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Cargo Location"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   3240
      TabIndex        =   57
      Tag             =   "31"
      Top             =   4560
      Width           =   1125
   End
   Begin VB.Label lblManifestStatus 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Manifest Status"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   240
      TabIndex        =   56
      Top             =   4440
      Width           =   1125
   End
   Begin VB.Label lblWtUnit 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Weight Unit"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   3240
      TabIndex        =   55
      Top             =   4170
      Width           =   1125
   End
   Begin VB.Label lblUnit2 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Unit 2  "
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   3240
      TabIndex        =   53
      Tag             =   "27"
      Top             =   3720
      Width           =   1125
   End
   Begin VB.Label lblUnit1 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Unit 1*"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   3240
      TabIndex        =   51
      Tag             =   "23"
      Top             =   3390
      Width           =   1125
   End
   Begin VB.Label lblQty2 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Quantity 2"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   240
      TabIndex        =   52
      Tag             =   "25"
      Top             =   3780
      Width           =   1125
   End
   Begin VB.Label lblMark 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Mark*"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   180
      TabIndex        =   49
      Top             =   1800
      Width           =   885
   End
   Begin VB.Label lblCommodity 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Commodity"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   150
      TabIndex        =   46
      Top             =   1020
      Width           =   975
   End
End
Attribute VB_Name = "frmChistory"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Dim sContainerNum As String
Dim dContainerNum As Double
Dim iAlreadyExists As Integer
Dim iNoOriginalRecordM As Boolean
Dim iNoOriginalRecordT As Boolean
Dim iFirst As Boolean
Dim lChangeNum As Long
Dim lChangeNum2 As Long
Option Explicit

Function GetExporterName(iId As Long) As String
    gsSqlStmt = "SELECT * FROM EXPORTER_PROFILE WHERE EXPORTER_ID=" & iId
    Set dsEXPORTER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsEXPORTER_PROFILE.recordcount > 0 Then
        If Not dsEXPORTER_PROFILE.EOF Then
           GetExporterName = dsEXPORTER_PROFILE.FIELDS("EXPORTER_NAME").Value
        Else
           GetExporterName = ""
        End If
    End If
End Function

Private Sub cmdClearScreen_Click()
    Call ClearScreen("FULL")
End Sub

Private Sub cmdCommodityList_Click()
    Dim iPos As Integer
    
    Me.MousePointer = vbHourglass
    
    lblStatus.Caption = "Loading Commodities..."
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Commodity List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE"
    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.recordcount > 0 Then
        While Not dsCOMMODITY_PROFILE.EOF
            frmPV.lstPV.AddItem dsCOMMODITY_PROFILE.FIELDS("COMMODITY_CODE").Value & " : " & dsCOMMODITY_PROFILE.FIELDS("COMMODITY_NAME").Value
            dsCOMMODITY_PROFILE.MoveNext
        Wend
    End If
    
    lblStatus.Caption = "Commodities Loaded."
    Me.MousePointer = vbDefault
    
    frmPV.Show vbModal
    
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCommodityCode.Text = Left$(gsPVItem, iPos - 1)
            txtCommodityName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
End Sub

Private Sub cmdContinue_Click()
    If SaveSuccess Then
        Call ClearScreen("BOL_ONWARDS")
    End If
End Sub

Private Sub cmdBOL_Click()
    
    'Me.MousePointer = vbHourglass
    
    
    Dim i As Integer
    Dim iFound As Integer
    
    If Trim$(txtLrNum.Text) = "" Then
        MsgBox "Please enter Vessel Number.", vbInformation, "Retrieve"
        txtLrNum.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtRecipientId.Text) = "" Then
        MsgBox "Please enter Owner.", vbInformation, "Retrieve"
        txtRecipientId.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Please enter Commodity Code.", vbInformation, "Retrieve"
        txtCommodityCode.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtBOL.Text) = "" Then
        gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLrNum.Text
        gsSqlStmt = gsSqlStmt & " AND RECIPIENT_ID = " & txtRecipientId.Text
        gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & txtCommodityCode.Text
        Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.recordcount > 0 Then
            If dsCARGO_MANIFEST.recordcount > 0 Then
                'Get distinct BOLs - distict does not work somehow
                lstbol.Clear
                lstmark.Clear
                dsCARGO_MANIFEST.MoveFirst
                Do While Not dsCARGO_MANIFEST.EOF
                    iFound = False
                    For i = 0 To lstbol.ListCount - 1
                        If dsCARGO_MANIFEST.FIELDS("CARGO_BOL").Value = lstbol.List(i) Then
                            iFound = True
                        End If
                    Next 'i
                    If Not iFound Then
                        lstbol.AddItem dsCARGO_MANIFEST.FIELDS("CARGO_BOL").Value
                    End If
                    dsCARGO_MANIFEST.MoveNext
                Loop
                'MsgBox "Multiple BOLs exist. Please choose from the list and click Retrive button again.", vbInformation, "Retrieve"
                
                'Exit Sub
            End If
        Else
            MsgBox "Cargo Manifest information does not exist.", vbInformation, "Retrieve"
            Exit Sub
        End If
    End If
    
    
    If lstbol.ListCount > 0 Then
        lstbol.Visible = Not lstbol.Visible
    Else
        lstbol.Visible = False
    End If
    
End Sub

Private Sub cmdHistory_Click()
    If IsNull(Trim$(txtLrNum.Text)) Or Trim$(txtLrNum.Text) = "" Then
        MsgBox "Please enter the Vessel No. for History", vbInformation, "History"
        Exit Sub
    Else
        ship_no = Val(Trim$(txtLrNum.Text))
        frmManifestChanges.Show
    End If
End Sub

Private Sub cmdMark_Click()
    Dim i As Integer
    Dim iFound As Integer
    
    If Trim$(txtLrNum.Text) = "" Then
        MsgBox "Please enter Vessel Number.", vbInformation, "Retrieve"
        txtLrNum.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtRecipientId.Text) = "" Then
        MsgBox "Please enter Owner.", vbInformation, "Retrieve"
        txtRecipientId.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Please enter Commodity Code.", vbInformation, "Retrieve"
        txtCommodityCode.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtMark.Text) = "" Then
            gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLrNum.Text
            gsSqlStmt = gsSqlStmt & " AND RECIPIENT_ID = " & txtRecipientId.Text
            gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & txtCommodityCode.Text
            gsSqlStmt = gsSqlStmt & " AND CARGO_BOL = '" & txtBOL.Text & "'"
            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.recordcount > 0 Then
                If dsCARGO_MANIFEST.recordcount > 0 Then
                    'Get distinct Marks   -- To Do
                    lstmark.Clear
                    dsCARGO_MANIFEST.MoveFirst
                    Do While Not dsCARGO_MANIFEST.EOF
                        iFound = False
                        For i = 0 To lstmark.ListCount - 1
                            If dsCARGO_MANIFEST.FIELDS("CARGO_MARK").Value = lstmark.List(i) Then
                                iFound = True
                            End If
                        Next 'i
                        If Not iFound Then
                            lstmark.AddItem dsCARGO_MANIFEST.FIELDS("CARGO_MARK").Value
                        End If
                        dsCARGO_MANIFEST.MoveNext
                    Loop
                    'MsgBox "Multiple Marks exist. Please choose from the list and click Retrive button again.", vbInformation, "Retrieve"
                    'Exit Sub
                End If
            Else
                MsgBox "Cargo Manifest information does not exist.", vbInformation, "Retrieve"
                Exit Sub
            End If
    End If
    
    If lstmark.ListCount > 0 Then
        lstmark.Visible = Not lstmark.Visible
    Else
        lstmark.Visible = False
    End If
End Sub


Private Sub cmdOrig_Click()
    
    frmOrigManifest.Show

End Sub

Private Sub cmdRecipientList_Click()
    Dim iPos As Integer
    
    Me.MousePointer = vbHourglass
    
    lblStatus.Caption = "Loading Customers..."
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Customer List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.recordcount > 0 Then
        While Not dsCUSTOMER_PROFILE.EOF
            frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value
            dsCUSTOMER_PROFILE.MoveNext
        Wend
    End If
    
    lblStatus.Caption = "Customers Loaded."
    Me.MousePointer = vbDefault
    
    frmPV.Show vbModal
    
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtRecipientId.Text = Left$(gsPVItem, iPos - 1)
            txtCustomerName.Text = Mid$(gsPVItem, iPos + 3)
        End If
        txtCommodityCode.SetFocus
    End If
End Sub

Private Sub cmdRetrieve_Click()
    Dim i As Integer
    Dim iFound As Integer
    
    If Trim$(txtLrNum.Text) = "" Then
        MsgBox "Please enter Vessel Number.", vbInformation, "Retrieve"
        txtLrNum.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtRecipientId.Text) = "" Then
        MsgBox "Please enter Owner.", vbInformation, "Retrieve"
        txtRecipientId.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Please enter Commodity Code.", vbInformation, "Retrieve"
        txtCommodityCode.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtBOL.Text) = "" Then
        MsgBox "Please select BOL from list and click the Retrieve button", vbInformation, "Retrieve"
        txtBOL.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtMark.Text) = "" Then
            MsgBox "Please select the Mark from the list and click the Retrieve button", vbInformation, "Retrieve"
            txtMark.SetFocus
            Exit Sub
    End If
    
    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLrNum.Text
    gsSqlStmt = gsSqlStmt & " AND RECIPIENT_ID = " & txtRecipientId.Text
    gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & txtCommodityCode.Text
    gsSqlStmt = gsSqlStmt & " AND CARGO_BOL = '" & txtBOL.Text & "'"
    gsSqlStmt = gsSqlStmt & " AND CARGO_MARK = '" & txtMark.Text & "'"
    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.recordcount > 0 Then
        'Now we should have only one record
        If dsCARGO_MANIFEST.recordcount > 1 Then
            MsgBox "Multiple Cargo Manifests exist. Fatal Error. Please contact administrator.", vbExclamation, "Retrieve"
            Exit Sub
        End If
    Else
        MsgBox "Cargo Manifest information does not exist.", vbInformation, "Retrieve"
        Exit Sub
    End If
    
    If OraDatabase.LastServerErr <> 0 Then
        MsgBox "Error occured while retrieving. Please contact administrator.", vbExclamation, "Retrieve"
        Exit Sub
    End If
            
    'Show fields
    'txtBOL.Text = dsCARGO_MANIFEST.Fields("CARGO_BOL").Value
    'txtMark.Text = dsCARGO_MANIFEST.Fields("CARGO_MARK").Value
    txtSupplierCode.Text = dsCARGO_MANIFEST.FIELDS("EXPORTER_ID").Value
    If Trim(txtSupplierCode.Text) <> "" Then
        txtSupplierName.Text = GetExporterName(Val(dsCARGO_MANIFEST.FIELDS("EXPORTER_ID").Value))
    End If
    txtQty1.Text = dsCARGO_MANIFEST.FIELDS("QTY_EXPECTED").Value
    If Not IsNull(dsCARGO_MANIFEST.FIELDS("QTY1_UNIT").Value) Then
        txtunit1.Text = dsCARGO_MANIFEST.FIELDS("QTY1_UNIT").Value
    End If
    If Not IsNull(dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED").Value) Then
        txtQty2.Text = Round(CDbl(dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED").Value), 2)
    End If
    If Not IsNull(dsCARGO_MANIFEST.FIELDS("QTY2_UNIT").Value) Then
        txtunit2.Text = dsCARGO_MANIFEST.FIELDS("QTY2_UNIT").Value
    End If
    If Not IsNull(dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT").Value) Then
        txtWeight.Text = dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT").Value
    End If
    If Not IsNull(dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT_UNIT").Value) Then
        txtweightunit.Text = dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT_UNIT").Value
    End If
    If Not IsNull(dsCARGO_MANIFEST.FIELDS("CARGO_LOCATION").Value) Then
        txtcargolocation.Text = dsCARGO_MANIFEST.FIELDS("CARGO_LOCATION").Value
    End If
    If Not IsNull(dsCARGO_MANIFEST.FIELDS("MANIFEST_STATUS")) Then
        txtmanifeststatus.Text = dsCARGO_MANIFEST.FIELDS("MANIFEST_STATUS").Value
    End If
    
    If dsCARGO_MANIFEST.FIELDS("IMPEX").Value = "I" Then
      optImpex(0).Value = True
    ElseIf dsCARGO_MANIFEST.FIELDS("IMPEX").Value = "E" Then
      optImpex(1).Value = True
    Else
      optImpex(2).Value = True
    End If
            
    If optImpex(0).Value = True Then
        optchangetype(0).Value = True
        
    ElseIf optImpex(1).Value Then
        optchangetype(1).Value = True
    Else
        optchangetype(2).Value = True
    End If
            
    'Get date
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value & "'"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If dsCARGO_TRACKING.recordcount > 0 Then
            chkReceiveCargo.Value = 1
            
            If Not IsNull(dsCARGO_TRACKING.FIELDS("DATE_RECEIVED").Value) Then
                txtDateReceived.Text = Format$(dsCARGO_TRACKING.FIELDS("DATE_RECEIVED").Value, "MM/DD/YYYY")
                txtQtyInHouse.Text = dsCARGO_TRACKING.FIELDS("QTY_IN_HOUSE").Value
            End If
            chkChangeReceiveCargo.Value = 1
        End If
    End If
    
   txtchangeqty1.SetFocus
   Call fillChangeFields
   'txtchangeqty1.Text = "0"
End Sub
Private Sub cmdSave_Click()
    If SaveSuccess Then
        printChanges
        Call ClearScreen("SCREEN")
    End If
End Sub

Private Sub cmdVesselList_Click()
    Dim iPos As Integer
    
    Me.MousePointer = vbHourglass
    
    lblStatus.Caption = "Loading Vessels..."
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Vessel List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.recordcount > 0 Then
        While Not dsVESSEL_PROFILE.EOF
            frmPV.lstPV.AddItem dsVESSEL_PROFILE.FIELDS("LR_NUM").Value & " : " & dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
            dsVESSEL_PROFILE.MoveNext
        Wend
    End If
    
    lblStatus.Caption = "Vessels Loaded."
    Me.MousePointer = vbDefault
    
    frmPV.Show vbModal
    
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtLrNum.Text = Left$(gsPVItem, iPos - 1)
            txtVesselName.Text = Mid$(gsPVItem, iPos + 3)
            Call txtLRNum_LostFocus
        End If
        txtRecipientId.SetFocus
    End If
End Sub

Private Sub Form_Load()
    'Center the form
    
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    Me.MousePointer = vbHourglass
    lblStatus.Caption = "Logging to database..."
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
'    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
        cbochangemanstatus.ListIndex = 0
        optchangetype(0).Value = True
        
        'Load units
        gsSqlStmt = "SELECT * FROM UNITS"
        Set dsUNITS = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsUNITS.recordcount > 0 Then
            While Not dsUNITS.EOF
                cbochangeunit1.AddItem dsUNITS.FIELDS("UOM").Value
                cbochangeunit2.AddItem dsUNITS.FIELDS("UOM").Value
                cbochangeweightunit.AddItem dsUNITS.FIELDS("UOM").Value
                dsUNITS.MoveNext
            Wend
        End If
        
        'Load location category
        gsSqlStmt = "SELECT LOCATION_TYPE FROM LOCATION_CATEGORY ORDER BY LOCATION_TYPE"
        Set dsLOCATION_CATEGORY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsLOCATION_CATEGORY.recordcount > 0 Then
            While Not dsLOCATION_CATEGORY.EOF
                cbochangecargolocation.AddItem dsLOCATION_CATEGORY.FIELDS("LOCATION_TYPE").Value
                dsLOCATION_CATEGORY.MoveNext
            Wend
        End If
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    Me.MousePointer = vbDefault
    
    Call ClearScreen("FULL")
   
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Manifest"
    lblStatus.Caption = "Error Occured."
    Me.MousePointer = vbDefault
    On Error GoTo 0

End Sub

Private Sub lstBOL_Click()
    If lstbol.ListIndex >= 0 Then
        txtBOL.Text = lstbol.List(lstbol.ListIndex)
    End If
    lstbol.Visible = False
    txtMark.SetFocus
End Sub


Private Sub lstMark_Click()
    If lstmark.ListIndex >= 0 Then
        txtMark.Text = lstmark.List(lstmark.ListIndex)
    End If
    lstmark.Visible = False
    
End Sub

Private Sub optchangeimport_Click(Index As Integer)

End Sub

Private Sub Text1_Change()

End Sub

Private Sub txtBol_LostFocus()
    'Get Mark
End Sub




Private Sub txtChangeBol_LostFocus()
 
    
    If Trim$(txtChangeBol.Text) = "" Then
        MsgBox "This field can not be null.", vbExclamation, "BOL"
        txtChangeBol.SetFocus
    End If
    
End Sub

Private Sub txtChangeComm_LostFocus()
    
    gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '" & txtChangeComm.Text & "'"
    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.recordcount > 0 Then
            'txtCommodityName.Text = dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value
    Else
        MsgBox "Commodity does not exist.", vbExclamation, "Commodity"
    End If
End Sub

Private Sub txtChangeES_LostFocus()
    If Trim$(txtChangeES) <> "" Then
        txtChangeESName.Text = Trim(GetESName(Val(txtChangeOwner)))
    Else
        txtChangeESName = ""
    End If
End Sub
Function GetESName(esId As Integer) As String
    gsSqlStmt = "SELECT * FROM EXPORTER_PROFILE WHERE EXPORTER_ID = " & esId
        Set dsEXPORTER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsEXPORTER_PROFILE.recordcount > 0 Then
            GetESName = dsEXPORTER_PROFILE.FIELDS("EXPORTER_NAME").Value
        Else
            MsgBox "Exporter/Supplier does not exist.", vbExclamation, "EXPORTER/SUPPLIER"
            GetESName = ""
        End If
End Function

Private Sub txtChangeOwner_LostFocus()
    
    If Trim$(txtChangeOwner.Text) = "" Then
        MsgBox "This field can not be null.", vbExclamation, "Owner"
        txtChangeOwner.SetFocus
    Else
        txtChangeES.Text = txtChangeOwner.Text
    End If
    
    ' and call the old box-fill routine
    ' txtChangeES_LostFocus
End Sub



Private Sub txtCommodityCode_LostFocus()
    If Trim$(txtCommodityCode) <> "" Then
        gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & txtCommodityCode.Text
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.recordcount > 0 Then
            txtCommodityName.Text = dsCOMMODITY_PROFILE.FIELDS("COMMODITY_NAME").Value
        Else
            MsgBox "Commodity does not exist.", vbExclamation, "Commodity"
        End If
    End If
End Sub

Private Sub txtLRNum_LostFocus()
    If Trim$(txtLrNum) <> "" And IsNumeric(txtLrNum) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLrNum.Text
        Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.recordcount > 0 Then
            txtVesselName.Text = dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
            
            'Default Date Received from the Voyage
            gsSqlStmt = "SELECT DATE_DEPARTED FROM VOYAGE WHERE LR_NUM = " & txtLrNum.Text & " AND "
            gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1"
            Set dsVOYAGE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsVOYAGE.recordcount > 0 Then
                If Not IsNull(dsVOYAGE.FIELDS("DATE_DEPARTED").Value) Then
                    txtDateReceived.Text = Format(dsVOYAGE.FIELDS("DATE_DEPARTED").Value, "mm/dd/yyyy")
                End If
            End If
        Else
            MsgBox "Vessel does not exist.", vbExclamation, "Vessel"
        End If
    End If
End Sub

Private Sub txtchangeqty1_LostFocus()
    
    If chkReceiveCargo.Value = 1 Then
        
        If dsCARGO_TRACKING.FIELDS("QTY_IN_HOUSE").Value + Val(Trim$(txtchangeqty1.Text)) < 0 Then
            MsgBox "The qty in house is only " & dsCARGO_TRACKING.FIELDS("QTY_IN_HOUSE").Value & ". ", vbInformation, "Change Quantity"
            txtchangeqty1.Text = ""
            txtchangeqty1.SetFocus
            Exit Sub
        End If
        
    End If
    
    
    If Trim$(txtLrNum.Text) = "" Then
        MsgBox "Please enter Vessel Number.", vbInformation, "Check"
        txtLrNum.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtRecipientId.Text) = "" Then
        MsgBox "Please enter Owner.", vbInformation, "Check"
        txtRecipientId.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Please enter Commodity Code.", vbInformation, "Check"
        txtCommodityCode.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtBOL.Text) = "" Then
        MsgBox "Please enter Commodity Code.", vbInformation, "Check"
        txtBOL.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtMark.Text) = "" Then
        MsgBox "Please enter Mark.", vbInformation, "Check"
        txtMark.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtchangeqty1.Text) <> "" And Val(txtchangeqty1.Text) <> 0 Then
        txtchangeweight.Text = Val(Trim$(txtchangeqty1.Text)) * Val(Trim$(txtWeight.Text)) / Val(Trim$(txtQty1.Text))
    Else
        txtchangeweight.Text = "0"
    End If
    
    

End Sub

Private Sub txtRecipientId_LostFocus()
    If Trim$(txtRecipientId) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtRecipientId.Text
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.recordcount > 0 Then
            txtCustomerName.Text = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
    End If
End Sub


Public Function SaveSuccess() As Integer
    Dim lFreeDays As Long
    
    SaveSuccess = False
    If Trim$(txtLrNum.Text) = "" Then
        MsgBox "Please select a Vessel.", vbInformation, "Save"
        txtLrNum.SetFocus
        Exit Function
    End If
    
    If Trim$(txtRecipientId.Text) = "" Then
        MsgBox "Please select an Owner.", vbInformation, "Save"
        txtRecipientId.SetFocus
        Exit Function
    End If
    
    If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Please select a Commodity.", vbInformation, "Save"
        txtQty1.SetFocus
        Exit Function
    End If
    
    If Trim$(txtBOL.Text) = "" Then
        MsgBox "Please enter a BOL.", vbInformation, "Save"
        txtBOL.SetFocus
        Exit Function
    End If
    
    If Trim$(txtMark.Text) = "" Then
        MsgBox "Please enter a Mark.", vbInformation, "Save"
        txtMark.SetFocus
        Exit Function
    End If
    
    If Not IsNumeric(txtchangeqty1.Text) Or Trim$(txtchangeqty1.Text) = "" Then
        MsgBox "Enter numeric value for Quantity 1.", vbInformation, "Save"
        txtchangeqty1.SetFocus
        Exit Function
    End If
    
    If Trim$(txtchangeqty2.Text) <> "" Then
        If Not IsNumeric(txtchangeqty2.Text) Then
            MsgBox "Enter numeric value for Quantity 2.", vbInformation, "Save"
            txtchangeqty2.SetFocus
            Exit Function
        End If
    End If
    
    If Trim$(txtchangeweight.Text) <> "" Then
        If Not IsNumeric(txtchangeweight.Text) Then
            MsgBox "Enter numeric value for Weight.", vbInformation, "Save"
            txtchangeweight.SetFocus
            Exit Function
        End If
    End If
    
    If Trim$(txtChangeOwner.Text) = "" Then
        MsgBox "Please select an Owner.", vbInformation, "Save"
        txtChangeOwner.SetFocus
        Exit Function
    End If
    gsSqlStmt = "SELECT COUNT(*) THE_COUNT FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & txtChangeOwner.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If dsSHORT_TERM_DATA.FIELDS("THE_COUNT").Value <> 1 Then
        MsgBox "New owner of " & txtChangeOwner.Text & " not a valid entry.", vbInformation, "Save"
        txtChangeOwner.Text = ""
        txtChangeOwner.SetFocus
        Exit Function
    End If
    
    
    If Trim$(txtChangeComm.Text) = "" Then
        MsgBox "Please select a Commodity.", vbInformation, "Save"
        txtChangeComm.SetFocus
        Exit Function
    End If
    gsSqlStmt = "SELECT COUNT(*) THE_COUNT FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '" & txtChangeComm.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If dsSHORT_TERM_DATA.FIELDS("THE_COUNT").Value <> 1 Then
        MsgBox "New commodity of " & txtChangeComm.Text & " not a valid entry.", vbInformation, "Save"
        txtChangeComm.Text = ""
        txtChangeComm.SetFocus
        Exit Function
    End If
    
    If Trim$(txtChangeBol.Text) = "" Then
        MsgBox "Please enter a BOL.", vbInformation, "Save"
        txtChangeBol.SetFocus
        Exit Function
    End If
    
    If Trim$(txtChangeMark.Text) = "" Then
        MsgBox "Please enter a Mark.", vbInformation, "Save"
        txtChangeMark.SetFocus
        Exit Function
    End If
    
    If (Trim$(txtChangeOwner.Text) <> txtRecipientId Or txtChangeBol <> txtBOL.Text Or Trim$(txtChangeMark.Text) <> txtMark.Text) Then
        gsSqlStmt = "SELECT COUNT(*) THE_COUNT FROM CARGO_MANIFEST WHERE LR_NUM = '" & Trim$(txtLrNum.Text) & "' AND RECIPIENT_ID = '" & Trim$(txtChangeOwner.Text) & "' AND CARGO_BOL = '" & Trim$(txtChangeBol.Text) & "' AND CARGO_MARK = '" & Trim$(txtChangeMark.Text) & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If dsSHORT_TERM_DATA.FIELDS("THE_COUNT").Value > 0 Then
            MsgBox "New BOL / Mark already exists in system for VSL:" & Trim$(txtLrNum.Text) & " Cust:" & Trim$(txtChangeOwner.Text), vbInformation, "Save"
            txtChangeComm.Text = ""
            txtChangeComm.SetFocus
            Exit Function
        End If
    End If
    
    If Trim$(txtchangerem.Text) = "" Then
        MsgBox "This field cannot be blank.", vbInformation, "Save"
        txtchangerem.SetFocus
        Exit Function
        
    End If
    
    If Trim$(txtchangeinitials.Text) = "" Then
        MsgBox "This field cannot be blank.", vbInformation, "Save"
        txtchangeinitials.SetFocus
        Exit Function
    End If
    If Trim$(txtChangeES.Text) = "" Then
        MsgBox "This field cannot be blank.", vbInformation, "Save"
        txtChangeES.SetFocus
        Exit Function
    End If
    
    sContainerNum = dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value
    
    'Check original record
    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST_ORIGINAL WHERE CONTAINER_NUM ='" & Trim$(dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value) & "'"
    Set dsCARGO_MANIFEST_ORIGINAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_ORIGINAL.recordcount > 0 Then
        iNoOriginalRecordM = False
    Else
        iNoOriginalRecordM = True
    End If
    
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING_ORIGINAL WHERE LOT_NUM ='" & Trim$(dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value) & "'"
    Set dsCARGO_TRACKING_ORIGINAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING_ORIGINAL.recordcount > 0 Then
        iNoOriginalRecordT = False
    Else
        iNoOriginalRecordT = True
    End If
                
    'prepare for add new into cargo manifest changes
    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST_CHANGES"
    Set dsCARGO_MANIFEST_CHANGES = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    'Get the new max change number values, replace with the sequence later
    gsSqlStmt = "SELECT MAX(CHANGE_NUM) FROM CARGO_MANIFEST_CHANGES WHERE CONTAINER_NUM ='" & Trim$(dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value) & "'"
    Set dsCARGO_MANIFEST_CHANGES_MAX = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_CHANGES_MAX.recordcount > 0 Then
        If IsNull(dsCARGO_MANIFEST_CHANGES_MAX.FIELDS("MAX(CHANGE_NUM)").Value) Then
            lChangeNum = 1
        Else
            lChangeNum = dsCARGO_MANIFEST_CHANGES_MAX.FIELDS("MAX(CHANGE_NUM)").Value + 1
        End If
    Else
        lChangeNum = 1
    End If
    
    'PREPARE FOR ADD NEW INTO CARGO TRACKING CHANGES
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING_CHANGES"
    Set dsCARGO_TRACKING_CHANGES = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
       
    'Get the new max change number values, replace with the sequence later
    gsSqlStmt = "SELECT MAX(CHANGE_NUM) FROM CARGO_TRACKING_CHANGES WHERE LOT_NUM = '" & Trim$(dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value) & "'"
    Set dsCARGO_TRACKING_CHANGES_MAX = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING_CHANGES_MAX.recordcount > 0 Then
        If IsNull(dsCARGO_TRACKING_CHANGES_MAX.FIELDS("MAX(CHANGE_NUM)").Value) Then
            lChangeNum2 = 1
        Else
            lChangeNum2 = dsCARGO_TRACKING_CHANGES_MAX.FIELDS("MAX(CHANGE_NUM)").Value + 1
        End If
    Else
        lChangeNum2 = 1
    End If
    
    'prepare for add new cargo_tracking record
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING"
    Set dsCARGO_TRACKING_ALL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                   
                   
    If OraDatabase.LastServerErr = 0 Then
        'Begin a transaction
        OraSession.BeginTrans
        
        'Step 1 - Add to Original tables
        'ADD NEW INTO CARGO MANIFEST ORIGINAL IF NOT THERE
        If iNoOriginalRecordM Then
            dsCARGO_MANIFEST_ORIGINAL.AddNew
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("LR_NUM").Value = dsCARGO_MANIFEST.FIELDS("LR_NUM").Value
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("ARRIVAL_NUM").Value = dsCARGO_MANIFEST.FIELDS("ARRIVAL_NUM").Value
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CONTAINER_NUM").Value = dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("RECIPIENT_ID").Value = dsCARGO_MANIFEST.FIELDS("RECIPIENT_ID").Value
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_BOL").Value = dsCARGO_MANIFEST.FIELDS("CARGO_BOL").Value
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_MARK").Value = dsCARGO_MANIFEST.FIELDS("CARGO_MARK").Value
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("EXPORTER_ID").Value = dsCARGO_MANIFEST.FIELDS("EXPORTER_ID").Value
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY_EXPECTED").Value = dsCARGO_MANIFEST.FIELDS("QTY_EXPECTED").Value
            If Not IsNull(dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT")) Then
                dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_WEIGHT").Value = dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT").Value
            End If
            If Not IsNull(dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT_UNIT")) Then
                dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_WEIGHT_UNIT").Value = dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT_UNIT").Value
            End If
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("COMMODITY_CODE").Value = dsCARGO_MANIFEST.FIELDS("COMMODITY_CODE").Value
            If Not IsNull(dsCARGO_MANIFEST.FIELDS("CARGO_LOCATION")) Then
                dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_LOCATION").Value = dsCARGO_MANIFEST.FIELDS("CARGO_LOCATION").Value
            End If
            
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("IMPEX").Value = dsCARGO_MANIFEST.FIELDS("IMPEX").Value
            
            dsCARGO_MANIFEST_ORIGINAL.FIELDS("MANIFEST_STATUS").Value = dsCARGO_MANIFEST.FIELDS("MANIFEST_STATUS").Value
            
            If Not IsNull(dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED")) Then
                dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY2_EXPECTED").Value = dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED").Value
            End If
            
            If Not IsNull(dsCARGO_MANIFEST.FIELDS("QTY1_UNIT")) Then
                dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY1_UNIT").Value = dsCARGO_MANIFEST.FIELDS("QTY1_UNIT").Value
            End If
            If Not IsNull(dsCARGO_MANIFEST.FIELDS("QTY2_UNIT")) Then
                dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY2_UNIT").Value = dsCARGO_MANIFEST.FIELDS("QTY2_UNIT").Value
            End If
            
            dsCARGO_MANIFEST_ORIGINAL.Update
        
        End If
        
        If iNoOriginalRecordT Then 'No original record
            
            If chkReceiveCargo.Value = 1 Then 'in inventory
                
                If chkChangeReceiveCargo.Value = 0 Then 'take out from inventory
                    'DELETE THE CARGO_TRACKING RECORD
                    gsSqlStmt = "DELETE FROM CARGO_TRACKING WHERE LOT_NUM = '" & Trim$(dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value) & "'"
                    OraDatabase.ExecuteSQL (gsSqlStmt)
                Else
                    'add new into cargo tracking original
                    dsCARGO_TRACKING_ORIGINAL.AddNew
                    dsCARGO_TRACKING_ORIGINAL.FIELDS("LOT_NUM").Value = dsCARGO_TRACKING.FIELDS("LOT_NUM").Value
                    dsCARGO_TRACKING_ORIGINAL.FIELDS("QTY_IN_HOUSE").Value = dsCARGO_TRACKING.FIELDS("QTY_IN_HOUSE").Value
                    dsCARGO_TRACKING_ORIGINAL.FIELDS("COMMODITY_CODE").Value = dsCARGO_TRACKING.FIELDS("COMMODITY_CODE").Value
                    dsCARGO_TRACKING_ORIGINAL.FIELDS("DATE_RECEIVED").Value = dsCARGO_TRACKING.FIELDS("DATE_RECEIVED").Value
                    dsCARGO_TRACKING_ORIGINAL.FIELDS("OWNER_ID").Value = dsCARGO_TRACKING.FIELDS("OWNER_ID").Value
                    dsCARGO_TRACKING_ORIGINAL.FIELDS("STORAGE_CUST_ID").Value = dsCARGO_TRACKING.FIELDS("STORAGE_CUST_ID").Value
                    dsCARGO_TRACKING_ORIGINAL.FIELDS("QTY_RECEIVED").Value = dsCARGO_TRACKING.FIELDS("QTY_RECEIVED").Value
                    dsCARGO_TRACKING_ORIGINAL.FIELDS("RECEIVER_ID").Value = 4 'Super User
                    dsCARGO_TRACKING_ORIGINAL.FIELDS("WHSE_RCPT_NUM").Value = dsCARGO_TRACKING.FIELDS("WHSE_RCPT_NUM").Value
                    
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("CARGO_DESCRIPTION")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("CARGO_DESCRIPTION").Value = Trim$(dsCARGO_TRACKING.FIELDS("CARGO_DESCRIPTION").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("FREE_TIME_END")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("FREE_TIME_END").Value = Trim$(dsCARGO_TRACKING.FIELDS("FREE_TIME_END").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("STORAGE_END")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("STORAGE_END").Value = Trim$(dsCARGO_TRACKING.FIELDS("STORAGE_END").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("STORAGE_CUST_ID")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("STORAGE_CUST_ID").Value = Trim$(dsCARGO_TRACKING.FIELDS("STORAGE_CUST_ID").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("FREE_DAYS")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("FREE_DAYS").Value = Trim$(dsCARGO_TRACKING.FIELDS("FREE_DAYS").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("STORAGE_DAYS")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("STORAGE_DAYS").Value = Trim$(dsCARGO_TRACKING.FIELDS("STORAGE_DAYS").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("STORAGE_RATE")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("STORAGE_RATE").Value = Trim$(dsCARGO_TRACKING.FIELDS("STORAGE_RATE").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("START_FREE_TIME")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("START_FREE_TIME").Value = Trim$(dsCARGO_TRACKING.FIELDS("START_FREE_TIME").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("QTY_IN_STORAGE")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("QTY_IN_STORAGE").Value = Trim$(dsCARGO_TRACKING.FIELDS("QTY_IN_STORAGE").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("DOCK_RCPT")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("DOCK_RCPT").Value = Trim$(dsCARGO_TRACKING.FIELDS("DOCK_RCPT").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("BILL_DOCK_RCPT")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("BILL_DOCK_RCPT").Value = Trim$(dsCARGO_TRACKING.FIELDS("BILL_DOCK_RCPT").Value)
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("QTY_UNIT")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("QTY_UNIT").Value = Trim$(dsCARGO_TRACKING.FIELDS("QTY_UNIT").Value)
                    End If
                    If Not IsNull(dsCARGO_TRACKING.FIELDS("WAREHOUSE_LOCATION")) Then
                        dsCARGO_TRACKING_ORIGINAL.FIELDS("WAREHOUSE_LOCATION").Value = Trim$(dsCARGO_TRACKING.FIELDS("WAREHOUSE_LOCATION").Value)
                    End If
                    dsCARGO_TRACKING_ORIGINAL.Update
                End If
                
            Else 'not in the inventory
                
                If chkChangeReceiveCargo.Value = 1 Then 'add new cargo tracking
                    'Try get the vessel sailing date if it is sailed
                    gsSqlStmt = "SELECT DISTINCT START_FREE_TIME FROM CARGO_TRACKING T, CARGO_MANIFEST M " & _
                                "WHERE T.LOT_NUM = M.CONTAINER_NUM AND M.LR_NUM = " & txtLrNum.Text & " AND START_FREE_TIME IS NOT NULL"
                    Set dsTEMP = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                    
                    dsCARGO_TRACKING_ALL.AddNew
                    dsCARGO_TRACKING_ALL.FIELDS("LOT_NUM").Value = Trim$(dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value)
                    dsCARGO_TRACKING_ALL.FIELDS("QTY_IN_HOUSE").Value = dsCARGO_MANIFEST.FIELDS("QTY_expected").Value + Val(Trim$(txtchangeqty1.Text))
                    dsCARGO_TRACKING_ALL.FIELDS("COMMODITY_CODE").Value = txtChangeComm.Text
                    dsCARGO_TRACKING_ALL.FIELDS("DATE_RECEIVED").Value = txtChangeDateReceived.Text
                    dsCARGO_TRACKING_ALL.FIELDS("OWNER_ID").Value = txtChangeOwner.Text
                    dsCARGO_TRACKING_ALL.FIELDS("STORAGE_CUST_ID").Value = txtChangeOwner.Text
                    dsCARGO_TRACKING_ALL.FIELDS("QTY_RECEIVED").Value = dsCARGO_MANIFEST.FIELDS("QTY_expected").Value + Val(Trim$(txtchangeqty1.Text))
                    dsCARGO_TRACKING_ALL.FIELDS("RECEIVER_ID").Value = 4 'Super User
                    dsCARGO_TRACKING_ALL.FIELDS("WHSE_RCPT_NUM").Value = 0 'No Whse Rcpt Num Yet
                    
                    If Trim$(cbochangeunit1.Text) <> "" Then
                        dsCARGO_TRACKING_ALL.FIELDS("QTY_UNIT").Value = Trim$(cbochangeunit1.Text)
                    End If
                    
                    If Trim$(cbochangecargolocation.Text) <> "" Then
                        dsCARGO_TRACKING_ALL.FIELDS("WAREHOUSE_LOCATION").Value = Trim$(cbochangecargolocation.Text)
                    End If
                    
                    'Set Free Time
                    If (txtLrNum = -1) And (txtChangeComm <> 6172) Then
                        dsCARGO_TRACKING_ALL.FIELDS("START_FREE_TIME").Value = txtChangeDateReceived.Text
                        dsCARGO_TRACKING_ALL.FIELDS("FREE_TIME_END").Value = txtChangeDateReceived.Text
                    Else
                       'Check if commodity code is 6172, if so set WHSE_RCPT_NUM to -6172 and do not set Start Free Time/Free Time End
                        If txtChangeComm = 6172 Then
                            dsCARGO_TRACKING_ALL.FIELDS("WHSE_RCPT_NUM").Value = -6172
                        Else
                            'Get free days for this commodity
                            ' EDIT ADAM WALTER Sep 2008.  New function for determining free days
'                            gsSqlStmt = "SELECT * FROM FREE_TIME WHERE COMMODITY_CODE = " & txtChangeComm.Text
'                            Set dsFREE_TIME = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'                            If OraDatabase.LastServerErr = 0 And dsFREE_TIME.RecordCount > 0 Then
                            lFreeDays = FindFreeTime(txtChangeComm.Text, txtChangeOwner.Text, txtLrNum.Text)
                            If lFreeDays <> -1 Then
                                dsCARGO_TRACKING_ALL.FIELDS("FREE_DAYS").Value = lFreeDays
                            Else
                                MsgBox "No Free Time available for the Commodity Code: " & txtChangeComm.Text & vbCrLf & "Unable to save changes", vbExclamation, "Free Time Error"
                                OraSession.RollBack
                                Exit Function
                            End If
                                
                            'Vessel # 2 is for Chiquita Paper and # 3 is for Futter Lumber
                            If (txtLrNum = 2) Or (txtLrNum = 3) Then
                                dsCARGO_TRACKING_ALL.FIELDS("START_FREE_TIME").Value = txtChangeDateReceived.Text
                                dsCARGO_TRACKING_ALL.FIELDS("FREE_TIME_END").Value = DateAdd("d", lFreeDays, Format(txtChangeDateReceived.Text, "MM/DD/YYYY"))
                            Else
                                If Not IsNull(dsTEMP.FIELDS("START_FREE_TIME")) Then
                                    dsCARGO_TRACKING_ALL.FIELDS("START_FREE_TIME").Value = Format(dsTEMP.FIELDS("START_FREE_TIME").Value, "MM/DD/YYYY")
                                    dsCARGO_TRACKING_ALL.FIELDS("FREE_TIME_END").Value = DateAdd("d", lFreeDays, Format(dsTEMP.FIELDS("START_FREE_TIME").Value, "MM/DD/YYYY"))
                                End If
                            End If
                        End If
                    End If
                    
                    dsCARGO_TRACKING_ALL.Update
                End If
            End If
        
        Else 'if in the inventroy and changed before
            'if chkChangeReceiveCargo.Value = 0, take out from orininal and cargo tracking
            If chkChangeReceiveCargo.Value = 0 Then
                gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE LOT_NUM ='" & Trim$(dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value) & "'"
                Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.recordcount > 0 Then
                    OraSession.RollBack
                    lblStatus.Caption = "Process Failed."
                    MsgBox "Have withdrawnals already, can not be deleted, exit.", vbExclamation, "Delete the cargo tracking record"
                    Exit Function
                End If
                
                'add new change cargo tracking
                dsCARGO_TRACKING_CHANGES.AddNew
                dsCARGO_TRACKING_CHANGES.FIELDS("LOT_NUM").Value = dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value
                dsCARGO_TRACKING_CHANGES.FIELDS("CHANGE_NUM").Value = lChangeNum2
                dsCARGO_TRACKING_CHANGES.FIELDS("QTY_IN_HOUSE").Value = Val(Trim$(txtchangeqty1.Text))
                dsCARGO_TRACKING_CHANGES.FIELDS("COMMODITY_CODE").Value = txtChangeComm.Text
                dsCARGO_TRACKING_CHANGES.FIELDS("DATE_RECEIVED").Value = CStr(Format$(Now, "MM/DD/YYYY"))
                dsCARGO_TRACKING_CHANGES.FIELDS("OWNER_ID").Value = Trim$(txtChangeOwner.Text)
                dsCARGO_TRACKING_CHANGES.FIELDS("STORAGE_CUST_ID").Value = Trim$(txtChangeOwner.Text)
                dsCARGO_TRACKING_CHANGES.FIELDS("QTY_RECEIVED").Value = Val(Trim$(txtchangeqty1.Text))
                dsCARGO_TRACKING_CHANGES.FIELDS("RECEIVER_ID").Value = 4 'Super User
                dsCARGO_TRACKING_CHANGES.FIELDS("WHSE_RCPT_NUM").Value = dsCARGO_TRACKING.FIELDS("WHSE_RCPT_NUM").Value
                
                If cbochangeunit1.ListIndex >= 0 Then
                    dsCARGO_TRACKING_CHANGES.FIELDS("QTY_UNIT").Value = cbochangeunit1.Text
                End If
                
                If cbochangecargolocation.ListIndex >= 0 Then
                    dsCARGO_TRACKING_CHANGES.FIELDS("WAREHOUSE_LOCATION").Value = cbochangecargolocation.Text
                End If
                dsCARGO_TRACKING_CHANGES.FIELDS("DATE_OF_CHANGE").Value = CStr(Format$(Now, "MM/DD/YYYY"))
                dsCARGO_TRACKING_CHANGES.FIELDS("INITIALS").Value = Trim$(txtchangeinitials.Text)
                dsCARGO_TRACKING_CHANGES.FIELDS("REASON").Value = Trim$(txtchangerem.Text)
                dsCARGO_TRACKING_CHANGES.Update
                        
                gsSqlStmt = "DELETE FROM CARGO_TRACKING WHERE LOT_NUM = '" & Trim$(dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value) & "'"
                OraDatabase.ExecuteSQL (gsSqlStmt)
                
                'gsSqlStmt = "DELETE FROM CARGO_TRACKING_ORIGINAL WHERE LOT_NUM = '" & Trim$(dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value) & "'"
                'OraDatabase.ExecuteSQL (gsSqlStmt)
            End If
        End If
        
        'Step 2 - Add to Changes tables
        'ADD TO CARGO MANIFEST CHANGES
        dsCARGO_MANIFEST_CHANGES.AddNew
        dsCARGO_MANIFEST_CHANGES.FIELDS("LR_NUM").Value = txtLrNum.Text
        dsCARGO_MANIFEST_CHANGES.FIELDS("CHANGE_NUM").Value = lChangeNum
        dsCARGO_MANIFEST_CHANGES.FIELDS("ARRIVAL_NUM").Value = 1
        dsCARGO_MANIFEST_CHANGES.FIELDS("CONTAINER_NUM").Value = sContainerNum
        dsCARGO_MANIFEST_CHANGES.FIELDS("COMMODITY_CODE").Value = Trim$(txtChangeComm.Text)
        dsCARGO_MANIFEST_CHANGES.FIELDS("RECIPIENT_ID").Value = Trim$(txtChangeOwner.Text)
        dsCARGO_MANIFEST_CHANGES.FIELDS("CARGO_BOL").Value = Trim$(txtChangeBol.Text)
        dsCARGO_MANIFEST_CHANGES.FIELDS("CARGO_MARK").Value = Trim$(txtChangeMark.Text)
        dsCARGO_MANIFEST_CHANGES.FIELDS("EXPORTER_ID").Value = Trim$(txtChangeES.Text)
        dsCARGO_MANIFEST_CHANGES.FIELDS("QTY_EXPECTED").Value = Val(txtchangeqty1.Text)
    
        If Trim$(txtchangeweight.Text) <> "" Then
            dsCARGO_MANIFEST_CHANGES.FIELDS("CARGO_WEIGHT").Value = Val(txtchangeweight.Text)
        End If
        If cbochangeweightunit.ListIndex >= 0 Then
            dsCARGO_MANIFEST_CHANGES.FIELDS("CARGO_WEIGHT_UNIT").Value = cbochangeweightunit.Text
        End If
        'dsCARGO_MANIFEST_CHANGES.Fields("COMMODITY_CODE").Value = Val(txtCommodityCode.Text)
        
        If cbochangecargolocation.ListIndex >= 0 Then
            dsCARGO_MANIFEST_CHANGES.FIELDS("CARGO_LOCATION").Value = cbochangecargolocation.Text
        End If
        
        If optchangetype(0).Value = True Then
            dsCARGO_MANIFEST_CHANGES.FIELDS("IMPEX").Value = "I"
        ElseIf optchangetype(1).Value = True Then
            dsCARGO_MANIFEST_CHANGES.FIELDS("IMPEX").Value = "E"
        Else
            dsCARGO_MANIFEST_CHANGES.FIELDS("IMPEX").Value = "B"
        End If
        
        If cbochangemanstatus.ListIndex >= 0 Then
            dsCARGO_MANIFEST_CHANGES.FIELDS("MANIFEST_STATUS").Value = cbochangemanstatus.Text
        End If
        
        If Trim$(txtchangeqty2.Text) <> "" Then
            dsCARGO_MANIFEST_CHANGES.FIELDS("QTY2_EXPECTED").Value = Val(txtchangeqty2.Text)
        End If
        
        If Trim$(cbochangeunit1.Text) <> "" Then
            dsCARGO_MANIFEST_CHANGES.FIELDS("QTY1_UNIT").Value = cbochangeunit1.Text
        End If
        
        If Trim$(cbochangeunit2.Text) <> "" Then
            dsCARGO_MANIFEST_CHANGES.FIELDS("QTY2_UNIT").Value = cbochangeunit2.Text
        End If
        
        dsCARGO_MANIFEST_CHANGES.FIELDS("DATE_OF_CHANGE").Value = CStr(Format$(Now, "MM/DD/YYYY"))
        dsCARGO_MANIFEST_CHANGES.FIELDS("INITIALS").Value = Trim$(txtchangeinitials.Text)
        dsCARGO_MANIFEST_CHANGES.FIELDS("REASON").Value = Trim$(txtchangerem.Text)
        dsCARGO_MANIFEST_CHANGES.Update
    
        
        If chkReceiveCargo.Value = 1 And chkChangeReceiveCargo.Value = 1 Then
            'ADD TO CARGO TRACKING CHANGES
            dsCARGO_TRACKING_CHANGES.AddNew
            dsCARGO_TRACKING_CHANGES.FIELDS("LOT_NUM").Value = dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value
            dsCARGO_TRACKING_CHANGES.FIELDS("CHANGE_NUM").Value = lChangeNum2
            dsCARGO_TRACKING_CHANGES.FIELDS("QTY_IN_HOUSE").Value = Val(Trim$(txtchangeqty1.Text))
            dsCARGO_TRACKING_CHANGES.FIELDS("COMMODITY_CODE").Value = txtChangeComm.Text
            dsCARGO_TRACKING_CHANGES.FIELDS("DATE_RECEIVED").Value = CStr(Format$(Now, "MM/DD/YYYY"))
            dsCARGO_TRACKING_CHANGES.FIELDS("OWNER_ID").Value = Trim$(txtChangeOwner.Text)
            dsCARGO_TRACKING_CHANGES.FIELDS("STORAGE_CUST_ID").Value = Trim$(txtChangeOwner.Text)
            dsCARGO_TRACKING_CHANGES.FIELDS("QTY_RECEIVED").Value = Val(Trim$(txtchangeqty1.Text))
            dsCARGO_TRACKING_CHANGES.FIELDS("RECEIVER_ID").Value = 4 'Super User
            dsCARGO_TRACKING_CHANGES.FIELDS("WHSE_RCPT_NUM").Value = dsCARGO_TRACKING.FIELDS("WHSE_RCPT_NUM").Value
            
            If cbochangeunit1.ListIndex >= 0 Then
                dsCARGO_TRACKING_CHANGES.FIELDS("QTY_UNIT").Value = cbochangeunit1.Text
            End If
            
            If cbochangecargolocation.ListIndex >= 0 Then
                dsCARGO_TRACKING_CHANGES.FIELDS("WAREHOUSE_LOCATION").Value = cbochangecargolocation.Text
            End If
            
            dsCARGO_TRACKING_CHANGES.FIELDS("DATE_OF_CHANGE").Value = CStr(Format$(Now, "MM/DD/YYYY"))
            dsCARGO_TRACKING_CHANGES.FIELDS("INITIALS").Value = Trim$(txtchangeinitials.Text)
            dsCARGO_TRACKING_CHANGES.FIELDS("REASON").Value = Trim$(txtchangerem.Text)
            dsCARGO_TRACKING_CHANGES.Update
        End If
        
        'Step 3 - Update Cargo Manifest & Cargo Tracking
        'update the manifest table
        dsCARGO_MANIFEST.Edit
        dsCARGO_MANIFEST.FIELDS("RECIPIENT_ID").Value = Val(Trim$(txtChangeOwner.Text))
        dsCARGO_MANIFEST.FIELDS("EXPORTER_ID").Value = Val(Trim$(txtChangeES.Text))
        dsCARGO_MANIFEST.FIELDS("CARGO_BOL").Value = Trim$(txtChangeBol.Text)
        dsCARGO_MANIFEST.FIELDS("COMMODITY_CODE").Value = Trim$(txtChangeComm.Text)
        dsCARGO_MANIFEST.FIELDS("CARGO_MARK").Value = txtChangeMark.Text
        dsCARGO_MANIFEST.FIELDS("QTY_EXPECTED").Value = dsCARGO_MANIFEST.FIELDS("QTY_EXPECTED").Value + Val(txtchangeqty1.Text)
        
        If Trim$(txtchangeqty2.Text) <> "" Then
          If Not IsNull(dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED")) Then
            dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED").Value = dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED").Value + Val(txtchangeqty2.Text)
          Else
            dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED").Value = Val(txtchangeqty2.Text)
          End If
          
        End If
        
        If Trim$(txtchangeweight.Text) <> "" Then
            dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT").Value = dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT").Value + Val(txtchangeweight.Text)
        End If
        
        If Trim$(cbochangeunit1.Text) <> "" Then
            dsCARGO_MANIFEST.FIELDS("QTY1_UNIT").Value = cbochangeunit1.Text
            
        End If
        
        If Trim$(cbochangeunit2.Text) <> "" Then
            dsCARGO_MANIFEST.FIELDS("QTY2_UNIT").Value = cbochangeunit2.Text
            
        End If
        
        If Trim$(cbochangeweightunit.Text) <> "" Then
            dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT_UNIT").Value = cbochangeweightunit.Text
        End If
        
        If Trim$(cbochangecargolocation.Text) <> "" Then
            dsCARGO_MANIFEST.FIELDS("CARGO_LOCATION").Value = cbochangecargolocation.Text
            
        End If
        
        If optchangetype(0).Value = True Then
            dsCARGO_MANIFEST.FIELDS("IMPEX").Value = "I"
        ElseIf optchangetype(1).Value = True Then
            dsCARGO_MANIFEST.FIELDS("IMPEX").Value = "E"
        Else
            dsCARGO_MANIFEST.FIELDS("IMPEX").Value = "B"
        End If
        
        dsCARGO_MANIFEST.FIELDS("MANIFEST_STATUS").Value = cbochangemanstatus
        dsCARGO_MANIFEST.Update
     
        gsSqlStmt = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE LOT_NUM = '" & dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value & "' AND SERVICE_CODE = '6120'"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        'UPDATE CARGO_TRACKING
        If chkReceiveCargo.Value = 1 And chkChangeReceiveCargo.Value = 1 Then
            dsCARGO_TRACKING.Edit
            If dsSHORT_TERM_DATA.FIELDS("THE_COUNT").Value = 0 Then
                dsCARGO_TRACKING.FIELDS("STORAGE_CUST_ID").Value = Val(Trim$(txtChangeOwner.Text))
            End If
            dsCARGO_TRACKING.FIELDS("OWNER_ID").Value = Val(Trim$(txtChangeOwner.Text))
            dsCARGO_TRACKING.FIELDS("COMMODITY_CODE").Value = Trim$(txtChangeComm.Text)
            dsCARGO_TRACKING.FIELDS("DATE_RECEIVED").Value = Trim$(txtChangeDateReceived.Text)
            dsCARGO_TRACKING.FIELDS("QTY_IN_HOUSE").Value = Val(dsCARGO_TRACKING.FIELDS("QTY_IN_HOUSE").Value) + Val(Trim$(txtchangeqty1.Text))
            dsCARGO_TRACKING.FIELDS("QTY_RECEIVED").Value = Val(dsCARGO_TRACKING.FIELDS("QTY_RECEIVED").Value) + Val(Trim$(txtchangeqty1.Text))
            If Not IsNull(dsCARGO_TRACKING.FIELDS("QTY_IN_STORAGE")) Then
                dsCARGO_TRACKING.FIELDS("QTY_IN_STORAGE").Value = Val(dsCARGO_TRACKING.FIELDS("QTY_IN_STORAGE").Value) + Val(Trim$(txtchangeqty1.Text))
            End If
            
            If Trim$(cbochangeunit1.Text) <> "" Then
                dsCARGO_TRACKING.FIELDS("QTY_UNIT").Value = cbochangeunit1.Text
            End If
            
            If Trim$(cbochangecargolocation.Text) <> "" Then
                dsCARGO_TRACKING.FIELDS("WAREHOUSE_LOCATION").Value = cbochangecargolocation.Text
            End If
            dsCARGO_TRACKING.Update
        End If
        
    End If
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        lblStatus.Caption = "Save Successful."
        SaveSuccess = True
    Else
        OraSession.RollBack
        lblStatus.Caption = "Save Failed."
        MsgBox "Error occured while saving.", vbExclamation, "Save"
    End If
End Function

Public Sub ClearScreen(asScope As String)
    If asScope = "FULL" Then
        txtLrNum.Text = ""
        txtVesselName.Text = ""
        txtRecipientId.Text = ""
        txtCustomerName.Text = ""
        txtCommodityCode.Text = ""
        txtCommodityName.Text = ""
        txtBOL.Text = ""
        txtMark.Text = ""
        txtchangerem.Text = ""
        txtchangeinitials.Text = ""
        
    End If
    txtQty1.Text = ""
    txtunit1.Text = ""
    txtQty2.Text = ""
    txtunit2.Text = ""
    txtweightunit.Text = ""
    txtWeight.Text = ""
    txtmanifeststatus.Text = ""
    txtDateReceived.Text = ""
    txtcargolocation.Text = ""
    optImpex(0).Value = True
    txtchangeqty1.Text = ""
    txtchangeqty2.Text = ""
    txtchangeweight.Text = ""
    txtChangeComm.Text = ""
    txtChangeES.Text = ""
    txtChangeESName.Text = ""
    cbochangeunit1.ListIndex = -1
    cbochangeunit2.ListIndex = -1
    cbochangeweightunit.ListIndex = -1
    cbochangemanstatus.ListIndex = -1
    cbochangecargolocation.ListIndex = -1
    cbochangeunit1.Text = ""
    cbochangeunit2.Text = ""
    cbochangeweightunit.Text = ""
    cbochangemanstatus.Text = ""
    cbochangecargolocation.Text = ""
    chkChangeReceiveCargo.Value = 0
    optchangetype(0).Value = True
    txtLrNum.SetFocus
    lstbol.Clear
    lstmark.Clear
    chkReceiveCargo.Value = 0
    txtChangeOwner.Text = ""
    txtChangeBol.Text = ""
    txtChangeMark.Text = ""
    txtQtyInHouse.Text = ""
    txtChangeDateReceived.Text = ""
    
End Sub

Private Sub fillChangeFields()
    
    txtchangeqty1.Text = "0"
    txtchangeqty2.Text = "0"
    txtchangeweight.Text = "0"
    cbochangeunit1.Text = txtunit1.Text
    cbochangeunit2.Text = txtunit2.Text
    cbochangeweightunit.Text = txtweightunit.Text
    txtChangeOwner.Text = txtRecipientId.Text
    txtChangeES.Text = txtSupplierCode.Text
    txtChangeESName.Text = txtSupplierName.Text
    txtChangeComm.Text = txtCommodityCode.Text
    txtChangeBol.Text = txtBOL.Text
    txtChangeMark.Text = txtMark.Text
    cbochangemanstatus.Text = txtmanifeststatus.Text
    cbochangecargolocation.Text = txtcargolocation.Text
    txtChangeDateReceived.Text = txtDateReceived.Text
    If Trim$(txtDateReceived.Text) <> "" Then
        txtChangeDateReceived.Text = txtDateReceived.Text
    Else
        txtChangeDateReceived.Text = CStr(Format$(Now, "MM/DD/YYYY"))
    End If
    
End Sub

Private Sub printChanges()
    Dim iType As String
    Dim iChangeType As String
    Dim iReceived As String
    Dim iChangeReceived As String
    iChangeReceived = ""
    iReceived = ""
    iType = ""
    iChangeType = ""
    iReceived = ""
    
    If Trim(txtChangeOwner.Text) <> "" Then
          gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(txtChangeOwner.Text))
          Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    End If
    
    If Trim(txtChangeComm.Text) <> "" Then
          gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Val(Trim$(txtChangeComm.Text))
          Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    End If
    
    If optImpex(0) Then
        iType = "Import"
    ElseIf optImpex(1) Then
        iType = "Export"
    ElseIf optImpex(2) Then
        iType = "Both"
    End If
    
    If optchangetype(0) Then
        iChangeType = "Import"
    ElseIf optchangetype(1) Then
        iChangeType = "Export"
    ElseIf optchangetype(2) Then
        iChangeType = "Both"
    End If
    
    If chkReceiveCargo Then
        iReceived = "In Storage"
    End If
    
    If chkChangeReceiveCargo Then
        iChangeReceived = "In Storage"
    End If
    
    
    
    Printer.Orientation = 1
    Printer.FontSize = 12
    Printer.Print Tab(5); " "
    Printer.Print Tab(35); "CARGO MANIFEST CHANGES REPORT"
    Printer.FontSize = 10
    Printer.Print Tab(5); " "
    Printer.Print Tab(5); " "
    Printer.Print Tab(5); "Ship: " & txtVesselName; Tab(90); "Activity Date: " & Date
    Printer.Print Tab(5); "Fields"; Tab(40); " Original"; Tab(90); " Changes"
    Printer.Print Tab(5); "------------------------------------------------------------------------------------------------------------------------------------------------"
    Printer.Print Tab(5); "Owner:"; Tab(30); txtCustomerName.Text; Tab(85); Trim$(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value)
    Printer.Print Tab(5); "Commodity:"; Tab(30); txtCommodityName.Text; Tab(85); Trim$(dsCOMMODITY_PROFILE.FIELDS("COMMODITY_NAME").Value)
    Printer.Print Tab(5); "BOL:"; Tab(30); txtBOL.Text; Tab(85); txtChangeBol.Text
    Printer.Print Tab(5); "Mark:"; Tab(30); txtMark.Text; Tab(85); txtChangeMark.Text
    Printer.Print Tab(5); "Qty1:"; Tab(30); txtQty1.Text; Tab(85); txtchangeqty1.Text
    Printer.Print Tab(5); "Unit1:"; Tab(30); txtunit1.Text; Tab(85); cbochangeunit1.Text
    Printer.Print Tab(5); "Qty2:"; Tab(30); txtQty2.Text; Tab(85); txtchangeqty2.Text
    Printer.Print Tab(5); "Unit2:"; Tab(30); txtunit2.Text; Tab(85); cbochangeunit2.Text
    Printer.Print Tab(5); "Weight:"; Tab(30); txtWeight.Text; Tab(85); txtchangeweight.Text
    Printer.Print Tab(5); "Weight Unit:"; Tab(30); txtweightunit.Text; Tab(85); cbochangeweightunit.Text
    Printer.Print Tab(5); "Shipping Type:"; Tab(30); iType; Tab(85); iChangeType
    Printer.Print Tab(5); "Manifest Status:"; Tab(30); txtmanifeststatus.Text; Tab(85); cbochangemanstatus.Text
    Printer.Print Tab(5); "Cargo Location:"; Tab(30); txtcargolocation.Text; Tab(85); cbochangecargolocation.Text
    Printer.Print Tab(5); "Date Received:"; Tab(30); txtDateReceived.Text; Tab(85); txtChangeDateReceived.Text
    Printer.Print Tab(5); "------------------------------------------------------------------------------------------------------------------------------------------------"
    Printer.Print Tab(5); "Remarks: " & txtchangerem.Text
    Printer.Print Tab(5); "Initial: " & txtchangeinitials.Text
    Printer.EndDoc
    
End Sub


Private Function FindFreeTime(commodity As String, cus_id As String, lr_num As String) As Integer

On Error GoTo Err_Handler

    Dim rs As Object
    Dim strSql As String
    Dim retVal As Integer



        '' Prepare sql statement- 1st check
        strSql = "select f.FREE_DAYS" & _
                    " from free_time f" & _
                    " where f.COMMODITY_CODE =" & commodity & _
                    " and f.CUSTOMER_ID=" & cus_id & _
                    " and f.LR_NUM=" & lr_num
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)

        If rs.recordcount = 1 Then
            retVal = Int(Val(rs.FIELDS(0).Value))
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If

        '' Prepare sql statement- 2nd check
        strSql = "select f.FREE_DAYS" & _
            " from free_time f" & _
            " where f.COMMODITY_CODE =" & commodity & _
            " and f.CUSTOMER_ID=" & cus_id & _
            " and f.LR_NUM IS NULL"
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        If rs.recordcount = 1 Then
            retVal = Int(Val(rs.FIELDS(0).Value))
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If


        '' Prepare sql statement- 3rd check
        strSql = "select f.FREE_DAYS" & _
                    " from free_time f" & _
                    " where f.COMMODITY_CODE =" & commodity & _
                    " and f.CUSTOMER_ID IS NULL" & _
                    " and f.LR_NUM IS NULL"

        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        If rs.recordcount = 1 Then
            retVal = Int(Val(rs.FIELDS(0).Value))
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If


        '' Set variables to Nothing-Nothing found after 3 checks
        Set rs = Nothing
        retVal = -1
        FindFreeTime = retVal
        Exit Function


Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        Me.Name & "." & "FindFreeTime"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        retVal = -1
        FindFreeTime = retVal
    End If

End Function


