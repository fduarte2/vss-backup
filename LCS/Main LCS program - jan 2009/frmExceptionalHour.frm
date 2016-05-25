VERSION 5.00
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Begin VB.Form frmExceptionalHour 
   Caption         =   "Exceptional Hour Reporting"
   ClientHeight    =   5715
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   5550
   LinkTopic       =   "Form1"
   ScaleHeight     =   5715
   ScaleWidth      =   5550
   StartUpPosition =   3  'Windows Default
   Begin VB.TextBox txtDayHours 
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
      Left            =   4440
      MaxLength       =   2
      TabIndex        =   7
      Text            =   "11"
      Top             =   2220
      Width           =   615
   End
   Begin VB.TextBox txtWeek 
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
      Left            =   4440
      MaxLength       =   3
      TabIndex        =   6
      Text            =   "80"
      Top             =   3300
      Width           =   615
   End
   Begin VB.CheckBox chkPrintDetailDay 
      Caption         =   "Print Detail?"
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
      Left            =   1200
      TabIndex        =   5
      Top             =   2640
      Width           =   2055
   End
   Begin VB.CheckBox chkPrintDetailWeek 
      Caption         =   "Print Detail?"
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
      Left            =   1200
      TabIndex        =   4
      Top             =   3720
      Width           =   2055
   End
   Begin VB.CommandButton cmdGetReport 
      Caption         =   "Get Report"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   615
      Left            =   420
      TabIndex        =   3
      Top             =   4920
      Width           =   1935
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "Exit"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   615
      Left            =   3180
      TabIndex        =   2
      Top             =   4950
      Width           =   1935
   End
   Begin VB.Frame Frame1 
      Height          =   735
      Left            =   120
      TabIndex        =   0
      Top             =   180
      Width           =   5295
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         Caption         =   "Exceptional Hours Reporting"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   360
         Left            =   480
         TabIndex        =   1
         Top             =   240
         Width           =   4050
      End
   End
   Begin Crystal.CrystalReport ReportEngine 
      Left            =   4080
      Top             =   4080
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin SSCalendarWidgets_A.SSDateCombo txtDate 
      Height          =   375
      Left            =   1320
      TabIndex        =   8
      Top             =   1200
      Width           =   2655
      _Version        =   65543
      _ExtentX        =   4683
      _ExtentY        =   661
      _StockProps     =   93
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty DropDownFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ShowCentury     =   -1  'True
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "Cut off number for hours in a day?"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   240
      Left            =   480
      TabIndex        =   10
      Top             =   2280
      Width           =   3450
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      Caption         =   "Cut off number for hours in a week?"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   240
      Left            =   480
      TabIndex        =   9
      Top             =   3360
      Width           =   3600
   End
End
Attribute VB_Name = "frmExceptionalHour"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit          '5/2/2007 HD2759 Rudy:

Private Sub cmdExit_Click()
    Unload frmExceptionalHour
    frmException.Show
End Sub

Private Sub cmdGetReport_Click()
    Dim rsHourly As Object, CurrDay As Integer, prev_Week As Date, dtReportDate As Date
    Dim From_Date As Date, To_Date As Date
      
    dtReportDate = CDate(txtDate.Text)
    CurrDay = Weekday(Format(CDate(txtDate.Text), "mm/dd/yyyy"))
    If CurrDay = vbMonday Then
       prev_Week = dtReportDate
    ElseIf CurrDay = vbSunday Then
       prev_Week = dtReportDate - 6
    Else
       prev_Week = dtReportDate + 2 - Val(Format(dtReportDate, "w"))
    End If
    prev_Week = Format(prev_Week, "mm/dd/yyyy")
    
    From_Date = prev_Week
    To_Date = From_Date + 6
    
    Me.ReportEngine.LogOnServer "PDSORA7", "LCS", "", "LABOR", "LABOR"   'ODBCDriver, ServerName, Database, UserName, Password
    Me.ReportEngine.DiscardSavedData = True    'To refresh the data
    Me.ReportEngine.Formulas(0) = "From_Date=Date(" & Year(From_Date) & "," & Month(From_Date) & "," & day(From_Date) & ")"
    Me.ReportEngine.Formulas(1) = "To_Date=Date(" & Year(To_Date) & "," & Month(To_Date) & "," & day(To_Date) & ")"
    Me.ReportEngine.Formulas(2) = "NumberOfHours=" & Me.txtDayHours.Text
    ' Depending on options chosen launch crystal reports.
    If Me.chkPrintDetailDay.Value = 1 Then
       Me.ReportEngine.ReportFileName = App.Path & "\HoursInDayDet.rpt"
    Else
       Me.ReportEngine.ReportFileName = App.Path & "\HoursInDaySum.rpt"
    End If
    Me.ReportEngine.Destination = crptToWindow
    Me.ReportEngine.Action = 1
    
    ' Print Over 80 hours report
    Me.ReportEngine.Formulas(2) = "NumberOfHours=" & Me.txtWeek.Text
    
    If Me.chkPrintDetailWeek.Value = 1 Then
       Me.ReportEngine.ReportFileName = App.Path & "\HoursWeekDet.rpt"
    Else
       Me.ReportEngine.ReportFileName = App.Path & "\HoursWeekSum.rpt"
    End If
    Me.ReportEngine.Action = 1
    Me.ReportEngine.Reset
End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    txtDate.Text = Format(findLastSunday(Date), "mm/dd/yyyy")
End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
    Unload frmExceptionalHour
    frmException.Show
End Sub

Private Sub Form_Unload(Cancel As Integer)
    Unload frmExceptionalHour
    frmException.Show
End Sub
