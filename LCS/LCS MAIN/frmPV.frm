VERSION 5.00
Begin VB.Form frmPV 
   Caption         =   "List"
   ClientHeight    =   4485
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   4650
   LinkTopic       =   "Form1"
   ScaleHeight     =   4485
   ScaleWidth      =   4650
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdCancel 
      Cancel          =   -1  'True
      Caption         =   "Cancel"
      Default         =   -1  'True
      Height          =   315
      Left            =   2370
      TabIndex        =   2
      Top             =   4080
      Width           =   1095
   End
   Begin VB.CommandButton cmdOK 
      Caption         =   "OK"
      Height          =   315
      Left            =   1200
      TabIndex        =   1
      Top             =   4080
      Width           =   1095
   End
   Begin VB.ListBox lstPV 
      Height          =   3960
      Left            =   90
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
Private Sub cmdOK_Click()

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

    Call cmdOK_Click
    
End Sub


