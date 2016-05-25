VERSION 5.00
Begin VB.Form frmSFLTReason 
   Caption         =   "Option Explicit"
   ClientHeight    =   4875
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   5100
   LinkTopic       =   "Form1"
   ScaleHeight     =   4875
   ScaleWidth      =   5100
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdOK 
      Caption         =   "OK"
      Height          =   315
      Left            =   690
      TabIndex        =   2
      Top             =   4020
      Width           =   1365
   End
   Begin VB.CommandButton cmdCancel 
      Caption         =   "Cancel"
      Height          =   315
      Left            =   2910
      TabIndex        =   1
      Top             =   4020
      Width           =   1455
   End
   Begin VB.TextBox reasonTxt 
      Height          =   2565
      Left            =   270
      TabIndex        =   0
      Top             =   1350
      Width           =   4605
   End
   Begin VB.Label txtLabel 
      BackStyle       =   0  'Transparent
      Caption         =   "Explain"
      Height          =   1125
      Left            =   270
      TabIndex        =   3
      Top             =   90
      Width           =   4575
   End
End
Attribute VB_Name = "frmSFLTReason"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit          '5/2/2007 HD2759 Rudy:

Private Sub cmdCancel_Click()
    gsReason = ""
    Unload Me
End Sub

Private Sub cmdOK_Click()
    gsReason = ""
    
    If Trim(reasonTxt.Text) <> "" Then
        gsReason = " " & CStr(Date) & ": " & reasonTxt.Text
        Unload Me
    Else
        MsgBox "Please specify your reason before saving the changes.", vbInformation, "Fill In Your Reason"
    End If
   
End Sub

