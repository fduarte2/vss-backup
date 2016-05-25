VERSION 5.00
Begin VB.Form frmPaperOutbound 
   Caption         =   "Abitibi Outbound Tally"
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
      TabIndex        =   6
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
         TabIndex        =   5
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
         TabIndex        =   4
         Top             =   240
         Width           =   5175
      End
   End
   Begin VB.Frame Frame1 
      Height          =   3255
      Left            =   240
      TabIndex        =   3
      Top             =   120
      Width           =   6615
      Begin VB.TextBox txtCode 
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
         Left            =   3000
         TabIndex        =   2
         Top             =   2400
         Width           =   2655
      End
      Begin VB.TextBox txtCustNum 
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
         Left            =   3000
         TabIndex        =   1
         Top             =   1680
         Width           =   2655
      End
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
         Left            =   3000
         TabIndex        =   0
         Top             =   960
         Width           =   2655
      End
      Begin VB.Label Label3 
         Caption         =   "Code"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   360
         TabIndex        =   10
         Top             =   2520
         Width           =   2295
      End
      Begin VB.Label Label4 
         Alignment       =   2  'Center
         AutoSize        =   -1  'True
         Caption         =   "AbitibiBowater Outbound Tally"
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
         Left            =   990
         TabIndex        =   9
         Top             =   360
         Width           =   4275
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         Caption         =   "Customer Number"
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
         TabIndex        =   8
         Top             =   1800
         Width           =   1860
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         Caption         =   "Order Number"
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
         TabIndex        =   7
         Top             =   1080
         Width           =   1470
      End
   End
End
Attribute VB_Name = "frmPaperOutbound"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim strBarcode As String
Dim strMark As String
Dim dWT As Double
Dim strQTY As String
Dim strRolls As String
Dim strDamage As String
Dim strChecker As String
Dim strContainer As String
Dim dTotalWeight As Double
Dim dTotalPallets As Double
Dim dTotalRolls As Double

Private Sub cmdClose_Click()
    End
End Sub

Private Sub cmdPrint_Click()
    ' I place the database definition files within this print routine, so that when the routine
    ' ends, it closes the connections along with the routine.  this prevents the Oracle Timeout error
    ' that occurs if the screen is left open for several hours.
    
    ' in order to facilitate this, and with minimal time to try new methods, I am canning all subroutines.
    ' Start to finish, the print button works in this one module.
    
    If txtOrderNum.Text = "" Or txtCustNum.Text = "" Then
        MsgBox "Both fields need to be entered"
        Exit Sub
    End If
    
    txtOrderNum.Text = UCase$(txtOrderNum.Text)

'    Dim OraSession As Object
'    Dim OraDatabase As Object
    
    On Error GoTo ErrHandler
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Set OraDatabase = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)
    Set OraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/owner", 0&)
    If OraDatabase.LastServerErr <> 0 Then
        MsgBox "Database connection could not be made.  Please Contact TS."
        End
    End If

    strSql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & txtCustNum.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If IsNull(dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value) Then
        MsgBox "Entered Customer (" & txtCustNum.Text & ") was not found in system."
        Exit Sub
    End If
    strCustName = dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value

    strSql = "SELECT To_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') END_TIME " _
            & "FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtOrderNum.Text & "' " _
            & "AND CUSTOMER_ID = '" & txtCustNum.Text & "' " _
            & "AND PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE CARGO_DESCRIPTION LIKE '%" & txtCode & "%')"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If IsNull(dsSHORT_TERM_DATA.Fields("START_TIME").Value) Then
        MsgBox "Entered Order (" & txtOrderNum.Text & ") Has no records of activity in the system."
        Exit Sub
    End If
    strStartTime = dsSHORT_TERM_DATA.Fields("START_TIME").Value
    strEndTime = dsSHORT_TERM_DATA.Fields("END_TIME").Value
    
', EMPLOYEE PE      , NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), 'NONE') THE_LOGIN        AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PE.EMPLOYEE_ID(+), -4)
    strSql = "SELECT CT.PALLET_ID THE_PALLET, CT.CARGO_DESCRIPTION THE_MARK, CT.QTY_RECEIVED THE_REC, CT.QTY_UNIT THE_ROLLS, CA.ARRIVAL_NUM, CA.ACTIVITY_NUM, " _
            & "CT.WEIGHT THE_WEIGHT, DECODE(TO_CHAR(QTY_DAMAGED), '0', 'N', 'Y') THE_DMG, CONTAINER_ID " _
            & "FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA " _
            & "WHERE CT.PALLET_ID = CA.PALLET_ID AND CA.CUSTOMER_ID = CT.RECEIVER_ID  " _
            & "AND CA.SERVICE_CODE = '6' " _
            & "AND CA.ORDER_NUM = '" & txtOrderNum.Text & "' " _
            & "AND CT.ARRIVAL_NUM = '4' " _
            & "AND CT.RECEIVER_ID = '" & txtCustNum.Text & "' " _
            & "AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION LIKE 'DMG%') " _
            & "AND CARGO_DESCRIPTION LIKE '%" & txtCode & "%' " _
            & "AND CA.QTY_CHANGE != 0 " _
            & "ORDER BY CT.CONTAINER_ID, CT.CARGO_DESCRIPTION, CT.PALLET_ID"
    Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

    If dsMAIN_DATA.RecordCount = 0 Then
        MsgBox "No records for this Order / Customer combination"
        OraDatabase.Close
'        OraSession.Close
        Set OraDatabase = Nothing
        Set OraSession = Nothing
        Exit Sub
    Else
        dTotalWeight = 0
        dTotalPallets = 0
        dTotalRolls = 0
        
        
        
            ' go to printer
            If LCase$(Environ$("USERNAME")) = "tally" Then
                Printer.Copies = 2
            Else
                Printer.Copies = 1
            End If
    
            '' Page Header
            Printer.Font = "Arial"
            Printer.FontBold = True
            Printer.Print ""
            Printer.FontSize = 11
            Printer.FontBold = True
            Printer.Print Tab(20); "PORT OF WILMINGTON TALLY - COMMERCIAL PAPER (Outbound)"
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(10); "CUSTOMER: " & strCustName; Tab(65); "START TIME: " & strStartTime
            Printer.Print Tab(10); "CONTAINER: " & txtOrderNum.Text; Tab(65); "END TIME: " & strEndTime
            Printer.Print ""
            Printer.FontSize = 10
            Printer.FontBold = False
            Printer.Print "BARCODE"; Tab(23); "MARK"; Tab(58); "QTY"; Tab(64); "RLS"; Tab(70); "WT"; Tab(80); "CHECKER"; Tab(96); "DMG"; Tab(105); "IB-CONTAINER"
            Printer.Print Tab(64); "/PK"
            Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"
            While (Not dsMAIN_DATA.EOF)

                strBarcode = dsMAIN_DATA.Fields("THE_PALLET").Value
                strMark = dsMAIN_DATA.Fields("THE_MARK").Value
                dWT = dsMAIN_DATA.Fields("THE_WEIGHT").Value
                strQTY = dsMAIN_DATA.Fields("THE_REC").Value
                strRolls = dsMAIN_DATA.Fields("THE_ROLLS").Value
                strDamage = dsMAIN_DATA.Fields("THE_DMG").Value
'                strChecker = dsMAIN_DATA.Fields("THE_LOGIN").Value
                strChecker = get_checker_name(strBarcode, txtCustNum.Text, dsMAIN_DATA.Fields("ARRIVAL_NUM").Value, dsMAIN_DATA.Fields("ACTIVITY_NUM").Value)
                strContainer = dsMAIN_DATA.Fields("CONTAINER_ID").Value

                
                Printer.Print strBarcode; Tab(23); strMark; Tab(60); strQTY; Tab(65); strRolls; _
                Tab(70); dWT; Tab(80); strChecker; Tab(97); strDamage; Tab(105); strContainer

                
                dsMAIN_DATA.MoveNext
                dTotalPallets = dTotalPallets + 1
                dTotalWeight = dTotalWeight + dWT
                dTotalRolls = dTotalRolls + Val(strRolls)
            Wend
            
            
            Printer.Print Tab(1); "====================================================================================================================================================="
            Printer.FontBold = True
'            Printer.Print Tab(45); "Total Cases:"; Tab(65); dTotalCases
            Printer.Print Tab(5); "Totals:"; Tab(40); dTotalPallets & " ROLLS"; Tab(65); dTotalWeight & " KG"
'            Printer.Print Tab(45); "Total Weight:"; Tab(65); dTotalWeight; " KG"
            
            
'            strSql = "SELECT CT.PALLET_ID THE_PALLET, CT.CARGO_DESCRIPTION THE_MARK, CT.QTY_RECEIVED THE_REC, CT.QTY_UNIT THE_ROLLS, " _
'                    & "CT.WEIGHT THE_WEIGHT " _
'                    & "FROM CARGO_TRACKING CT " _
'                    & "WHERE CT.PALLET_ID NOT IN " _
'                    & "(SELECT PALLET_ID FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtOrderNum & "' " _
'                    & "AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL)) " _
'                    & "AND CT.ARRIVAL_NUM = '4' " _
'                    & "AND CT.CONTAINER_ID = '" & txtOrderNum.Text & "' " _
'                    & "AND CT.RECEIVER_ID = '" & txtCustNum.Text & "'"
'            Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'
'            If dsMAIN_DATA.RecordCount > 0 Then
'                dTotalWeight = 0
'                dTotalPallets = 0
'
'                Printer.NewPage
'                Printer.Font = "Arial"
'                Printer.FontBold = True
'                Printer.Print ""
'                Printer.FontSize = 11
'                Printer.FontBold = True
'                Printer.Print Tab(10); "PORT OF WILMINGTON TALLY - COMMERCIAL PAPER (Outbound) - UNSCANNED CARGO"
'                Printer.Print ""
'                Printer.Print ""
'                Printer.Print Tab(10); "CUSTOMER: " & strCustName
'                Printer.Print Tab(10); "ORDER NUMBER: " & txtOrderNum.Text
'                Printer.Print ""
'                Printer.FontSize = 10
'                Printer.FontBold = False
'                Printer.Print "BARCODE"; Tab(20); "MARK"; Tab(60); "QTY"; Tab(70); "ROLLS"; Tab(80); "WEIGHT"
'                Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"
'
'                While (Not dsMAIN_DATA.EOF)
'                    strBarcode = dsMAIN_DATA.Fields("THE_PALLET").Value
'                    strMark = dsMAIN_DATA.Fields("THE_MARK").Value
'                    dWT = dsMAIN_DATA.Fields("THE_WEIGHT").Value
'                    strQTY = dsMAIN_DATA.Fields("THE_REC").Value
'                    strRolls = dsMAIN_DATA.Fields("THE_ROLLS").Value
'                    Printer.Print strBarcode; Tab(20); strMark; Tab(60); strQTY; Tab(70); strRolls; Tab(80); dWT
'
'
'                    dsMAIN_DATA.MoveNext
'                    dTotalPallets = dTotalPallets + 1
'                    dTotalWeight = dTotalWeight + dWT
'                Wend
'
'                Printer.Print Tab(1); "====================================================================================================================================================="
'                Printer.FontBold = True
'                Printer.Print Tab(5); "Totals:"; Tab(56); dTotalPallets & " PLTS"; Tab(75); dTotalWeight & " KG"
''                Printer.Print Tab(45); "Total Weight:"; Tab(65); dTotalWeight; " KG"
'            End If
               
        
            Printer.EndDoc
            
        ' one way or the other, output is done.
    End If
    
    Call ClearAllFields
    OraDatabase.Close
    Set OraDatabase = Nothing
    Set OraSession = Nothing
    
    GoTo exitsub
    
ErrHandler:
    End


exitsub:
End Sub



Private Sub Form_Load()
    ' do nothing special
End Sub

Sub ClearAllFields()

    frmPaperOutbound.txtCustNum.Text = ""
    frmPaperOutbound.txtOrderNum.Text = ""
    frmPaperOutbound.txtCode.Text = ""

End Sub

Function get_checker_name(Barcode As String, cust As String, LR As String, act_num As String) As String
    Dim ActDate As String
    Dim empno As String
    Dim strSql As String
    
    strSql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID " _
            & "FROM CARGO_ACTIVITY " _
            & "WHERE PALLET_ID = '" & Barcode & "' " _
            & "AND ARRIVAL_NUM = '" & LR & "' " _
            & "AND CUSTOMER_ID = '" & cust & "' " _
            & "AND ACTIVITY_NUM = '" & act_num & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    ActDate = dsSHORT_TERM_DATA.Fields("THE_DATE").Value
    empno = dsSHORT_TERM_DATA.Fields("ACTIVITY_ID").Value
    
    If IsNull(empno) Or empno = "" Then
        get_checker_name = "UNKNOWN"
        Exit Function
    End If
    
    strSql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE WHERE CHANGE_DATE >= TO_DATE('" & ActDate & "', 'MM/DD/YYYY')"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value >= 1 Then
        strSql = "SELECT LOGIN_ID THE_EMP FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '" & empno & "'"
    Else
'        get_checker_name = empno
'        Exit Function
        While Len(empno) < 5
            empno = "0" & empno
        Wend
'        get_checker_name = empno
'        Exit Function
        strSql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP FROM EMPLOYEE WHERE SUBSTR(EMPLOYEE_ID, -" & Len(empno) & ") = '" & empno & "'"
    End If
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    get_checker_name = dsSHORT_TERM_DATA.Fields("THE_EMP").Value
    
End Function



