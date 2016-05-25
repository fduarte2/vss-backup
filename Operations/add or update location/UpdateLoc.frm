VERSION 5.00
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "COMDLG32.OCX"
Begin VB.Form frmUpdate 
   BackColor       =   &H00C0C000&
   Caption         =   "Update Warehouse Location-Pandol"
   ClientHeight    =   2220
   ClientLeft      =   2475
   ClientTop       =   2160
   ClientWidth     =   7065
   LinkTopic       =   "Form1"
   ScaleHeight     =   2220
   ScaleWidth      =   7065
   Begin VB.CommandButton cmdUpdate 
      Caption         =   "&Update Location"
      Height          =   315
      Left            =   2400
      TabIndex        =   3
      Top             =   1320
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
      MaxLength       =   60
      TabIndex        =   1
      Top             =   270
      Width           =   4965
   End
   Begin MSComDlg.CommonDialog cdlImportFile 
      Left            =   6360
      Top             =   630
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
      CancelError     =   -1  'True
      DefaultExt      =   ".dbf"
      DialogTitle     =   "Select Import File"
      Filter          =   "Database Files|*.dbf|All Files|*.*"
      FilterIndex     =   1
   End
   Begin VB.Label lblStatus 
      BackColor       =   &H00C0C0C0&
      BorderStyle     =   1  'Fixed Single
      Height          =   285
      Left            =   0
      TabIndex        =   4
      Top             =   1920
      Width           =   7305
   End
   Begin VB.Line Line1 
      BorderColor     =   &H0000C0C0&
      BorderWidth     =   2
      X1              =   120
      X2              =   6960
      Y1              =   1800
      Y2              =   1800
   End
   Begin VB.Label lblImportFile 
      BackColor       =   &H00C0C000&
      Caption         =   "Import File:"
      Height          =   225
      Left            =   150
      TabIndex        =   0
      Top             =   300
      Width           =   1005
   End
End
Attribute VB_Name = "frmUpdate"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdBrowse_Click()
    On Error Resume Next
    cdlImportFile.Action = 1
    If Err = 0 Then
        txtImportFile = cdlImportFile.FileName
    End If
    On Error GoTo 0
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
    Dim iResponse As Integer
    Dim iSuccess As Integer
   
    
    'Reset oracle error if any
    OraDatabase.LastServerErrReset
    
    On Error Resume Next
    
    'Check for emply import file
    If Trim$(txtImportFile.Text) = "" Then
        MsgBox "Import File can not be blank.", vbInformation, "Invalid Import File"
        On Error GoTo 0
        txtImportFile.SetFocus
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
    gsMessage = gsMessage & gsNL & "Are you sure you want to continue with the Update?"
    If sMissingFields <> "" Then
        MsgBox "The following fields are missing when update file :" & gsNL & sMissingFields, vbInformation, "Invalid Import File"
        On Error GoTo 0
        Exit Sub
    End If
    Do While Not rsImport.EOF
        gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM= '" & rsImport.Fields("PLT_ID").Value & "'"
        Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If dsCARGO_TRACKING.RecordCount > 0 Then
            OraSession.BeginTrans
            dsCARGO_TRACKING.Edit
            dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION") = rsImport.Fields("LOC").Value
            dsCARGO_TRACKING.Update
            
            If OraDatabase.LastServerErr = 0 Then
                OraSession.CommitTrans
            Else
                lblStatus.Caption = "Error has occured. Changes have been rolled back."
                OraSession.Rollback
                Exit Sub
            End If
        End If
        rsImport.MoveNext
    Loop
    txtImportFile.Text = ""
    txtImportFile.SetFocus
    
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, Me.Caption
    lblStatus.Caption = "Error Occured."
    Screen.MousePointer = vbDefault
    On Error GoTo 0
    
End Sub

Private Sub Form_Load()
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
    Set OraDatabase = OraSession.OpenDatabase("TEST", "SAG_OWNER/SAG", 0&)
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    Screen.MousePointer = vbDefault
  
    
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

Private Sub txtImportFile_Change()

End Sub
