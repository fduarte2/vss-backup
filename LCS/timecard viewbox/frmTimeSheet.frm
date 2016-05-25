VERSION 5.00
Begin VB.Form frmTimeSheet 
   BackColor       =   &H00FFFFFF&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "TimeCard"
   ClientHeight    =   10170
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   15270
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   10170
   ScaleWidth      =   15270
   StartUpPosition =   3  'Windows Default
   Begin VB.TextBox txtTotLN 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFFF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   8640
      TabIndex        =   174
      Top             =   8640
      Visible         =   0   'False
      Width           =   495
   End
   Begin VB.TextBox txtGrandTotal 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
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
      Left            =   7680
      TabIndex        =   150
      Top             =   7560
      Width           =   495
   End
   Begin VB.TextBox txtTotReg 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
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
      Left            =   7680
      TabIndex        =   148
      Top             =   4200
      Width           =   495
   End
   Begin VB.TextBox txtTotST 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
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
      Left            =   7680
      TabIndex        =   147
      Top             =   6720
      Width           =   495
   End
   Begin VB.TextBox txtTotDT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
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
      Left            =   7680
      TabIndex        =   146
      Top             =   5880
      Width           =   495
   End
   Begin VB.TextBox txtTotOt 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
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
      Left            =   7680
      TabIndex        =   145
      Top             =   5040
      Width           =   495
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   13
      Left            =   14280
      TabIndex        =   168
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   12
      Left            =   13320
      TabIndex        =   167
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   11
      Left            =   12360
      TabIndex        =   166
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   10
      Left            =   11400
      TabIndex        =   165
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   9
      Left            =   10440
      TabIndex        =   164
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   8
      Left            =   9480
      TabIndex        =   163
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   7
      Left            =   8520
      TabIndex        =   162
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   6
      Left            =   6720
      TabIndex        =   161
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   5
      Left            =   5760
      TabIndex        =   160
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   4
      Left            =   4800
      TabIndex        =   159
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   3
      Left            =   3840
      TabIndex        =   158
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   2
      Left            =   2880
      TabIndex        =   157
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   1
      Left            =   1920
      TabIndex        =   156
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtLines 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Index           =   0
      Left            =   960
      TabIndex        =   155
      Top             =   9480
      Width           =   735
   End
   Begin VB.TextBox txtST1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   6
      Left            =   14280
      Locked          =   -1  'True
      TabIndex        =   130
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   5
      Left            =   13320
      Locked          =   -1  'True
      TabIndex        =   129
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   4
      Left            =   12360
      Locked          =   -1  'True
      TabIndex        =   128
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   3
      Left            =   11400
      Locked          =   -1  'True
      TabIndex        =   127
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   2
      Left            =   10440
      Locked          =   -1  'True
      TabIndex        =   126
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   1
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   125
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   0
      Left            =   8520
      Locked          =   -1  'True
      TabIndex        =   124
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   6
      Left            =   6720
      Locked          =   -1  'True
      TabIndex        =   123
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   5
      Left            =   5760
      Locked          =   -1  'True
      TabIndex        =   122
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   4
      Left            =   4800
      Locked          =   -1  'True
      TabIndex        =   121
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   3
      Left            =   3840
      Locked          =   -1  'True
      TabIndex        =   120
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   2
      Left            =   2880
      Locked          =   -1  'True
      TabIndex        =   119
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   1
      Left            =   1920
      Locked          =   -1  'True
      TabIndex        =   118
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtST 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   0
      Left            =   960
      Locked          =   -1  'True
      TabIndex        =   116
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtTotalHrs1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   14160
      Locked          =   -1  'True
      TabIndex        =   107
      Top             =   8160
      Width           =   975
   End
   Begin VB.TextBox txtReg1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   0
      Left            =   8520
      Locked          =   -1  'True
      TabIndex        =   99
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtOT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   0
      Left            =   8520
      Locked          =   -1  'True
      TabIndex        =   98
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtDT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   0
      Left            =   8520
      Locked          =   -1  'True
      TabIndex        =   97
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOut1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   0
      Left            =   8520
      Locked          =   -1  'True
      TabIndex        =   96
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtIN1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   0
      Left            =   8520
      Locked          =   -1  'True
      TabIndex        =   95
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtIN1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   1
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   94
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtOut1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   1
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   93
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtDT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   1
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   92
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   1
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   91
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtReg1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   1
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   90
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtIN1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   2
      Left            =   10440
      Locked          =   -1  'True
      TabIndex        =   89
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtOut1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   2
      Left            =   10440
      Locked          =   -1  'True
      TabIndex        =   88
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtDT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   2
      Left            =   10440
      Locked          =   -1  'True
      TabIndex        =   87
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   2
      Left            =   10440
      Locked          =   -1  'True
      TabIndex        =   86
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtReg1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   2
      Left            =   10440
      Locked          =   -1  'True
      TabIndex        =   85
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtIN1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   3
      Left            =   11400
      Locked          =   -1  'True
      TabIndex        =   84
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtOut1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   3
      Left            =   11400
      Locked          =   -1  'True
      TabIndex        =   83
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtDT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   3
      Left            =   11400
      Locked          =   -1  'True
      TabIndex        =   82
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   3
      Left            =   11400
      Locked          =   -1  'True
      TabIndex        =   81
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtReg1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   3
      Left            =   11400
      Locked          =   -1  'True
      TabIndex        =   80
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtIN1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   4
      Left            =   12360
      Locked          =   -1  'True
      TabIndex        =   79
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtOut1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   4
      Left            =   12360
      Locked          =   -1  'True
      TabIndex        =   78
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtDT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   4
      Left            =   12360
      Locked          =   -1  'True
      TabIndex        =   77
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   4
      Left            =   12360
      Locked          =   -1  'True
      TabIndex        =   76
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtReg1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   4
      Left            =   12360
      Locked          =   -1  'True
      TabIndex        =   75
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtReg1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   5
      Left            =   13320
      Locked          =   -1  'True
      TabIndex        =   74
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtOT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   5
      Left            =   13320
      Locked          =   -1  'True
      TabIndex        =   73
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtDT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   5
      Left            =   13320
      Locked          =   -1  'True
      TabIndex        =   72
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOut1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   5
      Left            =   13320
      Locked          =   -1  'True
      TabIndex        =   71
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtIN1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   5
      Left            =   13320
      Locked          =   -1  'True
      TabIndex        =   70
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtReg1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   6
      Left            =   14280
      Locked          =   -1  'True
      TabIndex        =   69
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtOT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   6
      Left            =   14280
      Locked          =   -1  'True
      TabIndex        =   68
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtDT1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   6
      Left            =   14280
      Locked          =   -1  'True
      TabIndex        =   67
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOut1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   6
      Left            =   14280
      Locked          =   -1  'True
      TabIndex        =   66
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtIN1 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   6
      Left            =   14280
      Locked          =   -1  'True
      TabIndex        =   65
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtDaily1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   0
      Left            =   8520
      Locked          =   -1  'True
      TabIndex        =   64
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   1
      Left            =   9480
      Locked          =   -1  'True
      TabIndex        =   63
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   2
      Left            =   10440
      Locked          =   -1  'True
      TabIndex        =   62
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   3
      Left            =   11400
      Locked          =   -1  'True
      TabIndex        =   61
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   4
      Left            =   12360
      Locked          =   -1  'True
      TabIndex        =   60
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   5
      Left            =   13320
      Locked          =   -1  'True
      TabIndex        =   59
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily1 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   6
      Left            =   14280
      Locked          =   -1  'True
      TabIndex        =   58
      Top             =   7560
      Width           =   735
   End
   Begin VB.Timer Timer1 
      Enabled         =   0   'False
      Interval        =   60000
      Left            =   0
      Top             =   8040
   End
   Begin VB.TextBox txtDaily 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   6
      Left            =   6720
      Locked          =   -1  'True
      TabIndex        =   56
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   5
      Left            =   5760
      Locked          =   -1  'True
      TabIndex        =   55
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   4
      Left            =   4800
      Locked          =   -1  'True
      TabIndex        =   54
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   3
      Left            =   3840
      Locked          =   -1  'True
      TabIndex        =   53
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   2
      Left            =   2880
      Locked          =   -1  'True
      TabIndex        =   52
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtDaily 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   1
      Left            =   1920
      Locked          =   -1  'True
      TabIndex        =   51
      Top             =   7560
      Width           =   735
   End
   Begin VB.TextBox txtIN 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   6
      Left            =   6720
      Locked          =   -1  'True
      TabIndex        =   48
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtOut 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   6
      Left            =   6720
      Locked          =   -1  'True
      TabIndex        =   47
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtDT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   6
      Left            =   6720
      Locked          =   -1  'True
      TabIndex        =   46
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   6
      Left            =   6720
      Locked          =   -1  'True
      TabIndex        =   45
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtIN 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   5
      Left            =   5760
      Locked          =   -1  'True
      TabIndex        =   43
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtOut 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   5
      Left            =   5760
      Locked          =   -1  'True
      TabIndex        =   42
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtDT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   5
      Left            =   5760
      Locked          =   -1  'True
      TabIndex        =   41
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   5
      Left            =   5760
      Locked          =   -1  'True
      TabIndex        =   40
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtReg 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   5
      Left            =   5760
      Locked          =   -1  'True
      TabIndex        =   39
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtTotalHrs 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   7200
      Locked          =   -1  'True
      TabIndex        =   37
      Top             =   8160
      Width           =   1455
   End
   Begin VB.TextBox txtReg 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   4
      Left            =   4800
      Locked          =   -1  'True
      TabIndex        =   36
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtOT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   4
      Left            =   4800
      Locked          =   -1  'True
      TabIndex        =   35
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtDT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   4
      Left            =   4800
      Locked          =   -1  'True
      TabIndex        =   34
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOut 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   4
      Left            =   4800
      Locked          =   -1  'True
      TabIndex        =   33
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtIN 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   4
      Left            =   4800
      Locked          =   -1  'True
      TabIndex        =   32
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtReg 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   3
      Left            =   3840
      Locked          =   -1  'True
      TabIndex        =   31
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtOT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   3
      Left            =   3840
      Locked          =   -1  'True
      TabIndex        =   30
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtDT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   3
      Left            =   3840
      Locked          =   -1  'True
      TabIndex        =   29
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOut 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   3
      Left            =   3840
      Locked          =   -1  'True
      TabIndex        =   28
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtIN 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   3
      Left            =   3840
      Locked          =   -1  'True
      TabIndex        =   27
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtReg 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   2
      Left            =   2880
      Locked          =   -1  'True
      TabIndex        =   26
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtOT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   2
      Left            =   2880
      Locked          =   -1  'True
      TabIndex        =   25
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtDT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   2
      Left            =   2880
      Locked          =   -1  'True
      TabIndex        =   24
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOut 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   2
      Left            =   2880
      Locked          =   -1  'True
      TabIndex        =   23
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtIN 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   2
      Left            =   2880
      Locked          =   -1  'True
      TabIndex        =   22
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtReg 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   1
      Left            =   1920
      Locked          =   -1  'True
      TabIndex        =   21
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtOT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   1
      Left            =   1920
      Locked          =   -1  'True
      TabIndex        =   20
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtDT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   1
      Left            =   1920
      Locked          =   -1  'True
      TabIndex        =   19
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOut 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   1
      Left            =   1920
      Locked          =   -1  'True
      TabIndex        =   18
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtIN 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   1
      Left            =   1920
      Locked          =   -1  'True
      TabIndex        =   17
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtIN 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   0
      Left            =   960
      Locked          =   -1  'True
      TabIndex        =   11
      Top             =   2760
      Width           =   735
   End
   Begin VB.TextBox txtOut 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   0
      Left            =   960
      Locked          =   -1  'True
      TabIndex        =   10
      Top             =   3360
      Width           =   735
   End
   Begin VB.TextBox txtDT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   0
      Left            =   960
      Locked          =   -1  'True
      TabIndex        =   9
      Top             =   5880
      Width           =   735
   End
   Begin VB.TextBox txtOT 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   0
      Left            =   960
      Locked          =   -1  'True
      TabIndex        =   8
      Top             =   5040
      Width           =   735
   End
   Begin VB.TextBox txtFirstName 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   4800
      Locked          =   -1  'True
      TabIndex        =   2
      Top             =   195
      Width           =   6255
   End
   Begin VB.TextBox txtEmpId 
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Left            =   1200
      Locked          =   -1  'True
      TabIndex        =   1
      Top             =   195
      Width           =   1935
   End
   Begin VB.TextBox txtReg 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   0
      Left            =   960
      Locked          =   -1  'True
      TabIndex        =   0
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtReg 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BorderStyle     =   0  'None
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
      Index           =   6
      Left            =   6720
      Locked          =   -1  'True
      TabIndex        =   149
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox txtDaily 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000040&
      Height          =   375
      Index           =   0
      Left            =   960
      Locked          =   -1  'True
      TabIndex        =   50
      Top             =   7560
      Width           =   735
   End
   Begin VB.PictureBox Picture1 
      BackColor       =   &H0080C0FF&
      BorderStyle     =   0  'None
      Height          =   735
      Left            =   0
      ScaleHeight     =   735
      ScaleWidth      =   7575
      TabIndex        =   153
      Top             =   7320
      Width           =   7575
      Begin VB.Line Line47 
         X1              =   7560
         X2              =   7560
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line26 
         X1              =   0
         X2              =   7560
         Y1              =   0
         Y2              =   0
      End
      Begin VB.Label Label12 
         Alignment       =   1  'Right Justify
         BackColor       =   &H0080C0FF&
         Caption         =   "Daily Total"
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
         TabIndex        =   154
         Top             =   120
         Width           =   690
      End
      Begin VB.Line Line25 
         X1              =   4680
         X2              =   4680
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line24 
         X1              =   3720
         X2              =   3720
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line23 
         X1              =   2760
         X2              =   2760
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line22 
         X1              =   1800
         X2              =   1800
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line21 
         X1              =   840
         X2              =   840
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line20 
         X1              =   0
         X2              =   0
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line19 
         X1              =   5640
         X2              =   5640
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line18 
         X1              =   6600
         X2              =   6600
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line17 
         X1              =   0
         X2              =   7560
         Y1              =   720
         Y2              =   720
      End
      Begin VB.Line Line16 
         X1              =   0
         X2              =   7560
         Y1              =   720
         Y2              =   720
      End
   End
   Begin VB.PictureBox Picture4 
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      ForeColor       =   &H80000008&
      Height          =   735
      Left            =   8400
      ScaleHeight     =   705
      ScaleWidth      =   6705
      TabIndex        =   169
      Top             =   9240
      Width           =   6735
      Begin VB.Line Line40 
         X1              =   5750
         X2              =   5750
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line39 
         X1              =   4790
         X2              =   4790
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line38 
         X1              =   3830
         X2              =   3830
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line37 
         X1              =   2870
         X2              =   2870
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line36 
         X1              =   1910
         X2              =   1910
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line35 
         X1              =   950
         X2              =   950
         Y1              =   0
         Y2              =   720
      End
   End
   Begin VB.PictureBox Picture2 
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      ForeColor       =   &H80000008&
      Height          =   6375
      Left            =   7560
      ScaleHeight     =   6345
      ScaleWidth      =   705
      TabIndex        =   151
      Top             =   1680
      Width           =   735
      Begin VB.Line Line27 
         X1              =   0
         X2              =   720
         Y1              =   7080
         Y2              =   7080
      End
      Begin VB.Line Line15 
         X1              =   840
         X2              =   840
         Y1              =   0
         Y2              =   6360
      End
      Begin VB.Line Line13 
         X1              =   0
         X2              =   840
         Y1              =   5630
         Y2              =   5630
      End
      Begin VB.Line Line9 
         X1              =   0
         X2              =   840
         Y1              =   2150
         Y2              =   2150
      End
      Begin VB.Line Line7 
         X1              =   0
         X2              =   840
         Y1              =   830
         Y2              =   830
      End
      Begin VB.Label Label5 
         BackColor       =   &H0080C0FF&
         Caption         =   "Total"
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
         Index           =   5
         Left            =   120
         TabIndex        =   152
         Top             =   1080
         Width           =   570
      End
      Begin VB.Line Line6 
         X1              =   720
         X2              =   720
         Y1              =   0
         Y2              =   7080
      End
   End
   Begin VB.PictureBox Picture3 
      Appearance      =   0  'Flat
      BackColor       =   &H008080FF&
      ForeColor       =   &H00000000&
      Height          =   735
      Left            =   0
      ScaleHeight     =   705
      ScaleWidth      =   7545
      TabIndex        =   171
      Top             =   9240
      Width           =   7575
      Begin VB.Line Line12 
         X1              =   825
         X2              =   825
         Y1              =   865
         Y2              =   0
      End
      Begin VB.Label Label13 
         Alignment       =   2  'Center
         BackColor       =   &H008080FF&
         Caption         =   "LINES"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   -1  'True
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   120
         TabIndex        =   172
         Top             =   240
         Width           =   735
      End
      Begin VB.Line Line29 
         X1              =   2750
         X2              =   2750
         Y1              =   0
         Y2              =   840
      End
      Begin VB.Line Line28 
         X1              =   1790
         X2              =   1790
         Y1              =   0
         Y2              =   840
      End
      Begin VB.Line Line30 
         X1              =   3710
         X2              =   3710
         Y1              =   0
         Y2              =   840
      End
      Begin VB.Line Line31 
         X1              =   4670
         X2              =   4670
         Y1              =   0
         Y2              =   840
      End
      Begin VB.Line Line32 
         X1              =   5630
         X2              =   5630
         Y1              =   0
         Y2              =   840
      End
      Begin VB.Line Line33 
         X1              =   6590
         X2              =   6590
         Y1              =   0
         Y2              =   840
      End
      Begin VB.Line Line34 
         X1              =   7550
         X2              =   7550
         Y1              =   0
         Y2              =   840
      End
   End
   Begin VB.PictureBox Picture6 
      Appearance      =   0  'Flat
      BackColor       =   &H0080C0FF&
      ForeColor       =   &H80000008&
      Height          =   735
      Left            =   8400
      ScaleHeight     =   705
      ScaleWidth      =   6705
      TabIndex        =   170
      Top             =   7320
      Width           =   6735
      Begin VB.Line Line46 
         X1              =   5750
         X2              =   5750
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line45 
         X1              =   4790
         X2              =   4790
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line44 
         X1              =   3830
         X2              =   3830
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line43 
         X1              =   2870
         X2              =   2870
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line42 
         X1              =   1910
         X2              =   1910
         Y1              =   0
         Y2              =   720
      End
      Begin VB.Line Line41 
         X1              =   950
         X2              =   950
         Y1              =   0
         Y2              =   720
      End
   End
   Begin VB.Label Label16 
      BackColor       =   &H00FFFFFF&
      Caption         =   "the following hours for Lines:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   2640
      TabIndex        =   176
      Top             =   8760
      Width           =   3615
   End
   Begin VB.Label Label15 
      BackColor       =   &H00FFFFFF&
      Caption         =   "include"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   -1  'True
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   1800
      TabIndex        =   175
      Top             =   8760
      Width           =   975
   End
   Begin VB.Label Label11 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "Weekly Total :   "
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   0
      Left            =   4080
      TabIndex        =   38
      Top             =   8160
      Width           =   3330
   End
   Begin VB.Label Label14 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      Caption         =   "The above hours  "
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H80000008&
      Height          =   375
      Left            =   0
      TabIndex        =   173
      Top             =   8760
      Width           =   1935
   End
   Begin VB.Line Line49 
      X1              =   7560
      X2              =   8280
      Y1              =   8040
      Y2              =   8040
   End
   Begin VB.Line Line48 
      X1              =   7560
      X2              =   8280
      Y1              =   8040
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   1
      X1              =   1800
      X2              =   1800
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line8 
      X1              =   0
      X2              =   7560
      Y1              =   8040
      Y2              =   8040
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   13
      Left            =   960
      TabIndex        =   144
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   12
      Left            =   2040
      TabIndex        =   143
      Top             =   2160
      Width           =   570
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   11
      Left            =   2880
      TabIndex        =   142
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   10
      Left            =   3840
      TabIndex        =   141
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   9
      Left            =   4800
      TabIndex        =   140
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   8
      Left            =   5760
      TabIndex        =   139
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   7
      Left            =   6720
      TabIndex        =   138
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   6
      Left            =   14280
      TabIndex        =   137
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   5
      Left            =   13320
      TabIndex        =   136
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   4
      Left            =   12360
      TabIndex        =   135
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   3
      Left            =   11400
      TabIndex        =   134
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   2
      Left            =   10440
      TabIndex        =   133
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   1
      Left            =   9480
      TabIndex        =   132
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Height          =   255
      Index           =   0
      Left            =   8520
      TabIndex        =   131
      Top             =   2160
      Width           =   690
   End
   Begin VB.Label Label6 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "ST"
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
      Left            =   120
      TabIndex        =   117
      Top             =   6720
      Width           =   570
   End
   Begin VB.Label Label5 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "CURRENT WEEK"
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
      Index           =   3
      Left            =   8400
      TabIndex        =   115
      Top             =   1320
      Width           =   6690
   End
   Begin VB.Label Label5 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "LAST WEEK"
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
      Index           =   2
      Left            =   120
      TabIndex        =   114
      Top             =   1320
      Width           =   7410
   End
   Begin VB.Label Label5 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Pay Period Ending:"
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
      TabIndex        =   113
      Top             =   840
      Width           =   2010
   End
   Begin VB.Line Line5 
      Index           =   4
      X1              =   8400
      X2              =   15120
      Y1              =   8040
      Y2              =   8040
   End
   Begin VB.Line Line5 
      Index           =   3
      X1              =   8400
      X2              =   15120
      Y1              =   3840
      Y2              =   3840
   End
   Begin VB.Line Line5 
      Index           =   2
      X1              =   8400
      X2              =   15120
      Y1              =   2520
      Y2              =   2520
   End
   Begin VB.Line Line5 
      Index           =   1
      X1              =   8400
      X2              =   15120
      Y1              =   1680
      Y2              =   1680
   End
   Begin VB.Label Week 
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
      Index           =   1
      Left            =   10680
      TabIndex        =   112
      Top             =   840
      Width           =   2610
   End
   Begin VB.Label Week 
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
      Index           =   0
      Left            =   2280
      TabIndex        =   111
      Top             =   840
      Width           =   1290
   End
   Begin VB.Label Label7 
      BackColor       =   &H00FFFFFF&
      Caption         =   "OUT"
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
      Left            =   120
      TabIndex        =   110
      Top             =   3360
      Width           =   570
   End
   Begin VB.Label Label9 
      BackColor       =   &H00FFFFFF&
      Caption         =   "IN"
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
      TabIndex        =   109
      Top             =   2760
      Width           =   450
   End
   Begin VB.Label Label11 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "Weekly Total :"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   1
      Left            =   11400
      TabIndex        =   108
      Top             =   8160
      Width           =   2730
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   16
      X1              =   8400
      X2              =   8400
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "MON"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   13
      Left            =   8520
      TabIndex        =   106
      Top             =   1800
      Width           =   690
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "TUE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   12
      Left            =   9480
      TabIndex        =   105
      Top             =   1800
      Width           =   690
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "WED"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   11
      Left            =   10440
      TabIndex        =   104
      Top             =   1800
      Width           =   690
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "THU"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   10
      Left            =   11400
      TabIndex        =   103
      Top             =   1800
      Width           =   690
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "FRI"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   9
      Left            =   12360
      TabIndex        =   102
      Top             =   1800
      Width           =   690
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "SAT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   8
      Left            =   13320
      TabIndex        =   101
      Top             =   1800
      Width           =   690
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "SUN"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   7
      Left            =   14280
      TabIndex        =   100
      Top             =   1800
      Width           =   690
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   15
      X1              =   9360
      X2              =   9360
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   14
      X1              =   10320
      X2              =   10320
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   13
      X1              =   11280
      X2              =   11280
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   12
      X1              =   12240
      X2              =   12240
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   11
      X1              =   13200
      X2              =   13200
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   10
      X1              =   14160
      X2              =   14160
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   9
      X1              =   15120
      X2              =   15120
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Label Label5 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Pay Period Ending:"
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
      Left            =   8520
      TabIndex        =   57
      Top             =   840
      Width           =   2010
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "SUN"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   6
      Left            =   6720
      TabIndex        =   49
      Top             =   1800
      Width           =   690
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   8
      X1              =   7560
      X2              =   7560
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "SAT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   5
      Left            =   5760
      TabIndex        =   44
      Top             =   1800
      Width           =   690
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   7
      X1              =   6600
      X2              =   6600
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   6
      X1              =   5640
      X2              =   5640
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   5
      X1              =   4680
      X2              =   4680
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   4
      X1              =   3720
      X2              =   3720
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   3
      X1              =   2760
      X2              =   2760
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   2
      X1              =   0
      X2              =   0
      Y1              =   1680
      Y2              =   8040
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "FRI"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   4
      Left            =   4800
      TabIndex        =   16
      Top             =   1800
      Width           =   690
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "THU"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   3
      Left            =   3840
      TabIndex        =   15
      Top             =   1800
      Width           =   690
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "WED"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   2
      Left            =   2880
      TabIndex        =   14
      Top             =   1800
      Width           =   690
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "TUE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   1
      Left            =   2040
      TabIndex        =   13
      Top             =   1800
      Width           =   570
   End
   Begin VB.Label Label10 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "MON"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Index           =   0
      Left            =   960
      TabIndex        =   12
      Top             =   1800
      Width           =   690
   End
   Begin VB.Line Line5 
      Index           =   0
      X1              =   0
      X2              =   7560
      Y1              =   1680
      Y2              =   1680
   End
   Begin VB.Line Line4 
      X1              =   0
      X2              =   7560
      Y1              =   2520
      Y2              =   2520
   End
   Begin VB.Line Line3 
      Index           =   0
      X1              =   0
      X2              =   7560
      Y1              =   3840
      Y2              =   3840
   End
   Begin VB.Label Label3 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "DT"
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
      Left            =   120
      TabIndex        =   7
      Top             =   5880
      Width           =   570
   End
   Begin VB.Label Label2 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "OT"
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
      Left            =   120
      TabIndex        =   6
      Top             =   5040
      Width           =   570
   End
   Begin VB.Label Label1 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFFF&
      Caption         =   "REG"
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
      Left            =   120
      TabIndex        =   5
      Top             =   4200
      Width           =   570
   End
   Begin VB.Label Label8 
      BackColor       =   &H00FFFFFF&
      Caption         =   "NAME :"
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
      Left            =   3840
      TabIndex        =   4
      Top             =   195
      Width           =   810
   End
   Begin VB.Label Label4 
      BackColor       =   &H00FFFFFF&
      Caption         =   "No."
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
      Left            =   120
      TabIndex        =   3
      Top             =   195
      Width           =   450
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   15240
      Y1              =   720
      Y2              =   720
   End
   Begin VB.Line Line1 
      BorderColor     =   &H00404040&
      Index           =   0
      X1              =   840
      X2              =   840
      Y1              =   1680
      Y2              =   8040
   End
End
Attribute VB_Name = "frmTimeSheet"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub Form_Load()
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  If (Trim$(Command$) = "-print") Then
    Timer1.Interval = 5000
  Else
    Timer1.Interval = 30000
  End If
  Timer1.Enabled = True

  TimeRecording
End Sub

Private Sub TimeRecording()
  'On Error Resume Next
  Dim dsHOURLY_DETAIL_OT As Object, dsHOURLY_DETAIL_OT_A As Object, dsHOURLY_DETAIL_REG As Object, Cntr As Integer
  Dim dsHOURLY_DETAIL_DT As Object, dsHOURLY_DETAIL_ST As Object, TotalHrs As Single, TotalDaily As Single
  Dim gsSqlStmt As String, dsEMPLOYEE As Object, CurrDay As Date, StartWeek As Date, EndWeek As Date
  Dim dsDAILY_HIRE_LIST As Object, dsDAILY_HIRE_PREV As Object, WeekNo As Integer, indxCtr As Integer
  Dim dsHOURLY_DETAIL_OTHRS As Object
  Dim dsHOURLY_DETAIL_REG2 As Object, dsHOURLY_DETAIL_OT_A2 As Object
  Dim iOt1 As Double
  Dim iOt2 As Double
  Dim iOtT As Double
  Dim dsHOURLY_DETAIL_LINES As Object
  Dim strYear As String
  Dim sqlStmt As String
  Dim dayRS As Object
  Dim start_date As String, end_date As String
  
  TotalHrs = 0
  iOt1 = 0
  iOt2 = 0
  iOtT = 0
  
  
  'Get Employee Name
  gsSqlStmt = "SELECT * FROM EMPLOYEE WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "'"
  Set dsEMPLOYEE = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
  If Trim(dsEMPLOYEE.Fields("Employee_Name").Value) = vbNullString Or IsNull(dsEMPLOYEE.Fields("Employee_Name").Value) Then
    Exit Sub    'No Employee in Employee Table with this Employee ID
  End If
  txtFirstName = UCase(dsEMPLOYEE.Fields("Employee_Name").Value)
  txtEmpId = EmpID
  
  'Current Week Data
  CurrDay = Format(Now, "w")
  strYear = Format(Now, "yyyy")
  If strYear = "2001" Then
    If CurrDay = 1 Then
      StartWeek = Format(Str(Date - 6), "mm/dd/yyyy")
      EndWeek = Format(Str(Date), "mm/dd/yyyy")
      WeekNo = Format(Now, "ww") - 1
    ElseIf CurrDay = 7 Then
      StartWeek = Format(Str(Date - 5), "mm/dd/yyyy")
      EndWeek = Format(Str(Date + 1), "mm/dd/yyyy")
      WeekNo = Format(Now, "ww")
    Else
      StartWeek = Format(Str(Date - Format(Date, "w") + 2), "mm/dd/yyyy")
      EndWeek = Format(Str(Date + 7 - Format(Date, "w") + 1), "mm/dd/yyyy")
      WeekNo = Format(Now, "ww")
    End If
    'WeekNo = Format(Now, "ww")
  Else
    If CurrDay = 1 Then
      StartWeek = Format(Str(Date - 6), "mm/dd/yyyy")
      EndWeek = Format(Str(Date), "mm/dd/yyyy")
      WeekNo = Format(Now, "ww") - 2
    ElseIf CurrDay = 7 Then
      StartWeek = Format(Str(Date - 5), "mm/dd/yyyy")
      EndWeek = Format(Str(Date + 1), "mm/dd/yyyy")
      WeekNo = Format(Now, "ww") - 1
    Else
      StartWeek = Format(Str(Date - Format(Date, "w") + 2), "mm/dd/yyyy")
      EndWeek = Format(Str(Date + 7 - Format(Date, "w") + 1), "mm/dd/yyyy")
      WeekNo = Format(Now, "ww") - 1
    End If
  End If
  
  If WeekNo = 0 Then
    WeekNo = 1
  End If
  
  'get first day(Monday) and last day (Sunday)of week from oracle
  sqlStmt = "SELECT TRUNC(sysdate, 'IW') START_DATE, TRUNC(sysdate, 'IW')+6 END_DATE FROM DUAL"
  Set dayRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
  If Not dayRS.EOF Then
      StartWeek = Format(dayRS.Fields("start_date").Value, "mm/dd/yyyy")
      EndWeek = Format(dayRS.Fields("end_date").Value, "mm/dd/yyyy")
      start_date = Format(StartWeek, "mm/dd/yyyy")
      end_date = Format(EndWeek, "mm/dd/yyyy")
  End If
  
  
  Week(1).Caption = Str(EndWeek)
  Week(0).Caption = Str(StartWeek - 1)
  'Label20(0).Caption = LTrim(Str(StartWeek))
  Label20(0).Caption = Mid$(CDate(Str(StartWeek)), 1, 3) & Mid$(CDate(Str(StartWeek)), 4, 2)
  For indxCtr = 1 To 6
    Label20(indxCtr).Caption = Mid$(CDate(Str(StartWeek + indxCtr)), 1, 3) & Mid$(CDate(Str(StartWeek + indxCtr)), 4, 2)
  Next
  For indxCtr = 7 To 13
    Label20(indxCtr).Caption = Mid$(CDate(Str(StartWeek - (indxCtr - 6))), 1, 3) & Mid$(CDate(Str(StartWeek - (indxCtr - 6))), 4, 2)
  Next
  
  'Fill IN and OUT time from Daily Hire
  gsSqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND HIRE_DATE>=TO_DATE('" + start_date + "', 'mm/dd/yyyy') AND HIRE_DATE<=TO_DATE('" + end_date + "', 'mm/dd/yyyy')"
  Set dsDAILY_HIRE_LIST = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
  If dsDAILY_HIRE_LIST.EOF And dsDAILY_HIRE_LIST.BOF Then
  'No data for the current week
  Else
    dsDAILY_HIRE_LIST.MoveFirst
    Do While Not dsDAILY_HIRE_LIST.EOF
      iOt1 = 0
      iOt2 = 0
      iOtT = 0
      'Fill Hours for REG, OT, DT from Hourly Detail
      gsSqlStmt = "SELECT SUM(REG_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND Upper(EARNING_TYPE_ID) = 'REG' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_REG = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      
      ' Added 1/19/2000 by Bruce LeBrun - Some records have Reg_hrs = Null,
      ' in this case take the duration and we will add it to the Reg_hrs SUM
      ' further down in the logic
      gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE REG_HRS IS NULL AND EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND Upper(EARNING_TYPE_ID) = 'REG' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_REG2 = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      
      gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND (Upper(EARNING_TYPE_ID) = 'OT' or Upper(EARNING_TYPE_ID) = 'HOL-OT') AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_OT = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      ' Fix 2/29/2000 ST overtime calculation doubling number of hours for a given day
      gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EARNING_TYPE_ID <> 'ST' AND EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_OT_A = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      
      gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND Upper(EARNING_TYPE_ID) = 'DT' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_DT = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND Upper(EARNING_TYPE_ID) = 'ST' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_ST = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      
      ' Added 4/10/2000 by Bruce LeBrun  - Now show the hours for lines.
      gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND REMARK = 'Line Runners' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_LINES = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      

      Select Case UCase(Format(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value, "ddd"))
      Case "MON"
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_In").Value) = vbNullString Then
          txtIN1(0) = ""
        Else
          txtIN1(0) = Format(dsDAILY_HIRE_LIST.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) = vbNullString Then
          txtOut1(0) = ""
        Else
          txtOut1(0) = Format(dsDAILY_HIRE_LIST.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg1(0) = ""
        Else
          txtReg1(0) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT1(0) = ""
        '  Else
        '    txtOT1(0) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT1(0) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT1(0) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
        
        'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT1(0) = ""
        Else
            txtOT1(0) = Trim$(iOtT)
        End If
        
        
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT1(0) = ""
        Else
          txtDT1(0) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST1(0) = ""
        Else
          txtST1(0) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(7) = ""
        Else
            txtLines(7) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If

        
      Case "TUE"
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_In").Value) = vbNullString Then
          txtIN1(1) = ""
        Else
          txtIN1(1) = Format(dsDAILY_HIRE_LIST.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) = vbNullString Then
          txtOut1(1) = ""
        Else
          txtOut1(1) = Format(dsDAILY_HIRE_LIST.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg1(1) = ""
        Else
          txtReg1(1) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT1(1) = ""
        '  Else
        '    txtOT1(1) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT1(1) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT1(1) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
        'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT1(1) = ""
        Else
            txtOT1(1) = Trim$(iOtT)
        End If
        
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT1(1) = ""
        Else
          txtDT1(1) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST1(1) = ""
        Else
          txtST1(1) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(8) = ""
        Else
            txtLines(8) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If

      Case "WED"
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_In").Value) = vbNullString Then
          txtIN1(2) = ""
        Else
          txtIN1(2) = Format(dsDAILY_HIRE_LIST.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) = vbNullString Then
          txtOut1(2) = ""
        Else
          txtOut1(2) = Format(dsDAILY_HIRE_LIST.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg1(2) = ""
        Else
          txtReg1(2) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT1(2) = ""
        '  Else
        '    txtOT1(2) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT1(2) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT1(2) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
        
        'add on 1/13/2000
        
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT1(2) = ""
        Else
            txtOT1(2) = Trim$(iOtT)
        End If
        
        
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT1(2) = ""
        Else
          txtDT1(2) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST1(2) = ""
        Else
          txtST1(2) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(9) = ""
        Else
            txtLines(9) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If

      Case "THU"
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_In").Value) = vbNullString Then
          txtIN1(3) = ""
        Else
          txtIN1(3) = Format(dsDAILY_HIRE_LIST.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) = vbNullString Then
          txtOut1(3) = ""
        Else
          txtOut1(3) = Format(dsDAILY_HIRE_LIST.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg1(3) = ""
        Else
          txtReg1(3) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT1(3) = ""
        '  Else
        '    txtOT1(3) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
         ' gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
         ' Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
         ' If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
         '   txtOT1(3) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
         ' Else
         '   txtOT1(3) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
         ' End If
        'End If
        'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT1(3) = ""
        Else
            txtOT1(3) = Trim$(iOtT)
        End If
        
        
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT1(3) = ""
        Else
          txtDT1(3) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST1(3) = ""
        Else
          txtST1(3) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(10) = ""
        Else
            txtLines(10) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If


      Case "FRI"
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_In").Value) = vbNullString Then
          txtIN1(4) = ""
        Else
          txtIN1(4) = Format(dsDAILY_HIRE_LIST.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) = vbNullString Then
          txtOut1(4) = ""
        Else
          txtOut1(4) = Format(dsDAILY_HIRE_LIST.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg1(4) = ""
        Else
          txtReg1(4) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT1(4) = ""
        '  Else
        '    txtOT1(4) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT1(4) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT1(4) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
        'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT1(4) = ""
        Else
            txtOT1(4) = Trim$(iOtT)
        End If
        
        
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT1(4) = ""
        Else
          txtDT1(4) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST1(4) = ""
        Else
          txtST1(4) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(11) = ""
        Else
            txtLines(11) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If

      
      Case "SAT"
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_In").Value) = vbNullString Then
          txtIN1(5) = ""
        Else
          txtIN1(5) = Format(dsDAILY_HIRE_LIST.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) = vbNullString Then
          txtOut1(5) = ""
        Else
          txtOut1(5) = Format(dsDAILY_HIRE_LIST.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg1(5) = ""
        Else
          txtReg1(5) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT1(5) = ""
        '  Else
        '    txtOT1(5) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        ''  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT1(5) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT1(5) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
        
        'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT1(5) = ""
        Else
            txtOT1(5) = Trim$(iOtT)
        End If
        
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT1(5) = ""
        Else
          txtDT1(5) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST1(5) = ""
        Else
          txtST1(5) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(12) = ""
        Else
            txtLines(12) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If
      
      Case "SUN"
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_In").Value) = vbNullString Then
          txtIN1(6) = ""
        Else
          txtIN1(6) = Format(dsDAILY_HIRE_LIST.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.Fields("Time_Out").Value) = vbNullString Then
          txtOut1(6) = ""
        Else
          txtOut1(6) = Format(dsDAILY_HIRE_LIST.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg1(6) = ""
        Else
          txtReg1(6) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT1(6) = ""
        '  Else
        '    txtOT1(6) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT1(6) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT1(6) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
        'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT1(6) = ""
        Else
            txtOT1(6) = Trim$(iOtT)
        End If
        
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT1(6) = ""
        Else
          txtDT1(6) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST1(6) = ""
        Else
          txtST1(6) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(13) = ""
        Else
            txtLines(13) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If
      
      End Select
      dsDAILY_HIRE_LIST.MoveNext
    Loop
    
    For Cntr = 0 To 6
      If Trim(txtReg1(Cntr)) <> "" Then TotalHrs = TotalHrs + CSng(txtReg1(Cntr))
      If Trim(txtOT1(Cntr)) <> "" Then TotalHrs = TotalHrs + CSng(txtOT1(Cntr))
      If Trim(txtDT1(Cntr)) <> "" Then TotalHrs = TotalHrs + CSng(txtDT1(Cntr))
      If Trim(txtST1(Cntr)) <> "" Then TotalHrs = TotalHrs + CSng(txtST1(Cntr))
    Next
    For Cntr = 0 To 6
      If Trim(txtReg1(Cntr)) <> "" Then TotalDaily = CSng(txtReg1(Cntr))
      If Trim(txtOT1(Cntr)) <> "" Then TotalDaily = TotalDaily + CSng(txtOT1(Cntr))
      If Trim(txtDT1(Cntr)) <> "" Then TotalDaily = TotalDaily + CSng(txtDT1(Cntr))
      If Trim(txtST1(Cntr)) <> "" Then TotalDaily = TotalDaily + CSng(txtST1(Cntr))
      If TotalDaily = 0 Then
        txtDaily1(Cntr) = ""
      Else
        txtDaily1(Cntr) = Trim(Str(TotalDaily))
      End If
      TotalDaily = 0
    Next
  End If
  txtTotalHrs1 = Trim(Str(TotalHrs))
  
'Previous Week Data
  TotalHrs = 0
  TotalDaily = 0
  
  gsSqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND HIRE_DATE>=TO_DATE('" + start_date + "', 'mm/dd/yyyy')-7 AND HIRE_DATE<=TO_DATE('" + start_date + "', 'mm/dd/yyyy')-1"
  
  'If strYear <> "2001" Then
  ' If WeekNo = 1 Then
  '    gsSqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND TO_CHAR(TRUNC(HIRE_DATE),'IW') = " + Str(52) + " and HIRE_DATE >= TO_DATE('01/01/" & Val(strYear) - 1 & "','mm/dd/yyyy')"
  '  Else
  '    gsSqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND TO_CHAR(TRUNC(HIRE_DATE),'IW') = " + Str(WeekNo - 1) + " and HIRE_DATE >= TO_DATE('01/01/" & strYear & "','mm/dd/yyyy')"
  '  End If
  'Else
  ' gsSqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND TO_CHAR(TRUNC(HIRE_DATE),'IW') = " + Str(WeekNo - 1) + " and HIRE_DATE >= TO_DATE('01/01/" & strYear & "','mm/dd/yyyy')"
  'End If
  
  Set dsDAILY_HIRE_PREV = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
  If dsDAILY_HIRE_PREV.EOF And dsDAILY_HIRE_PREV.BOF Then
    'No data for the Previous week
  Else
    dsDAILY_HIRE_PREV.MoveFirst
    Do While Not dsDAILY_HIRE_PREV.EOF
      iOt1 = 0
      iOt2 = 0
      iOtT = 0
      'Fill Hours for REG, OT, DT from Hourly Detail
      gsSqlStmt = "SELECT SUM(REG_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND Upper(EARNING_TYPE_ID) = 'REG' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_PREV.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_REG = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      
      ' Added 1/19/2000 by Bruce LeBrun - Some records have Reg_hrs = Null,
      ' in this case take the duration and we will add it to the Reg_hrs SUM
      ' further down in the logic
      gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE REG_HRS IS NULL AND EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND Upper(EARNING_TYPE_ID) = 'REG' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_PREV.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_REG2 = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
     
      gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND (Upper(EARNING_TYPE_ID) = 'OT' or Upper(EARNING_TYPE_ID) = 'HOL-OT') AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_PREV.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_OT = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      'add on 1/13/2000
      gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EARNING_TYPE_ID <> 'ST' AND EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_PREV.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_OT_A = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND Upper(EARNING_TYPE_ID) = 'DT' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_PREV.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_DT = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND Upper(EARNING_TYPE_ID) = 'ST' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_PREV.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_ST = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      
      ' Added 4/10/2000 by Bruce LeBrun  - Now show the hours for lines.
      gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" + Trim$(EmpID) + "' AND REMARK = 'Line Runners' AND HIRE_DATE = to_date('" + Format$(dsDAILY_HIRE_PREV.Fields("Hire_Date").Value, "mm/dd/yyyy") + "','mm/dd/yyyy')"
      Set dsHOURLY_DETAIL_LINES = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)


      Select Case UCase(Format(dsDAILY_HIRE_PREV.Fields("Hire_Date").Value, "ddd"))
      Case "MON"
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_In").Value) = vbNullString Then
          txtIN(0) = ""
        Else
          txtIN(0) = Format(dsDAILY_HIRE_PREV.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) = vbNullString Then
          txtOut(0) = ""
        Else
          txtOut(0) = Format(dsDAILY_HIRE_PREV.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg(0) = ""
        Else
          txtReg(0) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT(0) = ""
        '  Else
        '    txtOT(0) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        ''  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT(0) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT(0) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
          'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT(0) = ""
        Else
            txtOT(0) = Trim$(iOtT)
        End If
      
        
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT(0) = ""
        Else
          txtDT(0) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST(0) = ""
        Else
          txtST(0) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(0) = ""
        Else
            txtLines(0) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If

        
      Case "TUE"
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_In").Value) = vbNullString Then
          txtIN(1) = ""
        Else
          txtIN(1) = Format(dsDAILY_HIRE_PREV.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) = vbNullString Then
          txtOut(1) = ""
        Else
          txtOut(1) = Format(dsDAILY_HIRE_PREV.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg(1) = ""
        Else
          txtReg(1) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT(1) = ""
        '  Else
        '    txtOT(1) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT(1) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT(1) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
        
          'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT(1) = ""
        Else
            txtOT(1) = Trim$(iOtT)
        End If
      
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT(1) = ""
        Else
          txtDT(1) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST(1) = ""
        Else
          txtST(1) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(1) = ""
        Else
            txtLines(1) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If


      Case "WED"
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_In").Value) = vbNullString Then
          txtIN(2) = ""
        Else
          txtIN(2) = Format(dsDAILY_HIRE_PREV.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) = vbNullString Then
          txtOut(2) = ""
        Else
          txtOut(2) = Format(dsDAILY_HIRE_PREV.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg(2) = ""
        Else
          txtReg(2) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT(2) = ""
        '  Else
        '    txtOT(2) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT(2) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT(2) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
          'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT(2) = ""
        Else
            txtOT(2) = Trim$(iOtT)
        End If
      
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT(2) = ""
        Else
          txtDT(2) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST(2) = ""
        Else
          txtST(2) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(2) = ""
        Else
            txtLines(2) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If
      
      Case "THU"
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_In").Value) = vbNullString Then
          txtIN(3) = ""
        Else
          txtIN(3) = Format(dsDAILY_HIRE_PREV.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) = vbNullString Then
          txtOut(3) = ""
        Else
          txtOut(3) = Format(dsDAILY_HIRE_PREV.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg(3) = ""
        Else
          txtReg(3) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT(3) = ""
        '  Else
        '    txtOT(3) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT(3) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT(3) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
          'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT(3) = ""
        Else
            txtOT(3) = Trim$(iOtT)
        End If
      
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT(3) = ""
        Else
          txtDT(3) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST(3) = ""
        Else
          txtST(3) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(3) = ""
        Else
            txtLines(3) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If


      Case "FRI"
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_In").Value) = vbNullString Then
          txtIN(4) = ""
        Else
          txtIN(4) = Format(dsDAILY_HIRE_PREV.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) = vbNullString Then
          txtOut(4) = ""
        Else
          txtOut(4) = Format(dsDAILY_HIRE_PREV.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg(4) = ""
        Else
          txtReg(4) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT(4) = ""
        '  Else
        '    txtOT(4) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT(4) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT(4) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
          'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT(4) = ""
        Else
            txtOT(4) = Trim$(iOtT)
        End If
      
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT(4) = ""
        Else
          txtDT(4) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST(4) = ""
        Else
          txtST(4) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(4) = ""
        Else
            txtLines(4) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If
      
      Case "SAT"
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_In").Value) = vbNullString Then
          txtIN(5) = ""
        Else
          txtIN(5) = Format(dsDAILY_HIRE_PREV.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) = vbNullString Then
          txtOut(5) = ""
        Else
          txtOut(5) = Format(dsDAILY_HIRE_PREV.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg(5) = ""
        Else
          txtReg(5) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT(5) = ""
        '  Else
        '    txtOT(5) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        '  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT(5) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT(5) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
          'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT(5) = ""
        Else
            txtOT(5) = Trim$(iOtT)
        End If
      
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT(5) = ""
        Else
          txtDT(5) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST(5) = ""
        Else
          txtST(5) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
                
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(5) = ""
        Else
            txtLines(5) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If
      
      Case "SUN"
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_In").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_In").Value) = vbNullString Then
          txtIN(6) = ""
        Else
          txtIN(6) = Format(dsDAILY_HIRE_PREV.Fields("Time_In").Value, "hh:nnAM/PM")
        End If
        If IsNull(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_PREV.Fields("Time_Out").Value) = vbNullString Then
          txtOut(6) = ""
        Else
          txtOut(6) = Format(dsDAILY_HIRE_PREV.Fields("Time_Out").Value, "hh:nnAM/PM")
        End If
        If (IsNull(dsHOURLY_DETAIL_REG.Fields("Sum").Value) And IsNull(dsHOURLY_DETAIL_REG2.Fields("Sum").Value)) Or Trim(dsHOURLY_DETAIL_REG.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_REG.Fields("SUM").Value = 0 Then
          txtReg(6) = ""
        Else
          txtReg(6) = Val(GetValue(dsHOURLY_DETAIL_REG.Fields("SUM").Value, 0)) + Val(GetValue(dsHOURLY_DETAIL_REG2.Fields("SUM").Value, 0))
        End If
        'If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value = 0 Then
        '    txtOT(6) = ""
        '  Else
        '    txtOT(6) = dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value
        ''  End If
        'Else
        '  gsSqlStmt = "SELECT SUM(OT_HRS) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(EmpID) & "' AND HIRE_DATE = to_date('" + Format(Str(dsDAILY_HIRE_LIST.Fields("Hire_Date").Value), "mm/dd/yyyy") + "','mm/dd/yyyy')"
        '  Set dsHOURLY_DETAIL_OTHRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        '  If IsNull(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OTHRS.Fields("Sum").Value) = vbNullString Then
        '    txtOT(6) = dsHOURLY_DETAIL_OT.Fields("SUM").Value
        '  Else
        '    txtOT(6) = Trim(Str(CSng(dsHOURLY_DETAIL_OT.Fields("SUM").Value) + CSng(dsHOURLY_DETAIL_OTHRS.Fields("SUM").Value)))
        '  End If
        'End If
          'add on 1/13/2000
        If IsNull(dsHOURLY_DETAIL_OT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT.Fields("Sum").Value) = vbNullString Then
            iOt1 = 0
        Else
            iOt1 = dsHOURLY_DETAIL_OT.Fields("Sum").Value
        End If
        
        If IsNull(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_OT_A.Fields("Sum").Value) = vbNullString Then
            iOt2 = 0
        Else
            iOt2 = dsHOURLY_DETAIL_OT_A.Fields("Sum").Value
        End If
        iOtT = iOt1 + iOt2
        If iOtT = 0 Then
            txtOT(6) = ""
        Else
            txtOT(6) = Trim$(iOtT)
        End If
      
        If IsNull(dsHOURLY_DETAIL_DT.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_DT.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_DT.Fields("SUM").Value = 0 Then
          txtDT(6) = ""
        Else
          txtDT(6) = dsHOURLY_DETAIL_DT.Fields("SUM").Value
        End If
        If IsNull(dsHOURLY_DETAIL_ST.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_ST.Fields("Sum").Value) = vbNullString Or dsHOURLY_DETAIL_ST.Fields("SUM").Value = 0 Then
          txtST(6) = ""
        Else
          txtST(6) = dsHOURLY_DETAIL_ST.Fields("SUM").Value
        End If
        
        'add on 4/10/2000 by Bruce LeBrun - Show line runner hours.
        If IsNull(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) Or Trim(dsHOURLY_DETAIL_LINES.Fields("Sum").Value) = vbNullString Then
            txtLines(6) = ""
        Else
            txtLines(6) = dsHOURLY_DETAIL_LINES.Fields("Sum").Value
        End If

      
      End Select
      dsDAILY_HIRE_PREV.MoveNext
    Loop
    
    For Cntr = 0 To 6
      If Trim(txtReg(Cntr)) <> "" Then TotalHrs = TotalHrs + CSng(txtReg(Cntr))
      If Trim(txtOT(Cntr)) <> "" Then TotalHrs = TotalHrs + CSng(txtOT(Cntr))
      If Trim(txtDT(Cntr)) <> "" Then TotalHrs = TotalHrs + CSng(txtDT(Cntr))
      If Trim(txtST(Cntr)) <> "" Then TotalHrs = TotalHrs + CSng(txtST(Cntr))
            
      ' Added by Bruce LeBrun 03/24/2000 - Show total for Reg , OT , DT and ST hours
      Me.txtTotReg.Text = IIf(Val(Me.txtTotReg.Text) + Val(txtReg(Cntr)) = 0, "", Val(Me.txtTotReg.Text) + Val(txtReg(Cntr)))
      Me.txtTotOt.Text = IIf(Val(Me.txtTotOt.Text) + Val(txtOT(Cntr)) = 0, "", Val(Me.txtTotOt.Text) + Val(txtOT(Cntr)))
      Me.txtTotDT.Text = IIf(Val(Me.txtTotDT.Text) + Val(txtDT(Cntr)) = 0, "", Val(Me.txtTotDT.Text) + Val(txtDT(Cntr)))
      Me.txtTotST.Text = IIf(Val(Me.txtTotST.Text) + Val(txtST(Cntr)) = 0, "", Val(Me.txtTotST.Text) + Val(txtST(Cntr)))
      ' Added by Bruce LeBrun 04/10/2000 - Show total for Lines
      Me.txtTotLN.Text = IIf(Val(Me.txtTotLN.Text) + Val(txtLines(Cntr)) = 0, "", Val(Me.txtTotLN.Text) + Val(txtLines(Cntr)))
      
    Next
    
    Me.txtGrandTotal.Text = Val(txtTotReg.Text) + Val(txtTotOt.Text) + Val(txtTotDT.Text) + Val(txtTotST.Text)
    
    For Cntr = 0 To 6
      If Trim(txtReg(Cntr)) <> "" Then TotalDaily = CSng(txtReg(Cntr))
      If Trim(txtOT(Cntr)) <> "" Then TotalDaily = TotalDaily + CSng(txtOT(Cntr))
      If Trim(txtDT(Cntr)) <> "" Then TotalDaily = TotalDaily + CSng(txtDT(Cntr))
      If Trim(txtST(Cntr)) <> "" Then TotalDaily = TotalDaily + CSng(txtST(Cntr))
      If TotalDaily = 0 Then
        txtDaily(Cntr) = ""
      Else
        txtDaily(Cntr) = Trim(Str(TotalDaily))
      End If
      TotalDaily = 0
    Next
  End If
  txtTotalHrs = Trim(Str(TotalHrs))

End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  frmView.txtEmpId = ""
  frmView.Show
  frmView.txtEmpId.SetFocus
End Sub

Private Sub Timer1_Timer()

  Unload Me
  frmView.txtEmpId = ""
  frmView.Show
End Sub
