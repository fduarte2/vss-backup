VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "mscomct2.ocx"
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCTL.OCX"
Begin VB.Form frmHistory 
   AutoRedraw      =   -1  'True
   Caption         =   "LEASE HISTORY"
   ClientHeight    =   8400
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   15240
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
   ScaleHeight     =   8400
   ScaleWidth      =   15240
   StartUpPosition =   3  'Windows Default
   Begin MSComctlLib.StatusBar StatusBar1 
      Align           =   2  'Align Bottom
      Height          =   330
      Left            =   0
      TabIndex        =   13
      Top             =   8070
      Width           =   15240
      _ExtentX        =   26882
      _ExtentY        =   582
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
         EndProperty
      EndProperty
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBGrid grdLease 
      Height          =   5415
      Left            =   113
      TabIndex        =   12
      Top             =   2400
      Width           =   15015
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   13
      ForeColorEven   =   8388608
      RowHeight       =   503
      Columns.Count   =   13
      Columns(0).Width=   1931
      Columns(0).Caption=   "INV DATE"
      Columns(0).Name =   "INV DATE"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   2593
      Columns(1).Caption=   "INV NUM"
      Columns(1).Name =   "INV NUM"
      Columns(1).Alignment=   1
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   2011
      Columns(2).Caption=   "CUSTOMER"
      Columns(2).Name =   "CUSTOMER"
      Columns(2).Alignment=   1
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   2011
      Columns(3).Caption=   "START DT"
      Columns(3).Name =   "START DT"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2011
      Columns(4).Caption=   "END DATE"
      Columns(4).Name =   "END DATE"
      Columns(4).Alignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   2778
      Columns(5).Caption=   "LEASE TYPE"
      Columns(5).Name =   "LEASE TYPE"
      Columns(5).Alignment=   1
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1455
      Columns(6).Caption=   "SERV"
      Columns(6).Name =   "SERV"
      Columns(6).Alignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1376
      Columns(7).Caption=   "COMM"
      Columns(7).Name =   "COMM"
      Columns(7).Alignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1508
      Columns(8).Caption=   "RATE"
      Columns(8).Name =   "RATE"
      Columns(8).Alignment=   1
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1958
      Columns(9).Caption=   "QTY"
      Columns(9).Name =   "QTY"
      Columns(9).Alignment=   1
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   1773
      Columns(10).Caption=   "UNT"
      Columns(10).Name=   "UNT"
      Columns(10).CaptionAlignment=   2
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   1984
      Columns(11).Caption=   "AMT"
      Columns(11).Name=   "AMT"
      Columns(11).Alignment=   1
      Columns(11).CaptionAlignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   4948
      Columns(12).Caption=   "DESC"
      Columns(12).Name=   "DESC"
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      _ExtentX        =   26485
      _ExtentY        =   9551
      _StockProps     =   79
   End
   Begin VB.Frame Frame1 
      Height          =   1935
      Left            =   1793
      TabIndex        =   0
      Top             =   120
      Width           =   11655
      Begin VB.CommandButton cmdPrint 
         Caption         =   "PRINT"
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
         Left            =   9360
         TabIndex        =   11
         Top             =   1200
         Width           =   1455
      End
      Begin VB.CommandButton cmdRet 
         Caption         =   "RETERIVE"
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
         Left            =   7440
         TabIndex        =   10
         Top             =   1200
         Width           =   1455
      End
      Begin MSComCtl2.DTPicker dtpEndDt 
         Height          =   330
         Left            =   5160
         TabIndex        =   9
         Top             =   1200
         Width           =   1335
         _ExtentX        =   2355
         _ExtentY        =   582
         _Version        =   393216
         CustomFormat    =   "MM/dd/yyyy"
         Format          =   22937603
         CurrentDate     =   36762
      End
      Begin MSComCtl2.DTPicker dtpStartDt 
         Height          =   330
         Left            =   2160
         TabIndex        =   7
         Top             =   1200
         Width           =   1215
         _ExtentX        =   2143
         _ExtentY        =   582
         _Version        =   393216
         CustomFormat    =   "MM/dd/yyyy"
         Format          =   22937603
         CurrentDate     =   36762
      End
      Begin VB.CommandButton cmdList 
         Caption         =   "..."
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   3480
         TabIndex        =   6
         Top             =   502
         Width           =   615
      End
      Begin VB.TextBox txtCustName 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   4320
         TabIndex        =   5
         Top             =   502
         Width           =   3615
      End
      Begin VB.TextBox txtCustId 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   2160
         TabIndex        =   4
         Top             =   502
         Width           =   1215
      End
      Begin VB.CheckBox chkAllCust 
         Caption         =   "ALL CUSTOMER"
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
         Left            =   8520
         TabIndex        =   3
         Top             =   480
         Width           =   2175
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "END DATE  :"
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
         Left            =   3840
         TabIndex        =   8
         Top             =   1260
         Width           =   1065
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "START DATE  :"
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
         TabIndex        =   2
         Top             =   1260
         Width           =   1320
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "CUSTOMER  :"
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
         Left            =   840
         TabIndex        =   1
         Top             =   555
         Width           =   1200
      End
   End
End
Attribute VB_Name = "frmHistory"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iRec As Integer
Dim SqlStmt As String
Private Sub chkAllCust_Click()

    If chkAllCust.Value = vbChecked Then
        txtCustId = ""
        txtCustName = ""
    End If
    
End Sub
Private Sub cmdPrint_Click()
    
    Dim dAmt As Double

    If grdLease.Rows = 0 Then Exit Sub
    
    Printer.Orientation = 2
    Printer.Print ""
    Printer.Print Tab(5); "Printed on:"; Tab(20); Date
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 11
    Printer.Print Tab(65); "LEASE HISTORY"
    Printer.FontSize = 7
    Printer.Print ""
    If chkAllCust.Value = vbChecked Then
        Printer.Print Tab(5); "CUSTOMER   :"; Tab(25); "ALL"
    Else
        Printer.Print Tab(5); "CUSTOMER  :"; Tab(25); Trim(txtCustName)
    End If
    Printer.Print
    Printer.Print Tab(5); "START DATE"; Tab(25); Format(dtpStartDt.Value, "mm/dd/yyyy"); Tab(40); "END DATE"; Tab(60); Format(dtpEndDt.Value, "mm/dd/yyyy")
    Printer.Print ""
    Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
     Printer.Print ; Tab(5); "INV DATE"; Tab(25); "INVOICE#"; Tab(40); "CUST"; Tab(50); "START DT"; _
                  Tab(65); "END DT"; Tab(80); "LEASE"; Tab(100); "SERVICE"; Tab(115); "COMMODITY"; Tab(130); "RATE"; Tab(140); "QTY"; _
                  Tab(155); "UNT"; Tab(165); "AMT"; Tab(180); "DESC"
    Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
    
    With grdLease
        .MoveFirst
    
        For iRec = 0 To .Rows - 1
            
            Printer.Print Tab(5); Trim(.Columns(0).Value); Tab(25); .Columns(1).Value; Tab(40); .Columns(2).Value; _
                          Tab(50); .Columns(3).Value; Tab(65); Trim(.Columns(4).Value); _
                          Tab(80); Trim(.Columns(5).Value); Tab(100); Trim(.Columns(6).Value); _
                          Tab(115); Trim(.Columns(7).Value); Tab(130); Trim(.Columns(8).Value); _
                          Tab(140); Trim(.Columns(9).Value); Tab(155); Trim(.Columns(10).Value); _
                          Tab(165); Trim(.Columns(11).Value); Tab(180); Trim(.Columns(12).Value)
                          
            dAmt = dAmt + Val(.Columns(11).Value)
            .MoveNext
            
        Next iRec
        
        Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
        Printer.Print Tab(5); "TOTAL  :"; Tab(20); grdLease.Rows; Tab(165); dAmt
        Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------" _
                          ; "----------------------------------------------------------------------------------------------------------------------"
        .MoveFirst
    End With
    
    Printer.EndDoc
    
End Sub
Private Sub cmdRet_Click()
    
    Dim sInvDT As String
    Dim sInvNum As String
    Dim sCustId As String
    Dim sStDt As String
    Dim sEdDt As String
    Dim dLeasePeriod As Double
    Dim sSerCode As String
    Dim sCommCode As String
    Dim sRate As String
    Dim sQty As String
    Dim sUnt As String
    Dim dAmt As Double
    Dim sDesc As String
    Dim sTotalAmt As Double
    
    If chkAllCust.Value = vbUnchecked And txtCustId = "" Then Exit Sub
    
    grdLease.RemoveAll
    
    StatusBar1.SimpleText = "PROCESSING ..."
    
    If chkAllCust.Value = vbUnchecked Then
        SqlStmt = " SELECT * " _
                & " FROM BILLING " _
                & " WHERE BILLING_TYPE='LEASE' " _
                & " AND INVOICE_DATE >= TO_DATE('" & Format(dtpStartDt, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                & " AND INVOICE_DATE <= TO_DATE('" & Format(dtpEndDt, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                & " AND CUSTOMER_ID='" & Trim(txtCustId) & "' ORDER BY INVOICE_DATE,INVOICE_NUM"
    Else
        SqlStmt = " SELECT * " _
                & " FROM BILLING " _
                & " WHERE BILLING_TYPE='LEASE' " _
                & " AND INVOICE_DATE >= TO_DATE('" & Format(dtpStartDt, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                & " AND INVOICE_DATE <= TO_DATE('" & Format(dtpEndDt, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                & " ORDER BY INVOICE_DATE,INVOICE_NUM"
    End If
    
    DoEvents
    
    Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    'StatusBar1.SimpleText = dsBILLING.recordcount & " RECORD(S) FOUND"
    
    If OraDatabase.LastServerErr = 0 And dsBILLING.recordcount > 0 Then
        
        For iRec = 1 To dsBILLING.recordcount
            
            DoEvents
            
            sInvDT = dsBILLING.fields("INVOICE_DATE")
            sInvNum = dsBILLING.fields("INVOICE_NUM")
            sCustId = dsBILLING.fields("CUSTOMER_ID")
            sStDt = dsBILLING.fields("SERVICE_START")
            sEdDt = dsBILLING.fields("SERVICE_STOP")
            sSerCode = dsBILLING.fields("SERVICE_CODE")
            sCommCode = dsBILLING.fields("COMMODITY_CODE")
            sQty = dsBILLING.fields("SERVICE_QTY")
            sUnt = dsBILLING.fields("SERVICE_UNIT")
            dAmt = dsBILLING.fields("SERVICE_AMOUNT")
            sDesc = dsBILLING.fields("SERVICE_DESCRIPTION")
            
            
            SqlStmt = "SELECT * FROM LEASE_RATE WHERE LEASE_ID='" & dsBILLING.fields("PAGE_NUM").Value & "'"
            Set dsLEASE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsLEASE.recordcount > 0 Then
            
                sRate = dsLEASE.fields("RATE").Value
                dLeasePeriod = dsLEASE.fields("PERIOD").Value
            End If
            
            grdLease.AddItem Format(sInvDT, "mm/dd/yyyy") + Chr(9) + sInvNum + Chr(9) + sCustId + Chr(9) + _
                             Format(sStDt, "mm/dd/yyyy") + Chr(9) + Format(sEdDt, "mm/dd/yyyy") + Chr(9) + _
                             CStr(dLeasePeriod) + Chr(9) + sSerCode + Chr(9) + sCommCode + Chr(9) + sRate + Chr(9) + _
                             sQty + Chr(9) + sUnt + Chr(9) + CStr(dAmt) + Chr(9) + sDesc
            
            grdLease.Refresh
            sTotalAmt = sTotalAmt + dAmt
            
            dsBILLING.MoveNext
        Next iRec
    End If
    
    StatusBar1.SimpleText = grdLease.Rows & " Records and Invoiced Amount : " & sTotalAmt

End Sub
Private Sub Form_Load()

    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    chkAllCust.Value = vbChecked
    dtpStartDt.Value = Format(Now, "MM/DD/YYYY")
    dtpEndDt.Value = Format(Now, "MM/DD/YYYY")
    
End Sub
