VERSION 5.00
Begin VB.Form frmPassw 
   BackColor       =   &H00FFFFC0&
   Caption         =   "DELETE DELIVERY "
   ClientHeight    =   6765
   ClientLeft      =   5265
   ClientTop       =   3735
   ClientWidth     =   7350
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
   ScaleHeight     =   6765
   ScaleWidth      =   7350
   Begin VB.Frame Frame1 
      BackColor       =   &H00FFFFC0&
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   3855
      Left            =   120
      TabIndex        =   2
      Top             =   2040
      Width           =   7095
      Begin VB.TextBox txtComm 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   2760
         TabIndex        =   5
         Top             =   960
         Width           =   1575
      End
      Begin VB.TextBox txtComment 
         Appearance      =   0  'Flat
         Height          =   1455
         Left            =   2760
         MaxLength       =   100
         MultiLine       =   -1  'True
         ScrollBars      =   2  'Vertical
         TabIndex        =   9
         Top             =   2160
         Width           =   4095
      End
      Begin VB.TextBox txtInitial 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   2760
         MaxLength       =   10
         TabIndex        =   8
         Top             =   1560
         Width           =   1575
      End
      Begin VB.TextBox txtDeliveryNum 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   2760
         MaxLength       =   10
         TabIndex        =   4
         Top             =   360
         Width           =   1575
      End
      Begin VB.Label Label5 
         Alignment       =   1  'Right Justify
         BackStyle       =   0  'Transparent
         Caption         =   "COMMODITY :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   1080
         TabIndex        =   10
         Top             =   1020
         Width           =   1455
      End
      Begin VB.Label Label4 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "REASON  :"
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
         Left            =   1560
         TabIndex        =   7
         Top             =   2160
         Width           =   930
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "INITIALS  :"
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
         Left            =   1530
         TabIndex        =   6
         Top             =   1590
         Width           =   960
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "DELIVERY NUMBER  :"
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
         Left            =   585
         TabIndex        =   3
         Top             =   413
         Width           =   1905
      End
   End
   Begin VB.CommandButton cmdpassword 
      Caption         =   "OK"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   3000
      TabIndex        =   11
      Top             =   6120
      Width           =   1005
   End
   Begin VB.TextBox txtpassword 
      Appearance      =   0  'Flat
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   330
      IMEMode         =   3  'DISABLE
      Left            =   3240
      PasswordChar    =   "*"
      TabIndex        =   1
      Top             =   247
      Width           =   1215
   End
   Begin VB.Label lblPrecheckLR 
      BackColor       =   &H00FFFFC0&
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   3240
      TabIndex        =   15
      Top             =   1320
      Width           =   3855
   End
   Begin VB.Label lblPrecheckCust 
      BackColor       =   &H00FFFFC0&
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   3240
      TabIndex        =   14
      Top             =   720
      Width           =   3855
   End
   Begin VB.Label Label7 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Pre-Delivery LR#:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   960
      TabIndex        =   13
      Top             =   1320
      Width           =   1815
   End
   Begin VB.Label Label6 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Pre-Delivery Cust:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   960
      TabIndex        =   12
      Top             =   720
      Width           =   1815
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackColor       =   &H00FFFFC0&
      BackStyle       =   0  'Transparent
      Caption         =   "ENTER PASSWORD  :"
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
      Left            =   1260
      TabIndex        =   0
      Top             =   300
      Width           =   1905
   End
End
Attribute VB_Name = "frmPassw"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private Sub cmdpassword_Click()

    If txtpassword = "" Then Exit Sub
    
    If UCase$(Trim$(txtpassword.Text)) = "GEM" Then
        

        Call DeleteDelivery
        
              
    
    Else
        MsgBox "Incorrect Password.  Please Try Again."
        Exit Sub
    End If
    
End Sub
Public Sub DeleteDelivery()
    Dim sWhere As String
    Dim sLotNum As String
    Dim lActivityNum As Long
    Dim lServiceCode As Long
    Dim lRecCount As Long
    
    
    If txtDeliveryNum = "" Then
        MsgBox "Enter Delivery Number", vbInformation, "DELETE"
        txtDeliveryNum.SetFocus
        Exit Sub
    End If
    
    If txtInitial = "" Then
        MsgBox "Enter your Intials", vbInformation, "DELETE"
        txtInitial.SetFocus
        Exit Sub
    End If
    
    If txtDeliveryNum <> sWithdrawalDeleteNum Then
        MsgBox "Entered Delivery# (" & txtDeliveryNum & ") was not the same as the one entered in the pre-check."
        txtInitial.SetFocus
        Exit Sub
    End If
    
    gsSqlStmt = "SELECT COUNT(*) THE_COUNT FROM CARGO_DELIVERY CD, CARGO_TRACKING CT " & _
                " Where CD.DELIVERY_NUM = " & txtDeliveryNum & " And CD.LOT_NUM = CT.LOT_NUM AND " & _
                " CT.COMMODITY_CODE = '" & txtComm & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value <= 0 Then
        MsgBox "Commodity not found in the selected order", vbInformation, "DELETE"
        txtComm.SetFocus
        Exit Sub
    End If
   
    
    OraSession.BeginTrans
    gsSqlStmt = "SELECT * FROM CARGO_DELIVERY WHERE DELIVERY_NUM = " & txtDeliveryNum
    Set dsCARGO_DELIVERY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY.Recordcount > 0 Then
        While Not dsCARGO_DELIVERY.EOF
            sLotNum = dsCARGO_DELIVERY.Fields("LOT_NUM").Value
            lActivityNum = dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value
            lServiceCode = dsCARGO_DELIVERY.Fields("SERVICE_CODE").Value
            
            'Get cargo activity details
            gsSqlStmt = "SELECT * FROM CARGO_ACTIVITY WHERE LOT_NUM = '" & sLotNum & "' AND "
            gsSqlStmt = gsSqlStmt & "ACTIVITY_NUM = " & lActivityNum & " AND "
            gsSqlStmt = gsSqlStmt & "SERVICE_CODE = " & lServiceCode
            
            Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            'Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.Recordcount > 0 Then
                'While Not dsCARGO_ACTIVITY.EOF
                'Get cargo tracking detail to return the qty in house
                gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & sLotNum & "'"
                Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.Recordcount > 0 Then
                    dsCARGO_TRACKING.Edit
                    dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = Val(dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value) + Val(dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value)
                    dsCARGO_TRACKING.Update
                End If
                'dsCARGO_ACTIVITY.MoveNext
                'Wend
            End If
        
            dsCARGO_DELIVERY.MoveNext
        Wend
    Else
        MsgBox "Invalid Delivery Number", vbInformation, "DELETE"
        Exit Sub
    End If
    
    gsSqlStmt = "SELECT * FROM CARGO_DELIVERY WHERE DELIVERY_NUM = " & txtDeliveryNum
    Set dsCARGO_DELIVERY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY.Recordcount > 0 Then
        While Not dsCARGO_DELIVERY.EOF
            sLotNum = dsCARGO_DELIVERY.Fields("LOT_NUM").Value
            lActivityNum = dsCARGO_DELIVERY.Fields("ACTIVITY_NUM").Value
            lServiceCode = dsCARGO_DELIVERY.Fields("SERVICE_CODE").Value
            
            'Delete all the records
            
            sWhere = " WHERE LOT_NUM = '" & sLotNum & "' AND "
            sWhere = sWhere & "ACTIVITY_NUM = " & lActivityNum & " AND "
            sWhere = sWhere & "SERVICE_CODE = " & lServiceCode
            
            gsSqlStmt = "DELETE FROM CARGO_ACTIVITY" & sWhere
            lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
            gsSqlStmt = "DELETE FROM CARGO_ACTIVITY_EXT" & sWhere
            lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
            
            dsCARGO_DELIVERY.MoveNext
        Wend
    End If
     
    'RESET WO Number to a Negative Number
    gsSqlStmt = "UPDATE CARGO_DELIVERY SET DELIVERY_NUM = DELIVERY_NUM * -1 WHERE DELIVERY_NUM = " & txtDeliveryNum
    lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
    
   gsSqlStmt = "SELECT * FROM CARGO_DELIVERY_DELETE"
    Set dsCARGO_DELIVERY_DELETE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        dsCARGO_DELIVERY_DELETE.AddNew
        dsCARGO_DELIVERY_DELETE.Fields("DELIVERY_NUM").Value = -1 * Trim(txtDeliveryNum)
        dsCARGO_DELIVERY_DELETE.Fields("INITIALS").Value = Trim(txtInitial)
        dsCARGO_DELIVERY_DELETE.Fields("DELETE_DATE").Value = Format(Now, "MM/DD/YYYY")
        dsCARGO_DELIVERY_DELETE.Fields("COMMENTS").Value = Trim(txtComment)
        dsCARGO_DELIVERY_DELETE.Update
    End If
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        MsgBox "DELIVERY IS DELETED SUCCESSFULLY", vbInformation, "DELETE DELIVERY"
    Else
        OraSession.RollBack
        MsgBox OraDatabase.LastServerErrText, vbCritical, "error"
        OraDatabase.LastServerErrReset
    End If
    
    Unload Me
End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    lblPrecheckCust.Caption = ""
    lblPrecheckLR.Caption = ""

    gsSqlStmt = "SELECT CUSTOMER_NAME, VESSEL_NAME FROM " & _
                "CARGO_DELIVERY CD, CARGO_TRACKING CT, VESSEL_PROFILE VP, CUSTOMER_PROFILE CP, CARGO_MANIFEST CM " & _
                " Where CD.DELIVERY_NUM = " & sWithdrawalDeleteNum & " And CD.LOT_NUM = CT.LOT_NUM " & _
                " AND CT.OWNER_ID = CP.CUSTOMER_ID AND CT.LOT_NUM = CM.CONTAINER_NUM " & _
                " AND CM.LR_NUM = VP.LR_NUM"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If dsSHORT_TERM_DATA.Recordcount <= 0 Then
        ' nothing to display
    Else
        lblPrecheckCust.Caption = dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value
        lblPrecheckLR.Caption = dsSHORT_TERM_DATA.Fields("VESSEL_NAME").Value
    End If

End Sub

Private Sub txtComment_Change()
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtInitial_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtpassword_Validate(Cancel As Boolean)
    
    If txtpassword = "" Then Exit Sub
    
    If UCase$(Trim$(txtpassword.Text)) = "GEM" Then
        Cancel = False
    Else
        MsgBox "Invalid Password", vbInformation, "PASSWORD"
        txtpassword = ""
        Cancel = True
    End If
End Sub
