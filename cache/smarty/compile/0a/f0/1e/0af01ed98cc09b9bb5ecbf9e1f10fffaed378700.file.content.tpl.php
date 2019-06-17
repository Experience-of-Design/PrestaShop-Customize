<?php /* Smarty version Smarty-3.1.19, created on 2019-06-10 04:40:41
         compiled from "D:\__WorkProjects\__PROJECTS\PS_PromoModule\_NEW\admin365e5pevc\themes\default\template\content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9932366495cfdc329a63181-81116268%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0af01ed98cc09b9bb5ecbf9e1f10fffaed378700' => 
    array (
      0 => 'D:\\__WorkProjects\\__PROJECTS\\PS_PromoModule\\_NEW\\admin365e5pevc\\themes\\default\\template\\content.tpl',
      1 => 1541055670,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9932366495cfdc329a63181-81116268',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cfdc329a64d81_63800754',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cfdc329a64d81_63800754')) {function content_5cfdc329a64d81_63800754($_smarty_tpl) {?>
<div id="ajax_confirmation" class="alert alert-success hide"></div>

<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }} ?>
