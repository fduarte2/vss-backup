VERSION 5.00
Begin VB.Form frmMainForm 
   Caption         =   "Booking Paper Inbound Tally"
   ClientHeight    =   8265
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   7110
   LinkTopic       =   "Form1"
   ScaleHeight     =   8265
   ScaleWidth      =   7110
   StartUpPosition =   3  'Windows Default
   Begin VB.Frame Frame2 
      Height          =   1815
      Left            =   240
      TabIndex        =   11
      Top             =   6240
      Width           =   6615
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
         TabIndex        =   6
         Top             =   240
         Width           =   5175
      End
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
         TabIndex        =   7
         Top             =   960
         Width           =   5175
      End
   End
   Begin VB.Frame Frame1 
      Height          =   5775
      Left            =   240
      TabIndex        =   0
      Top             =   240
      Width           =   6615
      Begin VB.TextBox txtWarehouseCode 
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
         TabIndex        =   15
         Top             =   3360
         Width           =   2655
      End
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
         TabIndex        =   5
         Top             =   5040
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
         TabIndex        =   4
         Top             =   5040
         Width           =   2175
      End
      Begin VB.TextBox txtManifestNum 
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
         TabIndex        =   3
         Top             =   2520
         Width           =   2655
      End
      Begin VB.TextBox txtArvNum 
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
         Top             =   960
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
         TabIndex        =   2
         Top             =   1680
         Width           =   2655
      End
      Begin VB.Label Label8 
         Alignment       =   2  'Center
         Caption         =   "---OR---"
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
         Left            =   2400
         TabIndex        =   17
         Top             =   4080
         Width           =   1935
      End
      Begin VB.Line Line1 
         X1              =   0
         X2              =   6600
         Y1              =   3240
         Y2              =   3240
      End
      Begin VB.Label Label7 
         Caption         =   "Warehouse Code "
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
         Left            =   360
         TabIndex        =   16
         Top             =   3480
         Width           =   2295
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
         TabIndex        =   14
         Top             =   5160
         Width           =   1335
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
         Top             =   4560
         Width           =   2655
      End
      Begin VB.Label Label3 
         Caption         =   "Manifest# (Optional)"
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
         Left            =   360
         TabIndex        =   12
         Top             =   2520
         Width           =   1935
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         Caption         =   "Arrival Number"
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
         TabIndex        =   10
         Top             =   1080
         Width           =   1560
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
         TabIndex        =   9
         Top             =   1800
         Width           =   1860
      End
      Begin VB.Label Label4 
         Alignment       =   2  'Center
         AutoSize        =   -1  'True
         Caption         =   "Barnett Inbound Tally"
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
         Left            =   1620
         TabIndex        =   8
         Top             =   360
         Width           =   3015
      End
   End
End
Attribute VB_Name = "frmMainForm"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim strBarcode As String
Dim dTotalWeightLB As Double
Dim dTotalWeightKG As Double
'Dim dTotalPallets As Double
Dim dTotalRolls As Double
Dim bAnyDamage As Boolean
Dim sLastBooking As String
Dim sManifest As String
Dim sWarehouseCodeSQL As String
Dim strFromMill As String


Private Sub cmdClose_Click()
    End
End Sub

Private Sub Form_Load()
    Call ClearAllFields
End Sub

Sub ClearAllFields()

    frmMainForm.txtCustNum.Text = ""
    frmMainForm.txtArvNum.Text = ""
    frmMainForm.txtManifestNum = ""
    frmMainForm.txtWarehouseCode = ""
    
    frmMainForm.txtStartDate.Text = Format$(Now, "MM/DD/YYYY")
    frmMainForm.txtEndDate.Text = Format$(Now, "MM/DD/YYYY")

End Sub

Private Sub cmdPrint_Click()
    ' I place the database definition files within this print routine, so that when the routine
    ' ends, it closes the connections along with the routine.  this prevents the Oracle Timeout error
    ' that occurs if the screen is left open for several hours.
        
    If txtArvNum.Text = "" Or txtCustNum.Text = "" Then
        MsgBox "Both Arrival and Customer fields need to be entered"
        Exit Sub
    End If
    If txtWarehouseCode.Text = "" And (txtStartDate.Text = "" Or txtEndDate.Text = "") Then
        MsgBox "Either Warehouse Code or a Date Range needs to be entered."
        Exit Sub
    End If
    
    
    
    txtArvNum.Text = UCase$(txtArvNum.Text)

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
    ElseIf txtCustNum.Text <> 314 And txtCustNum.Text <> 338 And txtCustNum.Text <> 517 And txtCustNum.Text <> 1 Then
        MsgBox "This Program currently only designed for Booking customers."
        Exit Sub
    End If
    strCustName = dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value
    
    If txtWarehouseCode.Text <> "" Then
        sWarehouseCodeSQL = " AND WAREHOUSE_CODE = '" & txtWarehouseCode.Text & "' "
    Else
        sWarehouseCodeSQL = " "
    End If
    
    ' we need to populate the dates ourselves if only a WH code was given
    If txtWarehouseCode.Text <> "" And (txtStartDate.Text = "" Or txtEndDate.Text = "") Then
        strSql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY') START_TIME, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY') END_TIME " _
                & "FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtArvNum.Text & "' AND PALLET_ID IN (SELECT PALLET_ID FROM BOOKING_ADDITIONAL_DATA BAD WHERE 1 = 1 " & sManifest & sWarehouseCodeSQL & ") " _
                & " AND CUSTOMER_ID = '" & txtCustNum.Text & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        If dsSHORT_TERM_DATA.Recordcount <= 0 Or IsNull(dsSHORT_TERM_DATA.Fields("START_TIME").Value) Then
            MsgBox "Entered Order (" & txtArvNum.Text & ") Has no records of activity in the system for given date range."
            Exit Sub
        End If
        txtStartDate.Text = dsSHORT_TERM_DATA.Fields("START_TIME").Value
        txtEndDate.Text = dsSHORT_TERM_DATA.Fields("END_TIME").Value
    End If

    If txtManifestNum.Text <> "" Then
        strSql = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY CA, BOOKING_ADDITIONAL_DATA BAD WHERE CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID " _
                & "AND CA.ORDER_NUM = '" & txtArvNum.Text & "' AND CA.ACTIVITY_NUM = '1' AND BAD.BOL = '" & txtManifestNum.Text & "'" _
                & " AND DATE_OF_ACTIVITY >= TO_DATE('" & txtStartDate.Text & "', 'MM/DD/YYYY') " _
                & " AND CUSTOMER_ID = '" & txtCustNum.Text & "'" _
                & " AND DATE_OF_ACTIVITY <= TO_DATE('" & txtEndDate.Text & " 23:59:59', 'MM/DD/YYYY HH24:MI:SS') "
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value <= 0 Then
            MsgBox "No Inbound records for the given Arrival/Manifest/Date combination"
            Exit Sub
        Else
'            sManifest = txtManifestNum.Text
            sManifest = "AND BAD.BOL = '" & txtManifestNum.Text & "'"
        End If
    Else ' no manifest entered, figure out most recent and use it
        strSql = "SELECT MAX(DATE_OF_ACTIVITY) THE_DATE, BOL FROM CARGO_ACTIVITY CA, BOOKING_ADDITIONAL_DATA BAD WHERE CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID " _
                & "AND CA.ORDER_NUM = '" & txtArvNum.Text & "' AND CA.ACTIVITY_NUM = '1'" _
                & " AND DATE_OF_ACTIVITY >= TO_DATE('" & txtStartDate.Text & "', 'MM/DD/YYYY') " _
                & " AND CUSTOMER_ID = '" & txtCustNum.Text & "'" _
                & " AND DATE_OF_ACTIVITY <= TO_DATE('" & txtEndDate.Text & " 23:59:59', 'MM/DD/YYYY HH24:MI:SS') " _
                & " GROUP BY BOL ORDER BY THE_DATE DESC"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        If dsSHORT_TERM_DATA.Recordcount <= 0 Then
            MsgBox "No Manifest#s found for given LR#/Date, cannot autocomplete form."
            Exit Sub
        Else
'            sManifest = dsSHORT_TERM_DATA.Fields("BOL").Value
             sManifest = ""
        End If
    End If
    

'   BOL = '" & sManifest & "')"
    strSql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') END_TIME " _
            & "FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtArvNum.Text & "' AND PALLET_ID IN (SELECT PALLET_ID FROM BOOKING_ADDITIONAL_DATA BAD WHERE 1 = 1 " & sManifest & sWarehouseCodeSQL & ") " _
            & " AND CUSTOMER_ID = '" & txtCustNum.Text & "'" _
            & " AND DATE_OF_ACTIVITY >= TO_DATE('" & txtStartDate.Text & "', 'MM/DD/YYYY') " _
            & " AND DATE_OF_ACTIVITY <= TO_DATE('" & txtEndDate.Text & " 23:59:59', 'MM/DD/YYYY HH24:MI:SS') "
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If dsSHORT_TERM_DATA.Recordcount <= 0 Or IsNull(dsSHORT_TERM_DATA.Fields("START_TIME").Value) Then
        MsgBox "Entered Order (" & txtArvNum.Text & ") Has no records of activity in the system for given date range."
        Exit Sub
    End If
    strStartTime = dsSHORT_TERM_DATA.Fields("START_TIME").Value
    strEndTime = dsSHORT_TERM_DATA.Fields("END_TIME").Value





    ' PART ONE
    ' normal rolls
'                & "AND CA.DATE_OF_ACTIVITY >= (SYSDATE - 21) "
'                & "AND BAD.BOL = '" & sManifest & "' "
', SUBSTR(EMPLOYEE_NAME, 0, 8) THE_CHECKER      , EMPLOYEE PERS     AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)
    strSql = "SELECT CT.PALLET_ID, QTY_RECEIVED, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA, NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, NVL(BOOKING_NUM, '--NONE--') THE_BOOK, NVL(SHIPFROMMILL, 'UNKNOWN') THE_MILL, WAREHOUSE_CODE, " _
                & "BAD.ORDER_NUM, LENGTH || ' ' || LENGTH_MEAS THE_LINEAR, BAD.BOL THE_MANIFEST, ROUND(WEIGHT * UC3.CONVERSION_FACTOR) WEIGHT_LB, ROUND(WEIGHT * UC4.CONVERSION_FACTOR) WEIGHT_KG, DECODE(QTY_DAMAGED, NULL, 'N', '0', 'N', 'Y') DAMAGE_YN, ACTIVITY_NUM " _
                & "FROM CARGO_TRACKING CT, SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA, BOOKING_PAPER_GRADE_CODE BPGC, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4 " _
                & "Where CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID " _
                & "AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID " _
                & "AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'  AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'  AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'  AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG' " _
                & "AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+) " _
                & sManifest _
                & sWarehouseCodeSQL _
                & " AND CUSTOMER_ID = '" & txtCustNum.Text & "'" _
                & " AND CA.ACTIVITY_NUM = '1' " _
                & "AND CA.ORDER_NUM = '" & txtArvNum.Text & "' " _
                & " AND DATE_OF_ACTIVITY >= TO_DATE('" & txtStartDate.Text & "', 'MM/DD/YYYY') " _
                & " AND DATE_OF_ACTIVITY <= TO_DATE('" & txtEndDate.Text & " 23:59:59', 'MM/DD/YYYY HH24:MI:SS') " _
                & "ORDER BY NVL(BOOKING_NUM, '--NONE--')"
    Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

    If dsMAIN_DATA.Recordcount = 0 Then
        MsgBox "No records for this Order / Customer combination"
        OraDatabase.Close
'        OraSession.Close
        Set OraDatabase = Nothing
        Set OraSession = Nothing
        Exit Sub
    Else
        bAnyDamage = False
        dTotalWeightLB = 0
        dTotalWeightKG = 0
'        dTotalPallets = 0
        dTotalRolls = 0

        ' ADAM WALTER Jul 2010.  Not very eloquent, but same "from mill" should hold for all order, so first value will work fine.
        strFromMill = dsMAIN_DATA.Fields("THE_MILL").Value
'        strSql = "SELECT DOLEPAPER_ORIGINAL_MILL FROM CARGO_TRACKING_ADDITIONAL_DATA WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) IN " _
'            & "(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM CARGO_TRACKING WHERE BOL = '" & dsMAIN_DATA.Fields("BOL").Value & "')"
'        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
'        If Not IsNull(dsSHORT_TERM_DATA.Fields("DOLEPAPER_ORIGINAL_MILL").Value) Then
            strSql = "SELECT * FROM DOLEPAPER_EDI_MILL_CODES WHERE MILL_ID = '" & strFromMill & "'"
            Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
            If Not IsNull(dsSHORT_TERM_DATA.Fields("MILL_ID").Value) Then
                strFromMill = dsSHORT_TERM_DATA.Fields("MILL_ID").Value & " - " & dsSHORT_TERM_DATA.Fields("MILL_NAME").Value
            End If
'        End If


        ' go to printer
        If LCase$(Environ$("USERNAME")) = "tally" Then
            Printer.Copies = 2
        Else
            Printer.Copies = 1
        End If

        '' Page Header
        Printer.Orientation = 2
        Printer.Font = "Arial"
        Printer.FontBold = True
        Printer.Print ""
        Printer.FontSize = 11
        Printer.FontBold = True
        Printer.Print Tab(40); "PORT OF WILMINGTON TALLY - COMMERCIAL PAPER (Inbound)"
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(10); "CUSTOMER: " & strCustName; Tab(95); "START TIME: " & strStartTime
'        Printer.Print Tab(10); "Printed On: " & Format(Now, "mm/dd/yyyy"); Tab(65); "START TIME: " & strStartTime
        Printer.Print Tab(10); "ARRIVAL #: " & txtArvNum.Text; Tab(95); "END TIME: " & strEndTime
        Printer.Print Tab(10); "From Mill: " & strFromMill
        If txtWarehouseCode.Text <> "" Then
            Printer.Print Tab(10); "WAREHOUSE CODE: " & txtWarehouseCode.Text
        End If
        Printer.Print ""
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print "BARCODE"; Tab(26); "QTY"; Tab(35); "Size (cm)"; Tab(46); "DIA (cm)"; Tab(58); "PRODUCT"; Tab(77); "BOOKING"; Tab(97); "ORDER"; Tab(110); "Linear Meas"; Tab(130); "Rec. Manifest"; Tab(150); "LB"; Tab(158); "KG"; Tab(166); "DMG"; Tab(174); "CHECKER"; Tab(188); "WHSCD"
'        Printer.Print Tab(1); "_______________________________________________________________________________________________________________________________________________________________________________"
        sLastBooking = ""
        While (Not dsMAIN_DATA.EOF)
            If sLastBooking <> "" And sLastBooking <> dsMAIN_DATA.Fields("THE_BOOK").Value Then
                Printer.Print ""
            End If
            sLastBooking = dsMAIN_DATA.Fields("THE_BOOK").Value
            Printer.Print dsMAIN_DATA.Fields("PALLET_ID").Value; Tab(26); dsMAIN_DATA.Fields("QTY_RECEIVED").Value; _
            Tab(35); dsMAIN_DATA.Fields("THE_WIDTH").Value; Tab(46); dsMAIN_DATA.Fields("THE_DIA").Value; _
            Tab(58); dsMAIN_DATA.Fields("THE_CODE").Value; Tab(77); dsMAIN_DATA.Fields("THE_BOOK").Value; Tab(97); dsMAIN_DATA.Fields("ORDER_NUM").Value; _
            Tab(110); dsMAIN_DATA.Fields("THE_LINEAR").Value; _
            Tab(130); dsMAIN_DATA.Fields("THE_MANIFEST").Value; Tab(150); dsMAIN_DATA.Fields("WEIGHT_LB").Value; _
            Tab(158); dsMAIN_DATA.Fields("WEIGHT_KG").Value; Tab(166); dsMAIN_DATA.Fields("DAMAGE_YN").Value; Tab(174); get_checker_name(dsMAIN_DATA.Fields("PALLET_ID").Value, txtCustNum.Text, txtArvNum.Text, dsMAIN_DATA.Fields("ACTIVITY_NUM").Value); Tab(188); dsMAIN_DATA.Fields("WAREHOUSE_CODE").Value 'dsMAIN_DATA.Fields("THE_CHECKER").Value
            
            If dsMAIN_DATA.Fields("DAMAGE_YN").Value = "Y" Then
                bAnyDamage = True
            End If

'            dTotalPallets = dTotalPallets + 1
            dTotalWeightLB = dTotalWeightLB + dsMAIN_DATA.Fields("WEIGHT_LB").Value
            dTotalWeightKG = dTotalWeightKG + dsMAIN_DATA.Fields("WEIGHT_KG").Value
            dTotalRolls = dTotalRolls + 1
            dsMAIN_DATA.MoveNext
        Wend

'        Printer.Print Tab(1); "====================================================================================================================================================="
        Printer.FontBold = True
        Printer.Print Tab(5); "Totals:"; Tab(24); dTotalRolls & " ROLLS"; Tab(139); dTotalWeightLB; Tab(148); dTotalWeightKG
        Printer.Print
        Printer.Print
        Printer.Print
        
        Printer.FontBold = True
        Printer.Print "Receiving Summary"
        Printer.FontBold = False
        
        ' summary by measurement
'                    & "AND CA.DATE_OF_ACTIVITY >= (SYSDATE - 21)
        strSql = "SELECT SUM(QTY_RECEIVED) THE_REC, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA, NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, NVL(BOOKING_NUM, '--NONE--') THE_BOOK, " _
                    & "BAD.ORDER_NUM, BAD.BOL THE_MANIFEST, SUM(ROUND(WEIGHT * UC3.CONVERSION_FACTOR)) WEIGHT_LB, SUM(ROUND(WEIGHT * UC4.CONVERSION_FACTOR)) WEIGHT_KG " _
                    & "FROM CARGO_TRACKING CT, SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA, BOOKING_PAPER_GRADE_CODE BPGC, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4 " _
                    & "WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID " _
                    & "AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID " _
                    & "AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'  AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'  AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'  AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG' " _
                    & "AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+) " _
                    & "AND CA.ACTIVITY_NUM = '1' " _
                    & " " & sManifest & " " _
                    & sWarehouseCodeSQL _
                    & "AND CA.ORDER_NUM = '" & txtArvNum.Text & "' " _
                    & " AND DATE_OF_ACTIVITY >= TO_DATE('" & txtStartDate.Text & "', 'MM/DD/YYYY') " _
                    & " AND DATE_OF_ACTIVITY <= TO_DATE('" & txtEndDate.Text & " 23:59:59', 'MM/DD/YYYY HH24:MI:SS') " _
                    & "GROUP BY ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR), ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR), NVL(SSCC_GRADE_CODE, '--NONE--'), NVL(BOOKING_NUM, '--NONE--'), BAD.ORDER_NUM, BAD.BOL"
        Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        sLastBooking = ""
        While (Not dsMAIN_DATA.EOF)
            If sLastBooking <> "" And sLastBooking <> dsMAIN_DATA.Fields("THE_BOOK").Value Then
                Printer.Print ""
            End If
            sLastBooking = dsMAIN_DATA.Fields("THE_BOOK").Value
            Printer.Print Tab(26); dsMAIN_DATA.Fields("THE_REC").Value; _
            Tab(35); dsMAIN_DATA.Fields("THE_WIDTH").Value; Tab(46); dsMAIN_DATA.Fields("THE_DIA").Value; _
            Tab(58); dsMAIN_DATA.Fields("THE_CODE").Value; Tab(77); dsMAIN_DATA.Fields("THE_BOOK").Value; Tab(97); dsMAIN_DATA.Fields("ORDER_NUM").Value; _
            Tab(130); dsMAIN_DATA.Fields("THE_MANIFEST").Value; Tab(150); dsMAIN_DATA.Fields("WEIGHT_LB").Value; _
            Tab(158); dsMAIN_DATA.Fields("WEIGHT_KG").Value

'            dTotalPallets = dTotalPallets + 1
'            dTotalWeightLB = dTotalWeightLB + dsMAIN_DATA.Fields("WEIGHT_LB").Value
'            dTotalWeightKG = dTotalWeightKG + dsMAIN_DATA.Fields("WEIGHT_KG").Value
'            dTotalRolls = dTotalRolls + 1
            dsMAIN_DATA.MoveNext
        Wend



        ' PART TWO
        ' Damage History of inbound
        If bAnyDamage = True Then
            Printer.NewPage
            Printer.FontSize = 11
            Printer.FontBold = True
            Printer.Print Tab(40); "PORT OF WILMINGTON TALLY - COMMERCIAL PAPER (Inbound)"
            Printer.Print Tab(70); "--- DAMAGE REPORT ---"
            Printer.Print ""
            Printer.Print Tab(10); "CUSTOMER: " & strCustName
            Printer.Print Tab(10); "ARRIVAL #: " & txtArvNum.Text
            Printer.Print ""
            Printer.FontSize = 8
            Printer.FontBold = False
            Printer.Print "BOOKING#"; Tab(25); "BARCODE"; Tab(70); "DMG TYPE"; Tab(90); "QTY"; Tab(110); "RECORDED"; Tab(140); "RESPONSIBLE"; Tab(170); "REJECT CLEARED"

            strSql = "SELECT BAD.PALLET_ID, NVL(BAD.BOOKING_NUM, 'NONE') THE_BOOK, DAMAGE_ID, TO_CHAR(DATE_ENTERED, 'MM/DD/YYYY HH24:MI') THE_DATE, NVL(TO_CHAR(DATE_CLEARED, 'MM/DD/YYYY HH24:MI'), 'NO') THE_CLEARED, " _
                    & "CHECKER_ENTERED, NVL(CHECKER_CLEARED, '') CHECK_CLEARED, DAMAGE_TYPE, NVL(EXTRA_DESC, ' ') THE_QUAN, OCCURRED " _
                    & "FROM BOOKING_DAMAGES BD, BOOKING_ADDITIONAL_DATA BAD " _
                    & "WHERE BAD.RECEIVER_ID = '" & txtCustNum.Text & "' " _
                    & "AND BAD.ARRIVAL_NUM = '" & txtArvNum.Text & "' " _
                    & "AND BD.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND BD.RECEIVER_ID = BAD.RECEIVER_ID AND BAD.PALLET_ID = BD.PALLET_ID " _
                    & "AND BAD.PALLET_ID IN (SELECT PALLET_ID FROM CARGO_ACTIVITY WHERE ARRIVAL_NUM = '" & txtArvNum.Text & "' AND CUSTOMER_ID = '" & txtCustNum.Text & "' AND ACTIVITY_NUM = '1' AND DATE_OF_ACTIVITY >= TO_DATE('" & txtStartDate.Text & "', 'MM/DD/YYYY') AND DATE_OF_ACTIVITY <= TO_DATE('" & txtEndDate.Text & " 23:59:59', 'MM/DD/YYYY HH24:MI:SS')) " _
                    & "ORDER BY PALLET_ID, DAMAGE_ID"
            Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
            sLastBooking = ""
            While (Not dsMAIN_DATA.EOF)
                If sLastBooking <> "" And sLastBooking <> dsMAIN_DATA.Fields("THE_BOOK").Value Then
                    Printer.Print ""
                End If
                sLastBooking = dsMAIN_DATA.Fields("THE_BOOK").Value
                Printer.Print dsMAIN_DATA.Fields("THE_BOOK").Value; Tab(25); dsMAIN_DATA.Fields("PALLET_ID").Value; _
                Tab(70); dsMAIN_DATA.Fields("DAMAGE_TYPE").Value; Tab(90); dsMAIN_DATA.Fields("THE_QUAN").Value; _
                Tab(110); dsMAIN_DATA.Fields("THE_DATE").Value; Tab(140); dsMAIN_DATA.Fields("OCCURRED").Value; _
                Tab(170); dsMAIN_DATA.Fields("THE_CLEARED").Value
                
                dsMAIN_DATA.MoveNext
            Wend
        End If
                
        
        ' PART THREE
        ' Rolls-yet-to-receive
        strSql = "SELECT CT.PALLET_ID, QTY_RECEIVED, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA, NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, NVL(BOOKING_NUM, '--NONE--') THE_BOOK, " _
                    & "BAD.ORDER_NUM, LENGTH || ' ' || LENGTH_MEAS THE_LINEAR, BAD.BOL THE_MANIFEST, ROUND(WEIGHT * UC3.CONVERSION_FACTOR) WEIGHT_LB, ROUND(WEIGHT * UC4.CONVERSION_FACTOR) WEIGHT_KG, DECODE(QTY_DAMAGED, NULL, 'N', '0', 'N', 'Y') DAMAGE_YN " _
                    & "FROM CARGO_TRACKING CT, SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD, BOOKING_PAPER_GRADE_CODE BPGC, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4 " _
                    & "Where CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID " _
                    & "AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'  AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'  AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'  AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG' " _
                    & "AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+) " _
                    & "AND CT.DATE_RECEIVED IS NULL " _
                    & "AND CT.ARRIVAL_NUM = '" & txtArvNum.Text & "'"
        Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        If dsMAIN_DATA.Recordcount = 0 Then
            ' No unscanned rolls, do nothing
        Else
            Printer.NewPage
            dTotalWeightLB = 0
            dTotalWeightKG = 0
            dTotalRolls = 0
            
            Printer.Orientation = 2
            Printer.Font = "Arial"
            Printer.FontBold = True
            Printer.Print ""
            Printer.FontSize = 11
            Printer.FontBold = True
            Printer.Print Tab(40); "PORT OF WILMINGTON TALLY - COMMERCIAL PAPER (Inbound)"
            Printer.Print Tab(70); "--- UNRECEIVED REPORT ---"
            Printer.Print ""
            Printer.Print Tab(10); "CUSTOMER: " & strCustName
            Printer.Print Tab(10); "ARRIVAL #: " & txtArvNum.Text
            Printer.Print ""
            Printer.FontSize = 8
            Printer.FontBold = False
            Printer.Print "BARCODE"; Tab(26); "QTY"; Tab(35); "Size (cm)"; Tab(46); "DIA (cm)"; Tab(58); "PRODUCT"; Tab(77); "BOOKING"; Tab(97); "ORDER"; Tab(110); "Linear Meas"; Tab(130); "Rec. Manifest"; Tab(150); "LB"; Tab(158); "KG"; Tab(166); "DMG"; Tab(174); "CHECKER"
            While (Not dsMAIN_DATA.EOF)
                Printer.Print dsMAIN_DATA.Fields("PALLET_ID").Value; Tab(26); dsMAIN_DATA.Fields("QTY_RECEIVED").Value; _
                Tab(35); dsMAIN_DATA.Fields("THE_WIDTH").Value; Tab(46); dsMAIN_DATA.Fields("THE_DIA").Value; _
                Tab(58); dsMAIN_DATA.Fields("THE_CODE").Value; Tab(77); dsMAIN_DATA.Fields("THE_BOOK").Value; Tab(97); dsMAIN_DATA.Fields("ORDER_NUM").Value; _
                Tab(110); dsMAIN_DATA.Fields("THE_LINEAR").Value; _
                Tab(130); dsMAIN_DATA.Fields("THE_MANIFEST").Value; Tab(150); dsMAIN_DATA.Fields("WEIGHT_LB").Value; _
                Tab(158); dsMAIN_DATA.Fields("WEIGHT_KG").Value; Tab(166); dsMAIN_DATA.Fields("DAMAGE_YN").Value
                
    
                dTotalWeightLB = dTotalWeightLB + dsMAIN_DATA.Fields("WEIGHT_LB").Value
                dTotalWeightKG = dTotalWeightKG + dsMAIN_DATA.Fields("WEIGHT_KG").Value
                dTotalRolls = dTotalRolls + 1
                dsMAIN_DATA.MoveNext
            Wend
            Printer.FontBold = True
            Printer.Print Tab(5); "Totals:"; Tab(24); dTotalRolls & " ROLLS"; Tab(139); dTotalWeightLB; Tab(148); dTotalWeightKG
        End If

        Printer.EndDoc
        
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



