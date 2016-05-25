VERSION 5.00
Begin VB.Form frmUnPaidList 
   Caption         =   "List of Unpaid Employees"
   ClientHeight    =   3465
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   6660
   LinkTopic       =   "Form1"
   ScaleHeight     =   3465
   ScaleWidth      =   6660
   StartUpPosition =   3  'Windows Default
   Begin VB.TextBox txtEmpList 
      Height          =   2175
      Left            =   360
      MultiLine       =   -1  'True
      ScrollBars      =   2  'Vertical
      TabIndex        =   1
      Top             =   1080
      Width           =   5775
   End
   Begin VB.Label Label1 
      Alignment       =   2  'Center
      Caption         =   "Hired but not Paid Employees (last 14 days)"
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
      Left            =   1560
      TabIndex        =   0
      Top             =   120
      Width           =   3255
   End
End
Attribute VB_Name = "frmUnPaidList"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim theSQL As String
Dim dsHIREUNPAID As Object

Private Sub Form_Load()
  'Place Form top-left
  Me.Top = 0
  Me.Left = 0

' we are using may 26, 2008 as the "inaugural" date to begin this new process.
' only show employees more than a day "overboard" (dont want to make the SUPVs irate with obtrusive reminders),
' but also only show less than 2 weeks overboard (after that point, no SUPV can enter their time anyway)
    theSQL = "SELECT HIRE_DATE, LOCATION_ID, EMP.EMPLOYEE_ID, EMPLOYEE_NAME FROM DAILY_HIRE_LIST DHL, EMPLOYEE EMP" & _
            " WHERE EMPLOYEE_TYPE_ID IN ('CASC', 'CASB', 'REGR')" & _
            " AND DHL.EMPLOYEE_ID = EMP.EMPLOYEE_ID" & _
            " AND (SELECT COUNT(*) FROM HOURLY_DETAIL HD WHERE HD.EMPLOYEE_ID = DHL.EMPLOYEE_ID AND DHL.HIRE_DATE = HD.HIRE_DATE) = 0" & _
            " AND DHL.HIRE_DATE >= '26-may-2008'" & _
            " AND DHL.HIRE_DATE <= SYSDATE - 2" & _
            " AND DHL.HIRE_DATE >= SYSDATE - 15" & _
            " ORDER BY LOCATION_ID, HIRE_DATE, EMP.EMPLOYEE_ID"
    Set dsHIREUNPAID = OraDatabase.DBCreateDynaset(theSQL, 0&)

    While dsHIREUNPAID.EOF = False
        txtEmpList.Text = txtEmpList.Text & dsHIREUNPAID.Fields("LOCATION_ID").Value & " - " & dsHIREUNPAID.Fields("HIRE_DATE").Value & " - " & dsHIREUNPAID.Fields("EMPLOYEE_ID").Value & " " & dsHIREUNPAID.Fields("EMPLOYEE_NAME").Value & vbCrLf
        dsHIREUNPAID.MoveNext
    Wend

End Sub
