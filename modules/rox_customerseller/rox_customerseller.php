<?php

if (!defined('_PS_VERSION_'))
    exit;
class rox_customerseller extends Module
{
    public $default_lang;
    public $debug = true;
    private $_html = '';
    private $_box = '';
    private $_postErrors = array();
    protected static $module = false;
    public $data = null;
    protected static $statuses_array = array();
    public function __construct()
    {
        $this->name = 'rx_customerseller';
        $this->tab = 'Additional modules';
        $this->version = '1.0.0';
        $this->author = 'ROXAR Web Solution & Experience of Design';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->tabClassName = 'CustomerSeller';
        $this->tabParentName = 'AdminParentCustomer';
        $this->page = basename(__file__, '.php');
        $this->token = Tools::getAdminTokenLite('AdminModules');
        $this->path = $this->_path;
        parent::__construct();
        if (is_object(Context::getContext()->employee)) {
            $this->message = $this->version . ' *** ' . $_SERVER['SERVER_NAME'] . '//ver.' .
                _PS_VERSION_ . Context::getContext()->employee->email;
        }
        //$statuses = OrderState::getOrderStates((int)$this->context->language->id);
        //foreach ($statuses as $status) {
            //$this->$statuses_array[$status['id_order_state']] = $status['name'];
        //}

        $this->displayName = $this->l('Roxar X :: Customers Partner');
        $this->description = $this->l('Module for management custoemrs with sellers.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        if (!Configuration::get('CUSTOMERSELLER_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }
    public static function init($module)
    {
        if (self::$module == false) {
            self::$module = $module;
        }
        return self::$module;
    }
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        if (!$this->installModuleTab("Obchodníci")) {
            return false;
        }
        if (!parent::install() || !Configuration::updateValue('CUSTOMERSELLER_NAME',
            'Customer Seller Core')) {
            return false;
        }
        return true;
    }
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }
    public function installTab($parent, $class_name, $name) {
        $tab = new Tab();
        $tab->id_parent = (int)Tab::getIdFromClassName($parent);
        $tab->name = array();

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']]  = $name;
        }

        $tab->class_name = $class_name;
        $tab->module = $this->name;
        $tab->active = 1;

        return $tab->add();
    }
    private function installModuleTab($tabName)
    {
        echo $tabName;
        echo $this->tabClassName.", ";
        $id_tab = Tab::getIdFromClassName($this->tabClassName);
        if (!$id_tab) {
            print_r($id_tab);
            exit();
            @copy(_PS_MODULE_DIR_.$this->name.'/'.$this->tabClassName.'.gif', _PS_IMG_DIR_.'t/'.$this->tabClassName.'.gif');
            $tab = new Tab();
            $tab->class_name = $this->tabClassName;
            $tab->id_parent = Tab::getIdFromClassName($this->tabParentName);
            $tab->module = $this->name;
            $languages = Language::getLanguages();

            foreach ($languages as $language)
                $tab->name[$language['id_lang']] = $tabName;

            if (!$tab->add())
                return false;
        }
        exit();
        return true;
    }

    private function uninstallModuleTab()
    {
        $idTab = Tab::getIdFromClassName($this->tabClassName);
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            if (!$tab->delete())
                return false;
        }

        return true;
    }
    private function installSql() {
        $sql_query_1 = "CREATE TABLE `"._DB_PREFIX_."rox_customer_seller` (
                        `id_customer_seller` int(11) NOT NULL,
                        `id_seller` int(11) NOT NULL,
                        `date_add` datetime NOT NULL,
                        `points` decimal(15,1) NOT NULL DEFAULT '0.0',
                        `points_used` decimal(15,1) NOT NULL DEFAULT '0.0',
                        `status` int(5) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id_customer_seller`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql_query_1);
        $sql_query_2 = "CREATE TABLE `"._DB_PREFIX_."rox_customer_seller_customers` (
                        `id_customer_seller_customer` int(11) NOT NULL AUTO_INCREMENT,
                        `id_seller` int(11) NOT NULL,
                        `id_customer` int(11) NOT NULL,
                        `points_count` decimal(15,1) NOT NULL DEFAULT '0.0',
                        PRIMARY KEY (`id_customer_seller_customer`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;";
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql_query_2);
        $sql_query_3 = "CREATE TABLE `"._DB_PREFIX_."rox_customer_seller_orders` (
                        `id_customer_seller_order` int(11) NOT NULL AUTO_INCREMENT,
                        `id_customer` int(11) NOT NULL,
                        `id_order` int(11) NOT NULL,
                        `price` decimal(15,2) NOT NULL DEFAULT '0.00',
                        `points` decimal(15,1) NOT NULL DEFAULT '0.0',
                        PRIMARY KEY (`id_customer_seller_order`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;";
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql_query_3);
    }
    private function uninstallSql() {
        $sql_query_1 = "DROP TABLE `"._DB_PREFIX_."rox_customer_seller`;";
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql_query_1);
        $sql_query_2 = "DROP TABLE `"._DB_PREFIX_."rox_customer_seller_customers`;";
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql_query_2);
        $sql_query_3 = "DROP TABLE `"._DB_PREFIX_."rox_customer_seller_orders`;";
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql_query_2);
    }
    private function _postValidation()
    {
        if (Tools::isSubmit('submitEmailForm')) {
        }
    }
    private function _postProcess()
    {
    }
    public function getContent()
    {
        $this->init($this);
        $validate = true;
        $this->context->controller->addCss(__file__, '/views/css/admin_back.css');
        $this->context->controller->addJS(__file__, 'js/function.js');
        /*if (Tools::isSubmit('submitWeightForm') || Tools::isSubmit('submitRequestData')) {
        $this->_postValidation();
        if (!sizeof($this->_postErrors))
        $this->_postProcess();
        }*/
        // controller=AdminOrders&id_order=8431&vieworder&token=6bb75fee04b927cfeebdcadc454a9cd7
        $current_url = $this->context->link->getAdminLink('AdminCustomers') . '&updatecustomer' .
            '&id_customer=';
        $form_url = $this->context->link->getAdminLink('CustomerSeller_Manager');

        $this->data = $this->getValueList();
        $this->context->smarty->assign(array(
            'name' => $this->displayName,
            'module_name' => $this->name,
            'version' => $this->version,
            //'displayConf'		 => $this->displayConf,
            'nbErrors' => sizeof($this->_postErrors),
            '_postErrors' => $this->_postErrors,
            '_box' => $this->_box,
            'idTab' => Tools::getValue('idTab'),
            'languages' => Language::getLanguages(),
            'currencies' => Currency::getCurrencies(),
            'themes' => Theme::getThemes(),
            'token' => Tools::getAdminTokenLite('AdminModules'),
            'my_token' => Tools::getValue('token'),
            'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ .
                'modules/' . $this->name . '/',
            'data' => $this->data));
        $this->smarty->assign("datacount", count($this->data, COUNT_NORMAL));
        $this->smarty->assign("link", $current_url);
        $this->smarty->assign("formlink", $form_url);
        //$this->_html = $this->renderPaymentList();
        //return $this->_html;
        return $this->display(__file__, '/views/templates/back/admin_main.tpl');
    }
    public function getFieldList()
    {
        return array(
            'id_customer' => array(
                'title' => $this->$module->l('ID'),
                'type' => 'text',
                ),
            'firstname' => array(
                'title' => $this->$module->l('Jméno'),
                'type' => 'text',
                ),
            'lastname' => array(
                'title' => $this->$module->l('Pøíjmení'),
                'type' => 'text',
                ),
            'status' => array(
                'title' => $this->$module->l('Stav'),
                'type' => 'switch',
                'name' => 'active',
                'required' => false,
                'class' => 't',
                'is_bool' => true,
                'values' => $this->getOnOffValues('active')),
            'points' => array(
                'title' => $this->$module->l('Poèet bodù'),
                'align' => 'text-right',
                'type' => 'decimal'),
                //'callback' => 'getOrderPoints',
                //'badge_success' => true),
            'date_last_order' => array(
                'title' => $this->$module->l('Date'),
                'align' => 'text-right',
                'type' => 'datetime',
                'filter_key' => 'cs!date_add'));
    }
	public function getOnOffValues($attr_name)
	{
		return array(
			array(
				'id' => $attr_name . '_on',
				'value' => 1,
				'label' => $this->l('Enabled')
			),
			array(
				'id' => $attr_name . '_off',
				'value' => 0,
				'label' => $this->l('Disabled')
			)
		);
	}
    public function getValueList()
    {
        $id = Tools::getValue('id');
        $ref = Tools::getValue('ref');
        $status = Tools::getValue('status');
        $date_from = Tools::getValue('from');
        $date_to = Tools::getValue('to');
        $price = Tools::getValue('price');
        $payment = Tools::getValue('payment');
        $page = 1;
        if (Tools::getValue("page")) {
            $page = Tools::getValue("page");
        }
        $context = Context::getContext();
        $where = '';
        $sql = '
            SELECT cs.id_seller, cs.status, cp.firstname, cp.lastname, sum(o_cs.price) as price_sum, sum(o_cs.points) as points_sum, max(o.date_add) as date_add_max, cs.points_used,
            count(o.id_order) as ord_count, count(cx.id_customer) as cust_count
            FROM `' . _DB_PREFIX_ . 'rox_customer_seller` cs
            INNER JOIN `' . _DB_PREFIX_ . 'rox_customer_seller_customers` c_cs ON (cs.id_seller = c_cs.id_seller)
            INNER JOIN `' . _DB_PREFIX_ .'rox_customer_seller_orders` o_cs ON (o_cs.id_customer = c_cs.id_customer)
            LEFT JOIN `' . _DB_PREFIX_ .'orders` o ON (o.id_order = o_cs.id_order)
            INNER JOIN `' . _DB_PREFIX_ .'customer` cp ON (cs.id_seller = cp.id_customer)
            LEFT JOIN `' . _DB_PREFIX_ .'customer` cx ON (cx.id_customer = c_cs.id_customer)';
        $where .= strlen($id) > 0 ? 'o.id_order = ' . $id : '';
        $where .= strlen($status) > 0 && strlen($where) > 0 ? ' AND ' : '';
        $where .= strlen($status) > 0 ? 'o.current_state = "' . $status . '"' : '';
        $where .= strlen($date_from) > 0 && strlen($where) > 0 ? ' AND ' : '';
        $where .= strlen($date_from) > 0 ? 'o.date_add >= "' . $date_from . '"' : '';
        $where .= strlen($date_to) > 0 && strlen($where) > 0 ? ' AND ' : '';
        $where .= strlen($date_to) > 0 ? 'o.date_add <= "' . $date_to . '"' : '';
        $where .= strlen($price) > 0 && strlen($where) > 0 ? ' AND ' : '';
        $where .= strlen($price) > 0 ? 'o.total_paid_tax_incl <= ' . $price : '';
        //$where .= strlen($payment) > 0 ? ' AND ' : '';
        $sql .= strlen($where) > 0 ? ' WHERE ' : '';
        $sql .= $where;
        $sql .= 'GROUP BY cs.id_seller, cp.firstname, cp.lastname, cs.status
            ORDER BY cs.`id_seller` ASC'; // GROUP BY id_order
        $sql .= strlen($where) > 0 ? '' : ' LIMIT 50';
        $data = Db::getInstance()->ExecuteS($sql);
        $_count = 0;
        if ($data != null && count($data) > 0) {
            foreach ($data as $row) {
                $resource['items'] = null;
                $resource['id_item'] = $row['id_seller'];
                $resource['add'] = $row['date_add_max'];
                $resource['points'] = $row['points_sum'];
                $resource['price'] = $row['price_sum'];
                $resource['points_used'] = $row['points_used'];
                $resource['paid'] = $this->setOrderCurrency($row['total_paid_tax_incl'], $row['id_order'], $row['id_currency']);
                $resource['customer'] = $row['id_customer'];
                //$resource['payment'] = $row['payment'];
                $resource['fullname'] = $row['firstname']." ".$row['lastname'];
                $sq = 'SELECT id_order_detail, product_id, product_reference, product_quantity, unit_price_tax_incl, total_price_tax_incl, product_name FROM ' .
                    _DB_PREFIX_ . 'order_detail WHERE id_order = ' . $row['id_order'] . ';';
                $detail = Db::getINstance()->executeS($sq);
                foreach ($detail as $pr) {
                    $resource['items'][$pr['id_order_detail']]['id_product'] = $pr['product_id'];
                    $resource['items'][$pr['id_order_detail']]['reference'] = $pr['product_reference'];
                    $resource['items'][$pr['id_order_detail']]['quantity'] = $pr['product_quantity'];
                    $resource['items'][$pr['id_order_detail']]['unit_price'] = $this->setOrderCurrency($pr['unit_price_tax_incl'], $row['id_order'], $row['id_currency']);
                    $resource['items'][$pr['id_order_detail']]['total_price'] = $this->setOrderCurrency($pr['total_price_tax_incl'], $row['id_order'], $row['id_currency']);
                    $resource['items'][$pr['id_order_detail']]['name'] = $pr['product_name'];
                }
                $resources[$_count] = $resource;
                $_count++;
            }
        }
        return isset($resources) ? $resources : null;
    }
    public function GetStatusProcess($id_status)
    {
        $order_state = new OrderState($id_status);
        return $order_state;
    }
    public static function isNumeric($value)
    {
        return preg_match('/^[0-9]{1,9}$/', $value);
    }
    private function format_phone_number($phonenumber)
    {
        if (preg_match('/^(((?:\+|00)?420)?[67][0-9]{8}|((?:\+|00)?421|0)?9[0-9]{8})$/',
            preg_replace('/\s+/', '', $phonenumber)))
            $phonenumber = trim($phonenumber);

        return $phonenumber;
    }
    public function setOrderCurrency($echo, $tr, $curr)
    {
        return Tools::displayPrice($echo, (int)$curr);
    }
}
