VERSION 5.00
Object = "{67397AA1-7FB1-11D0-B148-00A0C922E820}#6.0#0"; "MSADODC.OCX"
Begin VB.Form frmCLMChkTally 
   Caption         =   "Clementine Outbound Tally"
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
      Begin VB.TextBox txtFile 
         Height          =   285
         Left            =   360
         TabIndex        =   11
         Top             =   2880
         Visible         =   0   'False
         Width           =   2175
      End
      Begin VB.TextBox txtVslNum 
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
      Begin MSAdodcLib.Adodc dc 
         Height          =   375
         Left            =   4920
         Top             =   120
         Visible         =   0   'False
         Width           =   1575
         _ExtentX        =   2778
         _ExtentY        =   661
         ConnectMode     =   0
         CursorLocation  =   3
         IsolationLevel  =   -1
         ConnectionTimeout=   15
         CommandTimeout  =   30
         CursorType      =   3
         LockType        =   3
         CommandType     =   1
         CursorOptions   =   0
         CacheSize       =   50
         MaxRecords      =   0
         BOFAction       =   0
         EOFAction       =   0
         ConnectStringType=   1
         Appearance      =   1
         BackColor       =   -2147483643
         ForeColor       =   -2147483640
         Orientation     =   0
         Enabled         =   -1
         Connect         =   "Provider=MSDAORA.1;User ID=sag_owner;Data Source=rf;Persist Security Info=False"
         OLEDBString     =   "Provider=MSDAORA.1;User ID=sag_owner;Data Source=rf;Persist Security Info=False"
         OLEDBFile       =   ""
         DataSourceName  =   ""
         OtherAttributes =   ""
         UserName        =   "sag_owner"
         Password        =   "owner"
         RecordSource    =   "select count(*) from cargo_activity"
         Caption         =   "dc"
         BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         _Version        =   393216
      End
      Begin VB.Label Label4 
         AutoSize        =   -1  'True
         Caption         =   "Clementines Out-Bound Checker Tally"
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
         Left            =   600
         TabIndex        =   10
         Top             =   360
         Width           =   5370
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         Caption         =   "Vessel (LR) Number"
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
         TabIndex        =   9
         Top             =   2400
         Width           =   2100
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
Attribute VB_Name = "frmCLMChkTally"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim strBarcode As String
Dim strPKG As String
Dim strWT As String
Dim strBoL As String
Dim strSize As String
Dim strOrigCnt As String
Dim strActualCnt As String
Dim strStatus As String
Dim strDamage As String
Dim strContainer As String
Dim strChecker As String
Dim dTotalCases As Double
Dim dTotalPallets As Double
Dim sSignatureCheck As String
Dim temp() As String
Dim sSignatureSQLAddon As String







Private Sub cmdClose_Click()
    End
End Sub

Private Sub cmdPrint_Click()
    ' I place the database definition files within this print routine, so that when the routine
    ' ends, it closes the connections along with the routine.  this prevents the Oracle Timeout error
    ' that occurs if the screen is left open for several hours.
    
    ' in order to facilitate this, and with minimal time to try new methods, I am canning all subroutines.
    ' Start to finish, the print button works in this one module.
    
    If txtOrderNum.Text = "" Or txtCustNum.Text = "" Or txtVslNum.Text = "" Then
        MsgBox "All 3 fields need to be entered"
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
        If txtFile.Text = "" Then
             MsgBox "Database connection could not be made.  Please Contact TS."
        End If
        End
    End If

    strSql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_SHIP FROM VESSEL_PROFILE WHERE LR_NUM = '" & txtVslNum.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If IsNull(dsSHORT_TERM_DATA.Fields("THE_SHIP").Value) Then
        If txtFile.Text = "" Then
            MsgBox "Entered vessel (" & txtVslNum.Text & ") was not found in system."
        End If
        Exit Sub
    End If
    strVesselName = dsSHORT_TERM_DATA.Fields("THE_SHIP").Value
    strVesselNameOutput = strVesselName & "-" & txtVslNum.Text
    

'    strSql = "SELECT PORT_COMMODITY_CODE || '-' || DC_COMMODITY_NAME THE_COMM FROM DC_EPORT_COMMODITY WHERE PORT_COMMODITY_CODE = " _
'            & "(SELECT COMMODITYCODE FROM DC_ORDER WHERE ORDERNUM = '" & txtOrderNum.Text & "')"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'    strCommodityName = dsSHORT_TERM_DATA.Fields("THE_COMM").Value

    strSql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & txtCustNum.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If IsNull(dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value) Then
        If txtFile.Text = "" Then
            MsgBox "Entered Customer (" & txtCustNum.Text & ") was not found in system."
        End If
        Exit Sub
    End If
    strCustName = dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value

    strSql = "SELECT To_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') END_TIME " _
            & "FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtOrderNum.Text & "' AND ARRIVAL_NUM = '" & txtVslNum.Text & "' " _
            & "AND CUSTOMER_ID = '" & txtCustNum.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If IsNull(dsSHORT_TERM_DATA.Fields("START_TIME").Value) Then
        If txtFile.Text = "" Then
            MsgBox "Entered Order (" & txtOrderNum.Text & ") Has no records of activity in the system."
        End If
        Exit Sub
    End If
    strStartTime = dsSHORT_TERM_DATA.Fields("START_TIME").Value
    strEndTime = dsSHORT_TERM_DATA.Fields("END_TIME").Value
    
    ' this next if looks weird, but:  basically, for domestic product, the vessel num is the customer num.
    ' and since domestic product comes from one table, and export from another...
    If txtCustNum.Text <> txtVslNum.Text Then
        sSignatureSQLAddon = "DC_PICKLIST"
    Else
        sSignatureSQLAddon = "DC_DOMESTIC_PICKLIST"
    End If
    
    ' it has been noted that sometimes, no picklist exists for orders.  If this is the case,
    ' set variable here, to prepare for footnote printing.
    sSignatureCheck = "UNSET"
    strSql = "SELECT COUNT(*) THE_COUNT FROM " & sSignatureSQLAddon & " WHERE ORDERNUM = '" & txtOrderNum.TabIndex & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value = 0 Then
        sSignatureCheck = "NOPICK"
    End If

' , EMPLOYEE PE     , NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), 'NONE') THE_LOGIN        AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PE.EMPLOYEE_ID(+), -4)
    strSql = "SELECT CA.ACTIVITY_NUM, CT.PALLET_ID THE_PALLET, CT.EXPORTER_CODE THE_PKG, CT.WEIGHT || CT.WEIGHT_UNIT THE_WEIGHT, CT.CARGO_SIZE THE_SIZE, " _
            & "CT.BATCH_ID THE_ORIG, CA.QTY_CHANGE THE_CHANGE, CA.SERVICE_CODE THE_SERVICE, CT.CARGO_STATUS THE_STATUS, " _
            & "NVL(CA.BATCH_ID, '0') THE_BATCH, CT.CONTAINER_ID THE_CONTAINER " _
            & "FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA " _
            & "WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID " _
            & "AND CA.SERVICE_CODE IN (6, 7, 13) " _
            & "AND CA.ORDER_NUM = '" & txtOrderNum.Text & "' " _
            & "AND CT.ARRIVAL_NUM = '" & txtVslNum.Text & "'  " _
            & "AND CT.RECEIVER_ID = '" & txtCustNum.Text & "' " _
            & "AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL)" _
            & "AND CA.QTY_CHANGE != 0 " _
            & "ORDER BY CT.EXPORTER_CODE, CT.PALLET_ID, CA.ACTIVITY_NUM"
    Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

    If dsMAIN_DATA.RecordCount = 0 Then
        MsgBox "No records for this Order / Customer / Vessel combination"
        OraDatabase.Close
'        OraSession.Close
        Set OraDatabase = Nothing
        Set OraSession = Nothing
        Exit Sub
    Else
        dTotalCases = 0
        dTotalPallets = 0
        
'        txtFile.Text = "C:\ePortFormDocuments\" & strVesselNameOutput & "\" & txtOrderNum.Text & "_Form4_TallySheet.csv"
    
    
        ' start outputting data.  There are 2 possible places the data can go.
        If txtFile.Text <> "" Then
            If Right(txtFile.Text, 3) <> "csv" Then
                GoTo exitsub
            End If
            
            ' go to file
            Open txtFile.Text For Output As #1

            Print #1, ""
            Print #1, ""
            Print #1, "PORT OF WILMINGTON TALLY"
            Print #1, ""
            Print #1, ""
            Print #1, "START TIME:  " & strStartTime ' & ",,,,,,,COMMODITY: " & strCommodityName
            Print #1, "END TIME:  " & strEndTime & ",,,,,,,ORDER NUMBER: " & txtOrderNum.Text
            Print #1, ""
'            If Val("" & txtCustNum.Text) = "439" Then
            If Val("" & txtCustNum.Text) = "835" Then
                Print #1, "VESSEL:  " & strVesselName & ",,,,,,,CUSTOMER:  " & strCustName
            Else
                Print #1, ",,,,,,," & "CUSTOMER:  " & strCustName
            End If
            Print #1, ""
            Print #1, ""
            Print #1, ",,,,,DESCRIPTION,,QTY,,RGR/"
            Print #1, "BARCODE,,,,PKG,WEIGHT,SIZE,ORIG,ACTUAL,HSP,DAMAGE,CONTAINER NUM,SCANNER,CHECKER"
            Print #1, "------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
            
            While (Not dsMAIN_DATA.EOF)
                If sSignatureCheck <> "NOPICK" Then
                    ' if the sSignatureCheck wasn't already set due to lack of picklist,
                    ' perform this looping check to see if any pallets don't match the picklist, and throw an exception for later.
                    strSql = "SELECT * FROM (SELECT DO.ORDERNUM THE_ORDER, PACKHOUSEID, SIZEHIGH, SIZELOW FROM DC_ORDERDETAIL DO, " & sSignatureSQLAddon & " DP WHERE " _
                    & "DO.ORDERNUM = DP.ORDERNUM AND DO.ORDERDETAILID = DP.ORDERDETAILID AND DO.ORDERNUM = '" & txtOrderNum.Text & "') T2 " _
                    & "WHERE T2.THE_ORDER = '" & txtOrderNum.Text & "' AND T2.PACKHOUSEID = '" & dsMAIN_DATA.Fields("THE_PKG").Value & "' AND TO_NUMBER(SIZEHIGH) >= " _
                    & Val("" & dsMAIN_DATA.Fields("THE_SIZE").Value) & " AND TO_NUMBER(SIZELOW) <= " & Val("" & dsMAIN_DATA.Fields("THE_SIZE").Value)
                    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
                    If (dsSHORT_TERM_DATA.RecordCount = 0) Then
                        sSignatureCheck = "SHOW"
                    End If
                End If

                strBarcode = "*" & dsMAIN_DATA.Fields("THE_PALLET").Value & "*"
                strPKG = dsMAIN_DATA.Fields("THE_PKG").Value
                strWT = dsMAIN_DATA.Fields("THE_WEIGHT").Value
                strSize = dsMAIN_DATA.Fields("THE_SIZE").Value
                strOrigCnt = dsMAIN_DATA.Fields("THE_ORIG").Value
                strActualCnt = dsMAIN_DATA.Fields("THE_CHANGE").Value
                dTotalCases = dTotalCases + Val("" & dsMAIN_DATA.Fields("THE_CHANGE").Value)
                strChecker = dsMAIN_DATA.Fields("THE_LOGIN").Value
                
                If Val("" & dsMAIN_DATA.Fields("THE_SERVICE").Value) = 7 Then
                    strActualCnt = strActualCnt & "FR"
                End If
                If Val("" & dsMAIN_DATA.Fields("THE_SERVICE").Value) = 13 Then
                    strActualCnt = strActualCnt & "DR"
                End If
                
                strStatus = "" & dsMAIN_DATA.Fields("THE_STATUS").Value
                
'                If UCase$(Left("" & dsMAIN_DATA.Fields("THE_DESCRIPTION").Value, 3)) = "DMG" Then
'                    temp = Split(dsMAIN_DATA.Fields("THE_DESCRIPTION").Value, ":")
'                    strDamage = temp(1)
'                Else
                    strDamage = dsMAIN_DATA.Fields("THE_BATCH").Value
'                End If
                
                strContainer = "" & dsMAIN_DATA.Fields("THE_CONTAINER").Value
                
                Print #1, strBarcode & ",,,," & strPKG & "," & strWT & "," & strSize & "," & strOrigCnt & "," _
                        & strActualCnt & "," & strStatus & "," & strDamage & "," & strContainer & "," & strChecker
                
                dsMAIN_DATA.MoveNext
            Wend
            
            strSql = "SELECT COUNT(*) THE_COUNT FROM " _
                & "(SELECT PALLET_ID, SUM(QTY_CHANGE) THE_CHANGE FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtOrderNum.Text & "' " _
                & "AND SERVICE_CODE IN (6, 7, 13) AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) " _
                & "GROUP BY PALLET_ID) " _
                & "WHERE THE_CHANGE > 0"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
            dTotalPallets = dsSHORT_TERM_DATA.Fields("THE_COUNT").Value
            
            Print #1, "======================================================================================================="
            Print #1, ",,,,,,Total Cases:,,," & dTotalCases
            Print #1, ",,,,,,Total Pallets:,,," & dTotalPallets
            
            If sSignatureCheck = "SHOW" Then
                Print #1, ""
                Print #1, ",,,,This order has pallets that are not present on the original picklist."
                Print #1, ",,,,Please obtain an authorized signature."
                Print #1, ""
                Print #1, ",,,,X_________________________________________________________"
            End If
            
            Close #1
        Else
        
        
            ' go to printer
            If LCase$(Environ$("USERNAME")) = "tally" Then
                Printer.Copies = 3
            Else
                Printer.Copies = 1
            End If
    
            '' Page Header
            Printer.Font = "Arial"
            Printer.FontBold = True
            Printer.Print ""
            Printer.FontSize = 11
            Printer.FontBold = True
            Printer.Print Tab(40); "PORT OF WILMINGTON TALLY"
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(10); "START TIME: " & strStartTime; Tab(55); ' "COMMODITY: " & strCommodityName
            Printer.Print Tab(10); "END TIME: " & strEndTime; Tab(55); "ORDER NUMBER: " & txtOrderNum.Text
            Printer.Print ""
'            If Val("" & txtCustNum.Text) = "439" Then
                Printer.Print Tab(10); "VESSEL:" & strVesselName; Tab(55); "CUSTOMER:" & strCustName
'            Else
'                Printer.Print Tab(55); "CUSTOMER:" & strCustName
'            End If
            Printer.Print ""
            Printer.Print ""
            Printer.FontSize = 10
            Printer.FontBold = False
            Printer.Print Tab(46); "DESCRIPTION"; Tab(71); "QTY"; Tab(85); "RGR/"
            Printer.Print "BARCODE"; Tab(43); "PKG"; Tab(50); "WEIGHT"; Tab(60); "SIZE"; Tab(67); "ORIG"; Tab(74); "ACTUAL"; Tab(85); "HSP"; Tab(93); "DMG"; Tab(100); "CONT #"; Tab(115); "CHECKER"
            Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"
            While (Not dsMAIN_DATA.EOF)
                If sSignatureCheck <> "NOPICK" Then
                    ' if the sSignatureCheck wasn't already set due to lack of picklist,
                    ' perform this looping check to see if any pallets don't match the picklist, and throw an exception for later.
                    strSql = "SELECT * FROM (SELECT DO.ORDERNUM THE_ORDER, PACKHOUSEID, SIZEHIGH, SIZELOW FROM DC_ORDERDETAIL DO, " & sSignatureSQLAddon & " DP WHERE " _
                    & "DO.ORDERNUM = DP.ORDERNUM AND DO.ORDERDETAILID = DP.ORDERDETAILID AND DO.ORDERNUM = '" & txtOrderNum.Text & "') T2 " _
                    & "WHERE T2.THE_ORDER = '" & txtOrderNum.Text & "' AND T2.PACKHOUSEID = '" & dsMAIN_DATA.Fields("THE_PKG").Value & "' AND TO_NUMBER(SIZEHIGH) >= " _
                    & Val("" & dsMAIN_DATA.Fields("THE_SIZE").Value) & " AND TO_NUMBER(SIZELOW) <= " & Val("" & dsMAIN_DATA.Fields("THE_SIZE").Value)
                    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
                    If (dsSHORT_TERM_DATA.RecordCount = 0) Then
                        sSignatureCheck = "SHOW"
                    End If
                End If

                strBarcode = dsMAIN_DATA.Fields("THE_PALLET").Value
                strPKG = dsMAIN_DATA.Fields("THE_PKG").Value
                strWT = dsMAIN_DATA.Fields("THE_WEIGHT").Value
                strSize = dsMAIN_DATA.Fields("THE_SIZE").Value
                strOrigCnt = dsMAIN_DATA.Fields("THE_ORIG").Value
                strActualCnt = dsMAIN_DATA.Fields("THE_CHANGE").Value
                dTotalCases = dTotalCases + Val("" & dsMAIN_DATA.Fields("THE_CHANGE").Value)
'                strChecker = dsMAIN_DATA.Fields("THE_LOGIN").Value
                strChecker = get_checker_name(strBarcode, txtCustNum.Text, txtVslNum.Text, dsMAIN_DATA.Fields("ACTIVITY_NUM").Value)

                If Val("" & dsMAIN_DATA.Fields("THE_SERVICE").Value) = 7 Then
                    strActualCnt = strActualCnt & "FR"
                End If
                If Val("" & dsMAIN_DATA.Fields("THE_SERVICE").Value) = 13 Then
                    strActualCnt = strActualCnt & "DR"
                End If
                
                strStatus = "" & dsMAIN_DATA.Fields("THE_STATUS").Value
                
'                If UCase$(Left("" & dsMAIN_DATA.Fields("THE_DESCRIPTION").Value, 3)) = "DMG" Then
'                    temp = Split(dsMAIN_DATA.Fields("THE_DESCRIPTION").Value, ":")
'                    strDamage = temp(1)
'                Else
                    strDamage = dsMAIN_DATA.Fields("THE_BATCH").Value
'                End If
                
                strContainer = "" & dsMAIN_DATA.Fields("THE_CONTAINER").Value
                
                Printer.Print strBarcode; Tab(43); strPKG; Tab(50); strWT; Tab(60); strSize; _
                Tab(67); strOrigCnt; Tab(74); strActualCnt; Tab(85); strStatus; Tab(93); strDamage _
                ; Tab(100); strContainer; Tab(115); strChecker

                
                dsMAIN_DATA.MoveNext
            Wend
            
            strSql = "SELECT COUNT(*) THE_COUNT FROM " _
                & "(SELECT PALLET_ID, SUM(QTY_CHANGE) THE_CHANGE FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtOrderNum.Text & "' " _
                & "AND SERVICE_CODE IN (6, 7, 13) AND (ACTIVITY_DESCRIPTION != 'VOID' OR ACTIVITY_DESCRIPTION IS NULL) " _
                & "AND CUSTOMER_ID = '" & txtCustNum.Text & "' AND ARRIVAL_NUM = '" & txtVslNum.Text & "'" _
                & "GROUP BY PALLET_ID) " _
                & "WHERE THE_CHANGE > 0"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
            dTotalPallets = dsSHORT_TERM_DATA.Fields("THE_COUNT").Value
            
            Printer.Print Tab(1); "====================================================================================================================================================="
            Printer.FontBold = True
            Printer.Print Tab(45); "Total Cases:"; Tab(65); dTotalCases
            Printer.Print Tab(45); "Total Pallets:"; Tab(65); dTotalPallets
            Printer.FontBold = False
           
            ' Adam Walter, Dec 2011
            ' here we go again... need to add a total summary set to ONLY customer 439 (now 835) printouts.
'            If txtCustNum.Text = 439 Then
            If txtCustNum.Text = 835 Then
                strSql = "SELECT CT.WEIGHT || CT.WEIGHT_UNIT THE_WEIGHT, " _
                        & "COUNT(DISTINCT CT.PALLET_ID) THE_PALLETS, SUM(CA.QTY_CHANGE) THE_CHANGE " _
                        & "FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA " _
                        & "WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID " _
                        & "AND CA.SERVICE_CODE IN (6, 7, 13) " _
                        & "AND CA.ORDER_NUM = '" & txtOrderNum.Text & "' " _
                        & "AND CT.ARRIVAL_NUM = '" & txtVslNum.Text & "' " _
                        & "AND CT.RECEIVER_ID = '" & txtCustNum.Text & "' " _
                        & "AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL)" _
                        & "AND CA.QTY_CHANGE != 0 " _
                        & "GROUP BY CT.WEIGHT || CT.WEIGHT_UNIT " _
                        & "HAVING SUM(CA.QTY_CHANGE) > 0 " _
                        & "ORDER BY CT.WEIGHT || CT.WEIGHT_UNIT"
                Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
                Printer.FontBold = True
                Printer.Print ""
                Printer.Print ""
                Printer.Print Tab(10); "SUBTOTALS:"
                Printer.Print Tab(21); "WEIGHT"; Tab(32); "CTNS"; Tab(46); "PLTS"
                Printer.FontBold = False
                While (Not dsMAIN_DATA.EOF)
                   ' strPKG = dsMAIN_DATA.Fields("THE_PKG").Value
                    strWT = dsMAIN_DATA.Fields("THE_WEIGHT").Value
                   ' strSize = dsMAIN_DATA.Fields("THE_SIZE").Value
                    dTotalCases = dsMAIN_DATA.Fields("THE_CHANGE").Value
                    dTotalPallets = dsMAIN_DATA.Fields("THE_PALLETS").Value
                    
                    Printer.Print Tab(23); strWT; Tab(35); dTotalCases; Tab(50); dTotalPallets;
                    dsMAIN_DATA.MoveNext
                Wend
            End If
            
            Printer.Print ""
            Printer.Print ""
            
            strSql = "SELECT NVL(BOL, 'UKN') THE_BOL, " _
                    & "COUNT(DISTINCT CT.PALLET_ID) THE_PALLETS, SUM(CA.QTY_CHANGE) THE_CHANGE " _
                    & "FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA " _
                    & "WHERE CT.PALLET_ID = CA.PALLET_ID AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM AND CA.CUSTOMER_ID = CT.RECEIVER_ID " _
                    & "AND CA.SERVICE_CODE IN (6, 7, 13) " _
                    & "AND CA.ORDER_NUM = '" & txtOrderNum.Text & "' " _
                    & "AND CT.ARRIVAL_NUM = '" & txtVslNum.Text & "' " _
                    & "AND CT.RECEIVER_ID = '" & txtCustNum.Text & "' " _
                    & "AND (CA.ACTIVITY_DESCRIPTION != 'VOID' OR CA.ACTIVITY_DESCRIPTION IS NULL)" _
                    & "AND CA.QTY_CHANGE != 0 " _
                    & "GROUP BY NVL(BOL, 'UKN') " _
                    & "HAVING SUM(CA.QTY_CHANGE) > 0 " _
                    & "ORDER BY NVL(BOL, 'UKN')"
            Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
            Printer.FontBold = True
            Printer.Print ""
            Printer.Print ""
            Printer.Print Tab(10); "SUBTOTALS:"
            Printer.Print Tab(21); "BOL"; Tab(45); "CTNS"; Tab(59); "PLTS"
            Printer.FontBold = False
            While (Not dsMAIN_DATA.EOF)
               ' strPKG = dsMAIN_DATA.Fields("THE_PKG").Value
                strBoL = dsMAIN_DATA.Fields("THE_BOL").Value
               ' strSize = dsMAIN_DATA.Fields("THE_SIZE").Value
                dTotalCases = dsMAIN_DATA.Fields("THE_CHANGE").Value
                dTotalPallets = dsMAIN_DATA.Fields("THE_PALLETS").Value
                
                Printer.Print Tab(23); strBoL; Tab(48); dTotalCases; Tab(63); dTotalPallets;
                dsMAIN_DATA.MoveNext
            Wend
            
                      
            If sSignatureCheck = "SHOW" Then
                Printer.FontBold = False
                Printer.Print ""
                Printer.Print ""
                Printer.Print ""
                Printer.Print Tab(45); "This order has pallets that are not present on the original picklist."
                Printer.Print Tab(45); "Please obtain an authorized signature."
                Printer.Print ""
                Printer.FontBold = True
                Printer.Print Tab(45); "X_________________________________________________________"
            End If
        
            Printer.EndDoc
            
        ' one way or the other, output is done.
        End If
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
    Dim CommandArgs() As String
    Dim sOrder As String
    Dim sCust As String
    Dim sVessel As String
    Dim sPath As String

    CommandArgs = Split(Command$, " ", 4)

    sPath = ""
    If UBound(CommandArgs) > 0 Then
        sOrder = CommandArgs(0)
        sCust = CommandArgs(1)
        sVessel = CommandArgs(2)
        sPath = CommandArgs(3)
        txtOrderNum.Text = sOrder
        txtCustNum.Text = sCust
        txtVslNum.Text = sVessel
        txtFile.Text = sPath
        Call cmdPrint_Click
        Unload Me
    End If




End Sub

Sub ClearAllFields()

    frmCLMChkTally.txtCustNum.Text = ""
    frmCLMChkTally.txtOrderNum.Text = ""
    frmCLMChkTally.txtVslNum.Text = ""

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





'Private Sub PrintTallyInit(sPath As String)
'
'On Error GoTo ErrHandler:
'
'    Dim i As Integer
'
''    Call setupData
''
''    For i = 1 To TotalPage
''        Call PrintTally(i)
''    Next i
'
'    order = UCase(Trim(Me.txtOrderNum.Text))
'    cusID = Trim(Me.txtCustNum.Text)
'    vslID = Trim(Me.txtVslNum.Text)
'
'    Call iniVariables
'
'    '' Step 1 -Get Vessel Name
'    If (vslID <> "440") Then
'        Call GetVslName
'    End If
'
'    '' Step 2-Get Customer Name
'    Call GetCusName
'
'    '' Step 3-Get Commodity
'    ''Call GetComm
'
'    '' Step 4 -Get Order Detail
'    Call GetOrderDetail
'
'    '' Step 5-Get Start/End Time
'    Call GetStartEndTime
'
'    TotalPage = Int(totalPlt / RecPerPage) + 1
'
'    For i = 1 To TotalPage
'        Call PrintTally(i)
'    Next i
'
'    Call ClearAllFields
'
'ErrHandler:
'
'    If Err.Number <> 0 Then
'
'        MsgBox Err.Description
'        Call ClearAllFields
'    End If
'
'End Sub
