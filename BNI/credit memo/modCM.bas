Attribute VB_Name = "modCM"
Option Explicit       '2258 4/2/2007 Rudy: note API not used.

Public Declare Function GetUserName Lib "advapi32.dll" Alias "GetUserNameA" (ByVal lpBuffer As String, nSize As Long) As Long
Global OraSession As Object
Global OraDatabase As Object
Global OraDatabaseProd As Object
Global OraSessionProd As Object

'Public Function VerifyUser()
'Dim sSql As String
'Dim dsUser As Object
'Dim sUname As String * 16
'
''if getusername(
'
'sSql = "SELECT * FROM xxRateEditUsers WHERE UserName = '" & txtUserName.Text & "' "
'Set OraDynaset = OraDb.CreateDynaset(sSql, 0&)
'If OraDynaset.eof And OraDynaset.BOF Then   'not found
'    VerifyUser = False
'Else
'    flUser = OraDynaset.fields("UserID").Value
'    VerifyUser = True
'End If
'
'End Function
