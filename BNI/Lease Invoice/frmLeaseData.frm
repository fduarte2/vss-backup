VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCTL.OCX"
Begin VB.Form frmLeaseData 
   Caption         =   "LEASE RATE - DATA ENTRY "
   ClientHeight    =   9480
   ClientLeft      =   165
   ClientTop       =   450
   ClientWidth     =   14850
   BeginProperty Font 
      Name            =   "Times New Roman"
      Size            =   9
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   MaxButton       =   0   'False
   ScaleHeight     =   9480
   ScaleWidth      =   14850
   StartUpPosition =   3  'Windows Default
   Begin MSComctlLib.StatusBar StatusBar1 
      Align           =   2  'Align Bottom
      Height          =   330
      Left            =   0
      TabIndex        =   7
      Top             =   9150
      Width           =   14850
      _ExtentX        =   26194
      _ExtentY        =   582
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
         EndProperty
      EndProperty
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "PRINT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   2205
      TabIndex        =   6
      Top             =   8520
      Width           =   1335
   End
   Begin VB.CommandButton cmdHistory 
      Caption         =   "HISTORY"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   4104
      TabIndex        =   5
      Top             =   8520
      Width           =   1335
   End
   Begin VB.CommandButton cmdInv 
      Caption         =   "GENERATE INVOICES"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   11700
      TabIndex        =   4
      Top             =   8520
      Width           =   1335
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "EXIT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   9801
      TabIndex        =   3
      Top             =   8520
      Width           =   1335
   End
   Begin VB.CommandButton cmdClear 
      Caption         =   "REFRESH"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   7902
      TabIndex        =   2
      Top             =   8520
      Width           =   1335
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "SAVE"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   6003
      TabIndex        =   1
      Top             =   8520
      Width           =   1335
   End
   Begin SSDataWidgets_B.SSDBGrid grdLease 
      Height          =   8175
      Left            =   120
      TabIndex        =   0
      Top             =   120
      Width           =   14655
      _Version        =   196616
      DataMode        =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      AllowAddNew     =   -1  'True
      ForeColorEven   =   8388608
      RowHeight       =   450
      ExtraHeight     =   1905
      Columns.Count   =   18
      Columns(0).Width=   1191
      Columns(0).Caption=   "CUST"
      Columns(0).Name =   "CUST"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   3200
      Columns(1).Visible=   0   'False
      Columns(1).Caption=   "CONT#"
      Columns(1).Name =   "CONT#"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1349
      Columns(2).Caption=   "ACTIVE"
      Columns(2).Name =   "ACTIVE"
      Columns(2).Alignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(2).Style=   2
      Columns(3).Width=   1058
      Columns(3).Caption=   "SER"
      Columns(3).Name =   "SER"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1244
      Columns(4).Caption=   "COMM"
      Columns(4).Name =   "COMM"
      Columns(4).Alignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1244
      Columns(5).Caption=   "ASSET"
      Columns(5).Name =   "ASSET"
      Columns(5).Alignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1588
      Columns(6).Caption=   "RATE"
      Columns(6).Name =   "RATE/MO"
      Columns(6).Alignment=   1
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1349
      Columns(7).Caption=   "QTY"
      Columns(7).Name =   "QTY"
      Columns(7).Alignment=   1
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1508
      Columns(8).Caption=   "UNT"
      Columns(8).Name =   "UNT"
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1296
      Columns(9).Caption=   "PERIOD"
      Columns(9).Name =   "PERIOD"
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   3916
      Columns(10).Caption=   "DESCRIPTION"
      Columns(10).Name=   "DESCRIPTION"
      Columns(10).CaptionAlignment=   2
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   1693
      Columns(11).Caption=   "START DT"
      Columns(11).Name=   "START DT"
      Columns(11).CaptionAlignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   1429
      Columns(12).Caption=   "END DT"
      Columns(12).Name=   "END DT"
      Columns(12).CaptionAlignment=   2
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   1614
      Columns(13).Caption=   "REV DT"
      Columns(13).Name=   "REV DT"
      Columns(13).CaptionAlignment=   2
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      Columns(14).Width=   3200
      Columns(14).Visible=   0   'False
      Columns(14).Caption=   "LEASEID"
      Columns(14).Name=   "LEASEID"
      Columns(14).DataField=   "Column 14"
      Columns(14).DataType=   8
      Columns(14).FieldLen=   256
      Columns(15).Width=   1270
      Columns(15).Caption=   "FROM"
      Columns(15).Name=   "FROM"
      Columns(15).DataField=   "Column 15"
      Columns(15).DataType=   8
      Columns(15).FieldLen=   256
      Columns(16).Width=   1270
      Columns(16).Caption=   "TO"
      Columns(16).Name=   "TO"
      Columns(16).DataField=   "Column 16"
      Columns(16).DataType=   8
      Columns(16).FieldLen=   256
      Columns(17).Width=   1984
      Columns(17).Caption=   "BILLED TO"
      Columns(17).Name=   "BILLED TO"
      Columns(17).DataField=   "Column 17"
      Columns(17).DataType=   8
      Columns(17).FieldLen=   256
      _ExtentX        =   25850
      _ExtentY        =   14420
      _StockProps     =   79
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
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
End
Attribute VB_Name = "frmLeaseData"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim SqlStmt As String
Dim iRec As Integer
Dim dsLEASE As Object
Dim sPeriod As String
Dim sActive As String
Dim dsLEASE_HISTORY As Object
Private Sub cmdClear_Click()

    grdLease.RemoveAll
    StatusBar1.SimpleText = ""
    Call FillGrd
    
End Sub
Private Sub cmdExit_Click()

    Unload Me
    
End Sub
Private Sub cmdHistory_Click()
    
    frmHistory.Show

End Sub
Private Sub cmdInv_Click()

    frmLeaseInv.Show
    
End Sub
Private Sub cmdPrint_Click()
    
    If grdLease.Rows = 0 Then Exit Sub
    
    Printer.Orientation = 2
    Printer.Print ""
    Printer.Print Tab(5); "Printed on:"; Tab(20); Format(Date, "MM/DD/YYYY")
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 11
    Printer.Print Tab(65); "LEASE DATA ENTRY SCREEN"
    Printer.FontSize = 7
    Printer.Print ""
    Printer.Print
    Printer.Print ""
    Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
     Printer.Print ; Tab(5); "ACTIVE"; Tab(15); "CUST"; Tab(25); "CONT#"; Tab(35); "SER"; Tab(45); "COMM"; _
                     Tab(55); "ASSET"; Tab(65); "RATE"; Tab(75); "QTY"; Tab(90); "UNT"; _
                     Tab(105); "PERIOD"; Tab(120); "START DT"; Tab(135); "END DT"; Tab(150); "REV DT"; Tab(165); _
                     "FROM"; Tab(180); "TO"; Tab(195); "BILLED TO"; Tab(210); "DESC"
    Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
    
    With grdLease
        .MoveFirst
    
        For iRec = 0 To .Rows - 1
            
            Printer.Print ""
            If .Columns(2).Value = -1 Then
                sActive = "YES"
            Else
                sActive = "NO"
            End If
            
            Printer.Print Tab(5); sActive; Tab(15); .Columns(0).Value; Tab(25); .Columns(1).Value; _
                          Tab(35); Trim(.Columns(3).Value); Tab(45); Trim(.Columns(4).Value); _
                          Tab(55); Trim(.Columns(5).Value); Tab(65); Trim(.Columns(6).Value); _
                          Tab(75); Trim(.Columns(7).Value); Tab(90); Trim(.Columns(8).Value); _
                          Tab(105); Trim(.Columns(9).Value); Tab(120); Trim(.Columns(11).Value); _
                          Tab(135); Trim(.Columns(12).Value); Tab(150); Trim(.Columns(13).Value); _
                          Tab(165); Trim(.Columns(15).Value); Tab(180); Trim(.Columns(16).Value); _
                          Tab(195); Trim(.Columns(17).Value); Tab(210); Trim(.Columns(10).Value)
                          
            .MoveNext
            
        Next iRec
        
        Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
        
        .MoveFirst
    End With
    
    Printer.EndDoc
    
End Sub
Private Sub cmdSave_Click()

    Dim iCol As Integer
    Dim iLeaseId As Integer
    
    If grdLease.Rows = 0 Then Exit Sub
    
    StatusBar1.SimpleText = "SAVING IN PROGRESS ..."
    
'    For iRec = 0 To grdLease.Rows - 1
'        For iCol = 0 To grdLease.Cols
'            If grdLease.Columns(iCol).Value = "" Then
'                MsgBox "Some fields in the grid are empty", vbInformation, "LEASE RATE"
'                Exit Sub
'            End If
'        Next iCol
'    Next iRec
    
    OraSession.BeginTrans
    
    grdLease.MoveFirst
    SqlStmt = "SELECT MAX(LEASE_ID) MLeaseID FROM LEASE_RATE"
    Set dsLEASE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        iLeaseId = dsLEASE.fields("MLeaseID").Value
    End If
        
    For iRec = 0 To grdLease.Rows - 1
    
        SqlStmt = " SELECT * FROM LEASE_RATE WHERE LEASE_ID='" & grdLease.Columns(14).Value & "'"
        Set dsLEASE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        
        If OraDatabase.LastServerErr = 0 Then
            sActive = ""
            sPeriod = ""
            
            If grdLease.Columns(2).Value = -1 Then sActive = "Y"
            If grdLease.Columns(2).Value = 0 Then sActive = "N"
                            
            If dsLEASE.recordcount > 0 Then
                dsLEASE.EDIT
                
                dsLEASE.fields("CUSTOMER_ID").Value = grdLease.Columns(0).Value      'Not Allowed to edit
                'dsLEASE.fields("CONTRACT_NUM").Value = grdLease.Columns(1).Value        'Presently Not using
                dsLEASE.fields("ACTIVE_STATUS").Value = sActive
                'dsLEASE.fields("GL_CODE").Value = grdLease.Columns(3).Value
                dsLEASE.fields("SERVICE_CODE").Value = grdLease.Columns(3).Value     'Not Allowed
                dsLEASE.fields("COMMODITY_CODE").Value = grdLease.Columns(4).Value   'Not Allowed
                dsLEASE.fields("ASSET").Value = grdLease.Columns(5).Value             'Not Allowed
                dsLEASE.fields("RATE").Value = Trim(grdLease.Columns(6).Value)
                dsLEASE.fields("QTY").Value = Trim(grdLease.Columns(7).Value)
                dsLEASE.fields("UNIT").Value = Trim(grdLease.Columns(8).Value)
                dsLEASE.fields("PERIOD").Value = Trim(grdLease.Columns(9).Value)
                dsLEASE.fields("DESCRIPTION").Value = Trim(grdLease.Columns(10).Value)
                                
                dsLEASE.Update
                
                SqlStmt = "SELECT * FROM LEASE_HISTORY WHERE LEASE_ID='" & dsLEASE.fields("LEASE_ID").Value & "'"
                Set dsLEASE_HISTORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                dsLEASE_HISTORY.EDIT
                dsLEASE_HISTORY.fields("LEASE_START_DATE").Value = Trim$(grdLease.Columns(11).Value)
                dsLEASE_HISTORY.fields("LEASE_END_DATE").Value = Trim$(grdLease.Columns(12).Value)
                dsLEASE_HISTORY.fields("REVIEW_DATE").Value = Trim(grdLease.Columns(13).Value)
                dsLEASE_HISTORY.fields("BILLING_FROM").Value = Trim(grdLease.Columns(15).Value)
                dsLEASE_HISTORY.fields("BILLING_TO").Value = Trim(grdLease.Columns(16).Value)
                dsLEASE_HISTORY.fields("BILLED_TO_DATE").Value = Trim(grdLease.Columns(17).Value)
                dsLEASE_HISTORY.Update
                
            Else
                iLeaseId = iLeaseId + 1
                dsLEASE.AddNew
                dsLEASE.fields("LEASE_ID").Value = iLeaseId
                dsLEASE.fields("ACTIVE_STATUS").Value = sActive
                dsLEASE.fields("CUSTOMER_ID").Value = grdLease.Columns(0).Value
                'dsLEASE.fields("CONTRACT_NUM").Value = grdLease.Columns(1).Value
                dsLEASE.fields("SERVICE_CODE").Value = grdLease.Columns(3).Value
                dsLEASE.fields("COMMODITY_CODE").Value = grdLease.Columns(4).Value
                dsLEASE.fields("ASSET").Value = grdLease.Columns(5).Value
                dsLEASE.fields("RATE").Value = grdLease.Columns(6).Value
                dsLEASE.fields("QTY").Value = grdLease.Columns(7).Value
                dsLEASE.fields("UNIT").Value = grdLease.Columns(8).Value
                dsLEASE.fields("PERIOD").Value = grdLease.Columns(9).Value
                dsLEASE.fields("DESCRIPTION").Value = grdLease.Columns(10).Value
                
                dsLEASE.Update
                
                SqlStmt = "SELECT * FROM LEASE_HISTORY "
                Set dsLEASE_HISTORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                dsLEASE_HISTORY.AddNew
                dsLEASE_HISTORY.fields("LEASE_ID").Value = iLeaseId
                dsLEASE_HISTORY.fields("LEASE_START_DATE").Value = grdLease.Columns(11).Value
                dsLEASE_HISTORY.fields("LEASE_END_DATE").Value = grdLease.Columns(12).Value
                dsLEASE_HISTORY.fields("REVIEW_DATE").Value = grdLease.Columns(13).Value
                dsLEASE_HISTORY.fields("BILLING_FROM").Value = Trim(grdLease.Columns(15).Value)
                dsLEASE_HISTORY.fields("BILLING_TO").Value = Trim(grdLease.Columns(16).Value)
                dsLEASE_HISTORY.fields("BILLED_TO_DATE").Value = Trim(grdLease.Columns(17).Value)
                dsLEASE_HISTORY.Update
                
            End If
        End If
        grdLease.MoveNext
    Next iRec
    OraSession.commitTrans
    StatusBar1.SimpleText = ""
    MsgBox "Saved changes successfully", vbInformation, "SAVE"
    grdLease.RemoveAll
    Call FillGrd
    
End Sub
Private Sub Form_Load()
    
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
        
    Call FillGrd

End Sub
Sub FillGrd()

    Dim sSDt As String
    Dim sEDt As String
    Dim sFrom As String
    Dim sTo As String
    Dim sRevDt As String
    Dim vAct
    
    grdLease.RowHeight = 300
    
    SqlStmt = "SELECT * FROM LEASE_RATE ORDER BY CUSTOMER_ID,SERVICE_CODE,COMMODITY_CODE"
    Set dsLEASE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        
    If OraDatabase.LastServerErr = 0 And dsLEASE.recordcount > 0 Then
        With dsLEASE
            For iRec = 1 To dsLEASE.recordcount
                
                SqlStmt = "SELECT * FROM LEASE_HISTORY WHERE LEASE_ID='" & dsLEASE.fields("LEASE_ID").Value & "'"
                Set dsLEASE_HISTORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsLEASE_HISTORY.recordcount > 0 Then
                    If Not IsNull(dsLEASE_HISTORY.fields("LEASE_START_DATE").Value) Then
                        sSDt = Format(dsLEASE_HISTORY.fields("LEASE_START_DATE").Value, "MM/DD/YYYY")
                    Else
                        sSDt = ""
                    End If
                    
                    If Not IsNull(dsLEASE_HISTORY.fields("LEASE_END_DATE").Value) Then
                        sEDt = Format(dsLEASE_HISTORY.fields("LEASE_END_DATE").Value, "MM/DD/YYYY")
                    Else
                        sEDt = ""
                    End If
                    
                    If Not IsNull(dsLEASE_HISTORY.fields("BILLING_FROM").Value) Then
                        sFrom = dsLEASE_HISTORY.fields("BILLING_FROM").Value
                    Else
                        sFrom = ""
                    End If
                    
                    If Not IsNull(dsLEASE_HISTORY.fields("BILLING_TO").Value) Then
                        sTo = dsLEASE_HISTORY.fields("BILLING_TO").Value
                    Else
                        sTo = ""
                    End If
                    
                    If Not IsNull(dsLEASE_HISTORY.fields("REVIEW_DATE").Value) Then
                        sRevDt = Format(dsLEASE_HISTORY.fields("REVIEW_DATE").Value, "MM/DD/YYYY")
                    Else
                        sRevDt = ""
                    End If
                    
                End If
                                           
                If .fields("ACTIVE_STATUS").Value = "N" Then vAct = 0
                If .fields("ACTIVE_STATUS").Value = "Y" Then vAct = -1
                'If Trim("" & .fields("ACTIVE_STATUS").Value) = "" Then sActive = ""
                
                                
                grdLease.AddItem Trim("" & .fields("CUSTOMER_ID").Value) + Chr(9) + _
                                 Trim("" & .fields("CONTRACT_NUM").Value) + Chr(9) + CStr(vAct) + Chr(9) + _
                                 Trim("" & .fields("SERVICE_CODE").Value) + Chr(9) + _
                                 Trim("" & .fields("COMMODITY_CODE").Value) + Chr(9) + _
                                 Trim("" & .fields("ASSET").Value) + Chr(9) + _
                                 Trim("" & .fields("RATE").Value) + Chr(9) + _
                                 Trim("" & .fields("QTY").Value) + Chr(9) + _
                                 Trim("" & .fields("UNIT").Value) + Chr(9) + _
                                 Trim("" & .fields("PERIOD").Value) + Chr(9) + _
                                 Trim("" & .fields("DESCRIPTION").Value) + Chr(9) + _
                                 sSDt + Chr(9) + sEDt + Chr(9) + sRevDt + Chr(9) + _
                                 .fields("LEASE_ID").Value + Chr(9) + sFrom + Chr(9) + sTo + Chr(9) + _
                                 Format(dsLEASE_HISTORY.fields("BILLED_TO_DATE").Value, "MM/DD/YYYY")
                                 
                 grdLease.Refresh
                dsLEASE.MoveNext
            Next iRec
        End With
    End If
    StatusBar1.SimpleText = grdLease.Rows & " Records"
    
End Sub
Private Sub grdLease_AfterColUpdate(ByVal ColIndex As Integer)
    
    Dim iPos As Integer
    Dim iPos1 As Integer
    
    Select Case ColIndex
                        
        Case 11, 12, 13
        
            If Trim(grdLease.Columns(ColIndex).Value) = "" Or Not IsDate(grdLease.Columns(ColIndex).Value) Then
                grdLease.Columns(ColIndex).Value = ""
                Exit Sub
            Else
                grdLease.Columns(ColIndex).Value = Format(grdLease.Columns(ColIndex).Value, "MM/DD/YYYY")
            End If
         
         Case 16, 15
            
            If Trim(grdLease.Columns(ColIndex).Value) = "" Then Exit Sub
            
            iPos = InStr(1, Trim(grdLease.Columns(ColIndex).Value), "/")
            
            If iPos = 0 Then
                MsgBox "Enter the values in the format of 'MM/DD'", vbInformation, "LEASE"
                grdLease.Columns(ColIndex).Value = ""
                Exit Sub
            End If
            
            iPos1 = InStr(iPos + 1, Trim(grdLease.Columns(ColIndex).Value), "/")
            If iPos1 <> 0 Then
                MsgBox "Don't use Year .Enter the values in the format of 'MM/DD'", vbInformation, "LEASE"
                grdLease.Columns(ColIndex).Value = ""
                Exit Sub
            End If
                    
            If Val(Mid(Trim(grdLease.Columns(ColIndex).Value), 1, iPos - 1)) > 12 Then
                MsgBox "Month should be equal or less then 12 " & vbCrLf & "Enter the values in the format of 'MM/DD'", vbInformation, "LEASE"
                grdLease.Columns(ColIndex).Value = ""
                Exit Sub
            End If
            
            If Val(Mid(Trim(grdLease.Columns(ColIndex).Value), iPos + 1)) > 31 Then
                MsgBox "Date should be equal or less then 31 " & vbCrLf & "Enter the values in the format of 'MM/DD'", vbInformation, "LEASE"
                grdLease.Columns(ColIndex).Value = ""
                Exit Sub
            End If
            
    End Select
    
End Sub
Private Sub grdLease_BeforeColUpdate(ByVal ColIndex As Integer, ByVal OldValue As Variant, Cancel As Integer)
    
    Dim dsGRD As Object
    
    Select Case ColIndex
        
        Case 0     'customer Id
            If Trim(grdLease.Columns(0).Value) = "" Then Exit Sub
            
            SqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID='" & Trim(grdLease.Columns(0).Value) & "'"
            Set dsGRD = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsGRD.recordcount = 0 Then
                MsgBox "INVALID CUSTOMER", vbInformation, "LEASE RATE"
                grdLease.Columns(0).Value = OldValue
                Cancel = False
            End If
        
        Case 2    'Active status
        
                    
        Case 3       'SERVICE
            
            If Trim(grdLease.Columns(4).Value) = "" Then Exit Sub
                        
            SqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE='" & Trim(grdLease.Columns(3).Value) & "'"
            Set dsGRD = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsGRD.recordcount = 0 Then
                MsgBox "INVALID SERVICE", vbInformation, "LEASE RATE"
                grdLease.Columns(3).Value = OldValue
                Cancel = False
            End If
            
        Case 4       'COMMODITY
        
            If Trim(grdLease.Columns(5).Value) = "" Then Exit Sub
            
            SqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE='" & Trim(grdLease.Columns(4).Value) & "'"
            Set dsGRD = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsGRD.recordcount = 0 Then
                MsgBox "INVALID COMMODITY", vbInformation, "LEASE RATE"
                grdLease.Columns(4).Value = OldValue
                Cancel = False
            End If
            
        Case 6       'RATE
            If Not IsNumeric(grdLease.Columns(6).Value) Then
                MsgBox "RATE SHOULD BE NUMERIC !", vbInformation, "LEASE"
                grdLease.Columns(6).Value = ""
                Exit Sub
            End If
            
       Case 7       'QTY
            If Not IsNumeric(grdLease.Columns(7).Value) Then
                MsgBox "QTY SHOULD BE NUMERIC !", vbInformation, "LEASE"
                grdLease.Columns(7).Value = ""
                Exit Sub
            End If
        
        Case 9       'PERIOD
            If Not IsNumeric(grdLease.Columns(9).Value) Then
                MsgBox "PERIOD SHOULD BE NUMERIC !", vbInformation, "LEASE"
                grdLease.Columns(9).Value = ""
                Exit Sub
            End If
    End Select
            
End Sub
Private Sub grdLease_KeyPress(KeyAscii As Integer)
                
     KeyAscii = Asc(UCase(Chr(KeyAscii)))
     
End Sub

