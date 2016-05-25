VERSION 5.00
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "CRYSTL32.OCX"
Begin VB.Form frmRePrint 
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "REPRINT INVOICES"
   ClientHeight    =   2970
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   5325
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   2970
   ScaleWidth      =   5325
   StartUpPosition =   3  'Windows Default
   Begin Crystal.CrystalReport crw1 
      Left            =   240
      Top             =   1680
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "&EXIT"
      Height          =   375
      Left            =   2895
      TabIndex        =   8
      Top             =   1800
      Width           =   1095
   End
   Begin VB.TextBox txtTo 
      Height          =   285
      Left            =   3375
      TabIndex        =   6
      Top             =   1080
      Width           =   1455
   End
   Begin VB.TextBox txtFrom 
      Height          =   285
      Left            =   1215
      TabIndex        =   5
      Top             =   1080
      Width           =   1335
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&PRINT"
      Height          =   375
      Left            =   1320
      TabIndex        =   3
      Top             =   1800
      Width           =   1095
   End
   Begin VB.Label lblNull 
      Height          =   255
      Left            =   3600
      TabIndex        =   7
      Top             =   1800
      Visible         =   0   'False
      Width           =   615
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   300
      Left            =   0
      TabIndex        =   4
      Top             =   2640
      Width           =   5295
   End
   Begin VB.Label Label3 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "TO  :"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   195
      Left            =   2895
      TabIndex        =   2
      Top             =   1125
      Width           =   450
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "FROM  :"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   195
      Left            =   495
      TabIndex        =   1
      Top             =   1125
      Width           =   720
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "INVOICE NUMBER  "
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   195
      Left            =   1792
      TabIndex        =   0
      Top             =   600
      Width           =   1740
   End
End
Attribute VB_Name = "frmRePrint"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
'   * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
'   * Module Name    : Invoice Reprint                              *
'   * Author         : PKA                                          *
'   * Date           : 023/03/2000                                  *
'   * Description    : Module for Reprinting the Storage Invoices   *
'   * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

Option Explicit
Dim iRow As Long
Dim sText As String
Dim lInvoiceNum As Long
Dim iPrintFileNum As Integer
Dim i As Integer
Dim sVessel As String
Dim iCount  As Integer
Dim sInvDt As String
Dim sInvNum As String

'Function for detail invoice listing
Sub Det_Billing(iSumBillNum As Long, iCustId As Integer, sArrNum As String, iInvoice_Num As Long, IST As String, dtDate As Date)
    Dim sFromStr As String
    Dim dInvoiceTotal As Double
    Dim sSerQty As String
    Dim iRec As Integer
    Dim dsBilling1 As Object
    Dim sqlstmt As String
    '-------Pawan
    Dim sqlstmt1 As String
    Dim dsCargo_dis As Object
    Dim cargo_dis As String
    Dim cargo_dis1 As String
    Dim location As String
    Dim subTotal As Double
    Dim first As Integer
    '-------Pawan
    Dim dsCustomer_DTLS As Object
    Dim iLine As Integer
    Dim iSpace As Integer
    Dim sCityStateZip As String
    Dim sServQty As String
    
    Dim iAbitibiExceptionCustID As Integer
    
    ' ABITIBI IS RETARDED AND NEEDS TO BE BILLED TO A CUSTOMER OTHER THAN THE ONE THAT IT BELONGS TO
    ' guess what?  now Clementine customers are equally dense...
    If iCustId = 113 Then
        iAbitibiExceptionCustID = 312
    ElseIf iCustId = 633 Then
        iAbitibiExceptionCustID = 439
    Else
        iAbitibiExceptionCustID = iCustId
    End If
    
    
    sFromStr = "PORT OF WILMINGTON"
    
'    If IST <> "D" Then
'        sqlstmt = "select RD.*, CT.variety, CT.warehouse_location from RF_Billing_detail RD, cargo_tracking CT " _
'             & " where RD.Sum_Bill_num In( Select Billing_num " _
'             & " from RF_Billing where customer_id = '" & iCustId & "' " _
'             & " AND invoice_num = '" & iInvoice_Num & " ' AND ARRIVAL_NUM='" & sArrNum & "' " _
'             & " AND service_status = 'INVOICED' AND STACKING = 'S') AND RD.SERVICE_START = " _
'             & " TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY')" _
'             & " AND CT.receiver_id = '" & iCustId & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
'             & " AND CT.pallet_id = RD.pallet_id " _
'             & " Order By CT.variety, RD.Service_Start, RD.Service_Stop"
'    Else
'        sqlstmt = "select RD.*, CT.variety, CT.warehouse_location from RF_Billing_detail RD, cargo_tracking CT " _
'             & " where RD.Sum_Bill_num In( Select Billing_num " _
'             & " from RF_Billing where customer_id = '" & iCustId & "' " _
'             & " AND invoice_num = '" & iInvoice_Num & " ' AND ARRIVAL_NUM='" & sArrNum & "' " _
'             & " AND service_status = 'INVOICED' AND STACKING IS NULL) AND RD.SERVICE_START = " _
'             & " TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY')" _
'             & " AND CT.receiver_id = '" & iCustId & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
'             & " AND CT.pallet_id = RD.pallet_id " _
'             & " Order By CT.variety, RD.Service_Start, RD.Service_Stop"
'    End If
    
    ' Select billing details for that one summary billing number only  -- LFW, 1/23/04
    If IST <> "D" Then
        sqlstmt = "select RD.*, CT.variety, CT.warehouse_location from RF_Billing_detail RD, cargo_tracking CT " _
             & " where RD.Sum_Bill_num = " & iSumBillNum & " AND RD.SERVICE_START = " _
             & " TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY') and service_status <> 'DELETED' " _
             & " AND CT.receiver_id = '" & iAbitibiExceptionCustID & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
             & " AND SUBSTR(CT.pallet_id, 0, 25) = RD.pallet_id " _
             & " Order By CT.variety, RD.Service_Start, RD.Service_Stop, RD.pallet_id"
    Else
        sqlstmt = "select RD.*, CT.variety, CT.warehouse_location from RF_Billing_detail RD, cargo_tracking CT " _
             & " where RD.Sum_Bill_num = " & iSumBillNum & " AND RD.SERVICE_START = " _
             & " TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY') and service_status <> 'DELETED' " _
             & " AND CT.receiver_id = '" & iAbitibiExceptionCustID & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
             & " AND SUBSTR(CT.pallet_id, 0, 25) = RD.pallet_id " _
             & " Order By CT.variety, RD.Service_Start, RD.Service_Stop, RD.pallet_id"
    End If
    
    Set dsCustomer_DTLS = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
    If dsCustomer_DTLS.recordcount > 0 Then
        dInvoiceTotal = 0
        subTotal = 0
        first = 0
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNull, 0)
        
        If dsCustomer_DTLS.FIELDS("SERVICE_UNIT").Value = "PLT" And dsCustomer_DTLS.FIELDS("CUSTomer_ID").Value = 1986 Then
            sServQty = "CASES" ' "PALETTES"
        ElseIf dsCustomer_DTLS.FIELDS("SERVICE_UNIT").Value = "CWT" And dsCustomer_DTLS.FIELDS("CUSTomer_ID").Value = 1986 Then
            sServQty = "CASES"
        Else
            sServQty = "CASES"
        End If
       
        Dim sRatestr As String
        If dsCustomer_DTLS.FIELDS("CUSTOMER_ID").Value = 1986 Then
            sRatestr = "RATE/CWT"
        Else
            sRatestr = "RATE/PLT"
        End If
        
        'If the vessel name is Trucked in cargo then only show the order num from the cargo_activity
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        If UCase(sVessel) = "TRUCKED IN CARGO" Then
            Call PreInvoice_AddNew(iRow, Space(20) & "BAR CODE" & Space(25) & "START" & Space(8) & "END" _
            & Space(8) & sServQty & Space(8) & sRatestr & Space(10) & "AMOUNT" & Space(10) & "ORDER NO.", 0)
            '& "LOCATION" & Space(8)
         Else
            Call PreInvoice_AddNew(iRow, Space(20) & "BAR CODE" & Space(25) & "START" & Space(8) & "END" _
            & Space(8) & sServQty & Space(8) & sRatestr & Space(10) & "AMOUNT", 0)
            '& "LOCATION" & Space(8)
         End If
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNull, 0)

               
        For iRec = 1 To dsCustomer_DTLS.recordcount
            DoEvents
            '-------Pawan
            cargo_dis1 = cargo_dis
            sqlstmt1 = "select variety, warehouse_location from cargo_tracking where " _
                    & " receiver_id='" & iAbitibiExceptionCustID & "' " _
                    & " AND pallet_id = '" & Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value) & "'" _
                    & "AND ARRIVAL_NUM='" & sArrNum & "' "
            Set dsCargo_dis = OraDatabase.dbcreatedynaset(sqlstmt1, 0&)
            If OraDatabase.lastservererr = 0 And dsCargo_dis.recordcount > 0 Then
                If Not IsNull(dsCargo_dis.FIELDS("variety").Value) Then
                    cargo_dis = Trim(dsCargo_dis.FIELDS("variety").Value)
                Else
                    cargo_dis = "No Cargo Discription"
                End If
                If Not IsNull(dsCargo_dis.FIELDS("warehouse_location").Value) Then
                    location = Trim(dsCargo_dis.FIELDS("warehouse_location").Value)
                Else
                    location = "No Location"
                End If
            Else
                MsgBox "No Cargo Description & Location Found For the Invoice", vbInformation + vbCritical, "Check"
            End If
            If cargo_dis <> cargo_dis1 Then
                If first <> 0 Then
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(85) & "SUB TOTAL :" & Space(45) & Format(Round(subTotal, 2), "##,###,###,##0.00,"), 0)
                    subTotal = 0
                    
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, lblNull, 0)
                End If
                first = 11
                
                
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, Space(20) & cargo_dis, 0)
           End If
            
            
            '-------Pawan
            If iCount = 60 Then Call NEW_PAGE
            iCount = iCount + 1
            iRow = iRow + 1
            
            'Picks up the order num from cargo activity if the vessel is "TRUCKED IN CARGO"
            If UCase(sVessel) = "TRUCKED IN CARGO" Then
                Dim sql As String
                Dim dsOrderNum As Object
                Dim STmpOrder As String
            
                sql = "Select distinct Order_num from cargo_activity where PALLET_ID=" _
                    & "'" & Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value) & "' AND " _
                    & " service_code='8' and customer_id='" & iAbitibiExceptionCustID & "'"
            
                    Set dsOrderNum = OraDatabase.dbcreatedynaset(sql, 0&)
                    If OraDatabase.lastservererr = 0 And dsOrderNum.recordcount <> 0 Then
                        If Not IsNull(dsOrderNum.FIELDS("ORDER_NUM").Value) Then
                            STmpOrder = dsOrderNum.FIELDS("ORDER_NUM").Value
                        Else
                            STmpOrder = ""
                        End If
                    Else
                        STmpOrder = ""
                    End If
            Else
                STmpOrder = ""
            End If
            
            
            Dim iSpc0 As Integer
            Dim iSpc1 As Integer
            Dim iSpc2 As Integer
            Dim iSpc3 As Integer
            Dim iLen As Integer
            
            'Adjustments of spacing for printing on the printer
            If Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) <= 8 Then
                iSpc0 = 21
            ElseIf Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) = 9 Then
                iSpc0 = 19
            ElseIf Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) = 10 Then   'default
                iSpc0 = 17
            ElseIf Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) = 11 Then
                iSpc0 = 15
            ElseIf Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) = 12 Then
                iSpc0 = 13
            ElseIf Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) >= 13 Then
                iSpc0 = 11
            End If
            
            If Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) <= 1 Then
                iSpc1 = 25
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) = 2 Then  'DEFAULT
                iSpc1 = 23
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) = 3 Then
                iSpc1 = 21
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) = 4 Then
                iSpc1 = 19
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) = 5 Then
                iSpc1 = 17
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) >= 6 Then
                iSpc1 = 15
            End If
            
            If Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) <= 1 Then
                iSpc2 = 32
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 2 Then
                iSpc2 = 30
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 3 Then
                iSpc2 = 28
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 4 Then
                iSpc2 = 26
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 5 Then    'DEFAULT
                iSpc2 = 24
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 6 Then
                iSpc2 = 22
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 7 Then
                iSpc2 = 20
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 8 Then
                iSpc2 = 18
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) >= 9 Then
                iSpc2 = 16
            End If
            
            If sVessel = "TRUCKED IN CARGO" Then
                If Len(dsCustomer_DTLS.FIELDS("SERVICE_Amount").Value) <= 1 Then
                    iSpc3 = 26
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 2 Then
                    iSpc3 = 24
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 3 Then
                    iSpc3 = 22
                 ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 4 Then   'DEFAULT
                    iSpc3 = 20
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 5 Then
                    iSpc3 = 18
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 6 Then
                    iSpc3 = 16
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 7 Then
                    iSpc3 = 14
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 8 Then
                    iSpc3 = 12
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) >= 9 Then
                    iSpc3 = 10
                End If
            End If
            
            Call PreInvoice_AddNew(iRow, Space(20) & dsCustomer_DTLS.FIELDS("PALLET_ID").Value _
                & Space(iSpc0) & dsCustomer_DTLS.FIELDS("SERVICE_START").Value _
                & Space(8) & dsCustomer_DTLS.FIELDS("SERVICE_STOP").Value _
                & Space(8) & dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value _
                & Space(iSpc1) & Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value, 2), "##0.00,") _
                & Space(iSpc2) & Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value, 2), "##0.00,") _
                & Space(iSpc3) & STmpOrder, 0)
                ' & Space(8) & location _

                       
            dInvoiceTotal = dInvoiceTotal + dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value
            subTotal = subTotal + dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value
            dsCustomer_DTLS.MoveNext
        Next iRec
        
        '---Pawan
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(85) & "SUB TOTAL :" & Space(45) & Format(Round(subTotal, 2), "##,###,###,##0.00,"), 0)
        subTotal = 0
        '---Pawan
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNull, 0)
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(90) & "----------------------------------------------------------------------------", 0)

        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(105) & "TOTAL :" & Space(30) & Format(Round(dInvoiceTotal, 2), "##,###,###,##0.00,"), 0)

        
    End If
End Sub
'Function that inserts strings to the table PREINVOICE for printing purposes
Sub PreInvoice_AddNew(RowNum As Long, Row_Text As String, eof As Integer)
    With dsPreInvoice
        .AddNew
        .FIELDS("Row_Num").Value = RowNum
        .FIELDS("Text").Value = Row_Text
        .FIELDS("eof").Value = eof
        .Update
    End With
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub

Private Sub cmdPrint_Click()
    Dim sDate As String
    Dim sDtChar As String
    Dim sTempDate As String
    Dim sFileName As String
    Dim sDuration As String
    Dim iDuration As Integer
    Dim sPerUnit As String
    Dim iCustId As Integer
    Dim iSubCustId As String
    Dim iPSubCustId As String
    Dim iCustName As String
    Dim dTotalAmount As Double
    Dim bStart As Boolean
    Dim dGrandTotal As Double
    Dim iCommodityCode As Integer
    Dim sArrNum As String
    Dim iPageNbr As Integer
    Dim bNewInvoiceNum As Boolean
    Dim sqlstmt As String
    Dim i As Integer
    Dim iLine As Integer
    Dim sCustomerAddr1 As String
    Dim sCustomerAddr2  As String
    Dim sCityStateZip As String
    Dim sLen As String
    Dim iLen As Long
    Dim iLenSpace As Double
    Dim iBillingRec As Integer
    Dim iInv_Num As Long
    Dim dtStartDate As Date
    Dim sEmailDirPath As String
    Dim iPStacking As String
    Dim iStacking As String
    Dim iSumBillNum As Long
    
    dGrandTotal = 0
    dTotalAmount = 0
    iRow = 0
    
    bNewInvoiceNum = False
    bStart = True
    iPageNbr = 1
    
    iCustId = 0
    sArrNum = ""
    iPSubCustId = ""
    iPStacking = ""
       
    lblStatus.Caption = "Processing Your Request ..."
    
    If txtFrom = "" And txtTo = "" Then
        MsgBox "Please Enter Invoice Number to be printed", vbInformation + vbCritical, "INVOICE REPRINT"
    End If
    
    If txtFrom = "" And txtTo <> "" Then
        If MsgBox("You want to print only Invoice No. '" & txtTo & "'", vbInformation, "INVOICE REPRINT") = vbYes Then
            txtFrom = txtTo
        Else
            txtFrom.SetFocus
        End If
    End If
            
    If txtFrom = txtTo Then            'For printing only one invoice no.
        sqlstmt = "SELECT * FROM RF_BILLING WHERE SERVICE_STATUS = 'INVOICED' AND" & _
                " BILLING_TYPE IN ('PLT-STRG')AND Customer_ID is NOT NULL " & _
                " AND invoice_num = '" & txtFrom & "' " & _
                " ORDER BY CUSTOMER_ID, ARRIVAL_NUM, COMMODITY_CODE, STACKING, SERVICE_START, SERVICE_STOP"
    ElseIf txtTo = "" Then      'For printing only one invoice no.
        sqlstmt = "SELECT * FROM RF_BILLING WHERE SERVICE_STATUS = 'INVOICED' AND" & _
                " BILLING_TYPE IN ('PLT-STRG')AND Customer_ID is NOT NULL " & _
                " AND Invoice_Num = '" & txtFrom & "' " & _
                " ORDER BY CUSTOMER_ID, ARRIVAL_NUM, COMMODITY_CODE, STACKING, SERVICE_START, SERVICE_STOP"
    ElseIf txtTo <> txtFrom Then    'For printing only more then one invoice no.
        sqlstmt = "SELECT * FROM RF_BILLING WHERE SERVICE_STATUS = 'INVOICED' AND" & _
                " BILLING_TYPE IN ('PLT-STRG')AND Customer_ID is NOT NULL " & _
                " AND INVOICE_Num between '" & txtFrom & "' AND '" & txtTo & "' " & _
                " ORDER BY CUSTOMER_ID, ARRIVAL_NUM, COMMODITY_CODE, STACKING, SERVICE_START, SERVICE_STOP"
    End If
    Set dsBILLING = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
    If OraDatabase.lastservererr = 0 And dsBILLING.recordcount > 0 Then
        sqlstmt = "Select * from PreInvoice "
        Set dsPreInvoice = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
        If OraDatabase.lastservererr = 0 And dsPreInvoice.recordcount <> 0 Then
            OraDatabase.DbExecuteSQL ("delete from Preinvoice")
        End If
        
        For iBillingRec = 1 To dsBILLING.recordcount
            
            If Not IsNull(dsBILLING.FIELDS("STACKING")) Then
                iStacking = Trim$(dsBILLING.FIELDS("STACKING").Value)
            Else
                iStacking = "D"
            End If
                       
            iSumBillNum = dsBILLING.FIELDS("BILLING_NUM").Value & ""
            iSubCustId = dsBILLING.FIELDS("SUB_CUSTID").Value & ""
            
            'If either the customer or the ARRIVAL_NUM changes , new invoice no is generated
            If (iCustId <> dsBILLING.FIELDS("CUSTOMER_ID").Value) Or _
                (sArrNum <> dsBILLING.FIELDS("ARRIVAL_NUM").Value) Or _
                (iPStacking <> iStacking) Or _
                (iPSubCustId <> iSubCustId) Then
                
                bNewInvoiceNum = True
                dGrandTotal = dGrandTotal + dTotalAmount
                
                'Print the Invoice Total if it is not the very first page
                If bStart = False Then
                    For i = 1 To 2
                        If iCount = 60 Then Call NEW_PAGE
                        iCount = iCount + 1
                        iRow = iRow + 1
                        Call PreInvoice_AddNew(iRow, lblNull, 0)
                    Next i
                    
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
                    & "--------------------------------------------------------------------------------------------------------------------------------------------", 0)
                                        
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(140) & "INVOICE TOTAL : " & Space(43) _
                    & Format(Round(dTotalAmount, 2), "##,###,###,##0.00"), 0)
                                       
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
                    & "--------------------------------------------------------------------------------------------------------------------------------------------", 3)
                End If
                
                iInv_Num = dsBILLING.FIELDS("INVOICE_NUM").Value
                iCustId = dsBILLING.FIELDS("CUSTOMER_ID").Value
                sArrNum = dsBILLING.FIELDS("ARRIVAL_NUM").Value
                iPSubCustId = dsBILLING.FIELDS("SUB_CUSTID").Value & ""
                
                If Not IsNull(dsBILLING.FIELDS("STACKING")) Then
                    iPStacking = Trim$(dsBILLING.FIELDS("STACKING").Value)
                Else
                    iPStacking = "D"
                End If
                
                dtStartDate = CDate(Format(dsBILLING.FIELDS("SERVICE_START").Value, "MM/DD/YYYY"))
                sInvNum = CStr(iInv_Num)
                sInvDt = CStr(dsBILLING.FIELDS("INVOICE_date").Value)
                
                lblStatus.Caption = "Processing Invoice no. : " & sInvNum
                
                'Extracting vessel name from vessel profile
                sqlstmt = "SELECT * FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '" & dsBILLING.FIELDS("ARRIVAL_NUM").Value & "'"
                Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                If dsVESSEL_PROFILE.recordcount > 0 Then
                    sVessel = dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
                ElseIf dsVESSEL_PROFILE.recordcount = 0 Then
                    sVessel = "TRUCKED IN CARGO"
                End If
                
                'Extracting Service unit from RF_Storage_Rate
                If dsBILLING.FIELDS("CUSTOMER_ID").Value = 1986 Then
                    If dsBILLING.FIELDS("SERVICE_UNIT").Value = "PLT" Then
                        sqlstmt = " SELECT BILL_DURATION FROM RF_STORAGE_RATE" & _
                                " WHERE CUSTOMER_ID = " & _
                                dsBILLING.FIELDS("CUSTOMER_ID").Value & _
                                " AND UNIT = 'PLT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR " & iDuration & " DAYS "
                    Else
                        sqlstmt = "SELECT BILL_DURATION FROM RF_STORAGE_RATE" & _
                                " WHERE CUSTOMER_ID = " & _
                                dsBILLING.FIELDS("CUSTOMER_ID").Value & _
                                " AND UNIT = 'CWT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " WEIGHT"
                        sDuration = " PER CWT FOR " & iDuration & " DAYS "
                    End If
                Else
                    If dsBILLING.FIELDS("CUSTOMER_ID").Value = 1512 And dsBILLING.FIELDS("COMMODITY_CODE").Value = 5411 Then
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    Else
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    End If
                End If
            
                'Extracting Customer name from customer_Profile
                sqlstmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & _
                          dsBILLING.FIELDS("CUSTOMER_ID").Value
                Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                If iSubCustId = "1514" Then
                    iCustName = "OPENHEIMER FOR FRESHCO"
                Else
                    iCustName = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value
                End If
                        
                'Extracting country name from country
                sqlstmt = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE = '" & _
                          dsCUSTOMER_PROFILE.FIELDS("COUNTRY_CODE").Value & "'"
                Set dsCOUNTRY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
            
                'Get header information
                If Not IsNull(dsBILLING.FIELDS("CARE_OF")) Then
                    If (dsBILLING.FIELDS("CARE_OF").Value = "1") Or _
                       (dsBILLING.FIELDS("CARE_OF").Value = "Y") Then
                        sHeader(0) = Space(45) & sArrNum & " - " & sVessel
                        sHeader(1) = Space(45) & "C/O " & iCustName
                     Else
                        sHeader(0) = ""
                        sHeader(1) = Space(45) & "C/O " & iCustName
                     End If
                Else
                    sHeader(0) = ""
                    sHeader(1) = Space(45) & iCustId & " - " & iCustName
                End If
                            
                If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS1").Value) Then
                    sHeader(2) = Space(45) & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS1").Value
                Else
                    sHeader(2) = ""
                End If
                    
                If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS2").Value) Then
                    sHeader(3) = Space(45) & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS2").Value
                Else
                    sHeader(3) = ""
                End If
                    
                If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_City").Value) Then
                    sCityStateZip = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_City").Value
                End If
                
                If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_State").Value) Then
                    sCityStateZip = sCityStateZip & ", " & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_State").Value
                End If
                
                If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_Zip").Value) Then
                    sCityStateZip = sCityStateZip & " - " & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_Zip").Value
                End If
                
                sHeader(4) = Space(45) & sCityStateZip
                    
                If dsCOUNTRY.FIELDS("COUNTRY_CODE").Value <> "US" Then
                    If Not IsNull(dsCOUNTRY.FIELDS("COUNTRY_NAME")) Then
                        sHeader(5) = Space(45) & dsCOUNTRY.FIELDS("COUNTRY_NAME").Value
                    Else
                        sHeader(5) = ""
                    End If
                Else
                    sHeader(5) = ""
                End If
                
                If bStart = True Then
                    Call NEW_PAGE
                    bStart = False
                Else
                    If bNewInvoiceNum = True Then
                        Call NEW_PAGE
                        bNewInvoiceNum = False
                    End If
                End If
                                
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, lblNull, 0)
    
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                
                sLen = "STORAGE BILL FOR " _
                    & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                    & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                    & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                    & sDuration
                    
                iLen = Len("STORAGE BILL FOR " _
                    & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                    & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                    & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                    & sDuration)
                    
                If Len(sLen) > 70 Then
                    iLenSpace = iLen - 70
                ElseIf Len(sLen) = 70 Then
                    iLenSpace = 0
                ElseIf Len(sLen) < 70 Then
                    iLenSpace = iLen - 70
                End If
                    
                Call PreInvoice_AddNew(iRow, Space(6) & dsBILLING.FIELDS("SERVICE_START").Value & _
                    Space(10) & "STORAGE BILL FOR " _
                   & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                   & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                   & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                   & sDuration, 0)
                   
                   'No amount here  -- LFW, 9/7/04
                   '& sDuration & Space(34 - iLenSpace) & Format(dsBILLING.FIELDS("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), 0)
                    
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(CLng(iRow), Space(34) & dsBILLING.FIELDS("SERVICE_START").Value _
                & " THRU " & DateAdd("d", -1, dsBILLING.FIELDS("SERVICE_STOP").Value) _
                & ".", 0)
                
                dTotalAmount = dsBILLING.FIELDS("SERVICE_AMOUNT").Value
                    
            Else     'If there are more records for the same customer and same ARRIVAL_NUM
                dTotalAmount = dTotalAmount + dsBILLING.FIELDS("SERVICE_AMOUNT").Value
                dtStartDate = CDate(Format(dsBILLING.FIELDS("Service_start").Value, "mm/dd/YYYY"))
                
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(CLng(iRow), lblNull, 0)
                
                If dsBILLING.FIELDS("CUSTOMER_ID").Value = 1986 Then
                    If dsBILLING.FIELDS("SERVICE_UNIT").Value = "PLT" Then
                        sqlstmt = " SELECT BILL_DURATION FROM RF_STORAGE_RATE" & _
                                " WHERE CUSTOMER_ID = " & _
                                dsBILLING.FIELDS("CUSTOMER_ID").Value & _
                                " AND UNIT = 'PLT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR " & iDuration & " DAYS "
                    Else
                        sqlstmt = "SELECT BILL_DURATION FROM RF_STORAGE_RATE" & _
                                " WHERE CUSTOMER_ID = " & _
                                dsBILLING.FIELDS("CUSTOMER_ID").Value & _
                                " AND UNIT = 'CWT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " WEIGHT"
                        sDuration = " PER CWT FOR " & iDuration & " DAYS "
                    End If
                Else
                    If dsBILLING.FIELDS("CUSTOMER_ID").Value = 1512 And dsBILLING.FIELDS("COMMODITY_CODE").Value = 5411 Then
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    Else
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    End If
                'End If
            End If
                    
            If iCount = 60 Then Call NEW_PAGE
            iCount = iCount + 1
            iRow = iRow + 1
         
            sLen = "STORAGE BILL FOR " _
                & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                & sDuration
           
            iLen = Len("STORAGE BILL FOR " _
                & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                & sDuration)
            If Len(sLen) > 70 Then
                iLenSpace = iLen - 70
            ElseIf Len(sLen) = 70 Then
                iLenSpace = 0
            ElseIf Len(sLen) < 70 Then
                iLenSpace = iLen - 70
            End If
                
            Call PreInvoice_AddNew(iRow, Space(6) & dsBILLING.FIELDS("SERVICE_START").Value & _
                   Space(10) & "STORAGE BILL FOR " _
                   & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                   & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                   & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                   & sDuration, 0)
                   
                   'No amount here  -- LFW, 9/7/04
                   '& sDuration & Space(33 - iLenSpace) & Format(dsBILLING.FIELDS("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), 0)
                 
            If iCount = 60 Then Call NEW_PAGE
            iCount = iCount + 1
            iRow = iRow + 1
            Call PreInvoice_AddNew(iRow, Space(34) & dsBILLING.FIELDS("SERVICE_START").Value _
                & " THRU " & DateAdd("d", -1, dsBILLING.FIELDS("SERVICE_STOP").Value) _
                & ".", 0)
        End If
        
        'retrieve detail billing for every summary record
        Call Det_Billing(iSumBillNum, iCustId, sArrNum, iInv_Num, iPStacking, dtStartDate)
        
        dsBILLING.MoveNext
        Next iBillingRec
        
'        'calling detail billing for the very last record in the recordset
'        Call Det_Billing(iCustId, sArrNum, iInv_Num, iPStacking, dtStartDate)
        
        For i = 1 To 2
            If iCount = 60 Then Call NEW_PAGE
            iCount = iCount + 1
            iRow = iRow + 1
            Call PreInvoice_AddNew(iRow, lblNull, 0)
        Next i
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
        & "--------------------------------------------------------------------------------------------------------------------------------------------", 0)
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(140) & "INVOICE TOTAL : " & Space(43) _
        & Format(Round(dTotalAmount, 2), "##,###,###,##0.00"), 0)
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
        & "--------------------------------------------------------------------------------------------------------------------------------------------", 0)
        
        dGrandTotal = dGrandTotal + dTotalAmount
        
        Call PreInvoice_AddNew(iRow, lblNull, 2)
        For i = 1 To 34
            Call PreInvoice_AddNew(iRow, lblNull, 0)
            iRow = iRow + 1
        Next i
        
        Call PreInvoice_AddNew(iRow, Space(100) & "GRAND TOTAL" & "  :  " & Format(Round(dGrandTotal, 2), "##,###,###,##0.00"), 0)
       
        crw1.ReportFileName = App.Path & "\RFINV.rpt"
        crw1.Connect = "DSN = RF;UID = sag_owner;PWD = owner"
'        crw1.Connect = "DSN = RF.TEST;UID = sag_owner;PWD = rfowner"
        crw1.Action = 1
        If crw1.LastErrorNumber <> 0 Then MsgBox crw1.LastErrorString
        
    
        OraDatabase.DbExecuteSQL ("DELETE FROM Preinvoice")
                
    Else
        MsgBox "INVALID INVOICE NUMBER(S) ! ", vbInformation + vbCritical, "REPRINT INVOICES"
        
    End If
    
End Sub
Sub NEW_PAGE()
    Dim iLine As Integer
       
    iCount = 0
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, lblNull, 1)
        
    iLine = iLine + 1
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, Space(227) & sInvNum, 0)
       
    iLine = iLine + 1
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, lblNull, 0)
    
    iLine = iLine + 1
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, Space(227) & sInvDt, 0)
    
    For iLine = 1 To 5
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNull, 0)
    Next iLine
    
    For iLine = 0 To 5
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, sHeader(iLine), 0)
    Next iLine
            
    For iLine = 1 To 4
        iRow = iRow + 1
        iCount = iCount + 1
        Call PreInvoice_AddNew(iRow, lblNull, 0)
    Next iLine
     
End Sub

Private Sub Form_Activate()
'    txtCutOffDate.SetFocus
End Sub

Private Sub Form_Load()
    Dim sql As String
    Dim dsInv As Object
        
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    On Error GoTo Err_FormLoad
    
        
    'Shows The last Invoivce No. generated in the database
    sql = "select max(Invoice_Num) MaxInv from Rf_Billing where customer_Id is not null and " _
           & "BILLING_TYPE IN ('PLT-STRG')AND SERVICE_STATUS = 'INVOICED' "
    Set dsInv = OraDatabase.dbcreatedynaset(sql, 0&)
    
    If OraDatabase.lastservererr = 0 And dsInv.recordcount > 0 Then
        txtFrom = dsInv.FIELDS("MaxInv").Value
        txtTo = dsInv.FIELDS("MaxInv").Value
    End If
    
    iCount = 0
    
    On Error GoTo 0
    Exit Sub
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    On Error GoTo 0
End Sub



