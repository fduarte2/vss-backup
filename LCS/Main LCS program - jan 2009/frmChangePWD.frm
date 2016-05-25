VERSION 5.00
Begin VB.Form frmChangePWD 
   Caption         =   "Change Password"
   ClientHeight    =   5070
   ClientLeft      =   3615
   ClientTop       =   3420
   ClientWidth     =   7650
   LinkTopic       =   "Form1"
   ScaleHeight     =   5070
   ScaleWidth      =   7650
   Begin VB.CommandButton cmdCancel 
      Caption         =   "&Cancel"
      Height          =   375
      Left            =   4200
      TabIndex        =   6
      Top             =   3840
      Width           =   1695
   End
   Begin VB.CommandButton cmdOK 
      Caption         =   "&OK"
      Height          =   375
      Left            =   1320
      TabIndex        =   5
      Top             =   3840
      Width           =   1695
   End
   Begin VB.TextBox txtConfirm 
      Height          =   375
      IMEMode         =   3  'DISABLE
      Left            =   3120
      PasswordChar    =   "*"
      TabIndex        =   4
      Top             =   2880
      Width           =   2535
   End
   Begin VB.TextBox txtPWD 
      Height          =   375
      IMEMode         =   3  'DISABLE
      Left            =   3120
      PasswordChar    =   "*"
      TabIndex        =   2
      Top             =   2040
      Width           =   2535
   End
   Begin VB.Label labTitle 
      Alignment       =   2  'Center
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   735
      Left            =   960
      TabIndex        =   7
      Top             =   0
      Width           =   6495
   End
   Begin VB.Image Image1 
      Height          =   840
      Left            =   0
      Picture         =   "frmChangePWD.frx":0000
      Stretch         =   -1  'True
      Top             =   0
      Width           =   855
   End
   Begin VB.Label labConfirm 
      Caption         =   "Confirm Password:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   1200
      TabIndex        =   3
      Top             =   3000
      Width           =   1695
   End
   Begin VB.Label labPassword 
      Caption         =   "New Password:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   1200
      TabIndex        =   1
      Top             =   2160
      Width           =   1455
   End
   Begin VB.Label Label1 
      Caption         =   "Change Password"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   2640
      TabIndex        =   0
      Top             =   1200
      Width           =   2415
   End
   Begin VB.Line Line1 
      X1              =   0
      X2              =   8520
      Y1              =   840
      Y2              =   840
   End
End
Attribute VB_Name = "frmChangePWD"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdCancel_Click()
    Unload frmChangePWD
    Load frmHiring
    frmHiring.Show
End Sub
Private Sub cmdOK_Click()
    Dim newPWD As String
    Dim conPWD As String
    Dim sqlStmt As String
    Dim rsUser As Object
    
    newPWD = Trim(txtPWD.Text)
    conPWD = Trim(txtConfirm.Text)
    
    'valid password
    If newPWD <> conPWD Then
        MsgBox "New password and confirm password are not same. Please enter again.", vbInformation, "Change Password"
        Exit Sub
    ElseIf newPWD = "" And conPWD = "" Then
        MsgBox "Password can't be blank. Please enter again.", vbInformation, "Change Password"
        Exit Sub
    End If
    
    'OraSession.CommitTrans
    OraSession.BeginTrans
    
    sqlStmt = "SELECT * FROM LCS_USER WHERE USER_ID = '" & UserID & "'"
    Set rsUser = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        If rsUser.RecordCount = 1 Then
            rsUser.Edit
            rsUser.Fields("user_password").Value = conPWD
            rsUser.Fields("expire_date").Value = Date + 91
            
            rsUser.Update
            
            If OraDatabase.LastServerErr = 0 Then
                OraSession.CommitTrans
                MsgBox "Password changed!", vbInformation
                Unload frmChangePWD
                frmChangePWD.Hide
                
                Load frmHiring
                frmHiring.Show
            Else
                OraSession.Rollback
            End If
        End If
    End If

End Sub
Private Sub Form_Load()
    labTitle.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
End Sub
