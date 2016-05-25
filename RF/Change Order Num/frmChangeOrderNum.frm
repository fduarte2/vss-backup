VERSION 5.00
Begin VB.Form frmChangeOrderNum 
   Caption         =   "Change Order Number"
   ClientHeight    =   3210
   ClientLeft      =   1665
   ClientTop       =   3060
   ClientWidth     =   6585
   LinkTopic       =   "Form1"
   ScaleHeight     =   3210
   ScaleWidth      =   6585
   Begin VB.TextBox txtCust 
      Height          =   315
      Left            =   3150
      TabIndex        =   1
      Top             =   180
      Width           =   1515
   End
   Begin VB.CommandButton cmdChange 
      Caption         =   "&Commit"
      Height          =   435
      Left            =   2370
      TabIndex        =   5
      Top             =   2220
      Width           =   1875
   End
   Begin VB.TextBox txtNewOrderNum 
      Height          =   315
      Left            =   3150
      MaxLength       =   12
      TabIndex        =   4
      Top             =   1350
      Width           =   1515
   End
   Begin VB.TextBox txtOldOrderNum 
      Height          =   315
      Left            =   3150
      TabIndex        =   2
      Top             =   750
      Width           =   1515
   End
   Begin VB.Label Label3 
      Alignment       =   1  'Right Justify
      Caption         =   "Customer Num"
      Height          =   255
      Left            =   1740
      TabIndex        =   7
      Top             =   210
      Width           =   1185
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   345
      Left            =   0
      TabIndex        =   6
      Top             =   2850
      Width           =   6585
   End
   Begin VB.Line Line1 
      X1              =   150
      X2              =   6420
      Y1              =   1950
      Y2              =   1950
   End
   Begin VB.Label Label2 
      Alignment       =   1  'Right Justify
      Caption         =   "Correct Order Num"
      Height          =   255
      Left            =   1500
      TabIndex        =   3
      Top             =   1380
      Width           =   1425
   End
   Begin VB.Label Label1 
      Alignment       =   1  'Right Justify
      Caption         =   "Incorret Order Num"
      Height          =   255
      Left            =   1500
      TabIndex        =   0
      Top             =   780
      Width           =   1425
   End
End
Attribute VB_Name = "frmChangeOrderNum"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim iResponse As Long
Private Sub cmdChange_Click()
'    Dim iOut As Boolean
'    iOut = False
    If Trim$(txtCust.Text) <> "" And Trim$(txtOldOrderNum.Text) <> "" And Trim$(txtNewOrderNum.Text) <> "" Then
        gsSqlStmt = "SELECT * FROM cargo_activity where CUSTOMER_ID = " & Val(Trim$(txtCust.Text))
        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(txtOldOrderNum.Text) & "'"
        Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        ' Large Edit, Adam Walter, May 2008.
        ' This program wasn't handling codes "8" and "11" properly, so I'm splitting
        ' this code into 3 functions.
        ' NOTE:  The above SQL can easily return numerous records, but they will all have the same SERVICE_CODE
        If dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 6 Then
            Call changeOutboundOrder
        ElseIf dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 8 Then
            Call changeInboundTruck
        ElseIf dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value = 11 Then
            Call changeTransferOwner
        Else
            MsgBox "This order number shows a service code of " & dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value & ", not valid for this program"
            Exit Sub
        End If
    Else
        MsgBox "All 3 boxes must be entered."
        Exit Sub
    End If
    
    
    
    
'    'CHECK IF IT IS OUTBOUND ORDER or Transfer Owner Order  -- added the Transfer check by LFW, 2/20/03
'    If dsCARGO_ACTIVITY.FIELDS("SERVICE_CODE").Value = 8 Or dsCARGO_ACTIVITY.FIELDS("SERVICE_CODE").Value = 11 Then
'        iOut = True
'    End If
'
'    If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.RecordCount > 0 Then
'        'Begin a transaction
'        OraSession.BeginTrans
'
'        While Not dsCARGO_ACTIVITY.EOF
'            If iOut Then
'                'UPDATE TRACKING TABLE'S ARRIVAL_NUM
'                gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(dsCARGO_ACTIVITY.FIELDS("PALLET_ID").Value) & "'"
'                gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(Trim$(txtCust.Text))
'                gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM = '" & Trim$(dsCARGO_ACTIVITY.FIELDS("ARRIVAL_NUM").Value) & "'"
'                Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'                dsCARGO_TRACKING.Edit
'                dsCARGO_TRACKING.FIELDS("ARRIVAL_NUM").Value = Trim$(txtNewOrderNum.Text)
'                dsCARGO_TRACKING.Update
'            End If
'
'            dsCARGO_ACTIVITY.Edit
'            dsCARGO_ACTIVITY.FIELDS("ORDER_NUM").Value = Trim$(txtNewOrderNum.Text)
'            If iOut Then
'                dsCARGO_ACTIVITY.FIELDS("ARRIVAL_NUM").Value = Trim$(txtNewOrderNum.Text)
'            End If
'            dsCARGO_ACTIVITY.Update
'            dsCARGO_ACTIVITY.MoveNext
'        Wend
'
'
'        If OraDatabase.LastServerErr = 0 Then
'            OraSession.CommitTrans
'            lblStatus.Caption = "Order Number was Changed Successfully"
'        Else
'            MsgBox "Error occured while saving to cargo activity table. Changes are not saved.", vbExclamation, "Save"
'            OraSession.Rollback
'            lblStatus.Caption = "Failed in Changing Order Number"
'        End If
'
'    End If
'
    
    Unload Me
    'Load Me
End Sub

Private Sub Form_Load()
On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    'Set OraDatabase = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)
    Set OraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)

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

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Activity"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0
End Sub



Private Sub txtCust_LostFocus()
    If Trim$(txtCust.Text) = "" Then
        txtOldOrderNum.Text = ""
        txtOldOrderNum.SetFocus
        MsgBox "This field cannot be null. Please try again.", vbExclamation, "Order Number"
    End If
End Sub


Private Sub txtNewOrderNum_LostFocus()
'    If Trim$(txtNewOrderNum.Text) <> "" Then
'
'        gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & Trim$(txtNewOrderNum.Text) & "'"
'        Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'        If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.RecordCount > 0 Then
'            iResponse = MsgBox("There are " & Trim$(dsCARGO_ACTIVITY.RecordCount) & " Records whose Order Number is " & Trim$(txtNewOrderNum.Text) & ". Do you want ot use this number?", vbQuestion + vbYesNo, "Order Number")
'            If iResponse = vbYes Then
'                cmdChange.SetFocus
'            Else
'                txtNewOrderNum.Text = ""
'                txtNewOrderNum.SetFocus
'            End If
'        Else
'            cmdChange.SetFocus
'        End If
'    Else
'        txtNewOrderNum.Text = ""
'        txtNewOrderNum.SetFocus
'        MsgBox "New Order Number cannot be null. Please try again.", vbExclamation, "Order Number"
'    End If
    
End Sub

Private Sub txtOldOrderNum_LostFocus()
  
    If Trim$(txtOldOrderNum.Text) <> "" Then
        gsSqlStmt = "SELECT * FROM cargo_activity where CUSTOMER_ID = " & Val(Trim$(txtCust.Text))
        gsSqlStmt = gsSqlStmt & " AND ORDER_NUM = '" & Trim$(txtOldOrderNum.Text) & "'"
        gsSqlStmt = gsSqlStmt & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
        
        Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.RecordCount > 0 Then
            lblStatus.Caption = "Total Records are: " + Trim$(dsCARGO_ACTIVITY.RecordCount)
        Else
            txtOldOrderNum.Text = ""
            txtOldOrderNum.SetFocus
            MsgBox "This Order Number does not Exist. Please try again.", vbExclamation, "Order Number"
        End If
    Else
        txtOldOrderNum.Text = ""
        txtOldOrderNum.SetFocus
        MsgBox "This field cannot be null. Please try again.", vbExclamation, "Order Number"
    End If
End Sub

Private Sub changeOutboundOrder()

    ' update CARGO_ACTIVITY only in this case.
    gsSqlStmt = "UPDATE CARGO_ACTIVITY SET ORDER_NUM = '" & Trim$(txtNewOrderNum.Text) & "' WHERE CUSTOMER_ID = '" _
        & Val(Trim$(txtCust.Text)) & "' AND ORDER_NUM = '" & Trim$(txtOldOrderNum.Text) & "'"
    OraDatabase.ExecuteSQL (gsSqlStmt)
    
End Sub

Private Sub changeInboundTruck()

    ' Need to change ORDER_NUM and ARRIVAL_NUM in CARGO_ACTIVITY, and ARRIVAL_NUM in CARGO_TRACKING.
    ' CARGO_ACTIVITY's ARRIVAL_NUM needs to be updated for all pallet activites, in case pallet has been shipped out
    ' before this program was run.
    gsSqlStmt = "UPDATE CARGO_ACTIVITY SET ORDER_NUM = '" & Trim$(txtNewOrderNum.Text) & "', ARRIVAL_NUM = '" & Trim$(txtNewOrderNum.Text) & "' WHERE CUSTOMER_ID = '" _
        & Val(Trim$(txtCust.Text)) & "' AND ORDER_NUM = '" & Trim$(txtOldOrderNum.Text) & "'"
    OraDatabase.ExecuteSQL (gsSqlStmt)
    
    gsSqlStmt = "UPDATE CARGO_ACTIVITY SET ARRIVAL_NUM = '" & Trim$(txtNewOrderNum.Text) & "' WHERE CUSTOMER_ID = '" _
        & Val(Trim$(txtCust.Text)) & "' AND ARRIVAL_NUM = '" & Trim$(txtOldOrderNum.Text) & "'"
    OraDatabase.ExecuteSQL (gsSqlStmt)
    
    gsSqlStmt = "UPDATE CARGO_TRACKING SET ARRIVAL_NUM = '" & Trim$(txtNewOrderNum.Text) & "' WHERE RECEIVER_ID = '" _
        & Val(Trim$(txtCust.Text)) & "' AND ARRIVAL_NUM = '" & Trim$(txtOldOrderNum.Text) & "'"
    OraDatabase.ExecuteSQL (gsSqlStmt)

End Sub

Private Sub changeTransferOwner()
Dim iNewCust As Integer

    ' this is the tough one.
    ' need to change the ORDER_NUM in CA for both the outbound "transfer" of the old owner and the inbound "transfer" of the new owner.
    ' Also, need to change the ARRIVAL_NUM in CA for the new owner, as well as the ARRIVAL_NUM in CARGO_TRACKING, for the new owner only.
    ' All CA records for the pallets have to have their ARRIVAL_NUMs updated, but only for said new owner.
    
    ' note that a transfer (as of me writing this anyway) cannot be made to more than 1 recipient, so I will use a simple SQL
    ' to figure out who said recipient is.
    gsSqlStmt = "SELECT CUSTOMER_ID FROM CARGO_ACTIVITY WHERE SERVICE_CODE = '11' AND ORDER_NUM = '" & Trim$(txtOldOrderNum.Text) & "' " _
        & "AND ARRIVAL_NUM = '" & Trim$(txtOldOrderNum.Text) & "'"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    iNewCust = dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value
    
    If iNewCust = Val(Trim$(txtCust.Text)) Then
        MsgBox "Please use the Original customer's number in the customer field for Transfer of Ownership changes."
        Exit Sub
    End If
    
    gsSqlStmt = "UPDATE CARGO_ACTIVITY SET ORDER_NUM = '" & Trim$(txtNewOrderNum.Text) & "' WHERE CUSTOMER_ID = '" _
        & Val(Trim$(txtCust.Text)) & "' AND ORDER_NUM = '" & Trim$(txtOldOrderNum.Text) & "'"
    OraDatabase.ExecuteSQL (gsSqlStmt)

    gsSqlStmt = "UPDATE CARGO_TRACKING SET ARRIVAL_NUM = '" & Trim$(txtNewOrderNum.Text) & "' WHERE RECEIVER_ID = '" _
        & iNewCust & "' AND ARRIVAL_NUM = '" & Trim$(txtOldOrderNum.Text) & "'"
    OraDatabase.ExecuteSQL (gsSqlStmt)

    gsSqlStmt = "UPDATE CARGO_ACTIVITY SET ORDER_NUM = '" & Trim$(txtNewOrderNum.Text) & "', ARRIVAL_NUM = '" & Trim$(txtNewOrderNum.Text) & "' WHERE CUSTOMER_ID = '" _
        & iNewCust & "' AND ORDER_NUM = '" & Trim$(txtOldOrderNum.Text) & "' AND SERVICE_CODE = '11'"
    OraDatabase.ExecuteSQL (gsSqlStmt)
    
    gsSqlStmt = "UPDATE CARGO_ACTIVITY SET ARRIVAL_NUM = '" & Trim$(txtNewOrderNum.Text) & "' WHERE CUSTOMER_ID = '" _
        & iNewCust & "' AND ARRIVAL_NUM = '" & Trim$(txtOldOrderNum.Text) & "'"
    OraDatabase.ExecuteSQL (gsSqlStmt)

End Sub
