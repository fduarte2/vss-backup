VERSION 5.00
Begin VB.Form frmD_W_His 
   BackColor       =   &H00FFFFC0&
   Caption         =   "Dummy Withdrawl"
   ClientHeight    =   10410
   ClientLeft      =   2355
   ClientTop       =   1725
   ClientWidth     =   12255
   LinkTopic       =   "Form1"
   ScaleHeight     =   10410
   ScaleWidth      =   12255
   Begin VB.CommandButton cmdCreatePDF 
      Caption         =   "Create PDF"
      Height          =   315
      Left            =   240
      TabIndex        =   163
      Top             =   9600
      Width           =   2055
   End
   Begin VB.TextBox txtNotes 
      Height          =   495
      Left            =   2280
      MaxLength       =   500
      MultiLine       =   -1  'True
      TabIndex        =   160
      Top             =   8880
      Width           =   7695
   End
   Begin VB.TextBox comment2 
      Height          =   285
      Left            =   2280
      MaxLength       =   80
      TabIndex        =   159
      Top             =   8400
      Width           =   7695
   End
   Begin VB.TextBox comment1 
      Height          =   285
      Left            =   2280
      MaxLength       =   80
      TabIndex        =   158
      Top             =   8040
      Width           =   7695
   End
   Begin VB.TextBox comment 
      Height          =   285
      Left            =   2280
      MaxLength       =   80
      MultiLine       =   -1  'True
      TabIndex        =   156
      Top             =   7680
      Width           =   7695
   End
   Begin VB.TextBox whse 
      Enabled         =   0   'False
      Height          =   285
      Index           =   9
      Left            =   11400
      TabIndex        =   155
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox whse 
      Enabled         =   0   'False
      Height          =   285
      Index           =   8
      Left            =   11400
      TabIndex        =   154
      Top             =   6360
      Width           =   735
   End
   Begin VB.TextBox whse 
      Enabled         =   0   'False
      Height          =   285
      Index           =   7
      Left            =   11400
      TabIndex        =   153
      Top             =   6000
      Width           =   735
   End
   Begin VB.TextBox whse 
      Enabled         =   0   'False
      Height          =   285
      Index           =   6
      Left            =   11400
      TabIndex        =   152
      Top             =   5640
      Width           =   735
   End
   Begin VB.TextBox whse 
      Enabled         =   0   'False
      Height          =   285
      Index           =   5
      Left            =   11400
      TabIndex        =   151
      Top             =   5280
      Width           =   735
   End
   Begin VB.TextBox whse 
      Enabled         =   0   'False
      Height          =   285
      Index           =   4
      Left            =   11400
      TabIndex        =   150
      Top             =   4920
      Width           =   735
   End
   Begin VB.TextBox whse 
      Enabled         =   0   'False
      Height          =   285
      Index           =   3
      Left            =   11400
      TabIndex        =   149
      Top             =   4560
      Width           =   735
   End
   Begin VB.TextBox whse 
      Enabled         =   0   'False
      Height          =   285
      Index           =   2
      Left            =   11400
      TabIndex        =   148
      Top             =   4200
      Width           =   735
   End
   Begin VB.TextBox whse 
      Enabled         =   0   'False
      Height          =   285
      Index           =   1
      Left            =   11400
      TabIndex        =   147
      Top             =   3840
      Width           =   735
   End
   Begin VB.TextBox whse 
      Enabled         =   0   'False
      Height          =   285
      Index           =   0
      Left            =   11400
      TabIndex        =   146
      Top             =   3480
      Width           =   735
   End
   Begin VB.CommandButton delete_dummy 
      Caption         =   "Delete Dummy Delivery"
      Height          =   315
      Left            =   5160
      TabIndex        =   144
      Top             =   9600
      Width           =   2175
   End
   Begin VB.TextBox status1 
      Enabled         =   0   'False
      ForeColor       =   &H000000FF&
      Height          =   285
      Left            =   11280
      TabIndex        =   143
      Top             =   2760
      Width           =   615
   End
   Begin VB.CheckBox dummy 
      BackColor       =   &H00FFFFC0&
      Caption         =   "From Dummy"
      Height          =   255
      Left            =   5760
      TabIndex        =   141
      Top             =   480
      Width           =   1215
   End
   Begin VB.CommandButton save_dummy 
      Caption         =   "Save_to_Dummy"
      Height          =   315
      Left            =   2760
      TabIndex        =   140
      Top             =   9600
      Width           =   1815
   End
   Begin VB.CommandButton clear 
      Caption         =   "Clear"
      Height          =   315
      Left            =   7920
      TabIndex        =   139
      Top             =   9600
      Width           =   1695
   End
   Begin VB.TextBox loc 
      Height          =   315
      Left            =   10320
      TabIndex        =   138
      Top             =   2040
      Visible         =   0   'False
      Width           =   1335
   End
   Begin VB.CommandButton exit 
      Caption         =   "&Exit"
      Height          =   315
      Left            =   10200
      TabIndex        =   136
      Top             =   9600
      Width           =   1695
   End
   Begin VB.CommandButton printunsaved 
      Caption         =   "Print Dummy Withdrawal"
      Height          =   315
      Left            =   9960
      TabIndex        =   135
      Top             =   8160
      Visible         =   0   'False
      Width           =   2175
   End
   Begin VB.ComboBox cboSuper 
      Height          =   315
      Left            =   10800
      TabIndex        =   16
      Top             =   1200
      Width           =   1215
   End
   Begin VB.TextBox txtRemarks 
      Height          =   495
      Left            =   4320
      MultiLine       =   -1  'True
      TabIndex        =   34
      Top             =   2520
      Width           =   6375
   End
   Begin VB.TextBox txtIntial 
      Height          =   285
      Left            =   11280
      TabIndex        =   15
      Top             =   480
      Width           =   855
   End
   Begin VB.TextBox txtDelDetQty1Total 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   315
      Left            =   5100
      MaxLength       =   12
      TabIndex        =   130
      Top             =   7260
      Width           =   1365
   End
   Begin VB.TextBox txtDelDetQty2Total 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   315
      Left            =   7020
      MaxLength       =   12
      TabIndex        =   129
      Top             =   7260
      Width           =   1365
   End
   Begin VB.TextBox txtDelDetWeightTotal 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   315
      Left            =   8940
      MaxLength       =   50
      TabIndex        =   128
      Top             =   7260
      Width           =   1635
   End
   Begin VB.TextBox txtDelDetWeightUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   10620
      MaxLength       =   4
      TabIndex        =   50
      Top             =   3510
      Width           =   675
   End
   Begin VB.TextBox txtDelDetWeightUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   10620
      MaxLength       =   4
      TabIndex        =   58
      Top             =   3870
      Width           =   675
   End
   Begin VB.TextBox txtDelDetWeightUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   10620
      MaxLength       =   4
      TabIndex        =   66
      Top             =   4230
      Width           =   675
   End
   Begin VB.TextBox txtDelDetWeightUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   10620
      MaxLength       =   4
      TabIndex        =   74
      Top             =   4590
      Width           =   675
   End
   Begin VB.TextBox txtDelDetWeightUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   10620
      MaxLength       =   4
      TabIndex        =   82
      Top             =   4950
      Width           =   675
   End
   Begin VB.TextBox txtDelDetWeightUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   10620
      MaxLength       =   4
      TabIndex        =   90
      Top             =   5310
      Width           =   675
   End
   Begin VB.TextBox txtDelDetWeightUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   6
      Left            =   10620
      MaxLength       =   4
      TabIndex        =   98
      Top             =   5670
      Width           =   675
   End
   Begin VB.TextBox txtDelDetWeightUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   7
      Left            =   10620
      MaxLength       =   4
      TabIndex        =   106
      Top             =   6030
      Width           =   675
   End
   Begin VB.TextBox txtDelDetWeightUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   8
      Left            =   10620
      MaxLength       =   4
      TabIndex        =   114
      Top             =   6390
      Width           =   675
   End
   Begin VB.TextBox txtDelDetWeightUnit 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   9
      Left            =   10620
      MaxLength       =   4
      TabIndex        =   122
      Top             =   6750
      Width           =   675
   End
   Begin VB.TextBox txtDelDetWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   9
      Left            =   9120
      MaxLength       =   50
      TabIndex        =   121
      Top             =   6750
      Width           =   1425
   End
   Begin VB.TextBox txtDelDetWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   8
      Left            =   9150
      MaxLength       =   50
      TabIndex        =   113
      Top             =   6390
      Width           =   1425
   End
   Begin VB.TextBox txtDelDetWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   7
      Left            =   9150
      MaxLength       =   50
      TabIndex        =   105
      Top             =   6030
      Width           =   1425
   End
   Begin VB.TextBox txtDelDetWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   6
      Left            =   9150
      MaxLength       =   50
      TabIndex        =   97
      Top             =   5670
      Width           =   1425
   End
   Begin VB.TextBox txtDelDetWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   5
      Left            =   9150
      MaxLength       =   50
      TabIndex        =   89
      Top             =   5310
      Width           =   1425
   End
   Begin VB.TextBox txtDelDetWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   4
      Left            =   9150
      MaxLength       =   50
      TabIndex        =   81
      Top             =   4950
      Width           =   1425
   End
   Begin VB.TextBox txtDelDetWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   3
      Left            =   9150
      MaxLength       =   50
      TabIndex        =   73
      Top             =   4590
      Width           =   1425
   End
   Begin VB.TextBox txtDelDetWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   2
      Left            =   9150
      MaxLength       =   50
      TabIndex        =   65
      Top             =   4230
      Width           =   1425
   End
   Begin VB.TextBox txtDelDetWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   1
      Left            =   9150
      MaxLength       =   50
      TabIndex        =   57
      Top             =   3870
      Width           =   1425
   End
   Begin VB.TextBox txtDelDetWeight 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      ForeColor       =   &H00000000&
      Height          =   315
      Index           =   0
      Left            =   9150
      MaxLength       =   50
      TabIndex        =   49
      Top             =   3510
      Width           =   1425
   End
   Begin VB.ListBox lstDelDetMark 
      Height          =   1230
      Left            =   2190
      TabIndex        =   127
      Top             =   3870
      Width           =   3075
   End
   Begin VB.ListBox lstDelDetBOL 
      Height          =   1230
      Left            =   600
      TabIndex        =   125
      Top             =   3870
      Width           =   1395
   End
   Begin VB.TextBox txtDelDetMark 
      Height          =   315
      Index           =   9
      Left            =   2040
      MaxLength       =   60
      TabIndex        =   116
      Top             =   6780
      Width           =   2865
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   9
      Left            =   450
      MaxLength       =   20
      TabIndex        =   115
      Top             =   6780
      Width           =   1185
   End
   Begin VB.TextBox txtDelDetMark 
      Height          =   315
      Index           =   8
      Left            =   2040
      MaxLength       =   60
      TabIndex        =   108
      Top             =   6420
      Width           =   2865
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   8
      Left            =   450
      MaxLength       =   20
      TabIndex        =   107
      Top             =   6420
      Width           =   1185
   End
   Begin VB.TextBox txtDelDetMark 
      Height          =   315
      Index           =   7
      Left            =   2040
      MaxLength       =   60
      TabIndex        =   100
      Top             =   6060
      Width           =   2865
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   7
      Left            =   450
      MaxLength       =   20
      TabIndex        =   99
      Top             =   6060
      Width           =   1185
   End
   Begin VB.TextBox txtDelDetMark 
      Height          =   315
      Index           =   6
      Left            =   2040
      MaxLength       =   60
      TabIndex        =   92
      Top             =   5700
      Width           =   2865
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   6
      Left            =   450
      MaxLength       =   20
      TabIndex        =   91
      Top             =   5700
      Width           =   1185
   End
   Begin VB.TextBox txtDelDetMark 
      Height          =   315
      Index           =   5
      Left            =   2040
      MaxLength       =   60
      TabIndex        =   84
      Top             =   5340
      Width           =   2865
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   5
      Left            =   450
      MaxLength       =   20
      TabIndex        =   83
      Top             =   5340
      Width           =   1185
   End
   Begin VB.TextBox txtDelDetMark 
      Height          =   315
      Index           =   4
      Left            =   2040
      MaxLength       =   60
      TabIndex        =   76
      Top             =   4980
      Width           =   2865
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   4
      Left            =   450
      MaxLength       =   20
      TabIndex        =   75
      Top             =   4980
      Width           =   1185
   End
   Begin VB.TextBox txtDelDetMark 
      Height          =   315
      Index           =   3
      Left            =   2040
      MaxLength       =   60
      TabIndex        =   68
      Top             =   4620
      Width           =   2865
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   3
      Left            =   450
      MaxLength       =   20
      TabIndex        =   67
      Top             =   4620
      Width           =   1185
   End
   Begin VB.TextBox txtDelDetMark 
      Height          =   315
      Index           =   2
      Left            =   2040
      MaxLength       =   60
      TabIndex        =   60
      Top             =   4260
      Width           =   2865
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   2
      Left            =   450
      MaxLength       =   20
      TabIndex        =   59
      Top             =   4260
      Width           =   1185
   End
   Begin VB.TextBox txtDelDetMark 
      Height          =   315
      Index           =   1
      Left            =   2040
      MaxLength       =   60
      TabIndex        =   52
      Top             =   3900
      Width           =   2865
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   1
      Left            =   450
      MaxLength       =   20
      TabIndex        =   51
      Top             =   3900
      Width           =   1185
   End
   Begin VB.TextBox txtDelDetMark 
      Height          =   315
      Index           =   0
      Left            =   2040
      MaxLength       =   60
      TabIndex        =   44
      Top             =   3540
      Width           =   2835
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   0
      Left            =   480
      MaxLength       =   20
      TabIndex        =   43
      Top             =   3540
      Width           =   1185
   End
   Begin VB.CommandButton cmdDelDetMark 
      Height          =   315
      Left            =   4920
      Style           =   1  'Graphical
      TabIndex        =   126
      TabStop         =   0   'False
      Top             =   3540
      Width           =   345
   End
   Begin VB.CommandButton cmdDelDetBOL 
      Height          =   315
      Left            =   1650
      Style           =   1  'Graphical
      TabIndex        =   124
      TabStop         =   0   'False
      Top             =   3540
      Width           =   345
   End
   Begin VB.TextBox txtDelDetCustomerName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   7800
      MaxLength       =   40
      TabIndex        =   28
      Top             =   1200
      Width           =   2955
   End
   Begin VB.CommandButton cmdDelDetCustomerList 
      Height          =   315
      Left            =   7440
      Style           =   1  'Graphical
      TabIndex        =   27
      TabStop         =   0   'False
      Top             =   1200
      Width           =   345
   End
   Begin VB.TextBox txtDelDetUnit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   9
      Left            =   8430
      MaxLength       =   4
      TabIndex        =   120
      Top             =   6750
      Width           =   675
   End
   Begin VB.TextBox txtDelDetUnit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   9
      Left            =   6510
      MaxLength       =   4
      TabIndex        =   118
      Top             =   6750
      Width           =   675
   End
   Begin VB.TextBox txtDelDetQty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   9
      Left            =   7230
      MaxLength       =   12
      TabIndex        =   119
      Top             =   6750
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetQty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   9
      Left            =   5310
      MaxLength       =   12
      TabIndex        =   117
      Top             =   6750
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetUnit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   8
      Left            =   8430
      MaxLength       =   4
      TabIndex        =   112
      Top             =   6390
      Width           =   675
   End
   Begin VB.TextBox txtDelDetUnit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   8
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   110
      Top             =   6390
      Width           =   675
   End
   Begin VB.TextBox txtDelDetQty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   8
      Left            =   7230
      MaxLength       =   7
      TabIndex        =   111
      Top             =   6390
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetQty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   8
      Left            =   5310
      MaxLength       =   7
      TabIndex        =   109
      Top             =   6390
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetUnit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   7
      Left            =   8430
      MaxLength       =   4
      TabIndex        =   104
      Top             =   6030
      Width           =   675
   End
   Begin VB.TextBox txtDelDetUnit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   7
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   102
      Top             =   6030
      Width           =   675
   End
   Begin VB.TextBox txtDelDetQty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   7
      Left            =   7230
      MaxLength       =   7
      TabIndex        =   103
      Top             =   6030
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetQty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   7
      Left            =   5310
      MaxLength       =   7
      TabIndex        =   101
      Top             =   6030
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetUnit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   6
      Left            =   8430
      MaxLength       =   4
      TabIndex        =   96
      Top             =   5670
      Width           =   675
   End
   Begin VB.TextBox txtDelDetUnit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   6
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   94
      Top             =   5670
      Width           =   675
   End
   Begin VB.TextBox txtDelDetQty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   6
      Left            =   7230
      MaxLength       =   7
      TabIndex        =   95
      Top             =   5670
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetQty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   6
      Left            =   5310
      MaxLength       =   7
      TabIndex        =   93
      Top             =   5670
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetUnit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   8430
      MaxLength       =   4
      TabIndex        =   88
      Top             =   5310
      Width           =   675
   End
   Begin VB.TextBox txtDelDetUnit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   86
      Top             =   5310
      Width           =   675
   End
   Begin VB.TextBox txtDelDetQty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   5
      Left            =   7230
      MaxLength       =   7
      TabIndex        =   87
      Top             =   5280
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetQty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   5
      Left            =   5310
      MaxLength       =   7
      TabIndex        =   85
      Top             =   5310
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetUnit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   8430
      MaxLength       =   4
      TabIndex        =   80
      Top             =   4950
      Width           =   675
   End
   Begin VB.TextBox txtDelDetUnit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   78
      Top             =   4950
      Width           =   675
   End
   Begin VB.TextBox txtDelDetQty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   4
      Left            =   7230
      MaxLength       =   7
      TabIndex        =   79
      Top             =   4950
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetQty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   4
      Left            =   5310
      MaxLength       =   7
      TabIndex        =   77
      Top             =   4950
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetUnit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   8430
      MaxLength       =   4
      TabIndex        =   72
      Top             =   4590
      Width           =   675
   End
   Begin VB.TextBox txtDelDetUnit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   70
      Top             =   4590
      Width           =   675
   End
   Begin VB.TextBox txtDelDetQty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   3
      Left            =   7230
      MaxLength       =   7
      TabIndex        =   71
      Top             =   4590
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetQty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   3
      Left            =   5310
      MaxLength       =   7
      TabIndex        =   69
      Top             =   4590
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetUnit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   8430
      MaxLength       =   4
      TabIndex        =   64
      Top             =   4230
      Width           =   675
   End
   Begin VB.TextBox txtDelDetUnit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   62
      Top             =   4230
      Width           =   675
   End
   Begin VB.TextBox txtDelDetQty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   2
      Left            =   7230
      MaxLength       =   7
      TabIndex        =   63
      Top             =   4230
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetQty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   2
      Left            =   5310
      MaxLength       =   7
      TabIndex        =   61
      Top             =   4230
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetUnit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   8430
      MaxLength       =   4
      TabIndex        =   56
      Top             =   3870
      Width           =   675
   End
   Begin VB.TextBox txtDelDetUnit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   54
      Top             =   3870
      Width           =   675
   End
   Begin VB.TextBox txtDelDetQty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   1
      Left            =   7230
      MaxLength       =   7
      TabIndex        =   55
      Top             =   3870
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetQty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   1
      Left            =   5310
      MaxLength       =   7
      TabIndex        =   53
      Top             =   3870
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetUnit2 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   8430
      MaxLength       =   4
      TabIndex        =   48
      Top             =   3510
      Width           =   675
   End
   Begin VB.TextBox txtDelDetUnit1 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   6510
      MaxLength       =   7
      TabIndex        =   46
      Top             =   3510
      Width           =   675
   End
   Begin VB.TextBox txtDelDetTransNum 
      Height          =   315
      Left            =   4800
      MaxLength       =   30
      TabIndex        =   24
      Top             =   1200
      Width           =   1485
   End
   Begin VB.PictureBox picDelDetIndex 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   240
      Left            =   180
      ScaleHeight     =   240
      ScaleWidth      =   240
      TabIndex        =   123
      TabStop         =   0   'False
      Top             =   3540
      Width           =   240
   End
   Begin VB.TextBox txtDelDetRemarks 
      BackColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   4320
      TabIndex        =   32
      Top             =   1860
      Width           =   4005
   End
   Begin VB.TextBox txtDelDetDeliverTo 
      BackColor       =   &H00FFFFFF&
      Height          =   1185
      Left            =   240
      MultiLine       =   -1  'True
      TabIndex        =   30
      Top             =   1860
      Width           =   4005
   End
   Begin VB.ComboBox cboDelDetTransMode 
      Height          =   315
      ItemData        =   "Dum_With_History.frx":0000
      Left            =   3600
      List            =   "Dum_With_History.frx":0010
      Style           =   2  'Dropdown List
      TabIndex        =   22
      TabStop         =   0   'False
      Top             =   1200
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetOrderDate 
      Height          =   315
      Left            =   240
      MaxLength       =   10
      TabIndex        =   18
      Top             =   1200
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetCustomerId 
      Height          =   315
      Left            =   6360
      MaxLength       =   7
      TabIndex        =   26
      Top             =   1200
      Width           =   975
   End
   Begin VB.TextBox txtDelDetOrderNum 
      Height          =   315
      Left            =   1440
      MaxLength       =   20
      TabIndex        =   20
      Top             =   1200
      Width           =   2115
   End
   Begin VB.TextBox txtDelDetQty1 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   0
      Left            =   5310
      MaxLength       =   7
      TabIndex        =   45
      Top             =   3510
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetQty2 
      Alignment       =   1  'Right Justify
      Height          =   315
      Index           =   0
      Left            =   7230
      MaxLength       =   7
      TabIndex        =   47
      Top             =   3480
      Width           =   1155
   End
   Begin VB.CheckBox chkDelDetBill 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Generate Bill?"
      Height          =   285
      Left            =   8520
      TabIndex        =   33
      Top             =   1920
      Width           =   1365
   End
   Begin VB.TextBox txtDeliveryNum 
      Height          =   315
      Left            =   8640
      MaxLength       =   15
      TabIndex        =   14
      Top             =   480
      Width           =   1875
   End
   Begin VB.TextBox txtCommodityName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   2520
      MaxLength       =   40
      TabIndex        =   12
      Top             =   450
      Width           =   2715
   End
   Begin VB.TextBox txtCommodityCode 
      Height          =   315
      Left            =   1110
      MaxLength       =   12
      TabIndex        =   10
      Top             =   450
      Width           =   975
   End
   Begin VB.CommandButton cmdCommodityList 
      Height          =   315
      Left            =   2130
      Style           =   1  'Graphical
      TabIndex        =   11
      TabStop         =   0   'False
      Top             =   450
      Width           =   345
   End
   Begin VB.CommandButton cmdRecipientList 
      Height          =   315
      Left            =   7980
      Style           =   1  'Graphical
      TabIndex        =   7
      TabStop         =   0   'False
      Top             =   60
      Width           =   345
   End
   Begin VB.CommandButton cmdVesselList 
      Height          =   315
      Left            =   2130
      Style           =   1  'Graphical
      TabIndex        =   3
      TabStop         =   0   'False
      Top             =   60
      Width           =   345
   End
   Begin VB.TextBox txtRecipientId 
      Height          =   315
      Left            =   6960
      MaxLength       =   6
      TabIndex        =   6
      Top             =   60
      Width           =   975
   End
   Begin VB.TextBox txtCustomerName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   8400
      MaxLength       =   40
      TabIndex        =   8
      Top             =   60
      Width           =   3435
   End
   Begin VB.TextBox txtVesselName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   2520
      MaxLength       =   40
      TabIndex        =   4
      Top             =   60
      Width           =   3435
   End
   Begin VB.TextBox txtLRNum 
      Height          =   315
      Left            =   1110
      MaxLength       =   7
      TabIndex        =   2
      Top             =   60
      Width           =   975
   End
   Begin VB.Label lblCharCount 
      BackColor       =   &H00FFFFC0&
      Caption         =   "0"
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
      Left            =   10080
      TabIndex        =   162
      Top             =   9000
      Width           =   1815
   End
   Begin VB.Label Label5 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Post Note"
      Height          =   255
      Left            =   1200
      TabIndex        =   161
      Top             =   9000
      Width           =   855
   End
   Begin VB.Label Label4 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Comments:"
      Height          =   255
      Left            =   1320
      TabIndex        =   157
      Top             =   7680
      Width           =   855
   End
   Begin VB.Label Label3 
      BackColor       =   &H00FFFFC0&
      Caption         =   "WHSE"
      Height          =   255
      Left            =   11400
      TabIndex        =   145
      Top             =   3240
      Width           =   615
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackColor       =   &H00FFFFC0&
      Caption         =   "Booked"
      Height          =   195
      Left            =   11280
      TabIndex        =   142
      Top             =   2520
      Width           =   555
   End
   Begin VB.Label location 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Location"
      Height          =   255
      Left            =   10320
      TabIndex        =   137
      Top             =   1680
      Visible         =   0   'False
      Width           =   1095
   End
   Begin VB.Label Label1 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Supervisor"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   10800
      TabIndex        =   134
      Top             =   960
      Width           =   855
   End
   Begin VB.Label lblRemarks 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Remarks"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4320
      TabIndex        =   133
      Top             =   2280
      Width           =   675
   End
   Begin VB.Label lblIntial 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Initial"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   10680
      TabIndex        =   132
      Top             =   480
      Width           =   435
   End
   Begin VB.Label ltlTotal 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Total"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4500
      TabIndex        =   131
      Top             =   7320
      Width           =   525
   End
   Begin VB.Label lblDelDetWeightUnit 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Wt. Unit"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   10620
      TabIndex        =   42
      Top             =   3240
      Width           =   675
   End
   Begin VB.Label lblDelDetWeight 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Weight"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   9150
      TabIndex        =   41
      Top             =   3240
      Width           =   1425
   End
   Begin VB.Label lblDelDetUnit2 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Unit 2"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   8430
      TabIndex        =   40
      Top             =   3240
      Width           =   675
   End
   Begin VB.Label lblDelDetUnit1 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Unit 1"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   6510
      TabIndex        =   38
      Top             =   3240
      Width           =   675
   End
   Begin VB.Label lblDelDetBOL 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "BOL"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   450
      TabIndex        =   35
      Top             =   3270
      Width           =   1185
   End
   Begin VB.Label lblDelDetMark 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Mark"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   2040
      TabIndex        =   36
      Top             =   3270
      Width           =   2415
   End
   Begin VB.Label lblDelDetQty1 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Quantity 1"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   5310
      TabIndex        =   37
      Top             =   3240
      Width           =   1155
   End
   Begin VB.Label lblDelDetQty2 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Quantity 2"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   7230
      TabIndex        =   39
      Top             =   3240
      Width           =   1155
   End
   Begin VB.Shape Shape3 
      BorderColor     =   &H80000002&
      BorderWidth     =   2
      Height          =   4005
      Left            =   120
      Top             =   3180
      Width           =   12045
   End
   Begin VB.Shape Shape1 
      BorderColor     =   &H80000002&
      BorderWidth     =   2
      Height          =   2355
      Left            =   120
      Top             =   840
      Width           =   12045
   End
   Begin VB.Label lblDelDetTransNum 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Trailer Lic Num"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4800
      TabIndex        =   23
      Top             =   930
      Width           =   1485
   End
   Begin VB.Label lblDelDetRemarks 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Carrier"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4290
      TabIndex        =   31
      Top             =   1590
      Width           =   825
   End
   Begin VB.Label lblDelDetDeliverTo 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Deliver To"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   210
      TabIndex        =   29
      Top             =   1590
      Width           =   825
   End
   Begin VB.Label lblDelDetTransMode 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Transport Mode"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   3600
      TabIndex        =   21
      Top             =   930
      Width           =   1155
   End
   Begin VB.Label lblDelDetOrderDate 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Order Date"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   210
      TabIndex        =   17
      Top             =   930
      Width           =   1155
   End
   Begin VB.Label lblDelDetBillToCustomer 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Bill To Customer"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   6360
      TabIndex        =   25
      Top             =   960
      Width           =   1635
   End
   Begin VB.Label lblDelDetOrderNum 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Order Number"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   1920
      TabIndex        =   19
      Top             =   930
      Width           =   1125
   End
   Begin VB.Label lblDeliveryNum 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Dummy Delivery No"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   7200
      TabIndex        =   13
      Top             =   480
      Width           =   1485
   End
   Begin VB.Label lblCommodity 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Commodity"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   90
      TabIndex        =   9
      Top             =   510
      Width           =   975
   End
   Begin VB.Label lblOwner 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Owner"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   6270
      TabIndex        =   5
      Top             =   120
      Width           =   645
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   285
      Left            =   120
      TabIndex        =   0
      Top             =   10080
      Width           =   11895
   End
   Begin VB.Label lblVessel 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Vessel"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   90
      TabIndex        =   1
      Top             =   120
      Width           =   975
   End
End
Attribute VB_Name = "frmD_W_His"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cboSuper_LOSTFOCUS()
    Dim sSqlStmt As String
    
    sSqlStmt = "SELECT * FROM PERSONNEL WHERE EMPLOYEE_FIRST_NAME = '" & Trim$(cboSuper.Text) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
    If dsPERSONNEL.Recordcount <= 0 Then
        MsgBox "Supervisor Field Value Is Not Correct", vbInformation, "Supervisor"
        cboSuper.SetFocus
        cboSuper.Text = ""
    End If
End Sub

Private Sub clear_Click()
Dim i As Long
For i = 0 To 9
    'txtDelDetBOL(i).Text = ""
    'txtDelDetMark(i).Text = ""
    txtDelDetQty1(i).Text = ""
    txtDelDetUnit1(i).Text = ""
    txtDelDetQty2(i).Text = ""
    txtDelDetUnit2(i).Text = ""
    txtDelDetWeight(i).Text = ""
    txtDelDetWeightUnit(i).Text = ""
    whse(i).Text = ""
Next i
txtDeliveryNum.Text = ""
status1.Text = ""
txtDelDetQty1Total.Text = ""
txtDelDetQty2Total.Text = ""
txtDelDetWeightTotal.Text = ""
End Sub

Private Sub cmdCreatePDF_Click()
'    Call save_dummy_Click
    Shell ("cmd /c start http://intranet/inventory/Dummy_PDF.php?DumNum=" & txtDeliveryNum)
End Sub

Private Sub cmdDelDetBOL_Click()
    Call FillBOL
    If lstDelDetBOL.ListCount > 0 Then
        lstDelDetBOL.Visible = Not lstDelDetBOL.Visible
    Else
        lstDelDetBOL.Visible = False
    End If
End Sub

Private Sub cmdDelDetMark_Click()
    Call FillMark(giDelDetIndex)
    If lstDelDetMark.ListCount > 0 Then
        lstDelDetMark.Visible = Not lstDelDetMark.Visible
    Else
        lstDelDetMark.Visible = False
    End If
End Sub

Private Sub delete_dummy_Click()
Dim iError As Boolean

    If dummy.Value = 0 Then
        MsgBox "You Can Delete Only From Dummy Withdrawal. To Delete From System Please Consult Your Supervisor", vbInformation, "Delete From Dummy"
        Exit Sub
    End If
    iError = False
    OraSession.BeginTrans
    gsSqlStmt = "SELECT * FROM bni_dummy_withdrawal where d_del_no='" & Trim$(txtDeliveryNum.Text) & "'"
    Set dsDummy = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsDummy.Recordcount > 0 Then
        Do While Not dsDummy.EOF
            dsDummy.Edit
                dsDummy.Fields("status").Value = "D"
                dsDummy.Update
            dsDummy.MoveNext
        Loop
    Else
        iError = True
    End If
    If iError Then
        'Rollback transaction
        MsgBox "Error occured while Deleting From Dummy Cargo Delivery table. Changes are not saved.", vbExclamation, "Delete Delivery"
        OraSession.Rollback
        Exit Sub
    Else
        'Commit transaction
        OraSession.CommitTrans
        txtDeliveryNum.Text = ""
    End If
End Sub

Private Sub exit_Click()
End
End Sub

Private Sub lstDelDetBOL_Click()
    If lstDelDetBOL.ListIndex >= 0 Then
        txtDelDetBOL(giDelDetIndex) = lstDelDetBOL.List(lstDelDetBOL.ListIndex)
    End If
    lstDelDetBOL.Visible = False
    txtDelDetMark(giDelDetIndex).SetFocus
End Sub

Private Sub lstDelDetBOL_LostFocus()
    lstDelDetBOL.Visible = False
End Sub


Private Sub lstDelDetMark_Click()
    If lstDelDetMark.ListIndex >= 0 Then
        txtDelDetMark(giDelDetIndex) = lstDelDetMark.List(lstDelDetMark.ListIndex)
    End If
    lstDelDetMark.Visible = False
    txtDelDetQty1(giDelDetIndex).SetFocus
End Sub

Private Sub lstDelDetMark_LostFocus()
    lstDelDetMark.Visible = False
End Sub


Private Sub printunsaved_Click()
    Dim dsCARGO_DELIVERY As Object
    Dim dsCUSTOMER_PROFILE As Object
    Dim dsCARGO_MANIFEST As Object
    
    Dim wt As Double
    Dim check As String
    Dim mark As String
    Dim bol As String
    Dim lRecCount As Long
    
    Dim sSqlStmt As String
    Dim iErrOccured As Integer
    
    Dim sCustomerAddr1 As String
    Dim sCustomerAddr2 As String
    Dim sCustomerAddr3 As String
    Dim RemarkLength As Integer
    Dim rem1 As String
    Dim rem2 As String
    Dim com1 As String
    Dim com2 As String
    Dim com3 As String
    
    Dim pos As Long
    
    Dim Loaded_Weight As Double
    Dim CRPosition1 As Long
    Dim CRPosition2 As Long
    
    Dim DeliveryAddr As String
    Dim DeliveryAddr1 As String
    Dim DeliveryAddr2 As String
    Dim DeliveryAddr3 As String
    Dim DeliveryAddr4 As String
    Dim AddrLineLength As Integer
    
    Dim sLine As String

    Dim i
    Dim Num_Of_Lines
    
    'On Error GoTo Err_CargoBilling
    Printer.FontName = "TIMES NEW ROMAN"
    Printer.FontSize = 10
    iErrOccured = False
    If (txtRemarks.Text = "") Then
        txtRemarks.Text = "NONE"
    End If
    pos = InStr(1, txtRemarks.Text, Chr$(13))
    If pos > 0 Then
        rem1 = Mid$(txtRemarks.Text, 1, pos - 1)
        rem2 = Mid$(txtRemarks.Text, pos + 2)
    Else
        rem1 = txtRemarks.Text
        rem2 = ""
    End If
    
    ' Fixed Comment - STM
    If (comment.Text = "") Then
        comment.Text = " "
    End If
    If (comment1.Text = "") Then
        comment1.Text = " "
    End If
    If (comment2.Text = "") Then
        comment2.Text = " "
    End If
    com1 = comment.Text
    com2 = comment1.Text
    com3 = comment2.Text
        
    DeliveryAddr = txtDelDetDeliverTo.Text
    
    
    CRPosition1 = 1
    CRPosition2 = 1
        
    CRPosition2 = InStr(CRPosition1, DeliveryAddr, Chr$(13))
    AddrLineLength = CRPosition2 - CRPosition1
    If AddrLineLength > 0 Then
            
            DeliveryAddr1 = Mid$(DeliveryAddr, CRPosition1, AddrLineLength)
            CRPosition1 = CRPosition2 + 2
            
            CRPosition2 = InStr(CRPosition1, DeliveryAddr, Chr$(13))
            AddrLineLength = CRPosition2 - CRPosition1
            
            If AddrLineLength > 0 Then
                
                DeliveryAddr2 = Mid$(DeliveryAddr, CRPosition1, AddrLineLength)
                CRPosition1 = CRPosition2 + 2
            
                CRPosition2 = InStr(CRPosition1, DeliveryAddr, Chr$(13))
                AddrLineLength = CRPosition2 - CRPosition1
                
                If AddrLineLength > 0 Then
                
                    DeliveryAddr3 = Mid$(DeliveryAddr, CRPosition1, AddrLineLength)
                    CRPosition1 = CRPosition2 + 2
            
                    CRPosition2 = InStr(CRPosition1, DeliveryAddr, Chr$(13))
                    AddrLineLength = CRPosition2 - CRPosition1
                    
                    If AddrLineLength > 0 Then
                        DeliveryAddr4 = Mid$(DeliveryAddr, CRPosition1, AddrLineLength)
                    
                    Else
                        DeliveryAddr4 = Mid$(DeliveryAddr, CRPosition1)
                    End If
                    
                    
                Else
                    DeliveryAddr3 = Mid$(DeliveryAddr, CRPosition1)
                
                End If
                
            
            Else
                DeliveryAddr2 = Mid$(DeliveryAddr, CRPosition1)
                
            End If
                
    Else
            DeliveryAddr1 = Mid$(DeliveryAddr, CRPosition1)
    End If
    
   
    
    Printer.Orientation = 2
    Printer.Height = 1240 * 8.5 'Twips 1440 per inch
    Printer.Width = 1240 * 10 'Twips 1440 per inch
    Printer.FontSize = 10
    Printer.FontBold = True
    Printer.FontSize = 12
    Printer.Print Tab(8); " VESSEL :  " & txtVesselName.Text; Tab(65); " OWNER :  " & txtCustomerName.Text
    Printer.Print Tab(8); " COMMODITY :  " & txtCommodityName.Text; Tab(65); " DUMMY DELIVERY NO :  " & txtDeliveryNum.Text; Tab(115); "INITIAL  : " & txtIntial.Text
    Printer.FontSize = 10
    Printer.FontBold = False
    Printer.Print "__________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
    Printer.Print Tab(10); "Order Date"; Tab(30); "Order No"; Tab(50); "Transport Mode"; Tab(75); "Trailer Lic Num"; Tab(100); "Bill To Customer"; Tab(145); "Supervisor  "
    Printer.Print Tab(10); txtDelDetOrderDate.Text; Tab(30); txtDelDetOrderNum.Text; Tab(50); cboDelDetTransMode.Text; Tab(75); txtDelDetTransNum.Text; Tab(100); txtDelDetCustomerName.Text; Tab(145); cboSuper.Text
    Printer.Print ""
    Printer.Print Tab(10); "Deliver To"; Tab(60); "Carrier"
    'Printer.Print ""
    Printer.Print Tab(10); DeliveryAddr1; Tab(60); txtDelDetRemarks.Text
    Printer.Print Tab(10); DeliveryAddr2; Tab(60); ""
    Printer.Print Tab(10); DeliveryAddr3; Tab(60); "Remarks"
    Printer.Print Tab(10); DeliveryAddr4; Tab(60); rem1
    Printer.Print Tab(60); rem2
    Printer.Print "__________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
    Printer.Print Tab(1); "BOL"; Tab(20); "Mark"; Tab(85); "Loc"; Tab(100); "Qty1"; Tab(113); "Unit1"; Tab(125); "Qty2"; Tab(138); "Unit2"; Tab(150); "Weight"; Tab(170); "CODE"
    Printer.Print ""
    Num_Of_Lines = 0
    For i = 0 To 9
        bol = txtDelDetBOL(i).Text
        mark = txtDelDetMark(i).Text
        If (bol <> "") Then
            sSqlStmt = "SELECT CARGO_LOCATION FROM CARGO_MANIFEST WHERE CARGO_BOL='" & bol & "' AND LR_NUM = '" & txtLRNum & "' AND CARGO_MARK = '" & mark & "'"
            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.Recordcount > 0 Then
                loc.Text = dsCARGO_MANIFEST.Fields("CARGO_LOCATION").Value
            Else
                loc.Text = ""
            End If
        Else
            loc.Text = ""
        End If
        check = Trim(txtDelDetQty1(i).Text)
        If (check <> "") Then
            wt = Val(txtDelDetWeight(i).Text)
            Printer.FontBold = True
            Printer.FontSize = 12
            Printer.Print Tab(1); txtDelDetBOL(i).Text; Tab(17); txtDelDetMark(i).Text; Tab(66); loc.Text; Tab(81); txtDelDetQty1(i).Text; Tab(89); txtDelDetUnit1(i).Text; Tab(100); txtDelDetQty2(i).Text; Tab(109); txtDelDetUnit2(i).Text; Tab(118); Format(wt, "##,###,###,##0.00"); Tab(132); whse(i).Text
            Printer.FontSize = 10
            Printer.FontBold = False
            'Printer.Print ""
            Num_Of_Lines = Num_Of_Lines + 1
        Else
            Printer.Print ""
            Printer.Print ""
            Num_Of_Lines = Num_Of_Lines + 2
        End If
    Next i
    
    ' Here is where we should print the Trailer Inspection Information
    Printer.FontSize = 8
    Printer.FontBold = True
    Printer.Print Tab(90); "Trailer Inspection Information"
    Printer.Print ""
    Num_Of_Lines = Num_Of_Lines + 20
    Printer.FontItalic = True
    Printer.Print Tab(55); "  DRIVER MUST CHOCK WHEEL PRIOR TO LOADING     ________ (Driver Initials)"
    'Printer.Print ""
    Printer.Print Tab(55); "  AND RETURN CHOCK TO HANGER OPON COMPLETION"
    Printer.FontItalic = False
    Printer.Print Tab(55); "DRIVER: TRAILER WAS COOLED TO ______ Degrees F ________ (Driver Initials)"
    Printer.Print Tab(55); "CLEANLINESS:   _____Good     _____Fair     _____Poor              ODOR:   _____YES     _____NO      "
    Printer.Print ""
    Printer.Print Tab(55); "ACCEPTED FOR LOADING:     _____Yes - Proceed & Load                     _____No - Contact Supervisor"
    Printer.Print ""
    'Printer.Print Tab(55); "COMMENTS (If Any): _________________________________________________________________________________"
    'Printer.Print ""
    'Printer.Print Tab(55); "_____________________________________________________________________________________________________"
    'Printer.Print ""
    Printer.Print Tab(55); "    CHECKER:________________________                                     DRIVER:________________________"
    Printer.Print Tab(55); "                      (Signature)                                                          (Signature)           "
    
    ' Reset the printer
    Printer.FontSize = 10
    Printer.FontBold = False
    For i = 1 To 23 - (Num_Of_Lines)
        Printer.Print ""
    Next i
    Printer.Print "__________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
    
    Printer.Print Tab(65); "TOTAL "; Tab(95); Val(txtDelDetQty1Total.Text); Tab(120); txtDelDetQty2Total.Text; Tab(145); Format(Val(txtDelDetWeightTotal.Text), "##,###,###,##0.00")
    Printer.FontSize = 12
    Printer.Print Tab(5); com1
    Printer.Print Tab(5); com2
    Printer.Print Tab(5); com3
    Printer.FontSize = 10
    CRPosition1 = 1
    CRPosition2 = 1
    
    Printer.EndDoc
End Sub

Private Sub save_dummy_Click()
    Dim iResponse As Integer
    Dim lDeliveryNum As String
    Dim delivery_no As Long
    Dim delivery_no1 As String
    Dim size As Long
    Dim buffer As String
    Dim lActivityNum As Long
    Dim i As Integer
    Dim iError As Boolean
    Dim iRecSaved As Integer
    Dim lRecCount As Long


 'Lock all the required tables in exclusive mode, try 10 times
    'On Error Resume Next
    'For i = 0 To 9
    '    OraDatabase.LastServerErrReset
    '    gsSqlStmt = "LOCK TABLE bni_dummy_withdrawal IN EXCLUSIVE MODE NOWAIT"
    '    lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
    '    If OraDatabase.LastServerErr = 0 Then Exit For
    'Next 'i
    'If OraDatabase.LastServerErr <> 0 Then
    ''    OraDatabase.LastServerErr
    '    MsgBox "Table could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
    '    Exit Sub
    'End If
    On Error GoTo 0
    iError = False
    If Not (Trim$(txtDelDetQty1(0).Text) <> "" And Val(Trim$(txtDelDetQty1(0).Text)) > 0) Then
        Exit Sub
    End If
    If txtIntial.Text = "" Then
       MsgBox "Please Enter Intial"
       Exit Sub
    End If
     
    If txtRemarks.Text = "" Then
       MsgBox "Please Enter Remarks"
       Exit Sub
    End If
     
    If Len(txtRemarks.Text) > 1000 Then
       MsgBox "Remark Length Should be Less than or equal to 1000"
       Exit Sub
    End If
    
    If Len(txtNotes.Text) > 1000 Then
       MsgBox "Post Notes Length Should be Less than or equal to 1000"
       Exit Sub
    End If
    
    If Trim$(cboSuper.Text) = "" Then
       MsgBox "Supervisor Field Can Be Null"
       Exit Sub
    End If
    If Trim$(txtDeliveryNum.Text) = "" Then
        
'        gsSqlStmt = "SELECT max(d_del_no) del_no FROM bni_dummy_withdrawal"
'        Dim dsDummy_max As Object          'only in save_dummy
'        Set dsDummy_max = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'        If OraDatabase.LastServerErr = 0 And dsDummy_max.RecordCount > 0 Then
'            If IsNull(dsDummy_max.Fields("del_no").Value) Then
'                lDeliveryNum = "DM00000001"
'            Else
'                buffer = Trim(dsDummy_max.Fields("del_no").Value)
'                If buffer <> "" Then
'                    delivery_no = Val(Mid(buffer, 3))
'                    delivery_no = delivery_no + 1
'                    delivery_no1 = delivery_no
'                    size = Len(delivery_no1)
'                    For i = size + 1 To 8
'                        delivery_no1 = "0" & delivery_no1
'                    Next i
'                    lDeliveryNum = "DM" & delivery_no1
'                Else
'                    lDeliveryNum = "DM00000001"
'                End If
'            End If
'        Else
'            lDeliveryNum = "DM00000001"
'        End If
'        Set dsDummy_max = Nothing
        gsSqlStmt = "SELECT BNI_DUMMY_NUMBER_SEQ.NEXTVAL THE_SEQ FROM DUAL"
        Dim dsDummy_max As Object          'only in save_dummy
        Dim iLeadingZeros As Integer
        Set dsDummy_max = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 Then
            lDeliveryNum = "DM"
            iLeadingZeros = 8 - Len(dsDummy_max.Fields("THE_SEQ").Value)
            While iLeadingZeros > 0
                lDeliveryNum = lDeliveryNum & "0"
                iLeadingZeros = iLeadingZeros - 1
            Wend
            lDeliveryNum = lDeliveryNum & dsDummy_max.Fields("THE_SEQ").Value
        End If
        Set dsDummy_max = Nothing
        
        'Begin a transaction
        OraSession.BeginTrans
    Else
        lDeliveryNum = UCase(Trim$(txtDeliveryNum.Text))
        OraSession.BeginTrans
        gsSqlStmt = "SELECT * FROM bni_dummy_withdrawal where d_del_no='" & Trim$(txtDeliveryNum.Text) & "'"
        Set dsDummy_Del = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 Then 'And dsDummy_Del.Recordcount > 0
        dsDummy_Del.MoveFirst
            Do While Not dsDummy_Del.EOF
                dsDummy_Del.Delete
                dsDummy_Del.MoveNext
            Loop
        Else
            iError = True
        End If
        
    End If
    'Insert records with the sequence
    iRecSaved = 0
    gsSqlStmt = "SELECT * FROM bni_dummy_withdrawal"
    Set dsDummy = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then 'And dsDummy.RECORDCOUNT > 0
        For i = 0 To 9
            If Trim$(txtDelDetQty1(i).Text) <> "" And Val(Trim$(txtDelDetQty1(i).Text)) > 0 Then
                dsDummy.AddNew
                        dsDummy.Fields("d_del_no").Value = lDeliveryNum
                        dsDummy.Fields("lr_num").Value = Val(txtLRNum.Text)
                        dsDummy.Fields("commodity_code").Value = Val(txtCommodityCode.Text)
                        dsDummy.Fields("owner_id").Value = Val(txtRecipientId.Text)
                        dsDummy.Fields("initial_id").Value = txtIntial.Text
                        dsDummy.Fields("supervisor").Value = cboSuper.Text
                        dsDummy.Fields("order_date").Value = Format$(txtDelDetOrderDate.Text, "mm/dd/yyyy")
                        dsDummy.Fields("order_no").Value = Left$(txtDelDetOrderNum.Text, 20)
                        dsDummy.Fields("transport_mode").Value = UCase$(cboDelDetTransMode.Text)
                        dsDummy.Fields("transport_no").Value = Left$(txtDelDetTransNum.Text, 20)
                        dsDummy.Fields("bill_to_customer").Value = Val(txtDelDetCustomerId.Text)
                        dsDummy.Fields("deliver_to").Value = Left$(txtDelDetDeliverTo.Text, 250)
                        dsDummy.Fields("carrier").Value = txtDelDetRemarks.Text
                        dsDummy.Fields("remarks").Value = txtRemarks.Text
                        dsDummy.Fields("bol").Value = txtDelDetBOL(i).Text
                        dsDummy.Fields("mark").Value = txtDelDetMark(i).Text
                        dsDummy.Fields("qty1").Value = Val(txtDelDetQty1(i).Text)
                        dsDummy.Fields("unit1").Value = txtDelDetUnit1(i).Text
                        dsDummy.Fields("qty2").Value = Val(txtDelDetQty2(i).Text)
                        dsDummy.Fields("unit2").Value = txtDelDetUnit2(i).Text
                        dsDummy.Fields("weight").Value = Val(txtDelDetWeight(i).Text)
                        dsDummy.Fields("wt_unit").Value = txtDelDetWeightUnit(i).Text
                        dsDummy.Fields("LOCATION_NOTE").Value = whse(i).Text
                        
                        dsDummy.Fields("COMMENTS").Value = comment.Text & " " & comment1.Text & " " & comment2.Text
                        
                        dsDummy.Fields("post_notes").Value = txtNotes.Text
                                                
                        dsDummy.Update
                        iRecSaved = iRecSaved + 1
            End If
        Next 'i
    Else
        iError = True
    End If
    If iError Then
        'Rollback transaction
        MsgBox "Error occured while saving to Dummy Cargo Delivery table. Changes are not saved.", vbExclamation, "Save Dummy Delivery"
        OraSession.Rollback
        Exit Sub
    Else
        'Commit transaction
        OraSession.CommitTrans
        txtDeliveryNum.Text = lDeliveryNum
    End If

End Sub

Private Sub txtDelDetBOL_GotFocus(Index As Integer)
    Call FillBOL
    Call ShowDetailLine(Index)
End Sub


Private Sub txtDelDetBOL_LostFocus(Index As Integer)
    Dim i As Integer
    Dim iFound As Integer
    
    If Trim$(txtDelDetBOL(Index).Text) <> "" Then
        iFound = False
        For i = 0 To lstDelDetBOL.ListCount - 1
            If txtDelDetBOL(Index).Text = lstDelDetBOL.List(i) Then
                iFound = True
            End If
        Next
        
        If Not iFound Then
            MsgBox "Cargo BOL is not valid. Please select from the list.", vbInformation, "Cargo BOL"
        End If
        
    End If
    
End Sub


Private Sub txtDelDetCustomerId_LostFocus()
    If Trim$(txtDelDetCustomerId) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtDelDetCustomerId.Text
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.Recordcount > 0 Then
            txtDelDetCustomerName.Text = dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
    End If
End Sub


Private Sub txtDelDetMark_GotFocus(Index As Integer)
    Call FillMark(Index)
    Call ShowDetailLine(Index)
End Sub

Private Sub cmdCommodityList_Click()
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Commodity List"
    frmPV.lstPV.clear
    
    gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE"
    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.Recordcount > 0 Then
        While Not dsCOMMODITY_PROFILE.EOF
            frmPV.lstPV.AddItem dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value & " : " & dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value
            dsCOMMODITY_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCommodityCode.Text = Left$(gsPVItem, iPos - 1)
            txtCommodityName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
End Sub

Private Sub cmdDelDetCustomerList_Click()
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Customer List"
    frmPV.lstPV.clear
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.Recordcount > 0 Then
        While Not dsCUSTOMER_PROFILE.EOF
            frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.Fields("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
            dsCUSTOMER_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtDelDetCustomerId.Text = Left$(gsPVItem, iPos - 1)
            txtDelDetCustomerName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
End Sub

Private Sub cmdRecipientList_Click()
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Customer List"
    frmPV.lstPV.clear
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.Recordcount > 0 Then
        While Not dsCUSTOMER_PROFILE.EOF
            frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.Fields("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
            dsCUSTOMER_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtRecipientId.Text = Left$(gsPVItem, iPos - 1)
            txtCustomerName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
End Sub

Private Sub FillBOL()
    Dim iFound As Integer
    Dim i As Integer
    
    lstDelDetBOL.clear
    
    If Trim$(txtLRNum) = "" Then
        Exit Sub
    End If
    
    If Trim$(txtRecipientId) = "" Then
        Exit Sub
    End If
    
    If Trim$(txtCommodityCode) = "" Then
        Exit Sub
    End If
    
    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text & " AND "
    gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1 AND "
    gsSqlStmt = gsSqlStmt & "RECIPIENT_ID = " & txtRecipientId.Text & " AND "
    gsSqlStmt = gsSqlStmt & "COMMODITY_CODE = " & txtCommodityCode.Text
    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.Recordcount > 0 Then
        While Not dsCARGO_MANIFEST.EOF
            If dsCARGO_MANIFEST.Fields("MANIFEST_STATUS").Value <> "HOLD" Then
                lstDelDetBOL.AddItem dsCARGO_MANIFEST.Fields("CARGO_BOL").Value
                dsCARGO_MANIFEST.MoveNext
            Else
                lstDelDetBOL.AddItem dsCARGO_MANIFEST.Fields("CARGO_BOL").Value & "(HOLD)"
                dsCARGO_MANIFEST.MoveNext
            End If
        Wend
    End If
    
    If lstDelDetBOL.ListCount = 1 Then
        txtDelDetBOL(giDelDetIndex).Text = lstDelDetBOL.List(0)
    End If
End Sub


Private Sub cmdVesselList_Click()
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Vessel List"
    frmPV.lstPV.clear
    
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.Recordcount > 0 Then
        While Not dsVESSEL_PROFILE.EOF
            frmPV.lstPV.AddItem dsVESSEL_PROFILE.Fields("LR_NUM").Value & " : " & dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value
            dsVESSEL_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtLRNum.Text = Left$(gsPVItem, iPos - 1)
            txtVesselName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
End Sub


Private Sub Form_Load()
   
    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    'Hide list boxes
    lstDelDetBOL.Visible = False
    lstDelDetMark.Visible = False
    
    lblStatus.Caption = "Logging to database..."
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
'    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    gsSqlStmt = "SELECT * FROM PERSONNEL WHERE EMPLOYEE_ID >7"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsPERSONNEL.Recordcount > 0 Then
        While Not dsPERSONNEL.EOF
            cboSuper.AddItem Trim$(UCase(dsPERSONNEL.Fields("EMPLOYEE_FIRST_NAME").Value))
            dsPERSONNEL.MoveNext
        Wend
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "fetch supervisor data"
        lblStatus.Caption = "Access to personnel table failed."
        Unload Me
    End If
    
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0
'    Unload Me
    
End Sub

Private Sub picDelDetIndex_Click()
    Dim iResponse As Integer
    Dim i As Integer
    
    iResponse = MsgBox("Do you want to delete this line.", vbQuestion + vbYesNo, "Delete")
    If iResponse = vbYes Then
        For i = giDelDetIndex To 8
            txtDelDetQty1(i).Text = txtDelDetQty1(i + 1).Text
            txtDelDetUnit1(i).Text = txtDelDetQty1(i + 1).Text
            txtDelDetQty2(i).Text = txtDelDetQty2(i + 1).Text
            txtDelDetUnit2(i).Text = txtDelDetQty2(i + 1).Text
            txtDelDetBOL(i).Text = txtDelDetBOL(i + 1).Text
            txtDelDetMark(i).Text = txtDelDetMark(i + 1).Text
        Next i
        
        txtDelDetQty1(9).Text = ""
        txtDelDetUnit1(9).Text = ""
        txtDelDetQty2(9).Text = ""
        txtDelDetUnit2(9).Text = ""
        txtDelDetBOL(9).Text = ""
        txtDelDetMark(9).Text = ""
        
        If giDelDetIndex >= 1 Then
            txtDelDetBOL(giDelDetIndex - 1).SetFocus
        Else
            txtDelDetBOL(0).SetFocus
        End If
    End If
End Sub

Private Sub txtCommodityCode_LostFocus()
    If Trim$(txtCommodityCode) <> "" Then
        gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & txtCommodityCode.Text
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.Recordcount > 0 Then
            txtCommodityName.Text = dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value
            
            If Val(txtCommodityCode.Text) = 5635 Or Val(txtCommodityCode.Text) = 4963 Then
                chkDelDetBill.Value = 1
            Else
                chkDelDetBill.Value = 0
            End If
            
        Else
            MsgBox "Commodity does not exist.", vbExclamation, "Commodity"
        End If
    End If
End Sub

Private Sub txtDelDetMark_LostFocus(Index As Integer)
    Dim i As Integer
    Dim iFound As Integer
    
    If Trim$(txtDelDetMark(Index).Text) <> "" Then
        iFound = False
        For i = 0 To lstDelDetMark.ListCount - 1
            If txtDelDetMark(Index).Text = lstDelDetMark.List(i) Then
                iFound = True
            End If
        Next
        
        If Not iFound Then
            MsgBox "Cargo Mark is not valid. Please select from the list.", vbInformation, "Cargo Mark"
        End If
    End If
End Sub

Private Sub txtDelDetOrderDate_GotFocus()
    If Trim$(txtDeliveryNum.Text) = "" Then
        If Trim$(txtDelDetOrderDate.Text) = "" Then
            Call SetDefaults
        End If
    End If
End Sub


Private Sub txtDelDetQty1_GotFocus(Index As Integer)
    Call ShowDetailLine(Index)
End Sub

Private Sub txtDelDetQty1_LostFocus(Index As Integer)
    If Trim$(txtLRNum.Text) = "" Then
        MsgBox "Please select a Vessel.", vbInformation, "Quantity 1"
        txtDelDetQty1(Index) = "0.00"
        Exit Sub
    End If
    
    If Trim$(txtRecipientId.Text) = "" Then
        MsgBox "Please select an Owner.", vbInformation, "Quantity 1"
        txtDelDetQty1(Index) = "0.00"
        Exit Sub
    End If
    
    If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Please select a Commodity.", vbInformation, "Quantity 1"
        txtDelDetQty1(Index) = "0.00"
        Exit Sub
    End If
    
    If Trim$(txtDelDetBOL(Index).Text) = "" Then
        MsgBox "Please select a BOL.", vbInformation, "Quantity 1"
        txtDelDetQty1(Index) = "0.00"
        Exit Sub
    End If
    
    If Trim$(txtDelDetMark(Index).Text) = "" Then
        MsgBox "Please select a Mark.", vbInformation, "Quantity 1"
        txtDelDetQty1(Index) = "0.00"
        Exit Sub
    End If
    
    'Get cargo manifest information
    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text & " AND "
    gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1 AND "
    gsSqlStmt = gsSqlStmt & "RECIPIENT_ID = " & txtRecipientId.Text & " AND "
    gsSqlStmt = gsSqlStmt & "COMMODITY_CODE = " & txtCommodityCode.Text & " AND "
    gsSqlStmt = gsSqlStmt & "CARGO_BOL = '" & txtDelDetBOL(Index).Text & "' AND "
    gsSqlStmt = gsSqlStmt & "CARGO_MARK = '" & txtDelDetMark(Index).Text & "'"
    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.Recordcount > 0 Then
        'Get cargo tracking detail
        gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value & "'"
        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.Recordcount > 0 Then
            If Val(txtDelDetQty1(Index).Text) > dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value Then
                MsgBox "Quantity can not be greater than " & dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value & ".", vbInformation, "Quantity 1"
                txtDelDetQty1(Index) = "0"
            End If
            'pawan    11_07_2001.........
            gsSqlStmt = "SELECT sum(qty1) qty FROM bni_dummy_withdrawal WHERE LR_NUM = " & Val(txtLRNum.Text)
            gsSqlStmt = gsSqlStmt & " and owner_id = " & Val(txtRecipientId.Text)
            gsSqlStmt = gsSqlStmt & " and bol = '" & txtDelDetBOL(Index).Text & "'"
            gsSqlStmt = gsSqlStmt & " and mark ='" & txtDelDetMark(Index).Text & "'"
            gsSqlStmt = gsSqlStmt & " and status is null"
            Set dsDummy_verify = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsDummy_verify.Recordcount > 0 Then
                If Not IsNull(dsDummy_verify.Fields("qty").Value) Then
                    status1.Text = dsDummy_verify.Fields("qty").Value
                Else
                    status1.Text = "0"
                End If
                If (Val(txtDelDetQty1(Index).Text) > (dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value - dsDummy_verify.Fields("qty").Value)) Then
                    MsgBox "Caution! You have already booked " & dsDummy_verify.Fields("qty").Value & " Out of Total " & dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value & ".", vbInformation, "Quantity 1"
                End If
            End If
            '..................11_07_2001
        End If
        
        txtDelDetUnit1(Index).Text = dsCARGO_MANIFEST.Fields("QTY1_UNIT").Value
        
        If Not IsNull(dsCARGO_MANIFEST.Fields("QTY2_EXPECTED").Value) Then
            txtDelDetQty2(Index).Text = dsCARGO_MANIFEST.Fields("QTY2_EXPECTED").Value * Val(txtDelDetQty1(Index).Text) / dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value
        End If
        
        If Not IsNull(dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value) Then
            txtDelDetUnit2(Index).Text = dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value
        End If
        
        txtDelDetWeight(Index).Text = Format$((Val(txtDelDetQty1(Index).Text) / dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value) * dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value, "#.00")
        
        If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value) Then
            txtDelDetWeightUnit(Index).Text = dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value
        End If
        
        If Not IsNull(dsCARGO_MANIFEST.Fields("LOCATION_NOTE")) Then
            whse(Index).Text = dsCARGO_MANIFEST.Fields("LOCATION_NOTE").Value
        End If
        
    End If
    Call CalcTotal
End Sub



Private Sub txtDelDetQty2_GotFocus(Index As Integer)
    Call ShowDetailLine(Index)
End Sub

Private Sub txtDelDetQty2_LostFocus(Index As Integer)
    Call CalcTotal
End Sub
Public Sub dummy_fill()
    Dim i As Integer
    Dim j As Integer
    Dim iDetIndex As Integer
    Call SetDefaults
    txtDeliveryNum.Text = UCase(Trim$(txtDeliveryNum.Text))
    gsSqlStmt = "SELECT * FROM bni_dummy_withdrawal where d_del_no='" & UCase(Trim$(txtDeliveryNum.Text)) & "'"
    Set dsDummy = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsDummy.Recordcount > 0 Then
        iDetIndex = 0
        If Not IsNull(dsDummy.Fields("LR_NUM").Value) Then
            txtLRNum.Text = dsDummy.Fields("LR_NUM").Value
        Else
            txtLRNum.Text = ""
        End If
        
        Call txtLRNum_LostFocus
        txtCommodityCode.Text = dsDummy.Fields("commodity_code").Value
        Call txtCommodityCode_LostFocus
        txtRecipientId.Text = dsDummy.Fields("owner_id").Value
        Call txtRecipientId_LostFocus
        If Not IsNull(dsDummy.Fields("initial_id").Value) Then
            txtIntial.Text = dsDummy.Fields("initial_id").Value
        Else
            txtIntial.Text = ""
        End If
        If Not IsNull(dsDummy.Fields("supervisor").Value) Then
            cboSuper.Text = dsDummy.Fields("supervisor").Value
        Else
            cboSuper.Text = ""
        End If
        If Not IsNull(dsDummy.Fields("order_date").Value) Then
            txtDelDetOrderDate.Text = Format$(dsDummy.Fields("order_date").Value, "mm/dd/yyyy")
        Else
            txtDelDetOrderDate.Text = ""
        End If
        If Not IsNull(dsDummy.Fields("order_no").Value) Then
            txtDelDetOrderNum.Text = dsDummy.Fields("order_no").Value
        Else
            txtDelDetOrderNum.Text = ""
        End If
        If Not IsNull(dsDummy.Fields("transport_mode").Value) Then
            cboDelDetTransMode.Text = UCase$(dsDummy.Fields("transport_mode").Value)
        Else
            cboDelDetTransMode.Text = ""
        End If
        If Not IsNull(dsDummy.Fields("transport_no").Value) Then
            txtDelDetTransNum.Text = dsDummy.Fields("transport_no").Value
        Else
            txtDelDetTransNum.Text = ""
        End If
        If Not IsNull(dsDummy.Fields("bill_to_customer").Value) Then
            txtDelDetCustomerId = Val(dsDummy.Fields("bill_to_customer").Value)
        Else
            txtDelDetCustomerId = ""
        End If
        Call txtDelDetCustomerId_LostFocus
        If Not IsNull(dsDummy.Fields("deliver_to").Value) Then
            txtDelDetDeliverTo.Text = dsDummy.Fields("deliver_to").Value
        Else
            txtDelDetDeliverTo.Text = ""
        End If
        If Not IsNull(dsDummy.Fields("carrier").Value) Then
            txtDelDetRemarks.Text = dsDummy.Fields("carrier").Value
        Else
            txtDelDetRemarks.Text = ""
        End If
        If Not IsNull(dsDummy.Fields("remarks").Value) Then
            txtRemarks.Text = dsDummy.Fields("remarks").Value
        Else
            txtRemarks.Text = ""
        End If
        
        If Not IsNull(dsDummy.Fields("post_notes").Value) Then
            txtNotes.Text = dsDummy.Fields("post_notes").Value
        Else
            txtNotes.Text = ""
        End If
        
        If Not IsNull(dsDummy.Fields("COMMENTS").Value) Then
            comment.Text = Left$(dsDummy.Fields("COMMENTS").Value, 79)
            comment1.Text = Mid$(dsDummy.Fields("COMMENTS").Value, 80, 159)
            comment2.Text = Mid$(dsDummy.Fields("COMMENTS").Value, 160, 239)
        Else
            comment.Text = ""
            comment1.Text = ""
            comment2.Text = ""
        End If
        
        Do While Not dsDummy.EOF
            'If Trim$(txtDelDetQty1(iDetIndex).Text) <> "" And Val(Trim$(txtDelDetQty1(i).Text)) > 0 Then
                        If Not IsNull(dsDummy.Fields("bol").Value) Then
                            txtDelDetBOL(iDetIndex).Text = dsDummy.Fields("bol").Value
                        Else
                            txtDelDetBOL(iDetIndex).Text = ""
                        End If
                        If Not IsNull(dsDummy.Fields("mark").Value) Then
                            txtDelDetMark(iDetIndex).Text = dsDummy.Fields("mark").Value
                        Else
                            txtDelDetMark(iDetIndex).Text = ""
                        End If
                        If Not IsNull(dsDummy.Fields("qty1").Value) Then
                            txtDelDetQty1(iDetIndex).Text = Val(dsDummy.Fields("qty1").Value)
                        Else
                            txtDelDetQty1(iDetIndex).Text = ""
                        End If
                        If Not IsNull(dsDummy.Fields("unit1").Value) Then
                            txtDelDetUnit1(iDetIndex).Text = dsDummy.Fields("unit1").Value
                        Else
                            txtDelDetUnit1(iDetIndex).Text = ""
                        End If
                        If Not IsNull(dsDummy.Fields("qty2").Value) Then
                            txtDelDetQty2(iDetIndex).Text = Val(dsDummy.Fields("qty2").Value)
                        Else
                            txtDelDetQty2(iDetIndex).Text = ""
                        End If
                        If Not IsNull(dsDummy.Fields("unit2").Value) Then
                            txtDelDetUnit2(iDetIndex).Text = dsDummy.Fields("unit2").Value
                        Else
                            txtDelDetUnit2(iDetIndex).Text = ""
                        End If
                        If Not IsNull(dsDummy.Fields("weight").Value) Then
                            txtDelDetWeight(iDetIndex).Text = Val(dsDummy.Fields("weight").Value)
                        Else
                            txtDelDetWeight(iDetIndex).Text = ""
                        End If
                        If Not IsNull(dsDummy.Fields("wt_unit").Value) Then
                            txtDelDetWeightUnit(iDetIndex).Text = dsDummy.Fields("wt_unit").Value
                        Else
                            txtDelDetWeightUnit(iDetIndex).Text = ""
                        End If
                        
                        If Not IsNull(dsDummy.Fields("LOCATION_NOTE").Value) Then
                            whse(iDetIndex).Text = dsDummy.Fields("LOCATION_NOTE").Value
                        Else
                            whse(iDetIndex).Text = ""
                        End If
                        
                        iDetIndex = iDetIndex + 1
                If iDetIndex > 9 Then
                    Exit Do
                End If
                dsDummy.MoveNext
            'End If
        Loop
        
    End If
    Call CalcTotal
    Call txtNotes_LostFocus
End Sub


Private Sub txtDeliveryNum_LostFocus()
    Dim i As Integer
    Dim j As Integer
    Dim iDetIndex As Integer
    Dim num As String
    Dim num1 As String
    
    Call SetDefaults
    If Trim$(txtDeliveryNum.Text) = "" Then
        Call ShowDetailLine(0)
        'cmdSaveDelivery.Enabled = True
        Exit Sub
    End If
    'Pawan ..... updation for dummy.....
    If dummy.Value = 1 Then
        Call dummy_fill
        Exit Sub
    End If
    num = UCase(Trim$(txtDeliveryNum.Text))
    num1 = Mid(num, 1, 2)
    If num1 = "DM" Then
        Call dummy_fill
        Exit Sub
    End If
    
    
    
    'pawan...........
    'Get cargo delivery details using delivery number, here we get LOT_NUM which is CONTAINER_NUM in cargo manifest
    gsSqlStmt = "SELECT * FROM CARGO_DELIVERY WHERE DELIVERY_NUM = " & txtDeliveryNum.Text & " AND "
    gsSqlStmt = gsSqlStmt & "SERVICE_CODE = 6200"
        Set dsCARGO_DELIVERY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY.Recordcount > 0 Then
        iDetIndex = 0
        Do While Not dsCARGO_DELIVERY.EOF
            'Now get the cargo manifest information
            gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = '" & dsCARGO_DELIVERY.Fields("LOT_NUM").Value & "' AND "
            gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1"
            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.Recordcount > 0 Then
                txtLRNum.Text = dsCARGO_MANIFEST.Fields("LR_NUM").Value
                Call txtLRNum_LostFocus
                txtRecipientId.Text = dsCARGO_MANIFEST.Fields("RECIPIENT_ID").Value
                Call txtRecipientId_LostFocus
                txtCommodityCode.Text = dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value
                Call txtCommodityCode_LostFocus
                If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_BOL").Value) Then
                    txtDelDetBOL(iDetIndex).Text = dsCARGO_MANIFEST.Fields("CARGO_BOL").Value
                End If
                txtDelDetMark(iDetIndex).Text = dsCARGO_MANIFEST.Fields("CARGO_MARK").Value
                txtDelDetUnit1(iDetIndex).Text = dsCARGO_MANIFEST.Fields("QTY1_UNIT").Value
                If Not IsNull(dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value) Then
                    txtDelDetUnit2(iDetIndex).Text = dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value
                End If
                If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value) Then
                    txtDelDetWeightUnit(iDetIndex).Text = dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value
                End If
                
                If Not IsNull(dsCARGO_MANIFEST.Fields("LOCATION_NOTE").Value) Then
                    whse(iDetIndex).Text = dsCARGO_MANIFEST.Fields("LOCATION_NOTE").Value
                End If
            End If

            'Get cargo activity details
            gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE LOT_NUM = '" & dsCARGO_DELIVERY.Fields("LOT_NUM").Value & "' AND "
            gsSqlStmt = gsSqlStmt & "ACTIVITY_NUM = " & dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value & " AND "
            gsSqlStmt = gsSqlStmt & "SERVICE_CODE = " & dsCARGO_DELIVERY.Fields("SERVICE_CODE").Value
            Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.Recordcount > 0 Then
                txtDelDetOrderDate.Text = Format$(dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value, "mm/dd/yyyy")
                If Not IsNull(dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value) Then
                    txtDelDetOrderNum.Text = dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value
                End If
                For i = 0 To 3
                    If UCase$(cboDelDetTransMode.List(i)) = UCase$(dsCARGO_DELIVERY.Fields("TRANSPORTATION_MODE").Value) Then
                        cboDelDetTransMode.ListIndex = i
                    End If
                Next
                If Not IsNull(dsCARGO_DELIVERY.Fields("TRANSPORTATION_NUM").Value) Then
                    txtDelDetTransNum.Text = dsCARGO_DELIVERY.Fields("TRANSPORTATION_NUM").Value
                End If
                If Not IsNull(dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value) Then
                    txtDelDetCustomerId.Text = dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
                    Call txtDelDetCustomerId_LostFocus
                End If
                If Not IsNull(dsCARGO_DELIVERY.Fields("DELIVER_TO").Value) Then
                    txtDelDetDeliverTo.Text = dsCARGO_DELIVERY.Fields("DELIVER_TO").Value
                End If
                If Not IsNull(dsCARGO_DELIVERY.Fields("DELIVERY_DESCRIPTION").Value) Then
                    txtDelDetRemarks.Text = dsCARGO_DELIVERY.Fields("DELIVERY_DESCRIPTION").Value
                End If
                If Not IsNull(dsCARGO_ACTIVITY.Fields("SUPER_ID")) Then
                    cboSuper.Text = Trim$(dsCARGO_ACTIVITY.Fields("SUPER_ID").Value)
                Else
                    cboSuper.Text = ""
                End If
                txtDelDetQty1(iDetIndex).Text = dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value
                txtDelDetWeight(iDetIndex).Text = Format$((dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value / dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value) * dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value, "#.00")
           End If
            
            'Get cargo activity extension details
            gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY_EXT WHERE LOT_NUM = " & dsCARGO_DELIVERY.Fields("LOT_NUM").Value & " AND "
            gsSqlStmt = gsSqlStmt & "ACTIVITY_NUM = " & dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value & " AND "
            gsSqlStmt = gsSqlStmt & "SERVICE_CODE = " & dsCARGO_DELIVERY.Fields("SERVICE_CODE").Value
            Set dsCARGO_ACTIVITY_EXT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY_EXT.Recordcount > 0 Then
                txtDelDetQty2(iDetIndex).Text = dsCARGO_ACTIVITY_EXT.Fields("QTY2").Value
                If dsCARGO_ACTIVITY_EXT.Fields("BILL").Value = "Y" Then
                    chkDelDetBill.Value = 1
                Else
                    chkDelDetBill.Value = 0
                End If
'                If dsCARGO_ACTIVITY_EXT.Fields("PRINTED").Value = "Y" Then
'                    cmdSaveDelivery.Enabled = False
'                End If
            End If
           'Get Cargo Delivery Remark Details
          gsSqlStmt = "Select * from Delivery_Remark where Delivery_Num = " & txtDeliveryNum.Text
          Set dsDelivery_Remark = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
          
          If IsNull(dsDelivery_Remark.Fields("Initial_ID")) Then
             txtIntial.Text = ""
          Else
             txtIntial.Text = dsDelivery_Remark.Fields("Initial_ID").Value
          End If
          If IsNull(dsDelivery_Remark.Fields("Remark")) Then
             txtRemarks.Text = ""
          Else
             txtRemarks.Text = dsDelivery_Remark.Fields("Remark").Value
          End If
          
          If IsNull(dsDelivery_Remark.Fields("post_notes")) Then
             txtNotes.Text = ""
          Else
             txtNotes.Text = dsDelivery_Remark.Fields("post_notes").Value
          End If
            
            iDetIndex = iDetIndex + 1
            If iDetIndex > 9 Then
                Exit Do
            End If
            dsCARGO_DELIVERY.MoveNext
        Loop
    End If
    
    Call ShowDetailLine(0)
    Call CalcTotal
    Call txtNotes_LostFocus
End Sub


Private Sub txtLRNum_LostFocus()
    If Trim$(txtLRNum) <> "" And IsNumeric(txtLRNum) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLRNum.Text
        Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.Recordcount > 0 Then
            txtVesselName.Text = dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value
        Else
            MsgBox "Vessel does not exist.", vbExclamation, "Vessel"
        End If
    End If
End Sub

Private Sub txtNotes_LostFocus()
    lblCharCount.Caption = Len(txtNotes.Text)
End Sub

Private Sub txtRecipientId_LostFocus()
    If Trim$(txtRecipientId) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtRecipientId.Text
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.Recordcount > 0 Then
            txtCustomerName.Text = dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
    End If
End Sub


Public Sub ShowDetailLine(aiIndex As Integer)
    giDelDetIndex = aiIndex
    picDelDetIndex.Top = txtDelDetQty1(aiIndex).Top
    cmdDelDetBOL.Top = txtDelDetQty1(aiIndex).Top
    cmdDelDetMark.Top = txtDelDetQty1(aiIndex).Top
    lstDelDetBOL.Top = cmdDelDetBOL.Top + cmdDelDetBOL.Height
    lstDelDetMark.Top = cmdDelDetMark.Top + cmdDelDetMark.Height
End Sub

Public Sub SetDefaults()
    Dim i As Integer
    
    txtDelDetOrderDate.Text = Format$(Now, "mm/dd/yyyy")
    txtDelDetOrderNum.Text = ""
    cboDelDetTransMode.ListIndex = 2
    txtDelDetTransNum.Text = ""
    txtDelDetCustomerId.Text = txtRecipientId.Text
    txtDelDetCustomerName.Text = txtCustomerName.Text
    txtDelDetDeliverTo.Text = ""
    txtDelDetRemarks.Text = ""
    chkDelDetBill.Value = 1
    txtIntial.Text = ""
    txtRemarks.Text = ""
    txtNotes.Text = ""
    
    For i = 0 To 9
        txtDelDetBOL(i).Text = ""
        txtDelDetMark(i).Text = ""
        txtDelDetQty1(i).Text = ""
        txtDelDetUnit1(i).Text = ""
        txtDelDetQty2(i).Text = ""
        txtDelDetUnit2(i).Text = ""
        txtDelDetWeight(i).Text = ""
        txtDelDetWeightUnit(i).Text = ""
        whse(i).Text = ""
    Next
End Sub

Public Sub FillMark(aiDetIndex As Integer)
    Dim iFound As Integer
    Dim i As Integer
    
    lstDelDetMark.clear
    
    If Trim$(txtLRNum) = "" Then
        Exit Sub
    End If
    
    If Trim$(txtRecipientId) = "" Then
        Exit Sub
    End If
    
    If Trim$(txtCommodityCode) = "" Then
        Exit Sub
    End If
        
    If Trim$(txtDelDetBOL(aiDetIndex).Text) = "" Then
        Exit Sub
    End If
    
    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text & " AND "
    gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1 AND "
    gsSqlStmt = gsSqlStmt & "RECIPIENT_ID = " & txtRecipientId.Text & " AND "
    gsSqlStmt = gsSqlStmt & "COMMODITY_CODE = " & txtCommodityCode.Text & " AND "
    gsSqlStmt = gsSqlStmt & "CARGO_BOL = '" & txtDelDetBOL(aiDetIndex).Text & "'"
    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.Recordcount > 0 Then
        While Not dsCARGO_MANIFEST.EOF
            lstDelDetMark.AddItem dsCARGO_MANIFEST.Fields("CARGO_MARK").Value
            dsCARGO_MANIFEST.MoveNext
        Wend
    End If
    
    If lstDelDetMark.ListCount = 1 Then
        txtDelDetMark(aiDetIndex).Text = lstDelDetMark.List(0)
    ElseIf lstDelDetMark.ListCount > 1 Then
        txtDelDetMark(aiDetIndex).Text = ""
    End If
End Sub

Public Sub CalcTotal()
    Dim i As Integer
    Dim dQty1Total As Double
    Dim dQty2Total As Double
    Dim dWeightTotal As Double
    
    dQty1Total = 0
    dQty2Total = 0
    dWeightTotal = 0
    For i = 0 To 9
        dQty1Total = dQty1Total + Val(txtDelDetQty1(i))
        dQty2Total = dQty2Total + Val(txtDelDetQty2(i))
        dWeightTotal = dWeightTotal + Val(txtDelDetWeight(i))
    Next
    txtDelDetQty1Total.Text = dQty1Total
    txtDelDetQty2Total.Text = dQty2Total
    txtDelDetWeightTotal.Text = dWeightTotal
End Sub

