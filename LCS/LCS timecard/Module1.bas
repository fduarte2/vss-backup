Attribute VB_Name = "Module1"
Option Explicit                 '2853 3/29/07 Rudy:

Global BCField(2) As String
'Global BCField(1) As String
Global OraSession As Object
Global OraDatabase As Object
Global SUPVName As String
Global DB As String
Global Login As String
Global colSUPVNAME As New Collection   '' key=EMP_ID, value=EMP_NAME
Global colSUPVID As New Collection     '' value=EMP_ID, NO key
Global colUserID As New Collection     '' No Key, value=USER_ID (ie employee #)
Global colUserName As New Collection      '' key=USER_ID, value=USER_NAME
Global colUserPWD As New Collection       '' key=USER_ID, value=USER_PASSWORD
Global colTimeCardUser As New Collection  '' key=USER_ID, value=USER_PASSWORD
Global uid As String
Global uname As String
Global upwd As String

Sub iniConnection()

  DB = "LCS"
  Login = "LABOR/LABOR"

  '2853 3/29/07 Rudy: TEMP, original 2 above
'  DB = "BNI.DEV"
'  Login = "LABOR/LABOR_DEV"

End Sub

Function GetValue(DataValue As Variant, Default As Variant) As Variant
  If IsNull(DataValue) Then
    GetValue = Default
  Else
    GetValue = DataValue
  End If
End Function

Public Sub Main()
  
  Dim sEmpID As String
  Dim dDate As Date
  
  If parseCommand(sEmpID, dDate) Then
    CreateSession
    BCField(2) = UCase(Trim(sEmpID))
    BCField(1) = Format(dDate, "MM/DD/YYYY")
    uid = BCField(2)
    Load frmGrid
    frmGrid.bNoReturn = True
    frmGrid.PrintTimesheet
    Unload frmGrid
  Else
    frmLogin.Show
  End If
  
End Sub

Private Function parseCommand(ByRef psEmpID As String, ByRef pdDate As Date) As Boolean
  
  Dim sArg() As String
  Dim i As Integer
  Dim bEmpID As Boolean
  Dim bDate As Boolean
  
  bEmpID = False
  bDate = False
  If Len(Command$) > 0 Then
    sArg = Split(Command$, "-")
    For i = 0 To UBound(sArg)
      If Len(sArg(i)) > 0 Then
        If Left$(sArg(i), 1) = "e" Then
          psEmpID = Trim$(Right$(sArg(i), Len(sArg(i)) - 1))
          bEmpID = True
        End If
        If Left$(sArg(i), 1) = "d" Then
          pdDate = CDate(Mid$(sArg(i), 2, 2) & "/" & Mid$(sArg(i), 4, 2) & "/" & Mid$(sArg(i), 6, 4))
          bDate = True
        End If
      End If
    Next i
  End If
  
  parseCommand = (bEmpID And bDate)
  
End Function

Private Sub CreateSession()
  On Error GoTo CS_ErrHandler
  Set OraSession = CreateObject("OracleInProcServer.XOraSession")
  Set OraDatabase = OraSession.OpenDatabase("LCS", "LABOR/LABOR", 0&)
  'Set OraDatabase = OraSession.OpenDatabase("BNI.DEV", "LABOR/LABOR_DEV", 0&)    '2853 3/29/07 Rudy: TEMP for dev, orig above
  
  'Set OraDatabase = OraSession.OpenDatabase("ISD", "LABOR/LABOR", 0&)
  If OraDatabase.LastServerErr = 0 Then
   ' Login to Oracle Successful!!
  Else
    MsgBox "Login to Oracle Failed!", vbInformation, "Oracle Connection Failure"
  End If
  Exit Sub
CS_ErrHandler:
  If Err.Number = 440 Then
    MsgBox "Incorrect Server Name/User ID/Password. Closing the Application", vbCritical, "Application Termination"
    Err.Clear
    Dim ErrFlag As Boolean                          '2853 3/29/07 Rudy: wasn't dim'd before
    ErrFlag = True
  Else
    MsgBox Err.Description + " " + Str(Err.Number)
  End If
End Sub

Public Sub PrintTimesheet4SUPV()
  
'  SSDBGrid1.PrintData ssPrintAllRows, False, True
  Dim indxCtr As Integer, ColCtr As Integer, GridData As String, rowCntr As Integer
  Dim gsSqlStmt As String, CurrDay As Integer, WeekNo As String, DayTotalHrs As String
  Dim dsHOURLY_DETAIL As Object, ds_USER As Object, dsEMPLOYEE As Object
  Dim dsDAILY_HIRE_LIST As Object, ds_HOURLY_SUM As Object, ds_HOURLY_DAYTOTAL As Object
  Dim HireDt As String, TimeIn As String, TimeOut As String, User As String
  Dim StartTime As String, EndTime As String, Duration As String, Earn As String
  Dim Duration1 As String, Earn1 As String, TwoRec As Boolean
  Dim MnDiff As Integer, PhysMns As Integer, PhysHrs As Integer, TimeDiff As String, Time_Entry As String
  Dim SrvcComm As String
  Dim dsWEEKS As Object
  Dim endDate As String
  Dim txtTotalHrs As String  '2853 3/29/07 Rudy: wasn't dim'd before (cut and copied from frmGrid, where it's a textbox
  
  ' Read it from the database
  'get week number and start date and end date
  gsSqlStmt = "SELECT * FROM WEEKS WHERE START_DATE<TO_DATE('" & Format(CDate(BCField(1)), "MM/DD/YYYY") & "','MM/DD/YYYY') +1"
  gsSqlStmt = gsSqlStmt & " AND END_DATE>=TO_DATE('" & Format(CDate(BCField(1)), "MM/DD/YYYY") & "','MM/DD/YYYY')"
  Set dsWEEKS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
  If dsWEEKS.recordcount <= 0 Then
    'MsgBox "NO ANY RECORDS, TRY LATER.", vbInformation, "HOURLY DETAILS"
    MsgBox "Not any records, try again later.", vbInformation, "HOURLY DETAILS"    '2853pt2 4/11/2007 Rudy:
    Exit Sub
  End If
  
  endDate = Format(dsWEEKS.fields("END_DATE").Value, "MM/DD/YYYY")
  
  'Open App.Path + "\TimeCard.txt" For Output As #1
  Printer.Font = "Courier New"
  Printer.Print " "
  Printer.Print " "
  Printer.Print " "
  Printer.Print String(5, " ") + "NO.    " + BCField(2) + "                                        " + String(15, " ") + "Pay Period Ending"
  Printer.Print String(5, " ") + "                              " + String(24, " ") + String(15, " ") + endDate
  Printer.Print String(5, " ") + "NAME    : " + SUPVName
  Printer.Print String(5, " ") + "PRINTED : " + Format(Now, "mm/dd/yyyy hh:mm:ss AMPM")
  Printer.Print " "
  Printer.Print String(5, " ") + String(43, " ") + "TIME CARD"
  Printer.Print String(5, " ") + String(117, "-")
  Printer.Print String(5, " ") + "DATE         IN      OUT     SUPERVISOR  SRVC-COMM-EQUIP-SPC  START   END     HRS   TYPE DAILY TOTAL   ENTRY TIME"
  Printer.Print String(5, " ") + String(117, "-")
  
  gsSqlStmt = "SELECT * FROM DAILY_HIRE_LIST WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
  gsSqlStmt = gsSqlStmt & " AND HIRE_DATE >= TO_DATE('" & Format$(dsWEEKS.fields("START_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
  gsSqlStmt = gsSqlStmt & " AND HIRE_DATE < TO_DATE('" & Format$(dsWEEKS.fields("END_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1"
  gsSqlStmt = gsSqlStmt & " ORDER BY HIRE_DATE"
  Set dsDAILY_HIRE_LIST = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
  
  If dsDAILY_HIRE_LIST.EOF And dsDAILY_HIRE_LIST.bof Then
    'No data for the current week
  Else
    dsDAILY_HIRE_LIST.MoveFirst
    gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
    gsSqlStmt = gsSqlStmt & " AND HIRE_DATE >= TO_DATE('" & Format$(dsWEEKS.fields("START_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
    gsSqlStmt = gsSqlStmt & " AND HIRE_DATE < TO_DATE('" & Format$(dsWEEKS.fields("END_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1"
    Set ds_HOURLY_SUM = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
    If ds_HOURLY_SUM.EOF And ds_HOURLY_SUM.bof Then
      txtTotalHrs = ""
    ElseIf IsNull(ds_HOURLY_SUM.fields("Sum").Value) Or Trim(ds_HOURLY_SUM.fields("Sum").Value) = vbNullString Then
      txtTotalHrs = ""
    Else
      txtTotalHrs = ds_HOURLY_SUM.fields("Sum").Value
    End If
    
    Do While Not dsDAILY_HIRE_LIST.EOF
      'Fill Hours from Hourly Detail
      gsSqlStmt = "SELECT * FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "'"
      gsSqlStmt = gsSqlStmt & " AND HIRE_DATE >=TO_DATE('" & Format(dsDAILY_HIRE_LIST.fields("HIRE_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')"
      gsSqlStmt = gsSqlStmt & " AND HIRE_DATE <TO_DATE('" & Format(dsDAILY_HIRE_LIST.fields("HIRE_DATE").Value, "MM/DD/YYYY") & "','MM/DD/YYYY')+1"
      gsSqlStmt = gsSqlStmt & " AND DURATION IS NOT NULL"
      gsSqlStmt = gsSqlStmt & " ORDER BY ROW_NUMBER"
      Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
      
      If dsHOURLY_DETAIL.EOF And dsHOURLY_DETAIL.bof Then
      'Do Nothing
      Else
        dsHOURLY_DETAIL.MoveFirst
        Do While Not dsHOURLY_DETAIL.EOF
          '2853 3/29/07 Rudy: reinit all 18 variables, just like it's the first time thru (Note a few may be reinitialized, but do all 4 thoroughness ) :
'          2853pt2 4/11/2007 Rudy:  A little too ambitious, need these four with data
'          DayTotalHrs = ""
'          HireDt = ""
'          TimeIn = ""
'          TimeOut = ""
          User = ""
          StartTime = ""
          EndTime = ""
          Duration = ""
          Earn = ""
          Duration1 = ""
          Earn1 = ""
          TwoRec = False
          MnDiff = 0
          PhysMns = 0
          PhysHrs = 0
          TimeDiff = ""
          Time_Entry = ""
    
          SrvcComm = ""
        
          If HireDt = dsDAILY_HIRE_LIST.fields("Hire_Date").Value Then
            HireDt = ""
          Else
            HireDt = dsDAILY_HIRE_LIST.fields("Hire_Date").Value
            TimeIn = ""
            TimeOut = ""
            DayTotalHrs = ""
          End If
          
          gsSqlStmt = "SELECT SUM(DURATION) SUM FROM HOURLY_DETAIL WHERE EMPLOYEE_ID = '" & Trim$(BCField(2)) & "' AND HIRE_DATE = to_date('" + Str(dsDAILY_HIRE_LIST.fields("Hire_Date").Value) + "','mm/dd/yy') and duration is not null "
          Set ds_HOURLY_DAYTOTAL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
          If ds_HOURLY_DAYTOTAL.EOF And ds_HOURLY_DAYTOTAL.bof Then
            DayTotalHrs = ""
          ElseIf IsNull(ds_HOURLY_DAYTOTAL.fields("Sum").Value) Or Trim(ds_HOURLY_DAYTOTAL.fields("Sum").Value) = vbNullString Then
            DayTotalHrs = ""
          ElseIf DayTotalHrs = ds_HOURLY_DAYTOTAL.fields("Sum").Value Then
            DayTotalHrs = ""
          Else
            DayTotalHrs = ds_HOURLY_DAYTOTAL.fields("Sum").Value
          End If
          
          If IsNull(dsDAILY_HIRE_LIST.fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_In").Value) = vbNullString Then
            TimeIn = "Null"
          ElseIf TimeIn = dsDAILY_HIRE_LIST.fields("Time_In").Value Then
            TimeIn = ""
          Else
            TimeIn = dsDAILY_HIRE_LIST.fields("Time_In").Value
          End If
          
          If TimeOut = "Null" Then
            TimeOut = ""
          ElseIf IsNull(dsDAILY_HIRE_LIST.fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_Out").Value) = vbNullString Then
            TimeOut = "Null"
          ElseIf TimeOut = dsDAILY_HIRE_LIST.fields("Time_Out").Value Then
            TimeOut = ""
          Else
            TimeOut = dsDAILY_HIRE_LIST.fields("Time_Out").Value
          End If
          
          If TimeOut <> "" And TimeIn <> "" And TimeOut <> "Null" Then
            MnDiff = DateDiff("n", Format(TimeIn, "hh:nnAM/PM"), Format(TimeOut, "hh:nnAM/PM"))
            PhysHrs = Fix(MnDiff / 60)
            PhysMns = MnDiff - PhysHrs * 60
            TimeDiff = Trim(Str(PhysHrs)) + ":" + Trim(Str(PhysMns))
          Else
            TimeDiff = ""
          End If
          
'          gsSqlStmt = "Select EMPLOYEE_NAME from  EMPLOYEE where EMPLOYEE_ID ='" + dsHOURLY_DETAIL.Fields("User_Id").Value + "'"
          gsSqlStmt = "Select USER_NAME from  LCS_USER where USER_ID ='" + dsHOURLY_DETAIL.fields("User_Id").Value + "'"
          
          Set ds_USER = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
          
          If (ds_USER.recordcount) > 0 Then
'            User = ds_USER.Fields("EMPLOYEE_NAME").Value
            User = ds_USER.fields("USER_NAME").Value
          Else
            'MsgBox "Supervisor Id is incorrect, Try it again.", vbInformation, "SUPERVISOR ID"
            MsgBox "Supervisor Id is incorrect, try again.", vbInformation, "SUPERVISOR ID"         '2853pt2 4/11/2007 Rudy:
            Exit Sub
          End If
          
'          gsSqlStmt = "Select User_Name from LCS_USER where User_ID = '" + dsHOURLY_DETAIL.Fields("User_ID").Value + "'"
'          Set ds_USER = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
'
'          If (ds_USER.recordcount) > 0 Then
'            User = ds_USER.Fields("User_Name").Value
'          Else
'            User = "UnKnown"
'          End If
          
          StartTime = GetValue(dsHOURLY_DETAIL.fields("Start_Time").Value, "")
          EndTime = GetValue(dsHOURLY_DETAIL.fields("End_Time").Value, "")
          
          ' Added 03/23/2000 by Bruce LeBrun to Display the time of entry
          Time_Entry = GetValue(dsHOURLY_DETAIL.fields("Time_Entry").Value, "")
          ' Added 04/10/2000 by Bruce LeBrun to Display the srvc-comm-asset-shift
          SrvcComm = Right("0000" & GetValue(dsHOURLY_DETAIL.fields("Service_Code").Value, "0000"), 4) & "-"
          SrvcComm = SrvcComm & Right("0000" & GetValue(dsHOURLY_DETAIL.fields("Commodity_Code").Value, "0000"), 4) & "-"
          SrvcComm = SrvcComm & Right("0000" & GetValue(dsHOURLY_DETAIL.fields("Equipment_ID").Value, "0000"), 4) & "-"
          SrvcComm = SrvcComm & Left(GetValue(dsHOURLY_DETAIL.fields("Special_Code").Value, "00") & "    ", 4)
          
          'Duration = dsHOURLY_DETAIL.fields("Duration").Value
          If IsNull(dsHOURLY_DETAIL.fields("Earning_Type_ID").Value) Or Trim(dsHOURLY_DETAIL.fields("Earning_Type_ID").Value) = vbNullString Then
            Duration = dsHOURLY_DETAIL.fields("Duration").Value            '2853 3/29/07 Rudy: per Inigo
            Earn = ""
            'Earn = "Null"
          ElseIf dsHOURLY_DETAIL.fields("Earning_Type_ID").Value <> "REG" Then
            Duration = dsHOURLY_DETAIL.fields("Duration").Value
            Earn = dsHOURLY_DETAIL.fields("Earning_Type_ID").Value
          ElseIf dsHOURLY_DETAIL.fields("Earning_Type_ID").Value = "REG" Then
            If dsHOURLY_DETAIL.fields("REG_Hrs").Value = vbNullString Or IsNull(dsHOURLY_DETAIL.fields("REG_Hrs").Value) Or IsNull(dsHOURLY_DETAIL.fields("OT_Hrs").Value) Or Trim(dsHOURLY_DETAIL.fields("OT_Hrs").Value) = vbNullString Then
              'Do Nothing
              ' Added 3/6/2000 by Bruce LeBrun to fix null REG_HRS/OT_HRS problem
              Duration = dsHOURLY_DETAIL.fields("duration").Value
              Earn = dsHOURLY_DETAIL.fields("Earning_Type_ID").Value
            ElseIf dsHOURLY_DETAIL.fields("REG_Hrs").Value <> 0 And dsHOURLY_DETAIL.fields("OT_Hrs").Value <> 0 Then
              'Add 2 records
              TwoRec = True
              Duration = dsHOURLY_DETAIL.fields("OT_Hrs").Value
              Earn = "OT"
              Duration1 = dsHOURLY_DETAIL.fields("REG_Hrs").Value
              Earn1 = "REG"
            Else
              If dsHOURLY_DETAIL.fields("REG_Hrs").Value = 0 Then
                Duration = dsHOURLY_DETAIL.fields("OT_Hrs").Value
                Earn = "OT"
              Else
                Duration = dsHOURLY_DETAIL.fields("REG_Hrs").Value
                Earn = "REG"
              End If
            End If
          End If
          If TwoRec = True Then
            Printer.Print Space(5) & Left(Format(HireDt, "mm/dd/yy") & Space(8), 8) & " " & Left(UCase(Format(HireDt, "ddd")) & "   ", 3) & " " & Left(Format(TimeIn, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Format(TimeOut, "hh:nnAM/PM") & Space(7), 7) & " " & Left(User & Space(11), 11) & " " & SrvcComm & "  " & Left(Format(StartTime, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Format(EndTime, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Duration & Space(4), 4) & "  " & Left(UCase(Earn) & Space(3), 3) & "  " & Left(DayTotalHrs & Space(4), 4) & Space(8) & Format(Time_Entry, "mm/dd hh:nnAM/PM")
            Printer.Print Space(83) & Left(Duration1 & Space(4), 4) & "  " & Left(UCase(Earn1) & Space(3), 3)
            TwoRec = False
            Earn1 = ""
            Duration1 = ""
          Else
            Printer.Print Space(5) & Left(Format(HireDt, "mm/dd/yy") & Space(8), 8) & " " & Left(UCase(Format(HireDt, "ddd")) & "   ", 3) & " " & Left(Format(TimeIn, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Format(TimeOut, "hh:nnAM/PM") & Space(7), 7) & " " & Left(User & Space(11), 11) & " " & SrvcComm & "  " & Left(Format(StartTime, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Format(EndTime, "hh:nnAM/PM") & Space(7), 7) & " " & Left(Duration & Space(4), 4) & "  " & Left(UCase(Earn) & Space(3), 3) & "  " & Left(DayTotalHrs & Space(4), 4) & Space(8) & Format(Time_Entry, "mm/dd hh:nnAM/PM")
          End If
          
          'Assign Values to Check with Next Record
          HireDt = dsDAILY_HIRE_LIST.fields("Hire_Date").Value
          If IsNull(dsDAILY_HIRE_LIST.fields("Time_In").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_In").Value) = vbNullString Then
            TimeIn = ""
          Else
            TimeIn = dsDAILY_HIRE_LIST.fields("Time_In").Value
          End If
          If IsNull(dsDAILY_HIRE_LIST.fields("Time_Out").Value) Or Trim(dsDAILY_HIRE_LIST.fields("Time_Out").Value) = vbNullString Then
            TimeOut = "Null"
          Else
            TimeOut = dsDAILY_HIRE_LIST.fields("Time_Out").Value
          End If
          DayTotalHrs = GetValue(ds_HOURLY_DAYTOTAL.fields("Sum").Value, 0)
          dsHOURLY_DETAIL.MoveNext
        Loop
      End If
      dsDAILY_HIRE_LIST.MoveNext
    Loop
  End If

  
  Printer.Print String(5, " ") + String(117, "-")
  Printer.Print String(5, " ") + String(75, " ") + "Weekly Total :" + txtTotalHrs
  Printer.Print String(5, " ") + String(117, "-")
  
  ' 03/23/2000 - Adding box for supervisors to hand write changes
  Printer.Print " "
  Printer.Print " "
  Printer.Print " "
  Printer.Print " "
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|   Date   |   Start   |    End    |  Hrs   | Pay Type |     Supv     |   Srvc   |  Comm   |  Equip  |  Spec  |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + "|" + Space(10) + "|     :     |     :     |        |          |              |          |         |         |        |"
  Printer.Print String(5, " ") + String(111, "-")
  Printer.EndDoc

  Exit Sub
  
'  For indxCtr = 0 To SSDBGrid1.Rows - 1
'    SSDBGrid1.Row = indxCtr
'
'    If Len(Trim(SSDBGrid1.Columns(0).Value)) < 12 Then
'      GridData = GridData + SSDBGrid1.Columns(0).Value + Space(11 - Len(Trim(SSDBGrid1.Columns(0).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(0).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(1).Value)) < 7 Then
'      GridData = GridData + SSDBGrid1.Columns(1).Value + Space(7 - Len(Trim(SSDBGrid1.Columns(1).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(1).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(2).Value)) < 7 Then
'      GridData = GridData + SSDBGrid1.Columns(2).Value + Space(7 - Len(Trim(SSDBGrid1.Columns(2).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(2).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(3).Value)) < 7 Then
'      GridData = GridData + SSDBGrid1.Columns(3).Value + Space(7 - Len(Trim(SSDBGrid1.Columns(3).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(3).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(4).Value)) < 25 Then
'      GridData = GridData + SSDBGrid1.Columns(4).Value + Space(25 - Len(Trim(SSDBGrid1.Columns(4).Value))) + " "
'    ElseIf Len(Trim(SSDBGrid1.Columns(4).Value)) > 25 Then
'      GridData = GridData + Left(SSDBGrid1.Columns(4).Value, 25)
'    Else
'      GridData = GridData + SSDBGrid1.Columns(4).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(5).Value)) < 7 Then
'      GridData = GridData + SSDBGrid1.Columns(5).Value + Space(7 - Len(Trim(SSDBGrid1.Columns(5).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(5).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(6).Value)) < 7 Then
'      GridData = GridData + SSDBGrid1.Columns(6).Value + Space(7 - Len(Trim(SSDBGrid1.Columns(6).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(6).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(7).Value)) < 5 Then
'      GridData = GridData + SSDBGrid1.Columns(7).Value + Space(5 - Len(Trim(SSDBGrid1.Columns(7).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(7).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(8).Value)) < 4 Then
'      GridData = GridData + SSDBGrid1.Columns(8).Value + Space(4 - Len(Trim(SSDBGrid1.Columns(8).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(8).Value + " "
'    End If
'    If Len(Trim(SSDBGrid1.Columns(9).Value)) < 6 Then
'      GridData = GridData + SSDBGrid1.Columns(9).Value + Space(6 - Len(Trim(SSDBGrid1.Columns(9).Value))) + " "
'    Else
'      GridData = GridData + SSDBGrid1.Columns(9).Value + " "
'    End If
'
'    Printer.Print String(5, " ") + GridData
'    GridData = ""
'  Next
  
'  Printer.Print String(5, " ") + String(95, "-")
'  Printer.Print String(5, " ") + String(68, " ") + "Weekly Total :" + txtTotalHrs
'  Printer.Print String(5, " ") + String(95, "-")
'  Printer.EndDoc
'  MsgBox "Time Card has been Printed Successfully", vbInformation, "Printing Successful"
'  'Close #1
'  'Shell "notepad.exe " + App.Path + "\TimeCard.txt", 1
'  'SendKeys "%" + "F" + "P" 'To Print the Data
'  'SendKeys "%" + "F" + "X" 'To Close NotePad
  
End Sub


Sub RetrieveOpsSUPV()

On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDatabase As Object
    Dim rs As Object
    Dim strSql As String
    
    
    '' Initialize variables for DB connection
    Call iniConnection
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        
        '' Prepare sql statement
        strSql = "select e.EMPLOYEE_ID, e.EMPLOYEE_NAME" & _
                    " from employee e" & _
                    " where e.EMPLOYEE_TYPE_ID='SUPV' and e.EMPLOYEE_SUB_TYPE_ID='OPS'" & _
                    " ORDER BY e.EMPLOYEE_ID"
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        
        '' Load values into colSUPVNAME(key-value) and colSUPVID (value only, no key)
        rs.MoveFirst
        Do While Not rs.EOF
            colSUPVNAME.Add rs.fields(1).Value, rs.fields(0).Value
            colSUPVID.Add rs.fields(0).Value
            rs.MoveNext
        Loop
    
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
    
    
    Else
        MsgBox "Error:" & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    Exit Sub
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        "RetrieveOpsSUPV"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End
    End If

End Sub

