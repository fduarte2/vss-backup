VERSION 5.00
Begin VB.Form OutboundKiwicheckerTally 
   BackColor       =   &H00FFFF80&
   Caption         =   "Outbound Kiwi Tally"
   ClientHeight    =   6795
   ClientLeft      =   990
   ClientTop       =   2775
   ClientWidth     =   9480
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   6795
   ScaleWidth      =   9480
   Begin VB.CommandButton cmdClose 
      Caption         =   "Close"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3960
      TabIndex        =   4
      Top             =   5520
      Width           =   1455
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&Print Tally"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3960
      TabIndex        =   3
      Top             =   4920
      Width           =   1455
   End
   Begin VB.TextBox txtOrderNum 
      Height          =   375
      Left            =   4800
      TabIndex        =   2
      Top             =   3720
      Width           =   1455
   End
   Begin VB.TextBox txtCustNum 
      Height          =   405
      Left            =   4800
      TabIndex        =   1
      Top             =   3000
      Width           =   1455
   End
   Begin VB.Label Label1 
      BackColor       =   &H00FFFF80&
      Caption         =   "PLEASE ENTER CUSTOMER ID AND ORDER NUMBER "
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   960
      TabIndex        =   7
      Top             =   1200
      Width           =   7935
   End
   Begin VB.Label lblOrderNum 
      BackColor       =   &H00FFFF80&
      Caption         =   "ORDER NUM"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   2880
      TabIndex        =   6
      Top             =   3720
      Width           =   1335
   End
   Begin VB.Label lblCustNum 
      BackColor       =   &H00FFFF80&
      Caption         =   "CUSTOMER NUM"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   2880
      TabIndex        =   5
      Top             =   3120
      Width           =   1695
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   375
      Left            =   0
      TabIndex        =   0
      Top             =   6600
      Width           =   9495
   End
End
Attribute VB_Name = "OutboundKiwicheckerTally"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iNoPL As Boolean
Dim theEnd As Boolean
Dim sTime As String
Dim eTime As String
Dim cTime As String
Dim subTotalCount As Long
Dim totalCount As Long
Dim iServiceId As Long
Dim iPosition1 As Long
Dim iPosition2 As Long
Dim iDate As String
Dim aMin As Date
Dim aMax As Date
Dim iException As String
Dim lineNum As Long
Dim iVesselString As String
Dim iVesselStringH As String
Dim iMyVesselString As String
Dim iVesselNum As Long
Dim iPltNum As Long
Dim iPltNumPage As Long
Dim iLotNumOld As String
Dim iLoginID As String
Dim iLotNumP As String
Dim iLotNumC As String
Dim iCustString As String
Dim iCommP As Long
Dim iCommC As Long
Dim iVariety  As String
Dim iResponse As Integer
Dim sDirChk As String
Dim myDate As String
Dim myDateS As String
Dim myDateE As String
Dim iCommName As String
Dim iQtyReceived As Long
Dim iSubTotalCount1 As Long
Dim iTotalCount1 As Long
Dim iRecordNum As Long
Dim sqlStmt1 As String
Dim sqlStmt2 As String
Dim sqlStmt3 As String
Dim iFileName As String
Dim gsFileName As String
Dim giFileNum As Integer
Dim iActID As String
Dim iOnce As Boolean
Dim iRecordNumTotal As Long
Dim sHatch As String
Dim sReleased As String


Private Sub subSummaryPrint()
 'print total count
     Printer.Print Tab(10); " "
     Printer.Print Tab(75); "========================="
     If iServiceId <> 8 Then
        Printer.Print Tab(70); "Sub Total Counts"; Tab(98); subTotalCount
     Else
        Printer.Print Tab(70); "Sub Total Counts"; Tab(98); iSubTotalCount1
     End If
     lineNum = lineNum + 3
     
     If theEnd = True Then
        If iServiceId <> 8 Then
            Printer.Print Tab(75); "Total Counts"; Tab(98); totalCount
            'Printer.Print Tab(75); "Total Pallets"; Tab(98); TotalRecordNum
            Printer.Print Tab(75); "Total Pallets"; Tab(98); iPltNum
        Else
            Printer.Print Tab(75); "Total Counts"; Tab(98); iTotalCount1
            Printer.Print Tab(75); "Total Pallets"; Tab(98); iPltNum
        End If
        
        lineNum = lineNum + 2
     End If
     
     lineNum = lineNum + 2
     'adjust lines for print foot
     While lineNum < 63
        'Printer.FontSize = 10
         Printer.Print Tab(10); " "
         'Printer.Print ""
         lineNum = lineNum + 1
     Wend
     
'     If iServiceId <> 8 Then
'        Printer.Print Tab(125); ""
'        'Printer.Print Tab(118); iRecordNum
'        Printer.Print Tab(118); iPltNumPage & " Plt(s)"
'
'     Else
'        'Printer.Print Tab(118); iRecordNum
'        Printer.Print Tab(118); iPltNumPage & " Plt(s)"
'        Printer.Print Tab(125); ""
'     End If
        'end print and initialize
    
    
    
End Sub


Private Sub cmdClose_Click()
    Unload Me
    End       'HD???? 5/1/2007 Rudy:
End Sub

Private Sub cmdPrint_Click()
    
    Call startPrinttally
   
    Unload Me
    Load Me
End Sub

Private Sub cmdPrint_KeyPress(KeyAscii As Integer)

   cmdPrint_Click
    
End Sub

Private Sub Form_Load()
 
    lineNum = 0
    iVesselString = ""
    iRecordNum = 0
    cmdPrint.Enabled = False
    
    
    
    
    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    lblStatus.Caption = "Please wait..."
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
    'Set BNIDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)

    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Ready"
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
      
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Checker Tally"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0
    
End Sub

Private Sub txtCustNum_LostFocus()



    If Len(txtCustNum.Text) = 0 Then Exit Sub
    If IsNumeric(txtCustNum.Text) Then
        sqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Val(Trim$(txtCustNum.Text))
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
            iCustString = Trim$(txtCustNum.Text) + " - " + Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value)
            cmdPrint.Enabled = True
            Exit Sub
        Else
            txtCustNum.Text = ""
            txtCustNum.SetFocus
            MsgBox "Customer ID is not correct, Please try again!", vbExclamation, "Customer ID"
        End If
    Else
        txtCustNum.Text = ""
        txtCustNum.SetFocus
    End If
End Sub

Private Sub txtOrderNum_LostFocus()
    
    If Len(txtOrderNum.Text) = 0 Then Exit Sub
    
    If Trim$(txtOrderNum.Text) <> "" Then
        txtOrderNum.Text = UCase(txtOrderNum.Text)
        
        sqlStmt = "SELECT * FROM CARGO_ACTIVITY"
        
        ''If Season.Text <> Format(DateAdd("m", 4, Now()), "yyyy") Then
        ''    sqlStmt = sqlStmt & "_" & Season.Text
        ''End If
        
        sqlStmt = sqlStmt & " WHERE ORDER_NUM = '" & Trim$(txtOrderNum.Text) & "'"
        sqlStmt = sqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(txtCustNum.Text))
        sqlStmt = sqlStmt & " AND SERVICE_CODE <> 12"
        sqlStmt = sqlStmt & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
        sqlStmt = sqlStmt & " ORDER BY PALLET_ID,ARRIVAL_NUM,ACTIVITY_NUM"
        Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.RECORDCOUNT > 0 Then
            iLotNumP = Trim$(dsCARGO_ACTIVITY.Fields("PALLET_ID").Value)
            
            Exit Sub
        Else
            txtOrderNum.Text = ""
            txtOrderNum.SetFocus
            MsgBox "Don't have this record, Please try again!", vbExclamation, "Order Number"
        End If
    End If
End Sub

Private Sub headPrint()
     Printer.FontBold = True
     Printer.FontItalic = False
     Printer.FontSize = 11
     Printer.Print Tab(40); "PORT OF WILMINGTON TALLY - KIWI FRUIT"
     Printer.FontBold = False
     Printer.Print ""
     Printer.Print ""
     Printer.Print "CUSTOMER: " & iCustString; Tab(100); "DATE: " & iDate
     Printer.FontSize = 10
     Printer.Print Tab(110); "Start: "; Tab(121); sTime
     Printer.Print Tab(110); "Finish: "; Tab(121); eTime
     
     If iServiceId = 8 Then   'Inbound
        Printer.Print Tab(90); "SHIPPING TYPE:INBOUND"
     ElseIf iServiceId = 11 Then                'transfer
        Printer.Print Tab(90); "SHIPPING TYPE:TRANSFER"
     Else                       'outbound
        Printer.Print Tab(90); "SHIPPING TYPE:OUTBOUND"
     End If
    
     Printer.Print Tab(2); "CHECKER: "; Tab(15); iLoginID
     Printer.Print Tab(2); "ORDER NUM: " & Trim$(txtOrderNum.Text)
     Printer.Print ""
     Printer.Print ""
     Printer.Print "BARCODE"; Tab(35); "DESCRIPTION"; Tab(70); "QTY"; Tab(78); "HATCH"; Tab(90); "RELEASED?"; Tab(109); "VESSEL"
     Printer.Print "_______________________________________________________________________________________________________"
     
End Sub

Private Sub detailPrint()

     
  dsCARGO_ACTIVITY_A.MOVEfirst
  iLotNumOld = Trim$(dsCARGO_ACTIVITY_A.Fields("PALLET_ID").Value)
  iPltNum = 1
  iPltNumPage = 1
  iRecordNum = 0
     
  While Not dsCARGO_ACTIVITY_A.EOF
    iRecordNum = iRecordNum + 1
    ''Printer.Print Tab(10); " "
    If iRecordNum = 1 Then
      ''Printer.Print Tab(10); " "
      lineNum = lineNum + 8
    End If
    
    'set new line for adjusting line distance
    If (lineNum = 30) Or (lineNum = 31) Then
      ''Printer.Print Tab(10); " "
      lineNum = lineNum + 1
    End If
        
    'get Inbound Qty received
    'If iServiceId = 8 Then
    If iLotNumOld <> Trim$(dsCARGO_ACTIVITY_A.Fields("PALLET_ID").Value) Then
      iPltNum = iPltNum + 1
      iPltNumPage = iPltNumPage + 1
      iLotNumOld = Trim$(dsCARGO_ACTIVITY_A.Fields("PALLET_ID").Value)
    End If
    
    iLotNumP = Trim$(dsCARGO_ACTIVITY_A.Fields("PALLET_ID").Value)
    getShipNameOrQtyReceived
    
    
    'get different service code
    If iServiceId <> Val(dsCARGO_ACTIVITY_A.Fields("SERVICE_CODE")) Then
      iServiceId = dsCARGO_ACTIVITY_A.Fields("SERVICE_CODE")
      If iServiceId = 5 Then
        iException = "TR"
      End If
      If iServiceId = 7 Then
        iException = "FR"
      End If
      If iServiceId = 9 Then
        iException = "RE"
      End If
      If iServiceId = 12 Then
        iException = "VO"
      End If
      If iServiceId = 13 Then
        iException = "DR"
      End If
    End If
         
    'get Vessel name per line
    'this change made by Adam Walter, Dec2006.  I have commented out substantial other parts
    'of this code, and caused some variables to become unused, because logically it is sufficient,
    'and my time is tight on projects.
    '' Paul modified on 3/7/2007 to add a close button
    If iServiceId <> 8 Then
       iMyVesselString = ""  'init
       'HD???? 5/1/2007 Rudy: put this if around it to fix it, were getting error 440
      sqlStmt = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '" & dsCARGO_ACTIVITY_A.Fields("ARRIVAL_NUM").Value & "'"
      Set dsVESSEL_NAME = OraDatabase.DbCreateDynaset(sqlStmt, 0&)

      If OraDatabase.LastServerErr = 0 And dsVESSEL_NAME.RECORDCOUNT > 0 Then
        iMyVesselString = dsVESSEL_NAME.Fields("VESSEL_NAME").Value
      End If
      
    End If
    
    sqlStmt = "SELECT NVL(SUBSTR(HATCH, 1, 2), 'NONE') THE_HATCH FROM CARGO_TRACKING WHERE PALLET_ID = '" & dsCARGO_ACTIVITY_A.Fields("PALLET_ID").Value & "' " _
        & "AND ARRIVAL_NUM = '" & dsCARGO_ACTIVITY_A.Fields("ARRIVAL_NUM").Value & "' " _
        & "AND RECEIVER_ID = '" & dsCARGO_ACTIVITY_A.Fields("CUSTOMER_ID").Value & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    sHatch = dsSHORT_TERM_DATA.Fields("THE_HATCH").Value
    
    sqlStmt = "SELECT COUNT(*) THE_COUNT FROM RELEASED_CARGO WHERE ARRIVAL_NUM = '" & dsCARGO_ACTIVITY_A.Fields("ARRIVAL_NUM").Value & "' " _
        & "AND HATCH_DECK = '" & sHatch & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value = 0 Then
        sReleased = "NO"
    Else
        sReleased = "YES"
    End If
                
    'Printer.Print Tab(16); dsCARGO_ACTIVITY_A.FIELDS("ORDER_NUM").Value; Tab(38); dsCARGO_ACTIVITY_A.FIELDS("PALLET_ID").Value; Tab(68); iCommName + "  " + iException; Tab(98); dsCARGO_ACTIVITY_A.FIELDS("QTY_CHANGE").Value; Tab(107); iMyVesselString
    Printer.Print dsCARGO_ACTIVITY_A.Fields("PALLET_ID").Value; Tab(35); iVariety; Tab(70); dsCARGO_ACTIVITY_A.Fields("QTY_CHANGE").Value & " " & iException; Tab(80); sHatch; Tab(94); sReleased; Tab(109); Trim(iMyVesselString)
    totalCount = totalCount + Val(dsCARGO_ACTIVITY_A.Fields("QTY_CHANGE").Value)
    subTotalCount = subTotalCount + Val(dsCARGO_ACTIVITY_A.Fields("QTY_CHANGE").Value)
            
         
     lineNum = lineNum + 2
     If iPltNumPage = 25 Then
        subSummaryPrint
        lineNum = 0
        iRecordNum = 0
        subTotalCount = 0
        iSubTotalCount1 = 0
        iPltNumPage = 0
        Printer.NewPage
        headPrint
     End If
    
     iException = ""
     dsCARGO_ACTIVITY_A.MOVENEXT
     
  Wend
     
  theEnd = True
  subSummaryPrint
    
End Sub

Private Sub getStartAndEndTime()
    
    sqlStmt = "SELECT Min(DATE_OF_ACTIVITY) as aMin, Max(DATE_OF_ACTIVITY) as aMax FROM CARGO_ACTIVITY"
        
    ''If Season.Text <> Format(DateAdd("m", 4, Now()), "yyyy") Then
    ''    sqlStmt = sqlStmt & "_" & Season.Text
    ''End If
    
    sqlStmt = sqlStmt & " WHERE ORDER_NUM = '" & Trim$(txtOrderNum.Text) & "'"
    sqlStmt = sqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(txtCustNum.Text))
    sqlStmt = sqlStmt & " AND SERVICE_CODE <> 12"
    sqlStmt = sqlStmt & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
    sqlStmt = sqlStmt & " ORDER BY PALLET_ID,ARRIVAL_NUM,ACTIVITY_NUM"
    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If Not IsNull(dsCARGO_ACTIVITY.Fields("aMin")) Then
            myDate = Format(Trim$(dsCARGO_ACTIVITY.Fields("aMin").Value), "MM/DD/YYYY HH:MM:SS")
            iPosition1 = InStr(1, myDate, " ")
            iDate = Left$(myDate, iPosition1)

            myDateS = Format(Trim$(dsCARGO_ACTIVITY.Fields("aMin").Value), "MM/DD/YYYY HH:MM:SS")
            iPosition1 = InStr(1, myDateS, " ")
            sTime = Left$(Trim$(Right$(myDateS, Len(myDateS) - iPosition1)), 8)
            'end time
            myDateE = Format(Trim$(dsCARGO_ACTIVITY.Fields("aMax").Value), "MM/DD/YYYY HH:MM:SS")
            iPosition2 = InStr(1, myDateE, " ")
            eTime = Left$(Trim$(Right$(myDateE, Len(myDateE) - iPosition2)), 8)
        Else
            iDate = " "
            sTime = " "
            eTime = " "
        End If
    Else
        MsgBox "Oracle Error Occurred While updating cargo activity Table.", vbExclamation, "Oracle Errors"
        Exit Sub
    End If
    
    
End Sub

Private Sub getShipNameOrQtyReceived()
        
    'get Commodity code from cargo_tracking and get QTY received if service code is 8
    sqlStmt = "SELECT * FROM CARGO_TRACKING"
        

    
    sqlStmt = sqlStmt & " WHERE PALLET_ID = '" & iLotNumP & "'"
    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.RECORDCOUNT > 0 Then
        iCommP = dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value
        
        If dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value = 8105 And dsCARGO_TRACKING.Fields("RECEIVER_ID").Value = 1512 Then
            ' this check because Kiwis get extra cargo_description printing
            iVariety = dsCARGO_TRACKING.Fields("CARGO_DESCRIPTION").Value & " " & dsCARGO_TRACKING.Fields("REMARK").Value
        ElseIf Not IsNull(dsCARGO_TRACKING.Fields("CARGO_DESCRIPTION")) And Len(iLotNumP) = 20 Then
            iVariety = " " & Left$(Trim$(dsCARGO_TRACKING.Fields("CARGO_DESCRIPTION").Value), 14)
        Else
            iVariety = ""
        End If
        
'        If Not IsNull(dsCARGO_TRACKING.FIELDS("QTY_RECEIVED")) Then
'            iQtyReceived = dsCARGO_TRACKING.FIELDS("QTY_RECEIVED").Value
'        Else
'            iQtyReceived = 0
'        End If
'
    End If
    
    'get Commodity description from commodity profile
    sqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Val(iCommP)
    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RECORDCOUNT > 0 Then
        iCommName = Trim$(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value)
    End If
    
    
End Sub


Private Sub printtally()
    
    
    getShipNameOrQtyReceived
    
    getStartAndEndTime
    
    'get total records for the customer at that order number
    sqlStmt = "SELECT * FROM CARGO_ACTIVITY"
            

        
    sqlStmt = sqlStmt & " WHERE ORDER_NUM = '" & Trim$(txtOrderNum.Text) & "'"
    sqlStmt = sqlStmt & " AND CUSTOMER_ID = " & Val(Trim$(txtCustNum.Text))
    sqlStmt = sqlStmt & " AND SERVICE_CODE <> 12"
    sqlStmt = sqlStmt & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <>'VOID')"
    sqlStmt = sqlStmt & " ORDER BY PALLET_ID,ARRIVAL_NUM,ACTIVITY_NUM"
    Set dsCARGO_ACTIVITY_A = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
    iServiceId = dsCARGO_ACTIVITY_A.Fields("SERVICE_CODE").Value
    iTotalRecordNum = dsCARGO_ACTIVITY_A.RECORDCOUNT
    'get checker
    If Not IsNull(dsCARGO_ACTIVITY_A.Fields("ACTIVITY_ID").Value) Then
        iActID = Trim$(dsCARGO_ACTIVITY_A.Fields("ACTIVITY_ID").Value)
        sqlStmt1 = "SELECT * FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = " & Val(iActID)
        Set dsPERSONNEL = OraDatabase.DbCreateDynaset(sqlStmt1, 0&)
        iLoginID = Trim$(dsPERSONNEL.Fields("LOGIN_ID").Value)
    Else
        iLoginID = " "
    End If
    
    'print records
    If iTotalRecordNum <> 0 Then
        'print the title
        If LCase$(Environ$("USERNAME")) = "tally" Then
            Printer.Copies = 3
        Else
            Printer.Copies = 1
        End If
        
        headPrint
        
        Printer.FontBold = False
        Printer.FontSize = 10
        'print each record
        detailPrint
        'print the foot
        
        cmdPrint.Enabled = False
        Printer.EndDoc
        Exit Sub
    Else
        MsgBox "No change happened", vbExclamation, "Checker Tally"
    End If

End Sub

'Private Sub CREATEFILE()
'    Dim i As Integer
'    Dim iPosition As Integer
'    Dim iMyChar As String
'    Dim giFileNumMove As Integer
'    Dim gsFileNameMove As String
'    Dim iRecordType As String
'    Dim iFileCode As String
'    Dim iFileDescription As String
'    Dim iLoadNumber As String
'    Dim iLoc As String
'    Dim iDate As String
'    Dim iTruckName As String
'    Dim iTrailerLicense As String
'    Dim iTruckState As String
'    Dim iDateIn As String
'    Dim iTimeIn As String
'    Dim iDateOut As String
'    Dim iTimeOut As String
'    Dim iTrailerTemp As String
'    Dim iUserCode As String
'    Dim iDateEntered As String
'    Dim iTimeEntered As String
'    Dim iRecordType2 As String
'    Dim iOrderNumber As String
'    Dim iLoadSequence As String
'    Dim iPalletTag As String
'    Dim iItemAttributes As String
'    Dim iItemDescription As String
'    Dim iCartonsShipped As String
'    Dim iString1 As String
'    Dim iString2 As String
'
'    iRecordType = "H"
'    iFileCode = "PBFS"
'    iFileDescription = "Pallet Box and Frup Shipping"
'    iLoadNumber = ""
'    iLoc = "WIL"
'    iDate = ""
'    iTruckName = ""
'    iTrailerLicense = ""
'    iTruckState = ""
'    iDateIn = ""
'    iTimeIn = ""
'    iDateOut = ""
'    iTimeOut = ""
'    iTrailerTemp = ""
'    iUserCode = ""
'    iDateEntered = ""
'    iTimeEntered = ""
'    iRecordType2 = "P"
'    iOrderNumber = ""
'    iLoadSequence = ""
'    iPalletTag = ""
'    iItemAttributes = ""
'    iItemDescription = ""
'    iCartonsShipped = ""
'    iString1 = ""
'    iString2 = ""
'
'    i = 0
'    'GET RECORDS FOR OUTPUT
'    sqlStmt = "SELECT * FROM CARGO_ACTIVITY"
'
'
'
'    sqlStmt = sqlStmt & " Where CUSTOMER_ID = " & Val(Trim$(txtCustNum.Text))
'    sqlStmt = sqlStmt & " AND ORDER_NUM = '" & Trim$(txtOrderNum.Text) & "'"
'    sqlStmt = sqlStmt & " AND SERVICE_CODE <>12"
'    sqlStmt = sqlStmt & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')"
'    sqlStmt = sqlStmt & " AND TRANSMIT IS NULL"
'    sqlStmt = sqlStmt & " ORDER BY PALLET_ID,ARRIVAL_NUM, ACTIVITY_NUM"
'
'    Set dsCARGO_ACTIVITY = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 Then
'        If dsCARGO_ACTIVITY.RECORDCOUNT > 0 Then
'        'OUTPUT INTO FILE
'            iFileName = Trim$(txtOrderNum.Text) & ".txt"
'            'gsFileName = "W:\ship-out\" & iFileName
'            gsFileName = "V:\ship-out\" & iFileName
'            sDirChk = Dir$(gsFileName)
'            While sDirChk <> ""
'                i = i + 1
'                If i = 1 Then
'                    iFileName = Left$(iFileName, Len(iFileName) - 4)
'                Else
'                    iFileName = Left$(iFileName, Len(iFileName) - 5)
'                End If
'                Select Case i
'                Case 1: iMyChar = "A"
'                Case 2: iMyChar = "B"
'                Case 3: iMyChar = "C"
'                Case 4: iMyChar = "D"
'                Case 5: iMyChar = "E"
'                Case 6: iMyChar = "F"
'                Case 7: iMyChar = "G"
'                Case 8: iMyChar = "H"
'                Case 9: iMyChar = "I"
'                Case 10: iMyChar = "J"
'                Case Else:  MsgBox "File Number Is Out Of Rang. Please Contact Shunchao.", vbInformation, "File Number"
'                            Exit Sub
'                End Select
'                iFileName = iFileName & iMyChar & ".txt"
'                'gsFileName = "W:\ship-out\" & iFileName
'                gsFileName = "V:\ship-out\" & iFileName
'                sDirChk = Dir$(iFileName)
'            Wend
'
'            giFileNum = FreeFile()
'            Open gsFileName For Output As #giFileNum
'
'            giFileNumMove = FreeFile()
'            gsFileNameMove = "V:\opp-ship-out\" & iFileName
'            Open gsFileNameMove For Output As #giFileNumMove
'
'            'sqlStmt = "SELECT * FROM PL_ORDER_HEAD WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) & "'"
'            sqlStmt = "SELECT * FROM PL_ORDER_HEAD WHERE ORDER_NUM like '%" & Trim$(txtOrderNum.Text) & "'"
'            Set dsPL_ORDER_HEAD = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
'            If OraDatabase.LastServerErr = 0 Then
'                iOrderNumber = Trim$(dsPL_ORDER_HEAD.FIELDS("ORDER_NUM").Value)
'                iLoadNumber = Trim$(dsPL_ORDER_HEAD.FIELDS("LOAD_NUM").Value)
'            Else
'                MsgBox "Oracle Error Occurred While Processing CT, CA tables.", vbExclamation, "Oracle Errors"
'                Exit Sub
'            End If
'
'            iDate = Format$(Trim$(dsCARGO_ACTIVITY.FIELDS("DATE_OF_ACTIVITY").Value), "MM/DD/YYYY")
'            iDateIn = Left$(myDateS, 10)
'            iDateOut = Left$(myDateE, 10)
'            iTimeIn = Mid$(myDateS, 12, 5)
'            iTimeOut = Mid$(myDateE, 12, 5)
'
'            iUserCode = Trim$(dsCARGO_ACTIVITY.FIELDS("ACTIVITY_ID").Value)
'            iDateEntered = iDateIn
'            iTimeEntered = iTimeIn
'            iString1 = iRecordType & Right$("     " & iFileCode, 5) & Right$("                              " & iFileDescription, 30) & Right$("          " & iLoadNumber, 10) & Right$("   " & iLoc, 3) & Right$("          " & iDate, 10) & Right$("                                   " & iTruckName, 35) & Right$("               " & iTrailerLicense, 15) & Right$("  " & iTruckState, 2) & Right$("          " & iDateIn, 10) & Right$("     " & iTimeIn, 5) & Right$("          " & iDateOut, 10) & Right$("     " & iTimeOut, 5) & Right$("      " & iTrailerTemp, 6) & Right$("      " & iUserCode, 6) & Right$("          " & iDateEntered, 10) & Right$("     " & iTimeEntered, 5)
'            Print #giFileNum, iString1
'            Print #giFileNumMove, iString1
'            While Not dsCARGO_ACTIVITY.EOF
'                iString2 = iRecordType2 & Right$("          " & iOrderNumber, 10) & Right$("   " & iLoadSequence, 3) & Right$("          " & Trim$(dsCARGO_ACTIVITY.FIELDS("PALLET_ID").Value), 10) & Right$("                    " & iItemAttributes, 20) & Right$("                                        " & iItemDescription, 40) & Right$("          " & Trim$(dsCARGO_ACTIVITY.FIELDS("QTY_CHANGE").Value), 10)
'                Print #giFileNum, iString2
'                Print #giFileNumMove, iString2
'                dsCARGO_ACTIVITY.MOVENEXT
'            Wend
'            Close #giFileNum
'            Close #giFileNumMove
'
'            dsCARGO_ACTIVITY.MOVEfirst
'            'UPDATE TRANSMIT TO Y
'            OraSession.BeginTrans
'            While Not dsCARGO_ACTIVITY.EOF
'                dsCARGO_ACTIVITY.edit
'                dsCARGO_ACTIVITY.FIELDS("TRANSMIT").Value = "Y"
'                dsCARGO_ACTIVITY.Update
'                dsCARGO_ACTIVITY.MOVENEXT
'            Wend
'
'            If OraDatabase.LastServerErr = 0 Then
'                OraSession.CommitTrans
'            Else
'                OraSession.RollBack
'                MsgBox "Oracle Error Occurred While updating cargo activity Table.", vbExclamation, "Oracle Errors"
'                Exit Sub
'            End If
'        End If
'    Else
'        MsgBox "Oracle Error Occurred While Processing Cargo Activity Table.", vbExclamation, "Oracle Errors"
'        Exit Sub
'    End If
'
'End Sub

Private Sub startPrinttally()

    iCommName = ""
    totalCount = 0
    iTotalCount1 = 0
    iException = ""
    iQtyReceived = 0
    iSubTotalCount1 = 0
    subTotalCount = 0
    theEnd = False
    iNoPL = False
    'get ship name
    
'    If Trim$(txtCustNum.Text) = 1512 And iOnce = False Then
'        'CHECK THE PREORDER
'        sqlStmt = "SELECT * FROM PL_ORDER_HEAD WHERE ORDER_NUM like '%" & Trim$(txtOrderNum.Text) & "'"
'        'sqlStmt = "SELECT * FROM PL_ORDER_HEAD WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) & "'"
'        Set dsPL_ORDER_HEAD = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
'        If dsPL_ORDER_HEAD.RECORDCOUNT = 0 Then
'            'iResponse = Msg Box("This order is not included in Pick List. Do you still need to continue?", vbQuestion + vbYesNo, "Confirm Print Tally Sheet")
'            'If iResponse <> vbYes Then
'
'            '    On Error GoTo 0
'            '    Exit Sub
'            'Else
'             iNoPL = True
'            'End If
'        Else
'            'GET ORDER TOTAL FROM PICK LIST AND TODAY'S SCANNED
'            'sqlStmt = "SELECT SUM(PALLETS_ORDERED) SUM_PALLETS FROM PL_ORDER_DETAIL WHERE ORDER_NUM = '" & Trim$(txtOrderNum.Text) & "'"
'            sqlStmt = "SELECT SUM(PALLETS_ORDERED) SUM_PALLETS FROM PL_ORDER_DETAIL WHERE ORDER_NUM = '" & Trim$(dsPL_ORDER_HEAD.FIELDS("ORDER_NUM").Value) & "'"
'            Set dsPL_ORDER_DETAIL = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
'
'            sqlStmt = "SELECT COUNT(DISTINCT(PALLET_ID)) COUNT_PALLETS FROM CARGO_ACTIVITY"
'
'
'
'            sqlStmt = sqlStmt & " WHERE ORDER_NUM = '" & Trim$(txtOrderNum.Text) & "'"
'            sqlStmt = sqlStmt & " AND CUSTOMER_ID = " & Val(txtCustNum.Text)
'            sqlStmt = sqlStmt & " AND SERVICE_CODE <> 12"
'            sqlStmt = sqlStmt & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')"
'            Set dsSUM_PALLETS = OraDatabase.DbCreateDynaset(sqlStmt, 0&)
'
'            If dsPL_ORDER_DETAIL.FIELDS("SUM_PALLETS").Value <> dsSUM_PALLETS.FIELDS("COUNT_PALLETS").Value Then
'                iResponse = MsgBox("Number of Pallets from Pick List is not matching counts which you scanned. Plts on pick list: " & dsPL_ORDER_DETAIL.FIELDS("SUM_PALLETS").Value & ". Plts you scanned: " & dsSUM_PALLETS.FIELDS("COUNT_PALLETS").Value & ". Do you still need to continue?", vbQuestion + vbYesNo, "Confirm Print Tally Sheet")
'                If iResponse <> vbYes Then
'                    On Error GoTo 0
'                    Exit Sub
'                End If
'            End If
'        End If
'        'PRINT TALLY SHEET
'        Call printtally
'
'        'CREATE THE FILE TO PARTICULAR FOLD
'        If Not iNoPL Then
'            Call CREATEFILE
'        End If
'
'    Else
'        'PRINT TALLY SHEET
        Call printtally
'    End If
        
    On Error GoTo 0
End Sub
