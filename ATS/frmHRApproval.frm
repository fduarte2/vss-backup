VERSION 5.00
Begin VB.Form frmHRApproval 
   BackColor       =   &H00FFFFFF&
   Caption         =   "HR Override Approval"
   ClientHeight    =   8145
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   10500
   LinkTopic       =   "Form1"
   ScaleHeight     =   8145
   ScaleWidth      =   10500
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdMoreSheets 
      Caption         =   "More Timesheets"
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
      Left            =   3480
      TabIndex        =   50
      Top             =   7320
      Width           =   3495
   End
   Begin VB.Frame Frame1 
      BackColor       =   &H00FFFFFF&
      Enabled         =   0   'False
      Height          =   2055
      Left            =   840
      TabIndex        =   46
      Top             =   240
      Width           =   8535
      Begin VB.TextBox WeekOfTime 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   285
         Left            =   4680
         Locked          =   -1  'True
         TabIndex        =   47
         Top             =   1080
         Width           =   1815
      End
      Begin VB.Label Label3 
         BackColor       =   &H00FFFFFF&
         Caption         =   "Week of:"
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
         Left            =   2400
         TabIndex        =   49
         Top             =   1080
         Width           =   1215
      End
      Begin VB.Label Label1 
         BackColor       =   &H00FFFFFF&
         Caption         =   "HR Override Approval"
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
         Left            =   2400
         TabIndex        =   48
         Top             =   240
         Width           =   4335
      End
   End
   Begin VB.Frame frmHours 
      BackColor       =   &H00C0C0C0&
      Height          =   4455
      Left            =   840
      TabIndex        =   0
      Top             =   2520
      Width           =   8535
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
         Left            =   7320
         TabIndex        =   40
         Top             =   3960
         Width           =   1005
      End
      Begin VB.CommandButton Emp8STD 
         Caption         =   "40 Hours"
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
         Left            =   5880
         TabIndex        =   39
         Top             =   3960
         Width           =   1125
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
         Left            =   4920
         TabIndex        =   38
         Top             =   3960
         Width           =   795
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
         Left            =   7320
         TabIndex        =   37
         Top             =   3480
         Width           =   1005
      End
      Begin VB.CommandButton Emp7STD 
         Caption         =   "40 Hours"
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
         Left            =   5880
         TabIndex        =   36
         Top             =   3480
         Width           =   1125
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
         Left            =   4920
         TabIndex        =   35
         Top             =   3480
         Width           =   795
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
         Left            =   7320
         TabIndex        =   34
         Top             =   3000
         Width           =   1005
      End
      Begin VB.CommandButton Emp6STD 
         Caption         =   "40 Hours"
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
         Left            =   5880
         TabIndex        =   33
         Top             =   3000
         Width           =   1125
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
         Left            =   4920
         TabIndex        =   32
         Top             =   3000
         Width           =   795
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
         Left            =   7320
         TabIndex        =   31
         Top             =   2520
         Width           =   1005
      End
      Begin VB.CommandButton Emp5STD 
         Caption         =   "40 Hours"
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
         Left            =   5880
         TabIndex        =   30
         Top             =   2520
         Width           =   1125
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
         Left            =   4920
         TabIndex        =   29
         Top             =   2520
         Width           =   765
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
         Left            =   7320
         TabIndex        =   28
         Top             =   2040
         Width           =   1005
      End
      Begin VB.CommandButton Emp4STD 
         Caption         =   "40 Hours"
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
         Left            =   5880
         TabIndex        =   27
         Top             =   2040
         Width           =   1125
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
         Left            =   4920
         TabIndex        =   26
         Top             =   2040
         Width           =   795
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
         Left            =   7320
         TabIndex        =   25
         Top             =   1560
         Width           =   1005
      End
      Begin VB.CommandButton Emp3STD 
         Caption         =   "40 Hours"
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
         Left            =   5880
         TabIndex        =   24
         Top             =   1560
         Width           =   1125
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
         Left            =   4920
         TabIndex        =   23
         Top             =   1560
         Width           =   765
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
         Left            =   7320
         TabIndex        =   22
         Top             =   1080
         Width           =   1005
      End
      Begin VB.CommandButton Emp2STD 
         Caption         =   "40 Hours"
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
         Left            =   5880
         TabIndex        =   21
         Top             =   1080
         Width           =   1125
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
         Left            =   4920
         TabIndex        =   20
         Top             =   1080
         Width           =   765
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
         Left            =   7320
         TabIndex        =   19
         Top             =   600
         Width           =   1005
      End
      Begin VB.CommandButton Emp1STD 
         Caption         =   "40 Hours"
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
         Left            =   5880
         TabIndex        =   18
         Top             =   600
         Width           =   1125
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
         Left            =   4920
         TabIndex        =   17
         Top             =   600
         Width           =   765
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
         TabIndex        =   16
         Top             =   600
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
         TabIndex        =   15
         Top             =   1080
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
         TabIndex        =   14
         Top             =   1560
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
         TabIndex        =   13
         Top             =   2040
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
         TabIndex        =   12
         Top             =   2520
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
         TabIndex        =   11
         Top             =   3000
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
         TabIndex        =   10
         Top             =   3480
         Width           =   1695
      End
      Begin VB.TextBox Emp8Name 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   120
         Locked          =   -1  'True
         TabIndex        =   9
         Tag             =   "NInvoice Number"
         Top             =   3960
         Width           =   1695
      End
      Begin VB.TextBox Status1 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   8
         Top             =   600
         Width           =   2415
      End
      Begin VB.TextBox Status2 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   7
         Top             =   1080
         Width           =   2415
      End
      Begin VB.TextBox Status3 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   6
         Top             =   1560
         Width           =   2415
      End
      Begin VB.TextBox Status4 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   5
         Top             =   2040
         Width           =   2415
      End
      Begin VB.TextBox Status5 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   4
         Top             =   2520
         Width           =   2415
      End
      Begin VB.TextBox Status6 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   3
         Top             =   3000
         Width           =   2415
      End
      Begin VB.TextBox Status7 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   2
         Top             =   3480
         Width           =   2415
      End
      Begin VB.TextBox Status8 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   2160
         Locked          =   -1  'True
         TabIndex        =   1
         Top             =   3960
         Width           =   2415
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
         Left            =   7620
         TabIndex        =   45
         Top             =   240
         Width           =   390
      End
      Begin VB.Line Line13 
         X1              =   7200
         X2              =   7200
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line12 
         X1              =   5760
         X2              =   5760
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
         TabIndex        =   44
         Top             =   240
         Width           =   1275
      End
      Begin VB.Line Line2 
         X1              =   1920
         X2              =   1920
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line1 
         X1              =   4800
         X2              =   4800
         Y1              =   120
         Y2              =   4440
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
         Left            =   4920
         TabIndex        =   43
         Top             =   240
         Width           =   630
      End
      Begin VB.Label Label8 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Standard 40"
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
         Left            =   5880
         TabIndex        =   42
         Top             =   240
         Width           =   945
      End
      Begin VB.Label Label4 
         BackColor       =   &H00C0C0C0&
         BackStyle       =   0  'Transparent
         Caption         =   "Status"
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
         TabIndex        =   41
         Top             =   240
         Width           =   1815
      End
      Begin VB.Line Line3 
         X1              =   0
         X2              =   8520
         Y1              =   480
         Y2              =   480
      End
   End
End
Attribute VB_Name = "frmHRApproval"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim OraTime As String
Dim PrintTime As String
Dim dsHR_OVERRIDE As Object
Dim EmpID1 As String
Dim EmpID2 As String
Dim EmpID3 As String
Dim EmpID4 As String
Dim EmpID5 As String
Dim EmpID6 As String
Dim EmpID7 As String
Dim EmpID8 As String
Dim isMoreEmp As Boolean




Private Sub ClearAllFields()

Emp1Name.Text = ""
Emp2Name.Text = ""
Emp3Name.Text = ""
Emp4Name.Text = ""
Emp5Name.Text = ""
Emp6Name.Text = ""
Emp7Name.Text = ""
Emp8Name.Text = ""

Status1.Text = ""
Status2.Text = ""
Status3.Text = ""
Status4.Text = ""
Status5.Text = ""
Status6.Text = ""
Status7.Text = ""
Status8.Text = ""

Emp1App.Enabled = False
Emp2App.Enabled = False
Emp3App.Enabled = False
Emp4App.Enabled = False
Emp5App.Enabled = False
Emp6App.Enabled = False
Emp7App.Enabled = False
Emp8App.Enabled = False

Emp1STD.Enabled = False
Emp2STD.Enabled = False
Emp3STD.Enabled = False
Emp4STD.Enabled = False
Emp5STD.Enabled = False
Emp6STD.Enabled = False
Emp7STD.Enabled = False
Emp8STD.Enabled = False

Emp1Mod.Enabled = False
Emp2Mod.Enabled = False
Emp3Mod.Enabled = False
Emp4Mod.Enabled = False
Emp5Mod.Enabled = False
Emp6Mod.Enabled = False
Emp7Mod.Enabled = False
Emp8Mod.Enabled = False

isMoreEmp = False

End Sub

Private Sub cmdMoreSheets_Click()
    Call form_load
End Sub

Private Sub form_load()

Call ClearAllFields

Me.Caption = "HR Emergency Timesheet Approvals -    Current User: " & strUser & "    Current Computer: " & strComputer


strSql = "SELECT TO_CHAR(NEXT_DAY(TRUNC(SYSDATE-11) -(17/24), 'MONDAY'), 'dd-mon-yyyy') ORA_TIME, TO_CHAR(NEXT_DAY(TRUNC(SYSDATE-11) -(17/24), 'MONDAY'), 'MM/DD/YYYY') PRINT_TIME FROM DUAL"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
OraTime = dsSHORT_TERM_DATA.Fields("ORA_TIME").Value
PrintTime = dsSHORT_TERM_DATA.Fields("PRINT_TIME").Value

WeekOfTime.Text = PrintTime

Call Populate_Grid
Call Activate_more_button

End Sub

Private Sub Populate_Grid()

strSql = "SELECT AT.EMPLOYEE_ID, FIRST_NAME || ' ' || LAST_NAME THE_NAME, DECODE(CONDITIONAL_SUBMISSION, 'Y', 'CONDITIONAL - ' || STATUS, STATUS) THE_STATUS FROM AT_EMPLOYEE AT, " _
        & "TIME_SUBMISSION TSUB WHERE AT.EMPLOYEE_ID = TSUB.EMPLOYEE_ID AND WEEK_START_MONDAY = '" & OraTime & "' " _
        & "AND (STATUS = 'SUBMITTED' OR (STATUS = 'ON HOLD' AND CONDITIONAL_SUBMISSION = 'Y')) AND AT.EMPLOYMENT_STATUS = 'ACTIVE'" _
        & "ORDER BY FIRST_NAME || ' ' || LAST_NAME"
Set dsHR_OVERRIDE = OraDatabase.DbCreateDynaset(strSql, 0&)

If dsHR_OVERRIDE.EOF = False Then
    Emp1Name.Text = dsHR_OVERRIDE.Fields("THE_NAME").Value
    Status1.Text = dsHR_OVERRIDE.Fields("THE_STATUS").Value
    EmpID1 = dsHR_OVERRIDE.Fields("EMPLOYEE_ID").Value
    Emp1Mod.Enabled = True
    Emp1Name.Enabled = True
    Status1.Enabled = True
    
    dsHR_OVERRIDE.Movenext
End If

If dsHR_OVERRIDE.EOF = False Then
    Emp2Name.Text = dsHR_OVERRIDE.Fields("THE_NAME").Value
    Status2.Text = dsHR_OVERRIDE.Fields("THE_STATUS").Value
    EmpID2 = dsHR_OVERRIDE.Fields("EMPLOYEE_ID").Value
    Emp2Mod.Enabled = True
    Emp2Name.Enabled = True
    Status2.Enabled = True
    
    dsHR_OVERRIDE.Movenext
End If

If dsHR_OVERRIDE.EOF = False Then
    Emp3Name.Text = dsHR_OVERRIDE.Fields("THE_NAME").Value
    Status3.Text = dsHR_OVERRIDE.Fields("THE_STATUS").Value
    EmpID3 = dsHR_OVERRIDE.Fields("EMPLOYEE_ID").Value
    Emp3Mod.Enabled = True
    Emp3Name.Enabled = True
    Status3.Enabled = True
    
    dsHR_OVERRIDE.Movenext
End If

If dsHR_OVERRIDE.EOF = False Then
    Emp4Name.Text = dsHR_OVERRIDE.Fields("THE_NAME").Value
    Status4.Text = dsHR_OVERRIDE.Fields("THE_STATUS").Value
    EmpID4 = dsHR_OVERRIDE.Fields("EMPLOYEE_ID").Value
    Emp4Mod.Enabled = True
    Emp4Name.Enabled = True
    Status4.Enabled = True
    
    dsHR_OVERRIDE.Movenext
End If

If dsHR_OVERRIDE.EOF = False Then
    Emp5Name.Text = dsHR_OVERRIDE.Fields("THE_NAME").Value
    Status5.Text = dsHR_OVERRIDE.Fields("THE_STATUS").Value
    EmpID5 = dsHR_OVERRIDE.Fields("EMPLOYEE_ID").Value
    Emp5Mod.Enabled = True
    Emp5Name.Enabled = True
    Status5.Enabled = True
    
    dsHR_OVERRIDE.Movenext
End If

If dsHR_OVERRIDE.EOF = False Then
    Emp6Name.Text = dsHR_OVERRIDE.Fields("THE_NAME").Value
    Status6.Text = dsHR_OVERRIDE.Fields("THE_STATUS").Value
    EmpID6 = dsHR_OVERRIDE.Fields("EMPLOYEE_ID").Value
    Emp6Mod.Enabled = True
    Emp6Name.Enabled = True
    Status6.Enabled = True
    
    dsHR_OVERRIDE.Movenext
End If

If dsHR_OVERRIDE.EOF = False Then
    Emp7Name.Text = dsHR_OVERRIDE.Fields("THE_NAME").Value
    Status7.Text = dsHR_OVERRIDE.Fields("THE_STATUS").Value
    EmpID7 = dsHR_OVERRIDE.Fields("EMPLOYEE_ID").Value
    Emp7Mod.Enabled = True
    Emp7Name.Enabled = True
    Status7.Enabled = True
    
    dsHR_OVERRIDE.Movenext
End If

If dsHR_OVERRIDE.EOF = False Then
    Emp8Name.Text = dsHR_OVERRIDE.Fields("THE_NAME").Value
    Status8.Text = dsHR_OVERRIDE.Fields("THE_STATUS").Value
    EmpID8 = dsHR_OVERRIDE.Fields("EMPLOYEE_ID").Value
    Emp8Mod.Enabled = True
    Emp8Name.Enabled = True
    Status8.Enabled = True
    
    dsHR_OVERRIDE.Movenext
End If

If dsHR_OVERRIDE.EOF = False Then
    isMoreEmp = True
Else
    isMoreEmp = False
End If


End Sub

Private Sub Emp1Mod_Click()
    Call ViewTime(EmpID1)
    Emp1STD.Enabled = True
    Emp1App.Enabled = True
End Sub
Private Sub Emp2Mod_Click()
    Call ViewTime(EmpID2)
    Emp2STD.Enabled = True
    Emp2App.Enabled = True
End Sub
Private Sub Emp3Mod_Click()
    Call ViewTime(EmpID3)
    Emp3STD.Enabled = True
    Emp3App.Enabled = True
End Sub
Private Sub Emp4Mod_Click()
    Call ViewTime(EmpID4)
    Emp4STD.Enabled = True
    Emp4App.Enabled = True
End Sub
Private Sub Emp5Mod_Click()
    Call ViewTime(EmpID5)
    Emp5STD.Enabled = True
    Emp5App.Enabled = True
End Sub
Private Sub Emp6Mod_Click()
    Call ViewTime(EmpID6)
    Emp6STD.Enabled = True
    Emp6App.Enabled = True
End Sub
Private Sub Emp7Mod_Click()
    Call ViewTime(EmpID7)
    Emp7STD.Enabled = True
    Emp7App.Enabled = True
End Sub
Private Sub Emp8Mod_Click()
    Call ViewTime(EmpID8)
    Emp8STD.Enabled = True
    Emp8App.Enabled = True
End Sub

Public Function ViewTime(empID As String) As Boolean
Dim TimeSheet As New frmPrint

TimeSheet.strEmployeeID = empID
TimeSheet.strWeek = OraTime
TimeSheet.Show 1

End Function

Private Sub Emp1STD_Click()
    Call STD_routine(EmpID1, Status1, Emp1App, Emp1STD, Emp1Mod, Emp1Name)
End Sub
Private Sub Emp2STD_Click()
    Call STD_routine(EmpID2, Status2, Emp2App, Emp2STD, Emp2Mod, Emp2Name)
End Sub
Private Sub Emp3STD_Click()
    Call STD_routine(EmpID3, Status3, Emp3App, Emp3STD, Emp3Mod, Emp3Name)
End Sub
Private Sub Emp4STD_Click()
    Call STD_routine(EmpID4, Status4, Emp4App, Emp4STD, Emp4Mod, Emp4Name)
End Sub
Private Sub Emp5STD_Click()
    Call STD_routine(EmpID5, Status5, Emp5App, Emp5STD, Emp5Mod, Emp5Name)
End Sub
Private Sub Emp6STD_Click()
    Call STD_routine(EmpID6, Status6, Emp6App, Emp6STD, Emp6Mod, Emp6Name)
End Sub
Private Sub Emp7STD_Click()
    Call STD_routine(EmpID7, Status7, Emp7App, Emp7STD, Emp7Mod, Emp7Name)
End Sub
Private Sub Emp8STD_Click()
    Call STD_routine(EmpID8, Status8, Emp8App, Emp8STD, Emp8Mod, Emp8Name)
End Sub

Private Sub STD_routine(empID As String, status As TextBox, App As CommandButton, STD As CommandButton, View As CommandButton, Name As TextBox)
' employee has a timesheet for this week guaranteed by virtue of this function being activated

strSql = "INSERT INTO TIME_SUB_HISTORY (SELECT TS.*, SYSDATE, 'HR default 40 - By " & strUser & "' FROM TIME_SUBMISSION TS WHERE " _
        & "EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "')"
OraDatabase.ExecuteSQL (strSql)

' due to VB limitations on line continuations, I will break this into several SQL's for ease
strSql = "UPDATE TIME_SUBMISSION SET " _
        & "SUBMISSION_PC_USERID = '" & strUser & "', " _
        & "SUBMISSION_PC = '" & strComputer & "', " _
        & "SUBMISSION_DATETIME = SYSDATE, " _
        & "SIGN_OFF_EMPID = '" & strUserID & "', " _
        & "SIGN_OFF_PC_USERID = '" & strUser & "', " _
        & "SIGN_OFF_PC = '" & strComputer & "', " _
        & "SIGN_OFF_DATETIME = SYSDATE " _
        & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
OraDatabase.ExecuteSQL (strSql)
        
strSql = "UPDATE TIME_SUBMISSION SET " _
        & "MON_TOTAL = 8, " _
        & "MON_REG = 8, " _
        & "MON_HOLIDAY = 0, " _
        & "MON_VACATION = 0, " _
        & "MON_PERSONAL = 0, " _
        & "MON_SICK = 0, " _
        & "MON_OVERTIME = 0 " _
        & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
OraDatabase.ExecuteSQL (strSql)

strSql = "UPDATE TIME_SUBMISSION SET " _
        & "TUE_TOTAL = 8, " _
        & "TUE_REG = 8, " _
        & "TUE_HOLIDAY = 0, " _
        & "TUE_VACATION = 0, " _
        & "TUE_PERSONAL = 0, " _
        & "TUE_SICK = 0, " _
        & "TUE_OVERTIME = 0 " _
        & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
OraDatabase.ExecuteSQL (strSql)

strSql = "UPDATE TIME_SUBMISSION SET " _
        & "WED_TOTAL = 8, " _
        & "WED_REG = 8, " _
        & "WED_HOLIDAY = 0, " _
        & "WED_VACATION = 0, " _
        & "WED_PERSONAL = 0, " _
        & "WED_SICK = 0, " _
        & "WED_OVERTIME = 0 " _
        & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
OraDatabase.ExecuteSQL (strSql)

strSql = "UPDATE TIME_SUBMISSION SET " _
        & "THU_TOTAL = 8, " _
        & "THU_REG = 8, " _
        & "THU_HOLIDAY = 0, " _
        & "THU_VACATION = 0, " _
        & "THU_PERSONAL = 0, " _
        & "THU_SICK = 0, " _
        & "THU_OVERTIME = 0 " _
        & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
OraDatabase.ExecuteSQL (strSql)

strSql = "UPDATE TIME_SUBMISSION SET " _
        & "FRI_TOTAL = 8, " _
        & "FRI_REG = 8, " _
        & "FRI_HOLIDAY = 0, " _
        & "FRI_VACATION = 0, " _
        & "FRI_PERSONAL = 0, " _
        & "FRI_SICK = 0, " _
        & "FRI_OVERTIME = 0 " _
        & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
OraDatabase.ExecuteSQL (strSql)

strSql = "UPDATE TIME_SUBMISSION SET " _
        & "SAT_TOTAL = 0, " _
        & "SAT_REG = 0, " _
        & "SAT_HOLIDAY = 0, " _
        & "SAT_VACATION = 0, " _
        & "SAT_PERSONAL = 0, " _
        & "SAT_SICK = 0, " _
        & "SAT_OVERTIME = 0 " _
        & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
OraDatabase.ExecuteSQL (strSql)

strSql = "UPDATE TIME_SUBMISSION SET " _
        & "SUN_TOTAL = 0, " _
        & "SUN_REG = 0, " _
        & "SUN_HOLIDAY = 0, " _
        & "SUN_VACATION = 0, " _
        & "SUN_PERSONAL = 0, " _
        & "SUN_SICK = 0, " _
        & "SUN_OVERTIME = 0 " _
        & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
OraDatabase.ExecuteSQL (strSql)

strSql = "UPDATE TIME_SUBMISSION SET " _
        & "WEEK_TOTAL_TOTAL = 40, " _
        & "WEEK_TOTAL_REG = 40, " _
        & "WEEK_TOTAL_HOLIDAY = 0, " _
        & "WEEK_TOTAL_VACATION = 0, " _
        & "WEEK_TOTAL_PERSONAL = 0, " _
        & "WEEK_TOTAL_SICK = 0, " _
        & "WEEK_TOTAL_OVERTIME = 0 " _
        & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
OraDatabase.ExecuteSQL (strSql)

strSql = "UPDATE TIME_SUBMISSION SET " _
        & "STATUS = 'APPROVED', " _
        & "CONDITIONAL_SUBMISSION = 'Y' " _
        & "WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
OraDatabase.ExecuteSQL (strSql)

App.Enabled = False
STD.Enabled = False
View.Enabled = False
Name.Enabled = False
status.Enabled = False
status.Text = "CONDITIONAL - APPROVED"

Call Activate_more_button

End Sub
Private Sub Emp1App_Click()
    Call App_routine(EmpID1, Status1, Emp1App, Emp1STD, Emp1Mod, Emp1Name)
End Sub
Private Sub Emp2App_Click()
    Call App_routine(EmpID2, Status2, Emp2App, Emp2STD, Emp2Mod, Emp2Name)
End Sub
Private Sub Emp3App_Click()
    Call App_routine(EmpID3, Status3, Emp3App, Emp3STD, Emp3Mod, Emp3Name)
End Sub
Private Sub Emp4App_Click()
    Call App_routine(EmpID4, Status4, Emp4App, Emp4STD, Emp4Mod, Emp4Name)
End Sub
Private Sub Emp5App_Click()
    Call App_routine(EmpID5, Status5, Emp5App, Emp5STD, Emp5Mod, Emp5Name)
End Sub
Private Sub Emp6App_Click()
    Call App_routine(EmpID6, Status6, Emp6App, Emp6STD, Emp6Mod, Emp6Name)
End Sub
Private Sub Emp7App_Click()
    Call App_routine(EmpID7, Status7, Emp7App, Emp7STD, Emp7Mod, Emp7Name)
End Sub
Private Sub Emp8App_Click()
    Call App_routine(EmpID8, Status8, Emp8App, Emp8STD, Emp8Mod, Emp8Name)
End Sub

Private Sub App_routine(empID As String, status As TextBox, App As CommandButton, STD As CommandButton, View As CommandButton, Name As TextBox)

Dim strStatus As String

    strSql = "UPDATE TIME_SUBMISSION SET SIGN_OFF_EMPID = '" & strUserID & "', SIGN_OFF_PC_USERID = '" & strUser & "', SIGN_OFF_PC = '" _
            & strComputer & "', SIGN_OFF_DATETIME = SYSDATE WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
    OraDatabase.ExecuteSQL (strSql)
    
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & empID & "' AND WEEK_START_MONDAY = '" & OraTime & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    strStatus = dsSHORT_TERM_DATA.Fields("STATUS").Value

    OraSession.BeginTrans
    dsSHORT_TERM_DATA.Edit
    dsSHORT_TERM_DATA.Fields("STATUS").Value = "APPROVED"
    
    If InStr(1, strStatus, "ON HOLD") <> 0 Then
            dsSHORT_TERM_DATA.Fields("CONDITIONAL_SUBMISSION").Value = "Y"
            status.Text = "CONDITIONAL - APPROVED"
    ElseIf InStr(1, strStatus, "SUBMITTED") <> 0 Then
            dsSHORT_TERM_DATA.Fields("CONDITIONAL_SUBMISSION").Value = "N"
            status.Text = "APPROVED"
    End If
    
    dsSHORT_TERM_DATA.Update
    OraSession.CommitTrans

    App.Enabled = False
    STD.Enabled = False
    View.Enabled = False
    Name.Enabled = False
    status.Enabled = False

    Call Activate_more_button
    
End Sub

Private Sub Activate_more_button()

If (InStr(Status1.Text, "APPROVED") Or Status1.Text = "") And _
    (InStr(Status2.Text, "APPROVED") Or Status2.Text = "") And _
    (InStr(Status3.Text, "APPROVED") Or Status3.Text = "") And _
    (InStr(Status4.Text, "APPROVED") Or Status4.Text = "") And _
    (InStr(Status5.Text, "APPROVED") Or Status5.Text = "") And _
    (InStr(Status6.Text, "APPROVED") Or Status6.Text = "") And _
    (InStr(Status7.Text, "APPROVED") Or Status7.Text = "") And _
    (InStr(Status8.Text, "APPROVED") Or Status8.Text = "") And _
    isMoreEmp = True Then
        cmdMoreSheets.Enabled = True
Else
        cmdMoreSheets.Enabled = False
End If

End Sub
