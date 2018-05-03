<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:fn="http://www.w3.org/2005/xpath-functions" xmlns:cer="http://forapi.dyndns-work.com:82/csjl_nvo20130705/wsesiscor" >
	<xsl:output method="text" version="1.0" encoding="UTF-8" indent="no"/>
	<xsl:include href="http://forapi.dyndns-work.com:82/csjl_nvo20130705/wsesiscor/utilerias.xslt"/>
	<xsl:template match="/">|<xsl:apply-templates select="/cer:Certificacion"/>||</xsl:template>
	<xsl:template match="cer:Certificacion">
		<xsl:call-template name="Requerido">
			<xsl:with-param name="valor" select="./@Concepto"/>
		</xsl:call-template>
		<xsl:call-template name="Requerido">
			<xsl:with-param name="valor" select="./@NombreDelContribuyente"/>
		</xsl:call-template>
		<xsl:call-template name="Requerido">
			<xsl:with-param name="valor" select="./@Cuenta"/>
		</xsl:call-template>
		<xsl:call-template name="Requerido">
			<xsl:with-param name="valor" select="./@FechaEmision"/>
		</xsl:call-template>
		<xsl:call-template name="Requerido">
			<xsl:with-param name="valor" select="./@HoraEmision"/>
		</xsl:call-template>
		<xsl:call-template name="Requerido">
			<xsl:with-param name="valor" select="./@HoraEmision"/>
		</xsl:call-template>
		<xsl:apply-templates select="./cer:Cobros"/>
	</xsl:template>
	<xsl:template match="cer:Cobros">
		<xsl:for-each select="./cer:Cobro">
			<xsl:apply-templates select="."/>
		</xsl:for-each>
	</xsl:template>
        <xsl:template match="cer:Cobro">
                <xsl:call-template name="Requerido">
                        <xsl:with-param name="valor" select="./@PuntoDeRecaudacion"/>
                </xsl:call-template>
                <xsl:call-template name="Requerido">
                        <xsl:with-param name="valor" select="./@FechaDeCobro"/>
                </xsl:call-template>
                <xsl:call-template name="Opcional">
                        <xsl:with-param name="valor" select="./@Caja"/>
                </xsl:call-template>
                <xsl:call-template name="Opcional">
                        <xsl:with-param name="valor" select="./@Partida"/>
                </xsl:call-template>
                <xsl:call-template name="Opcional">
                        <xsl:with-param name="valor" select="./@Periodos"/>
                </xsl:call-template>
                <xsl:call-template name="Requerido">
                        <xsl:with-param name="valor" select="./@LineaDeCaptura"/>
                </xsl:call-template>
                <xsl:call-template name="Requerido">
                        <xsl:with-param name="valor" select="./@TotalDelCobro"/>
                </xsl:call-template>
        </xsl:template>
</xsl:stylesheet>

