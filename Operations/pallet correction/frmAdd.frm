VERSION 5.00
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Begin VB.Form frmAdd 
   AutoRedraw      =   -1  'True
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "ADD "
   ClientHeight    =   7410
   ClientLeft      =   3675
   ClientTop       =   1770
   ClientWidth     =   7920
   ControlBox      =   0   'False
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
   LockControls    =   -1  'True
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   7410
   ScaleWidth      =   7920
   Begin VB.Frame Frame2 
      BackColor       =   &H00FFFFC0&
      Height          =   2655
      Left            =   203
      TabIndex        =   23
      Top             =   3840
      Width           =   7515
      Begin VB.TextBox txtMiscBill 
         Height          =   330
         Left            =   5400
         MaxLength       =   1
         TabIndex        =   39
         Top             =   2160
         Width           =   275
      End
      Begin VB.ComboBox cboAction 
         Height          =   345
         Left            =   1620
         TabIndex        =   35
         Top             =   953
         Width           =   1815
      End
      Begin VB.CommandButton cmdList 
         Height          =   315
         Index           =   0
         Left            =   3420
         Picture         =   "frmAdd.frx":0000
         Style           =   1  'Graphical
         TabIndex        =   34
         Top             =   1568
         Width           =   375
      End
      Begin VB.TextBox txtChecker 
         Height          =   330
         Left            =   1620
         TabIndex        =   33
         TabStop         =   0   'False
         Top             =   1568
         Width           =   1815
      End
      Begin VB.TextBox txtArrNum 
         Height          =   330
         Left            =   1620
         TabIndex        =   32
         Top             =   2160
         Width           =   1575
      End
      Begin VB.TextBox txtOrderNum 
         Height          =   330
         Left            =   5400
         TabIndex        =   28
         Top             =   360
         Width           =   1695
      End
      Begin VB.CommandButton cmdList 
         Height          =   315
         Index           =   1
         Left            =   6960
         Picture         =   "frmAdd.frx":0102
         Style           =   1  'Graphical
         TabIndex        =   0
         Top             =   968
         Width           =   375
      End
      Begin VB.TextBox txtQty 
         Height          =   330
         Left            =   5400
         TabIndex        =   1
         Top             =   1560
         Width           =   1455
      End
      Begin VB.TextBox txtCustomer 
         Height          =   330
         Left            =   5400
         TabIndex        =   6
         TabStop         =   0   'False
         Top             =   968
         Width           =   1575
      End
      Begin MSComCtl2.DTPicker DtpTime 
         Height          =   375
         Left            =   2700
         TabIndex        =   36
         Top             =   345
         Width           =   1440
         _ExtentX        =   2540
         _ExtentY        =   661
         _Version        =   393216
         CustomFormat    =   "HH:MM:SS"
         Format          =   16580610
         UpDown          =   -1  'True
         CurrentDate     =   36621
      End
      Begin MSComCtl2.DTPicker DtpDate 
         Height          =   375
         Left            =   1620
         TabIndex        =   37
         Top             =   345
         Width           =   1095
         _ExtentX        =   1931
         _ExtentY        =   661
         _Version        =   393216
         CustomFormat    =   "MM/dd/yy"
         Format          =   16580611
         CurrentDate     =   36621
      End
      Begin VB.Label Label2 
         BackColor       =   &H00FFFFC0&
         Caption         =   "MiscBilled? :"
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
         Left            =   4200
         TabIndex        =   38
         Top             =   2213
         Width           =   1095
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Arrival Num  :"
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
         Left            =   270
         TabIndex        =   31
         Top             =   2213
         Width           =   1140
      End
      Begin VB.Label Label9 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Date / Time  :"
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
         Left            =   315
         TabIndex        =   30
         Top             =   420
         Width           =   1095
      End
      Begin VB.Label Label12 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Order No.  :"
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
         Left            =   4215
         TabIndex        =   29
         Top             =   420
         Width           =   1095
      End
      Begin VB.Label Label14 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Quantity  :"
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
         Left            =   4440
         TabIndex        =   27
         Top             =   1620
         Width           =   870
      End
      Begin VB.Label Label13 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Customer : "
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
         Left            =   4350
         TabIndex        =   26
         Top             =   1020
         Width           =   960
      End
      Begin VB.Label Label11 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Checker  :"
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
         Left            =   540
         TabIndex        =   25
         Top             =   1620
         Width           =   870
      End
      Begin VB.Label Label10 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Action  :"
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
         Left            =   720
         TabIndex        =   24
         Top             =   1020
         Width           =   690
      End
   End
   Begin VB.CommandButton CmdSave 
      Caption         =   "&Save"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   1373
      TabIndex        =   3
      Top             =   6720
      Width           =   1335
   End
   Begin VB.CommandButton cmdClear 
      Caption         =   "&Clear"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   3293
      TabIndex        =   4
      Top             =   6720
      Width           =   1335
   End
   Begin VB.CommandButton cmdExit 
      Cancel          =   -1  'True
      Caption         =   "&Exit"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   5213
      TabIndex        =   5
      Top             =   6720
      Width           =   1335
   End
   Begin VB.Frame Frame1 
      BackColor       =   &H00FFFFC0&
      Height          =   3495
      Left            =   653
      TabIndex        =   7
      Top             =   180
      Width           =   6615
      Begin VB.TextBox txtIntls 
         Height          =   330
         Left            =   3683
         TabIndex        =   2
         Top             =   2947
         Width           =   1455
      End
      Begin VB.Label lblPltInfo 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "yy"
         Height          =   225
         Index           =   1
         Left            =   2040
         TabIndex        =   22
         Top             =   870
         Width           =   210
      End
      Begin VB.Label lblVesselNo 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Vessel No  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00404040&
         Height          =   225
         Left            =   855
         TabIndex        =   21
         Top             =   864
         Width           =   930
      End
      Begin VB.Label lblPltInfo 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Label7"
         Height          =   225
         Index           =   6
         Left            =   5160
         TabIndex        =   20
         Top             =   2400
         Width           =   480
      End
      Begin VB.Label lblPltInfo 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Label6"
         Height          =   225
         Index           =   5
         Left            =   2040
         TabIndex        =   19
         Top             =   2400
         Width           =   480
      End
      Begin VB.Label lblPltInfo 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Label5"
         Height          =   225
         Index           =   4
         Left            =   5160
         TabIndex        =   18
         Top             =   1875
         Width           =   480
      End
      Begin VB.Label lblPltInfo 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Label4"
         Height          =   225
         Index           =   3
         Left            =   2040
         TabIndex        =   17
         Top             =   1875
         Width           =   480
      End
      Begin VB.Label lblPltInfo 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Label3"
         Height          =   225
         Index           =   2
         Left            =   2040
         TabIndex        =   16
         Top             =   1365
         Width           =   480
      End
      Begin VB.Label lblPltInfo 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "yy"
         Height          =   225
         Index           =   0
         Left            =   2040
         TabIndex        =   15
         Top             =   360
         Width           =   210
      End
      Begin VB.Label lblRecdDate 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Date Received  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00404040&
         Height          =   225
         Left            =   3705
         TabIndex        =   14
         Top             =   1920
         Width           =   1260
      End
      Begin VB.Label lblOrgQtyRecvd 
         Alignment       =   1  'Right Justify
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
         ForeColor       =   &H00404040&
         Height          =   225
         Left            =   600
         TabIndex        =   13
         Top             =   1872
         Width           =   1185
      End
      Begin VB.Label lblCommCode 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity Code  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00404040&
         Height          =   225
         Left            =   270
         TabIndex        =   12
         Top             =   1368
         Width           =   1515
      End
      Begin VB.Label lblIntls 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Initials  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00404040&
         Height          =   225
         Left            =   2783
         TabIndex        =   11
         Top             =   3000
         Width           =   720
      End
      Begin VB.Label lblOwnerId 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Owner Id  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00404040&
         Height          =   225
         Left            =   4080
         TabIndex        =   10
         Top             =   2400
         Width           =   885
      End
      Begin VB.Label lblQtyInHouse 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Qty Inhouse  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00404040&
         Height          =   225
         Left            =   660
         TabIndex        =   9
         Top             =   2400
         Width           =   1125
      End
      Begin VB.Label lblPltNum 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Pallet Barcode  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00404040&
         Height          =   225
         Left            =   495
         TabIndex        =   8
         Top             =   360
         Width           =   1290
      End
   End
End
Attribute VB_Name = "frmAdd"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim iCustId As Integer
Dim iEmpId As Integer
Dim iSerCode As Integer

Private Sub cmdClear_Click()
    iCustId = ""
    iEmpId = ""
    
    cboAction = ""
    txtChecker = ""
    txtOrderNum = ""
    txtCustomer = ""
    txtQty = ""
    txtArrNum = ""
    txtMiscBill = ""
    
    DtpDate.Value = Format(Now, "MM/DD/YYYY")
    DtpTime.Value = Format(Now, "HH:MM")
    
End Sub

Private Sub cmdExit_Click()
    
    Unload Me
    
End Sub

Private Sub cmdList_Click(Index As Integer)
    Dim iPos As Integer
    Dim iRec As Long
    Dim dsList As Object
    Dim sqlstmt As String
    
    load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.lstPV.Clear
    
    Select Case Index
        Case 0
            frmPV.Caption = "CHECKER LIST"
            'where employee_id > 0 AND login_id not in ('TEST1','TEST2','TEST3')
            sqlstmt = "select SUBSTR(employee_id, -6) THE_EMP, EMPLOYEE_NAME login_id from EMPLOYEE order by employee_id"
            Set dsList = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
            If OraDatabase.lastservererr = 0 And dsList.recordcount > 0 Then
                For iRec = 1 To dsList.recordcount
                    frmPV.lstPV.AddItem dsList.fields("THE_EMP").Value & " : " & dsList.fields("Login_Id").Value
                    dsList.MoveNext
                Next iRec
            End If
            
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            iEmpId = Left$(gsPVItem, iPos - 1)
            txtChecker.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    
    Case 1
    
        frmPV.Caption = "CUSTOMER LIST"
            sqlstmt = "select Customer_id,Customer_name from Customer_profile order by " _
                    & " customer_id"
            Set dsList = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
            If OraDatabase.lastservererr = 0 And dsList.recordcount > 0 Then
                For iRec = 1 To dsList.recordcount
                    frmPV.lstPV.AddItem dsList.fields("Customer_Id").Value & " : " & dsList.fields("Customer_name").Value
                    dsList.MoveNext
                Next iRec
            End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            iCustId = Left$(gsPVItem, iPos - 1)
            txtCustomer.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
End Select
    
End Sub

Private Sub cmdSave_Click()
    Dim sqlstmt As String
    Dim dsMax As Object
    Dim iError As Boolean
    
    If cboAction.Text = "" Then
        MsgBox "Choose an action !", vbInformation, "PALLET DETAILS"
        Exit Sub
    End If
    
    If txtChecker.Text = "" Then
        MsgBox "Choose Checker from the list !", vbInformation, "PALLET DETAILS"
        Exit Sub
    End If
    
    If txtArrNum.Text = "" Then
        MsgBox "Enter Arrival Num !", vbInformation, "ARRIVAL NUMBER"
        Exit Sub
    End If
    
    If txtOrderNum.Text = "" Then
        MsgBox "Enter Order Number !", vbInformation, "PALLET DETAILS"
        Exit Sub
    End If
    
    If txtCustomer.Text = "" Then
        MsgBox "Choose Customer from the list !", vbInformation, "PALLET DETAILS"
        Exit Sub
    End If
    
    If txtQty.Text = "" Then
        MsgBox "Enter Quantity !", vbInformation, "PALLET DETAILS"
        Exit Sub
    End If
    
    If txtIntls.Text = "" Then
        MsgBox "Put your Initials !", vbInformation, "PALLET DETAILS"
        Exit Sub
    End If
    
    Select Case cboAction.ListIndex
        Case 0
            iSerCode = 6
        Case 1
            iSerCode = 7
        Case 2
            iSerCode = 8
        Case 3
            iSerCode = 9
        Case 4
            iSerCode = 12
    End Select
    
    If Format(DtpTime, "HH:MM:SS") = "00:00:00" Then
        MsgBox "Time should not be 12:00:00 AM (Midnight)", vbInformation, "PALLET DETAILS"
        DtpTime = Format(Now, "HH:MM")
        Exit Sub
    End If
    
    If ValidQty = False Then Exit Sub
    
    OraSession.begintrans
    
    sqlstmt = " SELECT MAX(ACTIVITY_NUM) MaxNum FROM CARGO_ACTIVITY WHERE PALLET_ID= " _
            & "'" & Trim(lblPltInfo(0)) & "'"
    Set dsMax = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
'    Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    sqlstmt = " SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID='" & Trim(lblPltInfo(0)) & "'" _
            & " order by Activity_Num"
    Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
        If OraDatabase.lastservererr = 0 Then
            With dsCARGO_ACTIVITY
                .AddNew
                .fields("PALLET_ID").Value = Trim(lblPltInfo(0))
                If Not IsNull(dsMax.fields("MaxNum").Value) Then
                    .fields("ACTIVITY_NUM").Value = dsMax.fields("MaxNum").Value + 1
                Else
                    .fields("ACTIVITY_NUM").Value = 1
                End If
                .fields("SERVICE_CODE").Value = iSerCode
                .fields("QTY_CHANGE").Value = Val(Trim(txtQty))
                .fields("ACTIVITY_ID").Value = iEmpId
'               .Fields("ACTIVITY_DESCRIPTION").Value = " "
                .fields("ORDER_NUM").Value = Trim$(txtOrderNum)
                .fields("CUSTOMER_ID").Value = iCustId
                .fields("DATE_OF_ACTIVITY").Value = Format(DtpDate.Value, "MM/DD/YYYY") & " " & Format(DtpTime.Value, "HH:MM:SS")
                .fields("ARRIVAL_NUM").Value = Trim$(txtArrNum)
                .fields("TO_MISCBILL").Value = Trim$("" & txtMiscBill.Text)
                 
                .Update
            End With
        Else
            iError = True
        End If
        '? Check Query -Ramesh
        ':Added Arrival_num and Receiver_id in where clause
        sqlstmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID ='" & _
                     Trim$(lblPltInfo(0)) & "'AND RECEIVER_ID='" & iCustId & "' AND ARRIVAL_NUM='" & txtArrNum & "'"
                     
        Set dsCargo_Tracking = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
        If OraDatabase.lastservererr = 0 And dsCargo_Tracking.recordcount > 0 Then
            With dsCargo_Tracking
                .edit
                .fields("QTY_RECEIVED").Value = Val(lblPltInfo(3))
                .fields("QTY_IN_HOUSE").Value = Val(lblPltInfo(5))
                .Update
            End With
        End If
        If OraDatabase.lastservererr <> 0 Then
            iError = True
        End If
        
        If Not iError Then
        OraSession.committrans
        MsgBox "CHANGES ARE MADE SUCCESSFULLY .", vbInformation, "PALLET CORRECTION"
        dsCargo_Tracking_Global.DbRefresh
    Else
        OraSession.rollback
        MsgBox "ERROR WHILE PROCESSING THE DATA !.", vbCritical + vbInformation, "PALLET CORRECTION"
    End If
    
    Unload Me
End Sub
Function ValidQty() As Boolean
    
    Select Case iSerCode      'Service_code
        Case 6   'DELIVERY
            If txtQty < 0 Then
                If MsgBox("Quantity cannot be less then zero." & vbCrLf & _
                          "Do You still want to use it ? ", vbQuestion + vbYesNo, "PALLET DETAILS") = vbNo Then
                    txtQty = ""
                    ValidQty = False
                    Exit Function
                End If
            End If
                
            If Val(txtQty) + Val(lblPltInfo(5)) > Val(lblPltInfo(3)) Then
                If MsgBox("The Qty Inhouse cannot exceed then the Qty Received " & vbCrLf & _
                          " DO you want to use it ? ", vbQuestion + vbYesNo, "PALLET DETAILS") = vbNo Then
                    txtQty = ""
                    ValidQty = False
                    Exit Function
                End If
            End If
                    
            lblPltInfo(5) = Val(lblPltInfo(5)) - Val(txtQty)
                    
        Case 7     'RETURN
            If Val(txtQty) > 0 Then
                If MsgBox("This value should be less then zero." & vbCrLf & _
                          "Do you still want to use it ?", vbQuestion + vbYesNo, "PALLET DETAIL") = vbNo Then
                    txtQty = ""
                    ValidQty = False
                    Exit Function
                End If
            End If
                    
            If Val(lblPltInfo(5)) + Val(txtQty) > Val(lblPltInfo(3)) Then
                If MsgBox("Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                          "Do you still want to use it ? ", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                    txtQty = ""
                    ValidQty = False
                    Exit Function
                End If
            End If
                    
            lblPltInfo(5) = Val(lblPltInfo(5)) + Val(txtQty)
            lblPltInfo(3) = Val(lblPltInfo(3)) + Val(txtQty)
            
        Case 8    'FROM PORT
            
            If Val(txtQty) < 0 Then
                If MsgBox("Quantity Cannot be zero. " & vbCrLf & " Do you still want to use " _
                          & "it ?", vbQuestion + vbYesNo, "PALLET DETAILS") = vbNo Then
                    txtQty = ""
                    ValidQty = False
                    Exit Function
                End If
            End If
                
            lblPltInfo(5) = Val(lblPltInfo(5)) + Val(txtQty)
            lblPltInfo(3) = Val(lblPltInfo(3)) + Val(txtQty)
                
        Case 9    'RECOUP
            If Val(lblPltInfo(5)) + Val(txtQty) > Val(lblPltInfo(3)) Then
                If MsgBox("Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                         "Do you still want to use it ? ", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                    txtQty = ""
                    ValidQty = False
                    Exit Function
                End If
            End If
                
            lblPltInfo(5) = Val(lblPltInfo(5)) + Val(txtQty)
    
        Case 12   'VOID
            
            If txtQty > 0 Then
                If MsgBox("This value should be less then zero." & vbCrLf & " Do you still want to " _
                          & "use it ?", vbQuestion + vbYesNo, "PALLET DETAIL") = vbNo Then
                    txtQty = ""
                    ValidQty = False
                    Exit Function
                End If
            End If
            
            If Val(lblPltInfo(5)) + Val(txtQty) > Val(lblPltInfo(3)) Then
                If MsgBox("Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                          "Do you still want to use it ? ", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                    txtQty = ""
                    ValidQty = False
                    Exit Function
                End If
            End If
                
            lblPltInfo(5) = Val(lblPltInfo(5)) + Abs(Val(txtQty))
            lblPltInfo(3) = Val(lblPltInfo(3)) + Abs(Val(txtQty))
                
    End Select
        
    If Val(lblPltInfo(5)) < 0 Then
        If MsgBox("InHouse Quantity cannot be equal to or less then zero." & vbCrLf & _
                  "Do you still want to use it ?", vbQuestion + vbYesNo, "PALLET DETAIL") = vbNo Then
            lblPltInfo(3) = Trim(frmPltCorrection.txtQtyRecvd)
            lblPltInfo(5) = Trim(frmPltCorrection.txtQtyRecvd)
            txtQty = ""
            ValidQty = False
            Exit Function
        End If
    End If
    
    If Val(lblPltInfo(3)) < Val(lblPltInfo(5)) Then
        If MsgBox("InHouse Quantity cannot be more then Quantity Received ." & vbCrLf & _
                  "Do you still want to use it ?", vbQuestion + vbYesNo, "PALLET DETAIL") = vbNo Then
            lblPltInfo(3) = Trim(frmPltCorrection.txtQtyRecvd)
            lblPltInfo(5) = Trim(frmPltCorrection.txtQtyRecvd)
            txtQty = ""
            ValidQty = False
            Exit Function
        End If
    End If
    
    ValidQty = True
End Function

Private Sub Form_Load()
    With frmPltCorrection
        lblPltInfo(0) = Trim(.txtPltNum)
        lblPltInfo(1) = Trim(.txtVesselNo) & " - " & Trim(.txtVessel)
        lblPltInfo(2) = Trim(.txtCommCode) & " - " & .txtComm
        lblPltInfo(3) = Trim(.txtQtyRecvd)
        lblPltInfo(4) = Trim(.txtDtRecd) & " - " & Trim(.txtTmRecd)
        lblPltInfo(5) = Trim(.txtQtyInHouse)
        lblPltInfo(6) = Trim(.txtOwnerId) & " - " & .txtOwner
        txtIntls = Trim(.txtIntls)
    End With
    
    cboAction.AddItem "DELIVERY", 0
    cboAction.AddItem "RETURN", 1
    cboAction.AddItem "FROM PORT", 2
    cboAction.AddItem "RECOUP", 3
    cboAction.AddItem "VOID", 4
End Sub
Private Sub txtChecker_KeyPress(KeyAscii As Integer)
    KeyAscii = 0
End Sub
Private Sub txtCustomer_KeyPress(KeyAscii As Integer)
    KeyAscii = 0
End Sub
