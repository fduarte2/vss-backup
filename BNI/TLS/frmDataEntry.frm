VERSION 5.00
Object = "{67397AA1-7FB1-11D0-B148-00A0C922E820}#6.0#0"; "MSADODC.OCX"
Object = "{F0D2F211-CCB0-11D0-A316-00AA00688B10}#1.0#0"; "MSDATLST.OCX"
Object = "{CDE57A40-8B86-11D0-B3C6-00A0C90AEA82}#1.0#0"; "MSDATGRD.OCX"
Begin VB.Form frmDataEntry 
   BackColor       =   &H00C0E0FF&
   Caption         =   "TLS-Data Entry"
   ClientHeight    =   10155
   ClientLeft      =   60
   ClientTop       =   960
   ClientWidth     =   15345
   LinkTopic       =   "Form1"
   ScaleHeight     =   -7811.539
   ScaleMode       =   0  'User
   ScaleWidth      =   17412.77
   StartUpPosition =   2  'CenterScreen
   Begin VB.Frame Frame4 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFC0C0&
      ForeColor       =   &H80000008&
      Height          =   735
      Left            =   240
      TabIndex        =   51
      Top             =   2640
      Width           =   14775
      Begin VB.CommandButton cmdFilterSetting 
         Caption         =   "Filter Settings"
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
         Left            =   5760
         TabIndex        =   52
         Top             =   240
         Width           =   4215
      End
   End
   Begin MSAdodcLib.Adodc dcUser 
      Height          =   375
      Left            =   2400
      Top             =   360
      Visible         =   0   'False
      Width           =   1695
      _ExtentX        =   2990
      _ExtentY        =   661
      ConnectMode     =   0
      CursorLocation  =   3
      IsolationLevel  =   -1
      ConnectionTimeout=   15
      CommandTimeout  =   30
      CursorType      =   3
      LockType        =   3
      CommandType     =   8
      CursorOptions   =   0
      CacheSize       =   50
      MaxRecords      =   0
      BOFAction       =   0
      EOFAction       =   0
      ConnectStringType=   1
      Appearance      =   1
      BackColor       =   -2147483643
      ForeColor       =   -2147483640
      Orientation     =   0
      Enabled         =   -1
      Connect         =   "Provider=MSDAORA.1;User ID=sag_owner;Data Source=bni;Persist Security Info=False"
      OLEDBString     =   "Provider=MSDAORA.1;User ID=sag_owner;Data Source=bni;Persist Security Info=False"
      OLEDBFile       =   ""
      DataSourceName  =   ""
      OtherAttributes =   ""
      UserName        =   "sag_owner"
      Password        =   "sag"
      RecordSource    =   $"frmDataEntry.frx":0000
      Caption         =   "USER"
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      _Version        =   393216
   End
   Begin VB.Timer Timer1 
      Enabled         =   0   'False
      Interval        =   60000
      Left            =   11160
      Top             =   240
   End
   Begin VB.Frame Frame3 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFC0C0&
      ForeColor       =   &H80000008&
      Height          =   6375
      Left            =   240
      TabIndex        =   24
      Top             =   3360
      Width           =   14775
      Begin VB.CommandButton cmdChkOutFilter 
         Caption         =   "..."
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
         Left            =   720
         TabIndex        =   50
         Top             =   3720
         Visible         =   0   'False
         Width           =   495
      End
      Begin VB.CommandButton cmdChkInFilter 
         Caption         =   "..."
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
         Left            =   600
         TabIndex        =   43
         Top             =   480
         Visible         =   0   'False
         Width           =   495
      End
      Begin MSDataGridLib.DataGrid grdCheckOutTruck 
         Bindings        =   "frmDataEntry.frx":0049
         Height          =   1935
         Left            =   240
         TabIndex        =   31
         Top             =   4080
         Width           =   14295
         _ExtentX        =   25215
         _ExtentY        =   3413
         _Version        =   393216
         AllowUpdate     =   0   'False
         Appearance      =   0
         HeadLines       =   1
         RowHeight       =   15
         FormatLocked    =   -1  'True
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ColumnCount     =   14
         BeginProperty Column00 
            DataField       =   "DAILY_ROW_NUM"
            Caption         =   "ID"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   "hh:mm AMPM"
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column01 
            DataField       =   "TIME_IN"
            Caption         =   "TIME_IN"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   1
               Format          =   "hh:mm AMPM"
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   4
            EndProperty
         EndProperty
         BeginProperty Column02 
            DataField       =   "TIME_OUT"
            Caption         =   "TIME_OUT"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   1
               Format          =   "hh:mm AMPM"
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   4
            EndProperty
         EndProperty
         BeginProperty Column03 
            DataField       =   "CHECKED_IN_BY"
            Caption         =   "CHK'D_IN_BY"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column04 
            DataField       =   "CHECKED_OUT_BY"
            Caption         =   "CHK'D_OUT_BY"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column05 
            DataField       =   "TRUCKING_COMPANY"
            Caption         =   "TRKG_CO"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column06 
            DataField       =   "DRIVER_NAME"
            Caption         =   "DRIVER"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column07 
            DataField       =   "COMMODITY_CODE"
            Caption         =   "COMM."
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column08 
            DataField       =   "COMMODITY_NAME"
            Caption         =   "COMM. DESC."
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column09 
            DataField       =   "BOL"
            Caption         =   "P. U. #"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column10 
            DataField       =   "CUSTOMER_CODE"
            Caption         =   "CUST."
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column11 
            DataField       =   "SEAL_NUM"
            Caption         =   "SEAL"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column12 
            DataField       =   "WAREHOUSE"
            Caption         =   "WHSE"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column13 
            DataField       =   "COMMENTS"
            Caption         =   "COMMENTS"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         SplitCount      =   1
         BeginProperty Split0 
            BeginProperty Column00 
               ColumnWidth     =   420.095
            EndProperty
            BeginProperty Column01 
               ColumnWidth     =   1005.165
            EndProperty
            BeginProperty Column02 
               ColumnWidth     =   1005.165
            EndProperty
            BeginProperty Column03 
               ColumnWidth     =   1094.74
            EndProperty
            BeginProperty Column04 
               ColumnWidth     =   1305.071
            EndProperty
            BeginProperty Column05 
               ColumnWidth     =   1200.189
            EndProperty
            BeginProperty Column06 
               ColumnWidth     =   1005.165
            EndProperty
            BeginProperty Column07 
               ColumnWidth     =   794.835
            EndProperty
            BeginProperty Column08 
               ColumnWidth     =   1395.213
            EndProperty
            BeginProperty Column09 
               ColumnWidth     =   1200.189
            EndProperty
            BeginProperty Column10 
               ColumnWidth     =   780.095
            EndProperty
            BeginProperty Column11 
               ColumnWidth     =   945.071
            EndProperty
            BeginProperty Column12 
               ColumnWidth     =   599.811
            EndProperty
            BeginProperty Column13 
               ColumnWidth     =   1049.953
            EndProperty
         EndProperty
      End
      Begin MSAdodcLib.Adodc dcCheckOutTruck 
         Height          =   330
         Left            =   11640
         Top             =   3600
         Visible         =   0   'False
         Width           =   2535
         _ExtentX        =   4471
         _ExtentY        =   582
         ConnectMode     =   0
         CursorLocation  =   3
         IsolationLevel  =   -1
         ConnectionTimeout=   15
         CommandTimeout  =   30
         CursorType      =   3
         LockType        =   3
         CommandType     =   1
         CursorOptions   =   0
         CacheSize       =   50
         MaxRecords      =   0
         BOFAction       =   0
         EOFAction       =   0
         ConnectStringType=   1
         Appearance      =   1
         BackColor       =   -2147483643
         ForeColor       =   -2147483640
         Orientation     =   0
         Enabled         =   -1
         Connect         =   "Provider=MSDAORA.1;User ID=sag_owner;Data Source=BNI;Persist Security Info=False"
         OLEDBString     =   "Provider=MSDAORA.1;User ID=sag_owner;Data Source=BNI;Persist Security Info=False"
         OLEDBFile       =   ""
         DataSourceName  =   ""
         OtherAttributes =   ""
         UserName        =   "sag_owner"
         Password        =   "SAG"
         RecordSource    =   $"frmDataEntry.frx":0067
         Caption         =   "CheckOutTruck"
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         _Version        =   393216
      End
      Begin MSDataGridLib.DataGrid grdCheckInTruck 
         Bindings        =   "frmDataEntry.frx":0245
         Height          =   2415
         Left            =   240
         TabIndex        =   25
         Top             =   840
         Width           =   14295
         _ExtentX        =   25215
         _ExtentY        =   4260
         _Version        =   393216
         AllowUpdate     =   0   'False
         HeadLines       =   1
         RowHeight       =   15
         FormatLocked    =   -1  'True
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ColumnCount     =   13
         BeginProperty Column00 
            DataField       =   "DAILY_ROW_NUM"
            Caption         =   "ID"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   "hh:mm AMPM"
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column01 
            DataField       =   "TIME_IN"
            Caption         =   "TIME_IN"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   1
               Format          =   "h:nn AM/PM"
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   4
            EndProperty
         EndProperty
         BeginProperty Column02 
            DataField       =   "CHECKED_IN_BY"
            Caption         =   "CHK'D_IN_BY"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column03 
            DataField       =   "TRUCKING_COMPANY"
            Caption         =   "TRKG_CO"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column04 
            DataField       =   "DRIVER_NAME"
            Caption         =   "DRIVER"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column05 
            DataField       =   "COMMODITY_CODE"
            Caption         =   "COMM."
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column06 
            DataField       =   "COMMODITY_NAME"
            Caption         =   "COMM. DESC."
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column07 
            DataField       =   "CUSTOMER_CODE"
            Caption         =   "CUST."
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column08 
            DataField       =   "LR_NUM"
            Caption         =   "VSL"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column09 
            DataField       =   "BOL"
            Caption         =   "P. U. #"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column10 
            DataField       =   "WAREHOUSE"
            Caption         =   "WHSE"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column11 
            DataField       =   "ALERT"
            Caption         =   "ALERT"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column12 
            DataField       =   "COMMENTS"
            Caption         =   "COMMENTS"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         SplitCount      =   1
         BeginProperty Split0 
            BeginProperty Column00 
               ColumnWidth     =   404.787
            EndProperty
            BeginProperty Column01 
               ColumnWidth     =   794.835
            EndProperty
            BeginProperty Column02 
               ColumnWidth     =   1200.189
            EndProperty
            BeginProperty Column03 
               ColumnWidth     =   1500.095
            EndProperty
            BeginProperty Column04 
               ColumnWidth     =   1200.189
            EndProperty
            BeginProperty Column05 
               ColumnWidth     =   599.811
            EndProperty
            BeginProperty Column06 
               ColumnWidth     =   1604.976
            EndProperty
            BeginProperty Column07 
               ColumnWidth     =   645.165
            EndProperty
            BeginProperty Column08 
               ColumnWidth     =   599.811
            EndProperty
            BeginProperty Column09 
               ColumnWidth     =   1739.906
            EndProperty
            BeginProperty Column10 
               ColumnWidth     =   705.26
            EndProperty
            BeginProperty Column11 
               ColumnWidth     =   599.811
            EndProperty
            BeginProperty Column12 
               ColumnWidth     =   2099.906
            EndProperty
         EndProperty
      End
      Begin MSAdodcLib.Adodc dcCheckInTruck 
         Height          =   375
         Left            =   9120
         Top             =   3600
         Visible         =   0   'False
         Width           =   2415
         _ExtentX        =   4260
         _ExtentY        =   661
         ConnectMode     =   0
         CursorLocation  =   3
         IsolationLevel  =   -1
         ConnectionTimeout=   15
         CommandTimeout  =   30
         CursorType      =   3
         LockType        =   3
         CommandType     =   1
         CursorOptions   =   0
         CacheSize       =   50
         MaxRecords      =   0
         BOFAction       =   0
         EOFAction       =   0
         ConnectStringType=   1
         Appearance      =   1
         BackColor       =   -2147483643
         ForeColor       =   -2147483640
         Orientation     =   0
         Enabled         =   -1
         Connect         =   "Provider=MSDAORA.1;User ID=SAG_OWNER;Data Source=BNI;Persist Security Info=False"
         OLEDBString     =   "Provider=MSDAORA.1;User ID=SAG_OWNER;Data Source=BNI;Persist Security Info=False"
         OLEDBFile       =   ""
         DataSourceName  =   ""
         OtherAttributes =   ""
         UserName        =   "SAG_OWNER"
         Password        =   "SAG"
         RecordSource    =   $"frmDataEntry.frx":0262
         Caption         =   "CheckInTruck"
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         _Version        =   393216
      End
      Begin VB.Label Label22 
         Appearance      =   0  'Flat
         AutoSize        =   -1  'True
         BackColor       =   &H00FFC0C0&
         Caption         =   "Targeted Turn Time:120 minutes"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H000000FF&
         Height          =   195
         Left            =   10680
         TabIndex        =   53
         Top             =   240
         Width           =   2775
      End
      Begin VB.Label Label21 
         AutoSize        =   -1  'True
         BackColor       =   &H00FFC0C0&
         Caption         =   "Filter Settings"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   195
         Left            =   240
         TabIndex        =   49
         Top             =   240
         Width           =   1185
      End
      Begin VB.Label lblCustFilterOut 
         BackColor       =   &H00FFC0C0&
         Caption         =   "XYZ OUT"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   -1  'True
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H000000FF&
         Height          =   255
         Left            =   3000
         TabIndex        =   48
         Top             =   3720
         Width           =   1815
      End
      Begin VB.Label lblCommFilterOut 
         BackColor       =   &H00FFC0C0&
         Caption         =   "XYZ OUT"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   -1  'True
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H000000FF&
         Height          =   255
         Left            =   3000
         TabIndex        =   47
         Top             =   3480
         Width           =   1815
      End
      Begin VB.Label Label20 
         AutoSize        =   -1  'True
         BackColor       =   &H00FFC0C0&
         Caption         =   "Filter Settings"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   195
         Left            =   240
         TabIndex        =   46
         Top             =   3480
         Width           =   1185
      End
      Begin VB.Label Label19 
         AutoSize        =   -1  'True
         BackColor       =   &H00FFC0C0&
         Caption         =   "Customer:"
         Height          =   195
         Left            =   2040
         TabIndex        =   45
         Top             =   3720
         Width           =   705
      End
      Begin VB.Label Label18 
         AutoSize        =   -1  'True
         BackColor       =   &H00FFC0C0&
         Caption         =   "Commodity Group:"
         Height          =   195
         Left            =   1560
         TabIndex        =   44
         Top             =   3480
         Width           =   1290
      End
      Begin VB.Label Label17 
         AutoSize        =   -1  'True
         BackColor       =   &H00FFC0C0&
         Caption         =   "Customer:"
         Height          =   195
         Left            =   2040
         TabIndex        =   42
         Top             =   480
         Width           =   705
      End
      Begin VB.Label Label16 
         BackColor       =   &H00FFC0C0&
         Caption         =   "Commodity Group:"
         Height          =   255
         Left            =   1560
         TabIndex        =   41
         Top             =   240
         Width           =   1395
      End
      Begin VB.Label lblCustFilterIn 
         BackColor       =   &H00FFC0C0&
         Caption         =   "CUS IN"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   -1  'True
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H000000FF&
         Height          =   255
         Left            =   3000
         TabIndex        =   40
         Top             =   480
         Width           =   1695
      End
      Begin VB.Label lblCommFilterIn 
         BackColor       =   &H00FFC0C0&
         Caption         =   "COM IN"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   -1  'True
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H000000FF&
         Height          =   255
         Left            =   3000
         TabIndex        =   39
         Top             =   240
         Width           =   2415
      End
      Begin VB.Label lblCheckOutTruck 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         AutoSize        =   -1  'True
         BackColor       =   &H00FFC0C0&
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00FF0000&
         Height          =   240
         Left            =   12075
         TabIndex        =   33
         Top             =   3720
         Width           =   105
      End
      Begin VB.Label Label14 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         AutoSize        =   -1  'True
         BackColor       =   &H00FFC0C0&
         Caption         =   "Checked Out Trucks"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00FF0000&
         Height          =   300
         Left            =   6120
         TabIndex        =   32
         Top             =   3720
         Width           =   2475
      End
      Begin VB.Label Label13 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         AutoSize        =   -1  'True
         BackColor       =   &H00FFC0C0&
         Caption         =   "Checked In Trucks"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00FF0000&
         Height          =   300
         Left            =   6360
         TabIndex        =   30
         Top             =   480
         Width           =   2265
      End
      Begin VB.Label lblCheckInTruck 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         AutoSize        =   -1  'True
         BackColor       =   &H00FFC0C0&
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00FF0000&
         Height          =   240
         Left            =   12075
         TabIndex        =   29
         Top             =   480
         Width           =   105
      End
   End
   Begin VB.Frame Frame2 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFC0C0&
      ForeColor       =   &H80000008&
      Height          =   735
      Left            =   240
      TabIndex        =   23
      Top             =   1920
      Width           =   14775
      Begin VB.CommandButton cmdRefreshGrid 
         Caption         =   "Refresh Grid"
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
         Left            =   11640
         TabIndex        =   36
         Top             =   240
         Width           =   1575
      End
      Begin VB.CommandButton cmdDML 
         Caption         =   "Action"
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
         Left            =   5760
         TabIndex        =   10
         Top             =   240
         Width           =   4215
      End
   End
   Begin VB.Frame Frame1 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFC0C0&
      ForeColor       =   &H80000008&
      Height          =   1095
      Left            =   240
      TabIndex        =   12
      Top             =   840
      Width           =   14775
      Begin VB.ComboBox dcboVsl 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   315
         Left            =   8400
         TabIndex        =   5
         Top             =   600
         Width           =   855
      End
      Begin VB.ComboBox dcboCust 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   315
         Left            =   7440
         TabIndex        =   4
         Top             =   600
         Width           =   975
      End
      Begin VB.ComboBox dcboComm 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   315
         Left            =   4200
         TabIndex        =   3
         Top             =   600
         Width           =   855
      End
      Begin VB.ComboBox dcboCheckInBy 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   315
         Left            =   120
         TabIndex        =   0
         Top             =   600
         Width           =   1335
      End
      Begin VB.ComboBox cboWHSE 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   315
         ItemData        =   "frmDataEntry.frx":044D
         Left            =   10440
         List            =   "frmDataEntry.frx":0469
         TabIndex        =   7
         Top             =   600
         Width           =   1095
      End
      Begin MSDataListLib.DataCombo dcboCheckOutBy 
         Bindings        =   "frmDataEntry.frx":0488
         DataField       =   "USER_EMAIL_NAME"
         DataSource      =   "dcUser"
         Height          =   315
         Left            =   12960
         TabIndex        =   9
         Top             =   600
         Width           =   1575
         _ExtentX        =   2778
         _ExtentY        =   556
         _Version        =   393216
         Appearance      =   0
         Style           =   2
         ListField       =   "USER_EMAIL_NAME"
         BoundColumn     =   "USER_EMAIL_NAME"
         Text            =   ""
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
      End
      Begin VB.TextBox txtCheckOutBy 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   195
         Left            =   10680
         TabIndex        =   11
         TabStop         =   0   'False
         Text            =   "txtCheckOutBy"
         Top             =   960
         Visible         =   0   'False
         Width           =   1815
      End
      Begin VB.TextBox txtSealNo 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   320
         Left            =   11520
         TabIndex        =   8
         Top             =   600
         Width           =   1455
      End
      Begin VB.TextBox txtWarehouse 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   195
         Left            =   8880
         TabIndex        =   35
         TabStop         =   0   'False
         Text            =   "txtWarehouse"
         Top             =   960
         Visible         =   0   'False
         Width           =   1095
      End
      Begin VB.TextBox txtBOL 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   320
         Left            =   9240
         TabIndex        =   6
         Top             =   600
         Width           =   1215
      End
      Begin VB.TextBox txtDriver 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   320
         Left            =   2880
         TabIndex        =   2
         Top             =   600
         Width           =   1335
      End
      Begin VB.TextBox txtTruckCompany 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   320
         Left            =   1440
         TabIndex        =   1
         Top             =   600
         Width           =   1455
      End
      Begin VB.TextBox txtCheckInBy 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   285
         Left            =   5160
         TabIndex        =   34
         TabStop         =   0   'False
         Text            =   "txtCheckInBy"
         Top             =   960
         Visible         =   0   'False
         Width           =   1335
      End
      Begin VB.Label lblCommDesc 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         BorderStyle     =   1  'Fixed Single
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   315
         Left            =   5040
         TabIndex        =   38
         Top             =   600
         Width           =   2415
      End
      Begin VB.Label Label12 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFF80&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "Chk'd In By"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   120
         TabIndex        =   22
         Top             =   240
         Width           =   1395
      End
      Begin VB.Label Label11 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFF80&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "Chk'd Out By"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   12960
         TabIndex        =   21
         Top             =   240
         Width           =   1575
      End
      Begin VB.Label Label10 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFF80&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "WHSE"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   10440
         TabIndex        =   20
         Top             =   240
         Width           =   1095
      End
      Begin VB.Label Label9 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFF80&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "Seal No."
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   11520
         TabIndex        =   19
         Top             =   240
         Width           =   1455
      End
      Begin VB.Label Label6 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFF80&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "P. U. #"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   9240
         TabIndex        =   18
         Top             =   240
         Width           =   1215
      End
      Begin VB.Label Label5 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFF80&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "VSL."
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   8400
         TabIndex        =   17
         Top             =   240
         Width           =   855
      End
      Begin VB.Label Label4 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFF80&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "CUST."
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   7440
         TabIndex        =   16
         Top             =   240
         Width           =   975
      End
      Begin VB.Label Label3 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFF80&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "COMM."
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   4200
         TabIndex        =   15
         Top             =   240
         Width           =   3255
      End
      Begin VB.Label Label2 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFF80&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "Driver's Name"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   2880
         TabIndex        =   14
         Top             =   240
         Width           =   1335
      End
      Begin VB.Label Label1 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFF80&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "Truck Company"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   1485
         TabIndex        =   13
         Top             =   240
         Width           =   1395
      End
   End
   Begin VB.Image Image1 
      Height          =   810
      Left            =   240
      Picture         =   "frmDataEntry.frx":04B2
      Top             =   0
      Width           =   1950
   End
   Begin VB.Label Label15 
      Caption         =   "Label15"
      Height          =   495
      Left            =   6600
      TabIndex        =   37
      Top             =   5160
      Width           =   1215
   End
   Begin VB.Label Label8 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H00C0E0FF&
      Caption         =   "Truck Logging System"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   18
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H80000008&
      Height          =   435
      Left            =   6000
      TabIndex        =   28
      Top             =   120
      Width           =   3915
   End
   Begin VB.Label Label7 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H00C0E0FF&
      Caption         =   "Port of Wilmington"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H80000008&
      Height          =   240
      Left            =   4320
      TabIndex        =   27
      Top             =   120
      Visible         =   0   'False
      Width           =   1890
   End
   Begin VB.Label lblDateTime 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H00000000&
      Caption         =   "DateTime"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H0000FF00&
      Height          =   360
      Left            =   11760
      TabIndex        =   26
      Top             =   240
      Width           =   3225
   End
   Begin VB.Menu mnuAction 
      Caption         =   "Actions"
      Begin VB.Menu mnuChangeMode 
         Caption         =   "Change Mode"
      End
      Begin VB.Menu mnuExit 
         Caption         =   "Exit"
      End
   End
   Begin VB.Menu mnuEdit 
      Caption         =   "Edit"
      Begin VB.Menu mnuEditEntry 
         Caption         =   "Edit Entry"
      End
   End
   Begin VB.Menu mnuTools 
      Caption         =   "Tools"
      Begin VB.Menu mnuAdmin 
         Caption         =   "Admin"
      End
      Begin VB.Menu mnuReport 
         Caption         =   "Report"
      End
   End
End
Attribute VB_Name = "frmDataEntry"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit       'Rudy

Private Entries(0 To 2) As String
Private Flag As Boolean
    
Private Declare Function GetAsyncKeyState Lib "user32" (ByVal vKey As Long) As Integer
Private Declare Function GetCursorPos Lib "user32" (lpPoint As POINTAPI) As Long
Private Declare Function WindowFromPoint Lib "user32" (ByVal xPoint As Long, ByVal yPoint As Long) As Long

Private Const VK_LBUTTON = &H1

Private Type POINTAPI
    X As Long
    Y As Long
End Type

Private Sub DropOnFocus(CB As Object)

''DataCombo
Dim pt As POINTAPI
Dim lHandle As Long
Const HighBit = &H8000
    Call GetCursorPos(pt)
    lHandle = WindowFromPoint(pt.X, pt.Y)
    If (lHandle <> CB.hWnd) Or ((GetAsyncKeyState(VK_LBUTTON) And HighBit) = 0) Then
        SendKeys "%{Down}"
    End If
End Sub

Private Sub Combo1_Change()

'    If Combo1.SelStart = 0 Then
'        Call SearchEntry(Combo1.Text, Combo1.SelStart)
'    Else
'        Call SearchEntry(Left(Combo1.Text, Combo1.SelStart), Combo1.SelStart)
'    End If

'    If (Flag = True) Then
'        MsgBox Combo1.Text
'        ''Me.Combo1.SelStart = curStart + 1
'        ''Me.Combo1.SelText = Right(Entries(i), Len(Entries(i)) - Len(SubString))
'        ''Me.Combo1.Text = Me.Combo1.Text & Right(Entries(i), Len(Entries(i)) - Len(SubString))
'        ''MsgBox Combo1.Text
'        Flag = False
'        Me.Combo1.Text = Me.Combo1.Text & Right(Entries(i), Len(Entries(i)) - Len(SubString) - 1)
'    End If

'Rudy Option explict, variable undefined because it's not used anymore - control doesn't exist!
'Call SearchEntry((Combo1.Text), Combo1.SelStart)
    
End Sub

Private Sub Combo1_KeyPress(KeyAscii As Integer)
'    ''MsgBox Chr(KeyAscii)
'    ''MsgBox (Combo1.SelStart)
'    If Combo1.SelStart = 0 Then
'        Call SearchEntry(Chr(KeyAscii), Combo1.SelStart)
'    Else
'        Call SearchEntry(Left(Combo1.Text, Combo1.SelStart), Combo1.SelStart)
'    End If
    
End Sub


Private Sub SearchEntry(SubString As String, curStart)

    
    Dim i As Integer

    
    For i = 0 To UBound(Entries)
    
    
        If StrComp(UCase(Entries(i)), UCase(SubString)) >= 0 Then
        
            
            ''Me.Combo1.Clear
            ''Me.Combo1.Refresh
            
'            Me.Combo1.SelStart = curStart + 1
'            Me.Combo1.SelText = Right(Entries(i), Len(Entries(i)) - Len(SubString))
'            Me.Combo1.Text = Me.Combo1.Text & Right(Entries(i), Len(Entries(i)) - Len(SubString))
             Flag = True
             Label12.Caption = Entries(i)
             
            Exit For
        
        End If
    
    Next i

    
End Sub

Private Sub cboWHSE_GotFocus()
    Call DropOnFocus(cboWHSE)
End Sub

Private Sub cmdChkInFilter_Click()
    optFilterApyTo = optChkInGrd
    frmFilterSetting.Show
End Sub

Private Sub cmdChkOutFilter_Click()
    optFilterApyTo = optChkOutGrd
    frmFilterSetting.Show
End Sub

Private Sub cmdDML_Click()
    
    If (ValidateInputs(action) = False) Then
        Exit Sub
    End If
    
    If action = actInsert Then
        Call CheckTruckIn
        Call RefreshGrid
        Call ClearControls
    
    ElseIf action = actUpdate Then
        
        Dim sn As String
        Dim op As String
        
        If (Len(Trim(Me.txtSealNo.Text)) = 0) Then
            sn = "NA"
        Else
            sn = Trim(Me.txtSealNo.Text)
        End If
    
        op = Trim(Me.txtCheckOutBy.Text)
        op = Trim(Me.dcboCheckOutBy.Text)
    
        Call CheckTruckOut(RecID, sn, op)
        Call RefreshGrid
        Call ClearControls
        
        '' set action to insert mode
        action = actInsert
        Call iniUI(action)
        
    End If
    

End Sub

Private Sub cmdFilterSetting_Click()
    optFilterApyTo = optBothGrd
    frmFilterSetting.Show
End Sub

Private Sub cmdLate_Click()
    Me.grdCheckInTruck.Row = 0
    Me.grdCheckInTruck.Col = 11
    ''MsgBox Me.grdCheckInTruck.Text
    
    Me.grdCheckInTruck.Text = "Yes"
    
    
    
    
End Sub

Private Sub cmdRefreshGrid_Click()
    Call RefreshGrid
End Sub

Private Sub Command1_Click()

    Me.dcCheckInTruck.RecordSource = "SELECT A.RECORD_ID, A.DAILY_ROW_NUM, A.TIME_IN, A.CHECKED_IN_BY, A.TRUCKING_COMPANY, A.DRIVER_NAME, A.COMMODITY_CODE, A.COMMODITY_NAME, A.CUSTOMER_CODE, A.LR_NUM, A.BOL, A.WAREHOUSE" & _
                                " FROM TLS_TRUCK_LOG A, TLS_COMMODITY_PROFILE B" & _
                                " WHERE TO_CHAR(a.TIME_IN, 'mm/dd/yyyy')=" & _
                                " (select TO_CHAR(sysdate, 'mm/dd/yyyy') from DUAL)" & _
                                " and a.TIME_IN=a.TIME_OUT" & _
                                " AND A.COMMODITY_CODE=B.COMMODITY_CODE" & _
                                " AND B.COMMODITY_GROUP=3" & _
                                " order by a.TIME_IN desc"

    Me.dcCheckInTruck.Refresh
    Me.grdCheckInTruck.Refresh
    
    
    
    

    
End Sub

Private Sub dcboCheckInBy_GotFocus()
'   Call DropOnFocus(dcboCheckInBy)
End Sub

Private Sub dcboCheckOutBy_GotFocus()
  Call DropOnFocus(dcboCheckOutBy)
End Sub

Private Sub dcboComm_GotFocus()
' Call DropOnFocus(dcboComm)
End Sub

Private Sub dcboComm_LostFocus()
    Me.lblCommDesc.Caption = UCase(getComDesc(Int(Val(dcboComm.Text))))
End Sub

Private Sub dcboComm_MouseUp(Button As Integer, Shift As Integer, X As Single, Y As Single)
    Me.lblCommDesc.Caption = UCase(getComDesc(Int(Val(dcboComm.Text))))
End Sub


Private Sub dcboCust_GotFocus()
' Call DropOnFocus(dcboCust)
End Sub

Private Sub dcboVsl_GotFocus()
'    Call DropOnFocus(dcboVsl)
End Sub

Private Sub Form_Load()
    
    On Error GoTo EH
    Call InitGlobalVariables
    
    strStatus = "Form_Load action = actInsert next"    'Rudy
    action = actInsert
    
    strStatus = "Form_Load next Call iniUI(action) next"    'Rudy
    Call iniUI(action)
    
    strStatus = "Form_Load Call ClearControls next"    'Rudy
    Call ClearControls
    
    strStatus = "Form_Load Call RefreshGrid next"    'Rudy
    Call RefreshGrid
     
    strStatus = "Form_Load Call RetrieveCommGrpDef next"    'Rudy
    'Call RetrieveCommGrpDef
    If RetrieveCommGrpDef = False Then
      strStatus = "Form_Load RetrieveCommGrpDef = False prior"    'Rudy
      GoTo EH
    End If

    strStatus = "Form_Load Call RetrieveCommList next"    'Rudy
    'Call RetrieveCommList
    If RetrieveCommList = False Then
      strStatus = "Form_Load RetrieveCommList = False prior"    'Rudy
      GoTo EH
    End If
    
    strStatus = "Form_Load next Me.Width = 0.9 * (Screen.Width)"    'Rudy
    Me.Width = 0.9 * (Screen.Width)
    Me.Height = 0.9 * (Screen.Height)
    Me.Timer1.Enabled = True
    ''MsgBox DB
    
    
    strStatus = "Form_Load Exit Sub normally next"    'Rudy
    Exit Sub
EH:
    strStatus = strStatus & "Form_Load EH begin"    'Rudy
    If Err.Number <> 0 Then
        strStatus = strStatus & "Form_Load EH MsgBox next"    'Rudy

        MsgBox Err.Description & " occurred in " & App.Title & "." & "Form_Load.  " & strStatus
    End If
     
    Unload Me
    End           'Rudy this may be his problem, it's still running from before, never really died properly?
End Sub

Private Sub Text1_Change()
    'Rudy Option explict, variable undefined because it's not used anymore - control doesn't exist!
    'MsgBox Text1.SelStart & Text1.SelText
    
End Sub

Private Sub grdCheckInTruck_DblClick()
    Call RetrieveRecord
End Sub

Private Sub grdCheckInTruck_MouseUp(Button As Integer, Shift As Integer, X As Single, Y As Single)
    
    If (grdCheckInTruck.SelBookmarks.count > 0) And (Button = 1) Then
        mnuEditEntry.Enabled = True
        selGrid = inGrid
    End If
    
    If (grdCheckInTruck.SelBookmarks.count > 0) And (Button = 2) Then
        mnuEditEntry.Enabled = True
        selGrid = inGrid
        Me.PopupMenu Me.mnuEdit
    End If
End Sub

Private Sub grdCheckOutTruck_Click()
    ''Call RetrieveSingleEntry
End Sub

Private Sub grdCheckOutTruck_DblClick()
    Call RetrieveSingleEntry
End Sub

Private Sub grdCheckOutTruck_MouseUp(Button As Integer, Shift As Integer, X As Single, Y As Single)
    If (grdCheckOutTruck.SelBookmarks.count > 0) And (Button = 1) Then
        mnuEditEntry.Enabled = True
        selGrid = outGrid
    End If
    
    If (grdCheckOutTruck.SelBookmarks.count > 0) And (Button = 2) Then
        mnuEditEntry.Enabled = True
        selGrid = outGrid
        Me.PopupMenu Me.mnuEdit
    End If
End Sub

Private Sub mnuAdmin_Click()
    'Rudy, it shows an empty form
    frmTLSAdmin.Show
End Sub

Private Sub mnuChangeMode_Click()

    If action = actUpdate Then
        action = actInsert
        Call iniUI(action)
        Call ClearControls
        Call RefreshGrid
    
    End If

End Sub

Private Sub mnuEditEntry_Click()
    Call EditLogEntry
End Sub

Private Sub mnuExit_Click()
    Dim ans As Integer
    
    ans = MsgBox("Program will be closed. Are you sure?", vbYesNo)
    
    'If (ans = vbYes) Then End
    If (ans = vbYes) Then       'Rudy, expand on that
      Unload Me
      End
    End If
End Sub

Private Sub mnuReport_Click()
    frmRptMenu.Show
End Sub

Private Sub Timer1_Timer()
    Call RefreshDateTime
    Call RefreshGrid
End Sub

Private Sub TimerGrid_Timer()
    Call RefreshGrid
End Sub
