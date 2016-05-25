VERSION 5.00
Begin VB.Form frmPV 
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "List"
   ClientHeight    =   4470
   ClientLeft      =   5205
   ClientTop       =   3285
   ClientWidth     =   4500
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   4470
   ScaleWidth      =   4500
   Begin VB.CommandButton cmdCancel 
      Cancel          =   -1  'True
      Caption         =   "&Cancel"
      Default         =   -1  'True
      Height          =   315
      Left            =   2303
      TabIndex        =   2
      Top             =   4080
      Width           =   1095
   End
   Begin VB.CommandButton cmdOK 
      Caption         =   "&Ok"
      Height          =   315
      Left            =   1133
      TabIndex        =   1
      Top             =   4080
      Width           =   1095
   End
   Begin VB.ListBox lstPV 
      Height          =   3960
      Left            =   30
      TabIndex        =   0
      Top             =   60
      Width           =   4425
   End
End
Attribute VB_Name = "frmPV"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdCancel_Click()
    gsPVItem = ""
    Unload Me
End Sub


Private Sub cmdOk_Click()
    If lstPV.ListIndex >= 0 Then
        If lstPV.Selected(lstPV.ListIndex) = True Then
            gsPVItem = lstPV.List(lstPV.ListIndex)
            Unload Me
        Else
            MsgBox "Please select an item from the list.", vbInformation, "Select"
        End If
    Else
        MsgBox "No items found in the list.", vbInformation, "Select"
    End If
End Sub

Private Sub lstPV_DblClick()
    cmdOk.Value = True
End Sub


