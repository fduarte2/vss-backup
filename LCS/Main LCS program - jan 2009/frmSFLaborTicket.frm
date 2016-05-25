VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Begin VB.Form frmSFLaborTicket 
   Caption         =   "Simplified Labor Ticket"
   ClientHeight    =   10695
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   11670
   LinkTopic       =   "Form1"
   ScaleHeight     =   10695
   ScaleWidth      =   11670
   StartUpPosition =   3  'Windows Default
   Begin VB.OptionButton optComm 
      Caption         =   "List All Commodities"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   1
      Left            =   8880
      TabIndex        =   54
      Top             =   2040
      Width           =   2295
   End
   Begin VB.OptionButton optComm 
      Caption         =   "List Relevant Commodities"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   0
      Left            =   5880
      TabIndex        =   53
      Top             =   2040
      Width           =   2775
   End
   Begin VB.CommandButton cmdAll 
      BackColor       =   &H0000FF00&
      Caption         =   "Save AND Print"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   24
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   855
      Left            =   360
      Style           =   1  'Graphical
      TabIndex        =   51
      Top             =   9600
      Width           =   6255
   End
   Begin VB.CommandButton cmdRetrieve 
      Caption         =   "Retrieve"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   4920
      TabIndex        =   50
      Top             =   3600
      Width           =   2055
   End
   Begin VB.OptionButton optCust 
      Caption         =   "List All Customers"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Index           =   1
      Left            =   8880
      TabIndex        =   49
      Top             =   1440
      Width           =   2295
   End
   Begin VB.OptionButton optCust 
      Caption         =   "List Relevent Customers"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Index           =   0
      Left            =   5880
      TabIndex        =   48
      Top             =   1440
      Width           =   2775
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnCount 
      Height          =   195
      Left            =   4320
      TabIndex        =   47
      Top             =   120
      Width           =   615
      DataFieldList   =   "Column 0"
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   1085
      _ExtentY        =   344
      _StockProps     =   77
      DataFieldToDisplay=   "Column 0"
   End
   Begin VB.TextBox txtSup 
      Enabled         =   0   'False
      Height          =   330
      Left            =   1800
      TabIndex        =   29
      Top             =   2520
      Width           =   2295
   End
   Begin VB.TextBox txtCust 
      Height          =   330
      Left            =   1800
      Locked          =   -1  'True
      TabIndex        =   27
      Top             =   1560
      Width           =   3255
   End
   Begin VB.TextBox txtShip 
      Height          =   330
      Left            =   7200
      TabIndex        =   26
      Top             =   3030
      Width           =   3255
   End
   Begin VB.TextBox txtComm 
      Height          =   330
      Left            =   1800
      TabIndex        =   25
      Top             =   2040
      Width           =   3135
   End
   Begin VB.TextBox txtArea 
      Height          =   330
      Left            =   7200
      MaxLength       =   10
      TabIndex        =   24
      Top             =   2520
      Width           =   1815
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   300
      Left            =   10200
      TabIndex        =   23
      Top             =   9720
      Width           =   1335
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&Print"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   300
      Left            =   240
      TabIndex        =   22
      Top             =   9480
      Visible         =   0   'False
      Width           =   1335
   End
   Begin VB.CommandButton cmdCustList 
      Height          =   375
      Left            =   5160
      Picture         =   "frmSFLaborTicket.frx":0000
      Style           =   1  'Graphical
      TabIndex        =   19
      Top             =   1560
      Width           =   375
   End
   Begin VB.CommandButton cmdShipList 
      Height          =   375
      Left            =   10560
      Picture         =   "frmSFLaborTicket.frx":0102
      Style           =   1  'Graphical
      TabIndex        =   18
      Top             =   3000
      Width           =   375
   End
   Begin VB.CommandButton cmdCommList 
      Height          =   375
      Left            =   5160
      Picture         =   "frmSFLaborTicket.frx":0204
      Style           =   1  'Graphical
      TabIndex        =   17
      Top             =   2040
      Width           =   375
   End
   Begin VB.CommandButton cmdLocationList 
      Height          =   375
      Left            =   9240
      Picture         =   "frmSFLaborTicket.frx":0306
      Style           =   1  'Graphical
      TabIndex        =   16
      Top             =   2520
      Width           =   375
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "&Exit"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   10230
      TabIndex        =   15
      Top             =   90
      Width           =   1245
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&Delete"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   300
      Left            =   10200
      TabIndex        =   14
      Top             =   10320
      Width           =   1335
   End
   Begin VB.TextBox txtNo 
      Height          =   330
      Left            =   1800
      TabIndex        =   13
      Top             =   1125
      Width           =   1695
   End
   Begin VB.TextBox txtJobDesc 
      Height          =   945
      Left            =   1800
      MaxLength       =   400
      MultiLine       =   -1  'True
      TabIndex        =   12
      Top             =   4170
      Width           =   9165
   End
   Begin VB.TextBox txtCustId 
      Enabled         =   0   'False
      Height          =   330
      Left            =   1350
      TabIndex        =   11
      Top             =   1590
      Visible         =   0   'False
      Width           =   375
   End
   Begin VB.TextBox txtShipId 
      Enabled         =   0   'False
      Height          =   330
      Left            =   6480
      TabIndex        =   10
      Top             =   3030
      Visible         =   0   'False
      Width           =   495
   End
   Begin VB.TextBox txtCommId 
      Enabled         =   0   'False
      Height          =   375
      Left            =   1320
      TabIndex        =   9
      Top             =   2040
      Visible         =   0   'False
      Width           =   255
   End
   Begin VB.TextBox txtService 
      Enabled         =   0   'False
      Height          =   330
      Left            =   1800
      Locked          =   -1  'True
      TabIndex        =   5
      Top             =   3000
      Width           =   3255
   End
   Begin VB.CommandButton cmdServiceList 
      Height          =   375
      Left            =   5160
      Picture         =   "frmSFLaborTicket.frx":0408
      Style           =   1  'Graphical
      TabIndex        =   4
      Top             =   3000
      Width           =   375
   End
   Begin VB.TextBox txtServiceGroup 
      Height          =   375
      Left            =   0
      TabIndex        =   3
      Top             =   3000
      Visible         =   0   'False
      Width           =   375
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnRateType 
      Height          =   195
      Left            =   3600
      TabIndex        =   0
      Top             =   480
      Width           =   495
      DataFieldList   =   "Column 0"
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   873
      _ExtentY        =   344
      _StockProps     =   77
      BackColor       =   -2147483648
      DataFieldToDisplay=   "Column 0"
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnLaborType 
      Height          =   195
      Left            =   5040
      TabIndex        =   1
      Top             =   480
      Width           =   525
      DataFieldList   =   "Column 0"
      ListWidth       =   1764
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      ColumnHeaders   =   0   'False
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   926
      _ExtentY        =   344
      _StockProps     =   77
      BackColor       =   -2147483648
      DataFieldToDisplay=   "Column 0"
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGDate1 
      Height          =   225
      Left            =   2640
      TabIndex        =   2
      Top             =   120
      Visible         =   0   'False
      Width           =   255
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   0
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   450
      _ExtentY        =   397
      _StockProps     =   79
      Caption         =   "SSDBGDate1"
   End
   Begin Crystal.CrystalReport crw1 
      Left            =   1320
      Top             =   120
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGDate4 
      Height          =   255
      Left            =   1920
      TabIndex        =   6
      Top             =   120
      Visible         =   0   'False
      Width           =   255
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   0
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   450
      _ExtentY        =   450
      _StockProps     =   79
      Caption         =   "SSDBGrid1"
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGDate3 
      Height          =   255
      Left            =   2280
      TabIndex        =   7
      Top             =   120
      Visible         =   0   'False
      Width           =   255
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   0
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   450
      _ExtentY        =   450
      _StockProps     =   79
      Caption         =   "SSDBGrid1"
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGDate2 
      Height          =   255
      Left            =   1920
      TabIndex        =   8
      Top             =   480
      Visible         =   0   'False
      Width           =   255
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   0
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   450
      _ExtentY        =   450
      _StockProps     =   79
      Caption         =   "SSDBGrid1"
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnType 
      Height          =   225
      Left            =   4320
      TabIndex        =   20
      Top             =   480
      Width           =   525
      DataFieldList   =   "Column 0"
      ListWidth       =   1764
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      ColumnHeaders   =   0   'False
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   926
      _ExtentY        =   397
      _StockProps     =   77
      DataFieldToDisplay=   "Column 0"
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnTime 
      Height          =   195
      Left            =   3600
      TabIndex        =   21
      Top             =   120
      Width           =   495
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   20
      ListWidth       =   1764
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      ColumnHeaders   =   0   'False
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   873
      _ExtentY        =   344
      _StockProps     =   77
      DataFieldToDisplay=   "Column 0"
   End
   Begin MSComCtl2.DTPicker DTPDate 
      Height          =   375
      Left            =   9000
      TabIndex        =   28
      Top             =   1080
      Width           =   1335
      _ExtentX        =   2355
      _ExtentY        =   661
      _Version        =   393216
      Format          =   48431105
      CurrentDate     =   37305
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGDate5 
      Height          =   255
      Left            =   3000
      TabIndex        =   30
      Top             =   120
      Visible         =   0   'False
      Width           =   255
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   0
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   450
      _ExtentY        =   450
      _StockProps     =   79
      Caption         =   "SSDBGDate5"
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGDate6 
      Height          =   255
      Left            =   2640
      TabIndex        =   31
      Top             =   480
      Visible         =   0   'False
      Width           =   255
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   0
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   450
      _ExtentY        =   450
      _StockProps     =   79
      Caption         =   "SSDBGDate6"
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGDate7 
      Height          =   255
      Left            =   2280
      TabIndex        =   32
      Top             =   480
      Visible         =   0   'False
      Width           =   255
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   0
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   450
      _ExtentY        =   450
      _StockProps     =   79
      Caption         =   "SSDBGDate7"
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGTicket 
      Height          =   4095
      Left            =   210
      TabIndex        =   33
      Top             =   5280
      Width           =   11445
      _Version        =   196616
      DataMode        =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Col.Count       =   9
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowColumnMoving=   0
      AllowGroupSwapping=   0   'False
      AllowColumnSwapping=   0
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   0
      BackColorOdd    =   16116683
      RowHeight       =   423
      Columns.Count   =   9
      Columns(0).Width=   4736
      Columns(0).Caption=   "TYPE"
      Columns(0).Name =   "TYPE"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1535
      Columns(1).Caption=   "COUNT"
      Columns(1).Name =   "NO."
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   2381
      Columns(2).Caption=   "START TIME"
      Columns(2).Name =   "START_TIME"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   2434
      Columns(3).Caption=   "END TIME"
      Columns(3).Name =   "END_TIME"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2037
      Columns(4).Caption=   "DURATION"
      Columns(4).Name =   "TOTAL HOURS"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1508
      Columns(5).Caption=   "TOTAL"
      Columns(5).Name =   "total_hour"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   3200
      Columns(6).Visible=   0   'False
      Columns(6).Caption=   "Service_Type"
      Columns(6).Name =   "Service_Type"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   2514
      Columns(7).Caption=   "LABOR TYPE"
      Columns(7).Name =   "LABOR TYPE"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   2090
      Columns(8).Caption=   "RATE TYPE"
      Columns(8).Name =   "RATE TYPE"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      _ExtentX        =   20188
      _ExtentY        =   7223
      _StockProps     =   79
      BackColor       =   -2147483648
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
   Begin VB.Label lblSaveStatus 
      ForeColor       =   &H000000FF&
      Height          =   735
      Left            =   6840
      TabIndex        =   52
      Top             =   9720
      Width           =   3255
   End
   Begin VB.Line Line1 
      X1              =   0
      X2              =   11520
      Y1              =   960
      Y2              =   960
   End
   Begin VB.Image Image1 
      BorderStyle     =   1  'Fixed Single
      Height          =   885
      Left            =   120
      Picture         =   "frmSFLaborTicket.frx":050A
      Stretch         =   -1  'True
      Top             =   0
      Width           =   975
   End
   Begin VB.Label Label1 
      BackStyle       =   0  'Transparent
      Caption         =   "Customer:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   330
      TabIndex        =   46
      Top             =   1620
      Width           =   855
   End
   Begin VB.Label Label2 
      BackStyle       =   0  'Transparent
      Caption         =   "Date:"
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
      Left            =   8280
      TabIndex        =   45
      Top             =   1200
      Width           =   615
   End
   Begin VB.Label Label3 
      BackStyle       =   0  'Transparent
      Caption         =   "Ship:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   5880
      TabIndex        =   44
      Top             =   3030
      Width           =   495
   End
   Begin VB.Label Label4 
      BackStyle       =   0  'Transparent
      Caption         =   "Commodity:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   360
      TabIndex        =   43
      Top             =   2100
      Width           =   975
   End
   Begin VB.Label Label5 
      BackStyle       =   0  'Transparent
      Caption         =   "Area Worked:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   5880
      TabIndex        =   42
      Top             =   2520
      Width           =   1215
   End
   Begin VB.Label Label6 
      BackStyle       =   0  'Transparent
      Caption         =   "Supervisor:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   330
      TabIndex        =   41
      Top             =   2580
      Width           =   1095
   End
   Begin VB.Label Label7 
      BackStyle       =   0  'Transparent
      Caption         =   "Job Description:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   330
      TabIndex        =   40
      Top             =   4200
      Width           =   1425
   End
   Begin VB.Label labTitle 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   15.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   735
      Left            =   1440
      TabIndex        =   39
      Top             =   120
      Width           =   9375
   End
   Begin VB.Label Label8 
      Caption         =   "Label8"
      Height          =   135
      Left            =   1440
      TabIndex        =   38
      Top             =   8760
      Width           =   15
   End
   Begin VB.Label Label9 
      BackStyle       =   0  'Transparent
      Caption         =   "Labor Ticket#:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   330
      TabIndex        =   37
      Top             =   1200
      Width           =   1335
   End
   Begin VB.Label txtLabel 
      BackColor       =   &H80000000&
      BackStyle       =   0  'Transparent
      Caption         =   "TOTAL HOUR:"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   285
      Left            =   6900
      TabIndex        =   36
      Top             =   9360
      Width           =   3225
   End
   Begin VB.Label totalHourLabel 
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   285
      Left            =   8400
      TabIndex        =   35
      Top             =   9360
      Width           =   1575
   End
   Begin VB.Label Label10 
      BackStyle       =   0  'Transparent
      Caption         =   "Service Code:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   315
      Left            =   330
      TabIndex        =   34
      Top             =   3030
      Width           =   1365
   End
End
Attribute VB_Name = "frmSFLaborTicket"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim i As Integer
Dim iPos As Integer
Dim sqlStmt As String
Dim ticket_date As String
Dim rsCustomer As Object
Dim rsVessel As Object
Dim rsCommodity As Object
Dim rsService As Object
Dim rsLocation As Object
Dim rsDETAIL As Object
Dim rs As Object
Dim isDisplay As Boolean
Dim recChanged As Boolean
Dim SuperInitials As String

Private Sub cmdAll_Click()
    ' Added Adam Walter, May 2008.
    ' due to an increased number of SUPV's either
    '   A) forgetting to hit Retrieve for a new ticket, or
    '   B) forgetting to hit "save" before "print"
    ' I am adding a new, VERY PROMINENTLY PLACED button
    ' that does all the major functions in one.
    

    
    recChanged = False
    Call cmdSave_Click
    Call cmdPrint_Click
    
    ' form currently has no concept of a "reset", so here it is.
    SSDBGTicket.RemoveAll
    txtNo = ""
    txtCust = ""
    txtCustId = ""
    txtShip = ""
    txtShipId = ""
    txtSup = ""
    txtService = ""
    txtServiceGroup = ""
    txtComm = ""
    txtCommId = ""
    txtArea = ""
End Sub

Private Sub cmdCommList_Click()
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Commodity List"
    frmPV.lstPV.Clear
    
    If optComm(1).Value = True Then
        sqlStmt = "SELECT DISTINCT COMMODITY_CODE, COMMODITY_NAME FROM COMMODITY ORDER BY COMMODITY_CODE"
    Else
        sqlStmt = " SELECT DISTINCT C.COMMODITY_CODE, C.COMMODITY_NAME FROM HOURLY_DETAIL D, COMMODITY C " & _
                  " WHERE (USER_ID = '" & UserID & "' OR SPECIAL_CODE = '" & SuperInitials & "') AND D.HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') AND " & _
                  " C.COMMODITY_CODE = D.COMMODITY_CODE ORDER BY C.COMMODITY_CODE"
    End If
    
    Set rsCommodity = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And rsCommodity.RecordCount > 0 Then
        While Not rsCommodity.EOF
            frmPV.lstPV.AddItem rsCommodity.Fields("commodity_code").Value & " : " & rsCommodity.Fields("commodity_name").Value
            rsCommodity.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCommId.Text = Left$(gsPVItem, iPos - 1)
            txtComm.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    cmdCommList.SetFocus
End Sub

Private Sub cmdCustList_Click()
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Customer List"
    frmPV.lstPV.Clear
    
    If optCust(1).Value = True Then
        sqlStmt = "SELECT DISTINCT C.CUSTOMER_ID, C.CUSTOMER_NAME FROM HOURLY_DETAIL D, CUSTOMER C " & _
                 " WHERE C.CUSTOMER_ID = D.CUSTOMER_ID ORDER BY C.CUSTOMER_ID"
    Else
    'HD9281
    'Not exactly part of the HD, but it appears that the reason the SUPV weren't aware that they already COULD change the customer
    ' is because after saving it, the now-saved customer wouldn't show up on the dropdown list to select.  editing SQL.
'        sqlStmt = "SELECT DISTINCT C.CUSTOMER_ID, C.CUSTOMER_NAME FROM HOURLY_DETAIL D, CUSTOMER C " & _
'                 " WHERE (USER_ID = '" & UserID & "' OR SPECIAL_CODE = '" & SuperInitials & "') AND D.HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') AND " & _
'                 " C.CUSTOMER_ID = D.CUSTOMER_ID ORDER BY C.CUSTOMER_ID"
        sqlStmt = " SELECT DISTINCT CUSTOMER_ID, CUSTOMER_NAME FROM " & _
                        " (SELECT C.CUSTOMER_ID, C.CUSTOMER_NAME " & _
                            " FROM HOURLY_DETAIL D, CUSTOMER C " & _
                            " WHERE (USER_ID = '" & UserID & "' OR SPECIAL_CODE = '" & SuperInitials & "') " & _
                            " AND D.HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') " & _
                            " AND C.CUSTOMER_ID = D.CUSTOMER_ID " & _
                        " UNION " & _
                        " SELECT C.CUSTOMER_ID, C.CUSTOMER_NAME " & _
                            " FROM LABOR_TICKET_HEADER L, CUSTOMER_PROFILE C " & _
                            " WHERE L.USER_ID = '" & UserID & "' " & _
                            " AND L.SERVICE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') " & _
                            " AND L.CUSTOMER_ID = C.CUSTOMER_ID" & _
                        " ) " & _
                        " ORDER BY CUSTOMER_ID "
    End If
    
    Set rsCustomer = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If rsCustomer.RecordCount > 0 Then
        While Not rsCustomer.EOF
            frmPV.lstPV.AddItem rsCustomer.Fields("customer_id").Value & " : " & rsCustomer.Fields("customer_name").Value
            rsCustomer.MoveNext
        Wend
    End If
    
       
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCustId.Text = Left$(gsPVItem, iPos - 1)
            txtCust.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    cmdCustList.SetFocus
End Sub

Private Sub cmdDelete_Click()
    Dim oldRows As Integer
    
    If SSDBGTicket.rows <> 0 Then
        oldRows = SSDBGTicket.rows
        SSDBGTicket.DeleteSelected
        
        If oldRows <> SSDBGTicket.rows Then
            If txtNo.Text <> "" Then
                'If fillChangeLTReason = False Then
                'Call fillLTChangeReason
                Call initValues
                    'Exit Sub
                'Else
                    SSDBGTicket.Refresh
                    recChanged = True
                'End If
            End If
        Else
            Exit Sub
        End If
        
        If SSDBGTicket.rows = 0 And Trim(txtNo.Text) <> "" Then
            OraSession.BeginTrans
            On Error GoTo ErrorHandler
            
            sqlStmt = "DELETE FROM LABOR_TICKET WHERE TICKET_NUM = " & Trim(txtNo.Text)
            OraDatabase.ExecuteSQL (sqlStmt)
            sqlStmt = "DELETE FROM LABOR_TICKET_HEADER WHERE TICKET_NUM = " & Trim(txtNo.Text)
            OraDatabase.ExecuteSQL (sqlStmt)
            
            If OraDatabase.LastServerErr = 0 Then
                OraSession.CommitTrans
            Else
                OraSession.Rollback
            End If
            
            Call display(DTPDate.Value, txtCustId.Text, txtServiceGroup.Text)
        ElseIf Trim(txtNo.Text) <> "" Then
            Call cmdSave_Click
        End If
    End If
    
    Call setTotalHour
    SSDBGTicket.MoveFirst
    If Trim(SSDBGTicket.Columns(0).Value) = vbNullString Then
        SSDBGTicket.Columns(0).Value = SSDBGTicket.Columns(6).Value
    End If
    Exit Sub
    
ErrorHandler:
    OraSession.Rollback
End Sub
Private Sub fillLTChangeReason()
    Load frmSFLTReason
    frmSFLTReason.Left = (Screen.Width - frmSFLTReason.Width) / 2
    frmSFLTReason.Caption = "Please Specify Your Reason"
    frmSFLTReason.txtLabel.Caption = " It is detected that you want to make some changes to this labor ticket." _
                                & " You can specify why you want to change it in the following text box."
    
    frmSFLTReason.Show vbModal
End Sub

Private Function fillChangeLTReason() As Boolean
    Load frmSFLTReason
    frmSFLTReason.Left = (Screen.Width - frmSFLTReason.Width) / 2
    frmSFLTReason.Caption = "Please Specify Your Reason"
    frmSFLTReason.txtLabel.Caption = " It is detected that you want to make some changes to this labor ticket." _
                                & " You can specify why you want to change it in the following text box."
    '                            & " If you decide not to save the changes, please click on Cancel button."
    'frmSFLTReason.reasonTxt.Text = ""
    
    frmSFLTReason.Show vbModal
    
    If Trim(gsReason) <> "" Then
        fillChangeLTReason = True
    Else
        fillChangeLTReason = False
    End If

End Function

Private Sub cmdExit_Click()
    Unload frmSFLaborTicket
    'frmSFHourlyDet.Show
    'frmSFHourlyDetail.MousePointer = vbHourglass
    frmSFHourlyDetail.Show
End Sub

Private Sub cmdLocationList_Click()
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Area Worked List"
    frmPV.lstPV.Clear
    
    'HD9281
    'Not exactly part of the HD, but it appears that the reason the SUPV weren't aware that they already COULD change the area
    ' is because after saving it, the now-saved customer wouldn't show up on the dropdown list to select.  editing SQL.
        sqlStmt = " SELECT DISTINCT LOCATION_ID FROM " & _
                        " (SELECT LOCATION_ID " & _
                            " FROM HOURLY_DETAIL " & _
                            " WHERE (USER_ID = '" & UserID & "' OR SPECIAL_CODE = '" & SuperInitials & "') " & _
                            " AND HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') " & _
                        " UNION " & _
                        " SELECT LOCATION_ID " & _
                            " FROM LABOR_TICKET_HEADER" & _
                            " WHERE USER_ID = '" & UserID & "' " & _
                            " AND SERVICE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') " & _
                        " ) " & _
                        " ORDER BY LOCATION_ID "
    
'    sqlStmt = " SELECT DISTINCT LOCATION_ID FROM HOURLY_DETAIL WHERE " & _
'              " (USER_ID = '" & UserID & "' OR SPECIAL_CODE = '" & SuperInitials & "') AND HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') " & _
'              " ORDER BY LOCATION_ID "
    Set rsLocation = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And rsLocation.RecordCount > 0 Then
        While Not rsLocation.EOF
            frmPV.lstPV.AddItem rsLocation.Fields("location_id").Value
            rsLocation.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        txtArea.Text = gsPVItem
    End If
    cmdLocationList.SetFocus
End Sub

Private Sub cmdPrint_Click()
    Dim vessel_name
    
    '5/3/2007 HD2759 Rudy: had a production issue
    If Len(Trim(txtShipId.Text)) = 0 Then
      MsgBox "Need Vessel info", vbCritical, "Insufficient data"
      Exit Sub
    End If
    If Len(Trim(txtNo.Text)) = 0 Then
      MsgBox "Need Ticket info", vbCritical, "Insufficient data"
      Exit Sub
    End If
    
    vessel_name = getVesselName(txtShipId.Text)
    
    sqlStmt = " SELECT * FROM LABOR_TICKET, LABOR_TICKET_HEADER, COMMODITY, CUSTOMER  " & _
             " WHERE LABOR_TICKET_HEADER.SERVICE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') AND " & _
             " LABOR_TICKET_HEADER.USER_ID = '" & UserID & "' AND LABOR_TICKET_HEADER.CUSTOMER_ID = " & txtCustId.Text & _
             " AND LABOR_TICKET_HEADER.TICKET_NUM = " & txtNo.Text & _
             " AND LABOR_TICKET.TICKET_NUM = LABOR_TICKET_HEADER.TICKET_NUM AND COMMODITY.COMMODITY_CODE = " & _
             " LABOR_TICKET_HEADER.COMMODITY_CODE AND " & _
             " CUSTOMER.CUSTOMER_ID = LABOR_TICKET_HEADER.CUSTOMER_ID " & _
             " ORDER BY LABOR_TICKET.EMP_TYPE, LABOR_TICKET.START_TIME"
             
             'ORDER BY SERVICE_TYPE.ID, LABOR_TICKET.START_TIME
            'SERVICE_TYPE.SERVICE_TYPE=LABOR_TICKET.EMP_TYPE AND
            
    crw1.Connect = "DSN = LCS;UID = LABOR;PWD = LABOR"
    crw1.ReportFileName = App.Path + "\Laborticket.rpt"
    crw1.Formulas(0) = "service = '" + txtService.Text + "'"
    crw1.Formulas(1) = "vessel_name = '" + Replace(vessel_name, "'", "''") + "'"
    crw1.DiscardSavedData = True
    crw1.SQLQuery = sqlStmt
    crw1.Action = 1
End Sub

Private Sub cmdRetrieve_Click()

    If (Len(Me.txtCust.Text) = 0) Then
        MsgBox "Please provide Customer information"
        Me.txtCust.SetFocus
        Exit Sub
    End If
    
    If (Len(Me.txtShip.Text) = 0) Then
        MsgBox "Please provide Ship information"
        Me.txtShip.SetFocus
        Exit Sub
    End If
    
    If (Len(Me.txtCommId.Text) = 0) Then
        MsgBox "Please provide Commodity information"
        Exit Sub
    End If
    
    If (Len(Me.txtComm.Text) = 0) Then
        MsgBox "Please provide Commodity information"
        Me.txtComm.SetFocus
        Exit Sub
    End If
    
    If (Len(Me.txtArea.Text) = 0) Then
        MsgBox "Please provide Area Worked information"
        Me.txtArea.SetFocus
        Exit Sub
    End If
    
    If (Len(Me.txtService.Text) = 0) Then
        MsgBox "Please provide Service Code information"
        Exit Sub
    End If

    Call display(DTPDate.Value, CLng(txtCustId.Text), CLng(txtServiceGroup.Text))

End Sub

Private Sub cmdServiceList_Click()
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Service List"
    frmPV.lstPV.Clear
    
    If txtCustId.Text = "" Then
        Exit Sub
    End If
    
'    sqlStmt = " SELECT DISTINCT SUBSTR(D.SERVICE_CODE, 1, 3) SERVICE_GROUP, SUBSTR(SERVICE_NAME, 6, INSTR(SERVICE_NAME, '/')-6) SERVICE " & _
'              " FROM HOURLY_DETAIL D, SERVICE S WHERE USER_ID = '" & UserID & "' AND D.CUSTOMER_ID = " & txtCustId.Text & " AND " & _
'              " D.HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') AND " & _
'              " S.SERVICE_CODE=D.SERVICE_CODE AND S.STATUS = 'N' ORDER BY SERVICE_GROUP"
    
    'Get service group from SERVICE table  -- LFW, 8/9/05
'    sqlStmt = " SELECT DISTINCT G.SERVICE_GROUP_ID, G.SERVICE_GROUP_NAME FROM HOURLY_DETAIL D, SERVICE S, SERVICE_GROUP G" & _
'              " WHERE (USER_ID = '" & UserID & "' OR SPECIAL_CODE = '" & SuperInitials & "') AND D.CUSTOMER_ID = " & txtCustId.Text & _
'              " AND D.HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') AND S.SERVICE_CODE = D.SERVICE_CODE" & _
'              " AND S.STATUS = 'N' and S.SERVICE_GROUP_ID = G.SERVICE_GROUP_ID ORDER BY G.SERVICE_GROUP_ID"
    
    'HD9281
    'Not exactly part of the HD, but it appears that the reason the SUPV weren't aware that they already COULD change the customer/area
    ' is because after saving it, the now-saved customer/area wouldn't show up on the dropdown list to select.  editing SQL.
        sqlStmt = " SELECT DISTINCT SERVICE_GROUP_ID, SERVICE_GROUP_NAME FROM " & _
                        " (SELECT G.SERVICE_GROUP_ID, G.SERVICE_GROUP_NAME " & _
                            " FROM HOURLY_DETAIL D, SERVICE S, SERVICE_GROUP G " & _
                            " WHERE (USER_ID = '" & UserID & "' OR SPECIAL_CODE = '" & SuperInitials & "') " & _
                            " AND D.CUSTOMER_ID = " & txtCustId.Text & _
                            " AND D.HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') " & _
                            " AND S.SERVICE_CODE = D.SERVICE_CODE " & _
                            " AND S.STATUS = 'N' " & _
                            " AND S.SERVICE_GROUP_ID = G.SERVICE_GROUP_ID " & _
                        " UNION " & _
                        " SELECT G.SERVICE_GROUP_ID, G.SERVICE_GROUP_NAME " & _
                            " FROM LABOR_TICKET_HEADER L, SERVICE S, SERVICE_GROUP G" & _
                            " WHERE L.USER_ID = '" & UserID & "' " & _
                            " AND L.SERVICE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') " & _
                            " AND L.CUSTOMER_ID = " & txtCustId.Text & _
                            " AND G.SERVICE_GROUP_ID = L.SERVICE_GROUP " & _
                            " AND S.STATUS = 'N' " & _
                            " AND S.SERVICE_GROUP_ID = G.SERVICE_GROUP_ID " & _
                        " ) " & _
                        " ORDER BY SERVICE_GROUP_ID "
    
    Set rsService = OraDatabase.CreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And rsService.RecordCount > 0 Then
        While Not rsService.EOF
            frmPV.lstPV.AddItem rsService.Fields("SERVICE_GROUP_ID").Value & " : " & rsService.Fields("SERVICE_GROUP_NAME").Value
            rsService.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtServiceGroup.Text = Left$(gsPVItem, iPos - 1)
            txtService.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    cmdCustList.SetFocus
End Sub

Private Sub updateLaborTicketHeader()
    Dim iUpdRecCnt As Integer
    
    sqlStmt = " UPDATE LABOR_TICKET_HEADER SET CHANGE_FLAG='Y'," _
            & " CHANGE_REASON=CHANGE_REASON + '" & gsReason & "'" _
            & " WHERE TICKET_NUM = " & Trim(txtNo.Text)
    iUpdRecCnt = OraDatabase.ExecuteSQL(sqlStmt)
    If iUpdRecCnt = 0 Then
        OraSession.Rollback
        Exit Sub
    End If
    
End Sub

Private Sub cmdSave_Click()
    Dim ticket_num As Double
    Dim isNew As Boolean
    Dim changeReason As String, ltype As String
    
    ' variable declared for Labor_Ticket in sert statement -LFW 11/18/02
    Dim row_num As Integer
    Dim qty As Double
    Dim startTime As Date
    Dim endTime As Date
    Dim hours As Double
    Dim labor_type As String
    Dim rate_type As String
    
    changeReason = ""
    
    If validateTicket() = False Then
        Exit Sub
    End If
    
    If recChanged = True And Trim(gsReason) = "" Then
    '    If fillChangeLTReason = False Then
    '       Call fillLTChangeReason
        Call initValues
    '       Exit Sub
    '    End If
    End If
    
    OraSession.BeginTrans
    On Error GoTo ErrorHandler
    
    If SSDBGTicket.rows > 0 Then
        If txtNo.Text = "" Then
            isNew = True
            sqlStmt = "Select Max(TICKET_NUM)MAX_NUM FROM LABOR_TICKET_HEADER "
            Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not rs.EOF And Not IsNull(rs.Fields("MAX_NUM")) Then
                ticket_num = rs.Fields("MAX_NUM").Value
                If ticket_num < 1000 Then
                    ticket_num = 1000
                Else
                    ticket_num = ticket_num + 1
                End If
            Else
                ticket_num = 1001
            End If
        Else
            isNew = False
            ticket_num = txtNo.Text
        End If
        
        If isNew Then
            sqlStmt = "SELECT * FROM LABOR_TICKET_HEADER"
            Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        
            rs.AddNew
            rs.Fields("ticket_num").Value = ticket_num
        Else
            sqlStmt = "SELECT * FROM LABOR_TICKET_HEADER WHERE TICKET_NUM = " + Str(ticket_num)
            Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        
            rs.Edit
            changeReason = GetValue(rs.Fields("change_reason").Value, "")
            
        End If
        
        rs.Fields("service_date").Value = DTPDate.Value
        rs.Fields("customer_id").Value = txtCustId.Text
        rs.Fields("vessel_id").Value = txtShipId.Text
        rs.Fields("commodity_code").Value = txtCommId.Text
        rs.Fields("user_id").Value = UserID
        rs.Fields("location_id").Value = txtArea.Text
        rs.Fields("service_group").Value = txtServiceGroup.Text
        rs.Fields("job_description").Value = txtJobDesc.Text
        
        If recChanged = True And Trim(gsReason) <> "" Then
            rs.Fields("change_flag").Value = "Y"
            rs.Fields("change_reason").Value = changeReason + gsReason
            Call initValues
        End If
        
        rs.Update
        
        If Not isNew Then
            sqlStmt = "DELETE FROM LABOR_TICKET WHERE TICKET_NUM = " + Str(ticket_num)
            OraDatabase.ExecuteSQL (sqlStmt)
        End If
                
        'Because of unknown reason for database access failure of the following
        'two statements, use in sert statement instead.   -LFW, 11/18/2002

'       sqlStmt = "SELECT * FROM LABOR_TICKET "
'       Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        
        SSDBGTicket.MoveFirst
        For i = 0 To SSDBGTicket.rows - 1
'           rs.AddNew
'           rs.Fields("ticket_num").Value = ticket_num
'           rs.Fields("row_num").Value = i + 1
            
            ' Switch 0 with 6, LFW, 11/18/02
            If Trim(SSDBGTicket.Columns(0).Value) = vbNullString Then
                ltype = SSDBGTicket.Columns(6).Value
            Else
                ltype = SSDBGTicket.Columns(0).Value
            End If
            
'           rs.Fields("emp_type").Value = ltype
'           rs.Fields("qty").Value = SSDBGTicket.Columns(1).Value
'           rs.Fields("start_time").Value = Format(DTPDate.Value, "MM/DD/yyyy") & " " & Format(SSDBGTicket.Columns(2).Value, "hh:nnAM/PM")
'           rs.Fields("end_time").Value = Format(DTPDate.Value, "MM/DD/yyyy") & " " & Format(SSDBGTicket.Columns(3).Value, "hh:nnAM/PM")
'           rs.Fields("hours").Value = SSDBGTicket.Columns(4).Value
'           rs.Fields("labor_type").Value = SSDBGTicket.Columns(7).Value
'           rs.Fields("rate_type").Value = SSDBGTicket.Columns(8).Value
'           rs.Update
            
            row_num = i + 1
            qty = SSDBGTicket.Columns(1).Value
            startTime = Format(DTPDate.Value, "MM/DD/yyyy") & " " & Format(SSDBGTicket.Columns(2).Value, "hh:nnAM/PM")
            endTime = Format(DTPDate.Value, "MM/DD/yyyy") & " " & Format(SSDBGTicket.Columns(3).Value, "hh:nnAM/PM")
            hours = SSDBGTicket.Columns(4).Value
            labor_type = SSDBGTicket.Columns(7).Value
            rate_type = SSDBGTicket.Columns(8).Value
            
            sqlStmt = "insert into LABOR_TICKET values (" & Str(ticket_num) & _
                      ", " & Str(row_num) & ", '" & ltype + "', " + Str(qty) & _
                      ", to_date('" & Str(startTime) & "', 'MM/DD/YYYY HH:MI:SS AM'), to_date('" & _
                      Str(endTime) & "', 'MM/DD/YYYY HH:MI:SS AM'), " & Str(hours) & _
                      ", '" & labor_type & "', '" & rate_type & "')"
                                 
            OraDatabase.ExecuteSQL (sqlStmt)

            SSDBGTicket.MoveNext
        Next
        
        txtNo.Text = ticket_num
    End If
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
    Else
        OraSession.Rollback
    End If
    
    cmdPrint.Enabled = True

    Exit Sub
    
ErrorHandler:
    OraSession.Rollback
    
End Sub

Private Sub cmdShipList_Click()
    Dim vlist() As String
    Dim vArray() As String
    Dim size As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Ship List"
    frmPV.lstPV.Clear
    
    'sqlStmt = " SELECT DISTINCT V.VESSEL_ID, V.VESSEL_NAME FROM HOURLY_DETAIL D, VESSEL V " & _
    '          " WHERE USER_ID = '" & UserID & "' AND D.HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') AND " & _
    '          " V.VESSEL_ID = D.VESSEL_ID ORDER BY V.VESSEL_ID"
    sqlStmt = " SELECT DISTINCT VESSEL_ID FROM HOURLY_DETAIL WHERE (USER_ID = '" & UserID & "' OR SPECIAL_CODE = '" & SuperInitials & "') AND HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') ORDER BY VESSEL_ID"
    Set rsVessel = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And rsVessel.RecordCount > 0 Then
        ReDim vlist(1 To rsVessel.RecordCount)
        size = 1
        While Not rsVessel.EOF
            vlist(size) = rsVessel.Fields("VESSEL_ID").Value
            size = size + 1
            rsVessel.MoveNext
        Wend
    
        vArray = getVesselArray(vlist)
        For size = LBound(vArray, 1) To UBound(vArray, 1)
            If vArray(size, 0) <> "" Then
                frmPV.lstPV.AddItem vArray(size, 0) & " : " & vArray(size, 1)
            End If
        Next
    End If
    Set rsVessel = Nothing
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtShipId.Text = Left$(gsPVItem, iPos - 1)
            txtShip.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    cmdShipList.SetFocus
End Sub



Private Sub DTPDate_Change()
    If isDisplay Then
        Exit Sub
    End If
    ''Call display(DTPDate.Value, 0, 0)
End Sub

Private Sub initValues()
    recChanged = False
    
    sqlStmt = "SELECT UPPER(INITIALS) THE_IN FROM SUPERVISOR_INITIALS WHERE USER_ID = '" & UserID & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If Not IsNull(dsSHORT_TERM_DATA.Fields("THE_IN").Value) Then
        SuperInitials = dsSHORT_TERM_DATA.Fields("THE_IN").Value
    Else
        SuperInitials = ""
    End If
    gsReason = ""
End Sub

Private Sub dwnCount_InitColumnProps()
    Dim i As Integer, j As Integer, arrMin(2) As String, tz As String
    dwnCount.RemoveAll
    
    For i = 1 To 100
        dwnCount.AddItem Trim(CStr(i))
    Next
    dwnCount.Columns(0).Width = 1000
End Sub

Private Sub dwnLaborType_InitColumnProps()
    Dim sqlStmt As String
    Dim rsLabor As Object
    
    sqlStmt = "Select LABOR_TYPE from LABOR_CATEGORY"
    Set rsLabor = OraDatabaseBNI.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabaseBNI.LastServerErr = 0 And rsLabor.RecordCount > 0 Then
        While Not rsLabor.EOF
            dwnLaborType.AddItem Trim(rsLabor.Fields("LABOR_TYPE").Value)
            rsLabor.MoveNext
        Wend
    End If
    
    Set rsLabor = Nothing
    
    dwnLaborType.Columns(0).Width = 1611
End Sub

Private Sub dwnRateType_InitColumnProps()
    Dim sqlStmt As String
    Dim rsRate As Object
    
    sqlStmt = "Select RATE_TYPE from LABOR_RATE_TYPE"
    Set rsRate = OraDatabaseBNI.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabaseBNI.LastServerErr = 0 And rsRate.RecordCount > 0 Then
        While Not rsRate.EOF
            dwnRateType.AddItem Trim(rsRate.Fields("RATE_TYPE").Value)
            rsRate.MoveNext
        Wend
    End If
    
    Set rsRate = Nothing
    
    dwnRateType.Columns(0).Width = 1611
End Sub

Private Sub dwnType_InitColumnProps()
    dwnType.RemoveAll
    
    If txtCust.Text = "313" Or txtCust.Text = "453" Then
        dwnType.AddItem "CHECKERS CASUAL"
        dwnType.AddItem "CHECKERS REGULAR"
        dwnType.AddItem "LABORERS CASUAL"
        dwnType.AddItem "LABORERS REGULAR"
        dwnType.AddItem "LIFT TRUCKS CASUAL"
        dwnType.AddItem "LIFT TRUCKS REGULAR"
    Else
        dwnType.AddItem "CHECKERS"
        dwnType.AddItem "LABORERS"
        dwnType.AddItem "LIFT TRUCKS"
        dwnType.AddItem "CHECKERS CASUAL"
        dwnType.AddItem "CHECKERS REGULAR"
        dwnType.AddItem "LABORERS CASUAL"
        dwnType.AddItem "LABORERS REGULAR"
        dwnType.AddItem "LIFT TRUCKS CASUAL"
        dwnType.AddItem "LIFT TRUCKS REGULAR"
    End If
    dwnType.AddItem "OFFICE CLERK"
    dwnType.AddItem "SUPERVISORS"
    dwnType.AddItem "EXTRA OPERATOR"
    
    dwnType.Columns(0).Width = 2611
End Sub

Private Sub Form_Load()
    labTitle.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
    
    DTPDate.Value = Format(Date, "MM/DD/YYYY")
    
    ticket_date = Format(DTPDate.Value, "MM/DD/YYYY")
    
    Call initValues

    ''Call display(ticket_date, 0, 0)
        
End Sub


'****************************************
'To Fill Start and End Time Drop Down
'****************************************
Private Sub dwnTime_InitColumnProps()
  Dim i As Integer, j As Integer, arrMin(2) As String, tz As String
  'Duration in 1/2 an Hour
  arrMin(0) = "00"
  arrMin(1) = "30"
  For i = 0 To 23
    For j = 0 To 1
      If i < 10 Then
        dwnTime.AddItem "0" + Trim(Str(i)) + ":" + arrMin(j)
      Else
        dwnTime.AddItem Trim(Str(i)) + ":" + arrMin(j)
      End If
  Next j, i
  'dwnTime.AddItem "24:00"
  dwnTime.Columns(0).Width = 1611
End Sub
'****************************************
'To Set the Format for all Columns on the Grid
'****************************************
Private Sub setupForm()
    isDisplay = False
    
    SSDBGTicket.Columns(0).DropDownHwnd = dwnType.hWnd
    SSDBGTicket.Columns(1).DropDownHwnd = dwnCount.hWnd
    SSDBGTicket.Columns(2).DropDownHwnd = dwnTime.hWnd
    SSDBGTicket.Columns(3).DropDownHwnd = dwnTime.hWnd
    SSDBGTicket.Columns(4).Locked = True
    SSDBGTicket.Columns(5).Locked = True
    SSDBGTicket.Columns(6).DropDownHwnd = dwnType.hWnd      'Added by LFW 11/18/2002
    SSDBGTicket.Columns(7).DropDownHwnd = dwnLaborType.hWnd
    SSDBGTicket.Columns(8).DropDownHwnd = dwnRateType.hWnd
  
    dwnType.ListAutoValidate = True
    dwnTime.ListAutoValidate = True
    dwnLaborType.ListAutoValidate = True
    dwnRateType.ListAutoValidate = True
    
    Dim day As Date
    'Dim goodDay As Boolean
    'day = Format(DTPDate, "mm/dd/yyyy")
    'goodDay = goodWorkDate(day)
    Dim changeable As Boolean
    changeable = laborTicketStatus
    
    If changeable Then
        SSDBGTicket.AllowUpdate = True
        If SSDBGTicket.rows = 0 Then
            cmdSave.Enabled = False
            cmdDelete.Enabled = False
        Else
            cmdSave.Enabled = True
            cmdDelete.Enabled = True
            lblSaveStatus.Caption = ""
        End If
    Else
        SSDBGTicket.AllowUpdate = False
        cmdSave.Enabled = False
        lblSaveStatus.Caption = "Finance has already processed this labor ticket.  Therefore, it cannot be modified"
        cmdDelete.Enabled = False
    End If
    
    If Trim(txtNo.Text) = "" Then
        cmdPrint.Enabled = False
    Else
        cmdPrint.Enabled = True
    End If
    
    Call setTotalHour
    SSDBGTicket.MoveFirst
End Sub

Private Function goodWorkDate(work_date As Date) As Boolean
    Dim latestday As Date
    Dim dayOfWeek As Integer
    
    dayOfWeek = Weekday(Date)
    If dayOfWeek = 4 Then
        If hour(time) <= 7 And Minute(time) <= 59 Then
            'It is before Monday 9:00AM, any date after last Monday is good
            latestday = Date - 9
        Else
            latestday = Date - 2
        End If
    ElseIf dayOfWeek = 2 Or dayOfWeek = 3 Then
        'It is after Monday 9:00AM, any date after this Monday is good
        latestday = Date - (dayOfWeek - 2) - 7
    ElseIf dayOfWeek = 5 Or dayOfWeek = 6 Then
        latestday = Date - (dayOfWeek - 2)
    ElseIf dayOfWeek = 7 Then
        latestday = Date - 6
    End If
    
    If work_date < latestday Then
        goodWorkDate = False
    Else
        goodWorkDate = True
    End If
End Function

Private Sub Form_Unload(Cancel As Integer)
    Call cmdExit_Click
End Sub

Private Sub SSDBGTicket_AfterColUpdate(ByVal ColIndex As Integer)
    Dim dur As Single, Total As Single
    Dim cnt As Integer
    
    If (ColIndex = 0) And dwnType.ListAutoValidate = False Then
        dwnType.ListAutoValidate = True
    ElseIf (ColIndex = 1) And dwnCount.ListAutoValidate = False Then
        dwnCount.ListAutoValidate = True
    ElseIf (ColIndex = 2 Or ColIndex = 3) And dwnTime.ListAutoValidate = False Then
        dwnTime.ListAutoValidate = True
    ElseIf (ColIndex = 2 Or ColIndex = 3) Then
        If Trim(SSDBGTicket.Columns(2).Value) <> vbNullString And Trim(SSDBGTicket.Columns(3).Value) <> vbNullString And Trim(SSDBGTicket.Columns(1).Value) <> vbNullString Then
            dur = Duration(SSDBGTicket.Columns(2).Value, SSDBGTicket.Columns(3).Value)
            cnt = SSDBGTicket.Columns(1).Value
            Total = dur * cnt
            SSDBGTicket.Columns(4).Value = dur
            SSDBGTicket.Columns(5).Value = Total
        End If
    ElseIf (ColIndex = 1) Then
        If Trim(SSDBGTicket.Columns(1).Value) <> vbNullString Then
            If Trim(SSDBGTicket.Columns(2).Value) <> vbNullString And Trim(SSDBGTicket.Columns(3).Value) <> vbNullString Then
                dur = Duration(SSDBGTicket.Columns(2).Value, SSDBGTicket.Columns(3).Value)
                cnt = SSDBGTicket.Columns(1).Value
                Total = dur * cnt
                SSDBGTicket.Columns(5).Value = Total
            End If
        End If
    End If
    
    recChanged = True
    If cmdSave.Enabled = False Then
        cmdSave.Enabled = True
    End If
    If cmdDelete.Enabled = False Then
        cmdSave.Enabled = True
    End If
    
    Call setTotalHour
End Sub

Private Sub retrieve(ticket_date As String, cust_id As Long, service_group As Integer, com_code As String)
    Dim custId As Long
    Dim service_type As Integer
    Dim comm_code As Integer
    Dim service_code As Integer
    Dim Start_Time As Date
    Dim End_Time As Date
    Dim emp_id As String
    Dim bType As Integer
    Dim qty As Integer
    Dim hours As Double
    Dim payLunch As String
    Dim payDinner As String
    Dim sfRowNumber As String
    Dim location As String
    
    Dim Eight_AM As Date
    Dim Five_PM As Date
    Dim Twelve_PM As Date
    Dim One_PM As Date
    Dim Six_PM As Date
    Dim Seven_PM As Date
       
    Eight_AM = Format(ticket_date + " 08:00", "mm/dd/yyyy HH:MM")
    Five_PM = Format(ticket_date + " 17:00", "mm/dd/yyyy HH:MM")
    Twelve_PM = Format(ticket_date + " 12:00", "mm/dd/yyyy HH:MM")
    One_PM = Format(ticket_date + " 13:00", "mm/dd/yyyy HH:MM")
    Six_PM = Format(ticket_date + " 18:00", "mm/dd/yyyy HH:MM")
    Seven_PM = Format(ticket_date + " 19:00", "mm/dd/yyyy HH:MM")
    
' Take off the If statement, so all customers will get different labor ticket for vessels of the same day
' -- LFW, 7/17/2003
'    If (cust_id = 313 Or cust_id = 453 Or cust_id = 1601) And Not (txtShipId.Text = "") Then
'    sqlStmt = "SELECT * FROM HOURLY_DETAIL WHERE USER_ID = '" & UserID & "' AND " & _
'            " CUSTOMER_ID = " & cust_id & " AND SUBSTR(SERVICE_CODE, 1, 3)='" & service_group & "' AND " & _
'            " HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') AND VESSEL_ID = '" & txtShipId.Text & "'"
'    Else
'        sqlStmt = "SELECT * FROM HOURLY_DETAIL WHERE USER_ID = '" & UserID & "' AND " & _
'            " CUSTOMER_ID = " & cust_id & " AND SUBSTR(SERVICE_CODE, 1, 3)='" & service_group & "' AND " & _
'            " HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') "
'    End If

    ' Changed the way of checking service group  -- LFW, 8/8/2005
    sqlStmt = "SELECT H.* FROM HOURLY_DETAIL H, SERVICE S, SERVICE_GROUP G WHERE H.SERVICE_CODE = S.SERVICE_CODE AND " & _
              "S.SERVICE_GROUP_ID = G.SERVICE_GROUP_ID AND (H.SPECIAL_CODE = '" & SuperInitials & "' OR (H.USER_ID = '" & UserID & "' AND H.SPECIAL_CODE IS NULL)) AND CUSTOMER_ID = " & cust_id & _
              " AND HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') AND VESSEL_ID = '" & txtShipId.Text & "'" & _
              " AND H.LOCATION_ID = '" & txtArea.Text & "' AND H.COMMODITY_CODE = '" & txtCommId.Text & "'" & _
              " AND H.COMMODITY_CODE=" & Trim(Me.txtCommId.Text)
    
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)

    If Not rs.EOF Then
        txtNo.Text = ""
        txtCustId.Text = cust_id
        txtCust.Text = fnCustomer(rs.Fields("customer_id").Value)
        txtShipId.Text = rs.Fields("vessel_id").Value
        txtShip.Text = fnVESSEL(rs.Fields("vessel_id").Value)
        txtSup.Text = fnEmp(UserID)
        txtArea.Text = rs.Fields("location_id").Value
        txtCommId.Text = rs.Fields("commodity_code").Value
        txtComm.Text = fnCOMMODITY(rs.Fields("commodity_code").Value)
        txtServiceGroup.Text = service_group
        txtService.Text = fnServiceGroup(service_group)
    Else
        Call cleanForm
        Exit Sub
    End If
    
    'To handle the situation when service code is 0000
    If service_group = 0 Then
        Exit Sub
    End If
    
    SSDBGTicket.RemoveAll
    SSDBGTicket.MoveFirst
    
    SSDBGDate1.RemoveAll
    SSDBGDate1.MoveFirst
    
    SSDBGDate2.RemoveAll
    SSDBGDate2.MoveFirst
    
    SSDBGDate3.RemoveAll
    SSDBGDate3.MoveFirst

    SSDBGDate4.RemoveAll
    SSDBGDate4.MoveFirst
    
    SSDBGDate5.RemoveAll
    SSDBGDate5.MoveFirst
    
    SSDBGDate6.RemoveAll
    SSDBGDate6.MoveFirst
    
    SSDBGDate7.RemoveAll
    SSDBGDate7.MoveFirst
       
' Take off the If statement, so all customers will get different labor ticket for vessels of the same day
' -- LFW, 7/17/2003
'    If (cust_id = 313 Or cust_id = 453 Or cust_id = 1601) And Not (txtShipId.Text = "") Then
'    sqlStmt = " SELECT DISTINCT A.*, SUBSTR(A.SERVICE_CODE, 4) TYPE, SFA.pay_lunch, SFA.pay_dinner " & _
'              " FROM HOURLY_DETAIL A, SF_HOURLY_DETAIL SFA WHERE A.USER_ID = '" & UserID & "' AND " & _
'              " A.CUSTOMER_ID = " & cust_id & " AND A.VESSEL_ID = '" & txtShipId.Text & "'" & _
'              " AND SUBSTR(A.SERVICE_CODE, 1, 3)='" & service_group & "' AND " & _
'              " A.HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') AND A.SF_ROW_NUMBER = SFA.ROW_NUMBER (+) ORDER BY TYPE, A.START_TIME "
'    Else
'        sqlStmt = " SELECT DISTINCT A.*, SUBSTR(A.SERVICE_CODE, 4) TYPE, SFA.pay_lunch, SFA.pay_dinner " & _
'              " FROM HOURLY_DETAIL A, SF_HOURLY_DETAIL SFA WHERE A.USER_ID = '" & UserID & "' AND " & _
'              " A.CUSTOMER_ID = " & cust_id & " AND SUBSTR(A.SERVICE_CODE, 1, 3)='" & service_group & "' AND " & _
'              " A.HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') AND A.SF_ROW_NUMBER = SFA.ROW_NUMBER (+) ORDER BY TYPE, A.START_TIME "
'    End If
        
    ' Changed the way of refering to servcie group       -- LFW, 8/8/2005
    sqlStmt = "SELECT DISTINCT A.*, SUBSTR(A.SERVICE_CODE, 4) TYPE, SFA.pay_lunch, SFA.pay_dinner " & _
              "FROM HOURLY_DETAIL A, SF_HOURLY_DETAIL SFA, SERVICE S, SERVICE_GROUP G " & _
              "WHERE A.SF_ROW_NUMBER = SFA.ROW_NUMBER (+) AND A.SERVICE_CODE = S.SERVICE_CODE AND S.SERVICE_GROUP_ID = G.SERVICE_GROUP_ID AND " & _
              "(A.SPECIAL_CODE = '" & SuperInitials & "' OR (A.USER_ID = '" & UserID & "' AND A.SPECIAL_CODE IS NULL)) AND A.CUSTOMER_ID = " & cust_id & " AND A.VESSEL_ID = " & txtShipId.Text & " AND " & _
              "S.SERVICE_GROUP_ID = " & service_group & " AND A.HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') " & _
              " AND A.LOCATION_ID = '" & txtArea.Text & "' AND A.COMMODITY_CODE=SFA.COMMODITY_CODE AND a.COMMODITY_CODE=" & com_code & _
              "ORDER BY TYPE, A.START_TIME "

    Set rsDETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    Dim timeDiff As Double

    Do While Not rsDETAIL.EOF
        custId = rsDETAIL.Fields("customer_id").Value
        service_type = rsDETAIL.Fields("type").Value
        comm_code = rsDETAIL.Fields("commodity_code").Value
        service_code = rsDETAIL.Fields("service_code").Value
        Start_Time = Format(rsDETAIL.Fields("start_time").Value, "mm/dd/yyyy HH:MM")
        End_Time = Format(rsDETAIL.Fields("end_time").Value, "mm/dd/yyyy HH:MM")
        emp_id = rsDETAIL.Fields("employee_id").Value
        hours = rsDETAIL.Fields("duration").Value
        payLunch = GetValue(rsDETAIL.Fields("pay_lunch").Value, "")
        payDinner = GetValue(rsDETAIL.Fields("pay_dinner").Value, "")
        sfRowNumber = GetValue(rsDETAIL.Fields("sf_row_number").Value, "")
        location = rsDETAIL.Fields("location_id").Value
              
        'Seems like this is trying to assign value to payLunch and payDinner.
        'But it needs some corrections  -LFW, 1/10/03
        timeDiff = Duration(Start_Time, End_Time)

'        If sfRowNumber = "" Then
'           If timeDiff - hours = 1 Then
'                If Start_Time <= Twelve_PM And End_Time >= One_PM Then
'                    payLunch = "N"
'                ElseIf Start_Time >= One_PM Then
'                    payDinner = "N"
'                End If
'            ElseIf timeDiff - hours = 2 Then
'                payLunch = "N"
'                payDinner = "N"
'            End If
'        End If
        
        ' Modified by LFW, 1/10/03
        If sfRowNumber = "" Then
            If timeDiff - hours < 0 Then
                GoTo NextRecord
            ElseIf timeDiff - hours = 0 Then
                If Start_Time <= Twelve_PM And End_Time > Twelve_PM Then
                    payLunch = "Y"
                End If
                If Start_Time <= Six_PM And End_Time > Six_PM Then
                    payDinner = "Y"
                End If
            ElseIf timeDiff - hours <= 1 Then
                If Start_Time <= Twelve_PM And End_Time > Twelve_PM Then
                    payLunch = "N"
                ElseIf Start_Time <= Six_PM And End_Time > Six_PM Then
                    payDinner = "N"
                End If
            ElseIf timeDiff - hours <= 2 Then
                payLunch = "N"
                payDinner = "N"
            End If
        End If

        bType = bill_type(cust_id, service_code, comm_code, location)
        
        If bType = 1 Then
        
'            If timeDiff - hours = 1 And payLunch = "N" Then
'                If Start_Time <= Twelve_PM And End_Time >= One_PM Then
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Twelve_PM)
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, End_Time)
'                End If
'            ElseIf timeDiff - hours = 1 And payDinner = "N" Then
'                If Start_Time <= Six_PM And End_Time >= Seven_PM Then
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Six_PM)
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
'                End If
'            ElseIf timeDiff - hours = 2 Then
'                Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, Six_PM)
'                If Start_Time < Twelve_PM Then
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Twelve_PM)
'                End If
'                If End_Time > Seven_PM Then
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
'                End If
'            Else
'                Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
'            End If
            
            ' Modified by LFW, 1/10/03
            If Start_Time < Twelve_PM Then
                If End_Time <= Twelve_PM Then
                    Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                ElseIf End_Time <= One_PM Then
                    If payLunch = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Twelve_PM)
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                ElseIf End_Time <= Six_PM Then
                    If payLunch = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Twelve_PM)
                        Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, End_Time)
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                ElseIf End_Time <= Seven_PM Then
                    If payLunch = "N" And payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Twelve_PM)
                        Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, Six_PM)
                    ElseIf payLunch = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Twelve_PM)
                        Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, End_Time)
                    ElseIf payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Six_PM)
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                Else
                    If payLunch = "N" And payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Twelve_PM)
                        Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, Six_PM)
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
                    ElseIf payLunch = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Twelve_PM)
                        Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, End_Time)
                    ElseIf payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Six_PM)
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                End If
            ElseIf Start_Time < One_PM Then
                If End_Time <= One_PM Then
                    If Not payLunch = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                ElseIf End_Time <= Six_PM Then
                    If payLunch = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, End_Time)
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                ElseIf End_Time <= Seven_PM Then
                    If payLunch = "N" And payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, Six_PM)
                    ElseIf payLunch = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, End_Time)
                    ElseIf payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Six_PM)
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                Else
                    If payLunch = "N" And payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, Six_PM)
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
                    ElseIf payLunch = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, One_PM, End_Time)
                    ElseIf payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Six_PM)
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                End If
            ElseIf Start_Time < Six_PM Then
                If End_Time <= Six_PM Then
                    Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                ElseIf End_Time <= Seven_PM Then
                    If payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Six_PM)
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                Else
                    If payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Six_PM)
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                End If
            ElseIf Start_Time < Seven_PM Then
                If End_Time <= Seven_PM Then
                    If Not payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                Else
                    If payDinner = "N" Then
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                End If
            Else
                Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
            End If
        
        ElseIf bType = 2 Then
            
            If Start_Time < Eight_AM And End_Time >= Eight_AM Then
                Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Eight_AM)
            ElseIf Start_Time < Eight_AM And End_Time < Eight_AM Then
                Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
            End If
            
'            If End_Time > Five_PM And Start_Time <= Five_PM Then
'                If End_Time >= Seven_PM And payDinner = "N" Then
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Five_PM, Six_PM)
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
'                Else
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Five_PM, End_Time)
'                End If
'            ElseIf End_Time > Five_PM And Start_Time > Five_PM Then
'                If Start_Time <= Six_PM And End_Time >= Seven_PM And payDinner = "N" Then
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Six_PM)
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
'                ElseIf Six_PM <= Six_PM And Start_Time <= Seven_PM And payDinner = "N" Then
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
'                Else
'                    Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
'                End If
'            End If
'        End If
            
            ' Modified by LFW, 1/10/03
            If End_Time > Five_PM Then
                If Start_Time <= Five_PM Then
                    If payDinner = "N" Then
                        If End_Time > Seven_PM Then
                            Call add_to_SSDBGDate(service_type, custId, emp_id, Five_PM, Six_PM)
                            Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
                        ElseIf End_Time < Six_PM Then
                            Call add_to_SSDBGDate(service_type, custId, emp_id, Five_PM, End_Time)
                        Else
                            Call add_to_SSDBGDate(service_type, custId, emp_id, Five_PM, Six_PM)
                        End If
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Five_PM, End_Time)
                    End If
                Else            'Start_Time > Five_PM
                    If payDinner = "N" Then
                        If Start_Time < Six_PM Then
                            If End_Time > Seven_PM Then
                                Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Six_PM)
                                Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
                            ElseIf End_Time >= Six_PM Then
                                Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, Six_PM)
                            Else
                                Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                            End If
                        ElseIf Start_Time <= Seven_PM Then
                            If End_Time > Seven_PM Then
                                Call add_to_SSDBGDate(service_type, custId, emp_id, Seven_PM, End_Time)
                            End If
                        Else
                            If End_Time > Seven_PM Then
                                Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                            End If
                        End If
                    Else
                        Call add_to_SSDBGDate(service_type, custId, emp_id, Start_Time, End_Time)
                    End If
                End If
            End If
            
        End If

NextRecord:
        rsDETAIL.MoveNext
    Loop
    
    Call show_date(cust_id, service_group)
    
End Sub

Private Function pay_type(cust_id As Long, service_code As Integer) As String
    Select Case service_code
        Case 611
           pay_type = "DF"
        Case 613
            Select Case cust_id
                Case 1601
                    pay_type = "DF"
                Case Else
                    pay_type = "ST"
            End Select
        Case 622
            Select Case cust_id
                Case 313, 453, 414, 249, 1638
                    pay_type = "ST"
                Case Else
                    pay_type = "DF"
            End Select
        Case 631
            Select Case cust_id
                Case 313, 453, 249, 1638
                    pay_type = "ST"
                Case Else
                    pay_type = "DF"
            End Select
        Case 671, 672, 655, 656, 657, 654
            pay_type = "ST"
        Case Else
            Select Case cust_id
                Case 313, 453, 1601, 284, 414, 2210, 249, 1638
                    pay_type = "ST"
                Case Else
                    pay_type = "DF"
            End Select
    End Select
End Function

Private Function rate_type(Start_Time As Date, payType As String)
    Dim eightAM As Date
    Dim fivePM As Date
    Dim sixAM As Date, sevenAM As Date, twelvePM As Date, onePM As Date
    Dim sixPM As Date, sevenPM As Date, twelveAM As Date, oneAM As Date
    
    twelveAM = "00:00"
    oneAM = "01:00"
    sixAM = "06:00"
    sevenAM = "07:00"
    eightAM = "08:00"
    twelvePM = "12:00"
    onePM = "13:00"
    fivePM = "17:00"
    sixPM = "18:00"
    sevenPM = "19:00"
    
    If (twelveAM <= Start_Time And Start_Time < oneAM) Or (sixAM <= Start_Time And Start_Time < sevenAM) Or (twelvePM <= Start_Time And Start_Time < onePM) Or (sixPM <= Start_Time And Start_Time < sevenPM) Then
        If payType = "DF" Then
            rate_type = "MH"
        Else
            rate_type = "DT"
        End If
    ElseIf (Start_Time < eightAM) Or (Start_Time >= fivePM) Then
        If payType = "DF" Then
            rate_type = "DF"
        Else
            rate_type = "OT"
        End If
    Else
        If payType = "DF" Then
            rate_type = "DF"
        Else
            rate_type = "ST"
        End If
    End If
End Function

Private Function bill_type(cust_id As Long, service_code As Integer, comm As Integer, location As String) As Integer
    Dim bill_date As Date
    Dim service As String
    
    ' always bill special days
    bill_date = Format(DTPDate.Value, "mm/dd/yyyy")
    If isDayHoliday(bill_date) = True Or Weekday(bill_date) = 1 Or Weekday(bill_date) = 7 Then
        bill_type = 1
        Exit Function
    End If
    
    ' a table of specific exceptions that dont follow the general "service-customer" rule.
    sqlStmt = "SELECT BILL_TYPE FROM SPECIAL_LABOR_TICKET_RULES WHERE (CUSTOMER_ID = '" & cust_id & "' OR CUSTOMER_ID IS NULL) AND " _
            & "(SERVICE_CODE = '" & service_code & "' OR SERVICE_CODE IS NULL) AND " _
            & "(COMMODITY_CODE = '" & comm & "' OR COMMODITY_CODE IS NULL) AND " _
            & "(LOCATION_ID = '" & location & "' OR LOCATION_ID IS NULL)"
    Set dsSHORT_TERM_DATA = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If dsSHORT_TERM_DATA.RecordCount > 0 Then
        bill_type = dsSHORT_TERM_DATA.Fields("BILL_TYPE").Value
        Exit Function
    End If
    
    
    service = Left(CStr(service_code), 3)
    Select Case service
        Case "611"
            Select Case cust_id
                Case 313
                    bill_type = 0   '0 - Not bill to the customer
                Case 453, 1601, 414, 249, 1638
                    bill_type = 1   '1 - Bill anytime to the customer
                Case Else
                    bill_type = 2   '2 - Bill only overtime ( Before 8:00 AM and After 5:00 PM) -LFW, 1/9/02
            End Select
        Case "613"
            Select Case cust_id
                Case 284, 2210
                    bill_type = 0
                Case Else
                    bill_type = 1
            End Select
        Case "622"
            Select Case cust_id
                'Case 313, 453, 1601, 284, 414, 2210, 1638
                Case 146, 175, 249, 284, 313, 399, 414, 453, 1237, 1598, 1601, 1608, 1630, 1638, 1804, 1913, 2210, 2305
                    bill_type = 1
                Case Else
                    bill_type = 2
            End Select
        Case "631"
            Select Case cust_id
                Case 1601, 284, 414, 2210
                    bill_type = 0
                Case 313, 453, 249, 1638
                    bill_type = 1
                Case Else
                    bill_type = 2
            End Select
        Case "661"              'Case added by LFW, 1/10/03, Per Malcolm Cutler
            Select Case cust_id
                Case 328, 453, 313, 249, 1638, 1601, 284, 414, 2210
                    bill_type = 1
                Case Else
                    bill_type = 2
            End Select
        Case "671", "672", "652", "654", "655", "656", "657"
            bill_type = 1
        Case Else
            Select Case cust_id
                'Double quotes around customer id's were taken off by LFW, 1/10/03
                Case 453, 313, 249, 1638, 1601, 284, 414, 2210
                    bill_type = 1
                Case Else
                    bill_type = 2
            End Select
    End Select
End Function

Private Function isDayHoliday(bill_date As Date)
    Dim sqlStmt As String
    Dim hlRS As Object
    Dim holiday As String
    Dim day As Date
    
    holiday = "'NYD', 'MLK', 'GFD', 'MD', 'IND', 'LD', 'TD', 'CD'"
    
    isDayHoliday = False
   
    sqlStmt = "SELECT * FROM Holiday_List WHERE HOLIDAY_ID IN (" & holiday & ")"
    Set hlRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)

    If hlRS.EOF And hlRS.BOF Then
        isDayHoliday = False
    Else
        hlRS.MoveFirst
        Do While Not hlRS.EOF
            day = hlRS.Fields("HOLIDAY_DATE").Value
            If day = bill_date Then
                isDayHoliday = True
                Exit Function
            End If
            hlRS.MoveNext
        Loop
    End If

    hlRS.Close
End Function

'****************************************************************
' This function will return the labor type. We do it since Dole
' has special requirement to the labor ticket.
' RETURN:
' 0: Supervision
' 1: Lift Truck Operator
' 4: Laborer
' 9: Checker
' 1C: Lift Trucker Operator by Casual Worker
' 1R: Lift Trucker Operator by Regular Worker
' 4C: Laborer by Casual Worker
' 4R: Laborer by Regular Worker
' 9C: Checker by Casual Worker
' 9R: Checker by Regular Worker
'****************************************************************
Private Function lab_type(cust_id As Long, emp_id As String, service_type As Integer) As String
    'Dole has special requirement. They want to differ the employee type
    'CASULE from REGULAR in service type CHECKER, LIFT TRUCK OPERATOR
    'and LABORER
    If cust_id = "453" Or cust_id = "313" Then 'Dole
        If service_type = 0 Then
            lab_type = "0"
        Else
            Dim EmpType As String
            EmpType = emp_type(emp_id)
            lab_type = CStr(service_type) + EmpType
        End If
    Else
        lab_type = CStr(service_type)
    End If
End Function

Private Function emp_type(emp_id As String) As String
    sqlStmt = " SELECT EMPLOYEE_TYPE_ID FROM EMPLOYEE WHERE EMPLOYEE_ID = '" & emp_id & "'"
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If Not rs.EOF And rs.Fields("employee_type_id").Value = "REGR" Then
        emp_type = "R"
    ElseIf rs.Fields("employee_type_id").Value = "SUPV" Then
        emp_type = "S"
    ElseIf rs.Fields("employee_type_id").Value = "CAS" Then
        emp_type = "C"
    ElseIf rs.Fields("employee_type_id").Value = "CASB" Then
        emp_type = "C"
    ElseIf rs.Fields("employee_type_id").Value = "CASC" Then
        emp_type = "C"
    End If
              
End Function

Private Sub add_to_SSDBGDate(service_type As Integer, cust_id As Long, emp_id As String, Start_Time As Date, End_Time As Date)
    If Start_Time >= End_Time Then
        Exit Sub
    End If
    
    Dim stype As String
    stype = lab_type(cust_id, emp_id, service_type)
    
    If stype = "1" Or stype = "1C" Then
        'SSDBGDate1.AddItem Format(Start_Time, "HH:MM") + Chr(9) + _
        '                   Format(End_Time, "HH:MM")
        SSDBGDate1.AddItem Format(Start_Time, "MM/DD/YYYY HH:MM") + Chr(9) + _
                           Format(End_Time, "MM/DD/YYYY HH:MM")
    ElseIf stype = "1R" Then
        'SSDBGDate5.AddItem Format(Start_Time, "HH:MM") + Chr(9) + _
        '                   Format(End_Time, "HH:MM")
        SSDBGDate5.AddItem Format(Start_Time, "MM/DD/YYYY HH:MM") + Chr(9) + _
                            Format(End_Time, "MM/DD/YYYY HH:MM")
    ElseIf stype = "4" Or stype = "4C" Then
        'SSDBGDate2.AddItem Format(Start_Time, "HH:MM") + Chr(9) + _
        '                   Format(End_Time, "HH:MM")
        SSDBGDate2.AddItem Format(Start_Time, "MM/DD/YYYY HH:MM") + Chr(9) + _
                           Format(End_Time, "MM/DD/YYYY HH:MM")
    ElseIf stype = "4R" Then
        'SSDBGDate6.AddItem Format(Start_Time, "HH:MM") + Chr(9) + _
        '                   Format(End_Time, "HH:MM")
        SSDBGDate6.AddItem Format(Start_Time, "MM/DD/YYYY HH:MM") + Chr(9) + _
                           Format(End_Time, "MM/DD/YYYY HH:MM")
    ElseIf stype = "9" Or stype = "9C" Then
        'SSDBGDate3.AddItem Format(Start_Time, "HH:MM") + Chr(9) + _
        '                   Format(End_Time, "HH:MM")
        SSDBGDate3.AddItem Format(Start_Time, "MM/DD/YYYY HH:MM") + Chr(9) + _
                           Format(End_Time, "MM/DD/YYYY HH:MM")
    ElseIf stype = "9R" Then
        'SSDBGDate7.AddItem Format(Start_Time, "HH:MM") + Chr(9) + _
        '                   Format(End_Time, "HH:MM")
        SSDBGDate7.AddItem Format(Start_Time, "MM/DD/YYYY HH:MM") + Chr(9) + _
                           Format(End_Time, "MM/DD/YYYY HH:MM")
    ElseIf stype = "0" Then
        'SSDBGDate4.AddItem Format(Start_Time, "HH:MM") + Chr(9) + _
        '                   Format(End_Time, "HH:MM")
        SSDBGDate4.AddItem Format(Start_Time, "MM/DD/YYYY HH:MM") + Chr(9) + _
                           Format(End_Time, "MM/DD/YYYY HH:MM")
    End If
End Sub
Private Sub show_date(cust_id As Long, service_group As Integer)
    Dim lift As String, labo As String, check As String
    Dim payType As String
    
    payType = pay_type(cust_id, service_group)
        
    SSDBGTicket.RemoveAll
    SSDBGTicket.MoveFirst
    
    If cust_id = "453" Or cust_id = "313" Then 'Dole
        If SSDBGDate1.rows > 0 Then
            Call group_time(SSDBGDate1, "LIFT TRUCKS CASUAL", "CASO", payType)
        End If
        
        If SSDBGDate5.rows > 0 Then
            Call group_time(SSDBGDate5, "LIFT TRUCKS REGULAR", "LIFT", payType)
        End If
        
        If SSDBGDate2.rows > 0 Then
            Call group_time(SSDBGDate2, "LABORERS CASUAL", "CASL", payType)
        End If
        
        If SSDBGDate6.rows > 0 Then
            Call group_time(SSDBGDate6, "LABORERS REGULAR", "LABO", payType)
        End If
        
        If SSDBGDate3.rows > 0 Then
            Call group_time(SSDBGDate3, "CHECKERS CASUAL", "CASC", payType)
        End If
        
        If SSDBGDate7.rows > 0 Then
            Call group_time(SSDBGDate7, "CHECKERS REGULAR", "CHEC", payType)
        End If
    Else
        If cust_id = "284" Then
            lift = "GANG"
            labo = "GANG"
            check = "GANG"
        ElseIf cust_id = "249" Or cust_id = "1638" Then
            lift = "BFOP"
            labo = "FLAB"
            check = "CHEC"
        Else
            lift = "LIFT"
            labo = "LABO"
            check = "CHEC"
        End If
        If SSDBGDate1.rows > 0 Then
             Call group_time(SSDBGDate1, "LIFT TRUCKS", lift, payType)
        End If
         
        If SSDBGDate2.rows > 0 Then
         Call group_time(SSDBGDate2, "LABORERS", labo, payType)
        End If
        
        If SSDBGDate3.rows > 0 Then
         Call group_time(SSDBGDate3, "CHECKERS", check, payType)
        End If
    End If
    
    If SSDBGDate4.rows > 0 Then
        Call group_time(SSDBGDate4, "SUPERVISORS", "SUPE", payType)
    End If
    
    SSDBGTicket.Refresh
End Sub

'****************************************
'To Find the Number of Hours between Start and End Time
'****************************************
Private Function Duration(sTime As Date, eTime As Date) As Single
    Dim mins As Single
    mins = DateDiff("n", sTime, eTime)
    
    If mins < 0 Then
        mins = 24 * 60 + mins
    End If
     
    Duration = mins / 60
End Function
Private Sub setTotalHour()
    Dim i As Integer, Index As Integer
    Dim Total As Single
    Total = 0
    Index = SSDBGTicket.row
    
    SSDBGTicket.MoveFirst
    If SSDBGTicket.rows <> 0 Then
        For i = 0 To SSDBGTicket.rows - 1
            'SSDBGTicket.Row = i
            If Trim(SSDBGTicket.Columns(5).Value) <> vbNullString Then
              Total = Total + CSng(SSDBGTicket.Columns(5).Value)
            End If
            SSDBGTicket.MoveNext
        Next
    End If
    
    totalHourLabel.Caption = CStr(Total)
    
    'SSDBGTicket.Row = Index
End Sub

Private Sub group_time(date1 As SSDBGrid, emp_type As String, lab_type As String, pay_type As String)
    Dim sTime As Date
    Dim sTime1 As Date
    Dim start_date As Date
    Dim end_date As Date
    Dim zeroAM As Date
    Dim count As Integer
    Dim pre_count As Integer
    Dim rows As Integer
    Dim j As Integer
    Dim ticket As String
    Dim myduration As Single, Total As Single
    Dim firstRecord As Boolean

    'zeroAM = Format("00:00", "HH:MM")
    zeroAM = Format(CStr(DTPDate.Value) + " 00:00", "MM/DD/YYYY HH:MM")
    
    rows = date1.rows
    pre_count = 0
    count = 0
    firstRecord = False
    For i = 0 To 48
        'sTime = Zero_AM + 0.5 / 24 * i
        sTime = DateAdd("n", 30 * i, zeroAM)
        date1.MoveFirst
        For j = 0 To rows - 1
            'start_date = Format(date1.Columns(0).Value, "HH:MM")
            'end_date = Format(date1.Columns(1).Value, "HH:MM")
            start_date = Format(date1.Columns(0).Value, "MM/DD/YYYY HH:MM")
            end_date = Format(date1.Columns(1).Value, "MM/DD/YYYY HH:MM")
            
            If DateDiff("n", start_date, sTime) >= 0 And DateDiff("n", end_date, sTime) < 0 Then
                count = count + 1
            End If
            date1.MoveNext
        Next
        
        If pre_count = 0 And count > 0 Then
            If firstRecord Then
                ticket = " "
            Else
                ticket = emp_type
                firstRecord = True
            End If
            
            sTime1 = sTime
            ticket = ticket + Chr(9) + _
                     Trim(Str(count)) + Chr(9) + _
                     Format(sTime1, "HH:MM") + Chr(9)
        ElseIf i = 2 Or i = 14 Or i = 26 Or i = 38 Or i = 12 Or i = 24 Or i = 36 Or i = 16 Or i = 34 Then
            If pre_count > 0 Then
                myduration = Duration(sTime1, sTime)
                Total = pre_count * myduration
                ticket = ticket + Format(sTime, "HH:MM") + Chr(9) + _
                     CStr(myduration) + Chr(9) + CStr(Total) + Chr(9) + _
                     emp_type + Chr(9) + lab_type + Chr(9) + rate_type(Format(sTime1, "HH:MM"), pay_type)
                SSDBGTicket.AddItem ticket
                
                If count > 0 Then
                    ticket = " " + Chr(9) + _
                             Trim(Str(count)) + Chr(9) + _
                             Format(sTime, "HH:MM") + Chr(9)
                    sTime1 = sTime
                End If
            End If
        ElseIf pre_count > 0 And count > 0 And count <> pre_count Then
            myduration = Duration(sTime1, sTime)
            Total = pre_count * myduration
            ticket = ticket + Format(sTime, "HH:MM") + Chr(9) + _
                     CStr(myduration) + Chr(9) + CStr(Total) + Chr(9) + _
                     emp_type + Chr(9) + lab_type + Chr(9) + rate_type(Format(sTime1, "HH:MM"), pay_type)
            SSDBGTicket.AddItem ticket
                               
            ticket = " " + Chr(9) + _
                     Trim(Str(count)) + Chr(9) + _
                     Format(sTime, "HH:MM") + Chr(9)
            sTime1 = sTime
        ElseIf pre_count > 0 And count = 0 Then
            myduration = Duration(sTime1, sTime)
            Total = pre_count * myduration
            ticket = ticket + Format(sTime, "HH:MM") + Chr(9) + _
                     CStr(myduration) + Chr(9) + CStr(Total) + Chr(9) + _
                     emp_type + Chr(9) + lab_type + Chr(9) + rate_type(Format(sTime1, "HH:MM"), pay_type)
            SSDBGTicket.AddItem ticket
        End If
        
        pre_count = count
        count = 0
    Next
End Sub
Private Sub display(ticket_date As String, cust_id As Long, service_group As Integer)
    isDisplay = True
    
'    If cust_id = 0 And service_group = 0 Then
''        sqlStmt = "SELECT D.*, subStr(service_code, 1, 3) SERVICE_GROUP FROM HOURLY_DETAIL D WHERE USER_ID = '" & UserID & "' AND " & _
''                " HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') ORDER BY CUSTOMER_ID, SERVICE_GROUP"
'        'Get service group ID from Service table   -- LFW, 8/9/2005
'        sqlStmt = "SELECT D.*, S.SERVICE_GROUP_ID FROM HOURLY_DETAIL D, SERVICE S " & _
'                  "WHERE D.SERVICE_CODE = S.SERVICE_CODE AND (SPECIAL_CODE = '" & SuperInitials & "' OR (USER_ID = '" & UserID & "' AND SPECIAL_CODE IS NULL) AND " & _
'                  "HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY')" & _
'                  "ORDER BY CUSTOMER_ID, SERVICE_GROUP_ID"
'        Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'        If Not rs.EOF Then
'            If IsNull(rs.Fields("customer_id")) Or IsNull(rs.Fields("service_group_id")) Then
'                'Do Nothing
'            Else
'                cust_id = rs.Fields("customer_id").Value
'                service_group = rs.Fields("service_group_id").Value
'            End If
'        Else
'            Call cleanForm
'            Call setupForm
'            Exit Sub
'        End If
'    ElseIf service_group = 0 Then
'
'    ' Take off the If statement, so all customers will get different labor ticket for vessels of the same day
'    ' -- LFW, 7/17/2003
'    '    If (cust_id = 313 Or cust_id = 453 Or cust_id = 1601) And Not (txtShipId.Text = "") Then
''        sqlStmt = "SELECT D.*, subStr(service_code, 1, 3) SERVICE_GROUP FROM HOURLY_DETAIL D WHERE USER_ID = '" & UserID & "' AND " & _
''                  " CUSTOMER_ID= " & cust_id & " AND HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') AND VESSEL_ID = '" & txtShipId.Text & "' ORDER BY SERVICE_GROUP"
'    '    Else
'    '        sqlStmt = "SELECT D.*, subStr(service_code, 1, 3) SERVICE_GROUP FROM HOURLY_DETAIL D WHERE USER_ID = '" & UserID & "' AND " & _
'                    " CUSTOMER_ID= " & cust_id & " AND HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') ORDER BY SERVICE_GROUP"
'    '    End If
'
'        'Get the service code from SERVICE table  -- LFW, 8/9/2005
'        sqlStmt = "SELECT D.*, S.SERVICE_GROUP_ID FROM HOURLY_DETAIL D, SERVICE S WHERE D.SERVICE_CODE = S.SERVICE_CODE AND " & _
'                  "(D.SPECIAL_CODE = '" & SuperInitials & "' OR (D.USER_ID = '" & UserID & "' AND D.SPECIAL_CODE IS NULL) AND D.CUSTOMER_ID = " & cust_id & " AND D.HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') AND " & _
'                  "D.VESSEL_ID = '" & txtShipId.Text & "' ORDER BY S.SERVICE_GROUP_ID"
'
'        Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'        If Not rs.EOF Then
'            cust_id = rs.Fields("customer_id").Value
'            service_group = rs.Fields("service_group_id").Value
'        Else
'            Call cleanForm
'            Call setupForm
'            Exit Sub
'        End If
'    ' We have both Service and customer
'    Else
'        ' Take off the If statement, so all customers will get different labor ticket for vessels of the same day
'        ' -- LFW, 7/17/2003
''        If (cust_id = 313 Or cust_id = 453 Or cust_id = 1601) And Not (txtShipId.Text = "") Then
''        sqlStmt = "SELECT D.*, subStr(service_code, 1, 3) SERVICE_GROUP FROM HOURLY_DETAIL D WHERE USER_ID = '" & UserID & "' AND subStr(SERVICE_CODE, 1, 3) = '" & service_group & "' AND " & _
''                  " CUSTOMER_ID = " & cust_id & " AND HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') AND VESSEL_ID = '" & txtShipId.Text & "'"
''        Else
''            sqlStmt = "SELECT D.*, subStr(service_code, 1, 3) SERVICE_GROUP FROM HOURLY_DETAIL D WHERE USER_ID = '" & UserID & "' AND subStr(SERVICE_CODE, 1, 3) = '" & service_group & "' AND " & _
''                    " CUSTOMER_ID = " & cust_id & " AND HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') ORDER BY SERVICE_GROUP"
''        End If
'
'        'Get service group id from SERVICE table   -- LFW, 8/9/2005
''        sqlStmt = "SELECT D.*, S.SERVICE_GROUP_ID FROM HOURLY_DETAIL D, SERVICE S " & _
''                  "WHERE D.SERVICE_CODE = S.SERVICE_CODE AND (D.SPECIAL_CODE = '" & SuperInitials & "' OR (D.USER_ID = '" & UserID & "' AND D.SPECIAL_CODE IS NULL)) " & _
''                  "AND S.SERVICE_GROUP_ID = " & service_group & " AND CUSTOMER_ID = " & cust_id & " AND " & _
''                  "HIRE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') AND VESSEL_ID = '" & txtShipId.Text & "'"
'
'
'        Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'        If rs.Fields("THE_COUNT").Value > 0 Then
''            cust_id = rs.Fields("customer_id").Value
''            service_group = rs.Fields("service_group_id").Value
'        Else
'            Call cleanForm
'            Call setupForm
'            Exit Sub
'        End If
'    End If
    
    'HD9281
    'The above code from LFW, 8/9/2005 was designed to make sure that a record existed at all before proceeding, but as it only checked
    'HOURLY_DETAIL and not LABOR_TICKET_HEADER, it isn't doing much good.  replacing it with the below SQL, 5/2014...
    sqlStmt = " SELECT COUNT(*) THE_COUNT FROM " & _
                    " (SELECT G.SERVICE_GROUP_ID, G.SERVICE_GROUP_NAME " & _
                        " FROM HOURLY_DETAIL D, SERVICE S, SERVICE_GROUP G " & _
                        " WHERE (USER_ID = '" & UserID & "' OR SPECIAL_CODE = '" & SuperInitials & "') " & _
                        " AND D.HIRE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') AND D.CUSTOMER_ID = " & txtCustId.Text & _
                        " AND S.SERVICE_CODE = D.SERVICE_CODE AND S.STATUS = 'N' AND S.SERVICE_GROUP_ID = G.SERVICE_GROUP_ID " & _
                        " AND D.COMMODITY_CODE = '" & txtCommId & "' " & _
                        " AND VESSEL_ID = '" & txtShipId.Text & "' " & _
                        " AND D.LOCATION_ID = '" & txtArea.Text & "' " & _
                        " AND G.SERVICE_GROUP_ID = '" & txtServiceGroup & "' " & _
                    " UNION " & _
                    " SELECT G.SERVICE_GROUP_ID, G.SERVICE_GROUP_NAME " & _
                        " FROM LABOR_TICKET_HEADER L, SERVICE S, SERVICE_GROUP G" & _
                        " WHERE L.USER_ID = '" & UserID & "' " & _
                        " AND L.SERVICE_DATE = TO_DATE('" & DTPDate.Value & "', 'MM/DD/YYYY') AND L.CUSTOMER_ID = " & txtCustId.Text & _
                        " AND G.SERVICE_GROUP_ID = L.SERVICE_GROUP AND S.STATUS = 'N' AND S.SERVICE_GROUP_ID = G.SERVICE_GROUP_ID " & _
                        " AND L.COMMODITY_CODE = '" & txtCommId & "' " & _
                        " AND VESSEL_ID = '" & txtShipId.Text & "' " & _
                        " AND L.LOCATION_ID = '" & txtArea.Text & "' " & _
                        " AND G.SERVICE_GROUP_ID = '" & txtServiceGroup & "' " & _
                    " ) "
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If rs.Fields("THE_COUNT").Value > 0 Then
        ' records exist, do nothing
    Else
        Call cleanForm
        Call setupForm
        Exit Sub
    End If
        
    
    
    ' Take off the If statement, so all customers will get different labor ticket for vessels of the same day
    ' -- LFW, 7/17/2003
'   If (cust_id = 313 Or cust_id = 453 Or cust_id = 1601) And Not (txtShipId.Text = "") Then
    sqlStmt = " SELECT * FROM LABOR_TICKET_HEADER WHERE USER_ID = '" & UserID & "' AND " & _
              " VESSEL_ID = '" & txtShipId.Text & "' AND LOCATION_ID = '" & txtArea.Text & "' AND " & _
              " CUSTOMER_ID = " & cust_id & " AND SERVICE_GROUP = '" & service_group & "' AND " & _
              " SERVICE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY')" & _
              " AND COMMODITY_CODE=" & Trim(Me.txtCommId.Text)
'   Else
'        sqlStmt = " SELECT * FROM LABOR_TICKET_HEADER WHERE USER_ID = '" & UserID & "' AND " & _
              " CUSTOMER_ID = " & cust_id & " AND SERVICE_GROUP = '" & service_group & "' AND " & _
              " SERVICE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY')"
'    End If
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If Not rs.EOF Then
        If rs.Fields("BILL_STATUS").Value <> "N" And rs.Fields("BILL_STATUS").Value <> "Y" Then
            MsgBox "Ticket Number " & rs.Fields("TICKET_NUM").Value & " has been labeled Unbillable by finance.  Please contact them for further information."
        Else
            Call retrieveTicket(ticket_date, cust_id, service_group, Trim(Me.txtCommId.Text))
        End If
    Else
        Call retrieve(ticket_date, cust_id, service_group, Trim(Me.txtCommId.Text))
    End If
    
    Call setupForm
    
End Sub
    
'Private Sub SSDBGTicket_Change()
'    Dim RowIndex As Integer, ColIndex As Integer
'    RowIndex = SSDBGTicket.row
'    ColIndex = SSDBGTicket.Col
    
'    Call setTotalHour
    
'    SSDBGTicket.row = RowIndex
'    SSDBGTicket.Col = ColIndex
'End Sub

Private Sub txtCust_change()
    
    '' commented out by pwu 7/28/2006
    ''If isDisplay Then
    ''    Exit Sub
    ''End If
    
    ''Call display(DTPDate.Value, CLng(txtCustId.Text), 0)
    ''commented out by pwu 7/28/2006
End Sub
Private Sub retrieveTicket(ticket_date As String, cust_id As Long, service_group As Integer, com_code)
    Dim Duration As Single
    Dim Total As Single
    Dim count As Integer
    Dim pre_emp_type As String, emp_type As String, myrec As String
    Dim lab_type As String, rate_type As String

    SSDBGTicket.RemoveAll
    SSDBGTicket.MoveFirst
    
    ' Take off the If statement, so all customers will get different labor ticket for vessels of the same day
    ' -- LFW, 7/17/2003
    'If (cust_id = 313 Or cust_id = 453 Or cust_id = 1601) And Not (txtShipId.Text = "") Then
    sqlStmt = "SELECT * FROM LABOR_TICKET_HEADER WHERE USER_ID = '" & UserID & "' AND LOCATION_ID = '" & txtArea.Text & "' AND " & _
            " CUSTOMER_ID = " & cust_id & " AND SERVICE_GROUP = '" & service_group & "' AND " & _
            " SERVICE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY') AND VESSEL_ID = '" & txtShipId.Text & "'" & _
            " AND COMMODITY_CODE=" & com_code & " AND (BILL_STATUS IS NULL OR BILL_STATUS = 'Y')"
    'Else
    '    sqlStmt = " SELECT * FROM LABOR_TICKET_HEADER WHERE USER_ID = '" & UserID & "' AND " & _
    '          " CUSTOMER_ID = " & cust_id & " AND SERVICE_GROUP = " & service_group & " AND " & _
    '          " SERVICE_DATE = TO_DATE('" & ticket_date & "', 'MM/DD/YYYY')"
    'End If
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    txtNo.Text = rs.Fields("ticket_num").Value
    txtCustId.Text = cust_id
    txtCust.Text = fnCustomer(rs.Fields("customer_id").Value)
    txtShipId.Text = rs.Fields("vessel_id").Value
        
    txtShip.Text = fnVESSEL(txtShipId.Text)
    txtSup.Text = fnEmp(UserID)
    txtArea.Text = rs.Fields("location_id").Value
    txtCommId.Text = rs.Fields("commodity_code").Value
    txtComm.Text = fnCOMMODITY(rs.Fields("commodity_code").Value)
    txtServiceGroup.Text = rs.Fields("service_group").Value
    txtService.Text = fnServiceGroup(rs.Fields("service_group").Value)
    
    ' Without this check, we'll get run time error when job_description is null
    If Not (rs.Fields("job_description").Value = vbNullString) Then
        txtJobDesc.Text = rs.Fields("job_description").Value
    End If
    
    'sqlStmt = " SELECT * FROM LABOR_TICKET, SERVICE_TYPE WHERE TICKET_NUM = " + txtNo.Text & _
    '          " AND SERVICE_TYPE = EMP_TYPE ORDER BY ID, START_TIME "
    sqlStmt = " SELECT * FROM LABOR_TICKET WHERE TICKET_NUM = " + txtNo.Text & _
              " ORDER BY EMP_TYPE, START_TIME "
    Set rsDETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    Do While Not rsDETAIL.EOF
        count = GetValue(rsDETAIL.Fields("qty").Value, 0)
        Duration = GetValue(rsDETAIL.Fields("hours").Value, 0)
        Total = count * Duration
        emp_type = GetValue(rsDETAIL.Fields("emp_type").Value, "")
        lab_type = GetValue(rsDETAIL.Fields("labor_type").Value, "")
        rate_type = GetValue(rsDETAIL.Fields("rate_type").Value, "")
        
        myrec = Chr(9) + Trim(CStr(count)) + Chr(9) + _
                Format(rsDETAIL.Fields("start_time").Value, "HH:MM") + Chr(9) + _
                Format(rsDETAIL.Fields("end_time").Value, "HH:MM") + Chr(9) + _
                CStr(Duration) + Chr(9) + CStr(Total) + Chr(9) + _
                emp_type + Chr(9) + lab_type + Chr(9) + rate_type
        
        If pre_emp_type = emp_type Then
            SSDBGTicket.AddItem " " + myrec
        Else
            SSDBGTicket.AddItem emp_type + myrec
            pre_emp_type = emp_type
        End If

        rsDETAIL.MoveNext
    Loop
    
    Set rsDETAIL = Nothing
End Sub

Private Sub cleanForm()
    'txtNo.Text = ""
    'txtCust.Text = ""
    'txtCustId.Text = ""
    'txtComm.Text = ""
    'txtCommId.Text = ""
    'txtShip.Text = ""
    'txtShipId.Text = ""
    'txtJobDesc.Text = ""
    'txtArea.Text = ""
    'txtService.Text = ""
    'txtServiceGroup.Text = ""
    totalHourLabel.Caption = ""
    
    SSDBGTicket.RemoveAll

    isDisplay = False
End Sub

Private Sub txtNo_lostfocus()
    sqlStmt = "SELECT LTH.CUSTOMER_ID, LTH.COMMODITY_CODE, LTH.LOCATION_ID, TO_CHAR(LTH.SERVICE_DATE, 'MM/DD/YYYY') THE_DATE, LTH.SERVICE_GROUP, " & _
                "CUSP.CUSTOMER_NAME, COMP.COMMODITY_NAME, SG.SERVICE_GROUP_NAME, EMP.EMPLOYEE_NAME, VP.VESSEL_NAME, LTH.VESSEL_ID " & _
                "FROM LABOR_TICKET_HEADER LTH, CUSTOMER_PROFILE CUSP, SAG_OWNER.COMMODITY_PROFILE COMP, VESSEL_PROFILE VP, EMPLOYEE EMP, SERVICE_GROUP SG " & _
                "WHERE LTH.vessel_id = VP.LR_NUM " & _
                "AND LTH.COMMODITY_CODE = COMP.COMMODITY_CODE " & _
                "AND LTH.CUSTOMER_ID = CUSP.CUSTOMER_ID " & _
                "AND LTH.USER_ID = EMP.EMPLOYEE_ID " & _
                "AND LTH.SERVICE_GROUP = SG.SERVICE_GROUP_ID " & _
                "AND LTH.TICKET_NUM = '" & txtNo.Text & "' " & _
                "AND LTH.USER_ID = '" & UserID & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.CreateDynaset(sqlStmt, 0&)
    If dsSHORT_TERM_DATA.RecordCount = 0 Then
        MsgBox "ticket " & txtNo.Text & " Not in system for logged in Supervisor."
        SSDBGTicket.RemoveAll
        txtNo = ""
        txtCust = ""
        txtCustId = ""
        txtShip = ""
        txtShipId = ""
        txtSup = ""
        txtService = ""
        txtServiceGroup = ""
        txtComm = ""
        txtCommId = ""
        txtArea = ""
    Else
        txtCust = dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value
        txtCustId = dsSHORT_TERM_DATA.Fields("CUSTOMER_ID").Value
        txtShip = dsSHORT_TERM_DATA.Fields("VESSEL_NAME").Value
        txtShipId = dsSHORT_TERM_DATA.Fields("VESSEL_ID").Value
        txtSup = dsSHORT_TERM_DATA.Fields("EMPLOYEE_NAME").Value
        txtService = dsSHORT_TERM_DATA.Fields("SERVICE_GROUP_NAME").Value
        txtServiceGroup = dsSHORT_TERM_DATA.Fields("SERVICE_GROUP").Value
        txtComm = dsSHORT_TERM_DATA.Fields("COMMODITY_NAME").Value
        txtCommId = dsSHORT_TERM_DATA.Fields("COMMODITY_CODE").Value
        txtArea = dsSHORT_TERM_DATA.Fields("LOCATION_ID").Value
        DTPDate.Value = dsSHORT_TERM_DATA.Fields("THE_DATE").Value
        
        Call display(DTPDate.Value, CLng(txtCustId.Text), CLng(txtServiceGroup.Text))
    End If
        
End Sub

Private Sub txtService_Change()
''' commented out by pwu 7/28/2006
'    If isDisplay Then
'        Exit Sub
'    End If
'
'    Call display(DTPDate.Value, CLng(txtCustId.Text), CLng(txtServiceGroup.Text))
''' commented out by pwu 7/28/2006
End Sub

Function GetValue(DataValue As Variant, Default As Variant) As Variant
  If IsNull(DataValue) Then
    GetValue = Default
  Else
    GetValue = DataValue
  End If
End Function

Private Function validateTicket() As Boolean
    SSDBGTicket.MoveFirst
    For i = 0 To SSDBGTicket.rows - 1
        If Trim(SSDBGTicket.Columns(6).Value) = vbNullString And Trim(SSDBGTicket.Columns(0).Value) = vbNullString Then
             MsgBox "Type is Required! ", vbInformation, "Authorization Required"
             validateTicket = False
             Exit Function
        ElseIf Trim(SSDBGTicket.Columns(1).Value) = vbNullString Then
             MsgBox "Count is Required! ", vbInformation, "Authorization Required"
             validateTicket = False
             Exit Function
        ElseIf Trim(SSDBGTicket.Columns(2).Value) = vbNullString Then
             MsgBox "Start Time is Required! ", vbInformation, "Authorization Required"
             validateTicket = False
             Exit Function
        ElseIf Trim(SSDBGTicket.Columns(3).Value) = vbNullString Then
             MsgBox "End Time is Required! ", vbInformation, "Authorization Required"
             validateTicket = False
             Exit Function
        ElseIf Trim(SSDBGTicket.Columns(4).Value) = vbNullString Then
             MsgBox "Duration is Required! ", vbInformation, "Authorization Required"
             validateTicket = False
             Exit Function
        ElseIf Trim(SSDBGTicket.Columns(7).Value) = vbNullString Then
             MsgBox "Labor Type is Required! ", vbInformation, "Authorization Required"
             validateTicket = False
             Exit Function
        ElseIf Trim(SSDBGTicket.Columns(8).Value) = vbNullString Then
             MsgBox "Rate Type is Required! ", vbInformation, "Authorization Required"
             validateTicket = False
             Exit Function
        End If
        
        SSDBGTicket.MoveNext
    Next
    
    validateTicket = True
End Function

Private Function getVesselName(id As String) As String
    Dim dsVESSEL As Object
    Dim sqlStmt1 As String
    Dim OraDatabase2 As Object
    
    'Set OraDatabase2 = OraSession.Open Database("BNI", "SAG_OWNER/SAG", 0&)
    'Set OraDatabase2 = OraSession.Open Database("BNI.DEV", "SAG_OWNER/SAG_DEV", 0&)  '2853 3/29/2007 Rudy: for testing, orig above
    Set OraDatabase2 = OraSession.OpenDatabase(DB, Login, 0&)  '5/2/2007 HD2759 Rudy: one init, orig above  TESTED /
    
    sqlStmt1 = "SELECT VESSEL_NAME FROM VESSEL_profile WHERE LR_NUM=" & id
    Set dsVESSEL = OraDatabase2.CreateDynaset(sqlStmt1, 0&)
    If dsVESSEL.RecordCount > 0 Then
        getVesselName = dsVESSEL.Fields("VESSEL_NAME").Value
    Else
        getVesselName = CStr(id)
    End If
        
    dsVESSEL.Close
    Set dsVESSEL = Nothing
    OraDatabase2.Close
    Set OraDatabase2 = Nothing
End Function

Private Function getVesselArray(idList() As String)
    Dim dsVESSEL As Object
    Dim sqlStmt1 As String
    Dim OraDatabase2 As Object
    Dim vArray() As String
    Dim hnum As Integer, lnum As Integer, size As Integer
    Dim ids As String
    
    hnum = UBound(idList)
    lnum = LBound(idList)
    
    ReDim vArray(lnum To hnum, 1)
    
    ids = getSQLList(idList, ",")
    
    'Set OraDatabase2 = OraSession.Open Database("BNI", "SAG_OWNER/SAG", 0&)
    'Set OraDatabase2 = OraSession.Open Database("BNI.DEV", "SAG_OWNER/SAG_DEV", 0&)  '2853 3/29/2007 Rudy: for testing, orig above
    Set OraDatabase2 = OraSession.OpenDatabase(DB, Login, 0&)  '5/2/2007 HD2759 Rudy: one init, orig above  TESTED /

    sqlStmt1 = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM IN (" & ids & ")"
    Set dsVESSEL = OraDatabase2.CreateDynaset(sqlStmt1, 0&)
    
    size = lnum
    While Not dsVESSEL.EOF
        vArray(size, 0) = dsVESSEL.Fields("LR_NUM").Value
        vArray(size, 1) = dsVESSEL.Fields("VESSEL_NAME").Value
        dsVESSEL.MoveNext
        size = size + 1
    Wend
        
    dsVESSEL.Close
    Set dsVESSEL = Nothing
    OraDatabase2.Close
    Set OraDatabase2 = Nothing
    
    getVesselArray = vArray()
End Function

Private Function getSQLList(idList() As String, delimiter As String) As String
    Dim hnum As Integer, lnum As Integer, i As Integer
    Dim ids As String
    
    hnum = UBound(idList)
    lnum = LBound(idList)
    
    If hnum = 0 Then
        ids = ""
    Else
        For i = lnum To hnum
            If idList(i) <> "" Then
                ids = ids + idList(i) + delimiter
            End If
        Next
        
        ids = Left(ids, Len(ids) - 1)
    End If
    
    getSQLList = ids
End Function

Private Function laborTicketStatus() As Boolean
    Dim ticket As String
    Dim sqlStmt As String, status As String
    Dim statusRS As Object
    
    ticket = txtNo.Text
    
    If ticket = "" Then
        laborTicketStatus = True
        Exit Function
    Else
        sqlStmt = " SELECT BILL_STATUS FROM LABOR_TICKET_HEADER WHERE TICKET_NUM = " + txtNo.Text
        Set statusRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        If statusRS.RecordCount > 0 Then
            status = GetValue(statusRS.Fields("BILL_STATUS").Value, "")
        Else
            status = ""
        End If
        Set statusRS = Nothing
        
        If status = "Y" Or status = "U" Then
            laborTicketStatus = False
        Else
            laborTicketStatus = True
        End If
    End If
End Function

Private Sub txtShip_Change()

'    '' '' commented out by pwu 7/28/2006
'    If isDisplay Then
'        Exit Sub
'    End If
'
'    If txtCustId.Text = "" Then
'        Call display(DTPDate.Value, 0, 0)
'    ElseIf txtServiceGroup.Text = "" Then
'        Call display(DTPDate.Value, CLng(txtCustId.Text), 0)
'    Else
'        Call display(DTPDate.Value, CLng(txtCustId.Text), CLng(txtServiceGroup.Text))
'    End If
'    '''' commented out by pwu 7/28/2006
End Sub

