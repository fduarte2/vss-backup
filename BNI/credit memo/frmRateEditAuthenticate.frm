VERSION 5.00
Begin VB.Form frmRateEditAuthenticate 
   Caption         =   "Rate-Edit Authentication"
   ClientHeight    =   3000
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   4140
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   ScaleHeight     =   3000
   ScaleWidth      =   4140
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdCancel 
      Caption         =   "Cancel"
      Height          =   375
      Left            =   2168
      TabIndex        =   5
      Top             =   2010
      Width           =   1215
   End
   Begin VB.CommandButton cmdOk 
      Caption         =   "OK"
      Default         =   -1  'True
      Height          =   375
      Left            =   758
      TabIndex        =   4
      Top             =   2010
      Width           =   1215
   End
   Begin VB.TextBox txtPwd 
      Height          =   285
      IMEMode         =   3  'DISABLE
      Left            =   1988
      PasswordChar    =   "*"
      TabIndex        =   3
      Top             =   960
      Width           =   1575
   End
   Begin VB.TextBox txtUserName 
      Height          =   285
      Left            =   2003
      TabIndex        =   1
      Top             =   450
      Width           =   1575
   End
   Begin VB.Label lblPwd 
      AutoSize        =   -1  'True
      Caption         =   "Password:"
      Height          =   195
      Left            =   578
      TabIndex        =   2
      Top             =   1005
      Width           =   735
   End
   Begin VB.Label lblUserName 
      AutoSize        =   -1  'True
      Caption         =   "User Name:"
      Height          =   195
      Left            =   563
      TabIndex        =   0
      Top             =   495
      Width           =   840
   End
End
Attribute VB_Name = "frmRateEditAuthenticate"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit       '2258 4/2/2007 Rudy:

Public fbValidUser As Boolean
Public flUser As Long


Private Sub cmdCancel_Click()
    fbValidUser = False
    Me.Hide
End Sub

Private Sub cmdOk_Click()
If VerifyUser() Then
    fbValidUser = True
Else
    fbValidUser = False
End If
Me.Hide
End Sub

Public Function VerifyUser() As Boolean
Dim OraSess As Object
Dim OraDb As Object
Dim OraDynaset As Object
Dim sSql As String
Dim sEncPwd As String

  'encrypt pwd
  sEncPwd = txtPwd.Text
  
  'find the username and pwd combination from the table
  Set OraSess = CreateObject("OracleInProcServer.XOraSession")
  Set OraDb = OraSess.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
  'Set OraDb = OraSess.OpenDatabase("BNI.DEV", "SAG_OWNER/SAG_DEV", 0&)   '2258 4/2/2007 Rudy: TEMP original above
  
  sSql = "SELECT * FROM xxRateEditUsers WHERE UserName = '" & txtUserName.Text & "' "
  sSql = sSql & " AND Password = '" & sEncPwd & "'"
  Set OraDynaset = OraDb.CreateDynaset(sSql, 0&)
  If OraDynaset.eof And OraDynaset.BOF Then   'not found
    VerifyUser = False
  Else
    flUser = OraDynaset.fields("UserID").Value
    VerifyUser = True
  End If

End Function

Private Sub Form_Load()
CenterForm Me
End Sub
