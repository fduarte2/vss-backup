VERSION 5.00
Begin VB.Form frmDolePaperOutbound 
   Caption         =   "Dole Paper (Outbound) Tally"
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
         Left            =   3960
         TabIndex        =   2
         Top             =   2640
         Visible         =   0   'False
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
         Left            =   3960
         TabIndex        =   1
         Top             =   2280
         Visible         =   0   'False
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
         Left            =   1920
         TabIndex        =   0
         Top             =   1560
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
         Visible         =   0   'False
         Width           =   1935
      End
      Begin VB.Label Label4 
         Alignment       =   2  'Center
         AutoSize        =   -1  'True
         Caption         =   "Dole Paper Outbound Tally"
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
         Left            =   1215
         TabIndex        =   9
         Top             =   360
         Width           =   3825
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
         Left            =   0
         TabIndex        =   8
         Top             =   2760
         Visible         =   0   'False
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
         Left            =   2520
         TabIndex        =   7
         Top             =   1080
         Width           =   1470
      End
   End
End
Attribute VB_Name = "frmDolePaperOutbound"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim strBarcode As String
Dim strMark As String
Dim dWT As Double
Dim dTON As Double
Dim strWTunit As String
Dim strQTY As String
'Dim strRolls As String
Dim strDamage As String
Dim strChecker As String
Dim strTicket As String
Dim dTotalWeight As Double
Dim dTotalTons As Double
Dim dTotalPallets As Double
Dim bAnyDamage As Boolean
'Dim dTotalRolls As Double
Dim strIBArrival As String

' these are for the damage recap, if needed
' id rather redifne them for ease of reading than use the above defined variables
Dim strRoll As String
Dim strDMGtype As String
Dim strOcurred As String
Dim strRecorded As String
Dim strQuan As String
Dim strCleared As String
Dim strDockTicket As String
Dim strContainer As String
Dim strDestination As String
Dim strVessel As String
Dim strDestinationNB As Integer
Dim strLR As Integer
Dim strUC As String
Dim strSeal As String
Dim strBooking As String

Dim strLinearFeet As String
Dim dMSF As Double ' Mean Square Feet
Dim dTotalMSF As Double
Dim strCargoSize As String


Private Sub cmdClose_Click()
    End
End Sub

Private Sub cmdPrint_Click()
    ' I place the database definition files within this print routine, so that when the routine
    ' ends, it closes the connections along with the routine.  this prevents the Oracle Timeout error
    ' that occurs if the screen is left open for several hours.
    
    ' in order to facilitate this, and with minimal time to try new methods, I am canning all subroutines.
    ' Start to finish, the print button works in this one module.
    
    If txtOrderNum.Text = "" Then
        MsgBox "Order# needs to be entered"
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

'    strSql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & txtCustNum.Text & "'"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'    If IsNull(dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value) Then
'        MsgBox "Entered Customer (" & txtCustNum.Text & ") was not found in system."
'        Exit Sub
'    End If
'    strCustName = dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value

   
    strSql = "SELECT NVL(DOR.USER_COMMENTS, ' ') THE_CMNTS, NVL(SEAL, 'NONE') THE_SEAL, NVL(BOOKING_NUM, 'NONE') THE_BOOK, CONTAINER_ID, DESTINATION, DD.DESTINATION_NB, VESSEL_NAME, VP.LR_NUM FROM DOLEPAPER_ORDER DOR, DOLEPAPER_DESTINATIONS DD, VESSEL_PROFILE VP " _
            & "WHERE DOR.ORDER_NUM = '" & txtOrderNum.Text & "' AND DOR.DESTINATION_NB = DD.DESTINATION_NB " _
            & "AND DOR.ARRIVAL_NUM = VP.ARRIVAL_NUM"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    strContainer = "" & dsSHORT_TERM_DATA.Fields("CONTAINER_ID").Value
    strDestination = dsSHORT_TERM_DATA.Fields("DESTINATION").Value
    strVessel = dsSHORT_TERM_DATA.Fields("VESSEL_NAME").Value
    strDestinationNB = dsSHORT_TERM_DATA.Fields("DESTINATION_NB").Value
    strLR = dsSHORT_TERM_DATA.Fields("LR_NUM").Value
    strUC = dsSHORT_TERM_DATA.Fields("THE_CMNTS").Value
    strSeal = dsSHORT_TERM_DATA.Fields("THE_SEAL").Value
    strBooking = dsSHORT_TERM_DATA.Fields("THE_BOOK").Value
    
    strSql = "SELECT To_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') END_TIME " _
            & "FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT " _
            & "WHERE ORDER_NUM = '" & txtOrderNum.Text & "' " _
            & "AND CA.PALLET_ID = CT.PALLET_ID AND CA.CUSTOMER_ID = CT.RECEIVER_ID AND CA.ARRIVAL_NUM = CT.ARRIVAL_NUM AND CA.SERVICE_CODE = '6' " _
            & "AND CT.REMARK = 'DOLEPAPERSYSTEM' "
'            & "AND CUSTOMER_ID = '" & txtCustNum.Text & "' "
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If IsNull(dsSHORT_TERM_DATA.Fields("START_TIME").Value) Then
        MsgBox "Entered Order (" & txtOrderNum.Text & ") Has no records of activity in the system."
        Exit Sub
    End If
    strStartTime = dsSHORT_TERM_DATA.Fields("START_TIME").Value
    strEndTime = dsSHORT_TERM_DATA.Fields("END_TIME").Value
     
     ' copy count based on computer location
    If LCase$(Environ$("USERNAME")) = "tally" Then
        Printer.Copies = 2
    Else
        Printer.Copies = 1
    End If
    
    
    ' CT.QTY_UNIT THE_ROLLS, "
    ' & "AND CT.RECEIVER_ID = '" & txtCustNum.Text & "' "
    ' & "AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL) "
    ' & "AND CA.QTY_CHANGE != 0 "
    
'    strSql = "SELECT DISTINCT BOL FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA " _
'            & "WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID " _
'            & "AND CA.SERVICE_CODE IN (6) " _
'            & "AND (CA.ACTIVITY_DESCRIPTION IS NULL) " _
'            & "AND CA.ORDER_NUM = '" & txtOrderNum.Text & "' " _
'            & "ORDER BY BOL"
'    Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

' EMPLOYEE PE,      AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PE.EMPLOYEE_ID(+), -4)     NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), 'NONE') THE_LOGIN,
    strSql = "SELECT CT.BOL THE_BOL, CT.PALLET_ID THE_PALLET, CT.CARGO_DESCRIPTION THE_MARK, CT.QTY_RECEIVED THE_REC, CT.ARRIVAL_NUM, CA.CUSTOMER_ID, ACTIVITY_NUM, " _
            & "CT.WEIGHT THE_WEIGHT, CT.WEIGHT_UNIT THE_WT_UNIT,  NVL(VARIETY, '0') THE_LINEAR_FEET, CARGO_SIZE, ROUND(CT.WEIGHT * UC.CONVERSION_FACTOR, 1) WEIGHT_TON  " _
            & "FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA,  UNIT_CONVERSION_FROM_BNI UC " _
            & "WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID " _
            & "AND CT.WEIGHT_UNIT = UC.PRIMARY_UOM AND UC.SECONDARY_UOM = 'TON' " _
            & "AND CA.SERVICE_CODE IN (6) " _
            & "AND (CA.ACTIVITY_DESCRIPTION IS NULL) " _
            & "AND CA.ORDER_NUM = '" & txtOrderNum.Text & "' " _
            & "AND CT.REMARK = 'DOLEPAPERSYSTEM' " _
            & "ORDER BY CT.PALLET_ID"
    Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
            
            
    If dsMAIN_DATA.RecordCount = 0 Then
        MsgBox "No records for this Order"
        OraDatabase.Close
'        OraSession.Close
        Set OraDatabase = Nothing
        Set OraSession = Nothing
        Exit Sub
    Else
        dTotalMSF = 0
        dTotalWeight = 0
        dTotalTons = 0
        dTotalPallets = 0
        bAnyDamage = False
        
    
        '' Page Header
        Printer.Font = "Arial"
        Printer.FontBold = True
        Printer.Print ""
        Printer.FontSize = 11
        Printer.FontBold = True
        Printer.Print Tab(20); "PORT OF WILMINGTON TALLY - DOCK TICKET PAPER (Outbound)"
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(10); "ORDER NUMBER: " & txtOrderNum.Text; Tab(65); "DESTINATION: " & strDestination
        Printer.Print Tab(10); "VESSEL: " & strVessel; Tab(65); "START TIME: " & strStartTime
        Printer.Print Tab(10); "CONTAINER: " & strContainer; Tab(65); "END TIME: " & strEndTime
        Printer.Print Tab(10); "Booking #: " & strBooking; Tab(65); "SEAL#: " & strSeal
        Printer.Print ""
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print "TICKET#"; Tab(13); "BARCODE"; Tab(34); "MARK"; Tab(74); "QTY"; Tab(81); "WEIGHT"; Tab(94); "(ST)"; Tab(104); "CHECKER"; Tab(118); "MSF"; Tab(128); "DMG"; Tab(135); "IB-ORDER"
        Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"
        
        
        
        While (Not dsMAIN_DATA.EOF)

            strIBArrival = dsMAIN_DATA.Fields("ARRIVAL_NUM").Value
            strWTunit = dsMAIN_DATA.Fields("THE_WT_UNIT").Value
            strTicket = dsMAIN_DATA.Fields("THE_BOL").Value
            strBarcode = dsMAIN_DATA.Fields("THE_PALLET").Value
            strMark = dsMAIN_DATA.Fields("THE_MARK").Value
            dWT = dsMAIN_DATA.Fields("THE_WEIGHT").Value
            dTON = dsMAIN_DATA.Fields("WEIGHT_TON").Value
            strQTY = dsMAIN_DATA.Fields("THE_REC").Value
'            strChecker = dsMAIN_DATA.Fields("THE_LOGIN").Value
            strChecker = get_checker_name(strBarcode, dsMAIN_DATA.Fields("CUSTOMER_ID").Value, strIBArrival, dsMAIN_DATA.Fields("ACTIVITY_NUM").Value)
            
            strCargoSize = dsMAIN_DATA.Fields("CARGO_SIZE").Value
            strLinearFeet = dsMAIN_DATA.Fields("THE_LINEAR_FEET").Value
            dMSF = Round((Val(strLinearFeet) * (Val(strCargoSize) / 12)) / 1000, 3)
            dTotalMSF = dTotalMSF + dMSF
            
            strSql = "SELECT COUNT(*) THE_COUNT FROM DOLEPAPER_DAMAGES WHERE ROLL = '" & strBarcode & "' AND DOCK_TICKET = '" _
                    & strTicket & "'"
            Set dsDAMAGE_CHECK = OraDatabase.DbCreateDynaset(strSql, 0&)
            If dsDAMAGE_CHECK.Fields("THE_COUNT").Value > 0 Then
                strDamage = "Y"
            Else
                strDamage = "N"
            End If
            
            If (strDamage = "Y") Then
                bAnyDamage = True
            End If

            
            Printer.Print strTicket; Tab(13); strBarcode; Tab(34); strMark; Tab(74); strQTY; _
            Tab(81); dWT & " " & strWTunit; Tab(94); dTON; Tab(104); strChecker; Tab(118); dMSF; Tab(128); strDamage; Tab(135); strIBArrival

            
            dsMAIN_DATA.MoveNext
            dTotalPallets = dTotalPallets + 1
            dTotalWeight = dTotalWeight + dWT
            dTotalTons = dTotalTons + dTON
        Wend
        
        
        Printer.Print Tab(1); "====================================================================================================================================================="
        Printer.FontBold = True
        Printer.Print Tab(5); "Totals:"; Tab(67); dTotalPallets; Tab(74); dTotalWeight & " " & strWTunit; Tab(87); dTotalTons; Tab(111); dTotalMSF

        
        If dTotalWeight > 46300 Then
            Printer.Print Tab(35); "********ORDER OVER 46300 LBS.  CONTACT OFFICE********"
        End If
        If bAnyDamage = True Then
            Printer.FontSize = 10
           ' damage recap, if applicable
           
            strSql = "SELECT ROLL, DOCK_TICKET, DMG_TYPE, OCCURRED, TO_CHAR(DATE_ENTERED, 'MM/DD/YYYY HH24:MI') WHEN_REC, " _
                & "NVL(QUANTITY || QTY_TYPE, 'N/A') THE_QUAN, NVL(TO_CHAR(DATE_CLEARED, 'MM/DD/YYYY HH24:MI'), ' ') THE_CLEARED " _
                & "FROM DOLEPAPER_DAMAGES WHERE ROLL IN " _
                & "(SELECT PALLET_ID FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtOrderNum.Text & "' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION IS NULL) " _
                & "ORDER BY ROLL, DATE_ENTERED"
            Set dsDAMAGE_REPORT = OraDatabase.DbCreateDynaset(strSql, 0&)
            
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(10); "PORT OF WILMINGTON TALLY - DOCK TICKET PAPER (Outbound) - DAMAGE HISTORY"
            Printer.Print ""
            Printer.Print Tab(10); "ORDER NUMBER: " & txtOrderNum.Text
            Printer.Print ""
            Printer.FontSize = 8
            Printer.FontBold = False
            Printer.Print "TICKET#"; Tab(15); "BARCODE"; Tab(40); "DMG TYPE"; Tab(55); "QTY"; Tab(70); "RECORDED"; Tab(90); "RESPONSIBLE"; Tab(110); "REJECT CLEARED"
            Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"
    
            While (Not dsDAMAGE_REPORT.EOF)
                strDockTicket = dsDAMAGE_REPORT.Fields("DOCK_TICKET").Value
                strRoll = dsDAMAGE_REPORT.Fields("ROLL").Value
                strDMGtype = dsDAMAGE_REPORT.Fields("DMG_TYPE").Value
                strOcurred = dsDAMAGE_REPORT.Fields("OCCURRED").Value
                strRecorded = dsDAMAGE_REPORT.Fields("WHEN_REC").Value
                strQuan = dsDAMAGE_REPORT.Fields("THE_QUAN").Value
                If Not (IsNull(strQuan)) Then
                    Select Case strQuan
                        Case ".125IN"
                            strQuan = "1/8IN"
                        Case ".25IN"
                            strQuan = "1/4IN"
                        Case ".5IN"
                            strQuan = "1/2IN"
                        Case ".75IN"
                            strQuan = "3/4IN"
                        Case Else
                            ' do nothing
                    End Select
                End If
                strCleared = dsDAMAGE_REPORT.Fields("THE_CLEARED").Value
                
                Printer.Print strDockTicket; Tab(15); strRoll; Tab(40); strDMGtype; Tab(55); strQuan; Tab(70); strRecorded; Tab(90); strOcurred; Tab(110); strCleared
                dsDAMAGE_REPORT.MoveNext
            Wend
            ' no totals, but print the bottom line anyway
            Printer.Print Tab(1); "====================================================================================================================================================="
        End If
        
        strSql = "SELECT COUNT(*) THE_COUNT " _
            & "FROM LU_DOLE_DT_OUT_NOTES_PRINT " _
            & "WHERE DESTINATION_NB = '" & strDestinationNB & "' AND ARRIVAL_NUM = '" & strLR & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value >= 1 Then
            Printer.Print ""
            Printer.Print strUC
        End If
        
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

'    frmDolePaperInbound.txtCustNum.Text = ""
    frmDolePaperOutbound.txtOrderNum.Text = ""
'    frmDolePaperInbound.txtCode.Text = ""

End Sub


Function get_checker_name(Barcode As String, cust As String, LR As String, act_num As String) As String
    Dim ActDate As String
    Dim empno As String
    
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



