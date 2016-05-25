VERSION 5.00
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "COMDLG32.OCX"
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.1#0"; "MSCOMCTL.OCX"
Begin VB.Form frmImpManifest 
   AutoRedraw      =   -1  'True
   Caption         =   "IMPORT MANIFEST IN BNI"
   ClientHeight    =   3465
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   7065
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
   LockControls    =   -1  'True
   ScaleHeight     =   3465
   ScaleWidth      =   7065
   StartUpPosition =   2  'CenterScreen
   Begin VB.OptionButton ReturnFile 
      Caption         =   "Returned Cargo"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   7200
      TabIndex        =   11
      Top             =   840
      Visible         =   0   'False
      Width           =   2055
   End
   Begin VB.OptionButton NewFile 
      Caption         =   "New Cargo"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   7200
      TabIndex        =   10
      Top             =   240
      Visible         =   0   'False
      Width           =   1935
   End
   Begin VB.CommandButton CmdExit 
      Cancel          =   -1  'True
      Caption         =   "E&XIT"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3420
      TabIndex        =   9
      Top             =   2640
      Width           =   1215
   End
   Begin VB.CheckBox chkQty2 
      Height          =   255
      Left            =   1920
      TabIndex        =   7
      Top             =   1680
      Visible         =   0   'False
      Width           =   375
   End
   Begin MSComctlLib.StatusBar StatusBar1 
      Align           =   2  'Align Bottom
      Height          =   330
      Left            =   0
      TabIndex        =   6
      Top             =   3135
      Width           =   7065
      _ExtentX        =   12462
      _ExtentY        =   582
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
         EndProperty
      EndProperty
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin MSComDlg.CommonDialog cdlImportFile 
      Left            =   5400
      Top             =   1920
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
      DialogTitle     =   "IMPORT MANIFEST FILE"
   End
   Begin VB.CommandButton cmdImport 
      Caption         =   "&IMPORT"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   2100
      TabIndex        =   5
      Top             =   2640
      Width           =   1275
   End
   Begin VB.CommandButton cmdBrowse 
      Caption         =   "&Browse"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   315
      Left            =   6060
      TabIndex        =   4
      Top             =   1200
      Width           =   795
   End
   Begin VB.TextBox txtFile 
      Appearance      =   0  'Flat
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   330
      Left            =   1920
      TabIndex        =   3
      Top             =   1192
      Width           =   4095
   End
   Begin VB.TextBox txtLrNum 
      Appearance      =   0  'Flat
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   330
      Left            =   1920
      TabIndex        =   2
      Top             =   720
      Width           =   1575
   End
   Begin VB.Label Label4 
      Caption         =   "NOTICE:  Mixing new and returned cargo in the same import file will cause inconsistencies with Storage Bills."
      ForeColor       =   &H00008000&
      Height          =   1575
      Left            =   7080
      TabIndex        =   12
      Top             =   1440
      Visible         =   0   'False
      Width           =   2295
   End
   Begin VB.Label Label3 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "QTY2 REQUIRED  :"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   240
      Left            =   120
      TabIndex        =   8
      Top             =   1680
      Visible         =   0   'False
      Width           =   1725
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "MANIFEST FILE  :"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   240
      Left            =   225
      TabIndex        =   1
      Top             =   1245
      Width           =   1650
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "VESSEL  :"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   240
      Left            =   900
      TabIndex        =   0
      Top             =   720
      Width           =   945
   End
End
Attribute VB_Name = "frmImpManifest"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Dim SqlStmt As String
Dim iRec As Integer
Dim OraSession As Object
Dim OraDatabase As Object
Dim dsLOTNUM As Object
Dim dsVESSEL_PROFILE As Object
Dim dsSERVICE_CATEGORY As Object
Dim dsCARGO_MANIFEST As Object
Dim dsVOYAGE_CARGO As Object
Dim dsCARGO_TRACKING As Object
Dim dsFREE_TIME As Object
Dim dsSHORT_TERM_DATA As Object
Dim dsTEMP As Object
Dim sText As TextStream
Dim sFys As New FileSystemObject
Dim sLine As String

Private Sub cmdExit_Click()
    Unload Me
End Sub

Sub Change()
    Dim iExporterSuppilerId As Integer
    Dim iLrNum As Long
    Dim iCustId As Long
    Dim sBOL As String
    Dim sMark As String
    Dim iComm As Long
    Dim iQty As Double
    Dim splitline() As String
    
    ' added Adam Walter, june 2008.  see comments below.
    'Dim bDuplicate As Boolean
    'Dim bBadcust As Boolean
    'Dim bBadcomm As Boolean
    'Dim bMissingData As Boolean
    Dim ibadRow As Integer
    Dim sErrorMsg As String
    
    'bDuplicate = False
    'bBadcust = False
    'bBadcomm = False
    'bMissingData = False
    sErrorMsg = ""
    
    Dim sUnt As String
    Dim iQty2 As Double
    Dim sUnt2 As String
    Dim sStatus As String
    Dim iWt As Double
    Dim sWtUnt As String
    Dim sDate As String
    Dim sImpex As String
    Dim sRcvdInv As String
    Dim dContainerNum As Double
    Dim sLoc As String
    Dim lFreeDays As Long
    Dim sContainerNum As String
    Dim ipos As Long
    Dim ipos1 As Long
    Dim iCount As Long
    
    iCount = 0
    
    SqlStmt = "select * from vessel_profile where lr_num='" & (txtLrNum) & "'"
    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RecordCount = 0 Then
        MsgBox "First enter the Vessel Information", vbInformation, "VESSEL"
        Exit Sub
    End If
    
'    gsSqlStmt = "SELECT * FROM VOYAGE WHERE LR_NUM = " & txtLrNum.Text
'    Set dsVOYAGE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    'Get free time starting date from CARGO_TRACKING, instead of VOYAGE, because it is not set  -- LFW, 8/22/05
    gsSqlStmt = "SELECT DISTINCT START_FREE_TIME FROM CARGO_TRACKING T, CARGO_MANIFEST M " & _
                "WHERE T.LOT_NUM = M.CONTAINER_NUM AND M.LR_NUM = " & txtLrNum.Text & " AND START_FREE_TIME IS NOT NULL"
    Set dsTEMP = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                    
    SqlStmt = "SELECT MIN(TO_NUMBER(CONTAINER_NUM)) INTO :A FROM CARGO_MANIFEST"
    Set dsLOTNUM = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsLOTNUM.RecordCount > 0 Then
        dContainerNum = Val(dsLOTNUM.Fields("MIN(TO_NUMBER(CONTAINER_NUM))").Value) - 1
    End If
                   
    OraSession.BeginTrans
               
    While (Not sText.AtEndOfStream) And (bDuplicate = False) And (bBadcust = False) And (bBadcomm = False) And (bMissingData = False)
        
        sLine = Trim(sText.ReadLine)
        
        If sLine <> "" Then
            splitline = Split(sLine, ",")
            
            iCustId = Trim(splitline(0))
            If EmptyField(iCustId) = True Then
                sErrorMsg = "Missing Data (column A)"
                ibadRow = iCount + 1
            End If
            sBOL = Trim(splitline(1))
            If EmptyField(sBOL) = True Then
                sErrorMsg = "Missing Data (column B)"
                ibadRow = iCount + 1
            End If
            iComm = Trim(splitline(2))
            If EmptyField(iComm) = True Then
                sErrorMsg = "Missing Data (column C)"
                ibadRow = iCount + 1
            End If
            sMark = Trim(splitline(3))
            If EmptyField(sMark) = True Then
                sErrorMsg = "Missing Data (column D)"
                ibadRow = iCount + 1
            End If
            iQty = Trim(splitline(4))
            If EmptyField(iQty) = True Then
                sErrorMsg = "Missing Data (column E)"
                ibadRow = iCount + 1
            End If
            sUnt = Trim(splitline(5))
            If EmptyField(sUnt) = True Then
                sErrorMsg = "Missing Data (column F)"
                ibadRow = iCount + 1
            End If
            sStatus = Trim(splitline(6))
            If EmptyField(sStatus) = True Then
                sErrorMsg = "Missing Data (column G)"
                ibadRow = iCount + 1
            End If
            sLoc = Trim(splitline(7))
            If EmptyField(sLoc) = True Then
                sErrorMsg = "Missing Data (column H)"
                ibadRow = iCount + 1
            End If
            iWt = Trim(splitline(8))
            If EmptyField(iWt) = True Then
                sErrorMsg = "Missing Data (column I)"
                ibadRow = iCount + 1
            End If
            sWtUnt = Trim(splitline(9))
            If EmptyField(sWtUnt) = True Then
                sErrorMsg = "Missing Data (column J)"
                ibadRow = iCount + 1
            End If
            sDate = Format(Trim(splitline(10)), "MM/DD/YYYY")
            If EmptyField(sDate) = True Then
                sErrorMsg = "Missing Data (column K)"
                ibadRow = iCount + 1
            End If
            sImpex = Trim(splitline(11))
            If EmptyField(sImpex) = True Then
                sErrorMsg = "Missing Data (column L)"
                ibadRow = iCount + 1
            End If
            sRcvdInv = Trim(splitline(12))
            If EmptyField(sRcvdInv) = True Then
                sErrorMsg = "Missing Data (column M)"
                ibadRow = iCount + 1
            End If
            
            
'            ipos = InStr(1, sLine, ",")
'            iCustId = Mid(sLine, 1, ipos - 1)
'
'            ipos1 = InStr(ipos + 1, sLine, ",")
'            sBOL = Mid(sLine, ipos + 1, ipos1 - 1 - ipos)
'
'            ipos = InStr(ipos1 + 1, sLine, ",")
'            iComm = Mid(sLine, ipos1 + 1, ipos - 1 - ipos1)
'
'            ipos1 = InStr(ipos + 1, sLine, ",")
'            sMark = Mid(sLine, ipos + 1, ipos1 - 1 - ipos)
'
'            ipos = InStr(ipos1 + 1, sLine, ",")
'            iQty = Mid(sLine, ipos1 + 1, ipos - 1 - ipos1)
'
'            ipos1 = InStr(ipos + 1, sLine, ",")
'            sUnt = Mid(sLine, ipos + 1, ipos1 - 1 - ipos)
'
'            ipos = InStr(ipos1 + 1, sLine, ",")
'            sStatus = Mid(sLine, ipos1 + 1, ipos - 1 - ipos1)
'
'            ipos1 = InStr(ipos + 1, sLine, ",")
'            sLoc = Mid(sLine, ipos + 1, ipos1 - 1 - ipos)
'
'            ipos = InStr(ipos1 + 1, sLine, ",")
'            iWt = Mid(sLine, ipos1 + 1, ipos - 1 - ipos1)
'
'            ipos1 = InStr(ipos + 1, sLine, ",")
'            sWtUnt = Mid(sLine, ipos + 1, ipos1 - 1 - ipos)
'
'            ipos = InStr(ipos1 + 1, sLine, ",")
'            sDate = Mid(sLine, ipos1 + 1, ipos - 1 - ipos1)
'
'            ipos1 = InStr(ipos + 1, sLine, ",")
'            sImpex = Mid(sLine, ipos + 1, ipos1 - 1 - ipos)
'
'            ipos = InStr(ipos1 + 1, sLine, ",")
'            iExporterSuppilerId = Mid(sLine, ipos1 + 1, 1)
'            iExporterSuppilerId = Trim(iExporterSuppilerId)
'            If EmptyField(iExporterSupplierId) = True Then
'                bMissingData = True
'                ibadRow = iCount + 1
'            End If
            
            If UBound(splitline) = 14 Then
'            If chkQty2.Value = vbChecked Then
'                ipos = InStr(ipos1 + 1, sLine, ",")
'                sRcvdInv = Mid(sLine, ipos1 + 1, ipos - 1 - ipos1)
'                sRcvdInv = Trim(sRcvdInv)
'                If EmptyField(sRcvdInv) = True Then
'                    sErrorMsg = "Missing Data (column M)"
'                    ibadRow = iCount + 1
'                End If
                
'                ipos1 = InStr(ipos + 1, sLine, ",")
'                iQty2 = Mid(sLine, ipos + 1, ipos1 - 1 - ipos)
                iQty2 = Trim(splitline(13))
                If EmptyField(iQty2) = True Then
                    sErrorMsg = "Missing Data (column N)"
                    ibadRow = iCount + 1
                End If
                
'                sUnt2 = Mid(sLine, ipos1 + 1)
                sUnt2 = Trim(splitline(14))
                If EmptyField(sUnt2) = True Then
                    sErrorMsg = "Missing Data (column O)"
                    ibadRow = iCount + 1
                End If
            Else
'                sRcvdInv = Mid(sLine, ipos1 + 1, 1)
                sUnt2 = ""
            End If
            
            ' check for valid customer
            SqlStmt = "SELECT COUNT(*) THE_COUNT FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & iCustId & "'"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value = 0 Then
                ibadRow = iCount + 1
                sErrorMsg = "Invalid Customer Number (column A)"
            End If
            
            ' check for valid commodity
            SqlStmt = "SELECT COUNT(*) THE_COUNT FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '" & iComm & "'"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value = 0 Then
                ibadRow = iCount + 1
                sErrorMsg = "Invalid Commodity Number (column C)"
            End If
            
            ' check for valid units of measure
            SqlStmt = "SELECT COUNT(*) THE_COUNT FROM UNITS WHERE UOM = '" & sUnt & "'"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value = 0 Then
                ibadRow = iCount + 1
                sErrorMsg = "Invalid QTY1 Unit of Measure (column F)"
            End If
            
'            If chkQty2.Value = vbChecked Then
            If UBound(splitline) = 14 Then
                SqlStmt = "SELECT COUNT(*) THE_COUNT FROM LU_QTY2_VALIDITY_CHECK WHERE VALID_QTY2 = '" & sUnt2 & "'"
                Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value = 0 Then
                    ibadRow = iCount + 1
                    sErrorMsg = "Invalid QTY2 Unit of Measure (column O)"
                End If
                
                ' if checked, also doublecheck that certain commodity codes have certain QTY2 values
                SqlStmt = "SELECT COUNT(*) THE_COUNT FROM LU_QTY2_VALIDITY_CHECK WHERE COMMODITY_CODE = '" & iComm & "'"
                Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value = 0 Then
                    ' no restrictions on this commodity
                Else
                    SqlStmt = "SELECT COUNT(*) THE_COUNT FROM LU_QTY2_VALIDITY_CHECK WHERE COMMODITY_CODE = '" & iComm & "' AND VALID_QTY2 = '" & sUnt2 & "'"
                    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value >= 1 Then
                        ' this QTY2 is allowed
                    Else
                        ibadRow = iCount + 1
                        sErrorMsg = "QTY2 of " & sUnt2 & " NOT valid for commodity code of " & iComm
                    End If
                End If
            End If
            
'            ' Check if selected units are in the RATE table (for storage) HD7626
'            SqlStmt = "SELECT COUNT(*) THE_COUNT FROM RATE WHERE COMMODITYCODE = '" & iComm & "' " _
'                    & "AND (ARRIVALNUMBER = '" & txtLrNum.Text & "' OR ARRIVALNUMBER IS NULL) AND (CUSTOMERID = '" & iCustId & "' OR CUSTOMERID IS NULL) " _
'                    & "AND SCANNEDORUNSCANNED = 'UNSCANNED' " _
'                    & "AND (UNIT IN ('" & sUnt & "', '" & sUnt2 & "', '" & sWtUnt & "')" _
'                    & "     OR UNIT IN (SELECT SECONDARY_UOM FROM UNIT_CONVERSION WHERE PRIMARY_UOM = '" & sWtUnt & "'))"
'            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'            If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value = 0 Then
'                ibadRow = iCount + 1
'                sErrorMsg = "No Storage Rate available."
'            End If
             
            SqlStmt = "SELECT COUNT(*) THE_COUNT FROM UNITS WHERE UOM = '" & sWtUnt & "'"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value = 0 Then
                ibadRow = iCount + 1
                sErrorMsg = "Invalid WEIGHT Unit of Measure (column J)"
            End If
            
            If InStr(1, UCase(sLoc), "WING") > 0 Then
                ibadRow = iCount + 1
                sErrorMsg = "Prefix WING is being phased out.  Please do not use it to specify warehouse locations anymore."
            End If
                
                
            DoEvents
            SqlStmt = " SELECT * FROM CARGO_MANIFEST WHERE LR_NUM='" & Trim(txtLrNum) & "' AND  " _
                    & " CARGO_MARK <>'" & sMark & "' AND RECIPIENT_ID='" & iCustId & "'" _
                    & " AND CARGO_BOL='" & sBOL & "'"
                    
            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RecordCount > 0 Then
'                OraSession.RollBack
'                StatusBar1.SimpleText = "Duplicate BOL#:" & sBOL
'                Exit Sub
                ibadRow = iCount + 1
                sErrorMsg = "Duplicate BOL with different Mark"
            End If
                    
            SqlStmt = " SELECT * FROM CARGO_MANIFEST WHERE LR_NUM='" & Trim(txtLrNum) & "' AND  " _
                    & " CARGO_MARK='" & sMark & "' AND RECIPIENT_ID='" & iCustId & "'" _
                    & " AND CARGO_BOL='" & sBOL & "'"
                    
            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RecordCount > 0 Then
                ibadRow = iCount + 1
                sErrorMsg = "Duplicate BOL with Matching Mark already entered"
            End If
                        
                        
            If sErrorMsg <> "" Then ' BREAK EXECUTE if any of this is the case
                MsgBox "Cannot import file; the following reason was returned: " & vbCrLf & sErrorMsg & " on line " & ibadRow
                OraSession.RollBack
                StatusBar1.SimpleText = "Upload Aborted"
                Exit Sub
            Else
            
                'Add new data
                dsCARGO_MANIFEST.AddNew
                dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value = CStr(dContainerNum)
                dsCARGO_MANIFEST.Fields("RECIPIENT_ID").Value = iCustId
                dsCARGO_MANIFEST.Fields("ARRIVAL_NUM").Value = 1
                dsCARGO_MANIFEST.Fields("CARGO_MARK").Value = Left(sMark, 60)
                dsCARGO_MANIFEST.Fields("CARGO_BOL").Value = sBOL
                dsCARGO_MANIFEST.Fields("LR_NUM").Value = txtLrNum.Text
                dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value = iComm
                dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value = iQty
                dsCARGO_MANIFEST.Fields("QTY1_UNIT").Value = sUnt
                dsCARGO_MANIFEST.Fields("MANIFEST_STATUS").Value = sStatus
                dsCARGO_MANIFEST.Fields("CARGO_LOCATION").Value = sLoc
                    If ReturnFile.Value = True Then
                        dsCARGO_MANIFEST.Fields("CARGO_TREATMENT").Value = "RETURN"
                    ElseIf NewFile.Value = True Then
                        dsCARGO_MANIFEST.Fields("CARGO_TREATMENT").Value = "ORIGINAL"
                    End If
                dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value = iWt
                dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value = sWtUnt
                dsCARGO_MANIFEST.Fields("EXPORTER_ID").Value = iCustId
'                dsCARGO_MANIFEST.FIELDS("EXPORTER_ID").Value = iExporterSuppilerId
                dsCARGO_MANIFEST.Fields("IMPEX").Value = UCase(Left$(sImpex, 1))
                dsCARGO_MANIFEST.Fields("QTY2_EXPECTED").Value = 0 + iQty2
                dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value = sUnt2
                dsCARGO_MANIFEST.Update
                
                gsSqlStmt = "SELECT * FROM VOYAGE_CARGO"
                Set dsVOYAGE_CARGO = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 Then
                     dsVOYAGE_CARGO.AddNew
                        dsVOYAGE_CARGO.Fields("LR_NUM").Value = txtLrNum.Text
                        dsVOYAGE_CARGO.Fields("ARRIVAL_NUM").Value = 1
                        dsVOYAGE_CARGO.Fields("CONTAINER_NUM").Value = CStr(dContainerNum) 'CStr(dContainerNum)
                        dsVOYAGE_CARGO.Fields("LOT_NUM").Value = CStr(dContainerNum)
                     dsVOYAGE_CARGO.Update
                End If
                
                If UCase(Trim(sRcvdInv)) = "Y" Then 'checked
                    SqlStmt = "SELECT * FROM CARGO_TRACKING"
                    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                
                    If OraDatabase.LastServerErr = 0 Then
                        dsCARGO_TRACKING.AddNew
                        dsCARGO_TRACKING.Fields("LOT_NUM").Value = CStr(dContainerNum)
                        dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value = iComm
                        dsCARGO_TRACKING.Fields("cargo_description").Value = sMark
                        dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value = sDate
                        dsCARGO_TRACKING.Fields("OWNER_ID").Value = iCustId
                        dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value = iQty
                        dsCARGO_TRACKING.Fields("RECEIVER_ID").Value = 4 'Super User
                        dsCARGO_TRACKING.Fields("WHSE_RCPT_NUM").Value = 0 'No Whse Rcpt Num Yet
                        dsCARGO_TRACKING.Fields("QTY_UNIT").Value = sUnt
                        dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value = sLoc
                        dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = iQty
                        
                        
                        ' MAJOR EDIT
                        ' Adam Walter, Jan 2010.
                        ' Due to a complete rework of how storage bills are geenrated, this program now ONLY deals in setting
                        ' storage dates if this cargo is returned. --- UPDATE ROLLED BACK, FORM ENTRY OBJECTS HIDDEN OFF RIGHT SIDE ---
''''                        If ReturnFile.Value = True Then
''''                            dsCARGO_TRACKING.Fields("START_FREE_TIME").Value = sDate
''''                            dsCARGO_TRACKING.Fields("FREE_TIME_END").Value = sDate
''''                            dsCARGO_TRACKING.Fields("STORAGE_END").Value = DateAdd("d", -1, sDate)
''''                        End If
'                        If (txtLrNum.Text = -1) And (iComm <> 6172) Then
'                            'Set Free Time ,Check if LR_NUM is -1 means its not a ship AND commodity is not 6172
'                            dsCARGO_TRACKING.Fields("START_FREE_TIME").Value = sDate
'                            dsCARGO_TRACKING.Fields("FREE_TIME_END").Value = sDate
'                        Else
'                            If iComm = 6172 Then     'Check if commodity code is 6172, if so set WHSE_RCPT_NUM to -6172 and do not set Start Free Time/Free Time End
'                                dsCARGO_TRACKING.Fields("WHSE_RCPT_NUM").Value = -6172
'                            Else
'                                'Get free days for this commodity
'                                ' EDIT Adam Walter, Sep 2008.  New function to determine free time.
''                                        SqlStmt = "SELECT * FROM FREE_TIME WHERE COMMODITY_CODE = " & iComm
''                                        Set dsFREE_TIME = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
''                                        If OraDatabase.LastServerErr = 0 And dsFREE_TIME.RecordCount > 0 Then
'                                lFreeDays = FindFreeTime(iComm, iCustId, txtLrNum.Text)
'                                If lFreeDays <> -1 Then
'                                    dsCARGO_TRACKING.Fields("FREE_DAYS").Value = lFreeDays
'                                Else
'                                    MsgBox "No Free Time available for the Commodity Code:" & dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value & vbCrLf & "Unable to import Manifest this time", vbExclamation, "Free Time Error"
'                                    OraSession.RollBack
'                                    Exit Sub
'                                End If
'
'                                'Vessel # 2 is for Chiquita Paper and # 3 is for Futter Lumber
'                                If (txtLrNum = 2) Or (txtLrNum = 3) Then
'                                    dsCARGO_TRACKING.Fields("START_FREE_TIME").Value = sDate
'                                    dsCARGO_TRACKING.Fields("FREE_TIME_END").Value = DateAdd("d", lFreeDays, sDate)
'                                Else
'                                    If Not IsNull(dsTEMP.Fields("START_FREE_TIME")) Then
'                                        dsCARGO_TRACKING.Fields("START_FREE_TIME").Value = Format(dsTEMP.Fields("START_FREE_TIME").Value, "MM/DD/YYYY")
'                                        dsCARGO_TRACKING.Fields("FREE_TIME_END").Value = DateAdd("d", lFreeDays, Format(dsTEMP.Fields("START_FREE_TIME").Value, "MM/DD/YYYY"))
'                                    End If
'                                End If
'                            End If
'                        End If
                        dsCARGO_TRACKING.Update
                    End If
                End If
                
                dContainerNum = dContainerNum - 1
            End If
            
            iCount = iCount + 1
            StatusBar1.SimpleText = iCount & "  records imported to the system"
            
        End If
    Wend
            
    ' if we've reached this point without breaking execute, we are good to go.
    OraSession.CommitTrans
    StatusBar1.SimpleText = StatusBar1.SimpleText & " --- DONE"
    
'    If OraDatabase.LastServerErr = 0 And bDuplicate = False And bBadcomm = False And bBadcust = False And bMissingData = False Then
'        OraSession.CommitTrans
'        StatusBar1.SimpleText = iCount & " Records imported"
'    Else
'        OraSession.RollBack
'        StatusBar1.SimpleText = "Action Cancelled"
'        If bDuplicate = True Then
'            MsgBox "Duplicate BoL + Vessel + Customer detected on line " & ibadRow
'        ElseIf bBadcust = True Then
'            MsgBox "Invalid customer found on line " & ibadRow
'        ElseIf bBadcomm = True Then
'            MsgBox "Invalid commodity found on line " & ibadRow
'        ElseIf bMissingData = True Then
'            MsgBox "Missing data on line " & ibadRow
'        Else
'            MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR. No Record is imported."
'        End If
'        OraDatabase.LastServerErrReset
'        iCount = 0
'    End If
    
End Sub

Private Sub cmdBrowse_Click()
    cdlImportFile.Filter = "(*.csv)|*.csv||*.CSV"
    cdlImportFile.FilterIndex = 0
    cdlImportFile.ShowOpen
    On Error Resume Next
    If Err = 0 Then
        txtFile = cdlImportFile.FileName
    End If
'    On Error GoTo 0
End Sub

Private Sub cmdImport_Click()
'    On Error GoTo ErrHandler
    
'    If NewFile.Value = False And ReturnFile.Value = False Then
'      MsgBox "Please specify if this is new or returned cargo", vbInformation, "IMPORT DATA"
'      Exit Sub
'    End If
    
    If Trim(txtLrNum) = "" Then
      MsgBox "Enter the Vessel Number", vbInformation, "IMPORT DATA"
      Exit Sub
    End If
    If Trim(txtFile) = "" Then
      MsgBox "Select the FileName to import", vbInformation, "IMPORT DATA"
      Exit Sub
    End If

    Set sText = sFys.OpenTextFile(txtFile, ForReading, False)
    Call Change
   
Exit Sub
ErrHandler:
  MsgBox "Error occured during importing" & vbCrLf & Err.Description & vbCrLf & "Process Terminated", vbInformation, "IMPORT DATA"
End Sub

Private Sub Form_Load()
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
'    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)
End Sub

Private Function EmptyField(TheData As Variant) As Boolean

    If (TheData = Null Or TheData = "" Or TheData = ",") Then
        EmptyField = True
    Else
        EmptyField = False
    End If
End Function

Private Function FindFreeTime(commodity As Long, cus_id As Long, lr_num As String) As Integer

On Error GoTo Err_Handler

    Dim rs As Object
    Dim strSql As String
    Dim retVal As Integer



        '' Prepare sql statement- 1st check
        strSql = "select f.FREE_DAYS" & _
                    " from free_time f" & _
                    " where f.COMMODITY_CODE =" & commodity & _
                    " and f.CUSTOMER_ID=" & cus_id & _
                    " and f.LR_NUM=" & lr_num
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)

        If rs.RecordCount = 1 Then
            retVal = Int(Val(rs.Fields(0).Value))
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If

        '' Prepare sql statement- 2nd check
        strSql = "select f.FREE_DAYS" & _
            " from free_time f" & _
            " where f.COMMODITY_CODE =" & commodity & _
            " and f.CUSTOMER_ID=" & cus_id & _
            " and f.LR_NUM IS NULL"
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        If rs.RecordCount = 1 Then
            retVal = Int(Val(rs.Fields(0).Value))
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If


        '' Prepare sql statement- 3rd check
        strSql = "select f.FREE_DAYS" & _
                    " from free_time f" & _
                    " where f.COMMODITY_CODE =" & commodity & _
                    " and f.CUSTOMER_ID IS NULL" & _
                    " and f.LR_NUM IS NULL"

        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        If rs.RecordCount = 1 Then
            retVal = Int(Val(rs.Fields(0).Value))
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If


        '' Set variables to Nothing-Nothing found after 3 checks
        Set rs = Nothing
        retVal = -1
        FindFreeTime = retVal
        Exit Function


Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        Me.Name & "." & "FindFreeTime"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        retVal = -1
        FindFreeTime = retVal
    End If

End Function






















                    ' MAJOR CHANGE
                    ' Adam Walter, June 2008.
                    ' This program used to allow over-writing of imports in case of error; it's M.O. is
                    ' now that once a vessel+customer+BoL is used, it can NEVER be over-written, so the
                    ' first half of this IF/ELSE statement is getting gutted.
                      
                        'Edit the existing data
        '                dsCARGO_MANIFEST.EDIT
        '                dsCARGO_MANIFEST.FIELDS("COMMODITY_CODE").Value = iComm
        '                dsCARGO_MANIFEST.FIELDS("QTY_EXPECTED").Value = iQty
        '                dsCARGO_MANIFEST.FIELDS("QTY1_UNIT").Value = sUnt
        '                dsCARGO_MANIFEST.FIELDS("MANIFEST_STATUS").Value = sStatus
        '                dsCARGO_MANIFEST.FIELDS("CARGO_LOCATION").Value = sLoc
        '                dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT").Value = iWt
        '                dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT_UNIT").Value = sWtUnt
        '                dsCARGO_MANIFEST.FIELDS("EXPORTER_ID").Value = iExporterSuppilerId
        '                dsCARGO_MANIFEST.FIELDS("IMPEX").Value = UCase(Left$(sImpex, 1))
        '                dsCARGO_MANIFEST.FIELDS("CARGO_MARK").Value = sMark
        '                dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED").Value = 0 + iQty2
        '                dsCARGO_MANIFEST.FIELDS("QTY2_UNIT").Value = sUnt2
        '                dsCARGO_MANIFEST.Update
        '
        '                sContainerNum = dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value
        '
        '                If UCase(Trim(sRcvdInv)) = "Y" Then 'checked
        '                    SqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & sContainerNum & "'"
        '                    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
        '                    If OraDatabase.LastServerErr = 0 Then
        '                        If dsCARGO_TRACKING.RECORDCOUNT > 0 Then
        '                            dsCARGO_TRACKING.EDIT
        '                            dsCARGO_TRACKING.FIELDS("COMMODITY_CODE").Value = iComm
        '                            dsCARGO_TRACKING.FIELDS("cargo_description").Value = sMark
        '                            dsCARGO_TRACKING.FIELDS("DATE_RECEIVED").Value = sDate
        '                            dsCARGO_TRACKING.FIELDS("OWNER_ID").Value = iCustId
        '                            dsCARGO_TRACKING.FIELDS("QTY_RECEIVED").Value = iQty
        '                            dsCARGO_TRACKING.FIELDS("RECEIVER_ID").Value = 4 'Super User
        '                            dsCARGO_TRACKING.FIELDS("WHSE_RCPT_NUM").Value = 0 'No Whse Rcpt Num Yet
        '                            dsCARGO_TRACKING.FIELDS("QTY_UNIT").Value = sUnt
        '                            dsCARGO_TRACKING.FIELDS("WAREHOUSE_LOCATION").Value = sLoc
        '                            dsCARGO_TRACKING.FIELDS("QTY_IN_HOUSE").Value = iQty
        '
        '                            'Set Free Time ,Check if LR_NUM is -1 means its not a ship AND commodity is not 6172
        '                            If (txtLrNum = -1) And (iComm <> 6172) Then
        '                            'Update only if WHSE_RCPT_NUM is 0
        '                                dsCARGO_TRACKING.FIELDS("START_FREE_TIME").Value = sDate
        '                                dsCARGO_TRACKING.FIELDS("FREE_TIME_END").Value = sDate
        '                            Else
        '                               'Check if commodity code is 6172, if so set WHSE_RCPT_NUM to -6172 and do not set Start Free Time/Free Time End
        '                                If iComm = 6172 Then
        '                                    dsCARGO_TRACKING.FIELDS("WHSE_RCPT_NUM").Value = -6172
        '                                Else
        '                                    'Get free days for this commodity
        '                                    SqlStmt = "SELECT * FROM FREE_TIME WHERE COMMODITY_CODE = " & iComm
        '                                    Set dsFREE_TIME = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
        '                                    If OraDatabase.LastServerErr = 0 And dsFREE_TIME.RECORDCOUNT > 0 Then
        '                                        lFreeDays = dsFREE_TIME.FIELDS("FREE_DAYS").Value
        '                                        dsCARGO_TRACKING.FIELDS("FREE_DAYS").Value = lFreeDays
        '                                    Else
        '                                        MsgBox "No Free Time available for the Commodity Code:" & dsCARGO_TRACKING.FIELDS("COMMODITY_CODE").Value & vbCrLf & "Unable to import Manifest this time", vbExclamation, "Free Time Error"
        '                                        OraSession.RollBack
        '                                        Exit Sub
        '                                    End If
        '
        '                                    'Vessel # 2 is for Chiquita Paper and # 3 is for Futter Lumber
        '                                    If (txtLrNum = 2) Or (txtLrNum = 3) Then
        '                                        dsCARGO_TRACKING.FIELDS("START_FREE_TIME").Value = sDate
        '                                        dsCARGO_TRACKING.FIELDS("FREE_TIME_END").Value = DateAdd("d", lFreeDays, sDate)
        '                                    Else
        '                                        If Not IsNull(dsTEMP.FIELDS("START_FREE_TIME")) Then
        '                                            dsCARGO_TRACKING.FIELDS("START_FREE_TIME").Value = Format(dsTEMP.FIELDS("START_FREE_TIME").Value, "MM/DD/YYYY")
        '                                            dsCARGO_TRACKING.FIELDS("FREE_TIME_END").Value = DateAdd("d", lFreeDays, Format(dsTEMP.FIELDS("START_FREE_TIME").Value, "MM/DD/YYYY"))
        '                                        End If
        '                                    End If
        '                                End If
        '                            End If
        '                            dsCARGO_TRACKING.Update
        '                        End If
        '                    End If
        '                End If

