VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.1#0"; "MSCOMCTL.OCX"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Begin VB.Form frmLeaseInv 
   Caption         =   "LEASE/RENT INVOICES"
   ClientHeight    =   9600
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   14880
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
   ScaleHeight     =   9600
   ScaleWidth      =   14880
   StartUpPosition =   3  'Windows Default
   Begin SSDataWidgets_B.SSDBGrid grdLease 
      Height          =   7695
      Left            =   53
      TabIndex        =   8
      Top             =   960
      Width           =   14775
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   14
      ForeColorEven   =   8388608
      RowHeight       =   503
      Columns.Count   =   14
      Columns(0).Width=   1429
      Columns(0).Caption=   "CUST"
      Columns(0).Name =   "CUST"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1217
      Columns(1).Caption=   "SERV"
      Columns(1).Name =   "SERV"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1561
      Columns(2).Caption=   "COMM"
      Columns(2).Name =   "COMM"
      Columns(2).Alignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1244
      Columns(3).Caption=   "ASSET"
      Columns(3).Name =   "ASSET"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1508
      Columns(4).Caption=   "RATE"
      Columns(4).Name =   "RATE"
      Columns(4).Alignment=   1
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1614
      Columns(5).Caption=   "QTY"
      Columns(5).Name =   "QTY"
      Columns(5).Alignment=   1
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1402
      Columns(6).Caption=   "UNIT"
      Columns(6).Name =   "UNIT"
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1984
      Columns(7).Caption=   "AMT"
      Columns(7).Name =   "AMT"
      Columns(7).Alignment=   1
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   5583
      Columns(8).Caption=   "DESC"
      Columns(8).Name =   "DESC"
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1482
      Columns(9).Caption=   "PERIOD"
      Columns(9).Name =   "PERIOD"
      Columns(9).Alignment=   2
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   3200
      Columns(10).Visible=   0   'False
      Columns(10).Caption=   "LEASE ID"
      Columns(10).Name=   "LEASE ID"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   2037
      Columns(11).Caption=   "START DT"
      Columns(11).Name=   "START DT"
      Columns(11).Alignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   1879
      Columns(12).Caption=   "END DT"
      Columns(12).Name=   "END DT"
      Columns(12).Alignment=   2
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   2381
      Columns(13).Caption=   "BILL TO"
      Columns(13).Name=   "BILLED UPTO"
      Columns(13).Alignment=   2
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      _ExtentX        =   26061
      _ExtentY        =   13573
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
   Begin MSComctlLib.StatusBar StatusBar1 
      Align           =   2  'Align Bottom
      Height          =   330
      Left            =   0
      TabIndex        =   7
      Top             =   9270
      Width           =   14880
      _ExtentX        =   26247
      _ExtentY        =   582
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
         EndProperty
      EndProperty
   End
   Begin Crystal.CrystalReport Crw1 
      Left            =   960
      Top             =   9000
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin VB.CommandButton cmdRePrint 
      Caption         =   "REPRINT"
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
      Left            =   5912
      TabIndex        =   6
      Top             =   8760
      Width           =   1215
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
      Left            =   7913
      TabIndex        =   5
      Top             =   360
      Width           =   2655
   End
   Begin MSComCtl2.DTPicker dtpCutOff 
      Height          =   330
      Left            =   6120
      TabIndex        =   4
      Top             =   375
      Width           =   1215
      _ExtentX        =   2143
      _ExtentY        =   582
      _Version        =   393216
      CustomFormat    =   "MM/dd/yyyy"
      Format          =   16646147
      CurrentDate     =   36923
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
      Height          =   375
      Left            =   9712
      TabIndex        =   2
      Top             =   8760
      Width           =   1215
   End
   Begin VB.CommandButton cmdClear 
      Caption         =   "CLEAR"
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
      Left            =   7811
      TabIndex        =   1
      Top             =   8760
      Width           =   1215
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "GENERATE"
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
      Left            =   4013
      TabIndex        =   0
      Top             =   8760
      Width           =   1215
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "CUT OFF DATE"
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
      Left            =   4320
      TabIndex        =   3
      Top             =   435
      Width           =   1320
   End
End
Attribute VB_Name = "frmLeaseInv"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim SqlStmt As String
Dim iRec As Integer
Dim sPeriod As String
Dim sActive As String
Dim iROW As Long
Dim iNum As Integer
Dim sInvNum As String
Dim sInvDT As String
Dim sCityStateZip As String
Dim bStart As Boolean
Dim iCustId  As Integer
Dim dTotalAmount  As Double
Dim dGrandTotal As Double
Dim lInvoiceNum As Long
Dim iline As Integer
Dim i As Integer
Private Sub cmdClear_Click()

    grdLease.RemoveAll
    StatusBar1.SimpleText = ""
    
End Sub
Private Sub cmdExit_Click()

    Unload Me
    
End Sub
Private Sub cmdRePrint_Click()

    Unload Me
    frmRePrint.Show
    
End Sub
Private Sub cmdRet_Click()

    StatusBar1.SimpleText = ""
    Call FillGrd
    
End Sub
Private Sub cmdSave_Click()

    Dim LastBillDt As Date
    Dim dsLEASE_TEMP As Object
    Dim dPeriod  As Double
    
    If grdLease.Rows = 0 Then Exit Sub
    
    StatusBar1.SimpleText = "Printing PreBill"
    Call PrintPreBill
    
    If MsgBox("Are you sure to generate lease Invoices ? ", vbQuestion + vbYesNo, "LEASE INVOICES") = vbNo Then
        Exit Sub
        StatusBar1.SimpleText = ""
    End If
    
    OraSession.BeginTrans
    
    StatusBar1.SimpleText = "Generating Invoices ..."
    
    SqlStmt = "SELECT * FROM BILLING"
    Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        
    grdLease.MoveFirst
      
    For iRec = 0 To grdLease.Rows - 1
        
        SqlStmt = " SELECT * FROM LEASE_HISTORY WHERE LEASE_ID='" & grdLease.Columns(10).Value & "'"
        Set dsLEASE_HISTORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsLEASE_HISTORY.recordcount > 0 Then
            If Not IsNull(dsLEASE_HISTORY.fields("BILLED_TO_DATE").Value) Then
                LastBillDt = Format(DateAdd("d", 1, dsLEASE_HISTORY.fields("BILLED_TO_DATE").Value), "MM/DD/YYYY")
            Else
                LastBillDt = Format(dsLEASE_HISTORY.fields("LEASE_START_DATE").Value, "MM/DD/YYYY")
            End If

        End If
        
        With dsBILLING
            .AddNew
            .fields("BILLING_TYPE").Value = "LEASE"
            .fields("BILLING_NUM").Value = fnMaxBillNum
            .fields("CUSTOMER_ID").Value = grdLease.Columns(0).Value
            .fields("SERVICE_CODE").Value = grdLease.Columns(1).Value
            .fields("COMMODITY_CODE").Value = grdLease.Columns(2).Value
            .fields("SERVICE_RATE").Value = grdLease.Columns(4).Value
            .fields("SERVICE_QTY").Value = grdLease.Columns(5).Value
            .fields("SERVICE_UNIT").Value = grdLease.Columns(6).Value
            .fields("SERVICE_AMOUNT").Value = grdLease.Columns(7).Value
            .fields("SERVICE_DESCRIPTION").Value = grdLease.Columns(8).Value
            .fields("PAGE_NUM").Value = grdLease.Columns(10).Value  'TO STORE LEASE_ID
            .fields("SERVICE_START").Value = LastBillDt
            .fields("SERVICE_DATE").Value = LastBillDt   ' Oops- someone forgot to add a service date
                                                         ' which conflicts with the Credit memo screen! - STM
            .fields("SERVICE_STOP").Value = grdLease.Columns(13).Value
            '.fields("TOSOLOMON").Value = grdLease.Columns().Value
            .fields("LR_NUM").Value = -1
            'Added field below for solomon import purposes.  06.05.2001 LJG
            .fields("ASSET_CODE").Value = grdLease.Columns(3).Value
            .Update
        End With
        
        With dsLEASE_HISTORY
            .EDIT
            .fields("BILLED_TO_DATE").Value = grdLease.Columns(13).Value
            .Update
        End With
        
        grdLease.MoveNext
    Next iRec
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.commitTrans
    Else
        OraSession.RollBack
        MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
        OraDatabase.LastServerErrReset
        Exit Sub
    End If
        
    Call PrintInvoices
    End If
    StatusBar1.SimpleText = ""
    
End Sub
Sub PrintPreBill()

    Dim dAmt As Double

    If grdLease.Rows = 0 Then Exit Sub
    
    Printer.Orientation = 2
    Printer.Print ""
    Printer.Print Tab(5); "Printed on:"; Tab(20); Date
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 12
    Printer.Print Tab(65); "LEASE PREBILL"
    Printer.FontSize = 8
    Printer.Print ""
    Printer.Print Tab(5); "CUTOFF DATE  : " & Format(dtpCutOff.Value, "mm/dd/yyyy")
    Printer.Print ""
    Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
     Printer.Print ; Tab(5); "CUSTOMER"; Tab(25); "SERVICE"; Tab(40); "COMMODITY"; Tab(60); "ASSET"; _
                     Tab(70); "RATE"; Tab(90); "QTY"; Tab(105); "UNIT"; Tab(120); "AMT"; Tab(135); "BILLED UPTO"; _
                     Tab(155); "DESC" '"START DATE"; Tab(210); "END DATE"
    Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
    
    With grdLease
        .MoveFirst
    
        For iRec = 0 To .Rows - 1
            
            Printer.Print Tab(5); Trim(.Columns(0).Value); Tab(25); .Columns(1).Value; Tab(40); .Columns(2).Value; _
                          Tab(60); .Columns(3).Value; Tab(70); Trim(.Columns(4).Value); _
                          Tab(90); Trim(.Columns(5).Value); Tab(105); Trim(.Columns(6).Value); _
                          Tab(120); Trim(.Columns(7).Value); Tab(135); Trim(.Columns(13).Value); _
                          Tab(155); Trim(.Columns(8).Value) 'Tab(190); Trim(.Columns(12).Value) _
                          ; Tab(210); Trim(.Columns(13).Value)
                          
            dAmt = dAmt + Val(.Columns(7).Value)
            .MoveNext
            
        Next iRec
        
        Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
        Printer.Print Tab(5); "TOTAL  :"; Tab(15); grdLease.Rows; Tab(110); dAmt
        Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
        .MoveFirst
    End With
    
    Printer.EndDoc
    
End Sub
Private Sub Form_Load()
   
   StartInvNum = 0
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    dtpCutOff.Value = Format(Now, "MM/DD/YYYY")
    
End Sub
Sub FillGrd()

    Dim dsMaxRunDate As Object
    Dim sMaxRunMonth As String
    Dim sMonth As String
    Dim iLeaseId As Integer
    Dim dsLEASE_HISTORY As Object
    Dim sNow As String
    Dim sAmt As String
    Dim sBilledDt As String
    Dim sStartDt As String
    Dim sFrom As String
    Dim iFromMonth As Integer
    Dim iFromDay As Integer
    Dim sTo As String
    Dim iToMonth As Integer
    Dim iToDay As Integer
    Dim iPos As Integer
    Dim dTotalAmt As Double
    Dim bCheck As Boolean
    Dim sTemp As String
    
    grdLease.RemoveAll
    
    sNow = Format(Now, "MM/DD/YYYY")
    OraSession.BeginTrans
    
    'Records which does not have billing_from  &  Billing_to fields enteries
    'Added the checking of null for BILLED_TO_DATE, lease_start_date <= instead of <  -- LFW, 2/10/04
    SqlStmt = " SELECT * FROM LEASE_RATE LR,LEASE_HISTORY LH" _
            & " WHERE ACTIVE_STATUS='Y' AND LR.LEASE_ID=LH.LEASE_ID " _
            & " AND LEASE_START_DATE IS NOT NULL AND LEASE_END_DATE IS NOT NULL " _
            & " AND LEASE_START_DATE <= TO_DATE('" & Format(dtpCutOff.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND LEASE_END_DATE >= TO_DATE('" & Format(dtpCutOff.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND (BILLED_TO_DATE IS NULL OR BILLED_TO_DATE <= TO_DATE('" & Format(dtpCutOff.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')) " _
            & " ORDER BY CUSTOMER_ID, LR.LEASE_ID"
    
    Set dsLEASE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsLEASE.recordcount > 0 Then

        For iRec = 1 To dsLEASE.recordcount
            
            '****************************************************************************************
            bCheck = False
            
            If Not IsNull(dsLEASE.fields("BILLING_FROM").Value) And Not IsNull(dsLEASE.fields("BILLING_TO").Value) Then
                 
                iPos = 0
           
                sFrom = dsLEASE.fields("BILLING_FROM").Value
                sTo = dsLEASE.fields("BILLING_TO").Value
            
                iPos = InStr(1, sFrom, "/")
                If iPos <> 0 Then
                    iFromMonth = Val(Mid(sFrom, 1, iPos - 1))
                    iFromDay = Val(Mid(sFrom, iPos + 1))
                End If
            
                iPos = InStr(1, sTo, "/")
                If iPos <> 0 Then
                    iToMonth = Val(Mid(sTo, 1, iPos - 1))
                    iToDay = Val(Mid(sTo, iPos + 1))
                End If
                
                If Month(dsLEASE.fields("BILLED_TO_DATE").Value) = iToMonth Then
                    
                    If iFromMonth = dtpCutOff.Month And iFromDay < 15 Then
                                    
                        bCheck = True
                        
                        sAmt = Round(dsLEASE.fields("RATE").Value * dsLEASE.fields("QTY").Value, 2)
                        dTotalAmt = dTotalAmt + Round(dsLEASE.fields("RATE").Value * dsLEASE.fields("QTY").Value, 2)
                        
                        'sBilledDt = DateCalculation(dsLEASE.fields("PERIOD").Value, Format(iFromMonth & "/" & iFromDay & "/" & Format(Now, "yyyy")))
                        sBilledDt = DateAdd("M", dsLEASE.fields("PERIOD").Value, Format(iFromMonth & "/" & iFromDay & "/" & Format(Now, "yyyy")))
                        
                    ElseIf iFromMonth = Month(DateAdd("m", -1, dtpCutOff.Value)) And iFromDay >= 15 Then
                        bCheck = True
                        sAmt = Round(dsLEASE.fields("RATE").Value * dsLEASE.fields("QTY").Value, 2)
                        dTotalAmt = dTotalAmt + Round(dsLEASE.fields("RATE").Value * dsLEASE.fields("QTY").Value, 2)
                        
                        'sBilledDt = DateCalculation(dsLEASE.fields("PERIOD").Value, Format(iFromMonth & "/" & iFromDay & "/" & Format(Now, "yyyy")))
                        sBilledDt = DateAdd("M", dsLEASE.fields("PERIOD").Value, Format(iFromMonth & "/" & iFromDay & "/" & Format(Now, "yyyy")))
                        
                    End If
                    
                    If bCheck = True Then
                        sTemp = Format(iFromMonth & "/" & iFromDay & "/" & Format(Now, "yyyy"))
                    
                        SqlStmt = " UPDATE LEASE_HISTORY SET BILLED_TO_DATE= to_date('" & Format(sTemp, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                                & " WHERE LEASE_ID='" & dsLEASE.fields("LEASE_ID").Value & "'"
                    
                        OraDatabase.ExecuteSQL (SqlStmt)
                    End If
                    
                ElseIf Month(dsLEASE.fields("BILLED_TO_DATE").Value) = dtpCutOff.Month And iFromDay < 15 Then
                    bCheck = True
                    sAmt = Round(dsLEASE.fields("RATE").Value * dsLEASE.fields("QTY").Value, 2)
                    dTotalAmt = dTotalAmt + Round(dsLEASE.fields("RATE").Value * dsLEASE.fields("QTY").Value, 2)
                    
                    'sBilledDt = DateCalculation(dsLEASE.fields("PERIOD").Value, dsLEASE.fields("BILLED_TO_DATE").Value)
                    sBilledDt = DateAdd("M", dsLEASE.fields("PERIOD").Value, dsLEASE.fields("BILLED_TO_DATE").Value)
                    
                ElseIf Month(dsLEASE.fields("BILLED_TO_DATE").Value) = Month(DateAdd("m", -1, dtpCutOff.Value)) And iFromDay >= 15 Then
                    bCheck = True
                    sAmt = Round(dsLEASE.fields("RATE").Value * dsLEASE.fields("QTY").Value, 2)
                    dTotalAmt = dTotalAmt + Round(dsLEASE.fields("RATE").Value * dsLEASE.fields("QTY").Value, 2)
                    
                    'sBilledDt = DateCalculation(dsLEASE.fields("PERIOD").Value, dsLEASE.fields("BILLED_TO_DATE").Value)
                          sBilledDt = DateAdd("M", dsLEASE.fields("PERIOD").Value, dsLEASE.fields("BILLED_TO_DATE").Value)
                    
                End If
                
                If bCheck = True Then
                    grdLease.AddItem Trim("" & dsLEASE.fields("CUSTOMER_ID").Value) + Chr(9) + _
                                     Trim("" & dsLEASE.fields("SERVICE_CODE").Value) + Chr(9) + _
                                     Trim("" & dsLEASE.fields("COMMODITY_CODE").Value) + Chr(9) + _
                                     Trim("" & dsLEASE.fields("ASSET").Value) + Chr(9) + _
                                     Trim("" & dsLEASE.fields("RATE").Value) + Chr(9) + _
                                     Trim("" & dsLEASE.fields("QTY").Value) + Chr(9) + _
                                     Trim("" & dsLEASE.fields("UNIT").Value) + Chr(9) + sAmt + Chr(9) + _
                                     Trim("" & dsLEASE.fields("DESCRIPTION").Value) + Chr(9) + _
                                     CStr(dsLEASE.fields("PERIOD").Value) + Chr(9) + _
                                     dsLEASE.fields("LEASE_ID").Value + Chr(9) + _
                                     Format(dsLEASE.fields("LEASE_START_DATE").Value, "MM/DD/YYYY") + Chr(9) + _
                                     Format(dsLEASE.fields("LEASE_END_DATE").Value, "MM/DD/YYYY") + Chr(9) + _
                                     Format(sBilledDt, "MM/DD/YYYY")
                    grdLease.Refresh
                End If
                
            Else
            '*****************************************************************************************
                sAmt = Round(dsLEASE.fields("RATE").Value * dsLEASE.fields("QTY").Value, 2)
                dTotalAmt = dTotalAmt + Round(dsLEASE.fields("RATE").Value * dsLEASE.fields("QTY").Value, 2)
                
                'Bypass the DateCalculation function to not to add one day for the months that don't have 31 days
                'Also substract one day when billed_to_date is null  -- LFW, 2/11/04
                If IsNull(dsLEASE.fields("BILLED_TO_DATE").Value) Then
                    'sBilledDt = DateCalculation(dsLEASE.fields("PERIOD").Value, dsLEASE.fields("LEASE_START_DATE").Value)
                    sBilledDt = DateAdd("d", -1, DateAdd("M", dsLEASE.fields("PERIOD").Value, dsLEASE.fields("LEASE_START_DATE").Value))
                Else
                    'sBilledDt = DateCalculation(dsLEASE.fields("PERIOD").Value, dsLEASE.fields("BILLED_TO_DATE").Value)
                    sBilledDt = DateAdd("M", dsLEASE.fields("PERIOD").Value, dsLEASE.fields("BILLED_TO_DATE").Value)
                End If
                
                grdLease.AddItem Trim("" & dsLEASE.fields("CUSTOMER_ID").Value) + Chr(9) + _
                            Trim("" & dsLEASE.fields("SERVICE_CODE").Value) + Chr(9) + _
                            Trim("" & dsLEASE.fields("COMMODITY_CODE").Value) + Chr(9) + _
                            Trim("" & dsLEASE.fields("ASSET").Value) + Chr(9) + _
                            Trim("" & dsLEASE.fields("RATE").Value) + Chr(9) + _
                            Trim("" & dsLEASE.fields("QTY").Value) + Chr(9) + _
                            Trim("" & dsLEASE.fields("UNIT").Value) + Chr(9) + sAmt + Chr(9) + _
                            Trim("" & dsLEASE.fields("DESCRIPTION").Value) + Chr(9) + _
                            CStr(dsLEASE.fields("PERIOD").Value) + Chr(9) + _
                            dsLEASE.fields("LEASE_ID").Value + Chr(9) + _
                            Format(dsLEASE.fields("LEASE_START_DATE").Value, "MM/DD/YYYY") + Chr(9) + _
                            Format(dsLEASE.fields("LEASE_END_DATE").Value, "MM/DD/YYYY") + Chr(9) + _
                            Format(sBilledDt, "MM/DD/YYYY")
                                    
                grdLease.Refresh
            End If
            dsLEASE.MoveNext
        Next iRec
    End If
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.commitTrans
        StatusBar1.SimpleText = grdLease.Rows & " Records and Invoiced Amount : " & dTotalAmt
    Else
        MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
        OraSession.RollBack
        OraDatabase.LastServerErrReset
    End If

End Sub
Private Sub grdLease_KeyPress(KeyAscii As Integer)
'     KeyAscii = 0
End Sub
Sub PreInv_AddNew(RowNum As Long, Row_Text As String, eof As Integer, Amt As Double)
    
    With dsPreInv
        .AddNew
        .fields("Row_Num").Value = RowNum
        .fields("Text").Value = Row_Text
        .fields("eof").Value = eof
        .fields("AMT").Value = Amt
        .Update
    End With
    
End Sub
Sub NEW_PAGE()

    Dim iline As Integer
       
    iNum = 0
    iROW = iROW + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iROW, "", 1, 0)
        
    iROW = iROW + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iROW, Space(227) & sInvNum, 0, 0)
       
    iROW = iROW + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iROW, "", 0, 0)
    
    iline = iline + 1
    iROW = iROW + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iROW, Space(227) & sInvDT, 0, 0)
    
    For iline = 1 To 33
        iline = iline + 1
        iROW = iROW + 1
        iNum = iNum + 1
        Call PreInv_AddNew(iROW, "", 0, 0)
    Next iline
    
End Sub
Sub PrintInvoices()

    Dim iCustId As Integer
    Dim iSerCode As Integer
    
    Dim iPos1 As Integer
    Dim iPos2 As Integer
    Dim Duration As String
    Dim dsSTORAGE_RATE As Object
    
    iNum = 0
    bStart = True
    iCustId = 0
    dTotalAmount = 0
    dGrandTotal = 0
    lInvoiceNum = 0
    iRec = 0
    iline = 0
    
    StatusBar1.SimpleText = ""
    
    OraSession.BeginTrans
    
    SqlStmt = " SELECT * FROM BILLING WHERE INVOICE_NUM IS NULL " _
            & " AND BILLING_TYPE='LEASE'  " _
            & " ORDER BY CUSTOMER_ID, SERVICE_CODE,SERVICE_START"
              
    Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBILLING.recordcount > 0 Then
        
        Call SubPreInv
        
        For iRec = 1 To dsBILLING.recordcount
            
            DoEvents
            
            'Get from Customer table based on Customer Code
            SqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID =" _
                      & "'" & dsBILLING.fields("CUSTOMER_ID").Value & "'"
            Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
            
            'Check to see if we need to change the invoice number and headings on the page
            If (iCustId <> dsBILLING.fields("CUSTOMER_ID").Value) Or _
               (iSerCode <> dsBILLING.fields("SERVICE_CODE").Value) Then
            
                If bStart = False Then
                    For iline = 1 To 2
                        If iNum = 54 Then Call NEW_PAGE
                        iNum = iNum + 1
                        iROW = iROW + 1
                        Call PreInv_AddNew(iROW, "", 0, 0)
                    Next iline
                    
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(5) & "-------------------------------------------" _
                    & "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
                                     
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(140) & "INVOICE TOTAL : ", 0, Format(Round(dTotalAmount, 3), "##,###,###,##0.00"))
                   
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(5) & "-------------------------------------------" _
                    & "------------------------------------------------------------------------------------------------------------------------------------------------------", 3, 0)
                End If
                
                bStart = False
                
                dGrandTotal = dGrandTotal + dTotalAmount
                dTotalAmount = 0
                
                iCustId = dsBILLING.fields("CUSTOMER_ID").Value
                iSerCode = dsBILLING.fields("SERVICE_CODE").Value
                lInvoiceNum = fnMaxInvNum
                sInvNum = CStr(lInvoiceNum)
                
                'StatusBar1.SimpleText = "PROCESSING LEASE INVOICE NUMBER : " & lInvoiceNum
                               
                iNum = 0
                iNum = iNum + 1
                iROW = iROW + 1
                Call PreInv_AddNew(iROW, Space(227) & CStr(lInvoiceNum), 1, 0)
                
                If iNum = 54 Then Call NEW_PAGE
                iNum = iNum + 1
                iROW = iROW + 1
                Call PreInv_AddNew(iROW, "", 0, 0)
                
                If iNum = 54 Then Call NEW_PAGE
                iNum = iNum + 1
                iROW = iROW + 1
                Call PreInv_AddNew(iROW, Space(227) & Format(Now, "MM/DD/YYYY"), 0, 0)
                sInvDT = Format(Now, "MM/DD/YYYY")
                
                For iline = 1 To 7
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, "", 0, 0)
                Next iline
                    
                If Not IsNull(dsBILLING.fields("CARE_OF")) Then
                    If (dsBILLING.fields("CARE_OF").Value = "1") Or (dsBILLING.fields("CARE_OF").Value = "Y") Then
                        If iNum = 54 Then Call NEW_PAGE
                        iNum = iNum + 1
                        iROW = iROW + 1
                        Call PreInv_AddNew(iROW, Space(34) & fnVesselName(dsBILLING.fields("LR_NUM").Value), 0, 0)
                        
                        If iNum = 54 Then Call NEW_PAGE
                        iNum = iNum + 1
                        iROW = iROW + 1
                        Call PreInv_AddNew(iROW, Space(34) & "C/O " & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
                    Else
                        If iNum = 54 Then Call NEW_PAGE
                        iNum = iNum + 1
                        iROW = iROW + 1
                        Call PreInv_AddNew(iROW, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
                    End If
                Else
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
                End If
                
                
                If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value) Then
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value, 0, 0)
                End If
                
                If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) Then
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value, 0, 0)
                End If
                
                If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value) Then
                    sCityStateZip = dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value
                End If
                If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value) Then
                    sCityStateZip = sCityStateZip & ", " & dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value
                End If
                If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value) Then
                    sCityStateZip = sCityStateZip & " - " & dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value
                End If
                If iNum = 54 Then Call NEW_PAGE
                iNum = iNum + 1
                iROW = iROW + 1
                Call PreInv_AddNew(iROW, Space(34) & sCityStateZip, 0, 0)
                
                If dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value <> "US" Then
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(34) & fnCountryName(dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value), 0, 0)
                End If
            
                For iline = 1 To 8
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, "", 0, 0)
                Next iline
                    
                dsBILLING.EDIT
                dsBILLING.fields("INVOICE_NUM").Value = lInvoiceNum
                dsBILLING.fields("SERVICE_STATUS").Value = "INVOICED"
                dsBILLING.fields("INVOICE_DATE").Value = Format(Now, "MM/DD/YYYY")
                dsBILLING.Update
            
                If iNum = 54 Then Call NEW_PAGE
                iNum = iNum + 1
                iROW = iROW + 1
                
                Call PreInv_AddNew(iROW, Space(8) & dsBILLING.fields("SERVICE_START").Value & Space(15) _
                                   & dsBILLING.fields("SERVICE_DESCRIPTION").Value & "   " & dsBILLING.fields("SERVICE_QTY").Value & " " & dsBILLING.fields("SERVICE_UNIT").Value _
                                   & "@  $" & dsBILLING.fields("SERVICE_RATE").Value & " " & dsBILLING.fields("SERVICE_UNIT").Value _
                                   & "  For Period : " & dsBILLING.fields("SERVICE_START").Value & " - " & dsBILLING.fields("SERVICE_STOP").Value, 0, Format(dsBILLING.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
                             
'                If iNum = 54 Then Call NEW_PAGE
'                iNum = iNum + 1
'                iROW = iROW + 1
'
'                Call PreInv_AddNew(iROW, Space(22) & "For Period : " & dsBILLING.fields("SERVICE_START").Value & " - " & dsBILLING.fields("SERVICE_STOP").Value, 0, Format(dsBILLING.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
            
                dTotalAmount = dTotalAmount + dsBILLING.fields("service_amount").Value
            Else
                DoEvents
                
                dTotalAmount = dTotalAmount + dsBILLING.fields("SERVICE_AMOUNT").Value
                
                dsBILLING.EDIT
                dsBILLING.fields("INVOICE_NUM").Value = lInvoiceNum
                dsBILLING.fields("SERVICE_STATUS").Value = "INVOICED"
                dsBILLING.fields("INVOICE_DATE").Value = Format(Now, "MM/DD/YYYY")
                dsBILLING.Update
            
                If iNum = 54 Then Call NEW_PAGE
                iNum = iNum + 1
                iROW = iROW + 1
                
                Call PreInv_AddNew(iROW, Space(8) & dsBILLING.fields("SERVICE_START").Value & Space(15) _
                                   & dsBILLING.fields("SERVICE_DESCRIPTION").Value & "   " & dsBILLING.fields("SERVICE_QTY").Value & " " & dsBILLING.fields("SERVICE_UNIT").Value _
                                   & "@  $" & dsBILLING.fields("SERVICE_RATE").Value & "$ " & dsBILLING.fields("SERVICE_UNIT").Value _
                                   & "  For Period : " & dsBILLING.fields("SERVICE_START").Value & " - " & dsBILLING.fields("SERVICE_STOP").Value, 0, Format(dsBILLING.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
                             
'                If iNum = 54 Then Call NEW_PAGE
'                iNum = iNum + 1
'                iROW = iROW + 1
'
'                Call PreInv_AddNew(iROW, Space(22) & "FOR : " & dsBILLING.fields("SERVICE_START").Value & " - " & dsBILLING.fields("SERVICE_STOP").Value, 0, Format(dsBILLING.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
            End If
            dsBILLING.MoveNext
        Next iRec
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox "Error " & OraDatabase.LastServerErrText & " occured." & vbCrLf & _
                    " Not able to print LEASE INVOICES !", vbExclamation
            OraSession.RollBack
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
            
        MsgBox "No Records Found For LEASE INVOICES !", vbInformation + vbExclamation
        
        OraSession.RollBack
        
        Exit Sub
    End If
    
    For iline = 1 To 2
        If iNum = 54 Then Call NEW_PAGE
        iNum = iNum + 1
        iROW = iROW + 1
        Call PreInv_AddNew(iROW, "", 0, 0)
    Next iline
    
    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iROW = iROW + 1
    Call PreInv_AddNew(iROW, Space(5) & "-------------------------------------------" _
    & "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
                                    
    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iROW = iROW + 1
    Call PreInv_AddNew(iROW, Space(140) & "INVOICE TOTAL : ", 0, Format(Round(dTotalAmount, 3), "##,###,###,##0.00"))
                   
    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iROW = iROW + 1
    Call PreInv_AddNew(iROW, Space(5) & "-------------------------------------------" _
    & "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
    
    dGrandTotal = dGrandTotal + dTotalAmount
    
    iROW = iROW + 1
    Call PreInv_AddNew(iROW, "", 2, 0)
    For i = 1 To 34
        iROW = iROW + 1
        Call PreInv_AddNew(iROW, "", 0, 0)
    Next i
    
    iROW = iROW + 1
    Call PreInv_AddNew(iROW, Space(45) & "GRAND TOTAL OF LEASE INVOICES FOR THE DATE  " & Format(Now, "MM/DD/YYYY") & "  :  " & Format(Round(dGrandTotal, 3), "##,###,###,##0.00"), 0, 0)
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.commitTrans
    Else
        MsgBox OraDatabase.LastServerErrText, vbCritical, "MASTER INVOICE"
        OraSession.RollBack
        OraDatabase.LastServerErrReset
    End If
    
'    Crw1.ReportFileName = App.Path & "\BNIINV.rpt"
'    Crw1.Connect = "DSN = BNI1;UID = sag_owner;PWD = sag"
'    Crw1.Connect = "DSN = BNITEST;UID = sag_owner;PWD = BNITEST238"
'    Crw1.Action = 1
'    If Crw1.LastErrorNumber <> 0 Then MsgBox Crw1.LastErrorString
    
End Sub
