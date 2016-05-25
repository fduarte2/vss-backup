VERSION 5.00
Object = "{1C0489F8-9EFD-423D-887A-315387F18C8F}#1.0#0"; "vsflex8l.ocx"
Object = "{BEEECC20-4D5F-4F8B-BFDC-5D9B6FBDE09D}#1.0#0"; "vsflex8.ocx"
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCTL.OCX"
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "comdlg32.ocx"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Object = "{67397AA1-7FB1-11D0-B148-00A0C922E820}#6.0#0"; "MSADODC.OCX"
Begin VB.Form frm_Import 
   BackColor       =   &H00C0C000&
   Caption         =   "Manifest input"
   ClientHeight    =   8595
   ClientLeft      =   2475
   ClientTop       =   2160
   ClientWidth     =   11820
   LinkTopic       =   "Form1"
   ScaleHeight     =   8595
   ScaleWidth      =   11820
   Begin VB.Frame fra_Manifest 
      Height          =   3375
      Left            =   120
      TabIndex        =   1
      Top             =   480
      Width           =   7335
      Begin VB.ComboBox cmb_Prefix 
         Height          =   315
         Left            =   120
         TabIndex        =   20
         Text            =   "Combo1"
         Top             =   960
         Width           =   2415
      End
      Begin VB.CheckBox chk_ReadFieldNames 
         Caption         =   "Read field names from file"
         Height          =   255
         Left            =   240
         TabIndex        =   15
         Top             =   1920
         Width           =   2535
      End
      Begin VB.TextBox txtShipId 
         Height          =   315
         Left            =   1440
         MaxLength       =   60
         TabIndex        =   8
         Top             =   240
         Width           =   1365
      End
      Begin VB.TextBox txtVesselName 
         Height          =   315
         Left            =   2880
         MaxLength       =   60
         TabIndex        =   7
         Top             =   960
         Width           =   3165
      End
      Begin VB.TextBox txtImportFile 
         Height          =   315
         Left            =   1440
         MaxLength       =   100
         TabIndex        =   5
         Top             =   1440
         Width           =   3885
      End
      Begin VB.CommandButton cmdBrowse 
         Caption         =   "&Browse"
         Height          =   315
         Left            =   5400
         TabIndex        =   4
         Top             =   1440
         Width           =   675
      End
      Begin VB.Label lbl_Prefix 
         Caption         =   "Ship Prefix (Contract)"
         Height          =   255
         Left            =   120
         TabIndex        =   21
         Top             =   720
         Width           =   2055
      End
      Begin VB.Label Label2 
         Caption         =   "Vessel Num:"
         Height          =   255
         Left            =   120
         TabIndex        =   10
         Top             =   300
         Width           =   1245
      End
      Begin VB.Label Label1 
         Caption         =   "Ship Name:"
         Height          =   255
         Left            =   2880
         TabIndex        =   9
         Top             =   720
         Width           =   885
      End
      Begin VB.Label lblImportFile 
         Caption         =   "File Name:"
         Height          =   225
         Left            =   120
         TabIndex        =   6
         Top             =   1500
         Width           =   1005
      End
   End
   Begin MSAdodcLib.Adodc dc_Data 
      Height          =   330
      Left            =   6120
      Top             =   4080
      Visible         =   0   'False
      Width           =   1200
      _ExtentX        =   2117
      _ExtentY        =   582
      ConnectMode     =   0
      CursorLocation  =   3
      IsolationLevel  =   -1
      ConnectionTimeout=   15
      CommandTimeout  =   30
      CursorType      =   3
      LockType        =   3
      CommandType     =   8
      CursorOptions   =   0
      CacheSize       =   50
      MaxRecords      =   0
      BOFAction       =   0
      EOFAction       =   0
      ConnectStringType=   1
      Appearance      =   1
      BackColor       =   -2147483643
      ForeColor       =   -2147483640
      Orientation     =   0
      Enabled         =   -1
      Connect         =   ""
      OLEDBString     =   ""
      OLEDBFile       =   ""
      DataSourceName  =   ""
      OtherAttributes =   ""
      UserName        =   ""
      Password        =   ""
      RecordSource    =   ""
      Caption         =   "Adodc1"
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      _Version        =   393216
   End
   Begin VB.Frame fra_report 
      Height          =   1215
      Left            =   120
      TabIndex        =   3
      Top             =   6120
      Visible         =   0   'False
      Width           =   7335
      Begin VB.CommandButton cmdPrintManifest 
         Caption         =   "Print Manifest"
         Height          =   315
         Left            =   240
         TabIndex        =   11
         Top             =   480
         Width           =   1575
      End
   End
   Begin VB.Frame fra_preview 
      Height          =   2175
      Left            =   120
      TabIndex        =   2
      Top             =   3840
      Visible         =   0   'False
      Width           =   7335
      Begin VB.CommandButton cmdRefresh 
         Caption         =   "Refresh"
         Height          =   315
         Left            =   3360
         TabIndex        =   19
         Top             =   240
         Width           =   1575
      End
      Begin VB.ComboBox cmb_ExcleSheets 
         Height          =   315
         Left            =   3240
         TabIndex        =   16
         Text            =   "Combo1"
         Top             =   210
         Width           =   975
      End
      Begin VSFlex8Ctl.VSFlexGrid fg_data 
         Height          =   255
         Left            =   1680
         TabIndex        =   14
         Top             =   1080
         Width           =   2175
         _cx             =   3836
         _cy             =   450
         Appearance      =   1
         BorderStyle     =   1
         Enabled         =   -1  'True
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         MousePointer    =   0
         BackColor       =   -2147483643
         ForeColor       =   -2147483640
         BackColorFixed  =   -2147483633
         ForeColorFixed  =   -2147483630
         BackColorSel    =   -2147483635
         ForeColorSel    =   -2147483634
         BackColorBkg    =   -2147483636
         BackColorAlternate=   -2147483643
         GridColor       =   -2147483633
         GridColorFixed  =   -2147483632
         TreeColor       =   -2147483632
         FloodColor      =   192
         SheetBorder     =   -2147483642
         FocusRect       =   1
         HighLight       =   1
         AllowSelection  =   -1  'True
         AllowBigSelection=   -1  'True
         AllowUserResizing=   0
         SelectionMode   =   0
         GridLines       =   1
         GridLinesFixed  =   2
         GridLineWidth   =   1
         Rows            =   50
         Cols            =   10
         FixedRows       =   0
         FixedCols       =   0
         RowHeightMin    =   0
         RowHeightMax    =   0
         ColWidthMin     =   0
         ColWidthMax     =   0
         ExtendLastCol   =   0   'False
         FormatString    =   ""
         ScrollTrack     =   0   'False
         ScrollBars      =   3
         ScrollTips      =   0   'False
         MergeCells      =   0
         MergeCompare    =   0
         AutoResize      =   -1  'True
         AutoSizeMode    =   0
         AutoSearch      =   0
         AutoSearchDelay =   2
         MultiTotals     =   -1  'True
         SubtotalPosition=   1
         OutlineBar      =   0
         OutlineCol      =   0
         Ellipsis        =   0
         ExplorerBar     =   0
         PicturesOver    =   0   'False
         FillStyle       =   0
         RightToLeft     =   0   'False
         PictureType     =   0
         TabBehavior     =   0
         OwnerDraw       =   0
         Editable        =   0
         ShowComboButton =   1
         WordWrap        =   0   'False
         TextStyle       =   0
         TextStyleFixed  =   0
         OleDragMode     =   0
         OleDropMode     =   0
         DataMode        =   0
         VirtualData     =   -1  'True
         DataMember      =   ""
         ComboSearch     =   3
         AutoSizeMouse   =   -1  'True
         FrozenRows      =   0
         FrozenCols      =   0
         AllowUserFreezing=   0
         BackColorFrozen =   0
         ForeColorFrozen =   0
         WallPaperAlignment=   9
         AccessibleName  =   ""
         AccessibleDescription=   ""
         AccessibleValue =   ""
         AccessibleRole  =   24
      End
      Begin VB.CommandButton cmdImport 
         Caption         =   "Import"
         Height          =   315
         Left            =   4680
         TabIndex        =   13
         Top             =   210
         Width           =   1575
      End
      Begin VSFlex8LCtl.VSFlexGrid fg_header 
         Height          =   255
         Left            =   1680
         TabIndex        =   12
         Top             =   840
         Width           =   2175
         _cx             =   3836
         _cy             =   450
         Appearance      =   1
         BorderStyle     =   1
         Enabled         =   -1  'True
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         MousePointer    =   0
         BackColor       =   -2147483643
         ForeColor       =   -2147483640
         BackColorFixed  =   -2147483633
         ForeColorFixed  =   -2147483630
         BackColorSel    =   -2147483635
         ForeColorSel    =   -2147483634
         BackColorBkg    =   -2147483636
         BackColorAlternate=   -2147483643
         GridColor       =   -2147483633
         GridColorFixed  =   -2147483632
         TreeColor       =   -2147483632
         FloodColor      =   192
         SheetBorder     =   -2147483642
         FocusRect       =   1
         HighLight       =   1
         AllowSelection  =   -1  'True
         AllowBigSelection=   -1  'True
         AllowUserResizing=   1
         SelectionMode   =   0
         GridLines       =   1
         GridLinesFixed  =   2
         GridLineWidth   =   1
         Rows            =   3
         Cols            =   2
         FixedRows       =   1
         FixedCols       =   0
         RowHeightMin    =   0
         RowHeightMax    =   0
         ColWidthMin     =   0
         ColWidthMax     =   0
         ExtendLastCol   =   0   'False
         FormatString    =   ""
         ScrollTrack     =   0   'False
         ScrollBars      =   3
         ScrollTips      =   0   'False
         MergeCells      =   0
         MergeCompare    =   0
         AutoResize      =   -1  'True
         AutoSizeMode    =   0
         AutoSearch      =   0
         AutoSearchDelay =   2
         MultiTotals     =   -1  'True
         SubtotalPosition=   1
         OutlineBar      =   0
         OutlineCol      =   0
         Ellipsis        =   0
         ExplorerBar     =   0
         PicturesOver    =   0   'False
         FillStyle       =   0
         RightToLeft     =   0   'False
         PictureType     =   0
         TabBehavior     =   0
         OwnerDraw       =   0
         Editable        =   2
         ShowComboButton =   1
         WordWrap        =   0   'False
         TextStyle       =   0
         TextStyleFixed  =   0
         OleDragMode     =   0
         OleDropMode     =   0
         ComboSearch     =   3
         AutoSizeMouse   =   -1  'True
         FrozenRows      =   0
         FrozenCols      =   0
         AllowUserFreezing=   0
         BackColorFrozen =   0
         ForeColorFrozen =   0
         WallPaperAlignment=   9
         AccessibleName  =   ""
         AccessibleDescription=   ""
         AccessibleValue =   ""
         AccessibleRole  =   24
      End
      Begin VB.Label lbl_From 
         Caption         =   "Data from File:"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   120
         TabIndex        =   18
         Top             =   240
         Width           =   1335
      End
      Begin VB.Label lbl_File 
         Caption         =   "Label3"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   1440
         TabIndex        =   17
         Top             =   240
         Width           =   1455
      End
   End
   Begin MSComctlLib.TabStrip tsFunction 
      Height          =   8535
      Left            =   0
      TabIndex        =   0
      Top             =   0
      Width           =   11775
      _ExtentX        =   20770
      _ExtentY        =   15055
      _Version        =   393216
      BeginProperty Tabs {1EFB6598-857C-11D1-B16A-00C0F0283628} 
         NumTabs         =   3
         BeginProperty Tab1 {1EFB659A-857C-11D1-B16A-00C0F0283628} 
            Caption         =   "Manifest"
            Key             =   "key_manifest"
            Object.ToolTipText     =   "Manifest"
            ImageVarType    =   2
         EndProperty
         BeginProperty Tab2 {1EFB659A-857C-11D1-B16A-00C0F0283628} 
            Caption         =   "Preview"
            Key             =   "key_preview"
            Object.ToolTipText     =   "Preview manifest file"
            ImageVarType    =   2
         EndProperty
         BeginProperty Tab3 {1EFB659A-857C-11D1-B16A-00C0F0283628} 
            Caption         =   "Report"
            Key             =   "key_report"
            Object.ToolTipText     =   "Print Manifest report"
            ImageVarType    =   2
         EndProperty
      EndProperty
   End
   Begin MSComDlg.CommonDialog cdlImportFile 
      Left            =   5400
      Top             =   600
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
   End
   Begin Crystal.CrystalReport CrystalReport1 
      Left            =   5400
      Top             =   1200
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin VB.Menu mnu_Popup 
      Caption         =   "mnu_Popup"
      Visible         =   0   'False
      Begin VB.Menu mnu_Save 
         Caption         =   "Save Settings"
      End
      Begin VB.Menu mnu_Load 
         Caption         =   "Load Settings"
      End
      Begin VB.Menu mnu_Sep 
         Caption         =   "-"
      End
      Begin VB.Menu mnu_Add 
         Caption         =   "Add New Column"
      End
   End
End
Attribute VB_Name = "frm_Import"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

' UI offset
Const int_pxl As Integer = 60
Dim str_ConStr As String
Dim str_File_List As String
Dim str_MAP_TABLES As String
Dim str_MAP_WHERE As String

Dim bln_bdf_in As Boolean

Dim lRecCount As Long
Dim lNewRecCount As Long
Dim iPos As Integer
Dim iLastBackSlashPos As Integer
Dim sPath As String
Dim sFileName As String
Dim sDirChk As String
Dim lMaxTranNum As Long
Dim sMissingFields As String
Dim iSuccess As Integer
Dim iString1 As String
Dim iString2 As String
Dim iPosition As Integer
Dim iFromShippingLine As Long
Dim iShippingLine As Long
Dim iCommID As Long
Dim iLRNum As Long
Dim iResponse As Integer
Dim iFirst  As Boolean
Dim iMyCommodityName As String
Dim sLoc1 As String
Dim sLoc2 As String
Dim sWareHLoc As String
Dim sInsp As String
Dim iLocation As String
Dim gsSqlStmt As String
Dim bln_LRNUM_in_system As Boolean





Private Sub cmdBrowse_Click()
    On Error Resume Next
    Select Case int_FileFormat
        Case 1: cdlImportFile.FileName = "*.txt"
        Case 2: cdlImportFile.FileName = "*.xls"
        Case 3, 4: cdlImportFile.FileName = "*.dbf"
    End Select
    
    cdlImportFile.Filter = "Text (*.txt)|*.txt|Excel (*.xls)|*.xls|dBase (*.dbf)|*.dbf|FoxPro (*.dbf)|*.dbf"
'str_File_List
    cdlImportFile.FilterIndex = int_FileFormat
    cdlImportFile.CancelError = True
 
    cdlImportFile.Action = 1
    
    If Not Err = 32755 Then
        txtImportFile = cdlImportFile.FileName
        int_FileFormat = cdlImportFile.FilterIndex
        Select Case cdlImportFile.FilterIndex
            Case 1: str_ConStr = str_ConnStr_txt
            Case 2: str_ConStr = str_ConnStr_xls
            Case 3: str_ConStr = str_ConnStr_dbf
            Case 4: str_ConStr = str_ConnStr_fox
        End Select
        
        End If
    On Error GoTo 0
End Sub


Private Sub cmdImport_Click()
    Dim str_UserName As String
    Dim str_LogFileName As String
    Dim int_LogFileNum As Integer
    Dim str_sql_stmnt As String
    Dim str_CRLF As String
    Dim bln_trace As Boolean
    ' - to do replace testboxes with variables
    Dim str_LRNUM As String
    Dim str_Vessel_Name As String
    Dim str_message As String
    Dim i As Long
    
    'Initialize new line char
    str_CRLF = Chr$(13) & Chr$(10)
    'Init the user
    str_UserName = Get_Logon_Username
    'Initialize log file name
    str_LogFileName = App.Path & "\_CM_.log"
    
    'Open the log file
    int_LogFileNum = FreeFile()
    'Open str_LogFileName For Output As #int_LogFileNum
        
    lRecCount = 0
    lNewRecCount = 0

    'Check for emply import file
    If Trim$(txtImportFile.text) = "" Then
        MsgBox "Import File can not be blank.", vbInformation, "Invalid Import File"
        On Error GoTo 0
        txtImportFile.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtShipId.text) = "" Then
        MsgBox "Can not be blank.", vbInformation, "Ship LR Num"
        On Error GoTo 0
        txtShipId.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtVesselName.text) = "" Then
        MsgBox "Vessel Name can not be blank.", vbInformation, "Ship Name"
        On Error GoTo 0
        'txtVesselName.SetFocus
        Exit Sub
    End If
    
   
    If bln_LRNUM_in_system Then
        iResponse = MsgBox("The LR Number " & iLRNum & " is already entered in the system. Do you want to continue?", vbQuestion + vbYesNo, "Vessel ID")
        If iResponse = vbNo Then
            Exit Sub
        End If
    Else
        ordb.BeginTrans
        'create new vessel and voyage
        
        str_sql_stmnt = "INSERT INTO VESSEL_PROFILE (LR_NUM,ARRIVAL_NUM,VESSEL_NAME,VESSEL_STATUS,SHIP_PREFIX) VALUES('%n','%n','%v','ACTIVE','%t')"
        str_sql_stmnt = Replace(str_sql_stmnt, "%n", txtShipId.text)
        str_sql_stmnt = Replace(str_sql_stmnt, "%v", txtVesselName.text)
        str_sql_stmnt = Replace(str_sql_stmnt, "%t", cmb_Prefix.text)
        bln_trace = or_Add_Value(str_sql_stmnt)
        If bln_trace Then
            str_sql_stmnt = "INSERT INTO VOYAGE (LR_NUM,ARRIVAL_NUM,VOYAGE_NUM,DATE_EXPECTED,DATE_ARRIVED,DATE_DEPARTED) VALUES ('%n','%n','1',TO_DATE('%d','MM/DD/YYYY'),TO_DATE('%d','MM/DD/YYYY'),TO_DATE('%d','MM/DD/YYYY'))"
            str_sql_stmnt = Replace(str_sql_stmnt, "%n", txtShipId.text)
            str_sql_stmnt = Replace(str_sql_stmnt, "%v", txtVesselName.text)
            str_sql_stmnt = Replace(str_sql_stmnt, "%d", Format$(Now, "mm/dd/yyyy"))
            bln_trace = bln_trace And or_Add_Value(str_sql_stmnt)
        End If
        If bln_trace Then
            ordb.CommitTrans
        Else
            iResponse = MsgBox("The LR Number " & iLRNum & " is already entered in the voyage system. Do you want to continue?", vbQuestion + vbYesNo, "Vessel ID")
            If iResponse = vbNo Then
                Exit Sub
            End If
            ordb.RollbackTrans
            
        End If
    End If
    'loop trough the grid and enter insert values
    
    Screen.MousePointer = vbHourglass
    'Load frm_ImportProgress
    frm_ImportProgress.Caption = "Importing " & txtVesselName.text
    frm_ImportProgress.pb.Value = 0
    frm_ImportProgress.lbl_Display = "Records - " & fg_data.Rows
    frm_ImportProgress.Show
    
    ordb.BeginTrans
    Load frm_ImportProgress
    For i = 0 To fg_data.Rows - 1
        
        str_sql_stmnt = "INSERT INTO CARGO_TRACKING (ARRIVAL_NUM,SOURCE_USER,SOURCE_NOTE," & UpdFieldsList() & ") SELECT  '" & txtShipId.text & "','" & str_UserName & "','" & right(txtImportFile.text, 50) & "'," & UpdFieldsValues(i) & " FROM DUAL "
        str_sql_stmnt = str_sql_stmnt & str_MAP_TABLES & str_MAP_WHERE
    
        lRecCount = lRecCount + 1
        bln_trace = or_Add_Value(str_sql_stmnt)
        If bln_trace Then
            frm_ImportProgress.lbl_Display.Caption = "Records - " & CStr(i) & " out of " & CStr(fg_data.Rows)
            DoEvents
            frm_ImportProgress.pb.Value = (i / fg_data.Rows) * 100
            frm_ImportProgress.pb.Refresh
            frm_ImportProgress.lbl_Display.Refresh
            DoEvents
            'frm_ImportProgress.Refresh
            
            lNewRecCount = lNewRecCount + 1
            
        Else
            Debug.Print str_sql_stmnt
            Screen.MousePointer = vbDefault
            str_message = "IMPORT FAILURE!" & str_CRLF & str_CRLF & "Records tried: " & CStr(lRecCount) & str_CRLF & "Records added: " & CStr(lNewRecCount) & str_CRLF & str_CRLF
            str_sql_stmnt = Replace(str_sql_stmnt, "(", str_CRLF & "(")
            str_sql_stmnt = Replace(str_sql_stmnt, "SELECT", str_CRLF & "SELECT")
            str_sql_stmnt = Replace(str_sql_stmnt, "FROM", str_CRLF & "FROM")
            str_message = str_message & "Last Command:" & str_sql_stmnt
            frm_ImportProgress.txt_Display = str_sql_stmnt
            Debug.Print str_sql_stmnt
            str_message = str_message & str_CRLF & str_CRLF & "Would you like to continue?"
            iResponse = MsgBox(str_message, vbYesNo, "ERROR")
            
            If iResponse = vbNo Then Exit For
            Screen.MousePointer = vbHourglass
            frm_ImportProgress.Hide
            'Exit Sub
        End If
    Next i
        
    Screen.MousePointer = vbDefault
    str_message = "IMPORT FINISHED!" & str_CRLF & str_CRLF & "Records tried: " & CStr(lRecCount) & str_CRLF & "Records added: " & CStr(lNewRecCount) & str_CRLF & str_CRLF
          
    iResponse = MsgBox(str_message, vbYesNo, "Import completed. Confirm commit!")
    If iResponse = vbNo Then
        ordb.RollbackTrans
    Else
        ordb.CommitTrans
        frm_ImportProgress.Hide
    End If
    '
   Unload frm_ImportProgress
End Sub


Private Sub cmdRefresh_Click()
Dim str_map As String
Dim str_Key As String
Dim str_Field As String
Dim str_Table As String
Dim strLd As String
Dim strRd As String
On Error GoTo errHandler
    Dim str_query As String
    Dim i As Integer
    str_query = ""
    strLd = ""
    strRd = ""
    If int_FileFormat <> 4 Then
        ' Foxpro does not like the parentesis
        strLd = "["
        strRd = "]"
    End If
    For i = 0 To fg_header.Cols - 1
        If Len(str_query) > 0 Then str_query = str_query & ","
        If fg_header.TextMatrix(2, i) <> "" Then
            If left(fg_header.TextMatrix(2, i), 4) = "MAP(" Or left(fg_header.TextMatrix(2, i), 5) = "CASE(" Then
                str_query = str_query & strLd & fg_header.TextMatrix(0, i) & strRd
            ' deal with mapping in format MAP(TABLE,KEY,FIELD)
                
            Else
                str_query = str_query & fg_header.TextMatrix(2, i)
            End If
        Else
            str_query = str_query & strLd & fg_header.TextMatrix(0, i) & strRd
        End If
    Next i
    dc_Data.RecordSource = ""
    dc_Data.RecordSource = "SELECT " & str_query & " FROM " & strLd & str_db_Table_Name & strRd
    Debug.Print dc_Data.RecordSource
    dc_Data.LockType = adLockReadOnly
    'For i = 0 To fg_header.Cols - 1
    '    fg_header.TextMatrix(0, i) = Format(fg_header.TextMatrix(0, i), "<")
    'Next i
    dc_Data.Refresh
    Call ResizeHeader
    Call CaseFormatting
    lbl_File.Caption = sFileName & " (" & Str(fg_data.Rows) & ")"
    Call FormatHatch
Exit Sub
errHandler:
    MsgBox Err.Description, vbOKOnly, "Error"
End Sub
Private Sub FormatHatch()
On Error GoTo errHndlr
Dim i As Integer
Dim r As Long
Dim s As Integer
Dim strHatch As String
Dim bln_Haitch_In As Boolean
Dim try_again As Boolean
'Find the hatch column if loading
bln_Haitch_In = False
For i = 0 To fg_header.Cols - 1
    If Format(fg_header.Cell(flexcpTextDisplay, 1, i), ">") = "HATCH" Then
        bln_Haitch_In = True
        Exit For
    End If
Next i
If bln_Haitch_In Then
    fg_data.Redraw = flexRDNone
    Screen.MousePointer = vbHourglass
        For r = 0 To fg_data.Rows - 1
            ' Remove "-" and spaces
            strHatch = Replace(fg_data.Cell(flexcpText, r, i), "-", "")
            strHatch = Replace(strHatch, " ", "")
            ' make sure no duplicates are present
            try_again = True
            s = 1
            If Len(strHatch) > 2 Then
                Do While try_again
                    If InStr(s + 1, strHatch, Mid(strHatch, s, 1)) > 0 Then
                        strHatch = left(strHatch, s) & right(strHatch, Len(strHatch) - s - 1)
                        try_again = True
                    Else
                        s = s + 1
                        If s >= Len(strHatch) Then try_again = False
                    End If
                Loop
            End If
            If Len(strHatch) = 0 Then strHatch = "CONT"
            fg_data.Cell(flexcpText, r, i) = strHatch
        Next r
        
        
    Screen.MousePointer = vbDefault
    fg_data.Redraw = flexRDDirect
End If
Exit Sub
errHndlr:
    MsgBox Err.Description, vbCritical, "ERROR in FormatHatch!"
    Screen.MousePointer = vbDefault
    fg_data.Redraw = flexRDDirect
End Sub

Private Sub fg_data_AfterScroll(ByVal OldTopRow As Long, ByVal OldLeftCol As Long, ByVal NewTopRow As Long, ByVal NewLeftCol As Long)
    fg_header.LeftCol = NewLeftCol
End Sub

Private Sub fg_data_DblClick()
Static sord As VSFlex8Ctl.SortSettings
Dim c As Integer
    c = fg_data.MouseCol
    If c = -1 Then Exit Sub
    fg_data.Select 0, fg_data.MouseCol
    If sord = flexSortGenericAscending Then
        sord = flexSortGenericDescending
    Else
        sord = flexSortGenericAscending
    End If
    fg_data.Sort = sord
End Sub

Private Sub fg_header_AfterEdit(ByVal Row As Long, ByVal Col As Long)
Dim vi As Integer
If Row = 1 Then
    If fg_header.ComboIndex = 0 And fg_header.ComboIndex < arr_combo_count Then
        vi = fg_header.ComboIndex
        arr_combo(arr_colPos(vi)).bln_In = Not arr_combo(arr_colPos(vi)).bln_In
        If fg_header.ColData(Col) <> 0 Then
            arr_combo(fg_header.ColData(Col)).bln_In = Not arr_combo(fg_header.ColData(Col)).bln_In
            
        End If
        fg_header.ColData(Col) = arr_colPos(vi)
    Else
        If fg_header.ColData(Col) <> 0 And fg_header.ComboIndex >= 0 Then
            arr_combo(fg_header.ColData(Col)).bln_In = Not arr_combo(fg_header.ColData(Col)).bln_In
            fg_header.ColData(Col) = 0
        End If
    End If
    
Else
    fg_header.ColComboList(Col) = ""
End If

End Sub

Private Sub fg_header_AfterScroll(ByVal OldTopRow As Long, ByVal OldLeftCol As Long, ByVal NewTopRow As Long, ByVal NewLeftCol As Long)
    fg_data.LeftCol = NewLeftCol
End Sub

Private Sub fg_header_AfterUserResize(ByVal Row As Long, ByVal Col As Long)
  
    fg_data.ColWidth(Col) = fg_header.ColWidth(Col)
   
End Sub

Private Sub fg_header_BeforeEdit(ByVal Row As Long, ByVal Col As Long, Cancel As Boolean)
Dim str_text As String
If Row = 1 Then
    fg_header.ColComboList(Col) = MakeFieldsComboList
Else
    str_text = fg_header.Cell(flexcpTextDisplay, Row - 1, Col)
    fg_header.ColComboList(Col) = ""
    fg_header.TextMatrix(Row - 1, Col) = str_text
End If
End Sub

Private Sub fg_header_MouseUp(Button As Integer, Shift As Integer, X As Single, Y As Single)
If Button = 2 Then PopupMenu mnu_Popup
End Sub

Private Sub Form_Load()
   ' Call Initialize_UI
    Me.top = (Screen.Height - Me.Height) / 3
    Me.left = (Screen.Width - Me.Width) / 2
    
   '
    Screen.MousePointer = vbHourglass
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
'    If Initialize_DB Then
'        gsSqlStmt = "SELECT * FROM CARGO_TRACKING"
'        Set dsCARGO_TRACKING_ALL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    End If
    
    Screen.MousePointer = vbDefault
  
    
    'Initialize log file name
    'gsLogFileName = App.Path & "\PSWImpEM.log"
    Me.Caption = Me.Caption & " - " & str_DatabaseName
    Call Fill_Prefix_Combo
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, Me.Caption
    Screen.MousePointer = vbDefault
    On Error GoTo 0
    
End Sub

Private Function Fill_Prefix_Combo() As Boolean
Dim str_sql As String
Fill_Prefix_Combo = False
Dim bln_trace As Boolean
    If Not ordb.State = adStateOpen Then
        cmb_Prefix.text = "Not connected"
        cmb_Prefix.Enabled = False
        Exit Function
    Else
        cmb_Prefix.Enabled = True
    End If
str_sql = "SELECT CONTRACT_ID, CONTRACT_CD FROM NSB_CONTRACT ORDER BY CONTRACT_ID"
bln_trace = FillCombo(cmb_Prefix, str_sql, False)
If Not bln_trace Then
    cmb_Prefix.text = "Not connected"
    cmb_Prefix.Enabled = False
    Exit Function
End If
Fill_Prefix_Combo = bln_trace
End Function

Public Function CheckField(asFieldName As String) As String
'Appends the content of the dbf file field to the message string
'Returns the field name if not found in the dbf file
Dim gsMessage As String
    On Error Resume Next
    
'    gsMessage = gsMessage & asFieldName & " : " & rsImport.Fields(asFieldName) & gsnl
'    If Err <> 0 Then
'        CheckField = asFieldName & "; "
'    Else
'        CheckField = ""
'    End If
End Function

Private Sub Form_Resize()
On Error Resume Next
With Me
    tsFunction.Move .ScaleLeft, .ScaleTop, .ScaleWidth, .ScaleHeight
    fra_Manifest.Move int_pxl, int_pxl * 6, .ScaleWidth - int_pxl * 2, .ScaleHeight - int_pxl * 7
    fra_preview.Move int_pxl, int_pxl * 6, .ScaleWidth - int_pxl * 2, .ScaleHeight - int_pxl * 7
    fra_report.Move int_pxl, int_pxl * 6, .ScaleWidth - int_pxl * 2, .ScaleHeight - int_pxl * 7
    fg_header.Move 0, int_pxl * 10, fra_preview.Width, fg_header.RowHeight(0) * 4 + int_pxl * 2
    fg_data.Move 0, fg_header.Height + fg_header.top + int_pxl, fra_preview.Width, fra_preview.Height - fg_header.Height - fg_header.top - int_pxl
    cmdImport.Move fra_preview.Width - cmdImport.Width - int_pxl
    cmdRefresh.Move cmdImport.left - cmdRefresh.Width - int_pxl
    cmb_ExcleSheets.Move cmdRefresh.left - cmb_ExcleSheets.Width - int_pxl
    lbl_File.Width = cmb_ExcleSheets.left - lbl_File.left - int_pxl * 2
End With
End Sub


Private Sub opt_File_Click(Index As Integer)
Select Case Index
Case 0:
    str_ConStr = str_ConnStr_txt
    'If Len(txtImportFile) > 0 Then str_ConStr = Replace(str_ConStr, "%f", txtImportFile)
    str_File_List = "*.txt, *.csv"
Case 1:
    str_ConStr = str_ConnStr_xls
    'If Len(txtImportFile) > 0 Then str_ConStr = Replace(str_ConStr, "%f", txtImportFile)
    str_File_List = "*.txt, *.csv"
Case 2:
    str_ConStr = str_ConnStr_dbf
    'If Len(txtImportFile) > 0 Then str_ConStr = Replace(str_ConStr, "%f", txtImportFile)
    str_File_List = "*.txt, *.csv"
 Case 3:
    str_ConStr = str_ConnStr_fox
    'If Len(txtImportFile) > 0 Then str_ConStr = Replace(str_ConStr, "%f", txtImportFile)
    str_File_List = "*.dbf"
      
End Select
End Sub



Private Sub mnu_Add_Click()
    fg_header.Cols = fg_header.Cols + 1
    fg_data.Cols = fg_header.Cols
End Sub

Private Sub mnu_Load_Click()
 On Error Resume Next
  Dim i As Integer
  Dim txtImportFile As String
    cdlImportFile.FileName = "*.nsm"
    cdlImportFile.Filter = "Configuration (*.nsm)"
    cdlImportFile.Action = 1
    If Err = 0 And Len(cdlImportFile.FileName) > 0 Then
        Screen.MousePointer = vbHourglass
        txtImportFile = cdlImportFile.FileName
        fg_header.LoadGrid txtImportFile, flexFileAll
        If fg_data.Cols <> fg_header.Cols Then fg_data.Cols = fg_header.Cols
        fg_header.Redraw = flexRDDirect
        'Call cmdRefresh_Click
        Call ResizeHeader
        fg_data.LeftCol = fg_header.LeftCol
        Screen.MousePointer = vbDefault
        
    End If
    On Error GoTo 0
    Screen.MousePointer = vbDefault
End Sub

Private Sub mnu_Rem_Click()
Dim res As Integer
If fg_header.Col > 0 Then
res = MsgBox("Are you sure to remove the LAST column?", vbYesNo, "Remove Column")
If res = vbYes Then fg_header.Cols = fg_header.Cols - 1
End If
End Sub

Private Sub mnu_Save_Click()
  On Error Resume Next
  Dim txtImportFile As String
    cdlImportFile.FileName = "*.nsm"
    cdlImportFile.Filter = "Configuration (*.nsm)"
    cdlImportFile.Action = 2
    If Err = 0 Then
        txtImportFile = cdlImportFile.FileName
        fg_header.SaveGrid txtImportFile, flexFileAll
    End If
    On Error GoTo 0
End Sub

Private Sub tsFunction_Click()
' change function display

    
Select Case tsFunction.SelectedItem.Key
Case "key_manifest"
    If Not fra_Manifest.Visible Then
'        fra_db.Visible = False
        fra_report.Visible = False
        fra_preview.Visible = False
        fra_Manifest.Visible = True
    End If
Case "key_preview"
    If Not fra_preview.Visible Then
'        fra_db.Visible = False
        fra_report.Visible = False
        fra_Manifest.Visible = False
        fra_preview.Visible = True
        Load_dbf
    End If

Case "key_report"
    If Not fra_report.Visible Then
'        fra_db.Visible = False
        fra_preview.Visible = False
        fra_Manifest.Visible = False
        fra_report.Visible = True
    End If
End Select


End Sub

Private Sub txtShipId_lostfocus()
    
    If Trim$(txtShipId.text) = "" Then
        txtShipId.text = ""
        txtShipId.SetFocus
        MsgBox "Can not be null for this field. Try it again!", vbInformation, "LR Num"
        Exit Sub
    Else
        iLRNum = Val(txtShipId.text)
        gsSqlStmt = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM= '" & Val(txtShipId.text) & "'"
        txtVesselName.text = or_Return_Value(gsSqlStmt)
        bln_LRNUM_in_system = False
        If Len(txtVesselName.text) > 0 Then bln_LRNUM_in_system = True
    End If
    
   
End Sub
Private Sub cmdPrintManifest_Click()

    Dim lLRNum As Long

    On Error Resume Next
    lLRNum = CLng(txtShipId.text)
    If lLRNum > 0 And IsNumeric(lLRNum) Then
        Call PrintManifest(lLRNum)
    Else
        MsgBox "You must enter numeric Ship Number.", vbInformation, "Invalid Ship Number"
    End If
    
    On Error GoTo 0

End Sub
Private Sub PrintManifest(lLRNum As Long)
'   CrystalReport1.Connect = "DSN=" & gsOraInstance & ";UID=" & gsOraUserId & ";PWD=" & gsOraPassword
'    CrystalReport1.Destination = crptToWindow
'    CrystalReport1.ReportFileName = App.Path & "\MANIFEST.RPT"
'    CrystalReport1.SelectionFormula = "{CARGO_TRACKING.ARRIVAL_NUM} = '" & lLRNum & "'"
'    CrystalReport1.WindowTop = 0
'    CrystalReport1.WindowLeft = 0
'    CrystalReport1.WindowWidth = Screen.Width / Screen.TwipsPerPixelX
'    CrystalReport1.WindowHeight = Screen.Height / Screen.TwipsPerPixelY - 28
'    CrystalReport1.WindowTitle = "PSW Import Manifest Report"
'    CrystalReport1.Action = 1
End Sub

Private Sub Format_Header()
Dim i As Integer
If Len(txtImportFile) = 0 Then Exit Sub
fg_header.Cols = fg_data.Cols
For i = 0 To fg_data.Cols - 1
    fg_header.ColWidth(i) = fg_data.ColWidth(i)
    fg_header.ColData(i) = 0
    If i < dc_Data.Recordset.Fields.Count Then
        fg_header.TextMatrix(0, i) = dc_Data.Recordset.Fields(i).Name
        fg_header.AutoSize i
        If fg_header.ColWidth(i) < fg_data.ColWidth(i) Then
            fg_header.ColWidth(i) = fg_data.ColWidth(i)
        Else
            fg_data.ColWidth(i) = fg_header.ColWidth(i)
        End If
    End If
Next i
End Sub
Private Sub ResizeHeader()
Dim i As Integer
fg_header.Cols = fg_data.Cols
For i = 0 To fg_data.Cols - 1
    fg_header.ColWidth(i) = fg_data.ColWidth(i)
    fg_header.ColData(i) = 0
    If i < dc_Data.Recordset.Fields.Count Then
        fg_header.AutoSize i
        If fg_header.ColWidth(i) < fg_data.ColWidth(i) Then
            fg_header.ColWidth(i) = fg_data.ColWidth(i)
        Else
            fg_data.ColWidth(i) = fg_header.ColWidth(i)
        End If
    End If
Next i
End Sub
Private Sub Load_dbf()
' Use the data control as a datasource for datagrid to display the file
On Error GoTo Err_handler
Dim str_connect As String

Select Case cdlImportFile.FilterIndex
Case 1 ' Text
Case 2 ' Excel
Case 3 ' dBase
Case 4 ' FoxPro
End Select

If Not bln_bdf_in Then
    If Len(txtImportFile.text) > 0 Then
    'Get path and file name
    iLastBackSlashPos = 0
    iPos = InStr(txtImportFile, "\")
    While iPos > 0
        iLastBackSlashPos = iPos
        iPos = InStr(iPos + 1, txtImportFile, "\")
    Wend
    If iLastBackSlashPos = 0 Then
        sPath = ""
        sFileName = Trim$(txtImportFile.text)
    Else
        sPath = Trim$(Mid$(txtImportFile.text, 1, iLastBackSlashPos - 1))
        sFileName = Trim$(Mid$(txtImportFile.text, iLastBackSlashPos + 1))
    End If
    lbl_File.Caption = sFileName
    'dc_Data.ConnectionString = "Driver={Microsoft dBASE Driver (*.dbf)};DriverID=277;Dbq=" & sPath & ";"
    str_connect = Replace(str_ConStr, "%p", sPath)
    str_connect = Replace(str_connect, "%f", sFileName)
    If chk_ReadFieldNames.Value Then
        str_connect = str_connect & "Extended Properties=""Excel 8.0;HDR=Yes;IMEX=1"""
    Else
        str_connect = str_connect & "Extended Properties=""Excel 8.0;HDR=No;IMEX=1"""
    End If
    Debug.Print str_connect
    'dc_Data.ConnectionString = str_connect
 'dc_Data.ConnectionString = "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=""Folios Discovery Bay 006N.xls"";Extended Properties=""Excel 8.0;HDR=Yes"""
    If int_FileFormat = 2 Then
        List_Table_Sources (str_connect)
        If cmb_ExcleSheets.ListCount > 0 Then
            cmb_ExcleSheets.ListIndex = 0
            dc_Data.RecordSource = "select * from [" & cmb_ExcleSheets.text & "]"
            str_db_Table_Name = cmb_ExcleSheets.text
        End If
    Else
        List_Table_Sources (str_connect)
        dc_Data.RecordSource = "select * from [" & sFileName & "]"
        str_db_Table_Name = sFileName
    End If
    dc_Data.LockType = adLockReadOnly
    dc_Data.ConnectionString = str_connect
    dc_Data.Refresh
   
   End If
   fg_data.DataMode = flexDMFree
   Set fg_data.DataSource = dc_Data
   Call Format_Header
    
End If
Exit Sub
Err_handler:
    MsgBox "Error: " & Err.Description

End Sub



Private Sub List_Table_Sources(Conn_STR As String)
' Read the spreadsheet names into a combo box

Dim cn As ADODB.Connection
Dim rsT As ADODB.Recordset
Dim intTblCnt As Integer, intTblFlds As Integer
Dim strTbl As String
Dim rsC As ADODB.Recordset
Dim intColCnt As Integer, intColFlds As Integer
Dim strCol As String
Dim t As Integer, c As Integer, f As Integer
Set cn = New ADODB.Connection
With cn
    .ConnectionString = Conn_STR
    .CursorLocation = adUseClient
    .Mode = adModeRead
    .Open
    
End With
Set rsT = cn.OpenSchema(adSchemaTables)
intTblCnt = rsT.RecordCount
intTblFlds = rsT.Fields.Count
cmb_ExcleSheets.Clear
For t = 1 To intTblCnt
    strTbl = rsT.Fields("TABLE_NAME").Value
    cmb_ExcleSheets.AddItem strTbl
    'add the following if fields are needed, however they will be listed in the row 0 of the header grid.
    '    Set rsC = cn.OpenSchema(adSchemaColumns, Array(Empty, Empty, strTbl, Empty))
    '    intColCnt = rsC.RecordCount
    '    intColFlds = rsC.Fields.Count
    '    For c = 1 To intColCnt
    '            strCol = rsC.Fields("COLUMN_NAME").Value
    '            cmb_ExcleSheets.AddItem vbTab & vbTab & "Column #" & c & ": " & strCol
    '            cmb_ExcleSheets.AddItem vbTab & vbTab & "--------------------"
    '            For f = 0 To intColFlds - 1
    '                cmb_ExcleSheets.AddItem vbTab & vbTab & rsC.Fields(f).Name & vbTab & rsC.Fields(f).Value
    '            Next
    '            cmb_ExcleSheets.AddItem vbTab & vbTab & "--------------------"
    '            rsC.MoveNext
    '
    '    Next
    '    rsC.Close
    rsT.MoveNext
Next
rsT.Close
cn.Close
    If cmb_ExcleSheets.ListCount > 0 Then
        cmb_ExcleSheets.ListIndex = 0
        If cmb_ExcleSheets.ListCount = 1 Then
            cmb_ExcleSheets.Visible = False
        Else
            cmb_ExcleSheets.Visible = True
        End If
    End If

End Sub


Private Function UpdFieldsList() As String
On Error GoTo errHandler
    Dim str_Fields As String
    Dim i As Integer
    str_Fields = ""
    For i = 0 To fg_header.Cols - 1
        If Trim(fg_header.Cell(flexcpTextDisplay, 1, i)) <> "" Then
            If Len(str_Fields) > 0 Then str_Fields = str_Fields & ","
            str_Fields = str_Fields & Replace(fg_header.Cell(flexcpTextDisplay, 1, i), " ", "_")
        End If
    Next i
    UpdFieldsList = str_Fields
Exit Function
errHandler:
    MsgBox Err.Description, vbOKOnly, "Error"
End Function

Private Function UpdFieldsValues(Row As Long) As String
Dim str_map As String
Dim str_Key As String
Dim str_Field As String
Dim str_Table As String
Dim str_Value As String
Dim intOptionalCond As Integer
Dim strOptionalCond As String
On Error GoTo errHandler
    Dim str_Fields As String
    Dim i As Integer
    str_MAP_TABLES = ""
    str_MAP_WHERE = ""
    str_Fields = ""
    For i = 0 To fg_header.Cols - 1
        If Trim(fg_header.Cell(flexcpTextDisplay, 1, i)) <> "" Then
            str_Value = fg_data.Cell(flexcpTextDisplay, Row, i)
            str_Value = Replace(str_Value, "'", "''")
            If left(str_Value, 1) <> "'" And right(str_Value, 1) <> "'" Then
                str_Value = "'" & str_Value & "'"
            End If
            If Len(str_Fields) > 0 Then str_Fields = str_Fields & ","
            If left(fg_header.TextMatrix(2, i), 4) = "MAP(" Then
            ' deal with mapping in format MAP(TABLE,KEY,FIELD,[extra selection])
                str_map = right(fg_header.TextMatrix(2, i), Len(fg_header.TextMatrix(2, i)) - 4)
                str_Table = Mid(str_map, 1, InStr(4, str_map, ",") - 1)
                str_map = right(str_map, Len(str_map) - Len(str_Table) - 1)
                str_Key = Mid(str_map, 1, InStr(1, str_map, ",") - 1)
                str_map = right(str_map, Len(str_map) - Len(str_Key) - 1)
                intOptionalCond = InStr(1, str_map, ",")
                If intOptionalCond = 0 Then
                    strOptionalCond = ""
                    str_Field = Trim(Mid(str_map, 1, InStr(1, str_map, ")") - 1))
                Else
                    str_Field = Trim(Mid(str_map, 1, InStr(1, str_map, ",") - 1))
                    str_map = right(str_map, Len(str_map) - Len(str_Field) - 1)
                    strOptionalCond = Trim(Mid(str_map, 1, InStr(1, str_map, ")") - 1))
                End If
                
                str_Fields = str_Fields & "MAP_" & Trim(CStr(i)) & "." & str_Field
                str_MAP_TABLES = str_MAP_TABLES & "," & str_Table & " MAP_" & Trim(CStr(i))
                If Len(str_MAP_WHERE) > 0 Then
                    str_MAP_WHERE = str_MAP_WHERE & " AND "
                Else
                    str_MAP_WHERE = " WHERE "
                End If
                str_MAP_WHERE = str_MAP_WHERE & "MAP_" & Trim(CStr(i)) & "." & str_Key & "=" & str_Value
                If Len(strOptionalCond) > 0 Then str_MAP_WHERE = str_MAP_WHERE & " AND MAP_" & Trim(CStr(i)) & "." & Trim(strOptionalCond)
            Else
                str_Fields = str_Fields & str_Value
            End If
        End If
    Next i
    UpdFieldsValues = str_Fields
Exit Function
errHandler:
    MsgBox Err.Description, vbOKOnly, "Error"
End Function

Private Function or_Return_Value(ByVal str_query As String) As String
On Error GoTo errHandler
Dim int_col As Integer
Dim str_ToRet As String
Dim rsPrint As New ADODB.Recordset
str_ToRet = ""

    Set rsPrint = ordb.Execute(str_query)
    If rsPrint.State = adStateOpen Then
        If Not rsPrint.EOF Then
            Do While Not rsPrint.EOF
                str_ToRet = ""
                
                    str_ToRet = rsPrint.Fields(0).Value
                
                rsPrint.MoveNext
            Loop
            If rsPrint.State = adStateOpen Then rsPrint.Close
        End If
    End If
   
    or_Return_Value = str_ToRet
    Exit Function
errHandler:
    MsgBox Err.Description, vbOKOnly, "or_Return_Value"
    or_Return_Value = ""
    If rsPrint.State = adStateOpen Then rsPrint.Close
End Function

Private Function or_Add_Value(ByVal str_query As String) As Boolean
On Error GoTo errHandler
Dim int_col As Integer
Dim str_ToRet As String
Dim lng_raff As Long
Dim rsPrint As New ADODB.Recordset
str_ToRet = ""
lng_raff = 0
    ordb.Execute str_query, lng_raff
    If lng_raff = 1 Then
        or_Add_Value = True
    Else
        or_Add_Value = False
    End If
    
    Exit Function
errHandler:
    MsgBox Err.Description, vbOKOnly, "or_Add_Value"
    or_Add_Value = False
End Function

Private Sub CaseFormatting()
Dim c As Integer
Dim r As Long
Dim str_map As String
Dim str_Key As String
Dim str_Field As String
Dim str_Table As String
Dim str_Value As String

For c = 0 To fg_header.Cols - 1
    If left(fg_header.TextMatrix(2, c), 5) = "CASE(" Then
        str_map = right(fg_header.TextMatrix(2, c), Len(fg_header.TextMatrix(2, c)) - 5)
        str_Table = Mid(str_map, 1, InStr(1, str_map, ",") - 1)
        str_map = right(str_map, Len(str_map) - Len(str_Table) - 1)
        str_Key = Mid(str_map, 1, InStr(1, str_map, ",") - 1)
        str_map = right(str_map, Len(str_map) - Len(str_Key) - 1)
        str_Field = Trim(Mid(str_map, 1, InStr(1, str_map, ")") - 1))
        For r = 0 To fg_data.Rows - 1
            If fg_data.TextMatrix(r, c) = str_Table Then
                fg_data.TextMatrix(r, c) = str_Key
            Else
                fg_data.TextMatrix(r, c) = str_Field
        End If
        Next r

    End If
Next c

Debug.Print
End Sub

