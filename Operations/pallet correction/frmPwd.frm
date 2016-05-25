VERSION 5.00
Begin VB.Form frmPwd 
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "                        OVERWRITE PASSWORD"
   ClientHeight    =   1770
   ClientLeft      =   4890
   ClientTop       =   2490
   ClientWidth     =   5220
   ControlBox      =   0   'False
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   1770
   ScaleWidth      =   5220
   Begin VB.Frame Frame1 
      BackColor       =   &H00FFFFC0&
      Height          =   1575
      Left            =   120
      TabIndex        =   3
      Top             =   0
      Width           =   4935
      Begin VB.CommandButton cmdCancel 
         Cancel          =   -1  'True
         Caption         =   "&Exit"
         Height          =   375
         Left            =   2640
         TabIndex        =   2
         Top             =   1080
         Width           =   855
      End
      Begin VB.CommandButton cmdOk 
         Caption         =   "&Ok"
         Height          =   375
         Left            =   1440
         TabIndex        =   1
         Top             =   1080
         Width           =   855
      End
      Begin VB.TextBox txtPwd 
         Height          =   300
         IMEMode         =   3  'DISABLE
         Left            =   2520
         PasswordChar    =   "*"
         TabIndex        =   0
         Top             =   480
         Width           =   1815
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         BackStyle       =   0  'Transparent
         Caption         =   "PASSWORD  :"
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
         Left            =   840
         TabIndex        =   4
         Top             =   525
         Width           =   1260
      End
   End
End
Attribute VB_Name = "frmPwd"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
'   * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
'   * Module Name    : PltCorrection                                                        *
'   * Author         : PKA                                                                  *
'   * Date           : 23/03/2000                                                           *
'   * Description    : Module from Pallet Correction password .This module enables to       *
'   *                  overwrite qty  change even if the validation for the changes fails.  *
'   *                  This require the valid password to enter to restrict the overwrite   *
'   *                  only to the authorised persons.                                      *
'   * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

Option Explicit
Dim ipwd As Integer

Private Sub cmdCancel_Click()
    Unload Me
End Sub

Private Sub cmdOk_Click()

If UCase(txtPwd) <> UCase("Pltcor") Then
    If ipwd < 3 Then
        MsgBox "Invalid Password", vbInformation + vbExclamation, "OVERWRITE PASSWORD"
        txtPwd = ""
        ipwd = ipwd + 1
        txtPwd.SetFocus
        Exit Sub
    ElseIf ipwd = 3 Then 'If a user enters three times the wrong password
        MsgBox "Invalid Password.Please contact System Administrator", vbInformation + vbCritical, "OVERWRITE PASSWORD"
        ipwd = 0
        Unload Me
        Exit Sub
    End If
Else
    bPwd = True
    sPwd = txtPwd
    Unload Me
End If
End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    bPwd = False
    sPwd = "Pltcor"
    ipwd = 0
End Sub


