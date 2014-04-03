<?xml version="1.0" encoding="UTF-8"?>
<!-- 
 SobiPro Template SobiRestara
 Authors: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 Copyright (C) 2012 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 Released under Sigsiu.NET Template License V1
 -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
<xsl:output method="xml" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

	<xsl:template name="catChooser">			
		<div>
			<div id="SpTreeCont" class="sigsiuTree sobiCatsSigsiuTree" >
				<xsl:copy-of select="tree/*" disable-output-escaping="yes"/>
			</div>
			<div id="SpSelectedCatsCont">							
				<div>
					<select name="categories" id="selectedCats" size="10" class="inputbox"/>						
				</div>
                <div id="SpTreeButtonsCont">
                    <div id="SpTreeAddButtonCont">
                        <button type="button" name="addCat" id="SpTreeAddButton" onclick="SP_addCat();" size="50" class="button">
                            <xsl:value-of select="php:function( 'SobiPro::Txt' , 'TP.ADD_CATEGORY_BT' )" disable-output-escaping="yes"/>                    
                        </button>
                    </div>
                    <div id="SpTreeDelButtonCont">
                        <button type="button" name="delCat" id="SpTreeDelButton" onclick="SP_delCat();" size="50" class="button">
                            <xsl:value-of select="php:function( 'SobiPro::Txt' , 'TP.REMOVE_CATEGORY_BT' )" disable-output-escaping="yes"/> 
                        </button>
                    </div>
                </div>
			</div>
            <div id="SpTreeSaveButtonCont">
                <button type="button" name="save" id="SPSaveCats" onclick="SP_Save();" size="50" class="button simplemodal-close">
                    <xsl:value-of select="php:function( 'SobiPro::Txt' , 'TP.SAVE_BT' )" disable-output-escaping="yes"/>
                </button>
            </div>                
		</div>
        <div class="spclear" />
	</xsl:template>
</xsl:stylesheet>