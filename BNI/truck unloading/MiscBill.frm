VERSION 5.00
Begin VB.Form frmMiscBill 
   Appearance      =   0  'Flat
   BackColor       =   &H00C0C000&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Dock Receipts Bill"
   ClientHeight    =   2580
   ClientLeft      =   1500
   ClientTop       =   3450
   ClientWidth     =   4890
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   2580
   ScaleWidth      =   4890
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
      Top             =   1590
      Width           =   1335
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
      Top             =   2190
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
    Dim iBilled As Boolean
    Dim dService_Rate As Double
    Dim dFinalWeight As Double
    Dim dWithdrawnWeight As Double
    Dim dWithdrawnQty As Double
    Dim dOriginalQty As Double
    Dim dOriginalWeight As Double
    Dim bGenerateBill As Boolean
    Dim dServiceCode As Integer
    Dim txtUnit As String
    Dim NoRateError As String
    
    Dim BillingQty As Double
    Dim BillingAmt As Double
    Dim BillingUnit As String
    Dim StorageStartDate As Date
    Dim StorageEndDate As Date
    Dim ConversionFactor As Double
    Dim dQtyInStorage As Double
    
    Dim lStartBillNum As Long
    Dim lEndBillNum As Long
        
    If Not IsDate(Trim$(Text1.Text)) Or Not IsDate(Trim$(Text2.Text)) Then
        MsgBox "Incorrect Format for Date"
        Exit Sub
    End If
    
'Took away the lock since billing numbers do not have to be unique  -- LFW, 3/16/04
    'Lock all the required tables in exclusive mode, try 10 times
    'On Error Resume Next
'    For i = 0 To 9
'        OraDatabase.LastServerErrReset
'        gsSqlStmt = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
'        lRecCount = OraDatabase.ExecuteSQL(gsSqlStmt)
'        If OraDatabase.LastServerErr = 0 Then Exit For
'    Next 'i
'
'    If OraDatabase.LastServerErr <> 0 Then
'        OraDatabase.LastServerErr
'        MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
'        Exit Sub
'    End If
    
    On Error GoTo errHandler
                
    'Begin a transaction
    OraSession.BeginTrans
    
    gsSqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
    Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
        If IsNull(dsBILLING_MAX.Fields("MAX(BILLING_NUM)").Value) Then
            glBillingNum = 1
        Else
            glBillingNum = dsBILLING_MAX.Fields("MAX(BILLING_NUM)").Value + 1
        End If
    Else
        glBillingNum = 1
    End If
    
    lStartBillNum = glBillingNum
    lEndBillNum = 0
    
    gsSqlStmt = "SELECT * FROM BILLING"
    Set dsBILLING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    
    ' 29 Aug, 2010, Adam Walter
    ' Pre-emptive check to see if any "to be billed" lots aren't in the DOCK_RCPT_HANDLING_RATE table
    NoRateError = ""
    gsSqlStmt = "SELECT LOT_NUM, CM.COMMODITY_CODE FROM CARGO_MANIFEST CM, CARGO_TRACKING CT WHERE CONTAINER_NUM = LOT_NUM"
    gsSqlStmt = gsSqlStmt & " AND DATE_RECEIVED >= TO_DATE('" & Text2.Text & "', 'MM/DD/YYYY')"
    gsSqlStmt = gsSqlStmt & " AND DATE_RECEIVED <= TO_DATE('" & Text1.Text & "', 'MM/DD/YYYY')"
    gsSqlStmt = gsSqlStmt & " AND CARGO_MARK NOT LIKE 'TR*%*%' AND CARGO_MARK NOT LIKE '*TRNSF%'"
    gsSqlStmt = gsSqlStmt & " AND LR_NUM in (-1, 3, 4, 2) AND (BILL_DOCK_RCPT IS NULL OR BILL_DOCK_RCPT = 'Y')"
    gsSqlStmt = gsSqlStmt & " AND CM.COMMODITY_CODE NOT IN"
    gsSqlStmt = gsSqlStmt & " (SELECT COMMODITY_CODE FROM DOCK_RCPT_HANDLING_RATE DRHR WHERE CUSTOMER_ID IS NULL OR DRHR.CUSTOMER_ID = CT.OWNER_ID)"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    Do While Not dsSHORT_TERM_DATA.EOF
        NoRateError = NoRateError & dsSHORT_TERM_DATA.Fields("LOT_NUM").Value & " (Comm " & dsSHORT_TERM_DATA.Fields("COMMODITY_CODE").Value & ")" & vbCrLf
        dsSHORT_TERM_DATA.DbMoveNext
    Loop
    If NoRateError <> "" Then
        MsgBox "The following lots are showing as needing a bill, but have no Dock Receipt Handling Rate: " & vbCrLf & NoRateError & "Cancelling run; please either add a rate for these commodities, or mark the lots to not bill"
        Exit Sub
    End If
    
    'Added the check of cargo_mark to prevent customers with cargos transfered to from getting
    'Truck Unloading charges  - LFW, 12/17/2002
    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST, CARGO_TRACKING WHERE CONTAINER_NUM = LOT_NUM"
    gsSqlStmt = gsSqlStmt & " AND DATE_RECEIVED >= TO_DATE('" & Text2.Text & "', 'MM/DD/YYYY')"
    gsSqlStmt = gsSqlStmt & " AND DATE_RECEIVED <= TO_DATE('" & Text1.Text & "', 'MM/DD/YYYY')"
    gsSqlStmt = gsSqlStmt & " AND CARGO_MARK NOT LIKE 'TR*%*%' AND CARGO_MARK NOT LIKE '*TRNSF%'"
    gsSqlStmt = gsSqlStmt & " AND LR_NUM in (-1, 3, 4, 2) AND (BILL_DOCK_RCPT IS NULL OR BILL_DOCK_RCPT = 'Y')"
       
    Set dsDOCK_RCPTS = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    
    Do While Not dsDOCK_RCPTS.EOF
                            
        'Obtain rate from storage rate table
        gsSqlStmt = "SELECT * FROM DOCK_RCPT_HANDLING_RATE WHERE COMMODITY_CODE = " & dsDOCK_RCPTS.Fields("COMMODITY_CODE").Value & " AND (CUSTOMER_ID IS NULL OR CUSTOMER_ID = '" & Trim$(dsDOCK_RCPTS.Fields("OWNER_ID").Value) & "') ORDER BY CUSTOMER_ID NULLS LAST"
        Set dsDOCK_RCPT_HANDLING_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
           
       'check billing table for this dock receipt lot num and service code, if billed, goes to next record
        iBilled = False
        gsSqlStmt = "SELECT * FROM BILLING WHERE LOT_NUM = '" & Trim$(dsDOCK_RCPTS.Fields("CONTAINER_NUM").Value) & "'"
        gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = " & Val(Trim$(dsDOCK_RCPT_HANDLING_RATE.Fields("SERVICE_CODE").Value))
        gsSqlStmt = gsSqlStmt & " AND SERVICE_STATUS IN ('INVOICED','PREINVOICE')"
        Set dsBILL_CHECK = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        
        If OraDatabase.LastServerErr = 0 And dsBILL_CHECK.RecordCount > 0 Then
            iBilled = True
        End If
           
        BillingQty = 0
        BillingAmt = 0
        BillingUnit = "NA"

        'Check if not a weight unit
        If dsDOCK_RCPT_HANDLING_RATE.Fields("UNIT").Value = "PLT" Then
            BillingQty = dsDOCK_RCPTS.Fields("QTY_RECEIVED").Value
            BillingUnit = "PLT"
            ConversionFactor = 1
        Else
            'Get the original weight of the cargo.  Previously it gets value of
            'cargo_manifest.cargo_weight  - LFW, 12/17/2002
            'Assume no transfers for commodity 4963-Futter Lumber  - LFW, 10/2/2003
            If dsDOCK_RCPTS.Fields("COMMODITY_CODE").Value = 4963 Then
                BillingQty = dsDOCK_RCPTS.Fields("CARGO_WEIGHT").Value
            Else
                gsSqlStmt = "SELECT SUM(CARGO_WEIGHT) WEIGHT FROM CARGO_MANIFEST WHERE LR_NUM = " _
                            & dsDOCK_RCPTS.Fields("LR_NUM").Value & " AND COMMODITY_CODE = " _
                            & dsDOCK_RCPTS.Fields("COMMODITY_CODE").Value & " and CARGO_BOL = '" _
                            & dsDOCK_RCPTS.Fields("CARGO_BOL").Value & "'"
            
                Set dsCARGO_ORIGINAL_WEIGHT = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                
                If OraDatabase.LastServerErr <> 0 Then
                    MsgBox "Error in retrieving data from Cargo_Manifest.  Transaction rollbacked. "
                    OraSession.Rollback
                    Exit Sub
                End If
                
                BillingQty = dsCARGO_ORIGINAL_WEIGHT.Fields("WEIGHT").Value
            End If
                            
            BillingUnit = dsDOCK_RCPTS.Fields("CARGO_WEIGHT_UNIT").Value
            
            'The rate might be for a different unit.  Determine the rate conversion
            gsSqlStmt = "SELECT * FROM UNIT_CONVERSION WHERE PRIMARY_UOM = '" _
                        & dsDOCK_RCPTS.Fields("CARGO_WEIGHT_UNIT").Value _
                        & "' AND SECONDARY_UOM = '" _
                        & dsDOCK_RCPT_HANDLING_RATE.Fields("UNIT").Value & "'"
            Set dsUNIT_CONVERSION = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
             If (IsNull(dsUNIT_CONVERSION.Fields("CONVERSION_FACTOR"))) Then
               MsgBox "Cargo Wt. unit is " & dsDOCK_RCPTS.Fields("CARGO_WEIGHT_UNIT").Value & " and Dock Receipt Handling Rate Unit is " & dsDOCK_RCPT_HANDLING_RATE.Fields("UNIT").Value & " for the Commodity code " & dsDOCK_RCPTS.Fields("COMMODITY_CODE").Value
               MsgBox "Don't have the Unit Conversion.Please make the necessary changes and try to prebill again"
             End If
            ConversionFactor = dsUNIT_CONVERSION.Fields("CONVERSION_FACTOR")
        End If
        
        'calculate service amount      -- LFW, 3/3/04
        'use the round function to make it consistent with description on prebill and invoices
        BillingAmt = Round(BillingQty * ConversionFactor, 2) * dsDOCK_RCPT_HANDLING_RATE.Fields("RATE").Value
        
        'insert into billing table
        If BillingQty > 0 And (iBilled = False) Then
        
            dsBILLING.AddNew
            dsBILLING.Fields("CUSTOMER_ID").Value = dsDOCK_RCPTS.Fields("RECIPIENT_ID").Value
            dsBILLING.Fields("SERVICE_CODE").Value = dsDOCK_RCPT_HANDLING_RATE.Fields("SERVICE_CODE").Value
            dsBILLING.Fields("LOT_NUM").Value = dsDOCK_RCPTS.Fields("CONTAINER_NUM").Value
            dsBILLING.Fields("ACTIVITY_NUM").Value = 1
            dsBILLING.Fields("BILLING_NUM").Value = glBillingNum
            dsBILLING.Fields("EMPLOYEE_ID").Value = 4
            dsBILLING.Fields("SERVICE_START").Value = dsDOCK_RCPTS.Fields("DATE_RECEIVED").Value
            dsBILLING.Fields("SERVICE_STOP").Value = dsDOCK_RCPTS.Fields("DATE_RECEIVED").Value
            dsBILLING.Fields("SERVICE_AMOUNT").Value = BillingAmt
            dsBILLING.Fields("SERVICE_STATUS").Value = "PREINVOICE"
            dsBILLING.Fields("SERVICE_DESCRIPTION").Value = "TRUCK UNLOADING"
            dsBILLING.Fields("LR_NUM").Value = dsDOCK_RCPTS.Fields("LR_NUM").Value
            dsBILLING.Fields("ARRIVAL_NUM").Value = 1
            dsBILLING.Fields("COMMODITY_CODE").Value = dsDOCK_RCPTS.Fields("COMMODITY_CODE").Value
            dsBILLING.Fields("INVOICE_NUM").Value = 0
            dsBILLING.Fields("SERVICE_DATE").Value = dsDOCK_RCPTS.Fields("DATE_RECEIVED").Value
            dsBILLING.Fields("SERVICE_QTY").Value = BillingQty
            dsBILLING.Fields("SERVICE_NUM").Value = 1
            dsBILLING.Fields("THRESHOLD_QTY").Value = 0
            dsBILLING.Fields("LEASE_NUM").Value = 0
            dsBILLING.Fields("SERVICE_UNIT").Value = BillingUnit
            dsBILLING.Fields("SERVICE_RATE").Value = dsDOCK_RCPT_HANDLING_RATE.Fields("RATE").Value
            dsBILLING.Fields("LABOR_RATE_TYPE").Value = ""
            dsBILLING.Fields("LABOR_TYPE").Value = ""
            dsBILLING.Fields("PAGE_NUM").Value = 1
            dsBILLING.Fields("CARE_OF").Value = "Y"
            dsBILLING.Fields("BILLING_TYPE").Value = "TRKUNLDNG"
            
            'Added for asset Coding 08.14.2001 pawan
            Dim dsAssetProfile As Object
                
            'Ignore box # for WING C1 - C8 and WING E1 - E8, treat them as WING C and WING E  -- LFW, 3/3/04
            If InStr(dsDOCK_RCPTS.Fields("CARGO_LOCATION").Value, "WING C") > 0 Then
                gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING C'"
            ElseIf InStr(dsDOCK_RCPTS.Fields("CARGO_LOCATION").Value, "WING D") > 0 Then
                gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING D'"
            ElseIf InStr(dsDOCK_RCPTS.Fields("CARGO_LOCATION").Value, "WING E") > 0 Then
                gsSqlStmt = " Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING E'"
            Else
                gsSqlStmt = " Select * from ASSET_PROFILE where " & _
                            " SERVICE_LOCATION_CODE = '" & dsDOCK_RCPTS.Fields("CARGO_LOCATION").Value & "' "
            End If
     
            Set dsAssetProfile = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            
            If dsAssetProfile.RecordCount = 0 Then
                dsBILLING.Fields("ASSET_CODE").Value = "W000"
            Else
                dsBILLING.Fields("ASSET_CODE").Value = dsAssetProfile.Fields("ASSET_CODE").Value
            End If
                
            dsBILLING.Update
            
            
            
            ' HD 9097 (4/2014) - For Paper invoices, we are going to do a post-creation update
            'to homogenize them with existing printouts.
            If dsDOCK_RCPTS.Fields("COMMODITY_CODE").Value = "1299" Or dsDOCK_RCPTS.Fields("COMMODITY_CODE").Value = "1272" Then
                gsSqlStmt = "UPDATE BILLING " _
                            & "SET (SERVICE_DESCRIPTION, LR_NUM) = " _
                            & "(SELECT 'BOL: ' || CARGO_BOL || ';  MARK: ' || CARGO_DESCRIPTION || '; ' || QTY_RECEIVED || ' ' || QTY_UNIT || '(S); ', CARGO_BOL " _
                            & " FROM CARGO_TRACKING CT, CARGO_MANIFEST CM WHERE CT.LOT_NUM = CM.CONTAINER_NUM " _
                            & " AND CT.LOT_NUM = '" & dsDOCK_RCPTS.Fields("CONTAINER_NUM").Value & "') " _
                            & "WHERE BILLING_NUM = '" & glBillingNum & "'"
                OraDatabase.EXECUTESQL (gsSqlStmt)
                  
                gsSqlStmt = "UPDATE BILLING " _
                            & "SET SUB_CUST_NUM = CUSTOMER_ID, " _
                            & "BILLING_TYPE = 'MISC', " _
                            & "SERVICE_DESCRIPTION = SERVICE_DESCRIPTION || TO_CHAR(ROUND(" & ConversionFactor & " * SERVICE_QTY, 2)) || ' NT @' || TO_CHAR(SERVICE_RATE, '$9,999.99') || '/ NT' " _
                            & "WHERE BILLING_NUM = '" & glBillingNum & "'"
                OraDatabase.EXECUTESQL (gsSqlStmt)
                
            End If
            
            
            glBillingNum = glBillingNum + 1
        End If
        
        
        dsDOCK_RCPTS.DbMoveNext
    Loop
    
    lEndBillNum = glBillingNum - 1

    'log to invoicedate table if generated prebills
    If lEndBillNum >= lStartBillNum Then
        Call AddNewInvDt("Truck Unloading", CStr(lStartBillNum), CStr(lEndBillNum))
    End If

    If OraDatabase.LastServerErr <> 0 Then
        'Rollback transaction
        MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
        OraSession.Rollback
        Exit Sub
    Else
        'Commit transaction
        MsgBox "Truck Unloading prebills were generated successfully. " & vbCrLf & _
               "Please go to the new BNI System to print them.", vbInformation, "Save"
        OraSession.CommitTrans
    End If

    Unload Me
    
    Exit Sub

errHandler:
     
    If OraDatabase.LastServerErr = 0 Then
         MsgBox "Error Occured. Unable to Process Truck Unloading Bills!", vbExclamation, "Truck Unloading"
    Else
         MsgBox "Error " & OraDatabase.LastServerErrText & " occured." & vbCrLf & _
                "Unable to Process Truck Unloading Prebills!", vbExclamation, "Truck Unloading"
    End If
         
    OraSession.Rollback
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
        If Not IsNull(dsID.Fields("MAXID").Value) Then
            DtID = dsID.Fields("MAXID").Value + 1
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
            .Fields("ID").Value = DtID
            .Fields("RUN_DATE").Value = Format(Now, "MM/DD/YYYY HH:MM:SS")
            .Fields("BILL_TYPE").Value = "B"
            .Fields("TYPE").Value = sType
            .Fields("START_INV_NO").Value = sStBillNo
            .Fields("END_INV_NO").Value = sEdBillNo
            .Update
        End With
    Else
        MsgBox OraSession.LastServerErrText, vbInformation, "Truck Unloading"
    End If
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
 '   Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)

    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    Call ClearScreen
    
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0
    
End Sub

Public Sub ClearScreen()

End Sub
