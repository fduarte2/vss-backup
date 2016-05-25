VERSION 5.00
Begin VB.Form frmChangeOrderDate 
   Caption         =   "Change Order Date"
   ClientHeight    =   3495
   ClientLeft      =   2070
   ClientTop       =   2925
   ClientWidth     =   6030
   LinkTopic       =   "Form1"
   ScaleHeight     =   3495
   ScaleWidth      =   6030
   Begin VB.TextBox txtCustId 
      Height          =   405
      Left            =   2790
      TabIndex        =   1
      Top             =   90
      Width           =   1485
   End
   Begin VB.CommandButton Command1 
      Caption         =   "&Commit"
      Height          =   405
      Left            =   2160
      TabIndex        =   7
      Top             =   2550
      Width           =   1785
   End
   Begin VB.TextBox txtNewOrderDate 
      Height          =   405
      Left            =   2790
      TabIndex        =   6
      Top             =   1620
      Width           =   1485
   End
   Begin VB.TextBox txtOldOrderDate 
      BackColor       =   &H80000000&
      Enabled         =   0   'False
      Height          =   405
      Left            =   2790
      TabIndex        =   4
      Top             =   1110
      Width           =   1485
   End
   Begin VB.TextBox txtOrderNum 
      Height          =   405
      Left            =   2790
      TabIndex        =   2
      Top             =   600
      Width           =   1485
   End
   Begin VB.Label Label4 
      Caption         =   "Customer Id"
      Height          =   375
      Left            =   1380
      TabIndex        =   9
      Top             =   150
      Width           =   1215
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   345
      Left            =   0
      TabIndex        =   8
      Top             =   3150
      Width           =   6015
   End
   Begin VB.Line Line1 
      X1              =   90
      X2              =   5940
      Y1              =   2280
      Y2              =   2280
   End
   Begin VB.Label Label3 
      Caption         =   "New Order Date"
      Height          =   375
      Left            =   1380
      TabIndex        =   5
      Top             =   1620
      Width           =   1215
   End
   Begin VB.Label Label2 
      Caption         =   "Old Order Date"
      Height          =   375
      Left            =   1380
      TabIndex        =   3
      Top             =   1110
      Width           =   1215
   End
   Begin VB.Label Label1 
      Caption         =   "Order Number"
      Height          =   375
      Left            =   1380
      TabIndex        =   0
      Top             =   630
      Width           =   1215
   End
End
Attribute VB_Name = "frmChangeOrderDate"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit


    Dim myDate As String
    Dim iDate As String
    Dim iPosition As Long
    
Private Sub Command1_Click()
  
    If Trim$(txtNewOrderDate.Text) <> "" Then
    
'        gsSqlStmt = "SELECT * FROM cargo_activity where CUSTOMER_ID = " & txtCustId.Text
'        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(txtOrderNum.Text) & "'"
'        Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'         If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.RecordCount > 0 Then
'             'Begin a transaction
'             OraSession.BeginTrans
'
'
'             While Not dsCARGO_ACTIVITY.EOF
'                dsCARGO_ACTIVITY.Edit
'                dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value = Format$(txtNewOrderDate.Text, "MM/DD/YYYY")
'                dsCARGO_ACTIVITY.Update
'                dsCARGO_ACTIVITY.MoveNext
'             Wend
'
'             If OraDatabase.LastServerErr = 0 Then
'                 OraSession.CommitTrans
'                 lblStatus.Caption = "An change was processed successful"
'             Else
'                 MsgBox "Error occured while saving to cargo activity table. Changes are not saved.", vbExclamation, "Save"
'                 OraSession.Rollback
'                 lblStatus.Caption = "Failed in Changing"
'
'             End If
'
'         End If

        gsSqlStmt = "UPDATE CARGO_ACTIVITY SET DATE_OF_ACTIVITY = TO_DATE('" & txtNewOrderDate.Text & "' || ' ' || TO_CHAR(DATE_OF_ACTIVITY, 'HH24:MI:SS'), 'MM/DD/YYYY HH24:MI:SS') " _
                & " WHERE ORDER_NUM = '" & Trim$(txtOrderNum.Text) & "' AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID') AND ACTIVITY_NUM != '1'"
        OraDatabase.ExecuteSQL (gsSqlStmt)
        
    End If
    Unload Me
    'Load Me
End Sub

Private Sub Form_Load()
 On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
    'Set OraDatabase = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)

    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0
End Sub


    
Private Sub Text1_Change()

End Sub

Private Sub txtCustId_Change()
    If (Trim$(txtCustId.Text)) = "" Then
            txtNewOrderDate.SetFocus
            txtNewOrderDate.Text = ""
    End If
End Sub


Private Sub txtNewOrderDate_LostFocus()
    If (Trim$(txtNewOrderDate.Text)) <> "" Then
        If IsDate(Trim$(txtNewOrderDate.Text)) Then
        Else
            txtNewOrderDate.SetFocus
            txtNewOrderDate.Text = ""
            MsgBox "Order Date Formate is mm/dd/yyyy. Please try again.", vbExclamation, "Order Date"
        End If
    Else
        Exit Sub
    End If

End Sub

Private Sub txtOrderNum_LostFocus()
    
    If Trim$(txtOrderNum.Text) <> "" Then
            gsSqlStmt = "SELECT * FROM cargo_activity where CUSTOMER_ID = " & txtCustId.Text
            gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(txtOrderNum.Text) & "'"
            gsSqlStmt = gsSqlStmt & " AND ACTIVITY_NUM != '1'"
            Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.RecordCount > 0 Then
                If Not IsNull(Trim$(dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value)) Then
                    myDate = dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY")
                    iPosition = InStr(1, myDate, " ")
                    iDate = Left$(myDate, iPosition)
       
                    txtOldOrderDate.Text = iDate
                    lblStatus.Caption = "Total Records are : " + Trim$(dsCARGO_ACTIVITY.RecordCount)
                Else
                    txtOldOrderDate.Text = ""
                    lblStatus.Caption = "Total Records are : " + Trim$(dsCARGO_ACTIVITY.RecordCount)
                End If
                
                Exit Sub
            Else
                txtOrderNum.Text = ""
                txtOrderNum.SetFocus
                MsgBox "This Order Number does not Exist, or was the Original Receipt. Please try again.", vbExclamation, "Order Number"
            End If
        Exit Sub
        
    Else
        txtOrderNum.Text = ""
        txtOrderNum.SetFocus
        MsgBox "This field can't be null. Please try again.", vbExclamation, "Order Number"
        
    End If
End Sub


