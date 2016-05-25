VERSION 5.00
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.0#0"; "mscomctl.ocx"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmSrvcCodes 
   Caption         =   "LCS"
   ClientHeight    =   5790
   ClientLeft      =   4560
   ClientTop       =   3375
   ClientWidth     =   9810
   LinkTopic       =   "Form1"
   ScaleHeight     =   5790
   ScaleWidth      =   9810
   StartUpPosition =   2  'CenterScreen
   Begin VB.CommandButton cmcRefresh 
      Caption         =   "&Refresh"
      Height          =   375
      Left            =   5040
      TabIndex        =   4
      Top             =   4800
      Width           =   735
   End
   Begin SSDataWidgets_B.SSDBGrid DataGrid 
      Height          =   3495
      Left            =   360
      TabIndex        =   7
      Top             =   960
      Width           =   9180
      ScrollBars      =   3
      _Version        =   196616
      DataMode        =   2
      HeadLines       =   2
      Col.Count       =   5
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      RowHeight       =   423
      CaptionAlignment=   0
      Columns.Count   =   5
      Columns(0).Width=   1138
      Columns(0).Caption=   "Service Code"
      Columns(0).Name =   "Service Code"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   7541
      Columns(1).Caption=   "Service Name"
      Columns(1).Name =   "Service Name"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1376
      Columns(2).Caption=   "Service Group Id"
      Columns(2).Name =   "Service Group Id"
      Columns(2).Alignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   2752
      Columns(3).Caption=   "Commodity Required (N - Req)"
      Columns(3).Name =   "Commodity Required (N - Req)"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2540
      Columns(4).Caption=   "Status (N - Allows Labor Ticket)"
      Columns(4).Name =   "Status (N - Allows Labor Ticket)"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      _ExtentX        =   16193
      _ExtentY        =   6165
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
      TabIndex        =   6
      Top             =   5415
      Width           =   9810
      _ExtentX        =   17304
      _ExtentY        =   661
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
         EndProperty
      EndProperty
   End
   Begin VB.CommandButton cmdExit 
      Cancel          =   -1  'True
      Caption         =   "&Exit"
      Height          =   375
      Left            =   6000
      TabIndex        =   5
      Top             =   4800
      Width           =   735
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&Delete"
      Height          =   375
      Left            =   4080
      TabIndex        =   3
      Top             =   4800
      Width           =   735
   End
   Begin VB.CommandButton cmdEdit 
      Caption         =   "&Save"
      Height          =   375
      Left            =   3120
      TabIndex        =   2
      Top             =   4800
      Width           =   735
   End
   Begin VB.CommandButton cmdAdd 
      Caption         =   "&Add"
      Height          =   375
      Left            =   1920
      TabIndex        =   1
      Top             =   4800
      Width           =   975
   End
   Begin VB.Label lblTitle 
      Alignment       =   2  'Center
      Caption         =   "Add / Edit Service Codes"
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
      Left            =   2040
      TabIndex        =   0
      Top             =   240
      Width           =   4455
   End
End
Attribute VB_Name = "frmSrvcCodes"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iService As Integer
Dim OraDatabase As Object
Dim OraSession As Object
Dim SqlStmt As String
Private Sub cmcRefresh_Click()

DataGrid.RemoveAll
Call Data

End Sub
Private Sub cmdAdd_Click()

'Adds the new SERVICE_CODE(s) to the SERVICE table.

Dim dsSERVICE As Object
Dim dsGROUPID As Object
Dim iRec As Integer
Dim added As String

  added = "False"

  OraSession.BeginTrans
  DataGrid.MoveFirst
    
  For iRec = 0 To DataGrid.Rows - 1
  
    SqlStmt = " Select * from SERVICE where SERVICE_CODE = '" & Trim$(DataGrid.Columns(0).Value) & "'"
              
    Set dsSERVICE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
  
    If dsSERVICE.RecordCount = 0 Then
      dsSERVICE.AddNew
      
      dsSERVICE.Fields("SERVICE_CODE").Value = DataGrid.Columns(0).Value
      dsSERVICE.Fields("SERVICE_NAME").Value = DataGrid.Columns(1).Value
      
      '4/30/2007 HD 2759 Rudy:
      If dsSERVICE.Fields.Count > 5 Then        'new field exists
        dsSERVICE.Fields("COMMODITY_REQUIRED").Value = DataGrid.Columns(3).Value
        
        '6/19/2007 Rudy:
        dsSERVICE.Fields("STATUS").Value = DataGrid.Columns(4).Value
      End If
      
      SqlStmt = " Select * from SERVICE_GROUP where SERVICE_GROUP_ID = '" & Trim$(DataGrid.Columns(2).Value) & "'"
                
      Set dsGROUPID = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
      If dsGROUPID.RecordCount <> 0 Then
        dsSERVICE.Fields("SERVICE_GROUP_ID").Value = DataGrid.Columns(2).Value
      Else
        MsgBox "You are trying to map Service Code " & DataGrid.Columns(0).Value & " with an Invalid Service Group Id", vbExclamation, "Save"
        OraSession.ROLLBACK
        DataGrid.RemoveAll
        Call Data
        Exit Sub
      End If
            
      If (Val(Trim$(DataGrid.Columns(0).Value)) >= 6000 And Val(Trim$(DataGrid.Columns(0).Value)) < 7000) Then
         dsSERVICE.Fields("STATUS").Value = "N"
      End If
      added = "True"
    End If
           
    dsSERVICE.Update
  
    DataGrid.MoveNext
  Next iRec
    
  If OraDatabase.LastServerErr <> 0 Then
  
    'Rollback transaction
    MsgBox "Error occured while saving to SERVICE table. Changes are not saved.", vbExclamation, "Save"
    OraSession.ROLLBACK
    Exit Sub
  Else
  
    'Commit transaction
    OraSession.CommitTrans
    If added = "True" Then
      MsgBox "Added New Service Code(s) Successfully.", vbExclamation, "Save"
    End If
  
  End If
    
  DataGrid.RemoveAll
  Call Data
  
End Sub
Private Sub cmdDelete_Click()

  If Trim(iService) <> "" Then
  
    Dim dsSERVICE As Object
    Dim iRec As Integer
  
    OraSession.BeginTrans
    
    If (Val(Trim$(iService)) >= 6000 And Val(Trim$(iService)) < 7000) Then
      SqlStmt = " Select * from SERVICE where SERVICE_CODE = '" & iService & "'" _
                & " and status = 'N'"
    Else
      SqlStmt = " Select * from SERVICE where SERVICE_CODE = '" & iService & "'"
    End If
                        
    Set dsSERVICE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsSERVICE.RecordCount = 0 Then
      Exit Sub
    End If
         
    If (Val(Trim$(iService)) >= 6000 And Val(Trim$(iService)) < 7000) Then
      SqlStmt = " delete from SERVICE where SERVICE_CODE = '" & iService & "'" _
              & " and status = 'N'"
    Else
      SqlStmt = " delete from SERVICE where SERVICE_CODE = '" & iService & "'"
    End If
    
    OraDatabase.ExecuteSQL (SqlStmt)

    If OraDatabase.LastServerErr <> 0 Then
    
      'Rollback transaction
      MsgBox "Error occured while saving to SERVICE table. Changes are not saved.", vbExclamation, "Save"
      OraSession.ROLLBACK
      Exit Sub
    Else
    
      'Commit transaction
      OraSession.CommitTrans
      MsgBox "Deleted Service Code Successfully.", vbExclamation, "Delete"
    
    End If
    
    DataGrid.RemoveAll
    Call Data
  End If
  
End Sub
Private Sub cmdEdit_Click()

'Adds or Updates the data in the SERVICE table.

Dim dsSERVICE As Object
Dim dsGROUPID As Object

Dim iRec As Integer
    
  OraSession.BeginTrans
  DataGrid.MoveFirst
  
  For iRec = 0 To DataGrid.Rows - 1
     
    If (Val(Trim$(DataGrid.Columns(0).Value)) >= 6000 And Val(Trim$(DataGrid.Columns(0).Value)) < 7000) Then
      SqlStmt = " Select * from SERVICE where SERVICE_CODE = '" & Trim$(DataGrid.Columns(0).Value) & "'" _
         & " and status = 'N'"
    Else
      SqlStmt = " Select * from SERVICE where SERVICE_CODE = '" & Trim$(DataGrid.Columns(0).Value) & "'"
    End If
    
    Set dsSERVICE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsSERVICE.RecordCount > 0 Then
      dsSERVICE.Edit
      
      dsSERVICE.Fields("SERVICE_CODE").Value = DataGrid.Columns(0).Value
      dsSERVICE.Fields("SERVICE_NAME").Value = DataGrid.Columns(1).Value
      
      '4/30/2007 HD 2759 Rudy:
      If dsSERVICE.Fields.Count > 5 Then        'new field exists
        dsSERVICE.Fields("COMMODITY_REQUIRED").Value = DataGrid.Columns(3).Value
        
        '6/19/2007 Rudy:
        dsSERVICE.Fields("STATUS").Value = DataGrid.Columns(4).Value
      End If
      
      SqlStmt = " Select * from SERVICE_GROUP where SERVICE_GROUP_ID = '" & Trim$(DataGrid.Columns(2).Value) & "'"
        
      Set dsGROUPID = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
      If dsGROUPID.RecordCount <> 0 Then
        dsSERVICE.Fields("SERVICE_GROUP_ID").Value = DataGrid.Columns(2).Value
      Else
        MsgBox "You are trying to map Service Code " & DataGrid.Columns(0).Value & " with an Invalid Service Group Id", vbExclamation, "Save"
        OraSession.ROLLBACK
        Exit Sub
      End If
    
    End If
    
    dsSERVICE.Update
    
    DataGrid.MoveNext
  Next iRec
  
  If OraDatabase.LastServerErr <> 0 Then
  
    'Rollback transaction
    'MsgBox "Error occured while saving to TRANSFER_CARGO table. Changes are not saved.", vbExclamation, "Save"
    MsgBox "Error occured while saving to SERVICE table. Changes are not saved.", vbExclamation, "Save"

    OraSession.ROLLBACK
    Exit Sub
  Else
  
    'Commit transaction
    OraSession.CommitTrans
    MsgBox "Modified Service Code(s) Successfully.", vbExclamation, "Save"
  End If
    
  DataGrid.RemoveAll
  Call Data
  
End Sub
Private Sub cmdExit_Click()

  Unload Me
  frmMaster.ZOrder 1
  
End Sub
Private Sub DataGrid_click()
        
  iService = Val(DataGrid.Columns(0).Value)

End Sub
Private Sub Form_Load()

'Create Connection to Datbase

  
  Me.Show
  DoEvents
  
  StatusBar1.SimpleText = "Logging onto DataBase..."
  
  Set OraSession = CreateObject("OracleInProcServer.XOraSession")
  'Set OraDatabase = OraSession.Open Database("LCS", "LABOR/LABOR", 0&)
  Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)  '4/30/2007 HD 2759 Rudy: one init, orig above Tested
  
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

Dim dsSERVICE As Object
Dim iRec As Integer
Dim Serv_Group_Id As Integer
Dim CommodityRequired As String


  SqlStmt = " Select * from SERVICE where service_code < 6000 or service_code >= 7000" _
            & " or (service_code between 6000 and 6999" _
            & " and status = 'N')" _
            & " order by SERVICE_CODE"
  
  'SqlStmt = " Select * from SERVICE order by SERVICE_CODE"
  
  Set dsSERVICE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
  
  If OraDatabase.LastServerErr = 0 And dsSERVICE.RecordCount > 0 Then
  
    With dsSERVICE
      
      For iRec = 1 To .RecordCount
        DoEvents
        
        '4/30/2007 HD 2759 Rudy: begin
        If dsSERVICE.Fields.Count > 5 Then        'new field exists
          CommodityRequired = "Y"
          If Len(Trim(.Fields("COMMODITY_REQUIRED").Value)) = 1 Then
'            DataGrid.AddItem .Fields("SERVICE_CODE").Value + Chr(9) + _
'            .Fields("SERVICE_NAME").Value + Chr(9) + _
'            .Fields("SERVICE_GROUP_ID").Value + Chr(9) + _
'            .Fields("COMMODITY_REQUIRED").Value
            
            '6/19/2007 HD 2759 Rudy: begin
            If IsNull(dsSERVICE.Fields("STATUS").Value) = True Then
              DataGrid.AddItem .Fields("SERVICE_CODE").Value + Chr(9) + _
              .Fields("SERVICE_NAME").Value + Chr(9) + _
              .Fields("SERVICE_GROUP_ID").Value + Chr(9) + _
              .Fields("COMMODITY_REQUIRED").Value + Chr(9)
            Else
              DataGrid.AddItem .Fields("SERVICE_CODE").Value + Chr(9) + _
              .Fields("SERVICE_NAME").Value + Chr(9) + _
              .Fields("SERVICE_GROUP_ID").Value + Chr(9) + _
              .Fields("COMMODITY_REQUIRED").Value + Chr(9) + _
              .Fields("STATUS").Value
            End If
            
            
            
          Else
            DataGrid.AddItem .Fields("SERVICE_CODE").Value + Chr(9) + _
            .Fields("SERVICE_NAME").Value + Chr(9) + _
            .Fields("SERVICE_GROUP_ID").Value + Chr(9) + _
            CommodityRequired
          End If
        Else
'          DataGrid.AddItem .Fields("SERVICE_CODE").Value + Chr(9) + _
'                           .Fields("SERVICE_NAME").Value + Chr(9) + _
'                           .Fields("SERVICE_GROUP_ID").Value + Chr(9)
          'original .additem above
          CommodityRequired = "Not set-up call TS"
          DataGrid.AddItem .Fields("SERVICE_CODE").Value + Chr(9) + _
                   .Fields("SERVICE_NAME").Value + Chr(9) + _
                   .Fields("SERVICE_GROUP_ID").Value + Chr(9) + _
                   CommodityRequired + Chr(9)
        End If
        '4/30/2007 HD 2759 Rudy: End
        
        DataGrid.Refresh
        .MoveNext
      Next iRec
    End With
    
    StatusBar1.SimpleText = dsSERVICE.RecordCount & "  Record(s)"
      
  End If

End Sub
