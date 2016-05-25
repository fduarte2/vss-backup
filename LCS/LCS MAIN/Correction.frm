VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Begin VB.Form frmCorrection 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Correction Screen"
   ClientHeight    =   10995
   ClientLeft      =   45
   ClientTop       =   120
   ClientWidth     =   14775
   Icon            =   "Correction.frx":0000
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   10995
   ScaleWidth      =   14775
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
      Left            =   6600
      TabIndex        =   27
      Top             =   1680
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
         TabIndex        =   29
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
         TabIndex        =   28
         Top             =   360
         Width           =   855
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboEmp 
         Height          =   375
         Left            =   1200
         TabIndex        =   30
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
         TabIndex        =   31
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
      Height          =   1860
      Left            =   0
      TabIndex        =   41
      ToolTipText     =   "Select Employee to enter Hourly Detail"
      Top             =   1680
      Width           =   4815
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnHrs 
      Height          =   255
      Left            =   6960
      TabIndex        =   26
      Top             =   11160
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
      Left            =   5640
      Style           =   1  'Graphical
      TabIndex        =   25
      ToolTipText     =   "Clear the Current Record from Grid. No Changes in Database"
      Top             =   10560
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
      Left            =   2880
      Style           =   1  'Graphical
      TabIndex        =   24
      ToolTipText     =   "Clear All Records from Grid. No Change in Database"
      Top             =   10560
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
      Height          =   1335
      Left            =   9840
      TabIndex        =   20
      Top             =   1440
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
         TabIndex        =   21
         ToolTipText     =   "View All Employees "
         Top             =   360
         Width           =   4635
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboSup 
         Height          =   375
         Left            =   1680
         TabIndex        =   22
         ToolTipText     =   "Select Supervisor to view his Employees"
         Top             =   840
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
         Left            =   135
         TabIndex        =   23
         Top             =   900
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
      Left            =   9840
      TabIndex        =   19
      ToolTipText     =   "View TimeSheet Report"
      Top             =   3000
      Width           =   2055
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
      Top             =   3000
      Width           =   1275
   End
   Begin SSCalendarWidgets_A.SSDateCombo SSDateCombo1 
      Height          =   375
      Left            =   1560
      TabIndex        =   17
      Top             =   960
      Width           =   2175
      _Version        =   65543
      _ExtentX        =   3836
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
      Left            =   0
      Style           =   1  'Graphical
      TabIndex        =   16
      ToolTipText     =   "Delete Record from Database"
      Top             =   10560
      Width           =   2000
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnTime 
      Height          =   195
      Left            =   5280
      TabIndex        =   15
      Top             =   11160
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
      Left            =   12360
      TabIndex        =   11
      ToolTipText     =   "Login Supervisor ID"
      Top             =   960
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
      Left            =   13440
      Style           =   1  'Graphical
      TabIndex        =   1
      ToolTipText     =   "Return Back"
      Top             =   3000
      Width           =   1275
   End
   Begin SSDataWidgets_B.SSDBDropDown dwnService 
      Height          =   255
      Left            =   1560
      TabIndex        =   4
      Top             =   11160
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
      Top             =   11160
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
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid2 
      Height          =   1695
      Left            =   0
      TabIndex        =   0
      ToolTipText     =   "Enter the Hourly Details for the Employee"
      Top             =   3600
      Width           =   14700
      _Version        =   196616
      DataMode        =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      Col.Count       =   0
      AllowAddNew     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowColumnMoving=   0
      AllowColumnSwapping=   0
      SelectTypeRow   =   0
      ForeColorEven   =   255
      ForeColorOdd    =   255
      BackColorOdd    =   12648447
      RowHeight       =   503
      ExtraHeight     =   185
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   25929
      _ExtentY        =   2990
      _StockProps     =   79
      Caption         =   "Existing Data"
      ForeColor       =   4210752
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   9.75
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
   Begin SSDataWidgets_B.SSDBDropDown dwnVessel 
      Height          =   255
      Left            =   3000
      TabIndex        =   5
      Top             =   11160
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
      Top             =   11280
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
      Top             =   11280
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
      Top             =   11280
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
      Top             =   11280
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
      TabIndex        =   32
      Top             =   11160
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
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
      Height          =   5055
      Left            =   -600
      TabIndex        =   33
      ToolTipText     =   "Enter the Hourly Details for the Employee"
      Top             =   5400
      Width           =   15300
      _Version        =   196616
      DataMode        =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      Col.Count       =   0
      AllowAddNew     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowColumnMoving=   0
      AllowColumnSwapping=   0
      SelectTypeRow   =   0
      ForeColorEven   =   4210752
      ForeColorOdd    =   8421504
      BackColorOdd    =   12648447
      RowHeight       =   503
      ExtraHeight     =   185
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   26987
      _ExtentY        =   8916
      _StockProps     =   79
      Caption         =   "Modifications"
      ForeColor       =   4210752
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   9.75
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
      Left            =   5040
      TabIndex        =   34
      Top             =   960
      Width           =   4455
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
         TabIndex        =   37
         Top             =   240
         Width           =   1215
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
         TabIndex        =   35
         ToolTipText     =   "Filter Employees based on Selected Criteria"
         Top             =   2040
         Width           =   4215
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboLoc 
         Height          =   375
         Left            =   1755
         TabIndex        =   38
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
            Size            =   8.25
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
         Caption         =   "E&mployee"
         Enabled         =   0   'False
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
         TabIndex        =   36
         Top             =   720
         Width           =   1455
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
         TabIndex        =   40
         Top             =   720
         Visible         =   0   'False
         Width           =   1455
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboComm 
         Height          =   375
         Left            =   1750
         TabIndex        =   39
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
      Left            =   9960
      TabIndex        =   14
      Top             =   10680
      Width           =   4455
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
      Picture         =   "Correction.frx":0442
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
      Left            =   0
      TabIndex        =   13
      Top             =   960
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
      Left            =   10320
      TabIndex        =   12
      Top             =   960
      Width           =   1890
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   14640
      Y1              =   840
      Y2              =   840
   End
   Begin VB.Label Label6 
      Alignment       =   2  'Center
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   735
      Left            =   840
      TabIndex        =   10
      Top             =   0
      Width           =   13785
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
      Left            =   0
      TabIndex        =   2
      Top             =   1410
      Width           =   2535
   End
End
Attribute VB_Name = "frmCorrection"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim LRS As Object, EmpRS As Object, FirstOpen As Boolean
Dim ct As Integer, LastRow As Integer, RowIndex As Integer, FirstClick As Boolean, SavePrev As Boolean
Dim arrEmpID() As String, NoRec As Boolean, DiffSupervisor() As Boolean
Dim PrevValue As String, CurrValue As String, ErrFlag As Boolean, PrevIndex As Integer
Dim PrvEnd As String, PrvReg As String, PrvEquip As String, PrvSvc As String
Dim PrvComm As String, PrvLoc As String, PrvShip As String, PrvCust As String, PrvSpec As String

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
  SetColWidth     'To Set Grid Column Width
  SetDefault      'To Set Blank Values to Prv Columns
End Sub

'****************************************
'To Delete a record from the Grid. NO Updatation in the Database
'****************************************
Private Sub cmdClearLine_Click()
  Dim nTotalSelRows As Integer, bkmrk As Variant, i As Integer, myDelRecCnt As Integer
  Dim myRowNo As Integer, myDelSQL As String
  SSDBGrid1.AllowAddNew = True
  
  If SSDBGrid1.rows > SSDBGrid1.row Then
    If SSDBGrid1.row = 0 Then
      Dim myStartTime As String, myLoc As String, myComm As String
      myStartTime = SSDBGrid1.Columns(1).Value
      myLoc = SSDBGrid1.Columns(9).Value
      myComm = SSDBGrid1.Columns(8).Value
      If UBound(DiffSupervisor) >= SSDBGrid1.row Then
        If DiffSupervisor(SSDBGrid1.row) = True Then
          MsgBox "Can't Delete Employee Data that belongs to Supervisor " + Trim(SSDBGrid1.Columns(12).Value), vbInformation, "Authorization Required"
        Else
          SSDBGrid1.RemoveItem (SSDBGrid1.row)
             'If SSDBGrid1.Rows < 4 Then 'Limit to 10 Rows
          If SSDBGrid1.rows < 9 Then
            'Add One More Blank Row with Defaults
            SSDBGrid1.AddItem Trim(Str(SSDBGrid1.row)) + "!" + ""
            SSDBGrid1.row = SSDBGrid1.rows - 1
            'SSDBGrid1.Columns(5).Value = True           'Default - Billing Flag to be TRUE
            SSDBGrid1.Columns(5).Value = False           'Default - Billing Flag to be FALSE
            SSDBGrid1.Columns(12).Value = UserID        'Default - UserID is the logon Person
          End If
       
          SSDBGrid1.row = 0
          SSDBGrid1.Columns(0).Value = 1
          SSDBGrid1.Columns(1).Value = myStartTime
          SSDBGrid1.Columns(9).Value = myLoc
          SSDBGrid1.Columns(8).Value = myComm
        End If
      Else
        SSDBGrid1.RemoveItem (SSDBGrid1.row)

        'If SSDBGrid1.Rows < 4 Then 'Limit to 10 Rows
        If SSDBGrid1.rows < 9 Then
        'Add One More Blank Row with Defaults
          SSDBGrid1.AddItem Trim(Str(SSDBGrid1.row)) + "!" + ""
          SSDBGrid1.row = SSDBGrid1.rows - 1
          'SSDBGrid1.Columns(5).Value = True           'Default - Billing Flag to be TRUE
          SSDBGrid1.Columns(5).Value = False           'Default - Billing Flag to be false
          SSDBGrid1.Columns(12).Value = UserID        'Default - UserID is the logon Person
        End If
        SSDBGrid1.row = 0
        SSDBGrid1.Columns(0).Value = 1
        SSDBGrid1.Columns(1).Value = myStartTime
        SSDBGrid1.Columns(9).Value = myLoc
        SSDBGrid1.Columns(8).Value = myComm
      End If
    Else
      If UBound(DiffSupervisor) >= SSDBGrid1.row Then
        If DiffSupervisor(SSDBGrid1.row) = True Then
          MsgBox "Can't Delete Employee Data that belongs to Supervisor " + Trim(SSDBGrid1.Columns(12).Value), vbInformation, "Authorization Required"
        Else
          SSDBGrid1.RemoveItem (SSDBGrid1.row)
        End If
      Else
        SSDBGrid1.RemoveItem (SSDBGrid1.row)
      End If
    End If
    'If SSDBGrid1.Rows < 4 Then   'Limit to 10 Rows
    If SSDBGrid1.rows < 9 Then
    'Add One More Blank Row with Defaults
      SSDBGrid1.AddItem Trim(Str(SSDBGrid1.row)) + "!" + ""
      SSDBGrid1.row = SSDBGrid1.rows - 1
      'SSDBGrid1.Columns(5).Value = True           'Default - Billing Flag to be TRUE
      SSDBGrid1.Columns(5).Value = False           'Default - Billing Flag to be FALSE
      SSDBGrid1.Columns(12).Value = UserID        'Default - UserID is the logon Person
    End If
    
    'Set Previous Values
    PrvEnd = SSDBGrid1.Columns(2).Value
    PrvReg = SSDBGrid1.Columns(4).Value
    PrvSvc = SSDBGrid1.Columns(6).Value
    PrvEquip = SSDBGrid1.Columns(7).Value
    PrvCust = SSDBGrid1.Columns(11).Value
    PrvSpec = SSDBGrid1.Columns(13).Value
    Exit Sub
  Else
    MsgBox "Please Click on a Row to Clear the Data", vbInformation, "Invalid Row"
    Exit Sub
  End If
End Sub

'****************************************
'To Delete a record from the Grid and Update in the Database
'****************************************
Private Sub cmdDelete_Click()
  Dim nTotalSelRows As Integer, bkmrk As Variant, i As Integer, myDelRecCnt As Integer
  Dim myDelSQL As String, DelConfirm As Integer, myRowNo As Integer
  DelConfirm = MsgBox("Are you sure to delete this data ?", vbYesNo + vbQuestion, "Confirm Delete")
  If DelConfirm = vbYes Then
    If UBound(DiffSupervisor) >= SSDBGrid1.row Then
      If DiffSupervisor(SSDBGrid1.row) = True Then
        MsgBox "Can't Delete Employee Data that belongs to Supervisor " + Trim(SSDBGrid1.Columns(12).Value), vbInformation, "Authorization Required"
      Else
        OraSession.BeginTrans               'Begin the Transaction
        myDelSQL = "Delete from hourly_detail where Hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Row_Number = " + Str(SSDBGrid1.Columns(0).Value) + " and employee_id = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'"
        myDelRecCnt = OraDatabase.ExecuteSQL(myDelSQL)
        If myDelRecCnt = 0 Then
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
      myDelSQL = "Delete from hourly_detail where Hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Row_Number = " + Str(SSDBGrid1.Columns(0).Value) + " and employee_id = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'"
      myDelRecCnt = OraDatabase.ExecuteSQL(myDelSQL)
      If myDelRecCnt = 0 Then
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
    SetDefault      'To set default values to Previous Data in Grid
  End If
End Sub

'****************************************
'To return back to previous Screen
'****************************************
Private Sub cmdExit_Click()
  frmCorrection.MousePointer = vbHourglass
  DoubleEntryReportDate     'Check for Overlapped Hours
  frmCorrection.MousePointer = vbDefault
  Unload Me
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
    SSDBGrid2.RemoveAll
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
  
  SSDBCboSup.Text = ""          'Supervisor Filter has to be removed
  
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
      SSDBGrid2.RemoveAll
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
      SSDBGrid2.RemoveAll
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
      SSDBGrid2.RemoveAll
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
      SSDBGrid2.RemoveAll
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
      SSDBGrid2.RemoveAll
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
  Dim myDE As New DE_LCS
  Dim myDR As New DR_TimeSheet
  myDE.rsHourlyDetail_Grouping.Source = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name, b.* From Employee a, Hourly_Detail b, LCS_USER c Where b.Employee_id = a.Employee_Id and b.Exception = 'Y' and upper(b.Employee_Id) Not Like 'T%' and b.User_ID = '" + UserID + "' and b.User_ID = c.User_ID and b.Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(b.EARNING_TYPE_ID) <> 'LU' Order by Row_Number}  AS HourlyDetail COMPUTE HourlyDetail BY 'Employee_Type_ID','Employee_Sub_Type_Id','Employee_Id','Employee_Name'"
  myDE.rsHourlyDetail_Grouping.Open
  myDR.Refresh
  myDR.Show
  myDE.rsHourlyDetail_Grouping.Close
End Sub

'****************************************
'To Save the Record(s) from the grid to DB
'****************************************
Private Sub cmdUpdate_Click()
  Dim myDelSQL As String, i As Integer, j As Integer
  Dim mySQL As String, myFieldData() As String, myField As String
  
  SSDBGrid1.row = 0
  If UCase(Trim(SSDBGrid1.Columns(4).Value)) = "LU" Then
    'Don't Check for Null Data
  Else
    'If Trim(SSDBGrid1.Columns(1).Value) = "" Or Trim(SSDBGrid1.Columns(2).Value) = "" Or _
    '    Trim(SSDBGrid1.Columns(4).Value) = "" Then 'Or Trim(SSDBGrid1.Columns(6).Value) = "" Or Trim(SSDBGrid1.Columns(7).Value) = "" Or _
        'Trim(SSDBGrid1.Columns(8).Value) = "" Or Trim(SSDBGrid1.Columns(9).Value) = "" Or Trim(SSDBGrid1.Columns(11).Value) = "" Then
      'MsgBox "Please Enter Start, End Time and Reg/OT and Click on SAVE to Update the Database.", vbInformation, "Up dation Failure"
    If (Trim(SSDBGrid1.Columns(1).Value) = "" Or Trim(SSDBGrid1.Columns(6).Value) = "") Then
      If SavePrev = False Then
        'Msg Box "Please Enter Start Time and Service Code and Click on SAVE to Update the Database.", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Start Time and Service Code and Click on SAVE to Update the Database.", vbInformation, "Required Fields"
        SSDBGrid1.SelectByCell = True
        SSDBGrid1.SetFocus
        Exit Sub
      Else
        Exit Sub
      End If
    End If
  End If
  ' Change by Bruce LeBrun 2/10/2000  -  Do not delete records that have already been posted to solomon
  If SavePrev = True Then
    myDelSQL = "Delete from Hourly_Detail where Employee_ID = '" + arrEmpID(PrevIndex + 1) + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Exception = 'Y' and TO_SOLOMON <> 'P'"
  Else
    myDelSQL = "Delete from Hourly_Detail where Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Exception = 'Y' and TO_SOLOMON <> 'P'"
  End If
  OraDatabase.ExecuteSQL myDelSQL
  ReDim myFieldData(SSDBGrid1.rows) As String
  For i = 0 To 9
    SSDBGrid1.row = i
    'If Trim(SSDBGrid1.Columns(1).Value) = "" And Trim(SSDBGrid1.Columns(2).Value) = "" And _
    '   Trim(SSDBGrid1.Columns(4).Value) = "" Then 'And Trim(SSDBGrid1.Columns(6).Value) = "" And Trim(SSDBGrid1.Columns(7).Value) = "" And _
       'Trim(SSDBGrid1.Columns(8).Value) = "" And Trim(SSDBGrid1.Columns(9).Value) = "" And Trim(SSDBGrid1.Columns(11).Value) = "" Then
    If Trim(SSDBGrid1.Columns(1).Value) = "" And Trim(SSDBGrid1.Columns(6).Value) = "" Then
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
      'If Trim(SSDBGrid1.Columns(1).Value) = "" Or Trim(SSDBGrid1.Columns(2).Value) = "" Or _
      '   Trim(SSDBGrid1.Columns(4).Value) = "" Then 'Or Trim(SSDBGrid1.Columns(6).Value) = "" Or Trim(SSDBGrid1.Columns(7).Value) = "" Or _
         'Trim(SSDBGrid1.Columns(8).Value) = "" Or Trim(SSDBGrid1.Columns(9).Value) = "" Or Trim(SSDBGrid1.Columns(11).Value) = "" Then
      If (Trim(SSDBGrid1.Columns(1).Value) = "" Or Trim(SSDBGrid1.Columns(6).Value) = "") And SavePrev = False Then
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
    
    UpdateData
  Next
End Sub

'****************************************
'To Update the Data to DB
'****************************************
Private Sub UpdateData()
  Dim mySQL As String, HourlyRS As Object, myChkRS As Object, ExpFlag As String
  mySQL = "Select Hire_Date,Employee_ID, Row_Number, Start_Time, End_Time, Duration, Earning_Type_Id, Billing_Flag, Service_Code, Equipment_Id, Commodity_Code, Location_ID, Vessel_Id, Customer_ID, User_Id, Special_Code, Exception, Exception_Reason,exact_end from hourly_detail where Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'"
  Set HourlyRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  HourlyRS.AddNew
  If SavePrev = True Then
    Call CheckRowNo(SSDBGrid1.Columns(0).Value, arrEmpID(PrevIndex + 1))
  Else
    Call CheckRowNo(SSDBGrid1.Columns(0).Value, arrEmpID(lstEmpName.ListIndex + 1))
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
    HourlyRS.Fields("End_Time").Value = Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(2).Value, "hh:nnAM/PM")
    If Trim(SSDBGrid1.Columns(3).Value) = vbNullString Or IsNull(SSDBGrid1.Columns(3).Value) Then
      SSDBGrid1.Columns(3).Value = FindDuration(SSDBGrid1.Columns(1).Value, SSDBGrid1.Columns(2).Value)
    End If
    HourlyRS.Fields("Duration").Value = SSDBGrid1.Columns(3).Value
    HourlyRS.Fields("Earning_Type_Id").Value = SSDBGrid1.Columns(4).Value
    HourlyRS.Fields("User_ID").Value = SSDBGrid1.Columns(12).Value
    
    'All the rest blank and zero values
    HourlyRS.Fields("Billing_Flag").Value = "N"
    HourlyRS.Fields("Service_Code").Value = 0
    HourlyRS.Fields("Equipment_Id").Value = 0
    HourlyRS.Fields("Commodity_Code").Value = 0
    HourlyRS.Fields("Location_Id").Value = ""
    HourlyRS.Fields("Vessel_Id").Value = 0
    HourlyRS.Fields("Customer_ID").Value = 0
    'Update Exception Column
    ExpFlag = "Y"
    HourlyRS.Fields("Exception").Value = ExpFlag
    HourlyRS.Fields("Exception_Reason").Value = SSDBGrid1.Columns(14).Value
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
    'Update Exception Column
    ExpFlag = "Y"
    HourlyRS.Fields("Exception").Value = ExpFlag
    HourlyRS.Fields("Exception_Reason").Value = SSDBGrid1.Columns(14).Value
    If IsNull(SSDBGrid1.Columns(15).Value) Or Trim(SSDBGrid1.Columns(15).Value) = vbNullString Then
      'Do Nothing
    Else
      HourlyRS.Fields("Exact_End").Value = Format(SSDateCombo1.Text, "MM/DD/yyyy") & " " & Format(SSDBGrid1.Columns(15).Value, "hh:nnAM/PM")
    End If
    HourlyRS.Update
  End If
  'If Reg Hours exceeds 40 for a week, convert excess REG hours to OT
  'ConvertREGtoOT

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
  Set ShipRS = OraDatabase.DBCreateDynaset("Select * from Vessel Order by Vessel_ID", 0&)
  If ShipRS.EOF And ShipRS.BOF Then
    'Do Nothing
  Else
    ShipRS.MoveFirst
    Do While Not ShipRS.EOF
      dwnVessel.AddItem ShipRS.Fields("Vessel_ID").Value & "!" & ShipRS.Fields("Vessel_Name").Value
      ShipRS.MoveNext
    Loop
  End If
End Sub

Private Sub Form_Load()
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"

  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
  'To populate default values for UserID
  txtSupervisorID = UserID
  RowIndex = 0
  FirstOpen = True
  
  'Open Recordset Object and Populate employee details in list
  Call PopulateEmpName(2)
  ShowLocation      'Fill the Combo with Location Information
  'ShowCommodity     'Fill the Combo with Commodity Information
  'Call ShowEmployee("")      'Fill both ID and Name Combo with Employee Information
  ShowUser          'Fill the Combo with User Information
  cmdUpdate.Enabled = False
  cmdClearLine.Enabled = False
  cmdClearAll.Enabled = False
  cmdDelete.Enabled = False
  
  'To Save the data if next employee is selected without Saving
  FirstClick = True
  SavePrev = False
  
  'William Stansbury have access to Hourly Detail - but he can see only GUARDS in Empl list
  'If UCase(Trim(UserID)) = " E405833" Then
  'If UCase(Trim(UserID)) = " E002047" Then
  '  lstEmpName.Clear
  '  Call PopulateEmpName(12)
  'End If
End Sub

'****************************************
'To Unload Current Form and Open Previous Form
'****************************************
Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  If HideDblEntry = True Or OverlapEntry = False Then
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
  Dim EmpIDRS As Object, EmpNMRS As Object, mySQL As String
  SSDBCboEmp.Columns(0).Caption = "Employee ID"
  SSDBCboEmp.Columns(1).Caption = "Employee Name"
  ssdbcboName.Columns(0).Caption = "Employee Name"
  ssdbcboName.Columns(1).Caption = "Employee ID"
  If Trim(EmpType) = vbNullString Or IsNull(EmpType) Then
    mySQL = "Select employee_name, employee_id from employee where employee_id in (select Employee_id from daily_hire_list where hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))"
  Else      'For Guard - William Stansbury
    mySQL = "Select employee_name, employee_id from employee where upper(employee_type_id) = '" + EmpType + "' and employee_id in (select Employee_id from daily_hire_list where hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))"
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
  Set EmpNMRS = OraDatabase.DBCreateDynaset("Select employee_name, employee_id from employee where employee_id in (select Employee_id from daily_hire_list where hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')) Order by Employee_Name", 0&)
  If EmpNMRS.EOF And EmpNMRS.BOF Then
    Exit Sub
  Else
    EmpNMRS.MoveFirst
    Do While Not EmpNMRS.EOF
      ssdbcboName.AddItem EmpNMRS.Fields("Employee_Name").Value & "!" & EmpNMRS.Fields("Employee_ID").Value
      EmpNMRS.MoveNext
    Loop
  End If
  EmpIDRS.Close
  Set EmpIDRS = Nothing
  EmpNMRS.Close
  Set EmpNMRS = Nothing
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
  SSDBGrid1.Columns(5).Style = ssStyleCheckBox
  SSDBGrid2.Columns(5).Style = ssStyleCheckBox
  SSDBGrid1.Columns(6).DropDownHwnd = dwnService.hWnd
  SSDBGrid1.Columns(7).DropDownHwnd = dwnEquipment.hWnd
  SSDBGrid1.Columns(8).DropDownHwnd = dwnCommodity.hWnd
  SSDBGrid1.Columns(9).DropDownHwnd = dwnLocation.hWnd
  SSDBGrid1.Columns(10).DropDownHwnd = dwnVessel.hWnd
  SSDBGrid1.Columns(11).DropDownHwnd = dwnCustomer.hWnd
  SSDBGrid1.Columns(12).Locked = True
  SSDBGrid1.Columns(12).ForeColor = RGB(0, 0, 0)
  SSDBGrid2.Columns(12).ForeColor = RGB(0, 0, 0)
  SSDBGrid1.Columns(13).DropDownHwnd = dwnSpec.hWnd
End Sub

'****************************************
'To Set the Column Width on the Grid
'****************************************
Private Sub SetColWidth()
  SSDBGrid1.Columns(0).Visible = False
  SSDBGrid1.Columns(1).Width = 1200.189 '1425.26
  SSDBGrid1.Columns(1).Caption = "START"
  SSDBGrid1.Columns(2).Width = 1200.189 '1395.213
  SSDBGrid1.Columns(2).Caption = "END"
  SSDBGrid1.Columns(3).Width = 734.7402 '870.2363
  SSDBGrid1.Columns(3).Caption = "HRS"
  SSDBGrid1.Columns(4).Width = 1005.165 '1124.787
  SSDBGrid1.Columns(4).Caption = "REG/OT"
  SSDBGrid1.Columns(5).Width = 599.811  '645.1654 '705.2599
  SSDBGrid1.Columns(5).Caption = "BILL"
  SSDBGrid1.Columns(6).Width = 854.9292 '1154.835 '1290
  SSDBGrid1.Columns(6).Caption = "SERVICE"
  SSDBGrid1.Columns(7).Width = 824.882  '989.8583
  SSDBGrid1.Columns(7).Caption = "EQUIP"
  SSDBGrid1.Columns(8).Width = 840.189  '915.0237 '1019.906
  SSDBGrid1.Columns(8).Caption = "COMM"
  SSDBGrid1.Columns(9).Width = 989.8583 '1184.882 '1244.976
  SSDBGrid1.Columns(9).Caption = "CATE"
  SSDBGrid1.Columns(10).Width = 794.8347  '1049.953
  SSDBGrid1.Columns(10).Caption = "SHIP"
  SSDBGrid1.Columns(11).Width = 854.9292  '915.0237  '1049.953
  SSDBGrid1.Columns(11).Caption = "CUST"
  SSDBGrid1.Columns(12).Width = 1065.26   '1110.047  '1214.929
  SSDBGrid1.Columns(12).Caption = "SUPV"
  SSDBGrid1.Columns(13).Width = 810.1418  '929.7639  '975.1182
  SSDBGrid1.Columns(13).Caption = "SPEC"
  SSDBGrid1.Columns(14).Width = 1230.236  '1739.906
  SSDBGrid1.Columns(14).Caption = "REASON"
  SSDBGrid1.Columns(15).Width = 1349.858
  SSDBGrid1.Columns(15).Caption = "EXACTEND"
  
  SSDBGrid2.Columns(0).Visible = False
  SSDBGrid2.Columns(1).Width = 1200.189 '1425.26
  SSDBGrid2.Columns(1).Caption = "START"
  SSDBGrid2.Columns(2).Width = 1200.189 '1395.213
  SSDBGrid2.Columns(2).Caption = "END"
  SSDBGrid2.Columns(3).Width = 734.7402 '870.2363
  SSDBGrid2.Columns(3).Caption = "HRS"
  SSDBGrid2.Columns(4).Width = 1005.165 '1124.787
  SSDBGrid2.Columns(4).Caption = "REG/OT"
  SSDBGrid2.Columns(5).Width = 645.1654 '705.2599
  SSDBGrid2.Columns(5).Caption = "BILL"
  SSDBGrid2.Columns(6).Width = 1154.835 '1290
  SSDBGrid2.Columns(6).Caption = "SERVICE"
  SSDBGrid2.Columns(7).Width = 824.882  '989.8583
  SSDBGrid2.Columns(7).Caption = "EQUIP"
  SSDBGrid2.Columns(8).Width = 915.0237 '1019.906
  SSDBGrid2.Columns(8).Caption = "COMM"
  SSDBGrid2.Columns(9).Width = 1184.882 '1244.976
  SSDBGrid2.Columns(9).Caption = "CATE"
  SSDBGrid2.Columns(10).Width = 794.8347  '1049.953
  SSDBGrid2.Columns(10).Caption = "SHIP"
  SSDBGrid2.Columns(11).Width = 915.0237  '1049.953
  SSDBGrid2.Columns(11).Caption = "CUST"
  SSDBGrid2.Columns(12).Width = 1110.047  '1214.929
  SSDBGrid2.Columns(12).Caption = "SUPV"
  SSDBGrid2.Columns(13).Width = 929.7639  '975.1182
  SSDBGrid2.Columns(13).Caption = "SPEC"
  SSDBGrid2.Columns(14).Width = 1739.906
  SSDBGrid2.Columns(14).Caption = "REASON"
  SSDBGrid2.Columns(15).Width = 1349.858
  SSDBGrid2.Columns(15).Caption = "EXACTEND"
  
  Dim indxCtr As Integer
  For indxCtr = 0 To 13
    SSDBGrid2.Columns(indxCtr).Locked = True
  Next
  'To find the Total Hours Worked for the Day for each Employee
  Label4.Caption = "Total Hours : " + Trim(Str(FindTotalHrs))
End Sub

'****************************************
'To Populate Employee Details (Name, ID, Type-ID) on the List
'****************************************
Private Sub PopulateEmpName(FilterBy As Integer)
  Dim myEmpSQL As String, TotalEmp As Integer, myChkRS As Object
  Dim arrCnt As Integer, DayOpened As Boolean
  
  On Error GoTo ErrHandler
  If FilterBy = 0 Then                'Location
    myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(Location_Id) = '" + UCase(Trim(SSDBCboLoc.Text)) + "') Order by Employee_Name"
    ElseIf FilterBy = 1 Then          'Commodity
    myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Commodity_Code = " + SSDBCboComm.Text + ") Order by Employee_Name"
  ElseIf FilterBy = 2 Then            'All Employees
    'Check whether the Day is Closed
    myEmpSQL = "Select * from DailyHrs_TransTo_PayRoll where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
    Set myChkRS = OraDatabase.DBCreateDynaset(myEmpSQL, 0&)
    If myChkRS.EOF And myChkRS.BOF Then   'Day is NOT CLOSED
      If FirstOpen = True Then
        MsgBox "The Current Date is Not Closed. Please make modifications in Hourly Detail Screen", vbInformation, "Day Not Closed"
        FirstOpen = False
      Else
        MsgBox "The Selected Day is Not Closed. Please make modifications in Hourly Detail Screen", vbInformation, "Day Not Closed"
      End If
      SSDBCboLoc.Text = ""
      chkFilterBy(0).Value = 0
      SSDBCboSup.Text = ""
      SSDBCboEmp.Text = ""
      optFilterEmp(0).Value = False
      SSDBCboEmp.Enabled = False
      ssdbcboName.Text = ""
      optFilterEmp(1).Value = False
      ssdbcboName.Enabled = False
    Else
      'Only Guards for William Stansbury
      'If UCase(Trim(UserID)) = " E002047" Then
      'If UCase(Trim(UserID)) = " E405833" Then
      '  myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')) and Upper(Employee_Type_ID) = 'GUARD' Order by Employee_Name"
      '  Call ShowEmployee("GUARD")
      'Else
        myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')) Order by Employee_Name"
        Call ShowEmployee("")
      'End If
    End If
  ElseIf FilterBy = 3 Then            'User
    myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Hourly_Detail where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(User_Id) = '" + UCase(Trim(SSDBCboSup.Text)) + "') Order by Employee_Name"
  ElseIf FilterBy = 4 Then            'Location + Commodity
    myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Commodity_Code = " + SSDBCboComm.Text + " and Upper(Location_Id) = '" + UCase(Trim(SSDBCboLoc.Text)) + "') Order by Employee_Name"
  ElseIf FilterBy = 5 Then            'Employee ID
    myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(Employee_ID) like '%" + UCase(Trim(SSDBCboEmp.Text)) + "') Order by Employee_Name"
  ElseIf FilterBy = 6 Then           'Employee Name
    myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')) and Upper(Employee_Name) = '" + UCase(Trim(ssdbcboName.Text)) + "' Order by Employee_Name"
  ElseIf FilterBy = 12 Then           'Only Guards for William Stansbury
    myEmpSQL = "Select * from Employee where Employee_ID IN (Select Employee_ID from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')) and Upper(Employee_Type_ID) = 'GUARD' Order by Employee_Name"
  End If
  Set EmpRS = OraDatabase.DBCreateDynaset(myEmpSQL, 0&)
  If FilterBy = 2 And EmpRS.BOF And EmpRS.EOF Then        'All Employees
    Exit Sub
  ElseIf FilterBy = 12 And EmpRS.BOF And EmpRS.EOF Then   'Only Guards for William Stansbury
    MsgBox "No Employee Data with Type GUARD are available. ", vbInformation, "Data Unavailable"
    Exit Sub
  ElseIf FilterBy = 0 And EmpRS.BOF And EmpRS.EOF Then    'Location
    MsgBox "No Employee Data available for the Selected Location. ", vbInformation, "Data Unavailable"
    cmdUpdate.Enabled = False
    cmdClearLine.Enabled = False
    cmdClearAll.Enabled = False
    cmdDelete.Enabled = False
    SSDBGrid1.RemoveAll
    SSDBGrid2.RemoveAll
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
    SSDBGrid2.RemoveAll
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
    SSDBGrid2.RemoveAll
    SSDBCboSup.Text = ""
    Exit Sub
  ElseIf FilterBy = 4 And EmpRS.BOF And EmpRS.EOF Then    'Location and Commodity
    MsgBox "No Employee Data available for the Selected Location and Commodity. ", vbInformation, "Data Unavailable"
    cmdUpdate.Enabled = False
    cmdClearLine.Enabled = False
    cmdClearAll.Enabled = False
    cmdDelete.Enabled = False
    SSDBGrid1.RemoveAll
    SSDBGrid2.RemoveAll
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
    SSDBGrid2.RemoveAll
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
    SSDBGrid2.RemoveAll
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
  Dim TotalRec As Integer, mySQL As String, arrCnt As Integer
  mySQL = "Select Row_Number, Start_Time, End_Time, Duration, Earning_Type_Id, Billing_Flag, Service_Code, Equipment_Id, Commodity_Code, Location_ID, Vessel_Id, Customer_ID, User_Id, Special_Code,Exception_Reason from hourly_detail where Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' order by Row_Number"
  Set LRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If LRS.BOF And LRS.EOF Then
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
  End If
End Sub

'****************************************
'To Open the Recordset and Show Defaults on Grid
'****************************************
Private Sub lstEmpName_Click()
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
  OpenHourlyRS    'Define the Recordset Object for the Grid
  If ErrFlag = False Then
    AddRec        'Add the Fields and records in Grid
    ColumnFormat  'Add Dropdown to Columns
    SetColWidth   'To set Grid Column Width
    SetDefault    'Set Default Values to Previous Data from Grid
    If SSDBGrid1.rows = 10 Then SSDBGrid1.AllowAddNew = False
    
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
  Dim i As Integer, myStr() As String, myField As String
  Dim recCnt As Integer, Bill() As Boolean, NewCnt As Integer
  
  ct = LRS.Fields.count           'Count the total number of fields in recordset object
  SSDBGrid1.Columns.RemoveAll     'Remove all the current fields in Grid
  SSDBGrid2.Columns.RemoveAll     'Remove all the current fields in Grid
  
  'Redefine the array based on presence of records
  If NoRec = True Then
    ReDim myStr(1) As String
  Else
    ReDim myStr(ct) As String
  End If
  myStr(0) = ""
  
  'Add the fields in Grid
  For i = 0 To (ct - 1)
    SSDBGrid1.Columns.add i
    SSDBGrid1.Columns(i).Visible = True
    SSDBGrid1.Columns(i).Caption = LRS.Fields(i).Name
  Next
  For i = 0 To (ct - 1)
    SSDBGrid2.Columns.add i
    SSDBGrid2.Columns(i).Visible = True
    SSDBGrid2.Columns(i).Caption = LRS.Fields(i).Name
  Next
  
  If NoRec = True Then
  'No records in database - just add blank lines in Grid
    'ReDim DiffSupervisor(4) As Boolean   'Limit 10 Rows
    ReDim DiffSupervisor(9) As Boolean
    For i = 0 To (ct - 1)         'Loop through Fields
      myStr(0) = myStr(0) + "" + "!"
    Next
    myStr(0) = Left(myStr(0), Len(myStr(0)) - 1)
    
    'To have Start_Time as the default Time_In from Daily Hire Table and Location and Commodity Code as default
    Dim DailyHireRS As Object, myHireSQL As String
    myHireSQL = "Select Time_In, Location_Id, Commodity_Code from Daily_Hire_List Where Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
    Set DailyHireRS = OraDatabase.DBCreateDynaset(myHireSQL, 0&)
    If DailyHireRS.EOF And DailyHireRS.BOF Then
    'Do Nothing
      Exit Sub
    End If
    SSDBGrid1.AddItem "1" + "!" + Format(Str(DailyHireRS.Fields("time_in").Value), "hh:nnAM/PM") + "!" + "" + "!" + "" + "!" + "" + "!" + "" + "!" + "" + "!" + "" + "!" + Trim(Str(DailyHireRS.Fields("Commodity_Code").Value)) + "!" + DailyHireRS.Fields("Location_ID").Value + "!" + myStr(0)
    'PrvComm = DailyHireRS.Fields("Commodity_Code").Value
    PrvLoc = DailyHireRS.Fields("Location_ID").Value
    
    'For i = 2 To 4   'Limit 10 Rows
    For i = 2 To 9
      SSDBGrid1.AddItem Str(i) + "!" + myStr(0)
    Next
    
    'For i = 0 To 3    'Limit 10 Rows
    For i = 0 To 8
      SSDBGrid1.row = i
      'Default Values for GUARD - entered by  William Stansbury
      'If UCase(Trim(UserID)) = " E405833" Then
      'If UCase(Trim(UserID)) = " E002047" Then
      '      SSDBGrid1.Columns(5).Value = False
      '  '    SSDBGrid1.Columns(6).Value = "9723"
      '      SSDBGrid1.Columns(7).Value = "0"
      '      SSDBGrid1.Columns(8).Value = "0"
      '      SSDBGrid1.Columns(9).Value = "N/A"
      '      SSDBGrid1.Columns(10).Value = "0"
      '      SSDBGrid1.Columns(11).Value = "0"
      '      SSDBGrid1.Columns(12).Value = UserID
      '      SSDBGrid1.Columns(13).Value = "0"
        
      'Else
        'SSDBGrid1.Columns(5).Value = True           'Default - Billing Flag to be TRUE
        SSDBGrid1.Columns(5).Value = False           'Default - Billing Flag to be FALSE
        SSDBGrid1.Columns(12).Value = UserID        'Default - UserID is the logon Person
        SSDBGrid1.Columns(10).Value = 0             'Default - Vessel as No Ship - 0(N/A)
      'End If
    Next
  Else
  'Add the records from the database to the Grid
    LRS.MoveLast
    Dim TotalGrdRec As Integer
    TotalGrdRec = LRS.RecordCount
    ReDim DiffSupervisor(TotalGrdRec) As Boolean
    ReDim Bill(TotalGrdRec) As Boolean
    LRS.MoveFirst
    recCnt = 1
    
    'Check whether this is an Exception Records
    Dim mySQL As String, myExpRS As Object, NewRec As Boolean
'    mySQL = "Select Exception from Hourly_Detail where Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Row_Number = " + Str(LRS.Fields("Row_Number").Value)

' Changed by Bruce LeBrun 02/10/2000 - Don't place into modification grid if already posted to solomon
    
    mySQL = "Select Exception,TO_SOLOMON from Hourly_Detail where Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Row_Number = " + Str(LRS.Fields("Row_Number").Value)
    Set myExpRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
    If myExpRS.EOF And myExpRS.BOF Then
      'Do Nothing
    Else
      If myExpRS.Fields("Exception").Value = "Y" And GetValue(myExpRS.Fields("TO_SOLOMON").Value, "") <> "P" Then
        NewRec = True
      Else
        NewRec = False
      End If
    End If

    Do While Not LRS.EOF              'Loop through records
      For i = 0 To (ct - 1)           'Loop through Fields
        If i = 5 And UCase(Trim(LRS.Fields(i).Value)) = "Y" And GetValue(myExpRS.Fields("TO_SOLOMON").Value, "") <> "P" Then
          Bill(recCnt) = True
        ElseIf i = 5 And (UCase(Trim(LRS.Fields(i).Value)) = "N" Or GetValue(myExpRS.Fields("TO_SOLOMON").Value, "") = "P") Then
          Bill(recCnt) = False
        ElseIf IsNumeric(LRS.Fields(i).Value) Then
          myField = Str(LRS.Fields(i).Value)
        ElseIf IsNull(LRS.Fields(i).Value) Then
          myField = " "
        ElseIf i = 1 Or i = 2 Then
          myField = Format(LRS.Fields(i).Value, "hh:nnAM/PM")
        Else
          myField = LRS.Fields(i).Value
        End If
        myStr(recCnt) = myStr(recCnt) + Trim(myField) + "!"
      Next
      
      'User can't change Employee Data of Other User
      If Trim(UCase(UserID)) <> Trim(UCase(LRS.Fields("User_ID").Value)) Then
        DiffSupervisor(recCnt - 1) = True
      Else
        DiffSupervisor(recCnt - 1) = False
      End If
      
      'Add to the Appropriate Grid
      If NewRec = True Then
        myStr(recCnt) = Left(myStr(recCnt), Len(myStr(recCnt)) - 1)
        SSDBGrid1.AddItem myStr(recCnt)
        NewCnt = NewCnt + 1
      Else
        myStr(recCnt) = Left(myStr(recCnt), Len(myStr(recCnt)) - 1)
        SSDBGrid2.AddItem myStr(recCnt)
      End If
      
      recCnt = recCnt + 1
      LRS.MoveNext
      If Not LRS.EOF Then
        'Check whether this is an Exception Record
'        mySQL = "Select Exception from Hourly_Detail where Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Row_Number = " + Str(LRS.Fields("Row_Number").Value)
' Changed by Bruce LeBrun 02/10/2000 - Don't place into modification grid if already posted to solomon
        mySQL = "Select Exception,TO_SOLOMON from Hourly_Detail where Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Row_Number = " + Str(LRS.Fields("Row_Number").Value)
        Set myExpRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
        If myExpRS.EOF And myExpRS.BOF Then
          'Do Nothing
        Else
          If myExpRS.Fields("Exception").Value = "Y" And GetValue(myExpRS.Fields("TO_SOLOMON").Value, "") <> "P" Then NewRec = True Else NewRec = False
        End If
      End If
    Loop
    
 '   'If recCnt <= 4 Then   'Limit 10 Rows
    If recCnt <= 9 Then
      Dim rowNo As Integer
      rowNo = recCnt
      'For i = 1 To (5 - recCnt)    'Limit 10 Rows
      For i = 1 To (11 - recCnt)
        SSDBGrid1.AddItem Str(rowNo) + "!" + myStr(0)
        rowNo = rowNo + 1
      Next
      'For i = (recCnt - 1) To 3    'Limit 10 Rows
      If NewCnt = 0 Then NewCnt = recCnt - 1
      For i = NewCnt To 8
        SSDBGrid1.row = i
        'Default Values for GUARD - entered by  William Stansbury
        'If UCase(Trim(UserID)) = " E002047" Then
        'If UCase(Trim(UserID)) = " E405833" Then
        '  SSDBGrid1.Columns(5).Value = False
        '  'SSDBGrid1.Columns(6).Value = "9723"
        '  SSDBGrid1.Columns(7).Value = "0"
        '  SSDBGrid1.Columns(8).Value = "0"
        '  SSDBGrid1.Columns(9).Value = "N/A"
        '  SSDBGrid1.Columns(10).Value = "0"
        '  SSDBGrid1.Columns(11).Value = "0"
        '  SSDBGrid1.Columns(12).Value = UserID
        '  SSDBGrid1.Columns(13).Value = "0"
        'Else
          'SSDBGrid1.Columns(5).Value = True
          SSDBGrid1.Columns(5).Value = False
          SSDBGrid1.Columns(12).Value = UserID
        'End If
      Next
    End If
    For i = 1 To recCnt - 1
      SSDBGrid1.row = i - 1
      'SSDBGrid1.Columns(5).Value = True
      SSDBGrid1.Columns(5).Value = False
      SSDBGrid1.Columns(12).Value = UserID
    Next
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
  SSDBGrid2.RemoveAll
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
  SSDBGrid2.RemoveAll
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
  SSDBGrid2.RemoveAll
  cmdUpdate.Enabled = False
  cmdDelete.Enabled = False
  cmdClearLine.Enabled = False
  cmdClearAll.Enabled = False
End Sub

'****************************************
'To Store Grid Content on Variables for Previous Values
'****************************************
Private Sub SSDBGrid1_AfterColUpdate(ByVal ColIndex As Integer)
  If (ColIndex = 1 Or ColIndex = 2) And dwnTime.ListAutoValidate = False Then
    dwnTime.ListAutoValidate = True
  ElseIf ColIndex = 3 And dwnHrs.ListAutoValidate = False Then
    dwnHrs.ListAutoValidate = True
  ElseIf ColIndex = 4 And dwnEarning.ListAutoValidate = False Then
    dwnEarning.ListAutoValidate = True
  ElseIf ColIndex = 6 And dwnService.ListAutoValidate = False Then
    dwnService.ListAutoValidate = True
  ElseIf ColIndex = 7 And dwnEquipment.ListAutoValidate = False Then
    dwnEquipment.ListAutoValidate = True
  ElseIf ColIndex = 8 And dwnCommodity.ListAutoValidate = False Then
    dwnCommodity.ListAutoValidate = True
  ElseIf ColIndex = 9 And dwnLocation.ListAutoValidate = False Then
    dwnLocation.ListAutoValidate = True
  ElseIf ColIndex = 10 And dwnVessel.ListAutoValidate = False Then
    dwnVessel.ListAutoValidate = True
  ElseIf ColIndex = 11 And dwnCustomer.ListAutoValidate = False Then
    dwnCustomer.ListAutoValidate = True
  End If
  Select Case ColIndex
  Case 2
    If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then
      PrvEnd = UCase(SSDBGrid1.ActiveCell.Text)
    End If
  Case 4
    If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then
      PrvReg = UCase(SSDBGrid1.ActiveCell.Text)
    End If
  Case 6
    If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then
      PrvSvc = UCase(SSDBGrid1.ActiveCell.Text)
    End If
  Case 7
    If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then
      PrvEquip = UCase(SSDBGrid1.ActiveCell.Text)
    End If
  Case 8
    If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then
      PrvComm = UCase(SSDBGrid1.ActiveCell.Text)
    End If
  Case 11
    If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then
      PrvCust = UCase(SSDBGrid1.ActiveCell.Text)
    End If
  Case 13
    If Trim(SSDBGrid1.ActiveCell.Text) <> vbNullString Then
      PrvSpec = UCase(SSDBGrid1.ActiveCell.Text)
    End If
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
  End If

  'Check for User ID - allow changes to same User Only
  CurrValue = SSDBGrid1.Columns(SSDBGrid1.Col).Value
  ' Commented out by Bruce LeBrun - 02/07/2000 per Inigo Thomas
  ' For the short term we will allow any supervisor to modify values.
'  If SSDBGrid1.Row > UBound(DiffSupervisor) Then
'    'Do Nothing
'  Else
'    If DiffSupervisor(SSDBGrid1.Row) = True And CurrValue <> PrevValue Then
'      MsgBox "Can't Edit Employee Data that belongs to Supervisor " + Trim(SSDBGrid1.Columns(12).Value), vbInformation, "Authorization Required"
'      SSDBGrid1.Columns(SSDBGrid1.Col).Value = Trim(PrevValue)
'    End If
'  End If
  
  'Earning_Type (Double Time) DT can be selected only when the Duration is 1 Hour
  If SSDBGrid1.Col >= 4 And SSDBGrid1.Columns(3).Value > 1 Then
    If UCase(Trim(SSDBGrid1.Columns(4).Value)) = "DT" Then
      MsgBox "Double Time Can't be More than an Hour", vbInformation, "Validation Failure"
      SSDBGrid1.Columns(4).Value = ""
      SSDBGrid1.Col = 4
    End If
  End If
End Sub

'****************************************
'To revert back if different User Changes data
'****************************************
Private Sub SSDBGrid1_Click()
  If SSDBGrid1.Col <> -1 Then
    PrevValue = SSDBGrid1.Columns(SSDBGrid1.Col).Value
  End If
  cmdUpdate.Enabled = True
End Sub

'****************************************
'To Validate Certain Criteria and to place Defaults on Grid
'****************************************
Private Sub SSDBGrid1_RowColChange(ByVal LastRow As Variant, ByVal LastCol As Integer)
  On Error GoTo ErrHandler
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
  
  'Default Values for GUARD - entered by  William Stansbury
  'If UCase(Trim(UserID)) = " E405833" Then
  'If UCase(Trim(UserID)) = " E002047" Then
  '  SSDBGrid1.Columns(5).Value = False
  '  'SSDBGrid1.Columns(6).Value = "9723"
  '  SSDBGrid1.Columns(7).Value = "0"
  '  SSDBGrid1.Columns(8).Value = "0"
  '  SSDBGrid1.Columns(9).Value = "N/A"
  '  SSDBGrid1.Columns(10).Value = "0"
  '  SSDBGrid1.Columns(11).Value = "0"
  '  SSDBGrid1.Columns(12).Value = UserID
  '  SSDBGrid1.Columns(13).Value = "0"
  'End If
  
  'To Make AM/PM upper case
  If SSDBGrid1.Col > 2 Then
    SSDBGrid1.Columns(1).Value = UCase(SSDBGrid1.Columns(1).Value)
    SSDBGrid1.Columns(2).Value = UCase(SSDBGrid1.Columns(2).Value)
  End If
  
  'To Handle Lunch Hour
  If SSDBGrid1.Col > 4 And UCase(Trim(SSDBGrid1.Columns(4).Value)) = "LU" Then
    SSDBGrid1.Columns(5).Value = False
    SSDBGrid1.Columns(6).Value = ""
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
  Else
    SSDBGrid1.Columns(6).DropDownHwnd = dwnService.hWnd
    SSDBGrid1.Columns(7).DropDownHwnd = dwnEquipment.hWnd
    SSDBGrid1.Columns(8).DropDownHwnd = dwnCommodity.hWnd
    SSDBGrid1.Columns(9).DropDownHwnd = dwnLocation.hWnd
    SSDBGrid1.Columns(10).DropDownHwnd = dwnVessel.hWnd
    SSDBGrid1.Columns(11).DropDownHwnd = dwnCustomer.hWnd
    SSDBGrid1.Columns(13).DropDownHwnd = dwnSpec.hWnd
  End If
  
  'For Call-in Employees, default Hrs is 4.
  If SSDBGrid1.Col > 4 And UCase(Trim(SSDBGrid1.Columns(4).Value)) = "CALL-IN" Then
    SSDBGrid1.Columns(3).Value = "4.00"
    If IsNull(SSDBGrid1.Columns(2).Value) Or Trim(SSDBGrid1.Columns(2).Value) = vbNullString Then
      'Do Nothing
    Else
      SSDBGrid1.Columns(15).Value = SSDBGrid1.Columns(2).Value
      SSDBGrid1.Columns(2).Value = FindEndTime
    End If
  End If

  'To Filter Ships based on Ship_Flag in Service Table
  If SSDBGrid1.Col > 6 And Trim(SSDBGrid1.Columns(6).Value) <> vbNullString Then
    Dim mySvcRS As Object, mySvcSQL As String
    mySvcSQL = "Select Ship_Flag from Service where Service_Code = " + SSDBGrid1.Columns(6).Value
    Set mySvcRS = OraDatabase.DBCreateDynaset(mySvcSQL, 0&)
    If mySvcRS.Fields("Ship_Flag").Value = "Y" Then
      'Show All Ships
      SSDBGrid1.Columns(10).DropDownHwnd = dwnVessel.hWnd
    Else
      'Show Only N/A (0)
      SSDBGrid1.Columns(10).Value = 0
      SSDBGrid1.Columns(10).DropDownHwnd = False
    End If
  End If
  
  'To Get Previous Row's Values as default for the Current Row
  If Trim$(SSDBGrid1.ActiveCell.Text) = vbNullString And LastCol = SSDBGrid1.Cols - 1 And UCase(Trim(SSDBGrid1.Columns(4).Value)) <> "LU" Then
    If Trim(PrvEnd) = vbNullString Or Trim(PrvReg) = vbNullString Or Trim(PrvComm) = vbNullString Or Trim(PrvLoc) = vbNullString _
      Or Trim(PrvEquip) = vbNullString Or Trim(PrvSvc) = vbNullString Or Trim(PrvCust) = vbNullString Or Trim(PrvSpec) = vbNullString Then
      'Take the Previous Row's Values from Database and Store
      Dim myLastRS As Object, mySQL As String
      mySQL = "Select End_Time, Earning_Type_Id, Service_Code, Equipment_Id, Commodity_Code, Location_ID, Vessel_Id, Customer_ID,Special_Code from hourly_detail where Hire_Date=to_date('" + SSDateCombo1.Text + " ','mm/dd/yyyy') and Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'"
      mySQL = mySQL + " and Row_Number in (select max(Row_Number) from Hourly_detail where Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))"

      Set myLastRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
      If myLastRS.EOF And myLastRS.BOF Then
        'Do Nothing
     '   SSDBGrid1.Row = 0
     '   Exit Sub
      Else
        If Trim(PrvEnd) = vbNullString Then
          PrvEnd = Format(myLastRS.Fields("End_Time").Value, "hh:nnAM/PM")
        End If
        If Trim(myLastRS.Fields("Earning_Type_Id").Value) <> vbNullString And Not IsNull(myLastRS.Fields("Earning_Type_Id").Value) Then
          PrvReg = myLastRS.Fields("Earning_Type_Id").Value
        Else
          PrvReg = ""
        End If
        If Trim(myLastRS.Fields("Equipment_Id").Value) <> vbNullString And Not IsNull(myLastRS.Fields("Equipment_Id").Value) Then
          PrvEquip = myLastRS.Fields("Equipment_Id").Value
        Else
          PrvEquip = 0
        End If
        If Trim(myLastRS.Fields("Commodity_Code").Value) <> vbNullString And Not IsNull(myLastRS.Fields("Commodity_Code").Value) Then
          PrvComm = myLastRS.Fields("Commodity_Code").Value
        Else
          PrvComm = 0
        End If
        If Trim(myLastRS.Fields("Location_ID").Value) <> vbNullString And Not IsNull(myLastRS.Fields("Location_ID").Value) Then
          PrvLoc = myLastRS.Fields("Location_ID").Value
        Else
          PrvLoc = ""
        End If
        If Trim(myLastRS.Fields("Service_Code").Value) <> vbNullString And Not IsNull(myLastRS.Fields("Service_Code").Value) Then
          PrvSvc = myLastRS.Fields("Service_Code").Value
        Else
          PrvSvc = 0
        End If
        'PrvShip = myLastRS.Fields("Vessel_Id").Value
        If Trim(myLastRS.Fields("Customer_ID").Value) <> vbNullString And Not IsNull(myLastRS.Fields("Customer_ID").Value) Then
          PrvCust = myLastRS.Fields("Customer_ID").Value
        Else
          PrvCust = 0
        End If
        If Trim(myLastRS.Fields("Special_Code").Value) <> vbNullString And Not IsNull(myLastRS.Fields("Special_Code").Value) Then
          PrvSpec = myLastRS.Fields("Special_Code").Value
        Else
          PrvSpec = 0
        End If
      End If
      Set myLastRS = Nothing
    End If
    'If Duration is entered and End Time is found then PrvEnd will be "" after Saving that Record
    If Trim(PrvEnd) = vbNullString Then
      mySQL = "Select End_Time from hourly_detail where Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'"
      mySQL = mySQL + " and Row_Number in (select max(Row_Number) from Hourly_detail where Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' and Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy'))"
      Set myLastRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
      PrvEnd = Format(myLastRS.Fields("End_Time").Value, "hh:nnAM/PM")
      Set myLastRS = Nothing
    End If
    'Values are stored in AfterColUpdate - use them
'    If SSDBGrid1.Rows > 8 Then    'Add at the end only if Grid has scroll bars
'      SSDBGrid1.AddItem Trim(Str(SSDBGrid1.Row + 1)) & "!" & PrvEnd & "!" & "" & "!" & "" & "!" & PrvReg & "!" & True & "!" & PrvSvc & "!" & PrvEquip & "!" & PrvComm & "!" & PrvLoc & "!" & "0" & "!" & PrvCust & "!" & UserID, SSDBGrid1.Rows - 3
'      ReDim Preserve DiffSupervisor(SSDBGrid1.Rows) As Boolean
'      DiffSupervisor(SSDBGrid1.Row) = False
'    Else
    If SSDBGrid1.rows <= 9 Then
      SSDBGrid1.AddItem Trim(Str(SSDBGrid1.row + 1)) & "!" & PrvEnd & "!" & "" & "!" & "" & "!" & PrvReg & "!" & True & "!" & PrvSvc & "!" & PrvEquip & "!" & PrvComm & "!" & PrvLoc & "!" & "0" & "!" & PrvCust & "!" & UserID & "!" & PrvSpec, SSDBGrid1.row
      ReDim Preserve DiffSupervisor(SSDBGrid1.rows) As Boolean
      DiffSupervisor(SSDBGrid1.rows) = False
      SendKeys "{right}"
      SendKeys "{right}"
      SendKeys "{Left}"
    ElseIf SSDBGrid1.row < 9 Then
      SSDBGrid1.RemoveItem SSDBGrid1.row
      SSDBGrid1.AddItem Trim(Str(SSDBGrid1.row + 1)) & "!" & PrvEnd & "!" & "" & "!" & "" & "!" & PrvReg & "!" & True & "!" & PrvSvc & "!" & PrvEquip & "!" & PrvComm & "!" & PrvLoc & "!" & "0" & "!" & PrvCust & "!" & UserID & "!" & PrvSpec, SSDBGrid1.row
      SendKeys "{tab}"
      SendKeys "{right}"
    ElseIf SSDBGrid1.row = 9 Then
      SSDBGrid1.RemoveItem SSDBGrid1.row
      SSDBGrid1.AddItem Trim(Str(SSDBGrid1.row + 1)) & "!" & PrvEnd & "!" & "" & "!" & "" & "!" & PrvReg & "!" & True & "!" & PrvSvc & "!" & PrvEquip & "!" & PrvComm & "!" & PrvLoc & "!" & "0" & "!" & PrvCust & "!" & UserID & "!" & PrvSpec, SSDBGrid1.row
    ElseIf SSDBGrid1.row = 10 Then
      SSDBGrid1.AllowAddNew = False
    End If
    
    'To assign default values for the newly added row
    If Trim(SSDBGrid1.Columns(0).Value) = "" And SSDBGrid1.row <> 0 Then
      'SSDBGrid1.Columns(5).Value = True
      SSDBGrid1.Columns(5).Value = False
      SSDBGrid1.Columns(12).Value = UserID
      SSDBGrid1.Columns(0).Value = Str(SSDBGrid1.row + 1)
      ReDim DiffSupervisor(SSDBGrid1.rows) As Boolean
    End If
'    SetDefault    'Store Blank Values to Variables
  End If

  'To Make Character Entry Converted to Upper Case
  If SSDBGrid1.Col = 5 Or SSDBGrid1.Col = 10 Then
    SSDBGrid1.Columns(SSDBGrid1.Col - 1).Value = UCase(SSDBGrid1.Columns(SSDBGrid1.Col - 1).Value)
  End If

  'To Validate Start Time
'  If SSDBGrid1.Col = 2 Then
'    If ValidateTime(SSDBGrid1.Columns(1).Value) = True Then
'      MsgBox "Incorrect Time Format" + Chr(13) + "Eg. 08:00AM", vbInformation, "Time Validation Failure"
'      SSDBGrid1.Columns(1).Value = ""
'      SSDBGrid1.Col = 1
'      Exit Sub
'    End If
'  End If
  
  'To Find the difference between Start and End Time for Duration Column
  If SSDBGrid1.Col >= 4 Then
    Duration
  End If
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
  Dim MN1 As Integer, MN2 As Integer
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
End Function

'****************************************
'To Find the Number of Hours between Start and End Time
'****************************************
Private Function FindDuration(myStart As String, myEnd As String) As String
  '******************************************************
  'Case     Start       End     Result
  '1        #AM         #AM     E - S
  '         #PM         #PM
  '2        12AM        #AM     E
  '         12PM        #PM
  '3        #AM         #PM     12 - S + E
  '         #PM         #AM
  '4        #AM         12PM    E - S
  '         #PM         12AM
  '5        12PM        #AM     S + E
  '         12AM        #PM
  '6        12AM        12PM    12
  '         12PM        12AM
  '7        12AM        12AM    0
  '         12PM        12PM
  '8        #AM         12AM    12 - S + E
  '         #PM         12PM
  '******************************************************
  
  Dim TZ1 As String, TZ2 As String
  Dim HR1 As Integer, HR2 As Integer
  Dim MN1 As Integer, MN2 As Integer
  Dim FindMinutes As String, LessHrs As Boolean, FindDur As String
  
  TZ1 = UCase(Right(Trim(myStart), 2))
  TZ2 = UCase(Right(Trim(myEnd), 2))
  HR1 = CInt(Left(Trim(myStart), 2))
  HR2 = CInt(Left(Trim(myEnd), 2))
  MN1 = CInt(Mid(Trim(myStart), 4, 2))
  MN2 = CInt(Mid(Trim(myEnd), 4, 2))
  
  If HR1 = 8 And MN1 = 0 And HR2 = 5 And MN2 = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    FindDuration = "8.0"
    Exit Function
  End If
  If HR1 = 8 And MN1 = 0 And HR2 = 6 And MN2 = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    FindDuration = "9.0"
    Exit Function
  End If
  If HR1 = 7 And MN1 = 0 And HR2 = 6 And MN2 = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    FindDuration = "10.0"
    Exit Function
  End If
  If HR1 = 7 And MN1 = 0 And HR2 = 5 And MN2 = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    FindDuration = "9.0"
    Exit Function
  End If
  
  'Difference in Minutes
  If MN1 = MN2 Then
    'FindMinutes = "00"  'Duration in 1/2 an Hour
    FindMinutes = "0"
  ElseIf MN1 < MN2 Then
    FindMinutes = Str(MN2 - MN1)
  Else
    FindMinutes = Str(60 - MN1 + MN2)
    If TZ1 = TZ2 And TZ1 = "PM" And HR1 <> 12 Then
      'Do Nothing
       LessHrs = True
    ElseIf TZ1 = TZ2 And TZ1 = "AM" And HR1 = HR2 And HR1 = 12 Then
      'Do Nothing
 '   Else
 '     LessHrs = True 'Duration in 1/2 an Hour
    End If
  End If
 'Duration in 1/2 an Hour << Start
'  If Len(Trim(FindMinutes)) < 2 Then
'    FindMinutes = "0" + Trim(FindMinutes)
'  End If
 
'  If Trim(FindMinutes) = "15" Then
'    FindMinutes = "25"
'  ElseIf Trim(FindMinutes) = "30" Then
'    FindMinutes = "50"
'  ElseIf Trim(FindMinutes) = "45" Then
'    FindMinutes = "75"
'  End If
'Duration in 1/2 an Hour >> End
  If Trim(FindMinutes) = "30" Then
    FindMinutes = "5"
  End If
  
  'Difference in Hours
  If TZ1 = TZ2 And HR1 = HR2 And MN1 = 30 And MN2 = 0 Then
    FindDur = "23"
  ElseIf TZ1 = TZ2 And HR1 = HR2 And MN1 = MN2 Then
    FindDur = "0"
  ElseIf TZ1 = "AM" And TZ2 = "AM" And HR1 <> 12 And HR2 <> 12 Then 'Case 1
    If HR1 > HR2 Then
      FindDur = Str(12 + 12 - HR1 + HR2)
    Else
      FindDur = Str(HR2 - HR1)
    End If
  ElseIf TZ1 = "PM" And TZ2 = "PM" And HR1 <> 12 And HR2 <> 12 Then 'Case 1
    FindDur = Str(HR2 - HR1)
  ElseIf TZ1 = "AM" And TZ2 = "AM" And HR1 = 12 And HR2 <> 12 Then 'Case 2
    FindDur = Str(HR2)
  ElseIf TZ1 = "PM" And TZ2 = "PM" And HR1 = 12 And HR2 <> 12 Then 'Case 2
    If HR2 = 1 And MN1 > MN2 Then
      FindDur = "0"
    ElseIf HR2 = 1 And MN1 <= MN2 Then
      FindDur = Str(HR2)
    Else
      FindDur = Str(HR2)
    End If
  ElseIf TZ1 = "PM" And TZ2 = "AM" And HR1 <> 12 And HR2 <> 12 Then  'Case 3
    FindDur = Str(12 - HR1 + HR2)
  ElseIf TZ1 = "AM" And TZ2 = "PM" And HR1 <> 12 And HR2 <> 12 Then  'Case 3
    FindDur = Str(12 - HR1 + HR2)
  ElseIf TZ1 = "AM" And TZ2 = "PM" And HR2 = 12 And HR1 <> 12 Then  'Case 4
    FindDur = Str(HR2 - HR1)
  ElseIf TZ1 = "PM" And TZ2 = "AM" And HR2 = 12 And HR1 <> 12 Then  'Case 4
    FindDur = Str(HR2 - HR1)
  ElseIf TZ1 = "AM" And TZ2 = "PM" And HR1 = 12 And HR2 <> 12 Then  'Case 5
    FindDur = Str(HR1 + HR2)
  ElseIf TZ1 = "PM" And TZ2 = "AM" And HR1 = 12 And HR2 <> 12 Then  'Case 5
    FindDur = Str(HR1 + HR2)
  ElseIf TZ1 = "PM" And TZ2 = "AM" And HR1 = 12 And HR2 = 12 Then  'Case 6
    FindDur = "12"
  ElseIf TZ1 = "AM" And TZ2 = "PM" And HR1 = 12 And HR2 = 12 Then  'Case 6
    FindDur = "12"
  ElseIf TZ1 = "PM" And TZ2 = "PM" And HR1 = 12 And HR2 = 12 Then 'Case 7
    FindDur = "0"
  ElseIf TZ1 = "AM" And TZ2 = "AM" And HR1 = 12 And HR2 = 12 Then 'Case 7
    FindDur = "0"
  ElseIf TZ1 = "AM" And TZ2 = "AM" And HR1 <> 12 And HR2 = 12 Then  'Case 8
    FindDur = Str(12 - HR1 + HR2)
  ElseIf TZ1 = "PM" And TZ2 = "PM" And HR1 <> 12 And HR2 = 12 Then  'Case 8
    FindDur = Str(12 - HR1 + HR2)
  End If
  
  'Concatenate Minutes with Hours and return the Value
  If LessHrs = True Then  'Less 1 from Hour
    FindDur = Trim(Str(CInt(FindDur) - 1))
  Else
    FindDur = Trim(FindDur)
  End If
  
  'If Start in AM and End in PM, just deduct 1 from Duration - Not for William Stansbury
'  If UserID <> " E405833" And TZ1 = "AM" And TZ2 = "PM" Then
'    If SSDBGrid1.Columns(1).Value > "12:30PM" Then FindMinutes = "0"
'    ElseIf SSDBGrid1.Columns(1).Value > "1:00PM" Then FindDur = Trim(Str(CInt(FindDur) - 1))
'    End If
'  End If

  FindDuration = Trim(Str(CSng(FindDur) + CSng(FindMinutes) / 10))
  If Len(Trim(FindDuration)) = 1 Then FindDuration = FindDuration + ".0"
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
        If RowRS.EOF Then Exit Sub
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
  PrvComm = 0
  PrvSpec = ""
'  PrvLoc = ""
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
      'myHireSQL = "Select a.Start_Time, a.End_Time, a.Row_Number, a.User_ID, a.Employee_ID, b.Employee_Name from Hourly_Detail a, Employee b where a.Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and a.Employee_ID = '" + DblEmpRS.Fields("Employee_ID").Value + "' and a.Employee_ID = b.Employee_ID Order by a.Row_Number"
     
      ' Bruce LeBrun - 2/10/2000 - changed SQL statement to prevent NULL Start Time records from being loaded.
      myHireSQL = "Select a.Start_Time, a.End_Time, a.Row_Number, a.User_ID, a.Employee_ID, b.Employee_Name from Hourly_Detail a, Employee b where a.Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and a.Employee_ID = '" + DblEmpRS.Fields("Employee_ID").Value + "' and a.Employee_ID = b.Employee_ID and a.duration > 0 Order by a.Row_Number"
      
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
          myStart = DblRS.Fields("Start_Time").Value
          If Trim(DblRS.Fields("End_Time").Value) = vbNullString Or IsNull(DblRS.Fields("End_Time").Value) Then
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
  If HideDblEntry = False And OverlapEntry = True Then
    Me.Hide
    frmDblEntryRpt.Show
  End If
End Sub

'****************************************
'To automatically convert REGULAR Hours over than 40 to OT
'****************************************
Private Sub ConvertREGtoOT()
  Dim CurrDay As Integer, WeekNo As String, mySQL As String, Over40RS As Object
  Dim RegRS As Object, ExcessHrs As Double, NewDur As Double, NewEnd As String
  Dim Start_Time As String, End_Time As String, NewRow As Integer, myrec As Integer
  Dim myEmpID As String, myHireDt As Date, myCommCd As Integer, myExpReason As String
  Dim myCustID As Integer, mySvcCd As Integer, myVessel As Long  ', myExp As String
  Dim myEquip As Integer, myLoc As String, myEnd As String, myBill As String
  Dim myUser As String, mySpec As String, myExactEnd As String
  Dim iSMyYear As String
  
  iSMyYear = Format(Now, "YYYY")
  'Get the Current Week No
  CurrDay = Format(SSDateCombo1.Text, "w")
  If CurrDay = 1 Then
    WeekNo = Str(Format(SSDateCombo1.Text, "ww") - 2)
  Else
    WeekNo = Str(Format(SSDateCombo1.Text, "ww") - 1)
  End If
  'Get Regular Hours for the employee for the whole week
  mySQL = "Select Sum(Duration) Total from hourly_detail where Earning_Type_Id = 'REG' and TO_CHAR(TRUNC(HIRE_DATE),'IW') = " + WeekNo
  mySQL = mySQL & " AND HIRE_DATE >=TO_DATE('01/01/" & iSMyYear & "','MM/DD/YYYY') and Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "'"
  Set Over40RS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If Over40RS.EOF And Over40RS.BOF Then
    'Do Nothing
  Else
    Over40RS.MoveFirst  'Only One Record for the whole week
    If Over40RS.Fields("Total").Value > 40 Then
      'Conversion Process
      ExcessHrs = Over40RS.Fields("Total").Value - 40
      mySQL = "Select Row_Number, Hire_Date, Start_Time, End_Time, Duration, Earning_Type_Id, Employee_ID, Billing_Flag, Service_Code, Equipment_Id, Commodity_Code, Location_ID, Vessel_Id, Customer_ID, User_Id, Special_Code,Remark, Exact_end, Exception, Exception_Reason from hourly_detail where TO_CHAR(TRUNC(HIRE_DATE),'IW') = " + WeekNo
      mySQL = mySQL & " AND HIRE_DATE >=TO_DATE('01/01/" & iSMyYear & "','MM/DD/YYYY') and Employee_ID = '" + arrEmpID(lstEmpName.ListIndex + 1) + "' order by Hire_Date, Row_Number"
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
                myEnd = RegRS.Fields("End_Time").Value
              End If
              RegRS.Fields("End_Time").Value = Format(Str(RegRS.Fields("Hire_Date").Value), "MM/DD/yyYY") & " " & Format(NewEnd, "hh:nnAM/PM")
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
              If IsNull(RegRS.Fields("Exception_Reason").Value) Or Trim(RegRS.Fields("Exception_Reason").Value) = vbNullString Then
                myExpReason = ""
              Else
                myExpReason = RegRS.Fields("Exception_Reason").Value
              End If
              If IsNull(RegRS.Fields("Exact_End").Value) Or Trim(RegRS.Fields("Exact_End").Value) = vbNullString Then
                myExactEnd = ""
              Else
                myExactEnd = RegRS.Fields("Exact_End").Value
              End If
              
              RegRS.AddNew
              RegRS.Fields("Hire_Date").Value = myHireDt
              RegRS.Fields("Employee_ID").Value = myEmpID
              RegRS.Fields("Row_Number").Value = NewRow
              RegRS.Fields("Commodity_Code").Value = myCommCd
              RegRS.Fields("Customer_ID").Value = myCustID
              RegRS.Fields("Earning_Type_ID").Value = "OT"
              RegRS.Fields("Service_Code").Value = mySvcCd
              RegRS.Fields("Vessel_ID").Value = myVessel
              RegRS.Fields("Equipment_ID").Value = myEquip
              RegRS.Fields("Location_ID").Value = myLoc
              RegRS.Fields("Start_Time").Value = NewEnd
              If myEnd = "" Then
                'Do Nothing
              Else
                RegRS.Fields("End_Time").Value = myEnd
              End If
              RegRS.Fields("Duration").Value = ExcessHrs
              RegRS.Fields("Billing_Flag").Value = myBill
              RegRS.Fields("User_ID").Value = myUser
              RegRS.Fields("Special_Code").Value = mySpec
              RegRS.Fields("Exception").Value = "Y"
              RegRS.Fields("Remark").Value = "System"
              RegRS.Fields("Exception_Reason").Value = myExpReason
              If myExactEnd = "" Then
                'Do Nothing
              Else
                RegRS.Fields("Exact_End").Value = myExactEnd
              End If
              RegRS.Update
'              mySQL = "IN SERT INTO HOURLY_DETAIL VALUES(to_date('" + Format(Str(RegRS.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy'),'" + RegRS.Fields("Employee_ID").Value + "'," + Str(NewRow) + "," + Str(RegRS.Fields("Commodity_Code").Value)
'              mySQL = mySQL + "," + RegRS.Fields("Customer_ID").Value + ",'OT'," + RegRS.Fields("Service_Code").Value + "," + RegRS.Fields("Vessel_ID").Value + "," + RegRS.Fields("Equipment_ID").Value
'              mySQL = mySQL + ",'" + RegRS.Fields("Location_ID").Value + "',to_date('" + Format(Str(RegRS.Fields("Start_Time").Value), "mm/dd/yyyy") + "','mm/dd/yyyy'),to_date('" + Format(Str(RegRS.Fields("End_Time").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')," + Str(RegRS.Fields("Duration").Value)
'              mySQL = mySQL + ",'" + RegRS.Fields("Billing_Flag").Value + "','" + RegRS.Fields("User_ID").Value + "','" + RegRS.Fields("Special_Code").Value + "','" + RegRS.Fields("Exception").Value
'              mySQL = mySQL + "','" + RegRS.Fields("Remark").Value + "','" + RegRS.Fields("Exception_Reason").Value + "'," + RegRS.Fields("Exact_End").Value
              
'              myRec = OraDatabase.ExecuteSQL(mySQL)
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
'To Get the New End Time after converting from REG to OT
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
  If Len(Trim(Str(NewEndHr))) < 2 Then EndHr = Trim(Str(NewEndHr)) + Space(2 - Len(Trim(Str(NewEndHr)))) Else EndHr = Trim(Str(NewEndHr))
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


