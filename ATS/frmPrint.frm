VERSION 5.00
Begin VB.Form frmPrint 
   BackColor       =   &H00FFFFFF&
   Caption         =   "Printout (demo)"
   ClientHeight    =   10500
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   13785
   LinkTopic       =   "Form2"
   ScaleHeight     =   10500
   ScaleWidth      =   13785
   StartUpPosition =   3  'Windows Default
   Tag             =   "SQL = "" Customer_ID """
   Begin VB.CommandButton Command1 
      Caption         =   "Send to Printer"
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
      Left            =   5160
      TabIndex        =   91
      Top             =   9960
      Width           =   3255
   End
   Begin VB.Line Line24 
      X1              =   5160
      X2              =   5160
      Y1              =   7440
      Y2              =   8520
   End
   Begin VB.Line Line23 
      X1              =   4200
      X2              =   4200
      Y1              =   7440
      Y2              =   8520
   End
   Begin VB.Line Line17 
      X1              =   3240
      X2              =   7080
      Y1              =   7920
      Y2              =   7920
   End
   Begin VB.Line Line22 
      BorderWidth     =   2
      X1              =   120
      X2              =   13320
      Y1              =   7200
      Y2              =   7200
   End
   Begin VB.Line Line21 
      BorderWidth     =   2
      X1              =   120
      X2              =   13320
      Y1              =   1680
      Y2              =   1680
   End
   Begin VB.Line Line20 
      BorderWidth     =   2
      X1              =   6120
      X2              =   6120
      Y1              =   1680
      Y2              =   1200
   End
   Begin VB.Line Line19 
      X1              =   5160
      X2              =   5160
      Y1              =   1680
      Y2              =   1200
   End
   Begin VB.Line Line18 
      X1              =   4200
      X2              =   4200
      Y1              =   1680
      Y2              =   1200
   End
   Begin VB.Label lblYTDOTS 
      BackColor       =   &H00FFFFFF&
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
      Left            =   6240
      TabIndex        =   117
      Top             =   1200
      Width           =   735
   End
   Begin VB.Label lblAVGOT 
      BackColor       =   &H00FFFFFF&
      Caption         =   "YTD Average Overtime"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   7200
      TabIndex        =   116
      Top             =   8040
      Width           =   2535
   End
   Begin VB.Label lblOT 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Year to Date Overtime"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   7200
      TabIndex        =   115
      Top             =   7440
      Width           =   2535
   End
   Begin VB.Label lblYTDOTAvg 
      BackColor       =   &H00FFFFFF&
      Height          =   495
      Left            =   6240
      TabIndex        =   114
      Top             =   8040
      Width           =   735
   End
   Begin VB.Label lblYTDOT 
      BackColor       =   &H00FFFFFF&
      Height          =   495
      Left            =   6240
      TabIndex        =   113
      Top             =   7440
      Width           =   735
   End
   Begin VB.Label lblYTDSickE 
      BackColor       =   &H00FFFFFF&
      Height          =   495
      Left            =   5280
      TabIndex        =   112
      Top             =   8040
      Width           =   735
   End
   Begin VB.Label lblYTDPerE 
      BackColor       =   &H00FFFFFF&
      Height          =   495
      Left            =   4320
      TabIndex        =   111
      Top             =   8040
      Width           =   735
   End
   Begin VB.Label lblYTDVacE 
      BackColor       =   &H00FFFFFF&
      Height          =   495
      Left            =   3360
      TabIndex        =   110
      Top             =   8040
      Width           =   735
   End
   Begin VB.Label lblWeekEnd 
      BackColor       =   &H00FFFFFF&
      Caption         =   "YTD Balance:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   120
      TabIndex        =   109
      Top             =   8040
      Width           =   3015
   End
   Begin VB.Label lblSickAccr 
      BackColor       =   &H00FFFFFF&
      Height          =   495
      Left            =   5280
      TabIndex        =   108
      Top             =   7440
      Width           =   735
   End
   Begin VB.Label lblVacAccr 
      BackColor       =   &H00FFFFFF&
      Height          =   495
      Left            =   3360
      TabIndex        =   107
      Top             =   7440
      Width           =   735
   End
   Begin VB.Label lblAccruals 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Yearly Accrual:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   120
      TabIndex        =   106
      Top             =   7440
      Width           =   3015
   End
   Begin VB.Line LineYTDEND 
      BorderWidth     =   2
      X1              =   6120
      X2              =   6120
      Y1              =   7200
      Y2              =   8760
   End
   Begin VB.Label lblYTDQualifier 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
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
      Left            =   7200
      TabIndex        =   105
      Top             =   1200
      Width           =   6135
   End
   Begin VB.Label lblYTDSickS 
      BackColor       =   &H00FFFFFF&
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
      Left            =   5280
      TabIndex        =   104
      Top             =   1200
      Width           =   735
   End
   Begin VB.Label lblYTDPerS 
      BackColor       =   &H00FFFFFF&
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
      Left            =   4320
      TabIndex        =   103
      Top             =   1200
      Width           =   735
   End
   Begin VB.Label lblYTDVacS 
      BackColor       =   &H00FFFFFF&
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
      Left            =   3360
      TabIndex        =   102
      Top             =   1200
      Width           =   735
   End
   Begin VB.Label Label21 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Available:"
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
      Left            =   240
      TabIndex        =   101
      Top             =   1200
      Width           =   2895
   End
   Begin VB.Label lblSuCom 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   8280
      TabIndex        =   100
      Top             =   5880
      Width           =   5055
   End
   Begin VB.Label lblSaCom 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   8280
      TabIndex        =   99
      Top             =   5280
      Width           =   5055
   End
   Begin VB.Label lblFrCom 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   8280
      TabIndex        =   98
      Top             =   4680
      Width           =   5055
   End
   Begin VB.Label lblThCom 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   8280
      TabIndex        =   97
      Top             =   4080
      Width           =   5055
   End
   Begin VB.Label lblWeCom 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   8280
      TabIndex        =   96
      Top             =   3480
      Width           =   5055
   End
   Begin VB.Label lblTuCom 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   8280
      TabIndex        =   95
      Top             =   2880
      Width           =   5055
   End
   Begin VB.Label lblMoCom 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   8280
      TabIndex        =   94
      Top             =   2280
      Width           =   5055
   End
   Begin VB.Label Label20 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Comments"
      Height          =   255
      Left            =   8280
      TabIndex        =   93
      Top             =   1800
      Width           =   5055
   End
   Begin VB.Line Line16 
      X1              =   8160
      X2              =   8160
      Y1              =   1680
      Y2              =   7200
   End
   Begin VB.Label lblCond 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   5160
      TabIndex        =   92
      Top             =   9360
      Width           =   3615
   End
   Begin VB.Label lblSignOn 
      BackColor       =   &H00FFFFFF&
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
      Left            =   10680
      TabIndex        =   90
      Top             =   9720
      Width           =   2055
   End
   Begin VB.Label lblSignPC 
      BackColor       =   &H00FFFFFF&
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
      Left            =   10680
      TabIndex        =   89
      Top             =   9360
      Width           =   2055
   End
   Begin VB.Label lblSignBy 
      BackColor       =   &H00FFFFFF&
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
      Left            =   10680
      TabIndex        =   88
      Top             =   9000
      Width           =   2055
   End
   Begin VB.Label Label19 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "On:"
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
      Left            =   9000
      TabIndex        =   87
      Top             =   9720
      Width           =   1455
   End
   Begin VB.Label Label18 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "From PC:"
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
      Left            =   9000
      TabIndex        =   86
      Top             =   9360
      Width           =   1455
   End
   Begin VB.Label Label17 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "Approved by:"
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
      Left            =   9000
      TabIndex        =   85
      Top             =   9000
      Width           =   1455
   End
   Begin VB.Label lblSubOn 
      BackColor       =   &H00FFFFFF&
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
      Left            =   1920
      TabIndex        =   84
      Top             =   9720
      Width           =   2055
   End
   Begin VB.Label lblSubPC 
      BackColor       =   &H00FFFFFF&
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
      Left            =   1920
      TabIndex        =   83
      Top             =   9360
      Width           =   2055
   End
   Begin VB.Label lblSubBy 
      BackColor       =   &H00FFFFFF&
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
      Left            =   1920
      TabIndex        =   82
      Top             =   9000
      Width           =   2055
   End
   Begin VB.Label Label16 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "From PC:"
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
      Left            =   240
      TabIndex        =   81
      Top             =   9360
      Width           =   1455
   End
   Begin VB.Label Label15 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "On:"
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
      Left            =   240
      TabIndex        =   80
      Top             =   9720
      Width           =   1455
   End
   Begin VB.Label Label14 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "Submitted by:"
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
      Left            =   240
      TabIndex        =   79
      Top             =   9000
      Width           =   1455
   End
   Begin VB.Label lblDayTot 
      BackColor       =   &H00FFFFFF&
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
      Left            =   7200
      TabIndex        =   78
      Top             =   6720
      Width           =   855
   End
   Begin VB.Label lblDay7 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   7200
      TabIndex        =   77
      Top             =   5880
      Width           =   855
   End
   Begin VB.Label lblDay6 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   7200
      TabIndex        =   76
      Top             =   5280
      Width           =   855
   End
   Begin VB.Label lblDay5 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   7200
      TabIndex        =   75
      Top             =   4680
      Width           =   855
   End
   Begin VB.Label lblDay4 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   7200
      TabIndex        =   74
      Top             =   4080
      Width           =   855
   End
   Begin VB.Label lblDay3 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   7200
      TabIndex        =   73
      Top             =   3480
      Width           =   855
   End
   Begin VB.Label lblDay2 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   7200
      TabIndex        =   72
      Top             =   2880
      Width           =   855
   End
   Begin VB.Label lblDay1 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   7200
      TabIndex        =   71
      Top             =   2280
      Width           =   855
   End
   Begin VB.Label lblOTTot 
      BackColor       =   &H00FFFFFF&
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
      Left            =   6240
      TabIndex        =   70
      Top             =   6720
      Width           =   735
   End
   Begin VB.Label lblOT7 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   6240
      TabIndex        =   69
      Top             =   5880
      Width           =   735
   End
   Begin VB.Label lblOT6 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   6240
      TabIndex        =   68
      Top             =   5280
      Width           =   735
   End
   Begin VB.Label lblOT5 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   6240
      TabIndex        =   67
      Top             =   4680
      Width           =   735
   End
   Begin VB.Label lblOT4 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   6240
      TabIndex        =   66
      Top             =   4080
      Width           =   735
   End
   Begin VB.Label lblOT3 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   6240
      TabIndex        =   65
      Top             =   3480
      Width           =   735
   End
   Begin VB.Label lblOT2 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   6240
      TabIndex        =   64
      Top             =   2880
      Width           =   735
   End
   Begin VB.Label lblOT1 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   6240
      TabIndex        =   63
      Top             =   2280
      Width           =   735
   End
   Begin VB.Label lblSickTot 
      BackColor       =   &H00FFFFFF&
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
      Left            =   5280
      TabIndex        =   62
      Top             =   6720
      Width           =   735
   End
   Begin VB.Label lblSick7 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   5280
      TabIndex        =   61
      Top             =   5880
      Width           =   735
   End
   Begin VB.Label lblSick6 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   5280
      TabIndex        =   60
      Top             =   5280
      Width           =   735
   End
   Begin VB.Label lblSick5 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   5280
      TabIndex        =   59
      Top             =   4680
      Width           =   735
   End
   Begin VB.Label lblSick4 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   5280
      TabIndex        =   58
      Top             =   4080
      Width           =   735
   End
   Begin VB.Label lblSick3 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   5280
      TabIndex        =   57
      Top             =   3480
      Width           =   735
   End
   Begin VB.Label lblSick2 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   5280
      TabIndex        =   56
      Top             =   2880
      Width           =   735
   End
   Begin VB.Label lblSick1 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   5280
      TabIndex        =   55
      Top             =   2280
      Width           =   735
   End
   Begin VB.Label lblPerTot 
      BackColor       =   &H00FFFFFF&
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
      Left            =   4320
      TabIndex        =   54
      Top             =   6720
      Width           =   735
   End
   Begin VB.Label lblPer7 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   4320
      TabIndex        =   53
      Top             =   5880
      Width           =   735
   End
   Begin VB.Label lblPer6 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   4320
      TabIndex        =   52
      Top             =   5280
      Width           =   735
   End
   Begin VB.Label lblPer5 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   4320
      TabIndex        =   51
      Top             =   4680
      Width           =   735
   End
   Begin VB.Label lblPer4 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   4320
      TabIndex        =   50
      Top             =   4080
      Width           =   735
   End
   Begin VB.Label lblPer3 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   4320
      TabIndex        =   49
      Top             =   3480
      Width           =   735
   End
   Begin VB.Label lblPer2 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   4320
      TabIndex        =   48
      Top             =   2880
      Width           =   735
   End
   Begin VB.Label lblPer1 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   4320
      TabIndex        =   47
      Top             =   2280
      Width           =   735
   End
   Begin VB.Label lblVacTot 
      BackColor       =   &H00FFFFFF&
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
      Left            =   3360
      TabIndex        =   46
      Top             =   6720
      Width           =   735
   End
   Begin VB.Label lblVac7 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   3360
      TabIndex        =   45
      Top             =   5880
      Width           =   735
   End
   Begin VB.Label lblVac6 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   3360
      TabIndex        =   44
      Top             =   5280
      Width           =   735
   End
   Begin VB.Label lblVac5 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   3360
      TabIndex        =   43
      Top             =   4680
      Width           =   735
   End
   Begin VB.Label lblVac4 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   3360
      TabIndex        =   42
      Top             =   4080
      Width           =   735
   End
   Begin VB.Label lblVac3 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   3360
      TabIndex        =   41
      Top             =   3480
      Width           =   735
   End
   Begin VB.Label lblVac2 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   3360
      TabIndex        =   40
      Top             =   2880
      Width           =   735
   End
   Begin VB.Label lblVac1 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   3360
      TabIndex        =   39
      Top             =   2280
      Width           =   735
   End
   Begin VB.Label lblHolTot 
      BackColor       =   &H00FFFFFF&
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
      Left            =   2400
      TabIndex        =   38
      Top             =   6720
      Width           =   735
   End
   Begin VB.Label lblHol7 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   2400
      TabIndex        =   37
      Top             =   5880
      Width           =   735
   End
   Begin VB.Label lblHol6 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   2400
      TabIndex        =   36
      Top             =   5280
      Width           =   735
   End
   Begin VB.Label lblHol5 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   2400
      TabIndex        =   35
      Top             =   4680
      Width           =   735
   End
   Begin VB.Label lblHol4 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   2400
      TabIndex        =   34
      Top             =   4080
      Width           =   735
   End
   Begin VB.Label lblHol3 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   2400
      TabIndex        =   33
      Top             =   3480
      Width           =   735
   End
   Begin VB.Label lblHol2 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   2400
      TabIndex        =   32
      Top             =   2880
      Width           =   735
   End
   Begin VB.Label lblHol1 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   2400
      TabIndex        =   31
      Top             =   2280
      Width           =   735
   End
   Begin VB.Line Line15 
      X1              =   120
      X2              =   13320
      Y1              =   6600
      Y2              =   6600
   End
   Begin VB.Label lblRegTot 
      BackColor       =   &H00FFFFFF&
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
      Left            =   1440
      TabIndex        =   30
      Top             =   6720
      Width           =   735
   End
   Begin VB.Label Label13 
      BackColor       =   &H00FFFFFF&
      Caption         =   "WEEKLY TOTALS:"
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
      Left            =   240
      TabIndex        =   29
      Top             =   6720
      Width           =   975
   End
   Begin VB.Label lblReg7 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   1440
      TabIndex        =   28
      Top             =   5880
      Width           =   735
   End
   Begin VB.Label lblReg6 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   1440
      TabIndex        =   27
      Top             =   5280
      Width           =   735
   End
   Begin VB.Label lblReg5 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   1440
      TabIndex        =   26
      Top             =   4680
      Width           =   735
   End
   Begin VB.Label lblReg4 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   1440
      TabIndex        =   25
      Top             =   4080
      Width           =   735
   End
   Begin VB.Label lblReg3 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   1440
      TabIndex        =   24
      Top             =   3480
      Width           =   735
   End
   Begin VB.Label lblReg2 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   1440
      TabIndex        =   23
      Top             =   2880
      Width           =   735
   End
   Begin VB.Label lblReg1 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   1440
      TabIndex        =   22
      Top             =   2280
      Width           =   735
   End
   Begin VB.Label lblDt7 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   240
      TabIndex        =   21
      Top             =   5880
      Width           =   975
   End
   Begin VB.Line Line14 
      X1              =   120
      X2              =   13320
      Y1              =   5760
      Y2              =   5760
   End
   Begin VB.Label lblDt6 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   240
      TabIndex        =   20
      Top             =   5280
      Width           =   975
   End
   Begin VB.Line Line13 
      X1              =   120
      X2              =   13320
      Y1              =   5160
      Y2              =   5160
   End
   Begin VB.Label lblDt5 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   240
      TabIndex        =   19
      Top             =   4680
      Width           =   975
   End
   Begin VB.Line Line12 
      X1              =   120
      X2              =   13320
      Y1              =   4560
      Y2              =   4560
   End
   Begin VB.Label lblDt4 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   240
      TabIndex        =   18
      Top             =   4080
      Width           =   975
   End
   Begin VB.Line Line11 
      X1              =   120
      X2              =   13320
      Y1              =   3960
      Y2              =   3960
   End
   Begin VB.Label lblDt3 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   240
      TabIndex        =   17
      Top             =   3480
      Width           =   975
   End
   Begin VB.Line Line10 
      X1              =   120
      X2              =   13320
      Y1              =   3360
      Y2              =   3360
   End
   Begin VB.Label lblDt2 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   240
      TabIndex        =   16
      Top             =   2880
      Width           =   975
   End
   Begin VB.Line Line9 
      X1              =   120
      X2              =   13320
      Y1              =   2760
      Y2              =   2760
   End
   Begin VB.Label lblDt1 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   240
      TabIndex        =   15
      Top             =   2280
      Width           =   975
   End
   Begin VB.Label lblStatus 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   6360
      TabIndex        =   14
      Top             =   8880
      Width           =   2295
   End
   Begin VB.Label Label12 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Status:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   5160
      TabIndex        =   13
      Top             =   8880
      Width           =   975
   End
   Begin VB.Line Line8 
      X1              =   120
      X2              =   13320
      Y1              =   2160
      Y2              =   2160
   End
   Begin VB.Line Line7 
      X1              =   7080
      X2              =   7080
      Y1              =   1680
      Y2              =   7200
   End
   Begin VB.Line Line6 
      BorderWidth     =   2
      X1              =   6120
      X2              =   6120
      Y1              =   1680
      Y2              =   7200
   End
   Begin VB.Line Line5 
      X1              =   5160
      X2              =   5160
      Y1              =   1680
      Y2              =   7200
   End
   Begin VB.Line Line4 
      X1              =   4200
      X2              =   4200
      Y1              =   1680
      Y2              =   7200
   End
   Begin VB.Line Line3 
      X1              =   3240
      X2              =   3240
      Y1              =   1680
      Y2              =   7200
   End
   Begin VB.Line Line2 
      X1              =   2280
      X2              =   2280
      Y1              =   1680
      Y2              =   7200
   End
   Begin VB.Line Line1 
      X1              =   1320
      X2              =   1320
      Y1              =   1680
      Y2              =   7200
   End
   Begin VB.Label Label11 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Daily Total"
      Height          =   255
      Left            =   7200
      TabIndex        =   12
      Top             =   1800
      Width           =   855
   End
   Begin VB.Label Label10 
      BackColor       =   &H00FFFFFF&
      Caption         =   "OT"
      Height          =   255
      Left            =   6240
      TabIndex        =   11
      Top             =   1800
      Width           =   735
   End
   Begin VB.Label Label9 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Sick Hrs"
      Height          =   255
      Left            =   5280
      TabIndex        =   10
      Top             =   1800
      Width           =   735
   End
   Begin VB.Label Label8 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Pers Hrs"
      Height          =   255
      Left            =   4320
      TabIndex        =   9
      Top             =   1800
      Width           =   735
   End
   Begin VB.Label Label7 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Vac Hrs"
      Height          =   255
      Left            =   3360
      TabIndex        =   8
      Top             =   1800
      Width           =   735
   End
   Begin VB.Label Label6 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Hol Hrs"
      Height          =   255
      Left            =   2400
      TabIndex        =   7
      Top             =   1800
      Width           =   735
   End
   Begin VB.Label Label5 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Reg Hrs"
      Height          =   255
      Left            =   1440
      TabIndex        =   6
      Top             =   1800
      Width           =   735
   End
   Begin VB.Label Label4 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Day / Date:"
      Height          =   255
      Left            =   240
      TabIndex        =   5
      Top             =   1800
      Width           =   975
   End
   Begin VB.Label lblWeekStart 
      BackColor       =   &H00FFFFFF&
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
      Left            =   9600
      TabIndex        =   4
      Top             =   720
      Width           =   2295
   End
   Begin VB.Label Label3 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Week Starting:"
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
      Left            =   7800
      TabIndex        =   3
      Top             =   720
      Width           =   1575
   End
   Begin VB.Label lblEmpName 
      BackColor       =   &H00FFFFFF&
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
      Left            =   3120
      TabIndex        =   2
      Top             =   720
      Width           =   2895
   End
   Begin VB.Label Label2 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Employee:"
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
      Left            =   1680
      TabIndex        =   1
      Top             =   720
      Width           =   1215
   End
   Begin VB.Label Label1 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Weekly Time Sheet"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   5040
      TabIndex        =   0
      Top             =   120
      Width           =   2535
   End
End
Attribute VB_Name = "frmPrint"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim MonDate As String
Dim TueDate As String
Dim WedDate As String
Dim ThuDate As String
Dim FriDate As String
Dim SatDate As String
Dim SunDate As String
Dim LastAppWeek As String
Dim LastAppWeekOra As String
Dim NoOfWeeksForOT As Double

Public strEmployeeID As String
Public strWeek As String
' note:  this page really shouldn't be such it's own entity; it should be a series of function calls to the TimeSheet form.
' at a later time, when I have said time, this should be re-written to just copy from the existing form.  This cut'n'paste job is only
' because I don't have sufficient knowledge at this time of VB to do what I just stated.  Inigo says:  I don't like it.

Private Sub Command1_Click()

Dim TempDrawWidth As Long

MonDate = strWeek
TueDate = DateAdd("d", 1, MonDate)
WedDate = DateAdd("d", 1, TueDate)
ThuDate = DateAdd("d", 1, WedDate)
FriDate = DateAdd("d", 1, ThuDate)
SatDate = DateAdd("d", 1, FriDate)
SunDate = DateAdd("d", 1, SatDate)

MonDate = Format(MonDate, "mm/dd/yyyy")
TueDate = Format(TueDate, "mm/dd/yyyy")
WedDate = Format(WedDate, "mm/dd/yyyy")
ThuDate = Format(ThuDate, "mm/dd/yyyy")
FriDate = Format(FriDate, "mm/dd/yyyy")
SatDate = Format(SatDate, "mm/dd/yyyy")
SunDate = Format(SunDate, "mm/dd/yyyy")



Printer.Orientation = 2
Printer.FontBold = False

Printer.FontSize = 16

'Printer.Print

Printer.Print Tab(37); "Port of Wilmington Timesheet"

Printer.FontSize = 12
Printer.Print

strSql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmployeeID & "'"
Set dsPRINTINFOHEADER = OraDatabase.DbCreateDynaset(strSql, 0&)

Printer.Print Tab(20); "Employee:  " & dsPRINTINFOHEADER.Fields("FIRST_NAME").Value & " " & dsPRINTINFOHEADER.Fields("LAST_NAME").Value; Tab(95); "Week Starting:  " & MonDate
Printer.Print
Printer.FontSize = 10

Printer.FontBold = True
If (lblYTDQualifier.Visible = True) Then
    Printer.Print Tab(9); "Available:"; Tab(46); lblYTDVacS.Caption; Tab(56); lblYTDPerS.Caption; Tab(65); lblYTDSickS.Caption; Tab(74); lblYTDOTS.Caption; Tab(90); lblYTDQualifier.Caption
Else
    Printer.Print Tab(9); "Available:"; Tab(46); lblYTDVacS.Caption; Tab(56); lblYTDPerS.Caption; Tab(65); lblYTDSickS.Caption; Tab(74); lblYTDOTS.Caption
End If
Printer.FontBold = False

Printer.Print
Printer.Print
Printer.Print Tab(10); "Date:"; Tab(30); "Reg Hrs"; Tab(40); "Hol Hrs"; Tab(50); "Vac Hrs"; Tab(60); "Pers Hrs"; Tab(70); "Sick Hrs"; Tab(80); "OT"; Tab(90); "Total"; Tab(100); "Comments"
Printer.Print
Printer.Print

strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' AND WEEK_START_MONDAY = '" & strWeek & "'"
Set dsPRINTINFODATA = OraDatabase.DbCreateDynaset(strSql, 0&)

Printer.Print Tab(10); "Mon " & MonDate; Tab(30); dsPRINTINFODATA.Fields("MON_REG").Value; Tab(40); dsPRINTINFODATA.Fields("MON_HOLIDAY").Value; Tab(50); _
                dsPRINTINFODATA.Fields("MON_VACATION").Value; Tab(60); dsPRINTINFODATA.Fields("MON_PERSONAL").Value; Tab(70); _
                dsPRINTINFODATA.Fields("MON_SICK").Value; Tab(80); dsPRINTINFODATA.Fields("MON_OVERTIME").Value; Tab(90); _
                dsPRINTINFODATA.Fields("MON_TOTAL").Value; Tab(100); "" & dsPRINTINFODATA.Fields("MONDAY_COMMENTS").Value
Printer.Print
Printer.Print

Printer.Print Tab(10); "Tue " & TueDate; Tab(30); dsPRINTINFODATA.Fields("TUE_REG").Value; Tab(40); dsPRINTINFODATA.Fields("TUE_HOLIDAY").Value; Tab(50); _
                dsPRINTINFODATA.Fields("TUE_VACATION").Value; Tab(60); dsPRINTINFODATA.Fields("TUE_PERSONAL").Value; Tab(70); _
                dsPRINTINFODATA.Fields("TUE_SICK").Value; Tab(80); dsPRINTINFODATA.Fields("TUE_OVERTIME").Value; Tab(90); _
                dsPRINTINFODATA.Fields("TUE_TOTAL").Value; Tab(100); "" & dsPRINTINFODATA.Fields("TUESDAY_COMMENTS").Value
Printer.Print
Printer.Print

Printer.Print Tab(10); "Wed " & WedDate; Tab(30); dsPRINTINFODATA.Fields("WED_REG").Value; Tab(40); dsPRINTINFODATA.Fields("WED_HOLIDAY").Value; Tab(50); _
                dsPRINTINFODATA.Fields("WED_VACATION").Value; Tab(60); dsPRINTINFODATA.Fields("WED_PERSONAL").Value; Tab(70); _
                dsPRINTINFODATA.Fields("WED_SICK").Value; Tab(80); dsPRINTINFODATA.Fields("WED_OVERTIME").Value; Tab(90); _
                dsPRINTINFODATA.Fields("WED_TOTAL").Value; Tab(100); "" & dsPRINTINFODATA.Fields("WEDNESDAY_COMMENTS").Value
Printer.Print
Printer.Print

Printer.Print Tab(10); "Thu " & ThuDate; Tab(30); dsPRINTINFODATA.Fields("THU_REG").Value; Tab(40); dsPRINTINFODATA.Fields("THU_HOLIDAY").Value; Tab(50); _
                dsPRINTINFODATA.Fields("THU_VACATION").Value; Tab(60); dsPRINTINFODATA.Fields("THU_PERSONAL").Value; Tab(70); _
                dsPRINTINFODATA.Fields("THU_SICK").Value; Tab(80); dsPRINTINFODATA.Fields("THU_OVERTIME").Value; Tab(90); _
                dsPRINTINFODATA.Fields("THU_TOTAL").Value; Tab(100); "" & dsPRINTINFODATA.Fields("THURSDAY_COMMENTS").Value
Printer.Print
Printer.Print

Printer.Print Tab(10); "Fri " & FriDate; Tab(30); dsPRINTINFODATA.Fields("FRI_REG").Value; Tab(40); dsPRINTINFODATA.Fields("FRI_HOLIDAY").Value; Tab(50); _
                dsPRINTINFODATA.Fields("FRI_VACATION").Value; Tab(60); dsPRINTINFODATA.Fields("FRI_PERSONAL").Value; Tab(70); _
                dsPRINTINFODATA.Fields("FRI_SICK").Value; Tab(80); dsPRINTINFODATA.Fields("FRI_OVERTIME").Value; Tab(90); _
                dsPRINTINFODATA.Fields("FRI_TOTAL").Value; Tab(100); "" & dsPRINTINFODATA.Fields("FRIDAY_COMMENTS").Value
Printer.Print
Printer.Print

Printer.Print Tab(10); "Sat " & SatDate; Tab(30); dsPRINTINFODATA.Fields("SAT_REG").Value; Tab(40); "N/A"; Tab(50); _
                "N/A"; Tab(60); "N/A"; Tab(70); _
                "N/A"; Tab(80); dsPRINTINFODATA.Fields("SAT_OVERTIME").Value; Tab(90); _
                dsPRINTINFODATA.Fields("SAT_TOTAL").Value; Tab(100); "" & dsPRINTINFODATA.Fields("SATURDAY_COMMENTS").Value
Printer.Print
Printer.Print

Printer.Print Tab(10); "Sun " & SunDate; Tab(30); dsPRINTINFODATA.Fields("SUN_REG").Value; Tab(40); "N/A"; Tab(50); _
                "N/A"; Tab(60); "N/A"; Tab(70); _
                "N/A"; Tab(80); dsPRINTINFODATA.Fields("SUN_OVERTIME").Value; Tab(90); _
                dsPRINTINFODATA.Fields("SUN_TOTAL").Value; Tab(100); "" & dsPRINTINFODATA.Fields("SUNDAY_COMMENTS").Value
Printer.Print
Printer.Print

Printer.Print Tab(10); "Totals"; Tab(30); dsPRINTINFODATA.Fields("WEEK_TOTAL_REG").Value; Tab(40); dsPRINTINFODATA.Fields("WEEK_TOTAL_HOLIDAY").Value; Tab(50); _
                dsPRINTINFODATA.Fields("WEEK_TOTAL_VACATION").Value; Tab(60); dsPRINTINFODATA.Fields("WEEK_TOTAL_PERSONAL").Value; Tab(70); _
                dsPRINTINFODATA.Fields("WEEK_TOTAL_SICK").Value; Tab(80); dsPRINTINFODATA.Fields("WEEK_TOTAL_OVERTIME").Value; Tab(90); _
                dsPRINTINFODATA.Fields("WEEK_TOTAL_TOTAL").Value
Printer.Print
Printer.Print


Printer.FontBold = True
If (lblYTDQualifier.Visible = False) Then
    Printer.FontSize = 10
    Printer.Print Tab(9); lblAccruals.Caption; Tab(46); lblVacAccr.Caption; Tab(65); lblSickAccr.Caption; Tab(74); lblYTDOT.Caption; Tab(90); lblOT.Caption
    Printer.Print
    Printer.Print Tab(9); lblWeekEnd.Caption; Tab(46); lblYTDVacE.Caption; Tab(56); lblYTDPerE.Caption; Tab(65); lblYTDSickE.Caption; Tab(74); lblYTDOTAvg.Caption; Tab(90); lblAVGOT.Caption
    Printer.Print
    Printer.Print
End If
Printer.FontBold = False



Printer.FontSize = 16

Printer.Print Tab(40); "Status:  " & dsPRINTINFODATA.Fields("STATUS").Value

If dsPRINTINFODATA.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
    Printer.Print Tab(40); "--- CONDITIONAL ---"
End If

Printer.Print

Printer.FontSize = 12

strSql = "SELECT NVL(SUBMISSION_PC_USERID, 'N/A') THE_SUB_USER, NVL(SUBMISSION_PC, 'N/A') THE_SUB_ID, " _
        & "NVL(TO_CHAR(SUBMISSION_DATETIME, 'MM/DD/YYYY HH:MI AM'), 'N/A') THE_SUB_DATE, NVL(SIGN_OFF_PC_USERID, 'N/A') THE_SIGN_USER, " _
        & "NVL(SIGN_OFF_PC, 'N/A') THE_SIGN_PC, NVL(TO_CHAR(SIGN_OFF_DATETIME, 'MM/DD/YYYY HH:MI AM'), 'N/A') THE_SIGN_TIME " _
        & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' AND WEEK_START_MONDAY = '" & strWeek & "'"
Set dsPRINTINFODATA = OraDatabase.DbCreateDynaset(strSql, 0&)

Printer.Print Tab(8); "Submitted by:  " & dsPRINTINFODATA.Fields("THE_SUB_USER").Value; Tab(100); "Approved by:  " & dsPRINTINFODATA.Fields("THE_SIGN_USER").Value
'Printer.Print
Printer.Print Tab(12); "From PC:  " & dsPRINTINFODATA.Fields("THE_SUB_ID").Value; Tab(104); "From PC:  " & dsPRINTINFODATA.Fields("THE_SIGN_PC").Value
'Printer.Print
Printer.Print Tab(18); "On:  " & dsPRINTINFODATA.Fields("THE_SUB_DATE").Value; Tab(110); "On:  " & dsPRINTINFODATA.Fields("THE_SIGN_TIME").Value
'Printer.Print

Printer.FontSize = 16

Printer.Print Tab(37); "Printed on:    " & Format(Now, "mm/DD/YYYY hh:mm AM/PM")


TempDrawWidth = Printer.DrawWidth
'vertical lines
Printer.Line (2400, 1500)-(2400, 7500)
Printer.Line (3350, 1500)-(3350, 7500)
Printer.Line (4250, 1500)-(4250, 7500)
Printer.Line (5150, 1500)-(5150, 7500)
Printer.Line (6050, 1500)-(6050, 7500)
Printer.Line (7850, 1500)-(7850, 7500)
Printer.Line (8750, 1500)-(8750, 7500)
Printer.DrawWidth = 7
Printer.Line (6950, 900)-(6950, 7500)
Printer.DrawWidth = TempDrawWidth

'horizontal lines
Printer.Line (800, 2100)-(14500, 2100)
Printer.Line (800, 2776)-(14500, 2776)
Printer.Line (800, 3453)-(14500, 3453)
Printer.Line (800, 4130)-(14500, 4130)
Printer.Line (800, 4806)-(14500, 4806)
Printer.Line (800, 5483)-(14500, 5483)
Printer.Line (800, 6160)-(14500, 6160)
Printer.Line (800, 6836)-(14500, 6836)

'if no outstanding conditional weeks (I.E. bottom part of page was printed), draw lines
If (lblYTDQualifier.Visible = False) Then
    Printer.Line (5150, 1500)-(5150, 1000)
    Printer.Line (6050, 1500)-(6050, 1000)
    Printer.Line (5150, 7825)-(5150, 8800)
    Printer.Line (6050, 7825)-(6050, 8800)
    Printer.Line (4250, 8300)-(7850, 8300)
    
    Printer.DrawWidth = 7
    Printer.Line (800, 1500)-(14500, 1500)
    Printer.Line (800, 7513)-(14500, 7513)
    Printer.Line (6950, 7500)-(6950, 8900)
    Printer.DrawWidth = TempDrawWidth
End If

Printer.EndDoc
End Sub

Private Sub form_load()

MonDate = strWeek
TueDate = DateAdd("d", 1, MonDate)
WedDate = DateAdd("d", 1, TueDate)
ThuDate = DateAdd("d", 1, WedDate)
FriDate = DateAdd("d", 1, ThuDate)
SatDate = DateAdd("d", 1, FriDate)
SunDate = DateAdd("d", 1, SatDate)

MonDate = Format(MonDate, "mm/dd/yyyy")
TueDate = Format(TueDate, "mm/dd/yyyy")
WedDate = Format(WedDate, "mm/dd/yyyy")
ThuDate = Format(ThuDate, "mm/dd/yyyy")
FriDate = Format(FriDate, "mm/dd/yyyy")
SatDate = Format(SatDate, "mm/dd/yyyy")
SunDate = Format(SunDate, "mm/dd/yyyy")


strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' AND WEEK_START_MONDAY = '" & strWeek & "'"
Set dsPRINTINFODATA = OraDatabase.DbCreateDynaset(strSql, 0&)

strSql = "SELECT * FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmployeeID & "'"
Set dsPRINTINFOHEADER = OraDatabase.DbCreateDynaset(strSql, 0&)

lblEmpName.Caption = dsPRINTINFOHEADER.Fields("FIRST_NAME").Value & " " & dsPRINTINFOHEADER.Fields("LAST_NAME").Value
lblWeekStart.Caption = MonDate
lblStatus.Caption = dsPRINTINFODATA.Fields("STATUS").Value

lblDt1.Caption = "Monday " & MonDate
lblDt2.Caption = "Tuesday " & TueDate
lblDt3.Caption = "Wednesday " & WedDate
lblDt4.Caption = "Thursday " & ThuDate
lblDt5.Caption = "Friday " & FriDate
lblDt6.Caption = "Saturday " & SatDate
lblDt7.Caption = "Sunday " & SunDate

If dsPRINTINFODATA.Fields("CONDITIONAL_SUBMISSION").Value = "Y" Then
    lblCond.Caption = "--- CONDITIONAL ---"
Else
    lblCond.Caption = ""
End If

strSql = "SELECT * FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' AND WEEK_START_MONDAY < '" & strWeek _
        & "' AND (CONDITIONAL_SUBMISSION = 'Y' OR STATUS != 'APPROVED')"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
If dsSHORT_TERM_DATA.Recordcount >= 1 Then
    strSql = "SELECT TO_CHAR(MIN(WEEK_START_MONDAY), 'MM/DD/YYYY') THE_WEEK, TO_CHAR(MIN(WEEK_START_MONDAY), 'DD-mon-YYYY') THE_WEEK_ORA " _
            & " FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' " _
            & " AND (CONDITIONAL_SUBMISSION = 'Y' OR STATUS != 'APPROVED')"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    LastAppWeek = dsSHORT_TERM_DATA.Fields("THE_WEEK").Value
    LastAppWeekOra = dsSHORT_TERM_DATA.Fields("THE_WEEK_ORA").Value
Else
    LastAppWeek = MonDate
    LastAppWeekOra = strWeek
End If
strSql = "SELECT YTD_WEEK_START_VACATION_BAL, YTD_WEEK_START_PERSONAL_BAL, YTD_WEEK_START_SICK_BAL, WEEKLY_ACCRUAL_VACATION, WEEKLY_ACCRUAL_SICK, YTD_WEEK_START_TOTAL_OVERTIME " _
        & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' AND WEEK_START_MONDAY = '" & LastAppWeekOra & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
lblYTDVacS.Caption = FormatNumber(dsSHORT_TERM_DATA.Fields("YTD_WEEK_START_VACATION_BAL").Value, 2)
lblYTDPerS.Caption = FormatNumber(dsSHORT_TERM_DATA.Fields("YTD_WEEK_START_PERSONAL_BAL").Value, 2)
lblYTDSickS.Caption = FormatNumber(dsSHORT_TERM_DATA.Fields("YTD_WEEK_START_SICK_BAL").Value, 2)
lblYTDQualifier.Caption = "As of last processed Week:  " & LastAppWeek
lblVacAccr.Caption = FormatNumber(dsSHORT_TERM_DATA.Fields("WEEKLY_ACCRUAL_VACATION").Value, 2)
lblSickAccr.Caption = FormatNumber(dsSHORT_TERM_DATA.Fields("WEEKLY_ACCRUAL_SICK").Value, 2)
lblYTDOTS.Caption = FormatNumber(dsSHORT_TERM_DATA.Fields("YTD_WEEK_START_TOTAL_OVERTIME").Value, 2)

lblReg1.Caption = dsPRINTINFODATA.Fields("MON_REG").Value
lblReg2.Caption = dsPRINTINFODATA.Fields("TUE_REG").Value
lblReg3.Caption = dsPRINTINFODATA.Fields("WED_REG").Value
lblReg4.Caption = dsPRINTINFODATA.Fields("THU_REG").Value
lblReg5.Caption = dsPRINTINFODATA.Fields("FRI_REG").Value
lblReg6.Caption = dsPRINTINFODATA.Fields("SAT_REG").Value
lblReg7.Caption = dsPRINTINFODATA.Fields("SUN_REG").Value
lblRegTot.Caption = dsPRINTINFODATA.Fields("WEEK_TOTAL_REG").Value

lblHol1.Caption = dsPRINTINFODATA.Fields("MON_HOLIDAY").Value
lblHol2.Caption = dsPRINTINFODATA.Fields("TUE_HOLIDAY").Value
lblHol3.Caption = dsPRINTINFODATA.Fields("WED_HOLIDAY").Value
lblHol4.Caption = dsPRINTINFODATA.Fields("THU_HOLIDAY").Value
lblHol5.Caption = dsPRINTINFODATA.Fields("FRI_HOLIDAY").Value
lblHol6.Caption = "N/A"
lblHol7.Caption = "N/A"
lblHolTot.Caption = dsPRINTINFODATA.Fields("WEEK_TOTAL_HOLIDAY").Value

lblVac1.Caption = dsPRINTINFODATA.Fields("MON_VACATION").Value
lblVac2.Caption = dsPRINTINFODATA.Fields("TUE_VACATION").Value
lblVac3.Caption = dsPRINTINFODATA.Fields("WED_VACATION").Value
lblVac4.Caption = dsPRINTINFODATA.Fields("THU_VACATION").Value
lblVac5.Caption = dsPRINTINFODATA.Fields("FRI_VACATION").Value
lblVac6.Caption = "N/A"
lblVac7.Caption = "N/A"
lblVacTot.Caption = dsPRINTINFODATA.Fields("WEEK_TOTAL_VACATION").Value

lblPer1.Caption = dsPRINTINFODATA.Fields("MON_PERSONAL").Value
lblPer2.Caption = dsPRINTINFODATA.Fields("TUE_PERSONAL").Value
lblPer3.Caption = dsPRINTINFODATA.Fields("WED_PERSONAL").Value
lblPer4.Caption = dsPRINTINFODATA.Fields("THU_PERSONAL").Value
lblPer5.Caption = dsPRINTINFODATA.Fields("FRI_PERSONAL").Value
lblPer6.Caption = "N/A"
lblPer7.Caption = "N/A"
lblPerTot.Caption = dsPRINTINFODATA.Fields("WEEK_TOTAL_PERSONAL").Value

lblSick1.Caption = dsPRINTINFODATA.Fields("MON_SICK").Value
lblSick2.Caption = dsPRINTINFODATA.Fields("TUE_SICK").Value
lblSick3.Caption = dsPRINTINFODATA.Fields("WED_SICK").Value
lblSick4.Caption = dsPRINTINFODATA.Fields("THU_SICK").Value
lblSick5.Caption = dsPRINTINFODATA.Fields("FRI_SICK").Value
lblSick6.Caption = "N/A"
lblSick7.Caption = "N/A"
lblSickTot.Caption = dsPRINTINFODATA.Fields("WEEK_TOTAL_SICK").Value

lblOT1.Caption = dsPRINTINFODATA.Fields("MON_OVERTIME").Value
lblOT2.Caption = dsPRINTINFODATA.Fields("TUE_OVERTIME").Value
lblOT3.Caption = dsPRINTINFODATA.Fields("WED_OVERTIME").Value
lblOT4.Caption = dsPRINTINFODATA.Fields("THU_OVERTIME").Value
lblOT5.Caption = dsPRINTINFODATA.Fields("FRI_OVERTIME").Value
lblOT6.Caption = dsPRINTINFODATA.Fields("SAT_OVERTIME").Value
lblOT7.Caption = dsPRINTINFODATA.Fields("SUN_OVERTIME").Value
lblOTTot.Caption = dsPRINTINFODATA.Fields("WEEK_TOTAL_OVERTIME").Value

lblDay1.Caption = dsPRINTINFODATA.Fields("MON_TOTAL").Value
lblDay2.Caption = dsPRINTINFODATA.Fields("TUE_TOTAL").Value
lblDay3.Caption = dsPRINTINFODATA.Fields("WED_TOTAL").Value
lblDay4.Caption = dsPRINTINFODATA.Fields("THU_TOTAL").Value
lblDay5.Caption = dsPRINTINFODATA.Fields("FRI_TOTAL").Value
lblDay6.Caption = dsPRINTINFODATA.Fields("SAT_TOTAL").Value
lblDay7.Caption = dsPRINTINFODATA.Fields("SUN_TOTAL").Value
lblDayTot.Caption = dsPRINTINFODATA.Fields("WEEK_TOTAL_TOTAL").Value

lblMoCom.Caption = "" & dsPRINTINFODATA.Fields("MONDAY_COMMENTS").Value
lblTuCom.Caption = "" & dsPRINTINFODATA.Fields("TUESDAY_COMMENTS").Value
lblWeCom.Caption = "" & dsPRINTINFODATA.Fields("WEDNESDAY_COMMENTS").Value
lblThCom.Caption = "" & dsPRINTINFODATA.Fields("THURSDAY_COMMENTS").Value
lblFrCom.Caption = "" & dsPRINTINFODATA.Fields("FRIDAY_COMMENTS").Value
lblSaCom.Caption = "" & dsPRINTINFODATA.Fields("SATURDAY_COMMENTS").Value
lblSuCom.Caption = "" & dsPRINTINFODATA.Fields("SUNDAY_COMMENTS").Value

' times entered, now so some possible null-selections.
strSql = "SELECT NVL(SUBMISSION_PC_USERID, 'N/A') THE_SUB_USER, NVL(SUBMISSION_PC, 'N/A') THE_SUB_ID, " _
        & "NVL(TO_CHAR(SUBMISSION_DATETIME, 'MM/DD/YYYY HH:MI AM'), 'N/A') THE_SUB_DATE, NVL(SIGN_OFF_PC_USERID, 'N/A') THE_SIGN_USER, " _
        & "NVL(SIGN_OFF_PC, 'N/A') THE_SIGN_PC, NVL(TO_CHAR(SIGN_OFF_DATETIME, 'MM/DD/YYYY HH:MI AM'), 'N/A') THE_SIGN_TIME " _
        & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' AND WEEK_START_MONDAY = '" & strWeek & "'"
Set dsPRINTINFODATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        
lblSubBy.Caption = dsPRINTINFODATA.Fields("THE_SUB_USER").Value
lblSubPC.Caption = dsPRINTINFODATA.Fields("THE_SUB_ID").Value
lblSubOn.Caption = dsPRINTINFODATA.Fields("THE_SUB_DATE").Value

lblSignBy.Caption = dsPRINTINFODATA.Fields("THE_SIGN_USER").Value
lblSignPC.Caption = dsPRINTINFODATA.Fields("THE_SIGN_PC").Value
lblSignOn.Caption = dsPRINTINFODATA.Fields("THE_SIGN_TIME").Value

lblYTDVacE.Caption = FormatNumber(Val("" & lblYTDVacS.Caption) + Val("" & lblVacAccr.Caption) - Val("" & lblVacTot.Caption), 2)
lblYTDPerE.Caption = FormatNumber(Val("" & lblYTDPerS.Caption) - Val("" & lblPerTot.Caption), 2)
lblYTDSickE.Caption = FormatNumber(dsSHORT_TERM_DATA.Fields("YTD_WEEK_START_SICK_BAL").Value + Val("" & lblSickAccr.Caption) - Val("" & lblSickTot.Caption), 2)
lblYTDOT.Caption = FormatNumber(Val("" & lblYTDOTS.Caption) + Val("" & lblOTTot.Caption), 2)

strSql = "SELECT COUNT(*) THE_WEEKS FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' AND FRI_DATE >= '01-JAN-" & Right(DateAdd("d", 4, strWeek), 4) & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
NoOfWeeksForOT = dsSHORT_TERM_DATA.Fields("THE_WEEKS").Value
lblYTDOTAvg.Caption = FormatNumber(Val("" & lblYTDOT.Caption) / NoOfWeeksForOT, 2)


If strWeek = LastAppWeekOra Then
    lblYTDQualifier.Visible = False
    lblAccruals.Visible = True
    lblWeekEnd.Visible = True
    lblVacAccr.Visible = True
    lblYTDVacE.Visible = True
    lblYTDPerE.Visible = True
    lblSickAccr.Visible = True
    lblYTDSickE.Visible = True
    LineYTDEND.Visible = True
    lblYTDOT.Visible = True
    lblYTDOTAvg.Visible = True
    lblOT.Visible = True
    lblAVGOT.Visible = True
    Line17.Visible = True
    Line23.Visible = True
    Line24.Visible = True
Else
    lblYTDQualifier.Visible = True
    lblAccruals.Visible = False
    lblWeekEnd.Visible = False
    lblVacAccr.Visible = False
    lblYTDVacE.Visible = False
    lblYTDPerE.Visible = False
    lblSickAccr.Visible = False
    lblYTDSickE.Visible = False
    LineYTDEND.Visible = False
    lblYTDOT.Visible = False
    lblYTDOTAvg.Visible = False
    lblOT.Visible = False
    lblAVGOT.Visible = False
    Line17.Visible = False
    Line23.Visible = False
    Line24.Visible = False
End If
    

End Sub
