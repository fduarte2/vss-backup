VERSION 5.00
Begin VB.Form frmMiscBill 
   Appearance      =   0  'Flat
   BackColor       =   &H00C0C000&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Storage Bill"
   ClientHeight    =   3135
   ClientLeft      =   2535
   ClientTop       =   1740
   ClientWidth     =   5190
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   3135
   ScaleWidth      =   5190
   Begin VB.TextBox Text2 
      Height          =   345
      Left            =   2370
      TabIndex        =   0
      Top             =   450
      Width           =   1395
   End
   Begin VB.TextBox Text1 
      Height          =   345
      Left            =   2370
      TabIndex        =   1
      Top             =   870
      Width           =   1395
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Generate Bill"
      Height          =   315
      Left            =   1740
      TabIndex        =   3
      Top             =   1800
      Width           =   1335
   End
   Begin VB.Label Label3 
      BackColor       =   &H80000018&
      Caption         =   "Note: The dates are inclusive"
      Height          =   255
      Left            =   1200
      TabIndex        =   6
      Top             =   1350
      Width           =   2535
   End
   Begin VB.Label Label2 
      BackColor       =   &H00FFFF00&
      BorderStyle     =   1  'Fixed Single
      Caption         =   "Start Date"
      Height          =   345
      Left            =   1170
      TabIndex        =   5
      Top             =   420
      Width           =   1005
   End
   Begin VB.Label Label1 
      BackColor       =   &H00FFFF00&
      BorderStyle     =   1  'Fixed Single
      Caption         =   "Cut Off Date"
      Height          =   345
      Left            =   1170
      TabIndex        =   4
      Top             =   870
      Width           =   1005
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   285
      Left            =   150
      TabIndex        =   2
      Top             =   2350
      Width           =   4545
   End
End
Attribute VB_Name = "frmMiscBill"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Private Sub cmdSave_Click()
    Dim i As Integer
    Dim lRecCount As Long
    Dim lStartBillNum As Long
    Dim lEndBillNum As Long
    
    Dim dServiceRate As Double
    Dim dFinalWeight As Double
    Dim dWithdrawnWeight As Double
    Dim dWithdrawnQty As Double
    Dim dOriginalWeight As Double
    Dim dQtyInStorage As Double
    Dim lQty1Rcvd As Double

    Dim bGenerateBill As Boolean
    Dim txtUnit As String
    
    Dim BillingQty As Double
    Dim BillingAmount As Double
    Dim BillingUnit As String
    Dim StorageStartDate As Date
    Dim StorageEndDate As Date
    Dim ConversionFactor As Double
    Dim InfoString As String
    Dim iNumDays As Integer
    Dim iRate As Double
    Dim iFound As Boolean
    
    Dim sMark As String
    Dim sMarkRcvd As String
    Dim iComm As Integer
    Dim iCust As Integer
    Dim iOrigCust As Integer
    Dim ithTransfer As String
    Dim blnNormal As Boolean
    Dim blnTransferred As Boolean
    Dim blnMakeSurcharge As Boolean
    Dim sDesc As String
    Dim sLocation As String
    
    If Not IsDate(Trim$(Text1.Text)) Or Not IsDate(Trim$(Text2.Text)) Then
        MsgBox "Incorrect Format for Date"
        Exit Sub
    End If
    
'   No table locking is neccessary  -- LFW, 7/29/03
    'Lock all the required tables in exclusive mode, try 10 times
    'On Error Resume Next
'    For i = 0 To 9
'        OraDatabase.LastServerErrReset
'        gsSqlStmt = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
'        lRecCount = OraDatabase.ExecuteSQL(gsSqlStmt)
'        If OraDatabase.LastServerErr = 0 Then Exit For
'    Next 'i
    
'    If OraDatabase.LastServerErr <> 0 Then
'        OraDatabase.LastServerErr
'        MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
'        Exit Sub
'    End If
    
    On Error GoTo errHandler
    
    lStartBillNum = 0
    lEndBillNum = 0
            
    'Begin a transaction
    OraSession.BeginTrans
    
    
    '' Commented out by pwu 6/2/2006
    '' ---------------------------------------------------------------------------------------------
    '' ---------------------------------------------------------------------------------------------
    gsSqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
    Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
        If IsNull(dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value) Then
            glBillingNum = 1
        Else
            glBillingNum = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
        End If
    Else
        glBillingNum = 1
    End If

    lStartBillNum = glBillingNum

    gsSqlStmt = "SELECT * FROM BILLING"
    Set dsBILLING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

    'First Phase: create storage bills for cargo already in storage

    'Note Storage End date is really Storage Rent Ending Date
    'If cargo is still at the Port a day after the rent ended, a bill is generated for the next storage duration
    'The cut off dates are dates for which bills must be generated

    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE QTY_RECEIVED > 0 AND " _
                & "WHSE_RCPT_NUM > 0 AND COMMODITY_CODE NOT IN (1272, 6172, 8101) " _
                & "AND STORAGE_END >= TO_DATE('" & Text2.Text & "', 'MM/DD/YYYY') " _
                & "AND STORAGE_END <= TO_DATE('" & Text1.Text & "', 'MM/DD/YYYY') " _
                & "ORDER BY STORAGE_END"
    Set dsCARGO_TRACKING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

    Do While Not dsCARGO_TRACKING.EOF

        'Obtain location from cargo manifest
        gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = " & dsCARGO_TRACKING.fields("LOT_NUM").Value
        Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

        'Error in Cargo Manifest Record
        If OraDatabase.LastServerErr <> 0 Or dsCARGO_MANIFEST.RecordCount <= 0 Then
            MsgBox "Error in Cargo Manifest Record for Lot Number = " & dsCARGO_TRACKING.fields("LOT_NUM").Value
            OraSession.RollBack
            Exit Sub
        End If

        sLocation = UCase(Trim$(dsCARGO_MANIFEST.fields("CARGO_LOCATION").Value))
        InfoString = "SHIP: " & Trim$(dsCARGO_MANIFEST.fields("LR_NUM").Value) & ", OWNER: " & Trim$(dsCARGO_MANIFEST.fields("RECIPIENT_ID").Value) & ", COMM: " & Trim$(dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value) & ", BOL: " & Trim$(dsCARGO_MANIFEST.fields("CARGO_BOL").Value) & ", MARK: " & Trim$(dsCARGO_MANIFEST.fields("CARGO_MARK").Value)

        'Obtain service code from location category
        gsSqlStmt = "SELECT * FROM LOCATION_CATEGORY WHERE LOCATION_TYPE = '" & sLocation & "'"
        Set dsLOCATION_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

        'Error in Location Category Record
        If OraDatabase.LastServerErr <> 0 Or dsLOCATION_CATEGORY.RecordCount <= 0 Then
            MsgBox "Error in Location Code." & InfoString
            OraSession.RollBack
            Exit Sub
        End If

        'Test Storage Cust Id
        If IsNull(dsCARGO_TRACKING.fields("STORAGE_CUST_ID")) Then
            MsgBox "Storage Customer ID is NULL" & InfoString
            OraSession.RollBack
            Exit Sub
        End If

        'LR Num
        If IsNull(dsCARGO_MANIFEST.fields("LR_NUM")) Then
            MsgBox "LR NUM is NULL" & InfoString
            OraSession.RollBack
            Exit Sub
        End If

        'Commodity Code
        If IsNull(dsCARGO_TRACKING.fields("COMMODITY_CODE")) Then
            MsgBox "Commodity Code is NULL" & InfoString
            OraSession.RollBack
            Exit Sub
        End If

        'Check whether this is transferred Argentine juice that should get storage surcharge
        'This part is the same as that of the 2nd phase
        '1) This is only for commodity 5031, 5032, 5033 and 5098
        '2) No surcharge should be made on transfers within the JDS group
        '   (customer 135-AL-CON, 321-COMMODITY FINANCE.COM CAYMAN,
        '    431-D.O.C. FINANCE S.A., 1032-J.D.S., 1035-JUGOS DEL SUR (USA) INC.,
        '    1607-PAMPA CONCENTRATES, 1964-SOWER S.A.
        '3) No surcharge should be made on transfers within the Amerimark group
        '   (Customer 179-Amerimark and 2008-Trillenium)
        '4) No surcharge should be issued if the transfer is not a transfer of ownership.
        '   i.e., it was made from a customer to itself  -- LFW, 2/4/04, Per Antonia and Lisa T.
        '5) In the case we need to make the surcharge, we charge $7/plt/30 days surcharge
        '   and the storage charge is also changed from $4.5/plt/15 days to $9.0/plt/30 days
        '   that makes a total of $16/plt/30 days
        iComm = Val("" & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value)
        sMark = Trim("" & dsCARGO_MANIFEST.fields("CARGO_MARK").Value)
        iCust = Val("" & dsCARGO_MANIFEST.fields("RECIPIENT_ID").Value)

        If Val("" & InStr(sMark, "TR*")) = 1 Then
            blnTransferred = True
        Else
            blnTransferred = False
        End If

        If iComm = 5031 Or iComm = 5032 Or iComm = 5033 Or iComm = 5098 Then
            If blnTransferred = True Then
                'Check to see whether it is a transfer of ownership
                gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = '" & dsCARGO_MANIFEST.fields("ORIGINAL_CONTAINER_NUM").Value & "'"
                Set dsCARGO_ORIGINAL_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                iOrigCust = Val("" & dsCARGO_ORIGINAL_MANIFEST.fields("RECIPIENT_ID").Value)

                gsSqlStmt = "SELECT * FROM CUSTOMER_GROUP_MEMBER WHERE CUSTOMER_ID "

                If (iCust = iOrigCust) Then
                    'Not transfer of ownership
                    blnMakeSurcharge = False
                ElseIf (iCust = 2008 And iOrigCust = 179) Or (iCust = 179 And iOrigCust = 2008) Then
                    'it is a transfer within the Amerimark Group
                    blnMakeSurcharge = False
                Else
                    'Check to see whether it a transfer within the JDS group
                    If iCust = 135 Or iCust = 321 Or iCust = 431 Or iCust = 1032 Or _
                       iCust = 1035 Or iCust = 1607 Or iCust = 1964 Or iCust = 400 Then
                        'Check to see whether the original owner are in the same group
                        If iOrigCust = 135 Or iOrigCust = 321 Or iOrigCust = 431 Or iOrigCust = 1032 Or _
                           iOrigCust = 1035 Or iOrigCust = 1607 Or iOrigCust = 1964 Or iOrigCust = 400 Then
                            blnMakeSurcharge = False
                        Else
                            blnMakeSurcharge = True
                        End If
                    Else
                        blnMakeSurcharge = True
                    End If
                End If
            Else
                blnMakeSurcharge = False
            End If
        Else
            blnMakeSurcharge = False
        End If

        BillingQty = 0
        BillingUnit = "NA"

        'Calculate QTY in Storage
        gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMCHANGE FROM CARGO_ACTIVITY WHERE LOT_NUM = "
        gsSqlStmt = gsSqlStmt & dsCARGO_TRACKING.fields("LOT_NUM").Value
        gsSqlStmt = gsSqlStmt & " AND DATE_OF_ACTIVITY <= TO_DATE("
        gsSqlStmt = gsSqlStmt & "'" & Trim$(Format(dsCARGO_TRACKING.fields("STORAGE_END").Value, "MM/DD/YYYY")) & "','MM/DD/YYYY')"
        Set dsCARGO_ACTIVITY_SUM = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

        dQtyInStorage = dsCARGO_TRACKING.fields("QTY_RECEIVED").Value
        If Not IsNull(dsCARGO_ACTIVITY_SUM.fields("SUMCHANGE").Value) Then
            dQtyInStorage = dsCARGO_TRACKING.fields("QTY_RECEIVED").Value - dsCARGO_ACTIVITY_SUM.fields("SUMCHANGE").Value
        End If

        'Determine Storage Start Date - read Storage End as Storage Billing End Date
        'Next Leg of Storage Picks up on the following day
        StorageStartDate = DateAdd("d", 1, dsCARGO_TRACKING.fields("STORAGE_END").Value)

        'Determine Billing QTY, Unit, Rate, Duration, etc. for making the billing record
        If blnMakeSurcharge Then
            BillingQty = dQtyInStorage
            BillingUnit = dsCARGO_MANIFEST.fields("QTY1_UNIT").Value
            ConversionFactor = 1
            StorageEndDate = DateAdd("d", 30, dsCARGO_TRACKING.fields("STORAGE_END").Value)
        Else


'        Commented out by pwu 6/1/2006
'        Replaced by an new sub routine
'            'Obtain rate from storage rate table
'            'Use the vessel specific rates if they exist.  Otherwise use the regular rates  - LFW, added on 1/22/03
'            gsSqlStmt = "SELECT * FROM STORAGE_RATE WHERE LR_NUM = " & dsCARGO_MANIFEST.fields("LR_NUM").Value & " AND " _
'                      & "SERVICE_CODE = " & dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value & " AND " _
'                      & "COMMODITY_CODE = " & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value & " " _
'                      & "ORDER BY START_DAY "
'
'            Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'
'            If OraDatabase.LastServerErr <> 0 Then
'                MsgBox "Database Error On Retrieving Storage Rate for " & InfoString
'                OraSession.Rollback
'                Exit Sub
'            ElseIf dsSTORAGE_RATE.RecordCount <= 0 Then
'                gsSqlStmt = "SELECT * FROM STORAGE_RATE WHERE SERVICE_CODE = " & dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value
'                gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value
'                gsSqlStmt = gsSqlStmt & " ORDER BY START_DAY "
'                Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'
'                'Error in Storage Rate
'                If OraDatabase.LastServerErr <> 0 Or dsSTORAGE_RATE.RecordCount <= 0 Then
'                    MsgBox "Error or Unavailable Storage Rate." & InfoString
'                    OraSession.Rollback
'                    Exit Sub
'                End If
'            End If

            iNumDays = 2 + DateDiff("d", dsCARGO_TRACKING.fields("FREE_TIME_END").Value, dsCARGO_TRACKING.fields("STORAGE_END").Value)
            Call findSRRecordset(dsCARGO_TRACKING.fields("STORAGE_CUST_ID").Value, _
                            dsCARGO_MANIFEST.fields("LR_NUM").Value, _
                            dsCARGO_TRACKING.fields("COMMODITY_CODE").Value, _
                            dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value, _
                            iNumDays)


            'Test Service Code
            If IsNull(dsSTORAGE_RATE.fields("SERVICE_CODE")) Then
                MsgBox "Storage Rate-Service Code is NULL" & InfoString
                OraSession.RollBack
                Exit Sub
            End If

            'Test Storage Rate
            If IsNull(dsSTORAGE_RATE.fields("RATE")) Then
                MsgBox "Storage Rate is NULL" & InfoString
                OraSession.RollBack
                Exit Sub
            End If

            'Determine whether storage rate is based on weight or quantity
            gsSqlStmt = "SELECT * FROM UNITS WHERE UOM = '" & dsSTORAGE_RATE.fields("UNIT").Value & "'"
            Set dsUNITS = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

            'Error in UOM
            If OraDatabase.LastServerErr <> 0 Or dsUNITS.RecordCount <= 0 Then
                MsgBox "Error or Unavailable Unit Of Measure." & InfoString
                OraSession.RollBack
                Exit Sub
            End If

            'If Based on Quantity, pick up qty that went into storage
            If Trim$(dsUNITS.fields("UOM_TYPE").Value) = "E" Then
                'Error in Qty1 Unit Rate
                If IsNull(dsCARGO_MANIFEST.fields("QTY1_UNIT")) Then
                    MsgBox "QTY1 UNIT is NULL." & InfoString
                    OraSession.RollBack
                    Exit Sub
                End If

                BillingQty = dQtyInStorage
                BillingUnit = dsCARGO_MANIFEST.fields("QTY1_UNIT").Value
                ConversionFactor = 1
            End If
            
            'If Based on Volume --- TEMPORARY ADDITION --- if the "bill by unit" = the QTY2, grab QTY2.  If not, return error.
            'Better logic involving volumetric conversion will come at a time when it becomes necessary.
            If Trim$(dsUNITS.fields("UOM_TYPE").Value) = "V" Then
                If IsNull(dsCARGO_MANIFEST.fields("QTY2_UNIT").Value) = False And dsSTORAGE_RATE.fields("UNIT").Value = dsCARGO_MANIFEST.fields("QTY2_UNIT").Value Then
                    BillingQty = dsCARGO_MANIFEST.fields("QTY2_EXPECTED").Value * dQtyInStorage
                    BillingUnit = dsCARGO_MANIFEST.fields("QTY2_UNIT").Value
                    ConversionFactor = 1
                Else
                    MsgBox "Volumetric billing without Conversion detected.  Please contact TS." & InfoString
                    OraSession.RollBack
                    Exit Sub
                End If
            End If

            'If Based on Weight, pick up qty that went into storage and convert it into lbs  - LFW, 1/7/03
            If Trim$(dsUNITS.fields("UOM_TYPE").Value) = "W" Then
                'Get the original qty received, weight, assuming it is not transferred cargo
                lQty1Rcvd = Val("" & dsCARGO_TRACKING.fields("QTY_RECEIVED").Value)
                dOriginalWeight = Val("" & dsCARGO_MANIFEST.fields("CARGO_WEIGHT").Value)

                ' Treat commodity 4963-Futter lumber differently  - LFW, 10/1/03
                ' Assuming that there is no transfers that affect weight calculation
                If dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value = 4963 Then
                    BillingQty = dQtyInStorage / lQty1Rcvd * dOriginalWeight
                    BillingUnit = "MBF"
                    ConversionFactor = 1
                Else
                'Start of Weight Calculation  -LFW, updated on 1/8/02
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

                            'If there is only one record of the cargo of the original owner, get the quantity received
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

                    BillingQty = dQtyInStorage / lQty1Rcvd * dOriginalWeight
                    BillingUnit = "LB"

                    'The rate might be for a different unit.  Determine the rate conversion
                    gsSqlStmt = "SELECT * FROM UNIT_CONVERSION WHERE PRIMARY_UOM = 'LB' AND SECONDARY_UOM = '" & dsSTORAGE_RATE.fields("UNIT").Value & "'"
                    Set dsUNIT_CONVERSION = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                    If OraDatabase.LastServerErr <> 0 Or dsUNIT_CONVERSION.RecordCount <= 0 Then
                        MsgBox "No Conversion available from LB to Storage Rate Unit." & InfoString
                        OraSession.RollBack
                        Exit Sub
                    End If

                    ConversionFactor = dsUNIT_CONVERSION.fields("CONVERSION_FACTOR")
                End If
            End If

            'Determine Storage End Date
            If IsNull(dsSTORAGE_RATE.fields("DURATION_UNIT")) Then
                MsgBox "Storage Rate has NULL DURATION_UNIT." & InfoString
                OraSession.RollBack
                Exit Sub
            End If

            If (Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "MO") Or (Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "DAY") Then
                'Ok
            Else
                MsgBox "Storage Rate DURATION_UNIT is incorrect.  It should be either MO or DAY." & InfoString
                OraSession.RollBack
                Exit Sub
            End If

            'If based on month then add 1 month to the date
            If Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "MO" Then
                StorageEndDate = DateAdd("m", dsSTORAGE_RATE.fields("DURATION").Value, dsCARGO_TRACKING.fields("STORAGE_END").Value)
            End If

            'If based on day then add number of days to date
            If Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "DAY" Then
                StorageEndDate = DateAdd("d", dsSTORAGE_RATE.fields("DURATION").Value, dsCARGO_TRACKING.fields("STORAGE_END").Value)
            End If
        End If

        'insert into billing table
        If BillingQty > 0 Then

            'Calculate service amount
            If blnMakeSurcharge Then
                'rate is $9 (storage) + $7 (surcharge) per plt per 30 days
                dServiceRate = 16
                sDesc = "STORAGE WITH SURCHARGE @ $16 / PLT / 30 DAYS"
            Else
                dServiceRate = dsSTORAGE_RATE.fields("RATE").Value
                sDesc = "ON-GOING STORAGE"
            End If

            BillingAmount = BillingQty * ConversionFactor * dServiceRate

            'Generate prebills only if the dollar amount is greater than 0
            'Commented out by pwu temporatorily 6/1/2006
            If BillingAmount > 0 Then
                dsBILLING.AddNew
                dsBILLING.fields("CUSTOMER_ID").Value = dsCARGO_TRACKING.fields("STORAGE_CUST_ID").Value
                dsBILLING.fields("SERVICE_CODE").Value = dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value
                dsBILLING.fields("LOT_NUM").Value = dsCARGO_TRACKING.fields("LOT_NUM").Value
                dsBILLING.fields("ACTIVITY_NUM").Value = 1
                dsBILLING.fields("BILLING_NUM").Value = glBillingNum
                dsBILLING.fields("EMPLOYEE_ID").Value = 4
                dsBILLING.fields("SERVICE_START").Value = StorageStartDate
                dsBILLING.fields("SERVICE_STOP").Value = StorageEndDate
                dsBILLING.fields("SERVICE_AMOUNT").Value = BillingAmount
                dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
                dsBILLING.fields("SERVICE_DESCRIPTION").Value = sDesc
                dsBILLING.fields("SERVICE_RATE").Value = dServiceRate
                dsBILLING.fields("LR_NUM").Value = dsCARGO_MANIFEST.fields("LR_NUM").Value
                dsBILLING.fields("ARRIVAL_NUM").Value = 1
                dsBILLING.fields("COMMODITY_CODE").Value = dsCARGO_TRACKING.fields("COMMODITY_CODE").Value
                dsBILLING.fields("INVOICE_NUM").Value = 0
                dsBILLING.fields("SERVICE_DATE").Value = StorageStartDate
                dsBILLING.fields("SERVICE_QTY").Value = BillingQty
                dsBILLING.fields("SERVICE_NUM").Value = 1
                dsBILLING.fields("THRESHOLD_QTY").Value = 0
                dsBILLING.fields("LEASE_NUM").Value = 0
                dsBILLING.fields("SERVICE_UNIT").Value = BillingUnit
                dsBILLING.fields("LABOR_RATE_TYPE").Value = ""
                dsBILLING.fields("LABOR_TYPE").Value = ""
                dsBILLING.fields("PAGE_NUM").Value = 1
                dsBILLING.fields("CARE_OF").Value = "Y"
                dsBILLING.fields("BILLING_TYPE").Value = "STORAGE"

                'Added for Asset Coding 06.14.2001 LJG
                ''Dim dsAssetProfile As Object

                'Ignore box # for WING C1 - C8 and WING E1 - E8, treat them as WING C and WING E  -- LFW, 10/16/03 and 11/18/03 Per Antonia
                If InStr(sLocation, "WING C") > 0 Then
                    gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING C'"
                ElseIf InStr(sLocation, "WING D") > 0 Then
                    gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING D'"
                ElseIf InStr(sLocation, "WING E") > 0 Then
                    gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING E'"
                Else
                    gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = '" & sLocation & "' "
                End If

                Set dsAssetProfile = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

                If dsAssetProfile.RecordCount = 0 Then
                     MsgBox " Please Add ASSET_CODE for & '" & sLocation & "' & to ASSET_PROFILE.", vbInformation, "Save"
                Else
                    dsBILLING.fields("ASSET_CODE").Value = dsAssetProfile.fields("ASSET_CODE").Value
                End If

                dsBILLING.Update

                'Update Storage End Date
                'which is the last day for which storage had been billed
                dsCARGO_TRACKING.Edit
                dsCARGO_TRACKING.fields("STORAGE_END").Value = StorageEndDate
                dsCARGO_TRACKING.Update

                glBillingNum = glBillingNum + 1
            End If

            If OraDatabase.LastServerErr <> 0 Then
                'Rollback transaction
                MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
                OraSession.RollBack
                Exit Sub
            End If
        End If
        dsCARGO_TRACKING.DbMoveNext
    Loop
    '' ---------------------------------------------------------------------------------------------
    '' ---------------------------------------------------------------------------------------------
    '' End of first phase
    
    
    '' ***********************************************************************************************
    '' ***********************************************************************************************
    'Second Phase - After On-going Storage is Billed.

'   No table locking is neccessary  -- LFW, 7/29/03
    'Lock all the required tables in exclusive mode, try 10 times
    'On Error Resume Next
'    For i = 0 To 9
'        OraDatabase.LastServerErrReset
'        gsSqlStmt = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
'        lRecCount = OraDatabase.ExecuteSQL(gsSqlStmt)
'        If OraDatabase.LastServerErr = 0 Then Exit For
'        Next 'i

'    If OraDatabase.LastServerErr <> 0 Then
'        OraDatabase.LastServerErr
'        MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
'        Exit Sub
'    End If


    gsSqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
    Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
        If IsNull(dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value) Then
            glBillingNum = 1
        Else
            glBillingNum = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
        End If
    Else
        glBillingNum = 1
    End If

    gsSqlStmt = "SELECT * FROM BILLING"
    Set dsBILLING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

    'Create billing records for the newly created warehouse receipts
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE QTY_RECEIVED > 0 AND " _
              & "WHSE_RCPT_NUM > 0 AND FREE_TIME_END IS NOT NULL AND " _
              & "FREE_TIME_END <= TO_DATE('" & Text1.Text & "', 'MM/DD/YYYY') AND " _
              & "STORAGE_END IS NULL AND COMMODITY_CODE NOT IN (1272, 6172, 8101)"
    Set dsCARGO_TRACKING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

    Do While Not dsCARGO_TRACKING.EOF

        'Obtain location from cargo manifest
        gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = " & dsCARGO_TRACKING.fields("LOT_NUM").Value
        Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

        'Error in Cargo Manifest Record
        If OraDatabase.LastServerErr <> 0 Or dsCARGO_MANIFEST.RecordCount <= 0 Then
            MsgBox "Error in Cargo Manifest Record for Lot Number = " & dsCARGO_TRACKING.fields("LOT_NUM").Value
            OraSession.RollBack
            Exit Sub
        End If

        sLocation = UCase(Trim$(dsCARGO_MANIFEST.fields("CARGO_LOCATION").Value))
        InfoString = "SHIP: " & Trim$(dsCARGO_MANIFEST.fields("LR_NUM").Value) & ", OWNER: " & Trim$(dsCARGO_MANIFEST.fields("RECIPIENT_ID").Value) & ", COMM: " & Trim$(dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value) & ", BOL: " & Trim$(dsCARGO_MANIFEST.fields("CARGO_BOL").Value) & ", MARK: " & Trim$(dsCARGO_MANIFEST.fields("CARGO_MARK").Value)

        'Obtain service code from location category
        gsSqlStmt = "SELECT * FROM LOCATION_CATEGORY WHERE LOCATION_TYPE = '" & sLocation & "'"
        Set dsLOCATION_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

        'Error in Location Category Record
        If OraDatabase.LastServerErr <> 0 Or dsLOCATION_CATEGORY.RecordCount <= 0 Then
            MsgBox "Error in Location Code." & InfoString
            OraSession.RollBack
            Exit Sub
        End If

        'Test Storage Cust Id
        If IsNull(dsCARGO_TRACKING.fields("STORAGE_CUST_ID")) Then
            MsgBox "Storage Customer ID is NULL" & InfoString
            OraSession.RollBack
            Exit Sub
        End If

        'LR Num
        If IsNull(dsCARGO_MANIFEST.fields("LR_NUM")) Then
            MsgBox "LR NUM is NULL" & InfoString
            OraSession.RollBack
            Exit Sub
        End If

        'Commodity Code
        If IsNull(dsCARGO_TRACKING.fields("COMMODITY_CODE")) Then
            MsgBox "Commodity Code is NULL" & InfoString
            OraSession.RollBack
            Exit Sub
        End If

        'Check whether this is transferred Argentine juice that should get storage surcharge
        'This part is the same as that of the 1st phase
        '1) This is only for commodity 5031, 5032, 5033 and 5098
        '2) No surcharge should be made on transfers within the JDS group
        '   (customer 135-AL-CON, 321-COMMODITY FINANCE.COM CAYMAN,
        '    431-D.O.C. FINANCE S.A., 1032-J.D.S., 1035-JUGOS DEL SUR (USA) INC.,
        '    1607-PAMPA CONCENTRATES, 1964-SOWER S.A.
        '3) No surcharge should be made on transfers within the Amerimark group
        '   (Customer 179-Amerimark and 2008-Trillenium)
        '4) No surcharge should be issued if the transfer is not a transfer of ownership.
        '   i.e., it was made from a customer to itself  -- LFW, 2/4/04, Per Antonia and Lisa T.
        '5) In the case we need to make the surcharge, we charge $7/plt/30 days surcharge
        '   and the storage charge is also changed from $4.5/plt/15 days to $9.0/plt/30 days
        '   that makes a total of $16/plt/30 days
        iComm = Val("" & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value)
        sMark = Trim("" & dsCARGO_MANIFEST.fields("CARGO_MARK").Value)
        iCust = Val("" & dsCARGO_MANIFEST.fields("RECIPIENT_ID").Value)

        If Val("" & InStr(sMark, "TR*")) = 1 Then
            blnTransferred = True
        Else
            blnTransferred = False
        End If

        If iComm = 5031 Or iComm = 5032 Or iComm = 5033 Or iComm = 5098 Then
            If blnTransferred = True Then
                'Check to see whether it is a transfer of ownership
                gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = '" & dsCARGO_MANIFEST.fields("ORIGINAL_CONTAINER_NUM").Value & "'"
                Set dsCARGO_ORIGINAL_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                iOrigCust = Val("" & dsCARGO_ORIGINAL_MANIFEST.fields("RECIPIENT_ID").Value)

                If (iCust = iOrigCust) Then
                    'Not transfer of ownership
                    blnMakeSurcharge = False
                ElseIf (iCust = 2008 And iOrigCust = 179) Or (iCust = 179 And iOrigCust = 2008) Then
                    'it is a transfer within the Amerimark Group
                    blnMakeSurcharge = False
                Else
                    'Check to see whether it a transfer within the JDS group
                    If iCust = 135 Or iCust = 321 Or iCust = 431 Or iCust = 1032 Or _
                       iCust = 1035 Or iCust = 1607 Or iCust = 1964 Or iCust = 400 Then
                        'Check to see whether the original owner are in the same group
                        If iOrigCust = 135 Or iOrigCust = 321 Or iOrigCust = 431 Or iOrigCust = 1032 Or _
                           iOrigCust = 1035 Or iOrigCust = 1607 Or iOrigCust = 1964 Or iCust = 400 Then
                            blnMakeSurcharge = False
                        Else
                            blnMakeSurcharge = True
                        End If
                    Else
                        blnMakeSurcharge = True
                    End If
                End If
            Else
                blnMakeSurcharge = False
            End If
        Else
            blnMakeSurcharge = False
        End If

        BillingQty = 0
        BillingUnit = "NA"
        dQtyInStorage = dsCARGO_TRACKING.fields("QTY_IN_STORAGE").Value

        'Determine Storage Start Date
        'Free time end should be later than date received, do the check just for a safe guard
        If (DateValue(dsCARGO_TRACKING.fields("DATE_RECEIVED").Value) <= DateValue(dsCARGO_TRACKING.fields("FREE_TIME_END").Value)) Then
            StorageStartDate = DateValue(dsCARGO_TRACKING.fields("FREE_TIME_END").Value)
        Else
            StorageStartDate = DateValue(dsCARGO_TRACKING.fields("DATE_RECEIVED").Value)
        End If

        'Determine Billing QTY, Unit, Rate, Duration, etc. for making the billing record
        'Storage charge with surcharge is billed on a 30-days base
        If blnMakeSurcharge Then
            BillingQty = dQtyInStorage
            BillingUnit = dsCARGO_MANIFEST.fields("QTY1_UNIT").Value
            ConversionFactor = 1
            StorageEndDate = DateAdd("d", 29, StorageStartDate)
        Else
'            'Obtain rate from storage rate table
'            'Use the vessel specific rates if they exist.  Otherwise use the regular rates  - LFW, added on 1/22/03
'            gsSqlStmt = "SELECT * FROM STORAGE_RATE WHERE LR_NUM = " & dsCARGO_MANIFEST.fields("LR_NUM").Value & " AND " _
'                      & "SERVICE_CODE = " & dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value & " AND " _
'                      & "COMMODITY_CODE = " & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value & " " _
'                      & "ORDER BY START_DAY "
'            Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'
'            If OraDatabase.LastServerErr <> 0 Then
'                MsgBox "Database Error On Retrieving Storage Rate for " & InfoString
'                OraSession.RollBack
'                Exit Sub
'            ElseIf dsSTORAGE_RATE.RecordCount <= 0 Then
'                gsSqlStmt = "SELECT * FROM STORAGE_RATE WHERE SERVICE_CODE = " & dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value _
'                         & " AND COMMODITY_CODE = " & dsCARGO_TRACKING.fields("COMMODITY_CODE").Value _
'                         & " ORDER BY START_DAY "
'                Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'
'                'Error in Storage Rate
'                If OraDatabase.LastServerErr <> 0 Or dsSTORAGE_RATE.RecordCount <= 0 Then
'                    MsgBox "Error or Unavailable Storage Rate." & InfoString
'                    OraSession.RollBack
'                    Exit Sub
'                End If
'            End If

            ''iNumDays = 2 + DateDiff("d", dsCARGO_TRACKING.fields("FREE_TIME_END").Value, dsCARGO_TRACKING.fields("STORAGE_END").Value)
            Call findSRRecordset(dsCARGO_TRACKING.fields("STORAGE_CUST_ID").Value, _
                            dsCARGO_MANIFEST.fields("LR_NUM").Value, _
                            dsCARGO_TRACKING.fields("COMMODITY_CODE").Value, _
                            dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value, _
                            -1)



            'Determine whether storage rate is based on weight or quantity
            gsSqlStmt = "SELECT * FROM UNITS WHERE UOM = '" & dsSTORAGE_RATE.fields("UNIT").Value & "'"
            Set dsUNITS = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

            'Error in UOM
            If OraDatabase.LastServerErr <> 0 Or dsSTORAGE_RATE.RecordCount <= 0 Then
                MsgBox "Error or Unavailable Unit Of Measure." & InfoString
                OraSession.RollBack
                Exit Sub
            End If

            'If Based on Quantity, pick up qty that went into storage
            If Trim$(dsUNITS.fields("UOM_TYPE").Value) = "E" Then
                'Error in Qty1 Unit or Qty in Storage for Warehouse Rcpt is Null
                If IsNull(dsCARGO_MANIFEST.fields("QTY1_UNIT")) Or IsNull(dsCARGO_TRACKING.fields("QTY_IN_STORAGE")) Then
                    MsgBox "QTY1 UNIT is NULL or QTY IN STORAGE of Whse Rcpt is Null." & InfoString
                    OraSession.RollBack
                    Exit Sub
                End If

                BillingQty = dQtyInStorage
                BillingUnit = dsCARGO_MANIFEST.fields("QTY1_UNIT").Value
                ConversionFactor = 1
            End If

            'If Based on Volume --- TEMPORARY ADDITION --- if the "bill by unit" = the QTY2, grab QTY2.  If not, return error.
            'Better logic involving volumetric conversion will come at a time when it becomes necessary.
            If Trim$(dsUNITS.fields("UOM_TYPE").Value) = "V" Then
                If IsNull(dsCARGO_MANIFEST.fields("QTY2_UNIT").Value) = False And dsSTORAGE_RATE.fields("UNIT").Value = dsCARGO_MANIFEST.fields("QTY2_UNIT").Value Then
                    BillingQty = dsCARGO_MANIFEST.fields("QTY2_EXPECTED").Value * dQtyInStorage
                    BillingUnit = dsCARGO_MANIFEST.fields("QTY2_UNIT").Value
                    ConversionFactor = 1
                Else
                    MsgBox "Volumetric billing without Conversion detected.  Please contact TS." & InfoString
                    OraSession.RollBack
                    Exit Sub
                End If
            End If

            'If Based on Weight, pick up qty that went into storage and convert it into lbs
            If Trim$(dsUNITS.fields("UOM_TYPE")) = "W" Then
                'Error in Qty in Storage
                If IsNull(dsCARGO_TRACKING.fields("QTY_IN_STORAGE")) Then
                    MsgBox "Null Values of QTY IN STORAGE in Cargo_Tracking. " & InfoString
                    OraSession.RollBack
                    Exit Sub
                End If

                'Get the original qty received, weight, assuming it is not transferred cargo
                lQty1Rcvd = Val("" & dsCARGO_TRACKING.fields("QTY_RECEIVED").Value)
                dOriginalWeight = Val("" & dsCARGO_MANIFEST.fields("CARGO_WEIGHT").Value)

                ' Treat commodity 4963-Futter lumber differently  - LFW, 10/1/03
                ' Assuming that there is no transfers that affect weight calculation
                If dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value = 4963 Then
                    BillingQty = dQtyInStorage / lQty1Rcvd * dOriginalWeight
                    BillingUnit = "MBF"
                    ConversionFactor = 1
                Else
                'Start of Weight Calculation
                    'Get the original qty received, weight and mark, assuming it is not transferred cargo  - LFW, 1/7/03
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

                    BillingQty = dQtyInStorage / lQty1Rcvd * dOriginalWeight
                    BillingUnit = "LB"
                'End of Weight Calculation

                    'The rate might be for a different unit.  Determine the rate conversion
                    gsSqlStmt = "SELECT * FROM UNIT_CONVERSION WHERE PRIMARY_UOM = 'LB' AND SECONDARY_UOM = '" & dsSTORAGE_RATE.fields("UNIT").Value & "'"
                    Set dsUNIT_CONVERSION = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

                    If OraDatabase.LastServerErr <> 0 Or dsUNIT_CONVERSION.RecordCount <= 0 Then
                        MsgBox "No Conversion available from LB to Storage Rate Unit." & InfoString
                        OraSession.RollBack
                        Exit Sub
                    End If

                    ConversionFactor = dsUNIT_CONVERSION.fields("CONVERSION_FACTOR")
                End If
            End If

            'Determine Storage End Date
            'If based on month then add 1 month to the date
            If IsNull(dsSTORAGE_RATE.fields("DURATION_UNIT")) Then
                MsgBox "Storage Rate has NULL DURATION_UNIT." & InfoString
                OraSession.RollBack
                Exit Sub
            End If

            If (Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "MO") Or (Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "DAY") Then
                'Ok
            Else
                MsgBox "Storage Rate DURATION_UNIT is incorrect.  It should be either MO or DAY." & InfoString
                OraSession.RollBack
                Exit Sub
            End If

            If Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "MO" Then
                StorageEndDate = DateAdd("d", -1, DateAdd("m", dsSTORAGE_RATE.fields("DURATION").Value, StorageStartDate))
            End If

            'If based on day then add number of days to date
            If Trim$(dsSTORAGE_RATE.fields("DURATION_UNIT").Value) = "DAY" Then
                StorageEndDate = DateAdd("d", dsSTORAGE_RATE.fields("DURATION").Value - 1, StorageStartDate)
            End If

            'Test Service Code
            If IsNull(dsSTORAGE_RATE.fields("SERVICE_CODE")) Then
                MsgBox "Storage Rate-Service Code is NULL" & InfoString
                OraSession.RollBack
                Exit Sub
            End If

            'Test Storage Rate
            If IsNull(dsSTORAGE_RATE.fields("RATE")) Then
                MsgBox "Storage Rate is NULL" & InfoString
                OraSession.RollBack
                Exit Sub
            End If
        End If

        'Calculate service amount
        If blnMakeSurcharge Then
            'rate is $9 (storage) + $7 (surcharge) per plt per 30 days
            dServiceRate = 16
            sDesc = "STORAGE WITH SURCHARGE @ $16 / PLT / 30 DAYS"
        Else
            dServiceRate = dsSTORAGE_RATE.fields("RATE").Value
            sDesc = "PUT INTO STORAGE"
        End If

        BillingAmount = BillingQty * ConversionFactor * dServiceRate

        'insert into billing table
        'Generate prebills only if the dollar amount is greater than 0
        'Commented out by pwu temporarily 6/1/2006
        If BillingAmount > 0 Then
            ''MsgBox "BillingAmount > 0"
            dsBILLING.AddNew
            dsBILLING.fields("CUSTOMER_ID").Value = dsCARGO_TRACKING.fields("STORAGE_CUST_ID").Value
            dsBILLING.fields("SERVICE_CODE").Value = dsLOCATION_CATEGORY.fields("STORAGE_SERVICE_CODE").Value
            dsBILLING.fields("LOT_NUM").Value = dsCARGO_TRACKING.fields("LOT_NUM").Value
            dsBILLING.fields("ACTIVITY_NUM").Value = 1
            dsBILLING.fields("BILLING_NUM").Value = glBillingNum
            dsBILLING.fields("EMPLOYEE_ID").Value = 4
            dsBILLING.fields("SERVICE_START").Value = StorageStartDate
            dsBILLING.fields("SERVICE_STOP").Value = StorageEndDate
            dsBILLING.fields("SERVICE_AMOUNT").Value = BillingAmount
            dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
            dsBILLING.fields("SERVICE_DESCRIPTION").Value = sDesc
            dsBILLING.fields("LR_NUM").Value = dsCARGO_MANIFEST.fields("LR_NUM").Value
            dsBILLING.fields("ARRIVAL_NUM").Value = 1
            dsBILLING.fields("COMMODITY_CODE").Value = dsCARGO_TRACKING.fields("COMMODITY_CODE").Value
            dsBILLING.fields("INVOICE_NUM").Value = 0
            dsBILLING.fields("SERVICE_DATE").Value = StorageStartDate
            dsBILLING.fields("SERVICE_QTY").Value = BillingQty
            dsBILLING.fields("SERVICE_NUM").Value = 1
            dsBILLING.fields("THRESHOLD_QTY").Value = 0
            dsBILLING.fields("LEASE_NUM").Value = 0
            dsBILLING.fields("SERVICE_UNIT").Value = BillingUnit
            dsBILLING.fields("SERVICE_RATE").Value = dServiceRate
            dsBILLING.fields("LABOR_RATE_TYPE").Value = ""
            dsBILLING.fields("LABOR_TYPE").Value = ""
            dsBILLING.fields("PAGE_NUM").Value = 1
            dsBILLING.fields("CARE_OF").Value = "Y"
            dsBILLING.fields("BILLING_TYPE").Value = "STORAGE"

            'Added for Asset Coding 06.14.2001 LJG

            'Ignore box # for WING C1 - C8 and WING E1 - E8, treat them as WING C and WING E  -- LFW, 10/16/03 and 11/18/03 Per Antonia
            If InStr(sLocation, "WING C") > 0 Then
                gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING C'"
            ElseIf InStr(sLocation, "WING D") > 0 Then
                gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING D'"
            ElseIf InStr(sLocation, "WING E") > 0 Then
                gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING E'"
            Else
                gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = '" & sLocation & "' "
            End If

            Set dsAssetProfile = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)

            If dsAssetProfile.RecordCount = 0 Then
                MsgBox "Please Add ASSET_CODE for & '" & sLocation & "' & to ASSET_PROFILE.", vbInformation, "Save"
            Else
                dsBILLING.fields("ASSET_CODE").Value = dsAssetProfile.fields("ASSET_CODE").Value
            End If

            dsBILLING.Update

            'Update Storage End Date
            dsCARGO_TRACKING.Edit
            dsCARGO_TRACKING.fields("STORAGE_END").Value = StorageEndDate
            dsCARGO_TRACKING.Update

            glBillingNum = glBillingNum + 1

        End If

        If OraDatabase.LastServerErr <> 0 Then
            'Rollback transaction
            MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
            OraSession.RollBack
            Exit Sub
        End If

        dsCARGO_TRACKING.DbMoveNext
    Loop

    '' End of 2nd Phase
    '' ******************************************************************************************
    '' ********************************************************************************************
    lEndBillNum = glBillingNum - 1
    
    'log to invoicedate table if generated storage bills
    If lEndBillNum >= lStartBillNum Then
    
        ''Commented out by pwu temparialy 6/1/2006
        Call AddNewInvDt("BNI Storage", CStr(lStartBillNum), CStr(lEndBillNum))
    End If
    
    If OraDatabase.LastServerErr <> 0 Then
        'Rollback transaction
        MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
        OraSession.RollBack
        Exit Sub
    Else
        'Commit transaction
        OraSession.CommitTrans
    End If
    
    Unload Me
    Exit Sub

errHandler:
     
    If OraDatabase.LastServerErr = 0 Then
         MsgBox "Error Occured. Unable to Process BNI Storage Prebills!", vbExclamation, "Storage Bill"
    Else
         MsgBox "Error " & OraDatabase.LastServerErrText & " occured." & vbCrLf & _
                "Unable to Process BNI Storage Prebills!", vbExclamation, "Storage Bill"
    End If
         
    OraSession.RollBack
    OraDatabase.LastServerErrReset
   
End Sub

Private Sub AddNewInvDt(sType As String, sStBillNo As String, sEdBillNo As String)
    Dim dsINVDATE As Object
    Dim dsID As Object
    Dim SqlStmt As String
    Dim DtID As Long
    
    SqlStmt = "SELECT MAX(ID) MAXID FROM INVOICEDATE"
    Set dsID = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsID.RecordCount > 0 Then
        If Not IsNull(dsID.fields("MAXID").Value) Then
            DtID = dsID.fields("MAXID").Value + 1
        Else
            DtID = 0
        End If
    Else
        DtID = 0
    End If
    
    SqlStmt = "SELECT * FROM INVOICEDATE"
    Set dsINVDATE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        With dsINVDATE
            .AddNew
            .fields("ID").Value = DtID
            .fields("RUN_DATE").Value = Format(Now, "MM/DD/YYYY HH:MM:SS")
            .fields("BILL_TYPE").Value = "B"
            .fields("TYPE").Value = sType
            .fields("START_INV_NO").Value = sStBillNo
            .fields("END_INV_NO").Value = sEdBillNo
            .Update
        End With
    Else
        MsgBox OraSession.LastServerErrText, vbInformation, "Storage Bill"
    End If
End Sub


Private Sub Command1_Click()

End Sub

Private Sub Form_Load()
    Dim i As Integer
   
    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    lblStatus.Caption = "Logging to database..."
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
'    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)

    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    Call ClearScreen
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0
    
End Sub

Public Sub ClearScreen()

End Sub

