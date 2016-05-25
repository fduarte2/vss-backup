VERSION 5.00
Begin VB.Form frmDolePaperInbound 
   Caption         =   "Dole Paper (Inbound) Tally"
   ClientHeight    =   7080
   ClientLeft      =   5145
   ClientTop       =   4500
   ClientWidth     =   7350
   LinkTopic       =   "Form1"
   ScaleHeight     =   7080
   ScaleWidth      =   7350
   StartUpPosition =   2  'CenterScreen
   Begin VB.Frame Frame2 
      Height          =   1815
      Left            =   240
      TabIndex        =   7
      Top             =   5040
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
      Height          =   4815
      Left            =   240
      TabIndex        =   6
      Top             =   120
      Width           =   6615
      Begin VB.TextBox txtEndDate 
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
         Left            =   4080
         TabIndex        =   3
         Top             =   4080
         Width           =   2175
      End
      Begin VB.TextBox txtStartDate 
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
         Left            =   360
         TabIndex        =   2
         Top             =   4080
         Width           =   2175
      End
      Begin VB.TextBox txtDockTicket 
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
         Left            =   2040
         TabIndex        =   1
         Top             =   2520
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
         Left            =   2040
         TabIndex        =   0
         Top             =   1080
         Width           =   2655
      End
      Begin VB.Label Label6 
         Alignment       =   2  'Center
         Caption         =   "Receive Dates:"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   2040
         TabIndex        =   13
         Top             =   3600
         Width           =   2655
      End
      Begin VB.Label Label5 
         Alignment       =   2  'Center
         Caption         =   "Through"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   2640
         TabIndex        =   12
         Top             =   4200
         Width           =   1335
      End
      Begin VB.Label Label3 
         Alignment       =   2  'Center
         Caption         =   "--- OR ---"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   2040
         TabIndex        =   11
         Top             =   1725
         Width           =   2655
      End
      Begin VB.Label Label2 
         Alignment       =   2  'Center
         Caption         =   "Dock Ticket"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   2160
         TabIndex        =   10
         Top             =   2160
         Width           =   2535
      End
      Begin VB.Label Label4 
         Alignment       =   2  'Center
         AutoSize        =   -1  'True
         Caption         =   "Dole Paper Inbound Tally"
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
         Left            =   1560
         TabIndex        =   9
         Top             =   360
         Width           =   3585
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         Caption         =   "Order (Railcar) Number"
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
         Left            =   2160
         TabIndex        =   8
         Top             =   720
         Width           =   2430
      End
   End
End
Attribute VB_Name = "frmDolePaperInbound"
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

' these are for the damage recap, if needed
' id rather redifne them for ease of reading than use the above defined variables
Dim strRoll As String
Dim strDMGtype As String
Dim strOcurred As String
Dim strRecorded As String
Dim strQuan As String
Dim strCleared As String
Dim strDockTicket As String
Dim strCustomer As String
Dim strLinearFeet As String
Dim dMSF As Double ' Mean Square Feet
Dim dTotalMSF As Double
Dim strCargoSize As String
Dim firstrun As Boolean
Dim strFromMill As String


Private Sub cmdClose_Click()
    End
End Sub

Private Sub cmdPrint_Click()
    ' I place the database definition files within this print routine, so that when the routine
    ' ends, it closes the connections along with the routine.  this prevents the Oracle Timeout error
    ' that occurs if the screen is left open for several hours.
    
    ' in order to facilitate this, and with minimal time to try new methods, I am canning all subroutines.
    ' Start to finish, the print button works in this one module.
    
    If txtOrderNum.Text = "" And txtDockTicket.Text = "" Then
        MsgBox "Enter either Order or Ticket number"
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
    
    If txtOrderNum.Text = "" And txtDockTicket.Text <> "" Then
        ' auto-fill in the order (railcar) number; that way, i dont have to change any of the rest of the code
        ' to accomodate the OPS request to "not require entry of railcar"...
        strSql = "SELECT ARRIVAL_NUM FROM CARGO_TRACKING WHERE REMARK = 'DOLEPAPERSYSTEM' AND BOL = '" & txtDockTicket.Text & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        If dsSHORT_TERM_DATA.RecordCount > 0 Then
            txtOrderNum.Text = dsSHORT_TERM_DATA.Fields("ARRIVAL_NUM").Value
        Else
            MsgBox "Invalid Ticket Number."
            Exit Sub
        End If
    End If

'    strSql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & txtCustNum.Text & "'"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'    If IsNull(dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value) Then
'        MsgBox "Entered Customer (" & txtCustNum.Text & ") was not found in system."
'        Exit Sub
'    End If
'    strCustName = dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value

    strSql = "SELECT To_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') END_TIME " _
            & "FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtOrderNum.Text & "' " _
            & " AND DATE_OF_ACTIVITY >= TO_DATE('" & txtStartDate.Text & "', 'MM/DD/YYYY') " _
            & " AND DATE_OF_ACTIVITY <= TO_DATE('" & txtEndDate.Text & " 23:59:59', 'MM/DD/YYYY HH24:MI:SS') "
    If (txtDockTicket <> "") Then
        strSql = strSql & "AND BATCH_ID = '" & txtDockTicket & "' "
    End If
'            & "AND PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE CARGO_DESCRIPTION LIKE '%" & txtCode & "%')"
'            & "AND CUSTOMER_ID = '" & txtCustNum.Text & "' "
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If IsNull(dsSHORT_TERM_DATA.Fields("START_TIME").Value) Then
        MsgBox "Entered Order (" & txtOrderNum.Text & ") Has no records of activity in the system fo the date range selected."
        Exit Sub
    End If
    strStartTime = dsSHORT_TERM_DATA.Fields("START_TIME").Value
    strEndTime = dsSHORT_TERM_DATA.Fields("END_TIME").Value
    
    ' CT.QTY_UNIT THE_ROLLS, "
    ' & "AND CT.RECEIVER_ID = '" & txtCustNum.Text & "' "
    ' & "AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL) "
    ' & "AND CA.QTY_CHANGE != 0 "
    
    firstrun = True
    
    ' location-based copy count
    If LCase$(Environ$("USERNAME")) = "tally" Then
        Printer.Copies = 2
    Else
        Printer.Copies = 1
    End If
    
'            & "AND CT.DATE_RECEIVED < SYSDATE "
    strSql = "SELECT DISTINCT BOL, RECEIVER_ID FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA " _
            & "WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ORDER_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID " _
            & "AND CA.SERVICE_CODE IN (1, 8) " _
            & "AND CA.ORDER_NUM = '" & txtOrderNum.Text & "' " _
            & "AND CT.ARRIVAL_NUM = '" & txtOrderNum.Text & "' " _
            & " AND DATE_OF_ACTIVITY >= TO_DATE('" & txtStartDate.Text & "', 'MM/DD/YYYY') " _
            & " AND DATE_OF_ACTIVITY <= TO_DATE('" & txtEndDate.Text & " 23:59:59', 'MM/DD/YYYY HH24:MI:SS') "
            
    If (txtDockTicket <> "") Then
        strSql = strSql & "AND BOL = '" & txtDockTicket & "' "
    End If
    
    strSql = strSql & "ORDER BY BOL"
    Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

    If dsMAIN_DATA.RecordCount = 0 Then
        MsgBox "No records for this Order"
        OraDatabase.Close
'        OraSession.Close
        Set OraDatabase = Nothing
        Set OraSession = Nothing
        Exit Sub
    Else
        While (Not dsMAIN_DATA.EOF)
            dTotalMSF = 0
            dTotalWeight = 0
            dTotalTons = 0
            dTotalPallets = 0
            bAnyDamage = False

            ' not very eloquent, but same customer should hold for whole order, so the first value will work fine.
            strSql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & dsMAIN_DATA.Fields("RECEIVER_ID").Value & "'"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
            strCustomer = dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value
            
            If firstrun Then
                firstrun = False
            Else
                Printer.NewPage
            End If
            
            ' ADAM WALTER Feb 2010.  Also not very eloquent, but same "from mill" should hold for all order, so first value will work fine.
            strFromMill = ""
            strSql = "SELECT DOLEPAPER_ORIGINAL_MILL FROM CARGO_TRACKING_ADDITIONAL_DATA WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) IN " _
                & "(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM CARGO_TRACKING WHERE BOL = '" & dsMAIN_DATA.Fields("BOL").Value & "')"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
            If Not IsNull(dsSHORT_TERM_DATA.Fields("DOLEPAPER_ORIGINAL_MILL").Value) Then
                strSql = "SELECT * FROM DOLEPAPER_EDI_MILL_CODES WHERE MILL_ID = '" & dsSHORT_TERM_DATA.Fields("DOLEPAPER_ORIGINAL_MILL").Value & "'"
                Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
                If Not IsNull(dsSHORT_TERM_DATA.Fields("MILL_ID").Value) Then
                    strFromMill = dsSHORT_TERM_DATA.Fields("MILL_ID").Value & " - " & dsSHORT_TERM_DATA.Fields("MILL_NAME").Value
                End If
            End If

    
            '' Page Header
            Printer.Font = "Arial"
            Printer.FontBold = True
            Printer.Print ""
            Printer.FontSize = 11
            Printer.FontBold = True
            Printer.Print Tab(20); "PORT OF WILMINGTON TALLY - DOCK TICKET PAPER (Inbound)"
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(10); "ORDER NUMBER: " & txtOrderNum.Text; Tab(70); "START TIME: " & strStartTime
            Printer.Print Tab(10); "CUSTOMER: " & strCustomer; Tab(70); "END TIME: " & strEndTime
            If (strFromMill = "") Then
                Printer.Print ""
            Else
                Printer.Print Tab(10); "From:  " & strFromMill
            End If
            Printer.FontSize = 8
            Printer.FontBold = False
            Printer.Print "TICKET#"; Tab(15); "BARCODE"; Tab(40); "MARK"; Tab(80); "QTY"; Tab(90); "WEIGHT"; Tab(103); "(ST)"; Tab(113); "MSF"; Tab(125); "CHECKER"; Tab(145); "DMG"
            Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"
            
'                    & "AND CT.DATE_RECEIVED < SYSDATE "
' EMPLOYEE PE,      NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), 'NONE') THE_LOGIN,     AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PE.EMPLOYEE_ID(+), -4)
            strSql = "SELECT CT.PALLET_ID THE_PALLET, CT.CARGO_DESCRIPTION THE_MARK, CT.QTY_RECEIVED THE_REC, NVL(VARIETY, '0') THE_LINEAR_FEET, CARGO_SIZE, CA.CUSTOMER_ID, ACTIVITY_NUM, CA.ARRIVAL_NUM, " _
                    & "CT.WEIGHT THE_WEIGHT, CT.WEIGHT_UNIT THE_WT_UNIT, CT.RECEIVER_ID REC_ID, ROUND(CT.WEIGHT * UC.CONVERSION_FACTOR, 1) WEIGHT_TON " _
                    & "FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA, UNIT_CONVERSION_FROM_BNI UC " _
                    & "WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ORDER_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID " _
                    & "AND CT.WEIGHT_UNIT = UC.PRIMARY_UOM AND UC.SECONDARY_UOM = 'TON' " _
                    & "AND CA.SERVICE_CODE IN (1, 8) " _
                    & "AND CT.BOL = '" & dsMAIN_DATA.Fields("BOL").Value & "' " _
                    & "AND CA.ORDER_NUM = '" & txtOrderNum.Text & "' " _
                    & "AND CT.ARRIVAL_NUM = '" & txtOrderNum.Text & "' " _
                    & " AND DATE_OF_ACTIVITY >= TO_DATE('" & txtStartDate.Text & "', 'MM/DD/YYYY') " _
                    & " AND DATE_OF_ACTIVITY <= TO_DATE('" & txtEndDate.Text & " 23:59:59', 'MM/DD/YYYY HH24:MI:SS') "
            strSql = strSql & "ORDER BY CT.PALLET_ID"
                    
            Set dsPER_DOCKTICKET = OraDatabase.DbCreateDynaset(strSql, 0&)
        
            While (Not dsPER_DOCKTICKET.EOF)

                strCargoSize = dsPER_DOCKTICKET.Fields("CARGO_SIZE").Value
                strLinearFeet = dsPER_DOCKTICKET.Fields("THE_LINEAR_FEET").Value
                strWTunit = dsPER_DOCKTICKET.Fields("THE_WT_UNIT").Value
                strTicket = dsMAIN_DATA.Fields("BOL").Value
                strBarcode = dsPER_DOCKTICKET.Fields("THE_PALLET").Value
                strMark = dsPER_DOCKTICKET.Fields("THE_MARK").Value
                dWT = dsPER_DOCKTICKET.Fields("THE_WEIGHT").Value
                dTON = dsPER_DOCKTICKET.Fields("WEIGHT_TON").Value
                strQTY = dsPER_DOCKTICKET.Fields("THE_REC").Value
'                strRolls = dsMAIN_DATA.Fields("THE_ROLLS").Value
'                strDamage = dsMAIN_DATA.Fields("THE_DMG").Value
'                strChecker = dsPER_DOCKTICKET.Fields("THE_LOGIN").Value
                strChecker = get_checker_name(strBarcode, dsPER_DOCKTICKET.Fields("CUSTOMER_ID").Value, dsPER_DOCKTICKET.Fields("ARRIVAL_NUM").Value, dsPER_DOCKTICKET.Fields("ACTIVITY_NUM").Value)
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

                
                Printer.Print strTicket; Tab(15); strBarcode; Tab(40); strMark; Tab(80); strQTY; _
                Tab(90); dWT & " " & strWTunit; Tab(103); dTON; Tab(113); dMSF; Tab(125); strChecker; Tab(145); strDamage

                
                dsPER_DOCKTICKET.MoveNext
                dTotalPallets = dTotalPallets + 1
                dTotalWeight = dTotalWeight + dWT
                dTotalTons = dTotalTons + dTON
'                dTotalRolls = dTotalRolls + Val(strRolls)
            Wend
            
            
            Printer.Print Tab(1); "====================================================================================================================================================="
            Printer.FontBold = True
'            Printer.Print Tab(45); "Total Cases:"; Tab(65); dTotalCases
            Printer.Print Tab(5); "Totals:"; Tab(72); dTotalPallets; Tab(82); dTotalWeight & " " & strWTunit; Tab(96); dTotalTons; Tab(107); dTotalMSF
'            Printer.Print Tab(45); "Total Weight:"; Tab(65); dTotalWeight; " KG"
            
            
            strSql = "SELECT CT.PALLET_ID THE_PALLET, CT.CARGO_DESCRIPTION THE_MARK, CT.QTY_RECEIVED THE_REC, CT.QTY_UNIT THE_ROLLS, " _
                    & "CT.WEIGHT THE_WEIGHT, CT.WEIGHT_UNIT THE_WT_UNIT, NVL(VARIETY, '0') THE_LINEAR_FEET, CARGO_SIZE, ROUND(CT.WEIGHT * UC.CONVERSION_FACTOR, 1) WEIGHT_TON  " _
                    & "FROM CARGO_TRACKING CT, UNIT_CONVERSION_FROM_BNI UC " _
                    & "WHERE CT.ARRIVAL_NUM = '" & txtOrderNum.Text & "' " _
                    & "AND CT.WEIGHT_UNIT = UC.PRIMARY_UOM AND UC.SECONDARY_UOM = 'TON' " _
                    & "AND BOL = '" & dsMAIN_DATA.Fields("BOL").Value & "' " _
                    & "AND DATE_RECEIVED IS NULL " _
                    & "ORDER BY CT.PALLET_ID"
            Set dsUNSCANNED = OraDatabase.DbCreateDynaset(strSql, 0&)
            
            If dsUNSCANNED.RecordCount > 0 Then
                dTotalMSF = 0
                dTotalWeight = 0
                dTotalTons = 0
                dTotalPallets = 0
'                dTotalRolls = 0
                            
                Printer.NewPage
                Printer.Font = "Arial"
                Printer.FontBold = True
                Printer.Print ""
                Printer.FontSize = 11
                Printer.FontBold = True
                Printer.Print Tab(10); "PORT OF WILMINGTON TALLY - DOCK TICKET PAPER (Inbound) - UNSCANNED CARGO"
                Printer.Print ""
                Printer.Print ""
                Printer.Print Tab(10); "ORDER NUMBER: " & txtOrderNum.Text
                Printer.Print ""
                Printer.FontSize = 10
                Printer.FontBold = False
                Printer.Print "TICKET#"; Tab(15); "BARCODE"; Tab(40); "MARK"; Tab(80); "QTY"; Tab(90); "WEIGHT"; Tab(103); "(ST)"; Tab(113); "MSF"
                Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"
                
                While (Not dsUNSCANNED.EOF)
                    strCargoSize = dsUNSCANNED.Fields("CARGO_SIZE").Value
                    strLinearFeet = dsUNSCANNED.Fields("THE_LINEAR_FEET").Value
                    strTicket = dsMAIN_DATA.Fields("BOL").Value
                    dMSF = Round((Val(strLinearFeet) * (Val(strCargoSize) / 12)) / 1000, 1)
                    dTON = dsUNSCANNED.Fields("WEIGHT_TON").Value
                    dTotalMSF = dTotalMSF + dMSF
                    strWTunit = dsUNSCANNED.Fields("THE_WT_UNIT").Value
                    strBarcode = dsUNSCANNED.Fields("THE_PALLET").Value
                    strMark = dsUNSCANNED.Fields("THE_MARK").Value
                    dWT = dsUNSCANNED.Fields("THE_WEIGHT").Value
                    strQTY = dsUNSCANNED.Fields("THE_REC").Value
'                   strRolls = dsMAIN_DATA.Fields("THE_ROLLS").Value
                    Printer.Print strTicket; Tab(15); strBarcode; Tab(40); strMark; Tab(80); strQTY; Tab(90); dWT & " " & strWTunit; Tab(103); dTON; Tab(113); dMSF
    
                    
                    dsUNSCANNED.MoveNext
                    dTotalPallets = dTotalPallets + 1
                    dTotalWeight = dTotalWeight + dWT
                    dTotalTons = dTotalTons + dTON
'                    dTotalRolls = dTotalRolls + Val(strRolls)
                Wend
                
                Printer.Print Tab(1); "====================================================================================================================================================="
                Printer.FontBold = True
                Printer.Print Tab(5); "Totals:"; Tab(74); dTotalPallets; Tab(83); dTotalWeight & " " & strWTunit; Tab(96); dTotalTons; Tab(105); dTotalMSF
'                Printer.Print Tab(45); "Total Weight:"; Tab(65); dTotalWeight; " KG"
            End If
            
            If bAnyDamage = True Then
               ' damage recap, if applicable
                Printer.NewPage
               
                strSql = "SELECT ROLL, DOCK_TICKET, DMG_TYPE, OCCURRED, TO_CHAR(DATE_ENTERED, 'MM/DD/YYYY HH24:MI') WHEN_REC, " _
                    & "NVL(QUANTITY || QTY_TYPE, 'N/A') THE_QUAN, NVL(TO_CHAR(DATE_CLEARED, 'MM/DD/YYYY HH24:MI'), ' ') THE_CLEARED " _
                    & "FROM DOLEPAPER_DAMAGES WHERE ROLL IN " _
                    & "(SELECT PALLET_ID FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtOrderNum.Text & "' AND SERVICE_CODE = '8' AND ACTIVITY_DESCRIPTION IS NULL) " _
                    & "AND DOCK_TICKET = '" & dsMAIN_DATA.Fields("BOL").Value & "' " _
                    & "ORDER BY ROLL, DATE_ENTERED"
                Set dsDAMAGE_REPORT = OraDatabase.DbCreateDynaset(strSql, 0&)
                
                Printer.Print Tab(10); "PORT OF WILMINGTON TALLY - DOCK TICKET PAPER (Inbound) - DAMAGE HISTORY"
                Printer.Print ""
                Printer.Print ""
                Printer.Print Tab(10); "ORDER NUMBER: " & txtOrderNum.Text
                Printer.Print ""
                Printer.FontSize = 10
                Printer.FontBold = False
                Printer.Print "TICKET#"; Tab(15); "BARCODE"; Tab(40); "DMG TYPE"; Tab(55); "QTY"; Tab(70); "RECORDED"; Tab(90); "RESPONSIBLE"; Tab(110); "REJECT"
                Printer.Print Tab(110); "CLEARED"
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
            
            dsMAIN_DATA.MoveNext
        Wend
        
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
    Call ClearAllFields
End Sub

Sub ClearAllFields()

    frmDolePaperInbound.txtOrderNum.Text = ""
    frmDolePaperInbound.txtDockTicket.Text = ""

    frmDolePaperInbound.txtStartDate.Text = Format$(Now, "MM/DD/YYYY")
    frmDolePaperInbound.txtEndDate.Text = Format$(Now, "MM/DD/YYYY")

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




