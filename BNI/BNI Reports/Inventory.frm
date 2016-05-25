VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{6B7E6392-850A-101B-AFC0-4210102A8DA7}#1.3#0"; "COMCTL32.OCX"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "COMDLG32.OCX"
Begin VB.Form frmInventory 
   Caption         =   "Inventory Reports"
   ClientHeight    =   10230
   ClientLeft      =   165
   ClientTop       =   450
   ClientWidth     =   15180
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
   ScaleHeight     =   10230
   ScaleWidth      =   15180
   StartUpPosition =   3  'Windows Default
   Begin MSComDlg.CommonDialog CSVSaveBox 
      Left            =   8400
      Top             =   9480
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
   End
   Begin VB.TextBox txtSupplierId 
      Appearance      =   0  'Flat
      Enabled         =   0   'False
      Height          =   330
      Left            =   3960
      TabIndex        =   28
      Top             =   3480
      Width           =   1215
   End
   Begin VB.Frame frRange 
      Caption         =   "RANGE OPTIONS"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   1695
      Left            =   9600
      TabIndex        =   23
      Top             =   2520
      Width           =   4695
      Begin VB.OptionButton optInvOnly 
         Caption         =   "Show Current Inventory Only"
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
         Index           =   1
         Left            =   360
         TabIndex        =   25
         Top             =   720
         Value           =   -1  'True
         Width           =   2895
      End
      Begin VB.OptionButton optInvOnly 
         Caption         =   "Show History Too"
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
         Index           =   0
         Left            =   360
         TabIndex        =   24
         Top             =   240
         Width           =   2415
      End
      Begin MSComCtl2.DTPicker DtpAsOf 
         Height          =   330
         Left            =   1080
         TabIndex        =   26
         Top             =   1200
         Width           =   1335
         _ExtentX        =   2355
         _ExtentY        =   582
         _Version        =   393216
         CustomFormat    =   "MM/dd/yyyy"
         Format          =   16580611
         CurrentDate     =   36782
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "As Of  :"
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
         Left            =   360
         TabIndex        =   27
         Top             =   1200
         Width           =   600
      End
   End
   Begin VB.Frame frSupplierOptions 
      Caption         =   "SUPPLIER SEARCH OPTIONS"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   1695
      Left            =   600
      TabIndex        =   18
      Top             =   2520
      Width           =   8895
      Begin VB.OptionButton OptSupplier 
         Caption         =   "BY EXPORTER(SUPPLIER) ONLY"
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
         Index           =   2
         Left            =   120
         TabIndex        =   22
         Top             =   1080
         Width           =   3135
      End
      Begin VB.OptionButton OptSupplier 
         Caption         =   "AND EXPORTER(SUPPLIER)"
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
         Index           =   1
         Left            =   120
         TabIndex        =   21
         Top             =   720
         Width           =   2775
      End
      Begin VB.OptionButton OptSupplier 
         Caption         =   "DON'T INCLUDE EXPORTER(SUPPLIER) IN SEARCH"
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
         Index           =   0
         Left            =   120
         TabIndex        =   20
         Top             =   360
         Value           =   -1  'True
         Width           =   4695
      End
      Begin VB.TextBox txtSupplierName 
         Appearance      =   0  'Flat
         Enabled         =   0   'False
         Height          =   330
         Left            =   4680
         TabIndex        =   19
         Top             =   960
         Width           =   4095
      End
   End
   Begin VB.CommandButton cmdSend 
      Caption         =   "Send Mail"
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
      Left            =   9068
      TabIndex        =   17
      Top             =   9480
      Width           =   1575
   End
   Begin ComctlLib.StatusBar statBar 
      Align           =   2  'Align Bottom
      Height          =   330
      Left            =   0
      TabIndex        =   10
      Top             =   9900
      Width           =   15180
      _ExtentX        =   26776
      _ExtentY        =   582
      Style           =   1
      SimpleText      =   ""
      _Version        =   327682
      BeginProperty Panels {0713E89E-850A-101B-AFC0-4210102A8DA7} 
         NumPanels       =   1
         BeginProperty Panel1 {0713E89F-850A-101B-AFC0-4210102A8DA7} 
            Object.Tag             =   ""
         EndProperty
      EndProperty
   End
   Begin VB.CommandButton cmdCSV 
      Caption         =   "Create CSV"
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
      Left            =   6698
      TabIndex        =   8
      Top             =   9480
      Width           =   1575
   End
   Begin VB.CommandButton cmdClose 
      Caption         =   "&Close"
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
      Left            =   11438
      TabIndex        =   7
      Top             =   9480
      Width           =   1575
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
      Left            =   4328
      TabIndex        =   6
      Top             =   9480
      Width           =   1575
   End
   Begin VB.CommandButton cmdRet 
      Caption         =   "&Retrieve"
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
      Left            =   2040
      TabIndex        =   5
      Top             =   9480
      Width           =   1575
   End
   Begin VB.Frame Frame1 
      Caption         =   "SEARCH OPTIONS"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   2415
      Left            =   638
      TabIndex        =   4
      Top             =   0
      Width           =   13695
      Begin VB.TextBox txtCommodity 
         Appearance      =   0  'Flat
         Enabled         =   0   'False
         Height          =   330
         Left            =   4560
         TabIndex        =   16
         Top             =   1800
         Width           =   4095
      End
      Begin VB.TextBox txtVessel 
         Appearance      =   0  'Flat
         Enabled         =   0   'False
         Height          =   330
         Left            =   4560
         TabIndex        =   15
         Top             =   1320
         Width           =   4095
      End
      Begin VB.TextBox txtCustName 
         Appearance      =   0  'Flat
         Enabled         =   0   'False
         Height          =   330
         Left            =   4560
         TabIndex        =   14
         Top             =   840
         Width           =   4095
      End
      Begin VB.TextBox txtCommCode 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   3240
         TabIndex        =   13
         Top             =   1800
         Width           =   1215
      End
      Begin VB.TextBox txtLrNum 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   3240
         TabIndex        =   12
         Top             =   1320
         Width           =   1215
      End
      Begin VB.TextBox txtCustId 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   3240
         TabIndex        =   11
         Top             =   840
         Width           =   1215
      End
      Begin VB.OptionButton optSelect 
         Caption         =   "COMMODITY"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Index           =   3
         Left            =   960
         TabIndex        =   2
         Top             =   1838
         Width           =   1695
      End
      Begin VB.OptionButton optSelect 
         Caption         =   "SHIP"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Index           =   2
         Left            =   960
         TabIndex        =   1
         Top             =   1358
         Width           =   1215
      End
      Begin VB.OptionButton optSelect 
         Caption         =   "CUSTOMER"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Index           =   1
         Left            =   960
         TabIndex        =   0
         Top             =   878
         Width           =   1575
      End
      Begin VB.OptionButton optSelect 
         Caption         =   "ALL"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Index           =   0
         Left            =   960
         TabIndex        =   3
         Top             =   398
         Visible         =   0   'False
         Width           =   1695
      End
   End
   Begin SSDataWidgets_B.SSDBGrid GrdData 
      Height          =   4935
      Left            =   0
      TabIndex        =   9
      Top             =   4440
      Width           =   15135
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   20
      RowHeight       =   503
      Columns.Count   =   20
      Columns(0).Width=   1085
      Columns(0).Caption=   "Exp/Sup"
      Columns(0).Name =   "Exp/Sup"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1085
      Columns(1).Caption=   "Cust"
      Columns(1).Name =   "Cust"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1164
      Columns(2).Caption=   "Vessel"
      Columns(2).Name =   "Vessel"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1191
      Columns(3).Caption=   "Comm"
      Columns(3).Name =   "Comm"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1323
      Columns(4).Caption=   "BOL"
      Columns(4).Name =   "BOL"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   3228
      Columns(5).Caption=   "Mark"
      Columns(5).Name =   "Mark"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1826
      Columns(6).Caption=   "Date Rcvd"
      Columns(6).Name =   "Date Rcvd"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1614
      Columns(7).Caption=   "Qty Rcvd"
      Columns(7).Name =   "Qty Rcvd"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1429
      Columns(8).Caption=   "Weight"
      Columns(8).Name =   "Weight"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1191
      Columns(9).Caption=   "Qty1"
      Columns(9).Name =   "Qty1"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   979
      Columns(10).Caption=   "Unt1"
      Columns(10).Name=   "Unt1"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   1111
      Columns(11).Caption=   "Qty2"
      Columns(11).Name=   "Qty2"
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   1085
      Columns(12).Caption=   "Unt2"
      Columns(12).Name=   "Unt2"
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   1693
      Columns(13).Caption=   "Qty Dmgd"
      Columns(13).Name=   "Qty Dmgd"
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      Columns(14).Width=   1588
      Columns(14).Caption=   "Withdrwn"
      Columns(14).Name=   "Withdrwn"
      Columns(14).DataField=   "Column 14"
      Columns(14).DataType=   8
      Columns(14).FieldLen=   256
      Columns(15).Width=   1905
      Columns(15).Caption=   "Tdy WDrwn"
      Columns(15).Name=   "Tdy WDrwn"
      Columns(15).DataField=   "Column 15"
      Columns(15).DataType=   8
      Columns(15).FieldLen=   256
      Columns(16).Width=   1191
      Columns(16).Caption=   "Left"
      Columns(16).Name=   "Left"
      Columns(16).DataField=   "Column 16"
      Columns(16).DataType=   8
      Columns(16).FieldLen=   256
      Columns(17).Width=   1349
      Columns(17).Caption=   "Wt Left"
      Columns(17).Name=   "Wt Left"
      Columns(17).DataField=   "Column 17"
      Columns(17).DataType=   8
      Columns(17).FieldLen=   256
      Columns(18).Width=   953
      Columns(18).Caption=   "R/H"
      Columns(18).Name=   "R/H"
      Columns(18).DataField=   "Column 18"
      Columns(18).DataType=   8
      Columns(18).FieldLen=   256
      Columns(19).Width=   1852
      Columns(19).Caption=   "Pending Qty"
      Columns(19).Name=   "Pending"
      Columns(19).DataField=   "Column 19"
      Columns(19).DataType=   8
      Columns(19).FieldLen=   256
      _ExtentX        =   26696
      _ExtentY        =   8705
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
End
Attribute VB_Name = "frmInventory"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim blnNormal As Boolean
Dim iRec As Integer
Dim sExporterId As String
Dim sCustId As String
Dim sLrNum As String

Dim sCommCode As String
Dim sBOL As String
Dim sMark As String
Dim sMarkRcvd As String
Dim sDateRcvd As String
Dim dQtyRcvd As Double
Dim dWtRcvd As Double
Dim dMyWtRcvd As Double
Dim dWtLeft As Double
Dim lQty1 As Double
Dim sUnt1 As String
Dim lQty2 As Double
Dim lQty2Rcvd As Double
Dim sUnt2 As String
Dim lQty1Rcvd As Double
Dim lQtyDmg As Double
Dim lWithdrwn As Double
Dim lTdyWDrwn As Double
Dim lLeft As Double
Dim sRH As String
Dim dummyPend As Double
Dim sFys As New FileSystemObject
Dim iLine As Long
Dim sum1 As Double
Dim sum2 As Double
Dim sum3 As Double
Dim sum4 As Double
Dim sum5 As Double
Dim sum6 As Double
Dim sum7 As Double
Dim sum8 As Double
Dim sum9 As Double
Dim iPage As Integer
Dim RcvdTotalLine As Double
Dim WithTotalLine As Double
Dim LeftTotalLine As Double
Dim PendTotalLine As Double
Dim WTLeftTotalLine As Double


Private Sub cmdClose_Click()

    Unload Me
    
End Sub

Private Sub cmdCSV_Click()

    Dim bFirst As Boolean
    Dim bFirst2 As Boolean
    Dim iGrp As Integer
    Dim iGrp2 As Integer
    Dim iNo As Integer
    Dim iSNo As Integer
    Dim lTQtyRcvd As Double
    Dim dTWtRcvd As Double
    Dim lTQty1 As Double
    Dim lTQty2 As Double
    Dim lTQtyWDrwn As Double
    Dim lTLeft As Double
    Dim dTWtLeft As Double
    Dim lQtyDmg As Double
    Dim pend As Double
    
    Dim lSTQtyRcvd As Double
    Dim dSTWtRcvd As Double
    Dim lSTQty1 As Double
    Dim lSTQty2 As Double
    Dim lSTQtyWDrwn As Double
    Dim lSTLeft As Double
    Dim dSTWtLeft As Double
    Dim lSQtyDmg As Double
    Dim spend As Double
    Dim sWeightLabel As String
    
    bFirst = True
    bFirst2 = True
    
    CSVSaveBox.Filter = "CSV files (*.csv)|*.csv|All files (*.*)|*.*"
    CSVSaveBox.InitDir = "C:\"
    CSVSaveBox.ShowSave
    If CSVSaveBox.FileName = "" Then
        ' User canceled.
    Else
    
        Open CSVSaveBox.FileName For Output As #1
        
        If optSelect(0).Value = True Then
           
        ElseIf optSelect(1).Value = True Then               'CUSTOMER
        
            Print #1, "INVENTORY FOR CUSTOMER  "; Trim(txtCustName); "  As of: "; Format(DtpAsOf.Value, "MM/DD/YYYY")
            Print #1, ""
            Print #1, ""
            Print #1, ""
        
            Print #1, "Vessel,Comm,BOL,Mark,Dt Rcvd,Qty Rcvd,QTY1,Unt1,Qty2,Unt2,Dmgd,Withdrawn,Left,Pending,Weight,R/H"
            Print #1, ""
            
            GrdData.MoveFirst
    '        iGrp = GrdData.Columns(2).Value
            
            lTQtyRcvd = 0
            dTWtRcvd = 0
            lTQty1 = 0
            lTQty2 = 0
            lQtyDmg = 0
            lTQtyWDrwn = 0
            lTLeft = 0
            dTWtLeft = 0
            pend = 0
    
            For iRec = 0 To GrdData.Rows - 1
            
                Print #1, GrdData.Columns(2).Value; ","; GrdData.Columns(3).Value; ","; GrdData.Columns(4).Value; ","; _
                    GrdData.Columns(5).Value; ","; GrdData.Columns(6).Value; ","; GrdData.Columns(7).Value; ","; _
                    GrdData.Columns(9).Value; ","; GrdData.Columns(10).Value; ","; GrdData.Columns(11).Value; ","; _
                    GrdData.Columns(12).Value; ","; GrdData.Columns(13).Value; ","; GrdData.Columns(14).Value; ","; _
                    GrdData.Columns(16).Value; ","; GrdData.Columns(19).Value; ","; GrdData.Columns(17).Value; ","; _
                    GrdData.Columns(18).Value
                    
                ' running totals, unused in current version
                lTQtyRcvd = lTQtyRcvd + Val(GrdData.Columns(7).Value)
                dTWtRcvd = dTWtRcvd + Val(GrdData.Columns(8).Value)
                lTQty1 = lTQty1 + Val(GrdData.Columns(9).Value)
                lTQty2 = lTQty2 + Val(GrdData.Columns(11).Value)
                lQtyDmg = lQtyDmg + Val(GrdData.Columns(13).Value)
                lTQtyWDrwn = lTQtyWDrwn + Val(GrdData.Columns(14).Value)
                lTLeft = lTLeft + Val(GrdData.Columns(16).Value)
                dTWtLeft = dTWtLeft + Val(GrdData.Columns(17).Value)
                pend = pend + Val(GrdData.Columns(19).Value)
                
                GrdData.MoveNext
                
            Next iRec
                    
        
         
        ElseIf optSelect(2).Value = True Then        'VESSEL
            
            Print #1, Tab(35); "INVENTORY FOR VESSEL  "; Trim(txtVessel); "  As of: "; Format(DtpAsOf.Value, "MM/DD/YYYY")
            Print #1, ""
            Print #1, ""
            Print #1, ""
        
            Print #1, "Cust,Comm,BOL,Mark,Dt Rcvd,Qty Rcvd,QTY1,Unt1,Qty2,Unt2,Dmgd,Withdrawn,Left,Pending,Weight,R/H"
            Print #1, ""
            
            GrdData.MoveFirst
    '        iGrp = GrdData.Columns(2).Value
            
            lTQtyRcvd = 0
            dTWtRcvd = 0
            lTQty1 = 0
            lTQty2 = 0
            lQtyDmg = 0
            lTQtyWDrwn = 0
            lTLeft = 0
            dTWtLeft = 0
            pend = 0
    
            For iRec = 0 To GrdData.Rows - 1
            
                Print #1, GrdData.Columns(1).Value; ","; GrdData.Columns(3).Value; ","; GrdData.Columns(4).Value; ","; _
                    GrdData.Columns(5).Value; ","; GrdData.Columns(6).Value; ","; GrdData.Columns(7).Value; ","; _
                    GrdData.Columns(9).Value; ","; GrdData.Columns(10).Value; ","; GrdData.Columns(11).Value; ","; _
                    GrdData.Columns(12).Value; ","; GrdData.Columns(13).Value; ","; GrdData.Columns(14).Value; ","; _
                    GrdData.Columns(16).Value; ","; GrdData.Columns(19).Value; ","; GrdData.Columns(17).Value; ","; _
                    GrdData.Columns(18).Value
                    
                ' running totals, unused in current version
                lTQtyRcvd = lTQtyRcvd + Val(GrdData.Columns(7).Value)
                dTWtRcvd = dTWtRcvd + Val(GrdData.Columns(8).Value)
                lTQty1 = lTQty1 + Val(GrdData.Columns(9).Value)
                lTQty2 = lTQty2 + Val(GrdData.Columns(11).Value)
                lQtyDmg = lQtyDmg + Val(GrdData.Columns(13).Value)
                lTQtyWDrwn = lTQtyWDrwn + Val(GrdData.Columns(14).Value)
                lTLeft = lTLeft + Val(GrdData.Columns(16).Value)
                dTWtLeft = dTWtLeft + Val(GrdData.Columns(17).Value)
                pend = pend + Val(GrdData.Columns(19).Value)
                
                GrdData.MoveNext
                
            Next iRec
            
       
        ElseIf optSelect(3).Value = True Then        'COMMODITY
        
            
            Print #1, Tab(35); "INVENTORY FOR COMMODITY  "; Trim(txtCommodity); "  As of: "; Format(DtpAsOf.Value, "MM/DD/YYYY")
            Print #1, ""
            Print #1, ""
            Print #1, ""
        
            Print #1, "Cust,Vessel,BOL,Mark,Dt Rcvd,Qty Rcvd,QTY1,Unt1,Qty2,Unt2,Dmgd,Withdrawn,Left,Pending,Weight,R/H"
            Print #1, ""
            
            GrdData.MoveFirst
    '        iGrp = GrdData.Columns(2).Value
            
            lTQtyRcvd = 0
            dTWtRcvd = 0
            lTQty1 = 0
            lTQty2 = 0
            lQtyDmg = 0
            lTQtyWDrwn = 0
            lTLeft = 0
            dTWtLeft = 0
            pend = 0
    
            For iRec = 0 To GrdData.Rows - 1
            
                Print #1, GrdData.Columns(1).Value; ","; GrdData.Columns(2).Value; ","; GrdData.Columns(4).Value; ","; _
                    GrdData.Columns(5).Value; ","; GrdData.Columns(6).Value; ","; GrdData.Columns(7).Value; ","; _
                    GrdData.Columns(9).Value; ","; GrdData.Columns(10).Value; ","; GrdData.Columns(11).Value; ","; _
                    GrdData.Columns(12).Value; ","; GrdData.Columns(13).Value; ","; GrdData.Columns(14).Value; ","; _
                    GrdData.Columns(16).Value; ","; GrdData.Columns(19).Value; ","; GrdData.Columns(17).Value; ","; _
                    GrdData.Columns(18).Value
                    
                ' running totals, unused in current version
                lTQtyRcvd = lTQtyRcvd + Val(GrdData.Columns(7).Value)
                dTWtRcvd = dTWtRcvd + Val(GrdData.Columns(8).Value)
                lTQty1 = lTQty1 + Val(GrdData.Columns(9).Value)
                lTQty2 = lTQty2 + Val(GrdData.Columns(11).Value)
                lQtyDmg = lQtyDmg + Val(GrdData.Columns(13).Value)
                lTQtyWDrwn = lTQtyWDrwn + Val(GrdData.Columns(14).Value)
                lTLeft = lTLeft + Val(GrdData.Columns(16).Value)
                dTWtLeft = dTWtLeft + Val(GrdData.Columns(17).Value)
                pend = pend + Val(GrdData.Columns(19).Value)
                
                GrdData.MoveNext
                
            Next iRec
        
       
        End If
        Close #1
        
    End If
End Sub
Private Sub cmdRet_Click()

    Call fillGridDetail
    
End Sub
Sub IntializeValues()
    sExporterId = ""
    sCustId = ""
    sLrNum = ""
    sCommCode = ""
    sBOL = ""
    sMark = ""
    sDateRcvd = ""
    dQtyRcvd = 0
    dWtRcvd = 0
    dMyWtRcvd = 0
    lQty1 = 0
    sUnt1 = ""
    lQty2 = 0
    lQty1Rcvd = 0
    lQty2Rcvd = 0
    sUnt2 = ""
    lQtyDmg = 0
    lWithdrwn = 0
    lTdyWDrwn = 0
    lLeft = 0
    dWtLeft = 0
    sRH = ""
    dummyPend = 0
End Sub

Private Sub cmdSend_Click()

    If sFys.FolderExists("D:\INVENTORY") = True Then
        If sFys.FileExists("D:\INVENTORY\output.csv") = True Then
            frmSendMail.Show
        Else
            MsgBox "First create the email file.", vbInformation, "EMAIL FILE"
            Exit Sub
        End If
    Else
        MsgBox "Make sure Inventory Folder exists in D drive and the create the email file.", vbInformation, "EMAIL FOLDER"
        Exit Sub
    End If
    
End Sub
Private Sub Form_Load()
   
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    DtpAsOf.Value = Format(Now, "MM/DD/YYYY")
    
End Sub
Private Sub fillGridDetail()
    
    Dim iLotLeft As Integer
    Dim ithTransfer As String

    Call IntializeValues
    
    GrdData.RemoveAll
    GrdData.Columns(0).Visible = True 'exp/sup
    GrdData.Columns(1).Visible = True 'cust
    GrdData.Columns(2).Visible = True 'vessel
    GrdData.Columns(3).Visible = True 'comm
    
    statBar.SimpleText = ""

    ' initialize running totals for the last displayed line
    RcvdTotalLine = 0
    WithTotalLine = 0
    LeftTotalLine = 0
    PendTotalLine = 0
    WTLeftTotalLine = 0

        
    If optSelect(1).Value = True Then    'CUSTOMER
    
        Call makeTopLevelSQL
        
        '.....Pawan 10/25/2001 Added as per Danial(Marty)....and CARGO_MARK not like 'TR%'
        Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
        
        If dsCARGO_MANIFEST.RECORDCOUNT <= 0 Then
            statBar.SimpleText = "No cargos were in inventory for this customer as of " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Else
            ' Reset lot in storage count
            iLotLeft = 0
            
            GrdData.Columns(1).Visible = False
            
            For iRec = 1 To dsCARGO_MANIFEST.RECORDCOUNT
                
                Call IntializeValues
                              
                'Get the value directly from the database
                sExporterId = Val("" & dsCARGO_MANIFEST.fields("EXPORTER_ID").Value)
                sCustId = ""
                sLrNum = Val("" & dsCARGO_MANIFEST.fields("LR_NUM").Value)
                sCommCode = Val("" & dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value)
                sBOL = "" & dsCARGO_MANIFEST.fields("CARGO_BOL").Value
                sMark = "" & dsCARGO_MANIFEST.fields("CARGO_MARK").Value
                sDateRcvd = "" & dsCARGO_MANIFEST.fields("DATE_RECEIVED").Value
                dQtyRcvd = Val("" & dsCARGO_MANIFEST.fields("QTY_RECEIVED").Value)
                lQtyDmg = 0 + Val("" & dsCARGO_MANIFEST.fields("QTY_DAMAGED").Value)
                sUnt1 = "" & dsCARGO_MANIFEST.fields("QTY1_UNIT").Value
                sUnt2 = Trim("" & dsCARGO_MANIFEST.fields("QTY2_UNIT").Value)
                If UCase(Trim$("" & dsCARGO_MANIFEST.fields("MANIFEST_STATUS").Value)) = "HOLD" Then sRH = "H"
                If UCase(Trim$("" & dsCARGO_MANIFEST.fields("MANIFEST_STATUS").Value)) = "RELEASED" Then sRH = "R"
                If sBOL = "B63325A" Then
                    sBOL = "B63325A"
                End If
                
                dQty1FromCM = Val("" & dsCARGO_MANIFEST.fields("QTY_EXPECTED").Value)
                dQty2FromCM = Val("" & dsCARGO_MANIFEST.fields("QTY2_EXPECTED").Value)
                dWeightFromCM = Val("" & dsCARGO_MANIFEST.fields("CARGO_WEIGHT").Value)
'                   End of first pass (raw uncalculated data)
                
                'Calculate QTY1, QTY1 Left (same as QTY1), and QTY Withdrawn
                SqlStmt = " SELECT SUM(QTY_CHANGE) QtyChge FROM CARGO_ACTIVITY WHERE LOT_NUM = '" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "'" _
                        & " AND DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
                Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                
                lWithdrwn = 0 + Val("" & dsCARGO_ACTIVITY.fields("QtyChge").Value)
                lLeft = Val("" & dsCARGO_MANIFEST.fields("QTY_RECEIVED").Value) - Val("" & dsCARGO_ACTIVITY.fields("QtyChge").Value)
                lQty1 = lLeft

                SqlStmt = "SELECT SUM(QTY_CHANGE) TDQtyChge FROM CARGO_ACTIVITY WHERE LOT_NUM='" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "'"
                Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                lTdyWDrwn = 0 + Val("" & dsCARGO_ACTIVITY.fields("TDQtyChge").Value)
'                   End of 2nd pass, calculations of QTY1 values
                                                         
'                It has come to my attention that entire cargos can be transferred, leaving dQty1FromCM, dQty2FromCM, and dWeightFromCM
'                With a value of zero, causing the ratio calculations to go haywire; threfore I need to add this check to determine
'                An alternate way of finding original weights and QTY2's.  Note that, unlike the previous version of this program,
'                Rather than looking for bastardized cargo marks, I use the ORIGINAL_CONTAINER_NUM field to definitely determine
'                This data.
                If dQty1FromCM = 0 Then
                    FindOriginalValues (dsCARGO_MANIFEST.fields("LOT_NUM").Value)
                End If
                
                
                'Original Weight Calculation Starts
                dQty1ToWeightRatio = dWeightFromCM / dQty1FromCM
                dMyWtRcvd = dQty1ToWeightRatio * dQtyRcvd
'                    End of 3rd pass, calculation of original weight


                'Wt left calculation
                dWtLeft = dQty1ToWeightRatio * lQty1
'                    End of 4th pass, calculation of current weight
               
               
                'Qty2 left calculation
                dQty1ToQty2Ratio = dQty2FromCM / dQty1FromCM
                lQty2 = dQty1ToQty2Ratio * lQty1
'                    End of 5th pass, calculation of current qty2
                
                               
                'Pending calculation; and the hardest part
                SqlStmt = " SELECT SUM(QTY1) Qt1 FROM BNI_DUMMY_WITHDRAWAL  WHERE lr_num='" & sLrNum & "'" _
                        & " AND commodity_code='" & sCommCode & "' and owner_id='" & Trim(txtCustId) & "'" _
                        & " AND bol='" & sBOL & "' AND mark='" & sMark & "' and status is null" _
                        & " AND order_date <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"

                Set dsDUMMY_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                If dsDUMMY_ACTIVITY.RECORDCOUNT > 0 Then
                    If Not IsNull(dsDUMMY_ACTIVITY.fields("Qt1").Value) Then
                        dummyPend = dsDUMMY_ACTIVITY.fields("Qt1").Value
                    Else
                        dummyPend = 0
                    End If
                Else
                    dummyPend = 0
                End If
                
                'Also add on the qty of cargo shipped on a date later than the Cut Off
                SqlStmt = "select sum(QTY_CHANGE) Qt1 from cargo_activity where lot_num = '" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "' " _
                        & "and date_of_activity > TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                        & " and SERVICE_CODE != '6120'"
                Set dsLATTER_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                If dsLATTER_ACTIVITY.RECORDCOUNT > 0 Then
                    If Not IsNull(dsLATTER_ACTIVITY.fields("Qt1").Value) Then
                        dummyPend = dummyPend + dsLATTER_ACTIVITY.fields("Qt1").Value
                    End If
                End If
'                    End of 6th pass, Pending Calculation
                                                               
                If optInvOnly(1).Value = True And lLeft <= 0 Then
                    ' Won't add this record to the data grid if it is Show Current Inventory Only
                Else
                    GrdData.AddItem sExporterId + Chr(9) + sCustId + Chr(9) + sLrNum + Chr(9) + sCommCode + Chr(9) + sBOL + Chr(9) + _
                                    sMark + Chr(9) + sDateRcvd + Chr(9) + CStr(dQtyRcvd) + Chr(9) + _
                                    CStr(Round(dMyWtRcvd, 0)) + Chr(9) + CStr(lQty1) + Chr(9) + sUnt1 + Chr(9) + CStr(Round(lQty2, 2)) + Chr(9) + _
                                    sUnt2 + Chr(9) + CStr(lQtyDmg) + Chr(9) + CStr(lWithdrwn) + Chr(9) + CStr(lTdyWDrwn) + Chr(9) + _
                                    CStr(lLeft) + Chr(9) + CStr(Round(dWtLeft, 0)) + Chr(9) + sRH + Chr(9) + CStr(dummyPend)
                    GrdData.Refresh
                    
                    RcvdTotalLine = RcvdTotalLine + dQtyRcvd
                    WithTotalLine = WithTotalLine + lWithdrwn
                    LeftTotalLine = LeftTotalLine + lLeft
                    PendTotalLine = PendTotalLine + dummyPend
                    WTLeftTotalLine = WTLeftTotalLine + dWtLeft
                    
                    If lLeft > 0 Then
                        iLotLeft = iLotLeft + 1
                    End If
                
                End If
                
                
                dsCARGO_MANIFEST.MoveNext
            Next iRec
            
            GrdData.AddItem Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + "TOTALS:" + Chr(9) + Chr(9) + CStr(RcvdTotalLine) + Chr(9) + Chr(9) + _
                            Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + CStr(WithTotalLine) + Chr(9) + Chr(9) + _
                            CStr(LeftTotalLine) + Chr(9) + CStr(Round(WTLeftTotalLine, 0)) + Chr(9) + Chr(9) + CStr(PendTotalLine)
            GrdData.Refresh
        
            If optInvOnly(0).Value = True Then
                statBar.SimpleText = "Totally " & dsCARGO_MANIFEST.RECORDCOUNT & " entries with " & iLotLeft & " entries still in storage"
            Else
                statBar.SimpleText = iLotLeft & " entries still in storage"
            End If
        
        End If
        
    ElseIf optSelect(2).Value = True Then    'VESSEL
    
        Call makeTopLevelSQL
        
        
        '.....Pawan 10/25/2001 Added as per Danial(Marty)....
        Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
        
        If dsCARGO_MANIFEST.RECORDCOUNT <= 0 Then
            statBar.SimpleText = "No cargos were in inventory for this vessel as of " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Else
            ' Reset lot in storage count
            iLotLeft = 0
           
            GrdData.Columns(2).Visible = False
            
            For iRec = 1 To dsCARGO_MANIFEST.RECORDCOUNT
                
                Call IntializeValues
                
                'Get the value directly from the database
                sExporterId = Val("" & dsCARGO_MANIFEST.fields("EXPORTER_ID").Value)
                sCustId = Val("" & dsCARGO_MANIFEST.fields("OWNER_ID").Value)
                sLrNum = ""
                sCommCode = Val("" & dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value)
                sBOL = "" & dsCARGO_MANIFEST.fields("CARGO_BOL").Value
                sMark = "" & dsCARGO_MANIFEST.fields("CARGO_MARK").Value
                sDateRcvd = "" & dsCARGO_MANIFEST.fields("DATE_RECEIVED").Value
                dQtyRcvd = Val("" & dsCARGO_MANIFEST.fields("QTY_RECEIVED").Value)
                lQtyDmg = 0 + Val("" & dsCARGO_MANIFEST.fields("QTY_DAMAGED").Value)
                sUnt1 = "" & dsCARGO_MANIFEST.fields("QTY1_UNIT").Value
                sUnt2 = Trim("" & dsCARGO_MANIFEST.fields("QTY2_UNIT").Value)
                If UCase(Trim$("" & dsCARGO_MANIFEST.fields("MANIFEST_STATUS").Value)) = "HOLD" Then sRH = "H"
                If UCase(Trim$("" & dsCARGO_MANIFEST.fields("MANIFEST_STATUS").Value)) = "RELEASED" Then sRH = "R"
                If sBOL = "B63325A" Then
                    sBOL = "B63325A"
                End If
                
                dQty1FromCM = Val("" & dsCARGO_MANIFEST.fields("QTY_EXPECTED").Value)
                dQty2FromCM = Val("" & dsCARGO_MANIFEST.fields("QTY2_EXPECTED").Value)
                dWeightFromCM = Val("" & dsCARGO_MANIFEST.fields("CARGO_WEIGHT").Value)
'                   End of first pass (raw uncalculated data)
                
                 
                 'Calculate QTY1, QTY1 Left (same as QTY1), and QTY Withdrawn
                SqlStmt = " SELECT SUM(QTY_CHANGE) QtyChge FROM CARGO_ACTIVITY WHERE LOT_NUM = '" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "'" _
                        & " AND DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
                Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                
                lWithdrwn = 0 + Val("" & dsCARGO_ACTIVITY.fields("QtyChge").Value)
                lLeft = Val("" & dsCARGO_MANIFEST.fields("QTY_RECEIVED").Value) - Val("" & dsCARGO_ACTIVITY.fields("QtyChge").Value)
                lQty1 = lLeft

                SqlStmt = "SELECT SUM(QTY_CHANGE) TDQtyChge FROM CARGO_ACTIVITY WHERE LOT_NUM='" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "'"
                Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                lTdyWDrwn = 0 + Val("" & dsCARGO_ACTIVITY.fields("TDQtyChge").Value)
'                   End of 2nd pass, calculations of QTY1 values
               
'                It has come to my attention that entire cargos can be transferred, leaving dQty1FromCM, dQty2FromCM, and dWeightFromCM
'                With a value of zero, causing the ratio calculations to go haywire; threfore I need to add this check to determine
'                An alternate way of finding original weights and QTY2's.  Note that, unlike the previous version of this program,
'                Rather than looking for bastardized cargo marks, I use the ORIGINAL_CONTAINER_NUM field to definitely determine
'                This data.
                If dQty1FromCM = 0 Then
                    FindOriginalValues (dsCARGO_MANIFEST.fields("LOT_NUM").Value)
                End If
               
               
                'Original Weight Calculation Starts
                dQty1ToWeightRatio = dWeightFromCM / dQty1FromCM
                dMyWtRcvd = dQty1ToWeightRatio * dQtyRcvd
'                    End of 3rd pass, calculation of original weight


                'Wt left calculation
                dWtLeft = dQty1ToWeightRatio * lQty1
'                    End of 4th pass, calculation of current weight
               
               
                'Qty2 left calculation
                dQty1ToQty2Ratio = dQty2FromCM / dQty1FromCM
                lQty2 = dQty1ToQty2Ratio * lQty1
'                    End of 5th pass, calculation of current qty2
                
                               
                'Pending calculation; and the hardest part
                SqlStmt = " SELECT SUM(QTY1) Qt1 FROM BNI_DUMMY_WITHDRAWAL  WHERE lr_num='" & Trim(txtLrNum) & "'" _
                        & " AND commodity_code='" & sCommCode & "' and owner_id='" & sCustId & "'" _
                        & " AND bol='" & sBOL & "' AND mark='" & sMark & "' and status is null" _
                        & " AND order_date <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"

                Set dsDUMMY_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                If dsDUMMY_ACTIVITY.RECORDCOUNT > 0 Then
                    If Not IsNull(dsDUMMY_ACTIVITY.fields("Qt1").Value) Then
                        dummyPend = dsDUMMY_ACTIVITY.fields("Qt1").Value
                    Else
                        dummyPend = 0
                    End If
                Else
                    dummyPend = 0
                End If
                
                'Also add on the qty of cargo shipped on a date later than the Cut Off
                SqlStmt = "select sum(QTY_CHANGE) Qt1 from cargo_activity where lot_num = '" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "' " _
                        & "and date_of_activity > TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                        & " and SERVICE_CODE != '6120'"
                Set dsLATTER_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                If dsLATTER_ACTIVITY.RECORDCOUNT > 0 Then
                    If Not IsNull(dsLATTER_ACTIVITY.fields("Qt1").Value) Then
                        dummyPend = dummyPend + dsLATTER_ACTIVITY.fields("Qt1").Value
                    End If
                End If
'                    End of 6th pass, Pending Calculation
                                                               
                If optInvOnly(1).Value = True And lLeft <= 0 Then
                    ' Wont't add this record to the data grid
                Else
                    
                    GrdData.AddItem sExporterId + Chr(9) + sCustId + Chr(9) + sLrNum + Chr(9) + sCommCode + Chr(9) + sBOL + Chr(9) + _
                                    sMark + Chr(9) + sDateRcvd + Chr(9) + CStr(dQtyRcvd) + Chr(9) + _
                                    CStr(Round(dMyWtRcvd, 0)) + Chr(9) + CStr(lQty1) + Chr(9) + sUnt1 + Chr(9) + CStr(Round(lQty2, 2)) + Chr(9) + _
                                    sUnt2 + Chr(9) + CStr(lQtyDmg) + Chr(9) + CStr(lWithdrwn) + Chr(9) + CStr(lTdyWDrwn) + Chr(9) + _
                                    CStr(lLeft) + Chr(9) + CStr(Round(dWtLeft, 0)) + Chr(9) + sRH + Chr(9) + CStr(dummyPend)
                    GrdData.Refresh
                    
                    RcvdTotalLine = RcvdTotalLine + dQtyRcvd
                    WithTotalLine = WithTotalLine + lWithdrwn
                    LeftTotalLine = LeftTotalLine + lLeft
                    PendTotalLine = PendTotalLine + dummyPend
                    WTLeftTotalLine = WTLeftTotalLine + dWtLeft
                    
                    If lLeft > 0 Then
                        iLotLeft = iLotLeft + 1
                    End If
                
                End If
                
                dsCARGO_MANIFEST.MoveNext
            Next iRec
        
            GrdData.AddItem Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + "TOTALS:" + Chr(9) + Chr(9) + CStr(RcvdTotalLine) + Chr(9) + Chr(9) + _
                            Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + CStr(WithTotalLine) + Chr(9) + Chr(9) + _
                            CStr(LeftTotalLine) + Chr(9) + CStr(Round(WTLeftTotalLine, 0)) + Chr(9) + Chr(9) + CStr(PendTotalLine)
            GrdData.Refresh
        
            If optInvOnly(0).Value = True Then
                statBar.SimpleText = "Totally " & dsCARGO_MANIFEST.RECORDCOUNT & " entries with " & iLotLeft & " entries still in storage"
            Else
                statBar.SimpleText = iLotLeft & " entries still in storage"
            End If
        
        End If
        
             
    ElseIf optSelect(3).Value = True Then    'COMMODITY
        
        Call makeTopLevelSQL
        
                 
        Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
        
        Dim Total As String
        Total = dsCARGO_MANIFEST.RECORDCOUNT
        
        If dsCARGO_MANIFEST.RECORDCOUNT <= 0 Then
            statBar.SimpleText = "No cargos were in inventory for this commodity as of " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Else
            ' Reset lot in storage count
            iLotLeft = 0
            
            GrdData.Columns(3).Visible = False
            
            For iRec = 1 To dsCARGO_MANIFEST.RECORDCOUNT
                
                Call IntializeValues
                                
                'Get the value directly from the database
                sExporterId = Val("" & dsCARGO_MANIFEST.fields("EXPORTER_ID").Value)
                sCustId = Val("" & dsCARGO_MANIFEST.fields("OWNER_ID").Value)
                sLrNum = Val("" & dsCARGO_MANIFEST.fields("LR_NUM").Value)
'                sCommCode = Val("" & dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value)
                sCommCode = ""
                sBOL = "" & dsCARGO_MANIFEST.fields("CARGO_BOL").Value
                sMark = "" & dsCARGO_MANIFEST.fields("CARGO_MARK").Value
                sDateRcvd = "" & dsCARGO_MANIFEST.fields("DATE_RECEIVED").Value
                dQtyRcvd = Val("" & dsCARGO_MANIFEST.fields("QTY_RECEIVED").Value)
                lQtyDmg = 0 + Val("" & dsCARGO_MANIFEST.fields("QTY_DAMAGED").Value)
                sUnt1 = "" & dsCARGO_MANIFEST.fields("QTY1_UNIT").Value
                sUnt2 = Trim("" & dsCARGO_MANIFEST.fields("QTY2_UNIT").Value)
                If UCase(Trim$("" & dsCARGO_MANIFEST.fields("MANIFEST_STATUS").Value)) = "HOLD" Then sRH = "H"
                If UCase(Trim$("" & dsCARGO_MANIFEST.fields("MANIFEST_STATUS").Value)) = "RELEASED" Then sRH = "R"
                If sBOL = "B63325A" Then
                    sBOL = "B63325A"
                End If
                
                dQty1FromCM = Val("" & dsCARGO_MANIFEST.fields("QTY_EXPECTED").Value)
                dQty2FromCM = Val("" & dsCARGO_MANIFEST.fields("QTY2_EXPECTED").Value)
                dWeightFromCM = Val("" & dsCARGO_MANIFEST.fields("CARGO_WEIGHT").Value)
'                   End of first pass (raw uncalculated data)
                
                
                 'Calculate QTY1, QTY1 Left (same as QTY1), and QTY Withdrawn
                SqlStmt = " SELECT SUM(QTY_CHANGE) QtyChge FROM CARGO_ACTIVITY WHERE LOT_NUM = '" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "'" _
                        & " AND DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
                Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                
                lWithdrwn = 0 + Val("" & dsCARGO_ACTIVITY.fields("QtyChge").Value)
                lLeft = Val("" & dsCARGO_MANIFEST.fields("QTY_RECEIVED").Value) - Val("" & dsCARGO_ACTIVITY.fields("QtyChge").Value)
                lQty1 = lLeft

                SqlStmt = "SELECT SUM(QTY_CHANGE) TDQtyChge FROM CARGO_ACTIVITY WHERE LOT_NUM='" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "'"
                Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                lTdyWDrwn = 0 + Val("" & dsCARGO_ACTIVITY.fields("TDQtyChge").Value)
'                   End of 2nd pass, calculations of QTY1 values
                                           
'                It has come to my attention that entire cargos can be transferred, leaving dQty1FromCM, dQty2FromCM, and dWeightFromCM
'                With a value of zero, causing the ratio calculations to go haywire; threfore I need to add this check to determine
'                An alternate way of finding original weights and QTY2's.  Note that, unlike the previous version of this program,
'                Rather than looking for bastardized cargo marks, I use the ORIGINAL_CONTAINER_NUM field to definitely determine
'                This data.
                If dQty1FromCM = 0 Then
                    FindOriginalValues (dsCARGO_MANIFEST.fields("LOT_NUM").Value)
                End If
                                           
                                           
                'Original Weight Calculation Starts
                dQty1ToWeightRatio = dWeightFromCM / dQty1FromCM
                dMyWtRcvd = dQty1ToWeightRatio * dQtyRcvd
'                    End of 3rd pass, calculation of original weight


                'Wt left calculation
                dWtLeft = dQty1ToWeightRatio * lQty1
'                    End of 4th pass, calculation of current weight
               
               
                'Qty2 left calculation
                dQty1ToQty2Ratio = dQty2FromCM / dQty1FromCM
                lQty2 = dQty1ToQty2Ratio * lQty1
'                    End of 5th pass, calculation of current qty2
                
                               
                'Pending calculation; and the hardest part
                SqlStmt = " SELECT SUM(QTY1) Qt1 FROM BNI_DUMMY_WITHDRAWAL  WHERE lr_num='" & sLrNum & "'" _
                        & " AND commodity_code='" & Trim(txtCommCode) & "' and owner_id='" & sCustId & "'" _
                        & " AND bol='" & sBOL & "' AND mark='" & sMark & "' and status is null" _
                        & " AND order_date <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"

                Set dsDUMMY_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                If dsDUMMY_ACTIVITY.RECORDCOUNT > 0 Then
                    If Not IsNull(dsDUMMY_ACTIVITY.fields("Qt1").Value) Then
                        dummyPend = dsDUMMY_ACTIVITY.fields("Qt1").Value
                    Else
                        dummyPend = 0
                    End If
                Else
                    dummyPend = 0
                End If
                
                'Also add on the qty of cargo shipped on a date later than the Cut Off
                SqlStmt = "select sum(QTY_CHANGE) Qt1 from cargo_activity where lot_num = '" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "' " _
                        & "and date_of_activity > TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                        & " and SERVICE_CODE != '6120'"
                Set dsLATTER_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                If dsLATTER_ACTIVITY.RECORDCOUNT > 0 Then
                    If Not IsNull(dsLATTER_ACTIVITY.fields("Qt1").Value) Then
                        dummyPend = dummyPend + dsLATTER_ACTIVITY.fields("Qt1").Value
                    End If
                End If
'                    End of 6th pass, Pending Calculation
                                                           
                If optInvOnly(1).Value = True And lLeft <= 0 Then
                    ' Wont't add this record to the data grid
                Else
                    GrdData.AddItem sExporterId + Chr(9) + sCustId + Chr(9) + sLrNum + Chr(9) + sCommCode + Chr(9) + sBOL + Chr(9) + _
                                    sMark + Chr(9) + sDateRcvd + Chr(9) + CStr(dQtyRcvd) + Chr(9) + _
                                    CStr(Round(dMyWtRcvd, 0)) + Chr(9) + CStr(lQty1) + Chr(9) + sUnt1 + Chr(9) + CStr(Round(lQty2, 2)) + Chr(9) + _
                                    sUnt2 + Chr(9) + CStr(lQtyDmg) + Chr(9) + CStr(lWithdrwn) + Chr(9) + CStr(lTdyWDrwn) + Chr(9) + _
                                    CStr(lLeft) + Chr(9) + CStr(Round(dWtLeft, 0)) + Chr(9) + sRH + Chr(9) + CStr(dummyPend)
                    GrdData.Refresh
                    
                    RcvdTotalLine = RcvdTotalLine + dQtyRcvd
                    WithTotalLine = WithTotalLine + lWithdrwn
                    LeftTotalLine = LeftTotalLine + lLeft
                    PendTotalLine = PendTotalLine + dummyPend
                    WTLeftTotalLine = WTLeftTotalLine + dWtLeft
                    
                    If lLeft > 0 Then
                        iLotLeft = iLotLeft + 1
                    End If
                End If
                
                dsCARGO_MANIFEST.MoveNext
            Next iRec
        
            GrdData.AddItem Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + "TOTALS:" + Chr(9) + Chr(9) + CStr(RcvdTotalLine) + Chr(9) + Chr(9) + _
                            Chr(9) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + CStr(WithTotalLine) + Chr(9) + Chr(9) + _
                            CStr(LeftTotalLine) + Chr(9) + CStr(Round(WTLeftTotalLine, 0)) + Chr(9) + Chr(9) + CStr(PendTotalLine)
            GrdData.Refresh
        
            If optInvOnly(0).Value = True Then
                statBar.SimpleText = "Totally " & dsCARGO_MANIFEST.RECORDCOUNT & " entries with " & iLotLeft & " entries still in storage"
            Else
                statBar.SimpleText = iLotLeft & " entries still in storage"
            End If
        
        End If
            
    Else ''''''''''''''''''''EXPORTER/SUPPLIER ONLY SEARCH'''''''''''''''''''''''''''''''''''''''''''''
        
        Call makeTopLevelSQL
        

        Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
        
        If dsCARGO_MANIFEST.RECORDCOUNT <= 0 Then
            statBar.SimpleText = "No cargos were in inventory for this customer as of " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Else
            ' Reset lot in storage count
            iLotLeft = 0
            
            GrdData.Columns(0).Visible = False
            
            For iRec = 1 To dsCARGO_MANIFEST.RECORDCOUNT
                
                Call IntializeValues
                              
                'Get the value directly from the database
                sExporterId = ""
                sCustId = Val("" & dsCARGO_MANIFEST.fields("OWNER_ID").Value)
                sLrNum = Val("" & dsCARGO_MANIFEST.fields("LR_NUM").Value)
                sCommCode = Val("" & dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value)
                sBOL = "" & dsCARGO_MANIFEST.fields("CARGO_BOL").Value
                sMark = "" & dsCARGO_MANIFEST.fields("CARGO_MARK").Value
                sDateRcvd = "" & dsCARGO_MANIFEST.fields("DATE_RECEIVED").Value
                dQtyRcvd = Val("" & dsCARGO_MANIFEST.fields("QTY_RECEIVED").Value)
                lQtyDmg = 0 + Val("" & dsCARGO_MANIFEST.fields("QTY_DAMAGED").Value)
                sUnt1 = "" & dsCARGO_MANIFEST.fields("QTY1_UNIT").Value
                sUnt2 = Trim("" & dsCARGO_MANIFEST.fields("QTY2_UNIT").Value)
                If UCase(Trim$("" & dsCARGO_MANIFEST.fields("MANIFEST_STATUS").Value)) = "HOLD" Then sRH = "H"
                If UCase(Trim$("" & dsCARGO_MANIFEST.fields("MANIFEST_STATUS").Value)) = "RELEASED" Then sRH = "R"
                If sBOL = "B63325A" Then
                    sBOL = "B63325A"
                End If
                
                dQty1FromCM = Val("" & dsCARGO_MANIFEST.fields("QTY_EXPECTED").Value)
                dQty2FromCM = Val("" & dsCARGO_MANIFEST.fields("QTY2_EXPECTED").Value)
                dWeightFromCM = Val("" & dsCARGO_MANIFEST.fields("CARGO_WEIGHT").Value)
'                   End of first pass (raw uncalculated data)





                 'Calculate QTY1, QTY1 Left (same as QTY1), and QTY Withdrawn
                SqlStmt = " SELECT SUM(QTY_CHANGE) QtyChge FROM CARGO_ACTIVITY WHERE LOT_NUM = '" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "'" _
                        & " AND DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
                Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                
                lWithdrwn = 0 + Val("" & dsCARGO_ACTIVITY.fields("QtyChge").Value)
                lLeft = Val("" & dsCARGO_MANIFEST.fields("QTY_RECEIVED").Value) - Val("" & dsCARGO_ACTIVITY.fields("QtyChge").Value)
                lQty1 = lLeft

                SqlStmt = "SELECT SUM(QTY_CHANGE) TDQtyChge FROM CARGO_ACTIVITY WHERE LOT_NUM='" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "'"
                Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                lTdyWDrwn = 0 + Val("" & dsCARGO_ACTIVITY.fields("TDQtyChge").Value)
'                   End of 2nd pass, calculations of QTY1 values
                                           
'                It has come to my attention that entire cargos can be transferred, leaving dQty1FromCM, dQty2FromCM, and dWeightFromCM
'                With a value of zero, causing the ratio calculations to go haywire; threfore I need to add this check to determine
'                An alternate way of finding original weights and QTY2's.  Note that, unlike the previous version of this program,
'                Rather than looking for bastardized cargo marks, I use the ORIGINAL_CONTAINER_NUM field to definitely determine
'                This data.
                If dQty1FromCM = 0 Then
                    FindOriginalValues (dsCARGO_MANIFEST.fields("LOT_NUM").Value)
                End If
                                           
                                           
                'Original Weight Calculation Starts
                dQty1ToWeightRatio = dWeightFromCM / dQty1FromCM
                dMyWtRcvd = dQty1ToWeightRatio * dQtyRcvd
'                    End of 3rd pass, calculation of original weight
                                           
                'Wt left calculation
                dWtLeft = dQty1ToWeightRatio * lQty1
'                    End of 4th pass, calculation of current weight
               
               
                'Qty2 left calculation
                dQty1ToQty2Ratio = dQty2FromCM / dQty1FromCM
                lQty2 = dQty1ToQty2Ratio * lQty1
'                    End of 5th pass, calculation of current qty2
                
                               
                'Pending calculation; and the hardest part
                SqlStmt = " SELECT SUM(QTY1) Qt1 FROM BNI_DUMMY_WITHDRAWAL  WHERE lr_num='" & sLrNum & "'" _
                        & " AND commodity_code='" & sCommCode & "' and owner_id='" & Trim(txtCustId) & "'" _
                        & " AND bol='" & sBOL & "' AND mark='" & sMark & "' and status is null" _
                        & " AND order_date <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"

                Set dsDUMMY_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                If dsDUMMY_ACTIVITY.RECORDCOUNT > 0 Then
                    If Not IsNull(dsDUMMY_ACTIVITY.fields("Qt1").Value) Then
                        dummyPend = dsDUMMY_ACTIVITY.fields("Qt1").Value
                    Else
                        dummyPend = 0
                    End If
                Else
                    dummyPend = 0
                End If
                
                'Also add on the qty of cargo shipped on a date later than the Cut Off
                SqlStmt = "select sum(QTY_CHANGE) Qt1 from cargo_activity where lot_num = '" & dsCARGO_MANIFEST.fields("LOT_NUM").Value & "' " _
                        & "and date_of_activity > TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                        & " and SERVICE_CODE != '6120'"
                Set dsLATTER_ACTIVITY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
                If dsLATTER_ACTIVITY.RECORDCOUNT > 0 Then
                    If Not IsNull(dsLATTER_ACTIVITY.fields("Qt1").Value) Then
                        dummyPend = dummyPend + dsLATTER_ACTIVITY.fields("Qt1").Value
                    End If
                End If
'                    End of 6th pass, Pending Calculation
                                           
                                           
                                           
                               
                If optInvOnly(1).Value = True And lLeft <= 0 Then
                    ' Wont't add this record to the data grid if it is Show Current Inventory Only
                Else
                    GrdData.AddItem sExporterId + Chr(9) + sCustId + Chr(9) + sLrNum + Chr(9) + sCommCode + Chr(9) + sBOL + Chr(9) + _
                                    sMark + Chr(9) + sDateRcvd + Chr(9) + CStr(dQtyRcvd) + Chr(9) + _
                                    CStr(Round(dMyWtRcvd, 0)) + Chr(9) + CStr(lQty1) + Chr(9) + sUnt1 + Chr(9) + CStr(Round(lQty2, 2)) + Chr(9) + _
                                    sUnt2 + Chr(9) + CStr(lQtyDmg) + Chr(9) + CStr(lWithdrwn) + Chr(9) + CStr(lTdyWDrwn) + Chr(9) + _
                                    CStr(lLeft) + Chr(9) + CStr(Round(dWtLeft, 0)) + Chr(9) + sRH + Chr(9) + CStr(dummyPend)
                    GrdData.Refresh
                    
                    If lLeft > 0 Then
                        iLotLeft = iLotLeft + 1
                    End If
                
                End If
                
                dsCARGO_MANIFEST.MoveNext
            Next iRec
        
            If optInvOnly(0).Value = True Then
                statBar.SimpleText = "Totally " & dsCARGO_MANIFEST.RECORDCOUNT & " entries with " & iLotLeft & " entries still in storage"
            Else
                statBar.SimpleText = iLotLeft & " entries still in storage"
            End If
        
        End If
    End If

End Sub

Private Sub cmdPrint_Click()
    Dim dsCTTotal As Object
    Dim Total As String
    Dim bFirst As Boolean
    Dim bFirst2 As Boolean
    Dim iGrp As Long
    Dim iGrp1 As Long
    Dim iGrp2 As Long
    Dim iGrp21 As Long
       
    Dim iNo As Integer
    Dim iSNo As Integer
    Dim lTQtyRcvd As Double
    Dim dTWtRcvd As Double
    Dim lTQty1 As Double
    Dim lTQty2 As Double
    Dim lTQtyWDrwn As Double
    Dim lTLeft As Double
    Dim dTWtLeft As Double
    Dim lQtyDmg As Double
    Dim pend As Double
    
    Dim lSTQtyRcvd As Double
    Dim dSTWtRcvd As Double
    Dim lSTQty1 As Double
    Dim lSTQty2 As Double
    Dim lSTQtyWDrwn As Double
    Dim lSTLeft As Double
    Dim dSTWtLeft As Double
    Dim lSQtyDmg As Double
    Dim spend As Double
    Dim bIs651 As Boolean
    Dim sWeightLabel  As String
    
    iGrp = 0
    iGrp1 = 0
    iGrp2 = 0
    iGrp21 = 0
    
    iLine = 8
    iPage = 1
    sWeightLabel = "WT Left"
    bFirst = True
    bFirst2 = True
    iGrp1 = iGrp
    iGrp21 = iGrp2
    Printer.Orientation = 2
    Printer.FontBold = False
    Printer.FontSize = 8
    Printer.Print Tab(3); "Port of Wilmington, Printed On " & Format(Now, "MM/DD/YYYY"); Tab(190); "Page No:  " & iPage
    Printer.Print
    
    If optSelect(0).Value = True Then
        sum1 = 0
        sum2 = 0
        sum3 = 0
        sum4 = 0
        sum5 = 0
        sum6 = 0
        sum7 = 0
        sum8 = 0
        sum9 = 0
        Printer.FontSize = 12
        Printer.Print Tab(35); "INVENTORY ON : " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Printer.FontSize = 8
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        
        Printer.Print Tab(3); "Comm"; Tab(17); "BOL"; Tab(40); "Mark"; Tab(80); "Dt Rcvd"; Tab(92); "Qty Rcvd"; Tab(105); _
                      "QTY1"; Tab(115); "Unt1"; Tab(125); "Qty2"; Tab(135); "Unt2"; Tab(143); _
                      "Dmgd"; Tab(152); "Withdrawn "; Tab(165); "Left"; Tab(172); "Pending"; Tab(183); sWeightLabel; Tab(199); "R/H"
        Printer.FontBold = True
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        Printer.FontBold = False
        Printer.Print ""
        GrdData.MoveFirst
        iGrp = Val(GrdData.Columns(1).Value)
        
        For iRec = 0 To GrdData.Rows - 2
            If Val(GrdData.Columns(16).Value) > 0 Then
                If iGrp <> GrdData.Columns(1).Value And bFirst = False Then
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); iNo & "  Record(s)"; Tab(94); lTQtyRcvd; _
                                Tab(104); lTQty1; Tab(124); Round(lTQty2, 2); Tab(144); lQtyDmg; Tab(154); lTQtyWDrwn; _
                                Tab(164); lTLeft; Tab(174); pend; Tab(182); Format$(dTWtLeft, "0.00##")
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                    iGrp = GrdData.Columns(1).Value
                    
                    iNo = 0: lTQtyRcvd = 0: dTWtRcvd = 0: lTQty1 = 0: lTQty2 = 0
                    lQtyDmg = 0: lTQtyWDrwn = 0: lTLeft = 0: dTWtLeft = 0: pend = 0
                    
                    '......
                    If iLine = 56 Then Call PageHeader
                    '-------
                    Printer.FontBold = True
                    iLine = iLine + 2
                    Printer.Print Tab(3); VesselName(iGrp)
                    Printer.Print ""
                    Printer.FontBold = False
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); GrdData.Columns(2).Value; Tab(15); GrdData.Columns(3).Value; _
                                  Tab(35); GrdData.Columns(4).Value; Tab(80); GrdData.Columns(5).Value; _
                                  Tab(95); GrdData.Columns(6).Value; _
                                  Tab(105); GrdData.Columns(8).Value; Tab(115); GrdData.Columns(9).Value; _
                                  Tab(125); Round(GrdData.Columns(10).Value, 2); Tab(135); GrdData.Columns(11).Value; _
                                  Tab(145); GrdData.Columns(12).Value; Tab(155); GrdData.Columns(13).Value; _
                                  Tab(165); GrdData.Columns(15).Value; Tab(175); GrdData.Columns(18).Value; _
                                  Tab(182); Format$(GrdData.Columns(16).Value, "0.00##"); Tab(200); GrdData.Columns(17).Value
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                
                    iNo = 1
                    lTQtyRcvd = Val(GrdData.Columns(6).Value)
                    dTWtRcvd = Val(GrdData.Columns(7).Value)
                    lTQty1 = Val(GrdData.Columns(8).Value)
                    lTQty2 = Round(Val(GrdData.Columns(10).Value), 2)
                    lQtyDmg = Val(GrdData.Columns(12).Value)
                    lTQtyWDrwn = Val(GrdData.Columns(13).Value)
                    lTLeft = Val(GrdData.Columns(15).Value)
                    dTWtLeft = Val(GrdData.Columns(16).Value)
                    pend = Val(GrdData.Columns(18).Value)
                    
                    sum1 = sum1 + Val(GrdData.Columns(6).Value)
                    sum2 = sum2 + Val(GrdData.Columns(11).Value)
                    sum6 = sum6 + Val(GrdData.Columns(13).Value)
                    sum7 = sum7 + Val(GrdData.Columns(15).Value)
                    sum8 = sum8 + Val(GrdData.Columns(16).Value)
                    sum9 = sum9 + Val(GrdData.Columns(18).Value)
                    sum5 = sum5 + Val(GrdData.Columns(12).Value)
                Else
                    If bFirst = True Then
                        '........
                        If iLine = 56 Then Call PageHeader
                        '---------
                        Printer.FontBold = True
                        iLine = iLine + 2
                        Printer.Print Tab(3); VesselName(GrdData.Columns(1).Value)
                        Printer.Print ""
                        Printer.FontBold = False
                        bFirst = False
                    End If
                    
                    iNo = iNo + 1
                    lTQtyRcvd = lTQtyRcvd + Val(GrdData.Columns(6).Value)
                    dTWtRcvd = dTWtRcvd + Val(GrdData.Columns(7).Value)
                    lTQty1 = lTQty1 + Val(GrdData.Columns(8).Value)
                    lTQty2 = Round(lTQty2 + Val(GrdData.Columns(10).Value), 2)
                    lQtyDmg = lQtyDmg + Val(GrdData.Columns(12).Value)
                    lTQtyWDrwn = lTQtyWDrwn + Val(GrdData.Columns(13).Value)
                    lTLeft = lTLeft + Val(GrdData.Columns(15).Value)
                    dTWtLeft = dTWtLeft + Val(GrdData.Columns(16).Value)
                    pend = pend + Val(GrdData.Columns(18).Value)
                    
                    sum1 = sum1 + Val(GrdData.Columns(6).Value)
                    sum2 = sum2 + Val(GrdData.Columns(11).Value)
                    sum6 = sum6 + Val(GrdData.Columns(13).Value)
                    sum7 = sum7 + Val(GrdData.Columns(15).Value)
                    sum8 = sum8 + Val(GrdData.Columns(16).Value)
                    sum9 = sum9 + Val(GrdData.Columns(18).Value)
                    sum5 = sum5 + Val(GrdData.Columns(12).Value)
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); GrdData.Columns(2).Value; Tab(15); GrdData.Columns(3).Value; _
                                  Tab(35); GrdData.Columns(4).Value; Tab(80); GrdData.Columns(5).Value; _
                                  Tab(95); GrdData.Columns(6).Value; _
                                  Tab(105); GrdData.Columns(8).Value; Tab(115); GrdData.Columns(9).Value; _
                                  Tab(125); Round(GrdData.Columns(10).Value, 2); Tab(135); GrdData.Columns(11).Value; _
                                  Tab(145); GrdData.Columns(12).Value; Tab(155); GrdData.Columns(13).Value; _
                                  Tab(165); GrdData.Columns(15).Value; Tab(175); GrdData.Columns(18).Value; _
                                  Tab(182); Format$(GrdData.Columns(16).Value, "0.00##"); Tab(200); GrdData.Columns(17).Value
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                    
                End If
            End If
            GrdData.MoveNext
        Next iRec
               
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        
        Printer.Print Tab(3); iNo & "  Record(s)"; Tab(94); lTQtyRcvd; _
                  Tab(104); lTQty1; Tab(124); Round(lTQty2, 2); Tab(144); lQtyDmg; Tab(154); lTQtyWDrwn; _
                  Tab(164); lTLeft; Tab(174); pend; Tab(182); Format$(dTWtLeft, "0.00##")
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        '-------------pawan
        Printer.NewPage
        Printer.Orientation = 2
        iPage = iPage + 1
        Printer.FontBold = False
        Printer.Print Tab(3); "Port of Wilmington, Printed On " & Format(Now, "MM/DD/YYYY"); Tab(190); "Page No:  " & iPage
        Printer.Print
        Printer.FontSize = 12
        Printer.Print Tab(20); "INVENTORY SUMMARY AS OF : " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Printer.FontSize = 8
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        Printer.Print
        Printer.Print
        Printer.Print
        Printer.FontSize = 12
        Printer.FontBold = True
        Printer.Print Tab(7); "INVENTORY  GRAND  TOTAL  : "
        
        Printer.Print ""
        Printer.Print ""
'        Printer.Print Tab(10); "Qty Rcvd"; Tab(30); "Withdrawn "; Tab(50); "Left"; Tab(70); "Pending"; Tab(90); sWeightLabel; Tab(110); "QTY DAMAGED"
'        Printer.FontSize = 8
'        Printer.FontBold = False
'        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
'        Printer.FontSize = 12
'        Printer.FontBold = True
'        Printer.Print Tab(10); sum1; Tab(30); sum6; Tab(50); sum7; Tab(70); sum9; Tab(90); Format$(sum8, "0.00##"); Tab(110); sum5
         Printer.Print Tab(10); "Qty Rcvd"; Tab(30); "Withdrawn "; Tab(50); "Left"; Tab(60); "Pending"; Tab(70); "QTY2"; Tab(85); sWeightLabel; Tab(110); "QTY DAMAGED"
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        Printer.FontSize = 12
        Printer.FontBold = True
        Printer.Print Tab(10); sum1; Tab(30); sum6; Tab(50); sum7; Tab(60); sum9; Tab(70); sum2; Tab(85); Format$(sum8, "0.00##"); Tab(110); sum5
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        '-----------Pawan
         
    ElseIf optSelect(1).Value = True Then       'Customer
        
        'Modifications for 651
        'If (Trim$(txtCustId.Text) = "651") Then sWeightLabel = "MBF Left"
        
        sum1 = 0
        sum2 = 0
        sum3 = 0
        sum4 = 0
        sum5 = 0
        sum6 = 0
        sum7 = 0
        sum8 = 0
        sum9 = 0
        Printer.FontSize = 12
        Printer.Print Tab(35); "INVENTORY FOR CUSTOMER  " & Trim(txtCustName) & "  As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Printer.FontSize = 8
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(3); "Comm"; Tab(17); "BOL"; Tab(40); "Mark"; Tab(80); "Dt Rcvd"; Tab(92); "Qty Rcvd"; Tab(105); _
                      "QTY1"; Tab(115); "Unt1"; Tab(125); "Qty2"; Tab(135); "Unt2"; Tab(143); _
                      "Dmgd"; Tab(152); "Withdrawn "; Tab(165); "Left"; Tab(172); "Pending"; Tab(183); sWeightLabel; Tab(199); "R/H"
        Printer.FontBold = True
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        Printer.FontBold = False
        Printer.Print ""
        GrdData.MoveFirst
        
        For iRec = 0 To GrdData.Rows - 2
            If Val(GrdData.Columns(16).Value) > 0 Then
                iGrp = Val(GrdData.Columns(2).Value)
                If iGrp <> iGrp1 And bFirst = False Then
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); iNo & "  Record(s)"; Tab(94); lTQtyRcvd; _
                                  Tab(104); lTQty1; Tab(124); Round(lTQty2, 2); Tab(144); lQtyDmg; Tab(154); lTQtyWDrwn; _
                                  Tab(164); lTLeft; Tab(174); pend; Tab(182); Format$(dTWtLeft, "0.00##")
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                    
                    iNo = 0: lTQtyRcvd = 0: dTWtRcvd = 0: lTQty1 = 0: lTQty2 = 0
                    lQtyDmg = 0: lTQtyWDrwn = 0: lTLeft = 0: dTWtLeft = 0: pend = 0
                    
                    '.....
                    If iLine = 56 Then Call PageHeader
                    '-------
                    Printer.FontBold = True
                    iLine = iLine + 2
                    Printer.Print Tab(3); VesselName(iGrp)
                    Printer.Print ""
                    Printer.FontBold = False
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); GrdData.Columns(3).Value; Tab(15); GrdData.Columns(4).Value; _
                                  Tab(35); GrdData.Columns(5).Value; Tab(80); GrdData.Columns(6).Value; _
                                  Tab(95); GrdData.Columns(7).Value; _
                                  Tab(105); GrdData.Columns(9).Value; Tab(115); GrdData.Columns(10).Value; _
                                  Tab(125); Round(GrdData.Columns(11).Value, 2); Tab(135); GrdData.Columns(12).Value; _
                                  Tab(145); GrdData.Columns(13).Value; Tab(155); GrdData.Columns(14).Value; _
                                  Tab(165); GrdData.Columns(16).Value; Tab(175); GrdData.Columns(19).Value; _
                                  Tab(182); Format$(GrdData.Columns(17).Value, "0.00##"); Tab(200); GrdData.Columns(18).Value
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                
                    iNo = 1
                    lTQtyRcvd = Val(GrdData.Columns(7).Value)
                    dTWtRcvd = Val(GrdData.Columns(8).Value)
                    lTQty1 = Val(GrdData.Columns(9).Value)
                    lTQty2 = Round(Val(GrdData.Columns(11).Value), 2)
                    lQtyDmg = Val(GrdData.Columns(13).Value)
                    lTQtyWDrwn = Val(GrdData.Columns(14).Value)
                    lTLeft = Val(GrdData.Columns(16).Value)
                    dTWtLeft = Val(GrdData.Columns(17).Value)
                    pend = pend + Val(GrdData.Columns(19).Value)
                    
                    sum1 = sum1 + Val(GrdData.Columns(7).Value)
                    sum2 = sum2 + Val(GrdData.Columns(11).Value)
                    sum6 = sum6 + Val(GrdData.Columns(14).Value)
                    sum7 = sum7 + Val(GrdData.Columns(16).Value)
                    sum8 = sum8 + Val(GrdData.Columns(17).Value)
                    sum9 = sum9 + Val(GrdData.Columns(19).Value)
                    sum5 = sum5 + Val(GrdData.Columns(13).Value)
                    iGrp1 = iGrp
                Else
                    If bFirst = True Then
                        '.........
                        If iLine = 56 Then Call PageHeader
                        '--------
                        Printer.FontBold = True
                        iLine = iLine + 2
                        Printer.Print Tab(3); VesselName(Val(GrdData.Columns(2).Value))
                        Printer.Print ""
                        Printer.FontBold = False
                        bFirst = False
                    End If
                    iGrp1 = iGrp
                    iNo = iNo + 1
                    lTQtyRcvd = lTQtyRcvd + Val(GrdData.Columns(7).Value)
                    dTWtRcvd = dTWtRcvd + Val(GrdData.Columns(8).Value)
                    lTQty1 = lTQty1 + Val(GrdData.Columns(9).Value)
                    lTQty2 = Round(lTQty2 + Val(GrdData.Columns(11).Value), 2)
                    lQtyDmg = lQtyDmg + Val(GrdData.Columns(13).Value)
                    lTQtyWDrwn = lTQtyWDrwn + Val(GrdData.Columns(14).Value)
                    lTLeft = lTLeft + Val(GrdData.Columns(16).Value)
                    dTWtLeft = dTWtLeft + Val(GrdData.Columns(17).Value)
                    pend = pend + Val(GrdData.Columns(19).Value)
                    
                    sum1 = sum1 + Val(GrdData.Columns(7).Value)
                    sum2 = sum2 + Val(GrdData.Columns(11).Value)
                    sum6 = sum6 + Val(GrdData.Columns(14).Value)
                    sum7 = sum7 + Val(GrdData.Columns(16).Value)
                    sum8 = sum8 + Val(GrdData.Columns(17).Value)
                    sum9 = sum9 + Val(GrdData.Columns(19).Value)
                    sum5 = sum5 + Val(GrdData.Columns(13).Value)
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); GrdData.Columns(3).Value; Tab(15); GrdData.Columns(4).Value; _
                                  Tab(35); GrdData.Columns(5).Value; Tab(80); GrdData.Columns(6).Value; _
                                  Tab(95); GrdData.Columns(7).Value; _
                                  Tab(105); GrdData.Columns(9).Value; Tab(115); GrdData.Columns(10).Value; _
                                  Tab(125); GrdData.Columns(11).Value; Tab(135); GrdData.Columns(12).Value; _
                                  Tab(145); GrdData.Columns(13).Value; Tab(155); GrdData.Columns(14).Value; _
                                  Tab(165); GrdData.Columns(16).Value; Tab(175); GrdData.Columns(19).Value; _
                                  Tab(182); Format$(GrdData.Columns(17).Value, "0.00##"); _
                                  Tab(200); GrdData.Columns(18).Value
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                    
                End If
            End If
            GrdData.MoveNext
        Next iRec
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
                    
        Printer.Print Tab(3); iNo & "  Record(s)"; Tab(94); lTQtyRcvd; Tab(104); _
                  lTQty1; Tab(124); Round(lTQty2, 2); Tab(144); lQtyDmg; Tab(154); lTQtyWDrwn; _
                  Tab(164); lTLeft; Tab(174); pend; Tab(182); Format$(dTWtLeft, "0.00##")
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        '----------Pawan
        Printer.NewPage
    
        Printer.Orientation = 2
        iPage = iPage + 1
        Printer.FontBold = False
        Printer.Print Tab(3); "Port of Wilmington, Printed On " & Format(Now, "MM/DD/YYYY"); Tab(190); "Page No:  " & iPage
        Printer.Print
   
        Printer.FontSize = 12
    
        Printer.Print Tab(20); "INVENTORY SUMMARY FOR CUSTOMER " & Trim(txtCustName) & " As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
    
        Printer.FontSize = 8
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
    
        Printer.Print
                
        Printer.Print
        Printer.Print
        Printer.FontSize = 12
        Printer.FontBold = True
        Printer.Print Tab(7); "CUSTOMER  GRAND  TOTAL  : "
        'diorelle
        Printer.Print ""
        Printer.Print ""
'        Printer.Print Tab(10); "Qty Rcvd"; Tab(30); "Withdrawn "; Tab(50); "Left"; Tab(60); "Pending"; Tab(80); "MBF"; Tab(100); sWeightLabel; Tab(110); "QTY DAMAGED"
'        Printer.FontSize = 8
'        Printer.FontBold = False
'        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
'        Printer.FontSize = 12
'        Printer.FontBold = True
'        Printer.Print Tab(10); sum1; Tab(30); sum6; Tab(50); sum7; Tab(60); sum9; Tab(80); sum2; Tab(100); Format$(sum8, "0.00##"); Tab(110); sum5
         Printer.Print Tab(10); "Qty Rcvd"; Tab(30); "Withdrawn "; Tab(50); "Left"; Tab(60); "Pending"; Tab(70); "QTY2"; Tab(85); sWeightLabel; Tab(110); "QTY DAMAGED"
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        Printer.FontSize = 12
        Printer.FontBold = True
        Printer.Print Tab(10); sum1; Tab(30); sum6; Tab(50); sum7; Tab(60); sum9; Tab(70); Format$(sum2, "0.00##"); Tab(85); Format$(sum8, "0.00##"); Tab(110); sum5
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        '-----------Pawan
   
    ElseIf optSelect(2).Value = True Then        'VESSEL
        'Modifications for 651
        'If (Trim$(txtLrNum.Text) = "3") Then sWeightLabel = "MBF Left"
        
        sum1 = 0
        sum2 = 0
        sum3 = 0
        sum4 = 0
        sum5 = 0
        sum6 = 0
        sum7 = 0
        sum8 = 0
        sum9 = 0
        Printer.FontSize = 12
        Printer.Print Tab(35); "INVENTORY FOR VESSEL  " & Trim(txtVessel) & "  As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Printer.FontSize = 8
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(3); "Comm"; Tab(17); "BOL"; Tab(40); "Mark"; Tab(80); "Dt Rcvd"; Tab(92); "Qty Rcvd"; Tab(105); _
                      "QTY1"; Tab(115); "Unt1"; Tab(125); "Qty2"; Tab(135); "Unt2"; Tab(143); _
                      "Dmgd"; Tab(152); "Withdrawn "; Tab(165); "Left"; Tab(172); "Pending"; Tab(183); sWeightLabel; Tab(199); "R/H"
        Printer.FontBold = True
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        Printer.FontBold = False
        Printer.Print ""
        
        GrdData.MoveFirst
        
        For iRec = 0 To GrdData.Rows - 2
            If Val(GrdData.Columns(16).Value) > 0 Then
                iGrp = GrdData.Columns(1).Value
                If iGrp <> iGrp1 And bFirst = False Then
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); iNo & "  Record(s)"; Tab(94); lTQtyRcvd; _
                                Tab(104); lTQty1; Tab(124); Round(lTQty2, 2); Tab(144); lQtyDmg; Tab(154); lTQtyWDrwn; _
                                Tab(164); lTLeft; Tab(174); pend; Tab(182); Format$(dTWtLeft, "0.00##")
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                
                    iNo = 0: lTQtyRcvd = 0: dTWtRcvd = 0: lTQty1 = 0: lTQty2 = 0
                    lQtyDmg = 0: lTQtyWDrwn = 0: lTLeft = 0: dTWtLeft = 0: pend = 0
                
                    '.......
                    If iLine = 56 Then Call PageHeader
                    '--------
                    Printer.FontBold = True
                    iLine = iLine + 1
                    Printer.Print Tab(3); CustomerName(GrdData.Columns(1).Value)
                    Printer.FontBold = False
                    
                    iGrp1 = iGrp
                    iNo = iNo + 1
                    lTQtyRcvd = lTQtyRcvd + Val(GrdData.Columns(7).Value)
                    dTWtRcvd = dTWtRcvd + Val(GrdData.Columns(8).Value)
                    lTQty1 = lTQty1 + Val(GrdData.Columns(9).Value)
                    lTQty2 = Round(lTQty2 + Val(GrdData.Columns(11).Value), 2)
                    lQtyDmg = lQtyDmg + Val(GrdData.Columns(13).Value)
                    lTQtyWDrwn = lTQtyWDrwn + Val(GrdData.Columns(14).Value)
                    lTLeft = lTLeft + Val(GrdData.Columns(16).Value)
                    dTWtLeft = dTWtLeft + Val(GrdData.Columns(17).Value)
                    pend = pend + Val(GrdData.Columns(19).Value)
                    
                    sum1 = sum1 + Val(GrdData.Columns(7).Value)
                    sum2 = sum2 + Val(GrdData.Columns(11).Value)
                    sum6 = sum6 + Val(GrdData.Columns(14).Value)
                    sum7 = sum7 + Val(GrdData.Columns(16).Value)
                    sum8 = sum8 + Val(GrdData.Columns(17).Value)
                    sum9 = sum9 + Val(GrdData.Columns(19).Value)
                    sum5 = sum5 + Val(GrdData.Columns(13).Value)
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); GrdData.Columns(3).Value; Tab(15); GrdData.Columns(4).Value; _
                                  Tab(35); GrdData.Columns(5).Value; Tab(80); GrdData.Columns(6).Value; _
                                  Tab(95); GrdData.Columns(7).Value; _
                                  Tab(105); GrdData.Columns(9).Value; Tab(115); GrdData.Columns(10).Value; _
                                  Tab(125); GrdData.Columns(11).Value; Tab(135); GrdData.Columns(12).Value; _
                                  Tab(145); GrdData.Columns(13).Value; Tab(155); GrdData.Columns(14).Value; _
                                  Tab(165); GrdData.Columns(16).Value; Tab(175); GrdData.Columns(19).Value; _
                                  Tab(182); Format$(GrdData.Columns(17).Value, "0.00##"); Tab(200); GrdData.Columns(18).Value
                    
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                

                Else
                    If bFirst = True Then
                        '.......
                        If iLine = 56 Then Call PageHeader
                        '------
                        Printer.FontBold = True
                        iLine = iLine + 1
                        Printer.Print Tab(3); CustomerName(GrdData.Columns(1).Value)
                        Printer.FontBold = False
                        bFirst = False
                    End If
                    iGrp1 = iGrp
                    iNo = iNo + 1
                    lTQtyRcvd = lTQtyRcvd + Val(GrdData.Columns(7).Value)
                    dTWtRcvd = dTWtRcvd + Val(GrdData.Columns(8).Value)
                    lTQty1 = lTQty1 + Val(GrdData.Columns(9).Value)
                    lTQty2 = Round(lTQty2 + Val(GrdData.Columns(11).Value), 2)
                    lQtyDmg = lQtyDmg + Val(GrdData.Columns(13).Value)
                    lTQtyWDrwn = lTQtyWDrwn + Val(GrdData.Columns(14).Value)
                    lTLeft = lTLeft + Val(GrdData.Columns(16).Value)
                    dTWtLeft = dTWtLeft + Val(GrdData.Columns(17).Value)
                    pend = pend + Val(GrdData.Columns(19).Value)
                    
                    sum1 = sum1 + Val(GrdData.Columns(7).Value)
                    sum2 = sum2 + Val(GrdData.Columns(11).Value)
                    sum6 = sum6 + Val(GrdData.Columns(14).Value)
                    sum7 = sum7 + Val(GrdData.Columns(16).Value)
                    sum8 = sum8 + Val(GrdData.Columns(17).Value)
                    sum9 = sum9 + Val(GrdData.Columns(19).Value)
                    sum5 = sum5 + Val(GrdData.Columns(13).Value)
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print Tab(3); GrdData.Columns(3).Value; Tab(15); GrdData.Columns(4).Value; _
                                  Tab(35); GrdData.Columns(5).Value; Tab(80); GrdData.Columns(6).Value; _
                                  Tab(95); GrdData.Columns(7).Value; _
                                  Tab(105); GrdData.Columns(9).Value; Tab(115); GrdData.Columns(10).Value; _
                                  Tab(125); GrdData.Columns(11).Value; Tab(135); GrdData.Columns(12).Value; _
                                  Tab(145); GrdData.Columns(13).Value; Tab(155); GrdData.Columns(14).Value; _
                                  Tab(165); GrdData.Columns(16).Value; Tab(175); GrdData.Columns(19).Value; _
                                  Tab(182); Format$(GrdData.Columns(17).Value, "0.00##"); Tab(200); GrdData.Columns(18).Value
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                    
                End If
            End If
            GrdData.MoveNext
        Next iRec
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        
        Printer.Print Tab(3); iNo & "  Record(s)"; Tab(94); lTQtyRcvd; _
                  Tab(104); lTQty1; Tab(124); Round(lTQty2, 2); Tab(144); lQtyDmg; Tab(154); lTQtyWDrwn; _
                  Tab(164); lTLeft; Tab(174); pend; Tab(182); Format$(dTWtLeft, "0.00##")
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        '----------Pawan
        Printer.NewPage
    
        Printer.Orientation = 2
        iPage = iPage + 1
        Printer.FontBold = False
        Printer.Print Tab(3); "Port of Wilmington, Printed On " & Format(Now, "MM/DD/YYYY"); Tab(190); "Page No:  " & iPage
        Printer.Print
   
        Printer.FontSize = 12
    
        Printer.Print Tab(20); "INVENTORY SUMMARY FOR VESSEL " & Trim(txtVessel) & " As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
    
        Printer.FontSize = 8
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
    
        Printer.Print
                
        Printer.Print
        Printer.Print
        Printer.FontSize = 12
        Printer.FontBold = True
        Printer.Print Tab(7); "VESSEL  GRAND  TOTAL  : "
        
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(10); "Qty Rcvd"; Tab(30); "Withdrawn "; Tab(50); "Left"; Tab(60); "Pending"; Tab(70); "QTY2"; Tab(85); sWeightLabel; Tab(110); "QTY DAMAGED"
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        Printer.FontSize = 12
        Printer.FontBold = True
        Printer.Print Tab(10); sum1; Tab(30); sum6; Tab(50); sum7; Tab(60); sum9; Tab(70); Format$(sum2, "0.00##"); Tab(85); Format$(sum8, "0.00##"); Tab(110); sum5
        
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        
        '-----------Pawan
            
    ElseIf optSelect(3).Value = True Then        'COMMODITY
        sum1 = 0
        sum2 = 0
        sum3 = 0
        sum4 = 0
        sum5 = 0
        sum6 = 0
        sum7 = 0
        sum8 = 0
        sum9 = 0
        Printer.FontSize = 12
        Printer.Print Tab(35); "INVENTORY FOR COMMODITY  " & Trim(txtCommodity) & "  As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Printer.FontSize = 8
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(17); "BOL"; Tab(40); "Mark"; Tab(80); "Dt Rcvd"; Tab(92); "Qty Rcvd"; Tab(105); _
                      "QTY1"; Tab(115); "Unt1"; Tab(125); "Qty2"; Tab(135); "Unt2"; Tab(143); _
                      "Dmgd"; Tab(152); "Withdrawn "; Tab(165); "Left"; Tab(172); "Pending"; Tab(183); sWeightLabel; Tab(199); "R/H"
                  
        Printer.FontBold = True
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        Printer.FontBold = False
        
        GrdData.MoveFirst
        
        For iRec = 0 To GrdData.Rows - 2
            If Val(GrdData.Columns(16).Value) > 0 Then
                iGrp = GrdData.Columns(1).Value
                iGrp2 = GrdData.Columns(2).Value
                
                If iGrp <> iGrp1 Then
                    If bFirst = False Then
                  
                        If iLine = 56 Then Call PageHeader
                        iLine = iLine + 1
                        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"

                        If iLine = 56 Then Call PageHeader
                        iLine = iLine + 1

                        Printer.Print Tab(3); iSNo & "  Record(s) For Vessel " & iGrp21; Tab(94); lSTQtyRcvd; _
                                      Tab(104); lSTQty1; Tab(124); Round(lSTQty2, 2); Tab(144); lSQtyDmg; Tab(154); lSTQtyWDrwn; _
                                      Tab(164); lSTLeft; Tab(174); spend; Tab(182); Format$(dSTWtLeft, "0.00##")

                        If iLine = 56 Then Call PageHeader
                        iLine = iLine + 1
                        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                        
                        If iLine = 56 Then Call PageHeader
                        iLine = iLine + 1
                        
                        Printer.Print Tab(3); iNo & "  Record(s) For Customer  " & iGrp1; Tab(94); lTQtyRcvd; _
                                      Tab(104); lTQty1; Tab(124); Round(lTQty2, 2); Tab(144); lQtyDmg; Tab(154); lTQtyWDrwn; _
                                      Tab(164); lTLeft; Tab(174); pend; Tab(182); Format$(dTWtLeft, "0.00##")
                       
                        If iLine = 56 Then Call PageHeader
                        iLine = iLine + 1
                        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                        
                        If iLine = 56 Then Call PageHeader
                        iLine = iLine + 1
                        Printer.Print ""
                        
                        iSNo = 0: lSTQtyRcvd = 0: dSTWtRcvd = 0: lSTQty1 = 0: lSTQty2 = 0
                        lSQtyDmg = 0: lSTQtyWDrwn = 0: lSTLeft = 0: dSTWtLeft = 0: spend = 0
                        
                        iNo = 0: lTQtyRcvd = 0: dTWtRcvd = 0: lTQty1 = 0: lTQty2 = 0
                        lQtyDmg = 0: lTQtyWDrwn = 0: lTLeft = 0: dTWtLeft = 0: pend = 0
                        
                    End If
                    
                    '.....
                    If iLine = 56 Then Call PageHeader
                    '-----
                    Printer.FontBold = True
                    iLine = iLine + 1
                    Printer.Print Tab(3); CustomerName(iGrp)
                    
                    If iLine = 56 Then Call PageHeader
                    '-----
                    Printer.FontBold = True
                    iLine = iLine + 2
                    Printer.Print Tab(7); VesselName(iGrp2)
                    Printer.Print ""
                    Printer.FontBold = False
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                   
                    'line 1
                    Printer.Print Tab(15); GrdData.Columns(4).Value; _
                                  Tab(25); GrdData.Columns(5).Value; Tab(80); GrdData.Columns(6).Value; _
                                  Tab(95); GrdData.Columns(7).Value; _
                                  Tab(105); GrdData.Columns(9).Value; Tab(115); GrdData.Columns(10).Value; _
                                  Tab(125); Round(GrdData.Columns(11).Value, 2); Tab(135); GrdData.Columns(12).Value; _
                                  Tab(145); GrdData.Columns(13).Value; Tab(155); GrdData.Columns(14).Value; _
                                  Tab(165); GrdData.Columns(16).Value; Tab(175); GrdData.Columns(19).Value; _
                                  Tab(182); Format$(GrdData.Columns(17).Value, "0.00##"); Tab(200); GrdData.Columns(18).Value
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                    

                    iNo = iNo + 1
                    lTQtyRcvd = lTQtyRcvd + Val(GrdData.Columns(7).Value)
                    dTWtRcvd = dTWtRcvd + Val(GrdData.Columns(8).Value)
                    lTQty1 = lTQty1 + Val(GrdData.Columns(9).Value)
                    lTQty2 = Round(lTQty2 + Val(GrdData.Columns(11).Value), 2)
                    lQtyDmg = lQtyDmg + Val(GrdData.Columns(13).Value)
                    lTQtyWDrwn = lTQtyWDrwn + Val(GrdData.Columns(14).Value)
                    lTLeft = lTLeft + Val(GrdData.Columns(16).Value)
                    dTWtLeft = dTWtLeft + Val(GrdData.Columns(17).Value)
                    pend = pend + Val(GrdData.Columns(19).Value)
                    
                    sum1 = sum1 + Val(GrdData.Columns(7).Value)
                    sum2 = sum2 + Val(GrdData.Columns(11).Value)
                    sum6 = sum6 + Val(GrdData.Columns(14).Value)
                    sum7 = sum7 + Val(GrdData.Columns(16).Value)
                    sum8 = sum8 + Val(GrdData.Columns(17).Value)
                    sum9 = sum9 + Val(GrdData.Columns(19).Value)
                    sum5 = sum5 + Val(GrdData.Columns(13).Value)
                    iSNo = iSNo + 1
                    
                    lSTQtyRcvd = lSTQtyRcvd + Val(GrdData.Columns(7).Value)
                    dSTWtRcvd = dSTWtRcvd + Val(GrdData.Columns(8).Value)
                    lSTQty1 = lSTQty1 + Val(GrdData.Columns(9).Value)
                    lSTQty2 = Round(lSTQty2 + Val(GrdData.Columns(11).Value), 2)
                    lSQtyDmg = lSQtyDmg + Val(GrdData.Columns(13).Value)
                    lSTQtyWDrwn = lSTQtyWDrwn + Val(GrdData.Columns(14).Value)
                    lSTLeft = lSTLeft + Val(GrdData.Columns(16).Value)
                    dSTWtLeft = dSTWtLeft + Val(GrdData.Columns(17).Value)
                    spend = spend + Val(GrdData.Columns(19).Value)
                    bFirst = False
                
                Else
                    If bFirst = True Then
                        '..................
                        
                        If iLine = 56 Then Call PageHeader
                        '-----------
                        Printer.FontBold = True
                        iLine = iLine + 1
                        Printer.Print Tab(3); CustomerName(GrdData.Columns(1).Value)
                        
                        If iLine = 56 Then Call PageHeader
                        '--------------
                        Printer.FontBold = True
                        iLine = iLine + 2
                        Printer.Print Tab(7); VesselName(GrdData.Columns(2).Value)
                        Printer.Print ""
                        Printer.FontBold = False
                        bFirst = False
                    End If
                    
                    If iGrp2 <> iGrp21 Then
                        If iLine = 56 Then Call PageHeader
                        iLine = iLine + 1
                        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"

                        If iLine = 56 Then Call PageHeader
                        iLine = iLine + 1

                        Printer.Print Tab(3); iSNo & "  Record(s) For Vessel " & iGrp21; Tab(94); lSTQtyRcvd; _
                                          Tab(104); lSTQty1; Tab(124); Round(lSTQty2, 2); Tab(144); lSQtyDmg; Tab(154); lSTQtyWDrwn; _
                                          Tab(164); lSTLeft; Tab(174); spend; Tab(182); Format$(dSTWtLeft, "0.00##")

                        If iLine = 56 Then Call PageHeader
                        iLine = iLine + 1
                        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"

                        iSNo = 0: lSTQtyRcvd = 0: dSTWtRcvd = 0: lSTQty1 = 0: lSTQty2 = 0
                        lSQtyDmg = 0: lSTQtyWDrwn = 0: lSTLeft = 0: dSTWtLeft = 0: spend = 0

                 
                        '.............
                        If iLine = 56 Then Call PageHeader
                        '---------------
                        Printer.FontBold = True
                        iLine = iLine + 2
                        Printer.Print Tab(7); VesselName(GrdData.Columns(2).Value)
                        Printer.Print ""
                        Printer.FontBold = False

                    End If
                    bFirst2 = False

                    iNo = iNo + 1
                    lTQtyRcvd = lTQtyRcvd + Val(GrdData.Columns(7).Value)
                    dTWtRcvd = dTWtRcvd + Val(GrdData.Columns(8).Value)
                    lTQty1 = lTQty1 + Val(GrdData.Columns(9).Value)
                    lTQty2 = Round(lTQty2 + Val(GrdData.Columns(11).Value), 2)
                    lQtyDmg = lQtyDmg + Val(GrdData.Columns(13).Value)
                    lTQtyWDrwn = lTQtyWDrwn + Val(GrdData.Columns(14).Value)
                    lTLeft = lTLeft + Val(GrdData.Columns(16).Value)
                    dTWtLeft = dTWtLeft + Val(GrdData.Columns(17).Value)
                    pend = pend + Val(GrdData.Columns(19).Value)
                    
                    sum1 = sum1 + Val(GrdData.Columns(7).Value)
                    sum2 = sum2 + Val(GrdData.Columns(11).Value)
                    sum6 = sum6 + Val(GrdData.Columns(14).Value)
                    sum7 = sum7 + Val(GrdData.Columns(16).Value)
                    sum8 = sum8 + Val(GrdData.Columns(17).Value)
                    sum9 = sum9 + Val(GrdData.Columns(19).Value)
                    sum5 = sum5 + Val(GrdData.Columns(13).Value)
                    iSNo = iSNo + 1
                    
                    lSTQtyRcvd = lSTQtyRcvd + Val(GrdData.Columns(7).Value)
                    dSTWtRcvd = dSTWtRcvd + Val(GrdData.Columns(8).Value)
                    lSTQty1 = lSTQty1 + Val(GrdData.Columns(9).Value)
                    lSTQty2 = Round(lSTQty2 + Val(GrdData.Columns(11).Value), 2)
                    lSQtyDmg = lSQtyDmg + Val(GrdData.Columns(13).Value)
                    lSTQtyWDrwn = lSTQtyWDrwn + Val(GrdData.Columns(14).Value)
                    lSTLeft = lSTLeft + Val(GrdData.Columns(16).Value)
                    dSTWtLeft = dSTWtLeft + Val(GrdData.Columns(17).Value)
                    spend = spend + Val(GrdData.Columns(19).Value)
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    'line 2
                    Printer.Print Tab(15); GrdData.Columns(4).Value; _
                                  Tab(25); GrdData.Columns(5).Value; Tab(80); GrdData.Columns(6).Value; _
                                  Tab(95); GrdData.Columns(7).Value; _
                                  Tab(105); GrdData.Columns(9).Value; Tab(115); GrdData.Columns(10).Value; _
                                  Tab(125); Round(GrdData.Columns(11).Value, 2); Tab(135); GrdData.Columns(12).Value; _
                                  Tab(145); GrdData.Columns(13).Value; Tab(155); GrdData.Columns(14).Value; _
                                  Tab(165); GrdData.Columns(16).Value; Tab(175); GrdData.Columns(19).Value; _
                                  Tab(182); Format$(GrdData.Columns(17).Value, "0.00##"); Tab(200); GrdData.Columns(18).Value
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                    
                End If
           End If
            iGrp1 = iGrp
            iGrp21 = iGrp2
            GrdData.MoveNext
        Next iRec
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"

        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1

        Printer.Print Tab(3); iSNo & "  Record(s) For Vessel " & iGrp2; Tab(94); lSTQtyRcvd; _
                    Tab(104); lSTQty1; Tab(124); Round(lSTQty2, 2); Tab(144); lSQtyDmg; Tab(154); lSTQtyWDrwn; _
                    Tab(164); lSTLeft; Tab(174); spend; Tab(182); Format$(dSTWtLeft, "0.00##")

        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"

        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        
        Printer.Print Tab(3); iNo & "  Record(s) For Customer  " & iGrp; Tab(94); lTQtyRcvd; _
                    Tab(104); lTQty1; Tab(124); Round(lTQty2, 2); Tab(144); lQtyDmg; Tab(154); lTQtyWDrwn; _
                    Tab(164); lTLeft; Tab(174); pend; Tab(182); Format$(dTWtLeft, "0.00##")
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        Printer.NewPage
    
        Printer.Orientation = 2
        iPage = iPage + 1
        Printer.FontBold = False
        Printer.Print Tab(3); "Port of Wilmington, Printed On " & Format(Now, "MM/DD/YYYY"); Tab(190); "Page No:  " & iPage
        Printer.Print
        Printer.FontSize = 12
        Printer.Print Tab(20); "INVENTORY SUMMARY FOR COMMODITY " & Trim(txtCommodity) & " As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Printer.FontSize = 8
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        Printer.Print
        Printer.Print
        Printer.Print
        Printer.FontSize = 12
        Printer.FontBold = True
        Printer.Print Tab(7); "COMMODITY  GRAND  TOTAL  : "
        
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(10); "Qty Rcvd"; Tab(30); "Withdrawn "; Tab(50); "Left"; Tab(60); "Pending"; Tab(70); "QTY2"; Tab(85); sWeightLabel; Tab(110); "QTY DAMAGED"
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        Printer.FontSize = 12
        Printer.FontBold = True
        Printer.Print Tab(10); sum1; Tab(30); sum6; Tab(50); sum7; Tab(60); sum9; Tab(70); Format$(sum2, "0.00##"); Tab(85); Format$(sum8, "0.00##"); Tab(110); sum5
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        
        '-----------Pawan
    
    Else '---------------Exporter/Supplier Search
        
        
        sum1 = 0
        sum2 = 0
        sum3 = 0
        sum4 = 0
        sum5 = 0
        sum6 = 0
        sum7 = 0
        sum8 = 0
        sum9 = 0
        Printer.FontSize = 12
        Printer.Print Tab(35); "INVENTORY FOR SUPPLIER  " & Trim(txtSupplierName) & "  As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        Printer.FontSize = 8
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(3); "Comm"; Tab(17); "BOL"; Tab(40); "Mark"; Tab(80); "Dt Rcvd"; Tab(92); "Qty Rcvd"; Tab(105); _
                      "QTY1"; Tab(115); "Unt1"; Tab(125); "Qty2"; Tab(135); "Unt2"; Tab(143); _
                      "Dmgd"; Tab(152); "Withdrawn "; Tab(165); "Left"; Tab(172); "Pending"; Tab(183); sWeightLabel; Tab(199); "R/H"
        Printer.FontBold = True
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        Printer.FontBold = False
        Printer.Print ""
        GrdData.MoveFirst
        
        For iRec = 0 To GrdData.Rows - 2
            If Val(GrdData.Columns(15).Value) > 0 Then
                iGrp = GrdData.Columns(1).Value
                If iGrp <> iGrp1 And bFirst = False Then
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); iNo & "  Record(s)"; Tab(94); lTQtyRcvd; _
                                  Tab(104); lTQty1; Tab(124); Round(lTQty2, 2); Tab(144); lQtyDmg; Tab(154); lTQtyWDrwn; _
                                  Tab(164); lTLeft; Tab(174); pend; Tab(182); Format$(dTWtLeft, "0.00##")
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                    
                    iNo = 0: lTQtyRcvd = 0: dTWtRcvd = 0: lTQty1 = 0: lTQty2 = 0
                    lQtyDmg = 0: lTQtyWDrwn = 0: lTLeft = 0: dTWtLeft = 0: pend = 0
                    
                    '.....
                    If iLine = 56 Then Call PageHeader
                    '-------
                    Printer.FontBold = True
                    iLine = iLine + 2
                    Printer.Print Tab(3); VesselName(iGrp)
                    Printer.Print ""
                    Printer.FontBold = False
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); GrdData.Columns(2).Value; Tab(15); GrdData.Columns(3).Value; _
                                  Tab(35); GrdData.Columns(4).Value; Tab(80); GrdData.Columns(5).Value; _
                                  Tab(95); GrdData.Columns(6).Value; _
                                  Tab(105); GrdData.Columns(8).Value; Tab(115); GrdData.Columns(9).Value; _
                                  Tab(125); Round(GrdData.Columns(10).Value, 2); Tab(135); GrdData.Columns(11).Value; _
                                  Tab(145); GrdData.Columns(12).Value; Tab(155); GrdData.Columns(13).Value; _
                                  Tab(165); GrdData.Columns(15).Value; Tab(175); GrdData.Columns(18).Value; _
                                  Tab(182); Format$(GrdData.Columns(16).Value, "0.00##"); Tab(200); GrdData.Columns(17).Value
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                
                    iNo = 1
                    lTQtyRcvd = Val(GrdData.Columns(6).Value)
                    dTWtRcvd = Val(GrdData.Columns(7).Value)
                    lTQty1 = Val(GrdData.Columns(8).Value)
                    lTQty2 = Round(Val(GrdData.Columns(10).Value), 2)
                    lQtyDmg = Val(GrdData.Columns(12).Value)
                    lTQtyWDrwn = Val(GrdData.Columns(13).Value)
                    lTLeft = Val(GrdData.Columns(15).Value)
                    dTWtLeft = Val(GrdData.Columns(16).Value)
                    pend = Val(GrdData.Columns(18).Value)
                    
                    sum1 = sum1 + Val(GrdData.Columns(6).Value)
                    sum2 = sum2 + Val(GrdData.Columns(11).Value)
                    sum6 = sum6 + Val(GrdData.Columns(13).Value)
                    sum7 = sum7 + Val(GrdData.Columns(15).Value)
                    sum8 = sum8 + Val(GrdData.Columns(16).Value)
                    sum9 = sum9 + Val(GrdData.Columns(18).Value)
                    sum5 = sum5 + Val(GrdData.Columns(12).Value)
                    iGrp1 = iGrp
                Else
                    If bFirst = True Then
                        '.........
                        If iLine = 56 Then Call PageHeader
                        '--------
                        Printer.FontBold = True
                        iLine = iLine + 2
                        Printer.Print Tab(3); VesselName(GrdData.Columns(1).Value)
                        Printer.Print ""
                        Printer.FontBold = False
                        bFirst = False
                    End If
                    iGrp1 = iGrp
                    iNo = iNo + 1
                    lTQtyRcvd = lTQtyRcvd + Val(GrdData.Columns(6).Value)
                    dTWtRcvd = dTWtRcvd + Val(GrdData.Columns(7).Value)
                    lTQty1 = lTQty1 + Val(GrdData.Columns(8).Value)
                    lTQty2 = Round(lTQty2 + Val(GrdData.Columns(10).Value), 2)
                    lQtyDmg = lQtyDmg + Val(GrdData.Columns(12).Value)
                    lTQtyWDrwn = lTQtyWDrwn + Val(GrdData.Columns(13).Value)
                    lTLeft = lTLeft + Val(GrdData.Columns(15).Value)
                    dTWtLeft = dTWtLeft + Val(GrdData.Columns(16).Value)
                    pend = pend + Val(GrdData.Columns(18).Value)
                    
                    sum1 = sum1 + Val(GrdData.Columns(6).Value)
                    sum2 = sum2 + Val(GrdData.Columns(11).Value)
                    sum6 = sum6 + Val(GrdData.Columns(13).Value)
                    sum7 = sum7 + Val(GrdData.Columns(15).Value)
                    sum8 = sum8 + Val(GrdData.Columns(16).Value)
                    sum9 = sum9 + Val(GrdData.Columns(18).Value)
                    sum5 = sum5 + Val(GrdData.Columns(12).Value)
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    
                    Printer.Print Tab(3); GrdData.Columns(2).Value; Tab(15); GrdData.Columns(3).Value; _
                                  Tab(35); GrdData.Columns(4).Value; Tab(80); GrdData.Columns(5).Value; _
                                  Tab(95); GrdData.Columns(6).Value; _
                                  Tab(105); GrdData.Columns(8).Value; Tab(115); GrdData.Columns(9).Value; _
                                  Tab(125); Round(GrdData.Columns(10).Value, 2); Tab(135); GrdData.Columns(11).Value; _
                                  Tab(145); GrdData.Columns(12).Value; Tab(155); GrdData.Columns(13).Value; _
                                  Tab(165); GrdData.Columns(15).Value; Tab(175); GrdData.Columns(18).Value; _
                                  Tab(182); Format$(GrdData.Columns(16).Value, "0.00##"); _
                                  Tab(200); GrdData.Columns(17).Value
                    
                    If iLine = 56 Then Call PageHeader
                    iLine = iLine + 1
                    Printer.Print ""
                    
                End If
            End If
            GrdData.MoveNext
        Next iRec
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
                    
        Printer.Print Tab(3); iNo & "  Record(s)"; Tab(94); lTQtyRcvd; Tab(104); _
                  lTQty1; Tab(124); Round(lTQty2, 2); Tab(144); lQtyDmg; Tab(154); lTQtyWDrwn; _
                  Tab(164); lTLeft; Tab(174); pend; Tab(182); Format$(dTWtLeft, "0.00##")
        
        If iLine = 56 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        '----------Pawan
        Printer.NewPage
    
        Printer.Orientation = 2
        iPage = iPage + 1
        Printer.FontBold = False
        Printer.Print Tab(3); "Port of Wilmington, Printed On " & Format(Now, "MM/DD/YYYY"); Tab(190); "Page No:  " & iPage
        Printer.Print
   
        Printer.FontSize = 12
    
        Printer.Print Tab(20); "INVENTORY SUMMARY FOR SUPPLIER " & Trim(txtSupplierName) & " As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
    
        Printer.FontSize = 8
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
    
        Printer.Print
                
        Printer.Print
        Printer.Print
        Printer.FontSize = 12
        Printer.FontBold = True
        Printer.Print Tab(7); "SUPPLIER  GRAND  TOTAL  : "
        
        Printer.Print ""
        Printer.Print ""
'        Printer.Print Tab(10); "Qty Rcvd"; Tab(30); "Withdrawn "; Tab(50); "Left"; Tab(70); "Pending"; Tab(90); sWeightLabel; Tab(110); "QTY DAMAGED"
'        Printer.FontSize = 8
'        Printer.FontBold = False
'        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
'        Printer.FontSize = 12
'        Printer.FontBold = True
'        Printer.Print Tab(10); sum1; Tab(30); sum6; Tab(50); sum7; Tab(70); sum9; Tab(90); Format$(sum8, "0.00##"); Tab(110); sum5
        Printer.Print Tab(10); "Qty Rcvd"; Tab(30); "Withdrawn "; Tab(50); "Left"; Tab(60); "Pending"; Tab(70); "QTY2"; Tab(85); sWeightLabel; Tab(110); "QTY DAMAGED"
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        Printer.FontSize = 12
        Printer.FontBold = True
        Printer.Print Tab(10); sum1; Tab(30); sum6; Tab(50); sum7; Tab(60); sum9; Tab(70); Format$(sum2, "0.00##"); Tab(85); Format$(sum8, "0.00##"); Tab(110); sum5
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
        
        '-----------Pawan
            
    End If
    
    Printer.EndDoc
    
End Sub
Sub PageHeader()
    
    Dim sWeightLabel  As String
    
    iPage = iPage + 1
    iLine = 8
    Printer.NewPage
    Printer.Orientation = 2
    
    Printer.FontBold = False
    Printer.Print Tab(3); "Port of Wilmington, Printed On " & Format(Now, "MM/DD/YYYY"); Tab(190); "Page No:  " & iPage
    Printer.Print
   
    sWeightLabel = "WT Left"
    Printer.FontSize = 12
    If optSelect(0).Value = True Then
        Printer.Print Tab(35); "INVENTORY As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
    ElseIf optSelect(1).Value = True Then
        Printer.Print Tab(35); "INVENTORY FOR CUSTOMER " & Trim(txtCustName) & " As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        If (Trim$(txtCustId.Text) = "651") Then sWeightLabel = "MBF Left"
    ElseIf optSelect(2).Value = True Then
        Printer.Print Tab(35); "INVENTORY FOR VESSEL " & Trim(txtVessel) & " As of: " & Format(DtpAsOf.Value, "MM/DD/YYYY")
        If (Trim$(txtLrNum.Text) = "3") Then sWeightLabel = "MBF Left"
    ElseIf optSelect(3).Value = True Then
        If (Trim$(txtCommCode.Text) = "4161") Or (Trim$(txtCommCode.Text) = "4261") Or (Trim$(txtCommCode.Text) = "4260") Or (Trim$(txtCommCode.Text) = "4963") Then 'dlr 2/6/06 4260
            sWeightLabel = "MBF Left"
        End If
        Printer.Print Tab(35); "INVENTORY FOR COMMODITY  " & Trim(txtCommodity) & " As of " & Format(DtpAsOf.Value, "MM/DD/YYYY")
    End If
    Printer.FontSize = 8
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""

    If optSelect(3).Value = True Then
        Printer.Print Tab(17); "BOL"; Tab(40); "Mark"; Tab(80); "Dt Rcvd"; Tab(92); "Qty Rcvd"; Tab(105); _
                  "QTY1"; Tab(115); "Unt1"; Tab(125); "Qty2"; Tab(135); "Unt2"; Tab(143); _
                  "Dmgd"; Tab(152); "Withdrawn "; Tab(165); "Left"; Tab(172); "Pending"; Tab(183); sWeightLabel; Tab(199); "R/H"
    Else
        Printer.Print Tab(3); "Comm"; Tab(17); "BOL"; Tab(40); "Mark"; Tab(80); "Dt Rcvd"; Tab(92); "Qty Rcvd"; Tab(105); _
                  "QTY1"; Tab(115); "Unt1"; Tab(125); "Qty2"; Tab(135); "Unt2"; Tab(143); _
                  "Dmgd"; Tab(152); "Withdrawn "; Tab(165); "Left"; Tab(172); "Pending"; Tab(183); sWeightLabel; Tab(199); "R/H"
    End If
                      
    Printer.FontBold = True
    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
    Printer.FontBold = False
    
End Sub

Private Sub OptBySupplier_Click()
    
End Sub



Private Sub optSelect_Click(Index As Integer)

    Select Case Index
        
        Case 0
            txtCustId.Enabled = False
            txtCustId.BackColor = &H80000004
            'txtCustName.Enabled = False
            txtCustName.BackColor = &H80000004
            txtLrNum.Enabled = False
            txtLrNum.BackColor = &H80000004
            txtVessel.Enabled = False
            txtVessel.BackColor = &H80000004
            txtCommCode.Enabled = False
            txtCommCode.BackColor = &H80000004
            txtCommodity.Enabled = False
            txtCommodity.BackColor = &H80000004
            ClearValues (0)
        Case 1
            txtCustId.Enabled = True
            txtCustId.BackColor = vbWhite
            'txtCustName.Enabled = True
            txtCustName.BackColor = vbWhite
            txtLrNum.Enabled = False
            txtLrNum.BackColor = &H80000004
            'txtVessel.Enabled = False
            txtVessel.BackColor = &H80000004
            txtCommCode.Enabled = False
            txtCommCode.BackColor = &H80000004
            'txtCommodity.Enabled = False
            txtCommodity.BackColor = &H80000004
            ClearValues (1)
        Case 2
            txtCustId.Enabled = False
            txtCustId.BackColor = &H80000004
            'txtCustName.Enabled = False
            txtCustName.BackColor = &H80000004
            txtLrNum.Enabled = True
            txtLrNum.BackColor = vbWhite
            'txtVessel.Enabled = True
            txtVessel.BackColor = vbWhite
            txtCommCode.Enabled = False
            txtCommCode.BackColor = &H80000004
            'txtCommodity.Enabled = False
            txtCommodity.BackColor = &H80000004
            ClearValues (2)
        Case 3
            txtCustId.Enabled = False
            txtCustId.BackColor = &H80000004
           ' txtCustName.Enabled = False
            txtCustName.BackColor = &H80000004
            txtLrNum.Enabled = False
            txtLrNum.BackColor = &H80000004
           ' txtVessel.Enabled = False
            txtVessel.BackColor = &H80000004
            txtCommCode.Enabled = True
            txtCommCode.BackColor = vbWhite
            'txtCommodity.Enabled = True
            txtCommodity.BackColor = vbWhite
            ClearValues (3)
    End Select
    
End Sub
Sub ClearValues(i As Integer)
    Select Case i
        Case 0
            txtCustId = ""
            txtCustName = ""
            txtLrNum = ""
            txtVessel = ""
            txtCommCode = ""
            txtCommodity = ""
        Case 1
            txtLrNum = ""
            txtVessel = ""
            txtCommCode = ""
            txtCommodity = ""
        Case 2
            txtCustId = ""
            txtCustName = ""
            txtCommCode = ""
            txtCommodity = ""
        Case 3
            txtCustId = ""
            txtCustName = ""
            txtLrNum = ""
            txtVessel = ""
    End Select
End Sub

Private Sub OptSupplier_Click(Index As Integer)
    Select Case Index
        Case 0 'No supplier included in search
            Call SetEnabled(False, True)
            txtSupplierId = ""
            txtSupplierName = ""
        Case 1 'include supplier in search
            Call SetEnabled(True, True)
        Case 2 'search only by supplier
            Call SetEnabled(True, False)
            Dim i As Integer
            For i = 0 To 3
                optSelect(i).Value = False
            Next
            txtSupplierId.SetFocus
    End Select
End Sub
Sub SetEnabled(bId, bFrame As Boolean)
    txtSupplierId.Enabled = bId
    Frame1.Enabled = bFrame
End Sub

Private Sub txtCommCode_Validate(Cancel As Boolean)

    If txtCommCode = "" Then Exit Sub
    
    SqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '" & Trim$(txtCommCode) & "'"
    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RECORDCOUNT > 0 Then
        txtCommodity = dsCOMMODITY_PROFILE.fields("COMMODITY_NAME").Value
    Else
        MsgBox "Invalid COMMODITY", vbInformation, "COMMODITY"
        Cancel = True
    End If
    
End Sub
Private Sub txtCustId_Validate(Cancel As Boolean)
    
    If txtCustId = "" Then Exit Sub
    
    SqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & Trim$(txtCustId) & "'"
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
        txtCustName = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
    Else
        MsgBox "Invalid CUSTOMER", vbInformation, "CUSTOMER"
        Cancel = True
    End If
    
End Sub
Function CustomerName(CustId As Long) As String

    SqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & Trim$(CustId) & "'"
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
       CustomerName = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
    Else
        CustomerName = ""
    End If
    
End Function
Function VesselName(LrNum As Long) As String

    SqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '" & Trim(LrNum) & "'"
    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RECORDCOUNT > 0 Then
        VesselName = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
    Else
        VesselName = ""
    End If
    
End Function
Private Sub txtLrNum_Validate(Cancel As Boolean)
    
    If txtLrNum = "" Then Exit Sub
    
    SqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '" & Trim$(txtLrNum) & "'"
    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RECORDCOUNT > 0 Then
        txtVessel = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
    Else
        MsgBox "Invalid LR Number", vbInformation, "VESSEL"
        Cancel = True
    End If
    
End Sub



Private Sub txtSupplierId_Validate(Cancel As Boolean)
    'NOTE: EXPORTER=SUPPLIER
    
    If txtSupplierId = "" Then Exit Sub
    On Error GoTo err
    SqlStmt = "SELECT * FROM EXPORTER_PROFILE WHERE EXPORTER_ID = " & Trim$(txtSupplierId)
    Set dsEXPORTER_PROFILE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsEXPORTER_PROFILE.RECORDCOUNT > 0 Then
        txtSupplierName = dsEXPORTER_PROFILE.fields("EXPORTER_NAME").Value
    Else
        MsgBox "Invalid SUPPLIER", vbInformation, "SUPPLIER"
        Cancel = True
    End If
    Exit Sub
err:
    Set dsEXPORTER_PROFILE = Nothing
    Exit Sub
End Sub

Private Sub FindOriginalValues(sLotNum As String)
    '  This function is a recursive call to find a single record which has a non-zero value for QTY1, QTY2, and weight in CARGO_MANIFEST
    
    SqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE ORIGINAL_CONTAINER_NUM = '" & sLotNum & "'"
    Set dsCARGO_ORIGINAL_MANIFEST = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    
    If dsCARGO_ORIGINAL_MANIFEST.fields("QTY_EXPECTED") > 0 Then
        dQty1FromCM = Val("" & dsCARGO_ORIGINAL_MANIFEST.fields("QTY_EXPECTED").Value)
        dQty2FromCM = Val("" & dsCARGO_ORIGINAL_MANIFEST.fields("QTY2_EXPECTED").Value)
        dWeightFromCM = Val("" & dsCARGO_ORIGINAL_MANIFEST.fields("CARGO_WEIGHT").Value)
        Exit Sub
    Else
        FindOriginalValues (dsCARGO_ORIGINAL_MANIFEST.fields("CONTAINER_NUM").Value)
    End If
        
End Sub

Private Sub makeTopLevelSQL()

    SqlStmt = "SELECT * FROM CARGO_TRACKING CT, CARGO_MANIFEST CM WHERE QTY_RECEIVED > 0 " _
        & "AND CT.DATE_RECEIVED <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
        & "AND CT.LOT_NUM = CM.CONTAINER_NUM "

    
    If optSelect(1).Value = True Then ' Customer
        SqlStmt = SqlStmt & "AND OWNER_ID='" & Trim(txtCustId) & "' "
    ElseIf optSelect(2).Value = True Then ' Vessel
        SqlStmt = SqlStmt & "AND LR_NUM='" & Trim(txtLrNum) & "' "
    ElseIf optSelect(3).Value = True Then ' Commodity
        SqlStmt = SqlStmt & "AND CM.COMMODITY_CODE='" & Trim(txtCommCode) & "' "
    Else ' Exporter Only
        SqlStmt = SqlStmt & "AND CM.EXPORTER_ID='" & Val(Trim(txtSupplierId)) & "' "
    End If
    
    If OptSupplier(1).Value = True And Trim(txtSupplierId) <> "" Then 'IF EXPORTER/SUPPLIER IS CHECKED
        SqlStmt = SqlStmt & "AND CM.EXPORTER_ID =" & Val(Trim(txtSupplierId)) & " "
    End If
    
    If optInvOnly(1).Value = True Then
        SqlStmt = SqlStmt & "AND ((SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY WHERE LOT_NUM = CT.LOT_NUM " _
            & "AND DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')) < CT.QTY_RECEIVED OR " _
            & "(SELECT SUM(QTY_CHANGE) FROM CARGO_ACTIVITY WHERE LOT_NUM = CT.LOT_NUM " _
            & "AND DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpAsOf.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')) IS NULL)"
    End If
    
    SqlStmt = SqlStmt & "ORDER BY "
    
    If optSelect(1).Value = True Then ' Customer
        SqlStmt = SqlStmt & "LR_NUM, CM.COMMODITY_CODE, "
    ElseIf optSelect(2).Value = True Then ' Vessel
        SqlStmt = SqlStmt & "OWNER_ID, CM.COMMODITY_CODE, "
    ElseIf optSelect(3).Value = True Then ' Commodity
        SqlStmt = SqlStmt & "OWNER_ID, LR_NUM, "
    Else ' Exporter Only
        SqlStmt = SqlStmt & "LR_NUM, CM.COMMODITY_CODE, "
    End If
    
    If optSelect(2).Value = False Or Trim$(txtLrNum.Text) <> "3" Then ' add this to everything except a vessel search of LR_NUM = 3
        SqlStmt = SqlStmt & "CARGO_BOL, "
    End If
    
    SqlStmt = SqlStmt & "CARGO_MARK, DATE_RECEIVED"
    
End Sub
