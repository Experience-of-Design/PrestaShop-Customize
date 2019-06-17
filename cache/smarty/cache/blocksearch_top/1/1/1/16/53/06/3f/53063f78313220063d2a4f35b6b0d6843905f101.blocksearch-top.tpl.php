<?php /*%%SmartyHeaderCode:17977899915cfe44f6ca4c80-08834194%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '53063f78313220063d2a4f35b6b0d6843905f101' => 
    array (
      0 => 'D:\\__WorkProjects\\__PROJECTS\\PS_PromoModule\\_NEW\\themes\\default-bootstrap\\modules\\blocksearch\\blocksearch-top.tpl',
      1 => 1541055672,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17977899915cfe44f6ca4c80-08834194',
  'variables' => 
  array (
    'link' => 0,
    'search_query' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cfe44f6d081d4_92382212',
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cfe44f6d081d4_92382212')) {function content_5cfe44f6d081d4_92382212($_smarty_tpl) {?><!-- Block search module TOP -->
<div id="search_block_top" class="col-sm-4 clearfix">
	<form id="searchbox" method="get" action="//psmodule.local:8088/vyhledavani" >
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="Vyhledávání" value="" />
		<button type="submit" name="submit_search" class="btn btn-default button-search">
			<span>Vyhledávání</span>
		</button>
	</form>
</div>
<!-- /Block search module TOP -->
<?php }} ?>
