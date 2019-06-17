<?php

/**
 * @author Michael Gordon
 * @copyright 2015
 */

class AdminPagexController extends AdminController {
	private $_empty_string = "";
	private $_error_collector = null;
	private $_error_output = "";
	private $_error_def = "FATAL: ERROR NOT READABLE!<br />";
	private $_error_exc_notread = "Error Reader cannot scan saved exception!<br />";
	private $_error_exc_notfound = "Error Thrower cannot open collector!<br />";
  private $_error_exc_notinit = "Error in init module!<br />";
  private $_token;
	private $_filters;
  private $_filters_name;
  private $_order;
	private $_offer;

  private $smarty;
	private $products = array();
	private $products_all = array();
  private $manufacturers = array();
	private $employee = 0;

	private $ecotax_global = 0;

	private $_operation = 0;
	private $_window = 0;
	private $_def_window = 1;

	public function initContent() {
		parent::initContent();
		$this->_ErrorCollectorClear();
		try {
			$this->smarty = $this->context->smarty;
			$tab = "AdminxPage";
			global $cookie;
			$this->employee = (int)$cookie->id_employee;
			$ecoGlob = 0;
			// TEMP USED PROPS
			$products = array();
			$products_all = array();
			// END TEMP USED PROPS
			if ($this->InitRequest()) {
				$_window = $this->GetWindow($this->_window);

        switch ($_window) {
          case 1:
            $this->GetPreviewList();
            break;
          case 2:
            $this->GetDetailItem($this->_offer);
            break;
          case 3:
            $this->ReOperation();
            break;
        }
			} else {
        $this->_ErrorCollect(99998, "Crash Module Initialize", $this->_error_def . $this->_error_exc_notfound, __file__, __class__, __FUNCTION__, __line__);         
			}
		}
		catch (exception $e) {
			$this->_ErrorCollect($e->id, $e->name, $e->message, __file__, __class__, __function__, __line__, $e);
		}
    
    if (!$this->_ErrorThrow()) {
      exit;
    }
	}
  /**
   * Return ID of window for screen
   */
	private function GetWindow($_window = "") {
		$_window_case = $this->_empty_string;

		if (!$this->IsNullOrEmpty($_window)) {
			switch ($_window) {
        case "detailItem":
          $_window_case = 2;
          break;
        case "operationWindow":
          $_window_case = 3;
          break;
        default:
          $_window_case = $this->_def_window;
          break;
			}
      return $_window_case;
    } else {
      return $this->_def_window;
		}
	}
  /**
   * Helper assigner for forgot values
   */
  private function AssignedRequireds() {
    $this->smarty->assign("_token", $this->_token);
    $this->smarty->assign("_order", $this->_order);
    $this->smarty->assign("_filter", $this->_filters);
    $this->smarty->assign("_filter_name", $this->_filters_name);
    $this->smarty->assign("filterLink", "filter=".$this->_filters);
    $this->smarty->assign("filterFullLink", "fulltext=".$this->_filters_name);
    $this->smarty->assign("_operation", $this->_operation);
    $this->smarty->assign("ecovat", $this->ecotax_global);
  }
  /**
   * Get preview list of offers
   */
  private function GetPreviewList() {
    if (!$this->IsNullOrEmpty($this->_operation)) {
      if ($this->_operation == "drop" && !$this->IsNullOrEmpty($this->_offer)) {
        $this->DropOffer($this->_offer);
      }
    }
		$list = $this->getOfferList();
		foreach ($list as &$offers) {
			if ($offers['updatedBy'] > 0) {
				$emp = $this->getEmployeeById($offers['updatedBy']);
				$offers['empName'] = $emp['firstname'] . " " . $emp['lastname'];
			} else {
				$offers['empName'] = "&nbsp;";
			}
		}
    $max_id = $this->GetLastId();
    $this->smarty->assign("_window", 1);
    $this->smarty->assign("_max_id", $max_id);
		$this->smarty->assign('pogModuleTitle', 'PDF Order Generator - Configurator');
		$this->smarty->assign(array('lst' => $list));
    
    $this->AssignedRequireds();
  }
  /**
   * Get detail view of offer
   */
  private function GetDetailItem($id = 0) {
    if ($id > 0) {
      if (!$this->IsNullOrEmpty($this->_operation)) {
        if ($this->_operation == "create" && !$this->IsNullOrEmpty($this->_offer)) {
          $this->VerifyIdRow($id, $this->employee);
        }
      }
  		$r = $this->getOfferById($_GET['offerId']);
      $this->GetProductList();
      $this->GetManufacturerList();
      $this->GetOfferItems();
			$r['ecovat'] = $this->ecotax_global;
			$this->smarty->assign('basicOffer', $r);
      $this->smarty->assign("_window", 2);
      $this->smarty->assign("_offer", $id);
      $this->addJqueryUI('ui.datepicker');
      $this->smarty->assign(array('products' => $this->products_all));
      $this->smarty->assign(array('products' => $this->products_all));
      $this->smarty->assign(array('manufacturers' => $this->manufacturers));
      $this->AssignedRequireds();
    } else {
      header("Location: index.php?controller=AdminPagex&token=" . $this->_token);
    }
  }
  
  private function ReOperation() {
    $_filtered = Tools::getValue("filterAction");

    if (!$this->IsNullOrEmpty($_filtered)) {
      $this->AssignedRequireds();
      $this->_filters = Tools::getValue("filter-select");

      if ($this->_filters != 0 || $this->_filters != "0") {
        header("Location: index.php?controller=AdminPagex&window=detailItem&offerId=" . $this->_offer . "&token=" . $this->_token . "&filter=" . $this->_filters . "&fulltext=" .$this->_filters_name);
      } else {
        $this->_filters = "";
        header("Location: index.php?controller=AdminPagex&window=detailItem&offerId=" . $this->_offer . "&token=" . $this->_token);
      }
    }
    $_generated = Tools::getValue("generating");
    if (!$this->IsNullOrEmpty($_generated)) {
      $this->AssignedRequireds();
      $this->_operation = "generating";
    }
		switch ($this->_operation) {
			case "preSave":
				$this->PrepareSave();
				break;
			case "generating":
				$this->PushPdf();
				break;
			case "recalc":
				$this->Recalculation();
				break;
			case "addAction":
				$this->AssignProducts();
				break;
			case "delItem":
				$this->DeleteItem();
				break;
		}
		if (!$this->IsNullOrEmpty($this->_filters)) {
			header("Location: index.php?controller=AdminPagex&window=detailItem&offerId=" . $this->_offer . "&token=" . $this->_token . "&filter=" . $this->_filters . "&fulltext=" .$this->_filters_name);
		} else {
			header("Location: index.php?controller=AdminPagex&window=detailItem&offerId=" . $this->_offer . "&token=" . $this->_token);
		}
  }
  private function GetOfferItems() {
			$opened_offer = $this->_offer;
			$gopProducts = $this->getListProduct($opened_offer);
			if (is_array($gopProducts) && count($gopProducts, COUNT_NORMAL) > 0) {
				$cx = 0;

				foreach ($gopProducts as &$product) {
					$id_image = Product::getCover($product['id_product']);
					$gopProducts[$cx]['image'] = $id_image['id_image'];

          if ((int)$product['voPrice'] > 0) {
            $sql = "SELECT id_image FROM "._DB_PREFIX_."product_attribute_image WHERE id_product_attribute = ".(int)$product['voPrice'].";";
            $_res = Db::getInstance()->executeS($sql);
            if ($_res != null && count($_res, COUNT_NORMAL) > 0) {
  				    $gopProducts[$cx]['image'] = $_res[0]['id_image'];
            }
          }
					$pr = Product::getUrlRewriteInformations($product['id_product']);
					$product['link_rewrite'] = $pr[0]['link_rewrite'];

					if ($product['ref'] == "" && $product['name'] == "") {
						$ref = Db::getInstance()->executeS("SELECT reference FROM " . _DB_PREFIX_ . "product WHERE id_product = " . $product['id_product'] . ";");
						$product['ref'] = $ref[0]['reference'];
						$name = Product::getProductName($product['id_product']);
						$product['name'] = $name;
						Db::getInstance()->execute("UPDATE mod_gop_products SET ref='" . $product['ref'] . "', name='" . $product['name'] . "' WHERE id_product=" . $product['id_product'] . ";");
					}
					if ($product['ref'] == "") {
						$ref = Db::getInstance()->executeS("SELECT reference FROM " . _DB_PREFIX_ . "product WHERE id_product = " . $product['id_product'] . ";");
						$product['ref'] = $ref[0]['reference'];
						Db::getInstance()->execute("UPDATE mod_gop_products SET ref='" . $product['ref'] . "' WHERE id_product=" . $product['id_product'] . ";");
					}
					if ($product['name'] == "") {
						$name = Product::getProductName($product['id_product']);
						$product['name'] = $name;
						Db::getInstance()->execute("UPDATE mod_gop_products SET name='" . $product['name'] . "' WHERE id_product=" . $product['id_product'] . ";");
					}
					$eco = Db::getInstance()->executeS("SELECT ecotax FROM " . _DB_PREFIX_ . "product WHERE id_product=" . $product['id_product'] . ";");
					$this->ecotax_global += $eco[0]['ecotax'] * $product['product_count']; // eco taxa
					$product['ecotax'] = $eco[0]['ecotax'] * $product['product_count'];
					$cx++;
				}
				// Základní informace pro sestavu do PDF výstupu

				if ($gopProducts != null) {
					$this->smarty->assign("gopList", $gopProducts);
				}
			}
  }
  private function GetProducts($id_product = 0) {
    $id_lang = $this->context->language->id;
    if ($id_product > 0) {
      $where = 'WHERE pa.`id_product` = '.(int)$id_product.' ';
    } else {
      $where = '';
    }
    $sql = 'SELECT pa.id_product_attribute, pa.id_product,pa.available_date, pa.price,
                  pa.reference, pa.ean13,
          ag.`id_attribute_group`,  agl.`name` AS group_name,     al.`name` AS attribute_name,   agl.`public_name` AS group_pname, 
                    a.`id_attribute`, ai.id_image 
                FROM `'._DB_PREFIX_.'product_attribute` pa
                '.Shop::addSqlAssociation('product_attribute', 'pa').'
                LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
                LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
                LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int)$id_lang.')
                                
                LEFT JOIN '._DB_PREFIX_.'product_attribute_image ai ON ai.`id_product_attribute` = pa.`id_product_attribute` 
                '.$where.'
                GROUP BY pa.`id_product_attribute`, ag.`id_attribute_group`
                ORDER BY pa.`id_product_attribute`';
//                 LEFT JOIN '._DB_PREFIX_.'stock_available s on (pa.id_product=s.id_product AND pa.id_product_attribute=s.id_product_attribute AND s.id_shop='.Context::getContext()->shop->id.')   ,s.quantity
    $combinations = Db::getInstance()->executeS($sql);
    return $combinations;
  }
  private function GetProductList() {
		if (!$this->IsNullOrEmpty(Tools::GetValue("filter")) && $this->_filters != 0) {
			$products = $this->searchByManufacturer($this->context->language->id, Tools::GetValue("filter"));
			if (!$this->IsNullOrEmpty($products)) {
				foreach ($products as &$pr) {
					$product = Product::getUrlRewriteInformations($pr['id_product']);
					if (!$this->IsNullOrEmpty($product) && !$this->IsNullOrEmpty($product[0]['link_rewrite'])) {
						$pr['link_rewrite'] = $product[0]['link_rewrite'];
					}
				} // end foreach
			}
		} else {
      $products = Product::getProducts($this->context->language->id, 0, 1000, "id_product", "ASC", false, true);
		}
		if (!$this->IsNullOrEmpty($products)) {
			$products_all = $products;
		}
		$cx = 0;
    $_temp = 0;
    $product_temp = array();
		if (!$this->IsNullOrEmpty($products_all)) {
			foreach ($products_all as &$product) {
				$id_image = Product::getCover($product['id_product']);
				if (!$this->IsNullOrEmpty($id_image)) {
					$products_all[$cx]['image'] = $id_image['id_image'];
					$products_all[$cx]['busprice'] = $product['ecotax'] > 0 ? round($product['price_tax_exc'] - $product['ecotax'], 1) : round($product['price_tax_exc'], 1);
          
          $combinations = $this->GetProducts($product['id_product']);
          if ($combinations != null && count($combinations, COUNT_NORMAL)) {
            foreach ($combinations as $i => $item) {
              $product_temp[$_temp] = $products_all[$cx];
              $product_temp[$_temp]['attribute'] = $item['id_product_attribute'];
              $product_temp[$_temp]['price'] += $item['price'];
              $product_temp[$_temp]['reference'] = $item['reference'];
              $product_temp[$_temp]['group_name'] = $item['group_name'];
              $product_temp[$_temp]['attribute_name'] = $item['attribute_name'];
              
              $sql = "SELECT id_image FROM "._DB_PREFIX_."product_attribute_image WHERE id_product_attribute = ".(int)$item['id_product_attribute'].";";
              $_res = Db::getInstance()->executeS($sql);
              if ($_res != null && count($_res, COUNT_NORMAL) > 0) {
                $product_temp[$_temp]['image'] = $_res[0]['id_image'];
              }
              //$product_temp[$_temp]['description_short'] = '';
              //$product_temp[$_temp]['description'] = '';
              $_temp++;
            }
          } else {
            $product_temp[$_temp] = $products_all[$cx];
            $_temp++;
          }
					$cx++;
				}
			}
      $this->products_all = $product_temp;
      //$this->PreventPrint($product_temp);
		}
  }
  private function GetManufacturerList() {
		$this->manufacturers = Db::getInstance()->executeS("SELECT id_manufacturer, name FROM " . _DB_PREFIX_ . "manufacturer WHERE active = 1;");
  }
  
	private function PrepareSave() {
		Db::getInstance()->execute("UPDATE mod_gop SET name='" . $_GET['offerName'] . "', customer='" . $_GET['customerName'] . "', currency='" . $_GET['currency'] . "', updatedBy='" . $this->employee . "', updatedTime=now() WHERE gopId=" . $_GET['offerId'] . ";");
	}

	private function DeleteItem() {
		$id = Tools::getValue("delItem");
    foreach ($_REQUEST as $key => $zero) {
      if ($zero == '0') {
    		Db::getInstance()->execute("DELETE FROM mod_gop_products WHERE gopProductId = $key;");
      }
    }
		$res = Db::getInstance()->executeS("SELECT sum(profit) as profit, sum(costs) as costs, sum(priceAfterLoss) as priceAfterLoss, sum(sumPriceAfterLoss) as priceAfterLossVat, sum((moPrice * product_count) - priceAfterLoss) as lossPrice FROM mod_gop_products WHERE gopId=" . $_GET['offerId'] . " GROUP BY gopId;");
		$res = $res[0];
		Db::getInstance()->execute("UPDATE mod_gop SET profit='" . $res['profit'] . "', priceAfterLoss='" . $res['priceAfterLossVat'] . "', priceWithoutVat='" . $res['priceAfterLoss'] . "', priceless='" . $res['lossPrice'] . "', costs='" . $res['costs'] . "', updatedBy='" . $employee . "', updatedTime=now() WHERE gopId=" . $_GET['offerId'] . ";");
	}
	private function PushPdf() {
    $this->_offer = Tools::getValue("offerId");
		$gopProducts = $this->getListProduct($this->_offer);
		if (is_array($gopProducts) && count($gopProducts, COUNT_NORMAL) > 0) {
			$cx = 0;

			foreach ($gopProducts as &$product) {
				$id_image = Product::getCover($product['id_product']);
        if ($product['voPrice'] > 0) {
          $sql = "SELECT id_image FROM "._DB_PREFIX_."product_attribute_image WHERE id_product_attribute = ".(int)$product['voPrice'].";";
          $_res = Db::getInstance()->executeS($sql);
          if ($_res != null && count($_res, COUNT_NORMAL) > 0) {
            $gopProducts[$cx]['image'] = $_res[0]['id_image'];
            $id_image['id_image'] = $_res[0]['id_image'];
          } else {
            $gopProducts[$cx]['image'] = $id_image['id_image'];
          }
        }
        
				$pr = Product::getUrlRewriteInformations($product['id_product']);
				$product['category_rewrite'] = $pr[0]['category_rewrite'];
				$product['link_rewrite'] = $pr[0]['link_rewrite'];
				$name = Product::getProductName($product['id_product']);
				if ($product['name'] == "") {
					$product['name'] = $name;
				}

				$len = strlen($id_image['id_image']);
				$idImage = $id_image['id_image'];
				$eco = Db::getInstance()->executeS("SELECT ecotax FROM " . _DB_PREFIX_ . "product WHERE id_product=" . $product['id_product'] . ";");
				$product['eco'] = $eco[0]['ecotax']; // cena

				if ($len == 1) {
					$href = "../img/p/$idImage/$idImage-home_default.jpg";
				} elseif ($len == 2) {
					$t = substr($idImage, 0, 1) . "/" . substr($idImage, 1, 1);
					$href = "../img/p/$t/$idImage-home_default.jpg";
				} elseif ($len == 3) {
					$t = substr($idImage, 0, 1) . "/" . substr($idImage, 1, 1) . "/" . substr($idImage, 2, 1);
					$href = "../img/p/$t/$idImage-home_default.jpg";
				} elseif ($len == 4) {
					$t = substr($idImage, 0, 1) . "/" . substr($idImage, 1, 1) . "/" . substr($idImage, 2, 1) . "/" . substr($idImage, 3, 1);
					$href = "../img/p/$t/$idImage-home_default.jpg";
				} elseif ($len == 5) {
					$t = substr($idImage, 0, 1) . "/" . substr($idImage, 1, 1) . "/" . substr($idImage, 2, 1) . "/" . substr($idImage, 3, 1) . "/" . substr($idImage, 4, 1);
					$href = "../img/p/$t/$idImage-home_default.jpg";
				} elseif ($len == 6) {
					$t = substr($idImage, 0, 1) . "/" . substr($idImage, 1, 1) . "/" . substr($idImage, 2, 1) . "/" . substr($idImage, 3, 1) . "/" . substr($idImage, 4, 1) . "/" . substr($idImage, 5, 1);
					$href = "../img/p/$t/$idImage-home_default.jpg";
				}
				$product['imgHref'] = $href;
				$cx++;
			}
		}
		$this->generatePdf($gopProducts);
	}

	private function AssignProducts() {
    $vals = $_GET;
		foreach ($_GET as $item => $value) {
		  $ids = explode("-", $item);
			if (is_numeric($ids[0]) && $value == 0) {
			  $item = $ids[0];
        if (count($ids) > 1) {
          $attr = $ids[1];
          $productItem = Product::getPriceStatic($item, false, $attr, 1);
  				$sql = "SELECT ecotax, reference FROM " . _DB_PREFIX_ . "product_attribute WHERE id_product=$item AND id_product_attribute = $attr;";
	   			$eco = Db::getInstance()->executeS($sql);
	 	   		if (count($eco, COUNT_NORMAL) > 0 && $eco[0]['ecotax'] > 0) {
  					$priceMo = $productItem - $eco[0]['ecotax']; // cena
  	 			} else {
  					$priceMo = $productItem;
  				}
  				$name = Product::getProductName($item, $attr);
  				$object = new Product($item, false, 2, 1, null);
  				$dsc = "";
  				$ref = "";
  				if ($object != null) {
  					$ref = $eco[0]['reference'];
  					$dsc = str_replace("\"", "\\\"", $object->description_short[1]);
  				}
        } else {
          $productItem = Product::getPriceStatic($item, false, null, 1);
  				$sql = "SELECT ecotax FROM " . _DB_PREFIX_ . "product WHERE id_product=$item;";
	   			$eco = Db::getInstance()->executeS($sql);
	 	   		if (count($eco, COUNT_NORMAL) > 0 && $eco[0]['ecotax'] > 0) {
  					$priceMo = $productItem - $eco[0]['ecotax']; // cena
  	 			} else {
  					$priceMo = $productItem;
  				}
  				$name = Product::getProductName($item);
  				$object = new Product($item, false, 2, 1, null);
  				$dsc = "";
  				$ref = "";
  				if ($object != null) {
  					$ref = $object->reference;
  					$dsc = str_replace("\"", "\\\"", $object->description_short[1]);
  				}
        }
				if ($attr == "") {
				  $attr = 0;
				}
				// $vat = TaxCore::getProductTaxRate($item);
        $sql ="SELECT count(*) as c FROM mod_gop_products WHERE id_product = $item AND voPrice = $attr AND gopId = ".$_GET['offerId'].";";
        $_res = Db::getInstance()->executeS($sql);
        if ($_res[0]['c'] == 0) {
  				$sql = "INSERT INTO mod_gop_products (`gopId`,`id_product`,`product_count`,`voPrice`,`moPrice`,`priceAfterLoss`,`lossAmount`,`sumPriceAfterLoss`,`profit`,`costs`,`descript`,`name`,`ref`) VALUES (" . $_GET['offerId'] . ", $item, 1, $attr, $priceMo, $productItem, 0, 0, 0, 0, '$dsc', '$name', '$ref');";
	 	   		//echo $sql;
	   			Db::getInstance()->execute($sql);
        }
			}
		}
    //$this->PreventPrint($vals);
	}

	private function getEmployeeById($id) {
		$res = Db::getInstance()->executeS("SELECT * FROM " . _DB_PREFIX_ . "employee WHERE id_employee = $id;");
		$res = $res != null ? $res[0] : null;
		return $res;
	}
	/**
	 * Return product list by list ID
	 */
	private function getListProduct($id = 0) {
		$result = null;
		if ($id > 0) {
			$result = Db::getInstance()->executeS("SELECT g.*, p.ecotax FROM `mod_gop_products` g INNER JOIN `" . _DB_PREFIX_ . "product` p ON g.id_product = p.id_product WHERE g.gopId = " . $id . ";");
		}
		return $result != null ? $result : array();
	}
	/**
	 * Return list of offers in main table
	 */
	private function getOfferList() {
		$where = "WHERE exportTo Like 'cat'";
		$order = "";
		//if ((!isset($_GET['order'])) && (!isset($_GET['filter'])))
		if (!$this->IsNullOrEmpty(Tools::getValue("order")) && !$this->IsNullOrEmpty(Tools::getValue("filter"))) {
			$result = Db::getInstance()->executeS("SELECT * FROM `mod_gop` WHERE `exportTo` Like 'cat' ORDER BY updatedTime Desc;");
		} else {
			if (!$this->IsNullOrEmpty(Tools::getValue("filter"))) {
				$where .= " and name Like '%" . $_GET['filter'] . "%' OR customer Like '%" . $_GET['filter'] . "%'";
			}
			if (!$this->IsNullOrEmpty(Tools::getValue("order"))) {
				$order = "ORDER BY " . $_GET['order'] . " " . $_GET['type'];
			} else {
				$order = "ORDER BY updatedTime Desc";
			}
			$result = Db::getInstance()->executeS("SELECT * FROM mod_gop " . $where . " " . $order . ";");
		}
		return $result;
	}
	private function getOfferById($id) {
		$result = Db::getInstance()->executeS("SELECT * FROM `mod_gop` WHERE gopId=$id");
		if (is_array($result)) {
			if (count($result, COUNT_NORMAL) > 0) {
				$result = $result[0];
			} else {
				header("Location: index.php?controller=AdminPagex&&token=" . $_GET['token'] . "&err=901");
			}
		} else {
			header("Location: index.php?controller=AdminPagex&&token=" . $_GET['token'] . "&err=902");
		}
		return $result;
	}
	private function DropOffer($id) {
		$result = Db::getInstance()->executeS("SELECT count(*) as c FROM mod_gop_products WHERE gopId=$id;");
		$result = $result[0]['c'];
		if ($result > 0) {
			Db::getInstance()->execute("DELETE FROM mod_gop_products WHERE gopId=$id;");
			Db::getInstance()->execute("DELETE FROM mod_gop WHERE gopId=$id;");
		} else {
			Db::getInstance()->execute("DELETE FROM mod_gop WHERE gopId=$id;");
		}
		header("Location: index.php?controller=AdminPagex&&token=" . $_GET['token']);
	}
	private function GetLastId() {
		$result = Db::getInstance()->executeS('SELECT max(gopId) as id FROM `mod_gop` g');
		if ($result[0]['id'] > 0) {
			return $result[0]['id'] + 1;
		}
		return 1;
	}
	private function VerifyIdRow($id, $employee) {
		$result = Db::getInstance()->executeS("SELECT count(*) as c FROM `mod_gop` WHERE gopId = $id;");
		if (is_array($result) && $result[0]['c'] == 0) {
			$result = Db::getInstance()->execute("INSERT INTO `mod_gop` (`gopId`,`name`,`customer`,`currency`,`exportTo`,`profit`,`priceAfterLoss`,`priceWithoutVat`,`priceless`,`costs`,`exported`,`createdBy`,`createdTime`,`updatedBy`,`updatedTime`) VALUES ('$id', '', '', 'czk', 'cat', 0.00, 0.00, 0.00, 0.00, 0.00, 0, $employee, now(), $employee, now());");
		}
	}
	private function GetProductImage($id_product) {
		$id_image = Product::getCover($id_product);
		// get Image by id
		if (sizeof($id_image) > 0) {
			$image = new Image($id_image['id_image']);
			// get image full URL
			$image_url = _PS_BASE_URL_ . _THEME_PROD_DIR_ . $image->getExistingImgPath() . ".jpg";
			$image_url = _PS_BASE_URL_ . _THEME_PROD_DIR_ . $image->getExistingImgPath() . "-home_default.jpg";
		}
	}
	private function GetCurrencies($iso) {
		$res = Db::getInstance()->executeS("SELECT * FROM " . _DB_PREFIX_ . "currency WHERE iso_code = '$iso';");
		$res = $res[0];
		return $res;
	}
  private function PreventPrint($arary) {
    echo "<pre>";
    print_r($arary);
    echo "</pre>";
    exit("System breaking");
  }
	private function generatePdf($p) {
		$res = $this->getOfferById($_GET['offerId']);
		$currency = $this->GetCurrencies($res['currency']);
		$customer = explode(";", $res['customer']);
		if (file_exists("../tools/tcpdf/tcpdf.php")) {
			require_once "../tools/tcpdf/tcpdf.php";
		}
		//$pdf = new TCPDF("P", "mm", "A4", true, "UTF-8", false);
		$gen = new PDFGeneratorCore();
		//$gen->SetHeaderMargin(0);
		$gen->WithoutHeader();
		$gen->SetMargins(105, -1, -1, false);
		$gen->SetFooterMargin(PDF_MARGIN_FOOTER);
		$gen->setFontForLang("cs");
		$gen->setEncoding("UTF-8");
		$c = '';
		$c .= '<table border="0" cellspacing="0px" cellpadding="5px" bgcolor="#AAA" height="20px">
          <tr>
            <td border="0" valign="middle">
              <font size="15pt">
              '.$res["name"].' - '.$res["customer"].'
              </font>
            </td>
          </tr>
          <tr>
            <td bgcolor="#FFF">
            
            </td>
          </tr>
        </table>';
		$date = date("d.m.Y");
		$c .= ' <table border="0" cellspacing="0" cellpadding="5px" height="20px" bgcolor="#DDD">
          <tr valign="middle">
            <td width="115px" align="center">
              <font size="9pt">Obrázek</font>
            </td>
            <td width="80px" align="center">
              <font size="9pt">Kód</font>
            </td>
            <td width="100px" align="center">
              <font size="9pt">Název</font>
            </td>
            <td width="155px" align="center">
              <font size="9pt">Popis</font>
            </td>
            <td valign="middle" width="90px" align="center">
              <font size="9pt">MO cena / ks</font>
            </td>
          </tr>
        </table>';
		//$c .= ' <table border="0" cellspacing="0" cellpadding="5px" bgcolor="#FFF" height="20px">';
		$globalEco = 0;
		$sign = $currency['sign'];
		$conv = $currency['conversion_rate'];
    $counter = 0;
    $scounter = 0;
    $rcounter = 0;
		foreach ($p as $pr) {
		  $desc = Db::getInstance()->executeS("SELECT description_short FROM "._DB_PREFIX_."product_lang WHERE id_product = ".$pr['id_product']." AND id_lang = 2 AND id_shop = 1;");
      if (count($desc, COUNT_NORMAL) > 0) {
        $pr['descript'] = $desc[0]['description_short'];
      }
			$pr['eco'] = $conv * ($pr['eco'] * $pr['product_count']);
			$globalEco += $pr['eco'];
			$moPrice = number_format(round($pr['moPrice'] * $conv, 1), 1, ".", " ");
			$stringEco = "";
			if ($pr['eco'] > 0) {
				$stringEco = '<br />Ekodaň: ' . round($pr['eco'], 1) . ' ' . $sign;
			}
      $size = getimagesize($pr['imgHref']);
      if (!$size) {
        $resize = "height=\"102px\"";
      } else {
        if ($size[0] >= $size[1]) {
          $resize = "height=\"102px\"";
        } else {
          $resize = "height=\"102px\"";
        }
      }
  		$c .= ' <table border="0" cellspacing="0" cellpadding="5px" bgcolor="#FFF" height="20px">';
			$c .= '<tr>
            <td width="115px" border="0.3pt" valign="middle" align="center">
              <font align="center" size="9pt" valign="middle"><img src="' . $pr['imgHref'] . '" '.$resize.' /></font>
            </td>
            <td valign="middle" border="0.3pt" width="80px" align="center">
              <font align="center" size="10pt" color="#000"><b>' . $pr['ref'] . '</b></font>
            </td>
            <td width="100px" border="0.3pt">
              <font align="center" size="8pt" color="#000"><a href="http://www.vybaveni-hotelu.cz/cs/' . $pr['category_rewrite'] . '/' . $pr['id_product'] . '-' . $pr['link_rewrite'] . '.html" target="_blank" style="text-decoration: none; color: #000;"><strong>' . $pr['name'] . '</b></strong></font>
            </td>
            <td width="155px" border="0.3pt">
              <font align="center" valign="top" size="8pt">' . strip_tags($pr['descript']) . '</font>
            </td>
            <td valign="middle" width="90px" border="0.3pt" align="center">
              <font align="center" size="10pt"><strong>' . $moPrice . ' ' . $sign. '</strong></font>
            </td>
          </tr>';
  		$c .= ' </table>';
      $counter++;
      $scounter++;
      if ($counter == 6) {
        //$c .= "<br />";
      }
      if ($scounter == 6) {
        $rcounter++;
      }
      if ($rcounter > 1 && $counter == $rcounter * $scounter) {
        //$c .= "<br /><br />";
        //$c .= "<br /><br /><br />";
      }
      if ($scounter == 6) {
        $c .= '<br pagebreak="true"/>';
        $scounter = 0;
      }
		}
		$xa = ((($res['priceAfterLoss'] * $conv) - $globalEco) / 121) * 100;
		$xb = ($xa + $globalEco);
		$summaryPrice = number_format(round($xb, 2), 1, ".", " ");
		$c .= ' <table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFF" height="20px">';
		$c .= '   <tr valign="middle">
          </tr>';
		$c .= ' </table>';
		//$gen->createHeader($h);
    //$gen->setPage(0);
		$gen->createContent($c);
		$f = '<font align="center" size="6">OXYGENIC s r.o., Zahradní 396, 252 25 Jinočany - Praha Západ, tel: +420 601 326 599</font> ';
		$f .= '<font align="center" size="6">E-mail: info@oxygenic.cz www.oxygenic.cz</font><br />';
		$gen->createFooter($f);
		$gen->writePageWithoutHeader();
		$gen->render('Nabidka c.' . $_GET['offerId'] . ' - ' . $res['customer'] . '.pdf');
	}

	/**
	 * Admin panel product search
	 *
	 * @param int $id_lang Language id
	 * @param string $query Search query
	 * @return array Matching products
	 */
	public function searchByManufacturer($id_lang, $query, Context $context = null) {
		if (!$context) {
			$context = Context::getContext();
		}

		$sql = new DbQuery();
		$sql->select('p.`id_product`, pl.`name`, p.`ean13`, p.`upc`, p.`active`, p.`reference`, m.`name` AS manufacturer_name, product_shop.advanced_stock_management, p.`customizable`');
		$sql->from('product', 'p');
		$sql->join(Shop::addSqlAssociation('product', 'p'));
		$sql->leftJoin('product_lang', 'pl', '
			p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl'));
		$sql->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');

		$where = 'p.`id_manufacturer` = \'' . pSQL($query) . '\'';
    if ($this->_filters_name != null) {
      $where .= ' AND (pl.`name` Like \'%'.$this->_filters_name.'%\' OR  p.`reference` Like \'%'.$this->_filters_name.'%\')';
      
    }
		$sql->orderBy('pl.`name` ASC');

		$sql->where($where);
		//$sql->join(Product::sqlStock('p', 0));

		$result = Db::getInstance()->executeS($sql);

		if (!$result) {
			return false;
		}

		$results_array = array();
		foreach ($result as $row) {
			$row['price_tax_incl'] = Product::getPriceStatic($row['id_product'], true, null, 2);
			$row['price_tax_excl'] = Product::getPriceStatic($row['id_product'], false, null, 2);
			$results_array[] = $row;
		}
		return $results_array;
	}

	private function InitRequest() {
		try {
		  $this->_token = Tools::getValue("token");
			$this->_offer = Tools::getValue("offerId");
			$this->_filters = Tools::getValue("filter");
      $this->_filters_name = Tools::getValue("fulltext");
			$this->_window = Tools::getValue("window");
      $this->_order = Tools::getValue("order");
			$this->_operation = Tools::getValue("operation");
		}
		catch (exception $e) {
			$this->_error_collector = $e->name . " : " . $e->message;
			return false;
		}
		return true;
	}

	/**
	 * Function can help assign if value is null or empty
	 * if value is null or empty, return true
	 */
	private function IsNullOrEmpty($value = null, $must_be_array = false) {
		if (is_null($value)) {
			return true;
		}
		if (is_array($value)) {
			if (count($value, COUNT_NORMAL) == 0) {
				return true;
			}
		} else {
			if ($must_be_array) {
				$this->_ErrorCollect(99999, "FTF-ObjectException", "Was await object [array] ! object is only: " . gettype($value), __file__, __class__, __function__, __line__);
				return true;
			}
		}
		if ($value === null) {
			return true;
		}
		if ($value == $this->_empty_string) {
			return true;
		}
		return false;
	}
	private function _ErrorThrow() {
		if (!$this->IsNullOrEmpty($this->_error_collector)) {
			echo "ERROR EXCEPTIONS: <br />";
			foreach ($this->_error_collector as $key => $value) {
				if (!$this->IsNullOrEmpty($value)) {
					echo $this->_ErrorReader($value);
				} else {
					echo $this->_error_def . $this->_error_exc_notfound;
				}
			}
			return false;
		} else {
			return true;
		}
	}
	private function _ErrorReader($error_object) {
		$_tmp = false;
		if (!$this->IsNullOrEmpty($error_object)) {
			foreach ($error_object as $key => $value) {
				switch ($key) {
					case $this->_message:
						$_tmp = $this->_ErrorOutput($value, "rowspan=\"5\"");
						break;
					case $this->_file:
						$_tmp = $this->_ErrorOutput($value);
						break;
					case $this->_class:
						$_tmp = $this->_ErrorOutput($value);
						break;
					case $this->_func:
						$_tmp = $this->_ErrorOutput($value);
						break;
					case $this->_line:
						$_tmp = $this->_ErrorOutput($value);
						break;
					default:
						$_tmp = $this->_ErrorOutput($value);
				}
			}
			$this->_error_output .= $this->_ErrorTable();
		} else {
			echo $this->_error_def . $this->_error_exc_notread;
		}
	}
	private function _ErrorOutput($value, $styles = "") {
		$_temp = $this->_error_output;
		if (!$this->IsNullOrEmpty($this->_error_output)) {
			$this->_error_output .= "<td $styles>" . $value . "</td>";
		} else {
			$this->_error_output = $this->_ErrorTable(true);
			$this->_error_output = "<td $styles>" . $value . "</td>";
		}
		return strlen($this->_error_output) > strlen($_temp) ? true : false;
	}
	private function _ErrorTable($is_start = false) {
		$_code = $this->_empty_string;
		if ($is_start) {
			$_code .= "<table style='width: 50%; margin: auto;'>";
			$_code .= "<thead>";
			$_code .= "<tr>";
			$_code .= "<td colspan='2' style='background-color: #AAA; color: black; font-weight: 700;'>";
			$_code .= "EXCEPTION HANDLER FALCON V HELION ONE";
			$_code .= "</td>";
			$_code .= "</tr>";
			$_code .= "</thead>";
			$_code .= "</tbody>";
		} else {
			$_code .= "<tbody>";
			$_code .= "</table>";
		}
		return $_code;
	}

	/**
	 * Create exception in module add to collector
	 */
	private function _ErrorCollect($id = 0, $name = "", $message = "", $_file = "", $_class = "", $_function = "", $_line = 0, $_e = null) {
		if ($id > 0) {
			$_tmp = array(
				$id => $name,
				$this->_message => $message,
				$this->_file => $_file,
				$this->_class => $_class,
				$this->_func => $_function,
				$this->_line = $_line);
			array_push($this->_error_collector, $_tmp);
		}
	}
	/**
	 * Clear and create new instance of error collector
	 */
	private function _ErrorCollectorClear() {
		$this->_error_collector = array();
		$this->_error_output = "";
	}
	private $_message = "message";
	private $_file = "file";
	private $_class = "class";
	private $_func = "function";
	private $_line = "line";
  
  private function _OldStyle() {

		if (Tools::getValue("order") != "") {
			$smarty->assign('_order', Tools::getValue("order"));
		} else {
			$smarty->assign('_order', "");
		}
		if (Tools::getValue("type") != "") {
			$smarty->assign('oType', Tools::getValue("type"));
		} else {
			$smarty->assign('_filter', "");
		}
		if (Tools::getValue("filter") != "") {
			$smarty->assign('_filter', Tools::getValue("filter"));
		} else {
			$smarty->assign('_filter', "");
		}
		if (Tools::getValue("filter") != "") {
			$smarty->assign('filter', Tools::getValue("filter"));
			$smarty->assign('filterLink', "&filter=" . Tools::getValue("filter"));
		} else {
			$smarty->assign('filter', "");
			$smarty->assign('filterLink', "");
		}
  }
}

?>