<xsl:stylesheet 
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:fn="http://www.w3.org/2005/xpath-functions"
                xmlns:vuat="http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado"
                     version="1.0" >
    <xsl:output encoding="UTF-8"
                indent="no"
                method="text"
                version="1.0" />
    <xsl:template match="/">|<xsl:apply-templates select="AvisoDeTestamento" />||
    </xsl:template>
    <xsl:template match="AvisoDeTestamento">
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@folioAviso" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@lineaDeCaptura" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@tramite" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@numeroNotario" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@nombreNotario" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@fechaEmision" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@notasDelAviso" />
        </xsl:call-template>
        <xsl:apply-templates select="Testador" />
        <xsl:apply-templates select="Instrumento" />
        <xsl:apply-templates select="Padres" />
        <xsl:apply-templates select="Domicilio" />
    </xsl:template>
    <xsl:template match="Testador">
        <!-- Iniciamos el tratamiento de los atributos del aviso de testamento -->
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@nombre" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@apellidoPaterno" />
        </xsl:call-template>
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@apellidoMaterno" />
        </xsl:call-template>
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@apellidoConyuge" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@nacionalidad" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@lugarDeNacimiento" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@fechaDeNacimiento" />
        </xsl:call-template>

        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@estadoCivil" />
        </xsl:call-template>
    </xsl:template>
    <!-- Manejador de nodos tipo Instrumento notarial -->
    <xsl:template match="Instrumento">
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@tipodeTestamento" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@escritura" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@volumen" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@fechaDeEscritura" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@fechaDeOtorgamiento" />
        </xsl:call-template>
    </xsl:template>

    <xsl:template match="Padres">
        <!-- Iniciamos el tratamiento de los padres del testador -->
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@nombrePadre" />
        </xsl:call-template>
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@paternoPadre" />
        </xsl:call-template>
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@maternoPadre" />
        </xsl:call-template>

        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@nombreMadre" />
        </xsl:call-template>
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@paternoMadre" />
        </xsl:call-template>
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@maternoMadre" />
        </xsl:call-template>
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@apellidoConyugeMadre" />
        </xsl:call-template>
    </xsl:template>

    <!-- Manejador de nodos tipo Domicilio -->
    <xsl:template match="Domicilio">
        <!-- Iniciamos el tratamiento de los atributos del Domicilio -->
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@calleYNumero" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@colonia" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@delegacionMunicipio" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@entidad" />
        </xsl:call-template>
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@codigoPostal" />
        </xsl:call-template>
    </xsl:template>
        <!-- Manejador de datos opcionales -->
        <xsl:template name="Opcional">
                <xsl:param name="valor"/>
                <xsl:if test="$valor">|<xsl:call-template name="ManejaEspacios"><xsl:with-param name="s" select="$valor"/></xsl:call-template></xsl:if>
        </xsl:template>

        <!-- Normalizador de espacios en blanco -->
        <xsl:template name="ManejaEspacios">
                <xsl:param name="s"/>
                <xsl:value-of select="normalize-space(string($s))"/>
        </xsl:template>
        <xsl:template name="Requerido">
                <xsl:param name="valor"/>|<xsl:call-template name="ManejaEspacios">
                        <xsl:with-param name="s" select="$valor"/>
                </xsl:call-template>
        </xsl:template>
</xsl:stylesheet>
