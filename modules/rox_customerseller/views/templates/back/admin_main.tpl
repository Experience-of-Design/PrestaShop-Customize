<div class="bootstrap container-fluid" id='module-object' data-token="{Tools::getValue('token')}">
<script>
				$(".datepicker").datepicker({
					prevText: '',
					nextText: '',
					altFormat: 'yy-mm-dd'
				});
    function submit_filter() {
        var token = $('#module-object').data('token');
        var id = $('#id').val();
        var reference = $('#reference').val();
        var status = $('#status').val();
        var date_from = $('#local_orderFilter_a__date_add_0').val();
        var date_to = $('#local_orderFilter_a__date_add_1').val();
        var url = 'index.php?controller=OrderDetailView_Manager&token='+token;
        
        
        
        url += id !== undefined ? '&id='+id : '';
        url += reference !== undefined ? '&ref='+reference : '';
        url += status !== undefined ? '&status='+status : '';
        url += date_from !== undefined ? '&from='+date_from : '';
        url += date_to !== undefined ? '&to='+date_to : '';
        window.location.href = url;
    }
    function row_state(id) {
        var last = $('.open').attr('id');
        if (last == 'row-'+id) {
            $('.opened').removeClass('opened').hide();
            $('.open').removeClass('open');
        } else {
            $('.opened').removeClass('opened').hide();
            $('.open').removeClass('open');  

            var classname = 'row-'+id;
            $('#'+classname).addClass('open');
            var elements = document.getElementsByClassName(classname);
            var names = '';
            for(var i=0; i<elements.length; i++) {
                $('#'+elements[i].id).show().addClass('opened');
            }
        }
    }
</script>
<div class="row">
  <table class="table product">
    <thead>
    <tr>
    <th>Obchodník</th>
    <th>Počet zákazníků</th>
    <th>Stav</th>
    <th>Datum poslední objednávky</th>
    <th>Počet bodů</th>
    <th>Utraceno bodů</th>
    <th>Suma objednávek</th>
    <th>Akce</th>
    </tr>
    <tr>
        <th>
            <input type="text" class="form-control" placeholder="Jméno nebo příjmení" id="id" name="id" value="{Tools::getValue('id')}" />
            Celkem zobrazeno: {$datacount}
        </th>
        <th>
            <input type="text" class="form-control" placeholder="počet" id="reference" name="reference" value="{Tools::getValue('ref')}" />
        </th>
        <th>
        <select name="statuses" class="form-control" id="status">
        <option value=""></option>
        {foreach OrderState::getOrderStates((int)Context::getContext()->language->id) as $states}
        {if (Tools::getValue('status') == $states.id_order_state)}
            <option value="{$states.id_order_state}" selected="selected">
                {$states.name}
            </option>
        {else}
            <option value="{$states.id_order_state}">
                {$states.name}
            </option>
        {/if}
        {/foreach}
        </select>
        </th>
        <th class="text-right">
            <div class="date_range row">
                <div class="input-group fixed-width-md center">
                    <input class="filter datepicker date-input form-control hasDatepicker" id="local_orderFilter_a__date_add_0" name="local_orderFilter_a!date_add[0]" placeholder="Od" type="date" style="line-height: 20px;" value="{Tools::getValue('from')}" />
                    <input id="orderFilter_a__date_add_0" name="orderFilter_a!date_add[0]" value="{Tools::getValue('from')}" type="hidden" />
                    <span class="input-group-addon">
                        <i class="icon-calendar"></i>
                    </span>
                </div>
                <div class="input-group fixed-width-md center">
                    <input class="filter datepicker date-input form-control hasDatepicker" id="local_orderFilter_a__date_add_1" name="local_orderFilter_a!date_add[1]" placeholder="Do" type="date" style="line-height: 20px;" value="{Tools::getValue('to')}" />
                    <input id="orderFilter_a__date_add_1" name="orderFilter_a!date_add[1]" value="{Tools::getValue('to')}" type="hidden" />
                    <span class="input-group-addon">
                        <i class="icon-calendar"></i>
                    </span>
                </div>
            </div>
        </th>
        <th>
            <input type="text" class="form-control" disabled="disabled" />
        </th>
        <th>
            <input type="text" class="form-control" disabled="disabled" />
        </th>
        <th>
            <input type="text" class="form-control" disabled="disabled" />
        </th>
        <th>
            <input type="submit" class="form-control btn btn-success" name="submit" id="send_filter" value="Filtrovat" onclick="submit_filter();" />
        </th>
    </tr>
    </thead>
    <tbody>
    {if ($data != null && $datacount > 0)}
    {foreach $data as $row}
    <tr id="row-{$row.id_item}" onclick="row_state({$row.id_item});" data-id="{$row.id_item}">
      <td>{$row.id_item}</td>
      <td>
          {$row.fullname}
      </td>
      <td>
          Aktivní
        {foreach OrderState::getOrderStates((int)Context::getContext()->language->id) as $states}
        {if ($states.id_order_state == $row.state)}
            {if ((substr($states.color, 1, 1) == 'f') || (substr($states.color, 1, 1) == 'e') || (substr($states.color, 1, 1) == 'd') || (substr($states.color, 1, 1) == 'c'))}
            <div style="background-color: {$states.color}; color: #000; text-align: center; padding: 5px 0;">
            {else}
            <div style="background-color: {$states.color}; color: #fff; text-align: center; padding: 5px 0;">
            {/if}
        {$states.name}
        </div>
        {/if}
        {/foreach}
      </td>
      <td>
        {$row.add}
      </td>
      <td>
        {$row.points}
      </td>
      <td>
        {$row.points_used}
      </td>
      <td>
        {$row.price}
      </td>
      <td>
        <a href="{$link}{$row.id_item}" class="btn btn-default">
        <i class="fa fa-edit"></i> Přejít
        </a>
      </td>
    </tr>
    {if (isset($row['items']))}
    {foreach $row['items'] as $pr}
    <tr style="display: none;" class="row-{$row.id_item}" id='item-{$row.id_item}-{$pr.id_product}'>
        <td>
            {$pr.id_product}
        </td>
        <td>
            {$pr.name}
        </td>
        <td>
            {$pr.reference}
        </td>
        <td>
            {$pr.quantity} kusů
        </td>
        <td>
            {$pr.unit_price}
        </td>
        <td>
            {$pr.total_price}
        </td>
    </tr>
    {/foreach}
    {/if}
    {/foreach}
    {else}
    <tr>
      <td colspan="7">
        <div class="col-lg-10 col-lg-push-1 alert-danger text-center" style="padding: 20px 10px; font-size: 14px; border: 1px solid #AA7777;">
            <i class="icon-remove" style="font-size: 16px;"></i> Žádná data v této kombinaci nebyla nalezena
        </div>
      </td>
    </tr>
    {/if}
    </tbody>
  </table>
</div>
</div>