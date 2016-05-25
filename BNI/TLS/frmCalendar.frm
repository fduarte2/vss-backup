VERSION 5.00
Object = "{8E27C92E-1264-101C-8A2F-040224009C02}#7.0#0"; "MSCAL.OCX"
Begin VB.Form frmCalendar 
   Caption         =   "Calendar"
   ClientHeight    =   3225
   ClientLeft      =   8055
   ClientTop       =   3045
   ClientWidth     =   4575
   LinkTopic       =   "Form2"
   ScaleHeight     =   3225
   ScaleLeft       =   500
   ScaleMode       =   0  'User
   ScaleTop        =   300
   ScaleWidth      =   4575
   Begin MSACAL.Calendar Calendar1 
      Height          =   2655
      Left            =   240
      TabIndex        =   0
      Top             =   480
      Width           =   3975
      _Version        =   524288
      _ExtentX        =   7011
      _ExtentY        =   4683
      _StockProps     =   1
      BackColor       =   -2147483633
      Year            =   2006
      Month           =   3
      Day             =   9
      DayLength       =   1
      MonthLength     =   1
      DayFontColor    =   0
      FirstDay        =   7
      GridCellEffect  =   1
      GridFontColor   =   10485760
      GridLinesColor  =   -2147483632
      ShowDateSelectors=   -1  'True
      ShowDays        =   -1  'True
      ShowHorizontalGrid=   -1  'True
      ShowTitle       =   -1  'True
      ShowVerticalGrid=   -1  'True
      TitleFontColor  =   10485760
      ValueIsNull     =   0   'False
      BeginProperty DayFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Arial"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty GridFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Arial"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty TitleFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Arial"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "Double click on a date to select it"
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
      Height          =   240
      Left            =   480
      TabIndex        =   1
      Top             =   120
      Width           =   3465
   End
End
Attribute VB_Name = "frmCalendar"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit       'Rudy

Private Sub Calendar1_Click()
    
    ''frmPopDailyHrTn.txtDate.Text = Format(Calendar1.Value, "mm/dd/yyyy")
    ''Unload Me

End Sub

Private Sub Calendar1_DblClick()

    ''If dateOption = 1 Then
        frmRptMenu.txtDate.Text = Format(Calendar1.Value, "mm/dd/yyyy")
    ''ElseIf dateOption = 2 Then
    ''    frmSetFreeTime.lblStartFTDate.Caption = Format(Calendar1.Value, "mm/dd/yyyy")
    ''End If
    
    Unload Me

End Sub

Private Sub Form_Load()
    
    Me.Calendar1.Month = Format(Now, "mm")
    Me.Calendar1.Day = Format(Now, "dd")
    Me.Calendar1.Year = Format(Now, "yyyy")
    
End Sub
