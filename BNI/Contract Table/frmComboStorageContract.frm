VERSION 5.00
Object = "{CDE57A40-8B86-11D0-B3C6-00A0C90AEA82}#1.0#0"; "MSDATGRD.OCX"
Object = "{67397AA1-7FB1-11D0-B148-00A0C922E820}#6.0#0"; "MSADODC.OCX"
Begin VB.Form frmComboStorageContract 
   Caption         =   "Storage Rate Table"
   ClientHeight    =   10965
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   11055
   LinkTopic       =   "Form1"
   ScaleHeight     =   10965
   ScaleWidth      =   11055
   StartUpPosition =   2  'CenterScreen
   Begin VB.Frame Frame3 
      Height          =   975
      Left            =   120
      TabIndex        =   2
      Top             =   9600
      Width           =   10695
      Begin VB.CommandButton cmdNewContract 
         Caption         =   "Add New Contract"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   4080
         TabIndex        =   5
         Top             =   240
         Width           =   3015
      End
      Begin VB.CommandButton cmdSaveChanges 
         Caption         =   "Save Changes"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   240
         TabIndex        =   4
         Top             =   240
         Width           =   3615
      End
      Begin VB.CommandButton cmdClose 
         Caption         =   "Close"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   7200
         TabIndex        =   3
         Top             =   240
         Width           =   3255
      End
   End
   Begin VB.Frame Frame1 
      Height          =   9015
      Left            =   120
      TabIndex        =   0
      Top             =   480
      Width           =   10695
      Begin MSDataGridLib.DataGrid grdContract 
         Bindings        =   "frmComboStorageContract.frx":0000
         Height          =   8295
         Left            =   240
         TabIndex        =   1
         Top             =   360
         Width           =   10215
         _ExtentX        =   18018
         _ExtentY        =   14631
         _Version        =   393216
         AllowUpdate     =   -1  'True
         AllowArrows     =   -1  'True
         HeadLines       =   1
         RowHeight       =   19
         TabAction       =   1
         FormatLocked    =   -1  'True
         AllowDelete     =   -1  'True
         BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Caption         =   "Storage Rates"
         ColumnCount     =   4
         BeginProperty Column00 
            DataField       =   "CONTRACTID"
            Caption         =   "CONTRACTID"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column01 
            DataField       =   "DESCRIPTION"
            Caption         =   "DESCRIPTION"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column02 
            DataField       =   "STARTDATE"
            Caption         =   "STARTDATE"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         BeginProperty Column03 
            DataField       =   "ENDDATE"
            Caption         =   "ENDDATE"
            BeginProperty DataFormat {6D835690-900B-11D0-9484-00A0C91110ED} 
               Type            =   0
               Format          =   ""
               HaveTrueFalseNull=   0
               FirstDayOfWeek  =   0
               FirstWeekOfYear =   0
               LCID            =   1033
               SubFormatType   =   0
            EndProperty
         EndProperty
         SplitCount      =   1
         BeginProperty Split0 
            BeginProperty Column00 
               Locked          =   -1  'True
               ColumnWidth     =   1590.236
            EndProperty
            BeginProperty Column01 
               ColumnWidth     =   2429.858
            EndProperty
            BeginProperty Column02 
               ColumnWidth     =   2429.858
            EndProperty
            BeginProperty Column03 
               ColumnWidth     =   2429.858
            EndProperty
         EndProperty
      End
      Begin MSAdodcLib.Adodc dcContract 
         Height          =   375
         Left            =   6720
         Top             =   8640
         Visible         =   0   'False
         Width           =   1575
         _ExtentX        =   2778
         _ExtentY        =   661
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
         Connect         =   "Provider=MSDAORA.1;User ID=sag_owner;Data Source=bni;Persist Security Info=False"
         OLEDBString     =   "Provider=MSDAORA.1;User ID=sag_owner;Data Source=bni;Persist Security Info=False"
         OLEDBFile       =   ""
         DataSourceName  =   ""
         OtherAttributes =   ""
         UserName        =   "sag_owner"
         Password        =   "sag"
         RecordSource    =   "select * from CONTRACT order by CONTRACTID"
         Caption         =   "vsl"
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
   End
End
Attribute VB_Name = "frmComboStorageContract"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private OldColValue As Variant

Private Sub cmdNewContract_Click()
    Dim NewContract As New frmAddContract
    NewContract.Show 1
    Me.dcContract.Refresh
    Me.grdContract.Refresh

End Sub

Private Sub Form_Load()
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
    'Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)

    SqlStmt = "SELECT COUNT(*) THE_COUNT FROM PROGRAM_AUTHORIZATION WHERE PROGRAM_NAME = 'CONTRACTS' AND USER_NAME = '" & LCase$(Environ$("USERNAME")) & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value <= 0 Then
        MsgBox "User " & LCase$(Environ$("USERNAME")) & " is not authorized to update storage rates."
        Unload Me
    End If

End Sub

Private Sub grdContract_AfterColUpdate(ByVal intColIndex As Integer)
    ' Yes, I realize that doing the check after the update is kinda offbase, but I have little choice...
    ' I don't know how to find the "newvalue" in the BeforeColUpdate function, and I can't search for
    ' Answers on the web since all the useful sites require fees or are blocked by St. Bernard.
    '  The only legit answer I could get to was to just "revert" any bad data after-the-fact.
    
    Dim UpdatedValue As Variant
    UpdatedValue = grdContract.Columns(intColIndex).Text
    
    If ValidateUpdate(UpdatedValue, intColIndex) = False Then
        '  new input failed to "pass inspection", revert to old
        grdContract.Columns(intColIndex).Value = OldColValue
    Else
        ' new input passed inspection, update related fields
'        grdContract.Columns(3).Value = LCase$(Environ$("USERNAME"))
'        grdContract.Columns(2).Value = Format(Now, G)
    End If
End Sub


Private Sub cmdClose_Click()
    Unload Me
End Sub

Private Sub cmdSaveChanges_Click()
    
    Me.dcContract.Recordset.Update
    Me.dcContract.Refresh
    Me.grdContract.Refresh
    
End Sub

Private Sub grdContract_BeforeColUpdate(ByVal ColIndex As Integer, OldValue As Variant, Cancel As Integer)
    'function passes old value to a pseudo-global variable to use for data checking in "after update"
    OldColValue = OldValue
    
'    If ColIndex = 4 Then
'        OldColValue = "customer" & OldValue
'    End If
'
'    If ColIndex <> 4 Then
'        OldColValue = "not a customer" & OldValue
''        Cancel = True
'    End If


End Sub

Private Function ValidateUpdate(UpdatedValue As Variant, intColIndex As Integer) As Boolean

    ' each column has a different "inspection".  I'm not big on VB's switch-case statements,
    ' so massive If's it is.
    
'    If intColIndex = 0 Then ' Contract ID
'        ValidateUpdate = ValidateContract(UpdatedValue)
'        Exit Function
'    End If
    
    If intColIndex = 1 Then ' test-description
        ValidateUpdate = ValidateDescription(UpdatedValue)
        Exit Function
    End If

    If intColIndex = 2 Then ' start date
        ValidateUpdate = ValidateStartDate(UpdatedValue)
        Exit Function
    End If

    If intColIndex = 3 Then ' end date
        ValidateUpdate = ValidateEndDate(UpdatedValue)
        Exit Function
    End If
    
    
    ValidateUpdate = True
            
End Function


'Private Sub grdContract_AfterInsert()
'    'Dim nResult As Integer
'
'    MsgBox grdContract.Columns(4).Value
''    If nResult = vbNo Then
''        Cancel = True    'cancel add
''    End If
'End Sub


Private Sub grdContract_HeadClick(ByVal ColIndex As Integer)   ' Sort on the clicked column.
    Dim rs As ADODB.Recordset
    Set rs = dcContract.Recordset
    If rs.Sort <> grdContract.Columns(ColIndex).DataField & " ASC" Then
        ' Sort in ascending order; this block is executed if the
        ' data isn't sorted, is sorted on a different field,
        ' or is sorted in descending order.
        rs.Sort = grdContract.Columns(ColIndex).DataField & " ASC"
    Else
        ' Sort in descending order.
        rs.Sort = grdContract.Columns(ColIndex).DataField & " DESC"
    End If
    ' No need to refresh the contents of the DataGrid.
End Sub






