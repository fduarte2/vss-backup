VERSION 5.00
Begin VB.Form frmPVps 
   Caption         =   "Select Customer"
   ClientHeight    =   4320
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   5130
   ControlBox      =   0   'False
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   ScaleHeight     =   4320
   ScaleWidth      =   5130
   StartUpPosition =   3  'Windows Default
   Begin VB.TextBox txtDisp 
      Height          =   285
      Left            =   180
      TabIndex        =   0
      Top             =   450
      Width           =   4755
   End
   Begin VB.CommandButton cmdSelect 
      Caption         =   "&Select"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3690
      TabIndex        =   2
      Top             =   3690
      Width           =   1245
   End
   Begin VB.ListBox lstDisp 
      Height          =   2595
      Left            =   180
      TabIndex        =   1
      Top             =   810
      Width           =   4755
   End
   Begin VB.Label Label1 
      Caption         =   "To find a Customer, Type in the Customer Name"
      Height          =   255
      Left            =   180
      TabIndex        =   3
      Top             =   90
      Width           =   4755
   End
End
Attribute VB_Name = "frmPVps"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit       '2258 4/2/2007 Rudy:

Public fsSql As String
Public fiPos As Integer
Public fsVal As String

Private Sub cmdSelect_Click()
fsVal = lstDisp.List(lstDisp.ListIndex)
Me.Hide
End Sub

Private Sub Form_Load()
Dim dsDisp As Object
Dim sList As String
Dim sModule As String
sModule = "- frmPVps - "
CenterForm Me
On Error GoTo errHandleForm_Load
If fsSql = "" Then Exit Sub
Set dsDisp = OraDatabase.dbcreatedynaset(fsSql, 0&)
If Not dsDisp.eof And Not dsDisp.BOF Then
    With dsDisp
        While Not .eof
            sList = dsDisp.fields(0).Value & IIf(6 - Len(dsDisp.fields(0).Value) > 0, Space(6 - Len(dsDisp.fields(0).Value)), "")
            sList = sList & " " & dsDisp.fields(1).Value
            lstDisp.AddItem sList
            .MoveNext
        Wend
    End With
Else
    MsgBox "No Records found."
    Me.Hide
End If
lstDisp.ListIndex = 0
'txtDisp.SetFocus
Exit Sub
errHandleForm_Load:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If
Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
 
        'Disable the use of the error handler class
        'because we don't have the source code  -- LFW, 8/18/03
        MsgBox (gsMsg1 + gsMsg2)
        
 '       gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "Form_Load"
End Select


End Sub

Private Sub txtDisp_Change()
Dim i As Integer
Dim sText As String
sText = UCase(txtDisp.Text)
For i = 0 To lstDisp.ListCount - 1
    If sText <= Mid(lstDisp.List(i), fiPos, Len(sText)) Then
        lstDisp.ListIndex = i
        Exit For
    End If
Next
End Sub
