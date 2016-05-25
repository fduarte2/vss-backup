<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="DateSelector.aspx.cs" Inherits="ePortDC.UI.DateSelector" %>
<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="ThemeButton.ascx" %>

<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head id="Head1" runat="server">
    <title></title>
    <link rel="stylesheet" rev="stylesheet" type="text/css" href="../Styles/Style.css" />
  </head>
  <body id="Body1" runat="server">
	<form id="Form1" method="post" runat="server">
          <BaseClasses:BasePageSettings id="PageSettings" runat="server"  LoginRequired="NOT_ANONYMOUS" />
<script type="text/javascript" language="javascript">
<!--
	function updateTarget(selectedValue)
	{
		if (window.opener && !window.opener.closed) {
			var target = window.opener.document.getElementById('<%# this.TargetName %>');
			var bSuccess = Fev_SetFormControlValue(target, selectedValue);
			if (!bSuccess)
			{
				//attempt to insert a new list item
				if (insertListControlValue(window.opener.document, target, selectedValue, selectedValue) ||
					Fev_ReplaceLastListControlOption(target, selectedValue, selectedValue) )
				{
					//try setting the selection again
					bSuccess = Fev_SetFormControlValue(target, selectedValue);
					target.fireEvent('onchange');
				}
			}
			
			if (bSuccess)
			{
				if(target != null) {
					if (navigator.appName == "Netscape") {
						var myevent = document.createEvent("HTMLEvents")
						myevent.initEvent("change", true, true)
						target.dispatchEvent(myevent);
					}
					else { // IE
						target.fireEvent('onchange');
					}
				}
			}			
			
			window.close();
		}
	}

	//Inserts the value into a list element, independent of the element's type.
	function insertListControlValue(objDocument, objListElement, strValue, strText)
	{
		var strTagName = Fev_GetElementTagName(objListElement);
		switch (strTagName.toLowerCase())
		{
			case "select":
				var objOption = objDocument.createElement("OPTION");
				objOption.value = strValue;
				objOption.text = strText;
				objListElement.options.add(objOption);
				return true;
				break;
			default:
				break;
		}
		return false;
	}
//-->
</script>

<center>
<br />
<BaseClasses:Calendar id=Calendar1 PrevYearText='<img src="../images/arrow_beg.gif" border="0">' PrevMonthText='<img src="../images/arrow_left.gif" border="0">' NextMonthText='<img src="../images/arrow_right.gif" border="0">' NextYearText='<img src="../images/arrow_end.gif" border="0">' runat="server" VisibleDate="<%# GetClosestValidVisibleDate(DefaultDate) %>" SelectedDate="<%# DefaultDate %>" ForeColor="#333333" Width="250px" DayNameFormat="FirstLetter">
	<DayStyle CssClass="dsDay"></DayStyle>
	<TodayDayStyle CssClass="dsTodayDay"></TodayDayStyle>
	<SelectorStyle></SelectorStyle>
	<NextPrevStyle></NextPrevStyle>
	<DayHeaderStyle CssClass="dsDayHeader"></DayHeaderStyle>
	<SelectedDayStyle CssClass="dsSelectedDay"></SelectedDayStyle>
	<TitleStyle CssClass="dsTitle"></TitleStyle>
	<WeekendDayStyle CssClass="dsWeekendDay"></WeekendDayStyle>
	<OtherMonthDayStyle CssClass="dsOtherMonthDay"></OtherMonthDayStyle>
</BaseClasses:Calendar>
<br />
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
	<td><script language="JavaScript" type="text/javascript">clearRTL()</script>
		<asp:ScriptManager ID="scriptManager1" runat="server" EnablePartialRendering="True" EnablePageMethods="True" />
		<ePortDC:ThemeButton runat="server" id="OkButton" Button-CausesValidation="False" Button-CommandName="Redirect" Button-Text="&lt;%# GetResourceValue(&quot;Btn:OK&quot;, &quot;ePortDC&quot;) %>">
		</ePortDC:ThemeButton></td>
	<td width="4"></td>
	<td><ePortDC:ThemeButton runat="server" id="CancelButton" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="Close" Button-Text="&lt;%# GetResourceValue(&quot;Btn:Cancel&quot;, &quot;ePortDC&quot;) %>">
		</ePortDC:ThemeButton></td>
    </tr>
</table>
</center>
<asp:ValidationSummary id="ValidationSummary1" ShowMessageBox="true" ShowSummary="false" runat="server"></asp:ValidationSummary>
 </form>
  </body>
</html>

