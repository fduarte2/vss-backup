Attribute VB_Name = "modWindowsRoutines"
Option Explicit       '2258 4/2/2007 Rudy:
'
'   centers form on the physical screen
'
Sub CenterForm(f As Form)
  f.Left = (Screen.Width - f.Width) \ 2
  f.Top = (Screen.Height - f.Height) \ 2
End Sub

Sub MB()
  Screen.MousePointer = vbHourglass
End Sub

Sub MN()
  Screen.MousePointer = vbDefault
End Sub
