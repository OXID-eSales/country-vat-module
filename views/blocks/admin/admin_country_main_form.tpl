[{$smarty.block.parent}]

<tr><!-- Liefersperre Privatkunden -->
    <td class="edittext">
        <label for="oxps_countryvatadministration_country_vat">[{oxmultilang ident="OXPSCOUNTRYVATADMINISTRATION_COUNTRY_MAIN_VAT"}]</label>
    </td>
    <td class="edittext">
        <input type="number" id="oxps_countryvatadministration_country_vat" name="editval[oxps_countryvatadministration_country_vat]" min="0" max="100" step="0.01" size="5" maxlength="11" value="[{$oView->configuredVat()}]">
    </td>
</tr>