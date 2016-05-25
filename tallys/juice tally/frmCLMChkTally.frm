VERSION 5.00
Begin VB.Form frmCLMChkTally 
   Caption         =   "Juice Tally"
   ClientHeight    =   5550
   ClientLeft      =   5145
   ClientTop       =   4500
   ClientWidth     =   7350
   LinkTopic       =   "Form1"
   ScaleHeight     =   5550
   ScaleWidth      =   7350
   StartUpPosition =   2  'CenterScreen
   Begin VB.Frame Frame2 
      Height          =   1815
      Left            =   240
      TabIndex        =   4
      Top             =   3480
      Width           =   6615
      Begin VB.CommandButton cmdClose 
         Caption         =   "Close"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   615
         Left            =   720
         TabIndex        =   3
         Top             =   960
         Width           =   5175
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "Print"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   615
         Left            =   720
         TabIndex        =   2
         Top             =   240
         Width           =   5175
      End
   End
   Begin VB.Frame Frame1 
      Height          =   3255
      Left            =   240
      TabIndex        =   1
      Top             =   120
      Width           =   6615
      Begin VB.TextBox txtOrderNum 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   3480
         TabIndex        =   0
         Text            =   "DM000"
         Top             =   960
         Width           =   2655
      End
      Begin VB.Label Label4 
         AutoSize        =   -1  'True
         Caption         =   "Trucking out Juice Checker Tally"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H000000FF&
         Height          =   360
         Left            =   960
         TabIndex        =   6
         Top             =   360
         Width           =   4620
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         Caption         =   "Dummy Order Number"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   240
         Left            =   360
         TabIndex        =   5
         Top             =   1080
         Width           =   2295
      End
   End
End
Attribute VB_Name = "frmCLMChkTally"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iTotalReband As Double
Dim iTotalOrder As Double
Dim iPrintLines As Integer
Dim sPrintDate As String
Dim sVessel As String
Dim sCustomer As String
Dim sDummyNo As String
Dim sRecOrderNo As String
Dim sCommodity As String
Dim sBol As String
Dim sMarkLot As String
Dim sUnit As String
Dim sReband As String
Dim sPallet As String
Dim sQty As String
Dim sChecker As String
Dim sFirstScanDate As String



Private Sub cmdClose_Click()

    Unload Me

End Sub

Private Sub Form_Load()

    Call Initialize
    
End Sub

Private Sub cmdPrint_Click()
    Dim empName As String

    iTotalReband = 0
    iTotalOrder = 0
    iPrintLines = 0

    sDummyNo = txtOrderNum.Text
    
    strSql = "SELECT TO_CHAR(SYSDATE, 'MM/DD/YYYY HH24:MI:SS') THE_DATE FROM DUAL"
    Set dsSHORT_TERM_DATA = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    sPrintDate = dsSHORT_TERM_DATA.Fields("THE_DATE").Value

    strSql = "SELECT * FROM BNI_DUMMY_WITHDRAWAL WHERE D_DEL_NO = '" & sDummyNo & "'"
    Set dsSHORT_TERM_DATA = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsSHORT_TERM_DATA.RecordCount = 0 Then
        MsgBox "Invalid Dummy Number"
        txtOrderNum.Text = "DM000"
        Exit Sub
    End If
    
    sVessel = get_vessel(dsSHORT_TERM_DATA.Fields("LR_NUM").Value)
    sCustomer = get_customer(dsSHORT_TERM_DATA.Fields("OWNER_ID").Value)
    If Not IsNull(dsSHORT_TERM_DATA.Fields("ORDER_NO").Value) Then
        sRecOrderNo = dsSHORT_TERM_DATA.Fields("ORDER_NO").Value
    Else
        sRecOrderNo = "NONE"
    End If
    sCommodity = get_commodity(dsSHORT_TERM_DATA.Fields("COMMODITY_CODE").Value)
'    sBol = dsSHORT_TERM_DATA.Fields("BOL").Value
'    sMarkLot = dsSHORT_TERM_DATA.Fields("MARK").Value
    sUnit = dsSHORT_TERM_DATA.Fields("UNIT2").Value
    
    strSql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH24:MI:SS') THE_DATE FROM CARGO_ACTIVITY " _
            & "WHERE ORDER_NUM = '" & sDummyNo & "' AND SERVICE_CODE = '6' " _
            & "AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')"
    Set dsSHORT_TERM_DATA = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    If Not IsNull(dsSHORT_TERM_DATA.Fields("THE_DATE").Value) Then
        sFirstScanDate = dsSHORT_TERM_DATA.Fields("THE_DATE").Value
    Else
        MsgBox "No Cargo Activity Records found for this Order."
        End
    End If
    
    
    Printer.Copies = 3
    'Printer.Copies = 1
    
    Printer.FontBold = True
    Printer.FontItalic = False
    Printer.FontSize = 11
    Printer.Print Tab(40); "PORT OF WILMINGTON TALLY"
    Printer.Print ""
    Printer.FontSize = 10
    
    Printer.Print Tab(5); "PRINTED ON:  " & sPrintDate
    Printer.Print ""
    Printer.Print Tab(5); "SCANNED STARTING:  " & sFirstScanDate; Tab(65); "CUSTOMER:  " & sCustomer
    Printer.Print Tab(5); "POW DUMMY#:  " & sDummyNo; Tab(65); "VESSEL:  " & sVessel
    Printer.Print Tab(5); "RECEIVER ORDER#:  " & sRecOrderNo; Tab(65); "COMMODITY:  " & sCommodity
    
    Printer.Print ""
    Printer.Print ""
    Printer.FontBold = False
    
    Printer.Print "BOL"; Tab(15); "BARCODE"; Tab(35); "HEADMARK / LOT"; Tab(85); "QTY/UOM"; Tab(98); "REBAND"; Tab(115); "CHECKER"
    Printer.Print "______________________________________________________________________________________________________________________________________________________"
    
'    strSql = "SELECT CA.PALLET_ID THE_PALLET, QTY_CHANGE THE_CHANGE, NVL(QTY_DAMAGED, 0) THE_DAMAGE, ACTIVITY_DESCRIPTION THE_CHECKER, BOL, CARGO_DESCRIPTION "
'SUBSTR(EMPLOYEE_NAME, 0, 8) THE_CHECKER,       EMPLOYEE PER        AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PER.EMPLOYEE_ID, -4)
    strSql = "SELECT CA.PALLET_ID THE_PALLET, QTY_CHANGE THE_CHANGE, NVL(JUICE_REBANDS, 0) THE_DAMAGE,  BOL, CARGO_DESCRIPTION, ACTIVITY_NUM, CA.ARRIVAL_NUM, CA.CUSTOMER_ID " _
        & "FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD " _
        & "WHERE ORDER_NUM = '" & sDummyNo & "' AND SERVICE_CODE = '6' " _
        & "AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID') " _
        & "AND CA.PALLET_ID = CT.PALLET_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND CT.RECEIVER_ID = CA.CUSTOMER_ID " _
        & "AND CT.PALLET_ID = CTAD.PALLET_ID(+) AND CT.RECEIVER_ID = CTAD.RECEIVER_ID(+) AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM(+)"
    Set dsCARGO_ACTIVITY = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    dsCARGO_ACTIVITY.MoveFirst
    While Not dsCARGO_ACTIVITY.EOF
    
        sPallet = dsCARGO_ACTIVITY.Fields("THE_PALLET").Value
        sQty = dsCARGO_ACTIVITY.Fields("THE_CHANGE").Value
'        sChecker = dsCARGO_ACTIVITY.Fields("THE_CHECKER").Value
        sBol = dsCARGO_ACTIVITY.Fields("BOL").Value
        sMarkLot = dsCARGO_ACTIVITY.Fields("CARGO_DESCRIPTION").Value
        
        empName = get_checker_name(sPallet, dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value, dsCARGO_ACTIVITY.Fields("ARRIVAL_NUM").Value, dsCARGO_ACTIVITY.Fields("ACTIVITY_NUM").Value)
        
        
        If dsCARGO_ACTIVITY.Fields("THE_DAMAGE").Value = 0 Then
            sReband = "N"
        Else
            sReband = "Y"
            iTotalReband = iTotalReband + 1
        End If
        
        iTotalOrder = iTotalOrder + Val("" & sQty)
        iPrintLines = iPrintLines + 1
        
        Printer.Print sBol; Tab(15); sPallet; Tab(35); sMarkLot; Tab(85); sQty & " " & sUnit; Tab(99); sReband; Tab(115); empName
        
        dsCARGO_ACTIVITY.MoveNext
    Wend
    
    Printer.Print "======================================================================================================================================================"
    Printer.Print Tab(80); "Total:  " & iTotalOrder; Tab(93); "Reband: " & iTotalReband
    
    While iPrintLines < 44
        Printer.Print ""
        iPrintLines = iPrintLines + 1
    Wend
    
    Printer.Print ""
    Printer.Print Tab(60); "Driver has verified cargo loaded with no visible signs of damage/leaks."
    Printer.Print ""
    
    Printer.Print Tab(60); "Driver Signature:  ________________________________________"
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(60); "TRL#:  ____________________________________________________"
    Printer.Print "______________________________________________________________________________________________________________________________________________________"
    
    Printer.EndDoc
    
    txtOrderNum.Text = "DM000"
    
End Sub

