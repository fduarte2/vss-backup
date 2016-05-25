VERSION 5.00
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.0#0"; "mscomctl.ocx"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmServGroup 
   Caption         =   "LCS"
   ClientHeight    =   6930
   ClientLeft      =   4665
   ClientTop       =   3510
   ClientWidth     =   6450
   LinkTopic       =   "Form1"
   ScaleHeight     =   6930
   ScaleWidth      =   6450
   StartUpPosition =   2  'CenterScreen
   Begin VB.CommandButton cmdAdd 
      Caption         =   "&Add"
      Height          =   375
      Left            =   720
      TabIndex        =   6
      Top             =   5880
      Width           =   975
   End
   Begin VB.CommandButton cmdEdit 
      Caption         =   "&Save"
      Height          =   375
      Left            =   1920
      TabIndex        =   5
      Top             =   5880
      Width           =   735
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&Delete"
      Height          =   375
      Left            =   2880
      TabIndex        =   4
      Top             =   5880
      Width           =   735
   End
   Begin VB.CommandButton cmdExit 
      Cancel          =   -1  'True
      Caption         =   "&Exit"
      Height          =   375
      Left            =   4680
      TabIndex        =   3
      Top             =   5880
      Width           =   735
   End
   Begin VB.CommandButton cmdRefresh 
      Caption         =   "&Refresh"
      Height          =   375
      Left            =   3840
      TabIndex        =   2
      Top             =   5880
      Width           =   735
   End
   Begin SSDataWidgets_B.SSDBGrid DataGrid 
      Height          =   4815
      Left            =   360
      TabIndex        =   1
      Top             =   840
      Width           =   4455
      ScrollBars      =   2
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   2
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      RowHeight       =   423
      CaptionAlignment=   0
      Columns.Count   =   2
      Columns(0).Width=   3200
      Columns(0).Caption=   "Group Id"
      Columns(0).Name =   "Group Id"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   3200
      Columns(1).Caption=   "Group Name"
      Columns(1).Name =   "Group Name"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      _ExtentX        =   7858
      _ExtentY        =   8493
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
   Begin MSComctlLib.StatusBar StatusBar1 
      Align           =   2  'Align Bottom
      Height          =   375
      Left            =   0
      TabIndex        =   7
      Top             =   6555
      Width           =   6450
      _ExtentX        =   11377
      _ExtentY        =   661
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
         EndProperty
      EndProperty
   End
   Begin VB.Label Label1 
      Alignment       =   2  'Center
      Caption         =   "Add / Edit Service Group"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   480
      TabIndex        =   0
      Top             =   360
      Width           =   4215
   End
End
Attribute VB_Name = "frmServGroup"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iServiceGroup As Integer
Dim OraDatabase As Object
Dim OraSession As Object
Dim SqlStmt As String
Private Sub cmdAdd_Click()

'Adds the new Servie Group Id to the SERVICE_GROUP table.

Dim dsServiceGroup As Object
Dim iRec As Integer
Dim added As String

added = "False"

    OraSession.BeginTrans
    DataGrid.MoveFirst
    
     For iRec = 0 To DataGrid.Rows - 1
    
        SqlStmt = " Select * from SERVICE_GROUP where SERVICE_GROUP_ID = '" & Trim$(DataGrid.Columns(0).Value) & "'"
                  
        Set dsServiceGroup = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
        If dsServiceGroup.RecordCount = 0 Then
                dsServiceGroup.AddNew

                dsServiceGroup.Fields("SERVICE_GROUP_ID").Value = DataGrid.Columns(0).Value
                dsServiceGroup.Fields("SERVICE_GROUP_NAME").Value = DataGrid.Columns(1).Value
                
                added = "True"
        End If
            
                dsServiceGroup.Update
    
        DataGrid.MoveNext
     Next iRec
    
            If OraDatabase.LastServerErr <> 0 Then
            
                'Rollback transaction
                MsgBox "Error occured while saving to SERVICE_GROUP table. Changes are not saved.", vbExclamation, "Save"
                OraSession.ROLLBACK
                Exit Sub
            Else
            
                'Commit transaction
                OraSession.CommitTrans
                If added = "True" Then
                 MsgBox "Added New Service Group Id Successfully.", vbExclamation, "Save"
                End If

            End If
    
DataGrid.RemoveAll
Call Data

End Sub
Private Sub cmdDelete_Click()

Dim dsServiceGroup As Object
Dim iRec As Integer

If Trim(iServiceGroup) <> "" Then

    
        SqlStmt = " Select * from SERVICE_GROUP where SERVICE_GROUP_ID = '" & iServiceGroup & "'"
                      
        Set dsServiceGroup = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
            If dsServiceGroup.RecordCount = 0 Then
            Exit Sub
            End If
            
        OraSession.BeginTrans
    
        SqlStmt = " delete from SERVICE_GROUP where SERVICE_GROUP_ID = '" & iServiceGroup & "'"
        OraDatabase.ExecuteSQL (SqlStmt)

            If OraDatabase.LastServerErr <> 0 Then
            
                'Rollback transaction
                MsgBox "Error occured while saving to SERVICE_GROUP table. Changes are not saved.", vbExclamation, "Save"
                OraSession.ROLLBACK
            Exit Sub
            
            Else
            
                'Commit transaction
                OraSession.CommitTrans
                MsgBox "Deleted Service Group Id Successfully.", vbExclamation, "Delete"

            End If
    
DataGrid.RemoveAll
Call Data
End If
End Sub
Private Sub cmdEdit_Click()

'Adds or Updates the data in the SERVICE_GROUP table.

Dim dsServiceGroup As Object
Dim iRec As Integer

    OraSession.BeginTrans
    DataGrid.MoveFirst
    
     For iRec = 0 To DataGrid.Rows - 1
    
        SqlStmt = " Select * from SERVICE_GROUP where SERVICE_GROUP_ID = '" & Trim$(DataGrid.Columns(0).Value) & "'"
                  
        Set dsServiceGroup = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
        If dsServiceGroup.RecordCount > 0 Then
                dsServiceGroup.Edit

                dsServiceGroup.Fields("SERVICE_GROUP_ID").Value = DataGrid.Columns(0).Value
                dsServiceGroup.Fields("SERVICE_GROUP_NAME").Value = DataGrid.Columns(1).Value

        End If
                
                dsServiceGroup.Update
    
        DataGrid.MoveNext
    Next iRec
    
            If OraDatabase.LastServerErr <> 0 Then
            
                'Rollback transaction
                MsgBox "Error occured while saving to SERVICE_GROUP Table. Changes are not saved.", vbExclamation, "Save"
                OraSession.ROLLBACK
                Exit Sub
            Else
            
                'Commit transaction
                OraSession.CommitTrans
                MsgBox "Modified Service Group Id Successfully.", vbExclamation, "Save"
            End If
    
DataGrid.RemoveAll
Call Data

End Sub
Private Sub cmdExit_Click()

  Unload Me
  frmMaster.ZOrder 1
End Sub
Private Sub cmdRefresh_Click()

DataGrid.RemoveAll
Call Data

End Sub
Private Sub DataGrid_click()

iServiceGroup = Val(DataGrid.Columns(0).Value)

End Sub
Private Sub Form_Load()

'Create Connection to Datbase
      
      Me.Show
      DoEvents
      
      StatusBar1.SimpleText = "Logging onto DataBase..."

      Set OraSession = CreateObject("OracleInProcServer.XOraSession")
      'Set OraDatabase = OraSession.Open Database("LCS", "LABOR/LABOR", 0&)
      Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)  '4/30/2007 HD 2759 Rudy: one init, orig above  Tested
      
      If OraDatabase.LastServerErr <> 0 Then
         MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
         StatusBar1.SimpleText = "Error Logging onto DataBase..."
         Exit Sub
      End If
    
      'StartDate = Format(Now, "mm/dd/yyyy")
      StatusBar1.SimpleText = "LogOn Successful!"
      
Call Data

End Sub
Private Sub Data()

Dim dsServiceGroup As Object
Dim iRec As Integer

        SqlStmt = " Select * from SERVICE_GROUP order by SERVICE_GROUP_ID"
        
        Set dsServiceGroup = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        
        If OraDatabase.LastServerErr = 0 And dsServiceGroup.RecordCount > 0 Then
        
            With dsServiceGroup
                For iRec = 1 To .RecordCount
                    DoEvents
                    DataGrid.AddItem .Fields("SERVICE_GROUP_ID").Value + Chr(9) + _
                                     .Fields("SERVICE_GROUP_NAME").Value
                                         
                    DataGrid.Refresh
                    .MoveNext
                Next iRec
            End With
            
            StatusBar1.SimpleText = dsServiceGroup.RecordCount & "  Record(s)"
            
        End If

End Sub


