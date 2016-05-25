VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Begin VB.Form frmHourlyDet 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Hourly Detail"
   ClientHeight    =   10395
   ClientLeft      =   570
   ClientTop       =   855
   ClientWidth     =   14910
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   10395
   ScaleWidth      =   14910
   Begin VB.TextBox des 
      Height          =   375
      Left            =   11520
      TabIndex        =   45
      Top             =   3000
      Width           =   3255
   End
   Begin VB.CommandButton cmdPreView 
      Caption         =   "PREVIEW LABOR TICKET"
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
      Left            =   9240
      TabIndex        =   43
      Top             =   315
      Width           =   2520
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
      Height          =   5535
      Left            =   120
      TabIndex        =   42
      Top             =   4200
      Width           =   14745
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   18
      AllowAddNew     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowColumnMoving=   0
      AllowGroupSwapping=   0   'False
      AllowColumnSwapping=   0
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      SelectTypeRow   =   0
      BackColorOdd    =   65535
      RowHeight       =   423
      ExtraHeight     =   582
      Columns.Count   =   18
      Columns(0).Width=   3200
      Columns(0).Visible=   0   'False
      Columns(0).Caption=   "RowNum"
      Columns(0).Name =   "RowNum"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   2117
      Columns(1).Caption=   "START"
      Columns(1).Name =   "START"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   2143
      Columns(2).Caption=   "END"
      Columns(2).Name =   "END"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1323
      Columns(3).Caption=   "HRS"
      Columns(3).Name =   "HRS"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1561
      Columns(4).Caption=   "REG/OT"
      Columns(4).Name =   "REG/OT"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   847
      Columns(5).Caption=   "BILL"
      Columns(5).Name =   "BILL"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(5).Style=   2
      Columns(6).Width=   1535
      Columns(6).Caption=   "SRVC"
      Columns(6).Name =   "SRVC"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1614
      Columns(7).Caption=   "EQUIP"
      Columns(7).Name =   "EQUIP"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1376
      Columns(8).Caption=   "COMM"
      Columns(8).Name =   "COMM"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1905
      Columns(9).Caption=   "LOCATION"
      Columns(9).Name =   "CATE"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   1376
      Columns(10).Caption=   "SHIP"
      Columns(10).Name=   "SHIP"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   1376
      Columns(11).Caption=   "CUST"
      Columns(11).Name=   "CUST"
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   1693
      Columns(12).Caption=   "SUPV"
      Columns(12).Name=   "SUPV"
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   1402
      Columns(13).Caption=   "SPEC"
      Columns(13).Name=   "SPEC"
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      Columns(14).Width=   1667
      Columns(14).Caption=   "REMARK"
      Columns(14).Name=   "REMARK"
      Columns(14).DataField=   "Column 14"
      Columns(14).DataType=   8
      Columns(14).FieldLen=   256
      Columns(15).Width=   1879
      Columns(15).Caption=   "EXACTEND"
      Columns(15).Name=   "EXACTEND"
      Columns(15).DataField=   "Column 15"
      Columns(15).DataType=   8
      Columns(15).FieldLen=   256
      Columns(16).Width=   1482
      Columns(16).Caption=   "LABOR TYPE"
      Columns(16).Name=   "LABOR TYPE"
      Columns(16).DataField=   "Column 16"
      Columns(16).DataType=   8
      Columns(16).FieldLen=   256
      Columns(16).Style=   3
      Columns(17).Width=   3200
      Columns(17).Visible=   0   'False
      Columns(17).Caption=   "rowid"
      Columns(17).Name=   "rowid"
      Columns(17).DataField=   "Column 17"
      Columns(17).DataType=   8
      Columns(17).FieldLen=   256
      _ExtentX        =   26009
      _ExtentY        =   9763
      _StockProps     =   79
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
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
   Begin VB.CommandButton Command1 
      Caption         =   "LABOR TICKET"
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
      Left            =   12075
      TabIndex        =   41
      Top             =   315
      Width           =   2520
   End
   Begin VB.Frame Frame3 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   1215
      Left            =   6510
      TabIndex        =   30
      Top             =   2310
      Visible         =   0   'False
      Width           =   2775
      Begin VB.OptionButton optFilterEmp 
         Caption         =   "&Name"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Index           =   1
         Left            =   120
         TabIndex        =   32
         Top             =   780
         Width           =   975
      End
      Begin VB.OptionButton optFilterEmp 
         Caption         =   "&ID"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Index           =   0
         Left            =   120
         TabIndex        =   31
         Top             =   300
         Width           =   855
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboEmp 
         Height          =   375
         Left            =   1200
         TabIndex        =   33
         ToolTipText     =   "Select Employee ID"
         Top             =   240
         Width           =   1500
         DataFieldList   =   "Column 0"
         MaxDropDownItems=   16
         AllowNull       =   0   'False
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         FieldSeparator  =   "!"
         RowHeight       =   609
         Columns.Count   =   3
         Columns(0).Width=   2117
         Columns(0).Caption=   "Supervisor_Id"
         Columns(0).Name =   "Supervisor_Id"
         Columns(0).Alignment=   1
         Columns(0).CaptionAlignment=   1
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   3043
         Columns(1).Caption=   "Supervisor_FName"
         Columns(1).Name =   "Supervisor_FName"
         Columns(1).Alignment=   1
         Columns(1).CaptionAlignment=   1
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   3200
         Columns(2).Caption=   "Supervisor_LName"
         Columns(2).Name =   "Supervisor_LName"
         Columns(2).Alignment=   1
         Columns(2).CaptionAlignment=   1
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         _ExtentX        =   2646
         _ExtentY        =   661
         _StockProps     =   93
         BackColor       =   -2147483643
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Enabled         =   0   'False
         DataFieldToDisplay=   "Column 0"
      End
      Begin SSDataWidgets_B.SSDBCombo ssdbcboName 
         Height          =   375
         Left            =   1200
         TabIndex        =   34
         ToolTipText     =   "Select Employee Name"
         Top             =   720
         Width           =   1500
         DataFieldList   =   "Column 0"
         MaxDropDownItems=   16
         AllowNull       =   0   'False
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         FieldSeparator  =   "!"
         RowHeight       =   609
         Columns.Count   =   3
         Columns(0).Width=   2117
         Columns(0).Caption=   "Supervisor_Id"
         Columns(0).Name =   "Supervisor_Id"
         Columns(0).Alignment=   1
         Columns(0).CaptionAlignment=   1
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   3043
         Columns(1).Caption=   "Supervisor_FName"
         Columns(1).Name =   "Supervisor_FName"
         Columns(1).Alignment=   1
         Columns(1).CaptionAlignment=   1
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   3200
         Columns(2).Caption=   "Supervisor_LName"
         Columns(2).Name =   "Supervisor_LName"
         Columns(2).Alignment=   1
         Columns(2).CaptionAlignment=   1
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         _ExtentX        =   2646
         _ExtentY        =   661
         _StockProps     =   93
         BackColor       =   -2147483643
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Enabled         =   0   'False
         DataFieldToDisplay=   "Column 0"
      End
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnHrs 
      Height          =   255
      Left            =   6960
      TabIndex        =   29
      Top             =   10680
      Width           =   1575
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      UseExactRowCount=   0   'False
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   2778
      _ExtentY        =   450
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.CommandButton cmdClearLine 
      Caption         =   "CL&EAR LINE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   6600
      Style           =   1  'Graphical
      TabIndex        =   28
      ToolTipText     =   "Clear the Current Record from Grid. No Changes in Database"
      Top             =   9960
      Width           =   2000
   End
   Begin VB.CommandButton cmdClearAll 
      Caption         =   "C&LEAR ALL"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   3840
      Style           =   1  'Graphical
      TabIndex        =   27
      ToolTipText     =   "Clear All Records from Grid. No Change in Database"
      Top             =   9960
      Width           =   2000
   End
   Begin VB.Frame Frame1 
      Caption         =   "View"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   1215
      Left            =   9855
      TabIndex        =   23
      Top             =   1680
      Width           =   4815
      Begin VB.CommandButton cmdViewAll 
         Caption         =   "&ALL EMPLOYEES"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   360
         Left            =   105
         TabIndex        =   24
         ToolTipText     =   "View All Employees "
         Top             =   240
         Width           =   4635
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboSup 
         Height          =   375
         Left            =   1680
         TabIndex        =   25
         ToolTipText     =   "Select Supervisor to view his Employees"
         Top             =   720
         Width           =   3060
         DataFieldList   =   "Column 0"
         MaxDropDownItems=   16
         AllowInput      =   0   'False
         AllowNull       =   0   'False
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         FieldSeparator  =   "!"
         RowHeight       =   609
         Columns.Count   =   2
         Columns(0).Width=   2117
         Columns(0).Caption=   "User ID"
         Columns(0).Name =   "Supervisor_Id"
         Columns(0).Alignment=   1
         Columns(0).CaptionAlignment=   1
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   6668
         Columns(1).Caption=   "User Name"
         Columns(1).Name =   "Supervisor_FName"
         Columns(1).Alignment=   1
         Columns(1).CaptionAlignment=   1
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         _ExtentX        =   5397
         _ExtentY        =   661
         _StockProps     =   93
         BackColor       =   -2147483643
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         DataFieldToDisplay=   "Column 0"
      End
      Begin VB.Label Label3 
         Caption         =   "&Supervisor ID"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   120
         TabIndex        =   26
         Top             =   720
         Width           =   1530
      End
   End
   Begin VB.CommandButton cmdReport 
      Caption         =   "&TIME SHEET"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   9855
      TabIndex        =   22
      ToolTipText     =   "View TimeSheet Report"
      Top             =   3600
      Width           =   2055
   End
   Begin VB.Frame Frame2 
      Caption         =   "Filter By"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   2535
      Left            =   5145
      TabIndex        =   19
      Top             =   1440
      Width           =   4455
      Begin VB.CommandButton cmdFilterBy 
         Caption         =   "&FILTER"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   360
         Left            =   120
         TabIndex        =   36
         ToolTipText     =   "Filter Employees based on Selected Criteria"
         Top             =   2040
         Width           =   4215
      End
      Begin VB.CheckBox chkFilterBy 
         Caption         =   "E&mployee"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Index           =   2
         Left            =   120
         TabIndex        =   35
         Top             =   720
         Width           =   1455
      End
      Begin VB.CheckBox chkFilterBy 
         Caption         =   "&Cate CD"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Index           =   0
         Left            =   120
         TabIndex        =   20
         Top             =   240
         Width           =   1215
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboLoc 
         Height          =   375
         Left            =   1755
         TabIndex        =   37
         ToolTipText     =   "Select Location Category ID"
         Top             =   240
         Width           =   2580
         DataFieldList   =   "Column 0"
         MaxDropDownItems=   16
         AllowInput      =   0   'False
         AllowNull       =   0   'False
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         FieldSeparator  =   "!"
         RowHeight       =   423
         Columns.Count   =   3
         Columns(0).Width=   2117
         Columns(0).Caption=   "Supervisor_Id"
         Columns(0).Name =   "Supervisor_Id"
         Columns(0).Alignment=   1
         Columns(0).CaptionAlignment=   1
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   3043
         Columns(1).Caption=   "Supervisor_FName"
         Columns(1).Name =   "Supervisor_FName"
         Columns(1).Alignment=   1
         Columns(1).CaptionAlignment=   1
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   3200
         Columns(2).Caption=   "Supervisor_LName"
         Columns(2).Name =   "Supervisor_LName"
         Columns(2).Alignment=   1
         Columns(2).CaptionAlignment=   1
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         _ExtentX        =   4551
         _ExtentY        =   661
         _StockProps     =   93
         BackColor       =   -2147483643
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Enabled         =   0   'False
         DataFieldToDisplay=   "Column 0"
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboComm 
         Height          =   375
         Left            =   1750
         TabIndex        =   38
         ToolTipText     =   "Select Commodity Code"
         Top             =   720
         Visible         =   0   'False
         Width           =   2580
         DataFieldList   =   "Column 0"
         MaxDropDownItems=   16
         AllowInput      =   0   'False
         AllowNull       =   0   'False
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         FieldSeparator  =   "!"
         RowHeight       =   609
         Columns.Count   =   3
         Columns(0).Width=   2117
         Columns(0).Caption=   "Supervisor_Id"
         Columns(0).Name =   "Supervisor_Id"
         Columns(0).Alignment=   1
         Columns(0).CaptionAlignment=   1
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   3043
         Columns(1).Caption=   "Supervisor_FName"
         Columns(1).Name =   "Supervisor_FName"
         Columns(1).Alignment=   1
         Columns(1).CaptionAlignment=   1
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   3200
         Columns(2).Caption=   "Supervisor_LName"
         Columns(2).Name =   "Supervisor_LName"
         Columns(2).Alignment=   1
         Columns(2).CaptionAlignment=   1
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         _ExtentX        =   4551
         _ExtentY        =   661
         _StockProps     =   93
         BackColor       =   -2147483643
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Enabled         =   0   'False
         DataFieldToDisplay=   "Column 0"
      End
      Begin VB.CheckBox chkFilterBy 
         Caption         =   "C&omm CD"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Index           =   1
         Left            =   120
         TabIndex        =   21
         Top             =   720
         Visible         =   0   'False
         Width           =   1455
      End
   End
   Begin VB.CommandButton cmdUpdate 
      Caption         =   "&SAVE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   12000
      Style           =   1  'Graphical
      TabIndex        =   18
      ToolTipText     =   "Save Changes"
      Top             =   3600
      Width           =   1275
   End
   Begin SSCalendarWidgets_A.SSDateCombo SSDateCombo1 
      Height          =   375
      Left            =   1800
      TabIndex        =   17
      Top             =   1080
      Width           =   2415
      _Version        =   65543
      _ExtentX        =   4260
      _ExtentY        =   661
      _StockProps     =   93
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty DropDownFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ShowCentury     =   -1  'True
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&DELETE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   1200
      Style           =   1  'Graphical
      TabIndex        =   16
      ToolTipText     =   "Delete Record from Database"
      Top             =   9960
      Width           =   2000
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnTime 
      Height          =   195
      Left            =   5280
      TabIndex        =   15
      Top             =   10680
      Width           =   1575
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   20
      ListWidth       =   2621
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ColumnHeaders   =   0   'False
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   2778
      _ExtentY        =   344
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      DataFieldToDisplay=   "Column 0"
   End
   Begin VB.TextBox txtSupervisorID 
      Enabled         =   0   'False
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
      Left            =   12375
      TabIndex        =   11
      ToolTipText     =   "Login Supervisor ID"
      Top             =   1080
      Width           =   2340
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
      Height          =   360
      Left            =   13455
      Style           =   1  'Graphical
      TabIndex        =   1
      ToolTipText     =   "Return Back"
      Top             =   3600
      Width           =   1275
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnService 
      Height          =   255
      Left            =   1560
      TabIndex        =   4
      Top             =   10680
      Width           =   1455
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      ListWidth       =   10795
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   2566
      _ExtentY        =   450
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnCommodity 
      Height          =   255
      Left            =   120
      TabIndex        =   3
      Top             =   10680
      Width           =   1950
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      ListWidth       =   10305
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      UseExactRowCount=   0   'False
      RowHeight       =   609
      ExtraHeight     =   185
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   3448
      _ExtentY        =   450
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.ListBox lstEmpName 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   2160
      Left            =   120
      TabIndex        =   0
      ToolTipText     =   "Select Employee to enter Hourly Detail"
      Top             =   1800
      Width           =   4815
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnVessel 
      Height          =   255
      Left            =   3000
      TabIndex        =   5
      Top             =   10680
      Width           =   1455
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      ListWidth       =   10795
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   2566
      _ExtentY        =   450
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnLocation 
      Height          =   255
      Left            =   120
      TabIndex        =   6
      Top             =   10800
      Width           =   1455
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      ListWidth       =   10795
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   2566
      _ExtentY        =   450
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnEarning 
      Height          =   255
      Left            =   1560
      TabIndex        =   7
      Top             =   10800
      Width           =   1455
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      ListWidth       =   10795
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   2566
      _ExtentY        =   450
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnEquipment 
      Height          =   255
      Left            =   3000
      TabIndex        =   8
      Top             =   10800
      Width           =   1455
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      ListWidth       =   10795
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   2566
      _ExtentY        =   450
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnCustomer 
      Height          =   255
      Left            =   4440
      TabIndex        =   9
      Top             =   10800
      Width           =   1455
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      ListWidth       =   10384
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   2566
      _ExtentY        =   450
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnSpec 
      Height          =   255
      Left            =   8520
      TabIndex        =   39
      Top             =   10680
      Width           =   1455
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      ListWidth       =   10795
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   2566
      _ExtentY        =   450
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnExactEnd 
      Height          =   195
      Left            =   6840
      TabIndex        =   40
      Top             =   10680
      Width           =   1575
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   20
      ListWidth       =   2621
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ColumnHeaders   =   0   'False
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   2778
      _ExtentY        =   344
      _StockProps     =   77
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      DataFieldToDisplay=   "Column 0"
   End
   Begin VB.Image Image1 
      Height          =   915
      Left            =   0
      Picture         =   "frmHourlyDet.frx":0000
      Stretch         =   -1  'True
      Top             =   0
      Width           =   975
   End
   Begin VB.Label Label5 
      Caption         =   "Description"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   9840
      TabIndex        =   44
      Top             =   3120
      Width           =   1455
   End
   Begin VB.Label Label4 
      Caption         =   "Total Hours :"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   10920
      TabIndex        =   14
      Top             =   9960
      Width           =   4455
   End
   Begin VB.Label Label7 
      Alignment       =   1  'Right Justify
      Caption         =   "Hire Date "
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   360
      TabIndex        =   13
      Top             =   1140
      Width           =   1050
   End
   Begin VB.Label Label2 
      Alignment       =   1  'Right Justify
      Caption         =   "Supervisor ID"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   10215
      TabIndex        =   12
      Top             =   1200
      Width           =   1890
   End
   Begin VB.Line Line2 
      X1              =   120
      X2              =   15960
      Y1              =   960
      Y2              =   960
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
      Width           =   6345
   End
   Begin VB.Label Label1 
      Caption         =   "Employee Details"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   360
      TabIndex        =   2
      Top             =   1530
      Width           =   2535
   End
End
Attribute VB_Name = "frmHourlyDet"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
    '5/2/2007 HD2759 Rudy: This form almost retired as of 5/2/2007, 2 users
Dim OraDatabase2 As Object
Dim iInSolomon As Boolean
Dim LRS As Object, EmpRS As Object ', BreakTime As Boolean
Dim ct As Integer, LastRow As Integer, RowIndex As Integer, FirstClick As Boolean, SavePrev As Boolean
Dim arrEmpID() As String, NoRec As Boolean, DiffSupervisor() As Boolean
Dim PrevValue As String, CurrValue As String, ErrFlag As Boolean, PrevIndex As Integer
Dim PrvEnd As String, PrvReg As String, PrvEquip As String, PrvSvc As String
Dim PrvComm As String, PrvLoc As String, PrvShip As String, PrvCust As String, PrvSpec As String, PrvLabor As String


'****************************************
'To Enable/Disable Combo based on Option Selected in Filter By
'****************************************
Private Sub chkFilterBy_Click(Index As Integer)
  If chkFilterBy(0).Value = 1 Then
    SSDBCboLoc.Enabled = True
    SSDBCboLoc.SetFocus
  ElseIf chkFilterBy(0).Value = 0 Then
    SSDBCboLoc.Enabled = False
    SSDBCboLoc.Text = ""
  End If
  If chkFilterBy(1).Value = 1 Then
    SSDBCboComm.Enabled = True
    SSDBCboComm.SetFocus
  ElseIf chkFilterBy(1).Value = 0 Then
    SSDBCboComm.Enabled = False
    SSDBCboComm.Text = ""
  End If
  If chkFilterBy(2).Value = 1 Then
    Frame3.Visible = True
    If optFilterEmp(0).Value = True Or optFilterEmp(1).Value = True Then
      chkFilterBy(0).Enabled = False
      chkFilterBy(1).Enabled = False
      SSDBCboLoc.Enabled = False
      SSDBCboLoc.Text = ""
      SSDBCboComm.Enabled = False
      SSDBCboComm.Text = ""
    End If
  ElseIf chkFilterBy(2).Value = 0 Then
    Frame3.Visible = False
    chkFilterBy(0).Enabled = True
    chkFilterBy(1).Enabled = True
  End If
End Sub

'****************************************
'To Clear All Data From Grid that are not saved
'****************************************
Private Sub cmdClearAll_Click()
  SSDBGrid1.RemoveAll
  OpenHourlyRS
  AddRec
  ColumnFormat
  Label4.Caption = "Total Hours : " + Trim(Str(FindTotalHrs))
  SetColWidth     'To Set Grid Column Width
  SetDefault      'To Set Blank Values to Prv Columns
End Sub

'****************************************
'To Delete a record from the Grid. NO Updatation in the Database
'****************************************
Private Sub cmdClearLine_Click()
    Dim sStartTime As String
    Dim sLoc As String
    Dim sComm As String
    Dim iDelRow As Integer
    
    
    sStartTime = SSDBGrid1.Columns(1).Value
    sLoc = SSDBGrid1.Columns(9).Value
    sComm = SSDBGrid1.Columns(8).Value
    
    If SSDBGrid1.rows > SSDBGrid1.row Then
       
        If UBound(DiffSupervisor) >= SSDBGrid1.row Then
            If DiffSupervisor(SSDBGrid1.row) = True Then
                MsgBox "Can't Delete Employee Data that belongs to Supervisor " + Trim(SSDBGrid1.Columns(12).Value), vbInformation, "Authorization Required"
            Else
                SSDBGrid1.CancelUpdate
                SSDBGrid1.RemoveItem (SSDBGrid1.row)
            End If
        Else
            SSDBGrid1.RemoveItem (SSDBGrid1.row)
        End If
    End If
    
    If SSDBGrid1.rows = 0 Then
        SSDBGrid1.AddItem "1", 0
        SSDBGrid1.row = 0
        SSDBGrid1.Columns(0).Value = 1
        SSDBGrid1.Columns(1).Value = sStartTime
        SSDBGrid1.Columns(9).Value = sLoc
        SSDBGrid1.Columns(8).Value = sComm
    End If
    
    SSDBGrid1.MoveLast
        'Set Previous Values
    PrvEnd = SSDBGrid1.Columns(2).Value
    PrvReg = SSDBGrid1.Columns(4).Value
    PrvSvc = SSDBGrid1.Columns(6).Value
    PrvEquip = SSDBGrid1.Columns(7).Value
    PrvCust = SSDBGrid1.Columns(11).Value
    PrvSpec = SSDBGrid1.Columns(13).Value
    PrvLabor = SSDBGrid1.Columns(16).Value
    
End Sub

'****************************************
'To Delete a record from the Grid and Update in the Database
'****************************************
Private Sub cmdDelete_Click()
    Dim iDelRecCnt As Integer
    Dim sqlStmt As String
    Dim DelConfirm As Integer
    
    If OnePM_Rule(CDate(SSDateCombo1.Text)) = False Then
     Exit Sub
    End If
  
    If MsgBox("Are you sure to delete this data ?", vbYesNo + vbQuestion, "Confirm Delete") = vbYes Then
        If UBound(DiffSupervisor) >= SSDBGrid1.row Then
            If DiffSupervisor(SSDBGrid1.row) = True Then
                MsgBox "Can't Delete Employee Data that belongs to Supervisor " + Trim(SSDBGrid1.Columns(12).Value), vbInformation, "Authorization Required"
            Else
                OraSession.BeginTrans               'Begin the Transaction
                sqlStmt = " Delete from hourly_detail where Hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and " _
                        & " Row_Number = " + Str(SSDBGrid1.Columns(0).Value) + " and employee_id = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'"
                iDelRecCnt = OraDatabase.ExecuteSQL(sqlStmt)
                If iDelRecCnt = 0 Then
                'No rec in DB - Give Message
                    MsgBox "Data not Found in Database. To Clear the data, Click on CLEAR button", vbInformation, "Delete Failure"
                    OraSession.Rollback
                    Exit Sub
                End If
                
                If OraDatabase.LastServerErr = 0 Then
                    OraSession.CommitTrans
                Else
                    OraSession.Rollback
                End If
            End If
        Else
            OraSession.BeginTrans               'Begin the Transaction
            sqlStmt = " Delete from hourly_detail where Hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and " _
                    & " Row_Number = " + Str(SSDBGrid1.Columns(0).Value) + " and employee_id = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'"
            
            iDelRecCnt = OraDatabase.ExecuteSQL(sqlStmt)
            If iDelRecCnt = 0 Then
                'No rec in DB - Give Message
                MsgBox "Data not Found in Database. To Clear the data, Click on CLEAR button", vbInformation, "Delete Failure"
                OraSession.Rollback
                Exit Sub
            End If
            If OraDatabase.LastServerErr = 0 Then
                OraSession.CommitTrans
            Else
                OraSession.Rollback
            End If
        End If
        'To refresh the Grid Contents after Deletion
        SSDBGrid1.RemoveAll
        OpenHourlyRS
        AddRec
        ColumnFormat
        SetColWidth     'To set Grid Column Width
        Label4.Caption = "Total Hours : " + Trim(Str(FindTotalHrs))
        SetDefault      'To set default values to Previous Data in Grid
    End If
End Sub

'****************************************
'To return back to previous Screen
'****************************************
Private Sub cmdExit_Click()
  frmHourlyDet.MousePointer = vbHourglass
  DoubleEntryReportDate     'Check for Overlapped Hours
  If LineRun = True Then LineRun = False
  frmHourlyDet.MousePointer = vbDefault
  Unload Me
End Sub

Private Sub cmdPreView_Click()
    Dim sFileName As String
    Dim iFile As Integer
    
    Dim sqlStmt As String
    Dim dsHD_Customer As Object
    Dim dsHD_LOCATION As Object
    Dim dsHD_SERVICE As Object
    Dim sLocation As String
    Dim sService As String
    Dim i As Integer
    Dim iFirst As Boolean
    Dim iSerCode As Integer
    Dim bNextPage As Boolean
    Dim iCustId As Integer
    Dim iLrNum As Long
    Dim iComm As Integer
    Dim iSer As Integer
    Dim iRecSer2 As Integer
    Dim iRecCust As Integer
    Dim iRecComm As Integer
    Dim iRecLrNum As Integer
    Dim iRecSer As Integer
    Dim iRecLoc As Integer
    Dim irec As Integer
    iFirst = True
    
    sFileName = "c:\TICKET.rtf"
                                
    iFile = FreeFile()
    Open sFileName For Output As #iFile
           
    sqlStmt = "SELECT DISTINCT CUSTOMER_ID,VESSEL_ID,COMMODITY_CODE FROM HOURLY_DETAIL WHERE " _
            & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
            & " AND USER_ID = '" & Trim$(UserID) & "'" _
            & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL ORDER BY CUSTOMER_ID,VESSEL_ID,COMMODITY_CODE"
        
    Set dsHD_Customer = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    For irec = 1 To dsHD_Customer.RecordCount
                   
        iCustId = dsHD_Customer.Fields("CUSTOMER_ID").Value
        iLrNum = dsHD_Customer.Fields("VESSEL_ID").Value
        iComm = dsHD_Customer.Fields("COMMODITY_CODE").Value
                            
               
        sqlStmt = " SELECT DISTINCT SUBSTR(SERVICE_CODE,1,3) SERVICE_CODE  FROM HOURLY_DETAIL WHERE " _
                & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                & " AND USER_ID = '" & Trim$(UserID) & "' AND CUSTOMER_ID='" & iCustId & "'" _
                & " AND COMMODITY_CODE='" & iComm & "' AND VESSEL_ID='" & iLrNum & "'" _
                & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL "
        Set dsHD_SERVICE = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        For iRecSer = 1 To dsHD_SERVICE.RecordCount
                    
            sLocation = ""
            sService = ""
            
            iFirst = False
            
            Print #iFile, ""
            Print #iFile, ""
            Print #iFile, Tab(37); "LABOR TICKET"
            Print #iFile, ""
            Print #iFile, ""
            Print #iFile, ""
            Print #iFile, Tab(5); "Date"; Tab(30); ":"; Tab(35); Format$(SSDateCombo1.Text, "MM/DD/YYYY")
            Print #iFile, ""
            Print #iFile, Tab(5); "Customer"; Tab(30); ":"; Tab(35); fnCustomer(iCustId)
            Print #iFile, ""
            Print #iFile, Tab(5); "Ship"; Tab(30); ":"; Tab(35); fnVESSEL(iLrNum)
            Print #iFile, ""
            Print #iFile, Tab(5); "Commodity"; Tab(30); ":"; Tab(35); fnCOMMODITY(iComm)
            Print #iFile, ""
            
            iSer = dsHD_SERVICE.Fields("SERVICE_CODE").Value
            
            sqlStmt = " SELECT DISTINCT LOCATION_ID FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "' AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "' AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND LOCATION_ID IS NOT NULL "
                
                                                
            Set dsHD_LOCATION = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            For iRecLoc = 1 To dsHD_LOCATION.RecordCount
                If sLocation = "" Then
                    sLocation = dsHD_LOCATION.Fields("LOCATION_ID").Value
                Else
                    sLocation = sLocation & "," & dsHD_LOCATION.Fields("LOCATION_ID").Value
                End If
                dsHD_LOCATION.MoveNext
            Next iRecLoc
            
            Print #iFile, Tab(5); "Area Worked"; Tab(30); ":"; Tab(35); sLocation
            Print #iFile, ""
            
            Print #iFile, Tab(5); "Supervisor"; Tab(30); ":"; Tab(35); fnEmp(Trim$(UserID)) & "       _____________________________________________"
            Print #iFile, Tab(90); "(signature)"
            Print #iFile,
            Print #iFile,
            Print #iFile,
                    
            Print #iFile, Tab(5); "--------------------------------------------------------------------------------------------" _
                                ; "--------------------------------------------------------------------------------------------"
            Print #iFile, Tab(10); "Employee Type"; Tab(46); "No."; Tab(55); "Total Hours"; Tab(70); "Labor Type"; Tab(100); "Time"
            Print #iFile, Tab(5); "--------------------------------------------------------------------------------------------" _
                                ; "--------------------------------------------------------------------------------------------"
            Print #iFile, ""
            
            
'             sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT,SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
'                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('CONT','SUPE','HARB')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL_SUPERVISOR = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL_SUPERVISOR.Fields("SUM_HOURS").Value) Then
'                Print #iFile, Tab(10); "SUPERVISOR";
'                For i = 1 To dsHOURLY_DETAIL_SUPERVISOR.RecordCount
'
'                    Print #iFile, Tab(47); dsHOURLY_DETAIL_SUPERVISOR.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL_SUPERVISOR.Fields("SUM_HOURS").Value; _
'                          Tab(70); dsHOURLY_DETAIL_SUPERVISOR.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL_SUPERVISOR.Fields("STIME").Value & " - " & _
'                          dsHOURLY_DETAIL_SUPERVISOR.Fields("ETIME").Value
'                    dsHOURLY_DETAIL_SUPERVISOR.MoveNext
'                Next i
'                Print #iFile,
'            End If
                    
            'FOR LIFT TRUCKS
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS ,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
'                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL " _
'                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID ='" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND LABOR_TYPE IN('DUMP','OPER','CRAN','LIFT','PAYL',',RAYG','POPR','WATR','CASO','L300','FOPR','TLIF','TYAR')" _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Print #iFile, Tab(10); "LIFT TRUCK OPERATORS";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Print #iFile, Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; _
'                                  Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Print #iFile, ""
'            End If
             
             '******************************
             'FOR XLIF
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS ,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
'                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL " _
'                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID ='" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND LABOR_TYPE IN('XLIF')" _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Print #iFile, Tab(10); "EXTRA LIFT TRUCK OPERATORS";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Print #iFile, Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; _
'                                  Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Print #iFile, ""
'            End If
             
                    
            'FOR CHECKERS
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
'                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM " _
'                    & " HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('CHEC','SWEE','CASC','BULL','TCHE','BFOP')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Print #iFile, Tab(10); "CHECKERS";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Print #iFile, Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Print #iFile, ""
'            End If

            'FOR LABOR
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL WHERE " _
'                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('CASL','LABO','FLAB')" _
'                    & " GROUP BY LABOR_TYPE, TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Print #iFile, Tab(10); "LABORERS";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Print #iFile, Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Print #iFile, ""
'            End If

            'FOR OFFICE CLERK
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
'                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('OFFI')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                 Print #iFile, Tab(10); "OFFICE CLERK";
'                 For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Print #iFile, Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(90); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Print #iFile, ""
'            End If
            
            'WEIGHTMASTER
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL " _
'                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('WEIG')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Print #iFile, Tab(10); "WEIGHMASTER";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Print #iFile, Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Print #iFile, ""
'            End If
            
            'SECURITY OFFICER
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('SECU', 'SECP')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                                        
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                 Print #iFile, Tab(10); "SECURITY OFFICER";
                 For i = 1 To dsHOURLY_DETAIL.RecordCount
                    
                    Print #iFile, Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Print #iFile, ""
            End If
            
            'MECHANIC
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS ,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
'                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('MECH')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                  Print #iFile, Tab(10); "MECHANIC";
'                  For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Print #iFile, Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL_TIME.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Print #iFile, ""
'            End If
                                
            'WELDER
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
'                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
'                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('WELD')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                 Print #iFile, Tab(10); "WELDER";
'                 For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Print #iFile, Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Print #iFile, ""
'            End If
                                
            'FLAT
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI')STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL " _
'                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('FLAT')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Print #iFile, Tab(10); "FLATED";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Print #iFile, Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Print #iFile, ""
'            End If
                                
            Print #iFile, Tab(5); "--------------------------------------------------------------------------------------------" _
                                ; "--------------------------------------------------------------------------------------------"
            
            Print #iFile, ""
            Print #iFile, ""
            
            'GET SERVICE DESCRIPTION
            sqlStmt = " SELECT DISTINCT SERVICE_CODE FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " ORDER BY SERVICE_CODE"
            Set dsHOURLY_DETAIL_SERVICE = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            Print #iFile, Tab(5); "Job Description"; Tab(30); ":";
            For iRecSer2 = 1 To dsHOURLY_DETAIL_SERVICE.RecordCount
                
                sqlStmt = " SELECT * FROM SERVICE WHERE SERVICE_CODE = " & dsHOURLY_DETAIL_SERVICE.Fields("SERVICE_CODE").Value
                Set dsService = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
                
                
                
                If Not IsNull(dsService.Fields("SERVICE_NAME").Value) Then
                    If sService = "" Then
                        Print #iFile, Tab(35); dsService.Fields("SERVICE_NAME").Value
                    Else
                        Print #iFile, Tab(35); dsService.Fields("SERVICE_NAME").Value
                    End If
                Else
                    If sService = "" Then
                       Print #iFile, Tab(35); dsService.Fields("SERVICE_CODE").Value
                    Else
                       Print #iFile, Tab(35); dsService.Fields("SERVICE_CODE").Value
                    End If
                End If
                dsHOURLY_DETAIL_SERVICE.MoveNext
            Next iRecSer2
            
            
            
            For i = 0 To 5
                Print #iFile, ""
            Next i
            
            Print #iFile, Tab(5); "Customer Approval : _______________________________"; Tab(80); " Date : _____________________"
                                
                                    
            dsHD_SERVICE.MoveNext
        Next iRecSer
        dsHD_Customer.MoveNext
                
    Next irec
    Close #iFile
    Shell ("c:Wordpad.exe c:\TICKET.rtf")
    
End Sub

'****************************************
'To View All Employees - No Filter
'****************************************
Private Sub cmdViewAll_Click()
    SSDBCboLoc.Enabled = False
    SSDBCboComm.Enabled = False
    SSDBCboLoc.Text = ""
    SSDBCboComm.Text = ""
    SSDBCboSup.Text = ""
    chkFilterBy(0).Value = 0
    chkFilterBy(1).Value = 0
    lstEmpName.Clear
    Call PopulateEmpName(2)
    SSDBGrid1.RemoveAll
    cmdUpdate.Enabled = False
    cmdClearLine.Enabled = False
    cmdClearAll.Enabled = False
    cmdDelete.Enabled = False
End Sub

'****************************************
'To Filter the List of Employees based on given Criteria
'****************************************
Private Sub cmdFilterBy_Click()
  If chkFilterBy(0).Value = 0 And chkFilterBy(1).Value = 0 And chkFilterBy(2).Value = 0 Then
    MsgBox "Please Select the Filter By Option", vbInformation, "Filter Clause Required"
    Exit Sub
  End If
  
  SSDBCboSup.Text = ""    'Supervisor Filter has to be removed
  
  If chkFilterBy(2).Value = 1 And optFilterEmp(0).Value = False And optFilterEmp(1).Value = False Then
    MsgBox "Please Select ID / Name option from Employee", vbInformation, "Filter Clause Required"
    Exit Sub
  End If
  
  If chkFilterBy(0).Value = 1 And chkFilterBy(1).Value = 0 And _
    chkFilterBy(0).Enabled = True And Frame3.Visible = False Then
    If Trim(SSDBCboLoc.Text) = vbNullString Then
      MsgBox "Please Select Category Code", vbInformation, "Data Required"
      Exit Sub
    Else                        'Only Location is Checked and has Value
      lstEmpName.Clear
      Call PopulateEmpName(0)
      SSDBGrid1.RemoveAll
      cmdUpdate.Enabled = False
      cmdClearLine.Enabled = False
      cmdClearAll.Enabled = False
      cmdDelete.Enabled = False
      Exit Sub
    End If
  End If
  
  If chkFilterBy(0).Value = 0 And chkFilterBy(1).Value = 1 And _
    chkFilterBy(1).Enabled = True And Frame3.Visible = False Then
    If Trim(SSDBCboComm.Text) = vbNullString Then
      MsgBox "Please Select Commodity Code", vbInformation, "Data Required"
      Exit Sub
    Else                        'Only Commodity is Checked and has Value
      lstEmpName.Clear
      Call PopulateEmpName(1)
      SSDBGrid1.RemoveAll
      cmdUpdate.Enabled = False
      cmdClearAll.Enabled = False
      cmdClearLine.Enabled = False
      cmdDelete.Enabled = False
      Exit Sub
    End If
  End If
  
  If chkFilterBy(0).Value = 1 And chkFilterBy(1).Value = 1 And _
    chkFilterBy(0).Enabled = True And chkFilterBy(0).Enabled = True And Frame3.Visible = False Then
    If Trim(SSDBCboLoc.Text) = vbNullString Then
      MsgBox "Please Select Category Code", vbInformation, "Data Required"
      Exit Sub
    ElseIf Trim(SSDBCboComm.Text) = vbNullString Then
      MsgBox "Please Select Commodity Code", vbInformation, "Data Required"
      Exit Sub
    Else                        'Location and Commodity are Checked and have Values
      lstEmpName.Clear
      Call PopulateEmpName(4)
      SSDBGrid1.RemoveAll
      cmdUpdate.Enabled = False
      cmdClearLine.Enabled = False
      cmdClearAll.Enabled = False
      cmdDelete.Enabled = False
      Exit Sub
    End If
  End If
  
  If chkFilterBy(0).Enabled = False And chkFilterBy(1).Enabled = False And Frame3.Visible = True And _
    optFilterEmp(0).Value = True And optFilterEmp(1).Value = False Then
    If Trim(SSDBCboEmp.Text) = vbNullString Then
      MsgBox "Please Select Employee ID", vbInformation, "Data Required"
      Exit Sub
    Else                        'Only Employee ID is Checked and has Value
      lstEmpName.Clear
      Call PopulateEmpName(5)
      SSDBGrid1.RemoveAll
      cmdUpdate.Enabled = False
      cmdClearAll.Enabled = False
      cmdClearLine.Enabled = False
      cmdDelete.Enabled = False
      Exit Sub
    End If
  End If
  
  If chkFilterBy(0).Enabled = False And chkFilterBy(1).Enabled = False And Frame3.Visible = True And _
    optFilterEmp(0).Value = False And optFilterEmp(1).Value = True Then
    If Trim(ssdbcboName.Text) = vbNullString Then
      MsgBox "Please Select Employee Name", vbInformation, "Data Required"
      Exit Sub
    Else                        'Only Employee Name is Checked and has Value
      lstEmpName.Clear
      Call PopulateEmpName(6)
      SSDBGrid1.RemoveAll
      cmdUpdate.Enabled = False
      cmdClearAll.Enabled = False
      cmdClearLine.Enabled = False
      cmdDelete.Enabled = False
      Exit Sub
    End If
  End If
End Sub

'****************************************
'To Show the Time Sheet Report
'****************************************
Private Sub cmdReport_Click()
  If LineRun = True Then
    Dim myLineDE As New DE_Line
    Dim myLineDR As New DR_Line
    myLineDE.rsHourlyDetail_Grouping.Source = "SHAPE {SELECT a.EMPLOYEE_ID, b.EMPLOYEE_NAME, a.DURATION, a.EARNING_TYPE_ID, a.SERVICE_CODE, a.COMMODITY_CODE, a.START_TIME, a.EXACT_END, a.HIRE_DATE, a.EXACT_END - a.START_TIME DIFF FROM HOURLY_DETAIL a, EMPLOYEE b Where a.EMPLOYEE_ID = b.EMPLOYEE_ID and a.HIRE_DATE=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and a.SERVICE_CODE IN ('1200', '1221','1222','1223') AND Upper(a.USER_ID) = '" + UCase(UserID) + "' ORDER BY a.EMPLOYEE_ID} AS HourlyDetail COMPUTE HourlyDetail BY 'START_TIME','HIRE_DATE','EXACT_END'"
    myLineDE.rsHourlyDetail_Grouping.Open
    myLineDR.Refresh
    myLineDR.Show
    myLineDE.rsHourlyDetail_Grouping.Close
  Else
    Dim myDE As New DE_LCS
    Dim myDR As New DR_TimeSheet
    myDE.rsHourlyDetail_Grouping.Source = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name, b.* From Employee a, Hourly_Detail b, LCS_USER c Where b.Employee_id = a.Employee_Id and upper(b.Employee_Id) Not Like 'T%' and b.User_ID = '" + UserID + "' and b.User_ID = c.User_ID and b.Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(b.EARNING_TYPE_ID) <> 'LU' Order by a.Employee_ID, Row_Number}  AS HourlyDetail COMPUTE HourlyDetail BY 'Employee_Type_ID','Employee_Sub_Type_Id','Employee_Id','Employee_Name'"
    myDE.rsHourlyDetail_Grouping.Open
    myDR.Refresh
    myDR.Show
    myDE.rsHourlyDetail_Grouping.Close
  End If
End Sub

'****************************************
'To Save the Record(s) from the grid to DB
Private Sub cmdUpdate_Click()
  Dim myDelSQL As String, i As Integer, j As Integer
  Dim mySQL As String, myFieldData() As String, myField As String
  Dim startTime As Date
  Dim hireTime As Date
  
  If OnePM_Rule(CDate(SSDateCombo1.Text)) = False Then
    '2853 3/29/2007 Rudy: need msgbox, exiting sub it's not saving
    Exit Sub
  End If
  Label4.Caption = "Total Hours : " + Trim(Str(FindTotalHrs))
    
  '2853 3/29/2007 Rudy: validations, goes thru just the first row of grid
  SSDBGrid1.row = 0
  If UCase(Trim(SSDBGrid1.Columns(4).Value)) = "LU" Then
    'Don't Check for Null Data
  Else
    If LineRun = True Then
      If Trim(SSDBGrid1.Columns(6).Value) = "" Then
        If SavePrev = False Then
          'MsgBox "Please Enter Service Code and Click on SAVE to Update the Database.", vbInformation, "Up dation Failure"
          MsgBox "Please Enter Service Code and Click on SAVE to Update the Database.", vbInformation, "Insufficient Data"
          SSDBGrid1.SelectByCell = True
          SSDBGrid1.SetFocus
          Exit Sub
        Else
          '2853 3/29/2007 Rudy: need msgbox, exiting sub it's not saving
          Exit Sub
        End If
      ElseIf Trim(SSDBGrid1.Columns(15).Value) = "" Then
        'MsgBox "Please Enter Exact End Time and Click on SAVE to Update the Database.", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Exact End Time and Click on SAVE to Update the Database.", vbInformation, "Insufficient Data"
        SSDBGrid1.SelectByCell = True
        SSDBGrid1.SetFocus
        Exit Sub
      End If
    Else
      If Trim(SSDBGrid1.Columns(6).Value) = "" Then
        If SavePrev = False Then
          'MsgBox "Please Enter Service Code and Click on SAVE to Update the Database.", vbInformation, "Up dation Failure"
          MsgBox "Please Enter Service Code and Click on SAVE to Update the Database.", vbInformation, "Insufficient Data"
          SSDBGrid1.SelectByCell = True
          SSDBGrid1.SetFocus
          Exit Sub
        Else
          '2853 3/29/2007 Rudy: need msgbox, exiting sub it's not saving
          Exit Sub
        End If
      End If
    End If
  End If
  
  
  '2853 3/29/2007 Rudy: validations, now goes thru the rows of the grid, one at a time
  SSDBGrid1.MoveFirst
  For i = 0 To SSDBGrid1.rows - 1
    If Trim(SSDBGrid1.Columns(0).Value) = "" Or Trim(SSDBGrid1.Columns(12).Value) = "" Then
      If Trim(SSDBGrid1.Columns(0).Value) = "" Then SSDBGrid1.Columns(0).Value = i + 1
      If Trim(SSDBGrid1.Columns(12).Value) = "" Then SSDBGrid1.Columns(12).Value = UserID
    End If
    
    
    If Trim(SSDBGrid1.Columns(1).Value) = "" Then
      MsgBox "Please Enter Start Time", vbInformation, "Start Time"
      SSDBGrid1.SelectByCell = True
      Exit Sub
    ElseIf Not IsDate(Trim(SSDBGrid1.Columns(1).Value)) Then
      MsgBox "Start Time format is not correct, please enter again!", vbInformation, "Start Time"
      SSDBGrid1.SelectByCell = True
      Exit Sub
    ElseIf Not IsDate(hireStartTime(arrEmpID(lstEmpName.ListIndex + 1))) Then
      MsgBox "Hiring Start time is not available.", vbInformation, "Start Time"
      SSDBGrid1.SelectByCell = True
      Exit Sub
    
    ' Commented out, May 31 2007, Adam Walter.  Security (I.E. Pat) wants to be able to enter times earlier than an
    ' Employee's "hire time" so that employees who work overnight show up on the same day's timesheet.
    ' As only Security has access to this VB form, this commenting shouldn't harm other usage.
'    Else
'      startTime = Format(Trim(SSDBGrid1.Columns(1).Value), "HH:NNAM/PM")
'      hireTime = Format(hireStartTime(arrEmpID(lstEmpName.ListIndex + 1)), "HH:NNAM/PM")
'      If startTime < hireTime Then
'        'MsgBox "Start Time can't early than Hire Start Time, please enter again!", vbInformation, "Start Time"
'        MsgBox "Start Time can't be earlier than Hire Start Time, please enter again!", vbInformation, "Start Time" '2853 3/29/2007 Rudy: grammar
'        SSDBGrid1.SelectByCell = True
'        Exit Sub
'      End If
    End If
        
    If Trim(SSDBGrid1.Columns(2).Value) = "" Then
      MsgBox "Please Enter End Time", vbInformation, "End Time"
      SSDBGrid1.SelectByCell = True
      Exit Sub
    End If
    
    
    If Trim(SSDBGrid1.Columns(8).Value) = "" Then
      MsgBox "Please Enter Commodity Code", vbInformation, "Commodity Code"
      SSDBGrid1.SelectByCell = True
      Exit Sub
    End If
    If Trim(SSDBGrid1.Columns(6).Value) = "" Then
      MsgBox "Please Enter Service ", vbInformation, "Service"
      SSDBGrid1.SelectByCell = True
      Exit Sub
    End If
    
    '5/2/2007 HD2759 Rudy:
    If Trim(SSDBGrid1.Columns(8).Value) = "0" Or Trim(SSDBGrid1.Columns(8).Value) = "0000" Then
'      Select Case Left(Trim(SSDBGrid1.Columns(6).Value), 1)'per Don's e-mail of 5/2/2007, can be virtually any service code not just 6XXX
'      Case "6"
        'need to check DB, if not permissable give message, exit func
        If CheckSvcCommAllowZero(Trim(SSDBGrid1.Columns(6).Value)) = True Then
          'Msg Box "Service code " & SSDBGrid1.Columns(6).Value & " require a non zero Commodity Code!", vbInformation, "Invalid Commodity Code"
          MsgBox "Commodity Code zero is not accepted with Service code " & SSDBGrid1.Columns(6).Value & " !", vbInformation, "Invalid Commodity Code"     'Don D. verbiage
          SSDBGrid1.SelectByCell = True
          Exit Sub
        End If
'      End Select
    End If

    
    If Trim(SSDBGrid1.Columns(11).Value) = "" Then
      MsgBox "Please Enter Customer Code ", vbInformation, "Customer Code"
      SSDBGrid1.SelectByCell = True
      Exit Sub
    End If
    If Trim(SSDBGrid1.Columns(10).Value) = "" Then
      MsgBox "Please Enter Ship Number ", vbInformation, "Ship Number"
      SSDBGrid1.SelectByCell = True
      Exit Sub
    End If
    If Trim(SSDBGrid1.Columns(9).Value) = "" Then
      MsgBox "Please Enter Location", vbInformation, "Location"
      SSDBGrid1.SelectByCell = True
      Exit Sub
    End If
    
    '2853 3/29/2007 Rudy: perhaps this is a proper validation
    If SSDBGrid1.Columns(5).Value = -1 And Trim(SSDBGrid1.Columns(16).Value) = "" Then
       'Msg Box "Please Enter Labor Type ", vbInformation, "Up dation Failure"
       MsgBox "Please Enter Labor Type ", vbInformation, "Insufficient Data"
       SSDBGrid1.SelectByCell = True
       Exit Sub
    End If
    
'    '2853 3/29/2007 Rudy: regardless, add this as well
'    If Trim(SSDBGrid1.Columns(4).Value) = "" Or Len(Trim(SSDBGrid1.Columns(4).Value)) = 0 Then
'       MsgBox "Please Enter Labor Type ", vbInformation, "Validation Failure"
'       SSDBGrid1.SelectByCell = True
'       Exit Sub
'    End If
    
    'Earning_Type (Double Time) DT can be selected only when the Duration is 1 Hour
    If UCase(Trim(SSDBGrid1.Columns(4).Value)) = "DT" And SSDBGrid1.Columns(3).Value > 1 Then
       MsgBox "Double Time Can't be More than an Hour", vbInformation, "Validation Failure"
       SSDBGrid1.Col = 4
       Exit Sub
    End If
    
'    '2853 3/29/2007 Rudy: add this as well, always seems that there is a value in col 3 - probably not needed
'    If Len(Trim(SSDBGrid1.Columns(4).Value)) > 0 And Len(Trim(SSDBGrid1.Columns(3).Value)) = 0 Then
'       MsgBox "Please Enter Hours, as there is a Labor Type ", vbInformation, "Validation Failure"
'       SSDBGrid1.SelectByCell = True
'       Exit Sub
'    End If
    
    SSDBGrid1.MoveNext
  Next i
    
  '2853 3/29/2007 Rudy: validations completed above, now start saving
    
'    If SavePrev = True Then
'        myDelSQL = "Delete from Hourly_Detail where Employee_ID = '" + arrEmpID(PrevIndex + 1) + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') AND TO_BNI IS NULL"
'    Else
'        myDelSQL = "Delete from Hourly_Detail where Employee_ID = '" + arrEmpID(lst EmpName.ListIndex + 1) + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') AND TO_BNI IS NULL"
'    End If
'    OraDatabase.ExecuteSQL myDelSQL
  ReDim myFieldData(SSDBGrid1.rows) As String
  
  For i = 0 To SSDBGrid1.rows - 1
  
    SSDBGrid1.row = i
    
    If (Trim(SSDBGrid1.Columns(6).Value) = "" And LineRun = False) Or (LineRun = True And SSDBGrid1.Columns(15).Value = "" And SSDBGrid1.Columns(6).Value = "") Then
      If SavePrev = True Then
        Call UpdateRowNo(arrEmpID(PrevIndex + 1))
      Else
        Call UpdateRowNo(arrEmpID(lstEmpName.ListIndex + 1))
      End If
      SavePrev = False
      cmdUpdate.Enabled = False   'Already Saved
      lstEmpName_Click
      Exit Sub      'No More rows to Process
    End If

    If UCase(Trim(SSDBGrid1.Columns(4).Value)) = "LU" Then
    'Don't Check for Null Data
    Else
      If (Trim(SSDBGrid1.Columns(6).Value) = "" And LineRun = False) Or (LineRun = True And SSDBGrid1.Columns(15).Value = "" And SSDBGrid1.Columns(6).Value = "") Then
        Dim ConfirmSave As Integer
        'ConfirmSave = Msg Box("First " + Trim(Str(SSDBGrid1.row)) + " Rows are Saved to the Database." + Chr(13) + "Do you wish to Fill in this Incomplete Data Entry and Save this?", vbInformation + vbYesNo, "Confirm Up dation")
        ConfirmSave = MsgBox("First " + Trim(Str(SSDBGrid1.row)) + " Rows are Saved to the Database." + Chr(13) + "Do you wish to Fill in this Incomplete Data Entry and Save this?", vbInformation + vbYesNo, "Confirm Incomplete Update")
        If ConfirmSave = vbYes Then
          SSDBGrid1.SelectByCell = True
          SSDBGrid1.SetFocus
          Exit Sub
        Else
          Call UpdateRowNo(arrEmpID(lstEmpName.ListIndex + 1))
          lstEmpName_Click
          Exit Sub      'No More rows to Process
        End If
      End If
    End If
    
    UpdateData SSDBGrid1.Columns(17).Value
  Next
  
  cmdClearAll_Click
    
End Sub

'****************************************
'To Update the Data to DB
'****************************************
Private Sub UpdateData(sRowID As String)
    Dim mySQL As String, HourlyRS As Object, myChkRS As Object, ExpFlag As String
    Dim dStartTime As Date
    Dim dEndTime As Date
    Dim sAddModify As String
    Dim sEmpId As String
    Dim isUpdate As Boolean
    
    sAddModify = ""
    dStartTime = Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(1).Value, "hh:nnAM/PM")
    dEndTime = Format(SSDateCombo1.Text, "MM/DD/YYyy") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM")
    
    'OraSession.CommitTrans
    OraSession.BeginTrans
    
    If Trim(sRowID) = "" Then
        mySQL = "SELECT * FROM HOURLY_DETAIL"
        sAddModify = "A"
        Set HourlyRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
        HourlyRS.AddNew
        
    Else
        
        mySQL = " Select * from hourly_detail where Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and " _
              & " ROWID = '" & Trim(sRowID) & "'"
    
        Set HourlyRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
        If Not IsNull(HourlyRS.Fields("TO_BNI").Value) Then
            OraSession.CommitTrans
            Exit Sub
        End If
        
        'check if user is the supervisor, if not, can not update ....rw update 12/5/2001
        If HourlyRS.Fields("USER_ID").Value <> UserID Then
            OraSession.CommitTrans
            Exit Sub
        End If
        
        'check if data was changed. if not, exit
        isUpdate = False
        If Format(HourlyRS.Fields("Start_Time").Value, "mm/dd/yyyy hh:nnAM/PM") <> Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(1).Value, "hh:nnAM/PM") Then
            isUpdate = True
        End If
        
        If Format(HourlyRS.Fields("End_Time").Value, "mm/dd/yyyy hh:nnAM/PM") <> Format(SSDateCombo1.Text, "MM/DD/YYyy") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM") Then
            isUpdate = True
        End If
               
        If IsNull(HourlyRS.Fields("Earning_Type_Id").Value) Then
            If Trim(SSDBGrid1.Columns(4).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("Earning_Type_Id").Value <> SSDBGrid1.Columns(4).Value Then
            isUpdate = True
        End If
        
        If HourlyRS.Fields("Billing_Flag").Value <> "Y" And SSDBGrid1.Columns(5).Value = True Then
            isUpdate = True
        End If
        
        If HourlyRS.Fields("Billing_Flag").Value <> "N" And SSDBGrid1.Columns(5).Value = False Then
            isUpdate = True
        End If
                
        If IsNull(HourlyRS.Fields("Service_Code").Value) Then
            If Trim(SSDBGrid1.Columns(6).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("Service_Code").Value <> SSDBGrid1.Columns(6).Value Then
            isUpdate = True
        End If
        
        If IsNull(HourlyRS.Fields("Equipment_Id").Value) Then
            If Trim(SSDBGrid1.Columns(7).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("Equipment_Id").Value <> SSDBGrid1.Columns(7).Value Then
            isUpdate = True
        End If
        
        If IsNull(HourlyRS.Fields("Commodity_Code").Value) Then
            If Trim(SSDBGrid1.Columns(8).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("Commodity_Code").Value <> SSDBGrid1.Columns(8).Value Then
            isUpdate = True
        End If
        
        If IsNull(HourlyRS.Fields("Location_Id").Value) Then
            If Trim(SSDBGrid1.Columns(9).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("Location_Id").Value <> SSDBGrid1.Columns(9).Value Then
            isUpdate = True
        End If
        
        If IsNull(HourlyRS.Fields("Vessel_Id").Value) Then
            If Trim(SSDBGrid1.Columns(10).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("Vessel_Id").Value <> SSDBGrid1.Columns(10).Value Then
            isUpdate = True
        End If
        
        If IsNull(HourlyRS.Fields("Customer_ID").Value) Then
            If Trim(SSDBGrid1.Columns(11).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("Customer_ID").Value <> SSDBGrid1.Columns(11).Value Then
            isUpdate = True
        End If
        
        If IsNull(HourlyRS.Fields("User_ID").Value) Then
            If Trim(SSDBGrid1.Columns(12).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("User_ID").Value <> SSDBGrid1.Columns(12).Value Then
            isUpdate = True
        End If
        
        If IsNull(HourlyRS.Fields("Special_Code").Value) Then
            If Trim(SSDBGrid1.Columns(13).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("Special_Code").Value <> SSDBGrid1.Columns(13).Value Then
            isUpdate = True
        End If
        
        If IsNull(HourlyRS.Fields("Remark").Value) Then
            If Trim(SSDBGrid1.Columns(14).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("Remark").Value <> SSDBGrid1.Columns(14).Value Then
            isUpdate = True
        End If
        
        If IsNull(HourlyRS.Fields("Exact_End").Value) Then
            If Trim(SSDBGrid1.Columns(15).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("Exact_End").Value = Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(15).Value, "hh:nnAM/PM") Then
            isUpdate = True
        End If
        
        If IsNull(HourlyRS.Fields("LABOR_TYPE").Value) Then
            If Trim(SSDBGrid1.Columns(16).Value) <> "" Then
                isUpdate = True
            End If
        ElseIf HourlyRS.Fields("LABOR_TYPE").Value <> SSDBGrid1.Columns(16).Value Then
            isUpdate = True
        End If
        
        If isUpdate = False Then
            OraSession.CommitTrans
            Exit Sub
        End If
        
        
        sAddModify = "M"
        HourlyRS.Edit
    
    End If
    
    
    If SavePrev = True Then
        Call CheckRowNo(SSDBGrid1.Columns(0).Value, arrEmpID(PrevIndex + 1))
        sEmpId = arrEmpID(PrevIndex + 1)
    Else
        Call CheckRowNo(SSDBGrid1.Columns(0).Value, arrEmpID(lstEmpName.ListIndex + 1))
        sEmpId = arrEmpID(lstEmpName.ListIndex + 1)
    End If
    
    If LastRow = 0 Then
        HourlyRS.Fields("Row_Number").Value = 1
    Else
        'Change Row Number and update in Order
        HourlyRS.Fields("Row_Number").Value = Str(LastRow)
    End If
    
    If UCase(Trim(SSDBGrid1.Columns(4).Value)) = "LU" Then
        'Save only Hire_Date, Employee_ID, Start_Time, End_Time, Duration, Earning_Type_ID, User_ID
        HourlyRS.Fields("Hire_Date").Value = SSDateCombo1.Text
        HourlyRS.Fields("Employee_Id").Value = arrEmpID(lstEmpName.ListIndex + 1)
        HourlyRS.Fields("Start_Time").Value = Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(1).Value, "hh:nnAM/PM")
        HourlyRS.Fields("End_Time").Value = Format(SSDateCombo1.Text, "MM/DD/YYyy") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM")
        ' Added 03/22/2000 by Bruce LeBrun for time of entry date/time stamp
        If sAddModify = "A" Then
            HourlyRS.Fields("Time_Entry").Value = Format(Now, "MM/DD/YYyy") & " " & Format(Now, "hh:nnAM/PM")
        End If
        HourlyRS.Fields("Time_Update").Value = Format(Now, "MM/DD/YYyy") & " " & Format(Now, "hh:nnAM/PM")
        If Trim(SSDBGrid1.Columns(3).Value) = vbNullString Or IsNull(SSDBGrid1.Columns(3).Value) Then
            SSDBGrid1.Columns(3).Value = FindDuration(SSDBGrid1.Columns(1).Value, SSDBGrid1.Columns(2).Value)
        End If
    
        HourlyRS.Fields("Duration").Value = SSDBGrid1.Columns(3).Value
        HourlyRS.Fields("Earning_Type_Id").Value = SSDBGrid1.Columns(4).Value
        HourlyRS.Fields("User_ID").Value = SSDBGrid1.Columns(12).Value
        HourlyRS.Fields("LABOR_TYPE").Value = SSDBGrid1.Columns(16).Value
    
        'All the rest blank and zero values
        HourlyRS.Fields("Billing_Flag").Value = "N"
        If Trim(SSDBGrid1.Columns(6).Value) = vbNullString Or IsNull(SSDBGrid1.Columns(6).Value) Then
            HourlyRS.Fields("Service_Code").Value = 0
        Else
            HourlyRS.Fields("Service_Code").Value = SSDBGrid1.Columns(6).Value
        End If
        HourlyRS.Fields("Equipment_Id").Value = 0
        HourlyRS.Fields("Commodity_Code").Value = 0
        HourlyRS.Fields("Location_Id").Value = ""
        HourlyRS.Fields("Vessel_Id").Value = 0
        HourlyRS.Fields("Customer_ID").Value = 0
    
        ExpFlag = "N"

        HourlyRS.Fields("Exception").Value = ExpFlag
        
        'HourlyRS.Fields("Remark").Value = SSDBGrid1.Columns(14).Value
        HourlyRS.Fields("Remark").Value = Replace(SSDBGrid1.Columns(14).Value, "'", "`")     '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007
        
        If IsNull(SSDBGrid1.Columns(15).Value) Or Trim(SSDBGrid1.Columns(15).Value) = vbNullString Then
            'Do Nothing
        Else
            HourlyRS.Fields("Exact_End").Value = Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(15).Value, "hh:nnAM/PM")
        End If
        HourlyRS.Update
    Else
        HourlyRS.Fields("Hire_Date").Value = SSDateCombo1.Text
        If SavePrev = True Then
            HourlyRS.Fields("Employee_Id").Value = arrEmpID(PrevIndex + 1)
        Else
            HourlyRS.Fields("Employee_Id").Value = arrEmpID(lstEmpName.ListIndex + 1)
        End If
        If IsNull(SSDBGrid1.Columns(1).Value) Or Trim(SSDBGrid1.Columns(1).Value) = vbNullString Or Trim(SSDBGrid1.Columns(1).Value) = "" Then
            'Do Nothing
        Else
            HourlyRS.Fields("Start_Time").Value = Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(1).Value, "hh:nnAM/PM")
        End If
        
        If IsNull(SSDBGrid1.Columns(2).Value) Or Trim(SSDBGrid1.Columns(2).Value) = vbNullString Or Trim(SSDBGrid1.Columns(2).Value) = "" Then
            If Trim(SSDBGrid1.Columns(3).Value) = vbNullString Or IsNull(SSDBGrid1.Columns(3).Value) Or Trim(SSDBGrid1.Columns(3).Value) = "" Then
                'Do Nothing
            Else
                If IsNull(SSDBGrid1.Columns(1).Value) Or Trim(SSDBGrid1.Columns(1).Value) = vbNullString Or Trim(SSDBGrid1.Columns(1).Value) = "" Then
                    'Do Nothing
                    HourlyRS.Fields("Duration").Value = SSDBGrid1.Columns(3).Value
                Else
                    Duration    'To find End Time
                    HourlyRS.Fields("End_Time").Value = Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM")
                    HourlyRS.Fields("Duration").Value = SSDBGrid1.Columns(3).Value
                End If
            End If
        Else
            HourlyRS.Fields("End_Time").Value = Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM")
            If Trim(SSDBGrid1.Columns(3).Value) = vbNullString Or IsNull(SSDBGrid1.Columns(3).Value) Or Trim(SSDBGrid1.Columns(3).Value) = "" Then
                If IsNull(SSDBGrid1.Columns(1).Value) Or Trim(SSDBGrid1.Columns(1).Value) = vbNullString Or Trim(SSDBGrid1.Columns(1).Value) = "" Then
                    'Do Nothing
                Else
                    SSDBGrid1.Columns(3).Value = FindDuration(SSDBGrid1.Columns(1).Value, SSDBGrid1.Columns(2).Value)
                    HourlyRS.Fields("Duration").Value = SSDBGrid1.Columns(3).Value
                End If
            Else
                HourlyRS.Fields("Duration").Value = SSDBGrid1.Columns(3).Value
            End If
        End If
        
        'If Earning Type is missed, but duration is filled, then put earning type as REG
        If IsNull(SSDBGrid1.Columns(4).Value) Or Trim(SSDBGrid1.Columns(4).Value) = vbNullString Then
            If IsNull(SSDBGrid1.Columns(3).Value) Or Trim(SSDBGrid1.Columns(3).Value) = vbNullString Then
                'do Nothing
            Else
                HourlyRS.Fields("Earning_Type_Id").Value = "REG"
            End If
        Else
            HourlyRS.Fields("Earning_Type_Id").Value = SSDBGrid1.Columns(4).Value
        End If
        
        If SSDBGrid1.Columns(5).Value = True Then
            HourlyRS.Fields("Billing_Flag").Value = "Y"
        Else
            HourlyRS.Fields("Billing_Flag").Value = "N"
        End If
        
        HourlyRS.Fields("Service_Code").Value = SSDBGrid1.Columns(6).Value
        HourlyRS.Fields("Equipment_Id").Value = SSDBGrid1.Columns(7).Value
        HourlyRS.Fields("Commodity_Code").Value = SSDBGrid1.Columns(8).Value
        HourlyRS.Fields("Location_Id").Value = SSDBGrid1.Columns(9).Value
        HourlyRS.Fields("Vessel_Id").Value = SSDBGrid1.Columns(10).Value
        HourlyRS.Fields("Customer_ID").Value = SSDBGrid1.Columns(11).Value
        HourlyRS.Fields("User_ID").Value = SSDBGrid1.Columns(12).Value
        HourlyRS.Fields("Special_Code").Value = SSDBGrid1.Columns(13).Value
        HourlyRS.Fields("LABOR_TYPE").Value = SSDBGrid1.Columns(16).Value
        
        ExpFlag = "N"
 
        HourlyRS.Fields("Exception").Value = ExpFlag
        
        'HourlyRS.Fields("Remark").Value = SSDBGrid1.Columns(14).Value
        HourlyRS.Fields("Remark").Value = Replace(SSDBGrid1.Columns(14).Value, "'", "`")     '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007
        
        If IsNull(SSDBGrid1.Columns(15).Value) Or Trim(SSDBGrid1.Columns(15).Value) = vbNullString Then
            'Do Nothing
        Else
            HourlyRS.Fields("Exact_End").Value = Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(15).Value, "hh:nnAM/PM")
        End If
        
        HourlyRS.Fields("Time_Update").Value = Format(Now, "MM/DD/YYyy") & " " & Format(Now, "hh:nnAM/PM")
        If sAddModify = "A" Then
            HourlyRS.Fields("Time_Entry").Value = Format(Now, "MM/DD/YYyy") & " " & Format(Now, "hh:nnAM/PM")
        End If
        HourlyRS.Update
        
  End If
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
    Else
        OraSession.Rollback
    End If
    
    Call UpdateRowNum(sEmpId)
    
End Sub

Private Sub Command1_Click()
    Dim sqlStmt As String
    Dim dsHD_Customer As Object
    Dim dsHD_LOCATION As Object
    Dim dsHD_SERVICE As Object
    
    Dim dsLABOR_TICKET As Object
    Dim iLTnum As Long
    
    Dim sLocation As String
    Dim sService As String
    Dim i As Integer
    Dim iFirst As Boolean
    Dim iSerCode As Integer
    Dim bNextPage As Boolean
    Dim iCustId As Integer
    Dim iLrNum As Long
    Dim iComm As Integer
    Dim iSer As Integer
    Dim iRecSer2 As Integer
    Dim iRecCust As Integer
    Dim iRecComm As Integer
    Dim iRecLrNum As Integer
    Dim iRecSer As Integer
    Dim iRecLoc As Integer
    Dim irec As Integer
    iFirst = True
   
    
    If MsgBox("Did you save any unsaved records ? " & vbCrLf & " If not, please save them before printing", vbQuestion + vbYesNo, "PRINT") = vbNo Then
        Exit Sub
    End If
    
           
    sqlStmt = "SELECT DISTINCT CUSTOMER_ID,VESSEL_ID,COMMODITY_CODE FROM HOURLY_DETAIL WHERE " _
            & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
            & " AND USER_ID = '" & Trim$(UserID) & "'" _
            & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL ORDER BY CUSTOMER_ID,VESSEL_ID,COMMODITY_CODE"
        
    Set dsHD_Customer = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    For irec = 1 To dsHD_Customer.RecordCount
                   
        iCustId = dsHD_Customer.Fields("CUSTOMER_ID").Value
        iLrNum = dsHD_Customer.Fields("VESSEL_ID").Value
        iComm = dsHD_Customer.Fields("COMMODITY_CODE").Value
                            
               
        sqlStmt = " SELECT DISTINCT SUBSTR(SERVICE_CODE,1,3) SERVICE_CODE  FROM HOURLY_DETAIL WHERE " _
                & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                & " AND USER_ID = '" & Trim$(UserID) & "' AND CUSTOMER_ID='" & iCustId & "'" _
                & " AND COMMODITY_CODE='" & iComm & "' AND VESSEL_ID='" & iLrNum & "'" _
                & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL "
        Set dsHD_SERVICE = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        For iRecSer = 1 To dsHD_SERVICE.RecordCount
                    
            sLocation = ""
            sService = ""
            
            If iFirst = False Then
                Printer.NewPage
            End If
            iFirst = False
            Printer.FontSize = 14
            Printer.Print ""
            Printer.Print ""
            Printer.FontBold = True
            Printer.Print Tab(37); "LABOR TICKET"
            Printer.FontBold = False
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.FontSize = 11
            Printer.Print Tab(5); "Date"; Tab(30); ":"; Tab(35); Format$(SSDateCombo1.Text, "MM/DD/YYYY")
            Printer.Print ""
            Printer.Print Tab(5); "Customer"; Tab(30); ":"; Tab(35); fnCustomer(iCustId)
            Printer.Print ""
            Printer.Print Tab(5); "Ship"; Tab(30); ":"; Tab(35); fnVESSEL(iLrNum)
            Printer.Print ""
            Printer.Print Tab(5); "Commodity"; Tab(30); ":"; Tab(35); fnCOMMODITY(iComm)
            Printer.Print ""
  
            iSer = dsHD_SERVICE.Fields("SERVICE_CODE").Value
            
            ' get/make the new LABOR_TICKET header for this combination
            ' this is for moving the Security labor bills to v2; as long as that isn't happening, this needs to be commented (originally Oct 2014)
'            sqlStmt = "SELECT TICKET_NUM " _
'                    & "FROM LABOR_TICKET_HEADER " _
'                    & "WHERE TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') = '" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "' " _
'                    & "AND CUSTOMER_ID = '" & iCustId & "' " _
'                    & "AND COMMODITY_CODE = '" & iComm & "' " _
'                    & "AND VESSEL_ID = '" & iLrNum & "' " _
'                    & "AND SERVICE_GROUP = '" & iSer & "' " _
'                    & "AND (BILL_STATUS IS NULL OR BILL_STATUS != 'Y')"
'            Set dsLABOR_TICKET = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If dsLABOR_TICKET.RecordCount = 0 Then
'                ' this ticket doesn't exist, make it
'                sqlStmt = "SELECT MAX(TICKET_NUM) THE_MAX FROM LABOR_TICKET_HEADER"
'                Set dsLABOR_TICKET = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'                iLTnum = dsLABOR_TICKET.Fields("THE_MAX").Value + 1
'                sqlStmt = "INSERT INTO LABOR_TICKET_HEADER " _
'                    & "(TICKET_NUM, SERVICE_DATE, CUSTOMER_ID, COMMODITY_CODE, VESSEL_ID, LOCATION_ID, USER_ID, SERVICE_GROUP, JOB_DESCRIPTION, BILL_STATUS) VALUES " _
'                    & "('" & iLTnum & "', TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "', 'MM/DD/YYYY'), " _
'                    & "'" & iCustId & "', '" & iComm & "','" & iLrNum & "', 'GUARDS', '" & Trim$(UserID) & "', '" & iSer & "', 'SECURITY', 'N')"
'                OraDatabase.ExecuteSQL sqlStmt
'            Else
'                iLTnum = dsLABOR_TICKET.Fields("TICKET_NUM").Value
'            End If
'
            sqlStmt = " SELECT DISTINCT LOCATION_ID FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "' AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "' AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND LOCATION_ID IS NOT NULL "
                
  
            Set dsHD_LOCATION = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            For iRecLoc = 1 To dsHD_LOCATION.RecordCount
                If sLocation = "" Then
                    sLocation = dsHD_LOCATION.Fields("LOCATION_ID").Value
                Else
                    sLocation = sLocation & "," & dsHD_LOCATION.Fields("LOCATION_ID").Value
                End If
                dsHD_LOCATION.MoveNext
            Next iRecLoc
            
            Printer.Print Tab(5); "Area Worked"; Tab(30); ":"; Tab(35); sLocation
            Printer.Print ""
            
            Printer.Print Tab(5); "Supervisor"; Tab(30); ":"; Tab(35); fnEmp(Trim$(UserID)) & "       _____________________________________________"
            Printer.FontSize = 9
            Printer.Print Tab(90); "(signature)"
            Printer.FontSize = 11
            Printer.Print
            Printer.Print
            Printer.Print
                    
            Printer.Print Tab(5); "--------------------------------------------------------------------------------------------" _
                                ; "--------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Employee Type"; Tab(46); "No."; Tab(55); "Total Hours"; Tab(70); "Labor Type"; Tab(100); "Time"
            Printer.Print Tab(5); "--------------------------------------------------------------------------------------------" _
                                ; "--------------------------------------------------------------------------------------------"
            Printer.Print ""
            
            
'             sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT,SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
'                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('CONT','SUPE','HARB')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL_SUPERVISOR = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL_SUPERVISOR.Fields("SUM_HOURS").Value) Then
'                Printer.Print Tab(10); "SUPERVISOR";
'                For i = 1 To dsHOURLY_DETAIL_SUPERVISOR.RecordCount
'
'                    Printer.Print Tab(47); dsHOURLY_DETAIL_SUPERVISOR.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL_SUPERVISOR.Fields("SUM_HOURS").Value; _
'                          Tab(70); dsHOURLY_DETAIL_SUPERVISOR.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL_SUPERVISOR.Fields("STIME").Value & " - " & _
'                          dsHOURLY_DETAIL_SUPERVISOR.Fields("ETIME").Value
'                    dsHOURLY_DETAIL_SUPERVISOR.MoveNext
'                Next i
'                Printer.Print
'            End If
                    
            'FOR LIFT TRUCKS
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS ,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
'                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL " _
'                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID ='" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND LABOR_TYPE IN('DUMP','OPER','CRAN','LIFT','PAYL',',RAYG','POPR','WATR','CASO','L300','FOPR','TLIF','TYAR')" _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Printer.Print Tab(10); "LIFT TRUCK OPERATORS";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; _
'                                  Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Printer.Print ""
'            End If
             
             '******************************
             'FOR XLIF
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS ,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
'                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL " _
'                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID ='" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND LABOR_TYPE IN('XLIF')" _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Printer.Print Tab(10); "EXTRA LIFT TRUCK OPERATORS";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; _
'                                  Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Printer.Print ""
'            End If
             
                    
            'FOR CHECKERS
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
'                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM " _
'                    & " HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('CHEC','SWEE','CASC','BULL','TCHE','BFOP')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Printer.Print Tab(10); "CHECKERS";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Printer.Print ""
'            End If

            'FOR LABOR
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL WHERE " _
'                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('CASL','LABO','FLAB')" _
'                    & " GROUP BY LABOR_TYPE, TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Printer.Print Tab(10); "LABORERS";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Printer.Print ""
'            End If

            'FOR OFFICE CLERK
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
'                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('OFFI')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                 Printer.Print Tab(10); "OFFICE CLERK";
'                 For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(90); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Printer.Print ""
'            End If
            
            'WEIGHTMASTER
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL " _
'                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('WEIG')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Printer.Print Tab(10); "WEIGHMASTER";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Printer.Print ""
'            End If
            
            'SECURITY OFFICER
            ' first, put them on LT if not already - Adam Walter, Oct 2014
            ' this is used for v2 billing, so until v2 goes forward, it is unneeded (decision:  3/2016)
'            sqlStmt = "UPDATE HOURLY_DETAIL " _
'                    & "SET EXCEPTION_REASON = '" & iLTnum & "' " _
'                    & "WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND (EXCEPTION_REASON IS NULL OR EXCEPTION_REASON != '" & iLTnum & "') " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('SECU', 'SECP')"
'            OraDatabase.ExecuteSQL sqlStmt
            
            ' then, draw up the printout
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('SECU', 'SECP')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"

            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                 Printer.Print Tab(10); "SECURITY OFFICER";
                 For i = 1 To dsHOURLY_DETAIL.RecordCount

                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If
            
            'MECHANIC
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS ,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
'                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('MECH')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                  Printer.Print Tab(10); "MECHANIC";
'                  For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL_TIME.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Printer.Print ""
'            End If
                                
            'WELDER
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
'                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
'                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('WELD')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                 Printer.Print Tab(10); "WELDER";
'                 For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Printer.Print ""
'            End If
                                
            'FLAT
'            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI')STIME," _
'                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL " _
'                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
'                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                    & " AND VESSEL_ID = '" & iLrNum & "'" _
'                    & " AND COMMODITY_CODE = '" & iComm & "'" _
'                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
'                    & " AND LABOR_TYPE IN('FLAT')" _
'                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
'
'            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
'                Printer.Print Tab(10); "FLATED";
'                For i = 1 To dsHOURLY_DETAIL.RecordCount
'
'                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
'                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
'                    dsHOURLY_DETAIL.MoveNext
'                Next i
'                Printer.Print ""
'            End If
                                
            Printer.Print Tab(5); "--------------------------------------------------------------------------------------------" _
                                ; "--------------------------------------------------------------------------------------------"
            
            Printer.Print ""
            Printer.Print ""
            
            'GET SERVICE DESCRIPTION
            sqlStmt = " SELECT DISTINCT SERVICE_CODE FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(UserID) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " ORDER BY SERVICE_CODE"
            Set dsHOURLY_DETAIL_SERVICE = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            Printer.Print Tab(5); "Job Description"; Tab(30); ":";
            For iRecSer2 = 1 To dsHOURLY_DETAIL_SERVICE.RecordCount
                
                sqlStmt = " SELECT * FROM SERVICE WHERE SERVICE_CODE = " & dsHOURLY_DETAIL_SERVICE.Fields("SERVICE_CODE").Value
                Set dsService = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
                
                
                
                If Not IsNull(dsService.Fields("SERVICE_NAME").Value) Then
                    If sService = "" Then
                        Printer.Print Tab(35); dsService.Fields("SERVICE_NAME").Value
                    Else
                        Printer.Print Tab(35); dsService.Fields("SERVICE_NAME").Value
                    End If
                Else
                    If sService = "" Then
                       Printer.Print Tab(35); dsService.Fields("SERVICE_CODE").Value
                    Else
                       Printer.Print Tab(35); dsService.Fields("SERVICE_CODE").Value
                    End If
                End If
                dsHOURLY_DETAIL_SERVICE.MoveNext
            Next iRecSer2
            
            
            
            For i = 0 To 5
                Printer.Print ""
            Next i
            
            Printer.Print Tab(5); "Customer Approval : _______________________________"; Tab(80); " Date : _____________________"
                                
                                    
            dsHD_SERVICE.MoveNext
        Next iRecSer
        dsHD_Customer.MoveNext
                
    Next irec
    
    Printer.EndDoc
End Sub


'****************************************
'To Fill Commodity Drop Down
'****************************************
Private Sub dwnCommodity_InitColumnProps()
  Dim i As Integer, CommRS As Object
  dwnCommodity.Columns.RemoveAll
  For i = 0 To 1
    dwnCommodity.Columns.add i
  Next
  dwnCommodity.Columns(0).Caption = "Commodity Code"
  dwnCommodity.Columns(1).Caption = "Commodity Name"
  Set CommRS = OraDatabase.DBCreateDynaset("Select * from Commodity Order by Commodity_Code", 0&)
  If CommRS.EOF And CommRS.BOF Then
    'Do Nothing
  Else
    CommRS.MoveFirst
    Do While Not CommRS.EOF
      dwnCommodity.AddItem CommRS.Fields("Commodity_Code").Value & "!" & CommRS.Fields("Commodity_Name").Value
      CommRS.MoveNext
    Loop
  End If
  dwnCommodity.Columns(0).Width = 1611
  dwnCommodity.Columns(1).Width = 4261
  CommRS.Close
  Set CommRS = Nothing
End Sub


'****************************************
'To Fill Customer Drop Down
'****************************************
Private Sub dwnCustomer_InitColumnProps()
  Dim i As Integer, CustRS As Object
  dwnCustomer.Columns.RemoveAll
  For i = 0 To 1
    dwnCustomer.Columns.add i
  Next
  dwnCustomer.Columns(0).Caption = "Customer ID"
  dwnCustomer.Columns(1).Caption = "Customer Name"
  Set CustRS = OraDatabase.DBCreateDynaset("Select * from Customer Order by Customer_ID", 0&)
  If CustRS.EOF And CustRS.BOF Then
    'Do Nothing
  Else
    CustRS.MoveFirst
    Do While Not CustRS.EOF
      dwnCustomer.AddItem CustRS.Fields("Customer_ID").Value & "!" & CustRS.Fields("Customer_Name").Value
      CustRS.MoveNext
    Loop
  End If
  dwnCustomer.Columns(0).Width = 1611
  dwnCustomer.Columns(1).Width = 4261
  CustRS.Close
  Set CustRS = Nothing
End Sub

'****************************************
'To Fill Earning Type Drop Down
'****************************************
Private Sub dwnEarning_InitColumnProps()
  Dim i As Integer, EarnRS As Object
  dwnEarning.Columns.RemoveAll
  For i = 0 To 1
    dwnEarning.Columns.add i
  Next
  dwnEarning.Columns(0).Caption = "Earning Type ID"
  dwnEarning.Columns(1).Caption = "Earning Type Description"
  Set EarnRS = OraDatabase.DBCreateDynaset("Select * from Earning_Type Order by Earning_Type_ID", 0&)
  If EarnRS.EOF And EarnRS.BOF Then
    'Do Nothing
  Else
    EarnRS.MoveFirst
    Do While Not EarnRS.EOF
      dwnEarning.AddItem EarnRS.Fields("Earning_Type_ID").Value & "!" & EarnRS.Fields("Earning_Description").Value
      EarnRS.MoveNext
    Loop
  End If
  
  dwnEarning.Columns(0).Width = 1611
  dwnEarning.Columns(1).Width = 4261
  EarnRS.Close
  Set EarnRS = Nothing
End Sub

'****************************************
'To Fill Equipment Drop Down
'****************************************
Private Sub dwnEquipment_InitColumnProps()
  Dim i As Integer, EquipRS As Object
  dwnEquipment.Columns.RemoveAll
  For i = 0 To 1
    dwnEquipment.Columns.add i
  Next
  dwnEquipment.Columns(0).Caption = "Equipment ID"
  dwnEquipment.Columns(1).Caption = "Equipment Description"
  Set EquipRS = OraDatabase.DBCreateDynaset("Select * from Equipment Order by Equipment_ID", 0&)
  If EquipRS.EOF And EquipRS.BOF Then
    'Do Nothing
  Else
    EquipRS.MoveFirst
    Do While Not EquipRS.EOF
      dwnEquipment.AddItem EquipRS.Fields("Equipment_ID").Value & "!" & EquipRS.Fields("Equipment_Description").Value
      EquipRS.MoveNext
    Loop
  End If
  dwnEquipment.Columns(0).Width = 1611
  dwnEquipment.Columns(1).Width = 4261
  EquipRS.Close
  Set EquipRS = Nothing
End Sub

'Private Sub dwnlabor_InitColumnProps()
'  Dim i As Integer, LaborRS As Object
'  dwnlabor .Columns.RemoveAll
'
'  dwnlabor .Columns.Add 0
'
'  dwnlabor .Columns(0).Caption = "Labor Type"
'
'  Set LaborRS = OraDatabaseBNI.DBCreateDynaset("SELECT  * FROM LABOR_CATEGORY ORDER BY LABOR_TYPE", 0&)
'  If LaborRS.EOF And LaborRS.BOF Then
'    'Do Nothing
'  Else
'    LaborRS.MoveFirst
'    Do While Not LaborRS.EOF
'      dwnlabor .AddItem LaborRS.Fields("Labor_type").Value
'      LaborRS.MoveNext
'    Loop
'  End If
'  dwnlabor .Columns(0).Width = 2000
'  LaborRS.Close
'  Set LaborRS = Nothing
'End Sub


'****************************************
'To Fill Exact End Drop Down
'****************************************
Private Sub dwnExactEnd_InitColumnProps()
  Dim i As Integer, j As Integer, tz As String
  For i = 1 To 12
    For j = 0 To 59
      If i = 12 Then tz = "PM" Else tz = "AM"
      If i < 10 Then
        If j < 10 Then
          dwnExactEnd.AddItem "0" + Trim(Str(i)) + ":0" + Trim(Str(j)) + tz
        Else
          dwnExactEnd.AddItem "0" + Trim(Str(i)) + ":" + Trim(Str(j)) + tz
        End If
      Else
        If j < 10 Then
          dwnExactEnd.AddItem Trim(Str(i)) + ":0" + Trim(Str(j)) + tz
        Else
          dwnExactEnd.AddItem Trim(Str(i)) + ":" + Trim(Str(j)) + tz
        End If
      End If
  Next j, i
  For i = 1 To 12
    For j = 0 To 59
      If i = 12 Then tz = "AM" Else tz = "PM"
      If i < 10 Then
        If j < 10 Then
          dwnExactEnd.AddItem "0" + Trim(Str(i)) + ":0" + Trim(Str(j)) + tz
        Else
          dwnExactEnd.AddItem "0" + Trim(Str(i)) + ":" + Trim(Str(j)) + tz
        End If
      Else
        If j < 10 Then
          dwnExactEnd.AddItem Trim(Str(i)) + ":0" + Trim(Str(j)) + tz
        Else
          dwnExactEnd.AddItem Trim(Str(i)) + ":" + Trim(Str(j)) + tz
        End If
      End If
  Next j, i
  dwnExactEnd.Columns(0).Width = 1611
End Sub

'****************************************
'To Fill Duration (Hrs) Drop Down
'****************************************
Private Sub dwnHrs_InitColumnProps()
  Dim i As Integer, j As Integer, arrMin(2) As String
  'Duration in 1/2 an Hour
  arrMin(0) = "0"
  arrMin(1) = "5"
  dwnHrs.AddItem "0"
  dwnHrs.AddItem "0.5"
  For i = 1 To 23
    For j = 0 To 1
      dwnHrs.AddItem Trim(Str(i)) + "." + arrMin(j)
  Next j, i
  'dwnHrs.AddItem "12.0"
  dwnHrs.Columns(0).Width = 1211
  dwnHrs.Columns(0).Caption = "Duration"
End Sub

'****************************************
'To Fill Location Drop Down
'****************************************
Private Sub dwnLocation_InitColumnProps()
  Dim i As Integer, LocRS As Object
  dwnLocation.Columns.RemoveAll
  For i = 0 To 1
    dwnLocation.Columns.add i
  Next
  dwnLocation.Columns(0).Caption = "Commodity Code"
  dwnLocation.Columns(1).Caption = "Commodity Name"
  Set LocRS = OraDatabase.DBCreateDynaset("Select * from Location_Category Order by Location_ID", 0&)
  If LocRS.EOF And LocRS.BOF Then
    'Do Nothing
  Else
    LocRS.MoveFirst
    Do While Not LocRS.EOF
      dwnLocation.AddItem LocRS.Fields("Location_ID").Value & "!" & LocRS.Fields("Location_Description").Value
      LocRS.MoveNext
    Loop
  End If
  dwnLocation.Columns(0).Width = 1611
  dwnLocation.Columns(1).Width = 4261
  LocRS.Close
  Set LocRS = Nothing
End Sub

'****************************************
'To Fill Service Drop Down
'****************************************
Private Sub dwnService_InitColumnProps()
  Dim i As Integer, SvcRS As Object
  dwnService.Columns.RemoveAll
  For i = 0 To 1
    dwnService.Columns.add i
  Next
  dwnService.Columns(0).Caption = "Service Code"
  dwnService.Columns(1).Caption = "Service Name"
  Set SvcRS = OraDatabase.DBCreateDynaset("Select * from Service Order by Service_Code", 0&)
  If SvcRS.EOF And SvcRS.BOF Then
    'Do Nothing
  Else
    SvcRS.MoveFirst
    Do While Not SvcRS.EOF
      dwnService.AddItem SvcRS.Fields("Service_Code").Value & "!" & SvcRS.Fields("Service_Name").Value
      SvcRS.MoveNext
    Loop
  End If
  dwnService.Columns(0).Width = 1611
  dwnService.Columns(1).Width = 4261
  SvcRS.Close
  Set SvcRS = Nothing
End Sub

'****************************************
'To Fill Special Code Drop Down
'****************************************
Private Sub dwnSpec_InitColumnProps()
  Dim i As Integer, SpecRS As Object
  dwnSpec.Columns.RemoveAll
  For i = 0 To 1
    dwnSpec.Columns.add i
  Next
  dwnSpec.Columns(0).Caption = "Vessel ID"
  dwnSpec.Columns(1).Caption = "Vessel Name"
  Set SpecRS = OraDatabase.DBCreateDynaset("Select * from Special_Code Order by Special_Code", 0&)
  If SpecRS.EOF And SpecRS.BOF Then
    'Do Nothing
  Else
    SpecRS.MoveFirst
    Do While Not SpecRS.EOF
      dwnSpec.AddItem SpecRS.Fields("Special_Code").Value & "!" & SpecRS.Fields("Special_Code_Name").Value
      SpecRS.MoveNext
    Loop
  End If
End Sub

'****************************************
'To Fill Start and End Time Drop Down
'****************************************
Private Sub dwnTime_InitColumnProps()
  Dim i As Integer, j As Integer, arrMin(2) As String, tz As String
  'Duration in 1/2 an Hour
  arrMin(0) = "00"
  arrMin(1) = "30"
  For i = 1 To 12
    For j = 0 To 1
      If i = 12 Then tz = "PM" Else tz = "AM"
      If i < 10 Then
        dwnTime.AddItem "0" + Trim(Str(i)) + ":" + arrMin(j) + tz
      Else
        dwnTime.AddItem Trim(Str(i)) + ":" + arrMin(j) + tz
      End If
  Next j, i
  For i = 1 To 12
    For j = 0 To 1
      If i = 12 Then tz = "AM" Else tz = "PM"
      If i < 10 Then
        dwnTime.AddItem "0" + Trim(Str(i)) + ":" + arrMin(j) + tz
      Else
        dwnTime.AddItem Trim(Str(i)) + ":" + arrMin(j) + tz
      End If
  Next j, i
  dwnTime.Columns(0).Width = 1611
End Sub

'****************************************
'To Fill Vessel Drop Down
'****************************************
Private Sub dwnVessel_InitColumnProps()
  Dim i As Integer, ShipRS As Object
  dwnVessel.Columns.RemoveAll
  For i = 0 To 1
    dwnVessel.Columns.add i
  Next
  dwnVessel.Columns(0).Caption = "Vessel ID"
  dwnVessel.Columns(1).Caption = "Vessel Name"
  dwnVessel.Columns(1).Width = 4500
  
  'Set ShipRS = OraDatabase.DBCreateDynaset("Select * from Vessel Order by Vessel_ID", 0&)
  Set ShipRS = OraDatabase2.DBCreateDynaset("Select * from Vessel_profile Order by Lr_Num", 0&)
  
  If ShipRS.EOF And ShipRS.BOF Then
    'Do Nothing
  Else
    ShipRS.MoveFirst
    Do While Not ShipRS.EOF
      dwnVessel.AddItem ShipRS.Fields("LR_NUM").Value & "!" & ShipRS.Fields("VESSEL_NAME").Value
      ShipRS.MoveNext
    Loop
  End If
End Sub

Private Sub Form_Load()
    
Dim sqlStmt As String
Dim CurrDay As Variant
Dim WeekNo As Integer
Dim irec As Integer
Dim dsLABOR_CATEGORY  As Object
  
  
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
    
    
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
  SSDBGrid1.RowHeight = 450

  'Create the OraDatabase Object
  'Set OraDatabase2 = OraSession.Open Database("BNI", "SAG_OWNER/SAG", 0&)
  'Set OraDatabase2 = OraSession.Open Database("BNI.DEV", "SAG_OWNER/SAG_DEV", 0&)  '2853 3/29/2007 Rudy: for testing, orig above
  Set OraDatabase2 = OraSession.OpenDatabase(DB, Login, 0&)  '5/2/2007 HD2759 Rudy:  one init, orig above  TESTED
  
  sqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE LOCATION_ID = 'SUPER'" _
               & " AND HIRE_DATE >=TO_DATE('" & Format(Now, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
               & " AND HIRE_DATE <TO_DATE('" & Format(Now, "MM/DD/YYYY") & "','MM/DD/YYYY') + 1"
  Set dsHIRE_SUPERVISOR = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
  
  If OraDatabase.LastServerErr = 0 And dsHIRE_SUPERVISOR.RecordCount = 0 Then
  
    sqlStmt = " SELECT EMPLOYEE_ID FROM DAILY_HIRE_LIST WHERE LOCATION_ID = 'SUPER'" _
            & " AND HIRE_DATE >=TO_DATE('" & Format(Now, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND HIRE_DATE <TO_DATE('" & Format(Now, "MM/DD/YYYY") & "','MM/DD/YYYY') + 1"
    
    sqlStmt = "SELECT * FROM EMPLOYEE WHERE EMPLOYEE_TYPE_ID ='SUPV' AND EMPLOYEE_ID NOT IN(" & sqlStmt & ")"
    Set dsSUPERVISOR = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And dsSUPERVISOR.RecordCount > 0 Then
       While Not dsSUPERVISOR.EOF
          OraSession.BeginTrans
          dsHIRE_SUPERVISOR.AddNew
          dsHIRE_SUPERVISOR.Fields("HIRE_DATE").Value = Format(Now, "MM/DD/YYYY")
          dsHIRE_SUPERVISOR.Fields("EMPLOYEE_ID").Value = Trim$(dsSUPERVISOR.Fields("EMPLOYEE_ID").Value)
          dsHIRE_SUPERVISOR.Fields("TIME_IN").Value = Format(Format(Now, "MM/DD/YYYY") & " 12:00:00am", "mm/dd/yyyy hh:mm:ssAM/PM")
          dsHIRE_SUPERVISOR.Fields("LOCATION_ID").Value = "SUPER"
          dsHIRE_SUPERVISOR.Fields("COMMODITY_CODE").Value = 0
          dsHIRE_SUPERVISOR.Fields("USER_ID").Value = Trim$(dsSUPERVISOR.Fields("EMPLOYEE_ID").Value)
          dsHIRE_SUPERVISOR.Update
          OraSession.CommitTrans
          dsSUPERVISOR.MoveNext
       Wend
    End If
  End If
  
  'To populate default values for UserID
  txtSupervisorID = UserID
  RowIndex = 0
  'Open Recordset Object and Populate employee details in list
  If LineRun = True Then
    Call PopulateEmpName(7)
  Else
    Call PopulateEmpName(2)
  End If
  
  Call SetColWidth
  ShowLocation      'Fill the Combo with Location Information
  ShowUser          'Fill the Combo with User Information
  cmdUpdate.Enabled = False
  cmdClearLine.Enabled = False
  cmdClearAll.Enabled = False
  cmdDelete.Enabled = False
  
  'To Save the data if next employee is selected without Saving
  FirstClick = True
  SavePrev = False

  If UCase(Trim(UserID)) = "002047" Then
    Call ShowEmployee("GUARD")
  Else
    Call ShowEmployee("")
  End If
    
'    Sqlstmt = "SELECT  * FROM LABOR_CATEGORY ORDER BY LABOR_TYPE"
'    Set dsLABOR_CATEGORY = OraDatabaseBNI.DBCreateDynaset(Sqlstmt, 0&)
'    If OraDatabaseBNI.LastServerErr = 0 And dsLABOR_CATEGORY.RecordCount > 0 Then
'        For irec = 1 To dsLABOR_CATEGORY.RecordCount
'            SSDBGrid1.Columns(16).AddItem dsLABOR_CATEGORY.Fields("LABOR_TYPE").Value '& "-" & dsLABOR_CATEGORY.Fields("LABOR_DESCRIPTION").Value
'            dsLABOR_CATEGORY.MoveNext
'        Next irec
'    End If

'Awalter, oct 2014.  Most of the dropdown options are being removed, as they are not relevant to GUARD postings.
'    SSDBGrid1.Columns(16).AddItem "CHEC"
'    SSDBGrid1.Columns(16).AddItem "OPER"
'    SSDBGrid1.Columns(16).AddItem "SUPE"
'    SSDBGrid1.Columns(16).AddItem "XLIF"
'    SSDBGrid1.Columns(16).AddItem "LABO"
'    SSDBGrid1.Columns(16).AddItem "LIFT"
'    SSDBGrid1.Columns(16).AddItem "BFOP"
'    SSDBGrid1.Columns(16).AddItem "FLAB"
'    SSDBGrid1.Columns(16).AddItem "CASC"
'    SSDBGrid1.Columns(16).AddItem "CASO"
'    SSDBGrid1.Columns(16).AddItem "CASL"
'    SSDBGrid1.Columns(16).AddItem "FOPR"
' Awalter - 02/28/2008.  While I'm not a fan of hardcoding, it is the easiest way to allocate this.
    SSDBGrid1.Columns(16).AddItem "SECP"
' end Awalter update
' pawan....10/25/2001.... As Per Pat hemphill Requirement
    SSDBGrid1.Columns(16).AddItem "SECU"
' pawan....10/25/2001....

    
    
End Sub

'****************************************
'To Unload Current Form and Open Previous Form
'****************************************
Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  'If HideDblEntry = True Or OverlapEntry = False Then
  If HideDblEntry = True And OverlapEntry = True Then
    'Do Nothing
  Else
    frmHiring.Show
  End If
End Sub

'****************************************
'To Close Recordsets opened
'****************************************
Private Sub Form_Terminate()
  Set EmpRS = Nothing
  Set LRS = Nothing
End Sub

'****************************************
'To Fill Location Combo
'****************************************
Private Sub ShowLocation()
  Dim i As Integer
  SSDBCboLoc.Columns.RemoveAll
  For i = 0 To 1
    SSDBCboLoc.Columns.add i
  Next
  Dim LocRS As Object
  SSDBCboLoc.Columns(0).Caption = "Category CD"
  SSDBCboLoc.Columns(1).Caption = "Category Name"
  
  Set LocRS = OraDatabase.DBCreateDynaset("Select * from Location_Category Order by Location_ID", 0&)
  If LocRS.EOF And LocRS.BOF Then
    Exit Sub
  Else
    LocRS.MoveFirst
    Do While Not LocRS.EOF
      SSDBCboLoc.AddItem LocRS.Fields("Location_ID").Value & "!" & LocRS.Fields("Location_Description").Value
      LocRS.MoveNext
    Loop
  End If
  LocRS.Close
  Set LocRS = Nothing
End Sub

'****************************************
'To Fill Commodity Combo
'****************************************
Private Sub ShowCommodity()
  Dim i As Integer
  SSDBCboComm.Columns.RemoveAll
  For i = 0 To 1
    SSDBCboComm.Columns.add i
  Next
  Dim CommRS As Object
  SSDBCboComm.Columns(0).Caption = "Commodity Code"
  SSDBCboComm.Columns(1).Caption = "Commodity Name"
  
  Set CommRS = OraDatabase.DBCreateDynaset("Select * from Commodity Order by Commodity_Code", 0&)
  If CommRS.EOF And CommRS.BOF Then
    Exit Sub
  Else
    CommRS.MoveFirst
    Do While Not CommRS.EOF
      SSDBCboComm.AddItem CommRS.Fields("Commodity_Code").Value & "!" & CommRS.Fields("Commodity_Name").Value
      CommRS.MoveNext
    Loop
  End If
  CommRS.Close
  Set CommRS = Nothing
End Sub

'****************************************
'To Fill User Combo
'****************************************
Private Sub ShowUser()
  Dim i As Integer
  SSDBCboSup.Columns.RemoveAll
  For i = 0 To 1
    SSDBCboSup.Columns.add i
  Next
  Dim SupRS As Object
  SSDBCboSup.Columns(0).Caption = "Supervisor ID"
  SSDBCboSup.Columns(1).Caption = "Supervisor Name"
  Set SupRS = OraDatabase.DBCreateDynaset("Select * from LCS_User", 0&)
  If SupRS.EOF And SupRS.BOF Then
    Exit Sub
  Else
    SupRS.MoveFirst
    Do While Not SupRS.EOF
      SSDBCboSup.AddItem SupRS.Fields("User_ID").Value & "!" & SupRS.Fields("User_Name").Value
      SupRS.MoveNext
    Loop
  End If
  SSDBCboSup.Columns(1).Width = 3500
End Sub

'****************************************
'To Fill Employee Combo
'****************************************
Private Sub ShowEmployee(EmpType As String)
  Dim i As Integer
  SSDBCboEmp.Columns.RemoveAll
  ssdbcboName.Columns.RemoveAll
  For i = 0 To 1
    SSDBCboEmp.Columns.add i
    ssdbcboName.Columns.add i
  Next
  Dim EmpIDRS As Object, EmpNMRS As Object, mySQL As String, myNMSQL As String
  SSDBCboEmp.Columns(0).Caption = "Employee ID"
  SSDBCboEmp.Columns(1).Caption = "Employee Name"
  ssdbcboName.Columns(0).Caption = "Employee Name"
  ssdbcboName.Columns(1).Caption = "Employee ID"
  If LineRun = True Then
    mySQL = "Select Employee_Name, Employee_ID from Employee where upper(Employee_Type_ID) = 'REGR' Order by Employee_ID"
    myNMSQL = "Select Employee_name, Employee_ID from Employee where Upper(Employee_Type_ID) = 'REGR' Order by Employee_Name"
  ElseIf Trim(EmpType) = vbNullString Or IsNull(EmpType) Then
    mySQL = "Select employee_name, employee_id from employee where employee_id in (select Employee_id from daily_hire_list where hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))"
    myNMSQL = "Select employee_name, employee_id from employee where employee_id in (select Employee_id from daily_hire_list where hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')) Order by Employee_Name"
  Else      'For Guard - William Stansbury
    mySQL = "Select a.employee_name, a.employee_id from employee a, daily_hire_list b where (a.employee_id = b.employee_id and upper(a.employee_type_id) = '" + EmpType + "' and b.hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))"
    mySQL = mySQL + " or (a.employee_id = b.employee_id and b.hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and a.employee_id in (Select employee_id from Daily_Hire_List where Upper(b.location_id) = '" + EmpType + "' and b.hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')))"
  End If
  Set EmpIDRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If EmpIDRS.EOF And EmpIDRS.BOF Then
    Exit Sub
  Else
    EmpIDRS.MoveFirst
    Do While Not EmpIDRS.EOF
      SSDBCboEmp.AddItem Right(EmpIDRS.Fields("Employee_ID").Value, 4) & "!" & EmpIDRS.Fields("Employee_Name").Value
      EmpIDRS.MoveNext
    Loop
  End If
  
  If myNMSQL <> "" Then
  
    Set EmpNMRS = OraDatabase.DBCreateDynaset(myNMSQL, 0&)
    If EmpNMRS.EOF And EmpNMRS.BOF Then
      Exit Sub
    Else
      EmpNMRS.MoveFirst
      Do While Not EmpNMRS.EOF
        ssdbcboName.AddItem EmpNMRS.Fields("Employee_Name").Value & "!" & EmpNMRS.Fields("Employee_ID").Value
        EmpNMRS.MoveNext
      Loop
    End If
  
  
  EmpNMRS.Close
  Set EmpNMRS = Nothing
  End If
  
  EmpIDRS.Close
  Set EmpIDRS = Nothing
  'EmpNMRS.Close
  'Set EmpNMRS = Nothing
  SSDBCboEmp.Columns(1).Width = 3500
  ssdbcboName.Columns(0).Width = 3500
End Sub

'****************************************
'To Set the Format for all Columns on the Grid
'****************************************
Private Sub ColumnFormat()
  SSDBGrid1.Columns(1).DropDownHwnd = dwnTime.hWnd
  SSDBGrid1.Columns(2).DropDownHwnd = dwnTime.hWnd
  SSDBGrid1.Columns(3).DropDownHwnd = dwnHrs.hWnd
  SSDBGrid1.Columns(4).DropDownHwnd = dwnEarning.hWnd
  'SSDBGrid1.Columns(5).Style = ssStyleCheckBox
  SSDBGrid1.Columns(6).DropDownHwnd = dwnService.hWnd
  SSDBGrid1.Columns(7).DropDownHwnd = dwnEquipment.hWnd
  SSDBGrid1.Columns(8).DropDownHwnd = dwnCommodity.hWnd
  SSDBGrid1.Columns(9).DropDownHwnd = dwnLocation.hWnd
  SSDBGrid1.Columns(10).DropDownHwnd = dwnVessel.hWnd
  SSDBGrid1.Columns(11).DropDownHwnd = dwnCustomer.hWnd
  SSDBGrid1.Columns(12).Locked = True
  SSDBGrid1.Columns(12).ForeColor = RGB(255, 0, 0)
  SSDBGrid1.Columns(13).DropDownHwnd = dwnSpec.hWnd
  SSDBGrid1.Columns(15).DropDownHwnd = dwnExactEnd.hWnd
  'SSDBGrid1.Columns(16).DropDownHwnd = dwnlabor .hWnd
End Sub

'****************************************
'To Set the Column Width on the Grid
'****************************************
Private Sub SetColWidth()
'  SSDBGrid1.Columns(0).Visible = False
'  SSDBGrid1.Columns(1).Width = 1200.189
''  SSDBGrid1.Columns(1).Caption = "START"
'  SSDBGrid1.Columns(2).Width = 1200.189
''  SSDBGrid1.Columns(2).Caption = "END"
'  SSDBGrid1.Columns(3).Width = 734.7402
''  SSDBGrid1.Columns(3).Caption = "HRS"
'  SSDBGrid1.Columns(4).Width = 1005.165
''  SSDBGrid1.Columns(4).Caption = "REG/OT"
'  SSDBGrid1.Columns(5).Width = 599.811
''  SSDBGrid1.Columns(5).Caption = "BILL"
'  SSDBGrid1.Columns(6).Width = 854.9292
''  SSDBGrid1.Columns(6).Caption = "SRVC"
'  SSDBGrid1.Columns(7).Width = 824.882
''  SSDBGrid1.Columns(7).Caption = "EQUIP"
'  SSDBGrid1.Columns(8).Width = 840.189
''  SSDBGrid1.Columns(8).Caption = "COMM"
'  SSDBGrid1.Columns(9).Width = 989.8583
''  SSDBGrid1.Columns(9).Caption = "CATE"
'  SSDBGrid1.Columns(10).Width = 794.8347
''  SSDBGrid1.Columns(10).Caption = "SHIP"
'  SSDBGrid1.Columns(11).Width = 854.9292
''  SSDBGrid1.Columns(11).Caption = "CUST"
'  SSDBGrid1.Columns(12).Width = 1065.26
''  SSDBGrid1.Columns(12).Caption = "SUPV"
'  SSDBGrid1.Columns(13).Width = 810.1418
''  SSDBGrid1.Columns(13).Caption = "SPEC"
'  SSDBGrid1.Columns(14).Width = 1230.236
''  SSDBGrid1.Columns(14).Caption = "REMARK"
'  SSDBGrid1.Columns(15).Width = 1349.858
'  SSDBGrid1.Columns(16).Width = 1349.858
'  SSDBGrid1.Columns(15).Caption = "EXACTEND"
'  'To find the Total Hours Worked for the Day for each Employee
  Label4.Caption = "Total Hours : " + Trim(Str(FindTotalHrs))
End Sub

'****************************************
'To Populate Employee Details (Name, ID, Type-ID) on the List
'****************************************
Private Sub PopulateEmpName(FilterBy As Integer)
  Dim myEmpSQL As String, TotalEmp As Integer, myChkRS As Object
  Dim arrCnt As Integer, DayOpened As Boolean, blContinue As Boolean
  
  On Error GoTo ErrHandler
  If FilterBy = 7 Then                'Line Runners
    myEmpSQL = "Select * from Employee where Upper(Employee_Type_ID) = 'REGR' Order by Employee_ID"
  ElseIf FilterBy = 0 Then                'Location
    
    'If UCase(Trim(UserID)) = " E002047" Then 'Only Guards for William Bolles
    'If UCase(Trim(UserID)) = " E405833" Then 'Only Guards for William Stansbury
    '  myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(Location_Id) = '" + UCase(Trim(SSDBCboLoc.Text)) + "') and Upper(Employee_Type_ID) = 'GUARD' Order by Employee_Name"
    'Else
      ' Changed by Bruce LeBrun - 02/17/2000.  If the filter is for Supervisors then all supervisors must show up in the list.
      If UCase(Trim(SSDBCboLoc.Text)) = "SUPER" Then
         ' Special case for Supervisor.  We want to show all supervisors including those not in the daily_hire_list.
         myEmpSQL = "Select * from Employee where (Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(Location_Id) = '" + UCase(Trim(SSDBCboLoc.Text)) + "')) " & _
         " or (EMPLOYEE_TYPE_ID ='SUPV' AND EMPLOYEE_ID NOT IN(Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(Location_Id) = '" + UCase(Trim(SSDBCboLoc.Text)) + "')) Order by Employee_Name"
      Else
         myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(Location_Id) = '" + UCase(Trim(SSDBCboLoc.Text)) + "') Order by Employee_Name"
      End If
    'End If
  ElseIf FilterBy = 1 Then          'Commodity
    myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Commodity_Code = " + SSDBCboComm.Text + ") Order by Employee_Name"
  ElseIf FilterBy = 2 Then            'All Employees
    'Check whether the Day is Closed
    myEmpSQL = "Select * from DailyHrs_TransTo_PayRoll where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
    Set myChkRS = OraDatabase.DBCreateDynaset(myEmpSQL, 0&)
    If myChkRS.EOF And myChkRS.BOF Then   'Day is NOT CLOSED
    
       ' Per Inigo Thomas 02/17/2000 - We will not allow data from previous weeks to be changed
       ' unless it is before 1:00 pm on Monday of the current week.
       'blContinue = OnePM_Rule(CDate(SSDateCombo1.Text))
    
    
       ' Per Inigo Thomas 02/14/2000 - We will not allow data from the previous day to be updated if it is after 5:00pm
       ' 5:00 pm rule
'       If CDate(SSDateCombo1.Text) < Date Then
'          ' We will see if the date is 2 days ago or longer
'          If CDate(SSDateCombo1.Text) <= Date - 2 Then
'             ' This day is closed
'             Call MsgBox("You cannot enter time for " & SSDateCombo1.Text & "!  This day is closed!", vbCritical + vbOKOnly, "LCS")
'             SSDBCboLoc.Text = ""
'             chkFilterBy(0).Value = 0
'             SSDBCboSup.Text = ""
'             SSDBCboEmp.Text = ""
'             optFilterEmp(0).Value = False
'             SSDBCboEmp.Enabled = False
'             ssdbcboName.Text = ""
'             optFilterEmp(1).Value = False
'             ssdbcboName.Enabled = False
'             ' Don't allow further processing
'             blContinue = False
'          Else
'             If Hour(Time) > 16 Then
'                ' Check and see if it is later than 5:00 pm if the data in question is from a previous day
'                Call MsgBox("You cannot enter time for a previous day after 5:00 PM!  This day is closed!", vbCritical + vbOKOnly, "LCS")
'                SSDBCboLoc.Text = ""
'                chkFilterBy(0).Value = 0
'                SSDBCboSup.Text = ""
'                SSDBCboEmp.Text = ""
'                optFilterEmp(0).Value = False
'                SSDBCboEmp.Enabled = False
'                ssdbcboName.Text = ""
'                optFilterEmp(1).Value = False
'                ssdbcboName.Enabled = False
'                ' Don't allow further processing
'                blContinue = False
'             Else
'                ' Further processing is okay
'                blContinue = True
'             End If
'          End If
'       Else
'          ' Further processing is okay
'          blContinue = True
'       End If
       blContinue = True
       
       
       If blContinue Then   ' Continue normal processing, date & time check passed
          If LineRun = True Then
             myEmpSQL = "Select * from Employee where Upper(Employee_Type_ID) = 'REGR'"
          'ElseIf UCase(Trim(UserID)) = " E405833" Then 'Only Guards for William Stansbury
          'ElseIf UCase(Trim(UserID)) = " E002047" Then 'Only Guards for William Bloes
          '   myEmpSQL = "Select a.* from Employee a, Daily_Hire_List b where (a.Employee_ID = b.Employee_ID and Upper(a.Employee_Type_ID) = 'GUARD' and b.Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))"
          '   myEmpSQL = myEmpSQL + " or (a.employee_id = b.employee_id and b.Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and a.Employee_id in (Select Employee_id from Daily_Hire_List where Upper(b.Location_ID) = 'GUARD'"
          '   myEmpSQL = myEmpSQL + "and b.Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))) Order by Employee_Name"
          '   Call ShowEmployee("GUARD")
          Else
'             myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')) Order by Employee_Name"
             
             ' Changed on 02/17/2000 by Bruce LeBrun to insure that all supervisors are part of the "all employees" filter
             myEmpSQL = "Select * from Employee where (Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))) " & _
             " or (EMPLOYEE_TYPE_ID ='SUPV' AND EMPLOYEE_ID NOT IN(Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))) Order by Employee_Name"
             Call ShowEmployee("")
          End If
       End If
    Else
      MsgBox "Data Transferred to PayRoll. " + Chr(13) + "Please Use Correction Screen for further Modification for this day.", vbInformation, "Data Entry Not Allowed"
      SSDBCboLoc.Text = ""
      chkFilterBy(0).Value = 0
      SSDBCboSup.Text = ""
      SSDBCboEmp.Text = ""
      optFilterEmp(0).Value = False
      SSDBCboEmp.Enabled = False
      ssdbcboName.Text = ""
      optFilterEmp(1).Value = False
      ssdbcboName.Enabled = False
    End If
  ElseIf FilterBy = 3 Then            'User
    myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Hourly_Detail where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(User_Id) = '" + UCase(Trim(SSDBCboSup.Text)) + "') Order by Employee_Name"
  ElseIf FilterBy = 4 Then            'Location + Commodity
    myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Commodity_Code = " + SSDBCboComm.Text + " and Upper(Location_Id) = '" + UCase(Trim(SSDBCboLoc.Text)) + "') Order by Employee_Name"
  ElseIf FilterBy = 5 Then            'Employee ID
    If LineRun = True Then
      myEmpSQL = "Select * from Employee where Employee_ID like '%" + UCase(Trim(SSDBCboEmp.Text)) + "' Order by Employee_Name"
    Else
      myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(Employee_ID) like '%" + UCase(Trim(SSDBCboEmp.Text)) + "') Order by Employee_Name"
    End If
  ElseIf FilterBy = 6 Then           'Employee Name
    If LineRun = True Then
      myEmpSQL = "Select * from Employee where Upper(Employee_Name) = '" + UCase(Trim(ssdbcboName.Text)) + "' Order by Employee_Name"
    Else
      myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')) and Upper(Employee_Name) = '" + UCase(Trim(ssdbcboName.Text)) + "' Order by Employee_Name"
    End If
  ElseIf FilterBy = 12 Then           'Only Guards for William Stansbury
    If LineRun = True Then            'Even Stansbury can view all REGR in Line
      myEmpSQL = "Select * from Employee where Upper(Employee_Type_ID) = 'REGR'"
    Else
      myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')) and Upper(Employee_Type_ID) = 'GUARD' Order by Employee_Name"
    End If
  End If
  Set EmpRS = OraDatabase.DBCreateDynaset(myEmpSQL, 0&)
  If FilterBy = 7 And EmpRS.BOF And EmpRS.EOF Then      'Line Runners
    MsgBox "No Regular Employees exists for Line", vbInformation, "Data Unavailable"
    Exit Sub
  ElseIf FilterBy = 2 And EmpRS.BOF And EmpRS.EOF Then        'All Employees
    'MsgBox "No Employee Data available. ", vbInformation, "Data Unavailable"
    'ShowMeNot = True
    Exit Sub
  ElseIf FilterBy = 12 And EmpRS.BOF And EmpRS.EOF Then   'Only Guards for William Stansbury
    MsgBox "No Employee Data with Type GUARD are available. ", vbInformation, "Data Unavailable"
    'ShowMeNot = True
    Exit Sub
  ElseIf FilterBy = 0 And EmpRS.BOF And EmpRS.EOF Then    'Location
    MsgBox "No Employee Data available for the Selected Location. ", vbInformation, "Data Unavailable"
    cmdUpdate.Enabled = False
    cmdClearLine.Enabled = False
    cmdClearAll.Enabled = False
    cmdDelete.Enabled = False
    SSDBGrid1.RemoveAll
    SSDBCboLoc.Text = ""
    chkFilterBy(0).Value = 0
    Exit Sub
  ElseIf FilterBy = 1 And EmpRS.BOF And EmpRS.EOF Then    'Commodity
    MsgBox "No Employee Data available for the Selected Commodity. ", vbInformation, "Data Unavailable"
    cmdUpdate.Enabled = False
    cmdClearLine.Enabled = False
    cmdClearAll.Enabled = False
    cmdDelete.Enabled = False
    SSDBGrid1.RemoveAll
    SSDBCboComm.Text = ""
    chkFilterBy(1).Value = 0
    Exit Sub
  ElseIf FilterBy = 3 And EmpRS.BOF And EmpRS.EOF Then    'Supervisor
    MsgBox "No Employee Data available for the Selected Supervisor. ", vbInformation, "Data Unavailable"
    cmdUpdate.Enabled = False
    cmdClearLine.Enabled = False
    cmdClearAll.Enabled = False
    cmdDelete.Enabled = False
    SSDBGrid1.RemoveAll
    SSDBCboSup.Text = ""
    Exit Sub
  ElseIf FilterBy = 4 And EmpRS.BOF And EmpRS.EOF Then    'Location and Commodity
    MsgBox "No Employee Data available for the Selected Location and Commodity. ", vbInformation, "Data Unavailable"
    cmdUpdate.Enabled = False
    cmdClearLine.Enabled = False
    cmdClearAll.Enabled = False
    cmdDelete.Enabled = False
    SSDBGrid1.RemoveAll
    SSDBCboLoc.Text = ""
    SSDBCboComm.Text = ""
    chkFilterBy(0).Value = 0
    chkFilterBy(1).Value = 0
    Exit Sub
  ElseIf FilterBy = 5 And EmpRS.BOF And EmpRS.EOF Then    'Employee ID
    MsgBox "No Employee Data available for the Selected Employee ID. ", vbInformation, "Data Unavailable"
    cmdUpdate.Enabled = False
    cmdClearLine.Enabled = False
    cmdClearAll.Enabled = False
    cmdDelete.Enabled = False
    SSDBGrid1.RemoveAll
    SSDBCboEmp.Text = ""
    optFilterEmp(0).Value = False
    SSDBCboEmp.Enabled = False
    Exit Sub
  ElseIf FilterBy = 6 And EmpRS.BOF And EmpRS.EOF Then    'Employee Name
    MsgBox "No Employee Data available for the Selected Employee Name. ", vbInformation, "Data Unavailable"
    cmdUpdate.Enabled = False
    cmdClearLine.Enabled = False
    cmdClearAll.Enabled = False
    cmdDelete.Enabled = False
    SSDBGrid1.RemoveAll
    ssdbcboName.Text = ""
    optFilterEmp(1).Value = False
    ssdbcboName.Enabled = False
    Exit Sub
  Else
    EmpRS.MoveLast
    TotalEmp = EmpRS.RecordCount
    EmpRS.MoveFirst
  End If
  ReDim arrEmpID(TotalEmp) As String
  arrCnt = 1
  Do While Not EmpRS.EOF
    Dim empId As String, TypeID As String
    empId = UCase(EmpRS.Fields("Employee_id").Value)
    If IsNull(EmpRS.Fields("Employee_Sub_Type_ID").Value) Then
      TypeID = UCase(EmpRS.Fields("Employee_Type_ID").Value)
    Else
      TypeID = UCase(EmpRS.Fields("Employee_Type_ID").Value + "-" + EmpRS.Fields("Employee_Sub_Type_ID").Value)
    End If
    
    If IsNull(EmpRS.Fields("Seniority").Value) Then
      lstEmpName.AddItem "(" + empId + ") " + EmpRS.Fields("Employee_Name").Value + ":" + TypeID
    Else
      lstEmpName.AddItem "(" + empId + ") " + EmpRS.Fields("Employee_Name").Value + ":" + TypeID + ":" + Str(EmpRS.Fields("Seniority").Value)
    End If
    arrEmpID(arrCnt) = EmpRS.Fields("Employee_id").Value
    EmpRS.MoveNext
    arrCnt = arrCnt + 1
  Loop
  Exit Sub
ErrHandler:
  If Err.Number = -2147467259 Then
    MsgBox "Please Close the database to run the Application", vbInformation, "Database is in use"
    ErrFlag = True
    Unload Me
  End If
End Sub

'****************************************
'To open the Hourly Detail Recordset
'****************************************
Private Sub OpenHourlyRS()
  On Error GoTo ErrHandler
  
  Dim TotalRec As Integer
  Dim sqlStmt As String
  Dim arrCnt As Integer
  
  sqlStmt = " SELECT ROW_NUMBER, START_TIME,END_TIME,DURATION,EARNING_TYPE_ID, BILLING_FLAG, SERVICE_CODE, EQUIPMENT_ID, " _
          & " COMMODITY_CODE, LOCATION_ID, VESSEL_ID, CUSTOMER_ID, USER_ID, SPECIAL_CODE,REMARK,EXACT_END,LABOR_TYPE,ROWID " _
          & " FROM HOURLY_DETAIL " _
          & " WHERE HIRE_DATE=TO_DATE('" + SSDateCombo1.Text + "','MM/DD/YYYY') AND " _
          & " EMPLOYEE_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' ORDER BY ROW_NUMBER"
          
  Set LRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
  If LRS.RecordCount = 0 Then
    NoRec = True
    Exit Sub
  Else
    NoRec = False
    LRS.MoveLast
    TotalRec = LRS.RecordCount
    LRS.MoveFirst
  End If
  
  Exit Sub
  
ErrHandler:
    If Err.Number = -2147467259 Then
        MsgBox "Please Close the database to run the Application", vbInformation, "Database is in use"
        ErrFlag = True
        Unload Me
    Else
        MsgBox Err.Description
        ErrFlag = True
        
  End If
End Sub

'****************************************
'To Open the Recordset and Show Defaults on Grid
'****************************************
Private Sub lstEmpName_Click()
  Dim ERS As Object, sqlStmt As String, i As Integer
  
  'check if this employee is supervisor. if yes, check if this employee is current user.
  'if not, stop.  ......update by rw 12/4/01
  If isSupervisor(arrEmpID(lstEmpName.ListIndex + 1)) And arrEmpID(lstEmpName.ListIndex + 1) <> UserID Then
    SSDBGrid1.RemoveAll
    MsgBox "Sorry, You can not enter or edit other supervisor's hourly detail.", vbCritical + vbOKOnly, "Hourly detail"
    Exit Sub
  End If
  
  If FirstClick = True Then
    PrevIndex = lstEmpName.ListIndex
    FirstClick = False
  Else
    'Check if Already Saved
    If cmdUpdate.Enabled = False Then
      'Do Nothing
    Else
      'Save Process
      SavePrev = True
      cmdUpdate_Click
      cmdUpdate.Enabled = True
    End If
  End If
  PrevIndex = lstEmpName.ListIndex

  SSDBGrid1.RemoveAll
    
  ' Depending on the employee type display only valid codes for the earning type.
  OpenHourlyRS    'Define the Recordset Object for the Grid
  
  dwnEarning.Columns.RemoveAll
  For i = 0 To 1
    dwnEarning.Columns.add i
  Next
  dwnEarning.Columns(0).Caption = "Earning Type ID"
  dwnEarning.Columns(1).Caption = "Earning Type Description"
  
  dwnEarning.Columns(0).Width = 1611
  dwnEarning.Columns(1).Width = 4261

  
  sqlStmt = "Select employee_type_id from employee where Employee_ID = '" & arrEmpID(lstEmpName.ListIndex + 1) & "'"
  Set ERS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
  If Not ERS.EOF Then
     Select Case ERS.Fields("employee_type_id").Value
        ' Lets change the available codes that can be selected per employee type
        Case "GUARD"
             ' Add codes if a guard
             dwnEarning.AddItem "REG!Regular"
             dwnEarning.AddItem "OT!Overtime"
             dwnEarning.AddItem "PERS!Personal Time"
             dwnEarning.AddItem "HOL-REG!Holiday Regular"
             dwnEarning.AddItem "HOL-OT!Holiday Overtime"
             dwnEarning.AddItem "BIRTH!Birthday"
             dwnEarning.AddItem "BIRTH-OT!Birthday Overtime"
        Case "CASC"
             ' Regular earning type only
             dwnEarning.AddItem "REG!Regular"
        Case "CASB"
             ' Add codes if a guard
             dwnEarning.AddItem "REG!Regular"
             If Weekday(CDate(SSDateCombo1.Text)) = vbSaturday Or Weekday(CDate(SSDateCombo1.Text)) = vbSunday Then
                dwnEarning.AddItem "OT!Overtime"
             End If
             dwnEarning.AddItem "ST!Supervisor Training"
        Case "REGR"
             dwnEarning_InitColumnProps      ' Database defaults
        Case "SUPV"
             dwnEarning_InitColumnProps      ' Database defaults
             ' Add the additional codes if a supervisor
'             dwnEarning.AddItem "SICK!Sick"
'             dwnEarning.AddItem "VACN!Vacation"
        Case Else
             ' Go with database default
             dwnEarning_InitColumnProps       ' Call Regular Routine
    End Select
  Else
     dwnEarning_InitColumnProps       ' Call Regular Routine
  End If
  ERS.Close
  Set ERS = Nothing

  If ErrFlag = False Then
    AddRec        'Add the Fields and records in Grid
    ColumnFormat  'Add Dropdown to Columns
    SetColWidth   'To set Grid Column Width
    SetDefault    'Set Default Values to Previous Data from Grid
    'If SSDBGrid1.Rows = 10 Then SSDBGrid1.AllowAddNew = False
    
    cmdUpdate.Enabled = True
    cmdClearLine.Enabled = True
    cmdClearAll.Enabled = True
    cmdDelete.Enabled = True
  Else
    ErrFlag = False
  End If
End Sub

'****************************************
'To Add Rows on Grid - Set default for Blank Rows
'****************************************
Private Sub AddRec()
    Dim i As Integer
    Dim sFields() As String
    Dim sLrsField As String
    Dim recCnt As Integer
    Dim Bill() As Boolean
    Dim DailyHireRS As Object
    Dim sqlStmt As String
    Dim irec As Integer
  
    ct = LRS.Fields.count           'Count the total number of fields in recordset object
  
    If NoRec = True Then
        ReDim DiffSupervisor(0) As Boolean   'Limit 10 Rows
       
       'To have Start_Time as the default Time_In from Daily Hire Table and Location and Commodity Code as default
        sqlStmt = " Select Time_In, Location_Id, Commodity_Code from Daily_Hire_List " _
                & " Where Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and " _
                & " Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
            
        Set DailyHireRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
      
        PrvLoc = Trim("" & DailyHireRS.Fields("Location_ID").Value)
        PrvComm = Trim("" & DailyHireRS.Fields("COMMODITY_CODE").Value)

        SSDBGrid1.MoveFirst
        If DailyHireRS.RecordCount > 0 Then
            
            
            
            If LineRun = True Then
                SSDBGrid1.AddItem CStr(irec)
            Else
                SSDBGrid1.AddItem "1" + Chr(9) + Format(Str(DailyHireRS.Fields("time_in").Value), "hh:nnAM/PM") + Chr(9) + Chr(9) + _
                                  Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + Trim(Str(DailyHireRS.Fields("Commodity_Code").Value)) + _
                                  Chr(9) + DailyHireRS.Fields("Location_ID").Value
            End If
            
        
        '
        'If UCase(Trim(UserID)) = " E002047" Then
        '     SSDBGrid1.Columns(5).Value = False
        '        SSDBGrid1.Columns(7).Value = "0"
        '        SSDBGrid1.Columns(8).Value = "0"
        '        SSDBGrid1.Columns(10).Value = "-1"
        '        SSDBGrid1.Columns(11).Value = "0"
        '        SSDBGrid1.Columns(12).Value = UserID
        '    Else
        
'                SSDBGrid1.Columns(4).Value = "REG"          '2853 3/29/2007 Rudy:   Default - Labor Type to REG, Per Inigo Thomas 3/29/2007 (no 4/3)
        
                SSDBGrid1.Columns(5).Value = False           'Default - Billing Flag to be FALSE
                SSDBGrid1.Columns(12).Value = UserID        'Default - UserID is the logon Person
                SSDBGrid1.Columns(10).Value = "-1"             'Default - Vessel as No Ship - 0(N/A)
        '    End If
'            SSDBGrid1.MoveLast
'            For irec = 1 To 9
'                SSDBGrid1.AddItem Chr(9) + Chr(9) + Chr(9) + _
'                                  Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + _
'                                  Chr(9) + Chr(9) + Chr(9) + Chr(9) + UserID 'CStr(irec + 1) +
'                SSDBGrid1.MoveNext
'            Next irec
            'SSDBGrid1.MoveFirst
        End If
        
    Else
        'Add the records from the database to the Grid
        LRS.MoveLast: LRS.MoveFirst
    
        'iRec = LRS.RecordCount
        'ReDim DiffSupervisor(iRec) As Boolean
        'ReDim Bill(iRec) As Boolean
        ReDim DiffSupervisor(LRS.RecordCount) As Boolean
        ReDim Bill(LRS.RecordCount) As Boolean
        ReDim sFields(LRS.RecordCount) As String
        recCnt = 1
        
        For irec = 1 To LRS.RecordCount
                 
            For i = 0 To (ct - 1)           'Loop through Fields
                If i = 5 Then
                    If UCase(Trim(LRS.Fields(i).Value)) = "Y" Then Bill(recCnt) = True
                    If UCase(Trim(LRS.Fields(i).Value)) = "N" Then Bill(recCnt) = False
                ElseIf IsNumeric(LRS.Fields(i).Value) Then
                    sLrsField = Str(LRS.Fields(i).Value)
                ElseIf IsNull(LRS.Fields(i).Value) Then
                    sLrsField = " "
                ElseIf i = 1 Or i = 2 Or i = 15 Then
                    sLrsField = Format(LRS.Fields(i).Value, "hh:mmAM/PM")
                Else
                    sLrsField = LRS.Fields(i).Value
                End If
                sFields(recCnt) = sFields(recCnt) + Trim(sLrsField) + Chr(9)
            Next i
      
            'User can't change Employee Data of Other User
            If Trim(UCase(UserID)) <> Trim(UCase(LRS.Fields("User_ID").Value)) Then
                DiffSupervisor(recCnt - 1) = True
            Else
                DiffSupervisor(recCnt - 1) = False
            End If
      
      
            LRS.MoveNext
            sFields(recCnt) = Left(sFields(recCnt), Len(sFields(recCnt)) - 1)
            SSDBGrid1.AddItem sFields(recCnt)
      
            recCnt = recCnt + 1
        Next irec
        
        For i = 1 To recCnt - 1
            SSDBGrid1.row = i - 1
            If Bill(i) = True Then
                SSDBGrid1.Columns(5).Value = -1
            Else
                SSDBGrid1.Columns(5).Value = 0
            End If
        Next
        
'        If SSDBGrid1.Rows < 10 Then
'            SSDBGrid1.MoveLast
'            For irec = 1 To 10 - SSDBGrid1.Rows
'                SSDBGrid1.AddItem Chr(9) + Chr(9) + Chr(9) + _
'                                  Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + _
'                                  Chr(9) + Chr(9) + Chr(9) + Chr(9) + UserID        'CStr(SSDBGrid1.Rows + 1) +
'                SSDBGrid1.MoveNext
'            Next irec
'        End If
        
        
    End If
End Sub

'****************************************
'To Enable / Disable Controls based on Selection in Employee ID / Name Options
'****************************************
Private Sub optFilterEmp_Click(Index As Integer)
  chkFilterBy(0).Enabled = False
  chkFilterBy(1).Enabled = False
  SSDBCboLoc.Enabled = False
  SSDBCboLoc.Text = ""
  SSDBCboComm.Enabled = False
  SSDBCboComm.Text = ""
  If Index = 0 Then
    SSDBCboEmp.Enabled = True
    SSDBCboEmp.SetFocus
    ssdbcboName.Text = ""
    ssdbcboName.Enabled = False
  ElseIf Index = 1 Then
    ssdbcboName.Enabled = True
    ssdbcboName.SetFocus
    SSDBCboEmp.Text = ""
    SSDBCboEmp.Enabled = False
  End If
End Sub

'****************************************
'To Populate Employee Details on the List based on Date Selected
'****************************************
Private Sub SSDateCombo1_Click()
  lstEmpName.Clear
  Call PopulateEmpName(2)
  SSDBGrid1.RemoveAll
  cmdUpdate.Enabled = False
  cmdDelete.Enabled = False
  cmdClearLine.Enabled = False
  cmdClearAll.Enabled = False
End Sub

'****************************************
'To Populate Employee Details on the List based on Date Selected - if not clicked, but changed
'****************************************
Private Sub SSDateCombo1_LostFocus()
  lstEmpName.Clear
  Call PopulateEmpName(2)
  SSDBGrid1.RemoveAll
  cmdUpdate.Enabled = False
  cmdDelete.Enabled = False
  cmdClearLine.Enabled = False
  cmdClearAll.Enabled = False
End Sub

'****************************************
'To Make the text Entered to Upper Case
'****************************************
Private Sub SSDBCboEmp_LostFocus()
  SSDBCboEmp.Text = UCase(SSDBCboEmp.Text)
End Sub
Private Sub SSDBCboLoc_LostFocus()
  SSDBCboLoc.Text = UCase(SSDBCboLoc.Text)
End Sub
Private Sub ssdbcboName_LostFocus()
  ssdbcboName.Text = UCase(ssdbcboName.Text)
End Sub

'****************************************
'To Show Emp Based on Selected User ID
'****************************************
Private Sub SSDBCboSup_Click()
  lstEmpName.Clear
  Call PopulateEmpName(3)
  SSDBGrid1.RemoveAll
  cmdUpdate.Enabled = False
  cmdDelete.Enabled = False
  cmdClearLine.Enabled = False
  cmdClearAll.Enabled = False
End Sub

'****************************************
'To Store Grid Content on Variables for Previous Values
'****************************************
Private Sub SSDBGrid1_AfterColUpdate(ByVal ColIndex As Integer)
    Dim sqlStmt As String
    
    If (ColIndex = 1 Or ColIndex = 2) And dwnTime.ListAutoValidate = False Then
        dwnTime.ListAutoValidate = True
  
    ElseIf (ColIndex = 1 Or ColIndex = 2) And SSDBGrid1.Columns(3).Value <> vbNullString Then
        If Trim(SSDBGrid1.Columns(1).Value) <> vbNullString And Trim(SSDBGrid1.Columns(2).Value) <> vbNullString Then
            SSDBGrid1.Columns(3).Value = FindDuration(SSDBGrid1.Columns(1).Value, SSDBGrid1.Columns(2).Value)
        End If
    End If
  
    If ColIndex = 3 And dwnHrs.ListAutoValidate = False Then dwnHrs.ListAutoValidate = True

    If ColIndex = 4 And dwnEarning.ListAutoValidate = False Then dwnEarning.ListAutoValidate = True
  
    If ColIndex = 6 Then
        If dwnService.ListAutoValidate = False Then dwnService.ListAutoValidate = True
        
        If SSDBGrid1.Columns(6).Value = "" Then Exit Sub
        If SSDBGrid1.Columns(5).Value = -1 Then
            sqlStmt = " SELECT * FROM SERVICE_LABOR_RATE_TYPE  WHERE SERVICE_CODE ='" & Trim(SSDBGrid1.Columns(6).Value) & "'"
            Set dsService = OraDatabaseBNI.DBCreateDynaset(sqlStmt, 0&)
            If dsService.RecordCount > 0 Then SSDBGrid1.Columns(16).Value = dsService("LABOR_TYPE").Value
        End If
    End If
  
    If ColIndex = 7 And dwnEquipment.ListAutoValidate = False Then dwnEquipment.ListAutoValidate = True
  
    If ColIndex = 8 And dwnCommodity.ListAutoValidate = False Then dwnCommodity.ListAutoValidate = True
  
    If ColIndex = 9 And dwnLocation.ListAutoValidate = False Then dwnLocation.ListAutoValidate = True
  
    If ColIndex = 10 And dwnVessel.ListAutoValidate = False Then dwnVessel.ListAutoValidate = True
  
    If ColIndex = 11 And dwnCustomer.ListAutoValidate = False Then dwnCustomer.ListAutoValidate = True
  
    If ColIndex = 15 And dwnExactEnd.ListAutoValidate = False Then dwnExactEnd.ListAutoValidate = True
    
    'If ColIndex = 16 And dwnlabor .ListAutoValidate = False Then dwnlabor .ListAutoValidate = True
  
  
    Select Case ColIndex
        Case 2
            If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then PrvEnd = UCase(SSDBGrid1.ActiveCell.Text)
        Case 4
            If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then PrvReg = UCase(SSDBGrid1.ActiveCell.Text)
        Case 6
            If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then PrvSvc = UCase(SSDBGrid1.ActiveCell.Text)
        Case 7
            If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then PrvEquip = UCase(SSDBGrid1.ActiveCell.Text)
        Case 8
            If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then PrvComm = UCase(SSDBGrid1.ActiveCell.Text)
        Case 9
            If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then PrvLoc = UCase(SSDBGrid1.ActiveCell.Text)
        Case 10
            If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then PrvShip = UCase(SSDBGrid1.ActiveCell.Text)
        Case 11
            If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then PrvCust = UCase(SSDBGrid1.ActiveCell.Text)
        Case 13
            If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then PrvSpec = UCase(SSDBGrid1.ActiveCell.Text)
        Case 16
            If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then PrvLabor = UCase(SSDBGrid1.ActiveCell.Text)
    End Select
  
End Sub

'****************************************
'To Validate Certain Criteria on Column Change of the Grid
'****************************************
Private Sub SSDBGrid1_Change()
    
  If SSDBGrid1.ActiveCell.Text = vbNullString Then
    dwnTime.ListAutoValidate = False
    dwnHrs.ListAutoValidate = False
    dwnEarning.ListAutoValidate = False
    dwnService.ListAutoValidate = False
    dwnEquipment.ListAutoValidate = False
    dwnCommodity.ListAutoValidate = False
    dwnLocation.ListAutoValidate = False
    dwnVessel.ListAutoValidate = False
    dwnCustomer.ListAutoValidate = False
    dwnExactEnd.ListAutoValidate = False
    'dwnlabor .ListAutoValidate = False
  Else
    dwnTime.ListAutoValidate = True
    dwnHrs.ListAutoValidate = True
    dwnEarning.ListAutoValidate = True
    dwnService.ListAutoValidate = True
    dwnEquipment.ListAutoValidate = True
    dwnCommodity.ListAutoValidate = True
    dwnLocation.ListAutoValidate = True
    dwnVessel.ListAutoValidate = True
    dwnCustomer.ListAutoValidate = True
    dwnExactEnd.ListAutoValidate = True
    'dwnlabor .ListAutoValidate = True
  End If
'Check for User ID - allow changes to same User Only
  CurrValue = SSDBGrid1.Columns(SSDBGrid1.Col).Value
  If SSDBGrid1.row > UBound(DiffSupervisor) Then
  'Do Nothing
  Else
    If DiffSupervisor(SSDBGrid1.row) = True And CurrValue <> PrevValue Then
      MsgBox "Can't Edit Employee Data that belongs to Supervisor " + Trim(SSDBGrid1.Columns(12).Value), vbInformation, "Authorization Required"
      SSDBGrid1.Columns(SSDBGrid1.Col).Value = Trim(PrevValue)
    End If
  End If
  
  'Earning_Type (Double Time) DT can be selected only when the Duration is 1 Hour
  If UCase(Trim(SSDBGrid1.Columns(4).Value)) = "DT" And SSDBGrid1.Columns(3).Value > 1 Then
      MsgBox "Double Time Can't be More than an Hour", vbInformation, "Validation Failure"
      SSDBGrid1.Columns(4).Value = ""
      SSDBGrid1.Col = 4
  End If
End Sub

'****************************************
'To revert back if different User Changes data
'****************************************
Private Sub SSDBGrid1_Click()

Dim start As String
Dim reg As String
'Dim bil As String
Dim srv As String
Dim eq As String
Dim comm As String
Dim loc As String
Dim ves As String
Dim cust As String
Dim sup As String
Dim spec As String
Dim rm As String
Dim lab As String
  If SSDBGrid1.Col > 0 And SSDBGrid1.Columns(0).Value = "" Then
        SSDBGrid1.Columns(0).Value = SSDBGrid1.row + 1
        SSDBGrid1.row = SSDBGrid1.row - 1
        start = SSDBGrid1.Columns(2).Value
        reg = SSDBGrid1.Columns(4).Value
        'bill = SSDBGrid1.Columns(4).Value
        srv = SSDBGrid1.Columns(6).Value
        eq = SSDBGrid1.Columns(7).Value
        comm = SSDBGrid1.Columns(8).Value
        loc = SSDBGrid1.Columns(9).Value
        ves = SSDBGrid1.Columns(10).Value
        cust = SSDBGrid1.Columns(11).Value
        sup = SSDBGrid1.Columns(12).Value
        spec = SSDBGrid1.Columns(13).Value
        rm = SSDBGrid1.Columns(14).Value
        lab = SSDBGrid1.Columns(16).Value
        
        
        SSDBGrid1.row = SSDBGrid1.row + 1
        SSDBGrid1.Columns(1).Value = start
        SSDBGrid1.Columns(4).Value = reg
        SSDBGrid1.Columns(6).Value = "9765"
'        SSDBGrid1.Columns(6).Value = srv
        SSDBGrid1.Columns(7).Value = eq
        SSDBGrid1.Columns(8).Value = comm
        SSDBGrid1.Columns(9).Value = loc
        SSDBGrid1.Columns(10).Value = ves
        SSDBGrid1.Columns(11).Value = cust
        'SSDBGrid1.Columns(12).Value = sup
        SSDBGrid1.Columns(13).Value = spec
        SSDBGrid1.Columns(14).Value = rm
        SSDBGrid1.Columns(16).Value = lab
        SSDBGrid1.Columns(12).Value = UserID
    End If
  If SSDBGrid1.Col <> -1 Then
    PrevValue = SSDBGrid1.Columns(SSDBGrid1.Col).Value
  End If
  cmdUpdate.Enabled = True
  
End Sub

'****************************************
'To Validate Certain Criteria and to place Defaults on Grid
'****************************************
Private Sub SSDBGrid1_RowColChange(ByVal LastRow As Variant, ByVal LastCol As Integer)
    
    Dim dsLastRs As Object
    Dim sqlStmt As String
    Dim dsSHIP As Object
    Dim sRow As String
    Dim sStart As String
    
    
  'On Error GoTo ErrHandler
  'To revert back if different User Changes data
    If SSDBGrid1.Col <> -1 Then
        PrevValue = SSDBGrid1.Columns(SSDBGrid1.Col).Value
    End If

  'To Show the Drop Down List as soon as the Column gets focus
    If SSDBGrid1.Col = 1 Then
        SSDBGrid1.DroppedDown = False
    Else
        SSDBGrid1.DroppedDown = True
    End If

    If LineRun = True Then
        If SSDBGrid1.Col > 1 And Trim(SSDBGrid1.Columns(1).Value) <> vbNullString And Not IsNull(SSDBGrid1.Columns(1).Value) Then
            FindEndTime
        Else
            'Do Nothing
        End If
    End If
    
    
    
    'Default Values for GUARD - entered by  William Stansbury
    'If UCase(Trim(UserID)) = " E002047" Then
    '    SSDBGrid1.Columns(5).Value = False
    '    SSDBGrid1.Columns(7).Value = "0"
    '    SSDBGrid1.Columns(8).Value = "0"
    '    SSDBGrid1.Columns(10).Value = "0"
    '    SSDBGrid1.Columns(11).Value = "0"
    '    SSDBGrid1.Columns(12).Value = UserID
    'End If

 
  'To Handle Lunch Hour
    If SSDBGrid1.Col > 4 And UCase(Trim(SSDBGrid1.Columns(4).Value)) = "LU" Then
        SSDBGrid1.Columns(5).Value = False
        SSDBGrid1.Columns(7).Value = ""
        SSDBGrid1.Columns(8).Value = ""
        SSDBGrid1.Columns(9).Value = ""
        SSDBGrid1.Columns(10).Value = ""
        SSDBGrid1.Columns(11).Value = ""
        SSDBGrid1.Columns(13).Value = ""
        If Trim(SSDBGrid1.Columns(2).Value) = vbNullString Then
            SSDBGrid1.Columns(3).Value = "1.00"
            SSDBGrid1.Columns(2).Value = FindEndTime
        End If

        SSDBGrid1.Columns(6).DropDownHwnd = False
        SSDBGrid1.Columns(7).DropDownHwnd = False
        SSDBGrid1.Columns(8).DropDownHwnd = False
        SSDBGrid1.Columns(9).DropDownHwnd = False
        SSDBGrid1.Columns(10).DropDownHwnd = False
        SSDBGrid1.Columns(11).DropDownHwnd = False
        SSDBGrid1.Columns(13).DropDownHwnd = False
        SSDBGrid1.Columns(16).DropDownHwnd = False
    Else
        SSDBGrid1.Columns(6).DropDownHwnd = dwnService.hWnd
        SSDBGrid1.Columns(7).DropDownHwnd = dwnEquipment.hWnd
        SSDBGrid1.Columns(8).DropDownHwnd = dwnCommodity.hWnd
        SSDBGrid1.Columns(9).DropDownHwnd = dwnLocation.hWnd
        SSDBGrid1.Columns(10).DropDownHwnd = dwnVessel.hWnd
        SSDBGrid1.Columns(11).DropDownHwnd = dwnCustomer.hWnd
        SSDBGrid1.Columns(13).DropDownHwnd = dwnSpec.hWnd
        'SSDBGrid1.Columns(16).DropDownHwnd = dwnlabor .hWnd
    End If

    'For Call-in Employees, default Hrs is 4.
    If SSDBGrid1.Col > 4 And UCase(Trim(SSDBGrid1.Columns(4).Value)) = "CALL-IN" Then
        SSDBGrid1.Columns(3).Value = "4.00"
        If IsNull(SSDBGrid1.Columns(2).Value) Or Trim(SSDBGrid1.Columns(2).Value) = vbNullString Then
            'D o Nothing
        Else
            SSDBGrid1.Columns(15).Value = SSDBGrid1.Columns(2).Value
        End If
        SSDBGrid1.Columns(2).Value = FindEndTime
    End If
' Pawan.....10/25/2001....updated as per the requirement of Mr. Pat Hemphill
    ''To Filter Ships based on Ship_Flag in Service Table
    'If SSDBGrid1.Col > 6 And Trim(SSDBGrid1.Columns(6).Value) <> vbNullString Then

        'Sqlstmt = "Select Ship_Flag from Service where Service_Code = " + SSDBGrid1.Columns(6).Value
        'Set dsSHIP = OraDatabase.DBCreateDynaset(Sqlstmt, 0&)
        'If dsSHIP.Fields("Ship_Flag").Value = "Y" Then
            ''Show All Ships
            'SSDBGrid1.Columns(10).DropDownHwnd = dwnVessel.hWnd
        'Else
            ''Show Only N/A (0)
            'SSDBGrid1.Columns(10).Value = "-1"
            'SSDBGrid1.Columns(10).DropDownHwnd = False
        'End If
    'End If
' Pawan.....10/25/2001....


'        If SSDBGrid1.Columns(5).Value = -1 Then
'            Sqlstmt = "SELECT * FROM SERVICE_LABOR_RATE_TYPE WHERE SERVICE_CODE ='" & SSDBGrid1.Columns(6).Value & "'"
'            Set dsSERVICE = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
'            If OraDatabaseBNI.LastServerErr = 0 And dsSERVICE.RecordCount > 0 Then
'                SSDBGrid1.Columns(16).Value = "" & dsSERVICE.Fields("LABOR_TYPE").Value
'             End If
'        Else
'            SSDBGrid1.Columns(16).Value = ""
'        End If
                
     
     
    'To Get Previous Row's Values as default for the Current Row
       If Trim$(SSDBGrid1.ActiveCell.Text) = vbNullString And LastCol = 16 And UCase(Trim(SSDBGrid1.Columns(4).Value)) <> "LU" Then
      
        If Trim(PrvEnd) = vbNullString Or Trim(PrvReg) = vbNullString Or Trim(PrvComm) = vbNullString Or Trim(PrvLoc) = vbNullString _
            Or Trim(PrvEquip) = vbNullString Or Trim(PrvSvc) = vbNullString Or Trim(PrvCust) = vbNullString _
            Or Trim(PrvSpec) = vbNullString Or Trim(PrvLabor) = vbNullString Then
            'Take the Previous Row's Values from Database and Store


            sqlStmt = " Select End_Time, Earning_Type_Id, Service_Code, Equipment_Id, Commodity_Code, Location_ID, Vessel_Id,Customer_ID," _
                    & " Special_Code,labor_type from hourly_detail where Hire_Date=to_date('" + SSDateCombo1.Text + " ','mm/dd/yyyy') and " _
                    & " Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'and Row_Number in " _
                    & " (select max(Row_Number) from Hourly_detail where Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and " _
                    & " Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))"

            Set dsLastRs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If dsLastRs.EOF And dsLastRs.BOF Then
                'Do Nothing

            Else
                If Trim(PrvEnd) = vbNullString Then
                    PrvEnd = Format(dsLastRs.Fields("End_Time").Value, "hh:nnAM/PM")
                End If

                If Trim(dsLastRs.Fields("Earning_Type_Id").Value) <> vbNullString And Not IsNull(dsLastRs.Fields("Earning_Type_Id").Value) Then
                    PrvReg = dsLastRs.Fields("Earning_Type_Id").Value
                Else
                    PrvReg = ""
                End If

                If Trim(dsLastRs.Fields("Equipment_Id").Value) <> vbNullString And Not IsNull(dsLastRs.Fields("Equipment_Id").Value) Then
                    PrvEquip = dsLastRs.Fields("Equipment_Id").Value
                Else
                    PrvEquip = 0
                End If

                If Trim(dsLastRs.Fields("Commodity_Code").Value) <> vbNullString And Not IsNull(dsLastRs.Fields("Commodity_Code").Value) Then
                    PrvComm = dsLastRs.Fields("Commodity_Code").Value
                Else
                    PrvComm = 0
                End If

                If Trim(dsLastRs.Fields("Location_ID").Value) <> vbNullString And Not IsNull(dsLastRs.Fields("Location_ID").Value) Then
                    PrvLoc = dsLastRs.Fields("Location_ID").Value
                Else
                    PrvLoc = ""
                End If

                If Trim(dsLastRs.Fields("Service_Code").Value) <> vbNullString And Not IsNull(dsLastRs.Fields("Service_Code").Value) Then
                    PrvSvc = dsLastRs.Fields("Service_Code").Value
                Else
                    PrvSvc = 0
                End If

                If Trim(dsLastRs.Fields("Customer_ID").Value) <> vbNullString And Not IsNull(dsLastRs.Fields("Customer_ID").Value) Then
                    PrvCust = dsLastRs.Fields("Customer_ID").Value
                Else
                    PrvCust = 0
                End If

                If Trim(dsLastRs.Fields("VESSEL_ID").Value) <> vbNullString And Not IsNull(dsLastRs.Fields("VESSEL_ID").Value) Then
                    PrvShip = dsLastRs.Fields("VESSEL_ID").Value
                Else
                    PrvShip = -1
                End If

                If Trim(dsLastRs.Fields("Special_Code").Value) <> vbNullString And Not IsNull(dsLastRs.Fields("Special_Code").Value) Then
                    PrvSpec = dsLastRs.Fields("Special_Code").Value
                Else
                    PrvSpec = ""
                End If
                
                If Trim(dsLastRs.Fields("labor_type").Value) <> vbNullString And Not IsNull(dsLastRs.Fields("LABOR_TYPE").Value) Then
                    PrvLabor = dsLastRs.Fields("LABOR_TYPE").Value
                Else
                    PrvLabor = ""
                End If
                
            End If
            Set dsLastRs = Nothing
        End If

    'If Duration is entered and End Time is found then PrvEnd will be "" after Saving that Record
        If Trim(PrvEnd) = vbNullString Then
            sqlStmt = " Select End_Time from hourly_detail where Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and " _
                    & " Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'" _
                    & " and Row_Number in (select max(Row_Number) from Hourly_detail where " _
                    & " Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and " _
                    & " Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))"

            Set dsLastRs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            PrvEnd = Format(dsLastRs.Fields("End_Time").Value, "hh:nnAM/PM")
            Set dsLastRs = Nothing
        End If
        If SSDBGrid1.Columns(5).Value = -1 Then PrvLabor = ""
        
    
        SSDBGrid1.MoveLast
        sRow = Trim(CStr(SSDBGrid1.rows + 1))
        SSDBGrid1.AddItem sRow + Chr(9) + PrvEnd + Chr(9) + "" + Chr(9) + "" + Chr(9) + PrvReg + Chr(9) + "0" + Chr(9) + _
                          PrvSvc + Chr(9) + PrvEquip + Chr(9) + PrvComm + Chr(9) + PrvLoc + Chr(9) + PrvShip + Chr(9) + PrvCust + Chr(9) + _
                          UserID + Chr(9) + PrvSpec + Chr(9) + Chr(9) + Chr(9) + PrvLabor ', SSDBGrid1.Rows
        SSDBGrid1.MoveLast
        SSDBGrid1.Refresh
    
      ReDim Preserve DiffSupervisor(SSDBGrid1.rows) As Boolean
      DiffSupervisor(SSDBGrid1.rows) = False
      SendKeys "{right}"
      SendKeys "{right}"
      SendKeys "{Left}"
   
  End If

  'To Find the difference between Start and End Time for Duration Column
  If SSDBGrid1.Col >= 4 Then Duration

  Exit Sub
ErrHandler:
If Err.Number = 94 Then
  MsgBox "Invalid Use of Null"
Else
  MsgBox Err.Description + " " + Str(Err.Number)
End If


End Sub

'****************************************
'To Display Duration or the End Time
'****************************************
Private Sub Duration()
  'To Find the difference between Start and End Time for Duration Column
  If SSDBGrid1.Col >= 4 And Trim(SSDBGrid1.Columns(1).Value) <> vbNullString And Trim(SSDBGrid1.Columns(2).Value) <> vbNullString Then
    SSDBGrid1.Columns(3).Value = FindDuration(SSDBGrid1.Columns(1).Value, SSDBGrid1.Columns(2).Value)
  'To Find the End Time from the Given Start Time and Duration
  ElseIf Trim(SSDBGrid1.Columns(1).Value) <> vbNullString Then
    If SSDBGrid1.Columns(3).Value <> 0 Then
      SSDBGrid1.Columns(2).Value = FindEndTime
      PrvEnd = SSDBGrid1.Columns(2).Value
    Else
      SSDBGrid1.Columns(2).Value = SSDBGrid1.Columns(1).Value
      PrvEnd = SSDBGrid1.Columns(2).Value
    End If
'  ElseIf Trim(SSDBGrid1.Columns(2).Value) <> vbNullString And SSDBGrid1.Columns(3).Value <> vbNullString Then
'    SSDBGrid1.Columns(1).Value = FindStartTime
  End If
End Sub

'****************************************
'To Find the End Time from Duration and Start Time
'****************************************
Private Function FindEndTime() As String
  Dim TZ1 As String, TotalHr As Integer, TotalMn As Integer
  Dim HR1 As Integer, HR2 As Integer, Pos As Integer
  Dim MN1 As Integer, MN2 As Integer, NewEnd As String
  Dim LineEarn As String, LineDur As String, LineSvc As String
  'Only for Line Runners
  If LineRun = True Then
    TZ1 = UCase(Right(Trim(SSDBGrid1.Columns(1).Value), 2))
    HR1 = CInt(Left(Trim(SSDBGrid1.Columns(1).Value), 2))
    MN1 = CInt(Mid(Trim(SSDBGrid1.Columns(1).Value), 4, 2))
    If HR1 = 5 And TZ1 = "PM" Then      '5Pm - 6Pm is OT
      If Len(Trim(Str(MN1))) < 2 Then
        NewEnd = "06" + ":0" + Trim(Str(MN1)) + Trim(TZ1)
      Else
        NewEnd = "06" + ":" + Trim(Str(MN1)) + Trim(TZ1)
      End If
      LineEarn = "OT"
      LineDur = "1.0"
      LineSvc = "1223"
    ElseIf HR1 = 6 Then                 '6PM - 7PM is DT. 6Am -7Am is DT
      If Len(Trim(Str(MN1))) < 2 Then
        NewEnd = "07" + ":0" + Trim(Str(MN1)) + Trim(TZ1)
      Else
        NewEnd = "07" + ":" + Trim(Str(MN1)) + Trim(TZ1)
      End If
      LineEarn = "DT"
      LineDur = "1.0"
      LineSvc = "1223"
    ElseIf HR1 = 7 And TZ1 = "AM" Then  '7AM - 8AM is OT
      If Len(Trim(Str(MN1))) < 2 Then
        NewEnd = "08" + ":0" + Trim(Str(MN1)) + Trim(TZ1)
      Else
        NewEnd = "08" + ":" + Trim(Str(MN1)) + Trim(TZ1)
      End If
      LineEarn = "OT"
      LineDur = "1.0"
      LineSvc = "1223"
    ElseIf TZ1 = "PM" Then              '7PM - 6AM is 3 HR OT
      If HR1 >= 7 And HR1 <> 12 Then
        HR1 = HR1 + 3
        If HR1 >= 12 Then HR1 = HR1 - 12: TZ1 = "AM"
        LineEarn = "OT"
        LineDur = "3.0"
        LineSvc = "1222"
        If Len(Trim(Str(HR1))) < 2 Then NewEnd = "0" + Trim(Str(HR1)) Else NewEnd = Trim(Str(HR1))
        If Len(Trim(Str(MN1))) < 2 Then
          NewEnd = NewEnd + ":0" + Trim(Str(MN1)) + Trim(TZ1)
        Else
          NewEnd = NewEnd + ":" + Trim(Str(MN1)) + Trim(TZ1)
        End If
      ElseIf HR1 = 12 Or HR1 <= 5 Then      '12PM - 5PM is REG
        HR2 = HR1 + 1
        If HR2 >= 12 Then
          If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
        End If
        If HR2 > 12 Then HR2 = HR2 - 12
        If Len(Trim(Str(HR2))) < 2 Then NewEnd = "0" + Trim(Str(HR2)) Else NewEnd = Trim(Str(HR2))
        If Len(Trim(Str(MN1))) < 2 Then
          NewEnd = NewEnd + ":0" + Trim(Str(MN1)) + Trim(TZ1)
        Else
          NewEnd = NewEnd + ":" + Trim(Str(MN1)) + Trim(TZ1)
        End If
        LineEarn = "REG"
        LineDur = "1.0"
        LineSvc = "1221"
      End If
    ElseIf TZ1 = "AM" Then
      If HR1 <= 6 Or HR1 = 12 Then
        HR1 = HR1 + 3
        If HR1 >= 12 Then HR1 = HR1 - 12
        LineEarn = "OT"
        LineDur = "3.0"
        LineSvc = "1222"
        If Len(Trim(Str(HR1))) < 2 Then NewEnd = "0" + Trim(Str(HR1)) Else NewEnd = Trim(Str(HR1))
        If Len(Trim(Str(MN1))) < 2 Then
          NewEnd = NewEnd + ":0" + Trim(Str(MN1)) + Trim(TZ1)
        Else
          NewEnd = NewEnd + ":" + Trim(Str(MN1)) + Trim(TZ1)
        End If
      ElseIf HR1 >= 8 And HR1 <= 11 Then    '8Am - 11Am is REG
        HR2 = HR1 + 1
        If HR2 >= 12 Then
          If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
        End If
        If HR2 > 12 Then HR2 = HR2 - 12
        If Len(Trim(Str(HR2))) < 2 Then NewEnd = "0" + Trim(Str(HR2)) Else NewEnd = Trim(Str(HR2))
        If Len(Trim(Str(MN1))) < 2 Then
          NewEnd = NewEnd + ":0" + Trim(Str(MN1)) + Trim(TZ1)
        Else
          NewEnd = NewEnd + ":" + Trim(Str(MN1)) + Trim(TZ1)
        End If
        LineEarn = "REG"
        LineDur = "1.0"
        LineSvc = "1221"
      End If
    End If
    SSDBGrid1.Columns(2).Value = NewEnd
    SSDBGrid1.Columns(3).Value = LineDur
    SSDBGrid1.Columns(4).Value = LineEarn
    SSDBGrid1.Columns(6).Value = LineSvc
    Exit Function
  End If
  
  If Trim(SSDBGrid1.Columns(3).Value) = vbNullString Then
    Exit Function
  End If
  TZ1 = UCase(Right(Trim(SSDBGrid1.Columns(1).Value), 2))

  HR1 = CInt(Left(Trim(SSDBGrid1.Columns(1).Value), 2))
  MN1 = CInt(Mid(Trim(SSDBGrid1.Columns(1).Value), 4, 2))
  
  Pos = InStr(SSDBGrid1.Columns(3).Value, ".")
  HR2 = CInt(Left(Trim(SSDBGrid1.Columns(3).Value), Pos - 1))
  MN2 = CInt(Mid(Trim(SSDBGrid1.Columns(3).Value), Pos + 1))
  
  'Find Total Hours
  TotalHr = HR1 + HR2
  If TotalHr > 12 Then
    TotalHr = TotalHr - 12
    If HR1 = 12 Then    'Don't Change AM/PM if already Start Time is 12
    'Do Nothing
    Else
      If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
    End If
  ElseIf TotalHr = 12 And HR1 <> 12 Then
    If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
  End If
  
  'Find Total Minutes
  'Duration in 1/2 an Hour << Start
  'If MN2 <= 25 And MN2 > 0 Then
  '  MN2 = 15
  'ElseIf MN2 <= 50 And MN2 > 25 Then
  '  MN2 = 30
  'ElseIf MN2 <= 75 And MN2 > 50 Then
  '  MN2 = 45
  'ElseIf MN2 > 75 Then
  '  MN2 = 60
  'Duration in 1/2 an Hour >> End
  If MN2 = 5 Then
    MN2 = 30
  'Duration in 1/2 an Hour << Start
  'ElseIf Len(Trim(Str(MN2))) = 1 Then
  '  MN2 = MN2 * 10
  'Duration in 1/2 an Hour >> End
  End If
  TotalMn = MN1 + MN2
  If TotalMn >= 60 Then
    TotalHr = TotalHr + 1
    If TotalHr > 12 Then TotalHr = TotalHr - 12
    If TotalHr = 12 Then
      If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
    End If
    TotalMn = TotalMn - 60
'    If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
  End If
  
  If Len(Trim(Str(TotalHr))) = 1 And Len(Trim(Str(TotalMn))) = 1 Then
    FindEndTime = "0" + Trim(Str(TotalHr)) + ":0" + Trim(Str(TotalMn)) + TZ1
  ElseIf Len(Trim(Str(TotalHr))) = 1 And Len(Trim(Str(TotalMn))) = 2 Then
    FindEndTime = "0" + Trim(Str(TotalHr)) + ":" + Trim(Str(TotalMn)) + TZ1
  ElseIf Len(Trim(Str(TotalHr))) = 2 And Len(Trim(Str(TotalMn))) = 1 Then
    FindEndTime = Trim(Str(TotalHr)) + ":0" + Trim(Str(TotalMn)) + TZ1
  Else
    FindEndTime = Trim(Str(TotalHr)) + ":" + Trim(Str(TotalMn)) + TZ1
  End If
End Function

'****************************************
'To find the Total Hours Worked for the Day for each Employee - to display in Label
'****************************************
Private Function FindTotalHrs() As Single
  Dim totalHrs As Single, i As Integer
  totalHrs = 0
  For i = 0 To SSDBGrid1.rows - 1
    SSDBGrid1.row = i
    If Trim(SSDBGrid1.Columns(3).Value) <> vbNullString And UCase(Trim(SSDBGrid1.Columns(4).Value)) <> "LU" Then
      totalHrs = totalHrs + CSng(SSDBGrid1.Columns(3).Value)
    End If
  Next
  FindTotalHrs = totalHrs
  SSDBGrid1.MoveFirst
End Function

'****************************************
'To Find the Number of Hours between Start and End Time
'****************************************
Private Function FindDuration(myStart As String, myEnd As String) As String
  Dim sTime As Date
  Dim eTime As Date
  Dim mins As Integer
  Dim lunchCutStart As Date
  Dim lunchCutEnd As Date
  
  lunchCutStart = "11:00:00 AM"
  lunchCutEnd = "02:00:00 PM"
  
  sTime = myStart
  eTime = myEnd
  
  mins = DateDiff("n", sTime, eTime)
  
  If mins < 0 Then  'overnight
    mins = 24 * 60 + mins
    If sTime < lunchCutStart Or eTime >= lunchCutEnd Then
        mins = mins - 60    'take off 60 min lunch hour
    End If
  ElseIf sTime < lunchCutStart And eTime >= lunchCutEnd Then
    mins = mins - 60    'take off 60 min lunch hour
  End If
    
  FindDuration = Str(mins / 60)
    
End Function

'****************************************
'To Validate Time Based on length
'****************************************
Private Function ValidateTime(myTime As String) As Boolean
  If Len(Trim(myTime)) < 7 Then
    ValidateTime = True
  End If
End Function

'****************************************
'To Get Proper Row Number for each Row on the Grid
'****************************************
Private Sub CheckRowNo(myRowNo As Integer, myEmpID As String)
  Dim RowRS As Object
  Dim myRowSQL As String
  myRowSQL = "Select Row_Number from Hourly_Detail where Employee_ID = '" + myEmpID + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') Order by Row_Number"
  Set RowRS = OraDatabase.DBCreateDynaset(myRowSQL, 0&)
  If RowRS.BOF And RowRS.EOF Then
    'Do Nothing
    LastRow = 0
  Else
    RowRS.MoveLast
    LastRow = RowRS.Fields("Row_Number").Value + 1
  End If
  RowRS.Close
End Sub

'****************************************
'To Set Proper Row Number for each Row on the Grid
'****************************************
Private Sub UpdateRowNo(myEmpID As String)
  Dim RowRS As Object
  Dim myRowSQL As String, InitRow As Integer
  
  OraSession.BeginTrans
  
  myRowSQL = "Select Row_Number from Hourly_Detail where Employee_ID = '" + myEmpID + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') order by Row_Number"
  Set RowRS = OraDatabase.DBCreateDynaset(myRowSQL, 0&)
  If RowRS.BOF And RowRS.EOF Then
   'Do Nothing
  Else
    RowRS.MoveLast
    If RowRS.RecordCount = 1 And RowRS.Fields("row_Number").Value <> 1 Then  'Only One Row
      myRowSQL = "Update Hourly_Detail Set Row_Number = 1 where Employee_ID = '" + myEmpID + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
      OraDatabase.ExecuteSQL myRowSQL
    ElseIf RowRS.RecordCount > 1 Then
      RowRS.MoveFirst
      InitRow = RowRS.Fields("row_Number").Value
      If InitRow <> 1 Then
        myRowSQL = "Update Hourly_Detail Set Row_Number = '1' where Employee_ID = '" + myEmpID + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
        OraDatabase.ExecuteSQL myRowSQL
      End If
      Do While Not RowRS.EOF
        RowRS.MoveNext
        If RowRS.EOF Then
            OraSession.CommitTrans
            Exit Sub
        End If
        
        If (InitRow + 1) = RowRS.Fields("row_Number").Value Then
          'Do Nothing
        Else
          myRowSQL = "Update Hourly_Detail Set Row_Number = '" + Trim(Str(InitRow + 1)) + "' where Employee_ID = '" + myEmpID + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
          OraDatabase.ExecuteSQL myRowSQL
        End If
        InitRow = RowRS.Fields("row_Number").Value
      Loop
    End If
  End If
  RowRS.Close
  
  OraSession.CommitTrans
    
    
End Sub
Sub UpdateRowNum(empId As String)
    Dim jRec As Integer
    Dim RowRS As Object
    Dim sqlStmt As String
               
    OraSession.BeginTrans
    
    sqlStmt = " Select Row_Number from Hourly_Detail where Employee_ID = '" & empId & "' and " _
            & " Hire_Date = to_date('" & Format(SSDateCombo1.Text, "MM/DD/YYYY") & "','mm/dd/yyyy') order by Row_Number"
    Set RowRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If RowRS.RecordCount > 0 Then
        If RowRS.RecordCount = 1 And RowRS.Fields("row_Number").Value = 1 Then
         OraSession.CommitTrans
         Exit Sub
        End If
        
        For jRec = 1 To RowRS.RecordCount
            RowRS.Edit
            RowRS.Fields("ROW_NUMBER").Value = jRec
            RowRS.Update
            RowRS.MoveNext
        Next jRec
    End If
    
    OraSession.CommitTrans
    
End Sub
'****************************************
'To Check for Blank Entry in Column on the Grid
'****************************************
Private Function CheckForNullData()
  If Trim(SSDBGrid1.Columns(1).Value) = vbNullString Then
    MsgBox "Start Time Should not be blank", vbInformation, "Insufficient Data"
    SSDBGrid1.Col = 1
    CheckForNullData = True
    Exit Function
  ElseIf Trim(SSDBGrid1.Columns(2).Value) = vbNullString Then
    MsgBox "End Time Should not be blank", vbInformation, "Insufficient Data"
    SSDBGrid1.Col = 2
    CheckForNullData = True
    Exit Function
  ElseIf Trim(SSDBGrid1.Columns(4).Value) = vbNullString Then
    MsgBox "Earning Type ID Should not be blank", vbInformation, "Insufficient Data"
    SSDBGrid1.Col = 4
    CheckForNullData = True
    Exit Function
  ElseIf Trim(SSDBGrid1.Columns(6).Value) = vbNullString Then
    MsgBox "Service Code Should not be blank", vbInformation, "Insufficient Data"
    SSDBGrid1.Col = 6
    CheckForNullData = True
    Exit Function
  ElseIf Trim(SSDBGrid1.Columns(7).Value) = vbNullString Then
    MsgBox "Equipment Code Should not be blank", vbInformation, "Insufficient Data"
    SSDBGrid1.Col = 7
    CheckForNullData = True
    Exit Function
  ElseIf Trim(SSDBGrid1.Columns(8).Value) = vbNullString Then
    MsgBox "Commodity Code Should not be blank", vbInformation, "Insufficient Data"
    SSDBGrid1.Col = 8
    CheckForNullData = True
    Exit Function
  ElseIf Trim(SSDBGrid1.Columns(9).Value) = vbNullString Then
    MsgBox "Location ID Should not be blank", vbInformation, "Insufficient Data"
    SSDBGrid1.Col = 9
    CheckForNullData = True
    Exit Function
  ElseIf Trim(SSDBGrid1.Columns(10).Value) = vbNullString Then
    MsgBox "Vessel ID Should not be blank", vbInformation, "Insufficient Data"
    SSDBGrid1.Col = 10
    CheckForNullData = True
    Exit Function
  ElseIf Trim(SSDBGrid1.Columns(11).Value) = vbNullString Then
    MsgBox "Customer Code Should not be blank", vbInformation, "Insufficient Data"
    SSDBGrid1.Col = 11
    CheckForNullData = True
    Exit Function
  '2853 3/29/2007 Rudy: nice function, but it's not called from here, or anywhere!
  ElseIf Trim(SSDBGrid1.Columns(16).Value) = vbNullString Then
    MsgBox "Labor Type Should not be blank", vbInformation, "Insufficient Data"
    SSDBGrid1.Col = 16
    CheckForNullData = True
    Exit Function
  End If
End Function

'****************************************
'To Store Blank Entries in Column on the Grid for the Previous Values
'****************************************
Private Sub SetDefault()
  PrvEnd = ""
  PrvReg = ""
  PrvSvc = ""
  PrvEquip = ""
  PrvCust = ""
  PrvSpec = ""
  PrvShip = ""
  PrvLabor = ""
  
  '2853 3/29/2007 Rudy: these 2 seem to carryover?  Ideally should reinit, but bus. logic?
  'PrvComm = ""
  'PrvLoc = ""
End Sub

'****************************************
'To Display Double Entry Report for Current Date before Exit
'****************************************
Private Sub DoubleEntryReportDate()
  Dim myStart As Date, myEnd As Date, DblRS As Object, DblEmpRS As Object
  Dim Total As Integer, i As Integer, arrPrintFlag() As Boolean
  Dim myEmpIDSQL As String, myHireSQL As String, AlreadyPrinted As Boolean
  Dim SuprName2 As String, mySupr As String
    
  'Select all the Employees hired for the day
  myEmpIDSQL = "Select distinct a.Employee_ID from Hourly_Detail a, Daily_Hire_List b where a.Employee_ID = b.Employee_ID and a.Hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and b.Hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
  Set DblEmpRS = OraDatabase.DBCreateDynaset(myEmpIDSQL, 0&)
  If DblEmpRS.BOF And DblEmpRS.EOF Then
    'Do Nothing
    HideDblEntry = True
  Else
    'Print the Report Header Section
    frmDblEntryRpt.rteDblRpt.Text = String(81, "_") + vbCrLf
    frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "DIAMOND STATE PORT CORPORATION" + vbCrLf
    frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + String(16, " ") + "DOUBLE ENTRY EXCEPTION REPORT   " + Str(Date) + vbCrLf
    frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + String(80, "_") + vbCrLf
    frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "EMPLOYEES WHOSE START AND END TIME OVERLAP" + vbCrLf + vbCrLf
    
    DblEmpRS.MoveFirst
    'For each Employee, check whether Double Entry is made
    Do While Not DblEmpRS.EOF
      AlreadyPrinted = False    'Print the Group Header for Each Employee
      'Get the details from Hourly_detail for the employee
      myHireSQL = "Select a.Start_Time, a.End_Time, a.Row_Number, a.User_ID, a.Employee_ID, b.Employee_Name from Hourly_Detail a, Employee b where a.Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and a.Employee_ID = '" + DblEmpRS.Fields("Employee_ID").Value + "' and a.Employee_ID = b.Employee_ID Order by a.Row_Number"
      Set DblRS = OraDatabase.DBCreateDynaset(myHireSQL, 0&)
      If DblRS.BOF And DblRS.EOF Then
        'Do Nothing
        HideDblEntry = True
      ElseIf DblRS.RecordCount = 1 Then
        'No Comparison for Overlapping Hours since only one record
        HideDblEntry = True
      Else
        Dim myEmpID As String
        myEmpID = DblRS.Fields("Employee_ID").Value
        'Update Row Number
        Call UpdateRowNo(myEmpID)
        DblRS.Refresh     'Refresh recordset so that the Row Number gets updated
        'Get the Start and End Time and Check for Double Entry
        DblRS.MoveLast
        Total = DblRS.RecordCount
        ReDim arrPrintFlag(Total) As Boolean
        For i = 1 To Total
          DblRS.MoveTo i
          If IsNull(DblRS.Fields("Start_Time").Value) Or Trim(DblRS.Fields("Start_Time").Value) = vbNullString Then
            Exit Sub
          End If
          myStart = DblRS.Fields("Start_Time").Value
          If DblRS.Fields("End_Time").Value = vbNullString Or IsNull(DblRS.Fields("End_Time").Value) Then
            Exit For
          End If
          myEnd = DblRS.Fields("End_Time").Value
          mySupr = DblRS.Fields("User_ID").Value
          DblRS.MoveFirst
          Do While Not DblRS.EOF
            If i = DblRS.Fields("Row_Number").Value Then
              'Proceed with the remaining records
              DblRS.MoveNext
              If DblRS.EOF Then Exit Do
            ElseIf myStart >= DblRS.Fields("Start_Time").Value And myStart < DblRS.Fields("End_Time").Value And arrPrintFlag(DblRS.Fields("Row_Number").Value) = False Then
              'Get the User Name to be printed
              Dim SupRS As Object, SuprName As String
              Set SupRS = OraDatabase.DBCreateDynaset("Select * from LCS_User where User_ID = '" + DblRS.Fields("User_ID").Value + "'", 0&)
              If SupRS.BOF And SupRS.EOF Then
                SuprName = Space(16)
              Else
                SuprName = SupRS.Fields("User_Name").Value
                'If Overlapping between two Supervisors
                If UCase(Trim(mySupr)) <> UCase(Trim(SuprName)) Then
                  Set SupRS = OraDatabase.DBCreateDynaset("Select * from LCS_USER where User_ID = '" + mySupr + "'", 0&)
                  If SupRS.BOF And SupRS.EOF Then
                    SuprName2 = Space(16)
                  Else
                    SuprName2 = SupRS.Fields("User_Name").Value
                  End If
                End If
              End If
              If AlreadyPrinted = False Then
                'Print the Group Header Section
                OverlapEntry = True
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + vbCrLf + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + DblRS.Fields("Employee_Name").Value + "     " + DblRS.Fields("Employee_ID").Value + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + String(56, "_") + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + "DATE          START               END                   SUPR " + vbCrLf
                frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + String(56, "_") + vbCrLf
                AlreadyPrinted = True
              End If
              'Print the Details Section
              frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + SSDateCombo1.Text + "      " + Format(Str(myStart), "hh:nn am/pm") + "           " + Format(Str(myEnd), "hh:nn am/pm") + "           " + SuprName2 + vbCrLf
              frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + SSDateCombo1.Text + "      " + Format(Str(DblRS.Fields("Start_Time").Value), "hh:nn am/pm") + "           " + Format(Str(DblRS.Fields("End_Time").Value), "hh:nn am/pm") + "           " + SuprName + vbCrLf
              frmDblEntryRpt.rteDblRpt.Text = frmDblEntryRpt.rteDblRpt.Text + "     " + String(56, "_") + vbCrLf
              arrPrintFlag(i) = True
              DblRS.MoveNext
              If DblRS.EOF Then Exit Do
            Else
              DblRS.MoveNext
              If DblRS.EOF Then Exit Do
            End If
          Loop
        Next
      End If
      DblEmpRS.MoveNext
    Loop

    'Set Font for Header Section
    frmDblEntryRpt.rteDblRpt.SelStart = 83
    frmDblEntryRpt.rteDblRpt.SelLength = 32
    frmDblEntryRpt.rteDblRpt.SelAlignment = 2
    frmDblEntryRpt.rteDblRpt.SelFontSize = 16
  
    frmDblEntryRpt.rteDblRpt.SelStart = 117
    frmDblEntryRpt.rteDblRpt.SelLength = 56
    frmDblEntryRpt.rteDblRpt.SelAlignment = 2
    frmDblEntryRpt.rteDblRpt.SelFontSize = 12
  
    frmDblEntryRpt.rteDblRpt.SelStart = 253
    frmDblEntryRpt.rteDblRpt.SelLength = 50
    frmDblEntryRpt.rteDblRpt.SelAlignment = 2
    frmDblEntryRpt.rteDblRpt.SelFontSize = 11
    
    frmDblEntryRpt.rteDblRpt.SelStart = 300
    frmDblEntryRpt.rteDblRpt.SelLength = Len(frmDblEntryRpt.rteDblRpt.Text)
    frmDblEntryRpt.rteDblRpt.SelFontSize = 10
    
    frmDblEntryRpt.rteDblRpt.SelStart = 0
    frmDblEntryRpt.rteDblRpt.SelLength = 0
  End If
  'Show Report Only if it has Some Overlapping Hours for the Current Date
  If HideDblEntry = True And OverlapEntry = True Then
  'If HideDblEntry = false And OverlapEntry = True Then
    Me.Hide
    frmDblEntryRpt.Show
  End If
End Sub

'****************************************
'To take care of REGULAR to OT Hours conversion if more than 40
'****************************************
Private Sub UpdateREGOT()

End Sub

'****************************************
'To automatically convert REGULAR Hours over than 40 to OT
'****************************************
Private Sub ConvertREGtoOT()
  Dim CurrDay As Integer, WeekNo As String, mySQL As String, Over40RS As Object
  Dim RegRS As Object, ExcessHrs As Double, NewDur As Double, NewEnd As String
  Dim Start_Time As String, End_Time As String, NewRow As Integer, myrec As Integer
  Dim myEmpID As String, myHireDt As Date, myCommCd As Integer
  Dim myCustID As Integer, mySvcCd As Integer, myVessel As Long  ', myExp As String, myExpReason As String
  Dim myEquip As Integer, myLoc As String, myEnd As String, myBill As String
  Dim myUser As String, mySpec As String, myExactEnd As String
  Dim iYear As String
  
  iYear = Format(Now, "YYYY")
  
  'Get the Current Week No
  CurrDay = Format(SSDateCombo1.Text, "w")
  If CurrDay = 1 Then
    WeekNo = Str(Format(SSDateCombo1.Text, "ww") - 2)
  Else
    WeekNo = Str(Format(SSDateCombo1.Text, "ww") - 1)
  End If
  'Get Regular Hours for the employee for the whole week
  mySQL = "Select Sum(Duration) Total from hourly_detail where Earning_Type_Id = 'REG' and TO_CHAR(TRUNC(HIRE_DATE),'IW') = " + WeekNo + " and Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'"
  mySQL = mySQL & " AND HIRE_DATE >= TO_DATE('01/01/" & iYear & "','MM/DD/YYYY')"
  Set Over40RS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If Over40RS.EOF And Over40RS.BOF Then
    'Do Nothing
  Else
    Over40RS.MoveFirst  'Only One Record for the whole week
    If Over40RS.Fields("Total").Value > 40 Then
      'Conversion Process
      ExcessHrs = Over40RS.Fields("Total").Value - 40
      mySQL = "Select Row_Number, Hire_Date, Start_Time, End_Time, Duration, Earning_Type_Id, Employee_ID, Billing_Flag, Service_Code, Equipment_Id, Commodity_Code, Location_ID, Vessel_Id, Customer_ID, User_Id, Special_Code,Remark, Exact_end, Exception, Exception_Reason from hourly_detail where TO_CHAR(TRUNC(HIRE_DATE),'IW') = " + WeekNo
      mySQL = mySQL & " and Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' AND HIRE_DATE>=TO_DATE('01/01/" & iYear & "','MM/DD/YYYY')"
      mySQL = mySQL & " order by Hire_Date, Row_Number"
      Set RegRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
      If RegRS.EOF And RegRS.BOF Then
        'Do Nothing
      Else
        RegRS.MoveLast
        Do While Not RegRS.BOF
          If UCase(RegRS.Fields("Earning_Type_ID")) = "REG" Then
            If RegRS.Fields("Duration").Value < ExcessHrs Then
              ExcessHrs = ExcessHrs - RegRS.Fields("Duration").Value
              RegRS.Edit
              RegRS.Fields("Earning_Type_ID").Value = "OT"
              RegRS.Fields("Remark").Value = "System"
              RegRS.Update
            ElseIf RegRS.Fields("Duration").Value = ExcessHrs Then
              RegRS.Edit
              RegRS.Fields("Earning_Type_ID").Value = "OT"
              RegRS.Fields("Remark").Value = "System"
              RegRS.Update
              Exit Do
            Else
              NewDur = RegRS.Fields("Duration").Value - ExcessHrs
              Start_Time = Format(RegRS.Fields("Start_Time").Value, "hh:nnAM/PM")
              End_Time = Format(RegRS.Fields("End_Time").Value, "hh:nnAM/PM")
              NewEnd = GetEndTime(Start_Time, End_Time, NewDur)
              RegRS.Edit
              If IsNull(RegRS.Fields("End_Time").Value) Or Trim(RegRS.Fields("End_Time").Value) = vbNullString Then
                myEnd = ""
              Else
                myEnd = Format(RegRS.Fields("End_Time").Value, "mm/dd/yyyy hh:nnAM/PM")
              End If
              RegRS.Fields("End_Time").Value = Format(Str(RegRS.Fields("Hire_Date").Value), "MM/DD/YYYY") & " " & Format(NewEnd, "hh:nnAM/PM")
              RegRS.Fields("Duration").Value = NewDur
              RegRS.Fields("Remark").Value = "System"
              RegRS.Update
              OraSession.BeginTrans
              NewRow = RegRS.Fields("Row_Number").Value + 1
              
              myEmpID = RegRS.Fields("Employee_ID").Value
              myHireDt = RegRS.Fields("Hire_Date").Value
              If IsNull(RegRS.Fields("Commodity_Code").Value) Or Trim(RegRS.Fields("Commodity_Code").Value) = vbNullString Then
                myCommCd = 0
              Else
                myCommCd = RegRS.Fields("Commodity_Code").Value
              End If
              If IsNull(RegRS.Fields("Customer_ID").Value) Or Trim(RegRS.Fields("Customer_ID").Value) = vbNullString Then
                myCustID = 0
              Else
                myCustID = RegRS.Fields("Customer_ID").Value
              End If
              mySvcCd = RegRS.Fields("Service_Code").Value
              If IsNull(RegRS.Fields("Vessel_ID").Value) Or Trim(RegRS.Fields("Vessel_ID").Value) = vbNullString Then
                myVessel = 0
              Else
                myVessel = RegRS.Fields("Vessel_ID").Value
              End If
              If IsNull(RegRS.Fields("Equipment_ID").Value) Or Trim(RegRS.Fields("Equipment_ID").Value) = vbNullString Then
                myEquip = 0
              Else
                myEquip = RegRS.Fields("Equipment_ID").Value
              End If
              If IsNull(RegRS.Fields("Location_ID").Value) Or Trim(RegRS.Fields("Location_ID").Value) = vbNullString Then
                myLoc = ""
              Else
                myLoc = RegRS.Fields("Location_ID").Value
              End If
              myBill = RegRS.Fields("Billing_Flag").Value
              myUser = RegRS.Fields("User_ID").Value
              If IsNull(RegRS.Fields("Special_Code").Value) Or Trim(RegRS.Fields("Special_Code").Value) = vbNullString Then
                mySpec = ""
              Else
                mySpec = RegRS.Fields("Special_Code").Value
              End If
              If IsNull(RegRS.Fields("Exact_End").Value) Or Trim(RegRS.Fields("Exact_End").Value) = vbNullString Then
                myExactEnd = ""
              Else
                myExactEnd = RegRS.Fields("Exact_End").Value
              End If
              
              RegRS.AddNew
              RegRS.Fields("Hire_Date").Value = Format(myHireDt, "mm/dd/yyyy")
              RegRS.Fields("Employee_ID").Value = myEmpID
              RegRS.Fields("Row_Number").Value = NewRow
              RegRS.Fields("Commodity_Code").Value = myCommCd
              RegRS.Fields("Customer_ID").Value = myCustID
              RegRS.Fields("Earning_Type_ID").Value = "OT"
              RegRS.Fields("Service_Code").Value = mySvcCd
              RegRS.Fields("Vessel_ID").Value = myVessel
              RegRS.Fields("Equipment_ID").Value = myEquip
              RegRS.Fields("Location_ID").Value = myLoc
              RegRS.Fields("Start_Time").Value = Format(SSDateCombo1.Text, "mm/dd/yyyy") + " " + NewEnd
              If myEnd = "" Then
                'Do Nothing
              Else
                RegRS.Fields("End_Time").Value = myEnd
              End If
              RegRS.Fields("Duration").Value = ExcessHrs
              RegRS.Fields("Billing_Flag").Value = myBill
              RegRS.Fields("User_ID").Value = myUser
              RegRS.Fields("Special_Code").Value = mySpec
              'As Hourly_Detail, no exception. In Correction Screen, the value is "Y"
              RegRS.Fields("Exception").Value = "N"
              RegRS.Fields("Remark").Value = "System"
              'RegRS.Fields("Exception_Reason").Value = myExpReason
              If myExactEnd = "" Then
                'Do Nothing
              Else
                RegRS.Fields("Exact_End").Value = myExactEnd
              End If
              RegRS.Update
              If OraDatabase.LastServerErr = 0 Then
                OraSession.CommitTrans
              Else
                OraSession.Rollback
              End If
              Exit Do
            End If
          End If
          RegRS.MovePrevious
        Loop
      End If
    End If
  End If
End Sub

'****************************************
'To Get New End Time after converting from REG to OT
'****************************************
Private Function GetEndTime(Start_Time As String, End_Time As String, NewDur As Double) As String
  Dim StartHr As Integer, NewEndHr As Integer, LunchHr As Integer, ExcessHr As Integer
  Dim StartMn As Integer, ExcessMn As Double, NewEndMn As Integer, TZ2 As String
  Dim EndHr As String, EndMn As String, TZ1 As String, MoreHrs As Integer
  
  LunchHr = 0
  MoreHrs = 0
  If Trim(Start_Time) = vbNullString Or IsNull(Start_Time) Then
    Exit Function
  End If
  If Trim(End_Time) = vbNullString Or IsNull(End_Time) Then
    Exit Function
  End If
  StartHr = CInt(Left(Trim(Start_Time), 2))
  StartMn = CInt(Mid(Trim(Start_Time), 4, 2))
  TZ1 = UCase(Right(Trim(Start_Time), 2))
  TZ2 = UCase(Right(Trim(End_Time), 2))
  EndHr = CInt(Left(Trim(End_Time), 2))
  EndMn = CInt(Mid(Trim(End_Time), 4, 2))
  
  ExcessHr = Fix(NewDur)  'To get only the integer portion, truncating the decimal part
  ExcessMn = NewDur - ExcessHr
  If ExcessMn = 0 And StartMn <> 0 Then
    NewEndMn = StartMn
  ElseIf ExcessMn = 0 And StartMn = 0 Then
    NewEndMn = 0
  ElseIf ExcessMn <> 0 And StartMn = 0 Then
    NewEndMn = ExcessMn
  ElseIf ExcessMn <> 0 And StartMn <> 0 Then
    NewEndMn = 0
    MoreHrs = 1
  End If
  
  NewEndHr = StartHr + ExcessHr
  If NewEndHr >= 12 Then
    If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
  End If
  If NewEndHr > 12 Then NewEndHr = NewEndHr - 12
  
  If StartHr = 8 And StartMn = 0 And EndHr = 5 And EndMn = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    LunchHr = 1
  End If
  If StartHr = 8 And StartMn = 0 And EndHr = 6 And EndMn = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    LunchHr = 1
  End If
  If StartHr = 7 And StartMn = 0 And EndHr = 6 And EndMn = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    LunchHr = 1
  End If
  If StartHr = 7 And StartMn = 0 And EndHr = 5 And EndMn = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    LunchHr = 1
  End If

  If MoreHrs <> 0 Then NewEndHr = NewEndHr + MoreHrs
  If LunchHr <> 0 Then NewEndHr = NewEndHr + LunchHr
  
  If NewEndMn = 0 Then EndMn = "00" Else EndMn = NewEndMn
  If Len(Trim(Str(NewEndHr))) < 2 Then EndHr = "0" + Trim(Str(NewEndHr)) Else EndHr = Trim(Str(NewEndHr))
  GetEndTime = EndHr + ":" + EndMn + Trim(TZ1)
End Function

'****************************************
'To Find Start Time from the Given Duration and End Time - NOT USED
'****************************************
'Private Function FindStartTime() As String
'  Dim TZ1 As String, TotalHr As Integer, TotalMn As Integer
'  Dim HR1 As Integer, HR2 As Integer
'  Dim MN1 As Integer, MN2 As Integer
'
'  TZ1 = UCase(Right(Trim(SSDBGrid1.Columns(2).Value), 2))
'  HR1 = CInt(Left(Trim(SSDBGrid1.Columns(2).Value), 2))
'  MN1 = CInt(Mid(Trim(SSDBGrid1.Columns(2).Value), 4, 2))
'
'  If Len(Trim(SSDBGrid1.Columns(3).Value)) = 5 Then
'    HR2 = CInt(Left(Trim(SSDBGrid1.Columns(3).Value), 2))
'    MN2 = CInt(Mid(Trim(SSDBGrid1.Columns(3).Value), 4, 2))
'  ElseIf Len(Trim(SSDBGrid1.Columns(3).Value)) <= 2 Then
'    HR2 = CInt(SSDBGrid1.Columns(3).Value)
'    MN2 = 0
'  Else
'    HR2 = CInt(Left(Trim(SSDBGrid1.Columns(3).Value), Len(Trim(SSDBGrid1.Columns(3).Value)) - 2))
'    MN2 = CInt(Mid(Trim(SSDBGrid1.Columns(3).Value), Len(Trim(SSDBGrid1.Columns(3).Value)), 2))
'  End If
'
'  'Find Total Hours
'  If HR1 < HR2 Then
'    If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
'    TotalHr = 12 - HR2 + HR1
'  ElseIf HR1 = HR2 Then
'    TotalHr = 12
'  Else
'    If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
'    TotalHr = HR1 - HR2
'  End If
'
'  'Find Total Minutes
'  If Len(Trim(Str(MN2))) = 1 Then
'    MN2 = MN2 * 10
'  End If
'
'  TotalMn = MN2 - MN1
'  If TotalMn >= 60 Then
'    TotalHr = TotalHr + 1
'    TotalMn = TotalMn - 60
''    If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
'  End If
'
'  If Len(Trim(Str(TotalHr))) = 1 And Len(Trim(Str(TotalMn))) = 1 Then
'    FindStartTime = "0" + Trim(Str(TotalHr)) + ":0" + Trim(Str(TotalMn)) + TZ1
'  ElseIf Len(Trim(Str(TotalHr))) = 1 And Len(Trim(Str(TotalMn))) = 2 Then
'    FindStartTime = "0" + Trim(Str(TotalHr)) + ":" + Trim(Str(TotalMn)) + TZ1
'  ElseIf Len(Trim(Str(TotalHr))) = 2 And Len(Trim(Str(TotalMn))) = 1 Then
'    FindStartTime = Trim(Str(TotalHr)) + ":0" + Trim(Str(TotalMn)) + TZ1
'  Else
'    FindStartTime = Trim(Str(TotalHr)) + ":" + Trim(Str(TotalMn)) + TZ1
'  End If
'End Function

'****************************************
'To Accept valid characters for Start Time, End Time and Duration on the Grid
'****************************************
'Private Sub SSDBGrid1_KeyPress(KeyAscii As Integer)
'  Dim myLen As Integer
'  'Checking only Duration Column
'  If SSDBGrid1.Col = 3 Then
'    If KeyAscii <> 8 And KeyAscii <> 13 Then
'      'Depending upon the position, allow different type of characters
'      myLen = Len(Trim(SSDBGrid1.Columns(SSDBGrid1.Col).Value))
'      If (myLen = 0) Then     'Hour - I digit
'        'Allow Only numbers from 0 - 9
'        If KeyAscii < 48 Or KeyAscii > 57 Then KeyAscii = 0
'      ElseIf myLen = 1 Then   'Hour - II digit
'        If CInt(SSDBGrid1.Columns(SSDBGrid1.Col).Value) = 1 Then
'          'Allow Only numbers from 0 - 9 or a period (.)
'          If (KeyAscii < 48 Or KeyAscii > 57) And KeyAscii <> 46 Then KeyAscii = 0
'        ElseIf CInt(SSDBGrid1.Columns(3).Value) = 2 Then
'          'Allow only 0, 1 and 2 or a period (.)
'          If KeyAscii <> 48 And KeyAscii <> 49 And KeyAscii <> 50 And KeyAscii <> 46 Then KeyAscii = 0
'        ElseIf CInt(SSDBGrid1.Columns(SSDBGrid1.Col).Value) = 0 Then
'          'Allow only period (.)
'          If KeyAscii <> 46 Then KeyAscii = 0
'        Else    'For Numbers 3 - 9 in the First Digit only allow period (.)
'           If KeyAscii <> 46 Then KeyAscii = 0
'        End If
'      ElseIf myLen = 2 Then   'Allow only .
'        If KeyAscii <> 46 Then KeyAscii = 0
'      ElseIf myLen = 3 Then   'Minutes - I digit
'        'Allow only numbers from 0 - 9
'        If KeyAscii < 48 Or KeyAscii > 57 Then KeyAscii = 0
'      ElseIf myLen = 4 Then   'Minutes - II digit
'        'Allow only numbers
'        If KeyAscii < 48 Or KeyAscii > 57 Then KeyAscii = 0
'      End If
'    End If
'  End If
'  'Checking only for Start and End time columns
'  If SSDBGrid1.Col = 1 Or SSDBGrid1.Col = 2 Then
'    'Always allow backspace and Enter characters
'    If KeyAscii <> 8 And KeyAscii <> 13 Then
'      'Depending upon the position, allow different type of characters
'      myLen = Len(Trim(SSDBGrid1.Columns(SSDBGrid1.Col).Value))
'      If (myLen = 0) Then     'Hour - I digit
'        'Allow Only 0 and 1
'        If KeyAscii <> 48 And KeyAscii <> 49 Then KeyAscii = 0
'      ElseIf myLen = 1 Then   'Hour - II digit
'        If CInt(SSDBGrid1.Columns(SSDBGrid1.Col).Value) = 1 Then
'          'Allow only 0, 1 and 2
'          If KeyAscii <> 48 And KeyAscii <> 49 And KeyAscii <> 50 Then KeyAscii = 0
'        ElseIf CInt(SSDBGrid1.Columns(SSDBGrid1.Col).Value) = 0 Then
'          'Don't Allow Zero - Other numbers are allowed
'          If KeyAscii < 49 Or KeyAscii > 57 Then KeyAscii = 0
'        End If
'      ElseIf myLen = 2 Then   'Allow only :
'        If KeyAscii <> 58 Then KeyAscii = 0
'      ElseIf myLen = 3 Then   'Minutes - I digit
''       'Allow only numbers from 0 - 5
'        If KeyAscii < 48 Or KeyAscii > 53 Then KeyAscii = 0
'      ElseIf myLen = 4 Then   'Minutes - II digit
'        'Allow only numbers
'        If KeyAscii < 48 Or KeyAscii > 57 Then KeyAscii = 0
'      ElseIf myLen = 5 Then   'Time Zone - I Character
'        'Allow only A, a, P and p characters
'        If KeyAscii = 65 Or KeyAscii = 97 Or KeyAscii = 112 Or KeyAscii = 80 Then
'          'Do Nothing
'        Else
'          KeyAscii = 0
'        End If
'      ElseIf myLen = 6 Then   'Time Zone - II Character
'        'Allow only M and m characters
'        If KeyAscii <> 109 And KeyAscii <> 77 Then KeyAscii = 0
'      Else  'No other Character allowed - Total Only 7 Characters
'        KeyAscii = 0
'      End If
'    End If
'  End If
'End Sub

Private Function OnePM_Rule(DateToCheck As Date)
    ' Per Inigo Thomas 02/17/2000 - We will not allow data from previous weeks to be changed
    ' unless it is before 1:00 pm on Monday of the current week.
    Dim CurrDay As Integer, prev_Week As Date
    
    ' Adam Walter, HD 3066.  This function does not include the possibiity of Monday being a holiday;
    ' I will be pulling the day value from the LCS cutoff table that the SIMPLIFIEDHOURLYDETAIL form uses.
    ' This function no longer checks for 9AM nor 1PM, now it's 11AM
    Dim dsSHORT_TERM_DATA As Object
    Dim short_term_sql As String
    
    short_term_sql = "SELECT DAY FROM CUTOFF_DAY"
    Set dsSHORT_TERM_DATA = OraDatabase.DBCreateDynaset(short_term_sql, 0&)

    
    CurrDay = Weekday(Now)
    If CurrDay = vbMonday Then
       prev_Week = Now - 7
    ElseIf CurrDay = vbSunday Then
       prev_Week = Now - 6
    ElseIf CurrDay = vbTuesday Then
        prev_Week = Now - 8
    Else
       prev_Week = Now + 2 - Val(Format(Now, "w"))
    End If
    prev_Week = Format(prev_Week, "mm/dd/yyyy")
    DateToCheck = Format(DateToCheck, "mm/dd/yy")
    
    If DateToCheck >= prev_Week And CurrDay <= dsSHORT_TERM_DATA.Fields("DAY").Value Then
       ' The user is atte mpting to go back to a previous week.  We will only allow this
       ' if it is before 1:00 PM on Monday.
       ' Only allow time from last week to be modified.  Reject everything else.
       If hour(time) < 20 Or DateToCheck = Format(Now, "mm/dd/yy") Then
          OnePM_Rule = True
       Else
          Call MsgBox("It is past 8 PM!  You cannot enter time for " & DateToCheck & "!  This day is closed!", vbCritical + vbOKOnly, "LCS")
          OnePM_Rule = False
          Unload Me
       End If
    Else
       If DateToCheck >= prev_Week Then
          OnePM_Rule = True
       Else
          ' The user is atte mpting to go back to a previous week that is closed.
          Call MsgBox("You cannot enter time for " & DateToCheck & "!  This day is closed!", vbCritical + vbOKOnly, "LCS")
          OnePM_Rule = False
       End If
    End If

End Function
Private Function isSupervisor(empId As String) As Boolean
    Dim EmpType As String
    Dim sqlStmt As String
    
    'get employee_type_id to check if is a supervisor
    sqlStmt = "SELECT EMPLOYEE_TYPE_ID FROM EMPLOYEE WHERE EMPLOYEE_ID = '" & empId & "'"
    Set dsSUPERVISOR = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And dsSUPERVISOR.RecordCount > 0 Then
        EmpType = dsSUPERVISOR.Fields("EMPLOYEE_TYPE_ID").Value
    End If
    
    If EmpType = "SUPV" Then
        isSupervisor = True
    Else
        isSupervisor = False
    End If
End Function

Private Function hireStartTime(empId As String) As String
    Dim sqlStmt As String
    Dim rs As Object
    
    'get hire start time from Daily_hire_list
        sqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE HIRE_DATE = to_date('" & SSDateCombo1.Text & " ','mm/dd/yyyy') " & _
              " AND EMPLOYEE_ID = '" & empId & "'"
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If Not rs.EOF And Not IsNull(rs.Fields("time_in").Value) Then
        hireStartTime = Format(rs.Fields("time_in").Value, "hh:nnam/pm")
    Else
        hireStartTime = Null
    End If
End Function
