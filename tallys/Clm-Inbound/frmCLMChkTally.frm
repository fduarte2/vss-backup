VERSION 5.00
Object = "{67397AA1-7FB1-11D0-B148-00A0C922E820}#6.0#0"; "MSADODC.OCX"
Begin VB.Form frmCLMChkTally 
   Caption         =   "Clementine Inbound Tally"
   ClientHeight    =   5745
   ClientLeft      =   5145
   ClientTop       =   4500
   ClientWidth     =   7095
   LinkTopic       =   "Form1"
   ScaleHeight     =   5745
   ScaleWidth      =   7095
   StartUpPosition =   2  'CenterScreen
   Begin VB.Frame Frame3 
      Height          =   975
      Left            =   240
      TabIndex        =   11
      Top             =   4560
      Width           =   6615
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
         Left            =   720
         TabIndex        =   5
         Top             =   240
         Width           =   5175
      End
   End
   Begin VB.Frame Frame2 
      Height          =   1095
      Left            =   240
      TabIndex        =   6
      Top             =   3480
      Width           =   6615
      Begin VB.CommandButton cmdPrint 
         Caption         =   "Print"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   615
         Left            =   720
         TabIndex        =   4
         Top             =   240
         Width           =   5175
      End
   End
   Begin VB.Frame Frame1 
      Height          =   3255
      Left            =   240
      TabIndex        =   3
      Top             =   120
      Width           =   6615
      Begin VB.TextBox txtVslNum 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   3000
         TabIndex        =   2
         Text            =   "440"
         Top             =   2400
         Visible         =   0   'False
         Width           =   2535
      End
      Begin VB.TextBox txtCustNum 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   3000
         TabIndex        =   1
         Top             =   1680
         Width           =   2535
      End
      Begin VB.TextBox txtOrderNum 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   3000
         TabIndex        =   0
         Top             =   960
         Width           =   2535
      End
      Begin MSAdodcLib.Adodc dc 
         Height          =   375
         Left            =   4920
         Top             =   120
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
         Connect         =   "Provider=MSDAORA.1;User ID=sag_owner;Data Source=rf;Persist Security Info=False"
         OLEDBString     =   "Provider=MSDAORA.1;User ID=sag_owner;Data Source=rf;Persist Security Info=False"
         OLEDBFile       =   ""
         DataSourceName  =   ""
         OtherAttributes =   ""
         UserName        =   "sag_owner"
         Password        =   "owner"
         RecordSource    =   "select count(*) from cargo_activity"
         Caption         =   "dc"
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
      Begin VB.Label Label4 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "Clementine In-Bound Tally"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H000000FF&
         Height          =   495
         Left            =   240
         TabIndex        =   10
         Top             =   240
         Width           =   6135
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         Caption         =   "Vessel (LR) Number"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   240
         Left            =   360
         TabIndex        =   9
         Top             =   2520
         Visible         =   0   'False
         Width           =   2100
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         Caption         =   "Customer Number"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   240
         Left            =   360
         TabIndex        =   8
         Top             =   1800
         Width           =   1860
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         Caption         =   "Order Number"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   240
         Left            =   360
         TabIndex        =   7
         Top             =   960
         Width           =   1470
      End
   End
End
Attribute VB_Name = "frmCLMChkTally"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False


Private Sub cmdClose_Click()
    End
End Sub

Private Sub cmdPrint_Click()

On Error GoTo ErrHandler:
    
    Dim i As Integer

'    Call setupData
'
'    For i = 1 To TotalPage
'        Call PrintTally(i)
'    Next i

    order = UCase(Trim(Me.txtOrderNum.Text))
    cusID = Trim(Me.txtCustNum.Text)
    'vslID = Trim(Me.txtVslNum.Text)
'    vslID = Trim(Me.txtCustNum.Text)
    vslID = UCase(Trim(Me.txtOrderNum.Text))
    'vslID = "440"
    'vslID = "123456"
    
    '' Step 1 -Get Vessel Name
    ''Call GetVslName
    
    '' Step 2-Get Customer Name
    Call GetCusName
    
    '' Step 3 -Get Order Detail
    Call GetOrderDetail
    
    '' Step 4-Get Start/End Time
    Call GetStartEndTime
    
    '' Step 5:  Checker name (added Adam Walter, 11/21/2007)
'    Call GetCheckName
    
    TotalPage = Int(totalPlt / RecPerPage) + 1
    
    For i = 1 To TotalPage
        Call PrintTally(i)
    Next i
    
    Call ClearAllFields
    
    
ErrHandler:
    
    If Err.Number <> 0 Then
    
        MsgBox Err.Description
        Call ClearAllFields
        
    
    End If
    

End Sub

Private Sub Form_Load()
    Call iniVariables
    
End Sub


