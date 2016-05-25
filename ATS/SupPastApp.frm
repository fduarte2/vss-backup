VERSION 5.00
Begin VB.Form SupPastApp 
   BackColor       =   &H00FFFFFF&
   Caption         =   "Post-Approvals"
   ClientHeight    =   7950
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   11925
   LinkTopic       =   "Form1"
   ScaleHeight     =   7950
   ScaleWidth      =   11925
   StartUpPosition =   3  'Windows Default
   Begin VB.Frame frmHours 
      BackColor       =   &H00C0C0C0&
      Height          =   4455
      Left            =   720
      TabIndex        =   6
      Top             =   2400
      Width           =   10455
      Begin VB.TextBox WeekOf8 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   51
         Top             =   3960
         Width           =   1575
      End
      Begin VB.TextBox WeekOf7 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   50
         Top             =   3480
         Width           =   1575
      End
      Begin VB.TextBox WeekOf6 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   49
         Top             =   3000
         Width           =   1575
      End
      Begin VB.TextBox WeekOf5 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   48
         Top             =   2520
         Width           =   1575
      End
      Begin VB.TextBox WeekOf4 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   47
         Top             =   2040
         Width           =   1575
      End
      Begin VB.TextBox WeekOf3 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   46
         Top             =   1560
         Width           =   1575
      End
      Begin VB.TextBox WeekOf2 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   45
         Top             =   1080
         Width           =   1575
      End
      Begin VB.TextBox WeekOf1 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   44
         Top             =   600
         Width           =   1575
      End
      Begin VB.TextBox Emp8Name 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   120
         Locked          =   -1  'True
         TabIndex        =   38
         Tag             =   "NInvoice Number"
         Top             =   3960
         Width           =   1695
      End
      Begin VB.TextBox Emp7Name 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "M/d/yyyy"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   3
         EndProperty
         Height          =   285
         Left            =   120
         Locked          =   -1  'True
         TabIndex        =   37
         Top             =   3480
         Width           =   1695
      End
      Begin VB.TextBox Emp6Name 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "M/d/yyyy"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   3
         EndProperty
         Height          =   285
         Left            =   120
         Locked          =   -1  'True
         TabIndex        =   36
         Top             =   3000
         Width           =   1695
      End
      Begin VB.TextBox Emp5Name 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "M/d/yyyy"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   3
         EndProperty
         Height          =   285
         Left            =   120
         Locked          =   -1  'True
         TabIndex        =   35
         Top             =   2520
         Width           =   1695
      End
      Begin VB.TextBox Emp4Name 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "M/d/yyyy"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   3
         EndProperty
         Height          =   285
         Left            =   120
         Locked          =   -1  'True
         TabIndex        =   34
         Top             =   2040
         Width           =   1695
      End
      Begin VB.TextBox Emp3Name 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "M/d/yyyy"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   3
         EndProperty
         Height          =   285
         Left            =   120
         Locked          =   -1  'True
         TabIndex        =   33
         Top             =   1560
         Width           =   1695
      End
      Begin VB.TextBox Emp2Name 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "M/d/yyyy"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   3
         EndProperty
         Height          =   285
         Left            =   120
         Locked          =   -1  'True
         TabIndex        =   32
         Top             =   1080
         Width           =   1695
      End
      Begin VB.TextBox Emp1Name 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "M/d/yyyy"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   3
         EndProperty
         Height          =   285
         Left            =   120
         Locked          =   -1  'True
         TabIndex        =   31
         Top             =   600
         Width           =   1695
      End
      Begin VB.CommandButton Emp1App 
         Caption         =   "O K"
         Enabled         =   0   'False
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
         Left            =   7680
         TabIndex        =   30
         Top             =   600
         Width           =   765
      End
      Begin VB.CommandButton Emp1Deny 
         Caption         =   "Deny"
         Enabled         =   0   'False
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
         Left            =   8640
         TabIndex        =   29
         Top             =   600
         Width           =   645
      End
      Begin VB.CommandButton Emp1Mod 
         Caption         =   "View"
         Enabled         =   0   'False
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
         Left            =   9480
         TabIndex        =   28
         Top             =   600
         Width           =   645
      End
      Begin VB.CommandButton Emp2App 
         Caption         =   "O K"
         Enabled         =   0   'False
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
         Left            =   7680
         TabIndex        =   27
         Top             =   1080
         Width           =   765
      End
      Begin VB.CommandButton Emp2Deny 
         Caption         =   "Deny"
         Enabled         =   0   'False
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
         Left            =   8640
         TabIndex        =   26
         Top             =   1080
         Width           =   645
      End
      Begin VB.CommandButton Emp2Mod 
         Caption         =   "View"
         Enabled         =   0   'False
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
         Left            =   9480
         TabIndex        =   25
         Top             =   1080
         Width           =   645
      End
      Begin VB.CommandButton Emp3App 
         Caption         =   "O K"
         Enabled         =   0   'False
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
         Left            =   7680
         TabIndex        =   24
         Top             =   1560
         Width           =   765
      End
      Begin VB.CommandButton Emp3Deny 
         Caption         =   "Deny"
         Enabled         =   0   'False
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
         Left            =   8640
         TabIndex        =   23
         Top             =   1560
         Width           =   645
      End
      Begin VB.CommandButton Emp3Mod 
         Caption         =   "View"
         Enabled         =   0   'False
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
         Left            =   9480
         TabIndex        =   22
         Top             =   1560
         Width           =   645
      End
      Begin VB.CommandButton Emp4App 
         Caption         =   "O K"
         Enabled         =   0   'False
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
         Left            =   7680
         TabIndex        =   21
         Top             =   2040
         Width           =   795
      End
      Begin VB.CommandButton Emp4Deny 
         Caption         =   "Deny"
         Enabled         =   0   'False
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
         Left            =   8640
         TabIndex        =   20
         Top             =   2040
         Width           =   645
      End
      Begin VB.CommandButton Emp4Mod 
         Caption         =   "View"
         Enabled         =   0   'False
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
         Left            =   9480
         TabIndex        =   19
         Top             =   2040
         Width           =   645
      End
      Begin VB.CommandButton Emp5App 
         Caption         =   "O K"
         Enabled         =   0   'False
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
         Left            =   7680
         TabIndex        =   18
         Top             =   2520
         Width           =   765
      End
      Begin VB.CommandButton Emp5Deny 
         Caption         =   "Deny"
         Enabled         =   0   'False
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
         Left            =   8640
         TabIndex        =   17
         Top             =   2520
         Width           =   645
      End
      Begin VB.CommandButton Emp5Mod 
         Caption         =   "View"
         Enabled         =   0   'False
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
         Left            =   9480
         TabIndex        =   16
         Top             =   2520
         Width           =   645
      End
      Begin VB.CommandButton Emp6App 
         Caption         =   "O K"
         Enabled         =   0   'False
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
         Left            =   7680
         TabIndex        =   15
         Top             =   3000
         Width           =   795
      End
      Begin VB.CommandButton Emp6Deny 
         Caption         =   "Deny"
         Enabled         =   0   'False
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
         Left            =   8640
         TabIndex        =   14
         Top             =   3000
         Width           =   645
      End
      Begin VB.CommandButton Emp6Mod 
         Caption         =   "View"
         Enabled         =   0   'False
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
         Left            =   9480
         TabIndex        =   13
         Top             =   3000
         Width           =   645
      End
      Begin VB.CommandButton Emp7App 
         Caption         =   "O K"
         Enabled         =   0   'False
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
         Left            =   7680
         TabIndex        =   12
         Top             =   3480
         Width           =   795
      End
      Begin VB.CommandButton Emp7Deny 
         Caption         =   "Deny"
         Enabled         =   0   'False
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
         Left            =   8640
         TabIndex        =   11
         Top             =   3480
         Width           =   645
      End
      Begin VB.CommandButton Emp7Mod 
         Caption         =   "View"
         Enabled         =   0   'False
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
         Left            =   9480
         TabIndex        =   10
         Top             =   3480
         Width           =   645
      End
      Begin VB.CommandButton Emp8App 
         Caption         =   "O K"
         Enabled         =   0   'False
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
         Left            =   7680
         TabIndex        =   9
         Top             =   3960
         Width           =   795
      End
      Begin VB.CommandButton Emp8Deny 
         Caption         =   "Deny"
         Enabled         =   0   'False
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
         Left            =   8640
         TabIndex        =   8
         Top             =   3960
         Width           =   645
      End
      Begin VB.CommandButton Emp8Mod 
         Caption         =   "View"
         Enabled         =   0   'False
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
         Left            =   9480
         TabIndex        =   7
         Top             =   3960
         Width           =   645
      End
      Begin VB.Line Line3 
         X1              =   0
         X2              =   10440
         Y1              =   480
         Y2              =   480
      End
      Begin VB.Label Label4 
         BackColor       =   &H00C0C0C0&
         Caption         =   "Week Of"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   2160
         TabIndex        =   43
         Top             =   240
         Width           =   1815
      End
      Begin VB.Label Label8 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Deny"
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
         Left            =   8640
         TabIndex        =   42
         Top             =   240
         Width           =   420
      End
      Begin VB.Label Label7 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Approve"
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
         Left            =   7680
         TabIndex        =   41
         Top             =   240
         Width           =   630
      End
      Begin VB.Line Line1 
         X1              =   7560
         X2              =   7560
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line2 
         X1              =   1920
         X2              =   1920
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Label Date 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Employee Name"
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
         Left            =   120
         TabIndex        =   40
         Top             =   240
         Width           =   1275
      End
      Begin VB.Line Line12 
         X1              =   8520
         X2              =   8520
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line13 
         X1              =   9360
         X2              =   9360
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Label Label9 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "View"
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
         Left            =   9480
         TabIndex        =   39
         Top             =   240
         Width           =   390
      End
   End
   Begin VB.Frame Frame1 
      BackColor       =   &H00FFFFFF&
      Enabled         =   0   'False
      Height          =   2055
      Left            =   720
      TabIndex        =   0
      Top             =   120
      Width           =   10455
      Begin VB.TextBox SuperName 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   1320
         Locked          =   -1  'True
         TabIndex        =   2
         Top             =   1080
         Width           =   2175
      End
      Begin VB.TextBox Dept 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   7560
         Locked          =   -1  'True
         TabIndex        =   1
         Top             =   1080
         Width           =   2175
      End
      Begin VB.Label Label1 
         BackColor       =   &H00FFFFFF&
         Caption         =   "Conditional/Past Timesheets"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   20.25
            Charset         =   0
            Weight          =   700
            Underline       =   -1  'True
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   2640
         TabIndex        =   5
         Top             =   240
         Width           =   5175
      End
      Begin VB.Label Label2 
         BackColor       =   &H00FFFFFF&
         Caption         =   "Name"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   600
         TabIndex        =   4
         Top             =   1080
         Width           =   735
      End
      Begin VB.Label Label3 
         BackColor       =   &H00FFFFFF&
         Caption         =   "Department"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   6120
         TabIndex        =   3
         Top             =   1080
         Width           =   1215
      End
   End
End
Attribute VB_Name = "SupPastApp"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim EmpID1 As String
Dim EmpID2 As String
Dim EmpID3 As String
Dim EmpID4 As String
Dim EmpID5 As String
Dim EmpID6 As String
Dim EmpID7 As String
Dim EmpID8 As String

Public EmpList As String

Dim dsAT_AND_TSUB As Object

Public strWeekOracle As String
Public strWeekHuman As String



Private Sub form_load()

Me.Caption = "Timesheet Review -    Current User: " & strUser & "    Current Computer: " & strComputer

strSql = "SELECT FIRST_NAME || ' ' || LAST_NAME THE_NAME, DEPARTMENT_ID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strUserID & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

Me.SuperName.Text = dsSHORT_TERM_DATA.Fields("THE_NAME").Value
Me.Dept.Text = dsSHORT_TERM_DATA.Fields("DEPARTMENT_ID").Value

Call PopulateGrid

End Sub

Private Sub PopulateGrid()

' first, we get the weeks in question.  Then, we iterate 8 times for 8 rows in the GUI.
strSql = "SELECT AT.EMPLOYEE_ID, FIRST_NAME || ' ' || LAST_NAME THE_NAME, TO_CHAR(WEEK_START_MONDAY, 'MM/DD/YYYY') THE_WEEK FROM AT_EMPLOYEE AT, " _
        & "TIME_SUBMISSION TSUB WHERE AT.EMPLOYEE_ID IN " & EmpList & " AND AT.EMPLOYEE_ID = TSUB.EMPLOYEE_ID AND WEEK_START_MONDAY < '" & strWeekOracle & "' " _
        & "AND (STATUS = 'SUBMITTED' OR (STATUS = 'ON HOLD' AND CONDITIONAL_SUBMISSION = 'Y')) AND AT.EMPLOYMENT_STATUS = 'ACTIVE'" _
        & "ORDER BY FIRST_NAME || ' ' || LAST_NAME, WEEK_START_MONDAY"
Set dsAT_AND_TSUB = OraDatabase.DbCreateDynaset(strSql, 0&)

If dsAT_AND_TSUB.EOF = False Then
    Emp1Name.Text = dsAT_AND_TSUB.Fields("THE_NAME").Value
    EmpID1 = dsAT_AND_TSUB.Fields("EMPLOYEE_ID").Value
    WeekOf1.Text = dsAT_AND_TSUB.Fields("THE_WEEK").Value
    Emp1Mod.Enabled = True
    
    dsAT_AND_TSUB.Movenext
End If

If dsAT_AND_TSUB.EOF = False Then
    Emp2Name.Text = dsAT_AND_TSUB.Fields("THE_NAME").Value
    EmpID2 = dsAT_AND_TSUB.Fields("EMPLOYEE_ID").Value
    WeekOf2.Text = dsAT_AND_TSUB.Fields("THE_WEEK").Value
    Emp2Mod.Enabled = True
    
    dsAT_AND_TSUB.Movenext
End If

If dsAT_AND_TSUB.EOF = False Then
    Emp3Name.Text = dsAT_AND_TSUB.Fields("THE_NAME").Value
    EmpID3 = dsAT_AND_TSUB.Fields("EMPLOYEE_ID").Value
    WeekOf3.Text = dsAT_AND_TSUB.Fields("THE_WEEK").Value
    Emp3Mod.Enabled = True
    
    dsAT_AND_TSUB.Movenext
End If

If dsAT_AND_TSUB.EOF = False Then
    Emp4Name.Text = dsAT_AND_TSUB.Fields("THE_NAME").Value
    EmpID4 = dsAT_AND_TSUB.Fields("EMPLOYEE_ID").Value
    WeekOf4.Text = dsAT_AND_TSUB.Fields("THE_WEEK").Value
    Emp4Mod.Enabled = True
    
    dsAT_AND_TSUB.Movenext
End If

If dsAT_AND_TSUB.EOF = False Then
    Emp5Name.Text = dsAT_AND_TSUB.Fields("THE_NAME").Value
    EmpID5 = dsAT_AND_TSUB.Fields("EMPLOYEE_ID").Value
    WeekOf5.Text = dsAT_AND_TSUB.Fields("THE_WEEK").Value
    Emp5Mod.Enabled = True
    
    dsAT_AND_TSUB.Movenext
End If

If dsAT_AND_TSUB.EOF = False Then
    Emp6Name.Text = dsAT_AND_TSUB.Fields("THE_NAME").Value
    EmpID6 = dsAT_AND_TSUB.Fields("EMPLOYEE_ID").Value
    WeekOf6.Text = dsAT_AND_TSUB.Fields("THE_WEEK").Value
    Emp6Mod.Enabled = True
    
    dsAT_AND_TSUB.Movenext
End If

If dsAT_AND_TSUB.EOF = False Then
    Emp7Name.Text = dsAT_AND_TSUB.Fields("THE_NAME").Value
    EmpID7 = dsAT_AND_TSUB.Fields("EMPLOYEE_ID").Value
    WeekOf7.Text = dsAT_AND_TSUB.Fields("THE_WEEK").Value
    Emp7Mod.Enabled = True
    
    dsAT_AND_TSUB.Movenext
End If

If dsAT_AND_TSUB.EOF = False Then
    Emp8Name.Text = dsAT_AND_TSUB.Fields("THE_NAME").Value
    EmpID8 = dsAT_AND_TSUB.Fields("EMPLOYEE_ID").Value
    WeekOf8.Text = dsAT_AND_TSUB.Fields("THE_WEEK").Value
    Emp8Mod.Enabled = True
    
    dsAT_AND_TSUB.Movenext
End If

End Sub

Private Sub Emp1Mod_Click()
    Call ViewTime(EmpID1, WeekOf1.Text)
    Emp1Deny.Enabled = True
    Emp1App.Enabled = True
End Sub
Private Sub Emp2Mod_Click()
    Call ViewTime(EmpID2, WeekOf2.Text)
    Emp2Deny.Enabled = True
    Emp2App.Enabled = True
End Sub
Private Sub Emp3Mod_Click()
    Call ViewTime(EmpID3, WeekOf3.Text)
    Emp3Deny.Enabled = True
    Emp3App.Enabled = True
End Sub
Private Sub Emp4Mod_Click()
    Call ViewTime(EmpID4, WeekOf4.Text)
    Emp4Deny.Enabled = True
    Emp4App.Enabled = True
End Sub
Private Sub Emp5Mod_Click()
    Call ViewTime(EmpID5, WeekOf5.Text)
    Emp5Deny.Enabled = True
    Emp5App.Enabled = True
End Sub
Private Sub Emp6Mod_Click()
    Call ViewTime(EmpID6, WeekOf6.Text)
    Emp6Deny.Enabled = True
    Emp6App.Enabled = True
End Sub
Private Sub Emp7Mod_Click()
    Call ViewTime(EmpID7, WeekOf7.Text)
    Emp7Deny.Enabled = True
    Emp7App.Enabled = True
End Sub
Private Sub Emp8Mod_Click()
    Call ViewTime(EmpID8, WeekOf8.Text)
    Emp8Deny.Enabled = True
    Emp8App.Enabled = True
End Sub

Public Function ViewTime(empID As String, strWeek As String) As Boolean
Dim TimeSheet As New frmPrint

TimeSheet.strEmployeeID = empID
TimeSheet.strWeek = Format(strWeek, "dd-mmm-yyyy")
TimeSheet.Show 1

' Call form_load

End Function
Private Sub Emp1Deny_Click()
    Call Deny_routine(EmpID1, WeekOf1.Text, Emp1App, Emp1Deny, Emp1Mod, Emp1Name, WeekOf1)
End Sub
Private Sub Emp2Deny_Click()
    Call Deny_routine(EmpID2, WeekOf2.Text, Emp2App, Emp2Deny, Emp2Mod, Emp2Name, WeekOf2)
End Sub
Private Sub Emp3Deny_Click()
    Call Deny_routine(EmpID3, WeekOf3.Text, Emp3App, Emp3Deny, Emp3Mod, Emp3Name, WeekOf3)
End Sub
Private Sub Emp4Deny_Click()
    Call Deny_routine(EmpID4, WeekOf4.Text, Emp4App, Emp4Deny, Emp4Mod, Emp4Name, WeekOf4)
End Sub
Private Sub Emp5Deny_Click()
    Call Deny_routine(EmpID5, WeekOf5.Text, Emp5App, Emp5Deny, Emp5Mod, Emp5Name, WeekOf5)
End Sub
Private Sub Emp6Deny_Click()
    Call Deny_routine(EmpID6, WeekOf6.Text, Emp6App, Emp6Deny, Emp6Mod, Emp6Name, WeekOf6)
End Sub
Private Sub Emp7Deny_Click()
    Call Deny_routine(EmpID7, WeekOf7.Text, Emp7App, Emp7Deny, Emp7Mod, Emp7Name, WeekOf7)
End Sub
Private Sub Emp8Deny_Click()
    Call Deny_routine(EmpID8, WeekOf8.Text, Emp8App, Emp8Deny, Emp8Mod, Emp8Name, WeekOf8)
End Sub

Private Sub Deny_routine(empID As String, WeekOf As String, App As CommandButton, Deny As CommandButton, View As CommandButton, Name As TextBox, Week As TextBox)
' If someone hits a deny button...
' short and simple.


    strSql = "UPDATE TIME_SUBMISSION SET STATUS = 'DENIED', CONDITIONAL_SUBMISSION = 'Y' WHERE EMPLOYEE_ID = '" _
            & empID & "' AND WEEK_START_MONDAY = to_date('" & WeekOf & "', 'MM/DD/YYYY')"
    OraDatabase.ExecuteSQL (strSql)

    strSql = "INSERT INTO TIME_SUB_HISTORY (SELECT TS.*, SYSDATE, 'Denied - By " & strUser & "' FROM TIME_SUBMISSION TS WHERE " _
            & "EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = to_date('" & WeekOf & "', 'MM/DD/YYYY'))"
    OraDatabase.ExecuteSQL (strSql)
    
    App.Enabled = False
    Deny.Enabled = False
    View.Enabled = False
    Name.Enabled = False
    Week.Enabled = False
    
End Sub

Private Sub Emp1App_Click()
    Call App_routine(EmpID1, WeekOf1.Text, Emp1App, Emp1Deny, Emp1Mod, Emp1Name, WeekOf1)
End Sub
Private Sub Emp2App_Click()
    Call App_routine(EmpID2, WeekOf2.Text, Emp2App, Emp2Deny, Emp2Mod, Emp2Name, WeekOf2)
End Sub
Private Sub Emp3App_Click()
    Call App_routine(EmpID3, WeekOf3.Text, Emp3App, Emp3Deny, Emp3Mod, Emp3Name, WeekOf3)
End Sub
Private Sub Emp4App_Click()
    Call App_routine(EmpID4, WeekOf4.Text, Emp4App, Emp4Deny, Emp4Mod, Emp4Name, WeekOf4)
End Sub
Private Sub Emp5App_Click()
    Call App_routine(EmpID5, WeekOf5.Text, Emp5App, Emp5Deny, Emp5Mod, Emp5Name, WeekOf5)
End Sub
Private Sub Emp6App_Click()
    Call App_routine(EmpID6, WeekOf6.Text, Emp6App, Emp6Deny, Emp6Mod, Emp6Name, WeekOf6)
End Sub
Private Sub Emp7App_Click()
    Call App_routine(EmpID7, WeekOf7.Text, Emp7App, Emp7Deny, Emp7Mod, Emp7Name, WeekOf7)
End Sub
Private Sub Emp8App_Click()
    Call App_routine(EmpID8, WeekOf8.Text, Emp8App, Emp8Deny, Emp8Mod, Emp8Name, WeekOf8)
End Sub

Private Sub App_routine(empID As String, WeekOf As String, App As CommandButton, Deny As CommandButton, View As CommandButton, Name As TextBox, Week As TextBox)
' If someone hits an approve button...
' First, update the DB with the new status, then call the function "timeoff_totals" to post-calculate the... time off totals.

Dim strStatus As String

    strSql = "UPDATE TIME_SUBMISSION SET SIGN_OFF_EMPID = '" & strUserID & "', SIGN_OFF_PC_USERID = '" & strUser & "', SIGN_OFF_PC = '" _
            & strComputer & "', SIGN_OFF_DATETIME = SYSDATE WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = to_date('" & WeekOf & "', 'MM/DD/YYYY')"
    OraDatabase.ExecuteSQL (strSql)
    
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = to_date('" & WeekOf & "', 'MM/DD/YYYY')"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    strStatus = dsSHORT_TERM_DATA.Fields("STATUS").Value
    
    
    OraSession.BeginTrans
    dsSHORT_TERM_DATA.Edit
    dsSHORT_TERM_DATA.Fields("STATUS").Value = "APPROVED"
    
    If InStr(1, strStatus, "ON HOLD") <> 0 Then
            dsSHORT_TERM_DATA.Fields("CONDITIONAL_SUBMISSION").Value = "Y"
    ElseIf InStr(1, strStatus, "SUBMITTED") <> 0 Then
            dsSHORT_TERM_DATA.Fields("CONDITIONAL_SUBMISSION").Value = "N"
    End If
    
    dsSHORT_TERM_DATA.Update
    OraSession.CommitTrans
    
'    strSql = "UPDATE TIME_SUBMISSION SET STATUS = 'APPROVED', CONDITIONAL_SUBMISSION = 'N' WHERE EMPLOYEE_ID = '" _
'            & empID & "' AND WEEK_START_MONDAY = to_date('" & WeekOf & "', 'MM/DD/YYYY')"
'    OraDatabase.ExecuteSQL (strSql)
    
'        IMPORTANT UPDATE:  all YTD_END calculations are now being done in theADP script.  timeoff_totals no longer called.
'    Call timeoff_totals(empID)

    App.Enabled = False
    Deny.Enabled = False
    View.Enabled = False
    Name.Enabled = False
    Week.Enabled = False

End Sub

Private Sub timeoff_totals(empID As String)
' This function sees what the earliest week for employee <empID> is with no values in the "YTD_END" fields.  If, for that week,
' the status is "approved" and the conditional_submission is "N", it calculates the "YTD_END" (and the YTD_START)
' values for that week, and then checks the next week.  If it hits either A) every single record with no YTD_END values, or
' B) a record with no YTD_END values but whose status isn't "approved" OR whose conditional_submission is 'Y', the function ends.

' Please note: What I am doing (checking every record every time) is logically equivalent to saying "find the first week prior
' to the approved week and work from there", the only difference is, my way reads every previous entry and discards it, the
' other way first determines where to start, and ignores previous weeks.  My way is simpler logic, the other way is less
' DB overhead; but unless the size of TIME_SUBMISSION grows to number in 7-figures, my overhead is inconsequential, and
' Therefore simpler coding logic is the way I'm going.

' Also note:  since this function is guaranteed to be working on previous weeks, AND the current week is guaranteed NOT
' "Approved" by virtue of being in this form for this employee, I can safely re-calculate the "YTD_START" values for the
' Week ---after--- each iteration of the loop, since I know said week will always be present (since this function will
' Never work for the "current" week due to it's non-approved status.)

Dim dsMAIN_RECORD As Object
Dim dsUPDATE_RECORDS As Object
Dim dsDATA_FROM_AT_EMPLOYEE As Object

Dim strNextWeek As String
Dim strThisWeek As String

Dim NewVacValue As Double
Dim NewSickValue As Double
Dim NewPersValue As Double
Dim NewRegValue As Double
Dim NewTotalValue As Double
Dim NewHolidayValue As Double

Dim NewVacBal As Double
Dim NewSickBal As Double
Dim NewPersBal As Double

Dim WeekEndOt As Double
Dim WeekEndOtWeeks As Double
Dim WeekEndOtAvg As Double


While True ' yes, I am setting up an infinite loop.  The first-case breakpoint is it's escape.

    ' grab the earliest record in the DB.
    ' techincally, I'm grabbing ALL values in DB, but since I'm ORDER-BY on WEEK_START_MONDAY, and after this pass
    ' of the loop is over I'm re-defining dsMAIN_RECORD anyway, all is well.
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & empID & "' AND YTD_WEEK_END_VACATION_BAL IS NULL " _
            & "ORDER BY WEEK_START_MONDAY ASC"
    Set dsMAIN_RECORD = OraDatabase.DbCreateDynaset(strSql, 0&)

    If dsMAIN_RECORD.EOF = True Then
        ' no record, leave subroutine
        Exit Sub
    End If

    If dsMAIN_RECORD.Fields("STATUS").Value <> "APPROVED" Or dsMAIN_RECORD.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
        ' earliest record isn't suitable for calculation, leave subroutine
        ' also note this is how we avoid operating on "current" week, since "current" week is never approved
        ' by virture of, if it WERE approved, this empID wouldn't be in the "past approval" screen.
        Exit Sub
    End If


    ' ok, if we are this far, time to calculate the YTD_END values for the current week, and thereby, the new
    ' YTD_START values for the next week, as well as the total values for AT_EMPLOYEE.
    
    ' step 1:  copy the NEXT week out into the historical table, in case of audits.
    ' No need to copy current week out, since no data is being changed in current week, just added.
    ' also, since I at this point define strThisWeek, I can add the "sign off" data to the record
    strThisWeek = Format(dsMAIN_RECORD.Fields("WEEK_START_MONDAY").Value, "dd-mmm-yyyy")
    strNextWeek = DateAdd("d", 7, dsMAIN_RECORD.Fields("WEEK_START_MONDAY").Value)
    strNextWeek = Format(strNextWeek, "dd-mmm-yyyy")
    
    strSql = "INSERT INTO TIME_SUB_HISTORY (SELECT TS.*, SYSDATE, '" & strUser & " - Past TS approval' FROM TIME_SUBMISSION TS " _
            & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & strNextWeek & "')"
    OraDatabase.ExecuteSQL (strSql)
    
    ' add approval information
    strSql = "UPDATE TIME_SUBMISSION SET SIGN_OFF_EMPID = '" & strUserID & "', SIGN_OFF_PC_USERID = '" & strUser & "', SIGN_OFF_PC = '" _
            & strComputer & "', SIGN_OFF_DATETIME = SYSDATE WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & strThisWeek & "'"
    OraDatabase.ExecuteSQL (strSql)
    
    
    ' step 2:  calculate current week's YTD_END and balance values for sick, pers, vac, and OT total/avg.
    NewVacValue = dsMAIN_RECORD.Fields("YTD_WEEK_START_TOTAL_VACATION").Value + dsMAIN_RECORD.Fields("WEEK_TOTAL_VACATION").Value
    NewSickValue = dsMAIN_RECORD.Fields("YTD_WEEK_START_TOTAL_SICK").Value + dsMAIN_RECORD.Fields("WEEK_TOTAL_SICK").Value
    NewPersValue = dsMAIN_RECORD.Fields("YTD_WEEK_START_TOTAL_PERSONAL").Value + dsMAIN_RECORD.Fields("WEEK_TOTAL_PERSONAL").Value
    NewRegValue = dsMAIN_RECORD.Fields("YTD_WEEK_START_TOTAL_REG").Value + dsMAIN_RECORD.Fields("WEEK_TOTAL_REG").Value
    NewTotalValue = dsMAIN_RECORD.Fields("YTD_WEEK_START_TOTAL_TOTAL").Value + dsMAIN_RECORD.Fields("WEEK_TOTAL_TOTAL").Value
    NewHolidayValue = dsMAIN_RECORD.Fields("YTD_WEEK_START_TOTAL_HOLIDAY").Value + dsMAIN_RECORD.Fields("WEEK_TOTAL_HOLIDAY").Value
    
    NewVacBal = Val("" & dsMAIN_RECORD.Fields("YTD_WEEK_START_VACATION_BAL").Value) + Val("" & dsMAIN_RECORD.Fields("WEEKLY_ACCRUAL_VACATION").Value) - Val("" & dsMAIN_RECORD.Fields("WEEK_TOTAL_VACATION").Value)
    NewSickBal = Val("" & dsMAIN_RECORD.Fields("YTD_WEEK_START_SICK_BAL").Value) + Val("" & dsMAIN_RECORD.Fields("WEEKLY_ACCRUAL_SICK").Value) - Val("" & dsMAIN_RECORD.Fields("WEEK_TOTAL_SICK").Value)
    NewPersBal = Val("" & dsMAIN_RECORD.Fields("YTD_WEEK_START_PERSONAL_BAL").Value) - Val("" & dsMAIN_RECORD.Fields("WEEK_TOTAL_PERSONAL").Value)
    
    ' for this next SQL, I don't need to include the checks of Approval and conditional_submission status, since all weeks
    ' prior to the one in operation are guaranteed approved and "N" by virtue of this semi-recursive function
    ' Also, count(*) will never be 0 (leading to a divide by zero error later) since we have at least 1 record, the
    ' one we are operating on.
    strSql = "SELECT NVL(SUM(WEEK_TOTAL_OVERTIME), 0) THE_SUM, COUNT(*) THE_COUNT FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & empID & "' AND " _
            & "WEEK_START_MONDAY <= '" & strThisWeek & "' AND " _
            & "WEEK_START_MONDAY >= '01-JAN-" & Right(strThisWeek, 4) & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    WeekEndOt = dsSHORT_TERM_DATA.Fields("THE_SUM").Value
    WeekEndOtWeeks = dsSHORT_TERM_DATA.Fields("THE_COUNT").Value
    WeekEndOtAvg = WeekEndOt / WeekEndOtWeeks
    
    
    ' step 3: edit AT_EMPLOYEE with new values for pers / vac / sick
    strSql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & empID & "'"
    Set dsDATA_FROM_AT_EMPLOYEE = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    OraSession.BeginTrans
    dsDATA_FROM_AT_EMPLOYEE.Edit
    dsDATA_FROM_AT_EMPLOYEE.Fields("VACATION_YTD_ACCRUED").Value = Val("" & dsDATA_FROM_AT_EMPLOYEE.Fields("VACATION_YTD_ACCRUED").Value) + Val("" & dsMAIN_RECORD.Fields("WEEKLY_ACCRUAL_VACATION").Value)
    dsDATA_FROM_AT_EMPLOYEE.Fields("SICK_YTD_ACCRUED").Value = Val("" & dsDATA_FROM_AT_EMPLOYEE.Fields("SICK_YTD_ACCRUED").Value) + Val("" & dsMAIN_RECORD.Fields("WEEKLY_ACCRUAL_SICK").Value)
    dsDATA_FROM_AT_EMPLOYEE.Fields("VACATION_YTD_TAKEN").Value = Val("" & dsDATA_FROM_AT_EMPLOYEE.Fields("VACATION_YTD_TAKEN").Value) + Val("" & dsMAIN_RECORD.Fields("WEEK_TOTAL_VACATION").Value)
    dsDATA_FROM_AT_EMPLOYEE.Fields("SICK_YTD_TAKEN").Value = Val("" & dsDATA_FROM_AT_EMPLOYEE.Fields("SICK_YTD_TAKEN").Value) + Val("" & dsMAIN_RECORD.Fields("WEEK_TOTAL_SICK").Value)
    dsDATA_FROM_AT_EMPLOYEE.Fields("PERSONAL_YTD_TAKEN").Value = Val("" & dsDATA_FROM_AT_EMPLOYEE.Fields("PERSONAL_YTD_TAKEN").Value) + Val("" & dsMAIN_RECORD.Fields("WEEK_TOTAL_PERSONAL").Value)
    dsDATA_FROM_AT_EMPLOYEE.Fields("VACATION_YTD_REMAIN").Value = Val("" & NewVacBal)
    dsDATA_FROM_AT_EMPLOYEE.Fields("SICK_YTD_REMAIN").Value = Val("" & NewSickBal)
    dsDATA_FROM_AT_EMPLOYEE.Fields("PERSONAL_YTD_REMAIN").Value = Val("" & NewPersBal)
    dsDATA_FROM_AT_EMPLOYEE.Update
    OraSession.CommitTrans
    
    
    ' step 4:  edit NEXT week with values.
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & strNextWeek & "'"
    Set dsUPDATE_RECORDS = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsUPDATE_RECORDS.RecordCount > 0 Then
        ' if employee's next week TS is already created, update that week's YTD_START values appropriately.
        OraSession.BeginTrans
        dsUPDATE_RECORDS.Edit
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_TOTAL_TOTAL").Value = NewTotalValue
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_TOTAL_REG").Value = NewRegValue
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_TOTAL_HOLIDAY").Value = NewHolidayValue
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_TOTAL_VACATION").Value = NewVacValue
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_TOTAL_PERSONAL").Value = NewPersValue
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_TOTAL_SICK").Value = NewSickValue
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_TOTAL_OVERTIME").Value = WeekEndOt
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_AVERAGE_OT").Value = WeekEndOtAvg
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_VACATION_BAL").Value = NewVacBal
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_PERSONAL_BAL").Value = NewPersBal
        dsUPDATE_RECORDS.Fields("YTD_WEEK_START_SICK_BAL").Value = NewSickBal
        dsUPDATE_RECORDS.Update
        OraSession.CommitTrans
    End If
    
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & strThisWeek & "'"
    Set dsMAIN_RECORD = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    ' step 5:  edit current week.
    OraSession.BeginTrans
    dsMAIN_RECORD.Edit
    dsMAIN_RECORD.Fields("YTD_WEEK_END_TOTAL_TOTAL").Value = NewTotalValue
    dsMAIN_RECORD.Fields("YTD_WEEK_END_TOTAL_REG").Value = NewRegValue
    dsMAIN_RECORD.Fields("YTD_WEEK_END_TOTAL_HOLIDAY").Value = NewHolidayValue
    dsMAIN_RECORD.Fields("YTD_WEEK_END_TOTAL_VACATION").Value = NewVacValue
    dsMAIN_RECORD.Fields("YTD_WEEK_END_TOTAL_PERSONAL").Value = NewPersValue
    dsMAIN_RECORD.Fields("YTD_WEEK_END_TOTAL_SICK").Value = NewSickValue
    dsMAIN_RECORD.Fields("YTD_WEEK_END_TOTAL_OVERTIME").Value = WeekEndOt
    dsMAIN_RECORD.Fields("YTD_WEEK_END_AVERAGE_OT").Value = WeekEndOtAvg
    dsMAIN_RECORD.Fields("YTD_WEEK_END_VACATION_BAL").Value = NewVacBal
    dsMAIN_RECORD.Fields("YTD_WEEK_END_PERSONAL_BAL").Value = NewPersBal
    dsMAIN_RECORD.Fields("YTD_WEEK_END_SICK_BAL").Value = NewSickBal
    dsMAIN_RECORD.Update
    OraSession.CommitTrans

Wend
    
End Sub
