VERSION 5.00
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.1#0"; "MSCOMCTL.OCX"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Begin VB.Form frmRePrint 
   AutoRedraw      =   -1  'True
   Caption         =   "REPRINT LEASE INVOICES"
   ClientHeight    =   3240
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   6135
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
   ScaleHeight     =   3240
   ScaleWidth      =   6135
   StartUpPosition =   3  'Windows Default
   Begin Crystal.CrystalReport Crw1 
      Left            =   240
      Top             =   2040
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin MSComctlLib.StatusBar StatusBar1 
      Align           =   2  'Align Bottom
      Height          =   330
      Left            =   0
      TabIndex        =   6
      Top             =   2910
      Width           =   6135
      _ExtentX        =   10821
      _ExtentY        =   582
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
         EndProperty
      EndProperty
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "EXIT"
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
      Left            =   3180
      TabIndex        =   5
      Top             =   2040
      Width           =   1455
   End
   Begin VB.CommandButton cmdProcess 
      Caption         =   "PROCESS"
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
      Left            =   1500
      TabIndex        =   4
      Top             =   2040
      Width           =   1455
   End
   Begin VB.TextBox txtEdInvNum 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2880
      TabIndex        =   1
      Top             =   1267
      Width           =   2655
   End
   Begin VB.TextBox txtStInvNum 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   2880
      TabIndex        =   0
      Top             =   480
      Width           =   2655
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "ENDING INVOICE #  :"
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
      TabIndex        =   3
      Top             =   1320
      Width           =   1830
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "STARTING INVOICE #  :"
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
      Left            =   465
      TabIndex        =   2
      Top             =   540
      Width           =   2085
   End
End
Attribute VB_Name = "frmRePrint"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iROW As Long
Dim iNum As Integer
Dim sInvNum As String
Dim sInvDT As String
Dim bStart As Boolean
Dim iCustId  As Integer
Dim iSerCode As Integer
Dim dTotalAmount  As Double
Dim dGrandTotal As Double
Dim lInvoiceNum As Long
Dim iline As Integer
Dim sCityStateZip As String
Dim i As Integer
Dim SqlStmt As String
Dim iRec As Integer
Dim Duration As String
Private Sub cmdExit_Click()

    Unload Me
    
End Sub
Private Sub cmdProcess_Click()
    
    dGrandTotal = 0
     
    If txtStInvNum = "" Then Exit Sub
    
    bStart = True
    iROW = 0
    
    StatusBar1.SimpleText = "PROCESSING ..."
    
    If txtStInvNum = txtEdInvNum Then            'For printing only one invoice no.
        SqlStmt = "SELECT * FROM BILLING WHERE SERVICE_STATUS = 'INVOICED' AND" & _
                " BILLING_TYPE IN ('LEASE')AND Customer_ID is NOT NULL " & _
                " AND invoice_num = '" & Trim(txtStInvNum) & "' "
                
    ElseIf txtEdInvNum = "" Then      'For printing only one invoice no.
        SqlStmt = "SELECT * FROM BILLING WHERE SERVICE_STATUS = 'INVOICED' AND" & _
                " BILLING_TYPE IN ('LEASE')AND Customer_ID is NOT NULL " & _
                " AND invoice_num = '" & Trim(txtStInvNum) & "' " & _
                " ORDER BY INVOICE_NUM"
    ElseIf txtStInvNum <> txtEdInvNum Then    'For printing only more then one invoice no.
        SqlStmt = "SELECT * FROM BILLING WHERE SERVICE_STATUS = 'INVOICED' AND" & _
                " BILLING_TYPE IN ('LEASE')AND Customer_ID is NOT NULL " & _
                " AND INVOICE_Num between '" & Trim(txtStInvNum) & "' AND '" & Trim(txtEdInvNum) & "' " & _
                " ORDER BY INVOICE_NUM"
    End If
              
    Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBILLING.recordcount > 0 Then
       
       Call SubPreInv
       
       For iRec = 1 To dsBILLING.recordcount
       
            DoEvents
            
            'Get from Customer table based on Customer Code
            SqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID =" _
                      & "'" & dsBILLING.fields("CUSTOMER_ID").Value & "'"
            Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
            
            'Check to see if we need to change the invoice number and headings on the page
            If (lInvoiceNum <> dsBILLING.fields("INVOICE_NUM").Value) Then
            
            If bStart = False Then
                    For iline = 1 To 2
                        If iNum = 54 Then Call NEW_PAGE
                        iNum = iNum + 1
                        iROW = iROW + 1
                        Call PreInv_AddNew(iROW, "", 0, 0)
                    Next iline
                    
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(5) & "-------------------------------------------" _
                    & "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
                                     
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(140) & "INVOICE TOTAL : ", 0, Format(Round(dTotalAmount, 3), "##,###,###,##0.00"))
                   
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(5) & "-------------------------------------------" _
                    & "------------------------------------------------------------------------------------------------------------------------------------------------------", 3, 0)
                End If
                
                bStart = False
                
                dGrandTotal = dGrandTotal + dTotalAmount
                dTotalAmount = 0
                
                iCustId = dsBILLING.fields("CUSTOMER_ID").Value
                iSerCode = dsBILLING.fields("SERVICE_CODE").Value
                lInvoiceNum = dsBILLING.fields("INVOICE_NUM").Value
                sInvNum = CStr(lInvoiceNum)
                sInvDT = Format(dsBILLING.fields("INVOICE_date").Value, "MM/DD/YYYY")
                 
                StatusBar1.SimpleText = "PROCESSING LEASE INVOICE NUMBER : " & lInvoiceNum
                               
                iNum = 0
                iNum = iNum + 1
                iROW = iROW + 1
                Call PreInv_AddNew(iROW, Space(227) & CStr(lInvoiceNum), 1, 0)
                
                If iNum = 54 Then Call NEW_PAGE
                iNum = iNum + 1
                iROW = iROW + 1
                Call PreInv_AddNew(iROW, "", 0, 0)
                
                If iNum = 54 Then Call NEW_PAGE
                iNum = iNum + 1
                iROW = iROW + 1
                Call PreInv_AddNew(iROW, Space(227) & sInvDT, 0, 0)
                
                For iline = 1 To 7
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, "", 0, 0)
                Next iline
                    
                If Not IsNull(dsBILLING.fields("CARE_OF")) Then
                    If (dsBILLING.fields("CARE_OF").Value = "1") Or (dsBILLING.fields("CARE_OF").Value = "Y") Then
                        If iNum = 54 Then Call NEW_PAGE
                        iNum = iNum + 1
                        iROW = iROW + 1
                        Call PreInv_AddNew(iROW, Space(34) & fnVesselName(dsBILLING.fields("LR_NUM").Value), 0, 0)
                        
                        If iNum = 54 Then Call NEW_PAGE
                        iNum = iNum + 1
                        iROW = iROW + 1
                        Call PreInv_AddNew(iROW, Space(34) & "C/O " & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
                    Else
                        If iNum = 54 Then Call NEW_PAGE
                        iNum = iNum + 1
                        iROW = iROW + 1
                        Call PreInv_AddNew(iROW, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
                    End If
                Else
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
                End If
                
                If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value) Then
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value, 0, 0)
                End If
                
                If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) Then
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value, 0, 0)
                End If
                
                If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value) Then
                    sCityStateZip = dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value
                End If
                If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value) Then
                    sCityStateZip = sCityStateZip & ", " & dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value
                End If
                If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value) Then
                    sCityStateZip = sCityStateZip & " - " & dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value
                End If
                If iNum = 54 Then Call NEW_PAGE
                iNum = iNum + 1
                iROW = iROW + 1
                Call PreInv_AddNew(iROW, Space(34) & sCityStateZip, 0, 0)
                
                If dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value <> "US" Then
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, Space(34) & fnCountryName(dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value), 0, 0)
                End If
            
                For iline = 1 To 8
                    If iNum = 54 Then Call NEW_PAGE
                    iNum = iNum + 1
                    iROW = iROW + 1
                    Call PreInv_AddNew(iROW, "", 0, 0)
                Next iline
                    
                If iNum = 54 Then Call NEW_PAGE
                iNum = iNum + 1
                iROW = iROW + 1
                
                Call PreInv_AddNew(iROW, Space(8) & dsBILLING.fields("SERVICE_START").Value & Space(15) _
                                   & dsBILLING.fields("SERVICE_DESCRIPTION").Value & "   " & dsBILLING.fields("SERVICE_QTY").Value & " " & dsBILLING.fields("SERVICE_UNIT").Value _
                                   & "@  $" & dsBILLING.fields("SERVICE_RATE").Value & "$ " & dsBILLING.fields("SERVICE_UNIT").Value _
                                   & "  For Period : " & dsBILLING.fields("SERVICE_START").Value & " - " & dsBILLING.fields("SERVICE_STOP").Value, 0, Format(dsBILLING.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
                             
'                If iNum = 54 Then Call NEW_PAGE
'                iNum = iNum + 1
'                iROW = iROW + 1
'
'                Call PreInv_AddNew(iROW, Space(35) & "FOR : " & dsBILLING.fields("SERVICE_START").Value & " - " & dsBILLING.fields("SERVICE_STOP").Value, 0, Format(dsBILLING.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
            
                dTotalAmount = dTotalAmount + dsBILLING.fields("service_amount").Value
            Else
                DoEvents
                
                dTotalAmount = dTotalAmount + dsBILLING.fields("SERVICE_AMOUNT").Value
                
                           
                If iNum = 54 Then Call NEW_PAGE
                iNum = iNum + 1
                iROW = iROW + 1
                
                Call PreInv_AddNew(iROW, Space(8) & dsBILLING.fields("SERVICE_START").Value & Space(15) _
                                   & dsBILLING.fields("SERVICE_DESCRIPTION").Value & "   " & dsBILLING.fields("SERVICE_QTY").Value & " " & dsBILLING.fields("SERVICE_UNIT").Value _
                                   & "@  $" & dsBILLING.fields("SERVICE_RATE").Value & "$ " & dsBILLING.fields("SERVICE_UNIT").Value _
                                   & "  For Period : " & dsBILLING.fields("SERVICE_START").Value & " - " & dsBILLING.fields("SERVICE_STOP").Value, 0, Format(dsBILLING.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
                             
'                If iNum = 54 Then Call NEW_PAGE
'                iNum = iNum + 1
'                iROW = iROW + 1
'
'                Call PreInv_AddNew(iROW, Space(35) & "FOR : " & dsBILLING.fields("SERVICE_START").Value & " - " & dsBILLING.fields("SERVICE_STOP").Value, 0, Format(dsBILLING.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
            End If
            dsBILLING.MoveNext
        Next iRec
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox "Error " & OraDatabase.LastServerErrText & " occured." & vbCrLf & _
                    " Not able to print LEASE INVOICES !", vbExclamation
        
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
            
        MsgBox "No Records Found For LEASE INVOICES !", vbInformation + vbExclamation
        
        Exit Sub
    End If
    
    For iline = 1 To 2
        If iNum = 54 Then Call NEW_PAGE
        iNum = iNum + 1
        iROW = iROW + 1
        Call PreInv_AddNew(iROW, "", 0, 0)
    Next iline
    
    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iROW = iROW + 1
    Call PreInv_AddNew(iROW, Space(5) & "-------------------------------------------" _
    & "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
                                    
    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iROW = iROW + 1
    Call PreInv_AddNew(iROW, Space(140) & "INVOICE TOTAL : ", 0, Format(Round(dTotalAmount, 3), "##,###,###,##0.00"))
                   
    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iROW = iROW + 1
    Call PreInv_AddNew(iROW, Space(5) & "-------------------------------------------" _
    & "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
    
    dGrandTotal = dGrandTotal + dTotalAmount
    
    iROW = iROW + 1
    Call PreInv_AddNew(iROW, "", 2, 0)
    For i = 1 To 34
        iROW = iROW + 1
        Call PreInv_AddNew(iROW, "", 0, 0)
    Next i
    
    iROW = iROW + 1
    Call PreInv_AddNew(iROW, Space(45) & "GRAND TOTAL OF LEASE INVOICES FOR THE DATE  " & Format(Now, "MM/DD/YYYY") & "  :  " & Format(Round(dGrandTotal, 3), "##,###,###,##0.00"), 0, 0)
    
       
    StatusBar1.SimpleText = ""
    
    Crw1.ReportFileName = App.Path & "\BNIINV.rpt"
    If Crw1.LastErrorNumber <> 0 Then MsgBox Crw1.LastErrorString
    Crw1.Connect = "DSN = BNI;UID = sag_owner;PWD = sag"
    If Crw1.LastErrorNumber <> 0 Then MsgBox Crw1.LastErrorString
    Crw1.PrintReport
    
    If Crw1.LastErrorNumber <> 0 Then MsgBox Crw1.LastErrorString
    
End Sub
Sub PreInv_AddNew(RowNum As Long, Row_Text As String, eof As Integer, Amt As Double)
    
    With dsPreInv
        .AddNew
        .fields("Row_Num").Value = RowNum
        .fields("Text").Value = Row_Text
        .fields("eof").Value = eof
        .fields("AMT").Value = Amt
        .Update
    End With
    
End Sub
Sub NEW_PAGE()

    Dim iline As Integer
       
    iNum = 0
    iROW = iROW + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iROW, "", 1, 0)
        
    iROW = iROW + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iROW, Space(227) & sInvNum, 0, 0)
       
    iROW = iROW + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iROW, "", 0, 0)
    
    iline = iline + 1
    iROW = iROW + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iROW, Space(227) & sInvDT, 0, 0)
    
    For iline = 1 To 33
        iline = iline + 1
        iROW = iROW + 1
        iNum = iNum + 1
        Call PreInv_AddNew(iROW, "", 0, 0)
    Next iline
    
End Sub
Private Sub Form_Load()

    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
 
    SqlStmt = "SELECT MAX(INVOICE_NUM) MaxInvNum FROM BILLING WHERE BILLING_TYPE='LEASE'"
    Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And Not IsNull(dsBILLING.fields("MaxInvNum").Value) Then
'        txtStInvNum = dsBILLING.fields("MaxInvNum").Value
        If StartInvNum <> 0 Then
            txtStInvNum = StartInvNum
        Else
            txtStInvNum = dsBILLING.fields("MaxInvNum").Value
        End If
        txtEdInvNum = dsBILLING.fields("MaxInvNum").Value
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErrText, vbCritical, "ORACLE ERROR"
            Unload Me
        End If
        
        MsgBox "No Previous Invoice Found.", vbInformation, "REPRINT"
    End If
    
End Sub
