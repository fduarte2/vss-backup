VERSION 5.00
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Begin VB.Form frmRptMenu 
   Caption         =   "TLS-Report Menu"
   ClientHeight    =   4155
   ClientLeft      =   5055
   ClientTop       =   2640
   ClientWidth     =   4830
   LinkTopic       =   "Form1"
   ScaleHeight     =   4155
   ScaleWidth      =   4830
   Begin VB.Frame Frame3 
      Height          =   2175
      Left            =   120
      TabIndex        =   6
      Top             =   960
      Width           =   4455
      Begin VB.CommandButton cmdDailyTrkLogGrpByCom 
         Caption         =   "Daily Truck Log Report-Group By Commodity"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   120
         TabIndex        =   9
         Top             =   840
         Width           =   4095
      End
      Begin VB.CommandButton cmdDaySumByGrp 
         Caption         =   "Daily Summary Report"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   120
         TabIndex        =   8
         Top             =   1440
         Width           =   4095
      End
      Begin VB.CommandButton cmdDailyTruckLog 
         Caption         =   "Daily Truck Log Report"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   120
         TabIndex        =   7
         Top             =   240
         Width           =   4095
      End
   End
   Begin Crystal.CrystalReport Report 
      Left            =   240
      Top             =   4080
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      WindowWidth     =   800
      WindowHeight    =   600
      PrintFileLinesPerPage=   60
   End
   Begin VB.Frame Frame2 
      Height          =   855
      Left            =   120
      TabIndex        =   2
      Top             =   3120
      Width           =   4455
      Begin VB.CommandButton cmdCancel 
         Caption         =   "Cancel"
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
         TabIndex        =   3
         Top             =   240
         Width           =   1335
      End
   End
   Begin VB.Frame Frame1 
      Height          =   855
      Left            =   120
      TabIndex        =   1
      Top             =   120
      Width           =   4455
      Begin VB.CommandButton cmdCalendar 
         Caption         =   "..."
         Height          =   375
         Left            =   3840
         TabIndex        =   5
         Top             =   240
         Width           =   375
      End
      Begin VB.TextBox txtDate 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H00C0FFFF&
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
         Height          =   375
         Left            =   1560
         TabIndex        =   0
         Text            =   "mm/dd/yyyy"
         Top             =   240
         Width           =   2175
      End
      Begin VB.Label lblDate 
         Alignment       =   2  'Center
         Caption         =   "Date (mm/dd/yyyy)"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   120
         TabIndex        =   4
         Top             =   240
         Width           =   1215
      End
   End
End
Attribute VB_Name = "frmRptMenu"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit       'Rudy

Private Sub cmdDailyTrkLogGrpByCom_Click()
    Dim arrDate() As String
    Dim strParaField As String
    
    If Len(Me.txtDate.Text) = 0 Then
        MsgBox "Please provide a date"
        Exit Sub
    End If
    
    If (IsDate(Trim(Me.txtDate.Text)) = False) Or (Len(Me.txtDate.Text) <> 10) Then
        MsgBox "Please provide a valid date following 'mm/dd/yyyy' format."
        Me.txtDate.SetFocus
        Exit Sub
    End If
    
    arrDate = Split(Me.txtDate.Text, "/")
    strParaField = "Log_Date;Date(" & arrDate(2) & "," & arrDate(0) & "," & _
                arrDate(1) & ");TRUE"
    Me.Report.LogOnServer "pdsora7.dll", DB, "", User, Pwd
    Me.Report.ReportFileName = App.Path & "\" & "Daily-Truck-Log-Group-By-Comm.rpt"
    Me.Report.WindowShowRefreshBtn = True
    Me.Report.ParameterFields(0) = strParaField
    ''Me.Report.WindowHeight = 0.6 * (Screen.Height)
    ''Me.Report.WindowWidth = 0.6 * (Screen.Width)
    ''Me.Report.ReplaceSelectionFormula (sRecSel)
    Me.Report.action = 0
End Sub

Private Sub cmdDailyTruckLog_Click()

    Dim arrDate() As String
    Dim strParaField As String
    
    If Len(Me.txtDate.Text) = 0 Then
        MsgBox "Please provide a date"
        Exit Sub
    End If
    
    If (IsDate(Trim(Me.txtDate.Text)) = False) Or (Len(Me.txtDate.Text) <> 10) Then
        MsgBox "Please provide a valid date following 'mm/dd/yyyy' format."
        Me.txtDate.SetFocus
        Exit Sub
    End If
    
    arrDate = Split(Me.txtDate.Text, "/")
    strParaField = "Log_Date;Date(" & arrDate(2) & "," & arrDate(0) & "," & _
                arrDate(1) & ");TRUE"
    Me.Report.LogOnServer "pdsora7.dll", DB, "", User, Pwd
    Me.Report.ReportFileName = App.Path & "\" & "Daily-Truck-Log.rpt"
    Me.Report.WindowShowRefreshBtn = True
    Me.Report.ParameterFields(0) = strParaField
    ''Me.Report.WindowHeight = 0.6 * (Screen.Height)
    ''Me.Report.WindowWidth = 0.6 * (Screen.Width)
    ''Me.Report.ReplaceSelectionFormula (sRecSel)
    Me.Report.action = 0
End Sub


Private Sub cmdCalendar_Click()
    frmCalendar.Show
End Sub

Private Sub cmdCancel_Click()
    Unload Me
End Sub

Private Sub cmdDailyTruckLog1_Click()

End Sub

Private Sub cmdDaySumByGrp_Click()
    Dim arrDate() As String
    Dim strParaField As String
    
    If Len(Me.txtDate.Text) = 0 Then
        MsgBox "Please provide a date"
        Exit Sub
    End If
    
    If (IsDate(Trim(Me.txtDate.Text)) = False) Or (Len(Me.txtDate.Text) <> 10) Then
        MsgBox "Please provide a valid date following 'mm/dd/yyyy' format."
        Me.txtDate.SetFocus
        Exit Sub
    End If
    
    arrDate = Split(Me.txtDate.Text, "/")
    strParaField = "Log_Date;Date(" & arrDate(2) & "," & arrDate(0) & "," & _
                arrDate(1) & ");TRUE"
    Me.Report.LogOnServer "pdsora7.dll", DB, "", User, Pwd
    Me.Report.ReportFileName = App.Path & "\" & "Daily-Truck-Log-Summary.rpt"
    Me.Report.WindowShowRefreshBtn = True
    Me.Report.ParameterFields(0) = strParaField
    ''Me.Report.WindowHeight = 0.6 * (Screen.Height)
    ''Me.Report.WindowWidth = 0.6 * (Screen.Width)
    ''Me.Report.ReplaceSelectionFormula (sRecSel)
    Me.Report.action = 0
    
    '' Call PrtDailySummaryByGrp(Me.txtDate.Text)
End Sub

