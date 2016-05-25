VERSION 5.00
Begin VB.Form SuperModTime 
   BackColor       =   &H00FFFFFF&
   Caption         =   "Supervisor Time Sheet Entry For Employee"
   ClientHeight    =   11070
   ClientLeft      =   750
   ClientTop       =   690
   ClientWidth     =   10875
   KeyPreview      =   -1  'True
   LinkTopic       =   "Form1"
   ScaleHeight     =   12792.39
   ScaleMode       =   0  'User
   ScaleWidth      =   10875
   Begin VB.TextBox txtStartWeekOT 
      Appearance      =   0  'Flat
      BackColor       =   &H00E0E0E0&
      Height          =   375
      Left            =   9480
      TabIndex        =   119
      Top             =   2880
      Width           =   975
   End
   Begin VB.TextBox txtSuperComments 
      Appearance      =   0  'Flat
      Height          =   1575
      Left            =   360
      MaxLength       =   200
      TabIndex        =   116
      Top             =   9000
      Width           =   3855
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
      TabIndex        =   103
      Top             =   8640
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
      TabIndex        =   102
      Top             =   8640
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
      TabIndex        =   101
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
      TabIndex        =   100
      Top             =   9120
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
      TabIndex        =   99
      Top             =   9120
      Width           =   855
   End
   Begin VB.TextBox txtYTDOT 
      Alignment       =   1  'Right Justify
      Appearance      =   0  'Flat
      BackColor       =   &H00E0E0E0&
      Height          =   375
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   98
      Top             =   9600
      Width           =   855
   End
   Begin VB.TextBox txtAVGOT 
      Alignment       =   1  'Right Justify
      Appearance      =   0  'Flat
      BackColor       =   &H00E0E0E0&
      Height          =   375
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   97
      Top             =   10080
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
      TabIndex        =   72
      Top             =   3360
      Width           =   10215
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
         TabIndex        =   115
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
         TabIndex        =   114
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
         TabIndex        =   113
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
         TabIndex        =   112
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
         TabIndex        =   111
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
         TabIndex        =   110
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
         TabIndex        =   109
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
         TabIndex        =   42
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
         TabIndex        =   46
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
         TabIndex        =   41
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
         TabIndex        =   45
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
         TabIndex        =   40
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
         TabIndex        =   44
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
         TabIndex        =   39
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
         TabIndex        =   43
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
         TabIndex        =   33
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
         TabIndex        =   34
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
         TabIndex        =   35
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
         TabIndex        =   36
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
         TabIndex        =   38
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
         TabIndex        =   37
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
         TabIndex        =   64
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
         TabIndex        =   63
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
         TabIndex        =   62
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
         TabIndex        =   61
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
         TabIndex        =   60
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
         TabIndex        =   59
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
         TabIndex        =   52
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
         TabIndex        =   53
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
         TabIndex        =   54
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
         TabIndex        =   55
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
         TabIndex        =   56
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
         TabIndex        =   57
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
         TabIndex        =   58
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
         Left            =   1320
         TabIndex        =   108
         Top             =   4320
         Width           =   855
      End
      Begin VB.Line Line14 
         X1              =   0
         X2              =   10200
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
         TabIndex        =   88
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
         TabIndex        =   87
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
         TabIndex        =   86
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
         TabIndex        =   85
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
         TabIndex        =   84
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
         TabIndex        =   83
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
         TabIndex        =   82
         Top             =   240
         Width           =   495
      End
      Begin VB.Line Line9 
         X1              =   0
         X2              =   10200
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
         TabIndex        =   81
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
         TabIndex        =   80
         Top             =   840
         Width           =   735
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
         TabIndex        =   79
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
         TabIndex        =   78
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
         TabIndex        =   77
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
         TabIndex        =   76
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
         TabIndex        =   75
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
         TabIndex        =   74
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
         TabIndex        =   73
         Top             =   240
         Width           =   315
      End
      Begin VB.Line Line8 
         X1              =   9000
         X2              =   9000
         Y1              =   0
         Y2              =   4920
      End
      Begin VB.Line Line7 
         X1              =   7920
         X2              =   7920
         Y1              =   0
         Y2              =   4920
      End
      Begin VB.Line Line6 
         X1              =   6840
         X2              =   6840
         Y1              =   0
         Y2              =   4920
      End
      Begin VB.Line Line5 
         X1              =   5760
         X2              =   5760
         Y1              =   0
         Y2              =   4920
      End
      Begin VB.Line Line4 
         X1              =   4680
         X2              =   4680
         Y1              =   0
         Y2              =   4920
      End
      Begin VB.Line Line3 
         X1              =   3600
         X2              =   3600
         Y1              =   0
         Y2              =   4920
      End
      Begin VB.Line Line2 
         X1              =   2400
         X2              =   2400
         Y1              =   0
         Y2              =   4920
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
      ScaleWidth      =   10815
      TabIndex        =   69
      Top             =   10785
      Width           =   10875
   End
   Begin VB.Frame UpperFrame 
      BackColor       =   &H00FFFFFF&
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
      TabIndex        =   65
      Top             =   120
      Width           =   10305
      Begin VB.TextBox txtStatus 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   4920
         Locked          =   -1  'True
         TabIndex        =   51
         TabStop         =   0   'False
         Top             =   1320
         Width           =   1815
      End
      Begin VB.CommandButton Modify 
         Caption         =   "Re-Open"
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
         TabIndex        =   32
         Top             =   1560
         Width           =   1245
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
         Left            =   7440
         TabIndex        =   31
         Top             =   1560
         Width           =   1245
      End
      Begin VB.TextBox txtWeek 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   1440
         Locked          =   -1  'True
         TabIndex        =   47
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
         Left            =   7440
         TabIndex        =   0
         Top             =   1080
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
         Left            =   8880
         TabIndex        =   30
         Top             =   1080
         Visible         =   0   'False
         Width           =   1245
      End
      Begin VB.TextBox txtDept 
         Appearance      =   0  'Flat
         BackColor       =   &H00E0E0E0&
         Height          =   285
         Left            =   1440
         Locked          =   -1  'True
         TabIndex        =   48
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
         TabIndex        =   50
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
         TabIndex        =   49
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
         TabIndex        =   89
         Top             =   1320
         Width           =   615
      End
      Begin VB.Label Label 
         Alignment       =   2  'Center
         BackColor       =   &H00FFFFFF&
         Caption         =   "By Supervisor For Employee"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   15.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   615
         Left            =   3240
         TabIndex        =   71
         Top             =   120
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
         TabIndex        =   70
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
         TabIndex        =   68
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
         TabIndex        =   66
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
         TabIndex        =   67
         Top             =   960
         Width           =   495
      End
   End
   Begin VB.Label Label11 
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
      TabIndex        =   118
      Top             =   2400
      Width           =   975
   End
   Begin VB.Line Line10 
      X1              =   9360
      X2              =   9360
      Y1              =   2773.418
      Y2              =   3744.114
   End
   Begin VB.Label Label10 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Reason for Edit:"
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
      Left            =   360
      TabIndex        =   117
      Top             =   8640
      Width           =   1815
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
      TabIndex        =   107
      Top             =   8640
      Width           =   1695
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
      TabIndex        =   106
      Top             =   9120
      Width           =   1695
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
      TabIndex        =   105
      Top             =   9720
      Width           =   1455
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
      TabIndex        =   104
      Top             =   10200
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
      TabIndex        =   96
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
      TabIndex        =   95
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
      TabIndex        =   94
      Top             =   2400
      Width           =   855
   End
   Begin VB.Line Line13 
      X1              =   8280
      X2              =   8280
      Y1              =   3744.114
      Y2              =   2773.418
   End
   Begin VB.Line Line12 
      X1              =   7200
      X2              =   7200
      Y1              =   3744.114
      Y2              =   2773.418
   End
   Begin VB.Line Line11 
      X1              =   6240
      X2              =   10440
      Y1              =   3189.431
      Y2              =   3189.431
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
      TabIndex        =   90
      Top             =   2880
      Width           =   1455
   End
End
Attribute VB_Name = "SuperModTime"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
