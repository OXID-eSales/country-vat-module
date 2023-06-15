[{$smarty.block.parent}]
[{if $oxid != "-1"}]
<tr>
    <td class="edittext">
        [{oxmultilang ident="OXPSCOUNTRYVATADMINISTRATION_COUNTRY_MAIN_VAT"}]
    </td>
    <td class="edittext">
        <input type="button" value="[{oxmultilang ident=OXPSCOUNTRYVATADMINISTRATION_COUNTRY_MAIN_VAT}]" class="edittext" onclick="JavaScript:showDialog('&cl=category_main&aoc=1&cvat=1&oxid=[{$oxid}]');">
    </td>
</tr>
[{/if}]