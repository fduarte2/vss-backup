VERSION 5.00
Begin VB.Form frmDDE_VB 
   Caption         =   "frmDDE-VB"
   ClientHeight    =   5580
   ClientLeft      =   1860
   ClientTop       =   1995
   ClientWidth     =   7020
   FillStyle       =   5  'Downward Diagonal
   LinkTopic       =   "Form1"
   PaletteMode     =   1  'UseZOrder
   ScaleHeight     =   5580
   ScaleWidth      =   7020
   Begin VB.Timer Waiting 
      Enabled         =   0   'False
      Interval        =   100
      Left            =   4080
      Top             =   2040
   End
   Begin VB.CommandButton cmdQuit 
      Cancel          =   -1  'True
      Caption         =   "&Quit"
      Height          =   375
      Left            =   5760
      TabIndex        =   20
      Top             =   2040
      Width           =   975
   End
   Begin VB.CommandButton cmdCloseICWConv 
      Caption         =   "&Close Conv'n"
      Height          =   375
      Left            =   1920
      TabIndex        =   19
      Top             =   2040
      Width           =   1455
   End
   Begin VB.CommandButton cmdOpenICWConv 
      Caption         =   "&Open Conv'n"
      Height          =   375
      Left            =   120
      TabIndex        =   18
      Top             =   2040
      Width           =   1455
   End
   Begin VB.Frame frmXmit 
      Caption         =   "Transmit to reader"
      Height          =   1455
      Left            =   120
      TabIndex        =   13
      Top             =   2640
      Width           =   6735
      Begin VB.CommandButton cmdDwnLd 
         Caption         =   "&Download List"
         Default         =   -1  'True
         Height          =   375
         Left            =   5400
         TabIndex        =   24
         Top             =   960
         Width           =   1215
      End
      Begin VB.TextBox TbxDwnLd 
         Height          =   285
         Left            =   1680
         TabIndex        =   23
         Top             =   960
         Width           =   3495
      End
      Begin VB.CommandButton cmdXmit 
         Caption         =   "&Xmit to Reader"
         Height          =   375
         Left            =   5400
         TabIndex        =   0
         Top             =   360
         Width           =   1215
      End
      Begin VB.TextBox tbxAddr 
         Height          =   285
         Left            =   120
         TabIndex        =   15
         Text            =   "1"
         Top             =   480
         Width           =   1335
      End
      Begin VB.TextBox tbxXmitMsg 
         Height          =   285
         Left            =   1680
         TabIndex        =   14
         Top             =   360
         Width           =   3495
      End
      Begin VB.Label Label1 
         Caption         =   "Download List Profile Name:"
         Height          =   255
         Left            =   1680
         TabIndex        =   25
         Top             =   720
         Width           =   2895
      End
      Begin VB.Label lblMsg 
         Caption         =   "Message to send:"
         Height          =   255
         Left            =   1800
         TabIndex        =   17
         Top             =   120
         Width           =   1575
      End
      Begin VB.Label lblAddr 
         Caption         =   "Address (1= Serial)"
         Height          =   255
         Left            =   120
         TabIndex        =   16
         Top             =   240
         Width           =   1455
      End
   End
   Begin VB.CommandButton cmdStatus 
      Caption         =   "S&tatus"
      Height          =   375
      Left            =   5520
      TabIndex        =   12
      Top             =   5160
      Width           =   1215
   End
   Begin VB.CommandButton cmdStopAdvise 
      Caption         =   "&Stop Advise"
      Height          =   375
      Left            =   5520
      TabIndex        =   11
      Top             =   4680
      Width           =   1215
   End
   Begin VB.CommandButton cmdAdvise 
      Caption         =   "&Advise (start)"
      Height          =   375
      Left            =   5520
      TabIndex        =   10
      Top             =   4200
      Width           =   1215
   End
   Begin VB.CommandButton cmdGetRecord 
      Caption         =   "&Get RECORD"
      Height          =   375
      Left            =   240
      TabIndex        =   9
      Top             =   4320
      Width           =   1455
   End
   Begin VB.Frame frmSelRec 
      Caption         =   "Select Record"
      Height          =   1455
      Left            =   120
      TabIndex        =   3
      Top             =   4080
      Width           =   4215
      Begin VB.OptionButton optLast 
         Caption         =   "Last"
         Height          =   495
         Left            =   3240
         TabIndex        =   8
         Top             =   600
         Width           =   1095
      End
      Begin VB.OptionButton optNext 
         Caption         =   "Next"
         Height          =   315
         Left            =   3240
         TabIndex        =   7
         Top             =   240
         Width           =   1095
      End
      Begin VB.OptionButton optCur 
         Caption         =   "Current"
         Height          =   255
         Left            =   1800
         TabIndex        =   6
         Top             =   960
         Width           =   855
      End
      Begin VB.OptionButton optPrev 
         Caption         =   "Previous"
         Height          =   495
         Left            =   1800
         TabIndex        =   5
         Top             =   480
         Width           =   1095
      End
      Begin VB.OptionButton optFirst 
         Caption         =   "First"
         Height          =   255
         Left            =   1800
         TabIndex        =   4
         Top             =   240
         Width           =   975
      End
   End
   Begin VB.TextBox tbxDDE_Msg 
      Height          =   285
      Left            =   3840
      TabIndex        =   2
      Top             =   1680
      Visible         =   0   'False
      Width           =   2775
   End
   Begin VB.ListBox lbxRecv 
      Height          =   1425
      Left            =   120
      TabIndex        =   1
      TabStop         =   0   'False
      Top             =   240
      Width           =   6615
   End
   Begin VB.Label lblNote 
      Caption         =   "NOTE: DDE message box is hidden to the right -->"
      Height          =   255
      Left            =   240
      TabIndex        =   22
      Top             =   1800
      Width           =   6135
   End
   Begin VB.Label lblRecv 
      Caption         =   "Data received"
      Height          =   255
      Left            =   120
      TabIndex        =   21
      Top             =   0
      Width           =   2895
   End
End
Attribute VB_Name = "frmDDE_VB"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Const RECEIVING As Integer = 1
Const DELIVERY As Integer = 2
Const MOVING As Integer = 3
Const VOID As Integer = 4
Const RECOUP As Integer = 5
Const REJECTED As Integer = 6

Dim iNewOrderNum As Boolean
Dim iFirstPage As Boolean
Dim iPltNum As String
Dim iActivityNum As Integer
Dim iTransferType As String

Dim fAdvise As Boolean
Dim fEZC_Open As Boolean
Dim EZC_Record As String
Dim Reader_Addr As String
Dim iUpdateAccess As Boolean
Dim sbCommandType As String
Dim iFound As Integer
Private Sub EZC_System_Link()
'On Error GoTo Finish
   tbxDDE_Msg.LinkMode = 0                  ' close DATA link if open
   tbxDDE_Msg.LinkTopic = ServerName & "|SYSTEM"  'EZC Topic
   tbxDDE_Msg.LinkMode = 2 ' LinkMode = Manual
   fEZC_Open = False
   Exit Sub
   
Finish:
   If Err <> 282 Then   ' EzComms must have been shut down independently, it's no longer running
     MsgBox "EZC_System_Link error: " & Err
   End If
End Sub

Private Sub Get_All_Fields()
'    Dim i As Integer
' On Error GoTo Finish
'    i = 0
'    EZC_Record = ""
'    While Err = 0
'      i = i + 1
'      tbxDDE_Msg.LinkItem = "Field" & i
'      tbxDDE_Msg.LinkRequest
'      If i > 1 Then
'        EZC_Record = EZC_Record & ","
'      End If
'      tempMsg = tbxDDE_Msg.Text
'      EZC_Record = EZC_Record & tempMsg
'      BCField(i) = tempMsg
'    Wend
'
'Finish:
 Dim i As Integer
    Dim NmbFlds As Integer
On Error Resume Next
    
    For i = 1 To 40
      'TrnFld(i) = ""
      BCField(i) = ""
    Next i
    i = 0
    iFld = 0
    EZC_Record = ""
    tbxDDE_Msg.LinkItem = "RecordStatus"
    tbxDDE_Msg.LinkRequest
    NmbFlds = Mid(tbxDDE_Msg.Text, 9, 3)
    For i = 1 To NmbFlds
      tbxDDE_Msg.LinkItem = "Field" & i
      tbxDDE_Msg.LinkRequest
      If Err = 0 Then
        If i > 1 Then
          EZC_Record = EZC_Record & ","
        End If
        EZC_Record = EZC_Record & tbxDDE_Msg.Text
        'TrnFld(i) = tbxDDE_Msg.Text
        BCField(i) = tbxDDE_Msg.Text
        iFld = i
      Else
        EZC_Record = EZC_Record & ","
        'TrnFld(i) = ""
        BCField(i) = ""
        Err = 0
      End If
    Next i
       
Finish:
End Sub

Private Sub cmdDwnLd_Click()
        
    On Error GoTo Error

     
    tbxDDE_Msg.LinkItem = "ListDL"  'download a list using a Download List Profile
                                    'that has been saved in an EzCommsInteractive session
       
    tbxDDE_Msg.Text = TbxDwnLd.Text    'use the profile name to this reader addr
    tbxDDE_Msg.LinkPoke
    Exit Sub

Error:
    Beep
    lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "List Download via EZC failed ...", 0
   

End Sub

Private Sub cmdGetRecord_Click()
  Dim fOK As Boolean
    
  'On Error GoTo NotAvail
  
  iFound = 0
  Set_Command_Type
  Call Get_Record(fOK)
  
  If Not fOK Then
    GoTo NotAvail
  End If
  
  Exit Sub

NotAvail:
    If iFound = 0 Then
      lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "No " & sbCommandType & " available!", 0
      Beep
    End If

End Sub

Private Sub cmdAdvise_Click()
    Dim fOK As Boolean
 
 'On Error GoTo Finish
    
    sbCommandType = "Next"
    iFound = 0                ' # records added to list
    fOK = True
    While fOK = True
      Call Get_Record(fOK)
      If fOK Then
        iFound = iFound + 1
      End If
    Wend
    If iFound = 0 Then
      lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "No new records to report...", 0
    End If
    tbxDDE_Msg.LinkItem = "LoadRecord"
    tbxDDE_Msg.Text = "last"
    tbxDDE_Msg.LinkMode = 3         ' Turn on Advise
    cmdAdvise.Enabled = False
    cmdStopAdvise.Enabled = True
    optLast.Value = True
    fAdvise = True
    Exit Sub

Finish:
    Beep

End Sub

Private Sub cmdStatus_Click()
  Dim fOK As Boolean
    
  'On Error GoTo NotAvail
  
  iFound = 0
  sbCommandType = "Current"
  
  tbxDDE_Msg.LinkItem = "LoadRecord"
  tbxDDE_Msg.Text = sbCommandType
  tbxDDE_Msg.LinkPoke
  
  tbxDDE_Msg.LinkItem = "RecordStatus"
  tbxDDE_Msg.LinkRequest
  EZC_Record = tbxDDE_Msg.Text
  lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & EZC_Record, 0
   
  GoTo SubEnd

NotAvail:
  lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "No " & sbCommandType & " available!", 0
  Beep

SubEnd:
    
  tbxDDE_Msg.LinkItem = "LoadRecord"
  tbxDDE_Msg.Text = "last"
  If fAdvise Then
    tbxDDE_Msg.LinkMode = 3    ' Automatic DDE Advise
  Else
    tbxDDE_Msg.LinkMode = 2    ' Manual DDE Advise
  End If

End Sub

Private Sub cmdStopAdvise_Click()
    'On Error GoTo Finish
    
    tbxDDE_Msg.LinkItem = "LoadRecord"
    tbxDDE_Msg.Text = "last"
    tbxDDE_Msg.LinkMode = 2         ' Turn manual notify
    cmdAdvise.Enabled = True
    cmdStopAdvise.Enabled = False
    fAdvise = False

Finish:

End Sub


Private Sub cmdOpenICWConv_Click()
    Dim i
    Dim J
    Dim str As String

On Error Resume Next

Restart:
    
    Init_EZC_Link
    If Err = 282 Then  ' ExComms not running
      GoTo Start_EZC
    End If
    If Err <> 0 Then
      MsgBox "Error on Start Up (cmdOpen 1): " & Err
      fEZC_Open = False
      Exit Sub
    End If
    
OK_Return:
    tbxDDE_Msg.LinkItem = "SetName"     ' Set Name is an EZC Command
    tbxDDE_Msg.Text = "EzCommsI-VB Link"     ' Link name for EZC
    tbxDDE_Msg.LinkPoke                 ' Send the command to EZC
                                   '   even though we are supposed to be
                                   '   a link destination -- receiving messages
    tbxDDE_Msg.LinkItem = "LoadRecord"  ' LoadRecord is an EZC Command
    tbxDDE_Msg.Text = "Last"            ' Tells EZC which record to point at
                                   '   -- when requested, the latest record
                                   '      received by EZC will be returned
    tbxDDE_Msg.LinkMode = 2             ' Sets link to Manual
                                   '   event will be triggered if last record
                                   '   changes in EZC (EZC Advise)

    cmdGetRecord.Enabled = True
    cmdAdvise.Enabled = True
    cmdStopAdvise.Enabled = False
    cmdStatus.Enabled = True
    cmdXmit.Enabled = True
    cmdDwnLd.Enabled = True
    cmdOpenICWConv.Enabled = False
    cmdCloseICWConv.Enabled = True
    cmdQuit.Enabled = True
    optFirst.Enabled = True
    optPrev.Enabled = True
    optCur.Enabled = True
    optNext.Enabled = True
    optLast.Enabled = True
    optNext.Value = True
    fAdvise = False
    cmdXmit.SetFocus

    Exit Sub
    
Start_EZC:
    
    If Err = 282 Then              ' EZC not running
      Err = 0
      'i = Shell("EzCommsI.EXE", 6)  ' EzComms Interactive Host Program
      
      i = Shell(App.Path & "\EzComms.EXE " & ServerName, 4)  ' EzComms Interactive Host Program
      If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
        Err = 0
        i = Shell("EzCommsE.EXE", 6) ' EzComms Evaluation Host Program which scrambles data slightly
        If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
          Err = 0
          i = Shell("BIN\EzCommsI.EXE", 6)
          If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
            Err = 0
            i = Shell("BIN\EzCommsE.EXE", 6) ' EzComms Evaluation Host Program which scrambles data slightly
            If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
              Err = 0
              i = Shell("..\BIN\EzCommsI.EXE", 6)
              If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
                Err = 0
                i = Shell("..\BIN\EzCommsE.EXE", 6) ' EzComms Evaluation Host Program which scrambles data slightly
                If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
                  Err = 0
                  i = Shell("..\..\BIN\EzCommsI.EXE", 6)
                  If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
                    Err = 0
                    i = Shell("..\..\BIN\EzCommsE.EXE", 6) ' EzComms Evaluation Host Program which scrambles data slightly
                  End If
                End If
              End If
            End If
          End If
        End If
      End If
      If Err <> 0 Then
        MsgBox "Error on Start Up (cmdOpen 2): " & Err
        fEZC_Open = False
        Exit Sub
      End If
                                   ' this Client Program
      fEZC_Open = True
      GoTo OK_Return
    Else
      MsgBox "Error on Start Up (cmdOpen 3): " & Err
      fEZC_Open = False
      Exit Sub
    End If
    
End Sub

    

Private Sub cmdCloseICWConv_Click()
 'On Error GoTo Finish
    EZC_System_Link
    tbxDDE_Msg.LinkExecute "[Quit(0)]"  ' EzComms Quit Command
    fEZC_Open = False
    cmdGetRecord.Enabled = False
    cmdAdvise.Enabled = False
    cmdStopAdvise.Enabled = False
    cmdStatus.Enabled = False
    cmdXmit.Enabled = False
    cmdDwnLd.Enabled = False
    cmdOpenICWConv.Enabled = True
    cmdCloseICWConv.Enabled = False
    cmdQuit.Enabled = True
    optFirst.Enabled = False
    optPrev.Enabled = False
    optCur.Enabled = False
    optNext.Enabled = False
    optLast.Enabled = False
    fAdvise = False
    cmdOpenICWConv.SetFocus

    Exit Sub

Finish:
   MsgBox "Error on Shut Down: " & Err
End Sub

Private Sub cmdQuit_Click()
    'On Error GoTo Finish

    If fEZC_Open Then
      EZC_System_Link
      tbxDDE_Msg.LinkExecute "[Quit(0)]"  ' EZC Quit Command
     End If
'
Finish:
    End
End Sub



Private Sub cmdXmit_Click()
    
       
    'On Error GoTo Error

    TempAddr = tbxAddr.Text
    
    If TempAddr = "COM1" Then
      TempAddr = "1"
    End If
    
    If TempAddr = "COM2" Then
      TempAddr = "1"
    End If
    
    If TempAddr = "COM3" Then
      TempAddr = "1"
    End If
    
    If TempAddr = "COM4" Then
      TempAddr = "1"
    End If
       
    tbxDDE_Msg.LinkExecute "[Xmit(" & TempAddr & "," & tbxXmitMsg.Text & ")]"
    Exit Sub

Error:
    Beep
    lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "Transmit to EZC failed ...", 0
   
End Sub



Private Sub Form_KeyPress(KeyAscii As Integer)
    'On Error GoTo Error
    If tbxAddr.Text <> "" Then
      tbxDDE_Msg.LinkExecute "[Xmit(" & tbxAddr.Text & "," & tbxXmitMsg.Text & ")]"
    Else
      Beep
    End If
    Exit Sub

Error:
    Beep
    lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "Transmit to EZC failed ...", 0
    

End Sub

Private Sub Form_Load()
    

    Open App.Path & "\Server.Txt" For Input As #1
    
    Input #1, ServerName
    Close #1
    
    ''Open App.Path & "\vessel.txt" For Input As #1
    ''Input #1, UnloadLRNum
    ''Close #1
    

    cmdGetRecord.Enabled = False
    cmdAdvise.Enabled = False
    cmdStatus.Enabled = False
    cmdXmit.Enabled = False
    cmdDwnLd.Enabled = False
    cmdOpenICWConv.Enabled = False
    cmdCloseICWConv.Enabled = False
    cmdStopAdvise.Enabled = False
    cmdQuit.Enabled = False
    optFirst.Enabled = False
    optPrev.Enabled = False
    optCur.Enabled = False
    optNext.Enabled = False
    optLast.Enabled = False
    fTime = True
    Waiting.Interval = 500
    Waiting.Enabled = True
    iFirstTime = True
    
    'OPEN ACCESS DB
    Call OpenAccessDb
    Call Initialize_DB
    
    Me.Caption = ServerName

    iFirstTime = False
    
End Sub

Private Sub OpenAccessDb()
    'Set accessDb = DBEngine.Workspaces(0).OpenDatabase("W:\access\XpX.mdb")
End Sub
Private Sub Initialize_DB()
 
    If Not iFirstTime Then
        oraErr = OraDatabase.LastServerErr
        oraErrStr = OraDatabase.LastServerErrText
        lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "Oracle Message: " & oraErr & "-" & oraErrStr, 0
        'If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErrReset
    End If
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    
    'Set OraDatabase = OraSession.OpenDatabase("RF.dev", "SAG_OWNER/RFOWNER", 0&)
    'Set OraDatabase = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)
    Set OraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "Logon Successful.", 0
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "Logon Failed.", 0
        Unload Me
    End If
    
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING"
    Set dsCARGO_TRACKING_ALL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY"
    Set dsCARGO_ACTIVITY_ALL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)


    ''gsSqlStmt = "SELECT * FROM CARGO_LOG"
    ''Set dsACTIVITY_LOG = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

    ''gsSqlStmt = "SELECT * FROM DAMAGE_LOG"
    ''Set dsDAMAGE_LOG = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
End Sub

'Private Sub SendToInLog(TransArgs As String)
'' adds entries to SCAN_LOG table
'' I realize my definitions are re-inventing the wheel,
'' But I can't think straight right now, so i need
'' more intuitive variable names
'
'
'Dim strPGMAction As String
'Dim strDummyID As String
'Dim strDummyNo As String
'Dim strBarcodeID As String
'Dim strSLGID As String
'Dim strLGNID As String
'Dim strSql As String
'Dim strScannerIp As String
'Dim strTransCode As String
''Dim strTransArgs As String
'Dim strSuperId As String
'Dim strCheckerId As String
'Dim strPalletNo As String
'Dim strComm As String
'Dim strOwner As String
'Dim strLR As String
'
'
'
'If BCField(1) = "DNC" Then ' Incoming Dummy Scan
'
'      strPGMAction = Trim$(BCField(1))
'      strDummyID = Trim$(BCField(2))
'      strSLGID = Trim$(BCField(3))
'      strLGNID = Trim$(BCField(4))
'
'
'
'
'      strScannerIp = Reader_Addr
'
'      strTransCode = strPGMAction
'
'      strTransArgs = BCField(1) & "," & BCField(2) & "," & BCField(3) & "," & BCField(4)
'
'      strSuperId = strSLGID
'
'      strCheckerId = strLGNID
'
'      strDummyNo = strDummyID
'
'      strSql = "INSERT INTO SCAN_LOG (ACTIVITY_DATE, SCANNER_IP, FROM_SCANNER, TRANSACTION_CODE, TRANSACTION_ARGS, SUPERVISOR_ID, CHECKER_ID, COMMODITY_TYPE, PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) "
'      strSql = strSql & "VALUES (SYSDATE, '" & strScannerIp & "', 'Y', '" & strTransCode & "', '" & strTransArgs & "', '"
'      strSql = strSql & strSuperId & "', '" & strCheckerId & "', '', '" & strDummyNo & "', '', '')"
'
'      OraDatabase.ExecuteSQL (strSql)
'
'Else ' Incoming Palet Scan
'
'
'      strPGMAction = Trim$(BCField(1))
'      strDummyID = Trim$(BCField(2))
'      strBarcodeID = Trim$(BCField(3))
'      strSLGID = Trim$(BCField(4))
'      strLGNID = Trim$(BCField(5))
'
'
'      strScannerIp = Reader_Addr
'
'      strTransCode = strPGMAction
'
'      strTransArgs = BCField(1) & "," & BCField(2) & "," & BCField(3) & "," & BCField(4) & "," & BCField(5)
'
'      strSuperId = strSLGID
'
'      strCheckerId = strLGNID
'
'      strPalletNo = strBarcodeID
'
'      strSql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & strBarcodeID & "'"
'      Set dsSCANLOGCHECK = OraDatabase.DbCreateDynaset(strSql, 0&)
'      If dsSCANLOGCHECK.RecordCount = 0 Then
'            strComm = "NOVALUE"
'            strOwner = "NOVALUE"
'            strLR = "NOVALUE"
'      Else
'            strComm = dsSCANLOGCHECK.Fields("COMMODITY_CODE").Value
'            strOwner = dsSCANLOGCHECK.Fields("RECEIVER_ID").Value
'            strLR = dsSCANLOGCHECK.Fields("ARRIVAL_NUM").Value
'      End If
'
'      strSql = "INSERT INTO SCAN_LOG (ACTIVITY_DATE, SCANNER_IP, FROM_SCANNER, TRANSACTION_CODE, TRANSACTION_ARGS, SUPERVISOR_ID, CHECKER_ID, COMMODITY_TYPE, PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) "
'      strSql = strSql & "VALUES (SYSDATE, '" & strScannerIp & "', 'Y', '" & strTransCode & "', '" & strTransArgs & "', '"
'      strSql = strSql & strSuperId & "', '" & strCheckerId & "', '" & strComm & "', '" & strPalletNo & "', '" & strOwner & "', '" & strLR & "')"
'
'      OraDatabase.ExecuteSQL (strSql)
'End If
'
'End Sub
'
'Private Sub SendToOutLog(strAddr As String, strCode As String, strRest As String, strSuper As String, strChecker As String, strComm As String, strPalletOrDummy As String, strLR As String, strOwner As String)
'
'Dim strSql As String
'
'      strSql = "INSERT INTO SCAN_LOG (ACTIVITY_DATE, SCANNER_IP, FROM_SCANNER, TRANSACTION_CODE, TRANSACTION_ARGS, SUPERVISOR_ID, CHECKER_ID, COMMODITY_TYPE, PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) "
'      strSql = strSql & "VALUES (SYSDATE, '" & strAddr & "', 'N', '" & strCode & "', '" & strRest & "', '"
'      strSql = strSql & strSuper & "', '" & strChecker & "', '" & strComm & "', '" & strPalletOrDummy & "', '" & strOwner & "', '" & strLR & "')"
'
'      OraDatabase.ExecuteSQL (strSql)
'
'End Sub
Private Sub tbxDDE_Msg_LinkNotify()
    Dim fOK As Boolean
'On Error GoTo Finish
    sbCommandType = "Next"
    fOK = True
    While fOK = True
      Call Get_Record(fOK)
    Wend
    tbxDDE_Msg.LinkItem = "LoadRecord"
    tbxDDE_Msg.Text = "last"
    Exit Sub

Finish:

End Sub



Public Sub Init_EZC_Link()
' NOTE: do not include an ON ERROR statement
'       or this will prevent DDE initialization.
  
  tbxDDE_Msg.LinkMode = 0
  tbxDDE_Msg.LinkTopic = ServerName & "|DATA"
  tbxDDE_Msg.LinkMode = 2
 
End Sub


Public Sub Get_Record(fOK As Boolean)
  On Error GoTo GetOut
  
  fOK = False
  tbxDDE_Msg.LinkItem = "LoadRecord"
  tbxDDE_Msg.Text = sbCommandType
  tbxDDE_Msg.LinkPoke
  
  tbxDDE_Msg.LinkItem = "Address"
  tbxDDE_Msg.LinkRequest
  Reader_Addr = tbxDDE_Msg.Text
  
  Get_All_Fields
  
  lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "<" & Reader_Addr & "> <" & EZC_Record & ">", 0
  iFound = iFound + 1
  fOK = True
  tbxAddr = Reader_Addr
  'Database update
  
  'DoEvents
  
  Call takeAction
  
  Exit Sub

GetOut:
  fOK = False
  
  If (OraDatabase.LastServerErr <> 0) Then
    OraDatabase.Close
    Call Initialize_DB
  End If
  
End Sub

Public Sub Set_Command_Type()
  
  If optFirst.Value = True Then
    sbCommandType = "First"
  ElseIf optPrev.Value = True Then
    sbCommandType = "Previous"
  ElseIf optCur.Value = True Then
    sbCommandType = "Current"
  ElseIf optNext.Value = True Then
    sbCommandType = "Next"
  ElseIf optLast.Value = True Then
    sbCommandType = "Last"
  End If

End Sub


Public Sub Init_Advise()
 Dim fOK As Boolean
 
 'On Error GoTo Finish
    
    sbCommandType = "Next"
    iFound = 0                ' # records added to list
    fOK = True
    While fOK = True
      Call Get_Record(fOK)
      If fOK Then
        iFound = iFound + 1
      End If
    Wend
    If iFound = 0 Then
      lbxRecv.AddItem Format(Now, "MM/DD/YYYY HH:MM:SS AMPM") & " " & "No new records to report...", 0
    End If
    
    tbxDDE_Msg.LinkItem = "LoadRecord"
    tbxDDE_Msg.Text = "last"
    tbxDDE_Msg.LinkMode = 3       ' Turn on Advise
    cmdAdvise.Enabled = False
    cmdStopAdvise.Enabled = True
    optLast.Value = True
    fAdvise = True
    
    Exit Sub

Finish:
    Beep

End Sub



Public Sub Init_Open_Conversation()
    Dim i
    Dim J
    Dim str As String

On Error Resume Next

Restart:
    
    Init_EZC_Link
    
    If Err = 282 Then  ' ExComms not running
      GoTo Start_EZC
    End If
    If Err <> 0 Then
      MsgBox "Error on Start Up (Init_Open 1): " & Err
      fEZC_Open = False
      Exit Sub
    End If

OK_Return:
    
    Init_EZC_Link
    tbxDDE_Msg.LinkItem = "SetName"     ' Set Name is an EZC Command
    tbxDDE_Msg.Text = "EzCommsI-VB"     ' Link name for EZC
    tbxDDE_Msg.LinkPoke                 ' Send the command to EZC
                                   '   even though we are supposed to be
                                   '   a link destination -- receiving messages
    tbxDDE_Msg.LinkItem = "LoadRecord"  ' LoadRecord is an EZC Command
    tbxDDE_Msg.Text = "Last"            ' Tells EZC which record to point at
                                   '   -- when requested, the latest record
                                   '      received by EZC will be returned
    tbxDDE_Msg.LinkMode = 2             ' Sets link to Manual
                                   '   event will be triggered if last record
                                   '   changes in EZC (EZC Advise)

    cmdGetRecord.Enabled = True
    cmdAdvise.Enabled = True
    cmdStopAdvise.Enabled = False
    cmdStatus.Enabled = True
    cmdXmit.Enabled = True
    cmdDwnLd.Enabled = True
    cmdOpenICWConv.Enabled = False
    cmdCloseICWConv.Enabled = True
    cmdQuit.Enabled = True
    optFirst.Enabled = True
    optPrev.Enabled = True
    optCur.Enabled = True
    optNext.Enabled = True
    optLast.Enabled = True
    optNext.Value = True
    fAdvise = False
    cmdXmit.SetFocus

    Exit Sub
    
Start_EZC:
    
    If Err = 282 Then              ' EZC not running
      Err = 0
      'i = Shell("C:\EzComms.EXE", 6)  ' EzComms Interactive Host Program
      
      ''i = Shell(App.Path & "\EzComms.EXE " & ServerName, 4)  ' EzComms Interactive Host Program
      i = Shell(App.Path & "\EzComms.EXE")  ' EzComms Interactive Host Program
      If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
        Err = 0
        i = Shell("EzCommsE.EXE", 6) ' EzComms Evaluation Host Program which scrambles data slightly
          If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
          Err = 0
          i = Shell("BIN\EzCommsI.EXE", 6)
          If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
            Err = 0
            i = Shell("BIN\EzCommsE.EXE", 6) ' EzComms Evaluation Host Program which scrambles data slightly
            If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
              Err = 0
              i = Shell("..\BIN\EzCommsI.EXE", 6)
              If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
                Err = 0
                i = Shell("..\BIN\EzCommsE.EXE", 6) ' EzComms Evaluation Host Program which scrambles data slightly
                If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
                  Err = 0
                  i = Shell("..\..\BIN\EzCommsI.EXE", 6)
                  If Err = 76 Or Err = 53 Then  ' 76 = Path not found, 53 = File not found
                    Err = 0
                    i = Shell("..\..\BIN\EzCommsE.EXE", 6) ' EzComms Evaluation Host Program which scrambles data slightly
                  End If
                End If
              End If
            End If
          End If
        End If
      End If
      If Err <> 0 Then
        MsgBox "Error on Start Up (Init_Open 2): " & Err
        fEZC_Open = False
        Exit Sub
      End If
                                   ' this Client Program
      fEZC_Open = True
      GoTo OK_Return
    Else
      MsgBox "Error on Start Up (Init_Open 3): " & Err
      fEZC_Open = False
      Exit Sub
    End If
    
End Sub

Private Sub TbxDwnLdMsg_Change()

End Sub


Private Sub Waiting_Timer()
    
    If fTime Then
      fTime = False  ' do initialization only once
      Init_Open_Conversation
      Init_Advise
    End If
    Waiting.Enabled = False

End Sub


Private Sub takeAction()
1
    If (OraDatabase.LastServerErr <> 0) Then
        OraDatabase.Close
        Call Initialize_DB
    End If
    
   'Call SendToInLog(EZC_Record)
                
    Select Case BCField(1)
           Case "CSLG", "SLG": SupervisorLogin
           Case "CLG", "LG": logginAction
           Case "LRN": CheckLRNum
           Case "SO": cshipOut
           Case "SOD": cshipOutDuplicatePallets
           Case "SOF"
                SendToInLog
                cShipOutFinal
           Case "SOC": ccloseShippingOrder
           Case "FP": findPallet
           Case "UCT"
                SendToInLog
                UpdateCT
           Case "CUS": CheckCustomer
           Case "COM": CheckCommodity
           Case "LOC": CheckLocation
           Case "KID": CheckKeyedID
           Case "SRX"
                SendToInLog
                shipReceivingException
           Case "WNG": CheckWing
           Case "RCI": PalletInformation
           Case "LOP": PalletInformation
           Case "RVO": OrderInformation
           Case "RLC"
                SendToInLog
                RelocatePalletsProcess
           Case "ORV": VerifyOrder
           Case "RTI": returnInquery
           Case "RT"
                SendToInLog
                returnAction
           Case "VDI": voidInquery
           Case "VDP": voidPallet
           Case "TRB": TruckReceivingBegin
           Case "TR":  truckReceiving
           Case "TRX"
                SendToInLog
                truckReceivedException
           Case "TRC"
                SendToInLog
                truckReceivedChangeFields
           Case "CPH": CheckPckHse
           Case "UDH": UpdateHosp
    End Select
End Sub

Private Sub SupervisorLogin()
    
    On Error GoTo DbError
'    If (BCField(1) = "SLG") Then
'        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N)]"
'        Exit Sub
'    End If
    'Added to track logins as well as testing
    If Left$(Trim$(BCField(2)), 4) = "TEST" Then
        TrackLogin "TEST", Right$(BCField(2), 1)
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
    Else
        'add on 07/23/1999
        gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(2)) & "'"
        'gsSqlStmt = gsSqlStmt & " AND LOGIN_ID IN ('SCHEN','JJOHNSO','ITHOMAS','GRANGER','DDONOFR','MMCLAUG','MCUTLER','JDONALD','AMARKOW','DGAMBLE','PKELLEY','MICKEY')"
        Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        PauseForResponse
        If (OraDatabase.LastServerErr = 0) And (dsPERSONNEL.RecordCount > 0) And (Trim$(BCField(2)) = Trim$(BCField(3))) Then
            TrackLogin Trim$(BCField(2)), ""
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
            'add a record to scanner_login table
            gsSqlStmt = "insert into scanner_login(login_id, login_time, ip) values ('" & Trim$(BCField(2)) & "', sysdate, '" & Reader_Addr & "')"
            OraDatabase.ExecuteSQL (gsSqlStmt)
            
            Exit Sub
        Else
            If OraDatabase.LastServerErr <> 0 Then
                OraDatabase.Close
                Call Initialize_DB
            End If
    
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N)]"
            Exit Sub
        End If
    End If
    Exit Sub
    
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
    
End Sub

Private Sub TrackLogin(sLogin As String, sLocation As String)
    
    Dim OraDB As Object
    Dim ds As Object
    Dim lRet As Long
    
    Set OraDB = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
    
    gsSqlStmt = "INSERT INTO SCAN_LOGIN (SCAN_DATE,UNIT_ID,LOGIN,LOCATION) VALUES (TO_DATE('" & Format$(Now, "mm/dd/yyyy HH:MM:ss AMPM") & "','MM/DD/YYYY HH:MI:SS AM'),'" & ServerName & "','" & sLogin & "','" & sLocation & "')"
    lRet = OraDB.ExecuteSQL(gsSqlStmt)
    OraDB.Close
    
End Sub

Private Sub logginAction()
    'On Error GoTo DbError
'    If (BCField(1) = "LG") Then
'        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N)]"
'        Exit Sub
'    End If
    'Check password
    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(2)) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    PauseForResponse
    
    If (OraDatabase.LastServerErr = 0) And (dsPERSONNEL.RecordCount > 0) And (Trim$(BCField(2)) = Trim$(BCField(3))) Then
        
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
      
    Else
        If (OraDatabase.LastServerErr <> 0) Then
            OraDatabase.Close
            Call Initialize_DB
        End If
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N)]"
    End If

    Exit Sub
    
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
    

End Sub
Private Sub cshipOut()
    
'On Error GoTo DbError

'passing fields: 1. "SO", 2.User, 3. Ship to Cust, 4. Order#, 5. Barcode, 6. Supervisor, 7. Wing 8. LR num 9.Pg
'In this case, we need concern the duplicate pallets if same customer, same pallet number from the different ship or truck
    
Dim isString As String
Dim isString1 As String
Dim isString2 As String
Dim isString3 As String
Dim isString4 As String
Dim isString5 As String
Dim isString6 As String
Dim isString7 As String
Dim isString8 As String
Dim isString9 As String
Dim isString10 As String
Dim isString11 As String
    
Dim isCustomerName As String
Dim isCommodityName As String
Dim isDmgd As String
Dim isLocation As String
Dim iNextArrivalNum As String
Dim iCurrArrivalNum As String
Dim iArrival As String
Dim iUnderOtherCust As Boolean
Dim iFromTruck As Boolean
Dim iVariety As String
Dim iSize As String
Dim iPck_hse As String
Dim dsORDER As Object
Dim dsORD_DETAIL As Object
Dim msg As String
Dim STATUS As String
Dim dsCOMM As Object
Dim iDuplicateCount As Integer
Dim sizecorrect As Boolean
Dim dsPICK As Object
Dim Pck_Hse_Correct As Boolean
Dim dsPICK_CONTAINER As Object
Dim strContID As String
    
'Oppenheimer Fix
'If Len(Trim$(BCField(5))) = 20 Then
'    Barcode = Right(Trim$(BCField(5)), 10)
'Else
'    Barcode = Trim$(BCField(5))
'End If

'If Len(Trim$(BCField(5))) > 12 And Len(Trim$(BCField(5))) <> 20 Then
'    Barcode = Right(Trim$(BCField(5)), 12)
'End If
    
Barcode = Trim$(BCField(5))
    
isString = ""
isString1 = ""
isString2 = ""
isString3 = ""
isString4 = ""
isString5 = ""
isString6 = ""
isString7 = ""
isString8 = ""
isString9 = ""
isString10 = ""
isString11 = ""
    
isCustomerName = ""
isCommodityName = ""
isDmgd = ""
isLocation = ""
iValidateString = ""
iOrderNumber = Trim$(BCField(4))
iPalletNumber = Barcode
iCurrArrivalNum = ""
iNextArrivalNum = ""
iArrival = ""
iUnderOtherCust = False
iVariety = ""
iSize = ""
iPck_hse = ""
    
'get pallet based on the pallet number and customer id
If (BCField(3) = "440") Or (BCField(3) = "441") Then
    iFromTruck = True
End If
    
gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(BCField(3))
If Not iFromTruck Then
    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & BCField(8) & "'"
ElseIf iFromTruck Then
    gsSqlStmt = gsSqlStmt & " AND RECEIVING_TYPE = 'T'"
End If
'gsSqlStmt = gsSqlStmt & " ORDER BY ARRIVAL_NUM"
Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & iOrderNumber & "'"
Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
If OraDatabase.LastServerErr = 0 Then
    If dsCARGO_TRACKING.RecordCount > 0 Then
'        If IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED")) Then
'            PauseForResponse
'            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N)]"
'            Exit Sub
'        End If
    Else
        'IF UNDER OTHER CUSTOMER
        gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
        'gsSqlStmt = "AND DATE_RECEIVED IS NOT NULL"
        'gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(BCField(3))
        gsSqlStmt = gsSqlStmt & " ORDER BY RECEIVER_ID,ARRIVAL_NUM"
        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If dsCARGO_TRACKING.RecordCount > 0 Then 'HAVE SAME PALLET ID UNDER ANOTHER ARRIVAL #
            iUnderOtherCust = True
        Else 'NO RECORDS AT ALL
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",NUNKNOWN PALLET. \nCONTACT OFFICE.)]"
            Exit Sub
        End If
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("MARK")) Then
        'Pallet has either been shipped or is in POW. If shipped and qty in house is 0, do not allow.
        If (dsCARGO_TRACKING.Fields("MARK").Value = "SHIPPED" And dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = 0) Then
            'Pallet has been shipped completely. Cannot ship again
            gsSqlStmt = "SELECT DISTINCT PALLET_ID, ORDER_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "' AND SERVICE_CODE = '6'"
            Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            iOrderNumber = dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value
            msg = "PALLET HAS BEEN \nSHIPPED ON ORDER\n" & iOrderNumber
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
            Exit Sub
        End If
    End If
    
    If InStr(1, iOrderNumber, "BUR") = 1 Then
        If ("" & UCase(dsCARGO_TRACKING.Fields("REMARK").Value)) <> "BURNAC" Then
            msg = "BURNAC ORDER \nNOT BURNAC PLT\n"
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
            Exit Sub
        End If
    End If
        
        
    'GET INFORMATION FOR DISPLAYING
    If Not IsNull(dsCARGO_TRACKING.Fields("RECEIVER_ID")) Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(dsCARGO_TRACKING.Fields("RECEIVER_ID").Value))
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME")) Then
            isCustomerName = Left$(Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value), 12)
        Else
            isCustomerName = ""
        End If
    Else
        isCustomerName = ""
    End If
        
    'GET ARRIVAL NUM AND ARRIVAL NAME IF FROM SHIP. IF FROM TRUCK, GIVE THE ARRIVAL NUM
    If Not IsNull(dsCARGO_TRACKING.Fields("ARRIVAL_NUM")) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Val(Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value))
        Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If dsVESSEL_PROFILE.RecordCount > 0 Then 'CARGO FROM SHIP
            If Not IsNull(dsVESSEL_PROFILE.Fields("VESSEL_NAME")) Then
                iCurrArrivalNum = Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
                iArrival = iCurrArrivalNum & "-" & Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value)
            Else 'ALMOST NO THIS CASE
                iCurrArrivalNum = ""
                iArrival = ""
            End If
        Else 'FROM TRUCK
            iCurrArrivalNum = Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
            iArrival = Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
                
        End If
    Else 'ALMOST NO THIS CASE
        iCurrArrivalNum = ""
        iArrival = ""
    End If
        
    If Not IsNull(dsCARGO_TRACKING.Fields("COMMODITY_CODE")) Then
        gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Val(Trim$(dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value))
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If Not IsNull(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME")) Then
            If (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5605") Then
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 11), 5)
            ElseIf (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5603") Then
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 4) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 18), 4)
            Else
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 14), 5)
            End If
            isCommodityName = dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value & "-" & isCommodityName
        Else
            isCommodityName = dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value
        End If
    Else
        isCommodityName = ""
    End If
        
    If Not IsNull(dsCARGO_TRACKING.Fields("QTY_DAMAGED")) Then
        isDmgd = Trim$(dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value)
    Else
        isDmgd = ""
    End If
                
    If Not IsNull(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION")) Then
        isLocation = Trim$(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value)
    Else
        isLocation = ""
    End If
        
    If Not IsNull(dsCARGO_TRACKING.Fields("VARIETY")) Then
        iVariety = Trim$(dsCARGO_TRACKING.Fields("VARIETY").Value)
    Else
        iVariety = ""
    End If
        
    If Not IsNull(dsCARGO_TRACKING.Fields("CARGO_SIZE")) Then
        iSize = Val(dsCARGO_TRACKING.Fields("CARGO_SIZE").Value)
    Else
        iSize = ""
    End If
        
    If Not IsNull(dsCARGO_TRACKING.Fields("EXPORTER_CODE")) Then
        iPck_hse = Trim$(dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value)
    Else
        iPck_hse = ""
    End If
        
    isString1 = Trim$(isCustomerName)
    isString1 = Left$(isString1 & "          ", 10)
    isString2 = Trim$(isCommodityName)
    isString2 = Left$(isString2 & "          ", 14)
    isString3 = Trim$(isLocation)
    isString3 = Left$(isString3 & "          ", 10)
    isString4 = Trim$(dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value)
    isString4 = Left$(isString4 & "   ", 3)
    isString5 = isDmgd
    isString5 = Left$(isString5 & "   ", 3)
    isString6 = Trim$(dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value)
    isString6 = Left$(isString6 & "   ", 3)
    isString7 = Left$(iArrival & "          ", 10) 'GET SHIP NAME OR ORDER NUMBER
    isString8 = Left$(iVariety & "          ", 10)
    isString9 = Left$(iSize & "   ", 3)
    isString10 = Left$(iPck_hse & " ", 5)
    isString11 = isString8 & "," & isString9 & "," & isString10

    'Y'Customer 'Commodity 'Location 'Qty Rcvd 'Qty Dmgd 'Qty In Hse 'Qty In Hse 'Ship Name 'Variety 'Size 'Pck Hse'Current Arrival
    'D'Customer 'Commodity 'Location 'Qty Rcvd 'Qty Dmgd 'Qty In Hse 'Qty In Hse 'Ship Name 'Variety 'Size 'Pck Hse'Current Arrival'Next Arrival
    'C'Customer 'Commodity 'Location 'Qty Rcvd 'Qty Dmgd 'Qty In Hse 'Qty In Hse 'Ship Name 'Variety 'Size 'Pck Hse
    'R'Customer 'Commodity 'Location 'Qty Rcvd 'Qty Dmgd 'Qty In Hse 'Qty In Hse 'Ship Name 'Variety 'Size 'Pck Hse

    
    'Check everything against the order
    'Allow for override, so send all the info about the pallet
    'Sent to scanner - Override: X, Cannot continue: N
    'If Not iFromTruck Then
    gsSqlStmt = "SELECT * FROM DC_ORDER WHERE ORDERNUM = '" & iOrderNumber & "'"
    Set dsORDER = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
    gsSqlStmt = "SELECT * FROM DC_ORDERDETAIL WHERE ORDERNUM = '" & iOrderNumber & "' "
    Set dsORD_DETAIL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
'    If iFromTruck Then
'        gsSqlStmt = "SELECT * FROM DC_DOMESTIC_PICKLIST WHERE ORDERNUM = '" & iOrderNumber & "'"
'        Set dsPICK_CONTAINER = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'        If dsPICK_CONTAINER.RecordCount = 0 Then
'            msg = "PICKLIST HASNT BEEN \nCOMPLETED FOR THIS \nORDER CONTACT OFFICE."
'            PauseForResponse
'            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
'            Exit Sub
'        End If
'    End If
        
    'Check the order status
    If Not (dsORDER.Fields("ORDERSTATUSID").Value = "4") Then
        msg = "ORDER STATUS IS INCORRECT FOR SCANNING. CONTACT OFFICE."
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
        Exit Sub
    End If
        
    'Check for correct commodity
    If Not (dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value = dsORDER.Fields("COMMODITYCODE").Value) Then
        msg = "PALLET DOESNT MATCH COMMODITY CODE FOR \nTHIS ORDER."
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
        Exit Sub
    End If
    
    If Not iFromTruck Then
        'Check the LR num of the pallet
        If Not (iCurrArrivalNum = dsORDER.Fields("VESSELID").Value) Then
            msg = "PALLET DOESNT MATCH ARRIVAL NUMBER FOR THIS ORDER."
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
            Exit Sub
        End If
'    ElseIf iFromTruck Then
'        'Check the container of the pallet
'        strContID = Left$(dsCARGO_TRACKING.Fields("CONTAINER_ID").Value, 1)
'        If (dsPICK_CONTAINER.RecordCount = 1) Then
'            If Not (strContID = dsPICK_CONTAINER.Fields("CONTAINER_ID").Value) Then
'                msg = "PALLET DOESNT MATCH CONTAINER NUMBER FOR THIS ORDER."
'                PauseForResponse
'                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
'                Exit Sub
'            End If
'        ElseIf (dsPICK_CONTAINER.RecordCount > 1) Then 'If a multiple line order, check all containers
'            Do While Not dsPICK_CONTAINER.EOF
'                If Not (strContID = dsPICK_CONTAINER.Fields("CONTAINER_ID").Value) Then
'                    msg = "PALLET DOESNT MATCH CONTAINER NUMBER FOR THIS ORDER."
'                ElseIf (strContID = dsPICK_CONTAINER.Fields("CONTAINER_ID").Value) Then
'                    msg = ""
'                    Exit Do
'                End If
'                dsPICK_CONTAINER.MoveNext
'            Loop
'        End If
'        If msg <> "" Then
'            PauseForResponse
'            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
'            Exit Sub
'        End If
    End If
    
    Pck_Hse_Correct = True
    If Not iFromTruck Then
        'Check if the Packing House and size of Pallet match on order
        gsSqlStmt = "SELECT * FROM DC_PICKLIST WHERE PACKINGHOUSE = '" & iPck_hse & "' AND ORDERNUM = '" & Trim$(BCField(4)) & "' AND PICKLISTSIZE = '" & iSize & "'"
        Set dsPICK = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If (dsPICK.RecordCount = 0) Then
            Pck_Hse_Correct = False
        End If
    ElseIf iFromTruck Then
'        'Check if the packing house and size match on order
'        gsSqlStmt = "SELECT * FROM DC_DOMESTIC_PICKLIST WHERE PACKINGHOUSE = '" & iPck_hse & "' AND ORDERNUM = '" & Trim$(BCField(4)) & "' AND PICKLISTSIZE = '" & iSize & "'"
'        Set dsPICK = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'        If (dsPICK.RecordCount = 0) Then
'            Pck_Hse_Correct = False
'        End If
        Do While Not dsORD_DETAIL.EOF
            If (dsORD_DETAIL.Fields("SIZELOW").Value <= iSize) And (iSize <= dsORD_DETAIL.Fields("SIZEHIGH").Value) Then
                sizecorrect = True
                Exit Do
            Else
                sizecorrect = False
            End If
            dsORD_DETAIL.MoveNext
        Loop
    End If
        
    'Check if a Order is a customer load if pallet status is not null
    If Not IsNull(dsCARGO_TRACKING.Fields("CARGO_STATUS")) Then
        STATUS = dsCARGO_TRACKING.Fields("CARGO_STATUS").Value
        If (dsORDER.Fields("LOADTYPE").Value = "CUSTOMER LOAD") Then
            If (STATUS = "R") Then
                STATUS = "REGRADE"
            ElseIf (STATUS = "H") Then
                STATUS = "HOSPITAL"
            ElseIf (STATUS = "B") Then
                STATUS = "BOTH"
            End If
        ElseIf (dsORDER.Fields("LOADTYPE").Value = "REGRADE LOAD") Then
            If (STATUS = "") Then
                STATUS = "GOOD"
            ElseIf (STATUS = "R") Then
                'correct
                STATUS = ""
            ElseIf (STATUS = "H") Then
                STATUS = "HOSPITAL"
            ElseIf (STATUS = "B") Then
                STATUS = "BOTH"
            End If
        ElseIf (dsORDER.Fields("LOADTYPE").Value = "HOSPITAL LOAD") Then
            If (STATUS = "") Then
                STATUS = "GOOD"
            ElseIf (STATUS = "R") Then
                STATUS = "REGRADE"
            ElseIf (STATUS = "H") Then
                'correct
                STATUS = ""
            ElseIf (STATUS = "B") Then
                STATUS = "BOTH"
            End If
        End If
    Else 'If no status for the pallet, check to make sure load is a customer load
        If Not (dsORDER.Fields("LOADTYPE").Value = "CUSTOMER LOAD") Then
            STATUS = "GOOD" 'This is so that the check is a fail for the correct status of the pallet for the order
        End If
    End If
    
    If (STATUS <> "") Then
        msg = "PALLET STATUS IS \n" & STATUS
    End If
    If Not iFromTruck Then
        If Not Pck_Hse_Correct Then
            If msg <> "" Then
                msg = "PALLET STATUS IS \n" & STATUS
                msg = msg & " AND \nSIZE/PCK_HSE COMBO \nDOESNT MATCH ORDER"
            Else
                msg = "PALLET SIZE/PCK_HSE COMBO DOESNT MATCH \nFOR THIS ORDER"
            End If
        End If
    ElseIf iFromTruck Then
        If Not sizecorrect Then
            If msg <> "" Then
                msg = "PALLET STATUS IS \n" & STATUS
                msg = msg & " AND \nDOES NOT MATCH SIZE \nFOR ORDER"
            Else
                msg = "PALLET DOESNT MATCH \nSIZE FOR THIS ORDER"
            End If
        End If
    End If
    If InStr(1, iOrderNumber, "BUR") = 0 Then
        If ("" & UCase(dsCARGO_TRACKING.Fields("REMARK").Value)) = "BURNAC" Then
            msg = "BURNAC PLT \nNOT BURNAC ORDER\n"
        End If
    End If
    
    
    If (msg <> "") Then
        isString = msg & "," & isString1 & "," & isString2 & "," & isString3 & "," & isString4 & " ," & isString5 & "  ," & isString6 & "," & isString7 & "," & isString11 & "," & iCurrArrivalNum
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & isString & ")]"
        Exit Sub
    End If
    
    If dsCARGO_TRACKING.RecordCount = 1 Then 'IF ONLY ONE
        isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & " ," & isString5 & "  ," & isString6 & "," & isString7 & "," & isString11 & "," & iCurrArrivalNum
        'send message to handheld
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & isString & ")]"
        Exit Sub
    Else 'IF MORE THAN ONE
        dsCARGO_TRACKING.MoveNext
        iNextArrivalNum = Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
        isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & " ," & isString5 & "  ," & isString6 & "," & isString7 & "," & isString11 & "," & iCurrArrivalNum & "," & iNextArrivalNum
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",D" & isString & ")]"
        Exit Sub
    End If
Else
    OraDatabase.Close
    Call Initialize_DB
    'send message to handheld
    PauseForResponse
    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Just Continue)]" 'should change to what need say on the screen
    Exit Sub
End If
    
        
End Sub
Private Sub cshipOutDuplicatePallets()
    
    'On Error GoTo DbError

    'passing fields: 1. "SOD", 2.User, 3. Ship to Cust, 4. Order, 5. Barcode, 6. Supervisor, 7. Wing, 8.Next Arrival Number
    
    Dim isString As String
    Dim isString1 As String
    Dim isString2 As String
    Dim isString3 As String
    Dim isString4 As String
    Dim isString5 As String
    Dim isString6 As String
    Dim isString7 As String
    Dim isString8 As String
    Dim isString9 As String
    Dim isString10 As String
    Dim isString11 As String
    
    Dim isCustomerName As String
    Dim isCommodityName As String
    Dim isDmgd As String
    Dim isLocation As String
    Dim iNextArrivalNum As String
    Dim iCurrArrivalNum As String
    Dim iArrival As String
    Dim iVariety As String
    Dim iSize As String
    Dim iChep As String
    
    'Oppenheimer Fix
    'If Len(Trim$(BCField(5))) = 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 10)
    'Else
    '    Barcode = Trim$(BCField(5))
    'End If

    'If Len(Trim$(BCField(5))) > 12 And Len(Trim$(BCField(5))) <> 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 12)
    'End If
    
    Barcode = Trim$(BCField(5))
    
    isString = ""
    isString1 = ""
    isString2 = ""
    isString3 = ""
    isString4 = ""
    isString5 = ""
    isString6 = ""
    
    isString7 = ""
    isString8 = ""
    isString9 = ""
    isString10 = ""
    isString11 = ""
    isCustomerName = ""
    isCommodityName = ""
    isDmgd = ""
    isLocation = ""
    iValidateString = ""
    iOrderNumber = Trim$(BCField(4))
    iPalletNumber = Barcode
    iCurrArrivalNum = ""
    iNextArrivalNum = ""
    iArrival = ""
    iVariety = ""
    iSize = ""
    iChep = ""
    'get pallet based on the pallet number and customer id
    
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(BCField(3))
    gsSqlStmt = gsSqlStmt & " ORDER BY ARRIVAL_NUM"
    
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        
        Do While Not dsCARGO_TRACKING.EOF
            If Trim$(BCField(8)) = Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value) Then
                Exit Do
            End If
            dsCARGO_TRACKING.MoveNext
        Loop
        
        If Not dsCARGO_TRACKING.EOF Then
            
            'If Not IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED")) Then
            
                If Not IsNull(dsCARGO_TRACKING.Fields("RECEIVER_ID")) Then
                        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(dsCARGO_TRACKING.Fields("RECEIVER_ID").Value))
                        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                        If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME")) Then
                            isCustomerName = Left$(Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value), 12)
                        Else
                            isCustomerName = ""
                        End If
                Else
                    isCustomerName = ""
                End If
                
                'GET ARRIVAL NUM AND ARRIVAL NAME IF FROM SHIP. IF FROM TRUCK, GIVE THE ARRIVAL NUM
                If Not IsNull(dsCARGO_TRACKING.Fields("ARRIVAL_NUM")) Then
                    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Val(Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value))
                    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                    If dsVESSEL_PROFILE.RecordCount > 0 Then 'CARGO FROM SHIP
                        If Not IsNull(dsVESSEL_PROFILE.Fields("VESSEL_NAME")) Then
                            iCurrArrivalNum = Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
                            iArrival = iCurrArrivalNum & "-" & Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value)
                        Else 'ALMOST NO THIS CASE
                            iCurrArrivalNum = ""
                            iArrival = ""
                        End If
                    Else 'FROM TRUCK
                        
                        iCurrArrivalNum = Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
                        iArrival = Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
                        
                    End If
                Else 'ALMOST NO THIS CASE
                    iCurrArrivalNum = ""
                    iArrival = ""
                End If
                
                
                If Not IsNull(dsCARGO_TRACKING.Fields("COMMODITY_CODE")) Then
                    gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Val(Trim$(dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value))
                    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                    If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME")) Then
                        isCommodityName = Trim$(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value)
                    Else
                        isCommodityName = ""
                    End If
                Else
                    isCommodityName = ""
                End If
                
                If Not IsNull(dsCARGO_TRACKING.Fields("QTY_DAMAGED")) Then
                    isDmgd = Trim$(dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value)
                Else
                    isDmgd = ""
                End If
                        
                If Not IsNull(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION")) Then
                    isLocation = Trim$(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value)
                Else
                    isLocation = ""
                End If
                
                 If Not IsNull(dsCARGO_TRACKING.Fields("VARIETY")) Then
                    iVariety = Trim$(dsCARGO_TRACKING.Fields("VARIETY").Value)
                Else
                    iVariety = ""
                End If
                
                If Not IsNull(dsCARGO_TRACKING.Fields("CARGO_SIZE")) Then
                    iSize = Trim$(dsCARGO_TRACKING.Fields("CARGO_SIZE").Value)
                Else
                    iSize = ""
                End If
                
                If Not IsNull(dsCARGO_TRACKING.Fields("EXPORTER_CODE")) Then
                    iChep = Trim$(dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value)
                Else
                    iChep = ""
                End If
                
                isString1 = Trim$(isCustomerName)
                isString1 = Left$(isString1 & "          ", 10)
                isString2 = Trim$(isCommodityName)
                isString2 = Left$(isString2 & "          ", 10)
                isString3 = Trim$(isLocation)
                isString3 = Left$(isString3 & "          ", 10)
                isString4 = Trim$(dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value)
                isString4 = Left$(isString4 & "   ", 3)
                isString5 = isDmgd
                isString5 = Left$(isString5 & "   ", 3)
                isString6 = Trim$(dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value)
                isString6 = Left$(isString6 & "   ", 3)
                isString7 = Left$(iArrival & "          ", 10) 'GET SHIP NAME OR ORDER NUMBER
                isString8 = Left$(iVariety & "          ", 10)
                isString9 = Left$(iSize & "   ", 3)
                isString10 = Left$(iChep & " ", 5)
                isString11 = isString8 & "," & isString9 & "," & isString10
                
                dsCARGO_TRACKING.MoveNext
                
                If dsCARGO_TRACKING.EOF Then 'IF THIS IS LAST RECORD
                    
                    isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & "," & isString5 & "," & isString6 & "," & isString7 & "," & isString11 & "," & iCurrArrivalNum & "," & "END"
                    'send message to handheld
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",D" & isString & ")]"
                    Exit Sub
                            
                Else 'IF MORE THAN ONE
                    
                    iNextArrivalNum = Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
                    isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & "," & isString5 & "," & isString6 & "," & isString7 & "," & isString11 & "," & iCurrArrivalNum & "," & iNextArrivalNum
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",D" & isString & ")]"
                    Exit Sub
                End If
            
            'Else 'NOT FOUND, GO TO CREATE THE PALLET
            '    PauseForResponse
            '    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N)]"
            '    Exit Sub
            'End If 'END dsCARGO_TRACKING.RecordCount > 0
        Else 'IF DATE RECEIVED IS NULL, HAVE TO GO BACE RECEIVE FIRST THEN SHIP OUT
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",NPALLET NOT RECEIVED)]"
            Exit Sub
        End If
    Else 'if oracle error
    
        OraDatabase.Close
        Call Initialize_DB
        'send message to handheld
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Just Continue)]" 'should change to what need say on the screen
        Exit Sub
    End If
        

End Sub
Private Sub cShipOutFinal()
    
    Dim iQtyShip As Long
    Dim iDmg As Long
    Dim iAction As Boolean
    Dim iPreQtyInHouse As Long
    Dim iArrivalNum As String
    Dim dsORD_DETAIL As Object
    Dim msg As String
    Dim iQtyShipped As Integer
    Dim iQtyLeft As Integer
    Dim Mark_Flag As String
    Dim iFromTruck As Boolean
    Dim iOrderQty As Integer
    Dim dsORDER As Object
    
    
   On Error GoTo ErrHandler
    
    
    'fields: 1."SOF", 2.User, 3.ship to Cust, 4.Order, 5.Barcode, 6. Supervisor, 7.Wing, 8."ALL", 9.ARRIVAL NUM, 10.Seal #, 11.Qty Dmgd, 12.Pg#
    'ADD ONE MORE FIELD: SEAL NUM AT LAST FIELD POSITION 10 ON 12/18/2001
    'Oppenheimer Fix
    'If Len(Trim$(BCField(5))) = 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 10)
    'Else
    '    Barcode = Trim$(BCField(5))
    'End If

    'If Len(Trim$(BCField(5))) > 12 And Len(Trim$(BCField(5))) <> 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 12)
    'End If
    Barcode = Trim$(BCField(5))
    
    iQtyShip = 0
    iPreQtyInHouse = 0
    iArrivalNum = ""
    
    If Trim$(BCField(11)) <> "" Then
        iDmg = Val(BCField(11))
    Else
        iDmg = 0
    End If
    
    If Trim$(BCField(8)) <> "ALL" Then
        iQtyShip = Val(BCField(8))
    End If
    
    If (BCField(3) = "440") Or (BCField(3) = "441") Then
        iFromTruck = True
    End If
    
    'GET SEAL NUM FOR THIS ORDER ON 12/18/2001
    gsSqlStmt = "SELECT * FROM SEAL_ORDERS WHERE ORDER_NUM = '" & Trim$(BCField(4)) & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(BCField(3))
    gsSqlStmt = gsSqlStmt & " AND SEAL_NUM = '" & Trim$(BCField(10)) & "'"
    Set dsSEAL_ORDERS = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If dsSEAL_ORDERS.RecordCount = 0 Then
         'ADD INTO SEAL_ORDERS TABLE
            dsSEAL_ORDERS.AddNew
            dsSEAL_ORDERS.Fields("ORDER_NUM").Value = Trim$(BCField(4))
            dsSEAL_ORDERS.Fields("CUSTOMER_ID").Value = Val(BCField(3))
            dsSEAL_ORDERS.Fields("SEAL_NUM").Value = Trim$(BCField(10))
            dsSEAL_ORDERS.Update
        'ELSE SEAL NUM IS THERE
        End If
    Else
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X)]"
        Exit Sub
    End If
    
    
    gsSqlStmt = "SELECT MAX(ACTIVITY_NUM) MACTIVITY_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(BCField(3))
    
    If Trim$(BCField(9)) <> "" Then
        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(BCField(9)) & "'"
    End If
    
    Set dsCARGO_ACTIVITY_A = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(2)) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(BCField(3))
    If Trim$(BCField(9)) <> "" Then
        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(BCField(9)) & "'"
    End If
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If Trim$(BCField(9)) = "" Then
        iArrivalNum = Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
    Else
        iArrivalNum = Trim$(BCField(9))
    End If
    
    
    iPreQtyInHouse = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value
  
    
    If OraDatabase.LastServerErr = 0 Then
    
        If iPreQtyInHouse = 0 Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XCANNOT SHIP 0 CASES)]"
            Exit Sub
        End If
   
        If Trim$(BCField(8)) = "ALL" Then
            iQtyShip = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value
        ElseIf iPreQtyInHouse = Trim$(BCField(8)) Then
            iQtyShip = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value
        Else
            iQtyShip = Trim$(BCField(8))
        End If
        
        iAction = False
        
        'if iQtyShip = 0 and iDmg = 0 then ignore go to next pallet

        If iQtyShip = 0 Then 'DO NOT HAVE TO PROCESS THE CARGO ACTIVITY TABLE AND ACTIVITY LOG TABLE, BUT NEED DISPLAY THE ORDER INFO
            'GET PALLET COUNT
            gsSqlStmt = "SELECT DISTINCT PALLET_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
            gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
            Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            'GET SUM QTY CHANGE
            
            gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
            gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
            Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            
            If OraDatabase.LastServerErr = 0 Then
                If dsCARGO_ACTIVITY.RecordCount = 0 Then
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YNew Order)]"
                    Exit Sub
                Else
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & dsCARGO_ACTIVITY.RecordCount & " Plts)]"
                    Exit Sub
                End If
            Else
                OraDatabase.Close
                Call Initialize_DB
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
                Exit Sub
            End If
        
        Else
            If (BCField(12) = "1") Then 'If Page# is 1, check if scanning this pallet will exceed the qty required for the order
                gsSqlStmt = "SELECT * FROM DC_ORDER WHERE ORDERNUM = '" & Trim$(BCField(4)) & "'"
                Set dsORDER = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                
                gsSqlStmt = "SELECT * FROM DC_ORDERDETAIL WHERE ORDERNUM = '" & Trim$(BCField(4)) & "'"
                Set dsORD_DETAIL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                
                iOrderQty = dsORD_DETAIL.Fields("ORDERQTY").Value

                gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT"
                gsSqlStmt = gsSqlStmt & " WHERE CA.CUSTOMER_ID = " & Val(Trim$(BCField(3)))
                gsSqlStmt = gsSqlStmt & " AND CA.ORDER_NUM = '" & Trim$(BCField(4)) & "'"
                gsSqlStmt = gsSqlStmt & " AND CA.SERVICE_CODE <> 12 AND (CA.ACTIVITY_DESCRIPTION IS NULL OR CA.ACTIVITY_DESCRIPTION <>'VOID')"
                gsSqlStmt = gsSqlStmt & " AND CA.PALLET_ID = CT.PALLET_ID"
                gsSqlStmt = gsSqlStmt & " AND CT.ARRIVAL_NUM  = '" & dsORDER.Fields("VESSELID").Value & "'"
                If (dsORD_DETAIL.RecordCount > 1) Then
                    gsSqlStmt = gsSqlStmt & " AND CT.EXPORTER_CODE = '" & dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value & "'"
                    gsSqlStmt = gsSqlStmt & " AND CT.CARGO_SIZE = '" & dsCARGO_TRACKING.Fields("CARGO_SIZE").Value & "'"
                End If
                Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                
                If Not IsNull(dsCARGO_ACTIVITY_SUM.Fields("SUMQty")) Then
                    iQtyShipped = dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value
                Else
                    iQtyShipped = 0
                End If
                
                If (dsORD_DETAIL.RecordCount > 1) Then
                    If Not iFromTruck Then 'This is temp until the domestic picklist is generated for orders
                        gsSqlStmt = "SELECT * FROM DC_ORDERDETAIL DE,"
                        If Not iFromTruck Then
                            gsSqlStmt = gsSqlStmt & "  DC_PICKLIST PI"
                        ElseIf iFromTruck Then
                            gsSqlStmt = gsSqlStmt & "  DC_DOMESTIC_PICKLIST PI"
                        End If
                        gsSqlStmt = gsSqlStmt & " WHERE DE.ORDERNUM = '" & Trim$(BCField(4)) & "'"
                        gsSqlStmt = gsSqlStmt & " AND DE.ORDERDETAILID = PI.ORDERDETAILID"
                        gsSqlStmt = gsSqlStmt & " AND PI.PICKLISTSIZE = '" & dsCARGO_TRACKING.Fields("CARGO_SIZE").Value & "'"
                        gsSqlStmt = gsSqlStmt & " AND PI.PACKINGHOUSE = '" & dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value & "'"
                        Set dsORD_DETAIL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                    End If
                    If (dsORD_DETAIL.RecordCount = 0) Then
                        'This pallet does not equal a size requested. Add requested amounts together
                        gsSqlStmt = "SELECT * FROM DC_ORDERDETAIL WHERE ORDERNUM = '" & Trim$(BCField(4)) & "' "
                        Set dsORD_DETAIL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                        iOrderQty = dsORD_DETAIL.Fields("ORDERQTY").Value
                        Do
                            dsORD_DETAIL.MoveNext
                            If dsORD_DETAIL.EOF = True Then
                                Exit Do
                            End If
                            iOrderQty = iOrderQty + dsORD_DETAIL.Fields("ORDERQTY").Value
                        Loop
                        
                        gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
                        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
                        gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
                        Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                
                        If Not IsNull(dsCARGO_ACTIVITY_SUM.Fields("SUMQty")) Then
                            iQtyShipped = dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value
                        Else
                            iQtyShipped = 0
                        End If
                    ElseIf (dsORD_DETAIL.RecordCount = 1) Then
                        iOrderQty = dsORD_DETAIL.Fields("ORDERQTY").Value
                    ElseIf (dsORD_DETAIL.RecordCount > 1) Then
                        iOrderQty = dsORD_DETAIL.Fields("ORDERQTY").Value
                        Do
                            dsORD_DETAIL.MoveNext
                            If dsORD_DETAIL.EOF = True Then
                                Exit Do
                            End If
                            iOrderQty = iOrderQty + dsORD_DETAIL.Fields("ORDERQTY").Value
                        Loop
                    End If
                End If
                
                If ((iQtyShip + iQtyShipped) > iOrderQty) Then
                    msg = "SHIPPING THIS PALLETWILL EXCEED QTY OF \nCASES REQUESTED."
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
                    Exit Sub
                End If

                
                'Add totals together and check if all the totals do not exceed the grand total
                'May seem repetative, but i dont see any other way of checking the grand total without doing this
                gsSqlStmt = "SELECT * FROM DC_ORDERDETAIL WHERE ORDERNUM = '" & Trim$(BCField(4)) & "' "
                Set dsORD_DETAIL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                iOrderQty = dsORD_DETAIL.Fields("ORDERQTY").Value
                Do
                    dsORD_DETAIL.MoveNext
                    If dsORD_DETAIL.EOF = True Then
                        Exit Do
                    End If
                    iOrderQty = iOrderQty + dsORD_DETAIL.Fields("ORDERQTY").Value
                Loop

                gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
                gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
                gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
                Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                
                If Not IsNull(dsCARGO_ACTIVITY_SUM.Fields("SUMQty")) Then
                    iQtyShipped = dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value
                Else
                    iQtyShipped = 0
                End If

                If ((iQtyShip + iQtyShipped) > iOrderQty) Then
                    msg = "SHIPPING THIS PALLETWILL EXCEED QTY OF \nCASES REQUESTED."
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
                    Exit Sub
                End If
            End If
            iAction = True
            If iQtyShip <= dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value Then
                
                'Begin a transaction
                OraSession.BeginTrans
               
                dsCARGO_TRACKING.Edit
                'If dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value >= Val(BCField(6)) Then
                dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value - iQtyShip
                dsCARGO_TRACKING.Fields("MARK").Value = "SHIPPED"
                'dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value = iDmg
                'If Trim$(BCField(7)) <> "" Then
                    'dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = Trim$(BCField(7))
                'End If
                'End If
                dsCARGO_TRACKING.Update
                Mark_Flag = "SHIPPED"
                Call InsertCTE(iArrivalNum, dsCARGO_TRACKING.Fields("RECEIVER_ID").Value, Barcode, Mark_Flag)
                  
                If OraDatabase.LastServerErr = 0 And iQtyShip <> 0 Then
                    'CHECK IF SAME PALLET ON SAME ORDER#
'                    gsSqlStmt = "SELECT MAX(ACTIVITY_NUM) MACTIVITY_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
'                    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(BCField(3))
'                    If Trim$(BCField(9)) <> "" Then
'                        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(BCField(9)) & "'"
'                    End If
'                    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
'                    gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
'                    Set dsCARGO_ACTIVITY_CHECK = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'
                    
                    dsCARGO_ACTIVITY_ALL.AddNew
                    dsCARGO_ACTIVITY_ALL.Fields("PALLET_ID").Value = Barcode
                    dsCARGO_ACTIVITY_ALL.Fields("ARRIVAL_NUM").Value = iArrivalNum
                    'dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_DESCRIPTION").Value = "User:" & BCField(2)
                    If Not IsNull(dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM")) Then
                        dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM").Value + 1
                    Else
                        dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = 2
                    End If
                    dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 6
                    dsCARGO_ACTIVITY_ALL.Fields("QTY_CHANGE").Value = iQtyShip
                    dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = iPreQtyInHouse - iQtyShip
                    dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
                    dsCARGO_ACTIVITY_ALL.Fields("ORDER_NUM").Value = BCField(4)
                    dsCARGO_ACTIVITY_ALL.Fields("CUSTOMER_ID").Value = BCField(3)
                    dsCARGO_ACTIVITY_ALL.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
                    dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_BILLED").Value = Trim$(BCField(7))
                    dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_DESCRIPTION").Value = "DMG:" & iDmg & ":" & BCField(2)
                    dsCARGO_ACTIVITY_ALL.Fields("BATCH_ID").Value = iDmg
                    dsCARGO_ACTIVITY_ALL.Update
                    
                    
                    
                    'OraSession.BeginTrans
               
'                    dsCARGO_TRACKING.Edit
'                    dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value
'                    dsCARGO_TRACKING.Update
                
                End If
            Else
                PauseForResponse
                'tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YOnly " & dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value & " available)]"
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X  CAN NOT SHIP OUT  MORE THAN " & dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value & " CASES.)]"
                Exit Sub
            End If 'end if recordcount >0
        End If 'end if 0,0
    End If 'END OraDatabase.LastServerErr = 0
    
    If OraDatabase.LastServerErr = 0 And iAction Then
            OraSession.CommitTrans
            'PASS LOG INFORMATION
            'Call LOG_ACTIVITY(BCField(1), BCField(2), Barcode, BCField(6), iArrivalNum, BCField(3))
            
            gsSqlStmt = "SELECT DISTINCT PALLET_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
            gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
            Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            'GET SUM QTY CHANGE
            
            gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
            gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
            Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            
            If OraDatabase.LastServerErr = 0 Then
                Call SendToOutLog(Reader_Addr, BCField(1), EZC_Record, BCField(6), BCField(2), dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value, BCField(5), dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value, dsCARGO_TRACKING.Fields("RECEIVER_ID").Value)
                If dsCARGO_ACTIVITY.RecordCount = 0 Then
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YNew Order)]"
                    Exit Sub
                Else
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & dsCARGO_ACTIVITY.RecordCount & " Plts)]"
                    Exit Sub
                End If
            Else
                OraDatabase.Close
                Call Initialize_DB
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
                Exit Sub
            End If
    Else
        If OraDatabase.LastServerErr <> 0 Then
            OraDatabase.Close
            Call Initialize_DB
        End If
        
       gsSqlStmt = "SELECT DISTINCT PALLET_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
       gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
       gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
       Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
       'GET SUM QTY CHANGE
       
       gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
       gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
       gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
       Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
       
       If OraDatabase.LastServerErr = 0 Then
           If dsCARGO_ACTIVITY.RecordCount = 0 Then
               PauseForResponse
               tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YNew Order)]"
               Exit Sub
           Else
               PauseForResponse
               tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & dsCARGO_ACTIVITY.RecordCount & " Plts)]"
               Exit Sub
           End If
       Else
           OraDatabase.Close
           Call Initialize_DB
           PauseForResponse
           tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
           Exit Sub
       End If

    End If

ErrHandler:

    If Err.Number <> 0 Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        Exit Sub
    End If
    
End Sub
Private Sub ccloseShippingOrder()
    'FIELDS:  1. SOC, 2. USER, 3. Ship to Cust#,4. ORDER#,5. SUPERVISOR
    
    ' Insert field into Order_header - This is for the automated E-Mail
    gsSqlStmt = "SELECT * FROM ORDER_HEADER"
    Set dsORDER_HEADER = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
            dsORDER_HEADER.AddNew
            dsORDER_HEADER.Fields("ORDER_NUM").Value = Trim$(BCField(4))
            dsORDER_HEADER.Fields("CUSTOMER_ID").Value = Val(BCField(3))
            dsORDER_HEADER.Fields("E_MAILED").Value = "N"
            dsORDER_HEADER.Update
    End If
    
    PauseForResponse
    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
    
End Sub
Private Sub voidInquery()
    'FIELDS: 1.VDI, 2. BARCODE,3. PAGE#, 4.CUSTOMER, 5.ORDER_NUM,6. TYPE OF ORDER(A. FROM TRUCK, B. RETURN, C. TRANSFER. D. TRUCK OUT)

    Dim i As Integer
    Dim isCustomerName As String
    Dim isString1 As String
    Dim isCommodityName As String
    Dim isString2 As String
    Dim isLocation As String
    Dim isString3 As String
    Dim isString4 As String
    Dim isDmgd As String
    Dim isString5 As String
    Dim isString6 As String
    Dim isString7 As String
    Dim iArrival As String
    Dim isDateRcvd As String
    Dim isOrderNum As String
    Dim isOne As Boolean
    Dim isNumRecord As Long
    Dim iCurrArrivalNum As String
    Dim isString As String
    Dim isQtyShipped As Long
    Dim isDateShipped As String
    
    i = 0
    isCustomerName = ""
    isString1 = ""
    isCommodityName = ""
    isString2 = ""
    isLocation = ""
    isString3 = ""
    isString4 = ""
    isDmgd = ""
    isString5 = ""
    isString6 = ""
    isString7 = ""
    iArrival = ""
    isDateRcvd = ""
    isOrderNum = ""
    isOne = True
    isNumRecord = 0
    isString = ""
    iCurrArrivalNum = ""
    isQtyShipped = 0
    isDateShipped = ""
    
    Barcode = Trim$(BCField(2))
    
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = '" & Val(Trim$(BCField(4))) & "'"
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(5)) & "'"
    Select Case Val(BCField(6))
    Case 1: gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 8" 'truck in
    Case 2: gsSqlStmt = gsSqlStmt & " AND (SERVICE_CODE = 7 OR SERVICE_CODE = 13)"  'return
    Case 3: gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 11"  'transfer
    Case 4: gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6"   'truck out
    Case Else:
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XIncorrect Order Type, Try Again.)]"
        Exit Sub
    End Select
    gsSqlStmt = gsSqlStmt & " AND (ACTIVITY_DESCRIPTION is null or ACTIVITY_DESCRIPTION <> 'VOID')"
    gsSqlStmt = gsSqlStmt & " ORDER BY PALLET_ID,ARRIVAL_NUM,ACTIVITY_NUM"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If dsCARGO_ACTIVITY.RecordCount > 1 Then
        isOne = False
    End If
    
    isNumRecord = dsCARGO_ACTIVITY.RecordCount

    If OraDatabase.LastServerErr = 0 Then
        If dsCARGO_ACTIVITY.RecordCount = 0 Then
            ' NO PALLET
            'MESSAGE SAY UNKNOWN PALLET
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XUNKNOWN PALLET)]"
            Exit Sub
        End If
    Else 'ORACLE ERROR
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XORACLE ERROR, TRY IT AGAIN)]"
        Exit Sub
    End If
       
    i = i + 1
    'get particular pallet
    
    While i < Val(BCField(3))
        dsCARGO_ACTIVITY.MoveNext
        i = i + 1
    Wend
    
    'CHECK IF FROM TRANSFER, SHOULD ONLY VOID THE ORIGINAL CUST
    If Val(BCField(6)) = 3 Then
        If Trim$(dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value) = Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) And dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 11 Then  'IF ARRIVAL_NUM = ORDER_NUM
            'COULD NOT VOID PALLETS THAT TRANSFER FROM OTHER CUST
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XCAN NOT VOID PALLET, WRONG CUSTOMER ID,      TRY AGAIN.)]"
            Exit Sub
        End If
    End If
    
    
    If Val(BCField(6)) = 4 Then
        'CHECK THE RETURN AND SHIPOUT QTY WHEN WANT TO VOID THE SHIP OUT FUNCTION
        'IF SHIP OUT AND RETURN QTY IS SAME, THEN NO VOID ON THE SHIP OUT ISSUE, BUT COULD BE ON RETURN
        gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUM_CHANGE_THIS_PALLET FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(4)))
        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(5)) & "'"
        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
        gsSqlStmt = gsSqlStmt & " AND (SERVICE_CODE = 7 OR SERVICE_CODE = 13) AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
        Set dsCARGO_RETURN_COUNT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUM_CHANGE_THIS_PALLET FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(4)))
        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(5)) & "'"
        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
        gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6 AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
        Set dsCARGO_SHIP_COUNT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        If Not IsNull(dsCARGO_RETURN_COUNT.Fields("SUM_CHANGE_THIS_PALLET")) Then
        
        'If Trim$(-dsCARGO_RETURN_COUNT.Fields("SUM_CHANGE_THIS_PALLET").Value) = Trim$(dsCARGO_SHIP_COUNT.Fields("SUM_CHANGE_THIS_PALLET").Value) Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X PALLET HAS BEEN RE-TURNED, CAN NOT VOID   IN THIS SCREEN.)]"
            Exit Sub
        'End If
        End If
        
    End If
    
    
    'GET PARTICULAR PALLET INFORMATION
    
    'GET INFORMATION FOR DISPLAYING
    If Val(BCField(6)) = 2 Then
        isQtyShipped = -dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value
    Else
        isQtyShipped = dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value
    End If
    
    isDateShipped = Format$(dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value, "MM/DD/YY")
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value))
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME")) Then
        isCustomerName = Left$(Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value), 12)
    Else
        isCustomerName = ""
    End If
    
    'GET ARRIVAL NUM AND ARRIVAL NAME IF FROM SHIP. IF FROM TRUCK, GIVE THE ARRIVAL NUM
    If Not IsNull(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM")) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Val(Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value))
        Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If dsVESSEL_PROFILE.RecordCount > 0 Then 'CARGO FROM SHIP
            If Not IsNull(dsVESSEL_PROFILE.Fields("VESSEL_NAME")) Then
                iCurrArrivalNum = Trim(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)
                iArrival = iCurrArrivalNum & "-" & Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value)
            Else 'ALMOST NO THIS CASE
                iCurrArrivalNum = ""
                iArrival = ""
            End If
        Else 'FROM TRUCK
            iCurrArrivalNum = Trim(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)
            iArrival = Trim(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)
        End If
    Else 'ALMOST NO THIS CASE
        iCurrArrivalNum = ""
        iArrival = ""
    End If
    
    'PREPARE CARGO TRACKING INFORMATION
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Barcode & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID= " & Val(Trim$(BCField(4)))
    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If Not IsNull(dsCARGO_TRACKING.Fields("COMMODITY_CODE")) Then
        gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Val(Trim$(dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value))
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME")) Then
            If (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5605") Then
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 11), 5)
            ElseIf (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5603") Then
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 4) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 18), 4)
            Else
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 14), 5)
            End If
            isCommodityName = dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value & "-" & isCommodityName
        Else
            isCommodityName = dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value
        End If
    Else
        isCommodityName = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("QTY_DAMAGED")) Then
        isDmgd = Trim$(dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value)
    Else
        isDmgd = ""
    End If
            
    If Not IsNull(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION")) Then
        isLocation = Trim$(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value)
    Else
        isLocation = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED")) Then
        isDateRcvd = Format(Trim$(dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value), "MM/DD/YY")
    Else
        isDateRcvd = ""
    End If
    
    isString1 = Trim$(isCustomerName)
    isString1 = Left$(isString1 & "          ", 10)
    isString2 = Trim$(isCommodityName)
    isString2 = Left$(isString2 & "          ", 14)
    isString3 = Trim$(isLocation)
    isString3 = Left$(isString3 & "          ", 10)
    isString4 = Trim$(dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value)
    'isString4 = Left$(isString4 & "   ", 3)
    isString5 = isDmgd
    'isString5 = Left$(isString5 & "   ", 3)
    isString6 = Trim$(dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value)
    'isString6 = Left$(isString6 & "   ", 3)
    isString7 = Left$(iArrival & "          ", 10) 'GET SHIP NAME OR ORDER NUMBER
    isString7 = Left$(isString7 & "," & isDateRcvd & "                     ", 20)
    isString7 = Left$(isString7 & "," & Trim$(isDateShipped) & "," & isQtyShipped & "                             ", 33)
    
    If Val(BCField(3)) = 1 Then
        If isNumRecord = 1 Then
            isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & "," & isString5 & "," & isString6 & "," & isString7 & "," & isNumRecord
            'send message to handheld
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & isString & ")]"
            Exit Sub
        Else
            isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & "," & isString5 & "," & isString6 & "," & isString7 & "," & isNumRecord
            'send message to handheld
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",D" & isString & ")]"
            Exit Sub
        End If
        
    Else 'IF MORE THAN ONE
        isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & "," & isString5 & "," & isString6 & "," & isString7 & "," & isNumRecord
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",D" & isString & ")]"
        Exit Sub
    End If
    
    
End Sub

Private Sub voidPallet()
'1. VDP, 2. CUST, 3. ORDER #,4. void type, 5. BARCODE, 6. QTY VOID, 7. USER, 8. SUPERVISOR, 9. PAGE#, ( TYPE OF ORDER(A. FROM TRUCK, B. RETURN, C. TRANSFER. D. TRUCK OUT)
'FIELDS:
   Dim i As Integer
   Dim Mark_Flag As String
   
   On Error GoTo ErrHandler
    
   i = 0
   
    'If Len(Trim$(BCField(4))) = 20 Then
    '    Barcode = Right(Trim$(BCField(4)), 10)
    'Else
    '    Barcode = Trim$(BCField(4))
    'End If
    
    'If Len(Trim$(BCField(4))) > 12 And Len(Trim$(BCField(4))) <> 20 Then
    '    Barcode = Right(Trim$(BCField(4)), 12)
    'End If
    Barcode = Trim$(BCField(5))
    
'    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(7)) & "'"
'    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(2)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
    
    'VOID ONLY EFFECT TRUCK IN AND TRUCK OUT.
    
    Select Case Val(BCField(4))
    Case 1: gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 8"
    Case 2: gsSqlStmt = gsSqlStmt & " AND (SERVICE_CODE = 7 OR SERVICE_CODE = 13)"
    Case 3: gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 11"
    Case 4: gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6"
    Case Else:
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XIncorrect Order Type, Try Again.)]"
        Exit Sub
    End Select
    
    'gsSqlStmt = gsSqlStmt & " AND QTY_CHANGE = " & Val(BCField(5))
    gsSqlStmt = gsSqlStmt & " AND (ACTIVITY_DESCRIPTION is null or ACTIVITY_DESCRIPTION <> 'VOID')"
    gsSqlStmt = gsSqlStmt & " ORDER BY PALLET_ID,ARRIVAL_NUM,ACTIVITY_NUM"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    i = i + 1
    While i < Val(BCField(9))
        dsCARGO_ACTIVITY.MoveNext
        i = i + 1
    Wend
    
    If Val(BCField(4)) = 2 Then
        If dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value <> -Val(BCField(6)) Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X  VOID QTY DOES NOT  MATCH ORIGINAL QTY,     TRY AGAIN.)]"
            Exit Sub
        End If
    Else
        If dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value <> Val(BCField(6)) Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X  VOID QTY DOES NOT  MATCH ORIGINAL QTY,     TRY AGAIN.)]"
            Exit Sub
        End If
    End If
    
    'GET ACTIVITY NUM
    gsSqlStmt = "SELECT MAX(ACTIVITY_NUM) MACTIVITY_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(2)))
    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value & "'"
    Set dsCARGO_ACTIVITY_A = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(7)) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.BeginTrans
        
        'PREPARE FOR UPDATING
        gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Barcode & "'"
        gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = '" & dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value & "'"
        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value & "'"
        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
  
        If OraDatabase.LastServerErr = 0 Then
        
            'GIVE QTY BACK TO IN HOUSE
            dsCARGO_TRACKING.Edit
            Select Case dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value
            
            Case 6: dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value + Val(Trim$(BCField(6)))
                    dsCARGO_TRACKING.Fields("MARK").Value = "AT POW"
                    
            
            Case 8: 'dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value - Val(Trim$(BCField(6)))
                    
                    'CHECK HAS ANY SHIP OUT, IF NOT, GET RID OF SYSTEM
                                            
                    'CHECK IF HAS ANY SHIP OUT ACTIVITY
                    gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUM_CHANGE FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
                    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
                    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & (dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
                    gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6 "
                    gsSqlStmt = gsSqlStmt & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
                    
                    Set dsCARGO_ACTIVITY_CHECK = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                    If Not IsNull(dsCARGO_ACTIVITY_CHECK.Fields("SUM_CHANGE")) Then
                        'If dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value < Val(BCField(6)) + dsCARGO_ACTIVITY_CHECK.Fields("SUM_CHANGE").Value > 0 Then
                            PauseForResponse
                            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X   PALLET HAS BEEN  SHIPPED,CAN NOT VOID   IN THIS SCREEN.)]"
                            Exit Sub
                        'End If
                    End If
                    
                    'PUT DELETE INFO INTO PALLET VOID TABLE
                    'OraDatabase.BeginTrans
'                    dsPALLET_VOID_ALL.AddNew
'                    dsPALLET_VOID_ALL.Fields("PALLET_ID").Value = Barcode
'                    dsPALLET_VOID_ALL.Fields("ARRIVAL_NUM").Value = Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)
'                    dsPALLET_VOID_ALL.Fields("ACTIVITY_NUM").Value = dsCARGO_ACTIVITY.Fields("ACTIVITY_NUM").Value
'                    dsPALLET_VOID_ALL.Fields("SERVICE_CODE").Value = dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value
'                    dsPALLET_VOID_ALL.Fields("QTY_CHANGE").Value = dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value
'                    dsPALLET_VOID_ALL.Fields("QTY_LEFT").Value = dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value
'                    dsPALLET_VOID_ALL.Fields("ACTIVITY_ID").Value = dsCARGO_ACTIVITY.Fields("ACTIVITY_ID").Value
'                    dsPALLET_VOID_ALL.Fields("ORDER_NUM").Value = dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value
'                    dsPALLET_VOID_ALL.Fields("CUSTOMER_ID").Value = dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
'                    dsPALLET_VOID_ALL.Fields("DATE_OF_ACTIVITY").Value = dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value
'                    dsPALLET_VOID_ALL.Update
                    
                    gsSqlStmt = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
                    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
                    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & (dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
                    gsSqlStmt = gsSqlStmt & " AND ACTIVITY_NUM = " & dsCARGO_ACTIVITY.Fields("ACTIVITY_NUM").Value
                    OraDatabase.ExecuteSQL (gsSqlStmt)
                    
                    If (dsCARGO_TRACKING.Fields("MANIFESTED").Value = "N") Then
                        gsSqlStmt = "DELETE FROM CARGO_TRACKING WHERE PALLET_ID = '" & Barcode & "'"
                        gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
                        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & (dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
                        OraDatabase.ExecuteSQL (gsSqlStmt)
                    ElseIf (dsCARGO_TRACKING.Fields("MANIFESTED").Value = "Y") Then
                        dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value = ""
                        dsCARGO_TRACKING.Fields("MARK").Value = "MANIFESTED"
                        dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
                    End If
        
                    
'            Case 7: dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value - Val(Trim$(BCField(6)))
'
'            Case 11: 'VOID THE TRANSFER AND DELETE THE RECORD WHICH CREATES FOR NEW CUSTOEMR
'                    dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value + Val(Trim$(BCField(6)))
                    
            End Select
            
            dsCARGO_TRACKING.Update
            Mark_Flag = "AT POW"
            Call InsertCTE(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value, dsCARGO_TRACKING.Fields("RECEIVER_ID").Value, Barcode, Mark_Flag)
            
            If Val(BCField(4)) = 2 Then
                'GET SHIPPED OUT RECORD AND ERASE THE RETURN MARK FROM ACTIVITY DESCRIPTION FIELD
                gsSqlStmt = "UPDATE CARGO_ACTIVITY SET ACTIVITY_DESCRIPTION = NULL"
                gsSqlStmt = gsSqlStmt & " WHERE PALLET_ID = '" & Barcode & "'"
                gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(2)))
                gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
                gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6"
                gsSqlStmt = gsSqlStmt & " AND ACTIVITY_DESCRIPTION = 'RETURN'"
                OraDatabase.ExecuteSQL (gsSqlStmt)
            End If
            
            If Val(BCField(4)) = 3 Then
                    
                'DELETE CARGO_TRACKING FIRST
                gsSqlStmt = "DELETE FROM CARGO_TRACKING WHERE (PALLET_ID,RECEIVER_ID,ARRIVAL_NUM) IN (SELECT PALLET_ID,CUSTOMER_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
                gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID <> " & dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
                gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(BCField(3)) & "'"
                gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = " & dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value & ")"

'                gsSqlStmt = "DELETE FROM CARGO_TRACKING_NEW WHERE PALLET_ID ='" & Barcode & "'"
'                gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID <> " & dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
'                gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(BCField(3)) & "'"
'
                OraDatabase.ExecuteSQL (gsSqlStmt)
                
                'DELETE CARGO_ACTIVITY
                gsSqlStmt = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
                gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID <> " & dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
                gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(BCField(3)) & "'"
                gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = " & dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value
                OraDatabase.ExecuteSQL (gsSqlStmt)
        
            End If
            
           If Val(BCField(4)) <> 1 Then
                dsCARGO_ACTIVITY.Edit
                dsCARGO_ACTIVITY.Fields("ACTIVITY_DESCRIPTION").Value = "VOID"
                dsCARGO_ACTIVITY.Update
           End If
'            If Val(BCField(4)) <> 1 Then
'
'                'PUT DELETE INFO INTO PALLET VOID TABLE
'                dsPALLET_VOID_ALL.AddNew
'                dsPALLET_VOID_ALL.Fields("PALLET_ID").Value = Barcode
'                dsPALLET_VOID_ALL.Fields("ARRIVAL_NUM").Value = Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)
'                dsPALLET_VOID_ALL.Fields("ACTIVITY_NUM").Value = dsCARGO_ACTIVITY.Fields("ACTIVITY_NUM").Value
'                dsPALLET_VOID_ALL.Fields("SERVICE_CODE").Value = dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value
'                dsPALLET_VOID_ALL.Fields("QTY_CHANGE").Value = dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value
'                dsPALLET_VOID_ALL.Fields("QTY_LEFT").Value = dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value
'                dsPALLET_VOID_ALL.Fields("ACTIVITY_ID").Value = dsCARGO_ACTIVITY.Fields("ACTIVITY_ID").Value
'                dsPALLET_VOID_ALL.Fields("ORDER_NUM").Value = dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value
'                dsPALLET_VOID_ALL.Fields("CUSTOMER_ID").Value = dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
'                dsPALLET_VOID_ALL.Fields("DATE_OF_ACTIVITY").Value = dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value
'                dsPALLET_VOID_ALL.Update
'
'                gsSqlStmt = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
'                gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
'                gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
'                gsSqlStmt = gsSqlStmt & " AND ACTIVITY_NUM = " & dsCARGO_ACTIVITY.Fields("ACTIVITY_NUM").Value
'                OraDatabase.ExecuteSQL (gsSqlStmt)
'
'            End If
        Else
            'EXIT SUB
            'OraSession.Rollback
            OraDatabase.Close
            Call Initialize_DB
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XORACLE ERROR, TRY IT AGAIN.)]"
            Exit Sub
        End If
        
    Else
        OraDatabase.Close
        Call Initialize_DB
        'INVALID PALLET
        'OraSession.Rollback
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XORACLE ERROR, TRY IT AGAIN.]"
        Exit Sub
    End If
    
    'ADD RECORD FOR VOID ACTION
    If OraDatabase.LastServerErr = 0 And Val(BCField(4)) <> 1 Then
        dsCARGO_ACTIVITY_ALL.AddNew
        dsCARGO_ACTIVITY_ALL.Fields("PALLET_ID").Value = Barcode
        dsCARGO_ACTIVITY_ALL.Fields("ARRIVAL_NUM").Value = Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)

        If Not IsNull(dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM")) Then
            dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM").Value + 1
        Else
            dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = 1
        End If
        dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 12
        dsCARGO_ACTIVITY_ALL.Fields("QTY_CHANGE").Value = Val(Trim$(BCField(6)))

        Select Case dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value
        Case 1: dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value - Val(Trim$(BCField(6)))
        Case 6: dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value + Val(Trim$(BCField(6)))

        Case 8: dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value - Val(Trim$(BCField(6)))

        Case 7: dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value - Val(Trim$(BCField(6)))

        Case 11: dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value + Val(Trim$(BCField(6)))

        End Select

        dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
        dsCARGO_ACTIVITY_ALL.Fields("ORDER_NUM").Value = BCField(3)
        dsCARGO_ACTIVITY_ALL.Fields("CUSTOMER_ID").Value = Val(Trim$(BCField(2)))
        dsCARGO_ACTIVITY_ALL.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
        dsCARGO_ACTIVITY_ALL.Update
    End If

    If OraDatabase.LastServerErr = 0 Then
            OraSession.CommitTrans
            'PASS LOG INFORMATION
            'Call LOG_ACTIVITY(BCField(1), BCField(7), Barcode, BCField(8), BCField(3), BCField(2))
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
            Exit Sub
    Else
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XORACLE ERROR, TRY IT AGAIN.)]"
        Exit Sub
    End If

    Exit Sub
    

ErrHandler:

    If Err.Number <> 0 Then
        OraSession.Rollback
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        Exit Sub
    End If
DbError:
    OraDatabase.Close
    Call Initialize_DB
    Err = 0
 '   PauseForResponse
 '   tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XSYSTEM ERROR, TRY IT AGAIN.)]"
    Exit Sub
End Sub

Private Sub CheckCustomer()
   'On Error GoTo DbError
    If (BCField(2) = "439") Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
        Exit Sub
    ElseIf (BCField(2) = "440") Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
        Exit Sub
    ElseIf (BCField(2) = "441") Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
        Exit Sub
    Else
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X)]"
        Exit Sub
    End If
'    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & BCField(2)
'    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
'        PauseForResponse
'        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
'        Exit Sub
'    Else
'        If OraDatabase.LastServerErr <> 0 Then
'            OraDatabase.Close
'            Call Initialize_DB
'        End If
'        PauseForResponse
'        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X)]"
'        Exit Sub
'    End If
    
    Exit Sub
    
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
    
    
End Sub
Private Sub CheckCommodity()
    
  '  On Error GoTo DbError
    
    gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & BCField(2)
    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RecordCount > 0 Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
        Exit Sub
    Else
        If OraDatabase.LastServerErr <> 0 Then
            OraDatabase.Close
            Call Initialize_DB
        End If
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N)]"
        Exit Sub
    End If
    Exit Sub
    
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
    
End Sub
Private Sub CheckKeyedID()
On Error GoTo ErrHandler
'From scanner: KID|barcode(last 10 digits)|arrival #|page #

Dim arrTranstext() As String
Dim PltID As String
Dim sqlStmt As String
Dim pck_hse As String
Dim plt_id As String
Dim strFuncNum As String
Dim retVal As String
Dim intState As Integer
Dim lr_num As String
Dim FPltID As String
Dim pow As String
Dim msg As String

    '' Step-1: Get Entered Pallet_ID
    arrTranstext = Split(Trim(EZC_Record), ",")
    PltID = arrTranstext(1)
    lr_num = Trim$(arrTranstext(2))
    pow = Left(PltID, 3)
    strFuncNum = arrTranstext(3)
    
    
    If (lr_num = "") Then
        If (pow = "POW") Then
            sqlStmt = "SELECT EXPORTER_CODE, PALLET_ID FROM CARGO_TRACKING WHERE PALLET_ID = '" & PltID & "'"
            Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
        Else
            sqlStmt = "SELECT DISTINCT(PALLET_ID), LENGTH(PALLET_ID)THE_LENGTH FROM CARGO_TRACKING WHERE PALLET_ID LIKE '%" & PltID & "%'"
            Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
        End If
    ElseIf (pow = "POW") Then
        sqlStmt = "SELECT EXPORTER_CODE, PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '" & lr_num & "' AND PALLET_ID = '" & PltID & "'"
        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    Else
        sqlStmt = "SELECT LENGTH(PALLET_ID)THE_LENGTH, PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '" & lr_num & "' AND PALLET_ID LIKE '%" & PltID & "%'"
        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    End If
    
    If (dsCARGO_TRACKING.RecordCount = 0) Then
        If (pow = "POW") Then 'send back to scanner that barcode was a relabel but info has not been entered yet. scanner prompts for info.
            msg = "SCAN POW BARCODE."
            retVal = ""
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
            Exit Sub
        ElseIf (lr_num = "") Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "NO BARCODE FOUND." & ")]"
            Exit Sub
        Else ' only time a pallet should be relabeled
            sqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID LIKE '%" & PltID & "%'"
            Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
            If (dsCARGO_TRACKING.RecordCount = 0) Then
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & "INVALID BARCODE. SET ASIDE FOR RELABEL." & ")]"
                Exit Sub
            ElseIf (dsCARGO_TRACKING.RecordCount > 0) Then
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & "PALLET DOESNT MATCH ARRIVAL NUMBER." & ")]"
                Exit Sub
            End If
        End If
    ElseIf (pow = "POW") Then
        'Do not check for the length. Find information
    ElseIf (dsCARGO_TRACKING.Fields("THE_LENGTH").Value = 28 Or 30) Then
        If (lr_num = "") Then 'Does not seem to need a table join, but it would not work otherwise for some reason unknown to me.
            sqlStmt = "SELECT DISTINCT(T.PALLET_ID), E.EXPORTER_CODE FROM CARGO_TRACKING T, CARGO_TRACKING E WHERE T.PALLET_ID LIKE '%" & PltID & "%' AND E.PALLET_ID LIKE '%" & PltID & "%'"
            Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
        Else
            sqlStmt = "SELECT EXPORTER_CODE, PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '" & lr_num & "' AND PALLET_ID LIKE '%" & PltID & "%'"
            Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
        End If
    Else
        msg = "NOT A CLM BARCODE. \nMUST CREATE."
        retVal = dsCARGO_TRACKING.Fields("PALLET_ID").Value
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & msg & "," & retVal & ")]"
        Exit Sub
    End If
    
    If OraDatabase.LastServerErr > 0 Then
        'ORACLE ERROR
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & "DB SERVER ERROR" & ")]"
        Exit Sub
     End If
     If (strFuncNum = 0) Then
        If (dsCARGO_TRACKING.RecordCount = 0) Then
           PauseForResponse
           tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & "NO BARCODE FOUND." & ")]"
           Exit Sub
        ElseIf (dsCARGO_TRACKING.RecordCount = 1) Then
            If (IsNull(dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value)) Then
                pck_hse = "None"
            Else
                pck_hse = dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value
            End If
            
            plt_id = dsCARGO_TRACKING.Fields("PALLET_ID").Value
        
            retVal = plt_id & "," & pck_hse
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & retVal & ")]"
            Exit Sub
        ElseIf (dsCARGO_TRACKING.RecordCount > 1) Then
           If (IsNull(dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value)) Then
               pck_hse = "None"
           Else
               pck_hse = dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value
           End If
           
           plt_id = dsCARGO_TRACKING.Fields("PALLET_ID").Value
           
           retVal = plt_id & "," & pck_hse & "," & dsCARGO_TRACKING.RecordCount
           PauseForResponse
           tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",M" & retVal & ")]"
           Exit Sub
        End If
     Else
        Do
            If (IsNull(dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value)) Then
               pck_hse = "None"
            Else
               pck_hse = dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value
            End If
            plt_id = dsCARGO_TRACKING.Fields("PALLET_ID").Value
            If (strFuncNum = intState) Then
                Exit Do
            End If
            intState = intState + 1
            dsCARGO_TRACKING.MoveNext
        Loop
     retVal = plt_id & "," & pck_hse
     dsCARGO_TRACKING.MoveNext
     If (dsCARGO_TRACKING.EOF = True) Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & retVal & ")]"
        Exit Sub
     Else
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",M" & retVal & ")]"
        Exit Sub
     End If
     End If
     
ErrHandler:

    If Err.Number <> 0 Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        
        Exit Sub
    End If
Exit Sub
End Sub
Private Sub CheckLocation()
    
   ' On Error GoTo DbError
   
    
    If Trim$(BCField(2)) = "NO" Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
        Exit Sub
    Else
        'gsSqlStmt = "SELECT * FROM LOCATION_CATEGORY WHERE LOCATION_TYPE = '" & BCField(2) & "'"
        'Set dsLOCATION_CATEGORY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        'If OraDatabase.LastServerErr = 0 And dsLOCATION_CATEGORY.RecordCount > 0 Then
        '    PauseForResponse
        '    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
        'Else
        '    If OraDatabase.LastServerErr <> 0 Then
        '        OraDatabase.Close
        '        Call Initialize_DB
        '    End If
        
        If Len(Trim$(BCField(2))) > 12 Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N)]"
            Exit Sub
        Else
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
            Exit Sub
        End If
    End If
    
    Exit Sub
    
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
End Sub
Private Sub CheckPckHse()
'CPH|PACKING HOUSE
On Error GoTo ErrHandler:
    
Dim arrTranstext() As String
Dim pck_hse As String

    'Step-1: Get Entered Packing House
    arrTranstext = Split(Trim(EZC_Record), ",")
    pck_hse = arrTranstext(1)
    
    
    gsSqlStmt = "SELECT EXPORTER_CODE FROM CARGO_TRACKING WHERE EXPORTER_CODE = '" & pck_hse & "'"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr > 0 Then
        'ORACLE ERROR
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "DB SERVER ERROR" & ")]"
        Exit Sub
    
    ElseIf (dsCARGO_TRACKING.RecordCount = 0) Then
        'An invalid Packing House was entered
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "Invalid Packing House" & ")]"
        Exit Sub
        
    ElseIf (dsCARGO_TRACKING.RecordCount > 0) Then
        'A valid Packing House was entered
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & pck_hse & ")]"
        Exit Sub
    End If

ErrHandler:

    If Err.Number <> 0 Then
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        
        Exit Sub
    End If


End Sub
Private Sub CheckLRNum()
'LRN|VESSEL#|TYPE|SUPER|CHECKER
On Error GoTo ErrHandler:
    
Dim arrTranstext() As String
Dim ln As String
Dim arr_type As String
Dim dsARRIVAL As Object

    '' Step-1: Get Entered LR_NUM
    arrTranstext = Split(Trim(EZC_Record), ",")
    ln = arrTranstext(1)
    arr_type = arrTranstext(2)
    
    If (arr_type = "VESSEL") Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '" & ln & "'"
        Set dsARRIVAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    ElseIf (arr_type = "TRUCK IN") Then
        gsSqlStmt = "SELECT DISTINCT ARRIVAL_NUM FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '" & ln & "'"
        Set dsARRIVAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    End If
    
    If OraDatabase.LastServerErr > 0 Then
        'ORACLE ERROR
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "DB SERVER ERROR" & ")]"
        Exit Sub
    
    ElseIf (dsARRIVAL.RecordCount = 0) Then
       
       '' An invalid lr_num was entered
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "Invalid Arrival Num" & ")]"
        Exit Sub
        
    ElseIf (dsARRIVAL.RecordCount = 1) Then
        If (dsARRIVAL.Fields("SHIP_PREFIX").Value = "CLEMENTINES") Then
            '' A valid lr num was entered
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & ln & ")]"
            Exit Sub
        Else
            'An invalid lr num was entered
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "Not A Clementine Vessel!" & ")]"
            Exit Sub
        End If
    
    ElseIf (dsARRIVAL.RecordCount > 1) Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "Multiple Arrival Numbers Found. Contact Office" & ")]"
        Exit Sub
    End If

ErrHandler:

    If Err.Number <> 0 Then
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        
        Exit Sub
    End If


End Sub
Private Sub OrderInformation()
'FIELDS: 1. RVO, 2. CUST, 3. ORDER #, 4. PAGE#
'DISPLAY ALL RECORDS INCLUDE RETURN, AND VOID
    Dim iRecordCount As Long
    Dim iPageNum As Long
    Dim iLine As Long
    Dim iTotalPage As Long
    Dim iMod As Integer
    Dim J As Integer
    Dim imyString(6) As String
    Dim i As Integer
    Dim bmyString1 As String
    Dim bmyString2 As String
    Dim bmyString3 As String
    Dim bmyString4 As String
    Dim iServiceString As String
    Dim iArrivalInfo As String
    Dim imyStringD As String
    Dim iTransferFrom As Boolean
    
   
    For J = 1 To 6
        imyString(J) = ""
    Next
    
    bmyString1 = ""
    bmyString2 = ""
    bmyString3 = ""
    bmyString4 = ""
    imyStringD = ""
    iMod = 0
    J = 1
    iLine = 0
    iRecordCount = 0
    iPageNum = Val(Trim$(BCField(4)))
    iTransferFrom = False
    
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(2)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
    gsSqlStmt = gsSqlStmt & " ORDER BY PALLET_ID,ARRIVAL_NUM,ACTIVITY_NUM"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    iRecordCount = dsCARGO_ACTIVITY.RecordCount
    If iRecordCount > 0 Then
        
        If iRecordCount <= 6 Then
            iTotalPage = 1
        Else
            If iRecordCount Mod 6 >= 1 Then
                'iTotalPage = Round(iRecordCount / 6) + 1
                iTotalPage = Val(Left(Round(iRecordCount / 6, 1), InStr(1, Round(iRecordCount / 6, 1), ".") - 1)) + 1
            Else
                iTotalPage = Round(iRecordCount / 6)
            End If
        End If
        
        i = i + 1
        
        While i <= (iPageNum - 1) * 6 'SKIP THE PREVIOUS PAGE
            dsCARGO_ACTIVITY.MoveNext
            i = i + 1
        Wend
        
        
        'GET SIX RECORDS, STORE IN THE ARRAY
        Do While i <= iPageNum * 6
            
            If Not dsCARGO_ACTIVITY.EOF Then
                If dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 6 Then
                    iServiceString = "O"
                ElseIf dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 7 Or dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 13 Then
                    iServiceString = "R"
                ElseIf dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 8 Then
                    iServiceString = "I"
                'ElseIf dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 9 Then 'RECOUP
                '    iServiceString = "U"
                ElseIf dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 12 Then
                    iServiceString = "V"
                ElseIf dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 11 Then 'TRANSFER
                    iServiceString = "T"
                End If
                
                'GET ARRIVAL NUM AND ARRIVAL NAME IF FROM SHIP. IF FROM TRUCK, GIVE THE ARRIVAL NUM
                If Not IsNull(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM")) Then
                    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Val(Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value))
                    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                    If dsVESSEL_PROFILE.RecordCount > 0 Then 'CARGO FROM SHIP
                        If Not IsNull(dsVESSEL_PROFILE.Fields("VESSEL_NAME")) Then
                            iArrivalInfo = Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value)
                        Else 'ALMOST NO THIS CASE
                            iArrivalInfo = ""
                        End If
                    Else 'FROM TRUCK
                        iArrivalInfo = Trim(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)
                    End If
                Else 'ALMOST NO THIS CASE
                    iArrivalInfo = ""
                End If
                
                bmyString1 = Right$(dsCARGO_ACTIVITY.Fields("PALLET_ID").Value, 13)
                bmyString2 = Right$("   " & Trim$(dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value), 4)
                bmyString3 = Right$("   " & iServiceString, 3)
                bmyString4 = Left$(iArrivalInfo & "          ", 10)
                imyString(J) = Left$(bmyString1 & bmyString2 & bmyString3 & bmyString4 & "                           ", 30)
                
            Else
                imyString(J) = "                              "
                'J = J + 1
            End If
            'imyStringD = imyStringD & imyString(J)
            J = J + 1
            If Not dsCARGO_ACTIVITY.EOF Then
                dsCARGO_ACTIVITY.MoveNext
            End If
            i = i + 1
        Loop
        i = i - 1
        'SEND STRINGS FOR DIPLAYING THE PALLETS ON THE HANDHELD
        J = 1
        While J <= 6
            imyStringD = imyStringD & imyString(J) & ","
            'CHECK FOR LINE 4
            If J = 4 Then
                'If i <= 6 Then ' FIRST PAGE, NEED PAGE TOTAL
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & imyStringD & iTotalPage & ")]"
                    'Exit Sub
'                Else 'DO NOT NEED PAGE #
'                    PauseForResponse
'                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & imyStringD & ")]"
'                    'Exit Sub
'                End If
                imyStringD = ""
            ElseIf J = 6 Then
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & imyStringD & ")]"
                    'Exit Sub
            End If
            J = J + 1
        Wend
           
    Else
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
        Exit Sub
    End If
    
End Sub
Private Sub PalletInformation()
'fields: 1. LOP, 2. barcode, 3. PAGES
    Dim i As Integer
    Dim isCustomerName As String
    Dim isString1 As String
    Dim isCommodityName As String
    Dim isString2 As String
    Dim isLocation As String
    Dim isString3 As String
    Dim isString4 As String
    Dim isDmgd As String
    Dim isString5 As String
    Dim isString6 As String
    Dim isString7 As String
    Dim isString8 As String
    Dim isString9 As String
    Dim isString10 As String
    Dim isString11 As String
    Dim isString12 As String
    
    Dim iArrival As String
    Dim isDateRcvd As String
    Dim isOrderNum As String
    Dim isOne As Boolean
    Dim isNumRecord As Long
    Dim iCurrArrivalNum As String
    Dim isString As String
    Dim isVariety As String
    Dim isSize As String
    Dim isPckHse As String
    Dim Plt_Status As String
    Dim dsARRIVAL As Object
    Dim correctvessel As Boolean
    
    i = 0
    isCustomerName = ""
    isString1 = ""
    isCommodityName = ""
    isString2 = ""
    isLocation = ""
    isString3 = ""
    isString4 = ""
    isDmgd = ""
    isString5 = ""
    isString6 = ""
    isString7 = ""
    isString8 = ""
    isString9 = ""
    isString10 = ""
    isString11 = ""
    isVariety = ""
    isSize = ""
    isPckHse = ""
    
    iArrival = ""
    isDateRcvd = ""
    isOrderNum = ""
    isOne = True
    isNumRecord = 0
    isString = ""
    iCurrArrivalNum = ""
    
    Barcode = Trim$(BCField(2))
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " ORDER BY RECEIVER_ID, ARRIVAL_NUM"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If dsCARGO_TRACKING.RecordCount > 1 Then
        isOne = False
    End If
    isNumRecord = dsCARGO_TRACKING.RecordCount
    
    If OraDatabase.LastServerErr = 0 Then
        If dsCARGO_TRACKING.RecordCount = 0 Then
            ' NO PALLET
            'MESSAGE SAY UNKNOWN PALLET
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XUNKNOWN PALLET)]"
            Exit Sub
        End If
    Else 'ORACLE ERROR
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XORACLE ERROR, TRY IT AGAIN)]"
        Exit Sub
    End If
    
    Do While Not dsCARGO_TRACKING.EOF
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '" & dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value & "'"
        Set dsARRIVAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        If (dsARRIVAL.Fields("SHIP_PREFIX").Value = "CLEMENTINES") Then
            correctvessel = True
            Exit Do
        Else
            correctvessel = False
        End If
        dsCARGO_TRACKING.MoveNext
    Loop
    If Not correctvessel Then
        'Not a clem plt has been scanned
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "NOT A CLEMENTINE \nPALLET!" & ")]"
        Exit Sub
    End If

        
    i = i + 1
    While i < Val(BCField(3))
        dsCARGO_TRACKING.MoveNext
        i = i + 1
    Wend
    
    'GET PARTICULAR PALLET INFORMATION
    
    
    'GET INFORMATION FOR DISPLAYING
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & dsCARGO_TRACKING.Fields("RECEIVER_ID").Value
    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value) & "'"
    gsSqlStmt = gsSqlStmt & " AND (SERVICE_CODE = 6 OR SERVICE_CODE = 11) AND (ACTIVITY_DESCRIPTION is null or ACTIVITY_DESCRIPTION <> 'VOID')"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If dsCARGO_ACTIVITY.RecordCount > 0 Then
        dsCARGO_ACTIVITY.MoveLast
        isOrderNum = Trim$(dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value)
    Else
        isOrderNum = ""
    End If
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(dsCARGO_TRACKING.Fields("RECEIVER_ID").Value))
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME")) Then
        isCustomerName = Left$(Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value), 12)
    Else
        isCustomerName = ""
    End If
    
    'GET ARRIVAL NUM AND ARRIVAL NAME IF FROM SHIP. IF FROM TRUCK, GIVE THE ARRIVAL NUM
    If Not IsNull(dsCARGO_TRACKING.Fields("ARRIVAL_NUM")) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Val(Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value))
        Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If dsVESSEL_PROFILE.RecordCount > 0 Then 'CARGO FROM SHIP
            If Not IsNull(dsVESSEL_PROFILE.Fields("VESSEL_NAME")) Then
                iCurrArrivalNum = Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
                iArrival = iCurrArrivalNum & "-" & Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value)
            Else 'ALMOST NO THIS CASE
                iCurrArrivalNum = ""
                iArrival = ""
            End If
        Else 'FROM TRUCK
            
            iCurrArrivalNum = Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
            iArrival = Trim(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
            
        End If
    Else 'ALMOST NO THIS CASE
        iCurrArrivalNum = ""
        iArrival = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("COMMODITY_CODE")) Then
        gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Val(Trim$(dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value))
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If Not IsNull(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME")) Then
            If (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5605") Then
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 11), 5)
            ElseIf (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5603") Then
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 4) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 18), 4)
            Else
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 14), 5)
            End If
            isCommodityName = dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value & "-" & isCommodityName
        Else
            isCommodityName = dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value
        End If
    Else
        isCommodityName = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("QTY_DAMAGED")) Then
        isDmgd = Trim$(dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value)
    Else
        isDmgd = ""
    End If
            
    If Not IsNull(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION")) Then
        isLocation = Trim$(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value)
    Else
        isLocation = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED")) Then
        isDateRcvd = Format(Trim$(dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value), "MM/DD/YY")
    Else
        isDateRcvd = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("VARIETY")) Then
        isVariety = Trim$(dsCARGO_TRACKING.Fields("VARIETY").Value)
    Else
        isVariety = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("CARGO_SIZE")) Then
        isSize = Trim$(dsCARGO_TRACKING.Fields("CARGO_SIZE").Value)
    Else
        isSize = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("EXPORTER_CODE")) Then
        isPckHse = Trim$(dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value)
    Else
        isPckHse = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("CARGO_STATUS")) Then
        Plt_Status = Trim$(dsCARGO_TRACKING.Fields("CARGO_STATUS").Value)
        If Plt_Status = "R" Then
            Plt_Status = "Regrade"
        ElseIf Plt_Status = "H" Then
            Plt_Status = "Hospital"
        ElseIf Plt_Status = "B" Then
            Plt_Status = "Hosp/Regr"
        End If
    Else
        Plt_Status = "None"
    End If
    
    isString1 = Left(isCustomerName, 10)
    isString2 = Left(isCommodityName, 14)
    isString3 = isLocation
    isString4 = dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
    isString5 = isDmgd
    isString6 = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value
    isString7 = iArrival & "," & isOrderNum 'GET SHIP NAME OR ORDER NUMBER
    'isString7 = Left$(isString7 & " " & isDateRcvd & " ", 20)
    'isString7 = Left$(isString7 & isOrderNum & " ", 32)
    isString8 = Left(isVariety, 10)
    isString9 = isSize
    isString10 = isPckHse
    If (BCField(1) = "LOP") Then
        isString12 = Plt_Status
        isString11 = isString8 & "," & isString9 & "," & isString10 & "," & isString12
    ElseIf (BCField(1) = "RCI") Then 'For relocate, the status of a pallet is not shown
        isString11 = isString8 & "," & isString9 & "," & isString10
    End If
    
    If Val(BCField(3)) = 1 Then
        If isNumRecord = 1 Then
            isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & " ," & isString5 & "  ," & isString6 & "," & isString7 & "," & isString11 & "," & isNumRecord
            'send message to handheld
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & isString & ")]"
            Exit Sub
        Else
            isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & " ," & isString5 & "," & isString6 & "," & isString7 & "," & isString11 & "," & isNumRecord
            'send message to handheld
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",D" & isString & ")]"
            Exit Sub
        End If
        
    Else 'IF MORE THAN ONE
        isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & " ," & isString5 & "," & isString6 & "," & isString7 & "," & isString11 & "," & isNumRecord
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",D" & isString & ")]"
        Exit Sub
    End If
  
End Sub
Private Sub RelocatePalletsProcess()
   Dim i As Integer
   Dim iChar As String
   Dim iNewLocaton As String
   Dim isChangeNewLocation As Boolean
   
   On Error GoTo ErrHandler
    'FIELDS:1.RLC, 2. BARCODE, 3.NEW LOCATION, 4. USER, 5. SUPER, 6. PAGE#
    iChar = ""
    i = 0
    isChangeNewLocation = False
    
    If Trim$(BCField(3)) <> "" And Trim$(BCField(3)) <> "NO" Then
        isChangeNewLocation = True
    End If
    'If Len(Trim$(BCField(2))) = 20 Then
    '    Barcode = Right(Trim$(BCField(2)), 10)
    'Else
    '    Barcode = Trim$(BCField(2))
    'End If
    
    'If Len(Trim$(BCField(2))) > 12 And Len(Trim$(BCField(2))) <> 20 Then
    '    Barcode = Right(Trim$(BCField(2)), 12)
    'End If
    Barcode = Trim$(BCField(2))
    
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " ORDER BY RECEIVER_ID, ARRIVAL_NUM"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    'GET PARTICULAR RECORD FOR PROCESS RECOUP
    i = i + 1
    While i < Val(BCField(6))
        dsCARGO_TRACKING.MoveNext
        i = i + 1
    Wend
    
    gsSqlStmt = "SELECT MAX(ACTIVITY_NUM) MACTIVITY_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & dsCARGO_TRACKING.Fields("RECEIVER_ID").Value
    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value) & "'"
    Set dsCARGO_ACTIVITY_A = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(4)) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.BeginTrans
    Else
        OraDatabase.Close
        Call Initialize_DB
        ' not expected
        'OraSession.Rollback
        Exit Sub
    End If
    
     'Begin a transaction
         
    If isChangeNewLocation Then 'CHANGE TO NEW LOCATION
       dsCARGO_ACTIVITY_ALL.AddNew
       dsCARGO_ACTIVITY_ALL.Fields("PALLET_ID").Value = Barcode
       dsCARGO_ACTIVITY_ALL.Fields("ARRIVAL_NUM").Value = Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
       If Not IsNull(dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM")) Then
           dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM").Value + 1
       Else
           dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = 1
       End If
       dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 2
       dsCARGO_ACTIVITY_ALL.Fields("QTY_CHANGE").Value = 0
       dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
       dsCARGO_ACTIVITY_ALL.Fields("CUSTOMER_ID").Value = dsCARGO_TRACKING.Fields("RECEIVER_ID").Value
       If Not IsNull(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION")) Then
           dsCARGO_ACTIVITY_ALL.Fields("FROM_LOCATION").Value = dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value
       End If
       dsCARGO_ACTIVITY_ALL.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
       dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value ' SET EQUAL TO QTY IN HOUSE AT THAT POINT
       dsCARGO_ACTIVITY_ALL.Update
    End If
    
    If OraDatabase.LastServerErr = 0 And isChangeNewLocation Then
         If isChangeNewLocation Then
            dsCARGO_TRACKING.Edit
            dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value = Trim(BCField(3))
            dsCARGO_TRACKING.Update
         End If
    End If
     
     If OraDatabase.LastServerErr = 0 Then
         OraSession.CommitTrans
         'PASS LOG INFORMATION
         Call SendToOutLog(Reader_Addr, "Y", EZC_Record, BCField(6), BCField(7), dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value, BCField(2), dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value, dsCARGO_TRACKING.Fields("RECEIVER_ID").Value)
         PauseForResponse
         tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
         Exit Sub
     Else
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XORACLE ERROR, TRY IT AGAIN)]"
        Exit Sub
     End If
   
    Exit Sub
    
    
ErrHandler:

    If Err.Number <> 0 Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        
        Exit Sub
    End If
    
End Sub
Private Sub VerifyOrder()
    'FIELDS: 1. ORV, 2. CUST, 3. ORD#
    Dim iTotalReturnOnThatPallet As Long
    Dim iTotalShipOnThatPallet As Long
    Dim iDuplicateCount As Integer
    Dim iTransferFrom As Boolean
    Dim iTransferToCust As String
    Dim iTransferFromCust As String
    Dim iSTransfeOrder As Boolean
    
    
   ' On Error GoTo DbError
    
    iTotalReturnOnThatPallet = 0
    iTotalShipOnThatPallet = 0
    iDuplicateCount = 0
    iTransferFrom = True
    iTransferFromCust = ""
    iTransferToCust = ""
    iSTransfeOrder = False
    
    gsSqlStmt = "SELECT DISTINCT PALLET_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(2)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
    gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    
    
    'GET SUM QTY CHANGE
    gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(2)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
    gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
    Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    'TRACKING RETURN PALLET FOR EXACTALY PALLET COUNT
    gsSqlStmt = "SELECT DISTINCT PALLET_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(2)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
    gsSqlStmt = gsSqlStmt & " AND (SERVICE_CODE = 7 OR SERVICE_CODE = 13) AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
    Set dsCARGO_RETURN_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If dsCARGO_RETURN_ACTIVITY.RecordCount > 0 Then
        While Not dsCARGO_RETURN_ACTIVITY.EOF
            gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUM_CHANGE_THIS_PALLET FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(dsCARGO_RETURN_ACTIVITY.Fields("PALLET_ID").Value) & "'"
            gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(2)))
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
            gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_RETURN_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
            gsSqlStmt = gsSqlStmt & " AND (SERVICE_CODE = 7 OR SERVICE_CODE = 13) AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
            Set dsCARGO_RETURN_COUNT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            
            gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUM_CHANGE_THIS_PALLET FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(dsCARGO_RETURN_ACTIVITY.Fields("PALLET_ID").Value) & "'"
            gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(2)))
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
            gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_RETURN_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
            gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6 AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
            Set dsCARGO_SHIP_COUNT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            
            If Trim$(-dsCARGO_RETURN_COUNT.Fields("SUM_CHANGE_THIS_PALLET").Value) = Trim$(dsCARGO_SHIP_COUNT.Fields("SUM_CHANGE_THIS_PALLET").Value) Then
                iDuplicateCount = iDuplicateCount + 1
            End If
            dsCARGO_RETURN_ACTIVITY.MoveNext
        Wend
    End If
    
    'CHECK THE TRANSFER ORDER
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(2)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
    gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 11 AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
    Set dsCARGO_TRANSFER_CHECK = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If dsCARGO_TRANSFER_CHECK.RecordCount > 0 Then 'IF IT IS TRANSFER ORDER
        iSTransfeOrder = True
        If Trim$(dsCARGO_TRANSFER_CHECK.Fields("ARRIVAL_NUM").Value) <> Trim$(dsCARGO_TRANSFER_CHECK.Fields("ORDER_NUM").Value) Then
            iTransferFrom = False
            'TRANSFER TO, GET THE TRANSFER TO CUSTOMER ID
            gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE CUSTOMER_ID <> " & Val(Trim$(BCField(2)))
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
            gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(BCField(3)) & "'"
            gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 11 AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
            Set dsCARGO_TRANSFER_TO_CUSTOMER = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            iTransferToCust = Trim$(dsCARGO_TRANSFER_TO_CUSTOMER.Fields("CUSTOMER_ID").Value)
        Else
            'TRANSFER FROM, GET TRANSFER FROM CUST, ASSUME THE ORDER NUM IS DIFFERENT UNDER DIFFERENT CUSTOMER
            gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE CUSTOMER_ID <> " & Val(Trim$(BCField(2)))
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
            gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM <> '" & Trim$(BCField(3)) & "'"
            gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 11 AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
            Set dsCARGO_TRANSFER_FROM_CUSTOMER = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            iTransferFromCust = Trim$(dsCARGO_TRANSFER_FROM_CUSTOMER.Fields("CUSTOMER_ID").Value)
        End If
    End If
    
    If OraDatabase.LastServerErr = 0 Then
        If dsCARGO_ACTIVITY.RecordCount = 0 Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XDOES NOT EXIST, TRY AGAIN.)]"
            Exit Sub
        Else
            PauseForResponse
            If iSTransfeOrder Then
                If iTransferFrom Then
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & Trim$(dsCARGO_ACTIVITY.RecordCount - iDuplicateCount) & "Plt Frm " & iTransferFromCust & ")]"
                    Exit Sub
                Else
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & Trim$(dsCARGO_ACTIVITY.RecordCount - iDuplicateCount) & "Plt To " & iTransferToCust & ")]"
                    Exit Sub
                End If
                
            Else
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & Trim(dsCARGO_ACTIVITY.RecordCount - iDuplicateCount) & " Plts)]"
                Exit Sub
            End If
            
        End If
    Else
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
        Exit Sub
    End If
       
    Exit Sub
    
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
 '   PauseForResponse
 '   tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
    Exit Sub
       
End Sub
Private Sub TruckReceivingBegin()
    'FIELDS: 1.TRB,2.CUSTOMER,3.ORDER#
    
    gsSqlStmt = "SELECT DISTINCT PALLET_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(2)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
    gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    'GET SUM QTY CHANGE
    
    gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(2)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
    gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
    Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        If dsCARGO_ACTIVITY.RecordCount = 0 Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YNew Order)]"
            Exit Sub
        Else
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & dsCARGO_ACTIVITY.RecordCount & " Plts)]"
            Exit Sub
        End If
    Else
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
        Exit Sub
    End If
    
End Sub
Private Sub truckReceiving()
    'Fields  1. TR, 2. User, 3. Customer, 4. Order #, 5. Barcode, 6. Supervisor

    Dim itString As String
    Dim itString1 As String
    Dim itString2 As String
    Dim itString3 As String
    Dim itString4 As String
    Dim itString5 As String
    Dim itString6 As String
    Dim itString7 As String
    Dim itString8 As String
    Dim itString9 As String
    Dim itCustomerName As String
    Dim itCommodityName As String
    Dim itDmgd As String
    Dim itLocation As String
    Dim itOrdNum As String
    Dim count As Integer
    Dim CLM As String
    Dim msg As String
    
    Dim arrTranstext() As String
    Dim bc As String
    Dim sqlStmt As String
    Dim retVal As String
    Dim qty_rcvd As String
    Dim qty_dmgd As String
    Dim plt_loc As String
    Dim plt_id As String
    Dim is_hospital As String
    Dim arr_num As String
    Dim cust As String
    Dim comm As String
    Dim variety As String
    Dim size As String
    Dim pck_hse As String
    Dim Source As String
    Dim pow As String
    Dim FFM As String
    Dim ds As Object

    
    'On Error GoTo DbError
    
    itString = ""
    itString1 = ""
    itString2 = ""
    itString3 = ""
    itString4 = ""
    itString5 = ""
    itString6 = ""
    itString7 = ""
    itString8 = ""
    itString9 = ""
    itCustomerName = ""
    itCommodityName = ""
    itDmgd = ""
    itOrdNum = ""
    itLocation = ""
    
    'If Len(Trim$(BCField(5))) = 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 10)
    'Else
    '    Barcode = Trim$(BCField(5))
    'End If
   '
    'If Len(Trim$(BCField(5))) > 12 And Len(Trim$(BCField(5))) <> 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 12)
    'End If
    
    Barcode = Trim$(BCField(5))
    'WE DO NOT CONCERN THE DUPLICATE PALLETS, BECAUSE THERE IS NO SAME PALLETS ARE ON THE SMAE ORDER
    'IF THE PALLET WAS IN THE SYSTEM, JUST EDIT
    
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND CONTAINER_ID = '" & Trim$(BCField(4)) & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID =" & Val(Trim$(BCField(3)))
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        If dsCARGO_TRACKING.RecordCount > 0 Then 'IF THE PALLET WAS IN THE SYSTEM, THEN EDIT
            'Good go down to the bottom and get info
        Else 'NO PALLET IS IN THE SYSTEM, THEN CREATE THE PALLET
            count = Len(Barcode)
            CLM = Left(Barcode, 9)
            If (count = 28) Then 'See if information can be taken from barcode.
                If Not CLM = "611124520" Then 'barcode does not match CLM format
                    msg = "UNKNOWN PALLET. \nMUST CREATE"
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
                    Exit Sub
                End If
                plt_id = Trim$(Right(Left(Barcode, 23), 6))
                qty_rcvd = Val(Right(Barcode, 3))
                FFM = Left(Right(Barcode, 19), 4)
                pck_hse = Left(Right(Barcode, 15), 5)
                sqlStmt = "SELECT * FROM CLM_SIZEMAP A WHERE A.FFM = '" & FFM & "'"
                Set ds = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
                If (ds.RecordCount = 0) Then 'barcode does not match CLM format
                    msg = "UNKNOWN PALLET. \nMUST CREATE."
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
                    Exit Sub
                End If
                size = Val(ds.Fields("DC").Value)
                retVal = qty_rcvd & "," & pck_hse & "," & size
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",W" & retVal & ")]"
                Exit Sub
            ElseIf (count = 30) Then 'see if info can be taken from barcode
                If Not CLM = "611124520" Then 'barcode does not match CLM format
                    msg = "UNKNOWN PALLET. \nMUST CREATE."
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
                    Exit Sub
                End If
'                plt_id = Trim$(Right(Left(Barcode, 23), 6))
'                gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID LIKE '%" & plt_id & "%'"
'                gsSqlStmt = gsSqlStmt & " AND CONTAINER_ID = '" & Trim$(BCField(4)) & "'"
'                gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID =" & Val(Trim$(BCField(3)))
'                Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'                If (dsCARGO_TRACKING.RecordCount = 1) Then
'                    'Barcode could be picked up from pallet id in cargo_tracking
'                Else
                    qty_rcvd = Val(Right(Left(Barcode, 27), 4))
                    size = Val(Right(Barcode, 3))
                    pck_hse = Right(Left(Barcode, 17), 4)
                    retVal = qty_rcvd & "," & pck_hse & "," & size
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",W" & retVal & ")]"
                    Exit Sub
'                End If
            ElseIf (count < 28) Then
                msg = "UNKNOWN PALLET. \nMUST CREATE."
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
                Exit Sub
            End If
        End If
        'GET ORDER NUM
        itOrdNum = Trim$(BCField(4))
            
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        'GET CUSTOMER
        If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME")) Then
            itCustomerName = Left$(Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value), 12)
        Else
            itCustomerName = ""
        End If
            
        'GET COMMODITY
        sqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value & ""
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
        If (IsNull(dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value)) Then
            itCommodityName = "None"
        Else
            If (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5605") Then
                itCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 11), 5)
            ElseIf (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5603") Then
                itCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 4) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 18), 4)
            Else
                itCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 14), 5)
            End If
            itCommodityName = dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value & "-" & itCommodityName
            itCommodityName = Left$(itCommodityName & "          ", 12)
        End If
            
        'GET QTY DMAGD
        If Not IsNull(dsCARGO_TRACKING.Fields("QTY_DAMAGED")) Then
            itDmgd = Trim$(dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value)
        Else
            itDmgd = ""
        End If
                                
        'GET LOCATION
        If Not IsNull(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION")) Then
            itLocation = Trim$(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value)
        Else
            itLocation = ""
        End If
            
        'Get variety
        If Not IsNull(dsCARGO_TRACKING.Fields("VARIETY")) Then
            variety = Trim$(dsCARGO_TRACKING.Fields("VARIETY").Value)
        Else
            variety = ""
        End If
            
        'Get Size
        If Not IsNull(dsCARGO_TRACKING.Fields("CARGO_SIZE")) Then
            size = Trim$(dsCARGO_TRACKING.Fields("CARGO_SIZE").Value)
        Else
            size = ""
        End If
            
        'Get Packing House
        If Not IsNull(dsCARGO_TRACKING.Fields("EXPORTER_CODE")) Then
            pck_hse = Trim$(dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value)
        Else
            pck_hse = ""
        End If
                
        itString1 = Trim$(itOrdNum)
        itString1 = Left$(itString1 & "          ", 10)
        itString2 = Trim$(itCustomerName)
        itString2 = Left$(itString2 & "          ", 10)
        itString3 = Trim$(itCommodityName)
        itString3 = Left$(itString3 & "          ", 14)
        itString4 = Trim$(dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value)
        itString4 = Left$(itString4 & "   ", 3)
        itString5 = itDmgd
        itString5 = Left$(itString5 & "   ", 3)
        itString6 = Trim$(itLocation)
        itString6 = Left$(itString6 & "          ", 10)
        itString7 = Trim$(variety)
        itString8 = Trim$(size)
        itString9 = Trim$(pck_hse)
            
        'X,$<Cust#>,$<Comm>,$<QtyRcvd>,$<QtyDmgd>,$<Loc>,$<Variety>,$<Size>,$<PkgHse>,$<Order#>,$<L3Val>
            
        itString = itString2 & "," & itString3 & "," & itString4 & "," & itString5 & "," & itString6 & "," & itString7 & "," & itString8 & "," & itString9 & "," & itString1 & "," & BCField(3)
        gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(3)))
        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & itOrdNum & "'"
        gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
        Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
'        If (dsCARGO_ACTIVITY.RecordCount > 0) Then
'            msg = "PALLET SCANNED IN ON\nORDER# " & itOrdNum & ". \nPOSSIBLE DUPLICATE \nPALLET. DO YOU WISH \nTO RECEIVE?"
'            PauseForResponse
'            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",D" & msg & "," & itString & ")]"
'            Exit Sub
'        End If
        If dsCARGO_TRACKING.RecordCount = 1 Then 'IF COUNT = 1
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & itString & ")]"
            Exit Sub
        End If
    Else 'ORACLE ERROR
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Just Try Again)]"
        Exit Sub
    End If

DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
'    PauseForResponse
'    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Just Try Again)]"
    Exit Sub
End Sub


Private Sub truckReceivedException()
  'FIELDS:  1.TRX, 2.USER,3.CUSTOMER COMING FROM, 4.ORDER #, 5.BARCODE,6.QTY RCVD,7.QTY DMGD,8.ORIGINAL CUST,9.COMMODITY,10.LOCATION,11.Size,12.Packing House,13.SUPERVISOR,14.Qty Expected,15.Status
    
    'On Error GoTo DbError
    
    'Oppenheimer Fix
    'If Len(Trim$(BCField(5))) = 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 10)
    'Else
    '    Barcode = Trim$(BCField(5))
    'End If
    
    'If Len(Trim$(BCField(5))) > 12 And Len(Trim$(BCField(5))) <> 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 12)
    'End If
    Dim dsCLM As Object
    Dim Mark_Flag As String
    Dim dsINSP_PLT As Object
    Dim dsINSP_LOG As Object
    Dim Action_Type As String
    Dim Trans_id As Integer
    Barcode = Trim$(BCField(5))
    
    'CHECK IF CREATE DUPLICATE PALLETS , ASSUME THE ORDER NUM IS NOT EQUAL TO LR NUM
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(BCField(8))
    gsSqlStmt = gsSqlStmt & " AND CONTAINER_ID = '" & Trim$(BCField(4)) & "'"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If dsCARGO_TRACKING.RecordCount > 1 Then
            'MESSAGE, CAN NOT CREATE THIS PALLET BECAUSE THIS CUSTOMER HAS MORE THAN ONE PALLETS FROM THIS THIP
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XCan not create this pallet because this customer already had pallet coame from this order)]"
            Exit Sub
        End If
    Else
        Call Initialize_DB
        'OraSession.Rollback
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Just Try Again)]"
        Exit Sub
    End If
    
    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(2)) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        gsSqlStmt = "SELECT DC, WEIGHT FROM CLM_SIZEMAP WHERE DC = '" & Val(BCField(11)) & "'"
        Set dsCLM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        'Begin a transaction
        OraSession.BeginTrans
        
        dsCARGO_ACTIVITY_ALL.AddNew
        dsCARGO_ACTIVITY_ALL.Fields("PALLET_ID").Value = Trim$(Barcode)
        dsCARGO_ACTIVITY_ALL.Fields("ARRIVAL_NUM").Value = Trim$(BCField(3))
        dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = 1
        dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 8
        dsCARGO_ACTIVITY_ALL.Fields("QTY_CHANGE").Value = Val(BCField(6))
        dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
        dsCARGO_ACTIVITY_ALL.Fields("ORDER_NUM").Value = Trim$(BCField(4))
        dsCARGO_ACTIVITY_ALL.Fields("CUSTOMER_ID").Value = BCField(3)
        dsCARGO_ACTIVITY_ALL.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
        dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = Val(BCField(6))
        dsCARGO_ACTIVITY_ALL.Update
        
        dsCARGO_TRACKING_ALL.AddNew
        dsCARGO_TRACKING_ALL.Fields("PALLET_ID").Value = Trim$(Barcode)
        dsCARGO_TRACKING_ALL.Fields("ARRIVAL_NUM").Value = Val(BCField(3))
        dsCARGO_TRACKING_ALL.Fields("QTY_RECEIVED").Value = Val(BCField(6))
        dsCARGO_TRACKING_ALL.Fields("QTY_IN_HOUSE").Value = Val(BCField(6))
        dsCARGO_TRACKING_ALL.Fields("COMMODITY_CODE").Value = Val(BCField(9))
        dsCARGO_TRACKING_ALL.Fields("DATE_RECEIVED").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
        dsCARGO_TRACKING_ALL.Fields("RECEIVER_ID").Value = Val(BCField(3))
        dsCARGO_TRACKING_ALL.Fields("QTY_DAMAGED").Value = Val(BCField(7))
        dsCARGO_TRACKING_ALL.Fields("RECEIVING_TYPE").Value = "T"
        dsCARGO_TRACKING_ALL.Fields("EXPORTER_CODE").Value = Val(BCField(12))
        dsCARGO_TRACKING_ALL.Fields("CARGO_SIZE").Value = Val(BCField(11))
        If Trim$(BCField(10)) <> "" Then
            dsCARGO_TRACKING_ALL.Fields("WAREHOUSE_LOCATION").Value = Right$(Trim$(BCField(10)), 12)
        End If
        dsCARGO_TRACKING_ALL.Fields("BATCH_ID").Value = Val(BCField(14))
        dsCARGO_TRACKING_ALL.Fields("FROM_SHIPPING_LINE").Value = Val(BCField(3))
        dsCARGO_TRACKING_ALL.Fields("SHIPPING_LINE").Value = Val(BCField(3))
        dsCARGO_TRACKING_ALL.Fields("CARGO_DESCRIPTION").Value = Val(BCField(12)) & "-" & Val(BCField(11)) & "-" & Val(BCField(14))
'        dsCARGO_TRACKING_ALL.Fields("WEIGHT").Value = dsCLM.Fields("WEIGHT").Value
        dsCARGO_TRACKING_ALL.Fields("WEIGHT_UNIT").Value = "KG"
        dsCARGO_TRACKING_ALL.Fields("BOL").Value = "0"
        dsCARGO_TRACKING_ALL.Fields("CONTAINER_ID").Value = Trim$(BCField(4))
        dsCARGO_TRACKING_ALL.Fields("MANIFESTED").Value = "N"
        dsCARGO_TRACKING_ALL.Fields("MARK").Value = "AT POW"
        If (BCField(15) <> "") Then
            dsCARGO_TRACKING_ALL.Fields("CARGO_STATUS").Value = BCField(15)
            
            gsSqlStmt = "SELECT * FROM DC_INSPECTION_LOG ORDER BY TRANSACTION_ID"
            Set dsINSP_LOG = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            
            gsSqlStmt = "SELECT * FROM DC_INSPECTED_PALLET"
            Set dsINSP_PLT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            
            If (dsINSP_LOG.RecordCount > 0) Then
                dsINSP_LOG.MoveLast
                Trans_id = dsINSP_LOG.Fields("TRANSACTION_ID").Value + 1
            Else
                Trans_id = 1
            End If
            
            If (BCField(15) = "") Then
                Action_Type = "NULL"
            ElseIf (BCField(15) = "R") Then
                Action_Type = "REGRADE"
            ElseIf (BCField(15) = "H") Then
                Action_Type = "HOSPITAL"
            ElseIf (BCField(15) = "B") Then
                Action_Type = "BOTH"
            End If
            
            dsINSP_LOG.AddNew
            dsINSP_LOG.Fields("TRANSACTION_ID").Value = Trans_id
            dsINSP_LOG.Fields("INSPECTOR_ID").Value = BCField(2)
            dsINSP_LOG.Fields("INSPECTION_DATETIME").Value = Format(Now, "MM/DD/YYYY HH:MM:SS AMPM")
            dsINSP_LOG.Fields("ACTION_TYPE").Value = Action_Type
            dsINSP_LOG.Fields("NUM_PALLETS").Value = "1"
            dsINSP_LOG.Fields("ARRIVAL_NUM").Value = Val(BCField(3))
            dsINSP_LOG.Update
    
            dsINSP_PLT.AddNew
            dsINSP_PLT.Fields("PALLET_ID").Value = Trim$(Barcode)
            dsINSP_PLT.Fields("TRANSACTION_ID").Value = Trans_id
            dsINSP_PLT.Fields("PREVIOUS_STATUS").Value = ""
            dsINSP_PLT.Fields("NEW_STATUS").Value = Trim$(BCField(15))
            dsINSP_PLT.Update
        End If
        dsCARGO_TRACKING_ALL.Update
        
        Mark_Flag = "AT POW"
        Call InsertCTE(Val(BCField(3)), Val(BCField(3)), Trim$(Barcode), Mark_Flag)

    End If
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        'PASS LOG INFORMATION
        'Call LOG_ACTIVITY(BCField(1), BCField(2), Barcode, BCField(11), BCField(4), BCField(3))
        
        gsSqlStmt = "SELECT DISTINCT PALLET_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
        gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
        Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        'GET SUM QTY CHANGE
        
        gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
        gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
        Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        If OraDatabase.LastServerErr = 0 Then
            If dsCARGO_ACTIVITY.RecordCount = 0 Then
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YNew Order)]"
                Exit Sub
            Else
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & dsCARGO_ACTIVITY.RecordCount & " Plts)]"
            End If
        Else
            OraDatabase.Close
            Call Initialize_DB
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
            Exit Sub
        End If
        
    Else
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
        Exit Sub
    End If

    Exit Sub
    
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
'    PauseForResponse
 '   tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
    
End Sub
Private Sub truckReceivedFinal()
    'fields:  1.TRF, 2.User, 3.CUST, 4.Order #,5.barcode,6.supervisor
    'NOT USEING IN NEW VERSION
    
    Dim iCameFromShip As Boolean
    
    Dim iNoActivity As Boolean
    
    'On Error GoTo DbError

    'Oppenheimer Fix
    'If Len(Trim$(BCField(5))) = 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 10)
    'Else
    '    Barcode = Trim$(BCField(5))
    'End If

    'If Len(Trim$(BCField(5))) > 12 And Len(Trim$(BCField(5))) <> 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 12)
    'End If
    
    Barcode = Trim$(BCField(5))
    
    iCameFromShip = False
    iNoActivity = False
    

    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(2)) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

    gsSqlStmt = "SELECT MAX(ACTIVITY_NUM) MACTIVITY_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    Set dsCARGO_ACTIVITY_A = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    gsSqlStmt = "SELECT * FROM VOYAGE_CARGO WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    Set dsVOYAGE_CARGO = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And dsVOYAGE_CARGO.RecordCount > 0 Then
        If dsVOYAGE_CARGO.Fields("LR_NUM").Value <> -1 Then
            iCameFromShip = True
        End If
    End If
        
    If OraDatabase.LastServerErr = 0 Then
        
        OraSession.BeginTrans
        
        If iCameFromShip Then
            'CHECK ANY SHIP OUT AGAINST.
            gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
            gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION = 'RETURN')"
            Set dsCARGO_ACTIVITY_B = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            
            If dsCARGO_ACTIVITY_B.RecordCount = 0 Then
                'ADD ACTIVITY
                dsCARGO_ACTIVITY_ALL.AddNew
                dsCARGO_ACTIVITY_ALL.Fields("PALLET_ID").Value = Trim$(Barcode)
                If Not IsNull(dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM")) Then
                    dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM").Value + 1
                Else
                    dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = 1
                End If
                dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 8
                dsCARGO_ACTIVITY_ALL.Fields("QTY_CHANGE").Value = dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
                dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
                dsCARGO_ACTIVITY_ALL.Fields("ORDER_NUM").Value = BCField(4)
                dsCARGO_ACTIVITY_ALL.Fields("CUSTOMER_ID").Value = BCField(3)
                dsCARGO_ACTIVITY_ALL.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
                dsCARGO_ACTIVITY_ALL.Update
            
                'CHANGE SHIP ID
                dsVOYAGE_CARGO.Edit
                dsVOYAGE_CARGO.Fields("LR_NUM").Value = -1
                dsVOYAGE_CARGO.Update
                
                'UPDATE CT
                dsCARGO_TRACKING.Edit
                If IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED")) Then
                    dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
                End If
                dsCARGO_TRACKING.Update
                
            Else
                ' SHIP OUT AGAINST SO CAN NOT SIMPLY ADD TOGETHER, CALL ADMINISTRATOR
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YCall Administrator!)]"
                OraSession.Rollback
                Exit Sub
            End If
            
        Else 'CAME FROM TRUCK
            
            'Check on same order or not.
            gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
            gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(3)))
            gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 8 AND ACTIVITY_DESCRIPTION IS NULL"
            
            Set dsCARGO_ACTIVITY_C = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr <> 0 Then
                OraDatabase.Close
                Call Initialize_DB
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YError Occurred!)]"
                Exit Sub
            Else
                If dsCARGO_ACTIVITY_C.RecordCount > 0 Then
                'SAME ORDER DO NOTHING
                Else
                    'ADD ACTIVITY
                    dsCARGO_ACTIVITY_ALL.AddNew
                    dsCARGO_ACTIVITY_ALL.Fields("PALLET_ID").Value = Trim$(Barcode)
                    If Not IsNull(dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM")) Then
                        dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM").Value + 1
                    Else
                        dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = 1
                    End If
                    dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 8
                    dsCARGO_ACTIVITY_ALL.Fields("QTY_CHANGE").Value = dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
                    dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
                    dsCARGO_ACTIVITY_ALL.Fields("ORDER_NUM").Value = BCField(4)
                    dsCARGO_ACTIVITY_ALL.Fields("CUSTOMER_ID").Value = BCField(3)
                    dsCARGO_ACTIVITY_ALL.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
                    dsCARGO_ACTIVITY_ALL.Update
                
                    'UPDATE CT
                    dsCARGO_TRACKING.Edit
                    If IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED")) Then
                        dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
                    End If
                    dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = Val(Trim$(dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value)) + Val(Trim$(dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value))
                    'dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value = Val(Trim$(dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value)) + Val(Trim$(dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value))
                    dsCARGO_TRACKING.Update
                End If
            End If
        End If
    End If
    
'    If Val(BCField(3)) = 407 Then
'        'CHECK ACCESS DB, IF NOT THERE, ADD IN.  MODIFIED ON 5/25/2000
'        gsSqlStmt = "SELECT * FROM xpxTranPallets WHERE PalletNo = '" & Trim$(Barcode) & "'"
'        Set rsxpxTranPallets = accessDb.OpenRecordset(gsSqlStmt, dbOpenDynaset)
'        If rsxpxTranPallets.RecordCount = 0 Then
'            rsxpxTranPallets.AddNew
'            rsxpxTranPallets.Fields("OrdNum").Value = "POW"
'            rsxpxTranPallets.Fields("PalletNo").Value = Trim$(Barcode)
'            rsxpxTranPallets.Fields("DateTimeStamp").Value = Format$(Now, "mm/dd/yyyy hh:mm:ss AM/PM")
'            rsxpxTranPallets.Update
'        End If
'        rsxpxTranPallets.Close
'    End If
'
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        'PASS LOG INFORMATION
        'Call LOG_ACTIVITY(BCField(1), BCField(2), Barcode, BCField(6), BCField(4), BCField(3))
    Else
        'OraSession.Rollback
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YError Occurred!)]"
        Exit Sub
    End If
    
    gsSqlStmt = "SELECT COUNT(Distinct(PALLET_ID)) CLotNum,SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
    gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 8 AND ACTIVITY_DESCRIPTION IS NULL"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YOracle Error Occurred! Try Again.)]"
        Exit Sub
    End If
    
    If dsCARGO_ACTIVITY.Fields("CLotNum").Value > 0 Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY.Fields("SUMQty").Value) & " In " & Trim$(dsCARGO_ACTIVITY.Fields("CLotNum").Value) & " Plts)]"
        Exit Sub
    Else
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YNew Order)]"
        Exit Sub
    End If
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0

End Sub

Private Sub truckReceivedChangeFields()
    'fields:  1.TRC, 2.User, 3.CUST, 4.Order #,5.barcode,6.QTY RCVD,7.QTY DMGD,8.ORIGINAL CUST-CHANGE TO,9.COMMODITY,10.LOCATION,11.supervisor,12.variety,13.Size,14.Pck Hse, 15.Status
    Dim iCameFromShip As Boolean
    Dim iQtyDiff As Long
    Dim Mark_Flag As String
    Dim Prev_Status As String
    Dim Trans_id As Integer
    Dim Action_Type As String
    Dim dsINSP_PLT As Object
    Dim dsINSP_LOG As Object
    
    'On Error GoTo DbError

    'Oppenheimer Fix
    'If Len(Trim$(BCField(5))) = 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 10)
    'Else
    '    Barcode = Trim$(BCField(5))
    'End If

    'If Len(Trim$(BCField(5))) > 12 And Len(Trim$(BCField(5))) <> 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 12)
    'End If
    
    Barcode = Trim$(BCField(5))

    iCameFromShip = False
    iQtyDiff = 0
    
    'IF CHANGE CUSTOMER ID, THEN CHACH IF DUPLICATE
'    If Trim$(BCField(8)) <> "" Then
'        gsSqlStmt = "SELECT * FROM CARGO_TRACKING_NEW WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
'        gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(BCField(8))
'        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = " & Trim$(BCField(4))
'        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'        If OraDatabase.LastServerErr = 0 Then
'            If dsCARGO_TRACKING.RecordCount >= 1 Then
'                'MESSAGE, CAN NOT CREATE THIS PALLET BECAUSE THIS CUSTOMER HAS MORE THAN ONE PALLETS FROM THIS THIP
'                PauseForResponse
'                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XCan not add, See Admin)]"
'                Exit Sub
'            End If
'        Else
'            Call Initialize_DB
'            'OraSession.Rollback
'            PauseForResponse
'            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Just Try Again)]"
'            Exit Sub
'        End If
'
'    End If
'
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(Trim$(BCField(3)))
    gsSqlStmt = gsSqlStmt & " AND CONTAINER_ID = '" & Trim$(BCField(4)) & "'"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

    If Trim$(BCField(6)) <> "" Then
        iQtyDiff = Val(BCField(6)) - dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
        If dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value < dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value - Val(BCField(6)) Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X   CAN NOT CHANGE    QTY RCVD.)]"
            'CAN NOT CHANGE BECAUSE OF QTY IN HOUSE WILL BW BELOW ZERO
            'MESSAGE, NO CHANGE ALLOWED
            Exit Sub
        End If
    End If
    
    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(2)) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    'GET READY FOR UPDATE OF ACTIVITY TABLE
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(3)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    'GET THE ACTIVITY NUM
    gsSqlStmt = "SELECT MAX(ACTIVITY_NUM) MACTIVITY_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(BCField(3))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
    Set dsCARGO_ACTIVITY_A = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
    
        OraSession.BeginTrans
                
        'UPDATE CT
        dsCARGO_TRACKING.Edit
        If IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED")) Then
            dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
        End If
        
        If Trim$(BCField(8)) <> "" Then
            dsCARGO_TRACKING.Fields("RECEIVER_ID").Value = Val(BCField(8))
            'dsCARGO_TRACKING.Fields("RECEIVER_ID").Value = Val(BCField(8))
        End If
        
        If Trim$(BCField(6)) <> "" Then
            If dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = 0 Then
                dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = iQtyDiff
            Else 'QTY IN HOUSE IS NOT NULL OR 0
                dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = Val(BCField(6))
            End If
            dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value = Val(BCField(6))
        End If
        
        If Trim$(BCField(7)) <> "" Then
            dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value = Val(BCField(7))
        Else
            dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value = "0"
        End If
        
        If Trim$(BCField(9)) <> "" Then
            dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value = Val(BCField(9))
        End If
        
        If Trim$(BCField(10)) <> "" Then
            dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value = Right$(Trim$(BCField(10)), 12)
        End If
        
        If Trim$(BCField(12)) <> "" Then
            dsCARGO_TRACKING.Fields("VARIETY").Value = Trim$(BCField(12))
        End If
        
        If Trim$(BCField(13)) <> "" Then
            dsCARGO_TRACKING.Fields("CARGO_SIZE").Value = Val(BCField(13))
        End If
        
        If Trim$(BCField(14)) <> "" Then
            dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value = Val(BCField(14))
        End If
        dsCARGO_TRACKING.Fields("RECEIVING_TYPE").Value = "T"
        
        If (dsCARGO_TRACKING.Fields("MARK").Value = "") Then
            dsCARGO_TRACKING.Fields("MARK").Value = "AT POW"
        ElseIf (dsCARGO_TRACKING.Fields("MARK").Value = "MANIFESTED") Then
            dsCARGO_TRACKING.Fields("MARK").Value = "AT POW"
        End If
        
        If (dsCARGO_TRACKING.Fields("CONTAINER_ID").Value = "0") Then
            dsCARGO_TRACKING.Fields("CONTAINER_ID").Value = BCField(4)
        End If
        
        'All the Pallet status stuff
        If Not IsNull(dsCARGO_TRACKING.Fields("CARGO_STATUS")) Then
            Prev_Status = dsCARGO_TRACKING.Fields("CARGO_STATUS").Value
        Else
            Prev_Status = ""
        End If

        If (Prev_Status <> BCField(15)) Then
            Select Case Prev_Status
                Case "" 'If the previous status is null and the new status is also not null, it can only mean the
                    dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "H"
                    Action_Type = "HOSPITAL"
                Case "R"
                    If (BCField(15) = "") Then
                        Action_Type = "NONE"
                    ElseIf (BCField(15) = "H") Then
                        dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "B"
                        Action_Type = "BOTH"
                    End If
                Case "H"
                    dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = ""
                    Action_Type = "NULL"
                Case "B"
                    If (BCField(15) = "") Then
                        dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "R"
                        Action_Type = "NULL"
                    ElseIf (BCField(15) = "H") Then
                        Action_Type = "NONE"
                    End If
            End Select
            If Not (Action_Type = "NONE") Then
                gsSqlStmt = "SELECT * FROM DC_INSPECTION_LOG ORDER BY TRANSACTION_ID"
                Set dsINSP_LOG = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                
                gsSqlStmt = "SELECT * FROM DC_INSPECTED_PALLET"
                Set dsINSP_PLT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                
                If (dsINSP_LOG.RecordCount > 0) Then
                    dsINSP_LOG.MoveLast
                    Trans_id = dsINSP_LOG.Fields("TRANSACTION_ID").Value + 1
                Else
                    Trans_id = 1
                End If
                
                dsINSP_LOG.AddNew
                dsINSP_LOG.Fields("TRANSACTION_ID").Value = Trans_id
                dsINSP_LOG.Fields("INSPECTOR_ID").Value = BCField(2)
                dsINSP_LOG.Fields("INSPECTION_DATETIME").Value = Format(Now, "MM/DD/YYYY HH:MM:SS AMPM")
                dsINSP_LOG.Fields("ACTION_TYPE").Value = Action_Type
                dsINSP_LOG.Fields("NUM_PALLETS").Value = "1"
                dsINSP_LOG.Fields("ARRIVAL_NUM").Value = Val(BCField(3))
                dsINSP_LOG.Update
        
                dsINSP_PLT.AddNew
                dsINSP_PLT.Fields("PALLET_ID").Value = Trim$(Barcode)
                dsINSP_PLT.Fields("TRANSACTION_ID").Value = Trans_id
                dsINSP_PLT.Fields("PREVIOUS_STATUS").Value = Prev_Status
                dsINSP_PLT.Fields("NEW_STATUS").Value = Trim$(BCField(15))
                dsINSP_PLT.Update
            End If
        End If
        dsCARGO_TRACKING.Update
        
        Mark_Flag = "AT POW"
        Call InsertCTE(Val(BCField(3)), Val(BCField(3)), Barcode, Mark_Flag)
        
        'UPDATE THE ACTIVITY TABLE
        If (dsCARGO_ACTIVITY.RecordCount = 0) Then
            dsCARGO_ACTIVITY_ALL.AddNew
            dsCARGO_ACTIVITY_ALL.Fields("PALLET_ID").Value = Trim$(Barcode)
            dsCARGO_ACTIVITY_ALL.Fields("ARRIVAL_NUM").Value = Val(BCField(3))
            dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = 1
            dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 8
            dsCARGO_ACTIVITY_ALL.Fields("QTY_CHANGE").Value = Val(BCField(6))
            dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
            dsCARGO_ACTIVITY_ALL.Fields("ORDER_NUM").Value = Trim$(BCField(4))
            dsCARGO_ACTIVITY_ALL.Fields("CUSTOMER_ID").Value = BCField(3)
            dsCARGO_ACTIVITY_ALL.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
            dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = Val(BCField(6))
            dsCARGO_ACTIVITY_ALL.Update
        ElseIf Trim$(BCField(6)) <> "" Then 'IF CHANGE QTY RCVD, THEN NEED CHANGE THE QTY_LEFT
            While Not dsCARGO_ACTIVITY.EOF
                dsCARGO_ACTIVITY.Edit
                If Trim$(BCField(8)) <> "" Then 'CHANGE CUSTOMER ID IF CHECKER WANTS TO CHANGE THE CUSTOMER ID
                    dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value = Val(Trim$(BCField(8)))
                End If
                'CHANGE THE QTY CHANGE ON THE SERVICE CODE = 1 WHICH IS SHIP RECEIVING
                If dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 8 Then
                    dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value = dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value + iQtyDiff
                End If
                
                dsCARGO_ACTIVITY.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
    
                'CHANGE EACH ITEM'S QTY LEFT
                dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value = dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value + iQtyDiff
                dsCARGO_ACTIVITY.Update
                dsCARGO_ACTIVITY.MoveNext
            Wend
        ElseIf Trim$(BCField(8)) <> "" Then
            While Not dsCARGO_ACTIVITY.EOF
                dsCARGO_ACTIVITY.Edit
                dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value = Val(Trim$(BCField(8)))
                dsCARGO_ACTIVITY.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
                dsCARGO_ACTIVITY.Update
                dsCARGO_ACTIVITY.MoveNext
            Wend
        End If
    Else
        'OraDatabase.Close
        'Call Initialize_DB
        'PauseForResponse
        'tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Just Try Again)]" ' NEED ADD MESSAGE
        'Exit Sub
    End If
        
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
    Else
        'OraDatabase.Close
        'Call Initialize_DB
        'PauseForResponse
        'tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Just Try Again)]" ' NEED ADD MESSAGE
        'Exit Sub
    End If
    
    'ADD INTO ACTIVITY LOG TABLE IF ANYTHING CHANGED, OTHERWISE, WOULD NOT ADD RECORD INTO ACTIVITY LOG
'    If Trim$(BCField(6)) <> "" Or Trim$(BCField(7)) <> "" Or Trim$(BCField(8)) <> "" Or Trim$(BCField(9)) <> "" Or Trim$(BCField(10)) <> "" Then
'        If Trim(BCField(8)) <> "" Then
'            'Call LOG_ACTIVITY(BCField(1), BCField(2), BCField(5), BCField(11), BCField(4), BCField(8))
'        Else
'            'Call LOG_ACTIVITY(BCField(1), BCField(2), BCField(5), BCField(11), BCField(4), Trim$(BCField(3)))
'        End If
'    End If
    
    'GET COUNT OF PALLETS
    gsSqlStmt = "SELECT DISTINCT PALLET_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
    gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    'GET SUM QTY CHANGE
    gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(3)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
    gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
    Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        Call SendToOutLog(Reader_Addr, BCField(1), EZC_Record, BCField(11), BCField(2), dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value, Barcode, dsCARGO_TRACKING.Fields("CONTAINER_ID").Value, dsCARGO_TRACKING.Fields("RECEIVER_ID").Value)
        If dsCARGO_ACTIVITY.RecordCount = 0 Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YNew Order)]"
            Exit Sub
        Else
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & dsCARGO_ACTIVITY.RecordCount & " Plts)]"
            Exit Sub
        End If
    Else
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
        Exit Sub
    End If
   
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
 '   PauseForResponse
 '   tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
           
End Sub
Private Sub CheckWing()
Dim dsORDER As Object
Dim iCurrArrivalNum As String
Dim iArrival As String
Dim retVal As String
Dim plts As String
Dim iDuplicateCount As Integer
On Error GoTo ErrHandler
    
    
    'modified on 1/8/2000
    'If Trim$(BCField(4)) <> "" Then
        'check customer
        If (Val(BCField(2)) = "439") Or (Val(BCField(2)) = "440") Or (Val(BCField(2)) = "441") Then
            'OK
            gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(BCField(2))
            Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
                'OK
            Else
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",C)]"
                Exit Sub
            End If
        Else
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",C)]"
            Exit Sub
        End If
        
        'check wing
        gsSqlStmt = "SELECT * FROM LOCATION_CATEGORY WHERE LOCATION_TYPE= '" & "W" & Trim$(BCField(4)) & "'"
        Set dsWING_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsWING_PROFILE.RecordCount > 0 Then
            'OK
        Else
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",W)]"
            Exit Sub
        End If
        
        'check order
        'GET PALLET COUNT
        gsSqlStmt = "SELECT DISTINCT PALLET_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(2)))
        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
        gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
        Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        'GET SUM QTY CHANGE
        
        gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUMQty FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(2)))
        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
        gsSqlStmt = gsSqlStmt & " and SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
        Set dsCARGO_ACTIVITY_SUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        'TRACKING RETURN PALLET FOR EXACT PALLET COUNT
        gsSqlStmt = "SELECT DISTINCT PALLET_ID,ARRIVAL_NUM FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = " & Val(Trim$(BCField(2)))
        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
        gsSqlStmt = gsSqlStmt & " AND (SERVICE_CODE = 7 OR SERVICE_CODE = 13) AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
        Set dsCARGO_RETURN_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If dsCARGO_RETURN_ACTIVITY.RecordCount > 0 Then
            While Not dsCARGO_RETURN_ACTIVITY.EOF
                gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUM_CHANGE_THIS_PALLET FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(dsCARGO_RETURN_ACTIVITY.Fields("PALLET_ID").Value) & "'"
                gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(2)))
                gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
                gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_RETURN_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
                gsSqlStmt = gsSqlStmt & " AND (SERVICE_CODE = 7 OR SERVICE_CODE = 13) AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
                Set dsCARGO_RETURN_COUNT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

                gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUM_CHANGE_THIS_PALLET FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(dsCARGO_RETURN_ACTIVITY.Fields("PALLET_ID").Value) & "'"
                gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(2)))
                gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
                gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_RETURN_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
                gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6 AND SERVICE_CODE <> 12 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
                Set dsCARGO_SHIP_COUNT = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

                If Trim$(-dsCARGO_RETURN_COUNT.Fields("SUM_CHANGE_THIS_PALLET").Value) = Trim$(dsCARGO_SHIP_COUNT.Fields("SUM_CHANGE_THIS_PALLET").Value) Then
                    iDuplicateCount = iDuplicateCount + 1
                End If
                dsCARGO_RETURN_ACTIVITY.MoveNext
            Wend
        End If

        gsSqlStmt = "SELECT * FROM DC_ORDER WHERE ORDERNUM = '" & Trim$(BCField(3)) & "'"
        Set dsORDER = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                        
        If (dsORDER.RecordCount = 0) Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "INVALID ORDER. \nCONTACT OFFICE" & ")]"
            Exit Sub
        ElseIf (dsORDER.RecordCount = 1) Then
            If (BCField(2) = "439") Then
                If OraDatabase.LastServerErr = 0 Then
                    If dsCARGO_ACTIVITY.RecordCount = 0 Then
                        retVal = "No Pallets" & "," & dsORDER.Fields("VESSELID").Value & "," & dsORDER.Fields("COMMODITYCODE").Value
                        PauseForResponse
                        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & retVal & ")]"
                        Exit Sub
                    Else 'pallets may have been returned and there is no need to show 0 in 0 plts
                        If Not (dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value = 0) Then
                            plts = Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & Trim(dsCARGO_ACTIVITY.RecordCount - iDuplicateCount) & " Plts"
                        Else
                            plts = "No Pallets"
                        End If
                        retVal = plts & "," & dsORDER.Fields("VESSELID").Value & "," & dsORDER.Fields("COMMODITYCODE").Value
                        PauseForResponse
                        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & retVal & ")]"
                        Exit Sub
                    End If
                Else
                    OraDatabase.Close
                    Call Initialize_DB
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
                    Exit Sub
                End If
            Else
                If OraDatabase.LastServerErr = 0 Then
                    If dsCARGO_ACTIVITY.RecordCount = 0 Then
                        PauseForResponse
                        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YNo Pallets)]"
                        Exit Sub
                    ElseIf (dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value = 0) Then
                        PauseForResponse
                        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",YNo Pallets)]"
                        Exit Sub
                    Else
                        PauseForResponse
                        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & Trim$(dsCARGO_ACTIVITY_SUM.Fields("SUMQty").Value) & " In " & Trim(dsCARGO_ACTIVITY.RecordCount - iDuplicateCount) & " Plts)]"
                        Exit Sub
                    End If
                Else
                    OraDatabase.Close
                    Call Initialize_DB
                    PauseForResponse
                    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Try Again)]"
                    Exit Sub
                End If
            End If
        ElseIf (dsORDER.RecordCount > 1) Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "DUPLICATE ORDERS \nFOUND. CONTACT OFFICE" & ")]"
            Exit Sub
        End If
    'Else 'if checker put nothing, pass it(Now handheld program do allow no value for Wing)
    '    PauseForResponse
    '    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
    'End If
    
    Exit Sub
ErrHandler:

If Err.Number <> 0 Then
    PauseForResponse
    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
    Exit Sub
End If
End Sub
Private Sub LOG_ACTIVITY(LOG_DATE As String, ID As String, user As String, SUPER As String, Barcode As String, Arrival_Num As String, LOT_ID As String, WHS_CODE As String, ACTIVITY_NUM As Integer, qty As Integer, Weight As Long, LOCATION As String, STATUS As String, SERVICE_CODE As Integer, ACTIVITY_STATUS As String, isDmg As String, ship_ok As String)
  ' On Error GoTo DbError
     
    'OraSession.BeginTrans
    dsACTIVITY_LOG.AddNew
    dsACTIVITY_LOG.Fields("LOG_DATE").Value = LOG_DATE
    dsACTIVITY_LOG.Fields("LOG_ID").Value = ID
    dsACTIVITY_LOG.Fields("SUP_NAME").Value = SUPER
    dsACTIVITY_LOG.Fields("CHECKER_NAME").Value = user
    dsACTIVITY_LOG.Fields("BARCODE").Value = Barcode
    dsACTIVITY_LOG.Fields("ARRIVAL_NUM").Value = Arrival_Num
    dsACTIVITY_LOG.Fields("MILL_ORDER_NUM").Value = LOT_ID
    dsACTIVITY_LOG.Fields("WAREHOUSE_MARK").Value = WHS_CODE
    dsACTIVITY_LOG.Fields("ACTIVITY_NUM").Value = ACTIVITY_NUM
    dsACTIVITY_LOG.Fields("QTY_IN_HOUSE").Value = qty
    dsACTIVITY_LOG.Fields("GROSS_WEIGHT").Value = Weight
    dsACTIVITY_LOG.Fields("WAREHOUSE_LOCATION").Value = LOCATION
    dsACTIVITY_LOG.Fields("ROLL_STATUS").Value = STATUS
    dsACTIVITY_LOG.Fields("DAMAGE_TYPE").Value = isDmg
    dsACTIVITY_LOG.Fields("SHIP_OK").Value = ship_ok
    dsACTIVITY_LOG.Fields("SERVICE_CODE").Value = SERVICE_CODE
    dsACTIVITY_LOG.Fields("ACTIVITY_STATUS").Value = ACTIVITY_STATUS
    dsACTIVITY_LOG.Update
    
'    If OraDatabase.LastServerErr = 0 Then
'        OraSession.CommitTrans
'    Else
'        OraDatabase.Close
'        Call Initialize_DB
'        'OraSession.Rollback
'    End If
    
    Exit Sub
    
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
    
    
    
End Sub

Private Sub LOG_DAMAGE(LOG_DATE As String, ID As String, user As String, SUPER As String, Barcode As String, Arrival_Num As String, LOT_ID As String, iType1() As String, iType2() As String)
  ' On Error GoTo DbError
     
    Dim i As Integer
    
'    OraSession.BeginTrans
    
    For i = 0 To 4
        If (iType1(i) <> "") Then
            dsDAMAGE_LOG.AddNew
            dsDAMAGE_LOG.Fields("LOG_DATE").Value = LOG_DATE
            dsDAMAGE_LOG.Fields("LOG_ID").Value = ID
            dsDAMAGE_LOG.Fields("SUP_NAME").Value = SUPER
            dsDAMAGE_LOG.Fields("CHECKER_NAME").Value = user
            dsDAMAGE_LOG.Fields("BARCODE").Value = Barcode
            dsDAMAGE_LOG.Fields("ARRIVAL_NUM").Value = Arrival_Num
            dsDAMAGE_LOG.Fields("MILL_ORDER_NUM").Value = LOT_ID
            dsDAMAGE_LOG.Fields("DAMAGE_TYPE1").Value = iType1(i)
            dsDAMAGE_LOG.Fields("DAMAGE_TYPE2").Value = iType2(i)
            dsDAMAGE_LOG.Update
        End If
    Next
    
'    If OraDatabase.LastServerErr = 0 Then
'        OraSession.CommitTrans
'    Else
'        OraDatabase.Close
'        Call Initialize_DB
'        'OraSession.Rollback
'    End If
    
    Exit Sub
    
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
    
    
    
End Sub
Private Sub PauseForResponse()
    LTime = Timer()
    While Timer() - LTime < 1
      DoEvents
    Wend
End Sub
Private Sub returnInquery()
    'FIELDS: 1.RTI, 2. BARCODE,3. PAGE#, 4.CUSTOMER, 5.ORDER_NUM,
'    Dim iPageNum As Integer
'
'    iPageNum = 0
'
'    If Trim$(BCField(5)) <> "" Then
'        iPageNum = Val(BCField(5))
'    End If
'
'    Barcode = Trim$(BCField(4))
'
'    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY_NEW WHERE PALLET_ID = '" & Barcode & "'"
'    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(2)))
'    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(3)) & "'"
'    gsSqlStmt = gsSqlStmt & " ORDER BY PALLET_ID,ARRIVAL_NUM,ACTIVITY_NUM"
'    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'
'    iRecordCount = dsCARGO_ACTIVITY.RecordCount
'
'    While i <= (iPageNum - 1) 'SKIP THE PREVIOUS PAGE
'        dsCARGO_ACTIVITY.MoveNext
'        i = i + 1
'    Wend
 
    Dim i As Integer
    Dim isCustomerName As String
    Dim isString1 As String
    Dim isCommodityName As String
    Dim isString2 As String
    Dim isLocation As String
    Dim isString3 As String
    Dim isString4 As String
    Dim isDmgd As String
    Dim isString5 As String
    Dim isString6 As String
    Dim isString7 As String
    Dim iArrival As String
    Dim isDateRcvd As String
    Dim isOrderNum As String
    Dim isOne As Boolean
    Dim isNumRecord As Long
    Dim iCurrArrivalNum As String
    Dim isString As String
    Dim isQtyShipped As Long
    Dim isDateShipped As String
    
    i = 0
    isCustomerName = ""
    isString1 = ""
    isCommodityName = ""
    isString2 = ""
    isLocation = ""
    isString3 = ""
    isString4 = ""
    isDmgd = ""
    isString5 = ""
    isString6 = ""
    isString7 = ""
    iArrival = ""
    isDateRcvd = ""
    isOrderNum = ""
    isOne = True
    isNumRecord = 0
    isString = ""
    iCurrArrivalNum = ""
    isQtyShipped = 0
    isDateShipped = ""
    
    Barcode = Trim$(BCField(2))
    
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(4)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(5)) & "'"
    gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6 "
    gsSqlStmt = gsSqlStmt & " AND (ACTIVITY_DESCRIPTION is null OR ACTIVITY_DESCRIPTION<>'VOID') "
    gsSqlStmt = gsSqlStmt & " ORDER BY PALLET_ID,ARRIVAL_NUM,ACTIVITY_NUM"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If dsCARGO_ACTIVITY.RecordCount > 1 Then
        isOne = False
    End If
    
    isNumRecord = dsCARGO_ACTIVITY.RecordCount

    If OraDatabase.LastServerErr = 0 Then
        If dsCARGO_ACTIVITY.RecordCount = 0 Then
            ' NO PALLET
            'MESSAGE SAY UNKNOWN PALLET
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XUNKNOWN PALLET)]"
            Exit Sub
        End If
    Else 'ORACLE ERROR
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XORACLE ERROR, TRY IT AGAIN)]"
        Exit Sub
    End If
       
    i = i + 1
    
    While i < Val(BCField(3))
        dsCARGO_ACTIVITY.MoveNext
        i = i + 1
    Wend
    
    'GET PARTICULAR PALLET INFORMATION
    
    'GET INFORMATION FOR DISPLAYING
    isQtyShipped = dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value
    isDateShipped = Format$(dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value, "MM/DD/YY")
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value))
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME")) Then
        isCustomerName = Left$(Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value), 12)
    Else
        isCustomerName = ""
    End If
    
    'GET ARRIVAL NUM AND ARRIVAL NAME IF FROM SHIP. IF FROM TRUCK, GIVE THE ARRIVAL NUM
    If Not IsNull(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM")) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Val(Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value))
        Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If dsVESSEL_PROFILE.RecordCount > 0 Then 'CARGO FROM SHIP
            If Not IsNull(dsVESSEL_PROFILE.Fields("VESSEL_NAME")) Then
                iCurrArrivalNum = Trim(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)
                iArrival = iCurrArrivalNum & "-" & Trim$(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value)
            Else 'ALMOST NO THIS CASE
                iCurrArrivalNum = ""
                iArrival = ""
            End If
        Else 'FROM TRUCK
            iCurrArrivalNum = Trim(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)
            iArrival = Trim(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)
        End If
    Else 'ALMOST NO THIS CASE
        iCurrArrivalNum = ""
        iArrival = ""
    End If
    
    'PREPARE CARGO TRACKING INFORMATION
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Barcode & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(Trim$(BCField(4)))
    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If Not IsNull(dsCARGO_TRACKING.Fields("COMMODITY_CODE")) Then
        gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Val(Trim$(dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value))
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If Not IsNull(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME")) Then
            If (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5605") Then
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 11), 5)
            ElseIf (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5603") Then
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 4) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 18), 4)
            Else
                isCommodityName = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 14), 5)
            End If
            isCommodityName = dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value & "-" & isCommodityName
        Else
            isCommodityName = dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value
        End If
    Else
        isCommodityName = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("QTY_DAMAGED")) Then
        isDmgd = Trim$(dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value)
    Else
        isDmgd = ""
    End If
            
    If Not IsNull(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION")) Then
        isLocation = Trim$(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value)
    Else
        isLocation = ""
    End If
    
    If Not IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED")) Then
        isDateRcvd = Format(Trim$(dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value), "MM/DD/YY")
    Else
        isDateRcvd = ""
    End If
    
    isQtyShipped = isQtyShipped - dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value
    
    isString1 = Trim$(isCustomerName)
    isString1 = Left$(isString1 & "          ", 10)
    isString2 = Trim$(isCommodityName)
    isString2 = Left$(isString2 & "          ", 14)
    isString3 = Trim$(isLocation)
    isString3 = Left$(isString3 & "          ", 10)
    isString4 = Trim$(dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value)
    'isString4 = Left$(isString4 & "   ", 3)
    isString5 = isDmgd
    'isString5 = Left$(isString5 & "   ", 3)
    isString6 = Trim$(dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value)
    'isString6 = Left$(isString6 & "   ", 3)
    isString7 = Left$(iArrival & "          ", 10) 'GET SHIP NAME OR ORDER NUMBER
    isString7 = Left$(isString7 & "," & isDateRcvd & "                     ", 20)
    isString7 = Left$(isString7 & "," & Trim$(isDateShipped) & "," & isQtyShipped & "                             ", 33)
    
    If Val(BCField(3)) = 1 Then
        If isNumRecord = 1 Then
            isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & " ," & isString5 & "   ," & isString6 & "," & isString7 & "," & isNumRecord
            'send message to handheld
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & isString & ")]"
            Exit Sub
        Else
            isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & "," & isString5 & "," & isString6 & "," & isString7 & "," & isNumRecord
            'send message to handheld
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",D" & isString & ")]"
            Exit Sub
        End If
        
    Else 'IF MORE THAN ONE
        isString = isString1 & "," & isString2 & "," & isString3 & "," & isString4 & "," & isString5 & "," & isString6 & "," & isString7 & "," & isNumRecord
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",D" & isString & ")]"
        Exit Sub
    End If
    
    
End Sub

Private Sub returnAction()
    'FIELDS: 1.RT, 2. USER NAME, 3. CUSTOMER, 4.ORDER_NUM, 5. BARCODE, 6.QTY RETURN, 7. supervisor, 8. QTY DAMAGED,9,PAGE#, 10. RETURN TYPE(DOCK/FULL)
    
    
    Dim iIsMyPlt As Boolean
    Dim iSumOut As Long
    Dim iSumReturn As Long
    Dim i As Integer
    Dim iSamePallt As Boolean
    Dim msg As String
    Dim Mark_Flag As String
    
    
    iIsMyPlt = False
    iSumOut = 0
    iSumReturn = 0
    iSamePallt = False
    
    'On Error GoTo DbError
    
    'Oppenheimer Fix
    'If Len(Trim$(BCField(5))) = 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 10)
    'Else
    '    Barcode = Trim$(BCField(5))
    'End If

    'If Len(Trim$(BCField(5))) > 12 And Len(Trim$(BCField(7)) <> 20 Then
    '    Barcode = Right(Trim$(BCField(5)), 12)
    'End If
    Barcode = Trim$(BCField(5))
    
    gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Barcode & "'"
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(3)))
    gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(BCField(4)) & "'"
    'gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6 "
    gsSqlStmt = gsSqlStmt & " AND (ACTIVITY_DESCRIPTION is null or ACTIVITY_DESCRIPTION <> 'VOID')"
    gsSqlStmt = gsSqlStmt & " ORDER BY PALLET_ID,ARRIVAL_NUM,ACTIVITY_NUM"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    i = i + 1
    
    While i < Val(BCField(9))
        dsCARGO_ACTIVITY.MoveNext
        i = i + 1
    Wend
    
    'GET TRACKING TABLE READY TO UPDATE
    
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(Trim$(BCField(3)))
    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            
    
    If OraDatabase.LastServerErr = 0 Then
        
        'GET MAX ACTIVITY NUMBER
        gsSqlStmt = "SELECT MAX(ACTIVITY_NUM) MACTIVITY_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(3)))
        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
        
        Set dsCARGO_ACTIVITY_A = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        'SUM QTY CHANGE OF OUTBOUND
        gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUM_OUT FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(3)))
        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
        gsSqlStmt = gsSqlStmt & "AND ORDER_NUM='" & Trim$(BCField(4)) & "'"
        gsSqlStmt = gsSqlStmt & "AND SERVICE_CODE = 6  AND (ACTIVITY_DESCRIPTION is null or ACTIVITY_DESCRIPTION <> 'VOID')"
        Set dsCARGO_ACTIVITY_C = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

        If Not IsNull(dsCARGO_ACTIVITY_C.Fields("SUM_OUT")) Then
            iSumOut = dsCARGO_ACTIVITY_C.Fields("SUM_OUT").Value
        Else
            iSumOut = 0
        End If

        'SUM QTY CHANGE OF RETURN
        gsSqlStmt = "SELECT SUM(QTY_CHANGE) SUM_RETURN FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(3)))
        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
        gsSqlStmt = gsSqlStmt & "AND ORDER_NUM='" & Trim$(BCField(4)) & "'"
        gsSqlStmt = gsSqlStmt & "AND (SERVICE_CODE = 7 OR SERVICE_CODE = 13) AND (ACTIVITY_DESCRIPTION is null or ACTIVITY_DESCRIPTION <> 'VOID')"
        Set dsCARGO_ACTIVITY_D = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

        If Not IsNull(dsCARGO_ACTIVITY_D.Fields("SUM_RETURN")) Then
            iSumReturn = -dsCARGO_ACTIVITY_D.Fields("SUM_RETURN").Value
            iSamePallt = True
        Else
            iSumReturn = 0
        End If
        
'        'CHECK IF THE SAME PALLET RETURN AGAIN
'        gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY_NEW WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
'        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(3)))
'        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
'        gsSqlStmt = gsSqlStmt & "AND ORDER_NUM='" & Trim$(BCField(4)) & "'"
'        gsSqlStmt = gsSqlStmt & "AND SERVICE_CODE = 7 AND (ACTIVITY_DESCRIPTION is null or ACTIVITY_DESCRIPTION <> 'VOID')"
'        Set dsCARGO_ACTIVITY_F = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)

        
        'qty return must <= qty CHANGE
        If (OraDatabase.LastServerErr = 0) And (iSumOut >= iSumReturn + Val(BCField(6))) Then
            iIsMyPlt = True
        End If
        
        gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(2)) & "'"
        Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        If OraDatabase.LastServerErr = 0 And iIsMyPlt Then
            'Begin a transaction
            OraSession.BeginTrans
            dsCARGO_ACTIVITY_ALL.AddNew
            dsCARGO_ACTIVITY_ALL.Fields("PALLET_ID").Value = Barcode
            dsCARGO_ACTIVITY_ALL.Fields("ARRIVAL_NUM").Value = Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value)
            dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = dsCARGO_ACTIVITY_A.Fields("MACTIVITY_NUM").Value + 1
            If Trim$(BCField(10)) = "FULL" Then
                dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 7
            Else
                dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 13
            End If
            
            dsCARGO_ACTIVITY_ALL.Fields("QTY_CHANGE").Value = -Val(BCField(6))
            dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value + Val(BCField(6))
            dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
            dsCARGO_ACTIVITY_ALL.Fields("ORDER_NUM").Value = BCField(4)
            dsCARGO_ACTIVITY_ALL.Fields("CUSTOMER_ID").Value = Val(BCField(3))
            dsCARGO_ACTIVITY_ALL.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
            dsCARGO_ACTIVITY_ALL.Update
            
            'UPDATE CARGO_TRACKING
            dsCARGO_TRACKING.Edit
            dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = Val(dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value) + Val(BCField(6))
            
'           'SET STORAGE DATE ON THE RETURN DAY IF IS FULL RETURN
'            If Trim$(BCField(10)) = "FULL" Then
'                If IsNull(dsCARGO_TRACKING.Fields("BILLING_STORAGE_DATE")) Or Format$(Now, "MM/DD/YYYY") > Format$(dsCARGO_TRACKING.Fields("BILLING_STORAGE_DATE").Value, "MM/DD/YYYY") Then
'                    dsCARGO_TRACKING.Fields("BILLING_STORAGE_DATE").Value = Format$(Now, "MM/DD/YYYY HH:MM:SS")
'                End If
'            End If
            
            'Set storage date to the later of return date, free time end, and storage date  -- LFW, 4/21/05
            If Trim$(BCField(10)) = "FULL" Then
                If Not IsNull(dsCARGO_TRACKING.Fields("FREE_TIME_END")) Then
                    If IsNull(dsCARGO_TRACKING.Fields("BILLING_STORAGE_DATE")) Then
                        If Format$(Now, "MM/DD/YYYY") > Format$(dsCARGO_TRACKING.Fields("FREE_TIME_END").Value, "MM/DD/YYYY") Then
                            dsCARGO_TRACKING.Fields("BILLING_STORAGE_DATE").Value = Format$(Now, "MM/DD/YYYY HH:MM:SS")
                        Else
                            dsCARGO_TRACKING.Fields("BILLING_STORAGE_DATE").Value = dsCARGO_TRACKING.Fields("FREE_TIME_END").Value
                        End If
                    Else
                        If Format$(Now, "MM/DD/YYYY") > Format$(dsCARGO_TRACKING.Fields("BILLING_STORAGE_DATE").Value, "MM/DD/YYYY") Then
                            dsCARGO_TRACKING.Fields("BILLING_STORAGE_DATE").Value = Format$(Now, "MM/DD/YYYY HH:MM:SS")
                        End If
                    End If
                End If
            End If
            
            If Trim$(BCField(7)) <> "" Then
                If Not IsNull(dsCARGO_TRACKING.Fields("QTY_DAMAGED")) Then
                    dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value = dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value + Val(BCField(8))
                Else
                    dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value = Val(BCField(8))
                End If
            End If
            dsCARGO_TRACKING.Fields("MARK").Value = "AT POW"
            dsCARGO_TRACKING.Update
            Mark_Flag = "AT POW"
            Call InsertCTE(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value, dsCARGO_TRACKING.Fields("RECEIVER_ID").Value, Barcode, Mark_Flag)
            
            'UPDATE ACTIVITY_DESCRIPTION TO RETURN
            gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
            gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(BCField(3)))
            gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value) & "'"
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM='" & Trim$(BCField(4)) & "'"
            gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = 6  AND (ACTIVITY_DESCRIPTION is null or ACTIVITY_DESCRIPTION <> 'VOID')"

            Set dsCARGO_ACTIVITY_E = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            While Not dsCARGO_ACTIVITY_E.EOF
                dsCARGO_ACTIVITY_E.Edit
                dsCARGO_ACTIVITY_E.Fields("ACTIVITY_DESCRIPTION").Value = "RETURN"
                dsCARGO_ACTIVITY_E.Update
                dsCARGO_ACTIVITY_E.MoveNext
            Wend
            
'            dsCARGO_ACTIVITY.Edit
'            dsCARGO_ACTIVITY.Fields("ACTIVITY_DESCRIPTION").Value = "RETURN"
'            dsCARGO_ACTIVITY.Update

        Else
            If Not iIsMyPlt Then
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XQTY RETURNED CAN NOT BE GREATER THAN QTY SHIPPED OUT)]"
                Exit Sub
            End If
        End If
    
        If OraDatabase.LastServerErr = 0 And iIsMyPlt Then
            OraSession.CommitTrans
            'PASS LOG INFORMATION
            Call SendToOutLog(Reader_Addr, BCField(1), "Y" & msg, BCField(7), BCField(2), dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value, Barcode, dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value, dsCARGO_TRACKING.Fields("RECEIVER_ID").Value)
            msg = "Returned " & BCField(6) & " on plt" & "\n" & BCField(5)
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & msg & ")]"
            Exit Sub
        Else
            If OraDatabase.LastServerErr <> 0 Then
                OraDatabase.Close
                Call Initialize_DB
            End If
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XORACLE ERROR, TRY IT AGAIN.)]"
            Exit Sub
       'USE O ERROR CODE FOR ORACLE ERROR
        End If
      
    Else
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XORACLE ERROR, TRY IT AGAIN.)]"
        Exit Sub
    End If
    
    Exit Sub
    
    
DbError:

    OraDatabase.Close
    Call Initialize_DB
    Err = 0
'    PauseForResponse
'    tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XORACLE ERROR, TRY IT AGAIN.)]"
    Exit Sub
End Sub
Private Sub findPallet()
    
On Error GoTo ErrHandler:

Dim arrTranstext() As String
Dim bc As String
Dim sqlStmt As String
Dim retVal As String
Dim qty_rcvd As String
Dim qty_dmgd As String
Dim plt_loc As String
Dim plt_id As String
Dim is_hospital As String
Dim arr_num As String
Dim cust As String
Dim comm As String
Dim variety As String
Dim size As String
Dim pck_hse As String
Dim Source As String
Dim pow As String
Dim FFM As String
Dim ds As Object
Dim msg As String
Dim count As Variant
Dim CLM As String

    '' Step-1: Get Barcode
    arrTranstext = Split(Trim(EZC_Record), ",")
    bc = arrTranstext(1)
    arr_num = arrTranstext(2)
    plt_id = Left(Right(bc, 6), 6)
    pow = Left(bc, 3)
    Source = arrTranstext(3)
  
    
   
        '' Step-2: Query the Database
        '' A.QTY_RECEIVED, A.QTY_DAMAGED, A.WAREHOUSE_LOCATION, A.DATE_RECEIVED
        sqlStmt = "SELECT A.*" & _
            " FROM CARGO_TRACKING A" & _
            " WHERE A.ARRIVAL_NUM = '" & arr_num & "'" & _
            " AND A.PALLET_ID = '" & bc & "'"
            '" AND A.RECEIVER_ID=439 AND A.COMMODITY_CODE=5602" & _

    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    
    If OraDatabase.LastServerErr > 0 Then
        'ORACLE ERROR
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "DB SERVER ERROR" & ")]"
        Exit Sub
    
    ElseIf (dsCARGO_TRACKING.RecordCount = 0) Then
'        If (pow = "POW") Then
'            PauseForResponse
'            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & ")]"
'            Exit Sub
'        End If
        count = Len(bc)
        sqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & bc & "'"
        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
        If (dsCARGO_TRACKING.RecordCount > 0) Then
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "PALLET DOES NOT \nMATCH THIS VESSEL." & ")]"
            Exit Sub
        ElseIf (count = 28) Then 'See if information can be taken from barcode.
            CLM = Left(bc, 9)
            If Not CLM = "611124520" Then 'barcode does not match CLM format
                msg = "UNKNOWN PALLET. \nMUST CREATE"
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
                Exit Sub
            End If
            qty_rcvd = Val(Right(bc, 3))
            FFM = Left(Right(bc, 19), 4)
            pck_hse = Left(Right(bc, 15), 5)
            sqlStmt = "SELECT * FROM CLM_SIZEMAP A WHERE A.FFM = '" & FFM & "'"
            Set ds = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
            If (ds.RecordCount = 0) Then 'barcode does not match CLM format
                msg = "UNKNOWN PALLET. \nMUST CREATE. \nSET PALLET ASIDE."
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
                Exit Sub
            End If
            size = Val(ds.Fields("DC").Value)
            retVal = qty_rcvd & "," & pck_hse & "," & size
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",W" & retVal & ")]"
            Exit Sub
        ElseIf (count = 30) Then 'see if info can be taken from barcode
            CLM = Left(bc, 9)
            If Not CLM = "611124520" Then 'barcode does not match CLM format
                msg = "UNKNOWN PALLET. \nMUST CREATE. \nSET PALLET ASIDE."
                PauseForResponse
                tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
                Exit Sub
            End If
            qty_rcvd = Val(Right(Left(bc, 27), 4))
            size = Val(Right(bc, 3))
            pck_hse = Right(Left(bc, 17), 4)
            retVal = qty_rcvd & "," & pck_hse & "," & size
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",W" & retVal & ")]"
            Exit Sub
        ElseIf (count < 28) Then
            msg = "UNKNOWN PALLET. \nMUST CREATE. \nSET PALLET ASIDE."
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",N" & msg & ")]"
            Exit Sub
        End If
    ElseIf (dsCARGO_TRACKING.RecordCount = 1) Then
        ''retVal = "1000,25,WING C3"
        
        '' step-1
        '' Check for 'NULL' !!!! display '0' if 'NULL' (QTY_RECEIVED, QTY_DAMAGED)
        '' for location display 'NONE' if 'NULL'
        ''
        '' QTY_RECEIVED
        If (IsNull(dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value)) Then
            qty_rcvd = "0"
        Else
            qty_rcvd = dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
        End If
        
        '' QTY_DAMAGED
        If (IsNull(dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value)) Then
            qty_dmgd = "0"
        Else
            qty_dmgd = dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value
        End If
        
        '' LOCATION
        If (IsNull(dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value)) Then
            plt_loc = "None"
        Else
            plt_loc = dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value
        End If
        
        'Customer
        sqlStmt = "SELECT SUBSTR(CUSTOMER_NAME, 0, 11)THE_CUST FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & dsCARGO_TRACKING.Fields("RECEIVER_ID").Value & ""
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
        If (IsNull(dsCUSTOMER_PROFILE.Fields("THE_CUST").Value)) Then
            cust = "None"
        Else
            cust = dsCUSTOMER_PROFILE.Fields("THE_CUST").Value
        End If
        
        'Commodity
        sqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value & ""
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
        If (IsNull(dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value)) Then
            comm = "None"
        Else
            If (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5605") Then
                comm = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 11), 5)
            ElseIf (dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value = "5603") Then
                comm = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 4) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 18), 4)
            Else
                comm = Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 3) & " " & Right(Left(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value, 14), 5)
            End If
            comm = dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value & "-" & comm
            comm = Left$(comm & "          ", 12)
        End If
        
        'Variety
        If (IsNull(dsCARGO_TRACKING.Fields("VARIETY").Value)) Then
            variety = "None"
        Else
            variety = dsCARGO_TRACKING.Fields("VARIETY").Value
        End If
        
        'Size
        If (IsNull(dsCARGO_TRACKING.Fields("CARGO_SIZE").Value)) Then
            size = "0"
        Else
            size = dsCARGO_TRACKING.Fields("CARGO_SIZE").Value
        End If
        
        'Packing House
        If (IsNull(dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value)) Then
            pck_hse = "None"
        Else
            pck_hse = dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value
        End If
        
        
        '' DATE_RECEIVED
        ''If (IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value)) Then
        ''    date_rcvd = "NO DATE"
        ''Else
        ''    date_rcvd = dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value
        ''End If
        
        '' CARGO_STATUS
        If (IsNull(dsCARGO_TRACKING.Fields("CARGO_STATUS").Value)) Then
            is_hospital = "N"
        Else
            If UCase(dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "H") Then
                is_hospital = "Y"
            ElseIf UCase(dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "R") Then
                is_hospital = "N"
            ElseIf UCase(dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "B") Then
                is_hospital = "Y"
            Else
                is_hospital = "UNKNOWN"
            End If
        End If
        
        
        retVal = cust & "," & comm & "," & qty_rcvd & "," & qty_dmgd & "," & plt_loc & "," & variety & "," & size & "," & pck_hse & "," & bc & "," & is_hospital
            
'        '' step-2
'        '' update DATE_RECEIVED in CT only if DATE_RECEIVED is 'NULL'
'        '' otherwise do nothing
'        If (IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value)) Then
'            OraSession.BeginTrans
'            dsCARGO_TRACKING.Edit
'            dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value = Format(Now, "mm/dd/yyyy HH:MM:ss AMPM")
'            dsCARGO_TRACKING.Update
'            OraSession.CommitTrans
'
'        End If
        
        '' step-3
        '' Emit message
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & retVal & ")]"
        Exit Sub
    
    ElseIf (dsCARGO_TRACKING.RecordCount > 1) Then
        msg = "THIS IS DUPLICATE CONTACT OFFICE"
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & msg & ")]"
        Exit Sub
    End If

ErrHandler:

    If Err.Number <> 0 Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        
        Exit Sub
    End If
End Sub
Private Sub shipReceivingException()
'   FIELDS :  1. 'SRX' , 2. USER NAME, 3. LR_NUM , 4. BARCODE, 5. QTY RCVD, 6. QTY DMGD, 7. CUST #, 8. COMMODITY, 9. LOCATION, 10. SUPERVISOR, 11. VARIETY, 12. SIZE, 13. PACKING HOUSE, 14. Hospital, 15.QTY EXPECTED
    Dim iMyLoc As String
    Dim dsCLM As Object
    Dim Mark_Flag As String
    Dim Prev_Status As String
    Dim Trans_id As Integer
    Dim dsINSP_LOG As Object
    Dim dsINSP_PLT As Object
    Dim inSqlStmt As String
    
    On Error GoTo ErrHandler

    'If Len(Trim$(BCField(4))) = 20 Then
    '    Barcode = Right(Trim$(BCField(4)), 10)
    'Else
    '    Barcode = Trim$(BCField(4))
    'End If

    'If Len(Trim$(BCField(4))) > 12 And Len(Trim$(BCField(4))) <> 20 Then
    '    Barcode = Right(Trim$(BCField(4)), 12)
    'End If
    iMyLoc = ""
    
    Barcode = Trim$(BCField(4))
    
    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(2)) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    iUpdateAccess = False
    
    'CHECK IF PALLETS ON THAT SHIP BELONG TO ONE CUSTOMER
    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(Barcode) & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(BCField(7))
    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(BCField(3)) & "'"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If dsCARGO_TRACKING.RecordCount > 1 Then
            'MESSAGE, CAN NOT CREATE THIS PALLET BECAUSE THIS CUSTOMER HAS MORE THAN ONE PALLETS FROM THIS THIP
            PauseForResponse
            tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XCan not add, See Admin)]"
            Exit Sub
        End If
    Else
        Call Initialize_DB
        'OraSession.Rollback
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Just Try Again)]"
        Exit Sub
    End If
    gsSqlStmt = "SELECT DISTINCT(DC), WEIGHT FROM CLM_SIZEMAP WHERE DC = '" & Val(BCField(12)) & "'"
    Set dsCLM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    OraSession.BeginTrans
    'ADD NEW RECORD-PALLET INTO SYSTEM
    'CATGO TRACKING TABLE AND CARGO ACTIVITY TABLE
        
    'ADD INTO CARGO TRACKING TABLE
    dsCARGO_TRACKING_ALL.AddNew
    dsCARGO_TRACKING_ALL.Fields("PALLET_ID").Value = Barcode
    dsCARGO_TRACKING_ALL.Fields("ARRIVAL_NUM").Value = Trim(BCField(3))
    dsCARGO_TRACKING_ALL.Fields("QTY_IN_HOUSE").Value = Val(BCField(5))
    dsCARGO_TRACKING_ALL.Fields("COMMODITY_CODE").Value = Val(BCField(8))
    dsCARGO_TRACKING_ALL.Fields("DATE_RECEIVED").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
    dsCARGO_TRACKING_ALL.Fields("RECEIVER_ID").Value = Val(BCField(7))
    'dsCARGO_TRACKING_ALL.Fields("CARGO_MANIFEST").Value = "Y"
    dsCARGO_TRACKING_ALL.Fields("RECEIVING_TYPE").Value = "S"
    dsCARGO_TRACKING_ALL.Fields("BATCH_ID").Value = Trim$(BCField(15))
    dsCARGO_TRACKING_ALL.Fields("FROM_SHIPPING_LINE").Value = "6000"
    dsCARGO_TRACKING_ALL.Fields("SHIPPING_LINE").Value = "6000"
    dsCARGO_TRACKING_ALL.Fields("CARGO_DESCRIPTION").Value = Trim$(BCField(13)) & "-" & Val(BCField(12)) & "-" & Val(BCField(15))
'    dsCARGO_TRACKING_ALL.Fields("WEIGHT").Value = dsCLM.Fields("WEIGHT").Value
    dsCARGO_TRACKING_ALL.Fields("WEIGHT_UNIT").Value = "KG"
    'dsCARGO_TRACKING_ALL.Fields("BOL").Value = "0"
    dsCARGO_TRACKING_ALL.Fields("CARGO_TYPE_ID").Value = "1"
    If Trim$(BCField(5)) <> "" Then
        dsCARGO_TRACKING_ALL.Fields("QTY_RECEIVED").Value = Val(BCField(5))
    End If
    
    If Trim$(BCField(6)) <> "" Then
        dsCARGO_TRACKING_ALL.Fields("QTY_DAMAGED").Value = Val(BCField(6))
    End If
    
    If Trim$(BCField(9)) <> "" And Trim$(BCField(9)) <> "NO" Then
'        If Trim$(BCField(16)) <> "" Then
'            dsCARGO_TRACKING_ALL.Fields("WAREHOUSE_LOCATION").Value = Right$(Trim$(BCField(9)), 12) & Trim$(BCField(16))
'        Else
            dsCARGO_TRACKING_ALL.Fields("WAREHOUSE_LOCATION").Value = Right$(Trim$(BCField(9)), 12)
        'End If
'    Else
'        If Trim$(BCField(16)) <> "" Then
'            dsCARGO_TRACKING_ALL.Fields("WAREHOUSE_LOCATION").Value = Trim$(BCField(16))
'        End If
    End If
    
    If Trim$(BCField(11)) <> "" Then
        dsCARGO_TRACKING_ALL.Fields("VARIETY").Value = Trim$(BCField(11))
    End If
    
    If Trim$(BCField(12)) <> "" Then
        dsCARGO_TRACKING_ALL.Fields("CARGO_SIZE").Value = Val(BCField(12))
    End If
    
    If Trim$(BCField(13)) <> "" Then
        dsCARGO_TRACKING_ALL.Fields("EXPORTER_CODE").Value = Trim$(BCField(13))
    End If
    
    If Trim$(BCField(14)) = "Y" Then
        dsCARGO_TRACKING_ALL.Fields("CARGO_STATUS").Value = "H"
        
        gsSqlStmt = "SELECT * FROM DC_INSPECTION_LOG ORDER BY TRANSACTION_ID"
        Set dsINSP_LOG = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        inSqlStmt = "SELECT * FROM DC_INSPECTED_PALLET"
        Set dsINSP_PLT = OraDatabase.DbCreateDynaset(inSqlStmt, 0&)
        
        If (dsINSP_LOG.RecordCount > 0) Then
            dsINSP_LOG.MoveLast
            Trans_id = dsINSP_LOG.Fields("TRANSACTION_ID").Value + 1
        Else
            Trans_id = 1
        End If
        Prev_Status = ""
        dsINSP_LOG.AddNew
        dsINSP_LOG.Fields("TRANSACTION_ID").Value = Trans_id
        dsINSP_LOG.Fields("INSPECTOR_ID").Value = BCField(2)
        dsINSP_LOG.Fields("INSPECTION_DATETIME").Value = Format(Now, "MM/DD/YYYY HH:MM:SS AMPM")
        dsINSP_LOG.Fields("ACTION_TYPE").Value = "HOSPITAL"
        dsINSP_LOG.Fields("NUM_PALLETS").Value = "1"
        dsINSP_LOG.Fields("ARRIVAL_NUM").Value = BCField(3)
        dsINSP_LOG.Update
        
        dsINSP_PLT.AddNew
        dsINSP_PLT.Fields("PALLET_ID").Value = Barcode
        dsINSP_PLT.Fields("TRANSACTION_ID").Value = Trans_id
        dsINSP_PLT.Fields("PREVIOUS_STATUS").Value = Prev_Status
        dsINSP_PLT.Fields("NEW_STATUS").Value = "H"
        dsINSP_PLT.Update
        
    ElseIf Trim$(BCField(14)) = "N" Then
        dsCARGO_TRACKING_ALL.Fields("CARGO_STATUS").Value = ""
    End If
    dsCARGO_TRACKING_ALL.Fields("MARK").Value = "AT POW"
    dsCARGO_TRACKING_ALL.Update
    Mark_Flag = "AT POW"
    Call InsertCTE(Trim(BCField(3)), Trim(BCField(7)), Barcode, Mark_Flag)
 
    
    'ADD INTO CARGO ACTIVITY TABLE
    dsCARGO_ACTIVITY_ALL.AddNew
    dsCARGO_ACTIVITY_ALL.Fields("PALLET_ID").Value = Barcode
    dsCARGO_ACTIVITY_ALL.Fields("ARRIVAL_NUM").Value = Trim(BCField(3))
    dsCARGO_ACTIVITY_ALL.Fields("QTY_CHANGE").Value = Val(BCField(5))
    dsCARGO_ACTIVITY_ALL.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
    dsCARGO_ACTIVITY_ALL.Fields("CUSTOMER_ID").Value = Val(BCField(7))
    dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 1
    dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = Val(BCField(5))
    dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = 1
    dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
        
    dsCARGO_ACTIVITY_ALL.Update
    
    'If Trim$(BCField(15)) <> "" Then
     '   dsCARGO_ACTIVITY_ALL.AddNew
     '   dsCARGO_ACTIVITY_ALL.Fields("PALLET_ID").Value = Barcode
     '   dsCARGO_ACTIVITY_ALL.Fields("ARRIVAL_NUM").Value = Trim(BCField(3))
     '   dsCARGO_ACTIVITY_ALL.Fields("QTY_CHANGE").Value = 0
     '   dsCARGO_ACTIVITY_ALL.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
     '   dsCARGO_ACTIVITY_ALL.Fields("CUSTOMER_ID").Value = Val(BCField(7))
     '   dsCARGO_ACTIVITY_ALL.Fields("SERVICE_CODE").Value = 14
     '   dsCARGO_ACTIVITY_ALL.Fields("QTY_LEFT").Value = Val(BCField(5))
     '   dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_NUM").Value = 2
     '   dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
     '   dsCARGO_ACTIVITY_ALL.Fields("ACTIVITY_DESCRIPTION").Value = "HOSPITAL-" & Trim$(BCField(15))
     '   dsCARGO_ACTIVITY_ALL.Update
   ' End If
 
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        Call SendToOutLog(Reader_Addr, "Y", EZC_Record, BCField(10), BCField(2), Val(BCField(8)), Barcode, Trim$(BCField(3)), Val(BCField(7)))
        'PASS LOG INFORMATION (PASS SRX, CHECKER, BARCODE, SUPERVISOR, LR#, AND CUSTOEMR ID
        'Call LOG_ACTIVITY(BCField(1), BCField(2), Barcode, BCField(10), BCField(3), BCField(7))
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y)]"
        Exit Sub
   
    Else
        OraDatabase.Close
        Call Initialize_DB
        'OraSession.Rollback
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",XOracle Error, Just Try Again)]"
        Exit Sub
    End If
    
    Exit Sub
    
ErrHandler:

    If Err.Number <> 0 Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        
        Exit Sub
    End If

End Sub
Private Sub SendToInLog()
'adds entries to SCAN_LOG table
Dim strDummyNo As String
Dim strSql As String
Dim strScannerIp As String
Dim strTransCode As String
Dim strTransArgs As String
Dim strSuperId As String
Dim strCheckerId As String
Dim strPalletNo As String
Dim strComm As String
Dim strOwner As String
Dim strLR As String
Dim dsSCANLOGCHECK As Object

Select Case BCField(1)
    Case "SRX"
        strTransCode = BCField(1)
        strPalletNo = BCField(4)
        strSuperId = BCField(10)
        strCheckerId = BCField(2)
    Case "UCT"
        strTransCode = BCField(1)
        strPalletNo = BCField(2)
        strSuperId = BCField(14)
        strCheckerId = BCField(15)
    Case "RLC"
        strTransCode = BCField(1)
        strPalletNo = BCField(2)
        strSuperId = BCField(6)
        strCheckerId = BCField(7)
    Case "SOF"
        strTransCode = BCField(1)
        strPalletNo = BCField(5)
        strSuperId = BCField(6)
        strCheckerId = BCField(2)
    Case "RT"
        strTransCode = BCField(1)
        strPalletNo = BCField(5)
        strSuperId = BCField(7)
        strCheckerId = BCField(2)
    Case "TRC"
        strTransCode = BCField(1)
        strPalletNo = BCField(5)
        strSuperId = BCField(11)
        strCheckerId = BCField(2)
    Case "TRX" 'FIELDS:  1.TRX, 2.USER,3.CUSTOMER COMING FROM, 4.ORDER #, 5.BARCODE,6.QTY RCVD,7.QTY DMGD,8.ORIGINAL CUST,9.COMMODITY,10.LOCATION,11.Size,12.Packing House,13.SUPERVISOR,14.Qty Expected,15.Status
        strTransCode = BCField(1)
        strPalletNo = BCField(5)
        strSuperId = BCField(13)
        strCheckerId = BCField(2)
End Select

strTransCode = BCField(1)
strScannerIp = Reader_Addr
strTransArgs = EZC_Record
      
strSql = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & strPalletNo & "'"
Set dsSCANLOGCHECK = OraDatabase.DbCreateDynaset(strSql, 0&)
If dsSCANLOGCHECK.RecordCount = 0 Then
    strComm = "NOVALUE"
    strOwner = "NOVALUE"
    strLR = "NOVALUE"
Else
    strComm = dsSCANLOGCHECK.Fields("COMMODITY_CODE").Value
    strOwner = dsSCANLOGCHECK.Fields("RECEIVER_ID").Value
    strLR = dsSCANLOGCHECK.Fields("ARRIVAL_NUM").Value
End If
      
strSql = "INSERT INTO SCAN_LOG (ACTIVITY_DATE, SCANNER_IP, FROM_SCANNER, TRANSACTION_CODE, TRANSACTION_ARGS, SUPERVISOR_ID, CHECKER_ID, COMMODITY_TYPE, PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) "
strSql = strSql & "VALUES (SYSDATE, '" & strScannerIp & "', 'Y', '" & strTransCode & "', '" & strTransArgs & "', '"
strSql = strSql & strSuperId & "', '" & strCheckerId & "', '" & strComm & "', '" & strPalletNo & "', '" & strOwner & "', '" & strLR & "')"

OraDatabase.ExecuteSQL (strSql)

End Sub
Private Sub SendToOutLog(strAddr As String, strCode As String, strRest As String, strSuper As String, strChecker As String, strComm As String, strPalletOrDummy As String, strLR As String, strOwner As String)
Dim strSql As String

strSql = "INSERT INTO SCAN_LOG (ACTIVITY_DATE, SCANNER_IP, FROM_SCANNER, TRANSACTION_CODE, TRANSACTION_ARGS, SUPERVISOR_ID, CHECKER_ID, COMMODITY_TYPE, PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) "
strSql = strSql & "VALUES (SYSDATE, '" & strAddr & "', 'N', '" & strCode & "', '" & strRest & "', '"
strSql = strSql & strSuper & "', '" & strChecker & "', '" & strComm & "', '" & strPalletOrDummy & "', '" & strOwner & "', '" & strLR & "')"

OraDatabase.ExecuteSQL (strSql)

End Sub
Sub UpdateCT()
On Error GoTo ErrHandler:

Dim arrTranstext() As String
Dim bc As String
Dim sqlStmt As String
Dim retVal As String
Dim qty_rcvd As String
Dim qty_dmgd As String
Dim plt_loc As String
Dim is_hospital As String
Dim comm As String
Dim cust As String
Dim variety As String
Dim size As String
Dim UnloadLRNum As String
Dim status_flag As Variant
Dim pck_hse As String
Dim Source As String
Dim CUSTOMER As String
Dim commodity As String
Dim isRcvd As Integer
Dim dsCLM As Object
Dim Weight As String
Dim Mark_Flag As String
Dim gsSqlStmt As String
Dim dsINSP_LOG As Object
Dim inSqlStmt As String
Dim dsINSP_PLT As Object
Dim Trans_id As String
Dim Action_Type As String
Dim Prev_Status As String
Dim dsPERSONNEL As Object
    
    '' Step-1: Get Barcode
    arrTranstext = Split(Trim(EZC_Record), ",")
    bc = arrTranstext(1)
    CUSTOMER = arrTranstext(2)
    commodity = arrTranstext(3)
    qty_rcvd = arrTranstext(4)
    qty_dmgd = arrTranstext(5)
    plt_loc = arrTranstext(6)
    variety = arrTranstext(7)
    size = arrTranstext(8)
    pck_hse = arrTranstext(9)
    is_hospital = arrTranstext(10)
    Source = arrTranstext(11)
    UnloadLRNum = arrTranstext(12)
    cust = Val(CUSTOMER)
    comm = Val(commodity)
    
    
'    gsSqlStmt = "SELECT DISTINCT(DC), WEIGHT FROM CLM_SIZEMAP WHERE DC = '" & Val(BCField(12)) & "'"
'    Set dsCLM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & Trim$(BCField(15)) & "'"
    Set dsPERSONNEL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
'    If (dsCLM.RecordCount = 1) Then
'        Weight = dsCLM.Fields("WEIGHT").Value
'    End If
    
    '' Step-2: Query the Database
    '' A.QTY_RECEIVED, A.QTY_DAMAGED, A.WAREHOUSE_LOCATION, A.DATE_RECEIVED
    sqlStmt = "SELECT A.*" & _
            " FROM CARGO_TRACKING A" & _
            " WHERE A.ARRIVAL_NUM='" & UnloadLRNum & "'" & _
            " AND A.PALLET_ID='" & bc & "'"
    
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    
    If OraDatabase.LastServerErr > 0 Then
        'ORACLE ERROR
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "DB SERVER ERROR" & ")]"
        Exit Sub
    
    ElseIf (dsCARGO_TRACKING.RecordCount = 0) Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "NO PLT FOUND" & ")]"
        Exit Sub
        
    ElseIf (dsCARGO_TRACKING.RecordCount = 1) Then
        ''retVal = "1000,25,WING C3"
        
        '' step-1
        '' Check for 'NULL' !!!! display '0' if 'NULL' (QTY_RECEIVED, QTY_DAMAGED)
        '' for location display 'NONE' if 'NULL'
        ''

        
        '' QTY_RECEIVED
        If (IsNull(qty_rcvd)) Then
            qty_rcvd = "0"
        End If
        
        '' QTY_DAMAGED
        If (qty_dmgd = "") Then
            qty_dmgd = "0"
        End If
        
        'Location
        If (plt_loc = "None") Then
            plt_loc = ""
        End If
        
        'Variety
        If (variety = "None") Then
            variety = ""
        End If
        
        'Size
        If (size = "") Then
            size = "0"
        End If
        
        'Source
        If (IsNull(Source)) Then
            Source = Null
        End If
        
'        '' HOSPITAL
        If (is_hospital = "Y") Then
            If dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "R" Then
                status_flag = "B"
            ElseIf dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "B" Then
                status_flag = "B"
            Else
                status_flag = "H"
            End If
        ElseIf (is_hospital = "N") Then
            If dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "R" Then
                status_flag = "R"
            ElseIf dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "B" Then
                status_flag = "R"
            Else
                status_flag = ""
            End If
        End If
        
        gsSqlStmt = "SELECT * FROM DC_INSPECTION_LOG ORDER BY TRANSACTION_ID"
        Set dsINSP_LOG = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        inSqlStmt = "SELECT * FROM DC_INSPECTED_PALLET"
        Set dsINSP_PLT = OraDatabase.DbCreateDynaset(inSqlStmt, 0&)
        
        If (dsINSP_LOG.RecordCount > 0) Then
            dsINSP_LOG.MoveLast
            Trans_id = dsINSP_LOG.Fields("TRANSACTION_ID").Value + 1
        Else
            Trans_id = 1
        End If
        
        If (status_flag = "") Then
            Action_Type = "NULL"
        ElseIf (status_flag = "R") Then
            Action_Type = "REGRADE"
        ElseIf (status_flag = "H") Then
            Action_Type = "HOSPITAL"
        ElseIf (status_flag = "B") Then
            Action_Type = "BOTH"
        End If
        
        If Not IsNull(dsCARGO_TRACKING.Fields("CARGO_STATUS")) Then
            Prev_Status = dsCARGO_TRACKING.Fields("CARGO_STATUS").Value
        Else
            Prev_Status = ""
        End If
        '' step-2
        '' update DATE_RECEIVED in CT
        OraSession.BeginTrans
        dsCARGO_TRACKING.Edit
        If IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value) Then
            dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value = Format(Now, "mm/dd/yyyy HH:MM:ss AMPM")
            isRcvd = 1
        Else
            isRcvd = 0
        End If
        'dsCARGO_TRACKING.Fields("RECEIVER_ID").Value = cust
        dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value = comm
        dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value = qty_rcvd
        dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = qty_rcvd
        dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value = qty_dmgd
        dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value = plt_loc
        dsCARGO_TRACKING.Fields("VARIETY").Value = variety
        dsCARGO_TRACKING.Fields("CARGO_SIZE").Value = size
        dsCARGO_TRACKING.Fields("EXPORTER_CODE").Value = pck_hse
        dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = status_flag
        If (isRcvd = 1) And (status_flag <> "") Then
            dsINSP_LOG.AddNew
            dsINSP_LOG.Fields("TRANSACTION_ID").Value = Trans_id
            dsINSP_LOG.Fields("INSPECTOR_ID").Value = arrTranstext(14)
            dsINSP_LOG.Fields("INSPECTION_DATETIME").Value = Format(Now, "MM/DD/YYYY HH:MM:SS AMPM")
            dsINSP_LOG.Fields("ACTION_TYPE").Value = Action_Type
            dsINSP_LOG.Fields("NUM_PALLETS").Value = "1"
            dsINSP_LOG.Fields("ARRIVAL_NUM").Value = UnloadLRNum
            dsINSP_LOG.Update
    
            dsINSP_PLT.AddNew
            dsINSP_PLT.Fields("PALLET_ID").Value = dsCARGO_TRACKING.Fields("PALLET_ID").Value
            dsINSP_PLT.Fields("TRANSACTION_ID").Value = Trans_id
            dsINSP_PLT.Fields("PREVIOUS_STATUS").Value = Prev_Status
            dsINSP_PLT.Fields("NEW_STATUS").Value = status_flag
            dsINSP_PLT.Update
        ElseIf (isRcvd = 0) Then
            If (Prev_Status <> status_flag) Then
                dsINSP_LOG.AddNew
                dsINSP_LOG.Fields("TRANSACTION_ID").Value = Trans_id
                dsINSP_LOG.Fields("INSPECTOR_ID").Value = arrTranstext(14)
                dsINSP_LOG.Fields("INSPECTION_DATETIME").Value = Format(Now, "MM/DD/YYYY HH:MM:SS AMPM")
                dsINSP_LOG.Fields("ACTION_TYPE").Value = Action_Type
                dsINSP_LOG.Fields("NUM_PALLETS").Value = "1"
                dsINSP_LOG.Fields("ARRIVAL_NUM").Value = UnloadLRNum
                dsINSP_LOG.Update
        
                dsINSP_PLT.AddNew
                dsINSP_PLT.Fields("PALLET_ID").Value = dsCARGO_TRACKING.Fields("PALLET_ID").Value
                dsINSP_PLT.Fields("TRANSACTION_ID").Value = Trans_id
                dsINSP_PLT.Fields("PREVIOUS_STATUS").Value = Prev_Status
                dsINSP_PLT.Fields("NEW_STATUS").Value = status_flag
                dsINSP_PLT.Update
            Else
                'Do Nothing: the pallet's status is not changing, so do not need to insert
            End If
        End If
        dsCARGO_TRACKING.Fields("SOURCE_NOTE").Value = Source
'        dsCARGO_TRACKING.Fields("WEIGHT").Value = Weight
        If IsNull(dsCARGO_TRACKING.Fields("MARK")) Then
            dsCARGO_TRACKING.Fields("MARK").Value = "AT POW"
        ElseIf (dsCARGO_TRACKING.Fields("MARK").Value = "MANIFESTED") Then
            dsCARGO_TRACKING.Fields("MARK").Value = "AT POW"
        End If
        Mark_Flag = "AT POW"
        dsCARGO_TRACKING.Update
        
        'Get ready for update of CARGO_ACTIVITY
        gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(bc) & "'"
        If Trim$(cust) <> "" Then
            gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & cust
        Else
            gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & dsCARGO_TRACKING.Fields("RECEIVER_ID").Value
        End If
        gsSqlStmt = gsSqlStmt & "AND ARRIVAL_NUM = '" & Trim$(UnloadLRNum) & "'"
        gsSqlStmt = gsSqlStmt & "ORDER BY ACTIVITY_NUM"
        Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        'GET MAX ACTIVITY NUM
        gsSqlStmt = "SELECT MAX(ACTIVITY_NUM) MAX_NUM FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(bc) & "'"
        If Trim$(cust) <> "" Then
            gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & cust
        Else
            gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & dsCARGO_TRACKING.Fields("RECEIVER_ID").Value
        End If
        gsSqlStmt = gsSqlStmt & "AND ARRIVAL_NUM = '" & Trim$(UnloadLRNum) & "'"
        gsSqlStmt = gsSqlStmt & "ORDER BY ACTIVITY_NUM"
        Set dsACTIVITY_MAX_NUM = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        If dsCARGO_ACTIVITY.RecordCount > 0 Then 'HAS CARGO ACTIIVTY RECORDS. UPDATE QTY LEFT, QTY CHANGE(ON THE SERVICE CODE = 1) AND CUSTOMER ID IF NEED
            If (qty_rcvd <> "") Then 'IF CHANGE QTY RCVD, THEN NEED CHANGE THE QTY_LEFT
                While Not dsCARGO_ACTIVITY.EOF
                    dsCARGO_ACTIVITY.Edit
                    If cust <> "" Then 'CHANGE CUSTOMER ID IF CHECKER WANTS TO CHANGE THE CUSTOMER ID
                        dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value = cust
                    End If
                    'CHANGE THE QTY CHANGE ON THE SERVICE CODE = 1 WHICH IS SHIP RECEIVING
                    If dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 1 Then
                        dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value = qty_rcvd
                    End If
                    dsCARGO_ACTIVITY.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
                    'CHANGE EACH ITEM'S QTY LEFT
                    dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value = dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
                    dsCARGO_ACTIVITY.Update
                    dsCARGO_ACTIVITY.MoveNext
                Wend
            End If
        Else
            'ADD NEW
            dsCARGO_ACTIVITY.AddNew
            dsCARGO_ACTIVITY.Fields("PALLET_ID").Value = bc
            dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value = UnloadLRNum
            'USE QTY RCVD FOR QTY CHANGE IF FIELD 5 IS NULL(NO CHANGE)
            If Trim$(qty_rcvd) <> "" Then
                dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value = qty_rcvd
                dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value = qty_rcvd
            Else
                dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value = dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
                dsCARGO_ACTIVITY.Fields("QTY_LEFT").Value = dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
            End If
            dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value = Format$(Now, "mm/dd/yyyy HH:MM:SS")
            'USER OLD CUSTOMER ID IF NO CHANGE
            If Trim$(cust) <> "" Then
                dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value = cust
            Else
                dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value = dsCARGO_TRACKING.Fields("RECEIVER_ID").Value
            End If
            dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 1
            dsCARGO_ACTIVITY.Fields("ACTIVITY_NUM").Value = 1
            dsCARGO_ACTIVITY.Fields("ACTIVITY_ID").Value = dsPERSONNEL.Fields("EMPLOYEE_ID").Value
            dsCARGO_ACTIVITY.Update
        End If
        OraSession.CommitTrans
        Call InsertCTE(UnloadLRNum, cust, bc, Mark_Flag)
        Call SendToOutLog(Reader_Addr, BCField(1), "Y" & isRcvd, BCField(14), BCField(15), comm, bc, UnloadLRNum, cust)
        '' step-3
        '' Emit message
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & isRcvd & ")]"
        Exit Sub
    
    ElseIf (dsCARGO_TRACKING.RecordCount > 1) Then
        Dim msg As String
        msg = "THIS IS DUPLICATE CONTACT OFFICE"
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & msg & ")]"
        Exit Sub
    End If


ErrHandler:

    If Err.Number <> 0 Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        
        Exit Sub
    End If

End Sub
Sub InsertCTE(Arrival_Num As String, Receiver_id As String, Pallet_ID As String, Mark_Flag As String)
On Error GoTo ErrHandler

Dim dsCARGO_TRACKING_EXT As Object
Dim sqlStmt As String

sqlStmt = "SELECT * FROM CARGO_TRACKING_EXT WHERE ARRIVAL_NUM = '" & Arrival_Num & "' "
sqlStmt = sqlStmt & "AND RECEIVER_ID = '" & Receiver_id & "' "
sqlStmt = sqlStmt & "AND PALLET_ID = '" & Pallet_ID & "'"
Set dsCARGO_TRACKING_EXT = OraDatabase.DbCreateDynaset(sqlStmt, 0&)

If (dsCARGO_TRACKING_EXT.RecordCount = 0) Then
    sqlStmt = "INSERT INTO CARGO_TRACKING_EXT(ARRIVAL_NUM, PALLET_ID, RECEIVER_ID) "
    sqlStmt = sqlStmt & "VALUES('" & Arrival_Num & "', '" & Pallet_ID & "', '" & Receiver_id & "')"
    
    'OraSession.BeginTrans
    OraDatabase.ExecuteSQL (sqlStmt)
    'OraSession.CommitTrans
ElseIf (dsCARGO_TRACKING_EXT.RecordCount = 1) Then
'    OraSession.BeginTrans
    dsCARGO_TRACKING_EXT.Edit
    If (Mark_Flag = "AT POW") Then
        dsCARGO_TRACKING_EXT.Fields("SHIPOUTTIME").Value = ""
    ElseIf (Mark_Flag = "SHIPPED") Then
        dsCARGO_TRACKING_EXT.Fields("SHIPOUTTIME").Value = Format(Now, "mm/dd/yyyy HH:MM:ss AMPM")
    Else
        dsCARGO_TRACKING_EXT.Fields("SHIPOUTTIME").Value = ""
    End If
    dsCARGO_TRACKING_EXT.Update
'    OraSession.CommitTrans
End If
ErrHandler:

    If Err.Number <> 0 Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        
        Exit Sub
    End If

End Sub
Sub UpdateHosp()
'From Scanner
'UDH|pallet_id|arrival_num|pltstatus|location|LoginID|Cust

On Error GoTo ErrHandler:

Dim arrTranstext() As String
Dim bc As String
Dim lr_num As String
Dim sqlStmt As String
Dim status_flag As Variant
Dim whs_loc As String
Dim gsSqlStmt As String
Dim dsINSP_LOG As Object
Dim inSqlStmt As String
Dim dsINSP_PLT As Object
Dim Trans_id As String
Dim Action_Type As String
Dim cust As String

    '' Step-1: Get Barcode
    arrTranstext = Split(Trim(EZC_Record), ",")
    bc = arrTranstext(1)
    cust = Val(arrTranstext(6))
    
    lr_num = Val(arrTranstext(2))
'    If (cust = "439") Then
'        lr_num = Left(lr_num, 4)
'    End If
    
    status_flag = arrTranstext(3)
    
    If Len(status_flag) = 0 Then
        status_flag = Null
    Else
        status_flag = UCase(status_flag)
    End If
    
    
'    whs_loc = UCase(arrTranstext(4))
'    If (Len(whs_loc) = 0) Then
'        PauseForResponse
'        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "NO LOC SPECIFIED" & ")]"
'        Exit Sub
'    End If
    
    
    '' Step-2: Query the Database
    '' A.QTY_RECEIVED, A.QTY_DAMAGED, A.WAREHOUSE_LOCATION, A.DATE_RECEIVED
'    sqlStmt = "SELECT A.*" & _
'            " FROM CARGO_TRACKING A" & _
'            " WHERE A.ARRIVAL_NUM='" & lr_num & "'" & _
'            " AND A.RECEIVER_ID=439 AND A.COMMODITY_CODE=5602" & _
'            " AND A.PALLET_ID='" & bc & "'"

    '' Remove COMMODITY_CODE as one of the filters
    '' 12/26/2006 pwu
    sqlStmt = "SELECT A.*" & _
        " FROM CARGO_TRACKING A" & _
        " WHERE A.ARRIVAL_NUM='" & lr_num & "'" & _
        " AND A.PALLET_ID='" & bc & "'"
        '" AND A.RECEIVER_ID=439" & _

    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    
    If OraDatabase.LastServerErr > 0 Then
        'ORACLE ERROR
        OraDatabase.Close
        Call Initialize_DB
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "DB SERVER ERROR" & ")]"
        Exit Sub
    
    ElseIf (dsCARGO_TRACKING.RecordCount = 0) Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "NO PLT FOUND" & ")]"
        Exit Sub
        
    ElseIf (dsCARGO_TRACKING.RecordCount = 1) Then
    
        gsSqlStmt = "SELECT * FROM DC_INSPECTION_LOG ORDER BY TRANSACTION_ID"
        Set dsINSP_LOG = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        inSqlStmt = "SELECT * FROM DC_INSPECTED_PALLET"
        Set dsINSP_PLT = OraDatabase.DbCreateDynaset(inSqlStmt, 0&)
        
        If (dsINSP_LOG.RecordCount > 0) Then
            dsINSP_LOG.MoveLast
            Trans_id = dsINSP_LOG.Fields("TRANSACTION_ID").Value + 1
        Else
            Trans_id = 1
        End If
        
'        If (is_hospital = "Y") Then
'            If dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "R" Then
'                status_flag = "B"
'            ElseIf dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "B" Then
'                status_flag = "B"
'            Else
'                status_flag = "H"
'            End If
'        ElseIf (is_hospital = "N") Then
'            If dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "R" Then
'                status_flag = "R"
'            ElseIf dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "B" Then
'                status_flag = "R"
'            Else
'                status_flag = ""
'            End If
'        End If
        
        If (IsNull(status_flag)) Then
            Action_Type = "NULL"
        ElseIf (status_flag = "R") Then
            Action_Type = "REGRADE"
        ElseIf (status_flag = "H") Then
            Action_Type = "HOSPITAL"
        ElseIf (status_flag = "B") Then
            Action_Type = "BOTH"
        End If
        
        OraSession.BeginTrans
        dsINSP_LOG.AddNew
        dsINSP_LOG.Fields("TRANSACTION_ID").Value = Trans_id
        dsINSP_LOG.Fields("INSPECTOR_ID").Value = arrTranstext(5)
        dsINSP_LOG.Fields("INSPECTION_DATETIME").Value = Format(Now, "MM/DD/YYYY HH:MM:SS AMPM")
        dsINSP_LOG.Fields("ACTION_TYPE").Value = Action_Type
        dsINSP_LOG.Fields("NUM_PALLETS").Value = "1"
        dsINSP_LOG.Fields("ARRIVAL_NUM").Value = lr_num
        dsINSP_LOG.Update
        
        dsINSP_PLT.AddNew
        dsINSP_PLT.Fields("PALLET_ID").Value = bc
        dsINSP_PLT.Fields("TRANSACTION_ID").Value = Trans_id
        dsINSP_PLT.Fields("PREVIOUS_STATUS").Value = dsCARGO_TRACKING.Fields("CARGO_STATUS").Value
        dsINSP_PLT.Fields("NEW_STATUS").Value = status_flag
        dsINSP_PLT.Update

        '' step-2
        '' update DATE_RECEIVED in CT
        'OraSession.BeginTrans
        dsCARGO_TRACKING.Edit
        If IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value) Then
            dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value = Format(Now, "mm/dd/yyyy HH:MM:ss AMPM")
        End If
        If IsNull(dsCARGO_TRACKING.Fields("CARGO_STATUS")) Then
            dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = status_flag
        ElseIf (IsNull(status_flag)) Then
            If (dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "B") Then
                dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "R"
            ElseIf (dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "R") Then
                'Do Nothing
            ElseIf (dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "H") Then
                dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = ""
            End If
        ElseIf (dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "R") Then
            dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "B"
        ElseIf (dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "B") Then
            dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "B"
        ElseIf (dsCARGO_TRACKING.Fields("CARGO_STATUS").Value = "H") Then
            'Do Nothing
        End If
        dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value = whs_loc
        dsCARGO_TRACKING.Update
        OraSession.CommitTrans
        
        '' step-3
        '' Emit message
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",Y" & "PLT STATUS UPDATED" & ")]"
        Exit Sub
    
    ElseIf (dsCARGO_TRACKING.RecordCount > 1) Then
        Dim msg As String
        msg = "THIS IS DUPLICATE CONTACT OFFICE"
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & msg & ")]"
        Exit Sub
    End If


ErrHandler:

    If Err.Number <> 0 Then
        PauseForResponse
        tbxDDE_Msg.LinkExecute "[Xmit(" & Reader_Addr & ",X" & "ERR:" & Err.Description & ")]"
        
        Exit Sub
    End If



End Sub



