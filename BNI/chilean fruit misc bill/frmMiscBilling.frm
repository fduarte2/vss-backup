VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.1#0"; "MSCOMCTL.OCX"
Begin VB.Form frmMiscBilling 
   AutoRedraw      =   -1  'True
   Caption         =   "MISC BILLING"
   ClientHeight    =   9585
   ClientLeft      =   165
   ClientTop       =   450
   ClientWidth     =   15240
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
   ScaleHeight     =   9585
   ScaleWidth      =   15240
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdMethyl 
      Caption         =   "ADD &METHYL BROMIDE"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   330
      Left            =   2640
      TabIndex        =   12
      Top             =   8715
      Width           =   3015
   End
   Begin VB.CommandButton cmdSecurity 
      Caption         =   "&ADD SECURITY"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   330
      Left            =   5880
      TabIndex        =   11
      Top             =   8715
      Width           =   1575
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&DELETE"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   330
      Left            =   10200
      TabIndex        =   10
      Top             =   8715
      Width           =   1170
   End
   Begin MSComctlLib.StatusBar StatusBar1 
      Align           =   2  'Align Bottom
      Height          =   330
      Left            =   0
      TabIndex        =   9
      Top             =   9255
      Width           =   15240
      _ExtentX        =   26882
      _ExtentY        =   582
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
      Caption         =   "&EXIT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   330
      Left            =   11520
      TabIndex        =   5
      Top             =   8715
      Width           =   1170
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&SAVE"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   330
      Left            =   8880
      TabIndex        =   4
      Top             =   8715
      Width           =   1170
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&PRINT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   330
      Left            =   7560
      TabIndex        =   3
      Top             =   8715
      Width           =   1170
   End
   Begin VB.CommandButton cmdRetrive 
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
      Height          =   330
      Left            =   9225
      TabIndex        =   1
      Top             =   262
      Width           =   1170
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   7470
      Left            =   120
      TabIndex        =   2
      Top             =   720
      Width           =   15000
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   12
      AllowAddNew     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowDragDrop   =   0   'False
      MaxSelectedRows =   1
      ForeColorEven   =   8388608
      RowHeight       =   503
      Columns.Count   =   12
      Columns(0).Width=   1588
      Columns(0).Caption=   "SHIP #"
      Columns(0).Name =   "SHIP #"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(0).VertScrollBar=   -1  'True
      Columns(0).HasHeadForeColor=   -1  'True
      Columns(1).Width=   1244
      Columns(1).Caption=   "CUST"
      Columns(1).Name =   "CUST"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1376
      Columns(2).Caption=   "COMM"
      Columns(2).Name =   "COMM"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1085
      Columns(3).Caption=   "SERV"
      Columns(3).Name =   "SERV"
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1905
      Columns(4).Caption=   "ORDER#"
      Columns(4).Name =   "ORDER#"
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   11774
      Columns(5).Caption=   "DESCRIPTION"
      Columns(5).Name =   "DESCRIPTION"
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1984
      Columns(6).Caption=   "AMOUNT"
      Columns(6).Name =   "AMOUNT"
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1429
      Columns(7).Caption=   "PAGE #"
      Columns(7).Name =   "PAGE #"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1720
      Columns(8).Caption=   "BILL #"
      Columns(8).Name =   "BILL #"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(8).Locked=   -1  'True
      Columns(8).HasForeColor=   -1  'True
      Columns(8).ForeColor=   255
      Columns(9).Width=   1191
      Columns(9).Caption=   "ASSET"
      Columns(9).Name =   "ASSET"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   3200
      Columns(10).Visible=   0   'False
      Columns(10).Caption=   "WEIGHT"
      Columns(10).Name=   "WEIGHT"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   3200
      Columns(11).Visible=   0   'False
      Columns(11).Caption=   "PLTS"
      Columns(11).Name=   "PLTS"
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      _ExtentX        =   26458
      _ExtentY        =   13176
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
   Begin MSComCtl2.DTPicker dtpDate 
      Height          =   330
      Left            =   7545
      TabIndex        =   0
      Top             =   262
      Width           =   1170
      _ExtentX        =   2064
      _ExtentY        =   582
      _Version        =   393216
      CustomFormat    =   "MM/dd/yyyy"
      Format          =   16449539
      CurrentDate     =   36965
   End
   Begin VB.Label lblTotAmt 
      AutoSize        =   -1  'True
      Caption         =   "0.00"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   225
      Left            =   14400
      TabIndex        =   8
      Top             =   315
      Width           =   360
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "TOTAL  AMOUNT  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   225
      Left            =   12120
      TabIndex        =   7
      Top             =   315
      Width           =   1710
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "SERVICE DATE  :"
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
      Left            =   5865
      TabIndex        =   6
      Top             =   315
      Width           =   1485
   End
End
Attribute VB_Name = "frmMiscBilling"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim Sqlstmt As String
Dim iRec As Integer
Dim dAmt As Double
Dim iRow As Integer
Private Sub cmdDelete_Click()
    
    If grdData.Rows = 0 Then Exit Sub
    
    If grdData.Row + 1 > grdData.Rows Then
        MsgBox "First Select the appropriate record !", vbInformation, "DELETE"
        Exit Sub
    End If
    
    If MsgBox("Are you sure to delete the selected record ?", vbQuestion + vbYesNo, "DELETE") = vbNo Then Exit Sub
    
    'grdData.MoveRecords (iRow)
    
    Sqlstmt = " DELETE FROM RF_BNI_MISCBILLS WHERE SERVICE_DATE >=TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
            & " AND SERVICE_DATE<TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 AND SERVICE_CODE = '" & grdData.Columns(3).Value & "' " _
            & " AND CUSTOMER_ID='" & grdData.Columns(1).Value & "' AND LR_NUM='" & grdData.Columns(0).Value & "'" _
            & " AND ORDER_NUM='" & grdData.Columns(4).Value & "' AND COMMODITY_CODE='" & grdData.Columns(2).Value & "'"
    
    OraSession.BEGINTRANS
    OraDatabase.EXECUTESQL (Sqlstmt)
    
    If OraDatabase.lastServerErr = 0 Then
        OraSession.COMMITTRANS
        If grdData.Columns(8).Value <> "" Then
            MsgBox "Please Delete bill # " & grdData.Columns(8).Value & "  from Bill Deletion screen", vbInformation, "DELETE"
        End If
        grdData.RemoveItem (grdData.Row)
        grdData.Refresh
    Else
        OraSession.Rollback
        MsgBox OraDatabase.lastServerErrText, vbCritical, "ERROR"
        OraDatabase.lastServerErrReset
    End If
    
    dAmt = 0
    grdData.MoveFirst
    For iRec = 0 To grdData.Rows - 1
        dAmt = grdData.Columns(6).Value + dAmt
        grdData.MoveNext
    Next iRec

    lblTotAmt = Format(dAmt, "00.00")
    StatusBar1.SimpleText = grdData.Rows & "  Records"
    
End Sub
Private Sub cmdExit_Click()

    Unload Me
    
End Sub
Sub PrintHeader()

    Printer.Orientation = 2
    Printer.Print "PRINTED ON : " & Format(Now, "mm/dd/yyyy")
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 12
    Printer.FontBold = True
    Printer.Print Tab(50); "MISCELLANEOUS PREINVOICES"
    Printer.FontBold = False
    Printer.FontSize = 8
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(5); "SERVICE DATE"; Tab(25); ":"; Tab(30); Format(dtpDate, "mm/dd/yyyy")
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(3); "-------------------------------------------------------------------------------------------------------------" _
                        ; "-------------------------------------------------------------------------------------------------------------" _
                        ; "-------------------------------------------------------------------------------------------------------------"
    Printer.Print Tab(5); "SHIP"; Tab(20); "CUST"; Tab(30); "COMM"; Tab(45); "SERV"; Tab(55); "PAGE#"; Tab(65); _
                  " AMOUNT"; Tab(80); "ORDER #"; Tab(135); "DESCRIPTION"
    Printer.Print Tab(3); "-------------------------------------------------------------------------------------------------------------" _
                        ; "-------------------------------------------------------------------------------------------------------------" _
                        ; "-------------------------------------------------------------------------------------------------------------"

End Sub



Private Sub cmdPrint_Click()

    Dim iCust As Long
    Dim iLrNum As Long
    Dim bFirst As Boolean
    Dim iCount As Long
    Dim iOrdNum As String
    iOrdNum = ""
    
    If grdData.Rows = 0 Then Exit Sub
    
    grdData.MoveFirst
    
    'Call PrintHeader
    bFirst = True
    With grdData
        For iRec = 0 To .Rows - 1
                       
            If iLrNum <> .Columns(0).Value Or iCust <> .Columns(1).Value Then
                
                iLrNum = .Columns(0).Value
                iCust = .Columns(1).Value
                
                If bFirst = False Then
                    Printer.Print Tab(3); "-------------------------------------------------------------------------------------------------------------" _
                                        ; "-------------------------------------------------------------------------------------------------------------" _
                                        ; "-------------------------------------------------------------------------------------------------------------"
                    Printer.FontBold = True
                    Printer.Print Tab(5); "TOTAL "; Tab(20); iCount & "  RECORD(S)"; Tab(58); dAmt
                    Printer.FontBold = False
                    Printer.Print Tab(3); "-------------------------------------------------------------------------------------------------------------" _
                                        ; "-------------------------------------------------------------------------------------------------------------" _
                                        ; "-------------------------------------------------------------------------------------------------------------"
                    Printer.NewPage
                
                End If
                bFirst = False
                Call PrintHeader
                dAmt = 0
                iCount = 0
            End If
            
            If Trim$(.Columns(4).Value) = "NON" Then
                iOrdNum = ""
            Else
                iOrdNum = Trim$(.Columns(4).Value)
            End If
            Printer.Print Tab(5); .Columns(0).Value; Tab(20); .Columns(1).Value; Tab(30); .Columns(2).Value; Tab(45); .Columns(3).Value; _
                          Tab(55); .Columns(7).Value; Tab(65); .Columns(6).Value; Tab(80); iOrdNum; Tab(100); .Columns(5).Value
            Printer.Print
            dAmt = dAmt + Val(.Columns(6).Value)
            iCount = iCount + 1
            
            grdData.MoveNext
            
        Next iRec
    End With
    
    Printer.Print Tab(3); "-------------------------------------------------------------------------------------------------------------" _
                        ; "-------------------------------------------------------------------------------------------------------------" _
                        ; "-------------------------------------------------------------------------------------------------------------"
    Printer.FontBold = True
    Printer.Print Tab(5); "TOTAL "; Tab(20); iCount & "  RECORD(S)"; Tab(58); dAmt
    Printer.FontBold = False
    Printer.Print Tab(3); "-------------------------------------------------------------------------------------------------------------" _
                        ; "-------------------------------------------------------------------------------------------------------------" _
                        ; "-------------------------------------------------------------------------------------------------------------"
                        
    Printer.EndDoc
    
End Sub
Private Sub cmdRetrive_Click()
    Dim asset1 As String
    Dim iOrderNum As String
    Dim WeightValue As String
    Dim PltValue As String
    Dim SubCust As String
    iOrderNum = ""
        
    StatusBar1.SimpleText = "Processing ..."
    
    grdData.RemoveAll
    
    DoEvents
    
    'COMMODITY_CODE != '1272'
    Sqlstmt = " SELECT * FROM BILLING WHERE SERVICE_DATE >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
            & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 AND COMMODITY_CODE NOT IN ('1272', '1299')" _
            & " AND SERVICE_STATUS = 'PREINVOICE' AND BILLING_TYPE = 'MISC' AND LR_NUM IS NOT NULL ORDER BY LR_NUM,CUSTOMER_ID,BILLING_NUM"
    Set dsBILLING = OraDatabase.CreateDynaset(Sqlstmt, 0&)
    If OraDatabase.lastServerErr = 0 And dsBILLING.RECORDCOUNT > 0 Then
        
        For iRec = 1 To dsBILLING.RECORDCOUNT
            DoEvents
            If IsNull(dsBILLING.Fields("RF_ORDER_NUM")) Then
                iOrderNum = "NON"
            Else
                iOrderNum = Trim$(dsBILLING.Fields("RF_ORDER_NUM").Value)
            End If
            If IsNull(dsBILLING.Fields("ASSET_CODE").Value) Then
                asset1 = ""
            Else
                asset1 = Trim$(dsBILLING.Fields("ASSET_CODE").Value)
            End If
            grdData.AddItem dsBILLING.Fields("LR_NUM").Value + Chr(9) + dsBILLING.Fields("CUSTOMER_ID").Value + Chr(9) + _
                            Trim("" & dsBILLING.Fields("COMMODITY_CODE").Value) + Chr(9) + dsBILLING.Fields("SERVICE_CODE").Value + Chr(9) + _
                            iOrderNum + Chr(9) + dsBILLING.Fields("SERVICE_DESCRIPTION").Value + Chr(9) + _
                            dsBILLING.Fields("SERVICE_AMOUNT").Value + Chr(9) + _
                            dsBILLING.Fields("PAGE_NUM").Value + Chr(9) + dsBILLING.Fields("BILLING_NUM").Value + Chr(9) + _
                            asset1
            grdData.Refresh
            dsBILLING.MoveNext
            
        Next iRec
    End If
    
    'COMMODITY_CODE != '1272'
    Sqlstmt = " SELECT RFB.*, DECODE(CUSTOMER_ID, 9722, 453, CUSTOMER_ID) THE_CUST FROM RF_BNI_MISCBILLS RFB WHERE SERVICE_DATE >=TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
            & " AND SERVICE_DATE<TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
            & " AND BILLING_FLAG IS NULL AND COMMODITY_CODE NOT IN ('1272', '1299') ORDER BY LR_NUM,CUSTOMER_ID,RF_SERVICE_CODE"
    Set dsMISC = OraDatabase.CreateDynaset(Sqlstmt, 0&)
    If OraDatabase.lastServerErr = 0 And dsMISC.RECORDCOUNT > 0 Then
        
        For iRec = 1 To dsMISC.RECORDCOUNT
            DoEvents
            If (IsNull(dsMISC.Fields("WEIGHT").Value)) Then
                WeightValue = "0"
            Else
                WeightValue = dsMISC.Fields("WEIGHT").Value
            End If
            If (IsNull(dsMISC.Fields("SERVICE_QTY").Value)) Then
                PltValue = "0"
            Else
                PltValue = dsMISC.Fields("SERVICE_QTY").Value
            End If
            
            grdData.AddItem dsMISC.Fields("LR_NUM").Value + Chr(9) + dsMISC.Fields("THE_CUST").Value + Chr(9) + _
                            Trim("" & dsMISC.Fields("COMMODITY_CODE").Value) + Chr(9) + dsMISC.Fields("SERVICE_CODE").Value + Chr(9) + _
                            dsMISC.Fields("ORDER_NUM").Value + Chr(9) + dsMISC.Fields("DESCRIPTION").Value + Chr(9) + _
                            dsMISC.Fields("AMOUNT").Value + Chr(9) + CStr(1) + Chr(9) + "" + Chr(9) + dsMISC.Fields("ASSET_code").Value + _
                            Chr(9) + WeightValue + Chr(9) + PltValue ' "" &
            grdData.Refresh
            
            If dsMISC.Fields("RF_SERVICE_CODE").Value = 11 And dsMISC.Fields("THE_CUST").Value <> 1913 Then
                grdData.AddItem dsMISC.Fields("LR_NUM").Value + Chr(9) + dsMISC.Fields("THE_CUST").Value + Chr(9) + _
                                Trim("" & dsMISC.Fields("COMMODITY_CODE").Value) + Chr(9) + dsMISC.Fields("SERVICE_CODE").Value + Chr(9) + _
                                dsMISC.Fields("ORDER_NUM").Value + Chr(9) + "Per-Pallet Transfer at $8.24/plt" + Chr(9) + _
                                Trim("" & Val(PltValue) * 8.24) + Chr(9) + CStr(1) + Chr(9) + "" + Chr(9) + dsMISC.Fields("ASSET_code").Value + _
                                Chr(9) + WeightValue + Chr(9) + PltValue  ' "" &
                grdData.Refresh
            End If
            
            dsMISC.MoveNext

                        
        Next iRec
    End If
    
    grdData.MoveFirst
    For iRec = 0 To grdData.Rows - 1
        dAmt = Val(grdData.Columns(6).Value) + dAmt
        grdData.MoveNext
    Next iRec
    grdData.MoveFirst
    
    lblTotAmt = Format(dAmt, "00.0")
    StatusBar1.SimpleText = grdData.Rows & "  Records"
    If grdData.Rows = 0 Then StatusBar1.SimpleText = "No Records Found"
    
End Sub
Private Sub cmdSave_Click()

    Dim i, j As Integer
    Dim iError As Integer
    Dim lRecCount As Long
    Dim rowNum As Integer
    Dim iCol As Integer
    Dim iAddNew As Boolean
    
    'Lock all the required tables in exclusive mode, try 10 times
    On Error Resume Next
    
    For i = 0 To 9
        OraDatabase.lastServerErrReset
        Sqlstmt = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
        lRecCount = OraDatabase.EXECUTESQL(Sqlstmt)
        If OraDatabase.lastServerErr = 0 Then Exit For
    Next 'i
        
    If OraDatabase.lastServerErr <> 0 Then
        OraDatabase.lastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.lastServerErrText, vbExclamation, "Save Delivery"
        Exit Sub
    End If
    
    On Error GoTo 0
    
    iError = False
    iAddNew = False
    
    For iCol = 0 To 6
        If grdData.Columns(iCol).Value = "" Then
            MsgBox "Enter  " & grdData.Columns(iCol).Caption, vbInformation, "SAVE"
            Exit Sub
        End If
    Next iCol
    
    OraSession.BEGINTRANS
    
    grdData.MoveFirst
    For iRec = 0 To grdData.Rows - 1
        If Trim(grdData.Columns(8).Value) <> "" Then
            Sqlstmt = " SELECT * FROM BILLING WHERE SERVICE_DATE = TO_DATE('" & Format(dtpDate, "mm/dd/yyyy") & "', 'MM/DD/YYYY') " _
                    & " AND BILLING_NUM='" & Trim(grdData.Columns(8).Value) & "'" _
                    & " AND SERVICE_STATUS='PREINVOICE' AND BILLING_TYPE = 'MISC' "
        
            Set dsBILLING = OraDatabase.DbCreateDynaset(Sqlstmt, 0&)
            dsBILLING.Edit
            
            iAddNew = False
            
        ElseIf Trim(grdData.Columns(8).Value) = "" Then
            Sqlstmt = "SELECT * FROM BILLING"
            Set dsBILLING = OraDatabase.DbCreateDynaset(Sqlstmt, 0&)
            
            dsBILLING.AddNew
            iAddNew = True
            Sqlstmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
            Set dsBILLING_MAX = OraDatabase.DbCreateDynaset(Sqlstmt, 0&)
            If OraDatabase.lastServerErr = 0 And dsBILLING_MAX.RECORDCOUNT > 0 Then
                If IsNull(dsBILLING_MAX.Fields("MAX(BILLING_NUM)").Value) Then
                    dsBILLING.Fields("BILLING_NUM").Value = 1
                Else
                    dsBILLING.Fields("BILLING_NUM").Value = dsBILLING_MAX.Fields("MAX(BILLING_NUM)").Value + 1
                End If
            Else
                dsBILLING.Fields("BILLING_NUM").Value = 1
            End If
        End If
              
        dsBILLING.Fields("LR_NUM").Value = Trim(grdData.Columns(0).Value)
        dsBILLING.Fields("CUSTOMER_ID").Value = Trim(grdData.Columns(1).Value)
        dsBILLING.Fields("COMMODITY_CODE").Value = Trim(grdData.Columns(2).Value)
        dsBILLING.Fields("SERVICE_CODE").Value = Trim(grdData.Columns(3).Value)
        dsBILLING.Fields("SERVICE_DESCRIPTION").Value = Trim(grdData.Columns(5).Value)
        dsBILLING.Fields("SERVICE_AMOUNT").Value = Trim(grdData.Columns(6).Value)
        dsBILLING.Fields("PAGE_NUM").Value = Val(grdData.Columns(7).Value)
        dsBILLING.Fields("EMPLOYEE_ID").Value = 4
        dsBILLING.Fields("SERVICE_STATUS").Value = "PREINVOICE"
        dsBILLING.Fields("ARRIVAL_NUM").Value = 1
        dsBILLING.Fields("INVOICE_NUM").Value = 0
        dsBILLING.Fields("SERVICE_QTY").Value = 0
        dsBILLING.Fields("SERVICE_NUM").Value = 1
        dsBILLING.Fields("THRESHOLD_QTY").Value = 0
        dsBILLING.Fields("LEASE_NUM").Value = 0
        dsBILLING.Fields("SERVICE_UNIT").Value = ""
        dsBILLING.Fields("SERVICE_RATE").Value = ""
        dsBILLING.Fields("LABOR_RATE_TYPE").Value = ""
        dsBILLING.Fields("LABOR_TYPE").Value = ""
        dsBILLING.Fields("CARE_OF").Value = 1
        dsBILLING.Fields("BILLING_TYPE").Value = "MISC"
        dsBILLING.Fields("SERVICE_START").Value = Format(dtpDate, "mm/dd/yyyy")
        dsBILLING.Fields("SERVICE_STOP").Value = Format(dtpDate, "mm/dd/yyyy")
        dsBILLING.Fields("SERVICE_DATE").Value = Format(dtpDate, "MM/DD/YYYY")
        
        
        'Added this field for Asset Coding.  06.21.2001 LJG
        
        dsBILLING.Fields("ASSET_CODE").Value = Trim(grdData.Columns(9).Value)
        
        If Trim(grdData.Columns(4).Value) <> "NON" Then
            dsBILLING.Fields("RF_ORDER_NUM").Value = Trim(grdData.Columns(4).Value)
        End If
        
        dsBILLING.Update
        
        If iAddNew Then
            'UPDATE THE TO BILLING_FLAG TO 'Y' ON RF_BNI_MISCBILLS TABLE
            Sqlstmt = "UPDATE RF_BNI_MISCBILLS SET BILLING_FLAG = 'Y'  WHERE SERVICE_DATE >= TO_DATE('" & Format(dtpDate, "mm/dd/yyyy") & "','MM/DD/YYYY')"
            Sqlstmt = Sqlstmt & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "mm/dd/yyyy") & "','MM/DD/YYYY') +1"
            If grdData.Columns(1).Value = 453 Then
                Sqlstmt = Sqlstmt & " AND CUSTOMER_ID = 9722"
            Else
                Sqlstmt = Sqlstmt & " AND CUSTOMER_ID =" & grdData.Columns(1).Value
            End If
            Sqlstmt = Sqlstmt & " AND ORDER_NUM ='" & Trim(grdData.Columns(4).Value) & "'"
            Sqlstmt = Sqlstmt & " AND COMMODITY_CODE =" & grdData.Columns(2).Value
            Sqlstmt = Sqlstmt & " AND SERVICE_CODE =" & grdData.Columns(3).Value
            
            OraDatabase.EXECUTESQL (Sqlstmt)
        End If
        If OraDatabase.lastServerErr <> 0 Then
            iError = True
        End If
                
        grdData.MoveNext
    Next iRec
    
    If iError Then
        MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
        OraSession.Rollback
        OraDatabase.lastServerErrReset
        Exit Sub
    Else
        OraSession.COMMITTRANS
        MsgBox "SAVE SUCCESSFUL", vbInformation, "SAVE"
    End If
    
End Sub

Private Sub cmdMethyl_Click()
    Dim Rate As Double
    Dim PLTS As Double
'    Dim NT As Double
    Dim NewDesc As String
    Dim AMT As Double
    
    If IsNull(grdData.Columns(11).Value) Or grdData.Columns(11).Value = "" Then
        Exit Sub
    End If
    PLTS = grdData.Columns(11).Value
    Rate = 3
    AMT = Rate * PLTS
    
    NewDesc = "METHYL BROMIDE MONITORING FEE: " & PLTS & " PALLETS @ $" & Rate & "/PLT"

    grdData.AddItem CStr(grdData.Columns(0).Value) + Chr(9) + CStr(grdData.Columns(1).Value) + Chr(9) + _
                    CStr(grdData.Columns(2).Value) + Chr(9) + "9733" + Chr(9) + _
                    CStr(grdData.Columns(4).Value) + Chr(9) + NewDesc + Chr(9) + _
                    CStr(AMT) + Chr(9) + CStr(1) + Chr(9) + "" + Chr(9) + CStr(grdData.Columns(9).Value)
    grdData.Refresh

    dAmt = 0
    grdData.MoveFirst
    For iRec = 0 To grdData.Rows - 1
        dAmt = grdData.Columns(6).Value + dAmt
        grdData.MoveNext
    Next iRec

    lblTotAmt = Format(dAmt, "00.00")
    StatusBar1.SimpleText = grdData.Rows & "  Records"

End Sub
Private Sub cmdSecurity_Click()
    Dim Rate As Double
    Dim Weight As Double
    Dim NT As Double
    Dim NewDesc As String
    Dim AMT As Double
    
    If IsNull(grdData.Columns(10).Value) Or grdData.Columns(10).Value = "" Then
        Exit Sub
    End If
    Weight = grdData.Columns(10).Value
    NT = Round(Weight / 2000, 3)
    ' hard coding sucks, but with any luck, this program will be phased out in longrun
    Sqlstmt = "SELECT RATE FROM LU_CHILEAN_MISCBILL_SECURITY"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(Sqlstmt, 0&)
    Rate = dsSHORT_TERM_DATA.Fields("RATE").Value
    'Rate = 0.15
    AMT = Round(NT * Rate, 2)
    
    NewDesc = "SECURITY FEE FOR ORDER " & grdData.Columns(4).Value & " " & grdData.Columns(10).Value & _
                " LB = " & NT & " NT @ $" & Rate & "/NT"
    
    
    grdData.AddItem CStr(grdData.Columns(0).Value) + Chr(9) + CStr(grdData.Columns(1).Value) + Chr(9) + _
                    CStr(grdData.Columns(2).Value) + Chr(9) + "2214" + Chr(9) + _
                    CStr(grdData.Columns(4).Value) + Chr(9) + NewDesc + Chr(9) + _
                    CStr(AMT) + Chr(9) + CStr(1) + Chr(9) + "" + Chr(9) + CStr(grdData.Columns(9).Value)
    grdData.Refresh
    
    dAmt = 0
    grdData.MoveFirst
    For iRec = 0 To grdData.Rows - 1
        dAmt = grdData.Columns(6).Value + dAmt
        grdData.MoveNext
    Next iRec

    lblTotAmt = Format(dAmt, "00.00")
    StatusBar1.SimpleText = grdData.Rows & "  Records"
    
End Sub

Private Sub Form_Load()

    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    grdData.RowHeight = 280
    dtpDate = Format(Now, "MM/DD/YYYY")
    
End Sub
Private Sub grdData_AfterColUpdate(ByVal ColIndex As Integer)
    
    If Trim(grdData.Columns(ColIndex).Value) = "" Then Exit Sub
    
    Select Case ColIndex
        Case 0   ' VESSEL
            If Not IsNumeric(grdData.Columns(ColIndex).Value) Then
                MsgBox "Invalid LR NUM", vbInformation, "VESSEL"
                grdData.Columns(ColIndex).Value = ""
                Exit Sub
            End If
            
            Sqlstmt = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM ='" & Trim(grdData.Columns(0).Value) & "'"
            Set dsVESSEL_PROFILE = OraDatabase.CreateDynaset(Sqlstmt, 0&)
            If dsVESSEL_PROFILE.RECORDCOUNT = 0 Then
                MsgBox "Invalid LR NUMBER", vbInformation, "VESSEL"
                grdData.Columns(0).Value = ""
            End If
        Case 1
            If Not IsNumeric(grdData.Columns(ColIndex).Value) Then
                MsgBox "Invalid CUSTOMER ID", vbInformation, "CUSTOMER"
                grdData.Columns(ColIndex).Value = ""
                Exit Sub
            End If
            
            Sqlstmt = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID ='" & Trim(grdData.Columns(1).Value) & "'"
            Set dsCUSTOMER_PROFILE = OraDatabase.CreateDynaset(Sqlstmt, 0&)
            If dsCUSTOMER_PROFILE.RECORDCOUNT = 0 Then
                MsgBox "Invalid CUSTOMER", vbInformation, "CUSTOMER"
                grdData.Columns(1).Value = ""
            End If
        Case 2
            
            If Not IsNumeric(grdData.Columns(ColIndex).Value) Then
                MsgBox "Invalid COMMODITY CODE", vbInformation, "COMMODITY"
                grdData.Columns(ColIndex).Value = ""
                Exit Sub
            End If
            
            Sqlstmt = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE ='" & Trim(grdData.Columns(2).Value) & "'"
            Set dsCOMMODITY_PROFILE = OraDatabase.CreateDynaset(Sqlstmt, 0&)
            If dsCOMMODITY_PROFILE.RECORDCOUNT = 0 Then
                MsgBox "Invalid COMMODITY", vbInformation, "COMMODITY"
                grdData.Columns(2).Value = ""
            End If
        Case 3
            
            If Not IsNumeric(grdData.Columns(ColIndex).Value) Then
                MsgBox "Invalid SERVICE CODE", vbInformation, "SERVICE"
                grdData.Columns(ColIndex).Value = ""
                Exit Sub
            End If
            
            Sqlstmt = "SELECT SERVICE_NAME FROM SERVICE_CATEGORY WHERE SERVICE_CODE ='" & Trim(grdData.Columns(3).Value) & "'"
            Set dsSERVICE_CATEGORY = OraDatabase.CreateDynaset(Sqlstmt, 0&)
            If dsSERVICE_CATEGORY.RECORDCOUNT = 0 Then
                MsgBox "Invalid SERVICE", vbInformation, "SERVICE"
                grdData.Columns(3).Value = ""
            End If
        Case 5
             If Not Len(grdData.Columns(5).Value) > 100 Then
                MsgBox "Description cannot be more then 100 characters long ", vbInformation, "DESCRIPTION"
                grdData.Columns(5).Value = Left(grdData.Columns(4).Value, 100)
            End If
        Case 6
            If Not IsNumeric(grdData.Columns(6).Value) Then
                MsgBox "Invalid Amount", vbInformation, "AMOUNT"
                grdData.Columns(6).Value = ""
            End If
        Case 7
            If Not IsNumeric(grdData.Columns(7).Value) Then
                MsgBox "Invalid Page No.", vbInformation, "Page No."
                grdData.Columns(7).Value = ""
            End If
    End Select
    
End Sub
Private Sub grdData_RowColChange(ByVal LastRow As Variant, ByVal LastCol As Integer)

    Dim dAmount As Double
    
        If LastCol = 6 Then
            grdData.MoveFirst

            For iRec = 0 To grdData.Rows - 1
                dAmount = grdData.Columns(6).Value + dAmount
                grdData.MoveNext
            Next iRec

            lblTotAmt = Format(dAmount, "00.00")
        End If
        
End Sub

