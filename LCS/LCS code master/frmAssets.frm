VERSION 5.00
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.0#0"; "mscomctl.ocx"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmAssets 
   Caption         =   "LCS"
   ClientHeight    =   6045
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   6255
   LinkTopic       =   "Form1"
   ScaleHeight     =   6045
   ScaleWidth      =   6255
   StartUpPosition =   2  'CenterScreen
   Begin VB.CommandButton cmdAdd 
      Caption         =   "&Add"
      Height          =   375
      Left            =   840
      TabIndex        =   6
      Top             =   5040
      Width           =   735
   End
   Begin VB.CommandButton cmdEdit 
      Caption         =   "&Save"
      Height          =   375
      Left            =   1800
      TabIndex        =   5
      Top             =   5040
      Width           =   735
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&Delete"
      Height          =   375
      Left            =   2760
      TabIndex        =   4
      Top             =   5040
      Width           =   735
   End
   Begin VB.CommandButton cmdRefresh 
      Caption         =   "&Refresh"
      Height          =   375
      Left            =   3720
      TabIndex        =   3
      Top             =   5040
      Width           =   735
   End
   Begin VB.CommandButton cmdExit 
      Cancel          =   -1  'True
      Caption         =   "&Exit"
      Height          =   375
      Left            =   4680
      TabIndex        =   2
      Top             =   5040
      Width           =   735
   End
   Begin SSDataWidgets_B.SSDBGrid DataGrid 
      Height          =   3735
      Left            =   360
      TabIndex        =   0
      Top             =   1080
      Width           =   5535
      ScrollBars      =   2
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   2
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      RowHeight       =   423
      Columns.Count   =   2
      Columns(0).Width=   3254
      Columns(0).Caption=   "Equipment Code"
      Columns(0).Name =   "Equipment Code"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   5345
      Columns(1).Caption=   "Equipment Description"
      Columns(1).Name =   "Equipment Description"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      _ExtentX        =   9763
      _ExtentY        =   6588
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
      TabIndex        =   1
      Top             =   5670
      Width           =   6255
      _ExtentX        =   11033
      _ExtentY        =   661
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
         EndProperty
      EndProperty
   End
   Begin VB.Label lblTitle 
      Alignment       =   2  'Center
      Caption         =   "Add / Edit Asset Codes"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   600
      TabIndex        =   7
      Top             =   240
      Width           =   4935
   End
End
Attribute VB_Name = "frmAssets"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iAsset As Integer
Dim OraDatabase As Object
Dim OraSession As Object
Dim SqlStmt As String
Private Sub cmdAdd_Click()

'Adds the new ASSET_CODE(s) to the ASSET table.

Dim dsASSET As Object
Dim iRec As Integer

    OraSession.BeginTrans
    DataGrid.MoveFirst
    
     For iRec = 0 To DataGrid.Rows - 1
    
        SqlStmt = " Select * from ASSET where ASSET_ID = '" & Trim$(DataGrid.Columns(0).Value) & "'"
                  
        Set dsASSET = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
        If dsASSET.RecordCount = 0 Then
                dsASSET.AddNew

                dsASSET.Fields("ASSET_ID").Value = DataGrid.Columns(0).Value
                dsASSET.Fields("ASSET_DESCRIPTION").Value = DataGrid.Columns(1).Value

        End If
            
                dsASSET.Update
    
        DataGrid.MoveNext
     Next iRec
    
            If OraDatabase.LastServerErr <> 0 Then
            
                'Rollback transaction
                MsgBox "Error occured while saving to Asset table. Changes are not saved.", vbExclamation, "Save"
                OraSession.ROLLBACK
                Exit Sub
            Else
            
                'Commit transaction
                OraSession.CommitTrans
                MsgBox "Added New Asset Code(s) Successfully.", vbExclamation, "Save"

            End If
    
DataGrid.RemoveAll

End Sub
Private Sub cmdDelete_Click()

If Trim(iAsset) <> "" Then

Dim dsASSET As Object
Dim iRec As Integer

    OraSession.BeginTrans
    
        SqlStmt = " Select * from ASSET where ASSET_ID = '" & iAsset & "'"
                      
        Set dsASSET = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
            If dsASSET.RecordCount = 0 Then
            Exit Sub
            End If
            
        SqlStmt = " delete from ASSET where ASSET_ID = '" & iAsset & "'"
        OraDatabase.ExecuteSQL (SqlStmt)

            If OraDatabase.LastServerErr <> 0 Then
            
                'Rollback transaction
                MsgBox "Error occured while saving to Asset table. Changes are not saved.", vbExclamation, "Save"
                OraSession.ROLLBACK
            Exit Sub
            
            Else
            
                'Commit transaction
                OraSession.CommitTrans
                MsgBox "Deleted Asset Code Successfully.", vbExclamation, "Delete"

            End If
    
DataGrid.RemoveAll

End If
End Sub
Private Sub cmdEdit_Click()

'Adds or Updates the data in the ASSET table.

Dim dsASSET As Object
Dim iRec As Integer

    OraSession.BeginTrans
    DataGrid.MoveFirst
    
     For iRec = 0 To DataGrid.Rows - 1
    
        SqlStmt = " Select * from ASSET where ASSET_ID = '" & Trim$(DataGrid.Columns(0).Value) & "'"
                  
        Set dsASSET = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
        If dsASSET.RecordCount > 0 Then
                dsASSET.Edit

                dsASSET.Fields("ASSET_ID").Value = DataGrid.Columns(0).Value
                dsASSET.Fields("ASSET_DESCRIPTION").Value = DataGrid.Columns(1).Value

        End If
                
                dsASSET.Update
    
        DataGrid.MoveNext
    Next iRec
    
            If OraDatabase.LastServerErr <> 0 Then
            
                'Rollback transaction
                MsgBox "Error occured while saving to Asset Table. Changes are not saved.", vbExclamation, "Save"
                OraSession.ROLLBACK
                Exit Sub
            Else
            
                'Commit transaction
                OraSession.CommitTrans
                MsgBox "Modified Asset Code(s) Successfully.", vbExclamation, "Save"
            End If
    
DataGrid.RemoveAll

End Sub
Private Sub cmdExit_Click()

Unload Me

End Sub
Private Sub cmdRefresh_Click()

DataGrid.RemoveAll
Call Data

End Sub
Private Sub DataGrid_click()

iAsset = Val(DataGrid.Columns(0).Value)

End Sub
Private Sub Form_Load()

'Create Connection to Datbase
      
      Me.Show
      DoEvents
      
      StatusBar1.SimpleText = "Logging onto DataBase..."

      Set OraSession = CreateObject("OracleInProcServer.XOraSession")
      
      'Table doesn't seem to exist in dev or Live?
      Set OraDatabase = OraSession.OpenDatabase("LCS", "LABOR/LABOR", 0&)
      'Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)  '4/30/2007 HD 2759 Rudy: one init, orig above Untested
          
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

Dim dsASSET As Object
Dim iRec As Integer

        SqlStmt = " Select * from ASSET order by ASSET_ID"
        
        Set dsASSET = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        
        If OraDatabase.LastServerErr = 0 And dsASSET.RecordCount > 0 Then
        
            With dsASSET
                For iRec = 1 To .RecordCount
                    DoEvents
                    DataGrid.AddItem .Fields("ASSET_ID").Value + Chr(9) + _
                                     .Fields("ASSET_DESCRIPTION").Value
                                         
                    DataGrid.Refresh
                    .MoveNext
                Next iRec
            End With
            
            StatusBar1.SimpleText = dsASSET.RecordCount & "  Record(s)"
            
        End If

End Sub

