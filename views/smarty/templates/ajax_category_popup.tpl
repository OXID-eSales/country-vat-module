[{include file="popups/headitem.tpl" title="OXPSCOUNTRYVATADMINISTRATION_COUNTRY_MAIN_VAT"|oxmultilangassign}]


<script type="text/javascript">
    initAoc = function()
    {
        let container1 = JSON.parse('[{$container1->encoded}]'), 
            container2 = JSON.parse('[{$container2->encoded}]');
        let uri1 = '[{$container1->getUri(oViewConf)}]',
            uri2 = '[{$container2->getUri(oViewConf)}]';

        YAHOO.oxid.container1 = new YAHOO.oxid.aoc( 'container1', container1, uri1);
        YAHOO.oxid.container2 = new YAHOO.oxid.aoc( 'container2', container2, uri2);

        YAHOO.oxid.container1.getDropAction = function()
        {
            return 'fnc=addattr';
        }

        YAHOO.oxid.container2.getDropAction = function()
        {
            return 'fnc=removeattr';
        }
        YAHOO.oxid.container2.subscribe( "rowClickEvent", function( oParam )
        {
            var aSelRows= YAHOO.oxid.container2.getSelectedRows();
            if ( aSelRows.length ) {
                oParam = YAHOO.oxid.container2.getRecord(aSelRows[0]);
                $('_attrname').innerHTML = oParam._oData._0;
                $('attr_value').value    = oParam._oData._2;
                $('attr_oxid').value     = oParam._oData._3;
                $D.setStyle( $('arrt_conf'), 'visibility', '' );
            } else {
                $D.setStyle( $('arrt_conf'), 'visibility', 'hidden' );
            }
        })
        YAHOO.oxid.container2.subscribe( "dataReturnEvent", function()
        {
            $D.setStyle( $('arrt_conf'), 'visibility', 'hidden' );
        })
        YAHOO.oxid.container2.onSave = function()
        {
            YAHOO.oxid.container1.getDataSource().flushCache();
            YAHOO.oxid.container1.getPage( 0 );
            YAHOO.oxid.container2.getDataSource().flushCache();
            YAHOO.oxid.container2.getPage( 0 );
        }
        YAHOO.oxid.container2.onFailure = function(e) { 
            alert('Something went wrong no callback, look into console for details.');
            console.error(e);
        }
        YAHOO.oxid.container2.saveAttribute = function()
        {
            var callback = {
                success: YAHOO.oxid.container2.onSave,
                failure: YAHOO.oxid.container2.onFailure,
                scope:   YAHOO.oxid.container2
            };
            const uri = '[{$oViewConf->getAjaxLink()}]&cmpid=container2&container=category_mainvat&fnc=saveAttributeValue&oxid=[{$oxid}]&attr_value=' + encodeURIComponent( $('attr_value').value ) + '&attr_oxid=' + encodeURIComponent( $('attr_oxid').value );
            YAHOO.util.Connect.asyncRequest( 'GET', uri, callback );

        }
        // subscribint event listeners on buttons
        $E.addListener( $('saveBtn'), "click", YAHOO.oxid.container2.saveAttribute, $('saveBtn') );
    }
    $E.onDOMReady( initAoc );
</script>

<table width="100%">
    <colgroup>
        <col span="2" width="40%" />
        <col width="20%" />
    </colgroup>
    <tr class="edittext">
        <td colspan="3">[{oxmultilang ident="GENERAL_AJAX_DESCRIPTION"}]<br>[{oxmultilang ident="GENERAL_FILTERING"}]<br /><br /></td>
    </tr>
    <tr class="edittext">
        <td align="center" valign="top"><b>[{oxmultilang ident="OXPSCOUNTRYVATADMINISTRATION_COUNTRY_COUNTRY"}]</b></td>
        <td align="center" valign="top"><b>[{oxmultilang ident="OXPSCOUNTRYVATADMINISTRATION_COUNTRY_ARTICLE"}]</b></td>
        <td align="center" valign="top">[{oxmultilang ident="OXPSCOUNTRYVATADMINISTRATION_COUNTRY_VAT_INPUT"}]:</td>
    </tr>
    <tr>
        <td valign="top" id="container1"></td>
        <td valign="top" id="container2"></td>
        <td valign="top" align="center" class="edittext" id="arrt_conf" style="visibility:hidden">
            <br><br>
            <b id="_attrname">[{$attr_name}]</b>:<br><br>
            <input id="attr_oxid" type="hidden">
            <input id="attr_value" class="editinput" type="text"><br><br>
            <input id="saveBtn" type="button" class="edittext" value="[{oxmultilang ident="OXPSCOUNTRYVATADMINISTRATION_COUNTRYVAT_SAVE"}]">
        </td>
    </tr>
    <tr>
        <td class="oxid-aoc-actions"><input type="button" value="[{oxmultilang ident="GENERAL_AJAX_ASSIGNALL"}]" id="container1_btn"></td>
        <td class="oxid-aoc-actions"><input type="button" value="[{oxmultilang ident="GENERAL_AJAX_UNASSIGNALL"}]" id="container2_btn"></td>
        <td></td>
    </tr>
</table>

</body>
</html>