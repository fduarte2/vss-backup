VERSION 5.00
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmVeslBill 
   BackColor       =   &H00FFFFC0&
   Caption         =   "Vessel Billing"
   ClientHeight    =   12210
   ClientLeft      =   225
   ClientTop       =   480
   ClientWidth     =   14880
   LinkTopic       =   "Form1"
   ScaleHeight     =   12210
   ScaleWidth      =   14880
   Begin VB.TextBox txtQty 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   8100
      TabIndex        =   197
      Top             =   2070
      Width           =   675
   End
   Begin VB.TextBox txtQty 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   8100
      TabIndex        =   196
      Top             =   2400
      Width           =   675
   End
   Begin VB.TextBox txtServiceCode 
      Height          =   315
      Index           =   3
      Left            =   120
      MaxLength       =   10
      TabIndex        =   195
      Top             =   2400
      Width           =   825
   End
   Begin VB.TextBox txtServiceCode 
      Height          =   315
      Index           =   2
      Left            =   120
      MaxLength       =   10
      TabIndex        =   194
      Top             =   2070
      Width           =   825
   End
   Begin VB.TextBox txtDescription 
      Height          =   315
      Index           =   3
      Left            =   4050
      MaxLength       =   100
      TabIndex        =   193
      Top             =   2400
      Width           =   1635
   End
   Begin VB.TextBox txtDescription 
      Height          =   315
      Index           =   2
      Left            =   4050
      MaxLength       =   100
      TabIndex        =   192
      Top             =   2070
      Width           =   1635
   End
   Begin VB.TextBox txtCustomerName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   13080
      TabIndex        =   191
      Top             =   2070
      Width           =   1725
   End
   Begin VB.TextBox txtCustomerName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   13080
      TabIndex        =   190
      Top             =   2400
      Width           =   1725
   End
   Begin VB.TextBox txtCustomerId 
      Height          =   315
      Index           =   3
      Left            =   11970
      MaxLength       =   6
      TabIndex        =   189
      Top             =   2400
      Width           =   705
   End
   Begin VB.CommandButton cmdCustomerList 
      Height          =   315
      Index           =   3
      Left            =   12690
      Style           =   1  'Graphical
      TabIndex        =   188
      TabStop         =   0   'False
      Top             =   2400
      Width           =   345
   End
   Begin VB.TextBox txtCustomerId 
      Height          =   315
      Index           =   2
      Left            =   11970
      MaxLength       =   6
      TabIndex        =   187
      Top             =   2070
      Width           =   705
   End
   Begin VB.CommandButton cmdCustomerList 
      Height          =   315
      Index           =   2
      Left            =   12690
      Style           =   1  'Graphical
      TabIndex        =   186
      TabStop         =   0   'False
      Top             =   2070
      Width           =   345
   End
   Begin VB.TextBox txtServiceName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   1350
      TabIndex        =   185
      Top             =   2070
      Width           =   2655
   End
   Begin VB.TextBox txtServiceName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   1350
      TabIndex        =   184
      Top             =   2400
      Width           =   2655
   End
   Begin VB.CommandButton cmdServiceList 
      Height          =   315
      Index           =   3
      Left            =   960
      Style           =   1  'Graphical
      TabIndex        =   183
      TabStop         =   0   'False
      Top             =   2400
      Width           =   345
   End
   Begin VB.CommandButton cmdServiceList 
      Height          =   315
      Index           =   2
      Left            =   960
      Style           =   1  'Graphical
      TabIndex        =   182
      TabStop         =   0   'False
      Top             =   2070
      Width           =   345
   End
   Begin VB.TextBox txtServiceAmount 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   2
      Left            =   10500
      TabIndex        =   181
      Top             =   2070
      Width           =   1425
   End
   Begin VB.TextBox txtServiceAmount 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   3
      Left            =   10500
      TabIndex        =   180
      Top             =   2400
      Width           =   1425
   End
   Begin VB.TextBox txtServiceUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   7230
      TabIndex        =   179
      Top             =   2070
      Width           =   825
   End
   Begin VB.TextBox txtServiceUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   7230
      TabIndex        =   178
      Top             =   2400
      Width           =   825
   End
   Begin VB.CheckBox chkCareOf 
      BackColor       =   &H00FFFFC0&
      Caption         =   "C/O?"
      Height          =   285
      Index           =   2
      Left            =   5730
      TabIndex        =   177
      Top             =   2100
      Value           =   1  'Checked
      Width           =   735
   End
   Begin VB.TextBox txtMinimumCharge 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   3
      Left            =   9510
      TabIndex        =   176
      Top             =   2400
      Width           =   945
   End
   Begin VB.TextBox txtMinimumCharge 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   2
      Left            =   9510
      TabIndex        =   175
      Top             =   2070
      Width           =   945
   End
   Begin VB.TextBox txtServiceRate 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   8820
      TabIndex        =   174
      Top             =   2400
      Width           =   645
   End
   Begin VB.TextBox txtDays 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   3
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   173
      Top             =   2400
      Width           =   675
   End
   Begin VB.TextBox txtServiceRate 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   8820
      TabIndex        =   172
      Top             =   2070
      Width           =   645
   End
   Begin VB.TextBox txtDays 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   2
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   171
      Top             =   2070
      Width           =   675
   End
   Begin VB.CheckBox chkCareOf 
      BackColor       =   &H00FFFFC0&
      Caption         =   "C/O?"
      Height          =   285
      Index           =   3
      Left            =   5730
      TabIndex        =   170
      Top             =   2430
      Value           =   1  'Checked
      Width           =   735
   End
   Begin VB.CommandButton cmdClearVessel 
      Caption         =   "Clear Single-Line Vessel Charge"
      Height          =   375
      Left            =   9840
      TabIndex        =   169
      Top             =   8280
      Width           =   2655
   End
   Begin VB.CommandButton cmdClrWharfage 
      Caption         =   "Clear Security Grid (Below)"
      Height          =   375
      Left            =   7440
      TabIndex        =   168
      Top             =   8280
      Width           =   2175
   End
   Begin VB.CommandButton cmdPopulateSec 
      Caption         =   "Populate Security Charge"
      Height          =   375
      Left            =   4680
      TabIndex        =   167
      Top             =   8280
      Width           =   2175
   End
   Begin VB.TextBox txtSecurityService 
      Height          =   285
      Left            =   6360
      TabIndex        =   166
      Top             =   8760
      Visible         =   0   'False
      Width           =   615
   End
   Begin VB.TextBox txtSecurityCustName 
      BackColor       =   &H00FFFFC0&
      Height          =   315
      Left            =   10530
      TabIndex        =   165
      Top             =   8760
      Width           =   1725
   End
   Begin VB.TextBox txtSecurityCust 
      BackColor       =   &H00FFFFC0&
      Height          =   315
      Left            =   9420
      TabIndex        =   164
      Top             =   8760
      Width           =   705
   End
   Begin VB.TextBox txtSecurityDockBill 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   7950
      TabIndex        =   163
      Top             =   8760
      Width           =   1425
   End
   Begin VB.TextBox txtSecurityDesc 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   4200
      TabIndex        =   161
      Top             =   8760
      Width           =   1935
   End
   Begin VB.ComboBox cmbSecurityType 
      Height          =   315
      ItemData        =   "VeslBill.frx":0000
      Left            =   1080
      List            =   "VeslBill.frx":0002
      TabIndex        =   157
      Top             =   8280
      Width           =   3015
   End
   Begin Crystal.CrystalReport crw2 
      Left            =   840
      Top             =   11400
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin VB.CommandButton cmdPBRep 
      Caption         =   "PreBill Report"
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
      Left            =   8393
      TabIndex        =   152
      Top             =   11400
      Visible         =   0   'False
      Width           =   1455
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "Exit"
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
      Left            =   6713
      TabIndex        =   151
      Top             =   11400
      Width           =   1455
   End
   Begin VB.TextBox txtPage 
      Height          =   315
      Index           =   5
      Left            =   6000
      TabIndex        =   150
      Top             =   4950
      Width           =   375
   End
   Begin VB.TextBox txtPage 
      Height          =   315
      Index           =   4
      Left            =   6000
      TabIndex        =   149
      Top             =   4620
      Width           =   375
   End
   Begin VB.TextBox txtPage 
      Height          =   315
      Index           =   3
      Left            =   6000
      TabIndex        =   148
      Top             =   4290
      Width           =   375
   End
   Begin VB.TextBox txtPage 
      Height          =   315
      Index           =   2
      Left            =   6000
      TabIndex        =   147
      Top             =   3960
      Width           =   375
   End
   Begin VB.TextBox txtPage 
      Height          =   315
      Index           =   1
      Left            =   6000
      TabIndex        =   146
      Top             =   3630
      Width           =   375
   End
   Begin VB.TextBox txtPage 
      Height          =   315
      Index           =   0
      Left            =   6000
      TabIndex        =   145
      Top             =   3300
      Width           =   375
   End
   Begin SSDataWidgets_B.SSDBGrid grdWharfage 
      Height          =   2175
      Left            =   120
      TabIndex        =   142
      Top             =   6000
      Width           =   14655
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   10
      AllowAddNew     =   -1  'True
      ForeColorEven   =   0
      ForeColorOdd    =   12582912
      RowHeight       =   450
      ExtraHeight     =   26
      Columns.Count   =   10
      Columns(0).Width=   1402
      Columns(0).Caption=   "SERVICE"
      Columns(0).Name =   "SERVICE"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   4022
      Columns(1).Caption=   "DESCRIPTION"
      Columns(1).Name =   "DESCRIPTION"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   820
      Columns(2).Caption=   "C/O"
      Columns(2).Name =   "C/O"
      Columns(2).Alignment=   2
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(2).Style=   2
      Columns(3).Width=   1879
      Columns(3).Caption=   "COMMODITY"
      Columns(3).Name =   "COMMODITY"
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1931
      Columns(4).Caption=   "QTY"
      Columns(4).Name =   "QTY"
      Columns(4).Alignment=   1
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1773
      Columns(5).Caption=   "RATE"
      Columns(5).Name =   "RATE"
      Columns(5).Alignment=   1
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   2328
      Columns(6).Caption=   "AMT"
      Columns(6).Name =   "AMT"
      Columns(6).Alignment=   1
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1905
      Columns(7).Caption=   "CUSTOMER"
      Columns(7).Name =   "CUSTOMER"
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   8652
      Columns(8).Caption=   "CUSTOMER NAME"
      Columns(8).Name =   "CUSTOMER NAME"
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   3200
      Columns(9).Visible=   0   'False
      Columns(9).Caption=   "BILLING NUM"
      Columns(9).Name =   "BillingNum"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      _ExtentX        =   25850
      _ExtentY        =   3836
      _StockProps     =   79
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
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
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
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
      Left            =   5033
      TabIndex        =   159
      Top             =   11400
      Width           =   1455
   End
   Begin VB.CommandButton cmdChangeDates 
      Caption         =   "&Change Dates"
      Height          =   315
      Left            =   13470
      TabIndex        =   10
      Top             =   90
      Width           =   1335
   End
   Begin VB.TextBox txtServiceQty2Lines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   9120
      TabIndex        =   79
      Top             =   3630
      Width           =   615
   End
   Begin VB.TextBox txtServiceCodeLines 
      Height          =   315
      Index           =   1
      Left            =   90
      MaxLength       =   10
      TabIndex        =   71
      Top             =   3630
      Width           =   825
   End
   Begin VB.TextBox txtDescriptionLines 
      Height          =   315
      Index           =   1
      Left            =   4020
      MaxLength       =   100
      TabIndex        =   74
      Top             =   3630
      Width           =   1635
   End
   Begin VB.TextBox txtCustomerIdLines 
      Height          =   315
      Index           =   1
      Left            =   11940
      MaxLength       =   6
      TabIndex        =   82
      Top             =   3630
      Width           =   705
   End
   Begin VB.CommandButton cmdCustomerListLines 
      Height          =   315
      Index           =   1
      Left            =   12660
      Style           =   1  'Graphical
      TabIndex        =   83
      TabStop         =   0   'False
      Top             =   3630
      Width           =   345
   End
   Begin VB.TextBox txtServiceNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   1320
      TabIndex        =   73
      Top             =   3630
      Width           =   2655
   End
   Begin VB.CommandButton cmdServiceListLines 
      Height          =   315
      Index           =   1
      Left            =   930
      Style           =   1  'Graphical
      TabIndex        =   72
      TabStop         =   0   'False
      Top             =   3630
      Width           =   345
   End
   Begin VB.TextBox txtServiceAmountLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   1
      Left            =   10470
      TabIndex        =   81
      Top             =   3630
      Width           =   1425
   End
   Begin VB.TextBox txtServiceRateLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   9780
      TabIndex        =   80
      Top             =   3630
      Width           =   645
   End
   Begin VB.TextBox txtServiceStartLines 
      Height          =   315
      Index           =   1
      Left            =   7440
      MaxLength       =   5
      TabIndex        =   77
      Top             =   3630
      Width           =   795
   End
   Begin VB.CheckBox chkCareOfLines 
      BackColor       =   &H00FFFFC0&
      Caption         =   "C/O?"
      Height          =   285
      Index           =   1
      Left            =   5700
      TabIndex        =   75
      Top             =   3660
      Value           =   1  'Checked
      Width           =   225
   End
   Begin VB.TextBox txtCustomerNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   13050
      TabIndex        =   84
      Top             =   3630
      Width           =   1725
   End
   Begin VB.TextBox txtServiceStopLines 
      Height          =   315
      Index           =   1
      Left            =   8280
      MaxLength       =   5
      TabIndex        =   78
      Top             =   3630
      Width           =   795
   End
   Begin VB.TextBox txtServiceDateLines 
      Height          =   315
      Index           =   1
      Left            =   6420
      MaxLength       =   10
      TabIndex        =   76
      Top             =   3630
      Width           =   975
   End
   Begin VB.TextBox txtServiceQty2Lines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   9120
      TabIndex        =   93
      Top             =   3960
      Width           =   615
   End
   Begin VB.TextBox txtServiceQty2Lines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   9120
      TabIndex        =   107
      Top             =   4290
      Width           =   615
   End
   Begin VB.TextBox txtServiceCodeLines 
      Height          =   315
      Index           =   3
      Left            =   90
      MaxLength       =   10
      TabIndex        =   99
      Top             =   4290
      Width           =   825
   End
   Begin VB.TextBox txtServiceCodeLines 
      Height          =   315
      Index           =   2
      Left            =   90
      MaxLength       =   10
      TabIndex        =   85
      Top             =   3960
      Width           =   825
   End
   Begin VB.TextBox txtDescriptionLines 
      Height          =   315
      Index           =   3
      Left            =   4020
      MaxLength       =   100
      TabIndex        =   102
      Top             =   4290
      Width           =   1635
   End
   Begin VB.TextBox txtDescriptionLines 
      Height          =   315
      Index           =   2
      Left            =   4020
      MaxLength       =   100
      TabIndex        =   88
      Top             =   3960
      Width           =   1635
   End
   Begin VB.TextBox txtCustomerIdLines 
      Height          =   315
      Index           =   3
      Left            =   11940
      MaxLength       =   6
      TabIndex        =   110
      Top             =   4290
      Width           =   705
   End
   Begin VB.CommandButton cmdCustomerListLines 
      Height          =   315
      Index           =   3
      Left            =   12660
      Style           =   1  'Graphical
      TabIndex        =   111
      TabStop         =   0   'False
      Top             =   4290
      Width           =   345
   End
   Begin VB.TextBox txtCustomerIdLines 
      Height          =   315
      Index           =   2
      Left            =   11940
      MaxLength       =   6
      TabIndex        =   96
      Top             =   3960
      Width           =   705
   End
   Begin VB.CommandButton cmdCustomerListLines 
      Height          =   315
      Index           =   2
      Left            =   12660
      Style           =   1  'Graphical
      TabIndex        =   97
      TabStop         =   0   'False
      Top             =   3960
      Width           =   345
   End
   Begin VB.TextBox txtServiceNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   1320
      TabIndex        =   87
      Top             =   3960
      Width           =   2655
   End
   Begin VB.TextBox txtServiceNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   1320
      TabIndex        =   101
      Top             =   4290
      Width           =   2655
   End
   Begin VB.CommandButton cmdServiceListLines 
      Height          =   315
      Index           =   3
      Left            =   930
      Style           =   1  'Graphical
      TabIndex        =   100
      TabStop         =   0   'False
      Top             =   4290
      Width           =   345
   End
   Begin VB.CommandButton cmdServiceListLines 
      Height          =   315
      Index           =   2
      Left            =   930
      Style           =   1  'Graphical
      TabIndex        =   86
      TabStop         =   0   'False
      Top             =   3960
      Width           =   345
   End
   Begin VB.TextBox txtServiceAmountLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   2
      Left            =   10470
      TabIndex        =   95
      Top             =   3960
      Width           =   1425
   End
   Begin VB.TextBox txtServiceAmountLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   3
      Left            =   10470
      TabIndex        =   109
      Top             =   4290
      Width           =   1425
   End
   Begin VB.CheckBox chkCareOfLines 
      BackColor       =   &H00FFFFC0&
      Caption         =   "C/O?"
      Height          =   285
      Index           =   2
      Left            =   5700
      TabIndex        =   89
      Top             =   3990
      Value           =   1  'Checked
      Width           =   225
   End
   Begin VB.TextBox txtServiceRateLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   9780
      TabIndex        =   108
      Top             =   4290
      Width           =   645
   End
   Begin VB.TextBox txtServiceStartLines 
      Height          =   315
      Index           =   3
      Left            =   7440
      MaxLength       =   5
      TabIndex        =   105
      Top             =   4290
      Width           =   795
   End
   Begin VB.TextBox txtServiceRateLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   9780
      TabIndex        =   94
      Top             =   3960
      Width           =   645
   End
   Begin VB.TextBox txtServiceStartLines 
      Height          =   315
      Index           =   2
      Left            =   7440
      MaxLength       =   5
      TabIndex        =   91
      Top             =   3960
      Width           =   795
   End
   Begin VB.CheckBox chkCareOfLines 
      BackColor       =   &H00FFFFC0&
      Caption         =   "C/O?"
      Height          =   285
      Index           =   3
      Left            =   5700
      TabIndex        =   103
      Top             =   4320
      Value           =   1  'Checked
      Width           =   225
   End
   Begin VB.TextBox txtCustomerNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   13050
      TabIndex        =   98
      Top             =   3960
      Width           =   1725
   End
   Begin VB.TextBox txtCustomerNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   13050
      TabIndex        =   112
      Top             =   4290
      Width           =   1725
   End
   Begin VB.TextBox txtServiceStopLines 
      Height          =   315
      Index           =   3
      Left            =   8280
      MaxLength       =   5
      TabIndex        =   106
      Top             =   4290
      Width           =   795
   End
   Begin VB.TextBox txtServiceStopLines 
      Height          =   315
      Index           =   2
      Left            =   8280
      MaxLength       =   5
      TabIndex        =   92
      Top             =   3960
      Width           =   795
   End
   Begin VB.TextBox txtServiceDateLines 
      Height          =   315
      Index           =   3
      Left            =   6420
      MaxLength       =   10
      TabIndex        =   104
      Top             =   4290
      Width           =   975
   End
   Begin VB.TextBox txtServiceDateLines 
      Height          =   315
      Index           =   2
      Left            =   6420
      MaxLength       =   10
      TabIndex        =   90
      Top             =   3960
      Width           =   975
   End
   Begin VB.TextBox txtServiceQty2Lines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   9120
      TabIndex        =   121
      Top             =   4620
      Width           =   615
   End
   Begin VB.TextBox txtServiceQty2Lines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   9120
      TabIndex        =   135
      Top             =   4950
      Width           =   615
   End
   Begin VB.TextBox txtServiceCodeLines 
      Height          =   315
      Index           =   5
      Left            =   90
      MaxLength       =   10
      TabIndex        =   127
      Top             =   4950
      Width           =   825
   End
   Begin VB.TextBox txtServiceCodeLines 
      Height          =   315
      Index           =   4
      Left            =   90
      MaxLength       =   10
      TabIndex        =   113
      Top             =   4620
      Width           =   825
   End
   Begin VB.TextBox txtDescriptionLines 
      Height          =   315
      Index           =   5
      Left            =   4020
      MaxLength       =   100
      TabIndex        =   130
      Top             =   4950
      Width           =   1635
   End
   Begin VB.TextBox txtDescriptionLines 
      Height          =   315
      Index           =   4
      Left            =   4020
      MaxLength       =   100
      TabIndex        =   116
      Top             =   4620
      Width           =   1635
   End
   Begin VB.TextBox txtCustomerIdLines 
      Height          =   315
      Index           =   5
      Left            =   11940
      MaxLength       =   6
      TabIndex        =   138
      Top             =   4950
      Width           =   705
   End
   Begin VB.CommandButton cmdCustomerListLines 
      Height          =   315
      Index           =   5
      Left            =   12660
      Style           =   1  'Graphical
      TabIndex        =   139
      TabStop         =   0   'False
      Top             =   4950
      Width           =   345
   End
   Begin VB.TextBox txtCustomerIdLines 
      Height          =   315
      Index           =   4
      Left            =   11940
      MaxLength       =   6
      TabIndex        =   124
      Top             =   4620
      Width           =   705
   End
   Begin VB.CommandButton cmdCustomerListLines 
      Height          =   315
      Index           =   4
      Left            =   12660
      Style           =   1  'Graphical
      TabIndex        =   125
      TabStop         =   0   'False
      Top             =   4620
      Width           =   345
   End
   Begin VB.TextBox txtServiceNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   1320
      TabIndex        =   115
      Top             =   4620
      Width           =   2655
   End
   Begin VB.TextBox txtServiceNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   1320
      TabIndex        =   129
      Top             =   4950
      Width           =   2655
   End
   Begin VB.CommandButton cmdServiceListLines 
      Height          =   315
      Index           =   5
      Left            =   930
      Style           =   1  'Graphical
      TabIndex        =   128
      TabStop         =   0   'False
      Top             =   4950
      Width           =   345
   End
   Begin VB.CommandButton cmdServiceListLines 
      Height          =   315
      Index           =   4
      Left            =   930
      Style           =   1  'Graphical
      TabIndex        =   114
      TabStop         =   0   'False
      Top             =   4620
      Width           =   345
   End
   Begin VB.TextBox txtServiceAmountLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   4
      Left            =   10470
      TabIndex        =   123
      Top             =   4620
      Width           =   1425
   End
   Begin VB.TextBox txtServiceAmountLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   5
      Left            =   10470
      TabIndex        =   137
      Top             =   4950
      Width           =   1425
   End
   Begin VB.CheckBox chkCareOfLines 
      BackColor       =   &H00FFFFC0&
      Caption         =   "C/O?"
      Height          =   285
      Index           =   4
      Left            =   5700
      TabIndex        =   117
      Top             =   4650
      Value           =   1  'Checked
      Width           =   225
   End
   Begin VB.TextBox txtServiceRateLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   9780
      TabIndex        =   136
      Top             =   4950
      Width           =   645
   End
   Begin VB.TextBox txtServiceStartLines 
      Height          =   315
      Index           =   5
      Left            =   7440
      MaxLength       =   5
      TabIndex        =   133
      Top             =   4950
      Width           =   795
   End
   Begin VB.TextBox txtServiceRateLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   9780
      TabIndex        =   122
      Top             =   4620
      Width           =   645
   End
   Begin VB.TextBox txtServiceStartLines 
      Height          =   315
      Index           =   4
      Left            =   7440
      MaxLength       =   5
      TabIndex        =   119
      Top             =   4620
      Width           =   795
   End
   Begin VB.CheckBox chkCareOfLines 
      BackColor       =   &H00FFFFC0&
      Caption         =   "C/O?"
      Height          =   285
      Index           =   5
      Left            =   5700
      TabIndex        =   131
      Top             =   4980
      Value           =   1  'Checked
      Width           =   225
   End
   Begin VB.TextBox txtCustomerNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   13050
      TabIndex        =   126
      Top             =   4620
      Width           =   1725
   End
   Begin VB.TextBox txtCustomerNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   13050
      TabIndex        =   140
      Top             =   4950
      Width           =   1725
   End
   Begin VB.TextBox txtServiceStopLines 
      Height          =   315
      Index           =   5
      Left            =   8280
      MaxLength       =   5
      TabIndex        =   134
      Top             =   4950
      Width           =   795
   End
   Begin VB.TextBox txtServiceStopLines 
      Height          =   315
      Index           =   4
      Left            =   8280
      MaxLength       =   5
      TabIndex        =   120
      Top             =   4620
      Width           =   795
   End
   Begin VB.TextBox txtServiceDateLines 
      Height          =   315
      Index           =   5
      Left            =   6420
      MaxLength       =   10
      TabIndex        =   132
      Top             =   4950
      Width           =   975
   End
   Begin VB.TextBox txtServiceDateLines 
      Height          =   315
      Index           =   4
      Left            =   6420
      MaxLength       =   10
      TabIndex        =   118
      Top             =   4620
      Width           =   975
   End
   Begin VB.TextBox txtLRNum 
      Height          =   315
      Left            =   1230
      MaxLength       =   7
      TabIndex        =   1
      Top             =   90
      Width           =   975
   End
   Begin VB.TextBox txtVesselName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   2610
      TabIndex        =   3
      Top             =   90
      Width           =   3435
   End
   Begin VB.CommandButton cmdVesselList 
      Height          =   315
      Left            =   2220
      Style           =   1  'Graphical
      TabIndex        =   2
      TabStop         =   0   'False
      Top             =   90
      Width           =   345
   End
   Begin VB.CheckBox chkCareOf 
      BackColor       =   &H00FFFFC0&
      Caption         =   "C/O?"
      Height          =   285
      Index           =   1
      Left            =   5700
      TabIndex        =   38
      Top             =   1200
      Value           =   1  'Checked
      Width           =   735
   End
   Begin VB.TextBox txtDays 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   0
      Left            =   6480
      MaxLength       =   7
      TabIndex        =   25
      Top             =   840
      Width           =   675
   End
   Begin VB.TextBox txtServiceDate 
      Height          =   315
      Left            =   6690
      MaxLength       =   10
      TabIndex        =   5
      Top             =   90
      Width           =   1155
   End
   Begin VB.TextBox txtServiceRate 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   8790
      TabIndex        =   28
      Top             =   840
      Width           =   645
   End
   Begin VB.TextBox txtDays 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   1
      Left            =   6480
      MaxLength       =   7
      TabIndex        =   39
      Top             =   1170
      Width           =   675
   End
   Begin VB.TextBox txtServiceRate 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   8790
      TabIndex        =   42
      Top             =   1170
      Width           =   645
   End
   Begin VB.TextBox txtMinimumCharge 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   0
      Left            =   9480
      TabIndex        =   29
      Top             =   840
      Width           =   945
   End
   Begin VB.TextBox txtMinimumCharge 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   1
      Left            =   9480
      TabIndex        =   43
      Top             =   1170
      Width           =   945
   End
   Begin VB.CheckBox chkCareOf 
      BackColor       =   &H00FFFFC0&
      Caption         =   "C/O?"
      Height          =   285
      Index           =   0
      Left            =   5700
      TabIndex        =   24
      Top             =   870
      Value           =   1  'Checked
      Width           =   735
   End
   Begin VB.TextBox txtServiceUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   7200
      TabIndex        =   40
      Top             =   1170
      Width           =   825
   End
   Begin VB.TextBox txtServiceUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   7200
      TabIndex        =   26
      Top             =   840
      Width           =   825
   End
   Begin VB.TextBox txtServiceAmount 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   1
      Left            =   10470
      TabIndex        =   44
      Top             =   1170
      Width           =   1425
   End
   Begin VB.TextBox txtServiceAmount 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   0
      Left            =   10470
      TabIndex        =   30
      Top             =   840
      Width           =   1425
   End
   Begin VB.CommandButton cmdServiceList 
      Height          =   315
      Index           =   0
      Left            =   930
      Style           =   1  'Graphical
      TabIndex        =   21
      TabStop         =   0   'False
      Top             =   840
      Width           =   345
   End
   Begin VB.CommandButton cmdServiceList 
      Height          =   315
      Index           =   1
      Left            =   930
      Style           =   1  'Graphical
      TabIndex        =   35
      TabStop         =   0   'False
      Top             =   1170
      Width           =   345
   End
   Begin VB.TextBox txtServiceName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   1320
      TabIndex        =   36
      Top             =   1170
      Width           =   2655
   End
   Begin VB.TextBox txtServiceName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   1320
      TabIndex        =   22
      Top             =   840
      Width           =   2655
   End
   Begin VB.CommandButton cmdCustomerList 
      Height          =   315
      Index           =   0
      Left            =   12660
      Style           =   1  'Graphical
      TabIndex        =   32
      TabStop         =   0   'False
      Top             =   840
      Width           =   345
   End
   Begin VB.TextBox txtCustomerId 
      Height          =   315
      Index           =   0
      Left            =   11940
      MaxLength       =   6
      TabIndex        =   31
      Top             =   840
      Width           =   705
   End
   Begin VB.CommandButton cmdCustomerList 
      Height          =   315
      Index           =   1
      Left            =   12660
      Style           =   1  'Graphical
      TabIndex        =   46
      TabStop         =   0   'False
      Top             =   1170
      Width           =   345
   End
   Begin VB.TextBox txtCustomerId 
      Height          =   315
      Index           =   1
      Left            =   11940
      MaxLength       =   6
      TabIndex        =   45
      Top             =   1170
      Width           =   705
   End
   Begin VB.TextBox txtCustomerName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   13050
      TabIndex        =   47
      Top             =   1170
      Width           =   1725
   End
   Begin VB.TextBox txtCustomerName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   13050
      TabIndex        =   33
      Top             =   840
      Width           =   1725
   End
   Begin VB.TextBox txtDescription 
      Height          =   315
      Index           =   0
      Left            =   4020
      MaxLength       =   100
      TabIndex        =   23
      Top             =   840
      Width           =   1635
   End
   Begin VB.TextBox txtDescription 
      Height          =   315
      Index           =   1
      Left            =   4020
      MaxLength       =   100
      TabIndex        =   37
      Top             =   1170
      Width           =   1635
   End
   Begin VB.TextBox txtServiceCode 
      Height          =   315
      Index           =   0
      Left            =   90
      MaxLength       =   10
      TabIndex        =   20
      Top             =   840
      Width           =   825
   End
   Begin VB.TextBox txtServiceCode 
      Height          =   315
      Index           =   1
      Left            =   90
      MaxLength       =   10
      TabIndex        =   34
      Top             =   1170
      Width           =   825
   End
   Begin VB.TextBox txtQty 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   8070
      TabIndex        =   41
      Top             =   1170
      Width           =   675
   End
   Begin VB.TextBox txtQty 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   8070
      TabIndex        =   27
      Top             =   840
      Width           =   675
   End
   Begin VB.TextBox txtDateArrived 
      BackColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   8970
      TabIndex        =   7
      Top             =   90
      Width           =   1635
   End
   Begin VB.TextBox txtDateDeparted 
      BackColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   11790
      TabIndex        =   9
      Top             =   90
      Width           =   1635
   End
   Begin VB.TextBox txtServiceQty2Lines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   9120
      TabIndex        =   65
      Top             =   3300
      Width           =   615
   End
   Begin VB.TextBox txtServiceCodeLines 
      Height          =   315
      Index           =   0
      Left            =   90
      MaxLength       =   10
      TabIndex        =   57
      Top             =   3300
      Width           =   825
   End
   Begin VB.TextBox txtDescriptionLines 
      Height          =   315
      Index           =   0
      Left            =   4020
      MaxLength       =   100
      TabIndex        =   60
      Top             =   3300
      Width           =   1635
   End
   Begin VB.TextBox txtCustomerIdLines 
      Height          =   315
      Index           =   0
      Left            =   11940
      MaxLength       =   6
      TabIndex        =   68
      Top             =   3300
      Width           =   705
   End
   Begin VB.CommandButton cmdCustomerListLines 
      Height          =   315
      Index           =   0
      Left            =   12660
      Style           =   1  'Graphical
      TabIndex        =   69
      TabStop         =   0   'False
      Top             =   3300
      Width           =   345
   End
   Begin VB.TextBox txtServiceNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   1320
      TabIndex        =   59
      Top             =   3300
      Width           =   2655
   End
   Begin VB.CommandButton cmdServiceListLines 
      Height          =   315
      Index           =   0
      Left            =   930
      Style           =   1  'Graphical
      TabIndex        =   58
      TabStop         =   0   'False
      Top             =   3300
      Width           =   345
   End
   Begin VB.TextBox txtServiceAmountLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   0
      Left            =   10470
      TabIndex        =   67
      Top             =   3300
      Width           =   1425
   End
   Begin VB.CheckBox chkCareOfLines 
      BackColor       =   &H00FFFFC0&
      Height          =   285
      Index           =   0
      Left            =   5700
      TabIndex        =   61
      Top             =   3330
      Value           =   1  'Checked
      Width           =   225
   End
   Begin VB.TextBox txtServiceRateLines 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   9780
      TabIndex        =   66
      Top             =   3300
      Width           =   645
   End
   Begin VB.TextBox txtServiceStartLines 
      Height          =   315
      Index           =   0
      Left            =   7440
      MaxLength       =   5
      TabIndex        =   63
      Top             =   3300
      Width           =   795
   End
   Begin VB.TextBox txtCustomerNameLines 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   13050
      TabIndex        =   70
      Top             =   3300
      Width           =   1725
   End
   Begin VB.TextBox txtServiceStopLines 
      Height          =   315
      Index           =   0
      Left            =   8280
      MaxLength       =   5
      TabIndex        =   64
      Top             =   3300
      Width           =   795
   End
   Begin VB.TextBox txtServiceDateLines 
      Height          =   315
      Index           =   0
      Left            =   6420
      MaxLength       =   10
      TabIndex        =   62
      Top             =   3300
      Width           =   975
   End
   Begin SSDataWidgets_B.SSDBGrid grdSecurity 
      Height          =   2175
      Left            =   120
      TabIndex        =   155
      Top             =   9120
      Width           =   14655
      _Version        =   196616
      DataMode        =   2
      AllowAddNew     =   -1  'True
      ForeColorEven   =   0
      ForeColorOdd    =   12582912
      RowHeight       =   609
      ExtraHeight     =   820
      Columns.Count   =   11
      Columns(0).Width=   1402
      Columns(0).Caption=   "SERVICE"
      Columns(0).Name =   "SERVICE"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(0).Locked=   -1  'True
      Columns(1).Width=   4022
      Columns(1).Caption=   "DESCRIPTION"
      Columns(1).Name =   "DESCRIPTION"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(1).Locked=   -1  'True
      Columns(2).Width=   820
      Columns(2).Caption=   "C/O"
      Columns(2).Name =   "C/O"
      Columns(2).Alignment=   2
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(2).Style=   2
      Columns(3).Width=   1879
      Columns(3).Caption=   "COMMODITY"
      Columns(3).Name =   "COMMODITY"
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1931
      Columns(4).Caption=   "QTY"
      Columns(4).Name =   "QTY"
      Columns(4).Alignment=   1
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1773
      Columns(5).Caption=   "RATE"
      Columns(5).Name =   "RATE"
      Columns(5).Alignment=   1
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   2328
      Columns(6).Caption=   "AMT"
      Columns(6).Name =   "AMT"
      Columns(6).Alignment=   1
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1905
      Columns(7).Caption=   "CUSTOMER"
      Columns(7).Name =   "CUSTOMER"
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   8652
      Columns(8).Caption=   "CUSTOMER NAME"
      Columns(8).Name =   "CUSTOMER NAME"
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(8).Locked=   -1  'True
      Columns(9).Width=   3200
      Columns(9).Visible=   0   'False
      Columns(9).Caption=   "BILLING NUM"
      Columns(9).Name =   "BillingNum"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   3200
      Columns(10).Visible=   0   'False
      Columns(10).Caption=   "SECURITY_FROM"
      Columns(10).Name=   "SECURITY_FROM"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      _ExtentX        =   25850
      _ExtentY        =   3836
      _StockProps     =   79
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
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
   Begin VB.Label Label15 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Qty"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   8100
      TabIndex        =   206
      Top             =   1800
      Width           =   675
   End
   Begin VB.Label Label14 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Description"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4050
      TabIndex        =   205
      Top             =   1800
      Width           =   1635
   End
   Begin VB.Label Label13 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Bill To Customer"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   11970
      TabIndex        =   204
      Top             =   1800
      Width           =   2835
   End
   Begin VB.Label Label12 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Total"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   10500
      TabIndex        =   203
      Top             =   1800
      Width           =   1425
   End
   Begin VB.Label Label11 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Unit"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   7230
      TabIndex        =   202
      Top             =   1800
      Width           =   825
   End
   Begin VB.Label Label8 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Min Charge"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   9510
      TabIndex        =   201
      Top             =   1800
      Width           =   945
   End
   Begin VB.Label Label6 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Rate"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   8820
      TabIndex        =   200
      Top             =   1800
      Width           =   645
   End
   Begin VB.Label Label5 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Idle Dockage (Service)"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   150
      TabIndex        =   199
      Top             =   1800
      Width           =   3855
   End
   Begin VB.Label Label4 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Days"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   6510
      TabIndex        =   198
      Top             =   1800
      Width           =   675
   End
   Begin VB.Shape Shape3 
      BorderColor     =   &H80000002&
      BorderWidth     =   2
      Height          =   1155
      Left            =   60
      Top             =   1680
      Width           =   14775
   End
   Begin VB.Label Label10 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Bill:"
      Height          =   255
      Index           =   2
      Left            =   7080
      TabIndex        =   162
      Top             =   8805
      Width           =   495
   End
   Begin VB.Label Label10 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Desc:"
      Height          =   255
      Index           =   1
      Left            =   3600
      TabIndex        =   160
      Top             =   8805
      Width           =   495
   End
   Begin VB.Label Label10 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Vessel Charge:"
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
      Index           =   0
      Left            =   2160
      TabIndex        =   158
      Top             =   8805
      Width           =   1335
   End
   Begin VB.Label Label9 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Security"
      Height          =   255
      Left            =   120
      TabIndex        =   156
      Top             =   8280
      Width           =   855
   End
   Begin VB.Label Label7 
      Caption         =   "Label7"
      Height          =   135
      Left            =   5640
      TabIndex        =   154
      Top             =   1440
      Width           =   15
   End
   Begin VB.Label Label3 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Wharfage"
      Height          =   255
      Left            =   120
      TabIndex        =   153
      Top             =   5640
      Width           =   1215
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Page#"
      Height          =   195
      Left            =   5940
      TabIndex        =   144
      Top             =   3045
      Width           =   480
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "C/O"
      Height          =   195
      Left            =   5640
      TabIndex        =   143
      Top             =   3045
      Width           =   300
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   285
      Left            =   0
      TabIndex        =   141
      Top             =   11880
      Width           =   14865
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
      Left            =   180
      TabIndex        =   0
      Top             =   120
      Width           =   975
   End
   Begin VB.Label lblServiceDate 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Date"
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
      Left            =   6210
      TabIndex        =   4
      Top             =   120
      Width           =   435
   End
   Begin VB.Label lblServiceQty2 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Days"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   6480
      TabIndex        =   13
      Top             =   570
      Width           =   675
   End
   Begin VB.Label lblService 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Dockage (Service)"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   120
      TabIndex        =   11
      Top             =   570
      Width           =   3855
   End
   Begin VB.Label lblServiceRate 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Rate"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   8790
      TabIndex        =   16
      Top             =   570
      Width           =   645
   End
   Begin VB.Label lblMinCharge 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Min Charge"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   9480
      TabIndex        =   17
      Top             =   570
      Width           =   945
   End
   Begin VB.Label lblServiceUnit 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Unit"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   7200
      TabIndex        =   14
      Top             =   570
      Width           =   825
   End
   Begin VB.Label lblServiceAmount 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Total"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   10470
      TabIndex        =   18
      Top             =   570
      Width           =   1425
   End
   Begin VB.Label lblCustomer 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Bill To Customer"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   11940
      TabIndex        =   19
      Top             =   570
      Width           =   2835
   End
   Begin VB.Shape Shape1 
      BackColor       =   &H00FFFFFF&
      BorderColor     =   &H80000002&
      BorderWidth     =   2
      Height          =   1155
      Left            =   60
      Top             =   510
      Width           =   14775
   End
   Begin VB.Label lblDescription 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Description"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4020
      TabIndex        =   12
      Top             =   570
      Width           =   1635
   End
   Begin VB.Label lblQty 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Qty"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   8070
      TabIndex        =   15
      Top             =   570
      Width           =   675
   End
   Begin VB.Label lblDateArrived 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Date Arrived"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   7950
      TabIndex        =   6
      Top             =   120
      Width           =   975
   End
   Begin VB.Label lblDateDeparted 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Date Departed"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   10680
      TabIndex        =   8
      Top             =   120
      Width           =   1065
   End
   Begin VB.Label lblHoursLines 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Hours"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   9120
      TabIndex        =   53
      Top             =   3030
      Width           =   615
   End
   Begin VB.Label lblDescriptionLines 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Description"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4020
      TabIndex        =   49
      Top             =   3030
      Width           =   1635
   End
   Begin VB.Shape Shape2 
      BackColor       =   &H00FFFFFF&
      BorderColor     =   &H80000002&
      BorderWidth     =   2
      Height          =   2505
      Left            =   60
      Top             =   2880
      Width           =   14775
   End
   Begin VB.Label lblCustomerLines 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Bill To Customer"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   11940
      TabIndex        =   56
      Top             =   3030
      Width           =   2835
   End
   Begin VB.Label lblServiceAmountLines 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Total"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   10470
      TabIndex        =   55
      Top             =   3030
      Width           =   1425
   End
   Begin VB.Label lblServiceRateLines 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Rate"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   9780
      TabIndex        =   54
      Top             =   3030
      Width           =   645
   End
   Begin VB.Label lblServiceLines 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Lines (Service)"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   120
      TabIndex        =   48
      Top             =   3030
      Width           =   3855
   End
   Begin VB.Label lblServiceStartLines 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Start Time"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   7440
      TabIndex        =   51
      Top             =   3030
      Width           =   795
   End
   Begin VB.Label lblServiceStopLines 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "End Time"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   8280
      TabIndex        =   52
      Top             =   3030
      Width           =   795
   End
   Begin VB.Label lblServiceDateLines 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Date"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   6420
      TabIndex        =   50
      Top             =   3030
      Width           =   975
   End
End
Attribute VB_Name = "frmVeslBill"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iPos As Integer
Dim iRec As Integer

'Private Sub chkSecurity_LostFocus()
'    Call UpdateSecurityCharge
'End Sub
'
'
'
'
'Private Sub txtSecurityPercent_LostFocus()
'    Call UpdateSecurityCharge
'End Sub
'Private Sub UpdateSecurityCharge()
'    If chkSecurity.Value = 0 Or Not IsNull(txtSecurityPercent.Value) Or (txtServiceCode(0).Value <> 1114 And txtServiceCode(1).Value <> 1114) Then
'        txtSecurityService.Value = ""
'        txtSecurityTotal.Value = ""
'        txtSecurityCust.Value = ""
'        txtSecurityCustName.Value = ""
'    Else ' they have valid entries
'        txtSecurityService.Value = "" 'whatever this is
'        If txtServiceCode(0).Value = 1114 Then
'            txtSecurityTotal.Value = txtServiceCode(0).Value * (txtSecurityPercent.Value / 100)
'            txtSecurityCust.Value = txtCustomerId(0).Value
'            txtSecurityCustName.Value = txtCustomerName(0).Value
'        Else
'            txtSecurityTotal.Value = txtServiceCode(1).Value * (txtSecurityPercent.Value / 100)
'            txtSecurityCust.Value = txtCustomerId(1).Value
'            txtSecurityCustName.Value = txtCustomerName(1).Value
'        End If
'    End If
'
'End Sub
Private Sub cmdChangeDates_Click()

    gsSqlStmt = "SELECT * FROM VOYAGE WHERE LR_NUM = " & txtLRNum.Text
    Set dsVOYAGE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVOYAGE.RecordCount > 0 Then
        dsVOYAGE.Edit
        If IsDate(txtDateArrived.Text) Then
            dsVOYAGE.fields("DATE_ARRIVED").Value = txtDateArrived.Text
        Else
            MsgBox "Please enter a valid Date Arrived.", vbInformation, "Invalid Date Arrived"
            Exit Sub
        End If
        If IsDate(txtDateDeparted.Text) Then
            dsVOYAGE.fields("DATE_DEPARTED").Value = txtDateDeparted.Text
        Else
            MsgBox "Please enter a valid Date Departed.", vbInformation, "Invalid Date Departed"
            Exit Sub
        End If
        dsVOYAGE.Update
        Call txtLRNum_LostFocus
    Else
        MsgBox "Vessel Voyage information does not exist. Dates not changed.", vbExclamation, "Change Dates"
    End If
    
End Sub



Private Sub cmdCustomerList_Click(Index As Integer)
    
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Customer List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
        While Not dsCUSTOMER_PROFILE.eof
            frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.fields("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
            dsCUSTOMER_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCustomerId(Index).Text = Left$(gsPVItem, iPos - 1)
            txtCustomerName(Index).Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtCustomerId(Index).SetFocus
    
End Sub
Private Sub cmdCustomerListLines_Click(Index As Integer)
    
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Customer List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
        While Not dsCUSTOMER_PROFILE.eof
            frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.fields("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
            dsCUSTOMER_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCustomerIdLines(Index).Text = Left$(gsPVItem, iPos - 1)
            txtCustomerNameLines(Index).Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtCustomerIdLines(Index).SetFocus
    
End Sub
Private Sub cmdExit_Click()

    Unload Me
    frmVeslEnt.Show
    
End Sub


Private Sub cmdSave_Click()
    Dim iResponse As Integer
    Dim lBillingNum As Long
    Dim lStartBillNum As Long
    Dim lEndBillNum As Long
    Dim i As Integer
    Dim berth_num As Integer
    Dim iRecSaved As Integer
    Dim lRecCount As Long
    Dim sEditAdd As String
    Dim dsLRNUM As Object
    Dim dsBerthNum As Object
    Dim iSecurityCustNum As Integer
    
    'Lock all the required tables in exclusive mode, try 10 times
    'On Error Resume Next
    'For i = 0 To 9
    '    OraDatabase.LastServerErrReset
    '    gsSqlStmt = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
    '    lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
    '    If OraDatabase.LastServerErr = 0 Then Exit For
    'Next 'i
    
    'If OraDatabase.LastServerErr <> 0 Then
    ''    OraDatabase.LastServerErr
    '    MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
    '    Exit Sub
    'End If
    
    On Error GoTo errHandler
    
    'Get the new max values, replace with the sequence later
    gsSqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
    Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
        If IsNull(dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value) Then
            lBillingNum = 1
        Else
            lBillingNum = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
        End If
    Else
        lBillingNum = 1
    End If
        
    'Initialize the variables
    lStartBillNum = lBillingNum
    lEndBillNum = 0
    
    'Begin a transaction
    OraSession.BeginTrans
    
    'Save Dockage information
    gsSqlStmt = "SELECT * FROM BILLING"
    Set dsBILLING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        For i = 0 To 3
            If Trim$(txtServiceCode(i)) <> "" Then
                gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & Trim(txtLRNum.Text) & "' " _
                          & "AND CUSTOMER_ID = '" & Trim(txtCustomerId(i).Text) & "' " _
                          & "AND SERVICE_CODE =' " & Trim(txtServiceCode(i).Text) & "' " _
                          & "AND BILLING_TYPE = 'DOCKAGE' AND " _
                          & "SERVICE_STATUS = 'INVOICED'"
                Set dsBILLING_CONF = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                
                If dsBILLING_CONF.RecordCount > 0 Then
                    MsgBox "Dockage Bill : " & i & " has already been Invoiced and won't be saved again!", vbInformation + vbExclamation, "DOCKAGE"
                Else
                    gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & Trim(txtLRNum.Text) & "' " _
                              & "AND CUSTOMER_ID = '" & Trim(txtCustomerId(i).Text) & "' " _
                              & "AND SERVICE_CODE =' " & Trim(txtServiceCode(i).Text) & "' " _
                              & "AND BILLING_TYPE = 'DOCKAGE' AND " _
                              & "SERVICE_STATUS = 'PREINVOICE'"
                    Set dsBILLING_CONF = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                    
                    If dsBILLING_CONF.RecordCount > 0 Then
                        MsgBox "Dockage Bill " & i & ": Please note that there is a prebill in the system that has the same vessel #, customer ID and service code!" & vbCrLf & _
                               "Please make sure this is not a duplicate!", vbInformation + vbExclamation, "DOCKAGE"
                    End If
                    
                    dsBILLING.AddNew
                    dsBILLING.fields("CUSTOMER_ID").Value = txtCustomerId(i).Text
                    dsBILLING.fields("SERVICE_CODE").Value = txtServiceCode(i).Text
                    dsBILLING.fields("BILLING_NUM").Value = lBillingNum
                    dsBILLING.fields("EMPLOYEE_ID").Value = 4
                    dsBILLING.fields("SERVICE_START").Value = Format(txtDateArrived, "MM/DD/YYYY")
                    dsBILLING.fields("SERVICE_STOP").Value = Format(txtDateDeparted, "MM/DD/YYYY")
                    dsBILLING.fields("SERVICE_AMOUNT").Value = txtServiceAmount(i).Text
                    dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
                    dsBILLING.fields("SERVICE_DESCRIPTION").Value = Trim$(txtDescription(i).Text)
                    dsBILLING.fields("LR_NUM").Value = txtLRNum.Text
                    dsBILLING.fields("ARRIVAL_NUM").Value = 1
                    dsBILLING.fields("INVOICE_NUM").Value = 0
                    dsBILLING.fields("SERVICE_DATE").Value = Format(txtDateDeparted, "MM/DD/YYYY")    'Charged from date arrived to date departed per Antonia  -- LFW, 5/5/05
                    dsBILLING.fields("SERVICE_NUM").Value = 1
                    dsBILLING.fields("THRESHOLD_QTY").Value = 0
                    dsBILLING.fields("LEASE_NUM").Value = 0
                    dsBILLING.fields("SERVICE_UNIT").Value = txtServiceUnit(i).Text
                    dsBILLING.fields("SERVICE_RATE").Value = txtServiceRate(i).Text
                    dsBILLING.fields("PAGE_NUM").Value = 1
                    dsBILLING.fields("CARE_OF").Value = chkCareOf(i).Value
                    dsBILLING.fields("BILLING_TYPE").Value = "DOCKAGE"
                    dsBILLING.fields("SERVICE_QTY2").Value = Val(txtDays(i).Text)
                    
                    'Added (lines below) to incorporate ASSET_CODES for importing into
                    'solomon 06.08.2001 LJG
                    
                    gsSqlStmt = " Select * from VOYAGE where LR_NUM = '" & Trim$(txtLRNum.Text) & "' "
                    Set dsLRNUM = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                        berth_num = NVL(dsLRNUM.fields("BERTH_NUM").Value, 0)
                     
                    
                     'If dsLRNum.fields("BERTH_NUM").Value <> "" Then
                     '   If dsLRNum.fields("BERTH_NUM").Value = 1 Then
                     '       dsBILLING.fields("ASSET_CODE").Value = "BE01"
                     '   ElseIf dsLRNum.fields("BERTH_NUM").Value = 2 Then
                     '       dsBILLING.fields("ASSET_CODE").Value = "BE02"
                     '   ElseIf dsLRNum.fields("BERTH_NUM").Value = 3 Then
                     '       dsBILLING.fields("ASSET_CODE").Value = "BE03"
                     '   ElseIf dsLRNum.fields("BERTH_NUM").Value = 4 Then
                     '       dsBILLING.fields("ASSET_CODE").Value = "BE04"
                     '   ElseIf dsLRNum.fields("BERTH_NUM").Value = 5 Then
                     '       dsBILLING.fields("ASSET_CODE").Value = "BE05"
                     '   ElseIf dsLRNum.fields("BERTH_NUM").Value = 6 Then
                     '       dsBILLING.fields("ASSET_CODE").Value = "BE06"
                     '   ElseIf dsLRNum.fields("BERTH_NUM").Value = 7 Then
                     '       dsBILLING.fields("ASSET_CODE").Value = "BE07"
                     '   ElseIf dsLRNum.fields("BERTH_NUM").Value = 8 Then
                     '       dsBILLING.fields("ASSET_CODE").Value = "BEFL"
                     '   End If
                     'End If
                                         
                    
                   If berth_num <> 0 Then
                      gsSqlStmt = " Select * from BERTH_DETAIL where BERTH_NUM = " & berth_num & ""
                      Set dsBerthNum = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                          dsBILLING.fields("ASSET_CODE").Value = dsBerthNum.fields("ASSET_CODE").Value
                   End If
                                      
                       
                    dsBILLING.Update
                    lBillingNum = lBillingNum + 1

                    ' new code for security charge.  copying a swath of the above code...
                    ' i hate doing that, but this needs done, like, yesterday.
                    If Not IsNull(txtSecurityDockBill.Text) And txtSecurityDockBill.Text <> "0" And txtSecurityDockBill.Text <> "" And ((Trim$(txtServiceCode(i)) = "1112" And cmbSecurityType.ListIndex = 3) Or ((Trim$(txtServiceCode(i)) = "1122" Or Trim$(txtServiceCode(i)) = "1124" Or Trim$(txtServiceCode(i)) = "1126" Or Trim$(txtServiceCode(i)) = "1114") And cmbSecurityType.ListIndex = 2)) Then
                        dsBILLING.AddNew
                        dsBILLING.fields("CUSTOMER_ID").Value = txtSecurityCust.Text
                        dsBILLING.fields("SERVICE_CODE").Value = txtSecurityService.Text
                        dsBILLING.fields("BILLING_NUM").Value = lBillingNum
                        dsBILLING.fields("EMPLOYEE_ID").Value = 4
                        dsBILLING.fields("SERVICE_START").Value = Format(txtDateArrived, "MM/DD/YYYY")
                        dsBILLING.fields("SERVICE_STOP").Value = Format(txtDateDeparted, "MM/DD/YYYY")
                        dsBILLING.fields("SERVICE_AMOUNT").Value = txtSecurityDockBill.Text
                        dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
                        dsBILLING.fields("SERVICE_DESCRIPTION").Value = txtSecurityDesc.Text
                        dsBILLING.fields("LR_NUM").Value = txtLRNum.Text
                        dsBILLING.fields("ARRIVAL_NUM").Value = 1
                        dsBILLING.fields("INVOICE_NUM").Value = 0
                        dsBILLING.fields("SERVICE_DATE").Value = Format(txtDateDeparted, "MM/DD/YYYY")    'Charged from date arrived to date departed per Antonia  -- LFW, 5/5/05
                        dsBILLING.fields("SERVICE_NUM").Value = 1
                        dsBILLING.fields("THRESHOLD_QTY").Value = 0
                        dsBILLING.fields("LEASE_NUM").Value = 0
                        dsBILLING.fields("SERVICE_UNIT").Value = "FEE"
                        dsBILLING.fields("SERVICE_RATE").Value = 0
                        dsBILLING.fields("PAGE_NUM").Value = 1
                        dsBILLING.fields("CARE_OF").Value = 1
                        dsBILLING.fields("BILLING_TYPE").Value = "DOCKAGE"
                        dsBILLING.fields("SERVICE_QTY2").Value = 0

                         gsSqlStmt = " Select * from VOYAGE where LR_NUM = '" & Trim$(txtLRNum.Text) & "' "
                         Set dsLRNUM = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                             berth_num = NVL(dsLRNUM.fields("BERTH_NUM").Value, 0)



                        If berth_num <> 0 Then
                           gsSqlStmt = " Select * from BERTH_DETAIL where BERTH_NUM = " & berth_num & ""
                           Set dsBerthNum = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                               dsBILLING.fields("ASSET_CODE").Value = dsBerthNum.fields("ASSET_CODE").Value
                        End If

                        dsBILLING.Update
                        lBillingNum = lBillingNum + 1
                    End If
                    ' end new Security charge code

                           
                End If
            End If
        Next i
        
    End If
       
    lEndBillNum = lBillingNum - 1
   
    'Save Lines information
    gsSqlStmt = "SELECT * FROM BILLING"
    Set dsBILLING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        For i = 0 To 5
            If Trim$(txtServiceCodeLines(i)) <> "" Then
                gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & Trim(txtLRNum.Text) & "' " _
                          & "AND CUSTOMER_ID ='" & Trim(txtCustomerIdLines(i).Text) & "' " _
                          & "AND SERVICE_CODE = '" & Trim(txtServiceCodeLines(i).Text) & "' " _
                          & "AND BILLING_TYPE = 'LINES' AND " _
                          & "SERVICE_STATUS = 'INVOICED'"
                Set dsBILLING_CONF = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                
                If dsBILLING_CONF.RecordCount > 0 Then
                    MsgBox "Lines Bill : " & i & " have already been Invoiced and won't be saved again.", vbInformation + vbExclamation, "LINES"
                Else
                    gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & Trim(txtLRNum.Text) & "' " _
                              & "AND CUSTOMER_ID ='" & Trim(txtCustomerIdLines(i).Text) & "' " _
                              & "AND SERVICE_CODE = '" & Trim(txtServiceCodeLines(i).Text) & "' " _
                              & "AND BILLING_TYPE = 'LINES' AND " _
                              & "SERVICE_STATUS = 'PREINVOICE'"
                    Set dsBILLING_CONF = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                    
                    If dsBILLING_CONF.RecordCount > 0 Then
                        MsgBox "Lines Bill " & i & ": Please note that there is a prebill in the system that has the same vessel #, customer ID and service code!" & vbCrLf & _
                               "Please make sure this is not a duplicate!", vbInformation + vbExclamation, "Lines"
                    End If
                    
                    dsBILLING.AddNew
                    dsBILLING.fields("CUSTOMER_ID").Value = txtCustomerIdLines(i).Text
                    dsBILLING.fields("SERVICE_CODE").Value = txtServiceCodeLines(i).Text
                    dsBILLING.fields("BILLING_NUM").Value = lBillingNum
                    dsBILLING.fields("EMPLOYEE_ID").Value = 4
                    dsBILLING.fields("SERVICE_START").Value = txtServiceDateLines(i).Text & " " & txtServiceStartLines(i).Text & ":00"
                    dsBILLING.fields("SERVICE_STOP").Value = txtServiceDateLines(i).Text & " " & txtServiceStopLines(i).Text & ":00"
                    dsBILLING.fields("SERVICE_AMOUNT").Value = txtServiceAmountLines(i).Text
                    dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
                    dsBILLING.fields("SERVICE_DESCRIPTION").Value = Trim$(txtDescriptionLines(i).Text)
                    dsBILLING.fields("LR_NUM").Value = txtLRNum.Text
                    dsBILLING.fields("ARRIVAL_NUM").Value = 1
                    dsBILLING.fields("INVOICE_NUM").Value = 0
                    dsBILLING.fields("SERVICE_DATE").Value = txtServiceDate.Text
                    dsBILLING.fields("SERVICE_NUM").Value = 1
                    dsBILLING.fields("THRESHOLD_QTY").Value = 0
                    dsBILLING.fields("LEASE_NUM").Value = 0
                    dsBILLING.fields("PAGE_NUM").Value = Val(Trim(txtPage(i)))
                    dsBILLING.fields("SERVICE_QTY").Value = 1   'Added to make Monthly Vessel Billing Report look better  -- LFW, 5/5/05
                    dsBILLING.fields("SERVICE_RATE").Value = txtServiceRateLines(i).Text
                    dsBILLING.fields("PAGE_NUM").Value = txtPage(i)
                    dsBILLING.fields("CARE_OF").Value = chkCareOfLines(i).Value
                    dsBILLING.fields("BILLING_TYPE").Value = "LINES"
                    If i < 2 Then dsBILLING.fields("SERVICE_QTY2").Value = Val(txtDays(i).Text)
                    
                    'Added (lines below) to incorporate ASSET_CODES for importing into
                    'solomon 06.08.2001 LJG
                    
                    gsSqlStmt = " Select * from VOYAGE where LR_NUM = '" & Trim$(txtLRNum.Text) & "' "
                    Set dsLRNUM = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                        berth_num = NVL(dsLRNUM.fields("BERTH_NUM").Value, 0)

'                    If frmVeslEnt.txtBerthNum <> "" Then
'                        If dsLRNum.fields("BERTH_NUM").Value = 1 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE01"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 2 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE02"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 3 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE03"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 4 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE04"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 5 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE05"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 6 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE06"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 7 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE07"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 8 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BEFL"
'                        End If
'                    End If

                   If berth_num <> 0 Then
                      gsSqlStmt = " Select * from BERTH_DETAIL where BERTH_NUM = " & berth_num & ""
                      Set dsBerthNum = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                          dsBILLING.fields("ASSET_CODE").Value = dsBerthNum.fields("ASSET_CODE").Value
                   End If

                    
                    dsBILLING.Update
                    lBillingNum = lBillingNum + 1
                End If
            End If
        Next i
    End If
    
    lEndBillNum = lBillingNum - 1
   
    'if we processed dockage/lines bills
    If lEndBillNum >= lStartBillNum Then
        Call AddNewInvDt("Dockage/Lines", CStr(lStartBillNum), CStr(lEndBillNum))
    End If
    
    If OraDatabase.LastServerErr <> 0 Then
        MsgBox "Error occured while saving Dockage/Lines Bill. Prebills are not saved.", vbExclamation, "Save"
        OraSession.Rollback
        Exit Sub
    Else
        OraSession.CommitTrans
    End If
    
    'initialize the starting billing number for Wharfage
    lStartBillNum = lBillingNum
    
    'Begin a transaction
    OraSession.BeginTrans
    
    'Save Wharfage information
    gsSqlStmt = "SELECT * FROM BILLING"
    Set dsBILLING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        grdWharfage.MoveFirst
        For iRec = 0 To grdWharfage.Rows - 1
                                          
            If grdWharfage.Columns(0).Value <> "" And grdWharfage.Columns(3).Value <> "" And grdWharfage.Columns(7).Value <> "" Then
                gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & CLng(Trim(txtLRNum.Text)) & "' " _
                          & "AND CUSTOMER_ID ='" & CLng(Trim(grdWharfage.Columns(7).Value)) & "' " _
                          & "AND SERVICE_CODE = '" & CLng(Trim(grdWharfage.Columns(0).Value)) & "' " _
                          & "AND BILLING_TYPE = 'WHARFAGE' AND COMMODITY_CODE = " _
                          & " '" & CLng(Trim(grdWharfage.Columns(3).Value)) & "' And " _
                          & "SERVICE_STATUS = 'INVOICED'"
                Set dsBILLING_CONF = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                
                If dsBILLING_CONF.RecordCount > 0 Then
                    MsgBox "Wharfage Bill :" & i & " already invoiced and cannot be saved.", vbInformation + vbExclamation, "LINES"
                Else
                    gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & CLng(Trim(txtLRNum.Text)) & "' " _
                              & "AND CUSTOMER_ID ='" & CLng(Trim(grdWharfage.Columns(7).Value)) & "' " _
                              & "AND SERVICE_CODE = '" & CLng(Trim(grdWharfage.Columns(0).Value)) & "' " _
                              & "AND BILLING_TYPE = 'WHARFAGE' AND COMMODITY_CODE = " _
                              & " '" & CLng(Trim(grdWharfage.Columns(3).Value)) & "' And " _
                              & "SERVICE_STATUS = 'PREINVOICE'"
                    Set dsBILLING_CONF = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                    
                    If dsBILLING_CONF.RecordCount > 0 Then
                        MsgBox "Wharfage Bill " & i & ": Please note that there is a prebill in the system that has the same vessel #, customer ID, service code and commodity code!" & vbCrLf & _
                               "Please make sure this is not a duplicate!", vbInformation + vbExclamation, "Wharfage"
                    End If
                    
                    dsBILLING.AddNew
                    dsBILLING.fields("INVOICE_NUM").Value = 0
                    dsBILLING.fields("EMPLOYEE_ID").Value = 4
                    dsBILLING.fields("ARRIVAL_NUM").Value = 1
                    dsBILLING.fields("SERVICE_NUM").Value = 1
                    dsBILLING.fields("THRESHOLD_QTY").Value = 0
                    dsBILLING.fields("LEASE_NUM").Value = 0
                    dsBILLING.fields("PAGE_NUM").Value = 1
                    dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
                    dsBILLING.fields("BILLING_TYPE").Value = "WHARFAGE"
                    dsBILLING.fields("BILLING_NUM").Value = lBillingNum
                    dsBILLING.fields("SERVICE_DATE").Value = txtServiceDate.Text
                    dsBILLING.fields("LR_NUM").Value = Trim(txtLRNum.Text)
                    dsBILLING.fields("SERVICE_START").Value = txtServiceDate.Text & " " & "00:00:00"
                    dsBILLING.fields("SERVICE_STOP").Value = txtServiceDate.Text & " " & "00:00:00"
                    dsBILLING.fields("SERVICE_CODE").Value = Trim(grdWharfage.Columns(0).Value)
                    dsBILLING.fields("SERVICE_DESCRIPTION").Value = Trim(grdWharfage.Columns(1).Value)
                    If grdWharfage.Columns(2).Value = -1 Then
                        dsBILLING.fields("CARE_OF").Value = 1
                    Else
                        dsBILLING.fields("CARE_OF").Value = 0
                    End If
                    dsBILLING.fields("COMMODITY_CODE").Value = Trim(grdWharfage.Columns(3).Value)
                    dsBILLING.fields("SERVICE_QTY").Value = Trim(grdWharfage.Columns(4).Value)
                    dsBILLING.fields("SERVICE_RATE").Value = Trim(grdWharfage.Columns(5).Value)
                    dsBILLING.fields("SERVICE_AMOUNT").Value = Trim(grdWharfage.Columns(6).Value)
                    dsBILLING.fields("CUSTOMER_ID").Value = Trim(grdWharfage.Columns(7).Value)
                    
                    'Added (lines below) to incorporate ASSET_CODES for importing into
                    'solomon 06.08.2001 LJG
                    
                    gsSqlStmt = " Select * from VOYAGE where LR_NUM = '" & Trim$(txtLRNum.Text) & "' "
                    Set dsLRNUM = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                        berth_num = NVL(dsLRNUM.fields("BERTH_NUM").Value, 0)
                     

'                    If frmVeslEnt.txtBerthNum <> "" Then
'                        If dsLRNum.fields("BERTH_NUM").Value = 1 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE01"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 2 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE02"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 3 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE03"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 4 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE04"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 5 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE05"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 6 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE06"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 7 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BE07"
'                        ElseIf dsLRNum.fields("BERTH_NUM").Value = 8 Then
'                            dsBILLING.fields("ASSET_CODE").Value = "BEFL"
'                        End If
'                    End If
                    
                   If berth_num <> 0 Then
                      gsSqlStmt = " Select * from BERTH_DETAIL where BERTH_NUM = " & berth_num & ""
                      Set dsBerthNum = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                          dsBILLING.fields("ASSET_CODE").Value = dsBerthNum.fields("ASSET_CODE").Value
                   End If

                    dsBILLING.Update
                    lBillingNum = lBillingNum + 1
                End If
            End If
            grdWharfage.MoveNext
        Next iRec
        
        
        ' now for the Security table.
        ' due to ciscmstances outside my control, i have to (potentially) show *more* lines here then are actually
        ' Wharfage-billed.  Also, if multiple lines have the same customer and COMM values, they need to be summed into the DB.
        ' Don't ask... Eesh.
        If grdSecurity.Rows > 0 Then
            Dim commCheck As String
        
            gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & CLng(Trim(txtLRNum.Text)) & "' " _
                        & "AND CUSTOMER_ID ='" & CLng(Trim(grdSecurity.Columns(7).Value)) & "' " _
                        & "AND SERVICE_CODE = '" & CLng(Trim(grdSecurity.Columns(0).Value)) & "' " _
                        & "AND BILLING_TYPE = '" & Trim(grdSecurity.Columns(10).Value) & "' AND SERVICE_DESCRIPTION = 'SECURITY' "
            Set dsBILLING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            grdSecurity.MoveFirst
            For iRec = 0 To grdSecurity.Rows - 1
                ' this little gem is because, as per above, similar customer-commodity sets get summed together for Wharfage, but not other types.
                ' IDLE doesnt have commodities at all, so to make the SQL not go nuts, we need....
                If Trim(grdSecurity.Columns(10).Value) = "WHARFAGE" Then
                    commCheck = " AND COMMODITY_CODE = '" & CLng(Trim(grdSecurity.Columns(3).Value)) & "' "
                Else
                    commCheck = " AND COMMODITY_CODE IS NULL "
                End If
                                              
    '            If grdSecurity.Columns(0).Value <> "" And grdSecurity.Columns(3).Value <> "" And grdSecurity.Columns(7).Value <> "" Then
                If grdSecurity.Columns(2).Value <> 0 Then
                    gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & CLng(Trim(txtLRNum.Text)) & "' " _
                              & "AND CUSTOMER_ID ='" & CLng(Trim(grdSecurity.Columns(7).Value)) & "' " _
                              & "AND SERVICE_CODE = '" & CLng(Trim(grdSecurity.Columns(0).Value)) & "' " _
                              & "AND BILLING_TYPE = '" & Trim(grdSecurity.Columns(10).Value) & "' AND SERVICE_DESCRIPTION = 'SECURITY' " & commCheck & " And " _
                              & "SERVICE_STATUS = 'INVOICED'"
                    Set dsBILLING_CONF = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                    
                    If dsBILLING_CONF.RecordCount > 0 Then
                        MsgBox "Security Bill :" & (iRec + 1) & " already invoiced and cannot be saved.", vbInformation + vbExclamation, "LINES"
                    Else
                        gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & CLng(Trim(txtLRNum.Text)) & "' " _
                                  & "AND CUSTOMER_ID ='" & CLng(Trim(grdSecurity.Columns(7).Value)) & "' " _
                                  & "AND SERVICE_CODE = '" & CLng(Trim(grdSecurity.Columns(0).Value)) & "' " _
                                  & "AND BILLING_TYPE = '" & Trim(grdSecurity.Columns(10).Value) & "' AND SERVICE_DESCRIPTION = 'SECURITY' " & commCheck & " And " _
                                  & "SERVICE_STATUS = 'PREINVOICE'"
                        Set dsBILLING_CONF = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                        
    '                    If dsBILLING_CONF.Recordcount > 0 Then
    '                        MsgBox "Security Bill " & (iRec + 1) & ": Please note that there is a prebill in the system that has the same vessel #, customer ID, service code and commodity code!" & vbCrLf & _
    '                               "Please make sure this is not a duplicate!", vbInformation + vbExclamation, "Wharfage"
    '                    End If
                        Dim serv_date As String
                        Dim serv_start As String
                        Dim serv_stop As String
                        Dim serv_desc As String
                        If Trim(grdSecurity.Columns(10).Value) = "WHARFAGE" Then
                            serv_date = txtServiceDate.Text
                            serv_start = txtServiceDate.Text & " " & "00:00:00"
                            serv_stop = txtServiceDate.Text & " " & "00:00:00"
                            serv_desc = "SECURITY"
                        Else
                            serv_date = Format(txtDateDeparted, "MM/DD/YYYY")
                            serv_start = Format(txtDateArrived, "MM/DD/YYYY")
                            serv_stop = Format(txtDateDeparted, "MM/DD/YYYY")
                            serv_desc = "IDLE LAY BERTH SECURITY CHARGE"
                        End If
                        
                        
                        If dsBILLING_CONF.RecordCount = 0 Or Trim(grdSecurity.Columns(10).Value) <> "WHARFAGE" Then ' new line in BILLING
                            dsBILLING.AddNew
                            dsBILLING.fields("INVOICE_NUM").Value = 0
                            dsBILLING.fields("EMPLOYEE_ID").Value = 4
                            dsBILLING.fields("ARRIVAL_NUM").Value = 1
                            dsBILLING.fields("SERVICE_NUM").Value = 1
                            dsBILLING.fields("THRESHOLD_QTY").Value = 0
                            dsBILLING.fields("LEASE_NUM").Value = 0
                            dsBILLING.fields("PAGE_NUM").Value = 1
                            dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
                            dsBILLING.fields("BILLING_TYPE").Value = Trim(grdSecurity.Columns(10).Value)
                            dsBILLING.fields("BILLING_NUM").Value = lBillingNum
                            dsBILLING.fields("SERVICE_DATE").Value = serv_date
                            dsBILLING.fields("LR_NUM").Value = Trim(txtLRNum.Text)
                            dsBILLING.fields("SERVICE_START").Value = serv_start
                            dsBILLING.fields("SERVICE_STOP").Value = serv_stop
                            dsBILLING.fields("SERVICE_CODE").Value = Trim(grdSecurity.Columns(0).Value)
                            dsBILLING.fields("SERVICE_DESCRIPTION").Value = serv_desc
                            If grdSecurity.Columns(2).Value = -1 Then
                                dsBILLING.fields("CARE_OF").Value = 1
                            Else
                                dsBILLING.fields("CARE_OF").Value = 0
                            End If
                            dsBILLING.fields("COMMODITY_CODE").Value = Trim(grdSecurity.Columns(3).Value)
                            dsBILLING.fields("SERVICE_QTY").Value = Trim(grdSecurity.Columns(4).Value)
                            dsBILLING.fields("SERVICE_RATE").Value = Trim(grdSecurity.Columns(5).Value)
                            dsBILLING.fields("SERVICE_AMOUNT").Value = Trim(grdSecurity.Columns(6).Value)
                            dsBILLING.fields("CUSTOMER_ID").Value = Trim(grdSecurity.Columns(7).Value)
                            dsBILLING.fields("SERVICE_UNIT").Value = "FEE"
                            
                            'Added (lines below) to incorporate ASSET_CODES for importing into
                            'solomon 06.08.2001 LJG
                            
                            gsSqlStmt = " Select * from VOYAGE where LR_NUM = '" & Trim$(txtLRNum.Text) & "' "
                            Set dsLRNUM = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                                berth_num = NVL(dsLRNUM.fields("BERTH_NUM").Value, 0)
                             
                            
                           If berth_num <> 0 Then
                              gsSqlStmt = " Select * from BERTH_DETAIL where BERTH_NUM = " & berth_num & ""
                              Set dsBerthNum = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                                  dsBILLING.fields("ASSET_CODE").Value = dsBerthNum.fields("ASSET_CODE").Value
                           End If
        
                            dsBILLING.Update
                            lBillingNum = lBillingNum + 1
                        Else ' update the security line in BILLING
                            gsSqlStmt = "UPDATE BILLING SET SERVICE_QTY = SERVICE_QTY + " & Val(Trim(grdSecurity.Columns(4).Value)) & ", SERVICE_AMOUNT = SERVICE_AMOUNT + " & Val(Trim(grdSecurity.Columns(6).Value)) & " " _
                                    & "WHERE LR_NUM = '" & CLng(Trim(txtLRNum.Text)) & "' " _
                                    & "AND CUSTOMER_ID ='" & CLng(Trim(grdSecurity.Columns(7).Value)) & "' " _
                                    & "AND SERVICE_CODE = '" & CLng(Trim(grdSecurity.Columns(0).Value)) & "' " _
                                    & "AND BILLING_TYPE = '" & Trim(grdSecurity.Columns(10).Value) & "' AND SERVICE_DESCRIPTION = 'SECURITY' " & commCheck
                            OraDatabase.ExecuteSQL (gsSqlStmt)
    '                        dsBILLING.Edit
    '                        dsBILLING.fields("SERVICE_QTY").Value = dsBILLING.fields("SERVICE_QTY").Value + Val(Trim(grdSecurity.Columns(4).Value))
    '                        dsBILLING.fields("SERVICE_AMOUNT").Value = dsBILLING.fields("SERVICE_AMOUNT").Value + Val(Trim(grdSecurity.Columns(6).Value))
    '                        dsBILLING.Update
                        End If
                    End If
                End If
                grdSecurity.MoveNext
            Next iRec
        End If
'       Call Save_Original_Manifest
    End If
    
    lEndBillNum = lBillingNum - 1
    
    'if we processed wharfage bills
    If lEndBillNum >= lStartBillNum Then
        Call AddNewInvDt("Wharfage", CStr(lStartBillNum), CStr(lEndBillNum))
    End If
        
    If OraDatabase.LastServerErr <> 0 Then
        MsgBox "Error occured while saving Wharfage Bill. Wharfage Bill not saved.", vbExclamation, "Save"
        OraSession.Rollback
        Exit Sub
    Else
        OraSession.CommitTrans
    End If
    
    Call ClearScreen
       
        
    MsgBox "Vessel Billing - Prebills Were Generated Successfully! " & vbCrLf & _
           "Please go to the BNI System on the Intranet to Print Prebills.", vbInformation, "Vessel Billing"

    Exit Sub

errHandler:
     
    If OraDatabase.LastServerErr = 0 Then
         MsgBox "Error occured. Unable to process ADVANCE BILLING prebills!", vbExclamation, "ADVANCE BILLS"
    Else
         MsgBox "Error " & OraDatabase.LastServerErrText & " occured." & vbCrLf & _
                "Unable to process ADVANCE BILLING prebills!", vbExclamation, "ADVANCE BILLS"
    End If
         
    OraSession.Rollback
    OraDatabase.LastServerErrReset
   
End Sub
Sub Save_Original_Manifest()
    
'    lblStatus = "Saving Original Manifest..."
'
'    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST_ORIGINAL WHERE LR_NUM='" & Trim(txtLRNum) & "'"
'    Set dsCARGO_MANIFEST_ORIGINAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_ORIGINAL.RECORDCOUNT > 0 Then
'        Exit Sub
'    End If
'
'    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM='" & Trim(txtLRNum) & "' ORDER BY CONTAINER_NUM"
'    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
'        For iRec = 1 To dsCARGO_MANIFEST.RECORCOUNT
'
'            dsCARGO_MANIFEST_ORIGINAL.AddNew
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("LR_NUM").Value = Trim(txtLRNum)
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CONTAINER_NUM").Value = dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("RECIPIENT_ID").Value = dsCARGO_MANIFEST.FIELDS("RECIPIENT_ID").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("ARRIVAL_NUM").Value = dsCARGO_MANIFEST.FIELDS("ARRIVAL_NUM").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY_EXPECTED").Value = dsCARGO_MANIFEST.FIELDS("QTY_EXPECTED").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_WEIGHT").Value = dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_WEIGHT_UNIT").Value = dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT_UNIT").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_BOL").Value = dsCARGO_MANIFEST.FIELDS("CARGO_BOL").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_MARK").Value = dsCARGO_MANIFEST.FIELDS("CARGO_MARK").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_DECK").Value = dsCARGO_MANIFEST.FIELDS("CARGO_DECK").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_HATCH").Value = dsCARGO_MANIFEST.FIELDS("CARGO_TREATMENT").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("COMMODITY_CODE").Value = dsCARGO_MANIFEST.FIELDS("COMMODITY_CODE").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_LOCATION").Value = dsCARGO_MANIFEST.FIELDS("CARGO_LOCATION").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("MANIFEST_STATUS").Value = dsCARGO_MANIFEST.FIELDS("MANIFEST_STATUS").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY2_EXPECTED").Value = dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY1_UNIT").Value = dsCARGO_MANIFEST.FIELDS("QTY1_UNIT").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY2_UNIT").Value = dsCARGO_MANIFEST.FIELDS("QTY2_UNIT").Value
'            dsCARGO_MANIFEST_ORIGINAL.FIELDS("IMPEX").Value = dsCARGO_MANIFEST.FIELDS("IMPEX").Value
'            dsCARGO_MANIFEST_ORIGINAL.Update
'
'            dsCARGO_MANIFEST.MoveNext
'        Next iRec
'    End If
'    lblStatus = "Saved Original Manifest"
    
End Sub
Private Sub cmdServiceList_Click(Index As Integer)
    
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Service List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE UPPER(SERVICE_NAME) LIKE '%DOCK%'"
    If Index = 2 Or Index = 3 Then
        gsSqlStmt = gsSqlStmt & " AND UPPER(SERVICE_NAME) LIKE '%IDLE%'"
    Else
        gsSqlStmt = gsSqlStmt & " AND UPPER(SERVICE_NAME) NOT LIKE '%IDLE%'"
    End If
    gsSqlStmt = gsSqlStmt & " ORDER BY SERVICE_CODE"
    
    Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RecordCount > 0 Then
        While Not dsSERVICE_CATEGORY.eof
            frmPV.lstPV.AddItem dsSERVICE_CATEGORY.fields("SERVICE_CODE").Value & " : " & dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value
            dsSERVICE_CATEGORY.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtServiceCode(Index).Text = Left$(gsPVItem, iPos - 1)
            txtServiceName(Index).Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtServiceCode(Index).SetFocus
    
End Sub
Private Sub cmdServiceListLines_Click(Index As Integer)
    
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Service List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY ORDER BY SERVICE_CODE"
    Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RecordCount > 0 Then
        While Not dsSERVICE_CATEGORY.eof
            frmPV.lstPV.AddItem dsSERVICE_CATEGORY.fields("SERVICE_CODE").Value & " : " & dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value
            dsSERVICE_CATEGORY.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtServiceCodeLines(Index).Text = Left$(gsPVItem, iPos - 1)
            txtServiceNameLines(Index).Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtServiceCodeLines(Index).SetFocus
    
End Sub
Private Sub cmdVesselList_Click()
    
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Vessel List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RecordCount > 0 Then
        While Not dsVESSEL_PROFILE.eof
            frmPV.lstPV.AddItem dsVESSEL_PROFILE.fields("LR_NUM").Value & " : " & dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
            dsVESSEL_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtLRNum.Text = Left$(gsPVItem, iPos - 1)
            txtVesselName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    
End Sub


Private Sub Form_Load()
   
   Dim temploop As Integer
   
    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    
    Me.Show
    Me.Refresh
    DoEvents
    
    cmdSave.Enabled = False
    
    On Error GoTo Err_FormLoad
    
    gsSqlStmt = "SELECT * FROM SECURITY_CHARGE_TYPE ORDER BY SELECTION_NO"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        cmbSecurityType.AddItem ("0 - Select Security Charge")
        
        For temploop = 1 To dsSHORT_TERM_DATA.RecordCount
            cmbSecurityType.AddItem (dsSHORT_TERM_DATA.fields("SELECTION_NO").Value & " - " & dsSHORT_TERM_DATA.fields("TEXT_DESC").Value)
            dsSHORT_TERM_DATA.MoveNext
        Next temploop
    End If
    
    cmbSecurityType.ListIndex = 0
    
    On Error GoTo 0
    
    Call ClearScreen
    
    grdWharfage.RowHeight = 330
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0
    
End Sub
Public Sub CalcServiceAmountLines(aiIndex As Integer)

    Dim dAmt As Double
    Dim iHourStart As Integer
    Dim iHourEnd As Integer
    Dim iMinuteStart As Integer
    Dim iMinuteEnd As Integer
    Dim dHourDiff As Double
    Dim lStartMinute As Integer
    Dim lEndMinute As Integer
    
    
    If Trim$(txtServiceStartLines(aiIndex).Text) = "" Or Trim$(txtServiceStopLines(aiIndex).Text) = "" Then
        Exit Sub
    End If
    
    If Not IsNumeric(txtServiceRateLines(aiIndex).Text) Then
        Exit Sub
    End If
    
    iHourStart = Left$(txtServiceStartLines(aiIndex).Text, 2)
    iHourEnd = Left$(txtServiceStopLines(aiIndex).Text, 2)
    iMinuteStart = Right$(txtServiceStartLines(aiIndex).Text, 2)
    iMinuteEnd = Right$(txtServiceStopLines(aiIndex).Text, 2)
    lStartMinute = iHourStart * 60 + iMinuteStart
    lEndMinute = iHourEnd * 60 + iMinuteEnd
    dHourDiff = (lEndMinute - lStartMinute) / 60#
    If dHourDiff < 0 Then
        Exit Sub
    End If
    txtServiceQty2Lines(aiIndex).Text = Format$(dHourDiff, "0.00")
    txtServiceAmountLines(aiIndex).Text = Format$(Val(txtServiceRateLines(aiIndex).Text) * dHourDiff, "0.00")

End Sub
Private Sub grdWharfage_AfterColUpdate(ByVal ColIndex As Integer)
        
    Select Case ColIndex
        
        Case 0                  ' Service
                
            If grdWharfage.Columns(ColIndex).Value = "" Then Exit Sub
            
            If Not IsNumeric(grdWharfage.Columns(0).Value) Then
                MsgBox "Invalid Service Code", vbInformation, "WHARFAGE"
                grdWharfage.Columns(0).Value = ""
                Exit Sub
            End If
            
            gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " & Trim(grdWharfage.Columns(0).Value)
            Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSERVICE_CATEGORY.RecordCount = 0 Then
                    MsgBox "Invalid Service Code", vbInformation, "WHARFAGE"
                    grdWharfage.Columns(0).Value = ""
                    Exit Sub
                End If
            End If
            
            If grdWharfage.Columns(0).Value <> "" And grdWharfage.Columns(3).Value <> "" And grdWharfage.Columns(7).Value <> "" Then
                Call Vessel_Rate_Amt(grdWharfage.Columns(0).Value, grdWharfage.Columns(3).Value, grdWharfage.Columns(7).Value)
            End If
        
        Case 1                  ' Description
                        
                    
        Case 2                  ' C/0
        
        
        Case 3                  ' Commodity
            If Trim(grdWharfage.Columns(3).Value) = "" Then Exit Sub
            
            If Not IsNumeric(grdWharfage.Columns(3).Value) Then
                MsgBox "Invalid Commodity  Code", vbInformation, "WHARFAGE"
                grdWharfage.Columns(3).Value = ""
                Exit Sub
            End If
            
            gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Trim(grdWharfage.Columns(3).Value)
            Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsCOMMODITY_PROFILE.RecordCount = 0 Then
                    MsgBox "Invalid Commodity Code", vbInformation, "WHARFAGE"
                    grdWharfage.Columns(3).Value = ""
                    Exit Sub
                End If
            End If
            
            If Trim(grdWharfage.Columns(0).Value) <> "" And Trim(grdWharfage.Columns(3).Value) <> "" And Trim(grdWharfage.Columns(7).Value) <> "" Then
                Call Vessel_Rate_Amt(Trim(grdWharfage.Columns(0).Value), Trim(grdWharfage.Columns(3).Value), Trim(grdWharfage.Columns(7).Value))
            End If
        
        Case 4                  ' Qty
        
        
        Case 5                  ' Rate
        
        
        Case 6                  ' Amt
        
        
        Case 7                  ' Customer
            If Trim(grdWharfage.Columns(7).Value) = "" Then Exit Sub
            
            If Not IsNumeric(grdWharfage.Columns(7).Value) Then
                MsgBox "Invalid Commodity  Code", vbInformation, "WHARFAGE"
                grdWharfage.Columns(7).Value = ""
                Exit Sub
            End If
            
            gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Trim(grdWharfage.Columns(7).Value)
            Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsCUSTOMER_PROFILE.RecordCount = 0 Then
                    MsgBox "Invalid Customer Code", vbInformation, "WHARFAGE"
                    grdWharfage.Columns(7).Value = ""
                    Exit Sub
                Else
                    'grdWharfage.Columns(7).Value = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value
                End If
            End If
            
'            If Trim(grdWharfage.Columns(0).Value) <> "" And Trim(grdWharfage.Columns(3).Value) <> "" And Trim(grdWharfage.Columns(7).Value) <> "" Then
'                Call Vessel_Rate_Amt(Trim(grdWharfage.Columns(0).Value), Trim(grdWharfage.Columns(3).Value), Trim(grdWharfage.Columns(7).Value))
'            End If
    End Select
    
End Sub
Sub Vessel_Rate_Amt(iSCode As Integer, iCCode As Integer, iCustId As Integer)
    
    Dim Conversion_Factor As Double
    
    gsSqlStmt = " SELECT * FROM VESSEL_RATE WHERE SERVICE_CODE ='" & iSCode & "'" _
              & " AND (COMMODITY_CODE = '0000' OR COMMODITY_CODE = '" & iCCode & "' ) ORDER BY COMMODITY_CODE DESC "
                
    Set dsVESSEL_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                
    If OraDatabase.LastServerErr = 0 And dsVESSEL_RATE.RecordCount > 0 Then
        grdWharfage.Columns(5).Value = dsVESSEL_RATE.fields("SERVICE_RATE").Value
    Else
        MsgBox "Vessel Rate information for Service " & iSCode & "and Commodity " & iCCode & "  does not exist.", vbExclamation, "Vessel Rate"
        Exit Sub
    End If
    
    
    If dsVESSEL_RATE.fields("UNIT1").Value = "EA" Then
        
        gsSqlStmt = "SELECT SUM(QTY_EXPECTED) SUMQTY FROM CARGO_MANIFEST WHERE LR_NUM = '" & Trim(txtLRNum.Text) & "'" _
                  & " AND COMMODITY_CODE = '" & iCCode & "'" _
                  & " AND RECIPIENT_ID = '" & iCustId & "' "
        
        Set dsCARGO_MANIFEST_SUM = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_SUM.RecordCount > 0 Then
            grdWharfage.Columns(4).Value = dsCARGO_MANIFEST_SUM.fields("SUMQTY").Value
            grdWharfage.Columns(6).Value = Format$(Val(grdWharfage.Columns(4).Value) * Val(grdWharfage.Columns(5).Value), "0.00")
        Else
            MsgBox "QTY could not be found in CARGO MANIFEST.", vbExclamation, "QTY"
        End If
    Else
        gsSqlStmt = "SELECT SUM(CARGO_WEIGHT) SUMWEIGHT FROM CARGO_MANIFEST WHERE LR_NUM = '" & Trim(txtLRNum.Text) & "'" _
                  & " AND COMMODITY_CODE = '" & iCCode & "'" _
                  & " AND RECIPIENT_ID = '" & iCustId & "'" _
        
        Set dsCARGO_MANIFEST_SUM = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_SUM.RecordCount > 0 Then
            If Not IsNull(dsCARGO_MANIFEST_SUM.fields("SUMWEIGHT").Value) Then
                grdWharfage.Columns(4).Value = dsCARGO_MANIFEST_SUM.fields("SUMWEIGHT").Value
            Else
                grdWharfage.Columns(4).Value = "0"
            End If
        
            If dsVESSEL_RATE.fields("UNIT1").Value = "TON" Then
                Conversion_Factor = 1 / 2000#
            Else
                Conversion_Factor = 1
            End If
         
            grdWharfage.Columns(6).Value = Format$(Conversion_Factor * Val(grdWharfage.Columns(4).Value) * Val(grdWharfage.Columns(5).Value), "0.00")
        Else
            MsgBox "Weight for Commodity  " & iCCode & " and Service " & iSCode & " not found in CARGO MANIFEST.", vbExclamation, "Weight"
            Exit Sub
        End If
    End If
    
End Sub
Private Sub grdWharfage_BeforeRowColChange(Cancel As Integer)
    
    If grdWharfage.Col = 7 And Trim(grdWharfage.Columns(7).Value) <> "" Then
            
            If Not IsNumeric(grdWharfage.Columns(7).Value) Then
                MsgBox "Invalid Commodity  Code", vbInformation, "WHARFAGE"
                grdWharfage.Columns(7).Value = ""
                Exit Sub
            End If
            
            gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Trim(grdWharfage.Columns(7).Value)
            Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsCUSTOMER_PROFILE.RecordCount = 0 Then
                    MsgBox "Invalid Customer Code", vbInformation, "WHARFAGE"
                    grdWharfage.Columns(7).Value = ""
                    Exit Sub
                Else
                    grdWharfage.Columns(8).Value = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
                End If
            End If
    End If
    
End Sub
Private Sub txtCustomerId_LostFocus(Index As Integer)
    
    If Trim$(txtCustomerId(Index)) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtCustomerId(Index).Text
        Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
            txtCustomerName(Index).Text = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
    End If
    
End Sub
Private Sub txtCustomerIdLines_LostFocus(Index As Integer)
    
    If Trim$(txtCustomerIdLines(Index)) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtCustomerIdLines(Index).Text
        Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
            txtCustomerNameLines(Index).Text = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
    End If
    
End Sub


Private Sub txtLRNum_LostFocus()
    
    Dim i As Integer
    Dim dSecurity_Qty As Double
    Dim dRate As Double
    
    If Trim$(txtLRNum) <> "" And IsNumeric(txtLRNum) Then
        
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Trim(txtLRNum.Text)
        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RecordCount > 0 Then
            txtVesselName.Text = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
            If dsVESSEL_PROFILE.fields("VESSEL_BILLING").Value = "L" Then
                txtServiceUnit(0).Text = "FEET"
                txtServiceUnit(1).Text = "FEET"
                txtServiceUnit(2).Text = "FEET"
                txtServiceUnit(3).Text = "FEET"
                If IsNull(dsVESSEL_PROFILE.fields("VESSEL_LENGTH").Value) Then
                    txtQty(0).Text = 0
                    txtQty(1).Text = 0
                    txtQty(2).Text = 0
                    txtQty(3).Text = 0
                Else
                    txtQty(0).Text = dsVESSEL_PROFILE.fields("VESSEL_LENGTH").Value
                    txtQty(1).Text = dsVESSEL_PROFILE.fields("VESSEL_LENGTH").Value
                    txtQty(2).Text = dsVESSEL_PROFILE.fields("VESSEL_LENGTH").Value
                    txtQty(3).Text = dsVESSEL_PROFILE.fields("VESSEL_LENGTH").Value
                End If
            ElseIf dsVESSEL_PROFILE.fields("VESSEL_BILLING").Value = "N" Then
                txtServiceUnit(0).Text = "NRT"
                txtServiceUnit(1).Text = "NRT"
                txtServiceUnit(2).Text = "NRT"
                txtServiceUnit(3).Text = "NRT"
                If IsNull(dsVESSEL_PROFILE.fields("VESSEL_NRT").Value) Then
                    txtQty(0).Text = 0
                    txtQty(1).Text = 0
                    txtQty(2).Text = 0
                    txtQty(3).Text = 0
                Else
                    txtQty(0).Text = dsVESSEL_PROFILE.fields("VESSEL_NRT").Value
                    txtQty(1).Text = dsVESSEL_PROFILE.fields("VESSEL_NRT").Value
                    txtQty(2).Text = dsVESSEL_PROFILE.fields("VESSEL_NRT").Value
                    txtQty(3).Text = dsVESSEL_PROFILE.fields("VESSEL_NRT").Value
                End If
            End If
            
            gsSqlStmt = " SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID IN " _
                      & " (SELECT VESSEL_OPERATOR_ID FROM VOYAGE WHERE LR_NUM='" & Trim(txtLRNum) & "')"
            Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
                txtCustomerId(0) = dsCUSTOMER_PROFILE.fields("CUSTOMER_ID").Value
                txtCustomerId(1) = dsCUSTOMER_PROFILE.fields("CUSTOMER_ID").Value
                txtCustomerId(2) = dsCUSTOMER_PROFILE.fields("CUSTOMER_ID").Value
                txtCustomerId(3) = dsCUSTOMER_PROFILE.fields("CUSTOMER_ID").Value
                txtCustomerName(0) = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
                txtCustomerName(1) = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
                txtCustomerName(2) = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
                txtCustomerName(3) = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
                For i = 0 To 5
                    txtCustomerIdLines(i).Text = dsCUSTOMER_PROFILE.fields("CUSTOMER_ID").Value
                    txtCustomerNameLines(i) = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
                Next i
            End If
            
            
            gsSqlStmt = "SELECT * FROM VOYAGE WHERE LR_NUM = " & txtLRNum.Text
            Set dsVOYAGE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsVOYAGE.RecordCount > 0 Then
                txtServiceDate.Text = Format$(dsVOYAGE.fields("DATE_DEPARTED").Value, "MM/DD/YYYY")
                If Not IsNull(dsVOYAGE.fields("DATE_ARRIVED").Value) Then
                    txtDateArrived.Text = dsVOYAGE.fields("DATE_ARRIVED").Value
                End If
                If Not IsNull(dsVOYAGE.fields("DATE_DEPARTED").Value) Then
                    txtDateDeparted.Text = dsVOYAGE.fields("DATE_DEPARTED").Value
                End If
                If Not IsNull(dsVOYAGE.fields("WORKING_DAYS").Value) Then
                    txtDays(0).Text = dsVOYAGE.fields("WORKING_DAYS").Value
                    txtDays(1).Text = dsVOYAGE.fields("WORKING_DAYS").Value
                End If
                If Not IsNull(dsVOYAGE.fields("IDLE_DAYS").Value) Then
                    txtDays(2) = dsVOYAGE.fields("IDLE_DAYS").Value
                    txtDays(3) = dsVOYAGE.fields("IDLE_DAYS").Value
                End If
                
            Else
                MsgBox "Vessel Voyage information does not exist. Days will not default.", vbExclamation, "Vessel"
            End If
            
            txtServiceDateLines(0) = Format(txtDateArrived, "MM/DD/YYYY")
            txtServiceDateLines(1) = Format(txtDateDeparted, "MM/DD/YYYY")
            
        
            'Fill the wharfage section
            grdWharfage.RemoveAll
            gsSqlStmt = " SELECT DISTINCT RECIPIENT_ID, COMMODITY_CODE FROM CARGO_MANIFEST WHERE " _
                      & " LR_NUM = '" & Trim(txtLRNum.Text) & "' ORDER BY RECIPIENT_ID "
                      
            Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RecordCount > 0 Then
                For iRec = 1 To dsCARGO_MANIFEST.RecordCount
                    
                    gsSqlStmt = "select customer_name from customer_profile where customer_id='" & dsCARGO_MANIFEST.fields("RECIPIENT_ID").Value & "'"
                    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                
                    grdWharfage.AddItem Chr(9) + Chr(9) + "-1" + Chr(9) + dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value + _
                                        Chr(9) + Chr(9) + Chr(9) + Chr(9) + dsCARGO_MANIFEST.fields("RECIPIENT_ID").Value + _
                                        Chr(9) + dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
                                        
                    grdWharfage.Refresh
                    dsCARGO_MANIFEST.MoveNext
                Next iRec
                
                
            Else
                MsgBox "Cargo Manifest information does not exist. Days will not default.", vbExclamation, "Vessel"
            End If
        Else
            MsgBox "Vessel does not exist.", vbExclamation, "Vessel"
        End If
    End If
    
End Sub
Public Sub ClearScreen()
    
    Dim i As Integer
    
    txtLRNum.Text = ""
    txtVesselName.Text = ""
    txtServiceDate.Text = Format$(Now, "mm/dd/yyyy")
    txtDateArrived.Text = ""
    txtDateDeparted.Text = ""
    
    'Clear Dockage
    For i = 0 To 1
        txtServiceCode(i).Text = ""
        txtServiceName(i).Text = ""
        txtDescription(i).Text = ""
        chkCareOf(i).Value = 1
        txtDays(i).Text = ""
        txtServiceUnit(i).Text = ""
        txtServiceRate(i).Text = ""
        txtQty(i) = ""
        txtMinimumCharge(i).Text = ""
        txtServiceAmount(i).Text = ""
        txtCustomerId(i).Text = ""
        txtCustomerName(i).Text = ""
    Next

    'Clear Lines
    For i = 0 To 5
        txtServiceCodeLines(i).Text = ""
        txtServiceNameLines(i).Text = ""
        txtDescriptionLines(i).Text = ""
        chkCareOfLines(i).Value = 1
        txtServiceDateLines(i).Text = ""
        txtServiceStartLines(i).Text = ""
        txtServiceStopLines(i).Text = ""
        txtServiceQty2Lines(i).Text = ""
        txtServiceRateLines(i).Text = ""
        txtServiceAmountLines(i).Text = ""
        txtCustomerIdLines(i).Text = ""
        txtCustomerNameLines(i).Text = ""
        txtPage(i) = ""
    Next

    'Clear Wharfage
    grdWharfage.RemoveAll
    
    'Clear Security Options
    grdSecurity.RemoveAll
    txtSecurityDesc.Text = ""
    txtSecurityDockBill.Text = ""
    txtSecurityCust.Text = ""
    txtSecurityCustName.Text = ""
    txtSecurityService.Text = ""
    
    
End Sub


Private Sub txtServiceCode_LostFocus(Index As Integer)
    
    If Trim$(txtServiceCode(Index)) <> "" And IsNumeric(txtServiceCode(Index)) Then
    
'        gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE UPPER(SERVICE_NAME) LIKE '%DOCK%'"
'        If Index = 2 Or Index = 3 Then
'            gsSqlStmt = gsSqlStmt & " AND UPPER(SERVICE_NAME) LIKE '%IDLE%'"
'        Else
'            gsSqlStmt = gsSqlStmt & " AND UPPER(SERVICE_NAME) NOT LIKE '%IDLE%'"
'        End If
'        gsSqlStmt = gsSqlStmt & " ORDER BY SERVICE_CODE"

        gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " & txtServiceCode(Index)
        
        Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RecordCount > 0 Then
            txtServiceName(Index).Text = dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value
            
            gsSqlStmt = "SELECT * FROM VESSEL_RATE WHERE SERVICE_CODE = " & txtServiceCode(Index)
            Set dsVESSEL_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsVESSEL_RATE.RecordCount > 0 Then
                txtServiceRate(Index).Text = dsVESSEL_RATE.fields("SERVICE_RATE").Value
                txtMinimumCharge(Index).Text = dsVESSEL_RATE.fields("MINIMUM_CHARGE").Value
            Else
                MsgBox "Vessel Rate information does not exist.", vbExclamation, "Vessel"
            End If
        Else
            MsgBox "Service not valid.", vbExclamation, "Service"
        End If
    End If
    
End Sub
Private Sub txtServiceCodeLines_LostFocus(Index As Integer)
    
    If Trim$(txtServiceCodeLines(Index)) <> "" And IsNumeric(txtServiceCodeLines(Index)) Then
        gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " & txtServiceCodeLines(Index).Text
        Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RecordCount > 0 Then
            txtServiceNameLines(Index).Text = dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value
            
            gsSqlStmt = "SELECT * FROM VESSEL_RATE WHERE SERVICE_CODE = " & txtServiceCodeLines(Index)
            Set dsVESSEL_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsVESSEL_RATE.RecordCount > 0 Then
                txtServiceRateLines(Index).Text = dsVESSEL_RATE.fields("SERVICE_RATE").Value
            Else
                MsgBox "Vessel Rate information does not exist.", vbExclamation, "Vessel"
            End If
        Else
            MsgBox "Service does not exist.", vbExclamation, "Service"
        End If
    End If
    
End Sub

Private Sub txtServiceDateLines_GotFocus(Index As Integer)
    
    'Default only if not an existing record
    
    If Trim$(txtServiceAmountLines(Index).Text) = "" Then
        If Index <> 0 Then txtServiceDateLines(Index).Text = Trim(txtServiceDate.Text)
    End If
    
End Sub
Private Sub txtDays_LostFocus(Index As Integer)
    
    Dim dAmt As Double
    
    If txtDays(Index) = "" Or txtServiceCode(Index) = "" Then Exit Sub
    
    If dsVESSEL_RATE.fields("UNIT1").Value = "DAY" Then
        dAmt = Val(txtDays(Index)) * 1# * Val(txtServiceRate(Index))
        txtServiceAmount(Index) = Format$(dAmt, "0.00")
    Else
        If Trim$(txtDays(Index)) <> "" Then
            dAmt = Val(txtDays(Index)) * Val(txtQty(Index).Text) * Val(txtServiceRate(Index))
            
            If dAmt < Val(txtMinimumCharge(Index)) * txtDays(Index) Then
                txtServiceAmount(Index) = Format$(Val(txtMinimumCharge(Index)) * txtDays(Index), "0.00")
            Else
                txtServiceAmount(Index) = Format$(dAmt, "0.00")
            End If
        End If
    End If
    
End Sub
Private Sub txtServiceStartLines_LostFocus(Index As Integer)
    
    If Trim$(txtServiceStartLines(Index).Text) <> "" Then
        If Not IsValidTime(txtServiceStartLines(Index).Text) Then
            MsgBox "Start Time is not valid. Please enter in HH:MM (24 Hour) format.", vbExclamation, "Invalid Start Hour"
            txtServiceStartLines(Index).SetFocus
            Exit Sub
        Else
            Call CalcServiceAmountLines(Index)
        End If
    End If
    
End Sub
Private Sub txtServiceStopLines_LostFocus(Index As Integer)
    
    If Trim$(txtServiceStopLines(Index).Text) <> "" Then
        If Not IsValidTime(txtServiceStopLines(Index).Text) Then
            MsgBox "Stop Time is not valid. Please enter in HH:MM (24 Hour) format.", vbExclamation, "Invalid Start Hour"
            txtServiceStopLines(Index).SetFocus
            Exit Sub
        Else
            Call CalcServiceAmountLines(Index)
        End If
    End If
End Sub

Private Sub cmdPopulateSec_Click()
'Private Sub cmbSecurityType_LostFocus()
    
    Dim SelectedSecurity As Integer
    Dim sTableName As String
    Dim sBillFrom As String
    Dim dRate As Double
    Dim iDockLine As Integer
    Dim sSecurityDesc As String
    Dim bValidConvert As Boolean
    Dim iSecurityLooper As Integer
    
    ' wharfage exclusive
    Dim WharfageLoopCounter As Integer
    Dim dSecurity_Qty As Double
    
    ' docking exclusive
    Dim sBillServiceCode As Integer
    Dim sChargeType As String
    
    ' idle exclusive
    Dim IdleLoopCounter As Integer

    
    SelectedSecurity = Left$(cmbSecurityType.Text, 1)
    sSecurityDesc = Mid$(cmbSecurityType.Text, 5)
    
    
    If SelectedSecurity = 0 Then
        cmdSave.Enabled = False
        Exit Sub
    Else
        cmdSave.Enabled = True
    End If
    
    gsSqlStmt = "SELECT * FROM SECURITY_CHARGE_TYPE WHERE SELECTION_NO = '" & SelectedSecurity & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    sTableName = dsSHORT_TERM_DATA.fields("TABLE_REF").Value
    sBillFrom = dsSHORT_TERM_DATA.fields("BILL_FROM").Value
    
    If sBillFrom = "WHARFAGE" Then ' use the bottom grid
'        grdSecurity.RemoveAll
        grdWharfage.MoveFirst
        For WharfageLoopCounter = 0 To grdWharfage.Rows - 1 ' for each wharfage row
            If grdWharfage.Columns(3).Value <> "" And grdWharfage.Columns(7).Value <> "" Then ' And grdWharfage.Columns(0).Value <> ""
                
                 gsSqlStmt = " SELECT SUM(QTY_EXPECTED) QTY1_SUM, QTY1_UNIT, SUM(QTY2_EXPECTED) QTY2_SUM, QTY2_UNIT," _
                        & " SUM(CARGO_WEIGHT) WEIGHT_SUM, CARGO_WEIGHT_UNIT, RATE_CHARGE, UNIT, SERVICE_CODE, CM.IMPEX" _
                        & " FROM CARGO_MANIFEST CM, SECURITY_CHARGE_RATES SCR WHERE " _
                        & " CM.LR_NUM = '" & Trim(txtLRNum.Text) & "' AND CM.COMMODITY_CODE = SCR.COMMODITY_CODE(+) AND SCR.IMP_EXP(+) = CM.IMPEX" _
                        & " AND CM.COMMODITY_CODE = '" & grdWharfage.Columns(3).Value & "' AND CM.RECIPIENT_ID = '" & grdWharfage.Columns(7).Value & "'" _
                        & " GROUP BY QTY1_UNIT, QTY2_UNIT, CARGO_WEIGHT_UNIT, RATE_CHARGE, UNIT, SERVICE_CODE, CM.IMPEX"
                Set dsSECURITY_LINES = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsSECURITY_LINES.RecordCount > 0 Then
                    dsSECURITY_LINES.MoveFirst
                    For iSecurityLooper = 1 To dsSECURITY_LINES.RecordCount
                         ' populate the line with...
                        bValidConvert = False
                        dSecurity_Qty = 0
                        If Not IsNull(dsSECURITY_LINES.fields("RATE_CHARGE").Value) And dsSECURITY_LINES.fields("RATE_CHARGE").Value <> "" Then
                            dRate = dsSECURITY_LINES.fields("RATE_CHARGE").Value
                            If UCase(Trim(dsSECURITY_LINES.fields("QTY1_UNIT").Value)) = UCase(Trim(dsSECURITY_LINES.fields("UNIT").Value)) Then ' measure by QTY1
                                dSecurity_Qty = dsSECURITY_LINES.fields("QTY1_SUM").Value
                                bValidConvert = True
                            ElseIf UCase(Trim(dsSECURITY_LINES.fields("QTY2_UNIT").Value)) = UCase(Trim(dsSECURITY_LINES.fields("UNIT").Value)) Then 'measure by QTY2
                                dSecurity_Qty = dsSECURITY_LINES.fields("QTY2_SUM").Value
                                bValidConvert = True
                            Else ' check to see if we can measure by weight
                                gsSqlStmt = "SELECT CONVERSION_FACTOR FROM UNIT_CONVERSION WHERE PRIMARY_UOM = '" _
                                        & UCase(Trim(dsSECURITY_LINES.fields("CARGO_WEIGHT_UNIT").Value)) & "' AND SECONDARY_UOM = '" _
                                        & UCase(Trim(dsSECURITY_LINES.fields("UNIT").Value)) & "'"
                                Set dsWEIGHT_CONVERSION = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                                If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RecordCount > 0 And Not IsNull(dsWEIGHT_CONVERSION.fields("CONVERSION_FACTOR").Value) Then
                                    dSecurity_Qty = Round(dsSECURITY_LINES.fields("WEIGHT_SUM").Value * dsWEIGHT_CONVERSION.fields("CONVERSION_FACTOR").Value, 2)
                                    bValidConvert = True
                                End If
                            End If
                        End If
                        
    
                        If dSecurity_Qty <> 0 Then 'if it is 0, then there is no data to enter, so skip this...
    
                            gsSqlStmt = "select customer_name from customer_profile where customer_id='" & grdWharfage.Columns(7).Value & "'"
                            Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    
                            grdSecurity.AddItem dsSECURITY_LINES.fields("SERVICE_CODE").Value + Chr(9) + "Security Charge" + Chr(9) + _
                                                "-1" + Chr(9) + grdWharfage.Columns(3).Value + Chr(9) + _
                                                CStr(dSecurity_Qty) + Chr(9) + CStr(dRate) + Chr(9) + CStr(Round(dSecurity_Qty * dRate, 2)) + _
                                                Chr(9) + grdWharfage.Columns(7).Value + _
                                                Chr(9) + grdWharfage.Columns(8).Value + Chr(9) + Chr(9) + "WHARFAGE"
    
                            grdSecurity.Refresh
                        Else
                            If bValidConvert = False Then
                                ' figure out if this is a bad IMPEXP value
                                 gsSqlStmt = "SELECT COUNT(*) THE_COUNT" _
                                        & " FROM CARGO_MANIFEST CM, SECURITY_CHARGE_RATES SCR WHERE " _
                                        & " CM.LR_NUM = '" & Trim(txtLRNum.Text) & "' AND CM.COMMODITY_CODE = SCR.COMMODITY_CODE(+) AND SCR.IMP_EXP != CM.IMPEX" _
                                        & " AND CM.COMMODITY_CODE = '" & grdWharfage.Columns(3).Value & "' AND CM.RECIPIENT_ID = '" & grdWharfage.Columns(7).Value & "'"
                                Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                                If dsSHORT_TERM_DATA.fields("THE_COUNT").Value > 0 Then
                                    MsgBox "For Commodity " & grdWharfage.Columns(3).Value & " On Wharfage Line# " & (grdWharfage.Row + 1) & ", The Import/Export value of the cargo is `" & dsSECURITY_LINES.fields("IMPEX").Value & "`, which does not have a security rate for this Customer/UOM combination."
                                Else
                                    MsgBox "For Commodity " & grdWharfage.Columns(3).Value & " the Security measurement is " & dsSECURITY_LINES.fields("UNIT").Value & ", but the available units of measure for this cargo are " & dsSECURITY_LINES.fields("QTY1_UNIT").Value & ", " & dsSECURITY_LINES.fields("QTY2_UNIT").Value & ", and " & dsSECURITY_LINES.fields("CARGO_WEIGHT_UNIT").Value & ".  Please close Vessel Bill, add/fix the cargo in question, and re-run."
                                End If
                                
                                cmdSave.Enabled = False
                            Else
                                ' was just a normal, 0 quantity thing, do nothing
                            End If
                        End If
                        
                        dsSECURITY_LINES.MoveNext
                    Next iSecurityLooper
                End If
            End If
            grdWharfage.MoveNext
        Next WharfageLoopCounter
    ElseIf sBillFrom = "IDLE" Then ' use the bottom grid
        For IdleLoopCounter = 2 To 3 ' for each idle row, which is defined as row indexes 2 and 3 from above
            If txtServiceCode(IdleLoopCounter) <> "" Then ' do this only if an idle line exists
                gsSqlStmt = "SELECT * FROM " & sTableName & " WHERE SELECTION_NO = '" & SelectedSecurity & "'"
                Set dsSECURITY_LINES = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                
                dRate = dsSECURITY_LINES.fields("CHARGE_RATE").Value
                sChargeType = dsSECURITY_LINES.fields("CHARGE_TYPE").Value
                sBillServiceCode = dsSECURITY_LINES.fields("BILLING_SERVICE_CODE").Value
                
                ' Adam, Feb 2014
                ' this is terrible coding, but right now all Idle is a % rate, and I am under heavy orders to finish this ASAHP
                ' so im not checking that DB field, and jsut assuming it to be %, and that the QTY to bill is always a monetary amount
                Dim qtyvalue As Double
                qtyvalue = txtServiceAmount(IdleLoopCounter)
                
                grdSecurity.AddItem dsSECURITY_LINES.fields("BILLING_SERVICE_CODE").Value + Chr(9) + "Security Charge" + Chr(9) + _
                                    "-1" + Chr(9) + "" + Chr(9) + _
                                    CStr(qtyvalue) + Chr(9) + CStr(dRate) + Chr(9) + CStr(Round(qtyvalue * dRate, 2)) + _
                                    Chr(9) + txtCustomerId(IdleLoopCounter) + _
                                    Chr(9) + txtCustomerName(IdleLoopCounter) + Chr(9) + Chr(9) + "DOCKAGE"

                grdSecurity.Refresh
            End If
        Next IdleLoopCounter
    ElseIf sBillFrom = "DOCKING" Then ' add a single charge per-ship
        txtSecurityDesc.Text = ""
        txtSecurityDockBill.Text = ""
        txtSecurityCust.Text = ""
        txtSecurityCustName.Text = ""
        txtSecurityService.Text = ""
        
        gsSqlStmt = "SELECT * FROM " & sTableName & " WHERE SELECTION_NO = '" & SelectedSecurity & "'"
        Set dsSECURITY_LINES = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        
        dRate = dsSECURITY_LINES.fields("CHARGE_RATE").Value
        sChargeType = dsSECURITY_LINES.fields("CHARGE_TYPE").Value
        sBillServiceCode = dsSECURITY_LINES.fields("BILLING_SERVICE_CODE").Value
        
        txtSecurityService.Text = sBillServiceCode
        txtSecurityDesc.Text = sSecurityDesc
       
        If txtServiceCode(0).Text <> "" Then ' we want to grab dockage data from above fields, but we want to make sure line isn't blank
            iDockLine = 0
        ElseIf txtServiceCode(1).Text <> "" Then
            iDockLine = 1
        Else
            MsgBox "Cannot add Dockage Security Charge for a vessel with no normal dockage fee"
            cmdSave.Enabled = False
            Exit Sub
        End If
        
        If sChargeType = "FLAT RATE" Then
            txtSecurityDockBill = dRate
            txtSecurityCust.Text = txtCustomerId(iDockLine).Text
            txtSecurityCustName.Text = txtCustomerName(iDockLine).Text
        ElseIf sChargeType = "PERCENT" Then
            If (txtServiceCode(0).Text <> "1114" And txtServiceCode(0) <> "1122" And txtServiceCode(0) <> "1124" And txtServiceCode(0) <> "1126") Then
                MsgBox "Cannot bill Idle-Only Time for vessel with non-idle hours (Note:  please use the first dockage line for idle charges)"
                Exit Sub
            Else
                Dim firstboxvalue As Double
                Dim secondboxvalue As Double
                If (txtServiceCode(0).Text = "1111" Or txtServiceCode(0).Text = "1114" Or txtServiceCode(0).Text = "1116" Or txtServiceCode(0).Text = "1122" Or txtServiceCode(0).Text = "1124" Or txtServiceCode(0).Text = "1126" Or txtServiceCode(0).Text = "1141" Or txtServiceCode(0).Text = "1142" Or txtServiceCode(0).Text = "1143" Or txtServiceCode(0).Text = "1144" Or txtServiceCode(0).Text = "1145") Then
                    firstboxvalue = Val("" + txtServiceAmount(0))
                Else
                    firstboxvalue = 0
                End If
                If (txtServiceCode(1).Text = "1111" Or txtServiceCode(1).Text = "1114" Or txtServiceCode(1).Text = "1116" Or txtServiceCode(1).Text = "1122" Or txtServiceCode(1).Text = "1124" Or txtServiceCode(1).Text = "1126" Or txtServiceCode(1).Text = "1141" Or txtServiceCode(1).Text = "1142" Or txtServiceCode(1).Text = "1143" Or txtServiceCode(1).Text = "1144" Or txtServiceCode(1).Text = "1145") Then
                    secondboxvalue = Val("" + txtServiceAmount(1))
                Else
                    secondboxvalue = 0
                End If
                txtSecurityDockBill = Round((dRate * (firstboxvalue + secondboxvalue)), 2)
                txtSecurityCust.Text = txtCustomerId(iDockLine).Text
                txtSecurityCustName.Text = txtCustomerName(iDockLine).Text
            End If
        Else ' does not exist for now
            MsgBox "No valid DB entry; contact TS for more information"
            Exit Sub
        End If
    End If
    
    
End Sub

Private Sub cmdClrWharfage_Click()
    grdSecurity.RemoveAll
End Sub


Private Sub cmdClearVessel_Click()
    txtSecurityDesc.Text = ""
    txtSecurityDockBill.Text = ""
    txtSecurityCust.Text = ""
    txtSecurityCustName.Text = ""
    txtSecurityService.Text = ""
End Sub

