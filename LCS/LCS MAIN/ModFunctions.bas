Attribute VB_Name = "Module2"
Option Explicit
Dim sqlStmt As String
Function fnCOMMODITY(id As Integer) As String
    Dim dsCOMMODITY As Object
    
    sqlStmt = "SELECT COMMODITY_NAME FROM COMMODITY WHERE COMMODITY_CODE='" & id & "'"
    Set dsCOMMODITY = OraDatabase.CreateDynaset(sqlStmt, 0&)
    If dsCOMMODITY.RecordCount > 0 Then
        fnCOMMODITY = dsCOMMODITY.Fields("COMMODITY_NAME").Value
    Else
        fnCOMMODITY = CStr(id)
    End If
    
End Function

Function fnVESSEL(id As Long) As String
    Dim dsVESSEL As Object
    
    sqlStmt = "SELECT VESSEL_NAME FROM VESSEL WHERE VESSEL_ID='" & id & "'"
    Set dsVESSEL = OraDatabase.CreateDynaset(sqlStmt, 0&)
    If dsVESSEL.RecordCount > 0 Then
        fnVESSEL = dsVESSEL.Fields("VESSEL_NAME").Value
    Else
        fnVESSEL = CStr(id)
    End If
    
End Function

Function fnEmp(id As String) As String
    Dim dsEMP As Object
    sqlStmt = " SELECT * FROM EMPLOYEE WHERE EMPLOYEE_ID = '" & id & "'"
    Set dsEMP = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If dsEMP.RecordCount > 0 Then
        fnEmp = dsEMP.Fields("EMPLOYEE_NAME").Value
    Else
        fnEmp = CStr(id)
    End If
    
End Function
Function fnCustomer(id As Integer) As String
    Dim dsCUSTOMER As Object
    
    sqlStmt = "SELECT CUSTOMER_NAME FROM CUSTOMER WHERE CUSTOMER_ID='" & id & "'"
    Set dsCUSTOMER = OraDatabase.CreateDynaset(sqlStmt, 0&)
    If dsCUSTOMER.RecordCount > 0 Then
        fnCustomer = dsCUSTOMER.Fields("CUSTOMER_NAME").Value
    Else
        fnCustomer = CStr(id)
    End If
    
End Function

Function fnServiceGroup(id As Integer) As String
    Dim dsService As Object
    
'    sqlStmt = "SELECT SUBSTR(SERVICE_NAME, 6, INSTR(SERVICE_NAME, '/')-6) SERVICE_GROUP FROM SERVICE " & _
'              " WHERE STATUS = 'N' AND SUBSTR(SERVICE_CODE, 1, 3) = " & id
    
    'Get service group name from SERVICE_GROUP table  -- LFW, 08/09/2005
    sqlStmt = "SELECT SERVICE_GROUP_NAME FROM SERVICE_GROUP WHERE SERVICE_GROUP_ID = " & id
    Set dsService = OraDatabase.CreateDynaset(sqlStmt, 0&)
    If dsService.RecordCount > 0 Then
        fnServiceGroup = dsService.Fields("SERVICE_GROUP_NAME").Value
    Else
        fnServiceGroup = CStr(id)
    End If
End Function


