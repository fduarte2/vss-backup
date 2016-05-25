VERSION 5.00
Begin VB.Form frmDtSelect 
   BackColor       =   &H80000016&
   Caption         =   "Date Selection"
   ClientHeight    =   2625
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   4680
   LinkTopic       =   "Form1"
   ScaleHeight     =   2625
   ScaleWidth      =   4680
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton Command1 
      Caption         =   "Retrieve Timesheet"
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
      Left            =   1200
      TabIndex        =   3
      Top             =   1800
      Width           =   2175
   End
   Begin VB.ComboBox LBDtList 
      Height          =   315
      Left            =   840
      TabIndex        =   2
      Text            =   "LBDtList"
      Top             =   1200
      Width           =   2895
   End
   Begin VB.Label lblEmpName 
      Alignment       =   2  'Center
      BackColor       =   &H80000016&
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
      Left            =   840
      TabIndex        =   1
      Top             =   600
      Width           =   3015
   End
   Begin VB.Label Label1 
      Alignment       =   2  'Center
      BackColor       =   &H80000016&
      Caption         =   "Select Week for:"
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
      Left            =   720
      TabIndex        =   0
      Top             =   240
      Width           =   3135
   End
End
Attribute VB_Name = "frmDtSelect"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Public strEmployeeID As String
Dim strWeek As String


Private Sub Command1_Click()

If LBDtList.Text = "No Timesheets" Then
    MsgBox "No Timesheets" ' should never happen, as by virtue of using timesheet program, employee has at least 1 timesheet
Else
    Dim Printout As New frmPrint
    Printout.strEmployeeID = strEmployeeID
    Printout.strWeek = Format(LBDtList.Text, "dd-mmm-yyyy")
    Printout.Show 1
    
    Unload Me
End If

End Sub

Private Sub form_load()

strSql = "SELECT FIRST_NAME || ' ' || LAST_NAME THE_NAME FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmployeeID & "'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

lblEmpName.Caption = dsSHORT_TERM_DATA.fields("THE_NAME").Value

strSql = "SELECT TO_CHAR(WEEK_START_MONDAY, 'MM/DD/YYYY') THE_DATE FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" & strEmployeeID & "' " _
        & "ORDER BY WEEK_START_MONDAY DESC"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

LBDtList.Clear

If dsSHORT_TERM_DATA.EOF = True Then
    LBDtList.AddItem "No Timesheets" ' should never happen, as by virtue of using timesheet program, employee has at least 1 timesheet
Else
    While dsSHORT_TERM_DATA.EOF = False
        LBDtList.AddItem dsSHORT_TERM_DATA.fields("THE_DATE").Value
        dsSHORT_TERM_DATA.Movenext
    Wend
End If

LBDtList.ListIndex = 0

End Sub
