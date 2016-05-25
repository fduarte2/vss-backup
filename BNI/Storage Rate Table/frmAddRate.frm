VERSION 5.00
Begin VB.Form frmAddRate 
   Caption         =   "Add New Rate"
   ClientHeight    =   11280
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   14190
   LinkTopic       =   "Form1"
   ScaleHeight     =   11280
   ScaleWidth      =   14190
   StartUpPosition =   3  'Windows Default
   Begin VB.ComboBox cboSpecialReturn 
      Height          =   315
      Left            =   9120
      TabIndex        =   78
      Text            =   "Special Returns"
      Top             =   5760
      Width           =   1335
   End
   Begin VB.ComboBox cboScanned 
      Height          =   315
      ItemData        =   "frmAddRate.frx":0000
      Left            =   2520
      List            =   "frmAddRate.frx":000A
      TabIndex        =   75
      Text            =   "Scanned?"
      Top             =   9240
      Width           =   1815
   End
   Begin VB.CheckBox chkResetFields 
      Caption         =   "Check1"
      Height          =   255
      Left            =   5640
      TabIndex        =   71
      Top             =   10680
      Visible         =   0   'False
      Width           =   255
   End
   Begin VB.ComboBox cboWeekends 
      Height          =   315
      ItemData        =   "frmAddRate.frx":0022
      Left            =   2520
      List            =   "frmAddRate.frx":002C
      TabIndex        =   70
      Text            =   "Weekends"
      Top             =   2640
      Width           =   1935
   End
   Begin VB.ComboBox cboHolidays 
      Height          =   315
      ItemData        =   "frmAddRate.frx":0036
      Left            =   2520
      List            =   "frmAddRate.frx":0040
      TabIndex        =   69
      Text            =   "Holidays"
      Top             =   3240
      Width           =   1935
   End
   Begin VB.ComboBox cboBillDurationUnit 
      Height          =   315
      ItemData        =   "frmAddRate.frx":004A
      Left            =   2520
      List            =   "frmAddRate.frx":0051
      TabIndex        =   68
      Text            =   "Bill Duration Unit"
      Top             =   4440
      Width           =   1935
   End
   Begin VB.ComboBox cboServiceCode 
      Height          =   315
      ItemData        =   "frmAddRate.frx":005A
      Left            =   2520
      List            =   "frmAddRate.frx":0064
      TabIndex        =   67
      Text            =   "Service Code"
      Top             =   6240
      Width           =   1935
   End
   Begin VB.ComboBox cboBillByUnit 
      Height          =   315
      Left            =   2520
      TabIndex        =   66
      Text            =   "Bill By Unit"
      Top             =   6840
      Width           =   1935
   End
   Begin VB.ComboBox cboXFERCredit 
      Height          =   315
      ItemData        =   "frmAddRate.frx":0074
      Left            =   2520
      List            =   "frmAddRate.frx":007E
      TabIndex        =   65
      Text            =   "XFER Credit"
      Top             =   7440
      Width           =   1935
   End
   Begin VB.ComboBox cboArrivalType 
      Height          =   315
      ItemData        =   "frmAddRate.frx":0088
      Left            =   2520
      List            =   "frmAddRate.frx":009B
      TabIndex        =   64
      Text            =   "Arrival Type"
      Top             =   8040
      Width           =   1935
   End
   Begin VB.ComboBox cboCustomer 
      Height          =   315
      ItemData        =   "frmAddRate.frx":00AE
      Left            =   9120
      List            =   "frmAddRate.frx":00B0
      TabIndex        =   63
      Text            =   "Customer"
      Top             =   960
      Width           =   1335
   End
   Begin VB.ComboBox cboCommodity 
      Height          =   315
      Left            =   2520
      TabIndex        =   62
      Text            =   "Commodity"
      Top             =   8640
      Width           =   1335
   End
   Begin VB.ComboBox cboFromShipping 
      Height          =   315
      Left            =   9120
      TabIndex        =   61
      Text            =   "From Shipping Line"
      Top             =   1560
      Width           =   1335
   End
   Begin VB.ComboBox cboToShippingLine 
      Height          =   315
      Left            =   9120
      TabIndex        =   60
      Text            =   "To Shipping Line"
      Top             =   2160
      Width           =   1335
   End
   Begin VB.ComboBox cboWarehouse 
      Height          =   315
      ItemData        =   "frmAddRate.frx":00B2
      Left            =   9120
      List            =   "frmAddRate.frx":00B4
      TabIndex        =   59
      Text            =   "Warehouse"
      Top             =   3360
      Width           =   1095
   End
   Begin VB.ComboBox cboStacking 
      Height          =   315
      ItemData        =   "frmAddRate.frx":00B6
      Left            =   9120
      List            =   "frmAddRate.frx":00B8
      TabIndex        =   58
      Text            =   "Stacking"
      Top             =   4560
      Width           =   975
   End
   Begin VB.ComboBox cboBox 
      Height          =   315
      Left            =   9120
      TabIndex        =   57
      Text            =   "Box"
      Top             =   3960
      Width           =   1095
   End
   Begin VB.ComboBox cboBillToCust 
      Height          =   315
      Left            =   9120
      TabIndex        =   56
      Text            =   "Bill To Customer"
      Top             =   5160
      Width           =   1335
   End
   Begin VB.TextBox txtLRNum 
      Height          =   375
      Left            =   9120
      TabIndex        =   55
      Top             =   2760
      Width           =   855
   End
   Begin VB.TextBox txtFreeDays 
      Height          =   375
      Left            =   2520
      TabIndex        =   54
      Top             =   2040
      Width           =   855
   End
   Begin VB.TextBox txtBillDuration 
      Height          =   375
      Left            =   2520
      TabIndex        =   53
      Top             =   3840
      Width           =   855
   End
   Begin VB.TextBox txtRateStartDate 
      Height          =   375
      Left            =   2520
      TabIndex        =   52
      Top             =   5040
      Width           =   855
   End
   Begin VB.TextBox txtRate 
      Height          =   375
      Left            =   2520
      TabIndex        =   51
      Top             =   5640
      Width           =   855
   End
   Begin VB.TextBox txtRatePriority 
      Height          =   375
      Left            =   2520
      TabIndex        =   50
      Top             =   1440
      Width           =   855
   End
   Begin VB.ComboBox cboContractID 
      Height          =   315
      Left            =   2520
      TabIndex        =   49
      Text            =   "Contract ID"
      Top             =   840
      Width           =   1935
   End
   Begin VB.CommandButton cmdClose 
      Caption         =   "Close"
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
      Left            =   9120
      TabIndex        =   48
      Top             =   9720
      Width           =   2775
   End
   Begin VB.CommandButton cmdInsert 
      Caption         =   "Insert"
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
      Left            =   8160
      TabIndex        =   47
      Top             =   7320
      Visible         =   0   'False
      Width           =   2655
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "Save"
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
      Left            =   1800
      TabIndex        =   46
      Top             =   9720
      Width           =   2895
   End
   Begin VB.Label lblVerifySpecialReturn 
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   6960
      TabIndex        =   77
      Top             =   5760
      Width           =   375
   End
   Begin VB.Label Label27 
      Caption         =   "Special Returns:"
      Height          =   375
      Left            =   7440
      TabIndex        =   76
      Top             =   5760
      Width           =   1575
   End
   Begin VB.Label lblVerifyScanned 
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   74
      Top             =   9240
      Width           =   375
   End
   Begin VB.Label Label26 
      Caption         =   "Scanned Cargo?"
      Height          =   375
      Left            =   600
      TabIndex        =   73
      Top             =   9240
      Width           =   1695
   End
   Begin VB.Label Label25 
      Caption         =   "Reset Fields After Insert"
      Height          =   270
      Left            =   6240
      TabIndex        =   72
      Top             =   10700
      Visible         =   0   'False
      Width           =   1815
   End
   Begin VB.Label lblVerifyPriority 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   45
      Top             =   1440
      Width           =   375
   End
   Begin VB.Label lblVerifyFreeDays 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   44
      Top             =   2040
      Width           =   375
   End
   Begin VB.Label lblVerifyWeekends 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   43
      Top             =   2640
      Width           =   375
   End
   Begin VB.Label lblVerifyHolidays 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   42
      Top             =   3240
      Width           =   375
   End
   Begin VB.Label lblVerifyDuration 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   41
      Top             =   3840
      Width           =   375
   End
   Begin VB.Label lblVerifyDurationUnit 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   40
      Top             =   4440
      Width           =   375
   End
   Begin VB.Label lblVerifyStartDate 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   39
      Top             =   5040
      Width           =   375
   End
   Begin VB.Label lblVerifyRate 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   38
      Top             =   5640
      Width           =   375
   End
   Begin VB.Label lblVerifyServiceCode 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   37
      Top             =   6240
      Width           =   375
   End
   Begin VB.Label lblVerifyBillUnit 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   36
      Top             =   6840
      Width           =   375
   End
   Begin VB.Label lblVerifyTransferCredit 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   35
      Top             =   7440
      Width           =   375
   End
   Begin VB.Label lblVerifyArrivalType 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   34
      Top             =   8040
      Width           =   375
   End
   Begin VB.Label lblVerifyCustomer 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   6960
      TabIndex        =   33
      Top             =   960
      Width           =   375
   End
   Begin VB.Label lblVerifyCommodity 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   32
      Top             =   8640
      Width           =   375
   End
   Begin VB.Label lblVerifyFromShipping 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   6960
      TabIndex        =   31
      Top             =   1560
      Width           =   375
   End
   Begin VB.Label lblVerifyToShipping 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   6960
      TabIndex        =   30
      Top             =   2160
      Width           =   375
   End
   Begin VB.Label lblVerifyLRNum 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   6960
      TabIndex        =   29
      Top             =   2760
      Width           =   375
   End
   Begin VB.Label lblVerifyWarehouse 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   6960
      TabIndex        =   28
      Top             =   3360
      Width           =   375
   End
   Begin VB.Label lblVerifyStacking 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   6960
      TabIndex        =   27
      Top             =   4560
      Width           =   375
   End
   Begin VB.Label lblVerifyBox 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   6960
      TabIndex        =   26
      Top             =   3960
      Width           =   375
   End
   Begin VB.Label lblVerifyBillToCust 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   6960
      TabIndex        =   25
      Top             =   5160
      Width           =   375
   End
   Begin VB.Label lblVerifyContract 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   24
      Top             =   840
      Width           =   375
   End
   Begin VB.Label Label24 
      Caption         =   "Customer ID:"
      Height          =   375
      Left            =   7440
      TabIndex        =   23
      Top             =   960
      Width           =   1455
   End
   Begin VB.Label Label23 
      Caption         =   "Commodity Code:"
      Height          =   375
      Left            =   600
      TabIndex        =   22
      Top             =   8640
      Width           =   1695
   End
   Begin VB.Label Label22 
      Caption         =   "From Shipping Line"
      Height          =   375
      Left            =   7440
      TabIndex        =   21
      Top             =   1560
      Width           =   1455
   End
   Begin VB.Label Label21 
      Caption         =   "To Shipping Line:"
      Height          =   375
      Left            =   7440
      TabIndex        =   20
      Top             =   2160
      Width           =   1455
   End
   Begin VB.Label Label20 
      Caption         =   "LR Number:"
      Height          =   375
      Left            =   7440
      TabIndex        =   19
      Top             =   2760
      Width           =   1455
   End
   Begin VB.Label Label19 
      Caption         =   "Arrival Type:"
      Height          =   375
      Left            =   600
      TabIndex        =   18
      Top             =   8040
      Width           =   1695
   End
   Begin VB.Label Label18 
      Caption         =   "Stacking:"
      Height          =   375
      Left            =   7440
      TabIndex        =   17
      Top             =   4560
      Width           =   1455
   End
   Begin VB.Label Label17 
      Caption         =   "Box:"
      Height          =   375
      Left            =   7440
      TabIndex        =   16
      Top             =   3960
      Width           =   1455
   End
   Begin VB.Label Label16 
      Caption         =   "Bill To Customer:"
      Height          =   375
      Left            =   7440
      TabIndex        =   15
      Top             =   5160
      Width           =   1455
   End
   Begin VB.Label Label15 
      Caption         =   "Warehouse:"
      Height          =   375
      Left            =   7440
      TabIndex        =   14
      Top             =   3360
      Width           =   1455
   End
   Begin VB.Label Label14 
      Caption         =   "Billing Duration Unit:"
      Height          =   375
      Left            =   600
      TabIndex        =   13
      Top             =   4440
      Width           =   1695
   End
   Begin VB.Label Label13 
      Caption         =   "Rate Start Date:"
      Height          =   375
      Left            =   600
      TabIndex        =   12
      Top             =   5040
      Width           =   1695
   End
   Begin VB.Label Label12 
      Caption         =   "Rate:"
      Height          =   375
      Left            =   600
      TabIndex        =   11
      Top             =   5640
      Width           =   1695
   End
   Begin VB.Label Label11 
      Caption         =   "Service Code:"
      Height          =   375
      Left            =   600
      TabIndex        =   10
      Top             =   6240
      Width           =   1695
   End
   Begin VB.Label Label10 
      Caption         =   "Bill By Unit:"
      Height          =   375
      Left            =   600
      TabIndex        =   9
      Top             =   6840
      Width           =   1695
   End
   Begin VB.Label Label9 
      Caption         =   "Transfer Day Credit:"
      Height          =   375
      Left            =   600
      TabIndex        =   8
      Top             =   7440
      Width           =   1695
   End
   Begin VB.Label Label8 
      Caption         =   "Free Days:"
      Height          =   375
      Left            =   600
      TabIndex        =   7
      Top             =   2040
      Width           =   1695
   End
   Begin VB.Label Label7 
      Caption         =   "Weekends:"
      Height          =   375
      Left            =   600
      TabIndex        =   6
      Top             =   2640
      Width           =   1695
   End
   Begin VB.Label Label6 
      Caption         =   "Holidays:"
      Height          =   375
      Left            =   600
      TabIndex        =   5
      Top             =   3240
      Width           =   1695
   End
   Begin VB.Label Label5 
      Caption         =   "Billing Duration:"
      Height          =   375
      Left            =   600
      TabIndex        =   4
      Top             =   3840
      Width           =   1695
   End
   Begin VB.Label Label4 
      Caption         =   "Rate Priority:"
      Height          =   375
      Left            =   600
      TabIndex        =   3
      Top             =   1440
      Width           =   1695
   End
   Begin VB.Label Label3 
      Alignment       =   2  'Center
      Caption         =   "OPTIONAL:"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   8760
      TabIndex        =   2
      Top             =   120
      Width           =   3015
   End
   Begin VB.Line Line2 
      X1              =   120
      X2              =   13560
      Y1              =   720
      Y2              =   720
   End
   Begin VB.Label Label2 
      Alignment       =   2  'Center
      Caption         =   "REQUIRED:"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   1920
      TabIndex        =   1
      Top             =   120
      Width           =   2895
   End
   Begin VB.Line Line1 
      X1              =   6840
      X2              =   6840
      Y1              =   120
      Y2              =   9360
   End
   Begin VB.Label Label1 
      Caption         =   "Contract ID:"
      Height          =   375
      Left            =   600
      TabIndex        =   0
      Top             =   840
      Width           =   1695
   End
End
Attribute VB_Name = "frmAddRate"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Private Const CB_FINDSTRING = &H14C
Private Const LB_FINDSTRING = &H18F
Private Declare Function SendMessage Lib _
    "user32" Alias "SendMessageA" (ByVal _
    hwnd As Long, ByVal wMsg As Long, _
    ByVal wParam As Long, lParam As Any) _
    As Long

Public PreLoad As String

Private Sub cmdClose_Click()
    Unload Me
End Sub

Private Sub cmdInsert_Click()
    If Insert_Record() = True Then
        Unload Me
    End If
End Sub

Private Sub cmdsave_Click()
    If Insert_Record() = True Then ' if the insert went through fine
'        If chkResetFields = 1 Then ' if they want to reset fields
            Call Reset_fields
'        End If
    Else
        ' do nothing
    End If
End Sub

Private Function Insert_Record() As Boolean
    Call ClearXs
    
    ' verify ALL values
    If ValidateContract(cboContractID.Text) = False Then
        lblVerifyContract.Caption = "X"
    End If
    If ValidateRatePriority(txtRatePriority.Text) = False Then
        lblVerifyPriority.Caption = "X"
    End If
    If ValidateFreeDays(txtFreeDays.Text) = False Then
        lblVerifyFreeDays.Caption = "X"
    End If
    If ValidateWeekends(cboWeekends.Text) = False Then
        lblVerifyWeekends.Caption = "X"
    End If
    If ValidateHolidays(cboHolidays.Text) = False Then
        lblVerifyHolidays.Caption = "X"
    End If
    If ValidateDuration(txtBillDuration.Text) = False Then
        lblVerifyDuration.Caption = "X"
    End If
    If ValidateDurationUnit(cboBillDurationUnit.Text) = False Then
        lblVerifyDurationUnit.Caption = "X"
    End If
    If ValidateRateStartDate(txtRateStartDate.Text) = False Then
        lblVerifyStartDate.Caption = "X"
    End If
    If ValidateRate(txtRate.Text) = False Then
        lblVerifyRate.Caption = "X"
    End If
    If ValidateServiceCode(cboServiceCode.Text) = False Then
        lblVerifyServiceCode.Caption = "X"
    End If
    If ValidateUnit(cboBillByUnit.Text) = False Then
        lblVerifyBillUnit.Caption = "X"
    End If
    If ValidateXFERCredit(cboXFERCredit.Text) = False Then
        lblVerifyTransferCredit.Caption = "X"
    End If
    If ValidateArvType(cboArrivalType.Text) = False Then
        lblVerifyArrivalType.Caption = "X"
    End If
    If ValidateCommodity(cboCommodity.Text) = False Then
        lblVerifyCommodity.Caption = "X"
    End If
    If ValidateCustomer(cboCustomer.Text) = False Then
        lblVerifyCustomer.Caption = "X"
    End If
    If ValidateShipLine(cboFromShipping.Text) = False Then
        lblVerifyFromShipping.Caption = "X"
    End If
    If ValidateShipLine(cboToShippingLine.Text) = False Then
        lblVerifyToShipping.Caption = "X"
    End If
'    If ValidateRatePriority(txtRatePriority.Text) = False Then  ---  no ship check?
'        lblVerifyPriority.Caption = "X"
'    End If
    If ValidateWarehouse(cboWarehouse.Text) = False Then
        lblVerifyWarehouse.Caption = "X"
    End If
    If ValidateBox(cboBox.Text) = False Then
        lblVerifyBox.Caption = "X"
    End If
    If ValidateStacking(cboStacking.Text) = False Then
        lblVerifyStacking.Caption = "X"
    End If
    If ValidateCustomer(cboBillToCust.Text) = False Then
        lblVerifyBillToCust.Caption = "X"
    End If
    If ValidateSpecialReturn(cboSpecialReturn.Text) = False Then
        lblVerifySpecialReturn.Caption = "X"
    End If
    
    If lblVerifyContract.Caption = "X" Or lblVerifyPriority.Caption = "X" Or lblVerifyFreeDays.Caption = "X" Or lblVerifyWeekends.Caption = "X" Or lblVerifyHolidays.Caption = "X" Or lblVerifyDuration.Caption = "X" Or lblVerifyDurationUnit.Caption = "X" Or lblVerifyStartDate.Caption = "X" Or lblVerifyRate.Caption = "X" Or lblVerifyServiceCode.Caption = "X" Or lblVerifyBillUnit.Caption = "X" Or lblVerifyTransferCredit.Caption = "X" Or lblVerifyArrivalType.Caption = "X" Or lblVerifyCommodity.Caption = "X" Or lblVerifyCustomer.Caption = "X" Or lblVerifyFromShipping.Caption = "X" Or lblVerifyToShipping.Caption = "X" Or lblVerifyWarehouse.Caption = "X" Or lblVerifyBox.Caption = "X" Or lblVerifyStacking.Caption = "X" Or lblVerifyBillToCust.Caption = "X" Or lblVerifySpecialReturn.Caption = "X" Then
        MsgBox "Un-usable values detected.  Please change any box marked with a red X and resubmit."
        Insert_Record = False
        Exit Function
    End If
    
    SqlStmt = "INSERT INTO RATE (CONTRACTID, DATEENTERED, ENTEREDBY, CUSTOMERID, COMMODITYCODE, RATEPRIORITY, FRSHIPPINGLINE, TOSHIPPINGLINE, ARRIVALNUMBER, ARRIVALTYPE, FREEDAYS, WEEKENDS, HOLIDAYS, BILLDURATION, BILLDURATIONUNIT, RATESTARTDATE, RATE, SERVICECODE, UNIT, STACKING, WAREHOUSE, BOX, BILLTOCUSTOMER, XFRDAYCREDIT, SCANNEDORUNSCANNED, SPECIALRETURN) " _
            & "VALUES " _
            & "('" & cboContractID.Text & "', SYSDATE,'" & LCase$(Environ$("USERNAME")) & "','" & cboCustomer.Text & "','" & cboCommodity.Text & "','" & txtRatePriority.Text & "','" & cboFromShipping.Text & "','" & cboToShippingLine.Text & "','" & txtLRNum.Text & "','" & cboArrivalType.Text & "', '" & txtFreeDays.Text & "','" & cboWeekends.Text & "','" & cboHolidays.Text & "', '" & txtBillDuration.Text & "','" & cboBillDurationUnit.Text & "','" & txtRateStartDate.Text & "','" & txtRate.Text & "','" & cboServiceCode.Text & "','" & cboBillByUnit.Text & "','" & cboStacking.Text & "','" & cboWarehouse.Text & "','" & cboBox.Text & "','" & cboBillToCust.Text & "','" & cboXFERCredit.Text & "','" & cboScanned.Text & "','" & cboSpecialReturn.Text & "')"
    OraDatabase.ExecuteSQL (SqlStmt)
    Insert_Record = True
    
End Function

Private Sub ClearXs()
    lblVerifyContract = ""
    lblVerifyPriority = ""
    lblVerifyFreeDays = ""
    lblVerifyWeekends = ""
    lblVerifyHolidays = ""
    lblVerifyDuration = ""
    lblVerifyDurationUnit = ""
    lblVerifyStartDate = ""
    lblVerifyRate = ""
    lblVerifyServiceCode = ""
    lblVerifyBillUnit = ""
    lblVerifyTransferCredit = ""
    lblVerifyArrivalType = ""
    lblVerifyCommodity = ""
    lblVerifyCustomer = ""
    lblVerifyFromShipping = ""
    lblVerifyToShipping = ""
    lblVerifyLRNum = ""
    lblVerifyWarehouse = ""
    lblVerifyBox = ""
    lblVerifyStacking = ""
    lblVerifyBillToCust = ""
    lblVerifyScanned = ""
    lblVerifySpecialReturn = ""
End Sub
Private Sub Reset_fields()
    cboContractID.ListIndex = 0
    txtRatePriority.Text = ""
    txtFreeDays = ""
    cboWeekends.ListIndex = 0
    cboHolidays.ListIndex = 0
    txtBillDuration = ""
    cboBillDurationUnit.ListIndex = 0
    txtRateStartDate = ""
    txtRate = ""
    cboServiceCode.ListIndex = 0
    cboBillByUnit.ListIndex = 0
    cboXFERCredit.ListIndex = 0
    cboArrivalType.ListIndex = 0
    cboCommodity.ListIndex = 0
    cboCustomer.ListIndex = 0
    cboFromShipping.ListIndex = 0
    cboToShippingLine.ListIndex = 0
    txtLRNum = ""
    cboWarehouse.ListIndex = 0
    cboBox.ListIndex = 0
    cboStacking.ListIndex = 0
    cboBillToCust.ListIndex = 0
    cboScanned.ListIndex = 0
    cboSpecialReturn.ListIndex = 0
End Sub


Private Sub Form_Load()
    
    SqlStmt = "SELECT CONTRACTID FROM CONTRACT ORDER BY CONTRACTID"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        cboContractID.AddItem dsSHORT_TERM_DATA.Fields("CONTRACTID").Value
        dsSHORT_TERM_DATA.MoveNext
    Wend

    SqlStmt = "SELECT UOM FROM UNITS ORDER BY UOM"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        cboBillByUnit.AddItem dsSHORT_TERM_DATA.Fields("UOM").Value
        dsSHORT_TERM_DATA.MoveNext
    Wend

    SqlStmt = "SELECT COMMODITY_CODE FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        cboCommodity.AddItem dsSHORT_TERM_DATA.Fields("COMMODITY_CODE").Value
        dsSHORT_TERM_DATA.MoveNext
    Wend

    cboCustomer.AddItem ""
    SqlStmt = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        cboCustomer.AddItem dsSHORT_TERM_DATA.Fields("CUSTOMER_ID").Value
        dsSHORT_TERM_DATA.MoveNext
    Wend

    cboFromShipping.AddItem ""
    SqlStmt = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        cboFromShipping.AddItem dsSHORT_TERM_DATA.Fields("CUSTOMER_ID").Value
        dsSHORT_TERM_DATA.MoveNext
    Wend
'    MsgBox cboFromShipping.ListCount
    
    cboToShippingLine.AddItem ""
    SqlStmt = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        cboToShippingLine.AddItem dsSHORT_TERM_DATA.Fields("CUSTOMER_ID").Value
        dsSHORT_TERM_DATA.MoveNext
    Wend
    
    cboWarehouse.AddItem ""
    cboWarehouse.AddItem "A"
    cboWarehouse.AddItem "B"
    cboWarehouse.AddItem "C"
    cboWarehouse.AddItem "D"
    cboWarehouse.AddItem "E"
    cboWarehouse.AddItem "F"
    cboWarehouse.AddItem "G"
    cboWarehouse.AddItem "H"
    cboWarehouse.AddItem "O"

    cboBox.AddItem ""
    cboBox.AddItem "1"
    cboBox.AddItem "2"
    cboBox.AddItem "3"
    cboBox.AddItem "4"
    cboBox.AddItem "5"
    cboBox.AddItem "6"
    cboBox.AddItem "7"
    cboBox.AddItem "8"
    
    cboStacking.AddItem ""
    cboStacking.AddItem "S"
    
    cboSpecialReturn.AddItem ""
    cboSpecialReturn.AddItem "REPACK"
    
    cboBillToCust.AddItem ""
    SqlStmt = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        cboBillToCust.AddItem dsSHORT_TERM_DATA.Fields("CUSTOMER_ID").Value
        dsSHORT_TERM_DATA.MoveNext
    Wend

    If PreLoad = "none" Then
        Call NoPrefill
    Else
        Call Prefill
    End If
    

End Sub

Private Sub NoPrefill()
    cboContractID.ListIndex = 0
    cboWeekends.ListIndex = 0
    cboHolidays.ListIndex = 0
    cboBillDurationUnit.ListIndex = 0
    cboServiceCode.ListIndex = 0
    cboBillByUnit.ListIndex = 0
    cboXFERCredit.ListIndex = 0
    cboArrivalType.ListIndex = 0
    cboCommodity.ListIndex = 0
    cboCustomer.ListIndex = 0
    cboFromShipping.ListIndex = 0
    cboToShippingLine.ListIndex = 0
    cboWarehouse.ListIndex = 0
    cboBox.ListIndex = 0
    cboStacking.ListIndex = 0
    cboBillToCust.ListIndex = 0
    cboScanned.ListIndex = 0
    cboSpecialReturn.ListIndex = 0
End Sub

Private Sub Prefill()
    SqlStmt = "SELECT * FROM RATE WHERE ROW_NUM = '" & PreLoad & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)

    txtRatePriority.Text = dsSHORT_TERM_DATA.Fields("RATEPRIORITY").Value & ""
    txtFreeDays.Text = dsSHORT_TERM_DATA.Fields("FREEDAYS").Value & ""
    txtBillDuration.Text = dsSHORT_TERM_DATA.Fields("BILLDURATION").Value & ""
    txtRateStartDate.Text = dsSHORT_TERM_DATA.Fields("RATESTARTDATE").Value & ""
    txtRate.Text = dsSHORT_TERM_DATA.Fields("RATE").Value & ""
    txtLRNum.Text = dsSHORT_TERM_DATA.Fields("ARRIVALNUMBER").Value & ""
    
    Call Preset_combo(cboContractID, dsSHORT_TERM_DATA.Fields("CONTRACTID").Value & "")
    Call Preset_combo(cboWeekends, dsSHORT_TERM_DATA.Fields("WEEKENDS").Value & "")
    Call Preset_combo(cboHolidays, dsSHORT_TERM_DATA.Fields("HOLIDAYS").Value & "")
    Call Preset_combo(cboBillDurationUnit, dsSHORT_TERM_DATA.Fields("BILLDURATIONUNIT").Value & "")
    Call Preset_combo(cboServiceCode, dsSHORT_TERM_DATA.Fields("SERVICECODE").Value & "")
    Call Preset_combo(cboBillByUnit, dsSHORT_TERM_DATA.Fields("UNIT").Value & "")
    Call Preset_combo(cboXFERCredit, dsSHORT_TERM_DATA.Fields("XFRDAYCREDIT").Value & "")
    Call Preset_combo(cboArrivalType, dsSHORT_TERM_DATA.Fields("ARRIVALTYPE").Value & "")
    Call Preset_combo(cboCommodity, dsSHORT_TERM_DATA.Fields("COMMODITYCODE").Value & "")
    Call Preset_combo(cboCustomer, dsSHORT_TERM_DATA.Fields("CUSTOMERID").Value & "")
    Call Preset_combo(cboFromShipping, dsSHORT_TERM_DATA.Fields("FRSHIPPINGLINE").Value & "")
    Call Preset_combo(cboToShippingLine, dsSHORT_TERM_DATA.Fields("TOSHIPPINGLINE").Value & "")
    Call Preset_combo(cboWarehouse, dsSHORT_TERM_DATA.Fields("WAREHOUSE").Value & "")
    Call Preset_combo(cboBox, dsSHORT_TERM_DATA.Fields("BOX").Value & "")
    Call Preset_combo(cboStacking, dsSHORT_TERM_DATA.Fields("STACKING").Value & "")
    Call Preset_combo(cboBillToCust, dsSHORT_TERM_DATA.Fields("BILLTOCUSTOMER").Value & "")
    Call Preset_combo(cboScanned, dsSHORT_TERM_DATA.Fields("SCANNEDORUNSCANNED").Value & "")
    Call Preset_combo(cboSpecialReturn, dsSHORT_TERM_DATA.Fields("SPECIALRETURN").Value & "")

End Sub

Private Sub Preset_combo(to_be_chosen As Object, Prefill_Value As String)

    If Prefill_Value = "" And to_be_chosen.List(0) = "" Then
        to_be_chosen.ListIndex = 0
    Else
        to_be_chosen.ListIndex = SendMessage(to_be_chosen.hwnd, CB_FINDSTRING, -1, ByVal Prefill_Value)
    End If
'    Dim indexcounter As Integer
'    indexcounter = 0
'
'    If Prefill_Value = "" Then
'        to_be_chosen.ListIndex = 0
'    Else
'        While indexcounter < to_be_chosen.ListCount
'            If to_be_chosen.Index(indexcounter).Value = Prefill_Value Then
'                to_be_chosen.ListIndex = indexcounter
'                indexcounter = to_be_chosen.ListCount
'            End If
'        Wend
'    End If
End Sub

