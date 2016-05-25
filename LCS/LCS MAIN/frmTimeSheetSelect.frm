VERSION 5.00
Begin VB.Form frmTimeSheetSelect 
   Caption         =   "Select Name"
   ClientHeight    =   3090
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   4680
   LinkTopic       =   "Form1"
   ScaleHeight     =   3090
   ScaleWidth      =   4680
   StartUpPosition =   3  'Windows Default
   Begin VB.ComboBox cmbOrderBy 
      Height          =   315
      ItemData        =   "frmTimeSheetSelect.frx":0000
      Left            =   480
      List            =   "frmTimeSheetSelect.frx":0002
      TabIndex        =   3
      Top             =   1560
      Width           =   3615
   End
   Begin VB.CommandButton btnViewSheet 
      Caption         =   "View TimeSheet"
      Height          =   255
      Left            =   1080
      TabIndex        =   2
      Top             =   2160
      Width           =   2415
   End
   Begin VB.ComboBox cmbNameChooser 
      Height          =   315
      Left            =   480
      TabIndex        =   1
      Top             =   720
      Width           =   3615
   End
   Begin VB.Label Label2 
      Alignment       =   2  'Center
      Caption         =   "Sort By:"
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
      Left            =   1320
      TabIndex        =   4
      Top             =   1200
      Width           =   1935
   End
   Begin VB.Label Label1 
      Alignment       =   2  'Center
      Caption         =   "Please Choose Super's Time to Display:"
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
      Left            =   360
      TabIndex        =   0
      Top             =   120
      Width           =   3855
   End
End
Attribute VB_Name = "frmTimeSheetSelect"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim sql As String

Private Sub Form_Load()
    cmbOrderBy.AddItem "EMPLOYEE"
    cmbOrderBy.AddItem "SUPERVISOR"

    sql = "SELECT * FROM LCS_USER WHERE USER_ID = '" & UserID & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DBCreateDynaset(sql, 0&)
    
    cmbOrderBy.Enabled = True
    
    If dsSHORT_TERM_DATA.Fields("GROUP_ID").Value = 4 Then
'        cmbOrderBy.Enabled = False
        cmbNameChooser.AddItem dsSHORT_TERM_DATA.Fields("USER_ID").Value & " - " & dsSHORT_TERM_DATA.Fields("USER_NAME").Value
    Else
        cmbNameChooser.AddItem "ALL"
'        cmbOrderBy.Enabled = True
        sql = "SELECT LU.USER_ID, USER_NAME FROM LCS_USER LU, SUPERVISOR_INITIALS SI WHERE STATUS = 'A' AND GROUP_ID IN ('2', '4', '6') AND LU.USER_ID = SI.USER_ID ORDER BY USER_NAME"
        Set dsSHORT_TERM_DATA = OraDatabase.DBCreateDynaset(sql, 0&)

        While dsSHORT_TERM_DATA.EOF = False
            cmbNameChooser.AddItem dsSHORT_TERM_DATA.Fields("USER_ID").Value & " - " & dsSHORT_TERM_DATA.Fields("USER_NAME").Value
            dsSHORT_TERM_DATA.MoveNext
        Wend
    End If
    
    cmbOrderBy.ListIndex = 0
    cmbNameChooser.ListIndex = 0
End Sub

Private Sub btnViewSheet_Click()
    If Left$(cmbNameChooser.Text, 3) = "ALL" Then
        TimeSheetSelection = "ALL"
    Else
        sql = "SELECT INITIALS FROM SUPERVISOR_INITIALS WHERE USER_ID = '" & Left$(cmbNameChooser.Text, 7) & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.DBCreateDynaset(sql, 0&)
        TimeSheetSelection = dsSHORT_TERM_DATA.Fields("INITIALS").Value
    End If
    
    If cmbOrderBy.Enabled = False Then
        TimeSheetOrderBy = "a.EMPLOYEE_ID"
    Else
        If cmbOrderBy.Text = "EMPLOYEE" Then
            TimeSheetOrderBy = "a.EMPLOYEE_ID, SPECIAL_CODE, b.User_ID"
        Else
            TimeSheetOrderBy = "SPECIAL_CODE, b.User_ID, a.EMPLOYEE_ID"
        End If
    End If
    
    Unload Me
End Sub

