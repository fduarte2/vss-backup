VERSION 5.00
Begin VB.Form frmCargoTrans 
   BorderStyle     =   3  'Fixed Dialog
   ClientHeight    =   9750
   ClientLeft      =   2130
   ClientTop       =   1365
   ClientWidth     =   15660
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   9750
   ScaleWidth      =   15660
   ShowInTaskbar   =   0   'False
   Begin VB.CheckBox chknostorageprint 
      Caption         =   "Check1"
      Height          =   315
      Left            =   10680
      TabIndex        =   126
      Top             =   3120
      Width           =   255
   End
   Begin VB.TextBox txtStrgCustValid 
      Height          =   315
      Left            =   8880
      TabIndex        =   123
      Top             =   2760
      Width           =   1095
   End
   Begin VB.TextBox txtBillCustValid 
      Height          =   315
      Left            =   8880
      TabIndex        =   122
      Top             =   2280
      Width           =   1095
   End
   Begin VB.TextBox txtNewRecipientIdValid 
      Height          =   315
      Left            =   8880
      TabIndex        =   121
      Top             =   1740
      Width           =   1095
   End
   Begin VB.CommandButton QTYbtn 
      Caption         =   "?"
      Height          =   315
      Index           =   7
      Left            =   14880
      TabIndex        =   120
      Top             =   7680
      Width           =   615
   End
   Begin VB.CommandButton QTYbtn 
      Caption         =   "?"
      Height          =   315
      Index           =   6
      Left            =   14880
      TabIndex        =   119
      Top             =   7200
      Width           =   615
   End
   Begin VB.CommandButton QTYbtn 
      Caption         =   "?"
      Height          =   315
      Index           =   5
      Left            =   14880
      TabIndex        =   118
      Top             =   6720
      Width           =   615
   End
   Begin VB.CommandButton QTYbtn 
      Caption         =   "?"
      Height          =   315
      Index           =   4
      Left            =   14880
      TabIndex        =   117
      Top             =   6240
      Width           =   615
   End
   Begin VB.CommandButton QTYbtn 
      Caption         =   "?"
      Height          =   315
      Index           =   3
      Left            =   14880
      TabIndex        =   116
      Top             =   5760
      Width           =   615
   End
   Begin VB.CommandButton QTYbtn 
      Caption         =   "?"
      Height          =   315
      Index           =   2
      Left            =   14880
      TabIndex        =   115
      Top             =   5280
      Width           =   615
   End
   Begin VB.CommandButton QTYbtn 
      Caption         =   "?"
      Height          =   315
      Index           =   1
      Left            =   14880
      TabIndex        =   114
      Top             =   4800
      Width           =   615
   End
   Begin VB.CommandButton QTYbtn 
      Caption         =   "?"
      Height          =   315
      Index           =   0
      Left            =   14880
      TabIndex        =   113
      Top             =   4320
      Width           =   615
   End
   Begin VB.CommandButton cmdRetrieve 
      Caption         =   "Retrieve"
      Height          =   375
      Left            =   11760
      TabIndex        =   112
      Top             =   2400
      Width           =   1455
   End
   Begin VB.TextBox txtTransferNum 
      Appearance      =   0  'Flat
      BackColor       =   &H00E0E0E0&
      Height          =   315
      Left            =   11760
      TabIndex        =   110
      Top             =   1800
      Width           =   1455
   End
   Begin VB.CommandButton cmdClear 
      Caption         =   "Clear"
      Height          =   375
      Left            =   10560
      TabIndex        =   109
      Top             =   8520
      Width           =   1575
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "Print"
      Height          =   375
      Left            =   8640
      TabIndex        =   108
      Top             =   8520
      Width           =   1695
   End
   Begin VB.TextBox txtInHouse 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   7
      Left            =   6240
      TabIndex        =   83
      Top             =   7680
      Width           =   1215
   End
   Begin VB.ComboBox cboBol 
      Height          =   315
      Index           =   7
      Left            =   240
      TabIndex        =   81
      Top             =   7680
      Width           =   1815
   End
   Begin VB.ComboBox cboMark 
      Height          =   315
      Index           =   7
      Left            =   2160
      TabIndex        =   82
      Top             =   7680
      Width           =   3975
   End
   Begin VB.TextBox txtTransQty1 
      Height          =   315
      Index           =   7
      Left            =   7560
      TabIndex        =   84
      Top             =   7680
      Width           =   1215
   End
   Begin VB.TextBox txtTransQty2 
      Height          =   315
      Index           =   7
      Left            =   9720
      TabIndex        =   86
      Top             =   7680
      Width           =   1575
   End
   Begin VB.TextBox txtWght 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   7
      Left            =   12240
      TabIndex        =   88
      Top             =   7680
      Width           =   1695
   End
   Begin VB.TextBox txtQty2Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   7
      Left            =   11400
      TabIndex        =   87
      Top             =   7680
      Width           =   735
   End
   Begin VB.TextBox txtQty1Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   7
      Left            =   8880
      TabIndex        =   85
      Top             =   7680
      Width           =   735
   End
   Begin VB.TextBox txtWghtUnit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   7
      Left            =   14040
      TabIndex        =   89
      Top             =   7680
      Width           =   735
   End
   Begin VB.TextBox txtInHouse 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   6
      Left            =   6240
      TabIndex        =   74
      Top             =   7200
      Width           =   1215
   End
   Begin VB.ComboBox cboBol 
      Height          =   315
      Index           =   6
      Left            =   240
      TabIndex        =   72
      Top             =   7200
      Width           =   1815
   End
   Begin VB.ComboBox cboMark 
      Height          =   315
      Index           =   6
      Left            =   2160
      TabIndex        =   73
      Top             =   7200
      Width           =   3975
   End
   Begin VB.TextBox txtTransQty1 
      Height          =   315
      Index           =   6
      Left            =   7560
      TabIndex        =   75
      Top             =   7200
      Width           =   1215
   End
   Begin VB.TextBox txtTransQty2 
      Height          =   315
      Index           =   6
      Left            =   9720
      TabIndex        =   77
      Top             =   7200
      Width           =   1575
   End
   Begin VB.TextBox txtWght 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   6
      Left            =   12240
      TabIndex        =   79
      Top             =   7200
      Width           =   1695
   End
   Begin VB.TextBox txtQty2Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   6
      Left            =   11400
      TabIndex        =   78
      Top             =   7200
      Width           =   735
   End
   Begin VB.TextBox txtQty1Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   6
      Left            =   8880
      TabIndex        =   76
      Top             =   7200
      Width           =   735
   End
   Begin VB.TextBox txtWghtUnit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   6
      Left            =   14040
      TabIndex        =   80
      Top             =   7200
      Width           =   735
   End
   Begin VB.TextBox txtInHouse 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   6240
      TabIndex        =   65
      Top             =   6720
      Width           =   1215
   End
   Begin VB.ComboBox cboBol 
      Height          =   315
      Index           =   5
      Left            =   240
      TabIndex        =   63
      Top             =   6720
      Width           =   1815
   End
   Begin VB.ComboBox cboMark 
      Height          =   315
      Index           =   5
      Left            =   2160
      TabIndex        =   64
      Top             =   6720
      Width           =   3975
   End
   Begin VB.TextBox txtTransQty1 
      Height          =   315
      Index           =   5
      Left            =   7560
      TabIndex        =   66
      Top             =   6720
      Width           =   1215
   End
   Begin VB.TextBox txtTransQty2 
      Height          =   315
      Index           =   5
      Left            =   9720
      TabIndex        =   68
      Top             =   6720
      Width           =   1575
   End
   Begin VB.TextBox txtWght 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   12240
      TabIndex        =   70
      Top             =   6720
      Width           =   1695
   End
   Begin VB.TextBox txtQty2Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   11400
      TabIndex        =   69
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtQty1Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   8880
      TabIndex        =   67
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtWghtUnit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   5
      Left            =   14040
      TabIndex        =   71
      Top             =   6720
      Width           =   735
   End
   Begin VB.TextBox txtInHouse 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   6240
      TabIndex        =   55
      Top             =   6240
      Width           =   1215
   End
   Begin VB.ComboBox cboBol 
      Height          =   315
      Index           =   4
      Left            =   240
      TabIndex        =   53
      Top             =   6240
      Width           =   1815
   End
   Begin VB.ComboBox cboMark 
      Height          =   315
      Index           =   4
      Left            =   2160
      TabIndex        =   54
      Top             =   6240
      Width           =   3975
   End
   Begin VB.TextBox txtTransQty1 
      Height          =   315
      Index           =   4
      Left            =   7560
      TabIndex        =   56
      Top             =   6240
      Width           =   1215
   End
   Begin VB.TextBox txtTransQty2 
      Height          =   315
      Index           =   4
      Left            =   9720
      TabIndex        =   59
      Top             =   6240
      Width           =   1575
   End
   Begin VB.TextBox txtWght 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   12240
      TabIndex        =   61
      Top             =   6240
      Width           =   1695
   End
   Begin VB.TextBox txtQty2Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   11400
      TabIndex        =   60
      Top             =   6240
      Width           =   735
   End
   Begin VB.TextBox txtQty1Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   8880
      TabIndex        =   58
      Top             =   6240
      Width           =   735
   End
   Begin VB.TextBox txtWghtUnit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   4
      Left            =   14040
      TabIndex        =   62
      Top             =   6240
      Width           =   735
   End
   Begin VB.TextBox txtInHouse 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   6240
      TabIndex        =   46
      Top             =   5760
      Width           =   1215
   End
   Begin VB.ComboBox cboBol 
      Height          =   315
      Index           =   3
      Left            =   240
      TabIndex        =   44
      Top             =   5760
      Width           =   1815
   End
   Begin VB.ComboBox cboMark 
      Height          =   315
      Index           =   3
      Left            =   2160
      TabIndex        =   45
      Top             =   5760
      Width           =   3975
   End
   Begin VB.TextBox txtTransQty1 
      Height          =   315
      Index           =   3
      Left            =   7560
      TabIndex        =   47
      Top             =   5760
      Width           =   1215
   End
   Begin VB.TextBox txtTransQty2 
      Height          =   315
      Index           =   3
      Left            =   9720
      TabIndex        =   49
      Top             =   5760
      Width           =   1575
   End
   Begin VB.TextBox txtWght 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   12240
      TabIndex        =   51
      Top             =   5760
      Width           =   1695
   End
   Begin VB.TextBox txtQty2Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   11400
      TabIndex        =   50
      Top             =   5760
      Width           =   735
   End
   Begin VB.TextBox txtQty1Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   8880
      TabIndex        =   48
      Top             =   5760
      Width           =   735
   End
   Begin VB.TextBox txtWghtUnit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   3
      Left            =   14040
      TabIndex        =   52
      Top             =   5760
      Width           =   735
   End
   Begin VB.TextBox txtInHouse 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   6240
      TabIndex        =   37
      Top             =   5280
      Width           =   1215
   End
   Begin VB.ComboBox cboBol 
      Height          =   315
      Index           =   2
      Left            =   240
      TabIndex        =   35
      Top             =   5280
      Width           =   1815
   End
   Begin VB.ComboBox cboMark 
      Height          =   315
      Index           =   2
      Left            =   2160
      TabIndex        =   36
      Top             =   5280
      Width           =   3975
   End
   Begin VB.TextBox txtTransQty1 
      Height          =   315
      Index           =   2
      Left            =   7560
      TabIndex        =   38
      Top             =   5280
      Width           =   1215
   End
   Begin VB.TextBox txtTransQty2 
      Height          =   315
      Index           =   2
      Left            =   9720
      TabIndex        =   40
      Top             =   5280
      Width           =   1575
   End
   Begin VB.TextBox txtWght 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   12240
      TabIndex        =   42
      Top             =   5280
      Width           =   1695
   End
   Begin VB.TextBox txtQty2Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   11400
      TabIndex        =   41
      Top             =   5280
      Width           =   735
   End
   Begin VB.TextBox txtQty1Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   8880
      TabIndex        =   39
      Top             =   5280
      Width           =   735
   End
   Begin VB.TextBox txtWghtUnit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   2
      Left            =   14040
      TabIndex        =   43
      Top             =   5280
      Width           =   735
   End
   Begin VB.TextBox txtInHouse 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   6240
      TabIndex        =   28
      Top             =   4800
      Width           =   1215
   End
   Begin VB.ComboBox cboBol 
      Height          =   315
      Index           =   1
      Left            =   240
      TabIndex        =   26
      Top             =   4800
      Width           =   1815
   End
   Begin VB.ComboBox cboMark 
      Height          =   315
      Index           =   1
      Left            =   2160
      TabIndex        =   27
      Top             =   4800
      Width           =   3975
   End
   Begin VB.TextBox txtTransQty1 
      Height          =   315
      Index           =   1
      Left            =   7560
      TabIndex        =   29
      Top             =   4800
      Width           =   1215
   End
   Begin VB.TextBox txtTransQty2 
      Height          =   315
      Index           =   1
      Left            =   9720
      TabIndex        =   31
      Top             =   4800
      Width           =   1575
   End
   Begin VB.TextBox txtWght 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   12240
      TabIndex        =   33
      Top             =   4800
      Width           =   1695
   End
   Begin VB.TextBox txtQty2Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   11400
      TabIndex        =   32
      Top             =   4800
      Width           =   735
   End
   Begin VB.TextBox txtQty1Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   8880
      TabIndex        =   30
      Top             =   4800
      Width           =   735
   End
   Begin VB.TextBox txtWghtUnit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   1
      Left            =   14040
      TabIndex        =   34
      Top             =   4800
      Width           =   735
   End
   Begin VB.TextBox txtInHouse 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   6240
      TabIndex        =   19
      Top             =   4320
      Width           =   1215
   End
   Begin VB.TextBox txtReferenceNum 
      Height          =   315
      Left            =   4440
      TabIndex        =   13
      Top             =   3240
      Width           =   3975
   End
   Begin VB.TextBox txtStrgCust 
      Height          =   315
      Left            =   4440
      TabIndex        =   11
      Top             =   2760
      Width           =   1095
   End
   Begin VB.TextBox txtStrgCustName 
      BackColor       =   &H80000004&
      Enabled         =   0   'False
      Height          =   315
      Left            =   5760
      TabIndex        =   12
      Top             =   2760
      Width           =   2655
   End
   Begin VB.TextBox txtService 
      BackColor       =   &H8000000F&
      Height          =   315
      Left            =   11760
      Locked          =   -1  'True
      TabIndex        =   14
      Top             =   360
      Width           =   1095
   End
   Begin VB.TextBox txtBillCustName 
      BackColor       =   &H80000004&
      Enabled         =   0   'False
      Height          =   315
      Left            =   5760
      TabIndex        =   10
      Top             =   2280
      Width           =   2655
   End
   Begin VB.TextBox txtBillCust 
      Height          =   315
      Left            =   4440
      TabIndex        =   9
      Top             =   2280
      Width           =   1095
   End
   Begin VB.TextBox txtDate 
      Height          =   315
      Left            =   11760
      TabIndex        =   15
      Top             =   840
      Width           =   1455
   End
   Begin VB.CheckBox chkBill 
      Caption         =   "  Bill Transfer "
      Height          =   255
      Left            =   11880
      TabIndex        =   16
      Top             =   1320
      Width           =   1335
   End
   Begin VB.TextBox txtNewCustname 
      BackColor       =   &H80000004&
      Enabled         =   0   'False
      Height          =   315
      Left            =   5760
      TabIndex        =   8
      Top             =   1740
      Width           =   2655
   End
   Begin VB.TextBox txtCommName 
      BackColor       =   &H80000004&
      Enabled         =   0   'False
      Height          =   315
      Left            =   5760
      TabIndex        =   6
      Top             =   1260
      Width           =   2655
   End
   Begin VB.TextBox txtOldCustName 
      BackColor       =   &H80000004&
      Enabled         =   0   'False
      Height          =   315
      Left            =   5760
      TabIndex        =   4
      Top             =   840
      Width           =   2655
   End
   Begin VB.TextBox txtShipName 
      BackColor       =   &H80000004&
      Enabled         =   0   'False
      Height          =   315
      Left            =   5760
      TabIndex        =   2
      Top             =   300
      Width           =   2655
   End
   Begin VB.TextBox txtWghtUnit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   14040
      TabIndex        =   25
      Top             =   4320
      Width           =   735
   End
   Begin VB.TextBox txtQty1Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   8880
      TabIndex        =   21
      Top             =   4320
      Width           =   735
   End
   Begin VB.TextBox txtQty2Unit 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   11400
      TabIndex        =   23
      Top             =   4320
      Width           =   735
   End
   Begin VB.TextBox txtWght 
      BackColor       =   &H8000000F&
      Enabled         =   0   'False
      Height          =   315
      Index           =   0
      Left            =   12240
      TabIndex        =   24
      Top             =   4320
      Width           =   1695
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "Save"
      Height          =   375
      Left            =   6600
      TabIndex        =   91
      Top             =   8520
      Width           =   1815
   End
   Begin VB.TextBox txtTransQty2 
      Height          =   315
      Index           =   0
      Left            =   9720
      TabIndex        =   22
      Top             =   4320
      Width           =   1575
   End
   Begin VB.TextBox txtTransQty1 
      Height          =   315
      Index           =   0
      Left            =   7560
      TabIndex        =   20
      Top             =   4320
      Width           =   1215
   End
   Begin VB.TextBox txtNewRecipientId 
      Height          =   315
      Left            =   4440
      TabIndex        =   7
      Top             =   1740
      Width           =   1095
   End
   Begin VB.ComboBox cboMark 
      Height          =   315
      Index           =   0
      Left            =   2160
      TabIndex        =   18
      Top             =   4320
      Width           =   3975
   End
   Begin VB.ComboBox cboBol 
      Height          =   315
      Index           =   0
      Left            =   240
      TabIndex        =   17
      Top             =   4320
      Width           =   1815
   End
   Begin VB.TextBox txtCommodityCode 
      Height          =   315
      Left            =   4440
      TabIndex        =   5
      Top             =   1260
      Width           =   1095
   End
   Begin VB.TextBox txtRecipientId 
      Height          =   315
      Left            =   4440
      TabIndex        =   3
      Top             =   840
      Width           =   1095
   End
   Begin VB.TextBox txtLRNum 
      Height          =   315
      Left            =   4440
      TabIndex        =   1
      Top             =   300
      Width           =   1095
   End
   Begin VB.Label txtstoragewarn 
      Caption         =   "Print ""No Storage"" on Printout"
      Height          =   255
      Left            =   11040
      TabIndex        =   127
      Top             =   3180
      Width           =   2175
   End
   Begin VB.Label Label21 
      Alignment       =   2  'Center
      Caption         =   "Entries"
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
      Left            =   8880
      TabIndex        =   125
      Top             =   1440
      Width           =   1095
   End
   Begin VB.Label Label20 
      Alignment       =   2  'Center
      Caption         =   "Verify"
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
      Left            =   8880
      TabIndex        =   124
      Top             =   1200
      Width           =   1095
   End
   Begin VB.Label Label19 
      Alignment       =   1  'Right Justify
      Caption         =   "Transfer Num :"
      Height          =   255
      Left            =   10320
      TabIndex        =   111
      Top             =   1800
      Width           =   1215
   End
   Begin VB.Label Label18 
      Alignment       =   1  'Right Justify
      Caption         =   "Qty In House"
      Height          =   255
      Left            =   6240
      TabIndex        =   107
      Top             =   4080
      Width           =   1095
   End
   Begin VB.Label Label17 
      Alignment       =   1  'Right Justify
      Caption         =   "Reference :"
      Height          =   255
      Left            =   3000
      TabIndex        =   106
      Top             =   3240
      Width           =   1215
   End
   Begin VB.Label Label16 
      Alignment       =   1  'Right Justify
      Caption         =   "Storage Bill To :"
      Height          =   255
      Left            =   2520
      TabIndex        =   105
      Top             =   2760
      Width           =   1695
   End
   Begin VB.Label Label15 
      Alignment       =   1  'Right Justify
      Caption         =   "Service :"
      Height          =   255
      Left            =   10800
      TabIndex        =   104
      Top             =   360
      Width           =   735
   End
   Begin VB.Label Label14 
      Alignment       =   1  'Right Justify
      Caption         =   "Transfer Fee Bill To :"
      Height          =   255
      Left            =   2520
      TabIndex        =   103
      Top             =   2280
      Width           =   1695
   End
   Begin VB.Label Label4 
      Alignment       =   1  'Right Justify
      Caption         =   "Effective Date :"
      Height          =   255
      Left            =   10320
      TabIndex        =   102
      Top             =   840
      Width           =   1335
   End
   Begin VB.Label Label13 
      Alignment       =   1  'Right Justify
      Caption         =   "Unit"
      Height          =   255
      Left            =   13800
      TabIndex        =   101
      Top             =   4080
      Width           =   735
   End
   Begin VB.Label Label12 
      Alignment       =   1  'Right Justify
      Caption         =   "Unit"
      Height          =   255
      Left            =   8880
      TabIndex        =   100
      Top             =   4080
      Width           =   495
   End
   Begin VB.Label Label11 
      Alignment       =   1  'Right Justify
      Caption         =   "Unit"
      Height          =   255
      Left            =   11520
      TabIndex        =   99
      Top             =   4080
      Width           =   495
   End
   Begin VB.Label Label10 
      Alignment       =   1  'Right Justify
      Caption         =   "Weight"
      Height          =   285
      Left            =   12480
      TabIndex        =   98
      Top             =   4080
      Width           =   735
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   375
      Left            =   0
      TabIndex        =   97
      Top             =   9360
      Width           =   15015
   End
   Begin VB.Label Label9 
      Alignment       =   1  'Right Justify
      Caption         =   "Qty2 Transfered"
      Height          =   255
      Left            =   9360
      TabIndex        =   96
      Top             =   4080
      Width           =   1695
   End
   Begin VB.Label Label8 
      Alignment       =   1  'Right Justify
      Caption         =   "Qty1 Transfered"
      Height          =   255
      Left            =   7560
      TabIndex        =   95
      Top             =   4080
      Width           =   1215
   End
   Begin VB.Label Label7 
      Alignment       =   1  'Right Justify
      Caption         =   "New Owner :"
      Height          =   285
      Left            =   2880
      TabIndex        =   94
      Top             =   1800
      Width           =   1335
   End
   Begin VB.Label Label6 
      Alignment       =   1  'Right Justify
      Caption         =   "Mark"
      Height          =   285
      Left            =   3720
      TabIndex        =   93
      Top             =   4080
      Width           =   735
   End
   Begin VB.Label Label5 
      Alignment       =   1  'Right Justify
      Caption         =   "BOL"
      Height          =   255
      Left            =   720
      TabIndex        =   92
      Top             =   4080
      Width           =   735
   End
   Begin VB.Label Label3 
      Alignment       =   1  'Right Justify
      Caption         =   "Commodity :"
      Height          =   255
      Left            =   3240
      TabIndex        =   90
      Top             =   1320
      Width           =   975
   End
   Begin VB.Label Label2 
      Alignment       =   1  'Right Justify
      Caption         =   "Original Owner :"
      Height          =   255
      Left            =   2880
      TabIndex        =   57
      Top             =   900
      Width           =   1335
   End
   Begin VB.Label Label1 
      Alignment       =   1  'Right Justify
      Caption         =   "Ship :"
      Height          =   255
      Left            =   3360
      TabIndex        =   0
      Top             =   360
      Width           =   855
   End
End
Attribute VB_Name = "frmCargoTrans"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Dim iDeliveryNum As Long
Dim iSaved As Boolean
Dim iError As Boolean
Dim iItemNum As Integer
Dim Total_Qty2 As Long
Dim i As Integer
Dim QTYavail(8) As Double
Dim QTYavailText(8) As String

Option Explicit


Private Sub clearBox()
    
    'cboBol.Clear
    'cboMark.Clear
    For i = 0 To 7
        cboBol(i).Text = ""
        cboMark(i).Text = ""
        txtTransQty1(i).Text = ""
        txtQty1Unit(i).Text = ""
        txtTransQty2(i).Text = ""
        txtQty2Unit(i).Text = ""
        txtWght(i).Text = ""
        txtWghtUnit(i).Text = ""
        txtInHouse(i).Text = ""
        QTYavail(i) = 0
        QTYavailText(i) = "No Data To Display"
        QTYbtn(i).Caption = "?"
    Next i
    txtNewCustname.Text = ""
    txtDate.Text = CStr(Format$(Now, "mm/dd/yyyy"))
    txtBillCust.Text = ""
    txtBillCustName.Text = ""
    chkBill.Value = 0
    chknostorageprint.Value = 0
    txtStrgCust.Text = ""
    txtStrgCustName.Text = ""
    txtReferenceNum.Text = ""
    txtNewRecipientId.Text = ""
    txtTransferNum.Text = ""
    txtNewRecipientIdValid.Text = ""
    txtBillCustValid.Text = ""
    txtStrgCustValid.Text = ""
    
    
End Sub

Private Sub cboBol_LostFocus(Index As Integer)

        gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text & " AND "
        gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1 AND "
        gsSqlStmt = gsSqlStmt & "RECIPIENT_ID = " & txtRecipientId.Text & " AND "
        gsSqlStmt = gsSqlStmt & "COMMODITY_CODE = " & txtCommodityCode.Text & " AND "
        gsSqlStmt = gsSqlStmt & "CARGO_BOL = '" & cboBol(Index).Text & "'"
        Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
            cboMark(Index).Clear        'clear the box first  -- LFW, 2/16/04
            QTYavail(Index) = 0
            QTYavailText(Index) = "No Data To Display"

            While Not dsCARGO_MANIFEST.EOF
                cboMark(Index).AddItem dsCARGO_MANIFEST.Fields("CARGO_MARK").Value
                dsCARGO_MANIFEST.MoveNext
            Wend
        End If
        'txtWght.Enabled = True
        
End Sub

Private Sub cboMark_LostFocus(Index As Integer)
    Dim RawInHouse As Double
' Dim QTYavail() As Double
'Dim QTYavailText() As String
   
    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text & " AND "
    gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1 AND "
    gsSqlStmt = gsSqlStmt & "RECIPIENT_ID = " & txtRecipientId.Text & " AND "
    gsSqlStmt = gsSqlStmt & "COMMODITY_CODE = " & txtCommodityCode.Text & " AND "
    gsSqlStmt = gsSqlStmt & "CARGO_BOL = '" & cboBol(Index).Text & "'"
    gsSqlStmt = gsSqlStmt & " AND CARGO_MARK = '" & cboMark(Index).Text & "'"
    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
        txtQty1Unit(Index).Text = Trim$(dsCARGO_MANIFEST.Fields("QTY1_UNIT").Value)
        
        If Not IsNull(dsCARGO_MANIFEST.Fields("QTY2_UNIT")) Then
            txtQty2Unit(Index).Text = Trim$(dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value)
        End If
        
        If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT")) Then
            txtWghtUnit(Index).Text = Trim$(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value)
        End If
        
        gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & Trim$(dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value) & "'"
        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.RECORDCOUNT > 0 Then
            ' start with STY_IN HOUSE...
            txtInHouse(Index).Text = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value
            QTYavail(Index) = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value
            QTYavailText(Index) = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value & " Currently showing In House." & vbCrLf & vbCrLf
        
            ' check if we need to decrease this by a Dummy value
            gsSqlStmt = "SELECT SUM(QTY1) THE_SUM, D_DEL_NO FROM BNI_DUMMY_WITHDRAWAL "
            gsSqlStmt = gsSqlStmt & "WHERE STATUS IS NULL AND "
            gsSqlStmt = gsSqlStmt & "LR_NUM = '" & txtLRNum.Text & "' AND "
            gsSqlStmt = gsSqlStmt & "OWNER_ID = " & txtRecipientId.Text & " AND "
            gsSqlStmt = gsSqlStmt & "COMMODITY_CODE = " & txtCommodityCode.Text & " AND "
            gsSqlStmt = gsSqlStmt & "BOL = '" & cboBol(Index).Text & "' AND "
            gsSqlStmt = gsSqlStmt & "MARK = '" & cboMark(Index).Text & "' "
            gsSqlStmt = gsSqlStmt & "GROUP BY D_DEL_NO"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            dsSHORT_TERM_DATA.MoveFirst
            If dsSHORT_TERM_DATA.RECORDCOUNT > 0 Then
                While Not dsSHORT_TERM_DATA.EOF
                    QTYavail(Index) = QTYavail(Index) - dsSHORT_TERM_DATA.Fields("THE_SUM").Value
                    QTYavailText(Index) = QTYavailText(Index) & "Less " & dsSHORT_TERM_DATA.Fields("THE_SUM").Value & ", Reserved on Pending Dummy " & dsSHORT_TERM_DATA.Fields("D_DEL_NO").Value & "." & vbCrLf
                    dsSHORT_TERM_DATA.MoveNext
                Wend
                QTYavailText(Index) = QTYavailText(Index) & vbCrLf
            End If
            
            
            If (dsCARGO_MANIFEST.Fields("MANIFEST_STATUS").Value <> "RELEASED") Then
                ' this is on hold, do NOT allow transferring
                QTYavail(Index) = 0
                QTYavailText(Index) = "This BoL / Mark combination is listed as " & dsCARGO_MANIFEST.Fields("MANIFEST_STATUS").Value & ".  Can Not Allow Transfers." & vbCrLf & vbCrLf
            End If
        
            QTYbtn(Index).Caption = "(" & QTYavail(Index) & ")"
        Else
            ' couldnt get a QTY, so show nothing
            txtInHouse(Index).Text = ""
        End If
        
    End If
        
End Sub



Private Sub cmdClear_Click()
Call clearBox
End Sub

Private Sub cmdPrint_Click()
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
    Dim dsCUSTOMER_PROFILE_A As Object
    Dim dsCARGO_MANIFEST_A As Object
    
    
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
    
    Dim Loaded_Weight As Double
    
    Dim DeliveryAddr As String
    Dim CRPosition1 As Long
    Dim CRPosition2 As Long
    Dim Total_Qty As Double
    Dim Total_Weight As Double
    Dim sLine As String

    Dim i
    Dim Num_Of_Lines
    
    'On Error GoTo Err_CargoBilling
    
    Printer.Orientation = 1
    iErrOccured = False
    lRecInserted = 0
    
    'check box
    If Trim$(txtLRNum.Text) = "" Then
        MsgBox "This field can not be null", vbInformation, "Ship Number"
        Exit Sub
    End If
    
    If Trim$(txtRecipientId.Text) = "" Then
        MsgBox "This field can not be null", vbInformation, "Customer Number"
        Exit Sub
    End If
   
    If Trim$(txtNewRecipientId.Text) = "" Then
        MsgBox "This field can not be null", vbInformation, "Customer Number"
        Exit Sub
    End If
    
    If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "This field can not be null", vbInformation, "Commodity Code"
        Exit Sub
    End If
    
    If Trim$(txtTransferNum.Text) = "" Then
        MsgBox "This field can not be null", vbInformation, "Transfer Number"
        Exit Sub
    End If
    
    If Trim$(txtReferenceNum.Text) = "" Then
        MsgBox "This field can not be null", vbInformation, "Reference"
        Exit Sub
    End If
    
    If Trim$(txtDate.Text) = "" Then
        MsgBox "This field can not be null", vbInformation, "As of Date"
        Exit Sub
    End If
    
    
    'Build SQL statement to get cargo delivery records
    sSqlStmt = "SELECT * FROM CARGO_DELIVERY WHERE DELIVERY_NUM = " & Val(Trim$(txtTransferNum.Text))
    Set dsCARGO_DELIVERY = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
    
    sSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID= " & Val(Trim$(txtNewRecipientId.Text))
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
  
    'Construct the 4 lines of delivery addresss from the single field in the database
    If Not IsNull(dsCARGO_DELIVERY.Fields("DELIVER_TO").Value) Then
        DeliveryAddr = dsCARGO_DELIVERY.Fields("DELIVER_TO").Value
    Else
        DeliveryAddr = ""
    End If
    
    Printer.FontSize = 18
    
    Printer.Print Tab(16); "CARGO TRANSFER CONFIRMATION"
    Printer.FontSize = 14
    Printer.Print Tab(25); " PORT OF WILMINGTON, DELAWARE"
    Printer.FontSize = 12
    Printer.Print Tab(45); " P.O. Box 1191"
    Printer.Print Tab(40); " Wilmington, DE 19801"
    Printer.Print Tab(45); " 302-571-4600"
    Printer.FontSize = 12
    Printer.Print ""
    Printer.Print Tab(80); "Date: " & Date
    Printer.Print ""
    Printer.Print ""
    
    If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_FAX")) Then
        Printer.Print Tab(5); "To: " & txtNewCustname.Text; Tab(80); "Fax#: " & Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_FAX"))
    Else
        Printer.Print Tab(5); "To: " & txtNewCustname.Text; Tab(80); "Fax#: " & "________________________"
    End If
    Printer.Print Tab(80); "Transfer #  : " & txtTransferNum.Text
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 11
    Printer.Print Tab(10); "  We have received a request from  " & txtOldCustName.Text & "   to transfer the"
    Printer.Print ""
    Printer.Print Tab(5); "following product, to the account of " & txtNewCustname.Text
    Printer.Print ""
    Printer.Print Tab(5); "as of " & txtDate.Text & "."
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 11
    Printer.Print Tab(5); " VESSEL"; Tab(41); "BOL"; Tab(50); "MARK"; Tab(80); "PALLETS"; Tab(93); "DRUM/BIN"; Tab(108); "WEIGHT"
    'Printer.Print ""
    Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------------------------------------------------------"
    i = 0
    Total_Qty = 0
    Total_Qty2 = 0
    Num_Of_Lines = 0
    Printer.FontSize = 10
    While Not dsCARGO_DELIVERY.EOF
    
        'Truck Loading - Bill, Truck Loading - No Bill, Truck Unloading - Bill, Truck Unloading - No Bill
        sSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE "
        sSqlStmt = sSqlStmt & "LOT_NUM = '" & Trim$(dsCARGO_DELIVERY.Fields("LOT_NUM").Value) & "' AND "
        sSqlStmt = sSqlStmt & "ACTIVITY_NUM = '" & dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value & "' AND "
        sSqlStmt = sSqlStmt & "SERVICE_CODE = '" & txtService.Text & "'"
        Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
        
        'Get from Voyage Cargo table based on lot number
        sSqlStmt = "SELECT * FROM VOYAGE_CARGO WHERE LOT_NUM = '" & dsCARGO_ACTIVITY.Fields("LOT_NUM").Value & "'"
        Set dsVOYAGE_CARGO = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)

        'Get from Vessel Profile table based on LR Num
        sSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Val(dsVOYAGE_CARGO.Fields("LR_NUM").Value)
        Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
           
        sSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = '" & Trim$(dsCARGO_DELIVERY.Fields("LOT_NUM").Value) & "'"
        Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
          
        'Get from Cargo manifest table based on lot number
        sSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & Val(dsVOYAGE_CARGO.Fields("LR_NUM").Value)
        sSqlStmt = sSqlStmt & " AND RECIPIENT_ID = " & Val(txtNewRecipientId.Text)
        sSqlStmt = sSqlStmt & " AND COMMODITY_CODE = " & Val(txtCommodityCode.Text)
        sSqlStmt = sSqlStmt & " AND CARGO_BOL = '" & Trim$(dsCARGO_MANIFEST.Fields("CARGO_BOL").Value) & "'"
        sSqlStmt = sSqlStmt & " AND (CARGO_MARK LIKE 'TR*%" & Trim$(Left$(Trim$(dsCARGO_MANIFEST.Fields("CARGO_MARK").Value), 50)) & "'"
        sSqlStmt = sSqlStmt & " OR CARGO_MARK LIKE '" & Trim$(Left$(Trim$(dsCARGO_MANIFEST.Fields("CARGO_MARK").Value), 50)) & "TR*%')" 'diorelle
        Set dsCARGO_MANIFEST_A = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
        dsCARGO_MANIFEST_A.MoveLast
        If Not IsNull(dsCARGO_MANIFEST_A.Fields("QTY2_EXPECTED")) And dsCARGO_MANIFEST_A.Fields("QTY2_EXPECTED").Value > 0 Then
            If Trim$(dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value) = "BIN" Then
                Printer.Print Tab(4); Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value); Tab(45); Trim$(dsCARGO_MANIFEST.Fields("CARGO_BOL").Value); Tab(53); Trim$(dsCARGO_MANIFEST.Fields("CARGO_MARK").Value); Tab(90); dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value; Tab(100); dsCARGO_MANIFEST_A.Fields("QTY2_EXPECTED").Value & " BIN"; Tab(115); dsCARGO_MANIFEST_A.Fields("CARGO_WEIGHT").Value & " " & Trim$(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value)
                Printer.Print " "
            ElseIf Trim$(dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value) = "DRUM" Then
                Printer.Print Tab(4); Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value); Tab(45); Trim$(dsCARGO_MANIFEST.Fields("CARGO_BOL").Value); Tab(53); Trim$(dsCARGO_MANIFEST.Fields("CARGO_MARK").Value); Tab(90); dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value; Tab(100); dsCARGO_MANIFEST_A.Fields("QTY2_EXPECTED").Value & " DRUM"; Tab(115); dsCARGO_MANIFEST_A.Fields("CARGO_WEIGHT").Value & " " & Trim$(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value)
                Printer.Print " "
            Else
                Printer.Print Tab(4); Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value); Tab(45); Trim$(dsCARGO_MANIFEST.Fields("CARGO_BOL").Value); Tab(53); Trim$(dsCARGO_MANIFEST.Fields("CARGO_MARK").Value); Tab(90); dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value; Tab(100); dsCARGO_MANIFEST_A.Fields("QTY2_EXPECTED").Value & " " & Trim$(dsCARGO_MANIFEST_A.Fields("QTY2_UNIT").Value); Tab(115); dsCARGO_MANIFEST_A.Fields("CARGO_WEIGHT").Value & " " & Trim$(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value)
                Printer.Print " "
            End If
        Else
            Printer.Print Tab(4); Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value); Tab(45); Trim$(dsCARGO_MANIFEST.Fields("CARGO_BOL").Value); Tab(53); Trim$(dsCARGO_MANIFEST.Fields("CARGO_MARK").Value); Tab(90); dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value
            Printer.Print " "
        End If
        
        Total_Qty = Total_Qty + dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value
        
        'Total_Qty2 = Total_Qty2 + Val(txtTransQty2.Text)
        
        Printer.Print Tab(10); " "
        
        i = i + 1
        Num_Of_Lines = Num_Of_Lines + 2
        dsCARGO_DELIVERY.MoveNext
        
    Wend
    Printer.Print ""
    Printer.Print ""
    'Printer.Print Tab(5); "ToTal: " & Total_Qty
    Printer.FontSize = 10
    For i = 1 To 14 - (Num_Of_Lines)
        Printer.Print Tab(10); " "
    Next i
    Printer.FontSize = 12
    Printer.Print Tab(10); "  This fax will serve as the confirmation of the transfer. A charge of $ 35.00 will appear on your"
    Printer.Print Tab(10); " "
    Printer.Print Tab(5); "next invoice for 'Transfer Charge'. Keep this copy for future records."
    Printer.Print Tab(10); " "
    Printer.Print Tab(10); " "
    Printer.Print Tab(10); " "
    Printer.FontSize = 14
    Printer.Print Tab(5); "POW Ref:    " & txtReferenceNum & "   "
    
    If chknostorageprint.Value = 1 Then
        Printer.FontSize = 16
        Printer.Print "DO NOT BILL STORAGE FOR THIS XFER."
    End If
    
    'Printer.Print Total_Qty; Tab(110); Format(Total_Weight, "##,###,###,##0.00")
    Printer.EndDoc
    
    lblStatus.Caption = "Done."
    
End Sub

Private Sub cmdRetrieve_Click()
  
  Dim iFirst As Boolean
  Dim iDeliveryCount As Long
  Dim lQty1Rcvd As Double
  Dim dOriginalWeight As Double
  Dim sMark As String
  Dim sMarkRcvd As String
  Dim ithTransfer As String
  Dim blnNormal As Boolean
  
  iFirst = True
  i = -1
  If Trim$(txtTransferNum.Text) <> "" Then
    If IsNumeric(txtTransferNum.Text) Then
        sSqlStmt = "SELECT * FROM CARGO_DELIVERY WHERE DELIVERY_NUM = " & Val(Trim$(txtTransferNum.Text))
        Set dsCARGO_DELIVERY = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY.RECORDCOUNT > 0 Then
            
            While Not dsCARGO_DELIVERY.EOF
                i = i + 1
                'Truck Loading - Bill, Truck Loading - No Bill, Truck Unloading - Bill, Truck Unloading - No Bill
                sSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE "
                sSqlStmt = sSqlStmt & "LOT_NUM = '" & Trim$(dsCARGO_DELIVERY.Fields("LOT_NUM").Value) & "' AND "
                sSqlStmt = sSqlStmt & "ACTIVITY_NUM = '" & dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value & "' AND "
                sSqlStmt = sSqlStmt & "SERVICE_CODE = " & dsCARGO_DELIVERY.Fields("SERVICE_CODE").Value
                Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                
                sSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = '" & Trim$(dsCARGO_DELIVERY.Fields("LOT_NUM").Value) & "'"
                Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                
                If iFirst Then
                    'Get from Voyage Cargo table based on lot number
                    sSqlStmt = "SELECT * FROM VOYAGE_CARGO WHERE LOT_NUM = '" & dsCARGO_ACTIVITY.Fields("LOT_NUM").Value & "'"
                    Set dsVOYAGE_CARGO = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
            
                    'Get from Vessel Profile table based on LR Num
                    sSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Val(dsVOYAGE_CARGO.Fields("LR_NUM").Value)
                    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                    
                    'GET NEW CUSTOMER NAME
                    sSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(dsCARGO_DELIVERY.Fields("DELIVER_TO").Value))
                    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                    
                    sSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(dsCARGO_MANIFEST.Fields("RECIPIENT_ID").Value))
                    Set dsCUSTOMER_PROFILE_A = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                   
                    'GET COMMODITY NAME
                    sSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Val(Trim$(dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value))
                    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                    
                    'GET BILLED CUSTOMER (added by Adam Walter, 4/17/06, to correct logic issue on display)
'                    sSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & Trim$(dsCARGO_DELIVERY.fields("LOT_NUM").Value) & "'"
'                    Set dsCARGO_TRACKING_B = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
'                    sSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(dsCARGO_TRACKING_B.fields("STORAGE_CUST_ID").Value))
'                    Set dsCUSTOMER_PROFILE_B = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                    
                    txtLRNum.Text = dsVOYAGE_CARGO.Fields("LR_NUM").Value
                    txtShipName.Text = Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value)
                    txtRecipientId.Text = dsCARGO_MANIFEST.Fields("RECIPIENT_ID").Value
                    txtOldCustName.Text = Trim$(dsCUSTOMER_PROFILE_A.Fields("CUSTOMER_NAME").Value)
                    txtNewRecipientId.Text = dsCARGO_DELIVERY.Fields("DELIVER_TO").Value
                    txtNewCustname.Text = Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value)
                    txtCommodityCode.Text = dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value
                    txtCommName.Text = Trim$(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value)
                    txtStrgCust.Text = dsCARGO_DELIVERY.Fields("DELIVER_TO").Value
                    txtStrgCustName.Text = Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value)
'                    txtStrgCust.Text = dsCARGO_TRACKING_B.fields("STORAGE_CUST_ID").Value
'                    txtStrgCustName.Text = Trim$(dsCUSTOMER_PROFILE_B.fields("CUSTOMER_NAME").Value)
                    txtService.Text = dsCARGO_DELIVERY.Fields("SERVICE_CODE").Value
                    txtReferenceNum.Text = Mid(Trim$(dsCARGO_DELIVERY.Fields("DELIVERY_DESCRIPTION").Value), 10, Len(Trim$(dsCARGO_DELIVERY.Fields("DELIVERY_DESCRIPTION").Value)))
                    txtDate.Text = dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value
                End If
                   
                'Get from Cargo manifest table based on lot number
                'sSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & Val(dsVOYAGE_CARGO.Fields("LR_NUM").Value)
                'sSqlStmt = sSqlStmt & " AND RECIPIENT_ID = " & Val(Trim$(dsCARGO_MANIFEST.Fields("RECIPIENT_ID").Value))
                'sSqlStmt = sSqlStmt & " AND COMMODITY_CODE = " & Val(Trim$(dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value))
                'sSqlStmt = sSqlStmt & " AND CARGO_BOL = '" & Trim$(dsCARGO_MANIFEST.Fields("CARGO_BOL").Value) & "'"
                'sSqlStmt = sSqlStmt & " AND CARGO_MARK = 'TR*" & Trim$(Left$(Trim$(dsCARGO_MANIFEST.Fields("CARGO_MARK").Value), 27)) & "'"
                'Set dsCARGO_MANIFEST_A = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                'dsCARGO_MANIFEST_A.MoveLast
                
                'GET QTY IN HOUSE and QTY Received, etc. -- LFW, 9/9/03
                sSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & Trim$(dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value) & "'"
                Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                
                'get qty2
                sSqlStmt = "SELECT * FROM CARGO_ACTIVITY_EXT WHERE LOT_NUM = '" & Trim$(dsCARGO_DELIVERY.Fields("LOT_NUM").Value) & "'"
                sSqlStmt = sSqlStmt & " AND ACTIVITY_NUM = '" & dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value & "' AND "
                sSqlStmt = sSqlStmt & "SERVICE_CODE = " & dsCARGO_DELIVERY.Fields("SERVICE_CODE").Value
                Set dsCARGO_ACTIVITY_EXT = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY_EXT.RECORDCOUNT > 0 Then
                    If iFirst Then
                        If Not IsNull(dsCARGO_ACTIVITY_EXT.Fields("BILL")) Then
                          
                          chkBill.Value = 1
                          
                          'GET BILL CUSTOMER
                          sSqlStmt = "SELECT * FROM BILLING WHERE BILLING_NUM = " & Val(Trim$(dsCARGO_ACTIVITY_EXT.Fields("BILLING_NUM").Value))
                          Set dsBILLING = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                          
                          'GET CUSTOMER NAME
                          sSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(dsBILLING.Fields("CUSTOMER_ID").Value))
                          Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                          
                          txtBillCust.Text = dsBILLING.Fields("CUSTOMER_ID").Value
                          txtBillCustName.Text = Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value)
                        End If
                          
                    End If
                Else
                    If iFirst Then
                        chkBill.Value = 0
                    End If
                End If
                
                cboBol(i).Text = dsCARGO_MANIFEST.Fields("CARGO_BOL").Value
                cboMark(i).Text = dsCARGO_MANIFEST.Fields("CARGO_MARK").Value
                txtInHouse(i).Text = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value
                txtTransQty1(i).Text = dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value
                txtQty1Unit(i).Text = dsCARGO_MANIFEST.Fields("QTY1_UNIT").Value
                
                If Not IsNull(dsCARGO_MANIFEST.Fields("QTY2_UNIT")) Then
                    txtQty2Unit(i).Text = dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value
                End If
                
                If Not IsNull(dsCARGO_ACTIVITY_EXT.Fields("QTY2")) Then
                    txtTransQty2(i).Text = dsCARGO_ACTIVITY_EXT.Fields("QTY2").Value
                End If
                
                If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT")) Then
                    txtWghtUnit(i).Text = Trim$(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value)
                End If
                
            'Start of Weight Calculation  -LFW, Added on 9/9/03
                'Get the original qty received, weight and mark, assuming it is not transferred cargo  - LFW, 1/7/03
                lQty1Rcvd = Val("" & dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value)
                dOriginalWeight = Val("" & dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value)
                sMark = "" & dsCARGO_MANIFEST.Fields("CARGO_MARK").Value
                sMarkRcvd = sMark
                            
                'Check if it is a normal record, which means that it has an unique
                '(lr_num, commodity_code, bol, and mark) tuple
                sSqlStmt = "SELECT * FROM CARGO_TRACKING CT, CARGO_MANIFEST CM " _
                        & "WHERE CT.LOT_NUM = CM.CONTAINER_NUM " _
                        & "AND CM.LR_NUM = " & dsCARGO_MANIFEST.Fields("LR_NUM").Value _
                        & " AND CM.COMMODITY_CODE = " & dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value _
                        & " AND CM.CARGO_BOL = '" & dsCARGO_MANIFEST.Fields("CARGO_BOL").Value _
                        & "' AND CM.CARGO_MARK = '" & sMark & "'"
                Set dsCARGO_ORIGINAL_MANIFEST = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)

                If dsCARGO_ORIGINAL_MANIFEST.RECORDCOUNT > 1 Then
                    blnNormal = False
                Else
                    blnNormal = True
                End If
                             
                'If it is a transfered cargo, original qty received, weight and mark should be those of the original owner
                If Val("" & InStr(sMark, "TR*")) = 1 And blnNormal = True Then
                    'Take off the TR*#* part (5 or 6 characters) to restore the original mark
                    'Assume cargos get transfered less than 100 times.  -LFW, 1/6/03
                    ithTransfer = Mid(sMarkRcvd, 4, 2)
                    If IsNumeric(ithTransfer) Then
                        sMarkRcvd = Trim(Mid(sMarkRcvd, 7))
                        blnNormal = True
                    Else
                        ithTransfer = Mid(sMarkRcvd, 4, 1)
                        If IsNumeric(ithTransfer) Then
                            sMarkRcvd = Trim(Mid(sMarkRcvd, 6))
                            blnNormal = True
                        Else
                            blnNormal = False
                        End If
                    End If
                    
                    If blnNormal = True Then
                        sSqlStmt = "SELECT CT.QTY_RECEIVED QTY1, CM.CARGO_WEIGHT WEIGHT " _
                            & "FROM CARGO_TRACKING CT, CARGO_MANIFEST CM WHERE CT.LOT_NUM = CM.CONTAINER_NUM " _
                            & "AND CM.LR_NUM = " & dsCARGO_MANIFEST.Fields("LR_NUM").Value _
                            & " AND CM.COMMODITY_CODE = " & dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value _
                            & " AND CM.CARGO_BOL = '" & dsCARGO_MANIFEST.Fields("CARGO_BOL").Value _
                            & "' AND CM.CARGO_MARK = '" & sMarkRcvd & "'"
                        Set dsCARGO_ORIGINAL_MANIFEST = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                    
                        'If there is only one record of the cargo of the original owner, get the quantity received
                        'Otherwise, switch back to quantity received and mark of the current record
                        If dsCARGO_ORIGINAL_MANIFEST.RECORDCOUNT = 1 And Val("" & dsCARGO_ORIGINAL_MANIFEST.Fields("QTY1").Value) > 0 Then
                            'So lQty1Rcvd will always be greater than 0
                            lQty1Rcvd = Val("" & dsCARGO_ORIGINAL_MANIFEST.Fields("QTY1").Value)
                            dOriginalWeight = Val("" & dsCARGO_ORIGINAL_MANIFEST.Fields("WEIGHT").Value)
                            blnNormal = True
                        Else
                            sMarkRcvd = sMark
                            blnNormal = False
                        End If
                    End If
                End If
                
                'Calculate total weight and weight left  - LFW, 1/6/03
                sSqlStmt = "SELECT CT.LOT_NUM, CT.DATE_RECEIVED DATE_RECEIVED, CM.CARGO_WEIGHT WEIGHT, CM.CARGO_MARK MARK " _
                        & "FROM CARGO_TRACKING CT, CARGO_MANIFEST CM WHERE CT.LOT_NUM = CM.CONTAINER_NUM " _
                        & "AND CM.LR_NUM = " & dsCARGO_MANIFEST.Fields("LR_NUM").Value _
                        & " AND CM.COMMODITY_CODE = " & dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value _
                        & " AND CM.CARGO_BOL = '" & dsCARGO_MANIFEST.Fields("CARGO_BOL").Value _
                        & "' AND CM.CARGO_MARK LIKE '%" & sMarkRcvd & "' ORDER BY CT.LOT_NUM"
                Set dsCARGO_ORIGINAL_MANIFEST = OraDatabase.DbCreateDynaset(sSqlStmt, 0&)
                         
                '1) Add weights only if it is a normal record.  Otherwise take its own weight as the original weight received.
                '   So when it is normal, original weight received is the sum of the weights of cargos with
                '   the same lr_num, commodity_code, bol, and with mark ending with the mark of the original one.
                '2) Since 10/26/2002, the weight, qty1 and qty2 of cargo transfered
                '   to a new customer will be subtracted from the original owner
                '3) Add only weights of transferred cargo because dOriginalWeight already
                '   have weight of the original owner
                If dsCARGO_ORIGINAL_MANIFEST.RECORDCOUNT > 0 Then
                    While Not dsCARGO_ORIGINAL_MANIFEST.EOF
                        If blnNormal = True And DateValue(dsCARGO_ORIGINAL_MANIFEST.Fields("DATE_RECEIVED").Value) >= DateValue("October 26, 2002") _
                            And Val("" & InStr(dsCARGO_ORIGINAL_MANIFEST.Fields("MARK").Value, "TR*")) = 1 Then
                            dOriginalWeight = dOriginalWeight + Val("" & dsCARGO_ORIGINAL_MANIFEST.Fields("WEIGHT").Value)
                        End If
                        dsCARGO_ORIGINAL_MANIFEST.MoveNext
                    Wend
                End If
            'End of Weight Calculation
                
                txtWght(i).Text = dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value / lQty1Rcvd * dOriginalWeight
                iFirst = False
                dsCARGO_DELIVERY.MoveNext
            Wend
        Else
            MsgBox "This delivery number not exist, try agian", vbInformation, "Retrieve"
            Exit Sub
        End If
    Else
        Exit Sub
    End If
  End If
End Sub

Private Sub cmdSave_Click()
    Dim sContainerNum As String
    Dim dContainerNum As Double
    Dim iAlreadyExists As Integer
    Dim iLocation As String
    Dim iImpExp As String
    Dim iManifestStatus As String
    Dim lActivityNum As Long
    Dim i As Integer
    Dim lRecCount As Integer
    Dim iResponse As Integer
    Dim jrec As Integer
    Dim krec As Integer
    Dim ipos As Integer
    Dim stemp As String
    Dim iNum As Integer
    Dim bMark As Boolean
    Dim sMark As String
    Dim dsTemp As Object
'   Dim StorageEndDate As Date
    Dim InfoString As String
    Dim iCommodity As Integer
    Dim blnTransferOwnership As Boolean
    Dim blnIsSteel As Boolean
    Dim blnAmerimarkGroup As Boolean
    
    iSaved = False
    iCommodity = Val(Trim$(txtCommodityCode.Text))

    'Begin a transaction
    OraSession.BeginTrans
    
    'Lock all the required tables in exclusive mode, try 10 times
    On Error GoTo ErrorHandler

    For i = 0 To 9
        OraDatabase.LastServerErrReset
        gsSqlStmt = "LOCK TABLE CARGO_MANIFEST IN EXCLUSIVE MODE NOWAIT"
        lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
        gsSqlStmt = "LOCK TABLE CARGO_ACTIVITY IN EXCLUSIVE MODE NOWAIT"
        lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
        gsSqlStmt = "LOCK TABLE CARGO_ACTIVITY_EXT IN EXCLUSIVE MODE NOWAIT"
        lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
        gsSqlStmt = "LOCK TABLE CARGO_DELIVERY IN EXCLUSIVE MODE NOWAIT"
        lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
        gsSqlStmt = "LOCK TABLE CARGO_TRACKING IN EXCLUSIVE MODE NOWAIT"
        lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
        If OraDatabase.LastServerErr = 0 Then Exit For
    Next 'i
    
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
        GoTo ErrorHandler
    End If
    
    If CDate(txtDate) > Now Then
        MsgBox "cannot transfer cargo in the future"
        Exit Sub
    End If
    
    'Check that Bill Customer Id is non zero
    If chkBill.Value = 1 Then
        If Trim$(txtBillCust.Text) = "" Then
            MsgBox "Bill Customer can not be null.", vbExclamation, "Bill Customer"
            OraSession.Rollback
            Exit Sub
        End If
        If Trim$(txtBillCust.Text) <> Trim$(txtBillCustValid.Text) Then
            MsgBox "Bill Customer verification did not match.", vbExclamation, "Bill Customer"
            OraSession.Rollback
            Exit Sub
        End If
    End If
    
    'Check that Storage Customer Id is non zero
    If Trim$(txtStrgCust.Text) = "" Then
        MsgBox "Storage Customer can not be null.", vbExclamation, "Storage Customer"
        OraSession.Rollback
        Exit Sub
    End If
    If Trim$(txtStrgCust.Text) <> Trim$(txtStrgCustValid.Text) Then
        MsgBox "Storage Customer verification did not match.", vbExclamation, "Storage Customer"
        OraSession.Rollback
        Exit Sub
    End If
    
    'verify new owner "confirmation" box is correct
    If Trim$(txtNewRecipientId.Text) <> Trim$(txtNewRecipientIdValid.Text) Then
        MsgBox "New Owner verification did not match.", vbExclamation, "Storage Customer"
        OraSession.Rollback
        Exit Sub
    End If
    
    
    'get delivery number for transfering
    gsSqlStmt = "SELECT MIN(DELIVERY_NUM) FROM CARGO_DELIVERY"
    Set dsCARGO_DELIVERY_MIN = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY_MIN.RECORDCOUNT > 0 Then
        iDeliveryNum = dsCARGO_DELIVERY_MIN.Fields("MIN(DELIVERY_NUM)").Value - 1
    Else
        iDeliveryNum = -1000000
    End If
     
    'Get the new max values, replace with the sequence later
    gsSqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
    Set dsBILLING_MAX = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RECORDCOUNT > 0 Then
        If IsNull(dsBILLING_MAX.Fields("MAX(BILLING_NUM)").Value) Then
            glBillingNum = 1
        Else
            glBillingNum = dsBILLING_MAX.Fields("MAX(BILLING_NUM)").Value + 1
        End If
    Else
        glBillingNum = 1
    End If
    
    'GET TRANSFER RATE
    gsSqlStmt = "SELECT * FROM TRANSFER_RATE WHERE SERVICE_CODE = " & txtService.Text
    Set dsTRANSFER_RATE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsTRANSFER_RATE.RECORDCOUNT > 0 Then
        'ok
    Else
        MsgBox "Do not have this service rate.", vbExclamation, "Service Rate"
        OraSession.Rollback
        Exit Sub
    End If
    
    'prepare for add new activity
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY_EXT"
    Set dsCARGO_ACTIVITY_EXT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

    gsSqlStmt = "SELECT * FROM CARGO_DELIVERY"
    Set dsCARGO_DELIVERY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    For i = 0 To iItemNum
        
        If Not (Trim$(txtTransQty1(i).Text) <> "" And Val(Trim$(txtTransQty1(i).Text)) > 0) Then
            OraSession.Rollback
            lblStatus.Caption = "Save Failed."
            MsgBox "QTY1 Transfered must be a value greater than 0, Try it again.", vbInformation, "Save transfer"
            Exit Sub
        End If
        
        If Val(Trim$(txtTransQty1(i).Text)) > QTYavail(i) Then
            OraSession.Rollback
            lblStatus.Caption = "Save Failed."
            MsgBox "Some of the QTY attempting to be transferred is already reserved; Please click the Info button to the right of this line for details.", vbInformation, "Save transfer"
            Exit Sub
        End If
        
        If Not (Trim$(txtWght(i).Text) <> "" And Val(Trim$(txtWght(i).Text)) > 0) Then
            OraSession.Rollback
            lblStatus.Caption = "Save Failed."
            MsgBox "Weight Transfered must be a value greater than 0, Try it again.", vbInformation, "Save transfer"
            Exit Sub
        End If
           
        gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text & " AND "
        gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1 AND "
        gsSqlStmt = gsSqlStmt & "RECIPIENT_ID = " & txtRecipientId.Text & " AND "
        gsSqlStmt = gsSqlStmt & "COMMODITY_CODE = " & txtCommodityCode.Text & " AND "
        gsSqlStmt = gsSqlStmt & "CARGO_BOL = '" & cboBol(i).Text & "'"
        gsSqlStmt = gsSqlStmt & " AND CARGO_MARK = '" & cboMark(i).Text & "'"
        Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
            If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_LOCATION").Value) Then
                iLocation = dsCARGO_MANIFEST.Fields("CARGO_LOCATION").Value
            Else
                iLocation = ""
            End If
            
            'Add "" just in case the fields are null
            iImpExp = "" & dsCARGO_MANIFEST.Fields("IMPEX").Value
            iManifestStatus = "" & dsCARGO_MANIFEST.Fields("MANIFEST_STATUS").Value
            
            'Get cargo tracking detail
            gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value & "'"
            Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.RECORDCOUNT > 0 Then
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
                dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = txtService.Text
                dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value = Val(txtTransQty1(i).Text)
                dsCARGO_ACTIVITY.Fields("ACTIVITY_ID").Value = 4 'SUPER USER
                dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value = 0 '? change later
                dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value = txtNewRecipientId.Text
                dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value = Trim$(txtDate.Text)
                dsCARGO_ACTIVITY.Update
                
                dsCARGO_ACTIVITY_EXT.AddNew
                dsCARGO_ACTIVITY_EXT.Fields("LOT_NUM").Value = dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value
                dsCARGO_ACTIVITY_EXT.Fields("ACTIVITY_NUM").Value = lActivityNum
                dsCARGO_ACTIVITY_EXT.Fields("SERVICE_CODE").Value = txtService.Text
                
                If Trim$(txtTransQty2(i).Text) <> "" Then
                    dsCARGO_ACTIVITY_EXT.Fields("QTY2").Value = Val(txtTransQty2(i).Text)
                End If
                
                If chkBill.Value = 1 Then
                    dsCARGO_ACTIVITY_EXT.Fields("BILL").Value = "Y"
                    dsCARGO_ACTIVITY_EXT.Fields("BILLING_NUM").Value = glBillingNum
                End If
                dsCARGO_ACTIVITY_EXT.Update
                
                dsCARGO_DELIVERY.AddNew
                dsCARGO_DELIVERY.Fields("LOT_NUM").Value = dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value
                dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value = lActivityNum
                dsCARGO_DELIVERY.Fields("SERVICE_CODE").Value = txtService.Text
                dsCARGO_DELIVERY.Fields("DELIVERY_NUM").Value = iDeliveryNum
                dsCARGO_DELIVERY.Fields("DELIVER_TO").Value = txtNewRecipientId.Text
                dsCARGO_DELIVERY.Fields("DELIVERY_ID").Value = 4 'SUPER USER
                dsCARGO_DELIVERY.Fields("DELIVERY_DESCRIPTION").Value = "TRANSFER-" & txtReferenceNum.Text
                dsCARGO_DELIVERY.Update
                dsCARGO_TRACKING.Edit
                
                'Check Qty in house, not allowed less than 0
                If dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value - Val(txtTransQty1(i).Text) >= 0 Then
                    dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value - Val(txtTransQty1(i).Text)
                Else
                    OraSession.Rollback
                    lblStatus.Caption = "Save Failed."
                    MsgBox "Qty in house < 0 after transfer, This process is not executed, Try it again.", vbInformation, "Save transfer"
                    Exit Sub
                End If
                dsCARGO_TRACKING.Update
                
                'update CARGO_MANIFEST by subtracting the transfered cargo from the original owner
                dsCARGO_MANIFEST.Edit
                                
                If dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value - Val(txtTransQty1(i).Text) >= 0 Then
                    dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value = dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value - Val(txtTransQty1(i).Text)
                Else
                    OraSession.Rollback
                    lblStatus.Caption = "Save Failed."
                    MsgBox "Qty Expected < 0 after transfer, This process is not executed, Try it again.", vbInformation, "Save transfer"
                    Exit Sub
                End If

                If Trim$(txtTransQty2(i).Text) <> "" Then
                    If dsCARGO_MANIFEST.Fields("QTY2_EXPECTED").Value - Val(txtTransQty2(i).Text) >= 0 Then
                        dsCARGO_MANIFEST.Fields("QTY2_EXPECTED").Value = dsCARGO_MANIFEST.Fields("QTY2_EXPECTED").Value - Val(txtTransQty2(i).Text)
                    Else
                        OraSession.Rollback
                        lblStatus.Caption = "Save Failed."
                        MsgBox "Qty2 Expected < 0 after transfer, This process is not executed, Try it again.", vbInformation, "Save transfer"
                        Exit Sub
                    End If
                End If
                
                If dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value - Val(txtWght(i).Text) >= 0 Then
                    dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value = dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value - Val(txtWght(i).Text)
                Else
                    OraSession.Rollback
                    lblStatus.Caption = "Save Failed."
                    MsgBox "CARGO_WEIGHT < 0 after transfer, This process is not executed, Try it again.", vbInformation, "Save transfer"
                    Exit Sub
                End If
                
                dsCARGO_MANIFEST.Update
                
                'create new manifest
                'Get the minimum container number and subtract 1 to get new container number
                gsSqlStmt = "SELECT MIN(TO_NUMBER(CONTAINER_NUM)) INTO :A FROM CARGO_MANIFEST"
                Set dsCARGO_MANIFEST_A = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_A.RECORDCOUNT > 0 Then
                    sContainerNum = dsCARGO_MANIFEST_A.Fields("MIN(TO_NUMBER(CONTAINER_NUM))").Value
                    dContainerNum = Val(sContainerNum)
                    dContainerNum = dContainerNum - 1
                    sContainerNum = CStr(dContainerNum)
                End If
            
                gsSqlStmt = "SELECT * FROM CARGO_MANIFEST"
                Set dsCARGO_MANIFEST_A = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 Then
                    dsCARGO_MANIFEST_A.AddNew
                    dsCARGO_MANIFEST_A.Fields("LR_NUM").Value = txtLRNum.Text
                    dsCARGO_MANIFEST_A.Fields("ARRIVAL_NUM").Value = 1
                    dsCARGO_MANIFEST_A.Fields("CONTAINER_NUM").Value = sContainerNum
                    dsCARGO_MANIFEST_A.Fields("RECIPIENT_ID").Value = txtNewRecipientId.Text
                    dsCARGO_MANIFEST_A.Fields("CARGO_BOL").Value = Trim$(cboBol(i).Text)
                                        
                    krec = 0
                    iNum = 1
                    
                    'If it is a transferred cargo with mark starting with "TR*"
                    'Added the check by LFW, 1/15/03
                    If Val("" & InStr(Trim(cboMark(i)), "TR*")) = 1 Then
                        For jrec = 1 To Len(Trim(cboMark(i)))
                            If Mid(Trim(cboMark(i)), jrec, 1) = "*" Then
                                krec = jrec
                            End If
                        Next jrec
                    
                        If krec <> 3 Then   'It is not the 1st *
                            stemp = Mid(Trim(cboMark(i)), 1, krec)
                            iNum = Val(Left(Mid(stemp, 4, krec), Len(Mid(stemp, 4, krec)) - 1)) + 1
                        End If
                    End If
                    
                    'sMark = "TR*" & iNum & "*" & Trim$(Mid(cboMark(i), krec + 1)) diorelle
                    sMark = Trim$(Mid(cboMark(i), krec + 1)) & "TR*" & iNum & "*"
                    
                    'Check if we already have an entry in Cargo_manifest with the same
                    'Lr_num, commodity_code, bol, and mark  - LFW, 1/15/03
                    bMark = False
                    While bMark = False
                        'Replaced this statement with the following one  - LFW, 1/15/03
                        'gsSqlStmt = " SELECT * FROM CARGO_MANIFEST WHERE LR_NUM='" & Trim(txtLRNum) & "' AND " _
                        '        & " RECIPIENT_ID='" & Trim(txtNewRecipientId) & "' " _
                        '        & " AND CARGO_MARK ='" & Trim(sMark) & "'"
                        gsSqlStmt = "SELECT * FROM CARGO_MANIFEST " _
                                  & "WHERE LR_NUM = " & Trim(txtLRNum) & " AND " _
                                  & "COMMODITY_CODE = " & txtCommodityCode.Text & " AND " _
                                  & "CARGO_BOL = '" & Trim(cboBol(i).Text) & "' AND " _
                                  & "CARGO_MARK = '" & Trim(sMark) & "'"
                        Set dsTemp = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                        
                        If OraDatabase.LastServerErr = 0 And dsTemp.RECORDCOUNT > 0 Then
                            bMark = False
                            iNum = iNum + 1
                            'sMark = "TR*" & iNum & "*" & Trim$(Mid(cboMark(i), krec + 1)) diorelle
                            sMark = Trim$(Mid(cboMark(i), krec + 1)) & "TR*" & iNum & "*"
                        Else
                            bMark = True
                        End If
                    Wend
                                  
                    'get location_note over to new record
                    If Not IsNull(dsCARGO_MANIFEST.Fields("LOCATION_NOTE")) Then
                        dsCARGO_MANIFEST_A.Fields("LOCATION_NOTE").Value = dsCARGO_MANIFEST.Fields("LOCATION_NOTE").Value
                    End If
                    
                    dsCARGO_MANIFEST_A.Fields("CARGO_MARK").Value = sMark
                    dsCARGO_MANIFEST_A.Fields("EXPORTER_ID").Value = txtNewRecipientId.Text
                    dsCARGO_MANIFEST_A.Fields("QTY_EXPECTED").Value = Val(txtTransQty1(i).Text)
                    
                    If Trim$(txtTransQty2(i).Text) <> "" Then
                        dsCARGO_MANIFEST_A.Fields("QTY2_EXPECTED").Value = Val(Trim$(txtTransQty2(i).Text))
                    End If
                    
                    If txtQty1Unit(i).Text <> "" Then
                        dsCARGO_MANIFEST_A.Fields("QTY1_UNIT").Value = Trim$(txtQty1Unit(i).Text)
                    End If
                    
                    If txtQty2Unit(i).Text <> "" Then
                        dsCARGO_MANIFEST_A.Fields("QTY2_UNIT").Value = Trim$(txtQty2Unit(i).Text)
                    End If
                    
                    If Trim$(txtWght(i).Text) <> "" Then
                        dsCARGO_MANIFEST_A.Fields("CARGO_WEIGHT").Value = Val(txtWght(i).Text)
                    End If
                        
                    If Trim$(txtWghtUnit(i).Text) <> "" Then
                        dsCARGO_MANIFEST_A.Fields("CARGO_WEIGHT_UNIT").Value = txtWghtUnit(i).Text
                    End If
                    
                    dsCARGO_MANIFEST_A.Fields("COMMODITY_CODE").Value = iCommodity
                    dsCARGO_MANIFEST_A.Fields("CARGO_LOCATION").Value = iLocation
                    dsCARGO_MANIFEST_A.Fields("IMPEX").Value = iImpExp
                    dsCARGO_MANIFEST_A.Fields("MANIFEST_STATUS").Value = iManifestStatus
                    
                    'Added so we can use the original container number to find out who the original owner is
                    dsCARGO_MANIFEST_A.Fields("ORIGINAL_CONTAINER_NUM").Value = dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value
                    dsCARGO_MANIFEST_A.Update
                End If
                
                gsSqlStmt = "SELECT * FROM VOYAGE_CARGO WHERE LOT_NUM = '" & sContainerNum & "'"
                Set dsVOYAGE_CARGO = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 Then
                    If dsVOYAGE_CARGO.RECORDCOUNT = 0 Then
                        dsVOYAGE_CARGO.AddNew
                        dsVOYAGE_CARGO.Fields("LR_NUM").Value = txtLRNum.Text
                        dsVOYAGE_CARGO.Fields("ARRIVAL_NUM").Value = 1
                        dsVOYAGE_CARGO.Fields("CONTAINER_NUM").Value = sContainerNum
                        dsVOYAGE_CARGO.Fields("LOT_NUM").Value = sContainerNum
                        dsVOYAGE_CARGO.Update
                    End If
                End If
                '-----09/13/2001  ----  Pawan
                
                'First Obtain location from cargo manifest
                gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = '" & dsCARGO_TRACKING.Fields("LOT_NUM").Value & "'"
                Set dsCARGO_MANIFEST1 = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                ' If Error in Cargo Manifest Record
                If OraDatabase.LastServerErr <> 0 Or dsCARGO_MANIFEST1.RECORDCOUNT <= 0 Then
                    MsgBox "Error in Cargo Manifest Record for Lot Number = " & dsCARGO_TRACKING.Fields("LOT_NUM").Value
                    OraSession.Rollback
                    Exit Sub
                End If
                'Now Obtain service code from location category
                gsSqlStmt = "SELECT * FROM LOCATION_CATEGORY WHERE LOCATION_TYPE = '" & UCase(Trim$(dsCARGO_MANIFEST1.Fields("CARGO_LOCATION").Value)) & "'"
                Set dsLOCATION_CATEGORY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                
                InfoString = "SHIP: " & dsCARGO_MANIFEST.Fields("LR_NUM").Value & ", OWNER: " & dsCARGO_MANIFEST.Fields("RECIPIENT_ID").Value & ", COMM: " & dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value & ", BOL:" & dsCARGO_MANIFEST.Fields("CARGO_BOL").Value & ", MARK: " & dsCARGO_MANIFEST.Fields("CARGO_MARK").Value
        
                'Error in Location Category Record
                If OraDatabase.LastServerErr <> 0 Or dsLOCATION_CATEGORY.RECORDCOUNT <= 0 Then
                    MsgBox "Error in Location Code." & InfoString
                    OraSession.Rollback
                    Exit Sub
                End If
                
                'Now we can Obtain rate from storage rate table
'                gsSqlStmt = "SELECT * FROM STORAGE_RATE WHERE SERVICE_CODE = " & dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value
'                gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value
'                gsSqlStmt = gsSqlStmt & " ORDER BY START_DAY "
                gsSqlStmt = "SELECT * FROM RATE RT WHERE (RT.COMMODITYCODE = '" & iCommodity & "' OR RT.COMMODITYCODE IS NULL) AND " _
                       & "(RT.ARRIVALNUMBER = '" & txtLRNum.Text & "' OR RT.ARRIVALNUMBER IS NULL) AND" _
                       & "(RT.CUSTOMERID = '" & txtNewRecipientId.Text & "' OR RT.CUSTOMERID IS NULL)" _
                       & "ORDER BY RATEPRIORITY ASC, RATESTARTDATE ASC"
               Set dsSTORAGE_RATE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                
                'If Error in Storage Rate
                If OraDatabase.LastServerErr <> 0 Or dsSTORAGE_RATE.RECORDCOUNT <= 0 Then
                    MsgBox "Error or Unavailable Storage Rate." & dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value
                    OraSession.Rollback
                    Exit Sub
                End If
                
'                If IsNull(dsSTORAGE_RATE.fields("DURATION_UNIT")) Then
'                    MsgBox "Storage Rate has NULL DURATION_UNIT." & InfoString
'                    OraSession.Rollback
'                    Exit Sub
'                End If
'
'                If (Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "MO") Or (Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "DAY") Then
'                    'Ok
'                Else
'                    MsgBox "Storage Rate DURATION_UNIT is incorrect.  It should be either MO or DAY." & InfoString
'                    OraSession.Rollback
'                    Exit Sub
'                End If
                
                '-----09/13/2001  ----  Pawan
                gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & sContainerNum & "'"
                Set dsCARGO_TRACKING_A = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 Then
                    dsCARGO_TRACKING_A.AddNew
                    dsCARGO_TRACKING_A.Fields("LOT_NUM").Value = sContainerNum
                    dsCARGO_TRACKING_A.Fields("QTY_IN_HOUSE").Value = txtTransQty1(i).Text
                    dsCARGO_TRACKING_A.Fields("COMMODITY_CODE").Value = iCommodity
                    dsCARGO_TRACKING_A.Fields("DATE_RECEIVED").Value = Trim$(txtDate.Text)
                    dsCARGO_TRACKING_A.Fields("OWNER_ID").Value = txtNewRecipientId.Text
                    dsCARGO_TRACKING_A.Fields("QTY_RECEIVED").Value = txtTransQty1(i).Text
                    dsCARGO_TRACKING_A.Fields("RECEIVER_ID").Value = 4 'Super User
                    dsCARGO_TRACKING_A.Fields("STORAGE_CUST_ID").Value = txtStrgCust.Text
                    
                    
                    ' MAJOR EDIT
                    ' ADAM WALTER
                    ' Changes to the Storage Billing program mean that this program needs to be edited as well.
                    ' All storage dates (free time or current run) will now be called from a BNI table
                    ' to determine what the "next date" gets set to.  All previous date-logic is commendted out.
                    gsSqlStmt = "SELECT * FROM RATE RT WHERE (RT.COMMODITYCODE = '" & iCommodity & "' OR RT.COMMODITYCODE IS NULL) AND " _
                            & "(RT.ARRIVALNUMBER = '" & txtLRNum.Text & "' OR RT.ARRIVALNUMBER IS NULL) AND" _
                            & "(RT.CUSTOMERID = '" & txtNewRecipientId.Text & "' OR RT.CUSTOMERID IS NULL)" _
                            & "ORDER BY RATEPRIORITY ASC, RATESTARTDATE ASC"
                    Set dsFREETIMEFROM_RATE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                    
                    If txtRecipientId.Text = txtNewRecipientId.Text Then ' no loss of storage date if new owner = old owner
                        If Not IsNull(dsCARGO_TRACKING.Fields("FREE_TIME_END").Value) Then
                            dsCARGO_TRACKING_A.Fields("FREE_TIME_END").Value = DateValue(dsCARGO_TRACKING.Fields("FREE_TIME_END").Value)
                        End If
                        If Not IsNull(dsCARGO_TRACKING.Fields("STORAGE_END")) Then
                            dsCARGO_TRACKING_A.Fields("STORAGE_END").Value = DateValue(dsCARGO_TRACKING.Fields("STORAGE_END").Value)
                        End If
                    ElseIf dsFREETIMEFROM_RATE.Fields("XFRDAYCREDIT").Value = "Y" Then ' rate table says new guy gets credit
                        If Not IsNull(dsCARGO_TRACKING.Fields("FREE_TIME_END").Value) Then
                            dsCARGO_TRACKING_A.Fields("FREE_TIME_END").Value = DateValue(dsCARGO_TRACKING.Fields("FREE_TIME_END").Value)
                        End If
                        If Not IsNull(dsCARGO_TRACKING.Fields("STORAGE_END")) Then
                            dsCARGO_TRACKING_A.Fields("STORAGE_END").Value = DateValue(dsCARGO_TRACKING.Fields("STORAGE_END").Value)
                        End If
                    Else ' new guy gets immediate bill
                        dsCARGO_TRACKING_A.Fields("STORAGE_END").Value = DateAdd("d", -1, DateValue(Trim$(txtDate.Text)))
                        dsCARGO_TRACKING_A.Fields("FREE_TIME_END").Value = DateValue(Trim$(txtDate.Text))
                    End If
'                    'For all transfers, we leave the free time end date untouched so it is easier for storage program to know
'                    'how many days elapsed since the 1st day of storage.  We use the storage end date to implement out policies   -- LFW, 5/17/04
'                    '1) Common Case: Transferred cargo doesn't get free time.  It gets storage charge from the day following the transfer date, Per Antonia  - LFW 1/8/04
'                    '   we set storage end date to the transfer day.
'                    '2) When it is a transfer with no ownership transfer, the transferred cargo would still have free time, no juice surcharge and no overlapped charge
'                    '   we set storage end date to either (free time end - 1) or the transfer day  -- LFW, 2/7/04
'                    '3) When it is a transfer between 179-Amerimark and 2008-Trillenium, we consider it is not a transfer of ownership
'                    '   the transferred cargo get free time, no overlapping charge, and no juice surcharge
'                    '   we set storage end to either (free time end - 1), storage end, or next period start  -- Per HD # 1507, LFW, 4/25/05
'                    '4) Transferred Steel will inherit the free time, Per Antonia  -- LFW, 2/11/04
'                    '5) Transferred Steel gets no overlapping charge  -- LFW, 2/22/04
'                    '   we set storage end to either (free time end - 1), storage end, or next period start
'
'                    If Trim$(txtNewRecipientId.Text) = Trim$(txtRecipientId.Text) Then
'                        blnTransferOwnership = False
'                    Else
'                        blnTransferOwnership = True
'                    End If
'
'                    If (Trim$(txtRecipientId.Text) = "179" And Trim$(txtNewRecipientId.Text) = "2008") Or _
'                       (Trim$(txtRecipientId.Text) = "2008" And Trim$(txtNewRecipientId.Text) = "179") Then
'                        blnAmerimarkGroup = True
'                    Else
'                        blnAmerimarkGroup = False
'                    End If
'
'                    If (iCommodity = 3302 Or iCommodity = 3304 Or iCommodity = 3312 Or iCommodity = 3323 Or _
'                        iCommodity = 3326 Or iCommodity = 3328 Or iCommodity = 3350 Or iCommodity = 3399) Then
'                        blnIsSteel = True
'                    Else
'                        blnIsSteel = False
'                    End If
'
'                    'Keep the Free Time End date anyway
'                    If Not IsNull(dsCARGO_TRACKING.fields("FREE_TIME_END")) Then
'                        dsCARGO_TRACKING_A.fields("FREE_TIME_END").Value = DateValue(dsCARGO_TRACKING.fields("FREE_TIME_END").Value)
'                    End If
'
'                    'Try set the Storage End date
'                    If blnTransferOwnership = False Or blnAmerimarkGroup = True Or blnIsSteel = True Then
'                        If Not IsNull(dsCARGO_TRACKING.fields("FREE_TIME_END").Value) Then
'                            If DateValue(Trim$(txtDate.Text)) < DateValue(dsCARGO_TRACKING.fields("FREE_TIME_END").Value) Then
'                                dsCARGO_TRACKING_A.fields("STORAGE_END").Value = DateAdd("d", -1, dsCARGO_TRACKING.fields("FREE_TIME_END").Value)
'                            Else
'                                If Not IsNull(dsCARGO_TRACKING.fields("STORAGE_END")) Then
'                                    If DateValue(Trim$(txtDate.Text)) <= DateValue(dsCARGO_TRACKING.fields("STORAGE_END").Value) Then
'                                        'Assign the storage end date
'                                        dsCARGO_TRACKING_A.fields("STORAGE_END").Value = DateValue(dsCARGO_TRACKING.fields("STORAGE_END").Value)
'                                    Else
'                                        'Assign the ending date of next period
'
'                                        'If based on month then add 1 month to the date
'                                        If Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "MO" Then
'                                            dsCARGO_TRACKING_A.fields("STORAGE_END").Value = DateAdd("m", dsSTORAGE_RATE.fields("DURATION").Value, dsCARGO_TRACKING.fields("STORAGE_END").Value)
'                                        End If
'
'                                        'If based on day then add number of days to date
'                                        If Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "DAY" Then
'                                            dsCARGO_TRACKING_A.fields("STORAGE_END").Value = DateAdd("d", dsSTORAGE_RATE.fields("DURATION").Value, dsCARGO_TRACKING.fields("STORAGE_END").Value)
'                                        End If
'                                    End If
'                                End If
'                            End If
'                        End If
'                    Else
'                        'Common Case: start to get storage from the day after the transfer is made
'                        dsCARGO_TRACKING_A.fields("STORAGE_END").Value = DateValue(Trim$(txtDate.Text))
'                    End If
                    
                    If txtQty1Unit(i).Text <> "" Then
                        dsCARGO_TRACKING_A.Fields("QTY_UNIT").Value = txtQty1Unit(i).Text
                    End If
                    dsCARGO_TRACKING_A.Fields("WAREHOUSE_LOCATION").Value = iLocation
                                        
                    If Not IsNull(dsCARGO_TRACKING.Fields("FREE_DAYS")) Then
                        dsCARGO_TRACKING_A.Fields("FREE_DAYS").Value = dsCARGO_TRACKING.Fields("FREE_DAYS").Value
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.Fields("STORAGE_DAYS")) Then
                        dsCARGO_TRACKING_A.Fields("STORAGE_DAYS").Value = dsCARGO_TRACKING.Fields("STORAGE_DAYS").Value
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.Fields("STORAGE_RATE")) Then
                        dsCARGO_TRACKING_A.Fields("STORAGE_RATE").Value = dsCARGO_TRACKING.Fields("STORAGE_RATE").Value
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.Fields("WHSE_RCPT_NUM")) Then
                        dsCARGO_TRACKING_A.Fields("WHSE_RCPT_NUM").Value = dsCARGO_TRACKING.Fields("WHSE_RCPT_NUM").Value
                    End If
                    
                    If Not IsNull(dsCARGO_TRACKING.Fields("START_FREE_TIME")) Then
                        dsCARGO_TRACKING_A.Fields("START_FREE_TIME").Value = DateValue(dsCARGO_TRACKING.Fields("START_FREE_TIME").Value)
                    End If
                    
                    dsCARGO_TRACKING_A.Fields("QTY_IN_STORAGE").Value = txtTransQty1(i).Text
                    dsCARGO_TRACKING_A.Update
                End If
            End If
        End If
    Next i
                   
    'Change to Bill Transfer Fee variable
    'Create Billing record if Transfer Fee is to be billed
    If chkBill.Value = 1 Then
    
        'create the billing record
        gsSqlStmt = "SELECT * FROM BILLING"
        Set dsBILLING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        dsBILLING.AddNew
        
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox "Error Occured.", vbExclamation, "Save"
            iError = True
            OraSession.Rollback
        End If
            
        If Not iError Then
            dsBILLING.Fields("CUSTOMER_ID").Value = txtBillCust.Text
            dsBILLING.Fields("BILLING_NUM").Value = glBillingNum
            dsBILLING.Fields("EMPLOYEE_ID").Value = 4
            dsBILLING.Fields("SERVICE_START").Value = Trim$(txtDate.Text)
            dsBILLING.Fields("SERVICE_STOP").Value = Trim$(txtDate.Text)
            dsBILLING.Fields("SERVICE_AMOUNT").Value = 1 * Val(dsTRANSFER_RATE.Fields("RATE").Value)
            dsBILLING.Fields("SERVICE_STATUS").Value = "PREINVOICE"
            dsBILLING.Fields("SERVICE_CODE").Value = txtService.Text
                
            ' pawan....10/29/2001....
            dsBILLING.Fields("SERVICE_DESCRIPTION").Value = "TRANSFER FEE (Transfer # :" & iDeliveryNum & " )"
            '........................
            dsBILLING.Fields("LR_NUM").Value = txtLRNum.Text
            dsBILLING.Fields("ARRIVAL_NUM").Value = 1
            dsBILLING.Fields("COMMODITY_CODE").Value = iCommodity
            dsBILLING.Fields("INVOICE_NUM").Value = 0
            'dsBILLING.Fields("REVERSE_DATE").Value = txt.Text
            dsBILLING.Fields("SERVICE_DATE").Value = Trim$(txtDate.Text)
            dsBILLING.Fields("SERVICE_QTY").Value = 1
            'dsBILLING.Fields("THRESHOLD_TRACK").Value = txt.Text
            'dsBILLING.Fields("LOT_NUM").Value = dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value
            dsBILLING.Fields("SERVICE_NUM").Value = 1
            dsBILLING.Fields("THRESHOLD_QTY").Value = 0
            dsBILLING.Fields("LEASE_NUM").Value = 0
            dsBILLING.Fields("SERVICE_UNIT").Value = dsTRANSFER_RATE.Fields("UNIT").Value
            dsBILLING.Fields("SERVICE_RATE").Value = dsTRANSFER_RATE.Fields("RATE").Value
            dsBILLING.Fields("LABOR_RATE_TYPE").Value = ""
            dsBILLING.Fields("LABOR_TYPE").Value = ""
            dsBILLING.Fields("PAGE_NUM").Value = "1"
            dsBILLING.Fields("CARE_OF").Value = 1
            dsBILLING.Fields("BILLING_TYPE").Value = "MISC"
            dsBILLING.Update
        End If
    End If
    
ErrorHandler:
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        txtTransferNum.Text = iDeliveryNum
        lblStatus.Caption = iItemNum + 1 & " transfer are saved Successfully. "
        iSaved = True
        
'        gsSqlStmt = "SELECT COMMODITY_TYPE FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '" & txtCommodityCode.Text & "'"
'        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'        If dsCOMMODITY_PROFILE.Fields("COMMODITY_TYPE").Value = "JUICE" Then
'            MsgBox "Please Check your email within the next 5 minutes to see if the RF-Juice transfer" & vbCrLf & "Was successful, and if not, use the intranet page to transfer the scanned cargo manually.", vbExclamation, "Reminder"
'        End If
        
        iResponse = MsgBox("Would you like to print out?", vbQuestion + vbYesNo, "Print Form")
        If iResponse = vbYes Then
            Call cmdPrint_Click
        End If
        'clearBox
    Else
        OraSession.Rollback
        lblStatus.Caption = "Save Failed."
        MsgBox "Error occured while saving." & OraDatabase.LastServerErrText, vbExclamation, "Save"
    End If
    
End Sub

Private Sub Form_Load()

    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    chkBill.Value = 0
    
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
        txtDate.Text = CStr(Format$(Now, "mm/dd/yyyy"))
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
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

Private Sub txtBillCust_lostFocus()
    If Trim$(txtBillCust.Text) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtBillCust.Text
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
            txtBillCustName.Text = dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
    End If
End Sub

Private Sub txtCommodityCode_LostFocus()

    If Trim$(txtCommodityCode) <> "" Then
        gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & txtCommodityCode.Text
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RECORDCOUNT > 0 Then
            txtCommName.Text = dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value
            
            txtService.Text = "6120"
            
            gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text & " AND "
            gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1 AND "
            gsSqlStmt = gsSqlStmt & "RECIPIENT_ID = " & txtRecipientId.Text & " AND "
            gsSqlStmt = gsSqlStmt & "COMMODITY_CODE = " & txtCommodityCode.Text
            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
             
            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
                For i = 0 To 7
                    cboBol(i).Clear
                Next i
                
                While Not dsCARGO_MANIFEST.EOF
                    For i = 0 To 7
                        cboBol(i).AddItem dsCARGO_MANIFEST.Fields("CARGO_BOL").Value
                    Next i
                    dsCARGO_MANIFEST.MoveNext
                Wend
            End If
           
        Else
            MsgBox "Commodity does not exist.", vbExclamation, "Commodity"
        End If
    End If
End Sub

Private Sub txtDate_LostFocus()
    If Trim$(txtDate.Text) <> "" Then
        If Not IsDate(txtDate.Text) Then
            MsgBox "Date format is not correct.", vbExclamation, "Date"
            txtDate.Text = ""
        End If
    Else
        MsgBox "This field should not be null.", vbExclamation, "Date"
        txtDate.Text = ""
    End If
    
    
End Sub

Private Sub txtLRNum_LostFocus()
    If Trim$(txtLRNum.Text) <> "" And IsNumeric(txtLRNum.Text) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLRNum.Text
        Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RECORDCOUNT > 0 Then
            txtShipName.Text = dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value
        Else
            MsgBox "Vessel does not exist.", vbExclamation, "Vessel"
        End If
    End If
End Sub

Private Sub txtNewRecipientId_LostFocus()
    If Trim$(txtNewRecipientId.Text) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtNewRecipientId.Text
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
            txtNewCustname.Text = dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
    End If
    
    If Trim$(txtNewRecipientId.Text) = Trim$(txtRecipientId.Text) Then
        MsgBox "Note:  you have chosen to transer this cargo to the exact same owner." & vbCrLf & "This notice is for your information only."
    End If
End Sub

Private Sub txtQty2Unit_LostFocus(Index As Integer)
    
    'If chkAll = 0 Then
        
    dsCARGO_MANIFEST.MoveFirst
    txtQty2Unit(Index).Text = UCase(txtQty2Unit(Index).Text)
    txtWght(Index).Text = Val(txtTransQty1(Index).Text) / Val(dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value) * Val(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value)
        'If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT")) Then
        '    txtWghtUnit(Index).Text = dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value
        'Else
        '    txtWghtUnit(Index).Text = ""
        'End If
        
    'End If
    
End Sub

Private Sub txtRecipientId_LostFocus()
    If Trim$(txtRecipientId) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtRecipientId.Text
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
            txtOldCustName.Text = dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
    End If
    
    If Trim$(txtNewRecipientId.Text) = Trim$(txtRecipientId.Text) Then
        MsgBox "Note:  you have chosen to transer this cargo to the exact same owner." & vbCrLf & "This notice is for your information only."
    End If

End Sub

Private Sub txtService_LostFocus()
    If Trim$(txtService.Text) <> "" Then
        gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " & txtService.Text
        Set dsSERVICE_CATEGORY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RECORDCOUNT > 0 Then
            'ok
            'txtDate.Text = CStr(Format$(Now, "MM/DD/YYYY"))
        Else
            MsgBox "Service code does not exist.", vbExclamation, "Service Code"
        End If
    End If
End Sub

Private Sub txtStrgCust_LostFocus()
    If Trim$(txtStrgCust.Text) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtStrgCust.Text
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
            txtStrgCustName.Text = dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
    Else
        MsgBox "This field can not be null", vbExclamation, "Customer"
        txtStrgCust.Text = ""
        txtStrgCust.SetFocus
    End If
End Sub

Private Sub txtTransQty1_LostFocus(Index As Integer)
    
    If IsNumeric(txtTransQty1(Index).Text) Then
        If Val(txtTransQty1(Index).Text) <= 0 Then
             MsgBox "Can not transfer 0 or negative quantity.", vbInformation, "Quantity 1"
             Exit Sub
        End If
        
        dsCARGO_MANIFEST.MoveFirst
        While Not dsCARGO_MANIFEST.EOF
            gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM ='" & dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value & "'"
            Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
            If Val(txtTransQty1(Index).Text) > dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value Then
                MsgBox "Quantity can not be greater than " & dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value & ".", vbInformation, "Quantity 1"
                txtTransQty1(Index).Text = "0"
                Exit Sub
            End If
            
            dsCARGO_MANIFEST.MoveNext
        Wend
        dsCARGO_MANIFEST.MoveFirst
        
        'Added so once qty1 is changed, weight will be updated accordingly  -- LFW, 2/16/04
        txtWght(Index).Text = Val(txtTransQty1(Index).Text) / Val(dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value) * Val(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value)
        txtTransQty2(Index).Text = Round((Val(txtTransQty1(Index).Text) / Val(dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value)) * Val(dsCARGO_MANIFEST.Fields("QTY2_EXPECTED").Value), 2)
    Else
        MsgBox "Quantity must be numeric.", vbInformation, "Quantity 1"
        txtTransQty1(Index).Text = "0"
    End If

    iItemNum = Index
End Sub

Private Sub txtTransQty2_LostFocus(Index As Integer)
    If Not IsNumeric(txtTransQty2(Index).Text) Then
        MsgBox "Quantity must not be numeric.", vbInformation, "Quantity 2"
        txtTransQty2(Index).Text = "0"
    End If
    
    iItemNum = Index
End Sub

Private Sub txtWght_LostFocus(Index As Integer)
    If IsNumeric(txtWght(Index).Text) Then
        If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT")) Then
            txtWghtUnit(Index).Text = dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value
        Else
            txtWghtUnit(Index).Text = ""
        End If
          
    Else
        MsgBox "Quantity must not be numeric.", vbInformation, "Quantity 2"
        txtWght(Index).Text = "0"
    
    End If
    
End Sub

Private Sub txtWghtUnit_LostFocus(Index As Integer)
    iItemNum = Index
End Sub


Private Sub txtWghtUnit_GotFocus(Index As Integer)
    iItemNum = Index
End Sub

Private Sub QTYbtn_Click(Index As Integer)
    MsgBox QTYavailText(Index), vbInformation, "Transfer Number"
End Sub


