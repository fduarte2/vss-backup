VERSION 5.00
Begin VB.Form frmTimeSheetPast 
   BackColor       =   &H00FFFFFF&
   Caption         =   "Time Sheet"
   ClientHeight    =   11130
   ClientLeft      =   750
   ClientTop       =   690
   ClientWidth     =   10920
   ForeColor       =   &H00000000&
   KeyPreview      =   -1  'True
   LinkTopic       =   "Form1"
   ScaleHeight     =   12861.73
   ScaleMode       =   0  'User
   ScaleWidth      =   10920
   Begin VB.TextBox txtStartWeekOT 
      Alignment       =   1  'Right Justify
      Appearance      =   0  'Flat
      BackColor       =   &H00E0E0E0&
      Height          =   375
      Left            =   9480
      TabIndex        =   118
      Top             =   2880
      Width           =   855
   End
   Begin VB.TextBox txtAVGOT 
      Alignment       =   1  'Right Justify
      Appearance      =   0  'Flat
      BackColor       =   &H00E0E0E0&
      Height          =   375
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   106
      Top             =   10080
      Width           =   855
   End
   Begin VB.TextBox txtYTDOT 
      Alignment       =   1  'Right Justify
      Appearance      =   0  'Flat
      BackColor       =   &H00E0E0E0&
      Height          =   375
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   105
      Top             =   9600
      Width           =   855
   End
   Begin VB.TextBox txtYTDVac 
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
      Height          =   375
      Left            =   6240
      Locked          =   -1  'True
      TabIndex        =   100
      Top             =   9120
      Width           =   855
   End
   Begin VB.TextBox txtYTDPers 
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
      Height          =   375
      Left            =   7320
      Locked          =   -1  'True
      TabIndex        =   99
      Top             =   9120
      Width           =   855
   End
   Begin VB.TextBox txtYTDSick 
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
      ForeColor       =   &H00000000&
      Height          =   375
      Left            =   8400
      Locked          =   -1  'True
      TabIndex        =   98
      Top             =   9120
      Width           =   855
   End
   Begin VB.TextBox txtSickWeekAccr 
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
      Height          =   375
      Left            =   8400
      Locked          =   -1  'True
      TabIndex        =   96
      Top             =   8640
      Width           =   855
   End
   Begin VB.TextBox txtVacWeekAccr 
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
      Height          =   375
      Left            =   6240
      Locked          =   -1  'True
      TabIndex        =   95
      Top             =   8640
      Width           =   855
   End
   Begin VB.TextBox txtStartWeekSick 
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
      Height          =   375
      Left            =   8400
      Locked          =   -1  'True
      TabIndex        =   93
      Top             =   2880
      Width           =   855
   End
   Begin VB.TextBox txtStartWeekPers 
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
      Height          =   375
      Left            =   7320
      Locked          =   -1  'True
      TabIndex        =   92
      Top             =   2880
      Width           =   855
   End
   Begin VB.TextBox txtStartWeekVac 
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
      Height          =   375
      Left            =   6240
      Locked          =   -1  'True
      TabIndex        =   91
      Top             =   2880
      Width           =   855
   End
   Begin VB.Frame frmHours 
      BackColor       =   &H00C0C0C0&
      BorderStyle     =   0  'None
      Height          =   4815
      Left            =   360
      TabIndex        =   73
      Top             =   3360
      Width           =   10095
      Begin VB.TextBox TtlOHrs 
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
         Height          =   375
         Left            =   9120
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   116
         TabStop         =   0   'False
         Top             =   4320
         Width           =   855
      End
      Begin VB.TextBox TtlSHrs 
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
         Height          =   375
         Left            =   8040
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   115
         TabStop         =   0   'False
         Top             =   4320
         Width           =   855
      End
      Begin VB.TextBox TtlPHrs 
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
         Height          =   375
         Left            =   6960
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   114
         TabStop         =   0   'False
         Top             =   4320
         Width           =   855
      End
      Begin VB.TextBox TtlVHrs 
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
         Height          =   375
         Left            =   5880
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   113
         TabStop         =   0   'False
         Top             =   4320
         Width           =   855
      End
      Begin VB.TextBox TtlHHrs 
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
         Height          =   375
         Left            =   4800
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   112
         TabStop         =   0   'False
         Top             =   4320
         Width           =   855
      End
      Begin VB.TextBox TtlRHrs 
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
         Height          =   375
         Left            =   3720
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   111
         TabStop         =   0   'False
         Top             =   4320
         Width           =   855
      End
      Begin VB.TextBox TotalHrs 
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
         Height          =   375
         Left            =   2520
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   110
         TabStop         =   0   'False
         Top             =   4320
         Width           =   855
      End
      Begin VB.TextBox MoOHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   9120
         MaxLength       =   5
         TabIndex        =   5
         Top             =   840
         Width           =   855
      End
      Begin VB.TextBox TuOHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   9120
         MaxLength       =   5
         TabIndex        =   10
         Top             =   1320
         Width           =   855
      End
      Begin VB.TextBox WeOHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   9120
         MaxLength       =   5
         TabIndex        =   15
         Top             =   1800
         Width           =   855
      End
      Begin VB.TextBox ThOHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   9120
         MaxLength       =   5
         TabIndex        =   20
         Top             =   2280
         Width           =   855
      End
      Begin VB.TextBox FrOHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   9120
         MaxLength       =   5
         TabIndex        =   25
         Top             =   2760
         Width           =   855
      End
      Begin VB.TextBox SaOHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   9120
         MaxLength       =   5
         TabIndex        =   27
         Top             =   3240
         Width           =   855
      End
      Begin VB.TextBox SuOHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   9120
         MaxLength       =   5
         TabIndex        =   29
         Top             =   3720
         Width           =   855
      End
      Begin VB.TextBox MoSHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   8040
         MaxLength       =   5
         TabIndex        =   4
         Top             =   840
         Width           =   855
      End
      Begin VB.TextBox TuSHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   8040
         MaxLength       =   5
         TabIndex        =   9
         Top             =   1320
         Width           =   855
      End
      Begin VB.TextBox WeSHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   8040
         MaxLength       =   5
         TabIndex        =   14
         Top             =   1800
         Width           =   855
      End
      Begin VB.TextBox ThSHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   8040
         MaxLength       =   5
         TabIndex        =   19
         Top             =   2280
         Width           =   855
      End
      Begin VB.TextBox FrSHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   8040
         MaxLength       =   5
         TabIndex        =   24
         Top             =   2760
         Width           =   855
      End
      Begin VB.TextBox SaSHrs 
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
         Enabled         =   0   'False
         Height          =   375
         Left            =   8040
         MaxLength       =   5
         TabIndex        =   43
         TabStop         =   0   'False
         Top             =   3240
         Width           =   855
      End
      Begin VB.TextBox SuSHrs 
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
         Enabled         =   0   'False
         Height          =   375
         Left            =   8040
         MaxLength       =   5
         TabIndex        =   47
         TabStop         =   0   'False
         Top             =   3720
         Width           =   855
      End
      Begin VB.TextBox MoPHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   6960
         MaxLength       =   5
         TabIndex        =   3
         Top             =   840
         Width           =   855
      End
      Begin VB.TextBox TuPHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   6960
         MaxLength       =   5
         TabIndex        =   8
         Top             =   1320
         Width           =   855
      End
      Begin VB.TextBox WePHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   6960
         MaxLength       =   5
         TabIndex        =   13
         Top             =   1800
         Width           =   855
      End
      Begin VB.TextBox ThPHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   6960
         MaxLength       =   5
         TabIndex        =   18
         Top             =   2280
         Width           =   855
      End
      Begin VB.TextBox FrPHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   6960
         MaxLength       =   5
         TabIndex        =   23
         Top             =   2760
         Width           =   855
      End
      Begin VB.TextBox SaPHrs 
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
         Enabled         =   0   'False
         Height          =   375
         Left            =   6960
         MaxLength       =   5
         TabIndex        =   42
         TabStop         =   0   'False
         Top             =   3240
         Width           =   855
      End
      Begin VB.TextBox SuPHrs 
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
         Enabled         =   0   'False
         Height          =   375
         Left            =   6960
         MaxLength       =   5
         TabIndex        =   46
         TabStop         =   0   'False
         Top             =   3720
         Width           =   855
      End
      Begin VB.TextBox MoVHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   5880
         MaxLength       =   5
         TabIndex        =   2
         Top             =   840
         Width           =   855
      End
      Begin VB.TextBox TuVHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   5880
         MaxLength       =   5
         TabIndex        =   7
         Top             =   1320
         Width           =   855
      End
      Begin VB.TextBox WeVHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   5880
         MaxLength       =   5
         TabIndex        =   12
         Top             =   1800
         Width           =   855
      End
      Begin VB.TextBox ThVHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   5880
         MaxLength       =   5
         TabIndex        =   17
         Top             =   2280
         Width           =   855
      End
      Begin VB.TextBox FrVHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   5880
         MaxLength       =   5
         TabIndex        =   22
         Top             =   2760
         Width           =   855
      End
      Begin VB.TextBox SaVHrs 
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
         Enabled         =   0   'False
         Height          =   375
         Left            =   5880
         MaxLength       =   5
         TabIndex        =   41
         TabStop         =   0   'False
         Top             =   3240
         Width           =   855
      End
      Begin VB.TextBox SuVHrs 
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
         Enabled         =   0   'False
         Height          =   375
         Left            =   5880
         MaxLength       =   5
         TabIndex        =   45
         TabStop         =   0   'False
         Top             =   3720
         Width           =   855
      End
      Begin VB.TextBox MoHHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   4800
         MaxLength       =   5
         TabIndex        =   1
         Top             =   840
         Width           =   855
      End
      Begin VB.TextBox TuHHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   4800
         MaxLength       =   5
         TabIndex        =   6
         Top             =   1320
         Width           =   855
      End
      Begin VB.TextBox WeHHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   4800
         MaxLength       =   5
         TabIndex        =   11
         Top             =   1800
         Width           =   855
      End
      Begin VB.TextBox ThHHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   4800
         MaxLength       =   5
         TabIndex        =   16
         Top             =   2280
         Width           =   855
      End
      Begin VB.TextBox FrHHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   4800
         MaxLength       =   5
         TabIndex        =   21
         Top             =   2760
         Width           =   855
      End
      Begin VB.TextBox SaHHrs 
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
         Enabled         =   0   'False
         Height          =   375
         Left            =   4800
         MaxLength       =   5
         TabIndex        =   40
         TabStop         =   0   'False
         Top             =   3240
         Width           =   855
      End
      Begin VB.TextBox SuHHrs 
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
         Enabled         =   0   'False
         Height          =   375
         Left            =   4800
         MaxLength       =   5
         TabIndex        =   44
         TabStop         =   0   'False
         Top             =   3720
         Width           =   855
      End
      Begin VB.TextBox MoRHrs 
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
         Height          =   375
         Left            =   3720
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   34
         TabStop         =   0   'False
         Top             =   840
         Width           =   855
      End
      Begin VB.TextBox TuRHrs 
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
         Height          =   375
         Left            =   3720
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   35
         TabStop         =   0   'False
         Top             =   1320
         Width           =   855
      End
      Begin VB.TextBox WeRHrs 
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
         Height          =   375
         Left            =   3720
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   36
         TabStop         =   0   'False
         Top             =   1800
         Width           =   855
      End
      Begin VB.TextBox ThRHrs 
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
         Height          =   375
         Left            =   3720
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   37
         TabStop         =   0   'False
         Top             =   2280
         Width           =   855
      End
      Begin VB.TextBox FrRHrs 
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
         Height          =   375
         Left            =   3720
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   39
         TabStop         =   0   'False
         Top             =   2760
         Width           =   855
      End
      Begin VB.TextBox SaRHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   3720
         MaxLength       =   5
         TabIndex        =   26
         Top             =   3240
         Width           =   855
      End
      Begin VB.TextBox SuRHrs 
         Alignment       =   1  'Right Justify
         Appearance      =   0  'Flat
         BeginProperty DataFormat 
            Type            =   1
            Format          =   "0.00"
            HaveTrueFalseNull=   0
            FirstDayOfWeek  =   0
            FirstWeekOfYear =   0
            LCID            =   1033
            SubFormatType   =   1
         EndProperty
         Height          =   375
         Left            =   3720
         MaxLength       =   5
         TabIndex        =   28
         Top             =   3720
         Width           =   855
      End
      Begin VB.TextBox MoTtl 
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
         Height          =   375
         Left            =   2520
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   38
         TabStop         =   0   'False
         Top             =   840
         Width           =   855
      End
      Begin VB.TextBox TuTtl 
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
         Height          =   375
         Left            =   2520
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   65
         TabStop         =   0   'False
         Top             =   1320
         Width           =   855
      End
      Begin VB.TextBox WeTtl 
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
         Height          =   375
         Left            =   2520
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   64
         TabStop         =   0   'False
         Top             =   1800
         Width           =   855
      End
      Begin VB.TextBox ThTtl 
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
         Height          =   375
         Left            =   2520
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   63
         TabStop         =   0   'False
         Top             =   2280
         Width           =   855
      End
      Begin VB.TextBox FrTtl 
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
         Height          =   375
         Left            =   2520
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   62
         TabStop         =   0   'False
         Top             =   2760
         Width           =   855
      End
      Begin VB.TextBox SaTtl 
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
         Height          =   375
         Left            =   2520
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   61
         TabStop         =   0   'False
         Top             =   3240
         Width           =   855
      End
      Begin VB.TextBox SuTtl 
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
         Height          =   375
         Left            =   2520
         Locked          =   -1  'True
         MaxLength       =   5
         TabIndex        =   60
         TabStop         =   0   'False
         Top             =   3720
         Width           =   855
      End
      Begin VB.TextBox MoDate 
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
         Height          =   375
         Left            =   1320
         Locked          =   -1  'True
         TabIndex        =   53
         TabStop         =   0   'False
         Top             =   840
         Width           =   975
      End
      Begin VB.TextBox TuDate 
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
         Height          =   375
         Left            =   1320
         Locked          =   -1  'True
         TabIndex        =   54
         TabStop         =   0   'False
         Top             =   1320
         Width           =   975
      End
      Begin VB.TextBox WeDate 
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
         Height          =   375
         Left            =   1320
         Locked          =   -1  'True
         TabIndex        =   55
         TabStop         =   0   'False
         Top             =   1800
         Width           =   975
      End
      Begin VB.TextBox ThDate 
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
         Height          =   375
         Left            =   1320
         Locked          =   -1  'True
         TabIndex        =   56
         TabStop         =   0   'False
         Top             =   2280
         Width           =   975
      End
      Begin VB.TextBox FrDate 
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
         Height          =   375
         Left            =   1320
         Locked          =   -1  'True
         TabIndex        =   57
         TabStop         =   0   'False
         Top             =   2760
         Width           =   975
      End
      Begin VB.TextBox SaDate 
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
         Height          =   375
         Left            =   1320
         Locked          =   -1  'True
         TabIndex        =   58
         TabStop         =   0   'False
         Top             =   3240
         Width           =   975
      End
      Begin VB.TextBox SuDate 
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
         Height          =   375
         Left            =   1320
         Locked          =   -1  'True
         TabIndex        =   59
         TabStop         =   0   'False
         Top             =   3720
         Width           =   975
      End
      Begin VB.Label Totals 
         BackColor       =   &H00C0C0C0&
         Caption         =   "Totals"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   14.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   1440
         TabIndex        =   109
         Top             =   4320
         Width           =   855
      End
      Begin VB.Line Line15 
         X1              =   0
         X2              =   10080
         Y1              =   4200
         Y2              =   4200
      End
      Begin VB.Label OHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Overtime"
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
         Left            =   9120
         TabIndex        =   89
         Top             =   240
         Width           =   750
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
         Left            =   8040
         TabIndex        =   88
         Top             =   240
         Width           =   360
      End
      Begin VB.Label PHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Personal"
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
         Left            =   6960
         TabIndex        =   87
         Top             =   240
         Width           =   690
      End
      Begin VB.Label VHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Vacation"
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
         TabIndex        =   86
         Top             =   240
         Width           =   690
      End
      Begin VB.Label HHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Holiday"
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
         Left            =   4800
         TabIndex        =   85
         Top             =   240
         Width           =   615
      End
      Begin VB.Label RHours 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Regular"
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
         Left            =   3720
         TabIndex        =   84
         Top             =   240
         Width           =   630
      End
      Begin VB.Label Total 
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
         Height          =   255
         Left            =   2520
         TabIndex        =   83
         Top             =   240
         Width           =   495
      End
      Begin VB.Line Line9 
         X1              =   0
         X2              =   10080
         Y1              =   480
         Y2              =   480
      End
      Begin VB.Label Date 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Date"
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
         Left            =   1320
         TabIndex        =   82
         Top             =   240
         Width           =   375
      End
      Begin VB.Label Monday 
         BackColor       =   &H00C0C0C0&
         Caption         =   "Mon"
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
         Left            =   120
         TabIndex        =   81
         Top             =   840
         Width           =   855
      End
      Begin VB.Label Sunday 
         BackColor       =   &H00C0C0C0&
         Caption         =   "Sun"
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
         Left            =   120
         TabIndex        =   80
         Top             =   3720
         Width           =   735
      End
      Begin VB.Label Saturday 
         BackColor       =   &H00C0C0C0&
         Caption         =   "Sat"
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
         Left            =   120
         TabIndex        =   79
         Top             =   3240
         Width           =   735
      End
      Begin VB.Label Friday 
         BackColor       =   &H00C0C0C0&
         Caption         =   "Fri"
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
         Left            =   120
         TabIndex        =   78
         Top             =   2760
         Width           =   495
      End
      Begin VB.Label Thursday 
         BackColor       =   &H00C0C0C0&
         Caption         =   "Thu"
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
         Left            =   120
         TabIndex        =   77
         Top             =   2280
         Width           =   855
      End
      Begin VB.Label Wednesday 
         BackColor       =   &H00C0C0C0&
         Caption         =   "Wed"
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
         Left            =   120
         TabIndex        =   76
         Top             =   1800
         Width           =   975
      End
      Begin VB.Label Tuesday 
         BackColor       =   &H00C0C0C0&
         Caption         =   "Tue"
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
         Left            =   120
         TabIndex        =   75
         Top             =   1320
         Width           =   735
      End
      Begin VB.Label Day 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Day"
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
         TabIndex        =   74
         Top             =   240
         Width           =   315
      End
      Begin VB.Line Line8 
         X1              =   9000
         X2              =   9000
         Y1              =   0
         Y2              =   5040
      End
      Begin VB.Line Line7 
         X1              =   7920
         X2              =   7920
         Y1              =   0
         Y2              =   5040
      End
      Begin VB.Line Line6 
         X1              =   6840
         X2              =   6840
         Y1              =   0
         Y2              =   5040
      End
      Begin VB.Line Line5 
         X1              =   5760
         X2              =   5760
         Y1              =   0
         Y2              =   5040
      End
      Begin VB.Line Line4 
         X1              =   4680
         X2              =   4680
         Y1              =   0
         Y2              =   5040
      End
      Begin VB.Line Line3 
         X1              =   3600
         X2              =   3600
         Y1              =   0
         Y2              =   5040
      End
      Begin VB.Line Line2 
         X1              =   2400
         X2              =   2400
         Y1              =   0
         Y2              =   5040
      End
      Begin VB.Line Line1 
         X1              =   1200
         X2              =   1200
         Y1              =   0
         Y2              =   4200
      End
   End
   Begin VB.PictureBox stbarCM 
      Align           =   2  'Align Bottom
      Height          =   285
      Left            =   0
      ScaleHeight     =   225
      ScaleWidth      =   10860
      TabIndex        =   70
      Top             =   10845
      Width           =   10920
   End
   Begin VB.Frame UpperFrame 
      BackColor       =   &H00FFFFFF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   2085
      Left            =   360
      TabIndex        =   66
      Top             =   120
      Width           =   10305
      Begin VB.CommandButton cmdApprove 
         Caption         =   "Approve"
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
         Height          =   375
         Left            =   8880
         TabIndex        =   33
         Top             =   1560
         Visible         =   0   'False
         Width           =   1245
      End
      Begin VB.TextBox txtStatus 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   4920
         Locked          =   -1  'True
         TabIndex        =   52
         TabStop         =   0   'False
         Top             =   1320
         Width           =   1815
      End
      Begin VB.CommandButton btnPastSheets 
         Caption         =   "Past Timesheets"
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
         Left            =   6960
         TabIndex        =   32
         Top             =   1560
         Width           =   1725
      End
      Begin VB.CommandButton Submit 
         Caption         =   "Submit"
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
         Left            =   8880
         TabIndex        =   31
         Top             =   1080
         Width           =   1245
      End
      Begin VB.TextBox txtWeek 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   1440
         Locked          =   -1  'True
         TabIndex        =   48
         TabStop         =   0   'False
         Top             =   1680
         Width           =   2175
      End
      Begin VB.CommandButton cmdSave 
         Caption         =   "Save"
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
         Left            =   8880
         TabIndex        =   0
         Top             =   600
         Visible         =   0   'False
         Width           =   1245
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "Print"
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
         Left            =   6960
         TabIndex        =   30
         Top             =   1080
         Width           =   1725
      End
      Begin VB.TextBox txtDept 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   1440
         Locked          =   -1  'True
         TabIndex        =   49
         TabStop         =   0   'False
         Top             =   1320
         Width           =   2175
      End
      Begin VB.TextBox txtEmpID 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   1440
         Locked          =   -1  'True
         TabIndex        =   51
         TabStop         =   0   'False
         Tag             =   "NInvoice Number"
         Top             =   600
         Width           =   2175
      End
      Begin VB.TextBox txtEmpName 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   1440
         Locked          =   -1  'True
         TabIndex        =   50
         TabStop         =   0   'False
         Top             =   960
         Width           =   2175
      End
      Begin VB.Label Label1 
         BackStyle       =   0  'Transparent
         Caption         =   "Status"
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
         Left            =   4200
         TabIndex        =   90
         Top             =   1320
         Width           =   615
      End
      Begin VB.Label Label 
         Alignment       =   2  'Center
         BackColor       =   &H00FFFFFF&
         Caption         =   "Weekly Time Sheet"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   21.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   615
         Left            =   3360
         TabIndex        =   72
         Top             =   0
         Width           =   4455
      End
      Begin VB.Label Week 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Week Starting"
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
         Left            =   165
         TabIndex        =   71
         Top             =   1680
         Width           =   1155
      End
      Begin VB.Label Department 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Department"
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
         Left            =   300
         TabIndex        =   69
         Top             =   1320
         Width           =   975
      End
      Begin VB.Label EmployeeID 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Employee ID"
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
         Left            =   300
         TabIndex        =   67
         Top             =   600
         Width           =   975
      End
      Begin VB.Label EmployeeName 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Name"
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
         Left            =   780
         TabIndex        =   68
         Top             =   960
         Width           =   495
      End
   End
   Begin VB.Line Line10 
      X1              =   9360
      X2              =   9360
      Y1              =   2773.419
      Y2              =   3744.116
   End
   Begin VB.Label Label10 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Overtime"
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
      Left            =   9480
      TabIndex        =   117
      Top             =   2400
      Width           =   735
   End
   Begin VB.Label Label9 
      BackColor       =   &H00FFFFFF&
      Caption         =   "OT/Week Avg:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   7800
      TabIndex        =   108
      Top             =   10200
      Width           =   1455
   End
   Begin VB.Label Label6 
      BackColor       =   &H00FFFFFF&
      Caption         =   "YTD OT Total:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   7800
      TabIndex        =   107
      Top             =   9720
      Width           =   1455
   End
   Begin VB.Label Label8 
      BackColor       =   &H00FFFFFF&
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
      Height          =   255
      Left            =   8400
      TabIndex        =   104
      Top             =   2400
      Width           =   855
   End
   Begin VB.Label Label7 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Personal"
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
      Left            =   7320
      TabIndex        =   103
      Top             =   2400
      Width           =   855
   End
   Begin VB.Label Label5 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Vacation"
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
      Left            =   6240
      TabIndex        =   102
      Top             =   2400
      Width           =   855
   End
   Begin VB.Line Line13 
      X1              =   8280
      X2              =   8280
      Y1              =   2773.419
      Y2              =   3744.116
   End
   Begin VB.Line Line12 
      X1              =   7200
      X2              =   7200
      Y1              =   3744.116
      Y2              =   2773.419
   End
   Begin VB.Line Line11 
      X1              =   6120
      X2              =   10320
      Y1              =   3189.432
      Y2              =   3189.432
   End
   Begin VB.Label Label4 
      BackColor       =   &H00FFFFFF&
      Caption         =   "YTD Balance:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   4440
      TabIndex        =   101
      Top             =   9120
      Width           =   1695
   End
   Begin VB.Label Label3 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Weekly Accrual:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   4440
      TabIndex        =   97
      Top             =   8640
      Width           =   1695
   End
   Begin VB.Label Label2 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Start Of Week:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   4560
      TabIndex        =   94
      Top             =   2880
      Width           =   1455
   End
End
Attribute VB_Name = "frmTimeSheetPast"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim YTDOT As Double
Dim ValidHourEntry As Boolean
Dim NoOfWeeksForOTCalc As Double

Dim WeekInQuestionOracle As String
Dim WeekInQuestionHuman As String



Private Sub btnPastSheets_Click()

Dim DateSelection As New frmDtSelect
DateSelection.strEmployeeID = strUserID
DateSelection.Show 1

End Sub

Private Sub cmdApprove_Click()
Dim Super As New SupervisorForm
Super.Show 1
End Sub

Private Sub cmdPrint_Click()
Dim Printout As New frmPrint
Printout.strWeek = strCurrentWeekOracle
Printout.strEmployeeID = strUserID
Printout.Show 1

End Sub

Private Sub form_load()
On Error GoTo ErrorHandler


Call Initialize

frmTimeSheet.Caption = "Time Sheet -    Current User: " & strUser & "    Current Computer: " & strComputer

CenterForm Me

' make sure employee actually exists, or if is Supervisor
strSql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strUserID & "'"
Set dsAT_EMPLOYEE = OraDatabase.DbCreateDynaset(strSql, 0&)
If (dsAT_EMPLOYEE.Recordcount = 0) Then
    MsgBox "Invalid Employee ID"
    Unload Me
    Exit Sub
End If
If (dsAT_EMPLOYEE.Fields("SUPERVISORY_STATUS").Value = "SUPERVISOR") Then
    cmdApprove.Enabled = True
    cmdApprove.Visible = True
End If

WeekInQuestionOracle = Past_NoEntry(strCurrentWeekOracle)
WeekInQuestionHuman = Format(WeekInQuestionOracle, "mm/dd/yyyy")

' set some YTD values...
txtStartWeekVac.Text = FormatNumber(dsAT_EMPLOYEE.Fields("VACATION_YTD_REMAIN").Value, 2)
txtStartWeekPers.Text = FormatNumber(dsAT_EMPLOYEE.Fields("PERSONAL_YTD_REMAIN").Value, 2)
txtStartWeekSick.Text = FormatNumber(dsAT_EMPLOYEE.Fields("SICK_YTD_REMAIN").Value, 2)
txtVacWeekAccr.Text = FormatNumber(dsAT_EMPLOYEE.Fields("VACATION_WEEKLY_RATE").Value, 2)
txtSickWeekAccr.Text = FormatNumber(dsAT_EMPLOYEE.Fields("SICK_WEEKLY_RATE").Value, 2)

strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY = '" & WeekInQuestionOracle & "'"
Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
If (dsTIME_SUBMISSION.Recordcount = 0) Then
'    OraDatabase.Close
    Call Insert_TS
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY = '" & WeekInQuestionOracle & "'"
    Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)
End If

If (dsTIME_SUBMISSION.Fields("STATUS").Value = "DENIED") Then
    cmdSave.Enabled = True
    Submit.Enabled = True
    frmHours.Enabled = True
ElseIf (dsTIME_SUBMISSION.Fields("STATUS").Value = "SUBMITTED") Then
    Submit.Enabled = False
    cmdSave.Enabled = False
    frmHours.Enabled = False
ElseIf (dsTIME_SUBMISSION.Fields("STATUS").Value = "APPROVED") Then
    Submit.Enabled = False
    cmdSave.Enabled = False
    frmHours.Enabled = False
'ElseIf (dsTIME_SUBMISSION.fields("STATUS").Value = "CONDITIONAL") Then
'    cmdSave.Enabled = False
'    Submit.Enabled = True
'    frmHours.Enabled = True
Else
    cmdSave.Enabled = True
    Submit.Enabled = True
    frmHours.Enabled = True
End If

txtEmpID.Text = dsAT_EMPLOYEE.Fields("EMPLOYEE_ID").Value
txtEmpName.Text = dsAT_EMPLOYEE.Fields("FIRST_NAME").Value & " " & dsAT_EMPLOYEE.Fields("LAST_NAME").Value
txtDept.Text = dsAT_EMPLOYEE.Fields("DEPARTMENT_ID").Value
txtWeek.Text = strCurrentWeekHuman
txtStatus.Text = dsTIME_SUBMISSION.Fields("STATUS").Value

Call Initialize_Table

txtYTDVac = FormatNumber(Val(txtStartWeekVac.Text) + Val(txtVacWeekAccr.Text) - TtlVHrs, 2)
txtYTDPers = FormatNumber(Val(txtStartWeekPers.Text) - TtlPHrs, 2)
txtYTDSick = FormatNumber(Val(txtStartWeekSick.Text) + Val(txtSickWeekAccr.Text) - TtlSHrs, 2)

' grabs all overtime for the current year, by virtue of the right() function
strSql = "SELECT NVL(SUM(WEEK_TOTAL_OVERTIME), 0) THE_OT_TOTAL FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY < '" & WeekInQuestionOracle & "' AND WEEK_START_MONDAY >= '01-JAN-" & Right(WeekInQuestionOracle, 4) & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

YTDOT = dsSHORT_TERM_DATA.Fields("THE_OT_TOTAL").Value

strSql = "SELECT COUNT(*) THE_WEEKS FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY >= '01-JAN-" & Right(WeekInQuestionOracle, 4) & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

' always guaranteed non-zero, as at the very least, the current week will make it 1
NoOfWeeksForOTCalc = dsSHORT_TERM_DATA.Fields("THE_WEEKS").Value

txtStartWeekOT.Text = FormatNumber(YTDOT, 2)
txtYTDOT.Text = FormatNumber(YTDOT + TtlOHrs, 2)
txtAVGOT.Text = FormatNumber(Val(txtYTDOT.Text) / NoOfWeeksForOTCalc, 2)

If Val(txtYTDSick) < 0 Then
   txtYTDSick.ForeColor = &HFF
Else
   txtYTDSick.ForeColor = &H0
End If
If Val(txtYTDVac) < 0 Then
   txtYTDVac.ForeColor = &HFF
Else
   txtYTDVac.ForeColor = &H0
End If
If Val(txtYTDPers) < 0 Then
   txtYTDPers.ForeColor = &HFF
Else
   txtYTDPers.ForeColor = &H0
End If

Exit Sub


ErrorHandler:
If OraDatabase.LastServerErr <> 0 Then
    MsgBox "An entered value cannot be placed into Oracle.  Oracle returned the error: " & OraDatabase.LastServerErrText
Else
    MsgBox "An Unknown Error has prevented the operation of this program.  Please contact TS."
End If

End Sub



Private Sub Insert_TS()
' sets up the employee's week with initial data, assuming 40 regular hours.
' done in 3 steps:
' 1, create timesheet with nothing.
' 2, enter default 40 hour week data
' 3, calculate YTD start-of-week values.

Dim MonDate As String
Dim TueDate As String
Dim WedDate As String
Dim ThuDate As String
Dim FriDate As String
Dim SatDate As String
Dim SunDate As String


MonDate = WeekInQuestionOracle
TueDate = DateAdd("d", 1, MonDate)
WedDate = DateAdd("d", 1, TueDate)
ThuDate = DateAdd("d", 1, WedDate)
FriDate = DateAdd("d", 1, ThuDate)
SatDate = DateAdd("d", 1, FriDate)
SunDate = DateAdd("d", 1, SatDate)

MonDate = Format(MonDate, "dd-mmm-yyyy")
TueDate = Format(TueDate, "dd-mmm-yyyy")
WedDate = Format(WedDate, "dd-mmm-yyyy")
ThuDate = Format(ThuDate, "dd-mmm-yyyy")
FriDate = Format(FriDate, "dd-mmm-yyyy")
SatDate = Format(SatDate, "dd-mmm-yyyy")
SunDate = Format(SunDate, "dd-mmm-yyyy")

strSql = "INSERT INTO TIME_SUBMISSION (EMPLOYEE_ID, WEEK_START_MONDAY, WEEK_END_SUNDAY, STATUS) VALUES ('" & strUserID & "', '" _
        & WeekInQuestionOracle & "', '" & SunDate & "', 'ON HOLD')"
OraDatabase.ExecuteSQL (strSql)

strSql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strUserID & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY = '" & WeekInQuestionOracle & "'"
Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)

OraSession.BeginTrans
dsTIME_SUBMISSION.Edit
dsTIME_SUBMISSION.Fields("MON_DATE").Value = MonDate
dsTIME_SUBMISSION.Fields("MON_TOTAL").Value = 8
dsTIME_SUBMISSION.Fields("MON_REG").Value = 8
dsTIME_SUBMISSION.Fields("MON_HOLIDAY").Value = 0
dsTIME_SUBMISSION.Fields("MON_VACATION").Value = 0
dsTIME_SUBMISSION.Fields("MON_PERSONAL").Value = 0
dsTIME_SUBMISSION.Fields("MON_SICK").Value = 0
dsTIME_SUBMISSION.Fields("MON_OVERTIME").Value = 0
dsTIME_SUBMISSION.Fields("TUE_DATE").Value = TueDate
dsTIME_SUBMISSION.Fields("TUE_TOTAL").Value = 8
dsTIME_SUBMISSION.Fields("TUE_REG").Value = 8
dsTIME_SUBMISSION.Fields("TUE_HOLIDAY").Value = 0
dsTIME_SUBMISSION.Fields("TUE_VACATION").Value = 0
dsTIME_SUBMISSION.Fields("TUE_PERSONAL").Value = 0
dsTIME_SUBMISSION.Fields("TUE_SICK").Value = 0
dsTIME_SUBMISSION.Fields("TUE_OVERTIME").Value = 0
dsTIME_SUBMISSION.Fields("WED_DATE").Value = WedDate
dsTIME_SUBMISSION.Fields("WED_TOTAL").Value = 8
dsTIME_SUBMISSION.Fields("WED_REG").Value = 8
dsTIME_SUBMISSION.Fields("WED_HOLIDAY").Value = 0
dsTIME_SUBMISSION.Fields("WED_VACATION").Value = 0
dsTIME_SUBMISSION.Fields("WED_PERSONAL").Value = 0
dsTIME_SUBMISSION.Fields("WED_SICK").Value = 0
dsTIME_SUBMISSION.Fields("WED_OVERTIME").Value = 0
dsTIME_SUBMISSION.Fields("THU_DATE").Value = ThuDate
dsTIME_SUBMISSION.Fields("THU_TOTAL").Value = 8
dsTIME_SUBMISSION.Fields("THU_REG").Value = 8
dsTIME_SUBMISSION.Fields("THU_HOLIDAY").Value = 0
dsTIME_SUBMISSION.Fields("THU_VACATION").Value = 0
dsTIME_SUBMISSION.Fields("THU_PERSONAL").Value = 0
dsTIME_SUBMISSION.Fields("THU_SICK").Value = 0
dsTIME_SUBMISSION.Fields("THU_OVERTIME").Value = 0
dsTIME_SUBMISSION.Fields("FRI_DATE").Value = FriDate
dsTIME_SUBMISSION.Fields("FRI_TOTAL").Value = 8
dsTIME_SUBMISSION.Fields("FRI_REG").Value = 8
dsTIME_SUBMISSION.Fields("FRI_HOLIDAY").Value = 0
dsTIME_SUBMISSION.Fields("FRI_VACATION").Value = 0
dsTIME_SUBMISSION.Fields("FRI_PERSONAL").Value = 0
dsTIME_SUBMISSION.Fields("FRI_SICK").Value = 0
dsTIME_SUBMISSION.Fields("FRI_OVERTIME").Value = 0
dsTIME_SUBMISSION.Fields("SAT_TOTAL").Value = 0
dsTIME_SUBMISSION.Fields("SAT_DATE").Value = SatDate
dsTIME_SUBMISSION.Fields("SAT_REG").Value = 0
dsTIME_SUBMISSION.Fields("SAT_OVERTIME").Value = 0
dsTIME_SUBMISSION.Fields("SUN_DATE").Value = SunDate
dsTIME_SUBMISSION.Fields("SUN_TOTAL").Value = 0
dsTIME_SUBMISSION.Fields("SUN_REG").Value = 0
dsTIME_SUBMISSION.Fields("SUN_OVERTIME").Value = 0
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_TOTAL").Value = 40
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value = 40
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value = 0
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value = 0
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value = 0
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value = 0
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value = 0
dsTIME_SUBMISSION.Fields("WEEKLY_ACCRUAL_VACATION").Value = dsSHORT_TERM_DATA.Fields("VACATION_WEEKLY_RATE").Value
dsTIME_SUBMISSION.Fields("WEEKLY_ACCRUAL_SICK").Value = dsSHORT_TERM_DATA.Fields("SICK_WEEKLY_RATE").Value
dsTIME_SUBMISSION.Fields("YTD_WEEK_START_VACATION_BAL").Value = dsSHORT_TERM_DATA.Fields("VACATION_YTD_REMAIN").Value
dsTIME_SUBMISSION.Fields("YTD_WEEK_START_PERSONAL_BAL").Value = dsSHORT_TERM_DATA.Fields("PERSONAL_YTD_REMAIN").Value
dsTIME_SUBMISSION.Fields("YTD_WEEK_START_SICK_BAL").Value = dsSHORT_TERM_DATA.Fields("SICK_YTD_REMAIN").Value
dsTIME_SUBMISSION.Fields("CONDITIONAL_SUBMISSION").Value = "N"
dsTIME_SUBMISSION.Update
OraSession.CommitTrans

strSql = "SELECT NVL(SUM(WEEK_TOTAL_TOTAL), 0) THE_TOTAL, " _
         & "NVL(SUM(WEEK_TOTAL_REG), 0) THE_REG, " _
         & "NVL(SUM(WEEK_TOTAL_HOLIDAY), 0) THE_HOLIDAY, " _
         & "NVL(SUM(WEEK_TOTAL_VACATION), 0) THE_VACATION, " _
         & "NVL(SUM(WEEK_TOTAL_PERSONAL), 0) THE_PERSONAL, " _
         & "NVL(SUM(WEEK_TOTAL_SICK), 0) THE_SICK, " _
         & "NVL(SUM(WEEK_TOTAL_OVERTIME), 0) THE_OVERTIME " _
         & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY < '" & WeekInQuestionOracle & "' AND WEEK_START_MONDAY >= '01-JAN-" & Right(WeekInQuestionOracle, 4) & "' AND STATUS = 'APPROVED'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY = '" & WeekInQuestionOracle & "'"
Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)

OraSession.BeginTrans
dsTIME_SUBMISSION.Edit
dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_TOTAL").Value = dsSHORT_TERM_DATA.Fields("THE_TOTAL").Value
dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_REG").Value = dsSHORT_TERM_DATA.Fields("THE_REG").Value
dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_HOLIDAY").Value = dsSHORT_TERM_DATA.Fields("THE_HOLIDAY").Value
dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_VACATION").Value = dsSHORT_TERM_DATA.Fields("THE_VACATION").Value
dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_PERSONAL").Value = dsSHORT_TERM_DATA.Fields("THE_PERSONAL").Value
dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_SICK").Value = dsSHORT_TERM_DATA.Fields("THE_SICK").Value
dsTIME_SUBMISSION.Fields("YTD_WEEK_START_TOTAL_OVERTIME").Value = dsSHORT_TERM_DATA.Fields("THE_OVERTIME").Value
dsTIME_SUBMISSION.Update
OraSession.CommitTrans

End Sub

Private Function validate_entry(theTextBox As TextBox, strDay As String) As Boolean ' verifies an entry to a (hourly) textbox is legit
    validate_entry = True
    
    If (theTextBox.Text = "") Then
        theTextBox.Text = 0
        validate_entry = False
        Exit Function
    End If

    If (IsNumeric(theTextBox) = False) Then
        theTextBox.Text = 0
        MsgBox "Please only enter numeric values for hours.  Resetting box to 0."
        validate_entry = False
        Exit Function
    End If
    
    Select Case strDay
        Case "Monday"
            If (Val(MoHHrs.Text) + Val(MoSHrs.Text) + Val(MoVHrs.Text) + Val(MoPHrs.Text)) > 8 Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 8 non-overtime hours in a single day.  Resetting last entry to 0."
                validate_entry = False
            ElseIf (Val(MoOHrs.Text) > 16) Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 24 hours per day.  Resetting last entry to 0."
                validate_entry = False
            End If
        Case "Tuesday"
            If (Val(TuHHrs.Text) + Val(TuSHrs.Text) + Val(TuVHrs.Text) + Val(TuPHrs.Text)) > 8 Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 8 non-overtime hours in a single day.  Resetting last entry to 0."
                validate_entry = False
            ElseIf (Val(TuOHrs.Text) > 16) Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 24 hours per day.  Resetting last entry to 0."
                validate_entry = False
            End If
        Case "Wednesday"
            If (Val(WeHHrs.Text) + Val(WeSHrs.Text) + Val(WeVHrs.Text) + Val(WePHrs.Text)) > 8 Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 8 non-overtime hours in a single day.  Resetting last entry to 0."
                validate_entry = False
            ElseIf (Val(WeOHrs.Text) > 16) Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 24 hours per day.  Resetting last entry to 0."
                validate_entry = False
            End If
        Case "Thursday"
            If (Val(ThHHrs.Text) + Val(ThSHrs.Text) + Val(ThVHrs.Text) + Val(ThPHrs.Text)) > 8 Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 8 non-overtime hours in a single day.  Resetting last entry to 0."
                validate_entry = False
            ElseIf (Val(ThOHrs.Text) > 16) Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 24 hours per day.  Resetting last entry to 0."
                validate_entry = False
            End If
        Case "Friday"
            If (Val(FrHHrs.Text) + Val(FrSHrs.Text) + Val(FrVHrs.Text) + Val(FrPHrs.Text)) > 8 Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 8 non-overtime hours in a single day.  Resetting last entry to 0."
                validate_entry = False
            ElseIf (Val(FrOHrs.Text) > 16) Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 24 hours per day.  Resetting last entry to 0."
                validate_entry = False
            End If
        Case "Saturday"
            If Val(SaRHrs.Text) > 8 Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 8 non-overtime hours in a single day.  Resetting last entry to 0."
                validate_entry = False
            ElseIf (Val(SaOHrs.Text) + Val(SaRHrs.Text) > 24) Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 24 hours per day.  Resetting last entry to 0."
                validate_entry = False
            End If
        Case "Sunday"
            If Val(SuRHrs.Text) > 8 Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 8 non-overtime hours in a single day.  Resetting last entry to 0."
                validate_entry = False
            ElseIf (Val(SuOHrs.Text) + Val(SuRHrs.Text) > 24) Then
                theTextBox.Text = 0
                MsgBox "Cannot exceed 24 hours per day.  Resetting last entry to 0."
                validate_entry = False
            End If
    End Select
End Function
Sub All_Totals() 'set all totals in form, used after an update to a textbox
MoRHrs = 8 - (Val(MoHHrs) + Val(MoVHrs) + Val(MoPHrs) + Val(MoSHrs))
TuRHrs = 8 - (Val(TuHHrs) + Val(TuVHrs) + Val(TuPHrs) + Val(TuSHrs))
WeRHrs = 8 - (Val(WeHHrs) + Val(WeVHrs) + Val(WePHrs) + Val(WeSHrs))
ThRHrs = 8 - (Val(ThHHrs) + Val(ThVHrs) + Val(ThPHrs) + Val(ThSHrs))
FrRHrs = 8 - (Val(FrHHrs) + Val(FrVHrs) + Val(FrPHrs) + Val(FrSHrs))
MoTtl.Text = 0 + MoRHrs + MoHHrs + MoVHrs + MoPHrs + MoSHrs + MoOHrs
TuTtl.Text = 0 + TuRHrs + TuHHrs + TuVHrs + TuPHrs + TuSHrs + TuOHrs
WeTtl.Text = 0 + WeRHrs + WeHHrs + WeVHrs + WePHrs + WeSHrs + WeOHrs
ThTtl.Text = 0 + ThRHrs + ThHHrs + ThVHrs + ThPHrs + ThSHrs + ThOHrs
FrTtl.Text = 0 + FrRHrs + FrHHrs + FrVHrs + FrPHrs + FrSHrs + FrOHrs
SaTtl.Text = 0 + SaRHrs + SaHHrs + SaVHrs + SaPHrs + SaSHrs + SaOHrs
SuTtl.Text = 0 + SuRHrs + SuHHrs + SuVHrs + SuPHrs + SuSHrs + SuOHrs
TtlRHrs.Text = 0 + MoRHrs + TuRHrs + WeRHrs + ThRHrs + FrRHrs + SaRHrs + SuRHrs
TtlHHrs.Text = 0 + MoHHrs + TuHHrs + WeHHrs + ThHHrs + FrHHrs + SaHHrs + SuHHrs
TtlVHrs.Text = 0 + MoVHrs + TuVHrs + WeVHrs + ThVHrs + FrVHrs + SaVHrs + SuVHrs
TtlPHrs.Text = 0 + MoPHrs + TuPHrs + WePHrs + ThPHrs + FrPHrs + SaPHrs + SuPHrs
TtlSHrs.Text = 0 + MoSHrs + TuSHrs + WeSHrs + ThSHrs + FrSHrs + SaSHrs + SuSHrs
TtlOHrs.Text = 0 + MoOHrs + TuOHrs + WeOHrs + ThOHrs + FrOHrs + SaOHrs + SuOHrs
TotalHrs.Text = 0 + MoTtl + TuTtl + WeTtl + ThTtl + FrTtl + SaTtl + SuTtl
txtYTDVac = FormatNumber(Val(txtStartWeekVac.Text) + Val(txtVacWeekAccr.Text) - TtlVHrs, 2)
txtYTDPers = FormatNumber(Val(txtStartWeekPers.Text) - TtlPHrs, 2)
txtYTDSick = FormatNumber(Val(txtStartWeekSick.Text) + Val(txtSickWeekAccr.Text) - TtlSHrs, 2)
txtYTDOT = FormatNumber(YTDOT + TtlOHrs, 2)
txtAVGOT = FormatNumber(Val(txtYTDOT.Text) / NoOfWeeksForOTCalc, 2)


If Val(txtYTDSick) < 0 Then
   txtYTDSick.ForeColor = &HFF
Else
   txtYTDSick.ForeColor = &H0
End If
If Val(txtYTDVac) < 0 Then
   txtYTDVac.ForeColor = &HFF
Else
   txtYTDVac.ForeColor = &H0
End If
If Val(txtYTDPers) < 0 Then
   txtYTDPers.ForeColor = &HFF
Else
   txtYTDPers.ForeColor = &H0
End If
   
End Sub




Private Sub MoHHrs_GotFocus()
If MoHHrs.Text = "0" Then
   MoHHrs.Text = ""
End If
End Sub
Private Sub MoHHrs_LostFocus()

        ValidHourEntry = validate_entry(MoHHrs, "Monday") ' resets textbox to 0 if not valid

        Call All_Totals

End Sub
Private Sub MoVHrs_GotFocus()
If MoVHrs.Text = "0" Then
   MoVHrs.Text = ""
End If
End Sub
Private Sub MoVHrs_LostFocus()

        ValidHourEntry = validate_entry(MoVHrs, "Monday") ' resets textbox to 0 if not valid

        Call All_Totals


End Sub
Private Sub MoPHrs_GotFocus()
If MoPHrs.Text = "0" Then
   MoPHrs.Text = ""
End If
End Sub
Private Sub MoPHrs_LostFocus()

       ValidHourEntry = validate_entry(MoPHrs, "Monday") ' resets textbox to 0 if not valid
    
       Call All_Totals

End Sub
Private Sub MoSHrs_GotFocus()
If MoSHrs.Text = "0" Then
   MoSHrs.Text = ""
End If
End Sub
Private Sub MoSHrs_LostFocus()

        ValidHourEntry = validate_entry(MoSHrs, "Monday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub MoOHrs_GotFocus()
If MoOHrs.Text = "0" Then
   MoOHrs.Text = ""
End If
End Sub
Private Sub MoOHrs_LostFocus()

        ValidHourEntry = validate_entry(MoOHrs, "Monday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub TuHHrs_GotFocus()
If TuHHrs.Text = "0" Then
   TuHHrs.Text = ""
End If
End Sub
Private Sub TuHHrs_LostFocus()

        ValidHourEntry = validate_entry(TuHHrs, "Tuesday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub TuVHrs_GotFocus()
If TuVHrs.Text = "0" Then
   TuVHrs.Text = ""
End If
End Sub
Private Sub TuVHrs_LostFocus()

       ValidHourEntry = validate_entry(TuVHrs, "Tuesday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub TuPHrs_GotFocus()
If TuPHrs.Text = "0" Then
   TuPHrs.Text = ""
End If
End Sub
Private Sub TuPHrs_LostFocus()

        ValidHourEntry = validate_entry(TuPHrs, "Tuesday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub TuSHrs_GotFocus()
If TuSHrs.Text = "0" Then
   TuSHrs.Text = ""
End If
End Sub
Private Sub TuSHrs_LostFocus()

        ValidHourEntry = validate_entry(TuSHrs, "Tuesday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub TuOHrs_GotFocus()
If TuOHrs.Text = "0" Then
   TuOHrs.Text = ""
End If
End Sub
Private Sub TuOHrs_LostFocus()

        ValidHourEntry = validate_entry(TuOHrs, "Tuesday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub WeHHrs_GotFocus()
If WeHHrs.Text = "0" Then
   WeHHrs.Text = ""
End If
End Sub
Private Sub WeHHrs_LostFocus()
   
        ValidHourEntry = validate_entry(WeHHrs, "Wednesday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub WeVHrs_GotFocus()
If WeVHrs.Text = "0" Then
   WeVHrs.Text = ""
End If
End Sub
Private Sub WeVHrs_LostFocus()

        ValidHourEntry = validate_entry(WeVHrs, "Wednesday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub WePHrs_GotFocus()
If WePHrs.Text = "0" Then
   WePHrs.Text = ""
End If
End Sub
Private Sub WePHrs_LostFocus()

        ValidHourEntry = validate_entry(WePHrs, "Wednesday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub WeSHrs_GotFocus()
If WeSHrs.Text = "0" Then
   WeSHrs.Text = ""
End If
End Sub
Private Sub WeSHrs_LostFocus()

        ValidHourEntry = validate_entry(WeSHrs, "Wednesday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub WeOHrs_GotFocus()
If WeOHrs.Text = "0" Then
   WeOHrs.Text = ""
End If
End Sub
Private Sub WeOHrs_LostFocus()

        ValidHourEntry = validate_entry(WeOHrs, "Wednesday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub ThHHrs_GotFocus()
If ThHHrs.Text = "0" Then
   ThHHrs.Text = ""
End If
End Sub
Private Sub ThHHrs_LostFocus()

        ValidHourEntry = validate_entry(ThHHrs, "Thursday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub ThVHrs_GotFocus()
If ThVHrs.Text = "0" Then
   ThVHrs.Text = ""
End If
End Sub
Private Sub ThVHrs_LostFocus()

        ValidHourEntry = validate_entry(ThVHrs, "Thursday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub ThPHrs_GotFocus()
If ThPHrs.Text = "0" Then
   ThPHrs.Text = ""
End If
End Sub
Private Sub ThPHrs_LostFocus()

        ValidHourEntry = validate_entry(ThPHrs, "Thursday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub ThSHrs_GotFocus()
If ThSHrs.Text = "0" Then
   ThSHrs.Text = ""
End If
End Sub
Private Sub ThSHrs_LostFocus()

        ValidHourEntry = validate_entry(ThSHrs, "Thursday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub ThOHrs_GotFocus()
If ThOHrs.Text = "0" Then
   ThOHrs.Text = ""
End If
End Sub
Private Sub ThOHrs_LostFocus()

        ValidHourEntry = validate_entry(ThOHrs, "Thursday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub FrHHrs_GotFocus()
If FrHHrs.Text = "0" Then
   FrHHrs.Text = ""
End If
End Sub
Private Sub FrHHrs_LostFocus()

        ValidHourEntry = validate_entry(FrHHrs, "Friday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub FrVHrs_GotFocus()
If FrVHrs.Text = "0" Then
   FrVHrs.Text = ""
End If
End Sub
Private Sub FrVHrs_LostFocus()

        ValidHourEntry = validate_entry(FrVHrs, "Friday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub FrPHrs_GotFocus()
If FrPHrs.Text = "0" Then
   FrPHrs.Text = ""
End If
End Sub
Private Sub FrPHrs_LostFocus()

        ValidHourEntry = validate_entry(FrPHrs, "Friday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub FrSHrs_GotFocus()
If FrSHrs.Text = "0" Then
   FrSHrs.Text = ""
End If
End Sub
Private Sub FrSHrs_LostFocus()

        ValidHourEntry = validate_entry(FrSHrs, "Friday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub FrOHrs_GotFocus()
If FrOHrs.Text = "0" Then
   FrOHrs.Text = ""
End If
End Sub
Private Sub FrOHrs_LostFocus()

        ValidHourEntry = validate_entry(FrOHrs, "Friday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub SaRHrs_GotFocus()
If SaRHrs.Text = "0" Then
   SaRHrs.Text = ""
End If
End Sub
Private Sub SaRHrs_LostFocus()

        ValidHourEntry = validate_entry(SaRHrs, "Saturday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub SaOHrs_GotFocus()
If SaOHrs.Text = "0" Then
   SaOHrs.Text = ""
End If
End Sub
Private Sub SaOHrs_LostFocus()

        ValidHourEntry = validate_entry(SaOHrs, "Saturday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub SuRHrs_GotFocus()
If SuRHrs.Text = "0" Then
   SuRHrs.Text = ""
End If
End Sub
Private Sub SuRHrs_LostFocus()

        ValidHourEntry = validate_entry(SuRHrs, "Sunday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub
Private Sub SuOHrs_GotFocus()
If SuOHrs.Text = "0" Then
   SuOHrs.Text = ""
End If
End Sub
Private Sub SuOHrs_LostFocus()

        ValidHourEntry = validate_entry(SuOHrs, "Sunday") ' resets textbox to 0 if not valid
    
        Call All_Totals

End Sub

Private Sub cmdSave_Click()







strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY = '" & WeekInQuestionOracle & "'"
Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)

OraSession.BeginTrans
dsTIME_SUBMISSION.Edit
dsTIME_SUBMISSION.Fields("STATUS").Value = "ON HOLD"
dsTIME_SUBMISSION.Fields("MON_TOTAL").Value = MoTtl
dsTIME_SUBMISSION.Fields("MON_REG").Value = MoRHrs
dsTIME_SUBMISSION.Fields("MON_HOLIDAY").Value = MoHHrs
dsTIME_SUBMISSION.Fields("MON_VACATION").Value = MoVHrs
dsTIME_SUBMISSION.Fields("MON_PERSONAL").Value = MoPHrs
dsTIME_SUBMISSION.Fields("MON_SICK").Value = MoSHrs
dsTIME_SUBMISSION.Fields("MON_OVERTIME").Value = MoOHrs
dsTIME_SUBMISSION.Fields("TUE_TOTAL").Value = TuTtl
dsTIME_SUBMISSION.Fields("TUE_REG").Value = TuRHrs
dsTIME_SUBMISSION.Fields("TUE_HOLIDAY").Value = TuHHrs
dsTIME_SUBMISSION.Fields("TUE_VACATION").Value = TuVHrs
dsTIME_SUBMISSION.Fields("TUE_PERSONAL").Value = TuPHrs
dsTIME_SUBMISSION.Fields("TUE_SICK").Value = TuSHrs
dsTIME_SUBMISSION.Fields("TUE_OVERTIME").Value = TuOHrs
dsTIME_SUBMISSION.Fields("WED_TOTAL").Value = WeTtl
dsTIME_SUBMISSION.Fields("WED_REG").Value = WeRHrs
dsTIME_SUBMISSION.Fields("WED_HOLIDAY").Value = WeHHrs
dsTIME_SUBMISSION.Fields("WED_VACATION").Value = WeVHrs
dsTIME_SUBMISSION.Fields("WED_PERSONAL").Value = WePHrs
dsTIME_SUBMISSION.Fields("WED_SICK").Value = WeSHrs
dsTIME_SUBMISSION.Fields("WED_OVERTIME").Value = WeOHrs
dsTIME_SUBMISSION.Fields("THU_TOTAL").Value = ThTtl
dsTIME_SUBMISSION.Fields("THU_REG").Value = ThRHrs
dsTIME_SUBMISSION.Fields("THU_HOLIDAY").Value = ThHHrs
dsTIME_SUBMISSION.Fields("THU_VACATION").Value = ThVHrs
dsTIME_SUBMISSION.Fields("THU_PERSONAL").Value = ThPHrs
dsTIME_SUBMISSION.Fields("THU_SICK").Value = ThSHrs
dsTIME_SUBMISSION.Fields("THU_OVERTIME").Value = ThOHrs
dsTIME_SUBMISSION.Fields("FRI_TOTAL").Value = FrTtl
dsTIME_SUBMISSION.Fields("FRI_REG").Value = FrRHrs
dsTIME_SUBMISSION.Fields("FRI_HOLIDAY").Value = FrHHrs
dsTIME_SUBMISSION.Fields("FRI_VACATION").Value = FrVHrs
dsTIME_SUBMISSION.Fields("FRI_PERSONAL").Value = FrPHrs
dsTIME_SUBMISSION.Fields("FRI_SICK").Value = FrSHrs
dsTIME_SUBMISSION.Fields("FRI_OVERTIME").Value = FrOHrs
dsTIME_SUBMISSION.Fields("SAT_TOTAL").Value = SaTtl
dsTIME_SUBMISSION.Fields("SAT_REG").Value = SaRHrs
dsTIME_SUBMISSION.Fields("SAT_OVERTIME").Value = SaOHrs
dsTIME_SUBMISSION.Fields("SUN_TOTAL").Value = SuTtl
dsTIME_SUBMISSION.Fields("SUN_REG").Value = SuRHrs
dsTIME_SUBMISSION.Fields("SUN_OVERTIME").Value = SuOHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_TOTAL").Value = TotalHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value = TtlRHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value = TtlHHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value = TtlVHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value = TtlPHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value = TtlSHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value = TtlOHrs
dsTIME_SUBMISSION.Update
OraSession.CommitTrans
MsgBox "Saved!"

txtStatus.Text = "ON HOLD"

'Call Form_Load

End Sub

Private Sub Submit_Click()







strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY = '" & WeekInQuestionOracle & "'"
Set dsTIME_SUBMISSION = OraDatabase.DbCreateDynaset(strSql, 0&)

OraSession.BeginTrans
dsTIME_SUBMISSION.Edit
dsTIME_SUBMISSION.Fields("STATUS").Value = "SUBMITTED"
dsTIME_SUBMISSION.Fields("SUBMISSION_PC_USERID").Value = strUser
dsTIME_SUBMISSION.Fields("SUBMISSION_PC").Value = strComputer
dsTIME_SUBMISSION.Fields("MON_TOTAL").Value = MoTtl
dsTIME_SUBMISSION.Fields("MON_REG").Value = MoRHrs
dsTIME_SUBMISSION.Fields("MON_HOLIDAY").Value = MoHHrs
dsTIME_SUBMISSION.Fields("MON_VACATION").Value = MoVHrs
dsTIME_SUBMISSION.Fields("MON_PERSONAL").Value = MoPHrs
dsTIME_SUBMISSION.Fields("MON_SICK").Value = MoSHrs
dsTIME_SUBMISSION.Fields("MON_OVERTIME").Value = MoOHrs
dsTIME_SUBMISSION.Fields("TUE_TOTAL").Value = TuTtl
dsTIME_SUBMISSION.Fields("TUE_REG").Value = TuRHrs
dsTIME_SUBMISSION.Fields("TUE_HOLIDAY").Value = TuHHrs
dsTIME_SUBMISSION.Fields("TUE_VACATION").Value = TuVHrs
dsTIME_SUBMISSION.Fields("TUE_PERSONAL").Value = TuPHrs
dsTIME_SUBMISSION.Fields("TUE_SICK").Value = TuSHrs
dsTIME_SUBMISSION.Fields("TUE_OVERTIME").Value = TuOHrs
dsTIME_SUBMISSION.Fields("WED_TOTAL").Value = WeTtl
dsTIME_SUBMISSION.Fields("WED_REG").Value = WeRHrs
dsTIME_SUBMISSION.Fields("WED_HOLIDAY").Value = WeHHrs
dsTIME_SUBMISSION.Fields("WED_VACATION").Value = WeVHrs
dsTIME_SUBMISSION.Fields("WED_PERSONAL").Value = WePHrs
dsTIME_SUBMISSION.Fields("WED_SICK").Value = WeSHrs
dsTIME_SUBMISSION.Fields("WED_OVERTIME").Value = WeOHrs
dsTIME_SUBMISSION.Fields("THU_TOTAL").Value = ThTtl
dsTIME_SUBMISSION.Fields("THU_REG").Value = ThRHrs
dsTIME_SUBMISSION.Fields("THU_HOLIDAY").Value = ThHHrs
dsTIME_SUBMISSION.Fields("THU_VACATION").Value = ThVHrs
dsTIME_SUBMISSION.Fields("THU_PERSONAL").Value = ThPHrs
dsTIME_SUBMISSION.Fields("THU_SICK").Value = ThSHrs
dsTIME_SUBMISSION.Fields("THU_OVERTIME").Value = ThOHrs
dsTIME_SUBMISSION.Fields("FRI_TOTAL").Value = FrTtl
dsTIME_SUBMISSION.Fields("FRI_REG").Value = FrRHrs
dsTIME_SUBMISSION.Fields("FRI_HOLIDAY").Value = FrHHrs
dsTIME_SUBMISSION.Fields("FRI_VACATION").Value = FrVHrs
dsTIME_SUBMISSION.Fields("FRI_PERSONAL").Value = FrPHrs
dsTIME_SUBMISSION.Fields("FRI_SICK").Value = FrSHrs
dsTIME_SUBMISSION.Fields("FRI_OVERTIME").Value = FrOHrs
dsTIME_SUBMISSION.Fields("SAT_TOTAL").Value = SaTtl
dsTIME_SUBMISSION.Fields("SAT_REG").Value = SaRHrs
dsTIME_SUBMISSION.Fields("SAT_OVERTIME").Value = SaOHrs
dsTIME_SUBMISSION.Fields("SUN_TOTAL").Value = SuTtl
dsTIME_SUBMISSION.Fields("SUN_REG").Value = SuRHrs
dsTIME_SUBMISSION.Fields("SUN_OVERTIME").Value = SuOHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_TOTAL").Value = TotalHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value = TtlRHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value = TtlHHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value = TtlVHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value = TtlPHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value = TtlSHrs
dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value = TtlOHrs
dsTIME_SUBMISSION.Update
OraSession.CommitTrans

strSql = "UPDATE TIME_SUBMISSION SET SUBMISSION_DATETIME = SYSDATE WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY = '" & strCurrentWeekOracle & "'"
OraDatabase.ExecuteSQL (strSql)

MsgBox "Submitted!"

txtStatus.Text = "SUBMITTED"
cmdSave.Enabled = False
Submit.Enabled = False

'Call Form_Load

End Sub

Private Sub Initialize_Table()
' function loads the current data into the timesheet.
' I don't personally thing this should have been divided into it's own function, but... not my call.
' At the (only) time this function gets called, the object dsTIME_SUBMISSION has just been called.

' all of the currently-in-DB hour values
MoRHrs.Text = dsTIME_SUBMISSION.Fields("MON_REG").Value
MoHHrs.Text = dsTIME_SUBMISSION.Fields("MON_HOLIDAY").Value
MoVHrs.Text = dsTIME_SUBMISSION.Fields("MON_VACATION").Value
MoPHrs.Text = dsTIME_SUBMISSION.Fields("MON_PERSONAL").Value
MoSHrs.Text = dsTIME_SUBMISSION.Fields("MON_SICK").Value
MoOHrs.Text = dsTIME_SUBMISSION.Fields("MON_OVERTIME").Value
MoTtl.Text = dsTIME_SUBMISSION.Fields("MON_TOTAL").Value
MoDate.Text = dsTIME_SUBMISSION.Fields("MON_DATE").Value

TuRHrs.Text = dsTIME_SUBMISSION.Fields("TUE_REG").Value
TuHHrs.Text = dsTIME_SUBMISSION.Fields("TUE_HOLIDAY").Value
TuVHrs.Text = dsTIME_SUBMISSION.Fields("TUE_VACATION").Value
TuPHrs.Text = dsTIME_SUBMISSION.Fields("TUE_PERSONAL").Value
TuSHrs.Text = dsTIME_SUBMISSION.Fields("TUE_SICK").Value
TuOHrs.Text = dsTIME_SUBMISSION.Fields("TUE_OVERTIME").Value
TuTtl.Text = dsTIME_SUBMISSION.Fields("TUE_TOTAL").Value
TuDate.Text = dsTIME_SUBMISSION.Fields("TUE_DATE").Value

WeRHrs.Text = dsTIME_SUBMISSION.Fields("WED_REG").Value
WeHHrs.Text = dsTIME_SUBMISSION.Fields("WED_HOLIDAY").Value
WeVHrs.Text = dsTIME_SUBMISSION.Fields("WED_VACATION").Value
WePHrs.Text = dsTIME_SUBMISSION.Fields("WED_PERSONAL").Value
WeSHrs.Text = dsTIME_SUBMISSION.Fields("WED_SICK").Value
WeOHrs.Text = dsTIME_SUBMISSION.Fields("WED_OVERTIME").Value
WeTtl.Text = dsTIME_SUBMISSION.Fields("WED_TOTAL").Value
WeDate.Text = dsTIME_SUBMISSION.Fields("WED_DATE").Value

ThRHrs.Text = dsTIME_SUBMISSION.Fields("THU_REG").Value
ThHHrs.Text = dsTIME_SUBMISSION.Fields("THU_HOLIDAY").Value
ThVHrs.Text = dsTIME_SUBMISSION.Fields("THU_VACATION").Value
ThPHrs.Text = dsTIME_SUBMISSION.Fields("THU_PERSONAL").Value
ThSHrs.Text = dsTIME_SUBMISSION.Fields("THU_SICK").Value
ThOHrs.Text = dsTIME_SUBMISSION.Fields("THU_OVERTIME").Value
ThTtl.Text = dsTIME_SUBMISSION.Fields("THU_TOTAL").Value
ThDate.Text = dsTIME_SUBMISSION.Fields("THU_DATE").Value

FrRHrs.Text = dsTIME_SUBMISSION.Fields("FRI_REG").Value
FrHHrs.Text = dsTIME_SUBMISSION.Fields("FRI_HOLIDAY").Value
FrVHrs.Text = dsTIME_SUBMISSION.Fields("FRI_VACATION").Value
FrPHrs.Text = dsTIME_SUBMISSION.Fields("FRI_PERSONAL").Value
FrSHrs.Text = dsTIME_SUBMISSION.Fields("FRI_SICK").Value
FrOHrs.Text = dsTIME_SUBMISSION.Fields("FRI_OVERTIME").Value
FrTtl.Text = dsTIME_SUBMISSION.Fields("FRI_TOTAL").Value
FrDate.Text = dsTIME_SUBMISSION.Fields("FRI_DATE").Value

SaRHrs.Text = dsTIME_SUBMISSION.Fields("SAT_REG").Value
SaHHrs.Text = dsTIME_SUBMISSION.Fields("SAT_HOLIDAY").Value
SaVHrs.Text = dsTIME_SUBMISSION.Fields("SAT_VACATION").Value
SaPHrs.Text = dsTIME_SUBMISSION.Fields("SAT_PERSONAL").Value
SaSHrs.Text = dsTIME_SUBMISSION.Fields("SAT_SICK").Value
SaOHrs.Text = dsTIME_SUBMISSION.Fields("SAT_OVERTIME").Value
SaTtl.Text = dsTIME_SUBMISSION.Fields("SAT_TOTAL").Value
SaDate.Text = dsTIME_SUBMISSION.Fields("SAT_DATE").Value

SuRHrs.Text = dsTIME_SUBMISSION.Fields("SUN_REG").Value
SuHHrs.Text = dsTIME_SUBMISSION.Fields("SUN_HOLIDAY").Value
SuVHrs.Text = dsTIME_SUBMISSION.Fields("SUN_VACATION").Value
SuPHrs.Text = dsTIME_SUBMISSION.Fields("SUN_PERSONAL").Value
SuSHrs.Text = dsTIME_SUBMISSION.Fields("SUN_SICK").Value
SuOHrs.Text = dsTIME_SUBMISSION.Fields("SUN_OVERTIME").Value
SuTtl.Text = dsTIME_SUBMISSION.Fields("SUN_TOTAL").Value
SuDate.Text = dsTIME_SUBMISSION.Fields("SUN_DATE").Value

TotalHrs.Text = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_TOTAL").Value
TtlRHrs = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_REG").Value
TtlHHrs = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_HOLIDAY").Value
TtlVHrs = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_VACATION").Value
TtlPHrs = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_PERSONAL").Value
TtlSHrs = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_SICK").Value
TtlOHrs = dsTIME_SUBMISSION.Fields("WEEK_TOTAL_OVERTIME").Value

End Sub

Function Past_NoEntry(TheDate As String) As String
    
Dim dsNOENTRYDATA As Object
    
    strSql = "SELECT FIRST_WEEK_PAID FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strUserID & "'"
    Set dsNOENTRYDATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    ' this is either their first week, or they have not submitted any sheets all the way back to their first week
    If dsNOENTRYDATA.Fields("FIRST_WEEK_PAID").Value = TheDate Then
        Past_NoEntry = TheDate
        Exit Function
    End If
    
    strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strUserID & "' AND WEEK_START_MONDAY = '" & Format(DateAdd("d", -7, TheDate), "dd-mmm-yyyy") & "'"
    Set dsNOENTRYDATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    ' they have no timesheet for last week, recursively call this function on last week
    If dsNOENTRYDATA.Recordcount = 0 Then
        Past_NoEntry = Past_NoEntry(Format(DateAdd("d", -7, TheDate), "dd-mmm-yyyy"))
        Exit Function
    End If
    
    ' If there is a record, but it is both conditional AND a status where the emplyoee is expected to handle the next step,
    ' then we recursively call the function to check if the week before the check also needs attention
    If dsNOENTRYDATA.Fields("CONDITIONAL_SUBMISSION").Value = "Y" And dsNOENTRYDATA.Fields("STATUS").Value <> "SUBMITTED" Then
        Past_NoEntry = Past_NoEntry(Format(DateAdd("d", -7, TheDate), "dd-mmm-yyyy"))
        Exit Function
    End If
    
    ' if previous checks pass, then the date in the current function iteration is the one for this form.
    Past_NoEntry = TheDate
        
    
End Function
