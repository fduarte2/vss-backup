VERSION 5.00
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "CRYSTL32.OCX"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Begin VB.Form frmCryRep 
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "PRINT INVOICES"
   ClientHeight    =   2310
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   7890
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   2310
   ScaleWidth      =   7890
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdEdit 
      Caption         =   "&Edit"
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
      Left            =   4868
      TabIndex        =   8
      Top             =   1440
      Width           =   1215
   End
   Begin VB.PictureBox StatBar 
      Align           =   2  'Align Bottom
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   330
      Left            =   0
      ScaleHeight     =   270
      ScaleWidth      =   7830
      TabIndex        =   7
      Top             =   1980
      Width           =   7890
   End
   Begin VB.CommandButton cmdPreBill 
      Caption         =   "Pre&Bill"
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
      Left            =   278
      TabIndex        =   6
      Top             =   1440
      Width           =   1095
   End
   Begin MSComCtl2.DTPicker dtpCutOffDt 
      Height          =   330
      Left            =   3720
      TabIndex        =   5
      Top             =   360
      Width           =   1335
      _ExtentX        =   2355
      _ExtentY        =   582
      _Version        =   393216
      CustomFormat    =   "MM/dd/yyyy"
      Format          =   16515075
      CurrentDate     =   37622
   End
   Begin Crystal.CrystalReport crw1 
      Left            =   240
      Top             =   600
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "&Exit"
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
      Left            =   6518
      TabIndex        =   4
      Top             =   1440
      Width           =   1095
   End
   Begin VB.CommandButton cmdReInv 
      Caption         =   "&Reprint"
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
      Left            =   3338
      TabIndex        =   2
      Top             =   1440
      Width           =   1095
   End
   Begin VB.CommandButton cmdPrintWdl 
      Caption         =   "&Invoice"
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
      Left            =   1800
      TabIndex        =   0
      Top             =   1440
      Width           =   1095
   End
   Begin VB.Label lblNULL 
      Height          =   255
      Left            =   120
      TabIndex        =   3
      Top             =   120
      Visible         =   0   'False
      Width           =   975
   End
   Begin VB.Label lblDateReceived 
      AutoSize        =   -1  'True
      BackColor       =   &H00FFFFC0&
      Caption         =   "INVOICE DATE :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   1950
      TabIndex        =   1
      Top             =   405
      Width           =   1425
   End
End
Attribute VB_Name = "frmCryRep"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
'   * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
'   * Module Name    : Invoice                                                *
'   * Author         : PKA                                                    *
'   * Date           : 023/03/2000                                            *
'   * Description    : Module for Generating & Printing the Storage Invoices  *
'   * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

Option Explicit
Dim iRow As Long
Dim sText As String
Dim lInvoiceNum As Long
Dim iPrintFileNum As Integer
Dim i As Integer
Dim sVessel As String
Dim iCount As Integer
Dim sInvNum As String
Dim sInvDt As String

Sub Det_Billing(iSumBillNum As Long, iCustId As Integer, iSubCustId As String, sArrNum As String, IST As String, dtDate As Date)
    Dim sFromStr As String
    Dim dInvoiceTotal As Double
    Dim sSerQty As String
    Dim iRec As Integer
    Dim dsBilling1 As Object
    Dim sqlstmt As String
    Dim sqlstmt1 As String
    Dim dsCargo_dis As Object
    Dim cargo_dis As String
    Dim cargo_dis1 As String
    Dim location As String
    Dim subTotal As Double
    Dim first As Integer
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
                 
    If IST <> "D" Then
        sqlstmt = "SELECT distinct RD.*, DECODE(WDI_RECEIVING_PO, NULL, CT.variety, CT.variety || ' - ' || WDI_RECEIVING_PO) variety, CT.warehouse_location from RF_Billing_detail RD, cargo_tracking CT, WDI_ADDITIONAL_DATA WAD " _
            & "where RD.Sum_bill_num = " & iSumBillNum & " and RD.service_status='PREINVOICE' " _
            & "and RD.service_start=TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY') " _
            & " AND CT.PALLET_ID = WAD.WDI_PALLET_ID(+) AND CT.RECEIVER_ID = WAD.WDI_RECEIVER_ID(+) AND CT.ARRIVAL_NUM = WAD.WDI_ARRIVAL_NUM(+) " _
            & " AND CT.receiver_id = '" & iAbitibiExceptionCustID & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
            & " AND SUBSTR(CT.pallet_id, 0, 25) = RD.pallet_id and " & subCustString(iSubCustId) _
            & "order by variety, RD.Service_Start, RD.Service_Stop, RD.pallet_id"
    Else
        sqlstmt = "SELECT distinct RD.*, DECODE(WDI_RECEIVING_PO, NULL, CT.variety, CT.variety || ' - ' || WDI_RECEIVING_PO) variety, CT.warehouse_location from RF_Billing_detail RD, cargo_tracking CT, WDI_ADDITIONAL_DATA WAD " _
            & "where RD.Sum_bill_num = " & iSumBillNum & " and RD.service_status='PREINVOICE' " _
            & "and RD.service_start=TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY') " _
            & " AND CT.PALLET_ID = WAD.WDI_PALLET_ID(+) AND CT.RECEIVER_ID = WAD.WDI_RECEIVER_ID(+) AND CT.ARRIVAL_NUM = WAD.WDI_ARRIVAL_NUM(+) " _
            & " AND CT.receiver_id = '" & iAbitibiExceptionCustID & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
            & " AND SUBSTR(CT.pallet_id, 0, 25) = RD.pallet_id and " & subCustString(iSubCustId) _
            & "order By variety, RD.Service_Start, RD.Service_Stop, RD.pallet_id"
    End If
    
    Set dsCustomer_DTLS = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
    If dsCustomer_DTLS.recordcount > 0 Then
        dInvoiceTotal = 0
        subTotal = 0
        first = 0
       
        If iCustId <> 1608 Then
            Print #iPrintFileNum, ""
            Print #iPrintFileNum, ""
            Print #iPrintFileNum, ""
            Print #iPrintFileNum, ""
            Print #iPrintFileNum, ""
        End If
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 0)
        If iCustId <> 1608 Then Print #iPrintFileNum, ""
        
        If dsCustomer_DTLS.FIELDS("SERVICE_UNIT").Value = "PLT" And dsCustomer_DTLS.FIELDS("CUSTomer_ID").Value = 1986 Then
            sServQty = "CASES"  ' "PALETTES"
        ElseIf dsCustomer_DTLS.FIELDS("SERVICE_UNIT").Value = "CWT" And dsCustomer_DTLS.FIELDS("CUSTomer_ID").Value = 1986 Then
            sServQty = "CASES"
        Else
            sServQty = "CASES"
        End If
       
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 0)
        If iCustId <> 1608 Then Print #iPrintFileNum, ""
        
        Dim sRatestr As String
        If dsCustomer_DTLS.FIELDS("CUSTOMER_ID").Value = 1986 Then
            sRatestr = "RATE/CWT"
        Else
            sRatestr = "RATE/PLT"
        End If
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        
        If UCase(sVessel) = "TRUCKED IN CARGO" Then
            Call PreInvoice_AddNew(iRow, Space(20) & "BAR CODE" & Space(25) & "START" & Space(8) & "END" _
            & Space(8) & sServQty & Space(8) & sRatestr & Space(10) & "AMOUNT" & Space(10) & "ORDER NO.", 0)
            
            If iCustId <> 1608 Then
                Print #iPrintFileNum, Tab(3); "LOT_NUM"; Tab(20); "START"; Tab(32); "END"; _
                Tab(44); sServQty; Tab(56); "RATE/PLT"; Tab(70); "AMOUNT"; Tab(85); "ORDER NO."
            End If
         Else
            Call PreInvoice_AddNew(iRow, Space(20) & "BAR CODE" & Space(25) & "START" & Space(8) & "END" _
            & Space(8) & sServQty & Space(8) & sRatestr & Space(10) & "AMOUNT", 0)
        
            If iCustId <> 1608 Then
                Print #iPrintFileNum, Tab(3); "PALLET_NUM"; Tab(20); "START"; Tab(32); "END"; _
                Tab(44); sServQty; Tab(56); "RATE/PLT"; Tab(70); "AMOUNT";
            End If
        End If
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 0)
        If iCustId <> 1608 Then Print #iPrintFileNum, ""
               
        For iRec = 1 To dsCustomer_DTLS.recordcount
            DoEvents
            cargo_dis1 = cargo_dis
            sqlstmt1 = "select distinct variety, warehouse_location, WDI_RECEIVING_PO from cargo_tracking ct, WDI_ADDITIONAL_DATA WAD where " _
                    & " receiver_id='" & iAbitibiExceptionCustID & "' " _
                    & " AND CT.PALLET_ID = WAD.WDI_PALLET_ID(+) AND CT.RECEIVER_ID = WAD.WDI_RECEIVER_ID(+) AND CT.ARRIVAL_NUM = WAD.WDI_ARRIVAL_NUM(+) " _
                    & " AND substr(pallet_id, 0, 25) = '" & Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value) & "'" _
                    & "AND ARRIVAL_NUM='" & sArrNum & "' "
            Set dsCargo_dis = OraDatabase.dbcreatedynaset(sqlstmt1, 0&)
            If OraDatabase.lastservererr = 0 And dsCargo_dis.recordcount > 0 Then
                If Not IsNull(dsCargo_dis.FIELDS("variety").Value) Then
                    cargo_dis = Trim(dsCargo_dis.FIELDS("variety").Value)
                Else
                    cargo_dis = "No Cargo Discription"
                End If
                
                If Not IsNull(dsCargo_dis.FIELDS("WDI_RECEIVING_PO").Value) Then
                    cargo_dis = cargo_dis & " - " & dsCargo_dis.FIELDS("WDI_RECEIVING_PO").Value
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
                    If iCustId <> 1608 Then Print #iPrintFileNum, Tab(55); "SUB TOTAL"; Tab(69); Format(Round(subTotal, 2), "##,###,###,##0.00,")
        
                    subTotal = 0
                    
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, lblNULL, 0)
                    If iCustId <> 1608 Then Print #iPrintFileNum, ""
                End If
                first = 11
                
                
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, Space(20) & cargo_dis, 0)
                If iCustId <> 1608 Then Print #iPrintFileNum, Tab(5); cargo_dis
           End If
            
            If iCount = 60 Then Call NEW_PAGE
            iCount = iCount + 1
            iRow = iRow + 1
            
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
                    iSpc3 = 24
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 2 Then
                    iSpc3 = 22
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 3 Then
                    iSpc3 = 20
                 ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 4 Then   'DEFAULT
                    iSpc3 = 18
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 5 Then
                    iSpc3 = 16
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 6 Then
                    iSpc3 = 14
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 7 Then
                    iSpc3 = 12
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 8 Then
                    iSpc3 = 10
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) >= 9 Then
                    iSpc3 = 8
                End If
            End If
            
            Call PreInvoice_AddNew(iRow, Space(20) & dsCustomer_DTLS.FIELDS("PALLET_ID").Value _
                & Space(iSpc0) & dsCustomer_DTLS.FIELDS("SERVICE_START").Value _
                & Space(8) & dsCustomer_DTLS.FIELDS("SERVICE_STOP").Value _
                & Space(8) & dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value _
                & Space(iSpc1) & Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value, 2), "##0.00,") _
                & Space(iSpc2) & Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value, 2), "##0.00,") _
                & Space(iSpc3) & STmpOrder, 0)

            If iCustId <> 1608 Then
                Print #iPrintFileNum, Tab(3); dsCustomer_DTLS.FIELDS("PALLET_ID").Value; _
                    Tab(20); dsCustomer_DTLS.FIELDS("SERVICE_START").Value; _
                    Tab(32); dsCustomer_DTLS.FIELDS("SERVICE_STOP").Value; _
                    Tab(44); dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value; _
                    Tab(56); Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value, 2), "##0.00,"); _
                    Tab(70); Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value, 2), "##0.00,"); _
                    Tab(85); STmpOrder
            End If
            
            Dim sPrnFile As String
            If iCustId = 1608 Then
                sPrnFile = lInvoiceNum & "," & sArrNum & "-" & UCase(Trim(sVessel)) & ","
                sPrnFile = sPrnFile & IIf(IsNull(dsCustomer_DTLS.FIELDS("SERVICE_DESCRIPTION").Value), "", Trim(dsCustomer_DTLS.FIELDS("SERVICE_DESCRIPTION").Value))
                sPrnFile = sPrnFile & "," & IIf(IsNull(dsCustomer_DTLS.FIELDS("PALLET_ID").Value), "", Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value))
                sPrnFile = sPrnFile & "," & IIf(IsNull(dsCustomer_DTLS.FIELDS("SERVICE_START").Value), "", Format(dsCustomer_DTLS.FIELDS("SERVICE_START").Value, "mm/dd/yyyy"))
                sPrnFile = sPrnFile & "," & IIf(IsNull(dsCustomer_DTLS.FIELDS("SERVICE_STOP").Value), "", Format(dsCustomer_DTLS.FIELDS("SERVICE_STOP").Value, "mm/dd/yyyy"))
                sPrnFile = sPrnFile & "," & IIf(IsNull(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value), 0, dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value)
                sPrnFile = sPrnFile & "," & IIf(IsNull(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value), 0, Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value, 2), "##0.00,"))
                sPrnFile = sPrnFile & "," & IIf(IsNull(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value), 0, Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value, 2), "##0.00,"))
                sPrnFile = sPrnFile & "," & STmpOrder
                Print #iPrintFileNum, sPrnFile
            End If
            
            dInvoiceTotal = dInvoiceTotal + dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value
            subTotal = subTotal + dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value
            dsCustomer_DTLS.MoveNext
        Next iRec
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(85) & "SUB TOTAL :" & Space(45) & Format(Round(subTotal, 2), "##,###,###,##0.00,"), 0)
        If iCustId <> 1608 Then Print #iPrintFileNum, Tab(55); "SUB TOTAL"; Tab(69); Format(Round(subTotal, 2), "##,###,###,##0.00,")
        
        subTotal = 0
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 0)
        Print #iPrintFileNum, ""
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(90) & "----------------------------------------------------------------------------", 0)
        If iCustId <> 1608 Then Print #iPrintFileNum, Tab(45); "--------------------------------------------"
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(115) & "TOTAL :" & Space(30) & Format(Round(dInvoiceTotal, 2), "##,###,###,##0.00,"), 0)
        If iCustId <> 1608 Then Print #iPrintFileNum, Tab(55); "TOTAL"; Tab(69); Format(Round(dInvoiceTotal, 2), "##,###,###,##0.00,")
        
    End If
    
    sqlstmt = "SELECT * FROM RF_BILLING WHERE SERVICE_STATUS = 'TO EMAIL' AND" _
                & " BILLING_TYPE ='PLT-STRG'and customer_id='" & iCustId & "' " _
                & "and ARRIVAL_NUM='" & sArrNum & "' and service_start=" _
                & " TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM-DD-YYYY')"
    
    Set dsBilling1 = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If OraDatabase.lastservererr = 0 And dsBILLING.recordcount > 0 Then
        While Not dsBilling1.eof
            DoEvents
            dsBilling1.Edit
            dsBilling1.FIELDS("SERVICE_STATUS").Value = "INVOICED"
            dsBilling1.Update
            dsBilling1.MoveNext
        Wend
    End If
    If iCustId <> 1608 Then Print #iPrintFileNum, ""
End Sub

Sub PreInvoice_AddNew(RowNum As Long, Row_Text As String, eof As Integer)
    With dsPreInvoice
        .AddNew
        .FIELDS("Row_Num").Value = RowNum
        .FIELDS("Text").Value = Row_Text
        .FIELDS("eof").Value = eof
        .Update
    End With
End Sub

Private Sub cmdEdit_Click()
    frmEditInv.Show
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub
Sub PreBill_Detail(iSumBillNum As Long, iCustId As Integer, iSubCustId As String, sArrNum As String, IST As String, dtDate As Date)
    
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
'        sqlstmt = "SELECT RD.*, CT.variety, CT.warehouse_location from RF_Billing_detail RD, cargo_tracking CT " _
'            & "where RD.Sum_bill_num in (Select Billing_num " _
'            & "from RF_Billing where customer_id='" & iCustId & "' and ARRIVAL_NUM =" _
'            & "'" & sArrNum & "' AND STACKING ='S' ) and RD.service_status='PREINVOICE' " _
'            & "and RD.service_start=TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY') " _
'            & " AND CT.receiver_id = '" & iCustId & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
'            & " AND CT.pallet_id = RD.pallet_id and " & subCustString(iSubCustId) _
'            & "order by CT.variety, RD.Service_Start, RD.Service_Stop"
'    Else
'        sqlstmt = "SELECT RD.*, CT.variety, CT.warehouse_location from RF_Billing_detail RD, cargo_tracking CT " _
'            & "where RD.Sum_bill_num in (Select Billing_num " _
'            & "from RF_Billing where customer_id='" & iCustId & "' and ARRIVAL_NUM =" _
'            & "'" & sArrNum & "' AND STACKING IS NULL) and RD.service_status='PREINVOICE' " _
'            & "and RD.service_start=TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY') " _
'            & " AND CT.receiver_id = '" & iCustId & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
'            & " AND CT.pallet_id = RD.pallet_id and " & subCustString(iSubCustId) _
'            & "order By CT.variety, RD.Service_Start, RD.Service_Stop"
'    End If

    ' Select billing details for that one summary billing number only  -- LFW, 1/23/04
    If IST <> "D" Then
        sqlstmt = "SELECT distinct RD.*, DECODE(WDI_RECEIVING_PO, NULL, CT.variety, CT.variety || ' - ' || WDI_RECEIVING_PO) variety, CT.warehouse_location from RF_Billing_detail RD, cargo_tracking CT, WDI_ADDITIONAL_DATA WAD " _
            & "where RD.Sum_bill_num = " & iSumBillNum & " and RD.service_status='PREINVOICE' " _
            & "and RD.service_start=TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY') " _
            & " AND CT.PALLET_ID = WAD.WDI_PALLET_ID(+) AND CT.RECEIVER_ID = WAD.WDI_RECEIVER_ID(+) AND CT.ARRIVAL_NUM = WAD.WDI_ARRIVAL_NUM(+) " _
            & " AND CT.receiver_id = '" & iAbitibiExceptionCustID & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
            & " AND SUBSTR(CT.pallet_id, 0, 25) = RD.pallet_id and " & subCustString(iSubCustId) _
            & "order by variety, RD.Service_Start, RD.Service_Stop, RD.pallet_id"
    Else
        sqlstmt = "SELECT distinct RD.*, DECODE(WDI_RECEIVING_PO, NULL, CT.variety, CT.variety || ' - ' || WDI_RECEIVING_PO) variety, CT.warehouse_location from RF_Billing_detail RD, cargo_tracking CT, WDI_ADDITIONAL_DATA WAD " _
            & "where RD.Sum_bill_num = " & iSumBillNum & " and RD.service_status='PREINVOICE' " _
            & "and RD.service_start=TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY') " _
            & " AND CT.PALLET_ID = WAD.WDI_PALLET_ID(+) AND CT.RECEIVER_ID = WAD.WDI_RECEIVER_ID(+) AND CT.ARRIVAL_NUM = WAD.WDI_ARRIVAL_NUM(+) " _
            & " AND CT.receiver_id = '" & iAbitibiExceptionCustID & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
            & " AND SUBSTR(CT.pallet_id, 0, 25) = RD.pallet_id and " & subCustString(iSubCustId) _
            & "order By variety, RD.Service_Start, RD.Service_Stop, RD.pallet_id"
    End If

    Set dsCustomer_DTLS = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
    If dsCustomer_DTLS.recordcount > 0 Then
        dInvoiceTotal = 0
        '.....Pawan
        subTotal = 0
        first = 0
        '.....Pawan
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 0)
        
        If dsCustomer_DTLS.FIELDS("SERVICE_UNIT").Value = "PLT" And dsCustomer_DTLS.FIELDS("CUSTomer_ID").Value = 1986 Then
            sServQty = "CASES" ' "PALETTES"
        ElseIf dsCustomer_DTLS.FIELDS("SERVICE_UNIT").Value = "CWT" And dsCustomer_DTLS.FIELDS("CUSTomer_ID").Value = 1986 Then
            sServQty = "CASES"
        Else
            sServQty = "CASES"
        End If
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 0)
        
        Dim sRatestr As String
        If dsCustomer_DTLS.FIELDS("CUSTOMER_ID").Value = 1986 Then
            sRatestr = "RATE/CWT"
        Else
            sRatestr = "RATE/PLT"
        End If
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        If UCase(sVessel) = "TRUCKED IN CARGO" Then
            Call PreInvoice_AddNew(iRow, Space(20) & "BAR CODE" & Space(25) & "START" & Space(8) & "END" _
            & Space(8) & sServQty & Space(8) & sRatestr & Space(10) & "AMOUNT" & Space(10) & "ORDER NO.", 0)
         
            'Call PreInvoice_AddNew(iRow, Space(20) & "BAR CODE" & Space(15) & "START" & Space(15) & "END" _
            & Space(15) & sServQty & Space(15) & sRatestr & Space(15) & "AMOUNT" & Space(15) & "ORDER NO.", 0)
         Else
            Call PreInvoice_AddNew(iRow, Space(20) & "BAR CODE" & Space(25) & "START" & Space(8) & "END" _
            & Space(8) & sServQty & Space(8) & sRatestr & Space(10) & "AMOUNT", 0)
        
            'Call PreInvoice_AddNew(iRow, Space(20) & "BAR CODE" & Space(15) & "START" & Space(15) & "END" _
            & Space(15) & sServQty & Space(15) & sRatestr & Space(15) & "AMOUNT", 0)
        End If
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 0)
               
        For iRec = 1 To dsCustomer_DTLS.recordcount
            DoEvents
            
            '-------Pawan
            cargo_dis1 = cargo_dis
            sqlstmt1 = "select distinct variety, warehouse_location, WDI_RECEIVING_PO " _
                    & " from cargo_tracking ct, WDI_ADDITIONAL_DATA WAD Where " _
                    & " ct.receiver_id='" & iAbitibiExceptionCustID & "' " _
                    & " AND CT.PALLET_ID = WAD.WDI_PALLET_ID(+) AND CT.RECEIVER_ID = WAD.WDI_RECEIVER_ID(+) AND CT.ARRIVAL_NUM = WAD.WDI_ARRIVAL_NUM(+) " _
                    & " AND substr(ct.pallet_id, 0, 25) = '" & Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value) & "'" _
                    & "AND ct.ARRIVAL_NUM='" & sArrNum & "' "
            Set dsCargo_dis = OraDatabase.dbcreatedynaset(sqlstmt1, 0&)
            If OraDatabase.lastservererr = 0 And dsCargo_dis.recordcount > 0 Then
                If Not IsNull(dsCargo_dis.FIELDS("variety").Value) Then
                    cargo_dis = Trim(dsCargo_dis.FIELDS("variety").Value)
                Else
                    cargo_dis = "No Cargo Discription"
                End If
                
                If Not IsNull(dsCargo_dis.FIELDS("WDI_RECEIVING_PO").Value) Then
                    cargo_dis = cargo_dis & " - " & dsCargo_dis.FIELDS("WDI_RECEIVING_PO").Value
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
                    'Print #iPrintFileNum, Tab(55); "SUB TOTAL"; Tab(69); Format(Round(subTotal, 2), "##,###,###,##0.00,")
        
                    subTotal = 0
                    
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, lblNULL, 0)
                    'Print #iPrintFileNum, ""
                End If
                first = 11
                
                
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, Space(20) & cargo_dis, 0)
                'Print #iPrintFileNum, Tab(5); cargo_dis
        
                
           End If
            
            
            '-------Pawan
            
            If iCount = 60 Then Call NEW_PAGE
            iCount = iCount + 1
            iRow = iRow + 1
            
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
                    iSpc3 = 24
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 2 Then
                    iSpc3 = 22
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 3 Then
                    iSpc3 = 20
                 ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 4 Then   'DEFAULT
                    iSpc3 = 18
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 5 Then
                    iSpc3 = 16
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 6 Then
                    iSpc3 = 14
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 7 Then
                    iSpc3 = 12
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 8 Then
                    iSpc3 = 10
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) >= 9 Then
                    iSpc3 = 8
                End If
            End If
            
            Call PreInvoice_AddNew(iRow, Space(20) & dsCustomer_DTLS.FIELDS("PALLET_ID").Value _
                & Space(iSpc0) & dsCustomer_DTLS.FIELDS("SERVICE_START").Value _
                & Space(8) & dsCustomer_DTLS.FIELDS("SERVICE_STOP").Value _
                & Space(8) & dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value _
                & Space(iSpc1) & Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value, 2), "##0.00,") _
                & Space(iSpc2) & Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value, 2), "##0.00,") _
                & Space(iSpc3) & STmpOrder, 0)
            '& Space(8) & location _

            dInvoiceTotal = dInvoiceTotal + dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value
            '---PAWAN
            subTotal = subTotal + dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value
            '----PAWAN
            dsCustomer_DTLS.MoveNext
        Next iRec
        '---Pawan
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(85) & "SUB TOTAL :" & Space(45) & Format(Round(subTotal, 2), "##,###,###,##0.00,"), 0)
        'Print #iPrintFileNum, Tab(55); "SUB TOTAL"; Tab(69); Format(Round(subTotal, 2), "##,###,###,##0.00,")
        
        subTotal = 0
        '---Pawan
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 0)
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(90) & "----------------------------------------------------------------------------", 0)
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(115) & "TOTAL :" & Space(30) & Format(Round(dInvoiceTotal, 2), "##,###,###,##0.00,"), 0)
        
    End If

End Sub
Private Sub cmdPreBill_Click()
    
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
    Dim sLen As String
    Dim iLen As Long
    Dim iLenSpace As Double
    Dim iBillingRec As Integer
    Dim dtStartDate As Date
    Dim iStacking As String
    Dim iPStacking As String
    Dim iSumBillNum As Long
    
    iRow = 0
    bNewInvoiceNum = False
    bStart = True
    iPageNbr = 1
    iCustId = 0
    iSumBillNum = 0
    dGrandTotal = 0
    sArrNum = ""
    iStacking = ""
    iPStacking = ""
    'StatBar.SimpleText = "Processing Your Request ..."
    
    sqlstmt = "SELECT * FROM RF_BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND" & _
                " BILLING_TYPE IN ('PLT-STRG')AND CUSTOMER_ID is NOT NULL " & _
                " ORDER BY CUSTOMER_ID, ARRIVAL_NUM, COMMODITY_CODE,STACKING, SUB_CUSTID, SERVICE_START, SERVICE_STOP"
    Set dsBILLING = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
    If OraDatabase.lastservererr = 0 And dsBILLING.recordcount > 0 Then
        OraSession.begintrans
        
        sqlstmt = "Select * from PreInvoice "
        Set dsPreInvoice = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        If OraDatabase.lastservererr = 0 And dsPreInvoice.recordcount <> 0 Then
            OraDatabase.DbExecuteSQL ("delete from Preinvoice")
        End If
        
        For iBillingRec = 1 To dsBILLING.recordcount
            'ADD STACKING CONDITION
                
            If Not IsNull(dsBILLING.FIELDS("STACKING")) Then
                iStacking = Trim$(dsBILLING.FIELDS("STACKING").Value)
            Else
                iStacking = "D"
            End If
            
            iSumBillNum = dsBILLING.FIELDS("BILLING_NUM").Value & ""
            iSubCustId = dsBILLING.FIELDS("SUB_CUSTID").Value & ""
            
            If (iCustId <> dsBILLING.FIELDS("customer_id").Value) Or _
                (sArrNum <> Trim$(dsBILLING.FIELDS("ARRIVAL_NUM").Value)) Or _
                iPStacking <> iStacking Or _
                (iSubCustId <> iPSubCustId) Then
                            
                If bStart = False Then
                    For i = 1 To 2
                        If iCount = 60 Then Call NEW_PAGE
                        iCount = iCount + 1
                        iRow = iRow + 1
                        Call PreInvoice_AddNew(iRow, lblNULL, 0)
                    Next i
                    
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
                    & "--------------------------------------------------------------------------------------------------------------------------------------------", 0)
                                     
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(140) & "BILL TOTAL : " & Space(43) _
                    & Format(Round(dTotalAmount, 2), "##,###,###,##0.00"), 0)
                    
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
                    & "--------------------------------------------------------------------------------------------------------------------------------------------", 3)
                End If
                
                bStart = False
                
                dGrandTotal = dGrandTotal + dTotalAmount
                dTotalAmount = 0
                
                iCustId = dsBILLING.FIELDS("customer_id").Value
                iPSubCustId = dsBILLING.FIELDS("sub_custid").Value & ""
                sArrNum = Trim$(dsBILLING.FIELDS("ARRIVAL_NUM").Value)
                
                If Not IsNull(dsBILLING.FIELDS("STACKING")) Then
                    iPStacking = Trim$(dsBILLING.FIELDS("STACKING").Value)
                Else
                    iPStacking = "D"
                End If
                
                dtStartDate = CDate(Format(dsBILLING.FIELDS("Service_start").Value, "mm/dd/YYYY"))
                
                sqlstmt = "SELECT * FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '" & Trim$(dsBILLING.FIELDS("ARRIVAL_NUM").Value) & "'"
                Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                
                If dsVESSEL_PROFILE.recordcount > 0 Then
                    sVessel = dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
                ElseIf dsVESSEL_PROFILE.recordcount = 0 Then
                    sVessel = "TRUCKED IN CARGO" 'COULD SHOW ORDER NUMBER
                End If
                
                If dsBILLING.FIELDS("CUSTOMER_ID").Value = 1986 Then
                    If dsBILLING.FIELDS("SERVICE_UNIT").Value = "PLT" Then
                        
                        sqlstmt = " SELECT BILL_DURATION FROM RF_STORAGE_RATE WHERE CUSTOMER_ID = " & _
                                dsBILLING.FIELDS("CUSTOMER_ID").Value & " AND UNIT = 'PLT'"
                        
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    Else
                        sqlstmt = "SELECT BILL_DURATION FROM RF_STORAGE_RATE WHERE CUSTOMER_ID = " & _
                                dsBILLING.FIELDS("CUSTOMER_ID").Value & " AND UNIT = 'CWT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " WEIGHT"
                        sDuration = " PER CWT FOR THE PERIOD "
                    End If
                Else ' IS THE ENZA PALLETS OR NOT
                    If dsBILLING.FIELDS("COMMODITY_CODE").Value <> 5411 Then
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    Else
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    End If
                End If
            
                sqlstmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & dsBILLING.FIELDS("CUSTOMER_ID").Value
                Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                
                If iSubCustId = "1514" Then
                    iCustName = "OPPENHEIMER FOR FRESHCO"
                Else
                    iCustName = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value
                End If
                        
'                sqlstmt = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE = ' " & dsCUSTOMER_PROFILE.FIELDS("COUNTRY_CODE").Value & "'"
'                Set dsCOUNTRY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                    
                iCount = 0
                
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, Space(227) & lblNULL, 1)
                            
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, lblNULL, 0)
                            
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1 '227
                Call PreInvoice_AddNew(iRow, Space(227) & Trim(dtpCutOffDt.Value), 0)
                sInvDt = dtpCutOffDt.Value
    
                For iLine = 1 To 6
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, lblNULL, 0)
                Next iLine
                
                If Not IsNull(dsBILLING.FIELDS("CARE_OF")) Then
                    If (dsBILLING.FIELDS("CARE_OF").Value = "1") Or (dsBILLING.FIELDS("CARE_OF").Value = "Y") Then
                        iRow = iRow + 1
                        Call PreInvoice_AddNew(iRow, Space(45) & sArrNum & " - " & sVessel, 0)
                        
                        If iCount = 60 Then Call NEW_PAGE
                        iCount = iCount + 1
                        iRow = iRow + 1
                        Call PreInvoice_AddNew(iRow, Space(45) & "C/O " & iCustName, 0)
                     Else
                        If iCount = 60 Then Call NEW_PAGE
                        iCount = iCount + 1
                        iRow = iRow + 1
                        If iSubCustId = "1514" Then
                            Call PreInvoice_AddNew(iRow, Space(45) & iCustId & " - " & iCustName, 0)
                        Else
                            Call PreInvoice_AddNew(iRow, Space(45) & iCustId & " - " & iCustName, 0)
                        End If
                     End If
                Else
                     If iCount = 60 Then Call NEW_PAGE
                     iCount = iCount + 1
                     iRow = iRow + 1
                     Call PreInvoice_AddNew(iRow, Space(45) & iCustId & " - " & iCustName, 0)
                End If
                            
                If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS1").Value) Then
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(45) & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS1").Value, 0)
                End If
                    
                If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS2").Value) Then
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(45) & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS2").Value, 0)
                End If
                    
                    
                Dim sCityStateZip As String
                If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_City").Value) Then
                    sCityStateZip = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_City").Value
                End If
                If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_State").Value) Then
                    sCityStateZip = sCityStateZip & ", " & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_State").Value
                End If
                If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_Zip").Value) Then
                    sCityStateZip = sCityStateZip & " - " & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_Zip").Value
                End If
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, Space(45) & sCityStateZip, 0)
                    
'                If dsCOUNTRY.FIELDS("COUNTRY_CODE").Value <> "US" Then
'                    If Not IsNull(dsCOUNTRY.FIELDS("COUNTRY_NAME")) Then
'                        If iCount = 60 Then Call NEW_PAGE
'                        iCount = iCount + 1
'                        iRow = iRow + 1
'                        Call PreInvoice_AddNew(iRow, Space(45) & iCustName, 0)
'                    End If
'                End If
    
                For iLine = 1 To 8
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, lblNULL, 0)
                Next iLine
                
                
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                                   
                sLen = "BILL #: " & iSumBillNum & ", STORAGE BILL FOR " _
                    & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                    & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                    & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                    & sDuration
                
                iLen = Len("BILL #: " & iSumBillNum & ", STORAGE BILL FOR " _
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
                    Space(10) & "BILL #: " & iSumBillNum & ", STORAGE BILL FOR " _
                   & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                   & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                   & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                   & sDuration & Space(30 - iLenSpace) & Format(dsBILLING.FIELDS("SERVICE_AMOUNT").Value, _
                     "##,###,###,##0.00"), 0)
                
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(CLng(iRow), Space(34) & dsBILLING.FIELDS("SERVICE_START").Value _
                & " THRU " & dsBILLING.FIELDS("SERVICE_STOP").Value _
                & ".", 0)
                
                dTotalAmount = dsBILLING.FIELDS("SERVICE_AMOUNT").Value
            Else
                dTotalAmount = dTotalAmount + dsBILLING.FIELDS("SERVICE_AMOUNT").Value
                
                dtStartDate = CDate(Format(dsBILLING.FIELDS("Service_start").Value, "mm/dd/YYYY"))

                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(CLng(iRow), lblNULL, 0)
                
                If dsBILLING.FIELDS("CUSTOMER_ID").Value = 1986 Then
                    If dsBILLING.FIELDS("SERVICE_UNIT").Value = "PLT" Then
                        sqlstmt = " SELECT BILL_DURATION FROM RF_STORAGE_RATE" & _
                                " WHERE CUSTOMER_ID = " & _
                                dsBILLING.FIELDS("CUSTOMER_ID").Value & _
                                " AND UNIT = 'PLT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    Else
                        sqlstmt = "SELECT BILL_DURATION FROM RF_STORAGE_RATE" & _
                                " WHERE CUSTOMER_ID = " & _
                                dsBILLING.FIELDS("CUSTOMER_ID").Value & _
                                " AND UNIT = 'CWT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " WEIGHT"
                        sDuration = " PER CWT FOR THE PERIOD "
                    End If
                Else ' IS ENZA PALLETS OR NOT
                    If dsBILLING.FIELDS("COMMODITY_CODE").Value <> 5411 Then
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    Else
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    End If
                End If
                        
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
             
                sLen = "BILL #: " & iSumBillNum & ", STORAGE BILL FOR " _
                    & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                    & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                    & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                    & sDuration
               
                iLen = Len("BILL #: " & iSumBillNum & ", STORAGE BILL FOR " _
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
                       Space(10) & "BILL #: " & iSumBillNum & ", STORAGE BILL FOR " _
                       & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                       & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                       & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                       & sDuration & Space(30 - iLenSpace) & Format(dsBILLING.FIELDS("SERVICE_AMOUNT").Value, _
                       "##,###,###,##0.00"), 0)
                     
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, Space(34) & dsBILLING.FIELDS("SERVICE_START").Value _
                    & " THRU " & dsBILLING.FIELDS("SERVICE_STOP").Value _
                    & ".", 0)
            End If
                
            Call PreBill_Detail(iSumBillNum, iCustId, iSubCustId, sArrNum, iPStacking, dtStartDate)
            
            dsBILLING.MoveNext
        Next iBillingRec
        
        For i = 1 To 2
            If iCount = 60 Then Call NEW_PAGE
            iCount = iCount + 1
            iRow = iRow + 1
            Call PreInvoice_AddNew(iRow, lblNULL, 0)
        Next i
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
         & "-----------------------------------------------------------------------------------------------------------------------------------------------------", 0)
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(140) & "INVOICE TOTAL : " & Space(43) _
                    & Format(Round(dTotalAmount, 2), "##,###,###,##0.00"), 0)
       
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
        & "-----------------------------------------------------------------------------------------------------------------------------------------------------", 0)
                                    
        dGrandTotal = dGrandTotal + dTotalAmount
        
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 2)
        For i = 1 To 34
            iRow = iRow + 1
            Call PreInvoice_AddNew(iRow, lblNULL, 0)
        Next i
        
        Call PreInvoice_AddNew(iRow, Space(100) & "GRAND TOTAL FOR INVOICE DATE  " & dtpCutOffDt.Value & "  :  " & Format(Round(dGrandTotal, 2), "##,###,###,##0.00,"), 0)
        
        OraSession.COMMITTRANS
        
        crw1.ReportFileName = App.Path & "\RFBILL.rpt"
        crw1.Connect = "DSN = RF;UID = sag_owner;PWD = owner"
'        crw1.Connect = "DSN = RF.TEST;UID = sag_owner;PWD = rfowner"
        crw1.Action = 1
        If crw1.LastErrorNumber <> 0 Then MsgBox crw1.LastErrorString
    Else
        MsgBox "NO RECORDS", vbInformation
        Exit Sub
    End If
End Sub

Private Sub cmdPrintWdl_Click()
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
    Dim sLen As String
    Dim iLen As Long
    Dim iLenSpace As Double
    Dim iBillingRec As Integer
    Dim dtStartDate As Date
    Dim iPStacking As String
    Dim iStacking As String
    Dim iSumBillNum As Long
    Dim sCityStateZip As String
    Dim iTemp As Long
    

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
    iStacking = ""
    
    'StatBar.SimpleText = "Processing Your Request ..."
    
    sDate = Format(Date, "MM/DD/YY") + Format(Now, "HH:mm")
    For i = 1 To Len(sDate)
        sDtChar = Mid(sDate, i, 1)
        If sDtChar <> "/" Then
            If sDtChar <> ":" Then
                sTempDate = sTempDate & sDtChar
            End If
        End If
    Next i
    
    iPrintFileNum = FreeFile()
    
    Dim sEmailDirPath As String
    sEmailDirPath = "c:\EmailBills"
        
    sqlstmt = "SELECT * FROM RF_BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND" & _
              " BILLING_TYPE IN ('PLT-STRG')AND Customer_ID is NOT NULL " & _
              " ORDER BY CUSTOMER_ID, ARRIVAL_NUM, COMMODITY_CODE, STACKING, SUB_CUSTID, SERVICE_START, SERVICE_STOP"
        
    Set dsBILLING = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
    If OraDatabase.lastservererr = 0 And dsBILLING.recordcount > 0 Then
        
        sqlstmt = "Select * from PreInvoice"
        Set dsPreInvoice = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
        If OraDatabase.lastservererr = 0 And dsPreInvoice.recordcount <> 0 Then
            OraDatabase.DbExecuteSQL ("delete from Preinvoice")
        End If
        
        OraSession.begintrans
        
        For iBillingRec = 1 To dsBILLING.recordcount
            
            If Not IsNull(dsBILLING.FIELDS("STACKING")) Then
                iStacking = Trim$(dsBILLING.FIELDS("STACKING").Value)
            Else
                iStacking = "D"
            End If
            
            iSumBillNum = dsBILLING.FIELDS("BILLING_NUM").Value & ""
            iSubCustId = dsBILLING.FIELDS("SUB_CUSTID").Value & ""
                       
            If (iCustId <> dsBILLING.FIELDS("customer_id").Value) Or _
                (sArrNum <> Trim$(dsBILLING.FIELDS("ARRIVAL_NUM").Value)) Or _
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
                        Call PreInvoice_AddNew(iRow, lblNULL, 0)
                        If iCustId <> 1608 Then Print #iPrintFileNum, ""
                    Next i
                    
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
                    & "--------------------------------------------------------------------------------------------------------------------------------------------", 0)
                    
                    If iCustId <> 1608 Then Print #iPrintFileNum, Tab(5); "--------------------------------------------------------------------------------------------------------------------------"""
                                     
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(140) & "INVOICE TOTAL : " & Space(43) _
                    & Format(Round(dTotalAmount, 2), "##,###,###,##0.00"), 0)
                    
                    If iCustId <> 1608 Then Print #iPrintFileNum, Tab(90); Format(Round(dTotalAmount, 2), "##,###,###,##0.00")
                    
                    If iCount = 60 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
                    & "--------------------------------------------------------------------------------------------------------------------------------------------", 3)
                    If iCustId <> 1608 Then Print #iPrintFileNum, Tab(5); "--------------------------------------------------------------------------------------------------------------------------"""
                    If iCustId <> 1608 Then Print #iPrintFileNum, ""
                    If iCustId <> 1608 Then Print #iPrintFileNum, ""
                End If
                   
                If iCustId <> dsBILLING.FIELDS("customer_id").Value Then
                    Close iPrintFileNum
                    sFileName = dsBILLING.FIELDS("customer_id").Value & Trim(sTempDate) & ".txt"
                    Open sEmailDirPath & "/" & sFileName For Output As iPrintFileNum
                End If
           
                iCustId = dsBILLING.FIELDS("customer_id").Value
                iPSubCustId = dsBILLING.FIELDS("SUB_CUSTID").Value & ""
                sArrNum = Trim$(dsBILLING.FIELDS("ARRIVAL_NUM").Value)
                
                If Not IsNull(dsBILLING.FIELDS("STACKING")) Then
                    iPStacking = Trim$(dsBILLING.FIELDS("STACKING").Value)
                Else
                    iPStacking = "D"
                End If
                
                dtStartDate = CDate(Format(dsBILLING.FIELDS("SERVICE_START").Value, "MM/DD/YYYY"))
                
                sqlstmt = "SELECT * FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '" & dsBILLING.FIELDS("ARRIVAL_NUM").Value & "'"
                Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                
                If dsVESSEL_PROFILE.recordcount > 0 Then
                    sVessel = dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
                ElseIf dsVESSEL_PROFILE.recordcount = 0 Then
                    sVessel = "TRUCKED IN CARGO"
                End If
                
                If dsBILLING.FIELDS("CUSTOMER_ID").Value = 1986 Then
                    If dsBILLING.FIELDS("SERVICE_UNIT").Value = "PLT" Then
                        sqlstmt = " SELECT BILL_DURATION FROM RF_STORAGE_RATE" & _
                                " WHERE CUSTOMER_ID = " & _
                                dsBILLING.FIELDS("CUSTOMER_ID").Value & _
                                " AND UNIT = 'PLT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    Else
                        sqlstmt = "SELECT BILL_DURATION FROM RF_STORAGE_RATE" & _
                                " WHERE CUSTOMER_ID = " & _
                                dsBILLING.FIELDS("CUSTOMER_ID").Value & _
                                " AND UNIT = 'CWT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " WEIGHT"
                        sDuration = " PER CWT FOR THE PERIOD "
                    End If
                Else ' IS ENZA OR NOT
                    If dsBILLING.FIELDS("COMMODITY_CODE").Value <> 5411 Then
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    Else
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    End If
                End If
            
                sqlstmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & _
                          dsBILLING.FIELDS("CUSTOMER_ID").Value
                Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                
                If iSubCustId = "1514" Then
                    iCustName = "OPPENHEIMER FOR FRESHCO"
                Else
                    iCustName = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value
                End If
                        
'                sqlstmt = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE = '" & _
'                          dsCUSTOMER_PROFILE.FIELDS("COUNTRY_CODE").Value & "'"
'                Set dsCOUNTRY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                
                sqlstmt = "SELECT max(invoice_num) INV_COUNT FROM RF_BILLING"
                
                Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                
                ' new logic; on next fiscal year, increment 3rd and 4th digit to new year
                iTemp = 24000000 + (Format(Now + 182, "yy") * 10000)
'                iTemp = 24000000 + (Format(Now, "yy") * 10000)
                If OraDatabase.lastservererr = 0 And dsBILLING_MAX.FIELDS("INV_COUNT").Value < iTemp Then
                    lInvoiceNum = iTemp
                Else
                    lInvoiceNum = 1 + dsBILLING_MAX.FIELDS("INV_COUNT").Value
                End If
                
                sInvNum = lInvoiceNum
                sInvDt = dtpCutOffDt.Value
                
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
                    
'                If dsCOUNTRY.FIELDS("COUNTRY_CODE").Value <> "US" Then
'                    If Not IsNull(dsCOUNTRY.FIELDS("COUNTRY_NAME")) Then
'                        sHeader(5) = Space(45) & dsCOUNTRY.FIELDS("COUNTRY_NAME").Value
'                    Else
'                        sHeader(5) = ""
'                    End If
'                Else
                    sHeader(5) = ""
'                End If
                
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
                Call PreInvoice_AddNew(iRow, lblNULL, 0)
    
                'StatBar.SimpleText = "Processing Invoice No. : " & sInvNum
                
                If iCustId <> 1608 Then
                    Print #iPrintFileNum, Tab(3); "INVOICE NUMBER :"; Tab(23); CStr(lInvoiceNum)
                    Print #iPrintFileNum, ""
                    Print #iPrintFileNum, Tab(3); "INVOICE DATE :"; Tab(23); dtpCutOffDt.Value
                    Print #iPrintFileNum, ""
                    Print #iPrintFileNum, ""
                    
                    If Not IsNull(dsBILLING.FIELDS("CARE_OF")) Then
                        If (dsBILLING.FIELDS("CARE_OF").Value = "1") Or _
                           (dsBILLING.FIELDS("CARE_OF").Value = "Y") Then
                            Print #iPrintFileNum, Tab(3); "TO : "; Tab(14); sArrNum & " - " & sVessel
                            Print #iPrintFileNum, Tab(14); "C/O " & iCustName
                        Else
                            Print #iPrintFileNum, Tab(3); "TO : "; Tab(14); iCustId & " - " & iCustName
                        End If
                    Else
                         Print #iPrintFileNum, Tab(3); "TO : "; Tab(14); iCustId & " - " & iCustName
                    End If
                                
                    If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS1").Value) Then
                        If iCustId <> 1608 Then Print #iPrintFileNum, Tab(14); dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_Address1").Value
                    End If
                        
                    If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS2").Value) Then
                        Print #iPrintFileNum, Tab(14); dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_Address2").Value
                    End If
                    
                    Print #iPrintFileNum, Tab(14); sCityStateZip
                        
'                    If dsCOUNTRY.FIELDS("COUNTRY_CODE").Value <> "US" Then
'                        If Not IsNull(dsCOUNTRY.FIELDS("COUNTRY_NAME")) Then
'                            Print #iPrintFileNum, Tab(14); dsCOUNTRY.FIELDS("COUNTRY_NAME").Value
'                        End If
'                    End If
        
                    Print #iPrintFileNum, ""
                    Print #iPrintFileNum, ""
                    Print #iPrintFileNum, ""
                    Print #iPrintFileNum, ""
                End If
                
                dsBILLING.Edit
                dsBILLING.FIELDS("INVOICE_NUM").Value = lInvoiceNum
                dsBILLING.FIELDS("SERVICE_STATUS").Value = "TO EMAIL"
                dsBILLING.FIELDS("INVOICE_DATE").Value = CDate(dtpCutOffDt.Value)
                dsBILLING.Update
                
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
                    
                    'No showing of the amount here  -- LFW, 9/7/04
                    '& sDuration & Space(30 - iLenSpace) & Format(dsBILLING.FIELDS("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), 0)
                    
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(CLng(iRow), Space(34) & dsBILLING.FIELDS("SERVICE_START").Value _
                    & " THRU " & dsBILLING.FIELDS("SERVICE_STOP").Value & ".", 0)
                
                If iCustId <> 1608 Then
                    Print #iPrintFileNum, Tab(3); Tab(12); "STORAGE BILL FOR "; _
                          dsBILLING.FIELDS("SERVICE_QTY2").Value; " PALLET(S), "; _
                          dsBILLING.FIELDS("SERVICE_QTY").Value; sPerUnit; " @ $"; _
                          Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00"); _
                          sDuration
                          
                          'No amount showing here  -- LFW, 9/7/04
                          'sDuration; Tab(90); Format(dsBILLING.FIELDS("SERVICE_AMOUNT").Value, "##,###,###,##0.00")
                    
                    Print #iPrintFileNum, Tab(12); dsBILLING.FIELDS("SERVICE_START").Value; _
                        " THRU "; dsBILLING.FIELDS("SERVICE_STOP").Value; "."
                End If
                
                dTotalAmount = dsBILLING.FIELDS("SERVICE_AMOUNT").Value
            Else
                dTotalAmount = dTotalAmount + dsBILLING.FIELDS("SERVICE_AMOUNT").Value
                dtStartDate = CDate(Format(dsBILLING.FIELDS("Service_start").Value, "mm/dd/YYYY"))

                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(CLng(iRow), lblNULL, 0)
                
                If iCustId <> 1608 Then Print #iPrintFileNum, ""
                
                If dsBILLING.FIELDS("CUSTOMER_ID").Value = 1986 Then
                    If dsBILLING.FIELDS("SERVICE_UNIT").Value = "PLT" Then
                        sqlstmt = " SELECT BILL_DURATION FROM RF_STORAGE_RATE" & _
                                  " WHERE CUSTOMER_ID = " & dsBILLING.FIELDS("CUSTOMER_ID").Value & _
                                  " AND UNIT = 'PLT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    Else
                        sqlstmt = "SELECT BILL_DURATION FROM RF_STORAGE_RATE" & _
                                  " WHERE CUSTOMER_ID = " & dsBILLING.FIELDS("CUSTOMER_ID").Value & _
                                  " AND UNIT = 'CWT'"
                        Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        
                        iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                        sPerUnit = " WEIGHT"
                        sDuration = " PER CWT FOR THE PERIOD "
                    End If
                Else    ' IS ENZA PALLETS OR NOT
                    If dsBILLING.FIELDS("COMMODITY_CODE").Value <> 5411 Then
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    Else
                        sPerUnit = " CASE(S)"
                        sDuration = " PER PLT FOR THE PERIOD "
                    End If
                End If
                        
                dsBILLING.Edit
                dsBILLING.FIELDS("INVOICE_NUM").Value = lInvoiceNum
                dsBILLING.FIELDS("SERVICE_STATUS").Value = "TO EMAIL"
                dsBILLING.FIELDS("INVOICE_DATE").Value = CDate(dtpCutOffDt.Value)
                dsBILLING.Update
                
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
                       
                       'No amount showing here  -- LFW, 9/7/04
                       '& sDuration & Space(30 - iLenSpace) & Format(dsBILLING.FIELDS("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), 0)
                     
                If iCount = 60 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, Space(34) & dsBILLING.FIELDS("SERVICE_START").Value _
                    & " THRU " & dsBILLING.FIELDS("SERVICE_STOP").Value & ".", 0)
                    
                If iCustId <> 1608 Then
                    Print #iPrintFileNum, Tab(3); Tab(12); "STORAGE BILL FOR "; _
                         dsBILLING.FIELDS("SERVICE_QTY2").Value; " PALLET(S), "; _
                         dsBILLING.FIELDS("SERVICE_QTY").Value; sPerUnit; " @ $"; _
                         Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00"); _
                         sDuration
                        
                        'No amount showing here  -- LFW, 9/7/04
                        'sDuration; Tab(90); Format(dsBILLING.FIELDS("SERVICE_AMOUNT").Value, "##,###,###,##0.00")
                        
                        
                    Print #iPrintFileNum, Tab(12); dsBILLING.FIELDS("SERVICE_START").Value; _
                        " THRU "; dsBILLING.FIELDS("SERVICE_STOP").Value; "."
                End If
            End If
        
            Call Det_Billing(iSumBillNum, iCustId, iSubCustId, sArrNum, iPStacking, dtStartDate)

            dsBILLING.MoveNext
        Next iBillingRec
               
        'This might be writing for the last invoice, LFW, 9/2/04
        For i = 1 To 2
            If iCount = 60 Then Call NEW_PAGE
            iCount = iCount + 1
            iRow = iRow + 1
            Call PreInvoice_AddNew(iRow, lblNULL, 0)
            If iCustId <> 1608 Then Print #iPrintFileNum, ""
        Next i
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
         & "-----------------------------------------------------------------------------------------------------------------------------------------------------", 0)
        
        If iCustId <> 1608 Then Print #iPrintFileNum, Tab(5); "--------------------------------------------------------------------------------------------------------------------------"""
        
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(140) & "INVOICE TOTAL : " & Space(43) _
            & Format(Round(dTotalAmount, 2), "##,###,###,##0.00"), 0)
        
        If iCustId <> 1608 Then Print #iPrintFileNum, Tab(90); Format(Round(dTotalAmount, 2), "##,###,###,##0.00")
       
        If iCount = 60 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
            & "-----------------------------------------------------------------------------------------------------------------------------------------------------", 0)
        
        If iCustId <> 1608 Then Print #iPrintFileNum, Tab(5); "--------------------------------------------------------------------------------------------------------------------------"""
                                                       
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 2)
        For i = 1 To 34
            iRow = iRow + 1
            Call PreInvoice_AddNew(iRow, lblNULL, 0)
        Next i
        
        'Add to last invoice total
        dGrandTotal = dGrandTotal + dTotalAmount
        
        'Write the grand total
        Call PreInvoice_AddNew(iRow, Space(100) & "GRAND TOTAL FOR THE INVOICE DATE  " & dtpCutOffDt.Value & "  :  " & Format(Round(dGrandTotal, 2), "##,###,###,##0.00,"), 0)
        OraSession.COMMITTRANS
        
        crw1.ReportFileName = App.Path & "\RFINV.rpt"
        crw1.Connect = "DSN = RF;UID = sag_owner;PWD = owner"
        crw1.Action = 1
        If crw1.LastErrorNumber <> 0 Then MsgBox crw1.LastErrorString
    Else
        MsgBox "NO RECORDS", vbInformation
        Exit Sub
    End If
    
End Sub
Sub NEW_PAGE()
    Dim iLine As Integer
       
    iCount = 0
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, lblNULL, 1)
        
    iLine = iLine + 1
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, Space(227) & sInvNum, 0)
       
    iLine = iLine + 1
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, lblNULL, 0)
    
    iLine = iLine + 1
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, Space(227) & sInvDt, 0)
    
    For iLine = 1 To 5
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 0)
    Next iLine
    
    For iLine = 0 To 5
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, sHeader(iLine), 0)
    Next iLine
            
    For iLine = 1 To 4
        iRow = iRow + 1
        iCount = iCount + 1
        Call PreInvoice_AddNew(iRow, lblNULL, 0)
    Next iLine
End Sub
Private Sub cmdReInv_Click()
    frmRePrint.Show vbModal
End Sub
Private Sub Form_Activate()
    dtpCutOffDt.SetFocus
End Sub

Private Sub Form_Load()
    
    Dim TimeArray(1) As SYSTEMTIME
    Dim result As Long
   
    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    ' Define first element of SYSTEMTIME Array to be minimum date.
    TimeArray(0).wDay = 1
    TimeArray(0).wMonth = 1
    TimeArray(0).wYear = 1999

    ' Define second element of SYSTEMTIME Array to be maximum date.
    TimeArray(1).wDay = Day(Now)
    TimeArray(1).wMonth = Month(Now)
    TimeArray(1).wYear = Year(Now)

    ' Call API to send message to control to set MinDate and MaxDate.
    result = SendMessage(Me.dtpCutOffDt.hwnd, DTM_SETRANGE, GDTR_MIN + GDTR_MAX, TimeArray(0))
    dtpCutOffDt.Value = Format(Now, "mm/dd/yyyy")
    ' All Dates are set to the proper constraints and the InviceDate cannot be changed!
    
    If MsgBox("Have you set the default Printer ?", vbQuestion + vbYesNo, "PRINTER") = vbNo Then
        End
        Exit Sub
    End If
        
    On Error GoTo Err_FormLoad
    
    'StatBar.SimpleText = "Logon Successful."
    iCount = 0
    
    'Call Print_Invoice
    On Error GoTo 0
    Exit Sub
    'Unload Me
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    On Error GoTo 0
End Sub

Private Function subCustString(subCustId As String) As String
    If IsNull(subCustId) Or Trim(subCustId) = "" Then
        subCustString = " (RD.SUB_CUSTID IS NULL OR RD.SUB_CUSTID ='') "
    Else
        subCustString = " RD.SUB_CUSTID = '" & subCustId & "' "
    End If
End Function
