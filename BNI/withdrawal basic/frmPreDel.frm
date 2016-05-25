VERSION 5.00
Begin VB.Form frmPreDel 
   BackColor       =   &H00FFFFC0&
   Caption         =   "Pre-Select Delete"
   ClientHeight    =   1605
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   4680
   LinkTopic       =   "Form1"
   ScaleHeight     =   1605
   ScaleWidth      =   4680
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdpassword 
      Caption         =   "Proceed"
      Height          =   345
      Left            =   1200
      TabIndex        =   2
      Top             =   1080
      Width           =   2085
   End
   Begin VB.TextBox txtDeliveryNum 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2520
      MaxLength       =   10
      TabIndex        =   1
      Top             =   460
      Width           =   1575
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "DELIVERY NUMBER  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   120
      TabIndex        =   0
      Top             =   480
      Width           =   1905
   End
End
Attribute VB_Name = "frmPreDel"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private Sub Form_Load()
    txtDeliveryNum.Text = ""

End Sub

Private Sub cmdpassword_Click()
    'txtDeliveryNum
    
    gsSqlStmt = "SELECT COUNT(*) THE_COUNT FROM CARGO_DELIVERY CD, CARGO_TRACKING CT " & _
                " Where CD.DELIVERY_NUM = " & txtDeliveryNum & " And CD.LOT_NUM = CT.LOT_NUM "
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value <= 0 Then
        MsgBox "delivery# " & txtDeliveryNum & " is not found in system."
    Else
        sWithdrawalDeleteNum = txtDeliveryNum
        Me.Hide
        txtDeliveryNum = ""
        frmPassw.Show 1
        
        Me.Show
    End If
                
End Sub
