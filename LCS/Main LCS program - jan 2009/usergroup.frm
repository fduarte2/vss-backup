VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmUserGroup 
   Caption         =   "UserGroup"
   ClientHeight    =   9090
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   11025
   LinkTopic       =   "Form1"
   ScaleHeight     =   9090
   ScaleWidth      =   11025
   StartUpPosition =   3  'Windows Default
   Begin SSDataWidgets_B.SSDBDropDown dwnGroup 
      Height          =   255
      Left            =   3240
      TabIndex        =   6
      Top             =   4080
      Width           =   1095
      DataFieldList   =   "Column 0"
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      ColumnHeaders   =   0   'False
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   1931
      _ExtentY        =   450
      _StockProps     =   77
      DataFieldToDisplay=   "Column 0"
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "&Exit"
      Height          =   375
      Left            =   6720
      TabIndex        =   5
      Top             =   8400
      Width           =   2055
   End
   Begin VB.CommandButton cmdPWD 
      Caption         =   "&Change Password"
      Height          =   375
      Left            =   4680
      TabIndex        =   4
      Top             =   8400
      Visible         =   0   'False
      Width           =   2055
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
      Height          =   375
      Left            =   2640
      TabIndex        =   3
      Top             =   8400
      Width           =   2055
   End
   Begin SSDataWidgets_B.SSDBGrid dbgGroup 
      Height          =   6135
      Left            =   0
      TabIndex        =   2
      Top             =   1800
      Width           =   11055
      ScrollBars      =   2
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   6
      UseGroups       =   -1  'True
      AllowAddNew     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowColumnMoving=   0
      AllowGroupSwapping=   0   'False
      AllowColumnSwapping=   0
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      SelectTypeRow   =   0
      ForeColorEven   =   0
      BackColorOdd    =   14737632
      RowHeight       =   423
      Groups.Count    =   2
      Groups(0).Width =   12806
      Groups(0).Columns.Count=   3
      Groups(0).Columns(0).Width=   2302
      Groups(0).Columns(0).Caption=   "User ID"
      Groups(0).Columns(0).Name=   "User ID"
      Groups(0).Columns(0).DataField=   "Column 0"
      Groups(0).Columns(0).DataType=   8
      Groups(0).Columns(0).FieldLen=   256
      Groups(0).Columns(0).Locked=   -1  'True
      Groups(0).Columns(1).Width=   5953
      Groups(0).Columns(1).Caption=   "User Name"
      Groups(0).Columns(1).Name=   "User Name"
      Groups(0).Columns(1).DataField=   "Column 1"
      Groups(0).Columns(1).DataType=   8
      Groups(0).Columns(1).FieldLen=   256
      Groups(0).Columns(1).Locked=   -1  'True
      Groups(0).Columns(2).Width=   4551
      Groups(0).Columns(2).Caption=   "User Group"
      Groups(0).Columns(2).Name=   "User Group"
      Groups(0).Columns(2).DataField=   "Column 2"
      Groups(0).Columns(2).DataType=   8
      Groups(0).Columns(2).FieldLen=   256
      Groups(0).Columns(2).Style=   3
      Groups(0).Columns(2).Row.Count=   2
      Groups(0).Columns(2).Col.Count=   2
      Groups(0).Columns(2).Row(0).Col(0)=   "admin"
      Groups(0).Columns(2).Row(1).Col(0)=   "supervisor"
      Groups(1).Width =   5874
      Groups(1).Caption=   "Hiring Restriction"
      Groups(1).Columns.Count=   3
      Groups(1).Columns(0).Width=   2011
      Groups(1).Columns(0).Caption=   "Operation"
      Groups(1).Columns(0).Name=   "Operation"
      Groups(1).Columns(0).Alignment=   2
      Groups(1).Columns(0).DataField=   "Column 3"
      Groups(1).Columns(0).DataType=   8
      Groups(1).Columns(0).FieldLen=   256
      Groups(1).Columns(0).Style=   2
      Groups(1).Columns(1).Width=   2011
      Groups(1).Columns(1).Caption=   "Maintenance"
      Groups(1).Columns(1).Name=   "Maintenance"
      Groups(1).Columns(1).Alignment=   2
      Groups(1).Columns(1).DataField=   "Column 4"
      Groups(1).Columns(1).DataType=   8
      Groups(1).Columns(1).FieldLen=   256
      Groups(1).Columns(1).Style=   2
      Groups(1).Columns(2).Width=   1852
      Groups(1).Columns(2).Caption=   "Guard"
      Groups(1).Columns(2).Name=   "Guard"
      Groups(1).Columns(2).Alignment=   2
      Groups(1).Columns(2).DataField=   "Column 5"
      Groups(1).Columns(2).DataType=   8
      Groups(1).Columns(2).FieldLen=   256
      Groups(1).Columns(2).Style=   2
      _ExtentX        =   19500
      _ExtentY        =   10821
      _StockProps     =   79
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.Label Label1 
      Caption         =   "User Access Control"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   4080
      TabIndex        =   1
      Top             =   960
      Width           =   2775
   End
   Begin VB.Label labTitle 
      Alignment       =   2  'Center
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   735
      Left            =   1200
      TabIndex        =   0
      Top             =   0
      Width           =   8655
   End
   Begin VB.Image Image1 
      BorderStyle     =   1  'Fixed Single
      Height          =   840
      Left            =   0
      Picture         =   "usergroup.frx":0000
      Stretch         =   -1  'True
      Top             =   0
      Width           =   855
   End
   Begin VB.Line Line1 
      X1              =   0
      X2              =   11040
      Y1              =   840
      Y2              =   840
   End
End
Attribute VB_Name = "frmUserGroup"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim sqlStmt As String
Dim accessRS As Object

Private Sub cmdExit_Click()
    Unload frmUserGroup
    Load frmHiring
End Sub

Private Sub cmdSave_Click()
    Dim i As Integer
    
    dwnGroup.ListAutoValidate = True
    dbgGroup.MoveFirst
    
    
    For i = 0 To dbgGroup.Rows - 1
        sqlStmt = " UPDATE LCS_USER SET GROUP_ID = (SELECT GROUP_ID FROM LCS_GROUP " + _
                  " WHERE GROUP_DESCRIPTION = '" + dbgGroup.Columns(2).Value + "' ) " + _
                  " WHERE USER_ID = '" + dbgGroup.Columns(0).Value + "'"
        OraDatabase.ExecuteSQL (sqlStmt)
        
        If dbgGroup.Columns(2).Value = "HIRING SUPERVISOR" Then
            sqlStmt = " DELETE FROM HIRING_ACCESS WHERE USER_ID = '" + dbgGroup.Columns(0).Value + "'"
            OraDatabase.ExecuteSQL (sqlStmt)
            If dbgGroup.Columns(3).Value <> 0 Then
                sqlStmt = " INSERT INTO HIRING_ACCESS(USER_ID, HIRING_GROUP_ID) VALUES " + _
                          " ('" + Trim(dbgGroup.Columns(0).Value) + "', 3) "
                OraDatabase.ExecuteSQL (sqlStmt)
            End If
            
            If dbgGroup.Columns(4).Value <> 0 Then
                sqlStmt = " INSERT INTO HIRING_ACCESS(USER_ID, HIRING_GROUP_ID) VALUES " + _
                          " ('" + Trim(dbgGroup.Columns(0).Value) + "', 1) "
                OraDatabase.ExecuteSQL (sqlStmt)
            End If
            
            If dbgGroup.Columns(5).Value <> 0 Then
                sqlStmt = " INSERT INTO HIRING_ACCESS(USER_ID, HIRING_GROUP_ID) VALUES " + _
                          " ('" + Trim(dbgGroup.Columns(0).Value) + "', 2) "
                OraDatabase.ExecuteSQL (sqlStmt)
            End If
            
        End If
        dbgGroup.MoveNext
    Next
    
    Call display
    dbgGroup.MoveFirst
End Sub

Private Sub dbggroup_AfterColUpdate(ByVal ColIndex As Integer)
    If ColIndex <> 0 And ColIndex <> 1 Then
        If dbgGroup.Columns(2).Value <> "HIRING SUPERVISOR" Then
            dbgGroup.Columns(3).Value = 0
            dbgGroup.Columns(4).Value = 0
            dbgGroup.Columns(5).Value = 0
        End If
    End If
End Sub

Private Sub Form_Load()
    Dim i As Integer
    labTitle.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
    dbgGroup.Columns(2).DropDownHwnd = dwnGroup.hWnd
    dwnGroup.ListAutoValidate = True
    
    Call display
    
End Sub
Private Function display()
    Dim op As String
    Dim mt As String
    Dim gd As String
    
    dbgGroup.RemoveAll
    dbgGroup.MoveFirst
    
    sqlStmt = "SELECT u.USER_ID, u.USER_NAME, u.GROUP_ID, g.GROUP_DESCRIPTION, h1.HIRING_GROUP_ID maintenance, h2.HIRING_GROUP_ID guard, h3.HIRING_GROUP_ID operation "
    sqlStmt = sqlStmt & " FROM (SELECT * FROM LCS_USER WHERE STATUS='A'AND GROUP_ID <> 0) u, "
    sqlStmt = sqlStmt & " (SELECT * FROM HIRING_ACCESS WHERE HIRING_GROUP_ID =1 ) h1, "
    sqlStmt = sqlStmt & " (SELECT * FROM HIRING_ACCESS WHERE HIRING_GROUP_ID =2 ) h2, "
    sqlStmt = sqlStmt & " (SELECT * FROM HIRING_ACCESS WHERE HIRING_GROUP_ID =3 ) h3, "
    sqlStmt = sqlStmt & " LCS_GROUP g "
    sqlStmt = sqlStmt & " WHERE  g.GROUP_ID = u.GROUP_ID AND u.USER_ID =  h1.USER_ID(+) AND u.USER_ID = h2.USER_ID(+) AND  u.USER_ID = h3.USER_ID(+) ORDER BY u.GROUP_ID"
    Set accessRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    Do While Not accessRS.EOF
        op = ""
        mt = ""
        gd = ""
        If accessRS.Fields("group_id").Value = 2 Then
            If accessRS.Fields("operation").Value = 3 Then
                op = "1"
            Else
                op = "0"
            End If
            If accessRS.Fields("maintenance").Value = 1 Then
                mt = "1"
            Else
                mt = "0"
            End If
            If accessRS.Fields("guard").Value = 2 Then
                gd = "1"
            Else
                gd = "0"
            End If
        End If
        
        dbgGroup.AddItem accessRS.Fields("user_id").Value + Chr(9) + _
                         accessRS.Fields("user_name").Value + Chr(9) + _
                         accessRS.Fields("group_description").Value + Chr(9) + _
                         op + Chr(9) + _
                         mt + Chr(9) + _
                         gd + Chr(9)
        accessRS.MoveNext
    Loop

    

    
End Function

Private Sub dwnGroup_InitColumnProps()
    sqlStmt = "SELECT * FROM LCS_GROUP ORDER BY GROUP_ID"
    Set accessRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)

    Do While Not accessRS.EOF
        dwnGroup.AddItem accessRS.Fields("group_description").Value
        accessRS.MoveNext
    Loop
End Sub


