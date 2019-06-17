<script type="text/javascript">
	jQuery(document).ready( function () {
		checker();
	});

	function checker()
	{
		jQuery( "#carrier_form" );
	}

	function carrier_excl()
	{
		var tax = carrier_form.tax.value;
		var vat = (tax/100.00)+1.00;
		if(vat!=0.00) {
			var result = carrier_form.carexclvat.value*vat;
			carrier_form.carinclvat.value=result.toFixed(2);
		} else {
			carrier_form.carinclvat.value=carrier_form.carexclvat.value;
		}
	}

	function carrier_incl()
	{
		var tax = carrier_form.tax.value;

		if(tax !=0.00) {
			var vat = ((tax/100.00)+1.00)*100;
			var result=(carrier_form.carinclvat.value*100)/vat;
			carrier_form.carexclvat.value=result.toFixed(2);
		} else {
			carrier_form.carexclvat.value=carrier_form.carinclvat.value;
		}
	}

	function PackageShippingCost(id_carrier)
	{		
		$.ajax({
			url: "{$link->getModuleLink($module_name, 'ajax', [], false)}",
			type:"POST",
			dataType: "json",
			data : {
					submit_carrier	: 1,
					id_carrier		: id_carrier,
					id_order		: '{$order->id}'
			},
			success: function(data){
				$("#carinclvat").val(data.shipping_tax_incl);
				$("#carexclvat").val(data.shipping_tax_excl);
			}
    	});
	}
</script>

{if $version_1_6}
<div id="edit_carriers">
{/if}

<form action="{$moduleIndex}" method="post" id="carrier_form" style="width:100%">
	<input type="hidden" id="tax"  name="tax" value="{$tax_rate}" />
	<input type="hidden" name="currentIndex" value="{$currentIndex}" />
	<input type="hidden" name="id_order" value="{$order->id}" />
	<fieldset>
		<legend><img src="../img/admin/suppliers.gif" alt="" title="" /> {l s='Change Carrier Parameters' mod='add_editcarriersinorder'}</legend>
		<p>
			<label>{l s='Carrier: ' mod='add_editcarriersinorder'}</label>
			<select name="id_carrier" style="width: 150px;" onChange="PackageShippingCost(this.options[this.selectedIndex].value)">
			{foreach $carriers as $carrier}
				<option value="{$carrier.id_carrier}" {if $carrier.id_carrier == $order->id_carrier}selected="selected"{/if}>
					{$carrier.name}
				</option>
			{/foreach}
			</select>
		</p>
		<p>
			<label>{l s='Price(VAT incl.): ' mod='add_editcarriersinorder'}</label>
			<input  onkeyup="javascript:carrier_incl();" id="carinclvat" name="carinclvat" value="{$order->total_shipping_tax_incl}" />
		</p>
		<p>
			<label>{l s='Price(VAT excl.): ' mod='add_editcarriersinorder'}</label>
			<input onkeyup="javascript:carrier_excl();" id="carexclvat" name="carexclvat" value="{$order->total_shipping_tax_excl}" />
		</p>
		<p>
			<label>{l s='Weight: ' mod='add_editcarriersinorder'}</label>
			<input name="weight" id="carWeight" value="{$order_carrier->weight}" />
		</p>
		<center><input id="submit_carrier" type="submit" class="button" name="submit_carrier" value="{l s='Update' mod='add_editcarriersinorder'}" onclick="return confirm('{l s='Do you really want to update this settings?' mod='add_editcarriersinorder'}');"/></center>
	</fieldset>
</form>
{if $version_1_6}
</div>
{/if}
