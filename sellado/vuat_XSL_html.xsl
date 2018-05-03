<xsl:stylesheet 
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:fn="http://www.w3.org/2005/xpath-functions"
                xmlns:vuat="http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado"
                     version="1.0" >
    <xsl:output encoding="UTF-8"
                indent="no"
                method="text"
                version="1.0" />
    <xsl:template match="/">
&lt;link type="text/css" rel="StyleSheet" href="digital.css" media="(min-width:1024px)"&gt;
&lt;link type="text/css" rel="StyleSheet" href="digital.css" media="(max-width:481px)"&gt;
&lt;link type="text/css" rel="StyleSheet" href="digital.css" media="(min-width:482px) and (max-width:1023px)"&gt;
&lt;table&gt;&lt;th colspan=2 class=titulo &gt;AVISO DE TESTAMENTO&lt;/th&gt;<xsl:apply-templates select="AvisoDeTestamento" />&lt;/table&gt;
    </xsl:template>
    <xsl:template match="AvisoDeTestamento">
        &lt;tr&gt; &lt;td class=dato &gt; Folio del Aviso: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@idAviso" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Linea de Captura: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@lineaDeCaptura" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Tramite: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@tramite" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Numero de notario: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@numeroNotario" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Nombre de notario: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@nombreNotario" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Fecha de Emision: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@fechaEmision" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Notas del aviso: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@notasDelAviso" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td colspan=2 class=subtit &gt; TESTADOR &lt;/td&gt; &lt;/tr&gt;
        <xsl:apply-templates select="Testador" />
        &lt;tr&gt; &lt;td colspan=2 class=subtit &gt; INSTRUMENTO NOTARIAL &lt;/td&gt; &lt;/tr&gt;
        <xsl:apply-templates select="Instrumento" />
        &lt;tr&gt; &lt;td colspan=2 class=subtit &gt; PADRES &lt;/td&gt; &lt;/tr&gt;
        <xsl:apply-templates select="Padres" />
        &lt;tr&gt; &lt;td colspan=2 class=subtit &gt; DOMICILIO &lt;/td&gt; &lt;/tr&gt;
        <xsl:apply-templates select="Domicilio" />
        &lt;tr&gt; &lt;td colspan=2 class=subtit &gt; FIRMAS DIGITALES &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Certificado: &lt;/td&gt; &lt;td class=valor &gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@certificado" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Sello: &lt;/td&gt; &lt;td class=valor &gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@sello" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Cadena Original Sellada: &lt;/td&gt; &lt;td class=valor &gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@cadenaOriginal" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;


    </xsl:template>
    <xsl:template match="Testador">
        <!-- Iniciamos el tratamiento de los atributos del aviso de testamento -->
        &lt;tr&gt; &lt;td&gt; Nombre: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@nombre" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Apellido Paterno: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@apellidoPaterno" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Apellido Materno: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@apellidoMaterno" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Apellido Conyuge: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@apellidoConyuge" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Nacionalidad: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@nacionalidad" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Lugar de Nacimiento: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@lugarDeNacimiento" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Fecha de Nacimiento: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@fechaDeNacimiento" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Estado Civil: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@estadoCivil" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
    </xsl:template>
    <!-- Manejador de nodos tipo Instrumento notarial -->
    <xsl:template match="Instrumento">
        &lt;tr&gt; &lt;td&gt; Tipo de Testamento: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@tipodeTestamento" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Escritura: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@escritura" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Volumen: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@volumen" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Fecha de Escritura: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@fechaDeEscritura" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Fecha de Otorgamiento: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@fechaDeOtorgamiento" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
    </xsl:template>

    <xsl:template match="Padres">
        <!-- Iniciamos el tratamiento de los padres del testador -->
        &lt;tr&gt; &lt;td&gt; Nombre del Padre: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@nombrePadre" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Apellido Paterno del Padre: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@paternoPadre" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Apellido Materno del Padre: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@maternoPadre" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Nombre de la Madre: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@nombreMadre" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Apellido Paterno de la Madre: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@paternoMadre" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Apellido Materno de la Madre: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@maternoMadre" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Apellido Conyuge de la Madre: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Opcional">
            <xsl:with-param name="valor"
                            select="./@apellidoConyugeMadre" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
    </xsl:template>

    <!-- Manejador de nodos tipo Domicilio -->
    <xsl:template match="Domicilio">
        <!-- Iniciamos el tratamiento de los atributos del Domicilio -->
        &lt;tr&gt; &lt;td&gt; Calle y Numero: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@calleYNumero" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Colonia: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@colonia" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Delegacion o Municipio: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@delegacionMunicipio" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Entidad: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@entidad" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
        &lt;tr&gt; &lt;td&gt; Codigo Postal: &lt;/td&gt; &lt;td&gt;
        <xsl:call-template name="Requerido">
            <xsl:with-param name="valor"
                            select="./@codigoPostal" />
        </xsl:call-template>
        &lt;/td&gt; &lt;/tr&gt;
    </xsl:template>
        <!-- Manejador de datos opcionales -->
        <xsl:template name="Opcional">
                <xsl:param name="valor"/>
                <xsl:if test="$valor"><xsl:call-template name="ManejaEspacios"><xsl:with-param name="s" select="$valor"/></xsl:call-template></xsl:if>
        </xsl:template>

        <!-- Normalizador de espacios en blanco -->
        <xsl:template name="ManejaEspacios">
                <xsl:param name="s"/>
                <xsl:value-of select="normalize-space(string($s))"/>
        </xsl:template>
        <xsl:template name="Requerido">
                <xsl:param name="valor"/><xsl:call-template name="ManejaEspacios">
                        <xsl:with-param name="s" select="$valor"/>
                </xsl:call-template>
        </xsl:template>
</xsl:stylesheet>
