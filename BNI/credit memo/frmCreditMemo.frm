VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCTL.OCX"
Object = "{BDC217C8-ED16-11CD-956C-0000C04E4C0A}#1.1#0"; "TABCTL32.OCX"
Begin VB.Form frmCreditMemo 
   BackColor       =   &H00FFFFC0&
   Caption         =   "Credit Memo"
   ClientHeight    =   10590
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   14040
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   ScaleHeight     =   12237.71
   ScaleMode       =   0  'User
   ScaleWidth      =   14040
   StartUpPosition =   3  'Windows Default
   Begin TabDlg.SSTab sstabDCM 
      Height          =   5595
      Left            =   360
      TabIndex        =   14
      Top             =   4680
      Width           =   13395
      _ExtentX        =   23627
      _ExtentY        =   9869
      _Version        =   393216
      TabOrientation  =   2
      Tabs            =   2
      TabHeight       =   520
      BackColor       =   -2147483638
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Arial"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      TabCaption(0)   =   "Credit Memo"
      TabPicture(0)   =   "frmCreditMemo.frx":0000
      Tab(0).ControlEnabled=   -1  'True
      Tab(0).Control(0)=   "lblCMInvoiceNum"
      Tab(0).Control(0).Enabled=   0   'False
      Tab(0).Control(1)=   "Label1"
      Tab(0).Control(1).Enabled=   0   'False
      Tab(0).Control(2)=   "lblServiceDate"
      Tab(0).Control(2).Enabled=   0   'False
      Tab(0).Control(3)=   "lblDescription"
      Tab(0).Control(3).Enabled=   0   'False
      Tab(0).Control(4)=   "lblCMNum"
      Tab(0).Control(4).Enabled=   0   'False
      Tab(0).Control(5)=   "lblCMDate"
      Tab(0).Control(5).Enabled=   0   'False
      Tab(0).Control(6)=   "lblServiceCode"
      Tab(0).Control(6).Enabled=   0   'False
      Tab(0).Control(7)=   "lblAssetCode"
      Tab(0).Control(7).Enabled=   0   'False
      Tab(0).Control(8)=   "lblCommCode"
      Tab(0).Control(8).Enabled=   0   'False
      Tab(0).Control(9)=   "lblAmount"
      Tab(0).Control(9).Enabled=   0   'False
      Tab(0).Control(10)=   "lbrGLCode"
      Tab(0).Control(10).Enabled=   0   'False
      Tab(0).Control(11)=   "ssgrdCM"
      Tab(0).Control(11).Enabled=   0   'False
      Tab(0).Control(12)=   "cmdDone"
      Tab(0).Control(12).Enabled=   0   'False
      Tab(0).Control(13)=   "txtCMInvoiceNum"
      Tab(0).Control(13).Enabled=   0   'False
      Tab(0).Control(14)=   "txtAdjTotal"
      Tab(0).Control(14).Enabled=   0   'False
      Tab(0).Control(15)=   "cmdNewCreateCM"
      Tab(0).Control(15).Enabled=   0   'False
      Tab(0).Control(16)=   "txtServiceDate"
      Tab(0).Control(16).Enabled=   0   'False
      Tab(0).Control(17)=   "txtDescription"
      Tab(0).Control(17).Enabled=   0   'False
      Tab(0).Control(18)=   "cmdPrint"
      Tab(0).Control(18).Enabled=   0   'False
      Tab(0).Control(19)=   "cmdRetrieve"
      Tab(0).Control(19).Enabled=   0   'False
      Tab(0).Control(20)=   "cmdDeleteCancel"
      Tab(0).Control(20).Enabled=   0   'False
      Tab(0).Control(21)=   "cmdExit"
      Tab(0).Control(21).Enabled=   0   'False
      Tab(0).Control(22)=   "txtCMNum"
      Tab(0).Control(22).Enabled=   0   'False
      Tab(0).Control(23)=   "txtCMDate"
      Tab(0).Control(23).Enabled=   0   'False
      Tab(0).Control(24)=   "cmdEditSave"
      Tab(0).Control(24).Enabled=   0   'False
      Tab(0).Control(25)=   "txtServiceCode"
      Tab(0).Control(25).Enabled=   0   'False
      Tab(0).Control(26)=   "txtAssetCode"
      Tab(0).Control(26).Enabled=   0   'False
      Tab(0).Control(27)=   "txtCommCode"
      Tab(0).Control(27).Enabled=   0   'False
      Tab(0).Control(28)=   "txtAmount"
      Tab(0).Control(28).Enabled=   0   'False
      Tab(0).Control(29)=   "txtMemoNum"
      Tab(0).Control(29).Enabled=   0   'False
      Tab(0).Control(30)=   "txtGLCode"
      Tab(0).Control(30).Enabled=   0   'False
      Tab(0).ControlCount=   31
      TabCaption(1)   =   "Debit Memo"
      TabPicture(1)   =   "frmCreditMemo.frx":001C
      Tab(1).ControlEnabled=   0   'False
      Tab(1).Control(0)=   "txtGLCodeDM"
      Tab(1).Control(1)=   "txtMemoNumDM"
      Tab(1).Control(2)=   "cmdCust"
      Tab(1).Control(3)=   "txtCustomerNameDM"
      Tab(1).Control(4)=   "txtCustomerIDDM"
      Tab(1).Control(5)=   "txtAmountDM"
      Tab(1).Control(6)=   "txtCommCodeDM"
      Tab(1).Control(7)=   "txtAssetCodeDM"
      Tab(1).Control(8)=   "txtServiceCodeDM"
      Tab(1).Control(9)=   "cmdEditSaveDM"
      Tab(1).Control(10)=   "txtDMDate"
      Tab(1).Control(11)=   "txtDMNum"
      Tab(1).Control(12)=   "cmdExit1"
      Tab(1).Control(13)=   "cmdDeleteCancelDM"
      Tab(1).Control(14)=   "cmdRetrieveDM"
      Tab(1).Control(15)=   "cmdPrintDM"
      Tab(1).Control(16)=   "txtDescriptionDM"
      Tab(1).Control(17)=   "txtServiceDateDM"
      Tab(1).Control(18)=   "cmdNewCreateDM"
      Tab(1).Control(19)=   "txtDMInvoiceNum"
      Tab(1).Control(20)=   "cmdDoneDM"
      Tab(1).Control(21)=   "ssgrdDM"
      Tab(1).Control(22)=   "lbGLCodelDM"
      Tab(1).Control(23)=   "lblCustomerIDDM"
      Tab(1).Control(24)=   "lblAmountDM"
      Tab(1).Control(25)=   "lblCommCodeDM"
      Tab(1).Control(26)=   "lblAssetCodeDM"
      Tab(1).Control(27)=   "lblServiceCodeDM"
      Tab(1).Control(28)=   "lblDMDate"
      Tab(1).Control(29)=   "lblDMNum"
      Tab(1).Control(30)=   "lblDescriptionDM"
      Tab(1).Control(31)=   "lblServiceDateDM"
      Tab(1).Control(32)=   "lblDMInvoiceNum"
      Tab(1).ControlCount=   33
      Begin VB.TextBox txtGLCode 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   8160
         MaxLength       =   10
         TabIndex        =   28
         Tag             =   "C"
         Top             =   1080
         Width           =   2265
      End
      Begin VB.TextBox txtGLCodeDM 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   -66480
         TabIndex        =   81
         Tag             =   "D"
         Top             =   2760
         Width           =   2265
      End
      Begin VB.TextBox txtMemoNumDM 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   -69150
         TabIndex        =   77
         Tag             =   "D"
         Top             =   120
         Width           =   2265
      End
      Begin VB.TextBox txtMemoNum 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   5880
         TabIndex        =   18
         Tag             =   "19"
         Top             =   150
         Width           =   2265
      End
      Begin VB.CommandButton cmdCust 
         Caption         =   "u"
         BeginProperty Font 
            Name            =   "Marlett"
            Size            =   12
            Charset         =   2
            Weight          =   500
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   285
         Left            =   -69660
         TabIndex        =   58
         Top             =   990
         Width           =   345
      End
      Begin VB.TextBox txtCustomerNameDM 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   -69285
         Locked          =   -1  'True
         TabIndex        =   59
         Tag             =   "D"
         Top             =   990
         Width           =   5025
      End
      Begin VB.TextBox txtCustomerIDDM 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   -70755
         Locked          =   -1  'True
         TabIndex        =   57
         Tag             =   "DNCustomer ID"
         Top             =   990
         Width           =   1095
      End
      Begin VB.TextBox txtAmountDM 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   -70740
         TabIndex        =   67
         Tag             =   "DNAmount"
         Top             =   2730
         Width           =   2265
      End
      Begin VB.TextBox txtCommCodeDM 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   -70740
         TabIndex        =   61
         Tag             =   "DNCommodity Code"
         Top             =   1410
         Width           =   2265
      End
      Begin VB.TextBox txtAssetCodeDM 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   -66510
         MaxLength       =   4
         TabIndex        =   63
         Tag             =   "D"
         Top             =   1410
         Width           =   2265
      End
      Begin VB.TextBox txtServiceCodeDM 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   -70740
         TabIndex        =   53
         Tag             =   "DNService Code"
         Top             =   570
         Width           =   2265
      End
      Begin VB.CommandButton cmdEditSaveDM 
         Caption         =   "&Edit"
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
         Left            =   -72825
         TabIndex        =   69
         Top             =   3120
         Width           =   1245
      End
      Begin VB.TextBox txtDMDate 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   -64650
         Locked          =   -1  'True
         TabIndex        =   51
         Tag             =   "DDDebit Memo Date"
         Top             =   120
         Width           =   2115
      End
      Begin VB.TextBox txtDMNum 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Height          =   285
         Left            =   -73440
         TabIndex        =   47
         Tag             =   "D"
         Top             =   120
         Width           =   2265
      End
      Begin VB.CommandButton cmdExit1 
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
         Left            =   -64950
         TabIndex        =   75
         Top             =   3120
         Width           =   1245
      End
      Begin VB.CommandButton cmdDeleteCancelDM 
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
         Left            =   -69675
         TabIndex        =   71
         Top             =   3120
         Width           =   1245
      End
      Begin VB.CommandButton cmdRetrieveDM 
         Caption         =   "&Retrieve"
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
         Left            =   -71250
         TabIndex        =   70
         Top             =   3120
         Width           =   1245
      End
      Begin VB.CommandButton cmdPrintDM 
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
         Left            =   -68100
         TabIndex        =   72
         Top             =   3120
         Width           =   1245
      End
      Begin VB.TextBox txtDescriptionDM 
         Appearance      =   0  'Flat
         Height          =   795
         Left            =   -70740
         MaxLength       =   1000
         MultiLine       =   -1  'True
         ScrollBars      =   2  'Vertical
         TabIndex        =   65
         Tag             =   "DSDescription"
         Top             =   1830
         Width           =   6495
      End
      Begin VB.TextBox txtServiceDateDM 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   -66495
         TabIndex        =   55
         Tag             =   "DDService Date"
         Top             =   570
         Width           =   2265
      End
      Begin VB.CommandButton cmdNewCreateDM 
         Caption         =   "&Create DM"
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
         Left            =   -74400
         TabIndex        =   68
         Top             =   3120
         Width           =   1245
      End
      Begin VB.TextBox txtDMInvoiceNum 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   -69150
         TabIndex        =   49
         Tag             =   "D"
         Top             =   120
         Visible         =   0   'False
         Width           =   2265
      End
      Begin VB.CommandButton cmdDoneDM 
         Caption         =   "D&one"
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
         Left            =   -66525
         TabIndex        =   73
         Top             =   3120
         Width           =   1245
      End
      Begin VB.TextBox txtAmount 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   4310
         MaxLength       =   20
         TabIndex        =   34
         Tag             =   "CNAmount"
         Top             =   2760
         Width           =   2265
      End
      Begin VB.TextBox txtCommCode 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   4310
         MaxLength       =   10
         TabIndex        =   30
         Tag             =   "CNCommodity Code"
         Top             =   1440
         Width           =   2265
      End
      Begin VB.TextBox txtAssetCode 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   4310
         MaxLength       =   4
         TabIndex        =   26
         Tag             =   "C"
         Top             =   1020
         Width           =   2265
      End
      Begin VB.TextBox txtServiceCode 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   4310
         MaxLength       =   10
         TabIndex        =   22
         Tag             =   "CNService Code"
         Top             =   600
         Width           =   2265
      End
      Begin VB.CommandButton cmdEditSave 
         Caption         =   "&Edit"
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
         Left            =   2225
         TabIndex        =   39
         Top             =   3150
         Width           =   1245
      End
      Begin VB.TextBox txtCMDate 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   10400
         Locked          =   -1  'True
         TabIndex        =   20
         Tag             =   "CDCredit Memo Date"
         Top             =   150
         Width           =   2115
      End
      Begin VB.TextBox txtCMNum 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Height          =   285
         Left            =   1605
         TabIndex        =   16
         Tag             =   "C"
         Top             =   150
         Width           =   2265
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
         Left            =   10100
         TabIndex        =   44
         Top             =   3150
         Width           =   1245
      End
      Begin VB.CommandButton cmdDeleteCancel 
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
         Left            =   5375
         TabIndex        =   41
         Top             =   3150
         Width           =   1245
      End
      Begin VB.CommandButton cmdRetrieve 
         Caption         =   "&Retrieve"
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
         Left            =   3800
         TabIndex        =   40
         Top             =   3150
         Width           =   1245
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
         Left            =   6950
         TabIndex        =   42
         Top             =   3150
         Width           =   1245
      End
      Begin VB.TextBox txtDescription 
         Appearance      =   0  'Flat
         Height          =   795
         Left            =   4310
         MaxLength       =   1000
         MultiLine       =   -1  'True
         ScrollBars      =   2  'Vertical
         TabIndex        =   32
         Tag             =   "CSDescription"
         Top             =   1860
         Width           =   6495
      End
      Begin VB.TextBox txtServiceDate 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   8160
         MaxLength       =   20
         TabIndex        =   24
         Tag             =   "CDService Date"
         Top             =   600
         Width           =   2265
      End
      Begin VB.CommandButton cmdNewCreateCM 
         Caption         =   "&Create CM"
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
         Left            =   650
         TabIndex        =   38
         Top             =   3150
         Width           =   1245
      End
      Begin VB.TextBox txtAdjTotal 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   9140
         MaxLength       =   20
         TabIndex        =   36
         Tag             =   "C"
         Top             =   2760
         Width           =   2265
      End
      Begin VB.TextBox txtCMInvoiceNum 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   5900
         TabIndex        =   37
         Tag             =   "C"
         Top             =   150
         Visible         =   0   'False
         Width           =   2265
      End
      Begin VB.CommandButton cmdDone 
         Caption         =   "D&one"
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
         Left            =   8525
         TabIndex        =   43
         Top             =   3150
         Width           =   1245
      End
      Begin SSDataWidgets_B.SSDBGrid ssgrdCM 
         Height          =   1785
         Left            =   450
         TabIndex        =   45
         Top             =   3600
         Width           =   12795
         _Version        =   196616
         DataMode        =   2
         FieldSeparator  =   ","
         Col.Count       =   6
         AllowUpdate     =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowDragDrop   =   0   'False
         SelectTypeCol   =   0
         SelectTypeRow   =   0
         RowHeight       =   423
         Columns.Count   =   6
         Columns(0).Width=   2752
         Columns(0).Caption=   "Memo Number"
         Columns(0).Name =   "Billing Number"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   2752
         Columns(1).Caption=   "Date"
         Columns(1).Name =   "Date"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   2752
         Columns(2).Caption=   "Service Code"
         Columns(2).Name =   "Service Code"
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   8255
         Columns(3).Caption=   "Description"
         Columns(3).Name =   "Description"
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   400
         Columns(4).Width=   2752
         Columns(4).Caption=   "Status"
         Columns(4).Name =   "Status"
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         Columns(5).Width=   2752
         Columns(5).Caption=   "Amount"
         Columns(5).Name =   "Amount"
         Columns(5).DataField=   "Column 5"
         Columns(5).DataType=   8
         Columns(5).FieldLen=   256
         _ExtentX        =   22569
         _ExtentY        =   3149
         _StockProps     =   79
         Caption         =   "Credit Memo History"
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
      Begin SSDataWidgets_B.SSDBGrid ssgrdDM 
         Height          =   1815
         Left            =   -74640
         TabIndex        =   76
         Top             =   3600
         Width           =   12855
         _Version        =   196616
         DataMode        =   2
         FieldSeparator  =   ","
         Col.Count       =   6
         AllowUpdate     =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowDragDrop   =   0   'False
         SelectTypeCol   =   0
         SelectTypeRow   =   0
         RowHeight       =   423
         Columns.Count   =   6
         Columns(0).Width=   2752
         Columns(0).Caption=   "Memo Number"
         Columns(0).Name =   "Billing Number"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   2752
         Columns(1).Caption=   "Date"
         Columns(1).Name =   "Date"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   2752
         Columns(2).Caption=   "Service Code"
         Columns(2).Name =   "Service Code"
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   8255
         Columns(3).Caption=   "Description"
         Columns(3).Name =   "Description"
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   400
         Columns(4).Width=   2752
         Columns(4).Caption=   "Status"
         Columns(4).Name =   "Status"
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         Columns(5).Width=   2752
         Columns(5).Caption=   "Amount"
         Columns(5).Name =   "Amount"
         Columns(5).DataField=   "Column 5"
         Columns(5).DataType=   8
         Columns(5).FieldLen=   256
         _ExtentX        =   22675
         _ExtentY        =   3201
         _StockProps     =   79
         Caption         =   "Credit/Debit Memo History"
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
      Begin VB.Label lbrGLCode 
         Alignment       =   2  'Center
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "GL Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   7080
         TabIndex        =   27
         Top             =   1080
         Width           =   975
      End
      Begin VB.Label lbGLCodelDM 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "GL Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -67560
         TabIndex        =   80
         Top             =   2760
         Width           =   915
      End
      Begin VB.Label lblCustomerIDDM 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Customer ID:"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -72030
         TabIndex        =   56
         Top             =   1020
         Width           =   1080
      End
      Begin VB.Label lblAmountDM 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Amount :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -71670
         TabIndex        =   66
         Top             =   2760
         Width           =   735
      End
      Begin VB.Label lblCommCodeDM 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -72405
         TabIndex        =   60
         Top             =   1410
         Width           =   1470
      End
      Begin VB.Label lblAssetCodeDM 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Asset Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -67680
         TabIndex        =   62
         Top             =   1470
         Width           =   975
      End
      Begin VB.Label lblServiceCodeDM 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -72075
         TabIndex        =   52
         Top             =   600
         Width           =   1140
      End
      Begin VB.Label lblDMDate 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Date :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -65250
         TabIndex        =   50
         Top             =   150
         Width           =   480
      End
      Begin VB.Label lblDMNum 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Bill Number :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -74610
         TabIndex        =   46
         Top             =   150
         Width           =   1095
      End
      Begin VB.Label lblDescriptionDM 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Description :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -71955
         TabIndex        =   64
         Top             =   1830
         Width           =   1020
      End
      Begin VB.Label lblServiceDateDM 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service Date :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -67830
         TabIndex        =   54
         Top             =   600
         Width           =   1110
      End
      Begin VB.Label lblDMInvoiceNum 
         Alignment       =   2  'Center
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Memo Number :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   -70575
         TabIndex        =   48
         Top             =   150
         Width           =   1305
      End
      Begin VB.Label lblAmount 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Amount :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   3380
         TabIndex        =   33
         Top             =   2790
         Width           =   735
      End
      Begin VB.Label lblCommCode 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   2645
         TabIndex        =   29
         Top             =   1440
         Width           =   1470
      End
      Begin VB.Label lblAssetCode 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Asset Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   3140
         TabIndex        =   25
         Top             =   1080
         Width           =   975
      End
      Begin VB.Label lblServiceCode 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   2975
         TabIndex        =   21
         Top             =   630
         Width           =   1140
      End
      Begin VB.Label lblCMDate 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Date :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   9800
         TabIndex        =   19
         Top             =   180
         Width           =   480
      End
      Begin VB.Label lblCMNum 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Bill Number :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   435
         TabIndex        =   15
         Top             =   180
         Width           =   1095
      End
      Begin VB.Label lblDescription 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Description :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   3095
         TabIndex        =   31
         Top             =   1860
         Width           =   1020
      End
      Begin VB.Label lblServiceDate 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service Date :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   6840
         TabIndex        =   23
         Top             =   630
         Width           =   1110
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Adjusted Invoice Total :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   7130
         TabIndex        =   35
         Top             =   2790
         Width           =   1860
      End
      Begin VB.Label lblCMInvoiceNum 
         Alignment       =   2  'Center
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Memo Number :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   4470
         TabIndex        =   17
         Top             =   180
         Width           =   1305
      End
   End
   Begin MSComctlLib.StatusBar stbarCM 
      Align           =   2  'Align Bottom
      Height          =   285
      Left            =   0
      TabIndex        =   74
      Top             =   10305
      Width           =   14040
      _ExtentX        =   24765
      _ExtentY        =   503
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
            AutoSize        =   1
            Object.Width           =   24236
         EndProperty
      EndProperty
   End
   Begin Crystal.CrystalReport crCM 
      Left            =   6270
      Top             =   4110
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin VB.Frame Frame1 
      BackColor       =   &H00FFFFC0&
      Caption         =   "INVOICE"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   4485
      Left            =   315
      TabIndex        =   0
      Top             =   90
      Width           =   13425
      Begin VB.TextBox txtInvoiceTotal 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   4230
         Locked          =   -1  'True
         TabIndex        =   12
         Top             =   1620
         Width           =   2115
      End
      Begin VB.TextBox txtVesselName 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   5430
         Locked          =   -1  'True
         TabIndex        =   10
         Top             =   1230
         Width           =   5325
      End
      Begin VB.TextBox txtVesselNum 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   4230
         Locked          =   -1  'True
         TabIndex        =   9
         Top             =   1230
         Width           =   1095
      End
      Begin VB.TextBox txtInvoiceNum 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   2010
         TabIndex        =   2
         Tag             =   "NInvoice Number"
         Top             =   330
         Width           =   2265
      End
      Begin VB.TextBox txtInvoiceDate 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   10860
         Locked          =   -1  'True
         TabIndex        =   4
         Top             =   330
         Width           =   2115
      End
      Begin VB.TextBox txtCustomerID 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   4230
         Locked          =   -1  'True
         TabIndex        =   6
         Top             =   750
         Width           =   1095
      End
      Begin VB.TextBox txtCustomerName 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   5430
         Locked          =   -1  'True
         TabIndex        =   7
         Top             =   750
         Width           =   5325
      End
      Begin SSDataWidgets_B.SSDBGrid ssgrdInvoice 
         Height          =   2295
         Left            =   420
         TabIndex        =   13
         Top             =   2040
         Width           =   12585
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
         Col.Count       =   6
         AllowUpdate     =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowDragDrop   =   0   'False
         RowHeight       =   503
         ExtraHeight     =   26
         Columns.Count   =   6
         Columns(0).Width=   2593
         Columns(0).Caption=   "SERVICE DATE"
         Columns(0).Name =   "SERVICE DATE"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   2514
         Columns(1).Caption=   "SERVICE CODE"
         Columns(1).Name =   "SERVICE CODE"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   6985
         Columns(2).Caption=   "DESCRIPTION"
         Columns(2).Name =   "DESCRIPTION"
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   3096
         Columns(3).Caption=   "AMOUNT"
         Columns(3).Name =   "AMOUNT"
         Columns(3).Alignment=   1
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         Columns(4).Width=   2249
         Columns(4).Caption=   "ASSET CODE"
         Columns(4).Name =   "ASSET CODE"
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         Columns(5).Width=   3757
         Columns(5).Caption=   "COMMODITY CODE"
         Columns(5).Name =   "COMMODITY CODE"
         Columns(5).DataField=   "Column 5"
         Columns(5).DataType=   8
         Columns(5).FieldLen=   256
         _ExtentX        =   22199
         _ExtentY        =   4048
         _StockProps     =   79
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
      End
      Begin VB.Label lblInvoiceTotal 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Invoice Total :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   3000
         TabIndex        =   11
         Top             =   1650
         Width           =   1125
      End
      Begin VB.Label lblVessel 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Vessel :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   3510
         TabIndex        =   8
         Top             =   1260
         Width           =   615
      End
      Begin VB.Label lblInvoiceNum 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Invoice Number :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   570
         TabIndex        =   1
         Top             =   360
         Width           =   1350
      End
      Begin VB.Label lblInvoiceDate 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Invoice Date :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   9600
         TabIndex        =   3
         Top             =   360
         Width           =   1080
      End
      Begin VB.Label lblCustomerID 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Customer ID:"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   3045
         TabIndex        =   5
         Top             =   780
         Width           =   1080
      End
   End
   Begin VB.Label Label3 
      Caption         =   "Label3"
      Height          =   495
      Left            =   6480
      TabIndex        =   79
      Top             =   5040
      Width           =   1215
   End
   Begin VB.Label Label2 
      Caption         =   "Label2"
      Height          =   495
      Left            =   6480
      TabIndex        =   78
      Top             =   5040
      Width           =   1215
   End
End
Attribute VB_Name = "frmCreditMemo"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False


Option Explicit
Dim sErrPos As String
Dim sMode As String
Dim sModeDM As String
Dim sModule As String
Dim lCurrCMNum As Long
Dim lCurrDMNum As Long
Dim lCurrInvNum As Long
Dim dblCurrInvCrTot As Double
Dim dblCurrCMAmt As Double
Dim bIsDisplaying As Boolean
Dim bIsClearing As Boolean
Dim lUser As Long
Dim bChkAddNew As Boolean
Dim bPreCM As Boolean
Dim bPreDM As Boolean
Dim sActiveTab As String
                  '2258 4/2/2007 Rudy: making this contol invisible and disabled, so shouldn't run
Private Sub cmdCust_Click()
Dim frmCustPV As frmPVps
Dim sSql As String
Dim sRetVal As String
  sSql = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_NAME"
  Set frmCustPV = New frmPVps
  frmCustPV.fsSql = sSql
  frmCustPV.fiPos = 8
  frmCustPV.Show 1
  sRetVal = frmCustPV.fsVal
  txtCustomerIDDM.Text = Mid(sRetVal, 1, InStr(sRetVal, " ") - 1)
  txtCustomerNameDM.Text = Trim(Mid(sRetVal, InStr(sRetVal, " ") + 1))
  Unload frmCustPV

End Sub

Private Sub cmdDeleteCancel_Click()
Dim sSql As String

On Error GoTo ErrorHandle

If cmdDeleteCancel.Caption = "&Delete" Then
    stbarCM.SimpleText = "Deleting..."
    'Delete Credit Memo
    If MsgBox("Delete this Record?", vbYesNo, "Pre-Credit Memo") = vbNo Then Exit Sub
    MB
    sSql = "DELETE FROM BILLING WHERE BILLING_NUM = " & lCurrCMNum & " and BILLING_TYPE = 'CM'"
    OraDatabase.DbExecuteSQL (sSql)
    MsgBox "Record Deleted."
    
    'clear screen
    cmdDone_Click
    MN
ElseIf cmdDeleteCancel.Caption = "&Cancel" Then
    stbarCM.SimpleText = "Cancel..."
    If lCurrCMNum <> 0 Then
        sMode = ""
        bIsDisplaying = True
        If Not DispCM(lCurrCMNum) Then
            MsgBox "Unexpected error occured.Contact BNI Administrator"
            sMode = ""
            bIsDisplaying = False
            cmdDone.Value = True
            MN
            Exit Sub
        End If
        ContAfterRetrieve
        bIsDisplaying = False
    
    Else
        If sMode <> "ADD" Then
            MsgBox "Unexpected error occured. Contact BNI Administrator."
        End If
        cmdDone_Click
    End If
End If

MN
Exit Sub

ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If

Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
        
        'Disable the use of the error handler class
        'because we don't have the source code  -- LFW, 8/18/03
        MsgBox (gsMsg1 + gsMsg2)
        
 '      gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdDelete_Click"
End Select
MN
End Sub

Private Sub cmdDeleteCancelDM_Click()
Dim sSql As String

On Error GoTo ErrorHandle

If cmdDeleteCancelDM.Caption = "&Delete" Then
    stbarCM.SimpleText = "Deleting Pre-Debit Memo..."
    
    'Delete Debit Memo
    If MsgBox("Delete this Record?", vbYesNo, "Pre-Debit Memo") = vbNo Then Exit Sub
    MB
    sSql = "DELETE FROM BILLING WHERE BILLING_NUM = " & lCurrDMNum & " and BILLING_TYPE = 'DM'"
    OraDatabase.DbExecuteSQL (sSql)
    MsgBox "Record Deleted."
    
    'clear screen
    cmdDoneDM_Click
    MN
ElseIf cmdDeleteCancelDM.Caption = "&Cancel" Then
    stbarCM.SimpleText = "Cancel..."
    If lCurrDMNum <> 0 Then
        sModeDM = ""
        bIsDisplaying = True
        If Not DispDM(lCurrDMNum) Then
            MsgBox "Unexpected error occured.Contact BNI Administrator"
            sModeDM = ""
            bIsDisplaying = False
            cmdDoneDM.Value = True
            MN
            Exit Sub
        End If
        ContAfterRetrieveDM
        bIsDisplaying = False
    
    Else
        If sModeDM <> "ADD" Then
            MsgBox "Unexpected error occured. Contact BNI Administrator."
        End If
        cmdDoneDM_Click
    End If
End If
MN
Exit Sub

ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else
    'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If

Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError

        'Disable the use of the error handler class
        'because we don't have the source code  -- LFW, 8/18/03
        MsgBox (gsMsg1 + gsMsg2)
        
'       gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdDeleteDM_Click"
End Select
MN

End Sub

Private Sub cmdDone_Click()
If sActiveTab = "CM" Then
    cmdDoneDM_Click
End If
ClrScr
sMode = ""

'enable buttons
txtInvoiceNum.Locked = False
txtCMNum.Locked = False
DisableAll
cmdNewCreateCM.Enabled = True
cmdNewCreateCM.Caption = "&New"
cmdEditSave.Caption = "&Edit"
cmdDeleteCancel.Caption = "&Delete"
cmdRetrieve.Enabled = True
cmdExit.Enabled = True
lCurrInvNum = 0
dblCurrInvCrTot = 0
lCurrCMNum = 0
dblCurrCMAmt = 0
stbarCM.SimpleText = "Mode: Retrieve"
End Sub

Private Sub cmdDoneDM_Click()
If sActiveTab = "DM" Then
    cmdDone_Click
End If
ClrScr
sModeDM = ""

'enable buttons
txtInvoiceNum.Locked = False
txtDMNum.Locked = False
DisableAllDM
cmdNewCreateDM.Enabled = True
cmdNewCreateDM.Caption = "&New"
cmdEditSaveDM.Caption = "&Edit"
cmdDeleteCancelDM.Caption = "&Delete"
cmdRetrieveDM.Enabled = True
cmdExit1.Enabled = True
lCurrInvNum = 0
'dblCurrInvCrTot = 0
lCurrDMNum = 0
'dblCurrCMAmt = 0
stbarCM.SimpleText = "Mode: Debit Memo Retrieve"
End Sub

Private Sub cmdEditSave_Click()
If cmdEditSave.Caption = "&Edit" Then
    sMode = "EDIT"
    DisableAll
    cmdEditSave.Enabled = True
    cmdEditSave.Caption = "&Save"
    cmdDeleteCancel.Enabled = True
    cmdDeleteCancel.Caption = "&Cancel"
    txtInvoiceNum.Locked = True
    txtCMNum.Locked = True
    stbarCM.SimpleText = "Mode: Edit"
ElseIf cmdEditSave.Caption = "&Save" Then
    
    SaveM
End If
End Sub

Private Sub cmdEditSaveDM_Click()
If cmdEditSaveDM.Caption = "&Edit" Then
    sModeDM = "EDIT"
    DisableAllDM
    cmdEditSaveDM.Enabled = True
    cmdEditSaveDM.Caption = "&Save"
    cmdDeleteCancelDM.Enabled = True
    cmdDeleteCancelDM.Caption = "&Cancel"
    txtInvoiceNum.Locked = True
    txtDMNum.Locked = True
    stbarCM.SimpleText = "Mode: DEBIT MEMO Edit"
ElseIf cmdEditSaveDM.Caption = "&Save" Then
    
    SaveM
End If

End Sub

Private Sub cmdExit_Click()
Unload Me
End Sub

Private Sub cmdExit1_Click()
Unload Me
End Sub

Private Sub cmdNewCreateCM_Click()
Dim lInvNum As Long
Dim sSql As String
Dim i As Integer
Dim bCcds As Boolean
Dim sCustId As String
Dim sMemoNum As String

On Error GoTo ErrorHandle

If cmdNewCreateCM.Caption = "Create CM" Then
    MB
    
    OraSession.begintrans
    
    'Lock all the required tables in exclusive mode, try 10 times
    'Added for getting unique invoice # and memo #  -LFW, 10/6/03
    For i = 0 To 9
        OraDatabase.LastServerErrReset
        sSql = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
        OraDatabase.EXECUTESQL sSql
        If OraDatabase.LastServerErr = 0 Then Exit For
    Next
    
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message:" _
             & OraDatabase.LastServerErrText, vbExclamation
        OraSession.rollback
        Exit Sub
    End If
    
    'assign Invoice_num, Invoice_date, Service_status and save
    sMemoNum = fnMaxCMNum(lCurrInvNum)
    If sMemoNum = "ERROR" Then
        MsgBox "Cannot create more than 26 credit memos per invoice!"
        OraSession.rollback
        Exit Sub
    End If
    
    lInvNum = fnMaxInvNum()
    
    sSql = "UPDATE BILLING SET INVOICE_NUM = " & lInvNum & ", INVOICE_DATE = TO_DATE('" & Format(Now, "dd-mmm-yy") _
         & "'), SERVICE_STATUS = 'CREDITMEMO', MEMO_NUM = '" & Mid(sMemoNum, 4) _
         & "' WHERE BILLING_NUM = " & lCurrCMNum & " and BILLING_TYPE = 'CM'"
    OraDatabase.DbExecuteSQL (sSql)
    
    If OraDatabase.LastServerErr = 0 Then
        MsgBox "Credit Memo created.", , "Memos"
        OraSession.committrans
        ClrCM
    Else
        GoTo ErrorHandle
    End If
    
    bIsDisplaying = True
    If Not DispCM(lCurrCMNum) Then
        MsgBox "Unexpected error occured.  Contact Department of Technology Solutions"
        bIsDisplaying = False
        txtCMNum.SetFocus
        MN
        OraSession.rollback
        Exit Sub
    End If
    
    bIsDisplaying = False
    ContAfterRetrieve
    MN
    
    'Print
    'chk the customer - Bni or ccds
    sCustId = txtCustomerID.Text
    If Len(sCustId) = 5 And Left(sCustId, 2) = "90" Then
        bCcds = True
    End If
    
    'Start a new transaction
    OraSession.begintrans
    
    'Lock required table in exclusive mode, try 10 times
    For i = 0 To 9
        OraDatabase.LastServerErrReset
        sSql = "LOCK TABLE PREINVOICE IN EXCLUSIVE MODE NOWAIT"
        OraDatabase.EXECUTESQL sSql
        If OraDatabase.LastServerErr = 0 Then Exit For
    Next
    
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message:" _
             & OraDatabase.LastServerErrText, vbExclamation
        OraSession.rollback
        Exit Sub
    End If
       
    If bCcds Then
        If Not ReadyToPrintCcds("C", True) Then
            GoTo ErrorHandle
            Exit Sub
        End If
    Else
        If Not ReadyToPrintBni("C", True) Then
            GoTo ErrorHandle
            Exit Sub
        End If
    End If
    
    'End of transaction
    OraSession.committrans
       
    crCM.ReportFileName = App.Path & "\BNICM.rpt"
    crCM.Connect = "DSN = BNI;UID = sag_owner;PWD = sag"
    crCM.Destination = crptToWindow
    crCM.PrintReport
    
    If crCM.LastErrorNumber <> 0 Then
        MsgBox crCM.LastErrorString, , "frmCreditMemo - cmdPrint_Click - Crystal Report Error"
        Exit Sub
    End If
     
ElseIf cmdNewCreateCM.Caption = "&New" Then
    If lCurrInvNum <> 0 Then
        If MsgBox("Use the same Invoice Number?", vbYesNo, "Memos") = vbYes Then
            ClrCM
        End If
    Else
        cmdDone_Click
        ClrCM
    End If
    
    sMode = "ADD"
    txtInvoiceNum.Locked = False
    txtCMNum.Locked = True
    DisableAll
    cmdEditSave.Enabled = True
    cmdEditSave.Caption = "&Save"
    cmdDeleteCancel.Enabled = True
    cmdDeleteCancel.Caption = "&Cancel"
    stbarCM.SimpleText = "Mode: Add"
    txtCMDate.Text = Format(Now, "mm/dd/yy")
End If

Exit Sub

ErrorHandle:

If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
ElseIf Err <> 0 Then  'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If

Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError

        'Disable the use of the error handler class
        'because we don't have the source code  -- LFW, 8/18/03
        MsgBox (gsMsg1 + gsMsg2)

'       gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdCreateCM_Click"
End Select

OraSession.rollback
MN

End Sub

Private Sub cmdNewCreateDM_Click()
Dim lInvNum As Long
Dim sSql As String
Dim i As Integer
Dim bCcds As Boolean
Dim sCustId As String
On Error GoTo ErrorHandle
Dim sMemoNum As String

On Error GoTo ErrorHandle

If cmdNewCreateDM.Caption = "Create DM" Then
    MB
    OraSession.begintrans
    
    'Lock all the required tables in exclusive mode, try 10 times
    'Added for getting unique invoice # and memo #  -LFW, 10/6/03
    For i = 0 To 9
        OraDatabase.LastServerErrReset
        sSql = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
        OraDatabase.EXECUTESQL sSql
        If OraDatabase.LastServerErr = 0 Then Exit For
    Next
    
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message:" _
             & OraDatabase.LastServerErrText, vbExclamation
        OraSession.rollback
        Exit Sub
    End If
     
    'assign Invoice_num, Invoice_date, Service_status and save
    sMemoNum = fnMaxDMNum(lCurrInvNum)
    If sMemoNum = "ERROR" Then
        MsgBox "More than 26 Debit Memos per Invoice cannot be created."
        OraSession.rollback
        Exit Sub
    End If
    
    lInvNum = fnMaxInvNum()
    
    sSql = "UPDATE BILLING SET INVOICE_NUM = " & lInvNum & ", INVOICE_DATE = TO_DATE('" & Format(Now, "dd-mmm-yy") _
        & "'), SERVICE_STATUS = 'DEBITMEMO', MEMO_NUM = '" & Mid(sMemoNum, 4) _
        & "' WHERE BILLING_NUM = " & lCurrDMNum & " and BILLING_TYPE = 'DM'"
    OraDatabase.DbExecuteSQL (sSql)
    
    If OraDatabase.LastServerErr = 0 Then
        MsgBox "Debit Memo created.", , "Debit Memo"
        OraSession.committrans
        ClrDM
    Else
        GoTo ErrorHandle
    End If
    
    bIsDisplaying = True
    If Not DispDM(lCurrDMNum) Then
        MsgBox "Unexpected error occured.Contact BNI Administrator"
        bIsDisplaying = False
        txtDMNum.SetFocus
        MN
        OraSession.rollback
        Exit Sub
    End If
    
    bIsDisplaying = False
    ContAfterRetrieveDM
    MN
    
    'Print
    'chk the customer - Bni or ccds
    sCustId = txtCustomerIDDM.Text
    If Len(sCustId) = 5 And Left(sCustId, 2) = "90" Then
        bCcds = True
    End If
    
    'Start another transaction
    OraSession.begintrans
    
    'Lock all the required tables in exclusive mode, try 10 times
    For i = 0 To 9
        OraDatabase.LastServerErrReset
        sSql = "LOCK TABLE PREINVOICE IN EXCLUSIVE MODE NOWAIT"
        OraDatabase.EXECUTESQL sSql
        If OraDatabase.LastServerErr = 0 Then Exit For
    Next
    
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message:" _
             & OraDatabase.LastServerErrText, vbExclamation
        OraSession.rollback
        Exit Sub
    End If
    
    If bCcds Then
        If Not ReadyToPrintCcds("D", True) Then
            GoTo ErrorHandle
            Exit Sub
        End If
    Else
        If Not ReadyToPrintBni("D", True) Then
            GoTo ErrorHandle
            Exit Sub
        End If
    End If
    
    'End of transaction
    OraSession.committrans

    crCM.ReportFileName = App.Path & "\BNICM.rpt"
    crCM.Connect = "DSN = BNI;UID = sag_owner;PWD = sag"
    crCM.Destination = crptToWindow
    crCM.PrintReport
    
    If crCM.LastErrorNumber <> 0 Then
        MsgBox crCM.LastErrorString, , "frmCreditMemo - cmdPrintDM_Click - Crystal Report Error"
        Exit Sub
    End If
     
ElseIf cmdNewCreateDM.Caption = "&New" Then
    If lCurrInvNum <> 0 Then
        If MsgBox("Use the same Invoice Number?", vbYesNo, "Memos") = vbYes Then
            ClrDM
        End If
    Else
        cmdDoneDM_Click
        ClrDM
    End If
    
    sModeDM = "ADD"
    txtInvoiceNum.Locked = False
    txtDMNum.Locked = True
    DisableAllDM
    cmdEditSaveDM.Enabled = True
    cmdEditSaveDM.Caption = "&Save"
    cmdDeleteCancelDM.Enabled = True
    cmdDeleteCancelDM.Caption = "&Cancel"
    stbarCM.SimpleText = "Mode: Add DEBIT MEMO"
    txtDMDate.Text = Format(Now, "mm/dd/yy")
End If

Exit Sub

ErrorHandle:

If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
ElseIf Err <> 0 Then  'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If

Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError

        'Disable the use of the error handler class
        'because we don't have the source code  -- LFW, 8/18/03
        MsgBox (gsMsg1 + gsMsg2)
        
'       gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdCreateDM_Click"
End Select

OraSession.rollback
MN

End Sub

Private Sub cmdPrint_Click()
Dim i As Integer
Dim sSql As String
Dim sCustId As String
Dim bCcds As Boolean
MB

'chk the customer - Bni or ccds
sCustId = txtCustomerID.Text
If sCustId = "96420" Or sCustId = "96000" Then
    bCcds = True
ElseIf Len(sCustId) = 5 And Left(sCustId, 2) = "90" Then
    bCcds = True
Else
    bCcds = False
End If

On Error Resume Next
OraSession.begintrans

'Lock all the required tables in exclusive mode, try 10 times
For i = 0 To 9
    OraDatabase.LastServerErrReset
    sSql = "LOCK TABLE PREINVOICE IN EXCLUSIVE MODE NOWAIT"
    OraDatabase.EXECUTESQL sSql
    If OraDatabase.LastServerErr = 0 Then Exit For
Next

If OraDatabase.LastServerErr <> 0 Then
    OraDatabase.LastServerErr
    OraSession.rollback
    MsgBox "Tables could not be locked. Please try again. Server Message:" _
         & OraDatabase.LastServerErrText, vbExclamation
    MN
    Exit Sub
End If

If bCcds Then
    If Not ReadyToPrintCcds("C", False) Then
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErr & " : " & OraDatabase.LastServerErrText
            OraDatabase.LastServerErrReset
        ElseIf Err <> 0 Then 'Must be some non-Oracle error.
            MsgBox Err & " : " & Error
        End If
        OraSession.rollback
        MN
        Exit Sub
    End If
Else
    If Not ReadyToPrintBni("C", False) Then
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErr & " : " & OraDatabase.LastServerErrText
            OraDatabase.LastServerErrReset
        ElseIf Err <> 0 Then  'Must be some non-Oracle error.
            MsgBox Err & " : " & Error
        End If
        OraSession.rollback
        MN
        Exit Sub
    End If
End If

OraSession.committrans

crCM.ReportFileName = App.Path & "\BNICM.rpt"
crCM.Connect = "DSN = BNI;UID = sag_owner;PWD = sag"
crCM.Destination = crptToWindow
crCM.PrintReport

MN
If crCM.LastErrorNumber <> 0 Then
    MsgBox crCM.LastErrorString, , "frmCreditMemo - cmdPrint_Click - Crystal Report Error"
End If

End Sub

Private Sub cmdPrintDM_Click()
Dim i As Integer
Dim sSql As String
Dim sCustId As String
Dim bCcds As Boolean
MB

'chk the customer - Bni or ccds
sCustId = txtCustomerIDDM.Text
If sCustId = "96420" Or sCustId = "96000" Then
    bCcds = True
ElseIf Len(sCustId) = 5 And Left(sCustId, 2) = "90" Then
    bCcds = True
Else
    bCcds = False
End If

On Error Resume Next
OraSession.begintrans

'Lock all the required tables in exclusive mode, try 10 times
For i = 0 To 9
    OraDatabase.LastServerErrReset
    sSql = "LOCK TABLE PREINVOICE IN EXCLUSIVE MODE NOWAIT"
    OraDatabase.EXECUTESQL sSql
    If OraDatabase.LastServerErr = 0 Then Exit For
Next

If OraDatabase.LastServerErr <> 0 Then
    OraDatabase.LastServerErr
    MsgBox "Tables could not be locked. Please try again. Server Message:" _
         & OraDatabase.LastServerErrText, vbExclamation
    MN
    Exit Sub
End If

If bCcds Then
    If Not ReadyToPrintCcds("D", False) Then
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErr & " : " & OraDatabase.LastServerErrText
            OraDatabase.LastServerErrReset
        ElseIf Err <> 0 Then
            'Must be some non-Oracle error.
            MsgBox Err & " : " & Error
        End If
        OraSession.rollback
        MN
        Exit Sub
    End If
Else
    If Not ReadyToPrintBni("D", False) Then
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErr & " : " & OraDatabase.LastServerErrText
            OraDatabase.LastServerErrReset
        ElseIf Err <> 0 Then
            'Must be some non-Oracle error.
            MsgBox Err & " : " & Error
        End If
        OraSession.rollback
        MN
        Exit Sub
    End If
End If

OraSession.committrans

crCM.ReportFileName = App.Path & "\BNICM.rpt"
crCM.Connect = "DSN = BNI;UID = sag_owner;PWD = sag"
crCM.PrintReport

MN
If crCM.LastErrorNumber <> 0 Then
    MsgBox crCM.LastErrorString, , "frmCreditMemo - cmdPrint_Click - Crystal Report Error"
End If

End Sub

Private Sub cmdRetrieve_Click()
Dim lCMNum As Long
Dim lOrigInvNum As Long
Dim dsBilling_BillingNum As Object
Dim sSql As String
On Error GoTo ErrorHandle

If sMode <> "" Then Exit Sub
If txtInvoiceNum.Text = "" And txtCMNum.Text = "" Then Exit Sub

If Len(Trim(txtInvoiceNum.Text)) > 10 Then        '2258 4/2/2007 Rudy: errors with overflow if too long
    MsgBox "Invoice number too long, try again"
    Exit Sub
End If

If txtInvoiceNum.Text <> "" And IsNumeric(txtInvoiceNum.Text) Then
    'search by Orig invoice num
    lOrigInvNum = txtInvoiceNum.Text
    lCurrInvNum = lOrigInvNum
    dblCurrInvCrTot = 0
    sSql = "SELECT * FROM BILLING WHERE ORIG_INVOICE_NUM = " & lOrigInvNum
    sSql = sSql & " AND BILLING_TYPE = 'CM'"
    Set dsBilling_BillingNum = OraDatabase.dbcreatedynaset(sSql, 0&)
    If Not dsBilling_BillingNum.eof And Not dsBilling_BillingNum.BOF Then
        'CHECK FOR ONE OR MORE RECORDS
        dsBilling_BillingNum.MoveLast: dsBilling_BillingNum.MoveFirst
        If dsBilling_BillingNum.RecordCount > 1 Then
            'show the Listbox screen
            lCMNum = ConfirmM(False, "CM")
        Else
            lCMNum = dsBilling_BillingNum.fields("BILLING_NUM").Value
        End If
        
    Else    'Not found
        MsgBox "No Credit Memos were issued against this Invoice."
        Exit Sub
    End If
End If
If txtCMNum.Text <> "" And IsNumeric(txtCMNum.Text) And lCMNum = 0 Then
    lCMNum = txtCMNum.Text
End If
'Retrieve
If lCMNum = 0 Then
    MsgBox "Invalid Search Criteria."
    Exit Sub
End If
MB
bIsDisplaying = True
If Not DispCM(lCMNum) Then
    MsgBox "Credit Memo could not be Retrieved. Try again."
    sMode = ""
    bIsDisplaying = False
    txtCMNum.SetFocus
    MN
    Exit Sub
End If
bIsDisplaying = False

'handle controls
ContAfterRetrieve
'DisableAll
'cmdEditSave.Enabled = True
'cmdDeleteCancel.Enabled = True
'cmdPrint.Enabled = True
'cmdDone.Enabled = True
'cmdEditSave.Caption = "&Edit"
'cmdNewCreateCM.Caption = "Create CM"
'cmdDeleteCancel.Caption = "&Delete"
MN
Exit Sub
ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If
Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError

        'Disable the use of the error handler class
        'because we don't have the source code  -- LFW, 8/18/03
        MsgBox (gsMsg1 + gsMsg2)
        
'       gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdRetrieve_Click"

End Select
MN
End Sub

Private Sub SaveM()
Dim sMemo As String
On Error GoTo ErrorHandle
MB
If sActiveTab = "CM" Then
    sMemo = "C"
Else
    sMemo = "D"
End If
If Not ValidData(sMemo) Then
    MN
    Exit Sub
End If
If sMemo = "C" Then
    If UpdateBilling(sMode) Then
        'MsgBox "Credit Memo Saved"
        'clear screen
        ClrCM
        sMode = ""
        bIsDisplaying = True
        If Not DispCM(lCurrCMNum) Then
            MsgBox "Unexpected error occured.Contact BNI Administrator"
            sMode = ""
            bIsDisplaying = False
            cmdDone.Value = True
            MN
            Exit Sub
        End If
        ContAfterRetrieve
        bIsDisplaying = False
        stbarCM.SimpleText = "Record Saved"
    Else
        MsgBox "Save Failed"
        If sMode = "ADD" Then
            cmdDone.Value = True
        ElseIf sMode = "EDIT" Then
            cmdDeleteCancel.Value = True
        End If
    End If
Else
    If UpdateBillingDM(sModeDM) Then
        'MsgBox "Credit Memo Saved"
        'clear screen
        ClrDM
        sModeDM = ""
        bIsDisplaying = True
        If Not DispDM(lCurrDMNum) Then
            MsgBox "Unexpected error occured.Contact BNI Administrator"
            sModeDM = ""
            bIsDisplaying = False
            cmdDoneDM.Value = True
            MN
            Exit Sub
        End If
        ContAfterRetrieveDM
        bIsDisplaying = False
        stbarCM.SimpleText = "Record Saved"
    Else
        MsgBox "Save Failed"
        If sModeDM = "ADD" Then
            cmdDoneDM.Value = True
        ElseIf sModeDM = "EDIT" Then
            cmdDeleteCancelDM.Value = True
        End If
    End If

End If
MN
Exit Sub
ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If
Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
        
        'Disable the use of the error handler class
        'because we don't have the source code  -- LFW, 8/18/03
        MsgBox (gsMsg1 + gsMsg2)
        
        'gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdSave_Click"
End Select
MN
End Sub

Private Sub cmdRetrieveDM_Click()
Dim lDMNum As Long
Dim lOrigInvNum As Long
Dim dsBilling_BillingNum As Object
Dim sSql As String
On Error GoTo ErrorHandle

If sModeDM <> "" Then Exit Sub
If txtInvoiceNum.Text = "" And txtDMNum.Text = "" Then Exit Sub
If txtInvoiceNum.Text <> "" And IsNumeric(txtInvoiceNum.Text) Then
    'search by Orig invoice num
    lOrigInvNum = txtInvoiceNum.Text
    lCurrInvNum = lOrigInvNum
    'dblCurrInvCrTot = 0
    sSql = "SELECT * FROM BILLING WHERE ORIG_INVOICE_NUM = " & lOrigInvNum
    sSql = sSql & " AND BILLING_TYPE = 'DM'"
    Set dsBilling_BillingNum = OraDatabase.dbcreatedynaset(sSql, 0&)
    If Not dsBilling_BillingNum.eof And Not dsBilling_BillingNum.BOF Then
        'CHECK FOR ONE OR MORE RECORDS
        dsBilling_BillingNum.MoveLast: dsBilling_BillingNum.MoveFirst
        If dsBilling_BillingNum.RecordCount > 1 Then
            'show the Listbox screen
            lDMNum = ConfirmM(False, "DM")
        Else
            lDMNum = dsBilling_BillingNum.fields("BILLING_NUM").Value
        End If
        
    Else    'Not found
        MsgBox "No Debit Memos were issued against this Invoice."
        Exit Sub
    End If
End If
If txtDMNum.Text <> "" And IsNumeric(txtDMNum.Text) And lDMNum = 0 Then
    lDMNum = txtDMNum.Text
End If
'Retrieve
If lDMNum = 0 Then
    MsgBox "Invalid Search Criteria."
    Exit Sub
End If
MB
bIsDisplaying = True
If Not DispDM(lDMNum) Then
    MsgBox "Credit Memo could not be Retrieved. Try again."
    sModeDM = ""
    bIsDisplaying = False
    txtDMNum.SetFocus
    MN
    Exit Sub
End If
bIsDisplaying = False

'handle controls
ContAfterRetrieveDM
'DisableAll
'cmdEditSave.Enabled = True
'cmdDeleteCancel.Enabled = True
'cmdPrint.Enabled = True
'cmdDone.Enabled = True
'cmdEditSave.Caption = "&Edit"
'cmdNewCreateCM.Caption = "Create CM"
'cmdDeleteCancel.Caption = "&Delete"
MN
Exit Sub
ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If
Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
        
        'Disable the use of the error handler class
        'because we don't have the source code  -- LFW, 8/18/03
        MsgBox (gsMsg1 + gsMsg2)
        
        'gEH.ErrorNotify gsMsg1, _
                       gsMsg2 , _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdRetrieveDM_Click"

End Select
MN
End Sub

Private Sub Form_Load()
Dim frmREAuth As New frmRateEditAuthenticate
Dim bProceed As Boolean

'  frmREAuth.Show 1
'  bProceed = frmREAuth.fbValidUser
'  lUser = frmREAuth.flUser
'  Unload frmREAuth
'  Set frmREAuth = Nothing
'  If Not bProceed Then
'    MsgBox "Invalid User"
'    Unload Me
'    Exit Sub
'  End If
'  'MsgBox lUser

  On Error GoTo ErrorHandle
  sModule = App.Title & " - frmCreditMemo - "
  
  CenterForm Me
  sstabDCM.Tab = 0
  sActiveTab = "CM"
  'on error resume next
  Set OraSession = CreateObject("OracleInProcServer.XOraSession")
'  Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
  Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)   '2258 4/2/2007 Rudy: TEMP original above
  
  If OraDatabase.LastServerErr <> 0 Then
    sErrPos = "Form: Credit Memo - Form_Load"
    MsgBox "Database connection could not be made.", , sErrPos
  End If

'  Set OraSessionProd = CreateObject("OracleInProcServer.XOraSession")
'  Set OraDatabaseProd = OraSessionProd.OpenDatabase("PROD", "APPS/APPS", 0&)
'  'Set OraDatabaseProd = OraSessionProd.OpenDatabase("PROD.DEV", "APPS/APPS_DEV", 0&)   '2258 4/2/2007 Rudy: cannot get this going TEMP, original above
'
'  If OraDatabaseProd.LastServerErr <> 0 Then
'    sErrPos = "Form: Credit Memo - Form_Load"
'    MsgBox "Production Database connection could not be made.", , sErrPos
'  End If
  
  
  cmdDone.Value = True
  
  '2258 4/2/2007 Rudy: begin
  cmdCust.Enabled = False             '2258 4/2/2007 Rudy: Per Antonia, do not need this functionality anymore
  cmdCust.Visible = False             '2258 4/2/2007 Rudy: Per Antonia, do not need this functionality anymore
  txtCustomerIDDM.Tag = ""
  txtCustomerNameDM.Tag = ""
  txtCustomerIDDM.TabStop = False
  txtCustomerNameDM.TabStop = False
  '2258 4/2/2007 Rudy: End
  
  Exit Sub
ErrorHandle:
  If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
  Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
  End If
  Select Case glSaveErr
    Case 0:
    Case Else:
      gsMsg1 = "Unexpected error in " & sModule
      gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
      
      'Disable the use of the error handler class
      'because we don't have the source code  -- LFW, 8/18/03
      MsgBox (gsMsg1 + gsMsg2)

'       gEH.ErrorNotify gsMsg1, _
                     gsMsg2, _
                     glSaveErr, _
                     gsSaveError, _
                     sModule & "Form_Load"
      Unload Me                       '2534 need to leave app
  End Select

End Sub


Private Sub ssgrdInvoice_Click()
If sActiveTab = "CM" Then
    If sMode <> "ADD" Then Exit Sub
    txtServiceCode.Text = ssgrdInvoice.Columns(1).Value
    txtServiceDate.Text = ssgrdInvoice.Columns(0).Value
    txtAssetCode.Text = ssgrdInvoice.Columns(4).Value
    txtCommCode.Text = ssgrdInvoice.Columns(5).Value
    txtDescription.SetFocus
Else
    If sModeDM <> "ADD" Then Exit Sub
    txtServiceCodeDM.Text = ssgrdInvoice.Columns(1).Value
    txtServiceDateDM.Text = ssgrdInvoice.Columns(0).Value
    txtAssetCodeDM.Text = ssgrdInvoice.Columns(4).Value
    txtCommCodeDM.Text = ssgrdInvoice.Columns(5).Value
    txtDescriptionDM.SetFocus
End If
End Sub

Private Sub sstabDCM_Click(PreviousTab As Integer)

If sstabDCM.Tab = 0 Then
    sActiveTab = "CM"
Else
    sActiveTab = "DM"
End If

End Sub

Private Sub sstabDCM_MouseUp(Button As Integer, Shift As Integer, X As Single, Y As Single)
If sMode <> "" Then
    sstabDCM.Tab = 0
End If
If sModeDM <> "" Then
    sstabDCM.Tab = 1
End If
End Sub

Private Sub txtAmount_Change()
Dim sAmt As String
sAmt = txtAmount.Text
If sAmt = "" Then Exit Sub
If Not IsNumeric(sAmt) Then
    MsgBox "Invalid Amount"
    txtAmount.SelStart = 0
    txtAmount.SelLength = Len(sAmt)
    txtAmount.SetFocus
    Exit Sub
End If
CalcBal
End Sub

Private Sub txtInvoiceNum_LostFocus()
On Error GoTo ErrorHandle

Dim lEntInvNum As Long
If sActiveTab = "CM" Then
    If txtInvoiceNum.Text = "" Or sMode = "EDIT" Or sMode = "" Then
        Exit Sub
    End If
Else
    If txtInvoiceNum.Text = "" Or sModeDM = "EDIT" Or sModeDM = "" Then
        Exit Sub
    End If
End If
lEntInvNum = txtInvoiceNum.Text
MB
If Not IsNumeric(lEntInvNum) Then
    MsgBox "Invalid Invoice Number."
    txtInvoiceNum.SelStart = 0
    txtInvoiceNum.SelLength = Len(txtInvoiceNum.Text)
    txtInvoiceNum.SetFocus
    Exit Sub
End If
lCurrInvNum = lEntInvNum
dblCurrInvCrTot = 0
If sActiveTab = "CM" Then
    lCurrCMNum = 0
    dblCurrCMAmt = 0
    If ChkExisting(lCurrInvNum, "CM") Then
        lCurrCMNum = ConfirmM(True, "CM")
    End If
    If lCurrCMNum = 0 Then
        bIsDisplaying = True
        If Not DispInvoice(lEntInvNum) Then
            MsgBox "Enter Invoice Number again."
            bIsDisplaying = False
            txtInvoiceNum.SelStart = 1
            txtInvoiceNum.SelLength = Len(CStr(lEntInvNum))
            txtInvoiceNum.SetFocus
            MN
            Exit Sub
        End If
        bIsDisplaying = False
        ClrCM
        txtCMDate.Text = Format(Now, "mm/dd/yy")
        txtDMDate.Text = Format(Now, "mm/dd/yy")
    Else
        'Retrieve the Selected CM
        bIsDisplaying = True
        If Not DispCM(lCurrCMNum) Then
            MsgBox "Credit Memo could not be Retrieved. Try again."
            sMode = ""
            bIsDisplaying = False
            txtCMNum.SetFocus
            Exit Sub
        End If
        bIsDisplaying = False
        'handle controls
        ContAfterRetrieve
    End If
Else
    'MN        '2258 4/2/2007 Rudy: otherwise hourglass on top of msg boxes and pop-up form
    If ChkExisting(lCurrInvNum, "DM") Then
        lCurrDMNum = ConfirmM(True, "DM")
    End If
    If lCurrDMNum = 0 Then
        bIsDisplaying = True
        If Not DispInvoice(lEntInvNum) Then
            MsgBox "Enter Invoice Number again."
            bIsDisplaying = False
            txtInvoiceNum.SelStart = 1
            txtInvoiceNum.SelLength = Len(CStr(lEntInvNum))
            txtInvoiceNum.SetFocus
            MN
            Exit Sub
        End If
        bIsDisplaying = False
        ClrDM
        txtDMDate.Text = Format(Now, "mm/dd/yy")
        
    Else
        'Retrieve the Selected DM
        bIsDisplaying = True
        If Not DispDM(lCurrDMNum) Then
            MsgBox "Debit Memo could not be Retrieved. Try again."
            sModeDM = ""
            bIsDisplaying = False
            txtDMNum.SetFocus
            Exit Sub
        End If
        bIsDisplaying = False
        'handle controls
        ContAfterRetrieveDM
    End If

End If
MN
Exit Sub
ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If
Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError

        'Disable the use of the error handler class
        'because we don't have the source code  -- LFW, 8/18/03
        MsgBox (gsMsg1 + gsMsg2)
        
'       gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "txtInvoiceNum_LostFocus"
End Select
MN
End Sub

Private Sub ClrScr()
Dim oCon As Control
bIsClearing = True
For Each oCon In Me.Controls
    If TypeName(oCon) = "TextBox" Then oCon.Text = ""
Next
ssgrdInvoice.RemoveAll
ssgrdCM.RemoveAll
ssgrdDM.RemoveAll

bIsClearing = False
'txtCMNum.Locked = False
End Sub

Private Sub ClrCM()
Dim oCon As Control
bIsClearing = True
For Each oCon In Me.Controls
    If TypeName(oCon) = "TextBox" Then
        If Left(oCon.Tag, 1) = "C" Then oCon.Text = ""
    End If
Next
bIsClearing = False
End Sub

Private Sub ClrDM()
Dim oCon As Control
bIsClearing = True
For Each oCon In Me.Controls
    If TypeName(oCon) = "TextBox" Then
        If Left(oCon.Tag, 1) = "D" Then oCon.Text = ""
    End If
Next
bIsClearing = False
End Sub

Private Function DispInvoice(alInvNum As Long) As Boolean
On Error GoTo ErrorHnd
Dim dsBillingDisp As Object
Dim dsInvoiceTotal As Object
Dim dsCMTotal As Object
Dim dsCM As Object
Dim dsDM As Object
Dim dsVESSEL_PROFILE As Object
Dim dsCUSTOMER_PROFILE As Object
Dim sSql As String
Dim sGrdItem As String
    'check for the validity of invoice num
    sSql = "SELECT * FROM BILLING WHERE INVOICE_NUM = " & alInvNum
    sSql = sSql & " AND SERVICE_STATUS = 'INVOICED'"
    Set dsBillingDisp = OraDatabase.dbcreatedynaset(sSql, 0&)
    If dsBillingDisp.eof And dsBillingDisp.BOF Then
        DispInvoice = False
        Exit Function
    End If
    'Get from Vessel Profile table based on LR Num
    sSql = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " _
              & "'" & dsBillingDisp.fields("LR_NUM").Value & "'"
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sSql, 0&)
    
    'Get from Customer table based on Customer Code
    sSql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " _
              & "'" & dsBillingDisp.fields("CUSTOMER_ID").Value & "'"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sSql, 0&)
    'Get the invoice total
    sSql = "SELECT SUM(SERVICE_AMOUNT) FROM BILLING WHERE INVOICE_NUM = " & alInvNum
    Set dsInvoiceTotal = OraDatabase.dbcreatedynaset(sSql, 0&)
    
     'Get the Credit Memo total
    sSql = "SELECT SUM(SERVICE_AMOUNT) FROM BILLING WHERE ORIG_INVOICE_NUM = " & alInvNum
    sSql = sSql & " AND SERVICE_STATUS IN ('PRECREDIT','CREDITMEMO')"
    Set dsCMTotal = OraDatabase.dbcreatedynaset(sSql, 0&)
    If Not dsCMTotal.eof And Not dsCMTotal.BOF Then
        If Not IsNull(dsCMTotal.fields(0).Value) Then dblCurrInvCrTot = dsCMTotal.fields(0).Value
    End If
    
    'Get Credit Memos
    sSql = "SELECT * FROM BILLING WHERE ORIG_INVOICE_NUM = " & alInvNum
    sSql = sSql & " AND SERVICE_STATUS IN ('PRECREDIT','CREDITMEMO')"
    Set dsCM = OraDatabase.dbcreatedynaset(sSql, 0&)
    
    'Get dEBit Memos
    sSql = "SELECT * FROM BILLING WHERE ORIG_INVOICE_NUM = " & alInvNum
    sSql = sSql & " AND SERVICE_STATUS IN ('PREDEBIT','DEBITMEMO')"
    Set dsDM = OraDatabase.dbcreatedynaset(sSql, 0&)
    
    'Display info
    txtInvoiceDate.Text = dsBillingDisp.fields("INVOICE_DATE").Value
    txtCustomerID.Text = dsBillingDisp.fields("CUSTOMER_ID").Value
    txtCustomerName.Text = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
    txtVesselNum.Text = dsBillingDisp.fields("LR_NUM").Value
    If IsNull(dsVESSEL_PROFILE.fields("VESSEL_NAME").Value) Then
        txtVesselName.Text = "IN-BOUND TRUCK"
    Else
        txtVesselName.Text = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
    End If
    txtInvoiceTotal.Text = Format(dsInvoiceTotal.fields(0).Value, "###.00")
    'fill up the Inv grid
    ssgrdInvoice.RemoveAll
    While Not dsBillingDisp.eof
        sGrdItem = Format(dsBillingDisp.fields("SERVICE_DATE").Value, "mm/dd/yy") + Chr(9)
        sGrdItem = sGrdItem & dsBillingDisp.fields("SERVICE_CODE").Value + Chr(9)
        sGrdItem = sGrdItem & dsBillingDisp.fields("SERVICE_DESCRIPTION").Value & "" + Chr(9)
        sGrdItem = sGrdItem & Format(dsBillingDisp.fields("SERVICE_AMOUNT").Value, "###.00") + Chr(9)
        sGrdItem = sGrdItem & dsBillingDisp.fields("ASSET_CODE").Value & "" + Chr(9)
        sGrdItem = sGrdItem & dsBillingDisp.fields("COMMODITY_CODE").Value
        ssgrdInvoice.AddItem sGrdItem
    
        dsBillingDisp.MoveNext
    Wend
    'fill up the CM grid
    ssgrdCM.RemoveAll
    If Not dsCM.eof And Not dsCM.BOF Then
        While Not dsCM.eof
            sGrdItem = dsCM.fields("MEMO_NUM").Value & ","
            sGrdItem = sGrdItem & Format(dsCM.fields("SERVICE_DATE").Value, "mm/dd/yy") & ","
            sGrdItem = sGrdItem & dsCM.fields("SERVICE_CODE").Value & ","
            sGrdItem = sGrdItem & dsCM.fields("SERVICE_DESCRIPTION").Value & ","
            sGrdItem = sGrdItem & dsCM.fields("SERVICE_STATUS").Value & ","
            sGrdItem = sGrdItem & Format(Abs(dsCM.fields("SERVICE_AMOUNT").Value), "###.00")
'            sGrdItem = dsCM.fields("BILLING_NUM").Value & ","
'            sGrdItem = sGrdItem & Format(dsCM.fields("SERVICE_DATE").Value, "mm/dd/yy") + Chr(9)
'            sGrdItem = sGrdItem & dsCM.fields("SERVICE_CODE").Value + Chr(9)
'            sGrdItem = sGrdItem & dsCM.fields("SERVICE_DESCRIPTION").Value + Chr(9)
'            sGrdItem = sGrdItem & dsCM.fields("SERVICE_STATUS").Value + Chr(9)
'            sGrdItem = sGrdItem & dsCM.fields("SERVICE_AMOUNT").Value
            ssgrdCM.AddItem sGrdItem
        
            dsCM.MoveNext
        Wend
    End If
    'fill up the DM grid
    ssgrdDM.RemoveAll
    If Not dsDM.eof And Not dsDM.BOF Then
        While Not dsDM.eof
            sGrdItem = dsDM.fields("MEMO_NUM").Value & ","
            sGrdItem = sGrdItem & Format(dsDM.fields("SERVICE_DATE").Value, "mm/dd/yy") & ","
            sGrdItem = sGrdItem & dsDM.fields("SERVICE_CODE").Value & ","
            sGrdItem = sGrdItem & dsDM.fields("SERVICE_DESCRIPTION").Value & ","
            sGrdItem = sGrdItem & dsDM.fields("SERVICE_STATUS").Value & ","
            sGrdItem = sGrdItem & Format(Abs(dsDM.fields("SERVICE_AMOUNT").Value), "###.00")
'            sGrdItem = dsCM.fields("BILLING_NUM").Value & ","
'            sGrdItem = sGrdItem & Format(dsCM.fields("SERVICE_DATE").Value, "mm/dd/yy") + Chr(9)
'            sGrdItem = sGrdItem & dsCM.fields("SERVICE_CODE").Value + Chr(9)
'            sGrdItem = sGrdItem & dsCM.fields("SERVICE_DESCRIPTION").Value + Chr(9)
'            sGrdItem = sGrdItem & dsCM.fields("SERVICE_STATUS").Value + Chr(9)
'            sGrdItem = sGrdItem & dsCM.fields("SERVICE_AMOUNT").Value
            ssgrdDM.AddItem sGrdItem
        
            dsDM.MoveNext
        Wend
        
        '2258 4/2/2007 Rudy: begin
'        txtCustomerIDDM.Enabled = True
'        txtCustomerNameDM.Enabled = True

        If IsNull(dsDM.fields("CUSTOMER_ID").Value) Then
          txtCustomerIDDM.Text = txtCustomerID.Text
        Else
          txtCustomerIDDM.Text = dsDM.fields("CUSTOMER_ID").Value
        End If
        txtCustomerNameDM.Text = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
        '2258 4/2/2007 Rudy: end
        
    End If
    DispInvoice = True
Exit Function
ErrorHnd:
DispInvoice = False
End Function

Private Function UpdateBilling(ByVal asAction As String) As Boolean
    Dim i, j As Integer
    Dim lRecCount As Long
    Dim RowNum As Integer
    Dim iCol As Integer
    Dim bAddNew As Boolean
    Dim sSql As String
    Dim dsBILLING As Object
    Dim dsBILLING_MAX As Object
    Dim lCurrBillingNum As Long
    
    bAddNew = False
    On Error GoTo lerrHand
    
    OraSession.begintrans
    
    'Lock all the required tables in exclusive mode, try 10 times
    'Moved table locking into the transaction to make it effective  -LFW, 10/6/03
    For i = 0 To 9
        OraDatabase.LastServerErrReset
        sSql = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
        lRecCount = OraDatabase.EXECUTESQL(sSql)
        If OraDatabase.LastServerErr = 0 Then Exit For
    Next
        
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message: " _
            & OraDatabase.LastServerErrText, vbExclamation, "frmCreditMemo - UpdateBilling"
        OraSession.rollback
        OraDatabase.LastServerErrReset
        Exit Function
    End If
    
    If asAction = "ADD" And lCurrCMNum = 0 Then
        bAddNew = True
    End If
    
    If bAddNew Then
        sSql = " SELECT * FROM BILLING"
        Set dsBILLING = OraDatabase.dbcreatedynaset(sSql, 0&)
        dsBILLING.AddNew
        
        sSql = "SELECT MAX(BILLING_NUM) FROM BILLING"
        Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(sSql, 0&)
        
        If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
            If IsNull(dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value) Then
                dsBILLING.fields("BILLING_NUM").Value = 1
                lCurrBillingNum = 1
            Else
                lCurrBillingNum = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
                dsBILLING.fields("BILLING_NUM").Value = lCurrBillingNum
            End If
        Else
            dsBILLING.fields("BILLING_NUM").Value = 1
            lCurrBillingNum = 1
        End If
    Else
        lCurrBillingNum = lCurrCMNum
        
        'Added the billing type check so it won't mess up with other billing types  -- LFW, 3/16/04
        sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & lCurrCMNum & " AND BILLING_TYPE = 'CM'"
        Set dsBILLING = OraDatabase.dbcreatedynaset(sSql, 0&)
        dsBILLING.Edit
    End If
              
    dsBILLING.fields("LR_NUM").Value = Trim(txtVesselNum.Text)
    dsBILLING.fields("CUSTOMER_ID").Value = Trim(txtCustomerID.Text)
    dsBILLING.fields("COMMODITY_CODE").Value = Trim(txtCommCode.Text)
    dsBILLING.fields("GL_CODE").Value = Trim(txtGLCode.Text)
    dsBILLING.fields("SERVICE_CODE").Value = Trim(txtServiceCode.Text)
    dsBILLING.fields("SERVICE_DATE").Value = Trim(txtServiceDate.Text)
    dsBILLING.fields("SERVICE_DESCRIPTION").Value = UCase(Trim(txtDescription.Text))
    dsBILLING.fields("SERVICE_AMOUNT").Value = "-" & Trim(txtAmount.Text)
    dsBILLING.fields("EMPLOYEE_ID").Value = lUser
    dsBILLING.fields("SERVICE_STATUS").Value = "PRECREDIT"
    dsBILLING.fields("ARRIVAL_NUM").Value = 1
    dsBILLING.fields("INVOICE_NUM").Value = 0
    dsBILLING.fields("INVOICE_DATE").Value = txtCMDate.Text
    dsBILLING.fields("ORIG_INVOICE_NUM").Value = Trim(txtInvoiceNum.Text)
    dsBILLING.fields("SERVICE_NUM").Value = 1
    dsBILLING.fields("SERVICE_RATE").Value = 0
    dsBILLING.fields("CARE_OF").Value = 1
    dsBILLING.fields("BILLING_TYPE").Value = "CM"
    dsBILLING.fields("SERVICE_START").Value = txtCMDate.Text
    dsBILLING.fields("SERVICE_STOP").Value = txtCMDate.Text
    dsBILLING.fields("SERVICE_DATE").Value = txtCMDate.Text
    
    'Added this field for Asset Coding.  06.21.2001 LJG
    dsBILLING.fields("ASSET_CODE").Value = Trim(txtAssetCode.Text)
    dsBILLING.Update
    
    lCurrCMNum = lCurrBillingNum
    dblCurrCMAmt = CDbl(txtAmount.Text) * -1

lerrHand:
    If OraDatabase.LastServerErr <> 0 Then
        MsgBox "Error in Credit Memo # " & lCurrCMNum & vbCrLf & vbCrLf & Err.Description, vbExclamation, "frmCreditMemo - UpdateBilling"
        OraSession.rollback
        OraDatabase.LastServerErrReset
        Exit Function
    Else
        OraSession.committrans
        MsgBox "SAVE SUCCESSFUL", vbInformation, "SAVE"
        UpdateBilling = True
    End If
End Function

Private Function UpdateBillingDM(ByVal asAction As String) As Boolean
    Dim i, j As Integer
    Dim lRecCount As Long
    Dim RowNum As Integer
    Dim iCol As Integer
    Dim bAddNew As Boolean
    Dim sSql As String
    Dim dsBILLING As Object
    Dim dsBILLING_MAX As Object
    Dim lCurrBillingNum As Long
        
    bAddNew = False
    On Error GoTo lerrHand
    
    OraSession.begintrans
    
    'Lock all the required tables in exclusive mode, try 10 times
    'Moved table locking into the database transaction to make it effective  -LFW, 10/6/03
    For i = 0 To 9
        OraDatabase.LastServerErrReset
        sSql = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
        lRecCount = OraDatabase.EXECUTESQL(sSql)
        If OraDatabase.LastServerErr = 0 Then Exit For
    Next 'i
        
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message: " _
            & OraDatabase.LastServerErrText, vbExclamation, "frmCreditMemo - UpdateBilling"
        OraSession.rollback
        OraDatabase.LastServerErrReset
        Exit Function
    End If
      
    If asAction = "ADD" And lCurrDMNum = 0 Then
        bAddNew = True
    End If
    
    If bAddNew Then
        sSql = " SELECT * FROM BILLING"
        Set dsBILLING = OraDatabase.dbcreatedynaset(sSql, 0&)
        dsBILLING.AddNew
        
        sSql = "SELECT MAX(BILLING_NUM) FROM BILLING"
        Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(sSql, 0&)
        
        If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
            If IsNull(dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value) Then
                dsBILLING.fields("BILLING_NUM").Value = 1
                lCurrBillingNum = 1
            Else
                lCurrBillingNum = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
                dsBILLING.fields("BILLING_NUM").Value = lCurrBillingNum
            End If
        Else
            dsBILLING.fields("BILLING_NUM").Value = 1
            lCurrBillingNum = 1
        End If
    
    Else
        lCurrBillingNum = lCurrDMNum
        
        'Added the billing type check so it won't mess up with other billing types  -- LFW, 3/16/04
        sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & lCurrDMNum & " AND BILLING_TYPE = 'DM'"
        Set dsBILLING = OraDatabase.dbcreatedynaset(sSql, 0&)
        dsBILLING.Edit
    End If
              
    dsBILLING.fields("LR_NUM").Value = Trim(txtVesselNum.Text)
    
    If Len(Trim(txtCustomerIDDM.Text)) <> 0 Then      '2258 4/2/2007 Rudy: put this if around it because removed tag so it'd display (but it skips the validation step)
      dsBILLING.fields("CUSTOMER_ID").Value = Trim(txtCustomerIDDM.Text)
    Else
      dsBILLING.fields("CUSTOMER_ID").Value = Trim(txtCustomerID.Text)
    End If
    
    dsBILLING.fields("COMMODITY_CODE").Value = Trim(txtCommCodeDM.Text)
    dsBILLING.fields("GL_CODE").Value = Trim(txtGLCodeDM.Text)
    dsBILLING.fields("SERVICE_CODE").Value = Trim(txtServiceCodeDM.Text)
    dsBILLING.fields("SERVICE_DATE").Value = Trim(txtServiceDateDM.Text)
    dsBILLING.fields("SERVICE_DESCRIPTION").Value = UCase(Trim(txtDescriptionDM.Text))
    dsBILLING.fields("SERVICE_AMOUNT").Value = Trim(txtAmountDM.Text)
    dsBILLING.fields("EMPLOYEE_ID").Value = lUser
    dsBILLING.fields("SERVICE_STATUS").Value = "PREDEBIT"
    dsBILLING.fields("ARRIVAL_NUM").Value = 1
    dsBILLING.fields("INVOICE_NUM").Value = 0
    dsBILLING.fields("INVOICE_DATE").Value = txtDMDate.Text
    dsBILLING.fields("ORIG_INVOICE_NUM").Value = Trim(txtInvoiceNum.Text)
    dsBILLING.fields("SERVICE_NUM").Value = 1
    dsBILLING.fields("SERVICE_RATE").Value = 0
    dsBILLING.fields("CARE_OF").Value = 1
    dsBILLING.fields("BILLING_TYPE").Value = "DM"
    dsBILLING.fields("SERVICE_START").Value = txtDMDate.Text
    dsBILLING.fields("SERVICE_STOP").Value = txtDMDate.Text
    dsBILLING.fields("SERVICE_DATE").Value = txtDMDate.Text
    
    'Added this field for Asset Coding.  06.21.2001 LJG
    dsBILLING.fields("ASSET_CODE").Value = Trim(txtAssetCodeDM.Text)
    dsBILLING.Update
    
    lCurrDMNum = lCurrBillingNum

lerrHand:
    If OraDatabase.LastServerErr <> 0 Then
        MsgBox "Error in Debit Memo # " & lCurrDMNum & vbCrLf & vbCrLf & OraDatabase.LastServerErrText, vbExclamation, "frmCreditMemo - UpdateBillingDM"
        OraSession.rollback
        OraDatabase.LastServerErrReset
        Exit Function
    ElseIf Err <> 0 Then
        MsgBox "Error in Debit Memo # " & lCurrDMNum & vbCrLf & vbCrLf & Err.Description, vbExclamation, "frmCreditMemo - UpdateBillingDM"
        OraSession.rollback
        Err.Clear
        Exit Function
    Else
        OraSession.committrans
        MsgBox "SAVE SUCCESSFUL", vbInformation, "SAVE"
        UpdateBillingDM = True
    End If
End Function

Public Function fnMaxInvNum() As Long
    Dim SqlStmt As String
    Dim dsBILLING_INV As Object
    
    SqlStmt = "SELECT MAX(INVOICE_NUM) MAX_NUM FROM BILLING"
    Set dsBILLING_INV = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
           
    If dsBILLING_INV.RecordCount > 0 Then
        If dsBILLING_INV.fields("MAX_NUM").Value < 980200000 Then
            fnMaxInvNum = 980200001
        Else
            fnMaxInvNum = dsBILLING_INV.fields("MAX_NUM").Value + 1
        End If
    Else
        fnMaxInvNum = 1
    End If
End Function

Private Function DispCM(alCMNum As Long) As Boolean
Dim sSql As String
Dim dsBILLING As Object
Dim lEntInvNum As Long
On Error GoTo errHandle
sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & alCMNum & " AND SERVICE_STATUS IN ('PRECREDIT','CREDITMEMO')"
Set dsBILLING = OraDatabase.dbcreatedynaset(sSql, 0&)
If dsBILLING.BOF And dsBILLING.eof Then
    MsgBox "Credit Memo # " & alCMNum & "not found."
    DispCM = False
    Exit Function
End If

If Not IsNull(dsBILLING.fields("ORIG_INVOICE_NUM").Value) Then
    lEntInvNum = dsBILLING.fields("ORIG_INVOICE_NUM").Value
Else
    MsgBox "The Credit Memo is not pointing to any Invoice"
    DispCM = False
    Exit Function
End If

'display invoice
If Not DispInvoice(lEntInvNum) Then
    MsgBox "Unexpected Error displaying Invoice # " & lEntInvNum
    DispCM = False
    Exit Function
End If

lCurrInvNum = lEntInvNum
txtInvoiceNum.Text = lEntInvNum
'display CM
txtMemoNum.Text = dsBILLING.fields("MEMO_NUM").Value & ""
txtCMInvoiceNum.Text = dsBILLING.fields("INVOICE_NUM").Value & ""
txtCMNum.Text = dsBILLING.fields("BILLING_NUM").Value
If Not IsNull(dsBILLING.fields("INVOICE_DATE").Value) Then
    txtCMDate.Text = dsBILLING.fields("INVOICE_DATE").Value
End If

If Not IsNull(dsBILLING.fields("SERVICE_CODE").Value) Then
    txtServiceCode.Text = dsBILLING.fields("SERVICE_CODE").Value
End If
    
If Not IsNull(dsBILLING.fields("GL_CODE").Value) Then
    txtGLCode.Text = dsBILLING.fields("GL_CODE").Value
End If
       
If Not IsNull(dsBILLING.fields("SERVICE_DATE").Value) Then
    txtServiceDate.Text = dsBILLING.fields("SERVICE_DATE").Value
End If
    
If Not IsNull(dsBILLING.fields("ASSET_CODE").Value) Then
    txtAssetCode.Text = dsBILLING.fields("ASSET_CODE").Value
End If
    
If Not IsNull(dsBILLING.fields("COMMODITY_CODE").Value) Then
    txtCommCode.Text = dsBILLING.fields("COMMODITY_CODE").Value
End If
    
If Not IsNull(dsBILLING.fields("SERVICE_DESCRIPTION").Value) Then
    txtDescription.Text = dsBILLING.fields("SERVICE_DESCRIPTION").Value
End If
    
If Not IsNull(dsBILLING.fields("SERVICE_AMOUNT").Value) Then
    dblCurrCMAmt = dsBILLING.fields("SERVICE_AMOUNT").Value
    txtAmount.Text = Format(Abs(dsBILLING.fields("SERVICE_AMOUNT").Value), "###.00")
End If

lCurrCMNum = alCMNum

'Check status field and set Enabled property of buttons
If dsBILLING.fields("SERVICE_STATUS").Value = "PRECREDIT" Then
    bPreCM = True
Else
    bPreCM = False
End If
DispCM = True
Exit Function

errHandle:

If OraDatabase.LastServerErr <> 0 Then
    MsgBox "Error occured while displaying CM #" & alCMNum & vbCrLf & OraDatabase.LastServerErrText
Else 'Must be some non-Oracle error.
    MsgBox "Error occured while displaying CM #" & alCMNum & vbCrLf & "VB:" & Err & " " & Error(Err)
End If

DispCM = False

End Function

Private Function DispDM(alDMNum As Long) As Boolean
Dim sSql As String
Dim dsBILLING As Object
Dim lEntInvNum As Long
On Error GoTo errHandle
sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & alDMNum & " AND SERVICE_STATUS IN ('PREDEBIT','DEBITMEMO')"
Set dsBILLING = OraDatabase.dbcreatedynaset(sSql, 0&)
If dsBILLING.BOF And dsBILLING.eof Then
    MsgBox "Debit Memo # " & alDMNum & "not found."
    DispDM = False
    Exit Function
End If

If Not IsNull(dsBILLING.fields("ORIG_INVOICE_NUM").Value) Then
    lEntInvNum = dsBILLING.fields("ORIG_INVOICE_NUM").Value
Else
    MsgBox "The Debit Memo is not pointing to any Invoice"
    DispDM = False
    Exit Function
End If

'display invoice
If Not DispInvoice(lEntInvNum) Then
    MsgBox "Unexpected Error displaying Invoice # " & lEntInvNum
    DispDM = False
    Exit Function
End If

lCurrInvNum = lEntInvNum
txtInvoiceNum.Text = lEntInvNum
txtMemoNumDM.Text = dsBILLING.fields("MEMO_NUM").Value & ""
txtDMInvoiceNum.Text = dsBILLING.fields("INVOICE_NUM").Value & ""
txtDMNum.Text = dsBILLING.fields("BILLING_NUM").Value
If Not IsNull(dsBILLING.fields("INVOICE_DATE").Value) Then
    txtDMDate.Text = dsBILLING.fields("INVOICE_DATE").Value
End If

If Not IsNull(dsBILLING.fields("SERVICE_CODE").Value) Then
    txtServiceCodeDM.Text = dsBILLING.fields("SERVICE_CODE").Value
End If
    
If Not IsNull(dsBILLING.fields("GL_CODE").Value) Then
    txtGLCodeDM.Text = dsBILLING.fields("GL_CODE").Value
End If
      
If Not IsNull(dsBILLING.fields("SERVICE_DATE").Value) Then
    txtServiceDateDM.Text = dsBILLING.fields("SERVICE_DATE").Value
End If
    
If Not IsNull(dsBILLING.fields("ASSET_CODE").Value) Then
    txtAssetCodeDM.Text = dsBILLING.fields("ASSET_CODE").Value
End If
    
If Not IsNull(dsBILLING.fields("COMMODITY_CODE").Value) Then
    txtCommCodeDM.Text = dsBILLING.fields("COMMODITY_CODE").Value
End If
    
If Not IsNull(dsBILLING.fields("SERVICE_DESCRIPTION").Value) Then
    txtDescriptionDM.Text = dsBILLING.fields("SERVICE_DESCRIPTION").Value
End If
    
If Not IsNull(dsBILLING.fields("SERVICE_AMOUNT").Value) Then
    'dblCurrCMAmt = dsBILLING.fields("SERVICE_AMOUNT").Value
    txtAmountDM.Text = Format(Abs(dsBILLING.fields("SERVICE_AMOUNT").Value), "###.00")
End If

'get customer info
Dim dsCustomer As Object
If Not IsNull(dsBILLING.fields("CUSTOMER_ID").Value) Then
    txtCustomerIDDM.Text = dsBILLING.fields("CUSTOMER_ID").Value
    sSql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & dsBILLING.fields("CUSTOMER_ID").Value & "'"
    Set dsCustomer = OraDatabase.dbcreatedynaset(sSql, 0&)
    If Not dsCustomer.eof And Not dsCustomer.BOF Then
        If Not IsNull(dsCustomer.fields("CUSTOMER_NAME").Value) Then
            txtCustomerNameDM.Text = dsCustomer.fields("CUSTOMER_NAME").Value
        End If
    End If
End If

lCurrDMNum = alDMNum

'Check status field and set Enabled property of buttons
If dsBILLING.fields("SERVICE_STATUS").Value = "PREDEBIT" Then
    bPreDM = True
Else
    bPreDM = False
End If
DispDM = True

'2258 4/2/2007 Rudy: whole if
If txtDMNum.Locked = True Then
  txtServiceCodeDM.SetFocus
Else
  txtDMNum.SetFocus
End If

Exit Function

errHandle:
If OraDatabase.LastServerErr <> 0 Then
    MsgBox "Error occured while displaying DM #" & alDMNum & vbCrLf & OraDatabase.LastServerErrText
Else
    'Must be some non-Oracle error.
    MsgBox "Error occured while displaying DM #" & alDMNum & vbCrLf & "VB:" & Err & " " & Error(Err)
End If
DispDM = False

End Function

Private Function ReadyToPrintBni(ByVal asMemo As String, ByVal abIsCreating As Boolean) As Boolean
Dim dsPrtCM As Object
Dim dsCUSTOMER_PROFILE As Object

Dim sSql As String
Dim sCityStateZip As String
Dim iline As Integer
Dim iCustId As Long
Dim iLRNUM As Long
Dim sServStatus As String
Dim iRow As Long
Dim iNum As Integer
Dim sMemoNum As String
Dim sInvDt As String
Dim iPos1 As Integer
Dim sBillNo As String
Dim i As Integer
Dim sDesp As String
Dim sFLine As String

On Error GoTo ErrHndl
Dim iPos2 As Integer
Dim sBegStr As String
iCustId = 0
iLRNUM = 0
iline = 0
iNum = 0

sSql = "DELETE FROM PREINVOICE"
OraDatabase.EXECUTESQL sSql
If asMemo = "C" Then
    stbarCM.SimpleText = "PROCESSING CREDIT MEMO..."
    sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & lCurrCMNum & " AND BILLING_TYPE = 'CM'"
Else
    stbarCM.SimpleText = "PROCESSING DEBIT MEMO..."
    sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & lCurrDMNum & " AND BILLING_TYPE = 'DM'"
End If
          
Set dsPrtCM = OraDatabase.dbcreatedynaset(sSql, 0&)
If Not dsPrtCM.eof And Not dsPrtCM.BOF Then
    dsPrtCM.MoveFirst
    sBillNo = dsPrtCM.fields("billing_num").Value
    'Get from Customer table based on Customer Code
    sSql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " _
              & "'" & dsPrtCM.fields("CUSTOMER_ID").Value & "'"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sSql, 0&)

    'Check to see if we need to change the invoice number and headings on the page
    iCustId = dsPrtCM.fields("CUSTOMER_ID").Value
    iLRNUM = dsPrtCM.fields("LR_NUM").Value
    sServStatus = dsPrtCM.fields("SERVICE_STATUS").Value
    sMemoNum = dsPrtCM.fields("MEMO_NUM").Value & ""
    iNum = 0
    iNum = iNum + 1
    iRow = iRow + 1
    If asMemo = "C" Then
        If sServStatus = "CREDITMEMO" Then
            Call PreInv_AddNew(iRow, Space(227) & sMemoNum, 1, 0)
        Else
            Call PreInv_AddNew(iRow, "", 1, 0)
        End If
    Else
        If sServStatus = "DEBITMEMO" Then
            Call PreInv_AddNew(iRow, Space(227) & sMemoNum, 1, 0)
        Else
            Call PreInv_AddNew(iRow, "", 1, 0)
        End If
    End If
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, "", 0, 0)
    
    iNum = iNum + 1
    iRow = iRow + 1
    If sServStatus = "CREDITMEMO" Then
        Call PreInv_AddNew(iRow, Space(227) & Format(dsPrtCM.fields("INVOICE_DATE").Value, "mm/dd/yy"), 0, 0)
    Else
        Call PreInv_AddNew(iRow, Space(227) & Format(Now, "mm/dd/yy"), 0, 0)
    End If
    Dim iCtr As Integer
    
'    If sServStatus = "CREDITMEMO" Then
        iCtr = 7
'    Else
'        iRow = iRow + 1
'        iNum = iNum + 1
'        Call PreInv_AddNew(iRow, Space(200) & "BILLING NO. " & lCurrCMNum, 0, 0)
'
'        iCtr = 6
'    End If
    For iline = 1 To iCtr
        
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, "", 0, 0)
    Next iline
    
       
    If Not IsNull(dsPrtCM.fields("CARE_OF")) Then
        If (dsPrtCM.fields("CARE_OF").Value = "1") Or (dsPrtCM.fields("CARE_OF").Value = "Y") Then
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(25) & fnVesselName(iLRNUM), 0, 0)

            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(25) & "C/O " & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
        Else
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(25) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
        End If
    Else
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, Space(25) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
    End If
    
    
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value) Then
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, Space(25) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value, 0, 0)
    End If
    
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) Then
        If Trim(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) <> "" Then
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(25) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value, 0, 0)
        End If
    End If
    sCityStateZip = ""
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value) Then
        sCityStateZip = dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value
    End If
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value) Then
        sCityStateZip = sCityStateZip & ", " & dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value
    End If
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value) Then
        sCityStateZip = sCityStateZip & " - " & dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value
    End If
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, Space(25) & sCityStateZip, 0, 0)
    
    If dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value <> "US" Then
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, Space(25) & fnCountryName(dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value), 0, 0)
    End If

    For iline = 1 To 6
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, "", 0, 0)
    Next iline
    
    sBillNo = dsPrtCM.fields("Billing_Num").Value
    iNum = iNum + 1
    iRow = iRow + 1
    If asMemo = "C" Then
        If sServStatus = "CREDITMEMO" Then
            Call PreInv_AddNew(iRow, Space(110) & "**CREDIT MEMO**", 0, 0)
        Else
            Call PreInv_AddNew(iRow, Space(100) & "**DRAFT CREDIT MEMO**", 0, 0)
        End If
    Else
        If sServStatus = "DEBITMEMO" Then
        '   commented out per Meredith, TS HD # 417  -- LFW, 9/25/03
        '   Call PreInv_AddNew(iRow, Space(110) & "**DEBIT MEMO**", 0, 0)
        Else
            Call PreInv_AddNew(iRow, Space(100) & "**DRAFT DEBIT MEMO**", 0, 0)
        End If
    End If
    
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, "", 0, 0)
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, "", 0, 0)
    iNum = iNum + 1
    iRow = iRow + 1
    
    If asMemo = "C" Then
        sFLine = "Please be advised that we have issued your account a credit for invoice #" & _
        dsPrtCM.fields("ORIG_INVOICE_NUM").Value
    Else
    '   commented out per Meredith, TS HD # 417  -- LFW, 9/25/03
    '    sFLine = "Please be advised that we have issued your account a debit for invoice #" & _
    '    dsPrtCM.fields("ORIG_INVOICE_NUM").Value
    End If
    
    Call PreInv_AddNew(iRow, Space(35) & sFLine, 0, 0)
    
    For iline = 1 To 3
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, "", 0, 0)
    Next iline
    
    If sServStatus = "CREDITMEMO" Or sServStatus = "DEBITMEMO" Then
        sBegStr = Space(16)
    Else
        sBegStr = Space(12) & "Bill No." & sBillNo & Space(4)
    End If
    
    sDesp = dsPrtCM.fields("SERVICE_DESCRIPTION").Value
    
    ' Break up lines by Carrage Return if there is.
    ' Otherwise, break up by 60 instead of 40 characters  -- LFW, 10/9/03
    If InStr(sDesp, vbCrLf) <> 0 Then
        iPos1 = InStr(sDesp, vbCrLf) + 1
        iNum = iNum + 1
        iRow = iRow + 1
            
        Call CCDS_PreInv_AddNew(iRow, Space(5) & Format(dsPrtCM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
            & Space(22) & Trim(Mid$(sDesp, 1, iPos1)), 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), Format(dsPrtCM.fields("SERVICE_RATE").Value, "##,###,###,##0.000"))
            
        Do While InStr(Mid$(sDesp, iPos1 + 1), vbCrLf) <> 0
            iPos2 = InStr(Mid$(sDesp, iPos1 + 1), vbCrLf) + 1
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sDesp, iPos1 + 1, iPos2), 0, 0, 0)
            iPos1 = iPos1 + iPos2
        Loop
       
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sDesp, iPos1 + 1), 0, 0, 0)
    ElseIf Len(sDesp) > 60 Then
        iPos1 = InStr(60, sDesp, " ")

        If iPos1 = 0 Then
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(5) & Format(dsPrtCM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
                & sBegStr & Trim(sDesp), 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
        Else
            iNum = iNum + 1
            iRow = iRow + 1
           Call PreInv_AddNew(iRow, Space(5) & Format(dsPrtCM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
                & sBegStr _
                & Trim(Mid$(sDesp, 1, iPos1)), 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(44) & Mid$(sDesp, iPos1 + 1), 0, 0)
        End If
    Else
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, Space(5) & Format(dsPrtCM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
                & sBegStr _
                & Trim(sDesp), 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
    End If
    
Else
    MsgBox "Memo not found !", vbInformation + vbExclamation, "MEMO"
    ReadyToPrintBni = False
    Exit Function
End If
    
For iline = 1 To 2
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, "", 0, 0)
Next iline
            
iNum = iNum + 1
iRow = iRow + 1
Call PreInv_AddNew(iRow, Space(5) & "-------------------------------------------" _
& "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
                                
iNum = iNum + 1
iRow = iRow + 1
If asMemo = "C" Then
    Call PreInv_AddNew(iRow, Space(160) & "CREDIT TOTAL : ", 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
Else
    ' DEBIT TOTAL -> TOTAL, per Meredith TS HD # 417  -- LFW, 9/25/03
    Call PreInv_AddNew(iRow, Space(160) & "TOTAL : ", 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
End If

iNum = iNum + 1
iRow = iRow + 1
Call PreInv_AddNew(iRow, Space(5) & "-------------------------------------------" _
& "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
If asMemo = "C" Then
    If abIsCreating Then AddInvDt IIf(sServStatus = "CREDITMEMO", sMemoNum, sBillNo), sServStatus
Else
    If abIsCreating Then AddInvDt IIf(sServStatus = "DEBITMEMO", sMemoNum, sBillNo), sServStatus
End If

ReadyToPrintBni = True
stbarCM.SimpleText = "PROCESSING COMPLETE."

Exit Function

ErrHndl:
    ReadyToPrintBni = False
    stbarCM.SimpleText = "PROCESSING FAILED."
End Function

Public Function fnVesselName(LrNum As Long) As String
    Dim SqlStmt As String
    Dim dsVESSEL_PROFILE As Object
    
    SqlStmt = "SELECT Vessel_Name FROM Vessel_Profile where lr_num=" & LrNum
    
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsVESSEL_PROFILE.RecordCount > 0 Then
        If Not IsNull(dsVESSEL_PROFILE.fields("VESSEL_NAME").Value) Then
            fnVesselName = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
        Else
            fnVesselName = ""
        End If
    Else
        fnVesselName = ""
    End If
End Function

Sub PreInv_AddNew(RowNum As Long, Row_Text As String, eof As Integer, Amt As Double)
Dim dsPreInv As Object
Set dsPreInv = OraDatabase.dbcreatedynaset("SELECT * FROM PREINVOICE", 0&)
    With dsPreInv
        .AddNew
        .fields("Row_Num").Value = RowNum
        .fields("Text").Value = Row_Text
        .fields("eof").Value = eof
        .fields("AMT").Value = Amt
        .Update
    End With
End Sub

Sub CCDS_PreInv_AddNew(RowNum As Long, Row_Text As String, eof As Integer, Amt As Double, Rate As Double)
Dim dsPreInv As Object
Set dsPreInv = OraDatabase.dbcreatedynaset("SELECT * FROM PREINVOICE", 0&)
    With dsPreInv
        .AddNew
        .fields("Row_Num").Value = RowNum
        .fields("Text").Value = Row_Text
        .fields("eof").Value = eof
        .fields("AMT").Value = Amt
        .fields("RATE").Value = Rate
        .Update
    End With
End Sub

Public Sub SubPreInv()
    Dim SqlStmt As String
    Dim dsPreInv As Object
    SqlStmt = "SELECT * FROM PreInvoice"
    
    Set dsPreInv = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If dsPreInv.RecordCount <> 0 Then
        OraDatabase.DbExecuteSQL ("DELETE FROM PreInvoice")
    End If

End Sub

Public Function fnCountryName(Country_Code As String) As String
'Get from Customer table based on Customer Code
    
    Dim dsCountry As Object
    Dim SqlStmt As String
    
    SqlStmt = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE =" _
                & "'" & Country_Code & "'"
    Set dsCountry = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsCountry.RecordCount > 0 Then
        If Not IsNull(dsCountry.fields("Country_Name").Value) Then
            fnCountryName = dsCountry.fields("Country_Name").Value
        Else
            fnCountryName = ""
        End If
    Else
        fnCountryName = ""
    End If

    
End Function

Public Sub CalcBal()
Dim dInvAmt As Double
Dim dCMAmt As Double
Dim dAdjAmt As Double
If IsNumeric(txtInvoiceTotal.Text) Then
    dInvAmt = txtInvoiceTotal.Text
    dCMAmt = txtAmount.Text
    dAdjAmt = dInvAmt + dblCurrInvCrTot - dblCurrCMAmt - dCMAmt
    txtAdjTotal.Text = Format(dAdjAmt, "#.00")
End If
End Sub

Private Sub DisableAll()
cmdNewCreateCM.Enabled = False
cmdEditSave.Enabled = False
cmdRetrieve.Enabled = False
cmdDeleteCancel.Enabled = False
cmdPrint.Enabled = False
cmdDone.Enabled = False
cmdExit.Enabled = False


End Sub

Private Sub DisableAllDM()
cmdNewCreateDM.Enabled = False
cmdEditSaveDM.Enabled = False
cmdRetrieveDM.Enabled = False
cmdDeleteCancelDM.Enabled = False
cmdPrintDM.Enabled = False
cmdDoneDM.Enabled = False
cmdExit1.Enabled = False


End Sub

Private Sub ContAfterRetrieve()
DisableAll
txtInvoiceNum.Locked = True
txtCMNum.Locked = True

cmdEditSave.Caption = "&Edit"
cmdDeleteCancel.Caption = "&Delete"
cmdNewCreateCM.Caption = "Create CM"
If bPreCM Then
    cmdNewCreateCM.Enabled = True
    cmdEditSave.Enabled = True
    cmdDeleteCancel.Enabled = True
Else
    cmdNewCreateCM.Enabled = False
    cmdEditSave.Enabled = False
    cmdDeleteCancel.Enabled = False
End If

cmdPrint.Enabled = True
cmdDone.Enabled = True

stbarCM.SimpleText = ""
sMode = ""
End Sub

Private Sub ContAfterRetrieveDM()
DisableAllDM
txtInvoiceNum.Locked = True
txtDMNum.Locked = True

cmdEditSaveDM.Caption = "&Edit"
cmdDeleteCancelDM.Caption = "&Delete"
cmdNewCreateDM.Caption = "Create DM"
If bPreDM Then
    cmdNewCreateDM.Enabled = True
    cmdEditSaveDM.Enabled = True
    cmdDeleteCancelDM.Enabled = True
Else
    cmdNewCreateDM.Enabled = False
    cmdEditSaveDM.Enabled = False
    cmdDeleteCancelDM.Enabled = False
End If

cmdPrintDM.Enabled = True
cmdDoneDM.Enabled = True

stbarCM.SimpleText = ""
sModeDM = ""
End Sub

Private Function ChkExisting(ByVal alOrigInvNum As Long, ByVal asMemoType As String) As Boolean
Dim sSql As String
Dim dsMCount As Object
On Error GoTo errHandleChkExisting
If asMemoType = "CM" Then
    sSql = "SELECT COUNT(*) FROM BILLING WHERE ORIG_INVOICE_NUM = " & alOrigInvNum
    sSql = sSql & " AND BILLING_TYPE = 'CM'"
Else
    sSql = "SELECT COUNT(*) FROM BILLING WHERE ORIG_INVOICE_NUM = " & alOrigInvNum
    sSql = sSql & " AND BILLING_TYPE = 'DM'"
End If
Set dsMCount = OraDatabase.dbcreatedynaset(sSql, 0&)
If Not dsMCount.BOF And Not dsMCount.eof Then
    If Not IsNull(dsMCount.fields(0).Value) Then
        If dsMCount.fields(0).Value > 0 Then
            ChkExisting = True
        End If
    End If
End If

Exit Function
errHandleChkExisting:
    MsgBox "Error occured while checking for Credit Memos", , "- frmCreditMemo - ChkExisting"
    MN
End Function

Private Function ConfirmM(ByVal abDisableAdd As Boolean, ByVal asMtype As String) As Long
Dim sSql As String
Dim frmPV As frmCMPV
'DISPLAY
If asMtype = "CM" Then
    sSql = "SELECT BILLING_NUM, SERVICE_DATE, SERVICE_AMOUNT, SERVICE_STATUS "
    sSql = sSql & "FROM BILLING WHERE ORIG_INVOICE_NUM = " & lCurrInvNum
    sSql = sSql & " AND BILLING_TYPE = 'CM'"
ElseIf asMtype = "DM" Then
    sSql = "SELECT BILLING_NUM, SERVICE_DATE, SERVICE_AMOUNT, SERVICE_STATUS "
    sSql = sSql & "FROM BILLING WHERE ORIG_INVOICE_NUM = " & lCurrInvNum
    sSql = sSql & " AND BILLING_TYPE = 'DM'"
End If
Set frmPV = New frmCMPV
frmPV.fsSql = sSql
If asMtype = "CM" Then
    frmPV.Caption = "Existing Credit/Pre-Credit Memos"
    frmPV.lblDisp.Caption = "Credit Memos issued against Selected Invoice:"
ElseIf asMtype = "DM" Then
    frmPV.Caption = "Existing Debit/Pre-Debit Memos"
    frmPV.lblDisp.Caption = "Debit Memos issued against Selected Invoice:"
End If

frmPV.fbDisableAdd = abDisableAdd
frmPV.fbCancel = True

frmPV.Refresh
frmPV.Show 1

If frmPV.fbCancel Then
    Unload frmPV
    Set frmPV = Nothing
    Exit Function
End If

If frmPV.fbNew Then
    bChkAddNew = True
    Unload frmPV
    Set frmPV = Nothing

Else
    ConfirmM = CLng(frmPV.fsVal)
    Unload frmPV
    Set frmPV = Nothing
End If
End Function

Private Function ReadyToPrintCcds(ByVal asMemo As String, ByVal abIsCreating As Boolean) As Boolean
Dim dsPrtCM As Object
Dim dsCUSTOMER_PROFILE As Object

Dim sSql As String
Dim sCityStateZip As String
Dim iline As Integer
Dim iCustId As Long
Dim iLRNUM As Long
Dim sServStatus As String
Dim iRow As Long
Dim iNum As Integer
Dim sMemoNum As String
Dim sInvDt As String
Dim iPos1 As Integer
Dim sBillNo As String
Dim i As Integer
Dim sDesp As String
On Error GoTo ErrHndl
Dim iPos2 As Integer
Dim iAdd As Integer
Dim sFLine As String

iCustId = 0
iLRNUM = 0
iline = 0
iNum = 0

sSql = "DELETE FROM PREINVOICE"
OraDatabase.EXECUTESQL sSql

If asMemo = "C" Then
    stbarCM.SimpleText = "PROCESSING CREDIT MEMO..."
    sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & lCurrCMNum & " AND BILLING_TYPE = 'CM'"
Else
    stbarCM.SimpleText = "PROCESSING DEBIT MEMO..."
    sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & lCurrDMNum & " AND BILLING_TYPE = 'DM'"
End If

Set dsPrtCM = OraDatabase.dbcreatedynaset(sSql, 0&)

If Not dsPrtCM.eof And Not dsPrtCM.BOF Then
    dsPrtCM.MoveFirst
    sBillNo = dsPrtCM.fields("billing_num").Value
    'Get from Customer table based on Customer Code
    sSql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " _
              & "'" & dsPrtCM.fields("CUSTOMER_ID").Value & "'"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sSql, 0&)

    'Check to see if we need to change the invoice number and headings on the page
    iCustId = dsPrtCM.fields("CUSTOMER_ID").Value
    iLRNUM = dsPrtCM.fields("LR_NUM").Value
    sMemoNum = dsPrtCM.fields("MEMO_NUM").Value & ""
    sServStatus = dsPrtCM.fields("SERVICE_STATUS").Value
    
    iNum = 0
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, "", 1, 0, 0)
    
    For iline = 1 To 3
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    Next iline
    
    iNum = iNum + 1
    iRow = iRow + 1
    
    If asMemo = "C" Then
        If sServStatus = "CREDITMEMO" Then
            Call CCDS_PreInv_AddNew(iRow, Space(190) & "  Invoice No:  " & sMemoNum, 0, 0, 0)
        Else
            Call CCDS_PreInv_AddNew(iRow, Space(190) & "  Invoice No:  ", 0, 0, 0)
        End If
    Else
        If sServStatus = "DEBITMEMO" Then
            Call CCDS_PreInv_AddNew(iRow, Space(190) & "  Invoice No:  " & sMemoNum, 0, 0, 0)
        Else
            Call CCDS_PreInv_AddNew(iRow, Space(190) & "  Invoice No:  ", 0, 0, 0)
        End If
    End If

    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    
    iNum = iNum + 1
    iRow = iRow + 1
    If sServStatus = "CREDITMEMO" Or sServStatus = "DEBITMEMO" Then
        Call CCDS_PreInv_AddNew(iRow, Space(190) & "Invoice Date: " & Format(dsPrtCM.fields("INVOICE_DATE").Value, "mm/dd/yy"), 0, 0, 0)
    Else
        Call CCDS_PreInv_AddNew(iRow, Space(190) & "Invoice Date: " & Format(Now, "mm/dd/yy"), 0, 0, 0)
    End If
    
    For iline = 1 To 6
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    Next iline
        
    iAdd = 0
    If Not IsNull(dsPrtCM.fields("CARE_OF")) Then
        If (dsPrtCM.fields("CARE_OF").Value = "1") Or (dsPrtCM.fields("CARE_OF").Value = "Y") Then
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(25) & fnVesselName(iLRNUM), 0, 0, 0)

            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(25) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value & "   " & iCustId, 0, 0, 0)
        Else
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(25) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0, 0)
            iAdd = iAdd + 1
        End If
    Else
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(25) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0, 0)
        iAdd = iAdd + 1
    End If
    
    
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value) Then
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(25) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value, 0, 0, 0)
    Else
        iAdd = iAdd + 1
    End If
    
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) Then
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(25) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value, 0, 0, 0)
    Else
        iAdd = iAdd + 1
    End If
    sCityStateZip = ""
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value) Then
        sCityStateZip = dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value
    End If
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value) Then
        sCityStateZip = sCityStateZip & ", " & dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value
    End If
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value) Then
        sCityStateZip = sCityStateZip & " - " & dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value
    End If
    
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, Space(25) & sCityStateZip, 0, 0, 0)
    
    If dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value <> "US" Then
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(25) & fnCountryName(dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value), 0, 0, 0)
    Else
        iAdd = iAdd + 1
    End If

    For iline = 1 To 4 + iAdd
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    Next iline
    
    sBillNo = dsPrtCM.fields("BILLING_NUM").Value
    iNum = iNum + 1
    iRow = iRow + 1
    
    If asMemo = "C" Then
        If sServStatus = "CREDITMEMO" Then
            Call CCDS_PreInv_AddNew(iRow, Space(105) & "**CREDIT MEMO**", 0, 0, 0)
        Else
            Call CCDS_PreInv_AddNew(iRow, Space(95) & "**DRAFT CREDIT MEMO**", 0, 0, 0)
        End If
    Else
        If sServStatus = "DEBITMEMO" Then
        '   commented out per Meredith, TS HD # 417  -- LFW, 9/25/03
        '   Call CCDS_PreInv_AddNew(iRow, Space(105) & "**DEBIT MEMO**", 0, 0, 0)
        Else
            Call CCDS_PreInv_AddNew(iRow, Space(95) & "**DRAFT DEBIT MEMO**", 0, 0, 0)
        End If
    End If
    
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    
    If asMemo = "C" Then
        sFLine = "Please be advised that we have issued your account a credit for invoice # " & _
        dsPrtCM.fields("ORIG_INVOICE_NUM").Value
    Else
    '   commented out per Meredith, TS HD # 417  -- LFW, 9/25/03
    '   sFLine = "Please be advised that we have issued your account a debit for invoice #" & _
    '   dsPrtCM.fields("ORIG_INVOICE_NUM").Value
    End If

'    iNum = iNum + 1
'    iRow = iRow + 1
'    Call CCDS_PreInv_AddNew(iRow, Space(41) & sFLine, 0, 0, 0)
    If asMemo = "C" And Len(sFLine) > 90 Then
        
        iPos1 = InStr(60, sFLine, " ")
        
        If iPos1 = 0 Then
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(41) & sFLine, 0, 0, 0)
        Else
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(41) & Trim(Mid$(sFLine, 1, iPos1)), 0, 0, 0)
            
            Do While Len(Mid$(sFLine, iPos1 + 1)) > 90
                iPos2 = InStr(60, Mid$(sFLine, iPos1 + 1), " ")
                If iPos2 = 0 Then
                    Exit Do
                End If
                iNum = iNum + 1
                iRow = iRow + 1
                Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sFLine, iPos1 + 1, iPos2), 0, 0, 0)
                iPos1 = iPos1 + iPos2
            Loop
       
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sFLine, iPos1 + 1), 0, 0, 0)
        End If
    Else
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(41) & Trim(sFLine), 0, 0, 0)
    End If
    
    For i = 1 To 3
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    Next
    
    If sServStatus = "CREDITMEMO" Or sServStatus = "DEBITMEMO" Then
        sDesp = dsPrtCM.fields("SERVICE_DESCRIPTION").Value
    Else
        sDesp = "Bill No." & sBillNo & "   " & dsPrtCM.fields("SERVICE_DESCRIPTION").Value
    End If
    
    iPos1 = 0
    iPos2 = 0
    
    ' Break up lines by Carrage Return if there is.
    ' Otherwise, break up by 60 instead of 40 characters  -- LFW, 10/9/03
    If InStr(sDesp, vbCrLf) <> 0 Then
        iPos1 = InStr(sDesp, vbCrLf) + 1
        iNum = iNum + 1
        iRow = iRow + 1
            
        Call CCDS_PreInv_AddNew(iRow, Space(5) & Format(dsPrtCM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
            & Space(22) & Trim(Mid$(sDesp, 1, iPos1)), 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), Format(dsPrtCM.fields("SERVICE_RATE").Value, "##,###,###,##0.000"))
            
        Do While InStr(Mid$(sDesp, iPos1 + 1), vbCrLf) <> 0
            iPos2 = InStr(Mid$(sDesp, iPos1 + 1), vbCrLf) + 1
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sDesp, iPos1 + 1, iPos2), 0, 0, 0)
            iPos1 = iPos1 + iPos2
        Loop
       
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sDesp, iPos1 + 1), 0, 0, 0)
    ElseIf Len(sDesp) > 60 Then
        iPos1 = InStr(60, sDesp, " ")

        If iPos1 = 0 Then
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(5) & Format(dsPrtCM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
            & Space(22) & sDesp, 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), Format(dsPrtCM.fields("SERVICE_RATE").Value, "##,###,###,##0.000"))
        Else
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(5) & Format(dsPrtCM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
                & Space(22) & Trim(Mid$(sDesp, 1, iPos1)), 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), Format(dsPrtCM.fields("SERVICE_RATE").Value, "##,###,###,##0.000"))

            Do While Len(Mid$(sDesp, iPos1 + 1)) > 60
                iPos2 = InStr(60, Mid$(sDesp, iPos1 + 1), " ")
                If iPos2 = 0 Then
                    Exit Do
                End If
                iNum = iNum + 1
                iRow = iRow + 1
                Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sDesp, iPos1 + 1, iPos2), 0, 0, 0)
                iPos1 = iPos1 + iPos2
            Loop

            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sDesp, iPos1 + 1), 0, 0, 0)
        End If
    Else
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(5) & Format(dsPrtCM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
            & Space(22) & Trim(sDesp), 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), Format(dsPrtCM.fields("SERVICE_RATE").Value, "##,###,###,##0.000"))
    End If
Else
    MsgBox "No Records Found For the Memo !", vbInformation + vbExclamation, "MEMO"
    ReadyToPrintCcds = False
    Exit Function
End If
    
For iline = 1 To 2
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
Next iline
                                            
iNum = iNum + 1
iRow = iRow + 1
If asMemo = "C" Then
    Call CCDS_PreInv_AddNew(iRow, Space(170) & "CREDIT TOTAL : ", 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), 0)
    If abIsCreating Then AddInvDt IIf(sServStatus = "CREDITMEMO", sMemoNum, sBillNo), sServStatus
Else
    ' DEBIT TOTAL -> TOTAL, per Meredith TS HD # 417  -- LFW, 9/25/03
    Call CCDS_PreInv_AddNew(iRow, Space(170) & "TOTAL : ", 0, Format(dsPrtCM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), 0)
    If abIsCreating Then AddInvDt IIf(sServStatus = "DEBITMEMO", sMemoNum, sBillNo), sServStatus
End If
ReadyToPrintCcds = True
stbarCM.SimpleText = "PROCESSING COMPLETE."

Exit Function

ErrHndl:
ReadyToPrintCcds = False
stbarCM.SimpleText = "PROCESSING COMPLETE."
End Function

Private Sub AddInvDt(ByVal asNum As String, ByVal asNumType As String)
Dim sSql As String
Dim dsID As Object
Dim dsChk As Object
Dim sID As String

sSql = "SELECT * FROM INVOICEDATE WHERE TYPE = 'CM' AND START_INV_NO = '" & asNum & "'"
Set dsChk = OraDatabase.dbcreatedynaset(sSql, 0&)
If dsChk.eof And dsChk.BOF Then
    sSql = "SELECT MAX(ID) MAXID FROM INVOICEDATE"
    Set dsID = OraDatabase.dbcreatedynaset(sSql, 0&)
    If dsID.RecordCount > 0 Then
        If Not IsNull(dsID.fields("MAXID").Value) Then
            sID = dsID.fields("MAXID").Value
        Else
            sID = 0
        End If
    Else
        sID = 0
    End If
    sID = sID + 1
    Call AddNewInvDt(CInt(sID), Format(Now, "MM/DD/YYYY"), IIf(asNumType = "CREDITMEMO", "I", "B"), "CM", "", "", asNum, asNum)
End If
End Sub

Sub AddNewInvDt(id As Integer, RnDt As Date, BType As String, sType As String, stDt As String, EdDt As String, sStInvNo As String, sEdInvNo As String)
    Dim dsINVDATE As Object
    Dim SqlStmt As String
    
    SqlStmt = "SELECT * FROM INVOICEDATE"
    Set dsINVDATE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        With dsINVDATE
            .AddNew
            .fields("ID").Value = id
            .fields("RUN_DATE").Value = RnDt
            .fields("BILL_TYPE").Value = sType
            .fields("TYPE").Value = BType
            If stDt <> "" Then .fields("START_DATE").Value = CDate(stDt)
            If EdDt <> "" Then .fields("CUTOFF_DATE").Value = CDate(EdDt)
            .fields("START_INV_NO").Value = sStInvNo
            .fields("END_INV_NO").Value = sEdInvNo
            .Update
        End With
    Else
        MsgBox OraSession.LastServerErrText, vbInformation, "BILLING"
    End If
End Sub

Private Function ValidData(ByVal asMemo As String) As Boolean
    Dim oCon As Control
    Dim sConVal As String
    Dim sConTag As String
    Dim bError As Boolean
    bError = False
    If txtInvoiceNum.Text = "" Then
        MsgBox "Enter a Invoice Number"
        ValidData = False
        Exit Function
    End If
    For Each oCon In Me.Controls
        If TypeName(oCon) = "TextBox" Then
            sConVal = oCon.Text
            sConTag = oCon.Tag
            If Left(sConTag, 1) = asMemo Then
                Select Case Mid(sConTag, 2, 1)
                Case "D"
                    If sConVal = "" Then
                        MsgBox "Enter " & Mid(sConTag, 3)
                        bError = True
                    Else
                        If Not IsDate(sConVal) Then
                            MsgBox "Enter Valid " & Mid(sConTag, 3)
                            bError = True
                        End If
                    End If
                Case "N"
                    If sConVal = "" Then
                        MsgBox "Enter " & Mid(sConTag, 3)
                        bError = True
                    Else
                        If Not IsNumeric(sConVal) Then
                            MsgBox "Enter Valid " & Mid(sConTag, 3)
                            bError = True
                        End If
                    End If
                    
                Case "S"
                    If sConVal = "" Then
                        MsgBox "Enter " & Mid(sConTag, 3)
                        bError = True
                    End If
                
                End Select
            End If
        End If
        If bError Then Exit For
    Next
    If bError Then
        ValidData = False
        Exit Function
    Else
        ValidData = True
    End If

End Function

Public Function fnMaxCMNum(ByVal asOrigInvNum As String)
Dim dsMemo As Object
Dim sSql As String
Dim sAlpha As String
Dim sMNum As String
sSql = "SELECT MAX(MEMO_NUM) FROM BILLING WHERE ORIG_INVOICE_NUM = " & asOrigInvNum & " AND BILLING_TYPE = 'CM'"
Set dsMemo = OraDatabase.dbcreatedynaset(sSql, 0&)

If Not IsNull(dsMemo.fields(0).Value) Then
    sAlpha = Right(dsMemo.fields(0).Value, 1)
    If Asc(sAlpha) = 90 Then
        fnMaxCMNum = "ERROR"
        Exit Function
    End If
    sAlpha = Chr(Asc(sAlpha) + 1)
    sMNum = asOrigInvNum & "C" & sAlpha
Else
    sMNum = asOrigInvNum & "CA"
End If

fnMaxCMNum = sMNum

End Function

Public Function fnMaxDMNum(ByVal asOrigInvNum As String)
Dim dsMemo As Object
Dim sSql As String
Dim sAlpha As String
Dim sMNum As String
sSql = "SELECT MAX(MEMO_NUM) FROM BILLING WHERE ORIG_INVOICE_NUM = " & asOrigInvNum & " AND BILLING_TYPE = 'DM'"
Set dsMemo = OraDatabase.dbcreatedynaset(sSql, 0&)
If Not IsNull(dsMemo.fields(0).Value) Then
    sAlpha = Right(dsMemo.fields(0).Value, 1)
    If Asc(sAlpha) = 90 Then
        fnMaxDMNum = "ERROR"
        Exit Function
    End If
    sAlpha = Chr(Asc(sAlpha) + 1)
    sMNum = asOrigInvNum & "D" & sAlpha
Else
    sMNum = asOrigInvNum & "DA"
End If
fnMaxDMNum = sMNum
End Function

Private Sub txtServiceCode_LostFocus()
Dim dsProd As Object
Dim sql As String
    If Trim$(txtServiceCode.Text) = "0" Then
      txtServiceCode.Text = "0000"
    End If

    If Trim$(txtServiceCode.Text) <> "" Then
      sql = "select * from fnd_flex_values where flex_value_set_id = '1005836' and flex_value='" & Trim$(txtServiceCode.Text) & "'"
'      Set dsProd = OraDatabaseProd.dbcreatedynaset(sql, 0&)
      Set dsProd = OraDatabase.dbcreatedynaset(sql, 0&)
      If IsNull(dsProd.fields(0).Value) Then
         MsgBox "Service Code Invalid, Please try again!", vbExclamation, "Service Code"
         txtServiceCode.SetFocus
      End If
    Else
        MsgBox "No Service Code, Please try again!", vbExclamation, "Service Code"
        txtServiceCode.SetFocus
    End If
    
    Set dsProd = Nothing        '2258 4/2/2007 Rudy:
End Sub

Private Sub txtAssetCode_LostFocus()
Dim dsProd As Object
Dim sql As String
    If Trim$(txtAssetCode.Text) = "0" Then
      txtAssetCode.Text = "0000"
    End If

    If Trim$(txtAssetCode.Text) <> "" Then
      sql = "select * from fnd_flex_values where flex_value_set_id = '1005838' and flex_value='" & Trim$(txtAssetCode.Text) & "'"
      Set dsProd = OraDatabase.dbcreatedynaset(sql, 0&)
'      Set dsProd = OraDatabaseProd.dbcreatedynaset(sql, 0&)
      If IsNull(dsProd.fields(0).Value) Then
         MsgBox "Asset Code Invalid, Please try again!", vbExclamation, "Asset Code"
         txtAssetCode.SetFocus
      End If
    Else
        MsgBox "No Asset Code, Please try again!", vbExclamation, "Asset Code"
        txtAssetCode.SetFocus
    End If
    
    Set dsProd = Nothing        '2258 4/2/2007 Rudy:
End Sub

Private Sub txtGLCode_LostFocus()
Dim dsProd As Object
Dim sql As String
    If Trim$(txtGLCode.Text) = "0" Then
      txtGLCode.Text = "0000"
    End If

    If Trim$(txtGLCode.Text) <> "" Then
      sql = "select * from fnd_flex_values where flex_value_set_id = '1005835' and flex_value='" & Trim$(txtGLCode.Text) & "'"
      Set dsProd = OraDatabase.dbcreatedynaset(sql, 0&)
'      Set dsProd = OraDatabaseProd.dbcreatedynaset(sql, 0&)
      If IsNull(dsProd.fields(0).Value) Then
         MsgBox "GL Code Invalid, Please try again!", vbExclamation, "GL Code"
         txtGLCode.SetFocus
      End If
    Else
        MsgBox "No GL Code, Please try again!", vbExclamation, "GL Code"
        txtGLCode.SetFocus
    End If
    
    Set dsProd = Nothing        '2258 4/2/2007 Rudy:
End Sub

Private Sub txtCommCode_LostFocus()
Dim dsProd As Object
Dim sql As String
    If Trim$(txtCommCode.Text) = "0" Then
      txtCommCode.Text = "0000"
    End If

    If Trim$(txtCommCode.Text) <> "" Then
      sql = "select * from fnd_flex_values where flex_value_set_id = '1005837'  and flex_value='" & Trim$(txtCommCode.Text) & "'"
      Set dsProd = OraDatabase.dbcreatedynaset(sql, 0&)
'      Set dsProd = OraDatabaseProd.dbcreatedynaset(sql, 0&)
      If IsNull(dsProd.fields(0).Value) Then
         MsgBox "Commodity Code Invalid, Please try again!", vbExclamation, "Commodity Code"
         txtCommCode.SetFocus
      End If
    Else
        MsgBox "No Commodity Code, Please try again!", vbExclamation, "Commodity Code"
        txtCommCode.SetFocus
    End If
    
    Set dsProd = Nothing        '2258 4/2/2007 Rudy:
End Sub


Private Sub txtServiceCodeDM_LostFocus()
Dim dsProd As Object
Dim sql As String
  
  If Trim$(txtServiceCodeDM.Text) = "0" Then
    txtServiceCodeDM.Text = "0000"
  End If
    
  If Trim$(txtServiceCodeDM.Text) <> "" Then
    sql = "select * from fnd_flex_values where flex_value_set_id = '1005836' and flex_value='" & Trim$(txtServiceCodeDM.Text) & "'"
    Set dsProd = OraDatabase.dbcreatedynaset(sql, 0&)
'    Set dsProd = OraDatabaseProd.dbcreatedynaset(sql, 0&)
    If IsNull(dsProd.fields(0).Value) Then
      MsgBox "Service Code Invalid, Please try again!", vbExclamation, "Service Code"
      txtServiceCodeDM.SetFocus
    End If
  Else
    If sModeDM = "" Then        '2258 4/2/2007 Rudy: put it in this if
      Exit Sub
    Else
      MsgBox "No Service Code, Please try again!", vbExclamation, "Service Code"
      txtServiceCodeDM.SetFocus
    End If
  End If
    
    Set dsProd = Nothing        '2258 4/2/2007 Rudy:
End Sub

Private Sub txtAssetCodeDM_LostFocus()
Dim dsProd As Object
Dim sql As String
  If Trim$(txtAssetCodeDM.Text) = "0" Then
    txtAssetCodeDM.Text = "0000"
  End If
    
  If Trim$(txtAssetCodeDM.Text) <> "" Then
    sql = "select * from fnd_flex_values where flex_value_set_id = '1005838' and flex_value='" & Trim$(txtAssetCodeDM.Text) & "'"
    Set dsProd = OraDatabase.dbcreatedynaset(sql, 0&)
'    Set dsProd = OraDatabaseProd.dbcreatedynaset(sql, 0&)
    If IsNull(dsProd.fields(0).Value) Then
      MsgBox "Asset Code Invalid, Please try again!", vbExclamation, "Asset Code"
      txtAssetCodeDM.SetFocus
    End If
  Else
    If sModeDM = "" Then        '2258 4/2/2007 Rudy: put it in this if
      Exit Sub
    Else
      MsgBox "No Asset Code, Please try again!", vbExclamation, "Asset Code"
      txtAssetCodeDM.SetFocus
    End If
  End If
    
  Set dsProd = Nothing          '2258 4/2/2007 Rudy:
End Sub

Private Sub txtGLCodeDM_LostFocus()
Dim dsProd As Object
Dim sql As String
  If Trim$(txtGLCodeDM.Text) = "0" Then
    txtGLCodeDM.Text = "0000"
  End If
  
  If Trim$(txtGLCodeDM.Text) <> "" Then
    sql = "select * from fnd_flex_values where flex_value_set_id = '1005835' and flex_value='" & Trim$(txtGLCodeDM.Text) & "'"
    Set dsProd = OraDatabase.dbcreatedynaset(sql, 0&)
'    Set dsProd = OraDatabaseProd.dbcreatedynaset(sql, 0&)
    If IsNull(dsProd.fields(0).Value) Then
       MsgBox "GL Code Invalid, Please try again!", vbExclamation, "GL Code"
       txtGLCodeDM.SetFocus
    End If
  Else
    If sModeDM = "" Then        '2258 4/2/2007 Rudy: put it in this if
      Exit Sub
    Else
      MsgBox "No GL Code, Please try again!", vbExclamation, "GL Code"
      txtGLCodeDM.SetFocus
    End If
  End If
    
  Set dsProd = Nothing        '2258 4/2/2007 Rudy:
End Sub

Private Sub txtCommCodeDM_LostFocus()
Dim dsProd As Object
Dim sql As String
  If Trim$(txtCommCodeDM.Text) = "0" Then
    txtCommCodeDM.Text = "0000"
  End If
    
  If Trim$(txtCommCodeDM.Text) <> "" Then
    sql = "select * from fnd_flex_values where flex_value_set_id = '1005837'  and flex_value='" & Trim$(txtCommCodeDM.Text) & "'"
    Set dsProd = OraDatabase.dbcreatedynaset(sql, 0&)
'    Set dsProd = OraDatabaseProd.dbcreatedynaset(sql, 0&)
    If IsNull(dsProd.fields(0).Value) Then
       MsgBox "Commodity Code Invalid, Please try again!", vbExclamation, "Commodity Code"
       If Len(Trim(txtAssetCodeDM.Text)) <> 0 Then    '2258 4/2/2007 Rudy: creates and endless loop with Asset
          txtCommCodeDM.SetFocus
       End If
    End If
  Else
    If sModeDM = "" Then        '2258 4/2/2007 Rudy: put it in this if
      Exit Sub
    Else
      MsgBox "No Commodity Code, Please try again!", vbExclamation, "Commodity Code"
      If Len(Trim(txtAssetCodeDM.Text)) <> 0 Then    '2258 4/2/2007 Rudy: creates and endless loop with Asset
         txtCommCodeDM.SetFocus
      End If
    End If
  End If
    
  Set dsProd = Nothing        '2258 4/2/2007 Rudy:
End Sub
