VERSION 5.00
Object = "{67397AA1-7FB1-11D0-B148-00A0C922E820}#6.0#0"; "MSADODC.OCX"
Object = "{F0D2F211-CCB0-11D0-A316-00AA00688B10}#1.0#0"; "MSDATLST.OCX"
Begin VB.Form frmFilterSetting 
   Caption         =   "TLS-Filter Settings"
   ClientHeight    =   3420
   ClientLeft      =   6960
   ClientTop       =   4740
   ClientWidth     =   5160
   LinkTopic       =   "Form1"
   ScaleHeight     =   3420
   ScaleWidth      =   5160
   Begin VB.Frame Frame2 
      Height          =   735
      Left            =   120
      TabIndex        =   1
      Top             =   2400
      Width           =   4815
      Begin VB.CommandButton cmdCancel 
         Cancel          =   -1  'True
         Caption         =   "Cancel"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   3000
         TabIndex        =   7
         Top             =   240
         Width           =   1215
      End
      Begin VB.CommandButton cmdApply 
         Caption         =   "Apply"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   720
         TabIndex        =   6
         Top             =   240
         Width           =   1215
      End
   End
   Begin VB.Frame Frame1 
      Height          =   2295
      Left            =   120
      TabIndex        =   0
      Top             =   120
      Width           =   4815
      Begin MSAdodcLib.Adodc dcCust 
         Height          =   375
         Left            =   2640
         Top             =   240
         Visible         =   0   'False
         Width           =   1695
         _ExtentX        =   2990
         _ExtentY        =   661
         ConnectMode     =   0
         CursorLocation  =   3
         IsolationLevel  =   -1
         ConnectionTimeout=   15
         CommandTimeout  =   30
         CursorType      =   3
         LockType        =   3
         CommandType     =   1
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
         RecordSource    =   $"frmFilterSetting.frx":0000
         Caption         =   "CUST"
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
      Begin MSDataListLib.DataCombo dcboCust 
         Bindings        =   "frmFilterSetting.frx":0049
         DataField       =   "CUSTOMER_ID"
         DataSource      =   "dcCust"
         Height          =   315
         Left            =   2520
         TabIndex        =   9
         Top             =   1680
         Width           =   2175
         _ExtentX        =   3836
         _ExtentY        =   556
         _Version        =   393216
         ListField       =   "CUSTOMER_ID"
         BoundColumn     =   "CUSTOMER_ID"
         Text            =   ""
      End
      Begin VB.ComboBox cboComGrp 
         Height          =   315
         Left            =   2520
         TabIndex        =   8
         Top             =   1200
         Width           =   2175
      End
      Begin VB.CheckBox chkByCustomer 
         Caption         =   "By Customer Number"
         Height          =   375
         Left            =   480
         TabIndex        =   5
         Top             =   1680
         Width           =   1815
      End
      Begin VB.CheckBox chkByCommodity 
         Caption         =   "By Commodity Group"
         Height          =   375
         Left            =   480
         TabIndex        =   4
         Top             =   1200
         Width           =   1815
      End
      Begin VB.OptionButton optFilterOn 
         Caption         =   "On"
         Height          =   375
         Left            =   120
         TabIndex        =   3
         Top             =   720
         Width           =   1215
      End
      Begin VB.OptionButton optFilterOff 
         Caption         =   "Off (Do not apply filter)"
         Height          =   375
         Left            =   120
         TabIndex        =   2
         Top             =   240
         Width           =   2295
      End
   End
End
Attribute VB_Name = "frmFilterSetting"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit       'Rudy

Private Sub chkByCommodity_Click()
    ''MsgBox chkByCommodity.Value
    If Me.chkByCommodity.Value = 1 Then
        Me.optFilterOn.Value = True
    End If
End Sub

Private Sub chkByCustomer_Click()
    If Me.chkByCustomer.Value = 1 Then
        Me.optFilterOn = True
    End If
End Sub

Private Sub cmdApply_Click()
    
    Call ApplyFilters(optFilterApyTo)
    Unload Me

End Sub

Private Sub cmdCancel_Click()
    Unload Me
End Sub

Private Sub Form_Load()

    Call LoadComGrp
    dcboCust.Text = ""

End Sub

Sub LoadComGrp()

    Dim i As Integer
    ''Dim rs As New ADODB.Recordset
    

    
    For i = 0 To UBound(arrComGrpDef)
    
        Me.cboComGrp.AddItem arrComGrpDef(i).GrpDesc
    
    Next i
    
    
    
'    Set rs = frmDataEntry.dcCust.Recordset
'
'    ''rs.MoveFirst
'
'    For i = 0 To rs.RecordCount - 1
'        rs.AbsolutePosition = i + 1
'        Me.cboCustList.AddItem "" & rs.Fields(0).Value
'        ''rs.AbsolutePosition
'
'    Next i
'
'    Set rs = Nothing
    
    
End Sub

Private Sub optFileterOff_Click()
    
End Sub

Private Sub optFilterOff_Click()
    ''MsgBox optFilterOff.Value
    
    If optFilterOff.Value Then
    
        Me.chkByCommodity.Value = 0
        Me.chkByCustomer.Value = 0
        
    End If
    
    Me.cboComGrp.Text = ""
    Me.dcboCust.Text = ""
    

End Sub




Private Sub optFilterOn_Click()
    If optFilterOn.Value Then
        Me.chkByCommodity.Value = 1
    End If
End Sub
