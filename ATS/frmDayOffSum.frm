VERSION 5.00
Begin VB.Form frmDayOffSum 
   BackColor       =   &H00FFFFFF&
   Caption         =   "Time Off Summary"
   ClientHeight    =   10020
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   9690
   LinkTopic       =   "Form1"
   ScaleHeight     =   10020
   ScaleWidth      =   9690
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton btnPrint 
      Caption         =   "Print"
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
      TabIndex        =   10
      Top             =   9360
      Width           =   2895
   End
   Begin VB.TextBox txtSick 
      Height          =   1455
      Left            =   480
      Locked          =   -1  'True
      MultiLine       =   -1  'True
      ScrollBars      =   2  'Vertical
      TabIndex        =   9
      Top             =   7560
      Width           =   8655
   End
   Begin VB.TextBox txtPer 
      Height          =   1455
      Left            =   480
      Locked          =   -1  'True
      MultiLine       =   -1  'True
      ScrollBars      =   2  'Vertical
      TabIndex        =   7
      Top             =   5280
      Width           =   8655
   End
   Begin VB.TextBox txtVac 
      Height          =   1455
      Left            =   480
      Locked          =   -1  'True
      MultiLine       =   -1  'True
      ScrollBars      =   2  'Vertical
      TabIndex        =   4
      Top             =   3000
      Width           =   8655
   End
   Begin VB.Label lblSick 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Sick:"
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
      Left            =   480
      TabIndex        =   8
      Top             =   7200
      Width           =   4815
   End
   Begin VB.Label lblPers 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Personal:"
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
      Left            =   480
      TabIndex        =   6
      Top             =   4920
      Width           =   4695
   End
   Begin VB.Label lblVac 
      BackColor       =   &H00FFFFFF&
      Caption         =   "Vacation:"
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
      Left            =   480
      TabIndex        =   5
      Top             =   2640
      Width           =   4695
   End
   Begin VB.Label lblCurAsOf 
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
      Left            =   480
      TabIndex        =   3
      Top             =   1440
      Width           =   7575
   End
   Begin VB.Label lblEmpName 
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
      Left            =   480
      TabIndex        =   2
      Top             =   1080
      Width           =   3975
   End
   Begin VB.Label lblEmpID 
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
      Left            =   480
      TabIndex        =   1
      Top             =   720
      Width           =   3135
   End
   Begin VB.Label Label1 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      Caption         =   "TIME OFF SUMMARY"
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
      Left            =   2880
      TabIndex        =   0
      Top             =   120
      Width           =   3855
   End
End
Attribute VB_Name = "frmDayOffSum"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim dsAGG_TIMES As Object
Dim dsAGG_TIMES_PRINT As Object
Dim strName As String

Public strYear As String
Public strEmpID As String

Private Sub btnPrint_Click()
' by nature of the calling form having it's "print" button disabled if there are no valid records,
' I need not do the check for "if any approved records this year" in the print function

Printer.Orientation = 1
Printer.FontBold = False

Printer.FontSize = 16
Printer.Print
Printer.Print Tab(30); "TIME OFF SUMMARY"

Printer.FontSize = 9
Printer.Print ""
Printer.Print Tab(60); "Printed on:  " & Format(Now, "MM/DD/YYYY HH:MM")

Printer.FontSize = 12
Printer.Print

Printer.Print "Employee ID:  " & strEmpID
Printer.Print "Employee:  " & strName
Printer.Print lblCurAsOf.Caption
Printer.Print ""
Printer.Print ""
Printer.Print ""
Printer.Print ""

' First, vacation
Printer.Print lblVac.Caption
Printer.Print txtVac.Text
'strSql = "SELECT TO_CHAR(MON_DATE, 'MM/DD/YYYY') THE_MON, TO_CHAR(TUE_DATE, 'MM/DD/YYYY') THE_TUE, " _
'        & "TO_CHAR(WED_DATE, 'MM/DD/YYYY') THE_WED, TO_CHAR(THU_DATE, 'MM/DD/YYYY') THE_THU, " _
'        & "TO_CHAR(FRI_DATE, 'MM/DD/YYYY') THE_FRI, TO_CHAR(SAT_DATE, 'MM/DD/YYYY') THE_SAT, " _
'        & "TO_CHAR(SUN_DATE, 'MM/DD/YYYY') THE_SUN, " _
'        & "MON_VACATION, TUE_VACATION, WED_VACATION, THU_VACATION, FRI_VACATION, SAT_VACATION, SUN_VACATION " _
'        & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND " _
'        & "FRI_DATE >= '01-JAN-" & strYear & "' AND STATUS = 'APPROVED' AND " _
'        & "CONDITIONAL_SUBMISSION = 'N' AND PAYROLL_DATETIME IS NOT NULL ORDER BY WEEK_START_MONDAY"
'Set dsAGG_TIMES_PRINT = OraDatabase.DbCreateDynaset(strSql, 0&)
'dsAGG_TIMES_PRINT.movefirst
'While Not dsAGG_TIMES_PRINT.EOF
'    If dsAGG_TIMES_PRINT.Fields("MON_VACATION").Value <> 0 Then
'        Printer.Print "Mon " & dsAGG_TIMES_PRINT.Fields("THE_MON").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("MON_VACATION").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("TUE_VACATION").Value <> 0 Then
'        Printer.Print "Tue " & dsAGG_TIMES_PRINT.Fields("THE_TUE").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("TUE_VACATION").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("WED_VACATION").Value <> 0 Then
'        Printer.Print "Wed " & dsAGG_TIMES_PRINT.Fields("THE_WED").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("WED_VACATION").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("THU_VACATION").Value <> 0 Then
'        Printer.Print "Thu " & dsAGG_TIMES_PRINT.Fields("THE_THU").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("THU_VACATION").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("FRI_VACATION").Value <> 0 Then
'        Printer.Print "Fri " & dsAGG_TIMES_PRINT.Fields("THE_FRI").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("FRI_VACATION").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("SAT_VACATION").Value <> 0 Then
'        Printer.Print "Sat " & dsAGG_TIMES_PRINT.Fields("THE_SAT").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("SAT_VACATION").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("SUN_VACATION").Value <> 0 Then
'        Printer.Print "Sun " & dsAGG_TIMES_PRINT.Fields("THE_SUN").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("SUN_VACATION").Value
'    End If
'    dsAGG_TIMES_PRINT.Movenext
'Wend
'
'strSql = "SELECT NVL(SUM(WEEK_TOTAL_VACATION), 0) THE_VAC " _
'        & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND FRI_DATE >= '01-JAN-" & strYear & "' " _
'        & "AND STATUS = 'APPROVED' AND CONDITIONAL_SUBMISSION = 'N'"
'Set dsAGG_TIMES_PRINT = OraDatabase.DbCreateDynaset(strSql, 0&)
'Printer.Print "Total Used: " & dsAGG_TIMES_PRINT.Fields("THE_VAC").Value
'
'strSql = "SELECT VACATION_YTD_REMAIN FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmpID & "'"
'Set dsAGG_TIMES_PRINT = OraDatabase.DbCreateDynaset(strSql, 0&)
'Printer.Print "YTD Remaining: " & Round(dsAGG_TIMES_PRINT.Fields("VACATION_YTD_REMAIN").Value, 2)

Printer.Print ""
Printer.Print ""
Printer.Print ""
Printer.Print ""

'next up, sick
Printer.Print lblSick.Caption
Printer.Print txtSick.Text

'strSql = "SELECT TO_CHAR(MON_DATE, 'MM/DD/YYYY') THE_MON, TO_CHAR(TUE_DATE, 'MM/DD/YYYY') THE_TUE, " _
'        & "TO_CHAR(WED_DATE, 'MM/DD/YYYY') THE_WED, TO_CHAR(THU_DATE, 'MM/DD/YYYY') THE_THU, " _
'        & "TO_CHAR(FRI_DATE, 'MM/DD/YYYY') THE_FRI, TO_CHAR(SAT_DATE, 'MM/DD/YYYY') THE_SAT, " _
'        & "TO_CHAR(SUN_DATE, 'MM/DD/YYYY') THE_SUN, " _
'        & "MON_SICK, TUE_SICK, WED_SICK, THU_SICK, FRI_SICK, SAT_SICK, SUN_SICK " _
'        & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND " _
'        & "FRI_DATE >= '01-JAN-" & strYear & "' AND STATUS = 'APPROVED' AND " _
'        & "CONDITIONAL_SUBMISSION = 'N' ORDER BY WEEK_START_MONDAY"
'Set dsAGG_TIMES_PRINT = OraDatabase.DbCreateDynaset(strSql, 0&)
'dsAGG_TIMES_PRINT.movefirst
'While Not dsAGG_TIMES_PRINT.EOF
'    If dsAGG_TIMES_PRINT.Fields("MON_SICK").Value <> 0 Then
'        Printer.Print "Mon " & dsAGG_TIMES_PRINT.Fields("THE_MON").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("MON_SICK").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("TUE_SICK").Value <> 0 Then
'        Printer.Print "Tue " & dsAGG_TIMES_PRINT.Fields("THE_TUE").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("TUE_SICK").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("WED_SICK").Value <> 0 Then
'        Printer.Print "Wed " & dsAGG_TIMES_PRINT.Fields("THE_WED").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("WED_SICK").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("THU_SICK").Value <> 0 Then
'        Printer.Print "Thu " & dsAGG_TIMES_PRINT.Fields("THE_THU").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("THU_SICK").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("FRI_SICK").Value <> 0 Then
'        Printer.Print "Fri " & dsAGG_TIMES_PRINT.Fields("THE_FRI").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("FRI_SICK").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("SAT_SICK").Value <> 0 Then
'        Printer.Print "Sat " & dsAGG_TIMES_PRINT.Fields("THE_SAT").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("SAT_SICK").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("SUN_SICK").Value <> 0 Then
'        Printer.Print "Sun " & dsAGG_TIMES_PRINT.Fields("THE_SUN").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("SUN_SICK").Value
'    End If
'    dsAGG_TIMES_PRINT.Movenext
'Wend
'
'strSql = "SELECT NVL(SUM(WEEK_TOTAL_SICK), 0) THE_SICK " _
'        & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND FRI_DATE >= '01-JAN-" & strYear & "' " _
'        & "AND STATUS = 'APPROVED' AND CONDITIONAL_SUBMISSION = 'N'"
'Set dsAGG_TIMES_PRINT = OraDatabase.DbCreateDynaset(strSql, 0&)
'Printer.Print "Total Used: " & dsAGG_TIMES_PRINT.Fields("THE_SICK").Value
'
'strSql = "SELECT SICK_YTD_REMAIN FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmpID & "'"
'Set dsAGG_TIMES_PRINT = OraDatabase.DbCreateDynaset(strSql, 0&)
'Printer.Print "YTD Remaining: " & Round(dsAGG_TIMES_PRINT.Fields("SICK_YTD_REMAIN").Value, 2)

Printer.Print ""
Printer.Print ""
Printer.Print ""
Printer.Print ""

'last, personal
Printer.Print lblPers.Caption
Printer.Print txtPer.Text
'strSql = "SELECT TO_CHAR(MON_DATE, 'MM/DD/YYYY') THE_MON, TO_CHAR(TUE_DATE, 'MM/DD/YYYY') THE_TUE, " _
'        & "TO_CHAR(WED_DATE, 'MM/DD/YYYY') THE_WED, TO_CHAR(THU_DATE, 'MM/DD/YYYY') THE_THU, " _
'        & "TO_CHAR(FRI_DATE, 'MM/DD/YYYY') THE_FRI, TO_CHAR(SAT_DATE, 'MM/DD/YYYY') THE_SAT, " _
'        & "TO_CHAR(SUN_DATE, 'MM/DD/YYYY') THE_SUN, " _
'        & "MON_PERSONAL, TUE_PERSONAL, WED_PERSONAL, THU_PERSONAL, FRI_PERSONAL, SAT_PERSONAL, SUN_PERSONAL " _
'        & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND " _
'        & "FRI_DATE >= '01-JAN-" & strYear & "' AND STATUS = 'APPROVED' AND " _
'        & "CONDITIONAL_SUBMISSION = 'N' ORDER BY WEEK_START_MONDAY"
'Set dsAGG_TIMES_PRINT = OraDatabase.DbCreateDynaset(strSql, 0&)
'dsAGG_TIMES_PRINT.movefirst
'While Not dsAGG_TIMES_PRINT.EOF
'    If dsAGG_TIMES_PRINT.Fields("MON_PERSONAL").Value <> 0 Then
'        Printer.Print "Mon " & dsAGG_TIMES_PRINT.Fields("THE_MON").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("MON_PERSONAL").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("TUE_PERSONAL").Value <> 0 Then
'        Printer.Print "Tue " & dsAGG_TIMES_PRINT.Fields("THE_TUE").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("TUE_PERSONAL").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("WED_PERSONAL").Value <> 0 Then
'        Printer.Print "Wed " & dsAGG_TIMES_PRINT.Fields("THE_WED").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("WED_PERSONAL").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("THU_PERSONAL").Value <> 0 Then
'        Printer.Print "Thu " & dsAGG_TIMES_PRINT.Fields("THE_THU").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("THU_PERSONAL").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("FRI_PERSONAL").Value <> 0 Then
'        Printer.Print "Fri " & dsAGG_TIMES_PRINT.Fields("THE_FRI").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("FRI_PERSONAL").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("SAT_PERSONAL").Value <> 0 Then
'        Printer.Print "Sat " & dsAGG_TIMES_PRINT.Fields("THE_SAT").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("SAT_PERSONAL").Value
'    End If
'    If dsAGG_TIMES_PRINT.Fields("SUN_PERSONAL").Value <> 0 Then
'        Printer.Print "Sun " & dsAGG_TIMES_PRINT.Fields("THE_SUN").Value & " Timesheet -- " & dsAGG_TIMES_PRINT.Fields("SUN_PERSONAL").Value
'    End If
'    dsAGG_TIMES_PRINT.Movenext
'Wend
'
'strSql = "SELECT NVL(SUM(WEEK_TOTAL_PERSONAL), 0) THE_PER " _
'        & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND FRI_DATE >= '01-JAN-" & strYear & "' " _
'        & "AND STATUS = 'APPROVED' AND CONDITIONAL_SUBMISSION = 'N'"
'Set dsAGG_TIMES_PRINT = OraDatabase.DbCreateDynaset(strSql, 0&)
'Printer.Print "Total Used: " & dsAGG_TIMES_PRINT.Fields("THE_PER").Value
'
'strSql = "SELECT PERSONAL_YTD_REMAIN FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmpID & "'"
'Set dsAGG_TIMES_PRINT = OraDatabase.DbCreateDynaset(strSql, 0&)
'Printer.Print "YTD Remaining: " & Round(dsAGG_TIMES_PRINT.Fields("PERSONAL_YTD_REMAIN").Value, 2)

Printer.EndDoc

End Sub

Private Sub form_load()
    Dim AsteriskString As String
    Dim LastLineAditionVac As String
    Dim LastLineAditionSick As String
    Dim LastLineAditionPers As String
    Dim TotalPending As Long

    strSql = "SELECT FIRST_NAME || ' ' || LAST_NAME THE_NAME FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmpID & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    lblEmpID.Caption = "Employee:  " & strEmpID
    strName = dsSHORT_TERM_DATA.Fields("THE_NAME").Value
    lblEmpName.Caption = "Name:  " & strName
    
    txtVac.Text = ""
    txtPer.Text = ""
    txtSick.Text = ""
        
    
    strSql = "SELECT NVL(TO_CHAR(MAX(WEEK_START_MONDAY), 'MM/DD/YYYY'), '---') THE_WEEK FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" _
            & strEmpID & "' AND FRI_DATE >= '01-JAN-" & strYear & "' AND STATUS = 'APPROVED' AND CONDITIONAL_SUBMISSION = 'N' AND PAYROLL_DATETIME IS NOT NULL"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsSHORT_TERM_DATA.Fields("THE_WEEK").Value = "---" Then
        ' there are no approved records for this year, break the form load.  Should never happen.
        lblCurAsOf.Caption = "No Approved/Processed Timesheets yet for " & strName & " this year.  Historical Summary Disabled."
        btnPrint.Enabled = False
    Else
        ' main page content here
        lblCurAsOf.Caption = "As Of " & dsSHORT_TERM_DATA.Fields("THE_WEEK").Value & ":"
    
        strSql = "SELECT YTD_WEEK_START_VACATION_BAL, YTD_WEEK_START_SICK_BAL, YTD_WEEK_START_PERSONAL_BAL, TO_CHAR(WEEK_START_MONDAY, 'MM/DD/YYYY') THE_WEEK FROM " _
            & "TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND FRI_DATE >= '01-JAN-" & strYear & "' ORDER BY WEEK_START_MONDAY"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        lblVac.Caption = "Vacation:  (On " & dsSHORT_TERM_DATA.Fields("THE_WEEK").Value & ":  " & dsSHORT_TERM_DATA.Fields("YTD_WEEK_START_VACATION_BAL").Value & ")"
        lblPers.Caption = "Personal:  (On " & dsSHORT_TERM_DATA.Fields("THE_WEEK").Value & ":  " & dsSHORT_TERM_DATA.Fields("YTD_WEEK_START_PERSONAL_BAL").Value & ")"
        lblSick.Caption = "Sick:  (On " & dsSHORT_TERM_DATA.Fields("THE_WEEK").Value & ":  " & dsSHORT_TERM_DATA.Fields("YTD_WEEK_START_SICK_BAL").Value & ")"
    

        strSql = "SELECT TO_CHAR(MON_DATE, 'MM/DD/YYYY') THE_MON, TO_CHAR(TUE_DATE, 'MM/DD/YYYY') THE_TUE, " _
                & "TO_CHAR(WED_DATE, 'MM/DD/YYYY') THE_WED, TO_CHAR(THU_DATE, 'MM/DD/YYYY') THE_THU, " _
                & "TO_CHAR(FRI_DATE, 'MM/DD/YYYY') THE_FRI, TO_CHAR(SAT_DATE, 'MM/DD/YYYY') THE_SAT, " _
                & "TO_CHAR(SUN_DATE, 'MM/DD/YYYY') THE_SUN, MON_PERSONAL, MON_SICK, MON_VACATION, " _
                & "TUE_PERSONAL, TUE_SICK, TUE_VACATION, WED_PERSONAL, WED_SICK, WED_VACATION, " _
                & "THU_PERSONAL, THU_SICK, THU_VACATION, FRI_PERSONAL, FRI_SICK, FRI_VACATION, " _
                & "SAT_PERSONAL, SAT_SICK, SAT_VACATION, SUN_PERSONAL, SUN_SICK, SUN_VACATION, NVL(TO_CHAR(YTD_WEEK_END_SICK_BAL), 'NONE') THE_BAL " _
                & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND " _
                & "FRI_DATE >= '01-JAN-" & strYear & "' AND STATUS = 'APPROVED' AND " _
                & "CONDITIONAL_SUBMISSION = 'N' " _
                & "ORDER BY WEEK_START_MONDAY"
        Set dsAGG_TIMES = OraDatabase.DbCreateDynaset(strSql, 0&)
        dsAGG_TIMES.movefirst
        
        While Not dsAGG_TIMES.EOF
            ' check to see if any "HR Edits" for this week
            strSql = "SELECT VACATION_CHANGE, PERSONAL_CHANGE, SICK_CHANGE, UPDATE_COMMENTS FROM YTD_ACCRUAL_CHANGES WHERE EMPLOYEE_ID = '" _
                & strEmpID & "' AND EFFECTIVE_WEEK >= TO_DATE('" & dsAGG_TIMES.Fields("THE_MON").Value & "', 'MM/DD/YYYY') AND " _
                & "EFFECTIVE_WEEK <= TO_DATE('" & dsAGG_TIMES.Fields("THE_SUN").Value & "', 'MM/DD/YYYY')"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
            If dsSHORT_TERM_DATA.Recordcount <> 0 Then
                If dsSHORT_TERM_DATA.Fields("VACATION_CHANGE").Value <> 0 Then
                    txtVac.Text = txtVac.Text & "Edit: " & dsSHORT_TERM_DATA.Fields("VACATION_CHANGE").Value & " Reason: " & dsSHORT_TERM_DATA.Fields("UPDATE_COMMENTS").Value & vbCrLf
                End If
                If dsSHORT_TERM_DATA.Fields("PERSONAL_CHANGE").Value <> 0 Then
                    txtPer.Text = txtPer.Text & "Edit: " & dsSHORT_TERM_DATA.Fields("PERSONAL_CHANGE").Value & " Reason: " & dsSHORT_TERM_DATA.Fields("UPDATE_COMMENTS").Value & vbCrLf
                End If
                If dsSHORT_TERM_DATA.Fields("SICK_CHANGE").Value <> 0 Then
                    txtSick.Text = txtSick.Text & "Edit: " & dsSHORT_TERM_DATA.Fields("SICK_CHANGE").Value & " Reason: " & dsSHORT_TERM_DATA.Fields("UPDATE_COMMENTS").Value & vbCrLf
                End If
            End If
            
            ' for timesheets not yet ADP-uploaded, "asteriskise" the line.
            If dsAGG_TIMES.Fields("THE_BAL").Value = "NONE" Then
                AsteriskString = "*"
            Else
                AsteriskString = ""
            End If
            
            ' for every row, output any given time off
            ' This is going to be 21 steps, 7 days for 3 boxes per loop.  No, I can't think up another way.
            If dsAGG_TIMES.Fields("MON_VACATION").Value <> 0 Then
                txtVac.Text = txtVac.Text & "Mon " & dsAGG_TIMES.Fields("THE_MON").Value & " Timesheet -- " & dsAGG_TIMES.Fields("MON_VACATION").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("TUE_VACATION").Value <> 0 Then
                txtVac.Text = txtVac.Text & "Tue " & dsAGG_TIMES.Fields("THE_TUE").Value & " Timesheet -- " & dsAGG_TIMES.Fields("TUE_VACATION").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("WED_VACATION").Value <> 0 Then
                txtVac.Text = txtVac.Text & "Wed " & dsAGG_TIMES.Fields("THE_WED").Value & " Timesheet -- " & dsAGG_TIMES.Fields("WED_VACATION").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("THU_VACATION").Value <> 0 Then
                txtVac.Text = txtVac.Text & "Thu " & dsAGG_TIMES.Fields("THE_THU").Value & " Timesheet -- " & dsAGG_TIMES.Fields("THU_VACATION").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("FRI_VACATION").Value <> 0 Then
                txtVac.Text = txtVac.Text & "Fri " & dsAGG_TIMES.Fields("THE_FRI").Value & " Timesheet -- " & dsAGG_TIMES.Fields("FRI_VACATION").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("SAT_VACATION").Value <> 0 Then
                txtVac.Text = txtVac.Text & "Sat " & dsAGG_TIMES.Fields("THE_SAT").Value & " Timesheet -- " & dsAGG_TIMES.Fields("SAT_VACATION").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("SUN_VACATION").Value <> 0 Then
                txtVac.Text = txtVac.Text & "Sun " & dsAGG_TIMES.Fields("THE_SUN").Value & " Timesheet -- " & dsAGG_TIMES.Fields("SUN_VACATION").Value & AsteriskString & vbCrLf
            End If
            
            If dsAGG_TIMES.Fields("MON_PERSONAL").Value <> 0 Then
                txtPer.Text = txtPer.Text & "Mon " & dsAGG_TIMES.Fields("THE_MON").Value & " Timesheet -- " & dsAGG_TIMES.Fields("MON_PERSONAL").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("TUE_PERSONAL").Value <> 0 Then
                txtPer.Text = txtPer.Text & "Tue " & dsAGG_TIMES.Fields("THE_TUE").Value & " Timesheet -- " & dsAGG_TIMES.Fields("TUE_PERSONAL").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("WED_PERSONAL").Value <> 0 Then
                txtPer.Text = txtPer.Text & "Wed " & dsAGG_TIMES.Fields("THE_WED").Value & " Timesheet -- " & dsAGG_TIMES.Fields("WED_PERSONAL").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("THU_PERSONAL").Value <> 0 Then
                txtPer.Text = txtPer.Text & "Thu " & dsAGG_TIMES.Fields("THE_THU").Value & " Timesheet -- " & dsAGG_TIMES.Fields("THU_PERSONAL").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("FRI_PERSONAL").Value <> 0 Then
                txtPer.Text = txtPer.Text & "Fri " & dsAGG_TIMES.Fields("THE_FRI").Value & " Timesheet -- " & dsAGG_TIMES.Fields("FRI_PERSONAL").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("SAT_PERSONAL").Value <> 0 Then
                txtPer.Text = txtPer.Text & "Sat " & dsAGG_TIMES.Fields("THE_SAT").Value & " Timesheet -- " & dsAGG_TIMES.Fields("SAT_PERSONAL").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("SUN_PERSONAL").Value <> 0 Then
                txtPer.Text = txtPer.Text & "Sun " & dsAGG_TIMES.Fields("THE_SUN").Value & " Timesheet -- " & dsAGG_TIMES.Fields("SUN_PERSONAL").Value & AsteriskString & vbCrLf
            End If
            
            If dsAGG_TIMES.Fields("MON_SICK").Value <> 0 Then
                txtSick.Text = txtSick.Text & "Mon " & dsAGG_TIMES.Fields("THE_MON").Value & " Timesheet -- " & dsAGG_TIMES.Fields("MON_SICK").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("TUE_SICK").Value <> 0 Then
                txtSick.Text = txtSick.Text & "Tue " & dsAGG_TIMES.Fields("THE_TUE").Value & " Timesheet -- " & dsAGG_TIMES.Fields("TUE_SICK").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("WED_SICK").Value <> 0 Then
                txtSick.Text = txtSick.Text & "Wed " & dsAGG_TIMES.Fields("THE_WED").Value & " Timesheet -- " & dsAGG_TIMES.Fields("WED_SICK").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("THU_SICK").Value <> 0 Then
                txtSick.Text = txtSick.Text & "Thu " & dsAGG_TIMES.Fields("THE_THU").Value & " Timesheet -- " & dsAGG_TIMES.Fields("THU_SICK").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("FRI_SICK").Value <> 0 Then
                txtSick.Text = txtSick.Text & "Fri " & dsAGG_TIMES.Fields("THE_FRI").Value & " Timesheet -- " & dsAGG_TIMES.Fields("FRI_SICK").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("SAT_SICK").Value <> 0 Then
                txtSick.Text = txtSick.Text & "Sat " & dsAGG_TIMES.Fields("THE_SAT").Value & " Timesheet -- " & dsAGG_TIMES.Fields("SAT_SICK").Value & AsteriskString & vbCrLf
            End If
            If dsAGG_TIMES.Fields("SUN_SICK").Value <> 0 Then
                txtSick.Text = txtSick.Text & "Sun " & dsAGG_TIMES.Fields("THE_SUN").Value & " Timesheet -- " & dsAGG_TIMES.Fields("SUN_SICK").Value & AsteriskString & vbCrLf
            End If
            
            dsAGG_TIMES.Movenext
            
        Wend
        
        ' total used
        strSql = "SELECT NVL(SUM(WEEK_TOTAL_VACATION), 0) THE_VAC, NVL(SUM(WEEK_TOTAL_SICK), 0) THE_SICK, NVL(SUM(WEEK_TOTAL_PERSONAL), 0) THE_PER " _
                & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND FRI_DATE >= '01-JAN-" & strYear & "' " _
                & "AND STATUS = 'APPROVED' AND CONDITIONAL_SUBMISSION = 'N'"
        Set dsAGG_TIMES = OraDatabase.DbCreateDynaset(strSql, 0&)
        
        txtVac.Text = txtVac.Text & "Total Used: " & dsAGG_TIMES.Fields("THE_VAC").Value & vbCrLf
        txtPer.Text = txtPer.Text & "Total Used: " & dsAGG_TIMES.Fields("THE_PER").Value & vbCrLf
        txtSick.Text = txtSick.Text & "Total Used: " & dsAGG_TIMES.Fields("THE_SICK").Value & vbCrLf
        
        ' freshly accrued
        strSql = "SELECT SUM(WEEKLY_ACCRUAL_SICK) THE_SICK, SUM(WEEKLY_ACCRUAL_VACATION) THE_VAC FROM TIME_SUBMISSION " _
            & "WHERE EMPLOYEE_ID = '" & strEmpID & "' AND FRI_DATE >= '01-JAN-" & strYear & "' " _
            & "AND STATUS = 'APPROVED' AND CONDITIONAL_SUBMISSION = 'N'"
        Set dsAGG_TIMES = OraDatabase.DbCreateDynaset(strSql, 0&)
            
        txtVac.Text = txtVac.Text & "Total Accrued YTD: " & dsAGG_TIMES.Fields("THE_VAC").Value & vbCrLf
        txtSick.Text = txtSick.Text & "Total Accrued YTD: " & dsAGG_TIMES.Fields("THE_SICK").Value & vbCrLf
        
        ' grand totals
        strSql = "SELECT VACATION_YTD_REMAIN, SICK_YTD_REMAIN, PERSONAL_YTD_REMAIN FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmpID & "'"
        Set dsAGG_TIMES = OraDatabase.DbCreateDynaset(strSql, 0&)
        
        ' get totals for use in parenthese after the last line
        strSql = "SELECT NVL(SUM(WEEK_TOTAL_VACATION), 0) THE_VAC, NVL(SUM(WEEK_TOTAL_SICK), 0) THE_SICK, NVL(SUM(WEEK_TOTAL_PERSONAL), 0) THE_PER " _
                & "FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmpID & "' AND FRI_DATE >= '01-JAN-" & strYear & "' " _
                & "AND STATUS = 'APPROVED' AND CONDITIONAL_SUBMISSION = 'N' AND YTD_WEEK_END_SICK_BAL IS NULL"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        
        If dsSHORT_TERM_DATA.Fields("THE_VAC").Value = 0 Then
            LastLineAditionVac = ""
        Else
            LastLineAditionVac = " (* Less " & dsSHORT_TERM_DATA.Fields("THE_VAC").Value & " pending ADP upload)"
        End If
 
        If dsSHORT_TERM_DATA.Fields("THE_SICK").Value = 0 Then
            LastLineAditionSick = ""
        Else
            LastLineAditionSick = " (* Less " & dsSHORT_TERM_DATA.Fields("THE_SICK").Value & " pending ADP upload)"
        End If
 
        If dsSHORT_TERM_DATA.Fields("THE_PER").Value = 0 Then
            LastLineAditionPers = ""
        Else
            LastLineAditionPers = " (* Less " & dsSHORT_TERM_DATA.Fields("THE_PER").Value & " pending ADP upload)"
        End If
 
        
        txtVac.Text = txtVac.Text & "YTD Remaining: " & Round(dsAGG_TIMES.Fields("VACATION_YTD_REMAIN").Value, 2) & LastLineAditionVac & vbCrLf
        txtPer.Text = txtPer.Text & "YTD Remaining: " & Round(dsAGG_TIMES.Fields("PERSONAL_YTD_REMAIN").Value, 2) & LastLineAditionPers & vbCrLf
        txtSick.Text = txtSick.Text & "YTD Remaining: " & Round(dsAGG_TIMES.Fields("SICK_YTD_REMAIN").Value, 2) & LastLineAditionSick & vbCrLf
        
    
    End If
        
        
End Sub

