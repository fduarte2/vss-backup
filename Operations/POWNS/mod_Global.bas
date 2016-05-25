Attribute VB_Name = "mod_Global"
Option Explicit

Global ordb As ADODB.Connection
Global str_DatabaseName As String
Global str_user As String
Global str_UPass As String
Global str_db_connection_string As String
Global str_db_Table_Name As String

Global Const str_slComboNames = "#0*; " & vbTab & "None" & "|#1*1;=" & vbTab & "EQ" & "|#2*2;>" & vbTab & "GT" & "|#3;<" & vbTab & "LT" & "|#4;<>" & vbTab & "NE" & "|#5;LIKE" & vbTab & "%" & "|#6;IN" & vbTab & "LIST" & "|#7;NOT IN" & vbTab & "LIST" & "|#8;=" & vbTab & "DATE" & "|#9;>" & vbTab & "DATE" & "|#10;<" & vbTab & "DATE"

Global Const str_FRMT_Date = "MM/DD/YYYY"
Global Const str_FRMT_Date_Hour = "MM/DD/YY HH:MM"

Global Const str_ConnStr_txt = "Driver={Microsoft Text Driver (*.txt; *.csv)};Dbq=%p;Extensions=asc,csv,tab,txt;"
Global Const str_ConnStr_xls = "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=""%p\%f"";"
'Global Const str_ConnStr_xls = "Driver={Microsoft Excel Driver (*.xls)};DriverId=790;Dbq=""%p\%f"";DefaultDir=%p;Mode=Read;"
Global Const str_ConnStr_dbf = "Driver={Microsoft dBASE Driver (*.dbf)};DriverID=277;Dbq=%p;Mode=Read;"
Global Const str_ConnStr_mdb = "Driver={Microsoft Access Driver (*.mdb)};Dbq=%f;Uid=Admin;Pwd=;Mode=Read;"
Global Const str_ConnStr_fox = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=%p;Collate=Machine;BACKGROUNDFETCH=No;DELETED=NO;"

Type CT_Field
    str_Name As String
    int_id As Integer
    bln_In As Boolean
End Type
    
Global arr_combo() As CT_Field
Global arr_colPos() As Integer
Global arr_combo_str As String
Global arr_combo_count As Integer
Global str_Format_Date_Received As String
Global int_FileFormat As Integer
'format  1 - Text
'        2 - Excel
'        3 - dBase
'        4 - Fox

Private Enum EXTENDED_NAME_FORMAT
    NameUnknown = 0
    NameFullyQualifiedDN = 1
    NameSamCompatible = 2
    NameDisplay = 3
    NameUniqueId = 6
    NameCanonical = 7
    NameUserPrincipal = 8
    NameCanonicalEx = 9
    NameServicePrincipal = 10
End Enum

Private Declare Function GetUserName Lib "advapi32" Alias "GetUserNameA" (ByVal lpBuffer As String, nSize As Long) As Long
Private Declare Function GetUserNameEx Lib "secur32.dll" Alias "GetUserNameExA" (ByVal NameFormat As EXTENDED_NAME_FORMAT, ByVal lpNameBuffer As String, ByRef nSize As Long) As Long

Private Declare Function lstrlenW Lib "kernel32" (ByVal lpString As Long) As Long

Public Function Get_Logon_Username() As String
    Dim Long_sBuffer As String
    Dim Long_Ret As Long
    Dim Short_buff As String
    Dim Short_nSize As Long
    Dim str_user As String
    Long_sBuffer = String(256, 0)
    Long_Ret = Len(Long_sBuffer)
     
    'returns full username ("John Doe")
    Long_Ret = GetUserNameEx(NameSamCompatible, Long_sBuffer, Long_Ret)
    str_user = left$(Long_sBuffer, lstrlenW(StrPtr(Long_sBuffer)))
    'returns short username ("jdoe")
    If Len(str_user) = 0 Then
        Short_buff = Space$(15)
        Short_nSize = Len(Short_buff)
     
        If GetUserName(Short_buff, Short_nSize) = 1 Then
            str_user = left$(Short_buff, lstrlenW(StrPtr(Short_buff)))
        End If
    End If
    Get_Logon_Username = str_user
End Function


' Initalize the Database connections
Public Sub Initialize_DB(Optional closeExisting As Boolean = True)

On Error GoTo ErrorHandler


str_db_connection_string = "Driver={Microsoft ODBC for Oracle};Server=" & str_DatabaseName & ";Uid=" & str_user & ";Pwd=" & str_UPass & ";"
    
    ' Close database connection is they are already open
    
    If closeExisting Then
        If ordb.State = adStateOpen Then ordb.Close
    Else
        Set ordb = New ADODB.Connection
    End If
        
    ordb.ConnectionString = str_db_connection_string
 
    ordb.Open
        
   ' If orDB.State = adStateOpen Then reportMessage ("Logon to " & str_DatabaseName & " DB Successful")
    
    Exit Sub
    
ErrorHandler:
    
    MsgBox Err.Description, vbCritical, "Failed DB init"
   ' End
    Exit Sub
End Sub

Public Sub Close_DB()
On Error Resume Next
    If ordb.State = adStateOpen Then ordb.Close
End Sub

Public Function MakeFieldsComboList() As String
Dim i As Integer
Dim vi As Integer
arr_combo_str = "#0; "
vi = 0
For i = 1 To arr_combo_count
    'If Not arr_combo(i).bln_In Then
        vi = vi + 1
        arr_colPos(vi) = i
        arr_combo_str = arr_combo_str & "|#" & arr_combo(i).int_id & ";" & arr_combo(i).str_Name & "|"
        '                            |#3;<" & vbTab & "LT"
    'End If
Next i
arr_combo_str = arr_combo_str & "#" & i & ";<<Expression>>"
'Debug.Print arr_combo_str
MakeFieldsComboList = arr_combo_str
End Function

Public Function FillCombo(cmb As ComboBox, str_query As String, Optional AddID As Boolean = True) As Boolean
On Error GoTo errHandler
FillCombo = True
    Dim rsPrint As New ADODB.Recordset
    Dim Raff    As Integer
    cmb.Clear
    
    Set rsPrint = ordb.Execute(str_query, Raff)
    If rsPrint.State = adStateOpen Then
        If Not rsPrint.EOF Then
            Do While Not rsPrint.EOF
                If IsNumeric(rsPrint.Fields(0)) Then
                    If AddID Then
                        cmb.AddItem left(Trim(rsPrint.Fields(0).Value) & "                          ", 12) & rsPrint.Fields(1).Value
                    Else
                        cmb.AddItem Trim(rsPrint.Fields(1).Value)
                    End If
                    cmb.ItemData(cmb.ListCount - 1) = CStr(rsPrint.Fields(0))
                End If
                rsPrint.MoveNext
            Loop
            If rsPrint.State = adStateOpen Then rsPrint.Close
        End If
    End If
    Exit Function
errHandler:
    MsgBox Err.Description, vbOKOnly, "Fill Combo"
    FillCombo = False
    If rsPrint.State = adStateOpen Then rsPrint.Close
End Function


Public Function FillGrid(fg As vsFlexGrid, str_query As String, bln_Show_Row) As Boolean
On Error GoTo errHandler
FillGrid = True
Dim str_Toadd As String
    Dim rsPrint As New ADODB.Recordset
    Dim Raff    As Integer
    fg.Rows = 1
    fg.Redraw = flexRDNone
    fg.FixedRows = 1
    fg.FixedCols = 0
    
    Set rsPrint = ordb.Execute(str_query, Raff)
    If rsPrint.State = adStateOpen Then
        
        If Not rsPrint.EOF Then
            Do While Not rsPrint.EOF
                str_Toadd = rsPrint.Fields(0).Value
                str_Toadd = Replace(str_Toadd, "_", " ")
                fg.AddItem CStr(fg.Rows) & vbTab & str_Toadd
                fg.RowData(fg.Rows - 1) = rsPrint.Fields(0).Value
                fg.RowHidden(fg.Rows - 1) = Not bln_Show_Row
                rsPrint.MoveNext
            Loop
            If rsPrint.State = adStateOpen Then rsPrint.Close
        End If
    End If
    fg.Redraw = flexRDBuffered
    
    Exit Function
errHandler:
    MsgBox Err.Description, vbOKOnly, "FillGrid"
    FillGrid = False
    If rsPrint.State = adStateOpen Then rsPrint.Close
End Function


Public Function FillGroup(fg As vsFlexGrid, ByRef str_query As String, int_FieldsCount) As Boolean
On Error GoTo errHandler
Dim int_col As Integer

FillGroup = True
Dim str_Toadd As String

If ordb.State <> adStateOpen Then Exit Function
    Dim rsPrint As New ADODB.Recordset
    Dim Raff    As Integer
    fg.Redraw = flexRDNone
    fg.FixedRows = 1
    fg.FixedCols = 0
    Set rsPrint = ordb.Execute(str_query, Raff)
    If rsPrint.State = adStateOpen Then
    
        For int_col = 0 To int_FieldsCount - 1
            fg.TextMatrix(0, int_col) = Replace(rsPrint.Fields(int_col).Name, "_", " ")
        Next int_col
        
        If Not rsPrint.EOF Then
            Do While Not rsPrint.EOF
                str_Toadd = ""
                For int_col = 0 To int_FieldsCount - 1
                    If int_col > 0 Then str_Toadd = str_Toadd & vbTab
                    str_Toadd = str_Toadd & rsPrint.Fields(int_col).Value
                Next int_col
                fg.AddItem str_Toadd
                rsPrint.MoveNext
            Loop
            If rsPrint.State = adStateOpen Then rsPrint.Close
        End If
    End If
    fg.AutoSize 0, fg.Cols - 1

    fg.Redraw = flexRDBuffered
    
    Exit Function
errHandler:
    MsgBox Err.Description, vbOKOnly, "Fill Group"
    FillGroup = False
    If rsPrint.State = adStateOpen Then rsPrint.Close
    'fg_Group.vsFlexGrid.Redraw = flexRDDirect
End Function

