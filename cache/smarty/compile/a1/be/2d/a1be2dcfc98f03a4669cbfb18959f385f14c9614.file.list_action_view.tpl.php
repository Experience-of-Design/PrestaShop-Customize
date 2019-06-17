<?php /* Smarty version Smarty-3.1.19, created on 2019-06-10 10:46:51
         compiled from "D:\__WorkProjects\__PROJECTS\PS_PromoModule\_NEW\admin365e5pevc\themes\default\template\helpers\list\list_action_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7408892155cfe18fb3efdd2-87435778%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a1be2dcfc98f03a4669cbfb18959f385f14c9614' => 
    array (
      0 => 'D:\\__WorkProjects\\__PROJECTS\\PS_PromoModule\\_NEW\\admin365e5pevc\\themes\\default\\template\\helpers\\list\\list_action_view.tpl',
      1 => 1541055670,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7408892155cfe18fb3efdd2-87435778',
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
  'unifunc' => 'content_5cfe18fb4090c4_30904153',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cfe18fb4090c4_30904153')) {function content_5cfe18fb4090c4_30904153($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['href']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>
" >
	<i class="icon-search-plus"></i> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>

</a>
<?php }} ?>
