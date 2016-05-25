VERSION 5.00
Begin VB.Form frmTransfer 
   Caption         =   "RF TRANSFERS"
   ClientHeight    =   7575
   ClientLeft      =   3810
   ClientTop       =   1665
   ClientWidth     =   7920
   BeginProperty Font 
      Name            =   "Times New Roman"
      Size            =   9
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   ScaleHeight     =   7575
   ScaleWidth      =   7920
   Begin VB.CommandButton cmdPlt 
      Caption         =   "Pallet &Details"
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
      Left            =   1395
      TabIndex        =   37
      Top             =   7020
      Width           =   1365
   End
   Begin VB.TextBox txtQty 
      Alignment       =   1  'Right Justify
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   8
      Top             =   3600
      Width           =   825
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&Print"
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
      Left            =   5340
      TabIndex        =   18
      Top             =   7020
      Width           =   1185
   End
   Begin VB.CommandButton cmdExit 
      Cancel          =   -1  'True
      Caption         =   "&Exit"
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
      Left            =   6585
      TabIndex        =   19
      Top             =   7020
      Width           =   1185
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&Delete"
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
      Left            =   4080
      TabIndex        =   17
      Top             =   7020
      Width           =   1185
   End
   Begin VB.CommandButton cmdCancel 
      Caption         =   "&Cancel"
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
      Left            =   2835
      TabIndex        =   16
      Top             =   7020
      Width           =   1185
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
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
      Left            =   150
      TabIndex        =   15
      Top             =   7020
      Width           =   1185
   End
   Begin VB.TextBox txtTmTrans 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   3690
      TabIndex        =   12
      Top             =   5490
      Width           =   1185
   End
   Begin VB.TextBox txtDtTrans 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   11
      Top             =   5490
      Width           =   1185
   End
   Begin VB.TextBox txtOrderNum 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   2
      Top             =   1170
      Width           =   1635
   End
   Begin VB.TextBox txtQtyTransInH 
      Alignment       =   1  'Right Justify
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   5310
      TabIndex        =   14
      Top             =   6000
      Width           =   825
   End
   Begin VB.TextBox txtQtyTrans 
      Alignment       =   1  'Right Justify
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   13
      Top             =   6000
      Width           =   825
   End
   Begin VB.TextBox txtTransCustomer 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   3690
      Locked          =   -1  'True
      TabIndex        =   29
      Top             =   4950
      Width           =   3255
   End
   Begin VB.TextBox txtTransCustId 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   10
      Top             =   4950
      Width           =   1005
   End
   Begin VB.TextBox txtOrigCustomer 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   3600
      Locked          =   -1  'True
      TabIndex        =   28
      Top             =   1980
      Width           =   3255
   End
   Begin VB.TextBox txtVessel 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   3690
      Locked          =   -1  'True
      TabIndex        =   27
      Top             =   4140
      Width           =   3255
   End
   Begin VB.TextBox txtArrNum 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   9
      Top             =   4140
      Width           =   1095
   End
   Begin VB.TextBox txtQtyInH 
      Alignment       =   1  'Right Justify
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   5310
      TabIndex        =   7
      Top             =   3060
      Width           =   825
   End
   Begin VB.TextBox txtQtyRecvd 
      Alignment       =   1  'Right Justify
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   6
      Top             =   3060
      Width           =   825
   End
   Begin VB.TextBox txtTmRecvd 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   3690
      TabIndex        =   5
      Top             =   2520
      Width           =   1185
   End
   Begin VB.TextBox txtDtRecvd 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   4
      Top             =   2520
      Width           =   1185
   End
   Begin VB.TextBox txtOrigCustId 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   3
      Top             =   1980
      Width           =   1005
   End
   Begin VB.TextBox txtCommodity 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   3780
      Locked          =   -1  'True
      TabIndex        =   22
      TabStop         =   0   'False
      Top             =   630
      Width           =   2715
   End
   Begin VB.TextBox txtCommCode 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   1
      Top             =   630
      Width           =   1005
   End
   Begin VB.TextBox txtPltNum 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2430
      TabIndex        =   0
      Top             =   90
      Width           =   1725
   End
   Begin VB.Label Label14 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Qty Change  :"
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
      Left            =   1080
      TabIndex        =   36
      Top             =   3653
      Width           =   1125
   End
   Begin VB.Label Label13 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Order #  :"
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
      Left            =   1395
      TabIndex        =   35
      Top             =   1223
      Width           =   810
   End
   Begin VB.Label Label8 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Date Of Transfer  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   690
      TabIndex        =   34
      Top             =   5543
      Width           =   1515
   End
   Begin VB.Label Label12 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Qty In House  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   3975
      TabIndex        =   33
      Top             =   3113
      Width           =   1200
   End
   Begin VB.Label Label6 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Qty Received  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   1020
      TabIndex        =   32
      Top             =   3113
      Width           =   1185
   End
   Begin VB.Label Label10 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Qty In House  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   3975
      TabIndex        =   31
      Top             =   6053
      Width           =   1200
   End
   Begin VB.Label Label9 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Qty Transfered  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   840
      TabIndex        =   30
      Top             =   6053
      Width           =   1365
   End
   Begin VB.Label Label7 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Transfer To   :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00C00000&
      Height          =   225
      Left            =   1065
      TabIndex        =   26
      Top             =   5003
      Width           =   1140
   End
   Begin VB.Label Label5 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Vessel  :"
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
      Left            =   1500
      TabIndex        =   25
      Top             =   4193
      Width           =   705
   End
   Begin VB.Label Label4 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Date & Time Received  :"
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
      Left            =   405
      TabIndex        =   24
      Top             =   2573
      Width           =   1800
   End
   Begin VB.Label Label3 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Original Customer  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00C00000&
      Height          =   225
      Left            =   480
      TabIndex        =   23
      Top             =   2040
      Width           =   1725
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Commodity  :"
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
      Left            =   1140
      TabIndex        =   21
      Top             =   683
      Width           =   1065
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Pallet ID  :"
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
      Left            =   1335
      TabIndex        =   20
      Top             =   143
      Width           =   870
   End
End
Attribute VB_Name = "frmTransfer"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Dim iExit As Boolean
Dim iOldQtyTrans As Integer
Dim dsCARGO_ACTIVITY_ORIG As Object
Dim dsCARGO_TRACKING_ORIG As Object
Dim dsCARGO_ACTIVITY_TRANS As Object
Dim dsCARGO_TRACKING_TRANS As Object
Dim dsCUSTID As Object
Private Sub cmdCancel_Click()
    Call txtPltNum_LostFocus
End Sub

Private Sub cmdDelete_Click()
   If MsgBox("Are you sure to delete this whole transfer ?", vbQuestion + vbYesNo, "DELETE TRANSFER") = vbNo Then Exit Sub
   
   OraSession.begintrans
   
      With dsCARGO_ACTIVITY_ORIG
         .Delete
         .Update
      End With
   
      With dsCARGO_TRACKING_ORIG
         .edit
         dsCARGO_TRACKING_ORIG.fields("QTY_IN_HOUSE").Value = Val(Trim(txtQtyInH)) + Val(Trim(txtQty))
         .Update
      End With
      
      With dsCARGO_ACTIVITY_TRANS
         .Delete
         .Update
      End With
   
      With dsCARGO_TRACKING_TRANS
         .Delete
         .Update
      End With
      
      
      If OraDatabase.lastservererr = 0 Then
         OraSession.committrans
      Else
         OraSession.rollback
         MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
         OraDatabase.LastServerErrReset
      End If
      

End Sub

Private Sub cmdExit_Click()
   Unload Me
End Sub
Sub DisplayMsg(sMsg As String)
   MsgBox "Enter " & sMsg, vbInformation + vbExclamation, sMsg
   iExit = True
End Sub

Private Sub cmdPlt_Click()
   gsLotNum = ""
   frmPltCorrection.Show
   Unload Me
End Sub

Private Sub cmdPrint_Click()
   
    Dim iLine As Long
    Dim sql1 As String
    Dim dsTemp1 As Object
    
    Printer.FontSize = 12
    Printer.Orientation = 2
    
    Printer.Print "Printed On : " & Format(Now, "MM/DD/YYYY")
    
    For iLine = 1 To 3
        Printer.Print
    Next iLine
    Printer.FontBold = True
    Printer.FontUnderline = True
    Printer.Print Tab(75); "TRANSFERS"
    Printer.FontUnderline = False
    Printer.Print
    Printer.Print
    Printer.FontBold = False
    
    Printer.Print Tab(5); "PALLET"; Tab(22); " : "; Tab(30); txtPltNum
    Printer.Print
    Printer.Print Tab(5); "ORDER #"; Tab(22); " : "; Tab(30); txtOrderNum
    Printer.Print ""
    Printer.Print Tab(5); "COMMODITY"; Tab(22); " : "; Tab(30); txtCommCode & " - " & txtCommodity
    Printer.Print ""
    Printer.Print
    Printer.Print
    Printer.FontUnderline = True
    Printer.Print Tab(40); "ORIGINAL CUSTOMER"; Tab(100); "TRANSFERED TO"
    Printer.FontUnderline = False
    Printer.Print
    Printer.Print Tab(30); " : "; Tab(40); txtOrigCustId & " - " & Trim(txtOrigCustomer); Tab(100); txtTransCustId & " - " & Trim(txtTransCustomer)
    Printer.Print
    Printer.Print Tab(5); "Received Date"; Tab(30); " : "; Tab(40); txtDtRecvd & " " & txtTmRecvd; Tab(100); txtDtTrans & " " & txtTmTrans
    Printer.Print
    Printer.Print Tab(5); "Qty Received"; Tab(30); " : "; Tab(40); txtQtyRecvd; Tab(100); txtQtyTrans
    Printer.Print
    Printer.Print Tab(5); "Qty InHouse"; Tab(30); " : "; Tab(40); txtQtyInH; Tab(100); txtQtyTransInH
    Printer.Print
    Printer.Print Tab(5); "Qty Changed"; Tab(30); " : "; Tab(40); txtQty
    Printer.Print
    Printer.Print Tab(5); "Vessel"; Tab(30); " : "; Tab(40); txtArrNum & " - " & txtVessel
    Printer.Print
       
    Printer.EndDoc
End Sub

Private Sub cmdSave_Click()
      
   iExit = False
   
   If Trim(txtOrigCustId) = "" Then Call DisplayMsg("Original Customer")
   
   If Trim(txtDtRecvd) = "" Then Call DisplayMsg("Date Received")
   
   If Trim(txtTmRecvd) = "" Then Call DisplayMsg("Time Received")
   
   If Trim(txtArrNum) = "" Then Call DisplayMsg("Vessel")
   
   If Trim(txtQtyRecvd) = "" Then Call DisplayMsg("Original Qty Received")
   
   If Trim(txtQtyInH) = "" Then Call DisplayMsg("Original Qty InHouse")
   
   If Trim(txtTransCustId) = "" Then Call DisplayMsg("New Customer")
      
   If Trim(txtDtTrans) = "" Then Call DisplayMsg("Transfer Date")
   
   If Trim(txtDtTrans) = "" Then Call DisplayMsg("Transfer Date")
   
   If Trim(txtTmTrans) = "" Then Call DisplayMsg("Transfer Time")
   
   If Trim(txtQtyTrans) = "" Then Call DisplayMsg("Transfer Quantity")
   
   If Trim(txtQtyTransInH) = "" Then Call DisplayMsg("Transfer Quantity InHouse")
   
   If Trim(txtDtTrans) = "" Then Call DisplayMsg("Transfer Date")
   
   If Trim(txtOrderNum) = "" Then Call DisplayMsg("Transfer Order #")
   
   If iExit = True Then Exit Sub
   
   If Val(txtQty) <> Val(txtQtyTrans) Then
      MsgBox "Qty Change must be equal to Qty transfered ", vbInformation + vbExclamation, "SAVE"
      Exit Sub
   End If
   
   If Val(txtQtyRecvd) < Val(txtQtyInH) Then
      MsgBox "Qty Inhouse can't be more then the Qty Received ", vbInformation + vbExclamation, "SAVE"
      Exit Sub
   End If
   
   
   If Val(txtQtyTrans) < Val(txtQtyTransInH) Then
      MsgBox "Qty Transfer can't be less then the Transfered Qty InHouse ", vbInformation + vbExclamation, "SAVE"
      Exit Sub
   End If
   
   If Val(txtQtyTrans) > Val(txtQtyRecvd) Then
      MsgBox "Qty Transfer can't be more then the Original Qty Received", vbInformation + vbExclamation, "SAVE"
      Exit Sub
   End If
   
   If Val(txtQtyTrans) > Val(txtQtyRecvd) - Val(txtQtyInH) Then
      MsgBox "Sum of Qty Transfer and Qty Inhouse can't be more then the Original Qty Received", vbInformation + vbExclamation, "SAVE"
      Exit Sub
   End If
   
   OraSession.begintrans
   
      With dsCARGO_ACTIVITY_ORIG
         .edit
         .fields("CUSTOMER_ID").Value = Trim(txtOrigCustId)
         .fields("DATE_OF_ACTIVITY").Value = Format(txtDtTrans, "MM/DD/YYYY") & " " & Format(txtTmTrans, "HH:MM:SS")
         .fields("ARRIVAL_NUM").Value = Trim(txtArrNum)
         .fields("ORDER_NUM").Value = Trim(txtOrderNum)
         .fields("QTY_CHANGE").Value = Trim(txtQtyTrans)
         .fields("QTY_LEFT").Value = .fields("QTY_LEFT").Value + (iOldQtyTrans - CInt(Trim(txtQtyTrans)))
         .Update
      End With
   
      With dsCARGO_TRACKING_ORIG
         .edit
         .fields("RECEIVER_ID").Value = Trim(txtOrigCustId)
         .fields("COMMODITY_CODE").Value = Trim(txtCommCode)
         .fields("DATE_RECEIVED").Value = Format(txtDtRecvd, "MM/DD/YYYY") & " " & Format(txtTmRecvd, "HH:MM:SS")
         .fields("ARRIVAL_NUM").Value = Trim(txtArrNum)
         .fields("QTY_RECEIVED").Value = Trim(txtQtyRecvd)
         .fields("QTY_IN_HOUSE").Value = Trim(txtQtyInH)
         .Update
      End With
      
      With dsCARGO_ACTIVITY_TRANS
         .edit
         .fields("CUSTOMER_ID").Value = Trim(txtTransCustId)
         .fields("DATE_OF_ACTIVITY").Value = Format(txtDtTrans, "MM/DD/YYYY") & " " & Format(txtTmTrans, "HH:MM:SS")
         .fields("ARRIVAL_NUM").Value = Trim(txtOrderNum)
         .fields("ORDER_NUM").Value = Trim(txtOrderNum)
         .fields("QTY_CHANGE").Value = Trim(txtQtyTrans)
         .fields("QTY_LEFT").Value = .fields("QTY_LEFT").Value - (iOldQtyTrans - CInt(Trim(txtQtyTrans)))
         
         .Update
      End With
   
      With dsCARGO_TRACKING_TRANS
         .edit
         .fields("RECEIVER_ID").Value = Trim(txtOrigCustId)
         .fields("COMMODITY_CODE").Value = Trim(txtCommCode)
         .fields("DATE_RECEIVED").Value = Format(txtDtTrans, "MM/DD/YYYY") & " " & Format(txtTmTrans, "HH:MM:SS")
         .fields("ARRIVAL_NUM").Value = Trim(txtOrderNum)
         .fields("QTY_RECEIVED").Value = Trim(txtQtyTrans)
         .fields("QTY_IN_HOUSE").Value = Trim(txtQtyTransInH)
         .Update
      End With
      
      
      If OraDatabase.lastservererr = 0 Then
         OraSession.committrans
      Else
         OraSession.rollback
         MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
         OraDatabase.LastServerErrReset
      End If
         
      Call txtPltNum_LostFocus
      
End Sub

Private Sub Form_Load()
   Me.Left = (Screen.Width - Me.Width) / 2
   Me.Top = (Screen.Height - Me.Height) / 2
   
   If gsLotNum <> "" Then txtPltNum_LostFocus
   
End Sub
Private Sub txtArrNum_Validate(Cancel As Boolean)
   
    If Trim(txtArrNum) <> "" And IsNumeric(txtArrNum) Then
        SqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM =" & txtArrNum & ""
        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        If OraDatabase.lastservererr = 0 And dsVESSEL_PROFILE.recordcount > 0 Then
            txtVessel = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
            Cancel = False
        Else
            MsgBox "Invalid Vessel No", vbInformation, sMsg
            Cancel = True
        End If
    Else
        txtVessel = ""
    End If
End Sub


Private Sub txtCommCode_Validate(Cancel As Boolean)
   If Trim(txtCommCode.Text) <> "" Then
        SqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE =" & txtCommCode.Text
        Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        If OraDatabase.lastservererr = 0 And dsCOMMODITY_PROFILE.recordcount > 0 Then
            txtCommodity = dsCOMMODITY_PROFILE.fields("COMMODITY_NAME").Value
            Cancel = False
        Else
            MsgBox "Invalid Commodity Code", vbInformation, sMsg
            Cancel = True
        End If
    Else
        txtCommodity = ""
    End If
End Sub

Private Sub txtOrigCustId_Validate(Cancel As Boolean)
   If txtOrigCustId = "" Then Exit Sub
    
    If Not IsNumeric(txtOrigCustId) Then
        MsgBox "Not a valid Id", vbInformation, "CUSTOMER"
        txtOrigCustId = ""
        Cancel = True
        Exit Sub
    End If
                                                                                                                                                                                                                                                               
    SqlStmt = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID= " _
             & " '" & Trim(txtOrigCustId) & "'"
    Set dsCUSTID = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.lastservererr = 0 And dsCUSTID.recordcount > 0 Then
        txtOrigCustomer = dsCUSTID.fields("CUSTOMER_NAME").Value
        Cancel = False
    Else
        If OraDatabase.lastservererr <> 0 Then
            MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation
            Exit Sub
        End If
        MsgBox "INVALID CUSTOMER ID !", vbInformation + vbExclamation, "CUSTOMER"
        txtOrigCustId = ""
        Cancel = True
    End If
End Sub

Private Sub txtPltNum_LostFocus()
   If Trim(txtPltNum) = "" Then Exit Sub
   
   iOldQtyTrans = 0
      
   SqlStmt = " SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID='" & Trim(txtPltNum) & "'" _
         & " AND SERVICE_CODE='11' AND ORDER_NUM<>ARRIVAL_NUM "
   Set dsCARGO_ACTIVITY_ORIG = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
   If OraDatabase.lastservererr = 0 And dsCARGO_ACTIVITY_ORIG.recordcount > 0 Then
      
      txtOrigCustId = dsCARGO_ACTIVITY_ORIG.fields("CUSTOMER_ID").Value
      txtArrNum = dsCARGO_ACTIVITY_ORIG.fields("ARRIVAL_NUM").Value
      txtOrderNum = dsCARGO_ACTIVITY_ORIG.fields("ORDER_NUM").Value
      txtQty = dsCARGO_ACTIVITY_ORIG.fields("QTY_CHANGE").Value
         
      SqlStmt = " SELECT * FROM CARGO_TRACKING WHERE PALLET_ID='" & Trim(txtPltNum) & "'" _
            & " AND ARRIVAL_NUM='" & Trim(txtArrNum) & "' AND RECEIVER_ID='" & Trim(txtOrigCustId) & "'"
      Set dsCARGO_TRACKING_ORIG = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
      If OraDatabase.lastservererr = 0 And dsCARGO_TRACKING_ORIG.recordcount > 0 Then
         txtCommCode = dsCARGO_TRACKING_ORIG.fields("COMMODITY_CODE").Value
         txtDtRecvd = Format(dsCARGO_TRACKING_ORIG.fields("DATE_RECEIVED").Value, "MM/DD/YYYY")
         txtTmRecvd = Format(dsCARGO_TRACKING_ORIG.fields("DATE_RECEIVED").Value, "HH:MM:SS")
         txtQtyRecvd = dsCARGO_TRACKING_ORIG.fields("QTY_RECEIVED").Value
         txtQtyInH = dsCARGO_TRACKING_ORIG.fields("QTY_IN_HOUSE").Value
      End If
   Else
      If OraDatabase.lastservererr <> 0 Then
         MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
         OraDatabase.LastServerErrReset
         Exit Sub
      End If
      
      MsgBox " No Transfer records found for this Pallet.", vbInformation, "TRANSFERS"
      Call Clear_Screen
      Exit Sub
   End If
   
   SqlStmt = " SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID='" & Trim(txtPltNum) & "'" _
           & " AND SERVICE_CODE='11' AND ORDER_NUM=ARRIVAL_NUM "
   Set dsCARGO_ACTIVITY_TRANS = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
   If OraDatabase.lastservererr = 0 And dsCARGO_ACTIVITY_TRANS.recordcount > 0 Then
      txtTransCustId = dsCARGO_ACTIVITY_TRANS.fields("CUSTOMER_ID").Value
      
         
      SqlStmt = " SELECT * FROM CARGO_TRACKING WHERE PALLET_ID='" & Trim(txtPltNum) & "'" _
            & " AND ARRIVAL_NUM='" & Trim(txtOrderNum) & "' AND RECEIVER_ID='" & Trim(txtTransCustId) & "'"
      Set dsCARGO_TRACKING_TRANS = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
      If OraDatabase.lastservererr = 0 And dsCARGO_TRACKING_TRANS.recordcount > 0 Then
         txtDtTrans = Format(dsCARGO_TRACKING_TRANS.fields("DATE_RECEIVED").Value, "MM/DD/YYYY")
         txtTmTrans = Format(dsCARGO_TRACKING_TRANS.fields("DATE_RECEIVED").Value, "HH:MM:SS")
         txtQtyTrans = dsCARGO_TRACKING_TRANS.fields("QTY_RECEIVED").Value
         txtQtyTransInH = dsCARGO_TRACKING_TRANS.fields("QTY_IN_HOUSE").Value
      End If
   Else
      If OraDatabase.lastservererr <> 0 Then
         MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
         OraDatabase.LastServerErrReset
         Exit Sub
      End If
      
      MsgBox " No Transfer records found for this Pallet.", vbInformation, "TRANSFERS"
      Exit Sub
   End If
   
   iOldQtyTrans = CInt(Trim(txtQtyTrans))
   
   txtOrigCustId_Validate (False)
   txtTransCustId_Validate (False)
   txtArrNum_Validate (False)
   txtCommCode_Validate (False)
   
End Sub
Private Sub txtQtyTrans_Validate(Cancel As Boolean)
 If Val(txtQtyTrans) > Val(txtQtyRecvd) Then
      MsgBox "Qty Transfer can't be more then the Original Qty Received", vbInformation + vbExclamation, "SAVE"
      Cancel = True
      Exit Sub
   End If
   
   If Val(txtQtyTrans) > Val(txtQtyRecvd) - Val(txtQtyInH) Then
      MsgBox "Sum of Qty Transfer and Qty Inhouse can't be more then the Original Qty Received", vbInformation + vbExclamation, "SAVE"
      Cancel = True
      Exit Sub
   End If
   
   txtQtyTransInH = Val(txtQtyTransInH) + Val(txtQtyTrans) - iOldQtyTrans
   txtQtyInH = Val(txtQtyInHouse) + iOldQtyTrans - Val(txtQtyTrans)
    txtQty = txtQtyTrans
End Sub

Private Sub txtTransCustId_Validate(Cancel As Boolean)
    If txtTransCustId = "" Then Exit Sub
    
    If Not IsNumeric(txtTransCustId) Then
        MsgBox "Not a valid Id", vbInformation, "CUSTOMER"
        txtTransCustId = ""
        Cancel = True
        Exit Sub
    End If
                                                                                                                                                                                                                                                               
    SqlStmt = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID= " _
             & " '" & Trim(txtTransCustId) & "'"
    Set dsCUSTID = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.lastservererr = 0 And dsCUSTID.recordcount > 0 Then
        txtTransCustomer = dsCUSTID.fields("CUSTOMER_NAME").Value
        Cancel = False
    Else
        If OraDatabase.lastservererr <> 0 Then
            MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation
            Exit Sub
        End If
        MsgBox "INVALID CUSTOMER ID !", vbInformation + vbExclamation, "CUSTOMER"
        txtTransCustId = ""
        Cancel = True
    End If
End Sub
Sub Clear_Screen()
   Dim Ctl As Control
   
   For Each Ctl In Controls
      If TypeOf Ctl Is TextBox Then
         Ctl.Text = ""
      End If
   Next Ctl
   
   txtPltNum.SetFocus
End Sub
