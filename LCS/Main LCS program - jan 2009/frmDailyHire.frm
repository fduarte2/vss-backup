VERSION 5.00
Object = "{BDC217C8-ED16-11CD-956C-0000C04E4C0A}#1.1#0"; "TABCTL32.OCX"
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "CRYSTL32.OCX"
Begin VB.Form frmDailyHire 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Daily Hire"
   ClientHeight    =   8325
   ClientLeft      =   1110
   ClientTop       =   1410
   ClientWidth     =   13170
   Icon            =   "frmDailyHire.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   8325
   ScaleWidth      =   13170
   Begin TabDlg.SSTab SSTab1 
      Height          =   7125
      Left            =   0
      TabIndex        =   14
      Top             =   1080
      Width           =   13125
      _ExtentX        =   23151
      _ExtentY        =   12568
      _Version        =   393216
      Tabs            =   2
      Tab             =   1
      TabsPerRow      =   2
      TabHeight       =   520
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      TabCaption(0)   =   "Daily Hire &Entry"
      TabPicture(0)   =   "frmDailyHire.frx":0442
      Tab(0).ControlEnabled=   0   'False
      Tab(0).Control(0)=   "Label8"
      Tab(0).Control(1)=   "Label12"
      Tab(0).Control(2)=   "Label7"
      Tab(0).Control(3)=   "Label2"
      Tab(0).Control(4)=   "Label4"
      Tab(0).Control(5)=   "Label1"
      Tab(0).Control(6)=   "Label10"
      Tab(0).Control(7)=   "SpinButton1"
      Tab(0).Control(8)=   "Label15"
      Tab(0).Control(9)=   "Label16"
      Tab(0).Control(10)=   "crw1"
      Tab(0).Control(11)=   "SSDBCombo2"
      Tab(0).Control(12)=   "SSDBCombo1"
      Tab(0).Control(13)=   "txtFirstName"
      Tab(0).Control(14)=   "lstEmpName"
      Tab(0).Control(15)=   "txtEmpId"
      Tab(0).Control(16)=   "cmdSelectAll"
      Tab(0).Control(17)=   "cmdDeselect"
      Tab(0).Control(18)=   "cmdAdd"
      Tab(0).Control(19)=   "cmdEdit"
      Tab(0).Control(20)=   "Frame1"
      Tab(0).Control(21)=   "optAMPM(1)"
      Tab(0).Control(22)=   "optAMPM(0)"
      Tab(0).Control(23)=   "Text2"
      Tab(0).Control(24)=   "Text1"
      Tab(0).Control(25)=   "SSDateCombo1"
      Tab(0).Control(26)=   "cmdLogin"
      Tab(0).Control(27)=   "cmdEntryExit"
      Tab(0).Control(28)=   "cmdReport"
      Tab(0).Control(29)=   "lstSelected"
      Tab(0).Control(30)=   "cmdAddEmp"
      Tab(0).Control(31)=   "cmdAddAll"
      Tab(0).Control(32)=   "cmdRemove"
      Tab(0).Control(33)=   "cmdRemoveAll"
      Tab(0).ControlCount=   34
      TabCaption(1)   =   "&View / Edit Daily Hire "
      TabPicture(1)   =   "frmDailyHire.frx":045E
      Tab(1).ControlEnabled=   -1  'True
      Tab(1).Control(0)=   "Label14"
      Tab(1).Control(0).Enabled=   0   'False
      Tab(1).Control(1)=   "Label11"
      Tab(1).Control(1).Enabled=   0   'False
      Tab(1).Control(2)=   "Label13"
      Tab(1).Control(2).Enabled=   0   'False
      Tab(1).Control(3)=   "Label9"
      Tab(1).Control(3).Enabled=   0   'False
      Tab(1).Control(4)=   "Label3"
      Tab(1).Control(4).Enabled=   0   'False
      Tab(1).Control(5)=   "cmdExit"
      Tab(1).Control(5).Enabled=   0   'False
      Tab(1).Control(6)=   "SSDBGrid1"
      Tab(1).Control(6).Enabled=   0   'False
      Tab(1).Control(7)=   "txtCommName"
      Tab(1).Control(7).Enabled=   0   'False
      Tab(1).Control(8)=   "txtLocDesc"
      Tab(1).Control(8).Enabled=   0   'False
      Tab(1).Control(9)=   "CmdDeleteAll"
      Tab(1).Control(9).Enabled=   0   'False
      Tab(1).Control(10)=   "txtEmpFNm"
      Tab(1).Control(10).Enabled=   0   'False
      Tab(1).Control(11)=   "txtNoofEmp"
      Tab(1).Control(11).Enabled=   0   'False
      Tab(1).ControlCount=   12
      Begin VB.CommandButton cmdRemoveAll 
         Caption         =   "<< Re&move All"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   -68880
         TabIndex        =   50
         ToolTipText     =   "Hire the Selected Employee(s)"
         Top             =   4800
         Width           =   1695
      End
      Begin VB.CommandButton cmdRemove 
         Caption         =   "<  Rem&ove "
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   -68880
         TabIndex        =   49
         ToolTipText     =   "Hire the Selected Employee(s)"
         Top             =   4245
         Width           =   1695
      End
      Begin VB.CommandButton cmdAddAll 
         Caption         =   "Add A&ll  >>"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   -68880
         TabIndex        =   48
         ToolTipText     =   "Hire the Selected Employee(s)"
         Top             =   3015
         Width           =   1695
      End
      Begin VB.CommandButton cmdAddEmp 
         Caption         =   "&Add  >"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   -68880
         TabIndex        =   47
         ToolTipText     =   "Hire the Selected Employee(s)"
         Top             =   2400
         Width           =   1695
      End
      Begin VB.ListBox lstSelected 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   2940
         Left            =   -67080
         MultiSelect     =   2  'Extended
         Sorted          =   -1  'True
         TabIndex        =   46
         ToolTipText     =   "Select an Employee to Hire"
         Top             =   2400
         Width           =   5055
      End
      Begin VB.CommandButton cmdReport 
         Caption         =   "RE&PORT"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   -65235
         TabIndex        =   45
         ToolTipText     =   "Show Report"
         Top             =   5475
         Width           =   1455
      End
      Begin VB.CommandButton cmdEntryExit 
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
         Height          =   480
         Left            =   -63555
         TabIndex        =   44
         ToolTipText     =   "Return Back"
         Top             =   5475
         Width           =   1455
      End
      Begin VB.CommandButton cmdLogin 
         Caption         =   "&HIRE"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   -66915
         TabIndex        =   43
         ToolTipText     =   "Hire the Selected Employee(s)"
         Top             =   5475
         Width           =   1455
      End
      Begin VB.TextBox txtNoofEmp 
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
         Left            =   8640
         TabIndex        =   41
         Top             =   4320
         Width           =   4200
      End
      Begin VB.TextBox txtEmpFNm 
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
         Left            =   8640
         TabIndex        =   39
         Top             =   1320
         Width           =   4200
      End
      Begin VB.CommandButton CmdDeleteAll 
         Caption         =   "DELE&TE ALL"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   8760
         TabIndex        =   38
         ToolTipText     =   "Delete All Hired Employees Data"
         Top             =   6480
         Visible         =   0   'False
         Width           =   2055
      End
      Begin VB.TextBox txtLocDesc 
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
         Left            =   8640
         TabIndex        =   37
         Top             =   2800
         Width           =   4200
      End
      Begin VB.TextBox txtCommName 
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
         Left            =   7080
         TabIndex        =   36
         Top             =   7635
         Visible         =   0   'False
         Width           =   4200
      End
      Begin SSCalendarWidgets_A.SSDateCombo SSDateCombo1 
         Height          =   375
         Left            =   -65715
         TabIndex        =   32
         Top             =   555
         Width           =   2775
         _Version        =   65543
         _ExtentX        =   4895
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
      Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
         Height          =   5895
         Left            =   150
         TabIndex        =   31
         ToolTipText     =   "Click on Employee to View the Details or Delete"
         Top             =   480
         Width           =   6855
         _Version        =   196616
         DataMode        =   2
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         FieldSeparator  =   "!"
         AllowDelete     =   -1  'True
         AllowRowSizing  =   0   'False
         AllowGroupSizing=   0   'False
         AllowColumnSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowColumnMoving=   0
         AllowGroupSwapping=   0   'False
         AllowColumnSwapping=   0
         AllowGroupShrinking=   0   'False
         AllowColumnShrinking=   0   'False
         AllowDragDrop   =   0   'False
         MaxSelectedRows =   1
         BackColorOdd    =   16777152
         RowHeight       =   503
         Columns(0).Width=   3200
         Columns(0).Caption=   "Column0"
         Columns(0).Name =   "Column0"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         _ExtentX        =   12091
         _ExtentY        =   10398
         _StockProps     =   79
         Caption         =   "Daily Hire Maintanence"
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
      Begin VB.TextBox Text1 
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
         Left            =   -65715
         TabIndex        =   5
         Top             =   1800
         Width           =   375
      End
      Begin VB.TextBox Text2 
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
         Left            =   -65355
         TabIndex        =   6
         Top             =   1800
         Width           =   375
      End
      Begin VB.OptionButton optAMPM 
         Caption         =   "AM"
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
         Index           =   0
         Left            =   -64545
         TabIndex        =   7
         Top             =   1920
         Width           =   800
      End
      Begin VB.OptionButton optAMPM 
         Caption         =   "PM"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Index           =   1
         Left            =   -63690
         TabIndex        =   8
         Top             =   1905
         Width           =   800
      End
      Begin VB.Frame Frame1 
         Caption         =   "Sort By"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   855
         Left            =   -74760
         TabIndex        =   23
         Top             =   360
         Width           =   5775
         Begin VB.OptionButton optSortBy 
            Caption         =   "Senio&rity"
            BeginProperty Font 
               Name            =   "MS Sans Serif"
               Size            =   12
               Charset         =   0
               Weight          =   700
               Underline       =   0   'False
               Italic          =   0   'False
               Strikethrough   =   0   'False
            EndProperty
            Height          =   300
            Index           =   3
            Left            =   4200
            TabIndex        =   29
            ToolTipText     =   "Sort by Seniority"
            Top             =   360
            Width           =   1455
         End
         Begin VB.OptionButton optSortBy 
            Caption         =   "&Type"
            BeginProperty Font 
               Name            =   "MS Sans Serif"
               Size            =   12
               Charset         =   0
               Weight          =   700
               Underline       =   0   'False
               Italic          =   0   'False
               Strikethrough   =   0   'False
            EndProperty
            Height          =   300
            Index           =   2
            Left            =   2760
            TabIndex        =   26
            ToolTipText     =   "Sort by Employee Type ID"
            Top             =   360
            Width           =   1335
         End
         Begin VB.OptionButton optSortBy 
            Caption         =   "Emp&ID"
            BeginProperty Font 
               Name            =   "MS Sans Serif"
               Size            =   12
               Charset         =   0
               Weight          =   700
               Underline       =   0   'False
               Italic          =   0   'False
               Strikethrough   =   0   'False
            EndProperty
            Height          =   300
            Index           =   1
            Left            =   1320
            TabIndex        =   25
            ToolTipText     =   "Sort by Employee ID"
            Top             =   360
            Width           =   1335
         End
         Begin VB.OptionButton optSortBy 
            Caption         =   "&Name"
            BeginProperty Font 
               Name            =   "MS Sans Serif"
               Size            =   12
               Charset         =   0
               Weight          =   700
               Underline       =   0   'False
               Italic          =   0   'False
               Strikethrough   =   0   'False
            EndProperty
            Height          =   300
            Index           =   0
            Left            =   120
            TabIndex        =   24
            ToolTipText     =   "Sort by Name"
            Top             =   360
            Value           =   -1  'True
            Width           =   1695
         End
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
         Height          =   480
         Left            =   11880
         TabIndex        =   21
         ToolTipText     =   "Return Back"
         Top             =   6480
         Width           =   1095
      End
      Begin VB.CommandButton cmdEdit 
         Caption         =   "EDIT"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   -71640
         TabIndex        =   12
         ToolTipText     =   "Edit Employee Data"
         Top             =   7200
         Visible         =   0   'False
         Width           =   2655
      End
      Begin VB.CommandButton cmdAdd 
         Caption         =   "ADD"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   -74760
         TabIndex        =   11
         ToolTipText     =   "Add Employee Data"
         Top             =   7200
         Visible         =   0   'False
         Width           =   2775
      End
      Begin VB.CommandButton cmdDeselect 
         Caption         =   "&DESELECT ALL"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   -74760
         TabIndex        =   9
         ToolTipText     =   "Deselect the Selected Employees"
         Top             =   6540
         Width           =   2775
      End
      Begin VB.CommandButton cmdSelectAll 
         Caption         =   "&SELECT ALL"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   480
         Left            =   -71640
         TabIndex        =   10
         ToolTipText     =   "Select all Employees"
         Top             =   6540
         Width           =   2655
      End
      Begin VB.TextBox txtEmpId 
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
         Left            =   -66555
         TabIndex        =   3
         Top             =   7920
         Visible         =   0   'False
         Width           =   2775
      End
      Begin VB.ListBox lstEmpName 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   4620
         Left            =   -74760
         MultiSelect     =   2  'Extended
         Sorted          =   -1  'True
         TabIndex        =   0
         ToolTipText     =   "Select an Employee to Hire"
         Top             =   1620
         Width           =   5775
      End
      Begin VB.TextBox txtFirstName 
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
         Left            =   -66555
         TabIndex        =   4
         Top             =   8595
         Visible         =   0   'False
         Width           =   2775
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCombo1 
         Height          =   375
         Left            =   -65715
         TabIndex        =   1
         ToolTipText     =   "Select Start Location Category ID"
         Top             =   1200
         Width           =   2775
         DataFieldList   =   "Column 0"
         MaxDropDownItems=   16
         AllowInput      =   0   'False
         AllowNull       =   0   'False
         _Version        =   196616
         DataMode        =   2
         Cols            =   3
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
         Columns(0).Width=   3200
         Columns(0).DataType=   8
         Columns(0).FieldLen=   4096
         _ExtentX        =   4895
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
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCombo2 
         Height          =   375
         Left            =   -66555
         TabIndex        =   2
         ToolTipText     =   "Select Start Commodity Code"
         Top             =   7245
         Visible         =   0   'False
         Width           =   2775
         DataFieldList   =   "Column 0"
         ListAutoValidate=   0   'False
         MaxDropDownItems=   16
         AllowInput      =   0   'False
         AllowNull       =   0   'False
         _Version        =   196616
         DataMode        =   2
         Cols            =   3
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
         Columns(0).Width=   3200
         Columns(0).DataType=   8
         Columns(0).FieldLen=   4096
         _ExtentX        =   4895
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
      End
      Begin Crystal.CrystalReport crw1 
         Left            =   -69000
         Top             =   6840
         _ExtentX        =   741
         _ExtentY        =   741
         _Version        =   348160
         WindowState     =   2
         PrintFileLinesPerPage=   60
      End
      Begin VB.Label Label3 
         Caption         =   "Number of Employees"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   8640
         TabIndex        =   42
         Top             =   3840
         Width           =   3075
      End
      Begin VB.Label Label9 
         Caption         =   "Employee Name"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   8640
         TabIndex        =   40
         Top             =   840
         Width           =   2235
      End
      Begin VB.Label Label13 
         Caption         =   "Commodity Name"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   7080
         TabIndex        =   35
         Top             =   7155
         Visible         =   0   'False
         Width           =   2685
      End
      Begin VB.Label Label11 
         Caption         =   "Category Name"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   8640
         TabIndex        =   34
         Top             =   2325
         Width           =   3075
      End
      Begin VB.Label Label16 
         Alignment       =   1  'Right Justify
         Caption         =   "Start Co&mm CD"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   -68760
         TabIndex        =   33
         Top             =   7200
         Visible         =   0   'False
         Width           =   2130
      End
      Begin VB.Label Label15 
         Caption         =   "Total Employees Hired "
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   -68640
         TabIndex        =   30
         Top             =   6600
         Width           =   4935
      End
      Begin VB.Label Label14 
         Caption         =   "Total Employees Hired "
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   120
         TabIndex        =   28
         Top             =   6600
         Width           =   4695
      End
      Begin MSForms.SpinButton SpinButton1 
         Height          =   420
         Left            =   -64995
         TabIndex        =   27
         Top             =   1800
         Width           =   255
         Size            =   "450;741"
      End
      Begin VB.Label Label10 
         Caption         =   "No Employee is Selected for Hiring"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   -68640
         TabIndex        =   22
         Top             =   6075
         Width           =   4935
      End
      Begin VB.Label Label1 
         Caption         =   "Employee Information"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   -74760
         TabIndex        =   20
         Top             =   1275
         Width           =   3135
      End
      Begin VB.Label Label4 
         Alignment       =   1  'Right Justify
         Caption         =   "Employee ID"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   -68475
         TabIndex        =   19
         Top             =   7995
         Visible         =   0   'False
         Width           =   1770
      End
      Begin VB.Label Label2 
         Alignment       =   1  'Right Justify
         Caption         =   "Start &Category"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   -68115
         TabIndex        =   18
         Top             =   1155
         Width           =   2250
      End
      Begin VB.Label Label7 
         Alignment       =   1  'Right Justify
         Caption         =   "Hire Date "
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   -67635
         TabIndex        =   17
         Top             =   555
         Width           =   1770
      End
      Begin VB.Label Label12 
         Alignment       =   1  'Right Justify
         Caption         =   "Time In"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   -67635
         TabIndex        =   16
         Top             =   1755
         Width           =   1770
      End
      Begin VB.Label Label8 
         Alignment       =   1  'Right Justify
         Caption         =   "Name"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   -68475
         TabIndex        =   15
         Top             =   8595
         Visible         =   0   'False
         Width           =   1770
      End
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
      Picture         =   "frmDailyHire.frx":047A
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   13080
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
      Left            =   960
      TabIndex        =   13
      Top             =   0
      Width           =   12120
   End
End
Attribute VB_Name = "frmDailyHire"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim EmpRS As Object, HireRS As Object
'Dim arrFName() As String
Dim TotalRec As Integer, blnAddMode As Boolean, PrevCate As String, PrevTime As String

'****************************************
'To Add ALL Records from the Employee List to the Selected List
'****************************************
Private Sub cmdAddAll_Click()
  Dim indxCtr As Integer, myEmpRS As Object, mySQL As String, myUpdateSQL As String
  Dim EmpIDStart As Integer, EmpIDEnd As Integer, myEmpID As String, ListVal As String
  frmDailyHire.MousePointer = vbHourglass
  For indxCtr = 0 To lstEmpName.ListCount - 1
    ListVal = lstEmpName.List(indxCtr)
    'Add to List lstSelected
    EmpIDStart = InStr(1, ListVal, "(")
    EmpIDEnd = InStr(1, ListVal, ")")
    myEmpID = Mid(ListVal, EmpIDStart + 1, EmpIDEnd - EmpIDStart - 1)
    mySQL = "Select employee_name || ' (' || employee_id || ') ' || employee_type_id || ' ' || employee_sub_type_id || ' ' || seniority Employee from Employee where Upper(Employee_ID) = '" + UCase(myEmpID) + "'"
    Set myEmpRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
    lstSelected.AddItem myEmpRS.Fields("Employee").Value
    
    'Add to Temp Table
    OraSession.BeginTrans
    myUpdateSQL = "Insert into Daily_Hire_Temp Values ('" + myEmpID + "')"
    OraDatabase.ExecuteSQL myUpdateSQL
    If OraDatabase.LastServerErr = 0 Then
      OraSession.CommitTrans
    Else
      OraSession.Rollback
    End If
  Next
  'Remove from List lstEmpName
  lstEmpName.Clear
  frmDailyHire.MousePointer = vbDefault
  cmdAddEmp.Enabled = False
  cmdAddAll.Enabled = False
  cmdRemove.Enabled = True
  cmdRemoveAll.Enabled = True
  myEmpRS.Close
  Set myEmpRS = Nothing
  If lstSelected.ListCount = 0 Then
    Label10.Caption = "No Employee is Selected for Hiring"
  Else
    Label10.Caption = Str(lstSelected.ListCount) + " Employee(s) Selected for Hiring"
  End If
End Sub

'****************************************
'To Add Selected Records from the Employee List to the Selected List
'****************************************
Private Sub cmdAddEmp_Click()
  Dim mySQL As String, myEmpRS As Object, myEmpID As String, myUpdateSQL As String
  Dim EmpIDStart As Integer, EmpIDEnd As Integer, indxCtr As Integer, ListVal As String
  If lstEmpName.SelCount = 0 Then
    MsgBox "Please Select an Employee to Add in the list for Hire", vbInformation, "Select Employee"
    Exit Sub
  Else
    For indxCtr = 0 To lstEmpName.ListCount - 1
    ListVal = lstEmpName.List(indxCtr)
      'Add to List lstSelected
      If lstEmpName.Selected(indxCtr) = True Then
        EmpIDStart = InStr(1, ListVal, "(")
        EmpIDEnd = InStr(1, ListVal, ")")
        myEmpID = Mid(ListVal, EmpIDStart + 1, EmpIDEnd - EmpIDStart - 1)
        mySQL = "Select employee_name || ' (' || employee_id || ') ' || employee_type_id || ' ' || employee_sub_type_id || ' ' || seniority Employee from Employee where Upper(Employee_ID) = '" + UCase(myEmpID) + "'"
        Set myEmpRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
        lstSelected.AddItem myEmpRS.Fields("Employee").Value
        
        'Add to Temp Table
        OraSession.BeginTrans
        myUpdateSQL = "Insert into Daily_Hire_Temp Values ('" + myEmpID + "')"
        OraDatabase.ExecuteSQL myUpdateSQL
        If OraDatabase.LastServerErr = 0 Then
          OraSession.CommitTrans
        Else
          OraSession.Rollback
        End If
      End If
    Next
    'Remove from List lstEmpName
    'For Bob Barker and Bill Krupa only REGR Employees
    'If UserID = "E407297" Or UserID = "E000457" Then
    '  If optSortBy(0).Value = True Then
    '    Call OpenRS("RegName")
    '  ElseIf optSortBy(1).Value = True Then
    '   Call OpenRS("RegEmpID")
    '  ElseIf optSortBy(2).Value = True Then
    '    Call OpenRS("RegTypeID")
    '  ElseIf optSortBy(3).Value = True Then
    '    Call OpenRS("RegSeniority")
    '  End If
    'Else
      If optSortBy(0).Value = True Then
        Call OpenRS("Name", HireRole)
      ElseIf optSortBy(1).Value = True Then
        Call OpenRS("EmpID", HireRole)
      ElseIf optSortBy(2).Value = True Then
        Call OpenRS("TypeID", HireRole)
      ElseIf optSortBy(3).Value = True Then
        Call OpenRS("Seniority", HireRole)
      End If
    'End If
    lstEmpName.Clear
    PopulateEmpName
    
    cmdRemove.Enabled = True
    cmdRemoveAll.Enabled = True
    If lstEmpName.ListCount = 0 Then
      cmdAddEmp.Enabled = False
      cmdAddAll.Enabled = False
    Else
      cmdAddEmp.Enabled = True
      cmdAddAll.Enabled = True
    End If
    myEmpRS.Close
    Set myEmpRS = Nothing
    If lstSelected.ListCount = 0 Then
      Label10.Caption = "No Employee is Selected for Hiring"
    Else
      Label10.Caption = Str(lstSelected.ListCount) + " Employee(s) Selected for Hiring"
    End If
  End If
End Sub

'****************************************
'To Delete ALL Records from the Daily Hire
'****************************************
Private Sub CmdDeleteAll_Click()
  Dim DelConfirm As Integer, mySQL As String
  DelConfirm = MsgBox("Are You Sure to Delete All the Records?", vbQuestion + vbYesNo, "Delete Confirm")
  If DelConfirm = vbYes Then
    OraSession.BeginTrans     'Begin the Transaction
    StoreInTextFile ("")      'To Store Hourly detail (if any) in Text File
    
    'Continue with deleting Records from Hourly Detail
    mySQL = "Delete from Hourly_Detail where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
    OraDatabase.ExecuteSQL mySQL
    
    'Now Delete Records from Daily Hire also
    mySQL = "Delete from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
    OraDatabase.ExecuteSQL mySQL
    
    'Commit or Rollback the Transaction
    If OraDatabase.LastServerErr = 0 Then
      OraSession.CommitTrans
      SSDBGrid1.RemoveAll
      txtEmpFNm = ""
      txtLocDesc = ""
      'txtCommName = ""
      txtNoofEmp = ""
      CmdDeleteAll.Enabled = False
    Else
      OraSession.Rollback
    End If
  End If
End Sub

'****************************************
'To Remove Selected Records from the Selected List and Place in Employee List
'****************************************
Private Sub cmdRemove_Click()
  Dim indxCtr As Integer, EmpIDStart As Integer, EmpIDEnd As Integer, ListVal As String
  Dim mySQL As String, myEmpID As String, myEmpRS As Object, myUpdateSQL As String
  If lstSelected.SelCount = 0 Then
    MsgBox "Please Select an Employee to Remove from the list", vbInformation, "Select Employee"
    frmDailyHire.MousePointer = vbDefault
    Exit Sub
  Else
    For indxCtr = 0 To lstSelected.ListCount - 1
      ListVal = lstSelected.List(indxCtr)
      'Add to List lstEmpName
      If lstSelected.Selected(indxCtr) = True Then
        EmpIDStart = InStr(1, ListVal, "(")
        EmpIDEnd = InStr(1, ListVal, ")")
        myEmpID = Mid(ListVal, EmpIDStart + 1, EmpIDEnd - EmpIDStart - 1)
        mySQL = "Select employee_name || ' (' || employee_id || ') ' || employee_type_id || ' ' || employee_sub_type_id || ' ' || seniority Employee from Employee where Upper(Employee_ID) = '" + UCase(myEmpID) + "'"
        Set myEmpRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
        lstEmpName.AddItem myEmpRS.Fields("Employee").Value
        
        'Remove From Temp Table
        OraSession.BeginTrans
        myUpdateSQL = "Delete From Daily_Hire_Temp Where Upper(Employee_ID) = '" + UCase(myEmpID) + "'"
        OraDatabase.ExecuteSQL myUpdateSQL
        If OraDatabase.LastServerErr = 0 Then
          OraSession.CommitTrans
        Else
          OraSession.Rollback
        End If
      End If
    Next
    lstEmpName.Refresh
    myEmpRS.Close
    Set myEmpRS = Nothing
    
    'Remove from List lstSelected
    lstSelected.Clear
    mySQL = "Select employee_name || ' (' || employee_id || ') ' || employee_type_id || ' ' || employee_sub_type_id || ' ' || seniority Employee from Employee where Upper(Employee_ID) IN (Select Upper(Employee_ID) from Daily_Hire_Temp)"
    Set myEmpRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
    If myEmpRS.EOF And myEmpRS.BOF Then
      'Do Nothing
    Else
      myEmpRS.MoveFirst
      Do While Not myEmpRS.EOF
        lstSelected.AddItem myEmpRS.Fields("Employee").Value
        myEmpRS.MoveNext
      Loop
      lstSelected.Refresh
    End If
    If lstSelected.ListCount = 0 Then
      Label10.Caption = "No Employee is Selected for Hiring"
    Else
      Label10.Caption = Str(lstSelected.ListCount) + " Employee(s) Selected for Hiring"
    End If

  End If
  If lstSelected.ListCount = 0 Then
    cmdRemove.Enabled = False
    cmdRemoveAll.Enabled = False
  Else
    cmdRemove.Enabled = True
    cmdRemoveAll.Enabled = True
  End If
  cmdAddEmp.Enabled = True
  cmdAddAll.Enabled = True
  If lstEmpName.SelCount >= 1 Then cmdDeselect_Click
End Sub

'****************************************
'To Remove ALL Records from the Selected List and to place in Employee List
'****************************************
Private Sub cmdRemoveAll_Click()
  Dim indxCtr As Integer, myEmpRS As Object, mySQL As String, myUpdateSQL As String
  Dim EmpIDStart As Integer, EmpIDEnd As Integer, myEmpID As String, myTempEmpRS As Object
  frmDailyHire.MousePointer = vbHourglass
  mySQL = "Select Employee_ID from Daily_Hire_Temp"
  Set myTempEmpRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If myTempEmpRS.EOF And myTempEmpRS.BOF Then
    'Do Nothing
    Exit Sub
  Else
    myTempEmpRS.MoveFirst
    Do While Not myTempEmpRS.EOF
      mySQL = "Select employee_name || ' (' || employee_id || ') ' || employee_type_id || ' ' || employee_sub_type_id || ' ' || seniority Employee from Employee where Upper(Employee_ID) ='" + myTempEmpRS.Fields("Employee_ID").Value + "'"
      Set myEmpRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
      lstEmpName.AddItem myEmpRS.Fields("Employee").Value
      myTempEmpRS.MoveNext
    Loop
  End If
  'Remove All from Temp Table
  OraSession.BeginTrans
  myUpdateSQL = "Delete from Daily_Hire_Temp"
  OraDatabase.ExecuteSQL myUpdateSQL
  If OraDatabase.LastServerErr = 0 Then
    OraSession.CommitTrans
  Else
    OraSession.Rollback
  End If
  
  'Remove from List lstSelected
  lstSelected.Clear
  frmDailyHire.MousePointer = vbDefault
  cmdAddEmp.Enabled = True
  cmdAddAll.Enabled = True
  cmdRemove.Enabled = False
  cmdRemoveAll.Enabled = False
  myEmpRS.Close
  Set myEmpRS = Nothing
  myTempEmpRS.Close
  Set myTempEmpRS = Nothing
  If lstSelected.ListCount = 0 Then
    Label10.Caption = "No Employee is Selected for Hiring"
  Else
    Label10.Caption = Str(lstSelected.ListCount) + " Employee(s) Selected for Hiring"
  End If
  If lstEmpName.SelCount >= 1 Then cmdDeselect_Click
End Sub

'****************************************
'To Select all Employees from the List
'****************************************
Private Sub cmdSelectAll_Click()
  Dim indxCtr As Integer
  For indxCtr = 0 To lstEmpName.ListCount - 1
    lstEmpName.Selected(indxCtr) = True
  Next
End Sub

'****************************************
'To Deselect all Employees from the List
'****************************************
Private Sub cmdDeselect_Click()
  Dim indxCtr As Integer
  For indxCtr = 0 To lstEmpName.ListCount - 1
    lstEmpName.Selected(indxCtr) = False
  Next
End Sub

'****************************************
'To Store details in Daily Hire Table
'****************************************
Private Sub cmdLogin_Click()
  Dim indxCtr As Integer, arrCnt As Integer, myUpdateSQL As String, myrec As Integer
  Dim TotalCount As Integer, SelectCtr As Integer, ListVal As String
  Dim myTime1 As String, myTime2 As String
  Dim EmpIDStart As Integer, EmpIDEnd As Integer, myEmpID As String
  TotalCount = lstEmpName.ListCount - 1
  frmDailyHire.MousePointer = vbHourglass
  If optAMPM(0).Value = True Then
    myTime1 = "AM"
  ElseIf optAMPM(1).Value = True Then
    myTime1 = "PM"
  End If
  myTime2 = Trim(SSDateCombo1.Text) + " " + Trim(Text1) + ":" + Trim(Text2) + Trim(myTime1)
  
  'No employee is selected - so report message
  If lstSelected.ListCount = 0 Then
    MsgBox "Please Select an Employee and Add to the List for Hire", vbInformation, "Select Employee"
    frmDailyHire.MousePointer = vbDefault
    Exit Sub
  Else  'One or More Employees are selected
    For indxCtr = 0 To lstSelected.ListCount - 1
      ListVal = lstSelected.List(indxCtr)
      OraSession.BeginTrans        'Begin the Transaction
      HireRS.AddNew
      HireRS.Fields("Hire_Date").Value = SSDateCombo1.Text
      HireRS.Fields("User_ID").Value = UserID
      EmpIDStart = InStr(1, ListVal, "(")
      EmpIDEnd = InStr(1, ListVal, ")")
      myEmpID = Mid(ListVal, EmpIDStart + 1, EmpIDEnd - EmpIDStart - 1)
      HireRS.Fields("Employee_id").Value = myEmpID
      If Trim(SSDBCombo1.Text) = vbNullString Then
        MsgBox "Please Select Start Category", vbInformation, "Hiring Unsuccessful"
        SSDBCombo1.SetFocus
        frmDailyHire.MousePointer = vbDefault
        OraSession.Rollback
        Exit Sub
      End If
      HireRS.Fields("Location_ID").Value = SSDBCombo1.Text
      HireRS.Fields("Commodity_Code").Value = 0
      HireRS.Fields("Time_in").Value = myTime2
      HireRS.Update
      'Commit or Rollback the Transaction
      If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        'Remove From Temp Table
        OraSession.BeginTrans
        myUpdateSQL = "Delete From Daily_Hire_Temp Where Upper(Employee_ID) = '" + UCase(myEmpID) + "'"
        myrec = OraDatabase.ExecuteSQL(myUpdateSQL)
        If OraDatabase.LastServerErr = 0 Then
          OraSession.CommitTrans
        Else
          OraSession.Rollback
        End If
      Else
        OraSession.Rollback
      End If
    Next
  End If

  frmDailyHire.MousePointer = vbDefault
  MsgBox Str(lstSelected.ListCount) + " Employee(s) hired successfully ", vbInformation, "Hiring Process Successful!"
  
  'After Login of Employee, update the Label and remove Hired Employee(s) from the List
  Label15.Caption = "Total Employees Hired " + Str(HireRS.RecordCount)
  Label10.Caption = "No Employee is Selected for Hiring"
  lstSelected.Clear
End Sub

'****************************************
'To Show the Report - Request for Labor
'****************************************
Private Sub cmdReport_Click()
  Dim strSelect As String

  'MsgBox "Sorry! This option is under Construction", vbInformation, "Under Construction"
  crw1.LogOnServer "PDSORA7", "LCS", "", "LABOR", "LABOR"   'ODBCDriver, ServerName, Database, UserName, Password
  crw1.ReportFileName = App.Path & "\Head.rpt"
  'crw1.Connect = "DSN = LCS;UID = LABOR;PWD = LABOR"
  
  crw1.DiscardSavedData = True
  'crw1.SelectionFormula = "{DAILY_HIRE_LIST.HIRE_DATE} =" + "date(" + Str(Year(SSDateCombo1.Text)) + "," + Str(Month(SSDateCombo1.Text)) + "," + Str(Day(SSDateCombo1.Text)) + ") and {DAILY_HIRE_LIST.USER_ID} = '" & UserID & "'"
  crw1.SelectionFormula = "{DAILY_HIRE_LIST.HIRE_DATE} =" + "date(" + Str(Year(SSDateCombo1.Text)) + "," + Str(Month(SSDateCombo1.Text)) + "," + Str(day(SSDateCombo1.Text)) + ")"
  
  crw1.Formulas(0) = "DtHead = '" + SSDateCombo1.Text + "'"
  crw1.Action = 1
  'MsgBox crw1.LastErrorString
End Sub

'****************************************
'To Close the current Form
'****************************************
Private Sub cmdEntryExit_Click()
  'Delete Temp Table Entries
  Dim myUpdateSQL As String
  OraSession.BeginTrans
  myUpdateSQL = "Delete from Daily_Hire_Temp "
  OraDatabase.ExecuteSQL myUpdateSQL
  If OraDatabase.LastServerErr = 0 Then
    OraSession.CommitTrans
  Else
    OraSession.Rollback
  End If
  Unload Me
End Sub
Private Sub cmdExit_Click()
  'Delete Temp Table Entries
  Dim myUpdateSQL As String
  OraSession.BeginTrans
  myUpdateSQL = "Delete from Daily_Hire_Temp "
  OraDatabase.ExecuteSQL myUpdateSQL
  If OraDatabase.LastServerErr = 0 Then
    OraSession.CommitTrans
  Else
    OraSession.Rollback
  End If
  Unload Me
End Sub

Private Sub Form_Load()
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
  'StoreinArray                            'To Store the Employee Details in Array
  'For Bob Barker and Bill Krupa - Only REG Employees
  'If UserID = "E407297" Or UserID = "E000457" Then
  '  Call OpenRS("RegName")
  'Else
    Call OpenRS("Name", HireRole)                      'Open Recordset based on Sort option - Initially Last Name
  'End If
  PopulateEmpName                         'To populate the List Box with Employee Names
  SetGrid
  Call PopulateDailyHire("Location_ID, Commodity_Code")   'To Populate the Grid with Entries from Daily Hire
  ShowLocation                            'Fill the Combo Box with Location ID and Description
  'ShowCommodity                           'Fill the Combo Box with Commodity Code and Name
  ShowTime                                'To display Time in the Time In Text Box
  Text1 = "8"                             'Default 8:00 AM
  Text2 = "00"
  optAMPM(0).Value = True
  cmdRemove.Enabled = False
  cmdRemoveAll.Enabled = False
  SSTab1.Tab = 0
  Label15.Caption = "Total Employees Hired " + Str(HireRS.RecordCount)
  
  'Delete Temp Table Entries - in case of System Crash before normal exit
  Dim myUpdateSQL As String
  OraSession.BeginTrans
  myUpdateSQL = "Delete from Daily_Hire_Temp "
  OraDatabase.ExecuteSQL myUpdateSQL
  If OraDatabase.LastServerErr = 0 Then
    OraSession.CommitTrans
  Else
    OraSession.Rollback
  End If

End Sub

'****************************************
'To Unload Current Form and Open Previous Form
'****************************************
Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  frmHiring.Show
End Sub

'****************************************
'To Close Recordsets opened
'****************************************
Private Sub Form_Terminate()
  Set EmpRS = Nothing
  Set HireRS = Nothing
End Sub

'****************************************
'To Fill the Combo Box with Location ID and Description
'****************************************
Private Sub ShowLocation()
  Dim i As Integer
  SSDBCombo1.Columns.RemoveAll
  For i = 0 To 1
    SSDBCombo1.Columns.add i
  Next
  Dim LocRS As Object
  SSDBCombo1.Columns(0).Caption = "Category CD"
  SSDBCombo1.Columns(1).Caption = "Category Name"
  
  Set LocRS = OraDatabase.DBCreateDynaset("Select * from Location_Category Order By Location_ID", 0&)
  If LocRS.EOF And LocRS.BOF Then
    Exit Sub
  Else
    LocRS.MoveFirst
    Do While Not LocRS.EOF
      SSDBCombo1.AddItem LocRS.Fields("Location_ID").Value & "!" & LocRS.Fields("Location_Description").Value
      LocRS.MoveNext
    Loop
  End If
  LocRS.Close
  Set LocRS = Nothing
End Sub

'****************************************
'To Fill the Combo Box with Commodity Code and Name
'****************************************
Private Sub ShowCommodity()
  Dim i As Integer
  SSDBCombo2.Columns.RemoveAll
  For i = 0 To 1
    SSDBCombo2.Columns.add i
  Next
  Dim CommRS As Object
  SSDBCombo2.Columns(0).Caption = "Commodity Code"
  SSDBCombo2.Columns(1).Caption = "Commodity Name"
  
  Set CommRS = OraDatabase.DBCreateDynaset("Select * from Commodity Order by Commodity_Code", 0&)
  If CommRS.EOF And CommRS.BOF Then
    Exit Sub
  Else
    CommRS.MoveFirst
    Do While Not CommRS.EOF
      SSDBCombo2.AddItem CommRS.Fields("Commodity_Code").Value & "!" & CommRS.Fields("Commodity_Name").Value
      CommRS.MoveNext
    Loop
  End If
  CommRS.Close
  Set CommRS = Nothing
End Sub

'****************************************
'To display Time in the Time In Text Box
'****************************************
Private Sub ShowTime()
  Dim myTime As Integer
  'SpinButton1.Min = -15   'Duration in 1/2 an Hour
  SpinButton1.Min = -30
  SpinButton1.Max = 60
  'SpinButton1.SmallChange = 15  'Duration in 1/2 an Hour
  SpinButton1.SmallChange = 30
  Text1 = Format(time, "h")
  If Int(Text1) <= 11 Then
    optAMPM(0).Value = True
  ElseIf Int(Text1) > 11 Then
    optAMPM(1).Value = True
    Text1 = Int(Text1) - 12
  End If
  
  Text2 = Format(time, "nn")
  myTime = Int(Text2)
  'Duration in 1/2 an Hour << Start
  'If myTime <= 15 Then
  '  Text2 = "15"
    
  'ElseIf myTime <= 30 And myTime > 15 Then
  '  Text2 = "30"
  'ElseIf myTime <= 45 And myTime > 30 Then
  '  Text2 = "45"
  'Duration in 1/2 an Hour >> End
  If myTime <= 30 Then
    Text2 = "30"
  Else
    Text2 = "00"
    Text1 = Int(Text1) + 1
  End If
  SpinButton1.Value = Text2
End Sub

'****************************************
'To Set the Columns and the Width for the Grid
'****************************************
Private Sub SetGrid()
  Dim i As Integer
  SSDBGrid1.Columns.RemoveAll
  For i = 0 To 2
    SSDBGrid1.Columns.add i
  Next
  SSDBGrid1.Columns(0).Caption = "Emp ID"
  SSDBGrid1.Columns(1).Caption = "Category"
  'SSDBGrid1.Columns(2).Caption = "Commodity"
  SSDBGrid1.Columns(2).Caption = "Time IN"
  SSDBGrid1.Columns(0).Width = 1590.236
  SSDBGrid1.Columns(1).Width = 3225.26
  'SSDBGrid1.Columns(2).Width = 1604.976
  SSDBGrid1.Columns(2).Width = 1484.787
  SSDBGrid1.Columns(0).Locked = True
  SSDBGrid1.Columns(0).ForeColor = RGB(255, 0, 0)
End Sub

'****************************************
'To Populate the Grid with Entries from Daily Hire
'****************************************
Private Sub PopulateDailyHire(OrdFld As String)
  Dim mySQL As String
  SSDBGrid1.RemoveAll
 ' 'For Bob Barker and Bill Krupa - Only REG Employees
 ' If UserID = "E407297" Or UserID = "E000457" Then
 '   mySQL = "Select a.* from Daily_Hire_List a, Employee b where a.Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and a.Employee_ID = b.Employee_ID and b.Employee_Type_ID = 'REGR' order by " + OrdFld
 ' Else
    mySQL = "Select * from Daily_Hire_List where Hire_Date=to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') order by " + OrdFld
 ' End If
  Set HireRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If HireRS.BOF And HireRS.EOF Then
    'No records to display
    CmdDeleteAll.Enabled = False
    Exit Sub
  Else
    HireRS.MoveLast
    TotalRec = HireRS.RecordCount
    HireRS.MoveFirst
    Do While Not HireRS.EOF
'      SSDBGrid1.AddItem HireRS.Fields("Employee_ID").Value & "!" & HireRS.Fields("Location_ID").Value & "!" & HireRS.Fields("Commodity_Code").Value & "!" & Format(HireRS.Fields("Time_In").Value, "hh:nnAM/PM")
      SSDBGrid1.AddItem HireRS.Fields("Employee_ID").Value & "!" & HireRS.Fields("Location_ID").Value & "!" & Format(HireRS.Fields("Time_In").Value, "hh:nnAM/PM")
      HireRS.MoveNext
    Loop
    CmdDeleteAll.Enabled = True
  End If
End Sub

'****************************************
'To Open the Recordset based on Sort By Option
'****************************************
Private Sub OpenRS(OrderClause As String, HireRole As String)
  Dim mySQL As String
  Dim strSelect As String
  Dim strOrder As String
  Dim strFromWhere As String
  Dim isUnion As String
   
  If Trim(OrderClause) = "Name" Then
    strSelect = " Select employee_id, employee_name || ' (' || employee_id || ') ' || employee_type_id || ' ' || employee_sub_type_id || ' ' || seniority Employee, employee_name "
    strOrder = " order by Employee_Name "
  ElseIf Trim(OrderClause) = "EmpID" Then
    strSelect = " Select employee_id, '(' || employee_id || ') ' || employee_name || ' ' || employee_type_id || ' ' || employee_sub_type_id || ' ' || seniority Employee "
    strOrder = " order by Employee_ID "
  ElseIf Trim(OrderClause) = "TypeID" Then
    strSelect = " Select employee_id, employee_type_id || ' ' || employee_name || ' (' || employee_id || ') ' || employee_sub_type_id || ' ' || seniority Employee, Employee_Type_Id, Employee_Sub_Type_Id, Employee_Name "
    strOrder = " order by Employee_Type_Id, Employee_Sub_Type_Id, Employee_Name "
  ElseIf Trim(OrderClause) = "Seniority" Then
    strSelect = " Select employee_id, employee_type_id || ' ' || employee_sub_type_id || ' ' || seniority || ' ' || employee_name || ' (' || employee_id || ')' Employee, Employee_Type_Id, Employee_Sub_Type_Id, Seniority "
    strOrder = " order by Employee_Type_Id, Employee_Sub_Type_Id, Seniority "
  End If
  
  strFromWhere = " from Employee  where Upper(Employee_Type_ID) NOT IN ('ADMN', 'SUPV', 'INACTE') and Employee_Id NOT in (Select employee_id from Daily_Hire_list where Hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')) and Employee_ID Not IN (Select Employee_ID from Daily_Hire_Temp) "

  If Left(HireRole, 1) = "1" Then
    mySQL = strSelect & strFromWhere & " and (Employee_sub_type_id IN ('PM', 'ME', 'CO')or Employee_id in ('E405619', 'E406366','E406569', 'E406652','E406968'))"
  Else
    mySQL = ""
  End If
  
  If Mid(HireRole, 2, 1) = "1" Then
    If mySQL <> "" Then
        isUnion = " UNION "
    Else
        isUnion = ""
    End If
    mySQL = mySQL & isUnion & strSelect & strFromWhere & " and Employee_type_id = 'GUARD' "
  End If
  
  If Right(HireRole, 1) = "1" Then
    If mySQL <> "" Then
        isUnion = " UNION "
    Else
        isUnion = ""
    End If
        mySQL = mySQL & isUnion & strSelect & strFromWhere & " and Employee_Type_ID <> 'GUARD' AND (Employee_sub_type_id is NULL or Employee_sub_type_id NOT in ('PM', 'ME', 'CO')) "
  End If

  If mySQL = "" Then
    Exit Sub
  Else
    mySQL = mySQL & strOrder
  End If
  
  Set EmpRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
End Sub

'****************************************
'To Store Employee details in Array - Used to Display Employee First & Last Name
'****************************************
Private Sub StoreinArray()
  Dim mySQL As String, arrCnt As Integer, TotalEmpRec As Integer
  mySQL = "Select * from Employee"
  Set EmpRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If EmpRS.BOF And EmpRS.EOF Then
    Exit Sub
  Else
    EmpRS.MoveFirst
    Do
      EmpRS.MoveNext
      TotalEmpRec = TotalEmpRec + 1
    Loop Until EmpRS.EOF
    EmpRS.MoveFirst
    ReDim arrEmplID(TotalEmpRec) As String
    'ReDim arrFName(TotalEmpRec) As String
    arrCnt = 0
    Do While Not EmpRS.EOF
    '  arrFName(arrCnt) = EmpRS.Fields("Employee_Name").Value
      arrEmplID(arrCnt) = EmpRS.Fields("Employee_id").Value
      arrCnt = arrCnt + 1
      EmpRS.MoveNext
    Loop
  End If
  maxarrrec = arrCnt
End Sub

'****************************************
'To populate the list box with Employee Name
'****************************************
Private Sub PopulateEmpName()
  Dim arrCnt As Integer ', TypeID As String, SubTypeID As String
  If IsNull(EmpRS) Or EmpRS.BOF And EmpRS.EOF Then
    MsgBox "No Employee records to display." + Chr(13) + "All Employees are Hired /  Selected for Hiring", vbInformation, "Data Unavailable"
    Exit Sub
  Else
    EmpRS.MoveLast
    TotalRec = EmpRS.RecordCount
    EmpRS.MoveFirst
    arrCnt = 0
    ReDim arrEmployeeID(TotalRec) As String ', arrFirstName(TotalRec) As String
    Do While Not EmpRS.EOF
      arrEmployeeID(arrCnt) = EmpRS.Fields("Employee_id").Value
      lstEmpName.AddItem EmpRS.Fields("Employee").Value
      arrCnt = arrCnt + 1
      EmpRS.MoveNext
    Loop
  End If
  maxarr = arrCnt
End Sub

'****************************************
'To Enable / Disable ADD ALL button
'****************************************
Private Sub lstEmpName_Click()
  If lstEmpName.SelCount > 0 Then   'One or more Records are selected from list
    cmdAddAll.Enabled = False
  ElseIf lstEmpName.SelCount = 0 Then
    cmdAddAll.Enabled = True
  End If
End Sub

'****************************************
'To Enable / Disable REMOVE ALL Button
'****************************************
Private Sub lstSelected_Click()
  If lstSelected.SelCount > 0 Then   'One or more Records are selected from list
    cmdRemoveAll.Enabled = False
  ElseIf lstSelected.SelCount = 0 Then
    cmdRemoveAll.Enabled = True
  End If
End Sub

'****************************************
'To Sort the Entries in List based on option selected
'****************************************
Private Sub optSortBy_Click(Index As Integer)
'For Bob Barker and Bill Krupa only REGR Employees
  'If UserID = "E407297" Or UserID = "E000457" Then
  '  If Index = 0 Then
  '    Call OpenRS("RegName")
  '  ElseIf Index = 1 Then
  '    Call OpenRS("RegEmpID")
  '  ElseIf Index = 2 Then
  '   Call OpenRS("RegTypeID")
  '  ElseIf Index = 3 Then
  '    Call OpenRS("RegSeniority")
  '  End If
  'Else
    If Index = 0 Then
      Call OpenRS("Name", HireRole)
    ElseIf Index = 1 Then
      Call OpenRS("EmpID", HireRole)
    ElseIf Index = 2 Then
      Call OpenRS("TypeID", HireRole)
    ElseIf Index = 3 Then
      Call OpenRS("Seniority", HireRole)
    End If
  'End If
  lstEmpName.Clear
  PopulateEmpName
  
  If lstEmpName.SelCount = 0 Then
    Label10.Caption = "No Employee is Selected for Hiring"
  Else
    Label10.Caption = Str(lstEmpName.SelCount) + " Employee(s) Selected for Hiring"
  End If
End Sub

'****************************************
'To Increment / Decrement the Time Entry
'****************************************
Private Sub SpinButton1_Change()
  If SpinButton1.Value = SpinButton1.Max Then
    Text1 = Int(Text1) + 1
    Text2 = "00"
  ElseIf SpinButton1.Value = 0 Then
    Text2 = "00"
  ElseIf SpinButton1.Value = SpinButton1.Min Then
    'Text2 = "45"    'Duration in 1/2 an Hour
    Text2 = "30"
    Text1 = Int(Text1) - 1
  Else
    Text2 = SpinButton1.Value
  End If
  If Int(Text1) < 1 Then
    Text1 = "12"
  ElseIf Int(Text1) > 12 Then
    Text1 = "1"
  End If
  If Int(Text1) = 12 And (SpinButton1.Value = 60) Then
    If optAMPM(0).Value = True Then
      optAMPM(1).Value = True
    Else
      optAMPM(0).Value = True
    End If
  ElseIf Int(Text1) = 11 And (SpinButton1.Value = -15) Then
    If optAMPM(0).Value = True Then
      optAMPM(1).Value = True
    Else
      optAMPM(0).Value = True
    End If
  End If
End Sub

'****************************************
'To Decrement the Time Entry
'****************************************
Private Sub SpinButton1_SpinDown()
  If SpinButton1.Value = SpinButton1.Min Then
    'SpinButton1.Value = 45   'Duration in 1/2 an Hour
    SpinButton1.Value = 30
  End If
End Sub

'****************************************
'To Increment the Time Entry
'****************************************
Private Sub SpinButton1_SpinUp()
  If SpinButton1.Value = SpinButton1.Max Then
    SpinButton1.Value = 0
  End If
End Sub

'****************************************
'To Refresh the List with the new set of Employees based on Date Selected
'****************************************
Private Sub SSDateCombo1_LostFocus()
  Dim myUpdateSQL As String, myrec As Integer
  lstEmpName.Clear
  'For Bob Barker and Bill Krupa only REGR Employees
  'If UserID = "E407297" Or UserID = "E000457" Then
  '  Call OpenRS("RegName")     'Open Recordset based on Sort option - Initially Name
  'Else
    Call OpenRS("Name", HireRole)     'Open Recordset based on Sort option - Initially Name
  'End If
  PopulateEmpName         'To populate the List Box with Employee Names
  'Remove the selected List from Temp Table
  lstSelected.Clear
  OraSession.BeginTrans
  myUpdateSQL = "Delete from Daily_Hire_Temp"
  myrec = OraDatabase.ExecuteSQL(myUpdateSQL)
  If OraDatabase.LastServerErr = 0 Then
    OraSession.CommitTrans
  Else
    OraSession.Rollback
  End If
  Label10.Caption = "No Employee is Selected for Hiring"
End Sub

'****************************************
'To Refresh the List with the new set of Employees based on Date Selected
'****************************************
Private Sub SSDateCombo1_Click()
  Dim myUpdateSQL As String, myrec As Integer
  lstEmpName.Clear
  'For Bob Barker and Bill Krupa only REGR Employees
  'If UserID = "E407297" Or UserID = "E000457" Then
  '  Call OpenRS("RegName")     'Open Recordset based on Sort option - Initially Name
  'Else
    Call OpenRS("Name", HireRole)     'Open Recordset based on Sort option - Initially Name
  'End If
  PopulateEmpName         'To populate the List Box with Employee Names
  'Remove the selected List from Temp Table
  lstSelected.Clear
  OraSession.BeginTrans
  myUpdateSQL = "Delete from Daily_Hire_Temp"
  myrec = OraDatabase.ExecuteSQL(myUpdateSQL)
  If OraDatabase.LastServerErr = 0 Then
    OraSession.CommitTrans
  Else
    OraSession.Rollback
  End If
  Label10.Caption = "No Employee is Selected for Hiring"
  Call PopulateDailyHire("Location_ID")        'To Update Grid
  Label15.Caption = "Total Employees Hired " + Str(HireRS.RecordCount)
  cmdRemove.Enabled = False
  cmdRemoveAll.Enabled = False
End Sub

'****************************************
'To Make the text entered to Upper Case
'****************************************
Private Sub SSDBCombo1_LostFocus()
  SSDBCombo1.Text = UCase(SSDBCombo1.Text)
End Sub

'****************************************
'To Update each Column on the Grid
'****************************************
Private Sub SSDBGrid1_AfterColUpdate(ByVal ColIndex As Integer)
  Call SSDBGrid1_AfterUpdate(0)
End Sub

'****************************************
'To update the Label after deleting a record from Grid
'****************************************
Private Sub SSDBGrid1_AfterDelete(RtnDispErrMsg As Integer)
  Label14.Caption = "Total Employees Hired " + Str(SSDBGrid1.rows)
  If SSDBGrid1.rows = 0 Then CmdDeleteAll.Enabled = False
  txtEmpFNm = ""
  txtLocDesc = ""
'  txtCommName = ""
  txtNoofEmp = ""
End Sub

'****************************************
'To update the DB using the entries from Grid
'****************************************
Private Sub SSDBGrid1_AfterUpdate(RtnDispErrMsg As Integer)
  On Error Resume Next
  HireRS.MoveFirst
  HireRS.DBFindFirst "Employee_ID = '" + SSDBGrid1.Columns(0).Value + "'"
  If HireRS.NoMatch Then
    'Do Nothing
  Else
    OraSession.BeginTrans     'Begin the Transaction
    'Edit the Data
    HireRS.Edit
    HireRS.Fields("Location_ID").Value = SSDBGrid1.Columns(1).Value
 '   HireRS.Fields("Commodity_Code").Value = SSDBGrid1.Columns(2).Value
    HireRS.Fields("Time_In").Value = HireRS.Fields("HIRE_DATE").Value & " " & SSDBGrid1.Columns(2).Value
    'HireRS.Fields("User_ID").Value = UserID
    HireRS.Update
    'Commit or Rollback the Transaction
    If OraDatabase.LastServerErr = 0 Then
      OraSession.CommitTrans
    Else
      OraSession.Rollback
    End If
  End If
End Sub

'****************************************
'To take care of Referential Integrity while deleting a record from Daily Hire
'****************************************
Private Sub SSDBGrid1_BeforeDelete(Cancel As Integer, DispPromptMsg As Integer)
  On Error GoTo ErrHandler
  Dim DelConfirm As Integer, CheckRS As Object, mySQL As String
  
  DispPromptMsg = 0       'Not to display System Message
  
  'Check for User ID; Allow modifications only if Same User ID
  mySQL = "Select * from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Employee_ID = '" + SSDBGrid1.Columns(0).Text + "'"
  Set CheckRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If CheckRS.EOF And CheckRS.BOF Then
    'Do Nothing
  Else
    CheckRS.MoveFirst   'Only One record in Daily Hire Table
    If CheckRS.Fields("User_ID").Value <> UserID Then
      MsgBox "You are not Authorised to Delete data entered by " + CheckRS.Fields("User_ID").Value, vbInformation, "Authorization Required"
      Cancel = True
      Exit Sub
    End If
  End If
  OraSession.BeginTrans   'Begin the Transaction
  DelConfirm = MsgBox("Are you sure to delete this Daily Hire Detail ?", vbYesNo + vbQuestion, "Confirm Delete")
  If DelConfirm = vbYes Then
    'Check for records in Hourly Detail Table for this Employee
    Dim HourlyRS As Object, myDelSQL As String, myRecCount As Integer
    
    mySQL = "Select * from Hourly_Detail where Hire_date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Employee_Id = '" + SSDBGrid1.Columns(0).Text + "'"
    Set HourlyRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
    If HourlyRS.EOF And HourlyRS.BOF Then
      'No records in Hourly Detail - Continue with deleting Daily Hire Record
      mySQL = "Delete from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Employee_ID = '" + SSDBGrid1.Columns(0).Text + "'"
      myRecCount = OraDatabase.ExecuteSQL(mySQL)
    Else
      'Confirm Deletion
      DelConfirm = MsgBox("Employee Data in Hourly Detail will be lost." + Chr(13) + "Are you sure to delete both from Daily Hire and Hourly Detail ?", vbYesNo + vbQuestion, "Confirm Delete")
      If DelConfirm = vbYes Then
        Call StoreInTextFile(SSDBGrid1.Columns(0).Value)     'To Store the Contents of Hourly detail (If any) in Text File
        'Continue with deleting Record from Hourly Detail
        myDelSQL = "Delete from Hourly_Detail where Employee_ID = '" + SSDBGrid1.Columns(0).Value + "' and Hire_Date= to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')"
        OraDatabase.ExecuteSQL myDelSQL
        
        'Now delete Record from Daily Hire
        mySQL = "Delete from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Employee_ID = '" + SSDBGrid1.Columns(0).Text + "'"
        OraDatabase.ExecuteSQL mySQL

      Else
        Cancel = True 'Don't delete daily Hire and Hourly Detail
      End If
    End If
  Else
    Cancel = True
  End If
  'Commit or Rollback the Transaction
  If OraDatabase.LastServerErr = 0 Then
    OraSession.CommitTrans
  Else
    OraSession.Rollback
  End If
  Exit Sub
ErrHandler:
  If Err.Number = 3705 Then   'Data Environment already Opened - Close and proceed
    DE_LCS.rsHourlyDetail_Grouping.Close
    Resume
  ElseIf Err.Number = -2147217915 Then
    MsgBox "Please try at a later time", vbInformation, "Deletion Failure"
    Cancel = True
  ElseIf Err.Number = 3704 Then
    'Object already Closed
  ElseIf Err.Number = 440 Then    'Transaction already in Progress
    OraSession.Rollback
    Resume
  ElseIf Err.Number = 55 Then   'File Already Open
    Close #1
    Resume
  End If
End Sub

'****************************************
'To Make Time In Entry to Upper Case
'****************************************
Private Sub SSDBGrid1_BeforeRowColChange(Cancel As Integer)
  If SSDBGrid1.Col = 2 Then
    SSDBGrid1.Columns(2).Value = UCase(SSDBGrid1.Columns(2).Value)
  End If
End Sub

'****************************************
'To Check for Blank Entries before updating the DB from the Grid
'****************************************
Private Sub SSDBGrid1_BeforeUpdate(Cancel As Integer)
  Dim mySQL As String, CheckRS As Object
  'Check for User ID; Allow modifications only if Same User ID
  mySQL = "Select * from Daily_Hire_List where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Employee_ID = '" + SSDBGrid1.Columns(0).Text + "'"
  Set CheckRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If CheckRS.EOF And CheckRS.BOF Then
    'Do Nothing
  Else
    CheckRS.MoveFirst   'Only One record in Daily Hire Table
    If CheckRS.Fields("User_ID").Value <> UserID And (PrevCate <> SSDBGrid1.Columns(1).Value Or PrevTime <> SSDBGrid1.Columns(2).Value) Then
        If GroupID <> 8 Then
            MsgBox "You are not Authorised to Modify data entered by " + CheckRS.Fields("User_ID").Value, vbInformation, "Authorization Required"
            Cancel = True
            SSDBGrid1.Columns(1).Value = PrevCate
            SSDBGrid1.Columns(2).Value = PrevTime
            Exit Sub
        End If
    End If
  End If
  
  If Trim(SSDBGrid1.Columns(2).Value) = vbNullString Then
    MsgBox "Time In Should not be blank", vbInformation, "Data Required"
    Cancel = True
  End If
End Sub

'****************************************
'Not to Show the Default Error Message from the Grid
'****************************************
Private Sub SSDBGrid1_Error(ByVal DataError As Integer, Response As Integer)
  Response = 0
End Sub

'****************************************
'To Sort by appropriate column on Column Head Click of Grid
'****************************************
Private Sub SSDBGrid1_HeadClick(ByVal ColIndex As Integer)
  Dim a(3) As String
  a(0) = "Employee_ID"
  a(1) = "Location_ID"
  'a(2) = "Commodity_Code"
  a(2) = "Time_In"
  Call PopulateDailyHire(a(ColIndex))
End Sub

'****************************************
'To display the Details when a Row is Changed on the Grid
'****************************************
Private Sub SSDBGrid1_RowColChange(ByVal LastRow As Variant, ByVal LastCol As Integer)
  On Error Resume Next
  PrevCate = SSDBGrid1.Columns(1).Value
  PrevTime = SSDBGrid1.Columns(2).Value

 'Display the Employee Name
  Dim EmpNMRS As Object
  Set EmpNMRS = OraDatabase.DBCreateDynaset("Select Employee_Name from Employee where upper(Employee_ID) = '" + UCase(Trim(SSDBGrid1.Columns(0).Value)) + "'", 0&)
  If EmpNMRS.EOF And EmpNMRS.BOF Then
    'Do Nothing
  Else
    txtEmpFNm = EmpNMRS.Fields("Employee_Name").Value
  End If
  EmpNMRS.Close
  Set EmpNMRS = Nothing
  
  'Display the Location Description
  Dim LocRS As Object
  Set LocRS = OraDatabase.DBCreateDynaset("Select Location_Description from Location_Category where Upper(Location_ID) = '" + UCase(Trim(SSDBGrid1.Columns(1).Value)) + "'", 0&)
  If LocRS.EOF And LocRS.BOF Then
    'Do Nothing
  Else
    txtLocDesc = LocRS.Fields("Location_Description").Value
  End If
  LocRS.Close
  Set LocRS = Nothing
  
  'Display the Commodity Name
  'Dim CommRS As Object
  'Set CommRS = OraDatabase.DBCreateDynaset("Select Commodity_Name from Commodity where Commodity_Code = '" + SSDBGrid1.Columns(2).Value + "'", 0&)
  'If CommRS.EOF And CommRS.BOF Then
  '  'Do Nothing
  'Else
  '  txtCommName = CommRS.Fields("Commodity_Name").Value
  'End If
  'CommRS.Close
  'Set CommRS = Nothing
  
  'Display the Total Number of Employees Hired for the current Location
  Dim CountEmpRS As Object
  Set CountEmpRS = OraDatabase.DBCreateDynaset("Select count(*) as Total from daily_hire_list where location_id ='" + SSDBGrid1.Columns(1).Value + "' and Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy')", 0&)
  If CountEmpRS.EOF And CountEmpRS.BOF Then
    'Do Nothing
  Else
    If CountEmpRS.Fields("Total").Value = 0 Then
      txtNoofEmp = ""
    Else
      txtNoofEmp = SSDBGrid1.Columns(1).Value + " : " + CountEmpRS.Fields("Total").Value
    End If
  End If
  CountEmpRS.Close
  Set CountEmpRS = Nothing
  
End Sub

'****************************************
'To Accept only Valid Characters for Time IN Column
'****************************************
Private Sub SSDBGrid1_KeyPress(KeyAscii As Integer)
  Dim myLen As Integer
 
  'Checking only for Time IN column
  If SSDBGrid1.Col = 2 Then
    'Always allow backspace character
    If KeyAscii <> 8 And KeyAscii <> 13 Then
      'Depending upon the position, allow different type of characters
      myLen = Len(Trim(SSDBGrid1.Columns(SSDBGrid1.Col).Value))
      If (myLen = 0) Then     'Hour - I digit
        'Allow Only 0 and 1
        If KeyAscii <> 48 And KeyAscii <> 49 Then KeyAscii = 0
      ElseIf myLen = 1 Then   'Hour - II digit
        If CInt(SSDBGrid1.Columns(SSDBGrid1.Col).Value) = 1 Then
          'Allow only 0, 1 and 2
          If KeyAscii <> 48 And KeyAscii <> 49 And KeyAscii <> 50 Then KeyAscii = 0
        ElseIf CInt(SSDBGrid1.Columns(SSDBGrid1.Col).Value) = 0 Then
          'Don't Allow Zero - Other numbers are allowed
          If KeyAscii < 49 Or KeyAscii > 57 Then KeyAscii = 0
        End If
      ElseIf myLen = 2 Then   'Allow only :
        If KeyAscii <> 58 Then KeyAscii = 0
      ElseIf myLen = 3 Then   'Minutes - I digit
        'Allow only numbers from 0 - 5
        If KeyAscii < 48 Or KeyAscii > 53 Then KeyAscii = 0
      ElseIf myLen = 4 Then   'Minutes - II digit
        'Allow only numbers
        If KeyAscii < 48 Or KeyAscii > 57 Then KeyAscii = 0
      ElseIf myLen = 5 Then   'Time Zone - I Character
        'Allow only A, a, P and p characters
        If KeyAscii = 65 Or KeyAscii = 97 Or KeyAscii = 112 Or KeyAscii = 80 Then
          'Do Nothing
        Else
          KeyAscii = 0
        End If
      ElseIf myLen = 6 Then   'Time Zone - II Character
        'Allow only M and m characters
        If KeyAscii <> 109 And KeyAscii <> 77 Then KeyAscii = 0
      Else  'No other Character allowed - Total Only 7 Characters
        KeyAscii = 0
      End If
    End If
  End If
End Sub

'****************************************
'To update the Controls when Tab is Selected
'****************************************
Private Sub SSTab1_Click(PreviousTab As Integer)
  If SSTab1.Tab = 0 Then
    'For Bob Barker and Bill Krupa only REGR Employees
    'If UserID = "E407297" Or UserID = "E000457" Then
    '  If optSortBy(0).Value = True Then
    '    Call OpenRS("RegName")
    '  ElseIf optSortBy(1).Value = True Then
    '    Call OpenRS("RegEmpID")
    '  ElseIf optSortBy(2).Value = True Then
    '    Call OpenRS("RegTypeID")
    '  ElseIf optSortBy(3).Value = True Then
    '    Call OpenRS("RegSeniority")
    '  End If
    'Else
      If optSortBy(0).Value = True Then
        Call OpenRS("Name", HireRole)
      ElseIf optSortBy(1).Value = True Then
        Call OpenRS("EmpID", HireRole)
      ElseIf optSortBy(2).Value = True Then
        Call OpenRS("TypeID", HireRole)
      ElseIf optSortBy(3).Value = True Then
        Call OpenRS("Seniority", HireRole)
      End If
    'End If
    lstEmpName.Clear
    PopulateEmpName          'To Update List
    Call PopulateDailyHire("Location_ID")        'To Update Grid
    Label15.Caption = "Total Employees Hired " + Str(HireRS.RecordCount)
    Label10.Caption = "No Employee is Selected for Hiring"
    If lstSelected.ListCount = 0 Then
      cmdRemove.Enabled = False
      cmdRemoveAll.Enabled = False
    Else
      cmdRemove.Enabled = True
      cmdRemoveAll.Enabled = True
    End If
  ElseIf SSTab1.Tab = 1 Then
    Call PopulateDailyHire("Location_ID")       'To Update Grid
    Label14.Caption = "Total Employees Hired " + Str(SSDBGrid1.rows)
    txtEmpFNm = ""
    txtLocDesc = ""
  End If
End Sub

'****************************************
'To Validate Hours in Time In
'****************************************
Private Sub Text1_LostFocus()
  If Int(Text1) > 12 Then
    MsgBox "Please Enter Regular Time", vbInformation, "Invalid Time"
    ShowTime
    Text1.SetFocus
  End If
End Sub

'****************************************
'To Validate Minutes in Time In
'****************************************
Private Sub Text2_LostFocus()
  If Int(Text2) > 59 Then
    MsgBox "Please Enter valid Minutes", vbInformation, "Invalid Time"
    ShowTime
    Text2.SetFocus
  End If
End Sub

'****************************************
'To Store the Hourly Detail in a Text File, While Deleting a record from Daily Hire
'****************************************
Private Sub StoreInTextFile(empId As String)
  Dim mySQL As String, myDtlRS As Object, myReg As String, myEmp As String
  Dim myStr As String, myHrs As String, myComm As String, myLoc As String, myCust As String
  Dim mySvc As String, myEqp As String, myShip As String, myFile As String

  If Trim(empId) = "" Then
    mySQL = "Select * from Hourly_Detail Where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') Order by Employee_ID, Row_Number"
  Else
    mySQL = "Select * from Hourly_Detail Where Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') and Upper(Employee_ID) = '" + UCase(Trim(empId)) + "' Order by Employee_ID, Row_Number"
  End If
  Set myDtlRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If myDtlRS.RecordCount > 0 Then
    'Store the data from Hourly Detail in a text file - Start with DH + Current date
    myFile = App.Path + "\DH" + Format(Date, "mm") + Format(Date, "dd") + ".txt"
    Open myFile For Append As #1
    Print #1, Space(20) + "DIAMOND STATE PORT CORPORATION"
    Print #1, Space(19) + "Deleted Data from Hourly Detail" + Space(15) + Str(Date)
    Print #1, "-----------------------------------------------------------------------------------"
    Print #1, "EMPID   START   END     HRS   REG BILL SVC  EQP  COM  CATE       SHIP  CUST  USER"
    Print #1, "-----------------------------------------------------------------------------------"
    myDtlRS.MoveFirst
    Do While Not myDtlRS.EOF
      'Stuff Hours with Blank Spaces
      If Trim(Len(myDtlRS.Fields("Duration").Value)) < 5 Then
        myHrs = Trim(myDtlRS.Fields("Duration").Value) + Space(5 - Trim(Len(myDtlRS.Fields("Duration").Value)))
      Else
        If IsNull(myDtlRS.Fields("Duration").Value) Or Trim(myDtlRS.Fields("Duration").Value) = vbNullString Then
          myHrs = Space(5)
        Else
          myHrs = myDtlRS.Fields("Duration").Value
        End If
      End If
      
      'Stuff Commodity with Blank Spaces
      If Trim(Len(myDtlRS.Fields("Commodity_Code").Value)) < 4 Then
        myComm = Trim(myDtlRS.Fields("Commodity_Code").Value) + Space(4 - Trim(Len(myDtlRS.Fields("Commodity_Code").Value)))
      Else
        If IsNull(myDtlRS.Fields("Commodity_Code").Value) Or Trim(myDtlRS.Fields("Commodity_Code").Value) = vbNullString Then
          myComm = Space(4)
        Else
          myComm = myDtlRS.Fields("Commodity_Code").Value
        End If
      End If
      
      'stuff Service with Blank Spaces
      If Trim(Len(myDtlRS.Fields("Service_Code").Value)) < 4 Then
        mySvc = Trim(myDtlRS.Fields("Service_Code").Value) + Space(4 - Trim(Len(myDtlRS.Fields("Service_Code").Value)))
      Else
        If IsNull(myDtlRS.Fields("Service_Code").Value) Or Trim(myDtlRS.Fields("Service_Code").Value) = vbNullString Then
          mySvc = Space(4)
        Else
          mySvc = myDtlRS.Fields("Service_Code").Value
        End If
      End If
      
      'Stuff Equipment with Blank Spaces
      If Trim(Len(myDtlRS.Fields("Equipment_ID").Value)) < 4 Then
        myEqp = Trim(myDtlRS.Fields("Equipment_ID").Value) + Space(4 - Trim(Len(myDtlRS.Fields("Equipment_ID").Value)))
      Else
        If IsNull(myDtlRS.Fields("Equipment_ID").Value) Or Trim(myDtlRS.Fields("Equipment_ID").Value) = vbNullString Then
          myEqp = Space(4)
        Else
          myEqp = myDtlRS.Fields("Equipment_ID").Value
        End If
      End If
      
      'Stuff Vessel with Blank Spaces
      If Trim(Len(myDtlRS.Fields("Vessel_ID").Value)) < 4 Then
        myShip = Trim(myDtlRS.Fields("Vessel_ID").Value) + Space(4 - Trim(Len(myDtlRS.Fields("Vessel_ID").Value)))
      Else
        If IsNull(myDtlRS.Fields("Vessel_ID").Value) Or Trim(myDtlRS.Fields("Vessel_ID").Value) = vbNullString Then
          myShip = Space(4)
        Else
          myShip = myDtlRS.Fields("Vessel_ID").Value
        End If
      End If
      
      'Stuff Location with Blank Spaces
      If Trim(myDtlRS.Fields("Location_ID").Value) = vbNullString Or IsNull(myDtlRS.Fields("Location_ID").Value) Then
        myLoc = Space(10)
      Else
        If Trim(Len(myDtlRS.Fields("Location_ID").Value)) < 10 Then
          myLoc = Trim(myDtlRS.Fields("Location_ID").Value) + Space(10 - Trim(Len(myDtlRS.Fields("Location_ID").Value)))
        Else
          myLoc = myDtlRS.Fields("Location_ID").Value
        End If
      End If
      
      'Stuff Earning Type with Blank Spaces
      If Trim(myDtlRS.Fields("Earning_Type_ID").Value) = vbNullString Or IsNull(myDtlRS.Fields("Earning_Type_ID").Value) Then
        myReg = Space(3)
      Else
        If Trim(Len(myDtlRS.Fields("Earning_Type_ID").Value)) < 3 Then
          myReg = Trim(myDtlRS.Fields("Earning_Type_ID").Value) + Space(3 - Trim(Len(myDtlRS.Fields("Earning_Type_ID").Value)))
        Else
          myReg = myDtlRS.Fields("Earning_Type_ID").Value
        End If
      End If
      
      'Stuff Employee ID with Blank Spaces
      If Trim(Len(myDtlRS.Fields("Employee_ID").Value)) < 7 Then
        myEmp = Trim(myDtlRS.Fields("Employee_ID").Value) + Space(7 - Trim(Len(myDtlRS.Fields("Employee_ID").Value)))
      Else
        myEmp = myDtlRS.Fields("Employee_ID").Value
      End If
      
      'Stuff Customer with Blank Spaces
      If Trim(Len(myDtlRS.Fields("Customer_ID").Value)) < 4 Then
        myCust = Trim(myDtlRS.Fields("Customer_ID").Value) + Space(4 - Trim(Len(myDtlRS.Fields("Customer_ID").Value)))
      Else
        If IsNull(myDtlRS.Fields("Customer_ID").Value) Or Trim(myDtlRS.Fields("Customer_ID").Value) = vbNullString Then
          myCust = Space(4)
        Else
          myCust = myDtlRS.Fields("Customer_ID").Value
        End If
      End If
      
      myStr = myEmp + " " + Format(myDtlRS.Fields("Start_Time").Value, "hh:nnAM/PM") + " " + Format(myDtlRS.Fields("End_Time").Value, "hh:nnAM/PM") + " " + myHrs + " " + myReg
      myStr = myStr + " " + myDtlRS.Fields("Billing_Flag").Value + "    " + mySvc + " " + myEqp + " " + myComm
      myStr = myStr + " " + myLoc + " " + myShip + "  " + myCust + " " + myDtlRS.Fields("User_ID").Value
      Print #1, myStr
      myDtlRS.MoveNext
    Loop
    Print #1, "-----------------------------------------------------------------------------------"
    Print #1, " "
    Close #1
    MsgBox "TimeSheet for all the Deleted Employees have been stored in the file " + myFile, vbInformation, "TimeSheet"
    ''Print the data from Hourly Detail
    'Dim myDE As New DE_LCS
    'Dim myDR As New DR_TimeSheet
    'myDE.rsHourlyDetail_Grouping.Source = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name , b.* From Employee a, Hourly_Detail b, LCS_User c Where b.Employee_id = a.Employee_Id and b.User_ID = c.User_ID and b.Hire_Date = to_date('" + SSDateCombo1.Text + "','mm/dd/yyyy') }  AS HourlyDetail COMPUTE HourlyDetail BY 'Employee_Type_ID','Employee_Sub_Type_Id','Employee_Id','Employee_Name'"
    'myDE.rsHourlyDetail_Grouping.Open
    'myDR.Refresh
    'myDR.Show
    'myDR.PrintReport
    'myDR.Hide
    'myDE.rsHourlyDetail_Grouping.Close
    myDtlRS.Close
    Set myDtlRS = Nothing
  End If
End Sub

