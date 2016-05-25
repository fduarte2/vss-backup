VERSION 5.00
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "CRYSTL32.OCX"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmEditInv 
   AutoRedraw      =   -1  'True
   Caption         =   "EDIT RF INVOICES"
   ClientHeight    =   10410
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   14475
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
   ScaleHeight     =   10410
   ScaleWidth      =   14475
   StartUpPosition =   3  'Windows Default
   Begin Crystal.CrystalReport crw1 
      Left            =   240
      Top             =   7080
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin VB.Frame Frame1 
      Height          =   2295
      Left            =   248
      TabIndex        =   13
      Top             =   480
      Width           =   9480
      Begin VB.TextBox txtComment 
         Appearance      =   0  'Flat
         Height          =   975
         Left            =   2280
         MultiLine       =   -1  'True
         ScrollBars      =   2  'Vertical
         TabIndex        =   18
         Top             =   1200
         Width           =   6495
      End
      Begin VB.TextBox txtVessel 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   3600
         TabIndex        =   17
         TabStop         =   0   'False
         Top             =   720
         Width           =   4935
      End
      Begin VB.TextBox txtLRNum 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   2280
         TabIndex        =   16
         Top             =   720
         Width           =   1215
      End
      Begin VB.TextBox txtCustId 
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
         Left            =   2280
         TabIndex        =   15
         Top             =   240
         Width           =   1215
      End
      Begin VB.TextBox txtCustName 
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
         Left            =   3600
         TabIndex        =   14
         TabStop         =   0   'False
         Top             =   240
         Width           =   4935
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Coments  :"
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
         TabIndex        =   21
         Top             =   1200
         Width           =   870
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Customer ID  :"
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
         Left            =   750
         TabIndex        =   20
         Top             =   300
         Width           =   1200
      End
      Begin VB.Label Label4 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Vessel/Order No. :"
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
         TabIndex        =   19
         Top             =   780
         Width           =   1545
      End
   End
   Begin VB.TextBox txtInitials 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   11760
      TabIndex        =   12
      Top             =   127
      Width           =   1215
   End
   Begin VB.TextBox txtNewInvNum 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   7320
      MaxLength       =   10
      TabIndex        =   9
      Top             =   127
      Width           =   2895
   End
   Begin VB.Frame Frame2 
      Height          =   2295
      Left            =   10080
      TabIndex        =   4
      Top             =   480
      Width           =   2895
      Begin VB.CommandButton cmdClear 
         Caption         =   "CLEAR"
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
         Left            =   1080
         TabIndex        =   8
         Top             =   795
         Width           =   975
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
         Left            =   1080
         TabIndex        =   7
         Top             =   1800
         Width           =   975
      End
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
         Left            =   1080
         TabIndex        =   6
         Top             =   1305
         Width           =   975
      End
      Begin VB.CommandButton cmdSave 
         Caption         =   "SAVE"
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
         Left            =   1080
         TabIndex        =   5
         Top             =   300
         Width           =   975
      End
   End
   Begin VB.CommandButton cmdRet 
      Caption         =   "Retrieve"
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
      Left            =   3615
      TabIndex        =   2
      Top             =   105
      Width           =   1095
   End
   Begin VB.TextBox txtInvNum 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   1455
      MaxLength       =   10
      TabIndex        =   0
      Top             =   127
      Width           =   1935
   End
   Begin SSDataWidgets_B.SSDBGrid grdDetail 
      Height          =   4335
      Left            =   1080
      TabIndex        =   3
      Top             =   6000
      Width           =   12180
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   8
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      RowHeight       =   503
      Columns.Count   =   8
      Columns(0).Width=   3889
      Columns(0).Caption=   "BARCODE"
      Columns(0).Name =   "BARCODE"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1984
      Columns(1).Caption=   "START"
      Columns(1).Name =   "START"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1826
      Columns(2).Caption=   "END"
      Columns(2).Name =   "END"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   2196
      Columns(3).Caption=   "CASES"
      Columns(3).Name =   "CASES"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2223
      Columns(4).Caption=   "RATE"
      Columns(4).Name =   "RATE"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   3200
      Columns(5).Caption=   "AMOUNT"
      Columns(5).Name =   "AMOUNT"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   3200
      Columns(6).Caption=   "ORDER #"
      Columns(6).Name =   "ORDER #"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1852
      Columns(7).Caption=   "BILL NO."
      Columns(7).Name =   "BILL"
      Columns(7).Alignment=   1
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      _ExtentX        =   21484
      _ExtentY        =   7646
      _StockProps     =   79
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBGrid grdSummary 
      Height          =   2775
      Left            =   255
      TabIndex        =   22
      Top             =   3000
      Width           =   13800
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   5
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      RowHeight       =   503
      Columns.Count   =   5
      Columns(0).Width=   1588
      Columns(0).Caption=   "DATE"
      Columns(0).Name =   "DATE"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   18018
      Columns(1).Caption=   "DESCRIPTION"
      Columns(1).Name =   "DESCRIPTION"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   2037
      Columns(2).Caption=   "AMOUNT"
      Columns(2).Name =   "AMOUNT"
      Columns(2).Alignment=   1
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1561
      Columns(3).Caption=   "BILL NO"
      Columns(3).Name =   "BILLNO"
      Columns(3).Alignment=   1
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   3200
      Columns(4).Visible=   0   'False
      Columns(4).Caption=   "ROWNUM"
      Columns(4).Name =   "ROWNUM"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      _ExtentX        =   24342
      _ExtentY        =   4895
      _StockProps     =   79
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.Label lblNull 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      ForeColor       =   &H80000008&
      Height          =   255
      Left            =   240
      TabIndex        =   23
      Top             =   6480
      Visible         =   0   'False
      Width           =   615
   End
   Begin VB.Label Label6 
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
      Left            =   10680
      TabIndex        =   11
      Top             =   180
      Width           =   960
   End
   Begin VB.Label Label5 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "NEW INVOICE #  :"
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
      Left            =   5520
      TabIndex        =   10
      Top             =   180
      Width           =   1560
   End
   Begin VB.Label Label1 
      Caption         =   "INVOICE #  :"
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
      Left            =   255
      TabIndex        =   1
      Top             =   165
      Width           =   1215
   End
End
Attribute VB_Name = "frmEditInv"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim iRec As Integer
Dim sqlstmt As String
Dim iDuration As Integer
Dim sPerUnit As String
Dim sDuration As String
Dim iRow As Long
Dim iCount As Integer
Dim sInvNum As String
Dim sInvDt  As String
Dim grdRow As Integer

Sub Det_Billing(iCustId As Integer, sArrNum As String, iInvoice_Num As String, dtDate As Date)
    Dim sFromStr As String
    Dim dInvoiceTotal As Double
    Dim sSerQty As String
    Dim iRec As Integer
    Dim dsBilling1 As Object
    Dim sqlstmt As String
    '-------Pawan
    Dim sqlstmt1 As String
    Dim dsCargo_dis As Object
    Dim cargo_dis As String
    Dim cargo_dis1 As String
    Dim location As String
    Dim subTotal As Double
    Dim first As Integer
    '-------Pawan
    Dim dsCustomer_DTLS As Object
    Dim iLine As Integer
    Dim iSpace As Integer
    Dim sCityStateZip As String
    Dim sServQty As String
    
    sFromStr = "PORT OF WILMINGTON"
    
    sqlstmt = " SELECT BD.*, CT.variety, CT.warehouse_location FROM BILLING_DETAIL_CORRECTION BD, cargo_tracking CT " _
            & " WHERE BD.SUM_BILL_NUM IN(SELECT BILLING_NUM " _
            & " FROM BILLING_CORRECTION WHERE CUSTOMER_ID='" & iCustId & "' " _
            & " AND INVOICE_NUM = '" & iInvoice_Num & "' AND ARRIVAL_NUM='" & sArrNum & "' " _
            & " AND SERVICE_STATUS='INVOICED') AND BD.SERVICE_START=" _
            & " TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY')" _
            & " AND BD.INVOICE_NUM = '" & iInvoice_Num & "'" _
            & " AND CT.receiver_id = '" & iCustId & "' AND CT.ARRIVAL_NUM = '" & sArrNum & "'" _
            & " AND SUBSTR(CT.pallet_id, 0, 25) = BD.pallet_id " _
            & " ORDER BY CT.variety, BD.SERVICE_START, BD.SERVICE_STOP"



    'sqlstmt = " SELECT * FROM BILLING_DETAIL_CORRECTION WHERE SUM_BILL_NUM IN(SELECT BILLING_NUM " _
            & " FROM BILLING_CORRECTION WHERE CUSTOMER_ID='" & iCustId & "' " _
            & " AND INVOICE_NUM = '" & iInvoice_Num & "' AND ARRIVAL_NUM='" & sArrNum & "' " _
            & " AND SERVICE_STATUS='INVOICED') AND SERVICE_START=" _
            & " TO_DATE('" & Format(dtDate, "mm/dd/yyyy") & "','MM/DD/YYYY')" _
            & " AND INVOICE_NUM = '" & iInvoice_Num & "'" _
            & " ORDER BY SERVICE_START,SERVICE_STOP"
            
    
    
    Set dsCustomer_DTLS = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
    If dsCustomer_DTLS.recordcount > 0 Then
        dInvoiceTotal = 0
        
        '.....Pawan
        subTotal = 0
        first = 0
        '.....Pawan
        
        If iCount = 54 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNull, 0)
        
        If dsCustomer_DTLS.FIELDS("SERVICE_UNIT").Value = "PLT" And dsCustomer_DTLS.FIELDS("CUSTomer_ID").Value = 1986 Then
            sServQty = "CASES" ' "PALETTES"
        ElseIf dsCustomer_DTLS.FIELDS("SERVICE_UNIT").Value = "CWT" And dsCustomer_DTLS.FIELDS("CUSTomer_ID").Value = 1986 Then
            sServQty = "CASES"
        Else
            sServQty = "CASES"
        End If
       
        Dim sRatestr As String
        If dsCustomer_DTLS.FIELDS("CUSTOMER_ID").Value = 1986 Then
            sRatestr = "RATE/CWT"
        Else
            sRatestr = "RATE/PLT"
        End If
        
        'If the vessel name is Trucked in cargo then only show the order num from the cargo_activity
        If iCount = 54 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        If UCase(txtVessel) = "TRUCKED IN CARGO" Then
            Call PreInvoice_AddNew(iRow, Space(20) & "BAR CODE" & Space(25) & "START" & Space(8) & "END" _
            & Space(8) & sServQty & Space(8) & sRatestr & Space(10) & "AMOUNT" & Space(10) & "ORDER NO.", 0)
            '& "LOCATION" & Space(8)
         Else
            Call PreInvoice_AddNew(iRow, Space(20) & "BAR CODE" & Space(25) & "START" & Space(8) & "END" _
            & Space(8) & sServQty & Space(8) & sRatestr & Space(10) & "AMOUNT", 0)
            '& "LOCATION" & Space(8)
         End If
        
        If iCount = 54 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNull, 0)

               
        For iRec = 1 To dsCustomer_DTLS.recordcount
            DoEvents
            '-------Pawan
            cargo_dis1 = cargo_dis
            sqlstmt1 = "select variety, warehouse_location from cargo_tracking where " _
                    & " receiver_id='" & iCustId & "' " _
                    & " AND pallet_id = '" & Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value) & "'" _
                    & "AND ARRIVAL_NUM='" & sArrNum & "' "
            Set dsCargo_dis = OraDatabase.dbcreatedynaset(sqlstmt1, 0&)
            If OraDatabase.lastservererr = 0 And dsCargo_dis.recordcount > 0 Then
                If Not IsNull(dsCargo_dis.FIELDS("variety").Value) Then
                    cargo_dis = Trim(dsCargo_dis.FIELDS("variety").Value)
                Else
                    cargo_dis = "No Cargo Discription"
                End If
                If Not IsNull(dsCargo_dis.FIELDS("warehouse_location").Value) Then
                    location = Trim(dsCargo_dis.FIELDS("warehouse_location").Value)
                Else
                    location = "No Location"
                End If
            Else
                MsgBox "No Cargo Description & Location Found For the Invoice", vbInformation + vbCritical, "Check"
            End If
            If cargo_dis <> cargo_dis1 Then
                If first <> 0 Then
                    If iCount = 54 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(85) & "SUB TOTAL :" & Space(45) & Format(Round(subTotal, 3), "##,###,###,##0.00,"), 0)
                    subTotal = 0
                    
                    If iCount = 54 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, lblNull, 0)
                End If
                first = 11
                
                
                If iCount = 54 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, Space(20) & cargo_dis, 0)
           End If
            
            
            '-------Pawan
            If iCount = 54 Then Call NEW_PAGE
            iCount = iCount + 1
            iRow = iRow + 1
            
            'Picks up the order num from cargo activity if the vessel is "TRUCKED IN CARGO"
            If UCase(txtVessel) = "TRUCKED IN CARGO" Then
                Dim sql As String
                Dim dsOrderNum As Object
                Dim STmpOrder As String
            
                sql = "Select distinct Order_num from cargo_activity where PALLET_ID=" _
                    & "'" & Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value) & "' AND " _
                    & " service_code='8' and customer_id='" & iCustId & "'"
            
                    Set dsOrderNum = OraDatabase.dbcreatedynaset(sql, 0&)
                    If OraDatabase.lastservererr = 0 And dsOrderNum.recordcount <> 0 Then
                        If Not IsNull(dsOrderNum.FIELDS("ORDER_NUM").Value) Then
                            STmpOrder = dsOrderNum.FIELDS("ORDER_NUM").Value
                        Else
                            STmpOrder = ""
                        End If
                    Else
                        STmpOrder = ""
                    End If
            Else
                STmpOrder = ""
            End If
            
            
            Dim iSpc0 As Integer
            Dim iSpc1 As Integer
            Dim iSpc2 As Integer
            Dim iSpc3 As Integer
            Dim iLen As Integer
            
            'Adjustments of spacing for printing on the printer
            If Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) <= 8 Then
                iSpc0 = 21
            ElseIf Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) = 9 Then
                iSpc0 = 19
            ElseIf Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) = 10 Then   'default
                iSpc0 = 17
            ElseIf Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) = 11 Then
                iSpc0 = 15
            ElseIf Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) = 12 Then
                iSpc0 = 13
            ElseIf Len(Trim(dsCustomer_DTLS.FIELDS("PALLET_ID").Value)) >= 13 Then
                iSpc0 = 11
            End If
            
            If Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) <= 1 Then
                iSpc1 = 25
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) = 2 Then  'DEFAULT
                iSpc1 = 23
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) = 3 Then
                iSpc1 = 21
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) = 4 Then
                iSpc1 = 19
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) = 5 Then
                iSpc1 = 17
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value) >= 6 Then
                iSpc1 = 15
            End If
            
            If Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) <= 1 Then
                iSpc2 = 32
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 2 Then
                iSpc2 = 30
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 3 Then
                iSpc2 = 28
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 4 Then
                iSpc2 = 26
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 5 Then    'DEFAULT
                iSpc2 = 24
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 6 Then
                iSpc2 = 22
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 7 Then
                iSpc2 = 20
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) = 8 Then
                iSpc2 = 18
            ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value) >= 9 Then
                iSpc2 = 16
            End If
            
            If txtVessel = "TRUCKED IN CARGO" Then
                If Len(dsCustomer_DTLS.FIELDS("SERVICE_Amount").Value) <= 1 Then
                    iSpc3 = 24
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 2 Then
                    iSpc3 = 22
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 3 Then
                    iSpc3 = 20
                 ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 4 Then   'DEFAULT
                    iSpc3 = 18
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 5 Then
                    iSpc3 = 16
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 6 Then
                    iSpc3 = 14
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 7 Then
                    iSpc3 = 12
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) = 8 Then
                    iSpc3 = 10
                ElseIf Len(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value) >= 9 Then
                    iSpc3 = 8
                End If
            End If
            
            Call PreInvoice_AddNew(iRow, Space(20) & dsCustomer_DTLS.FIELDS("PALLET_ID").Value _
                & Space(iSpc0) & dsCustomer_DTLS.FIELDS("SERVICE_START").Value _
                & Space(8) & dsCustomer_DTLS.FIELDS("SERVICE_STOP").Value _
                & Space(8) & dsCustomer_DTLS.FIELDS("SERVICE_QTY").Value _
                & Space(iSpc1) & Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_RATE").Value, 2), "##0.00,") _
                & Space(iSpc2) & Format(Round(dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value, 2), "##0.00,") _
                & Space(iSpc3) & STmpOrder, 0)
            ' & Space(8) & location _

            dInvoiceTotal = dInvoiceTotal + dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value
            '---PAWAN
            subTotal = subTotal + dsCustomer_DTLS.FIELDS("SERVICE_AMOUNT").Value
            '----PAWAN
            dsCustomer_DTLS.MoveNext
        Next iRec
        
        '---Pawan
        If iCount = 54 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(85) & "SUB TOTAL :" & Space(45) & Format(Round(subTotal, 3), "##,###,###,##0.00,"), 0)
        subTotal = 0
        '---Pawan
        
        If iCount = 54 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, lblNull, 0)
        
        If iCount = 54 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(90) & "----------------------------------------------------------------------------", 0)

        If iCount = 54 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(105) & "TOTAL :" & Space(30) & Format(Round(dInvoiceTotal, 3), "##,###,###,##0.00,"), 0)

        
    End If
End Sub
'Function that inserts strings to the table PREINVOICE for printing purposes
Sub PreInvoice_AddNew(RowNum As Long, Row_Text As String, eof As Integer)
    With dsPreInvoice
        .AddNew
        .FIELDS("Row_Num").Value = RowNum
        .FIELDS("Text").Value = Row_Text
        .FIELDS("eof").Value = eof
        .Update
    End With
End Sub

Private Sub cmdClear_Click()
    txtInvNum = ""
    txtCustId = ""
    txtCustName = ""
    txtLRNum = ""
    txtVessel = ""
    txtComment = ""
    txtNewInvNum = ""
    grdSummary.RemoveAll
    grdDetail.RemoveAll
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub

Private Sub cmdPrint_Click()
    Dim sInv As String
    Dim sDate As String
    Dim sDtChar As String
    Dim sTempDate As String
    Dim sFileName As String
    Dim sDuration As String
    Dim iBillNo As Integer
    Dim sPerUnit As String
    Dim iCustId As Integer
    Dim dTotalAmount As Double
    Dim bStart As Boolean
    Dim dGrandTotal As Double
    Dim iCommodityCode As Integer
    Dim sArrNum As String
    Dim iPageNbr As Integer
    Dim bNewInvoiceNum As Boolean
    Dim sVessel As String
    Dim i As Integer
    Dim iLine As Integer
    Dim sCustomerAddr1 As String
    Dim sCustomerAddr2  As String
    Dim sLen As String
    Dim iLen As Long
    Dim iLenSpace As Double
    Dim iBillingRec As Integer
    Dim iInv_Num As String
    Dim dtStartDate As Date
    Dim sEmailDirPath As String
    
    dGrandTotal = 0
    iRow = 0
    bNewInvoiceNum = False
    bStart = True
    iPageNbr = 1
    iCustId = 0
    
    If Trim(txtNewInvNum) = "" Then
        sInv = Trim(txtInvNum)
    Else
        sInv = Trim(txtNewInvNum)
    End If

    sqlstmt = "SELECT * FROM BILLING_CORRECTION WHERE INVOICE_NUM='" & sInv & "'"
    Set dsBILLING_CORRECTION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If dsBILLING_CORRECTION.recordcount = 0 Then
        MsgBox "First save the edited Invoice and then proceed to print the invoice", vbInformation, "PRINT"
        txtNewInvNum.SetFocus
        Exit Sub
    End If
    
    sqlstmt = "SELECT * FROM BILLING_CORRECTION WHERE INVOICE_NUM ='" & sInv & "' ORDER BY BILLING_NUM,ROW_NUM"
    Set dsBILLING = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
    If OraDatabase.lastservererr = 0 And dsBILLING.recordcount > 0 Then
        sqlstmt = ""
        sqlstmt = "Select * from PreInvoice "
        Set dsPreInvoice = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        If OraDatabase.lastservererr = 0 And dsPreInvoice.recordcount <> 0 Then
            OraDatabase.DbExecuteSQL ("DELETE FROM PREINVOICE")
        End If
        
        grdSummary.MoveFirst
        
        For iRec = 0 To grdSummary.Rows - 1
            
            If iBillNo <> grdSummary.Columns(3).Value Then
                
                If bStart = False Then Call Det_Billing(iCustId, sArrNum, iInv_Num, dtStartDate)
                                                   
                bStart = False
                iBillNo = Trim(grdSummary.Columns(3).Value)
                iInv_Num = sInv
                dtStartDate = CDate(Format(grdSummary.Columns(0).Value, "mm/dd/YYYY"))
                sInvNum = CStr(iInv_Num)
                sVessel = Trim(txtVessel)
                iCustId = Trim(txtCustId)
                sArrNum = Trim(txtLRNum)
                
                sqlstmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & Trim(txtCustId) & "'"
                Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                    
                sqlstmt = " SELECT * FROM COUNTRY WHERE COUNTRY_CODE IN (SELECT COUNTRY_CODE FROM CUSTOMER_PROFILE" _
                        & " WHERE CUSTOMER_ID='" & Trim(txtCustId) & "')"
                    
                Set dsCOUNTRY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                
            
                iCount = 0
                iCount = iCount + 1
                iRow = iRow + 1
                If bStart = True Then
                    bStart = False
                    Call PreInvoice_AddNew(iRow, Space(227) & iInv_Num, 0)
                Else
                    Call PreInvoice_AddNew(iRow, Space(227) & iInv_Num, 1)
                End If
            
                If iCount = 54 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, lblNull, 0)

            
                'Printing Invoice date or cutoffdate below the invoice no.
                If iCount = 54 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(iRow, Space(227) & dsBILLING.FIELDS("INVOICE_date").Value, 0)
                sInvDt = CStr(dsBILLING.FIELDS("INVOICE_date").Value)
                       
                For iLine = 1 To 6
                    If iCount = 54 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, lblNull, 0)
                Next iLine
                
           
                If Not IsNull(dsBILLING.FIELDS("CARE_OF")) And ((dsBILLING.FIELDS("CARE_OF").Value = "1") Or _
                       (dsBILLING.FIELDS("CARE_OF").Value = "Y")) Then
                    If iCount = 54 Then Call NEW_PAGE
                        iCount = iCount + 1
                        iRow = iRow + 1
                        Call PreInvoice_AddNew(iRow, Space(34) & sArrNum & " - " & Trim(txtVessel), 0)
    
                        If iCount = 54 Then Call NEW_PAGE
                        iCount = iCount + 1
                        iRow = iRow + 1
                        Call PreInvoice_AddNew(iRow, Space(34) & "C/O " & Trim(txtCustName), 0)
                    Else
                        If iCount = 54 Then Call NEW_PAGE
                        iCount = iCount + 1
                        iRow = iRow + 1
                        Call PreInvoice_AddNew(iRow, Space(34) & sArrNum & " - " & Trim(txtCustName), 0)
                    End If
               
                    If iCount = 54 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(34) & sArrNum & " - " & Trim(txtCustName), 0)
               
                        
                    If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS1").Value) Then
                        If iCount = 54 Then Call NEW_PAGE
                        iCount = iCount + 1
                        iRow = iRow + 1
                        Call PreInvoice_AddNew(iRow, Space(34) & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS1").Value, 0)
                    End If
                    
                    If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS2").Value) Then
                        If iCount = 54 Then Call NEW_PAGE
                        iCount = iCount + 1
                        iRow = iRow + 1
                        Call PreInvoice_AddNew(iRow, Space(34) & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ADDRESS2").Value, 0)
                    End If
                
                
                    Dim sCityStateZip As String
                    If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_City").Value) Then
                        sCityStateZip = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_City").Value
                    End If
                    If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_State").Value) Then
                        sCityStateZip = sCityStateZip & ", " & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_State").Value
                    End If
                    If Not IsNull(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_Zip").Value) Then
                        sCityStateZip = sCityStateZip & " - " & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_Zip").Value
                    End If
                    If iCount = 54 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, Space(34) & sCityStateZip, 0)
                    
                    If dsCOUNTRY.FIELDS("COUNTRY_CODE").Value <> "US" Then
                        If Not IsNull(dsCOUNTRY.FIELDS("COUNTRY_NAME")) Then
                        If iCount = 54 Then Call NEW_PAGE
                        iCount = iCount + 1
                        iRow = iRow + 1
                        Call PreInvoice_AddNew(iRow, Space(34) & dsCOUNTRY.FIELDS("COUNTRY_NAME").Value, 0)
                    End If
                End If

                For iLine = 1 To 8
                    If iCount = 54 Then Call NEW_PAGE
                    iCount = iCount + 1
                    iRow = iRow + 1
                    Call PreInvoice_AddNew(iRow, lblNull, 0)
                Next iLine
                   
                If iCount = 54 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
            End If
            
            If Trim(grdSummary.Columns(0).Value) <> "" Then ' dsBILLING.FIELDS("ROW_NUM").Value = 1 Then
            
                sLen = dsBILLING.FIELDS("SERVICE_DESCRIPTION").Value
        
                If Len(sLen) > 70 Then
                    iLenSpace = Len(sLen) - 70
                ElseIf Len(sLen) = 70 Then
                    iLenSpace = 0
                ElseIf Len(sLen) < 70 Then
                    iLenSpace = Len(sLen) - 70
                End If
            
                Call PreInvoice_AddNew(iRow, dsBILLING.FIELDS("SERVICE_START").Value & _
                Space(10) & dsBILLING.FIELDS("SERVICE_DESCRIPTION").Value & Space(34 - iLenSpace) & Format(dsBILLING.FIELDS("SERVICE_AMOUNT").Value, _
                "##,###,###,##0.00"), 0)
                dGrandTotal = dGrandTotal + dsBILLING.FIELDS("SERVICE_AMOUNT").Value
            Else
            
                If iCount = 54 Then Call NEW_PAGE
                iCount = iCount + 1
                iRow = iRow + 1
                Call PreInvoice_AddNew(CLng(iRow), Space(50) & dsBILLING.FIELDS("SERVICE_DESCRIPTION").Value, 0)
                
            End If
            dsBILLING.MoveNext
            grdSummary.MoveNext
        Next iRec
        
        'calling detail billing for the very last record in the recordset
        Call Det_Billing(iCustId, sArrNum, iInv_Num, dtStartDate)
        
        For i = 1 To 2
            If iCount = 54 Then Call NEW_PAGE
            iCount = iCount + 1
            iRow = iRow + 1
            Call PreInvoice_AddNew(iRow, lblNull, 0)
        Next i
        
        If iCount = 54 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
        & "--------------------------------------------------------------------------------------------------------------------------------------------", 0)
        
        If iCount = 54 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(140) & "INVOICE TOTAL : " & Space(43) _
        & Format(Round(dGrandTotal, 2), "##,###,###,##0.00"), 0)
        
        If iCount = 54 Then Call NEW_PAGE
        iCount = iCount + 1
        iRow = iRow + 1
        Call PreInvoice_AddNew(iRow, Space(5) & "-------------------------------------------" _
        & "--------------------------------------------------------------------------------------------------------------------------------------------", 0)
        
        'dGrandTotal = dGrandTotal + dTotalAmount
        
        crw1.ReportFileName = App.Path & "\RFINV.rpt"
        crw1.Connect = "DSN = RF1;UID = sag_owner;PWD = owner"
        crw1.Action = 1
        If crw1.LastErrorNumber <> 0 Then MsgBox crw1.LastErrorString
    
        OraDatabase.DbExecuteSQL ("DELETE FROM Preinvoice")
            
    Else
        MsgBox "INVALID INVOICE NUMBER(S) ! ", vbInformation + vbCritical, "REPRINT INVOICES"
    End If
    
End Sub
Sub NEW_PAGE()
    Dim iLine As Integer
       
    iCount = 0
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, lblNull, 1)
        
    iLine = iLine + 1
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, Space(227) & sInvNum, 0)
       
    iLine = iLine + 1
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, lblNull, 0)
    
    iLine = iLine + 1
    iRow = iRow + 1
    iCount = iCount + 1
    Call PreInvoice_AddNew(iRow, Space(227) & sInvDt, 0)
    
    For iLine = 1 To 33
        iLine = iLine + 1
        iRow = iRow + 1
        iCount = iCount + 1
        Call PreInvoice_AddNew(iRow, lblNull, 0)
    Next iLine
    
End Sub
Private Sub cmdRet_Click()
    Dim sDes1 As String
    Dim sDes2 As String
    
    grdSummary.RemoveAll
    grdDetail.RemoveAll
    grdSummary.RowHeight = 300
    grdDetail.RowHeight = 300
    
    sqlstmt = "SELECT * FROM BILLING_CORRECTION WHERE INVOICE_NUM='" & Trim(txtInvNum) & "' ORDER BY BILLING_NUM,ROW_NUM"
    Set dsBILLING = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If dsBILLING.recordcount > 0 Then
        For iRec = 1 To dsBILLING.recordcount
        
            txtCustId = dsBILLING.FIELDS("customer_id").Value
            txtLRNum = dsBILLING.FIELDS("ARRIVAL_NUM").Value
        
            sqlstmt = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID='" & dsBILLING.FIELDS("CUSTOMER_ID").Value & "'"
            Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
            If dsCUSTOMER_PROFILE.recordcount > 0 Then
                txtCustName = UCase(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value)
            End If
        
            sqlstmt = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM='" & dsBILLING.FIELDS("ARRIVAL_NUM").Value & "'"
            Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
            If dsVESSEL_PROFILE.recordcount > 0 Then
                txtVessel = UCase(dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value)
            Else
                txtVessel = ""
            End If
        
            sqlstmt = "SELECT * FROM INVOICE_HISTORY WHERE NEW_INVOICE_NUM='" & Trim(txtInvNum) & "'"
            Set dsINV_HISTORY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
            txtComment = Trim("" & dsINV_HISTORY.FIELDS("COMMENTS").Value)
            
        '***************************************************************
                                      
                grdSummary.AddItem Trim("" & dsBILLING.FIELDS("SERVICE_START").Value) + Chr(9) + _
                                   dsBILLING.FIELDS("SERVICE_DESCRIPTION").Value + Chr(9) + _
                                   Trim("" & dsBILLING.FIELDS("SERVICE_AMOUNT").Value) + Chr(9) + _
                                   dsBILLING.FIELDS("BILLING_NUM").Value
            dsBILLING.MoveNext
        Next iRec
        
        
    Else
        If Not IsNumeric(txtInvNum) Then
            MsgBox "INVALID INVOICE NUMBER", vbInformation, "INVOICE NUMBER"
            txtInvNum = ""
            txtInvNum.SetFocus
            Exit Sub
        End If
        sqlstmt = "SELECT * FROM RF_BILLING WHERE INVOICE_NUM='" & Trim(txtInvNum) & "'"
        Set dsBILLING = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        If dsBILLING.recordcount > 0 Then
            
            For iRec = 1 To dsBILLING.recordcount
            
                txtCustId = dsBILLING.FIELDS("customer_id").Value
                txtLRNum = dsBILLING.FIELDS("ARRIVAL_NUM").Value
        
                sqlstmt = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID='" & dsBILLING.FIELDS("CUSTOMER_ID").Value & "'"
                Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                If dsCUSTOMER_PROFILE.recordcount > 0 Then
                    txtCustName = UCase(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value)
                End If
        
                sqlstmt = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM='" & dsBILLING.FIELDS("ARRIVAL_NUM").Value & "'"
                Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                If dsVESSEL_PROFILE.recordcount > 0 Then
                    txtVessel = UCase(dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value)
                Else
                    txtVessel = ""
                End If
            
                With dsBILLING
            
                    If dsBILLING.FIELDS("CUSTOMER_ID").Value = 1986 Then
                        If dsBILLING.FIELDS("SERVICE_UNIT").Value = "PLT" Then
                            sqlstmt = " SELECT BILL_DURATION FROM RF_STORAGE_RATE WHERE " _
                                    & " CUSTOMER_ID = '" & dsBILLING.FIELDS("CUSTOMER_ID").Value & "'  AND UNIT = 'PLT'"
                            Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                            iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                            sPerUnit = " CASE(S)"
                            sDuration = " PER PLT FOR " & iDuration & " DAYS "
                        Else
                            sqlstmt = " SELECT BILL_DURATION FROM RF_STORAGE_RATE WHERE " _
                                    & " CUSTOMER_ID = '" & dsBILLING.FIELDS("CUSTOMER_ID").Value & "' AND UNIT = 'CWT'"
                            Set dsDURATION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                            iDuration = dsDURATION.FIELDS("BILL_DURATION").Value
                            sPerUnit = " WEIGHT"
                            sDuration = " PER CWT FOR " & iDuration & " DAYS "
                        End If
                    Else
                        If dsBILLING.FIELDS("COMMODITY_CODE").Value = 5411 Then
                            sPerUnit = " CASE(S)"
                            sDuration = " PER PLT FOR 15 DAYS "
                        Else
                            sPerUnit = " CASE(S)"
                            sDuration = " PER PLT FOR 30 DAYS "
                        End If
                    End If
                
                    sDes1 = "STORAGE BILL FOR " & dsBILLING.FIELDS("SERVICE_QTY2").Value & "  PALLET(S), " _
                           & dsBILLING.FIELDS("SERVICE_QTY").Value & "  " & sPerUnit & " @ $" _
                           & Format(dsBILLING.FIELDS("SERVICE_RATE").Value, "##,###,###,##0.00") _
                           & sDuration
                
                    sDes2 = dsBILLING.FIELDS("SERVICE_START").Value _
                           & " THRU " & DateAdd("d", -1, dsBILLING.FIELDS("SERVICE_STOP").Value) & "."
                        
                    grdSummary.AddItem CStr(dsBILLING.FIELDS("SERVICE_START").Value) + Chr(9) + _
                                       sDes1 + Chr(9) + CStr(dsBILLING.FIELDS("SERVICE_AMOUNT").Value) + Chr(9) + _
                                       CStr(dsBILLING.FIELDS("BILLING_NUM").Value) + Chr(9) + CStr(1)
                
                    grdSummary.AddItem Chr(9) + sDes2 + Chr(9) + Chr(9) + dsBILLING.FIELDS("BILLING_NUM").Value + Chr(9) + CStr(2)
                                   
                    dsBILLING.MoveNext
                End With
                
               
            Next iRec
            
        Else
            MsgBox "Invalid Invoice Number ! ", vbInformation, "RF INVOICE"
            cmdClear_Click
            Exit Sub
        End If
    End If
    
    Call FillGrdDetail
    
End Sub

Private Sub cmdSave_Click()
    Dim iSumBillNum As Integer
    Dim dAmtDet As Double
    Dim dAmtSumm As Double
    Dim iSerCode As Integer
    Dim dSerStop As Date
    Dim iCommCode As Integer
    Dim bFirst As Boolean
   Dim sCareOf As String
    
    If txtInvNum = "" Then Exit Sub
            
    If txtInitials = "" Then
        MsgBox "Enter your Initials.", vbInformation, "SAVE"
        txtInitials.SetFocus
        Exit Sub
    End If
            
    If txtNewInvNum = "" Then
        MsgBox "Enter New Invoice Number", vbInformation, "SAVE"
        txtNewInvNum.SetFocus
        Exit Sub
    End If
    
    If Trim(txtInvNum) = Trim(txtNewInvNum) Then
        MsgBox "New Invoice Number should be different from the old", vbInformation, "SAVE"
        txtNewInvNum = ""
        Exit Sub
    End If
    
    If grdDetail.Rows = 0 Then
        MsgBox "Cann't save without details", vbInformation, "SAVE"
        Exit Sub
    End If
    
    
        
    sqlstmt = "SELECT * FROM INVOICE_HISTORY WHERE NEW_INVOICE_NUM='" & Trim(txtNewInvNum) & "'"
    Set dsINV_HISTORY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If dsINV_HISTORY.recordcount > 0 Then
        MsgBox "Enter Different Invoice Number.This Invoice numbere already exist in the system.", vbInformation, "SAVE"
        txtNewInvNum = ""
        Exit Sub
    End If
    
    'iSumBillNum = Val(grdDetail.Columns(7).Value)
    
    grdDetail.MoveFirst
    For iRec = 0 To grdDetail.Rows - 1
        dAmtDet = dAmtDet + Val(grdDetail.Columns(5).Value)
        grdDetail.MoveNext
    Next iRec
    grdDetail.MoveFirst
    
    grdSummary.MoveFirst
    For iRec = 0 To grdSummary.Rows - 1
'        If Val(Trim(grdSummary.Columns(3).Value)) = iSumBillNum Then
''            If bFirst = True Then
'                'iRow = iRec
'                bFirst = False
'            End If
            dAmtSumm = dAmtSumm + Val(grdSummary.Columns(2).Value)
'        End If
        grdSummary.MoveNext
    Next iRec
    grdSummary.MoveFirst
        
    If Val(dAmtSumm) <> Val(dAmtDet) Then
         MsgBox "SERVICE AMOUNT IN : " & vbCrLf & "SUMMARY : " & dAmtSumm & vbCrLf & " DETAIL : " & dAmtDet & _
                vbCrLf & "Amount must be same in Summary and Detail" & vbCrLf & _
                "Please make the appropriate Changes.", vbInformation + vbExclamation, "AMOUNT"
         Exit Sub
'                "Do you want to change the Summary Amount ?", vbYesNo, "SAVE") = vbYes Then
'            grdSummary.MoveRecords (grdRow)
'            grdSummary.Columns(2).Value = dAmtDet
         'Else
          '  Exit Sub
        'End If
    End If
    
    grdSummary.MoveFirst
    grdDetail.MoveFirst
    
    
    
    sqlstmt = "SELECT * FROM BILLING_CORRECTION"
    Set dsBILLING_CORRECTION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)

    grdSummary.MoveFirst

    For iRec = 0 To grdSummary.Rows - 1
        If IsNumeric(txtInvNum) Then
            sqlstmt = " SELECT * FROM RF_BILLING WHERE INVOICE_NUM='" & Trim(txtInvNum) & "' AND " _
                    & " BILLing_NUM='" & grdSummary.Columns(3).Value & "'"
            Set dsBILLING = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
            If dsBILLING.recordcount = 0 Then
                sqlstmt = "SELECT * FROM BILLING_CORRECTION WHERE INVOICE_NUM='" & Trim(txtInvNum) & "' AND " _
                        & " BILLING_NUM='" & grdSummary.Columns(3).Value & "'"
                Set dsBILLING = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
            End If
        Else
            sqlstmt = "SELECT * FROM BILLING_CORRECTION WHERE INVOICE_NUM='" & Trim(txtInvNum) & "' AND " _
                & " BILLING_NUM='" & grdSummary.Columns(3).Value & "'"
            Set dsBILLING = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        End If

        iSerCode = dsBILLING.FIELDS("SERVICE_CODE").Value
        dSerStop = dsBILLING.FIELDS("SERVICE_STOP").Value
        iCommCode = dsBILLING.FIELDS("COMMODITY_CODE").Value
        sCareOf = dsBILLING.FIELDS("CARE_OF").Value
    
        'If Trim(grdDetail.Columns(7).Value) = Trim(grdSummary.Columns(3).Value) Then
            dsBILLING_CORRECTION.AddNew
            dsBILLING_CORRECTION.FIELDS("CUSTOMER_ID").Value = Trim(txtCustId)
            dsBILLING_CORRECTION.FIELDS("SERVICE_CODE").Value = iSerCode
            dsBILLING_CORRECTION.FIELDS("BILLING_NUM").Value = grdSummary.Columns(3).Value
            dsBILLING_CORRECTION.FIELDS("SERVICE_START").Value = Trim(grdSummary.Columns(0).Value)
            dsBILLING_CORRECTION.FIELDS("SERVICE_STOP").Value = dSerStop
            dsBILLING_CORRECTION.FIELDS("SERVICE_AMOUNT").Value = Val(Trim(grdSummary.Columns(2).Value))
            dsBILLING_CORRECTION.FIELDS("SERVICE_STATUS").Value = "INVOICED"
            dsBILLING_CORRECTION.FIELDS("SERVICE_DESCRIPTION").Value = grdSummary.Columns(1).Value
            dsBILLING_CORRECTION.FIELDS("ARRIVAL_NUM").Value = Trim(txtLRNum)
            dsBILLING_CORRECTION.FIELDS("COMMODITY_CODE").Value = iCommCode
            dsBILLING_CORRECTION.FIELDS("INVOICE_NUM").Value = Trim(txtNewInvNum)
            dsBILLING_CORRECTION.FIELDS("BILLING_TYPE").Value = "PLT-STRG"
            dsBILLING_CORRECTION.FIELDS("INVOICE_DATE").Value = Format(Now, "MM/DD/YYYY")
            dsBILLING_CORRECTION.FIELDS("CARE_OF").Value = sCareOf
            dsBILLING_CORRECTION.FIELDS("ROW_NUM").Value = iRec + 1
            dsBILLING_CORRECTION.Update
'        End If
        grdSummary.MoveNext
    Next iRec

    grdDetail.MoveFirst
    sqlstmt = "SELECT * FROM BILLING_DETAIL_CORRECTION"
    Set dsBILLING_CORRECTION = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
    For iRec = 0 To grdDetail.Rows - 1
        If Trim(grdDetail.Columns(0).Value) <> "" Then
            dsBILLING_CORRECTION.AddNew
            dsBILLING_CORRECTION.FIELDS("CUSTOMER_ID").Value = Trim(txtCustId)
            dsBILLING_CORRECTION.FIELDS("SERVICE_CODE").Value = iSerCode
            dsBILLING_CORRECTION.FIELDS("BILLING_NUM").Value = grdDetail.Columns(7).Value
            dsBILLING_CORRECTION.FIELDS("SUM_BILL_NUM").Value = grdDetail.Columns(7).Value
            dsBILLING_CORRECTION.FIELDS("SERVICE_STOP").Value = dSerStop
            dsBILLING_CORRECTION.FIELDS("SERVICE_STATUS").Value = "INVOICED"
            dsBILLING_CORRECTION.FIELDS("ARRIVAL_NUM").Value = Trim(txtLRNum)
            dsBILLING_CORRECTION.FIELDS("COMMODITY_CODE").Value = iCommCode
            dsBILLING_CORRECTION.FIELDS("INVOICE_NUM").Value = Trim(txtNewInvNum)
            dsBILLING_CORRECTION.FIELDS("BILLING_TYPE").Value = "PLT-STRG"
            dsBILLING_CORRECTION.FIELDS("INVOICE_DATE").Value = CDate(Format(Now, "MM/DD/YYYY"))
            dsBILLING_CORRECTION.FIELDS("PALLET_ID").Value = grdDetail.Columns(0).Value
            dsBILLING_CORRECTION.FIELDS("SERVICE_START").Value = Trim(grdDetail.Columns(1).Value)
            dsBILLING_CORRECTION.FIELDS("SERVICE_STOP").Value = grdDetail.Columns(2).Value
            dsBILLING_CORRECTION.FIELDS("SERVICE_QTY").Value = grdDetail.Columns(3).Value
            dsBILLING_CORRECTION.FIELDS("SERVICE_RATE").Value = grdDetail.Columns(4).Value
            dsBILLING_CORRECTION.FIELDS("SERVICE_AMOUNT").Value = Val(Trim(grdDetail.Columns(5).Value))
            
            dsBILLING_CORRECTION.Update
        End If
        grdDetail.MoveNext
    Next iRec

    grdSummary.MoveFirst
    grdDetail.MoveFirst
    sqlstmt = "SELECT * FROM INVOICE_HISTORY"
    Set dsINV_HISTORY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    dsINV_HISTORY.AddNew
    dsINV_HISTORY.FIELDS("OLD_INVOICE_NUM").Value = Trim(txtInvNum)
    dsINV_HISTORY.FIELDS("NEW_INVOICE_NUM").Value = Trim(txtNewInvNum)
    dsINV_HISTORY.FIELDS("COMMENTS").Value = Trim(txtComment)
    dsINV_HISTORY.FIELDS("REVISED_DATE").Value = Format(Now, "MM/DD/YYYY")
    dsINV_HISTORY.FIELDS("INITIALS").Value = Trim(txtInitials)
    dsINV_HISTORY.Update
End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
End Sub

Private Sub grdSummary_AfterDelete(RtnDispErrMsg As Integer)
    Dim bDel  As Boolean
       
'    For iRec = 0 To grdSummary.Rows - 1
'        If grdSummary.Columns(3).Value = grdDetail.Columns(7).Value Then
'            bDel = True
'        End If
'        grdSummary.MoveNext
'    Next iRec
'
'    If bDel = False Then grdDetail.RemoveAll
 '   For iRec = 0 To grdDetail.Rows - 1
        
        
        
End Sub

Private Sub FillGrdDetail()
    Dim dsOrderNum As Object
    Dim STmpOrder As String
    Dim iRecCount As Integer
    
    grdRow = grdSummary.Row
    
    grdDetail.RemoveAll
    
    For iRec = 0 To grdSummary.Rows
        If Trim(grdSummary.Columns(0).Value) <> "" Then
        
            sqlstmt = " SELECT * FROM BILLING_DETAIL_CORRECTION WHERE " _
                    & " SUM_BILL_NUM='" & Trim(grdSummary.Columns(3).Value) & "'" _
                    & " AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                    & " AND ARRIVAL_NUM='" & txtLRNum & "' AND INVOICE_NUM='" & Trim(txtInvNum) & "'"
                    
            Set dsRFBILLING_DETAIL = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
            If dsRFBILLING_DETAIL.recordcount > 0 Then
                'do nothing
            Else
                sqlstmt = " SELECT * FROM RF_BILLING_DETAIL WHERE SUM_BILL_NUM='" & Trim(grdSummary.Columns(3).Value) & "'" _
                    & " AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                    & " AND ARRIVAL_NUM='" & txtLRNum & "' " _
                    & " ORDER BY SERVICE_START,SERVICE_STOP"
                    Set dsRFBILLING_DETAIL = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
            End If
    
    
            If dsRFBILLING_DETAIL.recordcount > 0 Then
    
            
                If dsRFBILLING_DETAIL.FIELDS("CUSTOMER_ID").Value = 1986 Then
                    grdDetail.Columns(4).Caption = "RATE/CWT"
                Else
                    grdDetail.Columns(4).Caption = "RATE/PLT"
                End If
        
                For iRecCount = 1 To dsRFBILLING_DETAIL.recordcount
                    DoEvents
                            
                    'Picks up the order num from cargo activity if the vessel is "TRUCKED IN CARGO"
                    If txtVessel = "" Then
            
                        sqlstmt = "Select distinct Order_num from cargo_activity where PALLET_ID=" _
                                & "'" & Trim(dsRFBILLING_DETAIL.FIELDS("PALLET_ID").Value) & "' AND " _
                                & " service_code='8' and customer_id='" & Trim(txtCustId) & "'"
                
                        Set dsOrderNum = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
                        If OraDatabase.lastservererr = 0 And dsOrderNum.recordcount <> 0 Then
                            If Not IsNull(dsOrderNum.FIELDS("ORDER_NUM").Value) Then
                                STmpOrder = dsOrderNum.FIELDS("ORDER_NUM").Value
                            Else
                                STmpOrder = ""
                            End If
                        Else
                            STmpOrder = ""
                        End If
                    Else
                        STmpOrder = ""
                    End If
            
                    grdDetail.AddItem dsRFBILLING_DETAIL.FIELDS("PALLET_ID").Value + Chr(9) + _
                                      CStr(dsRFBILLING_DETAIL.FIELDS("SERVICE_START").Value) + Chr(9) + _
                                      CStr(dsRFBILLING_DETAIL.FIELDS("SERVICE_STOP").Value) + Chr(9) + _
                                      CStr(dsRFBILLING_DETAIL.FIELDS("SERVICE_QTY").Value) + Chr(9) + _
                                      CStr(dsRFBILLING_DETAIL.FIELDS("SERVICE_RATE").Value) + Chr(9) + _
                                      CStr(dsRFBILLING_DETAIL.FIELDS("SERVICE_AMOUNT").Value) + Chr(9) + _
                                      STmpOrder + Chr(9) + CStr("" & dsRFBILLING_DETAIL.FIELDS("SUM_BILL_NUM").Value) + Chr(9) + _
                                      CStr(iRec)
            
                       
                    dsRFBILLING_DETAIL.MoveNext
                Next iRecCount
            End If
        End If
        grdDetail.AddItem "" '+ Chr(9) + "" + Chr(9) + "" + Chr(9) + _
                                      CStr(dsRFBILLING_DETAIL.FIELDS("SERVICE_QTY").Value) + Chr(9) + _
                                      CStr(dsRFBILLING_DETAIL.FIELDS("SERVICE_RATE").Value) + Chr(9) + _
                                      CStr(dsRFBILLING_DETAIL.FIELDS("SERVICE_AMOUNT").Value) + Chr(9) + _
                                      STmpOrder + Chr(9) + CStr(dsRFBILLING_DETAIL.FIELDS("SUM_BILL_NUM").Value) + Chr(9) + _
                                      CStr(iRec)
        grdSummary.MoveNext
    Next iRec
End Sub
Private Sub txtCustId_Validate(Cancel As Boolean)
    If txtCustId = "" Then Exit Sub
    
    If Not IsNumeric(txtCustId) Then
        MsgBox "Expecting Numeric Value.", vbInformation, "CUSTOMER ID"
        txtCustId = ""
        Cancel = True
        Exit Sub
    End If
        
    sqlstmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID='" & Trim(txtCustId) & "' "
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If dsCUSTOMER_PROFILE.recordcount > 0 Then
        txtCustName = UCase(dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value)
    End If
    
    
End Sub
Private Sub txtCustName_KeyPress(KeyAscii As Integer)
    KeyAscii = 0
End Sub

Private Sub txtInitials_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub


Private Sub txtLRNum_Validate(Cancel As Boolean)
    If txtLRNum = "" Then Exit Sub
    
    If Not IsNumeric(txtLRNum) Then
        MsgBox "Expecting Numeric Value.", vbInformation, "LR NUMBER"
        txtLRNum = ""
        Cancel = True
        Exit Sub
    End If
        
    sqlstmt = "SELECT * FROM VESSEL_PROFILE WHERE ARRIVAL_NUM='" & Trim(txtLRNum) & "' "
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If dsVESSEL_PROFILE.recordcount > 0 Then
        txtVessel = UCase(dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value)
    Else
        txtVessel = ""
    End If
End Sub

Private Sub txtVessel_KeyPress(KeyAscii As Integer)
    KeyAscii = 0
End Sub
