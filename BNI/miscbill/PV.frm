VERSION 5.00
Begin VB.Form frmPV 
   Caption         =   "List"
   ClientHeight    =   4470
   ClientLeft      =   5220
   ClientTop       =   3300
   ClientWidth     =   4500
   LinkTopic       =   "Form1"
   ScaleHeight     =   4470
   ScaleWidth      =   4500
   Begin VB.CommandButton cmdCancel 
      Cancel          =   -1  'True
      Caption         =   "Cancel"
      Default         =   -1  'True
      Height          =   315
      Left            =   2303
      TabIndex        =   2
      Top             =   4080
      Width           =   1095
   End
   Begin VB.CommandButton cmdOK 
      Caption         =   "OK"
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
