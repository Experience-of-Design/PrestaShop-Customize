<?php /* Smarty version Smarty-3.1.19, created on 2019-06-10 04:09:14
         compiled from "D:\__WorkProjects\__PROJECTS\PS_PromoModule\_NEW\admin\themes\default\template\helpers\list\list_action_removestock.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9014598965cfdbbcaa4ca90-92603103%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '92259dd210f3fa0bea20dfae4c09403b07c387f4' => 
    array (
      0 => 'D:\\__WorkProjects\\__PROJECTS\\PS_PromoModule\\_NEW\\admin\\themes\\default\\template\\helpers\\list\\list_action_removestock.tpl',
      1 => 1541055670,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9014598965cfdbbcaa4ca90-92603103',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'href' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cfdbbcaa5b3b1_62845118',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cfdbbcaa5b3b1_62845118')) {function content_5cfdbbcaa5b3b1_62845118($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['href']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>
">
	<i class="icon-circle-arrow-down"></i> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>

</a>
<?php }} ?>
