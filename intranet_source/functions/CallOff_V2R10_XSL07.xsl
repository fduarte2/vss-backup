<?xml version='1.0'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:data="http://privater.ns">
	<xsl:import href="LanguageVariables_EN_V2R10_XSL07.xsl"/>
	<xsl:import href="AttributeSelector_V2R10_XSL07.xsl"/>
	<xsl:import href="Envelope_V2R10_XSL07.xsl"/>
	<xsl:import href="CommonStyles_V2R10_XSL07.xsl"/>
	<xsl:import href="CommonTemplates_V2R10_XSL07.xsl"/>
	<xsl:variable name="map" select="document('LanguageVariables_EN_V2R10_XSL07.xsl')/*/data:section/map"/>
	<xsl:template match="Envelope">
		<xsl:apply-templates select="Payload/Message/CallOff"/>
	</xsl:template>
	<xsl:template match="CallOff">
		<html>
			<title>
				<xsl:value-of select="$TitleCallOff"/>
				<xsl:value-of select="CallOffHeader/CallOffInformation/CallOffNumber"/>
				<xsl:value-of select="$TitleIssued"/>
				<xsl:apply-templates select="CallOffHeader/CallOffInformation/CallOffIssuedDate"/>
			</title>
			<head>
				<xsl:call-template name="TextStyleCSS"/>
				<xsl:call-template name="EnvelopeJS"/>
			</head>
			<body bgcolor="#FFFFFF">
				<xsl:apply-templates select="CallOffHeader"/>
				<xsl:call-template name="CallOffLineItemsHeader"/>
				<xsl:apply-templates select="CallOffLineItem"/>
				<xsl:call-template name="CallOffSummaryHeader"/>
				<xsl:apply-templates select="CallOffSummary"/>
				<xsl:call-template name="OpenEnvelope"/>
				<div id="envelope" style="visibility: hidden">
					<xsl:call-template name="EnvelopeContent"/>
					<xsl:element name="table" use-attribute-sets="default-table-center-640">
						<tr>
							<td valign="top" colspan="4">
								<hr style="height:1px;color:#000000;noshade"/>
							</td>
						</tr>
						<tr>
							<td class="EnvelopeStyle1" valign="top" width="200" bgcolor="#F5F5F5">Style Sheet Applied:</td>
							<td class="EnvelopeStyle2" bgcolor="#F5F5F5">CallOff_V2R10_XSL07</td>
							<td class="EnvelopeStyle1" valign="top" bgcolor="#F5F5F5">Style Sheet Version:</td>
							<td class="EnvelopeStyle2" bgcolor="#F5F5F5">XSL07</td>
						</tr>
						<tr>
							<td valign="top" colspan="4">
								<hr style="height:1px;color:#000000;noshade"/>
							</td>
						</tr>
					</xsl:element>
				</div>
			</body>
		</html>
	</xsl:template>
<xsl:template name="CallOffLineItemsHeader">
		<br/>
		<xsl:element name="table" use-attribute-sets="default-table-center-640">
			<tr>
				<td colspan="5" bgcolor="#767676" class="LineItemHeaderStyle6">
					<xsl:value-of select="$CallOffLineItems"/>
				</td>
			</tr>
		</xsl:element>
	</xsl:template>
<xsl:template name="CallOffSummaryHeader">
		<br/>
		<xsl:element name="table" use-attribute-sets="default-table-center-640">
			<tr>
				<td bgcolor="#767676" class="LineItemHeaderStyle6">
					<xsl:value-of select="$CallOffSummary"/>
				</td>
			</tr>
		</xsl:element>
	</xsl:template>
<xsl:template match="CallOffSummary">
	<br/>
	<xsl:element name="table" use-attribute-sets="default-table-center-640">
		<tr>
			<td valign="top" width="290" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Description"/>
			</td>
			<td valign="top" width="130" bgcolor="#E6E6E6" class="LineItemStyle8">
				&#160;</td>
			<td valign="top" width="210" bgcolor="#E6E6E6" class="LineItemStyle8">
				<xsl:value-of select="$Quantity"/>
			</td>
			<td valign="top" width="10" bgcolor="#E6E6E6" class="LineItemStyle8">&#160;</td>
		</tr>
		<xsl:apply-templates mode="CallOff"/>
	</xsl:element>
</xsl:template>
<xsl:template match="CallOffHeader">
	<xsl:call-template name="BuyerPartyLetterhead"/>
	<xsl:element name="table" use-attribute-sets="default-table-center-640">
		<tr>
			<td valign="top" width="70%">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td bgcolor="#767676" class="MessageInformationStyle6">
							<xsl:value-of select="concat($CallOff,'  - ')"/>
							<span class="MessageInformationStyle7">
								<xsl:apply-templates select="../@CallOffType"/>
							</span>
						</td>
					</tr>
				</table>
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td>
										<xsl:call-template name="CallOffHeaderStatusTypeColour"/>
									</td>
									<td valign="top">
										<span class="MessageInformationStyle4">&#160;<xsl:value-of select="$PurchaseOrderHeaderStatusType"/></span>
										<span class="MessageInformationStyle3">&#160;
											<xsl:apply-templates select="@CallOffHeaderStatusType"/>
										</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table cellpadding="0" cellspacing="0" border="0" align="center" valign="top" width="300">
					<tr>
						<td valign="top" class="ReceiverAddressStyle1">
							<br/>
							<br/>
							<br/>
							<xsl:value-of select="$MailingAddressOfSeller"/>
						</td>
					</tr>
					<tr>
						<td valign="top">
							<table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-color:#000000;height:1px">
								<tr>
									<td valign="top">
										<xsl:call-template name="SupplierPartyAddressfield"/>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top" bgcolor="#E6E6E6" width="30%">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td valign="top">
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td>
										<xsl:call-template name="CallOffStatusTypeColour"/>
									</td>
									<td class="MessageInformationStyle4">
										<xsl:value-of select="$PurchaseOrderStatusType"/>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="MessageInformationStyle2">
							<xsl:value-of select="../@CallOffStatusType"/>
							<xsl:if test="@Reissued='Yes'">
								<br/>
								<xsl:value-of select="$Reissued"/>
							</xsl:if>
						</td>
					</tr>
					<tr>
						<td class="MessageInformationStyle1">
							<xsl:value-of select="$CallOffType"/>
						</td>
					</tr>
					<tr>
						<td class="MessageInformationStyle2">
							<xsl:apply-templates select="../@CallOffType"/>
						</td>
					</tr>
					<tr>
						<td class="MessageInformationStyle1">
							<xsl:value-of select="$CallOffNumber2"/>
						</td>
					</tr>
					<tr>
						<td class="MessageInformationStyle2">
							<xsl:apply-templates select="CallOffInformation/CallOffNumber"/>
						</td>
					</tr>
					<tr>
						<td class="MessageInformationStyle1">
							<xsl:value-of select="$InvoiceDate"/>
							<span class="MessageInformationStyle5">&#160;[YYYY-MM-DD]</span>
						</td>
					</tr>
					<tr>
						<td class="MessageInformationStyle2">
							<xsl:apply-templates select="CallOffInformation/CallOffIssuedDate"/>
						</td>
					</tr>
					<xsl:if test="CallOffInformation/TransactionHistoryNumber">
					<tr>
						<td class="MessageInformationStyle1">
							<xsl:value-of select="$TransactionHistoryNumber"/>
						</td>
					</tr>
					<tr>
						<td class="MessageInformationStyle2">
							<xsl:value-of select="CallOffInformation/TransactionHistoryNumber"/>
						</td>
					</tr>
					</xsl:if>
					<xsl:if test="CallOffInformation/TransactionHistoryConfirmationNumber">
						<tr>
							<td class="MessageInformationStyle1">
								<xsl:value-of select="$TransactionHistoryConfirmationNumber"/>
							</td>
						</tr>
						<tr>
							<td class="MessageInformationStyle2">
								<xsl:value-of select="CallOffInformation/TransactionHistoryConfirmationNumber"/>
							</td>
						</tr>
					</xsl:if>
					<xsl:if test="CallOffInformation/CallOffReference">
						<tr>
							<td class="MessageInformationStyle1">
								<xsl:value-of select="$PurchaseOrderReference"/>
							</td>
						</tr>
						<xsl:for-each select="CallOffInformation/CallOffReference">
							<tr>
								<td class="MessageInformationStyle2">
									<xsl:value-of select="."/>
									<br/>
									<xsl:text>[</xsl:text>
									<xsl:value-of select="@CallOffReferenceType"/>
									<xsl:text>]</xsl:text>
								</td>
							</tr>
						</xsl:for-each>
					</xsl:if>
				</table>
			</td>
		</tr>
	</xsl:element>
	<br/>
	<xsl:element name="table" use-attribute-sets="default-table-center-640">
		<xsl:call-template name="BuyerSupplierShipToParty"/>
		<xsl:if test="string-length(SenderParty) !='0'">
			<xsl:if test="string-length(ReceiverParty) !='0'">
				<xsl:if test="string-length(BillToParty) !='0'">
					<xsl:if test="string-length(CarrierParty) !='0'">
						<xsl:call-template name="SenderReceiverBillToParty"/>
						<xsl:call-template name="CarrierParty"/>
					</xsl:if>
					<xsl:if test="string-length(CarrierParty) ='0'">
						<xsl:call-template name="SenderReceiverBillToParty"/>
						<xsl:call-template name="OtherParty"/>
					</xsl:if>
				</xsl:if>
				<xsl:if test="string-length(BillToParty) ='0'">
					<xsl:if test="string-length(CarrierParty) !='0'">
						<xsl:call-template name="SenderReceiverCarrierParty"/>
					</xsl:if>
					<xsl:if test="string-length(CarrierParty) ='0'">
						<xsl:call-template name="SenderReceiverParty"/>
					</xsl:if>
				</xsl:if>
			</xsl:if>
		</xsl:if>
		<xsl:if test="string-length(SenderParty) ='0'">
			<xsl:if test="string-length(ReceiverParty) ='0'">
				<xsl:if test="string-length(BillToParty) !='0'">
					<xsl:if test="string-length(CarrierParty) !='0'">
						<xsl:call-template name="BillToCarrierParty"/>
					</xsl:if>
					<xsl:if test="string-length(CarrierParty) ='0'">
						<xsl:call-template name="BillToParty"/>
					</xsl:if>
				</xsl:if>
				<xsl:if test="string-length(BillToParty) ='0'">
					<xsl:if test="string-length(CarrierParty) !='0'">
						<xsl:call-template name="CarrierParty"/>
					</xsl:if>
					<xsl:if test="string-length(CarrierParty) ='0'">
						<xsl:call-template name="OtherParty"/>
					</xsl:if>
				</xsl:if>
			</xsl:if>
		</xsl:if>
		<xsl:apply-templates select="ShipToCharacteristics/TermsOfDelivery" mode="Header"/>
		<xsl:apply-templates select="ShipToCharacteristics/DeliveryRouteCode" mode="Header"/>	
		<xsl:if test="TransportModeCharacteristics | TransportVehicleCharacteristics | TransportUnitCharacteristics | TransportLoadingCharacteristics | TransportOtherCharacteristics">
			<tr>
				<td valign="top" colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" width="70">&#160;</td>
				<td valign="top" colspan="3" class="DeliveryInstructionsStyle5">
					<xsl:value-of select="$TransportInstructions"/>
				</td>
				<td valign="top" width="30">&#160;</td>
			</tr>
			<tr>
				<td valign="top" width="70">&#160;</td>
				<td valign="top" colspan="3">
					<xsl:apply-templates select="TransportModeCharacteristics"/>
					<xsl:apply-templates select="TransportVehicleCharacteristics"/>
					<xsl:apply-templates select="TransportUnitCharacteristics"/>
					<xsl:apply-templates select="TransportLoadingCharacteristics"/>
					<xsl:apply-templates select="TransportOtherInstructions"/>
				</td>
				<td valign="top" width="30">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="AdditionalText">
			<tr>
				<td valign="top" colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" width="70">&#160;</td>
				<td valign="top" colspan="3">
					<xsl:apply-templates select="AdditionalText"/>
				</td>
				<td valign="top" width="30">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="../@Language">
			<tr>
				<td valign="top" colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" width="70">&#160;</td>
				<td valign="top" colspan="3">
					<xsl:apply-templates select="../@Language"/>
				</td>
				<td valign="top" width="30">&#160;</td>
			</tr>
		</xsl:if>
	</xsl:element>
</xsl:template>
<xsl:template name="CallOffHeaderStatusTypeColour">
		<xsl:if test="@CallOffHeaderStatusType='Rejected'">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="red">&#160;</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="@CallOffHeaderStatusType='Amended'">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="yellow">&#160;</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="@CallOffHeaderStatusType='Accepted'">
			<table cellpaing="0" cellspacing="0" border="0">
				<tr>
					<td class="green">&#160;</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="@CallOffHeaderStatusType='Original'">
			<table cellpaing="0" cellspacing="0" border="0">
				<tr>
					<td class="green">&#160;</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="@CallOffHeaderStatusType='NoAction'">
			<table cellpaing="0" cellspacing="0" border="0">
				<tr>
					<td class="black">&#160;</td>
				</tr>
			</table>
		</xsl:if>
</xsl:template>
<xsl:template name="CallOffStatusTypeColour">
	<xsl:if test="../@CallOffStatusType='Cancelled'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@CallOffStatusType='Rejected'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@CallOffStatusType='Amended'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="yellow">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@CallOffStatusType='Accepted'">
		<table cellpaing="0" cellspacing="0" border="0">
			<tr>
				<td class="green">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@CallOffStatusType='Original'">
		<table cellpaing="0" cellspacing="0" border="0">
			<tr>
				<td class="green">&#160;</td>
			</tr>
		</table>
	</xsl:if>
</xsl:template>
<xsl:template match="CallOffLineItem">
	<xsl:element name="table" use-attribute-sets="default-table-center-640">
		<tr>
			<td>
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<xsl:call-template name="CallOffLineItemStatusTypeColour"/>
						</td>
						<td>
							<span class="LineItemStyle1">&#160;&#160;<xsl:value-of select="$CallOffLineItemNumber2"/>&#160;<xsl:value-of select="CallOffLineItemNumber"/>&#160;<xsl:value-of select="$PurchaseOrderLineItemStatusType"/>&#160;</span>
							<span class="LineItemStyle2">
								<xsl:apply-templates select="@CallOffLineItemStatusType"/>
							</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</xsl:element>
	<xsl:element name="table" use-attribute-sets="default-table-center-640">
		<tr>
			<td valign="top" bgcolor="#F5F5F5" width="35">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" width="35" bgcolor="#E6E6E6" class="LineItemStyle4">
							<xsl:value-of select="$PurchaseOrderLineItemNumber2"/>
						</td>
					</tr>
					<tr>
						<td valign="top" bgcolor="#F5F5F5" class="LineItemStyle2">
							<xsl:apply-templates select="CallOffLineItemNumber"/>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td valign="top" width="21%" bgcolor="#E6E6E6" class="LineItemStyle4">
							<xsl:value-of select="$ProductIdentifier"/>
						</td>
						<td valign="top" width="45%" bgcolor="#E6E6E6" class="LineItemStyle4">
							<xsl:value-of select="$ProductDescription2"/>
						</td>
						<td valign="top" width="15%" bgcolor="#E6E6E6" class="LineItemStyle4">&#160;</td>
						<td valign="top" width="15%" bgcolor="#E6E6E6" class="LineItemStyle4">&#160;</td>
					</tr>
					<tr>
						<td valign="top" bgcolor="#FEFEFE">
							<xsl:apply-templates select="CallOffProduct/ProductIdentifier"/>
						</td>
						<td valign="top" bgcolor="#F5F5F5">
							<xsl:apply-templates select="CallOffProduct"/>
							<xsl:apply-templates select="CallOffPurchaseOrderLineItem"/>
							<xsl:apply-templates select="CallOffReference"/>
							<xsl:apply-templates select="CallOffLineItemText"/>
							<br/>
						</td>
						<td valign="top" bgcolor="#FEFEFE">&#160;</td>
						<td valign="top" bgcolor="#F5F5F5">&#160;</td>
					</tr>
					<xsl:if test="TransportModeCharacteristics | TransportVehicleCharacteristics | TransportUnitCharacteristics  | TransportLoadingCharacteristics | TransportOtherInstructions">
						<tr>
							<td valign="top" bgcolor="#FEFEFE">&#160;</td>
							<td valign="top" bgcolor="#F5F5F5">
								<xsl:call-template name="TransportinstructionsHeader2"/>
								<xsl:apply-templates select="TransportModeCharacteristics | TransportVehicleCharacteristics | TransportUnitCharacteristics  | TransportLoadingCharacteristics | TransportOtherInstructions"/>
								<br/>
							</td>
							<td valign="top" bgcolor="#FEFEFE">&#160;</td>
							<td valign="top" bgcolor="#F5F5F5">&#160;</td>
						</tr>
					</xsl:if>
					<xsl:apply-templates select="DeliverySchedule"/>
				</table>
			</td>
		</tr>
	</xsl:element>
	<xsl:apply-templates select="DeliverySchedule/DeliveryLeg"/>
</xsl:template>
<xsl:template name="CallOffLineItemStatusTypeColour">
		<xsl:if test="@CallOffLineItemStatusType='Accepted'">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="green">&#160;</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="@CallOffLineItemStatusType='Original'">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="green">&#160;</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="@CallOffLineItemStatusType='Amended'">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="yellow">&#160;</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="@CallOffLineItemStatusType='Cancelled'">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="red">&#160;</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="@CallOffLineItemStatusType='Rejected'">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="red">&#160;</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="@CallOffLineItemStatusType='NoAction'">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="black">&#160;</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="@CallOffLineItemStatusType='Pending'">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="orange">&#160;</td>
				</tr>
			</table>
		</xsl:if>
</xsl:template>
</xsl:stylesheet>






<!-- Stylus Studio meta-information - (c)1998-2002 eXcelon Corp.
<metaInformation>
<scenarios ><scenario default="no" name="full" userelativepaths="yes" externalpreview="no" url="CallOff_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="yes" name="min" userelativepaths="yes" externalpreview="no" url="CallOff_min_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/></scenarios><MapperInfo srcSchemaPath="" srcSchemaRoot="" srcSchemaPathIsRelative="yes" srcSchemaInterpretAsXML="no" destSchemaPath="" destSchemaRoot="" destSchemaPathIsRelative="yes" destSchemaInterpretAsXML="no"/>
</metaInformation>
-->