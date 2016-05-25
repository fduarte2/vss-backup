VERSION 5.00
Begin VB.Form frmWhseRcpt 
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Print Warehouse Receipts"
   ClientHeight    =   3165
   ClientLeft      =   4965
   ClientTop       =   3375
   ClientWidth     =   5280
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   3165
   ScaleWidth      =   5280
   Begin VB.TextBox txtCutOffDate 
      Height          =   315
      Left            =   2153
      MaxLength       =   10
      TabIndex        =   1
      Top             =   720
      Width           =   1665
   End
   Begin VB.CommandButton cmdPrintWdl 
      Caption         =   "&Print Warehouse Receipts"
      Height          =   315
      Left            =   1320
      TabIndex        =   2
      Top             =   1920
      Width           =   2415
   End
   Begin VB.Label Label1 
      BackColor       =   &H80000018&
      Caption         =   "Note: The date is inclusive"
      Height          =   255
      Left            =   1320
      TabIndex        =   3
      Top             =   1320
      Width           =   2415
   End
   Begin VB.Label lblDateReceived 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Cut Off Date"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   1200
      TabIndex        =   0
      Top             =   780
      Width           =   885
   End
End
Attribute VB_Name = "frmWhseRcpt"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdPrintWdl_Click()
    Dim DummyDebugString As String
    Dim blnNormal As Boolean
    Dim blnTransferred As Boolean
    Dim blnTransferOwnership As Boolean
    Dim blnIsSteel As Boolean
    Dim blnAmerimarkGroup As Boolean
    Dim sSqlStmt As String
    Dim sCustomerAddr1 As String
    Dim sCustomerAddr2 As String
    Dim dLoadedWeight As Double
    Dim dTotalQty As Double
    Dim dTotalWeight As Double
    Dim sLine As String
    Dim sMark As String
    Dim sMarkRcvd As String
    Dim ithTransfer As String
    Dim i As Integer
    Dim j As Integer
    Dim iNumOfLines As Integer
    Dim iPageNbr As Integer
    Dim dQtyInHouseToday As Double
    Dim dOriginalWeight As Double
    Dim iOldCustomerId As Integer
    Dim iOldLRNum As Integer
    Dim iOldCommodityCode As Integer
    Dim dStorageDate As Date
    Dim dOldStorageDate As Date
    Dim iFirstTime As Integer
    Dim iNewWhseRcpt As Integer
    Dim lWhseRcptNum As Long
    Dim lQty1Rcvd As Double
    Dim iCust As Integer
    Dim iOrigCust As Integer
    Dim iCommodity As Integer
    Dim blnCheck As Boolean

    'Check if date is valid
    If Not IsDate(txtCutOffDate.Text) Then
        MsgBox "Please enter a valid Cut Off Date.", vbInformation, "Invalid Cut Off Date"
        txtCutOffDate.SetFocus
        Exit Sub
    End If
    
    'Set Storage Customer Id to be Owner if not set already
    OraSession.BeginTrans
        gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE WHSE_RCPT_NUM = 0 AND COMMODITY_CODE NOT IN (1272, 6172, 8101) "
        gsSqlStmt = gsSqlStmt & "AND STORAGE_CUST_ID IS NULL "
        Set dsCARGO_TRACKING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.RecordCount > 0 Then
            While Not dsCARGO_TRACKING.EOF
                dsCARGO_TRACKING.Edit
                dsCARGO_TRACKING.fields("STORAGE_CUST_ID").Value = dsCARGO_TRACKING.fields("OWNER_ID").Value
                dsCARGO_TRACKING.Update
                dsCARGO_TRACKING.MoveNext
            Wend
        End If
    OraSession.CommitTrans
    
    'Initializations
    iOldCustomerId = 0
    iOldLRNum = 0
    iOldCommodityCode = 0
    
    'This system was implemented and processes free time only after 07/01/1998.  Hence no dates will be less 07/01/1998
    dOldStorageDate = CDate("01/01/1997")
    
    iNewWhseRcpt = True
    iFirstTime = True
    iNumOfLines = 0
    iPageNbr = 1
    
    'Ask the user to set the top of page on the printer
    MsgBox "Reset Printer to the top of the page.", vbInformation, "Reset Printer"
    
    'Open output file for printing
    giPrintFileNum = FreeFile()
    Open App.Path & "\WhseRcpt.PRN" For Output As giPrintFileNum
    
    'Begin another Oracle transaction - Added by Lynn F. Wang, 12/13/2002
    OraSession.BeginTrans
    
    'Select All Cargo Tracking records that do not have a warehouse receipt number and whose Free time runs out today
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE WHSE_RCPT_NUM = 0 AND " _
                & "QTY_RECEIVED > 0 AND FREE_TIME_END <= TO_DATE('" & Trim$(txtCutOffDate.Text) & "', 'MM/DD/YYYY') " _
                & "AND COMMODITY_CODE NOT IN (1272, 6172, 8101) " _
                & "ORDER BY FREE_TIME_END, OWNER_ID, COMMODITY_CODE "
                
    Set dsCARGO_TRACKING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr <> 0 Then
        MsgBox "Error in retrieving data from Cargo_Tracking.  Transaction rollbacked. "
        OraSession.Rollback
        Exit Sub
    End If
    
    If dsCARGO_TRACKING.RecordCount > 0 Then
        
        While Not dsCARGO_TRACKING.EOF
            gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = "
            gsSqlStmt = gsSqlStmt & "'" & dsCARGO_TRACKING.fields("LOT_NUM").Value & "'"
            Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    
            If OraDatabase.LastServerErr <> 0 Then
                MsgBox "Error in retrieving data from Cargo_Manifest.  Transaction rollbacked. "
                OraSession.Rollback
                Exit Sub
            End If
            
            If dsCARGO_MANIFEST.RecordCount > 0 Then
            
                iCommodity = Val(Trim(dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value))

                ' Check whether it is steel
                ' Transferred Steel will inherit the free time, Per Antonia  -- LFW, 2/11/04
                If iCommodity = 3302 Or iCommodity = 3304 Or iCommodity = 3312 Or iCommodity = 3323 Or _
                   iCommodity = 3326 Or iCommodity = 3328 Or iCommodity = 3350 Or iCommodity = 3399 Then
                    blnIsSteel = True
                Else
                    blnIsSteel = False
                End If

                'Try determine the storage date
                    
                'Check whether it is transferred cargo
                sMark = "" & dsCARGO_MANIFEST.fields("CARGO_MARK").Value
                If Val("" & InStr(sMark, "TR*")) = 1 Then
                    blnTransferred = True
                    
                    'For all transfers, we leave the free time end date untouched so it is easier for storage program to know
                    'how many days elapsed since the 1st day of storage.  We use the storage end date to implement out policies   -- LFW, 5/17/04
                    '1) Common Case: Transferred cargo doesn't get free time.  It gets storage charge from the day following the transfer date, Per Antonia  - LFW 1/8/04
                    '   we set storage end date to the transfer day.
                    '2) When it is a transfer with no ownership transfer, the transferred cargo would still have free time, but can get overlapped charge
                    '   we set storage end date to either (free time end - 1) or the transfer day
                    '3) When it is a transfer between 179-Amerimark and 2008-Trillenium, we consider it is not a transfer of ownership
                    '   the transferred cargo gets free time, in addition, no overlapping charge, and no juice surcharge
                    '   we set storage end to either (free time end - 1), storage end, or next period start
                    '4) Transferred Steel inherits free time and gets no overlapping charge
                    '   we set storage end to either (free time end - 1), storage end, or next period start
                    
                    'Check to see whether it is a transfer of ownership
                    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = '" & dsCARGO_MANIFEST.fields("ORIGINAL_CONTAINER_NUM").Value & "'"
                    Set dsCARGO_ORIGINAL_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                    
                    iOrigCust = Val("" & dsCARGO_ORIGINAL_MANIFEST.fields("RECIPIENT_ID").Value)
                    iCust = Val("" & dsCARGO_MANIFEST.fields("RECIPIENT_ID").Value)
                
                    'Transfers between 179-Amerimark and 2008-Trillenium are considered not a transfer of ownership  -- Per HD # 1507, LFW, 4/25/05
                    'get free time, no overlapped charge and no Juice surcharge
                    If (iOrigCust = 179 And iCust = 2008) Or (iOrigCust = 2008 And iCust = 179) Then
                        blnAmerimarkGroup = True
                    Else
                        blnAmerimarkGroup = False
                    End If
                
                    If iCust = iOrigCust Then
                        blnTransferOwnership = False
                    Else
                        blnTransferOwnership = True
                    End If
                    
                    'Try get the Storage End date
                    If Not IsNull(dsCARGO_TRACKING.fields("STORAGE_END").Value) Then
                        'The date has been set by previous storage run
                        dStorageDate = DateValue(dsCARGO_TRACKING.fields("STORAGE_END").Value)
                    ElseIf blnTransferOwnership = False Then
                        'Not a transfer of ownership. New owner gets free time but charges could overlap
                        If DateValue(dsCARGO_TRACKING.fields("DATE_RECEIVED").Value) < DateValue(dsCARGO_TRACKING.fields("FREE_TIME_END").Value) Then
                            'Transfer was made before free time is ended, get free time
                            dStorageDate = DateAdd("d", -1, dsCARGO_TRACKING.fields("FREE_TIME_END").Value)
                        Else
                            'Transfer was made after free time is ended, no more free time
                            'set the storage end date to the transfer day
                            dStorageDate = DateValue(dsCARGO_TRACKING.fields("DATE_RECEIVED").Value)
                        End If
                    ElseIf blnIsSteel = True Or blnAmerimarkGroup = True Then
                        'It is a transfer of steel, or a transfer within Amerimark Group
                        'gets free time and no overlapping charge
                        If DateValue(dsCARGO_TRACKING.fields("DATE_RECEIVED").Value) < DateValue(dsCARGO_TRACKING.fields("FREE_TIME_END").Value) Then
                            'Transfer was made before free time is ended
                            dStorageDate = DateAdd("d", -1, dsCARGO_TRACKING.fields("FREE_TIME_END").Value)
                        Else
                            'Transfer was made after free time is ended, no more free time
                            'Need to set the storage end date to the 1st period end that is later than or the same as transfer date.
                            
                            'Try to obtain period duration from storage rate table
                            'Get service code from location category
                            gsSqlStmt = "SELECT * FROM LOCATION_CATEGORY WHERE LOCATION_TYPE = '" & UCase(Trim$(dsCARGO_MANIFEST.fields("CARGO_LOCATION").Value)) & "'"
                            Set dsLOCATION_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    
                            'Use the vessel specific rates if they exist.  Otherwise use the regular rates
                            gsSqlStmt = "SELECT * FROM STORAGE_RATE WHERE LR_NUM = " & dsCARGO_MANIFEST.fields("LR_NUM").Value & " AND " _
                                      & "SERVICE_CODE = " & dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value & " AND " _
                                      & "COMMODITY_CODE = " & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value & " " _
                                      & "ORDER BY START_DAY "
                            Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                            
                            If OraDatabase.LastServerErr <> 0 Then
                                MsgBox "Database Error On Retrieving Storage Rate for Commodity Code - " _
                                        & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value & " and Location - " _
                                        & dsCARGO_MANIFEST.fields("CARGO_LOCATION").Value
                                OraSession.Rollback
                                Exit Sub
                            ElseIf dsSTORAGE_RATE.RecordCount <= 0 Then
                                gsSqlStmt = "SELECT * FROM STORAGE_RATE WHERE SERVICE_CODE = " & dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value
                                gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value
                                gsSqlStmt = gsSqlStmt & " ORDER BY START_DAY "
                                Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                                
                                'Error in Storage Rate
                                If OraDatabase.LastServerErr <> 0 Or dsSTORAGE_RATE.RecordCount <= 0 Then
                                    MsgBox "Error or Unavailable Storage Rate for Commodity Code - " _
                                        & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value & " and Location - " _
                                        & dsCARGO_MANIFEST.fields("CARGO_LOCATION").Value
                                    OraSession.Rollback
                                    Exit Sub
                                End If
                            End If
                            
                            'try to find the first period end that is later than or equal to transfer day
                            blnCheck = False
                            dStorageDate = DateValue(dsCARGO_TRACKING.fields("FREE_TIME_END").Value)
                            
                            Do  'start a loop
                                'If based on month then add number of months to the date
                                If Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "MO" Then
                                    dStorageDate = DateAdd("d", -1, DateAdd("m", dsSTORAGE_RATE.fields("DURATION").Value, dStorageDate))
                                End If
                                                                    
                                'If based on day then add number of days to date
                                If Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "DAY" Then
                                    dStorageDate = DateAdd("d", dsSTORAGE_RATE.fields("DURATION").Value - 1, dStorageDate)
                                End If
                                
                                If dStorageDate >= DateValue(dsCARGO_TRACKING.fields("DATE_RECEIVED").Value) Then
                                    blnCheck = True
                                End If
                            Loop Until blnCheck = True   'end of the loop
                        End If
                    Else
                        'Common Case: start to get storage from the day after the transfer is made
                        dStorageDate = DateValue(dsCARGO_TRACKING.fields("DATE_RECEIVED").Value)
                    End If
                Else
                    'Not a transfer at all
                    blnTransferred = False
                    blnTransferOwnership = False
                    dStorageDate = DateAdd("d", -1, dsCARGO_TRACKING.fields("FREE_TIME_END").Value)
                End If
                
                If DateDiff("d", Now, dStorageDate) >= 0 Then
                    'Not ready to process this one
                    If blnTransferred = True Then
                        'Update storage date for transferred cargo so we don't have to do it all over again
                        dsCARGO_TRACKING.Edit
                        dsCARGO_TRACKING.fields("STORAGE_END").Value = dStorageDate
                        dsCARGO_TRACKING.Update
                    End If
                Else
                'Weight Calculation Starts
                    'Get the original qty received, weight and mark, assuming it is not transferred cargo  - LFW, 1/7/03
                    lQty1Rcvd = Val("" & dsCARGO_TRACKING.fields("QTY_RECEIVED").Value)
                    dOriginalWeight = Val("" & dsCARGO_MANIFEST.fields("CARGO_WEIGHT").Value)
                    sMark = "" & dsCARGO_MANIFEST.fields("CARGO_MARK").Value
                    sMarkRcvd = sMark
                                
                    'Check if it is a normal record, which means that it has an unique
                    '(lr_num, commodity_code, bol, and mark) tuple
                    gsSqlStmt = "SELECT * FROM CARGO_TRACKING CT, CARGO_MANIFEST CM " _
                                & "WHERE CT.LOT_NUM = CM.CONTAINER_NUM " _
                                & "AND CM.LR_NUM = " & dsCARGO_MANIFEST.fields("LR_NUM").Value _
                                & " AND CM.COMMODITY_CODE = " & dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value _
                                & " AND CM.CARGO_BOL = '" & dsCARGO_MANIFEST.fields("CARGO_BOL").Value _
                                & "' AND CM.CARGO_MARK = '" & sMark & "'"
                    Set dsCARGO_ORIGINAL_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    
                    If dsCARGO_ORIGINAL_MANIFEST.RecordCount > 1 Then
                        blnNormal = False
                    Else
                        blnNormal = True
                    End If
                                 
                    'If it is a transfered cargo, original qty received, weight and mark should be those of the original owner
                    If blnTransferred = True And blnNormal = True Then
                        'Take off the TR*#* part (5 or 6 characters) to restore the original mark
                        'Assume cargos get transfered less than 100 times.  -LFW, 1/6/03
                        ithTransfer = Mid(sMarkRcvd, 4, 2)
                        If IsNumeric(ithTransfer) Then
                            sMarkRcvd = Trim(Mid(sMarkRcvd, 7))
                            blnNormal = True
                        Else
                            ithTransfer = Mid(sMarkRcvd, 4, 1)
                            If IsNumeric(ithTransfer) Then
                                sMarkRcvd = Trim(Mid(sMarkRcvd, 6))
                                blnNormal = True
                            Else
                                blnNormal = False
                            End If
                        End If
                        
                        If blnNormal = True Then
                            gsSqlStmt = "SELECT CT.QTY_RECEIVED QTY1, CM.CARGO_WEIGHT WEIGHT " _
                                & "FROM CARGO_TRACKING CT, CARGO_MANIFEST CM WHERE CT.LOT_NUM = CM.CONTAINER_NUM " _
                                & "AND CM.LR_NUM = " & dsCARGO_MANIFEST.fields("LR_NUM").Value _
                                & " AND CM.COMMODITY_CODE = " & dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value _
                                & " AND CM.CARGO_BOL = '" & dsCARGO_MANIFEST.fields("CARGO_BOL").Value _
                                & "' AND CM.CARGO_MARK = '" & sMarkRcvd & "'"
                            Set dsCARGO_ORIGINAL_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                        
                            'If there is one record for the cargo for the original owner, get the quantity received
                            'Otherwise, switch back to quantity received and mark of the current record
                            If dsCARGO_ORIGINAL_MANIFEST.RecordCount = 1 And Val("" & dsCARGO_ORIGINAL_MANIFEST.fields("QTY1").Value) > 0 Then
                                'So lQty1Rcvd will always be greater than 0
                                lQty1Rcvd = Val("" & dsCARGO_ORIGINAL_MANIFEST.fields("QTY1").Value)
                                dOriginalWeight = Val("" & dsCARGO_ORIGINAL_MANIFEST.fields("WEIGHT").Value)
                                blnNormal = True
                            Else
                                sMarkRcvd = sMark
                                blnNormal = False
                            End If
                        End If
                    End If
                    
                    'Calculate total weight and weight left  - LFW, 1/6/03
                    gsSqlStmt = "SELECT CT.LOT_NUM, CT.DATE_RECEIVED DATE_RECEIVED, CM.CARGO_WEIGHT WEIGHT, CM.CARGO_MARK MARK " _
                            & "FROM CARGO_TRACKING CT, CARGO_MANIFEST CM WHERE CT.LOT_NUM = CM.CONTAINER_NUM " _
                            & "AND CM.LR_NUM = " & dsCARGO_MANIFEST.fields("LR_NUM").Value _
                            & " AND CM.COMMODITY_CODE = " & dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value _
                            & " AND CM.CARGO_BOL = '" & dsCARGO_MANIFEST.fields("CARGO_BOL").Value _
                            & "' AND CM.CARGO_MARK LIKE '%" & sMarkRcvd & "' ORDER BY CT.LOT_NUM"
                    Set dsCARGO_ORIGINAL_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                             
                    '1) Add weights only if it is a normal record.  Otherwise take its own weight as the original weight received.
                    '   So when it is normal, original weight received is the sum of the weights of cargos with
                    '   the same lr_num, commodity_code, bol, and with mark ending with the mark of the original one.
                    '2) Since 10/26/2002, the weight, qty1 and qty2 of cargo transfered
                    '   to a new customer will be subtracted from the original owner
                    '3) Add only weights of transferred cargo because dOriginalWeight already
                    '   have weight of the original owner
                    If dsCARGO_ORIGINAL_MANIFEST.RecordCount > 0 Then
                        While Not dsCARGO_ORIGINAL_MANIFEST.EOF
                            If blnNormal = True And DateValue(dsCARGO_ORIGINAL_MANIFEST.fields("DATE_RECEIVED").Value) >= DateValue("October 26, 2002") _
                                And Val("" & InStr(dsCARGO_ORIGINAL_MANIFEST.fields("MARK").Value, "TR*")) = 1 Then
                                dOriginalWeight = dOriginalWeight + Val("" & dsCARGO_ORIGINAL_MANIFEST.fields("WEIGHT").Value)
                            End If
                            dsCARGO_ORIGINAL_MANIFEST.MoveNext
                        Wend
                    End If
                'Weight Calculation Ends
                    
                    'For every such record add qty_change from any withdrawals that were done past that date
                    gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMCHANGE FROM CARGO_ACTIVITY WHERE LOT_NUM = "
                    gsSqlStmt = gsSqlStmt & dsCARGO_TRACKING.fields("LOT_NUM").Value
                    gsSqlStmt = gsSqlStmt & " AND DATE_OF_ACTIVITY <= TO_DATE("
                    gsSqlStmt = gsSqlStmt & "'" & Format$(dStorageDate, "MM/DD/YYYY") & "','MM/DD/YYYY')"
                    
                    Set dsCARGO_ACTIVITY_SUM = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                    
                    If OraDatabase.LastServerErr <> 0 Then
                        MsgBox "Error in retrieving data from Cargo_Activity.  Transaction rollbacked. "
                        OraSession.Rollback
                        Exit Sub
                    End If
                   
                    If Not IsNull(dsCARGO_ACTIVITY_SUM.fields("SUMCHANGE").Value) Then
                        dQtyInHouseToday = dsCARGO_TRACKING.fields("QTY_RECEIVED").Value - dsCARGO_ACTIVITY_SUM.fields("SUMCHANGE").Value
                    Else
                        dQtyInHouseToday = dsCARGO_TRACKING.fields("QTY_RECEIVED").Value
                    End If
                    
                    'Check if qty change is > 0, if so print out this record as possible warehouse receipt
                    If dQtyInHouseToday <= 0 Then
                        'Update CARGO_TRACKING
                        dsCARGO_TRACKING.Edit
                        dsCARGO_TRACKING.fields("WHSE_RCPT_NUM").Value = -1
                        dsCARGO_TRACKING.fields("QTY_IN_STORAGE").Value = dQtyInHouseToday
                        
                        If blnTransferred = True Then
                            dsCARGO_TRACKING.fields("STORAGE_END").Value = dStorageDate
                        End If
                        
                        dsCARGO_TRACKING.Update
                    Else
                        'Get from Vessel Profile table based on LR Num
                        sSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '" & dsCARGO_MANIFEST.fields("LR_NUM").Value & "'"
                        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sSqlStmt, 0&)
     
                        'Get from Commodity table based on Commodity Code
                        sSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value
                        Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(sSqlStmt, 0&)
                    
                        'Get from Customer table based on Customer Code
                        sSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & dsCARGO_TRACKING.fields("STORAGE_CUST_ID").Value
                        Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sSqlStmt, 0&)
                        
                        'Get from Customer table based on Customer Code
                        sSqlStmt = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE = '" & dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value & "'"
                        Set dsCOUNTRY = OraDatabase.dbcreatedynaset(sSqlStmt, 0&)
                     
                        'Check to see if we need to change the receipt and headings on the page
                        If (dOldStorageDate <> dStorageDate) _
                            Or (iOldCustomerId <> dsCARGO_TRACKING.fields("STORAGE_CUST_ID").Value) _
                            Or (iOldLRNum <> dsCARGO_MANIFEST.fields("LR_NUM").Value) _
                            Or (iOldCommodityCode <> dsCARGO_TRACKING.fields("COMMODITY_CODE").Value) Then
                            iNewWhseRcpt = True
                            iOldCustomerId = dsCARGO_TRACKING.fields("STORAGE_CUST_ID").Value
                            iOldCommodityCode = dsCARGO_TRACKING.fields("COMMODITY_CODE").Value
                            iOldLRNum = dsCARGO_MANIFEST.fields("LR_NUM").Value
                            dOldStorageDate = dStorageDate
                        Else
                            iNewWhseRcpt = False
                        End If
                        
                        'Change the receipt and print the header, first time it will definitely go inside
                        If iNewWhseRcpt Then
                            'Do not print total line first time
                            If Not iFirstTime Then
                                For i = 1 To 40 - (iNumOfLines)
                                    Print #giPrintFileNum, ""
                                Next i
                                Print #giPrintFileNum, Tab(5); dTotalQty; Tab(60); Format(dTotalWeight, "##,###,###,##0.00")
                                For i = 1 To 14
                                    Print #giPrintFileNum, ""
                                Next i
                            End If
                            
                            iFirstTime = False
                            iPageNbr = 1
                            
                            'Get the maximum Warehouse receipt number and add 1
                            sSqlStmt = "SELECT MAX(WHSE_RCPT_NUM) MAX_NUM FROM CARGO_TRACKING"
                            Set dsCARGO_TRACKING_MAX = OraDatabase.dbcreatedynaset(sSqlStmt, 0&)
                            lWhseRcptNum = dsCARGO_TRACKING_MAX.fields("MAX_NUM").Value + 1
                            
                            'Line 1
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, Tab(50); "WHR # : "; lWhseRcptNum
                            Print #giPrintFileNum, Tab(50); "DATE : "; dStorageDate
                            Print #giPrintFileNum, Tab(50); "PAGE : "; iPageNbr
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            
                            'Line 10
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, Tab(25); dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
                            
                            If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value) Then
                                sCustomerAddr1 = dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value
                            Else
                                sCustomerAddr1 = ""
                            End If
                            
                            Print #giPrintFileNum, Tab(25); dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value
                            
                            If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) Then
                                sCustomerAddr2 = dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value
                            Else
                                sCustomerAddr2 = ""
                            End If
                            
                            If Trim$(sCustomerAddr2) <> "" Then
                                Print #giPrintFileNum, Tab(25); sCustomerAddr2
                            Else
                                Print #giPrintFileNum, ""
                            End If
                            
                            Print #giPrintFileNum, Tab(25); dsCUSTOMER_PROFILE.fields("CUSTOMER_CITY").Value; ", "; dsCUSTOMER_PROFILE.fields("CUSTOMER_STATE").Value; " "; dsCUSTOMER_PROFILE.fields("CUSTOMER_ZIP").Value
                            
                            If dsCOUNTRY.fields("COUNTRY_CODE").Value = "US" Then
                                Print #giPrintFileNum, ""
                            Else
                                Print #giPrintFileNum, Tab(25); dsCOUNTRY.fields("COUNTRY_NAME")
                            End If
                            
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            
                            'Line 20
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, Tab(5); dsCOMMODITY_PROFILE.fields("COMMODITY_NAME"); Tab(30); dsVESSEL_PROFILE.fields("VESSEL_NAME");
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            
                            dTotalQty = 0
                            dTotalWeight = 0
                            iNumOfLines = 0
                        End If
                        
                        'Calculate weight left  - LFW, 1/7/03
                        DummyDebugString = dsCARGO_TRACKING.fields("LOT_NUM").Value
                        If DummyDebugString = "-902440668404" Then
                            DummyDebugString = DummyDebugString
                        End If
                        If dOriginalWeight <= 0 And dsCARGO_MANIFEST.fields("QTY_EXPECTED").Value > 0 Then
                            MsgBox ("Data Error: QTY IN House > 0 But Original Weight <= 0 for Lot #: " _
                                    & dsCARGO_TRACKING.fields("LOT_NUM").Value _
                                    & ".  Please Report to Technology Soluctions Department.")
                            OraSession.Rollback
                            Exit Sub
                        End If
                                  
                        'If the record is normal,
                        '   lQty1Rcvd is QTY received of the original owner, which won't change over time
                        '   dOriginalWeight is the sum of the weights of cargos with the same lr_num, commodity_code, bol, and with mark ending with the mark of the original one.
                        'Otherwise,
                        '   lQty1Rcvd is its own QTY received and
                        '   dOriginalWeight is its own weight.
                        dLoadedWeight = dQtyInHouseToday * dOriginalWeight / lQty1Rcvd
                        dTotalQty = dTotalQty + dQtyInHouseToday
                        dTotalWeight = dTotalWeight + dLoadedWeight
                        
                        'Update CARGO_TRACKING
                        dsCARGO_TRACKING.Edit
                        dsCARGO_TRACKING.fields("WHSE_RCPT_NUM").Value = lWhseRcptNum
                        dsCARGO_TRACKING.fields("QTY_IN_STORAGE").Value = dQtyInHouseToday
                        
                        If blnTransferred = True Then
                            dsCARGO_TRACKING.fields("STORAGE_END").Value = dStorageDate
                        End If
                        
                        dsCARGO_TRACKING.Update
                        
                        'Print the detail lines
                        Print #giPrintFileNum, Tab(5); dQtyInHouseToday; Tab(11); dsCARGO_MANIFEST.fields("QTY1_UNIT").Value; Tab(18); dsCARGO_MANIFEST.fields("CARGO_BOL").Value; Tab(26); dsCARGO_MANIFEST.fields("CARGO_MARK").Value; Tab(60); Format(dLoadedWeight, "##,###,###,##0.00"); Tab(72); dsCARGO_MANIFEST.fields("CARGO_LOCATION").Value
                        
                        'Check if we need to break the page
                        If iNumOfLines <= 22 Then
                            iNumOfLines = iNumOfLines + 1
                        Else
                            'Go to next page
                            iPageNbr = iPageNbr + 1
                            For j = 1 To 16
                                Print #giPrintFileNum, ""
                            Next j
                        
                            'Line 1
                            Print #giPrintFileNum, Tab(50); "WHR # : "; lWhseRcptNum
                            Print #giPrintFileNum, Tab(50); "DATE : "; dStorageDate
                            Print #giPrintFileNum, Tab(50); "PAGE : "; iPageNbr
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            
                            'Line 10
                            Print #giPrintFileNum, Tab(25); dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
                            If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value) Then
                                sCustomerAddr1 = dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value
                            Else
                                sCustomerAddr1 = ""
                            End If
                            Print #giPrintFileNum, Tab(25); dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value
                            If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) Then
                                sCustomerAddr2 = dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value
                            Else
                                sCustomerAddr2 = ""
                            End If
                            If Trim$(sCustomerAddr2) <> "" Then
                                Print #giPrintFileNum, Tab(25); sCustomerAddr2
                            Else
                                Print #giPrintFileNum, ""
                            End If
                            Print #giPrintFileNum, Tab(25); dsCUSTOMER_PROFILE.fields("CUSTOMER_CITY").Value; ", "; dsCUSTOMER_PROFILE.fields("CUSTOMER_STATE").Value; " "; dsCUSTOMER_PROFILE.fields("CUSTOMER_ZIP").Value
                            If dsCOUNTRY.fields("COUNTRY_CODE").Value = "US" Then
                                Print #giPrintFileNum, ""
                            Else
                                Print #giPrintFileNum, Tab(25); dsCOUNTRY.fields("COUNTRY_NAME")
                            End If
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, Tab(5); dsCOMMODITY_PROFILE.fields("COMMODITY_NAME"); Tab(30); dsVESSEL_PROFILE.fields("VESSEL_NAME");
                                                                            
                            'Line 20
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            Print #giPrintFileNum, ""
                            
                            iNumOfLines = 0
                        End If
                    End If
                End If
            End If
            
            dsCARGO_TRACKING.MoveNext
        Wend
    End If
    
    'Print the last total
    For i = 1 To 40 - (iNumOfLines)
       Print #giPrintFileNum, ""
    Next i
    Print #giPrintFileNum, Tab(5); dTotalQty; Tab(60); Format(dTotalWeight, "##,###,###,##0.00")

    'Close the print file
    Close giPrintFileNum
        
    'Print the file on the printer
    giPrintFileNum = FreeFile()
    Open App.Path & "\WHSERCPT.PRN" For Input As giPrintFileNum
    While Not EOF(giPrintFileNum)
        Line Input #giPrintFileNum, sLine
        Printer.Print sLine
    Wend
    Printer.EndDoc
    Close giPrintFileNum
    
    If OraDatabase.LastServerErr = 0 Then
        MsgBox "Warehouse Receipts have been generated successfully!"
        OraSession.CommitTrans
    Else
        MsgBox "Error in accessing database.  Transaction rollbacked."
        OraSession.Rollback
    End If
    
    Unload Me
End Sub

Private Sub exit_Click()
    Unload Me
End Sub

Private Sub Form_Load()
    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)

    If OraDatabase.LastServerErr = 0 Then
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        Unload Me
    End If
       
    On Error GoTo 0
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    On Error GoTo 0
End Sub

 
