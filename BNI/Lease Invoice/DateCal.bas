Attribute VB_Name = "DateCal"
Option Explicit

Function DateCalculation(iPeriod As Integer, dtDate As Date) As Date
    
    Dim iMonth As Integer
    Dim iTemp As Integer
    Dim iPos As Integer
    
    iPos = InStr(1, DateAdd("M", iPeriod, dtDate), "/")
    
    iMonth = Mid(DateAdd("M", iPeriod, dtDate), 1, iPos - 1)
    
    Select Case iMonth
    
        Case 1, 3, 5, 7, 8, 10, 12
        
            DateCalculation = DateAdd("d", 1, (DateAdd("M", iPeriod, dtDate))) 'end date
        
        
        
        Case 2, 4, 6, 9, 11
        
            DateCalculation = DateAdd("M", iPeriod, dtDate) 'end date
    
    End Select

End Function
