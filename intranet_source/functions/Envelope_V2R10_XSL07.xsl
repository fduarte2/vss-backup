<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template name="OpenEnvelope">
		<br/>
		<xsl:element name="table" use-attribute-sets="default-table-center-640">
			<tr>
				<td style="padding-left:15px">
					<span class="PartiesStyle4">&gt;&gt; Transmission Envelope - Technical Information  </span>
					<a href="javascript://" onclick="MM_showHideLayers('envelope','','show')">
						<span class="EnvelopeStyle9">open</span>
					</a>
					<span class="SummaryStyle1">
						<xsl:text>/</xsl:text>
					</span>
					<a href="javascript://" onclick="MM_showHideLayers('envelope','','hide')">
						<span class="EnvelopeStyle9">close</span>
					</a>
				</td>
			</tr>
		</xsl:element>
	</xsl:template>
	<xsl:template name="EnvelopeHeader">
		<br/>
		<xsl:element name="table" use-attribute-sets="default-table-center-640">
			<tr>
				<td bgcolor="#767676" class="LineItemHeaderStyle6">
					<xsl:value-of select="$Envelope"/>
				</td>
			</tr>
		</xsl:element>
	</xsl:template>
	<xsl:template name="EnvelopeContent">
		<xsl:call-template name="EnvelopeHeader"/>
		<xsl:element name="table" use-attribute-sets="default-table-center-640">
			<tr>
				<td valign="top">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#F5F5F5">
					<table border="0" cellpadding="0" cellspacing="0">
						<xsl:apply-templates select="//TransmissionInformation"/>
					</table>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#F5F5F5">
					<table border="0" cellpadding="0" cellspacing="0">
						<xsl:apply-templates select="//Payload/MessageMetaData/DocumentInfo"/>
					</table>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#F5F5F5">
					<table border="0" cellpadding="0" cellspacing="0">
						<xsl:apply-templates select="//Payload/AttachmentDescription"/>
					</table>
				</td>
			</tr>
		</xsl:element>
	</xsl:template>
	<xsl:template match="TransmissionCharacteristics">
		<xsl:apply-templates select="@TransmissionMode"/>
		<xsl:apply-templates select="@TransmissionProtocol"/>
		<xsl:apply-templates/>
	</xsl:template>
	<xsl:template match="TransmissionSecurityCharacteristics">
		<xsl:apply-templates select="@CryptoAlgorithm"/>
		<xsl:apply-templates select="@HashAlgorithm"/>
		<xsl:apply-templates select="@SignatureAlgorithm"/>
		<xsl:apply-templates/>
	</xsl:template>
	<xsl:template match="TransmissionInformation">
		<xsl:apply-templates/>
	</xsl:template>
	<xsl:template match="TransmissionOrganisationIdentifiers">
		<xsl:apply-templates/>
	</xsl:template>
	<xsl:template match="CommunicationSoftware">
		<xsl:apply-templates/>
	</xsl:template>
	<xsl:template match="TransferID | SenderURI | ReceiverURI | TransmissionTimeStamp | SenderOrganisation | SenderOrganisationUnit | ReceiverOrganisation | ReceiverOrganisationUnit | KeyLength | ConverterVendorName | ConverterProductName | ConverterVersion | MessengerVendorName | MessengerProductName | MessengerVersion | LogInfo | DTDVersionNumber | ERPVendorName | ERPProductName | ERPVersion | DTDSet | Compression | AttachmentDescription">
		<tr>
			<td class="EnvelopeStyle1" width="200" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="EnvelopeStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="DocumentInfo">
		<xsl:if test="@Type">
		<tr>
			<td class="EnvelopeStyle1" width="200" valign="top">
				<xsl:value-of select="$Type"/>
			</td>
			<td class="EnvelopeStyle2" valign="top">
				<xsl:value-of select="@Type"/>
			</td>
		</tr>
		</xsl:if>
		<xsl:apply-templates select="@MessageName"/>
		<xsl:apply-templates select="@TestFlag"/>
		<xsl:apply-templates/>
	</xsl:template>
	<xsl:template match="ApplicationSoftware">
		<xsl:apply-templates/>
	</xsl:template>
</xsl:stylesheet>
<!-- Stylus Studio meta-information - (c)1998-2002 eXcelon Corp.
<metaInformation>
<scenarios/><MapperInfo srcSchemaPath="" srcSchemaRoot="" srcSchemaPathIsRelative="yes" srcSchemaInterpretAsXML="no" destSchemaPath="" destSchemaRoot="" destSchemaPathIsRelative="yes" destSchemaInterpretAsXML="no"/>
</metaInformation>
-->