VERSION 5.00
Begin VB.Form SupervisorForm 
   BackColor       =   &H00FFFFFF&
   Caption         =   "Timesheet Review"
   ClientHeight    =   7500
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   14955
   LinkTopic       =   "Form1"
   ScaleHeight     =   7500
   ScaleWidth      =   14955
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton PrevWeek 
      Caption         =   "Resolve Conditional Timesheets"
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
      TabIndex        =   128
      Top             =   1560
      Width           =   3375
   End
   Begin VB.Frame frmHours 
      BackColor       =   &H00C0C0C0&
      Height          =   4455
      Left            =   240
      TabIndex        =   12
      Top             =   2280
      Width           =   14535
      Begin VB.CommandButton cmdPrev8 
         Caption         =   "Prev"
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
         Left            =   13440
         TabIndex        =   136
         Top             =   3960
         Width           =   735
      End
      Begin VB.CommandButton cmdPrev7 
         Caption         =   "Prev"
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
         Left            =   13440
         TabIndex        =   135
         Top             =   3480
         Width           =   735
      End
      Begin VB.CommandButton cmdPrev6 
         Caption         =   "Prev"
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
         Left            =   13440
         TabIndex        =   134
         Top             =   3000
         Width           =   735
      End
      Begin VB.CommandButton cmdPrev5 
         Caption         =   "Prev"
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
         Left            =   13440
         TabIndex        =   133
         Top             =   2520
         Width           =   735
      End
      Begin VB.CommandButton cmdPrev4 
         Caption         =   "Prev"
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
         Left            =   13440
         TabIndex        =   132
         Top             =   2040
         Width           =   735
      End
      Begin VB.CommandButton cmdPrev3 
         Caption         =   "Prev"
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
         Left            =   13440
         TabIndex        =   131
         Top             =   1560
         Width           =   735
      End
      Begin VB.CommandButton cmdPrev2 
         Caption         =   "Prev"
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
         Left            =   13440
         TabIndex        =   130
         Top             =   1080
         Width           =   735
      End
      Begin VB.CommandButton cmdPrev1 
         Caption         =   "Prev"
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
         Left            =   13440
         TabIndex        =   129
         Top             =   600
         Width           =   735
      End
      Begin VB.TextBox Emp8Status 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   8040
         Locked          =   -1  'True
         MaxLength       =   25
         TabIndex        =   127
         TabStop         =   0   'False
         Top             =   3960
         Width           =   2655
      End
      Begin VB.TextBox Emp7Status 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   8040
         Locked          =   -1  'True
         MaxLength       =   25
         TabIndex        =   126
         TabStop         =   0   'False
         Top             =   3480
         Width           =   2655
      End
      Begin VB.TextBox Emp6Status 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   8040
         Locked          =   -1  'True
         MaxLength       =   25
         TabIndex        =   125
         TabStop         =   0   'False
         Top             =   3000
         Width           =   2655
      End
      Begin VB.TextBox Emp5Status 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   8040
         Locked          =   -1  'True
         MaxLength       =   25
         TabIndex        =   124
         TabStop         =   0   'False
         Top             =   2520
         Width           =   2655
      End
      Begin VB.TextBox Emp4Status 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   8040
         Locked          =   -1  'True
         MaxLength       =   25
         TabIndex        =   123
         TabStop         =   0   'False
         Top             =   2040
         Width           =   2655
      End
      Begin VB.TextBox Emp2Status 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   8040
         Locked          =   -1  'True
         MaxLength       =   25
         TabIndex        =   122
         TabStop         =   0   'False
         Top             =   1080
         Width           =   2655
      End
      Begin VB.TextBox Emp3Status 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   8040
         Locked          =   -1  'True
         MaxLength       =   25
         TabIndex        =   121
         TabStop         =   0   'False
         Top             =   1560
         Width           =   2655
      End
      Begin VB.TextBox Emp1Status 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   8040
         Locked          =   -1  'True
         MaxLength       =   25
         TabIndex        =   120
         TabStop         =   0   'False
         Top             =   600
         Width           =   2655
      End
      Begin VB.CommandButton Emp8Mod 
         Caption         =   "Cur"
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
         Left            =   12720
         TabIndex        =   118
         Top             =   3960
         Width           =   645
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
         Left            =   11880
         TabIndex        =   117
         Top             =   3960
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
         Left            =   10920
         TabIndex        =   116
         Top             =   3960
         Width           =   795
      End
      Begin VB.CommandButton Emp7Mod 
         Caption         =   "Cur"
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
         Left            =   12720
         TabIndex        =   115
         Top             =   3480
         Width           =   645
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
         Left            =   11880
         TabIndex        =   114
         Top             =   3480
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
         Left            =   10920
         TabIndex        =   113
         Top             =   3480
         Width           =   795
      End
      Begin VB.CommandButton Emp6Mod 
         Caption         =   "Cur"
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
         Left            =   12720
         TabIndex        =   112
         Top             =   3000
         Width           =   645
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
         Left            =   11880
         TabIndex        =   111
         Top             =   3000
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
         Left            =   10920
         TabIndex        =   110
         Top             =   3000
         Width           =   795
      End
      Begin VB.CommandButton Emp5Mod 
         Caption         =   "Cur"
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
         Left            =   12720
         TabIndex        =   109
         Top             =   2520
         Width           =   645
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
         Left            =   11880
         TabIndex        =   108
         Top             =   2520
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
         Left            =   10920
         TabIndex        =   107
         Top             =   2520
         Width           =   765
      End
      Begin VB.CommandButton Emp4Mod 
         Caption         =   "Cur"
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
         Left            =   12720
         TabIndex        =   106
         Top             =   2040
         Width           =   645
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
         Left            =   11880
         TabIndex        =   105
         Top             =   2040
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
         Left            =   10920
         TabIndex        =   104
         Top             =   2040
         Width           =   795
      End
      Begin VB.CommandButton Emp3Mod 
         Caption         =   "Cur"
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
         Left            =   12720
         TabIndex        =   103
         Top             =   1560
         Width           =   645
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
         Left            =   11880
         TabIndex        =   102
         Top             =   1560
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
         Left            =   10920
         TabIndex        =   101
         Top             =   1560
         Width           =   765
      End
      Begin VB.CommandButton Emp2Mod 
         Caption         =   "Cur"
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
         Left            =   12720
         TabIndex        =   100
         Top             =   1080
         Width           =   645
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
         Left            =   11880
         TabIndex        =   99
         Top             =   1080
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
         Left            =   10920
         TabIndex        =   98
         Top             =   1080
         Width           =   765
      End
      Begin VB.CommandButton Emp1Mod 
         Caption         =   "Cur"
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
         Left            =   12720
         TabIndex        =   96
         Top             =   600
         Width           =   645
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
         Left            =   11880
         TabIndex        =   95
         Top             =   600
         Width           =   645
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
         Left            =   10920
         TabIndex        =   0
         Top             =   600
         Width           =   765
      End
      Begin VB.TextBox Emp1OTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   6480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   83
         Top             =   600
         Width           =   495
      End
      Begin VB.TextBox Emp2OTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   6480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   82
         Top             =   1080
         Width           =   495
      End
      Begin VB.TextBox Emp3OTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   6480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   81
         Top             =   1560
         Width           =   495
      End
      Begin VB.TextBox Emp4OTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   6480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   80
         Top             =   2040
         Width           =   495
      End
      Begin VB.TextBox Emp5OTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   6480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   79
         Top             =   2520
         Width           =   495
      End
      Begin VB.TextBox Emp6OTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   6480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   78
         Top             =   3000
         Width           =   495
      End
      Begin VB.TextBox Emp7OTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   6480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   77
         Top             =   3480
         Width           =   495
      End
      Begin VB.TextBox Emp1RTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   5640
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   76
         Top             =   600
         Width           =   615
      End
      Begin VB.TextBox Emp2RTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   5640
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   75
         Top             =   1080
         Width           =   615
      End
      Begin VB.TextBox Emp3RTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   5640
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   74
         Top             =   1560
         Width           =   615
      End
      Begin VB.TextBox Emp4RTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   5640
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   73
         Top             =   2040
         Width           =   615
      End
      Begin VB.TextBox Emp5RTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   5640
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   72
         Top             =   2520
         Width           =   615
      End
      Begin VB.TextBox Emp6RTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   5640
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   71
         Top             =   3000
         Width           =   615
      End
      Begin VB.TextBox Emp7RTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   5640
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   70
         Top             =   3480
         Width           =   615
      End
      Begin VB.TextBox Emp1Sick 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4920
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   69
         Top             =   600
         Width           =   495
      End
      Begin VB.TextBox Emp2Sick 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4920
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   68
         Top             =   1080
         Width           =   495
      End
      Begin VB.TextBox Emp3Sick 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4920
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   67
         Top             =   1560
         Width           =   495
      End
      Begin VB.TextBox Emp4Sick 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4920
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   66
         Top             =   2040
         Width           =   495
      End
      Begin VB.TextBox Emp5Sick 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4920
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   65
         Top             =   2520
         Width           =   495
      End
      Begin VB.TextBox Emp6Sick 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4920
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   64
         Top             =   3000
         Width           =   495
      End
      Begin VB.TextBox Emp7Sick 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4920
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   63
         Top             =   3480
         Width           =   495
      End
      Begin VB.TextBox Emp1Per 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   62
         Top             =   600
         Width           =   495
      End
      Begin VB.TextBox Emp2Per 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   61
         Top             =   1080
         Width           =   495
      End
      Begin VB.TextBox Emp3Per 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   60
         Top             =   1560
         Width           =   495
      End
      Begin VB.TextBox Emp4Per 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   59
         Top             =   2040
         Width           =   495
      End
      Begin VB.TextBox Emp5Per 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   58
         Top             =   2520
         Width           =   495
      End
      Begin VB.TextBox Emp6Per 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   57
         Top             =   3000
         Width           =   495
      End
      Begin VB.TextBox Emp7Per 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   56
         Top             =   3480
         Width           =   495
      End
      Begin VB.TextBox Emp1Vac 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   3480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   55
         Top             =   600
         Width           =   495
      End
      Begin VB.TextBox Emp2Vac 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   3480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   54
         Top             =   1080
         Width           =   495
      End
      Begin VB.TextBox Emp3Vac 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   3480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   53
         Top             =   1560
         Width           =   495
      End
      Begin VB.TextBox Emp4Vac 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   3480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   52
         Top             =   2040
         Width           =   495
      End
      Begin VB.TextBox Emp5Vac 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   3480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   51
         Top             =   2520
         Width           =   495
      End
      Begin VB.TextBox Emp6Vac 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   3480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   50
         Top             =   3000
         Width           =   495
      End
      Begin VB.TextBox Emp7Vac 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   3480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   49
         Top             =   3480
         Width           =   495
      End
      Begin VB.TextBox Emp1Hol 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2760
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   48
         Top             =   600
         Width           =   495
      End
      Begin VB.TextBox Emp2Hol 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2760
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   47
         Top             =   1080
         Width           =   495
      End
      Begin VB.TextBox Emp3Hol 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2760
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   46
         Top             =   1560
         Width           =   495
      End
      Begin VB.TextBox Emp4Hol 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2760
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   45
         Top             =   2040
         Width           =   495
      End
      Begin VB.TextBox Emp5Hol 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2760
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   44
         Top             =   2520
         Width           =   495
      End
      Begin VB.TextBox Emp6Hol 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2760
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   43
         Top             =   3000
         Width           =   495
      End
      Begin VB.TextBox Emp7Hol 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2760
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   42
         Top             =   3480
         Width           =   495
      End
      Begin VB.TextBox Emp1Reg 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2040
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   41
         Top             =   600
         Width           =   495
      End
      Begin VB.TextBox Emp2Reg 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2040
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   40
         Top             =   1080
         Width           =   495
      End
      Begin VB.TextBox Emp3Reg 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2040
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   39
         Top             =   1560
         Width           =   495
      End
      Begin VB.TextBox Emp4Reg 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2040
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   38
         Top             =   2040
         Width           =   495
      End
      Begin VB.TextBox Emp5Reg 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2040
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   37
         Top             =   2520
         Width           =   495
      End
      Begin VB.TextBox Emp6Reg 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2040
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   36
         Top             =   3000
         Width           =   495
      End
      Begin VB.TextBox Emp7Reg 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2040
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   35
         Top             =   3480
         Width           =   495
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
         TabIndex        =   1
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
         TabIndex        =   34
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
         TabIndex        =   33
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
         TabIndex        =   32
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
         TabIndex        =   31
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
         TabIndex        =   30
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
         TabIndex        =   29
         Top             =   3480
         Width           =   1695
      End
      Begin VB.TextBox Emp8Name 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   120
         Locked          =   -1  'True
         TabIndex        =   28
         Tag             =   "NInvoice Number"
         Top             =   3960
         Width           =   1695
      End
      Begin VB.TextBox Emp8Reg 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2040
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   27
         Top             =   3960
         Width           =   495
      End
      Begin VB.TextBox Emp8Hol 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   2760
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   26
         Top             =   3960
         Width           =   495
      End
      Begin VB.TextBox Emp8Vac 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   3480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   25
         Top             =   3960
         Width           =   495
      End
      Begin VB.TextBox Emp8Per 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   24
         Top             =   3960
         Width           =   495
      End
      Begin VB.TextBox Emp8Sick 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   4920
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   23
         Top             =   3960
         Width           =   495
      End
      Begin VB.TextBox Emp8RTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   5640
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   22
         Top             =   3960
         Width           =   615
      End
      Begin VB.TextBox Emp8OTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   6480
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   21
         Top             =   3960
         Width           =   495
      End
      Begin VB.TextBox Emp1GTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   7200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   20
         Top             =   600
         Width           =   615
      End
      Begin VB.TextBox Emp2GTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   7200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   19
         Top             =   1080
         Width           =   615
      End
      Begin VB.TextBox Emp3GTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   7200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   18
         Top             =   1560
         Width           =   615
      End
      Begin VB.TextBox Emp4GTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   7200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   17
         Top             =   2040
         Width           =   615
      End
      Begin VB.TextBox Emp5GTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   7200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   16
         Top             =   2520
         Width           =   615
      End
      Begin VB.TextBox Emp6GTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   7200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   15
         Top             =   3000
         Width           =   615
      End
      Begin VB.TextBox Emp7GTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   7200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   14
         Top             =   3480
         Width           =   615
      End
      Begin VB.TextBox Emp8GTtl 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   285
         Left            =   7200
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   13
         Top             =   3960
         Width           =   615
      End
      Begin VB.Label Label10 
         AutoSize        =   -1  'True
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
         Height          =   225
         Left            =   8040
         TabIndex        =   119
         Top             =   240
         Width           =   510
      End
      Begin VB.Line Line14 
         X1              =   7920
         X2              =   7920
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
         Left            =   12720
         TabIndex        =   97
         Top             =   240
         Width           =   390
      End
      Begin VB.Line Line13 
         X1              =   12600
         X2              =   12600
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line12 
         X1              =   11760
         X2              =   11760
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line10 
         X1              =   7080
         X2              =   7080
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Label OHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "OT"
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
         Left            =   6480
         TabIndex        =   94
         Top             =   240
         Width           =   270
      End
      Begin VB.Label SHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Sick"
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
         TabIndex        =   93
         Top             =   240
         Width           =   360
      End
      Begin VB.Label PHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Pers"
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
         Left            =   4200
         TabIndex        =   92
         Top             =   240
         Width           =   345
      End
      Begin VB.Label VHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Vac"
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
         Left            =   3480
         TabIndex        =   91
         Top             =   240
         Width           =   285
      End
      Begin VB.Label HHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Hol"
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
         Left            =   2760
         TabIndex        =   90
         Top             =   240
         Width           =   285
      End
      Begin VB.Label RHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Total"
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
         Left            =   5640
         TabIndex        =   89
         Top             =   240
         Width           =   420
      End
      Begin VB.Label Total 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Reg"
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
         Left            =   2040
         TabIndex        =   88
         Top             =   240
         Width           =   300
      End
      Begin VB.Line Line9 
         X1              =   0
         X2              =   14520
         Y1              =   480
         Y2              =   480
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
         TabIndex        =   87
         Top             =   240
         Width           =   1275
      End
      Begin VB.Line Line8 
         X1              =   6360
         X2              =   6360
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line7 
         X1              =   5520
         X2              =   5520
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line6 
         X1              =   4800
         X2              =   4800
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line5 
         X1              =   4080
         X2              =   4080
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line4 
         X1              =   3360
         X2              =   3360
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line3 
         X1              =   2640
         X2              =   2640
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Line Line2 
         X1              =   1920
         X2              =   1920
         Y1              =   120
         Y2              =   4440
      End
      Begin VB.Label Label6 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Grand"
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
         Left            =   7200
         TabIndex        =   86
         Top             =   240
         Width           =   495
      End
      Begin VB.Line Line1 
         X1              =   10800
         X2              =   10800
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
         Left            =   10920
         TabIndex        =   85
         Top             =   240
         Width           =   630
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
         Left            =   11880
         TabIndex        =   84
         Top             =   240
         Width           =   420
      End
   End
   Begin VB.Frame Frame1 
      BackColor       =   &H00FFFFFF&
      Height          =   2055
      Left            =   240
      TabIndex        =   2
      Top             =   120
      Width           =   14535
      Begin VB.CommandButton CmdViewOld 
         Caption         =   "View Employee Timesheets"
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
         Left            =   5520
         TabIndex        =   138
         Top             =   960
         Width           =   3375
      End
      Begin VB.TextBox WeekEnd 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   11640
         Locked          =   -1  'True
         TabIndex        =   11
         Top             =   1560
         Width           =   1455
      End
      Begin VB.TextBox WeekStart 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   11640
         Locked          =   -1  'True
         TabIndex        =   10
         Top             =   960
         Width           =   1455
      End
      Begin VB.TextBox Dept 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   1680
         Locked          =   -1  'True
         TabIndex        =   6
         Top             =   1560
         Width           =   2175
      End
      Begin VB.TextBox SuperName 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   1680
         Locked          =   -1  'True
         TabIndex        =   5
         Top             =   960
         Width           =   2175
      End
      Begin VB.Label Label5 
         BackColor       =   &H00FFFFFF&
         Caption         =   "Week End"
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
         Left            =   10320
         TabIndex        =   9
         Top             =   1560
         Width           =   1095
      End
      Begin VB.Label Label4 
         BackColor       =   &H00FFFFFF&
         Caption         =   "Week Start"
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
         Left            =   10320
         TabIndex        =   8
         Top             =   960
         Width           =   1335
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
         Left            =   360
         TabIndex        =   7
         Top             =   1560
         Width           =   1215
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
         Left            =   960
         TabIndex        =   4
         Top             =   960
         Width           =   735
      End
      Begin VB.Label Label1 
         BackColor       =   &H00FFFFFF&
         Caption         =   "Departmental Roll Forward Sheet"
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
         Left            =   3960
         TabIndex        =   3
         Top             =   240
         Width           =   6255
      End
   End
   Begin VB.Label lblNoApprove 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Please wait until Friday at 2PM before approving any current timesheets."
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   3600
      TabIndex        =   137
      Top             =   3960
      Width           =   7575
   End
End
Attribute VB_Name = "SupervisorForm"
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

Dim EmpList As String

Dim Is_Past_11AM As Boolean


Dim WeekInQuestionOracle As String
Dim WeekInQuestionHuman As String






Private Sub form_load()

WeekInQuestionOracle = strCurrentWeekOracleAPP
WeekInQuestionHuman = strCurrentWeekHumanAPP



Me.Caption = "Timesheet Review -    Current User: " & strUser & "    Current Computer: " & strComputer

strSql = "SELECT FIRST_NAME || ' ' || LAST_NAME THE_NAME, DEPARTMENT_ID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strUserID & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

Me.SuperName.Text = dsSHORT_TERM_DATA.Fields("THE_NAME").Value
Me.Dept.Text = dsSHORT_TERM_DATA.Fields("DEPARTMENT_ID").Value
Me.WeekStart.Text = WeekInQuestionHuman
Me.WeekEnd.Text = DateAdd("d", 6, WeekInQuestionHuman)

Call PopulateGrid

' if it is the case that it is not within the time frame of Friday 2PM to Monday Noon,
' we do NOT want to show the grid AT ALL.  However, as it has become necessary for
' supervisors to approve PAST timesheets at any time, the approval button on frmTimeSheet must remain
' active, so that they can get to this screen, and resolve past timesheets from here.

' Note, Adam Walter, Dec 2008.  Apparently, HR's decision about when timesheets should be approvable
' changes like my mother's mind, so this small segment of code gets changed often.
' This could be moved to a DB-style call with a web-app to set it, but that needs more
' thought put behind it to prevent loopholes.

' August 2009.  Finally, this statement is based off a table.
strSql = "SELECT EARLIEST_DAY FROM TIMESHEET_APPROVAL"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

If Format(Now, "dddd") = "Saturday" Or Format(Now, "dddd") = "Sunday" Or _
(Format(Now, "dddd") = "Monday" And Format(Now, "h") <= 12) Or _
(Format(Now, "dddd") = "Friday") Or _
(Format(Now, "dddd") = "Thursday" And (dsSHORT_TERM_DATA.Fields("EARLIEST_DAY").Value = "WEDNESDAY" Or dsSHORT_TERM_DATA.Fields("EARLIEST_DAY").Value = "THURSDAY")) Or _
(Format(Now, "dddd") = "Wednesday" And dsSHORT_TERM_DATA.Fields("EARLIEST_DAY").Value = "WEDNESDAY") Then
'(Format(Now, "dddd") = "Wednesday") Or
'(Format(Now, "dddd") = "Thursday" And Format(Now, "h") >= 12) Or
    lblNoApprove.Visible = False
    frmHours.Visible = True
Else
    lblNoApprove.Visible = True
    frmHours.Visible = False
End If

End Sub

Private Sub PopulateGrid()
' This function, only called in Form Load, gets the data from TIME_SUBMISSION for a given week/department,
' And puts it in the form.
' Regretably, as the form is static, so to do I find myself having to do one of the
' Largest iterative calls of my programming career.

' subroutine TS_INSERT, used (potentially) 8 times in this routine, resets data object TIME_SUBMISSION, so that this code works fluidly.

' subroutine Button_Activation is called (potentially) 8 times in this routine, and determines which of the
' Approve / Deny / Modify buttons are available options for a given line

EmpList = "(" & GenerateEmployeeList(strUserID) & "'')"


' These few lines determine if we activate the "previous weeks" button
strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID IN " & EmpList & " AND WEEK_START_MONDAY < '" & strCurrentWeekOracleAPP & "' AND (STATUS = 'SUBMITTED' OR (STATUS = 'ON HOLD' AND CONDITIONAL_SUBMISSION = 'Y'))"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

If dsSHORT_TERM_DATA.Recordcount = 0 Then
    PrevWeek.Enabled = False
Else
    PrevWeek.Enabled = True
End If



' And here starts the major, grid populating-and-button-enabling logic
' Variable Is_Past_11AM is set in the routine that generates EmpList
' extra check purges employees who are not awaiting approvals from the list
strSql = "SELECT EMPLOYEE_ID, FIRST_NAME || ' ' || LAST_NAME THE_NAME, VACATION_YTD_REMAIN, PERSONAL_YTD_REMAIN, SICK_YTD_REMAIN FROM AT_EMPLOYEE WHERE EMPLOYEE_ID IN " & EmpList & " AND EMPLOYMENT_STATUS = 'ACTIVE'"
If Is_Past_11AM = True Then
    strSql = strSql & " AND EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "' AND (STATUS = 'SUBMITTED' OR (STATUS = 'ON HOLD' AND CONDITIONAL_SUBMISSION = 'Y')))"
End If
Set dsAT_EMPLOYEE = OraDatabase.DbCreateDynaset(strSql, 0&)

If dsAT_EMPLOYEE.EOF = False Then
    Emp1Name.Text = dsAT_EMPLOYEE.Fields("THE_NAME").Value
    EmpID1 = dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "' " _
            & "AND EMPLOYEE_ID = '" & dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value & "'"
    Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsTIME_SUBMISSION.EOF = True Then
'        Call TS_INSERT(EmpID1)
         Emp1Status.Text = "UNCREATED"
         Emp1App.Enabled = False
         Emp1Deny.Enabled = False
         Emp1Mod.Enabled = False
         cmdPrev1.Enabled = False
    Else
    
        Emp1Reg.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value
        Emp1Hol.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value
        Emp1Vac.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value
        Emp1Per.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value
        Emp1Sick.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value
        Emp1RTtl.Text = Val(Emp1Reg.Text) + Val(Emp1Hol.Text) + Val(Emp1Vac.Text) + Val(Emp1Per.Text) + Val(Emp1Sick.Text)
        Emp1OTtl.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value
        Emp1GTtl.Text = Val(Emp1RTtl.Text) + Val(Emp1OTtl.Text)
        Emp1Status.Text = dsTIME_SUBMISSION.Fields("STATUS").Value
                
        If dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
            Emp1Status.Text = "CONDITIONAL - " & Emp1Status.Text
        End If
            
        Call Button_Activation(dsTIME_SUBMISSION.Fields("STATUS").Value, dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value, Emp1App, Emp1Deny, Emp1Mod, cmdPrev1)
        
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_VACATION_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value) Then
           Emp1Vac.ForeColor = &HFF
           Emp1Name.ForeColor = &HFF
        Else
           Emp1Vac.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_PERSONAL_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value) Then
           Emp1Per.ForeColor = &HFF
           Emp1Name.ForeColor = &HFF
        Else
           Emp1Per.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_SICK_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value) Then
           Emp1Sick.ForeColor = &HFF
           Emp1Name.ForeColor = &HFF
        Else
           Emp1Sick.ForeColor = &H0
        End If
    
    End If
    
    dsAT_EMPLOYEE.Movenext
End If
If dsAT_EMPLOYEE.EOF = False Then
    Emp2Name.Text = dsAT_EMPLOYEE.Fields("THE_NAME").Value
    EmpID2 = dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "' " _
            & "AND EMPLOYEE_ID = '" & dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value & "'"
    Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsTIME_SUBMISSION.EOF = True Then
'        Call TS_INSERT(EmpID1)
         Emp2Status.Text = "UNCREATED"
         Emp2App.Enabled = False
         Emp2Deny.Enabled = False
         Emp2Mod.Enabled = False
         cmdPrev2.Enabled = False
    Else
    
        Emp2Reg.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value
        Emp2Hol.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value
        Emp2Vac.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value
        Emp2Per.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value
        Emp2Sick.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value
        Emp2RTtl.Text = Val(Emp2Reg.Text) + Val(Emp2Hol.Text) + Val(Emp2Vac.Text) + Val(Emp2Per.Text) + Val(Emp2Sick.Text)
        Emp2OTtl.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value
        Emp2GTtl.Text = Val(Emp2RTtl.Text) + Val(Emp2OTtl.Text)
        Emp2Status.Text = dsTIME_SUBMISSION.Fields("STATUS").Value
                    
        If dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
            Emp2Status.Text = "CONDITIONAL - " & Emp2Status.Text
        End If
            
        Call Button_Activation(dsTIME_SUBMISSION.Fields("STATUS").Value, dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value, Emp2App, Emp2Deny, Emp2Mod, cmdPrev2)
        
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_VACATION_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value) Then
           Emp2Vac.ForeColor = &HFF
           Emp2Name.ForeColor = &HFF
        Else
           Emp2Vac.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_PERSONAL_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value) Then
           Emp2Per.ForeColor = &HFF
           Emp2Name.ForeColor = &HFF
        Else
           Emp2Per.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_SICK_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value) Then
           Emp2Sick.ForeColor = &HFF
           Emp2Name.ForeColor = &HFF
        Else
           Emp2Sick.ForeColor = &H0
        End If
    
    End If
    
    dsAT_EMPLOYEE.Movenext
End If
If dsAT_EMPLOYEE.EOF = False Then
    Emp3Name.Text = dsAT_EMPLOYEE.Fields("THE_NAME").Value
    EmpID3 = dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "' " _
            & "AND EMPLOYEE_ID = '" & dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value & "'"
    Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsTIME_SUBMISSION.EOF = True Then
'        Call TS_INSERT(EmpID1)
         Emp3Status.Text = "UNCREATED"
         Emp3App.Enabled = False
         Emp3Deny.Enabled = False
         Emp3Mod.Enabled = False
         cmdPrev3.Enabled = False
    Else
    
        Emp3Reg.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value
        Emp3Hol.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value
        Emp3Vac.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value
        Emp3Per.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value
        Emp3Sick.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value
        Emp3RTtl.Text = Val(Emp3Reg.Text) + Val(Emp3Hol.Text) + Val(Emp3Vac.Text) + Val(Emp3Per.Text) + Val(Emp3Sick.Text)
        Emp3OTtl.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value
        Emp3GTtl.Text = Val(Emp3RTtl.Text) + Val(Emp3OTtl.Text)
        Emp3Status.Text = dsTIME_SUBMISSION.Fields("STATUS").Value
            
        If dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
            Emp3Status.Text = "CONDITIONAL - " & Emp3Status.Text
        End If
            
        Call Button_Activation(dsTIME_SUBMISSION.Fields("STATUS").Value, dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value, Emp3App, Emp3Deny, Emp3Mod, cmdPrev3)
        
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_VACATION_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value) Then
           Emp3Vac.ForeColor = &HFF
           Emp3Name.ForeColor = &HFF
        Else
           Emp3Vac.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_PERSONAL_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value) Then
           Emp3Per.ForeColor = &HFF
           Emp3Name.ForeColor = &HFF
        Else
           Emp3Per.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_SICK_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value) Then
           Emp3Sick.ForeColor = &HFF
           Emp3Name.ForeColor = &HFF
        Else
           Emp3Sick.ForeColor = &H0
        End If
    
    End If
        
    dsAT_EMPLOYEE.Movenext
End If
If dsAT_EMPLOYEE.EOF = False Then
    Emp4Name.Text = dsAT_EMPLOYEE.Fields("THE_NAME").Value
    EmpID4 = dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "' " _
            & "AND EMPLOYEE_ID = '" & dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value & "'"
    Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsTIME_SUBMISSION.EOF = True Then
'        Call TS_INSERT(EmpID1)
         Emp4Status.Text = "UNCREATED"
         Emp4App.Enabled = False
         Emp4Deny.Enabled = False
         Emp4Mod.Enabled = False
         cmdPrev4.Enabled = False
    Else
    
        Emp4Reg.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value
        Emp4Hol.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value
        Emp4Vac.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value
        Emp4Per.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value
        Emp4Sick.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value
        Emp4RTtl.Text = Val(Emp4Reg.Text) + Val(Emp4Hol.Text) + Val(Emp4Vac.Text) + Val(Emp4Per.Text) + Val(Emp4Sick.Text)
        Emp4OTtl.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value
        Emp4GTtl.Text = Val(Emp4RTtl.Text) + Val(Emp4OTtl.Text)
        Emp4Status.Text = dsTIME_SUBMISSION.Fields("STATUS").Value
            
        If dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
            Emp4Status.Text = "CONDITIONAL - " & Emp4Status.Text
        End If
            
        Call Button_Activation(dsTIME_SUBMISSION.Fields("STATUS").Value, dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value, Emp4App, Emp4Deny, Emp4Mod, cmdPrev4)
        
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_VACATION_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value) Then
           Emp4Vac.ForeColor = &HFF
           Emp4Name.ForeColor = &HFF
        Else
           Emp4Vac.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_PERSONAL_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value) Then
           Emp4Per.ForeColor = &HFF
           Emp4Name.ForeColor = &HFF
        Else
           Emp4Per.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_SICK_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value) Then
           Emp4Sick.ForeColor = &HFF
           Emp4Name.ForeColor = &HFF
        Else
           Emp4Sick.ForeColor = &H0
        End If
    
    End If
    
    dsAT_EMPLOYEE.Movenext
End If
If dsAT_EMPLOYEE.EOF = False Then
    Emp5Name.Text = dsAT_EMPLOYEE.Fields("THE_NAME").Value
    EmpID5 = dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "' " _
            & "AND EMPLOYEE_ID = '" & dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value & "'"
    Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsTIME_SUBMISSION.EOF = True Then
'        Call TS_INSERT(EmpID1)
         Emp5Status.Text = "UNCREATED"
         Emp5App.Enabled = False
         Emp5Deny.Enabled = False
         Emp5Mod.Enabled = False
         cmdPrev5.Enabled = False
    Else
    
        Emp5Reg.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value
        Emp5Hol.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value
        Emp5Vac.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value
        Emp5Per.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value
        Emp5Sick.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value
        Emp5RTtl.Text = Val(Emp5Reg.Text) + Val(Emp5Hol.Text) + Val(Emp5Vac.Text) + Val(Emp5Per.Text) + Val(Emp5Sick.Text)
        Emp5OTtl.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value
        Emp5GTtl.Text = Val(Emp5RTtl.Text) + Val(Emp5OTtl.Text)
        Emp5Status.Text = dsTIME_SUBMISSION.Fields("STATUS").Value
            
        If dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
            Emp5Status.Text = "CONDITIONAL - " & Emp5Status.Text
        End If
            
        Call Button_Activation(dsTIME_SUBMISSION.Fields("STATUS").Value, dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value, Emp5App, Emp5Deny, Emp5Mod, cmdPrev5)
        
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_VACATION_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value) Then
           Emp5Vac.ForeColor = &HFF
           Emp5Name.ForeColor = &HFF
        Else
           Emp5Vac.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_PERSONAL_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value) Then
           Emp5Per.ForeColor = &HFF
           Emp5Name.ForeColor = &HFF
        Else
           Emp5Per.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_SICK_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value) Then
           Emp5Sick.ForeColor = &HFF
           Emp5Name.ForeColor = &HFF
        Else
           Emp5Sick.ForeColor = &H0
        End If
    
    End If
    
    dsAT_EMPLOYEE.Movenext
End If
If dsAT_EMPLOYEE.EOF = False Then
    Emp6Name.Text = dsAT_EMPLOYEE.Fields("THE_NAME").Value
    EmpID6 = dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "' " _
            & "AND EMPLOYEE_ID = '" & dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value & "'"
    Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsTIME_SUBMISSION.EOF = True Then
'        Call TS_INSERT(EmpID1)
         Emp6Status.Text = "UNCREATED"
         Emp6App.Enabled = False
         Emp6Deny.Enabled = False
         Emp6Mod.Enabled = False
         cmdPrev6.Enabled = False
    Else
    
        Emp6Reg.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value
        Emp6Hol.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value
        Emp6Vac.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value
        Emp6Per.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value
        Emp6Sick.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value
        Emp6RTtl.Text = Val(Emp6Reg.Text) + Val(Emp6Hol.Text) + Val(Emp6Vac.Text) + Val(Emp6Per.Text) + Val(Emp6Sick.Text)
        Emp6OTtl.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value
        Emp6GTtl.Text = Val(Emp6RTtl.Text) + Val(Emp6OTtl.Text)
        Emp6Status.Text = dsTIME_SUBMISSION.Fields("STATUS").Value
            
        If dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
            Emp6Status.Text = "CONDITIONAL - " & Emp6Status.Text
        End If
            
        Call Button_Activation(dsTIME_SUBMISSION.Fields("STATUS").Value, dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value, Emp6App, Emp6Deny, Emp6Mod, cmdPrev6)
        
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_VACATION_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value) Then
           Emp6Vac.ForeColor = &HFF
           Emp6Name.ForeColor = &HFF
        Else
           Emp6Vac.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_PERSONAL_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value) Then
           Emp6Per.ForeColor = &HFF
           Emp6Name.ForeColor = &HFF
        Else
           Emp6Per.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_SICK_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value) Then
           Emp6Sick.ForeColor = &HFF
           Emp6Name.ForeColor = &HFF
        Else
           Emp6Sick.ForeColor = &H0
        End If
    
    End If
    
    dsAT_EMPLOYEE.Movenext
End If
If dsAT_EMPLOYEE.EOF = False Then
    Emp7Name.Text = dsAT_EMPLOYEE.Fields("THE_NAME").Value
    EmpID7 = dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "' " _
            & "AND EMPLOYEE_ID = '" & dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value & "'"
    Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsTIME_SUBMISSION.EOF = True Then
'        Call TS_INSERT(EmpID1)
         Emp7Status.Text = "UNCREATED"
         Emp7App.Enabled = False
         Emp7Deny.Enabled = False
         Emp7Mod.Enabled = False
         cmdPrev7.Enabled = False
    Else
    
        Emp7Reg.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value
        Emp7Hol.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value
        Emp7Vac.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value
        Emp7Per.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value
        Emp7Sick.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value
        Emp7RTtl.Text = Val(Emp7Reg.Text) + Val(Emp7Hol.Text) + Val(Emp7Vac.Text) + Val(Emp7Per.Text) + Val(Emp7Sick.Text)
        Emp7OTtl.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value
        Emp7GTtl.Text = Val(Emp7RTtl.Text) + Val(Emp7OTtl.Text)
        Emp7Status.Text = dsTIME_SUBMISSION.Fields("STATUS").Value
            
        If dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
            Emp7Status.Text = "CONDITIONAL - " & Emp7Status.Text
        End If
            
        Call Button_Activation(dsTIME_SUBMISSION.Fields("STATUS").Value, dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value, Emp7App, Emp7Deny, Emp7Mod, cmdPrev7)
        
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_VACATION_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value) Then
           Emp7Vac.ForeColor = &HFF
           Emp7Name.ForeColor = &HFF
        Else
           Emp7Vac.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_PERSONAL_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value) Then
           Emp7Per.ForeColor = &HFF
           Emp7Name.ForeColor = &HFF
        Else
           Emp7Per.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_SICK_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value) Then
           Emp7Sick.ForeColor = &HFF
           Emp7Name.ForeColor = &HFF
        Else
           Emp7Sick.ForeColor = &H0
        End If
    
    End If
    
    dsAT_EMPLOYEE.Movenext
End If
If dsAT_EMPLOYEE.EOF = False Then
    Emp8Name.Text = dsAT_EMPLOYEE.Fields("THE_NAME").Value
    EmpID8 = dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "' " _
            & "AND EMPLOYEE_ID = '" & dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value & "'"
    Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsTIME_SUBMISSION.EOF = True Then
'        Call TS_INSERT(EmpID1)
         Emp8Status.Text = "UNCREATED"
         Emp8App.Enabled = False
         Emp8Deny.Enabled = False
         Emp8Mod.Enabled = False
         cmdPrev8.Enabled = False
    Else
    
        Emp8Reg.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value
        Emp8Hol.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value
        Emp8Vac.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value
        Emp8Per.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value
        Emp8Sick.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value
        Emp8RTtl.Text = Val(Emp8Reg.Text) + Val(Emp8Hol.Text) + Val(Emp8Vac.Text) + Val(Emp8Per.Text) + Val(Emp8Sick.Text)
        Emp8OTtl.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value
        Emp8GTtl.Text = Val(Emp8RTtl.Text) + Val(Emp8OTtl.Text)
        Emp8Status.Text = dsTIME_SUBMISSION.Fields("STATUS").Value
        
        If dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
            Emp8Status.Text = "CONDITIONAL - " & Emp8Status.Text
        End If
            
        Call Button_Activation(dsTIME_SUBMISSION.Fields("STATUS").Value, dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value, Emp8App, Emp8Deny, Emp8Mod, cmdPrev8)
        
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_VACATION_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value) Then
           Emp8Vac.ForeColor = &HFF
           Emp8Name.ForeColor = &HFF
        Else
           Emp8Vac.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_PERSONAL_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value) Then
           Emp8Per.ForeColor = &HFF
           Emp8Name.ForeColor = &HFF
        Else
           Emp8Per.ForeColor = &H0
        End If
        If Val(dsTIME_SUBMISSION.Fields("YTD_WEEK_START_SICK_BAL").Value) < Val(dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value) Then
           Emp8Sick.ForeColor = &HFF
           Emp8Name.ForeColor = &HFF
        Else
           Emp8Sick.ForeColor = &H0
        End If
        
    End If
        
    dsAT_EMPLOYEE.Movenext
End If

End Sub

Private Sub TS_INSERT(strEmployeeID As String)
' this routine would have inserted a timesheet for a person who had not done so prior to a supervisors approval activation;
' but as said creation is now handled by the 10AM cron, this routine should no longer be used.

'Dim MonDate As String
'Dim TueDate As String
'Dim WedDate As String
'Dim ThuDate As String
'Dim FriDate As String
'Dim SatDate As String
'Dim SunDate As String
'
'MonDate = strCurrentWeekOracle
'TueDate = DateAdd("d", 1, MonDate)
'WedDate = DateAdd("d", 1, TueDate)
'ThuDate = DateAdd("d", 1, WedDate)
'FriDate = DateAdd("d", 1, ThuDate)
'SatDate = DateAdd("d", 1, FriDate)
'SunDate = DateAdd("d", 1, SatDate)
'
'MonDate = Format(MonDate, "dd-mmm-yyyy")
'TueDate = Format(TueDate, "dd-mmm-yyyy")
'WedDate = Format(WedDate, "dd-mmm-yyyy")
'ThuDate = Format(ThuDate, "dd-mmm-yyyy")
'FriDate = Format(FriDate, "dd-mmm-yyyy")
'SatDate = Format(SatDate, "dd-mmm-yyyy")
'SunDate = Format(SunDate, "dd-mmm-yyyy")
'
'' adds a timesheet record for an employee that hasn't yet entered one
'' at the very end of the routine, it sets TIME_SUBMISSION back to a value that PopulateGrid is expecting
'
'strSql = "INSERT INTO TIME_SUBMISSION (EMPLOYEE_ID, WEEK_START_MONDAY, WEEK_END_SUNDAY, STATUS) VALUES ('" & strEmployeeID & "', '" _
'        & strCurrentWeekOracle & "', '" & SunDate & "', 'ON HOLD')"
'OraDatabase.ExecuteSQL (strSql)
'
'strSql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmployeeID & "'"
'Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'
'strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' AND WEEK_START_MONDAY = '" & strCurrentWeekOracle & "'"
'Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
'
'OraSession.BeginTrans
'dsTIME_SUBMISSION.Edit
'dsTIME_SUBMISSION.Fields("MON_DATE").Value = MonDate
'dsTIME_SUBMISSION.Fields("MON_TOTAL").Value = 8
'dsTIME_SUBMISSION.Fields("MON_REG").Value = 8
'dsTIME_SUBMISSION.Fields("MON_HOLIDAY").Value = 0
'dsTIME_SUBMISSION.Fields("MON_VACATION").Value = 0
'dsTIME_SUBMISSION.Fields("MON_PERSONAL").Value = 0
'dsTIME_SUBMISSION.Fields("MON_SICK").Value = 0
'dsTIME_SUBMISSION.Fields("MON_OVERTIME").Value = 0
'dsTIME_SUBMISSION.Fields("TUE_DATE").Value = TueDate
'dsTIME_SUBMISSION.Fields("TUE_TOTAL").Value = 8
'dsTIME_SUBMISSION.Fields("TUE_REG").Value = 8
'dsTIME_SUBMISSION.Fields("TUE_HOLIDAY").Value = 0
'dsTIME_SUBMISSION.Fields("TUE_VACATION").Value = 0
'dsTIME_SUBMISSION.Fields("TUE_PERSONAL").Value = 0
'dsTIME_SUBMISSION.Fields("TUE_SICK").Value = 0
'dsTIME_SUBMISSION.Fields("TUE_OVERTIME").Value = 0
'dsTIME_SUBMISSION.Fields("WED_DATE").Value = WedDate
'dsTIME_SUBMISSION.Fields("WED_TOTAL").Value = 8
'dsTIME_SUBMISSION.Fields("WED_REG").Value = 8
'dsTIME_SUBMISSION.Fields("WED_HOLIDAY").Value = 0
'dsTIME_SUBMISSION.Fields("WED_VACATION").Value = 0
'dsTIME_SUBMISSION.Fields("WED_PERSONAL").Value = 0
'dsTIME_SUBMISSION.Fields("WED_SICK").Value = 0
'dsTIME_SUBMISSION.Fields("WED_OVERTIME").Value = 0
'dsTIME_SUBMISSION.Fields("THU_DATE").Value = ThuDate
'dsTIME_SUBMISSION.Fields("THU_TOTAL").Value = 8
'dsTIME_SUBMISSION.Fields("THU_REG").Value = 8
'dsTIME_SUBMISSION.Fields("THU_HOLIDAY").Value = 0
'dsTIME_SUBMISSION.Fields("THU_VACATION").Value = 0
'dsTIME_SUBMISSION.Fields("THU_PERSONAL").Value = 0
'dsTIME_SUBMISSION.Fields("THU_SICK").Value = 0
'dsTIME_SUBMISSION.Fields("THU_OVERTIME").Value = 0
'dsTIME_SUBMISSION.Fields("FRI_DATE").Value = FriDate
'dsTIME_SUBMISSION.Fields("FRI_TOTAL").Value = 8
'dsTIME_SUBMISSION.Fields("FRI_REG").Value = 8
'dsTIME_SUBMISSION.Fields("FRI_HOLIDAY").Value = 0
'dsTIME_SUBMISSION.Fields("FRI_VACATION").Value = 0
'dsTIME_SUBMISSION.Fields("FRI_PERSONAL").Value = 0
'dsTIME_SUBMISSION.Fields("FRI_SICK").Value = 0
'dsTIME_SUBMISSION.Fields("FRI_OVERTIME").Value = 0
'dsTIME_SUBMISSION.Fields("SAT_TOTAL").Value = 0
'dsTIME_SUBMISSION.Fields("SAT_DATE").Value = SatDate
'dsTIME_SUBMISSION.Fields("SAT_REG").Value = 0
'dsTIME_SUBMISSION.Fields("SAT_OVERTIME").Value = 0
'dsTIME_SUBMISSION.Fields("SUN_DATE").Value = SunDate
'dsTIME_SUBMISSION.Fields("SUN_TOTAL").Value = 0
'dsTIME_SUBMISSION.Fields("SUN_REG").Value = 0
'dsTIME_SUBMISSION.Fields("SUN_OVERTIME").Value = 0
'dsTIME_SUBMISSION.Fields("WEEK_TOTAL_TOTAL").Value = 40
'dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value = 40
'dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value = 0
'dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value = 0
'dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value = 0
'dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value = 0
'dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value = 0
'dsTIME_SUBMISSION.Fields("WEEKLY_ACCRUAL_VACATION").Value = dsSHORT_TERM_DATA.Fields("VACATION_WEEKLY_RATE").Value
'dsTIME_SUBMISSION.Fields("WEEKLY_ACCRUAL_SICK").Value = dsSHORT_TERM_DATA.Fields("SICK_WEEKLY_RATE").Value
'dsTIME_SUBMISSION.Fields("YTD_WEEK_START_VACATION_BAL").Value = dsSHORT_TERM_DATA.Fields("VACATION_YTD_REMAIN").Value
'dsTIME_SUBMISSION.Fields("YTD_WEEK_START_PERSONAL_BAL").Value = dsSHORT_TERM_DATA.Fields("PERSONAL_YTD_REMAIN").Value
'dsTIME_SUBMISSION.Fields("YTD_WEEK_START_SICK_BAL").Value = dsSHORT_TERM_DATA.Fields("SICK_YTD_REMAIN").Value
'dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value = "Y"
'dsTIME_SUBMISSION.Update
'OraSession.CommitTrans
'
'strSql = "SELECT NVL(SUM(WEEK_TOTAL_TOTAL), 0) THE_TOTAL, " _
'         & "NVL(SUM(WEEK_TOTAL_REG), 0) THE_REG, " _
'         & "NVL(SUM(WEEK_TOTAL_HOLIDAY), 0) THE_HOLIDAY, " _
'         & "NVL(SUM(WEEK_TOTAL_VACATION), 0) THE_VACATION, " _
'         & "NVL(SUM(WEEK_TOTAL_PERSONAL), 0) THE_PERSONAL, " _
'         & "NVL(SUM(WEEK_TOTAL_SICK), 0) THE_SICK, " _
'         & "NVL(SUM(WEEK_TOTAL_OVERTIME), 0) THE_OVERTIME " _
'         & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' AND WEEK_START_MONDAY < '" & strCurrentWeekOracle & "' AND WEEK_START_MONDAY >= '01-JAN-" & Right(strCurrentWeekOracle, 4) & "' AND STATUS = 'APPROVED'"
'Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'
'strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' AND WEEK_START_MONDAY = '" & strCurrentWeekOracle & "'"
'Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
'
'OraSession.BeginTrans
'dsTIME_SUBMISSION.Edit
'dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_TOTAL").Value = dsSHORT_TERM_DATA.Fields("THE_TOTAL").Value
'dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_REG").Value = dsSHORT_TERM_DATA.Fields("THE_REG").Value
'dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_HOLIDAY").Value = dsSHORT_TERM_DATA.Fields("THE_HOLIDAY").Value
'dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_VACATION").Value = dsSHORT_TERM_DATA.Fields("THE_VACATION").Value
'dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_PERSONAL").Value = dsSHORT_TERM_DATA.Fields("THE_PERSONAL").Value
'dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_SICK").Value = dsSHORT_TERM_DATA.Fields("THE_SICK").Value
'dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_OVERTIME").Value = dsSHORT_TERM_DATA.Fields("THE_OVERTIME").Value
'dsTIME_SUBMISSION.Update
'OraSession.CommitTrans
'
'
'strSql = "SELECT * FROM TIME_SUBMISSION WHERE WEEK_START_MONDAY = '" & strCurrentWeekOracle & "' AND EMPLOYEE_ID = '" & strEmployeeID & "'"


End Sub

Private Sub Button_Activation(strStatus As String, strCond As String, btnApp As CommandButton, btnDeny As CommandButton, btnMod As CommandButton, btnPrev As CommandButton)
' Activates buttons based on status
' All submitted, and on hold s that are conditional, can be approved/denied.  no others.
' Note that this function does not handle if a timesheet is not yet created; that is handled in the code prior to this function.

If strStatus = "SUBMITTED" Or (strStatus = "ON HOLD" And strCond = "Y") Then
        btnApp.Enabled = True
        btnDeny.Enabled = True
        btnMod.Enabled = True
        btnPrev.Enabled = True
Else
        btnApp.Enabled = False
        btnDeny.Enabled = False
        btnMod.Enabled = True
        btnPrev.Enabled = True
End If

'Select Case strStatus
'    Case "APPROVED"
'        btnApp.Enabled = False
'        btnDeny.Enabled = False
'        btnMod.Enabled = True
'        btnPrev.Enabled = True
'    Case "DENIED"
'        btnApp.Enabled = False
'        btnDeny.Enabled = False
'        btnMod.Enabled = True
'        btnPrev.Enabled = True
'    Case "ON HOLD"
'        btnApp.Enabled = True
'        btnDeny.Enabled = False
'        btnMod.Enabled = True
'        btnPrev.Enabled = True
'    Case "SUBMITTED"
'        btnApp.Enabled = True
'        btnDeny.Enabled = True
'        btnMod.Enabled = True
'        btnPrev.Enabled = True
''    Case "CONDITIONAL"
''        btnApp.Enabled = False
''        btnDeny.Enabled = False
''        btnMod.Enabled = True
''        btnPrev.Enabled = true
'    Case Else
'        btnApp.Enabled = False
'        btnDeny.Enabled = False
'        btnMod.Enabled = False
'        btnPrev.Enabled = False
'End Select

End Sub

Private Sub Emp1App_Click()
    If Is_Most_Recent(EmpID1) Then
        Call approve_action(EmpID1, Emp1Status.Text)
    Else
        MsgBox Emp1Name & " has previous weeks pending attention.  Please use the 'Previous Weeks' button to remedy all outstanding weeks before approving the current one."
    End If
End Sub
Private Sub Emp2App_Click()
    If Is_Most_Recent(EmpID2) Then
        Call approve_action(EmpID2, Emp2Status.Text)
    Else
        MsgBox Emp2Name & " has previous weeks pending attention.  Please use the 'Previous Weeks' button to remedy all outstanding weeks before approving the current one."
    End If
End Sub
Private Sub Emp3App_Click()
    If Is_Most_Recent(EmpID3) Then
        Call approve_action(EmpID3, Emp3Status.Text)
    Else
        MsgBox Emp3Name & " has previous weeks pending attention.  Please use the 'Previous Weeks' button to remedy all outstanding weeks before approving the current one."
    End If
End Sub
Private Sub Emp4App_Click()
    If Is_Most_Recent(EmpID4) Then
        Call approve_action(EmpID4, Emp4Status.Text)
    Else
        MsgBox Emp4Name & " has previous weeks pending attention.  Please use the 'Previous Weeks' button to remedy all outstanding weeks before approving the current one."
    End If
End Sub
Private Sub Emp5App_Click()
    If Is_Most_Recent(EmpID5) Then
        Call approve_action(EmpID5, Emp5Status.Text)
    Else
        MsgBox Emp5Name & " has previous weeks pending attention.  Please use the 'Previous Weeks' button to remedy all outstanding weeks before approving the current one."
    End If
End Sub
Private Sub Emp6App_Click()
    If Is_Most_Recent(EmpID6) Then
        Call approve_action(EmpID6, Emp6Status.Text)
    Else
        MsgBox Emp6Name & " has previous weeks pending attention.  Please use the 'Previous Weeks' button to remedy all outstanding weeks before approving the current one."
    End If
End Sub
Private Sub Emp7App_Click()
    If Is_Most_Recent(EmpID7) Then
        Call approve_action(EmpID7, Emp7Status.Text)
    Else
        MsgBox Emp7Name & " has previous weeks pending attention.  Please use the 'Previous Weeks' button to remedy all outstanding weeks before approving the current one."
    End If
End Sub
Private Sub Emp8App_Click()
    If Is_Most_Recent(EmpID8) Then
        Call approve_action(EmpID8, Emp8Status.Text)
    Else
        MsgBox Emp8Name & " has previous weeks pending attention.  Please use the 'Previous Weeks' button to remedy all outstanding weeks before approving the current one."
    End If
End Sub


Private Sub Emp1Deny_Click()
    Call deny_action(EmpID1, Emp1Status.Text)
End Sub
Private Sub Emp2Deny_Click()
    Call deny_action(EmpID2, Emp2Status.Text)
End Sub
Private Sub Emp3Deny_Click()
    Call deny_action(EmpID3, Emp3Status.Text)
End Sub
Private Sub Emp4Deny_Click()
    Call deny_action(EmpID4, Emp4Status.Text)
End Sub
Private Sub Emp5Deny_Click()
    Call deny_action(EmpID5, Emp5Status.Text)
End Sub
Private Sub Emp6Deny_Click()
    Call deny_action(EmpID6, Emp6Status.Text)
End Sub
Private Sub Emp7Deny_Click()
    Call deny_action(EmpID7, Emp7Status.Text)
End Sub
Private Sub Emp8Deny_Click()
    Call deny_action(EmpID8, Emp8Status.Text)
End Sub

Private Sub Emp1Mod_Click()
    Call ViewTime(EmpID1)
End Sub
Private Sub Emp2Mod_Click()
    Call ViewTime(EmpID2)
End Sub
Private Sub Emp3Mod_Click()
    Call ViewTime(EmpID3)
End Sub
Private Sub Emp4Mod_Click()
    Call ViewTime(EmpID4)
End Sub
Private Sub Emp5Mod_Click()
    Call ViewTime(EmpID5)
End Sub
Private Sub Emp6Mod_Click()
    Call ViewTime(EmpID6)
End Sub
Private Sub Emp7Mod_Click()
    Call ViewTime(EmpID7)
End Sub
Private Sub Emp8Mod_Click()
    Call ViewTime(EmpID8)
End Sub

Private Sub cmdPrev1_Click()
    Call ViewPrevWeek(EmpID1)
End Sub
Private Sub cmdPrev2_Click()
    Call ViewPrevWeek(EmpID2)
End Sub
Private Sub cmdPrev3_Click()
    Call ViewPrevWeek(EmpID3)
End Sub
Private Sub cmdPrev4_Click()
    Call ViewPrevWeek(EmpID4)
End Sub
Private Sub cmdPrev5_Click()
    Call ViewPrevWeek(EmpID5)
End Sub
Private Sub cmdPrev6_Click()
    Call ViewPrevWeek(EmpID6)
End Sub
Private Sub cmdPrev7_Click()
    Call ViewPrevWeek(EmpID7)
End Sub
Private Sub cmdPrev8_Click()
    Call ViewPrevWeek(EmpID8)
End Sub


Public Function ViewTime(empID As String) As Boolean
Dim TimeSheet As New frmPrint


TimeSheet.strEmployeeID = empID
TimeSheet.strWeek = strCurrentWeekOracleAPP
TimeSheet.Show 1

' Call form_load

End Function

Public Function ViewPrevWeek(strEmpID As String)
    Dim DateSelect As New frmDtSelect
    
    DateSelect.strEmployeeID = strEmpID
    DateSelect.Show 1
End Function
Private Sub cmdViewOld_Click()
    Dim DateSelectEmp As New frmDtEmpSelect
    Dim LocalEMPList As String

    strSql = "SELECT VIEW_ALLEMP_TIMESHEETS FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strUserID & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If dsSHORT_TERM_DATA.Fields("VIEW_ALLEMP_TIMESHEETS").Value = "N" Then
        DateSelectEmp.strEmpList = EmpList
    Else
        LocalEMPList = "("
        strSql = "SELECT EMPLOYEE_ID FROM AT_EMPLOYEE"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        While dsSHORT_TERM_DATA.EOF = 0
            LocalEMPList = LocalEMPList & "'" & dsSHORT_TERM_DATA.Fields("EMPLOYEE_ID").Value & "', "
            dsSHORT_TERM_DATA.Movenext
        Wend
        LocalEMPList = LocalEMPList & "'')"
        DateSelectEmp.strEmpList = LocalEMPList
    End If
    DateSelectEmp.Show 1
End Sub
Private Sub deny_action(strEmpID As String, strStatus As String)
' as there is only 1 case, as of writing this, where the Deny button is even enabled, I need not do a switch-case here... yet.
' technically, I don't even need the 2nd argument to this subroutine, but for ease's sake...


strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

OraSession.BeginTrans
dsSHORT_TERM_DATA.Edit
dsSHORT_TERM_DATA.Fields("STATUS").Value = "DENIED"
dsSHORT_TERM_DATA.Fields("CONDITIONAL_SUBMISSION").Value = "Y"
dsSHORT_TERM_DATA.Update
OraSession.CommitTrans

strSql = "INSERT INTO TIME_SUB_HISTORY (SELECT TS.*, SYSDATE, 'Denied - By " & strUser & "' FROM TIME_SUBMISSION TS WHERE " _
        & "EMPLOYEE_ID = '" & strEmpID & "' AND WEEK_START_MONDAY = '" & WeekInQuestionOracle & "')"
OraDatabase.ExecuteSQL (strSql)

Call Clear_All
Call form_load
End Sub

Private Sub approve_action(strEmpID As String, strStatus As String)
Dim newYTDVac As Double
Dim newYTDSick As Double
Dim newYTDPers As Double
Dim YTDVacAccr As Double
Dim YTDVacTaken As Double
Dim YTDSickAccr As Double
Dim YTDSickTaken As Double
Dim YTDPersTaken As Double
Dim YTDOT As Double
Dim OTWeeksWorked As Double
Dim trashVariable As Long


strSql = "UPDATE TIME_SUBMISSION SET SIGN_OFF_EMPID = '" & strUserID & "', SIGN_OFF_PC_USERID = '" & strUser & "', SIGN_OFF_PC = '" _
        & strComputer & "', SIGN_OFF_DATETIME = SYSDATE WHERE EMPLOYEE_ID = '" & strEmpID & "' AND WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "'"
trashVariable = OraDatabase.ExecuteSQL(strSql)

strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)



If InStr(1, strStatus, "ON HOLD") <> 0 Then
        OraSession.BeginTrans
        dsSHORT_TERM_DATA.Edit
        dsSHORT_TERM_DATA.Fields("STATUS").Value = "APPROVED"
        dsSHORT_TERM_DATA.Fields("CONDITIONAL_SUBMISSION").Value = "Y"
        dsSHORT_TERM_DATA.Update
        OraSession.CommitTrans
ElseIf InStr(1, strStatus, "SUBMITTED") <> 0 Then
        OraSession.BeginTrans
        dsSHORT_TERM_DATA.Edit
        dsSHORT_TERM_DATA.Fields("STATUS").Value = "APPROVED"
        dsSHORT_TERM_DATA.Fields("CONDITIONAL_SUBMISSION").Value = "N"
        dsSHORT_TERM_DATA.Update
        OraSession.CommitTrans
End If



'     IMPORTANT NOTE:  All YTD_END calculations are now done in the ADP script.  hence, all following lines commented out.

'strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND WEEK_START_MONDAY <= '" & strCurrentWeekOracleAPP & "' AND (STATUS != 'APPROVED' OR CONDITIONAL_SUBMISSION = 'Y')"
'Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'
'If dsSHORT_TERM_DATA.EOF = True Then ' Basically, if there are ANY records aren't approved non-conditionally prior to this week, don't do the following.
'
'    strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND WEEK_START_MONDAY = '" & strCurrentWeekOracleAPP & "'"
'    Set dsEMPLOYEE = OraDatabase.DbCreateDynaset(strSql, 0&)
'
'    newYTDVac = Val(dsEMPLOYEE.fields("YTD_WEEK_START_VACATION_BAL").Value) + Val(dsEMPLOYEE.fields("WEEKLY_ACCRUAL_VACATION").Value) - Val(dsEMPLOYEE.fields("WEEK_TOTAL_VACATION").Value)
'    newYTDSick = Val(dsEMPLOYEE.fields("YTD_WEEK_START_SICK_BAL").Value) + Val(dsEMPLOYEE.fields("WEEKLY_ACCRUAL_SICK").Value) - Val(dsEMPLOYEE.fields("WEEK_TOTAL_SICK").Value)
'    newYTDPers = Val(dsEMPLOYEE.fields("YTD_WEEK_START_PERSONAL_BAL").Value) - Val(dsEMPLOYEE.fields("WEEK_TOTAL_PERSONAL").Value)
'    YTDOT = Val(dsEMPLOYEE.fields("YTD_WEEK_START_TOTAL_OVERTIME").Value) + Val(dsEMPLOYEE.fields("WEEK_TOTAL_OVERTIME").Value)
'
'    ' always guaranteed non-zero, since the current week is at least 1
'    strSql = "SELECT COUNT(*) THE_COUNT FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND WEEK_START_MONDAY <= '" & strCurrentWeekOracleAPP & "' " _
'            & "AND WEEK_START_MONDAY >= '01-JAN-" & Right(strCurrentWeekOracleAPP, 4) & "'"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'
'    OraSession.BeginTrans
'    dsEMPLOYEE.Edit
'    dsEMPLOYEE.fields("YTD_WEEK_END_VACATION_BAL").Value = newYTDVac
'    dsEMPLOYEE.fields("YTD_WEEK_END_PERSONAL_BAL").Value = newYTDPers
'    dsEMPLOYEE.fields("YTD_WEEK_END_SICK_BAL").Value = newYTDSick
'    dsEMPLOYEE.fields("YTD_WEEK_END_TOTAL_OVERTIME").Value = YTDOT
'    dsEMPLOYEE.fields("YTD_WEEK_END_AVERAGE_OT").Value = YTDOT / dsSHORT_TERM_DATA.fields("THE_COUNT").Value
'    dsEMPLOYEE.Update
'    OraSession.CommitTrans
'
'    strSql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmpID & "'"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'
'    YTDVacTaken = Val("" & dsSHORT_TERM_DATA.fields("VACATION_YTD_TAKEN").Value) + Val(dsEMPLOYEE.fields("WEEK_TOTAL_VACATION").Value)
'    YTDVacAccr = Val("" & dsSHORT_TERM_DATA.fields("VACATION_YTD_ACCRUED").Value) + Val(dsEMPLOYEE.fields("WEEKLY_ACCRUAL_VACATION").Value)
'    YTDSickTaken = Val("" & dsSHORT_TERM_DATA.fields("SICK_YTD_TAKEN").Value) + Val(dsEMPLOYEE.fields("WEEK_TOTAL_SICK").Value)
'    YTDSickAccr = Val("" & dsSHORT_TERM_DATA.fields("SICK_YTD_ACCRUED").Value) + Val(dsEMPLOYEE.fields("WEEKLY_ACCRUAL_SICK").Value)
'    YTDPersTaken = Val("" & dsSHORT_TERM_DATA.fields("PERSONAL_YTD_TAKEN").Value) + Val(dsEMPLOYEE.fields("WEEK_TOTAL_PERSONAL").Value)
'
'    OraSession.BeginTrans
'    dsSHORT_TERM_DATA.Edit
'    dsSHORT_TERM_DATA.fields("VACATION_YTD_TAKEN").Value = YTDVacTaken
'    dsSHORT_TERM_DATA.fields("VACATION_YTD_ACCRUED").Value = YTDVacAccr
'    dsSHORT_TERM_DATA.fields("SICK_YTD_TAKEN").Value = YTDSickTaken
'    dsSHORT_TERM_DATA.fields("SICK_YTD_ACCRUED").Value = YTDSickAccr
'    dsSHORT_TERM_DATA.fields("PERSONAL_YTD_TAKEN").Value = YTDPersTaken
'    dsSHORT_TERM_DATA.fields("VACATION_YTD_REMAIN").Value = newYTDVac
'    dsSHORT_TERM_DATA.fields("PERSONAL_YTD_REMAIN").Value = newYTDPers
'    dsSHORT_TERM_DATA.fields("SICK_YTD_REMAIN").Value = newYTDSick
'    dsSHORT_TERM_DATA.Update
'    OraSession.CommitTrans
'End If

Call Clear_All
Call form_load

End Sub

Function GenerateEmployeeList(strSupervisor As String) As String
Dim dsEMPLIST As Object

Dim strReturnList As String
strReturnList = ""
' this function returns a comma-separated list of employees that a supervisor can validate timesheets for.
' Prior to Monday 11AM (or, technically, after Monday noon, although the approve button is deactivated before Friday 2PM)
' a supervisor can only approve his immediate subordinates.
' After 11AM, this function is called recursively, as a supervisor's supervisor (or his supervisor) can
' Approve the subordinates.
' The final entry (I.E. the one that wouldn't have a comma after it) is handled in the calling function.
strSql = "SELECT EMPLOYEE_ID, SUPERVISORY_STATUS FROM AT_EMPLOYEE WHERE SUPERVISOR_ID = '" & strSupervisor & "'"
Set dsEMPLIST = OraDatabase.DbCreateDynaset(strSql, 0&)

If (Format(Now, "dddd") <> "Monday") Or (Format(Now, "h") < 11) Then
' standard list
    Is_Past_11AM = False
    While dsEMPLIST.EOF = 0
        strReturnList = strReturnList & "'" & dsEMPLIST.Fields("EMPLOYEE_ID").Value & "', "
        dsEMPLIST.Movenext
    Wend

Else
' if Monday after 11, must recursively call to get employee's employees, et al
    Is_Past_11AM = True
    While dsEMPLIST.EOF = 0
        If dsEMPLIST.Fields("SUPERVISORY_STATUS").Value = "SUPERVISOR" Then
            strReturnList = strReturnList & "'" & dsEMPLIST.Fields("EMPLOYEE_ID").Value & "', " & GenerateEmployeeList(dsEMPLIST.Fields("EMPLOYEE_ID").Value)
            dsEMPLIST.Movenext
        Else
            strReturnList = strReturnList & "'" & dsEMPLIST.Fields("EMPLOYEE_ID").Value & "', "
            dsEMPLIST.Movenext
        End If
    Wend
        
End If

' For the executive director, who has no supervisor, allow to approve his own sheets.
'strSql = "SELECT SUPERVISOR_ID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strSupervisor & "'"
'Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'If dsSHORT_TERM_DATA.Fields("SUPERVISOR_ID").Value = "N/A" Then
'    strReturnList = strReturnList & "'" & strSupervisor & "', "
'End If

' Edit Adam Walter, June 2008.
' instead of the Port director approving his own sheets, from now on, people delegated with that ability
' will do the approving.  commenting out above, writing below.
strSql = "SELECT EXECDIR_APP FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strSupervisor & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
If dsSHORT_TERM_DATA.Fields("EXECDIR_APP").Value = "Y" Then
    strSql = "SELECT EMPLOYEE_ID FROM AT_EMPLOYEE WHERE SUPERVISOR_ID = 'N/A'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    strReturnList = strReturnList & "'" & dsSHORT_TERM_DATA.Fields("EMPLOYEE_ID").Value & "', "
End If

GenerateEmployeeList = strReturnList

End Function

Function Is_Most_Recent(TheEmpID As String) As Boolean

    strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & TheEmpID & "' AND WEEK_START_MONDAY < '" & strCurrentWeekOracleAPP & "' AND (STATUS = 'SUBMITTED' OR (STATUS = 'ON HOLD' AND CONDITIONAL_SUBMISSION = 'Y'))"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsSHORT_TERM_DATA.Recordcount = 0 Then
        Is_Most_Recent = True
    Else
        Is_Most_Recent = False
    End If

End Function

Private Sub PrevWeek_Click()

Dim PastApps As New SupPastApp


PastApps.EmpList = EmpList
PastApps.strWeekOracle = strCurrentWeekOracleAPP
PastApps.strWeekHuman = strCurrentWeekHumanAPP
PastApps.Show 1

strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID IN " & EmpList & " AND WEEK_START_MONDAY < '" & strCurrentWeekOracleAPP & "' AND (STATUS = 'SUBMITTED' OR (STATUS = 'ON HOLD' AND CONDITIONAL_SUBMISSION = 'Y'))"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

If dsSHORT_TERM_DATA.Recordcount = 0 Then
    PrevWeek.Enabled = False
Else
    PrevWeek.Enabled = True
End If

End Sub

Private Sub Clear_All()

EmpID1 = ""
Emp1Name.Text = ""
Emp1Reg.Text = ""
Emp1Hol.Text = ""
Emp1Vac.Text = ""
Emp1Per.Text = ""
Emp1Sick.Text = ""
Emp1RTtl.Text = ""
Emp1OTtl.Text = ""
Emp1GTtl.Text = ""
Emp1Status.Text = ""
Emp1App.Enabled = False
Emp1Deny.Enabled = False
Emp1Mod.Enabled = False
cmdPrev1.Enabled = False

EmpID2 = ""
Emp2Name.Text = ""
Emp2Reg.Text = ""
Emp2Hol.Text = ""
Emp2Vac.Text = ""
Emp2Per.Text = ""
Emp2Sick.Text = ""
Emp2RTtl.Text = ""
Emp2OTtl.Text = ""
Emp2GTtl.Text = ""
Emp2Status.Text = ""
Emp2App.Enabled = False
Emp2Deny.Enabled = False
Emp2Mod.Enabled = False
cmdPrev2.Enabled = False

EmpID3 = ""
Emp3Name.Text = ""
Emp3Reg.Text = ""
Emp3Hol.Text = ""
Emp3Vac.Text = ""
Emp3Per.Text = ""
Emp3Sick.Text = ""
Emp3RTtl.Text = ""
Emp3OTtl.Text = ""
Emp3GTtl.Text = ""
Emp3Status.Text = ""
Emp3App.Enabled = False
Emp3Deny.Enabled = False
Emp3Mod.Enabled = False
cmdPrev3.Enabled = False

EmpID4 = ""
Emp4Name.Text = ""
Emp4Reg.Text = ""
Emp4Hol.Text = ""
Emp4Vac.Text = ""
Emp4Per.Text = ""
Emp4Sick.Text = ""
Emp4RTtl.Text = ""
Emp4OTtl.Text = ""
Emp4GTtl.Text = ""
Emp4Status.Text = ""
Emp4App.Enabled = False
Emp4Deny.Enabled = False
Emp4Mod.Enabled = False
cmdPrev4.Enabled = False

EmpID5 = ""
Emp5Name.Text = ""
Emp5Reg.Text = ""
Emp5Hol.Text = ""
Emp5Vac.Text = ""
Emp5Per.Text = ""
Emp5Sick.Text = ""
Emp5RTtl.Text = ""
Emp5OTtl.Text = ""
Emp5GTtl.Text = ""
Emp5Status.Text = ""
Emp5App.Enabled = False
Emp5Deny.Enabled = False
Emp5Mod.Enabled = False
cmdPrev5.Enabled = False

EmpID6 = ""
Emp6Name.Text = ""
Emp6Reg.Text = ""
Emp6Hol.Text = ""
Emp6Vac.Text = ""
Emp6Per.Text = ""
Emp6Sick.Text = ""
Emp6RTtl.Text = ""
Emp6OTtl.Text = ""
Emp6GTtl.Text = ""
Emp6Status.Text = ""
Emp6App.Enabled = False
Emp6Deny.Enabled = False
Emp6Mod.Enabled = False
cmdPrev6.Enabled = False

EmpID7 = ""
Emp7Name.Text = ""
Emp7Reg.Text = ""
Emp7Hol.Text = ""
Emp7Vac.Text = ""
Emp7Per.Text = ""
Emp7Sick.Text = ""
Emp7RTtl.Text = ""
Emp7OTtl.Text = ""
Emp7GTtl.Text = ""
Emp7Status.Text = ""
Emp7App.Enabled = False
Emp7Deny.Enabled = False
Emp7Mod.Enabled = False
cmdPrev7.Enabled = False

EmpID8 = ""
Emp8Name.Text = ""
Emp8Reg.Text = ""
Emp8Hol.Text = ""
Emp8Vac.Text = ""
Emp8Per.Text = ""
Emp8Sick.Text = ""
Emp8RTtl.Text = ""
Emp8OTtl.Text = ""
Emp8GTtl.Text = ""
Emp8Status.Text = ""
Emp8App.Enabled = False
Emp8Deny.Enabled = False
Emp8Mod.Enabled = False
cmdPrev8.Enabled = False



End Sub
