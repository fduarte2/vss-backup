VERSION 5.00
Object = "{CDE57A40-8B86-11D0-B3C6-00A0C90AEA82}#1.0#0"; "MSDATGRD.OCX"
Object = "{67397AA1-7FB1-11D0-B148-00A0C922E820}#6.0#0"; "MSADODC.OCX"
Begin VB.Form frmComboStorage 
   Caption         =   "Storage Rate Table"
   ClientHeight    =   10965
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   14595
   LinkTopic       =   "Form1"
   ScaleHeight     =   10965
   ScaleWidth      =   14595
   StartUpPosition =   2  'CenterScreen
   Begin VB.Frame Frame3 
      Height          =   1215
      Left            =   120
      TabIndex        =   2
      Top             =   9600
      Width           =   13215
      Begin VB.OptionButton radioPrefill 
         Caption         =   "Option1"
         Height          =   255
         Index           =   1
         Left            =   8640
         TabIndex        =   9
         Top             =   840
         Width           =   255
      End
      Begin VB.OptionButton radioPrefill 
         Caption         =   "Option1"
         Height          =   255
         Index           =   0
         Left            =   5040
         TabIndex        =   6
         Top             =   840
         Value           =   -1  'True
         Width           =   255
      End
      Begin VB.CommandButton cmdNewRate 
         Caption         =   "Add New Rate"
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
         Left            =   5400
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
         Left            =   9720
         TabIndex        =   3
         Top             =   240
         Width           =   3255
      End
      Begin VB.Label Label2 
         Alignment       =   1  'Right Justify
         Caption         =   "Prefill With Chosen Row"
         Height          =   255
         Left            =   6720
         TabIndex        =   8
         Top             =   840
         Width           =   1815
      End
      Begin VB.Label Label1 
         Caption         =   "Blank Record"
         Height          =   255
         Left            =   5400
         TabIndex        =   7
         Top             =   840
         Width           =   1215
      End
   End
   Begin VB.Frame Frame1 
      Height          =   9015
      Left            =   120
      TabIndex        =   0
      Top             =   480
      Width           =   14415
      Begin MSDataGridLib.DataGrid grdRate 
         Bindings        =   "frmComboStorage.frx":0000
         Height          =   8295
         Left            =   240
         TabIndex        =   1
         Top             =   360
         Width           =   14055
         _ExtentX        =   24791
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
         ColumnCount     =   27
         BeginProperty Column00 
            DataField       =   "ROW_NUM"
            Caption         =   "ROW_NUM"
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
         BeginProperty Column02 
            DataField       =   "DATEENTERED"
            Caption         =   "DATEENTERED"
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
            DataField       =   "ENTEREDBY"
            Caption         =   "ENTEREDBY"
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
         BeginProperty Column04 
            DataField       =   "CUSTOMERID"
            Caption         =   "CUSTOMERID"
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
         BeginProperty Column05 
            DataField       =   "COMMODITYCODE"
            Caption         =   "COMMODITYCODE"
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
         BeginProperty Column06 
            DataField       =   "RATEPRIORITY"
            Caption         =   "RATEPRIORITY"
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
         BeginProperty Column07 
            DataField       =   "FRSHIPPINGLINE"
            Caption         =   "FRSHIPPINGLINE"
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
         BeginProperty Column08 
            DataField       =   "TOSHIPPINGLINE"
            Caption         =   "TOSHIPPINGLINE"
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
         BeginProperty Column09 
            DataField       =   "ARRIVALNUMBER"
            Caption         =   "ARRIVALNUMBER"
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
         BeginProperty Column10 
            DataField       =   "ARRIVALTYPE"
            Caption         =   "ARRIVALTYPE"
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
         BeginProperty Column11 
            DataField       =   "FREEDAYS"
            Caption         =   "FREEDAYS"
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
         BeginProperty Column12 
            DataField       =   "WEEKENDS"
            Caption         =   "WEEKENDS"
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
         BeginProperty Column13 
            DataField       =   "HOLIDAYS"
            Caption         =   "HOLIDAYS"
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
         BeginProperty Column14 
            DataField       =   "BILLDURATION"
            Caption         =   "BILLDURATION"
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
         BeginProperty Column15 
            DataField       =   "BILLDURATIONUNIT"
            Caption         =   "BILLDURATIONUNIT"
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
         BeginProperty Column16 
            DataField       =   "RATESTARTDATE"
            Caption         =   "RATESTARTDATE"
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
         BeginProperty Column17 
            DataField       =   "RATE"
            Caption         =   "RATE"
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
         BeginProperty Column18 
            DataField       =   "SERVICECODE"
            Caption         =   "SERVICECODE"
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
         BeginProperty Column19 
            DataField       =   "UNIT"
            Caption         =   "UNIT"
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
         BeginProperty Column20 
            DataField       =   "STACKING"
            Caption         =   "STACKING"
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
         BeginProperty Column21 
            DataField       =   "WAREHOUSE"
            Caption         =   "WAREHOUSE"
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
         BeginProperty Column22 
            DataField       =   "BOX"
            Caption         =   "BOX"
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
         BeginProperty Column23 
            DataField       =   "BILLTOCUSTOMER"
            Caption         =   "BILLTOCUSTOMER"
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
         BeginProperty Column24 
            DataField       =   "XFRDAYCREDIT"
            Caption         =   "XFRDAYCREDIT"
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
         BeginProperty Column25 
            DataField       =   "SCANNEDORUNSCANNED"
            Caption         =   "SCANNEDORUNSCANNED"
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
         BeginProperty Column26 
            DataField       =   "SPECIALRETURN"
            Caption         =   "SPECIALRETURN"
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
               ColumnWidth     =   1590.236
            EndProperty
            BeginProperty Column01 
               ColumnWidth     =   1590.236
            EndProperty
            BeginProperty Column02 
               ColumnWidth     =   2429.858
            EndProperty
            BeginProperty Column03 
               ColumnWidth     =   2429.858
            EndProperty
            BeginProperty Column04 
               ColumnWidth     =   1275.024
            EndProperty
            BeginProperty Column05 
            EndProperty
            BeginProperty Column06 
               ColumnWidth     =   1275.024
            EndProperty
            BeginProperty Column07 
               ColumnWidth     =   1409.953
            EndProperty
            BeginProperty Column08 
               ColumnWidth     =   1425.26
            EndProperty
            BeginProperty Column09 
               ColumnWidth     =   2429.858
            EndProperty
            BeginProperty Column10 
               ColumnWidth     =   1184.882
            EndProperty
            BeginProperty Column11 
               ColumnWidth     =   959.811
            EndProperty
            BeginProperty Column12 
               ColumnWidth     =   1005.165
            EndProperty
            BeginProperty Column13 
               ColumnWidth     =   884.976
            EndProperty
            BeginProperty Column14 
               ColumnWidth     =   1260.284
            EndProperty
            BeginProperty Column15 
               ColumnWidth     =   1649.764
            EndProperty
            BeginProperty Column16 
               ColumnWidth     =   1484.787
            EndProperty
            BeginProperty Column17 
               ColumnWidth     =   1379.906
            EndProperty
            BeginProperty Column18 
               ColumnWidth     =   1275.024
            EndProperty
            BeginProperty Column19 
               ColumnWidth     =   1065.26
            EndProperty
            BeginProperty Column20 
               ColumnWidth     =   884.976
            EndProperty
            BeginProperty Column21 
               ColumnWidth     =   1140.095
            EndProperty
            BeginProperty Column22 
               ColumnWidth     =   959.811
            EndProperty
            BeginProperty Column23 
               ColumnWidth     =   1544.882
            EndProperty
            BeginProperty Column24 
               ColumnWidth     =   1319.811
            EndProperty
            BeginProperty Column25 
               ColumnWidth     =   2115.213
            EndProperty
            BeginProperty Column26 
               ColumnWidth     =   1590.236
            EndProperty
         EndProperty
      End
      Begin MSAdodcLib.Adodc dcRate 
         Height          =   375
         Left            =   9840
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
         RecordSource    =   "select * from RATE order by ROW_NUM"
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
Attribute VB_Name = "frmComboStorage"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private OldColValue As Variant

Private Sub cmdNewRate_Click()
    Dim NewRate As New frmAddRate
    If radioPrefill(0) = True Then
        NewRate.PreLoad = "none"
    Else
        NewRate.PreLoad = grdRate.Columns(0).Value
    End If
    NewRate.Show 1
    Me.dcRate.Refresh
    Me.grdRate.Refresh

End Sub

Private Sub Form_Load()
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
    'Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)

'    SqlStmt = "SELECT COUNT(*) THE_COUNT FROM PROGRAM_AUTHORIZATION WHERE PROGRAM_NAME = 'STORAGE_RATES' AND USER_NAME = '" & LCase$(Environ$("USERNAME")) & "'"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value <= 0 Then
'        MsgBox "User " & LCase$(Environ$("USERNAME")) & " is not authorized to update storage rates."
'        Unload Me
'    End If

End Sub

Private Sub grdRate_AfterColUpdate(ByVal intColIndex As Integer)
    ' Yes, I realize that doing the check after the update is kinda offbase, but I have little choice...
    ' I don't know how to find the "newvalue" in the BeforeColUpdate function, and I can't search for
    ' Answers on the web since all the useful sites require fees or are blocked by St. Bernard.
    '  The only legit answer I could get to was to just "revert" any bad data after-the-fact.
    
    Dim UpdatedValue As Variant
    UpdatedValue = grdRate.Columns(intColIndex).Text
    
    If ValidateUpdate(UpdatedValue, intColIndex) = False Then
        '  new input failed to "pass inspection", revert to old
        grdRate.Columns(intColIndex).Value = OldColValue
    Else
        ' new input passed inspection, update related fields
        grdRate.Columns(3).Value = LCase$(Environ$("USERNAME"))
        grdRate.Columns(2).Value = Format(Now, G)
    End If
End Sub


Private Sub cmdClose_Click()
    Unload Me
End Sub

Private Sub cmdSaveChanges_Click()
    
    Me.dcRate.Recordset.Update
    Me.dcRate.Refresh
    Me.grdRate.Refresh
    
End Sub

Private Sub grdRate_BeforeColUpdate(ByVal ColIndex As Integer, OldValue As Variant, Cancel As Integer)
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
    
    If intColIndex = 1 Then ' Contract ID
        ValidateUpdate = ValidateContract(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 4 Then ' customer ID
        ValidateUpdate = ValidateCustomer(UpdatedValue)
        Exit Function
    End If

    If intColIndex = 5 Then ' commodity ID
        ValidateUpdate = ValidateCommodity(UpdatedValue)
        Exit Function
    End If

    If intColIndex = 6 Then ' rate priority
        ValidateUpdate = ValidateRatePriority(UpdatedValue)
        Exit Function
    End If

    If intColIndex = 10 Then ' Arrival Type (ship, truck, etc)
        ValidateUpdate = ValidateArvType(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 11 Then ' Free Days
        ValidateUpdate = ValidateFreeDays(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 12 Then ' weekends
        ValidateUpdate = ValidateWeekends(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 13 Then ' holidays
        ValidateUpdate = ValidateHolidays(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 14 Then ' billing duration
        ValidateUpdate = ValidateDuration(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 15 Then ' billing duration unit
        ValidateUpdate = ValidateDurationUnit(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 16 Then ' rate start day
        ValidateUpdate = ValidateRateStartDate(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 17 Then ' rate
        ValidateUpdate = ValidateRate(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 19 Then ' billunit
        ValidateUpdate = ValidateUnit(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 24 Then ' transfer credit
        ValidateUpdate = ValidateXFERCredit(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 25 Then ' RF or BNI bills?
        ValidateUpdate = ValidateScanned(UpdatedValue)
        Exit Function
    End If
    
    If intColIndex = 26 Then ' any special return type to trigger this bill?
        ValidateUpdate = ValidateSpecialReturn(UpdatedValue)
        Exit Function
    End If
    
    
    
    ValidateUpdate = True
            
End Function


'Private Sub grdRate_AfterInsert()
'    'Dim nResult As Integer
'
'    MsgBox grdRate.Columns(4).Value
''    If nResult = vbNo Then
''        Cancel = True    'cancel add
''    End If
'End Sub


Private Sub grdRate_HeadClick(ByVal ColIndex As Integer)   ' Sort on the clicked column.
    Dim rs As ADODB.Recordset
    Set rs = dcRate.Recordset
    If rs.Sort <> grdRate.Columns(ColIndex).DataField & " ASC" Then
        ' Sort in ascending order; this block is executed if the
        ' data isn't sorted, is sorted on a different field,
        ' or is sorted in descending order.
        rs.Sort = grdRate.Columns(ColIndex).DataField & " ASC"
    Else
        ' Sort in descending order.
        rs.Sort = grdRate.Columns(ColIndex).DataField & " DESC"
    End If
    ' No need to refresh the contents of the DataGrid.
End Sub






