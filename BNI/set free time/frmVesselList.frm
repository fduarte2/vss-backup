VERSION 5.00
Begin VB.Form frmVesselList 
   Caption         =   "Vessel List"
   ClientHeight    =   4920
   ClientLeft      =   9975
   ClientTop       =   2910
   ClientWidth     =   4245
   LinkTopic       =   "Form1"
   ScaleHeight     =   4920
   ScaleWidth      =   4245
   Begin VB.ListBox lstVesselName 
      Height          =   4350
      Left            =   240
      TabIndex        =   0
      Top             =   240
      Width           =   3615
   End
End
Attribute VB_Name = "frmVesselList"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False

Private Sub Form_Load()
    Call LoadVesselName
End Sub

Private Sub lstVesselName_DblClick()
    ''MsgBox lrNumList.Item(Me.lstVesselName.ListIndex + 1)
    frmSetFreeTime.txtVesNo.Text = Trim(lrNumList.Item(Me.lstVesselName.ListIndex + 1))
    frmSetFreeTime.lblVesselName.Caption = Trim(Me.lstVesselName.Text)
    frmSetFreeTime.txtVesNo.SetFocus
    If (Len(frmSetFreeTime.txtVesNo.Text)) >= 4 Then
        Call RetrieveStartFT(Int(Val(frmSetFreeTime.txtVesNo.Text)))
    End If
    Unload Me
End Sub


Private Sub LoadVesselName()

    Dim i As Integer
    
    For i = 1 To lrNumList.Count
    
     frmVesselList.lstVesselName.AddItem colVesselName(i)
    
    Next i

End Sub
