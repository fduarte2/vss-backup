VERSION 5.00
Begin VB.Form frmDtEmpSelect 
   BackColor       =   &H80000016&
   Caption         =   "Timesheet Selection"
   ClientHeight    =   3090
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   4680
   LinkTopic       =   "Form1"
   ScaleHeight     =   3090
   ScaleWidth      =   4680
   StartUpPosition =   3  'Windows Default
   Begin VB.ComboBox LBEmpList 
      Height          =   315
      ItemData        =   "frmDtEmpSelect.frx":0000
      Left            =   720
      List            =   "frmDtEmpSelect.frx":0002
      TabIndex        =   3
      Text            =   "Select Employee"
      Top             =   840
      Width           =   2895
   End
   Begin VB.CommandButton Command1 
      Caption         =   "Retrieve Timesheet"
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
      Height          =   375
      Left            =   1080
      TabIndex        =   2
      Top             =   2280
      Width           =   2175
   End
   Begin VB.ComboBox LBDtList 
      Height          =   315
      Left            =   720
      TabIndex        =   1
      Text            =   "Select Date"
      Top             =   1680
      Width           =   2895
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
      Left            =   600
      TabIndex        =   0
      Top             =   240
      Width           =   3135
   End
End
Attribute VB_Name = "frmDtEmpSelect"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
' this form is analogous to the "frmDtSelect" one, except this one has an employee who is non-constant.
' instead, it gets the "emplist" value from the supervisor (to determine which employees said supervisor can see)
' and populates the dropdown box with said employees.
' to sue this form in any other way (say, for single employees), just make sure "emplist" is formatted in a way that
' will fit an SQL "WHERE - IN" clause
Public strEmpList As String
Dim strWeek As String
Dim dsDATA As Object

Private Sub form_load()

LBEmpList.Clear

strSql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME FROM AT_EMPLOYEE WHERE EMPLOYEE_ID IN " & strEmpList _
        & " ORDER BY EMPLOYEE_ID"
Set dsDATA = OraDatabase.DbCreateDynaset(strSql, 0&)

LBEmpList.AddItem "Select Employee"

While dsDATA.EOF = False
    LBEmpList.AddItem dsDATA.Fields("EMPLOYEE_ID").Value & " - " & dsDATA.Fields("FIRST_NAME").Value & " " & dsDATA.Fields("LAST_NAME").Value
    dsDATA.Movenext
Wend

End Sub

Private Sub LBEmpList_click()

LBDtList.Clear

strSql = "SELECT TO_CHAR(WEEK_START_MONDAY, 'MM/DD/YYYY') THE_DATE FROM TIME_SUBMISSION WHERE EMPLOYEE_ID = '" _
    & Left$(LBEmpList.Text, 7) & "' ORDER BY WEEK_START_MONDAY DESC"
Set dsDATA = OraDatabase.DbCreateDynaset(strSql, 0&)

If dsDATA.EOF = True Then
    LBDtList.AddItem "No Timesheets"
    Command1.Enabled = False
Else
    Command1.Enabled = True
    While dsDATA.EOF = False
        LBDtList.AddItem dsDATA.Fields("THE_DATE").Value
        dsDATA.Movenext
    Wend
    LBDtList.ListIndex = 0
End If

End Sub

Private Sub Command1_Click()

    Dim Printout As New frmPrint
    Printout.strEmployeeID = Left$(LBEmpList.Text, 7)
    Printout.strWeek = Format(LBDtList.Text, "dd-mmm-yyyy")
    Printout.Show 1
    
    Unload Me

End Sub

