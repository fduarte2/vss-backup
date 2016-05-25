<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template name="TextStyleCSS">
		<style type="text/css"><![CDATA[
			<!--
			.SenderAddressStyle12 			{font-family:arial;font-weight:bold;font-size:15px;color:#5C7094;}
			.SenderAddressStyle13 			{font-family:arial;font-size:11px;color:#5C7094;}
	
			.ReceiverAddressStyle1			{font-family:arial;font-weight:bold;font-size:9px;color:#000000;padding-bottom:3px;}
			.ReceiverAddressStyle10			{font-family:arial;font-weight:bold;font-size:12px;color:#000000;padding-left:15px;padding-bottom:5px;padding-top:5px}
			.ReceiverAddressStyle11			{font-family:arial;font-size:12px;color:#000000;padding-left:15px;padding-bottom:5px;padding-top:5px}
	
			.MessageInformationStyle1		{font-family:arial;font-size:10px;font-weight:bold;color:#000000;padding-left:15px}
			.MessageInformationStyle2		{font-family:courier;font-size:12px;color:#000000;border-bottom:1px inset #FFFFFF;text-align:right;padding-right:15px;padding-bottom:2px;padding-top:5px}
			.MessageInformationStyle3		{font-family:courier;font-size:12px;color:#000000;text-align:top}
			.MessageInformationStyle4		{font-family:arial;font-size:10px;color:#000000;font-weight:bold;text-align:left;padding-left:5px}
			.MessageInformationStyle5		{font-family:arial;font-size:10px;color:#666666;padding-left:0px;padding-bottom:5px;padding-top:0px}

			.MessageInformationStyle6		{font-family:arial;font-weight:bold;font-size:12px;color:#FFFFFF;padding-left:15px;letter-spacing:1px;}
			.MessageInformationStyle7		{font-family:arial;font-weight:bold;font-size:12px;color:#FFFFFF;font-style:italic;letter-spacing:1px;}
	
			.PartiesStyle1					{font-family:arial;font-weight:bold;font-size:9px;color:#000000;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.PartiesStyle1a					{font-family:arial;font-weight:bold;font-size:9px;color:#000000;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.PartiesStyle2					{font-family:courier;font-size:12px;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.PartiesStyle3					{font-family:arial;font-size:10px;color:#666666;padding-left:15px;padding-bottom:5px;padding-top:0px}
			.PartiesStyle4					{font-family:arial;font-weight:bold;font-size:10px;color:#000000;padding-left:15px;padding-bottom:0px;padding-top:5px}
			.PartiesStyle5					{font-family:arial;font-weight:bold;font-size:12px;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.PartiesStyle6					{font-family:arial;font-weight:bold;font-size:8px;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.PartiesStyle7					{font-family:arial;font-weight:bold;font-size:12px;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}

			.LineItemHeaderStyle6			{font-family:arial;font-weight:bold;font-size:12px;color:#FFFFFF;padding-left:15px;padding-bottom:0px;padding-top:0px}

			.LineItemStyle1					{font-family:arial;font-weight:bold;font-size:9px;color:#000000;padding-left:15px;padding-bottom:2px;padding-top:2px}
			.LineItemStyle2					{font-family:courier;font-size:12px;color:#666666;padding-left:15px;padding-bottom:2px;padding-top:2px}
			.LineItemStyle3					{font-family:arial;font-size:10px;color:#000000;padding-left:15px;padding-bottom:2px;padding-top:2px}
			.LineItemStyle4					{font-family:arial;font-weight:bold;font-size:9px;color:#000000;padding-left:15px;padding-bottom:2px;padding-top:2px;border-top:1px inset #000000}
			.LineItemStyle5					{font-family:arial;font-weight:bold;font-size:9px;color:#000000;padding-left:5px;padding-bottom:5px;padding-top:5px;}
			.LineItemStyle6					{font-family:arial;font-size:10px;color:#666666;padding-left:15px;padding-bottom:2px;padding-top:2px}
			.LineItemStyle6a				{font-family:arial;font-size:10px;color:#666666;padding-left:5px;padding-bottom:2px;padding-top:2px}
			.LineItemStyle6b				{font-family:arial;font-size:10px;color:#666666;padding-left:0px;padding-bottom:2px;padding-top:2px}
			.LineItemStyle7					{font-family:courier;font-size:12px;color:#666666;padding-left:0px;padding-bottom:2px;padding-top:2px}
			.LineItemStyle8					{font-family:arial;font-weight:bold;font-size:9px;color:#000000;padding-left:10px;padding-bottom:2px;padding-top:2px;border-top:1px inset #000000}
	
			.LineItemShipToStyle1			{font-family:arial;font-weight:bold;font-size:9px;color:#000000;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.LineItemShipToStyle2			{font-family:courier;font-size:12px;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.LineItemShipToStyle3			{font-family:arial;font-size:10px;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.LineItemShipToStyle5			{font-family:arial;font-weight:bold;font-size:12px;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.LineItemShipToStyle8			{font-family:arial;font-weight:bold;font-style:italic;font-size:12px;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}
	
			.DeliveryLineItemStyle1			{font-family:arial;font-weight:bold;font-size:9px;color:#000000;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.DeliveryLineItemStyle2			{font-family:courier;font-size:12px;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.DeliveryLineItemStyle3			{font-family:arial;font-size:10px;color:#000000;padding-left:15px;padding-bottom:2px;padding-top:2px}	
	
			.DeliveryInstructionsStyle0		{font-family:arial;font-size:9px}
			.DeliveryInstructionsStyle1		{font-family:arial;font-size:9px;font-weight:bold;color:#000000;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.DeliveryInstructionsStyle2		{font-family:courier;font-size:12px;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.DeliveryInstructionsStyle3		{font-family:courier;font-size:12px;color:#666666;padding-right:15px;padding-bottom:0px;padding-top:0px}
			.DeliveryInstructionsStyle5		{font-family:arial;font-size:12px;font-weight:bold;color:#666666;padding-left:15px;padding-bottom:0px;padding-top:0px}
	
			.SummaryStyle1					{font-family:arial;font-size:9px;font-weight:bold;color:#000000;padding-left:5px;padding-bottom:0px;padding-top:0px}
			.SummaryStyle2					{font-family:courier;font-size:12px;color:#666666;padding-left:5px;padding-bottom:0px;padding-top:0px}
			.SummaryStyle4					{font-family:arial;font-size:9px;font-weight:bold;color:#FFFFFF;padding-left:5px;padding-bottom:0px;padding-top:0px}
			.SummaryStyle6					{font-family:arial;font-size:12px;font-weight:bold;color:#FFFFFF;padding-left:5px;padding-bottom:0px;padding-top:0px}
	
			.EnvelopeStyle1					{font-family:courier;font-size:12px;color:#CC0000;padding-left:15px;padding-bottom:0px;padding-top:0px}
		
			.EnvelopeStyle1					{font-family:arial;font-weight:bold;font-size:10px;color:#000000;padding-left:15px;padding-bottom:0px;padding-top:0px}
			.EnvelopeStyle2					{font-family:courier;font-size:12px;color:#666666;padding-left:0px;padding-bottom:0px;padding-top:0px}
			.EnvelopeStyle3					{font-family:arial;font-weight:bold;font-size:12px;color:#000000;padding-left:0px;padding-bottom:0px;padding-top:0px}
			.EnvelopeStyle9					{font-family:arial;font-weight:bold;font-size:10px}

			.red							{font-family:arial;font-size:10px;background:#CC0000;padding-left:8px;color:#CC0000}
			.yellow							{font-family:arial;font-size:10px;background:#FFFF00;padding-left:8px;color:#FFFF00}
			.green							{font-family:arial;font-size:10px;background:#99CC00;padding-left:8px;color:#99CC00}
			.orange							{font-family:arial;font-size:10px;background:#FF9900;padding-left:8px;color:#FF9900}
			.black							{font-family:arial;font-size:10px;background:#000000;padding-left:8px;color:#000000}
			// -->
			]]>
		</style>
	</xsl:template>
	<xsl:template name="EnvelopeJS">
		<script language="JavaScript" type="text/javascript"><![CDATA[
<!--
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
// -->

function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function tmt_findObj(n){
	var x,t; if((n.indexOf("?"))>0&&parent.frames.length){t=n.split("?");
	x=eval("parent.frames['"+t[1]+"'].document.getElementById('"+t[0]+"')");
	}else{x=document.getElementById(n)}return x;
}

function MM_showHideLayers() { //v3.0A Modified by Al Sparber and Massimo Foti for NN6 Compatibility
  var i,p,v,obj,args=MM_showHideLayers.arguments;if(document.getElementById){
   for (i=0; i<(args.length-2); i+=3){ obj=tmt_findObj(args[i]);v=args[i+2];
   v=(v=='show')?'visible':(v='hide')?'hidden':v;
   if(obj)obj.style.visibility=v;}} else{
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }}
}
//-->
			]]>
		</script>
	</xsl:template>
</xsl:stylesheet>
