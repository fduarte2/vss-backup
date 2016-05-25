VERSION 5.00
Begin VB.Form frmDayOffSumSelect 
   BackColor       =   &H80000016&
   Caption         =   "TimeOff Summary Selection"
   ClientHeight    =   2610
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   5130
   LinkTopic       =   "Form1"
   ScaleHeight     =   2610
   ScaleWidth      =   5130
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton Command1 
      Caption         =   "Display History"
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
      Left            =   1320
      TabIndex        =   2
      Top             =   1800
      Width           =   2175
   End
   Begin VB.ComboBox LBEmpList 
      Height          =   315
      ItemData        =   "frmDayOffSumSelect.frx":0000
      Left            =   1080
      List            =   "frmDayOffSumSelect.frx":0002
      TabIndex        =   1
      Text            =   "Select Employee"
      Top             =   960
      Width           =   2895
   End
   Begin VB.Label Label1 
      Alignment       =   2  'Center
      BackColor       =   &H80000016&
      Caption         =   "Select Employee:"
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
      Left            =   960
      TabIndex        =   0
      Top             =   240
      Width           =   3015
   End
End
Attribute VB_Name = "frmDayOffSumSelect"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Public strEmpID As String
Public strYear As String
Dim dsDATA As Object

Private Sub form_load()

' Adam Walter, March 28, 2008.  Modifying the select statement to include special permissions for the Director of HR,
' Granting him/her permission to view everyone's time off summary's.
strSql = "SELECT A.DEPARTMENT_ID THE_DEP, NVL(B.SUPERVISOR_ID, 'none') THE_SUP FROM AT_EMPLOYEE A, AT_EMPLOYEE B WHERE A.EMPLOYEE_ID = '" _
        & strEmpID & "' AND A.SUPERVISOR_ID = B.EMPLOYEE_ID(+)"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
If (dsSHORT_TERM_DATA.Fields("THE_DEP").Value = "HR" And dsSHORT_TERM_DATA.Fields("THE_SUP").Value = "N/A") Then
    ' do this for HR director
    strSql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME FROM AT_EMPLOYEE WHERE EMPLOYMENT_STATUS = 'ACTIVE' ORDER BY EMPLOYEE_ID"
    Set dsDATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    While dsDATA.EOF = False
    LBEmpList.AddItem dsDATA.Fields("EMPLOYEE_ID").Value & " - " & dsDATA.Fields("FIRST_NAME").Value & " " & dsDATA.Fields("LAST_NAME").Value
    dsDATA.Movenext
    Wend
Else

    ' do this for everyone else
    strSql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME FROM AT_EMPLOYEE WHERE EMPLOYEE_ID = '" & strEmpID _
            & "' ORDER BY EMPLOYEE_ID"
    
    
    Set dsDATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    LBEmpList.AddItem dsDATA.Fields("EMPLOYEE_ID").Value & " - " & dsDATA.Fields("FIRST_NAME").Value & " " & dsDATA.Fields("LAST_NAME").Value
    
    strSql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME FROM AT_EMPLOYEE WHERE SUPERVISOR_ID = '" & strEmpID _
            & "' AND EMPLOYMENT_STATUS = 'ACTIVE' ORDER BY EMPLOYEE_ID"
    Set dsDATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    While dsDATA.EOF = False
        LBEmpList.AddItem dsDATA.Fields("EMPLOYEE_ID").Value & " - " & dsDATA.Fields("FIRST_NAME").Value & " " & dsDATA.Fields("LAST_NAME").Value
        dsDATA.Movenext
    Wend
End If

End Sub


Private Sub Command1_Click()

    Dim TimeOff As New frmDayOffSum
    TimeOff.strEmpID = Left$(LBEmpList.Text, 7)
    TimeOff.strYear = strYear
    TimeOff.Show 1
    
    Unload Me

End Sub

Private Sub LBEmpList_click()

If (LBEmpList.Text <> "Select Employee") Then
    Command1.Enabled = True
Else
    Command1.Enabled = False
End If

End Sub


