VERSION 5.00
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "SSDW3B32.OCX"
Begin VB.Form frmBNIMiscBills 
   AutoRedraw      =   -1  'True
   Caption         =   "BNI MISC BILLS"
   ClientHeight    =   10860
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   16725
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
   MaxButton       =   0   'False
   ScaleHeight     =   10860
   ScaleWidth      =   16725
   StartUpPosition =   3  'Windows Default
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
      Height          =   375
      Left            =   15000
      TabIndex        =   10
      Top             =   720
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
      Height          =   375
      Left            =   13240
      TabIndex        =   7
      Top             =   720
      Width           =   1335
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
      Height          =   375
      Left            =   11480
      TabIndex        =   6
      Top             =   720
      Width           =   1335
   End
   Begin SSDataWidgets_B.SSDBGrid grdTrans 
      Height          =   3135
      Left            =   675
      TabIndex        =   5
      Top             =   7560
      Width           =   15375
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   10
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowGroupSizing=   0   'False
      AllowColumnSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   8388608
      RowHeight       =   503
      ExtraHeight     =   26
      Columns.Count   =   10
      Columns(0).Width=   2143
      Columns(0).Caption=   "VESSEL"
      Columns(0).Name =   "VESSEL"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   2064
      Columns(1).Caption=   "ORIG CUST"
      Columns(1).Name =   "ORIG CUST"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   2223
      Columns(2).Caption=   "NEW CUST"
      Columns(2).Name =   "NEW CUST"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1852
      Columns(3).Caption=   "DATE"
      Columns(3).Name =   "DATE"
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2011
      Columns(4).Caption=   "ORDER #"
      Columns(4).Name =   "ORDER #"
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1693
      Columns(5).Caption=   "PLTS"
      Columns(5).Name =   "PLTS"
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   2328
      Columns(6).Caption=   "COMM"
      Columns(6).Name =   "COMMODITY"
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   2223
      Columns(7).Caption=   "SERV"
      Columns(7).Name =   "SERV"
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   2143
      Columns(8).Caption=   "AMOUNT"
      Columns(8).Name =   "AMOUNT"
      Columns(8).Alignment=   1
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   7673
      Columns(9).Caption=   "DESCRIPTION"
      Columns(9).Name =   "DESCRIPTION"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      _ExtentX        =   27120
      _ExtentY        =   5530
      _StockProps     =   79
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   6015
      Left            =   75
      TabIndex        =   4
      Top             =   1440
      Width           =   16575
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   12
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowColumnSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   8388608
      RowHeight       =   450
      ExtraHeight     =   53
      Columns.Count   =   12
      Columns(0).Width=   1693
      Columns(0).Caption=   "DATE"
      Columns(0).Name =   "DATE"
      Columns(0).Alignment=   2
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   2170
      Columns(1).Caption=   "CUSTOMER"
      Columns(1).Name =   "CUSTOMER"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1588
      Columns(2).Caption=   "VESSEL"
      Columns(2).Name =   "VESSEL"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   2275
      Columns(3).Caption=   "ORDER #"
      Columns(3).Name =   "ORDER #"
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1640
      Columns(4).Caption=   "COMM"
      Columns(4).Name =   "COMMODITY"
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1429
      Columns(5).Caption=   "SERV"
      Columns(5).Name =   "SERV"
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1455
      Columns(6).Caption=   "PLTS"
      Columns(6).Name =   "PLTS"
      Columns(6).Alignment=   1
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1826
      Columns(7).Caption=   "CASES"
      Columns(7).Name =   "CASES"
      Columns(7).Alignment=   1
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1535
      Columns(8).Caption=   "AVG WT"
      Columns(8).Name =   "AVG WT"
      Columns(8).Alignment=   1
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1588
      Columns(9).Caption=   "WEIGHT"
      Columns(9).Name =   "WEIGHT"
      Columns(9).Alignment=   1
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   1799
      Columns(10).Caption=   "AMOUNT"
      Columns(10).Name=   "AMOUNT"
      Columns(10).Alignment=   1
      Columns(10).CaptionAlignment=   2
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   9340
      Columns(11).Caption=   "DESCRIPTION"
      Columns(11).Name=   "DESCRIPTION"
      Columns(11).CaptionAlignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      _ExtentX        =   29236
      _ExtentY        =   10610
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
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.CommandButton cmdRet 
      Caption         =   "RETRIEVE"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   9720
      TabIndex        =   3
      Top             =   720
      Width           =   1335
   End
   Begin VB.CheckBox chkService 
      Caption         =   "ALL"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   8520
      TabIndex        =   2
      Top             =   780
      Width           =   975
   End
   Begin VB.ComboBox cboService 
      Height          =   345
      ItemData        =   "frmBNIMiscBills.frx":0000
      Left            =   5760
      List            =   "frmBNIMiscBills.frx":0010
      Sorted          =   -1  'True
      TabIndex        =   1
      Top             =   735
      Width           =   2415
   End
   Begin MSComCtl2.DTPicker dtpDate 
      Height          =   375
      Left            =   5760
      TabIndex        =   8
      Top             =   240
      Width           =   1215
      _ExtentX        =   2143
      _ExtentY        =   661
      _Version        =   393216
      CustomFormat    =   "MM/dd/yyyy"
      Format          =   22872067
      CurrentDate     =   36955
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "DATE  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   4890
      TabIndex        =   9
      Top             =   315
      Width           =   645
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "SERVICE TYPE  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   4080
      TabIndex        =   0
      Top             =   795
      Width           =   1455
   End
End
Attribute VB_Name = "frmBNIMiscBills"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim SqlStmt As String
Dim iRec As Integer

Private Sub chkService_Click()
    If chkService.Value = vbChecked Then cboService.ListIndex = -1
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub
Sub LoadGrid1(rs As Object)
    With rs
        For iRec = 1 To .recordcount
            grdData.AddItem Format(.FIELDS("SERVICE_DATE").Value, "MM/DD/YYYY") + Chr(9) + .FIELDS("CUSTOMER_ID").Value + Chr(9) + _
                            .FIELDS("LR_NUM").Value + Chr(9) + .FIELDS("ORDER_NUM").Value + Chr(9) + _
                            .FIELDS("COMMODITY_CODE").Value + Chr(9) + .FIELDS("SERVICE_CODE").Value + Chr(9) + _
                            .FIELDS("SERVICE_QTY").Value + Chr(9) + .FIELDS("CASES").Value + Chr(9) + _
                            .FIELDS("AVG_WT").Value + Chr(9) + .FIELDS("WEIGHT").Value + Chr(9) + _
                            .FIELDS("AMOUNT").Value + Chr(9) + .FIELDS("DESCRIPTION").Value
            .MoveNext
        Next iRec
    End With

End Sub

Sub LoadGrid2(rs As Object)
    With rs
        For iRec = 1 To .recordcount
            grdTrans.AddItem .FIELDS("LR_NUM").Value + Chr(9) + .FIELDS("CUSTOMER_ID").Value + Chr(9) + _
                             .FIELDS("TRANSFER_TO").Value + Chr(9) + Format(.FIELDS("SERVICE_DATE").Value, "MM/DD/YYYY") + Chr(9) + _
                             .FIELDS("ORDER_NUM").Value + Chr(9) + .FIELDS("SERVICE_QTY").Value + Chr(9) + _
                             .FIELDS("COMMODITY_CODE").Value + Chr(9) + .FIELDS("SERVICE_CODE").Value + Chr(9) + _
                             .FIELDS("AMOUNT").Value + Chr(9) + .FIELDS("DESCRIPTION").Value
            .MoveNext
        Next iRec
    End With

End Sub

Private Sub cmdPrint_Click()
    
    
End Sub

Private Sub cmdRet_Click()
    
    'On Error GoTo errhandler
    
    grdData.RemoveAll
    grdTrans.RemoveAll
    
    If chkService.Value = vbChecked Then
        
        SqlStmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE IN ('7','8','13') " _
                & " AND SERVICE_DATE>= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
                & " ORDER BY CUSTOMER_ID,RF_SERVICE_CODE,LR_NUM "
                
        Set dsMISCBILLS = OraDatabase.createDynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsMISCBILLS.recordcount > 0 Then Call LoadGrid1(dsMISCBILLS)
        
        SqlStmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='11' " _
                & " AND SERVICE_DATE>= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
                & " ORDER BY CUSTOMER_ID,LR_NUM "
                
        Set dsMISCBILLS = OraDatabase.createDynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsMISCBILLS.recordcount > 0 Then Call LoadGrid2(dsMISCBILLS)
        
            
    Else
        
        If cboService.ListIndex = 0 Then
            
            SqlStmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='13' " _
                    & " AND SERVICE_DATE>= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                    & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
                    & " ORDER BY CUSTOMER_ID,LR_NUM "
            
            Set dsMISCBILLS = OraDatabase.createDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsMISCBILLS.recordcount > 0 Then Call LoadGrid1(dsMISCBILLS)
            
        
        
        ElseIf cboService.ListIndex = 1 Then
            SqlStmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='8' " _
                    & " AND SERVICE_DATE>= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                    & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
                    & " ORDER BY CUSTOMER_ID,LR_NUM "
            
            Set dsMISCBILLS = OraDatabase.createDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsMISCBILLS.recordcount > 0 Then Call LoadGrid1(dsMISCBILLS)
        
        
        ElseIf cboService.ListIndex = 2 Then
        
            SqlStmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='7' " _
                    & " AND SERVICE_DATE>= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                    & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
                    & " ORDER BY CUSTOMER_ID,LR_NUM "
            
            Set dsMISCBILLS = OraDatabase.createDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsMISCBILLS.recordcount > 0 Then Call LoadGrid1(dsMISCBILLS)
            
        
        ElseIf cboService.ListIndex = 3 Then
            SqlStmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='11' " _
                    & " AND SERVICE_DATE>= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                    & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
                    & " ORDER BY CUSTOMER_ID,LR_NUM "
            Set dsMISCBILLS = OraDatabase.createDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsMISCBILLS.recordcount > 0 Then Call LoadGrid2(dsMISCBILLS)
            
        
        End If
    End If
    
    Exit Sub
    
errhandler:
 
    If OraDatabase.LastServerErr <> 0 Then
        MsgBox OraDatabase.LastServerErrText, vbCritical, "BNI MISC BILLS"
        OraDatabase.LastServerErrReset
        Exit Sub
    End If
    
    MsgBox Err.Description, vbExclamation, "ERROR"
    Err.Clear
    Exit Sub
    
End Sub

Private Sub cmdSave_Click()
    
         If cboService.ListIndex = -1 Then Exit Sub
         
         If grdData.Rows = 0 And grdTrans.Rows = 0 Then Exit Sub
         
        If chkService.Value = vbChecked Then
            
            SqlStmt = " DELETE FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE IN ('7','8','13') AND " _
                  & " SERVICE_DATE >=TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') AND " _
                  & " SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 "
        Else
            
            If cboService.ListIndex = 0 Then
                SqlStmt = " DELETE FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='13' AND " _
                        & " SERVICE_DATE >=TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') AND " _
                        & " SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 "
            
            ElseIf cboService.ListIndex = 1 Then
                SqlStmt = " DELETE FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='8' AND " _
                        & " SERVICE_DATE >=TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') AND " _
                        & " SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 "
            
            ElseIf cboService.ListIndex = 2 Then
                SqlStmt = " DELETE FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='7' AND " _
                        & " SERVICE_DATE >=TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') AND " _
                        & " SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 "
            
            ElseIf cboService.ListIndex = 2 Then
                SqlStmt = " DELETE FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='11' AND " _
                        & " SERVICE_DATE >=TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') AND " _
                        & " SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 "
            End If
            
        End If
    
    OraSession.BeginTrans
    
        OraDatabase.ExecuteSQL (SqlStmt)
        
        
        
    
    
End Sub

Private Sub Form_Load()
    Me.Left = (Screen.Width - Me.Width) / 2
    Me.Top = (Screen.Height - Me.Height) / 2
    
    dtpDate = Format(Now, "MM/DD/YYYY")
    grdData.RowHeight = 280
    grdTrans.RowHeight = 280
End Sub
