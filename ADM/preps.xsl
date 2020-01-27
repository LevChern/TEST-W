<?xml version='1.0' encoding = "UTF-8" ?>
<xsl:stylesheet version="1.0"   xmlns:xsl="http://www.w3.org/1999/XSL/Transform"  xmlns:fo="http://www.w3.org/1999/XSL/Format">
<xsl:output encoding = "UTF-8" method='text'/>
<!-- <нагрузка_преп>
  <преподаватель фио="Лукин Владимир Николаевич" fio="lvn" psw="22" должность="доцент" />
Чернышов;chern;314;
-->
<xsl:template match="нагрузка_преп">
<xsl:for-each select="преподаватель" >
	<xsl:value-of select="substring-before(@фио,' ')"/>;<xsl:value-of select="@fio"/>;<xsl:value-of select="@psw"/>
	<xsl:value-of select="'&#x0A;'"/>
	
</xsl:for-each> 
</xsl:template>


</xsl:stylesheet>