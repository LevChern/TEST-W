<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format">
  <xsl:output encoding="UTF-8" method="text" />
<!-- 
-->
  <xsl:template match="tests">
{
	"test":
	{
	"assessmentItem":
	[
<xsl:variable name="qall"><xsl:value-of select="count(assessmentItem)"/></xsl:variable>
<xsl:for-each select="assessmentItem">
<xsl:variable name="typ"><xsl:value-of select="responseDeclaration/@cardinality"/></xsl:variable>
	{
		"responseDeclaration": {
			"correctResponse": {
<xsl:if test="$typ='single'">			
				"value":"<xsl:value-of select="responseDeclaration/correctResponse/value"/>"
</xsl:if>
<xsl:if test="$typ='multiple'">
	<xsl:variable name="num"><xsl:value-of select="count(responseDeclaration/correctResponse/value)"/></xsl:variable>
				"value":[<xsl:for-each select="responseDeclaration/correctResponse/value">
				"<xsl:value-of select="."/>"<xsl:if test="position()&lt;$num">,</xsl:if>
</xsl:for-each>					
				]
</xsl:if>
			},
		"_identifier": "RESPONSE",
		"_cardinality": "<xsl:value-of select="$typ"/>"
		},
		"itemBody": {
		"choiceInteraction": {
		"prompt": "<xsl:value-of select="itemBody/choiceInteraction/prompt"/>",
		"simpleChoice": [
<xsl:variable name="qnum"><xsl:value-of select="count(itemBody/choiceInteraction/simpleChoice)"/></xsl:variable>
<xsl:for-each select="itemBody/choiceInteraction/simpleChoice">
	{
		"_identifier": "<xsl:value-of select="@identifier"/>",
		"__text": "<xsl:value-of select="."/>"
	}<xsl:if test="position()&lt;$qnum">,</xsl:if>
</xsl:for-each>					
		]
	}
	}
	}<xsl:if test="position()&lt;$qall">,</xsl:if>
	
</xsl:for-each> 
	]
	}
}
</xsl:template>
</xsl:stylesheet>