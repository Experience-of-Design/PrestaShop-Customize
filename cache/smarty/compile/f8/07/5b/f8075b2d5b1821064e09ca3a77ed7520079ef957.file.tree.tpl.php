<?php /* Smarty version Smarty-3.1.19, created on 2019-06-10 04:09:16
         compiled from "D:\__WorkProjects\__PROJECTS\PS_PromoModule\_NEW\admin\themes\default\template\helpers\tree\tree.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5325654195cfdbbccb1fb41-23292087%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f8075b2d5b1821064e09ca3a77ed7520079ef957' => 
    array (
      0 => 'D:\\__WorkProjects\\__PROJECTS\\PS_PromoModule\\_NEW\\admin\\themes\\default\\template\\helpers\\tree\\tree.tpl',
      1 => 1541055670,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5325654195cfdbbccb1fb41-23292087',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'header' => 0,
    'nodes' => 0,
    'id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cfdbbccb23947_40252197',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cfdbbccb23947_40252197')) {function content_5cfdbbccb23947_40252197($_smarty_tpl) {?>
<div class="panel">
	<?php if (isset($_smarty_tpl->tpl_vars['header']->value)) {?><?php echo $_smarty_tpl->tpl_vars['header']->value;?>
<?php }?>
	<?php if (isset($_smarty_tpl->tpl_vars['nodes']->value)) {?>
	<ul id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['id']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="tree">
		<?php echo $_smarty_tpl->tpl_vars['nodes']->value;?>

	</ul>
	<?php }?>
</div>
<?php }} ?>
