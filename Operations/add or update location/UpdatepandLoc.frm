VERSION 5.00
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "COMDLG32.OCX"
Begin VB.Form frmUpdatePandol 
   BackColor       =   &H00C0C000&
   Caption         =   "Add Or Update Warehouse Location-Profuit, Pandol,Andes River"
   ClientHeight    =   3825
   ClientLeft      =   2475
   ClientTop       =   2160
   ClientWidth     =   7065
   LinkTopic       =   "Form1"
   ScaleHeight     =   3825
   ScaleWidth      =   7065
   Begin VB.CommandButton cmdManageComm 
      Caption         =   "Commodity List"
      Height          =   375
      Left            =   2640
      TabIndex        =   12
      Top             =   2640
      Width           =   1815
   End
   Begin VB.CommandButton cmdexit 
      Caption         =   "Exit"
      Height          =   315
      Left            =   4440
      TabIndex        =   11
      Top             =   1680
      Width           =   855
   End
   Begin VB.TextBox txtCustName 
      BackColor       =   &H00C0C000&
      Height          =   315
      Left            =   2520
      MaxLength       =   60
      TabIndex        =   9
      Top             =   960
      Width           =   3645
   End
   Begin VB.ComboBox cboCustId 
      Height          =   315
      Left            =   1200
      TabIndex        =   8
      Top             =   960
      Width           =   1335
   End
   Begin VB.ComboBox cboShipId 
      Height          =   315
      Left            =   1200
      TabIndex        =   7
      Top             =   600
      Width           =   1335
   End
   Begin VB.TextBox txtShipName 
      BackColor       =   &H00C0C000&
      Height          =   315
      Left            =   2520
      MaxLength       =   60
      TabIndex        =   6
      Top             =   600
      Width           =   3645
   End
   Begin VB.CommandButton cmdUpdate 
      Caption         =   "Add Or Update Location"
      Height          =   315
      Left            =   1800
      TabIndex        =   3
      Top             =   1680
      Width           =   2175
   End
   Begin VB.CommandButton cmdBrowse 
      Caption         =   "&Browse"
      Height          =   315
      Left            =   6210
      TabIndex        =   2
      Top             =   270
      Width           =   675
   End
   Begin VB.TextBox txtImportFile 
      Height          =   315
      Left            =   1200
      MaxLength       =   100
      TabIndex        =   1
      Top             =   240
      Width           =   4965
   End
   Begin MSComDlg.CommonDialog cdlImportFile 
      Left            =   6240
      Top             =   720
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
      CancelError     =   -1  'True
      DefaultExt      =   ".dbf"
      DialogTitle     =   "Select Import File"
      Filter          =   "Database Files|*.dbf|All Files|*.*"
      FilterIndex     =   1
   End
   Begin VB.Label Label2 
      BackColor       =   &H00C0C000&
      Caption         =   "Customer Id"
      Height          =   225
      Left            =   120
      TabIndex        =   10
      Top             =   1080
      Width           =   1005
   End
   Begin VB.Label Label1 
      BackColor       =   &H00C0C000&
      Caption         =   "Vessel Id"
      Height          =   225
      Left            =   120
      TabIndex        =   5
      Top             =   720
      Width           =   1005
   End
   Begin VB.Label lblStatus 
      BackColor       =   &H00C0C0C0&
      BorderStyle     =   1  'Fixed Single
      Height          =   285
      Left            =   0
      TabIndex        =   4
      Top             =   2040
      Width           =   7305
   End
   Begin VB.Line Line1 
      BorderColor     =   &H0000C0C0&
      BorderWidth     =   2
      X1              =   120
      X2              =   6960
      Y1              =   2520
      Y2              =   2520
   End
   Begin VB.Label lblImportFile 
      BackColor       =   &H00C0C000&
      Caption         =   "File Name:"
      Height          =   225
      Left            =   150
      TabIndex        =   0
      Top             =   300
      Width           =   1005
   End
End
Attribute VB_Name = "frmUpdatePandol"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdManageComm_Click()

Dim CommMaker As New frmCommList
CommMaker.Show 1

End Sub

Private Sub cboCustId_LOSTFOCUS()
    If Trim$(cboCustId.Text) = "" Then
        MsgBox "PLEASE SELECT CUSTOMER ID", vbInformation, "CUSTOMER ID"
        Exit Sub
    Else
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID =" & Val(cboCustId.Text)
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If dsCUSTOMER_PROFILE.RecordCount > 0 Then
            txtCustName.Text = Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value)
        Else
            MsgBox "CUSTOMER ID IS INVALID, PLEASE TRY AGAIN.", vbInformation, "CUSTOMER ID"
            txtImportFile.SetFocus
            cboCustId.Text = ""
        End If
    End If
End Sub

Private Sub cboShipId_LOSTFOCUS()
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM =" & Val(cboShipId.Text)
    Set DSVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If DSVESSEL_PROFILE.RecordCount > 0 Then
        txtShipName.Text = Trim$(DSVESSEL_PROFILE.Fields("VESSEL_NAME").Value)
    Else
        gsSqlStmt = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '" & cboShipId.Text & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If (dsSHORT_TERM_DATA.Fields("THE_COUNT").Value > 0) Then
            txtShipName.Text = "TRUCK"
        Else
            MsgBox "VESSEL ID IS NEITHER IN PoW LIST OF VESSELS OR EXPECTED TRUCKS", vbInformation, "SHIP ID"
            txtImportFile.SetFocus
            cboShipId.Text = ""
        End If
    End If

End Sub

Private Sub cmdBrowse_Click()
    On Error Resume Next
    cdlImportFile.Action = 1
    If Err = 0 Then
        txtImportFile = cdlImportFile.FileName
    End If
    On Error GoTo 0
End Sub


Private Sub cmdexit_Click()
End
End Sub


Private Sub cmdUpdate_Click()
    Dim lRecCount As Long
    Dim iPos As Integer
    Dim iLastBackSlashPos As Integer
    Dim sPath As String
    Dim sFileName As String
    Dim sDirChk As String
    Dim lMaxTranNum As Long
    Dim sMissingFields As String
    Dim sMissingInsp As String
    Dim iResponse As Integer
    Dim iSuccess As Integer
    Dim lRecCount2 As Long
    
    'Reset oracle error if any
    OraDatabase.LastServerErrReset
    
    On Error Resume Next
    
    Updates = 0
    Adds = 0
    lRecCount = 0
    lRecCount2 = 0
    'Check for emply import file
    If Trim$(txtImportFile.Text) = "" Then
        MsgBox "Import File can not be blank.", vbInformation, "Invalid Import File"
        On Error GoTo 0
        txtImportFile.SetFocus
        Exit Sub
    End If
    
    If Trim$(cboShipId.Text) = "" Then
        MsgBox "Vessel Must be chosen, either from dropdown box or typed in", vbInformation, "Invalid Vessel"
        cboShipId.SetFocus
        Exit Sub
    End If
    
    If Trim$(cboCustId.Text) = "" Then
        MsgBox "Customer Must be chosen from dropdown box", vbInformation, "Invalid Customer"
        cboCustId.SetFocus
        Exit Sub
    End If
    
    
    'Check if import file exists
    sDirChk = Dir$(txtImportFile.Text)
    If sDirChk = "" Then
        MsgBox "Error occured while opening '" & txtImportFile.Text & "' file.", vbInformation, "Invalid Import File"
        On Error GoTo 0
        Exit Sub
    End If
        
    'Get path and file name
    iLastBackSlashPos = 0
    iPos = InStr(txtImportFile, "\")
    While iPos > 0
        iLastBackSlashPos = iPos
        iPos = InStr(iPos + 1, txtImportFile, "\")
    Wend
    If iLastBackSlashPos = 0 Then
        sPath = ""
        sFileName = Trim$(txtImportFile.Text)
    Else
        sPath = Trim$(Mid$(txtImportFile.Text, 1, iLastBackSlashPos - 1))
        sFileName = Trim$(Mid$(txtImportFile.Text, iLastBackSlashPos + 1))
    End If
    
    'Connect to import file as dbase 5 file
    Set dbImport = OpenDatabase(sPath, False, True, "dBASE 5.0;")
    If Err <> 0 Then
        MsgBox "Error '" & Err & " - " & Error$ & "' occured while opening '" & sPath & "' path.", vbInformation, "Update Location"
        On Error GoTo 0
        Exit Sub
    End If
    
    Set rsImport = dbImport.OpenRecordset(sFileName, dbOpenSnapshot)
    If Err <> 0 Then
        MsgBox "Error '" & Err & " - " & Error$ & "' occured while opening '" & sFileName & "' recordset.", vbInformation, "Update Location"
        On Error GoTo 0
        Exit Sub
    End If
    
    
    'Ask for user confirmation before importing
    rsImport.MoveFirst
    If rsImport.EOF Then
        MsgBox "Import file is empty.", vbInformation, "Empty File"
        On Error GoTo 0
        Exit Sub
    End If
    sMissingFields = sMissingFields & CheckField("PLT_ID")
    sMissingFields = sMissingFields & CheckField("LOC")
    sMissingFields = sMissingFields & CheckField("HATCH")
    sMissingFields = sMissingFields & CheckField("VARIETY")
    sMissingFields = sMissingFields & CheckField("QTY")
    sMissingFields = sMissingFields & CheckField("COMMODITY")
    sMissingFields = sMissingFields & CheckField("LABEL")
    sMissingFields = sMissingFields & CheckField("SIZE")
    sMissingFields = sMissingFields & CheckField("PACKAGE")
    sMissingFields = sMissingFields & CheckField("GROWER")
    
    If sMissingFields <> "" Then
        MsgBox "The following fields are missing when update file :" & gsNL & sMissingFields, vbInformation, "Invalid Import File"
        On Error GoTo 0
        Exit Sub
    End If
    
    gsMessage = gsMessage & gsNL & "Are you sure you want to continue with the Update?"
    
    ' Check for the number of pallets to be updated and added
    lblStatus.Caption = "Pre-Processing"
    lblStatus.Refresh
    Dim PltList As New frmPalletList
    
    Do While Not rsImport.EOF
        lblStatus.Caption = "Pre-Processing..."
        lblStatus.Refresh
        gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID= '" & Trim$(rsImport.Fields("PLT_ID")) & "'"
        gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(cboCustId.Text)
        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(cboShipId.Text) & "'"
       
        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If dsCARGO_TRACKING.RecordCount > 0 Then
            Updates = Updates + 1
            PltList.txtUpdate = PltList.txtUpdate & Trim$(rsImport.Fields("PLT_ID")) & vbCrLf
            lblStatus.Caption = "Pre-Processing."
            lblStatus.Refresh
        Else
            PltList.txtAdd = PltList.txtAdd & Trim$(rsImport.Fields("PLT_ID")) & vbCrLf
            Adds = Adds + 1
        End If
        rsImport.MoveNext
    Loop
        
    ' Got our totals, let us ask the user what they want to do.
    lblStatus.Caption = "Waiting for Validation..."
    lblStatus.Refresh
    PltList.lblAdd.Caption = "To Add:  " & Adds
    PltList.lblUpd.Caption = "To Update:  " & Updates
    PltList.Show vbModal
'    iResponse2 = MsgBox(Updates & " to update, " & Adds & " to Add.  Continue?", vbQuestion + vbYesNo, "Add or Update?")
    If iResponse2 = "Yes" Then
        Call print_header
        rsImport.MoveFirst
        BreakExecute = False
        Do While (Not rsImport.EOF) And (Not BreakExecute)
            gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID= '" & Trim$(rsImport.Fields("PLT_ID")) & "'"
            gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(cboCustId.Text)
            gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(cboShipId.Text) & "'"
           
            Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If dsCARGO_TRACKING.RecordCount = 1 Then
                lRecCount = lRecCount + 1
                OraSession.BeginTrans
                dsCARGO_TRACKING.Edit
                dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value = Trim$(rsImport.Fields("LOC"))
                If Not IsNull(rsImport.Fields("VARIETY")) Then
'                    dsCARGO_TRACKING.Fields("CARGO_DESCRIPTION").Value = Trim$(rsImport.Fields("VARIETY"))
                    dsCARGO_TRACKING.Fields("VARIETY").Value = Trim$(rsImport.Fields("VARIETY"))
                End If
'                If Not IsNull(rsImport.Fields("LABEL")) Then
'                    If Not IsNull(rsImport.Fields("SIZE")) Then
'                        dsCARGO_TRACKING.Fields("REMARK").Value = Trim$(rsImport.Fields("LABEL")) & "/" & Trim$(rsImport.Fields("SIZE"))
'                    Else
'                        dsCARGO_TRACKING.Fields("REMARK").Value = Trim$(rsImport.Fields("LABEL"))
'                    End If
'                ElseIf Not IsNull(rsImport.Fields("SIZE")) Then
'                    dsCARGO_TRACKING.Fields("REMARK").Value = Trim$(rsImport.Fields("SIZE"))
'                End If
                If Not IsNull(rsImport.Fields("LABEL")) Then
                    dsCARGO_TRACKING.Fields("REMARK").Value = Trim$(rsImport.Fields("LABEL"))
                End If
                If Not IsNull(rsImport.Fields("GROWER")) Then
                    dsCARGO_TRACKING.Fields("CARGO_DESCRIPTION").Value = Trim$(rsImport.Fields("GROWER"))
                End If
                If Not IsNull(rsImport.Fields("SIZE")) Then
                    dsCARGO_TRACKING.Fields("CARGO_SIZE").Value = Trim$(rsImport.Fields("SIZE"))
                End If
                If Not IsNull(rsImport.Fields("PACKAGE")) Then
                    dsCARGO_TRACKING.Fields("BATCH_ID").Value = Trim$(rsImport.Fields("PACKAGE"))
                End If
                dsCARGO_TRACKING.Update
                
                If OraDatabase.LastServerErr = 0 Then
                    If lRecCount2 <> 0 Then
                        lblStatus.Caption = Updates & " Records are updated And" & Adds & " Record(s) are added."
                    Else
                        lblStatus.Caption = Adds & " Record(s) are added."
                    End If
                    lblStatus.Refresh
                Else
                    lblStatus.Caption = "Error has occured. Changes have been rolled back."
                    lblStatus.Refresh
                    OraSession.Rollback
                    Exit Sub
                End If
            ElseIf dsCARGO_TRACKING.RecordCount = 0 Then
            'ADD INTO SYSTEM
                OraSession.BeginTrans
                dsCARGO_TRACKING.AddNew
                dsCARGO_TRACKING.Fields("PALLET_ID").Value = Trim$(rsImport.Fields("PLT_ID"))
                dsCARGO_TRACKING.Fields("RECEIVER_ID").Value = Val(cboCustId.Text)
                dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value = Trim$(cboShipId.Text)
                dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value = rsImport.Fields("QTY")
                dsCARGO_TRACKING.Fields("QTY_UNIT").Value = "G"
                dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = rsImport.Fields("QTY")
                If Not IsNull(rsImport.Fields("VARIETY")) Then
'                    dsCARGO_TRACKING.Fields("CARGO_DESCRIPTION").Value = Trim$(rsImport.Fields("VARIETY"))
                    dsCARGO_TRACKING.Fields("VARIETY").Value = Trim$(rsImport.Fields("VARIETY"))
                End If
'                If Not IsNull(rsImport.Fields("LABEL")) Then
'                    If Not IsNull(rsImport.Fields("SIZE")) Then
'                        dsCARGO_TRACKING.Fields("REMARK").Value = Trim$(rsImport.Fields("LABEL")) & "/" & Trim$(rsImport.Fields("SIZE"))
'                    Else
'                        dsCARGO_TRACKING.Fields("REMARK").Value = Trim$(rsImport.Fields("LABEL"))
'                    End If
'                ElseIf Not IsNull(rsImport.Fields("SIZE")) Then
'                    dsCARGO_TRACKING.Fields("REMARK").Value = Trim$(rsImport.Fields("SIZE"))
'                End If
                If Not IsNull(rsImport.Fields("LABEL")) Then
                    dsCARGO_TRACKING.Fields("REMARK").Value = Trim$(rsImport.Fields("LABEL"))
                End If
                If Not IsNull(rsImport.Fields("GROWER")) Then
                    dsCARGO_TRACKING.Fields("CARGO_DESCRIPTION").Value = Trim$(rsImport.Fields("GROWER"))
                End If
                If Not IsNull(rsImport.Fields("SIZE")) Then
                    dsCARGO_TRACKING.Fields("CARGO_SIZE").Value = Trim$(rsImport.Fields("SIZE"))
                End If
                If Not IsNull(rsImport.Fields("PACKAGE")) Then
                    dsCARGO_TRACKING.Fields("BATCH_ID").Value = Trim$(rsImport.Fields("PACKAGE"))
                End If
                dsCARGO_TRACKING.Fields("SHIPPING_LINE").Value = 8091
                dsCARGO_TRACKING.Fields("FROM_SHIPPING_LINE").Value = 8091
                dsCARGO_TRACKING.Fields("MANIFESTED").Value = "Y"
                If Val(cboShipId.Text) = "9999999" Then
                    dsCARGO_TRACKING.Fields("RECEIVING_TYPE").Value = "T"
                Else
                    dsCARGO_TRACKING.Fields("RECEIVING_TYPE").Value = "S"
                End If
                If Not IsNull(rsImport.Fields("COMMODITY")) Then
                    If IsNumeric(rsImport.Fields("COMMODITY")) Then
                        dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value = rsImport.Fields("COMMODITY")
                    Else
                        dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value = CommodityCodeConvert(rsImport.Fields("COMMODITY"))
                    End If
                Else
                    dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value = 8060
                End If
    
                dsCARGO_TRACKING.Fields("HATCH").Value = GetHatch(Trim$(rsImport.Fields("HATCH")))
                dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value = Left(Trim$(rsImport.Fields("PLT_ID")), 3)
                dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value = Trim$(rsImport.Fields("LOC"))
                dsCARGO_TRACKING.Update
                
                If OraDatabase.LastServerErr = 0 Then
                    
                     sMissingInsp = sMissingInsp & CheckField("INSP")
                     If sMissingInsp <> "" Then
                       Call print_body1
                     Else
                       Call print_body
                     End If
                    If lRecCount <> 0 Then
                        lblStatus.Caption = Updates & " Records are updated And" & Adds & " Record(s) are added."
                    Else
                        lblStatus.Caption = Adds & " Record(s) are added."
                    End If
                    lblStatus.Refresh
                Else
                    lblStatus.Caption = "Error has occured. Changes have been rolled back."
                    lblStatus.Refresh
                    MsgBox ("Error" & OraDatabase.LastServerErrText & "!")
                    OraSession.Rollback
                    Exit Sub
                End If
            
            Else ' the sql returned 2 or more rows, and shouldn't have.
                MsgBox "Pallet Id " & rsImport.Fields("PLT_ID") & " has been found under multiple vessels in the database.  Please correct duplicates before running this program."
                lblStatus.Caption = "Cancelled operation, Please handle Duplicate Pallets"
                Printer.KillDoc
                OraSession.Rollback
                Exit Sub
            End If
            
            rsImport.MoveNext
            If (Not BreakExecute) Then
                lRecCount2 = lRecCount2 + 1
            End If
        Loop
        txtImportFile.Text = ""
        txtImportFile.SetFocus
        
        Call print_footer(Adds)
    
        If Not BreakExecute Then
            If lRecCount2 <> 0 Then
                lblStatus.Caption = Updates & " Records are updated And " & Adds & " are added. done!"
            Else
                lblStatus.Caption = Updates & " Records are updated. done!"
            End If
            OraSession.CommitTrans
            lblStatus.Refresh
        Else
            OraSession.Rollback
        End If
    
        On Error GoTo 0
    Else
        lblStatus.Caption = "Operation Canceled!"
        lblStatus.Refresh
        Exit Sub
    End If
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, Me.Caption
    lblStatus.Caption = "Error Occured."
    Screen.MousePointer = vbDefault
    On Error GoTo 0
    
End Sub

Private Sub Form_load()
 Me.Top = (Screen.Height - Me.Height) / 3
    Me.Left = (Screen.Width - Me.Width) / 2
    
    lblStatus.Caption = "Logging to database..."
    Screen.MousePointer = vbHourglass
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    'Database logon parameters
''    gsOraUserId = "SAG_OWNER/SAG"
''    gsOraPassword = "SAG"
''    gsOraInstance = "TEST"

    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    'Set OraDatabase = OraSession.OpenDatabase(gsOraInstance, gsOraUserId & "/" & gsOraPassword, 0&)
    Set OraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
'    Set OraDatabase = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    Screen.MousePointer = vbDefault
  
    cboCustId.AddItem (146)
    cboCustId.AddItem (175)
    cboCustId.AddItem (1131)
    cboCustId.AddItem (1420)
    cboCustId.AddItem (1608)
    cboCustId.AddItem (1630)
    cboCustId.AddItem (1989)
    'Initialize log file name
    gsLogFileName = App.Path & "\PSWImpEM.log"
    Me.Caption = Me.Caption & " - " & OraDatabase.DatabaseName
    
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, Me.Caption
    lblStatus.Caption = "Error Occured."
    Screen.MousePointer = vbDefault
    On Error GoTo 0
    
End Sub

Public Function CheckField(asFieldName As String) As String
'Appends the content of the dbf file field to the message string
'Returns the field name if not found in the dbf file
    On Error Resume Next
    
    gsMessage = gsMessage & asFieldName & " : " & rsImport.Fields(asFieldName) & gsNL
    If Err <> 0 Then
        CheckField = asFieldName & "; "
    Else
        CheckField = ""
    End If
End Function

Private Sub txtImportFile_LOSTFOCUS()
    
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
    Set DSVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    While Not DSVESSEL_PROFILE.EOF
        cboShipId.AddItem DSVESSEL_PROFILE.Fields("LR_NUM").Value
        DSVESSEL_PROFILE.MoveNext
    Wend

End Sub

Public Sub print_header()
   Printer.Orientation = 2
   Printer.Print "Printed On : " & Format(Now, "MM/DD/YYYY")
   Printer.Print ""
   Printer.Print ""
   Printer.FontSize = 12
   Printer.FontBold = True
   Printer.Print Tab(55); "ADDED PALLET DETAILS"
   Printer.FontSize = 9
   Printer.FontBold = False
   Printer.Print ""
   Printer.Print ""
   Printer.Print Tab(5); "VESSEL ID"; Tab(25); ":"; Tab(30); cboShipId.Text & " - " & txtShipName.Text
   Printer.Print ""
   Printer.Print Tab(5); "CUSTOMER ID"; Tab(25); ":"; Tab(30); Val(cboCustId.Text) & " - " & txtCustName.Text
   Printer.Print ""
    
   Printer.Print
   Printer.Print Tab(3); "---------------------------------------------------------------------------------------------" _
                       ; "---------------------------------------------------------------------------------------------" _
                       ; "---------------------------------------------------------------------------------------------"
   Printer.Print Tab(5); "PALLET ID"; Tab(30); "COMMODITY"; Tab(53); "VARIETY"; Tab(88); "LABEL"; Tab(108); "SIZE"; _
                 Tab(128); "QTY"; Tab(143); "HATCH"; Tab(158); "LOCATION"; Tab(177); "INSP"
   Printer.Print Tab(3); "---------------------------------------------------------------------------------------------" _
                       ; "---------------------------------------------------------------------------------------------" _
                       ; "---------------------------------------------------------------------------------------------"

End Sub

Public Sub print_body()
'print the added pallet details
    If (Not BreakExecute) Then
    Printer.Print Tab(5); Trim$(rsImport.Fields("PLT_ID")); Tab(34); Trim$(NVL(rsImport.Fields("COMMODITY"), "")); Tab(51); Trim$(NVL(rsImport.Fields("VARIETY"), "")); _
                  Tab(89); Trim$(NVL(rsImport.Fields("LABEL"), "")); Tab(109); Trim$(NVL(rsImport.Fields("SIZE"), "")); Tab(129); Trim$(NVL(rsImport.Fields("QTY"), "")); _
                  Tab(145); Trim$(NVL(rsImport.Fields("HATCH"), "")); Tab(161); Trim$(NVL(rsImport.Fields("LOC"), "")); Tab(178); Trim$(NVL(rsImport.Fields("INSP"), ""));
    End If
End Sub
Public Sub print_body1()
'print the added pallet details
    If (Not BreakExecute) Then
    Printer.Print Tab(5); Trim$(rsImport.Fields("PLT_ID")); Tab(34); Trim$(NVL(rsImport.Fields("COMMODITY"), "")); Tab(51); Trim$(NVL(rsImport.Fields("VARIETY"), "")); _
                  Tab(89); Trim$(NVL(rsImport.Fields("LABEL"), "")); Tab(109); Trim$(NVL(rsImport.Fields("SIZE"), "")); Tab(129); Trim$(NVL(rsImport.Fields("QTY"), "")); _
                  Tab(145); Trim$(NVL(rsImport.Fields("HATCH"), "")); Tab(161); Trim$(NVL(rsImport.Fields("LOC"), ""));
    End If
End Sub

Public Sub print_footer(Adds)
    If (Not BreakExecute) Then
        Printer.Print
        Printer.Print
        Printer.Print Adds; " Record(s) Added."
        Printer.EndDoc
    Else
        Printer.Print
        Printer.Print
        Printer.Print "Operation cancelled."
        Printer.EndDoc
    End If
End Sub
Private Function CommodityCodeConvert(NameOfCommodity As String) As Integer
    Dim dsCOMMODITY As Object
    
    gsSqlStmt = "SELECT COMMODITY_CODE FROM RECEIVER_COMMODITY_MAP WHERE UPPER(TEXT_DESC) = UPPER('" & NameOfCommodity & "')"
    Set dsCOMMODITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If dsCOMMODITY.RecordCount = 0 Then
        MsgBox "Commodity " & NameOfCommodity & " Not in Conversion table.  Upload Cancelled.  Please add the commodity, and re-run."
        BreakExecute = True
    Else
        CommodityCodeConvert = dsCOMMODITY.Fields("COMMODITY_CODE").Value
    End If
        
End Function

Private Function GetHatch(strHatch As String) As String

    strHatch = Replace(strHatch, " ", "")
    strHatch = Replace(strHatch, "-", "")
    
    If Len(strHatch) < 2 Or Len(strHatch) > 3 Then
        GetHatch = "CONT"
        Exit Function
    End If
    
    strHatch = UCase(strHatch)
    
    If Len(strHatch) = 2 And Mid$(strHatch, 2, 1) = "H" Then
        strHatch = strHatch & "C"
    End If
    
    If IsNumeric(Mid$(strHatch, 1, 1)) = False Then
        GetHatch = "CONT"
        Exit Function
    End If
    
    If Not (Mid(strHatch, 2, 1) = "A" Or Mid(strHatch, 2, 1) = "B" Or Mid(strHatch, 2, 1) = "C" Or Mid(strHatch, 2, 1) = "D" Or Mid(strHatch, 2, 1) = "E" Or Mid(strHatch, 2, 1) = "F" Or Mid(strHatch, 2, 1) = "G" Or Mid(strHatch, 2, 1) = "H") Then
        GetHatch = "CONT"
        Exit Function
    End If

    If Len(strHatch) = 3 And Mid(strHatch, 2, 1) = "H" Then
        If Mid(strHatch, 3, 1) <> "C" Then
            GetHatch = "CONT"
            Exit Function
        End If
    End If
    
    GetHatch = strHatch

End Function
