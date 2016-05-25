VERSION 5.00
Begin VB.Form frmCMPV 
   Caption         =   "Existing Credit Memo/Pre-Credit Memo"
   ClientHeight    =   3345
   ClientLeft      =   90
   ClientTop       =   315
   ClientWidth     =   4680
   LinkTopic       =   "Form1"
   ScaleHeight     =   3345
   ScaleWidth      =   4680
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdCreateNew 
      Caption         =   "&Create New"
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
      Left            =   3060
      TabIndex        =   2
      Top             =   2670
      Width           =   1245
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
      Left            =   1680
      TabIndex        =   1
      Top             =   2670
      Width           =   1245
   End
   Begin VB.ListBox lstDisp 
      Height          =   1815
      Left            =   240
      TabIndex        =   0
      Top             =   630
      Width           =   4215
   End
   Begin VB.Label lblDispHeading 
      AutoSize        =   -1  'True
      Height          =   195
      Left            =   240
      TabIndex        =   4
      Top             =   330
      Width           =   3405
      WordWrap        =   -1  'True
   End
   Begin VB.Label lblDisp 
      AutoSize        =   -1  'True
      Caption         =   "Credit Memos issued against Selected Invoice:"
      Height          =   195
      Left            =   240
      TabIndex        =   3
      Top             =   60
      Width           =   3405
      WordWrap        =   -1  'True
   End
End
Attribute VB_Name = "frmCMPV"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit       '2258 4/2/2007 Rudy:

Public fsVal As String
Public fsSql As String
Public fbNew As Boolean
Public fbCancel As Boolean
Public fbDisableAdd As Boolean

Private Sub cmdCreateNew_Click()
fsVal = 0
fbNew = True
fbCancel = False
Me.Hide
End Sub

Private Sub cmdSelect_Click()
Dim sBillingNum As String
Dim sListVal As String
If lstDisp.ListIndex = -1 Then
    MsgBox "No Credit Memo selected."
    Exit Sub
End If
sListVal = lstDisp.List(lstDisp.ListIndex)
sBillingNum = Mid(sListVal, 1, InStr(1, sListVal, " ") - 1)
If IsNumeric(sBillingNum) Then
    fsVal = sBillingNum
    fbCancel = False
Else
    MsgBox "Invalid Credit Memo Billing Number."
End If
Me.Hide
End Sub

Private Sub Form_Load()
'fill up the listbox with Data returned by fsSql
Dim dsDisp As Object
Dim sList As String
Dim sModule As String
sModule = "- frmCMPV - "
CenterForm Me
On Error GoTo errHandleForm_Load
Set dsDisp = OraDatabase.dbcreatedynaset(fsSql, 0&)
If Not dsDisp.eof And Not dsDisp.BOF Then
    With dsDisp
        While Not .eof
            sList = dsDisp.fields("BILLING_NUM").Value & " " & Chr(9) & dsDisp.fields("SERVICE_DATE").Value
            sList = sList & Chr(9) & Format(Abs(dsDisp.fields("SERVICE_AMOUNT").Value), "#.00") & Chr(9) & dsDisp.fields("SERVICE_STATUS").Value
            lstDisp.AddItem sList
            .MoveNext
        Wend
    End With
End If
lblDispHeading.Caption = "Bill Num    Date                       Amount    Status"
lstDisp.ListIndex = 0
'cmdCreateNew.Enabled = fbDisableAdd
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
