<?php /* Smarty version Smarty-3.1.19, created on 2019-06-10 14:51:09
         compiled from "D:\__WorkProjects\__PROJECTS\PS_PromoModule\_NEW\modules\rox_customerseller\\views\templates\back\admin_main.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18971533525cfe17e2a560e0-12505323%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bd9e57ca609aa0b7dd95223fd2c0d4cca5df1cf9' => 
    array (
      0 => 'D:\\__WorkProjects\\__PROJECTS\\PS_PromoModule\\_NEW\\modules\\rox_customerseller\\\\views\\templates\\back\\admin_main.tpl',
      1 => 1560171065,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18971533525cfe17e2a560e0-12505323',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cfe17e2c36054_69388128',
  'variables' => 
  array (
    'datacount' => 0,
    'states' => 0,
    'data' => 0,
    'row' => 0,
    'link' => 0,
    'pr' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cfe17e2c36054_69388128')) {function content_5cfe17e2c36054_69388128($_smarty_tpl) {?><div class="bootstrap container-fluid" id='module-object' data-token="<?php echo Tools::getValue('token');?>
">
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
            <input type="text" class="form-control" placeholder="Jméno nebo příjmení" id="id" name="id" value="<?php echo Tools::getValue('id');?>
" />
            Celkem zobrazeno: <?php echo $_smarty_tpl->tpl_vars['datacount']->value;?>

        </th>
        <th>
            <input type="text" class="form-control" placeholder="počet" id="reference" name="reference" value="<?php echo Tools::getValue('ref');?>
" />
        </th>
        <th>
        <select name="statuses" class="form-control" id="status">
        <option value=""></option>
        <?php  $_smarty_tpl->tpl_vars['states'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['states']->_loop = false;
 $_from = OrderState::getOrderStates((int)Context::getContext()->language->id); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['states']->key => $_smarty_tpl->tpl_vars['states']->value) {
$_smarty_tpl->tpl_vars['states']->_loop = true;
?>
        <?php if ((Tools::getValue('status')==$_smarty_tpl->tpl_vars['states']->value['id_order_state'])) {?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['states']->value['id_order_state'];?>
" selected="selected">
                <?php echo $_smarty_tpl->tpl_vars['states']->value['name'];?>

            </option>
        <?php } else { ?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['states']->value['id_order_state'];?>
">
                <?php echo $_smarty_tpl->tpl_vars['states']->value['name'];?>

            </option>
        <?php }?>
        <?php } ?>
        </select>
        </th>
        <th class="text-right">
            <div class="date_range row">
                <div class="input-group fixed-width-md center">
                    <input class="filter datepicker date-input form-control hasDatepicker" id="local_orderFilter_a__date_add_0" name="local_orderFilter_a!date_add[0]" placeholder="Od" type="date" style="line-height: 20px;" value="<?php echo Tools::getValue('from');?>
" />
                    <input id="orderFilter_a__date_add_0" name="orderFilter_a!date_add[0]" value="<?php echo Tools::getValue('from');?>
" type="hidden" />
                    <span class="input-group-addon">
                        <i class="icon-calendar"></i>
                    </span>
                </div>
                <div class="input-group fixed-width-md center">
                    <input class="filter datepicker date-input form-control hasDatepicker" id="local_orderFilter_a__date_add_1" name="local_orderFilter_a!date_add[1]" placeholder="Do" type="date" style="line-height: 20px;" value="<?php echo Tools::getValue('to');?>
" />
                    <input id="orderFilter_a__date_add_1" name="orderFilter_a!date_add[1]" value="<?php echo Tools::getValue('to');?>
" type="hidden" />
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
    <?php if (($_smarty_tpl->tpl_vars['data']->value!=null&&$_smarty_tpl->tpl_vars['datacount']->value>0)) {?>
    <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
    <tr id="row-<?php echo $_smarty_tpl->tpl_vars['row']->value['id_item'];?>
" onclick="row_state(<?php echo $_smarty_tpl->tpl_vars['row']->value['id_item'];?>
);" data-id="<?php echo $_smarty_tpl->tpl_vars['row']->value['id_item'];?>
">
      <td><?php echo $_smarty_tpl->tpl_vars['row']->value['id_item'];?>
</td>
      <td>
          <?php echo $_smarty_tpl->tpl_vars['row']->value['fullname'];?>

      </td>
      <td>
          Aktivní
        <?php  $_smarty_tpl->tpl_vars['states'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['states']->_loop = false;
 $_from = OrderState::getOrderStates((int)Context::getContext()->language->id); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['states']->key => $_smarty_tpl->tpl_vars['states']->value) {
$_smarty_tpl->tpl_vars['states']->_loop = true;
?>
        <?php if (($_smarty_tpl->tpl_vars['states']->value['id_order_state']==$_smarty_tpl->tpl_vars['row']->value['state'])) {?>
            <?php if (((substr($_smarty_tpl->tpl_vars['states']->value['color'],1,1)=='f')||(substr($_smarty_tpl->tpl_vars['states']->value['color'],1,1)=='e')||(substr($_smarty_tpl->tpl_vars['states']->value['color'],1,1)=='d')||(substr($_smarty_tpl->tpl_vars['states']->value['color'],1,1)=='c'))) {?>
            <div style="background-color: <?php echo $_smarty_tpl->tpl_vars['states']->value['color'];?>
; color: #000; text-align: center; padding: 5px 0;">
            <?php } else { ?>
            <div style="background-color: <?php echo $_smarty_tpl->tpl_vars['states']->value['color'];?>
; color: #fff; text-align: center; padding: 5px 0;">
            <?php }?>
        <?php echo $_smarty_tpl->tpl_vars['states']->value['name'];?>

        </div>
        <?php }?>
        <?php } ?>
      </td>
      <td>
        <?php echo $_smarty_tpl->tpl_vars['row']->value['add'];?>

      </td>
      <td>
        <?php echo $_smarty_tpl->tpl_vars['row']->value['points'];?>

      </td>
      <td>
        <?php echo $_smarty_tpl->tpl_vars['row']->value['points_used'];?>

      </td>
      <td>
        <?php echo $_smarty_tpl->tpl_vars['row']->value['price'];?>

      </td>
      <td>
        <a href="<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
<?php echo $_smarty_tpl->tpl_vars['row']->value['id_item'];?>
" class="btn btn-default">
        <i class="fa fa-edit"></i> Přejít
        </a>
      </td>
    </tr>
    <?php if ((isset($_smarty_tpl->tpl_vars['row']->value['items']))) {?>
    <?php  $_smarty_tpl->tpl_vars['pr'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pr']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['row']->value['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pr']->key => $_smarty_tpl->tpl_vars['pr']->value) {
$_smarty_tpl->tpl_vars['pr']->_loop = true;
?>
    <tr style="display: none;" class="row-<?php echo $_smarty_tpl->tpl_vars['row']->value['id_item'];?>
" id='item-<?php echo $_smarty_tpl->tpl_vars['row']->value['id_item'];?>
-<?php echo $_smarty_tpl->tpl_vars['pr']->value['id_product'];?>
'>
        <td>
            <?php echo $_smarty_tpl->tpl_vars['pr']->value['id_product'];?>

        </td>
        <td>
            <?php echo $_smarty_tpl->tpl_vars['pr']->value['name'];?>

        </td>
        <td>
            <?php echo $_smarty_tpl->tpl_vars['pr']->value['reference'];?>

        </td>
        <td>
            <?php echo $_smarty_tpl->tpl_vars['pr']->value['quantity'];?>
 kusů
        </td>
        <td>
            <?php echo $_smarty_tpl->tpl_vars['pr']->value['unit_price'];?>

        </td>
        <td>
            <?php echo $_smarty_tpl->tpl_vars['pr']->value['total_price'];?>

        </td>
    </tr>
    <?php } ?>
    <?php }?>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="7">
        <div class="col-lg-10 col-lg-push-1 alert-danger text-center" style="padding: 20px 10px; font-size: 14px; border: 1px solid #AA7777;">
            <i class="icon-remove" style="font-size: 16px;"></i> Žádná data v této kombinaci nebyla nalezena
        </div>
      </td>
    </tr>
    <?php }?>
    </tbody>
  </table>
</div>
</div><?php }} ?>
