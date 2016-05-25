VERSION 5.00
Object = "{BDC217C8-ED16-11CD-956C-0000C04E4C0A}#1.1#0"; "TABCTL32.OCX"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmBniRates 
   AutoRedraw      =   -1  'True
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "BNI RATES"
   ClientHeight    =   8175
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   13290
   BeginProperty Font 
      Name            =   "Times New Roman"
      Size            =   9.75
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   ScaleHeight     =   8175
   ScaleWidth      =   13290
   StartUpPosition =   3  'Windows Default
   Begin TabDlg.SSTab SSTab1 
      Height          =   8085
      Left            =   105
      TabIndex        =   0
      Top             =   0
      Width           =   13110
      _ExtentX        =   23125
      _ExtentY        =   14261
      _Version        =   393216
      Tabs            =   11
      TabsPerRow      =   5
      TabHeight       =   520
      BackColor       =   16777152
      ForeColor       =   16711680
      TabCaption(0)   =   "Dock Rcpt Handling"
      TabPicture(0)   =   "BniRate.frx":0000
      Tab(0).ControlEnabled=   -1  'True
      Tab(0).Control(0)=   "Label4"
      Tab(0).Control(0).Enabled=   0   'False
      Tab(0).Control(1)=   "Label3"
      Tab(0).Control(1).Enabled=   0   'False
      Tab(0).Control(2)=   "Label2"
      Tab(0).Control(2).Enabled=   0   'False
      Tab(0).Control(3)=   "Label1"
      Tab(0).Control(3).Enabled=   0   'False
      Tab(0).Control(4)=   "Label49"
      Tab(0).Control(4).Enabled=   0   'False
      Tab(0).Control(5)=   "grdData(0)"
      Tab(0).Control(5).Enabled=   0   'False
      Tab(0).Control(6)=   "txtUnit(0)"
      Tab(0).Control(6).Enabled=   0   'False
      Tab(0).Control(7)=   "txtCommodity(0)"
      Tab(0).Control(7).Enabled=   0   'False
      Tab(0).Control(8)=   "txtRate(0)"
      Tab(0).Control(8).Enabled=   0   'False
      Tab(0).Control(9)=   "txtService(0)"
      Tab(0).Control(9).Enabled=   0   'False
      Tab(0).Control(10)=   "cmdDelete(0)"
      Tab(0).Control(10).Enabled=   0   'False
      Tab(0).Control(11)=   "cmdPrint(0)"
      Tab(0).Control(11).Enabled=   0   'False
      Tab(0).Control(12)=   "cmdSave(0)"
      Tab(0).Control(12).Enabled=   0   'False
      Tab(0).Control(13)=   "cmdExit(0)"
      Tab(0).Control(13).Enabled=   0   'False
      Tab(0).Control(14)=   "cmdClear(0)"
      Tab(0).Control(14).Enabled=   0   'False
      Tab(0).Control(15)=   "txtCustomer(0)"
      Tab(0).Control(15).Enabled=   0   'False
      Tab(0).ControlCount=   16
      TabCaption(1)   =   "Equipment  Rate"
      TabPicture(1)   =   "BniRate.frx":001C
      Tab(1).ControlEnabled=   0   'False
      Tab(1).Control(0)=   "cmdClear(1)"
      Tab(1).Control(1)=   "cmdExit(1)"
      Tab(1).Control(2)=   "txtEquipRateType"
      Tab(1).Control(3)=   "txtRate(1)"
      Tab(1).Control(4)=   "txtEquipType"
      Tab(1).Control(5)=   "txtCommodity(1)"
      Tab(1).Control(6)=   "txtService(1)"
      Tab(1).Control(7)=   "cmdDelete(1)"
      Tab(1).Control(8)=   "cmdPrint(1)"
      Tab(1).Control(9)=   "cmdSave(1)"
      Tab(1).Control(10)=   "grdData(1)"
      Tab(1).Control(11)=   "Label9"
      Tab(1).Control(12)=   "Label8"
      Tab(1).Control(13)=   "Label7"
      Tab(1).Control(14)=   "Label6"
      Tab(1).Control(15)=   "Label5"
      Tab(1).ControlCount=   16
      TabCaption(2)   =   "Equipment Rate Type"
      TabPicture(2)   =   "BniRate.frx":0038
      Tab(2).ControlEnabled=   0   'False
      Tab(2).Control(0)=   "cmdClear(2)"
      Tab(2).Control(1)=   "cmdExit(2)"
      Tab(2).Control(2)=   "txtEquipRTypeDes"
      Tab(2).Control(3)=   "txtEquipRType"
      Tab(2).Control(4)=   "cmdDelete(2)"
      Tab(2).Control(5)=   "cmdPrint(2)"
      Tab(2).Control(6)=   "cmdSave(2)"
      Tab(2).Control(7)=   "grdData(2)"
      Tab(2).Control(8)=   "Label11"
      Tab(2).Control(9)=   "Label10"
      Tab(2).ControlCount=   10
      TabCaption(3)   =   "Free Time Rate"
      TabPicture(3)   =   "BniRate.frx":0054
      Tab(3).ControlEnabled=   0   'False
      Tab(3).Control(0)=   "cmdClear(3)"
      Tab(3).Control(1)=   "cmdExit(3)"
      Tab(3).Control(2)=   "txtFreeDays"
      Tab(3).Control(3)=   "txtCommodity(2)"
      Tab(3).Control(4)=   "cmdDelete(3)"
      Tab(3).Control(5)=   "cmdPrint(3)"
      Tab(3).Control(6)=   "cmdSave(3)"
      Tab(3).Control(7)=   "grdData(3)"
      Tab(3).Control(8)=   "Label13"
      Tab(3).Control(9)=   "Label12"
      Tab(3).ControlCount=   10
      TabCaption(4)   =   "Handling Rate"
      TabPicture(4)   =   "BniRate.frx":0070
      Tab(4).ControlEnabled=   0   'False
      Tab(4).Control(0)=   "cmdClear(4)"
      Tab(4).Control(1)=   "cmdExit(4)"
      Tab(4).Control(2)=   "txtRate(2)"
      Tab(4).Control(3)=   "txtUnit(1)"
      Tab(4).Control(4)=   "txtCommodity(3)"
      Tab(4).Control(5)=   "txtLocation"
      Tab(4).Control(6)=   "txtService(2)"
      Tab(4).Control(7)=   "cmdDelete(4)"
      Tab(4).Control(8)=   "cmdPrint(4)"
      Tab(4).Control(9)=   "cmdSave(4)"
      Tab(4).Control(10)=   "grdData(4)"
      Tab(4).Control(11)=   "Label18"
      Tab(4).Control(12)=   "Label17"
      Tab(4).Control(13)=   "Label16"
      Tab(4).Control(14)=   "Label15"
      Tab(4).Control(15)=   "Label14"
      Tab(4).ControlCount=   16
      TabCaption(5)   =   "Labor Rate"
      TabPicture(5)   =   "BniRate.frx":008C
      Tab(5).ControlEnabled=   0   'False
      Tab(5).Control(0)=   "cmdClear(5)"
      Tab(5).Control(1)=   "cmdExit(5)"
      Tab(5).Control(2)=   "txtLabRateType"
      Tab(5).Control(3)=   "txtRate(3)"
      Tab(5).Control(4)=   "txtLaborType"
      Tab(5).Control(5)=   "cmdDelete(5)"
      Tab(5).Control(6)=   "cmdPrint(5)"
      Tab(5).Control(7)=   "cmdSave(5)"
      Tab(5).Control(8)=   "grdData(5)"
      Tab(5).Control(9)=   "Label21"
      Tab(5).Control(10)=   "Label20"
      Tab(5).Control(11)=   "Label19"
      Tab(5).ControlCount=   12
      TabCaption(6)   =   "Labor Rate Type "
      TabPicture(6)   =   "BniRate.frx":00A8
      Tab(6).ControlEnabled=   0   'False
      Tab(6).Control(0)=   "cmdClear(6)"
      Tab(6).Control(1)=   "cmdExit(6)"
      Tab(6).Control(2)=   "txtLabRateDesc"
      Tab(6).Control(3)=   "txtLaborRateType"
      Tab(6).Control(4)=   "cmdDelete(6)"
      Tab(6).Control(5)=   "cmdPrint(6)"
      Tab(6).Control(6)=   "cmdSave(6)"
      Tab(6).Control(7)=   "grdData(6)"
      Tab(6).Control(8)=   "Label23"
      Tab(6).Control(9)=   "Label22"
      Tab(6).ControlCount=   10
      TabCaption(7)   =   "Storage Rate"
      TabPicture(7)   =   "BniRate.frx":00C4
      Tab(7).ControlEnabled=   0   'False
      Tab(7).Control(0)=   "cmdClear(7)"
      Tab(7).Control(1)=   "cmdExit(7)"
      Tab(7).Control(2)=   "txtEnd"
      Tab(7).Control(3)=   "txtStart"
      Tab(7).Control(4)=   "txtDurUnit"
      Tab(7).Control(5)=   "txtUnit(2)"
      Tab(7).Control(6)=   "txtCommodity(4)"
      Tab(7).Control(7)=   "txtDuration"
      Tab(7).Control(8)=   "txtRate(4)"
      Tab(7).Control(9)=   "txtService(3)"
      Tab(7).Control(10)=   "cmdDelete(7)"
      Tab(7).Control(11)=   "cmdPrint(7)"
      Tab(7).Control(12)=   "cmdSave(7)"
      Tab(7).Control(13)=   "grdData(7)"
      Tab(7).Control(14)=   "Label31"
      Tab(7).Control(15)=   "Label30"
      Tab(7).Control(16)=   "Label29"
      Tab(7).Control(17)=   "Label28"
      Tab(7).Control(18)=   "Label27"
      Tab(7).Control(19)=   "Label26"
      Tab(7).Control(20)=   "Label25"
      Tab(7).Control(21)=   "Label24"
      Tab(7).ControlCount=   22
      TabCaption(8)   =   "Truck Handling Rate"
      TabPicture(8)   =   "BniRate.frx":00E0
      Tab(8).ControlEnabled=   0   'False
      Tab(8).Control(0)=   "cmdClear(8)"
      Tab(8).Control(1)=   "cmdExit(8)"
      Tab(8).Control(2)=   "txtService(4)"
      Tab(8).Control(3)=   "txtRate(5)"
      Tab(8).Control(4)=   "txtCommodity(5)"
      Tab(8).Control(5)=   "txtUnit(3)"
      Tab(8).Control(6)=   "cmdDelete(8)"
      Tab(8).Control(7)=   "cmdPrint(8)"
      Tab(8).Control(8)=   "cmdSave(8)"
      Tab(8).Control(9)=   "grdData(8)"
      Tab(8).Control(10)=   "Label35(0)"
      Tab(8).Control(11)=   "Label34(0)"
      Tab(8).Control(12)=   "Label33(0)"
      Tab(8).Control(13)=   "Label32(0)"
      Tab(8).ControlCount=   14
      TabCaption(9)   =   "Vessel  Rate"
      TabPicture(9)   =   "BniRate.frx":00FC
      Tab(9).ControlEnabled=   0   'False
      Tab(9).Control(0)=   "cmdSave(9)"
      Tab(9).Control(1)=   "cmdPrint(9)"
      Tab(9).Control(2)=   "cmdDelete(9)"
      Tab(9).Control(3)=   "cmdExit(9)"
      Tab(9).Control(4)=   "cmdClear(9)"
      Tab(9).Control(5)=   "txtMinCUnit"
      Tab(9).Control(6)=   "txtUnit2"
      Tab(9).Control(7)=   "txtMinCharge"
      Tab(9).Control(8)=   "txtUnit1"
      Tab(9).Control(9)=   "txtRate(6)"
      Tab(9).Control(10)=   "txtCommodity(6)"
      Tab(9).Control(11)=   "txtService(5)"
      Tab(9).Control(12)=   "grdData(9)"
      Tab(9).Control(13)=   "Label42"
      Tab(9).Control(14)=   "Label41"
      Tab(9).Control(15)=   "Label40"
      Tab(9).Control(16)=   "Label39"
      Tab(9).Control(17)=   "Label38"
      Tab(9).Control(18)=   "Label37"
      Tab(9).Control(19)=   "Label36"
      Tab(9).ControlCount=   20
      TabCaption(10)  =   "Advance Rates"
      TabPicture(10)  =   "BniRate.frx":0118
      Tab(10).ControlEnabled=   0   'False
      Tab(10).Control(0)=   "Label35(1)"
      Tab(10).Control(1)=   "Label34(1)"
      Tab(10).Control(2)=   "Label33(1)"
      Tab(10).Control(3)=   "Label32(1)"
      Tab(10).Control(4)=   "Label43"
      Tab(10).Control(5)=   "Label44"
      Tab(10).Control(6)=   "Label45"
      Tab(10).Control(7)=   "Label46"
      Tab(10).Control(8)=   "Label47"
      Tab(10).Control(9)=   "Label48"
      Tab(10).Control(10)=   "grdData(10)"
      Tab(10).Control(11)=   "txtService(6)"
      Tab(10).Control(12)=   "txtRate(7)"
      Tab(10).Control(13)=   "txtCommodity(7)"
      Tab(10).Control(14)=   "txtUnit(4)"
      Tab(10).Control(15)=   "cmdSave(10)"
      Tab(10).Control(16)=   "cmdPrint(10)"
      Tab(10).Control(17)=   "cmdDelete(10)"
      Tab(10).Control(18)=   "cmdExit(10)"
      Tab(10).Control(19)=   "cmdClear(10)"
      Tab(10).Control(20)=   "txtCust"
      Tab(10).Control(21)=   "txtThreshold"
      Tab(10).Control(22)=   "txtDiscount"
      Tab(10).Control(23)=   "cboDiscountType"
      Tab(10).Control(24)=   "cboDiscountUnit"
      Tab(10).Control(25)=   "txtLoc"
      Tab(10).ControlCount=   26
      Begin VB.TextBox txtCustomer 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   0
         Left            =   11040
         TabIndex        =   172
         Top             =   1560
         Width           =   975
      End
      Begin VB.TextBox txtLoc 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -69750
         TabIndex        =   170
         Top             =   2100
         Width           =   1380
      End
      Begin VB.ComboBox cboDiscountUnit 
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   345
         ItemData        =   "BniRate.frx":0134
         Left            =   -66375
         List            =   "BniRate.frx":0141
         TabIndex        =   168
         Top             =   2070
         Width           =   1275
      End
      Begin VB.ComboBox cboDiscountType 
         Height          =   345
         ItemData        =   "BniRate.frx":0155
         Left            =   -63540
         List            =   "BniRate.frx":015F
         TabIndex        =   167
         Top             =   1170
         Width           =   1185
      End
      Begin VB.TextBox txtDiscount 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -66375
         TabIndex        =   166
         Top             =   1125
         Width           =   1095
      End
      Begin VB.TextBox txtThreshold 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -66375
         TabIndex        =   165
         Top             =   1620
         Width           =   1275
      End
      Begin VB.TextBox txtCust 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -73260
         TabIndex        =   160
         Top             =   1170
         Width           =   1365
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   10
         Left            =   -63645
         TabIndex        =   158
         Top             =   5775
         Width           =   1095
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   10
         Left            =   -63645
         TabIndex        =   157
         Top             =   6660
         Width           =   1095
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   10
         Left            =   -63645
         TabIndex        =   156
         Top             =   4020
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   10
         Left            =   -63645
         TabIndex        =   155
         Top             =   4905
         Width           =   1095
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
         Index           =   10
         Left            =   -63645
         TabIndex        =   154
         Top             =   3150
         Width           =   1095
      End
      Begin VB.TextBox txtUnit 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   4
         Left            =   -69750
         TabIndex        =   148
         Top             =   1620
         Width           =   600
      End
      Begin VB.TextBox txtCommodity 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   7
         Left            =   -69750
         TabIndex        =   147
         Top             =   1170
         Width           =   730
      End
      Begin VB.TextBox txtRate 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   7
         Left            =   -73260
         MaxLength       =   17
         TabIndex        =   146
         Top             =   2070
         Width           =   1580
      End
      Begin VB.TextBox txtService 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   6
         Left            =   -73260
         MaxLength       =   6
         TabIndex        =   145
         Top             =   1620
         Width           =   730
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
         Index           =   9
         Left            =   -64725
         TabIndex        =   144
         Top             =   3540
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   9
         Left            =   -64725
         TabIndex        =   143
         Top             =   5295
         Width           =   1095
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   9
         Left            =   -64725
         TabIndex        =   142
         Top             =   4410
         Width           =   1095
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   9
         Left            =   -64725
         TabIndex        =   141
         Top             =   7050
         Width           =   1095
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   9
         Left            =   -64725
         TabIndex        =   140
         Top             =   6165
         Width           =   1095
      End
      Begin VB.TextBox txtMinCUnit 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   -65730
         TabIndex        =   139
         Top             =   2100
         Width           =   855
      End
      Begin VB.TextBox txtUnit2 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   -65730
         TabIndex        =   138
         Top             =   1500
         Width           =   855
      End
      Begin VB.TextBox txtMinCharge 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   -68730
         TabIndex        =   137
         Top             =   2100
         Width           =   1095
      End
      Begin VB.TextBox txtUnit1 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   -68730
         TabIndex        =   136
         Top             =   1500
         Width           =   975
      End
      Begin VB.TextBox txtRate 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Index           =   6
         Left            =   -72570
         TabIndex        =   135
         Top             =   2700
         Width           =   1095
      End
      Begin VB.TextBox txtCommodity 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Index           =   6
         Left            =   -72570
         TabIndex        =   134
         Top             =   2100
         Width           =   975
      End
      Begin VB.TextBox txtService 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Index           =   5
         Left            =   -72570
         TabIndex        =   128
         Top             =   1500
         Width           =   975
      End
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   4575
         Index           =   9
         Left            =   -73485
         TabIndex        =   125
         Top             =   3300
         Width           =   8175
         _Version        =   196616
         DataMode        =   2
         Col.Count       =   7
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowDragDrop   =   0   'False
         RowHeight       =   503
         Columns.Count   =   7
         Columns(0).Width=   1614
         Columns(0).Caption=   "SERVICE"
         Columns(0).Name =   "SERVICE"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   2619
         Columns(1).Caption=   "COMMODITY"
         Columns(1).Name =   "COMMODITY"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   1799
         Columns(2).Caption=   "RATE"
         Columns(2).Name =   "RATE"
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   1931
         Columns(3).Caption=   "UNIT1"
         Columns(3).Name =   "UNIT1"
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         Columns(4).Width=   1614
         Columns(4).Caption=   "UNIT2"
         Columns(4).Name =   "UNIT2"
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         Columns(5).Width=   2408
         Columns(5).Caption=   "MIN CHARGE"
         Columns(5).Name =   "MIN CHARGE"
         Columns(5).DataField=   "Column 5"
         Columns(5).DataType=   8
         Columns(5).FieldLen=   256
         Columns(6).Width=   1349
         Columns(6).Caption=   "UNIT"
         Columns(6).Name =   "UNIT"
         Columns(6).DataField=   "Column 6"
         Columns(6).DataType=   8
         Columns(6).FieldLen=   256
         _ExtentX        =   14420
         _ExtentY        =   8070
         _StockProps     =   79
         BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   8
         Left            =   -65025
         TabIndex        =   124
         Top             =   5985
         Width           =   1095
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   8
         Left            =   -65025
         TabIndex        =   123
         Top             =   6870
         Width           =   1095
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   7
         Left            =   -64605
         TabIndex        =   122
         Top             =   5835
         Width           =   1095
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   7
         Left            =   -64605
         TabIndex        =   121
         Top             =   6630
         Width           =   1095
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   6
         Left            =   -65685
         TabIndex        =   120
         Top             =   5835
         Width           =   1095
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   6
         Left            =   -65685
         TabIndex        =   119
         Top             =   6750
         Width           =   1095
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   5
         Left            =   -65385
         TabIndex        =   118
         Top             =   5475
         Width           =   1095
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   5
         Left            =   -65385
         TabIndex        =   117
         Top             =   6270
         Width           =   1095
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   4
         Left            =   -64845
         TabIndex        =   116
         Top             =   5925
         Width           =   1095
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   4
         Left            =   -64845
         TabIndex        =   115
         Top             =   6750
         Width           =   1095
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   3
         Left            =   -65625
         TabIndex        =   114
         Top             =   5715
         Width           =   1095
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   3
         Left            =   -65625
         TabIndex        =   113
         Top             =   6630
         Width           =   1095
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   2
         Left            =   -65205
         TabIndex        =   112
         Top             =   5385
         Width           =   1095
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   2
         Left            =   -65205
         TabIndex        =   111
         Top             =   6270
         Width           =   1095
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   1
         Left            =   -64800
         TabIndex        =   110
         Top             =   5805
         Width           =   1095
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   1
         Left            =   -64800
         TabIndex        =   109
         Top             =   6630
         Width           =   1095
      End
      Begin VB.CommandButton cmdClear 
         Caption         =   "&Clear"
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
         Index           =   0
         Left            =   10290
         TabIndex        =   108
         Top             =   6090
         Width           =   1095
      End
      Begin VB.TextBox txtEquipRateType 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -72105
         MaxLength       =   2
         TabIndex        =   107
         Top             =   2040
         Width           =   400
      End
      Begin VB.TextBox txtLabRateType 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -69330
         MaxLength       =   2
         TabIndex        =   106
         Top             =   1680
         Width           =   400
      End
      Begin VB.TextBox txtService 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   4
         Left            =   -71265
         MaxLength       =   6
         TabIndex        =   101
         Top             =   1470
         Width           =   730
      End
      Begin VB.TextBox txtRate 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   5
         Left            =   -71265
         MaxLength       =   17
         TabIndex        =   100
         Top             =   1950
         Width           =   1580
      End
      Begin VB.TextBox txtCommodity 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   5
         Left            =   -67305
         TabIndex        =   99
         Top             =   1470
         Width           =   730
      End
      Begin VB.TextBox txtUnit 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   3
         Left            =   -67305
         TabIndex        =   98
         Top             =   1950
         Width           =   600
      End
      Begin VB.TextBox txtEnd 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -66810
         MaxLength       =   10
         TabIndex        =   97
         Top             =   1800
         Width           =   1095
      End
      Begin VB.TextBox txtStart 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -66810
         MaxLength       =   10
         TabIndex        =   96
         Top             =   1320
         Width           =   1095
      End
      Begin VB.TextBox txtDurUnit 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -68970
         MaxLength       =   4
         TabIndex        =   95
         Top             =   2280
         Width           =   600
      End
      Begin VB.TextBox txtUnit 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   2
         Left            =   -68970
         MaxLength       =   4
         TabIndex        =   94
         Top             =   1800
         Width           =   600
      End
      Begin VB.TextBox txtCommodity 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   4
         Left            =   -68970
         MaxLength       =   6
         TabIndex        =   93
         Top             =   1320
         Width           =   730
      End
      Begin VB.TextBox txtDuration 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -72450
         MaxLength       =   10
         TabIndex        =   92
         Top             =   2280
         Width           =   1095
      End
      Begin VB.TextBox txtRate 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   4
         Left            =   -72450
         MaxLength       =   17
         TabIndex        =   91
         Top             =   1800
         Width           =   1580
      End
      Begin VB.TextBox txtService 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   3
         Left            =   -72450
         MaxLength       =   6
         TabIndex        =   90
         Top             =   1320
         Width           =   730
      End
      Begin VB.TextBox txtLabRateDesc 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -68970
         MaxLength       =   30
         TabIndex        =   81
         Top             =   1680
         Width           =   3135
      End
      Begin VB.TextBox txtLaborRateType 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -71010
         MaxLength       =   2
         TabIndex        =   80
         Top             =   1680
         Width           =   405
      End
      Begin VB.TextBox txtRate 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   3
         Left            =   -67650
         MaxLength       =   17
         TabIndex        =   77
         Top             =   1680
         Width           =   1580
      End
      Begin VB.TextBox txtLaborType 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -71730
         MaxLength       =   4
         TabIndex        =   76
         Top             =   1680
         Width           =   840
      End
      Begin VB.CommandButton cmdExit 
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
         Index           =   0
         Left            =   10290
         TabIndex        =   72
         Top             =   6840
         Width           =   1095
      End
      Begin VB.TextBox txtRate 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   2
         Left            =   -66690
         MaxLength       =   17
         TabIndex        =   71
         Top             =   1560
         Width           =   1580
      End
      Begin VB.TextBox txtUnit 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   1
         Left            =   -69210
         MaxLength       =   4
         TabIndex        =   70
         Top             =   2160
         Width           =   600
      End
      Begin VB.TextBox txtCommodity 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   3
         Left            =   -69210
         TabIndex        =   69
         Top             =   1560
         Width           =   730
      End
      Begin VB.TextBox txtLocation 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -71970
         MaxLength       =   6
         TabIndex        =   68
         Top             =   2160
         Width           =   730
      End
      Begin VB.TextBox txtService 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   2
         Left            =   -71970
         MaxLength       =   6
         TabIndex        =   67
         Top             =   1560
         Width           =   730
      End
      Begin VB.TextBox txtFreeDays 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -66750
         MaxLength       =   4
         TabIndex        =   61
         Top             =   1800
         Width           =   600
      End
      Begin VB.TextBox txtCommodity 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   2
         Left            =   -70110
         MaxLength       =   12
         TabIndex        =   60
         Top             =   1800
         Width           =   1340
      End
      Begin VB.TextBox txtEquipRTypeDes 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -69090
         MaxLength       =   30
         TabIndex        =   57
         Top             =   1680
         Width           =   3135
      End
      Begin VB.TextBox txtEquipRType 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -71490
         MaxLength       =   2
         TabIndex        =   56
         Top             =   1680
         Width           =   400
      End
      Begin VB.TextBox txtRate 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   1
         Left            =   -66585
         MaxLength       =   17
         TabIndex        =   53
         Top             =   1440
         Width           =   1580
      End
      Begin VB.TextBox txtEquipType 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   -68865
         MaxLength       =   4
         TabIndex        =   52
         Top             =   2040
         Width           =   600
      End
      Begin VB.TextBox txtCommodity 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   1
         Left            =   -68865
         TabIndex        =   51
         Top             =   1440
         Width           =   730
      End
      Begin VB.TextBox txtService 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   1
         Left            =   -72105
         TabIndex        =   50
         Top             =   1440
         Width           =   730
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
         Index           =   0
         Left            =   10290
         TabIndex        =   31
         Top             =   3840
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   0
         Left            =   10290
         TabIndex        =   30
         Top             =   5340
         Width           =   1095
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   0
         Left            =   10290
         TabIndex        =   29
         Top             =   4590
         Width           =   1095
      End
      Begin VB.TextBox txtService 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   0
         Left            =   2880
         MaxLength       =   6
         TabIndex        =   28
         Top             =   1560
         Width           =   730
      End
      Begin VB.TextBox txtRate 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   0
         Left            =   2880
         MaxLength       =   17
         TabIndex        =   27
         Top             =   2160
         Width           =   1580
      End
      Begin VB.TextBox txtCommodity 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   0
         Left            =   7200
         MaxLength       =   6
         TabIndex        =   26
         Top             =   1560
         Width           =   730
      End
      Begin VB.TextBox txtUnit 
         Appearance      =   0  'Flat
         Height          =   330
         Index           =   0
         Left            =   7200
         MaxLength       =   4
         TabIndex        =   25
         Top             =   2160
         Width           =   600
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   1
         Left            =   -64800
         TabIndex        =   24
         Top             =   4170
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   1
         Left            =   -64800
         TabIndex        =   23
         Top             =   4995
         Width           =   1095
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
         Index           =   1
         Left            =   -64800
         TabIndex        =   22
         Top             =   3360
         Width           =   1095
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   2
         Left            =   -65205
         TabIndex        =   21
         Top             =   3630
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   2
         Left            =   -65205
         TabIndex        =   20
         Top             =   4515
         Width           =   1095
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
         Index           =   2
         Left            =   -65205
         TabIndex        =   19
         Top             =   2820
         Width           =   1095
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   3
         Left            =   -65625
         TabIndex        =   18
         Top             =   3900
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   3
         Left            =   -65625
         TabIndex        =   17
         Top             =   4815
         Width           =   1095
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
         Index           =   3
         Left            =   -65625
         TabIndex        =   16
         Top             =   3000
         Width           =   1095
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   4
         Left            =   -64845
         TabIndex        =   15
         Top             =   4290
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   4
         Left            =   -64845
         TabIndex        =   14
         Top             =   5115
         Width           =   1095
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
         Index           =   4
         Left            =   -64845
         TabIndex        =   13
         Top             =   3480
         Width           =   1095
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   5
         Left            =   -65385
         TabIndex        =   12
         Top             =   3900
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   5
         Left            =   -65385
         TabIndex        =   11
         Top             =   4695
         Width           =   1095
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
         Index           =   5
         Left            =   -65385
         TabIndex        =   10
         Top             =   3120
         Width           =   1095
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   6
         Left            =   -65685
         TabIndex        =   9
         Top             =   4020
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   6
         Left            =   -65685
         TabIndex        =   8
         Top             =   4935
         Width           =   1095
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
         Index           =   6
         Left            =   -65685
         TabIndex        =   7
         Top             =   3120
         Width           =   1095
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   7
         Left            =   -64605
         TabIndex        =   6
         Top             =   4260
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   7
         Left            =   -64605
         TabIndex        =   5
         Top             =   5055
         Width           =   1095
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
         Index           =   7
         Left            =   -64605
         TabIndex        =   4
         Top             =   3480
         Width           =   1095
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "&Delete"
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
         Index           =   8
         Left            =   -65025
         TabIndex        =   3
         Top             =   4230
         Width           =   1095
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
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
         Index           =   8
         Left            =   -65025
         TabIndex        =   2
         Top             =   5115
         Width           =   1095
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
         Index           =   8
         Left            =   -65025
         TabIndex        =   1
         Top             =   3360
         Width           =   1095
      End
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   4935
         Index           =   0
         Left            =   480
         TabIndex        =   32
         Top             =   2940
         Width           =   9495
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Col.Count       =   5
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         ForeColorEven   =   12582912
         RowHeight       =   450
         ExtraHeight     =   159
         Columns.Count   =   5
         Columns(0).Width=   3200
         Columns(0).Caption=   "SERVICE"
         Columns(0).Name =   "SERVICE"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   3200
         Columns(1).Caption=   "COMMODITY"
         Columns(1).Name =   "COMMODITY"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   3200
         Columns(2).Caption=   "RATE"
         Columns(2).Name =   "RATE"
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   3200
         Columns(3).Caption=   "UNIT"
         Columns(3).Name =   "UNIT"
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         Columns(4).Width=   3200
         Columns(4).Caption=   "CUSTOMER"
         Columns(4).Name =   "CUSTOMER"
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         _ExtentX        =   16748
         _ExtentY        =   8705
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
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   5295
         Index           =   1
         Left            =   -73200
         TabIndex        =   37
         Top             =   2745
         Width           =   7815
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Col.Count       =   5
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         ForeColorEven   =   12582912
         RowHeight       =   450
         ExtraHeight     =   503
         Columns.Count   =   5
         Columns(0).Width=   1931
         Columns(0).Caption=   "SERVICE "
         Columns(0).Name =   "SERVICE "
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   2461
         Columns(1).Caption=   "COMMODITY"
         Columns(1).Name =   "COMMODITY"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   1508
         Columns(2).Caption=   "RATE"
         Columns(2).Name =   "RATE"
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   2170
         Columns(3).Caption=   "RATE TYPE"
         Columns(3).Name =   "RATE TYPE"
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         Columns(4).Width=   4868
         Columns(4).Caption=   "EQUIP TYPE"
         Columns(4).Name =   "EQUIP TYPE"
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         _ExtentX        =   13785
         _ExtentY        =   9340
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
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   5295
         Index           =   2
         Left            =   -73005
         TabIndex        =   38
         Top             =   2340
         Width           =   6975
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Col.Count       =   2
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         ForeColorEven   =   12582912
         RowHeight       =   450
         ExtraHeight     =   344
         Columns.Count   =   2
         Columns(0).Width=   3200
         Columns(0).Caption=   "RATE TYPE"
         Columns(0).Name =   "RATE TYPE"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   8493
         Columns(1).Caption=   "DESCRIPTION"
         Columns(1).Name =   "DESCRIPTION"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         _ExtentX        =   12303
         _ExtentY        =   9340
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
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   4695
         Index           =   3
         Left            =   -71745
         TabIndex        =   39
         Top             =   2790
         Width           =   4215
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Col.Count       =   2
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         ForeColorEven   =   12582912
         RowHeight       =   450
         ExtraHeight     =   185
         Columns.Count   =   2
         Columns(0).Width=   3200
         Columns(0).Caption=   "COMMODITY"
         Columns(0).Name =   "COMMODITY"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   3200
         Columns(1).Caption=   "FREE DAYS"
         Columns(1).Name =   "FREE DAYS"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         _ExtentX        =   7435
         _ExtentY        =   8281
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
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   4935
         Index           =   4
         Left            =   -73365
         TabIndex        =   40
         Top             =   2940
         Width           =   7815
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Col.Count       =   5
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         ForeColorEven   =   12582912
         RowHeight       =   450
         ExtraHeight     =   185
         Columns.Count   =   5
         Columns(0).Width=   2355
         Columns(0).Caption=   "SERVICE"
         Columns(0).Name =   "SERVICE"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   2355
         Columns(1).Caption=   "COMMODITY"
         Columns(1).Name =   "COMMODITY"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   2858
         Columns(2).Caption=   "LOCATION"
         Columns(2).Name =   "LOCATION"
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   2143
         Columns(3).Caption=   "RATE"
         Columns(3).Name =   "RATE"
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         Columns(4).Width=   3200
         Columns(4).Caption=   "UNIT"
         Columns(4).Name =   "UNIT"
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         _ExtentX        =   13785
         _ExtentY        =   8705
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
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   5175
         Index           =   5
         Left            =   -72825
         TabIndex        =   41
         Top             =   2460
         Width           =   6495
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Col.Count       =   3
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         ForeColorEven   =   12582912
         RowHeight       =   450
         ExtraHeight     =   344
         Columns.Count   =   3
         Columns(0).Width=   3863
         Columns(0).Caption=   "LABOR TYPE"
         Columns(0).Name =   "LABOR TYPE"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   3200
         Columns(1).Caption=   "RATE TYPE"
         Columns(1).Name =   "RATE TYPE"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   3200
         Columns(2).Caption=   "RATE"
         Columns(2).Name =   "RATE"
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         _ExtentX        =   11456
         _ExtentY        =   9128
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
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   4695
         Index           =   6
         Left            =   -72525
         TabIndex        =   42
         Top             =   2700
         Width           =   5655
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Col.Count       =   2
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         ForeColorEven   =   12582912
         RowHeight       =   450
         ExtraHeight     =   185
         Columns.Count   =   2
         Columns(0).Width=   3200
         Columns(0).Caption=   "RATE TYPE"
         Columns(0).Name =   "RATE TYPE"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   5636
         Columns(1).Caption=   "RATE DESCRIPTION"
         Columns(1).Name =   "RATE DESCRIPTION"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         _ExtentX        =   9975
         _ExtentY        =   8281
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
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   4935
         Index           =   7
         Left            =   -73605
         TabIndex        =   43
         Top             =   2940
         Width           =   8415
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Col.Count       =   8
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         ForeColorEven   =   12582912
         RowHeight       =   450
         ExtraHeight     =   503
         Columns.Count   =   8
         Columns(0).Width=   1561
         Columns(0).Caption=   "SERVICE"
         Columns(0).Name =   "SERVICE"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   2566
         Columns(1).Caption=   "COMMODITY"
         Columns(1).Name =   "COMMODITY"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   1296
         Columns(2).Caption=   "RATE"
         Columns(2).Name =   "RATE"
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   1482
         Columns(3).Caption=   "UNIT"
         Columns(3).Name =   "UNIT"
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         Columns(4).Width=   2328
         Columns(4).Caption=   "DURATION"
         Columns(4).Name =   "DURATION"
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         Columns(5).Width=   2011
         Columns(5).Caption=   "DUR UNIT"
         Columns(5).Name =   "DUR UNIT"
         Columns(5).DataField=   "Column 5"
         Columns(5).DataType=   8
         Columns(5).FieldLen=   256
         Columns(6).Width=   1349
         Columns(6).Caption=   "START"
         Columns(6).Name =   "START"
         Columns(6).DataField=   "Column 6"
         Columns(6).DataType=   8
         Columns(6).FieldLen=   256
         Columns(7).Width=   1164
         Columns(7).Caption=   "END"
         Columns(7).Name =   "END"
         Columns(7).DataField=   "Column 7"
         Columns(7).DataType=   8
         Columns(7).FieldLen=   256
         _ExtentX        =   14843
         _ExtentY        =   8705
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
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   4935
         Index           =   8
         Left            =   -72975
         TabIndex        =   44
         Top             =   2820
         Width           =   6315
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Col.Count       =   4
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         ForeColorEven   =   12582912
         RowHeight       =   450
         ExtraHeight     =   635
         Columns.Count   =   4
         Columns(0).Width=   2619
         Columns(0).Caption=   "SERVICE"
         Columns(0).Name =   "SERVICE"
         Columns(0).Alignment=   1
         Columns(0).CaptionAlignment=   2
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   2699
         Columns(1).Caption=   "COMMODITY"
         Columns(1).Name =   "COMMODITY"
         Columns(1).Alignment=   1
         Columns(1).CaptionAlignment=   2
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   2540
         Columns(2).Caption=   "RATE"
         Columns(2).Name =   "RATE"
         Columns(2).Alignment=   1
         Columns(2).CaptionAlignment=   2
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   2143
         Columns(3).Caption=   "UNIT"
         Columns(3).Name =   "UNIT"
         Columns(3).CaptionAlignment=   2
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         _ExtentX        =   11139
         _ExtentY        =   8705
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
      Begin SSDataWidgets_B.SSDBGrid grdData 
         Height          =   4935
         Index           =   10
         Left            =   -74820
         TabIndex        =   149
         Top             =   2700
         Width           =   11010
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Col.Count       =   10
         AllowUpdate     =   0   'False
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         ForeColorEven   =   12582912
         RowHeight       =   450
         ExtraHeight     =   1270
         Columns.Count   =   10
         Columns(0).Width=   1323
         Columns(0).Caption=   "SER"
         Columns(0).Name =   "SERVICE"
         Columns(0).Alignment=   1
         Columns(0).CaptionAlignment=   2
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   1667
         Columns(1).Caption=   "COMM"
         Columns(1).Name =   "COMMODITY"
         Columns(1).Alignment=   1
         Columns(1).CaptionAlignment=   2
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   1455
         Columns(2).Caption=   "RATE"
         Columns(2).Name =   "RATE"
         Columns(2).Alignment=   1
         Columns(2).CaptionAlignment=   2
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   1376
         Columns(3).Caption=   "UNIT"
         Columns(3).Name =   "UNIT"
         Columns(3).CaptionAlignment=   2
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         Columns(4).Width=   1376
         Columns(4).Caption=   "CUST"
         Columns(4).Name =   "CUSTOMER"
         Columns(4).Alignment=   1
         Columns(4).CaptionAlignment=   2
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         Columns(5).Width=   2143
         Columns(5).Caption=   "DISCOUNT"
         Columns(5).Name =   "DISCOUNT"
         Columns(5).DataField=   "Column 5"
         Columns(5).DataType=   8
         Columns(5).FieldLen=   256
         Columns(6).Width=   1958
         Columns(6).Caption=   "DISC. TYPE"
         Columns(6).Name =   "DISC. TYPE"
         Columns(6).DataField=   "Column 6"
         Columns(6).DataType=   8
         Columns(6).FieldLen=   256
         Columns(7).Width=   2937
         Columns(7).Caption=   "DISC THRESHOLD"
         Columns(7).Name =   "DISC THRESHOLD"
         Columns(7).DataField=   "Column 7"
         Columns(7).DataType=   8
         Columns(7).FieldLen=   256
         Columns(8).Width=   1852
         Columns(8).Caption=   "DISC UNIT"
         Columns(8).Name =   "DISC UNIT"
         Columns(8).DataField=   "Column 8"
         Columns(8).DataType=   8
         Columns(8).FieldLen=   256
         Columns(9).Width=   2249
         Columns(9).Caption=   "LOC"
         Columns(9).Name =   "LOC"
         Columns(9).CaptionAlignment=   2
         Columns(9).DataField=   "Column 9"
         Columns(9).DataType=   8
         Columns(9).FieldLen=   256
         _ExtentX        =   19420
         _ExtentY        =   8705
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
      Begin VB.Label Label49 
         Caption         =   "Customer :"
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
         Left            =   9840
         TabIndex        =   171
         Top             =   1620
         Width           =   975
      End
      Begin VB.Label Label48 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Location  :"
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
         Left            =   -70905
         TabIndex        =   169
         Top             =   2100
         Width           =   855
      End
      Begin VB.Label Label47 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Discount Unit  :"
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
         Left            =   -67860
         TabIndex        =   164
         Top             =   2130
         Width           =   1275
      End
      Begin VB.Label Label46 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Discount Threshold  :"
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
         Left            =   -68355
         TabIndex        =   163
         Top             =   1680
         Width           =   1770
      End
      Begin VB.Label Label45 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Discount Type  :"
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
         Left            =   -65070
         TabIndex        =   162
         Top             =   1230
         Width           =   1320
      End
      Begin VB.Label Label44 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Discount  :"
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
         Left            =   -67470
         TabIndex        =   161
         Top             =   1185
         Width           =   885
      End
      Begin VB.Label Label43 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Customer  :"
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
         Left            =   -74370
         TabIndex        =   159
         Top             =   1230
         Width           =   960
      End
      Begin VB.Label Label32 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Unit  :"
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
         Index           =   1
         Left            =   -70545
         TabIndex        =   153
         Top             =   1680
         Width           =   495
      End
      Begin VB.Label Label33 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity  :"
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
         Index           =   1
         Left            =   -71115
         TabIndex        =   152
         Top             =   1230
         Width           =   1065
      End
      Begin VB.Label Label34 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate  :"
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
         Index           =   1
         Left            =   -74010
         TabIndex        =   151
         Top             =   2130
         Width           =   525
      End
      Begin VB.Label Label35 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service  :"
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
         Index           =   1
         Left            =   -74220
         TabIndex        =   150
         Top             =   1680
         Width           =   765
      End
      Begin VB.Label Label42 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Unit  :"
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
         Left            =   -66450
         TabIndex        =   133
         Top             =   2160
         Width           =   495
      End
      Begin VB.Label Label41 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Min Charge  :"
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
         Left            =   -70050
         TabIndex        =   132
         Top             =   2160
         Width           =   1140
      End
      Begin VB.Label Label40 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Unit 2  :"
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
         Left            =   -66600
         TabIndex        =   131
         Top             =   1560
         Width           =   645
      End
      Begin VB.Label Label39 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Unit 1  :"
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
         Left            =   -69555
         TabIndex        =   130
         Top             =   1560
         Width           =   645
      End
      Begin VB.Label Label38 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate  :"
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
         Left            =   -73290
         TabIndex        =   129
         Top             =   2760
         Width           =   525
      End
      Begin VB.Label Label37 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity  :"
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
         Left            =   -73830
         TabIndex        =   127
         Top             =   2160
         Width           =   1065
      End
      Begin VB.Label Label36 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service  :"
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
         Left            =   -73530
         TabIndex        =   126
         Top             =   1560
         Width           =   765
      End
      Begin VB.Label Label35 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service  :"
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
         Index           =   0
         Left            =   -72225
         TabIndex        =   105
         Top             =   1530
         Width           =   765
      End
      Begin VB.Label Label34 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate  :"
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
         Index           =   0
         Left            =   -71985
         TabIndex        =   104
         Top             =   2010
         Width           =   525
      End
      Begin VB.Label Label33 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity  :"
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
         Index           =   0
         Left            =   -68595
         TabIndex        =   103
         Top             =   1530
         Width           =   1065
      End
      Begin VB.Label Label32 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Unit  :"
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
         Index           =   0
         Left            =   -68025
         TabIndex        =   102
         Top             =   2010
         Width           =   495
      End
      Begin VB.Label Label31 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "End  :"
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
         Left            =   -67530
         TabIndex        =   89
         Top             =   1860
         Width           =   450
      End
      Begin VB.Label Label30 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Start  :"
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
         Left            =   -67650
         TabIndex        =   88
         Top             =   1380
         Width           =   570
      End
      Begin VB.Label Label29 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Duration Unit  :"
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
         Left            =   -70410
         TabIndex        =   87
         Top             =   2340
         Width           =   1275
      End
      Begin VB.Label Label28 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Unit  :"
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
         Left            =   -69630
         TabIndex        =   86
         Top             =   1860
         Width           =   495
      End
      Begin VB.Label Label27 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity  :"
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
         Left            =   -70200
         TabIndex        =   85
         Top             =   1380
         Width           =   1065
      End
      Begin VB.Label Label26 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Duration  :"
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
         Left            =   -73530
         TabIndex        =   84
         Top             =   2340
         Width           =   885
      End
      Begin VB.Label Label25 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate  :"
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
         Left            =   -73170
         TabIndex        =   83
         Top             =   1860
         Width           =   525
      End
      Begin VB.Label Label24 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service  :"
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
         Left            =   -73410
         TabIndex        =   82
         Top             =   1380
         Width           =   765
      End
      Begin VB.Label Label23 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Description  :"
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
         Left            =   -70170
         TabIndex        =   79
         Top             =   1740
         Width           =   1230
      End
      Begin VB.Label Label22 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate Type  :"
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
         Left            =   -72090
         TabIndex        =   78
         Top             =   1740
         Width           =   960
      End
      Begin VB.Label Label21 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate  :"
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
         Left            =   -68370
         TabIndex        =   75
         Top             =   1740
         Width           =   525
      End
      Begin VB.Label Label20 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate Type  :"
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
         Left            =   -70410
         TabIndex        =   74
         Top             =   1740
         Width           =   960
      End
      Begin VB.Label Label19 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Labor Type  :"
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
         Left            =   -72930
         TabIndex        =   73
         Top             =   1740
         Width           =   1065
      End
      Begin VB.Label Label18 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate  :"
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
         Left            =   -67530
         TabIndex        =   66
         Top             =   1620
         Width           =   645
      End
      Begin VB.Label Label17 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Unit  :"
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
         Left            =   -69930
         TabIndex        =   65
         Top             =   2220
         Width           =   495
      End
      Begin VB.Label Label16 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity  :"
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
         Left            =   -70500
         TabIndex        =   64
         Top             =   1620
         Width           =   1065
      End
      Begin VB.Label Label15 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Location  :"
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
         Left            =   -73050
         TabIndex        =   63
         Top             =   2220
         Width           =   855
      End
      Begin VB.Label Label14 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service  :"
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
         Left            =   -72960
         TabIndex        =   62
         Top             =   1620
         Width           =   765
      End
      Begin VB.Label Label13 
         Appearance      =   0  'Flat
         AutoSize        =   -1  'True
         BackColor       =   &H80000005&
         BackStyle       =   0  'Transparent
         Caption         =   "Free Days  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   225
         Left            =   -67950
         TabIndex        =   59
         Top             =   1860
         Width           =   975
      End
      Begin VB.Label Label12 
         Appearance      =   0  'Flat
         AutoSize        =   -1  'True
         BackColor       =   &H80000005&
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   225
         Left            =   -71310
         TabIndex        =   58
         Top             =   1860
         Width           =   1065
      End
      Begin VB.Label Label11 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Description  :"
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
         Left            =   -70290
         TabIndex        =   55
         Top             =   1740
         Width           =   1110
      End
      Begin VB.Label Label10 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate Type  :"
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
         Left            =   -72570
         TabIndex        =   54
         Top             =   1740
         Width           =   960
      End
      Begin VB.Label Label9 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate  :"
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
         Left            =   -67305
         TabIndex        =   49
         Top             =   1500
         Width           =   525
      End
      Begin VB.Label Label8 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Equipment Type  :"
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
         Left            =   -70425
         TabIndex        =   48
         Top             =   2100
         Width           =   1440
      End
      Begin VB.Label Label7 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity  :"
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
         Left            =   -70050
         TabIndex        =   47
         Top             =   1500
         Width           =   1065
      End
      Begin VB.Label Label6 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate Type  :"
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
         Left            =   -73215
         TabIndex        =   46
         Top             =   2100
         Width           =   960
      End
      Begin VB.Label Label5 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service  :"
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
         Left            =   -73020
         TabIndex        =   45
         Top             =   1500
         Width           =   765
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service  :"
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
         Left            =   1920
         TabIndex        =   36
         Top             =   1620
         Width           =   765
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Rate  :"
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
         Left            =   2160
         TabIndex        =   35
         Top             =   2220
         Width           =   525
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity  :"
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
         Left            =   5910
         TabIndex        =   34
         Top             =   1620
         Width           =   1065
      End
      Begin VB.Label Label4 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Unit  :"
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
         Left            =   6480
         TabIndex        =   33
         Top             =   2220
         Width           =   495
      End
   End
End
Attribute VB_Name = "frmBniRates"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim iRec As Integer
Sub DelRate(sql As String)

OraSession.BeginTrans
OraDatabase.ExecuteSQL (sql)

If OraDatabase.LastServerErr = 0 Then
    OraSession.COMMITTRANS
Else
    MsgBox OraDatabase.LastServerErrText & vbCrLf & "Unable to Delete records ! ", vbCritical, "ORACLE ERROR - DELETE RATE"
    OraDatabase.LastServerErrReset
    OraSession.ROLLBACK
    Exit Sub
End If

End Sub

Private Sub cmdClear_Click(Index As Integer)
    Select Case Index
        Case 0
                
            txtService(0) = ""
            txtCommodity(0) = ""
            txtRate(0) = ""
            txtUnit(0) = ""
            txtCustomer(0) = ""

        Case 1       'EQUIPMENT RATE
                        
            txtService(1) = ""
            txtCommodity(1) = ""
            txtEquipRateType = ""
            txtEquipType = ""
            txtRate(1) = ""
            
        Case 2        'EQUIPMENT RATE TYPE
            
            txtEquipRType = ""
            txtEquipRTypeDes = ""
        
        Case 3           'FREE TIME
            
            txtCommodity(2) = ""
            txtFreeDays = ""
            
        Case 4         'HANDLING
                                
            txtCommodity(3) = ""
            txtService(2) = ""
            txtLocation = ""
            txtRate(2) = ""
            txtUnit(1) = ""
            
        Case 5        'LABOR RATE
            
            txtLaborType = ""
            txtLabRateType = ""
            txtRate(3) = ""
            
        Case 6       'LABOR RATE TYPE
            
            txtLaborRateType = ""
            txtLabRateDesc = ""
        
        Case 7         'STORAGE RATE
        
            txtService(3) = ""
            txtCommodity(4) = ""
            txtUnit(2) = ""
            txtRate(4) = ""
            txtDuration = "'"
            txtDurUnit = ""
            txtStart = ""
            txtEnd = ""
            
        Case 8         'TRUCK HANDLING
            
            txtService(4) = ""
            txtCommodity(5) = ""
            txtRate(5) = ""
            txtUnit(3) = ""
        Case 9         'VESSEL RATE
            txtService(5) = ""
            txtCommodity(6) = ""
            txtRate(6) = ""
            txtUnit1 = ""
            txtUnit2 = ""
            txtMinCharge = ""
            txtMinCUnit = ""
        Case 10         'ADVANCE BILLING RATE
         txtService(6) = ""
         txtCommodity(7) = ""
         txtRate(7) = ""
         txtUnit(4) = ""
         txtCust = ""
         txtDiscount = ""
         cboDiscountType.ListIndex = -1
         txtThreshold = ""
         cboDiscountUnit.ListIndex = -1
         txtLoc = ""
    End Select
End Sub

Private Sub cmdDelete_Click(Index As Integer)
    Select Case Index
        Case 0
                
            If Trim(txtService(0)) = "" Or Trim(txtCommodity(0)) = "" Or Trim(txtRate(0)) = "" Or Trim(txtUnit(0)) = "" Then
                MsgBox "Required Field(s) are empty ! ", vbInformation, "DELETE DOCK RECPT HANDLING RATE"
                Exit Sub
            End If
                        
            SqlStmt = " DELETE FROM DOCK_RCPT_HANDLING_RATE " _
                    & " WHERE SERVICE_CODE='" & Trim(txtService(0)) & "'" _
                    & " AND COMMODITY_CODE='" & Trim(txtCommodity(0)) & "'" _
                    & " AND RATE='" & Trim(txtRate(0)) & "'" _
                    & " AND UNIT='" & Trim(txtUnit(0)) & "'"
            If Trim(txtCustomer(0)) <> "" Then
                SqlStmt = SqlStmt & " AND CUSTOMER_ID = '" & Trim(txtCustomer(0)) & "'"
            Else
                SqlStmt = SqlStmt & " AND CUSTOMER_ID IS NULL"
            End If
            
            
            Call DelRate(SqlStmt)
            Call DckRcpt

        Case 1       'EQUIPMENT RATE
                        
            If Trim(txtService(1)) = "" Or Trim(txtCommodity(1)) = "" Or Trim(txtEquipRateType) = "" Or Trim(txtEquipType) = "" Or Trim(txtRate(1)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "DELETE EQUIPMENT RATE"
                Exit Sub
            End If
                            
            SqlStmt = " DELETE FROM EQUIPMENT_RATE " _
                    & " WHERE SERVICE_CODE='" & Trim(txtService(1)) & "'" _
                    & " AND COMMODITY_CODE='" & Trim(txtCommodity(1)) & "'" _
                    & " AND RATE_TYPE='" & Trim(txtEquipRateType) & "'" _
                    & " AND EQUIPMENT_TYPE='" & Trim(txtEquipType) & "'" _
                    & " AND RATE='" & Trim(txtRate(1)) & "'"
            
            Call DelRate(SqlStmt)
            Call EquipRate
            
        Case 2        'EQUIPMENT RATE TYPE
            
            If Trim(txtEquipRType) = "" Or Trim(txtEquipRTypeDes) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "DELETE EQUIPMENT RATE TYPE"
                Exit Sub
            End If
            
            SqlStmt = " DELETE FROM EQUIPMENT_RATE_TYPE " _
                    & " WHERE RATE_TYPE='" & Trim(txtEquipRType) & "'" _
                    & " AND RATE_DESCRIPTION='" & Trim(txtEquipRTypeDes) & "'"
            
            Call DelRate(SqlStmt)
            Call EquipRateType
        
        Case 3           'FREE TIME
            
            If Trim(txtCommodity(2)) = "" Or Trim(txtFreeDays) = "" Then
                MsgBox "Required Field(s) are empty !.", vbInformation, "DELETE FREE TIME RATE"
                Exit Sub
            End If
                    
            SqlStmt = " DELETE FROM FREE_TIME " _
                    & " WHERE COMMODITY_CODE='" & Trim(txtCommodity(2)) & "'" _
                    & " AND FREE_DAYS='" & Trim(txtFreeDays) & "'"
            
            Call DelRate(SqlStmt)
            Call FreeTime
            
        Case 4         'HANDLING
                                
            If Trim(txtCommodity(3)) = "" Or Trim(txtService(2)) = "" Or Trim(txtLocation) = "" Or Trim(txtRate(2)) = "" Or Trim(txtUnit(1)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "DELETE HANDLING RATE"
                Exit Sub
            End If
            
            SqlStmt = " DELETE FROM HANDLING_RATE " _
                    & " WHERE COMMODITY_CODE='" & Trim(txtCommodity(3)) & "'" _
                    & " AND SERVICE_CODE='" & Trim(txtService(2)) & "'" _
                    & " AND LOCATION_CODE ='" & Trim(txtLocation) & "'" _
                    & " AND RATE ='" & Trim(txtRate(2)) & "'" _
                    & " AND UNIT = '" & Trim(txtUnit(1)) & "'"
            
            Call DelRate(SqlStmt)
            Call HandlingRate
                    
        Case 5        'LABOR RATE
            
            If Trim(txtLaborType) = "" Or Trim(txtLabRateType) = "" Or Trim(txtRate(3)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "DELETE LABOR RATE"
                Exit Sub
            End If
            
            SqlStmt = " DELETE FROM LABOR_RATE " _
                    & " WHERE LABOR_TYPE='" & Trim(txtLaborType) & "'" _
                    & " AND RATE_TYPE='" & Trim(txtLabRateType) & "'" _
                    & " AND RATE='" & Trim(txtRate(3)) & "'"
            
            Call DelRate(SqlStmt)
            Call LaborRate
            
        Case 6       'LABOR RATE TYPE
            
            If Trim(txtLaborRateType) = "" Or Trim(txtLabRateDesc) = "" Then
                MsgBox "Required Fields are empty !", vbInformation, "DELETE LABOR RATE TYPE"
                Exit Sub
            End If
            
            SqlStmt = " DELETE FROM LABOR_RATE_TYPE " _
                    & " WHERE RATE_TYPE='" & Trim(txtLaborRateType) & "'" _
                    & " AND RATE_DESCRIPTION = '" & Trim(txtLabRateDesc) & "'"
            
            Call DelRate(SqlStmt)
            Call LaborRateType
        
        Case 7         'STORAGE RATE
        
            If Trim(txtService(3)) = "" Or Trim(txtCommodity(4)) = "" Or Trim(txtUnit(2)) = "" Or Trim(txtRate(4)) = "" Then
                MsgBox "Required Field(s) are empty !.", vbInformation, "DELETE STORAGE RATE"
                Exit Sub
            End If
            
            SqlStmt = " DELETE FROM STORAGE_RATE " _
                    & " WHERE SERVICE_CODE='" & Trim(txtService(3)) & "'" _
                    & " AND COMMODITY_CODE='" & Trim(txtCommodity(4)) & "'" _
                    & " AND UNIT='" & Trim(txtUnit(2)) & "'" _
                    & " AND RATE='" & Trim(txtRate(4)) & "'"
            
            Call DelRate(SqlStmt)
            Call StorageRate
            
        Case 8         'TRUCK HANDLING
            
            If Trim(txtService(4)) = "" Or Trim(txtCommodity(5)) = "" Or Trim(txtRate(5)) = "" Or Trim(txtUnit(3)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "DELETE TRUCK HANDLING RATE"
                Exit Sub
            End If
            
            SqlStmt = " DELETE FROM TRUCK_HANDLING_RATE " _
                    & " WHERE SERVICE_CODE='" & Trim(txtService(4)) & "'" _
                    & " AND COMMODITY_CODE='" & Trim(txtCommodity(5)) & "'" _
                    & " AND RATE = '" & Trim(txtRate(5)) & "'" _
                    & " AND UNIT ='" & Trim(txtUnit(3)) & "'"
            
            Call DelRate(SqlStmt)
            Call TrkHandling
            
        Case 9           'VESSEL RATE
            
            If Trim(txtService(5)) = "" Or Trim(txtCommodity(6)) = "" Or Trim(txtRate(6)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "DELETE VESSEL RATE"
                Exit Sub
            End If
            
            SqlStmt = " DELETE FROM VESSEL_RATE " _
                    & " WHERE SERVICE_CODE='" & Trim(txtService(5)) & "'" _
                    & " AND COMMODITY_CODE='" & Trim(txtCommodity(6)) & "'" _
                    & " AND SERVICE_RATE = '" & Trim(txtRate(6)) & "'"
            
            Call DelRate(SqlStmt)
            Call VesselRate
         
        Case 10          'ADVANCE BILLING RATE
        
            If Trim(txtService(6)) = "" Or Trim(txtCommodity(7)) = "" Or Trim(txtRate(7)) = "" Or Trim(txtUnit(4)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "DELETE ADVANCE BILLING RATE"
                Exit Sub
            End If
            
            If Trim(txtCust) = "" Then
            
               SqlStmt = " DELETE FROM TERMINAL_RATE " _
                       & " WHERE SERVICE_CODE='" & Trim(txtService(6)) & "'" _
                       & " AND COMMODITY_CODE='" & Trim(txtCommodity(7)) & "'" _
                       & " AND RATE = '" & Trim(txtRate(7)) & "'" _
                       & " AND UNIT ='" & Trim(txtUnit(4)) & "' AND CUSTOMER_ID IS NULL"
            Else
               If Trim(txtLoc) = "" Then
                  SqlStmt = " DELETE FROM TERMINAL_RATE " _
                          & " WHERE SERVICE_CODE='" & Trim(txtService(6)) & "'" _
                          & " AND COMMODITY_CODE='" & Trim(txtCommodity(7)) & "'" _
                          & " AND RATE = '" & Trim(txtRate(7)) & "'" _
                          & " AND UNIT ='" & Trim(txtUnit(4)) & "'" _
                          & " AND CUSTOMER_ID='" & Trim(txtCust) & "' AND LOCATION IS NULL"
               ElseIf Trim(txtLoc) <> "" Then
                  SqlStmt = " DELETE FROM TERMINAL_RATE " _
                          & " WHERE SERVICE_CODE='" & Trim(txtService(6)) & "'" _
                          & " AND COMMODITY_CODE='" & Trim(txtCommodity(7)) & "'" _
                          & " AND RATE = '" & Trim(txtRate(7)) & "'" _
                          & " AND UNIT ='" & Trim(txtUnit(4)) & "'" _
                          & " AND CUSTOMER_ID='" & Trim(txtCust) & "'" _
                          & " AND LOCATION='" & Trim(txtLoc) & "'"
                          
               End If
            End If
            
            Call DelRate(SqlStmt)
            Call AdvanceRate
         
    End Select
    
End Sub

Private Sub cmdExit_Click(Index As Integer)
    Unload Me
End Sub

Private Sub cmdPrint_Click(Index As Integer)
    
    Printer.Print ""
    Printer.Print Tab(5); "Printed on  :"; Tab(20); Format(Date, "MM/DD/YYYY")
    Printer.FontSize = 10
    Printer.Print ""
    Printer.Print ""
    
    Select Case Index
        Case 0              'DOCK RCPT HANDLING RATE
            Printer.FontBold = True
            Printer.Print Tab(45); "DOCK RCPT HANDLING RATE"
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(5); "SERVICE "; Tab(30); "COMMODITY"; Tab(55); "RATE"; Tab(80); "UNIT"; Tab(105); "CUSTOMER"
            Printer.FontBold = False
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
    
            With grdData(0)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(8); Trim(.Columns(0).Value); Tab(37); .Columns(1).Value; Tab(61); .Columns(2).Value; Tab(85); .Columns(3).Value; Tab(105); .Columns(4).Value
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(5); "Total Records:"; Tab(30); grdData(0).Rows
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
            grdData(0).MoveFirst
            Printer.EndDoc
    
        Case 1       'EQUIPMENT RATE
            
            Printer.FontBold = True
            Printer.Print Tab(45); "EQUIPMENT RATE"
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(5); "-----------------------------------------------------------------------------------------------------------------------" _
                                ; "-----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "SERVICE "; Tab(30); "COMMODITY"; Tab(50); "RATE"; Tab(70); "RATE TYPE"; Tab(90); "EQUIPMENT TYPE"
            Printer.FontBold = False
            Printer.Print Tab(5); "-----------------------------------------------------------------------------------------------------------------------"; _
                                  "-----------------------------------------------------------------------------------------------------------------------"
    
            With grdData(1)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(13); Trim(.Columns(0).Value); Tab(37); .Columns(1).Value; Tab(55); .Columns(2).Value; Tab(77); .Columns(3).Value; Tab(100); .Columns(4).Value
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(5); "-----------------------------------------------------------------------------------------------------------------------"; _
                                  "-----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Total Records:"; Tab(30); grdData(1).Rows
            Printer.Print Tab(5); "-----------------------------------------------------------------------------------------------------------------------"; _
                                  "-----------------------------------------------------------------------------------------------------------------------"
            grdData(1).MoveFirst
            Printer.EndDoc
            
        Case 2          'EQUIPMENT RATE TYPE
            
            Printer.FontBold = True
            Printer.Print Tab(45); "EQUIPMENT RATE TYPE"
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "RATE TYPE "; Tab(30); "DESCRIPTION"
            Printer.FontBold = False
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
    
            With grdData(2)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(13); Trim(.Columns(0).Value); Tab(38); .Columns(1).Value
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Total Records:"; Tab(30); grdData(2).Rows
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
            grdData(2).MoveFirst
            Printer.EndDoc
            
        Case 3          'FREE TIME
            
            Printer.FontBold = True
            Printer.Print Tab(45); "FREE TIME"
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "COMMODITY "; Tab(40); "FREE DAYS"
            Printer.FontBold = False
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
    
            With grdData(3)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(13); Trim(.Columns(0).Value); Tab(46); .Columns(1).Value
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Total Records:"; Tab(30); grdData(3).Rows
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------"
            grdData(3).MoveFirst
            Printer.EndDoc
            
            
        
        Case 4          'HANDLING RATE
            
            Printer.FontBold = True
            Printer.Print Tab(45); "HANDLING RATE"
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------" _
                                  ; "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "SERVICE "; Tab(30); "COMMODITY"; Tab(50); "LOCATION"; Tab(70); "RATE"; Tab(90); "UNIT"
            Printer.FontBold = False
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------" _
                                  ; "----------------------------------------------------------------------------------------------------------------------"
    
            With grdData(4)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(13); Trim(.Columns(0).Value); Tab(37); .Columns(1).Value; Tab(57); .Columns(2).Value; Tab(77); .Columns(3).Value; Tab(98); .Columns(4).Value
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------" _
                                  ; "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Total Records:"; Tab(30); grdData(4).Rows
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------" _
                                  ; "----------------------------------------------------------------------------------------------------------------------"
            grdData(4).MoveFirst
            Printer.EndDoc
            
        Case 5      'LABOR RATE
        
            Printer.FontBold = True
            Printer.Print Tab(45); "LABOR RATE"
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "LABOR TYPE "; Tab(35); " RATE TYPE"; Tab(60); "RATE"
            Printer.FontBold = False
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------"
    
            With grdData(5)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(13); Trim(.Columns(0).Value); Tab(40); .Columns(1).Value; Tab(65); .Columns(2).Value
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Total Records:"; Tab(30); grdData(5).Rows
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------"
            grdData(5).MoveFirst
            Printer.EndDoc
            
        
        Case 6       'LABOR RATE TYPE
        
            Printer.FontBold = True
            Printer.Print Tab(45); "LABOR RATE TYPE"
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "LABOR TYPE "; Tab(35); "DESCRIPTION"
            Printer.FontBold = False
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------"
    
            With grdData(6)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(13); Trim(.Columns(0).Value); Tab(39); .Columns(1).Value
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Total Records:"; Tab(30); grdData(6).Rows
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------"
            grdData(6).MoveFirst
            Printer.EndDoc
        Case 7         'STORAGE RATE
        
            Printer.FontBold = True
            Printer.Print Tab(45); "STORAGE RATE"
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                                  ; "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(5); "SERVICE"; Tab(20); "COMMODITY"; Tab(40); "RATE"; Tab(55); "UNIT"; Tab(70); "DURATION"; Tab(85); "DUR. UNIT"; Tab(100); "START"; Tab(110); "END"
            Printer.FontBold = False
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                                  ; "----------------------------------------------------------------------------------------------------------------------"
    
            With grdData(7)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(7); Trim(.Columns(0).Value); Tab(23); Trim(.Columns(1).Value); Tab(44); Trim(.Columns(2).Value); Tab(59); Trim(.Columns(3).Value); Tab(77); Trim(.Columns(4).Value); Tab(95); Trim(.Columns(5).Value); Tab(110); Trim(.Columns(6).Value); Tab(120); Trim(.Columns(7).Value)
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                                ; "----------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(5); "Total Records:"; Tab(30); grdData(7).Rows
            Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                                  ; "----------------------------------------------------------------------------------------------------------------------"
            grdData(7).MoveFirst
            Printer.EndDoc
            
        
        Case 8      'TRUCK HANDLING RATE
            
            Printer.FontBold = True
            Printer.Print Tab(45); "TRUCK HANDLING RATE"
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(5); "------------------------------------------------------------------------------------------------------------------------------------------"
            Printer.Print ; Tab(10); "SERVICE "; Tab(30); "COMMODITY"; Tab(50); "RATE"; Tab(70); "UNIT"
            Printer.FontBold = False
            Printer.Print Tab(5); "------------------------------------------------------------------------------------------------------------------------------------------"
                                      
            With grdData(8)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(13); Trim(.Columns(0).Value); Tab(37); .Columns(1).Value; Tab(55); .Columns(2).Value; Tab(77); .Columns(3).Value
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(5); "------------------------------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Total Records:"; Tab(30); grdData(8).Rows
            Printer.Print Tab(5); "------------------------------------------------------------------------------------------------------------------------------------------"
            grdData(8).MoveFirst
            
            Printer.EndDoc
        
        Case 9          'VESSEL RATE
            
            Printer.FontBold = True
            Printer.Print Tab(45); "VESSEL RATE"
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------------------------------------------------"
            Printer.Print ; Tab(8); "SERVICE "; Tab(25); "COMMODITY"; Tab(40); "RATE"; Tab(50); "UNIT1"; Tab(60); "UNIT2"; Tab(70); "MIN CHRAGE"; Tab(90); "UNIT"
            Printer.FontBold = False
            Printer.Print Tab(5); "-----------------------------------------------------------------------------------------------------------------------------------------------------"
                                      
            With grdData(9)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(9); CStr(Trim(.Columns(0).Value)); Tab(28); CStr(.Columns(1).Value); Tab(43); CStr(.Columns(2).Value); Tab(55); CStr(.Columns(3).Value); _
                                  Tab(66); .Columns(4).Value; Tab(77); .Columns(5).Value; Tab(98); .Columns(6).Value
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(3); "-----------------------------------------------------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Total Records:"; Tab(30); grdData(8).Rows
            Printer.Print Tab(3); "-----------------------------------------------------------------------------------------------------------------------------------------------------"
            grdData(9).MoveFirst
            
            Printer.EndDoc
            
    Case 10      'ADVANCE RATE
            Printer.FontSize = 8
            Printer.FontBold = True
            Printer.Print Tab(55); "ADVANCE RATES"
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.FontBold = False
            Printer.Print Tab(5); "------------------------------------------------------------------------------------------------------------------------------------------" _
                                  ; "--------------------------------------------------------------------------------------------------------"
            Printer.FontBold = True
            Printer.Print ; Tab(5); "SERVICE "; Tab(20); "COMMODITY"; Tab(40); "RATE"; Tab(52); "UNIT"; Tab(62); "CUSTOMER" _
                            ; Tab(80); "DISCOUNT"; Tab(95); "TYPE"; Tab(110); "THRESHOLD"; Tab(130); "DIS UNIT"; Tab(145); "LOC"
            Printer.FontBold = False
            Printer.Print Tab(5); "------------------------------------------------------------------------------------------------------------------------------------------" _
                                 ; "--------------------------------------------------------------------------------------------------------"
                                      
            With grdData(10)
                .MoveFirst
    
                For iRec = 0 To .Rows - 1
                    Printer.Print Tab(8); Trim(.Columns(0).Value); Tab(27); .Columns(1).Value; Tab(45); .Columns(2).Value; _
                                  Tab(57); .Columns(3).Value; Tab(73); .Columns(4).Value; Tab(90); .Columns(5).Value; _
                                  Tab(105); .Columns(6).Value; Tab(125); .Columns(7).Value; Tab(145); .Columns(8).Value; Tab(155); .Columns(9).Value
                    .MoveNext
                Next iRec
            End With
            Printer.Print Tab(5); "------------------------------------------------------------------------------------------------------------------------------------------" _
                                 ; "--------------------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Total Records:"; Tab(30); grdData(10).Rows
            Printer.Print Tab(5); "------------------------------------------------------------------------------------------------------------------------------------------" _
                                 ; "--------------------------------------------------------------------------------------------------------"
            
            grdData(8).MoveFirst
            
            Printer.EndDoc
    End Select
    
End Sub

Private Sub txtCust_Validate(Cancel As Boolean)
   If Trim(txtCust) = "" Then Exit Sub
        
    If Not IsNumeric(txtCust) Then
        MsgBox "Customer ID should be numeric !", vbInformation, "CUSTOMER ID"
        Cancel = True
        txtCust = ""
        Exit Sub
    Else
        SqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID='" & Trim(txtCust) & "'"
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 Then
            If dsCUSTOMER_PROFILE.RecordCount = 0 Then
                MsgBox "Invalid CUSTOMER id !", vbInformation, "CUSTOMER ID"
                Cancel = True
                txtCust = ""
                Exit Sub
            End If
        Else
            MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
    End If
End Sub

Private Sub txtEquipRateType_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub cmdSave_Click(Index As Integer)
    Select Case Index
        
        Case 0
                
            If Trim(txtService(0)) = "" Or Trim(txtCommodity(0)) = "" Or Trim(txtRate(0)) = "" Or Trim(txtUnit(0)) = "" Then
                MsgBox "Required Field(s) are empty ! ", vbInformation, "SAVE DOCK RECPT HANDLING RATE"
                Exit Sub
            End If
                        
            SqlStmt = " SELECT * FROM DOCK_RCPT_HANDLING_RATE WHERE SERVICE_CODE='" & Trim(txtService(0)) & "'" _
                    & " AND COMMODITY_CODE='" & Trim(txtCommodity(0)) & "'"
            If Trim(txtCustomer(0)) <> "" Then
                SqlStmt = SqlStmt & " AND CUSTOMER_ID = '" & Trim(txtCustomer(0)) & "'"
            Else
                SqlStmt = SqlStmt & " AND CUSTOMER_ID IS NULL"
            End If
            
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(0))
                    dsSave.FIELDS("UNIT").Value = Trim(txtUnit(0))
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("SERVICE_CODE").Value = Trim(txtService(0))
                    dsSave.FIELDS("COMMODITY_CODE").Value = Trim(txtCommodity(0))
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(0))
                    dsSave.FIELDS("UNIT").Value = Trim(txtUnit(0))
                    If Trim(txtCustomer(0)) <> "" Then
                        dsSave.FIELDS("CUSTOMER_ID").Value = Trim(txtCustomer(0))
                    End If
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            Call DckRcpt

        Case 1       'EQUIPMENT RATE
                        
            If Trim(txtService(1)) = "" Or Trim(txtCommodity(1)) = "" Or Trim(txtEquipRateType) = "" Or Trim(txtEquipType) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "SAVE EQUIPMENT RATE"
                Exit Sub
            End If
                            
            SqlStmt = " SELECT * FROM EQUIPMENT_RATE WHERE SERVICE_CODE='" & Trim(txtService(1)) & "'" _
                    & " AND COMMODITY_CODE='" & Trim(txtCommodity(1)) & "'" _
                    & " AND RATE_TYPE='" & Trim(txtEquipRateType) & "'" _
                    & " AND EQUIPMENT_TYPE='" & Trim(txtEquipType) & "'"
            
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(1))
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("SERVICE_CODE").Value = Trim(txtService(1))
                    dsSave.FIELDS("COMMODITY_CODE").Value = Trim(txtCommodity(1))
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(1))
                    dsSave.FIELDS("RATE_TYPE").Value = Trim(txtEquipRateType)
                    dsSave.FIELDS("EQUIPMENT_TYPE").Value = Trim(txtEquipType)
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            Call EquipRate
            
        Case 2        'EQUIPMENT RATE TYPE
            
            If Trim(txtEquipRType) = "" Or Trim(txtEquipRTypeDes) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "SAVE EQUIPMENT RATE TYPE"
                Exit Sub
            End If
            
            SqlStmt = " SELECT * FROM EQUIPMENT_RATE_TYPE WHERE RATE_TYPE='" & Trim(txtEquipRType) & "'"
            
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("RATE_DESCRIPTION").Value = Trim(txtEquipRTypeDes)
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("RATE_TYPE").Value = Trim(txtEquipRType)
                    dsSave.FIELDS("RATE_DESCRIPTION").Value = Trim(txtEquipRTypeDes)
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            Call EquipRateType
        
        Case 3           'FREE TIME
            
            If Trim(txtCommodity(2)) = "" Or Trim(txtFreeDays) = "" Then
                MsgBox "Required Field(s) are empty !.", vbInformation, "SAVE FREE TIME RATE"
                Exit Sub
            End If
                    
            SqlStmt = " SELECT * FROM FREE_TIME WHERE COMMODITY_CODE='" & Trim(txtCommodity(2)) & "'"
            
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("FREE_DAYS").Value = Trim(txtFreeDays)
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("COMMODITY_CODE").Value = Trim(txtCommodity(2))
                    dsSave.FIELDS("FREE_DAYS").Value = Trim(txtFreeDays)
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            Call FreeTime
            
        Case 4         'HANDLING
                                
            If Trim(txtCommodity(3)) = "" Or Trim(txtService(2)) = "" Or Trim(txtLocation) = "" Or Trim(txtRate(2)) = "" Or Trim(txtUnit(1)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "SAVE HANDLING RATE"
                Exit Sub
            End If
            
            SqlStmt = " SELECT * FROM HANDLING_RATE WHERE COMMODITY_CODE='" & Trim(txtCommodity(3)) & "'" _
                    & " AND SERVICE_CODE='" & Trim(txtService(2)) & "'" _
                    & " AND LOCATION_CODE ='" & Trim(txtLocation) & "'"
            
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(2))
                    dsSave.FIELDS("UNIT").Value = Trim(txtUnit(1))
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("SERVICE_CODE").Value = Trim(txtService(2))
                    dsSave.FIELDS("COMMODITY_CODE").Value = Trim(txtCommodity(3))
                    dsSave.FIELDS("LOCATION_CODE").Value = Trim(txtLocation)
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(2))
                    dsSave.FIELDS("UNIT").Value = Trim(txtUnit(1))
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            Call HandlingRate
                    
        Case 5        'LABOR RATE
            
            If Trim(txtLaborType) = "" Or Trim(txtLabRateType) = "" Or Trim(txtRate(3)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "SAVE LABOR RATE"
                Exit Sub
            End If
            
            SqlStmt = " SELECT * FROM LABOR_RATE WHERE LABOR_TYPE='" & Trim(txtLaborType) & "'" _
                    & " AND RATE_TYPE='" & Trim(txtLabRateType) & "'"
                                
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(3))
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("LABOR_TYPE").Value = Trim(txtLaborType)
                    dsSave.FIELDS("RATE_TYPE").Value = Trim(txtLabRateType)
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(3))
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            Call LaborRate
            
        Case 6       'LABOR RATE TYPE
            
            If Trim(txtLaborRateType) = "" Or Trim(txtLabRateDesc) = "" Then
                MsgBox "Required Fields are empty !", vbInformation, "SAVE LABOR RATE TYPE"
                Exit Sub
            End If
            
            SqlStmt = " SELECT * FROM LABOR_RATE_TYPE WHERE RATE_TYPE='" & Trim(txtLaborRateType) & "'"
                                                    
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("RATE_DESCRIPTION").Value = Trim(txtLabRateDesc)
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("RATE_DESCRIPTION").Value = Trim(txtLabRateDesc)
                    dsSave.FIELDS("RATE_TYPE").Value = Trim(txtLaborRateType)
                   
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            Call LaborRateType
        
        Case 7         'STORAGE RATE
        
            If Trim(txtService(3)) = "" Or Trim(txtCommodity(4)) = "" Or Trim(txtUnit(2)) = "" Or Trim(txtRate(4)) = "" Then
                MsgBox "Required Field(s) are empty !.", vbInformation, "SAVE STORAGE RATE"
                Exit Sub
            End If
            
            SqlStmt = " SELECT * FROM STORAGE_RATE WHERE SERVICE_CODE='" & Trim(txtService(3)) & "'" _
                    & " AND COMMODITY_CODE='" & Trim(txtCommodity(4)) & "'" _
                    & " AND UNIT='" & Trim(txtUnit(2)) & "'"
                    
                                                    
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(4))
                    dsSave.FIELDS("START_DAY").Value = Trim(txtStart)
                    dsSave.FIELDS("END_DAY").Value = Trim(txtEnd)
                    dsSave.FIELDS("DURATION").Value = Trim(txtDuration)
                    dsSave.FIELDS("DURATION_UNIT").Value = Trim(txtDurUnit)
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("SERVICE_CODE").Value = Trim(txtService(3))
                    dsSave.FIELDS("COMMODITY_CODE").Value = Trim(txtCommodity(4))
                    dsSave.FIELDS("UNIT").Value = Trim(txtUnit(2))
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(4))
                    dsSave.FIELDS("START_DAY").Value = Trim(txtStart)
                    dsSave.FIELDS("END_DAY").Value = Trim(txtEnd)
                    dsSave.FIELDS("DURATION").Value = Trim(txtDuration)
                    dsSave.FIELDS("DURATION_UNIT").Value = Trim(txtDurUnit)
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            Call StorageRate
            
        Case 8         'TRUCK HANDLING
            
            If Trim(txtService(4)) = "" Or Trim(txtCommodity(5)) = "" Or Trim(txtRate(5)) = "" Or Trim(txtUnit(3)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "SAVE TRUCK HANDLING RATE"
                Exit Sub
            End If
            
            SqlStmt = " SELECT * FROM TRUCK_HANDLING_RATE WHERE SERVICE_CODE='" & Trim(txtService(4)) & "'" _
                    & " AND COMMODITY_CODE='" & Trim(txtCommodity(5)) & "'"
            
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(5))
                    dsSave.FIELDS("UNIT").Value = Trim(txtUnit(3))
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("SERVICE_CODE").Value = Trim(txtService(4))
                    dsSave.FIELDS("COMMODITY_CODE").Value = Trim(txtCommodity(5))
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(5))
                    dsSave.FIELDS("UNIT").Value = Trim(txtUnit(3))
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            

            Call TrkHandling
        
        Case 9            'VESSEL RATE
            
            If Trim(txtService(5)) = "" Or Trim(txtCommodity(6)) = "" Or Trim(txtRate(6)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "SAVE VESSEL RATE"
                Exit Sub
            End If
            
            SqlStmt = " SELECT * FROM VESSEL_RATE WHERE SERVICE_CODE='" & Trim(txtService(5)) & "'" _
                    & " AND COMMODITY_CODE='" & Trim(txtCommodity(6)) & "'"
            
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("UNIT1").Value = Trim(txtUnit1)
                    dsSave.FIELDS("UNIT2").Value = Trim(txtUnit2)
                    dsSave.FIELDS("MINIMUM_CHARGE").Value = Trim(txtMinCharge)
                    dsSave.FIELDS("MINIMUM_CHARGE_UNIT").Value = Trim(txtMinCUnit)
                    dsSave.FIELDS("SERVICE_RATE").Value = Trim(txtRate(6))
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("SERVICE_CODE").Value = Trim(txtService(5))
                    dsSave.FIELDS("COMMODITY_CODE").Value = Trim(txtCommodity(6))
                    dsSave.FIELDS("SERVICE_RATE").Value = Trim(txtRate(6))
                    dsSave.FIELDS("UNIT1").Value = Trim(txtUnit1)
                    dsSave.FIELDS("UNIT2").Value = Trim(txtUnit2)
                    dsSave.FIELDS("MINIMUM_CHARGE").Value = Trim(txtMinCharge)
                    dsSave.FIELDS("MINIMUM_CHARGE_UNIT").Value = Trim(txtMinCUnit)
                    dsSave.Update
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            

            Call VesselRate
       Case 10         'ADVANCE RATES
            
            If Trim(txtService(6)) = "" Or Trim(txtCommodity(7)) = "" Or Trim(txtRate(7)) = "" Or Trim(txtUnit(4)) = "" Then
                MsgBox "Required Field(s) are empty !", vbInformation, "SAVE ADVANCE RATE"
                Exit Sub
            End If
            
            If Trim(txtCust) = "" And Trim(txtLoc) <> "" Then
                If MsgBox("To save the Location , Customer ID is required." & vbCrLf & " Do you want to save without Location ?", vbQuestion + vbYesNo) = vbNo Then
                    Exit Sub
                End If
            End If
            
            If Trim(txtCust) = "" Then
               SqlStmt = " SELECT * FROM TERMINAL_RATE WHERE SERVICE_CODE='" & Trim(txtService(6)) & "'" _
                       & " AND COMMODITY_CODE='" & Trim(txtCommodity(7)) & "' AND CUSTOMER_ID IS NULL"
            Else
               If Trim(txtLoc) = "" Then
                  SqlStmt = " SELECT * FROM TERMINAL_RATE WHERE SERVICE_CODE='" & Trim(txtService(6)) & "'" _
                          & " AND COMMODITY_CODE='" & Trim(txtCommodity(7)) & "'" _
                          & " AND CUSTOMER_ID='" & Trim(txtCust) & "' AND LOCATION IS NULL"
               Else
                  SqlStmt = " SELECT * FROM TERMINAL_RATE WHERE SERVICE_CODE='" & Trim(txtService(6)) & "'" _
                       & " AND COMMODITY_CODE='" & Trim(txtCommodity(7)) & "'" _
                       & " AND CUSTOMER_ID='" & Trim(txtCust) & "' AND LOCATION='" & Trim(txtLoc) & "'"

               End If

            End If
            
            Set dsSave = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsSave.RecordCount > 0 Then
                    dsSave.edit
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(7))
                    dsSave.FIELDS("UNIT").Value = Trim(txtUnit(4))
                    dsSave.FIELDS("CUSTOMER_ID").Value = Trim(txtCust)
                    dsSave.FIELDS("DISCOUNT").Value = Trim(txtDiscount)
                    dsSave.FIELDS("DISCOUNT_TYPE").Value = Trim(cboDiscountType.Text)
                    dsSave.FIELDS("DISCOUNT_THRESHOLD").Value = Trim(txtThreshold)
                    dsSave.FIELDS("DISCOUNT_UNIT").Value = Trim(cboDiscountUnit)
                    If Trim(txtCust) <> "" Then dsSave.FIELDS("LOCATION").Value = Trim(txtLoc)
                    dsSave.Update
                Else
                    dsSave.AddNew
                    dsSave.FIELDS("SERVICE_CODE").Value = Trim(txtService(6))
                    dsSave.FIELDS("COMMODITY_CODE").Value = Trim(txtCommodity(7))
                    dsSave.FIELDS("RATE").Value = Trim(txtRate(7))
                    dsSave.FIELDS("UNIT").Value = Trim(txtUnit(4))
                    dsSave.FIELDS("CUSTOMER_ID").Value = Trim(txtCust)
                    dsSave.FIELDS("DISCOUNT").Value = Trim(txtDiscount)
                    dsSave.FIELDS("DISCOUNT_TYPE").Value = Trim(cboDiscountType.Text)
                    dsSave.FIELDS("DISCOUNT_THRESHOLD").Value = Trim(txtThreshold)
                    dsSave.FIELDS("DISCOUNT_UNIT").Value = Trim(cboDiscountUnit)
                    If Trim(txtCust) <> "" Then dsSave.FIELDS("LOCATION").Value = Trim(txtLoc)
                    dsSave.Update
                End If
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
            
            

            Call AdvanceRate
            
            
    End Select
    
    
    
End Sub

Private Sub Form_Load()
      
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    On Error GoTo Err_FormLoad
    
   
    
    DoEvents
    SSTab1.Tab = 0
    
    DoEvents
    For iRec = 0 To grdData.UBound
        grdData(iRec).RowHeight = 300
    Next iRec
    Me.Show
    Call DckRcpt
    Call EquipRate
    Call EquipRateType
    Call FreeTime
    Call HandlingRate
    Call LaborRate
    Call LaborRateType
    Call StorageRate
    Call TrkHandling
    Call VesselRate
    Call AdvanceRate
    
    SSTab1.Tab = 0
    
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    ''lblStatus.Caption = "Error Occured."
    On Error GoTo 0
    
End Sub
Sub DckRcpt()
    
    SqlStmt = "SELECT * FROM DOCK_RCPT_HANDLING_RATE ORDER BY SERVICE_CODE,COMMODITY_CODE,CUSTOMER_ID"
    Set dsDockRcptHandlingRate = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsDockRcptHandlingRate.RecordCount > 0 Then
    
        With grdData(0)
            .RemoveAll
            For iRec = 1 To dsDockRcptHandlingRate.RecordCount
                DoEvents
                .AddItem dsDockRcptHandlingRate.FIELDS("SERVICE_CODE").Value + Chr(9) + _
                         dsDockRcptHandlingRate.FIELDS("COMMODITY_CODE").Value + Chr(9) + _
                         Trim("" & dsDockRcptHandlingRate.FIELDS("RATE").Value) + Chr(9) + _
                         Trim("" & dsDockRcptHandlingRate.FIELDS("UNIT").Value) + Chr(9) + _
                         Trim("" & dsDockRcptHandlingRate.FIELDS("CUSTOMER_ID").Value)
                .Refresh
                dsDockRcptHandlingRate.MoveNext
            Next iRec
        End With
    End If
    
End Sub
    
Sub EquipRate()
    SqlStmt = "SELECT * FROM EQUIPMENT_RATE ORDER BY SERVICE_CODE,COMMODITY_CODE"
    Set dsEQUIP_RATE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsEQUIP_RATE.RecordCount > 0 Then
    
        With grdData(1)
            .RemoveAll
            For iRec = 1 To dsEQUIP_RATE.RecordCount
                DoEvents
                .AddItem dsEQUIP_RATE.FIELDS("SERVICE_CODE").Value + Chr(9) + _
                         dsEQUIP_RATE.FIELDS("COMMODITY_CODE").Value + Chr(9) + _
                         Trim("" & dsEQUIP_RATE.FIELDS("RATE").Value) + Chr(9) + _
                         Trim("" & dsEQUIP_RATE.FIELDS("RATE_TYPE").Value) + Chr(9) + _
                         Trim("" & dsEQUIP_RATE.FIELDS("EQUIPMENT_TYPE").Value)
                .Refresh
                dsEQUIP_RATE.MoveNext
            Next iRec
        End With
    End If
    
End Sub
    
Sub EquipRateType()

SqlStmt = "SELECT * FROM EQUIPMENT_RATE_TYPE ORDER BY RATE_TYPE"
    Set dsEQUIP_RATE_TYPE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsEQUIP_RATE_TYPE.RecordCount > 0 Then
    
        With grdData(2)
            .RemoveAll
            For iRec = 1 To dsEQUIP_RATE_TYPE.RecordCount
                DoEvents
                .AddItem dsEQUIP_RATE_TYPE.FIELDS("RATE_TYPE").Value + Chr(9) + _
                         Trim("" & dsEQUIP_RATE_TYPE.FIELDS("RATE_DESCRIPTION").Value)
                 .Refresh
                dsEQUIP_RATE_TYPE.MoveNext
            Next iRec
        End With
    End If
    
End Sub
Sub FreeTime()
    
    SqlStmt = "SELECT * FROM FREE_TIME ORDER BY COMMODITY_CODE"
    Set dsFREE_TIME = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsFREE_TIME.RecordCount > 0 Then
    
        With grdData(3)
            .RemoveAll
            For iRec = 1 To dsFREE_TIME.RecordCount
                DoEvents
                If Len(dsFREE_TIME.FIELDS("COMMODITY_CODE").Value) > 0 Then
                    .AddItem dsFREE_TIME.FIELDS("COMMODITY_CODE").Value + Chr(9) + _
                         Trim("" & dsFREE_TIME.FIELDS("FREE_DAYS").Value)
                    .Refresh
                End If
                dsFREE_TIME.MoveNext
            Next iRec
        End With
    End If
    
End Sub
Sub HandlingRate()
    SqlStmt = "SELECT * FROM HANDLING_RATE ORDER BY SERVICE_CODE,COMMODITY_CODE,LOCATION_CODE"
    Set dsHANDLING_RATE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsHANDLING_RATE.RecordCount > 0 Then
    
        With grdData(4)
            .RemoveAll
            For iRec = 1 To dsHANDLING_RATE.RecordCount
                DoEvents
                .AddItem dsHANDLING_RATE.FIELDS("SERVICE_CODE").Value + Chr(9) + _
                         dsHANDLING_RATE.FIELDS("COMMODITY_CODE").Value + Chr(9) + _
                         dsHANDLING_RATE.FIELDS("LOCATION_CODE").Value + Chr(9) + _
                         Trim("" & dsHANDLING_RATE.FIELDS("RATE").Value) + Chr(9) + _
                         Trim("" & dsHANDLING_RATE.FIELDS("UNIT").Value)
                 .Refresh
                dsHANDLING_RATE.MoveNext
            Next iRec
        End With
    End If
End Sub
Sub LaborRate()
    SqlStmt = "SELECT * FROM LABOR_RATE ORDER BY LABOR_TYPE"
    Set dsLABOR_RATE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsLABOR_RATE.RecordCount > 0 Then
    
        With grdData(5)
            .RemoveAll
            For iRec = 1 To dsLABOR_RATE.RecordCount
                DoEvents
                .AddItem dsLABOR_RATE.FIELDS("LABOR_TYPE").Value + Chr(9) + _
                         dsLABOR_RATE.FIELDS("RATE_TYPE").Value + Chr(9) + _
                         dsLABOR_RATE.FIELDS("RATE").Value
                 .Refresh
                dsLABOR_RATE.MoveNext
            Next iRec
        End With
    End If
End Sub
Sub LaborRateType()
    SqlStmt = "SELECT * FROM LABOR_RATE_TYPE ORDER BY RATE_TYPE"
    Set dsLABOR_RATE_TYPE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsLABOR_RATE_TYPE.RecordCount > 0 Then
    
        With grdData(6)
            .RemoveAll
            For iRec = 1 To dsLABOR_RATE_TYPE.RecordCount
                DoEvents
                .AddItem dsLABOR_RATE_TYPE.FIELDS("RATE_TYPE").Value + Chr(9) + _
                         Trim("" & dsLABOR_RATE_TYPE.FIELDS("RATE_DESCRIPTION").Value)
                 .Refresh
                dsLABOR_RATE_TYPE.MoveNext
            Next iRec
        End With
    End If
End Sub
Sub StorageRate()
    SqlStmt = "SELECT * FROM STORAGE_RATE ORDER BY SERVICE_CODE,COMMODITY_CODE,RATE"
    Set dsSTORAGE_RATE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsSTORAGE_RATE.RecordCount > 0 Then
    
        With grdData(7)
            .RemoveAll
            For iRec = 1 To dsSTORAGE_RATE.RecordCount
                DoEvents
                .AddItem dsSTORAGE_RATE.FIELDS("SERVICE_CODE").Value + Chr(9) + _
                         dsSTORAGE_RATE.FIELDS("COMMODITY_CODE").Value + Chr(9) + _
                         dsSTORAGE_RATE.FIELDS("RATE").Value + Chr(9) + _
                         Trim("" & dsSTORAGE_RATE.FIELDS("UNIT").Value) + Chr(9) + _
                         Trim("" & dsSTORAGE_RATE.FIELDS("DURATION").Value) + Chr(9) + _
                         Trim("" & dsSTORAGE_RATE.FIELDS("DURATION_UNIT").Value) + Chr(9) + _
                         Trim("" & dsSTORAGE_RATE.FIELDS("START_DAY").Value) + Chr(9) + _
                         Trim("" & dsSTORAGE_RATE.FIELDS("END_DAY").Value)
                .Refresh
                         
                dsSTORAGE_RATE.MoveNext
            Next iRec
        End With
    End If
End Sub
Sub TrkHandling()
    SqlStmt = "SELECT * FROM TRUCK_HANDLING_RATE ORDER BY SERVICE_CODE,COMMODITY_CODE"
    Set dsTRK_HDLING_RATE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsTRK_HDLING_RATE.RecordCount > 0 Then
    
        With grdData(8)
            .RemoveAll
            For iRec = 1 To dsTRK_HDLING_RATE.RecordCount
                DoEvents
                .AddItem dsTRK_HDLING_RATE.FIELDS("SERVICE_CODE").Value + Chr(9) + _
                         dsTRK_HDLING_RATE.FIELDS("COMMODITY_CODE").Value + Chr(9) + _
                         Trim("" & dsTRK_HDLING_RATE.FIELDS("RATE").Value) + Chr(9) + _
                         Trim("" & dsTRK_HDLING_RATE.FIELDS("UNIT").Value)
                .Refresh
                dsTRK_HDLING_RATE.MoveNext
            Next iRec
        End With
    End If
End Sub
Sub VesselRate()
    SqlStmt = "SELECT * FROM VESSEL_RATE ORDER BY SERVICE_CODE,COMMODITY_CODE,SERVICE_RATE"
    Set dsVESSEL_RATE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_RATE.RecordCount > 0 Then
    
        With grdData(9)
            .RemoveAll
            For iRec = 1 To dsVESSEL_RATE.RecordCount
                DoEvents
                .AddItem dsVESSEL_RATE.FIELDS("SERVICE_CODE").Value + Chr(9) + _
                         dsVESSEL_RATE.FIELDS("COMMODITY_CODE").Value + Chr(9) + _
                         dsVESSEL_RATE.FIELDS("SERVICE_RATE").Value + Chr(9) + _
                         Trim("" & dsVESSEL_RATE.FIELDS("UNIT1").Value) + Chr(9) + _
                         Trim("" & dsVESSEL_RATE.FIELDS("UNIT2").Value) + Chr(9) + _
                         Trim("" & dsVESSEL_RATE.FIELDS("MINIMUM_CHARGE").Value) + Chr(9) + _
                         Trim("" & dsVESSEL_RATE.FIELDS("MINIMUM_CHARGE_UNIT").Value)
                         
                         
                .Refresh
                dsVESSEL_RATE.MoveNext
            Next iRec
        End With
    End If
End Sub
Sub AdvanceRate()
    SqlStmt = "SELECT * FROM TERMINAL_RATE ORDER BY SERVICE_CODE,COMMODITY_CODE,CUSTOMER_ID"
    Set dsTRM_RATE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsTRM_RATE.RecordCount > 0 Then
    
        With grdData(10)
            .RemoveAll
            For iRec = 1 To dsTRM_RATE.RecordCount
                DoEvents
                .AddItem dsTRM_RATE.FIELDS("SERVICE_CODE").Value + Chr(9) + _
                         dsTRM_RATE.FIELDS("COMMODITY_CODE").Value + Chr(9) + _
                         Trim("" & dsTRM_RATE.FIELDS("RATE").Value) + Chr(9) + _
                         Trim("" & dsTRM_RATE.FIELDS("UNIT").Value) + Chr(9) + _
                         Trim("" & dsTRM_RATE.FIELDS("CUSTOMER_ID").Value) + Chr(9) + _
                         Trim("" & dsTRM_RATE.FIELDS("DISCOUNT").Value) + Chr(9) + _
                         Trim("" & dsTRM_RATE.FIELDS("DISCOUNT_TYPE").Value) + Chr(9) + _
                         Trim("" & dsTRM_RATE.FIELDS("DISCOUNT_THRESHOLD").Value) + Chr(9) + _
                         Trim("" & dsTRM_RATE.FIELDS("DISCOUNT_UNIT").Value) + Chr(9) + _
                         Trim("" & dsTRM_RATE.FIELDS("LOCATION").Value)
                .Refresh
                dsTRM_RATE.MoveNext
            Next iRec
        End With
    End If
End Sub
Private Sub grdData_Click(Index As Integer)
    
    Select Case Index
        
        Case 0
                
            txtService(0) = grdData(Index).Columns(0).Value
            txtCommodity(0) = grdData(Index).Columns(1).Value
            txtRate(0) = grdData(Index).Columns(2).Value
            txtUnit(0) = grdData(Index).Columns(3).Value
            txtCustomer(0) = grdData(Index).Columns(4).Value
            
        Case 1
            
            txtService(1) = grdData(Index).Columns(0).Value
            txtCommodity(1) = grdData(Index).Columns(1).Value
            txtRate(1) = grdData(Index).Columns(2).Value
            txtEquipRateType.Text = grdData(Index).Columns(3).Value
            txtEquipType = grdData(Index).Columns(4).Value
            
        Case 2
            
            txtEquipRType = grdData(Index).Columns(0).Value
            txtEquipRTypeDes = grdData(Index).Columns(1).Value
            
        Case 3
            
            txtCommodity(2) = grdData(Index).Columns(0).Value
            txtFreeDays = grdData(Index).Columns(1).Value
            
        Case 4
            
            txtService(2) = grdData(Index).Columns(0).Value
            txtCommodity(3) = grdData(Index).Columns(1).Value
            txtLocation = grdData(Index).Columns(2).Value
            txtRate(2) = grdData(Index).Columns(3).Value
            txtUnit(1) = grdData(Index).Columns(4).Value
            
        Case 5
            txtLaborType = grdData(Index).Columns(0).Value
            txtLabRateType = grdData(Index).Columns(1).Value
            txtRate(3) = grdData(Index).Columns(2).Value
        Case 6
            
            txtLaborRateType = grdData(Index).Columns(0).Value
            txtLabRateDesc = grdData(Index).Columns(1).Value
            
        Case 7
            
            txtService(3) = grdData(Index).Columns(0).Value
            txtCommodity(4) = grdData(Index).Columns(1).Value
            txtRate(4) = grdData(Index).Columns(2).Value
            txtUnit(2) = grdData(Index).Columns(3).Value
            txtDuration = grdData(Index).Columns(4).Value
            txtDurUnit = grdData(Index).Columns(5).Value
            txtStart = grdData(Index).Columns(6).Value
            txtEnd = grdData(Index).Columns(7).Value
            
        Case 8
        
            txtService(4) = grdData(Index).Columns(0).Value
            txtCommodity(5) = grdData(Index).Columns(1).Value
            txtRate(5) = grdData(Index).Columns(2).Value
            txtUnit(3) = grdData(Index).Columns(3).Value
            
        Case 9
            
            txtService(5) = grdData(Index).Columns(0).Value
            txtCommodity(6) = grdData(Index).Columns(1).Value
            txtRate(6) = grdData(Index).Columns(2).Value
            txtUnit1 = grdData(Index).Columns(3).Value
            txtUnit2 = grdData(Index).Columns(4).Value
            txtMinCharge = grdData(Index).Columns(5).Value
            txtMinCUnit = grdData(Index).Columns(6).Value
        Case 10
        
            txtService(6) = grdData(Index).Columns(0).Value
            txtCommodity(7) = grdData(Index).Columns(1).Value
            txtRate(7) = grdData(Index).Columns(2).Value
            txtUnit(4) = grdData(Index).Columns(3).Value
            txtCust = grdData(Index).Columns(4).Value
            txtDiscount = grdData(Index).Columns(5).Value
            cboDiscountType = grdData(Index).Columns(6).Value
            txtThreshold = grdData(Index).Columns(7).Value
            cboDiscountUnit = grdData(Index).Columns(8).Value
            txtLoc = grdData(Index).Columns(9).Value
    End Select
    
End Sub

Private Sub grdData_KeyPress(Index As Integer, KeyAscii As Integer)
    KeyAscii = 0
End Sub
Private Sub txtCommodity_Validate(Index As Integer, Cancel As Boolean)
    
    If Trim(txtCommodity(Index)) = "" Then Exit Sub
        
    If Not IsNumeric(txtCommodity(Index)) Then
        MsgBox "Commodity Code should be numeric !", vbInformation, "COMMODITY CODE"
        Cancel = True
        txtCommodity(Index) = ""
        Exit Sub
    Else
        SqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE='" & Trim(txtCommodity(Index)) & "'"
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 Then
            If dsCOMMODITY_PROFILE.RecordCount = 0 Then
                MsgBox "Invalid Commodity Code !", vbInformation, "COMMODITY CODE"
                Cancel = True
                txtCommodity(Index) = ""
                Exit Sub
            End If
        Else
            MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
    End If
End Sub
Private Sub txtDuration_Validate(Cancel As Boolean)
    
    If Trim(txtDuration) = "" Then Exit Sub
        
    If Not IsNumeric(txtDuration) Then
        MsgBox "Expecting Numbers !", vbInformation, "DURATION"
        txtDuration = ""
        Cancel = True
    End If
End Sub
Private Sub txtDurUnit_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub
Private Sub txtEnd_Validate(Cancel As Boolean)
    
    If Trim(txtEnd) = "" Then Exit Sub
    
    If Not IsNumeric(txtEnd) Then
        MsgBox "Expecting Numbers !", vbInformation, "END DAY"
        txtEnd = ""
        Cancel = True
    End If
End Sub

Private Sub txtEquipRateType_Validate(Cancel As Boolean)
    If Trim(txtEquipRateType) = "" Then Exit Sub
        
    SqlStmt = "SELECT * FROM EQUIPMENT_RATE_TYPE WHERE RATE_TYPE='" & Trim(txtEquipRateType) & "'"
    Set dsEQUIP_RATE_TYPE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If dsEQUIP_RATE_TYPE.RecordCount = 0 Then
            MsgBox "Invalid Equipment Rate Type !", vbInformation, "EQUIPMENT RATE TYPE"
            Cancel = True
            txtEquipRateType = ""
            Exit Sub
        End If
    Else
        MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
        OraDatabase.LastServerErrReset
        Exit Sub
    End If
    
End Sub

Private Sub txtEquipRType_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub



Private Sub txtEquipRTypeDes_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtEquipType_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtFreeDays_Validate(Cancel As Boolean)
    
    If Trim(txtFreeDays) = "" Then Exit Sub
    
    If Not IsNumeric(txtFreeDays) Then
        MsgBox "Expecting Numbers !", vbInformation, "FREE DAYS"
        txtFreeDays = ""
        Cancel = True
    End If
End Sub

Private Sub txtLaborRateType_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtLaborType_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtLabRateDesc_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub


Private Sub txtLabRateType_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtLabRateType_Validate(Cancel As Boolean)
    If Trim(txtLabRateType) = "" Then Exit Sub
    
    SqlStmt = "SELECT * FROM LABOR_RATE_TYPE WHERE RATE_TYPE='" & Trim(txtLabRateType) & "'"
    Set dsLABOR_RATE_TYPE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 Then
            If dsLABOR_RATE_TYPE.RecordCount = 0 Then
                MsgBox "Invalid LABOR RATE TYPE !", vbInformation, "LABOR RATE TYPE"
                Cancel = True
                txtLabRateType = ""
                Exit Sub
            End If
        Else
            MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
    
End Sub

Private Sub txtLocation_Validate(Cancel As Boolean)
    
    If Trim(txtLocation) = "" Then Exit Sub
    
    If Not IsNumeric(txtLocation) Then
        MsgBox "Location Code should be numeric !", vbInformation, "LOCATION"
        Cancel = True
        txtLocation = ""
        Exit Sub
    End If
End Sub

Private Sub txtMinCUnit_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtRate_Validate(Index As Integer, Cancel As Boolean)
    
    If Trim(txtRate(Index)) = "" Then Exit Sub
    
    If Not IsNumeric(txtRate(Index)) Then
        MsgBox "Rate be numeric !", vbInformation, "RATE"
        Cancel = True
        txtRate(Index) = ""
        Exit Sub
    End If
End Sub

Private Sub txtService_Validate(Index As Integer, Cancel As Boolean)
    
    If Trim(txtService(Index)) = "" Then Exit Sub
    
    If Not IsNumeric(txtService(Index)) Then
        MsgBox "Service Code should be numeric !", vbInformation, "COMMODITY CODE"
        Cancel = True
        txtService(Index) = ""
        Exit Sub
    Else
        SqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE='" & Trim(txtService(Index)) & "'"
        Set dsSERVICE_CATEGORY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 Then
            If dsSERVICE_CATEGORY.RecordCount = 0 Then
                MsgBox "Invalid Service Code !", vbInformation, "SERVICE CODE"
                Cancel = True
                txtService(Index) = ""
                Exit Sub
            End If
        Else
            MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
    End If
End Sub

Private Sub txtStart_Validate(Cancel As Boolean)
    If Trim(txtStart) = "" Then Exit Sub
    
    If Not IsNumeric(txtStart) Then
        MsgBox "Start day should be numeric !", vbInformation, "START DAY"
        Cancel = True
        txtStart = ""
        Exit Sub
    End If
End Sub

Private Sub txtThreshold_Validate(Cancel As Boolean)
   If Trim(txtThreshold) = "" Then Exit Sub
    
    If Not IsNumeric(txtThreshold) Then
        MsgBox "Discount Threshold should be numeric !", vbInformation, "Discount Threshold"
        Cancel = True
        txtThreshold = ""
        Exit Sub
    End If
End Sub

Private Sub txtUnit_KeyPress(Index As Integer, KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtUnit_Validate(Index As Integer, Cancel As Boolean)
    
    If Trim(txtUnit(Index)) = "" Then Exit Sub
    
    SqlStmt = "SELECT * FROM UNITS WHERE UOM='" & Trim(txtUnit(Index)) & "'"
    Set dsUnit = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If dsUnit.RecordCount = 0 Then
            MsgBox "Invalid Unit !", vbInformation, "UNIT"
            Cancel = True
            txtUnit(Index) = ""
            Exit Sub
        End If
    Else
        MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
        OraDatabase.LastServerErrReset
        Exit Sub
    End If
End Sub

Private Sub txtUnit1_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtUnit2_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub
