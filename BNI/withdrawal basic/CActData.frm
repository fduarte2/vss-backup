VERSION 5.00
Begin VB.Form frmCActData 
   BackColor       =   &H00FFFFC0&
   Caption         =   "Cargo Activity Data Entry (Search By Delivery Number)"
   ClientHeight    =   9825
   ClientLeft      =   2235
   ClientTop       =   1845
   ClientWidth     =   12315
   LinkTopic       =   "Form1"
   ScaleHeight     =   9825
   ScaleWidth      =   12315
   Begin VB.CommandButton cmdLatDelNum 
      Caption         =   "Display Last Assigned Delivery#"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   240
      TabIndex        =   146
      Top             =   8280
      Width           =   2055
   End
   Begin VB.CommandButton cmdByCNum 
      Caption         =   "SEARCH BY &ORDER#"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   675
      Left            =   120
      TabIndex        =   145
      Top             =   7440
      Width           =   2295
   End
   Begin VB.TextBox txtNotes 
      Height          =   495
      Left            =   4320
      MaxLength       =   500
      MultiLine       =   -1  'True
      TabIndex        =   143
      Top             =   7920
      Width           =   7215
   End
   Begin VB.TextBox status1 
      Enabled         =   0   'False
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   11400
      TabIndex        =   141
      Top             =   3480
      Width           =   735
   End
   Begin VB.CheckBox dummy 
      BackColor       =   &H00FFFFC0&
      Caption         =   "From Dummy"
      Height          =   255
      Left            =   8880
      TabIndex        =   140
      Top             =   480
      Visible         =   0   'False
      Width           =   1455
   End
   Begin VB.CommandButton exit 
      Caption         =   "&Exit"
      Height          =   315
      Left            =   10080
      TabIndex        =   139
      Top             =   9000
      Width           =   1095
   End
   Begin VB.ComboBox cboSuper 
      Height          =   315
      Left            =   10920
      TabIndex        =   16
      Top             =   1200
      Width           =   1215
   End
   Begin VB.CommandButton cmdDelHis 
      Caption         =   "&History of Deleted W/O"
      Height          =   315
      Left            =   7560
      TabIndex        =   137
      Top             =   9000
      Width           =   1935
   End
   Begin VB.TextBox txtRemarks 
      Height          =   495
      Left            =   4320
      MultiLine       =   -1  'True
      TabIndex        =   34
      Top             =   2520
      Width           =   7215
   End
   Begin VB.TextBox txtIntial 
      Height          =   285
      Left            =   10920
      TabIndex        =   15
      Top             =   480
      Width           =   855
   End
   Begin VB.CommandButton cmdDeleteDelivery 
      Caption         =   "&Delete Delivery"
      Height          =   315
      Left            =   5520
      TabIndex        =   134
      Top             =   9000
      Width           =   1335
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
      TabIndex        =   132
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
      TabIndex        =   131
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
      Left            =   8820
      MaxLength       =   50
      TabIndex        =   130
      Top             =   7260
      Width           =   1755
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
      Left            =   9150
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
      ItemData        =   "CActData.frx":0000
      Left            =   2190
      List            =   "CActData.frx":0002
      TabIndex        =   129
      Top             =   3870
      Width           =   3075
   End
   Begin VB.ListBox lstDelDetBOL 
      Height          =   1230
      ItemData        =   "CActData.frx":0004
      Left            =   600
      List            =   "CActData.frx":0006
      TabIndex        =   127
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
      Width           =   2865
   End
   Begin VB.TextBox txtDelDetBOL 
      Height          =   315
      Index           =   0
      Left            =   450
      MaxLength       =   20
      TabIndex        =   43
      Top             =   3540
      Width           =   1185
   End
   Begin VB.CommandButton cmdDelDetMark 
      Height          =   315
      Left            =   4920
      Picture         =   "CActData.frx":0008
      Style           =   1  'Graphical
      TabIndex        =   128
      TabStop         =   0   'False
      Top             =   3540
      Width           =   345
   End
   Begin VB.CommandButton cmdDelDetBOL 
      Height          =   315
      Left            =   1650
      Picture         =   "CActData.frx":010A
      Style           =   1  'Graphical
      TabIndex        =   126
      TabStop         =   0   'False
      Top             =   3540
      Width           =   345
   End
   Begin VB.TextBox txtDelDetCustomerName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   7440
      MaxLength       =   40
      TabIndex        =   28
      Top             =   1200
      Width           =   3435
   End
   Begin VB.CommandButton cmdDelDetCustomerList 
      Height          =   315
      Left            =   7080
      Picture         =   "CActData.frx":020C
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
      Top             =   5310
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
      Left            =   4440
      MaxLength       =   30
      TabIndex        =   24
      Top             =   1200
      Width           =   1485
   End
   Begin VB.CommandButton cmdPrintWdl 
      Caption         =   "&Print Withdrawal"
      Height          =   315
      Left            =   2640
      TabIndex        =   124
      Top             =   9000
      Width           =   2175
   End
   Begin VB.PictureBox picDelDetIndex 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   240
      Left            =   180
      Picture         =   "CActData.frx":030E
      ScaleHeight     =   240
      ScaleWidth      =   240
      TabIndex        =   125
      TabStop         =   0   'False
      Top             =   3540
      Width           =   240
   End
   Begin VB.CommandButton cmdSaveDelivery 
      Caption         =   "&Save Delivery"
      Height          =   315
      Left            =   600
      TabIndex        =   123
      Top             =   9000
      Width           =   1335
   End
   Begin VB.TextBox txtDelDetRemarks 
      BackColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   4290
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
      ItemData        =   "CActData.frx":0410
      Left            =   3240
      List            =   "CActData.frx":0420
      Style           =   2  'Dropdown List
      TabIndex        =   22
      TabStop         =   0   'False
      Top             =   1200
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetOrderDate 
      Height          =   315
      Left            =   210
      MaxLength       =   10
      TabIndex        =   18
      Top             =   1200
      Width           =   1155
   End
   Begin VB.TextBox txtDelDetCustomerId 
      Height          =   315
      Left            =   6000
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
      Width           =   1755
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
      Top             =   3510
      Width           =   1155
   End
   Begin VB.CheckBox chkDelDetBill 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Generate Bill?"
      Height          =   285
      Left            =   8400
      TabIndex        =   33
      Top             =   1860
      Width           =   1965
   End
   Begin VB.TextBox txtDeliveryNum 
      BackColor       =   &H000000FF&
      Height          =   315
      Left            =   6960
      MaxLength       =   15
      TabIndex        =   14
      Top             =   480
      Width           =   1755
   End
   Begin VB.TextBox txtCommodityName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   2520
      MaxLength       =   40
      TabIndex        =   12
      Top             =   450
      Width           =   2955
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
      Picture         =   "CActData.frx":0443
      Style           =   1  'Graphical
      TabIndex        =   11
      TabStop         =   0   'False
      Top             =   450
      Width           =   345
   End
   Begin VB.CommandButton cmdRecipientList 
      Height          =   315
      Left            =   7980
      Picture         =   "CActData.frx":0545
      Style           =   1  'Graphical
      TabIndex        =   7
      TabStop         =   0   'False
      Top             =   60
      Width           =   345
   End
   Begin VB.CommandButton cmdVesselList 
      Height          =   315
      Left            =   2130
      Picture         =   "CActData.frx":0647
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
      Left            =   8370
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
   Begin VB.Label lblCOA 
      BackColor       =   &H00FFFFC0&
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
      Left            =   11160
      TabIndex        =   149
      Top             =   8520
      Width           =   495
   End
   Begin VB.Label Label4 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Given Juice COA:"
      Height          =   255
      Left            =   9480
      TabIndex        =   148
      Top             =   8520
      Width           =   1455
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
      Left            =   4320
      TabIndex        =   147
      Top             =   8520
      Width           =   1455
   End
   Begin VB.Label Label3 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Post Notes"
      Height          =   255
      Left            =   3360
      TabIndex        =   144
      Top             =   8040
      Width           =   855
   End
   Begin VB.Label Label2 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Booked"
      Height          =   255
      Left            =   11400
      TabIndex        =   142
      Top             =   3240
      Width           =   735
   End
   Begin VB.Label Label1 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Supervisor"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   10440
      TabIndex        =   138
      Top             =   960
      Width           =   855
   End
   Begin VB.Label lblRemarks 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Remarks"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4320
      TabIndex        =   136
      Top             =   2280
      Width           =   675
   End
   Begin VB.Label lblIntial 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Initial"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   10440
      TabIndex        =   135
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
      TabIndex        =   133
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
      Width           =   12165
   End
   Begin VB.Shape Shape1 
      BorderColor     =   &H80000002&
      BorderWidth     =   2
      Height          =   2355
      Left            =   120
      Top             =   840
      Width           =   12165
   End
   Begin VB.Label lblDelDetTransNum 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Trailer Lic Num"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4440
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
      Left            =   3240
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
      Left            =   6000
      TabIndex        =   25
      Top             =   930
      Width           =   1635
   End
   Begin VB.Label lblDelDetOrderNum 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFC0&
      Caption         =   "Order Number"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   1680
      TabIndex        =   19
      Top             =   930
      Width           =   1125
   End
   Begin VB.Label lblDeliveryNum 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Delivery Number"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   225
      Left            =   5520
      TabIndex        =   13
      Top             =   480
      Width           =   1365
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
      Left            =   0
      TabIndex        =   0
      Top             =   9480
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
Attribute VB_Name = "frmCActData"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cboSuper_LOSTFOCUS()
    Dim sSqlStmt As String
    
    sSqlStmt = "SELECT * FROM PERSONNEL WHERE EMPLOYEE_FIRST_NAME = '" & Trim$(cboSuper.Text) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
    If dsPERSONNEL.RECORDCOUNT <= 0 Then
        MsgBox "Supervisor Field Value Is Not Correct", vbInformation, "Supervisor"
        cboSuper.SetFocus
        cboSuper.Text = ""
    End If
End Sub

Private Sub cmdByCNum_Click()
    Me.Hide
    frmCActDataByCNum.Show
    Unload Me
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

Private Sub cmdDeleteDelivery_Click()
    Dim sPassword As String
    
'    If Trim$(txtDeliveryNum.Text) = "" Then
'        MsgBox "Please enter the Delivery Number before deleting.", vbInformation, "Delete Delivery"
'        Exit Sub
'    End If
    
'    frmPassw.Show
    frmPreDel.Show
    
End Sub

Private Sub cmdDelHis_Click()
    frmDelHistory.Show
End Sub

Private Sub cmdLatDelNum_Click()
    gsSqlStmt = "SELECT MAX(DELIVERY_NUM) THE_NUM FROM CARGO_DELIVERY"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    MsgBox "Latest Delivery Number Assigned:  " & dsSHORT_TERM_DATA.Fields("THE_NUM").Value, vbInformation, "Delivery Num"
End Sub

Private Sub cmdPrintWdl_Click()
    Dim dsCARGO_ACTIVITY As Object
    Dim dsCARGO_DELIVERY As Object
    Dim dsBILLING As Object
    Dim dsSERVICE_CATEGORY As Object
    Dim dsVOYAGE_CARGO As Object
    Dim dsVESSEL_PROFILE As Object
    Dim dsCARGO_MANIFEST As Object
    Dim dsCOMMODITY_PROFILE As Object
    Dim dsCUSTOMER_PROFILE As Object
    Dim dsCOUNTRY As Object
    Dim dsUNIT_CONVERSION As Object
    Dim dsSERVICE_RATE As Object
    
    Dim lRecCount As Long
    Dim lRecInserted As Long
    Dim iResponse As Integer
    Dim sSqlStmt As String
    Dim iErrOccured As Integer
    Dim iItemErr As Integer
    Dim dQtyPct As Double
    Dim iLogFileNum As Integer
    Dim sLogFileName As String
    Dim sOrderNum As String
    Dim sTransportationNum As String
    Dim sCustomerAddr1 As String
    Dim sCustomerAddr2 As String
    Dim sCustomerAddr3 As String
    Dim RemarkLength As Integer
    
    Dim Loaded_Weight As Double
    
    Dim DeliveryAddr As String
    Dim CRPosition1 As Long
    Dim CRPosition2 As Long
    Dim CRPosition3 As Long
    Dim CRPosition4 As Long

    Dim DeliveryAddr1 As String
    Dim DeliveryAddr2 As String
    Dim DeliveryAddr3 As String
    Dim DeliveryAddr4 As String
    Dim AddrLineLength As Integer
    Dim RemarkAddr As String
    Dim Remark1 As String
    Dim Remark2 As String
    Dim Remark3 As String
    
    Dim PostNotes As String
    Dim PostNotes1 As String
    Dim PostNotes1_1 As String
    Dim PostNotes1_2 As String
    Dim PostNotes2 As String
    Dim PostNotes2_1 As String
    Dim PostNotes2_2 As String
    Dim PostNotes3 As String
    Dim PostNotes3_1 As String
    Dim PostNotes3_2 As String
    Dim PostNotes4 As String
    Dim PostNotes4_1 As String
    Dim PostNotes4_2 As String
    Dim PostNotes5 As String
    Dim PostNotes5_1 As String
    Dim PostNotes5_2 As String
    Dim PostNotes6 As String
    Dim PostNotes6_1 As String
    Dim PostNotes6_2 As String
    
    Dim Total_Qty As Double
    Dim Total_Weight As Double
    Dim sLine As String

    Dim i
    Dim Num_Of_Lines
    
    'On Error GoTo Err_CargoBilling
    Printer.FontName = "TIMES NEW ROMAN"
    Printer.FontSize = 11
    iErrOccured = False
    lRecInserted = 0
    
    'Build SQL statement to get cargo delivery records
    sSqlStmt = "SELECT * FROM CARGO_DELIVERY WHERE DELIVERY_NUM = " & Trim$(txtDeliveryNum.Text) & " order by lot_num"
    Set dsCARGO_DELIVERY = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
  
    'Truck Loading - Bill, Truck Loading - No Bill, Truck Unloading - Bill, Truck Unloading - No Bill
    sSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE "
    sSqlStmt = sSqlStmt & "LOT_NUM = '" & dsCARGO_DELIVERY.Fields("LOT_NUM").Value & "' AND "
    sSqlStmt = sSqlStmt & "ACTIVITY_NUM = '" & dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value & "' AND "
    sSqlStmt = sSqlStmt & "SERVICE_CODE = '6200' "
  
    'Get Cargo Activity Quantity
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
    
     'Get from Voyage Cargo table based on lot number
    sSqlStmt = "SELECT * FROM VOYAGE_CARGO WHERE LOT_NUM = '" & dsCARGO_ACTIVITY.Fields("LOT_NUM").Value & "'"
    Set dsVOYAGE_CARGO = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)

  
    'Get from Vessel Profile table based on LR Num
    sSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '" & dsVOYAGE_CARGO.Fields("LR_NUM").Value & "'"
    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
           
    'Get from Cargo manifest table based on lot number
    sSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = " & dsCARGO_ACTIVITY.Fields("LOT_NUM").Value
    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
            
    'Get from Commodity table based on Commodity Code
    sSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value
    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)

    'Get from Customer table based on Customer Code
    sSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
    
    'Get from Customer table based on Customer Code
    sSqlStmt = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE = '" & dsCUSTOMER_PROFILE.Fields("COUNTRY_CODE").Value & "'"
    Set dsCOUNTRY = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
    
    'Get Remark details
    gsSqlStmt = "Select * from Delivery_Remark where Delivery_Num = " & txtDeliveryNum.Text
    Set dsDelivery_Remark = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
    'Construct the 4 lines of delivery addresss from the single field in the database
    If Not IsNull(dsCARGO_DELIVERY.Fields("DELIVER_TO").Value) Then
        DeliveryAddr = dsCARGO_DELIVERY.Fields("DELIVER_TO").Value
        
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
    Else
        DeliveryAddr1 = ""
        DeliveryAddr2 = ""
        DeliveryAddr3 = ""
        DeliveryAddr4 = ""
    End If
    
    Printer.Height = 1240 * 8.5 'Twips 1440 per inch
    Printer.Width = 1240 * 10.75 'Twips 1440 per inch
    'Printer.FontSize = 10
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    
    'Add two blank lines, one before Delievery Number and one after  - LFW, 10/21/03
    Printer.Print ""
    
    'Line 1
    Printer.Print Tab(112); dsCARGO_DELIVERY.Fields("DELIVERY_NUM").Value
    
    Printer.Print ""
    Printer.Print ""
    
    If Not IsNull(dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value) Then
        sOrderNum = dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value
    Else
        sOrderNum = ""
    End If
    
    If Not IsNull(dsCARGO_DELIVERY.Fields("TRANSPORTATION_NUM").Value) Then
        sTransportationNum = dsCARGO_DELIVERY.Fields("TRANSPORTATION_NUM").Value
    Else
        sTransportationNum = ""
    End If
    
    Printer.Print ""
    Printer.Print ""
    
    If IsNull(dsDelivery_Remark.Fields("Initial_ID")) Then
       txtIntial.Text = "NONE"
    Else
       txtIntial.Text = dsDelivery_Remark.Fields("Initial_ID").Value
    End If
    
    'Printer.Print Tab(7); dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value; Tab(30); sOrderNum; Tab(60); sTransportationNum; Tab(120); txtIntial.Text;
    Printer.Print Tab(1); dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value; Tab(18); sOrderNum; Tab(47); sTransportationNum; Tab(102); txtIntial.Text;
   
    Printer.Print ""
    Printer.Print ""
    
    'Line 8
    Printer.Print Tab(12); DeliveryAddr1; Tab(72); dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
    
    'Line 10
    If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_ADDRESS1").Value) Then
        sCustomerAddr1 = dsCUSTOMER_PROFILE.Fields("CUSTOMER_ADDRESS1").Value
    Else
        sCustomerAddr1 = ""
    End If
    
    Printer.Print Tab(12); DeliveryAddr2; Tab(72); dsCUSTOMER_PROFILE.Fields("CUSTOMER_ADDRESS1").Value
    
    'Line 7
    If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_ADDRESS2").Value) Then
        sCustomerAddr2 = dsCUSTOMER_PROFILE.Fields("CUSTOMER_ADDRESS2").Value
    Else
        sCustomerAddr2 = ""
    End If
    
    If Trim$(sCustomerAddr2) <> "" Then
        Printer.Print Tab(12); DeliveryAddr3; Tab(72); sCustomerAddr2
    Else
        Printer.Print Tab(12); DeliveryAddr3;
    End If
       Printer.Print Tab(12); DeliveryAddr4; Tab(72); dsCUSTOMER_PROFILE.Fields("CUSTOMER_CITY").Value; ", "; dsCUSTOMER_PROFILE.Fields("CUSTOMER_STATE").Value; " "; dsCUSTOMER_PROFILE.Fields("CUSTOMER_ZIP").Value
   
    'Line 11
    If dsCOUNTRY.Fields("COUNTRY_CODE").Value = "US" Then
        Printer.Print ""
    Else
        Printer.Print Tab(72); dsCOUNTRY.Fields("COUNTRY_NAME")
    End If
    
    If DeliveryAddr3 = "" Then
      Printer.Print ""
    End If
    
    'Line 12
    If Not IsNull(dsCARGO_DELIVERY.Fields("DELIVERY_DESCRIPTION").Value) Then
        Printer.Print Tab(12); dsCARGO_DELIVERY.Fields("DELIVERY_DESCRIPTION").Value
    Else
        Printer.Print Tab(12); ""
    End If
    
    'Line 13
    Printer.Print ""
    'Printer.Print ""
    'Line 14
    Printer.Print Tab(12); dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME"); Tab(55); dsVESSEL_PROFILE.Fields("VESSEL_NAME")
    'Line 16
    Printer.Print ""
    'Line 15
    Printer.Print ""
    
    If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY.RECORDCOUNT > 0 Then
        Total_Qty = 0
        Total_Weight = 0
        Num_Of_Lines = 0
        
        Do While Not dsCARGO_DELIVERY.EOF
            sSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = " & dsCARGO_DELIVERY.Fields("LOT_NUM").Value
            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
            
            sSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE "
            sSqlStmt = sSqlStmt & "LOT_NUM = '" & dsCARGO_DELIVERY.Fields("LOT_NUM").Value & "' AND "
            sSqlStmt = sSqlStmt & "ACTIVITY_NUM = '" & dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value & "' AND "
            sSqlStmt = sSqlStmt & "SERVICE_CODE = '6200' "
            Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
            
            Loaded_Weight = dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value / dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value * dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value
            
            Total_Qty = Total_Qty + dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value
            Total_Weight = Total_Weight + Loaded_Weight
            
            'Printer.Print Tab(2); dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value; Tab(18); dsCARGO_MANIFEST.Fields("QTY1_UNIT").Value; Tab(35); dsCARGO_MANIFEST.Fields("CARGO_MARK").Value; Tab(92); dsCARGO_MANIFEST.Fields("CARGO_BOL").Value; Tab(108); Mid$(dsCARGO_MANIFEST.Fields("CARGO_LOCATION").Value, 1, 7); Tab(125); Format(Loaded_Weight, "##,###,###,##0.00")
            Printer.Print Tab(1); dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value; Tab(15); dsCARGO_MANIFEST.Fields("QTY1_UNIT").Value; Tab(25); dsCARGO_MANIFEST.Fields("CARGO_MARK").Value; Tab(79); dsCARGO_MANIFEST.Fields("CARGO_BOL").Value; Tab(95); Mid$(dsCARGO_MANIFEST.Fields("CARGO_LOCATION").Value, 1, 9); Tab(110); Format(Loaded_Weight, "##,###,###,##0.00") & " " & dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value
            
            sSqlStmt = "SELECT * FROM CARGO_ACTIVITY_EXT WHERE "
            sSqlStmt = sSqlStmt & "LOT_NUM = '" & dsCARGO_DELIVERY.Fields("LOT_NUM").Value & "' AND "
            sSqlStmt = sSqlStmt & "ACTIVITY_NUM = '" & dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value & "' AND "
            sSqlStmt = sSqlStmt & "SERVICE_CODE = '6200' "
            Set dsCARGO_ACTIVITY_EXT = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
            
            If (Not IsNull(dsCARGO_MANIFEST.Fields("QTY2_UNIT"))) And (Not IsNull(dsCARGO_ACTIVITY_EXT.Fields("QTY2").Value)) Then
                Printer.Print Tab(1); dsCARGO_ACTIVITY_EXT.Fields("QTY2").Value; Tab(13); dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value
                Num_Of_Lines = Num_Of_Lines + 1
            End If
            
            dsCARGO_DELIVERY.DbMoveNext
            Num_Of_Lines = Num_Of_Lines + 1
        Loop
    End If
    
    
'    For i = 1 To 15 - (Num_Of_Lines)
'        Printer.Print ""
'    Next i
    
    For i = 1 To 10 - (Num_Of_Lines)
        Printer.Print ""
    Next i
    
    'Begin Post Notes
    
    Num_Of_Lines = 0
    Printer.Print Tab(23); "Post Notes : "
    Num_Of_Lines = Num_Of_Lines + 1
    
    'PostNotes = txtNotes.Text
    
    'Construct the 6 lines of post notes from the single field in the database
    If Not IsNull(txtNotes.Text) Then
        PostNotes = txtNotes.Text
        
        CRPosition1 = 1
        CRPosition2 = 1
        
        CRPosition2 = InStr(CRPosition1, PostNotes, Chr$(13))
        AddrLineLength = CRPosition2 - CRPosition1
        If AddrLineLength > 0 Then
            
            PostNotes1 = Mid$(PostNotes, CRPosition1, AddrLineLength)
            CRPosition1 = CRPosition2 + 2
            
            CRPosition2 = InStr(CRPosition1, PostNotes, Chr$(13))
            AddrLineLength = CRPosition2 - CRPosition1
            
            If AddrLineLength > 0 Then
                
                PostNotes2 = Mid$(PostNotes, CRPosition1, AddrLineLength)
                CRPosition1 = CRPosition2 + 2
            
                CRPosition2 = InStr(CRPosition1, PostNotes, Chr$(13))
                AddrLineLength = CRPosition2 - CRPosition1
                
                If AddrLineLength > 0 Then
                
                    PostNotes3 = Mid$(PostNotes, CRPosition1, AddrLineLength)
                    CRPosition1 = CRPosition2 + 2
            
                    CRPosition2 = InStr(CRPosition1, PostNotes, Chr$(13))
                    AddrLineLength = CRPosition2 - CRPosition1
                    
                    If AddrLineLength > 0 Then
                        PostNotes4 = Mid$(PostNotes, CRPosition1, AddrLineLength)
                        CRPosition1 = CRPosition2 + 2
            
                        CRPosition2 = InStr(CRPosition1, PostNotes, Chr$(13))
                        AddrLineLength = CRPosition2 - CRPosition1
                        
                      If AddrLineLength > 0 Then
                        PostNotes5 = Mid$(PostNotes, CRPosition1, AddrLineLength)
                        CRPosition1 = CRPosition2 + 2
            
                        CRPosition2 = InStr(CRPosition1, PostNotes, Chr$(13))
                        AddrLineLength = CRPosition2 - CRPosition1
                        
                        If AddrLineLength > 0 Then
                           PostNotes6 = Mid$(PostNotes, CRPosition1, AddrLineLength)
                        Else
                           PostNotes6 = Mid$(PostNotes, CRPosition1)
                        End If
                      Else
                        PostNotes5 = Mid$(PostNotes, CRPosition1)
                      End If
                    Else
                        PostNotes4 = Mid$(PostNotes, CRPosition1)
                    End If
                Else
                    PostNotes3 = Mid$(PostNotes, CRPosition1)
                End If
            Else
                PostNotes2 = Mid$(PostNotes, CRPosition1)
                
            End If
        Else
            PostNotes1 = Mid$(PostNotes, CRPosition1)
        End If
    Else
        PostNotes1_1 = ""
        PostNotes2_1 = ""
        PostNotes3_1 = ""
        PostNotes4_1 = ""
        PostNotes5_1 = ""
        PostNotes6_1 = ""
        PostNotes1_2 = ""
        PostNotes2_2 = ""
        PostNotes3_2 = ""
        PostNotes4_2 = ""
        PostNotes5_2 = ""
        PostNotes6_2 = ""
    End If
 
     'spliting
     
     If Len(PostNotes1) > 50 Then
       PostNotes1_1 = Mid$(PostNotes1, 1, 50)
       PostNotes1_2 = Mid$(PostNotes1, 51, Len(PostNotes1))
     Else
       PostNotes1_1 = PostNotes1
     End If
     
     If Len(PostNotes2) > 50 Then
       PostNotes2_1 = Mid$(PostNotes2, 1, 50)
       PostNotes2_2 = Mid$(PostNotes2, 51, Len(PostNotes2))
     Else
       PostNotes2_1 = PostNotes2
     End If
     
     If Len(PostNotes3) > 50 Then
       PostNotes3_1 = Mid$(PostNotes3, 1, 50)
       PostNotes3_2 = Mid$(PostNotes3, 51, Len(PostNotes3))
     Else
       PostNotes3_1 = PostNotes3
     End If
     
     If Len(PostNotes4) > 50 Then
       PostNotes4_1 = Mid$(PostNotes4, 1, 50)
       PostNotes4_2 = Mid$(PostNotes4, 51, Len(PostNotes4))
     Else
       PostNotes4_1 = PostNotes4
     End If
 
     If Len(PostNotes5) > 50 Then
       PostNotes5_1 = Mid$(PostNotes5, 1, 50)
       PostNotes5_2 = Mid$(PostNotes5, 51, Len(PostNotes5))
     Else
       PostNotes5_1 = PostNotes5
     End If
 
     If Len(PostNotes6) > 50 Then
       PostNotes6_1 = Mid$(PostNotes6, 1, 50)
       PostNotes6_2 = Mid$(PostNotes6, 51, Len(PostNotes6))
     Else
       PostNotes6_1 = PostNotes6
     End If
 
     'printing
     
     Printer.Print Tab(23); PostNotes1_1
      If (PostNotes1_2 <> "") Then
         Printer.Print Tab(23); PostNotes1_2
         Num_Of_Lines = Num_Of_Lines + 1
      End If
      
     Printer.Print Tab(23); PostNotes2_1
      If (PostNotes2_2 <> "") Then
         Printer.Print Tab(23); PostNotes2_2
         Num_Of_Lines = Num_Of_Lines + 1
      End If
     
     Printer.Print Tab(23); PostNotes3_1
      If (PostNotes3_2 <> "") Then
         Printer.Print Tab(23); PostNotes3_2
         Num_Of_Lines = Num_Of_Lines + 1
      End If
     
     Printer.Print Tab(23); PostNotes4_1
      If (PostNotes4_2 <> "") Then
         Printer.Print Tab(23); PostNotes4_2
         Num_Of_Lines = Num_Of_Lines + 1
      End If
     
     Printer.Print Tab(23); PostNotes5_1
      If (PostNotes5_2 <> "") Then
         Printer.Print Tab(23); PostNotes5_2
         Num_Of_Lines = Num_Of_Lines + 1
      End If
     
     Printer.Print Tab(23); PostNotes6_1
      If (PostNotes6_2 <> "") Then
         Printer.Print Tab(23); PostNotes6_2
         Num_Of_Lines = Num_Of_Lines + 1
      End If
     
     Num_Of_Lines = Num_Of_Lines + 6

 
 
'  If Len(PostNotes) <> 0 Then
'    If Len(PostNotes) <= 100 Then
'       CRPosition1 = 50
'       PostNotes1 = Mid$(PostNotes, 1, CRPosition1)
'       If Len(PostNotes) > 50 Then
'         PostNotes2 = Right$(PostNotes, Len(PostNotes) - CRPosition1 + 1)
'       End If
'       Printer.Print Tab(23); PostNotes1
'       Num_Of_Lines = Num_Of_Lines + 1
'       If PostNotes2 <> "" Then
'         Printer.Print Tab(23); PostNotes2
'         Num_Of_Lines = Num_Of_Lines + 1
'       End If
'    ElseIf Len(PostNotes) <= 200 Then
'       CRPosition1 = 50
'       CRPosition2 = 100
'       PostNotes1 = Mid$(PostNotes, 1, CRPosition1)
'       PostNotes2 = Mid$(PostNotes, CRPosition1 + 1, 50)
'       PostNotes3 = Mid$(PostNotes, CRPosition2 + 1, 50)
'        If Len(PostNotes) > 150 Then
'         PostNotes4 = Right$(PostNotes, Len(PostNotes) - CRPosition1 - CRPosition2)
'        End If
'
'       Printer.Print Tab(23); PostNotes1
'       Printer.Print Tab(23); PostNotes2
'       Num_Of_Lines = Num_Of_Lines + 2
'       If PostNotes3 <> "" Then
'         Printer.Print Tab(23); PostNotes3
'         Num_Of_Lines = Num_Of_Lines + 1
'       End If
'       If PostNotes4 <> "" Then
'        Printer.Print Tab(23); PostNotes4
'         Num_Of_Lines = Num_Of_Lines + 1
'       End If
'    ElseIf Len(PostNotes) <= 300 Then
'       CRPosition1 = 50
'       CRPosition2 = 100
'       CRPosition3 = 150
'       PostNotes1 = Mid$(PostNotes, 1, CRPosition1)
'       PostNotes2 = Mid$(PostNotes, CRPosition1 + 1, 50)
'       PostNotes3 = Mid$(PostNotes, CRPosition2 + 1, 50)
'       PostNotes4 = Mid$(PostNotes, CRPosition1 + CRPosition2 + 1, 50)
'       PostNotes5 = Mid$(PostNotes, CRPosition1 + CRPosition3 + 1, 50)
'       If Len(PostNotes) > 250 Then
'         PostNotes6 = Right$(PostNotes, Len(PostNotes) - CRPosition3 - CRPosition2)
'       End If
'       Printer.Print Tab(23); PostNotes1
'       Printer.Print Tab(23); PostNotes2
'       Printer.Print Tab(23); PostNotes3
'       Printer.Print Tab(23); PostNotes4
'       Num_Of_Lines = Num_Of_Lines + 4
'       If PostNotes5 <> "" Then
'         Printer.Print Tab(23); PostNotes5
'         Num_Of_Lines = Num_Of_Lines + 1
'       End If
'       If PostNotes6 <> "" Then
'         Printer.Print Tab(23); PostNotes6
'         Num_Of_Lines = Num_Of_Lines + 1
'       End If
'    ElseIf Len(PostNotes) <= 400 Then
'       CRPosition1 = 50
'       CRPosition2 = 100
'       CRPosition3 = 150
'       PostNotes1 = Mid$(PostNotes, 1, CRPosition1)
'       PostNotes2 = Mid$(PostNotes, CRPosition1 + 1, 50)
'       PostNotes3 = Mid$(PostNotes, CRPosition2 + 1, 50)
'       PostNotes4 = Mid$(PostNotes, CRPosition1 + CRPosition2 + 1, 50)
'       PostNotes5 = Mid$(PostNotes, CRPosition1 + CRPosition3 + 1, 50)
'       PostNotes6 = Mid$(PostNotes, CRPosition2 + CRPosition3 + 1, 50)
'       PostNotes7 = Mid$(PostNotes, CRPosition1 + CRPosition2 + CRPosition3 + 1, 50)
'
'       If Len(PostNotes) > 350 Then
'         PostNotes8 = Right$(PostNotes, Len(PostNotes) - CRPosition3 - CRPosition2 - CRPosition1 - 50)
'       End If
'
'       Printer.Print Tab(23); PostNotes1
'       Printer.Print Tab(23); PostNotes2
'       Printer.Print Tab(23); PostNotes3
'       Printer.Print Tab(23); PostNotes4
'       Printer.Print Tab(23); PostNotes5
'       Printer.Print Tab(23); PostNotes6
'
'       Num_Of_Lines = Num_Of_Lines + 6
'       If PostNotes7 <> "" Then
'         Printer.Print Tab(23); PostNotes7
'         Num_Of_Lines = Num_Of_Lines + 1
'       End If
'       If PostNotes8 <> "" Then
'         Printer.Print Tab(23); PostNotes8
'         Num_Of_Lines = Num_Of_Lines + 1
'       End If
'    End If
'  End If
    
  
    'End Post Notes
    If txtCommodityCode.Text = 5031 Or txtCommodityCode.Text = 5033 Or txtCommodityCode.Text = 5098 Then
        Printer.FontBold = True
        Printer.FontSize = 14
    
        Printer.Print Tab(18); "***Drivers are responsible to confirm trailer temperature"
        Printer.Print Tab(18); "requirements with their Dispatcher***"
       
        Num_Of_Lines = Num_Of_Lines + 2
        
        If lblCOA.Caption = "YES" Then
            Printer.FontSize = 12
            Printer.Print
            Printer.Print Tab(20); "DRIVER HAS BEEN GIVEN COA; ALL DOCUMENTS RECEIVED AT TIME OF CHECK"
            Printer.Print Tab(20); "OUT MUST BE PRESENTED AT TIME OF DELIVERY OR LOAD WILL BE REFUSED."
            
            Num_Of_Lines = Num_Of_Lines + 2
        End If
           
    
        Printer.FontSize = 11
        
        Printer.FontBold = False
    End If
        
    
'    For i = 1 To 9 - (Num_Of_Lines)
'        Printer.Print ""
'    Next i
    
    For i = 1 To 14 - (Num_Of_Lines)
        Printer.Print ""
    Next i
    
    'Added 3 blank lines -LFW, 10/21/03
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    
    Printer.Print Tab(1); Total_Qty; Tab(110); Format(Total_Weight, "##,###,###,##0.00") & " " & dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value
    Printer.Print ""
    Printer.Print ""
    
    If IsNull(dsDelivery_Remark.Fields("Remark")) Then
        txtRemarks.Text = "NONE"
    Else
        RemarkAddr = dsDelivery_Remark.Fields("Remark").Value
    End If
       
    CRPosition1 = 1
    CRPosition2 = 1
    
    If Len(RemarkAddr) <= 80 Then
       Remark1 = RemarkAddr
       Printer.Print Tab(1); Remark1
    ElseIf Len(RemarkAddr) <= 150 Then
       CRPosition1 = InStr(71, RemarkAddr, " ")
       If CRPosition1 = 0 Then
         CRPosition1 = 1
       End If
       Remark1 = Mid$(RemarkAddr, 1, CRPosition1 - 1)
       Remark2 = Right$(RemarkAddr, Len(RemarkAddr) - CRPosition1 - 1)
       Printer.Print Tab(1); Remark1
       Printer.Print Tab(1); Remark2
    Else
       CRPosition1 = InStr(71, RemarkAddr, " ")
       If CRPosition1 = 0 Then
         CRPosition1 = 1
       End If
       Remark1 = Mid$(RemarkAddr, 1, CRPosition1 - 1)
       CRPosition2 = InStr(140, RemarkAddr, " ")
       If CRPosition2 = 0 Then
         CRPosition2 = 1
       End If
       Remark2 = Mid$(RemarkAddr, CRPosition1 + 1, CRPosition2 - CRPosition1 - 1)
       Remark3 = Right$(RemarkAddr, Len(RemarkAddr) - CRPosition2 + 1)
       Printer.Print Tab(1); Remark1
       Printer.Print Tab(1); Remark2
       Printer.Print Tab(1); Remark3
    End If
 
  'Printer.Print Chr$(12) 'Form Feed
    Printer.EndDoc
End Sub

Private Sub Command1_Click()

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
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
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
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE"
    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RECORDCOUNT > 0 Then
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
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
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
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
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
    
    lstDelDetBOL.Clear
    
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
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
        While Not dsCARGO_MANIFEST.EOF
            lstDelDetBOL.AddItem dsCARGO_MANIFEST.Fields("CARGO_BOL").Value
            dsCARGO_MANIFEST.MoveNext
        Wend
    End If
    
    If lstDelDetBOL.ListCount = 1 Then
        txtDelDetBOL(giDelDetIndex).Text = lstDelDetBOL.List(0)
    End If
End Sub

Private Sub cmdSaveDelivery_Click()
    Dim iResponse As Integer
    Dim lDeliveryNum As Long
    Dim lActivityNum As Long
    Dim i As Integer
    Dim iError As Integer
    Dim iRecSaved As Integer
    Dim lRecCount As Long
    Dim dummy_no As String
    Dim num As String
    Dim num1 As String
    Dim flag As Boolean
    flag = False
    'Lock all the required tables in exclusive mode, try 10 times
    'On Error Resume Next
    'For i = 0 To 9
    '    OraDatabase.LastServerErrReset
    '    gsSqlStmt = "LOCK TABLE CARGO_ACTIVITY IN EXCLUSIVE MODE NOWAIT"
    '    lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
    '    gsSqlStmt = "LOCK TABLE CARGO_ACTIVITY_EXT IN EXCLUSIVE MODE NOWAIT"
    '    lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
    '    gsSqlStmt = "LOCK TABLE CARGO_DELIVERY IN EXCLUSIVE MODE NOWAIT"
    '    lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
    '    gsSqlStmt = "LOCK TABLE CARGO_TRACKING IN EXCLUSIVE MODE NOWAIT"
    '    lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
    '    gsSqlStmt = "LOCK TABLE DELIVERY_REMARK IN EXCLUSIVE MODE NOWAIT"
    '    lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
    '    If OraDatabase.LastServerErr = 0 Then Exit For
    'Next 'i
    'If OraDatabase.LastServerErr <> 0 Then
    ''    OraDatabase.LastServerErr
     '   MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
    '    Exit Sub
    'End If
    On Error GoTo 0
    
    iError = False
    'pawan...11_07_2001... for dummy implementation...
    If dummy.Value = 1 Then
        dummy_no = UCase(Trim$(txtDeliveryNum.Text))
        txtDeliveryNum.Text = ""
        flag = True
    End If
    num = UCase(Trim$(txtDeliveryNum.Text))
    num1 = Mid(num, 1, 2)
    If num1 = "DM" Then
        dummy_no = UCase(Trim$(txtDeliveryNum.Text))
        txtDeliveryNum.Text = ""
        flag = True
    End If
    '............
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
    
    If Len(txtNotes.Text) > 400 Then
       MsgBox "Post Notes Length Should be Less than or equal to 400"
       Exit Sub
    End If

    
    If Trim$(cboSuper.Text) = "" Then
       MsgBox "Supervisor Field Can Be Null"
       Exit Sub
    End If
    
    If Trim$(txtDeliveryNum.Text) = "" Then
        OraSession.BeginTrans
        'Get the new max values, replace with the sequence later
        'gsSqlStmt = "SELECT MAX(DELIVERY_NUM) SEQVALUE FROM CARGO_DELIVERY"
        'Set dsCARGO_DELIVERY_MAX = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        'If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY_MAX.RecordCount > 0 Then
        
        'Sequence code is now used 09/03/98
'        gsSqlStmt = "SELECT CARGO_DELIVERY_SEQ.NEXTVAL SEQVALUE FROM DUAL"
        gsSqlStmt = "SELECT LAST_NUMBER FROM USER_SEQUENCES WHERE SEQUENCE_NAME = 'CARGO_DELIVERY_SEQ'"
        Set dsCARGO_DELIVERY_MAX = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY_MAX.RECORDCOUNT > 0 Then
            If IsNull(dsCARGO_DELIVERY_MAX.Fields("LAST_NUMBER").Value) Then
                lDeliveryNum = 1
            Else
                lDeliveryNum = dsCARGO_DELIVERY_MAX.Fields("LAST_NUMBER").Value + 1
            End If
        Else
            lDeliveryNum = 1
        End If
        
        'Begin a transaction
    Else
        'Inform User that You cannot save a withdrawal order twice
        MsgBox ("Sorry, you cannot change a withdrawal once it has been saved.  Request your supervisor to delete the order and re-enter")
        Exit Sub
        
        'Ask for user confirmation
        'iResponse = MsgBox("Any old delivery details will be replaced with new ones. Are you sure you want to save?", vbQuestion + vbYesNo, "Save Delivery")
        'If iResponse <> vbYes Then
        '    Exit Sub
        'End If
    
        'If Not IsNumeric(txtDeliveryNum.Text) Then
         '   MsgBox "Please enter a number in Delivery Number box.", vbInformation, "Save Delivery"
         '   Exit Sub
        'Else
        '    lDeliveryNum = txtDeliveryNum.Text
        '
        '    'Begin a transaction
        '    OraSession.BeginTrans
        '
        '    'Delete old transactions
        '    Call DeleteDelivery(lDeliveryNum)
        'End If
    End If
            
     
    
    'Insert records with the sequence
    iRecSaved = 0
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY_EXT"
    Set dsCARGO_ACTIVITY_EXT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    gsSqlStmt = "SELECT * FROM CARGO_DELIVERY"
    Set dsCARGO_DELIVERY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    'Added Delivery_Remark
    gsSqlStmt = "Select * from DElivery_Remark"
    Set dsDelivery_Remark = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    

    For i = 0 To 9
        If Trim$(txtDelDetQty1(i).Text) <> "" And Val(Trim$(txtDelDetQty1(i).Text)) > 0 Then
           'Get cargo manifest information
            gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text & " AND "
            gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1 AND "
            gsSqlStmt = gsSqlStmt & "RECIPIENT_ID = " & txtRecipientId.Text & " AND "
            gsSqlStmt = gsSqlStmt & "COMMODITY_CODE = " & txtCommodityCode.Text & " AND "
            gsSqlStmt = gsSqlStmt & "CARGO_BOL = '" & txtDelDetBOL(i).Text & "' AND "
            gsSqlStmt = gsSqlStmt & "CARGO_MARK = '" & txtDelDetMark(i).Text & "'"
            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
                'Get cargo tracking detail
                gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value & "'"
                Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.RECORDCOUNT > 0 Then
                    If Val(txtDelDetQty1(i).Text) > dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value Then
                        MsgBox "Quantity can not be greater than " & dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value & " at line " & i & " .", vbInformation, "Quantity 1"
                        iError = True
                    Else
                        'Get the new max value for Activity Number
                        gsSqlStmt = "SELECT MAX(ACTIVITY_NUM) FROM CARGO_ACTIVITY WHERE LOT_NUM = '" & dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value & "'"
                        Set dsCARGO_ACTIVITY_MAX = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                        If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY_MAX.RECORDCOUNT > 0 Then
                            If IsNull(dsCARGO_ACTIVITY_MAX.Fields("MAX(ACTIVITY_NUM)").Value) Then
                                lActivityNum = 1
                            Else
                                lActivityNum = dsCARGO_ACTIVITY_MAX.Fields("MAX(ACTIVITY_NUM)").Value + 1
                            End If
                        Else
                            lActivityNum = 1
                        End If
                        
                        dsCARGO_ACTIVITY.AddNew
                        dsCARGO_ACTIVITY.Fields("LOT_NUM").Value = dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value
                        dsCARGO_ACTIVITY.Fields("ACTIVITY_NUM").Value = lActivityNum
                        dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 6200
                        dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value = Val(txtDelDetQty1(i).Text)
                        dsCARGO_ACTIVITY.Fields("ACTIVITY_ID").Value = 4 'SUPER USER
                        dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value = Left$(txtDelDetOrderNum.Text, 20)
                        dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value = txtDelDetCustomerId.Text
                        dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value = Format$(txtDelDetOrderDate.Text, "mm/dd/yyyy")
                        dsCARGO_ACTIVITY.Update
                        
                        dsCARGO_ACTIVITY_EXT.AddNew
                        dsCARGO_ACTIVITY_EXT.Fields("LOT_NUM").Value = dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value
                        dsCARGO_ACTIVITY_EXT.Fields("ACTIVITY_NUM").Value = lActivityNum
                        dsCARGO_ACTIVITY_EXT.Fields("SERVICE_CODE").Value = 6200
                        dsCARGO_ACTIVITY_EXT.Fields("QTY2").Value = Val(txtDelDetQty2(i).Text)
                        If chkDelDetBill.Value = 1 Then
                            dsCARGO_ACTIVITY_EXT.Fields("BILL").Value = "Y"
                        End If
                        dsCARGO_ACTIVITY_EXT.Update
                        
                        dsCARGO_DELIVERY.AddNew
                        dsCARGO_DELIVERY.Fields("LOT_NUM").Value = dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value
                        dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value = lActivityNum
                        dsCARGO_DELIVERY.Fields("SERVICE_CODE").Value = 6200
                        dsCARGO_DELIVERY.Fields("TRANSPORTATION_MODE").Value = UCase$(cboDelDetTransMode.Text)
                        dsCARGO_DELIVERY.Fields("TRANSPORTATION_NUM").Value = Left$(txtDelDetTransNum.Text, 20)
                        dsCARGO_DELIVERY.Fields("DELIVERY_NUM").Value = lDeliveryNum
                        dsCARGO_DELIVERY.Fields("DELIVER_TO").Value = Left$(txtDelDetDeliverTo.Text, 250)
                        dsCARGO_DELIVERY.Fields("DELIVERY_ID").Value = 4 'SUPER USER
                        dsCARGO_DELIVERY.Fields("DELIVERY_DESCRIPTION").Value = Left$(txtDelDetRemarks.Text, 200)
                        dsCARGO_DELIVERY.Update
                        
                        dsCARGO_TRACKING.Edit
                            dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value - Val(txtDelDetQty1(i).Text)
                        dsCARGO_TRACKING.Update
                                            
                        iRecSaved = iRecSaved + 1
                    End If
                End If
            Else
                iError = True
            End If
        End If
    Next 'i
    
    dsDelivery_Remark.AddNew
    dsDelivery_Remark.Fields("Delivery_Num").Value = lDeliveryNum
    dsDelivery_Remark.Fields("Remark").Value = txtRemarks.Text
    dsDelivery_Remark.Fields("post_notes").Value = txtNotes.Text
    dsDelivery_Remark.Fields("Initial_ID").Value = txtIntial.Text
    dsDelivery_Remark.Update
    'pawan...11_07_2001... for dummy implementation...
    If flag = True Then
        stmt = "SELECT * FROM bni_dummy_withdrawal where d_del_no='" & dummy_no & "'"
        Set dsDummy = OraDatabase.DbCreateDynaset(stmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsDummy.RECORDCOUNT > 0 Then
            dsDummy.MoveFirst
            Do While Not dsDummy.EOF
                dsDummy.Edit
                    dsDummy.Fields("status").Value = "Y"
                    dsDummy.Update
                dsDummy.MoveNext
            Loop
        End If
    End If
    '............
    If iError Then
        'Rollback transaction
        MsgBox "Error occured while saving to Cargo Delivery table. Changes are not saved.", vbExclamation, "Save Delivery"
        OraSession.RollBack
        Exit Sub
    Else
        'Commit transaction, and increment sequence value if successful
        gsSqlStmt = "SELECT CARGO_DELIVERY_SEQ.NEXTVAL SEQVALUE FROM DUAL"
        Set dsCARGO_DELIVERY_MAX = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        OraSession.CommitTrans
        txtDeliveryNum.Text = lDeliveryNum
    End If
  
    Call ShowDetailLine(0)
End Sub

Private Sub cmdVesselList_Click()
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Vessel List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RECORDCOUNT > 0 Then
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
'    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/orcl", 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    gsSqlStmt = "SELECT * FROM PERSONNEL WHERE EMPLOYEE_ID >7"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsPERSONNEL.RECORDCOUNT > 0 Then
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
        If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RECORDCOUNT > 0 Then
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
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
        'Get cargo tracking detail
        gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value & "'"
        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.RECORDCOUNT > 0 Then
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
            If OraDatabase.LastServerErr = 0 Then  'And dsDummy_verify.RECORDCOUNT > 0
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
    If OraDatabase.LastServerErr = 0 And dsDummy.RECORDCOUNT > 0 Then
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
        If Not IsNull(dsDummy.Fields("coa").Value) Then
            lblCOA.Caption = "YES"
        Else
            lblCOA.Caption = "NO"
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
    txtDelDetOrderDate.Text = Format$(Date, "mm/dd/yyyy")
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
        cmdSaveDelivery.Enabled = True
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
    gsSqlStmt = gsSqlStmt & "SERVICE_CODE = 6200 order by lot_num"
    Set dsCARGO_DELIVERY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY.RECORDCOUNT > 0 Then
        iDetIndex = 0
        Do While Not dsCARGO_DELIVERY.EOF
            'Now get the cargo manifest information
            gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = '" & dsCARGO_DELIVERY.Fields("LOT_NUM").Value & "' AND "
            gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1"
            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
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
            End If

            'Get cargo activity details
            gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE LOT_NUM = '" & dsCARGO_DELIVERY.Fields("LOT_NUM").Value & "' AND "
            gsSqlStmt = gsSqlStmt & "ACTIVITY_NUM = " & dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value & " AND "
            gsSqlStmt = gsSqlStmt & "SERVICE_CODE = " & dsCARGO_DELIVERY.Fields("SERVICE_CODE").Value
            Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.RECORDCOUNT > 0 Then
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
            If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY_EXT.RECORDCOUNT > 0 Then
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
    'txtDelDetOrderDate.Text = Format$(Date, "mm/dd/yyyy")
    Call ShowDetailLine(0)
    Call CalcTotal
    Call txtNotes_LostFocus
End Sub


Private Sub txtLRNum_LostFocus()
    If Trim$(txtLRNum) <> "" And IsNumeric(txtLRNum) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLRNum.Text
        Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RECORDCOUNT > 0 Then
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
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
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
    Next
End Sub

Public Sub FillMark(aiDetIndex As Integer)
    Dim iFound As Integer
    Dim i As Integer
    
    lstDelDetMark.Clear
    
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
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
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

