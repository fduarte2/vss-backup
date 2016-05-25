Attribute VB_Name = "modEncrypt"
Option Explicit       '2258 4/2/2007 Rudy: TWO funcs, both NOT used in project

'2258 4/2/2007 Rudy: Func not used in project:
Public Function ValidUser() As Boolean
ValidUser = False
End Function

'this function requires that the string argument be 6 or more characters long
                      
'2258 4/2/2007 Rudy: Func not used in project:
Public Function EncryptStr(asStr As String) As String
Dim iLen As Integer
Dim iPartALen As Integer
Dim iPartBLen As Integer
Dim sPartA As String
Dim sPartB As String
Dim sPartAEnc As String
Dim sPartBEnc As String
Dim sSwapAB As String
Dim sAlpha As String
Dim i As Integer

'check the length of string
iLen = Len(Trim(asStr))

'Divide string into 2 parts
iPartALen = (iLen \ 2) + (iLen Mod 2)
iPartBLen = iLen - iPartALen

sPartA = Left(Trim(asStr), iPartALen)
sPartB = Right(Trim(asStr), iPartBLen)

'MsgBox sPartA & " " & sPartB
'Add 2 to ascii of Part A
For i = 1 To iPartALen
    sAlpha = Mid(sPartA, i, 1)
    sAlpha = Chr(Asc(sAlpha) + 2)
    sPartAEnc = sPartAEnc & sAlpha
Next i
'Add 3 to ascii of Part B
For i = 1 To iPartBLen
    sAlpha = Mid(sPartB, i, 1)
    sAlpha = Chr(Asc(sAlpha) + 2)
    sPartBEnc = sPartBEnc & sAlpha
Next i
'swap part A and part B
sSwapAB = sPartBEnc & sPartAEnc
EncryptStr = sSwapAB
End Function

