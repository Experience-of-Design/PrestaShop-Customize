<?php /*%%SmartyHeaderCode:16556511115cfe44f86392a6-75379608%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '34649d08c79a5e8f92185f5b6228397fd9aa40c5' => 
    array (
      0 => 'D:\\__WorkProjects\\__PROJECTS\\PS_PromoModule\\_NEW\\themes\\default-bootstrap\\modules\\blockcms\\blockcms.tpl',
      1 => 1541055672,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16556511115cfe44f86392a6-75379608',
  'variables' => 
  array (
    'block' => 0,
    'cms_titles' => 0,
    'cms_key' => 0,
    'cms_title' => 0,
    'cms_page' => 0,
    'link' => 0,
    'show_price_drop' => 0,
    'PS_CATALOG_MODE' => 0,
    'show_new_products' => 0,
    'show_best_sales' => 0,
    'display_stores_footer' => 0,
    'show_contact' => 0,
    'contact_url' => 0,
    'cmslinks' => 0,
    'cmslink' => 0,
    'show_sitemap' => 0,
    'footer_text' => 0,
    'display_poweredby' => 0,
  ),
  'has_nocache_code' => true,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cfe44f87e5431_89798636',
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cfe44f87e5431_89798636')) {function content_5cfe44f87e5431_89798636($_smarty_tpl) {?>
	<!-- Block CMS module footer -->
	<section class="footer-block col-xs-12 col-sm-2" id="block_various_links_footer">
		<h4>Informace</h4>
		<ul class="toggle-footer">
							<li class="item">
					<a href="http://psmodule.local:8088/slevy" title="Slevy">
						Slevy
					</a>
				</li>
									<li class="item">
				<a href="http://psmodule.local:8088/novinky" title="Nové produkty">
					Nové produkty
				</a>
			</li>
										<li class="item">
					<a href="http://psmodule.local:8088/Nejprodavanejsi" title="Nejprodávanější produkty">
						Nejprodávanější produkty
					</a>
				</li>
										<li class="item">
					<a href="http://psmodule.local:8088/prodejny" title="Naše prodejny">
						Naše prodejny
					</a>
				</li>
									<li class="item">
				<a href="http://psmodule.local:8088/napiste-nam" title="Napište nám">
					Napište nám
				</a>
			</li>
															<li class="item">
						<a href="http://psmodule.local:8088/content/3-terms-and-conditions-of-use" title="Terms and conditions of use">
							Terms and conditions of use
						</a>
					</li>
																<li class="item">
						<a href="http://psmodule.local:8088/content/4-about-us" title="About us">
							About us
						</a>
					</li>
													<li>
				<a href="http://psmodule.local:8088/mapa-stranek" title="Mapa stránek">
					Mapa stránek
				</a>
			</li>
					</ul>
		
	</section>
		<section class="bottom-footer col-xs-12">
		<div>
			<?php echo smartyTranslate(array('s'=>'[1] %3$s %2$s - Ecommerce software by %1$s [/1]','mod'=>'blockcms','sprintf'=>array('PrestaShop™',date('Y'),'©'),'tags'=>array('<a class="_blank" href="http://www.prestashop.com">')),$_smarty_tpl);?>

		</div>
	</section>
		<!-- /Block CMS module footer -->
<?php }} ?>
