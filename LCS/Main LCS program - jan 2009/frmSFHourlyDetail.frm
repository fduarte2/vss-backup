VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Begin VB.Form frmSFHourlyDetail 
   Caption         =   "Simplified Hourly Detail"
   ClientHeight    =   12225
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   15165
   LinkTopic       =   "Form1"
   ScaleHeight     =   12225
   ScaleWidth      =   15165
   StartUpPosition =   3  'Windows Default
   Begin SSDataWidgets_B.SSDBDropDown dwnRemark 
      Height          =   255
      Left            =   10320
      TabIndex        =   48
      Top             =   11850
      Width           =   885
      _Version        =   196616
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   1561
      _ExtentY        =   450
      _StockProps     =   77
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
      Left            =   210
      TabIndex        =   26
      ToolTipText     =   "Select Employee to enter Hourly Detail"
      Top             =   1830
      Width           =   4815
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "E&XIT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   11.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   13650
      Style           =   1  'Graphical
      TabIndex        =   25
      ToolTipText     =   "Return Back"
      Top             =   240
      Width           =   1275
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
      TabIndex        =   24
      ToolTipText     =   "Login Supervisor ID"
      Top             =   1080
      Width           =   2340
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
      Left            =   6000
      Style           =   1  'Graphical
      TabIndex        =   23
      ToolTipText     =   "Delete Record from Database"
      Top             =   11400
      Visible         =   0   'False
      Width           =   1635
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
      Left            =   10080
      Style           =   1  'Graphical
      TabIndex        =   22
      ToolTipText     =   "Save Changes"
      Top             =   3360
      Width           =   1275
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
      Left            =   5280
      TabIndex        =   12
      Top             =   1440
      Width           =   4455
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
         Left            =   1620
         TabIndex        =   16
         Top             =   750
         Visible         =   0   'False
         Width           =   2745
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
            TabIndex        =   18
            Top             =   360
            Width           =   855
         End
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
            TabIndex        =   17
            Top             =   780
            Width           =   975
         End
         Begin SSDataWidgets_B.SSDBCombo SSDBCboEmp 
            Height          =   375
            Left            =   1200
            TabIndex        =   19
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
            TabIndex        =   20
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
      Begin VB.CheckBox chkFilterBy 
         Caption         =   "&Category  Code"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   435
         Index           =   0
         Left            =   120
         TabIndex        =   15
         Top             =   240
         Width           =   1485
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
         Index           =   1
         Left            =   120
         TabIndex        =   14
         Top             =   720
         Width           =   1455
      End
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
         TabIndex        =   13
         ToolTipText     =   "Filter Employees based on Selected Criteria"
         Top             =   2040
         Width           =   4215
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboLoc 
         Height          =   375
         Left            =   1755
         TabIndex        =   21
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
      Left            =   10080
      TabIndex        =   11
      ToolTipText     =   "View TimeSheet Report"
      Top             =   3960
      Width           =   2055
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
      Height          =   1065
      Left            =   9855
      TabIndex        =   9
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
         Left            =   90
         TabIndex        =   10
         ToolTipText     =   "View All Employees "
         Top             =   420
         Width           =   4635
      End
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
      Left            =   13080
      Style           =   1  'Graphical
      TabIndex        =   8
      ToolTipText     =   "Clear All Records from Grid. No Change in Database"
      Top             =   3480
      Width           =   1755
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
      Left            =   13080
      Style           =   1  'Graphical
      TabIndex        =   7
      ToolTipText     =   "Clear the Current Record from Grid. No Changes in Database"
      Top             =   3960
      Width           =   1755
   End
   Begin VB.CommandButton cmdLabTicket 
      Caption         =   "LABOR TICKET"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   11.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   11010
      TabIndex        =   6
      Top             =   240
      Width           =   2520
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
      Left            =   12270
      TabIndex        =   5
      Top             =   11790
      Visible         =   0   'False
      Width           =   450
   End
   Begin VB.TextBox des 
      Height          =   375
      Left            =   11520
      TabIndex        =   4
      Top             =   2940
      Width           =   3255
   End
   Begin VB.OptionButton Option1 
      Caption         =   "Dole"
      Height          =   255
      Left            =   1560
      TabIndex        =   3
      Top             =   4080
      Width           =   735
   End
   Begin VB.OptionButton Option2 
      Caption         =   "Bulk Service"
      Height          =   255
      Left            =   2400
      TabIndex        =   2
      Top             =   4080
      Width           =   1215
   End
   Begin VB.OptionButton Option3 
      Caption         =   "Other"
      Height          =   255
      Left            =   3720
      TabIndex        =   1
      Top             =   4080
      Value           =   -1  'True
      Width           =   735
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBHDOLD 
      Height          =   315
      Left            =   150
      TabIndex        =   0
      Top             =   11280
      Visible         =   0   'False
      Width           =   255
      _Version        =   196616
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   450
      _ExtentY        =   556
      _StockProps     =   79
      Caption         =   "SSDBGrid2"
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnlabor 
      Height          =   255
      Left            =   750
      TabIndex        =   27
      Top             =   11340
      Width           =   255
      _Version        =   196616
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   450
      _ExtentY        =   450
      _StockProps     =   77
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
      Height          =   6855
      Left            =   240
      TabIndex        =   28
      Top             =   4440
      Width           =   14745
      ScrollBars      =   0
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   20
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
      SelectTypeRow   =   1
      BackColorOdd    =   16116683
      RowHeight       =   423
      ExtraHeight     =   2646
      Columns.Count   =   20
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
      Columns(4).Width=   1614
      Columns(4).Caption=   "REG/OT"
      Columns(4).Name =   "REG/OT"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   3200
      Columns(5).Visible=   0   'False
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
      Columns(10).Width=   1667
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
      Columns(12).Width=   3200
      Columns(12).Visible=   0   'False
      Columns(12).Caption=   "SUPV"
      Columns(12).Name=   "SUPV"
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   1402
      Columns(13).Caption=   "INITIALS"
      Columns(13).Name=   "SPEC"
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      Columns(14).Width=   3200
      Columns(14).Visible=   0   'False
      Columns(14).Caption=   "REMARK"
      Columns(14).Name=   "REMARK"
      Columns(14).DataField=   "Column 14"
      Columns(14).DataType=   8
      Columns(14).FieldLen=   256
      Columns(15).Width=   3200
      Columns(15).Visible=   0   'False
      Columns(15).Caption=   "EXACTEND"
      Columns(15).Name=   "EXACTEND"
      Columns(15).DataField=   "Column 15"
      Columns(15).DataType=   8
      Columns(15).FieldLen=   256
      Columns(16).Width=   3200
      Columns(16).Visible=   0   'False
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
      Columns(18).Width=   2143
      Columns(18).Caption=   "PAY_LUNCH"
      Columns(18).Name=   "PAY_LUNCH"
      Columns(18).DataField=   "Column 18"
      Columns(18).DataType=   11
      Columns(18).FieldLen=   1
      Columns(18).Style=   2
      Columns(19).Width=   2064
      Columns(19).Caption=   "PAY_DINNER"
      Columns(19).Name=   "PAY_DINNER"
      Columns(19).DataField=   "Column 19"
      Columns(19).DataType=   11
      Columns(19).FieldLen=   1
      Columns(19).Style=   2
      _ExtentX        =   26009
      _ExtentY        =   12091
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
   Begin SSDataWidgets_B.SSDBDropDown dwnHrs 
      Height          =   255
      Left            =   3870
      TabIndex        =   29
      Top             =   11850
      Width           =   885
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
      _ExtentX        =   1561
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
   Begin SSCalendarWidgets_A.SSDateCombo hireDate 
      Height          =   375
      Left            =   1770
      TabIndex        =   30
      Top             =   1050
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
   Begin SSDataWidgets_B.SSDBDropDown dwnTime 
      Height          =   195
      Left            =   6780
      TabIndex        =   31
      Top             =   11880
      Width           =   915
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
      _ExtentX        =   1614
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
   Begin SSDataWidgets_B.SSDBDropDown dwnService 
      Height          =   255
      Left            =   1080
      TabIndex        =   32
      Top             =   11850
      Width           =   825
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
      _ExtentX        =   1455
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
      Left            =   60
      TabIndex        =   33
      Top             =   11850
      Width           =   990
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
      _ExtentX        =   1746
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
   Begin SSDataWidgets_B.SSDBDropDown dwnVessel 
      Height          =   255
      Left            =   1980
      TabIndex        =   34
      Top             =   11850
      Width           =   855
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
      _ExtentX        =   1508
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
      Left            =   9420
      TabIndex        =   35
      Top             =   11850
      Width           =   795
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
      _ExtentX        =   1402
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
      Left            =   8520
      TabIndex        =   36
      Top             =   11850
      Width           =   855
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
      _ExtentX        =   1508
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
      Left            =   7740
      TabIndex        =   37
      Top             =   11850
      Width           =   735
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
      _ExtentX        =   1296
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
      Left            =   2880
      TabIndex        =   38
      Top             =   11850
      Width           =   945
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
      _ExtentX        =   1667
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
      Left            =   4800
      TabIndex        =   39
      Top             =   11850
      Width           =   795
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      ListWidth       =   14111
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
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   1402
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
      Left            =   5670
      TabIndex        =   40
      Top             =   11880
      Width           =   1005
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
      _ExtentX        =   1773
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
   Begin SSDataWidgets_B.SSDBGrid SSDBHDNEW 
      Height          =   315
      Left            =   450
      TabIndex        =   41
      Top             =   11280
      Visible         =   0   'False
      Width           =   255
      _Version        =   196616
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   450
      _ExtentY        =   556
      _StockProps     =   79
      Caption         =   "SSDBHDNEW"
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
      TabIndex        =   47
      Top             =   1530
      Width           =   2535
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
      Left            =   960
      TabIndex        =   46
      Top             =   0
      Width           =   6345
   End
   Begin VB.Line Line2 
      X1              =   120
      X2              =   15960
      Y1              =   960
      Y2              =   960
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
      TabIndex        =   45
      Top             =   1200
      Width           =   1890
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
      TabIndex        =   44
      Top             =   1140
      Width           =   1050
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
      Left            =   5400
      TabIndex        =   43
      Top             =   4080
      Width           =   4455
   End
   Begin MSForms.Image Image2 
      Height          =   855
      Left            =   0
      Top             =   0
      Width           =   855
      BorderStyle     =   0
      SizeMode        =   1
      SpecialEffect   =   2
      Size            =   "1508;1508"
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
      Left            =   9870
      TabIndex        =   42
      Top             =   3030
      Width           =   1455
   End
End
Attribute VB_Name = "frmSFHourlyDetail"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim OraDatabase2 As Object
'Dim lastHireDate As String  'this date will track down the last valid hire date used by the user
Dim EmpRS, LRS As Object
Dim arrEmpID(), arrEmpType(), rowNumber() As String
Dim NoRec As Boolean, DiffSupervisor() As Boolean
Dim PrevValue As String, CurrValue As String, ErrFlag As Boolean, PrevIndex As Integer
Dim PrvEnd As String, PrvReg As String, PrvEquip As String, PrvSvc As String
Dim PrvComm As String, PrvLoc As String, PrvShip As String, PrvCust As String, PrvSpec As String, PrvLabor As String

Private Function getBreakTime(dStartTime As Date, timeDiff As Single, payLunch As String, payDinner As String) As Date
    Dim twelvePM As Date, sixPM As Date, onePM As Date, sevenPM As Date
    Dim time As Date
    
    twelvePM = CDate(CStr(DateValue(dStartTime)) & " " & "12:00PM")
    onePM = CDate(CStr(DateValue(dStartTime)) & " " & "01:00PM")
    sixPM = CDate(CStr(DateValue(dStartTime)) & " " & "06:00PM")
    sevenPM = CDate(CStr(DateValue(dStartTime)) & " " & "07:00PM")
    
    time = DateAdd("n", timeDiff * 60, dStartTime)
    
    If dStartTime < onePM And time > twelvePM Then
        If payLunch = "N" Then
            time = DateAdd("n", 60, time)
        End If
    End If
    
    If dStartTime < sevenPM And time > sixPM Then
        If payDinner = "N" Then
            time = DateAdd("n", 60, time)
        End If
    End If
    
    getBreakTime = time
End Function


Private Sub cmdClearAll_Click()
    'We can't clear all lines if there is any line which is entered by the other user
    Dim i As Integer
    For i = 0 To UBound(DiffSupervisor) - 1
        If DiffSupervisor(i) = True And timeKeeperPrivilege = False Then
            MsgBox "Can't Delete Employee Data that belongs to Supervisor " + Trim(SSDBGrid1.Columns(12).Value), vbInformation, "Authorization Required"
            Exit Sub
        End If
    Next
    
    'Remove all lines
    SSDBGrid1.RemoveAll
    ColumnFormat
    refreshTotalHrs
End Sub

Private Sub cmdClearLine_Click()
    Dim sStartTime As String
    Dim sLoc As String
    Dim sComm As String
    Dim iDelRow As Integer
    Dim i As Integer
    Dim row As Integer
    Dim rows As Integer
    
    rows = SSDBGrid1.rows
    row = SSDBGrid1.row
    
    If row = rows - 1 Then
        SSDBGrid1.CancelUpdate
        If row <> SSDBGrid1.row Then
            Exit Sub
        End If
    End If

    
    sStartTime = SSDBGrid1.Columns(1).Value
    sLoc = SSDBGrid1.Columns(9).Value
    sComm = SSDBGrid1.Columns(8).Value
    
    If SSDBGrid1.rows > SSDBGrid1.row Then
        If UBound(DiffSupervisor) >= SSDBGrid1.row Then
            If DiffSupervisor(SSDBGrid1.row) = True And timeKeeperPrivilege = False Then
                MsgBox "Can't Delete Employee Data that belongs to Supervisor " + Trim(SSDBGrid1.Columns(12).Value), vbInformation, "Authorization Required"
                Exit Sub
            Else
                SSDBGrid1.CancelUpdate
                SSDBGrid1.RemoveItem (SSDBGrid1.row)
            End If
        Else
            SSDBGrid1.CancelUpdate
            SSDBGrid1.RemoveItem (SSDBGrid1.row)
        End If
        
        For i = SSDBGrid1.row To UBound(DiffSupervisor) - 1
                DiffSupervisor(i) = DiffSupervisor(i + 1)
        Next
        
    End If
    
    If SSDBGrid1.rows = 0 Then
        SSDBGrid1.AddItem "1", 0
        SSDBGrid1.row = 0
        'SSDBGrid1.Columns(0).Value = 1
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
'To return back to previous Screen
'****************************************
Private Sub cmdExit_Click()
    If LineRun = True Then LineRun = False
    Set OraDatabase2 = Nothing
    Unload Me
    frmHiring.Show
End Sub

Private Sub cmdLabTicket_Click()
    frmSFHourlyDetail.Hide
    Load frmSFLaborTicket
    frmSFLaborTicket.Show
End Sub

Private Sub cmdReport_Click()
    ' set the TimeSheetSelection/TimeSheetOrderBy variables
    Dim SelectName As New frmTimeSheetSelect
    SelectName.Show 1
    
    'break code if they clicked the "x" in the called form
    If Len(TimeSheetOrderBy) < 3 Then
       Exit Sub
    End If
    


  Dim strSource As String
  
  If TimeSheetOrderBy = "a.EMPLOYEE_ID, SPECIAL_CODE, b.User_ID" Then
      If LineRun = True Then
        Dim myLineDE As New DE_Line
        Dim myLineDR As New DR_Line
        
        strSource = "SHAPE {SELECT a.EMPLOYEE_ID, b.EMPLOYEE_NAME, a.DURATION, a.EARNING_TYPE_ID, "
        strSource = strSource & "a.SERVICE_CODE, a.COMMODITY_CODE, a.START_TIME, a.EXACT_END, a.HIRE_DATE, "
        strSource = strSource & "a.EXACT_END - a.START_TIME DIFF FROM HOURLY_DETAIL a, EMPLOYEE b "
        strSource = strSource & "Where a.EMPLOYEE_ID = b.EMPLOYEE_ID and a.HIRE_DATE=to_date('" + hireDate.Text + "','mm/dd/yyyy') "
        strSource = strSource & "and a.SERVICE_CODE IN ('1200', '1221','1222','1223') "
        If TimeSheetSelection <> "ALL" Then
            strSource = strSource & "AND SPECIAL_CODE = '" & TimeSheetSelection & "' "
        End If
        strSource = strSource & "AND Upper(a.USER_ID) = '" + UCase(UserID) + "' "
        strSource = strSource & "ORDER BY " & TimeSheetOrderBy & "} AS HourlyDetail "
        strSource = strSource & "COMPUTE HourlyDetail BY 'START_TIME','HIRE_DATE','EXACT_END'"
        
        myLineDE.rsHourlyDetail_Grouping.Source = strSource
        myLineDE.rsHourlyDetail_Grouping.Open
        myLineDR.Refresh
        myLineDR.Show
        myLineDE.rsHourlyDetail_Grouping.Close
      Else
        Dim myDE As New DE_LCS
        Dim myDR As New DR_TimeSheet
            
        'myDE.rsHourlyDetail_Grouping.Source = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name, b.* From Employee a, Hourly_Detail b, LCS_USER c Where b.Employee_id = a.Employee_Id and upper(b.Employee_Id) Not Like 'T%' and b.User_ID = '" + UserID + "' and b.User_ID = c.User_ID and b.Hire_Date = to_date('" + hireDate.Text + "','mm/dd/yyyy') and Upper(b.EARNING_TYPE_ID) <> 'LU' Order by a.Employee_ID, Row_Number}  AS HourlyDetail COMPUTE HourlyDetail BY 'Employee_Type_ID','Employee_Sub_Type_Id','Employee_Id','Employee_Name'"
        '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007. more readable, easier to modify
        strSource = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name, "
        strSource = strSource & "b.* From Employee a, Hourly_Detail b, LCS_USER c "
        strSource = strSource & "Where b.Employee_id = a.Employee_Id and upper(b.Employee_Id) Not Like 'T%' "
        strSource = strSource & "and b.User_ID = c.User_ID "
        strSource = strSource & "and b.Hire_Date = to_date('" + hireDate.Text + "','mm/dd/yyyy') "
        If TimeSheetSelection <> "ALL" Then
            strSource = strSource & "AND (SPECIAL_CODE = '" & TimeSheetSelection & "' OR b.User_ID = '" & UserID & "') "
        End If
        strSource = strSource & "and Upper(b.EARNING_TYPE_ID) <> 'LU' "
        strSource = strSource & "Order by " & TimeSheetOrderBy & ", Row_Number}  AS HourlyDetail "
        strSource = strSource & "COMPUTE HourlyDetail BY 'Employee_Type_ID','Employee_Sub_Type_Id','Employee_Id','Employee_Name'"
        
        myDE.rsHourlyDetail_Grouping.Source = strSource
        
        myDE.rsHourlyDetail_Grouping.Open
        
    
        '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007
        'broke here if no data for a date
    '    If myDE.rsHourlyDetail_Grouping.RecordCount = 0 Then
    '      myDE.rsHourlyDetail_Grouping.Close
    '      Exit Sub
    '    End If
    '
    '    myDR.Orientation = rptOrientLandscape
    
    
        'corrects error 8542, "Report width is larger than the Paper Width" that Dannye was getting on 5/11/2007
        myDR.Width = 1845    '12390
        'MsgBox "myDR.Width:  " & myDR.Width
        
        myDR.Refresh
        myDR.Show
        myDE.rsHourlyDetail_Grouping.Close
      End If
  Else
    Dim myDE_SUPV As New DE_LCS_SUPV
    Dim myDR_SUPV As New DR_TimeSheet_SUPV
    
    strSource = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name, "
    strSource = strSource & "b.* From Employee a, Hourly_Detail b, LCS_USER c "
    strSource = strSource & "Where b.Employee_id = a.Employee_Id and upper(b.Employee_Id) Not Like 'T%' "
    strSource = strSource & "and b.User_ID = c.User_ID "
    strSource = strSource & "and b.Hire_Date = to_date('" + hireDate.Text + "','mm/dd/yyyy') "
    If TimeSheetSelection <> "ALL" Then
        strSource = strSource & "AND (SPECIAL_CODE = '" & TimeSheetSelection & "' OR b.User_ID = '" & UserID & "') "
    End If
    strSource = strSource & "and Upper(b.EARNING_TYPE_ID) <> 'LU' "
    strSource = strSource & "Order by " & TimeSheetOrderBy & ", Row_Number}  AS HourlyDetail "
    strSource = strSource & "COMPUTE HourlyDetail BY 'User_Name','User_ID'"
  
    myDE_SUPV.rsHourlyDetail_Grouping.Source = strSource
    
    myDE_SUPV.rsHourlyDetail_Grouping.Open
    

    '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007
    'broke here if no data for a date
'    If myDE.rsHourlyDetail_Grouping.RecordCount = 0 Then
'      myDE.rsHourlyDetail_Grouping.Close
'      Exit Sub
'    End If
'
'    myDR.Orientation = rptOrientLandscape


    'corrects error 8542, "Report width is larger than the Paper Width" that Dannye was getting on 5/11/2007
    myDR_SUPV.Width = 1845    '12390
    'MsgBox "myDR.Width:  " & myDR.Width
    
    myDR_SUPV.Refresh
    myDR_SUPV.Show
    myDE_SUPV.rsHourlyDetail_Grouping.Close
  End If
  
  'reset variables
    TimeSheetSelection = ""
    TimeSheetOrderBy = ""
  
End Sub

Private Sub cmdUpdate_Click()
    Call MouseHourlyGlass
    refreshTotalHrs
    
    Dim i As Integer
    
    If checkFields Then
        If checkTime Then
            Call saveData
        End If
    End If
    
    Call MouseNormal
End Sub

Private Sub cmdViewAll_Click()
    Call display(CDate(hireDate.Text), 0)
End Sub

Private Sub dwnRemark_InitColumnProps()
    dwnRemark.Columns.RemoveAll
    dwnRemark.Columns.add 0
    
    dwnRemark.Columns(0).Caption = "Remark"
 
    dwnRemark.AddItem "Line Runners"
    dwnRemark.AddItem "PayRoll Adjustment"
    
    dwnRemark.Columns(0).Width = 1411
 
End Sub

Private Sub Form_Load()
    Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
        
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    SSDBGrid1.RowHeight = 450
    
    'To populate default values for UserID
    txtSupervisorID = UserID
    hireDate.Text = Format(Date, "MM/DD/YYYY")
    
    'Set OraDatabase2 = OraSession.Open Database("BNI", "SAG_OWNER/SAG", 0&)
    'Set OraDatabase2 = OraSession.Open Database("BNI.DEV", "SAG_OWNER/SAG_DEV", 0&)  '2853 3/29/2007 Rudy: for testing, orig above
    Set OraDatabase2 = OraSession.OpenDatabase(DB, Login, 0&)  '5/2/2007 HD2759 Rudy: one init, orig above  TESTED /

    'Fill in Category Codes
    'The reason why I move this out from display function is that
    'The Category codes are not changed since this page is loaded.
    'Calling it each time when the page is loaded is a waste of time.
    Call ShowLocations
    
    'Display the other sections in the form
    '0 means not applying any filter rules
    Call display(hireDate, 0)
End Sub

'*****************************************************************
' This function will be called to display the screen correctly
'   1. retrieve all employees who are hired on the hiring date,
'       and fill in Employee Detail List Box
'   2. Load all available category codes into Category CD
'   3. clear the Data Grid, disable some buttons
'*****************************************************************
Private Sub display(hire_date As Date, filterType As Integer)
    'Get Employee List
    'If filter type is 0, apply no fitler rules
    'Otherwise, apply the corresponding rule
    If filterType = 0 Then
        Call getAllEmployees(hire_date)
    Else
        Call getEmployeesByFilter(hire_date, filterType)
    End If
    
    'Get Category Codes
    'Move this to Form_Load function, since the Category Codes Drop List
    'Box will not be changed since the page is loaded
    'Call ShowLocations
    
    'Set up the form
    Call setupForm
End Sub

'*******************************************************************************
' This function will return whether a hireDate is valid or not:
' RETURN:
'   True - If a valid da te
'   False - If not a valid da te
'
'   in VB, Sudnay is 1, Monday is 2, Tuesday 3, etc.  For LCS purposes,
'   a week starts on MONDAY (I.E. 2)
'*******************************************************************************
Private Function validHireDate(hire_date As Date) As Boolean
    Dim latestday As Date
    Dim dayOfWeek As Integer
    Dim cutoff_day As Integer
    Dim cutoff_hour As Integer
    Dim cutoff_minute  As Integer
    Dim sqlStmt As String
    Dim rs As Object
    
    sqlStmt = "SELECT day, hour, minute from cutoff_day"
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)

    If OraDatabase.LastServerErr = 0 And rs.RecordCount > 0 Then
        If Not IsNull(rs.Fields("day")) Then
            cutoff_day = rs.Fields("day").Value
        Else
            cutoff_day = 0
        End If
        If Not IsNull(rs.Fields("hour")) Then
            cutoff_hour = rs.Fields("hour").Value
        Else
            cutoff_hour = 0
        End If
        If Not IsNull(rs.Fields("minute")) Then
            cutoff_minute = rs.Fields("minute").Value
        Else
            cutoff_minute = 0
        End If
    End If
   
   
   
    If timeKeeperPrivilege() Then
        'latestday = findMonday(Da te) - 7
        validHireDate = True
        Exit Function
    End If
    
    
    ' Step 0:  load a couple values.
    Dim dteTodaysDate As Date
    dteTodaysDate = Date
    dayOfWeek = Weekday(dteTodaysDate)
    
    
    'Step 1:  If RIGHT NOW is before the cutoff time, allow updates for this and past week
    If dayOfWeek < cutoff_day Or (dayOfWeek = cutoff_day And hour(time) <= cutoff_hour And Minute(time) <= cutoff_minute) Then
       If hire_date >= dteTodaysDate - ((dayOfWeek - 2) + 7) Then
            validHireDate = True
        Else
            validHireDate = False
        End If
     Else
    'Step 2:  If RIGHT NOW is after the cutoff time, allow updates for this week only
        If hire_date >= dteTodaysDate - (dayOfWeek - 2) Then
            validHireDate = True
        Else
            validHireDate = False
        End If
    End If
    
    'HD2695 Rudy 6/29/2007: had to remove the "Date" function call from this sub, so I could test on a day other than today.
'    Dim dteTodaysDate As Date
'    dteTodaysDate = Date
    
    'temp testing
    'dteTodaysDate = DateAdd("d", -3, dteTodaysDate)
    
    'dayOfWeek = Weekday(Da te)
'    dayOfWeek = Weekday(dteTodaysDate)
    
    'HD2695 Rudy 6/29/2007: if it's Tuesday, was Monday a holiday?
    'if so then add one to cutoff_day, etc.
'    If dayOfWeek = 3 Then
'        Dim dteTest As Date
'
'        dteTest = holidayInPeriod("TEST", DateAdd("d", -1, dteTodaysDate), DateAdd("d", 5, dteTodaysDate))  'hire_date or Date?
'        If dteTest = DateAdd("d", -1, dteTodaysDate) Then
'          cutoff_day = cutoff_day + 1
'          dteTodaysDate = dteTodaysDate - 1
'        End If
'    End If
'
'    If dayOfWeek = 1 Then           'Sunday is always good
'    'ElseIf dayOfWeek = 1 Then      'HD2695 Rudy 6/29/2007: reversed these two
'        'latestday = Date - 6
'        latestday = dteTodaysDate - 6
'    ElseIf dayOfWeek < cutoff_day And dayOfWeek = 2 Then
'        ' it's Monday, but cutoff date has been abnormally specified later than today's date.  allow updates for last 7 days.
'            latestday = dteTodaysDate - 6
'
'    ElseIf dayOfWeek = cutoff_day Then  'HD2695 Rudy 6/29/2007: reversed these two
'    'If dayOfWeek = cutoff_day Then
'        If hour(time) <= cutoff_hour And Minute(time) <= cutoff_minute Then
'            'It is before the cutoff time of the cutoff day
'            'latestday = Date - 7
'            latestday = dteTodaysDate - 7
'        Else
'            'latestday = Date
'            latestday = dteTodaysDate
'        End If
'
'
'    Else
'        'It is after Monday 9:00AM, any date after this Monday is good
'        'latestday = Date - (dayOfWeek - 2)
'        latestday = dteTodaysDate - (dayOfWeek - 2)
'    End If
'
'    If hire_date < latestday Then
'        validHireDate = False
'    Else
'        validHireDate = True
'    End If
End Function

'*******************************************************************************
' This function will retrieve every qualified employees and fill in the Employee
' Detail List Box, and the Employee ID (Name) Drop List in the Filter By Section
'   1. Check whether the system has hired all supervisors automatically. If not,
'      hire all of them
'   2. Fill in the Employee Detail List Box and Employee ID (Name) Drop List
'*******************************************************************************
Private Sub getAllEmployees(hire_date As Date)
    Dim sqlStmt, sqlStmt1, sqlStmt2 As String
    Dim strDate As String
    strDate = Format(hire_date, "MM/DD/YYYY")
    
    'Step 1
    sqlStmt = "SELECT Employee_ID FROM DAILY_HIRE_LIST WHERE LOCATION_ID = 'SUPER'" _
                 & " AND HIRE_DATE =TO_DATE('" & Format(hire_date, "MM/DD/YYYY") & "','MM/DD/YYYY')"
    Set dsHIRE_SUPERVISOR = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    'If we didn't get any emloyees, it means that the system has not hired
    'all supervisors yet, we need to hire them now
    If OraDatabase.LastServerErr = 0 And dsHIRE_SUPERVISOR.RecordCount = 0 Then
        'We should not hire all the supervisors if hire date is later than today
        'Most likely the user types in the date wrongly.
'        If hire_date <= Date Then
            Call hireAllSupervisors(hire_date)
'        End If
    End If
    
    'Step 2
    'Clear the employee details list box
    lstEmpName.Clear
    'Clear the employee ID(Name) drop list in the Filter By section
    SSDBCboEmp.Columns.RemoveAll
    ssdbcboName.Columns.RemoveAll
    SSDBCboEmp.Columns(0).Caption = "Employee ID"
    SSDBCboEmp.Columns(1).Caption = "Employee Name"
    ssdbcboName.Columns(0).Caption = "Employee Name"
    ssdbcboName.Columns(1).Caption = "Employee ID"
    
    'load the employees to the employee details list box
    'also load the employees to the employee drop list in the Filter By section
    
    'If the user is Wiliam Bolles, we will only show the employees whose employement type is GUARD
    'and employees who are hired under category code GUARD
    'If UCase(Trim(UserID)) = " E002047" Then
    '    sqlStmt = "SELECT e.EMPLOYEE_ID, e.EMPLOYEE_NAME, e.EMPLOYEE_TYPE_ID, e.EMPLOYEE_SUB_TYPE_ID, e.SENIORITY" _
    '            & " FROM EMPLOYEE e, DAILY_HIRE_LIST dhl" _
    '            & " Where e.EMPLOYEE_ID = dhl.EMPLOYEE_ID" _
    '            & " AND dhl.hire_date = to_date('" & strDate & "', 'mm/dd/yyyy')" _
    '            & " AND ( (Upper(Employee_Type_ID) = 'GUARD') OR (dhl.LOCATION_ID='GUARD'))"
    '
    '    sqlStmt1 = sqlStmt & " ORDER BY e.EMPLOYEE_NAME"
    '    sqlStmt2 = sqlStmt & " ORDER BY e.EMPLOYEE_ID"
    'Else
        sqlStmt = "SELECT e.EMPLOYEE_ID, e.EMPLOYEE_NAME, e.EMPLOYEE_TYPE_ID, e.EMPLOYEE_SUB_TYPE_ID, e.SENIORITY" _
                & " FROM EMPLOYEE e, DAILY_HIRE_LIST dhl" _
                & " WHERE e.EMPLOYEE_ID = dhl.EMPLOYEE_ID" _
                & " AND dhl.HIRE_DATE = TO_DATE('" & strDate & "','MM/DD/YYYY')"
        sqlStmt1 = sqlStmt & " ORDER BY e.EMPLOYEE_NAME"
        sqlStmt2 = sqlStmt & " ORDER BY e.EMPLOYEE_ID"
    'End If
    Set EmpRS = OraDatabase.DBCreateDynaset(sqlStmt1, 0&)
    
    Dim arrCnt, TotalEmp As Integer
    arrCnt = 1
    TotalEmp = EmpRS.RecordCount
    ReDim arrEmpID(TotalEmp)
    ReDim arrEmpType(TotalEmp)
    Dim empId As String, TypeID As String
    Do While Not EmpRS.EOF
        empId = UCase(EmpRS.Fields("Employee_id").Value)
        If IsNull(EmpRS.Fields("Employee_Sub_Type_ID").Value) Then
          TypeID = UCase(EmpRS.Fields("Employee_Type_ID").Value)
        Else
          TypeID = UCase(EmpRS.Fields("Employee_Type_ID").Value + "-" + EmpRS.Fields("Employee_Sub_Type_ID").Value)
        End If
        
        'Fill in Employee Details List Box
        If IsNull(EmpRS.Fields("Seniority").Value) Then
          lstEmpName.AddItem "(" + empId + ") " + EmpRS.Fields("Employee_Name").Value + ":" + TypeID
        Else
          lstEmpName.AddItem "(" + empId + ") " + EmpRS.Fields("Employee_Name").Value + ":" + TypeID + ":" + Str(EmpRS.Fields("Seniority").Value)
        End If
        
        'Fill in Employee Name Drop List Box in Filter By Section
        ssdbcboName.AddItem EmpRS.Fields("Employee_Name").Value & "!" & EmpRS.Fields("Employee_ID").Value
        
        arrEmpID(arrCnt) = EmpRS.Fields("Employee_id").Value
        arrEmpType(arrCnt) = EmpRS.Fields("Employee_Type_ID").Value
        EmpRS.MoveNext
        arrCnt = arrCnt + 1
    Loop
    
    'This is for Employee ID Drop List in Filter By Section
    Set EmpRS = OraDatabase.DBCreateDynaset(sqlStmt2, 0&)
    Do While Not EmpRS.EOF
        SSDBCboEmp.AddItem EmpRS.Fields("Employee_ID").Value & "!" & EmpRS.Fields("Employee_Name").Value
        EmpRS.MoveNext
    Loop
    
    SSDBCboEmp.Columns(1).Width = 3500
    ssdbcboName.Columns(0).Width = 3500
End Sub

'*******************************************************************************
' This function will retrieve every qualified employees satisfy the filter rule
' and fill them in the Employee Detail List Box
'   1. filterType = 1: Filter by category code
'   2. filterType = 2: Filter by Employee ID
'   3. filterType = 3: Filter by Employee Name
'*******************************************************************************
Private Sub getEmployeesByFilter(hire_date As Date, filterType As Integer)
    Dim sqlStmt, sqlStmt1 As String
    Dim strDate As String
    strDate = Format(hire_date, "MM/DD/YYYY")
    
    lstEmpName.Clear
    
    sqlStmt = "SELECT e.EMPLOYEE_ID, e.EMPLOYEE_NAME, e.EMPLOYEE_TYPE_ID, e.EMPLOYEE_SUB_TYPE_ID, e.SENIORITY" _
            & " FROM EMPLOYEE e, DAILY_HIRE_LIST dhl" _
            & " Where e.EMPLOYEE_ID = dhl.EMPLOYEE_ID" _
            & " AND dhl.hire_date = to_date('" & strDate & "', 'mm/dd/yyyy')"
            
    If filterType = 1 Then
        sqlStmt1 = sqlStmt & " AND UPPER(dhl.LOCATION_ID)='" + UCase(Trim(SSDBCboLoc.Text)) + "'"
    ElseIf filterType = 2 Then
        sqlStmt1 = sqlStmt & " AND dhl.EMPLOYEE_ID LIKE '%" & UCase(Trim(SSDBCboEmp.Text)) & "'"
    ElseIf filterType = 3 Then
        sqlStmt1 = sqlStmt & " AND UPPER(e.EMPLOYEE_NAME)='" & UCase(Trim(ssdbcboName.Text)) & "'"
    End If
    
    sqlStmt1 = sqlStmt1 & " ORDER BY e.EMPLOYEE_NAME"
    Set EmpRS = OraDatabase.DBCreateDynaset(sqlStmt1, 0&)
    
    'load the employees to the employee details list box
    Dim arrCnt, TotalEmp As Integer
    arrCnt = 1
    TotalEmp = EmpRS.RecordCount
    ReDim arrEmpID(TotalEmp)
    ReDim arrEmpType(TotalEmp)
    Dim empId As String, TypeID As String
    Do While Not EmpRS.EOF
        empId = UCase(EmpRS.Fields("Employee_id").Value)
        If IsNull(EmpRS.Fields("Employee_Sub_Type_ID").Value) Then
          TypeID = UCase(EmpRS.Fields("Employee_Type_ID").Value)
        Else
          TypeID = UCase(EmpRS.Fields("Employee_Type_ID").Value + "-" + EmpRS.Fields("Employee_Sub_Type_ID").Value)
        End If
        
        'Fill in Employee Details List Box
        If IsNull(EmpRS.Fields("Seniority").Value) Then
          lstEmpName.AddItem "(" + empId + ") " + EmpRS.Fields("Employee_Name").Value + ":" + TypeID
        Else
          lstEmpName.AddItem "(" + empId + ") " + EmpRS.Fields("Employee_Name").Value + ":" + TypeID + ":" + Str(EmpRS.Fields("Seniority").Value)
        End If
        
        arrEmpID(arrCnt) = EmpRS.Fields("Employee_id").Value
        arrEmpType(arrCnt) = EmpRS.Fields("Employee_Type_ID").Value
        EmpRS.MoveNext
        arrCnt = arrCnt + 1
    Loop
End Sub

'****************************************************************
' Hire all supervisors if they have not been hired on the
' specified day
'****************************************************************
Private Sub hireAllSupervisors(hire_date As Date)
    Dim sqlStmt As String
    sqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE LOCATION_ID = 'SUPER'" _
             & " AND HIRE_DATE =TO_DATE('" & Format(hire_date, "MM/DD/YYYY") & "','MM/DD/YYYY')"
    Set dsHIRE_SUPERVISOR = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
                  
    sqlStmt = " SELECT EMPLOYEE_ID FROM DAILY_HIRE_LIST WHERE LOCATION_ID = 'SUPER'" _
              & " AND HIRE_DATE = TO_DATE('" & Format(hire_date, "MM/DD/YYYY") & "','MM/DD/YYYY')"
              
    sqlStmt = "SELECT EMPLOYEE_ID FROM EMPLOYEE WHERE EMPLOYEE_TYPE_ID ='SUPV' AND EMPLOYEE_ID NOT IN(" & sqlStmt & ")"
    Set dsSUPERVISOR = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    Dim employee_id As String
    If OraDatabase.LastServerErr = 0 And dsSUPERVISOR.RecordCount > 0 Then
       While Not dsSUPERVISOR.EOF
          OraSession.BeginTrans
          employee_id = Trim$(dsSUPERVISOR.Fields("EMPLOYEE_ID").Value)
          
          dsHIRE_SUPERVISOR.AddNew
          dsHIRE_SUPERVISOR.Fields("HIRE_DATE").Value = Format(hire_date, "MM/DD/YYYY")
          dsHIRE_SUPERVISOR.Fields("EMPLOYEE_ID").Value = employee_id
          dsHIRE_SUPERVISOR.Fields("TIME_IN").Value = Format(Format(hire_date, "MM/DD/YYYY") & " 12:00:00am", "mm/dd/yyyy hh:mm:ssAM/PM")
          dsHIRE_SUPERVISOR.Fields("LOCATION_ID").Value = "SUPER"
          dsHIRE_SUPERVISOR.Fields("COMMODITY_CODE").Value = 0
          dsHIRE_SUPERVISOR.Fields("USER_ID").Value = employee_id
          dsHIRE_SUPERVISOR.Update
          OraSession.CommitTrans
          dsSUPERVISOR.MoveNext
       Wend
    End If
 End Sub

Private Sub Form_Unload(Cancel As Integer)
    Call cmdExit_Click
End Sub

'***********************************************************
' When the user choose a different hire date, we need to
'   1. Decide whether the date is good or not
'   2. Display the form again
'***********************************************************
Private Sub hireDate_Click()
    Call MouseHourlyGlass
    Call display(CDate(hireDate.Text), 0)
    Call MouseNormal
End Sub

'***********************************************************
' When the user choose a different hire date, we need to
'   1. Decide whether the date is good or not
'   2. Display the form again
'***********************************************************
Private Sub hireDate_Change()
    Call hireDate_Click
End Sub

'********************************************************
' This function will set up the form:
'   Clear the Data Grid
'   Disable Update Button
'   Disable Clear Line Button
'   Disable Clear All Button
'********************************************************
Private Sub setupForm()
    Dim hire_date As Date
    hire_date = CDate(hireDate.Text)
    
    SSDBGrid1.RemoveAll
    
    If validHireDate(hire_date) Then
        SSDBGrid1.AllowUpdate = True
    Else
        SSDBGrid1.AllowUpdate = False
    End If
    
    cmdUpdate.Enabled = False
    cmdClearLine.Enabled = False
    cmdClearAll.Enabled = False
End Sub

'****************************************
'To Fill Location Combo
'****************************************
Private Sub ShowLocations()
  Dim i As Integer
  SSDBCboLoc.Columns.RemoveAll
  For i = 0 To 1
    SSDBCboLoc.Columns.add i
  Next
  Dim LocRS As Object
  SSDBCboLoc.Columns(0).Caption = "Category CD"
  SSDBCboLoc.Columns(1).Caption = "Category Name"
  
  Set LocRS = OraDatabase.DBCreateDynaset("Select Location_ID, Location_Description from Location_Category Order by Location_ID", 0&)
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
        
Private Sub chkFilterBy_Click(Index As Integer)
    If chkFilterBy(0).Value = 1 Then
        optFilterEmp(0).Value = False
        optFilterEmp(0).Value = False
        SSDBCboLoc.Enabled = True
        SSDBCboLoc.SetFocus
    ElseIf chkFilterBy(0).Value = 0 Then
        SSDBCboLoc.Enabled = False
        SSDBCboLoc.Text = ""
    End If
    
    If chkFilterBy(1).Value = 1 Then
        Frame3.Visible = True
        If optFilterEmp(0).Value = True Or optFilterEmp(1).Value = True Then
            chkFilterBy(0).Value = 0
            chkFilterBy(0).Enabled = False
            SSDBCboLoc.Enabled = False
            SSDBCboLoc.Text = ""
        End If
    ElseIf chkFilterBy(1).Value = 0 Then
        Frame3.Visible = False
        chkFilterBy(0).Enabled = True
    End If
End Sub

Private Sub lstEmpName_Click()
    Dim empId As String
    Dim hire_date As Date
    
    Call MouseHourlyGlass
    
    empId = arrEmpID(lstEmpName.ListIndex + 1)
    hire_date = CDate(hireDate.Text)

    '1. clear the DB Grid
    SSDBGrid1.RemoveAll
  
    '2. check if this employee is supervisor.
    'If the employee is not the current user, pop up a warning message box
    If empId <> UserID Then
        If timeKeeperPrivilege() Then
            'Do Nothing
        ElseIf isSupervisor(empId) Then
            MsgBox "Sorry, You can not enter or edit other supervisor's hourly detail.", vbCritical + vbOKOnly, "Hourly detail"
            Call MouseNormal
            Exit Sub
        End If
    End If
    
    '3. Load the SFHD data into the DB Grid
    SSDBGrid1.Columns(18).Visible = True
    SSDBGrid1.Columns(19).Visible = True
    
    If empId = UserID Then
        SSDBGrid1.Columns(4).Visible = True
        SSDBGrid1.Columns(14).Visible = False
        SSDBGrid1.Columns(15).Visible = False
        Call dwnEarning_InitColumnProps
    ElseIf (timeKeeperPrivilege() And isSupervisor(empId)) Then
        SSDBGrid1.Columns(4).Visible = True
        SSDBGrid1.Columns(14).Visible = False
        SSDBGrid1.Columns(15).Visible = False
        Call dwnEarning_InitColumnProps
    Else
        SSDBGrid1.Columns(4).Visible = False
        If timeKeeperPrivilege() = True Then
            'SSDBGrid1.Columns(14).Visible = True
            SSDBGrid1.Columns(15).Visible = True
        End If
    End If
    
    '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007
'    SSDBGrid1.Columns(14).Visible = True
'    SSDBGrid1.Columns(14).FieldLen = 50
'    SSDBGrid1.Columns(14).VertScrollBar = True

    Call loadSFHD(empId, hire_date)
     
    '4. Set up the the column drop list in the corect format
    Call ColumnFormat  'Add Dropdown to Columns
    
    '5. Update the total hour label
    Call refreshTotalHrs
    
    If SSDBGrid1.AllowUpdate = True Then
        cmdUpdate.Enabled = True
        cmdClearLine.Enabled = True
        cmdClearAll.Enabled = True
    End If
    
    Call MouseNormal
End Sub

'****************************************
'To Set the Format for all Columns on the Grid
'****************************************
Private Sub ColumnFormat()
  SSDBGrid1.Columns(1).DropDownHwnd = dwnTime.hWnd
  SSDBGrid1.Columns(2).DropDownHwnd = dwnTime.hWnd
  SSDBGrid1.Columns(3).DropDownHwnd = dwnHrs.hWnd
  SSDBGrid1.Columns(3).Locked = False
  SSDBGrid1.Columns(4).DropDownHwnd = dwnEarning.hWnd
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
End Sub

'****************************************
'To Add Rows on Grid - Set default for Blank Rows
'****************************************
Private Sub loadSFHD(empId As String, hire_date As Date)
    Dim i As Integer
    Dim sFields() As String
    Dim sLrsField As String
    Dim recCnt As Integer
    Dim Bill() As Boolean
    Dim DailyHireRS As Object
    Dim sqlStmt As String
    Dim irec As Integer
    Dim iResponse As Integer
    
    Dim TotalRec, ct As Integer
    Dim arrCnt As Integer

'    sqlStmt = " SELECT ROW_NUMBER, START_TIME,END_TIME,DURATION,EARNING_TYPE_ID, BILLING_FLAG, SERVICE_CODE, EQUIPMENT_ID, " _
'            & " COMMODITY_CODE, LOCATION_ID, VESSEL_ID, CUSTOMER_ID,USER_ID, SPECIAL_CODE,REMARK,EXACT_END,LABOR_TYPE,ROWID,PAY_LUNCH, PAY_DINNER " _
'            & " FROM SF_HOURLY_DETAIL " _
'            & " WHERE HIRE_DATE=TO_DATE('" + Format(hire_date, "MM/DD/YYYY") + "','MM/DD/YYYY') AND " _
'            & " EMPLOYEE_ID = '" + empId + "' ORDER BY START_TIME"
    '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007.  easier to read, readily modifiable,  orig above
    sqlStmt = " SELECT ROW_NUMBER, START_TIME, END_TIME, DURATION, EARNING_TYPE_ID, BILLING_FLAG, "
    sqlStmt = sqlStmt & "SERVICE_CODE, EQUIPMENT_ID, COMMODITY_CODE, LOCATION_ID, VESSEL_ID, "
    sqlStmt = sqlStmt & "CUSTOMER_ID, USER_ID, SPECIAL_CODE, REMARK, EXACT_END, "
    sqlStmt = sqlStmt & "LABOR_TYPE, ROWID, PAY_LUNCH, PAY_DINNER "
    'sqlStmt = sqlStmt & "LABOR_TYPE, ROWID, PAY_LUNCH, PAY_DINNER, NEWFIELDNAME "
    sqlStmt = sqlStmt & "FROM SF_HOURLY_DETAIL "
    sqlStmt = sqlStmt & "WHERE HIRE_DATE=TO_DATE('" + Format(hire_date, "MM/DD/YYYY") + "','MM/DD/YYYY') AND "
    sqlStmt = sqlStmt & "EMPLOYEE_ID = '" + empId + "' ORDER BY START_TIME "
                    
    Set LRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If LRS.RecordCount = 0 Then
        Call initSFHDDataGrid(empId, hire_date)
        Exit Sub
    Else
        'Add the records from the database to the Grid
        LRS.MoveLast: LRS.MoveFirst
   
        ReDim pay_lunch(LRS.RecordCount) As Boolean
        ReDim pay_dinner(LRS.RecordCount) As Boolean
        ReDim DiffSupervisor(LRS.RecordCount) As Boolean
        ReDim rowNumber(LRS.RecordCount) As String
        ReDim sFields(LRS.RecordCount) As String
        recCnt = 1
        ct = LRS.Fields.count
        
        For irec = 1 To LRS.RecordCount
            For i = 0 To ct - 1         'Loop through Fields
                If IsNumeric(LRS.Fields(i).Value) Then
                    sLrsField = Str(LRS.Fields(i).Value)
                    If i = 3 Then
                        sLrsField = Format(Trim(sLrsField), "#.0")
                    End If
                ElseIf IsNull(LRS.Fields(i).Value) Then
                    sLrsField = " "
                ElseIf i = 1 Or i = 2 Or i = 15 Then
                    sLrsField = Format(LRS.Fields(i).Value, "hh:mmAM/PM")
                    'sLrsField = Format(time(LRS.Fields(i).Value), "hh:mmAM/PM")
                ElseIf i = 18 Then
                    If UCase(Trim(LRS.Fields(i).Value)) = "Y" Then pay_lunch(recCnt) = True
                    If UCase(Trim(LRS.Fields(i).Value)) = "N" Then pay_lunch(recCnt) = False
                ElseIf i = 19 Then
                    If UCase(Trim(LRS.Fields(i).Value)) = "Y" Then pay_dinner(recCnt) = True
                    If UCase(Trim(LRS.Fields(i).Value)) = "N" Then pay_dinner(recCnt) = False
                Else
                    sLrsField = LRS.Fields(i).Value
                End If
                sFields(recCnt) = sFields(recCnt) + Trim(sLrsField) + Chr(9)
            Next
      
            'User can't change Employee Data of Other User
            If Trim(UCase(UserID)) <> Trim(UCase(LRS.Fields("User_ID").Value)) Then
                DiffSupervisor(recCnt - 1) = True
            Else
                DiffSupervisor(recCnt - 1) = False
            End If
            
            rowNumber(recCnt - 1) = CStr(LRS.Fields("ROW_NUMBER"))
          
            LRS.MoveNext
            sFields(recCnt) = Left(sFields(recCnt), Len(sFields(recCnt)) - 1)
            SSDBGrid1.AddItem sFields(recCnt)
      
            recCnt = recCnt + 1
        Next irec
        
        For i = 1 To recCnt - 1
            SSDBGrid1.row = i - 1
            If pay_lunch(i) = True Then
                SSDBGrid1.Columns(18).Value = -1
            Else
                SSDBGrid1.Columns(18).Value = 0
            End If
            If pay_dinner(i) = True Then
                SSDBGrid1.Columns(19).Value = -1
            Else
                SSDBGrid1.Columns(19).Value = 0
            End If
        Next
    End If
End Sub

'*****************************************************************************
'Init the SFHD Data Grid if there is no SFHD data entered in the system yet.
'   1. Find the hire time, location an commdity code and set it up.
'*****************************************************************************
Private Sub initSFHDDataGrid(empId As String, hire_date As Date)
    Dim sqlStmt As String
    Dim DailyHireRS As Object
    ReDim DiffSupervisor(0)
    Dim timeIn As String
    
    'To have Start_Time as the default Time_In from Daily Hire Table and Location and Commodity Code as default
    sqlStmt = " Select Time_In, Location_Id, Commodity_Code from Daily_Hire_List " _
        & " Where Employee_ID = '" + empId + "' and " _
        & " Hire_Date = to_date('" + Format(hire_date, "MM/DD/YYYY") + "','mm/dd/yyyy')"
    
    Set DailyHireRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    PrvLoc = Trim("" & DailyHireRS.Fields("Location_ID").Value)
    PrvComm = Trim("" & DailyHireRS.Fields("COMMODITY_CODE").Value)
    
    SSDBGrid1.MoveFirst
    If DailyHireRS.RecordCount > 0 Then
        If empId <> UserID Then
            timeIn = Format(Str(DailyHireRS.Fields("time_in").Value), "hh:nnAM/PM")
        Else
            timeIn = ""
        End If
        
        'SSDBGrid1.AddItem "" + Chr(9) + timeIn + Chr(9) + Chr(9) + _
        '              Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + Trim(Str(DailyHireRS.Fields("Commodity_Code").Value)) + _
        '              Chr(9) + DailyHireRS.Fields("Location_ID").Value
        SSDBGrid1.AddItem "" + Chr(9) + timeIn + Chr(9) + Chr(9) + _
                      Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + PrvComm + _
                      Chr(9) + PrvLoc
    End If
    'If UCase(Trim(UserID)) = " E002047" Then
    '    SSDBGrid1.Columns(5).Value = False
    '    SSDBGrid1.Columns(7).Value = "0"
    '    SSDBGrid1.Columns(8).Value = "0"
    '    SSDBGrid1.Columns(10).Value = "-1"
    '    SSDBGrid1.Columns(11).Value = "1"
    '    SSDBGrid1.Columns(12).Value = UserID
    'Else
        If UserID = empId Then SSDBGrid1.Columns(4).Value = "REG"
        SSDBGrid1.Columns(5).Value = False           'Default - Billing Flag to be FALSE
        SSDBGrid1.Columns(12).Value = UserID        'Default - UserID is the logon Person
        SSDBGrid1.Columns(10).Value = "-1"             'Default - Vessel as No Ship - 0(N/A)
        SSDBGrid1.Columns(11).Value = "1"
        SSDBGrid1.Columns(13).Value = dwnSpec.Columns(0).Value
    'End If
    SSDBGrid1.Columns(18).Value = False
    SSDBGrid1.Columns(19).Value = False
    
    DailyHireRS.Close
    Set DailyHireRS = Nothing
End Sub

'****************************************
'To Enable / Disable Controls based on
'Selection in Employee ID / Name Options
'****************************************
Private Sub optFilterEmp_Click(Index As Integer)
     'Disable Location check box
     chkFilterBy(0).Value = 0
     chkFilterBy(0).Enabled = False
     SSDBCboLoc.Enabled = False
     SSDBCboLoc.Text = ""
    
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

'****************************************
'To Filter the List of Employees based on given Criteria
'****************************************
Private Sub cmdFilterBy_Click()
    'Define FilterType:
    '   0 - Reserved. No Filter
    '   1 - Filter By Category Code
    '   2 - Filter By Employee ID
    '   3 - Filter by Employee Name
    Dim filterType As Integer
    
    If chkFilterBy(0).Value = 0 And chkFilterBy(1).Value = 0 Then
      MsgBox "Please Select the Filter By Option", vbInformation, "Filter Clause Required"
      Exit Sub
    End If
  
    If chkFilterBy(1).Value = 1 And optFilterEmp(0).Value = False And optFilterEmp(1).Value = False Then
      MsgBox "Please Select ID / Name option from Employee", vbInformation, "Filter Clause Required"
      Exit Sub
    End If
    
    'Filter By Category Code
    If chkFilterBy(0).Value = 1 Then
        If Trim(SSDBCboLoc.Text) = vbNullString Then
          MsgBox "Please Select Category Code", vbInformation, "Data Required"
          Exit Sub
        Else
          filterType = 1
        End If
    'Filter By Employee
    ElseIf chkFilterBy(1).Value = 1 Then
        'Filter By Employee ID
        If optFilterEmp(0).Value = True Then
            If Trim(SSDBCboEmp.Text) = vbNullString Then
              MsgBox "Please Select Employee ID", vbInformation, "Data Required"
              Exit Sub
            Else                        'Only Employee ID is Checked and has Value
              filterType = 2
            End If
        ElseIf optFilterEmp(1).Value = True Then
            'Filter By Employee Name
            If Trim(ssdbcboName.Text) = vbNullString Then
                MsgBox "Please Select Employee Name", vbInformation, "Data Required"
                Exit Sub
            Else                        'Only Employee Name is Checked and has Value
                filterType = 3
            End If
        End If
    End If
    
    Call display(CDate(hireDate.Text), filterType)
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

  If Option1 = True Then
        dwnCustomer.AddItem "453" & "!" & "Dole Deciduous Division"
        dwnCustomer.AddItem "313" & "!" & "Dole"
  ElseIf Option2 = True Then
        dwnCustomer.AddItem "249" & "!" & "Bulk Services Co. Premier"
        dwnCustomer.AddItem "1638" & "!" & "Bulk"
  Else
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
        CustRS.Close
        Set CustRS = Nothing
  End If
        
  dwnCustomer.Columns(0).Width = 1611
  dwnCustomer.Columns(1).Width = 4261
  
End Sub

'****************************************
'To Fill Earning Type Drop Down
'****************************************
Private Sub dwnEarning_InitColumnProps()
  Dim i As Integer, EarnRS As Object
  Dim empId As String, earningID As String
  
  empId = arrEmpID(lstEmpName.ListIndex + 1)
  
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
        earningID = EarnRS.Fields("Earning_Type_ID").Value
        If (empId = UserID Or isSupervisor(empId)) And (earningID = "DT" Or earningID = "ST") Then
            'Do Nothing
        Else
            dwnEarning.AddItem earningID & "!" & EarnRS.Fields("Earning_Description").Value
        End If
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

Private Sub dwnlabor_InitColumnProps()
  Dim i As Integer, LaborRS As Object
  dwnlabor.Columns.RemoveAll

  dwnlabor.Columns.add 0

  dwnlabor.Columns(0).Caption = "Labor Type"

  Set LaborRS = OraDatabaseBNI.DBCreateDynaset("SELECT  * FROM LABOR_CATEGORY ORDER BY LABOR_TYPE", 0&)
  If LaborRS.EOF And LaborRS.BOF Then
    'Do Nothing
  Else
    LaborRS.MoveFirst
    Do While Not LaborRS.EOF
      dwnlabor.AddItem LaborRS.Fields("Labor_type").Value
      LaborRS.MoveNext
    Loop
  End If
  dwnlabor.Columns(0).Width = 2000
  LaborRS.Close
  Set LaborRS = Nothing
End Sub


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
  Dim sqlStmt(1) As String
  Dim continue As Boolean
  Dim service_code As String, service_name As String
    
  dwnService.Columns.RemoveAll
  For i = 0 To 1
    dwnService.Columns.add i
  Next
  
  dwnService.Columns(0).Caption = "Service Code"
  dwnService.Columns(1).Caption = "Service Name"
 
  'sqlStmt = " Select SERVICE_CODE, SERVICE_NAME from service where status='N' ORDER BY SERVICE_CODE UNION ALL " _
  '        & " select SERVICE_CODE, SERVICE_NAME from service WHERE STATUS <> 'N' or STATUS IS NULL ORDER BY SERVICE_CODE"
   sqlStmt(0) = " Select SERVICE_CODE, SERVICE_NAME from service where status='N' ORDER BY SERVICE_CODE"
   sqlStmt(1) = " select SERVICE_CODE, SERVICE_NAME from service WHERE SERVICE_CODE Not Like '6%' And (STATUS <> 'N' or STATUS IS NULL) ORDER BY SERVICE_CODE"
   
   For i = 0 To 1
    Set SvcRS = OraDatabase.DBCreateDynaset(sqlStmt(i), 0&)
    If SvcRS.EOF And SvcRS.BOF Then
      'Do Nothing
    Else
      SvcRS.MoveFirst
      Do While Not SvcRS.EOF
        service_code = GetValue(SvcRS.Fields("SERVICE_CODE").Value, "N/A")
        service_name = GetValue(SvcRS.Fields("SERVICE_NAME").Value, "N/A")
        dwnService.AddItem service_code & "!" & service_name
        SvcRS.MoveNext
      Loop
    End If
    dwnService.AddItem "" & "!" & ""
   Next
  
  dwnService.Columns(0).Width = 1011
  dwnService.Columns(1).Width = 5500
  'dwnService.ScrollBars = ssScrollBarsBoth
  SvcRS.Close
  Set SvcRS = Nothing
End Sub

'****************************************
'To Fill Special Code Drop Down
'****************************************
'****************************************
'Modified April, 2008.  Special Code field will now hold employee ID's, so that
'Astrid Outlaw (or any future employee) can enter time in lieu of other supvs, but can
'also enter said SUPV's name on the hourly detail record, so that said supv
'can later retrieve it for labor tickets
'***************************************************

Private Sub dwnSpec_InitColumnProps()
  Dim i As Integer, SpecRS As Object
  Dim sqlStmt As String
  dwnSpec.Columns.RemoveAll
  For i = 0 To 1
    dwnSpec.Columns.add i
  Next
  dwnSpec.Columns(0).Caption = "Employee Initials"
  dwnSpec.Columns(1).Caption = "Employee Name"
  
  sqlStmt = "SELECT GROUP_ID, USER_NAME, INITIALS FROM LCS_USER LU, SUPERVISOR_INITIALS SI WHERE LU.USER_ID = '" & UserID & "' AND LU.USER_ID = SI.USER_ID"
  Set dsSHORT_TERM_DATA = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
  If dsSHORT_TERM_DATA.Fields("GROUP_ID").Value = "4" Or dsSHORT_TERM_DATA.Fields("GROUP_ID").Value = "2" Then
      dwnSpec.AddItem dsSHORT_TERM_DATA.Fields("INITIALS").Value & "!" & dsSHORT_TERM_DATA.Fields("USER_NAME").Value
  Else
      sqlStmt = "SELECT USER_NAME, INITIALS FROM LCS_USER LU, SUPERVISOR_INITIALS SI WHERE LU.USER_ID = SI.USER_ID AND GROUP_ID IN ('4', '6') AND STATUS = 'A' ORDER BY INITIALS"
      Set SpecRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
  '' 2-21-2007, pwu
  '' Per HD# 2753, exclude special code '1401'
'  Set SpecRS = OraDatabase.DBCreateDynaset("Select * from Special_Code where SPECIAL_CODE NOT IN ('1401') Order by Special_Code", 0&)
'  Set SpecRS = OraDatabase.DBCreateDynaset("Select * from Lcs_user LU, Supervisor_initials SI where GROUP_ID IN ('4', '6') and status = 'A' and LU.User_ID = SI.User_Id Order by LU.User_Id", 0&)
        If SpecRS.EOF And SpecRS.BOF Then
          'Do Nothing
        Else
          SpecRS.MoveFirst
          dwnSpec.AddItem "N/A" & "!" & "DO NOT USE"
          Do While Not SpecRS.EOF
            dwnSpec.AddItem SpecRS.Fields("INITIALS").Value & "!" & SpecRS.Fields("USER_NAME").Value
            SpecRS.MoveNext
          Loop
        End If
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
  
  'If Option1 = True Then
  '      dwnVessel.AddItem "0" & "!" & "0"
  'ElseIf Option2 = True Then
  '      dwnVessel.AddItem "0" & "!" & "0"
  'Else
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
  'End If
End Sub
Private Sub Option1_Click()
    Call MouseHourlyGlass
    Call dwnCustomer_InitColumnProps
    'Call dwnVessel_InitColumnProps
    Call MouseNormal
End Sub

Private Sub Option2_Click()
    Call MouseHourlyGlass
    Call dwnCustomer_InitColumnProps
    'Call dwnVessel_InitColumnProps
    Call MouseNormal
End Sub

Private Sub Option3_Click()
    Call MouseHourlyGlass
    Call dwnCustomer_InitColumnProps
    'Call dwnVessel_InitColumnProps
    Call MouseNormal
End Sub

'****************************************
'To Store Grid Content on Variables for Previous Values
'****************************************
Private Sub SSDBGrid1_AfterColUpdate(ByVal ColIndex As Integer)
    Dim sqlStmt As String
    Dim dsService As Object
    Dim sixPM As Date, sevenPM As Date, onePM As Date, twelvePM As Date
    Dim twelveAM As Date, oneAM As Date, sixAM As Date, sevenAM As Date
    Dim startTime As Date, endTime As Date
    Dim EmpType As String
    Dim srvCode As String   ' Variable to store the Service Code
    
    EmpType = arrEmpType(lstEmpName.ListIndex + 1)
    
    sixPM = Format("06:00PM", "hh:mmAM/PM")
    sevenPM = Format("07:00PM", "hh:mmAM/PM")
    twelvePM = Format("12:00PM", "hh:mmAM/PM")
    onePM = Format("01:00PM", "hh:mmAM/PM")
    twelveAM = Format("12:00AM", "hh:mmAM/PM")
    oneAM = Format("01:00AM", "hh:mmAM/PM")
    sixAM = Format("06:00AM", "hh:mmAM/PM")
    sevenAM = Format("07:00AM", "hh:mmAM/PM")
  
    'Default endTime to be startTime + 1 so it is easier to pick the end time from the list
    If ColIndex = 1 And Trim(SSDBGrid1.Columns(1).Value) <> vbNullString Then
        startTime = Format(SSDBGrid1.Columns(1).Value, "hh:mmAM/PM")
        If Trim(SSDBGrid1.Columns(2).Value) = vbNullString Then
            endTime = DateAdd("h", 1, startTime)
            SSDBGrid1.Columns(2).Value = Format(endTime, "hh:mmAM/PM")
        End If
    End If
      
    'If the start time and end time covers the dinner time (6:00PM - 7:00PM), then
    'pay dinner is by default set to True
    If ColIndex = 1 Or ColIndex = 2 Then
        If Trim(SSDBGrid1.Columns(1).Value) <> vbNullString And Trim(SSDBGrid1.Columns(2).Value) <> vbNullString Then
            startTime = Format(SSDBGrid1.Columns(1).Value, "hh:mmAM/PM")
            endTime = Format(SSDBGrid1.Columns(2).Value, "hh:mmAM/PM")
            If startTime <= sixPM And endTime > sixPM Then
                SSDBGrid1.Columns(19).Value = True
            End If
        End If
    End If
    
    'Check that the duration time on column is correct if start time and end time are changed
    If (ColIndex = 1 Or ColIndex = 2 Or ColIndex = 18 Or ColIndex = 19) And dwnTime.ListAutoValidate = False Then
        dwnTime.ListAutoValidate = True
    End If
    
    If (ColIndex = 1 Or ColIndex = 2 Or ColIndex = 18 Or ColIndex = 19) Then
        If Trim(SSDBGrid1.Columns(1).Value) <> vbNullString And Trim(SSDBGrid1.Columns(2).Value) <> vbNullString Then
            If SSDBGrid1.Columns(18).Value = vbNullString Then SSDBGrid1.Columns(18).Value = False
            If SSDBGrid1.Columns(19).Value = vbNullString Then SSDBGrid1.Columns(19).Value = False
            SSDBGrid1.Columns(3).Value = CalculateDuration(hireDate.Text, SSDBGrid1.Columns(1).Value, SSDBGrid1.Columns(2).Value, SSDBGrid1.Columns(18).Value, SSDBGrid1.Columns(19).Value)
        End If
    End If
  
    If ColIndex = 3 Then dwnHrs.ListAutoValidate = True
    If ColIndex = 4 Then dwnEarning.ListAutoValidate = True
    
    If ColIndex = 6 Then
        srvCode = SSDBGrid1.Columns(6).Value
        
        If dwnService.ListAutoValidate = False Then dwnService.ListAutoValidate = True
        
        If SSDBGrid1.Columns(6).Value = "" Then Exit Sub
        
        If Trim(SSDBGrid1.Columns(1).Value) <> vbNullString And Trim(SSDBGrid1.Columns(2).Value) <> vbNullString Then
            startTime = Format(SSDBGrid1.Columns(1).Value, "hh:mmAM/PM")
            endTime = Format(SSDBGrid1.Columns(2).Value, "hh:mmAM/PM")
        Else
            MsgBox "Start time and End time have to be set first. Please try again."
            Exit Sub
        End If
        
        If Trim(srvCode) <> "5151" And Trim(srvCode) <> "5451" And Trim(srvCode) <> "5551" Then
            If EmpType = "REGR" Then
                If endTime > twelvePM And endTime < onePM Then
                    SSDBGrid1.Columns(18).Value = True
                    SSDBGrid1.Columns(2).Value = "01:00PM"
                ElseIf endTime > sixPM And endTime < sevenPM Then
                    SSDBGrid1.Columns(19).Value = True
                    SSDBGrid1.Columns(2).Value = "07:00PM"
                ElseIf endTime > twelveAM And endTime < oneAM Then
                    SSDBGrid1.Columns(2).Value = "01:00AM"
                ElseIf endTime > sixAM And endTime < sevenAM Then
                    SSDBGrid1.Columns(2).Value = "07:00AM"
                End If
                
                If startTime > twelvePM And startTime < onePM Then
                    SSDBGrid1.Columns(18).Value = True
                    SSDBGrid1.Columns(1).Value = "12:00PM"
                ElseIf startTime > sixPM And startTime < sevenPM Then
                    SSDBGrid1.Columns(19).Value = True
                    SSDBGrid1.Columns(1).Value = "06:00PM"
                ElseIf startTime > twelveAM And startTime < oneAM Then
                    SSDBGrid1.Columns(1).Value = "12:00AM"
                ElseIf startTime > sixAM And startTime < sevenAM Then
                    SSDBGrid1.Columns(1).Value = "06:00AM"
                End If
            End If
        End If
             
        If Trim(SSDBGrid1.Columns(1).Value) <> vbNullString And Trim(SSDBGrid1.Columns(2).Value) <> vbNullString Then
            If SSDBGrid1.Columns(18).Value = vbNullString Then SSDBGrid1.Columns(18).Value = False
            If SSDBGrid1.Columns(19).Value = vbNullString Then SSDBGrid1.Columns(19).Value = False
            SSDBGrid1.Columns(3).Value = CalculateDuration(hireDate.Text, SSDBGrid1.Columns(1).Value, SSDBGrid1.Columns(2).Value, SSDBGrid1.Columns(18).Value, SSDBGrid1.Columns(19).Value)
        End If

        If SSDBGrid1.Columns(5).Value = -1 Then
            sqlStmt = "SELECT * FROM SERVICE_LABOR_RATE_TYPE WHERE SERVICE_CODE ='" & Trim(SSDBGrid1.Columns(6).Value) & "'"
            Set dsService = OraDatabaseBNI.DBCreateDynaset(sqlStmt, 0&)
            If dsService.RecordCount > 0 Then SSDBGrid1.Columns(16).Value = dsService("LABOR_TYPE").Value
        End If
    End If
  
    If ColIndex = 7 Then dwnEquipment.ListAutoValidate = True
  
    If ColIndex = 8 Then dwnCommodity.ListAutoValidate = True
  
    If ColIndex = 9 Then dwnLocation.ListAutoValidate = True
  
    If ColIndex = 10 Then dwnVessel.ListAutoValidate = True
  
    If ColIndex = 11 Then dwnCustomer.ListAutoValidate = True
  
    If ColIndex = 15 Then dwnExactEnd.ListAutoValidate = True
    
    If SSDBGrid1.Columns(13).Value = "" Then
        SSDBGrid1.Columns(13).Value = dwnSpec.Columns(0).Value
    End If
  
End Sub

Private Sub SSDBGrid1_BeforeColUpdate(ByVal ColIndex As Integer, ByVal OldValue As Variant, Cancel As Integer)
    Select Case ColIndex
        Case 1, 2
            dwnTime.ListAutoValidate = True
        Case 3
            dwnHrs.ListAutoValidate = True
        Case 4
            dwnEarning.ListAutoValidate = True
        Case 6
            dwnService.ListAutoValidate = True
        Case 7
            dwnEquipment.ListAutoValidate = True
        Case 8
            dwnCommodity.ListAutoValidate = True
        Case 9
            dwnLocation.ListAutoValidate = True
        Case 10
            dwnVessel.ListAutoValidate = True
        Case 11
            dwnCustomer.ListAutoValidate = True
        Case 15
            dwnExactEnd.ListAutoValidate = True
    End Select
    
    CurrValue = SSDBGrid1.Columns(ColIndex).Value
    If timeKeeperPrivilege() = True Then
        'Do Nothing
        If CurrValue <> OldValue Then
            SSDBGrid1.Columns(14).Value = UserID
        End If
    ElseIf SSDBGrid1.row <= UBound(DiffSupervisor) Then
      If DiffSupervisor(SSDBGrid1.row) = True And CurrValue <> OldValue Then
        MsgBox "Can't Edit Employee Data that belongs to Supervisor " + Trim(SSDBGrid1.Columns(12).Value), vbInformation, "Authorization Required"
        SSDBGrid1.Columns(ColIndex).Value = Trim(OldValue)
      End If
    End If
    
    'Earning_Type (Double Time) DT can be selected only when the Duration is 1 Hour
    If ColIndex <= 4 And SSDBGrid1.Columns(3).Value > 1 And UCase(SSDBGrid1.Columns(4).Value) = "DT" Then
        MsgBox "Double Time Can't be More than an Hour", vbInformation, "Validation Failure"
        SSDBGrid1.Columns(ColIndex).Value = Trim(OldValue)
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
    
    If SSDBGrid1.row > 0 And SSDBGrid1.Col = 1 And SSDBGrid1.Columns(0).Value = "" Then
        SSDBGrid1.row = SSDBGrid1.row - 1
        start = SSDBGrid1.Columns(2).Value
        reg = SSDBGrid1.Columns(4).Value
        srv = SSDBGrid1.Columns(6).Value
        eq = SSDBGrid1.Columns(7).Value
        comm = SSDBGrid1.Columns(8).Value
        loc = SSDBGrid1.Columns(9).Value
        ves = SSDBGrid1.Columns(10).Value
        cust = SSDBGrid1.Columns(11).Value
        sup = SSDBGrid1.Columns(12).Value
        spec = SSDBGrid1.Columns(13).Value
        
        '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007
        'rm = SSDBGrid1.Columns(14).Value
        rm = ""
        
        lab = SSDBGrid1.Columns(16).Value
        
        SSDBGrid1.row = SSDBGrid1.row + 1
        If (sup = UserID) Then
          '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007 if there's already data there, don't get rid of it
          If Len(Trim(SSDBGrid1.Columns(1).Value)) = 0 Then
            SSDBGrid1.Columns(1).Value = start
            SSDBGrid1.Columns(4).Value = reg
            SSDBGrid1.Columns(6).Value = srv
            SSDBGrid1.Columns(7).Value = eq
            SSDBGrid1.Columns(8).Value = comm
            SSDBGrid1.Columns(9).Value = loc
            SSDBGrid1.Columns(10).Value = ves
            SSDBGrid1.Columns(11).Value = cust
            SSDBGrid1.Columns(12).Value = UserID
            SSDBGrid1.Columns(13).Value = spec
            SSDBGrid1.Columns(14).Value = rm
            SSDBGrid1.Columns(16).Value = lab
          End If
        End If
       
    End If
    
    If SSDBGrid1.Col <> -1 Then
        PrevValue = SSDBGrid1.Columns(SSDBGrid1.Col).Value
    End If
    
    If SSDBGrid1.AllowUpdate = True Then
        cmdUpdate.Enabled = True
    End If
End Sub

'****************************************
'To find the Total Hours Worked for the Day for each Employee - to display in Label
'****************************************
Private Function FindTotalHrs() As Single
  Dim totalHrs As Single, i As Integer
  totalHrs = 0
  SSDBGrid1.MoveFirst
  For i = 0 To SSDBGrid1.rows - 1
'     SSDBGrid1.row = i
    If Trim(SSDBGrid1.Columns(3).Value) <> vbNullString And UCase(Trim(SSDBGrid1.Columns(4).Value)) <> "LU" Then
      totalHrs = totalHrs + CSng(SSDBGrid1.Columns(3).Value)
    End If
    SSDBGrid1.MoveNext
  Next
  FindTotalHrs = totalHrs
  SSDBGrid1.MoveFirst
End Function

Private Function checkFields() As Boolean
    Dim i As Integer
    Dim Duration As String
    
    checkFields = False
   
    SSDBGrid1.MoveFirst
    For i = 0 To SSDBGrid1.rows - 1
        If Trim(SSDBGrid1.Columns(12).Value) = "" Then
                SSDBGrid1.Columns(12).Value = UserID
        End If
        If Trim(SSDBGrid1.Columns(1).Value) = "" Then
            MsgBox "Please Enter Start Time", vbInformation, "Start Time"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        If Trim(SSDBGrid1.Columns(2).Value) = "" Then
            MsgBox "Please Enter End Time", vbInformation, "End Time"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        If Trim(SSDBGrid1.Columns(3).Value) = "" Then
            MsgBox "Please Enter Duration", vbInformation, "Duration"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        If Trim(SSDBGrid1.Columns(6).Value) = "" Then
            MsgBox "Please Enter Service Code", vbInformation, "Service Code"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        If Trim(SSDBGrid1.Columns(8).Value) = "" Then
            'Msg Box "Please Enter Commodity Code", vbInformation, "Up dation Failure"
            MsgBox "Please Enter Commodity Code", vbInformation, "Commodity Code Blank"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        
        '6/30/2014 HD9497
        If Trim(SSDBGrid1.Columns(4).Value) = "VAC" Or Trim(SSDBGrid1.Columns(4).Value) = "SICK" Or Trim(SSDBGrid1.Columns(4).Value) = "PERS" Or Trim(SSDBGrid1.Columns(4).Value) = "HOL" Then
            ' if this is a supervisor-timeoff selection, we want to force certain parameters
            If Trim(SSDBGrid1.Columns(8).Value) <> "0" Then
                MsgBox "Commodity Code must be zero for Supervisor Time Off Earning Types", vbInformation, "Commodity Code"
                SSDBGrid1.SelectByCell = True
                Exit Function
            End If
            If Trim(SSDBGrid1.Columns(6).Value) <> "0" Then
                MsgBox "Service Code must be zero for Supervisor Time Off Earning Types", vbInformation, "Commodity Code"
                SSDBGrid1.SelectByCell = True
                Exit Function
            End If
            If Trim(SSDBGrid1.Columns(7).Value) <> "0" Then
                MsgBox "Equipment Code must be zero for Supervisor Time Off Earning Types", vbInformation, "Commodity Code"
                SSDBGrid1.SelectByCell = True
                Exit Function
            End If
            If Trim(SSDBGrid1.Columns(11).Value) <> "1" Then
                MsgBox "Customer Code must be 1 for Supervisor Time Off Earning Types", vbInformation, "Commodity Code"
                SSDBGrid1.SelectByCell = True
                Exit Function
            End If
            If Trim(SSDBGrid1.Columns(10).Value) <> "-1" Then
                MsgBox "Vessel must be -1 for Supervisor Time Off Earning Types", vbInformation, "Commodity Code"
                SSDBGrid1.SelectByCell = True
                Exit Function
            End If
            If Trim(SSDBGrid1.Columns(18).Value) = True Then
                MsgBox "Cannot Pay Lunch for Supervisor Time Off Earning Types", vbInformation, "Commodity Code"
                SSDBGrid1.SelectByCell = True
                Exit Function
            End If
            If Trim(SSDBGrid1.Columns(19).Value) = True Then
                MsgBox "Cannot Pay Dinner for Supervisor Time Off Earning Types", vbInformation, "Commodity Code"
                SSDBGrid1.SelectByCell = True
                Exit Function
            End If
            If isWeekendDay(CDate(hireDate.Text)) Then
                MsgBox "Cannot Assign Supervisor Time Off Earning Types for Weekends", vbInformation, "Commodity Code"
                SSDBGrid1.SelectByCell = True
                Exit Function
            End If
            If Trim(SSDBGrid1.Columns(3).Value) > "8.0" Then
                MsgBox "Cannot have more than 8 hours for Supervisor Time Off Earning Types", vbInformation, "Commodity Code"
                SSDBGrid1.SelectByCell = True
                Exit Function
            End If
        End If

        '5/2/2007 HD2759 Rudy:
        If Trim(SSDBGrid1.Columns(8).Value) = "0" Or Trim(SSDBGrid1.Columns(8).Value) = "0000" Then
'            Select Case Left(Trim(SSDBGrid1.Columns(6).Value), 1)    'per Don's e-mail of 5/2/2007, can be virtually any service code not just 6XXX
'            Case "6"
              'need to check DB, if not permissable give message, exit func
              If CheckSvcCommAllowZero(Trim(SSDBGrid1.Columns(6).Value)) = True Then
                'MsgBox "Service code " & SSDBGrid1.Columns(6).Value & " require a non zero Commodity Code!", vbInformation, "Invalid Commodity Code" 'Inigo verbiage
                MsgBox "Commodity Code zero is not accepted with Service code " & SSDBGrid1.Columns(6).Value & " !", vbInformation, "Invalid Commodity Code"     'Don D. verbiage
                SSDBGrid1.SelectByCell = True
                Exit Function
              End If
'            End Select
        ElseIf Trim(SSDBGrid1.Columns(6).Value) = "" Then
            MsgBox "Please Enter Service Code", vbInformation, "Service Code Blank"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        
        If Trim(SSDBGrid1.Columns(6).Value) = "7261" And (Trim(SSDBGrid1.Columns(8).Value) = "0" Or Trim(SSDBGrid1.Columns(8).Value) = "0000") Then
            MsgBox "The GEAR MAN service code must have a Commodity Code entered with it", vbInformation, "Gear Man Error"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If

        
        If Trim(SSDBGrid1.Columns(11).Value) = "" Then
            'Msg Box "Please Enter Customer Code ", vbInformation, "Up dation Failure"
            MsgBox "Please Enter Customer Code ", vbInformation, "Insufficient Data"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        If Trim(SSDBGrid1.Columns(10).Value) = "" Then
            'Msg Box "Please Enter Ship Number ", vbInformation, "Up dation Failure"
            MsgBox "Please Enter Ship Number ", vbInformation, "Insufficient Data"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        If Trim(SSDBGrid1.Columns(9).Value) = "" Then
            'Msg Box "Please Enter Category ", vbInformation, "Up dation Failure"
            MsgBox "Please Enter Category ", vbInformation, "Insufficient Data"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        
        If SSDBGrid1.Columns(13).Value = "N/A" Then
            MsgBox "Please Enter Employee Initials", vbInformation, "Insufficient Data"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
       
       If SSDBGrid1.Columns(14).Visible = True Then
            If Trim(SSDBGrid1.Columns(14).Value) = "" And SSDBGrid1.Columns(12).Value = UserID Then
                '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007 Per Jon Jaffe's 2560 this is optional
                'Msg Box "Please Enter Remark ", vbInformation, "Up dation Failure"
                MsgBox "Please Enter Remark ", vbInformation, "Insufficient Data"
                SSDBGrid1.SelectByCell = True
                Exit Function
            End If
        End If
        
        Duration = CalculateDuration(hireDate.Text, SSDBGrid1.Columns(1).Value, SSDBGrid1.Columns(2).Value, SSDBGrid1.Columns(18).Value, SSDBGrid1.Columns(19).Value)
       
        If (CSng(SSDBGrid1.Columns(3).Value) <> CSng(Duration)) Then
            MsgBox "The duration time is incorrect. The correct value should be " & Duration, vbInformation, "Incorrect Duration"
            SSDBGrid1.SelectByCell = True
            SSDBGrid1.Columns(3).Value = Duration
            Exit Function
        End If
        
        SSDBGrid1.MoveNext
    Next
    
    checkFields = True
End Function
Private Function getEarliestStartTime(empId As String, hire_date As Date) As Date
    Dim result As Date
    Dim sqlStmt As String
    Dim rs As Object
    
    sqlStmt = " SELECT TIME_IN" _
            & " FROM DAILY_HIRE_LIST" _
            & " WHERE EMPLOYEE_ID = '" & empId & "'" _
            & " AND HIRE_DATE = TO_DATE('" & hire_date & "','MM/DD/YYYY')"
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    Do While Not rs.EOF
        result = rs.Fields("TIME_IN").Value
        rs.MoveNext
    Loop
    getEarliestStartTime = result
End Function

Private Function checkTime() As Boolean
    Dim i As Integer, j As Integer, size As Integer
    Dim length As Integer
    Dim startTime(), endTime() As Date
    Dim earliestStartTime As Date
    Dim twelvePM As Date, onePM As Date, sixPM As Date, sevenPM As Date
    Dim empId As String
    Dim payLunch As Boolean, payDinner As Boolean
    Dim hire_date As Date
    
    hire_date = CDate(hireDate.Text)
    empId = arrEmpID(lstEmpName.ListIndex + 1)
    earliestStartTime = getEarliestStartTime(empId, hire_date)
    
    twelvePM = CDate(CStr(hire_date) & " " & "12:00PM")
    onePM = CDate(CStr(hire_date) & " " & "01:00PM")
    sixPM = CDate(CStr(hire_date) & " " & "06:00PM")
    sevenPM = CDate(CStr(hire_date) & " " & "07:00PM")
    
    length = SSDBGrid1.rows
    ReDim startTime(length + 4)
    ReDim endTime(length + 4)
    
    SSDBGrid1.MoveFirst
    size = 1
    For i = 1 To length
        startTime(size) = CDate(Format(hireDate.Text, "MM/DD/YYYY") & " " & Format(SSDBGrid1.Columns(1).Value, "hh:nnAM/PM"))
        endTime(size) = CDate(Format(hireDate.Text, "MM/DD/YYYY") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM"))
        If endTime(size) < startTime(size) Then
            endTime(size) = Format(hire_date + 1, "MM/DD/YYYY") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM")
        End If
       
        If (startTime(size) <= twelvePM And endTime(size) >= onePM) Then
            If SSDBGrid1.Columns(18).Value = False Then
                If startTime(size) = twelvePM Then
                    startTime(size) = onePM
                ElseIf endTime(size) = onePM Then
                    endTime(size) = twelvePM
                Else
                    size = size + 1
                    endTime(size) = endTime(size - 1)
                    startTime(size) = onePM
                    endTime(size - 1) = twelvePM
                End If
            End If
        End If
        
        If (startTime(size) <= sixPM And endTime(size) >= sevenPM) Then
            If SSDBGrid1.Columns(19).Value = False Then
                If startTime(size) = sixPM Then
                    startTime(size) = sevenPM
                ElseIf endTime(size) = sevenPM Then
                    endTime(size) = sixPM
                Else
                    size = size + 1
                    endTime(size) = endTime(size - 1)
                    startTime(size) = sevenPM
                    endTime(size - 1) = sixPM
                End If
            End If
        End If
        
        size = size + 1
        SSDBGrid1.MoveNext
    Next
    
    SSDBGrid1.MoveFirst
    For i = 1 To size - 1
        'Start time can't be earlier than the hiring start time
        If (startTime(i) < earliestStartTime) Then
            checkTime = False
            MsgBox "The start time can't be earlier than the hiring start time " & CStr(earliestStartTime) & "! Please correct this!", vbInformation, "Incorrect Time"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        
        'Start time can't equal to the end time
        If (startTime(i) = endTime(i)) Then
            checkTime = False
            MsgBox "The start time equals to the end time! Please correct this!", vbInformation, "Incorrect Time"
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
            
        For j = 1 To size - 1
            If i <> j Then
                If (startTime(i) >= startTime(j) And startTime(i) < endTime(j)) Or (endTime(i) > startTime(j) And endTime(i) <= endTime(j)) Then
                   checkTime = False
                   MsgBox "You have entered the overlap time for the employee! Please correct this!", vbInformation, "Overlap Time"
                   SSDBGrid1.SelectByCell = True
                   Exit Function
                End If
            End If
        Next
        SSDBGrid1.MoveNext
    Next
    
    checkTime = True
End Function

Private Function findEmployeeTypeID(empId As String) As String
    Dim sqlStmt As String
    Dim IdRS As Object
    
    sqlStmt = "SELECT EMPLOYEE_TYPE_ID FROM EMPLOYEE WHERE EMPLOYEE_ID = '" & empId & "'"
    Set IdRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If IdRS.EOF And IdRS.BOF Then
        findEmployeeTypeID = ""
    Else
        IdRS.MoveFirst
        Do While Not IdRS.EOF
            findEmployeeTypeID = UCase(IdRS.Fields("EMPLOYEE_TYPE_ID").Value)
            IdRS.MoveNext
        Loop
    End If
    
    IdRS.Close
End Function

'called from 1 place cmdUpdate_Click
Private Sub saveData()
    Dim i As Integer
    Dim empId As String
    Dim EmpType As String
    Dim hire_date As Date
    
    hire_date = CDate(Format(hireDate.Text, "MM/DD/YYYY"))
    empId = arrEmpID(lstEmpName.ListIndex + 1)
    EmpType = findEmployeeTypeID(empId)
    
    If empId = "E406167" Then     'Karen Lewandowski REGR
        Call saveDataJanitor(empId, hire_date)
    ElseIf EmpType = "SUPV" Then
        Call saveDataSUPV(empId, hire_date)
    ElseIf EmpType = "CASC" Or EmpType = "CAS" Then
        Call saveDataCAS("CASC", empId, hire_date)
    ElseIf EmpType = "CASB" Then
        Call saveDataCAS("CASB", empId, hire_date)
    ElseIf EmpType = "REGR" Then
        Call saveDataREGR(empId, hire_date)
    End If
End Sub

Private Sub saveDataJanitor(empId As String, hire_date As Date)
    OraSession.BeginTrans
    On Error GoTo ErrorHandler
    
    Call DeleteHDData(hire_date, hire_date, empId)
    Call DeleteSFHDData(hire_date, empId)
    Call updateAllDataFromGrid(hire_date, empId)
   
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
    Else
        OraSession.Rollback
    End If
    
    Exit Sub
    
ErrorHandler:
    OraSession.Rollback
End Sub

Private Sub saveDataSUPV(empId As String, hire_date As Date)
Dim bFailed As Boolean      '5/2/2007 HD2759 Rudy:
    bFailed = False
    
    OraSession.BeginTrans
    On Error GoTo ErrorHandler
    
    Call DeleteHDData(hire_date, hire_date, empId)
    Call DeleteSFHDData(hire_date, empId)
    Call updateAllDataFromGrid(hire_date, empId)
   
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
    Else
        OraSession.Rollback
        bFailed = True
    End If
    
    Exit Sub
    
ErrorHandler:
  If bFailed = False Then       '5/2/2007 HD2759 Rudy:  needed so it doesn't attemp rollback 2X, which will error
    OraSession.Rollback
  End If
End Sub
Private Sub updateAllDataFromGrid(hire_date As Date, empId As String)
    On Error GoTo ErrHandler
    
    Dim iUpdRecCnt As Integer
    Dim iInsRecCnt As Integer
    Dim sqlStmt As String
    Dim row_number As Integer
    Dim dStartTime As Date, dEndTime As Date, dBreakTime As Date, twelveAM As Date
    Dim sf_row_number, service_code, equipment_id, commodity_code, location_id As String
    Dim vessel_id, special_code, customer_id As String, earning_type_id As String, supervisor_id As String
    Dim row_num As String, remark As String
    Dim rs As Object
    Dim Duration As Single
    Dim i As Integer
    Dim payLunch As String, payDinner As String
    Dim twelvePM As Date, sixPM As Date, onePM As Date, sevenPM As Date
    twelvePM = CDate(CStr(hire_date) & " " & "12:00PM")
    onePM = CDate(CStr(hire_date) & " " & "01:00PM")
    sixPM = CDate(CStr(hire_date) & " " & "06:00PM")
    sevenPM = CDate(CStr(hire_date) & " " & "07:00PM")
   
    SSDBGrid1.MoveFirst
    row_number = 1
    For i = 0 To SSDBGrid1.rows - 1
        dStartTime = Format(hire_date, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(1).Value, "hh:nnAM/PM")
        dEndTime = Format(hire_date, "MM/DD/YYYY") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM")
        If dEndTime < dStartTime Then
            dEndTime = Format(hire_date + 1, "MM/DD/YYYY") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM")
        End If
            
        If Trim(SSDBGrid1.Columns(0).Value) = "" Then
            sqlStmt = "select row_num_seq.nextval row_num from dual"
            Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            SSDBGrid1.Columns(0).Value = rs.Fields("row_num").Value
        End If
            
        sf_row_number = SSDBGrid1.Columns(0).Value
        Duration = SSDBGrid1.Columns(3).Value
        earning_type_id = SSDBGrid1.Columns(4).Value
        ' This is for janitor only
        If earning_type_id = "" Then
            earning_type_id = "REG"
        End If
        ' -------------
        service_code = SSDBGrid1.Columns(6).Value
        equipment_id = SSDBGrid1.Columns(7).Value
        If equipment_id = "" Then
            equipment_id = 0
        End If
        commodity_code = SSDBGrid1.Columns(8).Value
        location_id = SSDBGrid1.Columns(9).Value
        vessel_id = SSDBGrid1.Columns(10).Value
        special_code = SSDBGrid1.Columns(13).Value
        customer_id = SSDBGrid1.Columns(11).Value
        If (SSDBGrid1.Columns(12).Value = "") Then
            supervisor_id = UserID
        Else
            supervisor_id = SSDBGrid1.Columns(12).Value
        End If
                
        If SSDBGrid1.Columns(18).Value = True And (dStartTime < onePM And dEndTime > twelvePM) Then
            payLunch = "Y"
        Else
            payLunch = "N"
        End If
        
        If SSDBGrid1.Columns(19).Value = True And (dStartTime < sevenPM And dEndTime > sixPM) Then
            payDinner = "Y"
        Else
            payDinner = "N"
        End If
       
        'remark = SSDBGrid1.Columns(14).Value
        remark = Replace(SSDBGrid1.Columns(14).Value, "'", "`")     '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007
        'DO NOT have to reformat sqlStmt, remark is the comment field
    
        sqlStmt = " INSERT INTO SF_HOURLY_DETAIL " _
                & " ( Row_Number, Hire_Date, Employee_Id, Start_Time, End_Time, Duration, Service_code, equipment_id, " _
                & " commodity_code, location_id, vessel_id, customer_id, User_ID, special_code, earning_type_id, time_entry, TIME_UPDATE, remark, pay_lunch, pay_dinner) " _
                & " VALUES (" & sf_row_number & ", to_date('" & hire_date & "', 'MM/DD/YYYY'), '" & empId & "', " _
                & " to_date('" & dStartTime & "', 'mm/dd/yyyy HH:MI:SS AM'), to_date('" & dEndTime & "', 'mm/dd/yyyy HH:MI:SS AM'), " & Duration & ", " _
                & service_code & ", " & equipment_id & ", " & commodity_code & ", '" & location_id & "', " _
                & vessel_id & ", " & customer_id & ", '" & supervisor_id & "', '" & special_code & "', '" & earning_type_id & "', " _
                & " to_date('" & Now & "', 'mm/dd/yyyy HH:MI:SS AM'), to_date('" & Now & "', 'mm/dd/yyyy HH:MI:SS AM'), '" & remark & "', '" & payLunch & "','" & payDinner & "')"
       
        iUpdRecCnt = OraDatabase.ExecuteSQL(sqlStmt)
        If iUpdRecCnt = 0 Then
            OraSession.Rollback
            Exit Sub
        End If
        
        sqlStmt = " INSERT INTO HOURLY_DETAIL " _
                & " ( SF_row_number, Row_Number, Hire_Date, Employee_Id, Start_Time, End_Time, Duration, Service_code, equipment_id, " _
                & " commodity_code, location_id, vessel_id, customer_id, User_ID, special_code, earning_type_id, time_entry, TIME_UPDATE, remark) " _
                & " VALUES (" & sf_row_number & "," & row_number & ", to_date('" & hire_date & "', 'MM/DD/YYYY'), '" & empId & "', " _
                & " to_date('" & dStartTime & "', 'mm/dd/yyyy HH:MI:SS AM'), to_date('" & dEndTime & "', 'mm/dd/yyyy HH:MI:SS AM'), " & Duration & ", " _
                & service_code & ", " & equipment_id & ", " & commodity_code & ", '" & location_id & "', " _
                & vessel_id & ", " & customer_id & ", '" & UserID & "', '" & special_code & "', '" & earning_type_id & "', " _
                & " to_date('" & Now & "', 'mm/dd/yyyy HH:MI:SS AM'), to_date('" & Now & "', 'mm/dd/yyyy HH:MI:SS AM'), '" & remark & "')"
        iUpdRecCnt = OraDatabase.ExecuteSQL(sqlStmt)
        If iUpdRecCnt = 0 Then
            OraSession.Rollback
            Exit Sub
        End If
        row_number = row_number + 1
        
        SSDBGrid1.MoveNext
    Next
    Exit Sub
    
ErrHandler:
    Call TransRollBack(Err.Number, Err.Description)
End Sub

Private Function findRegTimeOnDays(startDay As Date, endDay As Date, empId As String, holiday As Date)
    Dim sqlStmt As String, startTime As String, endTime As String, payLunch As String, payDinner As String
    Dim hrRS As Object
    Dim hour(6) As Single
    Dim Index As Integer
    Dim length As Single, Duration As Single
    Dim isHoliday As Boolean
    Dim monday As Date
    monday = findMonday(startDay)
    hour(0) = hour(1) = hour(2) = hour(3) = hour(4) = hour(5) = hour(6) = 0
    
    sqlStmt = " SELECT HIRE_DATE, START_TIME, END_TIME, DURATION, PAY_LUNCH, PAY_DINNER" _
            & " FROM SF_HOURLY_DETAIL" _
            & " WHERE EMPLOYEE_ID = '" & empId & "' " _
            & " AND HIRE_DATE >= TO_DATE('" & Format(startDay, "MM/DD/YYYY") & "', 'mm/dd/yyyy')" _
            & " AND HIRE_DATE <= To_DATE('" & Format(endDay, "MM/DD/YYYY") & "', 'mm/dd/yyyy')" _
            & " ORDER BY HIRE_DATE, START_TIME"
            
    Set hrRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    hrRS.MoveFirst
    Do While Not hrRS.EOF
        If Not IsNull(hrRS.Fields("HIRE_DATE").Value) Then
            startTime = hrRS.Fields("START_TIME").Value
            endTime = hrRS.Fields("END_TIME").Value
            Duration = hrRS.Fields("DURATION").Value
            payLunch = hrRS.Fields("PAY_LUNCH").Value
            payDinner = hrRS.Fields("PAY_DINNER").Value
            If IsNull(payLunch) Then
                payLunch = "N"
            End If
            If IsNull(payDinner) Then
                payDinner = "N"
            End If
            isHoliday = isDayHoliday(hrRS.Fields("HIRE_DATE").Value, holiday)
            If Not IsNull(startTime) And Not IsNull(endTime) Then
                'hour(CDate(hrRS.Fields("HIRE_DATE").Value) - monday) = hrRS.Fields("TOTAL").Value
                Index = CDate(hrRS.Fields("HIRE_DATE").Value) - monday
                'Index = CDate(hrRS.Fields("HIRE_DATE").Value) - startDay
                length = timeIn40Hour(CDate(startTime), CDate(endTime), Duration, payLunch, payDinner, isHoliday)
                hour(Index) = hour(Index) + length
            End If
        End If
        hrRS.MoveNext
    Loop
    
    findRegTimeOnDays = hour()
        
End Function

Private Function getSFRowNumber(hire_date As Date, empId As String) As String
    Dim sqlStmt As String
    Dim sfRows As Object
    Dim rowNum, result As String
    
    sqlStmt = "SELECT row_number from sf_hourly_detail " _
            & " where employee_id = '" & empId & "'" _
            & " and hire_date = to_date('" & hire_date & "', 'mm/dd/yyyy')"
            
    Set sfRows = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    sfRows.MoveFirst
    Do While Not sfRows.EOF
        rowNum = sfRows.Fields("row_number").Value
        result = result + rowNum + ","
        sfRows.MoveNext
    Loop
    
    result = Trim(result)
    If (result <> "") Then
        result = Left(result, Len(result) - 1)
    End If
    getSFRowNumber = result
End Function

Private Sub saveDataREGR(empId As String, hire_date As Date)
   
    Dim monday As Date, friday As Date, sunday As Date, breakDate As Date, holiday As Date
    Dim totalRegBFHD As Single
    Dim hour() As Single, oldTotal As Single, newTotal As Single, oldReg As Single, newReg As Single
    Dim i As Integer, hdindex As Integer
    Dim isWeekend As Boolean
    
    monday = findMonday(hire_date)
    friday = findFriday(hire_date)
    sunday = findSunday(hire_date)
    holiday = holidayInPeriod("REGR", monday, sunday)
    isWeekend = isWeekendDay(hire_date)
    hdindex = hire_date - monday
    
    OraSession.BeginTrans
    On Error GoTo ErrorHandler
    
    hour = findRegTimeOnDays(hire_date, hire_date, empId, holiday)
    oldReg = hour(hdindex)

    Call DeleteHDData(hire_date, hire_date, empId)
    Call DeleteSFHDData(hire_date, empId)
    Call updateSFHDDataFromGrid(hire_date, empId)
    hour = findRegTimeOnDays(hire_date, hire_date, empId, holiday)
    newReg = hour(hdindex)
    
    If isWeekend Then
        Call updateHDDataREGR(empId, hire_date, hire_date, True, holiday, 0)
    ElseIf (oldReg = newReg) Then
        hour = findRegTimeOnDays(monday, hire_date - 1, empId, holiday)
        For i = 0 To 6
            totalRegBFHD = totalRegBFHD + hour(i)
        Next
        Call updateHDDataREGR(empId, hire_date, hire_date, False, holiday, totalRegBFHD)
    Else
        hour = findRegTimeOnDays(monday, hire_date - 1, empId, holiday)
        For i = 0 To 6
            totalRegBFHD = totalRegBFHD + hour(i)
        Next
        Call DeleteHDData(hire_date, friday, empId)
        Call updateHDDataREGR(empId, hire_date, friday, False, holiday, totalRegBFHD)
    End If
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
    Else
        OraSession.Rollback
    End If
    
    Exit Sub
    
ErrorHandler:
    OraSession.Rollback
    
End Sub

Private Sub updateHDDataREGR(empId As String, startDay As Date, endDay As Date, isWeekend As Boolean, holiday As Date, totalBFSD As Single)
    On Error GoTo ErrHandler

    Dim overnightFlag As Boolean, isHoliday As Boolean
    Dim hours() As String
    Dim lastEndTime As Date, myDay As Date, breakDate As Date
    Dim sixAM As Date, sixPM As Date, sevenPM As Date
    'Dim iInsRecCnt As Integer
    Dim dsDetail As Object
    Dim j As Integer, size As Integer, k As Integer
    Dim sqlStmt As String
    Dim row_number As Integer
    Dim dStartTime As Date, dEndTime As Date, dBreakTime As Date, twelveAM As Date
    Dim sf_row_number, service_code, equipment_id, commodity_code, location_id As String
    Dim vessel_id, special_code, customer_id As String, remark As String
    Dim payLunch As String, payDinner As String, supervisor_id As String
    Dim row_num As String
    Dim rs As Object
    Dim length As Single, Total As Single, Duration As Single, myReg As Single
    Dim myStartTime(2) As Date, myEndTime(2) As Date, myduration(2) As Single, myEarningType(2) As String
    
    If totalBFSD > 40 Then
        breakDate = startDay - 1
    Else
        breakDate = endDay + 1
    End If
    
    myDay = startDay
    Total = totalBFSD       'totalBFSD means the total before start date, how many hours doe one have prior to the day working on
    
    Do While myDay <= endDay
        isHoliday = isDayHoliday(myDay, holiday)
        sixAM = Format(CStr(myDay) & " 6:00AM", "mm/dd/yyyy hh:mmAM/PM")
        sixPM = Format(CStr(myDay) & " 6:00PM", "mm/dd/yyyy hh:mmAM/PM")
        sevenPM = Format(CStr(myDay) & " 7:00PM", "mm/dd/yyyy hh:mmAM/PM")
    
        sqlStmt = " Select * from SF_HOURLY_DETAIL " _
                & " Where hire_date = to_date('" + Format(myDay, "MM/DD/YYYY") + "','mm/dd/yyyy')" _
                & " AND EMPLOYEE_ID = '" + empId + "'" _
                & " ORDER BY START_TIME"
        Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        rs.MoveFirst
        
        Do While Not rs.EOF
            'row_number = 1
            Duration = rs.Fields("DURATION").Value
            dStartTime = rs.Fields("START_TIME").Value
            dEndTime = rs.Fields("END_TIME").Value
            sf_row_number = rs.Fields("ROW_NUMBER").Value
            service_code = rs.Fields("SERVICE_CODE").Value
            equipment_id = rs.Fields("equipment_id").Value
            If equipment_id = "" Then
                equipment_id = 0
            End If
            commodity_code = rs.Fields("commodity_code").Value
            location_id = rs.Fields("location_id").Value
            vessel_id = rs.Fields("vessel_id").Value
            special_code = rs.Fields("special_code").Value
            customer_id = rs.Fields("customer_ID").Value
            supervisor_id = rs.Fields("User_ID").Value
            payLunch = rs.Fields("pay_lunch").Value
            payDinner = rs.Fields("pay_dinner").Value
            remark = GetValue(rs.Fields("remark").Value, "")
   
            If Not IsNull(dStartTime) And Not IsNull(dEndTime) Then
                length = timeIn40Hour(CDate(dStartTime), CDate(dEndTime), Duration, payLunch, payDinner, isHoliday)
                Total = Total + length
            End If
            
            If Total > 40 Then
                If breakDate > endDay Then
                    myReg = 40 - (Total - length)
                    breakDate = myDay
                ElseIf breakDate = endDay Then
                    myReg = 0
                End If
            Else
                myReg = -1
            End If
    
            overnightFlag = False
            'duganHours = findDuganHours(dStartTime, dEndTime, payLunch, payDinner)
            hours = findTimeBuckets(dStartTime, dEndTime, payLunch, payDinner, isWeekend, isHoliday)
            
            Dim lnum As Integer, hnum As Integer
            hnum = UBound(hours, 1)
            lnum = LBound(hours, 1)
            j = lnum
            size = lnum
            Dim records() As String
            ReDim records(hnum + 1, 3)
            Do While j <= hnum
                records(size, 0) = hours(j, 0)
                records(size, 1) = hours(j, 1)
                records(size, 2) = hours(j, 2)
                
                If hours(j, 3) <> "" Then
                    records(size, 3) = hours(j, 3)
                ElseIf (isWeekend) Or isHoliday Or myDay > breakDate Then
                    records(size, 3) = "OT"
                ElseIf CDate(hours(j, 1)) <= sixAM Then
                    records(size, 3) = "OT"
                ElseIf CDate(hours(j, 0)) >= sixPM Then
                    records(size, 3) = "OT"
                'This is for time over 40
                ElseIf myDay > breakDate Then
                    records(size, 3) = "OT"
                ElseIf myReg >= 0 Or breakDate = myDay Then
                    If myReg = 0 Then
                        records(size, 3) = "OT"
                    ElseIf myReg < CSng(hours(j, 2)) Then
                        records(size, 1) = getBreakTime(CDate(records(size, 0)), myReg, payLunch, payDinner)
                        'records(size, 2) = CSng(hours(j, 2)) - myReg
                        records(size, 2) = myReg
                        records(size, 3) = "REG"
                        size = size + 1
                        records(size, 0) = records(size - 1, 1)
                        records(size, 1) = hours(j, 1)
                        records(size, 2) = CSng(hours(j, 2)) - myReg
                        records(size, 3) = "OT"
                        myReg = 0
                    ElseIf myReg > CSng(hours(j, 2)) Then
                        records(size, 3) = "REG"
                        myReg = myReg - CSng(hours(j, 2))
                        
                    '2853 3/29/2007 Rudy:  what if they're equal (not handled in two elseifs above)
                    ElseIf myReg = CSng(hours(j, 2)) Then
                        records(size, 3) = "REG"            'need this
                        
                        'myReg = myReg - CSng(hours(j, 2))   'unsure
                        myReg = 0                           'Per Inigo Thomas
                    'Else        '2853 3/29/2007 Rudy: need this else?, would need to code
                        '
                    End If
                ElseIf Total <= 40 Or breakDate > myDay Then
                    records(size, 3) = "REG"
                'Else            '2853 3/29/2007 Rudy: need this else?, would need to code
                    '
                End If
                j = j + 1
                size = size + 1
            Loop
            
            For k = lnum To hnum + 1
                If records(k, 0) = "" Then
                    'skip
                Else
                    If (DateValue(CDate(records(k, 1))) - DateValue(CDate(records(k, 0))) = 1) Then
                        'row_number = 1
                        overnightFlag = True
                    End If
                    
                    'sqlStmt = " INSERT INTO HOURLY_DETAIL " _
                    '& " ( Row_Number, SF_Row_Number, Hire_Date, Employee_Id, Start_Time, End_Time, Duration, Service_code, equipment_id, " _
                    '& " commodity_code, location_id, vessel_id, customer_id, User_ID, special_code, time_entry, TIME_UPDATE, earning_type_id, remark) " _
                    '& " VALUES (" & row_number & ", " & sf_row_number & ", to_date('" & DateValue(CDate(records(k, 0))) & "', 'MM/DD/YYYY'), '" & EmpID & "', " _
                    '& " to_date('" & records(k, 0) & "', 'mm/dd/yyyy HH:MI:SS AM'), to_date('" & records(k, 1) & "', 'mm/dd/yyyy HH:MI:SS AM'), " & records(k, 2) & ", " _
                    '& service_code & ", " & equipment_id & ", " & commodity_code & ", '" & location_id & "', " _
                    '& vessel_id & ", " & customer_id & ", '" & supervisor_id & "', '" & special_code & "', " _
                    '& "to_date('" & Now & "', 'mm/dd/yyyy HH:MI:SS AM'), to_date('" & Now & "', 'mm/dd/yyyy HH:MI:SS AM'), '" _
                    '& records(k, 3) & "', '" & remark & "')"
                
                    'iInsRecCnt = OraDatabase.ExecuteSQL(sqlStmt)
                    
                    'If iInsRecCnt = 0 Then
                    '    OraSession.Rollback
                    '    Exit Sub
                    'End If
                    
                    sqlStmt = " SELECT * FROM HOURLY_DETAIL"
                    Set dsDetail = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
                    
                    dsDetail.AddNew
                    dsDetail.Fields("ROW_NUMBER").Value = row_number
                    dsDetail.Fields("SF_ROW_NUMBER").Value = sf_row_number
                    dsDetail.Fields("HIRE_DATE").Value = Format(DateValue(CDate(records(k, 0))), "mm/dd/yyyy")
                    dsDetail.Fields("EMPLOYEE_ID").Value = empId
                    dsDetail.Fields("START_TIME").Value = Format(records(k, 0), "mm/dd/yyyy hh:mmAM/PM")
                    dsDetail.Fields("END_TIME").Value = Format(records(k, 1), "mm/dd/yyyy hh:mmAM/PM")
                    
                    dsDetail.Fields("earning_type_id").Value = records(k, 3)
                    dsDetail.Fields("DURATION").Value = records(k, 2)
                    dsDetail.Fields("SERVICE_CODE").Value = service_code
                    dsDetail.Fields("EQUIPMENT_ID").Value = equipment_id
                    dsDetail.Fields("COMMODITY_CODE").Value = commodity_code
                    dsDetail.Fields("LOCATION_ID").Value = location_id
                    dsDetail.Fields("VESSEL_ID").Value = vessel_id
                    dsDetail.Fields("CUSTOMER_ID").Value = customer_id
                    dsDetail.Fields("USER_ID").Value = supervisor_id
                    dsDetail.Fields("SPECIAL_CODE").Value = special_code
                    dsDetail.Fields("TIME_ENTRY").Value = Now
                    dsDetail.Fields("TIME_UPDATE").Value = Now
                    dsDetail.Fields("REMARK").Value = remark
                    
                    If Not IsNull(rs.Fields("EXACT_END").Value) Then
                        dsDetail.Fields("EXACT_END").Value = Format(rs.Fields("EXACT_END").Value, "mm/dd/yyyy hh:mmAM/PM")
                    End If
                    dsDetail.Update
                    
                    If OraDatabase.LastServerErr <> 0 Then
                         OraSession.Rollback
                         Exit Sub
                    End If

                    If overnightFlag = True Then
                        row_number = 1
                        overnightFlag = False
                    Else
                        row_number = row_number + 1
                    End If
                    
                    dsDetail.Close
                    Set dsDetail = Nothing
                End If
            Next
            
            rs.MoveNext
        Loop
    
        myDay = myDay + 1
        rs.Close
        Set rs = Nothing
    Loop
                      
    Exit Sub
    
ErrHandler:
    Call TransRollBack(Err.Number, Err.Description)
    
End Sub

Private Function getWHFromLastWeek(monday As Date, empId As String) As Single
    Dim sqlStmt As String, rowNum As String
    Dim rs As Object
    Dim wh As Single
    wh = 0
    
    rowNum = getSFRowNumber(monday, empId)
    sqlStmt = " Select DURATION from HOURLY_DETAIL " _
            & " Where hire_date = to_date('" + Format(monday, "MM/DD/YYYY") + "','mm/dd/yyyy')" _
            & " AND EMPLOYEE_ID = '" + empId + "'"
    If rowNum <> "" Then
        sqlStmt = sqlStmt & " AND SF_ROW_NUMBER NOT IN (" & rowNum & ")"
    End If
    
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    rs.MoveFirst
    Do While Not rs.EOF
        If Not IsNull(rs.Fields("DURATION").Value) Then
            wh = wh + rs.Fields("DURATION").Value
        End If
        rs.MoveNext
    Loop
    
    getWHFromLastWeek = wh
    
End Function

Private Sub saveDataCAS(EmpType As String, empId As String, hire_date As Date)
    Dim monday As Date, friday As Date, sunday As Date, breakDate As Date, holiday As Date
    Dim hourOnHD As Single
    Dim hour() As Single, regHour() As Single, oldTotal As Single, newTotal As Single, hourFromLastWeek As Single
    Dim oldTime As Single, newTime As Single, reg As Single, totalRegHours As Single
    Dim i As Integer, hdindex As Integer
    Dim isWeekend As Boolean

    'Rules on how to calculate hours for casual workers
    'MAJOR EDIT, Adam Walter, July 2008.
            ' a contract renegotiation now makes the Dinner hour perform the same as the lunch hour
            
            
    '1) CASB:
    '   a) All weekend hours and holiday hours are OT (overtime)
    '   b) If paylunch, the lunch hour will be DT (Dugan hour)
    '   c) Any hour after 40 regular hours should be OT unless it is paid lunch hour
    '   d) OT and DT hours should not count into the 40 regular hours
    '   e) Paid dinner hour is treated as any other hour, --------NOT ANYMORE--------
    '      REG (regular hour) if <= 40 hours, OT if > 40 hours
    '   e2) If paydinner, the dinner hour will be DT (Dugan hour)
    '2) CASC:
    '   a) No weekend benifit
    '   b) No holiday benifit   (Taken care by the holidayInPeriod function)
    '   c) No lunch dugan hour
    '   d) Any hour after 40 regular hours should be OT hours
    
    
    monday = findMonday(hire_date)
    friday = findFriday(hire_date)
    sunday = findSunday(hire_date)
    holiday = holidayInPeriod(EmpType, monday, sunday)
    isWeekend = isWeekendDay(hire_date)
    hourFromLastWeek = getWHFromLastWeek(monday, empId)
    oldTotal = hourFromLastWeek
    newTotal = hourFromLastWeek
 
    OraSession.BeginTrans
    On Error GoTo ErrorHandler
    
    'Get total hours for each day
    If EmpType = "CASC" Then
        hour = getHourOnDays(monday, sunday, empId)
    ElseIf EmpType = "CASB" Then
        hour = getHourOnDays(monday, friday, empId)
    End If
    
    'Get regular hours hours for each day
    If EmpType = "CASC" Then
        regHour = getRegHourOnDays(monday, sunday, empId)
    ElseIf EmpType = "CASB" Then
        regHour = getRegHourOnDays(monday, friday, empId)
    End If
    
    For i = 0 To 6
        oldTotal = oldTotal + hour(i)
    Next i
    
    Call DeleteHDData(hire_date, hire_date, empId)
    Call DeleteSFHDData(hire_date, empId)
    Call updateSFHDDataFromGrid(hire_date, empId)
    
    hdindex = hire_date - monday
    
    oldTime = hour(hdindex)
    hour(hdindex) = FindTotalHrs

    i = 0
    Do While i <= 6
        newTotal = newTotal + hour(i)
        i = i + 1
    Loop
      
    'calculate regular hours need to reach 40 hours from current hire date on
    For i = 0 To hdindex - 1
        totalRegHours = totalRegHours + regHour(i)
    Next i

    reg = 40 - totalRegHours
       
    If EmpType = "CASB" And isWeekend = True Then
        Call updateHDDataCAS(empId, EmpType, hire_date, hire_date, reg, holiday)
    ElseIf oldTime = hour(hdindex) Then
        Call updateHDDataCAS(empId, EmpType, hire_date, hire_date, reg, holiday)
    ElseIf oldTotal <= 40 And newTotal <= 40 Then
        Call updateHDDataCAS(empId, EmpType, hire_date, hire_date, reg, holiday)
    Else
        If EmpType = "CASC" Then
            Call DeleteHDData(hire_date, sunday, empId)
            Call updateHDDataCAS(empId, EmpType, hire_date, sunday, reg, holiday)
        ElseIf EmpType = "CASB" Then
            Call DeleteHDData(hire_date, friday, empId)
            Call updateHDDataCAS(empId, EmpType, hire_date, friday, reg, holiday)
        End If
    End If
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
    Else
        OraSession.Rollback
    End If
    
    Exit Sub
    
ErrorHandler:
    OraSession.Rollback
    
End Sub

Private Sub updateHDDataCAS(empId As String, EmpType As String, startDate As Date, endDate As Date, RegularHour As Single, holiday As Date)
    On Error GoTo ErrHandler
    Dim myDay As Date, friday As Date, sunday As Date, twelveAM As Date
    Dim nextMonday As Date, nextSunday As Date, holidayInNextWeek As Date
    Dim dsDetail As Object
    Dim sqlStmt As String
    Dim row_number As Integer
    Dim dStartTime As Date, dEndTime As Date, dBreakTime As Date, dNewBreakTime As Date
    Dim sf_row_number, service_code, equipment_id, commodity_code, location_id As String
    Dim vessel_id, special_code, customer_id As String, remark As String
    Dim payLunch As String, payDinner As String, supervisor_id As String
    Dim row_num As String
    Dim rs As Object
    Dim reg As Single, Duration As Single, newReg As Single
    Dim i As Integer, j As Integer
    Dim flag As Boolean, overnightFlag As Boolean
    Dim myEarningType(8) As String, myStartTime(8) As Date, myEndTime(8) As Date, myduration(8) As Single
    Dim size As Integer
    Dim twelvePM As Date, onePM As Date, sixPM As Date, sevenPM As Date
    Dim isWeekend As Boolean
    Dim DefaultRate As String
    Dim BlockStartTime As Date, OtBlockEndTime As Date
    Dim RunningHourTotalPreLunch As Double

    reg = RegularHour
    If reg <= 0 Then
        DefaultRate = "OT"
    Else
        DefaultRate = "REG"
    End If


    ' MAJOR EDIT, Adam Walter, July 2008.
    ' a contract renegotiation now says that dinner hours are subject to the same DT rules
    ' as lunch hours.
    ' however, the way this is written as of July 2008, trying to modify it as such would mean adding tons of new code
    ' therefore, I am gutting basically this whole routine, and re-writing it from the ground up.
    ' the new logic will be that I define a block for each possible time frame.
    '-' 12AM - noon                             ARRAY INDICIE (0)
    '-' noon - 1PM                              ARRAY INDICIE (1)
    '-' 1PM - 6PM                               ARRAY INDICIE (2)
    '-' 6PM - 7PM                               ARRAY INDICIE (3)
    '-' 7PM - 12AM (next day)                   ARRAY INDICIE (4)
    '-' 12AM - noon (of the next day)           ARRAY INDICIE (5)
    '-' and one wildcard entry, if the jump from "regular" to "overtime" happens within any of the above ranges.            (6)
    ' ---The assumption is that no one will hire someone for day X, and then on day X+1, pay them past noon,
    ' ---without re-hiring them for day X+1.
    ' for each block that has duration > 0, I will add an entry to HOURLY_DETAIL.
    ' CASBs and CASCs both have the same "blocks", the difference is the earning type for Bs and Cs during the
    ' 12-1 and 6-7 ranges.
    
    RunningHourTotalPreLunch = 0
    
    myDay = startDate
    Do While myDay <= endDate
        'Reset row number for hourly detail entries of my day
        row_number = 0
        
        'Move into the loop and use myDay instead of startDate  -- LFW, 4/16/03
        friday = findFriday(myDay)
        sunday = findSunday(myDay)
        twelvePM = CDate(CStr(myDay) & " " & "12:00PM")
        onePM = CDate(CStr(myDay) & " " & "01:00PM")
        sixPM = CDate(CStr(myDay) & " " & "06:00PM")
        sevenPM = CDate(CStr(myDay) & " " & "07:00PM")
        
        'Check if the current day is weekend, override the value passed into the function
        'cause it only tells us whether the hire date is weekend or not  --LFW, 4/16/03
        ' IMPORTANT NOTE:  "isweekend" is only set for CASB employees.  CASC's weekends are treated as weekdays.
        If EmpType = "CASC" Then
            isWeekend = False
        ElseIf EmpType = "CASB" Then
            isWeekend = isWeekendDay(myDay)
        End If

        sqlStmt = " Select * from SF_HOURLY_DETAIL " _
                & " Where hire_date = to_date('" + Format(myDay, "MM/DD/YYYY") + "','mm/dd/yyyy')" _
                & " AND EMPLOYEE_ID = '" + empId + "'" _
                & " order by start_time"
        Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        rs.MoveFirst
        
        Do While Not rs.EOF
            myduration(6) = 0 'initialize in case not set later (I.E. no overtime this week)
            
            Duration = rs.Fields("DURATION").Value
            dStartTime = rs.Fields("START_TIME").Value
            dEndTime = rs.Fields("END_TIME").Value
            If (DateValue(dEndTime) - DateValue(dStartTime)) = 1 Then
                twelveAM = CDate(CStr(DateValue(dEndTime)) & " 12:00AM")
                overnightFlag = True
            Else
                overnightFlag = False
            End If
                        
            equipment_id = rs.Fields("equipment_id").Value
            If equipment_id = "" Then
                equipment_id = 0
            End If
            
            sf_row_number = rs.Fields("ROW_NUMBER").Value
            service_code = rs.Fields("SERVICE_CODE").Value
            commodity_code = rs.Fields("commodity_code").Value
            location_id = rs.Fields("location_id").Value
            vessel_id = rs.Fields("vessel_id").Value
            special_code = rs.Fields("special_code").Value
            customer_id = rs.Fields("customer_ID").Value
            supervisor_id = rs.Fields("User_id").Value
            payLunch = rs.Fields("pay_lunch").Value
            payDinner = rs.Fields("pay_dinner").Value
            remark = GetValue(rs.Fields("remark").Value, "")
            
'            size = -1
'
'            'Make possibly mutiple entries out of the one SFHD entry --LFW, 4/16/03
'            If (myDay = holiday) Or (isWeekend = True) Then
'                size = 0
'                myStartTime(0) = dStartTime
'                myEndTime(0) = dEndTime
'                myEarningType(0) = "OT"
'                myduration(0) = Duration
'            Else
'                If (reg <= 0) Then  'no more regular hours
'                    size = 0
'                    myStartTime(0) = dStartTime
'                    myEndTime(0) = dEndTime
'                    myEarningType(0) = "OT"
'                    myduration(0) = Duration
'                ElseIf (reg >= Duration) Then
'                    size = 0
'                    myStartTime(0) = dStartTime
'                    myEndTime(0) = dEndTime
'                    myEarningType(0) = "REG"
'                    myduration(0) = Duration
'                    reg = reg - Duration
'                Else
'                    size = 1
'                    myStartTime(0) = dStartTime
'                    myEndTime(0) = getBreakTime(myStartTime(0), reg, payLunch, payDinner)
'                    myduration(0) = reg
'                    myEarningType(0) = "REG"
'
'                    myStartTime(1) = myEndTime(0)
'                    myEndTime(1) = dEndTime
'                    myEarningType(1) = "OT"
'                    myduration(1) = Duration - reg
'                    reg = 0
'                End If
'            End If
'
'            'Added this block to make lunch hour dugan hour possible for CASB
'            '-- LFW, 2/28/03 modified on 4/15/03
'            If EmpType = "CASB" And payLunch = "Y" Then
'                If size = 0 Then    'have one entry after the 1st If structure
'                    If myStartTime(0) < twelvePM Then
'                        If myEndTime(0) <= onePM Then
'                            myEndTime(0) = twelvePM
'                            myduration(0) = DateDiff("n", myStartTime(0), myEndTime(0)) / 60
'
'                            myStartTime(1) = twelvePM
'                            myEndTime(1) = dEndTime
'                            myEarningType(1) = "DT"
'                            myduration(1) = DateDiff("n", myStartTime(1), myEndTime(1)) / 60
'                            size = 1
'                        Else
'                            myEndTime(0) = twelvePM
'                            myduration(0) = DateDiff("n", myStartTime(0), myEndTime(0)) / 60
'
'                            myStartTime(1) = twelvePM
'                            myEndTime(1) = onePM
'                            myEarningType(1) = "DT"
'                            myduration(1) = 1
'
'                            myStartTime(2) = onePM
'                            myEndTime(2) = dEndTime
'                            myEarningType(2) = myEarningType(0)
'                            myduration(2) = DateDiff("n", myStartTime(2), myEndTime(2)) / 60
'                            size = 2
'                        End If
'                    Else        'myStartTime >= twelvePM
'                         If myEndTime(0) <= onePM Then
'                            myEarningType(0) = "DT"
'                         Else
'                            myEndTime(0) = onePM
'                            myduration(0) = DateDiff("n", myStartTime(0), myEndTime(0)) / 60
'
'                            myEarningType(1) = myEarningType(0)
'                            myEarningType(0) = "DT"
'
'                            myStartTime(1) = onePM
'                            myEndTime(1) = dEndTime
'                            myduration(1) = DateDiff("n", myStartTime(1), myEndTime(1)) / 60
'                            size = 1
'                        End If
'                    End If
'                Else            'size = 1 (two entries)
'                    dBreakTime = myEndTime(0)
'                    If (dBreakTime < twelvePM) Then
'                        myEndTime(1) = twelvePM
'                        myduration(1) = DateDiff("n", myStartTime(1), myEndTime(1)) / 60
'                        If (dEndTime > onePM) Then
'                            myStartTime(2) = twelvePM
'                            myEndTime(2) = onePM
'                            myEarningType(2) = "DT"
'                            myduration(2) = 1
'
'                            myStartTime(3) = onePM
'                            myEndTime(3) = dEndTime
'                            myEarningType(3) = "OT"
'                            myduration(3) = DateDiff("n", myStartTime(3), myEndTime(3)) / 60
'                            size = 3
'                        Else
'                            myStartTime(2) = twelvePM
'                            myEndTime(2) = dEndTime
'                            myEarningType(2) = "DT"
'                            myduration(2) = DateDiff("n", myStartTime(2), myEndTime(2)) / 60
'                            size = 2
'                        End If
'                    ElseIf (dBreakTime = twelvePM) Then
'                        If (dEndTime > onePM) Then
'                            myEndTime(1) = onePM
'                            myduration(1) = 1
'                            myEarningType(1) = "DT"
'
'                            myStartTime(2) = onePM
'                            myEndTime(2) = dEndTime
'                            myduration(2) = DateDiff("n", myStartTime(2), myEndTime(2)) / 60
'                            myEarningType(2) = "OT"
'                            size = 2
'                        Else
'                            myEarningType(1) = "DT"
'                            size = 1
'                        End If
'                    ElseIf (dBreakTime <= onePM) Then
'                        If myStartTime(0) >= twelvePM Then
'                            If (dEndTime <= onePM) Then
'                                myEndTime(0) = dEndTime
'                                myEarningType(0) = "DT"
'                                reg = myduration(0)
'                                myduration(0) = DateDiff("n", myStartTime(0), myEndTime(0)) / 60
'                                size = 0
'                            Else
'                                myEndTime(0) = onePM
'                                myEarningType(0) = "DT"
'                                reg = myduration(0)
'                                myduration(0) = DateDiff("n", myStartTime(0), myEndTime(0)) / 60
'
'                                dNewBreakTime = DateAdd("n", reg * 60, onePM)
'
'                                If (dEndTime <= dNewBreakTime) Then
'                                    myStartTime(1) = onePM
'                                    myEndTime(1) = dEndTime
'                                    myduration(1) = DateDiff("n", myStartTime(1), myEndTime(1)) / 60
'                                    myEarningType(1) = "REG"
'
'                                    reg = reg - myduration(1)
'                                    size = 1
'                                Else
'                                    myStartTime(1) = onePM
'                                    myEndTime(1) = dNewBreakTime
'                                    myduration(1) = DateDiff("n", myStartTime(1), myEndTime(1)) / 60
'                                    myEarningType(1) = "REG"
'
'                                    myStartTime(2) = dNewBreakTime
'                                    myEndTime(2) = dEndTime
'                                    myduration(2) = DateDiff("n", myStartTime(2), myEndTime(2)) / 60
'                                    myEarningType(2) = "OT"
'
'                                    reg = 0
'                                    size = 2
'                                End If
'                            End If
'                        Else
'                            myEndTime(0) = twelvePM
'                            myduration(0) = DateDiff("n", myStartTime(0), myEndTime(0)) / 60
'
'                            reg = DateDiff("n", twelvePM, dBreakTime) / 60
'
'                            If (dEndTime > onePM) Then
'                                dNewBreakTime = DateAdd("n", reg * 60, onePM)
'                                myStartTime(1) = twelvePM
'                                myEndTime(1) = onePM
'                                myduration(1) = 1
'                                myEarningType(1) = "DT"
'
'                                If (dEndTime <= dNewBreakTime) Then
'                                    myStartTime(2) = onePM
'                                    myEndTime(2) = dEndTime
'                                    myEarningType(2) = "REG"
'                                    myduration(2) = DateDiff("n", myStartTime(2), myEndTime(2)) / 60
'
'                                    reg = reg - myduration(2)
'                                    size = 2
'                                Else
'                                    myStartTime(2) = onePM
'                                    myEndTime(2) = dNewBreakTime
'                                    myEarningType(2) = "REG"
'                                    myduration(2) = DateDiff("n", myStartTime(2), myEndTime(2)) / 60
'
'                                    myStartTime(3) = dNewBreakTime
'                                    myEndTime(3) = dEndTime
'                                    myduration(3) = DateDiff("n", myStartTime(3), myEndTime(3)) / 60
'                                    myEarningType(3) = "OT"
'
'                                    reg = 0
'                                    size = 3
'                                End If
'                            Else
'                                myStartTime(1) = twelvePM
'                                myEndTime(1) = dEndTime
'                                myduration(1) = DateDiff("n", myStartTime(1), myEndTime(1)) / 60
'                                myEarningType(1) = "DT"
'                                size = 1
'                            End If
'                        End If
'                    Else        'dBreakTime > onePM
'                        myEndTime(0) = twelvePM
'                        myduration(0) = DateDiff("n", myStartTime(0), myEndTime(0)) / 60
'
'                        dNewBreakTime = DateAdd("n", 60, dBreakTime)
'                        reg = 1
'
'                        myStartTime(1) = twelvePM
'                        myEndTime(1) = onePM
'                        myduration(1) = 1
'                        myEarningType(1) = "DT"
'
'                        If (dEndTime <= dNewBreakTime) Then
'                            myStartTime(2) = onePM
'                            myEndTime(2) = dEndTime
'                            myEarningType(2) = "REG"
'                            myduration(2) = DateDiff("n", myStartTime(2), myEndTime(2)) / 60
'
'                            reg = reg - myduration(2)
'                            size = 2
'                        Else
'                            myStartTime(2) = onePM
'                            myEndTime(2) = dNewBreakTime
'                            myEarningType(2) = "REG"
'                            myduration(2) = DateDiff("n", myStartTime(2), myEndTime(2)) / 60
'
'                            myStartTime(3) = dNewBreakTime
'                            myEndTime(3) = dEndTime
'                            myduration(3) = DateDiff("n", myStartTime(3), myEndTime(3)) / 60
'                            myEarningType(3) = "OT"
'
'                            reg = 0
'                            size = 3
'                        End If
'                    End If
'                End If
'            End If
'
'            'Make more hourly detail entries if it is overnight, modified on 4/16/03  --LFW
'            If overnightFlag = True And dEndTime > twelveAM Then
'                'First find the entry has twelveAM in
'                Dim breakpoint As Integer
'                Dim break As Boolean
'                break = False
'
'                For j = 0 To size
'                    If DateValue(myStartTime(j)) = myDay And DateValue(myEndTime(j)) = myDay + 1 Then
'                        breakpoint = j
'                        If myEndTime(j) = twelveAM Then
'                            break = False
'                        Else
'                            break = True
'                        End If
'                    End If
'                Next j
'
'                If break = True Then
'                    For j = size + 1 To breakpoint + 2 Step -1
'                        myStartTime(j) = myStartTime(j - 1)
'                        myEndTime(j) = myEndTime(j - 1)
'                        myEarningType(j) = myEarningType(j - 1)
'                        myduration(j) = myduration(j - 1)
'                    Next j
'
'                    myEndTime(breakpoint + 1) = myEndTime(breakpoint)
'                    myStartTime(breakpoint + 1) = twelveAM
'                    myduration(breakpoint + 1) = DateDiff("n", myStartTime(breakpoint + 1), myEndTime(breakpoint + 1)) / 60
'                    myEarningType(breakpoint + 1) = myEarningType(breakpoint)
'
'                    myduration(breakpoint) = myduration(breakpoint) - myduration(breakpoint + 1)
'                    myEndTime(breakpoint) = twelveAM
'                    size = size + 1
'                End If
'
'                If (myDay = friday) Or (myDay + 1 = holiday) Then
'                    For j = breakpoint + 1 To size
'                        If myEarningType(j) = "REG" Then
'                            reg = reg + myduration(j)
'                        End If
'                        myEarningType(j) = "OT"
'                    Next j
'                ElseIf (myDay = sunday) Then
'                    nextMonday = sunday + 1
'                    nextSunday = findSunday(nextMonday)
'                    holidayInNextWeek = holidayInPeriod(EmpType, nextMonday, nextSunday)
'
'                    If Not (nextMonday = holidayInNextWeek) Then
'                        For j = breakpoint + 1 To size
'                            myEarningType(j) = "REG"
'                        Next j
'                    End If
'                ElseIf (myDay = holiday) Then   'only MLK for CASB
'                    If reg > 0 Then
'                        For j = breakpoint + 1 To size
'                            myEarningType(j) = "REG"
'                            reg = reg - myduration(j)
'                        Next j
'                    End If
'                End If
'            End If
'
'            For j = 0 To size
'
'                sqlStmt = " SELECT * FROM HOURLY_DETAIL"
'                Set dsDetail = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'
'                dsDetail.AddNew
'                dsDetail.Fields("ROW_NUMBER").Value = row_number
'                dsDetail.Fields("SF_ROW_NUMBER").Value = sf_row_number
'                dsDetail.Fields("HIRE_DATE").Value = Format(DateValue(myStartTime(j)), "mm/dd/yyyy")
'                dsDetail.Fields("EMPLOYEE_ID").Value = empId
'                dsDetail.Fields("START_TIME").Value = Format(myStartTime(j), "mm/dd/yyyy hh:mmAM/PM")
'                dsDetail.Fields("END_TIME").Value = Format(myEndTime(j), "mm/dd/yyyy hh:mmAM/PM")
'                dsDetail.Fields("earning_type_id").Value = myEarningType(j)
'                dsDetail.Fields("DURATION").Value = myduration(j)
'                dsDetail.Fields("SERVICE_CODE").Value = service_code
'                dsDetail.Fields("EQUIPMENT_ID").Value = equipment_id
'                dsDetail.Fields("COMMODITY_CODE").Value = commodity_code
'                dsDetail.Fields("LOCATION_ID").Value = location_id
'                dsDetail.Fields("VESSEL_ID").Value = vessel_id
'                dsDetail.Fields("CUSTOMER_ID").Value = customer_id
'                dsDetail.Fields("USER_ID").Value = supervisor_id
'                dsDetail.Fields("SPECIAL_CODE").Value = special_code
'                dsDetail.Fields("TIME_ENTRY").Value = Now
'                dsDetail.Fields("TIME_UPDATE").Value = Now
'                dsDetail.Fields("REMARK").Value = remark
'
'                If Not IsNull(rs.Fields("EXACT_END").Value) Then
'                    dsDetail.Fields("EXACT_END").Value = Format(rs.Fields("EXACT_END").Value, "mm/dd/yyyy hh:mmAM/PM")
'                End If
'                dsDetail.Update
'
'                If OraDatabase.LastServerErr <> 0 Then
'                     OraSession.Rollback
'                     Exit Sub
'                End If
'
'                row_number = row_number + 1
'                dsDetail.Close
'                Set dsDetail = Nothing
'            Next j
'

            'block 0:  pre-lunch
            If dStartTime < twelvePM Then
                If dEndTime <= twelvePM Then
                    myduration(0) = DateDiff("n", dStartTime, dEndTime) / 60
                Else
                    myduration(0) = DateDiff("n", dStartTime, twelvePM) / 60
                End If
                    
                If isWeekend = True Then 'weekend hours for CASB don't count towards overtime calculations
                    myStartTime(0) = dStartTime
                    myEndTime(0) = DateAdd("n", myduration(0) * 60, dStartTime)
                    myEarningType(0) = "OT"
                Else
                    
                    If reg - myduration(0) < 0 And DefaultRate = "REG" Then ' if still in regular hours, but about to go over
                        myStartTime(0) = dStartTime
                        myEndTime(0) = DateAdd("n", reg * 60, dStartTime) ' remaining regular hours
                        myEarningType(0) = "REG"
                        myStartTime(6) = myEndTime(0)
                        myEndTime(6) = DateAdd("n", (myduration(0) - reg) * 60, myStartTime(6)) ' hours past regular 40
                        myEarningType(6) = "OT"
                        
                        myduration(6) = (myduration(0) - reg) ' hours past regular 40.  Might be zero.
                        myduration(0) = reg ' Might be zero.
                        
                        DefaultRate = "OT" ' we are now in OT hours
                    Else ' either not in regular hours, or not about to go over
                        myStartTime(0) = dStartTime
                        myEndTime(0) = DateAdd("n", myduration(0) * 60, dStartTime)
                        myEarningType(0) = DefaultRate
                    End If
                    reg = reg - myduration(0) 'remove spent hours from 40 total towards OT count
                End If
            Else
                myduration(0) = 0 ' 0 out so no data sent to DB during INSERT phase.
            End If
            
            
            RunningHourTotalPreLunch = RunningHourTotalPreLunch + myduration(0) + myduration(6)
            
            
            'block 1:  lunch
            If dStartTime < onePM And payLunch = "Y" Then
                
                If dStartTime > twelvePM Then
                    If dEndTime <= onePM Then
                        myduration(1) = DateDiff("n", dStartTime, dEndTime) / 60
                    Else
                        myduration(1) = DateDiff("n", dStartTime, onePM) / 60
                    End If
                    BlockStartTime = dStartTime
                    
                Else
                    If dEndTime <= onePM Then
                        myduration(1) = DateDiff("n", twelvePM, dEndTime) / 60
                    Else
                        myduration(1) = DateDiff("n", twelvePM, onePM) / 60
                    End If
                    BlockStartTime = twelvePM
                    
                End If
            
                If isWeekend = True Then 'weekend hours for CASB don't count towards overtime calculations
                    myStartTime(1) = BlockStartTime
                    myEndTime(1) = DateAdd("n", myduration(1) * 60, BlockStartTime)
                    myEarningType(1) = "DT"
                Else
                
                    If reg - myduration(1) < 0 And DefaultRate = "REG" Then ' if still in regular hours, but about to go over
                        myStartTime(1) = BlockStartTime
                        myEndTime(1) = DateAdd("n", reg * 60, BlockStartTime) ' remaining regular hours
                        If EmpType = "CASB" And RunningHourTotalPreLunch >= 4 Then
                            myEarningType(1) = "DT"
                        Else
                            myEarningType(1) = "REG"
                        End If
                        myStartTime(6) = myEndTime(1)
                        myEndTime(6) = DateAdd("n", (myduration(1) - reg) * 60, myStartTime(6)) ' hours past regular 40
                        If EmpType = "CASB" And RunningHourTotalPreLunch >= 4 Then
                            myEarningType(6) = "DT"
                        Else
                            myEarningType(6) = "OT"
                        End If
                        
                        myduration(6) = (myduration(1) - reg) ' hours past regular 40.  Might be zero.
                        myduration(1) = reg ' Might be zero.
                        
                        DefaultRate = "OT" ' we are now in OT hours
                    Else ' either not in regular hours, or not about to go over
                        myStartTime(1) = BlockStartTime
                        myEndTime(1) = DateAdd("n", myduration(1) * 60, BlockStartTime)
                        If EmpType = "CASB" And RunningHourTotalPreLunch >= 4 Then
                            myEarningType(1) = "DT"
                        Else
                            myEarningType(1) = DefaultRate
                        End If
                    End If
                    reg = reg - myduration(1) 'remove spent hours from 40 total towards OT count
                End If
            Else
                myduration(1) = 0 ' 0 out so no data sent to DB during INSERT phase.
            End If
            
            'block 2:  between lunch and dinner
            If dStartTime < sixPM And dEndTime > onePM Then
                If dStartTime > onePM Then
                    If dEndTime <= sixPM Then
                        myduration(2) = DateDiff("n", dStartTime, dEndTime) / 60
                    Else
                        myduration(2) = DateDiff("n", dStartTime, sixPM) / 60
                    End If
                    BlockStartTime = dStartTime
                    
                Else
                    If dEndTime <= sixPM Then
                        myduration(2) = DateDiff("n", onePM, dEndTime) / 60
                    Else
                        myduration(2) = DateDiff("n", onePM, sixPM) / 60
                    End If
                    BlockStartTime = onePM
                    
                End If
                    
                If isWeekend = True Then 'weekend hours for CASB don't count towards overtime calculations
                    myStartTime(2) = BlockStartTime
                    myEndTime(2) = DateAdd("n", myduration(2) * 60, BlockStartTime)
                    myEarningType(2) = "OT"
                Else
                    If reg - myduration(2) < 0 And DefaultRate = "REG" Then ' if still in regular hours, but about to go over
                        myStartTime(2) = BlockStartTime
                        myEndTime(2) = DateAdd("n", reg * 60, BlockStartTime) ' remaining regular hours
                        myEarningType(2) = "REG"
                        myStartTime(6) = myEndTime(2)
                        myEndTime(6) = DateAdd("n", (myduration(2) - reg) * 60, myStartTime(6)) ' hours past regular 40
                        myEarningType(6) = "OT"
                        
                        myduration(6) = (myduration(2) - reg) ' hours past regular 40.  Might be zero.
                        myduration(2) = reg ' Might be zero.
                        
                        DefaultRate = "OT" ' we are now in OT hours
                    Else ' either not in regular hours, or not about to go over
                        myStartTime(2) = BlockStartTime
                        myEndTime(2) = DateAdd("n", myduration(2) * 60, BlockStartTime)
                        myEarningType(2) = DefaultRate
                    End If
                    reg = reg - myduration(2) 'remove spent hours from 40 total towards OT count
                End If
            Else
                myduration(2) = 0 ' 0 out so no data sent to DB during INSERT phase.
            End If
            

            'block 3:  dinner
            If dStartTime < sevenPM And payDinner = "Y" Then
                If dStartTime > sixPM Then
                    If dEndTime <= sevenPM Then
                        myduration(3) = DateDiff("n", dStartTime, dEndTime) / 60
                    Else
                        myduration(3) = DateDiff("n", dStartTime, sevenPM) / 60
                    End If
                    BlockStartTime = dStartTime
                    
                Else
                    If dEndTime <= sevenPM Then
                        myduration(3) = DateDiff("n", sixPM, dEndTime) / 60
                    Else
                        myduration(3) = DateDiff("n", sixPM, sevenPM) / 60
                    End If
                    BlockStartTime = sixPM
                    
                End If
            
                If isWeekend = True Then 'weekend hours for CASB don't count towards overtime calculations
                    myStartTime(3) = BlockStartTime
                    myEndTime(3) = DateAdd("n", myduration(3) * 60, BlockStartTime)
                    myEarningType(3) = "DT"
                Else
                    If reg - myduration(3) < 0 And DefaultRate = "REG" Then ' if still in regular hours, but about to go over
                        myStartTime(3) = BlockStartTime
                        myEndTime(3) = DateAdd("n", reg * 60, BlockStartTime) ' remaining regular hours
                        If EmpType = "CASB" Then
                            myEarningType(3) = "DT"
                        Else
                            myEarningType(3) = "REG"
                        End If
                        myStartTime(6) = myEndTime(3)
                        myEndTime(6) = DateAdd("n", (myduration(3) - reg) * 60, myStartTime(6)) ' hours past regular 40
                        If EmpType = "CASB" Then
                            myEarningType(6) = "DT"
                        Else
                            myEarningType(6) = "OT"
                        End If
                        
                        myduration(6) = (myduration(3) - reg) ' hours past regular 40.  Might be zero.
                        myduration(3) = reg ' Might be zero.
                        
                        DefaultRate = "OT" ' we are now in OT hours
                    Else ' either not in regular hours, or not about to go over
                        myStartTime(3) = BlockStartTime
                        myEndTime(3) = DateAdd("n", myduration(3) * 60, BlockStartTime)
                        If EmpType = "CASB" Then
                            myEarningType(3) = "DT"
                        Else
                            myEarningType(3) = DefaultRate
                        End If
                    End If
                    reg = reg - myduration(3) 'remove spent hours from 40 total towards OT count
                End If
            Else
                myduration(3) = 0 ' 0 out so no data sent to DB during INSERT phase.
            End If
        
            'block 4:  after dinner, before midnight
            If dEndTime > sevenPM Then
                If dStartTime > sevenPM Then
                    If overnightFlag = False Then
                        myduration(4) = DateDiff("n", dStartTime, dEndTime) / 60
                    Else
                        myduration(4) = DateDiff("n", dStartTime, twelveAM) / 60
                    End If
                    BlockStartTime = dStartTime
                    
                Else
                    If overnightFlag = False Then
                        myduration(4) = DateDiff("n", sevenPM, dEndTime) / 60
                    Else
                        myduration(4) = DateDiff("n", sevenPM, twelveAM) / 60
                    End If
                    BlockStartTime = sevenPM
                    
                End If
                    
                If isWeekend = True Then 'weekend hours for CASB don't count towards overtime calculations
                    myStartTime(4) = BlockStartTime
                    myEndTime(4) = DateAdd("n", myduration(4) * 60, BlockStartTime)
                    myEarningType(4) = "OT"
                Else
                    If reg - myduration(4) < 0 And DefaultRate = "REG" Then ' if still in regular hours, but about to go over
                        myStartTime(4) = BlockStartTime
                        myEndTime(4) = DateAdd("n", reg * 60, BlockStartTime) ' remaining regular hours
                        myEarningType(4) = "REG"
                        myStartTime(6) = myEndTime(4)
                        myEndTime(6) = DateAdd("n", (myduration(4) - reg) * 60, myStartTime(6)) ' hours past regular 40
                        myEarningType(6) = "OT"
                        
                        myduration(6) = (myduration(4) - reg) ' hours past regular 40.  Might be zero.
                        myduration(4) = reg ' Might be zero.
                        
                        DefaultRate = "OT" ' we are now in OT hours
                    Else ' either not in regular hours, or not about to go over
                        myStartTime(4) = BlockStartTime
                        myEndTime(4) = DateAdd("n", myduration(4) * 60, BlockStartTime)
                        myEarningType(4) = DefaultRate
                    End If
                    reg = reg - myduration(4) 'remove spent hours from 40 total towards OT count
                End If
            Else
                myduration(4) = 0 ' 0 out so no data sent to DB during INSERT phase.
            End If
        
        
            'block 5:  after midnight (next day)
            If overnightFlag = True Then
                myduration(5) = DateDiff("n", twelveAM, dEndTime) / 60
                
                If isWeekend = True Then 'weekend hours for CASB don't count towards overtime calculations
                    myStartTime(5) = twelveAM
                    myEndTime(5) = DateAdd("n", myduration(5) * 60, twelveAM)
                    myEarningType(5) = "OT"
                Else
                    If reg - myduration(5) < 0 And DefaultRate = "REG" Then ' if still in regular hours, but about to go over
                        myStartTime(5) = twelveAM
                        myEndTime(5) = DateAdd("n", reg * 60, twelveAM) ' remaining regular hours
                        myEarningType(5) = "REG"
                        myStartTime(6) = myEndTime(5)
                        myEndTime(6) = DateAdd("n", (myduration(5) - reg) * 60, myStartTime(6)) ' hours past regular 40
                        myEarningType(6) = "OT"
                        
                        myduration(6) = (myduration(5) - reg) ' hours past regular 40.  Might be zero.
                        myduration(5) = reg ' Might be zero.
                        
                        DefaultRate = "OT" ' we are now in OT hours
                    Else ' either not in regular hours, or not about to go over
                        myStartTime(5) = twelveAM
                        myEndTime(5) = DateAdd("n", myduration(5) * 60, twelveAM)
                        myEarningType(5) = DefaultRate
                    End If
                    reg = reg - myduration(5) 'remove spent hours from 40 total towards OT count
                End If
            Else
                myduration(5) = 0 ' 0 out so no data sent to DB during INSERT phase.
            End If
            
            
            'final check.  if this is a holiday (which, as of this writing, is only MLK for CAS's),
            ' then REG becomes OT.
            If (myDay = holiday) And EmpType = "CASB" Then
                For j = 0 To 6
                    If myEarningType(j) = "REG" Then
                        myEarningType(j) = "OT"
                    End If
                Next j
            End If
            
            
            ' data is set, time to write to database if applicable.
            For j = 0 To 6
                If myduration(j) > 0 Then ' don't write a record that they didn't work during
                    sqlStmt = " SELECT * FROM HOURLY_DETAIL"
                    Set dsDetail = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
                    dsDetail.AddNew
                    dsDetail.Fields("ROW_NUMBER").Value = row_number
                    dsDetail.Fields("SF_ROW_NUMBER").Value = sf_row_number
                    dsDetail.Fields("HIRE_DATE").Value = Format(DateValue(myStartTime(j)), "mm/dd/yyyy")
                    dsDetail.Fields("EMPLOYEE_ID").Value = empId
                    dsDetail.Fields("START_TIME").Value = Format(myStartTime(j), "mm/dd/yyyy hh:mmAM/PM")
                    dsDetail.Fields("END_TIME").Value = Format(myEndTime(j), "mm/dd/yyyy hh:mmAM/PM")
                    dsDetail.Fields("earning_type_id").Value = myEarningType(j)
                    dsDetail.Fields("DURATION").Value = myduration(j)
                    dsDetail.Fields("SERVICE_CODE").Value = service_code
                    dsDetail.Fields("EQUIPMENT_ID").Value = equipment_id
                    dsDetail.Fields("COMMODITY_CODE").Value = commodity_code
                    dsDetail.Fields("LOCATION_ID").Value = location_id
                    dsDetail.Fields("VESSEL_ID").Value = vessel_id
                    dsDetail.Fields("CUSTOMER_ID").Value = customer_id
                    dsDetail.Fields("USER_ID").Value = supervisor_id
                    dsDetail.Fields("SPECIAL_CODE").Value = special_code
                    dsDetail.Fields("TIME_ENTRY").Value = Now
                    dsDetail.Fields("TIME_UPDATE").Value = Now
                    dsDetail.Fields("REMARK").Value = remark
    
                    If Not IsNull(rs.Fields("EXACT_END").Value) Then
                        dsDetail.Fields("EXACT_END").Value = Format(rs.Fields("EXACT_END").Value, "mm/dd/yyyy hh:mmAM/PM")
                    End If
                    dsDetail.Update
    
                    If OraDatabase.LastServerErr <> 0 Then
                         OraSession.Rollback
                         Exit Sub
                    End If
    
                    row_number = row_number + 1
                    dsDetail.Close
                    Set dsDetail = Nothing
                End If
            Next j
                
                
        
        
        
            rs.MoveNext
        Loop
        rs.Close
        Set rs = Nothing
        myDay = myDay + 1
    Loop
    
    Exit Sub
    
ErrHandler:
    Call TransRollBack(Err.Number, Err.Description)
End Sub

Private Function getHourOnDays(ByVal startDay As Date, ByVal endDay As Date, empId As String)
   
    Dim hrRS As Object
    Dim hour(6) As Single
    Dim i As Integer
    Dim sqlStmt As String
    hour(0) = hour(1) = hour(2) = hour(3) = hour(4) = hour(5) = hour(6) = 0
   
    sqlStmt = " SELECT HIRE_DATE, SUM(DURATION) AS TOTAL" _
            & " FROM SF_HOURLY_DETAIL" _
            & " WHERE EMPLOYEE_ID = '" & empId & "' " _
            & " AND HIRE_DATE >= TO_DATE('" & Format(startDay, "MM/DD/YYYY") & "', 'mm/dd/yyyy')" _
            & " AND HIRE_DATE <= To_DATE('" & Format(endDay, "MM/DD/YYYY") & "', 'mm/dd/yyyy')" _
            & " GROUP BY HIRE_DATE " _
            & " ORDER BY HIRE_DATE "
            
    Set hrRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    hrRS.MoveFirst
    Do While Not hrRS.EOF
        If Not IsNull(hrRS.Fields("TOTAL").Value) And Not IsNull(hrRS.Fields("HIRE_DATE").Value) Then
            hour(CDate(hrRS.Fields("HIRE_DATE").Value) - startDay) = hrRS.Fields("TOTAL").Value
        End If
        hrRS.MoveNext
    Loop

    getHourOnDays = hour
    
End Function
Private Function getRegHourOnDays(ByVal startDay As Date, ByVal endDay As Date, empId As String)
    ' April 2009, Adam Walter.
    'editing this function so that CASB's get ANY hour calculated intot heir total of 40, not just "reg"
   
    Dim hrRS As Object
    Dim hour(6) As Single
    Dim i As Integer
    Dim sqlStmt As String
    Dim rowNum As String
    Dim hire_date As Date
    Dim EmpType As String ' this variable isn't available to this function as-is, so rather than change all calls to here, i just re-get it.
    
    hire_date = CDate(Format(hireDate.Text, "MM/DD/YYYY"))
    
    hour(0) = hour(1) = hour(2) = hour(3) = hour(4) = hour(5) = hour(6) = 0
    
    sqlStmt = "SELECT EMPLOYEE_TYPE_ID FROM EMPLOYEE WHERE EMPLOYEE_ID = '" & empId & "'"
    Set hrRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    EmpType = hrRS.Fields("EMPLOYEE_TYPE_ID").Value
   
    sqlStmt = " SELECT HIRE_DATE, SUM(DURATION) AS TOTAL" _
            & " FROM HOURLY_DETAIL" _
            & " WHERE EMPLOYEE_ID = '" & empId & "' " _
            & " AND HIRE_DATE >= TO_DATE('" & Format(startDay, "MM/DD/YYYY") & "', 'mm/dd/yyyy')" _
            & " AND HIRE_DATE <= To_DATE('" & Format(endDay, "MM/DD/YYYY") & "', 'mm/dd/yyyy')"
    
    If EmpType = "CASB" Then
        sqlStmt = sqlStmt & " AND EARNING_TYPE_ID IN ('REG', 'OT', 'DT') "
    Else
        sqlStmt = sqlStmt & " AND EARNING_TYPE_ID = 'REG' "
    End If
            
    sqlStmt = sqlStmt & " GROUP BY HIRE_DATE " _
            & " ORDER BY HIRE_DATE "
            
    Set hrRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    hrRS.MoveFirst
    Do While Not hrRS.EOF
        If Not IsNull(hrRS.Fields("TOTAL").Value) And Not IsNull(hrRS.Fields("HIRE_DATE").Value) Then
            hour(CDate(hrRS.Fields("HIRE_DATE").Value) - startDay) = hrRS.Fields("TOTAL").Value
        End If
        hrRS.MoveNext
    Loop
    
'    'get REG hours which input befor hire day
'    rowNum = getSFRowNumber(hire_date - 1, empId)
'    sqlStmt = " SELECT SUM(DURATION) AS TOTAL " _
'        & " FROM HOURLY_DETAIL" _
'        & " WHERE EMPLOYEE_ID = '" & empId & "' " _
'        & " AND HIRE_DATE = TO_DATE('" & Format(hire_date, "MM/DD/YYYY") & "', 'mm/dd/yyyy')" _
'        & " AND EARNING_TYPE_ID = 'REG' "
'    Set hrRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
'    hrRS.MoveFirst
'    If Not hrRS.EOF And Not IsNull(hrRS.Fields("TOTAL")) Then
'        hour(hire_date - 1 - startDay) = hour(hire_date - 1 - startDay) + hrRS.Fields("TOTAL").Value
'    End If
        
    
    getRegHourOnDays = hour
    
End Function

Private Sub TransRollBack(num As Long, des As String)
    'If OraDatabase.LastServerErr = 0 Then
    '    OraSession.CommitTrans
    'Else
    OraSession.Rollback
    'End If
    MsgBox Str(num) & ": " & des & ". The Simplified Hourly Detail can't be saved. Please try again later.", , "Error"
End Sub

Private Sub updateSFHDDataFromGrid(hire_date As Date, empId As String)
    On Error GoTo ErrHandler
    
    'Dim iUpdRecCnt As Integer
    'Dim iInsRecCnt As Integer
    Dim dsDetail As Object
    Dim sqlStmt As String
    Dim row_number As Integer
    Dim dStartTime As Date, dEndTime As Date, dExactEndTime As Date, dBreakTime As Date, twelveAM As Date
    Dim sf_row_number, service_code, equipment_id, commodity_code, location_id As String
    Dim vessel_id, special_code, customer_id As String
    Dim payLunch As String, payDinner As String, supervisor_id As String, remark As String
    Dim row_num As String
    Dim rs As Object
    Dim Duration As Single
    Dim i As Integer
    Dim lineRunners As Boolean
    Dim twelvePM As Date, sixPM As Date, onePM As Date, sevenPM As Date
    twelvePM = CDate(CStr(hire_date) & " " & "12:00PM")
    onePM = CDate(CStr(hire_date) & " " & "01:00PM")
    sixPM = CDate(CStr(hire_date) & " " & "06:00PM")
    sevenPM = CDate(CStr(hire_date) & " " & "07:00PM")
   
    sqlStmt = " SELECT * FROM SF_HOURLY_DETAIL"
    Set dsDetail = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
   
    SSDBGrid1.MoveFirst
    row_number = 1
    
    For i = 0 To SSDBGrid1.rows - 1
        dStartTime = Format(hire_date, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(1).Value, "hh:nnAM/PM")
        dEndTime = Format(hire_date, "MM/DD/YYYY") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM")
        If dEndTime < dStartTime Then
            dEndTime = Format(hire_date + 1, "MM/DD/YYYY") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM")
        End If
            
        If Trim(SSDBGrid1.Columns(0).Value) = "" Then
            sqlStmt = "select row_num_seq.nextval row_num from dual"
            Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            SSDBGrid1.Columns(0).Value = rs.Fields("row_num").Value
            rs.Close
            Set rs = Nothing
        End If
            
        sf_row_number = SSDBGrid1.Columns(0).Value
        Duration = SSDBGrid1.Columns(3).Value
        service_code = SSDBGrid1.Columns(6).Value
        equipment_id = SSDBGrid1.Columns(7).Value
        If equipment_id = "" Then
            equipment_id = 0
        End If
        commodity_code = SSDBGrid1.Columns(8).Value
        location_id = SSDBGrid1.Columns(9).Value
        vessel_id = SSDBGrid1.Columns(10).Value
        special_code = SSDBGrid1.Columns(13).Value
        customer_id = SSDBGrid1.Columns(11).Value
        If Trim(SSDBGrid1.Columns(12).Value) = "" Then
            supervisor_id = UserID
        Else
            supervisor_id = SSDBGrid1.Columns(12).Value
        End If
            
        If SSDBGrid1.Columns(18).Value = True And (dStartTime < onePM And dEndTime > twelvePM) Then
            payLunch = "Y"
        Else
            payLunch = "N"
        End If
        
        If SSDBGrid1.Columns(19).Value = True And (dStartTime < sevenPM And dEndTime > sixPM) Then
            payDinner = "Y"
        Else
            payDinner = "N"
        End If
        
        'remark = SSDBGrid1.Columns(14).Value
        remark = Replace(SSDBGrid1.Columns(14).Value, "'", "`")     '5/2/2007 HD2560 Rudy: on Hold per Jon Jaffe 5/2/2007
                
        dsDetail.AddNew
        dsDetail.Fields("ROW_NUMBER").Value = sf_row_number
        dsDetail.Fields("HIRE_DATE").Value = Format(hire_date, "MM/DD/YYYY")
        dsDetail.Fields("EMPLOYEE_ID").Value = empId
        dsDetail.Fields("START_TIME").Value = Format(dStartTime, "mm/dd/yyyy hh:mmAM/PM")
        dsDetail.Fields("END_TIME").Value = Format(dEndTime, "mm/dd/yyyy hh:mmAM/PM")
        dsDetail.Fields("DURATION").Value = Duration
        dsDetail.Fields("SERVICE_CODE").Value = service_code
        dsDetail.Fields("EQUIPMENT_ID").Value = equipment_id
        dsDetail.Fields("COMMODITY_CODE").Value = commodity_code
        dsDetail.Fields("LOCATION_ID").Value = location_id
        dsDetail.Fields("VESSEL_ID").Value = vessel_id
        dsDetail.Fields("CUSTOMER_ID").Value = customer_id
        dsDetail.Fields("USER_ID").Value = supervisor_id
        dsDetail.Fields("SPECIAL_CODE").Value = special_code
        dsDetail.Fields("PAY_LUNCH").Value = payLunch
        dsDetail.Fields("PAY_DINNER").Value = payDinner
        dsDetail.Fields("TIME_ENTRY").Value = Now
        dsDetail.Fields("TIME_UPDATE").Value = Now
        dsDetail.Fields("REMARK").Value = remark
        
        If (Trim(SSDBGrid1.Columns(15).Value) <> "") Then
            dExactEndTime = Format(hire_date, "MM/DD/YYYY") & " " & Format(SSDBGrid1.Columns(15).Value, "hh:nnAM/PM")
            If dExactEndTime < dStartTime Then
                dExactEndTime = Format(hire_date + 1, "MM/DD/YYYY") & " " & Format(SSDBGrid1.Columns(15).Value, "hh:nnAM/PM")
            End If
            dsDetail.Fields("EXACT_END").Value = Format(dExactEndTime, "mm/dd/yyyy hh:mmAM/PM")
        End If
        dsDetail.Update
        
        If OraDatabase.LastServerErr <> 0 Then
             OraSession.Rollback
             Exit Sub
        End If
        
        SSDBGrid1.MoveNext
    Next
    
    dsDetail.Close
    Set dsDetail = Nothing
    
    Exit Sub
ErrHandler:
    Call TransRollBack(Err.Number, Err.Description)
End Sub

Private Sub DeleteHDData(startDate As Date, endDate As Date, empId As String)
    On Error GoTo ErrHandler
    Dim sqlStmt As String
    Dim iDelRecCnt As Integer
    Dim myDay As Date
    Dim sfRowNum As String
    
    myDay = startDate
    Do While myDay <= endDate
        sfRowNum = getSFRowNumber(myDay, empId)
        If sfRowNum <> "" Then
            sqlStmt = " Delete from hourly_detail " _
                & " WHERE ( hire_date = to_date('" & Format(myDay, "MM/DD/YYYY") & "','mm/dd/yyyy')" _
                & " OR hire_date = to_date('" & Format(myDay + 1, "MM/DD/YYYY") & "', 'mm/dd/yyyy')) " _
                & " AND EMPLOYEE_ID = '" & empId + "'" _
                & " AND SF_ROW_NUMBER In (" & sfRowNum & ")"
            iDelRecCnt = OraDatabase.ExecuteSQL(sqlStmt)
        End If
        myDay = myDay + 1
    Loop
    
    Exit Sub
    
ErrHandler:
    Call TransRollBack(Err.Number, Err.Description)
End Sub
Private Sub DeleteSFHDData(hire_date As Date, empId As String)
    On Error GoTo ErrHandler
    Dim sqlStmt As String
    Dim iDelRecCnt As Integer
   
    sqlStmt = " Delete from sf_hourly_detail " _
            & " where Hire_date = to_date('" + Format(hire_date, "MM/DD/YYYY") + "','mm/dd/yyyy')" _
            & " AND EMPLOYEE_ID = '" + empId + "'"
    iDelRecCnt = OraDatabase.ExecuteSQL(sqlStmt)
    
    Exit Sub
    
ErrHandler:
    'OraSession.Rollback
    'MsgBox Str(Err.Number) & ": " & Err.Description, , "Error"
    Call TransRollBack(Err.Number, Err.Description)
End Sub

Private Function timeIn40Hour(myStart As Date, myEnd As Date, Duration As Single, payLunch As String, payDinner As String, isHoliday As Boolean) As Single
    Dim sixPM As Date, sevenPM As Date, twelvePM As Date, onePM As Date
    Dim twelveAM As Date, oneAM As Date, sixAM As Date, sevenAM As Date
    Dim hire_date As String
    Dim myduration As Single
    hire_date = CStr(DateValue(myStart)) + " "
    twelveAM = hire_date + "12:00:00 AM"
    oneAM = hire_date + "1:00:00 AM"
    sixAM = hire_date + "6:00:00 AM"
    sevenAM = hire_date + "7:00:00 AM"
    twelvePM = hire_date + "12:00:00 PM"
    onePM = hire_date + "1:00:00 PM"
    sixPM = hire_date + "6:00:00 PM"
    sevenPM = hire_date + "7:00:00 PM"
    
    'All hours worked after 6 PM are paid at 1 1/2 times hourly rate.
    'overtime hours worked and paid at 1 1/2 times are not included in 40 hours
    If (myStart >= sixPM) And isHoliday = False Then
        Duration = 0
        Exit Function
    End If
    
    If (myEnd <= sixAM) And isHoliday = False Then
        Duration = 0
        Exit Function
    End If
    
    'Take out hours before six AM
     If (myEnd > sixAM) And (myStart < sixAM) Then
        If (isHoliday = False) Then
            Duration = Duration - (DateDiff("n", myStart, sixAM) / 60)
        End If
    End If
    
    'Take out hours after six PM
    If (myEnd > sixPM) And (myStart <= sixPM) Then
        If (isHoliday = False) Then
            If (payDinner = "Y") Then
                Duration = Duration - (DateDiff("n", sixPM, myEnd) / 60)
            Else
                Duration = Duration - (DateDiff("n", sevenPM, myEnd) / 60)
            End If
        Else
            If (payDinner = "Y") Then
                Duration = Duration - 1
            End If
        End If
    End If
    
'    If myEnd >= oneAM Then
'        'If myStart <= twelveAM Then
'        If myStart = twelveAM Then
'            Duration = Duration - 1
'        ElseIf twelveAM < myStart And myStart < oneAM Then
'            Duration = Duration - (DateDiff("n", myStart, oneAM) / 60)
'        End If
'    End If
    
    If myEnd >= sevenAM Then
        If myStart <= sixAM Then
            Duration = Duration - 1
        ElseIf sixAM < myStart And myStart < sevenAM Then
            Duration = Duration - (DateDiff("n", myStart, sevenAM) / 60)
        End If
    End If
    
    If myEnd >= onePM Then
        If payLunch = "Y" Then
            If myStart <= twelvePM Then
                Duration = Duration - 1
            ElseIf twelvePM < myStart And myStart < onePM Then
                Duration = Duration - (DateDiff("n", myStart, onePM) / 60)
            End If
        End If
    End If
    
    timeIn40Hour = Duration
End Function


'*****************************************************
'To calculate the duration between Start and End Time
'Return: number of hours in String (Ex. 2.5)
'*****************************************************
Public Function CalculateDuration(hire_date As String, myStart As String, myEnd As String, payLunch As Boolean, payDinner As Boolean) As String
    Dim sTime As Date
    Dim eTime As Date
    Dim mins As Integer
    Dim lunchStart, lunchEnd, dinnerStart, dinnerEnd As Date
   
    lunchStart = hire_date & " " & "12:00:00 PM"
    lunchEnd = hire_date & " " & "01:00:00 PM"
    dinnerStart = hire_date & " " & "6:00:00 PM"
    dinnerEnd = hire_date & " " & "7:00:00 PM"
    
    sTime = hire_date & " " & myStart
    eTime = hire_date & " " & myEnd
    
    If eTime < sTime Then
        eTime = DateAdd("d", 1, eTime)
    End If
    
    mins = DateDiff("n", sTime, eTime)
    
    If mins < 0 Then  'overnight
        mins = 24 * 60 + mins
    End If

    If sTime <= lunchStart And eTime >= lunchStart And eTime <= lunchEnd And payLunch = False Then
        mins = mins - DateDiff("n", lunchStart, eTime)
    ElseIf eTime >= lunchEnd And sTime >= lunchStart And sTime < lunchEnd And payLunch = False Then
        mins = mins - DateDiff("n", sTime, lunchEnd)
    ElseIf sTime <= lunchStart And eTime >= lunchEnd And payLunch = False Then
        mins = mins - 60    'take off 60 min lunch hour
    End If
    
    If sTime <= dinnerStart And eTime >= dinnerStart And eTime <= dinnerEnd And payDinner = False Then
        mins = mins - DateDiff("n", dinnerStart, eTime)
    ElseIf eTime >= dinnerEnd And sTime >= dinnerStart And sTime < dinnerEnd And payDinner = False Then
        mins = mins - DateDiff("n", sTime, dinnerEnd)
    ElseIf sTime <= dinnerStart And eTime >= dinnerEnd And payDinner = False Then
        mins = mins - 60
    End If
    
    If (mins = 0) Then
        CalculateDuration = "0"
    ElseIf (mins = 30) Then
        CalculateDuration = "0.5"
    Else
        CalculateDuration = Format(Trim(Str(CSng(mins / 60))), "#.0")
    End If
End Function

Private Function findMonday(day As Date) As Date
    Dim weekofday As Integer
    weekofday = Weekday(day)
    
    If weekofday = 1 Then
        findMonday = day - 6
    Else
        findMonday = day - (weekofday - 2)
    End If
End Function

Private Function findFriday(day As Date) As Date
    Dim weekofday As Integer
    weekofday = Weekday(day)
    
    If weekofday = 1 Then
        findFriday = day - 2
    Else
        findFriday = day - (weekofday - 6)
    End If
End Function

Private Function findSaturday(day As Date) As Date
    Dim weekofday As Integer
    weekofday = Weekday(day)
    
    If weekofday = 1 Then
        findSaturday = day - 1
    Else
        findSaturday = day - (weekofday - 7)
    End If
End Function
Private Function findSunday(day As Date) As Date
    Dim weekofday As Integer
    weekofday = Weekday(day)
    
    If weekofday = 1 Then
        findSunday = day
    Else
        findSunday = day - (weekofday - 8)
    End If
End Function

Private Sub refreshTotalHrs()
    Label4.Caption = "Total Hours : " + Trim(Str(FindTotalHrs()))
End Sub

Private Function holidayInPeriod(employeeType As String, monday As Date, sunday As Date) As Date
    'called from 'saveDataREGR, saveDataCAS, updateHDDataCAS
    Dim sqlStmt As String
    Dim hlRS As Object
    'Dim size As Integer
    'Dim holiday() As String
    Dim holiday As String
    Dim day As Date
    Dim i As Integer
    Dim diff As Integer
    
    If employeeType = "CASC" Then
        holidayInPeriod = sunday + 1
        Exit Function
    ElseIf employeeType = "CASB" Then
        'only Martin Luther King's Birthday
        'size = 1
        'ReDim holiday(size)
        'holiday(0) = "MLK"
        holiday = "'MLK'"
    ElseIf employeeType = "REGR" Then
        'New Year's Day, Martin Luther King's Birthday, Memorial Day
        'Independence Day, Labor Day, Thanksgiving Day, Christmas Day
        'size = 7
        'ReDim holiday(size)
        'holiday(0) = "NYD"
        'holiday(1) = "MLK"
        'holiday(2) = "MD"
        'holiday(3) = "IND"
        'holiday(4) = "LD"
        'holiday(5) = "TD"
        'holiday(6) = "CD"
        holiday = "'NYD', 'MLK', 'MD', 'IND', 'LD', 'TD', 'CD'"
    
    ElseIf employeeType = "TEST" Then   'HD2695 Rudy 6/29/2007: new else if for 2695
        day = sunday + 1    'init
        'ALL Holidays
        holiday = "'NYD', 'MLK', 'MD', 'IND', 'LD', 'TD', 'CD', 'MLUTHER'"
    End If
    
    'For i = 0 To size
    sqlStmt = "SELECT * FROM Holiday_List WHERE HOLIDAY_ID IN (" & holiday & ")"
    Set hlRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)

    If hlRS.EOF And hlRS.BOF Then
        day = sunday + 1
    Else
        hlRS.MoveFirst
        Do While Not hlRS.EOF
            day = hlRS.Fields("HOLIDAY_DATE").Value
            diff = DateDiff("d", monday, day)
            'HD2695 Rudy 6/29/2007: Big assumption, that no holiday falls within 6 days of one another!
            If diff >= 0 And diff <= 6 Then
                holidayInPeriod = day
                hlRS.Close
                Exit Function
            Else
                day = sunday + 1
            End If
            hlRS.MoveNext
        Loop
    End If
    'Next

    hlRS.Close
    holidayInPeriod = day
End Function

Private Function isWeekendDay(day As Date) As Boolean
    Dim Index As Integer
    Index = Weekday(day)
    If Index = 1 Or Index = 7 Then
        isWeekendDay = True
    Else
        isWeekendDay = False
    End If
End Function

Private Function findTimeBuckets(sTime As Date, eTime As Date, payLunch As String, payDinner As String, weekendFlag As Boolean, holidayFlag As Boolean)
    Dim startTime(3) As Date, endTime(3) As Date, myStart As Date, myEnd As Date
    Dim results() As String
    Dim start1() As String, end1() As String, Duration() As String, earning() As String
    Dim latestTime As Date, time1 As Date, time2 As Date
    Dim sixPM As Date, sevenPM As Date, twelvePM As Date, onePM As Date
    Dim twelveAM As Date, oneAM As Date, sixAM As Date, sevenAM As Date
    Dim hire_date As String
    Dim overnightFlag As Boolean, NotFinish As Boolean, add As Boolean
    Dim dur As Single
    
    myStart = sTime
    myEnd = eTime
    If (DateDiff("d", DateValue(myStart), DateValue(myEnd)) = 1) Then
        overnightFlag = True
    Else
        overnightFlag = False
    End If
    
    hire_date = CStr(DateValue(myStart)) + " "
    twelveAM = hire_date + "12:00:00 AM"
    oneAM = hire_date + "1:00:00 AM"
    sixAM = hire_date + "6:00:00 AM"
    sevenAM = hire_date + "7:00:00 AM"
    twelvePM = hire_date + "12:00:00 PM"
    onePM = hire_date + "1:00:00 PM"
    sixPM = hire_date + "6:00:00 PM"
    sevenPM = hire_date + "7:00:00 PM"
    
    startTime(0) = twelveAM
    endTime(0) = oneAM
    startTime(1) = sixAM
    endTime(1) = sevenAM
    startTime(2) = twelvePM
    endTime(2) = onePM
    startTime(3) = sixPM
    endTime(3) = sevenPM
    
    Dim i As Integer, Index As Integer
    Index = -1
    NotFinish = True
    
    While NotFinish
        For i = 0 To 3
            If (startTime(i) = twelvePM And payLunch = "N") Then
                'do nothing
            ElseIf (startTime(i) = sixPM And payDinner = "N") Then
                If weekendFlag = True Or holidayFlag = True Then
                    'Do Nothing
                ElseIf myEnd <= sixPM Or myStart >= sixPM Then
                    'Do Nothing
                ElseIf myEnd = sevenPM Then
                    'Do nothing
                Else
                    Index = Index + 1
                    ReDim Preserve start1(Index)
                    ReDim Preserve end1(Index)
                    ReDim Preserve earning(Index)
                    ReDim Preserve Duration(Index)
                    
                    start1(Index) = CStr(myStart)
                    end1(Index) = CStr(startTime(i))
                    'dur = FindDuration(myStart, startTime(i), "N", "N")
                    dur = FindDuration(myStart, startTime(i), payLunch, payDinner)
                    Duration(Index) = CStr(dur)
                    earning(Index) = ""
                    myStart = sixPM
                    Index = UBound(start1)
                End If
            Else
                If myStart <= startTime(i) And myEnd >= endTime(i) Then
                    time1 = startTime(i)
                    time2 = endTime(i)
                    add = True
                ElseIf startTime(i) < myStart And myStart < endTime(i) And myEnd >= endTime(i) Then
                    time1 = myStart
                    'time2 = DateAdd("n", 60, myStart)
                    time2 = endTime(i)
                    add = True
                ElseIf myStart < startTime(i) And startTime(i) < myEnd And myEnd <= endTime(i) Then
                    'time1 = DateAdd("n", -60, myEnd)
                    time1 = startTime(i)
                    time2 = myEnd
                    add = True
                Else
                    add = False
                End If
                
                If add = True Then
                    If DateDiff("n", myStart, startTime(i)) <= 0 Then
                        Index = Index + 1
                    Else
                        Index = Index + 2
                    End If
                    ReDim Preserve start1(Index)
                    ReDim Preserve end1(Index)
                    ReDim Preserve earning(Index)
                    ReDim Preserve Duration(Index)
                    
                    If DateDiff("n", myStart, time1) > 0 Then
                        start1(Index - 1) = CStr(myStart)
                        end1(Index - 1) = CStr(time1)
                        'dur = FindDuration(myStart, time1, "N", "N")
                        dur = FindDuration(myStart, time1, payLunch, payDinner)
                        Duration(Index - 1) = CStr(dur)
                        earning(Index - 1) = ""
                    End If
                    
                    start1(Index) = CStr(time1)
                    end1(Index) = CStr(time2)
                    'Duration(Index) = CStr(1)
                    dur = FindDuration(time1, time2, payLunch, payDinner)
                    Duration(Index) = CStr(dur)
                    
                    earning(Index) = "DT"
                    myStart = time2
                    Index = UBound(start1)
                End If
            End If
        Next
        
        If overnightFlag Then
            For i = 0 To 3
                startTime(i) = DateAdd("d", 1, startTime(i))
                endTime(i) = DateAdd("d", 1, endTime(i))
            Next
            overnightFlag = False
        Else
            NotFinish = False
        End If
    Wend
    
    If DateDiff("n", myStart, myEnd) <> 0 Then
        Index = Index + 1
        ReDim Preserve start1(Index)
        ReDim Preserve end1(Index)
        ReDim Preserve earning(Index)
        ReDim Preserve Duration(Index)
        
        start1(Index) = CStr(myStart)
        end1(Index) = CStr(myEnd)
        'dur = FindDuration(myStart, myEnd, "N", "N")
        dur = FindDuration(myStart, myEnd, payLunch, payDinner)
        Duration(Index) = CStr(dur)
        earning(Index) = ""
    End If
    
    ReDim results(UBound(start1), 3)
    For i = 0 To UBound(start1)
        results(i, 0) = start1(i)
        results(i, 1) = end1(i)
        results(i, 2) = Duration(i)
        results(i, 3) = earning(i)
    Next
    
    findTimeBuckets = results()
End Function

Private Function findCASTimeBuckets(sTime As Date, eTime As Date, payLunch As String, payDinner As String)
    Dim myStart As Date, myEnd As Date
    Dim results() As String
    Dim twelveAM As Date
    Dim hire_date As String
    Dim overnightFlag As Boolean
    Dim dur As Single
    
    myStart = sTime
    myEnd = eTime
    If (DateDiff("d", DateValue(myStart), DateValue(myEnd)) = 1) Then
        overnightFlag = True
    Else
        overnightFlag = False
    End If
    
    hire_date = CStr(DateValue(myEnd)) + " "
    twelveAM = hire_date + "12:00:00 AM"
    
    If overnightFlag Then
        ReDim results(1, 2)
        results(0, 0) = myStart
        results(0, 1) = twelveAM
        dur = FindDuration(myStart, twelveAM, payLunch, payDinner)
        results(0, 2) = CStr(dur)
        results(1, 0) = twelveAM
        results(1, 1) = myEnd
        results(1, 2) = CStr(DateDiff("n", results(1, 0), results(1, 1)) / 60)
    Else
        ReDim results(0, 2)
        results(0, 0) = myStart
        results(0, 1) = myEnd
        dur = FindDuration(myStart, myEnd, payLunch, payDinner)
        results(0, 2) = CStr(dur)
    End If
   
    findCASTimeBuckets = results()
End Function


Private Function findDuganHours(start1 As Date, end1 As Date, payLunch As String, payDinner As String)
    Dim startTime(3) As Date, endTime(3) As Date, myStart As Date, myEnd As Date
    'Dim results(0, 1) As Date
    Dim results() As Date
    ReDim results(0, 0)
    Dim sixPM As Date, sevenPM As Date, twelvePM As Date, onePM As Date
    Dim twelveAM As Date, oneAM As Date, sixAM As Date, sevenAM As Date
    Dim hire_date As String
    Dim overnightFlag As Boolean, NotFinish As Boolean
    
    myStart = start1
    myEnd = end1
    If (DateDiff("d", DateValue(myStart), DateValue(myEnd)) = 1) Then
        overnightFlag = True
    Else
        overnightFlag = False
    End If
    
    hire_date = CStr(DateValue(myStart)) + " "
    twelveAM = hire_date + "12:00:00 AM"
    oneAM = hire_date + "1:00:00 AM"
    sixAM = hire_date + "6:00:00 AM"
    sevenAM = hire_date + "7:00:00 AM"
    twelvePM = hire_date + "12:00:00 PM"
    onePM = hire_date + "1:00:00 PM"
    sixPM = hire_date + "6:00:00 PM"
    sevenPM = hire_date + "7:00:00 PM"
    
    startTime(0) = twelveAM
    endTime(0) = oneAM
    startTime(1) = sixAM
    endTime(1) = sevenAM
    startTime(2) = twelvePM
    endTime(2) = onePM
    startTime(3) = sixPM
    endTime(3) = sevenPM
    
    Dim i As Integer, j As Integer
    j = 0
    'results(0, 1) = myStart
    NotFinish = False
    While NotFinish
        For i = 0 To 3
            If (startTime(i) = twelvePM And payLunch = "N") Or (startTime(i) = sixPM And payDinner = "N") Then
                'do nothing
            Else
                If myStart <= startTime(i) And myEnd >= endTime(i) Then
                    ReDim Preserve results(UBound(results, 0) + 1, 1)
                    results(UBound(results, 0), 0) = startTime(i)
                    results(UBound(results, 1), 1) = endTime(i)
                    myStart = endTime(i)
                    'j = j + 1
                ElseIf startTime(i) < myStart And myStart < endTime(i) And myEnd >= endTime(i) Then
                    ReDim Preserve results(UBound(results, 0) + 1, 1)
                    results(UBound(results, 0), 0) = myStart
                    'results(j, 1) = EndTime(i)
                    results(UBound(results, 1), 1) = DateAdd("n", 60, myStart)
                    myStart = endTime(i)
                    'j = j + 1
                ElseIf myStart < startTime(i) And startTime(i) < myEnd And myEnd <= endTime(i) Then
                    'results(j, 0) = StartTime(i)
                    ReDim Preserve results(UBound(results, 0) + 1, 1)
                    results(UBound(results, 0), 0) = DateAdd("n", -60, myEnd)
                    results(UBound(results, 1), 1) = myEnd
                    myStart = myEnd
                    'j = j + 1
                End If
            End If
        Next
        
        If overnightFlag Then
            For i = 0 To 3
                startTime(i) = DateAdd("d", 1, startTime(i))
                endTime(i) = DateAdd("d", 1, endTime(i))
            Next
            overnightFlag = False
        Else
            NotFinish = False
        End If
    Wend
    
    findDuganHours = results()
End Function


Public Function FindDuration(sTime As Date, eTime As Date, payLunch As String, payDinner As String) As Single
    Dim hire_date As String
    Dim mins As Integer
    Dim lunchStart, lunchEnd, dinnerStart, dinnerEnd As Date
   
    hire_date = CStr(DateValue(sTime)) + " "
    lunchStart = hire_date & "12:00:00 PM"
    lunchEnd = hire_date & "01:00:00 PM"
    dinnerStart = hire_date & "6:00:00 PM"
    dinnerEnd = hire_date & "7:00:00 PM"
   
    mins = DateDiff("n", sTime, eTime)
    
    If sTime <= lunchStart And eTime >= lunchStart And eTime <= lunchEnd And payLunch = "N" Then
        mins = mins - DateDiff("n", lunchStart, eTime)
    ElseIf eTime >= lunchEnd And sTime >= lunchStart And sTime < lunchEnd And payLunch = "N" Then
        mins = mins - DateDiff("n", sTime, lunchEnd)
    ElseIf sTime <= lunchStart And eTime >= lunchEnd And payLunch = "N" Then
        mins = mins - 60    'take off 60 min lunch hour
    End If
    
    If sTime <= dinnerStart And eTime >= dinnerStart And eTime <= dinnerEnd And payDinner = "N" Then
        mins = mins - DateDiff("n", dinnerStart, eTime)
    ElseIf eTime >= dinnerEnd And sTime >= dinnerStart And sTime < dinnerEnd And payDinner = "N" Then
        mins = mins - DateDiff("n", sTime, dinnerEnd)
    ElseIf sTime <= dinnerStart And eTime >= dinnerEnd And payDinner = "N" Then
        mins = mins - 60
    End If
    
    FindDuration = mins / 60
End Function

Private Function isDayHoliday(day As Date, holiday As Date) As Boolean
    If day = holiday Then
        isDayHoliday = True
    Else
        isDayHoliday = False
    End If
End Function

Private Sub SSDBGrid1_KeyDown(KeyCode As Integer, Shift As Integer)
    If KeyCode = vbKeyDown Then
        SSDBGrid1.DroppedDown = True
    End If
End Sub

